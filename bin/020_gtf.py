import re
import argparse
import sys
import os

from mysql_tables import create_gff_table, load_table

sys.path.append(os.path.abspath('../'))
from data import *

def multiply(a, b):
  return 3, 4

def get_gene_name(astring):
  gene_name = ''
  if 'gene_name' not in astring:
    return gene_name
  try:
    gene_name = re.search('gene_name "([0-9A-Za-z_\-\.\_\$]*)";', astring).group(1)
  except Exception as e:
    print(astring, e)
  return gene_name

def extract_gene_id_name(astring):
  try:
    #print(astring)
    gene_id = re.search('gene_id "([0-9A-Za-z_\-\.\_]*)";', astring).group(1)
    #print(gene_id, gene_name)
  except Exception as e:
    print(e)
    print(astring)
  return gene_id, get_gene_name(astring)

def calc_gene_length(gene_start, gene_end):
  out = int(gene_end) - int(gene_start) + 1
  return str(out)

def extract_gene_info(cols):
  chr = cols[0]
  gene_start = cols[3]
  gene_end = cols[4]
  gene_length = calc_gene_length(gene_start, gene_end)
  gene_strand = cols[6]
  gene_id, gene_name = extract_gene_id_name(cols[8])
  return gene_id, chr, gene_start, gene_end, gene_length, gene_strand, gene_name

def parse_gff(filename, outfile):
  fh = open(filename)
  out = open(outfile, 'w')
  for rec in fh:
    if rec[0] == '#':
      continue
    rec = rec.rstrip("\r|\n")
    cols = rec.split("\t")
    if cols[2] != 'gene':
      continue
    if 'scaffold' in cols[0]:
      continue
    #print(">> ", rec)
    #print("\t".join(extract_gene_info(cols)))
    out.write("\t".join(extract_gene_info(cols)) + "\n")

  fh.close()
  out.close()
  
if __name__ == '__main__':
  #filename = 'data/Homo_sapiens.GRCh37.75.gtf'
  #filename = 'data/test'
  #filename = 'data/Zea_mays.AGPv3.31.gtf'
  parse_gff('../data/' + reference_gtf, 'data/' + reference_gtf + '.parse')
  create_gff_table()
  load_table('gfftest', 'data/' + reference_gtf + '.parse')

