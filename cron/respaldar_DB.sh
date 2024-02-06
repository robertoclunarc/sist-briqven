# /bin/bash

# DATOS
backups_path="/var/www/html";
PATH_BD=$backups_path/backup/db;
PATH_FILES=$backups_path/backup/fuentes;

user="roberto";
pasw="roberto";
db1="bdmatcestaticket";
db2="bdmat_ctrl_planta";
db3="bdmat_comprobantes";
db4="bdmatbitacora";
db5="bdmatconstancia";
db6="bdmatrrhh";
db7="bdmatserviciomedico";
db8="drupal";
db9="bdmatasistencia_laboral";
db10="bdmatcargas_sio";
db11="bdmat_suministro_combustible";
db12="bdmatreporte_produccion";
port="5432";
host="localhost";

if [ ! -d "$PATH_BD" ]; then
 echo "Creando directorio $PATH_BD"
 mkdir -p $PATH_BD
 chown www-data:root $PATH_BD
fi

if [ ! -d "$PATH_FILES" ]; then
  echo "Creando directorio $PATH_FILES"
  mkdir -p $PATH_FILES
  chown www-data:root $PATH_FILES
fi

echo 'Borrando contenido de la carpera '$PATH_BD;
#rm $PATH_BD/*

# COMIENZO
date="`date +%Y-%m-%d_%H:%M:%S`";
date2="`date +%Y-%m-%d`";
# Numero de dias a conservar los ficheros
DIAS=7



# dump
echo 'Iniciando Backup...';
date +%Y-%m-%d_%H:%M:%S;
#pg_dump -h localhost -p 5432 -U roberto -F c -v -d bdmatcestaticket -f $backups_path/[$db1]_$date.sql;
#pg_dump -h localhost -p 5432 -U roberto -F c -v -d bdmatcestaticket -f /var/www/html/backup_base_datos/[$db1]_$date.sql;
PGPASSWORD=$pasw pg_dump -h $host -p $port -U $user -F c -v -d $db1 -f $PATH_BD/[$db1]_$date.sql;
#cp $PATH_BD/\[$db1\]_$date.sql $PATH_BD/
echo $db1;
PGPASSWORD=$pasw pg_dump -h $host -p $port -U $user -F c -v -d $db2 -f $PATH_BD/[$db2]_$date.sql;
#cp $PATH_BD/\[$db2\]_$date.sql $PATH_BD/
echo $db2;
PGPASSWORD=$pasw pg_dump -h $host -p $port -U $user -F c -v -d $db3 -f $PATH_BD/[$db3]_$date.sql;
#cp $PATH_BD/\[$db4\]_$date.sql $PATH_BD/
echo $db3;
PGPASSWORD=$pasw pg_dump -h $host -p $port -U $user -F c -v -d $db4 -f $PATH_BD/[$db4]_$date.sql;
#cp $PATH_BD/\[$db4\]_$date.sql $PATH_BD/
echo $db4;
PGPASSWORD=$pasw pg_dump -h $host -p $port -U $user -F c -v -d $db5 -f $PATH_BD/[$db5]_$date.sql;
#cp $PATH_BD/\[$db5\]_$date.sql $PATH_BD/
echo $db5;
PGPASSWORD=$pasw pg_dump -h $host -p $port -U $user -F c -v -d $db6 -f $PATH_BD/[$db6]_$date.sql;
#cp $PATH_BD/\[$db6\]_$date.sql $PATH_BD/
echo $db6;
PGPASSWORD=$pasw pg_dump -h $host -p $port -U $user -F c -v -d $db7 -f $PATH_BD/[$db7]_$date.sql;
#cp $PATH_BD/\[$db7\]_$date.sql $PATH_BD/
echo $db7;
PGPASSWORD=$pasw pg_dump -h $host -p $port -U $user -F c -v -d $db8 -f $PATH_BD/[$db8]_$date.sql;
#cp $PATH_BD/\[$db8\]_$date.sql $PATH_BD/
echo $db8;
PGPASSWORD=$pasw pg_dump -h $host -p $port -U $user -F c -v -d $db9 -f $PATH_BD/[$db9]_$date.sql;
#cp $PATH_BD/\[$db9\]_$date.sql $PATH_BD/
echo $db9;
PGPASSWORD=$pasw pg_dump -h $host -p $port -U $user -F c -v -d $db10 -f $PATH_BD/[$db10]_$date.sql;
#cp $PATH_BD/\[$db10\]_$date.sql $PATH_BD/
echo $db10;
PGPASSWORD=$pasw pg_dump -h $host -p $port -U $user -F c -v -d $db11 -f $PATH_BD/[$db11]_$date.sql;
#cp $PATH_BD/\[$db10\]_$date.sql $PATH_BD/
echo $db11;
PGPASSWORD=$pasw pg_dump -h $host -p $port -U $user -F c -v -d $db12 -f $PATH_BD/[$db12]_$date.sql;
#cp $PATH_BD/\[$db12\]_$date.sql $PATH_BD/
echo $db12;
echo 'Fin Backup...';
date +%Y-%m-%d_%H:%M:%S;

#echo "Iniciando copia";
#date +%Y-%m-%d_%H:%M:%S;
#cp $PATH_BD/*$date2* $PATH_BD/db/
#echo "Fin de copiado";
#date +%Y-%m-%d_%H:%M:%S;

#echo "pg_dump -h" $host " -p " $port  " -U "  $user  " -W "  $pasw  " -F c -v -d "  $db3 " -f " $backups_path/$db3_$date.sql;
#pg_dump -h $host -p $port -U $user -F c -v -d $db4 -f $backups_path/$db4_$date.sql;
# Esto crea un archivo compactado con tus respaldos
#tar cjf respaldo[$DATE].tar.bz2 /var/www/html/backup_base_datos/
#psql-l
