#!/bin/sh
#echo Iniciando Script de envio de informacion
export QUERY_STRING="turno=3"; \
php -e -r 'parse_str($_SERVER["QUERY_STRING"], $_GET); include "/var/www/html/control_asistencia/pages/cron/reporte_diario_por_turno_ccure_gerencias.php";'
#echo Fin Script de envio de informacion

