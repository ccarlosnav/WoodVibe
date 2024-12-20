toc.dat                                                                                             0000600 0004000 0002000 00000054466 14657565543 0014502 0                                                                                                    ustar 00postgres                        postgres                        0000000 0000000                                                                                                                                                                        PGDMP       
                    |           woodvibe    13.15 (Debian 13.15-0+deb11u1)    13.15 (Debian 13.15-0+deb11u1) L                0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                      false         !           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                      false         "           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                      false         #           1262    82000    woodvibe    DATABASE     ]   CREATE DATABASE woodvibe WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE = 'en_GB.UTF-8';
    DROP DATABASE woodvibe;
                postgres    false         �            1259    172036    carrito    TABLE     	  CREATE TABLE public.carrito (
    id_usuario integer NOT NULL,
    id_producto integer NOT NULL,
    nombre_producto character varying(255) NOT NULL,
    cantidad integer NOT NULL,
    precio numeric(10,2) NOT NULL,
    nombre_producto_es character varying(255)
);
    DROP TABLE public.carrito;
       public         heap    postgres    false         �            1259    82002 
   categorias    TABLE     ~   CREATE TABLE public.categorias (
    id integer NOT NULL,
    nombre character varying(100) NOT NULL,
    descripcion text
);
    DROP TABLE public.categorias;
       public         heap    postgres    false         �            1259    82008    categorias_id_seq    SEQUENCE     �   CREATE SEQUENCE public.categorias_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 (   DROP SEQUENCE public.categorias_id_seq;
       public          postgres    false    200         $           0    0    categorias_id_seq    SEQUENCE OWNED BY     G   ALTER SEQUENCE public.categorias_id_seq OWNED BY public.categorias.id;
          public          postgres    false    201         �            1259    82010    checkout    TABLE       CREATE TABLE public.checkout (
    id integer NOT NULL,
    nombre character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    direccion text NOT NULL,
    country character varying(100) NOT NULL,
    state character varying(100) NOT NULL,
    codigo_postal character varying(20) NOT NULL,
    dui character varying(20) NOT NULL,
    subtotal numeric(10,2) NOT NULL,
    tax numeric(10,2) NOT NULL,
    total numeric(10,2) NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);
    DROP TABLE public.checkout;
       public         heap    postgres    false         �            1259    82017    checkout_id_seq    SEQUENCE     �   CREATE SEQUENCE public.checkout_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.checkout_id_seq;
       public          postgres    false    202         %           0    0    checkout_id_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.checkout_id_seq OWNED BY public.checkout.id;
          public          postgres    false    203         �            1259    90114    mis_productos    TABLE     o  CREATE TABLE public.mis_productos (
    id integer NOT NULL,
    name character varying(200) NOT NULL,
    description text NOT NULL,
    price numeric(10,2) NOT NULL,
    image character varying NOT NULL,
    status character varying(1) NOT NULL,
    stock integer NOT NULL,
    categoria_id integer,
    nombre_es character varying(200),
    descripcion_es text
);
 !   DROP TABLE public.mis_productos;
       public         heap    postgres    false         �            1259    90112    mis_productos_id_seq    SEQUENCE     }   CREATE SEQUENCE public.mis_productos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 +   DROP SEQUENCE public.mis_productos_id_seq;
       public          postgres    false    217         &           0    0    mis_productos_id_seq    SEQUENCE OWNED BY     M   ALTER SEQUENCE public.mis_productos_id_seq OWNED BY public.mis_productos.id;
          public          postgres    false    216         �            1259    82030    orden    TABLE     �  CREATE TABLE public.orden (
    id integer NOT NULL,
    customer_id integer NOT NULL,
    total_price numeric(10,2) NOT NULL,
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone NOT NULL,
    status character varying(1) NOT NULL,
    product_names text,
    quantities text,
    product_names_es text,
    CONSTRAINT orden_status_check CHECK (((status)::text = ANY (ARRAY[('1'::character varying)::text, ('0'::character varying)::text])))
);
    DROP TABLE public.orden;
       public         heap    postgres    false         �            1259    82034    orden_articulos    TABLE     �   CREATE TABLE public.orden_articulos (
    id integer NOT NULL,
    order_id integer NOT NULL,
    product_id integer NOT NULL,
    quantity integer NOT NULL
);
 #   DROP TABLE public.orden_articulos;
       public         heap    postgres    false         '           0    0    TABLE orden_articulos    ACL     �   REVOKE ALL ON TABLE public.orden_articulos FROM postgres;
GRANT SELECT,INSERT,REFERENCES,DELETE,TRIGGER,UPDATE ON TABLE public.orden_articulos TO postgres;
          public          postgres    false    205         �            1259    82037    orden_articulos_id_seq    SEQUENCE     �   CREATE SEQUENCE public.orden_articulos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 -   DROP SEQUENCE public.orden_articulos_id_seq;
       public          postgres    false    205         (           0    0    orden_articulos_id_seq    SEQUENCE OWNED BY     Q   ALTER SEQUENCE public.orden_articulos_id_seq OWNED BY public.orden_articulos.id;
          public          postgres    false    206         �            1259    82039    orden_articulos_id_seq1    SEQUENCE     �   ALTER TABLE public.orden_articulos ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.orden_articulos_id_seq1
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);
            public          postgres    false    205         �            1259    82041    orden_id_seq    SEQUENCE     �   CREATE SEQUENCE public.orden_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.orden_id_seq;
       public          postgres    false    204         )           0    0    orden_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.orden_id_seq OWNED BY public.orden.id;
          public          postgres    false    208         �            1259    82043    pedidos_detalles    TABLE     �   CREATE TABLE public.pedidos_detalles (
    id integer NOT NULL,
    pedido_id integer,
    producto_id integer,
    nombre character varying(255),
    cantidad integer,
    precio numeric(10,2),
    total numeric(10,2)
);
 $   DROP TABLE public.pedidos_detalles;
       public         heap    postgres    false         �            1259    82046    pedidos_detalles_id_seq    SEQUENCE     �   CREATE SEQUENCE public.pedidos_detalles_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 .   DROP SEQUENCE public.pedidos_detalles_id_seq;
       public          postgres    false    209         *           0    0    pedidos_detalles_id_seq    SEQUENCE OWNED BY     S   ALTER SEQUENCE public.pedidos_detalles_id_seq OWNED BY public.pedidos_detalles.id;
          public          postgres    false    210         �            1259    82048    rooms    TABLE     Y  CREATE TABLE public.rooms (
    id integer NOT NULL,
    name character varying(200) NOT NULL,
    price numeric(10,2) NOT NULL,
    image character varying(255) NOT NULL,
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone NOT NULL,
    status character varying(1) NOT NULL,
    stock integer NOT NULL
);
    DROP TABLE public.rooms;
       public         heap    postgres    false         �            1259    131090    sales    TABLE       CREATE TABLE public.sales (
    id integer NOT NULL,
    order_id integer NOT NULL,
    customer_name character varying(255) NOT NULL,
    product_name character varying(255) NOT NULL,
    quantity integer NOT NULL,
    purchase_date timestamp without time zone NOT NULL
);
    DROP TABLE public.sales;
       public         heap    postgres    false         �            1259    131088    sales_id_seq    SEQUENCE     �   CREATE SEQUENCE public.sales_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.sales_id_seq;
       public          postgres    false    219         +           0    0    sales_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.sales_id_seq OWNED BY public.sales.id;
          public          postgres    false    218         �            1259    82051    tipo_usuario    TABLE     g   CREATE TABLE public.tipo_usuario (
    id integer NOT NULL,
    tipo character varying(40) NOT NULL
);
     DROP TABLE public.tipo_usuario;
       public         heap    postgres    false         �            1259    82054    tipo_usuario_id_seq    SEQUENCE     �   CREATE SEQUENCE public.tipo_usuario_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 *   DROP SEQUENCE public.tipo_usuario_id_seq;
       public          postgres    false    212         ,           0    0    tipo_usuario_id_seq    SEQUENCE OWNED BY     K   ALTER SEQUENCE public.tipo_usuario_id_seq OWNED BY public.tipo_usuario.id;
          public          postgres    false    213         �            1259    82056    usuarios    TABLE     �  CREATE TABLE public.usuarios (
    id integer NOT NULL,
    usuario character varying(30) NOT NULL,
    password character varying(130) NOT NULL,
    nombre character varying(100) NOT NULL,
    correo character varying(80) NOT NULL,
    last_session timestamp without time zone,
    activacion integer DEFAULT 1 NOT NULL,
    token character varying(40) NOT NULL,
    token_password character varying(100),
    password_request integer DEFAULT 0,
    id_tipo integer NOT NULL
);
    DROP TABLE public.usuarios;
       public         heap    postgres    false         �            1259    82061    usuarios_id_seq    SEQUENCE     �   CREATE SEQUENCE public.usuarios_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.usuarios_id_seq;
       public          postgres    false    214         -           0    0    usuarios_id_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.usuarios_id_seq OWNED BY public.usuarios.id;
          public          postgres    false    215         e           2604    82063    categorias id    DEFAULT     n   ALTER TABLE ONLY public.categorias ALTER COLUMN id SET DEFAULT nextval('public.categorias_id_seq'::regclass);
 <   ALTER TABLE public.categorias ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    201    200         g           2604    82064    checkout id    DEFAULT     j   ALTER TABLE ONLY public.checkout ALTER COLUMN id SET DEFAULT nextval('public.checkout_id_seq'::regclass);
 :   ALTER TABLE public.checkout ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    203    202         o           2604    90117    mis_productos id    DEFAULT     t   ALTER TABLE ONLY public.mis_productos ALTER COLUMN id SET DEFAULT nextval('public.mis_productos_id_seq'::regclass);
 ?   ALTER TABLE public.mis_productos ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    217    216    217         h           2604    82066    orden id    DEFAULT     d   ALTER TABLE ONLY public.orden ALTER COLUMN id SET DEFAULT nextval('public.orden_id_seq'::regclass);
 7   ALTER TABLE public.orden ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    208    204         j           2604    82067    pedidos_detalles id    DEFAULT     z   ALTER TABLE ONLY public.pedidos_detalles ALTER COLUMN id SET DEFAULT nextval('public.pedidos_detalles_id_seq'::regclass);
 B   ALTER TABLE public.pedidos_detalles ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    210    209         p           2604    131093    sales id    DEFAULT     d   ALTER TABLE ONLY public.sales ALTER COLUMN id SET DEFAULT nextval('public.sales_id_seq'::regclass);
 7   ALTER TABLE public.sales ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    218    219    219         k           2604    82068    tipo_usuario id    DEFAULT     r   ALTER TABLE ONLY public.tipo_usuario ALTER COLUMN id SET DEFAULT nextval('public.tipo_usuario_id_seq'::regclass);
 >   ALTER TABLE public.tipo_usuario ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    213    212         n           2604    82069    usuarios id    DEFAULT     j   ALTER TABLE ONLY public.usuarios ALTER COLUMN id SET DEFAULT nextval('public.usuarios_id_seq'::regclass);
 :   ALTER TABLE public.usuarios ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    215    214                   0    172036    carrito 
   TABLE DATA           q   COPY public.carrito (id_usuario, id_producto, nombre_producto, cantidad, precio, nombre_producto_es) FROM stdin;
    public          postgres    false    220       3101.dat 	          0    82002 
   categorias 
   TABLE DATA           =   COPY public.categorias (id, nombre, descripcion) FROM stdin;
    public          postgres    false    200       3081.dat           0    82010    checkout 
   TABLE DATA           �   COPY public.checkout (id, nombre, email, direccion, country, state, codigo_postal, dui, subtotal, tax, total, created_at) FROM stdin;
    public          postgres    false    202       3083.dat           0    90114    mis_productos 
   TABLE DATA           �   COPY public.mis_productos (id, name, description, price, image, status, stock, categoria_id, nombre_es, descripcion_es) FROM stdin;
    public          postgres    false    217       3098.dat           0    82030    orden 
   TABLE DATA           �   COPY public.orden (id, customer_id, total_price, created, modified, status, product_names, quantities, product_names_es) FROM stdin;
    public          postgres    false    204       3085.dat           0    82034    orden_articulos 
   TABLE DATA           M   COPY public.orden_articulos (id, order_id, product_id, quantity) FROM stdin;
    public          postgres    false    205       3086.dat           0    82043    pedidos_detalles 
   TABLE DATA           g   COPY public.pedidos_detalles (id, pedido_id, producto_id, nombre, cantidad, precio, total) FROM stdin;
    public          postgres    false    209       3090.dat           0    82048    rooms 
   TABLE DATA           Y   COPY public.rooms (id, name, price, image, created, modified, status, stock) FROM stdin;
    public          postgres    false    211       3092.dat           0    131090    sales 
   TABLE DATA           c   COPY public.sales (id, order_id, customer_name, product_name, quantity, purchase_date) FROM stdin;
    public          postgres    false    219       3100.dat           0    82051    tipo_usuario 
   TABLE DATA           0   COPY public.tipo_usuario (id, tipo) FROM stdin;
    public          postgres    false    212       3093.dat           0    82056    usuarios 
   TABLE DATA           �   COPY public.usuarios (id, usuario, password, nombre, correo, last_session, activacion, token, token_password, password_request, id_tipo) FROM stdin;
    public          postgres    false    214       3095.dat .           0    0    categorias_id_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.categorias_id_seq', 11, true);
          public          postgres    false    201         /           0    0    checkout_id_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('public.checkout_id_seq', 1, false);
          public          postgres    false    203         0           0    0    mis_productos_id_seq    SEQUENCE SET     C   SELECT pg_catalog.setval('public.mis_productos_id_seq', 79, true);
          public          postgres    false    216         1           0    0    orden_articulos_id_seq    SEQUENCE SET     D   SELECT pg_catalog.setval('public.orden_articulos_id_seq', 2, true);
          public          postgres    false    206         2           0    0    orden_articulos_id_seq1    SEQUENCE SET     F   SELECT pg_catalog.setval('public.orden_articulos_id_seq1', 86, true);
          public          postgres    false    207         3           0    0    orden_id_seq    SEQUENCE SET     <   SELECT pg_catalog.setval('public.orden_id_seq', 183, true);
          public          postgres    false    208         4           0    0    pedidos_detalles_id_seq    SEQUENCE SET     F   SELECT pg_catalog.setval('public.pedidos_detalles_id_seq', 1, false);
          public          postgres    false    210         5           0    0    sales_id_seq    SEQUENCE SET     ;   SELECT pg_catalog.setval('public.sales_id_seq', 1, false);
          public          postgres    false    218         6           0    0    tipo_usuario_id_seq    SEQUENCE SET     B   SELECT pg_catalog.setval('public.tipo_usuario_id_seq', 1, false);
          public          postgres    false    213         7           0    0    usuarios_id_seq    SEQUENCE SET     ?   SELECT pg_catalog.setval('public.usuarios_id_seq', 159, true);
          public          postgres    false    215         �           2606    172040    carrito carrito_pkey 
   CONSTRAINT     g   ALTER TABLE ONLY public.carrito
    ADD CONSTRAINT carrito_pkey PRIMARY KEY (id_usuario, id_producto);
 >   ALTER TABLE ONLY public.carrito DROP CONSTRAINT carrito_pkey;
       public            postgres    false    220    220         r           2606    82071    categorias categorias_pkey 
   CONSTRAINT     X   ALTER TABLE ONLY public.categorias
    ADD CONSTRAINT categorias_pkey PRIMARY KEY (id);
 D   ALTER TABLE ONLY public.categorias DROP CONSTRAINT categorias_pkey;
       public            postgres    false    200         t           2606    82073    checkout checkout_pkey 
   CONSTRAINT     T   ALTER TABLE ONLY public.checkout
    ADD CONSTRAINT checkout_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.checkout DROP CONSTRAINT checkout_pkey;
       public            postgres    false    202         �           2606    90122     mis_productos mis_productos_pkey 
   CONSTRAINT     ^   ALTER TABLE ONLY public.mis_productos
    ADD CONSTRAINT mis_productos_pkey PRIMARY KEY (id);
 J   ALTER TABLE ONLY public.mis_productos DROP CONSTRAINT mis_productos_pkey;
       public            postgres    false    217         x           2606    82077 $   orden_articulos orden_articulos_pkey 
   CONSTRAINT     b   ALTER TABLE ONLY public.orden_articulos
    ADD CONSTRAINT orden_articulos_pkey PRIMARY KEY (id);
 N   ALTER TABLE ONLY public.orden_articulos DROP CONSTRAINT orden_articulos_pkey;
       public            postgres    false    205         v           2606    82079    orden orden_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.orden
    ADD CONSTRAINT orden_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.orden DROP CONSTRAINT orden_pkey;
       public            postgres    false    204         z           2606    82081 &   pedidos_detalles pedidos_detalles_pkey 
   CONSTRAINT     d   ALTER TABLE ONLY public.pedidos_detalles
    ADD CONSTRAINT pedidos_detalles_pkey PRIMARY KEY (id);
 P   ALTER TABLE ONLY public.pedidos_detalles DROP CONSTRAINT pedidos_detalles_pkey;
       public            postgres    false    209         �           2606    131098    sales sales_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.sales
    ADD CONSTRAINT sales_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.sales DROP CONSTRAINT sales_pkey;
       public            postgres    false    219         |           2606    82083    tipo_usuario tipo_usuario_pkey 
   CONSTRAINT     \   ALTER TABLE ONLY public.tipo_usuario
    ADD CONSTRAINT tipo_usuario_pkey PRIMARY KEY (id);
 H   ALTER TABLE ONLY public.tipo_usuario DROP CONSTRAINT tipo_usuario_pkey;
       public            postgres    false    212         ~           2606    82085    usuarios usuarios_pkey 
   CONSTRAINT     T   ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.usuarios DROP CONSTRAINT usuarios_pkey;
       public            postgres    false    214         �           2606    82091 0   pedidos_detalles pedidos_detalles_pedido_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.pedidos_detalles
    ADD CONSTRAINT pedidos_detalles_pedido_id_fkey FOREIGN KEY (pedido_id) REFERENCES public.checkout(id);
 Z   ALTER TABLE ONLY public.pedidos_detalles DROP CONSTRAINT pedidos_detalles_pedido_id_fkey;
       public          postgres    false    209    202    2932         �           2606    131099    sales sales_order_id_fkey    FK CONSTRAINT     y   ALTER TABLE ONLY public.sales
    ADD CONSTRAINT sales_order_id_fkey FOREIGN KEY (order_id) REFERENCES public.orden(id);
 C   ALTER TABLE ONLY public.sales DROP CONSTRAINT sales_order_id_fkey;
       public          postgres    false    219    204    2934                                                                                                                                                                                                                  3101.dat                                                                                            0000600 0004000 0002000 00000000106 14657565543 0014257 0                                                                                                    ustar 00postgres                        postgres                        0000000 0000000                                                                                                                                                                        157	57	Sandsberg Table	1	300.00	\N
152	17	Green Sofa	1	200.00	\N
\.


                                                                                                                                                                                                                                                                                                                                                                                                                                                          3081.dat                                                                                            0000600 0004000 0002000 00000000272 14657565543 0014272 0                                                                                                    ustar 00postgres                        postgres                        0000000 0000000                                                                                                                                                                        1	Sofas	\N
2	TV Furniture	\N
3	Beds	\N
4	Closets	\N
5	Bedsides_table	\N
6	Desks	\N
7	Office Chairs	\N
8	Cabinets	\N
9	Chairs	\N
10	Tables	\N
11	Open Cabinets	\N
12	Wall Cabinets	\N
\.


                                                                                                                                                                                                                                                                                                                                      3083.dat                                                                                            0000600 0004000 0002000 00000000005 14657565543 0014266 0                                                                                                    ustar 00postgres                        postgres                        0000000 0000000                                                                                                                                                                        \.


                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           3098.dat                                                                                            0000600 0004000 0002000 00000043710 14657565543 0014306 0                                                                                                    ustar 00postgres                        postgres                        0000000 0000000                                                                                                                                                                        39	Idanas Bedside Table	The IDANÄS bedside table offers a blend of modern design and practical functionality, making it a stylish and versatile addition to any bedroom.	39.99	idanas.png	1	59	5	Mesilla de noche Idanas  	La mesilla de noche IDANÄS ofrece una mezcla de diseño moderno y funcionalidad práctica, lo que la convierte en un complemento elegante y versátil para cualquier dormitorio.  
28	Sagesund Bed	The SAGESUND bed combines modern design with exceptional comfort to create a serene sleeping environment.	260.00	sagesund.png	1	26	3	Cama Sagesund	La cama SAGESUND combina un diseño moderno con un confort excepcional para crear un entorno de descanso sereno.  
42	Hemnes White Bedside Table	The HEMNES white bedside table combines classic charm with modern functionality, offering a stylish and practical solution for your bedroom. 	30.00	hemneswhite.png	1	59	5	 Mesilla de noche blanca Hemnes 	La mesilla de noche blanca HEMNES combina el encanto clásico con la funcionalidad moderna, ofreciendo una solución elegante y práctica para su dormitorio.   
38	Hemnes Bedside Table	The HEMNES bedside table brings classic charm and versatile functionality to your bedroom. 	45.00	hemnes.png	1	59	5	Mesilla de noche Hemnes  	La mesilla de noche HEMNES aporta un encanto clásico y una funcionalidad versátil a tu dormitorio.   
12	Alef Chair	The ALEF elegant orange chair brings a vibrant touch of sophistication to any modern space. 	269.00	alef.png	1	20	7	Silla Alef	La elegante silla naranja de ALEF aporta un vibrante toque de sofisticación a cualquier espacio moderno.   
17	Green Sofa	A vibrant green sofa stands out as the focal point of the room. Its lush, verdant hue evokes images of nature and freshness. 	200.00	greensofa.png	1	77	1	Sofá verde  	Un sofá verde vibrante destaca como punto focal de la habitación. Su tono verde y exuberante evoca imágenes de naturaleza y frescura.   
27	Wood TV Cabinet	This elegant wood TV cabinet brings a touch of classic charm and warmth to your living space. Expertly crafted from high-quality wood, it features a rich, natural finish that highlights the grain and texture of the material, adding a sophisticated and timeless appeal to your room.	270.00	habitat.jfif	1	70	2	Mueble de TV de madera  	Este elegante mueble de TV de madera aporta un toque de encanto clásico y calidez a su salón. Fabricado por expertos en madera de alta calidad, presenta un acabado rico y natural que resalta el grano y la textura del material, añadiendo un atractivo sofisticado y atemporal a su habitación.  
32	Slattum Bed	The SLATTUM bed combines simplicity and functionality in a stylish and modern design. 	220.00	slattum.png	1	57	3	Cama Slattum	La cama SLATTUM combina sencillez y funcionalidad en un diseño elegante y moderno.   
30	White Bed	The white bed is a timeless and versatile piece that brings a fresh, clean look to any bedroom.	315.00	whitebed.png	1	15	3	Cama de color Blanco	La cama blanca es una pieza atemporal y versátil que aporta un aspecto fresco y limpio a cualquier dormitorio.  
15	Skogsta Table	The Skogsta kitchen table brings rustic charm and sturdy craftsmanship to your dining area, combining a timeless design with practical functionality.	350.00	skogsta.png	1	18	10	Mesa Skogsta	La mesa de cocina Skogsta aporta un encanto rústico y una robusta artesanía a su comedor, combinando un diseño atemporal con una práctica funcionalidad.  
34	Platsa Closet	The PLATSA closet system is designed to offer flexible and efficient storage solutions with a modern touch. 	200.00	platsa.png	1	18	4	Armario Platsa	El sistema de armarios PLATSA está diseñado para ofrecer soluciones de almacenamiento flexibles y eficientes con un toque moderno.   
16	Vilhatten closet	The VILHATTEN closet is designed to offer both style and practicality, making it an ideal choice for modern storage solutions.	130.00	vilhatten.png	1	59	4	Armario Vilhatten	El armario VILHATTEN está diseñado para ofrecer tanto estilo como practicidad, lo que lo convierte en una opción ideal para soluciones de almacenamiento modernas.  
37	Hattasen Bedside Table	The HATTASEN bedside table combines sleek design with practical functionality, making it a stylish addition to any bedroom. 	59.99	hattasen.png	1	60	5	Mesilla de noche Hattasen  	La mesita de noche HATTASEN combina un diseño elegante con una funcionalidad práctica, por lo que es una adición elegante a cualquier dormitorio.   
45	Bekant Desk	The BEKANT desk offers a blend of modern design and ergonomic functionality, making it an ideal choice for creating a comfortable and efficient workspace.	269.99	bekant.png	1	14	6	Escritorio Bekant  	El escritorio BEKANT ofrece una mezcla de diseño moderno y funcionalidad ergonómica, lo que lo convierte en la opción ideal para crear un espacio de trabajo cómodo y eficiente.  
33	Pax Closet	The PAX closet system offers a versatile and customizable storage solution that seamlessly blends functionality with modern design.	270.00	pax.png	1	17	4	Armario Pax	El sistema de armarios PAX ofrece una solución de almacenamiento versátil y personalizable que combina a la perfección funcionalidad y diseño moderno.  
40	Olerdallen Bedside Table	The ÖLREDALLEN bedside table offers a sophisticated and contemporary touch to your bedroom decor.	69.99	olerdallen.png	1	59	5	Mesilla de noche Olerdallen  	La mesilla de noche ÖLREDALLEN ofrece un toque sofisticado y contemporáneo a la decoración de su dormitorio.  
21	Modern Black Bed	A modern black bed commands attention with its sleek and sophisticated design. 	499.99	Modern Bed in Black.jpg	1	10	3	Cama moderna negra  	Una moderna cama negra llama la atención por su diseño elegante y sofisticado.   
29	Ramnaj Bed	The RAMNAJ bed exudes contemporary elegance with its refined design and versatile functionality. 	239.99	ramnaj.png	1	29	3	Cama Ramnaj	La cama RAMNAJ irradia elegancia contemporánea con su diseño refinado y su funcionalidad versátil.   
36	Grimo Closet	The GRIMO closet offers a sophisticated and functional storage solution with a touch of modern elegance. 	300.00	grimo.png	1	15	4	Closet Grimo	El armario GRIMO ofrece una solución de almacenamiento sofisticada y funcional con un toque de elegancia moderna.  
57	Sandsberg Table	The Sandsberg kitchen table combines classic design with modern functionality, offering a stylish and practical centerpiece for your dining area. 	300.00	sandsberg.png	1	20	10	  Mesa Sandsberg	La mesa de cocina Sandsberg combina el diseño clásico con la funcionalidad moderna, ofreciendo un centro de mesa elegante y práctico para su comedor. 
55	Voxlov Chair	he Voxlov chair merges contemporary design with exceptional comfort, making it an ideal choice for enhancing both your home and office spaces.	29.99	voxlov.png	1	45	9	Silla Voxlov  	a silla Voxlov fusiona un diseño contemporáneo con un confort excepcional, lo que la convierte en una opción ideal para realzar tanto los espacios de su hogar como los de su oficina.  
10	Knoxhult Cabinet	The Knoxhult cabinet combines practical functionality with a sleek, modern design, making it an excellent addition to any living space or office	559.99	knoxhult.png	1	15	8	Armario Knoxhult  	El armario Knoxhult combina una funcionalidad práctica con un diseño elegante y moderno, lo que lo convierte en un excelente complemento para cualquier sala de estar u oficina.  
64	ENHET	This sleek wall-mounted bathroom cabinet features a natural wood finish and three interior shelves. Ideal for storing toiletries and essentials, it combines modern style with practical storage.	65.00	0816138_PE773287_S4.jpg	1	3	12	ENHET	Este elegante mueble de baño de pared presenta un acabado en madera natural y tres estantes interiores. Ideal para guardar artículos de aseo y básicos, combina un estilo moderno con un práctico almacenamiento.
22	Black TV Furniture	The black TV furniture piece is a sleek and functional addition to any modern living space. Its deep, rich black finish exudes sophistication and creates a striking visual anchor in the room.	180.00	black.jpg	1	38	2	Mueble de TV negro	El mueble de TV negro es un complemento elegante y funcional para cualquier espacio moderno. Su acabado en negro intenso y profundo destila sofisticación y crea un llamativo anclaje visual en la habitación.  
18	Reclining Sofa	A versatile reclining sofa offers the ultimate in comfort and functionality. This piece features a sturdy frame upholstered in durable, easy-to-clean fabric. 	280.00	reclinable.png	1	16	1	Sofá reclinable  	Un versátil sofá reclinable que ofrece lo último en confort y funcionalidad. Esta pieza cuenta con una robusta estructura tapizada en un tejido duradero y fácil de limpiar.   
51	Sektion Cabinet	The sektion cabinet is a versatile storage solution that combines modern design with practical functionality. 	370.00	sektion.png	1	8	8	  Gabinete Sektion	El armario sektion es una solución de almacenamiento versátil que combina un diseño moderno con una funcionalidad práctica  
48	White Desk	The white desk brings a fresh, clean aesthetic to any workspace, combining modern design with versatile functionality.	215.00	betsson.png	1	19	6	Escritorio blanco  	El escritorio blanco aporta una estética fresca y limpia a cualquier espacio de trabajo, combinando un diseño moderno con una funcionalidad versátil.  
63	HEMNES	This white wall-mounted bathroom cabinet offers five open shelves for toiletries and towels. A black metal bar adds support and modern style. Durable and elegant, it's perfect for any bathroom.	49.99	TEST.jpg	1	35	12	HEMNES	Este mueble de baño blanco montado en la pared ofrece cinco estantes abiertos para artículos de tocador y toallas. Una barra de metal negro añade soporte y estilo moderno. Duradero y elegante, es perfecto para cualquier cuarto de baño.
24	Big TV Furniture	This spacious and robust big TV furniture is designed to accommodate larger televisions and provide ample storage solutions for your entertainment needs.	389.99	besta.png	1	7	2	  Mueble para TV de grandes dimensiones	Este amplio y robusto mueble para televisor grande está diseñado para alojar televisores de mayor tamaño y ofrecer amplias soluciones de almacenamiento para sus necesidades de entretenimiento.  
26	TV Cabinet	This stylish TV cabinet is designed to offer both function and flair to your entertainment space. Featuring a robust construction and a sleek finish, it provides a sophisticated platform for your television while integrating seamlessly into your decor. 	170.00	tvcabinet.jpg	1	50	2	 Mueble para TV	Este elegante mueble de TV está diseñado para ofrecer funcionalidad y estilo a su espacio de entretenimiento. Con una construcción robusta y un acabado elegante, proporciona una plataforma sofisticada para su televisor mientras se integra perfectamente en su decoración.   
14	Rakkestad Closet	The RAKKESTAD closet is a perfect blend of functionality and contemporary design, offering a stylish solution for all your storage needs. 	229.99	rakkestad.png	1	24	4	Armario Rakkestad 	El armario RAKKESTAD es una mezcla perfecta de funcionalidad y diseño contemporáneo, que ofrece una solución elegante para todas sus necesidades de almacenamiento.   
9	Gray Sofa	A sophisticated gray sofa anchors the living space. Its neutral tone exudes versatility and timeless elegance.	269.00	graysofa.png	1	0	1	Sofá Color Gris	Un sofisticado sofá gris decora el salón. Su tono neutro irradia versatilidad y elegancia atemporal.
50	Renberget Chair	The RENBERGET chair combines functional design with a sleek, modern aesthetic, making it a versatile choice for a variety of settings.	170.00	renberget.png	1	28	7	Silla Renberget  	La silla RENBERGET combina un diseño funcional con una estética elegante y moderna, lo que la convierte en una opción versátil para una gran variedad de ambientes.  
58	Melltorp Table	The Melltorp table combines sleek, modern design with practical functionality, making it an excellent choice for contemporary dining and kitchen spaces. 	240.00	melltorp.png	1	18	10	Mesa Melltorp  	La mesa Melltorp combina un diseño elegante y moderno con una funcionalidad práctica, lo que la convierte en una excelente elección para espacios de comedor y cocina contemporáneos.   
31	Mam Bed	The MAM bed offers a blend of modern design and functional elegance, making it a standout piece in any bedroom.	199.99	mam.png	1	36	3	Cama Mam	La cama MAM ofrece una mezcla de diseño moderno y elegancia funcional, lo que la convierte en una pieza destacada en cualquier dormitorio.  
25	Momnes TV Furniture	This modern TV furniture combines sleek design with functionality to create a contemporary centerpiece for your living space. 	400.00	momnes.png	1	9	2	Mueble TV Momnes  	Este moderno mueble de TV combina un diseño elegante con funcionalidad para crear una pieza central contemporánea para su espacio vital.   
41	White Bedside Table	The white bedside table brings a touch of elegance and simplicity to your bedroom, seamlessly enhancing any decor style with its clean, modern design. 	40.00	nem.png	1	60	5	Mesilla de noche blanca  	La mesilla de noche blanca aporta un toque de elegancia y sencillez a su dormitorio, realzando a la perfección cualquier estilo de decoración con su diseño limpio y moderno.   
23	White TV Furniture	This sleek and modern white TV furniture is designed to enhance any living space with its clean, minimalist aesthetic. 	159.99	white.jpg	1	30	2	Mueble TV Blanco  	Este elegante y moderno mueble de TV blanco está diseñado para realzar cualquier espacio con su estética limpia y minimalista.   
43	Fredde Desk	The FREDDE desk combines modern design with practical functionality, making it a versatile and stylish addition to any home office or workspace	249.99	fredde.png	1	28	6	Escritorio Fredde	El escritorio FREDDE combina un diseño moderno con una funcionalidad práctica, lo que lo convierte en un complemento versátil y elegante para cualquier oficina doméstica o espacio de trabajo.  
46	Trotten Desk	The TROTTEN desk combines sleek design with practical functionality, offering an ideal workspace for both home offices and professional settings.	199.99	trotten.png	1	19	6	Escritorio Trotten  	El escritorio TROTTEN combina un diseño elegante con una funcionalidad práctica, ofreciendo un espacio de trabajo ideal tanto para oficinas domésticas como para entornos profesionales.  
49	Gruppspel Chair	The GRUPPSPEL office chair combines ergonomic design with modern style, making it an excellent choice for enhancing comfort and productivity in any workspace.	180.00	gruppspel.png	1	29	7	Silla Gruppspel	La silla de oficina GRUPPSPEL combina un diseño ergonómico con un estilo moderno, lo que la convierte en una excelente opción para mejorar el confort y la productividad en cualquier espacio de trabajo.  
54	Nordviken Chair	The Nordviken kitchen chair blends timeless design with modern functionality, making it an elegant and practical choice for your dining area	39.99	nordviken.png	1	49	9	Silla Nordviken  	La silla de cocina Nordviken combina un diseño atemporal con una funcionalidad moderna, lo que la convierte en una opción elegante y práctica para su comedor.  
47	Double Pedestal Desk	he double pedestal desk offers a blend of classic elegance and functional design, making it an excellent choice for both home offices and professional environments. 	320.00	81TRTo9lI9L._AC_UF894,1000_QL80_.png	1	18	6	Escritorio de doble pedestal  	El escritorio de doble pedestal ofrece una mezcla de elegancia clásica y diseño funcional, lo que lo convierte en una excelente elección tanto para despachos domésticos como para entornos profesionales.   
11	Black Sofa	A sleek black sofa dominates the room. Its smooth leather upholstery gleams under the light, exuding an air of sophistication and modernity. 	120.00	blacksofa2.png	1	59	1	  Sofá de color Negro	Un elegante sofá negro domina la estancia. Su suave tapicería de piel brilla bajo la luz, exudando un aire de sofisticación y modernidad.   
35	Klepstad Closet	The KLEPSTAD closet combines practicality with a sleek, modern design to offer an efficient storage solution for any home.	250.00	klepstad.png	1	29	4	  Armario Klepstad	El armario KLEPSTAD combina la practicidad con un diseño elegante y moderno para ofrecer una solución de almacenamiento eficaz para cualquier hogar.  
44	Idasen Desk	The IDASEN desk combines modern aesthetics with practical functionality, making it an excellent choice for any home office or study area. 	290.00	idasen.png	1	19	6	Escritorio Idasen  	El escritorio IDASEN combina una estética moderna con una funcionalidad práctica, lo que lo convierte en una excelente elección para cualquier oficina doméstica o zona de estudio.   
53	Xhult Cabinet	The XHULT cabinet combines modern functionality with sleek design, making it a stylish and practical addition to any home or office.	390.00	xhult.png	1	8	8	Gabinete Xhult  	El gabinete XHULT combina una funcionalidad moderna con un diseño elegante, lo que lo convierte en un complemento elegante y práctico para cualquier hogar u oficina.  
19	Glostad Sofa	his sofa is a compact and affordable piece with a contemporary design. It features clean lines and a minimalist aesthetic, making it suitable for modern living spaces. 	240.00	glostad.png	1	61	1	Sofá Glostad	Este sofá es una pieza compacta y asequible de diseño contemporáneo. Sus líneas depuradas y su estética minimalista lo hacen idóneo para espacios modernos.   
20	White Sofa	A pristine white sofa serves as a striking centerpiece in the room. Its crisp, clean color radiates a sense of purity and spaciousness, instantly brightening the entire space.	290.00	uppland.png	1	61	1	Sofá blanco  	Un sofá blanco inmaculado es el centro de atención de la habitación. Su color nítido y limpio irradia una sensación de pureza y amplitud, iluminando al instante todo el espacio.  
56	Skansas Chair	The SKANSAS chair combines modern elegance with practical comfort, making it an excellent addition to various spaces, from dining rooms to home offices. 	19.99	skansas.png	1	47	9	Silla Skansas  	La silla SKANSAS combina la elegancia moderna con la comodidad práctica, lo que la convierte en un excelente complemento para diversos espacios, desde comedores hasta despachos domésticos.   
\.


                                                        3085.dat                                                                                            0000600 0004000 0002000 00000017141 14657565543 0014301 0                                                                                                    ustar 00postgres                        postgres                        0000000 0000000                                                                                                                                                                        137	51	120.00	2024-08-09 10:53:53	2024-08-09 10:53:53	1	Black Sofa	1	  Sofá de color Negro
129	51	269.00	2024-08-09 09:23:09	2024-08-09 09:23:09	1	Gray Sofa	1	Sofá color gris
131	51	120.00	2024-08-09 10:28:19	2024-08-09 10:28:19	1	Black Sofa	1	Sofá de color negro
133	136	853.00	2024-08-09 10:44:47	2024-08-09 10:44:47	1	White Bed,Gray Sofa	1,2	Cama de color blanco,Sofá de color gris
135	51	49.99	2024-08-09 10:51:45	2024-08-09 10:51:45	1	HEMNES	1	HEMNES
139	51	290.00	2024-08-09 11:15:45	2024-08-09 11:15:45	1	White Sofa	1	Sofá blanco  
102	51	240.00	2024-08-04 23:52:35	2024-08-04 23:52:35	1	Glostad Sofa	1	Sofá Glostad
103	51	719.98	2024-08-04 23:54:03	2024-08-04 23:54:03	1	Rakkestad Closet,Sagesund Bed	2,1	Armario Rakkestad,Cama Sagesund  
104	51	699.98	2024-08-05 00:01:38	2024-08-05 00:01:38	1	Rakkestad Closet,Glostad Sofa	2,1	Armario Rakkestad,Sofá Glostad
105	51	240.00	2024-08-05 00:06:46	2024-08-05 00:06:46	1	Glostad Sofa	1	Sofá Glostad  
106	51	370.00	2024-08-05 00:49:48	2024-08-05 00:49:48	1	Sektion Cabinet	1	Gabinete Sektion  
107	51	200.00	2024-08-05 01:00:25	2024-08-05 01:00:25	1	Green Sofa	1	Sofá de color verde  
161	51	810.00	2024-08-14 19:28:21	2024-08-14 19:28:21	1	Pax Closet	3	Armario Pax
110	51	400.98	2024-08-05 01:06:41	2024-08-05 01:06:41	1	Mam Bed,Poopie Crow	2,1	Cama Mam,Poopie Crow   
111	51	240.00	2024-08-05 01:10:27	2024-08-05 01:10:27	1	Glostad Sofa	1	Sofá Glostad  
112	51	120.00	2024-08-05 01:10:51	2024-08-05 01:10:51	1	Black Sofa	1	Sofá de color negro  
113	51	120.00	2024-08-05 01:11:42	2024-08-05 01:11:42	1	Black Sofa	1	Sofá de color negro  
114	51	269.00	2024-08-05 02:54:03	2024-08-05 02:54:03	1	Gray Sofa	1	Sofá de color gris  
115	51	180.00	2024-08-07 10:00:26	2024-08-07 10:00:26	1	Black TV Furniture	1	Mueble de TV negro  
116	51	345.00	2024-08-07 10:06:42	2024-08-07 10:06:42	1	Sandsberg Table,Hemnes Bedside Table	1,1	Mesa Sandsberg,Mesilla de noche Hemnes  
117	51	280.00	2024-08-07 13:42:44	2024-08-07 13:42:44	1	Reclining Sofa	1	Sofá Reclinable  
118	51	280.00	2024-08-07 13:45:47	2024-08-07 13:45:47	1	Reclining Sofa	1	Sofá Reclinable  
119	127	489.98	2024-08-07 16:47:36	2024-08-07 16:47:36	1	Fredde Desk,Ramnaj Bed	1,1	Escritorio Fredde,Cama Ramnaj  
120	51	860.00	2024-08-07 17:18:25	2024-08-07 17:18:25	1	White Sofa,Reclining Sofa	2,1	Sofá de color blanco,Sofá Reclinable  
121	51	280.00	2024-08-07 17:25:32	2024-08-07 17:25:32	1	Reclining Sofa	1	Sofá Reclinable  
122	51	220.00	2024-08-07 17:27:44	2024-08-07 17:27:44	1	Slattum Bed	1	Cama Slattum  
123	51	260.00	2024-08-07 17:28:59	2024-08-07 17:28:59	1	Sagesund Bed	1	Cama Sagesund  
124	51	320.00	2024-08-07 17:31:27	2024-08-07 17:31:27	1	Double Pedestal Desk	1	Escritorio de doble pedestal  
141	51	280.00	2024-08-09 12:18:21	2024-08-09 12:18:21	1	Reclining Sofa	1	Sofá reclinable  
143	137	890.00	2024-08-09 12:24:00	2024-08-09 12:24:00	1	Sektion Cabinet,Double Pedestal Desk,Green Sofa	1,1,1	  Gabinete Sektion,Escritorio de doble pedestal  ,Sofá verde  
145	51	269.00	2024-08-09 23:55:10	2024-08-09 23:55:10	1	Gray Sofa	1	Sofá Color Gris
147	143	484.00	2024-08-10 10:43:07	2024-08-10 10:43:07	1	White Desk,Gray Sofa	1,1	Escritorio blanco  ,Sofá Color Gris
149	146	269.00	2024-08-10 19:41:47	2024-08-10 19:41:47	1	Gray Sofa	1	Sofá Color Gris
151	51	1120.00	2024-08-14 04:16:02	2024-08-14 04:16:02	1	Reclining Sofa	4	Sofá reclinable  
157	123	269.00	2024-08-14 17:53:47	2024-08-14 17:53:47	1	Gray Sofa	1	Sofá Color Gris
153	142	390.00	2024-08-14 15:58:32	2024-08-14 15:58:32	1	Xhult Cabinet	1	Gabinete Xhult 
155	142	19.99	2024-08-14 16:13:53	2024-08-14 16:13:53	1	Skansas Chair	1	Silla Skansas  
159	142	30.00	2024-08-14 18:27:12	2024-08-14 18:27:12	1	Hemnes White Bedside Table	1	Mesilla de noche blanca Hemnes
163	51	200.00	2024-08-14 19:53:33	2024-08-14 19:53:33	1	Platsa Closet	1	
165	51	199.99	2024-08-14 19:56:58	2024-08-14 19:56:58	1	Mam Bed	1	
167	142	280.00	2024-08-14 20:07:32	2024-08-14 20:07:32	1	Reclining Sofa	1	
169	142	420.00	2024-08-14 20:22:08	2024-08-14 20:22:08	1	Renberget Chair,Klepstad Closet	1,1	,
171	142	130.00	2024-08-14 20:34:09	2024-08-14 20:34:09	1	Vilhatten closet	1	Armario Vilhatten
173	142	200.00	2024-08-14 21:18:13	2024-08-14 21:18:13	1	Platsa Closet	1	Armario Platsa
175	152	170.00	2024-08-14 22:09:12	2024-08-14 22:09:12	1	Renberget Chair	1	Silla Renberget  
177	123	269.00	2024-08-15 00:46:04	2024-08-15 00:46:04	1	Gray Sofa	1	Sofá Color Gris
179	142	269.98	2024-08-15 14:32:51	2024-08-15 14:32:51	1	Trotten Desk,Olerdallen Bedside Table	1,1	,
181	157	269.99	2024-08-15 22:14:58	2024-08-15 22:14:58	1	Bekant Desk	1	Escritorio Bekant  
183	51	269.00	2024-08-16 00:42:10	2024-08-16 00:42:10	1	Gray Sofa	1	Sofá Color Gris
125	51	380.00	2024-08-07 17:39:05	2024-08-07 17:39:05	1	Sagesund Bed,Black Sofa	1,1	Cama Sagesund,Sofá de color negro  
126	51	315.00	2024-08-07 17:42:06	2024-08-07 17:42:06	1	White Bed	1	Cama de color blanco  
127	51	269.00	2024-08-07 18:10:42	2024-08-07 18:10:42	1	Gray Sofa	1	Sofá de color gris  
128	51	1809.99	2024-08-07 22:42:24	2024-08-07 22:42:24	1	Gruppspel Chair,Nordviken Chair,Green Sofa,Xhult Cabinet,Sektion Cabinet,Sagesund Bed	1,1,1,1,2,1	Silla Gruppspel,Silla Nordviken,Sofá de color verde,Gabinete Xhult,Gabinete Sektion,Cama Sagesund  
130	51	240.00	2024-08-09 09:56:53	2024-08-09 09:56:53	1	Melltorp Table	1	Mesa Melltorp  
132	51	120.00	2024-08-09 10:41:49	2024-08-09 10:41:49	1	Black Sofa	1	Sofá de color negro  
136	51	65.00	2024-08-09 10:53:13	2024-08-09 10:53:13	1	ENHET	1	ENHET
134	136	180.00	2024-08-09 10:50:03	2024-08-09 10:50:03	1	Black TV Furniture	1	 Mueble de TV negro 
138	51	269.00	2024-08-09 10:54:07	2024-08-09 10:54:07	1	Gray Sofa	1	Sofá color gris  
140	51	280.00	2024-08-09 12:16:50	2024-08-09 12:16:50	1	Reclining Sofa	1	Sofá reclinable  
142	51	1470.00	2024-08-09 12:23:25	2024-08-09 12:23:25	1	Reclining Sofa,White Bed	3,2	Sofá reclinable  ,Cama de color Blanco
144	51	280.00	2024-08-09 17:01:52	2024-08-09 17:01:52	1	Reclining Sofa	1	Sofá reclinable  
146	142	240.00	2024-08-09 23:57:07	2024-08-09 23:57:07	1	Glostad Sofa	1	Sofá Glostad
148	144	615.00	2024-08-10 16:44:10	2024-08-10 16:44:10	1	Sandsberg Table,White Bed	1,1	  Mesa Sandsberg,Cama de color Blanco
150	149	499.99	2024-08-12 12:38:42	2024-08-12 12:38:42	1	Modern Black Bed	1	Cama moderna negra  
158	142	709.99	2024-08-14 18:22:07	2024-08-14 18:22:07	1	Melltorp Table,Voxlov Chair,Slattum Bed	1,1,2	Mesa Melltorp  ,Silla Voxlov  ,Cama Slattum
152	142	290.00	2024-08-14 15:54:48	2024-08-14 15:54:48	1	Idasen Desk	1	Escritorio Idasen  
154	142	999.98	2024-08-14 16:13:05	2024-08-14 16:13:05	1	Modern Black Bed	2	Cama moderna negra  
156	142	19.99	2024-08-14 16:17:57	2024-08-14 16:17:57	1	Skansas Chair	1	Silla Skansas  
160	51	65.00	2024-08-14 19:24:19	2024-08-14 19:24:19	1	ENHET	1	ENHET  
162	142	350.00	2024-08-14 19:48:05	2024-08-14 19:48:05	1	Skogsta Table	1	Silla Skogsta
164	142	240.00	2024-08-14 19:55:10	2024-08-14 19:55:10	1	Glostad Sofa	1	Sofá Glostad
166	51	199.99	2024-08-14 19:57:45	2024-08-14 19:57:45	1	Mam Bed	1	Cama Mam
168	142	269.00	2024-08-14 20:09:54	2024-08-14 20:09:54	1	Gray Sofa	1	
170	142	119.96	2024-08-14 20:32:53	2024-08-14 20:32:53	1	Voxlov Chair	4	Silla Voxlov  
172	142	350.00	2024-08-14 20:35:49	2024-08-14 20:35:49	1	Skogsta Table	1	Mesa Skogsta
174	51	280.00	2024-08-14 21:45:39	2024-08-14 21:45:39	1	Reclining Sofa	1	Sofá reclinable  
176	123	580.00	2024-08-15 00:43:02	2024-08-15 00:43:02	1	White Sofa	2	Sofá blanco  
178	51	260.00	2024-08-15 01:27:06	2024-08-15 01:27:06	1	ENHET	4	ENHET
180	142	319.99	2024-08-15 16:56:43	2024-08-15 16:56:43	1	Sandsberg Table,Skansas Chair	1,1	  Mesa Sandsberg,Silla Skansas  
182	142	499.99	2024-08-15 22:42:39	2024-08-15 22:42:39	1	Modern Black Bed	1	Cama moderna negra  
\.


                                                                                                                                                                                                                                                                                                                                                                                                                               3086.dat                                                                                            0000600 0004000 0002000 00000001517 14657565543 0014302 0                                                                                                    ustar 00postgres                        postgres                        0000000 0000000                                                                                                                                                                        8	34	2	2
9	35	1	1
10	36	1	1
11	36	2	1
12	37	2	1
13	37	3	1
14	38	2	1
15	38	1	3
16	39	2	1
17	40	1	1
18	41	3	1
19	42	2	1
20	43	2	1
21	44	1	1
22	45	3	1
23	46	9	1
24	47	11	1
25	48	9	1
26	49	9	1
27	50	11	1
28	51	9	2
29	52	9	1
30	53	9	1
31	54	9	1
32	55	11	1
33	56	9	1
34	57	9	1
35	58	26	1
36	59	19	1
37	60	11	1
38	60	17	1
39	61	20	1
40	61	53	1
41	62	9	1
42	63	9	1
43	64	9	1
44	65	29	1
45	66	10	1
46	67	48	1
47	68	29	1
48	69	50	1
49	70	29	1
50	71	17	1
51	72	9	1
52	73	49	1
53	74	9	1
54	75	20	1
55	76	35	1
56	76	17	1
57	77	19	2
58	78	17	1
59	79	28	1
60	80	11	1
61	81	23	1
62	82	11	1
63	83	44	1
64	83	21	1
65	84	43	1
66	85	17	1
67	86	11	1
68	87	19	1
69	88	20	1
70	89	9	1
71	90	20	1
72	91	20	1
73	92	20	1
74	93	9	1
75	94	11	1
76	95	19	1
77	96	18	1
78	97	11	1
79	98	39	1
80	99	25	1
81	100	14	1
82	101	11	1
83	101	19	1
84	102	19	1
85	103	14	2
86	103	28	1
\.


                                                                                                                                                                                 3090.dat                                                                                            0000600 0004000 0002000 00000000005 14657565543 0014264 0                                                                                                    ustar 00postgres                        postgres                        0000000 0000000                                                                                                                                                                        \.


                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           3092.dat                                                                                            0000600 0004000 0002000 00000000005 14657565543 0014266 0                                                                                                    ustar 00postgres                        postgres                        0000000 0000000                                                                                                                                                                        \.


                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           3100.dat                                                                                            0000600 0004000 0002000 00000000005 14657565543 0014254 0                                                                                                    ustar 00postgres                        postgres                        0000000 0000000                                                                                                                                                                        \.


                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           3093.dat                                                                                            0000600 0004000 0002000 00000000037 14657565543 0014274 0                                                                                                    ustar 00postgres                        postgres                        0000000 0000000                                                                                                                                                                        1	Administrador
2	Usuario
\.


                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 3095.dat                                                                                            0000600 0004000 0002000 00000016346 14657565543 0014310 0                                                                                                    ustar 00postgres                        postgres                        0000000 0000000                                                                                                                                                                        47	raul	$2y$10$QVsnEEiOSe2tYiA8e2PHK.T7kD6.VdKdCBgaj1yt0G989wGf.kyci	raul	test@gmail.com	\N	0	7ab52837ba813bb6e655b1ac7cbbf31d	\N	0	2
49	raul0	$2y$10$2SMBMIILjw2.JmPOWuYpXeD982aQ5MSmL4fTw5gBV0K7ty1kzOv7S	raul	raul@gmail.com	\N	0	ca1b1d65b672827331f6b2f52db781c5	\N	0	2
71	kevin	$2y$10$mW2SUMySYptmt7Da5aSMDOYyAeoxE5nW0AMeu0X4caYAs4P7g0qr2	kevin	kevin@gmail.com	\N	1	36d7e5910c97af7974fbc1fe257d4398	\N	0	2
111	raull	$2y$10$qRz5c8WE1G9ec6ipNwrnZudngT49HwLsbyvAVIlI.WOeOTZ3JMaP6	raull	raull@gmail.com	\N	1	01836bb3739f4907737344c7086e2bf7	\N	0	2
112	ppppp	$2y$10$H3KWoym8gFbcXKqmwxzkkeia8dWz9dRNIN3m8yeyzxFL/BoS75M9W	ppppp	ppppp@gmail.com	\N	1	7294ef57af0f9095f83a7dcdf3c40fb2	\N	0	2
113	ttttt	$2y$10$QVxNGlEEVE0y6SVi/HGLee4r2BM/mpW/rNnW.KAp57NBiiKAgUCGW	ttttt	ttttt@gmail.com	\N	1	59cdfc3a9cdf256a80d13a76071d3bf6	\N	0	2
114	fffff	$2y$10$Rg0Kpjy7vP/8Hyb2m45PZ.7AQqUy6AP2edKZIeV/7QwP.n9mpttqW	fffff	fffff@gmail.com	\N	1	697c0076331fea8924313df3489bee0f	\N	0	2
115	ggggg	$2y$10$mPbb9yANh65r3NLwkgdRD.X4l/pHn9uRkWkVZQK.fDTvdBRBHY7nC	ggggg	ggggg@gmail.com	\N	1	ccc6204b1f3d05a6cec6526eec738ef1	\N	0	2
129	rauw666	$2y$10$36qHo4jA6LTJHzy1sjpEKusOAfYHF8y2hQZPf2MPkvzI620lwDNK.	raul	rauw@gmail.com	2024-08-09 15:59:19.21937	1	59c285beaa7c1e608d9eb8a778e27366		1	2
72	try123	$2y$10$iNIMx1TEEcZARbobi6hhR.8bAcbjtw5cy8MXtLWW4y06yVoheDbPO	prueba1	eprueba@gmail.com	\N	1	cd79b5f4738d2737204b07e115fab46c	\N	0	2
130	alejandro123	$2y$10$kSyXJnPiddOTFRexx51tsOSsZKKmZ9Nm0iC.zy8oqkKoISRriqV1a	Alejandro	alejandro@gmail.com	\N	0	6cfb9c2f6bf32bf71c09b22dd2912c8a	\N	0	2
117	alvaro	$2y$10$AVfHqoFaNXNERWnoa.PUvuc.BaHFalVnDLjNpfgCNDNXXUcNnY/eG	alvaro diaz	alvaro@gmail.com	\N	1	44e02ddefd975dd17bd32ed03562fd69	\N	0	2
136	david333	\\$2y\\$10\\$dw.b.79r3raMDsOMzTO3GeUBAwLeOSxXiJeDfkiUmDwYQ04kbbz9i	David	davidmorales333@gmail.com	2024-08-09 18:54:51.111531	1	25cf4764bf296b187cea5a5791aa5274	  	1	1
131	morales30	$2y$10$oOQZ1aNba1qw/eozdRYohe0aMcklZpdz56SxquULUw4obdAMg5jbS	Morales	rauwalejandro@gmail.com	\N	0	76ca0b2511e704ded36d1c34038c3ced	\N	0	2
69	armando	$2y$10$4PmBbcqFcPeCZyvVAnxyFePHiU9D420z338zL4xnl60SGGfS59hsK	armando	armando@gmail.com	\N	0	a86f002de55e84a6addf9911edb9b083	\N	0	2
127	nflgonzalez	$2y$10$m6pP/01893.hYHz.MW1/NORsBvBEu2oIsbnEb41u4xg5ThbzzgRhi	Nelson Flores	nflgonzalez@gmail.com	2024-08-07 22:43:13.007286	1	4020a52308c41e118f70447b673f8c7b		1	2
105	hola	$2y$10$Bh2vjz/FEwsspQoaYpJyL.vjapDXEzWvxKFjxLUP9YamZkLtagWym	hola	EXAMPLE@gmail.com	2024-07-22 00:54:54.022064	1	9e7ad2e9caa7f08893453508595f111a		1	2
142	flores333	$2y$10$xcU.EV2Bc/dCjl2/bu6kee0GLTUGpeQ70/NAE4vXqr7liyk8R3HZW	Diego	diego.flores200705@gmail.com	2024-08-16 06:04:39.584263	1	0649cda055d4d73207d225046696c55b		1	2
128	mau	$2y$10$ASsYfvZYc9cLDw9npof4ZOb6w09jxirVMgy729J/jZCs2IXFSZaIK	mauricio	mau@gmail.com	2024-08-09 14:39:53.781322	1	24b80f3f0cbe80ad942719b4d277cbae		1	2
132	urias333	$2y$10$g6ogu8uWzdGIHlRu8uLKoeXNDGgn2SyARwlykm.k9BTqdTPwop9qC	Urias	jimenezurias@gmail.com	\N	1	5cf0af7a0fccfac896229397c5740b38	\N	0	2
133	paheco123	$2y$10$YLI37GriGFrBNcTJQp32.er9gjXYJ/U9wxQwq6j/0AAp2xq9D8wRe	Pacheco	pacheco@gmail.com	\N	1	3465862463c9e4827360f40b713526b6	\N	0	2
134	sorto123	$2y$10$air/fPcjRJ4Ao/x/3R87KenSF40TuA5vdCUHR/IluwxkDrG/YkVzO	Gustavo	sorto@gmail.com	\N	1	f3d446b2a2f0b71a07600518f13f0f61	\N	0	2
48	morales	\\\\\\$2y\\\\\\$10\\\\\\$hVqUcq6.gdIs7/iHk0F32OxRt1Eb4UJ7P.7Y7q4F7VfIA1up8q3Aa	David	david@gmail.com	\N	1	be75721ae11ca70c24449df386cbb0a4	\N	\N	1
137	humberto	$2y$10$MYjWjA2sXVplNHDkXF3klOTHgMhSPRZytA6HxxeSaods.YUSbwmF.	humberto	humberto@gmail.com	2024-08-09 18:34:33.100438	1	32efee4809b6cf1fff935bbe0365bd60		1	2
135	carlosflores	$2y$10$xoelPz8QG6Lzw0pMLIcNRe0LKlKztKzjb257jNuhr88EbdByKNsiG	Carlos	carlosflores@gmail.com	\N	1	2907ecd124b172bd260f069ebf6da878	\N	0	2
139	davidx	$2y$10$YoI59VPWMG9Q7oMcM9VXqOjqe2bZA9/oZ4WCh1hQUp.g4F8hnwaqW	david	davidx@gmail.com	2024-08-09 22:18:30.129504	1	8487f61dab028472d68b111f8f57c157		1	2
141	nava	$2y$10$JLPOEktld.StxFUdSptNlunxouHOmU7Pvf7f4Qi.K01p6tzQkb5F6	ffdf	faff@gmail.com	\N	1	1c36127e4c8a7b079f58d1555ddcd7a3	\N	0	2
140	eeeee	$2y$10$dXdzTkypPtJ6z0DPkqE4he9peZg2HmFgk.eXvDarK3t5LDrO7MvRy	eeeee	vvvvve@gmail.com	\N	1	d4e22d049361b95dfd8bc2ddf7682107	\N	0	2
138	admin	$2y$10$9nEa/QO7VN2yprlSqT6LOeUXyo8CrN/N33HUBeALtMdQi9nbJlr5q	admin	cjnavarrete06@gmail.com	2024-08-16 06:33:26.722806	1	3fac715f813a0abc2b2ac7e6a880e881		1	1
143	svasquez	$2y$10$mvvQIQCaUbhirNqOsSypk.0XuZnRsEGDDxNA4hMqZb2hLQIZhufpy	steven vasquez	svasquez@gmail.com	2024-08-10 16:41:25.678447	1	b0a33cf1400e11492633ecae78318130		1	2
145	KMbappe	$2y$10$QuR5Xx1VkoJrUiqllSTMq.38YJRTgjC3lq3HTmWxs/9R7zw24qHQS	kylan Mbappe	kmbappe@lefrance.com	2024-08-10 22:52:44.942275	1	5d8534873bcf1cdb70fb8274fddbe8ed		1	2
144	bel	$2y$10$9zYv9I5Z1L21J2xylpuiAOh7wQCVOsAgnKofhwaatEfzv2DgEUT22	Ana	ljklhl@gmail.com	2024-08-10 22:48:05.995841	1	e30c68e08827eafd09dee779541125cf		1	2
51	username	$2y$10$GocDfhedpeRlrSUXUWDKOOsbKRoLmUaUWSnUV3wMHEnAqjQKm68JG	Carlos	cjnavarrete4@gmail.com	2024-08-16 06:41:24.383973	1	ee6fbddf3bd3229a3d87877a417edf53		1	2
123	Morales	$2y$10$9SyCIX2/7y8llSztNdY2vebdrF.d4Co2qERx2.IJpy2Y8bPKer9jm	Morales	morales@gmail.com	2024-08-15 09:45:40.24453	1	f740d278e29a69047b409a4a2051a2a6		1	2
146	programador	$2y$10$3fDAgb8.VNGeDL8VGPx.ie6/17rwb190beJhpanzcxmu/sK0yKIQ2	programador	testing1@gmail.com	2024-08-11 01:53:40.418537	1	f86604b781fa4365d1517ae420832ce7		1	2
148	DAVID20	$2y$10$QU5Nfi7vb6EdYicbK.tdUe5gADsFwp60yDIGEXs0mHEVvsFXqcCzS	David	DAVID@GMAIL.COM	2024-08-11 23:36:33.737485	1	48d47d086f2547d193c9273fc317eb73		1	2
147	jacqueline	$2y$10$sRhW0daaAVnJ/zWLNn2EF.Fy4Mo0QfFTxK0jcsRTY1L3Dz/COykva	jacqueline	dmejiafranco@yahoo.es	2024-08-11 23:37:30.527639	1	e39998a66196f949bbdeab9d43b69a0e		1	2
149	Jose1	$2y$10$XuK42corpD1qvcSJjk8AwuSWQlj9wwP4ga.WviVax212Qydvt24nq	Jose	jfjgjvigi@gmail.com	2024-08-12 18:35:04.440279	1	2b0793ef29b83534918d235163aeb089		1	2
150	test	$2y$10$Ed3zL5a4cj4VsarCGfLePuZSgk5U6s/Xc1HvI1f2aGNO9j.mEWiKG	test	jimiezzfran@gmail.com	2024-08-14 05:44:09.204036	1	0906f7841f4cff31f12d2d93f07be113	65bc34ab952badc2a34ddc1927aeb99b	1	2
157	nflores	$2y$10$KjYJNPd0wnf7FX/dZQYI9.TXxo38L6fLbpSkQjrCzIVVlFldCXUw2	Nelson Flores	nflores@cel.gob.sv	2024-08-16 04:17:32.08467	1	1f07faa69f7612dd578aae15b0898311		1	2
159	mike	$2y$10$V0dMwWgI4WL8JzkhYqkEtejvIkRbKC7Udj8xi7y0yFaLacivde1.a	Mike	mike@gmail.com	2024-08-16 06:44:14.639162	1	d657c2a4aeae919f8f3d32f1167720f7		1	2
151	PASS	$2y$10$JGt/Kbv0eolVuRR2jIJwMuUdLf69Yu6hA9.mDujFp8xLXFlUJyU1a	PASS	davidalejandromm951@gmail.com	2024-08-14 06:25:36.962152	1	efc99f3e458ad5d0f40cbc49687f7eb8	9f3ae9b700098386a3173a49daa16490	1	2
152	ElBoris	$2y$10$/v7vl3A5u7ZYl8vODWvuhOdXGxZtYLn5C7ZUovnNlhxuVOJTHHvo.	Boris	elborispatero79@gmail.com	2024-08-16 06:54:01.297909	1	e3a616765180bda90fb38bfca6c09c81		0	2
154	testtttttttt	$2y$10$bXR1hTAIHvAKU9yn139vgexmWuX2L1RPjV7LwQTDRjRmDrix5m9vm	testtttttttt	jimenezfrancisco465@gmail.com	2024-08-16 01:59:04.343865	1	3ee076562d1bf8e3433e2549d57c8562	583bb87542b11fe8b859a6441b02ed80	1	2
155	testsdasdasdasads	$2y$10$4hHXo6yw0VoRH8OZ35ksOO0dw385.L4YqSWVBFEDgP7hsDw9TfbO.	testsdasdasdasads	test144@gmail.com	2024-08-16 03:40:44.980114	1	b471ba95994038cdaf4b8ba8f22fc286		1	2
156	olaaasdadasdasdasdsa	$2y$10$wEhf36z1dmeDnfg9EHQOCetZiVeVLLK1oUvMuBF3jUSNuMkX5rl1K	olaaasdadasdasdasdsa	tests1333333332@gmail.com	2024-08-16 03:49:51.433278	1	484d786c3d8a14e4dd914d724ce34016		1	2
\.


                                                                                                                                                                                                                                                                                          restore.sql                                                                                         0000600 0004000 0002000 00000045762 14657565543 0015426 0                                                                                                    ustar 00postgres                        postgres                        0000000 0000000                                                                                                                                                                        --
-- NOTE:
--
-- File paths need to be edited. Search for $$PATH$$ and
-- replace it with the path to the directory containing
-- the extracted data files.
--
--
-- PostgreSQL database dump
--

-- Dumped from database version 13.15 (Debian 13.15-0+deb11u1)
-- Dumped by pg_dump version 13.15 (Debian 13.15-0+deb11u1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

DROP DATABASE woodvibe;
--
-- Name: woodvibe; Type: DATABASE; Schema: -; Owner: postgres
--

CREATE DATABASE woodvibe WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE = 'en_GB.UTF-8';


ALTER DATABASE woodvibe OWNER TO postgres;

\connect woodvibe

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: carrito; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.carrito (
    id_usuario integer NOT NULL,
    id_producto integer NOT NULL,
    nombre_producto character varying(255) NOT NULL,
    cantidad integer NOT NULL,
    precio numeric(10,2) NOT NULL,
    nombre_producto_es character varying(255)
);


ALTER TABLE public.carrito OWNER TO postgres;

--
-- Name: categorias; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.categorias (
    id integer NOT NULL,
    nombre character varying(100) NOT NULL,
    descripcion text
);


ALTER TABLE public.categorias OWNER TO postgres;

--
-- Name: categorias_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.categorias_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.categorias_id_seq OWNER TO postgres;

--
-- Name: categorias_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.categorias_id_seq OWNED BY public.categorias.id;


--
-- Name: checkout; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.checkout (
    id integer NOT NULL,
    nombre character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    direccion text NOT NULL,
    country character varying(100) NOT NULL,
    state character varying(100) NOT NULL,
    codigo_postal character varying(20) NOT NULL,
    dui character varying(20) NOT NULL,
    subtotal numeric(10,2) NOT NULL,
    tax numeric(10,2) NOT NULL,
    total numeric(10,2) NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.checkout OWNER TO postgres;

--
-- Name: checkout_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.checkout_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.checkout_id_seq OWNER TO postgres;

--
-- Name: checkout_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.checkout_id_seq OWNED BY public.checkout.id;


--
-- Name: mis_productos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.mis_productos (
    id integer NOT NULL,
    name character varying(200) NOT NULL,
    description text NOT NULL,
    price numeric(10,2) NOT NULL,
    image character varying NOT NULL,
    status character varying(1) NOT NULL,
    stock integer NOT NULL,
    categoria_id integer,
    nombre_es character varying(200),
    descripcion_es text
);


ALTER TABLE public.mis_productos OWNER TO postgres;

--
-- Name: mis_productos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.mis_productos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.mis_productos_id_seq OWNER TO postgres;

--
-- Name: mis_productos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.mis_productos_id_seq OWNED BY public.mis_productos.id;


--
-- Name: orden; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.orden (
    id integer NOT NULL,
    customer_id integer NOT NULL,
    total_price numeric(10,2) NOT NULL,
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone NOT NULL,
    status character varying(1) NOT NULL,
    product_names text,
    quantities text,
    product_names_es text,
    CONSTRAINT orden_status_check CHECK (((status)::text = ANY (ARRAY[('1'::character varying)::text, ('0'::character varying)::text])))
);


ALTER TABLE public.orden OWNER TO postgres;

--
-- Name: orden_articulos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.orden_articulos (
    id integer NOT NULL,
    order_id integer NOT NULL,
    product_id integer NOT NULL,
    quantity integer NOT NULL
);


ALTER TABLE public.orden_articulos OWNER TO postgres;

--
-- Name: orden_articulos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.orden_articulos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.orden_articulos_id_seq OWNER TO postgres;

--
-- Name: orden_articulos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.orden_articulos_id_seq OWNED BY public.orden_articulos.id;


--
-- Name: orden_articulos_id_seq1; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public.orden_articulos ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.orden_articulos_id_seq1
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: orden_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.orden_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.orden_id_seq OWNER TO postgres;

--
-- Name: orden_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.orden_id_seq OWNED BY public.orden.id;


--
-- Name: pedidos_detalles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.pedidos_detalles (
    id integer NOT NULL,
    pedido_id integer,
    producto_id integer,
    nombre character varying(255),
    cantidad integer,
    precio numeric(10,2),
    total numeric(10,2)
);


ALTER TABLE public.pedidos_detalles OWNER TO postgres;

--
-- Name: pedidos_detalles_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.pedidos_detalles_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.pedidos_detalles_id_seq OWNER TO postgres;

--
-- Name: pedidos_detalles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.pedidos_detalles_id_seq OWNED BY public.pedidos_detalles.id;


--
-- Name: rooms; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.rooms (
    id integer NOT NULL,
    name character varying(200) NOT NULL,
    price numeric(10,2) NOT NULL,
    image character varying(255) NOT NULL,
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone NOT NULL,
    status character varying(1) NOT NULL,
    stock integer NOT NULL
);


ALTER TABLE public.rooms OWNER TO postgres;

--
-- Name: sales; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sales (
    id integer NOT NULL,
    order_id integer NOT NULL,
    customer_name character varying(255) NOT NULL,
    product_name character varying(255) NOT NULL,
    quantity integer NOT NULL,
    purchase_date timestamp without time zone NOT NULL
);


ALTER TABLE public.sales OWNER TO postgres;

--
-- Name: sales_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.sales_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sales_id_seq OWNER TO postgres;

--
-- Name: sales_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.sales_id_seq OWNED BY public.sales.id;


--
-- Name: tipo_usuario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tipo_usuario (
    id integer NOT NULL,
    tipo character varying(40) NOT NULL
);


ALTER TABLE public.tipo_usuario OWNER TO postgres;

--
-- Name: tipo_usuario_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tipo_usuario_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tipo_usuario_id_seq OWNER TO postgres;

--
-- Name: tipo_usuario_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tipo_usuario_id_seq OWNED BY public.tipo_usuario.id;


--
-- Name: usuarios; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.usuarios (
    id integer NOT NULL,
    usuario character varying(30) NOT NULL,
    password character varying(130) NOT NULL,
    nombre character varying(100) NOT NULL,
    correo character varying(80) NOT NULL,
    last_session timestamp without time zone,
    activacion integer DEFAULT 1 NOT NULL,
    token character varying(40) NOT NULL,
    token_password character varying(100),
    password_request integer DEFAULT 0,
    id_tipo integer NOT NULL
);


ALTER TABLE public.usuarios OWNER TO postgres;

--
-- Name: usuarios_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.usuarios_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.usuarios_id_seq OWNER TO postgres;

--
-- Name: usuarios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.usuarios_id_seq OWNED BY public.usuarios.id;


--
-- Name: categorias id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorias ALTER COLUMN id SET DEFAULT nextval('public.categorias_id_seq'::regclass);


--
-- Name: checkout id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.checkout ALTER COLUMN id SET DEFAULT nextval('public.checkout_id_seq'::regclass);


--
-- Name: mis_productos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mis_productos ALTER COLUMN id SET DEFAULT nextval('public.mis_productos_id_seq'::regclass);


--
-- Name: orden id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.orden ALTER COLUMN id SET DEFAULT nextval('public.orden_id_seq'::regclass);


--
-- Name: pedidos_detalles id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pedidos_detalles ALTER COLUMN id SET DEFAULT nextval('public.pedidos_detalles_id_seq'::regclass);


--
-- Name: sales id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sales ALTER COLUMN id SET DEFAULT nextval('public.sales_id_seq'::regclass);


--
-- Name: tipo_usuario id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_usuario ALTER COLUMN id SET DEFAULT nextval('public.tipo_usuario_id_seq'::regclass);


--
-- Name: usuarios id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios ALTER COLUMN id SET DEFAULT nextval('public.usuarios_id_seq'::regclass);


--
-- Data for Name: carrito; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.carrito (id_usuario, id_producto, nombre_producto, cantidad, precio, nombre_producto_es) FROM stdin;
\.
COPY public.carrito (id_usuario, id_producto, nombre_producto, cantidad, precio, nombre_producto_es) FROM '$$PATH$$/3101.dat';

--
-- Data for Name: categorias; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.categorias (id, nombre, descripcion) FROM stdin;
\.
COPY public.categorias (id, nombre, descripcion) FROM '$$PATH$$/3081.dat';

--
-- Data for Name: checkout; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.checkout (id, nombre, email, direccion, country, state, codigo_postal, dui, subtotal, tax, total, created_at) FROM stdin;
\.
COPY public.checkout (id, nombre, email, direccion, country, state, codigo_postal, dui, subtotal, tax, total, created_at) FROM '$$PATH$$/3083.dat';

--
-- Data for Name: mis_productos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.mis_productos (id, name, description, price, image, status, stock, categoria_id, nombre_es, descripcion_es) FROM stdin;
\.
COPY public.mis_productos (id, name, description, price, image, status, stock, categoria_id, nombre_es, descripcion_es) FROM '$$PATH$$/3098.dat';

--
-- Data for Name: orden; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.orden (id, customer_id, total_price, created, modified, status, product_names, quantities, product_names_es) FROM stdin;
\.
COPY public.orden (id, customer_id, total_price, created, modified, status, product_names, quantities, product_names_es) FROM '$$PATH$$/3085.dat';

--
-- Data for Name: orden_articulos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.orden_articulos (id, order_id, product_id, quantity) FROM stdin;
\.
COPY public.orden_articulos (id, order_id, product_id, quantity) FROM '$$PATH$$/3086.dat';

--
-- Data for Name: pedidos_detalles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.pedidos_detalles (id, pedido_id, producto_id, nombre, cantidad, precio, total) FROM stdin;
\.
COPY public.pedidos_detalles (id, pedido_id, producto_id, nombre, cantidad, precio, total) FROM '$$PATH$$/3090.dat';

--
-- Data for Name: rooms; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rooms (id, name, price, image, created, modified, status, stock) FROM stdin;
\.
COPY public.rooms (id, name, price, image, created, modified, status, stock) FROM '$$PATH$$/3092.dat';

--
-- Data for Name: sales; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sales (id, order_id, customer_name, product_name, quantity, purchase_date) FROM stdin;
\.
COPY public.sales (id, order_id, customer_name, product_name, quantity, purchase_date) FROM '$$PATH$$/3100.dat';

--
-- Data for Name: tipo_usuario; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tipo_usuario (id, tipo) FROM stdin;
\.
COPY public.tipo_usuario (id, tipo) FROM '$$PATH$$/3093.dat';

--
-- Data for Name: usuarios; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.usuarios (id, usuario, password, nombre, correo, last_session, activacion, token, token_password, password_request, id_tipo) FROM stdin;
\.
COPY public.usuarios (id, usuario, password, nombre, correo, last_session, activacion, token, token_password, password_request, id_tipo) FROM '$$PATH$$/3095.dat';

--
-- Name: categorias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.categorias_id_seq', 11, true);


--
-- Name: checkout_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.checkout_id_seq', 1, false);


--
-- Name: mis_productos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.mis_productos_id_seq', 79, true);


--
-- Name: orden_articulos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.orden_articulos_id_seq', 2, true);


--
-- Name: orden_articulos_id_seq1; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.orden_articulos_id_seq1', 86, true);


--
-- Name: orden_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.orden_id_seq', 183, true);


--
-- Name: pedidos_detalles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.pedidos_detalles_id_seq', 1, false);


--
-- Name: sales_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.sales_id_seq', 1, false);


--
-- Name: tipo_usuario_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tipo_usuario_id_seq', 1, false);


--
-- Name: usuarios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.usuarios_id_seq', 159, true);


--
-- Name: carrito carrito_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carrito
    ADD CONSTRAINT carrito_pkey PRIMARY KEY (id_usuario, id_producto);


--
-- Name: categorias categorias_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorias
    ADD CONSTRAINT categorias_pkey PRIMARY KEY (id);


--
-- Name: checkout checkout_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.checkout
    ADD CONSTRAINT checkout_pkey PRIMARY KEY (id);


--
-- Name: mis_productos mis_productos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mis_productos
    ADD CONSTRAINT mis_productos_pkey PRIMARY KEY (id);


--
-- Name: orden_articulos orden_articulos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.orden_articulos
    ADD CONSTRAINT orden_articulos_pkey PRIMARY KEY (id);


--
-- Name: orden orden_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.orden
    ADD CONSTRAINT orden_pkey PRIMARY KEY (id);


--
-- Name: pedidos_detalles pedidos_detalles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pedidos_detalles
    ADD CONSTRAINT pedidos_detalles_pkey PRIMARY KEY (id);


--
-- Name: sales sales_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sales
    ADD CONSTRAINT sales_pkey PRIMARY KEY (id);


--
-- Name: tipo_usuario tipo_usuario_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_usuario
    ADD CONSTRAINT tipo_usuario_pkey PRIMARY KEY (id);


--
-- Name: usuarios usuarios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_pkey PRIMARY KEY (id);


--
-- Name: pedidos_detalles pedidos_detalles_pedido_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pedidos_detalles
    ADD CONSTRAINT pedidos_detalles_pedido_id_fkey FOREIGN KEY (pedido_id) REFERENCES public.checkout(id);


--
-- Name: sales sales_order_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sales
    ADD CONSTRAINT sales_order_id_fkey FOREIGN KEY (order_id) REFERENCES public.orden(id);


--
-- Name: TABLE orden_articulos; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE public.orden_articulos FROM postgres;
GRANT SELECT,INSERT,REFERENCES,DELETE,TRIGGER,UPDATE ON TABLE public.orden_articulos TO postgres;


--
-- PostgreSQL database dump complete
--

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              