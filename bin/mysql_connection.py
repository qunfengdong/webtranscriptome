import pymysql
import sys
import os

sys.path.append(os.path.abspath('../'))
from data import *

def mysqlaccess():
	settings = {
		'host': database_host,
		'user': database_user,
		'password': database_password,
		'db': database_db
	}
	return settings

conn = pymysql.connect(host=mysqlaccess()['host'],
											 user=mysqlaccess()['user'],
											 password=mysqlaccess()['password'],
											 db=mysqlaccess()['db'],
											 charset='utf8mb4',
											 local_infile=True,
											 cursorclass=pymysql.cursors.DictCursor)

def execute(query):
	print(query)
	cursor = conn.cursor()
	cursor.execute(query)
	a = cursor.fetchall()
	return a

if __name__ == '__main__':
	#connect()
	execute('select version()')