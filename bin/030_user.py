import os
import sys

sys.path.append(os.path.abspath('../'))
from data import *

b = os.getcwd()
a = os.path.abspath(os.path.join(os.pardir, 'php', 'mysql', 'details.php')) 
print(a)

with open(a, "w") as outfile:
  php = "<?php\n$username = '{0}';\n$password = '{1}';\n$host = '{2}';\n$dbname = '{3}';\n?>"
  outfile.write(php.format(database_user, database_password, database_host, database_db))
  