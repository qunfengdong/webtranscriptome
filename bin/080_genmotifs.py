import pymysql
conn = pymysql.connect(host='localhost', user='root', passwd='loyola', db='rnaseqchipseq')
cur = conn.cursor()

peaks = {}

count = 0
cur.execute("SELECT * FROM RA1_all_high_confidence")
for r in cur:
	print(count)
	peaks[r[count]] = {}
	peaks[r[count]]['chr'] = r[0]
	peaks[r[count]]['start'] = r[1]
	peaks[r[count]]['end'] = r[2]
	peaks[r[count]]['summit'] = r[4]
	print(peaks[r[count]])
	count += 1

cur.close()

pro = conn.cursor()

for key in peaks.keys():
	pro.execute("SELECT * FROM promoter_1000 where start <= " + str(peaks[key]['start']) + " and end > "+ str(peaks[key]['end']))
	for r in pro:
		motifstart = int(peaks[key]['start']) - int(r[1])
		motifend = int(peaks[key]['end']) - int(r[1])
		print(key +
			"\t" + peaks[key]['chr'] +
			"\t" + str(peaks[key]['start']) +
			"\t" + str(peaks[key]['end']) +
			"\t" + str(peaks[key]['peak']) +
			"\t" + r[3][motifstart:motifend])
pro.close()

conn.close()