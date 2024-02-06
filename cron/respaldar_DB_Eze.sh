#!/bin/bash
user="roberto";
db1="bdmatcestaticket";
db2="bdmat_ctrl_planta";
db3="bdmatrrhh";
db4="bdmatserviciomedico";
port="5432";
host="localhost";
backups_path="/var/www/html/backup_base_datos";
date="`date +%Y-%m-%d_%H:%M:%S`";
first_backup="`ls $backups_path | sort -n | head -1`";
backups_count="`find  $backups_path/*.sql -type f | wc -l`";
# dump
echo 'Iniciando Backup..'
#pg_dump -h localhost -p 5432 -U roberto -F c -v -d bdmatcestaticket -f $backups_path/[$db1]_$date.sql;
#pg_dump -h localhost -p 5432 -U roberto -F c -v -d bdmatcestaticket -f /var/www/html/backup_base_datos/[$db1]_$date.sql;
pg_dump -h $host -p $port -U $user -F c -v -d $db1 -f $backups_path/[$db1]_$date.sql;
pg_dump -h $host -p $port -U $user -F c -v -d $db2 -f $backups_path/[$db2]_$date.sql;
pg_dump -h $host -p $port -U $user -F c -v -d $db3 -f $backups_path/[$db3]_$date.sql;
pg_dump -h $host -p $port -U $user -F c -v -d $db4 -f $backups_path/[$db4]_$date.sql;
if [ $backups_count -ge 5 ] ; then
 echo 'Greather than 5'
# removing backup
rm $backups_path/$first_backup
# printing body explaining removed backup
 printf "\nArchivo borrado: "$first_backup
fi
#pg_dump -h $host -p $port -U $user -F c -v -d $db2 -f $backups_path/[$db3]_$date.sql;
#pg_dump -h $host -p $port -U $user -F c -v -d $db2 -f $backups_path/[$db4]_$date.sql;
#pg_dump -h $host -p $port -U $user -F c -v -d $db3 -f $backups_path/$db3_$date.sql;
#pg_dump -h $host -p $port -U $user -F c -v -d $db4 -f $backups_path/$db4_$date.sql;
# Esto crea un archivo compactado con tus respaldos
#tar cjf respaldo[$DATE].tar.bz2 /var/www/html/backup_base_datos/
#psql-l
