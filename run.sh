#!/usr/bin/env bash
#pip install numpy
#pip install biopython
#sudo pip install goatools
#sudo apt-get install python-pygraphviz

#sudo apt-get install r-cran-rjson
#sudo apt-get install r-cran-brew
#sudo apt-get install r-cran-rmysql

cd bin
cp -r php ../.
#python3 010_genome.py
#python3 020_gtf.py
#python3 021_descriptions.py
#python3 022_pathway.py
#python3 023_goterms.py
python3 024_ortholog.py
#python3 030_user.py
#python3 040_rnaseq.py
#python3 050_chipseq.py
#python3 060_genpromoters.py
#python3 070_programs.py
#python3 080_genmotifs.py
cd ..

