PGDMP                         |         	   bdmatrrhh    9.4.8    9.4.6 �    9	           0    0    ENCODING    ENCODING     #   SET client_encoding = 'SQL_ASCII';
                       false            :	           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                       false            ;	           1262    52772 	   bdmatrrhh    DATABASE     l   CREATE DATABASE bdmatrrhh WITH TEMPLATE = template0 ENCODING = 'SQL_ASCII' LC_COLLATE = 'C' LC_CTYPE = 'C';
    DROP DATABASE bdmatrrhh;
             roberto    false                        2615    2200    public    SCHEMA        CREATE SCHEMA public;
    DROP SCHEMA public;
             postgres    false            <	           0    0    SCHEMA public    COMMENT     6   COMMENT ON SCHEMA public IS 'standard public schema';
                  postgres    false    8            =	           0    0    public    ACL     �   REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;
                  postgres    false    8                        3079    11859    plpgsql 	   EXTENSION     ?   CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;
    DROP EXTENSION plpgsql;
                  false            >	           0    0    EXTENSION plpgsql    COMMENT     @   COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';
                       false    1                        3079    52773    dblink 	   EXTENSION     :   CREATE EXTENSION IF NOT EXISTS dblink WITH SCHEMA public;
    DROP EXTENSION dblink;
                  false    8            ?	           0    0    EXTENSION dblink    COMMENT     _   COMMENT ON EXTENSION dblink IS 'connect to other PostgreSQL databases from within a database';
                       false    2                       1255    791898     actualizar_jefes_unidad(integer)    FUNCTION     W  CREATE FUNCTION actualizar_jefes_unidad(integer) RETURNS character varying
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
       public       roberto    false    1    8                       1255    808669    actualizar_pagos_mensuales()    FUNCTION     �  CREATE FUNCTION actualizar_pagos_mensuales() RETURNS trigger
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
       public       roberto    false    8    1                       1255    268708    actualizar_trabajadores()    FUNCTION     [  CREATE FUNCTION actualizar_trabajadores() RETURNS trigger
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
       public       roberto    false    8    1                       1255    535189    cambiar_condicion()    FUNCTION     �  CREATE FUNCTION cambiar_condicion() RETURNS trigger
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
       public       roberto    false    8    1                       1255    808608    crear_pagos_mensuales()    FUNCTION     F  CREATE FUNCTION crear_pagos_mensuales() RETURNS trigger
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
       public       roberto    false    8    1                       1255    535159    insertar_condicion()    FUNCTION     ^  CREATE FUNCTION insertar_condicion() RETURNS trigger
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
       public       roberto    false    1    8                       1255    264107    ultimo_dia_del_mes(date)    FUNCTION     O  CREATE FUNCTION ultimo_dia_del_mes(date) RETURNS double precision
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
       public       roberto    false    8    1            �            1259    553269    adam_vw_dotacion_briqven_02_mas    TABLE     M  CREATE TABLE adam_vw_dotacion_briqven_02_mas (
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
       public       roberto    false    199    8            @	           0    0    baremo_id_seq    SEQUENCE OWNED BY     1   ALTER SEQUENCE baremo_id_seq OWNED BY baremo.id;
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
       public       roberto    false    8    196            A	           0    0    condiciones_idcondicion_seq    SEQUENCE OWNED BY     M   ALTER SEQUENCE condiciones_idcondicion_seq OWNED BY condiciones.idcondicion;
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
       public       roberto    false    201    8            B	           0    0    periodo_e_num_periodo_seq    SEQUENCE OWNED BY     I   ALTER SEQUENCE periodo_e_num_periodo_seq OWNED BY periodo_e.num_periodo;
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
       public         postgres    false    8            C	           0    0     COLUMN registro_diario.bloqueado    COMMENT     b   COMMENT ON COLUMN registro_diario.bloqueado IS 'Si el registro no permite cambios (0=no; 1 =si)';
            public       postgres    false    197            D	           0    0    registro_diario    ACL     �   REVOKE ALL ON TABLE registro_diario FROM PUBLIC;
REVOKE ALL ON TABLE registro_diario FROM postgres;
GRANT ALL ON TABLE registro_diario TO postgres;
            public       postgres    false    197            �            1259    63042    seleccion_regalos    TABLE     �   CREATE TABLE seleccion_regalos (
    trabajador character varying(10) NOT NULL,
    periodo character varying(6) DEFAULT '201601'::character varying NOT NULL,
    fkopcion integer NOT NULL,
    estatus character varying(20)
);
 %   DROP TABLE public.seleccion_regalos;
       public         roberto    false    8            �            1259    808145    sibes_beneficiarios    TABLE     v  CREATE TABLE sibes_beneficiarios (
    idbeneficiario integer NOT NULL,
    trabajador character varying(10) NOT NULL,
    fecha_nac date NOT NULL,
    sexo_beneficiario character(1) NOT NULL,
    sexo_trabajador character(1) NOT NULL,
    pago_colegio boolean,
    estatus_beneficio character varying(10) NOT NULL,
    nombre_beneficiario character varying(80) NOT NULL
);
 '   DROP TABLE public.sibes_beneficiarios;
       public         roberto    false    8            �            1259    808143 &   sibes_beneficiarios_idbeneficiario_seq    SEQUENCE     �   CREATE SEQUENCE sibes_beneficiarios_idbeneficiario_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 =   DROP SEQUENCE public.sibes_beneficiarios_idbeneficiario_seq;
       public       roberto    false    8    212            E	           0    0 &   sibes_beneficiarios_idbeneficiario_seq    SEQUENCE OWNED BY     c   ALTER SEQUENCE sibes_beneficiarios_idbeneficiario_seq OWNED BY sibes_beneficiarios.idbeneficiario;
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
       public       roberto    false    214    8            F	           0    0    sibes_colegios_idcolegio_seq    SEQUENCE OWNED BY     O   ALTER SEQUENCE sibes_colegios_idcolegio_seq OWNED BY sibes_colegios.idcolegio;
            public       roberto    false    213            �            1259    808279    sibes_detfacturas    TABLE     �   CREATE TABLE sibes_detfacturas (
    iddetfactura integer NOT NULL,
    fkfactura integer NOT NULL,
    fkmensualidad integer NOT NULL,
    mes integer,
    monto character varying(15) NOT NULL,
    corresponde character varying(15)
);
 %   DROP TABLE public.sibes_detfacturas;
       public         roberto    false    8            �            1259    808277 "   sibes_detfacturas_iddetfactura_seq    SEQUENCE     �   CREATE SEQUENCE sibes_detfacturas_iddetfactura_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 9   DROP SEQUENCE public.sibes_detfacturas_iddetfactura_seq;
       public       roberto    false    222    8            G	           0    0 "   sibes_detfacturas_iddetfactura_seq    SEQUENCE OWNED BY     [   ALTER SEQUENCE sibes_detfacturas_iddetfactura_seq OWNED BY sibes_detfacturas.iddetfactura;
            public       roberto    false    221            �            1259    808238    sibes_facturas    TABLE     �  CREATE TABLE sibes_facturas (
    idfactura integer NOT NULL,
    nro_factura character varying(10) NOT NULL,
    fecha_factura date NOT NULL,
    monto_total character varying(15) NOT NULL,
    subtotal character varying(15) NOT NULL,
    iva character varying(6) NOT NULL,
    fkcolegio integer NOT NULL,
    login_registro character varying(6) NOT NULL,
    fecha_registro timestamp without time zone NOT NULL
);
 "   DROP TABLE public.sibes_facturas;
       public         roberto    false    8            �            1259    808236    sibes_facturas_idfactura_seq    SEQUENCE     ~   CREATE SEQUENCE sibes_facturas_idfactura_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 3   DROP SEQUENCE public.sibes_facturas_idfactura_seq;
       public       roberto    false    8    218            H	           0    0    sibes_facturas_idfactura_seq    SEQUENCE OWNED BY     O   ALTER SEQUENCE sibes_facturas_idfactura_seq OWNED BY sibes_facturas.idfactura;
            public       roberto    false    217            �            1259    808208    sibes_inscripciones    TABLE     �  CREATE TABLE sibes_inscripciones (
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
    mes_inicio integer NOT NULL
);
 '   DROP TABLE public.sibes_inscripciones;
       public         roberto    false    8            �            1259    808206 %   sibes_inscripciones_idinscripcion_seq    SEQUENCE     �   CREATE SEQUENCE sibes_inscripciones_idinscripcion_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 <   DROP SEQUENCE public.sibes_inscripciones_idinscripcion_seq;
       public       roberto    false    8    216            I	           0    0 %   sibes_inscripciones_idinscripcion_seq    SEQUENCE OWNED BY     a   ALTER SEQUENCE sibes_inscripciones_idinscripcion_seq OWNED BY sibes_inscripciones.idinscripcion;
            public       roberto    false    215            �            1259    808259    sibes_mensualidades    TABLE     �  CREATE TABLE sibes_mensualidades (
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
    estatus character varying(10)
);
 '   DROP TABLE public.sibes_mensualidades;
       public         roberto    false    8            �            1259    808257 %   sibes_mensualidades_idmensualidad_seq    SEQUENCE     �   CREATE SEQUENCE sibes_mensualidades_idmensualidad_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 <   DROP SEQUENCE public.sibes_mensualidades_idmensualidad_seq;
       public       roberto    false    220    8            J	           0    0 %   sibes_mensualidades_idmensualidad_seq    SEQUENCE OWNED BY     a   ALTER SEQUENCE sibes_mensualidades_idmensualidad_seq OWNED BY sibes_mensualidades.idmensualidad;
            public       roberto    false    219            �            1259    1057160    sistema_horario    TABLE     s   CREATE TABLE sistema_horario (
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
       public         roberto    false    8            K	           0    0    tbl_auditorias    ACL     �   REVOKE ALL ON TABLE tbl_auditorias FROM PUBLIC;
REVOKE ALL ON TABLE tbl_auditorias FROM roberto;
GRANT ALL ON TABLE tbl_auditorias TO roberto;
            public       roberto    false    208            �            1259    551161    tbl_auditorias_idauditoria_seq    SEQUENCE     �   CREATE SEQUENCE tbl_auditorias_idauditoria_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 5   DROP SEQUENCE public.tbl_auditorias_idauditoria_seq;
       public       roberto    false    8    208            L	           0    0    tbl_auditorias_idauditoria_seq    SEQUENCE OWNED BY     S   ALTER SEQUENCE tbl_auditorias_idauditoria_seq OWNED BY tbl_auditorias.idauditoria;
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
       public       roberto    false    176    185    185    176    8            �            1259    383515    trabajadores_activos_con_jefes    VIEW     Y  CREATE VIEW trabajadores_activos_con_jefes AS
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
       public       roberto    false    189    189    189    186    186    186    177    177    177    177    176    176    176    176    176    8            �            1259    540003    trabajadores_encargados    TABLE     �  CREATE TABLE trabajadores_encargados (
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
       public       roberto    false    176    176    206    204    204    204    189    189    189    177    176    177    177    177    176    176    206    206    206    206    206    206    206    206    206    206    8            �            1259    153994    unidades    TABLE       CREATE TABLE unidades (
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
       public       roberto    false    176    176    177    177    177    8            �            1259    382154    vista_cumples    VIEW     Q  CREATE VIEW vista_cumples AS
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
       public       roberto    false    184    184    184    177    177    177    177    176    176    278    176    176    176    176    176    8            L           2604    536879    id    DEFAULT     X   ALTER TABLE ONLY baremo ALTER COLUMN id SET DEFAULT nextval('baremo_id_seq'::regclass);
 8   ALTER TABLE public.baremo ALTER COLUMN id DROP DEFAULT;
       public       roberto    false    199    198    199            D           2604    535066    idcondicion    DEFAULT     t   ALTER TABLE ONLY condiciones ALTER COLUMN idcondicion SET DEFAULT nextval('condiciones_idcondicion_seq'::regclass);
 F   ALTER TABLE public.condiciones ALTER COLUMN idcondicion DROP DEFAULT;
       public       roberto    false    195    196    196            M           2604    536891    num_periodo    DEFAULT     p   ALTER TABLE ONLY periodo_e ALTER COLUMN num_periodo SET DEFAULT nextval('periodo_e_num_periodo_seq'::regclass);
 D   ALTER TABLE public.periodo_e ALTER COLUMN num_periodo DROP DEFAULT;
       public       roberto    false    200    201    201            P           2604    808148    idbeneficiario    DEFAULT     �   ALTER TABLE ONLY sibes_beneficiarios ALTER COLUMN idbeneficiario SET DEFAULT nextval('sibes_beneficiarios_idbeneficiario_seq'::regclass);
 Q   ALTER TABLE public.sibes_beneficiarios ALTER COLUMN idbeneficiario DROP DEFAULT;
       public       roberto    false    212    211    212            Q           2604    808183 	   idcolegio    DEFAULT     v   ALTER TABLE ONLY sibes_colegios ALTER COLUMN idcolegio SET DEFAULT nextval('sibes_colegios_idcolegio_seq'::regclass);
 G   ALTER TABLE public.sibes_colegios ALTER COLUMN idcolegio DROP DEFAULT;
       public       roberto    false    213    214    214            V           2604    808282    iddetfactura    DEFAULT     �   ALTER TABLE ONLY sibes_detfacturas ALTER COLUMN iddetfactura SET DEFAULT nextval('sibes_detfacturas_iddetfactura_seq'::regclass);
 M   ALTER TABLE public.sibes_detfacturas ALTER COLUMN iddetfactura DROP DEFAULT;
       public       roberto    false    222    221    222            S           2604    808241 	   idfactura    DEFAULT     v   ALTER TABLE ONLY sibes_facturas ALTER COLUMN idfactura SET DEFAULT nextval('sibes_facturas_idfactura_seq'::regclass);
 G   ALTER TABLE public.sibes_facturas ALTER COLUMN idfactura DROP DEFAULT;
       public       roberto    false    218    217    218            R           2604    808211    idinscripcion    DEFAULT     �   ALTER TABLE ONLY sibes_inscripciones ALTER COLUMN idinscripcion SET DEFAULT nextval('sibes_inscripciones_idinscripcion_seq'::regclass);
 P   ALTER TABLE public.sibes_inscripciones ALTER COLUMN idinscripcion DROP DEFAULT;
       public       roberto    false    215    216    216            T           2604    808262    idmensualidad    DEFAULT     �   ALTER TABLE ONLY sibes_mensualidades ALTER COLUMN idmensualidad SET DEFAULT nextval('sibes_mensualidades_idmensualidad_seq'::regclass);
 P   ALTER TABLE public.sibes_mensualidades ALTER COLUMN idmensualidad DROP DEFAULT;
       public       roberto    false    220    219    220            O           2604    551166    idauditoria    DEFAULT     z   ALTER TABLE ONLY tbl_auditorias ALTER COLUMN idauditoria SET DEFAULT nextval('tbl_auditorias_idauditoria_seq'::regclass);
 I   ALTER TABLE public.tbl_auditorias ALTER COLUMN idauditoria DROP DEFAULT;
       public       roberto    false    207    208    208            '	          0    553269    adam_vw_dotacion_briqven_02_mas 
   TABLE DATA               �  COPY adam_vw_dotacion_briqven_02_mas (trabajador, nombre, sexo, fecha_ingreso, fecha_nacimiento, relacion_laboral, sistema_horario, talla_camisa, talla_pantalon, talla_zapatos, codigo_carnet, serial_carnet, procedencia, trabajador_onapre, contratacion_onapre, grado_trab, rango_trab, condicion, tipo_discapacidad, salario, ccosto, detalle_ccosto, direccion, gergral, gerencia, depto, coordina, puesto, desc_puesto, nivel_jerarquico, desc_nivel_jerarquico, grupo, area, subarea, detalle_subarea, encuadre_puesto, encuadre_onapre, clasificacion_onapre, encuadre2_onapre, puesto_superior, desc_psuperior, trabajador_sup, nombre_sup, grado_instruccion, titulo_profesional, siglado, email) FROM stdin;
    public       roberto    false    209   �I      	          0    536876    baremo 
   TABLE DATA               @   COPY baremo (id, puntuacion, resultado, porcentaje) FROM stdin;
    public       roberto    false    199    �      M	           0    0    baremo_id_seq    SEQUENCE SET     4   SELECT pg_catalog.setval('baremo_id_seq', 5, true);
            public       roberto    false    198            	          0    62908    carga_familiar_hcm 
   TABLE DATA               �   COPY carga_familiar_hcm (trabajador, persona_relacionada, nombre, sexo, fecha_nacimiento, domicilio, domicilio2, telefono_particular, indice_inf_soc, secuencia, hcm, maternidad, dato_01, dato_02, dato_03, dato_04, dato_05, sit_carga) FROM stdin;
    public       roberto    false    175   ��      	          0    107388    causas_baja 
   TABLE DATA               7   COPY causas_baja (causa, descripcion_baja) FROM stdin;
    public       roberto    false    183   ��      	          0    357710    ccostos_x_gerencias 
   TABLE DATA               N   COPY ccostos_x_gerencias (ccosto, gerencia, descripcion_gerencia) FROM stdin;
    public       roberto    false    189   Z�      	          0    535063    condiciones 
   TABLE DATA               6   COPY condiciones (idcondicion, condicion) FROM stdin;
    public       roberto    false    196   0�      N	           0    0    condiciones_idcondicion_seq    SEQUENCE SET     C   SELECT pg_catalog.setval('condiciones_idcondicion_seq', 10, true);
            public       roberto    false    195            	          0    535022    condiciones_trab 
   TABLE DATA               <   COPY condiciones_trab (trabajador, fkcondicion) FROM stdin;
    public       roberto    false    194   ��      "	          0    536900 
   evaluacion 
   TABLE DATA               b   COPY evaluacion (periodo, trabajador, puntuacion, observacion, supervisor, fecha_reg) FROM stdin;
    public       roberto    false    202   ��      	          0    358377    gerencias_generales 
   TABLE DATA               d   COPY gerencias_generales (descripcion_ggral, ccosto_gral, desccosto_gral, dependientes) FROM stdin;
    public       roberto    false    190   ��      O	           0    0    idunidad_seq    SEQUENCE SET     7   SELECT pg_catalog.setval('idunidad_seq', 61902, true);
            public       roberto    false    182            !	          0    536888 	   periodo_e 
   TABLE DATA               ?   COPY periodo_e (num_periodo, desde, hasta, status) FROM stdin;
    public       roberto    false    201   U�      P	           0    0    periodo_e_num_periodo_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('periodo_e_num_periodo_seq', 7, true);
            public       roberto    false    200            (	          0    739072    periodos_nomina 
   TABLE DATA               �   COPY periodos_nomina (inicio, fin, tipo_nomina, mes, anio, abierto, fecha_cierre, fecha_pago, id_calendario, semanas) FROM stdin;
    public       roberto    false    210   ��      	          0    63045    regalos 
   TABLE DATA               F   COPY regalos (idopcion, descripcion_regalo, grupo_opcion) FROM stdin;
    public       roberto    false    181   B�      	          0    536852    registro_diario 
   TABLE DATA               �   COPY registro_diario (trabajador, fecha, entrada_real1, salida_real1, asistio, sobre_tiempo, comision, cambio_turno, inasistencia, observacion, fecha_reg, trabajador_reg, bloqueado, turno, grupo) FROM stdin;
    public       postgres    false    197   ��      	          0    63042    seleccion_regalos 
   TABLE DATA               L   COPY seleccion_regalos (trabajador, periodo, fkopcion, estatus) FROM stdin;
    public       roberto    false    180   ��      *	          0    808145    sibes_beneficiarios 
   TABLE DATA               �   COPY sibes_beneficiarios (idbeneficiario, trabajador, fecha_nac, sexo_beneficiario, sexo_trabajador, pago_colegio, estatus_beneficio, nombre_beneficiario) FROM stdin;
    public       roberto    false    212   ��      Q	           0    0 &   sibes_beneficiarios_idbeneficiario_seq    SEQUENCE SET     N   SELECT pg_catalog.setval('sibes_beneficiarios_idbeneficiario_seq', 1, false);
            public       roberto    false    211            ,	          0    808180    sibes_colegios 
   TABLE DATA               �   COPY sibes_colegios (idcolegio, rif_colegio, nombre_colegio, estatus_colegio, direccion_colegio, localidada_colegio) FROM stdin;
    public       roberto    false    214   �      R	           0    0    sibes_colegios_idcolegio_seq    SEQUENCE SET     D   SELECT pg_catalog.setval('sibes_colegios_idcolegio_seq', 1, false);
            public       roberto    false    213            4	          0    808279    sibes_detfacturas 
   TABLE DATA               e   COPY sibes_detfacturas (iddetfactura, fkfactura, fkmensualidad, mes, monto, corresponde) FROM stdin;
    public       roberto    false    222   +�      S	           0    0 "   sibes_detfacturas_iddetfactura_seq    SEQUENCE SET     J   SELECT pg_catalog.setval('sibes_detfacturas_iddetfactura_seq', 1, false);
            public       roberto    false    221            0	          0    808238    sibes_facturas 
   TABLE DATA               �   COPY sibes_facturas (idfactura, nro_factura, fecha_factura, monto_total, subtotal, iva, fkcolegio, login_registro, fecha_registro) FROM stdin;
    public       roberto    false    218   H�      T	           0    0    sibes_facturas_idfactura_seq    SEQUENCE SET     D   SELECT pg_catalog.setval('sibes_facturas_idfactura_seq', 1, false);
            public       roberto    false    217            .	          0    808208    sibes_inscripciones 
   TABLE DATA               �   COPY sibes_inscripciones (idinscripcion, fkbeneficiario, fkcolegio, fecha_inscripcion, anio_escolar, monto_inscripcion, monto_mensual, login_registro, fecha_registro, estatus_inscripcioin, mes_inicio) FROM stdin;
    public       roberto    false    216   e�      U	           0    0 %   sibes_inscripciones_idinscripcion_seq    SEQUENCE SET     M   SELECT pg_catalog.setval('sibes_inscripciones_idinscripcion_seq', 1, false);
            public       roberto    false    215            2	          0    808259    sibes_mensualidades 
   TABLE DATA                 COPY sibes_mensualidades (idmensualidad, fkinscripcion, monto_inscripcion, mes_09, mes_10, mes_11, mes_12, mes_01, mes_02, mes_03, mes_04, mes_05, mes_06, mes_07, mes_08, deuda, pagado, ultimo_mes_pagado, monto_ultimo_mes, fecha_ult_pago, pago_prox, estatus) FROM stdin;
    public       roberto    false    220   ��      V	           0    0 %   sibes_mensualidades_idmensualidad_seq    SEQUENCE SET     M   SELECT pg_catalog.setval('sibes_mensualidades_idmensualidad_seq', 1, false);
            public       roberto    false    219            6	          0    1057160    sistema_horario 
   TABLE DATA               @   COPY sistema_horario (sistema_horario, descripcion) FROM stdin;
    public       roberto    false    224   ��      5	          0    1030806    supervisores_trabajadores 
   TABLE DATA               b   COPY supervisores_trabajadores (trabajador, nombre, nivel_jerarquico, trabajador_sup) FROM stdin;
    public       roberto    false    223   ��      &	          0    551163    tbl_auditorias 
   TABLE DATA               G   COPY tbl_auditorias (idauditoria, fecha, operacion, login) FROM stdin;
    public       roberto    false    208   �      W	           0    0    tbl_auditorias_idauditoria_seq    SEQUENCE SET     E   SELECT pg_catalog.setval('tbl_auditorias_idauditoria_seq', 2, true);
            public       roberto    false    207            	          0    268643    temp_trabajadores 
   TABLE DATA               <  COPY temp_trabajadores (trabajador, registro_fiscal, nombre, sexo, fecha_nacimiento, domicilio, domicilio2, poblacion, estado_provincia, pais, codigo_postal, calles_aledanas, telefono_particular, reg_seguro_social, domicilio3, e_mail, fkunidad, tipo_documento, nombres, apellidos, edo_civil, supervisor) FROM stdin;
    public       roberto    false    188   2�      	          0    63007 	   temporal1 
   TABLE DATA               �   COPY temporal1 (trabajador, persona_relacionada, nombre, sexo, fecha_nacimiento, domicilio, domicilio2, telefono_particular, indice_inf_soc, secuencia, hcm, maternidad, dato_01, dato_02, dato_03, dato_04, dato_05, sit_carga) FROM stdin;
    public       roberto    false    179   $X      	          0    62911    trabajadores 
   TABLE DATA               +  COPY trabajadores (trabajador, registro_fiscal, nombre, sexo, fecha_nacimiento, domicilio, domicilio2, poblacion, estado_provincia, pais, codigo_postal, calles_aledanas, telefono_particular, reg_seguro_social, domicilio3, e_mail, fkunidad, tipo_documento, nombres, apellidos, edo_civil) FROM stdin;
    public       roberto    false    176   �X      $	          0    540003    trabajadores_encargados 
   TABLE DATA               �   COPY trabajadores_encargados (trabajador, e_mail, nombres, apellidos, cargo, clase_nomina, supervisor, nombres_jefe, fkunidad, gerencia, descripcion_gerencia) FROM stdin;
    public       roberto    false    206   O      	          0    62914    trabajadores_grales 
   TABLE DATA               4  COPY trabajadores_grales (trabajador, fecha_ingreso, fecha_antiguedad, fecha_baja, fecha_vto_contrato, causa_baja, relacion_laboral, telefono_oficina, extension_telefonica, clase_nomina, sistema_antiguedad, sistema_horario, turno, forma_pago, sit_trabajador, grupo_sanguinio, cargo, ctadeposito) FROM stdin;
    public       roberto    false    177   Z      	          0    256376    trabajadores_supervisores 
   TABLE DATA               D   COPY trabajadores_supervisores (trabajador, supervisor) FROM stdin;
    public       roberto    false    185   .a      #	          0    537624    trabajadores_supervisores_1 
   TABLE DATA               N   COPY trabajadores_supervisores_1 (trabajador, ccosto, supervisor) FROM stdin;
    public       roberto    false    203   ;k      	          0    153994    unidades 
   TABLE DATA               a   COPY unidades (idunidad, descripcion_unidad, dependencia, centro_costo, jefe_unidad) FROM stdin;
    public       roberto    false    184   �v      	          0    62917    usuarios 
   TABLE DATA               s   COPY usuarios (login_username, trabajador, estatus, nivel, fecha_ultima_sesion, login_userpass, email) FROM stdin;
    public       roberto    false    178   qz      q           2606    551168    auditorias_pkey 
   CONSTRAINT     ^   ALTER TABLE ONLY tbl_auditorias
    ADD CONSTRAINT auditorias_pkey PRIMARY KEY (idauditoria);
 H   ALTER TABLE ONLY public.tbl_auditorias DROP CONSTRAINT auditorias_pkey;
       public         roberto    false    208    208            `           2606    63049    clave_primaria_idregalo 
   CONSTRAINT     \   ALTER TABLE ONLY regalos
    ADD CONSTRAINT clave_primaria_idregalo PRIMARY KEY (idopcion);
 I   ALTER TABLE ONLY public.regalos DROP CONSTRAINT clave_primaria_idregalo;
       public         roberto    false    181    181            i           2606    535068    condiciones_pkey 
   CONSTRAINT     \   ALTER TABLE ONLY condiciones
    ADD CONSTRAINT condiciones_pkey PRIMARY KEY (idcondicion);
 F   ALTER TABLE ONLY public.condiciones DROP CONSTRAINT condiciones_pkey;
       public         roberto    false    196    196            \           2606    62924    email_unico 
   CONSTRAINT     I   ALTER TABLE ONLY usuarios
    ADD CONSTRAINT email_unico UNIQUE (email);
 >   ALTER TABLE ONLY public.usuarios DROP CONSTRAINT email_unico;
       public         roberto    false    178    178            b           2606    107392    key_baja 
   CONSTRAINT     N   ALTER TABLE ONLY causas_baja
    ADD CONSTRAINT key_baja PRIMARY KEY (causa);
 >   ALTER TABLE ONLY public.causas_baja DROP CONSTRAINT key_baja;
       public         roberto    false    183    183            s           2606    553273    llave_princ_dotacion 
   CONSTRAINT     s   ALTER TABLE ONLY adam_vw_dotacion_briqven_02_mas
    ADD CONSTRAINT llave_princ_dotacion PRIMARY KEY (trabajador);
 ^   ALTER TABLE ONLY public.adam_vw_dotacion_briqven_02_mas DROP CONSTRAINT llave_princ_dotacion;
       public         roberto    false    209    209            g           2606    535026    llave_principal_trab_condi 
   CONSTRAINT     j   ALTER TABLE ONLY condiciones_trab
    ADD CONSTRAINT llave_principal_trab_condi PRIMARY KEY (trabajador);
 U   ALTER TABLE ONLY public.condiciones_trab DROP CONSTRAINT llave_principal_trab_condi;
       public         roberto    false    194    194            Z           2606    62926    llave_principal_trab_grales 
   CONSTRAINT     n   ALTER TABLE ONLY trabajadores_grales
    ADD CONSTRAINT llave_principal_trab_grales PRIMARY KEY (trabajador);
 Y   ALTER TABLE ONLY public.trabajadores_grales DROP CONSTRAINT llave_principal_trab_grales;
       public         roberto    false    177    177            X           2606    62928    llave_principal_trabajador 
   CONSTRAINT     f   ALTER TABLE ONLY trabajadores
    ADD CONSTRAINT llave_principal_trabajador PRIMARY KEY (trabajador);
 Q   ALTER TABLE ONLY public.trabajadores DROP CONSTRAINT llave_principal_trabajador;
       public         roberto    false    176    176            u           2606    786175    periodos_nomina_pkey 
   CONSTRAINT     f   ALTER TABLE ONLY periodos_nomina
    ADD CONSTRAINT periodos_nomina_pkey PRIMARY KEY (id_calendario);
 N   ALTER TABLE ONLY public.periodos_nomina DROP CONSTRAINT periodos_nomina_pkey;
       public         roberto    false    210    210            l           2606    536866    registro_diario_pk 
   CONSTRAINT     h   ALTER TABLE ONLY registro_diario
    ADD CONSTRAINT registro_diario_pk PRIMARY KEY (trabajador, fecha);
 L   ALTER TABLE ONLY public.registro_diario DROP CONSTRAINT registro_diario_pk;
       public         postgres    false    197    197    197            w           2606    808187    sibes_beneficiarios_pkey 
   CONSTRAINT     o   ALTER TABLE ONLY sibes_beneficiarios
    ADD CONSTRAINT sibes_beneficiarios_pkey PRIMARY KEY (idbeneficiario);
 V   ALTER TABLE ONLY public.sibes_beneficiarios DROP CONSTRAINT sibes_beneficiarios_pkey;
       public         roberto    false    212    212            y           2606    808185    sibes_colegios_pkey 
   CONSTRAINT     `   ALTER TABLE ONLY sibes_colegios
    ADD CONSTRAINT sibes_colegios_pkey PRIMARY KEY (idcolegio);
 L   ALTER TABLE ONLY public.sibes_colegios DROP CONSTRAINT sibes_colegios_pkey;
       public         roberto    false    214    214            �           2606    808300    sibes_detfacturas_pkey 
   CONSTRAINT     i   ALTER TABLE ONLY sibes_detfacturas
    ADD CONSTRAINT sibes_detfacturas_pkey PRIMARY KEY (iddetfactura);
 R   ALTER TABLE ONLY public.sibes_detfacturas DROP CONSTRAINT sibes_detfacturas_pkey;
       public         roberto    false    222    222            }           2606    808243    sibes_facturas_pkey 
   CONSTRAINT     `   ALTER TABLE ONLY sibes_facturas
    ADD CONSTRAINT sibes_facturas_pkey PRIMARY KEY (idfactura);
 L   ALTER TABLE ONLY public.sibes_facturas DROP CONSTRAINT sibes_facturas_pkey;
       public         roberto    false    218    218            {           2606    808213    sibes_inscripciones_pkey 
   CONSTRAINT     n   ALTER TABLE ONLY sibes_inscripciones
    ADD CONSTRAINT sibes_inscripciones_pkey PRIMARY KEY (idinscripcion);
 V   ALTER TABLE ONLY public.sibes_inscripciones DROP CONSTRAINT sibes_inscripciones_pkey;
       public         roberto    false    216    216                       2606    808307    sibes_mensualidades_pkey 
   CONSTRAINT     n   ALTER TABLE ONLY sibes_mensualidades
    ADD CONSTRAINT sibes_mensualidades_pkey PRIMARY KEY (idmensualidad);
 V   ALTER TABLE ONLY public.sibes_mensualidades DROP CONSTRAINT sibes_mensualidades_pkey;
       public         roberto    false    220    220            �           2606    1057167    sistema_horario_pkey 
   CONSTRAINT     h   ALTER TABLE ONLY sistema_horario
    ADD CONSTRAINT sistema_horario_pkey PRIMARY KEY (sistema_horario);
 N   ALTER TABLE ONLY public.sistema_horario DROP CONSTRAINT sistema_horario_pkey;
       public         roberto    false    224    224            d           2606    153999 
   unidad_key 
   CONSTRAINT     P   ALTER TABLE ONLY unidades
    ADD CONSTRAINT unidad_key PRIMARY KEY (idunidad);
 =   ALTER TABLE ONLY public.unidades DROP CONSTRAINT unidad_key;
       public         roberto    false    184    184            ^           2606    62930    user_key 
   CONSTRAINT     T   ALTER TABLE ONLY usuarios
    ADD CONSTRAINT user_key PRIMARY KEY (login_username);
 ;   ALTER TABLE ONLY public.usuarios DROP CONSTRAINT user_key;
       public         roberto    false    178    178            o           1259    536906    evaluacion_periodo_idx    INDEX     \   CREATE UNIQUE INDEX evaluacion_periodo_idx ON evaluacion USING btree (periodo, trabajador);
 *   DROP INDEX public.evaluacion_periodo_idx;
       public         roberto    false    202    202            n           1259    536880    newtable_1_id_idx    INDEX     B   CREATE UNIQUE INDEX newtable_1_id_idx ON baremo USING btree (id);
 %   DROP INDEX public.newtable_1_id_idx;
       public         roberto    false    199            j           1259    536872    registro_diario_fecha_idx    INDEX     O   CREATE INDEX registro_diario_fecha_idx ON registro_diario USING btree (fecha);
 -   DROP INDEX public.registro_diario_fecha_idx;
       public         postgres    false    197            m           1259    536873    registro_diario_trabajador_idx    INDEX     Y   CREATE INDEX registro_diario_trabajador_idx ON registro_diario USING btree (trabajador);
 2   DROP INDEX public.registro_diario_trabajador_idx;
       public         postgres    false    197            e           1259    354953    unidades_jefe_unidad_idx    INDEX     M   CREATE INDEX unidades_jefe_unidad_idx ON unidades USING btree (jefe_unidad);
 ,   DROP INDEX public.unidades_jefe_unidad_idx;
       public         roberto    false    184            �           2620    268709    insertar_temp_trab_trigger    TRIGGER     �   CREATE TRIGGER insertar_temp_trab_trigger AFTER INSERT ON temp_trabajadores FOR EACH ROW EXECUTE PROCEDURE actualizar_trabajadores();
 E   DROP TRIGGER insertar_temp_trab_trigger ON public.temp_trabajadores;
       public       roberto    false    188    280            �           2620    808910    sibes_detfacturas_trigger    TRIGGER     �   CREATE TRIGGER sibes_detfacturas_trigger AFTER INSERT ON sibes_detfacturas FOR EACH ROW EXECUTE PROCEDURE actualizar_pagos_mensuales();
 D   DROP TRIGGER sibes_detfacturas_trigger ON public.sibes_detfacturas;
       public       roberto    false    284    222            �           2620    808609    sibes_inscripciones_trigger    TRIGGER     �   CREATE TRIGGER sibes_inscripciones_trigger AFTER INSERT ON sibes_inscripciones FOR EACH ROW EXECUTE PROCEDURE crear_pagos_mensuales();
 H   DROP TRIGGER sibes_inscripciones_trigger ON public.sibes_inscripciones;
       public       roberto    false    216    279            �           2620    535192    trabajadores_grales_upd_trigger    TRIGGER     �   CREATE TRIGGER trabajadores_grales_upd_trigger AFTER UPDATE ON trabajadores_grales FOR EACH ROW EXECUTE PROCEDURE cambiar_condicion();
 L   DROP TRIGGER trabajadores_grales_upd_trigger ON public.trabajadores_grales;
       public       roberto    false    282    177            �           2620    535160    trabajadores_inst_trigger    TRIGGER     �   CREATE TRIGGER trabajadores_inst_trigger AFTER INSERT ON trabajadores_grales FOR EACH ROW EXECUTE PROCEDURE insertar_condicion();
 F   DROP TRIGGER trabajadores_inst_trigger ON public.trabajadores_grales;
       public       roberto    false    281    177            �           2606    535097 !   condiciones_trab_fkcondicion_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY condiciones_trab
    ADD CONSTRAINT condiciones_trab_fkcondicion_fkey FOREIGN KEY (fkcondicion) REFERENCES condiciones(idcondicion);
 \   ALTER TABLE ONLY public.condiciones_trab DROP CONSTRAINT condiciones_trab_fkcondicion_fkey;
       public       roberto    false    196    194    2153            �           2606    62931    llave_foranea_trab_carga_fam    FK CONSTRAINT     �   ALTER TABLE ONLY carga_familiar_hcm
    ADD CONSTRAINT llave_foranea_trab_carga_fam FOREIGN KEY (trabajador) REFERENCES trabajadores(trabajador);
 Y   ALTER TABLE ONLY public.carga_familiar_hcm DROP CONSTRAINT llave_foranea_trab_carga_fam;
       public       roberto    false    176    2136    175            �           2606    535027    llave_foranea_trab_condi    FK CONSTRAINT     �   ALTER TABLE ONLY condiciones_trab
    ADD CONSTRAINT llave_foranea_trab_condi FOREIGN KEY (trabajador) REFERENCES trabajadores(trabajador) ON UPDATE CASCADE ON DELETE CASCADE;
 S   ALTER TABLE ONLY public.condiciones_trab DROP CONSTRAINT llave_foranea_trab_condi;
       public       roberto    false    176    194    2136            �           2606    106565    llave_foranea_trab_grales    FK CONSTRAINT     �   ALTER TABLE ONLY trabajadores_grales
    ADD CONSTRAINT llave_foranea_trab_grales FOREIGN KEY (trabajador) REFERENCES trabajadores(trabajador) ON UPDATE CASCADE ON DELETE CASCADE;
 W   ALTER TABLE ONLY public.trabajadores_grales DROP CONSTRAINT llave_foranea_trab_grales;
       public       roberto    false    2136    176    177            �           2606    62941    llave_foranea_usuarios    FK CONSTRAINT     �   ALTER TABLE ONLY usuarios
    ADD CONSTRAINT llave_foranea_usuarios FOREIGN KEY (trabajador) REFERENCES trabajadores(trabajador);
 I   ALTER TABLE ONLY public.usuarios DROP CONSTRAINT llave_foranea_usuarios;
       public       roberto    false    2136    178    176            �           2606    536867    registro_diario_fk    FK CONSTRAINT     �   ALTER TABLE ONLY registro_diario
    ADD CONSTRAINT registro_diario_fk FOREIGN KEY (trabajador) REFERENCES trabajadores(trabajador);
 L   ALTER TABLE ONLY public.registro_diario DROP CONSTRAINT registro_diario_fk;
       public       postgres    false    176    2136    197            �           2606    808151 #   sibes_beneficiarios_trabajador_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY sibes_beneficiarios
    ADD CONSTRAINT sibes_beneficiarios_trabajador_fkey FOREIGN KEY (trabajador) REFERENCES trabajadores(trabajador);
 a   ALTER TABLE ONLY public.sibes_beneficiarios DROP CONSTRAINT sibes_beneficiarios_trabajador_fkey;
       public       roberto    false    2136    176    212            �           2606    808301     sibes_detfacturas_fkfactura_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY sibes_detfacturas
    ADD CONSTRAINT sibes_detfacturas_fkfactura_fkey FOREIGN KEY (fkfactura) REFERENCES sibes_facturas(idfactura);
 \   ALTER TABLE ONLY public.sibes_detfacturas DROP CONSTRAINT sibes_detfacturas_fkfactura_fkey;
       public       roberto    false    2173    218    222            �           2606    808315 $   sibes_detfacturas_fkmensualidad_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY sibes_detfacturas
    ADD CONSTRAINT sibes_detfacturas_fkmensualidad_fkey FOREIGN KEY (fkmensualidad) REFERENCES sibes_mensualidades(idmensualidad);
 `   ALTER TABLE ONLY public.sibes_detfacturas DROP CONSTRAINT sibes_detfacturas_fkmensualidad_fkey;
       public       roberto    false    220    2175    222            �           2606    808244    sibes_facturas_fkcolegio_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY sibes_facturas
    ADD CONSTRAINT sibes_facturas_fkcolegio_fkey FOREIGN KEY (fkcolegio) REFERENCES sibes_colegios(idcolegio);
 V   ALTER TABLE ONLY public.sibes_facturas DROP CONSTRAINT sibes_facturas_fkcolegio_fkey;
       public       roberto    false    218    2169    214            �           2606    808214 '   sibes_inscripciones_fkbeneficiario_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY sibes_inscripciones
    ADD CONSTRAINT sibes_inscripciones_fkbeneficiario_fkey FOREIGN KEY (fkbeneficiario) REFERENCES sibes_beneficiarios(idbeneficiario);
 e   ALTER TABLE ONLY public.sibes_inscripciones DROP CONSTRAINT sibes_inscripciones_fkbeneficiario_fkey;
       public       roberto    false    216    2167    212            �           2606    808219 "   sibes_inscripciones_fkcolegio_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY sibes_inscripciones
    ADD CONSTRAINT sibes_inscripciones_fkcolegio_fkey FOREIGN KEY (fkcolegio) REFERENCES sibes_colegios(idcolegio);
 `   ALTER TABLE ONLY public.sibes_inscripciones DROP CONSTRAINT sibes_inscripciones_fkcolegio_fkey;
       public       roberto    false    214    216    2169            �           2606    808308 &   sibes_mensualidades_fkinscripcion_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY sibes_mensualidades
    ADD CONSTRAINT sibes_mensualidades_fkinscripcion_fkey FOREIGN KEY (fkinscripcion) REFERENCES sibes_inscripciones(idinscripcion);
 d   ALTER TABLE ONLY public.sibes_mensualidades DROP CONSTRAINT sibes_mensualidades_fkinscripcion_fkey;
       public       roberto    false    216    2171    220            �           2606    537627 *   trabajadores_supervisores1_trabajador_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY trabajadores_supervisores_1
    ADD CONSTRAINT trabajadores_supervisores1_trabajador_fkey FOREIGN KEY (trabajador) REFERENCES trabajadores(trabajador) MATCH FULL ON UPDATE CASCADE ON DELETE CASCADE;
 p   ALTER TABLE ONLY public.trabajadores_supervisores_1 DROP CONSTRAINT trabajadores_supervisores1_trabajador_fkey;
       public       roberto    false    2136    203    176            �           2606    256379 )   trabajadores_supervisores_trabajador_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY trabajadores_supervisores
    ADD CONSTRAINT trabajadores_supervisores_trabajador_fkey FOREIGN KEY (trabajador) REFERENCES trabajadores(trabajador) MATCH FULL ON UPDATE CASCADE ON DELETE CASCADE;
 m   ALTER TABLE ONLY public.trabajadores_supervisores DROP CONSTRAINT trabajadores_supervisores_trabajador_fkey;
       public       roberto    false    2136    176    185            '	      x��[S�H5���z<'�+�2u��eF`aD�B�6�T��	a��-Z�tï?{gJ�R��0�����tal����}Y{mb:qtS9w���z�z���B5�|��/����/�Bl�H���]EwCS4M�-Ӳ��/�U��s/�nW=���Q���r��������ǃ~�?���o�h�L1�M����X��T�>��&�x�����c��Χ��1VΓh9���i�t���P;�"J�/�W��d�֟ml����zmu�3����}�x�ݶ���8�y�,��q�x���գp)FK����p�nW1[j�폼����h�{g���G�#����n�X\%Q/7�j��g�M!�V��҈�A���h{[v��1�cJ0���m5���Փ���C�����ß�}Wm� 
��?vۮ:PG�1lǅ�!�z��a�Ǿ;T���C��<D�I�0��{?���5�����㘌Z�7o8rաzv
{��}�=r�j�/��E#�Z�!j5D���`őj�̴M���׀ժ�ނ�D��6x �!Z�����'�$Z�Uo�Ux���v��9#�(���}��������^�6/m�k���ZU[��~�T~���'8�:�ږr�x���.��m�%�3e�;;���ݓ�kT�=H����;��w�"Z%p�C���9"	5,��-�s���jW������ۃ�Q��m �X��)��DsW�G�w���.e�[]Ij�(S,bip\��*�^���yȚ�DW�"��'җ��i�
����;�¦7H�T�GA�w����@���W1���®Wn^Ϣ��q���,x�ON`k��o���8��>�:��F�V�Wn4stp���nI[�}DK�A{�D���{��<�^ Ə�z�x���|����c|q�ܚ`�b���xt�=ب�o -�-�p�=]&�e8_�dV�� �3<G� �|�z]����F�e�9�r�/�Q0|Z
4�md�h��3�'4|؉����ױ�������Va#�_�o7I��|:��������iv���T7�� \�{ႋ���/�6pg"�	�����qgXL'����y�����#%�e����h���ZU���~ ILj3� 8#�cOm�~[�;�	�#7n�;���о�A8�DIK���J���|�#%z�a�gO�ciZ�A@��3�0���UpI�c܉��l �rDj�H�����Z�ά&k�SJ����\X��ɹ77X�����e`��[uM-��M�#[��1�]]�1�<��DZ�-�X�}�1��^����7�u>ZC�I-��|���A���^�������~.Ʉj�cSO�Sl���G>\o�T�0Z�B5�nc�m�=Dð�����-8�Z`�V-����6�����)�1��P���B�����)�y٠�O��y&H,���i��Q�E2��ί0imca�_��Y��յ��bX�y����<���Qrۂo囯�#���K7�/8�Q0���y�<�5~m��m�z�ܑ1��y��K����vݣA ���] 4���h߸�4�	�lP�� hg`¥W�z�L��3BL¦���Pg7�	�<n��F+�����>h�4hmlMh�&`�u�6��v�|�NdT^L'�������=�a���k�셋�>Y���_��!N���ǧ"��<t!w�� ��7��G;x��F.�;����$?;x�\Ų��_=�1?�B�>��}W�f:Qn��+8�e2�cqCc����#86���&��p�"˼O\�Ӏ�b��QC��D�.��(�{#|0����n���|cO��8S aO�U/0�{�w\��ܭp'W�i��Ur5]��!)�0������A������W���L�O�~t�7a��P�vmx����r��x��)��J��c8r8���#8E�\�&�h��f?M�q2�?�u\��
�Kz~N�w�-��W�s|7�f�%Z�$�LZ������q���%B7�u�î0C�T��pi6�P�}G�4�yz�4��FmZ���`5�f��Y�Z���g��!L�9VՄ�Y���_cĢJ�x�����W�`暯�`� � (�����n�w���Bq}��Ո���|�����:X���#@�oc�r0����e���r8�0L�\Ax~�$�MI�ub0�Jg��
��+��Ѡ~��=�Q1�U��8�Ēn:��W d�C �9�����Z����,�?o�G���m|"ڊX��m�`(��h��y ��@]�B�/�rߓ�:��6�,6nۚh��,�[������6�k��3��Ja<5��a|���^�cpR��{�6b&�S�vi[ze
t��dԅL�g$mhp-^	��눩���^�9�)�{[���Q"��&�`�M7�}ر�^��������+��K�3��U�0��;���;k���X�g�p-T`�� �A%��Aq������E��{.�I��sX�;�$�[3mŅ3�5����4��o�� 1�X��������c�������j���`��wf)��3�H����nM�4�$�^ag<��Ş#·��zqv�KC���a�U���M`ְVn�����A���ݶ�S����|���)����5�xg�[7��C�a���r�^�n��
�VS�1rk����1�F��8�	��Սrs{��v�j�|�#�뻪��x]��*�D7'BZY�m�eE˶I#�v(�ʊU༺���h6��*�!P�"g�QG����$�<Q���BkYH�8�3��P���P�nx�hؘ�q��`4���'�[�2z�6-���Mj0����1!���hZ�e�	�\�4�6��q�yU���-���� ���95��u�nf\��#�E������2�hR�ǆ��:z��"d��<6�"e0��1��������z��'��61�R�����ՊP�U������P!���g2[)��tI�e� ex��9U�.�-��p.�ݼ(Z^�),�_���YGYޜc�y�ݱ�YZ�8�p����A�-�eN����C򦯽���h1[�����Yx5��.$ӫ�L��?|��K���u֗U��=fD��:�eXZe ����i���������8j��>V<�8y�xm��m7��"U�e��4��,r��`|�v˞�i�D�r�e��鵮���Ixn����V�Ix�\���lO�V���v 5!��1W0�`�"����R���xG�6K$v
F��l�}��>�����ZҙF�uw��4���z�?y+���A�+\����c8~¬bU�A@Q�D�P�}߻��~�WL�!�,hVڱ��,�A��g�.x_�r�P��	n�2�H�@#�v>��?H��{	W��K�9�nJnGmb�2�K�2�5��{z@�O������Gʟ��}x�G��Rs$�Z�e2�5t8��z���vЯ���!b��c�����Ӏxt������H���?Rj
|X�Z�����Wd:4=��BXe9S�j�O[����)(���$Z���h2{δX�1��y�m�A+S6�6��6*��2�! �10 �;� ���pa#R�x'w�$��A(���YJ�Xl��J1TP�PC�F��d]�V��:t�L���F�r�y:�� ��+>2?W����W�Ǻ��6�)�����HU/=L����S{�j�NN�$��2y.�;Θ�X˦L�N%BlSg�հ��ƅE����������=4��=\�OW�p�Z�W�T�9<���!��_�DE@��X�y��X�:��9�UG��伪�b��|�S�9�.�k�c���!�k1��	�C�"�r�kG	���[��+�����% ���S����uݠ�F��"���a��	I,�ge2��.��4�5O�Ĥ������q5���w�D�}F˯�{���\���%�r�#`:(�Ij����g�`y���H�k�r�M����A,��A�>�ǁh���_���d�a����^��� E�a�ˁ%�.�    ;��U�	�Gԏ���΀�[��LX�WN_!��鶹��%�L�<�K扈��+$Ɖr�G���!�������_�9�V�^�[�Uci��D����7,|-֩�"�xX����%��v �#���3�ti�F �x�u��~=�sp�c��f��:҃�?�me4�7����\\�-�*�[�`Q:n�6�X����]�~�B��>~��C�ˀ�mj�f�#��q�P���� ns���rȼ�A5ʹa=×�v���C�hk!ͥGm%�f)�_,����1�y���:�������lnDx._�&<������k\����L��݁����!�ve��p`��ra��?�`�/������?�_&^/;�UØ�`R}1 4"���������KϢ[e�9$��r��0{��%�upK����=?p1+���uA(0w�����6\�"�"kB���1�Ҳ��Ak��t��i�;��[��t.�۾����+��wX����?~�$p=Ԭ=����ՠ�j�l���� ��t��i��t���z�_Q����c���i@ �Ǯ��j�t:�&^�K��-���zYa�E�^"p+��<\%�I$��� �y��
�z�,���`ܭ܁�umѳ�fX�r{��a��C���kL�����t�vA4>R�
��Z☦I�K�{�4��
@e�@���Z�6��^�h���E��V�jY�PF��X�,C������?�RW������F"p۱�6��&�������>�A��׽����D���,^~	[ϑ$��>���������gi�pW� ���B��!1Ehk�6��<A�9����mf` Qgr�Z�KZ���H���+j��>�^�p���.K����Na޶s���8 j$�~��M�(�	�bx��+�����^p��p���uG}�<�}� Ƣ�&r��P�L�}4[s0�K����}.7�2�^�S��y7M�5H�Թ���.n���Gg:,M+Gyy�W>Wb��� �\�ʍRڦ��F�Ӆ��/�\���gA�D�W���`x�C���@N{Pm2xN�9GQw����*]6�kl�t�G�i�7�]��(���%Ӈ%Ww�!	�=�p�9����-o�;��y�L:�%=���t�4t>��7F�BQ�s�mt��z>����i���|/P�~Q�(/c%��1Up�`Q@߂�T��0FӒdf��Yk�c�a[>�=���;R�\���e�KX7_���F��F�5wHpB���!��Jb%�M瘡M[����L��:��zޝ,�Q��`%���DEȘrQ��-8�۰t��8H���&�����i�c�������iH�g*]���}�J� ��G���f!"�>���j��]{ؼ3�8�jAL	�:��6�k����]j-1:���D���&h�Qۺ�}~��uh�>�D.�Kjb�7p��!Y����m�M3�S�`��ɥz+^����.��L��"�� ��eҿAЍ60@�=�]���R4/� ���TS�+f/� ���ϗ��Z�
tYy�`�0��~G����F�a���ɉ���i�i��W��O�j���S��5u䪝`w,v�\��0���d��~�`�Ũ%�-�UOq 6.��T��s���9�����ٍr������F�3L�ٔOx��$��#�`Uk�:_=��r�g���ceK�=R�;-�r;���7�&�I�o$R$+U�4^s��+{���MI�����*��Q�[Z��~a׻�Q��f��1p����4YBuΟ)G�����.3RF'�:x��;��f�xtc��@K7)����+��|���48����8�����'�Zz��f-xi���_�B
a���LZAſ/��~�)^ˮ�+%�<�O���U4���I�nZJ�_���>�'=U�"��X2A�0�M՘O Mf�4�|z=z����{��!�55y�L�d�4���y�X��Db���#�\p_[U�i6�SPь���`L�a�>,����<V��S����2��IX���LC���4�@��Z�wJ�@�%�B�,�6m�Z��@2;��*�),���>Lûh.�RSי�&��O�1��h�ۗ�"�&���~��R2`�DJ`�d�����[�a]�l���O�Д��@^Z��Xv]Λ]�����:�a�N@�ߒ+��!J�[=pR�� ������C ��;<D��o�z�����aZ`�qz�8���z����.vG�EE�X��l�b��8==m���S�a��8-b}���)W�J���w���.��e�F�e3���r�=��?g�x���.g<���|�X��q�La�Z>E�L"�ٚe�@��v�#Bz��꾹�P0��Ox�򢗕O�3�%�[�0�Y�>��V�2Mԇ&�Wgv�q����"%�����Z��si�^����S}��2���r���p8��f��K��B�.��zc���F����D�6	���Y���A}Ѡ��`��Eo�/��J󥶽����T�-�Ue����g��鴡(�z�W�g�'�=+�d1 ���J�	?�6"�Z�mIT�����O�g�����i3��.�ό��n̔�����x3 g'���kPb����Gr�o��Ж��j>l<�n{��3��\�+jr��(MZ@X�.�[k]�` ��bY/�r3�4�������t]�f���u��6Ɓ��I(r2�K1��Ћm<1���ҭdפ[+݄=������9�<9mL�̕���$N�<�K�k�E����N��&�Pq�JG����a��'�:'t=�'�kb�/����?���xmOQX�1S �;���I��X�,#��f����h+���t,���%��^�GĢ�&xG�-E2�4�� ���������.�}U*��v6{N�y�d��$aE2������8�GŰ�L>��f2jZ��eLf��9l�{5�$̬Y�0��b[���u{#^/��J�����髰�U�}��Ļ�d6�0��=܎t��_2�!�(!��[D^Ut�dU�Ѡ%}<�:	1�����a�M0�N����b�c��n�R��P�F�K���Q�D-m�Er	3+G���3���,\L����zXE�:�[���p �����w����H�.n� ��6<���M�R��;�t6˸V⼜g�6Tl���-��;�
a��tF9C�?�mIg�-jMé.�[yz	�	�����n6
 o��fO��2��8�ϱ[�|�T���	x�{�&4�M��sV���R�z���ٲ��������#X��&r���W�IQD�u@����A�cy6Ʈ��xǼ�9P�IaL77J���?����J������Ε��)o̧3x��)��0M�����o�A�༢f���=�E��:��G�ڹ��}Ld��W��'��lsm7��x�3*����1�~-0��Ԣ��\��<�}9�~��;��9��r͢��K�#@u�� lȅ���{iK:^�,�'$�X|�
�U�!���Tl�bjx� s0b���^�P�u��\�F���./��U�o����;��	��A�rDM�k��H�S��*tB�qP��%�*v��BÔ]��FQ�T��m՞˃��M��Hy	�헩�*��3lpt��<�G���/Ǭ�q=�l.������Q��T���`��_�a=0�� P�61�^)sT�]%��Q7$�������n�����?�20_�E��w�X�?s�L@R��l���K�:��Q�|�?V�&M��L­nX��d���� <����)��Ȟ�<t�t;eN�V� ׁ_��[�w
)'I(�������Yd��b���T���*b�1�4k�6M�I6���C+���֍�^N�������]Z��J'��i�]��W[R�+|s�������#��3�o�Gx��R/~%Nv*D��{h��7 w����
6H=L��O��:� V�I���T�	
�rڼZ��fM���7���`����'������ȁ��"cgؤF�Y�7��a���jށ��i�p�z���i�    �9w�\�/���{q��%}���KX�)�u�z��QLo2�תŲ�2>g˴Qj�w����عOZ����O_��9�`����Ai,�;����P��fOϋ�jv&�B�@Jtݴ,�������҆]��)ͻ�7�'��֚��F�a5��! :D��.�V���N���U�r%�pq�Hh�-́��ǁ�
�SU��>�E�.��в�u���V0L�= �u�^񁼳i|�#�)N6�g�[Xv=��w���:��@:�}��kVJ�&�Y�o�捖v�?k0�N��g+�aϦx:��MZ�>�QI� vt���O��$�-�,~]�������	(��f�[��>C��A�*�	d�����X]gK�?7������I"�Q�� (�t���r� ��R� �@s8�EL%�lh�n��s?��r>��m����\ͩFvI����,���-�?2��y,L'�p����$e�Ju��9<"H�:�`�̕V�A��N�w�+j��bc�C�v��ߜY�����-��$hbSz�z��*$8c�&��+�IUNf��60��������|�(�d�2���8�����T�c�+ʠ"�-w����tĤ��o�6Sg��Բ[�p]��bbD���No�JG�L���-�]*1�����F���:��r�U�a��FX�ѽ\]��Ӹ�v�#�U�GwK�~vl�
&+k��E@֩��x��o�m����~��;�1�[���Tt�A�>ĒN�|)��K8����,ʎ���゗ʧ�b����e�*����e�tG�-R�TU�JU�� ��Bl���d%��Zz���w	O���2��B�p6]�2��F!�g�����ns�V�U!qX,ۨ����j��[Kw~��Z��z��ibn��y��boM���/0b8J�,*vda<�󂎈��#�e-�b��H^��Ѿ�b��!���`Hof�r3Ð�q���KX$��0��$�G�q�/+%�����Q�5|�-Y8�,5G�#�5�>�'�$��}�3���܉;�=|���8���W0�ǰD	�t�Va�蟖��^�}4I9dC!������)�|�CV�l�F*�X6x��J/��WW�tǱ!�}'`����:��������q�T�Sɫ F��k5X����E�E\���n��<U/`%�*�i������s|���p��Y	�z!(�s�:f��%�E?��i���;�;I]�u�[>���������Kg�m�����L�����x6�S�%ы%c��`ߕ�����g8:��o�;	���zTO���Q������˭�7��(O�3�$��=�y��eQ���Gng���������ie��=%%��Ӧi6��U�W�E�"e`���$�}��.i�'M� �~�'6�م�}o{R� �xIl]����Ho8�پD��V��9V���h6�.��*��e5���<��ȃ�'ETu��[I�\��Z���a5�ʜ�2�nV�!�UnpOauG��=FW�l�&�e%���-�Os8�Ëլ��oY��#��r�0��ɼT���,��E���;�qZL�n\�쒾+���S�,.n�����R����0M��W)��v�.7��������K�65��2���h.A���2\�V���p���hc`��\l�F9�3���X��'+KK�xV*�Aل6�*�L����G�d�އ��u��F��0	QT-��Gh��MGm���U�?�}�^��4jƅOIAT:��c=t�S�2>�(�!ZgZ�@��>( ��1VF�e9i�s A��ۯ���Ւ躨i:�~���F��܃��_赴�$,g��gL ܇�����oR��X[���2�s����Ϩ�[�%�3 թ�*��C��7&�]4Q��.���5I��Z/ z��-�}��g"�E��`i����%�f�q���>+)jϹ�wby�?��>��� �횑@�)Z���Ͷ70���B;-��籨����6�o䔟���; �+��j]'�u�`]Q4�rP��|uUU4g��{Y��ẽF1��wE�Z�f��T��}:��]�<DYoQ��G\��&	��,���:~	a�.���i�|./�٬�*�ɥ�5򅰵z�U>��sO�c4������ߣ���6�ޟBc�}�z4K���9��Z���,�j0��B���ƕn:�LgZh�p�s3~iC�������"i'	,���X ���!���9��K8�+R8/�-�b&I��Ľ�GX�b�n����˻QT�G�^���@C�"��vM�p���G �>��X�M�I��Գڔ�+O���������[o�ߋ�7
%��-�X���R���ۅ�i*�+� )--��=��m���p�x��<q!��H�k��M��q؟��C��c��/E��5��M&�<����%�q�'������Se�XtG���Qp0C�f�m�-��5,��>xy����ꃒ:[cL�1�k��g�0ʷQ�'��g�}��-�E�m�6ڌ��fe�ʬXf�N@/��L.Gk��:̱�9�X�R��f�Zn�����-�^�,[SС����w�]-Ja�?>@.;�&��4Y�Fy�O�c�SI��y#4j�ƪJR�vK������$�טd�����ˢ��=�;��}O� �Vl�
g�q�݆� {��f����ܻ'�j�Q>��q�YJ�n� `�sԎ��i���UF,]���3}sx�a���3��ް�gH��=Xw;�=W� ���a��=X�/;�x�a�cx�Gz||����Dj[�5�% Ņ��µ�����e���P�l2c�n}P���(�W`����^D+	��:@q^�)��+�P@y��`[�Z�:�af"�[fr
?UNag�.�c��~ymj�.[�W%^8@��3 js���[_3w�֘��m3��<�=�^��/\5�6vGB���pZ��q3�7�o��Us�07���������~��O�G���x�y�D��W�Ç��!�㤋����9����a�&V.�S��:�aˠȗb�V�.��E�E��?uD���V��f��6�}e�8��Ax�B�T�^��B��!,<R{>,��m��(m�E�
�a?�$>MI�u�ޘ���O6��ъK	��'	ץU��������]4{�����&U�+�n;��H��}�����I}˧�LJ�m��Ok�zk�,\oIa�ݎ���x�OL�y�SC��/�l ��#
Vm�<�Yt>�����aw����^�P5�GAx��3���˲7�.�.�8����K!���S�������A��"^p�D�`��� mP�+��o���M��d*�aS������^�Ǩ:�ҧۛ�4����	�dO��L�XL��\3�.:��r�^�l��]���.x'5l��O�4�N�̧���!�E���K9�z:���E2KH�G[b:Q&(*��i:�E�P��P��̤� ��:$E:%�W�#^�u�=tL��iz�R���FiM�e�Ȫ'�Ჩ�i!W��t��P>�'g@�F�v�C'����T��n�>?��\��^�N8h����Χ���@�"6����%K��KC�)�[͋D�J5�C��J�ng��x���*zy�����j)K Adf:�����G.6�pڔ����6�L�hnp�����Z�����)NA}��4��0?)H������m-��<�ehy����N8ݥ�?�S��7����{����L���v�q�pb��p��ۼ����F}T^�*���0��Vb�'�/��E�"�	��s4y��-'�� ��"�S� ��MIl��6����������솽��TLTq|J�+���n��?�)����,��ħ^������	%�(�\�{`]�H�,\%�N$���L-'���\�;��+LJ����8h�Eq�TC���!���V��|��N�{S��)�MW���C�@pGV饯��I����;�����7r�wp��Z���岩]ɥ��/!�9���5"��;`������#��0 ҆��t�{�:hsA�5�Ε�G*(d],YFc4h��4�WR�4    y�fӥ̞r��]�n��.��{������x\�k
jC4�?�V��R(/�M�L�i����|��L��\^U��.5�L�JF��/��Zk���k�g��I�.jm����ڼB`+�<)���Ng��+�|j��wt��`c"�+�%p��6�'���Ѩ4M�3�8��]���/
����[�U���"	
���3�*��y��'���  4?!)��l�6�
�M9��:t�u���ư��KN�N!s��p�3fj��|����u���۴�����6��:�{�:��.� �"��f���T_̳�zD�C��6RA�L<Eπ���&��H�%�����X�$���~��o��#!Y�L@k&K��\��~ی3����5�+_��m;p�:c������k7��L=K�T<J5p��[��)A���@�2Ǉ�~���<>XM�T/@�e�Y���E�*bBd:��yF���r��W�C���W�fT>&�|��i����z&�1&�)ъ�!f�L̃AT�g� 8ja���MR7P.��e\�,õ1,�?}�4��JՙBt��[-�k���4��Q/
{Z��V0��>b�w3Ov�����tv�D��U/%~�A�nkJ����`�y�iǅ�3-�̥`�y�Y_3��q��8�g4������弣�4[R�p��ob/�H��BC�D̻E��h�|&p���57ޡ&���08��p\�s�hN��CH�Z{��跨챼�]0:�h
_-��c����|li��d��zF($�A���k5k�x�����R�������E������m��u��g.��q�إځst/�}]���N�#A0](���s>E�	e�fg��;�bdaT�\2�w����Z��?ک�؛-�����x�t~����Z��b��7Ͻ&-��bl�������B�ʆeJh�u�Id��zy��2�F!7�2u��������Ғ��AF%����MCzR��|����ض������9M���b�(��\h�f��i�L|L�/�I79�J�l��mGq;c��y� ��l���r�$�"�P���42���_�;����P�1�l!f-���M��)pDvR��Ƿ
<'_��T���J�)�<BN߹z��<��=��<\��%�rè�GR�v�ON�-f|>�Y����N�hE�[��?�tX��sl<JG�rXe�A��8�su�<��C8�y��<Љ��	Ys��Ru��m�`�Tjy��	�&rd?n�t$�f������ϣ�]m�Ӫ�������l0�9��'��1aF��3��Yt.���6�_6�\+,�sv��dS��F�t ��"�e\����}_{�7��Ѿ�{��"����fz�<��X�r��E)G�;䜻#K��mQ��"֬L���)ُ������v4^V��&����z�Q�I��r�q�(���b�U�D��&����f����uN�E�+��%<�(wPP�l���?���=D�W�4��bɓ�_} �/�t��Lʱ��.���5�e{��BX}u�E@Diq"��ۜ�^��"�Б�ç���C��N�|\j:��0B}9���)�B��o\錿���`x��^�8�Q���'G�b��&��~\�O7^e�|�4����C�oW�r�z����0�%�5̠�V�#���l��_�n�L�=�p�f�k��;�pa�hk���2�x�w^\�_���)��J�[������5}C�5��t���)��X�t���S��!~�f��$�Іe�Q�"����>#�L5�G�n�2�"��mVh�ȅ8��"�,�7����56�nY�ǥU���-YAk�}�_��`ۖRq�bw��/�R�7>��#r0&)mů{��A��&Q��'\>ޅ��`v>"�0���p�]ot*�h��^8���Td��	�h��pl�Fc��Z���lL2�V�ئR�����OlE���M�m�Qk�Y�����-��10ۗ(V�}jJ2�y�K09�ց����vI�-��D~]��8{LX.���M�����,N�
�Y�X�t~a����ƾ�έG�v��f�:y�ѯ��2���*g[Um~�� �#��'���t/b��d�aj�8�,(D4�%����/]b[����l��������}"]g�AIw܇@���8UQн��T!=#�ҵ�`��aZ3���n�^�x������<$0_A���p�n�a�ǖ����!ܖA��u���nJ8��gol�Zu�����ʿލ�㸠$7������4b��p�3���ڀ��9G���V�H�(y�'��"LneaAӰ��c7��@{ ����m>�R�Ә,w:�z�jI����i��E\���"b��q��v ��������x.K�&���6�)��h .q|�W�g�C+F�����E4�\ "RN��M�2I���Q널1?��Ѭ�D��%�o{E�-��!
��Ft��܍���/���U��[�*���%���n���K�Hc�4�䤲���������u�an�M$Sf�V�h����L��6h#�H�*�mbN�o�7����u�5��Lz�+ƣ"�*2ǦY�2�Ny��}�����
���0=�|��l2U&��+����<Ȍ5��	^�1�P=���U��`+W��i���0M�fC�f��B,��D������s�a1��>�w���΃�? ����Zл�������� ��e6V����H�n9'��~�Aa7\��+ =G7�p)
�)��\��n��m",���Z0�RŹn��b����ſ�g��_J����=�ƭ�3�_�k���a2#p���Y��ag`cMBgmQ;z�ȍÐj����=g-�+;KE�l��H��Z�a�ݽ�f����ԅ���l?:��=j���ce�\����_�	�]̙ݮ��?๧&5e@7Y ��bO��=��]��`J4T2
N:K;���2�0%��ir<fՖ��Ȃ�%O�D��*7/�D��2����/َ�(c�2�� �ͺe�N���©2]��h֚EQ��JR0� :At�v�����z�N�k_
 q��)ưa�լP�DN��	Z���A��%k�l&��`X��N�W��k����5x��LQ)�
�]>�f#u��E��6�Β���o|>F�睩{T���*o`e�3�Y.�	� �{�X���"��Rv���^h��5����i��։�j1��F6��%�NrcK�<���[���8���ܷ��j3	V�1.HX-DS*����G5��:f��A�BYVp��oN4�{ԧ�vm^N�{���<П���^!p[Oѿp<�����8���nO���0E+��ZJ}A~��&7滈�L��X���-����sks�sb�e� �Mg":�#At�AL�t�l���u/.���L�R_�7^7;R�i�è�L�QN�f�m<}����>M�-Ñ+�6��O��
'YM�:�� �:g��|Ī�x�ƺ۶�	W𮁧����9�EW�~�;i���5���t��5~���Y˘���%��E ���� ��_DG�C����7������,WN�IL�\Mb�?Mgŀk�V)�r6�r��(|���|���@O�?|�����a�-�?-��,~oW��C�lh�5 �ʈ����)�k:N%
`d�aӔ�X�a��G���`��͙�UX4�b�Uh-Z̮�K�9�>c�����\�Ɛ��W� �v�^��_v���	ǳ9Wמd���
�	H5(��0E��`p��z|�K[n��m���f�Y`Ĳl]���
A�fYz#<�A���y2\?��
�V����D��p�
I|.� ��I�z��4��Geg3ހcT7���Ӎ���ƻ�|Χ���]�܂a"wL�M.N�<��P�͖:���E_� Y�6���t��� �$K.5�H�����c޺z�$BVWP�5M��u�6��7�m-
x�ę�Z�,�"�m��+)�\J-��F�Ԑ~<tuΓ�A\ �.g�_!��d2����`������Ү���p�,��/��rIZ��n+��    Z���"'���X��������������2Z�T���%A��KW�-	�#:Ӥ��}���a̪�Cy�r��	\��P����E�3�k�c'� O=�[��\V�����>sp&�p|���0�9�iě��PجA�Uܨ�ʪ����edO� ���n���8����4<���hh,�t��W��I,dBap�K�+��Ѳ�/�E8]̣�󲬫� ��v���?�q���a>_���Y�#��ʑ	U��un��J$���Q�pysx�-��w����hUm��ݏ���m�̳�o��i<8��hgM�4L����:T��)Ou�{�7U��2g������{�,x��'� ��C�{p�;x{��|\�u�lX��y�KbӉqr���<\\�j:�Ԉ��H*OgϺi�C�Q/kA���,�C4�i��Nn>�#��E�4���o�(��c��Ǒ#5;j�5	ޢ ���|_�NT[�6��,d������`�pX��L�p,�6��*X&�k�w?ͧ�N�V1-�'3-���38�*��w�82�:g�_�=�K�D�Sn�~�sȸ�7�d�9u��u^��2gq�
m�D��u�@�_Zj��.�t���#&��af��xTR�F�"��dH�*�!���!�\�G$�q�I��,V=\m�E�:�fc+���_�V8������k�f�kZ�4U��G�Pib�+}j���]�+�9x�;�Z�����?��cah\tĶ_%E$��ɍ3�o_��<�e�Y�$���I8�'i)������5r�ᑸN���j��^��	���ѥ��ѥ��v�OɎhD=�	��ч���P��	
��.^<ߠ^Εԋ�P�c�^)�x(~_�0A����frˢ�fQ��A:�V�g����Bζd�� ��ݙ��Q��m��7��U7�1�nI]m�zK 
ܗB�Et�(�_�4-eJ8����Z#�^�}-�]#�W1Sօ��[h��ډf >[�d��7y@e��֒���&�U�qU��q N&�ws*��]�B��6��3J7j�c 8h>l����C��<G�)��^l��KmF��*@{�<��?0�,�/i ���͵5'w���s���p>�N��p�Xx��B����!i����~^�a��>�b.�G�8��\���z*&yo�p%.)b��K]�K�Ũn��n[o�z�w��|��>︊�yw+p���?�s��C�+��'��V@t�r�P�4m�̩��bp�]�'f�A �����z�Oz�-]箰��M_�fXX�3l+*wh_W~���.�p�q{�ΰ��F��:����pG��uDp�~��n��Dgp @�>Nn�?WS8�$���Ex��9��㖚����Q.�����/@�ykQ� &�炲�՜�I���:�����L�ao
��Ǳ9-�|�J�v�X?�(���������:]���/+%K���/��[�R�^K<���0&v�B�mi��u�����B�Y���ro����-%�����Z�`]���=�Ε��[��G�m
Ke��v��u�0�Y]+��m�"4�F���$2��"�&(�w,�:&Ҡ�C��rlXM�=��F��q���ׅ�Z�N҂ȝ��KU�&Q��]��Q�[Г�Q�y_"X�/�UȧjR�"�� �%A�2���<�#b���F|��X�MӴ!
��Ƙi�a�M��<�Tt�J���${�}��A��|:��f\�k��E-ٍ
<hl�h�S�;�rk�r�,-�R�m(2�B��9<�	O��9��[v	1m*�ޑ�d��*z�� �۷[{������m�ɲhBi��L��x�Yc�:L7+�x0<n>���:�"e�LZ`�ц�$�1��y�w�����}�|�:RT��%�%�L+�K(q���P��ޗ.�^��`���A�;TG�5j=Ʋ.X�hv��LB����**!wo��vQ�[А�~�	�h�y�?KiL�0ć%Ucv�.��e����M�lQ����r�����m��Ż�[=A��K�G��ܹ������j�c�N�z�6 ��J���w3T�7"4�d�A��iؕ�g1����B�^H����/��"� �7�=�;�4s����N4�e��U���eܕp�ܢ���q����k}������-��'$�l\�7�S٠��%5S���r�5�=}�&

$]c�q&���n�c�XQ>EԾz9��?�B7���|���@1�Q5j(��fY�=�ކr:��4�����J�j����9T������U=�7*x4O�8���y�[�T���lm�Mc����`	*��Pb�,~�C���D=(~�w��gv5E��V��LQ�;�k��i��A���>�
]�l�����x.L���0��6u*4���< �;�Rc�� .��]-��~��.T� Z�~��`?I���V����6��J�A>6�x�Dj�m���̼��=����^�_��'�~�� X��Ww�U��-��oEԲ���n��q��)ʡ��r��.�.�80�qlX��R�j\� �m��W�s��3�g�K�b���0��Վ�P���o7��}y�����,^�ήqB�|uKT��.v:^����O�7��yS$�:vb��Ql#���s��@��4��@��M(Q��G���#�X�+�Gy�F	�&4�ܪ6|�n�6��rP[3�#��7��q�����-N���"��b	�L7t���1�}�p�_��2,�r�Ѵ	��hdVY��s�m�l���{����+C�C�^�'��/� �*�i��چ�����ӬC�t��s?�<�'XS�W�r�\fc�er������ƞ�)�@J�yr*n!1�s�����h5.��yg���}{/�1���]�5]����p�ʦ>J�!�W\U��꺞�Ƣ�e���6�Sl����n�9Ԧ:��5l��T>���1��N�=��h�FI{�r�������)��˕�R�R���ڭWO�s.��f6f�2����y�<��JO���]q/�.��q�uF03��h"�88��Q�D4hP��<of863��D�M5�>G4hi�fnt�oQ�}[#�^�ďF{m4�zbg5�0\)��t?�K�Rx�,�<;zkc��=c�挶~��[��!b��~I�܃$�=e V�k�h8�݆JN�v���(A�I,	[G�T�=qE4��)
MW�<� �+��n�0%��MJH�Y��HzxGX�X�U�n޵�-��U�,�W�%�eb�@�E��,���&��h�N+�	Oͽ��?^ 
	]�)[�^��}���ͩ���k�R�r!���&T��5��$~��XN�QjQ#3�=�8�\�8c}�pO�o��3�I-�g��]ϻh�1��r6'���c�� 	A%�=�K�9��wlʹ��Vy�#>�v�lݘv�����c��4���0�6�l_M!�kT���A_lB������볨�A��P��(ŋ�WDO�j0�n7������"���K�K5U�����]�g�v{o ��U!Nˠ�\
��a2ɢ7�l�f��w��Vi�ߊu�����";́0	"���.8G�h��ڽ;W���;%Y-��g�a�n,S�5ݱ��X$::�l
��V�"��k0�|7V߫�s$-0֟���"�+��S;A(^�|��0f8�c�W@ݑ`k\.�~�ߙ[��*�wM�d�IQ�c�_c!��� ���e�=.�'�o���B][˄���Y�x�<mAKKU���������9t=J��ћ��*Т	9�=�Y-y�o|�L%���R޳�;�6�t��8D��癱�dN������cWuϻܨ��w)�U��>F/Vix;1�e[��w/��RO�9���	�)i�*\�A��)R�İ-ӱ�fB�5�[o1�����$�ܒWap(���S�HH�qĖf)����x�2>)��ϡkhh�;����w�e�p�(��q7-�Y�6�*kS�5s4-�)�T��Y˦!���K�T��.�{�>Z������_���IZ�`���_�y�,��YN,��@
���N�����^    ����oo$������!�P�r���I|��,��Iq��	���r��AՐ+Ī�6��zbvaM�F\iد��t�)q��Wu�dZ[�jo<·�"y�[�q
�2qH%�C���S���"���]v��!�pN�@�-1{�>^FO�l9�f���5�h�7�l�w�l�ԏ%�:�C��ȿ~�����F��Rl$[�y|��$�bg�IҶ�Z��G9F%<����z<Z��q)7����������Vp?�e���;�9'%�c�ْ��~ͺ*���Y�O�i�Imf�N���m��o��W}��	\�Y4�"�!���+�Q�~Ŷ~��C���F��tt5liÏ�Q5�CT�Z���2��I�m	Z("�GA��p�g4e#���p���� G����-�;���E��l��aO���`�$�z��[�S0xr}U�649�����|�z:���ck\�	B8L���'��A�E�Oׂ�=[�帍��l8�z���cl2��
BW�6���^z��q8K~~�l�S9��@�|�u�!Wi�_�g��b`:��T��[�m>�w�6aNj���F[h���T/pO�
$�)~��Kw޶�4��:��B ��&���wV�/�Jo�m��#��׸v>�cD���S8���QIo�^DIK/^��/�G��p�p�!͒TB'�a��i�jn3�B�f���0{�Ԉ�T��̅��^���SX�Q���)��L�xe�G3]|����ULx2B���u�i:����K�H�<�.e�\w$���)

��豅-�4E�Y�n[��¡��Sr�J^I�=ߙ=3���,[�К̕�Z�UK�8P��
��q_%���ȵ5�hb��i4�K����lǇ�B�D�jՏ9����%2F���DK�"1�,y	������U�"�	<!���� 6����ѭ쉠�/׾獗��S�,����6��~f3[�k"Q�|�z�����5މ�[#_��Ǝ>�A��D[#Ѽ}t�̕��^�x&�4i��[J��o�4𺤆Z�N*����q���QTn��K��z۪�ޢb����+\�=_�V��<Jn�Y�7_�F`�s%)4lJG���J���.�a��.k�<�'�u;��P�W����+os��D�\�P��f9A���zf�L &>lbSj��߲vʎ�}:�ڷ�k>����/'����V�k�-c �� ~Y��w|<�..���cV:��^N�ۦ���^���=��Ca4�&nK'�P�A`�u3Iu$b�i���d&M;x�������
��$��cx���Ln<�އ��T]We�lX�z�.t��6+���iJ� +u��T�G�U�L��Vb[�������|�P��g���r�x�1�NO5	���0���0&�1�|i���M�nE�����Dm��A��V2:)֨����EV�≒J�NB!�I�κ��6�j�"K��
���*Ly���!ᏸ�E�п1ٛ�0?&,ޞ�	�������������� {g�1�oo��u"Ua����{�i�&<���=���LE��n��3���z��e���K�6�\X�季�]��"F�lr+U!Vc�Pu�VjW��
���� �u���m��,��TI��h�AֈI-���K�c�y9���?����K+I|ͦ�t�Qu�!|,i�۴i��!M.R��3�*���b��kVbZ�c2�)l[�b[Xi�i�1? ���5/����8�c8#FA�c����/5;j�5V�T�$������
eĝ�z�K���?��+ h�ePj���`���t�eZ�{���8Q��%\\��H�گq�Od6�CR�:͆�r�ţG]K��y�f��09���F���9�͍mc5�c���n�
d��kY����7��O.��B��O�]%S��2����L��ĠT�[��p͒��3Pj�һ���S�aQ�͘ՎϰZ�(d۸�N�}4Ye�h�-݋5n�����*����~s��[�e��-�j`;{�dåز0\&ul��>��U.�bYo�L�mx����.\E���;���KR��=D&'Wh�7h܅ �Ei��7k}��&b�E��Zk���� ��8�'�� ʷ����^.���ҊF�bT'��� �������qr@�i/��{�pAڊ�wsX&i֊�Pg��H"�����I�Q��v_��A�p|��~�n#��w�����wD���p���h�b�-?oX�GՠOm]F`d��,|�nJ��;a�����]�w|��lh���K���� �������^=�8B��Ӥͨ��Z�.��>��d��������GVȚ%a�$��x��O�4��cD֝�G�?�/@�J�h>�('E����se�8��'�\w[�x�L�W8�%��%�a�d���������L3E���W����N���Do�뵵X��׻yP�y��q$���;�+�;��'8�R�'Δ��k�ᴂ �Ի]�l���&KS�����ꋐ-���mBw���,�l��+��@��T�!R���/�� ��H���ec�[���i���on�a�桘|ή��1�K�Aߙ�s���(�_`��"�&87�R}��������nZ>�xt�)������qxgqD#d�t���+�8ߊ[Oޠ�uw�Ci�.���w�,�۝Z�i������@�������9��|�b����#�(]۫�M�@�;���L�ԸIb�Bݣ�}�ԘN-�<�2V{����˷I�R�a���!8�V�G�)�3̧����)L�S��ch�wY9����v ����*��ǫ�i�EaҐ�y���(gKY�� Uk���0��|�՞��,S�e75���H��D9�e�]+Y@|4
C�ř��tj­�M�i	��5�����-SjNv0��V:��� ,rG����Rx��qW�9|���4iJ��b��	OWFDg���;���#�a(
!΂�w�ØxiL�Υ����4�<"�k6��W�k�Y��t�>��pͲ�p%`21^�WF�r��k[H�5��>���0?�uSgq����|��(�Rq��e,'��/Pʧ�p��|Z���,)ڼ��;F�av��gGm��g�a~"$l��:Εw�.�6ϴ��(����G�;k�q2T�_7��� ���
~!M�0�N�#E�St��`A�KZ?ݲ��I[�}�=?��9Yt�E�FY�|
?&s̒T�2���v�0u���;i,C%.�L	�.�ݼ]������O��Cj�t�U��B��'�O����oz�I��<��,�nʋSE�	6��)�UvG��JW����)A�.8��P��/��f�]5=N�X���oSӔ�R��Yׯ�>�k][��-(�#�
�#�:���<y�"~�;DT=7�U�(�SRٳ0�Ѿ�7W����Rbj�&��0� Ǧ^�ƬM���m4��z{�WfU�{����F��t
]���\��;����A��ď���^%��"	ղ�	��@I�~���x���;W�>z�pBH��X�[
��'�7o]9,�?���vmѧ(J%�� �.����� =�1!^s����t�V��.�âN�0|�@b���@��ܷ�m�(�T�61�2������<�m��z�B}�r�����B>�����H���tϦ����t6��R��ld��B5�qpQ���`{����������(YO
Z^�!���߷9��E�7<w�O�J�[qeQr��e��}􏉓ﮑ����v?)�"L���}��v�Ay>�������җ�܅>�Ei�>����H��%�K�2]�k?I�=�}�A!@	��:������,��v���0������Z��(��㘈��ٸ�����r�<������z����\����njH����7eH��-i����P^������H�֑J�d@�Ȁ�s�D>	��B �����4���m� K�G#����T�i�xL�����^��+�BC��
ɺc:FP����j�L� �Z�p �O	��{K����y��Z��bTϨ�B���޿�_ ;QB�    �z�1i����CK�b����y�ψj�7��]{�+�ᰇ/�������>X�?����!�ы���������a.f+e��.[�S�[f�:�"�Y`�\d,�1V�<;��Nã��!���v�Ь���+��6�K�A����~gS���޹X�~�۔!΢��q��o�x~��	�ߢ���9�^�P�T�=��p6C1�[�o�wS.����`��#�H%�}�Y^��Ĉ��Y��C�
oׂ�~wڀ6%��g�A�<�nwy��ʖ߿B�7�lv7N_!&���9�Sys�W��^`��;[I� 9\�F�d5}����.�[��_��YNG��wU����*B��Q����	ɚ��|TmI�C������.��+�~��yF�PJIQ�t���x[����x��n?�v"#�#�7�B��[F�㖗������&�Il�n8�^`�9�?Y`u�4}k�)�&�߅�~��빛�\Y%��I+N�vy����w�ޅ �4�,���3ۼ?gIYۻ�6F'u�R�S�"����iv���9֪�ض��3�[����E��T�o�@Qݝ��oW��#�d��U�&������ޔMm� 8B��="?U�y7/�k"�O�� O����U�YF�fI�z���2���7r�&wL�A"������j.�T��$��:�f8H5W5*:_w�M`p��s��;�L���WaG<��\1L�e*9�0���� �_� 3�שּޮBe���KK�_b�MQ+����/��q6X$�j����\O��ސ�Gnt���,zV�W����+�}�WR�c��k�x��
����4�;?q�zЋQH�l���ϐ����7��\>�L�l�*�]MZHuQ�������ƕl�g�o�@E��j�Tn_(_Ȃ�c�X�f�V�삯�"$Y!,��3]U��4޺�X�.s�Y�I��TZF�O����Rt@�z�c�w!�����Z����<[�j�d"����r�Z�� k
��������b���}]��7��E��$��I��ɍ�5:�$M�(�Et_�M�X3X��s��S��_���b�dE��z����mJ��Y�K���d��DF�ě����MfD��B���/B��/TڹDM�3eq����ǖ�"N&Q��d{.��-�1��p]oai91�T�}E2B�==g�%�Y��*�%�����c����݅�}�1(�{�J8�ਟ�2"_�Et�N �?�D
�AVj*��#�4I���q1�d`��>娟�IP�z?�U)�jS))XV�.g��5��%�kˣ�n����\D���a+�{�;��}���ý�����K��|'mkR�\i���8��#��,�.�>)&%���u�/-	�:b_���DPf;u�N�&�c��'��:�=�b/푼���=ܵ%u<^��Ⱥ�T�m�ٙ���T%�{�O2�G��'�K�x��+W`�ݞ�SV5:W�� �%	a�=(�)0�?���K���W�3��v�Qb��{tœdj�:!�����a�h�8m�I+b�~cv�_D�;Q�)�2r���y©p$Y�1�~W�bQ�Z�O�D)��td�;R��X���ưJ�Á���AM�EM��HQO�!I�E~���5���ޞ}�4x>���N����<�_�,N�9�@��R
v]��u������B>�o��l�b�k�A�B͢��C�P�,e�{��|,IG��y�,�0��Qs��v\��=c�u�;h��b��k�q\ƺͼ֏���i=L����?�W;&�I��K�EuN0�o��k�/��t[�j_k�@ ��JX���<q���G�QC��ܵ��tɔ�&e{�w�a��K��ݿ�;xC�SB�͉c����Zy�*9�(�e-O	y�s�<�I��x�Waߤ��q5�z���`�G��:�����d�����?�u�Z�����~ �F*�vcKd)��X/��L��PJ\�(�u�"O�ʱ����~*��ӗ��~��;�v�r`O*�zWqP�UL����q���o�iʖz���*5$m���Y���}y�Ê�t�Z~-#��� ���H�utgݥ�y<A"�M�[#��[+���V`�L��C��©R��U�=JhQ���~���w�&^%�HF����/�a<�x~����,l5`���'F�:�-W�>���̀�S�}lQ"�q�\�#�O�ҟ�檣�����Nm?=����h��������L`��u�
�e��
��ɝ�U�ѫe�5���m�~�)�K���jŐ���Mmz���xr�8��R�$���iǳ�օ�6�L�J�,�T�ɨ���ruWT�2�9�nPqO0^g��W�29L4mLsBDNs���#��V|M��4~�S���Ȃ ��
��Mr|�쐰kk��"���vֲ����ZV'�[k���#���|'[��]_��!"m/3.��&��d�o����cP��T���^�]�|*���#Q�&h)<�E�zOE�1�`�a
Y�! �"�--�s{�s���5!�90���j	RZ$H�#+���3'���!�b�f*s�$�V⛘�� �Y=�jYNqS�?I�|y&o��?RT��]O*D�?�,�t�Ya�4h��X�EO���d���5�QkB�>�`��Lxޣ{{�\+L����$C�<�2!,�n��H��W�S55i��O���q�Z#v�n�Øab�X�f&��ѥ~�~;�6���l4���0o�\i}�~���.X[���Q�����_es��Rs�1x����W�۳L���9�	�[�}w�=Ng�l��ى�8�S�����{ݣ�;xg��,�!K�M��8�7��i��`Z�I�g�=B�l���N�hb�9��e�!r+��n����k{KfH3ޚ���:�!ޙ�y�e�sK�B�\3��gǦ����C�dP� ���d:���ᙈ���76P�Xp�#(�t����6|=��鐷O�e,� xZ�)M���8.����T{�VD�a�:�Q!��x��\Y���Z�`���
�>ks����jJ�T�z
V<{���6��� �y�:����u�h��݊���D=%lW��Ds[����g���˕�jd��S:��0��@ͬ�0��b���*�֞Ǔqd�h��z��,���A]���{>|�l�2R|��E���l� ��"ъ\g�!�.s�[�Y����{�FZJ.�28�ᲄ����ȒZae�͓kff�<�)ŕ�I�;Ǖ���C���9ME��^Gt�>�P��;&��U|�G��1x���M7��9�^���F�kNyEԙ1u�+׈������CS��R���D�No,՟��$3��.qp&�5<�Ҵ>k��K1��T+������bJ���<�e1(nyz>�l�1��� 3�^?��_s��|Z�5�1x'��S�e�6���D��6PF&kP+��ïj����e>�P�ym��u�ƩH�!!M��5�� �cJ� ^d��ʐ����T[�༮-���a��g=�@�1f�נ�>�x���"K����վ�1s��on��<l�����w����4�\�bA0���m��Wm����������
�j;l�ɾ3����vUT��<{+�r�#�wM�9�*H_d�4��Xm�U 86�sU
ЦMYQd��R�B�3��ل\�g[U
X�G��paKgV/��t6���X�F�׳�X_�%�lMӀlQXF������9��Q�	Nh��8Xx��W^�z�.w�6�^~�RA����k}x��� �"�XF�<X������b!�#H$� ��&yE���T�*��Ǐ^#O�b�(cZ��cM&ɝ��pv����Lo��_QV��	��<�kzl����5P�J�[�y�Me/]�Q.�?oeX�������A���v�ץG�O��v�c�  �!�
��PU��FW��C�8W�'�'O�S4���9�u��u��\�E_5��a+�l��Ax����G{л@�=ZP+��Z�҂�$�����jO��Z�u|��Yo笫SHwɋ��8����]2{�a    �c(5b���b��'�}��OsU<m�Z�Af"�Q:�(�\��V�϶��!�.�<rۍP��Sb��&I#�����2<��.�t��,�����>�^�q2m��G�2�͐5�t0<Όe� \4��l����0��V:']HpU�����r?a'�(dZ�xx��!|�W\�ƟB�W�|`�}R�����Ԛ.��(����<�q�B���q�:+�\�ş�^�2�ż��+1��S�rͯH/����'���y+�ӵ���78�+��%R���N��\4�M]̎e��'d���������S	5���B��M+�>[�I<K#sPlSx�fÖ�� gtй�6�S=�Y'�Y�Z	Le�ʞ*�σd���iۣ�����{�{2-'F��Qg�����w��ADuIfw��Ш�"0J��N1����Y�Ң�4ZxK�ඎ���`Q��!��&R���y>��{u3�Ӥl�h�x���;G�
UA,fPD�d�pM��w�*����umk��TЊ�����LTU.m���\��(��X�-��!�v�k =�����G5�w/����\m���<�8ԳN�����c+jc,]��ˀ���,��IlMpf�O4��|"�S��k�R�3D5��JER�!�=W�E���Y�<�P�UD(6�!܁�RB�V�Q��]��e�"#��T�x$�c�Psm��;z�L��W�b�ς�A��.�ڹK��>.�����'���1��p�k�p�,'��g�.�����ktw���C��B�����گ�KM̻(��P�e<��Rx��Cɘ�.�N���#x�H)S��6-�H����A�OY7<����p5�+��­1�9$�i2{��M����?� ]�B���Ip�2�F��~���2�,s����۰}j*�U'�9`d׆7��tq��m^6`ׄD�H�#k�u]�Pl�_)�LO���t{�J�}��h��6���S�����lfe�?
�|Z6{�-�d�NEyP�v*^Y���r�{��X��e@�ɒ��q�S�Q�+/�Ѿf�+�\�CXs�Fu1� �lW�cJ���l|E+UH�&d(g��w���#Tz�w����X�	���D���aPY�wV��mQ����[�q|��g�@�KZ%-t���݁�N�Q�u�X 3ֶ���J#eS�MIЍ*�B�p�2�֚�8or�w��f��K��o����>G�F��]��@�+S
m�$�P��e�= ��hV<�L��@����j3��N�#��jwC��Z8OY�d��!q�(	-O��9�U�BP�D�5[�^)yx�U��w�~��w��o�����x�oc����$4vmH��_�q"�q����Ź!sU(&�.���"��]i$^9ʬR��OU�wHձ�#�;�{(����x<��gPu ��ׂ��Q��a�d��2�N�;P'A*�5{�X}��Px;0pv���:k��u�8�d��W�#�k�� ��C?�I~�����RJxǐc�3����^��<<H��eSK��ŏV�x���&�l�����0N]a}Q���q���B���!7V�~deYC�}I�r��f�Ѱ\�۵��v���W;����\���1J�"�'����}�Z\kx��'p�Gq����uk�R���9"��'G�^�zl����U%�r�U�}t%�`%�<f	8!���c��K��Ao�$��`���	��9�ҖO��k����1�%�H��-$�Z�GJ��s�~�qP����+�A�o��*[	ϑ�bv���Ub��z�i��KX�M؊Z��6nWg�[�ڌ�}cKڸOc+���l�\����P�Q��p�J�}AG��W,��
���|�� ^Dŋ�B9Æ�H����J�\��"}��}��w��6��YJ�?���g�{�햪�V%S��a�L67ߣt��XKM���7 Ĺ��*4@{.G8 �3)��#�űе{�Z8 ����{ �z�z�G�קpx
��$�
�����z�C2?kN�g[����.�U7�k}	z��i�t�b̄�m]Mb�/����3��Dl��ԇ��J�ƈ��z!��vJڤ�d}|u�`��h���[���_[���t2�5q�b��Rm�<8q��j�`��a�c��O�h���lZ�T��f��G�� �-|�0��#�%�w6���}�TmVO�D���kh�\z�~W��=OQ�k>���%����o����[L�E��'��z.�fUV��z~H����c��>3I�ۄJ54�քЅM��xc�-�$�U��o�F��^{������)�����P�4�Y�|X�>[S0�	���Ũ<��<{埃� �ſ�>0��|�S(*��8���C��կ�yK�� H�Nx�'���!)vs�Ec�͢���tsd��!�Ӱ�͈vxv�K.�����)�j��-�d��̔!k-�r�N����Zs�q�������r��)���ӏ�X���4�hb����bXq��.���Z��R�6OI�%�]n9*XP	"U����+ІY�̜.��
�l��q���e���0��H�FFϷ�w��ˏ�vZp��k��@b�U:�q�q0��Wv��niN�!8���9A�a �����;�|�e. ��v��Zh�hч+sTb�����2��E�Y�3Q��\Rգp�'�zT��K�wb����J��(��2�����gs:���\i�va%�(K�>zH�u�V���p�
j�A~5��T�X�3.��ˮQ��
�6��b?/BUۘ�E�S~Ϛz�g�o"��4!e�W`ϧ-D`(`|?�ܲUU=�*�mk�zI� ����@ɺ��Zh���F�Ss�^p�ϰ2�}�c�� 5�6Qs~��:&x��W�@�����G�I����V���z���]�/�j����A���������M���ݷPaG�4d!<��ʘ=�)ʡf�3��Q ��[7�x
W~�H��`��,'����oJm��O{AK�������e�@���ФTp$��ݩ�̲�]B���e�w�E��5 �f#d���/�G��<����Eoe�8� ��M���y>��FM4�
ÃC����V����A!���O��z@�ƴ��&�����$D�D*�8굨�}�U<�5$�ѿ�x4�����44���A|�Q~Q���	N|.�=�p~X%��s�^�O�G@�\kxhm{�c�~�qA/�f#8�P�*����C;U�u��-v�\*����>��Ѱk֥���;_�ȣ��c����]$�"�ŝ���<�0#l��O:�90s#�ʈ���=4��4̭gŵG�hE�c���{����{I�J�ʘ�w�L\z۠bI�>0N����I��;�$Gl�0��\���r鰱��h��&,�!�k#W>	�j.[A�%$*��X������?L�+�z�\��G�!���Fs�& ��1�p���_G���*U���~.������Z��F>�#����2̎X�]Jn^Hb3@ڍ�v��fʊv`~���8�M��%s�,E��C�H&��<O���Rc���*-�Ť��b�~;���J3�$��鴭�pN��RL}�@!���fRȤZ6�7R��6��_bQe�x��?8CCx�6�-XU�1즆���8m�vs��8j�i����gkҧ�v3u��iLr���D�$��A�s!���a�|��,��>!�+N�B������t�F��p�gSɣql=G�������hx@4L��Ƥ;~�:	E������ �	>�禙�'�����b�R'>� ����4��bjc�RRZ��@�#X\V�����Bj`=���=�3ӝ���KP�cm�`�S�R�1���(M�$��œI�L�	%�҇�������\�T.�2W�97��9�c��EQ����{]z��}1iC�EM�C�������٭����d��K�����zx�(�?������dG������i�$5)���c's�]/�����L}3ݾ���$��:N�`~^A<^*�pL��������<����    4��u=^C.�`��,��(M�Bqh=C�[�v�� �:'�:Cn����+���<TsB�Zm�:�`w/��]i��/�Y�����CX�l}q�\y�W?��
��d�,����,O!9�r� �a��.�A+<g�Q/\�)E�]k�v�p�zҵ�	ud�CǮ�n�>�T�=���;��q�����R3W�L5��i�׾�+���o��>�r���;���lI���f`�D��T����-���~�����	"��'�2[�ݦ9�S�6������MT������iQE����(���R�O�G"
��S!��U�e�f2=BR9Ć{f�����GW�ꩣ�y`�Y_�����7��A-�$.3C������?�Z�LS0ӈ�cE����m���6��yז��<��F>;��N�* ی��N��Ir��wI����#��J���if�9v��-R�D�L� ?�������U�E�Zv��+8x��yj6�eSU^��(w�r���$�������-z�Ǯ�����q<���?�6����6(�p���v�A��)W��(�@�&�jT��R��f�lj�(�R!���ݮ�$Y��A�pi?�ᚤ�=v}��O[��ry��{a)���Ax�[بǯ���]T� �K���Q܏g�,�'w�	���i2B�ˑ
��_@���!�j!-V�0WQ�+N�{��p	9��-Ծ��i7ݽ򅇞��q�dGV��ze*;��(M,�mz��wRP�B������s�h�a�P�U����������jk}��v�'Mg%�85�Q˄ ;��uP�4�~^��W�=��F�T���L�0�Tj>�  N4�U*z��6xfkн�s6����i��a�0^[��������������Zc��F�|�-���Ŗ�]S���>��=u/������;����kNܠ��8��4���ђ�-�����}H"�!y��h6�\G��G)���H6�{ri�����@Z���0����
�u�З͒�7�t�F\��(��nEA�T��.?`L��j�X��쒥�7l�!,�?���T+�J�^m�ѿ��4���Q��OY;�uZX�n"!��BVL�3��z�S:�\���zM��t?ݠ���r����O	����]>4�|9�ր��̴1/�R�}L�x��F3JmӪ9�ua!}��J�A�2V�����P����r-�d��Rؚ�N0B�V�P?}W� ���B==$ir͙g$a����R<9�{���Y�+�3��\�zԕ�HjTRs8����S��o�fp��"�=�̢��J)Y!.�	�=�f��'Ȃ�j�i��j�Q�%�H
� ��4�^[)�֙���n�vn�[?��R7m>U�c�ua�������NI�V2(*�\r����1�uФ��V�����^Z�f^6��4z���8�����Q�ȫb��m�.���𵢘̋b��,t��VNc����VX���&�8F����}��u'��E��4K��uGx?H�<���Ҵ��|7�@��e��8�`6}�GY]H��4���OK��C�ب��H+#9O\	�o��	�Q�N�P��]��{���n,5-M��4a�ru]G
I��*`�h�_���wYز��c��v�V>P+oJ�ͦ�P'�$�����̶u�m��68n|�en7M�B
�RH1�i Ձ)�c������<���t�����F��2)x]�3yH�-�WCX_?��_��VN� nbI�W)���'�Lvo�?M�a�z����u�L��y��4�;�K�^��a�A�p,�{�Iy�_�����f���iƚ�si�z/�WB�k)�d"<o��y}j��֢���� �4�ᠻ(���Э�2v,J�ņ䍷����
> �=��X@���V��m�w��P�l|p^R��e�Ɲ,��S���1����4�����[L� ��q(��\H�7������h�d&H�R����z��'ЯK#�)��2w-Q�'q5O���yd��ɟQ:�&.=�N������EدJ�e"W��֓���!�qU#88�:����ՒU�;?�ݯ8��R�N���;�n��[��E ^�����4	b7�a�h�d���ãVѻV*�x�� �#���K�G�?��Y��¥D�]9�p�l!��K����E+U����I�d}�V�C֖�>K9��c��8�X��%���fc�KG��x�,U?���O?��))����,l�,]�+=��m0��S�	F,a��ವ=B�{���g<�?�l�T�(+ɸj⺢%��T�i*&�๪���e$�N2}�;]/K����V����2�Rjd�dʬ�m�_�+�~��?�QdE#x�i�T1gOx����5�5΃���m��W/��롊��`k��HL�ۆ݇��Ղk.Ⴏ��yi�챽��	o����{��<��|zh�~����#�E>^å��oF��l�Ǚ.��ezw8s�-�T����ژ��o������㺲kp�$�A�77�`�����5u��<b�|�;��?���}<���b���T�c�f$�!�1�� ���ʀ�y$�%�K-C�0Ns�������f��4�L��q:M�ь2�*$��a�Y �/�g����I�X�u��`�u�tVR-l~c��:��5�/���r���lt���AG��_�r���������L�[!�!������η=�v5��$�TU�&�L�dZ�&���Kw���V<4"+a9��!0��08�*:��c%͘U(i]�k���5�� �B) ����U8����r���w�]��tk�����	Ϥb .��ի�H���ٿ���-\����t�)t{>�u���H͖�~T� /5�C9�V��޲֧BT����ǭް�b��![�D��N �Q��t���!ã&����G�Q���������1)�L/B\@���=C`��p	-��*,���#�k武k�ڵ��s�OB�[�a���D���q��AB��fR^�Ftao 3��_DsW��[[����Ň8�E)|&`�c�-<�+�x�����̈́)\;��E|]NF�&g��Y�����Z��k|=<��GI����.��'�v�w_� p�x����+Y�׳=_-�����}���]��k8:���B딴o�
�/�M�dc�wf{K$RZg�����fPUX ��':ѽf$�\��Ԇ����z��4�����O��M<kH�y���A�4�\�);�.�
k���̼�n���;���ŏ]^�8�����(g�1���!�7N��������^��ǯ�a~�]2{�&M�fF���$V�l��k�u[V�!$CPe'&�C�!ݝ�0e����Y�m�b���wAUs}-�ެT�����L��W>�h�f
����^:?���@`��CE������o�����Hb&pT��L�׾
:�����fʶuV,l;��3�_ܑ;M��`�?(���XZ#0�L%в#�}:���yO�ǳt�g\��8�N,H�{��:�q4��Dm�"�R�Y��ɷl�~���2T��I�l��{^%M٩s���Oj��|����Is^�-M(5�F�i<y�Sۨ�1�0׆t ��s�ŲYQ�S.�!�������=�"�`R�K�dby2i��{ ��v�ݷ��x�l='X�cXtב�Dw* ���م���������n6W����Iȵ�}��a���
�x�=k6^�4���nF�!IƑ4��/*o��>n�vY���,��%Y���B��+[�.�c��9�P�ht|)�e���T>��f9���z��0O��y��!��b���%J1��ĥ�:2;����2�Sa��4_�,s���6n��w�;��R#{$4��i�$��&��B�Y"H�E
�H
�n1FvԀk���F���SM����S��X�.v�9p��kd:������]�����bƳ�ű���������R�4ٴ�Qߢv��U�iV�2Fl.l�G�<�9�tj�7�SP@Q����P�j�%,�ǜ�����W��,��V�N��{q&��9�{����κ�     @O&����)q�0��\����֭��/�#J����U0@1��[|m|ʚT���O���;0��+ʮ�qz������~�#�$'V��R��A�L=�����)�����/�R5n��3b����7Q�Ό	��.��Rau�M��A`r��9ǯg��1Ad�宒zrs�ی0���qW\"TB�/�	Ƅ�cL���[��p����Ǧe3J�#���!XYh�h��fmͲW̔�u1��^H@��1��G�N�n�wu��_�1O�q�^��o`��8�?�N[~�_��JK�#��{�%8Hx���:d�'��>A�0����{Q��'U����-K]����۾�P���&���RTb����;�!I� �(_fBg�e���/%\^���$��F剢�Pts%c��5m��_?�x�7����zN�)��좤�F8
F#�Aӟڅ�"������h�T��J�.4���^ͭ}��~js�ME��4F��Ƴ4��b���/�u��\H���"�]� 2�T�XLi��ֆK!r�,z8q��R�o\�`�۫�?N��d��#6вNC�ƭ��F�٬�P1/�'�:߰Е���Q
�W���f˴v��3rU�]E��k���7ܵ�%��.uAetI7E�l:iC���Yox�/9�g*?��),���O\���D��	�k�j�=,���x\�K�
�����h&eek�^����ű�I5��&����E�z���\b/-��
�ܛ8 uwԝ()K|v�'Sc
�1�i�t3jG�2�#���� +��98BȤ
��t���	��O�@ �jG�$���5��&�TW!�-Z��_�~�5�vѯ� x']�nQVvN�=Mٵ� �����3&Ƿ>�����X*[�T�0�#�k�`��[��8ϱn�m�ѷ�Z刐�R�����螓	,��t��H��A��/�3�����a	G�F5�L���<��񔺕-j�nRq�'�H�Q�����ݞ��
���'0)٪�f0�����?Vk=��J�D��+6|� �1��%3�v���,%��xu3�-%|��f��V�ww%+	��l�}rAaB+�E,�^m�.6�}r@��a���[�qv�&��@���2��-B�Ă;��O��Ϋ�0e �!� J�ZՇÙ�� ��5����U�n��۶]���n��K4B"0�|ƙG9N��_G��Rj��%S�ʣ:%�;g�rY����oN��=H�Z���i2r*T��K�����Gp؋��#�U�O�c�k�\�[��֛6C�5�X6�y8��:�ߑ�n��t��y�F�㨩H<�1ċ���N��*9U�L��b߰j��h���j��to��:�'ιF���T��g�MU�514Tpt�`{�^��F��.�b�U�=�56t��1nK����>�Ž7�7��)��&n0bH
�`�0�N-n�7�����}��/�A�<m�����kmץpTb���|ށ.�%sk�N���Q��������Γؚ'�h�ģ�(5�#��p]����8齤�^M��I����$�k���,u
v������0�c#�t�n���/p^�+�.�p%[% Г
��i���aR�z��C��F�s�M�t��V+N�N���j��V�rr��AA���l�Se�H�	Y-����sMDJ���)�o�r89���+3([5;N.�OB,�9�^�.�KK�*���Zi9Nf��-WR�|�/�kw2,�&Y�'�i<J�d6Jfܐ�s$�!���d�q��òd�3���H���*GU������XI�쟳%6��c���O��fgϩ��F�Qt�k(и�Pɘ�V�W�s-�c7��L"�h@	���׽&>X�*1��аZ��� ����@-o��TFk��~ [�Ry>?����ڒn�{m1����8�7�d`�8���Y��dg'<�X	�v�}`Y�Ʊ��K>p�A�&��9[K�����f�]�#+�����2'"*H� t���ȃ�\��\.�#�7q�WTM�E�!��I��;I+���� w`<���m�!.��`l;�e�yf�5���\{�y�t~�Lg�*�d\���g�W)!�c�V���X4�W��B$g�X�bsIWkbv�î�v��&L�	�Q�6��ݦ�m���FӗH��Y:g.'��vۘ_^d
�_��p�S��E&
W����݊��%؟*��U�|㑨����8d���� ���
r�Ê'��P)t�Tn�TB�u�Ug�4V�b�}Q�a�
�|�J^���F��݅Gp��2�,a{܂ ��L�'���G�Z������i��9���K�
�*��MG����+�|�d��z��$˩��}�N�=
��qp����A+����т%�j��Ϛ����c4Cӛ�/�4�� ϣ`1��
%0ێ?���J�j���<���XX,,o��$l=|�f����1Z`�-LH��z�:Ŭ��r@����VB�EV��V�Q_��n��?�`�@�\��	lx3Sa~�mo˧ʂ	Q�.!�z��|�Ч��_��ΥAe8Z��	U�2\!�mrmM!���;zm�� ����-��y�xF�l����Lŋ�i���'����6ȳa�G���]τ���%�l��y޶/�8���/]B�~�F�B�⪲$�.���ҮZn�,�/3��z���Óh�rs�{5`z5�M�JB�eNa�)�w�8�]���q�r��
�%|-7��S�:囅��i!$*pS%����g�1�D+z�[��M�ﭓK�]�]�P@�.�Ԑ�����l��~X�u�ґN�'��*���3-�®kU,IP�my��Xj̈��#pƓ<	R�����3Tu���C}��7ΰ��W�MZ��Bw����+c�����>�2�4@��r�UYyB���Y8��ڗH���Y/8S�w��&鍅���mϔ��P�V�������`y3J�Y�"��⚇��d�s�lf4�X��h�(�'{͙,��ґh�9�v+�=ԥ+\���%���ň�6Ep¶�(��@!��Ơ��Q���;H�|�$˝�d����JPᵚ:���c�
��Z�A��ME6������Ҥ(c\�ۿY�4��i�4��K4h��%c�����I�
g���6N��d)L�\5�=�D�M��n_��m��!J�E��:���M鳄z�Ŋg:�FHE2;�n�u+fE_�E8�5�H�]������WE�i糳��wGG���xykx}y�`qyKB�|�:�1�Q��8^��j�
�QE
��"�Ğj�?�����Un��$}4��(��9=��
/|-�R�W��a��'���hN�CQ��r�:�D�$�`��;��b$9}�4�4�9�.E� lvGz:�e��*e�7��)	�L�B�)�sP�7%RZ�Q���|B�
��sX��i2{��-����?�pv�s�����[��\T&U��C��b�,CO����k2��U'Z4 �G�=�)�ɯ����o���&�=>�]M�K��\8�T�T&;� � �S���B�<��B^�@I	i�u᷺X���+Y��^i��u�׸��2�XX A����'6[bۦٖt����Ӡ��ffK��^ z��7�Xi��v�"0Ώ�ϐds��SO ,��%���=U����p�Oэuϣ�)�$)ZX٥RW��V_���� 1��̈́�⠭���TyU;'5���a3�`����"��R,[�I���>��9]�Z����M��wE��y�U}��]p�dz��9�b�|Z師+�Sk:����4���ة�^t�L�{�w:a�mB��Z�=����PI��cU.e���m�	�6�&兰6��F������'�� ��$�`6J��Y�/]:��At<��E�f��8�6�~s�j�w����
3�ta��n�EӜx�7h�M�:8�W>^������E���ݷ����^q�lȎ�A<|���v��E����=)�o�m|�X��7����(�:�RO	�2h�"j_EHt׬����w =  j���Q�ݗ11�f�?�l�*9��%�+���d�A��D�ܞ�A��jU�lO�S�'4-�PL�{MQ	�D������U����	e'X�q��Gn��ףE=P ��@��S�%Σ�x4�Gqj�e$.sQ�������2�@wz��@Y�ޚ-\US����L.GN������D�/���q����5����ҵ�dC.W�/��j(��ርt���v-V���?`������ bґ���b�����_��I��<D�j��?Qͯ	�xf����F��K����<��(���W.\oѝ{��-�86��E��[��oG�v���������l���7>��sC��+1Bl^8�@l���_�a0:����P#��w����?�0)��0Gr�QZV>�'��V��ռ��lU[٪(��x�������z->6��?F���/�|�>>E�r|�2*=�t��\KuNʂX`�)�Y�$&9�\k�|���L���'��Mʡ������ř{(�����>�VM�;*�#��T\�%��zB���,�}Ci�(2������\Gd��`���p�Ჯ��b�1�x}��v0؃�nk����G��bo���Fw�`%"sp'�<��YX�����
�.�[�i�����1�����Dd6z��4������S�9]N�\�1O����ޯ��6��{�,=W���5��Tc�7���%��H!�C&�=۩%K�֢g���ŉDw�Ƅ�.��ߜ_���#��q�R����Ʒ��c}��8���p2N�*exb�X'�l[gA/'�\r�0*�u�j>���L�*���HO �̬Y<�3JǔJ�~س�S�۹��_5ڍ������س���X���.�18B�j\�A�[k�v��kRe«�*�;S��W�p����m	�8��k��oў����#DkV�r��&��Kρ|��^WՉ��'��o�Ld���2�bx�q��Ppw<��S��%�����%\6����"~pp�QQ�܀W��r�K�#+)9��L�>R�$�sK�.�ئ?{R[��q<�~�j�DY���D����͇3��V~Fx��y�hwǾo��AoC���|��Ȃ�g�Ӆ&�hó�Wp]�D:�8�8XY�x!"�.(�Ni�ԇ�%p#��4&ѱ��� e�Ճ�aݥ���L��gx��2��q�����̲�ioD
�&w&Xtv���6����)߁k�g�4-$8u)l��C4�~��>F�W>�8�	�n�.��+6��9J������A=!�{��r���)5��*9�,-�A4P+�]����Zd�C:��z��iE�hv�+�7���)8e�W���1'=N�ʁ���5׶e�b`��9�X�z�ĵ���'�+v������t��X����Ja�ek�:��¾[8l�R�`x]'`��m�ZY�?�[�ut�=n"����GH\���ڸ���	�4��tGM���XU��y^�Gt��U�����-��#X��C�jF���i�d=�0r�71�N������?�	F�
����E��3.��]�v�]�%7�r%2��
<Dh�Ӡ�F��O%��
5F�%u�����"����@��v�#S��pw3vM�V4�{�n"0Z��fԬ��� ����v�	��̒�U�%�Ī��5���Z׻�<��X�/�	��}"���hC}'��?��ƘH�5�P=�҃Y<�P"ޑ�)�,�k��	!��ř�����Ăw���hN�+�/M4\G�l~��������+2�������,��rwqT���]�.���F/���<��0CR�E�s��sᗳ�]c<2+A)�zM�_�3�+�.��]�� �
鿍S&:Q5��/W<pTXf�������Ɓ3�A���Ƽ��o'J~�>����H%�\�Y��Qb+�+����f����1dv`�;�١QRUF4�<�fۇ8���r���;F%�;*��hY��>�>l4�~�oU5����k�l�)H�����=�D��#���u]��4y���i4A��Q:�{�_:\�
	��	D�!��nF�S5e��B^{8�8�p���`���|��Y<��'���ԱS$w�[�n��O�Ѧmp.-��dT��4C�,���Zaiwj���W\�PDE7����.�݊X\�*p��+:�ݨ&�]|[�������Wl��]v��[�Q��!�yDP��*E�Zy���h��P�������c;����9�k{V�����к��1��`G_�?l���H��/��t��t��13�^��s�v�rx��ˌ!�=���u^>���'��T�c\:p�R|�K!X�X�sGei�Z-�v.\K!<��p�`�;��v~���*"c�Z�%�^���2<8=�?�3���s�%-2žd�E/� �����5���D9xA=��v)����Rl_�����^9��_s�+�@~"�]/�e\e��6�h�/��M���s�B��D�Pͭ��JX���	�,^6����n=�=���e7��x���޺O�q4�M�QҔ܀�1�����p /���xF�`xr
&�9ư�A� n��quC�p�v���ԙ?G(���^��0��Dzx�z<ͧ���6����`�'w����l�F	�����4{H33�!�\�QVh��Q�.�Qɔ����9��H�7E��ʱ	qW,����O ����Yg��d�������m�
��V���?���o��"��1}�iC�*"Sj�Vv���F��v`�I�"��SlO�B�d2�ԉt�aqpHK������fB*\y��ёg���x2���p3q�����>��;�e����S��f@4.T�L�T�NC�H���S����5>�x��,�U	O�����k� [�,G�Y� ���v;_���9���T����ES�52�����"����)N��(
�	�Qi�=���
�Y�@e@cm�4�\E��4��?4�X�*��r�j�	�sg٥n�4.ʹC���Q�2��{�nχ�U�k|"�4� �}'���w��s�5L���m�J/3�ü�Nqt��}4O�i27,O:�ڲ<�� ���S��]=])s��ver҆=�SMB
�R�59CX�݅��05n33�9B�׼,ᰮ�����i�<q5[�%x�f�z*���D����r:�9\�'V~���%[^y�
OC�������LB�@Vր»^�p!���n-�E��x]Ǔ�!:!���k��/:bV8"Hc!���8���ƶ�Y�~C����p�f�:�J�^XD>�w�:=�h&Β�����7���$�e���������z-�      	   �   x�U�;�0��N�	*�a� �z�.4f���e;=h�dC��xn˞˪Ye�FI���_M���p�gR�:{��W*ղ$5�1��{oe��9�g#iE*'�p�����K�|�#F���[w���U����uC���E      	      x����v�8�6���<n�=�C�	$��Cw�$*ܝ*�������\�0� ���*�s�R�g�`���l�*o�<k˼iTr����5=��i���1��J�$o��W��*���߃�k�]�^�~&��ۏ�����0�W��
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
�Ҍ;� b��d�}r�]?T7���,�ɲ$�Var9v�p�	�,/�7R�c�L�0]��K�hX�e�ڕXѾ�ɛ;=�]ۣ+�F�a�dѷ�v1�����᥎I)�eie\�9��"Ms��i�������eI��&P]&j\�\ZخA3��v����Q������w�� �;]2      	   b  x�m�;O�@���_�!��<fyl�k����X�p�� $�������q��`���TS�w#�EƄS+U�2И�.�6�����8WF�ј,�;wǤ�Zr�y^"�m��j���pgop��R�J9z�.]�<�z��L�B�,���4X�V��hJh�F��u���r��ń�B�,ae5}x/����v�H�$ų���������������t�m'@譥�])��7-����\y� �/����_��b+��;�>y��~�j�ci�Ø9z�¥[Slj��37ő1R��"�@��W���k7P+&�ĺ��������Z�l��ow�L��P-*K�>|RͩX��0�
�z xv�      	   �  x���MN�0���)|�*�I�(ZQ�l��T�9	\�0c�Ң�����y�y�$	� ���@���Z k8{���A�������K\������K� >�%abܒ	v���k�	�}8a�n�i,	C�N�{����ԉʜ�܉*���'){~�+�s�����P%yov��ϧ�7���,�>ؕ� ?�*r���U�gWD��K�z��l)-l���x`�TJ7XW�/I�wj������&���Y� �+��Tl�xJi���A���Jnf��L�43i�d?���Xu;j�Pw�{V�o��k�{՛��H�\�l�g�\b��i>2mE��JhE��yV���j?0V�GԸ����r�PVf��Wxĩ���F��s�����'f�w�4Prʷ�s���l�b�7t��
��NIj7�=q�y�Gc����y��T#o��ͱc��R{�����u��      	   �   x�E�I�0E��)|Dʼ4��,�8�mE�?a����S��.�XՐ3e\�i�3��$�"U��R;�:��<.�����M
�Wׯ�����+т�����;g���F8��h\�.�+�m��>��{ �m,[      	   �  x�E�[vE)D����RŹ����ʓ�#���QU`�7N���p[{�23|�u1�ε�̹�D-�g�q����{�_Vd��s,sւ̴�{�E���>^���g���/�s{�(39l���s�=X>�<e޺k�icLߺk�{���Q�������]�X��3��\1�Ǒ9��n�����'ogi��iJ�Z�F,]���;6�ص��E�t�+�Z�A�.�1b���ϵ�ךMR��ͱ޶IM�;����섏�^�m��ύ}��s1��4ڞ����W�i1��3�wa��w�{���|��g�����.�ڣ׮CN���6I�| o�J�<`�c[k����9�Rr|\�ۨ�>:ao#4��j������\�F�!�t7��_A�R+�z�q6���NQL�A��9�s3N�W*6�L\�<���·�m�9��%hR�1����η+��86�m�c_U����0��m����`95Y�����.єy�b$��ԧ��uT��a� ���$�+ߴ�> ����m���0\�SR�,E����:�x(���/��Æ�c����֒� {in#�?$�>�@�PEIRc:CT�q�b���J�C�W��T&_ 4����VQɧ��?�z�'��!�so�������vKkz?G��Љ������[��b@,Z�H�
��E�q[P$Y89�X�!\��,���T"�����}�+i���%בN4�^�a��ZJ��B��)��;���s�ې��Yҝ�'GaB���*�� �1fT!�j��G��M�X���W�� '�c˕�R��nC�A@���p��O�Z���2�OnW���W*��c��}�둒~�d��	�A�R��~��'Y�b��?.� BG��Rj���WK�O�r8#�[�+
o��ر3>0\5RjF}��FPߦ|}gMP�:ѵĮ���)a���}
TBE��*�pB%�ѕ0T*���;���V��A�H��]�/F�v(a���V���W��PĈs�@���TǅȰG�
��+���MI���H�xX���҉t�O@p(�vח.D��F|@�FM��ya�B� 5�����t"�_`��k|~����$A5�=nҏa}�����y	E���
	t�y.�d0`����ɝLDnC5��"4�Я��Uƫla���)1<���h����i�C�G�s5��$�n���7S�,Hӡ��t6�(�k�SM�%֛�ݏ�ͺd6�84�ޫ���G��`]��jA��g� m���&�`[B�#������-06Ķ�-��^'��K�\�rCW�fdE�I���$�<��k�F��<�٭S��:�W^�c��K{��7�I[�a����(ƌ.�/OP�1���[��l+��`,�3� xKV$L���=)"M_p�qTMC��l���(M�Z�h+[��b�}�2�oq�*饕C�U���^"�����.n��H�����y=������|��~R3W:[� ��ɶd�osmJ�eVS��Ñ������ ).�ڵ�	��P�ki_[^e�(D��p��.Y��S$WN��p�.*�����V��Y
�js�|>y�R�YO�_z�l��u�;^ۮ�tk�s���~kѵ�v��g^m�t�T檷� ��xs��=vZ�%h��� ��*�v�X���z�e��.,��*_0i%E�$�����υ]�zj��#:c��z؇\��k��rqT��߉���T/�ݿ3p{	�?6D�O��f^��r��d�%_SC��Q�P���} ���;Դ�o�~�
c��$�M�=��x-��;M]��)<��Q��u0�>;�pI��G;���9G��7�l1�|z��F c���0XSs�E�$b.i�m�	'�����]����{?�P&L�3Xf����z��D����䌿7��<L�������A��{��x���ju�g����j�����J���%]��zf���P&���m�| o��D����>�5V���o�Y�������r�*�      "	      x������ � �      	   r   x���9�  ky/0�ƣ��J6�ՠv���X�`3�L2�!�xiQ��i^9�u����#����+"�k�	|4�*nVZ%���&L=��;�另�͖!v��_W      !	   ?   x�E��  že�]����i�����0�@�Zv��\ax����q��
���HD$�      (	   �  x�}�M��0���]:%�[f��	f���I<��r�j��?=@��@�ڋ�Ac�_=~�>b�?�{|�O8L5�_�Q�>�i��_��`t���K��� i	 9H0����(��7w�%��?u����������sq�Ŏ�m\ߩ�+α�	B+'��9'�h_��Υ�Kjg
Υ]f4o\V����m�s]�p�Y<-�絜�Q��f��?˦[`/9W�ϊ̩W�18�7ϧ�CH�i H+��'Ӌ��\r4?��L�_��oj`<�~�+�B�Mnm�a�s*ǰ�̼qCo�N�������=�{r�i7=4��w� 3�Gr�/�6/Q�a��@#�J_�mA�WP�ڬ�UK7��tw0���P�*}l5������2T��H5��3R��g/A��G^�����'����O���	-(��Vi?�|v>�T�*�G��/���"��#_�W�ǿ�d۝�fK�/-�"�q��m�������mq���9��<쑻����V��6"^D]F�=nO:MNy鍏���*���8�^�Zd/*FQ��Y��E��d��^\�R��%�W��V��r�LU��0U����ӭ���o]�jc�J�l�����cl�M�[�oi32��f�%�-!�O��md�if���! �E��02��S�.i*�ݥq6߲���[ތ��[�f� �i����%Y���g6kEoX��U�0��RU�ʆ`�ԊYQ�u�tFhL�� i��j��kxB��5<�:dd�oOo G��4�(���4!ˮ�Wpqe�
.�/��$��j6����Z�Zݰ
�t�L�B�	S5o&ͱ����F�fF6�cFZ��ZK1���ť��8g�]X3��z��O?qM?s�_�0�_8����O�ډ��S���m����C�����<�33x      	   @  x�]P;n�@�קxU:�?`�ҿ�H6�"���f�Z��4is��bYK�hfF��Ϟ�*��sQvDJÅl�|�Q����I�!�"'`E� ��Є��yLr���΄��(Y�i�,,�K�6̵��ϊh>�c�uGq�E$4%a�����V�9!���e��`-�&$��I�xRrTQ1�M_0��7��Xs{�a�3�5�;��R-��a_�Z"tݷ\���E�U͍���{���8�k��������O��(��f�2K1���[|�H����l��̢{iH�a%j���B������.�ܘ��m�ױ�8�}�      	      x������ � �      	   2   x�343�4561�420430�4�v�quv���st��2D�6D������ Ӛ�      *	      x������ � �      ,	      x������ � �      4	      x������ � �      0	      x������ � �      .	      x������ � �      2	      x������ � �      6	   >   x�3�t-N.ML)JTp�2Bp���g.ǅ�Iʗ����24D��L������ Z%       5	     x��WѶ�*|f_0��"�m�Q�A͹ɚ���)41����Zy�C�4�U���RKҹ�K)�}�K��R�*M3BZ�ŹՖII�s4�k�=h����QJq��7����]jbO]s�i����V�/�c�Q�Z������Wߏ=�~��,�,5I��z��6�8����񫠂��#r�]
�G�z��&��ʪ���;_�$��~�Ŋ�!�!�.T����ȵN��)l�&
e%C�8�����Uh�x��j���nb�=���+w�G3L[+�K�w�C�UlCWG�;�T��BK%Y�Eq�f�rr��q>n56!ҳ�]�B���3��������V��F��ڂ�R��~4�T*��:A����
4L��k��97�u=��.(or�n�Z� g���u�P��COcj^�]'S�h���OhÓV��K;V��,#��8���:�6v����ƾ{G�1�!KSX��ы�P�������~�)DQ
���W��lQ6�$�C��]��h���xZ3%jP��	H�y����u���꠴^���]���N~G����
�hn.��F�\(t�r��hf:YŃK��E!��K�����#�s�ܟ��~��R-_��S��P��B��~��:2�J�H�S���z�t�b�)-5i����c��O�)\����{��Ԁ.6��F0� LC������w�۵�0)$��k��1��LЬ���W��o��F�����0��o�sź�_���̀�]�\�%-ma��2>�H&\��k�2A�p���?vqX]f���=�R^f�RQ�	�Ћ��DP�p�KA-��Y�b#Hv
�͎��cUX�p�R>}N?����,-��=��]y6m��L�r`6rm��ć����m߳�+֔@��&���%�5:?��#�ה�����ʖ��э
�2Ӗ�����*���qB���������J��a��8��L��������+�%`�uv��4E���
�ѴF��'���#�P`B�v ��7_��6��%��Ԥw�G����}�f��䷾#J�HH��a�JW��{/d��3opK���ߏu�9�$��9Q�v
��'&\�.go_�yK ɕ%6��=]51�:;-���2O��q�_hyA��q����X��������)�{ǟл���Da�;�,y�景�<��Zw���q��:y��P��nU�m	��#��d�p���9a1 $���7���:�'�V`�cv�ۻ�?�Fc֌ܱQ���Ȓ�����gv��+�?���'@�ϰ��Ŝoc�WV̿��c����řB�0\l^X65�K��c��� f'y"t��ZfD�b��B�/',qM6�'��׈�LW%J� L�J�F`ǎy�¦3��CJ���J]~aa�
�!���"�W{���l^�W�@��+$�Ų��Z�o�/w��R0�a������\��l;q��{#1���m��ĖJ�Q�<)1���8=��y�ofL�lN���<�q�Z<U��W�C#��d�	�K�8�h�cXb����V?�B�`a�Tg�˦�x|+�P����˂Ƕ�d��3*`9����4���Kd��ǒ���`�x�,�X`�.�&�J������d�x�J�Ӂcy�5!�.9�&�ޏ��L1w��U����^|a!}.Q�2��<9?@��*�k�t����?�J���^xbx��~�;{���C%l{��׼a�w�������,�tAd����������}�Z�,�r?͏������"!�M�������?HcА      &	      x������ � �      	      x���ۚ�ֱ-|M=�2q>\i�$�2It@���o�ȶ�HK���(Y���:N�����E�i��Fq��8*)����C��(]��zߜ���ms���`uXEU����I���	������]m���	��K�]p��O���ޟ� [�_���]���V�n�>���96O�f_��8[��(.�0N�h	�j����w��������/_���0��Ճ��@�ɓ�v�y�%y�
>��a�r�(_m��p�5O���Ҟ���c��u,\�:
_G���o�}wBIn��H�]��r����t_��礊�*L�4)�
 �X�f�O��_?}������������_�Q�|�3<���L�4*��UFYZ& ��B�����v]_vmsv�JN��u������>�5���2��ss�s�&QH��eY��	�A�w�='.���<_�V�끴q�!IK#��]�åڝ<I��afV+,��K�R�~P���O�B���X�2��$1� ���?^�� �A��v@��`BI�*ME4E�S}���j��?�����uAͲ寣�5������9��t�D������Z�� �??�9(�`�@�/���=�U��U&!����I8�����������<��g�[;wڀ��jW�"�N���ހ���i���]G����e�"�
��4����?t�$�"X�8��O�y�~�׵����Tzȇ7(;�Hi�G�Ҩ,ӂv(������|m�M�T��$t����z��������aA�d��"^�q�ÆA�0��߿n_
LӇɏAF�-�2*R�^�/	X����>4�CK{Z�n���K�o_��ۺ���P��l9�VUT��� �^���Z����p����w��(#��G4y���禇ߞ��jK�F�t��~����>8^�X�wm�nk�VnH�����WO"��'Q@�~�|5��Z�D��ǎ��/x�[�����Ӫie'm�@Np���;��Z�;͛�S}riԁJ&5��Պ�fTe�7RL������s'���ɢuO푔�Q\&�-e�_E�s�	^�nV�װ'N�>*�Ad؟��Db��Mw��o���/B!�M�-,X��L�|u����i3V�Wª���X9�G#�˦�׷��aCs�mN�H/��|���������s�|3+���
�1��o#����!����tLS�%)#P�Z�w<7x��`}}S7{�7�X��y��E/�f�=�=�N5������}}�'��iڢ<�3�48m�	�8/��"�^
RQ��t����EB��SéqG
�a����� ҭ�fo��x�?*�]�`�X���Y�X2�b.�_�tV�z���(��t�,K��@y��65\�=���f�w�$f��gVh���*�r�����=\�,q�:{y�+,W�Z�Q���1Z�
&Ӄ��q2�r�@��?ʈ~˾�p��h��O����;Ts4C䮐Cz��I�a��Z�В�;qT (8`"�eN�w?�E���@�(ހ�y�Q	�iU&ELsʣP�5�Q���{��wd�f���z��5)�็�v���r�d3GjT��a��=R������ϣ��z�T��
>V���QAD�Y���}�7ǧAF�����6���[��d&[\�K��
�-@)�A�?>LX!��fg9+fz
�S��Wy,�G!`߃�:��]s�"g(2�>�%	?i�m���i��_6{\�<�����IT�0��9���z}$�r$!b�u�X��V4��WE�gY����]���ت�uӃ�pƭ~�m�����H��n�*���(�c@d=6�K{����a�߁�	I����3�=��W�}�̲U� ����K��?8|�x���La��#�l���i� �ꏭ��p�c��3\Ψ��h����d�3\��v�}��լ����<I�<����z-hN.!�T���T���UgIU�&/�l��S�����˥E3�.��|��~�%QJ���\���
�,�8��9~�Y��bl�6�¦op���^'	G���{�t��s�1�M��u ���WRLk8�o;<�w����u��VB�Z��U�y,.��Zz�	��Y��,�(_�-�7`K���v�,M��j�*���"X)���A,2D7Ҳ9�A[���ZbV觯#U��7�A�Ϭ��.�,%�h�� m�>��Y�� �d����o�����Y8\�s�?����P�xYQ(�7N���R����x��3�����H�,J3�G�j���rf@��=y�� �3�D`+���ɽ�[8���}�U�ff�Y^����,ҿ})
d3��	�a��2�xڐL4B�l���u{e@�!Z�p~�:zfe���3K��q\%eja������Y��`��buO�����Ѻ�Q�j��=���E����7S@��C�Ń�0S����ԉ��({�j
���a4q
��14�ƪzh�VUB��(Zm��g���=�<�	�NwM4�t"��V�'�z��eQ����8��������I�@8�<����f*b�p�:���V��R��
�j��B0�2�]�1j���R0����ϩ�	L#_��U� _�V���(Z���m�u�>��m�� ��hN��\�a坺}`�e�_`\~��Y|�A5�x��LH��8#b<��9R�|7Z�:�1h}�-�b2�d�����j���V��d�K�N����Im{Cz�hmߎ`}��Ya�]�I�K+,*�z)�A�/~y��a�0�j}j�@���u��F�bY߯]�>k��rT$s柳��f�����9|P9�x� �n������AL�3P�į]�8�%.q1��_���8ɪ���' ��� Gъf����`n�SdpԖ����K��g�5TY�o.o�/� ���� � m���O1�~����J� �:� ���dq�ʪ�\�z�N����6�'�S�un��Ro#��-9��m�GX���7q,�(
0�`��4s�~�}t(+��X��cO���*.eI�(tq�[cp���m�K����WD��~�5v>�y�N]V�QI�HXl��W#\i�
����gsX�h����#��]���|���='s�t����tin�G	x��+� o��Q��)�̺�2�1?�X�捧	fI�g I��rգ{�����mz��1-����Cۼ�m	*J��e.����`Q����b���n�T��{�`�f)�KE����s��ʛV&(�}��g�J�']���ԛ��V�m��v%^�IZ�$*a3:��?&u��+5>�@K��H#�A: :8^���nvr��;��}��[�ߛ���˽{	�(�1g,�g�$��{̓�^��T8��We��aNr�Xm��:�"ݮ�c������ؓ����}�`;�������S���+�]�E�X��ϐ̕9��b��E/���&�+0�!�J���a�A#P�0�	�_��a�%��s&�Cv��J&PY�YWt��C�G0
5��-K�"�x��nЛ��Z�&�~�4{��ǚX��F܍ ���S�9����g!znY.S�0�Ra�D����~�͉�q#G��� �%�2��Wѧ�n�z"�����;����>��<�ͷ	e�*3�:r��h�M?x9E�%?BHy	i�%�
�ڸ�>���
��L%ڂb�8L?���nYVE�����Y8�5۲�R�1��6�>wM�9vs��PLpr6��U�C��)����n��Z�B5�"���b
gs"�+Ǒ*�b��_}l�iT��y�� 0�~�O?H\�s��&&�ui��o.�Ӏ�-:v4`�����Z��E5��û}
?�au�`���8�ÙG��xy�oɑq�M	z�9�'[�`2`��P��3�QI�Km�2��c-XLR�o��!���E��8!�j!�Z�P4�V�]��x}<հ?�b<�ᔰ��.@7S�e�ɂ5,^�����(�y��*�@�<����o��Kt����.0P��0}��IЪ�DP��~��߱i��&��j@�)�#����۝�C    ��	��e�.���Ӛ�Y��]i͘?�ɔ�f��zd��i��冂�Ȃ�E���2�n�>���=��p�&�S1ܲ*!E4���+�����ѕd�}�ת��R�@�G��Jo�o=Ce�_ӑe��c��P���Y�g2*��I�J?��Ƙ��IY�`m����<�'�\y��l�~�F������ֻyRɘ� t�2P��g�{0�\&^�r׳��e��?є��QDx�,�d|��j����1B1 ��d��E�%%�
c�Gժ!G�3������M$m�_�t��B�w��a����9��E�(Yl0�ҼL�Df�&��篣����J/����Rd�Q��uwl�;���.p�&W�w�njt`��}�[��CڳyRE	���@Gt*9��8#����*��yZ�`
��8�VL�@Cﱣt��%�ɒ�b/ͱ�+&��7�@�0/��Dw�Aq��Efv��*�
ƞU����Rf91PJ�-ٽ��)��b1+��\��ќ��"50;���8R�jyQ�h�	"3��&RX]�9`�8�ŪOI-��"�ۖF�C�,y�e��3.	3�9�P��VE,��]��\����ۇ?�4��p��s	؄r�ed/R�!�(��x�7x����J���x?1e{�6�E�_�$U�9�Y���)0�Q��h�Ծ��ǹ\*��H+�l��(L�����x	�Y�c��P��.���8l���/�w`�r�	���q)S
�۳7�@��@tq6ᇂ���B�jA2�${��V���3.�%z=���~F��X��뗽���Ue��� 2I�|xNNN�TyD^^�@�*L�W��"̩?4�p8�������z���qF3�匱}����]9�;�}��1V��$�f"�a%���	��֦[?�E?��V�ݾ��P~���զ��.I�f�$�?���pիX��#EĢ��P^^�T%�Uhh�Nj�?��~�9R�W,Ry�Ox�Y)2�(\�`IRMO��](kԚ��c��c��0al�Y!W��`���f���gbK�@�U��R����~��5j�<�5p�_`�rU�q��p4J��>_`��qe�Ay�c������֝�m��\�d¥.e�����?=�^q�A)P��&�
΋$g�q��~�O��8R�����;��O-�!B����m'�[2�;������!�	�1���L���1\}c=���"+�ȢQ��c͹srT��/1l�T�w�E�T��?_��{9Rŉx��(�0�U������A<�S���nxC���y�|eX,�B,y���v���ׁ�yL�0�X���"J��`Z=l���^��y>���R��4��OBg'�r9�w�	<��E�(���M˘�e��I�5����6��n/i5�����w�K�"�]��2Wx�,�h�**��4^�.i�a�76��Y�YŒ�Hbq5N�tj�xJ}�CKEQ݁�jK	�fF��;3�.��:	���o<��Da|���Ů@լdid�2��hWI�ql�l�����w�A�Q����ڢ�=�^���ZeJ{D�y6=Xh�%�6��tT��K(o5�h�c���&�n�ss��R,��ĕD+
�S9�����$|���sڔn��:�0���Q��ozr	�^`M�.���S��;T���Ѕsv�S���*IK������$q"�����sF@M1��ߞ;�H��}�9,j9�^9�܃�
���$�o8q.g��U��%�5�<���S��q�x¢�T3=L̈&݄���@B�#�����]��d�+�ϴN�}���~ ���֊�4ɐkx�p��D����/=�maX�Q��$��{L(O�/x��{�5ƌXD*���U_?�1��z{�q����(�I���H�,�y�}���"���8t3��!;�����QRcE��Ǫ�mF~0�eő�o����� "X����s��7��S?u���Z�!!�gy�U�	<��y�N%~Hj� ���`�>��Jo!G��>��݃VY���bS�or"�qf��R��X���Y���3R�L{]UB�*�G��� ��[Ƙ��E�:�VTU���E����
�t��R(�P���s{��%�����$�� {K� ���\*�N�Nu0B�O�̃�e0�tV�	r����q�f�;��x�I�~n8�I�৴�O�姘��l�+��({�����95 �^�)���H�4�E@�}w��z���m��չ�OvF]�bw�9���������[P#7���Ae���Y�v(t˱\�	�<x�>�<�*�&i���Jy!�:D�Y�qH���!}���*z�n:��,(*v�sժ+�E3��-Ru��=�\�e����V���ߧn��Ƥo����9f��CGɊ,7,Ef��Ѐ<�uD�I2��
�x��ڂ��@8�w*9�
c���-u�*#�A\a��_L�����x���
��>�;r��9^5�gE�P J����������,�1��M0;�Z� ��: ��l&�}�M9�[��:����In�q�$M�#��lbO_T�&��cL���66q��Vc����3�I�X�HL��t���ͩS�H�h��*�(Y8�uY��DR2$�2_)RFi0���F��5_�"���Chև�T� �8g�3�v���r)'�g�*S�7r �G���a�׎�?@_�mx#:r���\�6��\����iw-\ˏb;r�pQ��~x���炏�K�" �y;2"c�}<�%��V�}pQސ2�gX�J��@F%+:�q
o׼���H( ��C��%�2\���)�2��xW46���kH�c��T���T��g�2KHFU�4:�+�B>�[/�� �9����Q5�[yN�G�ڣfr%��Rk�	�=�Π ]�����0�k8� �l�ی�$�w���R���bxC���A��$>Fܣ����:]z�uPEu�n4�%Z�f�i �����B#B'�׏�FW&"F��ye�v�B���XOp<��0)k�*v\P��c\����j,=S�pAi�R �?�c�A��ލfU���E���ø*�ڔQ��[ ��~��!�d^��5E�'X����"�W�,h�P@�u��ɗ�
��&a��q��(�ڐ�Ǭa�=��8)*�|e���؞a�ߢ�H��[�Grv-γ2Q��pD�sGԃGՆ�hX�Z��Q�?����o�18}Ṝ@�r�G�h�^B����]P��Q��f*҂1���]%�u��e���\��:a�$��V_1���E���$_	�N��/ž�'6M�+�u�e�}�^ɕ�W�%���	��d�^ե�(�s�B2��a��c�!�l<���:ƣ4���2JW�4�n}X�;4��WS
prKt5=������'��d��%hyE�	&�*��t+$5e��!�t/�,���>I�b:*A#�c�q}�������'*遣��]����?�x3e�]�&��"p�Y��F� E�>R��d��7�,aq�I�v�����c[T=�'69R�M_o�4NG%���M�ͨ�a�Ce�9�a�^��D@#@��*V��8�m��V�ȘkQ��3n�S�m�J�6��ǑdV��*��y��O�V�ӆ}|��T�3��g�2�V�J��ڴ
n~��u� ��W��?��?3^Q"��2)e&pV�	���+�
�>lyʩ�9Le�2���ju�K߭�֏���Ӊ������+4���+H���2�:����"������-�k��#��&_�7����#�xA	�3��'5Y8��U�6��p�{J���!��3۹�����(�Y`gϯqY��}����e�'	z�tThx��������SJǰ���>ԏ/y�I�f�C���eL�F1�X��g{�ρ�V�ڜE�n�cV�����e[)m�4N/(�HA�(hɟ�ݬ��$�S�$p������*A�'������e��Y���sL�f�H0Y��Q%~�NVu��j���f�b^q�rIَ���S��;�o�:�����
    �D��z{M���PdȘi��N5e�Q �˸��}�]0�,�m:	�U䩑Q��oj$��
 ل#!!+��<�=���3�b/R*q�gq9����u��0'�BG̃n����g��ɣl��E�k��w`OQn��b���AuhP����ϗǆj�l-!�5K���)k�:��q5~�ett	� ��<���?� {���#,E:";]=�Z��$d�jt�*�š�p�9����*�s�K�K� ��"�œ�z�s yo���k<����h:y���DuѰ8��|A���LʹYvx 6����
~�$��#36I@F�rS�M$�T���
�Gh�9��8��[HF�#��8�I.΅=�K',Z�،�v,�CI��<rpl�З�t!I[4���o;�]���1��lu������3������:�qF��֖�!��Bu��iF�
�:��9��+B8j,�����/"�˃���[̃n��[7��K�"���E+�Cq�&3��[���F�jgl\��Uw}>y�~����K����x�y��5$z����)˷k$*1�[M�d�#Rmy���*7KL
9z��b�bn�q]`�I:� ����(����	�h��;AT�v�B�(�$�������of��6���a�j�ˬ�����
��M��Ɲ(�T�NJ�{
<����j�)��|uh�ּ�)��o+�4���?����
�B?ݹ�)>�~sW"<]�c��j�i�c9����a"�-A�@��U}��2~�g�G	��g$�����Ƒ��h���j�) �(�gHbI��!mf�eYU&8d8�q l�{C9Spv�	i��f]G<�Vd��qF���n���#�ٲo5�(��1�b�	�5:_���^�rLʒ~�h�>\�m���1�/��d9/ra� O���f�����)K��s�]���m����_���Ĝѩ�L�����P7��Ҿ�粌���Q���<�L�aݝ����.&b���P�I��Rg&HE�m9r�3��h�j'�Xa�pj���뮄�!B��%�H$X��Ϡ�hQ̋jɸ� �˗�60�+��$1�s=�45�"�ԯf���`DG�3�o�af�I��Θt�`^"�ƻ�Tn��I��y�Du��_3���4�d>�9��M��~x �)�4��*�S����
�6�̽�	��FH�
"���q����GN_u�q���0e�x�nG+A�ٺ�_�c#q��F�q�7 �R��Ʌ��(������#,��,�P3՘�|�ї�p��%�X�cT̟&�����H��!^P�	���ŏSƊ��թ;��}\cJ��s�a�}9A4�8���L���b9��o�����C�2�)��10 �C�(���)�$3���Q��;L�8Ծ2�X�)p»���L����~�Nq/�NT��a���>��X��R���Z1�nce�iOm	LΪ��/*W�?W+�~GOU�1C`�i̞��`e��������Q,�%|a���K��ʱ��o���t�{�:Յ���6��"LE����ӻ�#)aP{�$�e�Ҡ<
ȣ����k��0��H�\L�mG����q(��Q�i�X�) ��{5]/�w�&#?�	�+��@v���gتc�2*�s�y}n��eӍ�N-�U�3XI<+Y�c�x�Lɽ�0,��7�8>.yJi��8ϲ<eu�G����]��f7H�r�v�w���1蹺Q�3M٨)]굱X2י 掍!E6i��q�f4%?���ߓYVau샎�U�n�=6U����1��I⻠J�1��r���X)X:�iY�T�pd��:�`��1��ìO���f6ǎ7:
WOM_�KM������;�+��ee�(]�hL�l�.HF�2�������	%�c��L`7������#�F`��{��Wj���1�w�l���h� ћ�j�^9ז*�$�-�kn2�����lt�0=1�>H�bT�AG��XQ���ֻc�i!����)!��-t��~>)5Ҙ���J ڣw��ęA�9-x�N+� ��x���fs�{�f�d��Z���=����'�J���lg������5_���y�i��Ax�c�FF��a)�p�I�?>2�s�{8&Q}�5���i��P�Sy����0�٧�)� m�V��l�����0i����7�f���M*��T:o�hf|�B���_�a���}�(k1r?Y�#)�$�0���ƚ��qL��K���з߹>��b�E�y�DìyY��Z"Ĉ���^b	Y؏�26�HO���p�HW��HW��'<\R�1�g��$5��:pu���|���6.���K��*J�WH��㘭J踤X�1�3�����30fx҈�K]�q+�N�,��
�-G�GU%A8�𥝽�$5�Һ�@U�r4s�\g���3h���@�7����)���35�Er�v��m������5]����I����'��'j~!.�M�o�l���q&˚p��q$R�_��0��>���:*W�ˑ
X&�V�aV��Wr{v�)�}����~#ʄ{�iΎ��\� ��m���9���ml���޿L�o����LE���wݑc����F�YN�Fd(ٶi�nST�o�\�|�G����B�Dd�h���B��AX���U�uD�fh��n1v�c��D�GA�8��≦k�vP	���!�U+�+"���e�x"���{�)�EV�l��(�t�S7�l�G.��Y�������#`^���Kٴt����P�arNO����?��#�ǣP����f�x:�F��f�Tx�)Es�oo2�s@��y��t	@������,�F���m��G5��Σ�9]6r3�}.��8b��B��qͰ�o�"b�4�D��0���Vԭ�	,V��O@�j�h�Q$��5\�����KL�PY�� n�n{�ԗ�哉KY�q ����
�0>wҖ��~�0�����&|���P���֞�ذ	ow�zw��/cq�
�I�ۿQ�f{�P�B���W��j��z�C�������
_��!�g\��U���]��LZ��/𒺎�-"-"�ᶉ6˼��H��H��=&��*���+$�+Sv��r9��d���Ͷ+#��']7m�o_k���tD������ N$�19�&#���{Aچp�a�/���EF�s�1S�aO̗Nd�bxy�Q8�j`�o� S�N3��tG]Z�Y��ϣ\F��R}�e|%aY����R�����K�Y�0+Y�,;9��8+I����ogձ�$�:V�V���G�)��4L}H>�0���'髈�^d���ƨ׎��jY��̏)aS_������3���Ca\��R`T�b|A0�Ǐ)�?�P����AzO�����޴��=���j͇1�Kʕ�n���:j����)v}��.�Z0ˑ�8�/s��T�4��� ��G�o,�:|�tC�-h��w�Ag���^\�K.�g�6L�L׳�Qn��f�fL�$b2�)�S��<'ǰ�+d�eiq���gwA�+r��?m�G��m�m��E�ƍ��JZS�|�)Jp�_��1��C��<R�Lb�J�8�H�Q��,�c�)O�$!��Ω�@C��Áj����%E���¯���vPÊ�J�<� �2P�2�F�o��7��&�y��YU�U��-�s�?y^a����2f���� ��RA�&!��
Ӱ�C������Z�fy�A@|�q9�ش[,l8"��q^��o�p~3;����t7.��_���y/>=W�d}敀'X��9ģ `�!�|6�x�[�"�sA��3��_�����	!%ɣ����W��H�=��S�`��9�IF�&�l&Y5��K|�6��;��ݷ����7�N.�r�x�jX�aB��B����	{� ��d�l��4���
���q�2e����V�N/�J��L�;��f���|�CS��x�
OtY�IA%���L�TI���W��X�Bx��Cqy�{�+7���{}֧�������t�Q�*�,�^�T�'Z)��W��@O1^3�x�=.�jZI�&�{*�p�����=v�7��,��o~�<���r��$lR-k���}��k��D�    ,_J�$����Ք$YB�E�Ib�ށ^����~桏���M���q5��Rc�Y�0�ӒR�����N�8W����G.OY���UT�$�lu{9�����~Z��	�CG��Z˸�E�N*�D�*�De�Ol�X<�7x
,\��s�����Yb�����6����w�-�;��v�tc&��6�&�$a�Qw�c]�I���J3��Ѱ��'nz��D.'�����?��].l����[�_&3k��z8Gn������4r�姵x#��[I�ɘ�N�c�8�ӄ+��[�M�L7k�s�N�qͼ��Q׺W~�	�ְxV�U)�:2��Â����	�Z�|oKe%9���sm�
�ʱ��j��Cv�\��<8r}�]�i�p�����a���m߸���$�t�j��4�M��.��T��i�$� ������� ��mW�h�ō)GAF��dԬ���O�No�A�(:0o�ߙ�qX��Y���^��o列�~մ�p�d�y���0Ja���=��X*v��<�R�4��-��yC���^�t��h�2c���[���Ff3k�xP	)H9F*�}�}KJ��X۞���`�{uJe��#����^]w;r[�Mxj�����w�{c�-;����K�`hI�?��l1&��������ޢ��~}_�A�G�=-Y׮�]S�z���%xa$�gi��o�ǆ����L� ���L����P<�t.k�]v������L��7[�%�ЮƮ����PPL�Yi'(�c�~�x��)�M��(w�#�σ�Rv༽nd����穋�@)^桊�,����6�zm��ɢ[�ߺ��!����K%Q��ό�fGѱ1E��:�ju2��ZY)���a2W���qN
˔B��ʗ"� N$,�p��Q�r�\�`HN��	l������Ւu_sev
Al��Ϝ��Km[�,(x�5i�ș�X��D�)�T�h`�z���9�����AG�S�&�6��	���5HpΉl��e��T��-D�����q͠*w�lM�¿]r8��v�Q�:w�c��yS�2�Բxy�Y��W��6�*H�&��a�����w#ŏ��EG�<��2$�G�X{1Oc6EJu���R�a��?��BVf��Lmi��"�l�����e��i�x���EM
�o"8�Q�ڷ�漖d�m�w�D3������S���u�kak�ԉ&@�nl��Ç��Γ!�A0BMOQ`�"�L���#L�or��5�_� ��4�^���R��_Ƭ� -�ߟ�ɃES��B�i�Wv��-|�7�o^x5IQ�!�?xT�ѶJ�o���8ȣ��L	�5�I�[ױqa�+2Gǰ�I��<�at-ж���=�2�
�KZ�<
���ZR�}E�Ā��e���`i��H}��j���E�]2��.��]���;��A�㤇�9�o��ǣXNN�|7����	�+��8�:�?r�t���5�QƅhAE>��'N��Tj|��'�����D����ܫ�zn�hze������ML�^���%i�!誼dg�������� �B1�(BE�0<��4)X���_Ё�г��z>���v�U6G�����*NQ;�([������� �����	�Iٙͣp��7����[Xg�����UR�duu7\)�����cr�-��Υ�<*2�Td&J�i\7�P�C��������eő��جh����-V۩& b�I��JL�VY�P#�}����x�O;��|]�|)�M(m�����'��5%���y·l��uȒgr�,�����^��(j�H��S�����@�p��e�������$>���#IX���^G��
�w$�������(K�t��,��#oƯ���Q¥w�&}��c
;k�O�n!,��A�q|0tz�u.F%�ki��E.B�7����Gh�ћ�J��%��6O���o�;՘L.G;p��I����"R�4{	�1m>Nv���n�H���v���4���sU+d5��������h�\��٨��@"�Fa�ր@2��k_�PyP�����r#��N����M[���1܆&�j���GQG�`X°�/�}s�GZ ɳ�w��m��a�N�"Iy33^3��l)�[I�����=��ki�ù��������]0&�+�^e��4,$�w�]i2��"�?�6�B2������pI����(5��fݞ���J��&�G�s����5q�hG�����A�B���l���	�Y�	<��g�aq���JN,�"���X��Z�Iխ���x3�L~���?ab�b�,LCd��:빘���
���}c{*���+�(���`��~��f?��b|]�6W�����5��}��B���yA�7
���>=���� ��^��^�5�#:1{bG�1:�m�l���!���ǭ��g��ܑ-4ʥW0�,��H�6�S`��o����͉k_F���a��5�i���m����G��O���u�S񺛘iyN�0>С�9�� ׍�B���>�4���t����q�@����j�=��~̬v�J$�O�Y�z�2���li�����4�4�9�yT��=QG����'u>:�c=���#�S��x�����(-r�W�3�맫D��n=RyBU;��qTr>��J��-�aǖ�k�ZRՌ79��r^�JT�(�����M�P)�RQnŇ`�wx�_�8�+*�Q��_���r��a��Ի�t�YfnO����U7d��6�r�(A��yg��_>�B[�#��>�\ �;���4�(2
\Kb�m��g8�m��7~w�X)%�\�!1�+�N��H�rY><�H0��8e+k$=h`ѳJ6r7&c�S����򄤐K���<�=T&�H��L�����AE�Ty�kPI��<�<��K<�5��-��je3�S@��}��a�l�Y�9ٟ1�h&�����DBH���<��y->ģ7%_�F��;yY��4a�vB��!�����Wg�݁ԷHc"/.��;�	h=Yl�IKw���s�4%�mD�/4X�i6�g����Z�^%�ͳ�%��1��Y�٠�j�G~!qX��)ݬ|���&�$4�bQ�@����W�5�$^װ�J݃�'��[�e���'����S��I���[�D)�ڔ��2�N��u)�{$�<�긨GvZ򯁣/4�ݏ�a�3���z�ȟ��i��R�eJ�=�/�@��&Ӌs2x;4 <X����H�oM�p��TF�;�����E=�@?{��:�S۔f���0� �.��o��~ 
�߽�nSL����Q%�w`��ݮÆ0&4�X5�c�9�U�0� ��r&U�Cb@H�r��u��3���!Ô��훅YD��2��T�~��/O~�<;�˥]�'���嘃�3��-��
јD?�7�G�>�1�S��ϥY$�(���I��l�����ꉅ�a�^��������������ʰ"�b�a�E5��c�;>BzF�G�T�DJ*��7`e��6��*#���ܴ�G
wybP���4�0�}Y�N�q�A�`T�&��ۻ�Q˜�ʌu�@�9fq��{eʺI�@"�C���w��+�Le�9�>_��Dg��`�R�c������D��ʬ��+U���<޿i8�װ0�8L[�X�P�Ą�:c&��% �X����վ���t
^M��� �,K�P�Q�e���[��r���/w�tl-�a%ۭ�l�l�G>u���Ǆ՜���5�X�F�}p�_���=ˎ�´l}���S�hHxP�J����F�[��r����
��bp�����9��d�|��(wjh��C��Z]3t8��<��aw��� ��	Kr��E�30�57�����#nm���e���A���M:����y3�O�
��v�QHy�G����	lO6�-O�`�/����,��&`��mXx%`�:�`�j�:�G�~%�4,%��|(g��Xd��[��Nڢ E^pY����=Å��Tym�R\�T�/�����:>��T��":mb�٨~g����K    ^-�yԗC�Y�	s����A�=Ȑ��c�$ia�)M�f��{�-|w����	��p��.TͳF�f��)Y��"���g��M
�&��S�)�'YN�@2�0:l�҂�0�׌���k5X�62�4��̴�y	�ɷ����<�9x<���wB�w9�_F1��X���^�)��W�'�Y
MozP��v��ы=s�hR*L/U�)<sQ~�s�V x������6jˇ�S<������\;Ւ!>u�m)���JP��Q���4\�1.�r|����XV�M�6o<��@n�BdN1���;#�G�I<RF*YjN��*��A���bW���Y��H;P�EQ����s;�c���mp�wx�U��E����?�w����ߡ�Z�����#�P��GK�hUd&�������De�O��g�ZCQ��O����S��B�S.R8�U�^��`e��Y�w����dKS����&�e���yuըDɎ�����T&$a
�WXT�#z�(���-�� �2(=����[�ҍ��̀R�W(I�X�p&#ٺjp��������pjyp�c�g�/E���8�\����6��ۚ�#L�5>o�=5�M;[U'tpL�d9I�L�ҿ��Gڋ��1�7���T�)-e�v��/X���O$��5�����,U�R??�����f%[���.�g��Zs��U���c�F�a}���K���%-�x�`	�pyA���%��s���ۄ�%��8��(� I��"����x��t�GT~;�"g��r����˵0-ќ��H�JǪఙ��#�23��oQ×%~ʣD<�؆���`<��th�<RpK�˖���0_|�
sINQ�𼱳{�{�W�"<�"�@U�wx
�����;@E>�tA�D�,��Jd�k��R	+�@��݄��S�0}�at�}G�.��8�9�P�S�	�,�GK:���p���I�^t��fW�}�Dϼ2�И���v�ر�ŮO=ɳ0���e��vm����f�bm�Vr�%K7��NH�sܹJ$+:�Ԃ[�s�Q<��-�����Ex�m���g޲�/�D�.�ju��!�R��;/^��T������a�j7�9��jt�$�D���J����8܀8asZ���S�0I���2�5a?u���`�{�������<qq%ўS׹�N���`�Lv��դ�r.�"���#^�F2�\ἍIV��_�E+�/�{�[�Y��d�9;�ms@"=��8���۟aO	_Zě���K��7�R��{m-
�o���?�X�ONMEΉ4���7)���sc�ə'�� V��^r��Cs�E9��YJ7
ֺ���߫��*?ڽ�EH!E��3��(���K�gc�$a�Z�0�vNͦC/�Ɗ��YXp�d҃���=$إ>����$��,�@����#��O�ޟIq'nO��.��(���3%���^y��
j�_׽�Ub�4�i��^��,&�I�G���<a�(��9�r���a4A?�J0�H�Z>j@�:9���;��J`Vm��| �Z�H ����F�JL(@F���m/����°zk�wt�quna>]4u�4�p�7D��E2�v��(��|D���>����Y�~yT��vw����f�%�-ܨ�S0���Y!���j]���|���Jo:G8-�6%��*t�{��62m.��#�����K���,���7d�^��4I����9��l��,���Mq�"����~�3�'a�ΡFyFF)|�@G���S�ûd��e����?j���*�5��U'Q%��:��J��E�E�'+\�>�y���;�ļ���e����Q�v�3���#a�ZXf6��Qr4�(�]��d�g�4k���v�j4K�4�\��@A��t�����}}
��"Q����k%F�[��c��9��������I9��,��S�
���E_,�0����h��^o�����YЉա��x&1x(��C9�
�D���vN�e�h�)L}�%�fI���Q��o/��[�o��;{t�s�:�{��3T�ԗGP��
rA�Y�#�V���1!��~
/�	<x�sﺁ/rM���֌�1w+IF����h����0L��`̌�/H�� %Or$)�VCW0]�ǳJA�捯W$E�/������#���t��	.l��C/CW����亸J�$щ&0�k�,A�	:8�F�7�1��, 6�y�Q�7�ߵ��9�Jt�'q��P^KӨ���PDD8F��6Ỹ./�@ǭg��(K�*��@�Z��-�P	��ͩ�7��'�8�����P�yƦ� ��W�����>�������i�Q���ZL��*/�� ƓM����M��󥶅��8�x�垨���a�)
nXE#��/x,�N2*V{0T�r������dDj�P�	z�%E��~Q�@��EF�c
̔���_>�#���!�G��R!=]ABF9���Q9���S���L�G�s���U�U?��79~�8�TPf.���]��.
�1\yÚlb��Q���X)�w|�t͐e~a��c"7z����K�/XID�����ƽ�h�����W���nYd"-�L߳���oa)����9���28%��x�N�F���)����1�RH6��υ�]��9���7���c�z�D��id��ֻ�����ya�W�]*S�g�͒V�Z�Q�!��Xj�eb���R<"�{P�أU�Oơ���x[#	ŞrC'{VPJ��w�T�+�.
���*����,WYc{}|��ȝꐂ�!�o@\ǣ�͢R������X��,�o�5�������Ğ-���iߡg5�F;w'K��w�T$�".��<�l��b1̮�Xzasi����z�S�v�1�����n��3<�Kw��
%?������(��󗉌R�gg������Ǥ.i�X�<�`���աy��H��c4L��`�Y6�pt�Nt��G�͛���-M͋�+;�*JA�f>��gn����}����I��Q��f��ܯ7�Bj�n[���rv!��%s����������Tv��ԗ~���ȴS�`�� �ϸˋ
����Аx��!|i���q.�<o��@�ꨮHJ ��T0�FE�[R��A���u�ugWX��9]�l(�P���\F�iam����ڵVB&ީ<���T��J�Dj󴂃#LIP�2��T-`A6�ՎQ�pm��;��D6�G��K��P��x��16L�G�vL>��R��N5�k���7Q
U'(.��2N��H2�@��M���%NN8��^�OB4�U�ÉHĦ��h站ȕ+ÁY�us���u7U���
���J�D�G1��@4���U���D���o�o���2�����Aet�oW ���w�^~κa
[� i�_K���$D���]����8O�P�ﬧ�fq���?��I`�3��Ϧ?[t����b�m}�Q��� ��-��/����Q�3Ef��e\y� �.ִ�2'��z}���gIR\h�X\�/�]��$���S}s�ݱ]߯����C.��=_����֔�Ed������b���r�Bʒ�o�5$��{��^��
��>0��c[�U`�S;6����Y�$6�+��^M��x������ f�)#"Џr�}� ��N�s�qX��.�@�P"�6� ɉ*�AG��ٽa�V����)��sâ��K��A)ʜ� ^��K�M�E���SJtW�F{�4ف���?z�iw�^'�ݙJ���֎퉵�)"[ր'�H�^bz��`:�^~��K���ݢ�+�bnJ��D�??|���Ý_�T�	\b�4p��iAYI)�������#������uQ���dz��3�7�1Z��<&�����5���������8�H0�����-��9��|���G	AW]��U���i*����e�0�Ғ5c5!��~W�c��:6�½aM��~t=�([��e���y���>����'�SM�{�p�wL6��
3������q�~��_C�*9M�Y{|d��?��0�s�	G�^1=1[N�1�%�~��c��H�����5    ���n�����]}n�?6"�|(�Ɣ���#���o�#�|2^��x����{���`a�iQ2
�9m:eh�r��ʫ)�N�C���²Ν�/*3q�����b3��qL��`��I��C�yJ_�R��J�	#�5�����4޵t���Ldn�3���IJTT8��b"S�T7�x�F@�7ܮZ.��)��>�TZ<8z���5�b��Ǜ'�wm���I�Ўl�d��	� $6#�y�	"�@�w�0�s:�$;�e�Q�b��ޮ)��=՛�|�Σh"����AGdD[
��ˬ�,n�N<����)Oj�	�+R#��%��1"C��2��>g�OF���lq����ʍQ�,���0���R��e�כn��w�M�6Q�e%3��vߧO�L�ʍ�{�P��dU��XF�jScT��R�Ư����)b��R�^���SfɯJP��Hlpg�В�d����Q�x�1����$�Qj��xn(����_v���S�k/VytX�d2�,IE>c��~~7�C5q��&��&����Gي�
�z÷�5U)�GK�w�x"f�\K���Z�
�:I���7�w����#��#��F������7����;�-�Ԣ\�n��Ù��+��??�5[���K4jA��e��+��Q+�����e�@Y���<I!����ΦY�Ym�Ϋ
�l,.5>,��E���&m�	�U��``VG2o<r�<���E��
��"�c���d�E[�Tg�Ȃ:Ok�j���+;KKAkY�i�m\��,R�4�������
V��8�c�ۻn�Kc8�Rg�)>JL���A��	0Z��W��GX��ǏS�=#�����ޱe@�ө�#J[��*iŊ��Nr���7�wפ�#�5[���I���cAi̷��\�i�&�a�7��s�h�(�H�у�P&���M-��[�"υ����ɺS���w�s��c��o'QQ�O�Pa��e�����=���;��G�crWر��0,�0&�1�c<��&*+��׃����~5����4[L;���S��(Lg�l�g:�)��
з"����8"���(T���q��.�@���{�ٽ!B�;�'�n�S��O[����ډ�V<�M�fӊE����u��HTPb㰒�L�l���32ŗV#��/V ߄5-��(�32�VDi���(��t-?�[�����wN�_�)�"�Y �`��1Տ��9 �31�>�LR9�p���8S�WAG�֦��"G{��v8��~���l��خ����y�m��/��Vح#���Kϳ0D��q�ۯ�k8qN���K��������;�c5f��L�ݢ@ߤ�b�ithזr!�n��[���,<�3Нٺ�Q���
��X���<9���C��n�6�1����p�B)���ʜ������c���K�P;��#N�-�H���t�Z�	[=�`EELU\���o'1�|���p�p:��7�NI^��gK�#��'���zQ�+�	����䍙�g��5��o��9�V���Y�5ۍ��rH�/_>_ �⃎PܷT����&3�]�8�ڼ�!��Lcn�r7Q������˘p�0#���,}%�>�;y���+������V����������JG#*�Rh&t��99}�*�g�/���&���.�Ǯ���c5���Ƭ�h��FE6W�N#C��\K�,�gfa��������($��j�W��{��f��"u���0�x�׻�]�����O�cU��|/���EwO��+�-��?O%=K��" �\!���W&��ϲˈp[	�S�� �M�@�	�(]F�P�ʇtY2�:�Zo���[\�|zz/!܋�asD5����F�V���-�6<���<�{9.h���w����B��)O*�j��?9׻��"��!��k��{���J⹪��G&:JuT��N�ݳ�O
[c�������^"��������ӿ�>�XѤ$ǅM 4è,r8�S���C��~�cp<
�;ģ(��%Z�H�d����}��#ܸ�+	ي09�/=O�r�.z�s�.Ph�\�(5͇��N=1���IQbA�k����4J�2�[�>�.c��`.�I�ci��8���	H��$�R�T�hl}��A���|����l]�R�&��#tQ�{̸gw�io�v0������j���x�k�P����bqWG����URj.�L8}�`C���A�z��phT!��:JVG��ok�C�f_����ۅ���>;G\�4�\Pf��q�Y4Y��1�Y:H�н�8�@K'��+X����f�x�������;��4�qi�-�� �,`MSbl�w�I�$���܇��I�/��VF���#ڡ���wc�'dx�&�(�t�}���$�]%�|u���Vs��JB��Y���h�c� �s�o2�	|���N'J<��̻���HLY���Ş��H;n����o�唂_4)��E�Y���h���)����ۈX�e��Q������$��c�:����� (Ϯ����=�R؃L�8�h���1j=�Z~��f؛�Yq49���]�c��uJ�	rgQS��c��%���D�qA-�eS�zN�֬�>!�x�8�=���|87�f�Y��Ċ�[�%j�Q+?�*ZT�*UU1_�B��
�v�C��&���c�v,���>�('��0�j�t���O���4l�L.�0���\oPo������f�[�QEUL]��Q�?|�j,(>X}��i�{�O@F&�.��%��U��L^$/AՃ� �G��Q>2�vuaO�w��W�5M*��_�7w�i	����U�i}�-@�oȳR�"�n�z��.���qGx�oN">^�K�����������b'-�7��J��)u�QT�z�FR�*�=���o���/ř�+��F���}�y�8�ԯ��a��Cw�7��tQ_�[��/m:�tQ�µnͯ�0������P��[�������i�Zfx4��4X�������*�a�d�Hgصا�8�S>����
�f힡��c/?Mv��"��l����[�.�WN�R�k���t_��벊݁�.�|Z�������T��l�b|��E�����B<���}�ֲcn��)p�_H.��GJ�=^���f;�����rlm&����m2���6�!�7�۳$��4�Q����-UV1]�m��R�]l*t@���.���r~�5]vL"1��SMy��I��l���#���bv��O}�3I
�Y�Q�m�jX�X���3��ӌR�մh���G���k�1춽DM�V!A�l�\��W���
��j�x<yh�aTb���G=��B�:�uΙ��a��o!k^2$�P�������Yh	�ׇA,���"-�R���_���kA���wi��cD�Ba"�*�(�u�R��u;���Y���\F�_�6����a\�7����x�ɔ�=d�ј�n�(%�y7�f/ٰD�� ����U���x�`E��9�?_�&���{�<��J�cp��0���6X}�Y7O�_.�s ���:��x#�ď���f_?�Je-�ǲ���it�i���������YF�p�^��7]c:�4
)�TF�״�c�w'ح?`+��<6i���8�ٗoޜT�(T/���*]x�&1�J��}���|*Rإ>R����Ə���+�*n��~N�&Ѵ�����p�t��\�څ��=��X9H�$��N�Ā�xaR�N�G��#�".xvb]��o|"�L���L}T�m:ptCZ4�P��H��9%HC�{9���P���iK�F"�/M�Ha�P(MF�
�O��>��Y#����s��N�b��U����A����ᮻ��y#)�Nsp���O"3,˰6Q��n�O�<�����KE�2*Lm�\^º��*~m������k4�W4�Ԝ�]��ܫ@��h�;�G�:��,�:�U��%1*��)��"r�E�����*YRC��ͥ^�A��]H6W��0���IU�Q,8��k�5���azB!?��VZD�j�"���*�)�;4��    �[.���F�K͡M�#����,��AϨ"���
�2�-d���<�3b'�Q��7��P�1����)�9��O:.��$��㴭d���������,�޸����a��#��/i&B�(^u`%ad�G��L�p�v��Ɲܷ �z��i�)�13�sm!�|���2 ����a���E�WY��Ƥ-������GIm���rD�E�����4��C^�����fv�I�:��#V��Wy���(V3i��V���Q0���'wU��/���<^@���4�Y�����5�k�<����/ʤʈlYF�����Kc��L�bI�����[��\�|hM~�,+�"�$*���q�P��r~�9�˰(��|�Q�º�Ѱa�
c�V�o倦�����~n�����x�|6���WeET�
�xo��ddT�FP�O�(#t��b�QȌ��`ޭwu�c{��݊�B��l��ʙv�y��M��CwZ^ں?�?��O�X�ٻS��Q�֜��Y��W����KM�	�&J闠Qh��j��8�	��3���=�/G�$/ҍh�ѺCG !S�+0K�8��Y��d�«�ֻa���4­�Z��ǎ��zbt�IU��8Pk�ؙQ#�j����1��ԡH���w�.�� ��B��xe�%�0�gE�f���C�{�&81���A	i���-�.K�H�r1M����X\eT<P}Jk�İ�Jd�?xD�mT�^PW�b6��k}����{���Y���V���3�ʌ҆����&�����l�����2�<%����u�s�(�a���9d�R���K�̃We=jT$�e�E��O$Ck8r�\���6��@n�.T� �Sұ�8-��%�Q�0�bU���e�`ݒ����Q<�u�qҤHӔN(Aa��'�+ze5No��3/�>[F�W�}R��٢�I�(x�����؞\�]:j8��i��mNؔ��W3nٴ�Nxi��JǻO���
f�����ޖ�ע��R����,m���]��s��#���L�;��Y�Q=�=ҬJ*�YcS+�5�	���٤�����Ĭ���e7�XD6:�Y�mSyb��(�g�ƕ��-�����u���L��~��+��7����]!�RY�Bs��(\&���GϠ_<b��k1���V0��qZ%ɽ���NpyDtW�̰�~W���s�C5o|=:I��ˋ�tu� T�9@a��Kit|宽��{<2Lq�����.�"�L�;.�vD��E&pƅ��Q����zh���D��]�"+�0|t�a����K����9��ճ�A�B�$��(��\
�H���X�`l`��o|�)5)FO�����1���,qH��)�墹�,,�u,���t2��۷_��O��,�� Af�͙EiJ�����r�9�;W����iEE��%�ž�c�R�
c��
��N�WT0z�9����{4��$�����oؽ,7�}�R�d/{�X:fs�Y"�:En�Y}�\�_��"�O�9}跩��|���0�s�e2�L#T��F'^��f�U�s���吠���w�v�z�p��C�Ȯ,�"�{T�X�a�6�]�)ܥR��b���_V�K:�"���X�{P���۝��$��>�ݭB̐�%�mTƮ�l����F���;�f(��B�9�-2�9���]�_߮9y�{�鹄E�~�̌�^u�7~ً���eUF!5cWH�/1�<�Tb��m���o��A�Y�%N��*��`�B����U/w
�x��C�묫�Uϵ՝ՈQ�".���X����fƊ�U+?��m�m�ft�+�=�]7\���T-g�O^�t�v|�ƚ��'�F�"^:Ef��W��;�3�����7��Bn՜��dT����Է�s��s�C��;8&�Z����c���9����p&��6��̱�u�z�o#A�\��=��$M[z���}w�<R�%'?4�#z&t9cKw�/�$�3}sn]W�#�B�����KJe�r0�1՝Q��~6��P�d*��!�[3X�z��oDF�
]r=�c8�����v��P�ɪ�C3���PI�����F����c�P ����9���`�D,o��ǤTU؇�w.�*�z����h�Ċ�&>Ӡ뛥�6/�ԏ�xOO/LC�/�oU$$C2[���F���8d0�nC6�ky�Q^F�#��]����~�R�}Gk�kK9&џ=���(��Zd���4]���`yxs%5EuT���{����tE�'"q�Ø]jc����$	�<�>3���L2j�q]U��)�x$�@�wc��E�O�l�5žhU��!Yy�n���և�� J��1���T*#8l��.�w�t}�i���y}2��W�G����>����c�`3�0�s�[��*�ÉG�0cd�	�\���0%�UD	��,,�vε&q˥���b�K�f���C�7l 
n0F��>��U-5��E�[槾rB�Rps��j�f��E�M�Qܒ*l��������f�on�����ǔ��Rq�RF���2�A�~��3m�I{��ȩ�+����ʻА'�q^Qz�²<�c��>��7^��
�����2�� ��)x&p]�&�i���I)7,_��aFu
��A���-��y���WÏ��%��(\Q��Ǽ�<�qm��fa'J鉬Ub�}�#�� 	��_v ���r�+�!Y����P-��8������Ҕ�HdT��>��G_��(I�L\/UN��O�V��@��d@ԔYF��
�A��p��e��}�C�ǣ�Z���DF�I=�/�{L�<QU$,��s������ږܸ��3���9�˓���(�R��ҵ�<ĉV�S%�*N\�ߧO7h���Iޠ��u��it7N�^"��
��a��/Xp�\���Ä�K����B^J͝܈\�\s
�6o�����rC�)�p��A-im]���53��u|�n�W�9I�]���"z縺�6��fM��@ٜ�l�^�%�'��4,-�Z��+�-z�<���E�6�Ӆ�m�i$>���`Jo�����2/˲E՚�jM+����=�~_��U3�5RX5V
�ގ˸��=��-����dy��U�tS��i�h���X`�6n�<"[�g����~l���",X_u���}r���>2Wn�߼��u���P�-�=Xc��j�z��#R �,YF����eN�9S:����t�\�����d�;.q�<�-*���	NӠ��K�ㅲ��l��d?�&- �$��{Ξ�#���z��K������ӳ�4���^�����~�$#�yMBV"�bz�uk&�#�~��掍�_Êm�*6v�B/�Nf%ȉ�x�]ރS^HH4~�Q����"�a�:�XE璻7!��������{h_y7j`��P`��.��� ��z��1�����K�e�b��G.~
ɲJ�J�Nܞ�#���ܤCH(���s�n��L�_H6-�N�Jbӧ�v9�dQ�0��=*�Z^����-�7�;��:Xߘ�֓S�ڜj$5�3�\ⴚ��q��\���{�G��?]�q+��.K��>Tʹ,�a�?����P�??�����^C���0�wѝ|P͠?z�-��T`���J�iAi3��Ғ?��	~?;�V�2�W��y��SH*�҉�{`��������Ur��@�V��;��C|̃2��TJ�B.Σ+�n�snN'T�x�����.�0��X��Z�A��y�Ro��XՓ���y�=t(������*e���@����(_�s�Y�Vo�&�i!�
��o�~�(ޅ���q=���@z�y�b�I�BK��Wk��訊.q��R�����{���x��R�oӐUfJ��
ϖ�N�VuM��f�G���
����t���>h��QD(���v�j�u�.�*m�Qk��4����J<{FM��z>�@���Ǐ�[�v#����K�����v��51�Aڈ�B�78�o���n;��|�m�ˢf*��Β|�>GAz�qZr��Ꝗ�� ;d�
�s�ժ�RG�mn����qɽ<[����V�����V�kU���q�:�_�fq4{�Y��0� �  Cʦ�:��Q��ٸ:�����Qp�	&Z�Uהu%*d�Lu����қמ���9	�I12�/�%���gW �9o D�˦��hX�o>0e�*J�{�,;�ƾ�x����s>��^P:���$	5����8����0����t�V[�w=yi�L���^\�{�ʤEC�b߱=�W��<�Z��j�*���M[LJ!���%���3�U b��Y�:jc��ɜ��~�i�x:�}�9�"_T���<���2�ɧ�$i�N-Єt���/U�:�߿l�oF�BYT��^|lA��w��UVٺt�%�tN��\ESW(%����\��b6J����gZW������Ww��DQl��*��I(t�0���U�q�~������H)<��x���͝x:yL	�0É�N'7�����w\_]���G"�xB���<�%���lG���!a���i��c��R�{���@_ �u�buD�-g%蝇�y���콅D���p���@�US�m�װF
ň�<��v�p�@ ���	��U脈�y��<u��郧�T���c�������[n!=r�asz��e<���{��o���堜����sx��Ư��� �8�&b��S��(��v.�h�7�7��H�l�j���},��VA�ꪫ�wXn��Z���Q�0���J`�/�����j�p} �ލ�J��ϔ�dS��_��+���je7��yh��^����%�q5.y�m�݁K�8�(�2��c�=M^�ob��(_��D��F��������� �W!SjX?���[-�k�hD�y�
��p`ցck�&�p��������8rHP%�#P�?�&�7���(DI������?4I�����(�	����J�?����٨���"�eS�W���e��
,_Ra|_�$����o�]�CוC�fXaO��ߓ}Ւ��V]݅f����8�������x�F��G�_�� uw��c��UV�ٻk�~�Χ�Ϯ@�;BM��z�/nTqY,Z�����ٟ���75r���ש�o��W�#�A�.< T��0@��2	�����|x7�V=��6�߯Ow�+��teJn�m��)m�@̓��U������+y�BQ�N�2���~�0��V���gт�L�_����;�Q����iA27�u��tm_��`���jsko.q�:�D��o+�-_��%�+�Vu��C�Ǒ-������ru�}�.��;�֓i��y<K��i���
:N����R���%��2:��A�.<2�
��?�@��P��A��t�,�-k(���T���j%�j$��Mj$�������3��LjX���"�
:��q����%-4B�O�-m˪�,7rR�R��ʆ�j��WR�^�=��^����YW���?zbu%���%�\Y��GN����p��At��t������#�>�3�Ǣ��K}w��z�<-�/0_��{���$+����S��0̌������*���N:O�h� a��h�y��L�� �u�L��a�R��fR�J��[�#46���5�ك���ڦ��"_����g
oP��.�0���V���St�X��\�ߪ�t5��(�P�>�f{�׬���(�I���@��ZvEߢ��EG��f}��h=�ct�C�W^�[�6ِ�&vwoT����e���%(i��Ҡ�\W� ���ݬ�}�ዷ�RG�~̮��u������W����UU|#�{ΡZ�fo?G$��,�g�ۭ�����+�:�>#yڭܯ�{*������%��\	����H�G	l=�&�ZTL&��?��ƃ���?�������R<%7��G�_��������re=��F�Q��ۨ�c�4��u�U�) ۘ�T��@���&ʌ�aW�=�/ݨ�	_
�"(!I�ہ�bf7�ܹ�w��r�Z�����ɟ�o$<a�獡�y^O��]n߀H{�=ݮ������\���p��U��ź"<J?6C����r��-Q���
�|�?�q��l�|�<ʂ��N�5�� �9�a�[ƥ���Xf�7�Q������1�i
��>���M�L��-<�Zk%������(_ǎ;T�G��2Ƈ;��f}E��RwH�"�k�x�Z��,<Sr�ϫK5��N(0%8�L��M�X,UF,y$
X��A�Ŋ���`�S���N��)1��Q�q���ҿ~��ŋ�[��      	   �   x�]�Aj�0E��)�l.��쥚xR';)�������vJ��`���>����9��a�֜jM7��:IC���Zu�Uqp<;�d�Ag����_fk��v��oʶH�1�/�eA)����nrD�2[�����O��G�H m��\�b�}��"�c껮�_�>(DK��?�\��\�)=�g�^�1��?      	      x��}i��ȕ�g�Wd���D�K��D� �D\<U�"�T��ji�x�n�=���5q������ـ�t�2�y�s��8-â��B��E}��7ۇ�b����n���k��v�yr�7a0;v�cW��լ:ջfU���Y6���-�}�������k�U�8[���Tu3�����T�8�g�$̊2ς"���b���v�̾>���O��������~����|�ea���G�n��1[�)̓8�B����ٶ:vuW�O��z��ÿZ-������$ ǭj�o6���wWw�j���fS�M?d�iv�L>
�پ�9��}b�a��2)����h?�_>|O���L���x
�b��S��e��u���]���X?��x���Zg�߄�f�ڝ�L�:���+����
�W$�x�Aqi��2�9F	�<�p�
��㯟���o�~�������;g��8���_DQ��d���-�];_ϷU������6�	��~���_ܚ�l ��̊(�咿���__ϖ'SY���nE���ȲGF4�C��bj�7����T�g��L�MP����MYm���r�J%�Q����_^�g&EQ�i��`?�?�O�8b��4^��[��I�������Y.�z7��ok�V����y��+�n����ƽxj����|�Y*�}�Q��?f������|�|�̛gCSIa*� t}NE��9���/��級W��֙?��&�9DzT6u����5���'f��[];>8ҋ{6
�E��Q����'��OS�f����y��!N4
8��^�Q>��x���-�b\Wm�M3�p3����MWo�}���c�^\�XN� O28+B�������G����j>���q&p��t��rlv�����mۭ��~'z�7]�5w�fY�U��A�C��T��_=�C�����}�o]��U��~x�?�(��L�,��t	=Vs��9���V�Y�A3��yC���qAy̺�w`5���a��5���-}!�2gw���Wpw�.h��~S-/�~���E��õ�c�X �������������;���q�� ��g,�t������mk
��]�ہ��Gj����3�P/a]]�����������i��5�n�����&��<夤)Ge�D~/�����_G��!;�����ō��ȃ���`q�E�ƽ���`��j�w�j{����t���7u�YT�t�<�+�T6r2�q��U�M�;4���M��M���[��˭���ut�3f��c����<
3:xuκv�5k<���ͱ�;��9\ހ�`�p��r�U�e�_�����i�۹��Wo�Co���.N���}��.�{3���1�����	'�U�n�����8;F�B 5�w3�U��j���8Y������˻l7�v�Tn��p��iPʔ�/�C|>l�Q^�X�����>����l�O`�Nl�<����#8k�U����y����V���1~�p��e�I`Z�]��7(�95p
�h����ٹݑ��Kk��\�e��O�����a�n����p�E|��.�M���z?����l�b����
NѾ���	H�E�v���r���ٗ6(9Xx�fId|��~R��O��m�����ǴzQ�����݂��_�t�N��$�Sd��-�4y�����}�5�CW5��#�����|ڄb�'q�FB�s��7(�%����P�S�4r�=��'����0�?�a� �������Y�F:թÔ�Q8��)x���H.Z~d��G�EtJ�5i�x��$�΍P�S�'caI����Y��?���حZ��E����T<U][\�]���;vn�i�Hw�L�B,����iG*�7�~)�~�O?~��4&gDp�[7��_����F�
yT�VM��f������U+�����n�w���l��ռ�˕�����o >�,O+�P�b��������1J~L3�"���ڮ���]����'d���/����gW��>���&n��
`��YI+�X�?����N���WN6����� �>�}~��;������+��5�?� o~��p/Vp	��X���r�F2����F,7a� ��"�5>|07!��=<��-�����F�w!��-�֦A��A�8%y�"<QCr���y���� W�o72�js�m�*߅.�',A�=�� ۆ�ʧ/f��''
|^40�����G�����櫖�e�%�Ob��N#
��/�j￶@��X-Wp��C}|���<�g1'@�������z>��ۑ��D�|�v*���[k�.ǟ�N��`"���쫌r����l;pO14��&^<���ag-�%��n���#\�9�R�'�"N��:J��~���c~qb��>�x�dI?�,��m�}E^9Y�@?��m ��L`~�=0c�~~�ʡm~r�yNR�E�N�������-H�`|�,1�l��/nh�=�s;�p�eϒ��[�鋷�˓���*�"M�LW��B��_M5d{�~�C��u�Q0[W|�xK��^Y��ȍ�����Yf�;J̖�qU��W01�<�0lV�qH�C�mQ��G��.^��\���0�6�IG%8(�Wv��n���m���/v��;B<�о�� -&��aIN�Ke�`%�5���6"�݌��A��偖NR�i��d�bߞ�l:]8�����A�<��<���l�u�j��2oqWKp ������j0`yP�����n��ԏ��)��;����U" �����( �ܣ���S����G6����Oߴ{�,�"Ʋ�BYT��(:����/���*fe	�x��dO��Sn>Hol� �yA�f�<�u����5\4��pp�=|����'���t��M��[l�%^~��K�3��n�ü�R�K�����#���uX�����pg1�M
��(�����pɮ�[R|G��R3ai����yS�6���JSm��e'1�����w3\w����v=^���/�Od9؅���ou<}A�&X�)�tT��{؞�n�jCAS-�l5�#��0Ieƴ?���&r>|�r����E��eJ��2ٜ�M��!�u����ڈ�8��y������7�Y�&�� \�%�N��7x��Fܭ~��A6�{�-�R9wq���� �`�����-�c¡�  !w+7D.O�F��Y�1�!�n�e_�2�4���ɧ�2�̼Ţ��e���;�+=}.NV�:y��'����~��_��8X6#��<g�~"��:*fz��1����+��h�eB��l�a%�~����Mvy���3�C2�*(s}�幔��������YP�\F%@�n^����T��K��\8��X�9i���h�2
$3t9���8	�
��R|�|�Xْ�ZD�*7B�O)V���K<J�{�*X�M݁� G����� ��0p�r$�b��� ���BĆ��x+N�J���n�����v��Q�����3�^1��11�*U�n�T�򹩜q)����)Q=?V�2E�pw��S���3�S�(�T;8�n���]]�@Ο��]��j,���I�B�@e )'!	�G���9Sh�?�pbP�ڜ:*gUw��L���y�ۮ��(�Qj0�����I��8�(._
�X�{!���"~��K
"Z'V����i�a��	�Ly���L7��5:!��u޲�0b���敏#X]	�ږ`�-!��v�,��� �Y���B���tn �?�&q |���"q���⧉6�[�]JK��hb����;Iap%1,*39K�P2S&3EDaf�sp������A	��2�g��cm��w��SN��	�ME�� 9���xG���A.(̭��9?�o��D�n�����`s�Y�77��Y�?Ғ�q�X�}�P�8�n�*گ���wy�Us��jW���e|�]a��J3��������~>�P�!�)���W? �K\F%Y���޵������Ԭ�u�ᦙ��dŃ峡T���>J!l�(���C[?@�b    ���g�����scp�vi����(%k.��z�>���:O�����ӄ\�cw����*+3��6��Eq���齣�2�z�	D�v�(I�4�߁x�r5����_4�q� �~����.�s�Jc�i/�ص�-���|���Ǳ_�N,XoQ��P�y�qTX9Q0ayWc^�'����~�8d�;�y��As�r�#�E�R����>L<9S7�#&	`߄����oO����F�eN��K���a^2�Ghn�`��ᎉq�\B�����
<5�[{�¥�#w9&q��L	���~�y�hƕ��i-R~
���4���&�M{O�Ɂ
�.��29I����0���.!'f� $��Յ�Z(��e�`8=��̥���Q@rM�2�X(�2ζ��
�m�?���� Zp���7�f�~�5�Pb�e�R0FA����O���{pC��|� �������s����^)0q�#�����;3�5��͋�>�W���O��H��V�f�?}�E"'M�ң�Lb0%�4e����2Ma{>�w�f��3=j�X_� �������j�%�ř"%k�	s��qѿ�^����Gg���6��W���1�`bϗ5�B���0���Y	��tݒ#�y�b�W��e��cضO�z|lS��!ZB��N�Ď[j7��ݮ��14̌�v��բ�$�5Fpx����s,/,a�A�����$����_?�0�
nK���'=D4ϒ$��=�;a�FN#|�x�##�l�>���{H21�H,�����5Լ,�2�%�G��i����z�&�Mg�a��C�¨�Պ1�H2� �B�gHS�g��yɅ��k��Aʧ�@4��Y��`������L�M0d�#4Bw�Y$m��4�p{�L{~&�l�W�7�]�C.`��x��������*e�qr��'�Ha~����,2�y���
ON7o+����Rf)ja:{h�-���o���e��۰�˱Y՚���x_M�ݳ���p���NEpЇ$��3ʚnS����L~A�@i�N���|����>E���J*8�Ik����4n�����j1'#�	x`fiL	Mc���Fǉ��sb��?�OvBI%�u%`�/��'��
�m�SQТ�A&�j��Zr ����/��?�����M��fQ`hSA����ˇ��<�*�pK\����*�,��
s�Õ���k��
��=R����J���,�n
W��'���8���8"
�����G�n�1�~�\����%x��)|HpM�_��j����4d��D*���d�:�+��!{A9�YSL�*��5,g�/_!�<���t��k��j��sB�� ���jJ�v������RI�i0��P	���_��Xa��<�޺18}A&JV$z�:���o���L���s�&����բ}��|���D11Z�)��/�<�w]�<&�y:���~�p����,�`�ä�(l4"h�[�O(��]����؍!&o=B����L|=�s��I�G0��];�e����Z!!��-�L�����;��i�G"+rf��%V�bvq9���֦�1���*,�� ��*��ņ[���#�hO��)�?���%���0L����(s�����	8�������)rX2�������ߤ��*an�,l���"�
Z�:�Q�T�-��n��c̰Y��{����r�_(<��w�>e�2���I�!B���oS�F={��
z�C.i%u�Q��=��t�mej�B��r����,
_�s])��)K�B�3��1��տ�RO���m�(Ԍ�m.h!�:Z�K_Lukx��7r���S��aH����
G�ʋ�<C�[������s��M��)�W���i�� �+ʅ1��v�ר$�%��,1B�}��%ؕQ�Y��?�av��O�Q?!U�Y��X����N:�g�\��� G��sM�YS"}1�G��m��"���/�LJ�(��,B��`�q���06���%�����X�0�9�TVO���AC�d�O���s�yѡ&����1O7������%�&B��'�T}?@,�iQ�4�(�.��2����m�d�8��ϖ�;gKz`��ͮ1�%���j����_�)'���M�qJ�݊�r�'�d�1~w�,~y��иL1����-lrʀ-���Z	J?QN֏5ܙ��<�%թ!P��H~b�vь�U�6>��8�X�B6f|ɽ�s j�g�6y��p�c�PFɬ��o�0N������O_+F0@#A��0
�>f 9-��Xnh��\�uW�}���������y��� )vwnX���F"��t�(I���ޡ9�b�m#���@/�d8�w�z��Q�{��޼��?>��˼�,�jJ��'���i�_>>��&j�����*��*�K*s��EW���P}٧���aj=OF��$���UL�0�8��L�i"�TpY�E6�\y��	|L�?�8Q ˚����/����S���ɿ�aɶ�zC8~�����k���1��xAH��
���?��#�,�2�o�̓,M1�(����G$����wՑ��,���Qg�/�zy��Q�����ɗ�9e�����8��r�'ؤL�������
m�ydS��z1n����K(���f~�88%�9^w���V3N��\�o�$�|�87�S��̱x�(�`V!/ U'�H���d�O�x,�y.�+��r�l��B;�M��0�Tc�=&���B��Q	U��w�
�D�R�hW��z%����aʁ4�>��R'-E���G?I8$�B
���Θ�����l'�Kd�\���l0�0"�#Cр+s��f����o��$�H����sP��X�o�z W���}Q<�PL���#��^��h���������qѦzg?�`�Csܿ��L�Oz�����>փeR�-�}�'�4i�C�����
�)D��O�?�`9C���J��D�IG���"K���_L���9r=:,$^�>l��$b^Y�ڟ��P��w3���d�j�,��*ˤ�҈r54
g�V�%1f�do�n����޵��`�T>@����-�}��m��S>���QR�Q�j��3�����Ջ�h�GJ3�:� .����2*f�uG�z�z�h�G�%�9h���7}�g��^�Z!&�Y�����D6i�a$NEi��A��x�4f%���i�2J�J�W������T��	���0�O�|���`��9<�U`�D>"�q���"��)�9)1�z��p����g��)��gTJ�2i?�콐d	�im㓥g����`*���֔o�s��Aܸ�,�Rز�4b%t��ߓ�����\w�6����67*G�[�mQL{k2�:�C�U��]��5Nq�Ղɰ�z:�I8��ܧ��I0=�|��7c8�{B	�{����K��ZJ9Vz9��C�G�L&�
sk�T*���:a���ϐ� ����<���n�E��� ��cqw�6�/1ӹH2����5�!�~��j�o� �ߐ�NQ������R[���b8��m���x�J��B�s��k�ň���^�h�&Z��4K�d������0+��栐����G��������{v��o�UmxQ�7oL�?��c7V*)"��l�q�,�ߧ`�=�ܿ�O5��tT��_����� �F�a)�a�`�b���>������Z�����TCR��~�Ș�_'SV%D�n��P��މ����2*�����T����%�1�A҃.�Ws�����<Z:���j���>�1H�%���l熀���JF٬�\$|f���v²وH�]�./+	M���79W;i�Ju��`}�j0VS2�Y���60Uq�S(uT�����Ȩ�U�\���=Y��wh��E�'�4*�~[����.l�@g�W��	Zq	���b���ogS�j��_)V�eI!�x0�T����ے6Z��_[h�T�3$W�Td��V�'���+��ݶ?���vK�a�    ��`K�$�'� ^����%��[,N�4����잓1Q��N@�.k�`�+��
�s[���)��,��/�� ���Q`BPo���%���B��r4Q��a��r�}=���u̘�CNo����1~s)��p�7�#<�V����q}�Y6+Y����=�<CV�ϫ"�qW?��T1��t��)�@i<�u�Lp�w�RiP�H�>�(��[X���i�ܵ$t���z���]�eBۍ����a��˛O>��>����aJ�m?A�q���Vk� R�%�vwt�?ZrwJRA<�k���j��_��
�Ҟ'��#s�;7�ɏ)��-eex�~}����R�YIrz��������Ui6����5��kk̑0#��yz�~�d�ۇ	�]҈S��S0�~?��m2
f�-�t)�71��э��@�|���ȅ�He�G���p�#�L���	�l�a(tR����c9wEr:b�	'Z�NW�^�F/��ʘ��U�����1j��N���Z8���MA�S�1�/%�(����M���b{��+
�ïf�Š���_��y�(
�����p��3�/�S�x��c"�,�=��utJNw�,N~$�B��`S=���e$`���ed�Ukݬ������,A��Ǆ����&�����!��ȢA�\���H���-�+&��[�d�C�<z�)��p�ڒv�[4�{nI�uO�f�rJ��3�T�/�����?�Jɰ�M�cqev�$�%�v�ܔ,|:��<ò������u�XA�]r���L&c���C��D�,!n�����F����䥾�G /��^c��~�b�&�B�mIt[���,>��z�g,�31q�o��`����|Á�a��R��HcӦ�r_/�u�qJ-����5x(�C�	�����?>��O�6n�A�C
�DI�!x�b��[<7�G��Eq�>�T��o�s���躻̛ҠL��	�)(s��:J�!J���(�1�ʤQ��3��z��1���z-�,���(����f%y�A{�ͳ�J6�!)L+��~4�n�[0qRH뤣|��QT���H��֨�'~{�IdKv��Pv�׾��,pv��q!�r���d��D����z^�1F�g�7�Ⲝ�A���еNo�PJ1�h�}X�}�P��_�p%A�LP����*�|=K����,�� Y�4Zd���|N�-M�.Aށ��C����E�[%\8L�X410�QhB�_����a*���P(8}Dnx�9�x�ȢcV�Dˮ���W��j#�Ĭń�~�r������_��Z�X4�9̢wN��V��f�)��c�}��)�W}O�ћJ\�?�ds�������r��%JF���	��߆����\5Ծ����
)�����ᮦ�D�}�
��4�܊U����]��#�D��K���q�q��u�{��-��i1��I��8�f�������x|���5FIP>[���W�o1�/��h��@H�Q��_��| �p�͝��1F�!�~
�Tܨ�)%-�`���\�d
xr70�j������(6+���фR�*VT��X�!��$�s
��(�-0�����k��mEEJ1M8&���4ꂓtb�����ЛKMp/�W�)��I�>�M�ܷ���f7���)��:�q�(�0�3[��mv#˿ &!����quJ)��_[2��R�	b5����=��lhe��.V#�/n���)��I'�<o��
��T�;���!r����l����A~j�ӚQP�ZԬ޺�p�^�J��0�ʼ(�o���O�۷��B*��ܭ�9���(*Q��Gq�-o=��!����Ր�����-��6����2gu�5��?h,GJ:E.L<�i�I�~H
sӨ}�<�*a����H�T������L܊m�#mM�tm,o��xy�e�TCl��T4�������3�%8g������3՗G�P���uʏڐ(�6F���$�l�a\U�����d�)^����$�,T�}���?bB=#fJ3��<͹���b��ٷ}�e���̖�qa�HQ��8媂x��Z�^AA���9tM=���ٷ}������$��$�9��x�9��Cax,���@�یg���t*]�N}��,Kta��Mt��b���D�q�#2��'�T�-#���C������;�����\؆�G�y�䨹Õ�W3OH�z� ��o
8_BPl�l�cB7��B��֧��<�I��G��q�:o�{���(v�c,���F!f������F�Y��U"?�u��d�f�j΋$F�:8axI����eݑ�L`��5�C��ά>�+'���J����J� 7����=�'lO� �G,��-U!�(�{ǝ�,i�h����(���=s�����(؁�sEy�d���it�K.�s�>$S1I��(���l��o��j��bdS�����MWu��A)>wD^�4��]���]F�Q�b?�_�ڭ���3��f}�Ҕ:�$MI�p��V{�Z�u�&��A�Q��:oD9��rüO�]uZb��ȇ
�Zޓ����\���Z*QcL,�3�D^�V��Ъ�aH]�<�`��)g�*n�4��WB�bܧ��q�c\�3l�����H��o4�1���16�3Xl ��⢞#�L��"~�����_�d�8�cn��pŮ��X���4�C���Q����R�2lGHy�1�:'+֋��.��e�Uti�c�5v�RO�`��?�<W x�,R}H,�(�=#�:��|�qd�A�s,�f�{<�=Rc7,�XJ%KrE���6QF�z�&�`Sjq���d��G��`�2�cr�d͈Z�!
��ش]�{�o�e|�i.|��V�L:��=��\сZ��;� I���Y�B����3=%�̹�:�(����kx�l��Y�%�j��K~S^h�A
r�H!���LP�1jГ1�3�:��6JI�G��A��f_U��`-��D�b�����e�,.u�*4d�T,C��lx���f'u��Yx0feDEڰy�7p/a7��(84�m�y�+�,�%�]}\l�������p�c�[qY��3�X+�1��1by�J�A���~Pak��ϩE|���%=In�ɏ���2��m��`�u�	�2]������b�d�1f��z�Q�=�o�XMf>,�r7\�^=3��쵋��yt�����%(ï@�Q���(�'��o�A�ϐ��yQFԝ�G�"�4e5Mg:�S����wfo�70�.�#-F�ӿt�)���8N(�&����/�?��L���� ��LL�Q>RڜP�m����q��w�X���������#Y	=*#��i"�ǖ�P��� $ƽ�����h��iJ��͓�j89Iz��05" /��⤂��P\�B���X`���g�Ȥ �1]�7�#:����o����E�^��v�	����T ws�LD�!�� Ed�AOJ�M `ů�bէ�-��K����Y�=�*t�����lk��1�.��%�i��3w.24�'�ԧ�O�������+�� Fdd몈��..�w��AZqq�{��E'�>�	�����Oܗ��R�%��x�x��ߟy�Z}�vC����H� �瓎r)�[��
o��c�W%ʤ�&u�r�U�ʽk6���V`f_�7*�>�Y�Ɖ��_F.���o^ �^�2E�@F!������:/�d��n��n��pP\����#Z?�f���6M���:�w�$�$#؝���զ�1���/��/H����K��"!2[����������ê�|t�ψ��4���y�?ab��u�K7;	4��M����,�a	��7\�I�4�dm���aM�?>L��r��O3lvB�18�0.��;���U���wر����T��U�nz�uQ8h#���/�~R�F0�K�K��Y�O��\�_���@��{�	#݆8�������&��� `����AuԳ<O�S�b��|���o���Oğs=    8~�ܿ �j��#�c���f���ƶ\����l�s�z�@G"`#�6WOs;���!�iE㾚C5�7��;�[r_\$�Oӈr��h�������((k@�<g6�/Q�󀾺4S�r�V|��Tj�$E�.�ȼ�����8��&2J�[���gQ�9�F��bݟ��W�<^�h�yZ����}j�ClnA�y��*����A�X<p��M|���m`c������|�4*-���wѦ��R�3t��"~-����4Q��&Q{���1�7+СR���96z�"6��՟�<�o�U�,��j0GNMIrR:��gM�4���ܴn'���{�B�+���:�!�D`�o��D-�$piBn���oxѓ�6��-�����
�M��ڨ(�$����~��5���lg���̄q��1��Y��<��7��W#g�G�l?��	3���~�?\ �z��ꢺ�\j����j�F8ls�`����糋�09��0�.Z��|6hi��ܶ�ay?��Tc�E��PNN��a
4��4�h��M}��4|�).\�5֟��m�.�n�,��1RΣ"E��S �T����%�y=�������E�:	�/o^�l��IJ����Od���T��|�������A�)ʹ�(%�n�Q�w���\�?���^T]�]ahD��S8�Y����Y&���|�/E��ȱ��ٺ�c5V=b��q���z-�	��$x����a8��
|�+�o]n�G��$���ͣ��Z�\���Q�a�߿!y�,N�i���w�o�D�5��Ë%o�n�����B�o_n*	�A�©!5Jax&����ѝ��­����P��\���2��v~h:�ջ��:�]���r���d���5���,���5�� ᇌw�ejx>ي���enF��2HK�|2
fo��E{��'���#����'B�==���;QE �"7�_�m_>pGҦU*�{�O�Б��Ӿ�5y�A�K���@�zv��!H������d��
dtn��+����#���z]��i��v�ɤٞρm���i%@\�T��&�z`��Z�x��Z	¡RHHz���?&. f*r�wC��Z�2�
*�Q>�.���*2&9����ƖXFtE�4��!pjeY!�{n -Q�b�S�5�|)�c#�h�Ы>�5�������pw��37ՎQW(O�)�fE�ҹ�@������I�7{!��K�1��I��� /���5��ժj6�5+���������[/�+���K�YAEeL���7�(^�1L�F<{�qĳ�Q�+I�`���e�ݑ�X?3���R���k�y_��T�Jc���
���>~�=?a�x�E�գ�K�{����>����;.�]r��i��2�Lґq���D�u�����X��(��.��=H�,R3�&�G�K�c[��ȷ4
h!j0��� ;�&��W?#N���o|�x\Qp�.���3*�>C�7����~e�.X#����
�E\=�u�Wi�:4��<�˘z��UmQkL�,�)�~>��tF�3i2j�k��HG��P #\;8p�`d���n
�`6(���K�}�5�#�|Գ��'��pX�T�!��.�/F#!���"�g$�z�')���2r�
vT�F�5�M��#�+g���g�����[J���!&T�����������8q���C��L���Le�*��/�{,�h�������sd��<2�D���'�E�3	}0�	����{�+�Ô(z�����/�'��r�W�wg��l��G\�/�G�6�z+5eo{�<��褗�^C�1�a~�z��r�����aJ�q�b<�^�͒-H��@��n R��י�����H2�g%��hz��︽'\�؈���A�-{�H�R?OM�i��l�cm�q[:������G�[�"�t2*{����@���m������a��l���:p�lPS���[0���+p.�z���g)�������z)��õ��_�6]���0� gl+�]SC$�C�ꂪ,H�o;���� �K�0��*P�Y��Dqg ��m̴P���vj&�Ҩ��V�e'-7�����+F��w�k\�Db�t�
l���A�P�hx:��)!!㫠[%�%�r���z�YL��h�����q;�JT�D��&��Q;Zܸ��/��<ˊ<�<<�A��#F�	Z���咵<��-� �v�vh;�֡`�N�/S
����ˮ�j�9���g�0��/자�J�,h;jꠏir�u�ӁG1���SEj���d9�@�"�ON�j����(�Q؜p�bJL;yb! �yN����e�H*��i������|��I!�4Jg+5��?��"�}��	W��:ny\_A��H|���;�꾻��*uu�$����/~�c�[���8�G3
�XI�VF�l�|��j:9��>��cUm}��}2l:$��G���R��,���F��=.ʮzT&�ݙF�!�l�"�c��J��_
�(E�����ze�(Bm���,[dbsr��l]��<e�V
�tF�-��L�&%��|��:<�g�9_�D&vn��;�����^��q% x�)���Q�mL0lXO4�ֿ��tלt�e��n�"��`TA�s����^4_��0�㡖�/�ʩ��Q�N�}�>aj��:�&���t�P ��ȣ@3���}��X:(M0`��A��^](�]!�(`�D�ԞKa�t��&���t-`�tLz�2
��P%x�����TH���J�g}K�>�5B4���ꦁQ��(F����z�82Z<R��H������IG�!`7�n���#O����x�\��i�l.���J#�Վ=��vCј���ϕ�
N��o0Ԃ��dDj�+���
��Yz)�x9
�$M�ɉ���UyW,z+1q�����{9G��B(.���Y��R�#�rv���`ߡ:���B�5���Nr3����kW:L&�+�頋jOg���#�����Y-a
�s�����Y��V�
n�2�u�Y�+�+��x>`"�ނIˎx�j����"�u��i�,H\�#���(��dT�,�x�U�jM�ڨSL^H z�N�JZ�t�i�Ư�,�;�椿bw8��Qܧ�t ��Ϡև�B�Q��D�xd	�w�򅪳~�:>H��[����̙�
��]��T���̪^wi ��a��2�'sְ�@wn�۾!I��]�@Fx	oPDy��w�f��B}���zi!��8�8=\�����I�Ϊ"2�g��+�JMt�d�T�dDN`���_���ĚZ�����}I�w�I�hu��yV��A�o��?�9bVV8��X婴zF9an���|V=��#Y��a��-�b��_�o�ˏ�{���|G�+zvk!j^�)�V������犑2������Y����}��A�F�[V��3�>l!����~!�p�(�r%P0�\���9,:K��W1{ґ����W��l5��|K��^d����"�+�:G �,�T�n�z�/�M��7D����T���>9���0�^�Y%��^|/n����[jq��%���$̥`�ag��8l�s�K7��S<3���X�2�_���y�:d@�9������k����e���Vo���gw� ��>g&��y�H!a����r�W�(��o`qV�M�����u���z�����_�W�R�/��V�1"e�O��l<L����7�Q�H�%�G�\�k���z?�6��3eD;�#2T����S�����='�bL��a�N!�{A�+,|�}�xT��E���ƴNd
���;�^B������ᤤ:n�2Vz�r�B�Ȯa���r�Y�S����r
�3V�!(X�9 %8<aL�4�����{��������(&3�ef0�7ovNퟫ�����y�T������*��A�T��Y�$�Ϥ��<L5�M�4��:$מw���]�z^l�yLfrg���[�H���錤�����w�޽�7�R�5�+:����fعq�Pot��UP�S^� i�;2Q���� ����9��)xxC��    ]�߆z˨D�x��iH� l�|�p{����yQw��s��_��Ŋ�{nts]����Dq@2�
�*.X�f-* c����I0�I��Q~!\M�5V��C�`߳���Ra�Y>�ӥI�'�t�xLfR�lo,i�9 ǘ��2��������Ƭ�8��F�p�sԾa�"�.J��f�Q	�B�^��O��V︢��DA\1Z�z4:F��]�Xۘ��^���/�\�D>F�]-�G�6yU�ŀ_�|�8[5�bh��$��)��@iI�k<�8��7�H�w�	�ِ&��n������4bv+��l��ّB�d$�9ڿ�@���[��p� ���o��p��Iy�i������SJ^�S��/_�
i����C��!:}C�@B�w���Q٫��)Pm�X��U^�3�Z��nx������)�$&��i�Ǌ��yIg���Yx�i�3�Q	&��#\r���~o�!Z�)1��و�z�.��i4�uA|�ǉ�<ᴈ��'�뇟G&�G	� �}�kV!]2ʕY���{��G�5k���G�W̅~�$3�1Z���N��r�ڍ��:O�����2��9�9�Iu��k��شm<���!����7���B�l�����xA=>���d���.x+���ͫn]�ް������''&����_����S���3���(�W8%�%�l�H?ޏ���$ܝ����9yO:�Y����5�l�ѭ�{($K@���H���[F�Bs����wZN����ˤ���5�{ga�C2��-O�����پ�b�聳�V5�/j�{DV�k��p���A�`��F.�!�{��\�( j�3v��/�{���=�ъ��4*g���s�T%���N(�T9���;�n��c�7�kJ*�vA��*s����yF�b��/�����AU�[Y��'FLO]-lp����S�K40q�(����Hg�-2y�9�?�Kj5��)���(+��4q�Q2���9�yI��<
� �v^�7��S���q	%7��(���i?Ŝ�A��byٶV1�4#!D�*m�H��D�Y�_�ۺ!r�Fxe�K#ɒ�#[$�iV��;���t#/q�s>`���
<Z�hiK��9�0��C8� �8]�3-������X�S�H��ϩ���ȍ3�>��{>�ڱ!&_�r��l�`��qDm#��D߈Z������M��AN�j����H�Z�:�[,�X���Co��V�M��V
E���.u�� 2mYAc�g��\�zƉL�1N}C+���@��2J���V��"UjcLxMb���[-UM��\k��|e�=W8PY�ʔ�`���x)~~�U�౞{^�S�n����(�� i��-�������Պ��93S��L�/��l}�hF��c��֗��4Q�PJ�ʆ$�h�9�a�PiG �r���E���am]���)�67��ʘ��#�#���s�LO�&�Nr�mɈ�2��uo�04BJe|T�%�r���f�Tm��6L��p{ ��W^��6B'}�2���}p&-폟�.��9��TRT0*�S�JS���|�UoX�$�*���+���LR��K6�7��"��O聘����=����cv�}y3�bQVl��(�QA�o�	땀��L�*p�3�T���JN�(ͬ��"��_g%�yd�'ɧI�4�$F(\N��>��!Hieģ`������6��E�n������rj�|k�LN�΁�J)�b��seHm4��M��>ׂ`4�N:*{/�����'�KmXIӾ�{�l[�*�J5�(}�=��jS�bl��#M�u����Q$E��R��VL(.����}v�>�7O�����9�n����͘g��صv�R���ǃ��}�l�-1��c�A6�[7�,�騡�	%��)k�=c��I�u^�U?�i_ܫ"t�s���Z�Y�oS��萩�N�w8YVeL��2��z,�[c櫦P�@_8��-�;m����J!h&Y����36����Q�������k�Y*��4�g8_�f����垉a�����{�a��=�*L>�H��Ԅ񺸹<�\��D�IL����̔{%ݵx�u�i�o(6S`+{�($�a�.;�6��=({����!��a@5�[�e9*3���b����K��*�z9�DݖL~��Q�3�(�+��>����6x���,ѧP��H�_�J���$b��k�R�M���R���P?�B�_�B�)���(�݃1S/� �]���VA\����8޵��l��kEt[=�j/����A�B��V�&�2��4��h�a��X:���E�2m�5��٠�� �O_��n�O��������E<��9wJ d6��1Ʉ;m�T}�$�2͑���L�?�盺Y�*��>ͭJ�CJ줮��Q�ٛfT�!���}�`B±/��w��^.AQ�%�t���#V�*����7��7@*�r_,�km��~����㋑d�J�u���}����!Q�b���E�q(�(�&��V^ި�����a9f�`��4Q|Y���S���ᗟ(-
zZ��|��!���g��l��g��	%Pe�e��5]�l�C*�f���l 'yV��\Y�e�ԦY�X�dR�I;\E���𙊄Z����Yη`"����ҩ��pxRk%�|ӏ���B�s�k�KG��{��x��x)�������J�)C'��qGg���~�f"]a�I�*�U��:at�ރ�~P_z��EY���{I�u�ߩ�}�$%�(�m��C�a�ļEi��nV�S�՜�΢� �H��(TL
�^V�{>9
="�G�y��V�㠪A{��/:����PH%Q�agtR͎bsaT�!��$ڴK�E���)U��"�KQ�ٸ��I���Tnٿ�u�����(�"��
�T�y�N��bL�6�D�ń��P��̒�k���2�R��Gzx����g��#��9U��!qIӊ�~��{X��TYkgqTR�ݒ�е�;���o�o�+1EEX}���Нt�|�j�w���D��2(��~_�W��T)�X��ZO_c��<�q^ó���7�7a/��p�p�bN�a+|���S��铓��h]z�_#���ُQ�)+���q[u��b�O�ȥ����""|����)A�
��*Y�`$u�jl�E�+O��*�M�\�cl�/��cYF�\8T�q�<���^<�EgK*
X��:��>��?�o�1c�ݣ�lA}�������-)��%e��QQ�$hyV�p��6���H�M���Y�ճ�W�\I�S�oR_�Z4Í:'���A_OA�o���ȾE:>�2)����nl~4$�z"bv�ݩRQw< L}=�� x"�#��"�=c1�_����\(���%Q�v�0_��8i����E�^��v�Uwt�����wꕤ��f�qB`�.������oҎߚ����L��2�J�����#C��7���ʚ��_Հ=�ʈ����Nb�+b%r��uO2�az��	=V��~��o������jC���J�,��2
f��i3�V(64"�>w�+�	�JSO����ebs�t�d&EeI[�5k��R��A�Pa��U󦝷ڏ���k�d���X�IH(p��+�E\f%�M��Ǒ3:B�%���iLU�2J%1o�;�f
�A�,
�p�x��>.ː:Q���R{z?��c��ò1�Q�=x2n�p$߁�5&}R�0~Fg[L7T�}/7����Xe�Q-������~����΀�6�PH��$2x����Ū�cZ��]��Q
#f�� 
ȹ��nY��n�����]͔�K�T���z��2'�y����ǒ�K���IGQO�Z�����0��р����o+Jjq[a�M��ف;KSR|g �ܾ>�YLZ��9Wk7��_���8.S
d�P�9 ��ƀ���X�)����Y9�(�y��9�C�����0�b1��/�|��Q�]1Di��N�ΎX)���F�~�64�Js	"ˣ�x\�rـ��Zsr��[    ï��hRp�Ng��p�6���Z��N�89�kp������`�ֿ%�ߝ�������?�
E1?a�X�ݨG{��u،�����4P�(���d*S#��,���H���Fx�7L�	�h�Q>#�H��qiJ�8ӕ�9b����F�N�70˪������R�($3�!X��n����5[�a�����|Uumvk��U�3�<��z�=R|("�/���C�l�Խ��o���2-����b��PP�΍fѿ�*q�O�G![�(��vT����Sr�{T��B�+J	���s)8c�};SQ��ל�tC���4(�2�Ե�"-��u���X;������%+���q�m8~�`6$��z|6�9ѯ��%"�#�o��8H��Q>o��@�h�$rT�M�b�Q�:��,���,B� (��y�y��i8e�F��7j�QE�� K{����h�a43W뮈4A���Z�����b�%��o?Ǐ��9�?dw"�K:�n+�i�^�Ȟew�8�։q4����xT��j�֎��L%1����?�^'$�����^a����hd����hxw�HJl�D8�	^ָ�}�5�MeOѢ�+���̭d|67�c�^�$���7s�9�fi!I���^�ò��i���LrpUIْVs�p���m�a�����J�y�A���|2{n����,�뎨���bPCO�/�%&�tg�Y�u��0|k�#x���)��K�J%~�H��z(�~�?�0
2Z�Fh��f1r�X�ux��$a���@wy���C��F������^+�H/t�M2Y�2���rF�y֔ ��9x~�E�%��9�6�(���5��Bt�S��n�	����{u� }p�(�{u~��hZJ�_�@}�G�J�*S�B���a��՗�^�n�&�<.���BYA�_?L�[��^U	%��1���p�/��a}�K�b�H���'Ҧ=5X[��6~�L�nS�_�|+���|cq�/�2��ϣ�/7�����Q�%�b?�(�=��=V��s�϶]l�î���rJ��7,L����0A��f��Dx>Lʆ�%\c��d��8#�P�>}�JE`��s�=���}r ����a�X�r
Lp�K9x)���6?��9B���On4���(O�u�#�GI̣Q��H�%E���0]n��He����F�Q���I�����d��ב)� ����\��%'oѨ =�FH�߱>�]%�0����-0���'5�8�v{��S�2�KR�9׽*,c�<�8���݄;X�!��S��8L���J/�fB�ɀ�,K�Y&�kv4�- F90����L
_ ��"a��>q��d�1���{߿ZM"�v������6�Q�}�n�|k��k*�+��z�~�%��է��+�=���+��AI� ��7��:�{��mjn��>�[���w���ٮ]-*�a&�з^��"?����{I�L��a��9Ѽ}ϳ\���ul��2���]A�����'r.���,�(Ӂy4���"�b�!�r�j�șYK���O��(Z@�0���uV���̕��?ԗ�&��h]{�b3ɓO?F����]=�S��Y�d����y�}u[��'�R����Bǚ6Z
/	2�gX�O�'�2��\i���� �<$e�L�@��pT���w��v�t#��6s���T�6�+����슸mȕ(I�:�l�V;�g�?/决�JR�z�D�:i�����T��lci�2���a{��4�^�y�2�0��hR�����WOcI��#r�"p�|JF��{n;|,{2"�=H��8>y�x��6�T-�h�=��ȹ��u���1v��u��Q٫%�	��N��9RP��K�M��}װ���E�І��\�XR�7yNNQ�0�8Ab����p>�@�Sʇᨰ���jM�*+H	��~S��y�7��7(����EϜ�;�R^�0i��?>}�8Fr ��A�w�!^�+�6+t��I�����6܅BH�d!W��b����>bW��"����Nk/EI)[��EB�Ֆ����c]?x�Jܰ��
۬�I����F�ǹ�^���A�&Sv#��ˤ#m
���` SA������s�4l�6���򇻗�Ryd�o�5G��U����-���QC#JK:a�h�[���2�*��zv9��!���)����ؽр+w놨��Nˬ�+�����{������
#��6b��~R_���g-�]��O�2����J��*�MZ�����¿��@A\�9Aݾqb?T��ɠ��Km`�AE9��I[gj������71)_�P��r/U�e��hA)Z+���PE?w��<o�wP�H����Qr��/�������=�p�h�J�ipS��Q���!�u4ft&+���+g�}�0Y��V�UB����
�p��
I�;aXe䁢q M(++ٷ]��PZM$�/e>#�Y��F��;�u�-c�p<��7�V
�4��2�3|���s�Y�9�a����[��\�T�_��ii���<"3���ּ��`��j����(���n�G���.:i�9�A[c�Ʀ98m�r��a�=J�ˁz��c�i�����<�}^���Q�GB1����,_�� �hS���e��/����:N'�X�Z��-b4��3�~��f�P�I��gRW&IƙPž��:�S��,ʗ��l�V@L�Yqb�����_���BU�P�,��$�L(a�g��7ۧ,��}������7������D1�X�&&��)6�5ҦD�S�\�bG���*�x�9��L��xvR|�9T��~P�m�HE5�-��R�Cc�g���ϋ�bȧ[��y|�\��V�Y�Ɵ �����F�vz�`�0�o�&���3N:*fE�V��E���T�d&�N���<=�
]�#(�Az�ϓw=�+�x���$܅I(��l��(�������7�~`�������:)H�p�&����a��jq�H/��<,�`���kj��v��i|�vb��A�d	Nѡ]���n���w?j�
��z�w�8�4��F���1�ꐰO��~c�o��`'�"ਘ�⯤�Os�>�D�X�QQ�w��Xbf�G�g]��*b�<їg�h�xRd����o�4a	��
`�.{��˯�"/EF���jϗ�j��m��6CD���v��� ��)�#-�H����E|�ita0>�tg��\�	j�tͪ��"�9Qh����Hó��
�#���
�L�:q���C4'ї���$����t �?��=ٓ�Bo�ts�0]��7f��Ϧ3ɫ��4秺��鞫�\��wm������O���%���7�;��� �B���T�l֓�
�x�bC�C}l�F�BJ�"����<'�@�X���#|���乤�k(���l��[ͫ��;�8���8��XUmār���s��VGU�.|�� �%v��8%H���:�����1�:��P׶$�?���t�l��$sD�_�Շqd���/"4-|p	߼٫�U��$P��$�4v聙 ���MQ����7܋	�܋G�Z�(�d0Zb�s�z"���ѷ���4��.�H���:a2G��5��v�X�(ԃԇ|¤a���r�nzv{�/���	��-��N'���jW�,Z�6��B���R��g����i�8��:a����7��N+R/v�Q1�˱y[�d ��c���dܴu�^_�%��A�01�#<�����\%4�]k�$��w
����c򞀧���L��!�DF�>��� ��elqţ�Jj<|���A2�LR��}��0M��p�!���k_'��3�,����g��Z2*z.��BZ>𱫤S��|7�7L!��D��Uxm�6��L4=Ls�|��rO/>������0�F�����l*�����o���84��K�{S=4J��$�U�0�Y��.��2��D�����`y·r	G�8
��71��@T��k���9{�p�    `IA�1a���̀��܈e6�Df�.�|5ߵ݉�	��Z��L�t�8�����U��l_�b
w1B�Vj�*�̷���h�=T�r����a,�A0��Lڪ�E�:bz���������H
��S���j�%�.�J�%���]����dH!�Y[�!h�ދ2��oE�G|_ͧȍ�&��B��	��x瀞�X�Q);��e��E-]E|n�Z���v˥+*�"�q��ߡ6��jv*6���k�˛�f���\3��^����<���z����bA<)���=��)�(Sp\�d�R�:����EĆ� g!�>,)v�}�<R��x��y�
l"<YB�����?�e3�ۻ��K\��H"-2
��}Sw���W1Ǡ�;�Z�KP��2��(�*�r'O��*ࠡ6A
τ��|�}���o���7�2�X|�F�(�;#YdT��1�����U�;x�	�R��p��VRl�*C7�0��N��+��'��9&�tВ�K��:w�c��
��P5u�Uܑz2�.�$W�Qc1��م�
�d��a�IH"<RM�-2�U�)q����osk^�B�<F㈬�3P>��I_%�3*S�a,��2[7�)ϥM& �6�8�-pD�n+N����[���g�RҎ�U�Df�'�Pz���ڮd�`X�D�N����i6F���>�PE��QB"�n~�v�B�G�iX0[ӕ����(eI�L]�{����XI���� ����~�`����[\�}�>�D]$=���M���be�f�h1k��3��*2��*43�0�Y"
�.����.���Ȉ�8�w��.��Ǧ���+��}��Ƹ��Q�Y��Z�t�1��r��˘P>�!P
.�h��XiPb�Y �P��JNq����m�xw*t���L�iw{������
B����w�;fh�s��y��UnZ��mU��ŴU��sM/V�7�A*�jD���G���w��>��%����q�֒�v.E���P1���B��,OA�R�A�?'��SB֜�Qf��]�9jR���fd�v5՜���~��f�1�tѥ��`D�[J����{}<CxFL�&����UFѬ��o���F���tj�a����R�{���_�NU#��z��\�?�*]t��|�f}��4)��=o��>na�b�
�z�lj���Fûp1���ЋÓ\��@�V2N��>�0���Z�6p�)/C��B�|�2
Y�G��F��VhkzF8�*L�$��o�Yx��3,���$��,`I�s����*��YCLO('�%qQN�F6�t�=��Þ��[Ό2whH�2.�����78���TUt� �Ȫ̄�|�_��F{� �o�&(��c&��(����~+UW�r9 g����8�32�>���k��7/Q�i&� Ttf%�xf����p�9e�餣�W-a��q�)�u���&�s;>Tn���H��.H5�sܗn�XB�Y^`k�	�·�R;�F�	�vh��eY`s�@F��Ç4&U��$�����5Y"t��s��\��=��&�E
��?=˩ѫ�sx�s���+����6,�\��1:kԸ�Ф��5�ʯ�BR�����^�Wt�TS���<�J�qw����U�����_஍��j]O:*����a��B"�i/�f|��بG	�$�ϱz�$ҎU��˚ozaKa�B��B'j�$:��%�u��(�9�k$�,���r�������h�ַ!+R�rTVz�KEC�y׏��6Y����s���P���}�����FZ֭�o0b���N:�͝��o�[G� E���-��Z"*����B�CI�`�c� �����uB!.� �� �oHw	~d�:�:��:���]��b���+�pX$�
�ꅪ��$�M���p&x���y���a1�,HY�G�� aX��Q�B*���I^��ӥ�A(�j4�K�!b(6f5V�Wl�hCl�L+�p��� ���<��]���^/�����Ğ��W�Zm[���{�����hQr�pX�w*,�v�r�v굃�a�0=��]``�)�d͈���4��|����K�KJ�`!!��Qai-f�}�b5ǁ(���n'�(1��"���?���"��P�P��w�Ҕ�S�(�$�M��8�\�BK��~}��#�$8�{�w�Md�<)&�<}��u��j���cv?q'���(����P#��v�b�=yj$�^J�0����pk�T��0,1e\KȪ�7�%OE,N��Q9#`���=B���{��ܒ�i!���7j�i)���ػ�VWi�U:��0	�br�Q2�;{O՞����͠x��I��u�Aiǉ"!��˗}+�Oz�*�6"�L>#��3#�]G�l��|y�<���
��v�p���cޗ�q,5yv^�
�D�1:�y#m��<��)��)lJ�@��D���B�|z��*G��*m5�=��I�^����RTRK�"���oτo�&R��@�!�)�`L����(�U� Ŵ��|Ղ��n�nط�-p�,��/ߨ������#�{A)���K(3��?���
�ݺ��9�	�����86�
������A}��$��/�}K���^��F�_�J-����#_�"P`�;��I?'��>��z�ƴ����E�����*��ևR��""�&�d����hxpC��T��P]����G�
{�z^C���Tnu��9�X��vr��Z;|��[J��o��8�gal��SR� �X�"�Zם��1y6��zޱ���i��IV��Ҿm��#����Wɨ��ɧH�dI ���8�lIVK-Y��Ӟ�?���3QT��<�;Ue�;������Z��Ws�Ҝw#y�u��HC���uE^3��L��o�e����[48�$�ɬ�r�՚q-�������a����n��i%V,2�A a�;��wh�{�Sk38a�)�t�ll�T*y��\ޭ�>�z
�ps������*���
�KE�2g�iw|��yg9��WI`�yAe!ضLA�#�8��x�XU*�i6R�W�y� ��#F�^艛�(C��$�5w�FN���K̥�I�&j���?�;q�4�}Mÿ�=ZVy��,#bz�t�rX��?�R--�!\�MxO�׼��0x.ئ? 2�=@W^�͒s�t`�%񉩙~��%�ԣKd��x{�q7�w1GT�$�|����xY��d����Wp�-�;I�$��t�]F>Cr1
C~����PdI���S>p���CTG�b�]�ƳkN`��h�H5�'R��&�S�L��m��B�&d��T����&Y��8e�.����ey8n!_�7�$�DV�:�s�]��-9\47%����Z45�~�i���i$��ߌ�N��-�	,בf1cШ��[��_��n�����F$e�D��MS�+V�����W�a��e��l�aG�4�hCn��$��?�Jp�s���IN��Z^Cܱ�0���vKx%�ԛ�s�����H�-a�p.y��"o���i2�f-��I�0�m�;�ٽ�Tl��K�6�G(6�^3e��6e�[f�����<�C�ӺdL�����f�	�\ u�cc��efxV&dC)Od��D��mKt62�lg�@��TQ3N�uT�͂�Ɍ��"'	�&z~�\wk�)��Yf���>��5���QCDV�%�u�Z�1-�F*��PË˃I^�"��式p^6�3}]N�Sj�������$	��y�^.�P�i���a��Za���7`�/�'��jYc��Z���u0���ۂ$�����2�Є�3��/�Б��Z��Vr���K�0u�:�ߙ�B�1)Z|�z���߉J+pM��)W>�����w9���>�Uk��$���VA��ԓʨX�3�_��Gh]����$����&k��/����H�d.�O#�I\��j���*o�@%v7 ��r�q/��||YpW�8P�u؂߯������fy��|��3��H*����Q��E\�{m_`��WH��w˫����kv�c�D�r�����jy!�[s�M+_j6M(�;������k    �$#����2����7������u��r�J�w[��3TR���D@?��'�!y�^"iC�3����3��t��O��1[*��r�q�QABW�嵦�"�"/���`�n��)�CD��!��)¤\��`=Y+Om3s|z>g�V'�ɹ��>�*��TF�������V���I��J�z�S�;�n|P֠��?K����H�HH51�"�₥XF8'o�{�����l���@�s�WK<��a������=������\,��~��4��Q^6Y���~��X8�ճ^%�T���mg����J�����mQ�yM4�m�ǭ��l	�T�wm]9�Zc?Ǒ�ՋPe�B;C�<�f���\����ܻ�~}}����k����)�n�$��Z`^�H�@c3�����}�<aP�<iVkp�*HL�R�)���DX3�n�_�.dm~ �e�����e��-F�����룞W,f��(�]F(lr��_//���1�]�%���BQ.��GP�1T�A�Pz ΢�ls�3�#&���������E��	�cɲ�)�NT��߬��%6$?\
�.�^���W�?���%��f�\��UՖuE�d�,��Qΐ����L}C)4"��&a6�%	e���i"~��
�pK
���҈d]��K��tS2Y��Zk�=��L���Lآ�f������F�@�_~������BGY�b���F�Fd�Ttʣ�O7��e��)�\ ��m1���ߢˌ�CB]k�<���U���F��C�X���CqR]���@"i+�����*�$�v���KĶ�L�1}U_��e�t!f&}��^`�as�AIF�vq�\ng��Į(H%3�G����� W�_����ٓyYBE�u92o�&{?>��@gA!�&	�7o��c7�qx�����T��?b�o-(8�;�����{\b���Mӂ6����p��$������q}��w�6o���c�����#dq��x�'�6@�<�U�ђ<jHǥzy1-��h
8C��E�L��Y����ؘpc���=f�nV�{�6��;	e�&7:�y���Z��%�0���.w�E΂�d�켉Jl)�|���X05�2����#�F��zJM��Q~G��+�w��8W$o��zk�&��7��/#�톸
���"�����Ȼ�ФiTK�PyqX�Y�,�*�L�Pd	���/���晧JX�P}S64S�ˤ�?>�m�)C%�q�c��WHev�QN}=e���:*k�S.�r1���e�s4\�)��Q9�:��6��0�k�9$���?)J�C9�
N5]/�w	����R:�ZjpA_�r�W����E����	!�pR~W3,���ߟSv�:	��g܌��(�M�Q� 	 lm�*�F�0/*�s�
#m�sK�)
7Ө����9K����>9�Y^�I���)��4*%��`�/���q�c�rq���Kd�U#��Ǉ(�dQB#_��p3�)�9k�9�,�A����jD(��}�\qWp��/��c��qڗ�F�0�ת�x*}o���Ὀ7%�@s/�Q1q�ə��>�t���(ʂ>��ɹ@���X!�҆!�I5�������)(��X�5�ի5�������K��0��N}�4[���*���/��2PH�2�P�~s���7'�HN��II&�57I�i&������$��$�U�w������!U�ù�}��o���,��h�{SBz숁_%��q�i�~H�|9���.���.kgO�_���+�[k�F�������YG�$~��m��(&��ګ~�,�h>#�T�"�k�J2:~�9o���=��8ʦBl*������R�Y�6�Y`���b��z{�8FsQ�oQ(�pj�A5�ۢ(	��oxZ-�Ѧ2�]�۟v���;�[e,d��2���f����Rc�PG�b�:��-��Z�X�YWp+Yg"�7�<�,�vkc��-�P�*��HF�GL�@�n����$�d6}�Y�w��rpr�$��(�XVh����0�IZ�b$v�z#��hU�;u��L\A,8,w�5˯�D
�EևE��*��L�BQZ㇤����fN��L�>XŬ)�&�����ߟ\��a��պ��V������j`"���Z7�B\j�Pz�!�}���?`� ����Cۓ�6��l�I���l�44Ι-��H�Gl<��� $�k��.d�A�D�C�c�H��*��7�2�M)��H�a��f���G�7f��pXV�SfR,�B�qT����k��9^u4W����oQ[�X��|�,g9�۴�
Y�����Y�T��#��Spϩ�$�(p�#�4�#�
��|v�r��9i��O�~q�;�Ϩl������ؚ�4��U��F���m��6���	�@� ��W�a�p�LE
���T���"c�sFI^k�x��f�㍝��^V� �4V2࿵p�D%3K�U�}���[F�q��O̱I��Ob���h
1�c`��"{gu��	�uKI���NXHb<0DK������%2�W�Y�#C=�j������$#@���#�FX��O�2���l��I-,�L��ϕݢf�	Ёr�f�"Ӓ�f�5��f�$�U��/�v̱��bq?�w��G�O����}�װ#���v�S��f�@�Y[Ve�n�Xe�_?�Z.���&���eD����ſtT���9E�磠��Z�)��F�H*���VL��_<��"D��YL��4>x��M��X7	N�3�%G�4J{DXRI�p�[�HCr��w${�;��yIm+ΜB�d��b(�Q#	qMZFj�� �~�^�V5�\�*�)��R�2}��?��q��Rb际*=����$�D��॒��3и�O�m��ѷ���=�����H^e�xZ �K̚v�f�D|]!�3ȴ&k�\�@:�<�2�V0�����(�����A��+�4ª��rMݺ�@6A=����1� �/��Ѽ��w�a�t�І�,�W+��Ou~��~����M��X}Jeo�ǲ7�*�ڃ��q�JG��O�M�̭�'�'u �w���~�h�Ħ�7��X��w��*�̕��[O��p}�C6#��s	�p��ga�Ro��!%Q���7^��1�;[��`^s�G�#\c����̈�.�Mm ����0$�6�O���9PHTA9�Y��3��T���ޙ۰&d�5ŞU���/�TF)�_/�ї�+��;KI�{�wʛ��$_@��kID�l�NUFʈ\'�e�tq�c��Zr�ydpv�K����Y�(���~�[(v�
��������O�V1�d��:O����7Jw��d�pn�G1�{���	 �>�a<���I�҅�_�_o�,^࡭����0�4.mӖ�|6���}�r�b1��B���*��*J����!�G��P+���%��]ٲ��dڌˀu�N%������Ĺ����}�@}��yD�G��}8.�z?F����l�//���3����E��I
���Q	�z��<;�3���ϖ�I`�>�^��zDuTro�+�-`9H��f��O���2�}�����ZyC:��Jn$clC�� v<�J�����ڃv:��ؼ�T%1}��I��Zx�ᴵ��(d��D����|��w���,C�$o�y��qWv�4¨�"{�d��ȅ	�,F��5�7��1�?U�ǰ��z�@�Ef@�H�=�Z�.n�s#��gG+���hc48R!�%=��զ�*��'���g�錤@��׀a�f�ql�O���ەMIT*:8 -�~�엫a}�p]JS/�g��A����c������|>�Z���M��|{�"�ֈ����iQ7M�p���������I��mA��s��bX�j��3U\1��^aÝc�i(��󭥠DF���^�Ǹ]	n�7��xG�ͺ$�-|
�G&}bT��f:�򺐖h2˰��;˻:Y<4�.5X���^Y�]�Y�y����n+��]X)���X��HJ
�.:�KU#��h���cv�o��H��g�"�~��k�L�r��m��zMC��H/Mjr�-��E+S��Z�'p9��    �&務M�����cp��ڎ3J)9P��#� ��c�n��V�uX"�k�>��٭a��� d�8F5�6�ٰ1���u��X���x�7 y�m�R~�3�����`$3�<|=Z����D��ߨ3�c�)|?y���2J�B�T��D��Fz��B��~۟�"��|�Sx���2WS_��� ���&�G�e����)��rXt9��#�[�h@2!F&໘Ẫnv�T�kQ6M������d�Z�l��<�v:*p(o��X!n����.���0nw�O��`��j-�w���X�rN䡘}1:�L���)N��*���q�"���4�4C��R��㞬�;<������i���S�.�����_�xf7!�m�܃:����8 ��4��3�x��48�mF���ج��g�%��E���=��ҽ�q�՜��Q�X��8���ۿä7���o���("-�m���P'�^��j�b�����i�<��	�x#L_��~�M���1�/M�L�(C�=ˏ�ݎ`��:"]z1����b茫.���tZPv^�0@ ���A��J��.}��X�"_��ƃ8��v0�u��Lѓ��������g@iX_�[A\����&!m/����v�<�TFC3��vy�+��=="y�?�4�Y\���L�84Lו�xK�.J�=�����D�̰a#�Q�4�	�"FD�|�"A!n��*����1Q��ƪHG D�3�w#�"+<���Th2/��OT{Č-��+2V��Hh%�ɛuMs������}Yj:����N�s���0y+6��퇳�iO�O��Qh�uO�8Ȫ,/H�OF����dA��Q)����!��с\f��˶E|D�]�d�)�����]� P �ܫ�mU<�$_�l���c�1R��cw�v�0�I2��Hk�y�)� ��vm�}��O&&�K���(�˒[7ɹ��`����_[�]�˫�f}��8��w{�wt���E�?��ೋ��x�KS6���gJ�x^V�A9���}���Kn��Zv'��is"��t���G�<���JD���	�$Da���?$���x�&G����ȕJ$:j��yg���B�hn�yh��B��⡫��].��g=��)�t���1�䎙ꥄ�31J�k�D�9�L���c��>���S�6��2%�G����rp��x���)2|?�g�'��Tm�l��i�	�����h&ڰx��6�7�Cԛ3�� ���R]��.P!i��X6��)�ؑ�����G�� �u� I�?�n��W�2���ǳ�W` Y͹�2�2n*�Q��l�aK�"�M������i��t0�^�$�0�~�]�$��i����٠z��)Zq���@\IYM\�'�nCE���(W{�̱�M�)���:�+�mށ��"�Yb�٨��U�F�m�Fy"ߦ �dD�!x�H���ͮGc��&��e�@d���|.�C)o���Y|Y�3������1 �Q�S��c�lq�3�������S�G�#7���␭�������
*��y[�H��_�c[��E�7c]rH�u��[M=�2������!�������A�m�zFfK����h����#R0�2 n,4���ڢ���(5`��T2��M�u4V�zGʧ*�=}������.����j�%�ێҏ����$�ۼ�;0�
��y���0���IE�U�(��x�ۀoj(|���q�y�T��z��鬁Q�S�o�i	_!�Ȣ#���wL*��7C�Gy��g�-��.#�+ދ�W�:��s<���%����`�o�F`�<G4��FB���0�Ѕtґ�4ڬww�M�L�LY7%�xQt�x�b���1�.�~T�Ͷ}��e��(4�6�b�J2�?��TX��Œ:�V?"����6��i@���4b��~w��p�/N�W�˫��2ِat����	d�a��Ne̔���ې�a*#�DJ����i5�k��r���w~�����.%"�*v�����)�r)Xy\�"��dt�PW��~�-L;�I��f��<��@Vn
�hw�fX��8���22��#]��X���8M� 8S�}�l*�uG%:ʛ�zY�D�?�p��j|� c�yA�a�fYE�]u�O�ݭ7q�OIxjh�_Ԁ?ƔOx��@��G� 6�VR~�\�+��D�r�T^1ƉG��n������=�O�֙)���L&�+X[TQ��i`65�J��5��/ӌ��邘��m�)Ϙ,���ם�98��惹�S�~�hs,)�>筠e}�R?Ǫ� /|�dm@�E�`�������_�Ɣ�>�b�}��^W��Ҩs<�����>!�}'���j@K<C���=��x���}}:� P�%�D��X�BfY��؁����^�م_qC�H��ȧ��3}���/��׷;/�$�@��t�T�yV�$�^S������s�'��d&�)	g�s�]uE�/��{�p";� #�Qҿz.�3�R��w�\�X��v��"j�'�P���Uۊ�ˣ2������scb���n�ˣܲB47U�T��V��j���\�>��$��jt�7��i��x�p��5O�uJv���ذ�:���a���B"xڰ1�6�1���]�7�[%��"�p25����WC
���+��0�J����Y�� �dn��gu��iAd�j�]�ߞ�Q�d�J���H����rG]�[Q���U��?r����/a���HdY-�����a��f.��ne%K�Ѧ��(�xa�.��x���k�� ���+n�ZB���m|�f)=v�xɠ�iC��:�ޤ��`L٤�C��rN�$�b~�vh�{A���IG�bM}���ù'uPޮ���!xt�0)
��s}DT�~H�	}�3������1@�J������Q��oXT$����07dg���#�|��=��%��0���h³7q�+یz���T!	�@4�ОH|	[!�,	���þz ?�ء��Wϝn'��nF��S{,��S�3A�z���c�7��.u����ʖ�(a3=��H��U�5�e!'q'�l�*,;.�#x��*�:&��J�K1D�ݪ�	.s��%�8���BD+lF��o���OZA\#��!5�{�`ß�e�g�c��#'�Н�o�sD}��� ����ō�7����ХA�(�ɪסO���I�;ɺ���N�^*e�y�v^	�.�f���к���v���('Ү�5V~^��K��p@�[����	��=��lT�A��'�>䙦Y�g��e�����RA.�c`nr��Ǵ���J-�2Bf��˹\���W�/s������[�@�$��|����W��&;7�-$p!�J��@2�`���+=N��FD��5���w�a�sK���\�j�A�����<!�C������Y����Cz��� Y�`�y�}P�ݜ���9�R�&�Һ�El���H���7�0�����ꆚ>uԡ#����bL��t�3�G�6�ps�_$�� �2Mk��	�����YC��j����G�ʐ��e�*avE[�-u�ʨ\lwפA��PȀ���㾔G���\�Q�y�%���!*Q��s�'�t��&�!�K�'�p�N$�2*�����ԘV'�n���ӵ�x�3٘�2�Ũڦ�@@�2�/�/]:�	�'\i�����j��r���4y,��Ic=�=k8L��dw���J�y��p��gʿ>=͌�p ��d��W�ϧ/_�
o��4�i�o���7�jĺ���殑 ��ۭ5A^xk���+{�\�X7��)x�%�l�]���.��î�$�T�-PS��IG�Vhn���~��x�w��;4�s\��u
�	�Чi=�Ӧ� ��)�p�k$�,�v�j�ſ}���V���0D�r8J�.m��]�#.����l�Y�#�6';��OB��s�2W�e���e<�IWs�!�m%b�g4՝?��>��c�O��/]�0Ɇ�buLpR��*?Qs�ϓ�x\�Tv���[tQJ�y9E�n�2v�{�tNk(4�烡��!Y2���q�&#�f�)��Ǹ    ���(��tJ���r[�K݋%��m��L�d#�뼍�Lf�t�ԒQ�x��f�zyŽ2;m�l��X4ޓ�n������.7�[D�/$3���0q,����eS�U��$��<��4�1�#��N^'�jy��d۔5�c<�+�߬(���h��F���_=�kڊ:�y1�楓pS=�(MmW֝8�l����y�gʶ�:��]�Һ���*]:�P!���.7�q��2T�tlta��@�1.Y �//Ip~꼧st�b*kf����y9xw,�qG���xy̤�E�Q�KF�f�i�݇aUĀ��M�vEʣi�6�6B9�Ix緪.k�u���|Um��d����$�;����ѡ<��%ڃ�̝ax�\�Pdor
(�7A���
�SY�VABȑ*�	޼#�D�N$I;�ㅲ�:Q*#����p�:�v�����A��'J�����+��,TF���e2gj��^~�\�M�L��N}L�v]N�*2*�|�������7�V	��nA,}�^���3;)Pq)�^|<{�=KfR��u�c�wub�O�b������������%Nܲ�	"0��bSȪ�#ੌ��D�a����9;k$T �T�*���堝r�ǉ��iFF�C(���s��ׯ�6�h�g��#��Ǡ*���7a,�G4l:��]E����Ske�Y��ƺ�4)�\01�P۪�e>�wϊx��gވ+Azˇ�r�b��Ӱ2ض,衞DL��hV�ʜ@�j��<
"3I]ə)�x^EVQ	EF�m>bw W]�Ҹ9�U�o�@��U7�}Y�hK�Ъ"�y1�bj�tj�l����3�卺���}�Q���]v.��[w��4w:'W��/f7�e�Nc]qp���=Z�^�P��gP�x0j�b����Ƚi�r�����x'E�<WL�wk�x����U\����}��4?�I�R?bh+��D~W�#��QE�Z�����NU#)���6�x V����ߣ�����l�F\��.Jv�q������w�\]���]`�;�;�#���Q������Ƅ0K b��U#NK%�Q�0yH�c�X�%r.�-_���HZ@�Y���r��r���RM3���P�E��2�&�$�6&ke�Ļ$�P����z��~����sC��y��w�/�\CPP���\;��_�2h��xҾ��_���W��	���v�;?�q�1�'���[����,N�9-�R��uA�䷧���l#�E[�cw�W����v7M���F���b��f�(�$蕖�X����l���T[tT4ˁ���g|����[���ox�y[�N#dn�y˴�ۇ�;MI3< %��;�P��'�����,(D��!����ߥv��������m�.BuNO:��=*��W���>�aIH���������R3R՝СTm������<��`k���է�j5|t!�v����X�yE��t��mI5��d�+Z%Ewࣻ�O���e��(�����5�<q��O��D����L�7�
�*L��υ��O�v�����������?'E���J�4����ߢ��+h��ܚ�֓�R����</���Tج�V�,R̕�.7�Ʊ���	բ)��UM��y�V����|��z�9E��,���Tr9k���ΐ9x�
�.H��
4������d��d���h�[���ބR.��}�[��G`�y��BȻJ'*�Zd !�KU��0@^VJWI3���?8.V��GpN��$	�h~�x�ROu��G��~���;�L"��.�4uױ�'��xy��M�{ν�wOt���I�ŗi��j�eR���{�]��	oL�'���2LU��7*�fQ,	��-�h����3P]�bĚHF	O���,�nU�ME�j��?��iN�h)v�;K�ͭ�ʂ��2J�ɘ���9a����;�3D���Q�;i�b�'���SIQ�6a���F���/#�;�}#�z���//x/2��IG���N�pd�Wr*/��;�U+��[̼?/��9���w΃�y���d�v�����g�6D�+�ܫ�­���p�oLr�8��(1���'y��d.z��4��!>�g�|U[��V�'�Q����=�g�Y#ϖ��im�8�΃v�f!x0�� 0�����I���M�$�W��#�LF�ϯ�/�������b8�CJI� �$�|�z03u�0�T�Hb�9�L�ܗ�9��Z�c���}k�Е^!�<�+�GBz��W�
�q�%
C_4�!�BzfT�<�"�⍮���_���v$�[�Seu<����(���g����]���K����C�ܘ��h���R9� ��������XU3�	ϫ���5�2�f.R!u�A�e��`g�4�)��r�=�𠐊�c	��D%֡Ih��w\�uS��jeJ�[�FY�}	��.p���x�����z��i�s�5��N��6˚�P���p�B+�<�N��vN:�^�3��<��W�))�O9�1Yw�B�ʪ�9&�mDa����jyp�ȣI�~��G~Z^3���,�'�"��eY;�(�Tp$Ӻ�vi���O#�����9%�m��)�f�����|���$�4�����,��o������U\4`����C܁�Z�N�1�<�VY+3�2D�5���-j��c�@$��=:p��!���yT����9SuxY���2����AC;��X,e�m��&�tK �B��*��:w�1+s�y"j����L��߯2�4:g����Yf]1n��L}L�)x3-2J�@_�(���r�i"c��d!-@����+�1����f"��q#8���1V��j;� �Z%��[�Bl`�/'�x�,g�wG�y'����/���g����RŌ�@rOs�D6�s�*x�D���
�QB���z���l��?�����3�S��s�����&�3�N;d��0HMulC�	���@� ��.��;O�Au��f����؃�J�����,�oV����VɅ�d���f����A^C+�k�ʻ�G���������p�j ���q�
BM��t����%:A�����1z�3�UyG�Ֆbx���o�ٶLWt?��OFNݦ͉WFG��a�fr�,��Ez{�\2� cD�ފ:i����gUAsƈdM[�$�vYμ�Q�MH.���u=ۦ�����e�փ4�JO���A~�|�B3�^�i����`��e�� /��ۈ�z�4Wə���&٥M��Gˣ�\��k�ZI�g��Ԛ�f,��k�������?���'�d��9��+�NL�' �����#�HYS"�G�.О�(�T�D��x	.K��д�3�����6kT��|�߯�_6smI�<�5�J#Q��!Y߭�R�C?◸���ޑ�7xCO��5Ŕ2���=׉fs؍p����Q/�;J�����b�J�q�(�ad�E���gŔfa\�Uް�D�҈0\N���b}c��|?���W���'���#��'_&8�˗3��
圭��do�,5������k2�s0�į�:�O��[ {f������7��G�â�p�w*�V��<]h��Wm���m@����w�%���D6�ˎ��l��ě�I�2��i����ۤT���o���r۳�P�x"$���k�����O�>��)6���T�̹��ܐQ��Sc���{d�z�p@ϞQp��2�ֱܯw���a��n��d�b?`^�s���`�MD9��aê��:��5XE���թ$�|�MF�����p8 ���LB fN�?E��OI�y��	�&�l�,_2�u���c!���H�0g��:��Z6dT,�\��jy�=uhڝ�y�`
_�ك(hm&_�����
��������&Aj�����	7~y��8	릑\	�0��J>^pT-8���{��)��c��q��i�b�»p���l���!�,�K�@K��𖺇��|i�6 �i�����|}�mm�V����ee����yBW����a���%�w��_gl�/�    �@�XvpkH����$��0��*��W���&fgB�`:( � Yf�����T�"S��� k��l0sH7��*G{0�
��o+f��ͩ�+�H���*"�͜~.߾����z�E�1/���ѨY � ���bH;���c�φ+,lE�ڈM��Mþʬ-���f�D����e��i�K94�션���D�t�9y�1�:��F�ƌ{ͽ���f�QVٙr�???�;&��J�7�*MŁ �h��K��.6ҧ�6!RKD���)�7���"�W�#�	3�T$F��׷#th��<�0]'d���XM�$S�_�P���+/S�����pmz��jAxN��}�C���K�`���b�������Q�ՉLy�w؄�gE9x-a�VЗ�$O�.f��"�	�3�>X�Q�l�s&����Hε�+���2�%@�N�>p�̙QxRE�Yw�� E��cN����>D�4���y6M�����Ze@�gY����"L�-<N��,�agv��P�~�w�Z��_w"K��	�	�������!r��Ҙ^ҙ�CJ�t2��Uج�H�8v�4QR�)j=�a��1Ɉ^Y9+�V�6����2��>[tS��6m�/�>k�2o�>�1_�vS�j־�U���n�@��Y��<�o�c�1)T�a��*>d�"(�i*�O�r��*����S�sW"uΙG�����$�r1\��햷�+x���b^D<��d�>r�q65����qӪ��4����,8N!9%���ڬ!v#U�B�k+�M.��q�7��ӆ��a��RtI*���6����%�*���E�^�M�d2J�����4�c�|s�K'����b⯈����/	�9%b)3f�R���&�R!Wiŷ�=��Y����,����dT`�>��{�O��W�#�������%�+�dU�Bt7f�ij���*��jt��I'%0нp��j$��C� 
[}J�ÿ����H����	yz��ƒ�}�<,s���<��c��ww����g���LWXEG�kT���;<
n�.��� u�C*G���|���j1�����Hl���qȍ[�e�	Aٓ��w�,J���J�� Tb���і�
�@�����=�Eq,ĝx��츮�]z�Q�1�W��~5�e�9Lq���Bl���e��YDSQ;��_���n
-Qd+�8"�SE��2*���Ϙ��ĭ	6RNm;6��C��<V��rU��ܢ�����:e7E���3@���Y/�D�E)�U��N�c�,J/�������*���t;�\��-G�l�I$�g��lu �Vy,.WUg%�\8��'���~�����!�*E�f4Yn�s�zĝ�i�̚nD�����|�lr�:M�6�\�J"��_�W��;�A@�>s
Ω�F�[g��A�償�8A9���2j��Q~��Zb��'���}�58$�'#8��D����:B��)Y�)�����?Pw��,ʴ��g�L��;9qe�rl�Ȝ��Q����6%��S�3pOUt	ۂO:20R�y�PC�j{Y;���#(-�A.�w^[JPO�Y���2LȘ� ��ŷ|o˨a��������h��Υ]I�j��mJ�o��E�!祅ڲ��D�ۜ�	�UVVΫ�{0��J�\'�z~@s�ꬠ�uJ���d�A�Rjl}����j�i����)�DvHB;�.�?@�6V��.�a����*��h�_ar��>�;����%!�P��HZ�������:f�V]�f���v����?"G���DL ��SCR&2"X��9yua[����V�=���n�P�k8�1gX-��/��$������C4��*C�D��(iS�Q�~8�o�2y��*"ݳ�{=Kf�"	��P�"s������f.ra� 0�l�o�[m���	�
�N̤��s�5߃~(��f���y�4��v`cR1
�}�KL�/��$9����2�gN�de��*g�F�_�k��ʰ(�m
q�rm+��S�p�I�P%r�;ZP1ʄ��F�Nh���g�;��S�#r E[w+8���)� p��m���xS��GA����f�	s���$E��¿6�9I&8�$4ٽ�̚*��Qkvػ[�#�NCWD�Cc������k���$�S8E>�iJ`�҆�1�Ä��Q���e�N�IG�c<�/��Ѕe�z1�TR����y��u3���e�*�f�:Kv+�+�ah��8��$��RF�b�ޡ�+�Y�B�L{޻����9��W"v��9T��y�U�l�n,���Qkm�G4��J����.���恾��K�\zk��Bw�n�&�$M��e��"ir�*`��9�6k��?����.̬��G����#��Eo��$�����c����$m�M���G���ט������T������n�?���� �PPi,_���Gj,��Ʋ���CLb�o��禠�����%��rœ.�J�콬:h+3=s����Di#���4A��&ӌ 6j�������O�f�D��Y##t��p�m�+������$!�P�bA+��Y>J_�u*+�վɂ�I8���g�B��RN/��-b�O:�DZ�|�߃�0l�⿡�@���`�+Ų��� ����Q JRJ#�}�N0"'bU�Nə��]ʶD��Edd���,��81 �Fݬ�^���-�ҚU��"�mr����� $��w,��Zꌧ 6�z�cK��q+g$rW���rq����%�Q��*������ov�ÏWq�Պ#���`�o��0+JhEu[�r>&�8K��6�n���"	�է�m��Z��L���Uo���%��MhP���R��&�i��򋳙s@��%	T�dڪ�(��������nhO,л/���!x~%�uR7%�/����[P�$X�L�:���5p$p��y�մ������>q�Q�د���r.��?����z��?� Y㴕9��#A�c^�n����}g����.��"�z���3j)m'�l�������x�Cv��q5*��큯έ#P����9���W�0��w��-�b�9�M���qF�#�Cb�ԇ�a�]�u��(6���s����<}�&KH�����?�ۆ'C�g�.�,��O��Fg[=)��ҁ�){��r�R�5,�	���c��� �ݙPfD�2AK�bϦL;J�]f�y}��<7���~�i�.��@��.�xݺ��d|\O�춼�rK�.uC�]���ᡡ���a���C�D=/� �[n�*1@V`WK��W=H�Γ����pu_n�yZ��TC(���A֙K�Ӈ��dl��l>K^��V����Q���o��%�:C��a��x�Hɸm���}�&Z ��5p��|�F~	���Og	,k�u6دG�`�
G�ӳ�KC�5t�a��+�g��J��!��[�OgiZtu)�5�/�?�l��5Og�ѱ�ڴ�I�FF�$9���*`���s!ZFcM��?ۘy&uYW���&o���Ų����(�xRM���'e��8-�רuh����1;��°Z�ƅ�jZ�)�
|��esD9J`���P~H���H����T��W�����0��J�Æ	�Y�QW�{�̱�׷tgH��uMiS1�^�����#0E�eD4A\����IG)����r��l��Y�SG��i��i0hu�)����0Ζ�Q�9�L�)N5���k
���tR�H>G1'�`i6�P_8l'pP�/%!�i�(7lH�R�p\:ғ�N�r/��MJ� �ǬӧmNv�����K��e�H;���(ZV�$&z�l{����M���7�����)ҡ
�D�r�Ze������ިI5O����c�Q�k�Z��u@ړ+>I0��!���νb����l��g���7s6ZʮQ�ғa�?X/���+=�Ҕ�R�x_�vb B�}�Ҡ��jP�	�]��A�����KS_�bM�'X���'e�s����fy�<�f3Y^�:M� Yj���T�'�~�E�Am�f)5�IP2 �]llr���7�*%|�*%�~�a��v������S�Fw�
� 
  �-�	��N[�q�7y9�F��k��l+������gG���|���ះ��x���x�,���c��U�4��"�T[-�$	;���1V����f���e�$���Γ�P�-B�d�k=�s��$�)NA~��,�|��\kY��q�%h��i� �f�����Fuѩg6అ��z������-'���׵�����Cǃ�ұ�|~z-���,S��Cp5��(Ֆ�̯���$a	�<ʪ�%zQ�Ȼx���we�)������/1}ph�3�S��ΜזQg�1�7��x��{���}lB��H{����C@z'�^��~��n1����"�� (��������m����n�K%��F�B��Dn��Gp/y����,�dF��)�9�WY�H��ahY,K����n.�b��p�b�	����' #�Yظ8����PY�	5��ˮ�SFq�)��1�;ж�b)o�<�@1�S	SҼ���_mR�x,���ϧq�~�ÛF���Ϭs!�s�VDI�是�C���'�O���?��.��~#R.��~xg�;>��@�2_��aHrټD!�qb�p+#�����e�mۼ��q���==���z�g�\��k�d�<)�I�S,e��/����@C�K��9ݶ�jĄg�6{H���f���3�|W]�T7�ԅk�������DV��erw+'���:w�F�t��HÖ9[,�e\p��&� Xt��#�Rn"���SŜ]'y�d*���1Ʌ�-/���Hu$���.����eR0�|�?w/��ฆ��c�^^��t���E����Z�bV�~?<��"Ǯ����s|ǳR��������g��N�B h8L��p}�3�[XCd��Qi�X�����@�;���,�L���N|���]⌱�戜�*b��LC͚�o.Ü�IG�����4�ۣ��"4y �}�2�?�=������H�:��3Ķ9,�����T���$��?'���+�D��tA�K�b�>i����H%���3����n�r��#,�߼Ġ=X��`��ʋ
\�WGFuZ"�(Ĺ����D�W���V��tFƐ��

3g�%�}��t�"��mJT�<�_�w;�7���[�>0�r�6���$'h�<,\����*�>���6�*�m1(�qn��.M��^�qg�N��~^O����Hm�kiV�ƥJ�(���vg�)#��h;���q��������>1��4yS�M��:ʃ�!Gk&�ru��Hj_L"��K���n�C��jZ�T�*e�2h�=|w㉉T���%����&qn]U�<�IG͂�uBL5\���C����?Ɩp�\ Kc�1<�V���/���L�-P���b_p=�%`�ݖ�%5��ږTJ�ܴ먰wW�P�����=���@��e���l��H�gW��"�\Gt��W�	����J ��3�p��X3����$*��eB��J��QcA���ſ�N��>��BK�+�F�I��Œ����khVc�z��S��O5Ք�MxZ���V����m��u���+qd,=d�8�}�^1��j�\�P)�7{��GEr;�,Ǥ�.`'�w�
->r�,���,HM�!!(F#�^?�o?E;�9�yb|R�M��%7�P�7nN���-���v�س=���tJ����u�UF15�dD?<E3
�"K)J��D{���F����}��m��\휋X#]P5ι&����-����dq��{����9�G��D��s>,�Cĉ�Z��q��v��̐�$��?��.K��a�Y>�6$�&v���&7�5TҚem���IG�� ?,��i����U�mЊZsx���e�b��py\�B�S�7N�;��n�l���#�z�U
�CY��跊)t7P�Qkz�ӱ���x�0�|�֦��Vx�8���]��B�wԓ_\����<���a��٢�T��4���"�{i��/W$sd%2?�{͜m�����Kh�P5�"�g��t�(�L�U:��p�D]Xs��rk�T�y9c��(��U7�w�[n�06���b�����\$w=G2�⚽FTRb���)��/X���q���*���������V���4(��-�@!owK��k0arQ��o����U�c����Sѕ9��b�no�M��8��ݹI���<������y����hMOK.��������S�tv�u�~�����I6h�U�n�tT�D�J�h�RK��wN/�&%UL��z9��
ͦ����㣦��˓i�%���"15y@�F������}��%8[�
Ɨ[t2{c+�yC��a/T�۟����#�h�İ�Y�����<1��$�ٿ��+R(���8�+�`
��Jh�q��i1}����pyІ�����0�S53A�$�5�9���#����I��v�s��[� 8�Pu�;Rg�\�It��N��	�w�g�AdO�i�!h����Y�f�p#��6��iouT.n�+Dp�(����CZ����衏G�T���M�&Q�p~ v+s�����8z93�L����?�����dq��      $	   �   x�Ő�N�@���)�f��m���0�6��.nH".&��Úx�l�m�4���_o|_��ԝ���i� c},s��BXu`KH@��*+��\�XJTa��۲p� g�q�A�a���Ľ�2NE�=��-Z�d,�k���S;<w�Z���v�����f=��)D OS�2�[W�>��t�8��)�mͦ�����C�6�j1�<�pYi�ؙ��i͐�9�_�f��f��yS���Z�ǜ�7��}`���      	      x��}�r7��3�+�xn����X���rP����k���;�^�3�BR>�����^�;!�2�N0��y���r�����������r��q'鿯��;?;?]^������L0ŕ��~)Ǆr'�=sV)�l���竡{`��4���O���1���pg��"���f�~y;y}��v~���\0����������߯�aI�1���њ���ђ���
�Q�FK���?�������ߝ'g��L	6�{fT�]��5���p����iVy���?����x��)��v�r}zM�/,�7�B0iO�3/�M�q����&�w����+���������t=?���r��U|���/U�s�ɹ�tNB)N߯~b�i�o���}`����`f�׊NS���]��|�r.X�e� y�`�|2ߪ���K�������_�/�'�������}'�&�c�7���;�d=�r��0l�P�����B��X�����L_'�qrϤ�[�d:?azE��_�H}�A�|q�h��o������K��3]�s{��Jkᬡ#0Lh�y�a�7��~��L���v��}�-Ǔ��_��i!�[ƌU����KaM�Z���voY�s\��}���ۅ^1&�8?����??�a�ge}j��JiOGMR�ri����w[�gU�������K��a�Ih�4�r��n&�v���d����E-Ι�����}�9��-kc�j�"�u�~˭ ��>�f}�}��E7���Z 	���$7���LR�H'�Їg��g6�gɢ�I�eIK?S�n�C��˩+�)nh�}EZ�ȗ-W�}��U���Ѭ��D)����o�_/Q&��v~�_Dw!�U��[ᴥ^������D3Ą·s҅~��k!��i�7����n��=ܭ����UWt�V��$=�]�\m�6�PP^ПrJ���|��By;avy{ǊZ���*�HC{�6�ԈX�j$�Q��g�����n�5��]ޯ_.�O�u7$v�~{	�)HS�O������U�_�����l�O7��ß��R{m��������n�܈��I��?�����Q�4����&3�+e�@�M�r�@oU}��+]����\�Oo"�7��0,,Ccʳ
���	��H�|���}����|�+.h,�,)h2%�͗���1���]_~};_ ş>~�������<_h�I.مa�IW\z̘������B8Ȃ��O/����lu��$.	mR�^���N��k�S6�PK�m:A�Ĥ&��F�*C��E.	�v2�I�e���Œztt��(ۙ�>�l�)l��=�k�]�\�c�/O�9g����C!�<2A����Ӛ���ByN҃��i�<�F'϶C^�?�wڈl�=�~�?]��%;�q����W�(ʐ�Er�҅T"�N����_�f/H��`��%+["�?8�o���A�.L� �i&o���Q&:~�:�g_��p�)���i��3k��4�d�74�@�dM��*��#:��ׯt^`R�A_�p�F�����&�vκ�$����;ߟ|�VM�%l����Ǘz	}���� ܕq��ܴ�ۢ� !J���j!/�U�XR�'�1�t�+Y��:�#���Z�s�����]�]��(��.��kG��UqrA5��"�1�*eت11�������+<�U8�&&�wBl���[$�ke���O˻�Xͣ����;��ik*��9a̐���\�[;h�3���aKY:Wc�L�v����X���!�w2<(z��a���;ǖv2}�˗�_�ߛ3�yB�S��&�3X���tӆ2�Wi�Pi�����r���4a�AZ[X:W�������}Zє{��ǒG$�I�l�j��Brt�\0(V�L�ǧq�Wå��Rp熓4�	�G�cĐ�i���sR��3�����qY_9�13�gvCh.��$�k:�w�*T��.�!-��}�uύ'`�=A*}7٭ܚQ�!>;�<�×�	c��p\�}iH����,��Ą,}�A�HI�kwKSL)����G�T�	��4������	�w�>s�.���Kc�e��3ܸ4?���T1!�0%f�0
��3<�!]��;c��#eg��ҝ������F���Ǻ0��璑Cޜ����0�pϛ��_-j(h�/�o�4���0A4�uF���̌k���v�������Q��d� ����9�����+��FlfefY�Z�&Ǫn��7}gX��������$�����X��J9K!�a���d|�%_��#�92�����#FFwZ�nڞ]��%�:�a�$�ˑ��p\���0�u,�ilïCOY�W/�N.c�W��v
J��줐����6�]6oox��/�Њ����d|H_L���pu�{�K�vB��]�}����c�����,�ýn���oInU��Χs��k�g�a���n�C Y���ݿ��g���N��z��[o$��n���{s�6[�q��U�Xg����j�%�F��Ͱ�pqV���݋m4�wZ�u�M�>�}oy�̕ā�}��$/���:�ĨIf�[�a8��/or���_��S��qt�e�5\�É0��p���ܜy�{;��?��1�����$4s��T��B*��U:������UF�����ɱ�8�"�?���,�����=�r�ų�#��)î���QK�YG����.���}K{��C4�Op1�̪g��3�����&��|~$��f�ZU�!A��%�BN*/�I�v��ͿRX�.��t��P��m��vVqD� �~�����3�&0�{D�,�/W�"٭mҒ1C���0M��?ׯW�4���+�-�/[漲��Rd��xOA�<����`jR�$5C�_Y[ä���*!UL�z� �E:�����-�)��1���I}��گ�tG�M�$��}��G�QK�y����������L�v8�zٿ���gӈ�������̄/�_��Mf�'CZ����fx|v`0"�Hz�X���&Ɓ; W������y)"� �eȌ��"ci�����\e ELYKҪY�@���' w�C�������/�c������Lb`9
G>�r2Xɴ�����L�(gۡF�4������������(�	��b3$9(>��֩jm�&�����g�W729����	AWId\M���&3��5�W�zL�7rt�J�v���w#imr�q/��X?�	_+�}vB�P���+?�L��/�ЀI.n�`���=p��`,�q�[A� ��4'����j�ͅ,�O]Е,��y;܈� ��C�}���#���ϗ���*~��V2�`� ���Ӿ
$�+��/q$��Vp�I������"z��ڳ�j &( NC&Z�٣w�����0I�M	z���ϻ�"�Sq.�g9�Yk�c>ǈ7��Ǆ�$]m�F�K:u��k�u8�C懌z	��>��{�x�<��i'|� a��}��"4w'�	K�|�P�a��eaI���kݚ�M�z���_Z,C�u �#�ش��F+HTa��hd�#���V�^��qv�v�F����^�p�$9��u�h�&���lx�)9{��I�9�s61�Sѳg��c#89 !d����I�kQg/(_q����|�Ғ]��b�-��0I��^>z�˟	�y+�wB��WF2Y���a��FC���U�i��}U��J�1�%�j��\����a�ߺ�"K�``"2�����]�J�M�y��l��a�.�O���߯O�nk��]�2x6�6
3�b�s��~�
v�v��]� �1�̅�4�����]^r��A�'!��3�i_� qx[O�g�h�h2���kf�Wɴ�y����2`�U�Z; �Y�9;>oB�l��E��nNH�n1�ڤV/��*�Z,���^�O��[�� խY.��\����#�� ~�e��vN�?�YՀ��1_$��!�/���"�F�%��B/�?p���US��\�e�P�	2�$���D�)�`�!�	��K�������ĵ1�T9�2麮�\�[�v��mm�a�܀��a�.�h�r`�l!�B�@}`�К�M����Ms���.��1�3���@Υ��
    �q��d����e���u�U	xN)�|.��Vè�};\^:QM��˯�7ڌE�JĔ*��z&կ��WÄ�K�1���x���p�31�.eLu�di.�6@�t�[[�#UB�^g�?/?r�ɉV�ZȈ�n-"вy�G\��8�
�2vj�k~iy����$����� �!�A"K���
Wh�xl=����e5�N��4�5Z����j�g>}���Z�,6�F�2�ŒI�ȉ��jm<��Nr�ʥ��Vi,k��*=�&��gD��u^9�%8�1� 2���	��Q���M��W�����3I?пP�#�Hҍ�\��'��g�n�w�O+ey,������H^U"��MPc!�vb)�����w���G����Rey��kO��PTͦ<����JH��[��V��|���a�a;����s��w�0��F٬`�G�v V<Y�)�R��1=ޡ�2(�|F��/:����/UX֭g��b��$cm�?�YgD�7 ��C0��C��4�� ɓ�6�ݸp}�y�(W.�4!f�}����S��Z��r���*B��ֈ��,#���=�n��ީ֫�t��AŞ7Z&�ø���|G�ވ���iTl��Fց<��H_X�2��é�DOkf'O8u����~��ۻ|��NB:�4 y�d��>;����ù$�*���~~#��s��\s��}�;�1)NM��m�RX����vQ�mu	��z	w�>��q͎�=	娒,��+�k�����6g8Y��o�<�e�y2�t?��GB����]�y��r8��%f2��62����GZ�����%����[f�~6�bд�FA�#rc�@��h��h�[֌����k���A"��deд4�r��Y;��PF����[����+q �aj
����o���Ѯ.Ù/~�����hҝ���x�飣aH���㪵s.Kh'��u�I�-=����7ȃ��5$�hN��`u�e$��=�Ϭ#��E��A�r���My��#�!ӯ�NISAHF��+����U��	��
Hx�Ɂt���7{&̲�tu#L��f)���<�j���V���`&�-]����c�l�W�PX��b�-	(�nN7Z����V��8�&{ K�L�Eš�xSa�&�rG���3	L~.� ���k6���(Q=�yˤ)	8n)����Ťh<6���),�c�+��)��� �F�Ί���^� |*D�8�_��N�w'�.�3-��O�	�� p���	�B5�,�%n�ޞD�㬰۝:W'l��XV�	m�SuJ�H�\U������F�#���qAjJ2���n9��� �d����,��"���(�}�
q��B5�j�Y�pRH�#xa�
�j���r)�FKdb/>����a�ˣ��>���P� �e�fQqh*�C��-��:��lue͌�>���]Wn�t_�N�1��JVct	���#??{˙;�8�1o�0�7K{��^;�����F��\}��ۼpd&hM.2��E��?����5!D��Kn�����,�pI\p�������/Ƀ�A�P%�����CIzy����������G�b/˔�HsXE���v��NE/R�L��%Ǒ�&~��Li�<��	���IA�2����ʢ��ћe��܄���F�d��P�$��!�&��וu��E�,�K�b$*��'̗N��? @��tNgR���(ñ�_�5�A���BpD7TU����W�#_�{��u�I!_~#:qdN�=G�s< Fv�O�]�l��櫡-���Ql��A�!vŶ�^je+�P�aS�TD�A|��2�M�&h|���B
U�<�43 )�1�%�P�b1�Ԉ1oL���h��A�f8V��! \��y��$�6z�j5v�&�-��Rl  R� �ɴFD?v��1PHJ1�<&L�{a���O<�sH؜���t~^�o����w���k���O{:& ��b�A26ҙɂ��,U����d��qa��Ih���J�2���:�d�&�'.��5���]��=b��:�m8Q�D�"sK��G��̈́;���nm����p��#C��C�U:jA
�6����7�\;�M6{����-���{����Y��`� d���Ȱ�����'��Ո���Sx�/WWՏ&{��D�rQ�����6�_��+��h��%�
���qߩ�:F�.)�k������S���[�B'���H�~1��٬9��6l�v�����Tt^ߟ�<� q������.j̄�z�Au	�o�(v]��(RE#v$2-%��� �@��A�mчL9'#C4��w��+�YA����2�F� 9Cd���G�tP)1}	�8�O�2��9��z �ţ]��D.�[��lTu[>ȭ��J"YCz�ԜA�Gt�|�$v����k}W���ʇހ�0V�� t�<&z�}�|�Q=KB��F�X2Wn�x(l��<���|{������ls�Y/H�8�c��Z8~^��R����r�g}h�=�R���g7U,��s͗�|R�
\����4I!�a_!j{nB)}X�`��D���� �Z�
:[k�'\��V��;���y3��5X ��t%�|T="���>����u��zn��ꭧߠ��U�0����f5H�@�mŚ
�� 9\��.v�8\M�.��CNG�#�%��y���BK��O�A�F�'�Ǐ_�B#8����R���<$�w�Cǁ��gPQ�j�?o���{�df�e)��U*e��K�Q3~e�������ϊ���ff(�@���ʁ߶�Y"=3��JJ1BS�.�}����ɘH�!⇖G�L\��鞚��c�Q<�k�{aB>ȴÁ�!a��u[����!V��~N�a��J@�"A�k���ʱ��#*�:�Fx�d��z��v�j��K(��2�^�c��E��}�����D��#_�x��1~�����W�{7G&>?��	��-`��q�նW�GW��Y����	OV���h�;I�2[�COȺ�����[,s*�p�tZ#�O�u<���!���n���S�/�g�Wy4���L&�s?����7�S���h����f rk�8�Xr�����C�0�2�W
�i�7��2;viV3�h�!B��h�t$<ّ~>#��k�۹�"l�u
p� (y��ᆀH%"����s/�0`�Z��K �3�����U���Ax��R�NVI�3��J(���gM���(x3�&�:���I�6èa� G��m�4��_Ѝ��������%�C�SH�ώ$#��6��TB�4�ؕƗ�����1Ҁtm��g�p"�ᬰ.��
�DU������ek��<��(�� �*id-�I}KVab �أ�Ϭܴ^9t±H�fz��o����S�Ҩ���+O?�����z��.�� �B�`� 
���"������X ��-�Lg�'G~g��(�@�Nട���Hq,a�2��w�v��jQ��$���'�,Lӻ��j��$������r�m�;_�䬠x�	�-��m&�.�'�<���V.��/��ȥEu� \�v�x��'�;���,pS�����ƹ��]ϪR�ū��Έ�G/C��t�]�<�@��V�fk~��Sl#���9&B���;ZW�}*O݅�G�:�SHC0D4=\��@<Ƒ����S1�ʗ�	����D"��~��|�N���&�i��� z#��|c�t�<���	��8G��#�St�BC�>`��ChB�����B�'��{��n7`��mh�{���ڄ��Ý��Fb�B��.%q{f�qd�譍�Ɔ��D�[��N8椬�̏FQU�5��mH����3�����;�O�Z��,�
�.M��L�&��2����`S�7�����d����y�w�r{VC�U"s���=!a1X��޷<�Os��UB�qM'n��L�T�;I��=xdԬ4BpV!A*�>͓�ݤ ��rݛo���5��19��*�pNeL��Z>fҜ&5=�����@�xQ�%e'�;�����N"WtI~�q)���	�|2    �fM+!˝�������i��k��"���2O�\�R���_�R����.KV�q?��gSw�U�=��
��ۜ��&�)�`��T�l��k��+���ɥ��� $wKN��t9���+j:��M�Kߗ����������l����1� ���'���Í�1��� ��#�15�%�:,������x�D�nJe�@6}�����B���Hy�!��h<���AKXC/�y?R�_n�B(�"�(a�{�F׉�!h����M��/;5±?q�b�6[�K,��p�I���֡�:������?&�;�:�`�7����ަ����7�l��.���JĥB/�\DB~z5����|ȣz>���JC�^�K�uHb$�G����aK6��h���'آ�r8lƾ��m�E�^�᡿�6|H��i[��z �U���N�nN(�y~F�ߦ/Gr�6�r��%P�m���1��ڜKr� �Y��\��[=��HEf����L:���b��n������q{k�e�&3H.�׀oGw�ۛ��7�Ǜ�?o�Mɡ�N��?��e���&Ϭcg��q�t��{��ՙ�����"p��g�P
�5��V̲�U(����N��P�G��=�>��`D�GI*c��L��c�t��Ǉ���~��.��2��A+8�Iu�.-��;�������P��7.��v,�P��;g�eQOw ��v�*m}@O���r�qz��������Yoz*iTw��xd��O�v�ջ��4�����ȱ� ����V&,�FxtX�wS�Kog{���/՜G�7��
�R�,�+��pYaT�p�x����0`=�Y=	B�#Y�dF�.~d�ѭ�iP�k<d?*����l-p5��:�"n�~-Q+�� �{�L��x�����>��l}���c����A����2��䍐`g��@�Z ��y�5Ò���W�H -��懗�|�r�����_��u�I0T~c�0�������ι��.�ƪ��]�f���U0:�vI� 	�j��l�z�A.���*�m&�VK?ӥ^C��Y���݈����p̒h�-���p2�C,��7��z2;X�F�m��*\ݽ(5��'��dS����`��'0Ⱦ7-j�g(ۂ�� ����H��̅�}/ �����k����AbxHO렴��,ʃ��VeU�w[�a�6H&T�m���ӡ�t7�㓭��|2Tk/�\�U����x��N�$�c��i����`�u7�լ���KEh!��;IG�r��%5Q�	hӉn��R2 {�j�(��gJF/����=�IL�s�!U�C��K���#� ~����yl�GlѢ<��r���:�!zb��>�	��'�ލyP�L��o�&���-�d�8m�6��q�E*�<��� x�H߲H�lY.�X{��z���s��<��Sdd�̜�v��W�^B� �d'�-�%��)N!]8�Е���`3��Ü����V�VfE��*�=C�����=6�D[ޑk_�HkL^�2!�I���I�JOKT��u��8ۺ�3"����-����,	d'�)���UQvᤶ(�pY,�����n�X�F,�V�����Aj�ʫ%Y��g_�Q�EP����������,��������;��"�j��b��#�؝u���ᴩo#���׍N�d�8����a�/����b��ٛV\ޗ���u�;x�uv"톎�Q��P�,�*�Dp��(ng��̆�yW�iL������qFVoi��?&�V�p��&�T�g�n2�a�h�#��e�3���M����v9v�P5k�yu��b8���	vH�>�	A���I01mU�mdj�fي��*$��+��t��"�V�(m -��(�lc,��DH���s ^���@�קy�d�xϏK��W|�(���ɰ��	_Ґұ�^�������i#�*6��j�ˣ���F&���m�Mߝ���5���uN��ɻm�6y���
���w�=�P�}��N��� �ʂ�A�iȜ���~9�D��b���>!$nD�Z�t҄��Eo�:�1ږ����-�VhH\�Y���7�uֺ�I�.X����AN��&��'������[4�.�Oo�Hl�N� x� |&��J��O ����QXtPtm����8w����%FT\�E��-��@Ex�3F���&�`���S�P�Ne�E���i�(��q.�X��,c2�����@��d�wD�.��Bl�6�`*6&����z�?�Kp��X�)7�C���C��U�V7�R	�7߮�|���r-Ƣ,�f;Ef]���\WF��/,*~̾H�<(DX�OR;ȏP������K�8�o����P�;�����1I��84i����>!_��� Z8Q?Fs���n�}�"��{�(+{�l+��>�l�,���G��]-P�!���^�d�����?,�qC�$��I+���t[?�Xz3G[�%�͡0g�_ՍҰ�թ��ϥ~H:
|�ѳ�1Lt�*��9h;�]�tɾf�ŘTmwB����!�)P���3��x�Q!2���!��~�dh,�������r��a&tO5�����4@]*�Z-("��d^�����e{Y�X�����+J��Z}>���|��9h�x�y!~���f�:�^�����!��ĤǤ&��Z`*��/#J�pP��	�:iQ��Ź�1F-jq˚�H$��d�����A���*r��ko��������1 �Y쾩	SZHr��R����T�f�9,zo�G�5!q�:�u��=|Q���"���"�.�%�]���|p���)��:�r����� ���c'�Z��l󓲦��Mӄ����F,�db�g�Yƍ����Mp�=�t�'��'�ĭ�(C���,u��(���̳+��즻l�-� ��@�����o��븦�Z�@�$����Ɗ̏�׀I� �JM���%F麽zn�[h'�#��t-J�h&Fi��#eV�-�ZU;2�Cmb����N4ӥ�K/'��sf�f��0��l�iF�X�}��:5�����d�8p�v��װN� 4;Q�xI�z���nnɮ�`>�\Е:E�}j���a�1.x���c�Cl|-�t�6�x��m3I�����&�l������7�����%��e�\�E�:O������{�/c;�G�Ny-]�Pr�nx��*���K}���kp��x�s��A�l͐!&�A!�a��31���-ֶъ�@��d�Gv"�@����[<fDܸ?��{鐜UG&J��-*� �q�y�a![ ��v^���Kx`�S�uĩa�Tb�^�!���>��1*���' �Y8/�g[��I[��W�яN�Oÿ��p��o
eG��X(�B� 9�х���5�`�K�R.�?���-D�9Y��k�(� ؋�}�H����k致�(��X}��(T�cd4��kJ�+j��z됽�ITig�{�i�+"�wY�-h��rA �g�>��bFz�a���E8xA�������V������*��;�!9J� RѦ��ܬ�Лu-�r�6>a�H�H�jc�������ED�#�d�d����~3ȅsE�@+��9��)�i�5ċ���vH��o�����+G�!�����Y�%��)�!ܺ�X삉�@�������O�_�����9�����x�x�n��%Zװ��:�'����C��${K������Ҝt\V�q�-r��^���p���AҢ�&A9�f��������죋�6�o�F\��Fo�p�p���Vq��#�+6�?�6T��!r��(Yݥ/m'�:N�Ҽ�=��l%[0�?�������n�U �2�!����{�W���&hrr�
`�M�N�QM�c5� )��C�6��
��
��<mc�W��NW�a���b���Z����w��V�G�k@��}��q<�/���<��0s�v���Z7b��$��{$�x�Y���&�9�pg�M��]X����;+�;�BT^�b'��J%q1�Fv��"�+i78-���f��6�c�ܱm�h��w����|=�-��d�:�    �6���&�D��2�㑪�\�%Ej*Ǉ\a�c�NV?��ɾ�z(J6���X�n���ǐ�/Oo�l�s&$i��:E���2!�Q��AeZ"��m݇�-yl:è�h�]+��H����Eg�Dv��Qs�Ys��Ί]$������I%�^';�� �����Im��K��4�����G�>F��~I��0��^�2f�a��MI5�˶�`H+�@�a���O'5�_�<�|.X�a�w*��x������y��{��W�mm+�S�M���Iƿh���K`��	{m���1��A�)d_��.6�7ڣ��8�/�9_B_0R>�HRhQ�,bs����+󺉗Q*c�yr �wdۭ�g�tx������aU��j��w���܁�xI�ɱWd=°�k�Sk�0�aꌹ��FI�F��eئrw�3�l�}<��՟^'w���'N���j���vd�!1 �R*�v�y���b��jݤxU��u@�*8	�DA�V���� �\K'P�^�j>a�ƥ�-��aX��������Ԭu\�6�q}����?�>~�W������vW$���
H-�XM�Q�k�^�5U����t�s�{yH��d�½�q��}3^���"������y��!n�Pp:(@�H��I���7���"�#+N��x��?v�E��H��=b�B%�r��k�ħe�S|�k܈�}j��-�FI����X���	����N�����j2�8��]�)o�j�<�VԪ��q�7	�p"p)8��HV��)"���������� =5�ID��	${z���!�T-�1�O�Q��@���C�=�n����O�U�]`r�(�S���xK�Z�`<" p�#�\eǜ��;d)�� s�G0Y=���~OZ��0=�*��tl39�s`\W+e�#�b��H�ۤhoh'{��OW�蹉���Ƹ .s�nn����3��J��=�5x�r|��px�S;0zf�?{g6�-�J�/�����T��
Z!}�0)s6��F����E�����1D=��5�2b)/�HeE�����7���f~���Q��_g#�KBd��a5��;|fW�xv�{Jm��'�K��(Vj��0ކ��1�z/[��<Ø��ei%���:$��&r�fhS��_`	�6'���9�̬�� �Tt&����q������'I��nr\��qi�	Q{������� =ڡn�h.�&e+i�
� 5Ѵh���f6~g<�r����Zᮍ^;�0���n�
ir��_*��}�,�U�UHU<�l��` ))y#�\;�(�@w��B=�0������r�s�Y��^�&��m������(z$zH �v1/���H���CU�1d/댆2���t���P�%l?��ꮦf&�ۂ��Q����`D*+�<m�g_x�#r�F��6�t�gD��N1���k5��ܯ^r��VI�~&ǵ��[A[�vا���9�T�������K0j���~H�>c��/�-^��PT@�cd�q���������h\����@9�Ş���+�b�d�,�#�r&��z����-x�	X���g��ơ�!�׹7�F\����v�m��ܛA�G�1�C�ɍ�,��Ԅ:&!X����B���Q��ȄolF�b���tA��e�"AO�C��~=@
��˼���ȥd6}};7Eéj1v�̀�Yi9�_	Z6@�:A��;�TTw��tF��H��NvȻ5��(��/N`V=+����WfR��}�w�D�?�a7�������V����j�����eGX���A&��}Z�o�&-�2S9�<f��C���cM/L�P����`F3���kZ�[&���:ie���f�#�'���CC�\%?��9$I���;:Sm؉n��qߢS��f�G�fX��$������OQU��F�@��MYBQnC���Uo��� �8��d�dc�ALRo4���'���l!O�U3���vR���2=Q��U���8���������66s��k�YeA铙��`��'�L)�F��MׇZ�U9m�
���$\1��K����qv3��'�l�ȍ=nlͦcϵs¢��'p��l@xL�!��<U�:�>���po��2�KOߧ�ӣbs�sD�vӪ��ش8��>I9�����t�O�췝�J͑ٿ܂&�-�n�`g�Fof2͙ρD����߃%u/��B�[`	�����]��q:�ϣ�vHG�n˦�\�L�Ze�61��d\'�	o7"�:=9�p�.	];�R�t�qy�Bȁ�Duz,�:��gt�������9r�W�d95
�����o��#'�,����+ط<�w >�F�5��1�$r8�q��C��D�=Y�.dիU�n:�����˰��UIpuW�z�gA��X3h��!j��{�8#�"�L�'�i��:�eH��������_��^�=3�V���L����`����쐛2�����MBltm�6*�EQ�`��F͡�N{�X���6�v���;OТ�X����C[zƹ~�F��+O��g��?}[uϙN�y,��B��vEY{��T��
�i�9:��U����	*kONYctŴ��fݖv����������^.��iZ��#Du�RH��|-���m�B�0��'���"{�	�X�:�����v��d3����f\�8�H5���D��*��4����K0��v�E����ҭ�_>5�/�*��o��_˾jrl"�f>v�q��Iө��t�����f)��HN	��Y�yk���U�&5�вb���é�s&W�@ΡLLJ�����׋άӦj�f���LȘ�q�M�M-�f3錆u�[���eƘ``U~��a���V�������"�_���?�@�����;j��T���{��"!�H���Q���暝��1Z�Q̆�eC6L".�!�AO�����5��%u�����S1r�Hf�F�A��B���oC?�.�t`_ �l�r�	����׉�'�߫˾Uٕ�bS(B.��&R�����U��g�m�Ơ��wIO6p�1��.��m�*F���)9������B�T��)�>挏"4��	��3�U|Y�27&�!2�� �<[���2oz-��9_g���Xk����W%z�[�LX�� ��K�*�L��ϑ3���
/��>�x~���E��Y�CEL�p�qX)�H,Qp�=��`�U�9T*�)���L��w���u3ʽ�D�T��r�Ik��!�I�ll�{� ���f�o�h�$cb�i� E��yH?���e�*�A�<�F�@O�T�F�b�	K�˦eO�}�a��\�w��%�/]'���z���s(m((7�Ҏ��ª�Lo��Ld�3�D�g(K-�1�j�}�E����k	E�$�w~ ���Ŷ���h�`S�\�b���Qs���-T���["�=�oE�V�e�{�j��<�D)ɐ��#df�����e %|��C	1��-�Do+���Bz�/�qFđ�U�"���^[g�Vk~��x���z):4�#k���~�	p-ya��D�"P��2j;b?`J)����9����+X�)�J�5�u;�^�:���z�N��}�p]� ��ռp#������W�h�����<���?d��H��Z��u�!���>�� s����o
����J��1j��n�C����4�ۄ��M[�P�W�`�5l;����턺��)+FOˢ�I-���X�a�5uq��O]��W�+ů�Z��3Νzi�I��g�߆%�M�Uv�dR<y��tc�@�atc��+��>��o��A"+�c�la&��%�%Z-���I�/�}�p�ߧ3��=LH�;�@���Z���$y͹�Ǎ:����8��3|�m� ��2l��5�ǀ^L�}?r\b�Z7��4<�����PPճ'��|�J ��S$k��������5rt��m�����ƻ��v%	�����:�1�t�4<��EcI5�*o~�!���}�����ۈ���t��"�tqj��8y���2k���h| �  ?�0cā� μ����d=����G������͏a=��t7jf��bل�*!1��ts$T�	�RU[�K-�,�Qn�*\�H�N�ь���� �����P��c9�_H\o3�)�U���G`֍w��JdJ�W� B\:�C��F����v�֦��_�&�sg�q�r`P;�g�iDA��geHڐ/~F�5�eZ�����U�\K;�6ÞAVpa���i�s}`�B����V��֙M�GD���&����!hq=ښ��x�E�v�*I�#CGp�~"�W�]�i�ʦ7p/�2�T9�E������1�]y�c�2�j�%kԱ�G�'� ��]i��j���<wHq� 䢘�ǘ3�}:��]C�u�&��PCq����ީ6��r�LS�C������?�\qd�n��[�^qg�UjR�>��d����r瘭z�ңO����0���1X�
�h`�r0<2	�ft�K�f(B�����K^�wkk�b��2�t/O�i�2^/V�5ÿs1���W���ȜQ4F���~r�����H~[f�j���W1Z�2AҐ�Np��"?�7t�dƚ�_����hM����a�S�̠�������{w���g�+�z�g;e�4�4v�j��u�$�,���!L;��|����&�ڒK�4�p����=�
��܂я�Xa��c���h��l�U���[�F#�k�E��b1����|�l��o�R3���s��v��L�Ѥ�͇oc�©��iX�5;��c����[�e刑g	=MO ��s�vַ��Ȏ���'���q2p�9�%������&l\��ǣ�򜞗�wY�������zsp�rAzY�E��fmT'�m�uc��6K�vl�@�K0b6#Y�v���C�z�-X^���6����2���N��ps#�ph�JN�=h�G�@t!Љ�}��R�<������<,�����(�^7�]7�Em��"^:O�~'����"�=�T���>���W�;:�����T$�${^�q�i:O�Qe��o~��Z`�"v,�!al0J��>��);~�%<�0��̏R��-�Vӈ�H�a!f�H���q������WhIw䐇u�����b(!i��]S�2��.�"�������^��î��7����o��@>��� ca�bsQ¶�W�eq(B,�&�>&O��
�O�=I:y�^ȭTq� ��6��.?$m$Z��*:6�8��wh�`�k܁PH^�M�\��@��}��7��u�6!6&f�Vk��	��!�����
���-,`&{�M�Φ��ʑ���#8y
��������N5+���ʜ���2���Ge�[Wd����(��JlS��w%S��PfX9jD��"��ԺŀXhl�������f�/ c�{�r�S�8GƖ��Z|=���N��5to�&���
lIi��E�@T�;��-�P~G�Y,�J� �G֎wr�Cp�I8��Z ������j:{�K�B�@�'|,�wr�q���rG9C���\�N�.9[A
�J�J���S���+ǕAD����y၌����Zq�?�C1��%%�aYK�37�0�M|`�JFV32�YM�shw~���d�����
7b�����!�1�����2����M{�NP�<����s/�jc&W^� I��|/�:�$�"���)!��#a*k�锰>���@	�w&�� ��S6G��xq�)���{��#�lB����Ο�1d� d!�[y���+`o�����M����7�9�-4`�#&�����|ź̾&��WÍ��`J�C�\��p]xȞ'm�iornd:%�F�gڊ�rR�Z���]��<��!�6:��N��N~�����Z�4�ݿ�#����Tq핵<{B!��,iG�)�c�x7�h�:�|8���!�7�{�~�H�x00]ST,�x���O��E�YSr$&��h�r�=H��9V�����'[SK�����P3��n=w�������dQ�X��-Wr������βF)Re�9f30�l;�)��T����.��f�e[ޛI�H�mz����a]G�,R��J�{N��P����WB���$ ��U��U!G]�2��M�m7[��!R���<T5!aBn${�
� @8�$q�q���0���N�E*z
e���d���!GX/}kK�Z��l��yO��V�����l#8
��Cz7�ŨDp'�eS��1�u�O��u?�:�\�l� e�-	�b��_���E��ـq�� I(O
\.;�W�� �J�e츍�d�����>���ȱ@���?�����^&�m�CD 6�	�ץ:�$T��E���Lw��-0�����i�ͭ�yؗ;�}U4JƧ�n��E��'�90Ã���z�|�ED�����[cN*�F(�Y!�F���#e�o��^��x��q-�z\*`38)}���	����|L�$�6ہ_O92���}I�g���T� ��z��3ڧm��mꀵ�;OH�
}p;��Ϝmy���N�]�;�z[�6���C��$W5:�v�7;��lh��;=D�D���t yx���o��wl�2�'�]O�ڳ-�~X�e��?�)u�辨/����Ɓ����n�nfCAE�p�|�Y����g�m+t?x�24k�w�$b�f�1&��P�Տ!��0�|H�ȢT����Kd�8]@/܁-�Vs����O61e�����I.�~�j�<���xD�*7��JꟉK�#/?�,�˃�mYozλ��l@�g�(�reE��g(,�L�S�ơ��&X:*+��EY��L-� R���[���6������	M�~1~5�|�k>�A��Ǘ�G�y����4Q��� �t����I�����+GbNVH��w�K�R%�s�v�$i�~u���T��%^pۋ��?2���)�=��K+	��J�솂٦Aڤ�hd?��!�fr������vU�%�%��8�J��n�_�� �Jfz�)�v�Uvk���t�r�ң���+�Pd;[7�{wػh$,͞^b���`� $gzQ��7�i����o��|yx��ϻ�*��u��!�%��S���MW�Aru��[{��9.$:��Q#@�t�޺���X��^~��Ӑ�� +��u�j�mB���Z�[��Zv��<��X@>s��(mQ�0�<��z��!G�^I4%���9�N��      	   �	  x�]YQ��(��]LA�K�]e%�1g|u�"P�b�n�����bC��d��[H��K�GN$���Eh��5�.
d��)�銑�9zވw��Om����4�!72���h��/$̣ �5ž�+"9�X=Z�.r���l&v�:&�/-����c��v����y�V�"��yON$R�5��a{S���D3&���}X��G��%z��E4�f Lz�ђ��[���DP�Z�A�t⬗�op"��O�W@�
͘��B�q�v�si�zH�($һ(���$Dەc����� ��$ٓ�vI%g�|�N��[��n�9����Hf�8'�s"t�FK�9_`��>M�a-��uOLh6aif��-�`]=�J�O���M�ǔ���pztyi�����-*��c�~��i،&H��E�D^��&-F�օL+��hk��K�{�<��#(Y��k�,���K����ұ%'��m(2�4���}��v�@��+�.1��(3p{�+���[/�������\{��N8{��{92v��t �\�6/�?����Gf�r3&��sdq���'r\",t��&_��ӭ)+�����RW�k�K����p�#F�Uv$����ܿ��- ��Ð����(F��'I���\Y΂���{���;�DP��߻f�"�#����UAv5~P��H��L�>��V�ޢ�iR��H~8��%w����J��>X�q�q#�j	'��]k��S�� �	Ue�j�=(BW���eۚ`!�B�,1t���x" m�z�`J�Q*G��fW$�Ou�I*�G~�[����&� �Տ� (��'+�Aa�=G3�nQ>ǂ�x�֭6��I��&Z�|e��j(蕡vv#�F��MAY��N�%�W�I!'r��c���GX _�;�)�O��%��d�}�Ԡ:*@g����2b�@1':h�\};0M������L��%rDZZB���OJ)~�	��^h�0(f�G��M�N��'�\(��Ļz����3��8�>�}�`�9��E&~��G�o������	9�[�t���/��R�Yb�=s�d@/��&�_�bO���o������K�-�A '��M�pGK�8�Y�u*�㬉�|�����ы=^3��FGk���(�1��C*��ѽ���E�Q�Љ�4h(�T @�R]Zx��C���zW�U"ɯg���b�{ڜ��B�OEkL@�"��Gd���3�� �a���<�$	k�6��F���@"gu����r�����T���-�~��P�hK���W	Ek3�!���h��9=&�Q怠^��A���`G�E|a"oM`��;�B��{!�+W"w�`�dPJ��sV���a������BdP6ܠ�샔���n��]�(,_��9Sf�H�B���{ �I���P��0M��(Z"�,gs��GaE{�LYʶLA,��[(l�e���\�5�F�U��ݑ^4�B�g+ )������Q�:���|.$���s�g�|�?�"����б�!"�����6�>?����ɪ�������>͋���/w�V����#�!8J�#�d���QHZQ���?���y$jD�tn��G����$�7c�ϋ��A�C����)�e=<r��& ��3�����BA!��	�Y�K�B`t�K�?b�^��<�8��>�Y�����4��ͣ�c ������8���&y#K���nd](��Pʻ�^��ؙ�����zњq���"����F�//�A��3�VR�@�>��`nީ8��Z!`^L�ɳYI��+�z?�4���{R���5��%�Y1$�=Z6�����[��[��}��J�k��*���o�!
��
���5�ЪU̚�`����� S��W�CD�����z�C��ƝH�w���{h��gϛY}:U�b��a �~����9Dw�W-��>�=���%����*�S�[~.��������y��~J��zd�������U6�'��[��~��|�f�i���Y5�n+��a�o��N��Ӝw| �Q��@R���x	8�Ж/Dt��X\Ӫ+���U�������~����+��ۏwr�֗Jv=��Ȣ�6t�lg-@�e�u�!���@�ɺ+�
򅴘��R�k�ݾ/�D�Eq�\G(�����1W�O̶^�����F���Oʸf����D�+�1�W�|7.������e�I{Gn�o��]�����V!9lZ�PR���'��T��OT�|��@��^W���>ˍ��ԑ*��yJ�B(�1Zt^�r�y"���o|PiE���'��ދ�Dð�[
�;r�ژ*;� �AE���#���JF	)~z6�*��ԕY˂���L_�?\���;���^������y�à�t���꾥��Aڍ�P��4x�h���@�����A%�Q� m׳�^��*޵�+S�~ ~�T��eq��|o������@FWC_��'��#�7��Kܿi��[}jD�a��ms���"�ɢ����~� :�Kt      #	   5  x�mZG�$)\�:�<���.}�sL��f�3�!2t�^ks�Ӫ��~5��*�k��u��8�:�	���Z������jm�˄�����68U��Zb[�.��z�;��"^|�*�c��x�E�*5a���y�ׅ�����U\�eK�Bz��x�^%�S����?¯��u��j	X����Oi]�ĝ�8������.�ŉ�Ke�����֑ҙb=u���*��V�F��f�U����1�������u���<S��[�������;�롽��|��]���X5�A�I5�ě�n0�y����o�YV��i��U�1�7��a\{�p�/'h�׏m���zBօ�}�����^�,�h�p+5k�Hy8�[+��і�{o6��T�|Xޅ�F�x�Y���R�mF���@-1�ە���+��6�ӹ��q^��䲄K)�K��R�c;HFy9�K� ܊m�����c��H�8q�����'X�$q��T�۠�+����&"�������AL�%�ۇ���jI�!l���X�#A�q�<�.�
�W�\]CJ�΃�C�K�l���f����r�#8Hd5�7�
Wl�(�
��$W�\)�����-�N�G�yS$ w}���QD����w ,K�M��{�t�T�{��ab�Ѝ�愐;x���8U�Δ�b�!#�m����!</xS`(*���e�ӗ`KD�Y��!��-�k���:��P�a�u��D�������#�j��0�Q�ր����A
5Z�qǝ���҃*������R늿`�:|��UV`�Q�yyhް�t�#
u�7N�8Y�]�9���B��˰�?O?���ه��H�N0q�=U�k%U8��-i� K��Dص����^����t�i��#��&���.ܔRA��K��A��8_Z��k+�����������@�h����"�W�nO��5-o�I����z�n�k�u�@���� ��O�:�P,/�}�ҕ� ��[�F5���US����G�k�]�ħ�&���M��X��g.
w0N��E�r�B�q^wiy0����j�I.�c2R�ȅ+k�b�6��@��"?C0���w�=SJ	��k#�Z��3��*�һ�<<;���c����a3n�����i�`��>gIRP���>V32��F�n���B`6G6ի`-��� 8��.X�$. �P�� 1�,�?K�wu���p�>p��Į��>y=����l}��a��(�7n����BɝSk�^���} �O�
��wJU;pt�-�t��^Y:�Q@�V�*3(,����W^����g8^�Okp�g���' �������]G�} B"���.zy:S�.y1p�^ �O�6*"����w�W9_,�q^et1=�Չf����*g~�+I����$�w�GC����쳏Oc��[ٽ��<�$�y4p���-�`X^�ox�p�K�#��c_��_m�ͧ�����E�/��}��^��Ϡb@��g��?	Q`ϴ�
�[��6!A&[�]c��v�՚�������r6��e� ׏� �aI	H����ӽ�o?��+>������}8k���=|/
v�x�c�gz��Ҫ����w]³���x����C�6�n��G%f����y�W�DW7��eк�t�F�X����©���f��jɁ�U��43��=*��{��������k2@s]�}2p,��Z8_��\6�ffAR�*!�8��Z��I�d7p�t�l���TD��<I�p�1�~�b��1�w��થ�+��76���!���Ц��������;��uG�Ms�J�P%�I�m��g�8U�,.���^}�zv�*(��Q�d��h�ȁ�C�r��Xu�ĥ�i����C��,� W� $T��$ki�%�pH���	v���Ԉ�(O5D��{�����}����U .��\�W���S����ӊF{��� L���i
-��qz�+}m�]�I�ɗ��kͤ���*��(
���ƚ��q�-Fit�����}�)d��p��wk��X���ˠ�I��d|2�r�s����n��Q�휗��b9�9��*��J~��l�×m��N&�]p\'?XHZ$��6�}��<)�PȔ�Bo��;9�\=�ZT��o�܄.5;p�7�������X�CoѪ�=rݥ����ė�E���R1x�x�)�d�^���k��طH�5gs������1���q�%����e�&����hb})^rC��������b�1d�����G��7���f�Rˣ��������7I#�xW�6�u��?��ԭ�z�W����K݁��s.���фw�<j �{�U��8F��]!��~~I��I�$����<)!��9�'\c���j��d�\� ��yjjy���',�� �kt�ov�5O4-_�P���gk��\-�4ܯ�::�H�e=R	<&�$���Q�_���3N���U3�o������%��B�.������2*嶘8��d���Ŀ(i&�T��Zj���F���)o�ݏ}<���ir3P���=�=��i$�'�|N�Z���]��'���]��b�ӥ�9ZHG���K�\	��z����".�&��[��DP���pCBv�N~����>�i1Q�&�W{MVi��;��B�5���;�]��G��6�Q�+Q ǵ86H�8�\����\|�5��Y.A��^�]$Ƅ%�iI��5CL�P!T��������󾠺Uh�ȏ����砫P ������6�H��\|wmԓ�L�1t��60��0���F"}p7D�X�G����q^�� ������g3      	   �  x��VMo�F=����(���8:��hZ�ѩ�eM��-H��$$��3��DKF{$꽝��ff��"��Y�0�\��$>�)%�
�Y�?eu�i�3��mI��/+*����ZPJTu�:��|��!�M�/n�����96��#0)����)��Cs��qps�鐼�Ɋ˅`��AZ1uat� 7��Z?���` � ���Pkk�?<v���%��p����ο�4�!� ��8�&7�����	� EqE��0�iN��tf"�P��L�N�qvO���6<��i������؂OWњͯP��3���^�� �6�S�3{�9��S(_���*H���_�\J��{��a�D�rа��e�Y�gW��i����(��pA�2vQ٭�B@~gj!���jQڗ��u1!�S�^�ݜ���z?�$E|�~�J7���޷s��o�ڸ�c��ZN��2�n�a�t2t��(t	7ȡ9_֣A�.��ǯ��eJHAt]&b+Ǜ���ֹ�@\1��,����b�������x3<!�ˬ2��@V̈�eK�{��8��DE,T�$W8��>�)t9����O�-�e��Ƨ�я#$�O��SD�l&"�Ӏ�5>cg)���ri��|��X[����S|$R�-����쭤o�+��(�:�YIzC:&�K�����)�;�y��
X,`=����/��~�yxC�Tk�xI�P����@P+�s�L8��O� �LF����^-0�."Yj�P�2um��o��È���sL�y��6hε�$O��N���� ?�cy��O��2}��$7�w�͝�p������8��DI�������|���n?�������f2xō�&�4sa����?/��I �Q�"s��d�S�7�]Ú���ǆ6�i\�\��֕��c����@7p�e� ƛ"a��9��k�w~�m^t���)���m���k�'����+h�H�U]��7�ٓ�fߞ],
���,�^��u�/��e�      	      x���ɒ\9�����0W0�*�Um�7-��F�{�Ә�tf0��?=��8���Hc� P���N�(?_��fS�ɩ��������������u+5�PK��wec�&�:U���ht�}lc���k�?���?�����z�9����������|q$5M�����Y��Ky�֭u�5�8_7�CJJ��|%���}��[NeF�jmN�fCL�5�8�K7c�N.��|΍�g�M%?��VeX���2�m�o����_���ڟ�[��i��z9x�����⃯&O－����>n[�7�����j;��5:�br���r��R����z[����5�ݼ�Z�p��^��)����56�W��sc�|[�����[�Y����<�QE���a�B,2k>�9_�Te�[�{�_'���9KG/͌�k�fx�2��Tn�պ�毿NS��y�7c��I�b���r�/B���ͧfJ�����%����sք��Pk�����ye&��C�f��%o��F�brV!���4~�Zc�����j�Uє���z3��������W	9ˇ��f��Դ=�n�����
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