cd $1
if ! [ -e tmp ] ;then
  mkdir tmp
fi
chmod 777 tmp

scripts/mysql_install_db --port=3308 --socket=/home/villalbamedina/dolibarr-3.9.1-1/mysql/tmp/mysql.sock  --datadir=/home/villalbamedina/dolibarr-3.9.1-1/mysql/data --pid-file=/home/villalbamedina/dolibarr-3.9.1-1/mysql/data/mysqld.pid > /dev/null

if [ `uname -s` = "SunOS" ]; then
    U=`id|sed -e s/uid=//g -e s/\(.*//g`
else
    U=`id -u`
fi

if [ $U = 0 ]; then
   chown -R root .
   chgrp -R root .
   
   # External data directory - T3532
   cd /home/villalbamedina/dolibarr-3.9.1-1/mysql/data
   chown -R mysql .
   chgrp -R root .
   cd $1
fi



/home/villalbamedina/dolibarr-3.9.1-1/mysql/scripts/ctl.sh start mysql > /dev/null
sleep 10
bin/mysql -S /home/villalbamedina/dolibarr-3.9.1-1/mysql/tmp/mysql.sock -u root -e "UPDATE mysql.user SET Password=PASSWORD('$2') WHERE User='root';"
bin/mysql -S /home/villalbamedina/dolibarr-3.9.1-1/mysql/tmp/mysql.sock -u root -e "DELETE FROM mysql.user WHERE User='';"
bin/mysql -S /home/villalbamedina/dolibarr-3.9.1-1/mysql/tmp/mysql.sock -u root -e "FLUSH PRIVILEGES;"

