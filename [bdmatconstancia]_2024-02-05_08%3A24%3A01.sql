PGDMP                         |            bdmatconstancia    9.4.8    9.4.6 0               0    0    ENCODING    ENCODING     #   SET client_encoding = 'SQL_ASCII';
                       false            	           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                       false            
           1262    455761    bdmatconstancia    DATABASE     r   CREATE DATABASE bdmatconstancia WITH TEMPLATE = template0 ENCODING = 'SQL_ASCII' LC_COLLATE = 'C' LC_CTYPE = 'C';
    DROP DATABASE bdmatconstancia;
             roberto    false                        2615    2200    public    SCHEMA        CREATE SCHEMA public;
    DROP SCHEMA public;
             postgres    false                       0    0    SCHEMA public    COMMENT     6   COMMENT ON SCHEMA public IS 'standard public schema';
                  postgres    false    6                       0    0    public    ACL     �   REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;
                  postgres    false    6                        3079    11859    plpgsql 	   EXTENSION     ?   CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;
    DROP EXTENSION plpgsql;
                  false                       0    0    EXTENSION plpgsql    COMMENT     @   COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';
                       false    1            �            1259    458812    tbl_auditorias    TABLE     �   CREATE TABLE tbl_auditorias (
    idauditoria integer NOT NULL,
    fecha time without time zone,
    operacion character varying(100),
    login character varying(6)
);
 "   DROP TABLE public.tbl_auditorias;
       public         roberto    false    6            �            1259    458810    auditorias_idauditoria_seq    SEQUENCE     |   CREATE SEQUENCE auditorias_idauditoria_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 1   DROP SEQUENCE public.auditorias_idauditoria_seq;
       public       roberto    false    179    6                       0    0    auditorias_idauditoria_seq    SEQUENCE OWNED BY     O   ALTER SEQUENCE auditorias_idauditoria_seq OWNED BY tbl_auditorias.idauditoria;
            public       roberto    false    178            �            1259    455796    tbl_constacias    TABLE     �  CREATE TABLE tbl_constacias (
    idconstacia integer NOT NULL,
    fecha timestamp without time zone,
    cedula character varying(10) NOT NULL,
    nombres character varying(80) NOT NULL,
    cargo character varying(100) NOT NULL,
    bsennumero character varying(15),
    bsenletras character varying(500),
    sitiodetrabajo character varying(200),
    mes character varying(12),
    tipo character varying(20) NOT NULL,
    bsintennumeros character varying(15),
    bsintenletras character varying(500),
    usuario character varying(6) NOT NULL,
    fecha_ingreso character varying(40) NOT NULL,
    comision boolean NOT NULL,
    sexo character(1) NOT NULL,
    ente_adscrito character varying(100),
    destinatario character varying(100)
);
 "   DROP TABLE public.tbl_constacias;
       public         roberto    false    6            �            1259    455794    tbl_constacias_idconstacia_seq    SEQUENCE     �   CREATE SEQUENCE tbl_constacias_idconstacia_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 5   DROP SEQUENCE public.tbl_constacias_idconstacia_seq;
       public       roberto    false    6    174                       0    0    tbl_constacias_idconstacia_seq    SEQUENCE OWNED BY     S   ALTER SEQUENCE tbl_constacias_idconstacia_seq OWNED BY tbl_constacias.idconstacia;
            public       roberto    false    173            �            1259    570108    tbl_firma_autorizada    TABLE     �   CREATE TABLE tbl_firma_autorizada (
    nombres character varying(50),
    estatus character varying(10),
    id_autorizado integer NOT NULL,
    cedula character varying(11),
    cargo character varying(50)
);
 (   DROP TABLE public.tbl_firma_autorizada;
       public         roberto    false    6            �            1259    570120 &   tbl_firma_autorizada_id_autorizado_seq    SEQUENCE     �   CREATE SEQUENCE tbl_firma_autorizada_id_autorizado_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 =   DROP SEQUENCE public.tbl_firma_autorizada_id_autorizado_seq;
       public       roberto    false    6    182                       0    0 &   tbl_firma_autorizada_id_autorizado_seq    SEQUENCE OWNED BY     c   ALTER SEQUENCE tbl_firma_autorizada_id_autorizado_seq OWNED BY tbl_firma_autorizada.id_autorizado;
            public       roberto    false    183            �            1259    458450    tbl_solicitudes    TABLE     x  CREATE TABLE tbl_solicitudes (
    idsolicitud integer NOT NULL,
    fecha timestamp without time zone NOT NULL,
    cedula character varying(10) NOT NULL,
    nombres character varying(80) NOT NULL,
    cargo character varying(80) NOT NULL,
    departamento character varying(80) NOT NULL,
    estatus character varying(10) DEFAULT 'PENDIENTE'::character varying NOT NULL
);
 #   DROP TABLE public.tbl_solicitudes;
       public         roberto    false    6            �            1259    458448    tbl_solicitudes_idsolicitud_seq    SEQUENCE     �   CREATE SEQUENCE tbl_solicitudes_idsolicitud_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 6   DROP SEQUENCE public.tbl_solicitudes_idsolicitud_seq;
       public       roberto    false    177    6                       0    0    tbl_solicitudes_idsolicitud_seq    SEQUENCE OWNED BY     U   ALTER SEQUENCE tbl_solicitudes_idsolicitud_seq OWNED BY tbl_solicitudes.idsolicitud;
            public       roberto    false    176            �            1259    485627    tbl_tipos_constancias    TABLE     �   CREATE TABLE tbl_tipos_constancias (
    descripcion character varying(30),
    observacion character varying(30),
    estatus character varying,
    idconstacia integer NOT NULL
);
 )   DROP TABLE public.tbl_tipos_constancias;
       public         roberto    false    6            �            1259    485625 #   tbl_tipo_constacias_idconstacia_seq    SEQUENCE     �   CREATE SEQUENCE tbl_tipo_constacias_idconstacia_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 :   DROP SEQUENCE public.tbl_tipo_constacias_idconstacia_seq;
       public       roberto    false    6    181                       0    0 #   tbl_tipo_constacias_idconstacia_seq    SEQUENCE OWNED BY     _   ALTER SEQUENCE tbl_tipo_constacias_idconstacia_seq OWNED BY tbl_tipos_constancias.idconstacia;
            public       roberto    false    180            �            1259    455804    tbl_usuarios    TABLE     <  CREATE TABLE tbl_usuarios (
    login_username character varying(6) NOT NULL,
    nombres character varying(50) NOT NULL,
    estatus character varying(10) NOT NULL,
    nivel integer NOT NULL,
    fecha_ultima_sesion timestamp without time zone,
    login_userpass character varying(32) NOT NULL,
    email text
);
     DROP TABLE public.tbl_usuarios;
       public         roberto    false    6            |           2604    458815    idauditoria    DEFAULT     v   ALTER TABLE ONLY tbl_auditorias ALTER COLUMN idauditoria SET DEFAULT nextval('auditorias_idauditoria_seq'::regclass);
 I   ALTER TABLE public.tbl_auditorias ALTER COLUMN idauditoria DROP DEFAULT;
       public       roberto    false    178    179    179            y           2604    455799    idconstacia    DEFAULT     z   ALTER TABLE ONLY tbl_constacias ALTER COLUMN idconstacia SET DEFAULT nextval('tbl_constacias_idconstacia_seq'::regclass);
 I   ALTER TABLE public.tbl_constacias ALTER COLUMN idconstacia DROP DEFAULT;
       public       roberto    false    173    174    174            ~           2604    570122    id_autorizado    DEFAULT     �   ALTER TABLE ONLY tbl_firma_autorizada ALTER COLUMN id_autorizado SET DEFAULT nextval('tbl_firma_autorizada_id_autorizado_seq'::regclass);
 Q   ALTER TABLE public.tbl_firma_autorizada ALTER COLUMN id_autorizado DROP DEFAULT;
       public       roberto    false    183    182            z           2604    458453    idsolicitud    DEFAULT     |   ALTER TABLE ONLY tbl_solicitudes ALTER COLUMN idsolicitud SET DEFAULT nextval('tbl_solicitudes_idsolicitud_seq'::regclass);
 J   ALTER TABLE public.tbl_solicitudes ALTER COLUMN idsolicitud DROP DEFAULT;
       public       roberto    false    176    177    177            }           2604    485630    idconstacia    DEFAULT     �   ALTER TABLE ONLY tbl_tipos_constancias ALTER COLUMN idconstacia SET DEFAULT nextval('tbl_tipo_constacias_idconstacia_seq'::regclass);
 P   ALTER TABLE public.tbl_tipos_constancias ALTER COLUMN idconstacia DROP DEFAULT;
       public       roberto    false    181    180    181                       0    0    auditorias_idauditoria_seq    SEQUENCE SET     C   SELECT pg_catalog.setval('auditorias_idauditoria_seq', 713, true);
            public       roberto    false    178                      0    458812    tbl_auditorias 
   TABLE DATA               G   COPY tbl_auditorias (idauditoria, fecha, operacion, login) FROM stdin;
    public       roberto    false    179   O:       �          0    455796    tbl_constacias 
   TABLE DATA               �   COPY tbl_constacias (idconstacia, fecha, cedula, nombres, cargo, bsennumero, bsenletras, sitiodetrabajo, mes, tipo, bsintennumeros, bsintenletras, usuario, fecha_ingreso, comision, sexo, ente_adscrito, destinatario) FROM stdin;
    public       roberto    false    174   ~q                  0    0    tbl_constacias_idconstacia_seq    SEQUENCE SET     H   SELECT pg_catalog.setval('tbl_constacias_idconstacia_seq', 1146, true);
            public       roberto    false    173                      0    570108    tbl_firma_autorizada 
   TABLE DATA               W   COPY tbl_firma_autorizada (nombres, estatus, id_autorizado, cedula, cargo) FROM stdin;
    public       roberto    false    182   �                  0    0 &   tbl_firma_autorizada_id_autorizado_seq    SEQUENCE SET     M   SELECT pg_catalog.setval('tbl_firma_autorizada_id_autorizado_seq', 2, true);
            public       roberto    false    183            �          0    458450    tbl_solicitudes 
   TABLE DATA               e   COPY tbl_solicitudes (idsolicitud, fecha, cedula, nombres, cargo, departamento, estatus) FROM stdin;
    public       roberto    false    177   ��                  0    0    tbl_solicitudes_idsolicitud_seq    SEQUENCE SET     G   SELECT pg_catalog.setval('tbl_solicitudes_idsolicitud_seq', 1, false);
            public       roberto    false    176                       0    0 #   tbl_tipo_constacias_idconstacia_seq    SEQUENCE SET     J   SELECT pg_catalog.setval('tbl_tipo_constacias_idconstacia_seq', 3, true);
            public       roberto    false    180                      0    485627    tbl_tipos_constancias 
   TABLE DATA               X   COPY tbl_tipos_constancias (descripcion, observacion, estatus, idconstacia) FROM stdin;
    public       roberto    false    181   ��       �          0    455804    tbl_usuarios 
   TABLE DATA               t   COPY tbl_usuarios (login_username, nombres, estatus, nivel, fecha_ultima_sesion, login_userpass, email) FROM stdin;
    public       roberto    false    175   7�       �           2606    458817    auditorias_pkey 
   CONSTRAINT     ^   ALTER TABLE ONLY tbl_auditorias
    ADD CONSTRAINT auditorias_pkey PRIMARY KEY (idauditoria);
 H   ALTER TABLE ONLY public.tbl_auditorias DROP CONSTRAINT auditorias_pkey;
       public         roberto    false    179    179            �           2606    455813    email_unico 
   CONSTRAINT     M   ALTER TABLE ONLY tbl_usuarios
    ADD CONSTRAINT email_unico UNIQUE (email);
 B   ALTER TABLE ONLY public.tbl_usuarios DROP CONSTRAINT email_unico;
       public         roberto    false    175    175            �           2606    455801    tbl_constacias_pkey 
   CONSTRAINT     b   ALTER TABLE ONLY tbl_constacias
    ADD CONSTRAINT tbl_constacias_pkey PRIMARY KEY (idconstacia);
 L   ALTER TABLE ONLY public.tbl_constacias DROP CONSTRAINT tbl_constacias_pkey;
       public         roberto    false    174    174            �           2606    570127    tbl_firma_autorizada_pkey 
   CONSTRAINT     p   ALTER TABLE ONLY tbl_firma_autorizada
    ADD CONSTRAINT tbl_firma_autorizada_pkey PRIMARY KEY (id_autorizado);
 X   ALTER TABLE ONLY public.tbl_firma_autorizada DROP CONSTRAINT tbl_firma_autorizada_pkey;
       public         roberto    false    182    182            �           2606    458456    tbl_solicitudes_pkey 
   CONSTRAINT     d   ALTER TABLE ONLY tbl_solicitudes
    ADD CONSTRAINT tbl_solicitudes_pkey PRIMARY KEY (idsolicitud);
 N   ALTER TABLE ONLY public.tbl_solicitudes DROP CONSTRAINT tbl_solicitudes_pkey;
       public         roberto    false    177    177            �           2606    485635    tbl_tipo_constacias_pkey 
   CONSTRAINT     n   ALTER TABLE ONLY tbl_tipos_constancias
    ADD CONSTRAINT tbl_tipo_constacias_pkey PRIMARY KEY (idconstacia);
 X   ALTER TABLE ONLY public.tbl_tipos_constancias DROP CONSTRAINT tbl_tipo_constacias_pkey;
       public         roberto    false    181    181            �           2606    455811    user_key 
   CONSTRAINT     X   ALTER TABLE ONLY tbl_usuarios
    ADD CONSTRAINT user_key PRIMARY KEY (login_username);
 ?   ALTER TABLE ONLY public.tbl_usuarios DROP CONSTRAINT user_key;
       public         roberto    false    175    175            �           2606    455818    tbl_constacias_usuario_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY tbl_constacias
    ADD CONSTRAINT tbl_constacias_usuario_fkey FOREIGN KEY (usuario) REFERENCES tbl_usuarios(login_username) ON UPDATE CASCADE ON DELETE CASCADE;
 T   ALTER TABLE ONLY public.tbl_constacias DROP CONSTRAINT tbl_constacias_usuario_fkey;
       public       roberto    false    1924    175    174                  x��}M�t;n���*b/!Q�~r��.<)�����6н��!%�e����^�9y�D��Gο%���Ө�V��������?�������������������R&���k#j�!�����_�������K1������Ҥ�f��x���)���S+C� �(����Z�V�?C�r��l�ͦ=��g�z��ϯ��l�J���`#�-�[[�_�?�57����4���6��B�R�/�Үd��̃�d9[�-�o���}Ig����.O:�������c<5�ѵ�<~�7�up:C�AI�˖?��"���{�rʟY^A5	?Cf��_�?��|�t�� ԰M�lz�o��?���.���6�;g��Dn��(��Qu�H����lE�|��?_e�s�'��8����FPVY�<����;��[�JL��w���Du<O+%[R�_��qL����υ�ltz�����K:Ct�O:�J�rÁ��o��������UҌ����&N�n�6B?\�e�/�ONM���P�{/�K~G�z�jɗ�_���ڈ�U?�ٙ��[������:�#2I��}Gm.��o��36��[
�!u[����\�Ce��D�����z���闇>\��N爮�k�'J>��U���g��5�8��+���~8T��?������..�����{���L;��*]�t�.����G��;w��*:�se�iM�c�vӚ6�j���O'xW�HE����%��)�&B��Tr
[A�Y����&^t�(\2�O�&�����E�=��Ubħ�CѰ'������]�t/�T�5VLwR�Ə��/ktoDν<���� ]�_N?�'����B�:�靈CA���'��;]yM���5�쇢c�U�T����H]-�'[�KV��z��vQ�F��^�Sv�_��E�����9��*���Mzg��mT��]�ds�I�y|�>�"N�n!tc�pb�M:QY3T��hv:E�N)h l�n#�咛��"ct��
����Jj�������ߓNҔu�]s�)��mDRm���DLyR�E��2���3}����Su��Kݭ������(2�<5;)N�z���*��w���*��?���~��R}�ܟt���J�=�O���p�۝�t�F��͎�������zM(�F�%��+v$X�颫{�S�d"�"�u�GB�T���M�{�uN��ت��$v&`1�:��}�t�P�)�����\��P���V|]Pz����EwPR�%�]O2CHI��'��;B�0���MR�k#�JFy.k5[m=�T(����9"5��&��(&i��˷��_��"��X͚H���H*�/����
㰬nM��b�I��N���6����]��S2ï���NLr.D.������G}��F�:G��ӳ^���_Ms��SA�:l�\F�DIړ��5����:���ڈV��_�&N�AQ۩]��E�Re&���u�q_6�~'b����7��Lu*��l�i'2=;��[����ƀf�u�qé���n!RՋ%<���cc�(7g�Lߎ#� ۸U|;&7OZ�/�۝�(���V�Cjϫ�1U�J�_!j��^�퉎�5JK�}7��D�˧�Rs��Ե�d��'y��F
����c�!�|sb�w_���OQ�����.6�m�m��F�^���u;\q���͎�S?qD�#���[dBO`.?j~��Us-���s�u3��.��R]��N+��3�R��e���a[b�)��|�QU�M�e�T���h��E|V8�7�U�:Oy��v�U��U36]�R��O�'������AWQ�k���v;b
�P.7@�`# ��S�4=
�!����Ӯ���g/;��	�!Y���1��A�)(x�)PjG
ܗM.�n�@���c|�Mo�*����htQ~z)n�aF�^��zd�륽��{A���X7Q��w�vر��(�O�*;�L�aB�z��M/�pˎ6���+���R,:E0�s�P�E|(���\J簪�U-���"�ޠ�MDÞ�.�4�Dj��Q��?s}m���l%�����s����#�{9����+��G�!8����}��!�Y��펃�|Z|��Ze�糰��J2~X��[P��I�!qR�7��L��RӋ,_O�;�Q�۔�����JgYز��rsy.:G�DK;��|摕MW�
�b(��\���������#]u�6/ZG���0c�d����|�hU��x��4��E����|�����%���P�md}������wD��,~��G�JM���|�H=���s���֬���:���
�m���3�;��G���|��/GB�X��s��Yty�۹��F�F�!�UYt��9�B�~������nwN�nZ��m�Zɲ����g?c3�q�V��)�����1��IW]��!C�S�#K�����>햊�|���\%�f�|J�=�W�w��9��f�.�ɧPW�ps?�Q��-���X�&!���h���n��B���%������6LME�e����&O�Jv3��Q�AV�\No��#�pP�;�2��޶˃N4����>)��
U�nW��3D�5��A}��TЎ����m:G$~��oL�
�����E�|�PA�=��b�RpQe%�ѷ��̈|��yR�<����� _�]B�P��.>$����?��6BF�������dL�fu�`.I���߲oH�+�x˾�ңiP�t���~"�/��l|H����*ʹ��P=�V�澝�����"�gǃ,�h�w?�Ng�Ϊ,��3;j��᪷���\^���p���Hj�N�(��ǯ����^�Ӽ*��*��-���5�=;�d,ay9O���r������UUߏ�����W;k����#�[�l�:��}����V�&�1o5s�jQU-�|J��z�������55��H喓ӧ#��Q�x�:�3|	L���W_ј$�2���iEd�o���'Y4�-�%<_���2_zbw��ڈڐS��tU��pL�һ9��rg	�9b����vf�B�*�F�U�FX_�"w�u���[F��/G�҃<��^V��j����1,Կ%���<�1`H�����|�_�PeR�����a*H�o���"�Ɵ�LG�&Y9>�E6��mz}�l�y�P]�S\ߊ�櫕GR�u�,�T|9�����:����zs�(�]���=s���� OCUu��/�W���$��z�~cz#UY����G�I��p~��W	O��{�5_|� Vu-�o��˦�҉.�`��&->��֊%%�4�%%:�B���֞��Lӗ��.��O^��Ec����Кʞ��o�9��p�����`����\�cz"�Ά ^�]�t�T[����9XR�1�{�'�Zr�9Jt��p)Q��e|����.��*.���+!��;�[X����2����r�VW;�Rq��L�r��E9���q�]U�DP�;>G��GQ�5�;x@��t󒀮�6���$����T�T�e�(��NK�B�D�����EfE-r���F��+���b����������Eg8k�� �X�u/�%��k#�����Ԏ�Y+R�,��|�`�5n�>C�w�<.�֛�Hƌ�1�͛�ҍ[Ŧs� �!��V�"�t�D��t�Zr\�n�w�/U-�)V��>NG �-*��f�3��T3�"I7�#*���
�W 6��5p�]�B��Y��xf���0��e����>ƈft_G6��/������)l�^���8����e�S"I�pa{t��'m"	���PQG�b�J����>�b�+ߌ�X>b�������^��c]A�땯�6�O���d�{���&��0��������C�4�h#���_hf�:Be_�v�0�
���~�t;�4�'`��;�L���r���yմ:�������f��P=�p�Ho�l�م�>Jt[�v��2�;���x�9"�L�[M�T�2�[���Җ=6��k3�:��e��4���|G�-���S�b?wև���[�^?���*K�U=���ؓ��"���AH�x��
��Y�hH��9����j��,`չj���+    >��.��p	�����n!�����z�J�7���8>���AU�)��|zެh���Q�|<������JN��ڀ�+���m%��p���W�m���S+���q�Y_�����v�#��I!������xFΙ���-D��[�����<��7:��: �����f$`sF���ς � Tǅ���]wX�����kP�6BxH�|كvz� :����v[]�nʻ�U�Y���Q��G�p�!�*AV�/��Ͻ`�*���oiW��,S�����̟uDӻ2xu)�|_X�zvOA���hTƈ|�fe	�j��S�|��zFH�%�R��Z�gq S0O�*!Ϫ�>/g�pz�2y~_��qsDe����3ۃ�v�L'/ӓ������Y��O��G>�>C���[��(/����!���k#N��DS;��jz�����ڈ4T
��4Op�6��E�|!c����	>��3J����gU��h5��(!�,ā�1z�Sqn�3�^�����E�؝Cu��OYғ�M_����hF�|m���ys���m�+B��_׌J��լ��.�*NG������hHz�~��x��F�*w%l��f>-r�t�OƖ��Ok�B�A٥��1���ѩ;��k#,�!�{���)�^�,��g��1�H^��ėݒMZ��ʸ�W,����V.�lA[^G�<z��ҧ�+K_Q3���WL>~mD5�nF�\`��Y�*�O�~�/[W쯸m:��W����QRM���	�*H�����3D���7�g^�8"��������-$��α�~S6�|�P͔#_�|R���&�|�Pe�CF!d.�=5~D�ީ=ԓ���c��* DҨ_H+5�7�bt^ԁ����E���9	�,�?��en�GH�/����]P��P���2�� �Y�l�r/���Ew��4�T�B���ϳ�D�r���G.[�s!�#h��T�Z�R����|�N%��R���ߊ�W�蓗x�y9�!T�c
�E�lR�]�p>*8���>�}�,�l��j��S:��s��[-�{I,S���Ԇ=�9AG3��BGX^�H�O�.��M7���z�J4<��V��"V�������߈T����X�C�&T�lv~ym��H�T� ��Y�;����Ú�!v�^���Q8��)�k���wDU;5:]�f<�ND2S?9��!����T�pT2�ڠj����4�H(���4�����2���f|d��@�,G7��g��*�4:�S�֛�H6h�sX���d�U�vO�8��;�#�%[K�S���|� ���2�l��_�)B�Q\�:sg2|�:�|�H�C����X�b"��H�*bN����O��:g��!���3ʂjN+/��c۽L>��r�xz[��ht�嘊>��1	y�=��y�F��m6�k���Ies���j4B�y�H�k�s�i�?&x.�ELKs�Z)���g�ruHcS�2ղ�|)�C�y�ܴt�D��|���ZB",�:�Y߹�%?� �UM��h43:rEx�9����|�(���3�
u�� �t�5|�H� =�ǘZx�{�����sDI(�z�u�:ܡ�j�UQ�|��ҢK���T�э�8G���J�.l/�*%>�)V%Y�l�vwTs!.�����2KȲ�T�)@���9�y�6˛�����jv׏��bB��%�!��p���M��Y�f^�����!(|n��Ÿ́cv��k���� Gݠ����ׯwva?���x ȗ>f?>��ڸ�o�~|h��.ἡ���Z����q��s>o܄\b�>E'��S\�z��!�ȧ�������
ڊ�	��_����}����UВ�P]{�6_���ޣv0x�0���uyg^m�˗U�6YF���V��m�[e
jV�N�Q�O��t;nҪ��|�-�r��;�t�	�-����6=j��ң���U^P���)�>��U4Ga5�ʣ��o§*��q���^�Ϸ-)ͮF0�������t�P�L!�_��`N4����2���!��T������PRA{�2|ާ	�*8����v�}G���yn�!���<�������|=!G�5P��q�6鵟j��!P��V�mT�Rĝ̓;@�����CD%U�n7��3D֯J�����z�.����g�$@�e!CB0����U>:'�N��ڀ�ݍ���cvM,�v�o>Gt��O>��f��8��r_��:B�!e|�������*f�w®܂�Uv���[���s�3�3^�ۈ|�iE�us�Ey�<ʳ�|#���1��UP$�c��yl:�L��`V��ֳ���_�S\�6+ ��h�)�݂w�P�ܬ(�r_h4����|��'����̤#��T˼�������Xy�P�����1�ӫ|#r���-B+v�
n⠛��)֋�I0S��۪�=b֐7Е�5c���c ��g�&z�� �ai+\����g7/z�U��>�VLF�:f9�o̖�@�.L�q�ky���l(hv�ڻe��y����s�޺#ҹI�=F��]�N�0��ڎ��P{D�ߛ SI3#�GM����ޥh��c7v��N�˔#_��S=AJ?��=������|�d先�@�t��pE����M��"'�S)��4���@ �xu�j՛V5���[�C���,̥�ܓ��|���J�J�$+jօ��]I&�]�@$|�rѭ��/��M��߿_mDG����de�c%#f&m��XIAK����	R��l>G���0O>N�CR麅��|��9ST<N�&XՇ���'�hC�$U��zx�\M�l-<���!p���Ǟc5��8�|�@g�c �p)-T��'G
�������C[H�)�Lr7>Ǥ���'Lr�Sכ<.o]����\�ܙtd�9>���=��t�@<��Q���*�V�@K���I=}%� �z(�((,�F3�_�E�|����xI��'����q~��	�@g={f����v��ƛ@���a�y���l�p6� ���] �?T1�ã���qj�|�4�3z����VK�������U��#Tî��̶��<cy�SS��gUp?,��Xݚj�^�?]}��ד.Qŕ�"X�|��h�8�~z����K��% ��?|h�Ն|����R�׹ړ�r/�Q���"Ij�sU�/{e��Z�vk^��
�·�J������^����x�����j^E�H�0`���v���@����Ύ�Nᒬ<=����D��3tc|2S�w$��a|� ����ȅ�ة�{6A����x�(�[���Y�����P�O>C$Uz>�����ޡz�_^���- t���| X#h���	�R7><�9A�=�mEِ��1����h�Y�m�l�7A��/��8Ų���X$+zè�/��uln-$� 3U8ܺp�\�M�#͢�`��̇ͺT��{p>��qf�k��J�g1A�J��]O>G� 
AT���k}��9�~�-D�����ló�_C0����H����5�b��Y����K֓��6�0b,<��1<��A��*��e�*m�\?���N3F	=C/����i�]K����t�����1���g#T�c �?<F_T����fUǰ�S����Y��Q�Qpy}<�a�DJ��T��0�����j~hA}���K�\÷�BEU�����j��3_�ts4ǚ��W?Z�Ӹ��i_9�P��_FZ�6B�Y6��`T
��B�i�v��l+@��L��'��=�TЇ<�cZ8��#P��>�_��
a�M�K�gt�j���އA�<���.>G�s�t ]�^J(0=��o�6C$$6��<+����M����S�>����]Y0C�\Q��3D+��>O/�K�{�͛OܽD��1fv$�O�Qt9�u&��c�!�SZ�����xL:��%tK�h���a�-�>}������d����|�5b��B؎�54�s�T�:&�Ӛ��h�C�}}��و�_�G��
�KК�e�G2��XA-��^�wWD��B2-{:C����j�f�#R¬��������+F��p����\g��*�8c��^�i!�͜Ad�AkUE��    �Ǐ��C� {�6�M0���L���#�
(8H�c�l�	Ӊ���ج�N1s�ޚ��h{9k��|!Ў<TM��7g�=�Q��<�o!P��l�mz>��RT������#�E|>�W�~>΃��uzd,"�����7��l�����z�Cr�#���m�O���w�:�|�W_j�~}��F��V�������*��7�y�M�.�m�y����`/��ޒ�����d1C0怄�LK���40�o|�I�XC9���ߝl&K����'b$�<�2:]��;��n|�|�a�����AhA��
���˻�t�S�V����b�ĕo���2�xQzښ1�p��d^l�ГmrF�:O�B�|���-�y!0K�E�>=t��Z97��X��l�KQa�ً�قp]-��"4��0|�nk�"/iZ��Yn.�1�&�wW>L�T)s������T����Ra�!MV9\ѿ��l�a�t!ZQ�*�F������l�@�����T�(-fu��C����=L��&�OVQ��fL�=����@���X#�ܵZ�5E�ǯ��hZCg	�=v-f֏���P�b��9ж��ﯻ\�7J�z��,��)_�1��H8����,hC�K�>��z��Q�b�3�Ts��:fߐ�@H<�LK�P���:ߜ�;�����fc�A#e��㨈Ig����b�?�xz�� ��I���F�'ߪ�@�Aƨ�/��~	�F�����ϸ/��9"��f��C���+��Kh��g��j_���g�<�:`����\L�ȒS|>7;����pk�u٪���� 4Y� ���d��O�W�e��Ok�_��#T���f�*11z�I�-p�T����.������_{9kQc�\��_�ts\����}&k�)L�B��ۼz���^!PKr��H����Ń�5czo��cV-�J㋳j�-���n`�|�^s������<� MK�zr����^:��-���/}ȟ��4ALr��9�������Q׵N5�K��Mg]���jJZ�#��w|b3�t+�UDJ��-�sD��ro�y5��я���W̕�n]���|Ż�\����O�7��+A�ϧ����!�Ɇ�	R��1���׬J�Q	�m�*�K*��}�*Պu}%>�yr�M�PIqN,}��3\�^��<�����������a�ӞCq��˧ M0�/�Y0{�s��j1W(�xq�:m/����'���(A�le��UӞF��T����4b*��z-Z���������[0��|�����v	ڽ�A*����,�Q�sTV����|%yYfwnm��E^X9΃}�9��D=��J^BJ"J�����F@���FH$E�~	����UܼJ�x��4�:j�  }�|����S$z�fWsk�N%�ǳ�mFC���7������r]�X�7���(��K�̾��]��|�|i"P���Q��;/Ϧ�[~��s��/�F>���j.e����G>���_]��ڕvI�z����lY�|mN��n*��i�>Gx-g��W� 4k>C���3�]p�}���#jo��7VgAd�TUÿ|>G��/%�ǜ��l=*���g�P���>��G���=W|������N�c#I�c��⛈j����ӿ��c鉿�~�$����Z��k�G���g�Mg ��(�yN��&"r�o>G��Q���f�\bt\r7'�Bd�})n�>��.UQ��W�z�N�q�1]ͨ|���ۈ�E��+�̎\�p��렟|>�
�G|y�UU-��,ߜ���k!Q�����YA�/����}�5��6��H��H畨b��Q���f����>�3	���h�(jY=�R=�Ɍh�Dg����9�[�MX�:�D�p��<�����{���y�D��q���j��=�x/�Y�l�r��ޛ'�!����,��S��t�n���T��P���U��{�Y5V�%�x�M Q-F���2n��
�7��1"���h�>�,7xT��g�=p@�������ˌ�HQ��d'�߯m�'�!�1�(��m���c���0W-x��;�[��~�����gL&��x^l�Pj�ؓ����Y�.}fh�2R-�.[�k#�	Z��7fq�o�����3��@��ӬT����{�ھ�3R��j�74w��i�n#�,!�.D���/�s��ȇ�*G�2�:�Q'��h>�Q�P��Z%6;��3�7�`#���^���������3D/%&	y��ay��� K�rDAC�����ÛT�]�e�>G��LB}����t�*>���?V����G��r�������6B�)��׺���F6R�I�aqK������M���V@�7GYY�h�[���<����!�M��~c*z?��8_}m���j�:m��U����� ���"�J<D�4�-n�|��	��_�����o��3�j�%����I����hM,���[�
E1.���(ZX$׋w���h�ݢl)�$G?B��|�h��BO>v������|����6�\���J�~l���sD-hU��$7�ip����3D��!�!��\\�Ȕo�o�"û�ג�5+�}?{�#�N���ؼz���*��L��߹�6���	8dp��gUJ�l6�;��s����n	��M��W���|�h�Z���rH��b�|,;y�裆�#��D�1�����X�o��{G����6��������u�T�L>�w�rQ���x<GQ9 �����xGs/�ԋ�ߌ��g���k/�y��uD��1����;\������Q���3DW;-���[�Q(q���Zn�/�H�|bD�F��C�p�>q���+mɛp�%�!8�ȑo�.��ړ]�$<�;B�1
������0h�v��:��c��+���~]���~^7o"&H�Aa���r�~;���QX�����������I��?�s�	�jf��!m	��|x��F���|2�>��W2�7������<����S�}�-������_���E�����X|�����fy�5�+i\���g��g-��O�o���F���d=�'�%Js��:�9�(W2/#G��5B:�{_�ځ͋��"ȍhX��G�,�Gi�D�ͷi:��_<\.6g)�6n�G����z������ɓ+-ף{�-��F/b��us�R�1U��gU�R��[�`5d�7:7�|��B;i�\U���r�(8�٪�`��k|_S��: e��������ه�0��_K��C��O>C�SK�-��D�HU�������9N���§tI�|�n��G3=��N�/��^��q��c��,-ߴ���$��t^�>�*`�|��sn�P�*ݴ�Tͫ���v��5���c�W��dC�G�tL�~�B���~q�<����E�<��xo�<P�'�/*�jE���u�/�W�!V��m�{t����.g��P�#^T��4�MѮ����D$ķЫG<\�p���E��)� K0�#qʇ��Ǽ<A9����H0a�R���c�vR�˓YQ^��	B˗��%��a�瓙�dI�#�.�����Ķ�2+ʛ_Ft�Q��3�j����<٪��4�i�O����2+�g*rn��3DFS���c���v���I�% ���WӚ�
�>*ޯ��|� �)���c�z�ƛ'�Yk��PC0v\���t��)����|�h���ꘚ,M׋$�5z��Ù>ޗg�3���z��x𱕥"S2��kr���A������r#P�2���Y͂�J���S���,�����
|f|�X3���:h��#�#�SF��O-͙�%�+��|cz&-�%+6����s��bк�P�9�6�!���L��R���P�s]�ih��ȉ�G�17t�^�q��g���o9���Dn��f���T���/�����M�ʷ��R> �����իʡ�`"Ź���� �/��lI�]��~kyQ4M婡�L�kӯ�B�Lt�̝�� ��WcUx��bI�<l��uF�uuUw�����Y���9J�#Z��%^ɺ� ��r�����3�%eo��u|��%K��   �y�eU1/DVM���hV����y��#D��>�<v|�^n�����&�K��_=b�(�A��8��-��y!D��"�y!٤�-}f�B�����e"z��������|�P�6t�@x�����o�t���� |EQ9Y1��E�y��-D�2Z|�1+��
��-�c�B�6E�R,ѽni�sG�cx%$0`N�?����/���4� �^V�VH�����y�O>C0טPR����U{j� ��-�y�u���ف����[�����L?D1ŵ�ޟ|�P�(��*TQ��Z?4~���h�T��fЈ0�y�c�ۓ�p�F��1s��UBk�+�58qDN�Qx�܇��R]cncrDB�HX�acl��MZ:�Q8����0A(�m0��&zp���9B���sss#y*}G�h6��>�&�����|�h)<���Llt�Je�2k��"���*�)񣊏���\�d|���!$S��|�bFV��W�L���v������j��R@����-[�����1A�Ln(�;�Ͷ���#�)�p<2�{�9ߘҏ�6����/�r ?K��爎D���d��`���偺���F�V���ۛ�K
͛�M��P$Xe��B�;��؏����k�!]�z̜�5�l������3���ཡ���*�ѱ����Z`C?ᶏǿ��oJW�Ed�-�#t��6GA"�ݧ���3
����|�)~K�.���>ߓ����$s����v!�����s�^��S��z�P+�VU��=��gJp�<�0V�`���|��Y�,���4����5�O>C��xg�9_Y	�9�r�e"_��)��]����]6G�[>�Yo'��������D����s�w�����$Y-_5��%O:C@���������j��������n�l����~<,F�T����q :.�A�:GdD��i�c�1�D�6�15���VY��ח��"L�w�.y6�_^�qX^o���q&�9{�-�>��h֗�.ՅF>:MyA-D�jZ��g�I��6::��tȪ���E�f�:�W����l8�!?
h���Y�
dXc�czσ�<��g���H�Gn���s>Cdx�K�i|�C����l���2������3��1W����3�����݁5(��q̅}�9B�R��ݼV�}�����<�ƞv|Q�ۢ$�>�Yz
����5��US��k��M1׳��f6��B}԰��{�*�U����\�p�z�E��h�<�Ȓ{G��2�����D����6�Q%����ʡZ�1{��W���xغT$���|���Y��������x��F��7���Y�Ur)�>�59Ymد�	�A4�pi>Л�N�'_�vM��"^�lx��>5�LO>Cd�0����HMjH���=�#*��n��)�-�/��(��S�c�zjQvsj��C���Q ���1��p�2��7��9Be���Ҧ���M��F�����e��d�"�һ����T�WcLY���*	����k#p���k)�h蜩g��~�6"�^�"_����7.����x/�8���(֕�c�������銴���	��>�}�9�#2 9�y�7+JCZ�]Q�|�@o�L�Of��EP䜭��6�T�fB�hy��Y�������F@�q}�*��.|�s���E@���\��L"��Kw����K��˘��l��*�ה��@O�~-GU�`������F�>��M�/�QCHu�|@�g�:"���K�����9�|y*���9���j��2�!��O��Y�b�yx�g;����_������[      �      x���r˕-:N~E����ꤼox�@f 8�@�@&e�b�b#{zp5�vg�wΏ��ܣ���DG$���J�i.�ؾ����v �?1���	�o�{+��[&�pe���d���,]&��,]�i�N��t>^��8�&׃�l1Iǋ$[]e�<��u:`�����/�>|����]r����_޿���?����O�7~��z��〙�3�{��|�i��M�͓�M6N�|�Ζ�*]��;?1�0@+�[f��	��@h攗jp��Ӝ�Fp�t�%���,�'� �Gt�ϒI�Z�9~���׋�bC���}���ǻdr����;���}���p�����ۯw��>�߾�s ��9�A\�A���ś���\��|(�7��UFq��MN���*����7�d��/��� ]�9t�9J6ϖ�<m���_}�����p�]@��3�z�W�-��$_$�Y�\��l���^����˷��3J����C�Q����������q����C�����w���Я��/�&�>&����?��ɻO��������}����S���߽�����O��ӗc�.�q1X�}����Srv�����}i������Y6X,��$MF�rAW4N.�,,��%=,\er���������|�&����?߽{��#��?�{zw��N������=6�|���l�����@_��׿�׊�e�^���z�["��
����f����FrF��Zb��/����|9]%���O�3����2R@�]��t8ᝡKږ�O���S-_�I@�m{�~�˯�?���5�Wx���|�x���W��#]E7`�;���9�yM�3���S�u����57֨�<�N�K����b6�I��/M���!���_���r1}�+atV���t>Z�F�h�\vSc���<>�`�̀霕z0Y�oI��&)�mz��54yv3�e�Ts���-���Nʇ f�q��[y᳻�������1�9�<�G�9���^��{Mw@$=#y�&U0!�M
!]���Q�I�:%K�L����y���O������n���,����t~��wJ?¿�MÇ%�a'?[LI%���t�wAʔK��~]���aP��e�ڬ^PX��=�5�-,��x.W|�Lg9�/IV&�2�$�X�v���)f@�u��=��̼p��0���F/��<[�T�h1�Z���v��׿������� C�	PW�
e���=�@ˣ��(����'&�zɥ�~H�,�j���^K7��k�%cB���2;��Ք�^:|��r��Ru�/HUA���� QW9yݤ��[e���A�Lh��{-t���y��C�`z|�|Y^GϠ��l1���fŻ\��n��/�ixR�ߓ���B}�v�F���o2��o��?��������2�az���u���b��'������ňaD�<��DdI�<���d���<by�����g>�/pv����Az��K�n-����o�bP-�͜�@z�$��Hg��5=O�iKS泫<��h�7d��tZ~�R��|#y���Q�L6T;I�B����J��z![I_�|h�e�H�%���Y>� ܦwI/8�ȯ�mH�VO�1[��:J|�����+�ߎR}Ob�=yxH�奄'ٹ'�� ��(�{R�=ِ�o�*�"�!�WN���Y�� ۾$/l�L�]�u���y��,�;bz��1�@�����-`'"������_���K"׻R'-�ه\�"7�[N��)�O�D}����/k�{�������E5<��PMT�%�䉐�%�i>����PaLߙ,�,+#���_�?�z�y;}S~�J��ݝ=�ե$�D?.����ٸ?�Uv<��$,2�L��	�',ctm7��!O�yr>%�g�d�7�
'��7߅d�J5$7XXsүE�m�ZA&�礑�O��u�<,�6�Ǔ�_G������X���gx&�(�	������k$@'��� w��^�j����[f�
3$�܅�_J��mNZ�����@HX�����Ȉ�>�H�ۯ0xP�$+�D#��u���@�
�`�5�6!�Ud��G���!n�g���l]g�ѯo{�t�]�勢�Ǩ�<�OHQR���^%��:!�tO.����ٔ�o��=�pdF�D���W(�x��J14�dS�#Q5���.I��Jl�Sҳ�a&�_��غ������J�c��Yp�Ƞ���L�^n(�\G�-��v����z8�����$��^�2
9���M�/�I�<���t>I�$|J��@}u�G�ds�3��_�w�~R��"5_\�|�0n��85E�=O�YJ��wR9d�*/��'�9�++R��y~M<��e�&so�x!OV��^q�:�)!�~E��,ɦ�|��i>�E>��e�說jWU7�lX�v�v�$����i�d��,�ي`�>��9n=�v�L��$h�x筴�
O�q���?��N�!��%+"���'�t���񼱮�z��$@V�t�S���1r�G�G������,_�@Os��4�M+#�Ng�(��w��׻w�������w	��pB�O�J��NX�{aZ�(j@�Ir�A0F��b�Xo>F�)�����!�.Q�?q��I��Yh��s��ly��8������l������������>~���oQ&������o�i�{| [��wcv�hi4�����|��(γ���|݊�0�M<���s��$�{�=���Z6�Χp4oɖϓ���
����~���M�(���e�����~��6�����t�6s��I8{�&O��'�\,�ɟ���)����^h-�=����+�����;���uݽ����.QxNJ�"J��*�w$i�*�p�,�)�w/��M�5��rA:!zP^���'�[*
���hO��wV0�}���f3��ɧ;%3C�rD���k�/������_�,�^W^���a������gR��Y:�����ٔ�]i$ɤG���ZX������<=E����_�s2��K�'�8��U���p�*�N!��#$i�GȔ��8��y-�M~�$��fJ=/S����LoFˌ�	݂���[���!����N�;I��P���g�{%_k1��hfnW�+��oz5Z�1��lb�h'p����)>���KU��*!M޵C�9���<���Q��f��qׅF~�����\_��N���5<4.8��U��ָ����f�o0��Do��NaƋI���g���4������ϟ>|��d����펺��#Z9+u	JN^���Sd�%=^-��*�C����X�qp�9	T����cլ��2YZ����3��D:f���ւK��Q���[f��pR'��魋���w�*z��鍃���!���I�?@ٸH�z���~&}��2�q�@�Y��B����!=8�LY }�����@J����=��G����>tS��7�#,A�W\a�b�.s��[�b��u6�Y!���QsOl� ln���=����Vhj����v˖w�D��~�eF��h��[p+�1/�I�R�f��q�������"����������������/�Ϧ_[
��\�z�42�V���8�zA*y�Dv)MR�&�!I
��W�|�\����&G�-�r߸]_�{zo�I��8+I��u%� _�¡���/�XO>��Ae�\�Gb��/�:���r�k���}���E�S���c�vz��?�
"X���7��ZB̅�@+kvVd��}j�y@I!����a�4���(�d&B���F��6�E��U�^r���7�_�r��;��&p/��c��-9�-�����!M���wq����[�k���;�j�%��Ӿe%Mn�i�1g���$
B�P�(#9-y�
I�}�.��<eYh�P�@�'�B]\x�M�L�bR�3����솞a�jej��X�C�!��q]���5���ѝ�b�M���=��¿�!Rl�tʰ*�|�,���́���'�/�4j�֝VMѰ�KzB�@�^P� Ed���z�RP?>mm�����;��2)�:�y/j�!yE>�>I2@?D�    1~�B��'�b�c��44��d�|}�6zV��u��6�|E�t��V:�o/��&d�Bii��Mj�b|�n�[r��R�	�7��1�q���d١���پ�Ż�Ț#]��a�s�Y~���ˋ�M2!�(�^T���:�Q0��MSP��s)���^��?Ɠ@z�Y�����|Y4���HlUɮ�洘jN6Ä���+
�1%����\f�z�qT�q^�� / >�4L	��Id�&�W`Q�v�}�QX�� R̓�$As�+e/��m�s�ic�q��7$Bcz��~ E�{+ؐ�Ga�Ic��*G?b�KL�<�=}дf6��w/{�k���q�ޒ3�d���"�q��jI>�<Po�,��͑�>m#T/^)���Ζ�-]��<�B�V�Ki��?vyY[t��Ä��1Rט\hKbHrX%�KC�1 �-IL���)��Cp�IWk�ay��9��y�w��(�}�ꓦ7�� I�����d��"�o��X�����S[3�l���9�Z�p#�)���~]m�9]4	n��W7��Mf���1�Z~^�l2=�o�*�	�~BQ���Շ���#�޿���A�m�|r?�p�n�9�w5z�"��o�ZK�K�x��K\����d��-s��+2��u���1���@>_��*$�4y�ɜ+i5�����29�Ȗh��ݧ�d?�s �F� \^j{0�(V��<�uH/A�0�ʹ��Bhݐ�#�v�DOg�P�������.����Ɛ�zM��C;:E	�1^�$����B�՟�§|������Ĳr:
�L4y!JT�"4dc
�B�|7�{��6t�UA�*��1�0��8�TT�~�V�!ݺ`v��� e�e�.���(��M�_4Fۑ���K`�}ء9��F�SY��z����`�� ?x'���]џ����:D�	�C��Sx��3���\���	G�<
�r��V��+Q���W�Sjs:T�%�S<�����&{8��=	���^�Kϻ�>�[��p[v*=6�i�ݏ��菣4$�����܌<z�Jqk� ��ir�\D/�d��ʭ-B��}��x38	���OL wK�V��qޓ{pR���~��"�߲��-�Md�bD�A#���9pS(Gʳ�9j?����,]��o�z�lu��צV��� ����!�(�@3��d�[ �8ˣ�	G��/Ad���Q��nHR���H�!�Q������~N��x<�W\h&��&ں�R#h�(�b
s _���z�_��c��y�Y�5�P��*,]�nV��B='x�t�2��{���o�_vK��j�~g���5p���E���;r?7��dS��?�)�W���%�#�[t�Ԯ\���2��?1j��S����+8��@�R0�lq���̮ �tBC B��Az�|g�dU�/��H���%��[oD8d�25C�c$���&�_.*uR�&k\�����������/�>c��.��?>�k�:<��G�H��7a��@�E1A%�0� 2��KWZs����f���睡��z^�=������b=�
=��hA�U�`}g�m[Jl�)!�"l��Ӌq�-� �����լ"�hU�**�����0��6����<�9�^߄"�L	w��� �C��g}g�]<���z��_�fP[�p7$�`�^3�ʧ��Pe�kw��`�N��T%��S$�I[�Ѯ�>�*!�X0�����"A�P5w�K7�L13JF�t3�e��b���zI�|��2��2D��/�kG:�=�xsQ9C��3+�`�W.�ANx���ePP.\���%�H�)��|P����,;�D�����s$Xu��䃼��z��T�id�U��0���%���J>�䀹|�t��-C�$'")μ��]�yX[�BLr�!���i/�~VR? t�j�EJ��`�"�(��SR
�;%�f���{�Oպ�?B������[s��PP>P�a�!��G�����6�Y�토���v+*?�$-o]
��6Z��������i���.&���q�䲆U��>!�C.K�z�����6��^�$,z��� �����Ng�$�C�/�h�<-c9���K{c����`w0���9������C�/uߑN5}��T��:G����(dy�#�Of�zQ6��,���<�}�θd���8��~JO�L�m��[�mW\HX��%7	����τ`z�[f问���,%{���w|��������{����zaj���b�ur��f7X�����f߮�O���7�}�����CJ�%Ǡ�
��c��b��kg���p�d��<�ư��WӖl#�КS`@N�,Z����f�P2��$ݬu� �n:݌��V���V�gߍ�.�M�ƽ�\K7>�S7Jҳ|J���3�z�(���rٴ�N.�W�I�K�3�样����N�#+��w�k:R,����2˘�������^n��\Q�����N�:�:��?�v)I1*j�0�b4���DK��L���Gȹ1��$�iP����Vi�����v�d�~��g��iM�H�:��`��ut't��3��Wi6�׻��������k��IL���Q�%��N1w��\�7�^S��P�x��_5=��T�4�V_a��a�`� ه����
_ 6.�	��EO�bE1]�~ �8&�7�l�sh�uo0*49c�`��j�@n��{�J-�b����*�h*�%��H�Ȁ.�mS~��TP�J�_��8�A�L�#c���p3�Y�W*��FywU"9�UA��2�X� %�7�ziWOt :֊kɫWO�+�ԟ����0v �"�xR������'���}��I=_ �D��x�H�J��(&��ipG@�d������|��=���`�s�Y���-��O�I;n�C��D҃]5mD��yD�EX�(�#�N)2&�;x��J�D.��N8���㍇4ay��ɴ�qW�/ug��	���̽�L��l���R+�e_��S����5���Y��p{�y�����9�j��ש%�
��Xl�vr���WI��\���wN�]/r���FG����2½BԐ?#�Ю��.��.�����0�E�d�P�#'9�����mô���t���ǌ&-A�9��%%o��ZAa�$�Zͽ&������/ƛ��v���<}�M��.�VKQ���#[��7ҕVϬѶ�WW����.��&���
g���M���2\D�E��/�;BT��WJ*������M�7o�0f�eFj�
c<����<�,2�E������g�d��O�SdA؝"��%�Zg�¾�^	w�B���K�!77�(n���������'������ٟ�l$�-��dDjH��ƽ
t~��p�|Se��JǖM�0�PlJ���QQ�n��?dz����T�EX�d}�H�[�^f٣���u�$�!"C?I���
�co��ߪ\��s����c|�pʄ�k�ߪ\��3���h��,ɰ��BIx���G<ӛB˭��{�e>���U�l��>����mU)��N�&��([���Q(~s
B�T��ք�+���h�m|�uy��7h�VS�3^������Md�mk)�h�
A�#=�s�cK���iE�͕���x?��*�ya�1�2y h�A��{�2�t_��Jz���a�ቬjT`��9]_dX��s�e��(�<�4�m]-�QW�6��(���4حg�6�Z���3A�ɩz���K��:��}�	���<Iup���w����pe�2�������#	Fg��2�j���a�	N��������7J~Ƽo���y�^�B{�\�g��[�{�>�|0m/L)ТQm�;+	���)b=�M��B�d�Uٷ�wy���:��p���>"����F{��\/ܓ�E�����A�r�gC'E���n�f�����=�]�57��gA�w��EA�
��]B�PE�z�����_��0�1�EYEJ��!��ڝjA�b>�2_�����F�|N$h�T�t��2�-�l��I�#�����S�Z�;#cI\>�̉��    ���.��H+i^���8&ݥ%]SQ���ш��*����.�ݪG�4�q49�2 ��n���D��%
����&P�[3=X��-��U�Q	�g����� E��G�]�3��K�C�iz3;��;[�JK�px�荕�����t5kq�=}}1�e�G�P��J;I�7
��s�������Y����.MG-.�2��O��'\��?Gn J��)�$���%)dԓ/�[҅�z�
����w��޳"���
|ʛ�n���� _���5��.p�S �3Z�r��K��X��K�[%j�uBKo����r	��)\�Ә Y<�ONe;s*��G�OZ(���
a�59*��t�Ւ.��|��1rG��<4�"}/��_��O�;����?�]�� ��@?��Q���$�e��e�K����u;D�A��l{GNN���?��=��6�[� �N�n�á��2�e�Ez���,��U���%�\?C+2y;�1j�XN���Lͱk���7}�E�عR��nʅ\�ꈎ��KI��o{�k!=�%�U2[�跋��Aw)��=v%�q�R�kR%�u���Ip���<�T)��St����_>��yOQ�u�
{��Pz%T�<(#��)Rgh��5QW4ȍoFU����?Aw��}�C�T/��[|κ�m�LJ����@�=W��k?��a,R��!6X��{h�!.��w;ZZ�na�XXcJ��6���y�Xw�Y:�I73Kn;v%�4�E6���:��hS����>F��R��0��W�UU���!2��f	�#��6&�ҟb�cN1U��	wN�qU�q��e�;&N��˕|���-ƝP_B�|�ö��j��G�̌BW�C��֖c6`�#��^e}���k������^��5��b]��i3 �{3��I2�9CִhLl[�|ŏ�=��xM�Gy��z���f�2lŵo���0(OJ!�����a©��j��nM�QԔ���!���A[k<�?͆R�H�S�K�^�'��[�o���̐k#�
)Ҕ��G�Dan��~G��Y;rywʡ�VS���Y�:R$5K7���N-���ɢq}w���^qԋ��|�XAtt�9��=�]��8yw���sO�$�j�$��,�/~N��n�	?j"�7e��}G�����fs��Jq���,_��PO�6r��~�nxe1z虃ks⮉U�<�b�yc�'�j�o��Z���˸���������V��5Y��$[v)�����&��R���nv� �,	��6�3��dox��4�GG��.�6��h���ۗ�OoR�y�gi."��	���c��o%}����U	_�hZA_jR���[��.��`Uݏ+��¯��)xn�=��ru��.�.�u���lvUt{�rD�$4t��ŷ_���"��:�f�q�R���!�N���il��e����7���
	
3Z�PʭV \�MC��8��,�|V����Ɋc���  *��T�q>ޭ���k�۽���
�)#��	s��ta�=�B�3H�a�/��q��>!ļNTD"p��T*��pR��o��]n(�X�H��(ʅ�\���5���0.��Ђ�G�k��L0TQ�mǢ�S�������Ԑ4O��a�]b��$6U��ج�5O�^_����y���SnH
�'��sq�5`a�.6�z���P��?��%)����=Xa�Y�g�ʒ���Yz�y�F[�U��*��(� ɽs'�9��q�,�b�S��O
��4$�ag�"#E�S��d1ƀ�n��gjC�Gp%�}��#'_H{-g��W �}���P�Ƈym2��Ii�+nx��d�ސh����i.,׹@"�G��$�'������<��U�bR�H�7�n�v��9GZe\�����U6�|{J�}A*)�5�U�k�A��pܩ�4<��X�'�{Q���ī��*ܱ����AG2XLOj������(����4��w(�nMq�k�ܑRk$� �x��]���ur�_ƕ/��o� P���)��>�=	�H���
&9?d$��+,|��̠$c'(��T/�B� yH�$ -u�tu:�n�����Q|��C:O�h���W���<.Pb"5�y��������Ҽ���c�[��u������o��-��O�^Z������ؕ�����^�'[/�]j-�V��U��N@��Bb��X�%�j�$�U�B*˻Ó�[?��m�;Q'9�[5ǔ�^8P�y��%������6��mm=��X6��,�m��Q��C��I'e��N`���r3�_�2ɷz-���XO^�=��?��bu�-I�q��Vr���?���L��n���/[g�B9�� Y�38���x��.��~�[��-������Ē�r�^��s����� -9�։S���N��{��s�� R<u��F�Ԥ��P+�`��ѻV�ijR��'���40�7#9-�+&�k�Ow^}���sւӽ��W�q
���	n���S�xГ��e�XRV�V]�c�4R�x�#D�4�a�4_�8DX�G �L[�&)����YΑ�Ƙ󸔠Ҵ��k"'�?���̪Г���)<E�2!_���9j���"��n��*jz�܇sJfk�x�d�i燻�~<��	DH`��ʒ�6�+i�ׯ�k4�(���As���'��j�Z����
�</U�*>=�:EN��I+4����v���qS28Ճ��"aTW�/쬳`�G�.'_/�
����]g�@�Cq�A�څ4 �Oj�+�cģ����is�u��'V��+�֢�j=�|He��um8�ʸ�Z�yv�	:�����d��N��b�݌�a�E0�)���z�u*�5�:�,�U�L��#��%�BZ�U�;���Qr���'W��лi݁�W�1�B48p��X�<صIn�E�9PO��D9m�Y�ܩͽp5�H;J������f�h-��|hI�}U�'�lƠ�^6�s
�����X��RTh+~@H�T�"�P�C�Q ���Bg��قg.��E&��¨Rt&��,��:��H&I3jɑM:�&_-9�H�c�~��P��>���9;/}}������䎉�`��#��itgEsaìn�>��:��);�U�(�G�X�8SIR�`L=�%
9$���i�u�M̖���r���C�s5+�H���L��q$���@�g���#z�9F'�p��Bٌ^�5�W�:��/Қ�ݕA�UM=�DY�|r���C�Of�E
 e؅H:D�ߧ Ӕ9�NI�I��NM)�J56U�`c(��dZN�
�7�ɻl}J�B���7;Ϭl����4�5�F�X�bR��=�$3˟�)�E���yۢM�an;D��H�¼	����'�Blu��'�ؚ�$�XKt������YKt������XK���(�Q��h/RmiV �$_��-4ͲA 'np%>R2dQ29:�5��*��h5U�kK��E׋u~y�[3Pm�\���ː�� E4�H� ������Gs�\/���p�����1I�u����a�Qc7bk��;/�ݏsk����y�S�Y�b�n��;���0S��s���r��?`�<w�//!h�ub��ڄ|*�W�Z�K���ڭ���w>���cUOS  �ZY�N~����VUO��E����î���*�!M��|˰=�t&8�U	^4�"?��J����9�>��Z�V#cz�O�2BI,�l��J�?2ʅ��FQ��˽E�N ���(!qM�-�'�5�Դ��r�)�<+�HU���7��s��,މ�)��� &_-�{��@�:L)��bs왎=�!0��^?���2�M���4s����U���Y5��a����t�(�`U=2�@��\�c�kø�Ur���iJ�xz���I�F2!� $�~�|���N�E�HW�ʴ��5�r0��=�CI�	M��4�U��;�/�=�a���Kև�X!*���Xz�m��x���;f<iꔬJ:���왅���."�x����z)+�EMK�d�W\���{LM�TzUQ�!q��?V��Zx�ɢ+����[��Ct{q�    �A��z�Irfp��ڸi�3�"��G�hj��, ����F`��
�椕�f��s���U~I�g�yh��5���c����q�T�0�MB8�@)Tlθ�ɣ�=h�\��ˊչ=�ߵ��uD��0t"�O+(j,I�(O3������Ͳ��nb�n֋fr��F%����5�H�.1]�,�%F���JP x���^�'9�i|����lh��|�	���0e�x�X�^�+C?r�JH������u�hQ��
��tOvEo&���Z��"/���D�n���l��m��5��ܒ�r�VwY��@�ΐ��,X�]���	Nw����w����'��#�����3�bZ}�YE�Nw����$��Ӥ!�F�Z�����/��޿�oƭHz����x�Hh�L6��Q�������bS�%�:�m�\lf0����&���P������[l��a��Ypa�<8��ӂl���{�@^�J׼h� �ʽ��Q����f3�f�r����JG'�E����#�N�(;�9�@7�i�N���j�Nk�w�}��)ê��Y�X�����8Ԁ���G��|����-G�@ť�8�� ��M���{�\�s���d���Bk������%i�v�sU"�L٭���2ŤՃ)Y�}(Dސ�Q��Z���U��0��Ͽ��
�� �_���=�]6�X�d���:�B�;y���*��y���Ԏ@�a�i��</�g�/��<l\[��T�����Yrl�W�Y� *��Ju�-C�SFݛ9��AF�P&���LK1(K�q+7D�dN�3��&z�Z�{�Ԑ[��1��ޖَ�d
ކ�N�%��K��"$J�|��"��r�t�j�Z�l�T���KM��4���Y��cn������fE�|u���V���'7�2�5i�q�o��[W����F���b^�&:U��gz���]%�[���I����k�RsAn=	M���>�pz|�I��vʾIf���Jp`��E�0�Ԓ��X� ԐP�ՂӶ���YT>T�,'O�J%�b(���S�?_���-����u�g�����������5+����e�F��X�"�-�q/� �&���Y�EXnw��5���W5�0n��p�>?YKM!C]�) �ƅ�pJZ�� ]�A>C��gZq~��a(à	}��jl����"��P2��EN��$��f���������z�32(���=�1  �y���D��}���n���(��	
�յ��c̀��q.�{��cM�u�N�Tq�fs�J� :Y�ӏ�3 ��,G�^{5ݼ�հ�G�!z��$���Pהg7�q�Jo
��,�Xo۪�?��#��V��4��(^��\�c�=,���l�n�<���4[_��az3{X
�ϪP�(���1��,������_�����ڻ �f�Wo0kq��L�EzB�jD����tdTS8nY1����M��OJ���ۺJG�y�+�<(b��!�>R�B��lc"Л��s��M��g����x���}���ÇOI��O�ߝ-�t7g���S�)�+�.˂;��R�Q(JH*
����8�jJ1�]�K"��PK
��W�����.�����k�:�OE�����-s�1A�1I�5��	jI�i>β��U�G���ZJ}����lgy�*�N����I���P�;m��������WݬEq�Ǘ��;_g�D��>n�l����P0��i���p�,��$$$��C7��6|yě����a7Bd���f����+�!I��
e������B�]7���rb��6�S�������A�8��uM��&����t3=����v�Y��I���:����l���8ͅ�'���
���$�4����;]H�G��6�
],4�/�L�{��Ia�b��[QDsݗMou�A!�'��񸴜��,F�&	I��`��z#�����
v�[c(�M7`��P(\}���|C�j�S�k���1@N&"w͈
րz�zkIK9ɴ��A�j3�����|��-�HB�]���Ă�����<�N|�ȕ��|��?�g�	 �H���68Z����Ն�2!1(кbC��<��8���hk��y�H+F���k�
A���Vs�+��z����i<�5���jO7�C铚�\��߹^MS�k�P%�:�.9�v�}M��LNլ���
��8��E�JPo\qXl�R�nB����1�W!,����湐��_Q$�?�-W�C<���V��7W�0�p;Iޡ8�]+�ԅuQ�*�O
���}�}��ۖ����
5	Bv�� ����3G��`�L���8�p�kk�Z�`�\���h �>@�u�>_pq��6�f����j����)��dc<�.���خۖ�v�o|:~'��Êb�ײ$O�E+�6��	r�sF�.�3kzq�$���wi{1��(��ˊ��j���<瘆o�ƌ�����?Ǫ_>�Ә׺��ݚu��s����ܹ�ةa�rN������`�W�T9y��`S�~���G����_�Ah��R����	��f�a${AK��r�`4�f��c$�담��|��A�e{�y7��c`W���I��p�(N�2�/�����ό�������gS��[v���Fu�QT�N���MgMw�"xC�� �p^J�+�#�^a(^" ����k���حt�2IO��04d���&�����m|P>��!x�@��"0l��h��,�&��?�Sl[<��~_���+l�=
��طf��4����9ϗ�)�=�?�|IR��C�
���;-�'[�Nߍ�M�HPr	���)Q�k�������!�ʨb��q]�*��Uy�S�I�~!�'���Y�J����'Y����mt�z�{�w�%��p�Ƴz�J���F�����OXtW�i��uὙ�g,�)u^6�Ue3�ڗN�lV�@Y6S,2_���*���(�h$�E�h;�\�1]�uqJ����d(,�SRP�@o �,\yKk�j��┛��t�튝����/�@.�8�d�����?%�O_��+���7����K����Ђb������uz�5��"������L%lb�v�"���?�/8����_�RBҨ%`��o��� :)W+�$W�q,J"�I^��� *+WM��ff�E:I(�L3r0��5ٌ(�:'��̦�mZQ�5iQ��2L�qÄV7�G�1��T-�	�Ǚytt���$v������ �p��ch�0�8�`�I5�e��-a��͍��':�%��E�f�ᨫ���)�jw6h�I�]1�3S��-��Ie�!dZ���?]\��t�Us��F
���"}�Ca�Sc��e�������t����t��Hvϲ"���ri��><G<�"E_��I�q��ͪa��es�	�X�cZDu�]�U��+:n�*�&W�%���eg��L;^�������(��&n��[�����b.oG|XD)[�:�F�"�}������A3����W��k
�'qa��3�/
?�l��,��
o���(&�w���7+;��ls�-	��)�qVH�!�J���R��r��6�{�Q [h
�E�Y/Z��I�v��|�+`tb����5)�n�t��v�B8�F�*N�V�m#����P,� ��R��D�8�t:�_U��"����_��܉PhNގ.�ٚ^�b���/^���wM��Hyw#=��]ж��֖_*g�l�y
6۪��xM�'B�ܘ�C�F/�Wa$�Cd§�;!n�|~�/ΖQjY�K�YX�h��-�dD�Sd`��2LÃC��Z�L����#O��S��I�:�;]Ի���!g�������w��]�����ǧ���&v�����І�Af*�������Q�K�x�Z������7��Jr|�>�XC�BA4����7��|�48�
���bn����"XˡRZ���
�d�手ք��HK��1H�D�N����e?�Ｉ�s��׬7��������DQ<g�>���t9.z����2�T�lZI��ԉ���([����֬e� �V���l�=����`e�)��    Z�}(�ilv�Jy�N��T�=	z#����q� s�{gA�����^�e�hR����޻R�B��)�¾�)��ZE22/��O�>�
�9C���dy<�b=��.h�B�7+c5���t�&S`+��X��-0A?|����s���'~�:[Y_�u�w4��kL�M�@�َ��)���|�]LA��	�a��x��̜d�E���"�Q�Ƚ�_ғ��2��]�]c\��]h3ԁa��.�Y���ba��k���.<��~����C�R�>����m7�S���^�'��猙N�O�0�N�K�͐���㏑F�����fj��y�P�w��ZY��w���<l�Et/f
����m��ۆ;��yӇ�il�Ճ�b� �LZN"�w������-�ے��ے܎*FuѢ��	S���C�l��=�Ym7��>FV�{�7�59���T	�ur��f7���g ;;���	ϒ���N��V1���@Z���:�V8����*����e�<c��v��	�8+��x�3�P�D�E�E�hp��e�䢈^v+�vq/Ow�S��yŉ�*n�d��V�-C���5p��a���#�6	�����S&=�m5f�I9�D��IYx�qk�N+0��f��ޒ�C���Z]�\��aZ�@�����
E�W�,޻3l���*%^m��z	��wS_����'��qg-���MNa�}1H��P����{s5=�U�
S%�b��su��bd2��^�&�t�Wݑ{W2��]���t`I�Q$|hr:�����Å�����J�PP#��7�.��\�t�i��/����c��\:��,2k��,���|�g3�u
��;�m���~�����A�Z����qi�\���Ŋ�����M,\�w7�ge�8�gd�����vw�tN������A�a)ԃ���SB��ى�0��z�}Q\1HX(F��j� �ux�"�\U6FZ�8_ {�7*U5tTՕ0�E����~�n��W�<���	�#�V�P0��l�k���^���I�V�e�0�m�Qʾ��m�!o�1���1PŹE��t�1ͱ#['�c}�j��<)�?���5��O���l�P!X�$���{���#��I�}�S�����̨��ǌt����������>�NK�����s���{�Sk)@��t����|j�BϢ�PX쾪wo_>���0�˄+fg�X�Y����O�삭Ƃc����IP�ʰY�U{���V-x"W�!|;�N<{��_u�Ua�Ȑ3
��떾ڌ�nR%����Lj�(�Y�$*W���и��1#X`�m<�i6)�t���o6�%��I����&s�Ξ��ɺ�A�bk�1�a|dU>k3��D�{`ǎ��2x��Ń2��2|'������y�/�0���hM�bW��7Oq�Pi���"��.]�悃�HikP��D^�Mim��ՠ9�)(���`���L��
g��Z����-<ϴ�6dx:ow� ���ذ"��0�| ��^aU��H�%)�kz��>r�`�P��,��?'�����n����/���%�[�����G� B1�%n3�<��`�(H'�s�*#!9M�፲q���N&�ǭ��*Uht:?H��v���ǭ\!��ԫ���n�u/|��t0G���	�wE��n|�[�χIP/�l���C`�e�@nz��-X���Mt���q�ܗ�~�%�Z�#R7a��^�e}�.J&u��[��>����h���Տ�z�������	��\��5֙G�*䜅�Vj�^]�7�n��C+i4n�3�Th�t�K��V��3���d���rPַiݢK�YV9��0Wq����C�����W�0ډ���	�P
���f
c�	������pêߏp���
�r���c��b���%>�䶢ý�L�_C��r���8�@�!s^�s}ep�N!pJ�^�b-p
�>���u�7v��Ӥ,�v�y�y����#��3Hab'�f�s�dpX^2J�u�?k��5�`Ru�W����'��аqӺƆo�v�
q`��k~HP�d(dנ�;��B�؆�n�L���-F���R����2-��2�r
WC�<粦D�xjn�BN����抎Y$���S�؋[��.�o�S�	&q��ޒ��Ύ�q��6�Ӓ��� ��R.4�zs#���0�tm��yvS��wRy�R��a���`�m�AAذGF�� ��\�X~���֔��M>E̵�[�{��ݭ����;x%FŇN;4�9%��W9�ڼk鍑��c�/5���	]�|���ᚵ3�j5(H%SpJ#�@�n3ʓ�5���:c�����O�|���QI�RG�#.^��	B'�u�sU�(Q���lHαd{�����4H���)4>4Rz�:ؘ>S�E� >��!�BPA��f�Dax�5��/Ag��>k�.|g�q/lل�Π��S4�o��U{�*)G��3\��2-k<�"��~��}ȓ�q5Z�V{Q�^�pE(��͑���#A&�^bS<
�1t�G拗� �]daS��\�����8��������2]^�6�h��i��߾~"4��ߝťҜ��tiΐ/�쒥 k(]�鑑�~���r�l��C5Y��g/Tہ���sH��V�S�궡���4��ݪ���]��8z&CV鄠��v�(rʦ�|���L�CHϽ�ם_Id�هT0B���q��F4Qs�����ГE-[��TlS�&��N��E}R¬k�2t]
D>Vq��A5-�y1��a��Ɋ�k�F2/��[���K�����]h�~���԰�wh��'|���P�����[2?B2ڊ��]��^�]'(��Axx�ٌ3�X�o�Txe�m[�5I��~o�E�� J
j���"�������P#f���n�>ϖ�M��M�i~��g���q~�\�е�������ݿJ~}�!�˧��qG�\������Oɻ�q��������+I_�ٗK2|�(xrv������˪��jK�l��*/�bp�5^�����݇Vv��o��п�7�ؿ�ȅԄ�}'�ݓ�������w3��*�A,e��V���B��p��gEG}�ϱTp�h��')7&)����Y�tZPPĮ��᱇g��Jچ�����C���p��z?�&aC{LOͲFzm�<������=4��� s�$���ѾI
ƌ$�sַSh~�џq�����@_^�Y��e����F�H��Pj/1�e��bTl�5)���d�V�U3=�t~�p��H��[W���Z)��X%Sn�k'q#߳e��;��"-�!+�@Q�,�I�ܔC�$�Ec~ڂZ�\-�z]2���fp�ه^Rr�=���q��WR�=f>�)��M�P�;۝S�$M�$��0�P��5�m���>2a�j{!D�]����"a�pɦ,)|��tS�F�Z�9V l���(�Ж��R�!����z6y8�f�\n�s�a2�v2˺���֛qX>|�L-����C5fW�
����a�
3c�wEm991)q�ؽ�Oyn�u���-�d�HKXĆ�ZQZ� ��y������ݗ�w�=}��t���x�:A�WP\����8�� k��y�{������8*��/�P~�r+;�szC.����@�Ӻ#tD�,��gt�� +�u��Q��x�w��1����P���DnI��m@�-����-��~�Nf���>Rh�\����ݷ�%�	�C���]2�7+�M�u����� �G�No����l�w}p�g�B���0��Rg���.&(�WY����U�~ċ����\����L�!ݰT�����~X���[e�0`6G�˘ԃjEb8EfX�8y�<Z�Dc�?j^���ҠPa��ad�3����|���<�����~��(-�C� ��;O���+�_Н�)/��&�͈��K����Eqְ��h�f~�����kOϫq��GEށ�^�-���V�>�x1���M�[��Ӹ�-��W�[��۰juh���
u%-�t���8,��� |��� ��Jf!-yi�$ٵ�䱑    �#k�Æ�0�[p:BSs����Lae�
�x3e��8��5�A?��
����^�q�?t9��uR�A�">߽��!lͥR�G��8�h��Jڪ��HJ�e^c����c�'9�-�__�!32[fâ�U��D[R�����G5!^��V8��3�o�Y��/Z��%�����>�Ve�kS[V�9�C�r�*D�w���A�N�2�N��������3��i4)s^k{�B�fƇ�T|�kn89��N7�o,1�J�����lr�vk={g�!�g9�llN���H�2���f���w!<���ee9K����>�w�H�>������_�?�������K��]7�k#U��!{ӽ2�k�w�l��+�qe�e�Z�P��z�@��V[.�6�2n�h�g�"��������ŉ�m�`L�)���UXul~��^miA-<ŇR
���<���(͒���8�� �C�����I�YJ�1���0E�K�@�j(����e+V�>�)%n����O�أF��c<��$BH����9xE�a�E';żwƹ�ӰCdX�ó�r>ɷ��6�	l��й�~x���S�Gg	*�N�q�(�Z�Xh�:�9:^�n�q��$�"%��+h�Ps̯�9�J k�9T�m`sʐ���t�����g��H��^�Q������h��h��\N|i-z���/tR�z.KN��_�ɽAT[���#�F�]G�G�HE�*����C���*��DG}�S@)Ձ�����S���9Bۆ�9�����KG��������j�L� ߈�����+���.c�`on�U���r��v��a�@Q�W,�qk���*�e-ǁ�~�c�\����ΪR�P��;:b�>vC�$3�h9�]�J ~ָ�sjy��믅��`�^����E�a���H���&�M)�Pie���}�VK/sw������B��Q��hԢw(��q��u4�|6�:
����3��.Sdob�R�d��}G;��G	Za��;��m)�NY7l��4�7H��ݸ���8G�a���bQTtJȶK2�^4� ��9t5^n���#�D�i��8,���86o���t�ܣo�j��Lz��d\��V��,-�х:����2�����|G�ۀI4�@����U�	72�P<F
�QirȲ=$>�2�7m��Q�i���C"�#9�j�dڠ��Uhn��P=��gAo�瘽D�K�݋��.q�� �$�,,_�Z38��p?�S��6[ʳ�W��Z;C9M������+j˶��r�z��:�wQ��pC/�Q��
������(+�2�'x�I�]����`{�P��h��H/^��.����<��VZ�cH��=�� I���ǐ$�;�P!�L�F��H��'�!$�k��G�A$�k�ۇ��	�5Н�pb�������� ū��F�2Qm�g]=dH�j��UX�md�59��_�]�� �ђau쩣7��>nf�s{���xV�в�w�5B_)�|~�'7�0}w����Y̆�Y*�L*�ɍ��c�L�]�%5zI�2�!�^�ڑܐ2�S>��L^й�$���Qc]���zP�>��K�x�UH&Uíפ.�F��vw�,z����#�c��)~�52������0���Ʋ�l�Π��y���r���`����SBb�7�E���j_B��(%���M>G���Yn��I�-$ϻ e?L�S$i\���y�X�)�)f�[$J�ݺ��F���*O�Ҕ�kG�~|��ZO�O����	��ȟ��EF�5�id���*�ݑ�zA�c��X�tn��Mr�R�� ���dc<t����t-X:;�ƻU�!�ptf�v-�ԁ��2�����3l��C�o,�lm�L�j�}����>;۵ip�Ր9�<��ɢy��YS�����gE�e�ރ3B�6e��.�vѹ���/�<�G���hM/r�ہ�p�΁|<�	$���J"�v���tu�tVْOt�c0~v�/M߭��7�#�E�ql�qH�)��&_�gX�Z�b�^�dŏs����c�Lr��ë�h�;� ���<����La��դi�3�W�2R���fԏ�p����a���b���{I�����C�s4��#����m�x�,��a�V�h�
֕h>ˉ���{�ʸ%lH���Fb��4�y�GC��<l�>�܂�]�iƘ}�AW��]�ρU�ϡ:�ï�s�O���;�Bϱ$B�=�hx=?�37���̬����,v�,XM�B��:Ki�]h$h�
s3H�1���ģ�a+nW��2v;.T�nO�_dA둾@n����Y�#-�Ɗ���LKi=��b�L|T�7�����!F��H�i!��*=��r�׬a�F��S��D�d�z�j��`��l��;��mŀ�8�RC����:��D��\�`���t�=�A�Oq~`���N��'	{k)�V������I�D��9���Nb�h�t㲰�\��ʸ�҉g�!q��Ct�����FÒ����
�B�;�m�io�A��4�~olE�\1�v���}�<JW��i�$Ɂ:�����5����M�	�;��x��`k=m䢋\ �.H����a�=�et���uZ~�i��1�[��^��g�~F�z�`U5��.{��=�h|�����Ԃն�Kh{���!gN���a1�`�۔�/'1�}��`���Tr��+�v�s��!ݲ�d��<9?́���@��zD��`�9-�Zk0{'��h�,�%��e�KD�-�z�`<p�����q8��>���Ь���wv4H�ª���u�c=���*�f�DG���]���f���|�:�'bhPǑ��V�v����Ɩ��j1�ʔ�p~���X�^p���:Lsr.��f����t3"C��-����u�d��=P%9��G���
��kdg%F��Z(C� _.JZ��"s�݄���F�Ft ��cw
^[Y�
J(�t�z��e�M+�w���0$_���I/3h�3�_쓆���9D��/���V��J� ۮ�vX�y��!I��~�h�K��E[�z1��@��B��w_ZZ_0�J��)��X��]�/���1�����\k%5��9�t�S�.�6H��F?][� =����8�l���nĊs��{#�}�E���#Is:IWߧ]MTt?{PJ���+�ޔ��סY��tWD�y�hA��,�!��"
-a%SJ�r�p+��5��-����̜̑e+�9��WT�>�6	�R�C�-�EK�Pb0�힑#:�>'�o�`������^�1Pl�1
�Ж�=Ԕ��aEu�g����U��J�K����L���g'�yD�|2ĵ��GT��<�)�[O@����&����1��iC�r��&���:]>u�Q���d�6g<��c���X^��M��"!|/��#�!���_�����(+d��ѧ_���+��d���p��;��G����Cƴ'��y؂|�ݭB�&d8�z�]�iB�͞��C��E�������)0"�����ݷ���XB�>���Ƒژܜ��/t���;W{�Ur��' g?�� U�	NY�H݁��"yʉ���\Θ�BBQ�bٕ�F;<�c��fa�yHo���Y�����V��]���)�5h��*)���l�_8���t��^R�MG�����|ٳ�#��c�]��-&f1C'��dֵ@��DKU����Mv���]�%z,Œ��@�B<�Ň���w_w�C�<����=	�M�rsF��+�EB���kz;���}��u���K�*�@�t�{�����7/3�.or��=��X���n�ҏ=fs�P��D�<�kv(�4��6�QĦ�r���k4�����M(��U�W���aU��)W�
,�<-���5�TCɕB}�T�܄2��_`��Ы;�]-��8+�)�ƃ'�6Dpc���<�թ��1k�#�;^���$%�P>�8����g�:x_�w���QK��[�q�I�0��I�o�j��%s4O�Rrf;��w�AnJ� B/B�A����(/1h��;�H�����*��>q/K�'    �XҜ|���?��.ɂ�\�E�I��p�5�;�[N��d��4��a��c���3f�M�?�o�����j��(I.��6cƬ'������_��t�)v��aY���-�LX�t�)�1裣6��m;�L��I��>5�B1��Gox|<~��]�.p��ì�#�}\��X��d�Ы0ݨ�IƩ^�Q+�X�ʐv��o��L7���̫��Mt��/_������}���ÇOI��O�:v�[帇nT��q��B��T�)o�j�SL��"�)S�@ݎ��	�"�X�؀�$��R�1Bܣ�{bģ���`��|��^n����l�i>W�1Z[A�A>�(MH�C�;L�0����H�ex�#(�ޣ�.K"�î�-f�ZJ�0�`�w#�P4�W���~d	�خoQ�큏�'x����j���%]K*��:�L����a�so�z�n,��E8N��p;\Bk��w�֩�i���rN^��b ����f�1�����@�AL���ʜ-��>'���H.�Z��=%ўCS�i<è��Z���q�+ţ�4%'�>�
��fg�͢K�����y���`�#��a�<�M`��J2+� �E��H��W�:�H�(F88>RJ��&(�~��_bZO�!�B8���/�:�LF���(��9X�"-C)���,�� lkY�2�Z��5�!g�}���Ξ��Ϣ�ol(�U�&�*�L!!�nq9��fy�9$*����U
(a¯����ڟً]�at�g��s����Y(��Z?mm`����R�/����KɃ�E�%4�6WRAK���E?W��*[{�!���<�J��jZ.�Zc�d�xhV��׫+�<v����/�="_<�]B��xtG������Dj���q,:Ԗ����'�z�΁j��MI���FI)T�K>��B�>�]����Z�Z)J�#`�)e��j+�Q�0�� uu�Q�vl�[���<�IY�rn3�R�G ���u��C�F���i�Yv�H������B
1��K�Z�p5"P5�kL�9���Q����x,��Gx�&lc5Nq��Ah 0��+�����XN�`�gg�%�9�ږ]�Ŭ*�q:E��\��R�6�����E�%]�6<��t5�K����)���>��,�lߠW��.�zD��^�"%U2����|�Or�%�C5����/ۍɲ|��
>�S7��E�$Jb��\�.W��"�� 23�si`����I�$s�B����
���5��焺'B@�����������Sȷl�6LA߇�����R��#G�<q�.3pE�a4�ڦ��ms���Г����y��p��g�Kt�7�o7��8�v���M{4'�׾w�"0%)�MXy���M�SBp?H������]u/�ᥤچ�Ķ��[e����9[�bӏ'���3霺�s���ir	��>S����x�-n�Ǽ����9���X���M*��UUT���p�����2j#�a�/�=%c�#ܭ�>a3%��2gɻ��iݤ�Gr�"NT(7��Ui�B�a��@������M�ox���6^w��@�Bޒy+��')����ȼ�QIn�j�ެW�t������		�yԩU]�<��S�\��=һӎfY������P_���v�ͨ�KL�P���)C
'��Z���bC
^w�J����-�4�a����2�������[f4�*�m��9��O ���~�D��9��5;��wG2����j����:�o&�ph�Ѕ���-�1#���6g��Ü��� �Μ�d���p���\I��C�s�,8ګ6��W�����\����J�/����>=^�HsO�z�C��5���uD]��|�P�+\��W:��u��g�D�#�"��C�C2��i=�ڷ���M}^�����6�T��K�<���(���d�3�� 愛t��λ�!���:A)�+)�⮽'tQ��jw������8����+gN����A���嵟MQC��+]h�� �h�5~�qq��2�?˒nÓ+�1=>�H.�ρvt,�|��W�Ҹ���o�3ݵ�P�c��iE�Yo� ��(Q�G_He,��Ӯ����ש�N�ݗ<y�v�_��k�?�u�7ՐN��)Ғ�8O�C̕��N�r�c7���ISW/�eNLv$�Z��~H]Z!!��������U�9�p^gf=䊍BQ�{A��u�����^�U�|�u��v��j���b��Ǒ�KE�&�)\4��@�x���$�m�R�X�S�����9?��{�,z�COټf '҃=����Έ?��)9HG�#����.��3˾�� š����1��o��۔2tM����Q�6���)r��[F���q��З X�+�Kv�����9��Н���f������&V]��=��X��)�\;a4K)��-$ʘsGx����wKf5���ՙ�M�1u��n��g|��ޢ�-f9�+��,,v��q䕅�S��K\�v��09ȂSGNn���M��O���~�r��Dn��{��E��e���^/��a~s�����K����R����H�{m(�����)aa&,�:/ɠ�ULU>WE;���q����f�6��\'�pv�����׏m����)���W�+D"UA��պ��� Z}������u܃S�ƌ�D�/$��c39PX��n�Y;$�|Q�PLuNi��ì�?�����9���>�E������/ſ����?Q�]|�o��Ͽ->���?}��ϟ��oR��Ǥ����/�~��8��Ǘ�{��i,YR���Z
Zv�´E�QWҝ[^��Fs�Ƌ�K��U��-��5����w�L|��ˬ�#���1ς�7��됹ŵ��'��_D���	�T�߽��°�%B(�Lg�0��ݷ����ge��_�>t��R+�j��`�UQa¤	�C1�ɑ����Z2r\�dh��(e\�����ɬ��ر�A�I'e��^
�޷9 S��Q��0:t�JMg���:vE[Ƈ��>�GӍ	�?<�.̱aYm�%Eߛu#�6�9�a)}oT���D蒢p-;I�R1��$�Xg�b����΀u�vçx�rw�ԛP6!��i-���8���L&O�ɳ0S�)���y�����۸Ńg�p��h� ���:��v��|��������iRr��CR�2� ���!7ˢs"����!O�T��v���E�t^)�m�YUܯCzP�����{��O���C���n�!S;9J��2J�� �\u�Z� �`��;�"��GP&g
������6n�)?���^���MU���q�=Q�f��,-�)�cQ���:/ַ����Bƛ4�U�E��R���ʐ"y��R�����P��(��J�ܾV0�Rq���Ds��C�T��Y8��9����:����~��SdL�c��I? 
���H���R�1`�I�^R,f�{�2�zƷZe���~�1`	��бy�g��;�as����1h���|��'�mo���;�������J=v��a��V3T��[Ts+�E�^C��}���4c����$��͝=���$�Zb>Sa�$m����x��56	CT5q�P���,�1"����{ıd�R��f����!x4Ex4��l_J����뗌��_ `�#a�"�$�x�����)��o����(z��Eu�;�bL��+����9�����bP���q+���,x׃�QqW@^Qi�{敡��l�ޞ���;����u}{��;���w�۽Y��J#��u�Y�f�E%��aT���������;v�+B�K�U�c�})�vA�Ds�0r���.XaOP_u�}�yV�hE�!ABKDB�0-5��*��Ss�
,YO���b��sm;�M;U�"�Z��	��<�4XO����0�R�^��`Q��-��ꁆ�W!�ZG8m�O�8i�!Q�;��zK{�-v��#���:B�b�)�l���@z��	�1!�]���b�+���5�h�:[�|�fK �z�1�K�:��СC���l���`�w�X���-�    e!+�_��A��N�C���p�߸�ȯ�+���b����[�~�C�Z�v���v�-V����P�~���z��F�mji����
x����������4g?�X�{��DΆ$-��	%�<�(���MT�N�z;4%}[��8o�7d�դ7�]�"
�� [ $��v~J{F��Ng���U�h��Pu�"!���o��ֶ	����~��"ڛ��j�~��pj:��P��=ZG��y�$z��A(؊e�����')�`��s��?�CO"�$�^��?m����l����0���`FW�}D���dO��P+�"2ڋ�v�(��W���� xϲ�1Ђ��Ӫ���*����Ԁ�l�ȃ/�OUF�"hȄq���h�F]��lY���I�!a��~��9d�-�_~����_�P���_�j��O��IS�^��](t��J	�O�x���/���AW3�^���H�(�->9M�;Q�4���iO�B/��5�j\�=&��4C�g��Ӯ�[����jc�Y�;+Z�iO$#$<���M���/�����?����G�{�(���P;�4��7�./��`��B}��t��ȋ�'��6��h� �Jbs�VG�}w��Np�W�뎾���?ѥ�{
��?�������c4ݢ�+��e�Ү���;�U�<7�}y�Nw1f9���,��|ђ]C��*LL�YR���;�~��k���,�����r����ҡ�Ⱦ"��%��#��	�o1����b��/�'�;a(�rء!MΉ�H���46+Ų��H<�b��Di�A#-P)�T������D�� ��=�<����[�����E-m1�����b�+m�E�i]r��B���6�.�4?&R���-�9B�P�H׆܄ K1?��N�/�L�%��FS>�4U�Q�]�cX?i'�7	)g�jY2)��39M��=h�o�Mft��!PI�/9g�~�gQo�֧`����)t7�D�$"���n{N��H��4�D��C#(��J"���R�,Z��⬹�h���K��@�!��?b�72('F�C��=�h%�9�xC�J��#;e�FY�x*߆�Rq��I�D��^�hO���+8E��I��gj���=��@��	5��Z�l��7N�d��z����%����QHu�,#.�������/��y��O�}~���ǡ�n�m����%g�)�~�����QP�{����W'�)��v8�q�,DC�K�_)�eY˱|s�1U�Eq��m��iY%���k��Z�˱�E�d�,��ګJ�,���&MbS���z@��+yJ��3�X�-�4�5�k�h��7Ql?��/�hd�M�I��۟��J�,t�����;qU�7-�H���B�S{iJ�1XL�Jk����U���Y?��7s�n���M���َ��}I.��ߥpc��v��g�g~)|���Y�X�N8/%ӎ�y����H{h��37d�%���Z���g��d8II�5BHô􋶩��]��n�M�UR�q���L��BUu"�bvC�L^(,H�vd�����[.f���f�BkY�d�|A�1h�Bm���I�1h��#��K7[�~����7X�'h�z�6l���@�Q9u4��VXH�0ϕ>���DoEK�eN�,�P�HJ3M�R�퐾�~�$�Jv�!)m���|�t�r�m�`�RqjQz����/r�U��P��<��L�aV���quqՒm-ɣG?'�������mXrjg����O���b�n�3~qS�ַ5�x���0�K6��͂��N�Rn6,�k��4�>�u޷B�,�y�
͇`��gC3C���)�:Km�];����UF�)�hV�{u��3�9�y���q���M�B����ה,V7&����z�c�v���ť�k���0�7�IkFE͢S6+�X\S遏���	���sZQVZ��r4�1�L��Y!M>�b��l�%?���
�����QN]������.�ה�CGHO��j�����w/N�\��&�RM�h�&O�i7������<�>��H�a�\F~U�1����c�#S��͠L�x2��ћE��Zq��N�GhB���J����C��dJi��Ge���hY$��J!-CJ�M_{$�ݶ��ݮ��8*3&��K)5��ڽi��Z��'���ҡ��e��1N�x$�v��B�v�fwJKT�~��C��Y,6cs54��6����t�W�}����#>
�hE��]���N�w���T+����xJ������>ú/��o�ާ
	����9�X�R�[���W����B���K\�qb\p߱��l��{6� j������Y��Ɛ(�v(=J2Nݩ_5�X}��������*��KY�3�+f�� �}�kH<�x�&9��9�ZJ��
���U\ˊi��~]�i/�U����mu�|����O�Qɷx ���ھ��d��%o.LɅ'�3��r�����CP�	�� ��{���"�F�~�}�e}�n�'}T�Ѿt�1�gB����e_DR�pޓ*$�>�:28I�hGkŸ�Y#��ݘ�m�q���WUqۜҋ7�>�<?��#��]玗�3�A��YI��P�WHZ4E�(�x�:��)-�'��/��<ٱw$=�J�^1���YC7C�ch^1['L��� -۬�7��?�H0-�f�nO�=k��P�AFH��S���L�%��\�q�8.�Ӫ>�PҪ��<Z�H]��`;@�:�tO��%�+5�.}G�3@	~l��������x:��>�V[M��p�g(�ʲ��t�-�����@�_�g�����|�����.�j�J����@���~.=��+��^)6���x�_�UqF�(�����A?�R&�(^3@ehұ��=I��ձW^eѣk5_�Uˏ�<�d(b��[e��+]���0{M���BjG19/���H|�h���)N<Nn�D<BG�\�IA������ !Wϣ�p���V͆��#� �B�6P�U��%��>UK\�(T0�ێQ:T�%��Gv�.k�l܏`jv�D�=�Gu޺��yԐ���XxZ����D�cn6(E�Ҧ�=[:E0��EttG���]��ٓ��c!��|LwZe-��u�c��2e\T�L��yL�,��"\*�DA;&��v��Ǉ����?�|V��r�I�f��r���\��;e�����|�8o6ݾE$�@��F���a��I=�!���6j��*oTH��O�y�[z��2TH�}� &��Y��u���*�_��+�Enze����<��\G��p��� [�Aơ4-�L֡��=���愱]	J=�w�t�^&���x.���1�wu��C3TX$��!(��c�<9Ƌu���R�~��q~�x�ȩ�G�}z��n����Fg��7��A]��
�vB	o�7�6��N8+]���7�\��VlK��i�(��V"�_��98�#Wi�������e�J��g�W@��W����%Rb�Lz� �uL�ȱ%ꄙ�s���%��D����-�R85��t#�Q�n,���/6�e	ڿfZ���{��v����><����QD5������G�Q�BZŏf�^'⠡�C)����p�� ���Y�������v�X9z:5�l�;�y�E�8�8-j�9�B�lrpH�~S:�����`V�c��wv� "��t���u�'Q���e�4C�{����fd.,�s��_A�tJ�Vm�5��/�����݁�\����9��9�p��Nfy.;��v�'Y�$�������}���ٶ}��,��Mh{�D;�[�S�����C�i��$�n��D�dMEq 5*�AQX��=eɬ6�tK;���ZMQ��CO��a?����M�%�D����$��VH,�I�(��M��ڱ��Ͻ֬n��#������ݼR��=l�٦��<(���)���q}��8-�ГTBY���n9��oQҡ�ڠ3U���(q�mߙ��h �"9%�)����裸�6k�̯�˂���c��*��=*�M#�� Wz�޴E�V2�� '����h W  [P��)�S���!T�:9�V7�(!�E�w�2����vUmn:���zy�1��WiG�(�:e�����iQJ��irle!�4���#7Mu�a�N�<Qޢ�3��2�u��kT��[7QG_4T��v��?�7�0�7=�!�M֌c)�j�5CXmAv��������ת��?�شn?m�_-����WK���1�TW�=��b	��N���x$�a9+����ޑ�P�M ��p�%}��.̒���U
92���ޟ6�eˤpo��'x�Ғ�V��h�9}�ˤ�]t!�)�j#�@����>��*N�|O����A����W7�%��Mr^Fe8��_����6:�y�Pژ1j�ۖ���3�����ꦸ��`��(1�kQ�~�~����n����O�����|���?���[��ԓ>��vXA��uA	�΋�8�r�ҵ�^���?���0��~d$�yo`?�{�R�|���q��o*�(lR~;�O~����
�w� l����0E�L��P�Ko���<�%�S�;���#����v���9<��cYgG++6�%e�N�hLCf�n�K�Ňb���7A(͟���S�qR��&��B9#&~���m���aR��t6$�����

�R:
E�7d���)���>��]�cO*�漏~��2�x�.Բ���JU�1<�*)S
(<�z�ZZ�=��i��d	W��(��S\Il5�ӽZ�����b�{d�ѽ������Sw��m+���4Q%������~��?���[k�GBe�͚qP(u=�Tk]ސ9�Y�����&�ԓv���H�1#b�i麃<�KX���*��u���X}���BjO���d�\ݧ���մ> �l�!|����"!�O?�n�0���!�J@�=t��v�M��iF-ݹ���X ����%�ٙ״'0����F]w�!ms�;Ļ; vcā��Yhh�q�� ���m ��>d!�;�p �8����K|N���z�S��WN8�����/9��Mu����j�����ǎ4���U��y��_�b�#B�^�����Zh�z��+��<�,<�����'��;�j��Sea��[��?k�r����~���.��10��t)&H���r��[[� è\��?���������W�V� w��t��������7E*b?`��T��� �汮��e��[.�~��V�x�dwe]�I,��f]��3�5�N.��`�(#�/"D�)����>�~�C�u�UWJ�o.bd�2t�#����v�˧߾����%���! Hl�	 �8����>y1F�D�Q��y#850Y�v`��\Z�~�����-"��=�����AG���������nl1�/}�ʢ�Vr������LgY�Oʨ 
J�mAN{��PB�76ξ~��'�#�S����>�E0 u0�zlGH@5k��v�&��xNݎ����䖃4���=��[��M�K�e�^�U>etA�pY�s>���{Vx0hI�I�B����Z����TO���v�ٍ����{O!���v)Q�ȓ>H'Ѝ�Jr���z��eyA��nV��@�($�\I�b�Yc�0,�����V��
����c7�*q)B��*���#I9D��RJ_
�@�B���Ҥ��mq_mW����`}�����]��+��Ӑ���U9�NB�c�U������D��ï_���Z%ӓ�1�ޟ	������5�g�3�Y���;T߯��y_��4��]�Cn��߬~����~ݧwҎP�fMqO��H�)��
�����Z��MqE �э�C}�K*߿���}��
�;w�B��{O�ęk�]9M���O�0����@�F�&�Ҽ�|�����*�Îf�01�gkվ��U��h�ќ�0=�L�IE]?�>����w��B<�)�8�_/��Wl6��'|���{I~�T)�d}��l��}B�`�V<�wA��Ι��0��L�	�O�+�@�9����ס�[/�r���z.�3'Å�gjp��A�p:{(���evV����{�'y���KôÏ,eq�i
t���Y��Vw�_�){� G��N�-�򿝕U	�f�3�iUz����t}K�������?�`         �   x��˽
�0 ���7�"��?8���6�4�.�i�﯃�$ݾ�o�+�F�cyL�`�P�G����x�p���>N�:�������4P�K�H�ϸ���Mhٟ;l9��@%Q�$�¯�餩/�s���P��:-��?l�Ro8Gz      �      x������ � �         X   x����K/����Ҏ�!�a��&\N�ř��
�
�y%��E�9���I�Ŝ�~P%�\��9�E��
��P
��� n�Sd����� g�)h      �   �   x�m��j�@����*��'��ŶRFA�BC6Ǚ3E���һoZ7d�-�w�S�BS?ͦ��m�7�?oVo5H�W�R��Ij4�)�PK�2e�"��0�	�v�.<&�0$�n���ދU�Z�M��ݭ��NF��ͬW�-	�A�(h�5�����*�?���=_i�<��6S��
�3��@�J�+�[�¬<�=3����!~���x';F#P��u�Yr��§� Rfc�v�y�DQ�v�l�     