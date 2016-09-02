import re
import sys
import os
import shutil

from mysql_tables import create_go_table, load_table
from mysql_connection import execute, mysqlaccess

sys.path.append(os.path.abspath('../'))
from data import *

def delete_first_line(filename):
	cmd = 'sed \'1d\' '+ filename +' > file.txt; mv file.txt '+ filename
	#subprocess.call(cmd)
	os.system(cmd)

def load_gff_file():
	create_go_table()
	src = os.path.abspath(os.path.join(os.pardir, 'data', reference_go))
	dest = os.path.abspath(os.path.join('data', reference_go))
	shutil.copyfile(src, dest)
	delete_first_line(dest)
	load_table('gotable', dest)

def generate_association():
	dest = os.path.abspath(os.path.join('data', reference_go))
	hash = {}
	with open(dest, "r") as infile:
		for line in infile:
			line = line.rstrip("\r|\n")
			cols = line.split("\t")
			if not cols[1]:
				continue
			if cols[0] not in hash:
				hash[cols[0]] = []
			hash[cols[0]].append(cols[1])
	newdest = dest + ".association"
	with open(newdest, "w") as outfile:
		for id in hash:
			outfile.write(id+ "\t" + ";".join(hash[id]) +"\n")
	newpath = os.path.abspath(os.path.join(os.pardir, 'php', 'data', reference_go + '.association'))
	shutil.copyfile(newdest, newpath)

def generate_population():
	sql = "select gene_id from gff"
	result = execute(sql)
	newpath = os.path.abspath(os.path.join(os.pardir, 'php', 'data', reference_go + '.population'))
	print(newpath)
	fh = open(newpath, "w")
	for row in result:
		fh.write(row['gene_id'] + "\n")
	fh.close()

if __name__ == '__main__':
	#load_gff_file()
	#generate_association()
	generate_population()