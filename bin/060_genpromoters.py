import re
from helpers import get_path, run_system_cmd
from connect_mysql import execute
from mysql_create_table import create_promoter_table, load_table

class Promoters:

	def __init__(self, filename):
		self.filename = filename
		#self.chrs = []
		self.chrs = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'Mt', 'Pt']

	def save_chromosome(self, chr, seq):
		print(chr)
		self.chrs.append(chr)
		#fh = open(get_path("reference_sequence." + chr + ".fasta"), "w")
		#fh.write(">"+chr+"\n"+seq+"\n")
		#fh.close()



	def split_fasta_file(self):
		fh = open(get_path(self.filename), "r")
		pre = ''
		seq = ''
		for line in fh:
			line = line.rstrip("\r|\n")
			if ">" in line:
				chr = re.search('>chr([0-9A-Za-z]*) ', line).group(1)
				#print(pre, chr, line)
				if seq != '' and pre != chr:
					#print(">", pre, "\n", seq, "\n")
					self.save_chromosome(pre, seq)
					pre = chr
					seq = ''
				else:
					pre = chr
			else:
				seq += line
		#print(">", pre, "\n", seq, "\n")
		self.save_chromosome(pre, seq)

	def get_chrs(self):
		return self.chrs

	def get_header_seq(self, filename):
		fh = open(get_path(filename), "r")
		line = fh.readline()
		seq = fh.readline()
		fh.close
		return line, seq

	def get_stend_pos_genes(self, chr):
		sql = 'select * from gff where chr like "' + chr + '"'
		out = execute(sql)
		return out

	def mask_genes(self):
		for chr in self.get_chrs():
			#fh = open(get_path("reference_sequence." + chr + ".fasta"), "r")
			#line = fh.readline()
			#seq = fh.readline()
			#fh.close
			#print(chr, line, len(seq))
			line, fseq = self.get_header_seq("reference_sequence." + chr + ".fasta")
			rseq = fseq
			print(chr, line, len(fseq))
			out = self.get_stend_pos_genes(chr)
			#print(seq[335517:336000])
			for row in out:
				#print(row['start'], row['end']);
				st = int(row['start']) - 1
				en = int(row['end'])
				xing = "X" * (en - st)
				if row['strand'] == '+':
					fseq = fseq[:st] + xing + fseq[en:]
				if row['strand'] == '-':
					rseq = rseq[:st] + xing + rseq[en:]
				#print(seq[335517:336000])
			fh = open(get_path("reference_sequence." + chr + ".masked.forward.fasta"), "w")
			fh.write(line + fseq)
			fh.close()
			fh = open(get_path("reference_sequence." + chr + ".masked.reverse.fasta"), "w")
			fh.write(line + rseq)
			fh.close()

	def toleft(self, seq, pos, pre):
		st = int(pos) - pre - 1
		en = int(pos) - 1
		if(st < 0):
			st = 0
		pro = seq[st:en]
		pro = re.sub(r".*X", "", pro)
		return str(st), str(en), pro

	def toright(self, seq, pos, pre):
		st = int(pos)
		en = int(pos) + pre
		if(en > len(seq)):
			en = len(seq) - 1
		pro = seq[st:en]
		pro = re.sub(r"X.*", "", pro)
		return str(st), str(en), pro

	def get_promoters(self):
		fh = open(get_path("promoters.sql.10000.txt"), "w")
		for dir in ["forward", "reverse"]:
			for chr in self.get_chrs():
				print(chr)
				line, seq = self.get_header_seq("reference_sequence." + chr + ".masked."+dir+".fasta")
				out = self.get_stend_pos_genes(chr)
				for row in out:
					if row['strand'] == '+' and dir == "forward":
						st, en, pro = self.toleft(seq, row['start'], 10000)
						fh.write(row['gene_id'] +"\t"+ chr +"\t"+ st +"\t"+ en +"\tupstream\t"+ pro +"\n")
						st, en, pro = self.toright(seq, row['end'], 10000)
						fh.write(row['gene_id'] +"\t"+ chr +"\t"+ st +"\t"+ en +"\tdownstream\t"+ pro +"\n")
					if row['strand'] == '-' and dir == "reverse":
						st, en, pro = self.toright(seq, row['end'], 10000)
						fh.write(row['gene_id'] +"\t"+ chr +"\t"+ st +"\t"+ en +"\tdownstream\t"+ pro +"\n")
						st, en, pro = self.toleft(seq, row['start'], 10000)
						fh.write(row['gene_id'] +"\t"+ chr +"\t"+ st +"\t"+ en +"\tupstream\t"+ pro +"\n")
		fh.close()

	def split_promoters(self):
		fh = open(get_path('promoters.sql.10000.txt'), 'r')
		fh_5000 = open(get_path('promoters.sql.5000.txt'), 'w')
		fh_2000 = open(get_path('promoters.sql.2000.txt'), 'w')
		fh_1000 = open(get_path('promoters.sql.1000.txt'), 'w')
		fh_500 = open(get_path('promoters.sql.500.txt'), 'w')

		for line in fh:
			line = line.rstrip("\r|\n")
			cols = line.split("\t")
			astring = "\t".join(cols[0:5])
			print(astring)
			fh_5000.write(astring + "\t" + cols[5][0:5001] + "\n")
			fh_2000.write(astring + "\t" + cols[5][0:2001] + "\n")
			fh_1000.write(astring + "\t" + cols[5][0:1001] + "\n")
			fh_500.write(astring + "\t" + cols[5][0:501] + "\n")
		fh_5000.close()
		fh_2000.close()
		fh_1000.close()
		fh_500.close()
		fh.close()

	def load_promoters_to_mysql(self):
		ls = ['10000', '5000', '2000', '1000', '500']
		for l in ls:
			filename = 'promoters.sql.'+ l +'.txt'
			tname = "promoter_" + l
			print(filename, tname)
			#create_promoter_table(tname)
			load_table(tname, filename)

if __name__ == '__main__':
	#filename = 'test.fasta'
	filename = 'Zea_mays.AGPv3.21.dna.chromosome.fa'
	a = Promoters(filename)
	#a.split_fasta_file()
	print(a.get_chrs())
	#a.mask_genes()
	#a.get_promoters()
	#a.split_promoters()
	a.load_promoters_to_mysql()
	#split_fasta_file(filename)