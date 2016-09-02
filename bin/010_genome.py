import os
import sys

sys.path.append(os.path.abspath('../'))
from data import *

print(reference_genome)

from Bio import SeqIO
handle = open('../data/' + reference_genome, "rU")
for record in SeqIO.parse(handle, "fasta"):
    print(record.id)
handle.close()