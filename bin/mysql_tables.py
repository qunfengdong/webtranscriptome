import os
from mysql_connection import execute, mysqlaccess

def create_gff_table():
	sql = "CREATE TABLE IF NOT EXISTS gfftest"
	sql += """(
	gene_id VARCHAR(50) NOT NULL,
	chr VARCHAR(50) NOT NULL,
	start INT(20) NOT NULL,
	end INT(20) NOT NULL,
	length INT(20) NOT NULL,
	strand VARCHAR(1) NOT NULL,
	name VARCHAR(200) NOT NULL,
	PRIMARY KEY(gene_id)
	)"""
	execute(sql)

def create_rnaseq_table():
	sql = "DROP TABLE IF EXISTS rnaseq; CREATE TABLE IF NOT EXISTS rnaseq"
	sql += """(
	test_id VARCHAR(50) NOT NULL,
	gene_id VARCHAR(50) NOT NULL,
	gene VARCHAR(50) NOT NULL,
	locus VARCHAR(50) NOT NULL,
	sample_1 VARCHAR(50) NOT NULL,
	sample_2 VARCHAR(50) NOT NULL,
	status VARCHAR(50) NOT NULL,
	value_1 DOUBLE NOT NULL,
	value_2 DOUBLE NOT NULL,
	l2fc INT(20) NOT NULL,
	test_stat VARCHAR(50) NOT NULL,
	p_value DOUBLE NOT NULL,
	q_value DOUBLE NOT NULL,
	significant VARCHAR(50) NOT NULL
	)"""
	execute(sql)

def create_chipseq():
	sql = "DROP TABLE IF EXISTS chipseq; CREATE TABLE IF NOT EXISTS chipseq"
	sql += """ (
	chr VARCHAR(50) NOT NULL,
	start INT(20) NOT NULL,
	end INT(20) NOT NULL,
	len INT(10) NOT NULL,
	summit INT(10) NOT NULL,
	tags INT(10) NOT NULL,
	pvalue DOUBLE NOT NULL,
	foldenrichment DOUBLE NOT NULL,
	fdr DOUBLE NOT NULL,
	exp VARCHAR(50) NOT NULL
	)"""
	#print(sql)
	execute(sql)

def create_chipseq_peaks(tname):
	sql = "CREATE TABLE IF NOT EXISTS " + tname
	sql += """ (
	chr VARCHAR(50) NOT NULL,
	start INT(20) NOT NULL,
	end INT(20) NOT NULL,
	seq TEXT NOT NULL
	)"""
	print(sql)
	execute(sql)

def create_chipseq_table(tname):
	sql = "CREATE TABLE IF NOT EXISTS " + tname
	sql += """ (
	chr VARCHAR(50) NOT NULL,
	start INT(20) NOT NULL,
	end INT(20) NOT NULL,
	len INT(10) NOT NULL,
	summit INT(10) NOT NULL,
	tags INT(10) NOT NULL,
	pvalue DOUBLE NOT NULL,
	foldenrichment DOUBLE NOT NULL,
	fdr DOUBLE NOT NULL,
	)"""
	print(sql)
	#execute(sql)

def create_rnaseq_expression_table(explist):
	sql = "DROP TABLE IF EXISTS rnaseq_expression; CREATE TABLE IF NOT EXISTS rnaseq_expression ("
	sql += "gene_id VARCHAR(50) NOT NULL, "
	for exp in explist:
		sql += exp +" DOUBLE NOT NULL, "
	sql += " PRIMARY KEY(gene_id))"
	execute(sql)

def create_user_table():
	sql = "CREATE TABLE IF NOT EXISTS users"
	sql += """ (
	id INT(11) NOT NULL AUTO_INCREMENT,
	firstname VARCHAR(255) NOT NULL,
	lastname VARCHAR(255) NOT NULL,
	username VARCHAR(255) NOT NULL,
	password CHAR(64) NOT NULL,
	salt CHAR(16) NOT NULL,
	email VARCHAR(255) NOT NULL,
	level varchar(1) NOT NULL,
	PRIMARY KEY(id)
	)"""
	execute(sql)

def create_promoter_table(tname):
	sql = "CREATE TABLE IF NOT EXISTS " + tname
	sql += """ (
	gene_id VARCHAR(50) NOT NULL,
	chr VARCHAR(50) NOT NULL,
	start INT(20) NOT NULL,
	end INT(20) NOT NULL,
	region varchar(50) NOT NULL,
	seq text
	)"""
	print(sql)
	execute(sql)

def create_mygenelist():
	sql = "CREATE TABLE IF NOT EXISTS mygenelist "
	sql += """ (
	id INT(10) NOT NULL AUTO_INCREMENT,
	listname VARCHAR(50) NOT NULL,
	created DATE NOT NULL,
	modified DATE NOT NULL,
	userid INT(11) NOT NULL,
	PRIMARY KEY(id)
	)"""
	execute(sql)

def create_gene_descriptions():
	sql = "DROP TABLE IF EXISTS gene_descriptions; CREATE TABLE IF NOT EXISTS gene_descriptions "
	sql += """ (
	gene_id varchar(50) NOT NULL,
	description text,
	PRIMARY KEY(gene_id)
	)"""
	execute(sql)
	
def create_pathway_table():
	sql = "DROP TABLE IF EXISTS pathway; CREATE TABLE IF NOT EXISTS pathway "
	sql += """ (
	gene_id varchar(50),
	enzyme_name text,
	reaction_id varchar(50),
	reaction_name text,
	ec varchar(20),
	pathway_id varchar(50),
	pathway_name text 
	)"""
	execute(sql)

def create_go_table():
	sql = "DROP TABLE IF EXISTS gotable; CREATE TABLE IF NOT EXISTS gotable "
	sql += """ (
	gene_id varchar(50),
	goterm varchar(50),
	godesc text
	)"""
	execute(sql)

def create_cluster():
	sql = "DROP TABLE IF EXISTS cluster; CREATE TABLE IF NOT EXISTS cluster"
	sql += """(
	gene_id VARCHAR(50) NOT NULL,
	cluster int(10) NOT NULL
	)"""
	execute(sql)

def create_ortholog(species):
	sql = "DROP TABLE IF EXISTS ortholog; CREATE TABLE IF NOT EXISTS ortholog"
	sqld = ["(gene_id VARCHAR(50) NOT NULL"]
	for sp in species:
		sqld.append(sp + " varchar(50)")
		sqld.append(sp + "_desc text")
	sql += ",".join(sqld)
	sql += ")"
	#print(sql)
	execute(sql)

def load_table(tname, filename):
	sql = "mysql --local-infile=1 -u "+mysqlaccess()['user']+" -p" + mysqlaccess()['password']+ " " + mysqlaccess()['db']
	sql += " --execute=\"Load data local infile '"+ filename +"' into table "+ tname +"\""
	print(sql)
	os.system(sql)

if __name__ == '__main__':
	#create_user_table()
	create_mygenelist()
