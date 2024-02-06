# /bin/bash

# DATOS
backups_path="/var/www/html";
PATH_BD=$backups_path/backup/db;
PATH_FILES=$backups_path/backup/fuentes;
backups_server=/mnt/backup/backup_system/10.50.188.48/DB

echo "Iniciando copia";
date +%Y-%m-%d_%H:%M:%S;
cp $PATH_BD/* $backups_server
echo "Fin de copiado";
date +%Y-%m-%d_%H:%M:%S;
