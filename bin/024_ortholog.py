import re
import sys
import os
import shutil
import subprocess

sys.path.append(os.path.abspath('../'))
from data import *


from mysql_tables import create_ortholog, load_table

def get_first_line():
	src = os.path.abspath(os.path.join(os.pardir, 'data', reference_ortholog))
	cmd = 'head -n 1 '+ src
	p = subprocess.Popen(["head", "-n", "1", src], stdout=subprocess.PIPE, stderr=subprocess.PIPE)
	out, err = p.communicate()
	#a = os.system(cmd)
	line = out.decode("utf-8")
	line = line.rstrip("\r|\n")
	ortho = []
	for col in line.split("\t"):
		if('gene_id' in col):
			continue
		if('_desc' in col):
			continue
		ortho.append(col)
	a = os.path.abspath(os.path.join(os.pardir, 'php', 'settings', 'ortholog.php'))
	php = "<?php\n$ortholog = ['{0}'];\n?>".format("','".join(ortho))
	with open(a, "w") as outfile:
		outfile.write(php)
	create_ortholog(ortho)

if __name__ == '__main__':
	#get_first_line()
	a = os.path.abspath(os.path.join(os.pardir, 'data', reference_ortholog))
	load_table('ortholog', a)
