import os
import sys
import shutil

sys.path.append(os.path.abspath('../'))
from data import *

def setup_meme():
	meme = os.path.abspath(os.path.join(os.pardir, 'php', 'sequence', 'memeprogram.php'))
	with open(meme, 'w') as fh:
		fh.write("<?php\n$memeprogram='"+ program_meme +"';\n?>")

def setup_blastdb():
	ref_dir = os.path.abspath(os.path.join(os.pardir, 'data'))
	src = os.path.join(ref_dir, reference_genome)
	target = os.path.abspath(os.path.join(os.pardir, 'php', 'data', reference_genome))
	#shutil.copyfile(src, target)
	cmd = program_blast + '/makeblastdb -in '+ src +' -dbtype nucl -out '+ target
	os.system(cmd)

def setup_blast_program():
	meme = os.path.abspath(os.path.join(os.pardir, 'php', 'sequence', 'blastprogram.php'))
	target = os.path.join('data', reference_genome)
	with open(meme, 'w') as fh:
		fh.write("<?php\n$blastprogram='"+ program_blast +"';\n$blastdb='"+ target +"'\n;?>")

def setup_goatools():
	goset = os.path.abspath(os.path.join(os.pardir, 'php', 'settings', 'goatools.php'))
	target = os.path.join('data', reference_go)
	with open(goset, 'w') as fh:
		fh.write("<?php\n")
		fh.write("$goprogram='"+ program_gotools +"';\n")
		fh.write("$goassociation='"+ target +".association';\n")
		fh.write("$gopopulation='"+ target +".population';\n")
		fh.write("?>\n")

if __name__ == '__main__':
	#setup_meme()
	#setup_blastdb()
	#setup_blast_program()
	setup_goatools()