#!/bin/sh
#echo Iniciando Script de envio de informacion
export QUERY_STRING="turno=1"; \
php -e -r 'parse_str($_SERVER["QUERY_STRING"], $_GET); include "/var/www/html/control_asistencia/pages/cron/reporte_diario_por_turno_ccure.php";'
#php /var/www/html/control_asistencia/pages/reporte_diario_por_turno_ccure.php "turno=2"
#echo Fin Script de envio de informacion

