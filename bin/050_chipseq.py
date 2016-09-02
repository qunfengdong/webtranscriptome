import os
import re
import shutil
from mysql_tables import create_chipseq, load_table
from mysql_connection import execute
#from connect_mysql import execute
#from helpers import get_path

def create_query(tname):
	fh = open("data/chipseq_query.php", "w")
	q = "select gff.gene_id as id, " \
			"gff.chr, " \
			"gff.start as genestart, " \
			"gff.end as geneend, " \
			"gff.length, " \
			+ tname + ".start, " \
			+ tname + ".end, " \
			+ tname + ".len, " \
			+ tname + ".summit, " \
			+ tname + ".tags, " \
			+ tname + ".pavalue, " \
			+ tname + ".foldenrichment, " \
			+ tname + ".fdr, " \
			"("+ tname +".summit - gff.start) as diff " \
			"FROM "+ tname +", gene_gff " \
			"WHERE "+ tname +".chr = gff.chr " \
			"AND "+ tname +".summit >= (gff.start - $offset) " \
			"AND "+ tname +".summit <= (gff.end + $offset) "
	print(q)

def create_chipseq_table(filename):
	tname = os.path.basename(filename)
	tname = tname.replace('_peaks.xls', '')
	tname = tname.replace('.', '_')
	print(tname)
	#create_chipseq(tname)
	create_query(tname)

def get_stend_pos_genes(chr):
	sql = 'select * from RA1_all_high_confidence where chr like "' + chr + '"'
	out = execute(sql)
	return out

def get_peak_seqs():
	chrs = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'Mt', 'Pt']
	for chr in chrs:
		result = get_stend_pos_genes(chr)
		line, seq = get_header_seq("reference_sequence."+ chr +".fasta")
		for row in result:
			st = row['start']
			en = row['end']
			print(row['chr'], st, en, seq[st:en])

def get_header_seq(filename):
		fh = open(get_path(filename), "r")
		line = fh.readline()
		seq = fh.readline()
		fh.close
		return line, seq

def delete_first_line(filename):
	cmd = 'sed \'1d\' '+ filename +' > file.txt; mv file.txt '+ filename
	#subprocess.call(cmd)
	os.system(cmd)

def parse_comma(filename):
	cmd = 'sed -i "s/,/\\t/g" ' + filename
	os.system(cmd)

def parse_exp(filename, modfilename):
	cmd = 'sed -i "s/,/\\t/g;s/$/\\t'+modfilename+'/g" ' + filename
	os.system(cmd)

if __name__ == '__main__':
	filename = '../data_chipseq/RA1_all_high_confidence_peaks.xls'
	chipseq_dir = os.path.abspath(os.path.join(os.pardir, 'data_chipseq'))
	files = [f for f in os.listdir(chipseq_dir) if re.match(r'.*\.csv', f)]
	for filename in files:
		## define original source
		src = os.path.join(chipseq_dir, filename)
		## define path of destination
		dest = os.path.abspath(os.path.join('data', filename))
		print(filename, src, dest)
		## copy source to destination
		shutil.copyfile(src, dest)
		## Create table and load it
		modfilename = filename.replace('.csv', '')
		modfilename = modfilename.replace('\.', '')
		create_chipseq()
		## delete the first line
		delete_first_line(dest)
		parse_comma(dest)
		parse_exp(dest, modfilename)
		load_table('chipseq', dest)

	#create_chipseq_table(filename)
	#get_peak_seqs()