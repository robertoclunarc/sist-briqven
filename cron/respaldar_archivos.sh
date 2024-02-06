#!/bin/bash
HOME="/var/www/html/"
BACKUP_DIR="/var/www/html/backup_base_datos/fuentes"
ROTATE_DIR="/var/www/html/backup_base_datos/rotate"
TIMESTAMP="timestamp.dat"
SOURCE="$HOME"
DATE=$(date +%Y-%m-%d-%H%M%S)

#EXCLUDE="--exclude=/mnt/* --exclude=/proc/* --exclude=/sys/* --exclude=/tmp/*"
EXCLUDE="--exclude=/backup_base_datos/* "

cd $HOME

mkdir -p ${BACKUP_DIR}

echo set -- ${BACKUP_DIR}/backup-??.tar.gz
lastname=${!#}
echo $lastname
backupnr=${lastname##*backup-}
echo $backupnr

if [ -z "$backupnr" ]; then
 echo 'paso por aqui'
 backupnr=1
else
	backupnr=${backupnr%%.*}
	#backupnr=${backupnr//\?/0}
	backupnr=$[10#${backupnr}]

	if [ "$[backupnr++]" -ge 30 ]; then
	mkdir -p ${ROTATE_DIR}/${DATE}
	mv ${BACKUP_DIR}/b* ${ROTATE_DIR}/${DATE}
	mv ${BACKUP_DIR}/t* ${ROTATE_DIR}/${DATE}
	backupnr=1
	fi
	
	backupnr=0${backupnr}
	backupnr=${backupnr: -2}
fi
	filename=backup-${backupnr}.tar.gz
echo $filename
echo tar -cpzf ${BACKUP_DIR}/${filename} -g ${BACKUP_DIR}/${TIMESTAMP} -X $EXCLUDE ${SOURCE]}
