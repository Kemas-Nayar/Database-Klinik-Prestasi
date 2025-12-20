--
-- PostgreSQL database dump
--

\restrict j3ZAFabaeYKIvmsznj1Mucxb3OhWH4WqVjpgV6peElKwDzeRAqk7u9PTbbUt2ZG

-- Dumped from database version 18.0
-- Dumped by pg_dump version 18.0

-- Started on 2025-12-20 15:28:42

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
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
-- TOC entry 222 (class 1259 OID 26216)
-- Name: admin; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.admin (
    id_admin character(4) NOT NULL,
    id_user character(4) NOT NULL,
    jabatan character varying(30) NOT NULL,
    CONSTRAINT chk_jabatan CHECK (((jabatan)::text = ANY ((ARRAY['Ketua Divisi'::character varying, 'Sekbend'::character varying, 'Anggota'::character varying])::text[])))
);


ALTER TABLE public.admin OWNER TO postgres;

--
-- TOC entry 224 (class 1259 OID 26239)
-- Name: karya; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.karya (
    id_karya character(4) NOT NULL,
    id_user character(4) NOT NULL,
    judul_karya character varying(100) NOT NULL,
    bidang_karya character varying(10) NOT NULL,
    file_karya character varying(255),
    CONSTRAINT chk_bidang_karya CHECK (((bidang_karya)::text = ANY ((ARRAY['Esai'::character varying, 'KTI'::character varying, 'Bisnis'::character varying, 'Design'::character varying])::text[])))
);


ALTER TABLE public.karya OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 26282)
-- Name: laporan_layanan; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.laporan_layanan (
    id_laporan character(4) NOT NULL,
    id_admin character(4) NOT NULL,
    id_layanan character(4) NOT NULL,
    hasil_laporan text,
    tanggal_laporan date NOT NULL,
    CONSTRAINT chk_tgl_laporan_layanan CHECK ((tanggal_laporan <= CURRENT_DATE))
);


ALTER TABLE public.laporan_layanan OWNER TO postgres;

--
-- TOC entry 223 (class 1259 OID 26232)
-- Name: layanan; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.layanan (
    id_layanan character(4) NOT NULL,
    nama_layanan character varying(50) NOT NULL
);


ALTER TABLE public.layanan OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 26178)
-- Name: mahasiswa; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.mahasiswa (
    nim character(11) NOT NULL,
    nama character varying(50) NOT NULL,
    angkatan character(3) NOT NULL,
    kontak character varying(30) NOT NULL,
    status_mhs character varying(20) DEFAULT 'Aktif'::character varying,
    CONSTRAINT chk_angkatan CHECK ((angkatan = ANY (ARRAY['F19'::bpchar, 'F20'::bpchar, 'F21'::bpchar, 'F22'::bpchar, 'F23'::bpchar]))),
    CONSTRAINT chk_status_mhs CHECK (((status_mhs)::text = ANY ((ARRAY['Aktif'::character varying, 'Nonaktif'::character varying])::text[])))
);


ALTER TABLE public.mahasiswa OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 26202)
-- Name: mentor; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.mentor (
    id_mentor character(4) NOT NULL,
    id_user character(4) NOT NULL,
    bidang_keahlian character varying(50),
    status_mentor character varying(20) DEFAULT 'Aktif'::character varying,
    CONSTRAINT chk_status_mentor CHECK (((status_mentor)::text = ANY ((ARRAY['Aktif'::character varying, 'Nonaktif'::character varying])::text[])))
);


ALTER TABLE public.mentor OWNER TO postgres;

--
-- TOC entry 225 (class 1259 OID 26254)
-- Name: peer_review; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.peer_review (
    id_review character(4) NOT NULL,
    id_karya character(4) NOT NULL,
    id_layanan character(4) NOT NULL,
    id_mentor character(4) NOT NULL,
    status_review character varying(30) NOT NULL,
    hasil_review text,
    tanggal_review date,
    CONSTRAINT chk_status_review CHECK (((status_review)::text = ANY ((ARRAY['Menunggu'::character varying, 'Selesai'::character varying])::text[])))
);


ALTER TABLE public.peer_review OWNER TO postgres;

--
-- TOC entry 220 (class 1259 OID 26188)
-- Name: user_member; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.user_member (
    id_user character(4) NOT NULL,
    nim character(11) NOT NULL
);


ALTER TABLE public.user_member OWNER TO postgres;

--
-- TOC entry 5073 (class 0 OID 26216)
-- Dependencies: 222
-- Data for Name: admin; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.admin (id_admin, id_user, jabatan) FROM stdin;
A001	U006	Ketua Divisi
A002	U010	Sekbend
A003	U011	Anggota
\.


--
-- TOC entry 5075 (class 0 OID 26239)
-- Dependencies: 224
-- Data for Name: karya; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.karya (id_karya, id_user, judul_karya, bidang_karya, file_karya) FROM stdin;
K004	U005	Poster Lomba Desain Grafis	Design	https://github.com/Kemas-Nayar/Database-Klinik-Prestasi
K005	U003	Rancangan Aplikasi Mobile	Design	https://github.com/Kemas-Nayar/Database-Klinik-Prestasi
K001	U002	Inovasi Sampah Plastik	KTI	https://github.com/Kemas-Nayar/Database-Klinik-Prestasi
K002	U003	Strategi Pemasaran Digital	Bisnis	https://github.com/Kemas-Nayar/Database-Klinik-Prestasi
K003	U002	Meningkatkan Kualitas Udara	Esai	https://github.com/Kemas-Nayar/Database-Klinik-Prestasi
\.


--
-- TOC entry 5077 (class 0 OID 26282)
-- Dependencies: 226
-- Data for Name: laporan_layanan; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.laporan_layanan (id_laporan, id_admin, id_layanan, hasil_laporan, tanggal_laporan) FROM stdin;
L001	A001	L001	Total 10 Esai telah selesai direview bulan ini.	2025-11-15
L002	A001	L002	Ada 5 permintaan KTI baru.	2025-11-15
L003	A002	L003	Perlu tambahan mentor di bidang bisnis.	2025-11-10
\.


--
-- TOC entry 5074 (class 0 OID 26232)
-- Dependencies: 223
-- Data for Name: layanan; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.layanan (id_layanan, nama_layanan) FROM stdin;
L001	Review Esai
L002	Review KTI
L003	Review Bisnis Plan
L004	Review Desain
L005	Bimbingan Karya
\.


--
-- TOC entry 5070 (class 0 OID 26178)
-- Dependencies: 219
-- Data for Name: mahasiswa; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.mahasiswa (nim, nama, angkatan, kontak, status_mhs) FROM stdin;
F2001234567	Ani Susanti	F20	081211110001	Aktif
F2101234567	Budi Cahyo	F21	081211110002	Aktif
F2201234567	Citra Dewi	F22	081211110003	Aktif
F2001234568	Eka Fitri	F20	081211110005	Aktif
F2101234568	Ferry Sanjaya	F21	081211110006	Aktif
F2201234569	Gita Mentor	F22	081211110007	Aktif
F2201234570	Hadi Mentor	F22	081211110008	Aktif
F2201234571	Indah Mentor	F22	081211110009	Aktif
F2201234572	Joko Admin	F22	081211110010	Aktif
F2201234573	Kiki Admin	F22	081211110011	Aktif
F1901234567	Dika Pratama	F19	081211110004	Nonaktif
\.


--
-- TOC entry 5072 (class 0 OID 26202)
-- Dependencies: 221
-- Data for Name: mentor; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.mentor (id_mentor, id_user, bidang_keahlian, status_mentor) FROM stdin;
M001	U001	Esai & KTI	Aktif
M002	U004	Design	Aktif
M003	U007	Bisnis	Aktif
M004	U008	KTI	Aktif
M005	U009	Design	Aktif
\.


--
-- TOC entry 5076 (class 0 OID 26254)
-- Dependencies: 225
-- Data for Name: peer_review; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.peer_review (id_review, id_karya, id_layanan, id_mentor, status_review, hasil_review, tanggal_review) FROM stdin;
R001	K001	L002	M001	Selesai	Revisi minor pada bab 2, selebihnya bagus.	2025-10-15
R002	K002	L003	M003	Menunggu	\N	\N
R003	K003	L001	M001	Selesai	Sangat inspiratif, siap lomba.	2025-11-01
R004	K004	L004	M002	Selesai	Perbaiki kontras warna.	2025-11-10
R005	K005	L004	M005	Menunggu	\N	\N
\.


--
-- TOC entry 5071 (class 0 OID 26188)
-- Dependencies: 220
-- Data for Name: user_member; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.user_member (id_user, nim) FROM stdin;
U001	F2001234567
U002	F2101234567
U003	F2201234567
U004	F1901234567
U005	F2001234568
U006	F2101234568
U007	F2201234569
U008	F2201234570
U009	F2201234571
U010	F2201234572
U011	F2201234573
\.


--
-- TOC entry 4903 (class 2606 OID 26226)
-- Name: admin admin_id_user_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.admin
    ADD CONSTRAINT admin_id_user_key UNIQUE (id_user);


--
-- TOC entry 4905 (class 2606 OID 26224)
-- Name: admin admin_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.admin
    ADD CONSTRAINT admin_pkey PRIMARY KEY (id_admin);


--
-- TOC entry 4909 (class 2606 OID 26248)
-- Name: karya karya_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.karya
    ADD CONSTRAINT karya_pkey PRIMARY KEY (id_karya);


--
-- TOC entry 4913 (class 2606 OID 26293)
-- Name: laporan_layanan laporan_layanan_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.laporan_layanan
    ADD CONSTRAINT laporan_layanan_pkey PRIMARY KEY (id_laporan);


--
-- TOC entry 4907 (class 2606 OID 26238)
-- Name: layanan layanan_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.layanan
    ADD CONSTRAINT layanan_pkey PRIMARY KEY (id_layanan);


--
-- TOC entry 4893 (class 2606 OID 26187)
-- Name: mahasiswa mahasiswa_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mahasiswa
    ADD CONSTRAINT mahasiswa_pkey PRIMARY KEY (nim);


--
-- TOC entry 4899 (class 2606 OID 26210)
-- Name: mentor mentor_id_user_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mentor
    ADD CONSTRAINT mentor_id_user_key UNIQUE (id_user);


--
-- TOC entry 4901 (class 2606 OID 26208)
-- Name: mentor mentor_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mentor
    ADD CONSTRAINT mentor_pkey PRIMARY KEY (id_mentor);


--
-- TOC entry 4911 (class 2606 OID 26266)
-- Name: peer_review peer_review_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.peer_review
    ADD CONSTRAINT peer_review_pkey PRIMARY KEY (id_review);


--
-- TOC entry 4895 (class 2606 OID 26196)
-- Name: user_member user_member_nim_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_member
    ADD CONSTRAINT user_member_nim_key UNIQUE (nim);


--
-- TOC entry 4897 (class 2606 OID 26194)
-- Name: user_member user_member_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_member
    ADD CONSTRAINT user_member_pkey PRIMARY KEY (id_user);


--
-- TOC entry 4916 (class 2606 OID 26227)
-- Name: admin admin_id_user_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.admin
    ADD CONSTRAINT admin_id_user_fkey FOREIGN KEY (id_user) REFERENCES public.user_member(id_user) ON DELETE CASCADE;


--
-- TOC entry 4917 (class 2606 OID 26249)
-- Name: karya karya_id_user_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.karya
    ADD CONSTRAINT karya_id_user_fkey FOREIGN KEY (id_user) REFERENCES public.user_member(id_user) ON DELETE CASCADE;


--
-- TOC entry 4921 (class 2606 OID 26294)
-- Name: laporan_layanan laporan_layanan_id_admin_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.laporan_layanan
    ADD CONSTRAINT laporan_layanan_id_admin_fkey FOREIGN KEY (id_admin) REFERENCES public.admin(id_admin);


--
-- TOC entry 4922 (class 2606 OID 26299)
-- Name: laporan_layanan laporan_layanan_id_layanan_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.laporan_layanan
    ADD CONSTRAINT laporan_layanan_id_layanan_fkey FOREIGN KEY (id_layanan) REFERENCES public.layanan(id_layanan);


--
-- TOC entry 4915 (class 2606 OID 26211)
-- Name: mentor mentor_id_user_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mentor
    ADD CONSTRAINT mentor_id_user_fkey FOREIGN KEY (id_user) REFERENCES public.user_member(id_user) ON DELETE CASCADE;


--
-- TOC entry 4918 (class 2606 OID 26267)
-- Name: peer_review peer_review_id_karya_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.peer_review
    ADD CONSTRAINT peer_review_id_karya_fkey FOREIGN KEY (id_karya) REFERENCES public.karya(id_karya) ON DELETE CASCADE;


--
-- TOC entry 4919 (class 2606 OID 26272)
-- Name: peer_review peer_review_id_layanan_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.peer_review
    ADD CONSTRAINT peer_review_id_layanan_fkey FOREIGN KEY (id_layanan) REFERENCES public.layanan(id_layanan);


--
-- TOC entry 4920 (class 2606 OID 26277)
-- Name: peer_review peer_review_id_mentor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.peer_review
    ADD CONSTRAINT peer_review_id_mentor_fkey FOREIGN KEY (id_mentor) REFERENCES public.mentor(id_mentor);


--
-- TOC entry 4914 (class 2606 OID 26197)
-- Name: user_member user_member_nim_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_member
    ADD CONSTRAINT user_member_nim_fkey FOREIGN KEY (nim) REFERENCES public.mahasiswa(nim) ON DELETE CASCADE;


-- Completed on 2025-12-20 15:28:43

--
-- PostgreSQL database dump complete
--

\unrestrict j3ZAFabaeYKIvmsznj1Mucxb3OhWH4WqVjpgV6peElKwDzeRAqk7u9PTbbUt2ZG

