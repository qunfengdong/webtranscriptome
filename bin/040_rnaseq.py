#from helpers import *
#from mysql_create_table import load_table, create_rnaseq_table, create_rnaseq_expression_table
#from connect_mysql import execute
from mysql_tables import create_rnaseq_table, create_rnaseq_expression_table, load_table

import shutil
import os
import re
import subprocess

def delete_first_line(filename):
	cmd = 'sed \'1d\' '+ filename +' > file.txt; mv file.txt '+ filename
	#subprocess.call(cmd)
	os.system(cmd)

def parse_infinity(filename):
	cmd = 'sed -i "s/\\t-inf\\t/\\t-100000\\t/g;s/\\tinf\\t/\\t100000\\t/g" ' + filename
	os.system(cmd)

def output_R_file(hash, filename):
	fh = open(filename + ".Rinput", "w")
	b = next (iter (hash.values()))
	exps = list(b.keys())
	#print(exps)
	fh.write("\t" + "\t".join(exps) + "\n")
	for geneid in hash.keys():
		if "gene_id" in geneid:
			continue
		st = []
		for exp in exps:
			st.append(hash[geneid][exp])
		fh.write(geneid + "\t" + "\t".join(st) + "\n")
	fh.close()

def create_R_file(filename):
	fh = open(filename, "r")
	gene_exp = dict()
	for line in fh:
		line = line.rstrip("\r|\n")
		cols = line.split("\t")
		gene_exp[cols[1]] = {}
		gene_exp[cols[1]][cols[4]] = cols[7]
		gene_exp[cols[1]][cols[5]] = cols[8]
		#print(line)
	fh.close()
	output_R_file(gene_exp, filename)
	b = next (iter (gene_exp.values()))
	exps = list(b.keys())
	return exps

def update_rnaseq(expslist, rnaseq_combine_file):
	php_dir = os.path.abspath(os.path.join(os.pardir, 'php'))
	print(php_dir)
	expfile = os.path.join(php_dir, 'profile', 'experiments.php')
	with open(expfile, "w") as out:
		for experiment in expslist:
			out.write("<label><input type='checkbox' id='list' value='"+ experiment +"'>"+ experiment +"</label>\n")
	expfile = os.path.join(php_dir, 'profile', 'experiments.options.php')
	with open(expfile, "w") as out:
		for experiment in expslist:
			out.write("<option value='"+ experiment +"'>"+ experiment +"</option>\n")
	expfile = os.path.join(php_dir, 'brew', 'experiments.html')
	target = os.path.join(php_dir, 'brew', 'rnaseq.txt')
	shutil.copyfile(rnaseq_combine_file, target)
	with open(expfile, "w") as out:
		out.write(target + "\n")

def get_first_line(filename):
	fh = open(filename, "r")
	fline = fh.readline()
	fline = fline.rstrip("\r|\n")
	exps = fline.split("\t")
	fh.close()
	return exps[1:]

def combine_rnaseq(files):
	hash = {}
	expslist = []
	for file in files:
		fh = open(file + '.Rinput', "r")
		fline = fh.readline()
		fline = fline.rstrip("\r|\n")
		exps = fline.split("\t")
		expslist += exps[1:]
		for line in fh:
			line = line.rstrip("\r|\n")
			cols = line.split("\t")
			#print(exps)
			#print(cols)
			if cols[0] in hash:
				hash[cols[0]].update(dict(zip(exps[1:], cols[1:])))
			else:
				hash[cols[0]] = dict(zip(exps[1:], cols[1:]))
		fh.close()
	print(expslist)
	return expslist, hash

def rnaseqcombine(expslist, hash, filename):
	fh = open(filename, "w")
	fh.write('\t' + '\t'.join(expslist) + '\n')
	for id in hash:
		fh.write(id)
		for exp in expslist:
			fh.write("\t" + hash[id][exp])
		fh.write("\n")
	fh.close()

if __name__ == '__main__':
	rnaseq_dir = os.path.abspath(os.path.join(os.pardir, 'data_rnaseq'))
	files = [f for f in os.listdir(rnaseq_dir) if re.match(r'.*\.diff', f)]
	outfiles = []
	for filename in files:
		print(filename)
		## define original source
		src = os.path.join(rnaseq_dir, filename)
		## define path of destination
		dest = os.path.abspath(os.path.join('data', filename))
		## copy source to destination
		shutil.copyfile(src, dest)
		## delete the first line
		delete_first_line(dest)
		## remove inf and -inf and replace it with 100000
		parse_infinity(dest)
		## Make a R file with only the experiments
		a = create_R_file(dest)
		## Get the experiments list
		print(a)
		## Collect the R file list
		outfiles.append(dest)

	create_rnaseq_table()
	for file in outfiles:
		load_table('rnaseq', file)

	expslist, hash = combine_rnaseq(outfiles)
	rnaseq_combine_file = os.path.abspath(os.path.join('data', 'rnaseq.combine'))
	rnaseqcombine(expslist, hash, rnaseq_combine_file)
	update_rnaseq(expslist, rnaseq_combine_file)
	delete_first_line(rnaseq_combine_file)
	create_rnaseq_expression_table(expslist)
	load_table('rnaseq_expression', rnaseq_combine_file)


