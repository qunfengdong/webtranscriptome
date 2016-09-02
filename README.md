## PREREQUISITES

### Essential tools

- Linux
- Apache
- MySQL
- PHP
- Python 3 or higher

### Additional packages

- rApache (http://rapache.net/)
- MEME suite (http://meme-suite.org/index.html)
- BLAST package (ftp://ftp.ncbi.nlm.nih.gov/blast/executables/blast+/LATEST/)
- Goatools (https://github.com/tanghaibao/goatools)
- JBrowse (http://jbrowse.org/)

## CONFIGURATION

Download the package from Github
```
$ wget <github>
$ git clone <github>
```

### Reference genome and GFF file

Update the following details in the file **data.py**

The login details
```
## MySQL details
database_host = ''
database_user = ''
database_password =  ''
database_db = ''
```

### The reference genome details
```
reference_genome = ''
reference_gtf = ''
```

Note:
- the chromosome information in reference genome is similar to the gtf file
- In the GTF file, the gene id in column 9, must be in the format of **gene_id "<id>";**

## Additional files

### Gene descriptions
```
reference_description = 'genedesc.txt'
```

The format of the file must be 2 columns, separated by tabs.
```
#gene_id	description
<geneid>	1-aminocyclopropane-1-carboxylate oxidase
<geneid>	Uncharacterized protein
```

### Pathway information
```
reference_pathway = 'pathway.txt'
```

The format of the file must be 7 columns, separated by tabs
```
#gene_id	enzyme_name	reaction_id	reaction_name	ec	pathway_id	pathway_name
<geneid>	Polygalacturonate 4-alpha-galacturonosyltransferase	2.4.1.43-RXN		EC-2.4.1.43	PWY-1061	homogalacturonan biosynthesis
<geneid>	H(+)-transporting two-sector ATPase	ATPSYN-RXN		EC-3.6.3.14	PWY-6126	adenosine nucleotides de novo biosynthesis
```

### GO information
```
reference_go = 'go.txt'
```

The format of the file must be 3 columns, separated by tab
```
#gene_id	go_term	go_description
<geneid>	GO:0046872	metal ion binding
<geneid>	GO:0008270	zinc ion binding
<geneid>	GO:0005515	protein binding
```

### Executables

- Meme package
provide path to the meme executable file
```
program_meme = '/path/to/meme'
```

- BLAST package
provide path to the binary folder of the blast executables
```
program_blast = '/path/to/blast/bin'
```

- Goatools package, provide path to the 2 binary files of goatools
```
program_goatools = '/path/to/bin/find_enrichment.py'
program_goatools = '/path/to/bin/plot_go_term.py'
```

- JBrowse
Download the JBrowse latest version, unzip and install it in the folder of DOCUMENT_ROOT, as mentioned in Apache. Provide the JBrowse folder name
```
program_jbrowse = 'JBrowse-1.12.1'
```

RApache
Install the following packages in R as superuser
$ sudo apt-get install r-cran-rjson
$ sudo apt-get install r-cran-brew
$ sudo apt-get install r-cran-rmysql

Update Directory in the apache.conf to execute the R scripts at the brew folder in the DOCUMENT_ROOT
For example, the Directory is updates to
<Directory /DOCUMENT_ROOT/brew>
SetHandler r-script
RHandler brew::brew
</Directory>

INSTALLATION
============
Run the shell script
$ sh run.sh

This creates the php folder, copy paste the contents of PHP folder to the DOCUMENT_ROOT
$ cp -r php/* /DOCUMENT_ROOT/.



