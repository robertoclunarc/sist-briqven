PGDMP                         |         	   bdmatrrhh    9.4.8    9.4.6 �    X	           0    0    ENCODING    ENCODING     #   SET client_encoding = 'SQL_ASCII';
                       false            Y	           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                       false            Z	           1262    52772 	   bdmatrrhh    DATABASE     l   CREATE DATABASE bdmatrrhh WITH TEMPLATE = template0 ENCODING = 'SQL_ASCII' LC_COLLATE = 'C' LC_CTYPE = 'C';
    DROP DATABASE bdmatrrhh;
             roberto    false                        2615    2200    public    SCHEMA        CREATE SCHEMA public;
    DROP SCHEMA public;
             postgres    false            [	           0    0    SCHEMA public    COMMENT     6   COMMENT ON SCHEMA public IS 'standard public schema';
                  postgres    false    8            \	           0    0    public    ACL     �   REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;
                  postgres    false    8                        3079    11859    plpgsql 	   EXTENSION     ?   CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;
    DROP EXTENSION plpgsql;
                  false            ]	           0    0    EXTENSION plpgsql    COMMENT     @   COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';
                       false    1                        3079    52773    dblink 	   EXTENSION     :   CREATE EXTENSION IF NOT EXISTS dblink WITH SCHEMA public;
    DROP EXTENSION dblink;
                  false    8            ^	           0    0    EXTENSION dblink    COMMENT     _   COMMENT ON EXTENSION dblink IS 'connect to other PostgreSQL databases from within a database';
                       false    2                        1255    791898     actualizar_jefes_unidad(integer)    FUNCTION     W  CREATE FUNCTION actualizar_jefes_unidad(integer) RETURNS character varying
    LANGUAGE plpgsql
    AS $_$
declare 
cc ALIAS FOR $1;
cedula character varying(10);
BEGIN

select trabajador into cedula
FROM adam_vw_dotacion_briqven_02_mas 
where cast(ccosto as integer)=cc
and cast(grado_trab as integer)=(select max(cast(grado_trab as integer)) 
		FROM adam_vw_dotacion_briqven_02_mas 
		where cast(ccosto as integer)=cc
		and trabajador in (select trabajador 
				   from trabajadores_grales 
				   where sit_trabajador=1)
				   and trabajador not in 
					('15372135','9948087','18248750', '14505808','16629446','12893599','14987467','14986631','17218799','13982132','4297976','14576991','9424908','13336163','16500839')
					---trabajadores que tienen mismo grado y centro de costo pero no son jefes
);

RETURN cedula;
END;
$_$;
 7   DROP FUNCTION public.actualizar_jefes_unidad(integer);
       public       roberto    false    8    1            !           1255    808669    actualizar_pagos_mensuales()    FUNCTION     �  CREATE FUNCTION actualizar_pagos_mensuales() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
vdeuda double precision;
BEGIN
	 
if corresponde='MENSUALIDAD' then
     
     if new.mes=9 then
        vdeuda=(new.mes*12)-new.mes;--la deuda es igual a la cantidad de meses del a�o a pagar menos el mes que esta pagando
	UPDATE sibes_mensualidades
	   SET mes_09=new.monto, 
	       mes_10=new.monto, mes_11=new.monto, mes_12=new.monto, mes_01=new.monto, mes_02=new.monto, mes_03=new.monto, mes_04=new.monto, 
	       mes_05=new.monto, mes_06=new.monto, mes_07=new.monto, mes_08=new.monto, deuda=vdeuda, pagado=pagado+new.monto, ultimo_mes_pagado=new.mes, 
	       monto_ultimo_mes=new.monto, fecha_ult_pago=now(), pago_prox='MENSUALIDAD'
	 WHERE idmensualidad=new.fkmensualidad;
     elsif new.mes=10 then
        vdeuda=(new.mes*11)-new.mes;--la deuda es igual a la cantidad de meses del a�o a pagar menos el mes que esta pagando
	UPDATE sibes_mensualidades
	   SET  
	       mes_10=new.monto, mes_11=new.monto, mes_12=new.monto, mes_01=new.monto, mes_02=new.monto, mes_03=new.monto, mes_04=new.monto, 
	       mes_05=new.monto, mes_06=new.monto, mes_07=new.monto, mes_08=new.monto, deuda=vdeuda, pagado=pagado+new.monto, ultimo_mes_pagado=new.mes, 
	       monto_ultimo_mes=new.monto, fecha_ult_pago=now(), pago_prox='MENSUALIDAD'
	 WHERE idmensualidad=new.fkmensualidad;
     elsif new.mes=11 then
        vdeuda=(new.mes*10)-new.mes;--la deuda es igual a la cantidad de meses del a�o a pagar menos el mes que esta pagando
	UPDATE sibes_mensualidades
	   SET  
	       mes_11=new.monto, mes_12=new.monto, mes_01=new.monto, mes_02=new.monto, mes_03=new.monto, mes_04=new.monto, 
	       mes_05=new.monto, mes_06=new.monto, mes_07=new.monto, mes_08=new.monto, deuda=vdeuda, pagado=pagado+new.monto, ultimo_mes_pagado=new.mes, 
	       monto_ultimo_mes=new.monto, fecha_ult_pago=now(), pago_prox='MENSUALIDAD'
	 WHERE idmensualidad=new.fkmensualidad;
     elsif new.mes=12 then
        vdeuda=(new.mes*9)-new.mes;--la deuda es igual a la cantidad de meses del a�o a pagar menos el mes que esta pagando
	UPDATE sibes_mensualidades
	   SET  
	       mes_12=new.monto, mes_01=new.monto, mes_02=new.monto, mes_03=new.monto, mes_04=new.monto, 
	       mes_05=new.monto, mes_06=new.monto, mes_07=new.monto, mes_08=new.monto, deuda=vdeuda, pagado=pagado+new.monto, ultimo_mes_pagado=new.mes, 
	       monto_ultimo_mes=new.monto, fecha_ult_pago=now(), pago_prox='MENSUALIDAD'
	 WHERE idmensualidad=new.fkmensualidad;
     elsif new.mes=1 then
        vdeuda=(new.mes*8)-new.mes;--la deuda es igual a la cantidad de meses del a�o a pagar menos el mes que esta pagando
	UPDATE sibes_mensualidades
	   SET  
	       mes_01=new.monto, mes_02=new.monto, mes_03=new.monto, mes_04=new.monto, 
	       mes_05=new.monto, mes_06=new.monto, mes_07=new.monto, mes_08=new.monto, deuda=vdeuda, pagado=pagado+new.monto, ultimo_mes_pagado=new.mes, 
	       monto_ultimo_mes=new.monto, fecha_ult_pago=now(), pago_prox='MENSUALIDAD'
	 WHERE idmensualidad=new.fkmensualidad;
     elsif new.mes=2 then
	vdeuda=(new.mes*7)-new.mes;--la deuda es igual a la cantidad de meses del a�o a pagar menos el mes que esta pagando
	UPDATE sibes_mensualidades
	   SET  
	       mes_02=new.monto, mes_03=new.monto, mes_04=new.monto, 
	       mes_05=new.monto, mes_06=new.monto, mes_07=new.monto, mes_08=new.monto, deuda=vdeuda, pagado=pagado+new.monto, ultimo_mes_pagado=new.mes, 
	       monto_ultimo_mes=new.monto, fecha_ult_pago=now(), pago_prox='MENSUALIDAD'
	 WHERE idmensualidad=new.fkmensualidad;
     elsif new.mes=3 then
	vdeuda=(new.mes*6)-new.mes;--la deuda es igual a la cantidad de meses del a�o a pagar menos el mes que esta pagando
	UPDATE sibes_mensualidades
	   SET  
	       mes_03=new.monto, mes_04=new.monto, 
	       mes_05=new.monto, mes_06=new.monto, mes_07=new.monto, mes_08=new.monto, deuda=vdeuda, pagado=pagado+new.monto, ultimo_mes_pagado=new.mes, 
	       monto_ultimo_mes=new.monto, fecha_ult_pago=now(), pago_prox='MENSUALIDAD'
	 WHERE idmensualidad=new.fkmensualidad;
     elsif new.mes=4 then
	vdeuda=(new.mes*5)-new.mes;--la deuda es igual a la cantidad de meses del a�o a pagar menos el mes que esta pagando
	UPDATE sibes_mensualidades
	   SET	        
	       mes_04=new.monto, mes_05=new.monto, mes_06=new.monto, mes_07=new.monto, mes_08=new.monto, deuda=vdeuda, pagado=pagado+new.monto, ultimo_mes_pagado=new.mes, 
	       monto_ultimo_mes=new.monto, fecha_ult_pago=now(), pago_prox='MENSUALIDAD'
	 WHERE idmensualidad=new.fkmensualidad;
     elsif new.mes=5 then
	vdeuda=(new.mes*4)-new.mes;--la deuda es igual a la cantidad de meses del a�o a pagar menos el mes que esta pagando
	UPDATE sibes_mensualidades
	   SET	        
	       mes_05=new.monto, mes_06=new.monto, mes_07=new.monto, mes_08=new.monto, deuda=vdeuda, pagado=pagado+new.monto, ultimo_mes_pagado=new.mes, 
	       monto_ultimo_mes=new.monto, fecha_ult_pago=now(), pago_prox='MENSUALIDAD'
	 WHERE idmensualidad=new.fkmensualidad;
     elsif new.mes=6 then
	vdeuda=(new.mes*3)-new.mes;--la deuda es igual a la cantidad de meses del a�o a pagar menos el mes que esta pagando
	UPDATE sibes_mensualidades
	   SET	        
	       mes_06=new.monto, mes_07=new.monto, mes_08=new.monto, deuda=vdeuda, pagado=pagado+new.monto, ultimo_mes_pagado=new.mes, 
	       monto_ultimo_mes=new.monto, fecha_ult_pago=now(), pago_prox='MENSUALIDAD'
	 WHERE idmensualidad=new.fkmensualidad;
     elsif new.mes=7 then
	vdeuda=(new.mes*2)-new.mes;--la deuda es igual a la cantidad de meses del a�o a pagar menos el mes que esta pagando
	UPDATE sibes_mensualidades
	   SET	        
	       mes_07=new.monto, mes_08=new.monto, deuda=vdeuda, pagado=pagado+new.monto, ultimo_mes_pagado=new.mes, 
	       monto_ultimo_mes=new.monto, fecha_ult_pago=now(), pago_prox='MENSUALIDAD'
	 WHERE idmensualidad=new.fkmensualidad;
     else
	vdeuda=0;--la deuda es igual a la cantidad de meses del a�o a pagar menos el mes que esta pagando
	UPDATE sibes_mensualidades
	   SET	        
	       mes_07=new.monto, mes_08=new.monto, deuda=vdeuda, pagado=pagado+new.monto, ultimo_mes_pagado=new.mes, 
	       monto_ultimo_mes=new.monto, fecha_ult_pago=now(), pago_prox='MENSUALIDAD'
	 WHERE idmensualidad=new.fkmensualidad;
     end if;
     
else
	UPDATE sibes_mensualidades SET 
	monto_inscripcion= new.monto, 
	pago_prox ='MENSUALIDAD',
	fecha_ult_pago=NOW(), 
	ultimo_mes_pagado=0, 
	deuda=deuda- new.monto,
	pagado=pagado+new.monto
	WHERE idmensualidad= new.idmensualidad;
end if;

 RETURN NEW;
END;
$$;
 3   DROP FUNCTION public.actualizar_pagos_mensuales();
       public       roberto    false    1    8                       1255    268708    actualizar_trabajadores()    FUNCTION     [  CREATE FUNCTION actualizar_trabajadores() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
IF EXISTS (SELECT trabajador FROM trabajadores where trabajador=NEW.trabajador) THEN 
	 UPDATE trabajadores
	   SET registro_fiscal=NEW.registro_fiscal, nombre=NEW.nombre, sexo=NEW.sexo, fecha_nacimiento=NEW.fecha_nacimiento, 
	       domicilio=NEW.domicilio, domicilio2=NEW.domicilio2, poblacion=NEW.poblacion, estado_provincia=NEW.estado_provincia, pais=NEW.pais, 
	       codigo_postal=NEW.codigo_postal, calles_aledanas=NEW.calles_aledanas, telefono_particular=NEW.telefono_particular, reg_seguro_social=NEW.reg_seguro_social, 
	       domicilio3=NEW.domicilio3, e_mail=NEW.e_mail, fkunidad=NEW.fkunidad, tipo_documento=NEW.tipo_documento, nombres=NEW.nombres, 
	       apellidos=NEW.apellidos, edo_civil=NEW.edo_civil 
	 WHERE trabajador=NEW.trabajador;

	--UPDATE trabajadores_supervisores set supervisor=NEW.supervisor  WHERE trabajador=NEW.trabajador;
ELSE
   INSERT INTO trabajadores(
            trabajador, registro_fiscal, nombre, sexo, fecha_nacimiento, 
            domicilio, domicilio2, poblacion, estado_provincia, pais, codigo_postal, 
            calles_aledanas, telefono_particular, reg_seguro_social, domicilio3, 
            e_mail, fkunidad, tipo_documento, nombres, apellidos, edo_civil)
    VALUES (NEW.trabajador, NEW.registro_fiscal, NEW.nombre, NEW.sexo, NEW.fecha_nacimiento, NEW.
            domicilio, NEW.domicilio2, NEW.poblacion, NEW.estado_provincia, NEW.pais, NEW.codigo_postal, NEW.
            calles_aledanas, NEW.telefono_particular, NEW.reg_seguro_social, NEW.domicilio3, NEW.
            e_mail, NEW.fkunidad, NEW.tipo_documento, NEW.nombres, NEW.apellidos, NEW.edo_civil);

    INSERT INTO trabajadores_supervisores (trabajador, supervisor) VALUES (NEW.trabajador, NEW.supervisor);

END IF;	

 RETURN NEW;
END;
$$;
 0   DROP FUNCTION public.actualizar_trabajadores();
       public       roberto    false    1    8                       1255    535189    cambiar_condicion()    FUNCTION     �  CREATE FUNCTION cambiar_condicion() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
IF EXISTS (SELECT trabajador FROM condiciones_trab where trabajador=NEW.trabajador) THEN
	IF NEW.clase_nomina='SP' THEN
		UPDATE condiciones_trab SET fkcondicion=9 WHERE trabajador=NEW.trabajador;
	END IF;
	IF NEW.sit_trabajador=2 THEN
		DELETE FROM condiciones_trab WHERE trabajador=NEW.trabajador;
	END IF;
END IF;	

 RETURN NEW;
END;
$$;
 *   DROP FUNCTION public.cambiar_condicion();
       public       roberto    false    8    1                       1255    808608    crear_pagos_mensuales()    FUNCTION     F  CREATE FUNCTION crear_pagos_mensuales() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
enero character varying(15);
febrero character varying(15);
marzo character varying(15);
abril character varying(15);
mayo character varying(15);
junio character varying(15);
julio character varying(15);
agosto character varying(15);
septiembre character varying(15);
octubre character varying(15);
noviembre character varying(15);
diciembre character varying(15);
--fecha_pres_aux date;
vdeuda double precision;
diaadd int;
BEGIN
IF NOT EXISTS (SELECT idinscripcion FROM sibes_inscripciones where fkbeneficiario=NEW.fkbeneficiario and anio_escolar=NEW.anio_escolar) THEN 
	 
if new.mes_inicio=9 then
	enero =NEW.monto_mensual;
	febrero =NEW.monto_mensual;
	marzo =NEW.monto_mensual;
	abril  =NEW.monto_mensual;
	mayo =NEW.monto_mensual;
	junio  =NEW.monto_mensual;
	julio  =NEW.monto_mensual;
	agosto  =NEW.monto_mensual;
	septiembre  =NEW.monto_mensual;
	octubre  =NEW.monto_mensual;
	noviembre  =NEW.monto_mensual;
	diciembre  =NEW.monto_mensual;
	vdeuda=12*NEW.monto_mensual::numeric;

elsif new.mes_inicio=10 then
	enero =NEW.monto_mensual;
	febrero =NEW.monto_mensual;
	marzo =NEW.monto_mensual;
	abril  =NEW.monto_mensual;
	mayo =NEW.monto_mensual;
	junio  =NEW.monto_mensual;
	julio  =NEW.monto_mensual;
	agosto  =NEW.monto_mensual;
	septiembre  ='0.0';
	octubre  =NEW.monto_mensual;
	noviembre  =NEW.monto_mensual;
	diciembre  =NEW.monto_mensual;
	vdeuda=11*NEW.monto_mensual::numeric;

elsif new.mes_inicio=11 then
	enero =NEW.monto_mensual;
	febrero =NEW.monto_mensual;
	marzo =NEW.monto_mensual;
	abril  =NEW.monto_mensual;
	mayo =NEW.monto_mensual;
	junio  =NEW.monto_mensual;
	julio  =NEW.monto_mensual;
	agosto  =NEW.monto_mensual;
	septiembre  ='0.0';
	octubre   ='0.0';
	noviembre  =NEW.monto_mensual;
	diciembre  =NEW.monto_mensual;
	vdeuda=10*NEW.monto_mensual::numeric;

elsif new.mes_inicio=12 then
	enero =NEW.monto_mensual;
	febrero =NEW.monto_mensual;
	marzo =NEW.monto_mensual;
	abril  =NEW.monto_mensual;
	mayo =NEW.monto_mensual;
	junio  =NEW.monto_mensual;
	julio  =NEW.monto_mensual;
	agosto  =NEW.monto_mensual;
	septiembre  ='0.0';
	octubre   ='0.0';
	noviembre  ='0.0';
	diciembre  =NEW.monto_mensual;
	vdeuda=9*NEW.monto_mensual::numeric;

elsif new.mes_inicio=1 then
	enero =NEW.monto_mensual;
	febrero =NEW.monto_mensual;
	marzo =NEW.monto_mensual;
	abril  =NEW.monto_mensual;
	mayo =NEW.monto_mensual;
	junio  =NEW.monto_mensual;
	julio  =NEW.monto_mensual;
	agosto  =NEW.monto_mensual;
	septiembre  ='0.0';
	octubre   ='0.0';
	noviembre  ='0.0';
	diciembre  ='0.0';
	vdeuda=8*NEW.monto_mensual::numeric;
elsif new.mes_inicio=2 then
	enero ='0.0';
	febrero =NEW.monto_mensual;
	marzo =NEW.monto_mensual;
	abril  =NEW.monto_mensual;
	mayo =NEW.monto_mensual;
	junio  =NEW.monto_mensual;
	julio  =NEW.monto_mensual;
	agosto  =NEW.monto_mensual;
	septiembre  ='0.0';
	octubre   ='0.0';
	noviembre  ='0.0';
	diciembre  ='0.0';
	vdeuda=7*NEW.monto_mensual::numeric;
elsif new.mes_inicio=3 then
	enero ='0.0';
	febrero  ='0.0';
	marzo =NEW.monto_mensual;
	abril  =NEW.monto_mensual;
	mayo =NEW.monto_mensual;
	junio  =NEW.monto_mensual;
	julio  =NEW.monto_mensual;
	agosto  =NEW.monto_mensual;
	septiembre  ='0.0';
	octubre   ='0.0';
	noviembre  ='0.0';
	diciembre  ='0.0';
	vdeuda=6*NEW.monto_mensual::numeric;
elsif new.mes_inicio=4 then
	enero ='0.0';
	febrero  ='0.0';
	marzo ='0.0';
	abril  =NEW.monto_mensual;
	mayo =NEW.monto_mensual;
	junio  =NEW.monto_mensual;
	julio  =NEW.monto_mensual;
	agosto  =NEW.monto_mensual;
	septiembre  ='0.0';
	octubre   ='0.0';
	noviembre  ='0.0';
	diciembre  ='0.0';
	vdeuda=5*NEW.monto_mensual::numeric;
elsif new.mes_inicio=5 then
	enero ='0.0';
	febrero  ='0.0';
	marzo ='0.0';
	abril  ='0.0';
	mayo =NEW.monto_mensual;
	junio  =NEW.monto_mensual;
	julio  =NEW.monto_mensual;
	agosto  =NEW.monto_mensual;
	septiembre  ='0.0';
	octubre   ='0.0';
	noviembre  ='0.0';
	diciembre  ='0.0';
	vdeuda=4*NEW.monto_mensual::numeric;
elsif new.mes_inicio=6 then
	enero ='0.0';
	febrero  ='0.0';
	marzo ='0.0';
	abril  ='0.0';
	mayo ='0.0';
	junio  =NEW.monto_mensual;
	julio  =NEW.monto_mensual;
	agosto  =NEW.monto_mensual;
	septiembre  ='0.0';
	octubre   ='0.0';
	noviembre  ='0.0';
	diciembre  ='0.0';
	vdeuda=3*NEW.monto_mensual::numeric;
elsif new.mes_inicio=7 then
	enero ='0.0';
	febrero  ='0.0';
	marzo ='0.0';
	abril  ='0.0';
	mayo ='0.0';
	junio  ='0.0';
	julio  =NEW.monto_mensual;
	agosto  =NEW.monto_mensual;
	septiembre  ='0.0';
	octubre   ='0.0';
	noviembre  ='0.0';
	diciembre  ='0.0';		
	vdeuda=2*NEW.monto_mensual::numeric;
else 
	enero ='0.0';
	febrero  ='0.0';
	marzo ='0.0';
	abril  ='0.0';
	mayo ='0.0';
	junio  ='0.0';
	julio   ='0.0';
	agosto  =NEW.monto_mensual;
	septiembre  ='0.0';
	octubre   ='0.0';
	noviembre  ='0.0';
	diciembre  ='0.0';
	vdeuda=NEW.monto_mensual::numeric;
end if;

if new.mes_inicio=9 then
    vdeuda=vdeuda+NEW.monto_inscripcion;
end if;

INSERT INTO sibes_mensualidades(
             fkinscripcion, monto_inscripcion, mes_09, mes_10, 
            mes_11, mes_12, mes_01, mes_02, mes_03, mes_04, mes_05, mes_06, 
            mes_07, mes_08, deuda, pagado, ultimo_mes_pagado, monto_ultimo_mes, 
            pago_prox, estatus)
	    VALUES (NEW.fkinscripcion, NEW.monto_inscripcion, septiembre, octubre, 
		    noviembre, diciembre, enero, febrero, marzo, abrir, mayo, junio, 
		    julio, agosto, vdeuda, 0, 0, '0.0', 
		    'INSCRIPCION', 'ACTIVO');
	
END IF;	

 RETURN NEW;
END;
$$;
 .   DROP FUNCTION public.crear_pagos_mensuales();
       public       roberto    false    8    1                       1255    535159    insertar_condicion()    FUNCTION     ^  CREATE FUNCTION insertar_condicion() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
	CASE WHEN (NEW.clase_nomina = 'PA') THEN
		INSERT INTO condiciones_trab (trabajador, fkcondicion) VALUES (NEW.trabajador, 2);
	ELSE
		INSERT INTO condiciones_trab (trabajador, fkcondicion) VALUES (NEW.trabajador, 3);
	END CASE;
 RETURN NEW;
END;
$$;
 +   DROP FUNCTION public.insertar_condicion();
       public       roberto    false    1    8            "           1255    1091806 '   lista_trabajadores_del_supervisor(text)    FUNCTION       CREATE FUNCTION lista_trabajadores_del_supervisor(ilogin text) RETURNS TABLE(trabajador character varying, nombre character varying, email character varying, siglado character varying, trabajador_sup character varying, nombre_sup character varying, nivel_jerarquico integer)
    LANGUAGE plpgsql
    AS $$
DECLARE
    filtro TEXT;
    query TEXT;
	itrabajador character varying(10);
    inivel_jerarquico integer;	
BEGIN
-- Obtener valores de trabajador y nivel_jerarquico seg�n ilogin
    SELECT dot.trabajador, dot.nivel_jerarquico INTO itrabajador, inivel_jerarquico
    FROM adam_vw_dotacion_briqven_02_mas AS dot
    WHERE dot.siglado = ilogin;
    
    CASE inivel_jerarquico
        WHEN 1 THEN
            filtro := 'direccion';
        WHEN 2 THEN
            filtro := 'direccion';
        WHEN 3 THEN
            filtro := 'gergral';
        WHEN 4 THEN
            filtro := 'gerencia';
        WHEN 5 THEN
            filtro := 'depto';
        WHEN 6 THEN
            filtro := 'coordina';
        ELSE
            filtro := 'coordina';
    END CASE;

    query := 'SELECT trabajador, nombre, email, siglado, trabajador_sup, nombre_sup, nivel_jerarquico FROM adam_vw_dotacion_briqven_02_mas 
              WHERE ' || filtro || ' = (SELECT ' || filtro || ' FROM adam_vw_dotacion_briqven_02_mas WHERE trim(trabajador)=' || quote_literal(itrabajador) || ') 
              AND nivel_jerarquico::integer >= ' || quote_literal(inivel_jerarquico) || ' ';
    
    query := query || 'UNION ';
    
    query := query || 'SELECT trabajador, nombre, email, siglado, trabajador_sup, nombre_sup, nivel_jerarquico FROM adam_vw_dotacion_briqven_02_mas 
                       WHERE trim(trabajador_sup) = ' || quote_literal(itrabajador) || ' ';
    
    query := query || 'UNION ';
    
    query := query || 'SELECT trabajador, nombre, email, siglado, trabajador_sup, nombre_sup, nivel_jerarquico FROM adam_vw_dotacion_briqven_02_mas 
                       WHERE trabajador_sup IN (
                           SELECT trabajador FROM adam_vw_dotacion_briqven_02_mas 
                           WHERE depto = (
                               SELECT depto FROM adam_vw_dotacion_briqven_02_mas 
                               WHERE trim(trabajador)=' || quote_literal(itrabajador) || ') 
                               AND nivel_jerarquico::integer >= ' || quote_literal(inivel_jerarquico) || ') ';
    
    query := query || 'UNION ';
    
    query := query || 'SELECT s.trabajador, s.nombre, br.email, br.siglado, br.trabajador_sup, br.nombre_sup, s.nivel_jerarquico FROM supervisores_trabajadores AS s 
						LEFT JOIN adam_vw_dotacion_briqven_02_mas AS br 
						ON br.trabajador = s.trabajador
    					WHERE s.trabajador_sup =  ' || quote_literal(itrabajador) || ' ';

    -- Ejecutar la consulta
    RETURN QUERY EXECUTE query;

END;
$$;
 E   DROP FUNCTION public.lista_trabajadores_del_supervisor(ilogin text);
       public       roberto    false    8    1                       1255    264107    ultimo_dia_del_mes(date)    FUNCTION     O  CREATE FUNCTION ultimo_dia_del_mes(date) RETURNS double precision
    LANGUAGE plpgsql STRICT
    AS $_$
DECLARE
 fch ALIAS FOR $1;
 ultimo_dia double precision DEFAULT 0;
BEGIN
select extract (day from (select date_trunc('month', fch) 
+ interval '1 month') - interval '1 day') into ultimo_dia;
RETURN ultimo_dia;	
END;
$_$;
 /   DROP FUNCTION public.ultimo_dia_del_mes(date);
       public       roberto    false    1    8            �            1259    553269    adam_vw_dotacion_briqven_02_mas    TABLE     M  CREATE TABLE adam_vw_dotacion_briqven_02_mas (
    trabajador character varying(10) NOT NULL,
    nombre character varying(50) NOT NULL,
    sexo character varying(1),
    fecha_ingreso date,
    fecha_nacimiento date,
    relacion_laboral character varying(1),
    sistema_horario integer,
    talla_camisa character varying(5),
    talla_pantalon character varying(5),
    talla_zapatos character varying(5),
    codigo_carnet character varying(10),
    serial_carnet character varying(10),
    procedencia character varying(50),
    trabajador_onapre character varying(20),
    contratacion_onapre character varying(20),
    grado_trab character varying(10),
    rango_trab character varying(10),
    condicion character varying(30),
    tipo_discapacidad character varying(30),
    salario character varying(15),
    ccosto character varying(10),
    detalle_ccosto character varying(50),
    direccion character varying(50),
    gergral character varying(50),
    gerencia character varying(50),
    depto character varying(50),
    coordina character varying(50),
    puesto character varying(10),
    desc_puesto character varying(50),
    nivel_jerarquico integer,
    desc_nivel_jerarquico character varying(50),
    grupo character varying(50),
    area character varying(50),
    subarea character varying(50),
    detalle_subarea character varying(50),
    encuadre_puesto character varying(50),
    encuadre_onapre character varying(50),
    clasificacion_onapre character varying(50),
    encuadre2_onapre character varying(50),
    puesto_superior character varying(10),
    desc_psuperior character varying(50),
    trabajador_sup character varying(10),
    nombre_sup character varying(50),
    grado_instruccion character varying(50),
    titulo_profesional character varying(50),
    siglado character varying(6),
    email character varying(100)
);
 3   DROP TABLE public.adam_vw_dotacion_briqven_02_mas;
       public         roberto    false    8            �            1259    536876    baremo    TABLE     �   CREATE TABLE baremo (
    id integer NOT NULL,
    puntuacion integer NOT NULL,
    resultado character varying(100) NOT NULL,
    porcentaje integer NOT NULL
);
    DROP TABLE public.baremo;
       public         roberto    true    8            �            1259    536874    baremo_id_seq    SEQUENCE     o   CREATE SEQUENCE baremo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 $   DROP SEQUENCE public.baremo_id_seq;
       public       roberto    false    199    8            _	           0    0    baremo_id_seq    SEQUENCE OWNED BY     1   ALTER SEQUENCE baremo_id_seq OWNED BY baremo.id;
            public       roberto    false    198            �            1259    62908    carga_familiar_hcm    TABLE     �  CREATE TABLE carga_familiar_hcm (
    trabajador character varying(10),
    persona_relacionada character varying(10),
    nombre character varying(40),
    sexo integer,
    fecha_nacimiento date,
    domicilio character varying(40),
    domicilio2 character varying(40),
    telefono_particular character varying(20),
    indice_inf_soc character varying(10),
    secuencia integer,
    hcm integer,
    maternidad integer,
    dato_01 character varying(15),
    dato_02 character varying(15),
    dato_03 character varying(10),
    dato_04 character varying(10),
    dato_05 character varying(20),
    sit_carga integer DEFAULT 1 NOT NULL
);
 &   DROP TABLE public.carga_familiar_hcm;
       public         roberto    false    8            �            1259    107388    causas_baja    TABLE     |   CREATE TABLE causas_baja (
    causa character varying(2) NOT NULL,
    descripcion_baja character varying(100) NOT NULL
);
    DROP TABLE public.causas_baja;
       public         roberto    false    8            �            1259    357710    ccostos_x_gerencias    TABLE     �   CREATE TABLE ccostos_x_gerencias (
    ccosto integer NOT NULL,
    gerencia integer NOT NULL,
    descripcion_gerencia character varying(50) NOT NULL
);
 '   DROP TABLE public.ccostos_x_gerencias;
       public         roberto    false    8            �            1259    535063    condiciones    TABLE     m   CREATE TABLE condiciones (
    idcondicion integer NOT NULL,
    condicion character varying(50) NOT NULL
);
    DROP TABLE public.condiciones;
       public         roberto    false    8            �            1259    535061    condiciones_idcondicion_seq    SEQUENCE     }   CREATE SEQUENCE condiciones_idcondicion_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 2   DROP SEQUENCE public.condiciones_idcondicion_seq;
       public       roberto    false    8    196            `	           0    0    condiciones_idcondicion_seq    SEQUENCE OWNED BY     M   ALTER SEQUENCE condiciones_idcondicion_seq OWNED BY condiciones.idcondicion;
            public       roberto    false    195            �            1259    535022    condiciones_trab    TABLE     s   CREATE TABLE condiciones_trab (
    trabajador character varying(10) NOT NULL,
    fkcondicion integer NOT NULL
);
 $   DROP TABLE public.condiciones_trab;
       public         roberto    false    8            �            1259    536900 
   evaluacion    TABLE     �   CREATE TABLE evaluacion (
    periodo integer NOT NULL,
    trabajador character varying(10) NOT NULL,
    puntuacion integer NOT NULL,
    observacion text,
    supervisor character varying(10) NOT NULL,
    fecha_reg date NOT NULL
);
    DROP TABLE public.evaluacion;
       public         roberto    false    8            �            1259    358377    gerencias_generales    TABLE     �   CREATE TABLE gerencias_generales (
    descripcion_ggral character varying(50) NOT NULL,
    ccosto_gral integer NOT NULL,
    desccosto_gral character varying(10) NOT NULL,
    dependientes integer NOT NULL
);
 '   DROP TABLE public.gerencias_generales;
       public         roberto    false    8            �            1259    69911    idunidad_seq    SEQUENCE     r   CREATE SEQUENCE idunidad_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 61902;
 #   DROP SEQUENCE public.idunidad_seq;
       public       roberto    false    8            �            1259    536888 	   periodo_e    TABLE     �   CREATE TABLE periodo_e (
    num_periodo integer NOT NULL,
    desde date NOT NULL,
    hasta date NOT NULL,
    status integer DEFAULT 0 NOT NULL
);
    DROP TABLE public.periodo_e;
       public         roberto    true    8            �            1259    536886    periodo_e_num_periodo_seq    SEQUENCE     {   CREATE SEQUENCE periodo_e_num_periodo_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 0   DROP SEQUENCE public.periodo_e_num_periodo_seq;
       public       roberto    false    8    201            a	           0    0    periodo_e_num_periodo_seq    SEQUENCE OWNED BY     I   ALTER SEQUENCE periodo_e_num_periodo_seq OWNED BY periodo_e.num_periodo;
            public       roberto    false    200            �            1259    739072    periodos_nomina    TABLE     9  CREATE TABLE periodos_nomina (
    inicio date NOT NULL,
    fin date NOT NULL,
    tipo_nomina character varying(2) NOT NULL,
    mes integer NOT NULL,
    anio integer NOT NULL,
    abierto boolean NOT NULL,
    fecha_cierre date,
    fecha_pago date,
    id_calendario integer NOT NULL,
    semanas integer
);
 #   DROP TABLE public.periodos_nomina;
       public         roberto    false    8            �            1259    62911    trabajadores    TABLE       CREATE TABLE trabajadores (
    trabajador character varying(10) NOT NULL,
    registro_fiscal character varying(20),
    nombre character varying(100),
    sexo character(1),
    fecha_nacimiento date,
    domicilio character varying(40),
    domicilio2 character varying(40),
    poblacion character varying(50),
    estado_provincia character varying(30),
    pais character varying(30),
    codigo_postal character varying(10),
    calles_aledanas character varying(80),
    telefono_particular character varying(20),
    reg_seguro_social character varying(15),
    domicilio3 character varying(40),
    e_mail character varying(100),
    fkunidad integer,
    tipo_documento character(1),
    nombres character varying(50),
    apellidos character varying(50),
    edo_civil character(1)
);
     DROP TABLE public.trabajadores;
       public         roberto    false    8            �            1259    62914    trabajadores_grales    TABLE     e  CREATE TABLE trabajadores_grales (
    trabajador character varying(10) NOT NULL,
    fecha_ingreso date,
    fecha_antiguedad date,
    fecha_baja date,
    fecha_vto_contrato date,
    causa_baja character varying(2),
    relacion_laboral character(1),
    telefono_oficina character varying(20),
    extension_telefonica integer,
    clase_nomina character(2),
    sistema_antiguedad integer,
    sistema_horario integer,
    turno integer,
    forma_pago integer,
    sit_trabajador integer,
    grupo_sanguinio character varying(5),
    cargo character varying(100),
    ctadeposito character varying(20)
);
 '   DROP TABLE public.trabajadores_grales;
       public         roberto    false    8            �            1259    382109    personal_activo_con_correo    VIEW     |  CREATE VIEW personal_activo_con_correo AS
 SELECT trabajadores.trabajador,
    trabajadores.nombres,
    trabajadores.apellidos,
        CASE
            WHEN ((trabajadores.e_mail)::text ~~ '%briqven%'::text) THEN (((trabajadores.e_mail)::text || '.com.ve'::text))::character varying
            ELSE trabajadores.e_mail
        END AS correo
   FROM trabajadores,
    trabajadores_grales
  WHERE (((((trabajadores.e_mail)::text ~~ '%briqven%'::text) OR ((trabajadores.e_mail)::text ~~ '%sidor.com%'::text)) AND (trabajadores_grales.sit_trabajador = 1)) AND ((trabajadores.trabajador)::text = (trabajadores_grales.trabajador)::text));
 -   DROP VIEW public.personal_activo_con_correo;
       public       roberto    false    177    177    176    176    176    176    8            �            1259    63045    regalos    TABLE     �   CREATE TABLE regalos (
    idopcion integer NOT NULL,
    descripcion_regalo character varying(100) NOT NULL,
    grupo_opcion character(1)
);
    DROP TABLE public.regalos;
       public         roberto    false    8            �            1259    536852    registro_diario    TABLE       CREATE TABLE registro_diario (
    trabajador character varying(10) NOT NULL,
    fecha date NOT NULL,
    entrada_real1 time without time zone DEFAULT '00:00:00'::time without time zone,
    salida_real1 time without time zone DEFAULT '00:00:00'::time without time zone,
    asistio character varying(1) DEFAULT 'N'::character varying,
    sobre_tiempo character varying(1) DEFAULT 'N'::character varying,
    comision character varying(1) DEFAULT 'N'::character varying,
    cambio_turno character varying(1) DEFAULT 'N'::character varying,
    inasistencia integer,
    observacion text NOT NULL,
    fecha_reg timestamp without time zone,
    trabajador_reg character varying(10),
    bloqueado integer DEFAULT 0,
    turno character varying(50),
    grupo integer
);
 #   DROP TABLE public.registro_diario;
       public         postgres    false    8            b	           0    0     COLUMN registro_diario.bloqueado    COMMENT     b   COMMENT ON COLUMN registro_diario.bloqueado IS 'Si el registro no permite cambios (0=no; 1 =si)';
            public       postgres    false    197            c	           0    0    registro_diario    ACL     �   REVOKE ALL ON TABLE registro_diario FROM PUBLIC;
REVOKE ALL ON TABLE registro_diario FROM postgres;
GRANT ALL ON TABLE registro_diario TO postgres;
            public       postgres    false    197            �            1259    63042    seleccion_regalos    TABLE     �   CREATE TABLE seleccion_regalos (
    trabajador character varying(10) NOT NULL,
    periodo character varying(6) DEFAULT '201601'::character varying NOT NULL,
    fkopcion integer NOT NULL,
    estatus character varying(20)
);
 %   DROP TABLE public.seleccion_regalos;
       public         roberto    false    8            �            1259    808145    sibes_beneficiarios    TABLE     m  CREATE TABLE sibes_beneficiarios (
    idbeneficiario integer NOT NULL,
    trabajador character varying(10) NOT NULL,
    fecha_nac date NOT NULL,
    sexo_beneficiario character(1) NOT NULL,
    pago_colegio boolean,
    estatus_beneficio character varying(10) NOT NULL,
    nombre_beneficiario character varying(80) NOT NULL,
    cedula character varying(10)
);
 '   DROP TABLE public.sibes_beneficiarios;
       public         roberto    false    8            �            1259    808143 &   sibes_beneficiarios_idbeneficiario_seq    SEQUENCE     �   CREATE SEQUENCE sibes_beneficiarios_idbeneficiario_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 =   DROP SEQUENCE public.sibes_beneficiarios_idbeneficiario_seq;
       public       roberto    false    8    212            d	           0    0 &   sibes_beneficiarios_idbeneficiario_seq    SEQUENCE OWNED BY     c   ALTER SEQUENCE sibes_beneficiarios_idbeneficiario_seq OWNED BY sibes_beneficiarios.idbeneficiario;
            public       roberto    false    211            �            1259    808180    sibes_colegios    TABLE       CREATE TABLE sibes_colegios (
    idcolegio integer NOT NULL,
    rif_colegio character varying(12),
    nombre_colegio character varying(50),
    estatus_colegio character varying(10),
    direccion_colegio character varying(350),
    localidada_colegio character varying(20)
);
 "   DROP TABLE public.sibes_colegios;
       public         roberto    false    8            �            1259    808178    sibes_colegios_idcolegio_seq    SEQUENCE     ~   CREATE SEQUENCE sibes_colegios_idcolegio_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 3   DROP SEQUENCE public.sibes_colegios_idcolegio_seq;
       public       roberto    false    214    8            e	           0    0    sibes_colegios_idcolegio_seq    SEQUENCE OWNED BY     O   ALTER SEQUENCE sibes_colegios_idcolegio_seq OWNED BY sibes_colegios.idcolegio;
            public       roberto    false    213            �            1259    808279    sibes_detfacturas    TABLE     �  CREATE TABLE sibes_detfacturas (
    iddetfactura integer NOT NULL,
    fkfactura integer NOT NULL,
    fkmensualidad integer NOT NULL,
    mes integer,
    monto character varying(15) NOT NULL,
    corresponde character varying(15),
    fecha_modificacion timestamp without time zone,
    login_modificacion character varying(6),
    tasa_cambio character varying(6),
    fkbeneficiario integer
);
 %   DROP TABLE public.sibes_detfacturas;
       public         roberto    false    8            �            1259    808277 "   sibes_detfacturas_iddetfactura_seq    SEQUENCE     �   CREATE SEQUENCE sibes_detfacturas_iddetfactura_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 9   DROP SEQUENCE public.sibes_detfacturas_iddetfactura_seq;
       public       roberto    false    8    222            f	           0    0 "   sibes_detfacturas_iddetfactura_seq    SEQUENCE OWNED BY     [   ALTER SEQUENCE sibes_detfacturas_iddetfactura_seq OWNED BY sibes_detfacturas.iddetfactura;
            public       roberto    false    221            �            1259    808238    sibes_facturas    TABLE     �  CREATE TABLE sibes_facturas (
    idfactura integer NOT NULL,
    nro_factura character varying(10) NOT NULL,
    fecha_factura date NOT NULL,
    monto_total character varying(15) NOT NULL,
    subtotal character varying(15) NOT NULL,
    iva character varying(6) NOT NULL,
    fkcolegio integer NOT NULL,
    login_registro character varying(6) NOT NULL,
    fecha_registro timestamp without time zone NOT NULL,
    fecha_modificacion timestamp without time zone,
    login_modificacion character varying(6),
    tasa_cambio character varying(6),
    fecha_entrega_rrhh timestamp without time zone NOT NULL,
    trabajador character varying(10)
);
 "   DROP TABLE public.sibes_facturas;
       public         roberto    false    8            �            1259    1101083    sibes_facturas_beneficiarios    TABLE     �   CREATE TABLE sibes_facturas_beneficiarios (
    idfacturabenf integer NOT NULL,
    fkfactura integer NOT NULL,
    fkbeneficiario integer NOT NULL
);
 0   DROP TABLE public.sibes_facturas_beneficiarios;
       public         roberto    false    8            �            1259    1101081 .   sibes_facturas_beneficiarios_idfacturabenf_seq    SEQUENCE     �   CREATE SEQUENCE sibes_facturas_beneficiarios_idfacturabenf_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 E   DROP SEQUENCE public.sibes_facturas_beneficiarios_idfacturabenf_seq;
       public       roberto    false    229    8            g	           0    0 .   sibes_facturas_beneficiarios_idfacturabenf_seq    SEQUENCE OWNED BY     s   ALTER SEQUENCE sibes_facturas_beneficiarios_idfacturabenf_seq OWNED BY sibes_facturas_beneficiarios.idfacturabenf;
            public       roberto    false    228            �            1259    808236    sibes_facturas_idfactura_seq    SEQUENCE     ~   CREATE SEQUENCE sibes_facturas_idfactura_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 3   DROP SEQUENCE public.sibes_facturas_idfactura_seq;
       public       roberto    false    218    8            h	           0    0    sibes_facturas_idfactura_seq    SEQUENCE OWNED BY     O   ALTER SEQUENCE sibes_facturas_idfactura_seq OWNED BY sibes_facturas.idfactura;
            public       roberto    false    217            �            1259    808208    sibes_inscripciones    TABLE     o  CREATE TABLE sibes_inscripciones (
    idinscripcion integer NOT NULL,
    fkbeneficiario integer NOT NULL,
    fkcolegio integer NOT NULL,
    fecha_inscripcion date NOT NULL,
    anio_escolar integer NOT NULL,
    monto_inscripcion character varying(15) NOT NULL,
    monto_mensual character varying(15) NOT NULL,
    login_registro character varying(6),
    fecha_registro timestamp without time zone,
    estatus_inscripcioin character varying(10),
    mes_inicio integer NOT NULL,
    fecha_modificacion timestamp without time zone,
    login_modificacion character varying(6),
    tasa_cambio character varying(6)
);
 '   DROP TABLE public.sibes_inscripciones;
       public         roberto    false    8            �            1259    808206 %   sibes_inscripciones_idinscripcion_seq    SEQUENCE     �   CREATE SEQUENCE sibes_inscripciones_idinscripcion_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 <   DROP SEQUENCE public.sibes_inscripciones_idinscripcion_seq;
       public       roberto    false    216    8            i	           0    0 %   sibes_inscripciones_idinscripcion_seq    SEQUENCE OWNED BY     a   ALTER SEQUENCE sibes_inscripciones_idinscripcion_seq OWNED BY sibes_inscripciones.idinscripcion;
            public       roberto    false    215            �            1259    808259    sibes_mensualidades    TABLE     K  CREATE TABLE sibes_mensualidades (
    idmensualidad integer NOT NULL,
    fkinscripcion integer NOT NULL,
    monto_inscripcion character varying(15) NOT NULL,
    mes_09 character varying(15) NOT NULL,
    mes_10 character varying(15) NOT NULL,
    mes_11 character varying(15) NOT NULL,
    mes_12 character varying(15) NOT NULL,
    mes_01 character varying(15) NOT NULL,
    mes_02 character varying(15) NOT NULL,
    mes_03 character varying(15) NOT NULL,
    mes_04 character varying(15) NOT NULL,
    mes_05 character varying(15) NOT NULL,
    mes_06 character varying(15) NOT NULL,
    mes_07 character varying(15) NOT NULL,
    mes_08 character varying(15) NOT NULL,
    deuda character varying(15) NOT NULL,
    pagado character varying(15) NOT NULL,
    ultimo_mes_pagado integer DEFAULT 0,
    monto_ultimo_mes character varying(15) NOT NULL,
    fecha_ult_pago date,
    pago_prox character varying(15) NOT NULL,
    estatus character varying(10),
    fecha_modificacion timestamp without time zone,
    login_modificacion character varying(6),
    tasa_cambio character varying(6)
);
 '   DROP TABLE public.sibes_mensualidades;
       public         roberto    false    8            �            1259    808257 %   sibes_mensualidades_idmensualidad_seq    SEQUENCE     �   CREATE SEQUENCE sibes_mensualidades_idmensualidad_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 <   DROP SEQUENCE public.sibes_mensualidades_idmensualidad_seq;
       public       roberto    false    220    8            j	           0    0 %   sibes_mensualidades_idmensualidad_seq    SEQUENCE OWNED BY     a   ALTER SEQUENCE sibes_mensualidades_idmensualidad_seq OWNED BY sibes_mensualidades.idmensualidad;
            public       roberto    false    219            �            1259    1101006    sibes_menus    TABLE     
  CREATE TABLE sibes_menus (
    idmenu integer NOT NULL,
    idpadre integer,
    name character varying(100),
    url character varying(200),
    href character varying(200),
    icon character varying(200),
    badge_text character varying(100),
    badge_variant character varying(100),
    badge_class character varying(100),
    variant character varying(100),
    attributes character varying(100),
    attributes_element character varying(100),
    divider boolean,
    class character varying(100),
    label_class character varying(100),
    label_variant character varying(100),
    wrapper_attributes character varying(100),
    wrapper_element character varying(100),
    linkprops character varying(300),
    title boolean,
    estatus boolean,
    orden integer
);
    DROP TABLE public.sibes_menus;
       public         roberto    false    8            �            1259    1101004    sibes_menus_idmenu_seq    SEQUENCE     x   CREATE SEQUENCE sibes_menus_idmenu_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 -   DROP SEQUENCE public.sibes_menus_idmenu_seq;
       public       roberto    false    226    8            k	           0    0    sibes_menus_idmenu_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE sibes_menus_idmenu_seq OWNED BY sibes_menus.idmenu;
            public       roberto    false    225            �            1259    1101016    sibes_menus_usuarios    TABLE     �   CREATE TABLE sibes_menus_usuarios (
    idmenu integer NOT NULL,
    login character varying(6) NOT NULL,
    pupdate boolean NOT NULL,
    pinsert boolean NOT NULL,
    pdelete boolean,
    pselect boolean
);
 (   DROP TABLE public.sibes_menus_usuarios;
       public         roberto    false    8            �            1259    1057160    sistema_horario    TABLE     s   CREATE TABLE sistema_horario (
    sistema_horario integer NOT NULL,
    descripcion character varying NOT NULL
);
 #   DROP TABLE public.sistema_horario;
       public         roberto    false    8            �            1259    1030806    supervisores_trabajadores    TABLE     �   CREATE TABLE supervisores_trabajadores (
    trabajador character varying(10) NOT NULL,
    nombre character varying(100),
    nivel_jerarquico integer,
    trabajador_sup character varying(10)
);
 -   DROP TABLE public.supervisores_trabajadores;
       public         roberto    false    8            �            1259    551163    tbl_auditorias    TABLE     �   CREATE TABLE tbl_auditorias (
    idauditoria integer NOT NULL,
    fecha time without time zone,
    operacion character varying(100),
    login character varying(6)
);
 "   DROP TABLE public.tbl_auditorias;
       public         roberto    false    8            l	           0    0    tbl_auditorias    ACL     �   REVOKE ALL ON TABLE tbl_auditorias FROM PUBLIC;
REVOKE ALL ON TABLE tbl_auditorias FROM roberto;
GRANT ALL ON TABLE tbl_auditorias TO roberto;
            public       roberto    false    208            �            1259    551161    tbl_auditorias_idauditoria_seq    SEQUENCE     �   CREATE SEQUENCE tbl_auditorias_idauditoria_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 5   DROP SEQUENCE public.tbl_auditorias_idauditoria_seq;
       public       roberto    false    8    208            m	           0    0    tbl_auditorias_idauditoria_seq    SEQUENCE OWNED BY     S   ALTER SEQUENCE tbl_auditorias_idauditoria_seq OWNED BY tbl_auditorias.idauditoria;
            public       roberto    false    207            �            1259    268643    temp_trabajadores    TABLE     ?  CREATE TABLE temp_trabajadores (
    trabajador character varying(10),
    registro_fiscal character varying(20),
    nombre character varying(100),
    sexo character(1),
    fecha_nacimiento date,
    domicilio character varying(40),
    domicilio2 character varying(40),
    poblacion character varying(50),
    estado_provincia character varying(30),
    pais character varying(30),
    codigo_postal character varying(10),
    calles_aledanas character varying(80),
    telefono_particular character varying(20),
    reg_seguro_social character varying(15),
    domicilio3 character varying(40),
    e_mail character varying(100),
    fkunidad integer,
    tipo_documento character(1),
    nombres character varying(50),
    apellidos character varying(50),
    edo_civil character(1),
    supervisor character varying(10)
);
 %   DROP TABLE public.temp_trabajadores;
       public         roberto    false    8            �            1259    63007 	   temporal1    TABLE     i  CREATE TABLE temporal1 (
    trabajador character varying(10),
    persona_relacionada character varying(10),
    nombre character varying(40),
    sexo integer,
    fecha_nacimiento date,
    domicilio character varying(40),
    domicilio2 character varying(40),
    telefono_particular character varying(20),
    indice_inf_soc character varying(10),
    secuencia integer,
    hcm integer,
    maternidad integer,
    dato_01 character varying(15),
    dato_02 character varying(15),
    dato_03 character varying(10),
    dato_04 character varying(10),
    dato_05 character varying(20),
    sit_carga integer
);
    DROP TABLE public.temporal1;
       public         roberto    false    8            �            1259    256376    trabajadores_supervisores    TABLE     �   CREATE TABLE trabajadores_supervisores (
    trabajador character varying(10) NOT NULL,
    supervisor character varying(10)
);
 -   DROP TABLE public.trabajadores_supervisores;
       public         roberto    false    8            �            1259    256438    v_trabajadores_supervisores    VIEW        CREATE VIEW v_trabajadores_supervisores AS
 SELECT s.trabajador,
    s.supervisor,
    ((t.nombres)::text || ' '::text) AS nombres_jefe
   FROM (trabajadores_supervisores s
     LEFT JOIN trabajadores t ON (((t.trabajador)::text = (s.supervisor)::text)));
 .   DROP VIEW public.v_trabajadores_supervisores;
       public       roberto    false    185    185    176    176    8            �            1259    383515    trabajadores_activos_con_jefes    VIEW     Y  CREATE VIEW trabajadores_activos_con_jefes AS
 SELECT DISTINCT trabajadores.trabajador,
    trabajadores.e_mail,
    trabajadores.nombres,
    trabajadores.apellidos,
    trabajadores_grales.cargo,
    trabajadores_grales.clase_nomina,
    v_trabajadores_supervisores.supervisor,
    v_trabajadores_supervisores.nombres_jefe,
    trabajadores.fkunidad,
    ccostos_x_gerencias.gerencia,
    ccostos_x_gerencias.descripcion_gerencia
   FROM trabajadores,
    trabajadores_grales,
    v_trabajadores_supervisores,
    ccostos_x_gerencias
  WHERE (((((trabajadores.trabajador)::text = (trabajadores_grales.trabajador)::text) AND (trabajadores.fkunidad = ccostos_x_gerencias.ccosto)) AND ((trabajadores_grales.trabajador)::text = (v_trabajadores_supervisores.trabajador)::text)) AND (trabajadores_grales.sit_trabajador = 1))
  ORDER BY trabajadores.trabajador;
 1   DROP VIEW public.trabajadores_activos_con_jefes;
       public       roberto    false    176    189    189    189    186    186    186    177    177    177    177    176    176    176    176    8            �            1259    540003    trabajadores_encargados    TABLE     �  CREATE TABLE trabajadores_encargados (
    trabajador character varying(10) NOT NULL,
    e_mail character varying(100),
    nombres character varying(50),
    apellidos character varying(50),
    cargo character varying(100),
    clase_nomina character(2),
    supervisor character varying(10) NOT NULL,
    nombres_jefe character varying(50),
    fkunidad integer,
    gerencia integer,
    descripcion_gerencia character varying(50)
);
 +   DROP TABLE public.trabajadores_encargados;
       public         roberto    false    8            �            1259    537624    trabajadores_supervisores_1    TABLE     �   CREATE TABLE trabajadores_supervisores_1 (
    trabajador character varying(10) NOT NULL,
    ccosto integer NOT NULL,
    supervisor character varying(10)
);
 /   DROP TABLE public.trabajadores_supervisores_1;
       public         roberto    false    8            �            1259    537635    v_trabajadores_supervisores_1    VIEW       CREATE VIEW v_trabajadores_supervisores_1 AS
 SELECT s.trabajador,
    s.supervisor,
    (((t.nombres)::text || ' '::text) || (t.apellidos)::text) AS nombres_jefe
   FROM (trabajadores_supervisores_1 s
     LEFT JOIN trabajadores t ON (((t.trabajador)::text = (s.supervisor)::text)));
 0   DROP VIEW public.v_trabajadores_supervisores_1;
       public       roberto    false    176    203    203    176    176    8            �            1259    537639     trabajadores_activos_con_jefes_1    VIEW       CREATE VIEW trabajadores_activos_con_jefes_1 AS
 SELECT DISTINCT trabajadores.trabajador,
    trabajadores.e_mail,
    trabajadores.nombres,
    trabajadores.apellidos,
    trabajadores_grales.cargo,
    trabajadores_grales.clase_nomina,
    v_trabajadores_supervisores_1.supervisor,
    v_trabajadores_supervisores_1.nombres_jefe,
    trabajadores.fkunidad,
    ccostos_x_gerencias.gerencia,
    ccostos_x_gerencias.descripcion_gerencia
   FROM trabajadores,
    trabajadores_grales,
    v_trabajadores_supervisores_1,
    ccostos_x_gerencias
  WHERE (((((trabajadores.trabajador)::text = (trabajadores_grales.trabajador)::text) AND (trabajadores.fkunidad = ccostos_x_gerencias.ccosto)) AND ((trabajadores_grales.trabajador)::text = (v_trabajadores_supervisores_1.trabajador)::text)) AND (trabajadores_grales.sit_trabajador = 1))
UNION
 SELECT trabajadores_encargados.trabajador,
    trabajadores_encargados.e_mail,
    trabajadores_encargados.nombres,
    trabajadores_encargados.apellidos,
    trabajadores_encargados.cargo,
    trabajadores_encargados.clase_nomina,
    trabajadores_encargados.supervisor,
    trabajadores_encargados.nombres_jefe,
    trabajadores_encargados.fkunidad,
    trabajadores_encargados.gerencia,
    trabajadores_encargados.descripcion_gerencia
   FROM trabajadores_encargados;
 3   DROP VIEW public.trabajadores_activos_con_jefes_1;
       public       roberto    false    176    176    176    176    176    177    177    177    177    189    189    189    204    204    204    206    206    206    206    206    206    206    206    206    206    206    8            �            1259    153994    unidades    TABLE       CREATE TABLE unidades (
    idunidad integer DEFAULT nextval('idunidad_seq'::regclass) NOT NULL,
    descripcion_unidad character varying(100) NOT NULL,
    dependencia character varying(10),
    centro_costo character varying(10) NOT NULL,
    jefe_unidad character varying(10)
);
    DROP TABLE public.unidades;
       public         roberto    false    182    8            �            1259    62917    usuarios    TABLE     ;  CREATE TABLE usuarios (
    login_username character varying(6) NOT NULL,
    trabajador character varying(10) NOT NULL,
    estatus character varying(10) NOT NULL,
    nivel integer NOT NULL,
    fecha_ultima_sesion timestamp without time zone,
    login_userpass character varying(32) NOT NULL,
    email text
);
    DROP TABLE public.usuarios;
       public         roberto    false    8            �            1259    268619    v_trabajadores_activos    VIEW     �   CREATE VIEW v_trabajadores_activos AS
 SELECT t.trabajador,
    t.nombre,
    s.turno
   FROM (trabajadores t
     JOIN trabajadores_grales s ON (((t.trabajador)::text = (s.trabajador)::text)))
  WHERE (s.sit_trabajador = 1);
 )   DROP VIEW public.v_trabajadores_activos;
       public       roberto    false    177    177    177    176    176    8            �            1259    382154    vista_cumples    VIEW     Q  CREATE VIEW vista_cumples AS
 SELECT t.trabajador,
    t.nombres,
    t.apellidos,
        CASE
            WHEN ((t.e_mail)::text ~~ '%briqven%'::text) THEN ((t.e_mail)::text || '.com.ve'::text)
            WHEN ((t.e_mail)::text ~~ '%sidor.com%'::text) THEN (t.e_mail)::text
            ELSE ''::text
        END AS correo,
    (((t.nombres)::text || ' '::text) || (t.apellidos)::text) AS nombrecompl,
    t.sexo,
    t.fecha_nacimiento,
    age((t.fecha_nacimiento)::timestamp with time zone) AS edad,
    g.cargo,
    u.centro_costo,
    u.descripcion_unidad,
    date_part('month'::text, age((t.fecha_nacimiento)::timestamp with time zone)) AS mes,
    date_part('day'::text, age((t.fecha_nacimiento)::timestamp with time zone)) AS dia,
    age((g.fecha_ingreso)::timestamp with time zone) AS antiguedad,
    g.fecha_ingreso,
        CASE
            WHEN ((date_part('month'::text, age((t.fecha_nacimiento)::timestamp with time zone)) = (0)::double precision) AND (date_part('day'::text, age((t.fecha_nacimiento)::timestamp with time zone)) = (0)::double precision)) THEN 'HOY'::text
            WHEN ((date_part('month'::text, age((t.fecha_nacimiento)::timestamp with time zone)) = (0)::double precision) AND (date_part('day'::text, age((t.fecha_nacimiento)::timestamp with time zone)) = (1)::double precision)) THEN 'AYER'::text
            WHEN ((date_part('month'::text, age((t.fecha_nacimiento)::timestamp with time zone)) = (11)::double precision) AND (date_part('day'::text, age((t.fecha_nacimiento)::timestamp with time zone)) = (( SELECT ultimo_dia_del_mes((now())::date) AS ultimo_dia_del_mes) - (1)::double precision))) THEN 'MA&ntilde;ANA'::text
            ELSE 'LUEGO'::text
        END AS cuando,
    date_part('month'::text, t.fecha_nacimiento) AS mes_cumple
   FROM trabajadores t,
    trabajadores_grales g,
    unidades u
  WHERE ((((t.trabajador)::text = (g.trabajador)::text) AND (g.sit_trabajador = 1)) AND (t.fkunidad = u.idunidad))
  ORDER BY date_part('month'::text, age((t.fecha_nacimiento)::timestamp with time zone)), date_part('day'::text, age((t.fecha_nacimiento)::timestamp with time zone));
     DROP VIEW public.vista_cumples;
       public       roberto    false    184    177    176    176    176    176    177    177    177    176    176    176    283    184    184    8            ^           2604    536879    id    DEFAULT     X   ALTER TABLE ONLY baremo ALTER COLUMN id SET DEFAULT nextval('baremo_id_seq'::regclass);
 8   ALTER TABLE public.baremo ALTER COLUMN id DROP DEFAULT;
       public       roberto    false    199    198    199            V           2604    535066    idcondicion    DEFAULT     t   ALTER TABLE ONLY condiciones ALTER COLUMN idcondicion SET DEFAULT nextval('condiciones_idcondicion_seq'::regclass);
 F   ALTER TABLE public.condiciones ALTER COLUMN idcondicion DROP DEFAULT;
       public       roberto    false    196    195    196            _           2604    536891    num_periodo    DEFAULT     p   ALTER TABLE ONLY periodo_e ALTER COLUMN num_periodo SET DEFAULT nextval('periodo_e_num_periodo_seq'::regclass);
 D   ALTER TABLE public.periodo_e ALTER COLUMN num_periodo DROP DEFAULT;
       public       roberto    false    200    201    201            b           2604    808148    idbeneficiario    DEFAULT     �   ALTER TABLE ONLY sibes_beneficiarios ALTER COLUMN idbeneficiario SET DEFAULT nextval('sibes_beneficiarios_idbeneficiario_seq'::regclass);
 Q   ALTER TABLE public.sibes_beneficiarios ALTER COLUMN idbeneficiario DROP DEFAULT;
       public       roberto    false    212    211    212            c           2604    808183 	   idcolegio    DEFAULT     v   ALTER TABLE ONLY sibes_colegios ALTER COLUMN idcolegio SET DEFAULT nextval('sibes_colegios_idcolegio_seq'::regclass);
 G   ALTER TABLE public.sibes_colegios ALTER COLUMN idcolegio DROP DEFAULT;
       public       roberto    false    213    214    214            h           2604    808282    iddetfactura    DEFAULT     �   ALTER TABLE ONLY sibes_detfacturas ALTER COLUMN iddetfactura SET DEFAULT nextval('sibes_detfacturas_iddetfactura_seq'::regclass);
 M   ALTER TABLE public.sibes_detfacturas ALTER COLUMN iddetfactura DROP DEFAULT;
       public       roberto    false    222    221    222            e           2604    808241 	   idfactura    DEFAULT     v   ALTER TABLE ONLY sibes_facturas ALTER COLUMN idfactura SET DEFAULT nextval('sibes_facturas_idfactura_seq'::regclass);
 G   ALTER TABLE public.sibes_facturas ALTER COLUMN idfactura DROP DEFAULT;
       public       roberto    false    218    217    218            j           2604    1101086    idfacturabenf    DEFAULT     �   ALTER TABLE ONLY sibes_facturas_beneficiarios ALTER COLUMN idfacturabenf SET DEFAULT nextval('sibes_facturas_beneficiarios_idfacturabenf_seq'::regclass);
 Y   ALTER TABLE public.sibes_facturas_beneficiarios ALTER COLUMN idfacturabenf DROP DEFAULT;
       public       roberto    false    229    228    229            d           2604    808211    idinscripcion    DEFAULT     �   ALTER TABLE ONLY sibes_inscripciones ALTER COLUMN idinscripcion SET DEFAULT nextval('sibes_inscripciones_idinscripcion_seq'::regclass);
 P   ALTER TABLE public.sibes_inscripciones ALTER COLUMN idinscripcion DROP DEFAULT;
       public       roberto    false    216    215    216            f           2604    808262    idmensualidad    DEFAULT     �   ALTER TABLE ONLY sibes_mensualidades ALTER COLUMN idmensualidad SET DEFAULT nextval('sibes_mensualidades_idmensualidad_seq'::regclass);
 P   ALTER TABLE public.sibes_mensualidades ALTER COLUMN idmensualidad DROP DEFAULT;
       public       roberto    false    219    220    220            i           2604    1101009    idmenu    DEFAULT     j   ALTER TABLE ONLY sibes_menus ALTER COLUMN idmenu SET DEFAULT nextval('sibes_menus_idmenu_seq'::regclass);
 A   ALTER TABLE public.sibes_menus ALTER COLUMN idmenu DROP DEFAULT;
       public       roberto    false    226    225    226            a           2604    551166    idauditoria    DEFAULT     z   ALTER TABLE ONLY tbl_auditorias ALTER COLUMN idauditoria SET DEFAULT nextval('tbl_auditorias_idauditoria_seq'::regclass);
 I   ALTER TABLE public.tbl_auditorias ALTER COLUMN idauditoria DROP DEFAULT;
       public       roberto    false    207    208    208            A	          0    553269    adam_vw_dotacion_briqven_02_mas 
   TABLE DATA               �  COPY adam_vw_dotacion_briqven_02_mas (trabajador, nombre, sexo, fecha_ingreso, fecha_nacimiento, relacion_laboral, sistema_horario, talla_camisa, talla_pantalon, talla_zapatos, codigo_carnet, serial_carnet, procedencia, trabajador_onapre, contratacion_onapre, grado_trab, rango_trab, condicion, tipo_discapacidad, salario, ccosto, detalle_ccosto, direccion, gergral, gerencia, depto, coordina, puesto, desc_puesto, nivel_jerarquico, desc_nivel_jerarquico, grupo, area, subarea, detalle_subarea, encuadre_puesto, encuadre_onapre, clasificacion_onapre, encuadre2_onapre, puesto_superior, desc_psuperior, trabajador_sup, nombre_sup, grado_instruccion, titulo_profesional, siglado, email) FROM stdin;
    public       roberto    false    209   �r      9	          0    536876    baremo 
   TABLE DATA               @   COPY baremo (id, puntuacion, resultado, porcentaje) FROM stdin;
    public       roberto    false    199   d      n	           0    0    baremo_id_seq    SEQUENCE SET     4   SELECT pg_catalog.setval('baremo_id_seq', 5, true);
            public       roberto    false    198            &	          0    62908    carga_familiar_hcm 
   TABLE DATA               �   COPY carga_familiar_hcm (trabajador, persona_relacionada, nombre, sexo, fecha_nacimiento, domicilio, domicilio2, telefono_particular, indice_inf_soc, secuencia, hcm, maternidad, dato_01, dato_02, dato_03, dato_04, dato_05, sit_carga) FROM stdin;
    public       roberto    false    175   �      .	          0    107388    causas_baja 
   TABLE DATA               7   COPY causas_baja (causa, descripcion_baja) FROM stdin;
    public       roberto    false    183   L�      2	          0    357710    ccostos_x_gerencias 
   TABLE DATA               N   COPY ccostos_x_gerencias (ccosto, gerencia, descripcion_gerencia) FROM stdin;
    public       roberto    false    189   ��      6	          0    535063    condiciones 
   TABLE DATA               6   COPY condiciones (idcondicion, condicion) FROM stdin;
    public       roberto    false    196   ��      o	           0    0    condiciones_idcondicion_seq    SEQUENCE SET     C   SELECT pg_catalog.setval('condiciones_idcondicion_seq', 10, true);
            public       roberto    false    195            4	          0    535022    condiciones_trab 
   TABLE DATA               <   COPY condiciones_trab (trabajador, fkcondicion) FROM stdin;
    public       roberto    false    194   )�      <	          0    536900 
   evaluacion 
   TABLE DATA               b   COPY evaluacion (periodo, trabajador, puntuacion, observacion, supervisor, fecha_reg) FROM stdin;
    public       roberto    false    202   	�      3	          0    358377    gerencias_generales 
   TABLE DATA               d   COPY gerencias_generales (descripcion_ggral, ccosto_gral, desccosto_gral, dependientes) FROM stdin;
    public       roberto    false    190   &�      p	           0    0    idunidad_seq    SEQUENCE SET     7   SELECT pg_catalog.setval('idunidad_seq', 61902, true);
            public       roberto    false    182            ;	          0    536888 	   periodo_e 
   TABLE DATA               ?   COPY periodo_e (num_periodo, desde, hasta, status) FROM stdin;
    public       roberto    false    201   ��      q	           0    0    periodo_e_num_periodo_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('periodo_e_num_periodo_seq', 7, true);
            public       roberto    false    200            B	          0    739072    periodos_nomina 
   TABLE DATA               �   COPY periodos_nomina (inicio, fin, tipo_nomina, mes, anio, abierto, fecha_cierre, fecha_pago, id_calendario, semanas) FROM stdin;
    public       roberto    false    210   ��      ,	          0    63045    regalos 
   TABLE DATA               F   COPY regalos (idopcion, descripcion_regalo, grupo_opcion) FROM stdin;
    public       roberto    false    181   �      7	          0    536852    registro_diario 
   TABLE DATA               �   COPY registro_diario (trabajador, fecha, entrada_real1, salida_real1, asistio, sobre_tiempo, comision, cambio_turno, inasistencia, observacion, fecha_reg, trabajador_reg, bloqueado, turno, grupo) FROM stdin;
    public       postgres    false    197   f�      +	          0    63042    seleccion_regalos 
   TABLE DATA               L   COPY seleccion_regalos (trabajador, periodo, fkopcion, estatus) FROM stdin;
    public       roberto    false    180   ��      D	          0    808145    sibes_beneficiarios 
   TABLE DATA               �   COPY sibes_beneficiarios (idbeneficiario, trabajador, fecha_nac, sexo_beneficiario, pago_colegio, estatus_beneficio, nombre_beneficiario, cedula) FROM stdin;
    public       roberto    false    212   ��      r	           0    0 &   sibes_beneficiarios_idbeneficiario_seq    SEQUENCE SET     N   SELECT pg_catalog.setval('sibes_beneficiarios_idbeneficiario_seq', 1, false);
            public       roberto    false    211            F	          0    808180    sibes_colegios 
   TABLE DATA               �   COPY sibes_colegios (idcolegio, rif_colegio, nombre_colegio, estatus_colegio, direccion_colegio, localidada_colegio) FROM stdin;
    public       roberto    false    214   ��      s	           0    0    sibes_colegios_idcolegio_seq    SEQUENCE SET     D   SELECT pg_catalog.setval('sibes_colegios_idcolegio_seq', 1, false);
            public       roberto    false    213            N	          0    808279    sibes_detfacturas 
   TABLE DATA               �   COPY sibes_detfacturas (iddetfactura, fkfactura, fkmensualidad, mes, monto, corresponde, fecha_modificacion, login_modificacion, tasa_cambio, fkbeneficiario) FROM stdin;
    public       roberto    false    222   ��      t	           0    0 "   sibes_detfacturas_iddetfactura_seq    SEQUENCE SET     J   SELECT pg_catalog.setval('sibes_detfacturas_iddetfactura_seq', 1, false);
            public       roberto    false    221            J	          0    808238    sibes_facturas 
   TABLE DATA               �   COPY sibes_facturas (idfactura, nro_factura, fecha_factura, monto_total, subtotal, iva, fkcolegio, login_registro, fecha_registro, fecha_modificacion, login_modificacion, tasa_cambio, fecha_entrega_rrhh, trabajador) FROM stdin;
    public       roberto    false    218   �      U	          0    1101083    sibes_facturas_beneficiarios 
   TABLE DATA               Y   COPY sibes_facturas_beneficiarios (idfacturabenf, fkfactura, fkbeneficiario) FROM stdin;
    public       roberto    false    229   9�      u	           0    0 .   sibes_facturas_beneficiarios_idfacturabenf_seq    SEQUENCE SET     V   SELECT pg_catalog.setval('sibes_facturas_beneficiarios_idfacturabenf_seq', 1, false);
            public       roberto    false    228            v	           0    0    sibes_facturas_idfactura_seq    SEQUENCE SET     D   SELECT pg_catalog.setval('sibes_facturas_idfactura_seq', 1, false);
            public       roberto    false    217            H	          0    808208    sibes_inscripciones 
   TABLE DATA               
  COPY sibes_inscripciones (idinscripcion, fkbeneficiario, fkcolegio, fecha_inscripcion, anio_escolar, monto_inscripcion, monto_mensual, login_registro, fecha_registro, estatus_inscripcioin, mes_inicio, fecha_modificacion, login_modificacion, tasa_cambio) FROM stdin;
    public       roberto    false    216   V�      w	           0    0 %   sibes_inscripciones_idinscripcion_seq    SEQUENCE SET     M   SELECT pg_catalog.setval('sibes_inscripciones_idinscripcion_seq', 1, false);
            public       roberto    false    215            L	          0    808259    sibes_mensualidades 
   TABLE DATA               D  COPY sibes_mensualidades (idmensualidad, fkinscripcion, monto_inscripcion, mes_09, mes_10, mes_11, mes_12, mes_01, mes_02, mes_03, mes_04, mes_05, mes_06, mes_07, mes_08, deuda, pagado, ultimo_mes_pagado, monto_ultimo_mes, fecha_ult_pago, pago_prox, estatus, fecha_modificacion, login_modificacion, tasa_cambio) FROM stdin;
    public       roberto    false    220   s�      x	           0    0 %   sibes_mensualidades_idmensualidad_seq    SEQUENCE SET     M   SELECT pg_catalog.setval('sibes_mensualidades_idmensualidad_seq', 1, false);
            public       roberto    false    219            R	          0    1101006    sibes_menus 
   TABLE DATA               
  COPY sibes_menus (idmenu, idpadre, name, url, href, icon, badge_text, badge_variant, badge_class, variant, attributes, attributes_element, divider, class, label_class, label_variant, wrapper_attributes, wrapper_element, linkprops, title, estatus, orden) FROM stdin;
    public       roberto    false    226   ��      y	           0    0    sibes_menus_idmenu_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('sibes_menus_idmenu_seq', 1, false);
            public       roberto    false    225            S	          0    1101016    sibes_menus_usuarios 
   TABLE DATA               Z   COPY sibes_menus_usuarios (idmenu, login, pupdate, pinsert, pdelete, pselect) FROM stdin;
    public       roberto    false    227   {�      P	          0    1057160    sistema_horario 
   TABLE DATA               @   COPY sistema_horario (sistema_horario, descripcion) FROM stdin;
    public       roberto    false    224   	�      O	          0    1030806    supervisores_trabajadores 
   TABLE DATA               b   COPY supervisores_trabajadores (trabajador, nombre, nivel_jerarquico, trabajador_sup) FROM stdin;
    public       roberto    false    223   W�      @	          0    551163    tbl_auditorias 
   TABLE DATA               G   COPY tbl_auditorias (idauditoria, fecha, operacion, login) FROM stdin;
    public       roberto    false    208   F      z	           0    0    tbl_auditorias_idauditoria_seq    SEQUENCE SET     E   SELECT pg_catalog.setval('tbl_auditorias_idauditoria_seq', 2, true);
            public       roberto    false    207            1	          0    268643    temp_trabajadores 
   TABLE DATA               <  COPY temp_trabajadores (trabajador, registro_fiscal, nombre, sexo, fecha_nacimiento, domicilio, domicilio2, poblacion, estado_provincia, pais, codigo_postal, calles_aledanas, telefono_particular, reg_seguro_social, domicilio3, e_mail, fkunidad, tipo_documento, nombres, apellidos, edo_civil, supervisor) FROM stdin;
    public       roberto    false    188   c      *	          0    63007 	   temporal1 
   TABLE DATA               �   COPY temporal1 (trabajador, persona_relacionada, nombre, sexo, fecha_nacimiento, domicilio, domicilio2, telefono_particular, indice_inf_soc, secuencia, hcm, maternidad, dato_01, dato_02, dato_03, dato_04, dato_05, sit_carga) FROM stdin;
    public       roberto    false    179   �{      '	          0    62911    trabajadores 
   TABLE DATA               +  COPY trabajadores (trabajador, registro_fiscal, nombre, sexo, fecha_nacimiento, domicilio, domicilio2, poblacion, estado_provincia, pais, codigo_postal, calles_aledanas, telefono_particular, reg_seguro_social, domicilio3, e_mail, fkunidad, tipo_documento, nombres, apellidos, edo_civil) FROM stdin;
    public       roberto    false    176   �|      >	          0    540003    trabajadores_encargados 
   TABLE DATA               �   COPY trabajadores_encargados (trabajador, e_mail, nombres, apellidos, cargo, clase_nomina, supervisor, nombres_jefe, fkunidad, gerencia, descripcion_gerencia) FROM stdin;
    public       roberto    false    206   7      (	          0    62914    trabajadores_grales 
   TABLE DATA               4  COPY trabajadores_grales (trabajador, fecha_ingreso, fecha_antiguedad, fecha_baja, fecha_vto_contrato, causa_baja, relacion_laboral, telefono_oficina, extension_telefonica, clase_nomina, sistema_antiguedad, sistema_horario, turno, forma_pago, sit_trabajador, grupo_sanguinio, cargo, ctadeposito) FROM stdin;
    public       roberto    false    177   8      0	          0    256376    trabajadores_supervisores 
   TABLE DATA               D   COPY trabajadores_supervisores (trabajador, supervisor) FROM stdin;
    public       roberto    false    185   �      =	          0    537624    trabajadores_supervisores_1 
   TABLE DATA               N   COPY trabajadores_supervisores_1 (trabajador, ccosto, supervisor) FROM stdin;
    public       roberto    false    203   �      /	          0    153994    unidades 
   TABLE DATA               a   COPY unidades (idunidad, descripcion_unidad, dependencia, centro_costo, jefe_unidad) FROM stdin;
    public       roberto    false    184   �      )	          0    62917    usuarios 
   TABLE DATA               s   COPY usuarios (login_username, trabajador, estatus, nivel, fecha_ultima_sesion, login_userpass, email) FROM stdin;
    public       roberto    false    178   �      �           2606    551168    auditorias_pkey 
   CONSTRAINT     ^   ALTER TABLE ONLY tbl_auditorias
    ADD CONSTRAINT auditorias_pkey PRIMARY KEY (idauditoria);
 H   ALTER TABLE ONLY public.tbl_auditorias DROP CONSTRAINT auditorias_pkey;
       public         roberto    false    208    208            t           2606    63049    clave_primaria_idregalo 
   CONSTRAINT     \   ALTER TABLE ONLY regalos
    ADD CONSTRAINT clave_primaria_idregalo PRIMARY KEY (idopcion);
 I   ALTER TABLE ONLY public.regalos DROP CONSTRAINT clave_primaria_idregalo;
       public         roberto    false    181    181            }           2606    535068    condiciones_pkey 
   CONSTRAINT     \   ALTER TABLE ONLY condiciones
    ADD CONSTRAINT condiciones_pkey PRIMARY KEY (idcondicion);
 F   ALTER TABLE ONLY public.condiciones DROP CONSTRAINT condiciones_pkey;
       public         roberto    false    196    196            p           2606    62924    email_unico 
   CONSTRAINT     I   ALTER TABLE ONLY usuarios
    ADD CONSTRAINT email_unico UNIQUE (email);
 >   ALTER TABLE ONLY public.usuarios DROP CONSTRAINT email_unico;
       public         roberto    false    178    178            v           2606    107392    key_baja 
   CONSTRAINT     N   ALTER TABLE ONLY causas_baja
    ADD CONSTRAINT key_baja PRIMARY KEY (causa);
 >   ALTER TABLE ONLY public.causas_baja DROP CONSTRAINT key_baja;
       public         roberto    false    183    183            �           2606    553273    llave_princ_dotacion 
   CONSTRAINT     s   ALTER TABLE ONLY adam_vw_dotacion_briqven_02_mas
    ADD CONSTRAINT llave_princ_dotacion PRIMARY KEY (trabajador);
 ^   ALTER TABLE ONLY public.adam_vw_dotacion_briqven_02_mas DROP CONSTRAINT llave_princ_dotacion;
       public         roberto    false    209    209            {           2606    535026    llave_principal_trab_condi 
   CONSTRAINT     j   ALTER TABLE ONLY condiciones_trab
    ADD CONSTRAINT llave_principal_trab_condi PRIMARY KEY (trabajador);
 U   ALTER TABLE ONLY public.condiciones_trab DROP CONSTRAINT llave_principal_trab_condi;
       public         roberto    false    194    194            n           2606    62926    llave_principal_trab_grales 
   CONSTRAINT     n   ALTER TABLE ONLY trabajadores_grales
    ADD CONSTRAINT llave_principal_trab_grales PRIMARY KEY (trabajador);
 Y   ALTER TABLE ONLY public.trabajadores_grales DROP CONSTRAINT llave_principal_trab_grales;
       public         roberto    false    177    177            l           2606    62928    llave_principal_trabajador 
   CONSTRAINT     f   ALTER TABLE ONLY trabajadores
    ADD CONSTRAINT llave_principal_trabajador PRIMARY KEY (trabajador);
 Q   ALTER TABLE ONLY public.trabajadores DROP CONSTRAINT llave_principal_trabajador;
       public         roberto    false    176    176            �           2606    786175    periodos_nomina_pkey 
   CONSTRAINT     f   ALTER TABLE ONLY periodos_nomina
    ADD CONSTRAINT periodos_nomina_pkey PRIMARY KEY (id_calendario);
 N   ALTER TABLE ONLY public.periodos_nomina DROP CONSTRAINT periodos_nomina_pkey;
       public         roberto    false    210    210            �           2606    536866    registro_diario_pk 
   CONSTRAINT     h   ALTER TABLE ONLY registro_diario
    ADD CONSTRAINT registro_diario_pk PRIMARY KEY (trabajador, fecha);
 L   ALTER TABLE ONLY public.registro_diario DROP CONSTRAINT registro_diario_pk;
       public         postgres    false    197    197    197            �           2606    808187    sibes_beneficiarios_pkey 
   CONSTRAINT     o   ALTER TABLE ONLY sibes_beneficiarios
    ADD CONSTRAINT sibes_beneficiarios_pkey PRIMARY KEY (idbeneficiario);
 V   ALTER TABLE ONLY public.sibes_beneficiarios DROP CONSTRAINT sibes_beneficiarios_pkey;
       public         roberto    false    212    212            �           2606    808185    sibes_colegios_pkey 
   CONSTRAINT     `   ALTER TABLE ONLY sibes_colegios
    ADD CONSTRAINT sibes_colegios_pkey PRIMARY KEY (idcolegio);
 L   ALTER TABLE ONLY public.sibes_colegios DROP CONSTRAINT sibes_colegios_pkey;
       public         roberto    false    214    214            �           2606    808300    sibes_detfacturas_pkey 
   CONSTRAINT     i   ALTER TABLE ONLY sibes_detfacturas
    ADD CONSTRAINT sibes_detfacturas_pkey PRIMARY KEY (iddetfactura);
 R   ALTER TABLE ONLY public.sibes_detfacturas DROP CONSTRAINT sibes_detfacturas_pkey;
       public         roberto    false    222    222            �           2606    808243    sibes_facturas_pkey 
   CONSTRAINT     `   ALTER TABLE ONLY sibes_facturas
    ADD CONSTRAINT sibes_facturas_pkey PRIMARY KEY (idfactura);
 L   ALTER TABLE ONLY public.sibes_facturas DROP CONSTRAINT sibes_facturas_pkey;
       public         roberto    false    218    218            �           2606    1101088     sibes_facturasbeneficiarios_pkey 
   CONSTRAINT        ALTER TABLE ONLY sibes_facturas_beneficiarios
    ADD CONSTRAINT sibes_facturasbeneficiarios_pkey PRIMARY KEY (idfacturabenf);
 g   ALTER TABLE ONLY public.sibes_facturas_beneficiarios DROP CONSTRAINT sibes_facturasbeneficiarios_pkey;
       public         roberto    false    229    229            �           2606    808213    sibes_inscripciones_pkey 
   CONSTRAINT     n   ALTER TABLE ONLY sibes_inscripciones
    ADD CONSTRAINT sibes_inscripciones_pkey PRIMARY KEY (idinscripcion);
 V   ALTER TABLE ONLY public.sibes_inscripciones DROP CONSTRAINT sibes_inscripciones_pkey;
       public         roberto    false    216    216            �           2606    808307    sibes_mensualidades_pkey 
   CONSTRAINT     n   ALTER TABLE ONLY sibes_mensualidades
    ADD CONSTRAINT sibes_mensualidades_pkey PRIMARY KEY (idmensualidad);
 V   ALTER TABLE ONLY public.sibes_mensualidades DROP CONSTRAINT sibes_mensualidades_pkey;
       public         roberto    false    220    220            �           2606    1057167    sistema_horario_pkey 
   CONSTRAINT     h   ALTER TABLE ONLY sistema_horario
    ADD CONSTRAINT sistema_horario_pkey PRIMARY KEY (sistema_horario);
 N   ALTER TABLE ONLY public.sistema_horario DROP CONSTRAINT sistema_horario_pkey;
       public         roberto    false    224    224            �           2606    1101014    tbl_menus_pkey 
   CONSTRAINT     U   ALTER TABLE ONLY sibes_menus
    ADD CONSTRAINT tbl_menus_pkey PRIMARY KEY (idmenu);
 D   ALTER TABLE ONLY public.sibes_menus DROP CONSTRAINT tbl_menus_pkey;
       public         roberto    false    226    226            x           2606    153999 
   unidad_key 
   CONSTRAINT     P   ALTER TABLE ONLY unidades
    ADD CONSTRAINT unidad_key PRIMARY KEY (idunidad);
 =   ALTER TABLE ONLY public.unidades DROP CONSTRAINT unidad_key;
       public         roberto    false    184    184            r           2606    62930    user_key 
   CONSTRAINT     T   ALTER TABLE ONLY usuarios
    ADD CONSTRAINT user_key PRIMARY KEY (login_username);
 ;   ALTER TABLE ONLY public.usuarios DROP CONSTRAINT user_key;
       public         roberto    false    178    178            �           1259    536906    evaluacion_periodo_idx    INDEX     \   CREATE UNIQUE INDEX evaluacion_periodo_idx ON evaluacion USING btree (periodo, trabajador);
 *   DROP INDEX public.evaluacion_periodo_idx;
       public         roberto    false    202    202            �           1259    536880    newtable_1_id_idx    INDEX     B   CREATE UNIQUE INDEX newtable_1_id_idx ON baremo USING btree (id);
 %   DROP INDEX public.newtable_1_id_idx;
       public         roberto    false    199            ~           1259    536872    registro_diario_fecha_idx    INDEX     O   CREATE INDEX registro_diario_fecha_idx ON registro_diario USING btree (fecha);
 -   DROP INDEX public.registro_diario_fecha_idx;
       public         postgres    false    197            �           1259    536873    registro_diario_trabajador_idx    INDEX     Y   CREATE INDEX registro_diario_trabajador_idx ON registro_diario USING btree (trabajador);
 2   DROP INDEX public.registro_diario_trabajador_idx;
       public         postgres    false    197            y           1259    354953    unidades_jefe_unidad_idx    INDEX     M   CREATE INDEX unidades_jefe_unidad_idx ON unidades USING btree (jefe_unidad);
 ,   DROP INDEX public.unidades_jefe_unidad_idx;
       public         roberto    false    184            �           2620    268709    insertar_temp_trab_trigger    TRIGGER     �   CREATE TRIGGER insertar_temp_trab_trigger AFTER INSERT ON temp_trabajadores FOR EACH ROW EXECUTE PROCEDURE actualizar_trabajadores();
 E   DROP TRIGGER insertar_temp_trab_trigger ON public.temp_trabajadores;
       public       roberto    false    188    285            �           2620    808910    sibes_detfacturas_trigger    TRIGGER     �   CREATE TRIGGER sibes_detfacturas_trigger AFTER INSERT ON sibes_detfacturas FOR EACH ROW EXECUTE PROCEDURE actualizar_pagos_mensuales();
 D   DROP TRIGGER sibes_detfacturas_trigger ON public.sibes_detfacturas;
       public       roberto    false    222    289            �           2620    808609    sibes_inscripciones_trigger    TRIGGER     �   CREATE TRIGGER sibes_inscripciones_trigger AFTER INSERT ON sibes_inscripciones FOR EACH ROW EXECUTE PROCEDURE crear_pagos_mensuales();
 H   DROP TRIGGER sibes_inscripciones_trigger ON public.sibes_inscripciones;
       public       roberto    false    216    284            �           2620    535192    trabajadores_grales_upd_trigger    TRIGGER     �   CREATE TRIGGER trabajadores_grales_upd_trigger AFTER UPDATE ON trabajadores_grales FOR EACH ROW EXECUTE PROCEDURE cambiar_condicion();
 L   DROP TRIGGER trabajadores_grales_upd_trigger ON public.trabajadores_grales;
       public       roberto    false    287    177            �           2620    535160    trabajadores_inst_trigger    TRIGGER     �   CREATE TRIGGER trabajadores_inst_trigger AFTER INSERT ON trabajadores_grales FOR EACH ROW EXECUTE PROCEDURE insertar_condicion();
 F   DROP TRIGGER trabajadores_inst_trigger ON public.trabajadores_grales;
       public       roberto    false    177    286            �           2606    535097 !   condiciones_trab_fkcondicion_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY condiciones_trab
    ADD CONSTRAINT condiciones_trab_fkcondicion_fkey FOREIGN KEY (fkcondicion) REFERENCES condiciones(idcondicion);
 \   ALTER TABLE ONLY public.condiciones_trab DROP CONSTRAINT condiciones_trab_fkcondicion_fkey;
       public       roberto    false    196    2173    194            �           2606    62931    llave_foranea_trab_carga_fam    FK CONSTRAINT     �   ALTER TABLE ONLY carga_familiar_hcm
    ADD CONSTRAINT llave_foranea_trab_carga_fam FOREIGN KEY (trabajador) REFERENCES trabajadores(trabajador);
 Y   ALTER TABLE ONLY public.carga_familiar_hcm DROP CONSTRAINT llave_foranea_trab_carga_fam;
       public       roberto    false    175    176    2156            �           2606    535027    llave_foranea_trab_condi    FK CONSTRAINT     �   ALTER TABLE ONLY condiciones_trab
    ADD CONSTRAINT llave_foranea_trab_condi FOREIGN KEY (trabajador) REFERENCES trabajadores(trabajador) ON UPDATE CASCADE ON DELETE CASCADE;
 S   ALTER TABLE ONLY public.condiciones_trab DROP CONSTRAINT llave_foranea_trab_condi;
       public       roberto    false    194    176    2156            �           2606    106565    llave_foranea_trab_grales    FK CONSTRAINT     �   ALTER TABLE ONLY trabajadores_grales
    ADD CONSTRAINT llave_foranea_trab_grales FOREIGN KEY (trabajador) REFERENCES trabajadores(trabajador) ON UPDATE CASCADE ON DELETE CASCADE;
 W   ALTER TABLE ONLY public.trabajadores_grales DROP CONSTRAINT llave_foranea_trab_grales;
       public       roberto    false    2156    177    176            �           2606    62941    llave_foranea_usuarios    FK CONSTRAINT     �   ALTER TABLE ONLY usuarios
    ADD CONSTRAINT llave_foranea_usuarios FOREIGN KEY (trabajador) REFERENCES trabajadores(trabajador);
 I   ALTER TABLE ONLY public.usuarios DROP CONSTRAINT llave_foranea_usuarios;
       public       roberto    false    178    176    2156            �           2606    536867    registro_diario_fk    FK CONSTRAINT     �   ALTER TABLE ONLY registro_diario
    ADD CONSTRAINT registro_diario_fk FOREIGN KEY (trabajador) REFERENCES trabajadores(trabajador);
 L   ALTER TABLE ONLY public.registro_diario DROP CONSTRAINT registro_diario_fk;
       public       postgres    false    176    2156    197            �           2606    808151 #   sibes_beneficiarios_trabajador_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY sibes_beneficiarios
    ADD CONSTRAINT sibes_beneficiarios_trabajador_fkey FOREIGN KEY (trabajador) REFERENCES trabajadores(trabajador);
 a   ALTER TABLE ONLY public.sibes_beneficiarios DROP CONSTRAINT sibes_beneficiarios_trabajador_fkey;
       public       roberto    false    212    2156    176            �           2606    808301     sibes_detfacturas_fkfactura_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY sibes_detfacturas
    ADD CONSTRAINT sibes_detfacturas_fkfactura_fkey FOREIGN KEY (fkfactura) REFERENCES sibes_facturas(idfactura);
 \   ALTER TABLE ONLY public.sibes_detfacturas DROP CONSTRAINT sibes_detfacturas_fkfactura_fkey;
       public       roberto    false    218    222    2193            �           2606    808315 $   sibes_detfacturas_fkmensualidad_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY sibes_detfacturas
    ADD CONSTRAINT sibes_detfacturas_fkmensualidad_fkey FOREIGN KEY (fkmensualidad) REFERENCES sibes_mensualidades(idmensualidad);
 `   ALTER TABLE ONLY public.sibes_detfacturas DROP CONSTRAINT sibes_detfacturas_fkmensualidad_fkey;
       public       roberto    false    220    222    2195            �           2606    808244    sibes_facturas_fkcolegio_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY sibes_facturas
    ADD CONSTRAINT sibes_facturas_fkcolegio_fkey FOREIGN KEY (fkcolegio) REFERENCES sibes_colegios(idcolegio);
 V   ALTER TABLE ONLY public.sibes_facturas DROP CONSTRAINT sibes_facturas_fkcolegio_fkey;
       public       roberto    false    214    218    2189            �           2606    1101089    sibes_facturas_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY sibes_facturas_beneficiarios
    ADD CONSTRAINT sibes_facturas_fkey FOREIGN KEY (fkfactura) REFERENCES sibes_facturas(idfactura);
 Z   ALTER TABLE ONLY public.sibes_facturas_beneficiarios DROP CONSTRAINT sibes_facturas_fkey;
       public       roberto    false    218    229    2193            �           2606    1101094    sibes_facturasbenef_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY sibes_facturas_beneficiarios
    ADD CONSTRAINT sibes_facturasbenef_fkey FOREIGN KEY (fkbeneficiario) REFERENCES sibes_beneficiarios(idbeneficiario);
 _   ALTER TABLE ONLY public.sibes_facturas_beneficiarios DROP CONSTRAINT sibes_facturasbenef_fkey;
       public       roberto    false    229    212    2187            �           2606    808214 '   sibes_inscripciones_fkbeneficiario_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY sibes_inscripciones
    ADD CONSTRAINT sibes_inscripciones_fkbeneficiario_fkey FOREIGN KEY (fkbeneficiario) REFERENCES sibes_beneficiarios(idbeneficiario);
 e   ALTER TABLE ONLY public.sibes_inscripciones DROP CONSTRAINT sibes_inscripciones_fkbeneficiario_fkey;
       public       roberto    false    2187    212    216            �           2606    808219 "   sibes_inscripciones_fkcolegio_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY sibes_inscripciones
    ADD CONSTRAINT sibes_inscripciones_fkcolegio_fkey FOREIGN KEY (fkcolegio) REFERENCES sibes_colegios(idcolegio);
 `   ALTER TABLE ONLY public.sibes_inscripciones DROP CONSTRAINT sibes_inscripciones_fkcolegio_fkey;
       public       roberto    false    2189    214    216            �           2606    808308 &   sibes_mensualidades_fkinscripcion_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY sibes_mensualidades
    ADD CONSTRAINT sibes_mensualidades_fkinscripcion_fkey FOREIGN KEY (fkinscripcion) REFERENCES sibes_inscripciones(idinscripcion);
 d   ALTER TABLE ONLY public.sibes_mensualidades DROP CONSTRAINT sibes_mensualidades_fkinscripcion_fkey;
       public       roberto    false    2191    216    220            �           2606    537627 *   trabajadores_supervisores1_trabajador_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY trabajadores_supervisores_1
    ADD CONSTRAINT trabajadores_supervisores1_trabajador_fkey FOREIGN KEY (trabajador) REFERENCES trabajadores(trabajador) MATCH FULL ON UPDATE CASCADE ON DELETE CASCADE;
 p   ALTER TABLE ONLY public.trabajadores_supervisores_1 DROP CONSTRAINT trabajadores_supervisores1_trabajador_fkey;
       public       roberto    false    2156    176    203            �           2606    256379 )   trabajadores_supervisores_trabajador_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY trabajadores_supervisores
    ADD CONSTRAINT trabajadores_supervisores_trabajador_fkey FOREIGN KEY (trabajador) REFERENCES trabajadores(trabajador) MATCH FULL ON UPDATE CASCADE ON DELETE CASCADE;
 m   ALTER TABLE ONLY public.trabajadores_supervisores DROP CONSTRAINT trabajadores_supervisores_trabajador_fkey;
       public       roberto    false    2156    176    185            A	      x��[s�X�5���΃�q��Z��e�t�F�rqqp�3��
� �0���s-Ih	�d����;vW�@J���u�1�e��֙r�z��S��k=���)�j����o��Ǧ�4����*���VM�4M7�iR�W�R.����m�2��z'���ʙ���@W�~W=�u������F���(!p�f������=D��h��/�p��'�I4���$ZLn��h*�(��V4��p������)����kkGh>>5�:C��F���Nݮ�S��v�m��Wl�2J�>�i<��'�"R�ow���є�^/h�]~���v�������zjv�nk��5���l�wզ?�柺MW�����*����S8ĩ���Y��>*��m?N���O�p2m����PͶ�CM����j_�8��98w��{��0�i�7��m8چ��a�k:�����b�~зM¬4	��1��+^��#\d�&�B}Q/�x-�E}���og��7L�;��p����~�k{��L�2����#]��8�܋�vC���s���W�}x͞��c%�ý�p���>�G�~ا���8�B�1݄3���n��U�b��}5p;p�J�bh*�.~�kZ��7�[�:�m~��Z�[U�e�(&15�\�x�Ln�[�2��>��x�"YZ
�����d���3~e����R���A�����S��S�_��(lu������.G��\Dw��2	�P�<;��y}�ɯ�bÓ^k��e�Kw��C�n[!����#������nHG�sD<As�D���3���fM���e*-/ ��n����40�V���m�?P�����0l��x�o-�T����A W.��1�A���� v��.��`��t�����"i,�i�&Ӣ:������5��?=w������z��!���7�(�,-�C8��h��2�#�Y���g�����n�����j�̔�dtF�Q/n^����u��2�Ads�W.d<^����Y˚�0���Jt�C��Vb��N���R��P�(����$n�;������� ��tԦ{�7U�}=�­�;
���m��8��+���$J�p<��F�>4��8R��[8}mV4fͥ�]���y/ /��K=� v�no�^(�-˒��dn�4uǬ��J�a0�����R|��7X99��e����m� �hAw����}�*���I�I�K��H�kn��Ay�����a.lɛh�m��Vy���������lT31C�F5p��2W��aX�AL���@_�q(��d�G��qt=OB&%�̲�m�"���Ԡwҽ���*�O�չ��EO�j���BoPaTqg7X�Fۯ|!�l�E����ob�-��k���<Y�fQ2n����W��!����>� ��K�u<�y�7�-j�-U�އ�Fי��h���j���iCdH���v�\�d��7��ih��P��5�� ��,�m�x�`-�X�1x�%��죲>"�ap?:�l�x<Y<MF�Q\MF��#9������i~_̟�@
ǇX4M���E�C�^��==�;��m�
�^k��{-�ژ<�s�+W1Mb�[/}l��P��}x'C�{����d��M�KH��H.cͱ-e��g�s�~OXʦ,�!YDI�S�q�:;��jav������� o� <��S�n%�Gۮz���=�[����B�	���$�}��L�!^m�Kg;��4K�@|������h �?!-Օ_�#1�;]Ci��Sn=���+�?t�?/р||�>9|������]�j	��<Y��$��i��a�����h:Y��HB�H�Ķ�p�3�6���g�P�é��v�6ԳT�7��C�׳���0*��a޼ς?7ܝ�½��K��Z3z��p��w��l<Uͷ9Ĥ��<���Υ�}�B�9�0��X̮Ǚ�C+�q�t�C[�������:k
���׽n��`S
g��ɟK(cn��$�#�y�A�
�Bi����~��;�:�����ƺ���u������<�v�C将����V�DL�|��H�'2�� ���x��.��I,����Hׄ������G�F�o����8��5C,��׿�m*Y�0�#�6z��9bw��g�����;*�@�9k��߆���'/ u��R[��q�;o���^�Z�ը�l�c�(j�[��������e2U���>O�DJj��R7�]8��^�}�$l�g8@�����ptmc����EN�0,!Mg�(��\���t*�mp5���d宸����7��)�17��D�`���4f).|#l3�U�W8*��>6H�I�}��{�u�����#y�̣�������n3(�!�h���z��n���a��q����gS�:��\�$�'���(��>��B\����er1��1X��o�H�F12|���M?&���(yóh^�|���n�(���?6��8��>^��T�i�Xl�������I���s8&!4\��A$;S�� ]Q�x�}s��S��7�n���o�0s�z]W�Szm��M�H�m�}1�He�]}ӲH-�5�����]��A���M�C
W���K��6���p$���v��TA�K����q��w?M�Kג��ՙǍͳ�i��ļ�Ԁ��+�h1�َ�(԰A�`��j�	���>\�f�F�`8�nS��=W��2͆������u�6��{τ�y����&��դRڂ\��z�>NEM�uyR�ib�`Q�kjt��[m�����0��
�t���	���%�.��r���"z��hp
�I��{IQ�
"��C9QE�(C�e���Ṇ��[���dۆ�_2	g����(�<L�$\NÛ�lc.�Mw�W����Zp\pe)��6s�ɷX�MD��9��2�0��%�	�[3�V8d�6RX\���R�kZ?��#`��*��f֏�L�b�!�7�p+D��2��s4�W���^���ѧ���7���.�>�ʱk�l��k̥*s�/��^P�ʎ�[�l�(7�b2�ģ0�9�PS6*�+�����կn��2���X]٢�SB�QpW��⬍��a��?1:;�H�h����>�����Q�O��#�(�b��1�/ � t��%�վ���+���{��m�H^1�毥m�|��}�Y��^L��|=��G�^�X�1��,H�ӥ{��Б����>�1�f��m��Ա�-^��.hoO��|��?����6|�˅fK�a2�9'FpҞz��|�^wCk��n�����B�PYc$�U}�ZhF��5H�R��\5�OR��ˉ��AF!5���W���
n�t�j�0�mM-@�3r�'�������]�!���SZ�M�T΁�r��x0 �s<8m�
�`�p���Q,�RBM[3�����=Z�ۀG@��o-x��+�����Z��|�����!X�}�1�ZYN�3�E�'֥<D���	��T���v�i/�uz�$�R2m��V�F�l�G�A~jQGGH.!�۬�ѮHH�b��H8�#H/�>��	!��Y8O��g�z�[�p��0."	O��������]�Ǽ���v��ms��/k[r�������������d��	�z��~�)|�p�gǡ7���	�r���꽍� ���[�R��L�)��mz�]��������譭{�m7h�m�&�	���g8E!��s�&7�yܠ�&a�uJ���?|0���Lx�6|�1���ؽ�a}+�N�aC���`6rȐ�XY���дj݊@Dwq2�+��c��S�t�xY�D5�fÑ��/n����t�Q��b�p�o� 
^C�-�J�{�ir���6|��5"/�D��~�n	kl����k��〴��G(L��:���ޕ����F]���Jk�F��D!6(�;H��h�M�Y���A^�K�Oݯ�s��޿/!o�]��v*}�<�0�{�D�Q�*�]1�_1���ŏw    �4w���GX}��ÏI OsL���v���Zj�{��s*�}���6�P�԰-���.NT$N�^����5����|�/�l�~'�4fA���Ĳ����Z���:�D�l4��O�'���X+AB8�Uo~��y�6m�(������L��i<k�}'�("e�X�C���^��3�)�~�S/'�N�8�ac	����zݻr�]��4+�>~r/����j��8؀�
!������
����!0��`�+[3XˉO�ֈ���HN3!D�xh��Y��h�����Cǟ=��c_�9FF�sn���'�x��  ���w��C���0p�W��4��z��܏�p`����)�3lxFr��&�bir����j:UәU��������i�ꤠ>x_D C����u�yѫ�F�E�Y��(����#ʵ��L/M:�z^t�o�v5{}W����͈f�zU��T.A��� 5}ի�£�t;��<>kE���kYpm �OY�N\d��������{A�_��2
�i��6^"i��"�C9��]���y�%|��d�(�5
&L�O�{<T���]�Ml��{�Z�.��aH��#�#3��rGw+rް�<���pH��nb���g���(V~ڭ����r4�C�#L&�.��i���\bx���쪽��;�z�y����	�o�X)dr�� S�,���� ���\��dp-�] ����W�r�
��*����.MLM+�y�X�����`,C(�)7Zjuy�������� ���h�L�x�oA��ˀPK-�׿�!e��ٓ���W��/�yI��8�������[���u�fޕ35�v,�9ܾ�S�@�u�L�9SwX<�n3����:Cd���^�Wl���8�e�_2�C���DD�y�0�լ"?Z����6ƪ#.KO�!��ts�8M[�?�ɥ5>���ex�t�~��bA�j�d�����N�`��ԩ�ԩ�T�, ��n�"9��h:�a#��Ҟ!��t"L�S�T�����'B,��:%�In��]�h��1�V�jឹ��D�l�)NF8GE毱%��j�Ů�]06�A�Ӆ"��=�{m7+C��P� �15�4��}bS�-�J(Ց#�p+�&�,���''�p�K[U�T���s���4����
o�xb�m{�E5&uO|��U ��`�ݛ�X��<��sp�#�W�(����f4�|���4��=�̣�J��P���5�sp�;j�5�>��d9#��Hd8d3xn5aE�6��+y5�-R��̨ν <bTܝ:(?pp��u1&�P��$��_7��O��h-����8�?�� �g#O���N��e%�-x'�vk�B��M�m(~Jh�M9����Lvg���)wI8�,��P��0�b?���ц�����&�����̙��7�F���2���r[�s�o����8^J�xak�J��8C���o5EL{�X�%�A�N�6mP��<I��&�@���8PWνv�:h����޾bw����+Gw���L�Q$����0M��V�L{��j��T(R&�7�Z���zh�8��L��d����#r��/<�au�m�����$7J<z
�'1D��e4e�w�љ����͹S��&����6y+G�%�,�H���{IǷL�W�F�V�46n�ӇY��(!�Y2Q���d:�g�|)�u��"�1��H�����e�Lv@���mVC��+r~�ȹi�bŝ>�I�/�Y��E9Jq1������F����HEC8��)F�� ˷,k3���.�'ٳ���+O��$��f��`��?=�ޣ���j^o�7c�����k�L�.P�0�lߵ���>� �`��b۰����8��L���1�?4���A�a�CO.w!#�dA=J� �/��q����2�	^I%������ׇӴq�]�_��x���D���t��w̚�[������ ����2&7�K�`O�N��F���@y��n���'�^�W�E3l�>K�Q���{Z&py��(�J`�
�����h	�����f>�3s.q�1E��P��\ו-���UΊ͆��.��y,�Aӎ�*��6M��Z�p�Q��gxzC�o�N����(�?xhr���M�7�/�6j �"@m�6���6Bl+5��b۴�iMG�u=��eؖ��,����G�����J���X�����$�����%����rv�|����voA�O^�vjm��l�����:.z��'���?pf�m ���	h����S�?�OieI���\q{�v�v���m�U7[O�����">��k;�,?|ks$�#he��C��҆|�ϢJ[�\�t`½�Nr�;�n��F��B&��u]s�:�킁��J���a����NQ͜�3�lW�c$�"]y�,�H��0ޚ�y�b[K��Zn�G��E��ٓd �0��|�����h�� �K�t����d0ܮ���ϗM����S�MX_���tg��=X�Vs�I8Ji��c���3�V�}-F�$������xJ�LH��ʄzͬC���.�����i��P	����%	g��G$ֶ%���iO(f�[���(ll!�ƙ�l��k���?�wB5*�^V����LX!����=�{��oWn�����tc��1퀒괇w2���O6=����wU��	�s�c� ެ9�5ރ7�F�<�?b	ň�]:�L��m�@��6�8t����^'u`\L�]�n�Ij�Z�&õ�X-���������	�a�C[Z>.�E����[��{��3m���{Ox�H������MM	�������z���v��1(��5���w�
������.��� ���g�����g���/ L�0������3FA�x�e4}���ܣB�u�r���PG�ZY���u��q�&�k�rH�{k��Հ4��)�}l�]�N���mw��wlzd�P�ˬ9PR#�l/����,/���m��wʛ��}��t��tӓ��(���ɜ�n�P��gJ4��4�f�)��{?5sli����M.� �#n-^���]4�7�����u���������`�|[_�
���k��;�n�>�������Q`T��Ic�����ᷮ���n�-8啉�E�,=/<�y��y�ٞ����bM���kq�Hu��8ȅ��;�JZQ���t�hrVV���A5���-�n��4���>f��r����[�o�W�O�ഡ����i�2��ڼ�m��a٨��;�+�p��5�$�2�ǿ��QOW��X�W6ES̴������9��F�D~`K�f2�|q�f)�!�]2�B%vyÂ�+'�z0�M�e�X=s��IO�a�����RC�e�,'s_���i/���9��(3\Ǜ�$��I��� v!��	�Wn�^������2&�]b��b� �t����!�NW��s{�m?��&��˧{qs�0G2�0��]\?��� �`�ݘ���+.���qt+7����/<�g#�O�P4k�*oEَ~�)�V��lb���F�gPXju�¯������_�m1yT�[H�'w(d�0����Cmf�-.v.����&ε������`
٨{��������^Zo�A� �9c'
�'��F/��m{��pH"�T���_`��/�ŧ7E��J���k$�Ve�iឤ�Ĵ��S�R�t�� d/��8B�;E�1�ɍ�-�q�:P���x�O�`q�&_���	
��}s;	_���#����7p����׾�������%KH��9�@�2����YiIz�8��#c�����Si���G4}~�/�ӛ0	���P���4�L�x,Χ�p*���FҚ���Y��rSf�5��+'��9�}t���W�,�9����.J���6z��C75ɹ�詮@lj�r�$HVN�Y�U�6U�m���/�lr��M����m(�T�
I��K���-A��Vˍ��l��(����kf
WCY�
㨮_���U����m�M���$�Np`�z����ZN�&\��N��t֚��!��`��-    ���
\����{�?��if������>�!�tۭ^N
�(O}�+���J���Y?jL%�ch�n2�]x���j#��lUN*d��
�D�f���;�d2RD�-U�TM��T/�`8h���nj)���C��Zk��2�YUد��Gd-oV;ѣ��sa�a��c�%�@D5F$?�z����e�ϢU8B5�(���X���Y�,��h��e�,_go�mI�u�Aa��#_��ٙl	��הp|V� tg3e�MSGȧ��d�<�(����[Hew��S7�a��e]ay��=u�+�æ�vO�\��6�6�x7����N�8��[����"����'2����V���?]�3�`�]���n�Q�tQ<w%E���,�l�-o�2��|���B,\?>�>�����<������!�C2��Q�]����2���rВ�l�O����g1BEб����@���Y��g�����MC�n����d�E�션���uИOԠ�����V�c���V_��>�Ψ�����ɽ��
T������KE5ijN�S���Ή��F�[8��#9� ֪͕3Nf��ZA�AY�o�XI������F��:�V���$(=(u�+��v��5�;|���9�Y#�|e�?���@;��9_�1��d�f3������׆#���A/�yM�f���i�#�G�b>xg� ����m7��/��E}��6�e�H��	��������w�����,�Y7����^y����p�0�K���-;:�S�*%�pUDy���[�!�k���͙����A�>��9�Cyخ�#�d��%�˂�IM���=q[=�\���xm\�K{̛�R2�|9�1V�8�J��{���`أhwիuN�rΓ�a�(����P�7�}ܧ%^\�B����ᗓ̵L+;W��ߛx��%V^��h:�,��&����p�Qw�<H�S�ڜTs<����+d1��ு��<�ce�@�q
x�S�����s��eoqf�.��u��VX*�W��\�i����ȿf�~d���`��`{3��B�{���;#�|Mg�%�PS'��!�s
�������u�B"�M׸��D�cQ����t\}�E+�p>]BȊo�wx�	�MǱ��ŵ�'�P���`6 �E�2E'�#��՜�"�~�����!� P�Cx�܆�I4}�)�����i8��#�A�o��?�6r�^���+����QX����!�C�a�r�g�A�R\EO0���q���7M;�	�N�p��3�L�-m��,���a��~����ꐡ��b����8y��!���,)��\ נw'�DmBe��C 
���X5�7?����=Rf1�C�>��&��.7�`,+kق�CRAG�ù�x�EM����8p��6�v����`A��A֋b������g�#������YJ0B������hqҠ�#��X�Ϩ��2��Z��`��kp�����̢�	�p�*�F���ʚi#)I���������$6k{�(U��(6prx���~Vк��vt�S�H��i2����o�r���� ��Ư!D�"��1�����S ���+8�F�gE�`�k����׵�7P�lZ�T?��t�<��a��$�4Q���� 3eN�!qL4�N��)�+��J��rpV=�MM���h��r�����Z�V�BՋ����|�AR�^\�XGI��\�Y�OXzc�j��=4��nx��	q"7K��q�P����	���]zi�U�'�&�YDt�P�:�FNW�����F�?�E$Z�)�`��#Z<N�����nJ��oHL���Z�S����^�4��ɴ�d|ӭ5P��V�At'3$��s9y�� ~
��rW}��o��������G[(�pZ�ty{�Ĝ�2ޖ@�`h��V��+���-� ۮ��0�:H�ǌ��>[���z	��-��@ѧ[�72(���#66��8�xҚ�-��:��/�ğ݃~�d��w&��%�#���)���J3�+�ܼf�Vzr�1J��������ʳ�5r�}� �n��P��bB��}(|ڸ�"����r��>ed%h��q��C�ֲ�@歳U1�c����Yo��PN�Z\��<�^(-��r�r&W��xt��KM�\�$���H�i�+�����!����z�:�C�G��������"����ŷ��]L�=	]s����p��ȗ�%�׭�?��u/.g�:L�_\Y�ɕހS���x-%끳�`��W��xZ�Y&��\�V��Rla!�*�>�d�����eP����5Snv�
���|�"�b,�a�aY�N-�O�l�R��>rB(�:X�]�8餵����΅�!�D���t��N�Uu'��#�l���EF����ƈ�r+u<|�c�jL���s6�ų�3l�_�������
�t�;#e�����	�Om����p�j�/�AXo��!�1M� )8h$Wa��<U������!���1��f6N(s_�����ה�h�k|�(��Ȩ�>���ʜ��Hd�8���N���#P]�t��ϕ�9����ܡ�p�Z���h�E�]��K*"E��Q����+LȋZ�>��4ӻ�&�G��:j�2�5J���Z�e�[�z�!��ٲ�:v��>1ܾ�����ANa*n�]�mȡ��a��ׂ���)%\����/�:�=����|[b�p8�t9��<���D��L���H��^���̇�:�A���r@B2B����p��g���0��b~�E���7�Dy������
@���.����<z������c{�L��<ԥ�TBL�ap�������N�F�c�J�b�����P�DK%YN^9�ic�u�2�eB%���i{�9�;`�9�3���2fԀ���"��ܰz�pծ{��I��!�G�F8��c	~I'�6p�+�6�|�L�y�ȄQ�*yu�����b���2z}��^�Ų,*K(�!��sA�7N\D��t�5�A�ሙ����ziԗY|�x�����	J9<E��q���X�jpapۖ�PN�C�W�L$}�'4��Wr���|>𮛛Q1zX����2n���v�P��U��oh�B���^6�i�x_�w����*�a��Հ�t(�I���=�D��`�'ߨh��e��mQ��c���2��ﮰ=�u�ϳ!���N���^��o4ޘ����NS��Cu�{)7j*��o�w��瘆�$ԉ<ѵ��n�ak��{��ڮ������
;����� Ro:c9T���Zk�z���h8���z��aI%�2����ElwHϘ�K��Q3��^�:w����w.�_xi�i��⥲��3�hn��V�Ą��GH���N١p$�zeb�Z(7[�(7I�P ��n%�r{�Ө$[ י����/P�Es_uy��+�j�b��� �l���Z�I����`��AM!H�.R��+K��:�bb-�l�i��|w��P�?����x����k�OvH��C��Y��l�3Y���M�e`A��h|#���9/r�qV�%F���S�� $����&K�ϲĬ��Lf�r�>�i����/��NI��=�:�����íڜ���GzùI3i�8��]$�n�Y6\9��!k����\b��U8G���KF�x�j�	\6
x`1��(9����hA%zs�{|u��1/�Ӭj�E'���>�y�l͒��'�{�y((�A���8�U&YFJt���;R�hE��E!#��y~�V�4��
�jyjU�5�� Z'�Un@h#�'�:���]j5p��P!�]ｌ�k����z:[���eo�WX<F�����`��{�+'���L�wIts�ui�hPG�4���n�+�|�����6��M0c�E�^� vX\��Z���{�L��ޤ���vWZ#�_4�8���l�C}ADo�+�n/��)�{1�q��lX�Zԋ}�!�p�E#��'r���O[NP񲩙���=�8<Lh8�@���!��_f ��b�M    �{f����.��6���Zm��t�z5#�."b~��@ q����1�����PsZ�P+'B)p�/��ɕe��@rٕ�4�`�Q:���YЃ\
֐v�J�0	����
��C�af�H:TB��1M
g��P��N���O�$$藱3y>&��K��y��dV����N�ް���ibiUʿZԆ`]n��ľT������xC��.��	mo��l�ip�3�ͼq�	�z�A�c�7�#�,F���(����J�ԤPʺ����Z6��0!j#�bL��
�	�M�jP�}ea�.^��$��臉�x����M8�KSm�t0�i.ڻp;�z�H�:7��(5�#�al�.-�U���c���x��32�?��l���`�͔*\I�D]�*9p�à�s��s��<��c8��R[O2!�aQS'd5�>I���/�Jzk�D�U4�nN��+}�m*fKd�}r���m�'�?Ε� �tln(w�Ȧ��ԓ��lUo=��[�����Z�(�z k^���)�YV-�<Xu�ݴ>nk[G�U�ɽv�>B�q�����+O�(֨�caR�-���s8ǉ����m�X��̌�g�5��ߵ�#�4%�D̱T�`�vA�#���I�Mx�`O{�7�����f�r�х乜n�j0�}����dT&�Y�D�k���F��c��'ϓ[��,`�����E<<�`���2�g,�s<'B��JB�A����Mi�����I�,y���\q��ٶ5��*����	Lb�OJ��/&w��*e
9d�Jk�U[j��)J�!�օ�|l!WB�"�Y����ƛ���S
UJÌ��2^����c[U�������k#6R��([�ɝ��.�Pg��m����eM��Ϗ6�4SS�n�Ki��BR>��^5ށ'�9� ����k���{S~6��#dͲHԂ�F��^����S4E���,�fB�n+�*n{]�{ ��x���۾���n��E:�'k`^Ӥz-fR\���nP���+�BK|�٤��]��p[�л�x7��>�t�]��+?�����)��۽�i��b��(��!�Z<݇Ӣ\�0V0�<{���)�ם�ఆ��%;g�~C�hT����U�]�a�ϥ�*h�~�.q�J�������/��S�/��a�JgN��6K�)1�K�4��_"�p��Z����A4͒�t�ɝ�	��w|9��,����5�f�^w5`(���!'� �cÊXy����S�	��u@ҭ^�a�j^c��&�	��4�@5�$~�w/o$�OSw�ys{؅\����($  X�������M���Jm$��'����~��9נ�yÈ���[��V�ljZ9���e�`!:��v���U��O�Y��n�cP�x���I���qa\��C�-��(@"
��B�âƀ^���4&�.�B�(y�G��<L�2'3,����������J�#׵Ь��z*������c�zF#�?z����t0��B��ab���e����6Vn㙐�"6����j8���.��zm����]"�B-��j�}W�+MDWD73�qFj4[�2������d�;Ѭ}"�1��h:yo���q<�����ݴ����{�|��Կ�곧��v��Gd8%&����b�/�3ˠ�I!'�90����C;��\{�;_�1�g����oPEA�7M��cVܶZe��_�q[[5rR�rRU�L��EHP���2�<bI1�1z}\���l��*���Dk�4s&"$�M���i�/3��@yG�e�BB��v�8S������f��ϞU�e�*��ņP-�c�[�p�y9�	���a���@������N��꿃A�V���E�I�Fd�T�kwᾔ���y���V���a2#H(����A(��e"[�h��U�M�j&�=��Q�^�2C�sr]M(WW�pS�W�z~�
���,e�����8
0��q��iY��
������!�Z��F*v���w(c4p�dR]_�BL��K2^*�?��)�L�?�a�8�̨n:���^[m{^f
DC��Il�*R�[�w�l\O�3p��s��{%"�n8��w��w�K5�t����9�q����}����/�2�݉�É2Y��hژFQ��J��W�����V�P��z�V�k^��#Ẫ�������qS���mv���H��E�I8H�C���H�/|Ƴ|\�ӫ0��4D�����{Ox�H���hNx�����`�8�"��#�rzdi`c�5ȧ��|����,�S�����}�u�˄7`�|9<i����p�,���_w����@s�{>b��a-����W��2��#�GB�,#͆�p>��;�!���M��TA��+�kg�bBZf����kĺEou�a����$yП9ר�\D�����Z�V��;�~��2�,�ґ;&������� ��4��l���nj_́�#[q��jR�
[��D��ؕV��C�P���u��qC�4��KY����{�F_6�	(F��TǓ�IY��d�0lyTd
�R��y���0z����1c�<��}ACͲ,M$q`\�pdo-5�01�C�|�����SC��?���C,2���o;*�R?���v��7�-ݷ�%�r�m~F.vdb�^�H���*�	A�{o'�E8Τ>�p��L��6�nS���U���{�)\0����l�C����#�#�Gƺ.��X����XƤ��?]���64�qR�Q�Ϟ.���	_ђT�fF*�QO�y?�������1r�V�y|AL��u�'+P�i��ﭹ����4~�����}F���C'ι��.��T���!���ĩ��d�X�66/��m���It���v���M������D��0p8���(�q?CV��g��ŜW1�:�u�X�n���)�I�,��)ArԠ���+\`3M��*W�I{������P������'2g%зZ!����dP<���x�ΑW�g? of�,� T�Sp�A����'�6d��z��E��Bݾ+��"�+�osN�W��q��������n���B�.����s���
��n��=~�ۗ��b����DJeh�QC� ��u�.�uR�r����,^��E~C�GR&�j����ĥ8Rx�t�VFa��xܷ^���V��K=��ʐ��|{b�Hl"p��,�h��@*���	��n��6�N�\��gO�\����,��,ʋ�8Rd���������ߪ�8�5�%����˶E���i���y��I�儎o�u�°�Ґ�M(�Ʀco~��ɐ?`F���o���5�g�~F��^
g�A�վP�T]ȝ��u�R��S:E��̶�3��V���t���ɻ�ǫ߶V|��wP��Q�~���(a&�p~�b*�8�m 2��x]!e�{���\�
����Y���C*�dp9/q݊�V�:��.'���O����+NT�m��)�oJ�8�@���dw��47�U����7�	H�P�0f�&�:���'br��p�Z�<`g2Cx�/cZ���LG'H?޻����L� �'���;o6��Neh�o>�����I�p��0R��4nܠ3�pk�nS��`N^�b��(2O��N�I�Dq=��Z�$�̤�Pσ���C��!{��c}{O���{�����񵣬dm5X�����'�ƳFݷY�e�pk���GN�-���8ō9��X?.�w�M=ȅ �r����M����DN,��Jj�T�H,�/<�	�+:�3����L�l���3H�!*Jee�n�v��`�1�����خ�ĸ/U2�}r?� ����r��9C�!Z�0ѻ�
��}<�ÍzTi��-�Б�I�u���]و:V�܈4��+�p�`�b��d��:�dm�6Z���^��x+9�A����Ex7���G���A̨!���h��6Q���T�r$�%W�V���?�^G ">�tP�r�����E3\yK<t�����E+q���)��Q��i ��MwƢ} A��F��(J?�/�K4����Ւ�f�r("�t=8Smak�q�/�.��+�m    X��G��+�@��G���1���Zg<�u�:�O%zpI�n�U�^3p��g���\(��s��)�����#�E�������tA�(e��Cu���5��8h}ϔf%������f0��!Vn�(�c�yO1�h\�)Z`FA�+���D�5���>���d;cs2P��p�������)NwB��e�l���7���@�?l#l��J��ޭ���fN����ٵ����V߷���`��kԴ �y��q��r�+��q4��^��8n�i�b��C�+�`߇��A��.��-��F17�FO�/�9Kw,*l�9̲�w-U���|\C�r��
W�݄�x���ה��L�g���%��Ц�"����Q���W�%�#y4C�u���+�[��āD�UE�3����;Λ��q?��J׹�N�u>�9 �Ii̹���{[�!Tv\^��X������ǯROU��P�{�}�\A�0gO�g�&_�#�yގ���<̰�F��Q}}�LN�� ��J�Gȍ�]m����{.&}���k����7!'�7�j�$i�6<��m\KNK�L����dx�t9Qc�YP��T]sƪL�o�rD���k��g���o�1�ce6�GS�p.C!�����e��i��KC\$���!9� �
F1�N�8�H���Fا�ٔ���VŔ� ��I2�s=E�Q	˻d����U��\dyQ�Ħ�7�m���W��9N�݀ub}��ÚQ)�E� O�^t*�.�a�Ho}⡽�M5=�X���xPQP��T,L��B�\߰(hSh�^�4D��kUs�U�Jgi��S,;˂���7
�lFs~C�D%���[�bmd��_^���]� ��|��u54G�#�&��9V�߀0�nAk��J�t��ᤈF��w�"N�d!XM��Q�p�r��gc�y�l�V��=�V��}������Q���^[�k%��X���/5��f���Ҧ�����R8��~1\�p�a�yjK�'ȆH4����UO�dGe6xȷ�tT��18���Q��9Su_�r+����:������\�2�+��.J^_�ލME�%���8G��y���|
�X���I��&��VPz�;��z=l�~ΌE�z���"/-ca�@d�#v��I�>���r>C�q��\���<��3l���P�g^��Y�,H���S{��KW`l۶)1�
`���-�S��G(��R�ge�Xl�O�A1��"���n�~�N��R[���WY���D�� A��eJ�2Hm��3>�$����5V�+27Qg�V���Ϸ���u�p��v,z�7���M��Ų��XDZ1j�(kv����I=%��
�R
��(����~�k�7lB�^�W���]�11�2d�?foX7;��uL�F��Lj7�	Tk�y����k:o��������+Z���I"������c�ޯ's3�K�Ƌ���[T��-o�b
P�.H�[^�
m��>Ot�����
�D1BZ�(RVz�bl	��1�X���%���0��dA�.�w]dhڋ��$�Zњ��i;�a�O0�9 U\�w
�M����z̹��r$crtCw�CL��u�w�%����.�H94m��A��x��Y� �����Ѩ>����F�ex�)Z�$���"2܊e���9�`��=��'hL�&�/FIA�0%As,��9	�C8<� �هM�-
f.��:WPuAvܿ����oU*<b:�+��&���}��T���� �����j��8K�Nף��ՙ�m�"k�9�$�M-�>�!qt�u�e��vup?��j��;�VV��C�as�������ɼG��g�r�����?p�nZ�>N#eMn'P��4��JFf �����֧�ݛ`���H��(�HD�@�Խ��vD�`P�� Iǰ-�����%G� գ�� ��9mW��,Rޮw�(å�����mI� ���\qB�U�R�$Z�c(�S+Jݤ����A�����|��)\Ŵ۵��C%	'p7�gQ�WY�RZ:rf)�+���h��oͰ���P�J��d�r�D��";�	� �KY���u��-r�l<q��e�LlՕ�>�/��S���
�'L�F~^�᯾���'�@@E�F��)̩��!R�>���y�_Z�*���ޅJ8��/4��$�[����|aG=�����y��t��R�T2�a�+�ά�yj��8��)Y�p������h&m͍@z�Ҙ%ՙ8�����>��J�j�5���V/K�`�жd�
��Et��&`�P�zH��x,�·�	$W�������ۉ�lVA
K���4�i��(M���|�,;=���b7z��2�ʣ�Tm5�3�1�u�)�m�-����}�!l�x������ۏ�d�X *c��K���t��E+Q��2"\	�Q����"���69K�z	i�k:r;�P��h@=����ʊ��(�4ۂ�pAO���w�v��� ��h�@ ���1�rs��4\����N3�9O8щX��sI�v��gZMb�:���h	��	��jl��Mͦ+�R��ݴ�Cg�r��n�fF#�����q;�!�a���Ϥ��M.}�T��mb���Գ������M��h��C(�O�Tߺ)�IQ�D7�|���%�9i���u���R��9�D�Lv�M0�R�qa�_I�B�Uڛ�x�§�-��8�M���᳛���y|?�O�-��n3
U�=��!)e����	�sm{�KPz�֜�`�<�1�7�_)�����^>�ck+A��T��5 b
�E�w>���'���W��|�&��o\a���C�X.���j)Gs��H,=�0��_r^���L���Y7�(�K��xn����յLiaM��W��������4��=+�s/�I|� �+�IQ�AJc8�r�
�O�&�z�U}���7�B͵[qӡ��`C��èYr��=XA))��H+\�q��\(՘�p��`�n �G�~��]�F�[�ȿzZ=W��C�����U�4�iB5�[����E�U����T@�Ƀ}_�*T��h�~|�@(������;�.	�3���D>F�i�m+�
\�w|4�]�?X�H6��39��;Y��ݏʪ��H�輝�͍|[�O�s���P�24�r+jr�C��:���n�(���$l0}6��X�>��	���t;��uԦ{�7߱��E&h?�4�ː�D[�y��quτ��Ih�#j��P�B��*`�z�Ĭ:Av%\�5�����ޖL�����]�`#�Inyz;;�z_�V�-Z%�h{톊����f5q�����D��H��O��n@��������z�F��pM=[�!D��WDW�z����qF(��Y�"Z����n� w��a;��z���C3_��Q�;����1�vczl�-8:?n���Nr(=|�Յ��v�i|���q`Q�SW�P�+�n&p��v$t3�5UXJLݶ��JJ�FWC�U�#�#�kI�����ez������~���sJ3<y���i��N%b5ӆ��2J;��ԛ�
y���-��&��Y E/k��G�1��	̣����w���Hb�����9l:�,���T�|wm�����AH\���(P�f�t^HC9�M~�x�L��l�&���ˣN��$�W�TJj�)����àDEbB�i
��MuC�5���"����;�F���_���t6���T�Rb����|���txl ���e�?3���s�@���ɶ2I>4O����b����_�ť�����5�O#�>����H��'������.�WyXꀭqd��P�T��@9�"�'+�,YD��Sw2V�n��άR�c;4�C�|��H��@�9�A�pH�<$�|��!-���z�)�+�Z $���Ct�Wu0w��v�
Z�G��i�%ZE�8�"��)Zp�V��f���Tp��n����x��͢d��g~��78<|�c)1�x�ZZb�d'����o<��5a:24��H*y���$|a%J樓�3�f_s"K?U���d�ON>�����"��T����E���}�Zz��0`n    w{�Fy���o!r��+p�\��Wl!(�I	J�'����i_�|�B=��-�h��d1�ۖEm�y��ʽ �� �n���XOG�pVx]�o|���@�$���0&8 r����,͖�+6���`j�k�b<;��h� ��s�N�	`]�r0p�P��:�?U��
��%�
�L�3S���%`2���)K��g���T�˰�����ώ^`�J��1��!�S��m��\"���h<����M�d�$��7���tI��1N-EO�c3"T�ʹe��H�ko�dY'��H�ġX?���-F��N�\�`�*Ybu/�Á�N�8C����	�E��	.wS{��mV����K���I3z�3� �r�νv�:Å�P���&7����)�cec@���>������%\W������{��%�y_�h��Zȣ9� �ψ4¨iT�����G!��_O�5��$G�	��2<B�V0��6XjR�
��)�L�]�Rf�6s8͙emY�&�T Ҏe��Ȫ,�]4U��L�y�w)~�m�lD�2���1��}~K\Ա[���i����D��WԐ+�h�����(9��)]Z[�C,DVbNW	�@Q VE�ƫ�4���|�QMd!C�؍FD�z\@�o~��4l��2�	� H���M2{T���?^h�KJu�m�U��
���w�M�gr�w����j�t*)q͆-�LF�4%{�F�l�A���6�:C������[������-� �ӱsl�)w�^�"��m$6���u�7�FC�ƣ3Y�a58UZ�e���s,��P$�k+S��!��S��/�����"?:����a�k1�RT:��SF9Fb3��|�ժ�����"��An�V�}�8&�OSHz��5�I��RdpH��|<�+�7ز@ R<�[�و��/Ѷ��G�o�co��kl �.nN���N#fiW�8������~�z\oJvsN�6mq�"�])��PnTF���yb�ѣi�P%�,�Qn�����)��|Y�l��#	%���i>�cI�����V�NT�_��q�1S7�Sr���b$J��-��D���BKO���9��59].�t4��]4,n��A�)�-���`�dI���&%�J��5*9���
��>���c�3'�db/v�8�>z�[Z�@"W�R�)B�?X�6�5�2�%�u�&����/�B\}h��+�f固��ߗ6�W3��ai�)����Vp�3K��Ƕp*����'�s�/Q�eW)���n6��D�S<��I�e����(�t�-`"R�F��P۝�a�zpk�uu�>޺�Ժe!�k��EO|����}n�X�)���ν'_D��H��}���c��}�wVBDR�`�R�}�^��
I�_�p��',��r[�����A��crˆ+JW���;f��e�i�����k��������ȥ�Lstj*n��>��Q��M��^��؍�'Ⱦ�6�<Bl#f����V�]>��l"��ڐ�*�^��'��<�@�, G�|�1�b3���LݨՓ�N�D� ����|6��<�`���eV�n9M�˼mN �)bh	�h��%2
Rb�ؾ+�p?(���?�Q�"-1	B۝IF�H8��6�g�`Ң���$��=�-���¸�,���Ւm�&�3$&�Th�0����850C��eEK$��tyo�YoO6�C������<+ϓ�7�S�g���SSڽ�ۅ���L��d�ГӲnE*����!��@f@\6��r��dU�8Q��8�\�6J��%�)'�]�o�,�3Qu��&%c��7?>�%��6�C�;�:����0'@k�ܐx?�ם��$t��;L/�;�����	<M�^�B U[����W5�@����h��H���6,�+����C����3�2��l�fܣR;�MgV�~��r.�-�YF&��E���/�������C��e_�«��[_���?�+����ZR���T#M�]��%^�$(5
�YA��	|���ih�%2j��BƁa���C̚
�XQ�Ạ�Y�19:�z�{��W�Z+;�{$��m��F3��_F�R	Cuۀt*��[�%��\m�/)�*�ܟP��cp��a@P��P�ԙ'�j\�E8�ByL��a�����Rт���Ө�A�}�.~R�'���7�䩈32M��E��}j8~�s_�WX,�P+���w�p�.������nX����+L���߯�q���~��(J7���'|(���s�?���y�"�9R^�CR��ڸ��4 ��70�ݜ~�r�������Z�E�YF�l�����D�a�D ���!3�Nc4")=E�x������*�hE}#�J_I�gS�ڊ�F�iV�#��fك$�Ȳׄ��==�4;RN�owz4�~���Q�h�p���p�%Ə��+��LŶSlG�z���4)�H����HF_�3Z#F��H��DPv���X�K��k��L�$�$3�?h������5� -TQ2��a�&L�#/��Z0�p�$r�|/P/�]�d���m��i�U��AZM���sJ.W`��'����|��J �={U��:��k�jb
����:0����$����<\�\e���DJZ��x�\y�i+l�[�1L�MC����)ve^m'Z�i������2Q�/�y�ϊ՗C�w���a�-��K�&��_���!�/��eӁ��B�\���˯�l��:SҜ���<|(�9���Q�I��R�.'��x"�`������d���M��)5N�z� ��7b�ת5�Ɗ�y,����Sٶ�ŋ�.D)Ώ^ԭ���{?�J�cL���t�lc���`���px�����	���,�qe���F���m䷯a�H�Si����X�h�N��pk���������9�u��L�ml�L�{��t��߄\v�k__!�1jZ���U��z.�e�O�9Xr8G�d9y����>����
؟�^�U]��2��p�N1e3ł.�6��\���������Xڋ��W���	�y(u6(�j]wp-Ғ+����Rg��vs�a���X������]a����g�:#�U���%����Uj�R�WQ�Y�4b����$�DQ�*��лr��K�ݕ���ŷ�򩾹
��5mTa���,"5QWw��E���~ֵk������7�~���ZS�p���xy�0ӥ����s�����BAJ�����]�v\�D\$C�}�|���v)IKJ�Dͨ|
��C�Z�S�:��4�Qi�^1b���k�c����P��a�6������2��9\Ņ)A�ۢH�����W���,
3D�p�s����޹�ʏ�c�{��1�^���x=	g�fS\� �ɍ�A�K.����W���V�|���e�ڐ9�|W���q44L���'.�W(oRV_�7��Y�� �ل�Y�YGJ4C0J�\�d�@�Ȕ~b<�on��v��Vm�l��֐	�C��G���7,��/��5;��矹�Vw���z�"���`�O<B6�i��Gq��a2%l(����$��x���L�C�=�hbi���vepf[?Ʋ�ђ�f�=�v�~C�l�"��@���]Dq�|(f�Ԡ��^#�u�E������C���t6�%�94�Q�2Ir�'�zsK�~��&_�%�1#����J�ћ~��E.y\�������i�iMh#�PF��o��3�� .�]�"	"b��D�G9~]�W�e��D�����$�Z0>3еu`FLQe��ie����a��V��;��o�n���C�A���,7M��R~��,��On�W�1���eSu�u�>q��U��mU�HCQ�)�ў�z���q�M��3%���5��@�fS�q�2]a�'�y�Q��J��e��8e��4�Y#Q��W���E�L7�SFԈpvNĆ#���-�'�%Բvj
�$�1���hϤ�U��^��c�����#'I)	�aG���Z�M�a};0��C�E����a�=��Ix�6����a�(�(|���[�Ѱ-�7y�EY��$�Em���W@5    =K�R�4A@�#'�e6�!�\Q��9�\��vx��v������H�z�����J���
J= Q"��ѫ�MĂ�	��2�]q��/.���^�BRu�����h5�ִ%eM��u�`���nA5�I%H��Z����=��S#k��Q<�(O�?���wp*��J�Rj6��&�xj<�{N��k��(TA�Y��*�MMF6`Z:�i+H�tV������q9���s&����	�c�k&1䒩���2kU2YE��t�B�  ��hu�1۪��������q�kZz�j�S"	y�����H�������?��d��2!+�BH���{�1��:=���f�6M!5���Z���(��%��B��w��ը,�A�����K���!)v{ɀP̋�x�j�:��&]��w���k�u�_9�P�̢ �PN�K�c�X�۠��2b+T�{[��R]�X'�E4���9��wR~oYl�@X�>v�b�B��hf��
�мia���/����G��՜��WHB�dX)�a���*���(W�-�{h�ju��r��8���P�J�C	���|�1��r�d�<D����C��q0�L���W/�(Y qXY
��b=�#G�����&���Y�ƴm������73ۦ�i�k[yPƓ��r:�/&x�e�(�1��p�e�0�Li�Hs�[��H��
C�R���I��!Z�c�f���q�������V�ۜ�,l5N#%z��a�N��NF�\B�}L~���l�6rq�D�u�w����qȟM���q�$�Si�7U��)�v�NU!�v�n��s��]\�;�H�$`��>,C�K�^�b�t9��ќ_,P/ݓvO� e8���
�<���칎��9~����?E]��p�|��8v#��:�p���b��������đd��:O��2Sʔ~͕m�E5��]k�ldn@5®��oDfJd
ld�U��s��\�FB
�玽�]�8�Y=��;��]l��$=��=�i���Qh�{r3ɨM�� ��Hy��`�q��ǒ-���#XN��p�[4�&U:@6I��z�n$\���. �x)�2�m+B��[6�F�պU�\��}d���K�]�g�,��UGvg�����������ގg�qb�h�9��k�K�B����	�����*�(ػ�@$	}���w����>��~��@�o38�oi>C�q;S8u ��%ԩ�;FϺ�^w��:��X��<�Z�_��79wP[e-��9���_��>�ح�i�s�������<�a$T���l.��\da�2���\|c!Q>�'����s��D��]��c3[]W�LqW���q�`!��;�Ĩe$x�܍��ø�rI��m�����X"u9_�g����+�v5]�{��t�\�$��9*U�/�Բ0�v�K�&X<'N�����n4B˶"w�~�)X��%��ad�A����ܲg1�Т%�m;2��[Z|���Y�O	�*�s��+�X0]"����mU�?����<��Kk�0��0�@8�$l[;�+s�m��>/n�֤|=��k����V<(?��R'�~X�wpZ"��N�/
ڭ
�-��]HD�"Y6R��A��7N�}���<	s�Q�.\r�?}�_�{62ї�8G��1юSA�}ي��i�IT���WI+ի��ژ$�*��(~b~�q˄7��#�R�Y���Α�x�pnϰG��j2JH�L�*A
THR�I,��ئ'T/E~=}�0�g��1Gu�5�ۮ�
_?��_sB�B�:�Z�n>�bA%��e��6뙔�H#�J��Un��C�һ*P�5�U��W�J�\�	*����>�m%FT�B&�������:�������aGp^�n�0��ǜO LB�R$�h'Oߟ����K1�o�x����D��;x��?6m�g�he;x���T���:%�>�٠���(�Z��?�����٠t��Se�M���P;~�_I��c^�]��6O&ɜ�(�=Yz_l�r>�1Y�)Ӧ��>����PF&
��jI�bM�fx6���Ȝ~:)B�F��K~|'���%�j6����f�fUg0�j�K�2��2��6W~�k��=���T��~ �>Xx��W��ٺ>���P�J�ɺ�ևǈ|��h1�p�y��<&��w�Gb�{�s.�t/��Gj2WQ�����Qv�L|zm9��xt�$ ��˚M�{e���]�Ok��aԯ�EZ��\�k�ؾ���]�I�~��ߔ��C6l鲣����l��<�r�E��8/�|(��g�A��B���pYV�.�Z���+S�\=d?���dZ����@��Y�{'}�4�����5�#��K.P�(�[�h��q����z�oK��B�~�}u����g���������i�>O��l�'��s3Ɛ#�b}���k�:�����P�PƯ8o�6�ص`���k=���q���i���2~D�,?�b�d�x6����vŒ��ҧ,Ȓ)�7Jx����ٴY�m��M���9�`x��e�$\4�?��@��@5{���ݓ�r�╗�,?!�T9y���!��A�<K�� �^�q��W'fX�l�gSg�ඡ�Z�����Ӆ|�Y�wW&6�8*�顟4��B��+GÛ/�1��U�����,������������;8"��TU+4W(����X���ME�?���O�DW�aK_T� ������c��iJ���y��Y���W�"܁ >��Ҽ��J��D���q��+T�¥�C�����p�k�������~RU�i���X��(�WH���m�k����Fd�_��T칔ǀ{2ϣ�s:D��h����h���@V0�_�$u&����\>�DՌ��*:ù�	�]d�J�ΪYq��M}E\e�q9���y��y[r|���2(&�$���>�B'�	���j�rW�*���M��UC#Qq������p�WʛCY�r"�D5�S�@���*�w��]uw��dFT�V����5��,����>-��'����/f��ԹG����>���>�ii�3>wN�.GP▯����"�0Y+y���)H{C���ƨ���5���� �VЗ�j��1��{�A�.9	����.�ωMp�s�.3�f�z�b%g5�M� �"���$\�=�F�+,�~�j0gԯr!�g���7K��W��������7����X3���P&��.���H�#��M�4�
���Ǐϵ]��E�T��>ă�	3��~O�������oك3��ك��/y���˻��B;<y-�Y����� l�Jڋ�[i�>K���/�	��� ��~x������E�2OB��=��|c���J��
!��DA�%]i�#B2���}u��b>�M�� d�J�ݞ��u8���H�;��.�Ϳ�)c)MK���$\�_ʚ	A9���xk��^�5����Z�Tk�J��n�_g�����.��k=���|Rq��C�\kh\ŭv��=�ӊ%q�3N�G��^�����+�+c�:�:;��\CT��i���g�9O�!b|
��OD�q����ʇ�C��2IU8E������Ϥ�2c�Ս�g>�v���Ƥ�u�z�d��	1$�����.�ưӕ\�A����\Tt���u\�;�p�j�4Og����}��O����o��P�e��Ʃ/��-!�n\r-�ޞiD��L���.�L��RR�`��`C���bIP��cd~��ø�S��a�
?�(��o�JCه�����GU��2����t�R2^���2��}-���e�(~W|��a��-Y�U>}�k]����.H��	���8�U뷸��-t���!Zc�Ƿ ה$�O��`����>�e���C)�p!�v$%�p����q\������
��jA���\�3H]����3������g7<��չ��M���;��_�ݖ���A:M�t�&��s)Ff��X��%��r����(�!K�m>3gR��{�)��d�G���ܠ�йG%���L��4��=�Ǹ�|�-�G�1���    �<�V�YyRT� ��/B�2�q���~��B������������u�_�p,[MM���}k=a�]ژC����*��>�P Κ�����3�҅�/�o}��H:d۴9x��DT\��Lp�.4b���I����X^'���\�hB����l���2&�ϗ��~u��W�I����[�OƳ&��M��.��և���n��I\��6�4?E�����ҥ2�ă�\�M�l�r LJ���Ә����8Iڬ�/���!�P�ٸ���̌q�3�"o�|��%���lo���L!%q�)NR����3'���B�0�܏�l�̬o�Ԡi_#PP�t����Ͼ��3]�%�-��p�5W��'��3������X�T���`��s���GW8��'���rM��f1by��0ԝy�Ud2�b���b\
��I/֘]��,�q6h.ɠ~N��ibS�Wp��H2��d��$����#ː��N)�}l�wZg'��	�+LY�!����!�f�U�i��=-��k�DY�Y����-{�럶�������)���|�﹍$�$B-ّ����Cv���:;+�]��^6�B�L��j�Q���@�2�a�
�V���fN��|��6���8�WY{[��V|�f�>��Z ct07s�w��㭝�L(�N�5dT�^��I3�D�5.�~�ۚ�y��4'�6�iv:r����^"3��@=�K�b�Ms����LKء����3͕�I'=M�N���C��[?�> Ww\݉�L�;�n�0ɸ�P 5��ѷ����Ze$Je���������Q�����9����Rsa��:��Z��F�-,�R0�V��q�%	��'���YSA�'��6A:)�C�|��aZr�f(�䬪c��!CkȜ@��<"B�I��2ϕA�Z��B���Y�
��~|�o�a,j]`�H��Ț����g��iM��	>D��u+;��D���N4h�Iu�����j�P���Or��1��"=.-,䜢.�a}�筯m.�6ypn'���m�=�Iǝ&���������i?n�A6�F��/Q9db:-r�nq�
E� ��&�!����;���6�@]?Lk7ܷCe�� �=��E�|�4q ^���x�<��f�&�Ie�֣�ET.'yⓓ�A�^�iв|��cJ��=���U��!D	騣��Ro��Ro�z7y�C�]�<A��~W����FR�7Pt%���t�/���/hh6�'�(�QD��%�.���R�8)�����uBX�Ł�cNH���9�ւ2n�Ai�x�/���RRY����e���&��}�.��G�T4��,�k ���T�c<	}�:Bî�_K���F�����,L��* �>Fʒ���x�G�?qu�`7*�8�FL}��_�0���^���U�d�H�m�{���#iLE���Uk�������� �/욝��]8�׾�p�H�a@D�)/47�U߃�6�M�f����qJXv_G�$�SsՎ�iѪ���J�)���v��_������V+�r��dn����4o;�^t����2m���௾.�z��b���V�C��9J�y�G�y�K<��6���`M��&<�sx����%��Z���ƃr&���E��?�A5;�8$eFf��� 2k��oR*��T���ŝ�cEk�Xuvˠ��!���]{���Xӑk�i�7W���<pI@+���*6����5Q2CW+�ń;���Vʍ�]�o<{{��9�Jk`�}U�y��\��`�T��4w.�����o�	�l4�>"��8��s�m��)yϬ�5���xr{�5��;D�5pGQ�r�I5E9-W�A����Ca_ڋ���i���zAc��l'���?��G`r1�ш��������� 1���U7zA���^�:�ɧ���$qn�<O�dv�N&i6M&��i��������F��0.�2�B��T@�Dʠsr=w�4��2�b/!&=���`���j \���	�%����*�k�� ���(Bdx{�V�a��,��s�����3M��b�=�ܤ9�B�'Hȍ�� ��]�	5��s�������:ns���0ʳ���(y����d-��1�@wOK���pV�'k��3R��. �L������n�K�mn���Lk_|v��d2��l|$��L���('8��.�q�[�q�,:�V{�Ŋ��[O�h=aA����Y��3�2gۦ1^�����ܷ~b�͘�I�"]=m](?��K�k��62�"��r�ᢿ��k�>��'��WpM����M�^S�hҩs�N���i��ab}b����g�1� ��sI�T(�'�#��3ǃbe�f�����K��q�-d_f�p�tutO6�e�Wf٭�q�>Ж���s���)�-� ����0�~y��鐒��:�����X=��&
2r��뢠��C�C:�sݸ]�d6Y���;w��>O�ܖ��$$����
��m�&䁔5��QD ��"{��N��ķ0K�|�vjcu� ����D(�ŵ��k�v���siL����c�V���I��������7��\�� �-����j��k��
�`.uv���L|��d0h.i��~cHBf��h�Z�)/P�@��������E�-���.k	���zg���ȓ�.#gH���VW���o�@�z;���̙e��~<��ZF(�9қ��KH���3��/��%����	m��Em9�5#����ٳ� ��Ϋ�2e���4��9�f}�Zt�B.��X2�s��f�"$L�/�}$,d0�逋��k��6^����4�Z���톻1��skmD׈ro��1ĸ4SȢ�V�����.�0�#���h�.�'đ�&�24��ŲU�DG�5O�?uDsC���K��[��z��-e7ai����/��F��y�~$y2OnP��P�))ٷ��@�\َ���@Z
5���5�kF�=@�R�g�OiRn�[D�(_G���5c�����UcŞeB<�f�D��m�׉+wy:=O�F���]4?-�S5�q����B�@R����<��t<@��rP��h�t�o��߯<�ы?�~�+[Q�����jX2S�������S����.�Q��&͉ �O���_z��	
zO�"=	� f��h��@��(E0B�aYu/:S�$w��1˳�d�B�T9�/�8�g�0�$����i��b��D����㖼���q��v�ǣ�z�{x��{��{�pь���. ��_�m�O�
�x��̿��*�(�'��)5Q�#ũ�lu�B�;���?�H��7T�b<�����.�3);G]�v����Ċ}�h�����ֺA��b�\�����u�&k3y���ɏ�>{H���}V�����-LG�ڗjof�Z�$XH���JP�m<|m�˚^��C@ϳ�����v��_ �}&��:��e�]�,����h����?�T8(
�^��V��iP��=F
N=钐(p��P���F��6��٭#����G�g����:G�v�iǍ���$wP������w�5�Z�z�.d�{4��8�v�)T��a)z��*pU-	��1�6`�Z��y� k~�]����S�V0%u%�Ѧ���Ȍ������ɹ�&c0��� ��h�>�U���ø��o���Q��:��
BP2]�иT�_1C��` 1O	���U�ޣ߸��RcM��p�[43�F��Ît�R�����mi���o����t��}�����r��+��R��ǹ����]{�z>͝<�'Oy���\����^�0v���~�gV�������Ar�����!Њ�xJRА��D�u��>egOP�e%�$o������l�8�t�g��CkM��I�I���q�Rk�HbA��%��0{���s�ѾĂ�Ϩ#��WS���]J��������[�ۋ��9��#��tbP���hhx�.�(s�N�ZZ�4D"�X�W<j��.���0*n�.�z!!"�_�    �C�s�Wx����{��z�ڲ򳔷Pf�����4ɥ� ��b�49d�x��g�ea��o�.���H��S��g���4�G�&������c�o�Ο�x��\����I�̐�Q|e���U��گ ��l�k�+5['���|�5�4���P�ՔU@m�y��Y���"�P(�����D���.%N2�;��?*���;�#�g������ڨ�m��4��R�ఆ09����|�+x�p�W���dz�pmnl��~!r��������w�{:�B��&�3���mTOk.���B_.(1|g^=Ґ��dS8�����������ీx�?ָ@�����&u�[Dh�y�RXR�^?8��i�}��A���r_s�@F���/�:��L��,t���D�	��3�Iv&�d��1K���$����l��(#�Bo��R%
��e|��Q%E3�$�
�����Ż�F��(^Ii9;���������.ztO��+3�/՜���eRo�:��~/%�����mX����d���6K�ո�&�t�������t�V�	�`�^��q���c���j�NF�䡍�
ߑ�Zt�u��#IbD�f�RF�b�[Y�-��7�8�J8k4h�i�x���N���ń���_x���j,敢0ׁ2���Ok6s�� ���U�E�AC`+���Ϲ�	A���&>��R8��de_�4~���`m������3>?�٣�;CĂ�9���KX��>-�/�5Wo�W��_�ӈ�'-Y$��:A�;�q�-@P�h.�h+���׫�Ek{����/K�n_��
�2�cL��G�i:�OrxL�Ff�M�]0����Z�'���݆,H�s�l�V��or���������w��S����� ��5|��P�G�A(\&r>p�T���翡�^�V�<�%Q90�C7D4�e܎.�9��J_{��X�h.�.��� 0�x|>_�7�:�(�i���vc��9�u��]�m�U�L"{ij�ݫ^m�
n�Օ��\��ܡ�	���H�h2Mn�Y#F��[��2إ	���@��^��*�J{�M@/ˆ��џ�/~��>G�j�4E=��ruQ�y�4:j��Q��'���큁�X�@`��f/ɤi�υ�GC�h.������$�%D�l���!������x��K��[2�ת�M�!&��ڡ��Fv�� ������	Rw����-�&��?R�*BV� q������/<]2�CU޺���2�֣hba=�!MWZ����>{_?�(H#	�4hez!���)T��[:����}��B���hB��å����[�*���w2U����q����k� 5����T���������yN]�Ę�|b� �8�UAe+J�D��R7P�L��e�ʀ�i�>	&���K�����GOo'Z���yΰ�2��`�7���<*�8ĉ�e�l]�hr�]����{�,�wk��`�4r_�����u$��~�~�C�G����7���,�~o�QT�vN��(y����H�@���l��x6��'˦����l���DB�����EY���֚_|ȯ������$Ǭ[T�MчBM�G#,q�\�/-A�>�%,���b(Yk	����w��]�F4�As姽����wR;I�ٳ3�$n�	�*Q��{��T.75�\-܅�o���S�5��2.�@!V��vT�";��y>n�8N�d&TH={�Fj��NK�f�Q�Vsu頻E��߳��.����Q��c޺��k��)�w�s�R���	�s�t�K��5^Dr��s����Ф�~��c���Dcr�<$��'��.T�#�,��AVW�
��~Rc-�\v0��{�^:_��&Uh�D�������\�4�������o	3�M��\w��g�jof�R+b����]�iD���	��oJ6��K��D�a�5�O(�t�<���)�C8�/�Z�M=O*g�j�Η��~!8���ܐ���\e��bⰰ�����7g����%���B���Q½��v���UAHѝ���*�r�ͭ;�{�����#�܌�����t�n�z�W����QvӋc�|���W�ސ��PL]�3��q�+)X��ZJ6�o"Q^�Bi�;�ȿ��70���>rM��x؏�.	94m�5�{�1�� �)_&�c%��Ue�")�Ur����	"[jYH�� �y��L�=���v����=��,w�3x�%y��0P���5�p��X�,���,p�GTlԢ���u`���Vq�Z�����ʣi��v����.I�<J_��L�������� "�l��20{�_�e��+آO!+�\+󶵌���Q/r�4�?�����B.AR�4w�b��/z��j�ԭ2́�F��X���h"��$�rYH�2��f\v}V1X��6��]	.��r��� �������=����p���eyk��Q?MҺ�o�	�;V^�L7tڭ>v�r���%n�T4���T�t��#�(bcO�8�Z�"g	x���;�+ւ�B_�
>q�����0���)��Y�~�叶�![[�D�i��6�Z�S�X�^f��DQ�U�@�NV�U04.l�@�x"�h�'������+�M�{�?[��,����|��}�-2d�~�5���u�-Q�'=�y�V�����Zb#�4x���kơ�^��&Z,�-Y*bT�:Z��\���v�\�U�[��;�5���Q���E�@�i��Jf�t��˛|���b2��tGO@%�[��<���JD�V�aF�B<���wE-%���f��~�䚭���y��@���1�	���#�zB���Z�>��}�����NeM��`��ӕ�lڎ���Bi���#K[������pz��u��`o�[w����+�%T���8M�T�
!|�G���$���Y4�10S1Ȗ�  ��� ��b+*�ZM�ä�SN
n�t�M&/��%��g!�r�UJ5��,F!�%� �Z�f�	%|V�d�@���W��f���di�g#�BE�9��~t{�wi��x5؄ҏ�f�{�%�R=�;r���#Aӣ��s����E�#��t�̓<�'M��J���}`�P`���T���Ou�$
���:�A���iG����F�Y�z����Mي����x <,���$N+ �(�*ԓN|Ho��`:TT_tU�h3�	�ػY|4����"�:Ŕ��o`^�Z[�o�m&����d''��>�-(���}Q�F	�,�#R���IAp��@
�9��� !|��������Y�̳I2���Sb�.�I�w��?��ҒU5-@�I��L����"3�`�u=���������Ӊ�������.U��,�������g�*ӊU������l�NR��fp����7KYJZ�UB?/�XDS�j���!�I1W��I1h���/�`���>﷘]m�n��on�I�;�>�/���{��dc�������u4S��+4�ܠ�\�������d(����g<A��,�f�l�-R|/`B@�n \4��Q�T���J%Ѥ6mS��!��xxk��4��9��n�u>A��q�2{Ν�<��Y�#��̜���Ѐ�l;8���kծ��#:i��w*p�^,Z�C�M��A��C��jL����ea����/��
�-s�����E���2Y�>��M�Z�Ψ�f�(��m�=Z9j1�r���ȳ�L���O#��eS#M�d6�I&�\����8��7��p�L�		[_��,N
����3�E[�#��q���+��2��"�F:��\	�#>���J8#�2�W͡Qf�4�����u�'�a<��
t�p���m��~���2���z�z�oAE�����e>M��iDT����δ&3�˝�<��	V4M�/�dz���|N���:��_jM���ek�t%p�ʟ�y7"B�4�{fK<re���o>��&�j���I'�C�(�D�4���:��f���mg,L罬2�����i���5��ʻߺ֪3�l-]�!w"    )��iz`x�9����nf/6�ΕVI������Cc���7FsȦ���FM'���t��	�\j&gVy��t��Yⲃ�U�v�{C<�!��q���@���!����$}p����$}I�IP��
C
S���`��h����!B@5dcj�t�I�ˡ�"Ú�{�<~Kf���0.2�6:�8T�9Ō�B��xo�Q�^	g���� ��]��v�钓��pf+p-���#���$A]�cD�E�|:E��n���?�\
ы�zL_�:iz��f��{Gd�؛$�;�@�݀za�\���@���q״-���9߲��yN|�Z�bz�P� �Sh�bܴD'&�./�LF%�!�:�z��?E�z)�!�j�5��}��J���L�cj 9,$xR1H�c� 7��O|��K=]��[*���`���o+n�ҡ�)�IB|��,���0���9$��gP��$��c��ȿ!��V���仃�$�Z˳Ɛ��ep�$y
��ti�W%&�ŗ�z�/�v�>ʚ�O��U
ae��5C�Ljl��W�z��~x����Y�`m>t
����WC��ר�l�|8������ݹ��D�����lo;]��[U��|��2D�\0�k�7r����r�R��[�G��Z5����#h��S��x����=fI_�!���,��!�(p�Qg�*n|�+��Xڸ�pD��I<.K���{%�v/��&�{�d:�� O�kk��0<�K#��Pr��Sw����8~�`�O>����v,�&3x�-�\�X�\�{һƍ�g�������K�I��|*�ziv��5P5�t�Cn��ܤ�>�/���(-��c�O��N�PB���P��@R[���)8��xVu`}H�\�a����Y��\��`��L�WI�7BQH�Œ���ޒa�U��̿d���)g<��4d+tO���7�1�38T��]���B=�Au�+=��A�W>¡�-�g����f\V��ڭ�H��ኚ]a��ڥ��R�����a��	c6I0��&�Q��.?p�XY7�,�"z|���Ti[a!5�8F(v5󤌋�Յ� �
du!y��ǩ����!Bw-M�U�����V^�}Z8�PW��fJ����	�%��Be��p�yaW	��d1J���ph��WQ�s��,#!�sV����q+��F|����=�V�=�������^b�<���N9Ҡˣ�(3Ez;�Π,���6��ɭs�Γ�G2�r���Ϡ~ GG�pyd��ZH�/[x5Z��ݕB#�*݆[,�SE�B�p�O��G��"�U-E�5cI����D	��r~�O�iT����U>W��:�aDg&���Z:Sj��#2�$s��A�b�e��ׯz�Q��2<B��CfG��kK"\�y��Q�Bs���F�&U>�����^`�3~���	�=D�Iֈg��>�eo���OY��],�Q�:�<ΦͺoÌ��A��G���F~��x��z��,J��S���։|�Ѵ������V�4_{Y䙟��$��Z9y���!��A�<KG*�P�]b������mDXm���g����f����M3}��S�4�4~�x��Zjn��Du�+�U�:C�N���C읿�o�^JlW�x�C4�D`-�!�W��#�ɭv���R�K��Aq�@�M3T�O*Ҧ|��1��O�j��N&�?rkGk5��'����7_�/6O���,��Κ�L�n��5_�����<IS�4���j�P�2%8��l�������ۓI�˷��M�?fn�[Y>���E�M�_,���X�����e�
���Z������;g��G���O�c�YER��4w�:�q��o
U<�r����+��]������kO�Tn]�<�ւ�Q��5VcS���o�T��w�"�����_s�S+J����?)f�򞅐��8��pG{1ӭ� ����L�#[�i(%!p��Ix�ȰZ��D�"�+�E�!�D�����_�V���k��8�O��$�Q��#����P��8�YA,��&s0�B�*����)�:t���(���;�U���Lv��B�[��}$2Ƶ����VT�Fi|+�Ab
4 Q���|����u��/�y��Ȱ1�7sF,n�9��	]u�ǃ�"H8m-��.�|���`1�l1��w�{��co���Vw�`�Ud;�F��d�n�V��`3~)�^ݗ�����@�STDE�3ʜ�f�4�N�����~W�E��5�GO�3Y,2Y9;x����{�NQ}N���,���QXK1_:]��PN�B<t�Z�l->���P8��������7�k�����&rP^�1� �^C�Y�����=�7[Sʃ*��U� �9ܹFZ�L�s�XMڿ�&��3K'&�������j���)u�F-��X{����B֬����5����^�`*UC��m�M*Mx5`�b��3��~%����5�dЂo_NR�x��l��w���<Sq���t��N�w(�p��ue��Q�796�d��;�����e��pS�捥A���!�꠺�?PN�q�w,"��[�>���Pj2xC�rɱjϴ��.A�o4��W�	��9���E|܇�G�w����N1���EX�x� o�q�U
c����;טW
M�ɟ�P��Jb貰\f�,V���k,3s�+���D�/�T�(=hH����4�u��<Knn�[���m(���~!���K=݊�L�E�eH	�`�����XQB���In��N�Q2If��V���D=7N���/��g�-z��%Oq�o�_dd̢�V[�@��d�\�Us+|��tx�����p�ȼ|3����U�:��\ԅϵq����U����χGm]!�ONr��~K�H	dM�A g#��_���!xF�cĸ�8��e%��_��d!�ǅ�U~?�Nvs,[oS���[w��lmC���c���p�Ĝ�
�&���0:F�?���^�����KK¶�ހCc*�n�.<?X9\�KE�A4q�4�wPj��j�`J��w<<��iՂ���ݍpQ�A�Z�V��S'��?%�	B�&���5�8��NO�=�����0����1��2��@��bk�Ȟ��늋�D�"3�?��s�{�7Pj˵6\\kC���{�^ig�8�EM��q��`[&��}i���q�pu���&ģ$``xGjR񯽮T����F��
L���*�Ǳ9�g=}i��Z�=�2fHW���G��ʓ��{��j���\�d%��~����df����;sO�m�S���-kAW"a���_��Ճ��m\Q��M�v���㏭���<:�#:����zz׶־ž�^�E�����wO�#uzk
}�<z��0(L�d#�2���i�)��b����1lj�Zc������K燜��T���&1;��P��h�.:Q����j��Š�\�~X�a�G���@)���<{v��i2Aq�Q>�J_,V&�\�����Z=S��0�@
<�˞�;�-E8�^@w��L�c�#���|<�I�6��� �����u���k}|���y/u�
-LN�Z�����?�[���Fb��ms&⟣��R,�!{t���ՃGbj+���J���U�L��RE�J-��`���P��zǐ����g�:1n��$/�u�UNg�ȿQ�K��j̺��l�d�y�jvS�+��<�t#����������W{W���r��H���,g�-�!��Sf/`�K2�ɓ��B8<��YT�����r����8����W���*�S"�����TȻ��	�=2��֤"�����F�+X�k�r�@qc�riR˨���V�c�]�M��ƂFL���'He�8��'��լ�5��&�+
�$WJ���\m��0�!��p �/��fe��j��~�������{�j.�~=�S����HlB��+�Pǂ[x��^�M��ݨ�"�{g�.+]jV���qʎ��K�2����śg�O����
E��l�C�^��R�a I�ƽX�1���R�ȲT� ~  O&)y�\�Ѓ��`y�	Xv�l���)!����K�e�C8b+�����vn�Ek�0z��:7)�a��S�x�$�&�4<�9oG�1�\�Bމm0WhT�\�F���ޥ)����#�G�آ:0���*�����;�r��4o~��(�0�a�dH�ջ���F±%Y1"�4�UI)çL���J�0H�	�h֧adm���}"�6�k0K���� ��GxW�m��|��oʷ�z'f����o���Bg�.2�r�P5pu��\#�-���<��?����*zL!���}�P�G[�Y�f)�E`V�K~D��1�P�,U����!U,�>�޲3��e�r�� t����p��DKc��W�5�����2ޒ"q�nW`��U�0��
����NV��$O�y�L��e�'���,hË+T�τ�I\���*�px�����7����͏p�?79,�u�7��a͊`偫e��7	T6��mp	��@���gK!˙��cH,�%Nt�ww4I��`������@ͨ,�8ɐ�!�a<On���b�����Ԗ�����2�8��b!u%�"��.���Sa
��9>�����A�����]psy�f�p�Ƿ���$��Z��k������/��      9	   �   x�U�;�0��N�	*�a� �z�.4f���e;=h�dC��xn˞˪Ye�FI���_M���p�gR�:{��W*ղ$5�1��{oe��9�g#iE*'�p�����K�|�#F���[w���U����uC���E      &	      x����v�8�6���<n�=�C�	$��Cw�$*ܝ*�������\�0� ���*�s�R�g�`���l�*o�<k˼iTr����5=��i���1��J�$o��W��*���߃�k�]�^�~&��ۏ�����0�W��
��:ɕ*�j�Sw��w�)�����v��[Z�.3�#
i�ض�J�2k�Z�I��]{��=�����_�aL����~�ů2�!���<�?��;�زl
"Q�EVT{/����ԝc�`կL����~ٽ����{�w/)����ܥG���ڥ�?Ӭ�&�
]�jUєM��H�jږBeuU�ə�����r�����"'��"���tF�UL��:ކ�qL��嫣S gr��O��<x��2L�\�y[��e���v�l솅+e����0��eL��%��k��/��p��%�>o��<������r�e;�����[�Y���hɲ���<9�_���F��E;2��mf�_E�d7,i����m��FZ��G�f�G:�]��9��">���>0k��pG��E�=�C��6+��L>�3A�w@<��I�+����q�vM��.���ٍ����E����+ɪ��eV���hf+s�'v,����4%nt[$������qO�'��`2|NW��3j�f$%�Z�6�����'�.l����'��$qx�q�iY����iV�G���t�w����u�2��Ϊ����,�U�?�t�I�'�����~��L����ԀH_,O@��J�.HY�
�y���z?�é� ����m��/�-X�߭t�A�\�t���\.[�������"�u՚GDU*ǽ$��ҩ�N�ec}"�KHǎDG?����@�~ʋ&���7n�bA/yF�GRa+J��- 	A�p��f��#���3#A�=V���y�����kPn�:���zˌ�1�����UV�q8�!��K�e.�>݅�q\�X�W?8B��V$3�uMҢ����;����{z���ŷI�v��iG��J��/�W�t_����6�z�uF,���� �_t�6ɩ�_蒎����r����"9?�N������WH;DE�=�m��E���nt^�X��@�Ĥ�E�J���>(�-i�XP�
<U�˹�G�`����s�f�>����-}�/��| ތ�/�~<usP� �#�^>�iԑޓb�9Ṭ���M��tt���Rc����ڍ+U���d_��E�I�exr��JM�Y���C��i{nt���C��b=/�(�-bC�(��9}9)��\C��g��u��gZ�[�֭��e��YΖV�[ضn���C^U�&=wߟ����E�fN��v�A2Tk�"��]I�;�3����7�O�s���&RgU���YV�C��s))|$4Hf�����z�f/'�&7�=�$ԡ��wW�1��_y�+��/C��13�x�J�~D"VUt��C(��:���tX�6h�1�����X]�s��")J���	�)xd����#�C��M�>DG�"�x�����i����.��Õ�E�
{�\��|l�$~����,�7���ۖ^�L=������.������O*�M}:�X�����h��T��/R��~�j�mKk� �&	����2�cٶ�&=�l"���驲b��|w��+��߶FdIb�����N�6(M������h!z�o��\C�հV�V���+����I���b��J�"�O�c!m�?K��i�	v��l�����x��a��@n�3��f[�QeeF�
iQݽ69���S���$���Z����a��MFh��8��ާ���3vL���HX�6tU������?.l�n:�d��z��-����*����h���Nl���B�:<d8�n?����`p�lE�')���i����}���#w-��ז���sCH;D��̩�΋BO:�S_�݅>�D��
�n��u����?�VYÆ���Αm�Yu�q{f�l��������Ӑj�/#�4i[�*�g�lK҉6M˕0K&�L�_F�q�}������i��7c�����;���i��~�+x)b���o����x�"u�1I�Oub�xp�9�,�	�V�X%�B�3��!�����l�F������H�;ز*�4��=���v{����nO'�~|��QGΞU'~��'��?��sȨ��7�S����J�O!lYn5D�Ɖo���*�\��j6¢?�V�ͭ�"�ʑ62�y��.w��]��*#Q���v'\8���Q��v���:B����~��Й������ɪCz�߾����oA ������@���o�������'�:<��1EA^����E����6���T��L��� ���AU&��n��3{��:~�5k���&�a��]��U[��������i}�j��,���;��붪h������>}3��mX��Y�B�"ҳ�X����5�g������T'�4���K���"�����A7�>�X|h�c�B��jZ�ƛ�T�
�
��:���ؒxK�������J<��*H�WI�9�vX�=FС#/��JU?s�}K�\�$.���Α��>��-�_x�où ������YG�%�1oD��U���x�Ņ����2��/������b>�~/))�u�">�*t��Ou ˘�����O: ����T�#�x���ѻ6.��P ܫ/����� 6n�G��� fJ+�lc-��uF��x����/����~�©n���'�П`�B9/���ҝ�y �q�Պ-U�zV \5�C�Sq�&�h� Һ���3:[�MÉ0Xi��C'�1��������Ҟ�9�M�&C���/&��^�t�������"��=f���b�|V0��|���L����������ˉ�nw�~���l�Ѻ��5�SX�����0��<�z���k��XF&�pI�=$#ɀf- h��4p9�{�7B.s���X]T8_��֤��Ͻq��7r0�vq��9��E���������ҷ�0-`AmTt�����H�)rh����B��&xAB����lgZZf��[��e��YWu��B��l����"��ѦC�;5
���՜ϗt��joE��J�x��+EH!���&,	��8^آi�2O>��ov#�	O�,�K�r��=Ж��d�N2�J��SF�$&rC��u<�~Й8'��m�P�a�rޔ�9��Ж�����9'-la������\pY�ې<�@ E�$��i)=A����lk�/�y��ϤQ����+�_Տ�a�rF����S]�
��^�$VR�Acʽ�BJ|�^���܋�$�^8��;v{R���1������m˃}�'քc��K���"#1�������kI���Ⓟp�����3���/K���
�EG��	oi�$�s�����B�4EK3v�0&�O�$TV�Hi�f?(�DYÏz���r7c��њ�bNr��!>�K��a'�ӬH�ܝ!�\�X���?��YN�O�p�hR�;�GD�;��E�,}�՚�ABۖe�=���7�4W8�.슰���k[���س���{&}���o�����:��LL�iH ���Z�{���n��7�qOu"s��2�*���ۓ)�U�]r��Z?�9�~G_��G��O^[�?�jHت)�m���1༺�Ĳ#Uk��W�I�ZH[�"ץ��IP���ٟv����s{�l;��7��$3�0i�5�������	]���s)��EѲ����ll���������Ҏڧ��v�%�{d��y��&����7a:(��p��=7 ,�	��� )�iU��z=w�Ã4���~�Z�����"v�%���k��<�
��&��]7�0��r̕�hEʵ�p=�Wv�K��;�޹-_�S5��E,q��آEҭM_!U��m�Iw4'�?ulY�n�
���3h9@v�+�n�q�-�m����UV6P�5<M���"NH�ZZ+��y��?){/�\?����g�g���s�-i��V Vؖ������$ʔJ^�s�N?]ܑ��Ivo}����nmKKo7���"yH�� s��o�)����&.�S�����r���X2�Z���S绰    c�T���ey[��,'�܁4[���(�,�u�]�^'KzB��1�ubU����FJ���M��
FV�K�9�E��JA�mT�a/\M���V<e����ƶ�������/��z��f��E؝�+��L��<9V�,�L�:Ҥ�pӇ�.� �ț���ҳ�h!mI����o4�B8����\>3�&�e����}K�B��ֺ��]�k
�B݂5���Ҿ���El�fc��5Y�$�L��o�l��]e���H2��d��!se�A.s*#���/-�?J�iʻ2�O�2GY�m>�hO6ߒĶ-8�KB^�)у����q����!Ė�ۏRK�����K�������չ���x|�o��J�����^��ܒDF�4n(@��}��b}١!X��`9����j8��(�^KRq��M�O�~�=������O�����~Hs/�DDeU�"xILj�0ey�m��}ns�&f�h?S6����nn�oaUN�����W��������#����|gLP��&��Ƞ�MH�1y����JO�~��w�m9ϩ��9q��AdV�J��cg����[B�f�-miN?s��&X]��e�V�B�݃v�}f�M�����Y��F�U��;Kc󓲋�����fi;���MGZ�P�����Ig<ޯ�Ƨ�]㬮��ͣ��$a���������(�nѷ��~|�̝�d,��;�
�I�~K��W���8�ᐕ8s�J+���2���?įζ�<c�\B�"jU��Jaz�y�a��<�B�,$[���6U��	R��Fq��u8��r����0,<���5�Iꘐ��d��t�3�h
�E��b	Y9���>��x�����7���c�z/�R�I�!G���+�F��C	ݻ����j�ώVf�r����g�;B�!�*��&4������#����ۥ?��ne*9IZCvG:�=�k�6�l����e��B����
.8.�9R;j<,u���h@P�mby���P���&�B���Y����I��(�֟�Nk�cqn�&\�?
�	}�-T���Q�P݈L g��"j�ܿ�$�BH;Ě�=rF�p7"9��5�)�o�	��%���d�6�Q��b/���E��6��%krƫ�����o'\��H�	�U�ԯe���l�c�jJ� u�sw����Gh���\Ȍ$�8��&	M�홉�L������~i�s���;��4�
9n����~NR����>��̲�&�q���*���(��,DL��p?t�t���H��^iz7��re�%���x)�0H���?��\���H"u��ܹz��7.��nJ��B�2���b������H���|Nf��3+OH;D�Y�')U��fV+8�%�i�^��I��%�9���-��4mMK��d/�f%�8�\�rWo�2qђv�n�4(�E���k&_��A� �C�g~0G�!"��~[��%{��^=l�İ�D��cq�W,�2��Kk�;X�i�u�ںB>ݵ��Y����@�u���������.�B��NrE�$��/��ܻ���|��x�׻�?�fG�3�%�M����p��lRA?�=��G�X^����;L⫇�B3�
[�T.��ˬU$4�<�*�q�W�G�����e���/9�~�s�V�R�:-���PyAjT��Ӑn��&{���f��{C�Q���5#�_�\dK�o&=��W�������$�:� �>	��/Q�������������w6��/��$�R�A�LFB%%���/WX1���aWH�=Eص%-���@�ʋ<����#PU%)�3d����A$>�E�!���!}+`����d��*!�`�O����5h,�9�5'=�֤�_�o��c3iz�m8���%�2u	U�T�T����(�p$��z��*#��!G	y�P�b�y\�*��A%�%���5ï��jT��6��Z]��4*]{��_IҢ���&oj�:0�`�^YW�-����)���ˤ��D�U7y�ꕗ8��]������AG���~f�->U��SU���]�cO*Ur�7�;��f����J'���_�+=���r1;5��sm�����B�m��l8���nt�px��ժ
9s;+\���m8�_��_��(x�-x���7�:���iR����斏`�J{��9������t����<�͚'G�9���������|�S(�sM�&V��BR�L�#�֬�O��-'>�H�,�Z=k���ol��T6L�]:|����
n��q�R1�^A^,XS��K��l�P�]0�^�߳��`��`e�l7��n5r�����?R��S8�;㯮9�2�&"�}<��(aд t3x|߶�F{uV�t���^ 6tVZ�y�D���vLw�%�jM��*��F���OM�P(F6?���bQy�j�8]��m0Ro�R�6e�١������%��"�q����n=i!Ol����gw�G���ڡ\��o�M���`�4z��u����9�|�8�I}|�ػMOxwڍ�D��J�ካO����1l
�	�Y�[��
_��m�R�:ѲZ�0)p4�oc���~�����)��\k�L9V[.�維m���ŏݴ�&!�3���U���)��,�E�HL;+&�? �$�����.���u��[�,�+i�&[�@�nK]�x�\#�I|p�z�N��L2�ͧ��i7t�e���r�wVQ���j5�▭����'ʬ�7�Ff���R�V��֬(p����Ǟ��i��U[{�)��@-�[�Ѭ�����3Ә��
)�	*���\�� K�&AW�݀,9�;]zD��-����B���̭tKz�Hfܥ���@<���8�m喟��]~����V�}ߥ '7�5��љUӉ�c.��
N�Z����"J���8@O�B�"��E��+�6�sG��:mT�MK
�1o[�HBz�X(�h�M߻Ǒ����yW��bU�t�N�}J?��|z��BZM���۪�G�k���.��>���`�]��Υ;.\·8����sy����?4�V?2+G�{�*�t�2����UM�Y�Ƀ���~��dzl��fņ��hU���@4_�aQP��_�=l��u�
G�l�5�S?���]*���0{5�АLZ�uִ:��w;%q�?͆S�F&��_xa���l��.����&����}t��^oOۮ��J
֩��/����+[�U�˫�5��8 g����F��O�2��3]-ݳ�f�/�Qٿө��v�k�$���^#�r>��[!-�Wr���T����@{���)�v9W�zV�,'�e5�b,e:�s@_s4m�׶?�9�A�=�;���ĺB�2&I;���S�昮�J�M��_V}�7�9��k@��WJ��e�LX����~s�}Q[�-����$�U��|Q?��"/4"F�좿Ё�6I6��-K1�e�]C���](2�M�,~�cY,�lD,~�Ϛ#m K����H�O���|<�iܺ�M�C[��J!/�t�H��\ғ����,���a�OA�wu�:>�k	����^K� V-sF
1rgs�1{o:Da^i��������Jȴ*MK!�ο�y��Q����l^q���-���|�����5>�b�,q���HG���vm��h�l��,���;�k��&{��g�h	i�Fo�����p���z{'U2�h 9ma%�ګ%m[Ҁ��UQʋ�)�2Qk����uB"�Fqp��Yn�MV/�����*�lnE><�r�&Y����p�*�>g�=��IF(A y��pȏ�:pI��Tu�5*�}	��6+�f����idκ�Õ�o��f������@c�ra���UO�!� $��}"ͅn	��N�x���nH3`]C�g	��s!P?�G���#�w'Q?�}Bك+��A�j�� v��9&��6_'�.vPH� �ޯ��	���e���uK�UR7�1Ag��]���0��|{
��@���?����pw��k���w�z���ҁdj���ȡ�M�s�p�g��M/>f"p�����}4�փ5�[[��cfj����颬�,�q?{��=�C�ʘJg�<    ��U(�bU��N�6,�Y�L��u���t}�F�(��c!6mB9	����!����Hb����րj��Y�����m�
�g���;���
iԅ*Ig��֔"|W�5^M�w�`�͔�)�s�P��HmQ�jv��;ݾ�GpC;h,�����G�C+�e3k��:�t6^z=���T!-U�A��2�6O�u'k�jR.�n7���^I����K�n({p�C.�S�f	�ꟃ��^�a�u]��p"�Q�`K��_����7�F�HD�yNi�N\�/z(UUm�h-�(��#E�F����A�U�ŎA-te!o1u�������OѺ��bm�E2k`j �tȃ�<ct�̅MV� ǖ��;6<�HͫtFz�5X|2��iO�XֶI��t���e&�b����N��F��ťBa��Y�TFv[ë8�`��t缭���R����\D_M��ʳd<�u6'�!ˣC�����,:�jf�X�ޖ���9�?��H!��S�C0�����t"��UKǻ�����Y�F������hM�'�I�g8�8�x��T��_������L*��|n]���xi��3�-t��p=]i�5��M�zݿ����t,Nr]�j*�s8���1������������e�w�n3Ly�9z����]@�'��B.��տȪ�%�/.�0gY֘lEܠԞgm��� >�h���
E��4�����sw{GK�m�y�I:��6��L�6O�!�*\x�:�Bμ��?x�����ͻ`Bn�?s�	i���RUm�i.��AL����ُ'EMt^B��!}ۯ��q�(;������������2J�� �=L.sʍ��x�m>o��&����m#�9���
]*k%�L�����Đ0�qcGt�k̖���-$]5��@94��6�-<��e��Y��ڋ�)ږ�|Z�BC���L�x��xo��������1-�=�\k�Ǽ��3�8�t����揳%���>����5�KN|�_SW��p�ƾ-���+!t�o��Ue�xF�7ٹ��.Doif,����	]����F��5��f
9�$�7<l �d"��z��w�;��^�$���7��Cb([8��$]*�s%���˯'��ge��������=L�����D|uO�*3_pyR�y��+`F� B	�*��z���Z411�wF���O`({py�pj�5@���;�"��m��`����ZQ�}��q���᫋!:[Z��n�t�DX�Z�N.�ڪ��_�M��c���A�G�[L���{����):�ׇ�t���h�{x<�3k�3��Ϝ$+^��/_s6%�X�Wt������g\�v������R|Õ�}̖WQjn;y�$����v"�9��j�o�}�cX��v�1�.�=u<�R*���3�:�� A� bj7�=2�11�I���6eAF��)���<Qi��g�P�:��rF����[���D��}Yjۖ�4ӝ�a�ݣ%�����I�!�3@WY���;�ZI�g�<L��E��5�A+�!�ً��
��LUKV�0|pI&w0{���fA{��B��r��s�=k�IA5*�
�d�TCK��KZ`��+w+�]���R�灹��"�lI T(��v�&k��O�E���� s.�ࢺ�����<q]��/�#-��B�`���iq�������`���*�LN���t-��p^f��eNL�yܸ�n��'��C�\�OV�4�<ntz�T�����w�B	+��Z���������Z�%�b���n��e��I?�	&������x����a60Y=�q��l$�T�ٔ�٧�Mp �<�'t�R����4 �A8Gm?�h�X�f�e?Г������9ҟֻ9�p��,�e��N>��x��=���d��}�"�.O�$���P��%oxU������d6�3?��ߛk;�GoO����6<�Ш�D,�?��Q&R$�ӯ�'��l���`nQ�)|wM���$�r��ѝ��B{*���lih:4�'ոt����\�H�_������VgU�M������54�	+�Ы�tL�1��J2\�۰���̵�n�/�٘�	]�``�\��{�<F�ș��Jމ���ڼj����%�M�*��m�w�75in��g��®;�m����jֶ��F{?T?���E�QgX��I���Y������1�!|q'E�"z|�am���i�o�D�6N�1�[)˾½��bZ��M����^�
˅����l���7S5#��~�v���am��	kUE���SxJ���e�\��)����=f�R�)jPC�5칄�G		�n��q�[�{;e�z`����#�@�kSu�џ�*�-Ž� KW�-;���t��{�#��h��saw��ͬ�#-����ͦ�}w��;{��s�$ 
��p�+Mn��3gᮂ1��*�c��X��o��wIm��ck�|����cђ�Py���k�"ѓ���]����ֶ��F���Bպ2�O��|'�S���VƛO�ZaN+�`Z�P/�����$P(�=��tK�)��q�6��k���v�Nf�g�N�.�g�Û�lue2�¹cL�z`E�)�$G4 ��{s��e8�yM�6�1�LuK�TM���5~<����]KZ Is�����8�w=u���˩�-��/]W��35�eh�b���}�9	XW����;�끱*g͉�^3��:|��Z�x�P녴 jt�CH4��6i�m�̒O�*�гi-]k1c�ht�ђ�7g��L�d<�:�����dZ҉��D�D�VTh�N�9 \�[n�ִ��D��|��Bz�XWp��Iw=\�aR2L���`��%�D�KXl&]��q�g1���\��,iA�UK�>Z��$pm��5)�O,M=n�� �[4���������eD�R�`�`�ȵcm��y�Y��n��a��ڵkzʋ&���������-<��]����4��E��֤�Y�B8R5��t���Y��� 2�M��1��e4Ò�������O/���Q6{�����e2��g�viY���<�V��p$2�6o��r�R�h�jU�{��,]�f;�5}4=m	��&*7E4S����Ѓk��si��+X�S��-������,
f�&&��s���UT�i豶'f���	LX����)��urH�Po&���z.�%m+t�l��mV��<���#p%z�"KwBkUF�w��Z%�ٕ(}F
fh�����pd����l#_a1\X�T^� }^������:};v���@�z?���\���]:� v��K����eu���&d�8;\Rk��5���pP��sU��'�s9��>�U�{P{�C�G��%�)�"�A��k9�p��<#�2�ް�,]���H�,�ͳ��^�H�C�<��Vl*HB�G$>�N	=���d2Y
�cUG�R��]|�L���?H���i�}�k�rBǪ�n�֒^�(����n�P�G�߅P�����.Э�Ҋ��^���b�4r��&bi@~����r8�c�����y;�����b����2��rԖ��]�I��=��l����w�!��B��<���0�r������("�M�恼{^,>sdm�:��u�XZ\�؄��o17S�a�J����:+�zJI݇�us���U�Rj�
���7��������cx҇�Y��ܰ���!�#�$�s�|=W�]���v�S�x�ڵiK��48�ĪR��`j���O�^����{�g@]��=�TbN|�Y�q�����a��P�t-�3���TȶH��˭?_�gרc2"F��̾-�,<���]�����*����09�F3��{R��^bN4�c�	fIs 9/����-�
F��N Kz����s���>�Y`o�*�_����
�{��Z�����B�����4�p�p�b���?N� ���P^��YޠG=����}׳��_�F�5:�����H��%:�(�ܑ�(Œr���>��ײV�,�/�=�J.���K    䡼�h���?q%=��j�q%U��p({-����.�E�x�̤�������k1�Y�h�U'� @�7s���.w�V]��3���!�R�}A%K�-�$t3�ļq���r�9�_��r8e��7~��ɂ��\������S�C;���1b�}�8���̊y>v���pꪋ�)�Ŀ�72�'lhQ�;S+LN���r�`������+R#d�Kס��+����yo�!�@�C�sy�H��%�c�=Eґ�]]:��3�@�zI�YG[H/��5~�f��nR�?����N���@� >��AV�Z�����f�S�~���/`[U!lu�Cx4��FB輈`�R���T^Mj`����q��>����z�%�qmV[���FZ�>�V%bW	R{��������Lߧ�:vI���룑}�4�f~�п������DU�`������լ뢄N}�9(&�[|5s��lH��Ԑ"�+P�_ζ�k�a K�"�E�3�S)�d6}�$�S��I�f��7߳�,]��}����N.E�\����_gpsL�Ӓ^ 6d���ˎ%9aA�{_��&v�#m���n�0;�Fpݢ�qr�s���Jr��l$ѩ�3�_(06WO��
�6�� ��]`�F�f׽�Lmwt}��F�a��ף�	����hsV���y��Н��L�o��g՝-|�l�\c�]�w�ʟ��'u�8����%�N��^a�g���r��m��m6ٛ'��5/C�2!�i����Vv��Ά�i�m��DY��2�5���k �m^p��l躛.�����A�ݒ�?��qoH/�+Z�&y;n���B�Zr-S?,i�X�NtR�O�e�ԇ���^i&Q�he-}Z*������.�rZĺ�w"�P�V�{���C�`C���Nб��Yȯ	qq�]�.ߘd�Mt�p*����z�Ͼn�y�g�sL��)����W�*�κ�)��0h��N�A,��v�'�]A�3U�(���w��vh�1��QTx����J�G�%���kj�Уe��H���LMHO���V�!&Zb��mj�����P�hf��n8i�,].G�"K�S'y��>�U�7Oy�еhU�-.
t��v2�[�Wj&�"i�~y�<uU,mΩ��K�#9���{R����JBҺ�֯�{hf�UI"��sE�
X�p��A���itη�(j�,�7?37�6'�&�8�˲���m�';�56)��.F�����Wf
������M��a��$��m����a�)-�9+tׇ�݌�h�fZ4D�%��CW��~���)$$����~�7�S���W�Hr��o^-�B<4Ue�7�5`p�%�GZ����E��X��h��,)_bpaI�U�"ёT��T�g������G�$>7�%P2]�֢��D��RIw�����[�@��*8M������uN�T���� �C�y!����k��[B��h���Ʒ��������ϋ8cG��������h����M�!|��<p����:Gh�M��6Kw��b���Ns`���ᬾM���[	�B�G+�=
��C�	��'�_� ˳I��E��tA!]��HrC'cN�ҡ���[�~����Ue���0�>3>sN��He6�))��m�}��A_�ļ���H]��4cx��q����G���4���Df��>���~��2[�����:Df������o�:��i���O�t�.�����:��N/D<����@ˊ�!1�W�#�_��7��䲄���OY6t'�V��3S��N.�#�<�nw	�t\�b���zA�۞�}�t�_�]��V.����-R��U����E��g�Oa`��}�瘰e�ģl�wʌ���i��� ˨�>Ҁt��t���I�M��Mf��P��ΏW�ǈ����o?����iTQ�/�|�^����@#��
zwJۮlr�wtݬ?��O"�B�G��[��=�o#�x������v
��P�#`��h%��P�L!�H%����P��)*t'�
��3�no��&s~HO�mO$6cˋuƳ4��3��T�)�uV���	C§*��.U��0��?�7n$��P�U�$t}4�r�>+h�}�6h͵q�6U�C��Z���>C4q�ӹ�˷}ጣ="]e��룡|/�9zş��+��T�! �Q����%�@ĔP�?��E�Z�A�"���]i�����	�.LG��y.���e�����_?9�Q@yY��o���-R]u�L���\c}S�T��n�r�d����!���i�s�)J
\����]��j���-��s��,KЄ�]#���Q�CZ�c򨳧ٗJs�����f�[Y�<����\۹m�
��^ VdU!�@[�=��A��$W���Ԓv��7#��=��"��1ΐ��b�O�yN�ox4��'�bV���y=�9�QkȲ�`��{6�*�M�[t�������1O�ZceFҭ��S7y2�D1F0^~��5�&C��i��wj��D:U������i`Y���]�4���d���n�7���22�GV.�G���N��@���[7�%�Lr�ʧ�@������2t�`�Lǲ<TY��� �ior�6K�]���&~���%hs��kN��7��6�Wםk����1���n>�(]{�Ue����i���]�d�Y�X���4~K�+dc�*���mƢ��l��,"��s��&�ʺ;���;#?�ы��7WV� >lIf�Υ��Rο��K N�b{��� �ySb��#t�?������ffN�)���kX����o��@ˊB�2;�uUkg�����'�[���mKz�Xe-T!s�lC�Zv9��68Y����5ht�k8Q^WeN�9^�O�ۀ�і�.eT=�$�}D2 �s�㸍5;�up��ܔ�4��\���3}rr��i���BW�tS�e��.�i ��(�Ѹ����6&��9�{�v���浮x�)��nK<^�����2�W�m�ө�g�$��؈�I�1��N|���ݎt݇�������Q���ɒ3�����8k�'�*�Zs��f��:_8�1PB{���F*~�ư���ś�y�f夥룑\o����W$n;Н�
��QoBZ[m��q�d�v�>���d����7��]�OY�А�V�%wNx�/�>]c���gKs�+}���:4T��'c^ ����pJ��H��]��4���8�K�b�m�j��p%{�T�Q2k���0�ۼh̀��Z����ɼ$�c#�.y �Y��@�����ҡ,]����GG��P'Q$���!ݍ�7�������\��j�� �k�
�jK�&m1l��8�>�`S�i�a��l��]��k]$7���<�Y}i��K�*�	&�JZ�����$P�4*��9�����{�H��޵�
Os��/���̳��(�C��i�3d�r)E)y҆ՑĦ*�iBkֶɬ�)#�㿌�l������V�x�l�\ǝC]���i�,o���d�Z4�r���Iq������x�:nYp��ю$牊`��hE���F.e�4�H����4��#ɯ���|��� �UIFW1b�|�k�.�);o�Eu�f˺�
���dYЖ6	��ѥ�~�3-�*#�e��x�����.ת�v�C���14�n�(�O��X�3Dz�UV45��T�#l9��0v>�W��x��U�Z�J�i�M����6�!0��V,]��ۢ@���x������������*�i���CTy������|��q��7������Ͻ�D�x+���}�J"�dA���Y��L]˔Ϫ&�@\1�ɔ9Ţ�N�aG��4�!�QN�)����=sL������<46#ek
���Ѫ��/���m�/�������
G��e���Н�*�u�55��I0w]ݯw��o���p^��V��Z�>g�p��6��+<f.���6sq#��QX��Ч�BS@�ʴa#�2qԝ���IQ��� ��8�eN*}Lyb[h<�԰-˧��H�NeUU��zƕ�*���Gׇ���8�
L���_ڪ=����%�h���n��!:��D&D�}�mEN����;�!T�H|����'�V�-Ϸib�_�}���F﬉+��lo���8Q    ��~����7N�9e)�~��7���Z:�E�5XR��+Џb�ۏv-���{�6���~s�[s��rj��hue.���c�n�lP�W�=��^�����)�Z����9Ϧ�����a��9Z`�E�����n�7�
vZ�Ŗ� ���vdL�@?��p&����	r���#���M�Z3u ����P��4��=C���[>,�e�!�hRO`����ė2?]ȧ���'��"jҨ��p���X���d�kE�@�-�����J���C�GSe[�$l[#����w![��4sIg�Z4U����\t����?jY�&���B吓��#6�E���Z���S�;�h0�p�����z�Gˁ����Z�����\+.��Y�H��9C�
4��诘�����9�$8��b�a��nٰ��d�!g��Ȗ��TG3����dj4O���<y�����(�_i�_�d��A��h��0�Y�e��j6����8 �O����'-?��%���3�1{�nh�p�_��7m��4��y�I�>Z^��T��~�^3�t�xI��]E��J/K��5y�&�w����.ץ@D�-\��MW��]��92;���r0-v0�i�N��i��\'4m��uK'������jg~�s����֩LW�4�f-+le����\r����6m�j�}���{+:�yzF&UJ�R����$"���i����J>�/���+��o�����>�������_�ļ���îVzYF,{�	df)���� Ay��"� ��	;��d��֐BMj>me�U?L^��e%���͌��g�`��m`D�Ԣ0�{(D�&,.`���&��:y7<Wّ��b)�X�����焴�X�QZ��hg��L�O���uC�c��%-t߇6$n�����EJKrW�1v��KS,[����m���s?\ឌ�,���V�O�:��T�x:k��{�b��2P�x#þ_��K�}�����sɾ���p�7	���'Z?�����m���|f�gѢ�q��W᳧���2�z$�^x��ˬ�[�&fk��V�lS��7��^�o�P��a��㖓Y��h����(��z{�?5UG;;H隡+h�d��T�9:z�γ�{q�J9��V�,���ِ����)$d�Z���x�`�[tf��-cF�h􆮏�#�i���șx�]�8��Z3��CS	[��surM]�A�_�z�lm��4�ⱓ���A�N��/`:�� ;u~q2DM"�f������H0�s�N/3D,��0G�6��|H��A�2BV��V����6\�\S�?d
�����\q��
k�c�]A�]����l�^:��?+�w���
O8M�7~ʶ�9T�l6�K:CAVj��\!<}��"����A��)�BW�в$K�qNK��+�q�?EG������K��)��j�x7��~��a��H;�I��$�h���6�8�|�UK�r�u8ߛ)��U0v~%������N��ʪR��-CTl�����׶��7M����	/����_yg�
��� -
�x�!n{($�K~��ɦ^$KAKC�G����, ��x�6�m3�V$_�Nh�A$_�M] U�� ��hZحT�]�1œ��B�G��k�$Y4P[�Y^m����6�Q:�l�nlQ�=�YQ��Y�$��g�ǝ~��C�%��n�O�`���n�^�8�0�]��%C�viq����}X6�u���s�g=;4�hl�Pg�ϱ�^���)���\�x���9d��C����q\���9zŭ"�}��T��҉�a6�
��?y�C�|م��V4m���'���?�	$W���s�뻈z���]H[�"��8DO��9���B��-YN���F=6]|���d[ӝc?�'C�p)�G���b_z^.N��>��g��F�m�c�"
��x�Pm]�¦�����QK�ú��HheI�:u zzx��@Άt �y"�5��1�L���l��b�d���k��r�n���F�#?h�� K{�*b�i�=����֦_�;�K����9Bׇ+��D�7���6�)��H>\��8q����5�8�9�����Y��pN�$���V��lőH�S��Z�Cjc��.l��Y�EӘVc�LCv,��PIB�Nw�Q��Y3�`m�y�՛��9O���W/z�x.�3̠�b=��,c��&Q�'\xv�ų\�y���2o�)�h?����h�����e֍s�5P�
��Ř��d�����<8� �U��!��\U�p���Q�Z:��ͼ�DVXg��������UUv�5b��V�7�+&��8�(� ӹθEY��.�a\�c��jڕK���k�z����/9N��K�Xg[�H�;"8����0@���]��E�8J�=֪�t"�y񲹬��{��8s����n�N�d��x�������Ob��?8�4T���j��o�[5$@rmLc�>�=ݐ'*������'�UZ����ǩy&U/j�Ng���c�2���/�Ue?��0��U^"h�ޖ��|��0є;
1�0_��>"�Z���'��m#�#��LBg�1�Z�h�t_<[�e�)���n��z�k�4 �{��%����_��^m�.�B_,�^6,b���4'��7f�/r�_/�o�R��93�d#^�߮�"�n�q�{J���]M�}�.��	����a��������s}�ҝ�*�� ��.m����АKa~��[e!H�m)��iѾ��=!!���i�̒�(���p�ݖ݉���p�Y�TH��W]ľʩ� �����:}o�c7]��.�g�y���N�����{�6�!>\��،����!�M<U`�_�h��h#�Ho���N�af��+y]o,]�h�����i+@��g^9�@�~;����Qդt4HBz��QSs�4��F^�Ȗ}h,�9���_tE7�/��"�s�?���ىq��6օ}^k�O�:�(;k�=7��Nݳ葁�XkD�6������eR!c=8nQ�P�T*��ú����`����ʗ����&��SO��~m�i�(�?5~C��6�<[l%=@�	����FE��xU�3��B�G#���+�[�.~ܮnK��=P5_I�k�؀YR��.�x�5�D�m�kꤶ����jK�G+rh�y),l���1i��ƳQ���Q��'�ڵ�X2a�P/��F�U�{R�$����E�-��0^uF���	*����hc�} _$c�_iV�m���kB��7�H�� %0y���DP����+"-�	�D�M��y�H���K����.CgM�o�D.�yqy.�d���ϱ?�n5ِe����,�Y�����G�8��H�m�r����f�-
Sg�ES�hZ&�*�	H��l��6��e�y�Hͧ,9�6z��̹��T�8���Uz?�9�ϳ�%�+�n����#�HL�������x3mUF��E�L'���bݘ��eS�+��fe��'�Da^V��>?S�ӈ���|��g�T�We2�^����_+7�ư��E�}�ҝS��剋�=-�-��k����։��S�2tǱs8V�������>"�öQ�m�v05��.��5��u����h�`>���~nm�����3���1��V�I��j
lg*��U�\�t=&�"�B,�%bS4�8\"N�w_�LB�z�<OK�!�[r�zi���13,v�eEKlǰlIR��ttU&�����vv�����-]#*v���l�Ra��A��p�!�2�F��v&�y�&�����v��T2�r�`��t���uK�|��$M0`����A�;܎��}�	����iݲtޓ
z�{I�����P�w��}�}��JB=I��_U�*���P�|y�<X>��#�B��,u8�h3đ�x�g��m����e�cRx��@�J�q��x�칩�{�l�\H�������Um�-�>7u���~1�	v�}<�bJ�� �Gb���Y�MY�_k�&tM�c�y+�ȏ~롞v�GWd�_���l��r}�5+U�+��Q��0�'*F�9��	O׉��.�'��x����sR-k�t�    �Ŋ?���F�A�TUd���G�Bu�K8�	���=8�M�R˖魂�R=�b>o�(�z����1Ʋ���Wv���.?,PN맘�ZKTeZӎ�2��ySHl�� Zkk%��.t}4zgQ�/9Mܣr�0�%�h+cK�:��"��s��ۀ3E�����=X�2�����v����*�thW��6�^:ˀa-�I�HTY�%I�w���#�ik���61UY��RO��(��f,�Dg;D32�<d=��8]d���c.�M�c�}�������CF�TM[u?r����э5�q��d~�����@�
zL�&�f�ǃ.�T�SG�+�����FinD���D��1����|V�$�-b��$�E�y�K���H����$ex�����%��'��p�$���x�����J
�x%�v^1�̽�V#��c�XQj��F({�Q��d������+��{� e�(���`mƍ2�#N�T�t�m,2S|W���zO]��K'�>^�Ѭ�P��Ȳ{�~�V�>\.�nc@(��OCWК�`U��%7�}�`����u�`�XjM��}�;�!��<�mL��k�߱��$� &��l��HVT�q�:O��
��6�.�ÔrN��8&�.3�u�?��KP��ɐoU��8j�J�������}�������pTeA��kJc�Nhm��i�Ig�DI����^LUƹ�;n�'�E=��1�2�ʸR�Xr��"�� ��&,M|~�(ꌷ�P��� �`Ee+zTlw��	3�'�-��c�vI��{�s���a/J2�hns�Գi|���_$BzV�.G�I*�L�y��� ��Ibh�S%��H�
���O��[#󈷂�Bw�V���~���g��k*��:�Y����yB6k�T�u�5E�51H��p�c�d�@�t[�Ж�s8��d�oS��ׄ��C#���INɡ$
d����-&
���櫓¤}��n�,Y3/# �k��Sߚ�C8��dT���TE����J�+>�x���p-2�j�=��$l2�ʍ�r�&��d
�	��jUJ3�l��H)M|��C�Jiv�b��=���xO�WJC�n[�E�CD�xϑGoZI�l]��qgܡ'��S٨^��3]�5n<ϤҤ��yav^�㓊^����Y��a�+ȵ�X&U�ȿ,������锘�w�y����\�7Hb���i
{�5A��-�Rےm^�-tZ^c���%���I�� ��r��3�Qo�~������s��	��Y� �s�ήc�_�]`�Ӏ�iNL�G$})#U�F=7+�@'����	a�Ѻh�����TyV��d��S�4{;�;t�]�c8?x�"�<O�c�u�0�7b�il,#�=20�%S�6-w���>�7�P2뙶յN��h��q�fz8�y4�4�IN��1O�����M�4���S��u��˶G��Y���p�X�	�lp��t� c���-���n��kl �R��z%'sv���#���G�2k�B�D�@�C���]��KM���=�/�XX94�v�a���tMG- `�I��XcR8�c��K����t}4�J5�U'���oS��J�ۄ��V�Y��Mχ]>�ۨ�5ǤX˿E�NH[DL�&�Y�l� <|���^N������k���BW�0�t�Pc+u�����u��{�V�R�תШ���g��+���۽����٧�qw��K[6���y]�$�x�COEG��r���OjҬ���m�@����A�q���?��i��V���P3�`u����E����b��\Y��M���H�,A����[يk8�<�wrR�H_�9����~Y�$��!�5̷NU!<���2��c�7:�x�Yo�벉���(Lד���Ŕ�i2H���6�����@2�*�a�u;�k�����΍��2Od�Ve�ç�&�M�H5���A'r8A�B�5"��F0��V���*a�g��@�Ȃ��]���ToZ���?´w�,{�tS����P�Ř�/��ݳ����U0V�"KW��}�C^��M�	]ţuA~w��x"h�����_��']����7{z����� �,+��zI�q���PC���vh�'��ҁ�2��Չ���;m�"ě�����:�S���Gv����)��QF۰
s�d&��MUH"yC<q֞�׆�¥��l�����>�j�l;������'����X�Oi�VL�q��ʪ��Ԥ�_��{�O����jg*[���&��ϗQ,$��0�Ц�v�E�=���$q�tO�m��h�)T�j�2=�6��u�T*/+s]1���1��1'�(��K�d��a���t�%e7I��),��U+���0U˗�-�|�C�u��v���(;�����U�������*�O(z<�'6�(���΄98�cư�^;ߐ����U�2]M)x�����4�!�GG���|JS:���lFw��#O�rA���#Еf�-3�,i�Xg$L��uޘ��8�r(�\���6ؕe~5����H�Յ~Ɀ��xL<$җ-�}��Y�3D�>0$�K��Q�:8�}(��ۿƪ[��+hEEb)�)�th����=��Dܖ���d��n��G�[��K�����������#cÄ�:
4].�Md��oP���Lގ<��G�:^nCj�,�����[�	]��E��q�N���F�o��q��)tZ�
{��\�Q�/ofx�1E�����]��:����
�`��;y�������4,]���tl4ۙm�m���\�j����ɾY�>ZU��/N�c#�A#7߿�V	r3��r�y�u,]�F�k8yN7��ڷ�G��A�G�5�o!ۼ��	�;�a(�"��m`�:���)_W�&�룡�F��Fz�F��.�cC���(z]�����*�߬���#,d�xeW��<��$����ە�t}4��IDZZ��2�tX�	��J�i�L���kъ���~^�E�X�ҕo[��%W6G�կ�y���Y��7\%��7�䤿R�e���˕�kU��<��ͮ���dŚ3)���T]U�E��y-K��R���yr�P�.܀���q!�2;�yT�m[X�:4ݔ�3%!l���b���~D&�*��/��@����>UA��n��G����Z��&��&h���uh-���I�g:�_\	��.b%T�={��E���ާal��Vv|�;�6��+�h������IY)b�f�.дF��$<��h���=l-]�V�-��Ǯ� ն�r��t��O�����	��*Sj�t��0������v��Wcn��rIFq�����+������X�Ma���� �!L����1	��_7iy$.�#i��d	�&${︥k��)�*ʺ(K���������Q�L���kj;?>b�GH��
Y$d��]��q�Y�3���i�m,~R����47�读a.�#N�yE�m�r��:sԤ^*ť�*'�:���k~(�~��x����+Kk��tZ��%\
*E�0�}W��.\@b�K��M	��0�<�HXĵ��>�n����q��c�M��j���:Cw��Ȫ,P_b��v�la��@�������A�1�:��$��Ւ��H���zm�x�����B��ҵ��� l�LyZ��r�{�OALl[h�Lע5tJ�IW�ŕʒ�;Ɛt���Wr�7Rf��}�;*����ҏ����7yS�1I,�j�c��Lg���<�0���Q�8C���Ð���<�8�f��d�HU6s�[��*�-��MU�zkÌ������6�/��~�H&�m-��o^�t�U��4�a���E$;%oڶ��;r�&{�A��#����\X��2�V���\`������m��^BO�V2��H4:�wt��u��|1�إ��/�9��_�LKY���!A��v)G���4�7�c���#)c:"��kw��z鯗)�A����f�އ3��"S��7�άz}��3���*�Y�^��O�_���[?+�l{ݢuƟp��0�e��'����qo1��������}�RUݖ�����A?�_�K*��Xy���L׿e�&@LX"��Q    ����=1%����yt���|6�|�-��f����[Mon������}�P�'�?s��rp˞0���h��d	Ĭ	U�jD'C~�YA�v͓�+BM�xv�"#<a}�<]�M�0�cKdݡO3]_D!����o�*�>�^��X�s�ʅ�����0�5eMM��携���mS��]������Ǟ���t<x�ĕK��_�>AF�DR�\e�uh�Q����)��u����,ms�nc�y�=�����Qp��\7u����x���=v���{�'́�rV�|N���.��AM�<�n�*2|�O���ø%��j=�Gi!���R�Ӭ爖� �Y����0}����O��c'zwF��S��3�[aع�dAv^(®�A���?���9\���]�,v8�s�B���*��I3澞����)���B���0��4a�h�Zx���u�L_�5��&q���-	{2��X����΃	����]���?r�BO�������y9x����c���Ad���Z��RAE:qK|3�a�E8��uh繣,]�D����S�ƺ��mr��	b�� �Н��6:V9:3w�����L�E9�siD[74�룡}SM�{m���.�ژ{�̮^����SW5jH��e�����-s�ط�H:	>���,l��i(�2еu
�R���m�,�	M�\/X�Y�̵��uР����O�2t}4��G��w4d�$����C�s�$6�vKסՅ.�slY�|1����sg��Ґ���#-��h�Й��ffU�ӝ#��v!��P�''-]A��T#pMen�Cv��~�k?� .�D�}�Hl�QZ@|h�m��L����?mL�s�j|�������h�,��5�C��+��������i�k_L�?�T͝��E��[U'��G;�c���Ԁy���72�.�4J�d(�B��i�HA�Q�Ea�c�@#-����յ�G}�6F�I'�0��3��G��F��Ԋ`C:���
�e�s��r��ɞC-���$6��3-�~ �lC��zK#y�i���'�P9����ޢ���+�ᩰ�Y�9�Kп5F������o6�gs�;���Z	}��k�]�4)Ot)�<��aG;I�����|}2T��{u��!J:c��@̏:w)=�6���z�]"*3�'�q�a��*�pK�	��M�����ֱ���*�}y�1���"�G��1�㴰H�5�b�M�W@��%�Z�6&7ƹ��q!��tUR�oY�>�F��������pE�,D�~Z�c���R�JqH<x)Y�q�d��^#�%\�p�q��o�ɨ b_���sA�"��%���;Ie8�d�g�oͷ�.���O��#��!����秙���dޅ��>!?�oxV����;��������j8���+�{7a���0)Z�����)���NKQ۾@HI���M������Tw
����J�"��]b�9�),��X��#Mk�f�����"���Nk[W�!�,݅J���5M���!#��;י�Ԭ�lMq�}] ��1�cwD.Cz���ͳ,}83��ݿ�
�$PȺQUL�	k�K�J�P��ٍ'\��d;���^��eM�+��hi� �؟NR�(a�ˈK7;]M�M��K����=�3�܍&��N���+e��T6v�'�v3�.Zp6�����W�>�r��7[/v5�|�����g�8ա �"�g��ȷYkRf���Н����sͳ]X59L����53�/�qfF�V�0G��𖡮�δ��9*����4h�[m��Z4R217`J���xg��ߤv��,6c1�#������"�u<�Ky&ǂ-_f��C��n��k�E��I8(�<��˗Z{3�3��#8��t�42*[�Q�hő�.�͓7ZN�9���f���}���������Z�˪5WKr���~E�+���gAۜ�}�ޖ=M!L��`�@me�2���'�TevēK9h��I ��7��6Us�@���Q&�� ��E�C\���bͽx�m#��jI[D6I��̊������On��ۖ�V�,o8�IhBHb�U-1(3�#`<��&^�zR�1��f0m���o�Y��k#B��:�%|?U�0h1�feblsV�x�:�c?eTM9�'�&Y��ZZx&����ꪭ�*E��H��|v�/E	��g��/w��.�j��z�Y
��uYeU��'���Xk� ��{һ5�ꄚ��l`j��1t}4�o��m�I!7��[�����9Z�B��"k���LBY������%��J��+�.��Ns��e�H-$�o��J[�b��5_V�:4��Wh�m}��p�g�%�)�9f��1� �+2bZ���� ��\���6,�h���g�	y�)�cx	'�O�+�Đ�"ø��Y�)��ݒ9�L��2\��ؖ���i2���A��E�a5���t���#����?������+�5���VU�U�ˉ�qEE�^���TsϹ���+O2.Q6�1�֝
�&��L��W(���f�e�p�t�'�dt��f��rm4�WB;��$�֐��?��ӯt�b8Ng��"a7����D�j����4Z=�����7;]�'�~;�pnq[Հ{��ҝК
Y"�/�T�Eȍӈ�i��]㜍���P,}��P�J��Wwz�p�v<��B�ѧ��JR踮>Y���,]�.:��pM+W�B��&�*\W�"�Lw��4�	i��&�Ah�<5�V�۳���C#����h��8��&��җ�P�5w^���*�-\	�.��⾬Ir���9�+�Www>M03�Y�ʥ�̬��p�bR�e�VX����}��B�H�2VK��|ո ���CoeUp�O��o����;�_����m������i����%�`S��(�|g��h�~c�(�{�3cV���ҋ�%�����O}{�-pke2O����kV��H,�5p�c���P�c���7Q���Ll?L�o��5O4Gdo�@}�s��P��y^*�LT=߱YV����Ȭ6һ�0h��|乷�f�S���J�-t��S�𘼍qh��=�'N���`��uDEbYXGYۂ8̹�[�&b��q�9�{5���Y��/I��&���OX-4�E�,j��r�aBJ��>}ь����H����m?��ڵe���0ndP��J�@����s�`�{���gu�R��H+���"���8�$A��Q�w���B�t�̚�u�e�`.�ۥ�+���E�>yq�u�-^�%�P�k�Ud0�>�����¿侬���N�S�ʫ2z���O,�9��a!�)L����t����Ӑuл�.҂<�=Yo9V�ʵ��:��j܉��n�2֭j���7�׻w���w[��s�-Ը�#6(�.�;f��eC�ò�P�����YK�j�Ʋ�VC���^�eS�/���w�ܾI���*T�|ї-%9����HɹD$�����	%Nq�d����
8��P{�����Kp��-*�5!��qt�ؼZ�56�������J ����h���i=����m�ǒ`u�]�96��eF�iB_�T�����?�:V}�k���-ϨA~^��a�g���l
�C@.ƌ�;�qgZI�PJ�@h�l��I+��p�Eu!
�|/�]����p�1X�22�Έ�#ɑ��D=�u-Q�ŭ����J"LY:%��*�0�y k�0#�a�V�_��́.w�xUC+����s��i#"|EM:z��cطO^��v�Vz�Q;G=JD� .s�ƪ�x�Mzt����I�/P�k�e��.�*ɱ�െvE,3������v�"��2˭l�����Sv�M���a�R���5�E8&:���4�W0��=nOp$4���������p{���VJbE>���h%�촏e�ޠށ���x~����
��$�K|����c��EvWָ�-_�)�@�*i��u������ʧ��A���AWUBL�@8䚕���|{�Z�+�_ʴ�w��ʾ��B��]Z�&�Wz�Qb��*�Q/K�L��x� ���4%���xv���Y�O<�+���m    m�Z�t1�Ǥu
>Ը�˄�^��.��h,c��+t�=��UҪ*�2R4�S�9�<�n�H�2���z�\��K��}�t��1��V� $�0�����U�e��q8w(�<�TOsHV>��z��7롴�A�Q� �Ё_������Jo���ߢw��K�\��VG7`�q�}����Gr�>�1�Kk�M��a��
5�k�T,;x��%��1��5�lA�X��=��V�1�Mg�G�|�e���,��d�A����YAǴ�S���|{�M�[�ӝ��<��~99�\(_��Af�p�07>G�/=�6Kfy�f�3o�`���/<XN>^���C��)g�P뚓��%}��ה���ߔ�?�}�;�t=��y�5�&~4���i�� ϑL�hjϦ��( ?���iʘ�(P���E5�k�Ve]&t-}<ΗF�y�z��J�=鬤w"-'o-Gp��p^����b�OQ���I��]�U�S2����^	�p��V�n�=���G���<��dOQ�����H=Fְf�R���_�p�������� pn�`'�@� �Tz	�T���*Z2��}wj��K�\� �s�i��K.���E�	S�����N
�� �c1)��<��Yb�q��)���2IJ��!��*>Ėܝ}��oV0@>��v��~49���Y*;�*%��,mH��.]�Xb���ݝѓfwM
�l�e����N~A�2n��FuP�li Qσ���T��A��q�4tp ���G2��h���n2�w$������G$ꃴ��n`wO���Z	�(�y^T����V�$�A�n.!qT���+]��J��ɬ3�&�t?�08�`g�ە9Z�r\%�쌲�Qf����m���B�C��@0%g�7xܩ��H
@���	���\f�d�%�vVR������	�>��:'��~G�ly� ���յykw#\�l�[�I��d�;���ec�(v���ז���G�McSb�f��q�������G�e+Zƽ��\S������WIK��� BE-勓i�oa�f2P d��x\W�a��֭)����5W`uKs��*i9ɢ�1��LD��#��]�*	VL����XZh� ��VT1,xi���`�y܉4E��*u��4�=��C��fp��ғ��Iy��\��'�������yW�375�y��C��V��.C���'2��W�J�C� g^tX�e-¤�Z�n?�gS��j�@���Q85C}E��=zm��cxmlmJy�-$��]��;���ۡ�Bv�Ѫ�� ���Y6�l���q]i��Ɉ��X�e���?l�{aY��.5�m�%?I�~�$Qc#�H�ًK҂��	s6��G�5Ȣ����~8��݀�`�Wb�@{��sY��|�r�a�Y[����q��3H���I�g�ہ�n��t�g���H�O��I�wuR�TH�^Lގ�����1LE�(�DHy�ɲ�����_CH���@�L~�ׁ�p���܁0z� H	��2�	ߧ�}��
�`S�_e�� ��t�ʸ�s��-�{2帮��*
� �~��`ʃ�t��?��v�qGiY�� ��]ʍ��ޅ�go��J"�/+�m���;me�V����Z�-@1b�6��L{(zak�cT����M��%�sݓY{A���JѨ�P��jX���b���ڜQ��G��C�[@A� �5���(�d�&R�$lp�cE�!��q{�w���No�i��px��!3�DbWd��J�j/zi������m����!��e$ꡥW��$�8#j&8�ŗ�h%S�����v�]�]�%�`���QL�������x2K��j�*��`��wΊ�pm��+]���9;-%QC�D�l\ʄ�U�8���7 |jD0i	�C�U��z��h��}C����/σ����#�^�ȝ.����U g�v��綼Ԑ(�ߝ�~PIN"U��h�h-�nI�z�,liB73�f�hX�=y�l'3��O{ӌr�ߤ�_��6@KH�F2��+���\[}.*�"2��זC�)�;��N�r�֞#q2`�,��zt��a�#{Vhom�@xl�C*�hG�UZ��Ɔ�S3��J<R,�A��(.b�[],ZQ_�rJ0ꮘC1�џ�E���NI2+B-���Ͽ1���a�ܒѫ/��Q����"�v	m��XY�P���%�j�^�oSo�%��#J�Z��V�����L�R�Z�D-r�*[�vБ�Z(
��QkBթPj�d�%Vڟ#j:=a��iO�����cf�B%���e���N�=������'y�c�5���t|;�~�|DҸ>~��1�b�T*t��T�5����������7Z)X+�B������7��}�����
R~��FV��$:�I�b�tOܜj����$1���`<5;�F���O��pD�R���U\x�0�[�r���;%Q��ZiS�d{�[�V�����O�kg~�N�T������M�k���V�d��Ϡ[�\��1��_�y�Us��#nj]*�qx�Z�3��)��rj�����l�fӀE�SH3���ۣ��_���0�9�10�Ov��+y���ݺ��a�Y�Ho�L%���X3}	5\�2z��O���7t�.��W�*��~�rW9���nRz[�w�Qx�P}��=��Vr�T0����S�wg��%� F15{���1��e2���	di�LL&_w�=/I�g�e2M�Y+�U"����$���<���U�ji�σ��
v����q71%_��x�L�����⤶<�\�:��6ue����uGtoWI����2�o�+f���5��1uW@�wL��D( B7��/�2��$�e3�����x�eR�uY��x�����#���^��!h�� �N�qy�sGTߒ�nIpV ��}#|Fw��#��� �~N���r5��V�Z�uQ�;	��_P�,k���t��E���K���S�е�F�:��J���_�)(.��K{�<Գ�f൹u^0c�_;��	�?o4�{�|۞���w��Է�-=����cM�����;��Ei3�;��.)��,;��,RV�3���􉵃����+�L��c���m�o�D�I�#M�Q��T=��������Io4?4�F[5ڤ�Q��'L���_66��\�:N����KF�hGPB(,Ub�P.���#��Fz"(�(X�Tej��0y��WW"�99���z�~Bp�,/ͽ�D�u�԰RV���A+���`{Ȃ��'�ձ�\���l�2�SK����ʭn�)締 	�����k��w`�C�'}�c���춼?4�2��6}�+��"D��/(���tU��J�����5��N�����k���Xu�|;5��iM�Ze�<�켢L�KW+4Q3%�-��E=��dm��5��R�U3�ˢ��v0��T�)c?Gͩ���)������.Ϋ]9ђ��g�9�Y�B��9�n2Y{i}�߾�Le���ٙ��)e�ۈ�:qI������{ɼ����ɇ�C;���EQ�d���~�����0} �b�aʡ]����X�<J��Q�w�X�������v��1��S�Lp�����s���R���mW�y!L�� =��e�6M�-�3��Űu�fQE�͚8�!�O�#5�+)�J�5_���G2HL���y�D�VE\��ߠY{�LĞ3�?��M��R��+�h�Ńm@[nJx�sޞ߁���2!�֠��sq��9δ���c��Yr�5���e�T�I��.`�a�bߐ}�!s���$�� �u|#�ki����Ղƥ�0��,>�s���NR�^�;��Qߪ��>��Y�$;4Y���hR���[� 4W\�(0� 3�lw��
��{!B'{F-���tҍ�=����B�B!��
��*9��y��:��:���q�0�{��o�G�T���G:/n3z���Q�_/�\Z��Q'IR�������M��[5r�3#�]#��b��㽕�u�*�X{S��v������@T��B9瞁���D�r�a�I=��;Љ�(A�J��Ԗ�:z>%��5�/#Oy�e��]#ܪ_ѥ�{�Hx��    <h��~U=�O�������m�J��0��Z%�L�F)��Ȏ�v�P^���?��~sG�s}����0��md����cP�З$+���ϑ���~��|Y�ᢳjj�g���%2ʹ�\��p'H�~�������s ��L�R��	v�l�8g�e"���'��ٽ%�Ю�"Of-rfQ�h�W���4.�C덃�)��KQ�d��7��6�o?�|O$����q\g�&e�ѷ؍ ��Gg�g��+���h�Z��J/s�si��ѐ�T��$ʵ)����
�ߥ1=}UFVٴ�k���7���vos�����]�e���&��GgM���b~d�ש#P��0E��Nb�O�6�q?z/��\����8,���l$W���f��Z�F��K I�'8N��S�������哏	��a�^�~L������1���Ol����NGm	����3����uM�ʗH����%��)"g�g��KV��i%4�Q}{!s@���>�9;���`ʟ��|���1��4w]ƃ��V�MȻ��Q��W�w7���R���I�^-�/r����tٹ�r�RMR9�\  Sǰ�qQ�k�Ξ��ֺD�,�$�S�{>zܤ���#!|���v�p!.��k���
�k�i�f^�G��o���d���H��x���3�6s�
��WC��fS����~��%��&&�P�
Lܣ�Do��{v\�xn�N�T�
Ll�b�����to�i���F��yV��(�r�]��3������P=�e)�,��Uyc�k�Gv���UF�Q�V�U�h���@5��q�B��Ӌ��v�?�8|5�{w6���G�BO���t�W���H�n��u��nڻ�)a�ѫ�+��%�H�U�x�^�_BxY3�*���\��M�}��ڲ�z{�&��ڝ�����5�(��y��Q�Y&�N�Bt(·��/,��� ����Q��cGX�p7�2���jhX�y�W�ƀ�H��F	.Y��\�vX�� Ӵ+; �6�tF��N�~#�xm�V�7|nb���+�73���#ف����eZ{�۫�#jx�詈c���bi4ynbMok@d��L���Y9$zk���狀�әOa>��F��]�}��7�D���-��akK�ߩ�҅�9���	�'` }Z p'��N�ɪ�Y��<�Wk����_:y����GĆ��Ԣ�9(��{C�~�x�"4y�e5���7��^tG��w��?�Y�g�+aK*����I�@��T@^�SKL��v���R���y{���jWge)���*A����h� ���̍B)q�bf|gݘe�L����a}�u�iȞ\�zh��EM�-�h_�N�g��k���������V���o:.8��he��r�x�mJ	W3�u��]{|0d�R��s�Kk�,r~@�tv�YG;�:�>���Ȍ�+��V����J�J�"KQ8O�q���=h�㖀�~��r���H/e���r��Y�e9�,��G��v^�d�:�|^��c_"Y/<TC�@�_�.S��K��k-��p<�"�GT(����9p��\��ҵW��j�̠
�y>5�Ȁ���]�)z7|�7?����y�菩����$A+f��"+�x����8�%C��>^�0u��F�54g.��_�8��l�5�ǡ�=���V�Y f��#`tb@i.����*f�K
��o#ɠ�j1��Xi&W�s>�DMx"��\k��ݳ��2��!]�n�$�t��t\��G�e(����N�8#,ÚZ��"�6Ѹ|i]I8����K�w�V�9v�d�e<�|�s��A5���eJ�ʅT��q��6å�z��G�����🉬J��oRj�i��5�����R����]H 9u���B�9~�T��ޙ��: ����gwE}Ţ�D2.�%C�d��N�挲&(2��K����YQ �Ÿjb6�IgҐ�%�94�X�\�@�,�uI�^��٫^��!��A[�gjE<w���5���Ȟن��}wI\���+�cL�Z	����a#���~=P^z�H0�-�Q	8u7��w��	|2��zsu�fȸ��k��"���]�b�(RЀ��I2Ip=i��Vo`���Ʋ8���0J�8�����m�����>iqN�M�}��R�p���ic��XpCB{�E*�����<>�x�y�+�������4�m�/�f����7qYgy*<hh��(�$A�A�y��ѓ���ؔ�E���� �ҷ��U֘Dih^_� �7̳��ԍZ��䨮�
/�h+HK姮�ϔ2�)�4��/�!	����8�#��OXϩ��2�Yg�^OM��J�G�z�
��	�4��F���$wV�+��O�3j��A*ي�*Y�S�ߴ���$��>�0aisE��>�����f���ݥ�,�r7�JK��B���1!$>'rl-�Z��.��L�maP�,s$ΐ�F�r��D�뼤�6�k7XM�����\ٖV�^cڷ_K%gR��l��c�r��l}S\D��@�F<[-������i&�$�2�؜\?�r��7C�E���N/��FN罉�%�c���m�Y5�V	��|�"��كS`&���,�f6m�Y=�9C��S�S�$r&+�~C�)�t��8���@�8`rdW^RǴĥ�oowƐrDxf�9� �j�V���AD�"z�o2�BG��ö|W�Σt���>���q�4+���5TQu�1��dx�Y�����^PJ�"��I��oH��<O�3ferA.� �GdHD��a�Y
HiU�	�4 g@��(�m%��:�Y�tU����	���q3%�_�8��F�4M��Heʥ�^5B��l{�!>�2H�0��w���߾�gyv�SY���И���?#�E�������Sҹ,�aT��G$����v׎�\����쩨�ב%!�r$��,�8(cH���λ4O�w��#�<@�,�~���Ą�FK��J�UJʺ��)��|`�rǑ�����6��-���\oF��I�EB��
_��m�6���a.��תx�%�F3CO$�i��?��d��Q|j�m�f$f%�y�����P+y`�Gl��\e3�o���^j9u��d��l[�l��c[�i�P?czʴ\E�|/��^�
H�o�;l�GIU�ҥ�R�7Q��2��Mw-s 4�RY��iK�����W^�7�|��Ŀo�"b���[��oM;�
/��O��gj�9��^�S�U�i�F��(�O��!���1t����2iIfhw�Sf�I�sЬ���s�W�˪l?�,v�۞T>�i�� 5����(�p�oae�+�\�1a ���ds������5v�F���ǝ��c���A��~C�)(Д�Yx�8=�D=�]#o=��:�"]g�^C �;����`��Gy[j\�b�K� �q�O�ӎA0� ��3�R���,����V \��}W+J4�H́��>_-`򔃄��w����H�x��_���=)�*<���~�q]iU�J��x������p��m�.�rnۡ=��VfY� t*�!q`����),H�Q:Q��d؁ ����#��@א�$y�!jxieg�#�X 6�N�;�&D���9?���{i`5���)���Kĵz\�$�t���\d1�x��VaP��*UH�rz�ɓ�iZ�^y�q}�ǰ�<)i�Tt�G=X�>h���U'>+=�L4�e�nK��I��5c��AH��u��r���+\7�(�-h��4�hU�0� b�;d$\� �.���비�$�Ǯ=��R�K�v
�W�`����N ��x�#�-x�5r>�0�g�*�|���G7a3P�(7�Ѭo;)q2O�V�Ƙ���l
a��Tڑ���E 	oɽ�o���$N_>�k'�Ӑ(���{�#�a�����~F�̨Kq�;�EZmH��ҽ(�2*����������:)j�ˢZ�N������vy^g\��
]��B_��5����%�8���p�uC;�ߒ�����c����'!y��e��ͫ
��]�2��v�]S"��6�@�������    �l�Q�h�B��$O1�OH�~Y�� N�oWR�煌�Z6<kZ�<]�Q�l���NWs{��W�C�m�8��c�Z�,�IB����ј��UgO���Ю�x��e�NA���si`9$(�җ1C�%,J~7%�L�ҿ^Q�S4���*zݼG�Nl�7���%�0�-��SMd'�F_	�ک�[<�wjڵRQ]�E��>Po(F�)�/1~�aK�"�4�W9���l��Z`j���|�|����E	�$�����,n� ������[X�������"MS$��������%ω3���ژ2\�9[���	�p3�3�=���g���w�{T3^4xU�0�Y�m.���z\%�,�?�����kTu *��~��WO��w_Z����}�c�0�ȼ����JGyFvBfC�a!�9~�T�R��åY�����?!�I�
|J�3��d�%o��,��ܺ���_��d.X.��4�4�=ޚ�m�D����~\�p��Ve�F�U��]�h����1z�;�%�š�� �˲@`X[�/[u���8�a��'���Q'!Y���)��*�F �e �.Y���TNr��	n���:td>qjė��HO��H��cot����S:�U�Mv�x2ݑsq�b�������(qI��y�?#Da��؟-�$��T#�4�N�vh.o7���%ʣǑ<��S�U�Մ�p�3�RRUƖ#;;)�$��I��p�^�,���e�;�Frܤ�ڕH3�%+��ޠQ�-gӋ#<��	��k�I9��t->]L/�3��qC�c%~�m�l�=s�� {��3�fo��� ����?�?`|��C_K>��a�����Y	k?Z��Q�4��?��Z�^�qS�K��u�ۯ{���]"��$ϫ�^����eG^�jr_�"Hi�hg�i�]��D�Ai�����]�6Ͷ��F	����~E�]�3�	����+���>�|9�F0j����>2�?29(��e��mGp��)��T�@��q�9�u���܂�ݝ.mO��N�H|V����wp�0�Y�|������#1��g�� 3=ߚLO�˃z��Y�:dM�c��ѹ�p7�z�K��y6p�B:��k����,Az�I�Ф�h�*_i��X�wMV�Wĥh̷���;[��d��5�d��8gt��w�bJ����y��O�kj:�b:U�8��<����v�*�K�[ϲ6&�l�� �k����9i~��J�f\������2��)��8���"Ɂ}[fuE{R:��asĻ�Tn��-~]=���Uh�x���	���݁^w��q���g׈%R�["nB���!�xr�4EkP�x��1���9!_va�f�&ޘ��MK�:Cͳ�3����?�˾Gv���8��j�'i�k{�o�7]�L��R������&@� ��?��h�t�����q��dÃ��!�ɐ��E����I���M���z�F
P54���)������"ϱ�8r�n]��99ׇ+�g!0����퓫%��c����U����;E]	��k%S�A��o�6XTdL����.!�,ita���j�����&���JQ�YUmX'z������C����+?G�3
�6C��$��4��|�
)]�;����HL�[���.�;�,�^���4��(�ɐ��`=��}^W%w>wg+�y��8��j-���!P��J��M
�& ly�T��__[dYR	cw��14{x��(W�O�:���%
���݃��uQ*dѽT�2C�߹A�õ�w�ݱ9nj]�r�C0z\-M�Qȷ��_H�Η͍ax��i]�	�ݛ7��c�~��u�`1�,�D|�ѮB�$� K�K�Y��DBX?(]�R�ER�R[N�8L�@xL��Zx�o��YY�#ڇ���ag�Y���^���"	5
�6�+G)��Bg�>��:M.�*�~7���� �Fc����[3u1�@����H2Q�.�Y�rԚ��ZK]�q�i��i�M�}$�������b����"��*?�����h��}�N�Tc�$=^	�Ҷ����R/e4��\��������4L' ���_��?��?�������?����a���E͙�:&Y�F�}xFb[�ʤ=rLy�N�>��C][_�r=��"O.Q� ��=6"K�~�y�7���ms�[�,��;6���IZ\�Ϣ	��B�D#s�2÷k�y���_Z8��9��?��<P��F�hY�	^z`���$����X��՜���F���.F<�UU�9Fn4�MC��`^E�2�A��߻��[���5H���վ�P���Z��E}�ETԺa`��Z4��lpe����q�F	��"�J2�ͧ�^}nTȼ�v+��HK3v}n9R���L�w�W�G ; �R�����2��vx�;��?O~�Mr#N�g�����B�U�!K��g����&��K�цO�w�G�h�,A8�;�����˝�7����^#Se�e׭JS�O�[�} ,��(���[���u��"lD��儿�W9ޘt��傽|E��5�E�"���d"Os�pƖ�S�>��o� v�;!S;a���3D��;�+�<����Uj �Ӄ\��Ƞu�d�N�����.����$v�w���gu\e�yʓG.�gS�t���S�\h���Z�R1�Q�Җ����t���(��&9ߖzHLQx(X���H�|>�nU��ү�z$���hlGk�R/��7�ƴ�<�</t��0�|s�� ����� �L�(2�d7�<�f���<�2G^*�]��8�6x�;�4l�iE&�*��4�p`:�Z;��j�Z�t� ��He�3ټ�ʿ;E��xh����R��u/U���Hxm������F����m�k�Q�2������W)��Bu��Z�]ƞ֦�[#�s�ͪ����7��Y����$Ab���yi��.�^5�˴�����[�;�|�*�d+"��5�k�q�V)�v�搂�g~��Ks����w%0���UJst�s�+��7U�(��s`?��3��W�ilL��@��A�)}ײ�ز�R52|�Pe˪��&7Ğ?����i! �3�t,��`���р���#��
 �yT�5~��4j��=h�.ݞ2�����Up�����T�#�v3���ޡIz��ת|'t��sג�#X���৷y��\����Kg�����V������\X��,���d����pw %#���6MR��S�6�9;�8M���oͯ���?7��9:�{q�]<޲������O��������L�<by'`�
��Jr�8�I�gߕ\�".].��f]�\Q�.jc�c3{zy������dGS̭]oL���*E[��6�_�+_�@?� �ri] �wl[���\|�w����럺c*�_Jy#����:P"������!�O��&u�?ݢ���� {P��L�4V-�[��� #=]�ƤO�1ll�ޥ9�tK^�xM�,k�s�%�L�0�e!�v���1s�2*p�q��r����8�ժ$�I�����a9*|ji$G��s*C��N۔���ҳ]R0{�K���l�q��	,?�~�ỡi(F�^x�����˥�h�y5-�[{���7�K{Q��9=����2�q]���`K���7S���y���MُӉ�l^V��h�.���a�Q�]��Єp��WCt�<L��8{fO_��)�l!x� d�S��M?oQ��,����g���@��pE�_a.��R5�d%~��/��:������L b4���a'����d��N�J�DoЬD��K��]s��Ѽ4�N�Q^�_�`��2]Yƚ⪒������*����ȿ7wgI\U����-yU��j�L�5�@�&	�������w�kY�z-�Qˢ��/!�j*e&+�ݢ�)��6-M���N�N��VƟ�����qU�>���J���wGW7�4�)?����O�����8]2cL?C-��Cj	�Ҟ��X��LԪ�����<.bP.K�'A��70��u���xض�$�?����ïH~�J��2�M���Y_���	��-A-A�n\|DƼRLLI���$ y������+�U��    GD|-����L���)ڷ��n��0�d 5�ǈ�I��Z�*�%�Z �Ej�Ur׶@����+p��^t� L�*��^���g���<˒LpE�Р�Qsh���J�uU��_�X ��vo���8ӵ���+^����!�S��Kc�t��\:�y�����$��[�9��>6<4���?g�)�P%H:W=�<�`D�����BIV@�*A$�L�$�Y�{F]��/~i��[�jא�q��n�Sg�:TM�qa���y�����0k�@^҉�}>}}��\b�uPWB Ɩ\R'�N�F�uMy����ʺJrT)�zU�ٿ�d�>�_=�	�������9j�io7���+<Y�Q�9�\H�χ��O�.D���@�W�-o*Z�d�~>s]3멺� �nJ����%�@G轳gX�1ϓ8%��E����J0�Bߚ˔Q��%�Y���Y�A���\��'54�;�1�Y�^x�y1:���&yN�/�������B%���v�DY5�<-t;����;��Z��ͱ9?�%c�Ie�;~Ro@`��~�]:ݣ�f}:?�����,�m�K	�CZ�;ڴx�ߢ����*t�i�\Oϩ���sU�e^I���`ky�e$�4e"��D���>�G蔙���!�q���uYd��R(EW�H��3�	pՆ)�g�'UK��4R7�g`s��rYf�\&�g>��~:��Q4�ج�M%\���<ݑٔ'�Oi��X��+V��}	��Yzd��@9�(˚�O(܎�爻T��
��^�U2=���n�q�'3�?	����!�^#{�'d�*Z�x[���̨�[m�~�QKW6����
+�+וֹ��*fxχ2Ze���;!g���`��q�?R<���Z�zH�ܚ�?�zàN���O��$OW���oL���4��?�Ȋ_�3]��ݢ�F�7ʎ�^��ST���Ū�b6uM�V��k�q:����妮`��lj��bmj-۫3�^�E�e�v�{U�*e�/����<�o������.aB�<��{$$�e%��ռj.�!���	@Ɨ�j��9 喧���������_����?�#�x������q��������ӿ��������ɵnh%]�$�/:��_���z%:�@�m�'.�Ѱ���*k�ƵtE��]�e��Il7������Lʁ6��)��o���zdWg��雒f#gvl�?�,� N9��`�Fj������5����t^�|-�1����Ø Q�;:З���������eFB����5��G7�YP��\(�i�ҚGVQɒ�s�/]��ek�ҪI��Y���]�$�S�虾�/q�~����$��v��J���8�q��f�n��P����eR05�Z��^h�h�'gкǹ�}VƋ*�W�ۃ>	.?�Y��I5���ť4����a��&�Ȼ�<����R�1�H �Ɋ %��]�׳&aep��J�gS�$�8����.7�6�H	���n�$]�hUɾ���ԕ�8�xS���`5�._�Em9�;�V%w�C�\�H��:���䛛�����3�n��Ql.|�,*��L��6�Wl*��ā���\`� �Φ��$7���;���נ�E���vi�F,�H�FL�<I��u"�x2$ȶ�1~C���qv��Xl,XL���d�ؠe�����O�������*+���"(�j��V�=Bb�������F���������T�FkPBW|��O��?9�d��t%�:qxN�����|����!;�`���\n��l2�ޡ����S��;�؎��D��D��fߝ&��e@C�Tm�e!��@������x6�X��������3�z��t��Lw��h�.~v����X�8ajU�b�>~U{��o��w������aRY�S^�m��F]�U��O�T�ak���h�$�ȣ���\IBU��������9�8�pztW�Bf��5��*rk��S��g��s��C`#o��)��a�xcv�����v<Yk��e��v�3V��p��4����?'����?�*l
��=���g�-W��A��	E��#EDV�Wd�Ȩ����&G�S�h�D^$乃=�ލ�@��GF��ݾ9>���$Rj�� �zdW��֟�,����ƲUI���;B��58� � 7�h��"�d/^�Ԍ2�P*��fd`ָg�Y�� ����Oxso�O�'d0�+������)�k��6�Q!wȾǹ�h.���t���l�b^_�(�׸6/���7f���6��F�k���G#" fb�>�yJ*TݕI����c�Xa�� �+�'s`	������CV�[� 	�.�����1|��C{��dI�襻s�g�K�|Z�O��I\h?�'�8[^�/g�z�E�̏e̄*pa���N^�~�3��X!.�n����T�h��a�>��8���PU���T`[r���J�o8)���FeRPP��[�Y?	L4^�9,�z���ݚ�}
+&W-#�#_�{��'F��x"4��ww���d�߸AO�@���|
�i����}Rż~ɕK��f	p�uR�GT�j{�mn�g�������uZAw���HcP����WSd����K$��-`�������R;keg�S�S�%�g���%�W�gE�7q�GT	��4?�x�o�i	C�g�u:�P@�/f�zYBsyHW"4��T$~��î�7۵
<`���j�}�k)���wh���Da����U9e�V���PCOf��9���I�� P��?�H�"�Ѡ�5���8B�\�����vA�N�����:����a�Eu��E��_�iK�^�$O�*�rƠ~nXq^�Y���V=Y�C���=�V#׵ �W������P�/?h��lt���g�v�S��!#��%�L�`��s�H(��/�v��L�F��(S�S3����H�Tt�ĺ������^�yk�{�H�Q��}�'zl���$H"�����w`
�>s�@�DO�=����Do�;I��P'�Jc�������N(�Vi�j��jQn�~ɤKK]++�c�R��]F��Q��zc�L��מ�(�����W	��w6����O������`�����5]·K��8+!f�F����U!��ܺ"�H�����A]�>P�4&2�����13��qڷ�<Y?NZ���'��L�+��|I�q+"�<G�#D����mn��u~3k���i#��gJ�����b	�y�۵�RW�Ķ����g��]��=�����F�E�E��,!�0��r ���� ��SU>�]� Qd��ͱW?[�����,���7�[s`�%@$��i_5�#_���њ8_�V����m��p1��-�����g�W�ľW��z�Ij"�H;R!�]9��ֻ̊�;5��?�^�V��L�#�ՃT��Ɋ2u��AȚlᢘxUW�d��#!�)u�����Z¹���$��r�pw�tb��g$��4Ot&]����,D���ՕELH�b�n��p���8����EU��	U�ըf��`^.��E�Q��N�@;�-�u�x)��&/���[���u9J+��|��⣇��zC�|\@_�-�v}��<r�VW;'�<{5�o�n��g���:���5=#�yR���E����+�tQ���O�&^qH��P�:^�'���Q<�0��o�IhUlG������y[���������n�
|��N��̔b���}1�Nr�!S6��C�$|U������w�s�L'>L�B�q�e�Pu����j�'�i=�έ�����j��_foq�C��{Mlp���zN"�fLj�(#����II~.���-][_o/�a8>QJNO�5~s@ms�/�A6U�Zm����{���'�$����ER&9�SGZt�������KJ��b+ǣ�)6�2TX�g�� �TW:��� ����9��,z;�b�m����*��!i^�ό�u%9""�"����G��K`G)�����\���B`)o�fQ�qʩ*Y�!��}��>\��*١�o�n^ZiPy�8�Sp�2P0    �|y=�J�5^;��\�0M��0�E�¶$��0���ڬ	;�Ӎ8QyZ�4	�w�

�_�CN��+YR��D���]�������ƚ�Аh�Ց�^���=]HU]�h���ۙv���L)�M��Q� �/Ͽ��z=��/������}�2$ک�����w����z_�(c�$��?t��w�q�]lt��|}i�x9����������N!�,Gl���<4ϣ�~�4-�ǯ.��g�a͍I�>&������E��#%|^8�o��.�����j_���f~D)P���B����nH�6Q������r��'޶�t�n4�ߛ;��.o�����$nJ�.ݞa���6%���$$���1�����̚�l�c2�������Wʌ?��ĤԳ
ۗf[z;�K���(�zm�GN�[�P�\v�l��1��Z^�A�emJ�D,U6�;��š̗��.^f#o��N� =5�$�*ЊGW����co=7n{=l]/
�Jxj|�WzH��r�U��#�Kr��GϷ3t�R�+ґN��u�Io#��:� "[��uu�#�}p$�o>�D��v��nZ���C�FB� iװ�p�$=6L��Qqc��AOvP��[�90�������Ȋ+�1{9�k�$�I��@���ڜ��y�����	6y\a��W�;��mxeh�-�Q��M�9��	�r0�d��	�����@�r�J�e�`�ǭ%�*��3$�?�?<�efbg�7)��B�y�^�����nZ��ԙ>��h$f��=�2���TN�?�i��b�Sk�se���,�*�!u�_X�L��.3w�¿��M$���1W����ȶ��o��x���*�]G�kԾ#:tfL͏[KF�{�Zk��e��N*K�{�Et�g�o�y�6�ړ)��OGTBf;k"$q��{{��Ch�Yy���lJѳy^��	7E7�˹��N�����</-���n������_�C��}P�fÛ"����y�$tF����h��AK�z�����zl�`���w�v@�9p�75���A�壭wU=��x�:*	یa%ar3����[��ө;��*3��;�����/k>+�z#�(�\<��R�������-x�JP�U��
�A6�o6}��HU���B��_l"��l�ƟqSc����DqV�X�`�����ȁ�Kh�a�X� IE&�]Z$n��O�Ή��m��%���0�l{�b/*~`��<�O{x�<�b�Mg3�>��>����f@7���[T�ݰ�{���y���
�?�� ���#'٠n�ʫȡU|�Z�6�H`�Z�S�mV�ixA9��<e�A5��V��a=�g� ���4�Q�@�u�FI�SO;S�e�����zt�P�Ҵý��#��X�Ckda�؆�aʋVY��"Q]�����G���8K�2/���54�0�^��I8u�&8�%A�� Ch��n�����n��jCW�nÀ��NP��e��v�-�,��X%%᭹�mjDBh��;�U]WY��3��j��u���6Uò�0)��\��>��t�9���]PaZ�����덬`i7�M�*~� -��t��"�E�W��K�9����+%J�<G�4�衕�'LnY��Jx���PD���Ԃ�8��V0�����	u�u+yZhFl��������Bh�*��~,�s,�p��n6|�Ҍt���-�i������۩4�{�tCܒ�Nz��ݠ@��W��Yc2�k����%��?�����؉�7�u=��5�ĝO���ZY�=:�M;}��B�%*�TS����EZ�l�*���<Jա���xM��6d�/�b�^l�) �"	��n��>OK�� \<�񾽸��0�S���=�KVJ'Qz ��9X�·掊#��0m:��d~�̺� �S�(�ԙz��*D��)�K\32�b�H�Aũū�{�.��{Iߺ����1�}@�&@���nI�	�0B[KJx��X��+ϔi������y�DTe^)�E�(�4��n*�=�kr��y�Eto��͎�J
�\���p�
��ܲ��M��ڏ�\eڸ^�]	��3Yh��`W�mdq��d������qǏpM=�8�
,ۇ�9 �v�P�oO@�xy��WzX���f��}��VC�oKΫ,1�K��Im�(I����STY\[K��9�qN���Q������%��G���h�Qf����B����v�(`,_@��mv=��['�%�}���:�����qCA^3 �O>G�Ϛ)���ʂ��]����HDUv��妛�Y`s>u�ܚ��0+�G��>[vn�̳��ϛ��PG�7��EZU%���jW�D@0����AѬ��l�3���ƚ�U�`�^K�W�ʢ�s�Rr�������V
9�C'�3���@�U^��;�+d�%? ��衝s�'�݀�D�V�tFFۮ��������s��4�P9�P��D;��zo��\�5+�\3�����tq�S�͏f��ɭ���Vs٣�/[�V�˼���(1����B�I^��3�d&�L@�jq�{���|W��1�5#�\�����-ϻ�|�T.�W�*����$�St��Ր�K��R�V�e����Nqn���M2S����wL��_$w�l�%��x�R/���@2�+��R��E;x�Y�`pN.l
;וʅ.�Ԯ�5�{ԯE)�<+��F�):w��/��>J�����d`�Ю�)��M�	�y�4�P�K�/������������i�J?s��
kt�b��R�k��>���W�����5W��K�lvg�a��l�����5�6
�;'���B����S0�z��h��sL�+k���I��HjL݀{"���@�{�@�H�%gF8ɍ���i�K�4��$�c蔢S�j(�J2� �xj{N� �uh��:.�3�I��(B�J���[@��:���4*�g�Jz�R���WY��*�{~^�o�U�=�X��v�� �g�k�������T�[n�*�����V
ظM�)b�7"����̉n����y�'ř�$���u;����t��%C��s����ϙ�:z$��,�����u`$�U]��\�P��u�LaeWz"Vb[���`8��$���T�y�)W��'Ok����&�̾0Û%ByՂ s_x����KIjZsQ������@3��q���o�7c�k���r@ҐR(�om�D����sY�h����}]Ttgf��2ZB��>t�3��_R(~ fޗc��q��*�wq�sG�r�
������>Ǚֶr>7�枑��qR�Hc��eO~�]��z-;^$�d���d=*�e:��㉴%_�?��L�]�P@��Ye��g��zc;Y����4��cL71��N����O2I�]Uy�ǻ��c�p��
}�(r����٨����ܤ���Fq�?�� ��=�%�,  7��7Z)��ͨa�M8�x6�Sm$����Q�?*�[����@B�y�ǁ/4v�t~�+Ru��A��J���PUj��eAg��ᝈub<�ʹ��帓�\:��Q�����:=�r�V�ӯ�thŸgd�rW�H�͠�F\$� �9���H�q����%"(R�Bb ����7���[������3�����g�:FB��У}Y��|W�����^0�z���)I���Օ �����"f�N�L1�ý��ϤQ�����%MK��6���TL���'�E(��X$��\�*�l��Gs�E���=��/��q�t���z�J�|�U���@0fO%h�9�,��
|x�fS��H�`�+Û��z�.��U�؄�d��B�~��q[{�
/��$E�O<	���$�JEޢc����	�Sˀ����f@SjWC��zB���n��Gs �ϡ{ )��4��ٙ�#Jv�� "���_�{�H�Eb�8���(�5p�t#敔��ir��ȡ���u^�%�{�:2ʟ�w���'B}�ua3<��O�-S�Z��YV�.��@zx$x��&��4|6�J�J���T4�K���쒡m`QU���Tp��;���tdWٲ�F�%X��?��VFw^�0   ҩ5|)jC�y�����Ʋ��x ��A�����m�yo�H{�Jc�L�ͧ�U���/��4_uL��暹�Ɔ�@��_-A���r��v��U����۟��߷,[Y�q�i����^F\��Dt�qe�&zΉ��Y�&!V��6zβJ�mr<��S_)�!k�=�X ��v��}��g�.�-$f�iI.E����Γ��6��mf�bc�����n��>�7�7�]��1�"b�M��e]"V(22{�4���W=�a�S� �SI�qi���G���G�n@� ~����0/o�JMVC�4�H��걷Z�g���/�՘𔯰��Wn� �;&�dB���sC�����uV�i�SS�/7�~��p�lP�q�4H��3Fz\kiK���3p|�'��Ӓwc�P��3��/�kHu�~S�ۼ��Hʼ��H�4.'��8��[��}x�B	��Yq2f�[�yv�	&��_9��C�g��"=n?�RI�-B��ō*��-�Z�[xdk:y�-������8��Y��jw퐂nt��itn.I�������0�]s8�=��<�e��d�	��  �U���#yoR#�y;�Q�E���X�.o��y����O�gԊ\��/PZ˼Ŧ����T�+k?5�L�VH�-7���P=�N��\�"4����ls��q(�w��_~�?:�3c�u�,a^��a�G�RDڒP�Y,��AO�6���P��K3��6�#�R3:��ޖ�X�Ŭ�xy� l��A���|��.��WW)���.�QUU���z{4���\	� Ɋ�~��8���z�ɜ~�d�o�qV3j}>|E��{ʜU�i�.z����'r쿧HBZ�(��w�-FFf�7��k�pf�h��d�s���~�J3K�9��<�Sź����tח�}�if�[?��2.��~�W3c�&����EQ �o�=�[X��.#s=�g�v���Tɋ*�)f�l����_!�3C��<?����~2$e�9�iN�¬���S�j;7^Ҝ�i�v�M�	��H86�/�o)*c$$�"��JӺ��y��q���X�^CK�m=x�J�M؛�>ZB�'5ـ�q`� Ԝz��'�&<����]��/�!*�����y*F�l�e�7r�ed�f�ctF=A�qݬ���l׶}KI]�����0�*a�4�+V��ώ�'x��WF�	�F�r)�4^f����v��~����{ZT^z�\Ue��گm�Y�ݬB�Ɖ��N����3��E������1;�թ�8�*Z�������eD�/�|�����w���p�[�.��8(�mM�:�T�6ӑy�C��|u��7V��5��S��A厾f�DQ˱�p#�ڏ��ks��u�9f��ٽ4�&��l�i�W��ɚ����Y�Clk�n0yE,JK��~\�0�����Ȩ�����q4�͝U��^9{�yhwq�}C��"q�%~l�;�k��k�-�������
{߯�SW��;=X���`�3�ȿ�}��I�����j}7x�1}p�4��:��^���|B以7qo��V��)�X�gu��o�a�JP�l�
kA<lJC�qY����)��[�N��ˀ)%d1p�2�Dh껭�c�4��s���r\-��
��їE��Z(�M�m�?9%��S�f>���|D^0���V^���[�����dӣ�޲��{��.�qO:��=wZX����k����"�U��2O��"XTkgP�>!��/�����Nf�г� ��"N=H�����XHk�ݔZ!p=�d>���<s�2��G����E�o��>�V���
2م���$�"Iۋ�6������|2m>�L��0����+�s����t�.Y:bO^]�>�.M煶׆�2V�=|Qp��ڨ�e���-�0S��.���x�G��c"z�����(�M�W�>��T-�
4�f��ٛ(���&�kfo�f)�8���_�_gtc��V���l����zD����w�sL��1d
��"�-v��h�O��بyAO\xx��p�{�/u��d#��r[�{wj�>��qNG�C�v��{�F��Ǫ���?:���/�]�"S��CCG���?"Q|�Z`��(�.���J	et���LF6&��ȱ{�8&T]PX54�1�7Z7OM����q��E�I�+P	V�T1�X�=�d6n����}F���{�X�4������L��.��}��_�m��d��H彩�z��(n���.�;�.=f��$��ոJZ]�I�@�(��V��J���%�A���Lz�R�YwPIT����Td�	��˿:Z_Z�Sw۔�����&u�L��|����[BE�����&l�o��-L���G��|;��˃{�}m���)�V�ٲ�,˱�;X��A�%�܈B!���'I��u����_֠�xI�-����Gu/��-�_H��s�F)�uP�ؼ���v�ni��կ�ƥlR�$�@��ͫ�s���0�q�$T[�'��g� �^�I�@d�����X �_T2���	A�����mh��T���kR2���B
��=�w�B̿�)ے��sL7bR�
�rgj�(+!��>����G��2��*ڹ���Sh8�é%�7�el��(��y7�uǫq�G�$o����eDg�[3�'�o�D�J�i�%d�$��R[1h(��sV�i��Ŵ�)t��
s�A�qje���fRv��M=�L.���B�����,��9���57�f� 	�]�s�H���f.���=d���SYk�t��s�I��0�U���ڳ����5��φ�4������콦U���i���EE[���.#�?�v[%Yø��3]���3�� f9+(쯟
�tR#�:_W���5�(��QQ��]Ӛ�z�� Q�\�]�#��D���b���A/+��=�?Ua]�h澈U�${�zK3��>aj?X~f:�����u�e5��R]./?����4��~�q�q��̄���ע0x���E������x�;�y~���5�sY�cͼ��KG�J���k�n�6*���v.�X=�w���z�����O�&<dZ�����/̢�S-a��d��Wt?[Q�Mlt{��k��夏�0�"�i_��vd��d7<@E��]{_x���(S����_�ڝ"�~g1�uV�ɀ
�Ҍ;� b��d�}r�]?T7���,�ɲ$�Var9v�p�	�,/�7R�c�L�0]��K�hX�e�ڕXѾ�ɛ;=�]ۣ+�F�a�dѷ�v1�����᥎I)�eie\�9��"Ms��i�������eI��&P]&j\�\ZخA3��v����Q������w�� �;]2      .	   b  x�m�;O�@���_�!��<fyl�k����X�p�� $�������q��`���TS�w#�EƄS+U�2И�.�6�����8WF�ј,�;wǤ�Zr�y^"�m��j���pgop��R�J9z�.]�<�z��L�B�,���4X�V��hJh�F��u���r��ń�B�,ae5}x/����v�H�$ų���������������t�m'@譥�])��7-����\y� �/����_��b+��;�>y��~�j�ci�Ø9z�¥[Slj��37ő1R��"�@��W���k7P+&�ĺ��������Z�l��ow�L��P-*K�>|RͩX��0�
�z xv�      2	   �  x���MN�0���)|�*�I�(ZQ�l��T�9	\�0c�Ң�����y�y�$	� ���@���Z k8{���A�������K\������K� >�%abܒ	v���k�	�}8a�n�i,	C�N�{����ԉʜ�܉*���'){~�+�s�����P%yov��ϧ�7���,�>ؕ� ?�*r���U�gWD��K�z��l)-l���x`�TJ7XW�/I�wj������&���Y� �+��Tl�xJi���A���Jnf��L�43i�d?���Xu;j�Pw�{V�o��k�{՛��H�\�l�g�\b��i>2mE��JhE��yV���j?0V�GԸ����r�PVf��Wxĩ���F��s�����'f�w�4Prʷ�s���l�b�7t��
��NIj7�=q�y�Gc����y��T#o��ͱc��R{�����u��      6	   �   x�E�I�0E��)|Dʼ4��,�8�mE�?a����S��.�XՐ3e\�i�3��$�"U��R;�:��<.�����M
�Wׯ�����+т�����;g���F8��h\�.�+�m��>��{ �m,[      4	   �  x�E�[vE)D����RŹ����ʓ�#���QU`�7N���p[{�23|�u1�ε�̹�D-�g�q����{�_Vd��s,sւ̴�{�E���>^���g���/�s{�(39l���s�=X>�<e޺k�icLߺk�{���Q�������]�X��3��\1�Ǒ9��n�����'ogi��iJ�Z�F,]���;6�ص��E�t�+�Z�A�.�1b���ϵ�ךMR��ͱ޶IM�;����섏�^�m��ύ}��s1��4ڞU2��_�=�x;־}�ݰ��Η1U��l�>�R�=z�:$��G>��u1 s���X�ZX}��1����Z�F)��	{�(��%��`6��5*�������r|yJ���!�ٴ��:�09,Z
琧��8i_}ؘ2q����;ܶ	۠)��H%���;߮���ؔ��}Uj��G�C��M
@���d�>��K��DS�I������O�
�c�QE�m/,�s�	�������{��� ��{�?�Ō*�Z��N�t�36��� ��3��û2��Vg��Z@��쥹�������C%mlT��T�;�E���fz*5[^A �R�|��hg 3"�.8XE�T�-�����z�|˽�
.ڒ<����-���YVB'j��r�_�n�׋�h- m*D�mA�d��|
5�p]X�
��f�R)��k�o�m$��H,Bז\G/{U�i*j)��
I����x,��nC��dIw��	�r�G��C�ƘQ�|��WQ7�b="�_mB�x�-WzK�Z�} ߮�A��K?�ֵ>+)eƟ(ܮ$Z��T���,'��r�#%M�i�������w���&�d���v������(o��-���8���pFl��MWތ��cg|`��Ԍ��x���M��͚�Tu�U1�]a^LS������/ UJ��JH!�+a�T>.#>/w(���H!ă,����X_���P� )�� +����7�1����<,ҁ�W��6�a��H�W�;U��^��0���霟(��P>��/]��?ԍ��.�$�=��� ��	�A$��
�ֻs�y� ~�ا���5�WV�����I?��}n����$�n�*$�	�4�����9j$w2���L2��B�j�W�N��	v�H�<�@C'�I�'KL�I-��h��p\����LQ� ��ؔ}���_�#�d
�גV���n,.Luc�3A�.YC������PuH�}C6��C8꿅�F��8V�k��y	@��_�4k�U`$�oY
�߰o���P����x�:�(zJ�Aؼ��v]���)Oj�:��dt�� ��p�v �J?9/��ߚ<g[Y�0�b���j��[�!�)���� ��|�y�Q�Q
@��O?`,�j5�kl�
����Ǩ�����N�W�f�W�zi�R���?��O�")��j?�\
|4�O&��56�I�T��l���^&ےY�͵)U�Y�?5f� �Ņ��AR\��k�Ɵ@�kI[[^e�(�3���T.Y�}�C"VN�p�.�B����V�ΐX��bMl>yMJ�Y/�_tg�]���+���5H�9jkr�g��Z� �m�3/�6A:E*s��
���.ޑ��&��Vf�ҭ�5�������]#T����ZYf������CRH�1I��C�ǉx.�R��K�^��ֻ�>��]^�򔋣p�N���&$��/yQ����c3�������;��z!�^�5�+�8u(�H=�C=YO���C����u��Şgm��{�����������$G�:N��X��$�ˣ��W���������o�5=[�?������w¢�T�4��/L8i��g��
�O���f.a�^�2K��g�;N&5��g�=���]rU/��V_��=�㻦��^;:jh��{���&�G��˕��7*���N!	
�LV�SE7ڦ� ޶ى8�;C{�c��}�_?���������#      <	      x������ � �      3	   r   x���9�  ky/0�ƣ��J6�ՠv���X�`3�L2�!�xiQ��i^9�u����#����+"�k�	|4�*nVZ%���&L=��;�另�͖!v��_W      ;	   ?   x�E��  že�]����i�����0�@�Zv��\ax����q��
���HD$�      B	     x�}�K�,'E�U{)H�7��~+��[���EJ�,G�.8\I�(|B�P|�X��W_����a������'���b�����mT��1����m)�3v��O�� ����]��2���qsgpY2��S��<��8�:G\������:���� �rp;�s���e1�\:��v��\��eF@��e�K���x�1\mO� �y-��(�u��?ˡ[`/9W�ϊ̩W�18�7六CH�i H+��+�g�c�2�opl��J�
2N�ø��n\�JÈős4���8'��e��v�r��i�2q!�E^��M/��\@X�e�_�m½lz���ܻ��;�p.�
J�`j��"�a}�c��LW
�\B-Y�!4����Fn{�P�.#G�+Ϩv����%_y/΋۟���W>�O�'���2]=����=,�\Wm?�|q����_�
�:_=����<5[*��cW�o������h'�7���͹Ԯc��ڣ�T�=*imD�R]F�=3
-*����a��J+�#��E��b��П�\'z�$+.��b����Z��pqu���)P-�T}�	S��ڜ�|]�w���0�ۧ�7�ݐ�]m�ldc�lr�|K��	�5�-�o	}¦o#��f6Z�x=���#�����ѥ M����4��[v�2|ˇ�Y}�ь$7����M-��bF��м�Y+ڰ#�~7�Sf���V� k�V�Ȋ
����&��*
�f���/6�a�FSk��:��'����r�1�[���0YiB/��+��+Z��6;1�5�/�7/�>�?]Fq��Ψۧ��ܶ�5ڹT�r`����
|}�T�Q�C�Z�Z=�
�t�L�B�	S5�ͱ����F�fF6ybFZ��[K|�p�4������2���ĩ^�|!ϗ�����S��'z��n�:_X�a����g��շ�&f���X�`��ʹ��QW�fن	�<DɱmL�
yb���w`�u������o[�0m^��ն
ܰ�t�Lm���j�Iö�~��b#I�z����J��      ,	   @  x�]P;n�@�קxU:�?`�ҿ�H6�"���f�Z��4is��bYK�hfF��Ϟ�*��sQvDJÅl�|�Q����I�!�"'`E� ��Є��yLr���΄��(Y�i�,,�K�6̵��ϊh>�c�uGq�E$4%a�����V�9!���e��`-�&$��I�xRrTQ1�M_0��7��Xs{�a�3�5�;��R-��a_�Z"tݷ\���E�U͍���{���8�k��������O��(��f�2K1���[|�H����l��̢{iH�a%j���B������.�ܘ��m�ױ�8�}�      7	      x������ � �      +	   2   x�343�4561�420430�4�v�quv���st��2D�6D������ Ӛ�      D	      x������ � �      F	      x������ � �      N	      x������ � �      J	      x������ � �      U	      x������ � �      H	      x������ � �      L	      x������ � �      R	   �  x���Mo�8��ԯ|wlI��{K�-�1�M��X�m�9I�p�%���d7*	 )?s2&7�ɒ�L�Y�KJ~,��[�˓b���z���b���;���22L?stj�\��\d�� G�Ҡz9Y$��ͩh�.���AH�e+����&���)3V�o�(.�5"4X(A@�IVPj./]V��!�x��Z�d��;�r�>W��Y��q��(��ccN[����=Ւ��;�����P&0 V԰Ql/��_�뵔�d�� U�t����ٟA�<w���13d.��jO���-�� 
�Y +oF��q�MZ�F3�ތDO���܆�g���!���D �ֈ��[�	4��	@X�֛q�|L���������_��#}ᙃ�R]��_��H���^SY�������ǛdI>%���f߾�_�F��V�j>l� b,m���˲K�w
͜�gA7Aa�g���(H���q�ٳqlH��3��՜�fE���Ե�z�;%X���|�L318�ڒ�|�bL��L[T��������)�v��WL�����oi�a�U;���*�Þ�wn0����@>b�	�U�F.�Z�>������Y�M-��C@]��wG��~�������4Ζ �I����Um_k8��%�۔_flT�b1p�ݖ6�P�WOT���ީ㶶��
�ܽ���S���q����A��඗�������z�h���u���LZvٵ�0��nQ=tI1G�?���h籂\ӳɝ's�d"��f^��,�d�mv
��[ <P�n��e��v�p���v�q�e'�����dL�rZ�k�AG�5k��� ]<�P�C=��α���w7�ꘅ��{�+.x��"}�SQQ�9���&�}�b�����G躭�&;O���o��9zJ����)��:����;��y:Də�\��E�Er$ݣg�7x�`��+��*I� ���u      S	   ~   x�e�!�@CQ�=Lۓ��KHyʶR�P6_f=`-��<�2��!=T[�^=�{�{Њ"`���Xd�l��a6̆�0f�l�s`��)`́90���悹`.�x�1nǫ�m      P	   >   x�3�t-N.ML)JTp�2Bp���g.ǅ�Iʗ����24D��L������ Z%       O	   �  x��XѲ�*|�|_p xJa�8�p��\����cI�%e7Uyp�Z�t���ץ��uw�R�����ԥƵU$�(f����s�-���C�h���y
���5Jq�����o�I==�Tǎ���S�!�3&��_\�2��54����%�I��:D}cKY0Yj���u��M8�`��3�_��Y��oS�5 ,����VYU�ε��t���o��d�A�B*Wњ^����9��}�(��Qc�:ҖV���s�-P+�ؐS���y:�r�P�qT0ô����{wv�g�О#�����x������8_3�9�t�8����w.m�JI������H��vZ�� �WX#KnmA)�=Mߊ�*Ӹ:A�����;Lx�k��9־q=="��/Hor'��Z� G���y�P_�CGc�gڮ/S�(���O(ÓV�mK9V)
Y(F|����#"`B���n�ƪR������,Mag�Z�����#4�:��zH!�R���?����i�J�s������7��
��98��	H�y���׶��A�Ai��	�m��;%�A�Ytb�_47�Mo�[.�:����hf*YŝG��E!��Kͨ����瞹?�����%�Z,�B��6��١���*�}�-Vyd��J�:t���q� ���Gm�,�Ԥq�Kx����>�_��B(m ���S���b�+`i
�ԇ|�#*x�cB�6�e1L
���3�U�軸G&hV�I���7zLx������0��/�sź�_���̀MU�\�v%-ma��#2>�HF\��Q2'd�4�:�߿�b7��
k��dK���RQ�	�P��G|"�^��%� �K�<�aHvg��ÏUaI��}���}�^>��ei������c�ibg�Hz#7��͍|��
��}�,��5%P)��;3}ǳ�F��{�{�v�rt�4�A�R�_�Q�V&�қ{@u[�T��M:N�k�y��Z9�[i �;�8`�9�z�c.Ӆ��'�Av���%3�
f	�9�Ձ�n�;�ua�FkZ�S��D?�#�P�C����7�IiZ��35�u۽|L�b�>&3Fk�[�%��$��Ұx��b}��V�g^�������s�;%H�s��f
��'F\�*go_�yK ɕ%&��=]5���*;-���2u�?�8�/���Ըq�`�?X����p �tս�G�����Da�;�,y�♯�ܽ�ZW���q��:y�Q��n��m	�t��d�p�?�}¢Hһƃ���ڱ'�V���Cv�۫�ߑFc֌�1Q��Ǟ%�5~a���)����]�� y>�d0}��]Y��B�.��%'O��-�2���a����A��rz�0;�����2#2��B	8)�`����>1��-��]�(�������!�
�M9f��������� �!$�퇼�+�R�� _�s��'����
	c�<O�V������c]
f��Q���e~M��~���X�y0�f��&�d���D��{���֦I�	�1u�9�Z�r�1k�P�Z_MPK\,�FJ�1����,p��l�0�`���V7�B_�a�TG�Ӧ�����%i��zY�v�[��X&/r?L����Y���$�cpX96�E���E�d^a���,����$C�*+A_4L��V��s���8{-׉b���1�^?)j=��B���n6�����o �Fjn%=�}DqO�;R�°�'�W��<��9=�T¶
u�������yjY��wDv=Nn>��=�{�NiMV�X��0-E�n����-�[�����^��y�R0��V���:{$i���0����slke�J�މ�]v����Ye�nQ��cr=ɀ�Ӥ�n3����_0���p�U�㤾ׄ��`�@�.4yz?�]���Ԋ}M��+�a^��-xɴ��ScG������,�vs6#����#L�#�eO��e��������-����梼�wU8���w�����K�q8l�������ΜR�      @	      x������ � �      1	      x��}ۖ�֕�3�� W���I�P�$Q�r�^Nd[v��"�%�����k����3Fw�X�\طu���Ҫ̪�cX�(Z���y]����l�c{�V�UT��M�����=����n����ݡ�ն;�C{l������Z��c�ٷYm�}�X�����<_�}���x�J㼈����Xm�;������ˇO��Ǉ_�|]�QF�G�ԁ�ǏV�U&U�F�wU��U�\4�V��o�H��n�>��ݿ훝W݄�M��N��;�4��(LM��(sw<u��	�~BR��?�2� `��(#Cd������������������{6	c�A��c��|uZ�y��e�]�i����QG�����c��]�_��NA}<���2!|��˱���o������å]��s}���d��å��]����yB̸J@�(-�<)�h98F��?��i._p���s/�aF�"��2���Q�zh��Ļ���|��ط�4nN���2�=��}��zS��U��'���tC��*��@���ࡅَ�$Ob�<��[$ /r@���x�s�s�"�_:A�â�MQ�eA����? �
� w����m�M}9��t�7q8Z�:�1����	z��s�D'��9�p5��Fq�E,,������)as'�]0�+�q�i�eIN�Vi!���|�w����Ќ�����C��,k�D��u��m}�3����>����y��v"x�ß��a@@[VbT)M���~�t-4X6�� �<�Ӥ��4F���ĦQ������[�k��!��N���Q�\��p5���C��kD�lvC�n��΢�A3B���6t�(+a���U��'p͔��q�����$�q�6�և�`��\$=5[خ�&؃���~��i%��	�'pZ�i��{�X�������)��"+x�b�pT��s�~�����.�����8�	u��H-���)�+�-�@��{>����TX�|�y���L0�E��K�X.���Z y��,*�2�/��j�o�#\�i��B��K��1�����O���x��ٮ0�g:�Y�m.=N"��B��¸D�� ����}-zE�#r��G��)��fQ^�Q�#�#������Ӿ9��*���� ɶCU*��'-���J>_?�Ӕ�4�aRiN� ����Ka�=
H�SR��͛�ֈ҂La���8�W������}��������X�󶻿����msܶ5N�)�z��}�nAo�ܜ����s}h�OƳS��t	Geep!G�|	��~�p�%
��I��!��F^�׀ӿ��Q�q���Q����R���t�Ur�����]��ժ;n��M��u��Z��T�LX�{ �}���P��?kT�疁^�Q�gi9y)���S�2�
�~x��v�$Y% WU���<JVܘG8��=���_���Y~��<�|E>0�xxU���$�jz '^ � ���	lT�_U�+�� t�tU	Ds�}xoNs�s���fy���&I����x���
V�=����4{w���s{�k�|W�g�^ J3�p��L�t^DEDZ����o��֪ ,hpxʇV����,�EP���Ӯ�<�ׇ��oN�����)#��3�l��F�.)�U��x��9���]���1H�ċ�C$pu��Y\��x�Qᩑ���)�:F�+5�Ӧ��
�.��O�� U�c�Iɐ��|�G�q�huƓU�`�W�Hi��6p[���[7�C{�A k�c��~�X^+7ks�3)D��˼�H$0����Ö����C���3\?`2�M�(��a�o�]�򓘎5ݲp₮�v������E��e#�c�~l���A��F�`s�tg�Ȓ�3�1�>�Q�G%I���Cp�VސZ�g��wl9�(����8��k�|�<�p�f7qb��Fj܂�`�G�b��c��p/Fd�+s����zj#�J'�Z�9<!�.�����MPFU�>:�S�B>�4I�́��fN��� ^0���E��R�����d�&�j�^P�Tq��5dGT�x�E�L�	����ܬ�1� ���yN�T-Z�I��"3��(����`}��5��7��!#�Q�AOr$�.����2LP�iN2�Ѽ�2�1����Ј�	���ѭ��� ���ݕQT�KҨ���A݃�ړ58�QX�x;��_á��Mq?(�O�^e��V����ݓ�cd�`���&#�Mj��C�4�
�9eT��z�D�%��S�E�<�J�o���t���:�`\Ц�޵�
u2�x��v��a$3��i���b5z�Ǳ"$�A����<�IQ��+����s{�J�)P��@�Τ���h�g��͑�I�n��r��e\���X̑���x:	\p�����JH��'BF����ߓ��h<.�����Z,[X80u�j�^А�����éԜ����Jьk\�/Y�J9�F9�tu��
z�8)z��'4M�4�Hd+�����{�=�����@��%]��!Ϗ�Э�D��?0��Z� ��O?���qHzf]�Q�Q��R�+@�b�>���xA��Z�&Z�Υ(43�m��>��;� 4��"�� ܻ_�r[�p28#��Y��(�t��O�d�˨%x��{��ޮ��;��6�
���������_� �,�q��D���r��9(�a���y�!������H����Լ�M���O���ͲP����p�a����M?6;�	�ݸVj>sWw��(7}���dbK�C��۹�: 	y��7���%'8���y��_���H���U�RW�W�6}Z�d,4�{#t���I;��v���B��	c�U@��`�U*ne��w�iJ'�܆}
|��T�s⒬yL�E��x���Z=��=�V�@[��l����:�Q����I��I_��(�J�a�28+��.��[���Y��T,s���0�V�����n����<�8��iۚ�Ct���M2\N�m}r�����+�傛 �:�svh�rt�A�����K���#���?S�v�@��N�����:8�n۹�.P#������6#ёԝ��}s
�nxu;W�U\Dq��,0�����#����>���c2Ot�?��H�y������Q�GMq�Q}��^s����R�7��˘v�b�n���*v��<ŕ<�Ty�c����2<ж5� j�~N(�<�&�O�.n`�<���܈���6�9��][��兓����w�2xt�����j�3\������Q%��NVq݃"�o���8e<z��5T��/A�``����V���������Mp���h���k��"+
��,�r:�O��k5�:��>r����F��1L3��%ȗ��QE����-��G݊7��H�z�H|�w��U�9.���؋�H\�YQEd�
@sP��nj}�gF����A7ox'��,B�(y����� �}��MP�����̠ja�/O`
5�P�����eGs��Q%��7��/��J���>B*/XB��t���䑎��05us�d�"�-�j�z��f�Y���`�"�l&���*��r�*�A�~x�����p3?"�wTUEY�4�(#��*�-,J4��C*E�
��v�0]2����}��V.��I+��#9*sZ��ꡅ�/�w�"�$��Y���s��8�P�[GFp��a
KuM!L
`�*�Q����hȃ�J�7��.�9]ii^��pl"З�D �=Be8{<KJ~3f�p�����:p��8�Oc�O�7�Z��Fl�p.�K��y��~� Y��F�|EG�E8<�\���e��0
W�`aL
5��t p'�+L�8��)'��:��tF�VX0�QVk8c�J���b���ɋ��ɋ������o�xw���<�(qJ����/p�m�ۺ�W"���s��Dt:��~"�Q�M��x".��(�ԻPU�*���j����=��
����8
5m�y�l� p^50���Mk�yح7���f�R=h���	�ݰW7�S�8U�9��C��w�CSO���8�Vb	3	���)��T#|��]M�F�{k0���    	T�;h�t�A_��?L$u��'���$Ч�K�Ůx�y���{����ҟ�M��t	Mt��<��c�8��M}
�$W$JҐ6��2A��?���8 6����:�3�L�(�OJ�M�lE*c �\_c�e�8��ox���d� ���?Q��9:_��%ke�&eI��!���W�X���k����i�_L��;��՝L����z�i]'?�+F�G2�o�'=�w͡��5z1dC�n���H70�h*�A̿��e�6�P�7���2�+�G����3�sw��W�8����Qu���i��V7�Zq�$Ƽ��a4!�o_^R=��c��º!�. ÒW5��*kү����jHH:�8�|���	.Q5�㹔�L��4LҔ$f\v�?]ͱ K����Wp,#�"g�߮q�1T�#c���g0�=G#�4n��I�w�I�Dc�(u�\I���������>k�b��@}J2���n���d��_|�Mz s�F+�9JBAB���8�t��#'���7rӸ(����h(:c��n�8�S4w7�D>n��/��커�b��C��cwK��qhrt���a���ç9r�֗��+�`6�@\2q���^�p�:���,�#^���΂O���[�坰\�Y�r�����BV]����z/gn�&�Z� ɭFYw�k5���M=�c�Ё�����Hј���D~i$-�N},�0�{��Q�)Ꮱ�B�����v]_v-(�^�VF�1:!�������Լ��8u�Sm6��\. n^���ǭ�2.�w�^� �`X��Z�,�4�K�$-��ohv͛�*Z�F��a�j֝��J��p��=\^F�?��x1~��#=K�퀔��V�5��"+t��2"�z{�`�V�\Б��C��x4�8].�d~DU�����ׇ�؆��1�`�F�2$}H�zYe���p�������(5�w��=p�{����^~4�Ov/ў�r�K�O�y�K��	��`q>ߐ"���DX#Uq��|x��K��w)\4iA;�G):4���p@S$���|#S ���g� ;,އfi~�C�p�ď�jU0֠�2�u
� c�&?6I\��"��$�����k�ǖ��.]�o�w�-X�C��e��˖K.�� ̞����"��q?��g�1��}ܡ�h�0�'VAl[�Ne�㓎�rv��Z���ܔ�^TE �k��(���������Ǐ��Q��w�҂�2���E �=6���Z�[�3x�On�:P-���U%��LF��u���eW/���ȢuO�Q���[&�e�_E�s����ٌ�]p]P)�� �KR�,��=�D)X���dDv�N8�Sړ�U瓰�CTٳ8���I>��xc��q\�x��C��ӥ��.��+��,�@-=�5�
�����F
���#d�^;�.�i*��8�&ѡo���!J�}���QF��p��<h��\�.�٣�B5������}����&m�|�I]����
xU2D<@-D}�����k [$�QJI �;R�k�^wO�a�k3�����	�h���K"/*���%#,���Jgu�z���yC�L��EQ��<2���H�$f��gVh��qsm1����5���bS��X�׿"3J���GK�e��U��d�����PF\9�vX C�\� ��;Ts4C��&s�� 
�Hq����8�N��Q::�1�uYU&ELsʣP�5��{�$�Ј4����'s�<t�|�����f�Ԩ�9��^�wX� �����h{�^���� DTQF`a��������<�h��F�G-'N����|F��B*���W����u4T5��R�Y1�SS��W�@��ay�=� �S��ܵ0g,rF�@��^��y�l�俦	-���L�Oqٴ��
U�`� �������E�gY����]��D��nzP�\���6�)
L��CZ.`I#��Z����Z
tkqJ�Z��./aLL#��)I���K�	��x����n�e
Ko�`��ק�#\�?�F��Y�Z�չ|�}��(h��z���{-M��+�yE�y��!
*xL��/E�� ��c`��#ʛ�8K�5xe�g��z��4�͟/��H�r��A�ݒ(���S������菍�9~��S�[����T�J8h� �h�������s����A�%�g����)3#P��Ƿ�q�k������s��
ά��W%���m��\&XE�ƣ|��B�k
���v�,J��1�iX(֨P<_o�	�XіbU����_G�#��>��I� ����Q���ጡo4'����`��:�N(��0��ܠ��� ���u�Gt3����'J��l��*Ϋ�:�p�z��7#���B	�C0$���)��������/	iÑ�Y^����,ο}��	v� 0��	�RLZ&%�.$�W%�^�9�D#���;{��̐��Qq���즈L���_t�h�G�����BE�+���(����-	�!|!dy�f	ńVk	a�zgo�%��\�&l�(H��Ǉ��)4̷Aso�j��j�9`����~��Џ�gݐg1��e1�\�p��0GZ��<{�_L_�(Xl��O�S���p8y�VI3�<8J�<�C{���5��4CeVU[�����Y1j��R0ƨ�:�],�"��9���ʴDV0��F��v�S}�o��L0��D�B?Wܫ����R����?�f�U�H&�1Ԋ���+ץ��|���[��PB&���Uw�y��]燘s�X���	N�z����:`e�ċ�c$�6�
�[�m��W�u=u5L��Z��h�d�v9JL�\��և�E�4S��,WR@ꝯ�kl6bz/w�R��&jA��=F]\��$>5K����#j=�����_Ɗ�8�V4k�u�����%)@Ÿ���g��$�7���� �\Z� m��CO1��|�B2�RzZA穬j!;�[�֧��m�O��z��#�Uч+�o��g���y�0S�(����rT�,y���U78����,��R�2�B?�5�m��v�D��P�!^J��OvLV�QI�GXl���Q��Fn���Mr*34PI��Q�qj���:�OdD�xU�7Ri�R���� 	�7su�����iI�x
�R��}@�]�G�3l�_��Thʨ\a�jO�P��6^R5W�'���=PI�m��5�7�� P�w��b���n�D2�d�)�`�f)�C	����9��|����pc�^hw��@"�a�h�U��UJ"6��6�Wa]}�J]ϐ�w!�`�th�#	��0�.��U7O1x�DS���7]J07x�4&Q*cΖ/ɖ�q�0�:�t/Н(�V�w���$'����؞aqnג��tJL!�4|sN�PRz���W���
kX\�䣐lj�]K�(�E��G%�#0�!�J���\�� �������b(��!��/��sTE��Sa�b��"�q����|� P�S�,A���Q�z��Y*D�k��ܰ����
�"S�zO���rJ�n�\��a|��h,�(��ۨn�Ac=���0�����2�~�ǪI$θ�ㆬ� ��r�j)��Tv���ȩ���N�7-�!��aR���q�}r9	O��q�}xCzAYT���Y2�5۪�Ή7M�C���ai�����3!;H��*�z������KՄ�$J��T����M��)�Y/���@ۤU�@~%6�������c���1�A�y̴��ɂ_r�`��������a\�k"�V�ō]ř�<�W����x����&8��<�d��Y�
J�6WАrs#	"��XLr�o�����C��x�b!�L�P4��������e���G�a����S�`�_�G��g��>�Ja~(+�@a���o��Dtw�8�.0P��0m���Ue%�¨��	O��Mw�7���P�����6��f��&e�e�
ݶҚ)��)���o�8����ς����Q2�j9�C�/Vj8�=��p���R�0�R<��������*�J���-�˨-3,X`�]�{�6ʊ\�z�0H��9��    L�q�����=���Q<I����D�҈P��60p�?�)�mHP����9z
>�[��I%��{��UvB-�?����=�;����;�0����8-�XF��0Qc&Z��� X����Xd�Q�j����<��wI���1]ߠH ��f��m�L�.Q8��2�-�˔Ndh�J�:ں�X����i����2Z���ǨA�ú;6��Y|@R�>��y�)��s��w���9H�(aBpY�g�N]U3!����YS��U�"�-Ɍ#��ᩣ���/����)�*��;�a�+�ny=�&:s
��(2K���q���t�$��rN��4�[���h(��b1+�t�\Ыќۗ"00;7s�\�SyQb�
Ed�t����V��Z�����*+r�mi���ʒ'\6�q��\2g�T��%�_��돖+c�>}��ǯ��)9�?aY�4�\w�}D'
?���������(�O%�Q�-��K^��n*�3��"f=F_^􇡡l��7p�8�Jep���Q�B�>?5��[����1�-e��ww!"�N�l_�%��SN��#�1)���xc
Dj�� ����=�eZ�(���S�����ا����s��b٪lo^���W�y6�=�$�>A�+]6(��!0P�%=��0��L!�Q�����H��wB���{�=�3��|�=F�[��������h7L�h&�/�!l�92�M�~1�}�)��ܶ��P^���զ�� .I�f�$E?����յ��Y��"R��s\ ��k�j�걂���5ޟ~}?�)�#�<�':
ˬa䘾�u��QͩG]���%[=uD��ԇ�֖�wH�k\��x9�ZR�30�r�*Gv2U��i���c�����Jc��c����V��T��^��ﭥ�C5�$>Wܘ-`�����'؍�P+� �6OM�~8/����Q�~����>��H�s5�z�	�V �iC��-�ۮ^�H��"O"j,��q��b�p͌���ɗ�*,/�*Ss��#��sE���نyŪ	u�����C�~�&�����T�bʬy�N�)����"k���N,�B,�x���j�����\�%)��X�w�r�n(Y�ɥ��~���00�%s&i)�nU	�%�3�����\�]�l�<�N^%v�JI�X���(ҋ6�#���j������w�I�|~����Ha�E��l��xٺ��M0����L62�JsEȗq��O:�^��T4�c5W���F�@�-�$^�c��Ʉ1�0R�R�txAy:ȧ�\������,��L�E�5����в��J���x���tC�|#� ��c��2N�A@��+����s1�����Q��;$�?�>�c(L�f�"SJ;���M���L�Ty:��񺳋��K
�.����͇g��Je��k�@@���!�	Ɣn�����rV�ܥ	���Uų�PLvѧq�[������7�d?VF��UF�Y��UpE�O�ʎ����{/�IuA.�
�S���q~z�q���A���S"���Q��>�5���ZP$��߶�(;J�0%=*
9x� u:��?.R,��^��>M�����n����RgX���2*�j�>7�o}v`�)Kq�V��I�7��V'q�)�5.䂄s�㒧Ԟ��Y��̑�#XR����R�:�ݮ�x��r�_��ɉe���ע�d.K<�*�N�"�����j�
h�a@�cފYV�v�xjXs�%XȺ��-c$3��pAݵ�p{�8�&� Ѳ�S��E+�X��9q�Np��[�1��ìO���1�h�UF:
W�M�PÉJ�G�k����<8�+

M*\@��Y���S���F� � ������/H�%�p��Q�s2�]��
��*�HH��v"���~lw��8K���� �$z�W�.�s��i&?�んЮ���l��_,&ۤX����xE��s������}�9n��a��q�\����ԓ��/�i(�d����p�W�c�[�=�S�uS��x������=O�ԜY���KF�aղ�Cz�F��f6�<."�E
Ӛ�b�ִ~��c�F��F�(�*,e~q=;鏏8�r�5;�T}e���A�|^�èԾy���u��E���p�6������������0i��cw���0���M��pg:o�h�ԅ"S�K�	�+1����bd��E@�h�l4�>Oj�ǧ�c$��R�AΎ�qvЊ�E8ы��,Ă��1��{�~3!��	#B#���H�銰�
��$f"��9�O�QQ�ԁ{�[��ჶ��f"'XFF3�Ŭ�a
Ʉr?�#:�fv�Q���FV������F��Ā�[�Tᠾ`��έ���M{�a2֪J��\�"���b���Y��It�)aS�=��yi1ႈ1�%�Er�V��m��_t��5]� ٝ=V��|���?D�/$Gl��n���38�dYN���8�sV�W�������?�9Տ:*W�ˑ�M^�xì�5�����^�}��Lj�QX�>��e)���*�m��"1�s�p��66H	n��_���5��<Sa��̏�����£e��YN�"P�h۴g�y�Ҹo���l�����B�Cd6a�_�r���>T}�*~�:!_+4�W�ȁ�c�	�������K���V��s�Z�wa뾂��-q�e�X"���{�)�EV�lx�(�^6�n`�\���L��Q�t��HN��N&t@ы��MAg�?�3�iא��<��=�,�-+�k<
��^�6��:��I�i&m��T��v�캄��z.�y���U���w�6�M�v���s[��G2��Σ��V6r�]+���0�o�4��3�m[YT���]j����w����l��=��ӘLH��eIS��.J�W��J���tO��A&����
_?���mߢ������m��J�W�Y��p�2�`��C0FΏ}'�n�7�LF�Ar��a�usx鸻ֻւ!��Sl����V�r�؋���)Dst]����Y�~xN�lп�*�N�?�֘�R}�FkJF���M��Y�u	�U\��Y��O���
����<&��*�@�;�ܕ);�yT��-�����Ͷ+��4���ط7ZH��4��9�!�T�g�Tc�k�]�:Av/��.�X(Ұm�z��mݱC�J�}JXp"S��Kr��!J�!(�,�>mI^ Ņ�	�hf�m�+�ɆH�F���c�cF��z�¡�R�����54��7aV���89���)�����*������jV��qT��ӊ=��9e]܉'���>$�[gpד�UD�e��Ѽ��:˞K��qO�kZs���{aF1s%r(�w��Uu��[�/F��1��s	����Q�t8=��֛��ުm� �W�0fj�A�2$M�Y2\�G�i�(M�+�?�xj�"��D�O<f��S�8�E��=dxcS]��1�)�oA�TS�:ç�����R>������0�)B���]��fL6�u�sD:8��1ZyN�a9W� ���(_���)�������c�3��;���I�_|+i�]LHYP�c\�?}�[�'|��Q�E@�(�3R8d�h����.����y��"pJ�R>ϸ�E��'/)4�p�~�Ы�3�y@�SE�@_�H4E��|����k�� ��vUe9q-/Qˣa�WX�9\�@�6�λ��|��SrP�iX�!I[`G�G��{ufy�i@yHq9��0��sO�����B�;nA��\ȼ�}�N����K��ρ��ϼ��	v�L���Q0��PV���ζ�D��B�}3�l��9Y�[%t����Oқ.�6{p��~�h'�so��Y��~��N=��Í���3��kz]�oZl���N�r�T��BX�aBF�B��Ǘ	˷ G��{l�1x}!��ƸH
�H�kg�F����a��0n�LS�(7�С��YVO��e�ԁVq�����i[�k;{�X�>����(�Z�3ƇС�v_Zt�y]
bm��=����*�gg�N�����6�KP_&�B �    ��+L��:�Y���j7�T$I�&��GF�
]���z�p���Y.��~�ƽ�r��$^R-���\`t?F�-����/�J
q��#���o&�����(�t��z�D{$���H�^��mj�	�`��9¥&>y밍E�����lm�D��;��`�������2�V��sF(���P�x�"m��e��5�`ν��:U��
ʸ1�)�ԃ��ρ�k�{n�q�<1K���+[E��}��N>�IA��i�+�F��X�I��YF% ����'�,��t(X���JV]ެ�|��MBO\q_���݄r:F��+7�����-Ć����N�)���w�����4r5~��� ��[Ij�0�r���hB���%���� Y"�i�ĸQߺ�6��/X?�`��{������Dq� >����m,,Q����D��.��*�
$�2�i`r���x���w�i�M�d4���>ھq'3Sּv�j��4�M�Y��Tz�i�$� ������P�j�ە����1%%Ȩ*6Fe��つQ�{�&Ҷ�Z�(�t����p�<�Hŋ����M��j��i�Z��<�ELa?��gy��|�t�\>��p�b˛!��D2��l��H���T((�Q��T�+vbB
R���s�c�3���4�V0mOY}x���e��#��z��x�䘜T`
�Z��?���� AQQaKυ�� q�<��dk�15jÏ1��G�^�GTfe&�hp���?<�)Ǻf]�Q`=�k�^�6.��)yU�	'�)�Z\,��\�j�C_� �8��x4t;�hOK4�nG�0��}�Q1��q�I��e>uY 
�)<+�D/9'6�;�p}�r�U��D�!���Q�~�����Nw��D=��x�C����k*
�Y#`�����1�~=9?C�0��/a)����xjvH0٘V��(�m9����~:���e��˓���ڽ���$�!�y?�cMC� )&�����%Fˉ�r�c�pҮO`��%h.��TJ�y9��MP4���"�m+����c��,�Y	��{7Q��t�Ơ�9���/F�
�d\��p7��I�M8f��n�C�b�T��d���p�"e�y��nL����8-ӽ�[%��o!�+2߮AU����רx��0i�_srj��3rJ&��ԍ����w#E��a�=�T<�[�9���q�<j��i)uAVw>��*�aq���$��E� PI�F��	]��O��(;f�G�M�-��K6M
����<J0��9�%�{t:A�NNwz9�N3}{�VZϘ.���ŁO�� ��	� C��`����G�I�)^2�<��!o�^����|Nc�%;�e��͟��t���h�ȑM��
���_٥z�8��h�y�u$�Z4�<��i \��1޵Y�8���]��.Y��>9K5�fɤ�c�PE>a�e�ӯ��O�G�>Nz�������G��+��+^P�T"Zh~"*�vp���om�㺷/!���L��m����I�BV�H�G"�s��U��7�Q��nhz[�ą���n����Lb���Kv�_8��_�'\G�ͮ-��E(#l�����*s��Qy��z��S? ���8�f��#f�Bzf��#����}t�`�M�P�C�r��Iٵ�#���͡>��p�){�K]��"�j�n(*���19�U�RbY����&�i\.�P�m0��/tct�?�ȶ�߬h����9T�i�Ñ��F�Cҁ9Vnр��8p�o���,�l����&2)�K(m����'��-eD��yn�u�ofr���6|8v=�L<����;ՁZ�Eܟ���ph���?��g���r��t� �I��Q$�4y��<Q(_!���O4.�`3�t�H~*�E�<���d<J8�^�`��=J��a���WX��XP�Զ�\Ƒ*�[i�a�U������_�|p��Fod�W̚�����NZ|w5��!�d��9wת���S���5��(�p ��L���$��2sl�U��W����F�������Y�G�<��9�'J�&�$c����V��L�oX�B�7����ʾ޴5��C^lK���8!����������� �����7�n�)�u�-�h��R�1k��a���ۑ�k&������N�P+:+�{�9��0O���QG`Q�js�<����M��B��ӄ-�2\Պ����<��
m��Rh��a��ˌ��dd[Al��id�h��Ӑ�#t�R2Bk-�<���S&9%O+.���K�[Ը�b�iXE��'���(�F�>����AZu[[�����C�)�;ނ`.+oi�G�a��l:k�O\A��*���۾�;yX���T�/�P+�%j��Z�6vs1��i[3:L��pҲ_�+�׻��9Qp�����p��8c؎�q(���-htD'$\;���X$���Tj5�FbM,�#z~��X���F��
�V=�)�]p
:��ե���tT�"���h�4���9�����et^JM/P���ϟ'�������]J9.dz%��h��%�am���:�ɜ:2�CM���MK�l&�2IdStV������6[���6�-��DNi�uO܎�)|�Fo^�`��̥cp��:`>]���y�'f��O}}��z
���R�E)6Υ�q�
�W0��m�_kUh0U3�T��Z�kU� XYD9M���M�J�L���`�wx�9�8�3"'�i���˜�X��7.r;3�zא�c8�Sl��i7	uKo#��΋4\�Wp�����(��h1�l��C�|Q��Z�~V�C��X���m1���}�����2ԧ9��EF�m�HX�������ב
�b�><6g����`+i��o`���5r'^w3���cz�lP�^ۿ,k�V`�J$�|޿��vK�GP�9kL	6�y��V���U���Y�Bbm�U_��}��aol��N�b���+�fN���?_*�#�A���c�
ׂ:<ja㣜<J�z�o9q�>z)Қ�3a�v�㵱����Kg�Y�E;'eh��I�N�ĳ�)��5%�������""S�]�uW�p�J:�N����Uj�<�\�<�!IEǮ`���	�y�^�����M��8
��QJ;�G��Jؕ�Th�+��hX�jX]%��݊�74
�-����C}j:Ւy���a#������V�]OĖ�)=�eµ�
	�Nخ�G�{�I)Zi�_G)^"h4�A��gWA@�Ƒ�"Z�Ҍ��D�j����@��a2y�����9V�X�����/N��\�P���+���а���8�7��%<�%�I�0GA����^�h�P}��~8� �p2�xTQF�D����:l=GA1㯁Uc�:�����s�x�R��W�Ѥ��kug'�`�a�s�m�0�(�TF`�4;�=zXo�˳_����rt�ni3�8|7{톯��\0��6V!����45��fyʷ.�L�E")�b����>�?8Nw��tЁQ���6􃖒�x�,���ƫ8qR��o&�G�v
|���M^�%N����H<}U6%m�z����WZ���Vhn(�)�iA������X�eq,:�e*��_d�,���fF����Ikv̊�u���Х>t��t/�Ȕ���DZ�
�ki�B��=R�T��C��u����ER�/ܓB�VL���Ȉv�0>�']��@�;gx��au�9#��7�Z�aZ}�.�l��i���#u��S�F�&�.{�(r5eR>���[��J��%��~ԋ M)d�l�[uŔ�L���6�n��ڬ����.��W�z�k��¿_x�Yv+X�wH�qٵ���pʠ�����]��DgY��O3��I"Q�0׻E�\���e	D��� �熙~0
����&=ݭ�{����=r,E�.0�5�Ǖ����#�����e���AE���9����y#G/\��&4
)��H=r.��=��E�<�ݹ�>6+c��Jh���	�0AX0�j�f¶+�॑p8)��!����d�l��NC%��ʢ E]pY���ѽ�U�«&k�I!'�Y��z��\F��<�    �{:H�o��N��E�F6�vp�\�O'4KvhJ�sQJ�fYO&��
��@zBK4��G&��(Sv��u{<QM	������,��0�)�K�݊�\Y��3��\w_"
���,B��N�<�rb������~�p�`�u�),7�����Ez�.��2i{^b�hf���%�.C�>��𻜣.�Uz�#G��ӏ��kg�C�"u�&:7=�9S7mq3�Wz��*c0�T�����Q���E^*�{�۠,�7���6q�4K�xuvu�.o+*A�F%���J�u�`E�YV��c�WI�6�ؼ����i\��)fq>tg����9�ԋ��*��4'wfƥ��y�@���g�,ϥ���脍(�PQٹ��!q8U���7�;��j/Ԃx�Qh
ϟ�;�l#v���'�����؝�Ty�0�D�����6h~���Hajȟ��<棸(J�
~�Q�)@p���)(���Y���
2�O���'Ug�fKSz���%�����9u�>C9y�����T$d�`���E,S�QL��[L��d�y��N�����(��H�!��AI�H���e���ϗ��.�V\���(M�_.�8aqq���#�b#*�|Hsh3�Kj�%,���igkô�7�tEy�
ɔ��{��#����{�ߺ�8�Ғ�j���u�y��D2^nZ+��=�e������,L}�� +�:Lv!O$<hkdT�-Zz���*�X�����o��?�b��~�@�,壖�.HCS&�$-����o������`���$y��L0����~ӭ�P��x���Ou�s�jK�El�b?�1r�ұ*8l&�D~��t3���{�[��)��j<J�S���o޾��>>Ńf�J)5P�r����S�a.�)^�7v�>#��ث��F�F���;<�������"o� v"n�VC���`?��JXy���&��H�!�1��{�;��րe���HH���R$�$g<Z�!pϝ��8�)�IoV���z�xv��3�+9d�1�߽H����S{���'y�1� ���ծ�1�Ӄ[���JίD�r�/����.���!��/6���0g�zl
�D��u E�.�n?�-�"���Q�:\��恺�z��x�̔�֏����9���Y	�t%�����o��a�	����GtMda�p�eC:����(}ո�9�=�j�0�he\\E9���tn��cezA�R���j�Y9'N�¾��/l�X�p��$+�R�o��Kd��cq�=C;* ��d�9��ks@�7�҆���z��ϰ���
-AMBjF�H�7�W+ͭ�}�0��&���$c�99/e9��d�2ߜ��>�m�'gj�׮XɃ;ɕ�K����F���R�cQ�֍��^�EW���~�"��������\`�Rk+�)��D�k����95����]��ga��5�I��:��P�`� �^��,*���2�VO�o?��~!�T<)c��G#x�4�&X���)�G\O��A��~rBQb�4���^��,&BD�G���<c�"�19�Z�I%�&��U�E�X�G��7����hucwgZ�̪��D���j��ރ,o<���
��(Y����syWo���j8��-̧��n�&~�ӬHB�a��u��PS�]�� ԧ6a�~��Y�����x"�Ԕa�����v�D_�Σ�rX}�K�k��`���#C���b:���0����Ƴh��Q$.0�����?�L����tNu�Z�kO�,�Y�Ss�m"�䓄��X���q����#���s��;d��E���i*j���Ǫ�5	�uz�K�ߌ%�I�"�"҇�q��<a��	��
b�xT�2��1p��j�dl�Hx���ͧnh��JHW06���"z;10�ě��3K�4���@A�t�J�@����y�({�����Z	��H~�8��xn�%�� �=$�iR���]B��T��//x��/|�(���h�a]�m؃8|��:�:4%u�$��Qx(�"^���2���	�M:��O���,)��\�2JV���t��['b��\��~�6l�*���J0.H�s�
��4&���/#�@�o��ܻn���\F�
k;�w��f��LLL���Hk&�t~/f>���RH�'9�D����Y��Y���lV
���+�"��Kਠ�����-]��q[���bϕ��<E�(Y.��<It�	��Nx��ڈ���4B7��~������f0�:���(㻖��W	�`�$���_����,�"�Q%�M�(���� �ۢ�a��i\���	�ɨԚ�G���J�-nNu���>	�ᶨ\LL��-�36m����"�v^_��xF���ͬ3"J(7]F%vz�N�[e���x�)w�_���RK��Mo�4��;���5��n_?�4E��h�۽����HF�j���JJ.���!�
��H�J.A/�ğ(G��/JȒ�Ȩ�K��r�����a�x9���r[*$S+H�(��'2B_6�/��O��
��"~�S�s{_�Ъ����Y�{u��\*(3�S��	��pWް� �#tcx��
��o��f�2���1�WA����%�,�$�*yEej��M�v�쩜+<���IY�H	#Ӌ�~��'X�v��wN1,��
N��8>�S�Q��\�h�faL�l��r�����.���`����(1�z�D�~idi�׻�����y_^V�]�R�g��%�=����C��Ԅ����xD����G{@?�**��}�${���l�@)w.�ERѮl�(�s7\$��Oc�Z�\e������o#��C
ꃇԾq�/�h�"-�@��^���
��W��\�Mk��m���;5Z�ҾC�jL�6�N�T��H�E\F%�y�
��"b�]�<����j����̦�,m�b
*8�0R�Uh��w��pi�-w�$t�R�8�*�2�1*����{Q����UZ��>�)�,�xuh�g	�{�vI��2����։Ʀ��y�W���ԻX�����C���Iy>{7+5'����vo�$�,O��w-�����|�~�A��v[�mg���^���3��_�8�g��T��ʰ�ɤ���ןF&�k�h�xF]^T��:
�V?�h����F����ꃤ��]L���S��%EJ�p�Ѱ\'y�17pi��Eo��7�D� eɝ�eTi�F��0j]k%c��2\�M�tօ�fb�yZ���$*�b����k��4���v8�E��("�*��C��.!��c��c���"wG�|5�g�J<ו��i��:P\��e�D)�y($l��s�@ɿ�89�D�rzi<	�Z$W�'"�p"�|�m�f"VJ�fA��uH����T�r*P#�l����B!y3]�D��>�J�6��	��r����DZ᡿��Aet�oW��[ɪw�^~�H�vaiq���۽K��_W�N���4�]\� ��7�E"B��׋i��t�s_���:
W���H_?�e����:�|�l�+���Ś�U�d�^��w�ߍHZ0���KXg7�F�t�\�}�ݱ]?�����	C.���H�:��֔�Ed�����b���r�,ʒ�o�5$���TE�v���C`�Ƕ�)�����a2�=h}���9l�V� ��h/~�(�BC�%� f��"Гr�q� ��N�s�[X��.�@�P⯶H ɉ��QG��'�a&U����)��qâ��K��A9����\��K�M�M���SJhW�F��4�0���?z��t�^'�L$� ,?��FlOlSL�ز&<hG�����/XP=�R{�.���5&�샇�E�W(E�BAщ�|��1��;�R�"���h��7dۂʒ��������{���uQ��@=S������,rf��o���A���t�J��Fr�f�7h� ��t��=��j�&ިZ-��xN�P���%c!Y�������^7eRU��@������*l^@���������N���ȁ��8�!��p0>ה���V��d�/��0�;��BI���O�1�2o;r>    2~��`�9˄�\o��('9�РC7�e�q1꾣CS:F�C[�o`�W��]}n�?6"�|&����#�Z0P�#o|�<�������ƻ���R�d§9m:%X�r���+�n�C;�����ȯ�)3����g�b3��q̥�`�bI��CZ���S�2}�Q��np
	}��pbj��^'.m������Z��\��nĜw���Y���\�x�x{F���gH�ˌ�t���i�]Sr)�|��8�s{4��`^�NW��	'W����xHmo��'c�	Qc+R�����m0�A_��l����C
)n�x����m�e�E���.���}��6����\�E\V2�o
}�4NT��!N��g�w� V�Re��65�k��l��| {����+馞w-2�{U�MJ�EEb�S܌�8Ơ���S�obFV��RS'�\jC�?'L�����I��N�s�Z��ae�I�˳$�������(47��y膲0o�b{�*e�p���E�0�㲢U�%m�F	3�=�7ZY��f��_FA���R��n��c�I�X��{6�G��!&��>a˜�(B�(��{��m���c�������҇� ��8F���#�;�ۇ���b̟�)T�����!z
~��Ĉ�֔Y��os.�4ܰ$v��ڜ��n�DTXX�`�d�x|�y���E�=��:�c���dh�C[�T?�H�Zk<g�����JKAkY�i�m���5,����|�$���"-�*Cq��xi�Q���!�E�X�Ae8�Ұ 'D+t�R�����q2��g��QZv�!�N]Q&�0��jN,V�$�'{y�~��<2�E�o�$*�8�Ƥ�8NH��>l��z��G㜜�2�=�vt�4�\ntaS�=n����raK5�pa�c�x��j��jǴ�F�1i;�f��PM��e���k�<�ǳ�����z&rW㰰�.,�0&&-�c���&��*�w��4�����jLE2B�#h���y۷�f?Q��\�.�r�K�9��A94q�t��M��
�}� �������3��1f��8���?	w�H��8�����w�Ny�!m���!��R܍�T>�j�����bBfK'�s�2�pz��o<�9�f�"-�"?9��&���O�=kO��#z���P��~�����"-b����O~��xY��qR��?�'�T*e�=��U�	�'�g�����s��0��^�ؕ���5!�1k�M�_�i�
�u�U{Y�y�H���#Hu��~'Ω�Lh�̚<�u�y�r�F�#����藔e�0����	ksw���[RDV;����l��(_=`Q�=�b�/ώ��y�)�n[H�����æ5F�ҒΠ (E.�A��� ���a��}0Fɏ�i�>��<����"5�q]�O7.Ŧ��K=BT�T��e��x�&qc�CQ�d�	wG��6��zo��>T�U,5�lN��r�J'�����)���7fF�y��DK�A�|S�3����D/ҽ�!5o�>���u���S-��}'O��hvmϛ7h��/�dg:*K{Z���7�@4�_�\���-fy���H���3'��\���{�F�]��S>����BI�������󱪀d��e"i	��m�S���챐G�CcVn4"�����rs��C~��jS��Q�Y�F$'#4:�'j(9����U;���8��4�S�x�4���E��޵���)[��s�����{���'�kb����Uh	���OT�2{�����قȞ�^���H�"��k%pO9���%��
׷���f	�id6q*4�ޤ����'8���^�)c�݈�G���^,�pK�]x����p\�j����:P���}f;S�P�}�r�w�ԷC&#�˞�8 �A�k�s��p�dk��� M�ڻ��>vs��S[�?[Ri��/D��lO����kcŒ����krMY�pP�"n�UF���d�x�v�Gj��H�.�F�H9��v7�X@�U��`�^z�Y&�U���`�Ьy4Qe�-%�:b@�Ց��.w4ה�@��(*Dn��c��ع��T����P��ވ.��
�S�����!3\jC+.�|����lM�>K��G�j��t��z����`�-j]5�@�a�=<s���ƃZ^P�%���\���p����"��/�<����ШB��u���xW�֤w
�ɾ��'��_#"�,|v��F_B����6��2,�h����(f� ��@�£��xVu�`�7;�����&�-|�n�E��,�U�R��.7�%�Y���N"
����&+P�f��oۇ���*tɰ�웕�b���uhowzԽ5��d[>!5
��&<	jW>_(�
��K�����b`�]�2��K��+���k�b�E_��8�Ӊr��X�$l�#�cc�s�]®&ފ�Z�?l�崂/���J�����~�~��xH��D�2B��sv�c{F�;���uM3G�q���'Ϯ���� �vH�NL�X����2j?�Z~�>Ʌ+��T!��h�,�כ��Ǽ���C�%���>�������.iL&��rd).����Kʴ&�������z��Dy(4�����97��Z�'mR/��D-��]��6�UA�jUUL�-�߄b���|�	|�����"���꣎r��Ӌ�M��y�o��J0�|��r�¾Q)����Z�� j�u�XUT���L�%�ç�k������Ǟ_�~�(�ed\�:Zn;\�آ�U�WG�'�0a��#��k��\�yJ�s�X֣r{����K�o]��|xH�N��B����d�u ���pq7=hd�6��~s���_jՔ����V��;iQ^�R��L����$6��Ra}��
���5w��+��F���{�9�8��b��a��C�r��F�tI_�[��/�
�tQ֜µn̯㰊�����PJ�W�3"^�`L��u����!�P7m�+��A�����Og��؇�8�S>���
�fm���c/���`EF�r��&�/z��9=�o���hu`��7�֣�~��rK�;��ۼ�geQ�}E�p���vߺ��H�3r
\���~h�k۾�Wy���ka�x��u�B�����Ll.��x|����,I*>�h�6�eKEĚi�G��X��Pfa2�˦���k���'�w��^
E��F36����H�d���f��S_�L���veig���6��� 2�Lj�4����՝���G�wk#-l�D�L%M?A��l�b�W��
��j�xqX�slN���>qx�#?O*���tޜ�V���%CB�;(.�fO���V�;q}���+�(R��",S�Q������������o<f Ĩ&̨�ف�]�.�)�Q�#�K�U�~Q^�e���o��Y����P}C�h��?�Rr��4
3Ѡ����f�,%�h1���_5<���	pQ�M��3��u��=��{H��Ӊ?��H	#X�m���u������/��ۭӨ�~6r�R�uXo���+�՚s�L�mF�Q�F| �iLP�h�`�	��N}C6̽F�F!%�����tl������М�&b"�TX�'Q�0��͛�J��{}>Se��$fI��ܳ�9�OE
��Gj�x����e"󊣊;�m�eуI$�h�|�v\.�/�uR����{4t��,.r��)x�ޚ��#?��(��8�*	G�=\���Z�����Y�������6�hs�*��tg<�Z牌�30J$���rPS�p]���"�׌D�^�s��^�������q}ZS����) ���ɝ��P��$C����]w95��Q�M���DfX�Tl���8@�xx�9�Ζ�eT�����h΅S��7�=&L͹�C#��X���љW��He��wH��u�{Yr��@Kb*P0P	S��"r�"��C��+���R����\�.�#��N���*�֯5�eV���0#����rX\V�_��P���E�2�w���v�����t�=�g���{$��ᓅ�     1�U$�"0��y�]A!S����x���qF��2�W��;��
0�6xU7�5}R)ǥ����t�����¯�`T���%�׶"V�4,V~�~�KE���0�WXI�6�#s\<������������l�6�d�3[�7+q�Zp0���}F$%�s	�A_�-�I�Z��˘�V���Y8�g���_#ہ��,Oy��y�Uoa�"��q��(���x�b 5\����^��+&/㈣���r]��:&c(��{�+�<@Iy�!����]�'.������*O^/5l��^�H��3�Y���2�l�&���<����P�I���be�փǺ J2&�%y�6.�n��z����|�,+�"�d����qn��~�r~C�2,J4IuT��2�De���r���[9©9�(v�\�[*��$1��蚟�UY?�2~�i����D��v��{�i'e�.\�2
���LŻ���l���UoYАW��@��nO��)�НV�������B�B���}�5�~q�r2����]0�bx��]���z����B�;x�e�\'X�/��9������H+����Z��̫��,���gee��<��W!�}g�<#�i�[�����ǎh�2~bt�I��&?P?��X#�k���"1(�ԦG�����w]�����B��x��%F5�gE����C\���&l1P���
�x�I/��L�R�t1�X���X\�<P}Jk��@�J~�?xD�nT�^PW�j���k}���{��Y�� WN��3�ʌ����&����#p�����2��%���3�u�s�Q�k����U�R���K��|W>jv$��E���$C{9r�\���^�����g.U� �S��⴨b��F�� �j��\J� ���L�6�j��|>�FP�"MS:�������T��Ƚ�lvϼ|��2�D��,�zg��{hob���e�ַ������Q8�Lߡ-��{4%M���qئ���K�(�T6�}����V0�<�0o��}md���u�j��w=�lМ��xF��Y�f��qe�2���3�fUR���t���L0]�4�3�G�V�d�M�G{�Qh�U��EL����6�'f_��{� i\i�2���z8硈[xa�O�'��r�Tz��������wh��d�����',�`�vcG���
F:N�$�7s?�-��J�R��u\>Eρռ�#)I��ˉ�t�}C�>s��օ����ʁ{�=�;��d��[+�5\�E.��xx\�����1�J��s��/�d F��է���u��8�q��<c�"��t
W/��HJ&iEQ$��P(F�����c�o�mx�J�G1�vd��匙��U�Qɿ`L	�f(ͅ�`ai����p?����߾�j~�TWdg		2���*�H�4%�VF�j��݈�3Ҋ@�������ʑ97sO� TaL;]a����r��JGo;�:"�����0��u�Q�6�[v@ˍ�*�T���x���9aC���^��|V���$�H�hN�m��1ߣb��F���w��ǙD���_�����c�4G�j'u9�.�P~�}�-��[���Y�EH���
��&;�R�K=��E{5���0?�tEWñn����Aַ;'o1t�m6�k��!�K0ۨ�]Qٴ�	��]6��w��P�)Z�$s�}KdsT��f��]sZ7��	�G������vo���]�˪�B�D���_b"=Xs�lpx`�/�Pa�{��,�
�0����d�˝�9^4�P���j��K�eg5b��*I���fZ�����bٕ���<��E�G��
:��m����u�UE��'[#ݹ߼���lb����<���N��{������+ƌ�8>��=��p4�B;U��?7�m��{d"�����	�_���X,g�F��ji�;��%%�)&sl}�����H�:׺�u��;I�u��N�'*ߠ�����xDτ.g�g��&���c���oέk�z�`)���;"Z�)�cL~'��딫��B%�:��7|���VV��"b�Q�B�\O��8��ێ�oJ�Ux����5fط�iY�d�QPLI&	�9��]�T���J���{L���iy��}y�;؉F_�(Z�s�fRz�`G�M�Ԉ�����4��baWEB2$�e��m��v<�i�c�~���i�e9������oѹ��+�x�v�^�cf��#X�ob�o�eG>��O���ᬻF��7WRS|XG���V�:���1��a�.��ה�;L��s�3=7za�$�^�uV�3���&�n̟�h�)�Mp���,�JcX=$+����f�����k1���{�p4;�
�*��t��]&�_ȋ%�}^�L#a���'z}�h����=��3��#�����]�2�f�>���������(�5��>Tӹ�$n����CQL-e��L��{h"A�A����{��V�Ǖ:0Ɉb��-�7_9�J)ɹ�q����L$a/�(nI58
���?]���{#�$*gSS޾vn��P-�Qe,�~���i���p�sQ'X�)yc�w�aO�㼢D�e�*>��13}�6o�n.\!��e�d��?'��uEt���l��b�'%�\����VR�q�)s}�� ���'���#Kd�Q���7⑹�<��p��Ja{Fi��c�C�#�L ɶ7K����W"B�lH�7���<�q��Q��JS�/�Q���;����*�F�Hj���r�T}�`+�W���ڵ���og׶��mD�����̹_�T�ȥF!�2w��U�!����*�Uq����Ӎ�f�7h�b�^� ��ӧ.�T`f�s"��I):C�>d1d}�ֱ+��B����PdCj(�f������D$ǕU� I+6Tp������K���B�I�}܈\�\r��m�Hӗ���&S>�*-�Z��(��jf
)���ݼ�,�s�+Cv�Og,�Ms����j��ެ	�(�SiDY
;,(Oc��-��`�ˢ�#	�/�ds9Uh��%� �����Л,Fmf,5��˲lQϦ�ZÊh�,�g�z����E�ju��F�V@���No�e,mm�J�,ȹ��d���*W�)�C4zV���m���m��<��h���S�Lu(Db���mA��}or��PE2�+��o^���:��b(����ٟ���z�$"!�,1#����2��9O:����t�,����d�;.q�<�-j��+
�Ҡ��K��Ԋ�e��d?��% ����{n�x�dT=��%��Jez�u����A�m������.k�9m��6;HF,󚦬�C�����L��q�ZyC�����`��]ł\��n���r�>�n�7�T�����u/�a��R,,f�a�j9�����V�g��>�C˻OK,��G�pa5G������<(�!$0�L�]��Ps�%#m=r���F+1*Mq{:��e�B QD�KH:Cۙ��OH6-�Lb%1ҧ4�B�	24�M��k�&5�����o�w,�-�8t��1�'��9wy�Pl�ӯr��jֳǉ�b/�}�g��?�����J�[gwY�8̘p����h�����,9h���G~8w����^a
f�TK�Dw�A7��DhP�D!�R���++y����?K?��&��L�`�������oλ�:R��S�����Ӡ���8��T�q[�Z�02ʼ�SA'Q	e�8����9�P��;{kdC��dHA�ٝ�j)�<8�X����aUO�>���^ɡ(Ȃ�*�jȪ��?�Ŋ6:�Q\b�W!�7H�մ$wF��7G�_t�����q5�B* �_��u��b8��E%:�����%A���y<zOz��[�@+��:�X����_���l���)U'����({���`*\I7�i��b�;��ծ\�6��eU��1j��.;��&n!�=�&�l=R Y\���¥Gc���%3�i<��h�����sI�i`�*�oK%�op��,�n;��E>�dQ۔yag	2�M��4��8-a�z��'d�,��?�U����#uT������\1���y��ym5��mz��}����|�%m� 7  &@�w��ٺ��͒uT�u4�=��0�4.7ak֨��u$]��8R��/|�4�z��z#aORL����4=ɍ���Hy�L��4�����C�Hp�ۂ��ا��s�U���܋Ig�P2a$������'�`�fv����j�z���yp#����(�I����qĠkeqe��������b_X�ذ�d�r��dW0u�6��"g���ژW�c���vx�4*<}s}���"_T��=�y ��eΏOOIP8b�Z�1�ԇ���T���y�9eeI�m��ރT
��S�RUe�>����͗kM]���C4�/p�)*�l4.���lŲ����IG=��Uwd�DSl�"�*�����a|M��
��y���w���gI�!��Z�'Rt�.8��*%��G�jxW�;�Q�UL޾�R�(�Q;爜�K L����i.j,�f��vM��RfA�V�?f	*�,�N�.}�K��#z�n9�@/=6�GZ)���=H-�C �@i-4Y5V�V}��H����_��0��hv�ڼn6�G��p��y�B66e7��T����@v��'�|m��9���2N��=�Y�(��>�rPR{�ʪ9<&����d�a��.t���ӝ7�OPF��׍?�/^-~�WB�����ռP� K���*([>u��N�uM�n״�6꺬���_:B��oȚ����:�F��]ˇJ�6����/�˕��Ae����x茅����%�q�(y�u�Ӂ��8���2��c�s�����:�Q���2}����w'ێrh�D�_������6�o�F�]"�+ |�7��[��s���{� �	+i@ƑG�2H����i�Y?�F� %ᕖ/,��!G:O(���'�?��g)�<�z��g�G%����?/����m�Q�K�|�lب���[&Aþ_�RvU]W%o�
ø�������TŸ���η`'Ͻn�����߯�[�/O�]-/��y_�ب���	yݶ���P��]�:��t>�}vRg��l:��u�n��*r]�������a�R\��{c�`�n} �A�@*���}Ā_Ѝ$�UL�aMF�5�v�����Iޠ�}dg��ݖ�A^d���;0�4k����6����,�[��[s�Gd�l�p���^�ݜ��0���ʎ:��!����h�����ru�}���l�������y<K��i����2N����R���Rh��ׂ2�AY.<2겮W�@eSc�~���Iv8z�M+k��鈅F��RU��!�*A�;�ƥ�ݙn���_���GM>4
=(�O}Iӭ%�ԧ�e�rNǍ���T���_ r����׃f�mÊЯ�fr�J}�9pC���䨑b��/���*�R�i����mlqKs���'��}�7g:�DAa|}wam=}��1��_�ut=�L;6́	��<Ɨ ���mMa�����vvҍAGy������vgʖ�/�g�p��z"�4�Vr@�1T�X��6�׀=h8���ٺe��ҕ�u:�f�3������{l����R��R��+�b.c2�N֙_��y(f���kV:bl��'��/�S-��*v7�m����Z�#���G>�=�zU_���ı9R���o0�MG�Y��ī[9�w.�gW�u��
��ZI��Ԯ��פ��`槕�\����H�EL=��5)&m���d�� �,�kMnf�sصPj���Fb�� +��{ȏby��/s(W�R�T����ܩdQǛ�\���{d\_s�H��w�B��%�ʾg��6 H70��JL��v`��Y����9��n٭M�����bEf������c�IP	��}��������z\�h���A�=n��2�XS���ҩ�б4�;��t;<r\[ᘏ��߯Bc���Y�O��2��XCtKG�W2Hߍ�`M�h��
�����Q��=���8�is�ĊB1!�O��t+� i���Vc�D���-d��+��q����h1P��pG����Wt!�:�)o^��o�U��£1%��J���pBy'��g"/�o*���T�ԍ蚖�R'T�\�ǅ
$�b�8�f�OO�.��u+j3��-��߼x��;p��      *	   �   x�]�Aj�0E��)�l.��쥚xR';)�������vJ��`���>����9��a�֜jM7��:IC���Zu�Uqp<;�d�Ag����_fk��v��oʶH�1�/�eA)����nrD�2[�����O��G�H m��\�b�}��"�c껮�_�>(DK��?�\��\�)=�g�^�1��?      '	      x���[�Ǖ-�����+���%�N5P�	���=�q"�CI�D)z(�ϯ?{�KVVM`&�;]%�+�Y�����q��I׳�XG�l���o��v�~hݪ����f���u��D�M����-[�7w�o��nO�d߮�~���~����l�l6��4�l6�]�;qq�}�s��}v�m֏M?{lw���4����Y'uVgE���6����v�ٗ���×������Ǉ�^�����|��ųG��{t�)؋��OqZ�?]U4wa����u��|۬ߵ�L�g^�7q|g��|��������u��y>3�4�f�(K����*)1'��9�痓9q�s`(n��P�1f��yWX=ų��p�n�O��y�$��!�����k��-�}�:6��P���{�׋�C��E�<��TTuYDUN4<�?���t�1OА�����ş�"���F���������L�٭l�ӛ��ў|���z�c������ڄU�J�U�&��	M�V���$�N4@�$����~����o�k��<Bw���c,_VWE�`j:�xX��=_Ο�e}�g�i�n�n�����n��>!|x�����	gs�e�ɭ~���fذqVUu��	M� S���3��R��y�6!��b&y�)}6ج�u����-����^�*�4�MR�J޶X�����GHZ��[l�O}�����3��Կu��珟d�0�JNS	�(B��W�IB����Y�~�i��i����/�_\7�P4�kurZ���?noyyn�fq��$����yѡG'LM[3T���}��tŪ(�i
tG���=Ǌ���9f��|����-�v��oڍ{��v���H3���_�qBsh��w	~��#~����;���]��m����O9�y�Ig	�رG8L��o_����(�Mp�^��F�1|�ʨ�m[�b�y��o�ݎ'Y� �h����џ���G�!Ʃ��ߝW��LTfE�K�p�y}��u2/��\�����Oq��qF飍��_��݁~i���v���oڷtS�r?T	�k��~�m�'(�]7�m��<;ZU�C�W�a��\��5�}q�F��$�"fe��c�5��P=s���Y��8�E��4����r��lD_ks۬0��}O_ߺ��ݾ����c:1�%<�fsۼ�Ln�<78k7z�����5�M�;���������3����k����_'��`��N1�7l͔qT&l��(�����'��On��ݶ;tr0U0fp��!pnj|L�ߧO�b�m��W]$�G�<%���b���t*1j�&���]�U��I��~�����t3�5��f��w�\����3b:���n�mo׍�>��YzOֺ��?/�v	mC�*뤬�T�J�
��o6E�[IG��l�Oa��UTYV���fH՞͜�@ɿğiz�혘����7I�h�k�ٷ�Yr]\��>��UY�I+�p��Ǉ��L:,�G��#b�dQо��Ftڴ��M7w�nO�[6;�n��M�@�v�v8jɠ�;���G�t�x�>��z�vG�'�w�&u���$/h/�NN�]����S�4��ٰ�Q4k谤Ol~7ǭ�/;����c�}YP��5������ػ�MG�"�u|�Vz�^���kL茥�
7��3|���钦l�)nw�&��^Ԥ,ˈUFՌ����/s�)�K�I8�l��<u{.n
�x�l��hN��Yoڞ��f���lc������{l�=���Wv��vq#��S-����F���z�u1��{�f����yˆX�w}�[����C�~R�\��C�����[]0�r�pt��l��:����z�z�l�p#���8/�5;BF�l�n��m7m�|ڻf�?��.�7�I���D���x+��А)|:ł����<�����>&���3���@I���<V����D,>���4�w;��z�U�[<��&9��5ZN�����M�����?���c6_��Q!6��o���t?x�n�B��c^3���^6��A��n~;ߵ��S��-����n��v�?i�î�M�י/�.��Щ��a������}��~�����*پn2�9dC�s,!��S�ҡK�%sM�5筜��"�І�=@��i��*]�D'_H���3�Ps`G`!���_���-W">`:�ؤ��  ��ڬ�=�iF�,Z�C4f�����f��=)r�q�n�j6w����ʭ���]pH2���B������+�[�B�/�F�$����m�힚�n��8"8�X�
1�pb�(���X7{ŉ̱J�D'�9�ǧ����,��
4�2~�zf��� Ss;>[Ȩ�����J�y.k9-�gX���':�&kz�U&%�JW���Yr�	�μx�;[�L���8���5�es	PN��g���4��4�h[�(n���[�&����Y���l��i|�?�a�~���ZFv��I|���ݑ͏��ܒ���)��t|9>�i��*-��U����d���9�"�A�F��,ڄ�_���C�v�a�uϲ��[��>|ߞ��>fUUyΞ�A�����4�v��' Q��xZ�6�f��������exGV�ܔ�^	��a�yǁ��b}\6K�	�̧�$�^Tus����m���Bӱ�+诡�v�K�ʍO�v�s4YG5M��͛���9�B.?NY.6��R�Š�b��M�w�I��o|r��I�H"��\��Ә=��*�̱�.���75����M��S� ���ҴL��Q5�}�j���Y�-���>"\(�������|��G�p����M�|ٛ��-�1���'E�]��?���qBW#�T2*�����n���7���$p�,
i�M��[;�G�+�I���Z���d���R@���R�u��߻T�Ѫ���������d���_�2����3�����IWĪ۟s&��^]܏��
Z�az5��?��0�d�p��ld)����֛�~����Y�鸧���y��l��nٽi�mCW#N���6�|�5FӸ��\���3#Ob���������A]F����QNvO��ʐ�Jw��'7cp�F�����u=�	�7�:�DwW�%lD�0��ٟf��O�ucd�/�4�Sv�t�.Y2L��n�Ͳ�2��S�_8��hk�%_y��K�a�<�(��V��2|���팓';#wK7F�O1[�ኒl�Gճ]C�n~?��䭑�F��p�Wp`�����w��?���nV�?�����Nx��WEUF����6�2\jr��o�O"�v�(|w�&���*Jr�K8�:*�^\t����%^���ń}��Ō��`����ﱳ����z�\v�Rsu˺����3�����N]'N�����������<�(�o��Wa�y�J����\��4�f�o�y�:�+E]�V�r�1�Ж��\D����n6xA��Ҋ�/�f��Dmf:H���1��<ü�լ�����z6q]��O�Bm���lf �8�ܰ2���i�g�8UEVO�L����S�@��ڢ�첚��UR;ҟg�Ί5��3Y��G���XԻ/'�*gCSA���K�2\׺	\}�9�EJ�/eT���������߱[$g�I<$�<M�O�ˆ��Mc,���r�4���+�=�~]�������Ӽ���K�FgB��t&�B|�2��B2�%I��O��3}{4�wm��X8%6g��ũN��2`�K����t#	�ҏ��p8bҹ12yȆ�<�_N�E׾ih-u��#�垖�0-�'��k�s5>׏�Ź<����CS=GG2<ݥ�!���<e+;�����Y�Z���C"��omV|��i�i�!��j�m�a6:_�	�l�����S���t�3�9]�h��چ�?fC�>���`h�(�u�#���əd��T��!�h7!��wdy�C�[�r}h�f�v���^a�F�I�{�������N���<%:]�������揾G���}�Iɝ[6��߭���㏙n��Nw���>�2�����VS����O�;�kP�9��䅲�Ȓ���,�͚̿��Vq�/�u�*��=�?f���f7��/�IٚA�>���R    �	��?~:��T�n�B�����"����.�}�irN���g�����̤iv;�bhNlT ��W�]ݜW0�*^D�|��L㣘hֻ�0��Lk|�t�f��EViH! ���S���v9Cq���\)іDf�,-,s�[XG��P�ٙ�saQ:i�����0�����A�	�	t��[��q�PG�ls\!�O��i��.9L?a�?ܧ�(�ϥ��y9��v8mED�=�����$l�+ �[7,ϰ�1��e�E�Q�n--��H�=y����^�9Y��;48�	0\}��f,��-�$M�"�#Ӏ�˷_N��v%*D���!�	0l�6�oU��~t]��R�]�ke��#�lR�B���I�Ds!��LrrN�=�`�<�f8�ȕ1TTY�mk�$��?���Xf����h`7z�Ls�=+O����,�X�� ��VD����q`����l�E%m?�x:*�H\�f����/��K#�����x)�>$kFNx�d�\I���.ϊ*�j ������Pz���Y��y3�FGi�^�������||ҏ�n.-6[�[`�C���ye0�VS��/SQ�7o���5���}����)S�rF�.�<gV [�:�F����Z�A�{�w3�4�I���������S���*�k���fw�QzG
7h�I�G����O�?�D3�hȍ���┬|�6*��\�d����#�#�8@g�~z �~흋8���z��IQ�Q�p$ΰA��'ǋ�so���@*"O���H��w=}�j��]�0c��������~��1יiYI�^�!k0���?&����e���Q��<T��RaNl����9���r�ڭ�!/��q�x��������E�^���"+��h[����n>��h�s!����bc�:Jgl>/:���12�=�)����f`8��m�;�{���]M�a�Q��0�λ����(`:iB��7��}S�0�tT�n�� n�V�kj�腋Qx2/>��z��#���=��a�V���q�py0b.!��|G�W���#�#7�!�|1o����w���Q�F�����/���{�5��t�5E�C�*.�*�9�#����&�)V��[`9�*N��WGUp�����0;��r��s
��j�F����ӏ*�<�Oh��Xi�D	.b��.0���4$�X�d �a#�������hֻ������p�݌(}'U(v����r)����|��_
�>��x5A_�迍�N��
[�o�S
�T`fA(jE�}xt�7��wj�]�DK�@�;�RD���N�)�4#���XQy��b��ֲ�ҽ0䭬�l�r��us���-9d��Tn�Zc1Rb�8�tS�V�g��c�!�n�g~�:>����5�p�_�c�N8�4�A�9N��-���慥���b��=����}�Z�)p��l���=�����أ��շ��h�əJ�ۃM���g�4��mB&�E���%N3�.O`����{0�xW	���:m�y�m�ұ�ײQ5�9�O���eOr����H]���gy�
�[���͋Qn���f�-8��u"�`�
!�Ο���BY2S�.P����zc��kꟼ��l1P�?�ۙ��*g	�{rc���/:���/��g�W2>�JT�ѿ�("�9�4�w��qpe� ���p���^zQ�U����/�'�.�V����#���p'�(��.�$�!�D��^͚/��}DR����� ��|�$ˋ����㗏/1!�����x����>���d5��3��*8�A�S�EaL�H�+�~|�.�\�hU�Q](�9���-p�0;�IV�'?dg).�޺�2�k��͐�>7��0��_ޤ�	,B{�#��b&�"E�2������O�����@Ss���p�P�ő�]�ك��cgWƣ|�l��;�IK�gץ���3)�7P����	k�/�=�n���d��ѕ}(��t��vy��0�����8�=u��h�������9�eg�Z�k?j!��	I�Hā�c���qR�c���D����`��ȣ���:�8컟?��6䥆�����u��jI�c��5����E�2��VKc/����=rS�q�s�����gh2��������DE�:o�F�lE�ĳ��@�����ْ�T�����g������LLoJ�/����p�e����%���1oj�lj:h�/��h]f�nD�I3FG٬�h8Et�m�n���l#�RS�Ql�/�c�8�,���]���-]�:�+��]�I"�

+����ů���8�Oi/�Q��>"e�e��3���/��9Jx�Ȑ2dsm�iN�v��OXV��=�ٮ��ϥ�4��4a§���gX}���;�|�x�d�I1P1�lm�����A��9���v�B)B�eoP�JX4b�2��w�t���3��w>�����E��
et�`F�HC_�x��=�:�E*<G�5���Q7-ۨD}�{~��X�1���ܐ��,2a %�����\�k���[t%
�p�ꈮ{$⠩�Bf��尐��⥐Ej���U�Y>�Xx,aZ�[��͵0V�'+<g	�i7�����b��)���ݒ	s|�ɝy�j)��V)��Q\a"��t1$\�xf�������s�-��~�?)����e{�,��AܼyXw���H�H�d��`C�*�f}q�(f�'��7LX�Ɂ����Q�������]sX�︪�8�r���p�^��{u?/J3�*/�r�W�}��8EcCB��V�y'H�4D��8PtmTY�"���o|7b�#U��(���P���4_E����wo�L= Rk����J6F����2ai{�	��<�g��ô4��=~gOq�\����hA��p7�7+���ѷ�-��ne�;ɪ4I�\������C��#�(�N3r�@y��`�}{DɈ�;�>�V�S<�ؕ�g��/��Q�E��Bʃ
��g�P29���������iUD� 눼��gF�\>�NTX��xg��8���p�oָ>�W�4�P��XH,�2�!��(����SvPFۢ�	�(+v���wtB=;V���j�`��3�8��bsN��a�ғYC��A����+��Qf�	����o�|=! �C)vx��F�$1�MtTd�~����.�C�%򖍒��A�2�A�Vú�J�[oE��Y��W�:�)ս2�I�ƀ�7�Qjʹ(=�A��A���������f����/^�����]���(�]��T�Ry�� Q�����FF������!�9.�!><�a��\���#%4������4�!�R^��
�P��������O㼄�ף�2+q=H��I�#P�Ѡ�DDpp�ݮ���y���7�� Q��r�k�*T�X:R!���Ͽ���0������Y���4���Q�+%_x�����F&Z����G��=9/�A��*�H����J���	���E����Q�˅02
+���V��8�Ĥ���8��DPAR�V�qTW�A�����q�&d��Ύ_@D�z��kJlsn�|x��/T�g\q.#d�P��r!��B��(K�h��̈́o.U�e��1"V��>Ut�7����i��jp�v׿��\U���QGyX��!��+�Y�pVvZ-s�X�TP�Z��eZ�4�aq�Y3�������<$ᨬ��x�QA�v���,��msw��o�lD�Ȝ�=#�el�zץ����F�
(�T�����E�Pݭ�B�oD-
Nwl���[$Ii-���yc��FU��[�Zd�,�[!H�6�.���
F��� �#gC��3��o#���9�6	��@++CD:�f"��{B��S�(!��3�W9���̤%����d~%�D6KO��һ�`���2�ch��v2�s@����2�"���F����?�[VI[�[�k_!����P���̓��V�'��]*�Lh�}�_��r3���v<�ty    ocu�_�q݊����:(Y�5>��(3aD\��+�����E�櫪�e{�c��ۇ3r��$���S����\�w�(�5w�Y����	���2�}i�cGJ��K+(��,����I�{c ���9B	@t�#�o��t��T�X��Ǎʕ��f�^�X_^ӞmK��!�(���'�{�k�k������TU��6�ե�P�K��V�!�Q�N8�bT��jE.��}W��B��g����|E%�0SHK���/��"�"��S �a2{�|�J��}�Q4�w�C�9�g
4k���V?�ow{�j��6X~r �����tS�)��޵
�p?���`�k����$#�'9�Ȩ�m�o�gx�Lb!l�Ѫ���Zv4��#h��\�v���pd�S8:��h��H�1^a\�?�L&�8݃q�#�
������":���W�D�>T�>�H����^��:?��ZDW(G��������AJ�C�'ɘ!1�ClM��PVF[SGQ�$x;��������8Z�W�Π����m~�Rk���۱��/,��D�HG��Y۬�!Y,��(�uy���VڇFZ40!e�l�$�W�Ô�Zw_u���	F�qC|�Qt�3ڏ���`��+2����|�����Ln<�������2�<� J�/�4�S�	(�Η�0l�t�7�~��=�X�3��|���I=�ّ�c��=��{�f\�^���4��Һ����ѭ'���)H���p&rV|�QJ���)=����/c�[�o�=J|z��Ze�C̓��$،�:��N�^{��
��r�s�S�tT��?.�x`��pˆN��+1�p�B�4/�n#U�BJgl�Uy�7��8��<0�B(�I��eT��������F���%��/GR����r��v�f㬴��j� E�)&�4����>MB�6nrx�WXN�P�*�2"�oM��[\͛�3��!=d
k�f��u�R>�X͗�F,1geły*p>~�Dۀ���)Jy�L�<)�xf�����9z�x2C�f&&��۩k0iVR�З߼�Q��a��o0Cq�'S���#�1��mT�hS#��5#KnЯ��P����$���5G�bAi~��zn�9��?r�hD9�������bX�vZ42E�g�Q���Һ��A���GЍ+<Q(�L2ɮ���!�k��Í/A����&P�a��?���'Xm̎�3VX;PG�I��dn�Jh�����#VI�{B\8L�T�AN(84aX�����T��a�8{ĹP�K���m/��3M��[�k�N�)֒�D|�I�?YRg%�*�_�^���s�h�s�E�,��F%�����w1
��~)Ӄ������M��ڟ1��T,/����ve5�d�x\<�N�ۘ+�@�m	��ox�A:"g6��t<���� �}lH���J�bJ����}��#�D��WF��D�?*�
����ש/�a�>a����ǚ�"łQ2k�7�眉:�ݲ�3l�D��g&�pEř�p����#	���kr�� 1N��sh���t��q�S�kr)����[Ms9�)���"�l�ŗ|M����_plV��jgd���lX����Cq�YY���Q:�E(���=�˾�s������ڜ46�%��ua��}s�}S�F�4G�K�	���5ˣ(�ZV�J�A��,�A�D�G��>Ʒ�|��0��Rte���$��*6͓���5z°�iQv34Uj���݅JeݚER�U��"�0��~;��Uߑ���;>�cs���i[2J�r�9xmd�=��ޚ�A7�mO-p�a�|R�P�B���fk��S��4��~�����yԾ�ht#E)Wq�(�ݵ�hܰ�o�5�z-�����eXv��L����f��xฦ�
]��f�匴/�0럿���9fZ�	f{N�\��
�[F�R���՚�f��5��W�ercz������	�Zqx�Z!G��@~�߿�2��5�?E,T`�>��,��u�tT�6�����y?VG�2�b#��`��ֽ�%L�~�<����	i%g����q��p�#�:i��Hx�t��S�ƶ��.i+7�v_������>�v}bu%)z�5��hh���?�z�(�s.��'� 1/�U��R�;�w�����p�ͱ��$Xv�r�&x����V�E�N��]p��]A��D ��E#�b�`��IhW���s�����}=|���=&�of�����+�5=}S�Q�$�a(���Hr�R9¨ig�P�S&?Eq��\z(�ԋA+�ț�������(�B20�?7a4!�tY�?��;B�W3}D�4�o�u^��\��\�D;yh%�o��B��-��n���}G��qy����:�\%I��I�
���
3�g
|5#��� ��b��zgt1�(�|ɣ��켄�!�)�ϟg��B��WN{�eU��/��G��	Y]gO�!K�����N��I�:Jf�́)�5���ow���Z�M�-�v��h"�Y�\�O�XA� C��N�rS=��sc�����q��,�Qi��%r�{Oqs�C%c)5��cc��̌����M��ǆV�!�{d�3������H����F��(���$�d���~�E���̭<m;
`�2�r`\u�#��[,�8�j$�z0d�r���%��|�G)m�����6D�����Bϒ�/ֲ�#m"r������`*ԁ4)Si�hpb��M�SbC\�V��$	�+�t��������𓑴�=4Ē\T��g���Zԣ�e���슨W?Bh�u�S0�s��>ܺ�=�=��)"�d��(�IE+*���U����Gc�B�s�c1��*�b`1�'�P�����c�jۅ;�fo�ξ�r���Qb��^مՐ\#A'����MW��-'�>���~�Q�~��J��]�Zav��t���px�-�97jǬ��u۬^����/�UD�B���"2H�+w��#-�)��rб��,�!�\Ie��z�4��n=��*�c�#����E����	�����(C��B�d���|�N�b�kd�$	_�r���y�j֨����K�dWv-�!I�0M �{T�4��3�9�xީ^RQ�v?~Р�t���'~8�e����1����l��htEk7X��3c5��S���d�ƨ,�	��U�p�!U��!�h�T�`���\S[aՃ�n��74����������^LӌsQ
��?�|Ɛ��^��y2�>�a�x>�eTh�����������sP1���~�ku�\��&%�A�r�$0�?��;��s2{�NX��cA�jC(�<
�n�D\o׽w b��|�θo��X^Bw�pJz�&��@31\�>�cj�����)b}c����jI_�@>�x���������Q�8*�ɩ�J�[Rq��p��O��*��0>
+Ⱥҳ���hV=���_�`��t3>�Va����35R���%��o�}���t��Z������Q���gJ8�G%�A����oh�U	r:�h7�u� �Ήu�؂�,�+�&�(�엌{Ȍ���Ƚ1v]��,K��*��
W~���4'�p�ޅp��"[��z����λ�_9����/J�ߑM&́��J����]�{�����J��/��N�'Z�a��x�s�f�e9������.��R߲i����3��O�J_�'��a���9��5[����V������/��R�*PA�
��Cޫ�,�x��(,,c�,W�U��k%F�Mj�����'�i6K����W���)Z>�Џ�a�*�P��#ۊ�Vn�6x���������(�5�#-H�<�}�����0(E��i�t^��)���t�qx���dN�#\��o�"�U�h�RkTo��1T�N������Y�����J���z�m[�$!7��Jq�$��*J�؆'L$�<��5������0�ӺH�F0a�>�w�	���ΥpR�1�;�ģ~�7å8L�b{�#�LC� �lˏ�q.��{@�s�����m4��ѽeM��AHSΔ��n    ����a�w�mQ��o.Y4���e�f��(�ޟi�t	+���=���9K�N����f�q��}���ar���\)��k����D��<D��LI%��J�	��$&�m��m{د�&�d���˄N�B%����
P]U!.��JH�
3TW�|���@Pׅ���\u���£Xrx�:�S&\}?(v��Co;���1]�p��[��>`lAF����f�&`5�G�T\b[F:J-���}�l��+X����-M0��	�
���Tlu��}�4:ͰSc�����R�`K裂��tc�6*g\���-h�H����Gv�Y �w�
 m���fsیU���~�1.��s��7��p������
���V	'��dY���R�N����Q�C�j0� �y�Ik E�ӯ���Hc�#CeΕ�kU�]�KS����Lݵ�{yBn��o@]��ٚŨθ�߹�m�N>�T�X�k�"G�,�n�O��*6����+�D`Hh��ٍ��埪�$��oYe�[�3'f.4�s�w��i\f[a�K5]B��	,u4�30�J~��+	�76���W0羕l<`T�!&i�����K�u>h�k�.���(�Jfl6���������ʭ��p�\���э�1�Q���	ΣR��闀̥7͡{�_��������|Y�m8�(������qᓞ.���X� I���F1a����λ�ad�W�7� �Fφ?����nL^��\�+V����e9R�G�c�_��(�R!C�?�lO�VQ#nô
����{.��l���M�n}�ﮥ�\aY�K�U�T8��÷��r[���#6�S��3�oa����i�ݽm�쨇#��8y1�3AJU�-�oxq����@��oNpE<Ò-���b�u�5��HG���u�ؗ����~e�l��=Sb���EQc�8Uh]17��jS5�4�-�����MF��C��I����(�ycQ�8`IxI�A��Z��Tŉ�I'wf(�_��O�道��S�2���aB�lf��aBɭ��dx��|"lC�����jf�L�r��#�p�a�]�FX9\\�Q�6��ŧ�9��c��ލ�/�����J�a�X��s���fE?ԲYo|�D��54m��a�L%C/k/Z-�ۄ4��
�h�O}C�ɽuS���j�Iċ�#�@���f�,�I�1;�5�V'T�B��Y�,�%t��G!�K�����̐�5��>�Q�I^vt���HD��6��dX��7⛙ī���B`*�@��s���\�����z"O��]�q���73F� ��җ���O��iq��~�P,}��PV�;����6��$�Y��P����٫�b�=Qz�3l����q�4��<o�����v!�;r��̴�3K���3N�U���܅����q�!%"��1�E�f�M��B�F����<�x!Z2��;Q�d

�_�G0a�7��:V�ܘ�
	��;�f�3�.�W�4�N+\�u��^�:O*�y�Q9
Ï�r�#8e���|t#�\�.�#bU���4H�-<R��&��s-J9������{�f�)_, Q����G����^��3����^;���T���,dP��<�􆣿Mb��Y�]N�NW*����4��1�Jв
�>mZ�����.��T����0/���`n�Jfʐ�N���1��
=��Q�e<Vs����
(��A��/���h,!�_�y���*nkiPBыߧN�*����c�I� !�h##�:�&�g%ii�Q��v�=9�������3�T�٥u���y-����zF�jt%�C��C���uf"���+��na�v�G�7����:�0.@�@�rQ?��N2�:/�L*�"<9�0	�0R��C���k��׬\&����2��C��u�a9���Z�P�,�d��r��V
�5�\~�0����
�ҽu#���%�Ҋ�GY��J������Ҵ1��]�B����4ʃ[_�g?�ĕB�[7���C �-��f�'����V�L'}��޻M";�_��C�h+Ҽ��q^#;kpB�y�ɑmcm����]g�\��5�EG'�t���]#�LF�I
;��qN�?6d>_f8�dN�?+	�RFbş_,�ʐ�2��L���������+2-����g��-4� �z��}ߴ�j�tEUH�����i�8*�ME��z�Q�l�{�i�����M��v�$��AF���꧔^�K4J��I����z���,V����l~Td�Jo���^v���.� ?YG�l��>իi�a���6�S��]wyk�!�Dq,�s�t���ƪ��(�eV�>��ْA��>����~m֥��#��FIX`,��8�}�w�$��f����ս�XOB�g���C"�;.�Qu��nV��Q4ې�2��mֽZ�������v�����U7��5��kKX�p<>�����p1Pvt�<�W$=0*���0_�Pݹݚ|�Ʒ��<u��"�xDNW׋��4J]3H�C_�˞�o�DFg��P���ǳ���'�<������*���:���������bσ�I�e̹B���[�F�����mVИ�y��cײȹv����4K`��FX�1�����UF�1���v�^��Dzt�ie��gW��傦�Y�¼�is;�!���0���*&��FyDʦ�6��"
��)�,�7X��pfmU�o��y�W1Yժ�jԤ',.*Ȃ���E Pіk�^�ǎ?��\tT
}��5Њv���ǳ��S�G��{�Z#��@ k���$K�uD��q�P����L���\����;��"�Fd}9i����gjgcD_
l��4�s]hwb!�s'ؐ?�9S+#t�Ȝt��o4�a�t�7��
JPP�r�Q�'
9^
)���^,���OV0Kɠq��'�%Ɗ���=����M�5�7�Q1k6�2(t*"f�r�b����}�G�X�vK&��~�)��n�+4��:�	���$�Y��x��2-#I�Zk�tT��C�xi�`�b��6&%��F3��h�kH�� �yТ�7H�q���s$|g�ݹ �>�-���I]d��؈�'?E5���h?��EڂQ�<��HB7�PL�9y���A����H���"|�Q�M���f�P;�Ȃ����Օ-��v��zf�Δ�}rS�G�Ʌ���FNcZI�3�|��d�kk<l҈#��T=�G�=\��}�^�k�/��E#+}(!V��%p�,M�ͦ��ZC�[/�s����(�����=Y���i!U�n�U���Ly�X�-��er�y�t3���o�A"*f:�P���_��ͣȺXcH���,4�eb��L26��=�Ld�����i�^��E�R����X�Yٌ��Uy�
����Nؑ<2Eǳѧl��,S������Aݎ��w���	%���E�y<�B_��Z����U�t�G�(�P��?&�3�t⎑o�Y���,��"��AZ�����ׅ9>���מ�I|����힭y�U�d"�PTj�c.$��I��ʍ�.1��ydh�HZ�+�#�����Kڡ�:޽oFZNK��\u��^���-�B��.)�Nwʧizz�D��d���Q9�"�ǵaڝҎT�>�G�@Dgh�K�a�ۏ_ip�C��
+��~�F=�a��8�s>Y�a�EA�W��WUd*C/p>Ћ���˝�bq�k��JV+30a%�?'ɇ�0,D��'�:p̍G�ր�PiO�4֓��w����c���h�y����z�n)�<符����\���pY:��1ʌ��Ö|�Q4
t'瞎	���}���O�C��L��ɵ�&�������tb�Z*Pq¨���o�.��`"����h=>|����D��$3RҵU�ź��
q��a������W(pM3�+/�B(���
�2
+����ԛ���l�E��X;2�Vtj�4��ݓ����W��)̣<a�/E����7���@0�w    #��Ꮰ�A�#<�f����r�Y���v�N�О�;O��^��\
����c��"˭�=(c�q&�-;�aB�k
���>>�!OG5T���f�Z�]jt���ʭ�Z�;p��G=\�9n!{� }��s�$��	��	��Ck+@�З�삼攼�r��"�˩�k�ʁz�@�5؊�����]�T{�*���`R��a�GtN0k��T[���#�_��>�^��������Pn����n/�V6zJK_�ᯩ����D�>W��Q5Uɤ��i�=�L|���h�@r��e�7�ˌ��Dmi�,o�\�ŷ��:U\R� 6��3�yɿ{�]PFІ7�~�o`#����9���-��4�#�G\����I���W��e�3�h��H���/�j��٤������aU˘��U-eT���f�ʶ�񲖹Vؾv��➩O��_j>0�?5O�p�&L�������H#>%���v5�\!sn�^r7xg�G|^�`����O�7ѫ�;S�ɡ#��$����[�̑��T��C2�y�4f��x��͞vᆮ�_�;.���0�TUc�'����|![�R��'3_5����Un�$���1�DsV�Q̡(���0	���I������OT�,�~��I���x�O5=�L"Bl܄��M2�hJ�$P�v�XA87�8���٠��w!,{h_c^��H�zv�'���n�\w�X+�7��3����v�-��[����Ģ2j��V��?M���ݹ���p?�2�SG�l�maY?	�g��m΂Y� �+9E<�5;��Z{�U�1�j����.z��x��=�;$�� *������gڄP�{b�6�b6WtTm5���&a��������I5��{�_MV*�Cnڙ+D�Ye������Y#�$��G��?)>l��n���Z��Z��]�3іJo��}��(}}U\4�#˂�H�%��] �_`U[��u�s�a�T��՞r�`*K�O��0X�wa>㮉�k	���]�<�lNi\��Є*��\�c��.��f�B��[��(�^�Ԥ�n���5�I�I�S�&^hj�ʇ+% ��A�~�pKS���'�U}97A�/8�B,t�mĔ��g�9�v���?t�����m��N����=�\Ij�N`�|�N��s����������J]F���ەU��z�B<�����WZ{��[J"�|�]\�ܚHH^pm>PA�/����LdIS�ܺ1���]7�Q�Y�d������/MÚ��m�MЃ�s�"�
}/�*j�=y`��� TV����3�Z+�6��7E��y��h±�M���i��x�&�v
��ӂ�e:�_�7��`}�k:j��Rt#9�c�8՘ |΅����,�J�U��u���)�EM�§����������4^di1�/�D>���w��	}��~�1���p����a�?��"ճ۞~�y�m�6"����ta�sg	x9�3��N�_I�Ĺ��EV��� 
"��ٛ���J���E�e�:�9���->��R��Nx�b��ƴH"O�5��W!7�
�K�Ўȹ�f��}z9�)�Qo?o�gK�fP�,�:(n7���͓�{�\ �KJ�z�f ���x���J$�b$��~<�'���+���P -��R:��f�.,�ޝd�eb^��{b'���	U�K�j� �{l�x�o���vZ"�ޝ��w��]��r�5d�<��4W�t)�;Qk�-�����=8�X��o\�F��\>KK�HBʶd�n8���D��#�p��Lcx#\�p��j�M;�@n3��CP�K�`�	?�n�>���B��hs �mǾ�[+�Je:���7���H�*1�Iڹ	d},�[�6�W��=R��>�Z�T'��u෢�Ͷ��-�Le�,���juU�*�f�/F�KlB��lt:���[_F�A���Q4�4�v٠��i����v�m�`�*,�x\z�DW�7B�-�nb$p%NU�p� 0*�l~ўs�1rRVT�٪�{�4Xˮ�HR�Q����;n�����z�RXrM/�<O�D���{d�wx#����"�ryT�T��`���٫�>y\�F��Z��x��=�VvM^�/j�	��ЏgR�梉	�ǲ�S�q9\�qȣx����e���U�>�u�qeP��+K�M����1��+�h�2�朐1����˔i�y�Ph!/T�8�A�Q>k���84z	E���o�V���<����16�T]���o	��
Ms�ә��O�NuC�GR�xx������� F��M�*�I��\:"b�h��q ��W��Ѻ��B�Z���s���Q�NVT�&l�	T{�s���I�lc�]/���/MVkk�H��Y����lߟ]R��; wS�Ҏd��L_�!=�3�pBi�	ry���s2B/�Q6{ ��]0�	�+�ݲ5�YO�ML�y�wO������1i>�t��oT�����@��<�����%(hf�����#�y	���
-�{�o��R�#dG�\��_Z��6���8��8�5	��+i��p�\̧��)~��7R_���N���E�Q^K�	�� ��<��J,�(�oX�6t�敻"��F C��t�`<�۩atJ�XN�!ox��Q���6��F=�q[gV�MҀ��h{��}�nBʖ��|�X9�����9�����9;�ӭ�5nٿ�`y6H2���t1ߒA|x>!�H�<�����O�;��QϜ]�ݞ��佮�0_�EZs�GA��^_&Q9�'p����竨�4ֺ!�A`veD�I�/2lOb�z��G��P��vz��=%E����Y� �����q��B�+���PBԌ{�(
R_ZE��2����r1�W��2@�=5&��D�"Nc�N�G�Ђ�i9�9ih�(2��5�,-�x���m���J��K����x�V�T��,O-�b�(Ll�4�aj���i2����ltɲ��lO���n����DIP�j}�Q��.�B�w
�@%����C@6�:��b��O*˳HV�G}�;ز�#��{���HMو���Mv%K�"AG��#	ݒ�M5�<���b�',Ƙ� ��>ţB	|⃭�a+�B�L�`ÙLD< Un��K�V���3_J����C�a�����?d�0a�� VG��K��t�R!��k��*�I������ԯ|5�UfN{
����|�Yu��;$o]T��G��{-���J-�'���Q�x�s�YItv��R�s�ʙ�Һ�ιK��I��y�����5����0�6��F��pܱ�iw�0�B�¾Q���0�fi>a�?X	l~�PP]��'�ob��e�cJ�g�P���C>/�-~�]�(�A��9�̆�[2�}��Q�J����ϱEb��5B,�1��s~?m�aA�[b�O��1�!9��d��H�f��B9��Q`!�;� �g�7օl��o C[q�f|g?NU�CJ�UHi7)eW�y�md_����R&B��Xp�{���Gw�_���?Ͻ��5�ĉe���t��i�;��{����vaƨ��ܮ�l�@f�N���hh
{��F%��Yp�Q;,.��(��k��0���{��c�ۇcI���'��yu��!�i�h�=g6�f	�����%��;�=B�/��'�=��\�]{-^:<X�P�������MrO���Q�qG��r���wʟ�p���XӉ��'�a�%�����+��%Rw��O�_&��� �4�S.��Q�9��z��6�����d�=��:��8�}Z�1�5�wH����(�ܴy�\Fڣ�������d�'�p�<c��F'[�6T�X�5$�dե��0��~�m�,� mR��1�����x��P��N6Wu�]gg��#��;�Խ"��Ȣ��,�s�h�њ}!�������>S��YL<
D`nU:}�6:�yZ��=*ڃҭ�F�y��.�J�k�u��,�ZH"�/�
|�:X��ߺ1���&��4�%чQ2�V�v�|:��u2�i/��ۆ����[��n��ב[��\��	�!������k"���r��W�E)����͂�ϥۚ�m�^ ��    �N	*�ɐ�vj��킺1�Es�z%�k���_���<��k:�5�ñ��f_v\3��^L:�5�����rKPi&�CS+$��]��+t�⍫�h�-��/�����*��B{���^�iR��Wm��v���	«;ɖ�ʐ
5�!Qh��M���n2���@<<��q�b�h�� a�PA�q8�s�nK7X��x{�9߸JK��`��{���M�?�n[��å#/�#M�d�Ź�?��B�S�B��/t�>4�Pl䟸��}�M��`Z{|a�L��*�77n�ܿ�s9����2���Od�����g=���I���rlڄ7A��هPTA�ӧ�̑����G7�;����u��w!����;h��XY�ߵO���n1��@h��w�&Ltb��C��O�jy���%q��\�k8����H� �ɕq�XX� �U�Z�QEk��z�s6�N*,�e�U��� U0����x:����;��qe��uYJ�?0X��}���j�
`G�������i��p�t�q��Q�. y�|
�����hL?я�)6W�*ar��4H_��f���m�t��t[7F��f�1A�3�a唊�%I���	��U+�޶�tt5t�\g,�e\`�����p�h#�'�n�?f8E%���}w`!��	��8�V���n0G<���t��9�Gj�g#���?}��c�۹ ���k�g�
m4XK[x�R�AHc����q�Tc��8�Ⱦ.��Y'�*f��A	���燑j-��ul�ܑIN�4THm�B	/K�I��W�!��'�����J�R��r�5�O S��3\S 6����xH2mO@�s!A��R�Q�9�+.0�)`۰:�Ê���ݺJ�8�Hj��к��M�f����=����'jC�k��]��P�>1i�R+d��	��,��>&�d���mg���=Xѯ%N�2��w�(����3�^6�}n��ho���|�L*1ʴ��)� �D5���Ц{\��.�{�R]v��:v�mL�y�*�
Cn�O�D�����.��#���ᨣ���1=��<Ѫ���װ����	�����x���	f�г4���PhC�/g�q�A���M0�slU:�n����=�eDgJ�6 �4Qsެw����<� +���=�+�a��E���������$�-h�����`*2$Vm͞���$۶����4�]���p�99���zcEs���#W�%�\}8�����(ݭ����l^�i��96�=���? ݐ��u�7�Ɂor�+�-g�:[���h�އ`U�h�{�!��Ǘ8�Fqyt�i�Cܻ�q�
��M|}��H����NW�T6e��I5ļC��_?��3M����X��BLK�8�6<��v�J(x��cl�kt�B`l���m	/D"�h�y�ݘ��qn�Bi�p@�1k���Zs�z���=咓<�*�G�7�pǽ�v �$R�O%����i�W��"��dӇ�2�*���;+��i�kXC��&��zw
^^��X!���RG�l����wt A������ݞxI�ē���&$����N����\�B�>4�����߾���M�s�2�˦C'�d:��!ʤ�R�c��zY0��W���I���	ӯ���v\�.r�v0ץ�����i�z
��!6lA����R+X��c��9�ҡ�7�Б�2<�jMة^;6�qdƲ�w�\he|�%)�_R@���jPH��2q��nyp#��P�{�5�.�������Ż�k�Q��@�o��,���Ӷ�'ڦ�++�'�]n��ֲ5]����K�_� �A'r8����ZC�>� ���	ؑ<R��2�.!� ��҃�
���K$wQ,G>9�����_>��?b�v8k�A�iG˸C���AS
N�
Z��'�K�%�Cm�A���Z̧�.I�CT\&���Լ)�8JPf������,ԕ��9�;��j�	�:�o����yK���Г$u��'S�;�ͺ�����#]��.E�5}�$OS�;��D��~��(�8'@�냥Y�>�PQ�Y���b���''apݤM������+�WG���G,��R�]�պ��-,#mX����4�w"yc�Go8'�(�2
�������`W-�;K�j�4���S�s�F��<�-���/T�Aη��)���;wn�:x#d$r�#�Nƨ����wO&�� ��c���i���sI��� �EeW�b=�r�N�A�Xû�ES�޹�,���6�	�����s� w�w��l�Y�AJ�溌~�F��ʦ@�)�(�⇳<�r�����>��{IM�cT�:�r��4�畦���y�mm�q[��e	�GI�Ν~gL�a��_� �7�������EM6
�$U&�%}��硫�KG' �:lU�h̎Xh�3�{�ܵd�EȄ-�
ʝ���ݪ���Mg��p��*���2���Sr8w�	ڥ�*�]�ni�X��T ��Qfx�}���X)1�w�tA��[X�]x���}9��$-��\G���s����V���L(�OZ�;�Y_�̹y��'���0��$��q1�����\�Z$ʍ�Q4C��7��w�f�}���\���	(2��X�nCv�� �|���AD9],\R�L�Q��u�0������;��߈��0#Ua3?Y�!_��HYO��t�Z��_��`�����fL�c��)Ķ�֨�AC�1갡��#�r��jv�K��Zs�Y��l������,R��l���=m
IAU������fV��U���
�:?��M$���
<`}ȭ6ʤD3�HGx�����$U1�(�IcM���ۀ���@崺\vC*�.`Yȷ8��uh��1���F�~u���È��=s�E�n��L���l���'O���6t��-��C{�"v_U�e͟�a
T��'��a�� �C&
e��'��ҎT2M�ۅ4!fW$�O<���/�f_.	����,�6
B�
�J���1�2?����l�%�z�L*\�dcAG�c�m��7�"��E�M�Y%�Ƶ�v��v��� ˘״��T���}��d�[�|�����J�դ�|}K?��x�Jw⠴��PU�X��F�~��P�����*���C���Hf�E-���3xb�zUPyy�Q5k8�l��r��2Ɣݰ�+>��tB��E��qN#ξxo���� 03�`��k4|��7�Vͣ��Y�!*�x�x'T�i~&��+��7����D����C����}!�PO�����z2�jN��ҹz�t*I�f�4��9�5Z��7M�]~�:�]g�]O��]=R���a*4�P�@��)��O;�x$���d@?�Iׅ6�����+�ԭ�V��eS�mi����E���k<Wd��(��@*���z�g\bߧw�*��:��K�$��(�P{��?ZlH��χi���혱���r� ��h��_��$Yo!V�L�yxټ��=���o���T*���a0�2�{la�M��- �5B=�ѾRt�h_)���>���[��C'�� �\�8\��Z=�.�y� ��s��d-Ʌ��IrsP��L?BAK�s��K����bN
��ʷ���uˮ�8FPN��0v�oȼ��AL7�������
r�Y�NP��
;��ں)L}n��R;�(���H"�M$[�ݣ��o"U"{Ak;Q���C���6�!?��L�ߖ�~���
9S ���S��P2�E���~���;�m��es�~����3��Zϩ~�yM&�j��kr"qI���+��x��n>J�����)A�9�j�����f�v;�fl4�wꃫO(l��OϬ$���U�J�4�	VlP��4�h�c�[7=���뜼鈃R<�-s��%a*I^�4�����܃ͦ��t�ʓ+}#�ѥ���8�ZT=�ѓ�ռe㦠��f����:�g��~Gr�`e���4 )i��oߠ1�ۼy���*M�B�,2e�,�2������?&�UCJG�i�F4����[Uӡ��j3���(���N��;]S8��vˣ����_R�q�~䉜B�)�0�O�' ]� �q��$�S-�����    �J͛#���Oƞ����Y��_��}n�^�b8C�GJZH.r3ha���ב��+7����)Le����Q��S��fX��p��~������J��R1L�y�wKdY�F}Bh� ��ty���Q�ݪ���ڎH�����˺��!�B^W�k;�� &�=����NT�#��a�el�	�з:�����N�ƍg��# �'5����E��w[�iA�)�M<m�a��<�ĊF TU�J2�0m�ʫ�kn���s�o���<=T?����q��q�F�����������,�\(=��;��q�A�I��0�?��k�qU�n�& �7�eI;��Vt$��m?o绮D�F���_\m֮�Ɩ6C����g���u�%;��S�sk��?�6�(^׺	��f���K�(�=�@"�����8X<e���Q_�_7Y��!/�(���3R��>7e��cD��<!�U舍���m�A�Q��
�q�$'�Lܔ�����<���V����*q��s�!�Ǘ��_ts3�#��TG����=�QD�״��ys\������9k��AI-X=J߱h�{���U�-@Y�*�U�Mj�����֒|���	`}n�N��:�:� �t�iS��X��M8X�M��	�V����ۨ��,��B���F�&*�h�
�ۆ�E��2k��@jrR�t��V7�	�j�ɬ{�vFÏ.�	�?��Fw�>���O������x�دLZ�	J��[T��}׳�9�����@�y�؁B¹�
�2q���M��l��4����rc
7B��K�������*����1��Fz�<��C�p\��v�t�+�	�uZ���+���q2e����>g�6.���Fܜ�Yⳟ/�&;����F23*�-굥魌���/�HTފ��n�,8�~�:1E�n�B��7�ɯ�2���T?|��M| +��:���ٽ�	�~�)Ǥ��嘤�U'�L��z�?�T��@N'`R �/TdW	_UFX�T{BR`�5��;�pC#���o�DOF_��[%�+c��QN>�oh�'Ć��/��/��-+	h�碅
������MI?8gr.H+f�a�-�L��~�;�7CrI���2.�j�.b
,T:y?�P��qZ���jqd����CmB-mD_p���h|_���Wv�z~X����/L0ͭ�5J��5���:�J�A�g�^H�V��I,��I'#0|��&���x�='�ѿA� M���5��8W�'C���D��4�˩~x�����'�-)�4M8�ģ0ǄD�T1��� �0L$�6�*����E��:���M#�<��b�� ��o_'�7d��	
��o�$3�(S���(�_O�~�EIAaO�i���d�b�-X:��̈́������.ь+m��|��`%���N��M�d0�v�0�Q-���-����R&'��n{D��F����61��ԓ(�V����bh�:�:�pY�O_'Ǐ�};>�P�-��z�Q��P�s܀�j�C���+/YD.n��U��O-�8zԥ�p�vjQVh{&?�۩ԅ](V���� ��>/��?ڨ�u��]q��Ft3-W��^�$0\�����[���&Nd�9n/��؇���,�h0���"���<'�o��=�2�"�'#�C~�']�u�W	��{����X�Ӹ)k��(�S5 5l�m�؂7"�W���Q�3śAFk�D�u�ؿ}���͗��őX{W�
Z������8�>��Ti�A��a�eM������򩴝/��د��.eU�{��4:%E����������I��6��Br�2�}=+����|m���yFhCW�����v��o�Gn����i+|�X��LS�/��ӄGUd	2ʱ�ܱ_Ψ�d���I���tZ���"U�� 1��o����y�z� q�5����͛���:�}r�
i�?���4۶w#x�s�r��JD6J̈8̟�;6>Fi�3�ʫH�'��~�rQV՝e�c%��0��t8��������Rrמ`5H
�CT�\����Z������^�!M%Pz�S�����,�k2�V(�t�ְe`ڛ!`~"���l��T���Ђ�v7���!L2��5��^ �F�K���#�IM+TDb��Ƹ���%��2�8%f,�^'OC���-�� ��;������'�B�Nu�	k�yd����}���0�S�F���M��d���]Cw$��|`<���S/�'5gQQ���K�
��B����\�VBw�{Ѳ�`�Qj�E'2<�`?��F�:��P�%n����<�g���M'R����A�x�'œptou^�E�!_V�UbMF�ഽ8.�14Q�T��ɏ6���#�2w�-G�m�n�:��m�1أ&�!�c����{���g�O}�PT$ᬲ����@�6��'�`vwn�ٿ�;�Z��m]6İ�\�6
aq��[pzz�!��rG�+�Lٚu�*׆)���<�1H�F �b���j)�6b�k�A����UT��q!�6KH�O��R��]<\|< F�?=��ezU���Sv��D�������m:��1�ٵi�-���q�"Bo����:ExF�WZy���9�zCL��hQӂkFt�C:���h���~C�3�k?-����R�J�	�v�R�*X�ГäXpG>Eb��,S��������'���s���3��4nylвMO����>4]8|��[)��[C�nJ�a��9���h#h�����r�K�lW��7�U��%�(f=w��S�痮U�R���Wf�L��|���&O���Ҿ��m#����+�V^���S)	6EjH�ii}kM�cǱێ��qwg~��kթ"d �=��$�.8u�{cn��E�umVw\!�U�@&�[�ٿRE$��LH=�Xps��������̡)�0��0�k�4�q�� '�� ����G�������� n�m4����&��[�øM=ӯN�]S�y�E�{~���ju��s�'
��o�(�*�yN�j��t����@��u��k�.��5�F�sǘ����91�)&[b����}���l,��h�XҊ��8i����f��R��7Rt7�?6]�(��&�荢\ ����'%X�R2x�,rm�*�u^�D��0-;��H��\���K2��
�w����2�7r�'�mUYcG��REx:����Ǉ�����SO�����u�e�)���~2�3�v�j�.~M�a_�IW%��q�礝��g��"�9~q�2KmIc��/A�����,� �&S,�FSuE�t�eU,z��q���jw;l�w��6f��)W~�w��c�eQ�f��ɠh�O��M�,*<p�x�:�.!��ꪐ �vyy����m5��EY:�
�~L5��̰��6��R�u��Kt�s }�D��2�Zt4�K��J��Ad^صtC��y-L�9���h�r��&�a�}F�yq����d��~�����!	��E��,���TV���T���V���I�Ŋ_y�F8O��{�ܗ�>�$��s��ڬ ���8�.Ȩ�瑹��D ��b�{���%���"���#-jAZGL�z%Cv�)v�zO�/3�S��ؘ�\2�ɤ1I���;���Q�n#�'�6�:�����dU(�<N�=_I���|�;�\}��YѕuK��
���#�>$ v�$ Jט��tK���V�O"�r��������X�iIt:���\W�+	��*&<ȶ.�^a�����ȘK���&z�>S��1�E�up���?��
EsN��	:����6$�I�X�~���=�-�,"�|7F�(쾺�Ub���"$�zw<0��B�ʯ5������!Ͽ�lps$��V�/o4�о�bG�Ep�$�Q�������J����9j���a=�MeU,����-��2au���#��<b'5�j�z���~��ӪH�2�G����#_�&9�*w��%I��qm��%;�$�7@���J��[����_����~`�VD+�g�?3����y�&:TBXĸM�Z���d�.�0�Gu�0����x�Y��@)����A�l���@�JYn    g�M�CI
�<���zl�,+:A����Br����U� �y�7Ho�p\��A��+'���*+�0��cۻ?^ׇ���p	Y�eDƳ	 ��!��!��|Vu�g�����8<�R(aù�sf��@0���30�%�#�t�e���#�ZD�H��Y�#�:��j=���QL�-����XQ{�)����8%&��>J��`��c���t��4u�x���*

ז�F����F Ύ6��FS״]JM�BA@̽R]��m&��`�+y���c$�v��p����j&�%�v-�8\��pֳ�@�*d��e���"�W��]��*��{��ڍ�ҫ�&y4�+U���Ŵ�X�V(Cu<تB�����`Lx��at�9�^5�d�.����k�&�ϴ�����V���-��������h��0<F�D�á�Lc��P�Mw�"���
6��G��6�}���G�]�N5n��i�2i��	ZM���]Ѭn,`W�Y��͗��w�����Q#��G��Y�[ 5���Efk�3@�"_��7K�l���-�)؄�^��N]r��W�Ο^,��3��4�X�A�SEz�����bWb�i;(rfp �=.�h�F����ne�"���rZr��mhӴ����;L �v�t�U� ):��F��/+�뺞�U��nS6�S�e���s)R�tb����`r�UN������:bo��(�*VP�p��s�ᓇx�Fl�>��g�~*���Շ$DH�(�MQE��EyU�یz���!y�_^J�|K��8�@��qgM��~�7x�Np�b�u��n���j���<׫A��u��kT�/��Ґ�J�u�~s�G�5Q2j�N�_.�t?[K/��x��iI�X�8�p�Z.Q��g�x�59��xn�
�!���k40ܗh�n��վ,Ҩ���%�w��� �H�`g�M�`H;�`������x�)�& ��y���QӖi�3�'�O}��ë ��J�P
�:�����N�ܺ����7���+TU[��QY3��(}F0��:��w0Z6H�D#,�����{$ ��U̡:b���/52�#���G�65= ���>�§L��7��ߣȕ�!��'����y\�V��C�p$H�sS�_�
m|��P��&��&��d�wt>�K�)�L���#�R5�f�'Lw[��S�Xa��^�n|�8hFrR�[� @�R���^�@0�̯c���)��V�(��h��i,��o�`�� �����GV��~�\n���W��~[&RY�3�].��Z�xJ�e&�{�ˑ�w�!���(�H�o���C{ϑۊ�yo?�����g��~�����ؕ�-�]_h����
�q��b����Z#q��	:�7�l@��������-�����i�?�tf���VG��r���x�ov8�,�
��I�?�,��S:��ˬ+x<�Й���(�&+� n�Q�r�f��
h��S4��~wx�ߎ����g��ii|�3%�9B��r ��
�X��#J��I* q:ǃ�k<K�U:�3�U��_9�S�I#;��B@��s>W����ev#�hO��>-�3�r�P�a�R0I�R�G���Be����3���
���	|Ή�b��R?8�dL�d����qZn�\��
���*������@��M�rл���e��Y�������,c`�����)�& MFfl(��:�e[����EQ��<�J8x����=�p.����U�3���[_P�"[7�}�m��D��hL/��gZ�.�O8a�N�N�i��)��R���R�!�,�NRP��7G�-����8���a�Y��</A39�F��OB�z��[��u�>@�:��t8��#0׶/-
��ʽh� ��4���z�-q�~H�|9��$fh6'�:}���D?{a(W˰h����EQw��R)!��'�;�r!�~�,�j>�sW �Bk�Jr8z�寸"�Ư��M�A��:Ӫ��2�N����1Q�q�<��+���v/*ӎ��������gI����a��*�/������D�[�Yy��0�Y�˕f�:��0���U�%���5{J�f���O�5%5�+6�����~[�3�EQ�������������h�Mֺ��<�j(�ϐ�>~�����	���&;ޖ6[�c��ߟ�Cp6KCWH�FL����hw�]�!�C�ϯq�!&�TiřR���g���0�J`���Lr��ϓ_LF�Y�Z�2�]��h��Z1���n8����N��^5_*36/�_8��R�xZ�U9[���6Ee
��O?�E�j\�xhn�]��_$�AqK]�|4�{�z�^�_��qXT,d�y�&��]����3Fe�`�t��6��T���*N8���$x9�|�XMH��оZ��C�b*Zc�M�P��,2�Xf�;5_���1�����p�ңH#�t�[�K��1��Vvء/p��r�7���=6iQs�F��=�F���v�蛵3��X��Έ�&}���q��c*���WF��?ԩD��2�c�c֖U��[&�L���{�o�o�`B�����F��ш鰔��]�E&���b�?x��T�O&�M� �|'&d���51�0A�m/T�a���*���/���V�KI˅1�sI�X�aFЋCc8QВ�C�ٹ��}b���?/�r�O]UF����&�[ݲbOi��^�$��|�i����*��9+m1N��x4 c�za�[ʈ3�f�iU�*�	��}��Tc�������9�*�(tg4�uNA�����q6���}Csw8Z �\r@K�tq�����n{��ܐF�yr-��3ૂ�J��Ȝ�Ԉ��4(����&}jN�/������	��U��8~�U�hEn��G��n�Ve"[�P���鲚�(�t9��$/� ������T���� vɠ�ߒ���O�D�G�L;��dz�]��12ȶ&+�ǀ�yZe��<��}�4"`�9��Ʃ)K�%�|邭�g�7��@|=u"Kv�>�!m�{s�����o�&�>3�E4��ʜ��kڱ�:�lH�c�A-�K��¢��rMS"T+7�;��.f[�{�����gϷ?��ؙ��>8�k��E*,26���ܕ�G,X����ȕ��x���Z5n1�?D��kcWm��'�B!I��U��B�
�f������1�q�e��835��Z��l)�%|���J��s�t-ؾ"U���g'�`?�S�l&���]K��
�a�3U�@�>p�D�����Zr�yezn��ξ���w��o���n��[Up�?=AM�|��J4B-`L��s�(%�ѓ���. �&x���˰��(���E��2�S�8)�|��zC����.��8�kl�l:��̬o�F۴e�� Mk��OQ�Lci4D�n�V�7UQWV���xT|E΂l���$����L�����0&��V�c�^c(WW�?���ԋ�E�-y��˫�M��[X=�/����!f$ /���3���E��IF��ɱ��Z����MT�wK���$ �Weh���V]�ܦ�ʏXR�6>�j��ߨ�y��5r�e�-05����%��ۛ}�b��ׯ� _��x�^@�E]�`6�1UI$_ă2f~6��tm-LC�I9�8]"wKَ����3�|,;dȔ䑙��Fve'��Z�PK�=��v�Dre�ȯQ׻��Su�@��דu4d b�~�4�Zp�n�s#Aܳ�����i�c40����JSg�k��+���g��@��㗀]�����O���ؕMIC��8 ��~�엫a}��"���
~b�T����3����#JK���|8��OK4�%s�ϛ3'0D#(I�(OӢn�n�x,wK#��W;RY�ӂ^5�nH�cȵ�Ǵ%�\y����@1��!s�`�FM;��r�&��Ϋ ޸I��j΅(ҹ5�TsSv���^���1"�d㮘�DC��$�n����co�W���W�Ñ�T��xRY�k09B��ը��t�k���h��|41��E����Xtt�7�b��-��i�Qt��nB��R�����G�E-*O�s��    j�'���(���6�W�����݇�z��b�%�2�U!�vR�tR˧���Z�
�a�;���T��m�T��Y6M�U�w�cR贇:/��|�$T��]$���L�+��I�9w^�H5��*�Z�O6��O�9Dy
�O^!E��R�
=!mO�DV�'/�"�����Ⱦ'������+ �ャJ
�~8���6!F�P'�H������X\ר\�b?W��#�sf���~�kVkW���Xc���Cwڅ*���ĂvW��5E�]�S�Z��UV�j����t��m�7�"c���|�i�	?E�eMQ[�t$A�*z�n�L� n�P���ܮ�T��xT���&/���]��h�T���ӊ����?���Pz��M�*������w�@�)�޵2��m��8M1�+LluZ��<�}G)
a�(O�:��|{^�V����y���e2�M�"��*Cq�=F��2�)�ǈd�ŀ��z�,3N+8�_�{A�^�a�Fݫ��W򘄸�2��j6`�u�]�8�t�n�����Rtu�Ȼ1���r~�~�Ճ��2������p���!-�����R!��)��<�u��^�)����S�Ko�X�vq�?N�Df2���d���[Q�L� �R���!����p0�U��8	��F����"�&ܗ�Zk�ww�9{��"j}��b<fgoG�E�L(q�Tq.��'�bbJ�W8�?k�[���� �1������Y�,5�s���f'$�M[t��L&����së$Z����*�yA���*ͼ�D��]Fe�z���G�2���l[l h�j�0���K ���`�?�^�U� �f����w:v�Jq�"�=�dlw�=��$���h�ӳ�D�T�������4���L~�%�~���Mr�Y�KE�v���`�T��x��5N+'���L��E.���2�����p?��H�'�'�Wi��0T,��_ߞ��8�\N��?�]WT,܂ܣ��r�>�x���=�::� }���/��!M�4S���f�qPe`bs:~{��<R�Zv��˵isia��X�T�_^|Xnp͵*���uI�a$J����!I.7ǋ��gba.'U���D[����[K���uE{�!jV<.Ӷ��We�t0��{��`o�����yX���u�n�AT�V,�ⲭJ#T�YԪt���l�MS�,�ZG�pM��=�NkuL��<|;�����S�-�mɾ��~�)��(��ucrwȬ@t�3�0� 6�����.��iinP*�`����B)c?g���aa���L���nh�MQ� ���YM, H�Yժ�����	��v�RגWD���2�^%�C/��U�<�~O���-�9N�*CfS�7W��uY�O�Ff���Z����@/�l��GI���dK``��)���Fư������̕̕6��bw��DC��Ǹ2��#G_���!*��*������e=��P,{��g�;����>�i�_L\�]Su��?<��ѓOg��L5�i��P���@%g�[ȁ�}���e�b�X��+{���:�J�[���lm������MZu� w���f����xEU�=MF�7���,3IȢ�j��G���h��MC��A!`,���(�l��s4��;���iM���dJ��|�<J�����^�AfUBeF�OyV�������1�����p�\.��ڢ���*5��7B=i�Z�J!��D��񞫮�mOgǥ��3����=��OEgI��d�P�Ҵ@�$ns_Ul��j�IW��G!�~�,a�n���u�]�/'��Pf���ߔԏ��SW��� �V�c�+ԍ�Z��ģ+�e�ẁ��7C�1��\���X���F�M:<�iZ��lhЉ�CV���ⷯ#-���"d�E!�u!� 3vҕ�9ެww��1mI�)�T<���x��J��2�ud]NS���!۲\fg�C�v��x�)
�U텄!�����4A���^�s���NN>� )'�!��w���^��i�Rj���+�L��0����K��r�LU
�?"7�~y����-�
WTqt�f���G,h;״z)5Ǚ��bω�<s���!rj!|y��J�Z�z�']�)� �Aw�_b��*��#A����o�ͬ ���[�l�5c��ț�u�m�?�pr�
>��ܠطk��*�E�U'����z�j��@�z�"&����'��0�榼��MUP��kt��
{�u�ySy�\�*w�PL#7 �)��dLL��3) _��E�u�Ҝ��þW��f�z�2�8�. 
@ݶH���k^=]����8�{IvI������YQ� Oi�9����8�����{�]��7d�b�|ng�
��������?�X��%�ND�Lq�˺]}ڴ��:N�#�b�4�I���O7(�!%Q�3,�������lR^�ɔDG�����;��l#���˓63F!|�r�E��G�5x�w���8����v�Y��(4؜#�[�d�<�K�)�TA��sj=vd'�)	w�sLVuE~.����`��E�"�Q�z���)��Μ� ߀NRAbS�q�G�y^���TQ��+�*�����7)�I&=Xü�:Y;0P����p�»J�D�lꏷQ� �턻$Q���U�� v+�;�ow��B�ם��{���%��iW,O �:���	E���MV���6s�oFy��o�3@� >mF�μ��+�3�-�[�P͌U(#�s�AW%���GX��n�Bz��&_{.�d������S�i�L�֋+�RZB��GFl�2�)=fw� ��L��5�5=	��X �lRU�	�c~>�UT�Y��@��w7h��*y#���;���]n���ű�O�^v�?�*8�d}6�~O:�I�MGi\�Ųx7����`:���v��d�Dx��)��t�.�4��ڑf�{R�d�T�4����y���}����~�� �-M��`|��5 Vr�κ�Nu\u�� �)��A��GϳX�y�/(��H���'�0ӮB4��O�$,cF�� �\���|�tx�*'~��5&)_ñ���G�=9sKS��NG�6�8����&��i����g�x��?��\h� nr�p�e���אV9($��w�w(�=T���$�0�ء@�B��@�V<���c�Յ�I���Y]w$,+� ��-e�Cr.� ��?!�F�NRQ���3s\�X����� ����X�.�v��1\nh�SV8P.&y��7�j��,`!�W���H�[,����|�_�̶�#�nB��4-�b��~��`{�fԹ�������~�N3��b[���	�e��gDj��(��M�s�;b6��zPd���=��)z�p
��sW��f�w�arrK�Ű?��r��Z�����G�Cctw�Yv%z��;^!�����0d�,�'��C@�Ns/�4�t@H3[��I떟�`����@��7�0Il����ꆆ>u���
�i�c.�۝��;�-��u/��D��ԶLۚ�&���9��u�М���c<�������\��m��4�*�r��]��q"���S�/L[������#1�KU�Fϼ�w�7�Í��Ǽ�N�U;���	>��n��$!�B�/�L���w���_~�����b&Ә�S�U�4�.Ӭ���l5�
s�|�|I�9��b��#��3�4Γb1�wk8�����D����KғW���x���}z��,���������?�-U�д�iB�ی4x�i�v����n��xd���Y�wY�E'�pO�4'�'�`^��<D"�z.bQ���d�.^����1D_���Z5@��9�HoO�������Ϟa��Y�8eh�|?ss�L@'}�v��	^���\�1M�'��f�F��ρz�ӯ��d{�=��e{���E{,9�R��Dȭɗ����+�E�AZD�B��=)�cM�?�d1r�vŹ�$B*��(�g��QL�n���,���=�'n����`��5�\���czo^N������#�cO��iM�� 	����낼E^�����    [>����\b��+����®Z���V綾���I�Ւ���`�_���!LH��#t��)2o���w����?�,_/�xh�]��4zr�[�8^ÿ��]���-�,,$9����|7a��B�����
_M��7�����6��:y��r��Yd�d���g8�0����!f�`W��w0�[MV�/���U�d��tTטx��Tt��C�;ĕUt7�*3V���URAP�(�TE��'q���JM%x��Ǿ�"k��BP3���!��*	a�;�p�9E�N]e�i�s}����B��`�쟐H+u�;�]��P��b������)�\�Ek&���,����:�꿣��\��si�8�S���rō=XHy���_�9�+Ռ���8H:��ڂyϛF��i��0a�'Z�*��F�ؓ��������s%�V׹.�������^�u�R�@�\d#D8L��y��O:#Bw@
B��I��?�
�WAO���
�W0�"�aN���)|�*���4�k!ӿ�|��A���ocQ�%��.��e���V�V����ӿ�ܨIv�߮���g��I]蚣dMle8H�τ�
�M�L�>�*�qbk#p�U���;��eU�Q�]Vx��7�l+��Įi�κTb1�T�@���A�͸��i�E�C��Y�_ޗ/�6��"k�#�bA�GWZV���h�o��W%�gl�gUB�N<"��kR�iJ0��`����	b�e:}�>K��c����o��d����8�2+�GQCc�AQ��gx1�=�ɰ�E�v�vA췧��#eZ��2+�E����U��e�<B4�Y�@��"�;I`�������aڕ�lNa��wQ*��l�H}I�Ԫ~���fES+JW�Zwr���?�kQ�$iҴ�Ƕ������2!��n:I�1B�E�����Q��b׫��:�^�U��FzT򄰩�t'��MlT	��`n�U';[�BUu��� ������OQ�"M�I�����
�w���"�rq;��>,�p|o�8��ݲ����)&!ۏ�rn.��G�a��W'=Y��Pq9&1��}�9��w\��$���2���H
��;z6�,�v�>�ה���$��c�e��aZ���j|P�Cb�ڿ����%a�fI�Q�i��T�+\�c�,��2Y2�(�ݛ�7��Op��X"�7�z]�S!��/qcE���H��1��H�$v#�� d. �� 蔎ob&��}|�S�p�w	1�^J��&5����e� ����p�G��|@�7�tU!��r�OQ��4����e��nZ�Skax8�$���=�����7z��r�����$ h���� l��}�<;<󐚌�.��ȗO[/�U�9��Z%h�����⥸�����Q!��c�46X�UY�A*+T���I��N���G���
Q�HV�D|��P�zU;�M�k'ӈ��/�!�)�}M����$	p�e:@�2����*���/����m���]Ų	[˜r��g�����	V)�Af��Q��?c��D�>�}�5w�Ru����
��Mο�9%��o|s�#���X���"�����T�%h����}���ZO�j.�����
	D�?`��9������/��#��~s��bɆ�����7K�����:��2��?����J�a�b�(�Dap�$�R��P!�B�L�]25�<��3垔MGZ���R�q��&�Ή�>}~�xI`�����Veù^�����c���.�y�ԍ}�Hjc�F���TAL�1'CScڌߌt�s��a�7�0�
��l���y]3��p?ƪɩ���3Γ>h�I�rVXAF!����#?EA��C���C��*�R?���<Q� ���SR�����N8ڮIegǤ_��(]g�ځ@���פ��*�z�p0Û�>��%"2���<�=������O���M�/����W�F|,șE-`�ju(�����5���xȥc*��e���iJ��.�+(R���N-K�)���u~H�Ln�Z�K!M�KUV��h :&������5a+�
|�#X�?�
�Ǚ�c�\�]��#ӷ_�G	@�/z��ݠ��S!PV�ޭ_���,�+ܦB�8K��F�&%�Q����������߹� 
X�$���[ϗ��_�T,B�����AF���
:8E���W���?(�n2Mȳ=,3����o;<�9�=�3CmJ�w{�*Ty��wY5p��EDސ��Un3�"y�M_�腥[eo��n��:���϶c��4���[N�/��DR�D�. � �«
S��C���(��.�!B�Y��6�-�*���ω��EW�]:�����ߣt��g4w���IW�WH���T�W�+��V�U�Q�
�dż���y�V������
N1 Ib�rG�Ȝ����qhu��G�7N�n��<h��Z;���f���Zɀ�j��4uWMv)*;q{�e0���7I� �\f�t���t��ͽ�y�ksf�a�S{����k �a�a����0}V4%U3�!18o�TV&�ɹO,zs]�M,�[��Zi�QNv�pËK!2،c�T��2S��S�|7�\O.���vMְ�F�,��k᧨_��0���i�%u>�*���$!����$��֍QRձ[~m�Oe9<@pC:fbmB�ʟ�N-?��&u� ����fxQ���)���l\{�)>�	�8��3i<%��4Y�a��H�{$�����copd�Qo!�����`�^X��X����5��޷���m�	/��p2\i>���	���#��.���>��S�J1�G��ܠ��4�7�!Y��$�j(����rû�6�/�X!,^(��@�/,.�3�e��|9���Pn8���H�K����ZSמ39ރ�M���7�<V^�,�
��Hu��L;�b��+l/���Lr�t[�Je�C�(�&�L~il���Y%gx��t=�5E���=9�����G#r> ���l���n0`�@V��!�e�mOӶ#����dxf��(c�;�<i"B�ט%�<����d&S*�lf�_e]��j(��H�OJ;��"d�l���_�`�"����8�)u)򪆗B��zy��.��Q�y��-�5J�X=�D�2�Ϙ���L�Ԫq:���L�JNHn����بF���*��m�����i��wf�Td��+�>��w��LbB1�grA|��f�1�-)�+J��7o� ��`<C�7���5�+Y�����[n���ͪ�u;�c�#?�9��O\�.v��4�g�� ,��%EfB���.��
��]�7�ɰ�줫Z��Hܻ��D��x	�����3g�+��6k���|�߯�_6�kI�;C\�$O��x��[�maH�s<���&�����KV�K+x���bG�v,ښ�A�	�Qð3r��_��Os�Q�S�	���&c3O�CL��`1�6`����%�����U>Ļe/�;���4��>K��98�{�B��tN69��&�r�CfL���x���^��§�v%��Z���
�~VP)s��2�����D�D~�sL6��N�|l̦>G�*A��G�.�������
mXa��ٮo����E�#�a9CN�N��ZJ9�Q@�=�� ��J6�ds?��6��<rb 2p�7�o1����Q�)��W��<{I��:$ce��%v(SԟF�x��b��U�u�!�:�S��b��X����HC-؃�C�~���'�l�p���{��z�p@;�M�rpr��%�_�V���fEu��c����Ȟl��s\a������(�|����!s�N�W��ܓ� �QFo��h�p8`��D��o��2�CT����T�8��h�Ÿ�����%sZ��Wщ�xl��Z�� �ˋ��ʪX\�D���Ǟڝ�0qJێ�� Ȕ�`AN|><��zl"�?U��u6;P��u�Q[� ���Ǚ� ��Fr�N<�KT��Ug��^��6�x��.Ȓ��"ȶ:��ƭ�ݘ�p庆�F���>|�օ�z!�`PϢ��3^�&�w �Ԟ������5B��g�����L��q�r}vy�-SrD�A0����    �3��3z=��U�G��GogF�Z��p̺���$R��e�T�d�����؋QD�6d���C�ݺ�,3C��8B�<�mԸ��(�=�"R�>�tĬ���[���-ZR�U\f��PX���s@P��	��$A@�f�ST��j)�GҰkz�0�V�
S�[��6b�s��pl4k���Ȏ~���S�?�M)��o����BV���:�/��WG��o�*f<k�56��5����Δ����S���?Z��)������h��KT��>iҧ�6�{K\��4����}�
�<W�=��� U{�y(N�H;��f�-�;aSG�B��,�����(�)�-�0�V��Wa����mzn]��Μ��
C���s�Z�<b�y������nYe�n֯_'b%}o�o��O�%E&�a䬘ӢV�Dyψz_�K���96jyI����}h�<%j3YU�7k�x_�ԓ�",)M.F�dD̫�ea(�C*��%Efs���l(f0�!f�vۖeސ5��Ԭv�~��R�D�Y\��A ���7��7�c���:1ER
�r�Q�XA�2�ӳ*
_u��^�=w%����y�Ӭ�@/O���58i��6���H�eB��XZ5���}9�kR[/��%1zj��9�$��[HNI��JpZ��2�8�Ҁmr�Y��l�vH��;�L8��s��0(�d���Q��"�p�aQY����L�ۓM&�H����T�J�\���4���;���K�ۀO73z����]0�����t�J&�.M���b��#�
���Q%gh��#Q'jY5�!\�p���0�[U;|����=\<��ϖ?����1Gl��tPG	���w�j/�k����i�e�5D�$�J�za��~���]0"��m�aTI*����x+2$�B!���ᙃ^���́�������IP�Yz	��#׾gLm���( ~���O$�N95)0��}zey	i�A!I� � ���W�t�S�C|-I����AF����B!����Y�m�*y3�z�H��Q�W����/q1J�'�?q��;�x9Z5�U�@ui���>�Τ�H�=�M+�YC�������Wi��b��d�I��AV�N<F�@AE���桫f�A��z���	��ym"��|�]*����|����y1Z:{���HL�`x��EÃ��']U�E�������>ɀ)�t�=��\��x���bU4���Wc.��.F��a4��\<Q^˪�J��_"���1��9Ma�Lt�/9�p�}Ԛ�KVL����;'+��Qh�3κ0J�^�FZt)��ɪ�_Ȱ�^F+7�^K9���ԽD::�U�F�yfL���U6������L��!@�� ��SWuV�O����nJ6�����Y�R]Fk���E�}�&�&�խ���#�X���uL2UQJO������4����қv������"
�7͕�l�,Sы�S'���쾎�qHKX����*��[W��oW`�L��װGG�c����N�;��Qx����k�>����m��9 ʱO8s8�'��_ϒ>�`�v��)Ҁ�te�i3005RXcU��)����k\\�l[JL@�l��B�r���Z/)U���%�����ǿ��V���'���������пH�ԛ�爾I�e�֜�	�UVV��P���i�e��%���:����5�����e�)���R�cT0Hi���:�V��yl�Q:��OOQ�5�!��(_�]����f[^s'�p�;��?���'"bV�+L�]���qGJ,� &�xO�=��8�GZ����m���|�T2����H�Fq����?"ˤ`�̊k�{Ȫ�3�^<�չ`����ՙY'z���L����#2�T��$w������6�2��4���_�іdU�T�=S�\�3e�/���f?D�.t1�e:�@|��$Efړ��>�p��P�������*_l�;�$�}S{>C�<��9k�W�s=hs8�L�Ѥ�B�S$1�7bEMo��/�#�W��Ȳ�w�ށ�D����|L)���Aۿ���:kq/�w	��'P�ı�pW
��5�D�L��a֬|���R'�����@����g*0v�=N���"+�]�y��/Ad��!���G�V���hOۼ��E:�tx��`�p��#Me�i_��m(j6��.�_6p��O��,�]����w!\Cy״�����r�L��t�:+�W�|��W��l�|�����ޯ���Xh1:}���X�AF(��9ߡ7U�P�ZV(p5�6pD���`#�u�)0���@������JO��e�̢rrJX���)��j"K�?�Qq�Т�S���rqŅ֋%U�}��̟�Fnv8���^�qב��N��r'>�ǩ֔V�:��� pv$�M�2��"	��U~0�þ������̝�T����魣y���Q�9�l��㸿I�P8��_�9<��?~�Q��*�f��ZY���Q��f
i�_K^rSM�HM�y9=�k��4�N>fb���S���̼��17��*E�;0���
\�����O��i��U�bAO"��q����T��c'Kx&�@��S�#	!1RN$ ���O��DR�|���	:l�Q�wC���SI�G�TRV?˓�k
hFS��䃓�R��lacD�Ūg��3�z�����u6��gG���T���4h���=��P�4�'nj��c"�|��*�NT��ߡ]�8��'��l�했��G���{�m�$��J���Gob��+D�y���A8TiTޱ.��S�Ō��H�K,n#�j����X~�v�;\b��˵P��u`��fi�>�ϋ� ��%�LN?���:�y�O�<����I��i�{�8�p��W�N'�_p2Q��\���� ����!�Ã ?-�g[P�1��=�T� �N�rىW�b��^W8C,���qO�[�V�}pM��H+ce�/Aoc��[7]��������������2W��, �<,o��;�6�O�Bam�:�n�γ���	[�a��F��]Z�pp��(�,��/qR�C�CbA�Ez1�"��XȊu�p�%�w+�@b*JdAzL���D��ɕ4�tY�Y�=9�Hg�A�F��/��9G*2~����	2���9��UkK��P��F#���
�;��������J�VN���XD�T������Bv;�fc�l5��ў���֞P���:�_��1�;ӈ�8&�Z��mʴ�XEq{��\�"������B�M
�*K](��u�9�������rK�$=՚S������T7J=᦭�g���^b�m���n�d�C������^�AҐdL/�G��Jվ�8�T��\�U���E�����}Pe� (�gUh�e�V�*\5�"|>�i�Qơ�3d��e��nD��H�����m���oc��e߫6�Kx6�{:��-h놰����R'f�'��f�7�	�`��Y������
�j^�%h�ե��(<�@��dc��h\:�FĢkSx�xFȪ%�v��Le�#�Y"�4�v��;����B���p)k��M+c ����َ���"��UV���*����r}��1̀�2�!�J$�~��{�4���s�Y��uM�@�Ƿ�!��P��~���&�%Y����變O���SLei�5Wz7�hS63Eʺ�i"�d���D�'���Q.�IYN��8�}Z>�Q��bu�c^n�\����
�Τ*,Q5w�ȴ3�e��D���OI�/"MI�"��f�*E��݁��כ�H����h�6�%M��o�m��ȝ���=s�LM�)N�H�ڇ �:�<�sT�í��Y�5���䭼���T��]������cYyuh�x��eQT���0Z
�QB��O�b�;@/��M�3-����R.�^�x`@0N���R@�>�3�A{*�ʞ_?D���'Sɖ:{��(��+0u��_i��H�����j��8$ �^8 a�H�I���t��&�l��1���|4�)� ��CGp�ݷؿ$�, �ؒ��M����+���s�OIORQ�Ax׈�BQ�^�g��-�F�7���R�kn�JZ'�q�����}, 
  -���t�],]Rg�L39�h�?�Ff�d���X|�M �Y�W*�,����t�-�FbI��R漋I�;܂�pc�?�\������Dv�V��춘`J�U��Oq��?{g�ѕoc�o;���=��p�
n̘m&C�P���OO?E�gs�˖8΂W8c�BY�ܿ��|��i��`*BĎ��Aq�r�K9�fD��Ԁ�c�]��4���lO�H��6�*��:	��;�F6)��*�a��(���$A07,���%��⽻ ��w�w��)5�⬶�ߟc����ƙ�.-1�E'R�I/x�P�>YR�e3�ca+y(^,&�.�Y'j�q5y����_�ćc�!��/��[��(�vq�=Η��'*���Ru�pD��!>�������K��~9�#�{QS���1W� ]b#��P9��eU,PU~����i��1d�s..�+��)T�aF��+���
��6~��[Ė����E��\9�UgI��7��$؋��e�p��� =|�����{l���Ţw_~��Õo0�|�n�d8��+�. ��NM�Vn��u�������yT|����i`ٳy�h�yMk&\��x�Ƶm����%�rq��"�r�\ov��v���;�t�'��.)T������Ś\�ð���7'�1�ݶ]��ޙ�fYHs�1�N�1�횦Z��4k\�qkO��s7ܮ�L����� 1�3	ϒ �	�yE2���_�� ��i`��E�:��,�f z��k�.�Zt��9�&S�󑗱H.�ny9���M�]S�%2�9\&���i�x������˴���;̯�
#�����h}�)����ȟy�ŷ�_������u%|�9*<$��a-#p��p��;�Ӿ��UW4j��ߛ%5�F�T��hj�T�bY��t�bV�\ � ������4�I,P�H��;|�']y%��r؟֛��W�|h׊�f؍s� 罒���R��zj�������f�����2±ˍ�(rH"���^x�"�t��A�_	#���==9���!X��;����}��y���WS��19�3��~�>HŔ\&&�Hu뮠�E]��;�D�)�9�y(�9��S}s�~���/}��!<��{=&��b�	`R�[���BF�W��0��#�_�]үv���{:5~uA�j8�u�c�X�� ���t2cT���?ΊѨ�4$>���*ۦD%Г�𯼽�!yJ��_�^�;x�.���M2�=b�zWQr�I��X�LE1�V;JQ�Wu���S"8o���P�HW����zR;]�FJ�`A2���-���Ӕ���0NFR��vF�,��Z���}b>N�����#@A;]�A��Cq��f������O"}!��%��^����^K�c��^s�o~k��e�|�6gڬ*�?�Y��9N�䇫�>y��:�#w���QαܶZ��n*�9���G=F��}�>���t�R����%M����d�ی%��wW��^zU�k��m3�P�)�l� �����k����^Wtʟ�W��d`����[��M	d:�(}Ǉ���7�:�MWBt�.�����g�=§�Y.$o�#F�t]�V�^����f�L �N�&ȇR�m��Sl-�� î`���n���F�&�i�n���ZTq ��j�Y�W?��\?p���}\Y.�{���|
�/���!��b7�e��}�C�F�6����|�>��p���T�M������T��d�Vr��6��Lk�S�=�N0N\>����2��) ���v�"����Y�n���p4iM�>&|#��|L�'��_�����3��ĖQP&.s����m�	�,�p.	>⼨�LWB�r��~3K_�5dWƴ�}OkO� ?��93gr�)��(�5y��������xO��X���#��Bیz�֮1P�vSg\��|�+����Vh��w��6zCP�wԓ�X�������;b���̻T��4���b��봣���sd30��f�%�xs�y�	|Hpbf5YI^��x=dxJ���zZ�����3�C�֌�xd�X�B̪��s��`z���T>H�z�U.!��=|����J$2��^��^����yO7FD��ӗ�cK�Q)Vq���n�-ow��r[�%�|I,�o�/I��,���]�?��JlF�B1�n?�m����ݹM���-<i�����Ǝg:���2ڞ&��oo��a�n)������.>�����3Xf��
�N�*YX	"��~��1UK��ON/�M$�$L��z9�E�M��/@���˓�� ���@M0����:η�*so�~	���<�G�2�k[�����^�:�?���#�j����	�O�|�<s���$�����+Rp�ᗲ8�
�� �2,mf���P���:�����q�q^�ϐ��U!wz�LP��PtLP�C�I�S�K��"�IW��qޑ'�Ph�����ImZ�A6����ȑ�u��D2��>�UK�:�+k�:O��WW��v��"n�RO�͐��u}�HV���D*Nb*�zS>� ��`?��&s�l��S�ԟ$��������_�]+�      >	   �   x�Ő�N�@���)�f��m���0�6��.nH".&��Úx�l�m�4���_o|_��ԝ���i� c},s��BXu`KH@��*+��\�XJTa��۲p� g�q�A�a���Ľ�2NE�=��-Z�d,�k���S;<w�Z���v�����f=��)D OS�2�[W�>��t�8��)�mͦ�����C�6�j1�<�pYi�ؙ��i͐�9�_�f��f��yS���Z�ǜ�7��}`���      (	      x��}�r9��3�+�8���򘤲�Y�ECRU��������@$���2�l*"��q��ǅ1Ba���������Ʌ?黻�;����ߩ;q'���}w}y�~y�x}����������������t~�8��1�S�s��_�X�݉[��/���9�g|��x2�w��E����<�	g,�s�p�w��}G/�r}|�<��|��>ayy��}��<^���^�sI�F��ޘ��F*�ez��gj����^ȴ���;f�.e_D�O~����9�I� �_���x#O\z�wi�Mz�bI{!YZ�����d}��������>���t}�8�_ʅ�B���ư���j�����e>�P�O����q><��q���o_�<�'������_��r���_�_��>]޿����ԟn��%�boN�y���_��A͒��|����/_��~�������������B~�6�+���~|��ϧӧ�}	e�H;n�i��7h��HG�^��D���_t>�~<_^>���y��V���3e�cT��eN�i��~n�x�������������˙�����昫p̍�s{�9�4/g�9�� �c.��ޕ=�鶝������z�m����#t۽Py/<���5���
͒�F�C��;7����%���7����L��N��׷����s����8)Ϥ�x}vl�Y.�J׮�[ARqc�w����|4�"��ĝP��j�],������No�{��\~�D������w�LV�Ya�p��9�ګp���K��,}0b�J&�᎑Y��"�ыW�_����O7G���:��_�SL+]3/�	&��`;9�3�>n]ҩ���{A�G9�}��my�"��#�������;�}
~��3G׿6�Z��]@<w,^Al���pg�G���V�-��������fk��,�~P
z7�
������W��J�5��9t�Va���@�;㤬�����54+oԼ��2��k��qO�8��U�]���� E�C���5���W��������0�\��3�2��x
�N�'�������s��^������+)�aJ2@���M��M����v���<~�x���������:��0�K^�;��Ib�]�vy�Ӽ�>}�Xx�6���	f��u}Wۥ����/a�f�uu�i^�ޱ��-s<�3��������˥�x���K��N����Z������5������J3/E>j2���l���o���㢑�>_�/^+�
���IH�;�?��p#,#�������Ǘ���?,�O��p�M��XO����"#I�H�.�N���v�v�?Qy�Lz��r�NQ����'�/�x��2�� � �.�>j�%��m�zS�@����O�J�q	g	�sCߔ^���.7�>�ӽ�Z��s��x�72%�"����7V�Ny,r޼܍p�2^�����M�� �x���7���?_�\W���� ��N��M2y9�u^a�����xyY`s���qt��~갽�W��$S�Ae��h�"BI�8]����[n�x����A`�,×��+���{�OE�DF&�XY0��r�sK���b��OΡ�uu�����'��b�~�pt"l8�xp��
��p�e��T������}��xF���!�r�*Mp�"J�.7�!t䤌d���5ƌNo��H�8M/�).a�����s!����}�?8��!�]�'��O6�篬%Ah��a�C���j�elI�0k�"�ix����-_a�̄����� " ���Q�:�Z.��K@�F�7�:��OЁ�g0�Oǟ�\� D�܌��j�iϯ/W���k\Z���$�A��Zڛ6��e�ҢY�k��o�5���[ƞdB_�^����XP#����	mskF�/�m�,8OK��O���}�0�Y��<C�,�?`��d�����쫽�]�L_�V1g�=?_ST�����'����ܷ˙'������~\>.�]�â%U<\%�)�3��*?v#bXe�,wѨH�1ލ��x��א���������K'Af�)�,��dB��rI�Kg"�:r(��L;1@j��B�i]���i��'�?dV�E8�����H�ד%�e�>,�i���#��<��etZ�2�fC�����ј ���6�填à ����!��7>�y�'�P��\_P)��ٸ��-�������9��3����n�.7�CNQ*K~�_�L��|p���k�S�S��7HɅ��	����Qߠ��T��)|"�24��%9�>�\vF^#b�ltLkR�(\�,p^b�{Y�l8;e�b`z�f0�d�L�qn��r*B*�����ޭ�I���N![㛭�]N����z�$p	�p��لn����=:����C`$�����4^>��/���+�B��츖|T��O�8�;iX��D�����pŋ<l��^[q
���\|��6K$�Īz��rQ�������]�A1Etw�u�m<��djx�%�m
T���) FE�N3���l�,���W��t�����<��E1�ǽ �π�ÄD@�:�9=Y3����y`9�:���=�Cs��mre/r��bש�r����"-�������8U������ざ!9I�����r\f�a ���5���K�Pڴ�x�h�*�7B����5()��ú�2�F�����M�w`B����	�ab�qS�r6���M���U�ۃ쮐�xE�9V�^~}{׫�Ed�A9�,=�Hz9��Q������??�W�G�g��T*?3{��m�p��X��30�t�5l���c���yS�~H�k㘱��p.��58��D�S<�e����-|F��>��O$���rT�Z!^����w�'�ֲ�KP8u2^�dy,��e47��!͌��|z*��Z�Q�<c�G�Z�b�J�3/}Xf�ʱ\_@�a ��V�_�o@�*�����U�:�z	[�+B�]l��݌>'��G��Q8��\��fW�H�p�oE�k<�K�$�eq *�>�7��}͵�%�l *s��}�>�|.C_]��vȀ(�f�C.�p̆V���.���˟���"�h�m�Β+'��!��N��)V�e����e�NE���Y������=?=]޾����j��̆Z r���X!k�"�i�{�&`�HMVjQ�xa2�Z+�E�Dj��.�`��(��9�����ݎ���%�3f��a4}�5%ay,lhEO���O9}�,U ��L�\@�Ƌ�5��r�ļ�J��H�z%��Pd%O��L��<Ɏs8O��Ϭ�ʽy9���ֺ�����HIK� a��G-���MNE@�X���Od��N5��B��
�ڦL\��4˩p��$���X���~�����6M漲!Q�E�5�h��ԓ{��H�9�����{�jT��X�!l��g����k3����x��`%��u|�����C|�~�a�"�Wd-CEEp�<0@�/����\��������U^{���;]���ALT�bϋ�X=.3�Ps��y}�,�2��u��
Զxf^��q9e��	$d�U�M�^�cVQ�_�H��Vva���l��7�F�ki�1��JT�8��֛ގ��K��U�]�D�&-�%�*}�FK�� �嫼]J,ؒ�jM��C��LVk�y��L�u]n�( �@� $|4�T�U0�NA R��kl�v)����ü�����
7w'2{-���i/�;	�A���=��xs�����\z����o���o�m��'��?~_[�\~�W�����J���-��\tw^g�mn�	�l�Ԧ�!2��"n������� ��B���V$	YU6g�%�%
�j&�����z�֨x�e�:����D|�К§˷%7E�R�R���d<�2����2�%>�b�q�-c���F4ޅNst�d��M���;���KM@m`�lhhft��w$�����|��`A�zF���2�R�"F�]�ӼGv� UrO�EJ@s��Ld���e�˱u��e�1h:�j����ڴg�(B{�:��Ѣ^��2�H�r��)nDN!�4�ؒ)�ȒP�V ��Q.���&�6����`���v~�ǠI�����X}    >�����V��z�d�].�S��br��6!,�_��aH�!�RC>��2S�]�L��?��҅S���Տ�@������|/�q$�("�~���O�������y}�����Uey�O��O-x��/��ܦj�EJH��[��v'}�>�눳���v?m��GP�B Wb�e��6m�{;���#x'�P������x���І$1��]�����OU&Z*Y!�m�
��)����ix?&�GC]�fx��8���U�|���[//�_ޞ/o�@ׁ��� �}US�\t���r�9��z�Vhod�-�\�WKw�\�N����R����5>.o�X�3��ڹJw��ͿJ��r^~�\�d,m+:�<�������a��^9�4��6�	ic�QBC��ά������e"Q�*)>UR�S�N0��cE��KnS��?/���՗�/�s���5�-�t��G֩����Y|��#�v�Y�(p짥�)��r�mP����85�����,��ʺp��" ��a�V`��Y"���GS��M>#>#����c�i�LvK��^>�@n�c���v��&�&,��2=ND���=��B �Z���T�䁨0�����*M�LLNH#����%�D���om�VF�1:j��*yF�v9���Ew�&
�N�"z�)$���K 1`�[�;���H��1�|8�.��Uu�����F�)���̃g����	Z�0s��Đ�'�����]�E�u�[M��5k��My���=�̈�-��W2�.'��-
|N�r�}�E	�Ư����c���%�:��������r��14���)^iz_i�%3���������˩���@���[�؍�ve�L`�
\֣��ǾW����t���J�\(�Zz,=]��o��}�W�&!j�_�H�Ƞ���29��1�S��K]�^�x��$� MI�u�a�IS�	�������$���7�勵�������WɠtL�t�
V���v|�ߊ4K� Y�8P1���Z��ϛ�mqfO�D��n�4:t��<ұG���}b���$���Q��i�ѱyn��s��w7{�O���.>���
��7��,����a����ȴ3��=��vD���<�E��O-[[�G��Qw��v�Y�5���7}�6�:�6Q�Bڎ��5�	�$�㬰k��X�p-���dۊ��C�F�(_J��2�K�@\�����"�S!;a�Q6g�k!?/K{�\��/0� ���%��J̧/��O�nU���Dṙ{��=����f��@�^p�Cŷ<�y��4Ud-�m�qIgdi�ƣ�@t��7�{殮��D�T2�aC>�)�7�r�n���O�&�wD��A�Z����ף�*C��;&0|J޾��!}R?�s�%�iVzQ�u�m	?�u�����e�	�Պ�6nRFQL�����Ĭ�>��l,ʦ�'.�앴�?��ѵ`�]вb9�&�\Y^�F������@����aAˆ"N�5�X"1��8g����I�]�&R�$7�]���/�%�*H�#�N,�X)�V��j��٥�R��W��)��|��%eV�6�x(�]�!��Z�*�&�jٴ�Sk`�`))B���zh���� /МAYՄ�x)=��X,�I"�u���H��X�S)��,IY��!3���12�,�˛��s��j��<IN�לP������E�E�8��d"�Y��O��N��z��䐑sR:�M=0�]Nd_���e��Hp�=Ѝ~z�q�=ꐾ�������台�/��e�K�@�` S����Y(�v�-h���Ֆ�&8-mSt`�����SH�v�LV�
!��{޴��!��!A����������y;�������Ӳ��O�4̢І�p�*%��:c��m[��Ic�n�,��Ϫ}-��6�?Q�����ҖZ���v��H�����ɊZGs=� ��V%7H�n��_!ִ �h���2����J�y :gUJ�KN�B>�nx�/���Z�q�^Q�uC�����;�s�6��7����:�"�И�5��]��=R���5H�CW�E7ɰ*
 �@��(�y�Na���ګF�=����{�{�H̪���~}_�E�OX?`#��U6c�$萞CJ��J�$�?E�^�(4H8��B�t0���~}�-�x' ��D�,P��.k��7�<�m*Ih��#��?:��KW�]S�>��Vys�'{z~J`�,;�����Ԭ�۶dpku��=�'M@�F�Y��]�Rf�[^��-9��{�!��g ���Ss(�V�P��"��_{��*��h#	����g9a��߷�ķ��o�o���~}�0�PV#�Y�ot.\��e��D)�Mk����j�Hj��9/ �D��/�?	�p���,'��/�Ik�������Dq����q9�7B?�%=Ln+OY�v9Wv�ٔoo��\Ov0R@B[m��SPC6Y�ܘ5Ӡk]:����~��)����)�ٖ�7�ދ��h�\��~���*���G@'/�S�з��6�ք��W�k~�̇@jz�J���M�\�e?��h��$���U� Y�>H�����ci+��Zc��:���y�K�7CE[$�Ϝ���C}�R�s�l�נ=�)�I��P:��"_|�T2&�8Oٱ���B�0Ϡ(�K������|[���S�*d�˗�?/eO��΄�I��K���FE�_H:�촟1]�M3T�DlT �)���R�c�͔sO�!���� ����x<���k�KE��bK�8$����d(C�J��Y�t����u�ih�D��ؘP4�r`^�'?q�� gC����ε}�e��X�i܊Q��vvg�=ݣ6W)lar����O-�"�~q�7͙�<��E�����ޅ�($qĵ'��Wװ�A��z�qB���L��<���gC�)J2�P避�wE�����ycW���������:�a��ϣ�ⲛd� ����l��"���&�1�d��ED>��Ѥ�����po��
���#��(Z�bk��w���O3��Ó���L���K� �h�}���cAލ�w��4�E�>�a/�}���Z�8��ƙ��,7*iP`E�b'R�5�ۜĔ���&�����8�Y,a�T�yY�32���h��7m���"�(�hO{�g�=�e�y��L�\��^�Mq=��a6��~ߠ���� ������,#2A6�U��4�8 ė��]O1Ea2Fc3�aj�VX��*�6���'&!n�Όp-J�'�A���/W]�A�-�bNؑU���M���"*m�W��,ʜY99k�6�>ك��yT��ŕ��	����W%�*^>ڣ^��4@4JC�TS�\M#w��,r�4�t��G{�E,FX���Q<ę�%�<��JMi����0f�����'����I�i�B�U�ƾ�/�z��kHP7�")S�S��������/��7���/'�hz��
8���}P�=�V�H<�%�ff��� ��-�v���I+_���%>UX�R�Kj��)� �	�k	�NJu�e'�!�qd�{�T�7g�%j
�V�/!�j�R����#O���wM3l��$	���s�[��%����N@Ug�=�K=�z�iC��'
�Ujʔ���,���V��A�IKm;��͑yC��>Q����_��]ܶm���T�]�.�l8D|��2��z��&>����#TDf�c� �s>p)x������<�F��� fz�( ��
w�,�m�~I��K�6�@߶��;�x|m�٤1��2�h�NP�b��3ύs�G�&���X+����PȘ�hH����P��+.�xچ�68!9JA��	nrǶKT���'+ʹ��!�2�b�bg�S?�n���N��l�(w\��6!A,��	fH����n�L��˖��+Rs�;l����jQ��UǑ�##n��Ŵ����$�6�q=BH8�BK2 pFL�$�r�����t����J�]%�WM�Ϧ��9VK18;"�:ڴ�tOQO�G����o*���A��H6�y    ��n��Ka�����,t��arm5s��rpN�[�&�������?PU������pz>�#?�Sg���/���L*S��Ԗcb���E>3�P��~�p��(#�r��,���:8�o���At�%�b��jdYV�r�=����:Տ�à2 �
b����?K;4ˈ�:i6���:r�3���S�b�6�K��4�IM��d�C���#��9ѩ�3� ��	r�f�%:��g��s�Z�*{��(��&��.E$�i������jL�E13�D6ţ��.r/���C��M�jG
�[6$w�q�",�<�"�fk��JTG�j���$2A�cSn��ۚ�&�ky>%�Vj�lN�.��<�!/�� yM:/�,21-R���jB���P��m�s|)��U���b��'t�<=������Q:aΦ��v�z ,��z�� '�)C�$� �m������, ¢����,�=�QE
���]�����y��;�>s�8.t���ߎ4��oW7w~K����p�5��k,sͽ0����Β����_�ҥk�M�Z1-���w��m^���)����03�L�KHqsru�V���LC�!���83[��m���<ae�����|A����:S��=�A��Hr��탄<f2Ӂg��G���=�9���t�:�L9�������P�o\��HCEXh:��,{g� ��I� 3�I/��В��A���TM��A�w
z�pJ�=O�b���C���M��)�����!~�@'l9X���V�Ă`q�m��0�hR��"u�R+�e3�#�㑾�������Z{�Y"Ȗ�.�ƌ�;�G\(Q�06672 ���ԩ��{��������39�5�8�@�X��_��wCG�I�e�i�"��Jǁ��@�
���o���)���H�t��������o?��&rJ��3�C�Obv�,�a��r��Gw�6Ř�)^���+��6hv� ��,�>�\-C��{�2U���v�7��73K��a����z3�R9d[��BG��BW�A�z8!u�Ǡ���zH% v#�v�ތ�
��t4�����TUh�.�����,�{�au<C�L���u�I#+&��N����0�s���x=,��ÀZJ_��	s�����U����}8z@�<_
��~�����Wː�kR�_�m�۰}{z!���6�BoBϡp��b�����wnn�藗JQX:�"O����P�n�;fѢ��er�J2�.A���9�c���2P�z,�������ѩ�uƙ�@fQIv�T!�f�����zl�#����P�@��ɤ��l�ID��^N$9P��sH�f�բ����E5�c\�&緙�l<��,�\��	�Mԑp
= �ZKz��P��������S�S;{�bpg��������oh`j�~yu�b��a��E⟣�S�ᘅ9T]%���F+oT�k��$޵��UԪY���g�[V���ӳ t$ߍB�oZ�c�����^ҥO�Z�N$��1JT[^ ��ݪra&w�b]g��M����𥶄K4�;K�}���%�M7Ã�� {�h2''٫�o����7�4�1����W�������J�=&�P|gF�!��K�W>�(��;
)	w��j�D�lBt��&D������@t�w�����:-��W�3�1R�Su����MN��F�e�M�B[:]�xNǯ�٣W*HfV�i�)��Gaתщ�{ķ�f'U||���q�Zm�,�N�Z$��4Qg�i!6�*ho���?��n�m%��䓏�4ɣh(�m+i�w[�n��I�/H�ze���1���۩��_t}�V���`��\�xw�T��5��M��;-��W>ޕ�n1�y������"6�,7+���v�6?�*�S��'��)�Hت�0�7|;�#�o$����G�]"���,�:�!��Я��e��D�S�,w:A�cS�e�o%�����o˖�o��:���8z���-����t�r�u�z]:���*��%����@���l�s���1��ʅY�sw�u�U�f���kX��L�}�}�K��-{��O�1����ǖ8�X4_#�1�
��G��n��b�r*f���B��A��C�v�����՛���n�_Ɔy�hVY��P[%-�2�l�zh��7�@C[��q��a��|�tC�qȳx����B-B�f��jU���l�к���Z��ߢ)���=j�2��ht�eȥ��6=o��A%�� ���P!�{��[�HY��,DPU��`"�Qj�E�^�u �䁜>��.�O��kI�qtI#�{I$�yiE吮5ADjh�MY@��L�MMb2
�#�F��ӛeK�LJ�Ͷ�+��nK��3ho��t��em7*o��eQ7� ��FD(5�&(|'�Y�s_
��骛� �Q+��2�wHR	Z���#8>n�&�r+87A&�R��D��>����-,���w-f$�['��v����ᵕI@�ފP���D�>�.�~��?t�$�R��K�!��څ r�#<8`L�E[�V0�G�m��<{�mlw�R���M4Q����6�c�W�VfQ�`1A�D��X�j���e�j�S���-�!����OTf��i*�[z�4������aE�0���X��p�i���
�+�c�:�^sO;6�Sb�B6����'��nh7��� �ţ��Xj�N��_Q��O����o�\�߮Q6vM�B��C�,����3g��T�%`2�k�� �1kiC�@�J��:��%i�u�q�l�]�+�a4�Jk<�� �oNe{��,c�y7�,f�a�9m�":��ؕw;y��l�^KAxJl21��.������Bm"N.E,5b�v�Dh�[,7��sH�n��ѓ6C�	�����(o�>�;�~����V|+W�$X�0�ʏ��D��v.H�{+�7���>��"� |�`Rsɰ�\wi�ITF�[�ƖRJ���tip��:�2��+*� a�rWó8�^���ڝB�Kl�^��UnJ[��I�H,7�n�Ftf�nba��SiS���l�ɗ]c�{4�"�f�����wY��)��ݘ^��t�yr�Zn����X�d�SH�0*L���VP�����������֖&�d���qO����o%��%�Jwv���Kp���~�ePVw��oN�l�Yd���֚{��jy�23����Ä[KG^�2�i��'dmw���SdQ��}�S4��D�Y��bWΪ#D9��-G���q�y�n�((t,�N�B�Aw�����N��a>���q���d�;���;��PÅ��x����t�&���y�[���r/�hzpGƾ@�V����܅���b��'�-Rʥ��'d���5z�<��%C�z��XP��I�m8��xJ�c�W��K��j��R�ިՉb�D ����Ύ�(K>ވ`��ۜ�v�����xϴ7�C�K�uYt��9�!L_�X�����0,���˟���3<��#ɕ�v��i�f���)��Rh5�ӱ�� �GU��si�r��(���l�Ǖh۟~��vț�Z����v
K9��l��W���Z�?����z~[HZ�$g+tx뜴L�K�÷���.�Y=˅����A/��V�q�5��^�����>��^%m�cQ`�I��,��t^�z� G�3���|�t������;�9ۍ�(jFf,���g����!�����Ƿ k#U���<��s��5�}�-�{�-b2��(*=���ƥPD�Y�CCҌ��z9�����N?-�D�t+��(zc�2��(��&�M53���dZ�x:��L�Y���K�cD'�� ��_���9 sm�Kd��cs-�_$A���%ȳ���O�xa������ӎ��20���J�LX����R��_��\�K���*H,퐁D.���=�\Py' E�y�%�LS:�:�c�� �K�L3�+�n�f}������?m'�ck�rڅ!�!g��D@��]���7�XF�O� �H^86�I�oK��=�66ui    �d7�w�pL>�fZk43;T��t�
5b�2Ŏ]��8vH:�s:�WBwXv� �	s�SG ��n�4�1��_�6��P�o@?��@T]��5'S$�֕n���߯dP�
�#´E�\"-b�K�n��Q�t:�5��|jP�� �[�{�+jf�+%���IKn�9Z���(~�Q��Զ�fI�'��h=/۫����	&�B���ǋ��4�J9�R�;�0�E��L)R�Ag����i�!���c�%q��ƁL8Y:�Ǣ�
^�"$�'e9�6�_l��8��9��\��2R-���~G�gMG����s�4�m>�$��N�� ��M����{����#�Aݽ�q\�@�W�p3�nz]�=#�mh�}21����bx��}�u	�S�}&��'��a5m[�ę���~?;�����`k�w�(�؈�P��m�s���/I�gf�܆����M{"V>)��~������jȡ	E�Se0<x�^�WLW���:o�<f���� ��j�x�L�t{��*"�	�_Eh( �.�]�n���^�4Q�64��/�t�W�IO�vQ��Z�y��p��ƃ�ZA�{]��nN��8a�3�#�C�\�"VK?�������^_~;c�ɏǏo�D|���>�֞�0�7���j	
CתSc�������Gھuv��&�`��$5�Ȝ�\)�3�S�I%M����hY�����O;ȡ�24��<axNe�8����1�=\�fFH:a�Rc������A��K��*F��M:|
�$az�#[y�������N���]���B�<�C&՗���y(=U[}y,���Ǻ�0�H��bq�M���)�C�	�LN~��!�� �J Cb��C����ǜ�"ڝ6�N������E����26���g�,���xdr"Mct��q9���� �j͑3_ l;��MaH�zC;���������(��k����#qU+8'��>Ӽ$�}�H�倀B(���w��:�\�k�1��JfC0����r�<%�iN܆�+�K��`��S
(�#Y �ey�ݢ����맨��(�I�:
c"g�)���M�Y�|h�8e �m��<�u�l���vA�]	!垈ը�,��tj]cF:��IB���ܲ`�ܼ�2���-kw�@����*����V�"����`��o�v�3Ȼ��Z`P܃b:�ۤ�,m�e��̣���Mp[��j��
�HE!���,��m`�$�>hi��6+�ڬ��Z��Z�A��\j��Ҹ_��C����DI����e�'H��MQ��P�)�\�y��1�+2�̪��������9k�7-T���>���.:���,w7�ż��O��
i-��Fՙd� ��Ԛ� ¬�_��M�c����tiT$�*cpSV�y�ب����m��Z{�m$;�	�S���ZM(�����)��*������|*hޮ� t��?e�?�.���E�_��r.xT9�C��0�?�����EQ���� |8L~ܓ��{����b'%�̘��bۮzw&�j��|�������RnF	�b.�?��� 憭^����T�!t��v9 !4%�.�:͙B���#]M���q�K�:1���܏`s�T�nB/1!cӽH���'�[������3 �?�pY9/��#�%3`[�$�$z�g
��/�ި5�tL�(޻���2j�:�S@�b��7J�78���$	/l0Ʊ�B �n0)Jf�;�+E�˅�w�86�+�y����߂�/�۬�yf�Q�*�!�*��W؊#�v1Y�
�-�*!-EiM?�����G��%	A���t�R��m��R�~�T)1�)2_�9LU�t[�W���4(���j��'�\ǎ�GGK�W/�R�b}�Hisę������yOX�B7|#�QP�w:�Ӊ�q2�!�E�SY�����v�p�����r3S9m����cbv%P��[NaJ���Ni�"+�Y�m����0|��N{�%+�-�%3��KA� 9���|W2�^:�nV�
�mK�"*�0=��UG(��\+�_=ن�@FB	!�dm&�7���[��0 �,EE )�p �L����+Ƞ��<�����l����N����Z`|}̈K����������w*@U"��3� ���'�k��/����0�C�#��̂?��0��;j-q�r {�~W��85чf�g��0���Rb�ZbxNU^����zc���Ӹ.5�WQ@S�Cb��cu'�a��θ�6C���IML��Sx���B�t���ܻ«V"�t�Y�t��Iӌ�'3�{~x%] �5���Q�J�ǃh��|g��o�b�$ $���#\W.j~rw�x�� ��ty�ΞY|�^?ANs�?	A�X�C�E�;/1`3)����2`P�$W��j�u�)?���PW��9S=�Ņ�_L+���ν�z8�3��A�Q1�b������-s��2�!�}F�Ϗ��Efd��`P���q���	e���P�
���PO&4T�0��Ц86��KNz9AC�l��Ggc��^�I�w!���Zn+�xÁН�(�-�*x�w����\�
&C����i�l$!b�DF�S(e�{��+�7�oB"�� ��x�K��5ڞ���ƞ�lf8z2�NYct��͔���v)u秧�ۗ��~�\:N���!�#j~�J��j*�$�m�D6�V�.h�ȇ,,�r�>���u��8EC�O�s3����\ڐ��4�u�Y/�y��i]���2Î(�tw�6�/���),c[ۗ��?�e_5�lQ@3:x�s\OF!�g6T�����4��֠����iz�-n�8WYJ�J�-i����[ݣ~�&3SSG%L�:��I��<}��0�l}�u��#��f���c�)��I�~�Hc��mT����X[AI@IcL����˹�^��������������`�RN(�*?&vL��٩��Sw�"�H���tR�N4���5�1�;9-p�be�N*�u�y���GĆ�l�V����[QN�P�rSl�Q�[��~�mj�������z^}��p��x��ޟ��.��W�}�٬�����*�(��,�B�����mǰ5 nF�%i.Y�Ur
��.u�m�.�}��A�.K�{@�Y�\k��y���Q����=6�׵zy�i�JF���ZC�%|4�aK����dhw����	�0JYW��.cX���|)�A�����S��ړ/��>�xz��ȝ��,�"���T�8��=����	ە]ȉp�j�tgʀW�<]����"�*Rc9�d�x�ɲ�S8��S.v��5�o�i�dc��wd�D��yI�D�����[��#H@�(�j���"[,?�n-�6���_�E�'=/a}�8�1����W_TiD� ��T�P���V�,�A�m�<����´ ����Q�]����D���kB�s�.>?@��0Dh���x8:z1���y�"�l���S��oi3�G\ޒD��}kZ$r�Zƽ窎���N�_44IQAь���5P�Ĵ ���	1��-6ň+r	Ë�:��&�#�H�"M���Yg�Vk��MxCC��)ұ ��
�ϵ�l-/�y0It)�*��#9�A*�L��+���3��M��7;��lDc�OS����0��S�q���w�dx�s�b웓��f�ZwoLЗ/_��_8hI����<r��`<dA<�X��Z��u�<x@���E�mu����,_����t}�T���P��k	�`��6��Ԗ!��?���m�1�C��t�Vd8��jYA!�E��W+�� �ə.gy�S-+��W�����y�s_��'���ɛ��hӋ�s����L�'/��!��H�hx��`Kc��4u��w:�|{�.$'�3��P�^��2͹�t�x�@J_�e% ��A^M�R#V���p�@')j��n�6/F'ęM�ᓬtII^���w3qL�)y�R�đ���X-���f~�Sc�'��|��J��a�0k*f�]���C�5
t�-�����Ƴ���[���i �v��L�.�|��%��%�xrhD���͟c���f���d�&��� �  ��n%�� �2wԉ��(�Q�6(�����ODvY�;D����DP��X��,<z������o~L�����q3�b,���̸��2Q�L�&14*Wm�cٴt�K�F�ͫq�#�:�F3mn��N��$�On�d�z����߻��\�6V���A�)��+������Y��%2Pu�n��yڛf-��@h�&͟�	w����U�Lq(t)��B�6ǋ��y���y���A�a�-\��CٛePA&\X�/�2z�\��px ��eY0�TyDtA���t����}�5�]�q)����>v�:8<�X~��W�u��
��t�x��*���P��ݜ⮽�9[�j=��Q��Ş�C�>�'�g����(-�#K\R'��7�S͙J�>������8i%�L��܉U?D\�}Ն�7����<�T(�ӆO��_ɮ8���$ᖑ���Yʚ\��,d�Z.���_��eX�2f��7&�U�&�!�%� A�0�%h�"���]��vbw��X{T���	Xӹ<I�����q�,���\>,%7��t6#�F�E��b�ʍߢ��"�mYQ���jjT�Y��F/�L�4d�%��a���]��X0�X��s���j��b�B�L�Ėt����^v��Nt���P���zvTP���reG,�bWjb�qyؤ8	�c�� (������ӄ���
��ih�N�-�=�*��������h��%ZF6a��h�Uk��G4�ڋb��b9q����������ܜ���׷�'ڏ�\�N>b��#N���â��.�M�eH�R�+g�<�#X,]���ϓY�����Ξ,�G�:X��8�0�;����&m\@����\��ƄpU�_������>�
�ˊ/*7g��S�n�۱�{���V	rR@���;T$o���;h��6�n�P[
�l�O��]:�l��	J���h
:�85���"�&���t!�yz��u
r
�r�h�B�j��n�Y��_��G׋�"	������[��9(k��BJQ��}���"w�!� B��#���黺�.�����s؂S9d��Hg89a�������������2�	Z��V�u�"S�h���g��ڡ ��P�b�S]>JL�I@�*a�f)K	�� �wM�t���T&.J$����r({M���Q�	�ά�8⎸����PN��.Z�v�벼z��qsl��������:��s�!�J�}��:��a`q�~��H�1���`�qY%V�0A���qR.��N4�sٻ��L�q��&�)a�8B��ړ�
�ҩO�kGG��?L��9�@&�N�`NG���Q�R����"��������F_5;���
����MhTĠ��ue��NBﯢ��ĶT{��W*�h��(���	��#��ABH��/�u��v^p��b�5(k�Q4��(t(�U�8]b;���(�.�B���Y��zu�['?�m'&iR��?�<l���;
�b�T��v9B;������T�h���-@]d�SMbɢro�eI�C��ē%S�N�9n�|�N�⠠�S�����醧�+X!��鳄I2��[�9����W��n;�E3.�G�j�}���ŵ�-)	�qT/����@a2C|��JF�7���&�9ĝ��d6�^�_���FLrvnKޗ�F�#�#�<B$s>rT�Ԥ%1/�}�����_Ř)��.P����=?�>�#iQ���P��9�0U��t�Yָ�(aB��'�ѩ�I�T�Qe�V\~*d��=�,E6�h�xi��2T����<������������&�e��7eH�-V`�#&HF��|ĺ��&6�ӯFԇҺ�;�4�� N�H�����ti��\��l����{�+���#ޟ3�ѩR0�)luV'��VM���l	f��w�ӈg���B���c�W%cP��	݄<tγ��S^ރ�1���rC�ͥc��H��V��� �ۂj'�tMY�0j���~�w�n�Ϟ��@ɤ�x���)Ȓ]ϱ��ݍ.�rZ��M�~@�:�w����՜'���zoX�u�����ו|x�5J��B�(����A5e+���&��O��P��L�FoK(2"Ƕ���6�5t	Y�AL�����*a���IL���\+ڞ� >����)7�B���e�H[���n�h?:B�����ja��6$@�rv%&2����\����v�TУ�h-�5�C���K�^q\p�}���#�hj%��ਨ�H�MNS*�oa��?��N�i���O?1B�>��b���|����#��5�c*k��,�<)��mZ��y��I-s�mƘ,×����Jf�t�K�3i����^f�1�1:��OH/=�9��,���og:#�oA�w�R��z�[�s�ow����Yh�v�Q�ٞ~�x,��\L��#g��l����N*�HJ�Y1�F̈��#g�^�n������V/�p4�r������eRBC&H�����1#��W�l�V\�(�9o�%m�킝��A�Z���+f�q�P��,����qH�v
4p;H��Ϝm5��멒]���"�YF�塶a���8�U��c�e�ʆ�abH�E� yy���oK�w0=2�)��`�ڳmRvؿm��?�9u�辸/���� ���s�S7����[� B�,���3�����M<�_��3f�@N��	�>�d�sk�)@������jJ-ͥw�J��;�%�jN��g�!e�)�=4�O����X���vt�%Jt�IEGR�L^����u�nBl���At*rh���7!�vR](I�%�t��kt^'�h�2I�ɚ�.&��ux�赠Aزr��Z.#�HJ�O��z�e��t��T��eT�_����?�^?���������Ee!��M�VJ��OM^y92x�����>��ߚ�*����9ʦE��w�U��Ö���x.�	v��o� 	!�3�t�(4ȸjj����f[Q�l��<�6s�ǔh&� ۞�ɺWa�9�!�S�����	ȭ�/@�^ɬX85�xa���9I�NãB�SzL0wwE�W��1�靻�T����`k���&��=�B����*�{#�61��x��Y�//o���n�JƑ���y0��O�
�X�ffڠ̺��[dI�_$����#��t��������~����[gD0�����֥	N�	�.��2���u4x��x�A�ǂ��[�DV����u�&� ���Hbb�=����t���( �      0	   �	  x�U�]�� �����QTԽ|�_G�1&r.�r&�"?�2���W�x��h��q�Zk�}�5�VG��~�Ȗ�<=�iy��+�1[*	�$+v�R孻�1jK��{���,�G��9�ZͲ�Rxb������ѭ�] f�W[�����D�l�{f{��t뗥1zI�������G�r*S*�=yk��i���}T>����aXg��PR�k�V��kC���gZ�3ԨV��Q3�91��X��4�9��s��j��[�����P�:ͼ�4o6m��Z��h+���*F�=>�6����j����g[v�������s��̋��3&x�'Ӛ�z9&'-�ڟ��s>V�Wq:��c�n����
8볕! �g	��1Q4�a		2��q�����ugw����2�g�
\ջ�2o/A�9	�aB���4�Гj������a�Y{��n #v�Q��u��	 �f�@�uX0}^KY̧�� �j�~���s*A�|��t�"J�%�m+}��o�"��|�Eʵ/�V�� 9�a&~(����Q|�TH={�J@��@�֢f|4��nX��	1ݔ��k��9� �a�KR�&񙥆?�����>ψ?W"�B�_䆖VVҷ��Q��-���O�=ȴ�B܆{Bt���ҧ�٧Kr,�f\ɛc#=#x�GB��3;I,��Zu�Ѣ-�X.䛐�D�q���B�>���=%AkR|�8\��z�4�V�Ś������}}��C? 3��$[y�{6�D��h����ʆB�|�ǂJ+N�I�)k$ʨ����.1 �������>Õ�����#Wer���������'11���80��qd��Bn�a���T�o��W8G��Ŵ��wY$'f�s�#y����2�P�r���O�����#އ:��A�H���D��*���2Өr\����,�&V�¥.6�d�-*��$��[@����m^&e>���c:s!�]��SM��B�5�Q��D�� �@��(Rڰ������	R�~�������xV�}��F��>z�z0|Zj�0VQZ�>��C���VZP�j�Y�hi�K�K.!�ߢ"��K!�Ps����2F�^��Q���TC��N������<	G%�u��/�Y~\�yC�B���aS����z��&���B����B�q!��"��^-K$���UD�.K�yE�y+�d�D[!X��QW���=!C�b��U��)�r]��Y���Sz�#H��	�I*NNĴ�&5ר���nr�u:$��
j���O����eZY;w�Rm��ʉ��x$�,F��D�^:����8DI%ė�c?t\]�βRM-��)`9ҟ�C8m7g\:�+@��I"*B#ۮ%i2	�)M>�� ��y5h�q���PM��Q��p���X��G{>��81�O�j���,D*���#qƲ5RO⫯@��l��b�w���֒�-�Em���$K��/��&= `�$8&x�s�[�@��;4���B|{��L ��YO�rH����۾��.��9]�Fo!@���C�@�0lT�)�!I��a��/H6����{�t�&2�j'�N�z�6_���PB�6]i�H� ��-���YH������I�5*��	�r�*$�P�܄s��ȃ���mm�v�$͸C�`1���RoJJ�	Z�)���y�:�1���3a���ps���f��G�#��Rj��/�����H��2�5�y��WmS���xA���`*!�F�RkDMP�ijC���>�)N��g��-q/'-5�~a�5g��/��pc��UE�akHf�pX��*����J�V�ڗE�8��ކd��B�X)� ����e�
F��҃�q���u>C��Ko�Y}���.����ی��
�q~����_d���{]���Q�������D��Ym�G�B���^X]�fڅ�c�+��Z��`*��`Z�@9���Br*a22S�6k?#�|�'t��ŉq!�(ͳ�.e�cƺ0i��mB��H���Ѝ�a��8XD���u��zT_�Z�E�4��a�(,�E��9�������y�* я��X?� ��]u��0��Ղ����h�x'�t�t׷/ �W��ʣؤ�u����HUO�%��U�W�$�"�B�����qM8ߴ0��6,X`<���*�o��5�|�q]��k��c~�T��K"�U�n�h���}��!���0�]���^�XR���0D�gj�b�V]���Bخ��z�Ӵ����e����"g���t�q�z!�JCZ��v��&D�6�L��@#}3��,i�6:8�iT���"E`R*|ffV'���o��;tU���i�Tu������~\i@�7^ҧ��"���u����PX X�"<�F�3P�t�Dk�v34]ϥ�	0>�_jk�ݧ��Z|�t�LV���s�<{*՛sFC�B�8ݯԑs:S�\�)ݝ�Mw�t�6ta���uҧFB�AD_�a{�?q�2t1�߿�������      =	     x�mZ[��(�>L/E@�˝�8�����Z�(Js�	Y�?)��_5Ui*W3a.�����\{��K���5	(Y����W�:�;�W��W�\J+�[�F�k������Ռ{Z�Y�L@)&;��Zk��K���$T�ZS0��'W�5��_{ �ض����pN���M���N}-IŬcg���rf�v�cL.����Ջ�XD�
��������X�+<?��4�gC�:��g؊(ׯ��H��T�c}��x�'@��]��*BY��1 �H�F�x#l�&�a�~`�>oB�����W��W��HN)ӽBN���9�\��Jz?�T��g9��v
�.�2��}��\��%���]+�:U�C領�pg�9�𽔓���)h��l��Z���0��cM�]�Z�p��<���ب�4���4
[����1�L�YLT�[�5�aW��Q�v���Q��m�3�w�"ܽ%��Q`F{Mw��Z��4������M}�ڛ&{���5�V���Uc��K 0
ÿ��tC��'�����X�K���˂�*���a�~27^^}��U;�S9,�ܾ��i�(j
��-ƂMБm0{���L��ا������E���ڀG�b�@k�s(ƌ��F	8%F N"�va����'���k�����9��8p���[k��<*��������<T��<�17�/�*�&� `�a&��t��X�~ۮ��;}y<p%�q45��z�h�����*�X<�GE�{���������6��K���!��w0����H�2qYଋ%���I�։�U�!>��A���H����n��*��=��t��ǣ��X}D�m�WLC��ps��J��M?Mb��D٭sD3p^��w�2h�����KVo30��إ��+�K� F�HK� 8
�?�C*Z��߬G��C9��lEk<�w���.��D߆�kߊ	��rj�����*�E�7`M���^,o�
vqn�`���#���a���ܺ7c�����4�����Y`$�����1ȱ%Q{9�g�;�xm7)���:��M ]! ����Аƺ�5T1�	����Iݬ�6��'!M�<���<_���e��Ȧ{7�VW� ���ŵ~&��*F�L��5@[O\g��2l��	�	Q��������&�n��b�9vM""pL��Yx;g�9m�R0�Y'5����1k��	��s��Q��D,?ކ��Q o"Q��#�1���J*���5�K��b����B��0��,v/h߲o����j���o҂ƪl�:D�ӻ7/_�cCw�d`�wC�,�c�����j���3�P�QNt�2�l���-��bn�+�O�1��ƹ��\��=7�1�֯���\���U�QMʭ/�Hd4�I#��2Γ���uz��1"v���?2=�!\9�7<��\[�n�z�m�e�jr��$ ��;|��C�k�ט���oM�t����.���c�|`=44�\�3Y�d��x��G��Xo4�e=�\��p����\�� i7;i��YN+��'"������p�h��>I�L�L�( x�Բ�8Z#%Nϋ�Jg���:��>�|&�'�gs�ќܓp�Vd}����!E�4m�W�b�8G�ӂ�Q�'�C�G(�ygȌ��ƻ�w�ɶ��Հ%'��ɫE�q�1)������1��>j�=!���SP�� �W/��ȜZs2��K���˄����|*�����Z�spĕ���1����1��%�4�6�G�t>pJ!`�5;7ea�AT�Ѻ}� �D9����g���������>B�Q�1��Sݫ�'�R�J\��,��"����hg���чA�JO�8Ȥ�(��d�����ۺW���n�G�X�(rȿ���A�+� �r�-g2[u��8c����L(ϸ�zUZ�놠7��LCL�Frg�h|~28��-f<� ��sE}��ƥ���7`҇ފ�^�SUң��1p�'p�r%�d5�1��U�MB���sz�,V�����ĸI�6�^���1��'����3�~����;�[�F�K=錀��W�#�{�����ڥp*c�W�e�8�����V�b֥ӵ�����8�ʍ���ER���;((�h�gS�������>쮳�����Z�I�:\jN.���n}	��ǲ�7��� �[��ҝ�'���[kO-6p�� �Y�d��:��11sJ����Қ��P9�.�����t��r���-f�^2W'�A���qw��/aK�w[�H� y��P�w瞵��xv��-�Gq��V�<�4�9�"��Ļ�6ѳ�wNr@���v:|�z.k��d�z�:�x�����aȶ��s�g�|�� �;(�[��ہ�>;��V<ZZ�zf�N�CFh/e~e�$\\�{:��9��6p0�|�@{���*I:��)<�t��%.�~I��#׿��_��ޮ��{{��ў��O5x�-o��Tpg|�U3����}�g��V�]qs�Щܝ�ߊ��%�x�c�M��γ��J��K���������8U<���ko�(�����+�7�d��ӷ����[[���H����ǧ�u��.+�-�����R��B�B���;şY߿[5��U=Qwe�#��Q4�,�*aB�5';a�p���/������T�E�/�
�4�h�!�y^X� p�Ւ`A{�+�nh[�cG��ڽ���^�vK|�*I�"����3pP)%�AqM)1'�I�lr�������ы��y8��6��y���|��5�2�����"�e<i�(_�@�ã���8�5'=��Q9�.�����w]��ԛ�b      /	   �  x��VMo�F=����(���8:��hZ�ѩ�eM��-H��$$��3��DKF{$꽝��ff��"��Y�0�\��$>�)%�
�Y�?eu�i�3��mI��/+*����ZPJTu�:��|��!�M�/n�����96��#0)����)��Cs��qps�鐼�Ɋ˅`��AZ1uat� 7��Z?���` � ���Pkk�?<v���%��p����ο�4�!� ��8�&7�����	� EqE��0�iN��tf"�P��L�N�qvO���6<��i������؂OWњͯP��3���^�� �6�S�3{�9��S(_���*H���_�\J��{��a�D�rа��e�Y�gW��i����(��pA�2vQ٭�B@~gj!���jQڗ��u1!�S�^�ݜ���z?�$E|�~�J7���޷s��o�ڸ�c��ZN��2�n�a�t2t��(t	7ȡ9_֣A�.��ǯ��eJHAt]&b+Ǜ���ֹ�@\1��,����b�������x3<!�ˬ2��@V̈�eK�{��8��DE,T�$W8��>�)t9����O�-�e��Ƨ�я#$�O��SD�l&"�Ӏ�5>cg)���ri��|��X[����S|$R�-����쭤o�+��(�:�YIzC:&�K�����)�;�y��
X,`=����/��~�yxC�Tk�xI�P����@P+�s�L8��O� �LF����^-0�."Yj�P�2um��o��È���sL�y��6hε�$O��N���� ?�cy��O��2}��$7�w�͝�p������8��DI�������|���n?�������f2xō�&�4sa����?/��I �Q�"s��d�S�7�]Ú���ǆ6�i\�\��֕��c����@7p�e� ƛ"a��9��k�w~�m^t���)���m���k�'����+h�H�U]��7�ٓ�fߞ],
���,�^��u�/��e�      )	      x���ɒ\9�����0W0�*�Um�7-��F�{�Ә�tf0��?=��8���Hc� P���N�(?_��fS�ɩ��������������u+5�PK��wec�&�:U���ht�}lc���k�?���?�����z�9����������|q$5M�����Y��Ky�֭u�5�8_7�CJJ��|%���}��[NeF�jmN�fCL�5�8�K7c�N.��|΍�g�M%?��VeX���2�m�o����_���ڟ�[��i��z9x�����⃯&O－����>n[�7�����j;��5:�br���r��R����z[����5�ݼ�Z�p��^��)����56�W��sc�|[�����[�Y����<�QE���a�B,2k>�9_�Te�[�{�_'���9KG/͌�k�fx�2��Tn�պ�毿NS��y�7c��I�b���r�/B���ͧfJ�����%����sք��Pk�����ye&��C�f��%o��F�brV!���4~�Zc�����j�Uє���z3��������W	9ˇ��f��Դ=�n�����
!�ݕ�gߏ����ϛS&ew�..bw����X��zc�ђ�n���d|��۷�6e��iӴFT:9�I%un�R�K�`�5|����u�,3f�N�M�|6c��l��3f2�m�:��-�_���9}��%�k%�6��zh�b�j:��涆�.T�CV������ɷi�*���ʹ���\��\T�F�?�����貫9ǳ��i�,I�c��Rr��G�u�5��~��F�&e�o�W�DM�2�A�n@�\d�uS7�`��s��yC.�7�4��1�4���=��t��=��v2z����l��6�/'j;��.ͱ��V���JG+����T��?�*�r:U�V��f,1���,&��	e�Hț�2z7�۝;��֜?k"��	�.	���A�rs�������\s;DtY�g�XJ�����t�	�Nh+��ݖ8����aŦj�χ�щv���|J�-�{�Q��F�������Jb[c��+�REUL��=�E�-����)����U�������B?P=�U�9�e����4C���v!���K�K�=ѝ��#?���}SD������O����UQ>Y%fI��*�J�+߆�%1a��*A7��(����c�������s4�W�vdǷ��
�n���_�����|�Ts�,�h̒�4�8w �P9bT�з�d�~����.�lQ*g����΃�|B�5����m�Vl����J�;A�֝�S��`.ь��2����U���7�e��+���9�(����u��dX������0&�����} Sw�P?ڝ
L�γi6���h&Ղ2�� *y;R��	�æb�ϲ &�V���4J�w������G�����*�o��T�5L$����:��Y/`������B�PD�du6MY��ˮpӆ��9�ȥ��2�h��'��%�v�O��0�t���T)-��s�E����_�|/:e���ۚ�ÎSE�W� r�]-��m����cx���p��
���鹪��,9���W�ƶ�w Yv���(����6_?�ܯ��b�"��m����ClX��O�DPD H�cA� �fv�+*S-f���j���i�˳����q�4�w=[���Uj�ز)F了�[�J>�lU� ��r1���#��������3z7��,x.�����H�$N!#�ȏ��mz��e��R�V	ϧ�1��3hu��!�����/-��窥�D88�Y��6A[�߱��s�$"�䥻ws}�.�����>�t�0Kbt�������0���'��	���Q{�
� ;*�}v4�V)���Oya��G���@�	��Q�u8�ug[Q>Ej��v���3|�5�;_\�tV�:�Y�)
�T0 ������ı������Z����=��I�<�u�B�#l�p�,�ns1|�]w�'b�Yϖ��E-M����hD������������~��<�������VU�q�������߯���7|��j�i�ga��]�ā�6��0�5z'd_��4]�����-J�5���)m =�5|Ϧ������֠��2�`���1�>-6��8oCT5Z�Z�7�����%���1�c�,��Y����>��,�N�c���4��-��� �f�a���ϥ,�e�<��-��~�ͣ�rvQ+4q��L}�.s���8B���0JJa�T�Ȫ�X^����ם����B4H��y4���`�H���:+�l����{��
��~�΢
 p=���?�
.Ȩ��7@����ُ���lwU�!WŶ�m�nY���nk�~�_Y������e�� ?��W�j� ���Our�}���Gh��E�hف����x:dT��m��۾�&vZ�.�2E��܈f�"4�p��K�1|�\�w!)^�r�T�Į鈅d��*=��sK��v��Rfc��O@��+�tj ~�g��2o�{�� (cP𬨹A%)��i�ꈭ��� ++��$v
1L(��Ņ�H)7S���� ڴq�طc�~��X��>\�S�BAJ������h�W��=w'����Ya�'�c��@�a`�b��q����Ͻ�h��x0��V*\���@8�4А�m*�궆�n�6����3L�J�ZGL�?Wr� f5�)��m�ݤ�?�TE���&��"AMV�.Gڬ8���ڶ_�}���4��,_��>�F�p��t�#�>z[���st�l��ՐU�sC�'�Gxjo�����-YM�|#g�qkP�&�Pg�ѹ�{���`ہ�ǫ�M�v��5v�{�Z���l�j�&?U�ok�^�� #Q�����mԈ�x8�) ����e�K��-[��\�f>��������8�ŊW.$�[�����s>ā�UUw�*N��M"륈
�:�s�#�f���c�@j_PP�u�`���DL�x �F�
��F�Aş��UF���f��&�INtb�������)v����M�*j�O��3ilNm�g��{f���E�L���Xo	�
�8��NmS�LWU�0|�lN1�:ú[���&��L��-b06���_zSΰرp�cw�� ���c���P�#䲸#-����nk��oT^�s�r
ge���u��Pn@߁���j�,�n��;WnAI �%�ra���1����F�*$�pR`�����W^��1��e�d,`����7�'��x�Jbe�����R�mǸ'���z��Ȣt�t�ob�{^��q��|�ϛq����4��dg�ce�$8��|���7g��_ɗ�d6��\ �ޔ!�)W� ���4u��_�y���_r���3Q�1ꢟၨ}8�8��+`�u?n~���J_g�0"Lkv���QZ���(�`�u�g�"k����x�-3呬@�zB} �Pݴ�$4H�n�w��	�3����r���h��C��_����#kˣ�/`�&a��D�X������z��-8��Ț��@�i��D�t��B�4XԒ�ආ��ByYtZ�܅%�S�I���@����� �g*^��ą����oɠMUc6�d�F�
�Gq/���	,��t鋷��6�#%�b����Y��U�)g�Nۊo�B:��C�;@g TE���N�/�a]��];x�CAڔe��[�ys������6U�`|L��u�/g?��ڷ�_��*�{L�'6(t��������x�����S0!G5O�xP3�ThC��7=��N�o�]q���N�lF+��I8f�U%A�K��q�&@��i`�*v���,ӈ#� �
*�����Rob���&\�!b�8b48X���5�A����������i��Otb!�%�,ѫ�A�ʦ9čS;���F��� xmDa_� ����>b�HLoe��3����vM+�p_MP�CRjAi�|4�mM��y�u�������t E�����l\�����s~���`���I�,�2m�BKkb�P��"�d��o9ݲv��u&]�K��{�Xy����a���f���'1~|    �Y�5�K���/:*�]�:d:bЗ��8Dd>��7q^�^�t\QB�T(I�6�^O��<���>�(�R�<Z-M��ڭx���,9z�E[������Xn�kn�d���"ŐZp�T̓��a70����V|�&]��b�"���f�&����w[�O7���	���K��h�"�����������Sܮoq;�j�oF֒5 H-"&�U�P��M0|��w�{t>\�(�H� �F�;�2�lO�J�wN<�@tq��Y�����Ghu�>0�-q0l��w~�����+���x�S����X[��2�Ɉ ��1b����ceG�KF	�{�E㏂�3���P��K��8D�.*�\�H��Bx0��F�#�����=�@�>\��iU�q �`8R�r����-?��I��o�� �VDk�/�>�Z��Y������]B*Q�,� �����G�p����)�ABS���P��L�:]��bPj�P��wf�6��#e�ف�oo������srm6'7s�0ЛPIW�^���۷=���Cwfa�`#�4x�!=1���j�Xa�����xà�ņ��BW�~������I�����b��4gp�wz��h�f�oQ�5z���n�,@�Ya ����%>֫��`42�X�\9h��Ʉȫ� ��2����P �g+�:_�4ðq�����{�O@o���KȮ��T(�/�M�JM���_�蟻�=�%g@k���:l�L(3��f�U|�j}�����bN���B���)��P�E���*V�����N���y�A���%,��(h��1���q�苬��8�c�.?aH�\�>%P%򡷜���kvi�?O�^n����/�Yɂ"��{lGg�0#Ӕ������ߢ_B��M�>R���VX��B���\K��jX�m��[��!��~.2���C����"���m�n?~H�*V�9����o��al��dhh��Ժ�s��w}�>�ٱ`��Z�+ϯ�*X��z�@(&�����ǽ�س�0ԑ�Ok�䈡(^s��(�[8H��V���}��_��YS(��L}��2��#+�~��Z9�>�}|H 
嵟�
�6r&�B	�H峪��T��.��8��_>�͇?�b50���*��p̐�A��y1��0�~����.9��QI�0��'X0�{�:�F����+'����g�Y<��w�ڲJ�uZ�1 &��������'��!�a�8/�������
��0	����)���bg${�`I�gw��!:մd	��q7�4cP�x2����^��h��l�F��J�èku�W(�$"XI���5��f���H,7�gZpt  `�v�I�%Z�Ř7�����c�6��_QT��o��Y��iHjE�r���޶�����cs�ۈ�?Φ��t�:� ��`j�5(�9��ڷ���TD�Q�Q�9W�Q%^��J����� @q(� ����?~/V�|8���X�q�G8aq��u�:�n�`��s��?�e�?o���h'�u�h[c$�NI�aM���?�:�z��e�u>�iL��$��+�>5�i	��7ly$O��q߼D��t\-�47P�~8�N��1���))��8����׷[�>����Dh"P0� �Z�(<���cϗj��Z��'wi����L���$���~r�֭�'��ן}�#O�mv�Oq��Q��>���Ǟ �u�⯓~��	4�4��M��ؐ�!�T9�
�m��y�*�S���CT U�b�
��J�ޥ��f�+ŲRJ�3}kB�]R�X�.L�Si9�n$<;0T�'��No�����0z�@^K�@໕���!B�ڊM}��lN]�I.l�D�ͳ<@�P� ��rw+y��|}�"����JG�*Q<J�^U[� ��-b�hoel9h��/�k;�S:�kK,��K��
:�p�&���"q�k �$�3@��v�Uĸs�/��m��-^yT���a�B�����X'��L�z�gn8���+���U����Ї������DI� 	=������S(f�Wi���ꮱ@���}���t��|#��O�{౺|���`r![I��ښւGbvV%�c��>��Ľ��.	& �@4/���C�$��\���;y��ͧ��֬��M�HN�-ɴ�N�׌ ���#R!��]�p�h���ܜ���:U����ߔc�W�o��Rr��I&���\E�;�oJ��YU�{$��u������u�`U�I��B����sїZ|9���3�Zj��< �-q�1J0#���-X�lw���{��.y��Hk-�@H;4���%/P�{��c��\�B��$��P�3{Q��Z�F(c6/f���?�/����ξ���ongN��B�r�S[	_?�X����Y6*����5 ��ᵔ�y8���������]�V��pv|0��Tt=�~V�2�����嬆x��Qj�@�EPw^�!�8S�֔�<�dɁ5��|q����A�a}5�M6��V�Y��ρ�?B�}���:����1���몱^DCO�
���g��?��g
��&���F-�P�#�����皶!)��Z)Q
E
P8G�L�T	�I��1r�˩}�_�-�"��,+\snh1�\w��daJ�府�:3�5�|e�k�p&�=�;v�h(k+����q����]�ף��;P�(@����t�>����h��O4�=3�I��>�CAa��
O	��1�3�l�g��XHPA_2|�3EjI�;P�9�Fnf�Xv@��G^^_֞�K�##eΈQ����[r��.�iO���~���%�.&dl��>'�Z��$���q5+����mo~�̅J�\#� ׹v�j�bP*�~���%���7L�Vn��g��'ߖ��k����&�/ô;�_RR�~����p��wt���=�v4~�$���f�B	�I�gy��mrĝ$AN��l��OP|�M	Ip��0ۈ���IR̨|���������)}EvTx�LՌ��	d6�VmԡA~�o�����5�] �a��:�m 4S�*���Q+�̯��_�ľ�^7�<��4���9��J�U�IaL�a��{=7���K�QR������JZKK�P�/����/��c�s	Ȕ 	>�����P�87�����,�E���PmK�]O�����T����݋�]���͑*��{�J��^1	.1Z�y����W��R�f���%�!		�Rm���6 �mA����q�K�^���5?��s\�$�Y v�����l9kj�-���'6_���%kud`]�����O��HZ��Hjܶ�k�n�n�9{x%��B�Ⱥ���=,
��]�]K�|C|�R/���Eoj�,Π���������a��(#qkI��Fu�XAN!$��3|�]��<c/ɴy�;�4a!kn�>�(m�YAF��J�D���l������4'\�v)����J�}��۽�q	���Ŀq��'� �%Չp��s�ˇ�Q��z+HHO#Gh���D�UW)kF?�������g��n��i �*DK�bPX_��R���Cݢw`_���.
wG�b��M�b(C��D]�P����^�X٘�����Zy4�@&@�
�z����ۨ��H�/�]	N�$�w�S1�5K�@��+-/�rhw�a%��ל�AM��SI��^���]����˒�`�Z:g� "��YS�tt%\Y1,��)��|_>���~�aFD�Ld�UP�T�Vm������r�S���DlO�8t��&u:R�5�Moz��
�s!�I�Ln�'�(��vp�V�tR4����?N%1oL�/Plp����cPq1&�,j���.
��Rl�o���wIm �M���VU�ش"�w�|�#���6Y�㨽�,b����QF����}���J����I���Q,�N�w6P�>}B1?�8�햏-�/(}<���T�*�Bk#��D��    -�z����~��[[)
=�J�gQ!7`��fe�Cސ�� ���.��{r��F�	�7R���J�U��lR�����fx��2[E�U㴭��+�H������U��ޟ�����tɸ�s���S�)9T�c6�����Gk�e�5>�cr�
 [�й
_`�9JK�b@v�mڟ��U~/���3\�M������6��F!��J�����}~P-&�G}���-���m��S������ӵ��;rT�?�	��m8�{�lwI�.&6���Yaq-̥y|��ky:�)�:�4q�Ր�_�N;��W�|��d�8���'@$ �䗻d�-1������ꀷ�_\<K>V��$J/�:�)����:/N�g���^Uɰ�j]<�Fթ9��+6<�`C�X�(�If���}��?���=�%oH^�NP�tkFI��H��7�c?���w�\�\2V�dwf�>8��2�W-�v����|����ں�h):;[܈)�}��Lɋ!2P�&w�3F���/��g"�i�KS�� ;�>���J�R3�1ok��z���g�k��Z^A�Q'G���=If�fWv���.�ՋQ|�E:�Ě�Uw��45�*Fi3�ӆ�[7��E��R�0�-߾0�͐��W��g�I��Urn1U��8����`})K�Id6�:�Xh�3}�Co�[� �/-���P���j�������˷��CB�����(]R�٥F�l�j;ѩ�{�$���]Fŧ�1��-]�@7
���b X��b��6I+��*?�a�_L���?3�n�Li�:`:T�EP
<��ѿ�0}�?nI��)��lz���P%b���_��v���G>$�E`�8,F�|���*��"x�O-*��h�����~�ۭ�E�i��f-�䢄�p���u��a��?�����6gBor`]e���@��1���b�N����(hw�$�5	_n%j9�]���|Q�B��+��m�D5�z饈շ!���u�y��L�~�>�3�{���dc~H�e�"@�if'W߷�q���,J�E$|-Bk4��4���c�u�
>	�5��|�# �y��&���Aͥ]�b�{���X)���
<�R�8�Η�`�,���������3��*��R&�*7+��U� �`�I�:�r��k��>�#;jv
o��1�,f��ƭ�}��`���?��óc��ҥ�RDe{(��FtS265����K�_R���GI3]ґJ�y�}Ŋz3R��g���^�-�W�N�zڐ:ݖ2�v�C�݈V�w��9�My|���;��q6�S)#iA�H�������C0cE����Vi�5��w�#����J�V�С��(�Kc��$I�زX\�O��z	q)6�>��ܑ�jV�б�_���?�u�A��Є��h��pM��i;DLKar\�urO�������sQ�4�N:u)Q�N?4�_��e�Lޤ� q����k�ثKGE�DJA((`�GyV��^�G���/PES��ݥ[F1J>�1�X5�y:˩�����j�k�!�P"W�b��x)�GX�z�.\���>�P/�Ft��䁆1K�v�tR �%S�`\W���b��ٌ�@\2@�O���Y��t�'����.��?W�@���OQ�P�P�r���AUێ1�P�)�G���%4Yr,�D-��P�IX�U������͜�_?HR���1����7��uh6>Uҝ�m?��Ȍ�!/�А��CT�n|5��_�xz�}��ȭ�4��.aX'�!G� >HQ����B�nk�I���=�%@�c/|��}Ar6P��t/6�~[����*�����xa�$������J�=)>�.��k��ƕz ��KFH��dt��p�I���]@۪W���Ip�dpץ���2Q(�P���N�ve��s��x��4��k@����?/*K�\��th�hAYq�ϝ���}�j\I�f㊙1eI�ҵ6 �|�a���8��w駸E�¥G3���$��ta�8��	x�ڧ���R��R/:�I*(�k��(��,=��')UN��^�`�d�g=^:/H�R	v�^��v@y���3� �g~�!�"{���V���Ғ���䥷�Yg�J�l9���I#լٝ�L�A�!ء��htb>�\~%�Bnk�q��[��=���b�dƉ�H�� �d՚R;����iY����Q�j��4VjĤ���Z ���P|�/_7i��)�jBn�u+Y��*�%��xi˄>
�5�l��Y*��1c)�_5��6a�X?+� C:������^|���:~�n�!�uk� ��.���()�T�>��|A��Қ�r��Z�+�fV�Z��Pt���d�i2����M�䩗:TAj[P��@�E�W�	Y};W�����	�u/ؤ����V�1j%J���C���'������ �ic��K$����k�Ռ���������Ǿ��s��A��SCLo���L<-��)PR��O�q�i�д-�`C��5���m�n�a���؝��^�,t3D���5N#pV.A;��O	��aG������l�i��nI�L����!�#�AT���7050��6���/���.��`�K���YV֋�	ha�V�n��R�W���c�	���|��$�}���@����Aq^�1Uޡ�W���{�$��x;��)9i�d��vr�h�������1�Y��ܤ2w�
��Q�R��,�I_X�	[��jB���Jj���^�g�W�Dh�%����J��s+X���ާ���χ	^���M��#P#���*���	��;7��iv�R�=`�t��4��kpYn|�����8%����:O�ܙ�bݠ��G���Ժ`��O�4��(�9R���bѺtjV� .���(�SI�hH�Zb�����R��hpҥ+��HE�z��5�%����w጗}��*� I�g��HS��M|�Lɗ�j%� �k��c��Ի���q�о �i[��m]�J ��SX8c߬VZ�e�}I��C#:(J2�\/���x�S�x�ζ�L�/)����Á�fE�;�S�EOni�6ν$�'����	��x��� �t���ԢC@._�WN�<!-Ć 1��xW�	�/�|�]��s>�/��)��<���(��oI�\m�	�$��	콡/�H����K�#��Ȧi$>�`ј��P�HA�m?�|m�U,��:N6x�Ii��m�ŝ�����
��}�OR&U)���*�G�]0 �[\i^rF������nk�l�����Q8;�W�;$���6��'�k���Cﱟ�ck�+ow\.�t^�Z��L?������L�c�H�>����](v���SNR]m��N�MM��(�.!�M�̕��_Z)����b��h�Ǥ�6�e6%��k�o��!�Y��F��6��K�>=�	���徟kJe�՗�t骓kF�i%ڤG��k�ٚ���^���rT�jK��g#&ߌ��+�收�z)��ד�}���d�',%~�0���baKic0�a+�>���U�z��~��������eI�Lv��z�������͘&����4G��Ƕg��*�ML��bN�JG�'����iD	�Gp�%��u4�Y�h�Y��s����}�㵿o--���pN�"��1%=L�J�U�Q+��5xo���M�t�`A �m�5�(�w݂��W]:��s�9Xך��A'CL�����J]�U�cv'DG�$@C�)��h5�C&���)U��٘��څ��
����T��:y�S�*�u��B_Ӥ�ɘ���b��ފGo+�8�{��Ul-�l$)A�5���5W9=��/��>e�ߧ˃F k��P���]I�0��<�s[��!ǲt�)��Zd|�:�0�1�Rk���ձZc2�`6�t���p��J�VF�jqkEU���y�Y�[π�Cj7��R�}tt�����������&��bZ������[7���9���[US��.���Q6�3ϧ=����IR��T������PkmH�aTRf���m������n�x*���L�nlEQ5%ܾEq�    j����?�;�����<Wv�l�i��h-��� u/��cX/#��s��:V��eҿ�up��P�Vr��f�qqz�ݷ0�K��Ps���ɲa	ɑ|�(Aۖ��~����Ey �_�-c�J_+XR���f� x�$����Rß$�B���VcQ&hO�Z��K�+��ʟ`�~��׏�T���R7$2;�����[����Ε՟R�%�����^�ޱ�&�8W$^�.�1��O�VNٙ/_����p�+��4*4Q��h�i��$v�$��wT���sjs8y,��/�#�x\��̌O2�O��s�n����s��Kc� �H���|-*���+5�Bǋ�I�T�G>�BEz] ��q�+J���n���q��_?�V�\��[	]��T��K�Y��U�/�.��:�jz��\�p>,�4��`��&Kj�n/9���%��~��_y�����C�$�$I:/⛅-�JXy�{��r��z�#]
\�<>?�u��uSL�%�l�*��:�rbY�岺$���9�V���ڥ!����z���'uT�`��[C�C�Eq�3ץp�|�R��txVx�=��h��jB>9�1����̈́ECǣ����ċ>�ߥ5n�\*�B�CW�?G�\��7��q���O����rKI�^����,rٱV�[Z�,��QF��s|m���ذ�JR;c�>8H.�\�)W��V�-9C'��.ʪ���e�\`�JD)Z�a����W��������:�gJwy���1l�k��g�jߧ�~Ĺ)��ςJU[��J���6�����zq�0��P�x'�Ph�`���Gnl��g�����b��%�%��NI�߈�q��/�еv�Z������U$:�D4>�<���
^�B����"&�/76��'��|^�"���-��]6&MQH����-V�qt�<Do�h1�
k_�=@ͻ���g�
����s��W��q�8#E�gmUE&�/"?Z��Ij%���m���+"�/E����%���kE���Q�r����W�������T��%eG�e%�C8D^wY��>������Jg����g�F�g�Z�t�P�^e���\o!9�R�8Bn�]9y��Ig��L�0[+ƿ:��b|����|�l�R�#C^�MUJ��$ʄ^ʸ����^��,��q>���+JMl�F ���|���Y�O	rDy�����*lWUBW���ٛ���d�~�(zs�E��k���G" ��h�$q�PT���ؗg��i�~��}qrU)%�j���4HQM��mV⚷5|�U��(��0;�XU1�)��VE.��=��:����T��su�u�8�4uc:QuBЪr�k(�TnC�����|���r�]#�-�3[����m2�{w�٣$%�]�y2�bQ���xy���[���R��� <��ɮ��*�����w�?�4e!�Y��9h��$�����1��ݲ����Kn��K&�F����bJ�H��g-�$��n�H��%چ�ži����g��	�~�?;�~/j/LC�PI\H��
�^ImXi�D��������j�/�N��B�H�8�_u�.�'
�d���=��m��2\X��P�p�ڷ����}g���3��:׌���S��j�\�C��(�)%��_ejn�s.����!����i����eH+�	�[�ՐBvi�%��T�c�am�x���q>$݁�$������$̿����[3��S��}&-�U�R�דU*�����-���9t�(���4)�?p�.a��?v��;_�#<=�v�软�B���0�C���3��0=��Kl�y����}�ƌ���]ۓx��y5��~y�b��_��X�Ն��9�l�+���"��@ț
�y�M�>�ļKz������dQMՑ���.����tU�1g����X1��!h�J
��E��չ��n�p�h�	�����WھԻt6�wϮ��m��L���4	�ұv(i��ΦM.�4�Kgd�ڷ�ۮIg���p���W�����̜��M>B6���*L�1�f�!��ɴ���:�U@c'>et�4�ұ��7�[ws�Wb��i�=��t�8�ÌZz<J���$�����dfNaQ��}����=���!_�����D��t��.�K^-����l��c�Gzm��r3�|b?�����6����u��\�̹61Gia�ȧ䊕��w��-�>%oaKĺ��"h��(�|��ayXkrZN��s��CvL�Y85��R��*����Vj)P�h�K��~�p]�I�)���n����j⯍D��E~F���I�BH��1�"o_��Fl�T��;6�97B�n���'){�RO/�-���������у1�(�l"0ܷ�}�B��VWA�u=�L��H�K�*�k^�(	5o���{�����Ǎ6B"��GJ��x6F�i��0�t��Fg}��!P(w 3W|�M-��}�0�vߚm$�.��(�rTd�0z���I�׈�|�!��w�+����BQ��*ά��Gi�)O�U]VWx��,�|܌�1]�Y�����1J��*��b�<�t[�i/����%��x=*�?8E^��g���T'o����X�l�ǲ]"��k�����΂�,�f����H-�����I��k)���B���E@,���T0�e%_�����I�Մ��G+5�ym	�h[;)i��W�����lB��"ن�	Bm�`�>�<d0��w�n��Vh��Sޥ~�o��`�Hu.�L و]�%�ۋh#�r��w��Mx��_{HT!� �6!��{�b�����s�o��~a�0��87���,KwJ[�<�"��ߊY���;���nRA��O�D����X�]�JD�b��^�Z��Cc����5�H�p�p�Ú�YT�8��m-�5�z������1V���\�Pܘ�- ��$�$ , �4�|ߊˣ?�Z�}�(����(}.�����Ҙe����H��6W���P�)�	m�Q?Lׇ�<1(÷�l������y�)B^��V��@:$_�.�_��oB���A���#�Z�4�SX7@k�'z��b�l����^�$��F`�-�� MP��۰�IE�|5�s'cSںl}���>�R�FYQ��
� ������E�cK2�Hԩ=�t���U�u�i��#�aB7����;߿�~"){�٥����G%���^f�� �����nk����~=��WǺ~�!m>�1�xdy}@I�b����(���%�;���ƶ��sSݢd���2$5͚��\J}�����>��f� �N�E��S^5G���\���)%�6������.�4$�\�]���CEc��W��}�Խ�H�1I�9�ِN�P>�-�� 2�x�"N�M�{�	����;"\޶G:]��΢�����g��[4�E*w���c{�K�{^BPm�Z,E*��X�Lq�5y��/�r�����J�&�T1�K�+�^��̈�в>jz���};���K�:{}}ɺi�yX���(Z�3hF��n�w������q��a�>�	 4��CHڋ[�{�|~i���i`͵^=�˔�������Fwp�D�q����q�R����
��P�u%I�h̄�oI$�dk_�ۥG�0�����9%y;3J�
C^a[ҺA��ay�w^���[�i���5H�u�����?����i=���Wm=�0�g����E�Gp�,����E:"[a��v����|�Q���S���V���g�K��"�e,%���C��Y���͇�K��}��:��@�=�UEٯ:��u������pz�BʬQ��3z'��$$ע_�a�h��'��Q�=x:J���5�5���VO��GUx_�f��"�*���f$`y�ւǘJ^hFn�������[�A��E��c�./�]�u#ofHA�m>����:��S����C!!�R���457#o�T8���o��Zo�
�x	����O�v�Q1����p!�iS�߃��])]+婁 �C���E�
�j��L�6�i��(E�dXy���ZA�CI�˒5ӥ�Y�B& 7   �&�,��K#��v@��XJ�)>$�&��C���䵿U$��m�������?�X�     