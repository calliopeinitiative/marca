--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: calendar; Type: TABLE; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE TABLE calendar (
    id integer NOT NULL,
    user_id integer,
    course_id integer,
    startdate date NOT NULL,
    starttime time(0) without time zone NOT NULL,
    enddate date NOT NULL,
    endtime time(0) without time zone NOT NULL,
    title character varying(255) NOT NULL,
    description text,
    created timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    updated timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
);


ALTER TABLE public.calendar OWNER TO rlbaltha;

--
-- Name: calendar_id_seq; Type: SEQUENCE; Schema: public; Owner: rlbaltha
--

CREATE SEQUENCE calendar_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.calendar_id_seq OWNER TO rlbaltha;

--
-- Name: calendar_id_seq; Type: SEQUENCE SET; Schema: public; Owner: rlbaltha
--

SELECT pg_catalog.setval('calendar_id_seq', 3, true);


--
-- Name: comment; Type: TABLE; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE TABLE comment (
    id integer NOT NULL,
    user_id integer,
    body text NOT NULL,
    created timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    updated timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    forum_id integer
);


ALTER TABLE public.comment OWNER TO rlbaltha;

--
-- Name: comment_id_seq; Type: SEQUENCE; Schema: public; Owner: rlbaltha
--

CREATE SEQUENCE comment_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.comment_id_seq OWNER TO rlbaltha;

--
-- Name: comment_id_seq; Type: SEQUENCE SET; Schema: public; Owner: rlbaltha
--

SELECT pg_catalog.setval('comment_id_seq', 21, true);


--
-- Name: course; Type: TABLE; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE TABLE course (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    "time" time(0) without time zone NOT NULL,
    enroll boolean NOT NULL,
    post boolean NOT NULL,
    multicult boolean NOT NULL,
    parentid integer NOT NULL,
    assessmentid integer NOT NULL,
    studentforum boolean NOT NULL,
    notes boolean NOT NULL,
    journal boolean NOT NULL,
    portfolio boolean NOT NULL,
    zine boolean NOT NULL,
    announcement text,
    portstatus boolean NOT NULL,
    created timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    updated timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    term_id integer,
    forum boolean NOT NULL,
    projectdefault_id integer,
    user_id integer,
    portset_id integer
);


ALTER TABLE public.course OWNER TO rlbaltha;

--
-- Name: course_id_seq; Type: SEQUENCE; Schema: public; Owner: rlbaltha
--

CREATE SEQUENCE course_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.course_id_seq OWNER TO rlbaltha;

--
-- Name: course_id_seq; Type: SEQUENCE SET; Schema: public; Owner: rlbaltha
--

SELECT pg_catalog.setval('course_id_seq', 50, true);


--
-- Name: course_tagset; Type: TABLE; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE TABLE course_tagset (
    course_id integer NOT NULL,
    tagset_id integer NOT NULL
);


ALTER TABLE public.course_tagset OWNER TO rlbaltha;

--
-- Name: doc; Type: TABLE; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE TABLE doc (
    id integer NOT NULL,
    file_id integer,
    body text NOT NULL,
    created timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    updated timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
);


ALTER TABLE public.doc OWNER TO rlbaltha;

--
-- Name: doc_id_seq; Type: SEQUENCE; Schema: public; Owner: rlbaltha
--

CREATE SEQUENCE doc_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.doc_id_seq OWNER TO rlbaltha;

--
-- Name: doc_id_seq; Type: SEQUENCE SET; Schema: public; Owner: rlbaltha
--

SELECT pg_catalog.setval('doc_id_seq', 18, true);


--
-- Name: ext_log_entries; Type: TABLE; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE TABLE ext_log_entries (
    id integer NOT NULL,
    action character varying(8) NOT NULL,
    logged_at timestamp(0) without time zone NOT NULL,
    object_id character varying(32) DEFAULT NULL::character varying,
    object_class character varying(255) NOT NULL,
    version integer NOT NULL,
    data text,
    username character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE public.ext_log_entries OWNER TO rlbaltha;

--
-- Name: COLUMN ext_log_entries.data; Type: COMMENT; Schema: public; Owner: rlbaltha
--

COMMENT ON COLUMN ext_log_entries.data IS '(DC2Type:array)';


--
-- Name: ext_log_entries_id_seq; Type: SEQUENCE; Schema: public; Owner: rlbaltha
--

CREATE SEQUENCE ext_log_entries_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.ext_log_entries_id_seq OWNER TO rlbaltha;

--
-- Name: ext_log_entries_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rlbaltha
--

ALTER SEQUENCE ext_log_entries_id_seq OWNED BY ext_log_entries.id;


--
-- Name: ext_log_entries_id_seq; Type: SEQUENCE SET; Schema: public; Owner: rlbaltha
--

SELECT pg_catalog.setval('ext_log_entries_id_seq', 1, false);


--
-- Name: ext_translations; Type: TABLE; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE TABLE ext_translations (
    id integer NOT NULL,
    locale character varying(8) NOT NULL,
    object_class character varying(255) NOT NULL,
    field character varying(32) NOT NULL,
    foreign_key character varying(64) NOT NULL,
    content text
);


ALTER TABLE public.ext_translations OWNER TO rlbaltha;

--
-- Name: ext_translations_id_seq; Type: SEQUENCE; Schema: public; Owner: rlbaltha
--

CREATE SEQUENCE ext_translations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.ext_translations_id_seq OWNER TO rlbaltha;

--
-- Name: ext_translations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rlbaltha
--

ALTER SEQUENCE ext_translations_id_seq OWNED BY ext_translations.id;


--
-- Name: ext_translations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: rlbaltha
--

SELECT pg_catalog.setval('ext_translations_id_seq', 1, false);


--
-- Name: file; Type: TABLE; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE TABLE file (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    path character varying(255) NOT NULL,
    created timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    updated timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    project_id integer,
    access integer,
    user_id integer,
    course_id integer
);


ALTER TABLE public.file OWNER TO rlbaltha;

--
-- Name: file_id_seq; Type: SEQUENCE; Schema: public; Owner: rlbaltha
--

CREATE SEQUENCE file_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.file_id_seq OWNER TO rlbaltha;

--
-- Name: file_id_seq; Type: SEQUENCE SET; Schema: public; Owner: rlbaltha
--

SELECT pg_catalog.setval('file_id_seq', 36, true);


--
-- Name: file_portitem; Type: TABLE; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE TABLE file_portitem (
    file_id integer NOT NULL,
    portitem_id integer NOT NULL
);


ALTER TABLE public.file_portitem OWNER TO rlbaltha;

--
-- Name: file_tag; Type: TABLE; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE TABLE file_tag (
    file_id integer NOT NULL,
    tag_id integer NOT NULL
);


ALTER TABLE public.file_tag OWNER TO rlbaltha;

--
-- Name: forum; Type: TABLE; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE TABLE forum (
    id integer NOT NULL,
    title character varying(255) NOT NULL,
    body text NOT NULL,
    created timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    updated timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    user_id integer,
    course_id integer
);


ALTER TABLE public.forum OWNER TO rlbaltha;

--
-- Name: forum_id_seq; Type: SEQUENCE; Schema: public; Owner: rlbaltha
--

CREATE SEQUENCE forum_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.forum_id_seq OWNER TO rlbaltha;

--
-- Name: forum_id_seq; Type: SEQUENCE SET; Schema: public; Owner: rlbaltha
--

SELECT pg_catalog.setval('forum_id_seq', 3, true);


--
-- Name: journal; Type: TABLE; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE TABLE journal (
    id integer NOT NULL,
    title character varying(255) NOT NULL,
    body text NOT NULL,
    created timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    updated timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    user_id integer,
    course_id integer
);


ALTER TABLE public.journal OWNER TO rlbaltha;

--
-- Name: journal_id_seq; Type: SEQUENCE; Schema: public; Owner: rlbaltha
--

CREATE SEQUENCE journal_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.journal_id_seq OWNER TO rlbaltha;

--
-- Name: journal_id_seq; Type: SEQUENCE SET; Schema: public; Owner: rlbaltha
--

SELECT pg_catalog.setval('journal_id_seq', 18, true);


--
-- Name: marca_user; Type: TABLE; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE TABLE marca_user (
    id integer NOT NULL,
    username character varying(255) NOT NULL,
    username_canonical character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_canonical character varying(255) NOT NULL,
    enabled boolean NOT NULL,
    salt character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    last_login timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    locked boolean NOT NULL,
    expired boolean NOT NULL,
    expires_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    confirmation_token character varying(255) DEFAULT NULL::character varying,
    password_requested_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    roles text NOT NULL,
    credentials_expired boolean NOT NULL,
    credentials_expire_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    lastname character varying(255) DEFAULT NULL::character varying,
    firstname character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE public.marca_user OWNER TO rlbaltha;

--
-- Name: COLUMN marca_user.roles; Type: COMMENT; Schema: public; Owner: rlbaltha
--

COMMENT ON COLUMN marca_user.roles IS '(DC2Type:array)';


--
-- Name: marca_user_id_seq; Type: SEQUENCE; Schema: public; Owner: rlbaltha
--

CREATE SEQUENCE marca_user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.marca_user_id_seq OWNER TO rlbaltha;

--
-- Name: marca_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: rlbaltha
--

SELECT pg_catalog.setval('marca_user_id_seq', 4, true);


--
-- Name: markup; Type: TABLE; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE TABLE markup (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    function character varying(255) NOT NULL,
    value character varying(255) NOT NULL,
    color character varying(255) NOT NULL,
    markupset text NOT NULL,
    user_id integer
);


ALTER TABLE public.markup OWNER TO rlbaltha;

--
-- Name: COLUMN markup.markupset; Type: COMMENT; Schema: public; Owner: rlbaltha
--

COMMENT ON COLUMN markup.markupset IS '(DC2Type:array)';


--
-- Name: markup_id_seq; Type: SEQUENCE; Schema: public; Owner: rlbaltha
--

CREATE SEQUENCE markup_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.markup_id_seq OWNER TO rlbaltha;

--
-- Name: markup_id_seq; Type: SEQUENCE SET; Schema: public; Owner: rlbaltha
--

SELECT pg_catalog.setval('markup_id_seq', 4, true);


--
-- Name: page; Type: TABLE; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE TABLE page (
    id integer NOT NULL,
    body text NOT NULL
);


ALTER TABLE public.page OWNER TO rlbaltha;

--
-- Name: page_id_seq; Type: SEQUENCE; Schema: public; Owner: rlbaltha
--

CREATE SEQUENCE page_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.page_id_seq OWNER TO rlbaltha;

--
-- Name: page_id_seq; Type: SEQUENCE SET; Schema: public; Owner: rlbaltha
--

SELECT pg_catalog.setval('page_id_seq', 1, true);


--
-- Name: portfolio; Type: TABLE; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE TABLE portfolio (
    id integer NOT NULL,
    portorder integer NOT NULL,
    user_id integer,
    course_id integer,
    file_id integer,
    portitem_id integer
);


ALTER TABLE public.portfolio OWNER TO rlbaltha;

--
-- Name: portfolio_id_seq; Type: SEQUENCE; Schema: public; Owner: rlbaltha
--

CREATE SEQUENCE portfolio_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.portfolio_id_seq OWNER TO rlbaltha;

--
-- Name: portfolio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: rlbaltha
--

SELECT pg_catalog.setval('portfolio_id_seq', 5, true);


--
-- Name: portitem; Type: TABLE; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE TABLE portitem (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    description text NOT NULL,
    sortorder integer,
    portset_id integer
);


ALTER TABLE public.portitem OWNER TO rlbaltha;

--
-- Name: portitem_id_seq; Type: SEQUENCE; Schema: public; Owner: rlbaltha
--

CREATE SEQUENCE portitem_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.portitem_id_seq OWNER TO rlbaltha;

--
-- Name: portitem_id_seq; Type: SEQUENCE SET; Schema: public; Owner: rlbaltha
--

SELECT pg_catalog.setval('portitem_id_seq', 20, true);


--
-- Name: portset; Type: TABLE; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE TABLE portset (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    user_id integer
);


ALTER TABLE public.portset OWNER TO rlbaltha;

--
-- Name: portset_id_seq; Type: SEQUENCE; Schema: public; Owner: rlbaltha
--

CREATE SEQUENCE portset_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.portset_id_seq OWNER TO rlbaltha;

--
-- Name: portset_id_seq; Type: SEQUENCE SET; Schema: public; Owner: rlbaltha
--

SELECT pg_catalog.setval('portset_id_seq', 5, true);


--
-- Name: profile_id_seq; Type: SEQUENCE; Schema: public; Owner: rlbaltha
--

CREATE SEQUENCE profile_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.profile_id_seq OWNER TO rlbaltha;

--
-- Name: profile_id_seq; Type: SEQUENCE SET; Schema: public; Owner: rlbaltha
--

SELECT pg_catalog.setval('profile_id_seq', 5, true);


--
-- Name: project; Type: TABLE; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE TABLE project (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    sortorder integer NOT NULL,
    resource boolean NOT NULL,
    course_id integer
);


ALTER TABLE public.project OWNER TO rlbaltha;

--
-- Name: project_id_seq; Type: SEQUENCE; Schema: public; Owner: rlbaltha
--

CREATE SEQUENCE project_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.project_id_seq OWNER TO rlbaltha;

--
-- Name: project_id_seq; Type: SEQUENCE SET; Schema: public; Owner: rlbaltha
--

SELECT pg_catalog.setval('project_id_seq', 143, true);


--
-- Name: reply; Type: TABLE; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE TABLE reply (
    id integer NOT NULL,
    comment_id integer,
    user_id integer,
    body text NOT NULL,
    created timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    updated timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
);


ALTER TABLE public.reply OWNER TO rlbaltha;

--
-- Name: reply_id_seq; Type: SEQUENCE; Schema: public; Owner: rlbaltha
--

CREATE SEQUENCE reply_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.reply_id_seq OWNER TO rlbaltha;

--
-- Name: reply_id_seq; Type: SEQUENCE SET; Schema: public; Owner: rlbaltha
--

SELECT pg_catalog.setval('reply_id_seq', 5, true);


--
-- Name: roll; Type: TABLE; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE TABLE roll (
    id integer NOT NULL,
    role text NOT NULL,
    status integer NOT NULL,
    course_id integer,
    user_id integer
);


ALTER TABLE public.roll OWNER TO rlbaltha;

--
-- Name: roll_id_seq; Type: SEQUENCE; Schema: public; Owner: rlbaltha
--

CREATE SEQUENCE roll_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.roll_id_seq OWNER TO rlbaltha;

--
-- Name: roll_id_seq; Type: SEQUENCE SET; Schema: public; Owner: rlbaltha
--

SELECT pg_catalog.setval('roll_id_seq', 42, true);


--
-- Name: tag; Type: TABLE; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE TABLE tag (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    color character varying(255) NOT NULL,
    icon character varying(255) DEFAULT NULL::character varying,
    sort integer,
    user_id integer
);


ALTER TABLE public.tag OWNER TO rlbaltha;

--
-- Name: tag_id_seq; Type: SEQUENCE; Schema: public; Owner: rlbaltha
--

CREATE SEQUENCE tag_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.tag_id_seq OWNER TO rlbaltha;

--
-- Name: tag_id_seq; Type: SEQUENCE SET; Schema: public; Owner: rlbaltha
--

SELECT pg_catalog.setval('tag_id_seq', 5, true);


--
-- Name: tag_tagset; Type: TABLE; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE TABLE tag_tagset (
    tag_id integer NOT NULL,
    tagset_id integer NOT NULL
);


ALTER TABLE public.tag_tagset OWNER TO rlbaltha;

--
-- Name: tagset; Type: TABLE; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE TABLE tagset (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    user_id integer
);


ALTER TABLE public.tagset OWNER TO rlbaltha;

--
-- Name: tagset_id_seq; Type: SEQUENCE; Schema: public; Owner: rlbaltha
--

CREATE SEQUENCE tagset_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.tagset_id_seq OWNER TO rlbaltha;

--
-- Name: tagset_id_seq; Type: SEQUENCE SET; Schema: public; Owner: rlbaltha
--

SELECT pg_catalog.setval('tagset_id_seq', 3, true);


--
-- Name: term; Type: TABLE; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE TABLE term (
    id integer NOT NULL,
    term character varying(255) NOT NULL,
    termname character varying(255) NOT NULL,
    status integer NOT NULL
);


ALTER TABLE public.term OWNER TO rlbaltha;

--
-- Name: term_id_seq; Type: SEQUENCE; Schema: public; Owner: rlbaltha
--

CREATE SEQUENCE term_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.term_id_seq OWNER TO rlbaltha;

--
-- Name: term_id_seq; Type: SEQUENCE SET; Schema: public; Owner: rlbaltha
--

SELECT pg_catalog.setval('term_id_seq', 3, true);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY ext_log_entries ALTER COLUMN id SET DEFAULT nextval('ext_log_entries_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY ext_translations ALTER COLUMN id SET DEFAULT nextval('ext_translations_id_seq'::regclass);


--
-- Data for Name: calendar; Type: TABLE DATA; Schema: public; Owner: rlbaltha
--

COPY calendar (id, user_id, course_id, startdate, starttime, enddate, endtime, title, description, created, updated) FROM stdin;
1	1	43	2012-07-04	08:00:00	2007-07-05	08:00:00	July 4th	<p>\r\n\tHoliday</p>	2012-06-25 23:16:03	2012-06-26 00:09:47
3	1	43	2012-08-13	08:00:00	2012-08-13	08:00:00	Fall Classes Begin	\N	2012-06-26 16:41:56	2012-06-26 16:41:56
\.


--
-- Data for Name: comment; Type: TABLE DATA; Schema: public; Owner: rlbaltha
--

COPY comment (id, user_id, body, created, updated, forum_id) FROM stdin;
20	1	<p>\r\n\tCras sollicitudin, arcu id rutrum imperdiet, mauris tortor pretium velit, a lobortis enim leo eget purus. Quisque hendrerit vulputate facilisis. Fusce tempus bibendum nibh, eu blandit leo volutpat ac. Donec laoreet placerat fermentum. Mauris sed nisi quis nibh aliquam faucibus et et sapien. Ut vel orci purus. Proin et malesuada felis. Nam ut nibh urna. Proin a odio a turpis mattis adipiscing. Fusce quis dapibus nibh.</p>	2012-06-29 12:42:14	2012-06-29 12:42:14	3
21	1	<p>\r\n\tCurabitur hendrerit auctor dui nec iaculis. Suspendisse imperdiet nunc non ante blandit sed tempor purus tempus. Nunc vel dolor lorem, vehicula elementum nulla. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Fusce in magna at felis aliquet malesuada vel facilisis metus. Sed egestas sollicitudin ante in facilisis. Vivamus placerat sapien eget nisi posuere venenatis.</p>	2012-06-29 13:44:16	2012-06-29 13:44:16	3
19	1	<p>\r\n\tQuisque nunc lorem, commodo sed vulputate ut, aliquet ut felis. Nam eget nunc sit amet quam tempor cursus. Suspendisse ligula odio, consectetur eu sollicitudin at, fermentum ac risus. Sed interdum aliquam massa quis bibendum. Nullam sagittis commodo nisl, in malesuada elit lacinia sit amet. Sed sed leo ac felis cursus cursus at non ipsum. Phasellus varius, eros et accumsan laoreet, lorem mi lacinia erat, in semper justo urna a elit.&nbsp;</p>	2012-06-29 12:41:51	2012-06-29 13:44:31	3
\.


--
-- Data for Name: course; Type: TABLE DATA; Schema: public; Owner: rlbaltha
--

COPY course (id, name, "time", enroll, post, multicult, parentid, assessmentid, studentforum, notes, journal, portfolio, zine, announcement, portstatus, created, updated, term_id, forum, projectdefault_id, user_id, portset_id) FROM stdin;
45	ENGL1103	10:00:00	t	t	f	1	1	f	t	t	t	f	\N	t	2012-04-26 20:58:17	2012-06-23 00:03:10	2	f	114	1	4
49	ENGL1104	11:00:00	t	t	f	1	1	f	t	t	t	f	Welcome	t	2012-06-28 11:49:06	2012-06-28 11:49:06	2	t	136	1	4
42	ENGL1101	10:00:00	t	t	f	1	1	f	t	t	t	f	We will have class today.  Be sure to be here.  You won't want to miss it.	t	\N	2012-07-02 14:48:50	2	f	102	1	4
43	ENGL1102	10:00:00	t	t	f	1	1	f	t	t	t	f	\N	t	2012-03-28 22:03:49	2012-07-03 07:26:04	2	f	106	1	4
\.


--
-- Data for Name: course_tagset; Type: TABLE DATA; Schema: public; Owner: rlbaltha
--

COPY course_tagset (course_id, tagset_id) FROM stdin;
42	3
43	3
45	3
49	3
\.


--
-- Data for Name: doc; Type: TABLE DATA; Schema: public; Owner: rlbaltha
--

COPY doc (id, file_id, body, created, updated) FROM stdin;
11	28	<p>\r\n\t<span class="eDoc_highlight 1334980635372">paper </span><span class="eDoc_note 1334980635372"> test </span> 2 <span class="subject mc1" title="subject">test </span> <span class="main_verb mc2" title="main_verb">zzzzzzzzzzzzzzzzz </span></p>	2012-04-11 22:56:23	2012-04-20 23:57:17
2	13	<p>\r\n\t<img alt="" src="http://localhost/marca/web/app_dev.php/file/43/12/view" style="width: 800px; height: 148px; " /></p>\r\n<p>\r\n\t&nbsp;</p>\r\n<p>\r\n\t&quot;Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...&quot;</p>\r\n<p>\r\n\t&quot;There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...&quot;</p>\r\n<p>\r\n\t&nbsp;</p>\r\n<p>\r\n\tLorem ipsum dolor sit amet, consectetur adipiscing elit. Sed et mollis lectus. Fusce <span class="subject mc1" title="subject">molestie </span> <span class="main_verb mc2" title="main_verb">lorem </span> vitae neque euismod at rhoncus tellus venenatis. Morbi aliquet, ante ut tristique elementum, mauris purus iaculis nisl, vel gravida est odio a felis. Aenean quis mi id tellus dictum tempus. Suspendisse ligula turpis, dictum at rutrum sed, tristique a lectus. Nam pulvinar, urna id volutpat consequat, dui dui laoreet tortor, ac vehicula nibh lacus a sapien. Nam sed augue at eros pellentesque elementum sit amet et ligula. Maecenas vitae arcu velit, quis porta nulla. Sed non tortor pellentesque enim interdum commodo. Cras bibendum erat non nisl dignissim sagittis. Praesent a ligula ac risus varius mollis. Suspendisse aliquet velit nec nibh eleifend sed eleifend metus dictum. Mauris mattis, nulla ac commodo scelerisque, velit augue blandit odio, vel facilisis massa lorem id lectus. Nam convallis aliquet molestie.</p>\r\n<p>\r\n\tInteger eget velit nisi. In vestibulum euismod bibendum. Integer id mollis felis. Donec sed augue enim, vel fringilla odio. Nam in ornare lectus. Duis vel tortor eget quam faucibus convallis. Pellentesque non urna nunc, ullamcorper accumsan odio. Ut convallis porta dolor eget vehicula. Donec molestie sagittis purus. Integer ut magna leo, sed iaculis tortor.</p>\r\n<p>\r\n\tNunc quis neque non magna placerat lobortis. Donec magna purus, volutpat ut pharetra eu, blandit sed lectus. Donec auctor felis sit amet purus consectetur pulvinar elementum tortor tempor. Nunc id lacus dolor, et fermentum metus. Morbi nulla arcu, sollicitudin in sollicitudin ac, varius ut lacus. Duis id dui libero. Proin suscipit massa in purus congue tristique dapibus urna euismod.</p>\r\n<p>\r\n\tQuisque blandit mauris eu quam eleifend tempus. Duis tempor mollis mi, ac pretium lectus vehicula vitae. Sed pretium auctor metus, ac pulvinar mauris accumsan sit amet. Sed tincidunt metus vel libero egestas bibendum. Maecenas eget erat turpis, et eleifend nulla. Maecenas a gravida risus. Fusce venenatis, leo vel gravida dapibus, dui augue porta velit, interdum pellentesque nisi turpis quis nulla. Nam fringilla mi vel justo eleifend sit amet mollis lacus facilisis. Duis euismod dignissim dolor, suscipit convallis elit consequat ac. Maecenas rutrum dui id orci semper imperdiet. Nam ante risus, mollis nec dapibus a, ultrices vel leo. Quisque ac quam eget erat cursus porttitor sed sit amet tellus. Nam a ante a quam lacinia convallis quis at risus. Etiam et lorem enim, quis adipiscing lorem. Cras porttitor blandit pellentesque.</p>\r\n<p>\r\n\tDonec iaculis sem suscipit magna mattis gravida. Sed eros erat, egestas non viverra facilisis, vulputate non lacus. Pellentesque lectus tortor, pulvinar ut tempor sit amet, tempor vel lacus. Aenean luctus sodales iaculis. Suspendisse porttitor dictum pulvinar. Curabitur erat odio, tempor ut laoreet quis, tristique sit amet magna. In hac habitasse platea dictumst. Mauris ut velit nec ipsum pharetra placerat. Integer et felis eu augue mollis faucibus. Phasellus placerat semper dictum. Praesent sagittis adipiscing metus vel sagittis. Nulla facilisi.</p>\r\n<p>\r\n\t&nbsp;</p>\r\n<p>\r\n\tSincerely</p>\r\n<p>\r\n\t&nbsp;</p>	2012-03-30 22:36:26	2012-06-23 21:46:49
3	16	<p class="eDoc_paragraph_indent">\r\n\t&nbsp;</p>\r\n<p class="eDoc_paragraph_indent">\r\n\t&nbsp;</p>\r\n<p class="eDoc_paragraph_indent">\r\n\t<span style="font-family:verdana,geneva,sans-serif;">Lorem ipsum dolor sit amet, consectetur <span class="subject" title="subject">adipiscing </span> <span class="main_verb" title="main_verb">elit </span>. In in <span class="preposition" title="preposition">tortor </span> a lacus <span class="adjective" title="adjective">adipiscing </span> <span class="adverb" title="adverb">vulputate </span> ut ac turpis. <span class="subject mc1" title="subject">Vestibulum </span> <span class="main_verb mc2" title="main_verb">neque </span> tortor, fermentum eget volutpat et, porta id orci. Integer fringilla purus at massa tempor ut hendrerit libero laoreet. Donec sit amet est ut ipsum aliquam feugiat quis eu urna. Vivamus fermentum iaculis pretium. Pellentesque non metus in elit pretium pharetra. Nulla facilisi. Nunc elementum accumsan luctus. Etiam tristique nisl at justo elementum suscipit. <span class="eDoc_error Agreement_PA" title="Agreement_PA">Aenean </span> sollicitudin volutpat <span class="significant" title="significant">aliquet </span>. <span class="eDoc_highlight 1333666510417">Nullam quis augue sem, ut imperdiet enim. Vivamus accumsan quam tellus. Nulla facilisi. </span><span class="eDoc_note 1333666510417"> Nullam quis augue sem, ut imperdiet enim. Vivamus accumsan quam tellus. Nulla facilisi. </span><img alt="" src="http://www.english.uga.edu/~rlbaltha/balthazor_ron_hdt.jpg" style="margin-left: 10px; margin-right: 10px; margin-top: 10px; margin-bottom: 10px; float: right; width: 200px; height: 241px; " /></span></p>\r\n<p class="eDoc_paragraph_indent">\r\n\t<span style="font-family:verdana,geneva,sans-serif;">Mauris suscipit justo a tortor faucibus nec adipiscing lorem <span class="eDoc_error Pronoun_Reference" title="Pronoun_Reference">aliquet </span>. Praesent erat nisl, scelerisque a aliquet vel, sagittis at risus. Cras quis augue ac urna dictum iaculis. Maecenas rutrum dui et mauris euismod mollis. Maecenas laoreet, turpis nec pellentesque dapibus, sem lorem dapibus ligula, vitae vestibulum enim arcu at risus. In vitae sem nunc. Curabitur malesuada <span class="predicate" title="predicate">commodo </span> sapien, vel dictum leo venenatis sed. Phasellus nunc urna, <span class="eDoc_highlight 1333666516634">gravida a posuere non, </span><span class="eDoc_note 1333666516634"> Nullam quis augue sem, ut imperdiet enim. Vivamus accumsan quam tellus. Nulla facilisi. </span>lacinia vehicula lorem.</span></p>\r\n<p class="eDoc_paragraph_indent">\r\n\t<span style="font-family:verdana,geneva,sans-serif;"><span class="thesis" title="thesis">Etiam vel quam arcu. </span><span class="topic_sentence" title="topic_sentence"> Nulla facilisi. </span> <span class="transition" title="transition">Pellentesque </span> quis sem sed ipsum <span class="support" title="support">venenatis </span> adipiscing ac vel sapien. Etiam vestibulum quam nec lorem gravida eu facilisis lacus commodo. Maecenas orci arcu, venenatis non facilisis non, vulputate sed nunc. Mauris convallis vehicula dictum. Vestibulum odio ipsum, viverra non rhoncus vitae, dapibus ac arcu. Cras consequat lacinia urna, nec blandit nibh volutpat eget. <span class="key_concepts" title="key_concepts">Sed </span> vel quam magna, quis elementum ipsum. Maecenas libero sem, fringilla quis lacinia a, auctor nec libero. Duis ac bibendum enim. Aenean bibendum libero eget odio egestas sed dapibus odio molestie.</span></p>\r\n<p class="eDoc_paragraph_indent">\r\n\t<span style="font-family:verdana,geneva,sans-serif;">Sed auctor urna vel metus ornare aliquam. Donec nec eros ac est tincidunt dignissim. Etiam tempor dolor non mauris pulvinar eu ornare eros pulvinar. Vivamus vitae nisl sed justo vulputate vestibulum. Aliquam et viverra risus. Nullam porttitor eleifend nisi, vitae pellentesque erat sagittis et. Duis mattis, tortor sit amet dignissim commodo, ipsum mauris lobortis justo, eu placerat lorem urna a velit. Nunc sed leo quis ipsum dapibus sodales ac quis mauris. Phasellus faucibus sagittis lacus, ut blandit nulla suscipit in. Cras placerat, purus id faucibus interdum, diam libero accumsan massa, eu auctor est mi in diam.</span></p>\r\n<p class="eDoc_paragraph_indent">\r\n\t<span style="font-family:verdana,geneva,sans-serif;">Mauris sagittis augue vitae sapien tempus volutpat. Ut consectetur, arcu non fermentum faucibus, odio velit pretium leo, ut dictum nibh lacus in sapien. Quisque vestibulum magna at neque euismod suscipit congue lorem porttitor. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse nunc turpis, egestas in elementum iaculis, aliquet a massa. In laoreet augue id ante lacinia pulvinar. Sed in interdum purus. Nunc odio mauris, tristique a ullamcorper in, ultricies at quam.</span></p>	2012-04-03 22:16:12	2012-04-06 19:37:45
15	33	<p>\r\n\tLorem ipsum dolor sit amet, consectetur adipiscing elit. Integer lorem turpis, blandit nec iaculis vulputate, hendrerit tincidunt nisl. Cras sodales rhoncus lobortis. Aliquam feugiat felis non sapien pretium id gravida nisi malesuada. Vivamus a vehicula nisi. Mauris pretium faucibus tempus. Donec suscipit hendrerit augue, in viverra augue commodo eu. Quisque iaculis auctor nulla, a pellentesque leo feugiat lobortis. Cras id leo nec quam consectetur tincidunt. Vestibulum cursus commodo ante, sit amet pulvinar urna pellentesque in. Quisque placerat, nibh quis accumsan semper, magna nisl porttitor justo, vitae sagittis magna tortor sed sem. Nulla tincidunt commodo nibh in faucibus. Sed non luctus ipsum.</p>\r\n<p>\r\n\tDonec accumsan mi rhoncus nisi posuere venenatis. Integer ac tortor purus. <span class="preposition mc3" title="preposition">Etiam </span> dictum arcu et <span class="subject mc1" title="subject">orci </span> ultrices <span class="main_verb mc2" title="main_verb">quis </span> ultrices risus egestas. In non magna in lacus dapibus mattis gravida id arcu. Phasellus nec mauris vitae libero porttitor imperdiet sed vitae quam. Praesent at augue quis nisi lacinia tincidunt a eu urna. Nunc est turpis, venenatis a volutpat eu, placerat in elit. Maecenas volutpat varius lectus a scelerisque. Praesent justo turpis, feugiat non mollis a, consectetur vel risus. Donec nec sapien interdum nulla luctus porttitor a ac ipsum. Etiam non quam a risus tempor molestie. In ornare, lacus vel consequat sagittis, ligula metus tempor massa, eu tempus lacus libero eu metus. Donec placerat tempus euismod. <span class="eDoc_highlight 1340362355949">Ut gravida, diam at posuere molestie, leo elit bibendum est, sed tincidunt eros justo nec sem. Vestibulum bibendum tempor fringilla. </span><span class="eDoc_note 1340362355949"> testtest </span></p>\r\n<p>\r\n\tSed condimentum rutrum felis at luctus. Phasellus egestas, neque nec aliquet <span class="eDoc_error Comma_Series" title="Comma_Series">accumsan </span>, libero quam vestibulum <span class="subject mc1" title="subject">purus </span>, at <span class="main_verb mc2" title="main_verb">dapibus </span> elit turpis ut purus. Donec malesuada, tortor pellentesque commodo blandit, dolor diam tincidunt <span class="eDoc_error Dangling_Modifier" title="Dangling_Modifier">sapien </span>, ut congue arcu libero ac magna. Integer convallis pretium ultrices. Morbi fringilla bibendum urna, at luctus libero vehicula ac. Aenean facilisis felis laoreet erat vehicula eu lacinia libero cursus. Curabitur quam enim, egestas in aliquam eu, aliquam a ante. Etiam elementum facilisis ante non tristique. Etiam ac lacus lectus. Ut ipsum lacus, facilisis nec tristique quis, imperdiet et quam. Morbi fringilla aliquet accumsan. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nulla facilisi. <span class="eDoc_error Wrong_Word" title="Wrong_Word">Nunc </span> rutrum venenatis tortor, ut pellentesque ligula pharetra eleifend.</p>\r\n<p>\r\n\tCurabitur leo nunc, fermentum quis tristique eu, egestas sed dui. Mauris nulla lorem, commodo id tristique quis, <span class="preposition mc3" title="preposition">pretium </span> vel urna. Aliquam erat volutpat. Mauris iaculis sem sed neque condimentum at aliquam libero volutpat. Morbi urna tellus, tincidunt sed sagittis eget, venenatis eget odio. Morbi congue aliquam aliquam. Nunc fermentum scelerisque tincidunt. Nunc iaculis ligula sit amet nulla lacinia rutrum. Sed venenatis tincidunt mattis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere <span class="subject mc1" title="subject">cubilia </span> <span class="eDoc_highlight 1340797431616">Curae; Vivamus dignissim, ante ut tincidunt bibendum, massa sapien interdum magna, in consequat leo elit tempus massa. </span><span class="eDoc_note 1340797431616"> ttt xxx </span></p>\r\n<p>\r\n\tFusce in eros dui, vel congue nulla. Sed ac orci lorem. Sed a justo in sem convallis lobortis vitae in mauris. Nulla in <span class="preposition mc3" title="preposition">ligula </span> in <span class="main_verb mc2" title="main_verb">ante </span> molestie aliquam. Morbi lectus ante, hendrerit sit amet congue ac, sollicitudin nec tortor. Fusce consectetur, est a pharetra tempor, sem mauris blandit ante, vitae suscipit elit neque vitae risus. Maecenas sapien sapien, tempor sed elementum nec, convallis in arcu. Praesent gravida nisl et enim rhoncus blandit sed in libero. Donec arcu ligula, porta ac lobortis et, eleifend eget felis. Curabitur ac lacinia odio. Integer posuere nisl quis mauris venenatis consectetur. Sed id massa mi. Etiam vitae quam arcu, sed laoreet sapien.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer lorem turpis, blandit nec iaculis vulputate, hendrerit tincidunt nisl. Cras sodales rhoncus lobortis. Aliquam feugiat felis non sapien pretium id gravida nisi malesuada. Vivamus a vehicula nisi. Mauris pretium faucibus tempus. Donec suscipit hendrerit augue, in viverra augue commodo eu. Quisque iaculis auctor nulla, a pellentesque leo feugiat lobortis. Cras id leo nec quam consectetur tincidunt. Vestibulum cursus commodo ante, sit amet pulvinar urna pellentesque in. Quisque placerat, nibh quis accumsan semper, magna nisl porttitor justo, vitae sagittis magna tortor sed sem. Nulla tincidunt commodo nibh in faucibus. Sed non luctus ipsum.</p>\r\n<p>\r\n\tDonec accumsan mi rhoncus nisi posuere venenatis. Integer ac tortor purus. Etiam dictum arcu et orci ultrices quis ultrices risus egestas. In non magna in lacus dapibus mattis gravida id arcu. Phasellus nec mauris vitae libero porttitor imperdiet sed vitae quam. Praesent at augue quis nisi lacinia tincidunt a eu urna. Nunc est turpis, venenatis a volutpat eu, placerat in elit. Maecenas volutpat varius lectus a scelerisque. Praesent justo turpis, feugiat non mollis a, consectetur vel risus. Donec nec sapien interdum nulla luctus porttitor a ac ipsum. Etiam non quam a risus tempor molestie. In ornare, lacus vel consequat sagittis, ligula metus tempor massa, eu tempus lacus libero eu metus. Donec placerat tempus euismod. Ut gravida, diam at posuere molestie, leo elit bibendum est, sed tincidunt eros justo nec sem. Vestibulum bibendum tempor fringilla.</p>\r\n<p>\r\n\tSed condimentum rutrum felis at luctus. Phasellus egestas, neque nec aliquet accumsan, libero quam vestibulum purus, at dapibus elit turpis ut purus. Donec malesuada, tortor pellentesque commodo blandit, dolor diam tincidunt sapien, ut congue arcu libero ac magna. Integer convallis pretium ultrices. Morbi fringilla bibendum urna, at luctus libero vehicula ac. Aenean facilisis felis laoreet erat vehicula eu lacinia libero cursus. Curabitur quam enim, egestas in aliquam eu, aliquam a ante. Etiam elementum facilisis ante non tristique. Etiam ac lacus lectus. Ut ipsum lacus, facilisis nec tristique quis, imperdiet et quam. Morbi fringilla aliquet accumsan. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nulla facilisi. Nunc rutrum venenatis tortor, ut pellentesque ligula pharetra eleifend.</p>\r\n<p>\r\n\tCurabitur leo nunc, fermentum quis tristique eu, egestas sed dui. Mauris nulla lorem, commodo id tristique quis, pretium vel urna. Aliquam erat volutpat. Mauris iaculis sem sed neque condimentum at aliquam libero volutpat. Morbi urna tellus, tincidunt sed sagittis eget, venenatis eget odio. Morbi congue aliquam aliquam. Nunc fermentum scelerisque tincidunt. Nunc iaculis ligula sit amet nulla lacinia rutrum. Sed venenatis tincidunt mattis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Vivamus dignissim, ante ut tincidunt bibendum, massa sapien interdum magna, in consequat leo elit tempus massa.</p>\r\n<p>\r\n\tFusce in eros dui, vel congue nulla. Sed ac orci lorem. Sed a justo in sem convallis lobortis vitae in mauris. Nulla in ligula in ante molestie aliquam. Morbi lectus ante, hendrerit sit amet congue ac, sollicitudin nec tortor. Fusce consectetur, est a pharetra tempor, sem mauris blandit ante, vitae suscipit elit neque vitae risus. Maecenas sapien sapien, tempor sed elementum nec, convallis in arcu. Praesent gravida nisl et enim rhoncus blandit sed in libero. Donec arcu ligula, porta ac lobortis et, eleifend eget felis. Curabitur ac lacinia odio. Integer posuere nisl quis mauris venenatis consectetur. Sed id massa mi. Etiam vitae quam arcu, sed laoreet sapien.</p>	2012-06-21 09:13:05	2012-06-27 07:44:04
12	29	<p>\r\n\tLorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur porttitor, <a href="http://dotearth.blogs.nytimes.com/2012/04/26/making-information-matter-in-a-noisy-age/?src=recg">massa</a> at dignissim pellentesque, dui urna cursus tellus, vel tempus urna libero id odio. Integer vestibulum ante ac leo tincidunt sagittis. Quisque nunc lacus, ornare ac elementum eu, ultricies at lorem. Quisque imperdiet erat at turpis sodales rutrum. Maecenas viverra lacus quis nisi pulvinar ultrices. Praesent sit amet dolor vitae felis rutrum mollis vitae sed diam. Nunc id enim augue. <span class="eDoc_highlight 1335609999616">Aenean tincidunt sapien quis metus sagittis eget pulvinar arcu volutpat. Sed quis laoreet orci. Nunc pulvinar, urna at vehicula facilisis, risus leo egestas tortor, sed porttitor augue sem id lectus. Aenean feugiat pellentesque nulla, vitae vulputate lectus aliquet id. </span><span class="eDoc_note 1335609999616"> Aenean tincidunt sapien quis metus sagittis eget pulvinar arcu volutpat. Sed quis laoreet orci. Nunc pulvinar, urna at vehicula facilisis, risus leo egestas tortor, sed porttitor augue sem id lectus. Aenean feugiat pellentesque nulla, vitae vulputate lectus aliquet id. </span></p>\r\n<p>\r\n\tSed auctor sapien at lorem elementum quis ullamcorper eros dictum. Aenean urna purus, <span class="subject mc1" title="subject">ullamcorper </span> <span class="main_verb mc2" title="main_verb">vitae </span> <span class="preposition mc3" title="preposition">posuere </span> eget, euismod non libero. Integer dignissim adipiscing dui nec tempor. Donec vulputate congue metus ut pellentesque. Donec sagittis tortor eget justo commodo euismod sollicitudin lacus eleifend. In hac habitasse platea dictumst. Aliquam erat volutpat. Mauris porta egestas suscipit. Mauris risus turpis, pulvinar sed rutrum sit amet, euismod et nibh. Aenean sed quam ut mauris fringilla fermentum nec in lacus.</p>\r\n<p>\r\n\tPellentesque erat lacus, sodales ut mattis congue, dapibus sit amet metus. <a href="http://dotearth.blogs.nytimes.com/2012/04/26/making-information-matter-in-a-noisy-age/?src=recg" target="_blank">Maecenas</a> hendrerit blandit ligula, non lacinia <span class="evidence mc1" title="evidence">metus </span> gravida vel. Phasellus elementum varius nulla sed euismod. Quisque tortor lorem, sollicitudin nec elementum vel, viverra nec eros. Sed porta adipiscing placerat. Curabitur neque lectus, varius quis viverra in, convallis non massa. Donec vel neque eget urna molestie tempus vel a tortor. Phasellus in lacus condimentum sapien laoreet aliquet vitae nec turpis. Integer sed enim nisi. Morbi et urna ligula, eu faucibus lectus. Aliquam dolor mi, placerat id hendrerit eget, vehicula in diam. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>\r\n<p>\r\n\tSed ut elementum mauris. Proin lorem mauris, hendrerit bibendum hendrerit ornare, viverra ut sem. <img alt="park hall" src="http://www.english.uga.edu/home_images/parkhall/1.jpg" style="margin-left: 10px; margin-right: 10px; margin-top: 10px; margin-bottom: 10px; float: right; width: 333px; height: 250px; " />Quisque molestie neque et diam semper condimentum. Pellentesque lobortis, velit vitae viverra porta, sapien ante varius mi, ac commodo est sapien non est. Quisque iaculis quam et massa consectetur mollis eu non nisl. Phasellus vulputate ornare mauris, quis convallis urna lobortis sed. Nulla id nulla et nisi dapibus imperdiet. Etiam nec metus odio, eu elementum quam.</p>\r\n<p>\r\n\tInteger vehicula laoreet venenatis. Donec nulla diam, hendrerit a tincidunt volutpat, porta in purus. Phasellus eleifend augue non leo euismod ac vestibulum libero malesuada. Curabitur et porttitor ante. Aenean viverra laoreet elit, id gravida sem luctus ultricies. Aenean nisl nisl, aliquam tincidunt condimentum eu, condimentum sit amet ante. Sed varius dapibus fermentum. Nulla in libero purus, id semper ante. Fusce nibh enim, iaculis facilisis consequat auctor, porttitor et metus. Etiam nec enim in nunc faucibus convallis. Curabitur quis magna mi. Integer fermentum, est vitae placerat volutpat, elit diam euismod ante, eu placerat felis felis et felis. Morbi id quam non augue aliquet dapibus at vel purus. Sed mollis commodo erat, ut tristique lorem condimentum sed.</p>	2012-04-28 06:46:07	2012-06-27 07:45:56
10	27	<p class="eDoc_paragraph_indent">\r\n\tLorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam nisi urna, dapibus nec suscipit eu, vehicula vel ipsum. Phasellus varius placerat magna vel molestie. Aenean molestie fringilla nulla, nec porta mi venenatis auctor. Sed tincidunt tincidunt suscipit. Curabitur diam urna, tincidunt quis egestas sed, gravida eu velit. Donec vitae velit magna, vitae fringilla velit. Aliquam egestas rutrum mollis. Phasellus in nibh felis. Curabitur tincidunt adipiscing libero a pellentesque. <span class="preposition mc3" title="preposition">Aenean </span> vel mi eu <span class="subject mc1" title="subject">lectus </span> <span class="main_verb mc2" title="main_verb">tristique </span> egestas. Phasellus bibendum velit non augue scelerisque vulputate. Curabitur eget nisl ut dui dictum fermentum in consectetur diam. Proin <span class="preposition mc3" title="preposition">feugiat </span> nisi sit amet arcu interdum vitae accumsan erat bibendum. Donec rhoncus rhoncus neque eget commodo. <span class="eDoc_highlight 1334983090501">Vivamus felis tellus, gravida et euismod quis, eleifend a dui. Vestibulum facilisis luctus est ac venenatis. </span><span class="eDoc_note 1334983090501"> Aliquam egestas rutrum mollis. Phasellus in nibh felis. Curabitur tincidunt adipiscing libero a pellentesque. </span></p>\r\n<p>\r\n\t&nbsp;</p>\r\n<p class="eDoc_paragraph_indent">\r\n\tDonec a mauris vel dolor tincidunt pellentesque sed non erat. Class aptent taciti sociosqu ad litora <span class="subject mc1" title="subject">torquent </span> per conubia <span class="eDoc_error Agreement_PA" title="Agreement_PA">nostra </span>, per inceptos himenaeos. Aliquam non dui a magna bibendum hendrerit. Nam imperdiet cursus nibh, vitae egestas lorem varius in. <span class="eDoc_error Agreement_SV" title="Agreement_SV">Sed </span> at felis purus, id lobortis sem. Vivamus diam neque, vestibulum at placerat vitae, sollicitudin at arcu. <span class="eDoc_highlight 1334983097558">Nunc dictum <span class="main_verb mc2" title="main_verb">lacinia </span> aliquet. Pellentesque non magna elit, </span><span class="eDoc_note 1334983097558"> Aliquam egestas rutrum mollis. Phasellus in nibh felis. Curabitur tincidunt adipiscing libero a pellentesque. </span> malesuada gravida libero. Maecenas erat dolor, malesuada sit amet mattis sit amet<span class="eDoc_highlight 1334983102290">, fringilla a est. Suspendisse nec orci erat, sit amet placerat purus. Cras nec mauris a leo porttitor varius. </span><span class="eDoc_note 1334983102290"> Aliquam egestas rutrum mollis. Phasellus in nibh felis. Curabitur tincidunt adipiscing libero a pellentesque. </span></p>\r\n<p class="eDoc_paragraph_indent">\r\n\tMauris semper lobortis risus nec bibendum. Nullam eleifend orci nec turpis dignissim eget feugiat ipsum tempus. Maecenas justo felis, dictum sed molestie a, tempus in eros. Nullam dolor sem, placerat vel hendrerit feugiat, vehicula ac augue. Vestibulum dui nisi, pretium et commodo a, tincidunt a ante. Aliquam malesuada tempus lorem ut consectetur. Phasellus porttitor euismod risus eu elementum. Cras nec mi mauris. Cras vitae risus velit.</p>\r\n<p>\r\n\t&nbsp;</p>\r\n<p class="eDoc_paragraph_indent">\r\n\tSuspendisse eget tempor urna. Fusce eu nunc velit. Nunc ornare pellentesque blandit. Donec ultricies, neque non consequat tincidunt, nulla mauris eleifend nisi, malesuada imperdiet lacus diam blandit dui. Nullam augue libero, porta feugiat aliquet eget, viverra sagittis tortor. Nulla volutpat sodales lectus, eu sodales nisi consequat sit amet. Quisque ullamcorper arcu vitae elit dapibus convallis.</p>\r\n<p class="eDoc_paragraph_indent">\r\n\tIn vel sem odio, ac pulvinar magna. Aliquam sit amet lectus sit amet est sagittis auctor. Vestibulum mattis tristique nisl sit amet suscipit. Integer posuere est in elit egestas et convallis ligula congue. Integer id ligula nec nisi ultrices tempus. Proin posuere, est ut fringilla ultricies, dui ante tincidunt velit, at consectetur nisi augue consequat elit. Suspendisse placerat dolor felis, lacinia eleifend leo. Mauris pulvinar nulla eget erat consequat eleifend. Mauris vel ante mauris. Praesent mauris elit, euismod in lobortis eu, fringilla quis purus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nulla faucibus risus in enim scelerisque pretium.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam nisi urna, dapibus nec suscipit eu, vehicula vel ipsum. Phasellus varius placerat magna vel molestie. Aenean molestie fringilla nulla, nec porta mi venenatis auctor. Sed tincidunt tincidunt suscipit. Curabitur diam urna, tincidunt quis egestas sed, gravida eu velit. Donec vitae velit magna, vitae fringilla velit. Aliquam egestas rutrum mollis. Phasellus in nibh felis. Curabitur tincidunt adipiscing libero a pellentesque. Aenean vel mi eu lectus tristique egestas. Phasellus bibendum velit non augue scelerisque vulputate. Curabitur eget nisl ut dui dictum fermentum in consectetur diam. Proin feugiat nisi sit amet arcu interdum vitae accumsan erat bibendum. Donec rhoncus rhoncus neque eget commodo. Vivamus felis tellus, gravida et euismod quis, eleifend a dui. Vestibulum facilisis luctus est ac venenatis.</p>\r\n<p class="eDoc_paragraph_indent">\r\n\tDonec a mauris vel dolor tincidunt pellentesque sed non erat. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Aliquam non dui a magna bibendum hendrerit. Nam imperdiet cursus nibh, vitae egestas lorem varius in. Sed at felis purus, id lobortis sem. Vivamus diam neque, vestibulum at placerat vitae, sollicitudin at arcu. Nunc dictum lacinia aliquet. Pellentesque non magna elit, malesuada gravida libero. Maecenas erat dolor, malesuada sit amet mattis sit amet, fringilla a est. Suspendisse nec orci erat, sit amet placerat purus. Cras nec mauris a leo porttitor varius.</p>\r\n<p class="eDoc_paragraph_indent">\r\n\tMauris semper lobortis risus nec bibendum. Nullam eleifend orci nec turpis dignissim eget feugiat ipsum tempus. Maecenas justo felis, dictum sed molestie a, tempus in eros. Nullam dolor sem, placerat vel hendrerit feugiat, vehicula ac augue. Vestibulum dui nisi, pretium et commodo a, tincidunt a ante. Aliquam malesuada tempus lorem ut consectetur. Phasellus porttitor euismod risus eu elementum. Cras nec mi mauris. Cras vitae risus velit.</p>\r\n<p class="eDoc_paragraph_indent">\r\n\tSuspendisse eget tempor urna. Fusce eu nunc velit. Nunc ornare pellentesque blandit. Donec ultricies, neque non consequat tincidunt, nulla mauris eleifend nisi, malesuada imperdiet lacus diam blandit dui. Nullam augue libero, porta feugiat aliquet eget, viverra sagittis tortor. Nulla volutpat sodales lectus, eu sodales nisi consequat sit amet. Quisque ullamcorper arcu vitae elit dapibus convallis.</p>\r\n<p class="eDoc_paragraph_indent">\r\n\tIn vel sem odio, ac pulvinar magna. Aliquam sit amet lectus sit amet est sagittis auctor. Vestibulum mattis tristique nisl sit amet suscipit. Integer posuere est in elit egestas et convallis ligula congue. Integer id ligula nec nisi ultrices tempus. Proin posuere, est ut fringilla ultricies, dui ante tincidunt velit, at consectetur nisi augue consequat elit. Suspendisse placerat dolor felis, lacinia eleifend leo. Mauris pulvinar nulla eget erat consequat eleifend. Mauris vel ante mauris. Praesent mauris elit, euismod in lobortis eu, fringilla quis purus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nulla faucibus risus in enim scelerisque pretium.</p>	2012-04-11 22:55:33	2012-06-13 16:33:42
16	34	<p>\r\n\tLorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi enim mauris, hendrerit ut convallis et, vestibulum at justo. Vestibulum quis libero turpis. Praesent fringilla nunc eu eros lacinia euismod. Ut rhoncus dolor nec leo tincidunt sed sodales augue eleifend. Sed feugiat lorem diam. Mauris cursus congue elit quis tristique. Etiam metus tortor, sodales porttitor posuere non, tincidunt aliquam dolor. Aliquam ac ante nunc, et facilisis massa. Aenean vel nunc facilisis augue tincidunt pharetra in id enim. Fusce at sapien ante.</p>\r\n<p>\r\n\tMaecenas sit amet dolor ut quam fringilla faucibus quis eu libero. Praesent a interdum nunc. Nam id est elit. Quisque fermentum risus eget felis aliquet sed accumsan sapien ullamcorper. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla facilisi. Phasellus tempus porttitor dolor non faucibus. Nam a nunc a diam pulvinar eleifend. Aliquam est nunc, sagittis vel fringilla sit amet, elementum ut nisi.</p>\r\n<p>\r\n\tVestibulum nec sagittis urna. Nulla lorem dolor, rhoncus in venenatis vitae, facilisis et ante. Nullam venenatis tellus id turpis molestie condimentum. Vestibulum commodo arcu sed metus volutpat vel sollicitudin neque fringilla. Aenean hendrerit libero eget nisi euismod id sodales erat tempor. Quisque rutrum ultricies metus imperdiet varius. Ut varius dui vitae est tempus ut adipiscing dolor interdum. In accumsan consectetur dui.</p>\r\n<p>\r\n\tUt velit dui, elementum nec consectetur non, semper at arcu. Pellentesque sit amet massa vitae quam commodo ullamcorper. Nunc mi arcu, congue at scelerisque vel, tincidunt ac velit. Proin vel eleifend mauris. Aenean sed leo pellentesque tellus feugiat aliquet eget eu purus. Proin rhoncus mollis orci, vitae facilisis tortor tempus eget. Quisque in eros felis, at dictum turpis. Nulla facilisi. Mauris rhoncus sapien a purus molestie fermentum. Duis volutpat dignissim orci non ultricies.</p>\r\n<p>\r\n\tIn tincidunt mi non libero dignissim commodo nec quis velit. Sed quis nisi id metus facilisis ullamcorper. Curabitur hendrerit ante ut ligula egestas sed bibendum tortor ultricies. Aliquam eleifend felis eu risus posuere ullamcorper. Sed vel urna a ligula pretium pellentesque. Aliquam convallis consequat sapien, quis blandit enim egestas sed. Suspendisse ligula massa, interdum nec iaculis eu, ornare sit amet eros. Pellentesque turpis nunc, tristique vel bibendum accumsan, dictum nec purus. Nullam egestas purus quis leo rhoncus posuere fringilla eros imperdiet. Praesent id quam vitae neque tincidunt sollicitudin vitae at lectus. Morbi vitae dignissim nunc.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi enim mauris, hendrerit ut convallis et, vestibulum at justo. Vestibulum quis libero turpis. Praesent fringilla nunc eu eros lacinia euismod. Ut rhoncus dolor nec leo tincidunt sed sodales augue eleifend. Sed feugiat lorem diam. Mauris cursus congue elit quis tristique. Etiam metus tortor, sodales porttitor posuere non, tincidunt aliquam dolor. Aliquam ac ante nunc, et facilisis massa. Aenean vel nunc facilisis augue tincidunt pharetra in id enim. Fusce at sapien ante.</p>\r\n<p>\r\n\tMaecenas sit amet dolor ut quam fringilla faucibus quis eu libero. Praesent a interdum nunc. Nam id est elit. Quisque fermentum risus eget felis aliquet sed accumsan sapien ullamcorper. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla facilisi. Phasellus tempus porttitor dolor non faucibus. Nam a nunc a diam pulvinar eleifend. Aliquam est nunc, sagittis vel fringilla sit amet, elementum ut nisi.</p>\r\n<p>\r\n\tVestibulum nec sagittis urna. Nulla lorem dolor, rhoncus in venenatis vitae, facilisis et ante. Nullam venenatis tellus id turpis molestie condimentum. Vestibulum commodo arcu sed metus volutpat vel sollicitudin neque fringilla. Aenean hendrerit libero eget nisi euismod id sodales erat tempor. Quisque rutrum ultricies metus imperdiet varius. Ut varius dui vitae est tempus ut adipiscing dolor interdum. In accumsan consectetur dui.</p>\r\n<p>\r\n\tUt velit dui, elementum nec consectetur non, semper at arcu. Pellentesque sit amet massa vitae quam commodo ullamcorper. Nunc mi arcu, congue at scelerisque vel, tincidunt ac velit. Proin vel eleifend mauris. Aenean sed leo pellentesque tellus feugiat aliquet eget eu purus. Proin rhoncus mollis orci, vitae facilisis tortor tempus eget. Quisque in eros felis, at dictum turpis. Nulla facilisi. Mauris rhoncus sapien a purus molestie fermentum. Duis volutpat dignissim orci non ultricies.</p>\r\n<p>\r\n\tIn tincidunt mi non libero dignissim commodo nec quis velit. Sed quis nisi id metus facilisis ullamcorper. Curabitur hendrerit ante ut ligula egestas sed bibendum tortor ultricies. Aliquam eleifend felis eu risus posuere ullamcorper. Sed vel urna a ligula pretium pellentesque. Aliquam convallis consequat sapien, quis blandit enim egestas sed. Suspendisse ligula massa, interdum nec iaculis eu, ornare sit amet eros. Pellentesque turpis nunc, tristique vel bibendum accumsan, dictum nec purus. Nullam egestas purus quis leo rhoncus posuere fringilla eros imperdiet. Praesent id quam vitae neque tincidunt sollicitudin vitae at lectus. Morbi vitae dignissim nunc.</p>	2012-06-26 17:26:06	2012-06-26 17:26:06
17	35	<p>\r\n\tLorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi enim mauris, hendrerit ut convallis et, vestibulum at justo. Vestibulum quis libero turpis. Praesent fringilla nunc eu eros lacinia euismod. Ut rhoncus dolor nec leo tincidunt sed sodales augue eleifend. Sed feugiat lorem diam. Mauris cursus congue elit quis tristique. Etiam metus tortor, sodales porttitor posuere non, tincidunt aliquam dolor. Aliquam ac ante nunc, et facilisis massa. Aenean vel nunc facilisis augue tincidunt pharetra in id enim. Fusce at sapien ante.</p>\r\n<p>\r\n\tMaecenas sit amet dolor ut quam fringilla faucibus quis eu libero. Praesent a interdum nunc. Nam id est elit. Quisque fermentum risus eget felis aliquet sed accumsan sapien ullamcorper. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla facilisi. Phasellus tempus porttitor dolor non faucibus. Nam a nunc a diam pulvinar eleifend. Aliquam est nunc, sagittis vel fringilla sit amet, elementum ut nisi.</p>\r\n<p>\r\n\tVestibulum nec sagittis urna. Nulla lorem dolor, rhoncus in venenatis vitae, facilisis et ante. Nullam venenatis tellus id turpis molestie condimentum. Vestibulum commodo arcu sed metus volutpat vel sollicitudin neque fringilla. Aenean hendrerit libero eget nisi euismod id sodales erat tempor. Quisque rutrum ultricies metus imperdiet varius. Ut varius dui vitae est tempus ut adipiscing dolor interdum. In accumsan consectetur dui.</p>\r\n<p>\r\n\tUt velit dui, elementum nec consectetur non, semper at arcu. Pellentesque sit amet massa vitae quam commodo ullamcorper. Nunc mi arcu, congue at scelerisque vel, tincidunt ac velit. Proin vel eleifend mauris. Aenean sed leo pellentesque tellus feugiat aliquet eget eu purus. Proin rhoncus mollis orci, vitae facilisis tortor tempus eget. Quisque in eros felis, at dictum turpis. Nulla facilisi. Mauris rhoncus sapien a purus molestie fermentum. Duis volutpat dignissim orci non ultricies.</p>\r\n<p>\r\n\tIn tincidunt mi non libero dignissim commodo nec quis velit. Sed quis nisi id metus facilisis ullamcorper. Curabitur hendrerit ante ut ligula egestas sed bibendum tortor ultricies. Aliquam eleifend felis eu risus posuere ullamcorper. Sed vel urna a ligula pretium pellentesque. Aliquam convallis consequat sapien, quis blandit enim egestas sed. Suspendisse ligula massa, interdum nec iaculis eu, ornare sit amet eros. Pellentesque turpis nunc, tristique vel bibendum accumsan, dictum nec purus. Nullam egestas purus quis leo rhoncus posuere fringilla eros imperdiet. Praesent id quam vitae neque tincidunt sollicitudin vitae at lectus. Morbi vitae dignissim nunc.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi enim mauris, hendrerit ut convallis et, vestibulum at justo. Vestibulum quis libero turpis. Praesent fringilla nunc eu eros lacinia euismod. Ut rhoncus dolor nec leo tincidunt sed sodales augue eleifend. Sed feugiat lorem diam. Mauris cursus congue elit quis tristique. Etiam metus tortor, sodales porttitor posuere non, tincidunt aliquam dolor. Aliquam ac ante nunc, et facilisis massa. Aenean vel nunc facilisis augue tincidunt pharetra in id enim. Fusce at sapien ante.</p>\r\n<p>\r\n\tMaecenas sit amet dolor ut quam fringilla faucibus quis eu libero. Praesent a interdum nunc. Nam id est elit. Quisque fermentum risus eget felis aliquet sed accumsan sapien ullamcorper. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla facilisi. Phasellus tempus porttitor dolor non faucibus. Nam a nunc a diam pulvinar eleifend. Aliquam est nunc, sagittis vel fringilla sit amet, elementum ut nisi.</p>\r\n<p>\r\n\tVestibulum nec sagittis urna. Nulla lorem dolor, rhoncus in venenatis vitae, facilisis et ante. Nullam venenatis tellus id turpis molestie condimentum. Vestibulum commodo arcu sed metus volutpat vel sollicitudin neque fringilla. Aenean hendrerit libero eget nisi euismod id sodales erat tempor. Quisque rutrum ultricies metus imperdiet varius. Ut varius dui vitae est tempus ut adipiscing dolor interdum. In accumsan consectetur dui.</p>\r\n<p>\r\n\tUt velit dui, elementum nec consectetur non, semper at arcu. Pellentesque sit amet massa vitae quam commodo ullamcorper. Nunc mi arcu, congue at scelerisque vel, tincidunt ac velit. Proin vel eleifend mauris. Aenean sed leo pellentesque tellus feugiat aliquet eget eu purus. Proin rhoncus mollis orci, vitae facilisis tortor tempus eget. Quisque in eros felis, at dictum turpis. Nulla facilisi. Mauris rhoncus sapien a purus molestie fermentum. Duis volutpat dignissim orci non ultricies.</p>\r\n<p>\r\n\tIn tincidunt mi non libero dignissim commodo nec quis velit. Sed quis nisi id metus facilisis ullamcorper. Curabitur hendrerit ante ut ligula egestas sed bibendum tortor ultricies. Aliquam eleifend felis eu risus posuere ullamcorper. Sed vel urna a ligula pretium pellentesque. Aliquam convallis consequat sapien, quis blandit enim egestas sed. Suspendisse ligula massa, interdum nec iaculis eu, ornare sit amet eros. Pellentesque turpis nunc, tristique vel bibendum accumsan, dictum nec purus. Nullam egestas purus quis leo rhoncus posuere fringilla eros imperdiet. Praesent id quam vitae neque tincidunt sollicitudin vitae at lectus. Morbi vitae dignissim nunc.</p>	2012-06-26 17:27:06	2012-06-26 17:27:06
18	36	<p>\r\n\tLorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi enim mauris, hendrerit ut convallis et, vestibulum at justo. Vestibulum quis libero turpis. Praesent fringilla nunc eu eros lacinia euismod. Ut rhoncus dolor nec leo tincidunt sed sodales augue eleifend. Sed feugiat lorem diam. Mauris cursus congue elit quis tristique. Etiam metus tortor, sodales porttitor posuere non, tincidunt aliquam dolor. Aliquam ac ante nunc, et facilisis massa. Aenean vel nunc facilisis augue tincidunt pharetra in id enim. Fusce at sapien ante.</p>\r\n<p>\r\n\tMaecenas sit amet dolor ut quam fringilla faucibus quis eu libero. Praesent a interdum nunc. Nam id est elit. Quisque fermentum risus eget felis aliquet sed accumsan sapien ullamcorper. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla facilisi. Phasellus tempus porttitor dolor non faucibus. Nam a nunc a diam pulvinar eleifend. Aliquam est nunc, sagittis vel fringilla sit amet, elementum ut nisi.</p>\r\n<p>\r\n\tVestibulum nec sagittis urna. Nulla lorem dolor, rhoncus in venenatis vitae, facilisis et ante. Nullam venenatis tellus id turpis molestie condimentum. Vestibulum commodo arcu sed metus volutpat vel sollicitudin neque fringilla. Aenean hendrerit libero eget nisi euismod id sodales erat tempor. Quisque rutrum ultricies metus imperdiet varius. Ut varius dui vitae est tempus ut adipiscing dolor interdum. In accumsan consectetur dui.</p>\r\n<p>\r\n\tUt velit dui, elementum nec consectetur non, semper at arcu. Pellentesque sit amet massa vitae quam commodo ullamcorper. Nunc mi arcu, congue at scelerisque vel, tincidunt ac velit. Proin vel eleifend mauris. Aenean sed leo pellentesque tellus feugiat aliquet eget eu purus. Proin rhoncus mollis orci, vitae facilisis tortor tempus eget. Quisque in eros felis, at dictum turpis. Nulla facilisi. Mauris rhoncus sapien a purus molestie fermentum. Duis volutpat dignissim orci non ultricies.</p>\r\n<p>\r\n\tIn tincidunt mi non libero dignissim commodo nec quis velit. Sed quis nisi id metus facilisis ullamcorper. Curabitur hendrerit ante ut ligula egestas sed bibendum tortor ultricies. Aliquam eleifend felis eu risus posuere ullamcorper. Sed vel urna a ligula pretium pellentesque. Aliquam convallis consequat sapien, quis blandit enim egestas sed. Suspendisse ligula massa, interdum nec iaculis eu, ornare sit amet eros. Pellentesque turpis nunc, tristique vel bibendum accumsan, dictum nec purus. Nullam egestas purus quis leo rhoncus posuere fringilla eros imperdiet. Praesent id quam vitae neque tincidunt sollicitudin vitae at lectus. Morbi vitae dignissim nunc.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi enim mauris, hendrerit ut convallis et, vestibulum at justo. Vestibulum quis libero turpis. Praesent fringilla nunc eu eros lacinia euismod. Ut rhoncus dolor nec leo tincidunt sed sodales augue eleifend. Sed feugiat lorem diam. Mauris cursus congue elit quis tristique. Etiam metus tortor, sodales porttitor posuere non, tincidunt aliquam dolor. Aliquam ac ante nunc, et facilisis massa. Aenean vel nunc facilisis augue tincidunt pharetra in id enim. Fusce at sapien ante.</p>\r\n<p>\r\n\tMaecenas sit amet dolor ut quam fringilla faucibus quis eu libero. Praesent a interdum nunc. Nam id est elit. Quisque fermentum risus eget felis aliquet sed accumsan sapien ullamcorper. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla facilisi. Phasellus tempus porttitor dolor non faucibus. Nam a nunc a diam pulvinar eleifend. Aliquam est nunc, sagittis vel fringilla sit amet, elementum ut nisi.</p>\r\n<p>\r\n\tVestibulum nec sagittis urna. Nulla lorem dolor, rhoncus in venenatis vitae, facilisis et ante. Nullam venenatis tellus id turpis molestie condimentum. Vestibulum commodo arcu sed metus volutpat vel sollicitudin neque fringilla. Aenean hendrerit libero eget nisi euismod id sodales erat tempor. Quisque rutrum ultricies metus imperdiet varius. Ut varius dui vitae est tempus ut adipiscing dolor interdum. In accumsan consectetur dui.</p>\r\n<p>\r\n\tUt velit dui, elementum nec consectetur non, semper at arcu. Pellentesque sit amet massa vitae quam commodo ullamcorper. Nunc mi arcu, congue at scelerisque vel, tincidunt ac velit. Proin vel eleifend mauris. Aenean sed leo pellentesque tellus feugiat aliquet eget eu purus. Proin rhoncus mollis orci, vitae facilisis tortor tempus eget. Quisque in eros felis, at dictum turpis. Nulla facilisi. Mauris rhoncus sapien a purus molestie fermentum. Duis volutpat dignissim orci non ultricies.</p>\r\n<p>\r\n\tIn tincidunt mi non libero dignissim commodo nec quis velit. Sed quis nisi id metus facilisis ullamcorper. Curabitur hendrerit ante ut ligula egestas sed bibendum tortor ultricies. Aliquam eleifend felis eu risus posuere ullamcorper. Sed vel urna a ligula pretium pellentesque. Aliquam convallis consequat sapien, quis blandit enim egestas sed. Suspendisse ligula massa, interdum nec iaculis eu, ornare sit amet eros. Pellentesque turpis nunc, tristique vel bibendum accumsan, dictum nec purus. Nullam egestas purus quis leo rhoncus posuere fringilla eros imperdiet. Praesent id quam vitae neque tincidunt sollicitudin vitae at lectus. Morbi vitae dignissim nunc.</p>	2012-06-26 17:27:53	2012-06-26 17:27:53
\.


--
-- Data for Name: ext_log_entries; Type: TABLE DATA; Schema: public; Owner: rlbaltha
--

COPY ext_log_entries (id, action, logged_at, object_id, object_class, version, data, username) FROM stdin;
\.


--
-- Data for Name: ext_translations; Type: TABLE DATA; Schema: public; Owner: rlbaltha
--

COPY ext_translations (id, locale, object_class, field, foreign_key, content) FROM stdin;
\.


--
-- Data for Name: file; Type: TABLE DATA; Schema: public; Owner: rlbaltha
--

COPY file (id, name, path, created, updated, project_id, access, user_id, course_id) FROM stdin;
16	New eDoc	doc	2012-04-03 22:16:12	2012-04-10 16:08:50	106	1	1	43
13	Letterhead	doc	2012-03-30 22:36:26	2012-04-10 16:08:57	106	1	1	43
28	paper 2 test	doc	2012-04-11 22:56:23	2012-04-12 07:10:27	107	0	1	43
26	Prayer Vigil.odt	Prayer Vigil.odt	2012-04-10 17:25:17	2012-04-12 21:33:44	106	1	1	43
15	4835_syllabus.odt	4835_syllabus.odt	2012-04-03 08:59:32	2012-04-03 10:25:38	103	0	1	43
14	sig.png	sig.png	2012-03-30 23:39:56	2012-04-03 11:03:25	107	0	1	43
12	letterhead_image.png	letterhead_image.png	2012-03-30 22:27:09	2012-04-03 11:06:53	106	0	1	43
10	4835_syllabus.pdf	4835_syllabus.pdf	2012-03-29 18:25:02	2012-04-03 17:45:32	109	0	1	43
30	Balthazor_June_order.doc	Balthazor_June_order.doc	2012-06-13 15:48:00	2012-06-13 15:55:02	106	1	1	43
27	Lorem Ipsum	doc	2012-04-11 22:55:33	2012-06-20 16:54:57	106	1	1	43
33	Lorem Ipsum Again	doc	2012-06-21 09:13:04	2012-06-23 00:03:33	107	1	1	43
34	Lorem 1	doc	2012-06-26 17:26:06	2012-06-26 17:26:54	102	1	4	42
35	New eDoc	doc	2012-06-26 17:27:06	2012-06-26 17:27:11	103	1	4	42
36	Lorem Ron	doc	2012-06-26 17:27:53	2012-06-26 17:28:09	102	1	1	42
29	Lorem	doc	2012-04-28 06:46:07	2012-06-27 09:11:48	108	0	1	43
\.


--
-- Data for Name: file_portitem; Type: TABLE DATA; Schema: public; Owner: rlbaltha
--

COPY file_portitem (file_id, portitem_id) FROM stdin;
16	16
13	13
13	4
33	13
29	19
\.


--
-- Data for Name: file_tag; Type: TABLE DATA; Schema: public; Owner: rlbaltha
--

COPY file_tag (file_id, tag_id) FROM stdin;
15	1
15	2
14	2
12	2
10	3
13	3
16	2
26	1
28	1
27	3
28	2
30	1
33	4
34	4
35	5
36	5
29	4
\.


--
-- Data for Name: forum; Type: TABLE DATA; Schema: public; Owner: rlbaltha
--

COPY forum (id, title, body, created, updated, user_id, course_id) FROM stdin;
3	Thread A	<p>\r\n\tLorem ipsum dolor sit amet, consectetur adipiscing elit. In eget orci nibh, ut dictum nunc. Mauris vitae hendrerit eros. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi vulputate enim eu odio blandit ut scelerisque justo sodales.</p>\r\n<p>\r\n\tVestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Sed ut augue justo, vel viverra justo. In hac habitasse platea dictumst. Nunc tellus eros, egestas ac pellentesque id, convallis eu orci. Vestibulum imperdiet felis a leo mollis consequat.</p>	2012-06-21 11:46:32	2012-06-29 16:24:43	1	43
\.


--
-- Data for Name: journal; Type: TABLE DATA; Schema: public; Owner: rlbaltha
--

COPY journal (id, title, body, created, updated, user_id, course_id) FROM stdin;
10	test3	<p>\r\n\tLorem ipsum dolor sit amet, consectetur adipiscing elit. Duis et vehicula est. Aliquam erat volutpat. Aliquam accumsan pulvinar vehicula. Nunc elit ligula, sagittis ac luctus nec, molestie id augue. Phasellus ut orci mi, quis dapibus velit. Ut aliquet aliquet massa a fermentum. Ut sit amet nunc dictum arcu aliquam dictum. In mattis, odio eu molestie semper, quam eros laoreet erat, eget ornare nibh ipsum nec enim. Sed mollis metus sit amet erat pellentesque at blandit urna vestibulum. Vivamus ultrices accumsan ornare. Nam tristique dapibus diam, sit amet suscipit ipsum aliquam eget.</p>	2012-03-24 07:56:09	2012-03-24 07:56:09	1	43
11	test4	<p>\r\n\tLorem ipsum dolor sit amet, consectetur adipiscing elit. Duis et vehicula est. Aliquam erat volutpat. Aliquam accumsan pulvinar vehicula. Nunc elit ligula, sagittis ac luctus nec, molestie id augue. Phasellus ut orci mi, quis dapibus velit. Ut aliquet aliquet massa a fermentum. Ut sit amet nunc dictum arcu aliquam dictum. In mattis, odio eu molestie semper, quam eros laoreet erat, eget ornare nibh ipsum nec enim. Sed mollis metus sit amet erat pellentesque at blandit urna vestibulum. Vivamus ultrices accumsan ornare. Nam tristique dapibus diam, sit amet suscipit ipsum aliquam eget.</p>	2012-03-24 07:56:20	2012-03-24 07:56:20	1	43
12	test5	<p>\r\n\tLorem ipsum dolor sit amet, consectetur adipiscing elit. Duis et vehicula est. Aliquam erat volutpat. Aliquam accumsan pulvinar vehicula. Nunc elit ligula, sagittis ac luctus nec, molestie id augue. Phasellus ut orci mi, quis dapibus velit. Ut aliquet aliquet massa a fermentum. Ut sit amet nunc dictum arcu aliquam dictum. In mattis, odio eu molestie semper, quam eros laoreet erat, eget ornare nibh ipsum nec enim. Sed mollis metus sit amet erat pellentesque at blandit urna vestibulum. Vivamus ultrices accumsan ornare. Nam tristique dapibus diam, sit amet suscipit ipsum aliquam eget.</p>	2012-03-24 07:56:32	2012-03-24 07:56:32	1	43
14	test	<p>\r\n\ttest</p>	2012-04-10 17:06:47	2012-04-10 17:06:47	1	43
15	test	<p>\r\n\ttest222</p>	2012-04-10 17:10:52	2012-05-04 14:09:32	1	43
13	Journal 6	<p>\r\n\tLorem ipsum dolor sit amet, consectetur adipiscing elit. Duis et vehicula est. Aliquam erat volutpat. Aliquam accumsan pulvinar vehicula. Nunc elit ligula, sagittis ac luctus nec, molestie id augue. Phasellus ut orci mi, quis dapibus velit. Ut aliquet aliquet massa a fermentum. Ut sit amet nunc dictum arcu aliquam dictum. In mattis, odio eu molestie semper, quam eros laoreet erat, eget ornare nibh ipsum nec enim. Sed mollis metus sit amet erat pellentesque at blandit urna vestibulum. Vivamus ultrices accumsan ornare. Nam tristique dapibus diam, sit amet suscipit ipsum aliquam eget.</p>	2012-03-24 07:56:46	2012-03-27 05:27:28	1	43
16	Lorem	<p>\r\n\tSed euismod risus ac nisl lobortis non interdum nibh ornare. Sed adipiscing odio vel justo interdum at tempor est dictum. Ut sagittis tempus lorem a venenatis. Phasellus sollicitudin augue sit amet erat euismod cursus. Pellentesque condimentum velit sed tellus laoreet ultrices. Maecenas feugiat, orci sed tincidunt elementum, nisi ante cursus dolor, at sollicitudin justo tortor ut ligula. Sed dapibus, dolor dignissim dignissim cursus, dui justo porttitor ante, eu ullamcorper lectus quam non metus....</p>	2012-05-04 14:23:02	2012-06-21 16:55:20	1	43
9	Title2	<p>\r\n\t<span style="font-family:courier new,courier,monospace;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis et vehicula est. Aliquam erat volutpat. Aliquam accumsan pulvinar vehicula. Nunc elit ligula, sagittis ac luctus nec, molestie id augue. Phasellus ut orci mi, quis dapibus velit. Ut aliquet aliquet massa a fermentum. Ut sit amet nunc dictum arcu aliquam dictum. In mattis, odio eu molestie semper, quam eros laoreet erat, eget ornare nibh ipsum nec enim. Sed mollis metus sit amet erat pellentesque at blandit urna vestibulum. Vivamus ultrices accumsan ornare. Nam tristique dapibus diam, sit amet suscipit ipsum aliquam eget.</span></p>\r\n<p>\r\n\t&nbsp;</p>	2012-03-24 07:54:35	2012-04-06 23:14:08	1	43
8	Sed a orci	<p>\r\n\t<em>Sed a orci eget risus pharetra malesuada. Quisque quam est, ultrices in malesuada sed, aliquet at enim. Cras sem libero, suscipit vitae tempor ac, convallis et dui. Vestibulum lobortis rhoncus mauris, nec bibendum sem pulvinar at. Nam eget sapien ut tellus consectetur aliquam. Nulla pellentesque porttitor nunc, a interdum dui iaculis sagittis. Nullam eget velit augue. Phasellus at ante vel velit rutrum pellentesque et quis libero. Proin et urna sem. Cras mattis sollicitudin nisl eu bibendum.</em></p>\r\n<p>\r\n\t<em>Ut lacinia vulputate quam, in tempus sem egestas non. Curabitur fringilla imperdiet orci et suscipit. Fusce eget turpis sem, consequat elementum sapien. Pellentesque rhoncus interdum tincidunt. Proin dignissim fringilla mollis. Nulla sapien nulla, pretium in pretium ac, elementum sit amet sem. Pellentesque sapien arcu, rutrum non consectetur at, consequat a nulla. Sed nulla neque, ullamcorper placerat tristique at, viverra nec dolor. Nullam eu nunc a nisi tincidunt aliquet in quis lectus. Proin cursus ligula quis augue egestas egestas. Sed sodales nunc vitae urna sodales semper. Vivamus a tortor sapien, id convallis libero. Nullam tempus rhoncus felis sit amet hendrerit. Nam pellentesque, metus id dapibus accumsan, tortor felis condimentum lectus, nec dictum nisl ipsum quis mi.</em></p>	2012-03-24 07:43:20	2012-04-06 23:15:07	1	43
17	test	<p>\r\n\ttest</p>	2012-06-14 14:09:42	2012-06-14 14:09:42	1	42
18	test	<p>\r\n\ttest5</p>	2012-06-14 14:13:21	2012-06-14 14:13:21	1	42
\.


--
-- Data for Name: marca_user; Type: TABLE DATA; Schema: public; Owner: rlbaltha
--

COPY marca_user (id, username, username_canonical, email, email_canonical, enabled, salt, password, last_login, locked, expired, expires_at, confirmation_token, password_requested_at, roles, credentials_expired, credentials_expire_at, lastname, firstname) FROM stdin;
4	imatest	imatest	ron.balthazor@gmail.com	ron.balthazor@gmail.com	t	dm2g8zi6u00gk48soc4sc0kcccoskwo	hdbVip2Ml1Hc/vtfLMEClBiJFhzO56LDdRG/CDuKUVWRoyiC3O1znwZMD6ipX+p6v4T2oc9SFmRQEkMPFUuT8g==	2012-07-02 14:29:48	f	f	\N	\N	\N	a:0:{}	f	\N	Test	Ima
1	rlbaltha	rlbaltha	rlbaltha@uga.edu	rlbaltha@uga.edu	t	iya6slz62e8gcw48wc0ggk48gowcsw4	ldEc5aVMpYZNaTdg5gPinON5YoMaITWYxHW0XpHzhF4LeecuYRdg1DWYL9AUunWExY8ijTi3OnO220pjOzidfw==	2012-07-03 07:14:20	f	f	\N	\N	\N	a:1:{i:0;s:10:"ROLE_INSTR";}	f	\N	Balthazor	Ron
\.


--
-- Data for Name: markup; Type: TABLE DATA; Schema: public; Owner: rlbaltha
--

COPY markup (id, name, function, value, color, markupset, user_id) FROM stdin;
1	Subject	markup	subject	mc1	s:1:"1";	1
2	Verb	markup	main_verb	mc2	s:1:"1";	1
3	Preposition	markup	preposition	mc3	s:1:"1";	1
4	Evidence	markup	evidence	mc1	s:1:"1";	1
\.


--
-- Data for Name: page; Type: TABLE DATA; Schema: public; Owner: rlbaltha
--

COPY page (id, body) FROM stdin;
1	<div class="container-fluid">\r\n\t<div class="row-fluid">\r\n\t\t<div class="span12">\r\n\t\t\t<p>\r\n\t\t\t\t<span style="font-size:24px;">Marca is developed and supported by&nbsp;The Calliope Initiative.</span></p>\r\n\t\t\t<p>\r\n\t\t\t\t<br />\r\n\t\t\t\tThe Calliope Initiative offers hosting, support, consulting, and development for the &lt;emma&gt; writing web application. For more information about Calliope, please contact robin@calliopeinitiative.org</p>\r\n\t\t\t<p>\r\n\t\t\t\t<span style="font-size:26px;">Take Marca for a TestDrive.</span></p>\r\n\t\t</div>\r\n\t</div>\r\n</div>
\.


--
-- Data for Name: portfolio; Type: TABLE DATA; Schema: public; Owner: rlbaltha
--

COPY portfolio (id, portorder, user_id, course_id, file_id, portitem_id) FROM stdin;
1	1	1	43	33	13
2	1	1	43	27	4
3	1	1	43	26	19
4	4	1	43	16	19
5	1	1	42	36	20
\.


--
-- Data for Name: portitem; Type: TABLE DATA; Schema: public; Owner: rlbaltha
--

COPY portitem (id, name, description, sortorder, portset_id) FROM stdin;
17	Upper portfolio item	<p>\r\n\tDescription of the item&nbsp;Upper</p>	2	5
16	Bio	<p>\r\n\tDescription of the bio</p>	2	5
18	Revision Reflection	<p>\r\n\tDescription of the reflection</p>	3	5
19	Revision Reflection	<p>\r\n\tReflection on revision.</p>	3	4
13	Biography	<p>\r\n\tAcademic biography</p>	2	4
4	IRE	<p>\r\n\tDescription of the IRE...</p>	2	4
20	Process Reflection	<p>\r\n\tDescription of the process</p>	4	4
\.


--
-- Data for Name: portset; Type: TABLE DATA; Schema: public; Owner: rlbaltha
--

COPY portset (id, name, description, user_id) FROM stdin;
4	FYC Standard Portfolio	<p>\r\n\tThe standard portfolio for FYC.</p>	1
5	Upper Div Portfolio	<p>\r\n\tPortfolio for majors</p>	1
\.


--
-- Data for Name: project; Type: TABLE DATA; Schema: public; Owner: rlbaltha
--

COPY project (id, name, sortorder, resource, course_id) FROM stdin;
102	Paper 1	1	t	42
136	Paper 1	1	t	49
137	Paper 2	2	t	49
138	Paper 3	3	t	49
139	Portfolio Prep	4	t	49
103	Paper 2	2	t	42
104	Paper 3	3	t	42
105	Portfolio Prep	4	t	42
116	Paper 3	3	t	45
117	Portfolio Prep	4	t	45
115	Paper 2	2	t	45
114	Paper 1	1	t	45
109	Portfolio Prep	5	t	43
108	Paper 3	4	t	43
107	Paper  2	3	t	43
106	Paper 1	2	t	43
\.


--
-- Data for Name: reply; Type: TABLE DATA; Schema: public; Owner: rlbaltha
--

COPY reply (id, comment_id, user_id, body, created, updated) FROM stdin;
1	20	1	<p>\r\n\tQuisque nunc lorem, commodo sed vulputate ut, aliquet ut felis. Nam eget nunc sit amet quam tempor cursus. Suspendisse ligula odio, consectetur eu sollicitudin at, fermentum ac risus. Sed interdum aliquam massa quis bibendum.</p>	2012-06-29 13:56:00	2012-06-29 16:13:37
4	20	4	<p>\r\n\tUt neque massa, varius non ullamcorper non, semper placerat est. Etiam lorem erat, dignissim vitae commodo vel, auctor id erat. Praesent tempor aliquam lacinia. Phasellus convallis mauris a neque congue malesuada hendrerit nisi congue. Donec lacinia bibendum odio et malesuada. Aliquam erat volutpat. Integer non lectus urna. Integer vel lorem nec urna congue varius. Sed scelerisque aliquam neque, eu convallis diam lobortis vel. Suspendisse nibh mi, volutpat in egestas quis, elementum non ante. Ut vel purus euismod nisl iaculis pharetra et in velit. Morbi vel nibh dolor. In mi metus, dictum eget gravida nec, venenatis non justo. Fusce adipiscing quam nec magna volutpat dapibus.&nbsp;</p>	2012-06-29 16:17:46	2012-06-29 16:17:46
2	20	1	<p>\r\n\tQuisque nunc lorem, commodo sed vulputate ut, aliquet ut felis. Nam eget nunc sit amet quam tempor cursus. Suspendisse ligula odio, consectetur eu sollicitudin at, fermentum ac risus. Sed interdum aliquam massa quis bibendum. Nullam sagittis commodo nisl, in malesuada elit lacinia sit amet. Sed sed leo ac felis cursus cursus at non ipsum. Phasellus varius, eros et accumsan laoreet, lorem mi lacinia erat, in semper justo urna a elit.</p>\r\n<p>\r\n\tPellentesque tempus quam sed tellus semper bibendum. Nam vel tellus id massa auctor elementum. Pellentesque accumsan, eros a lacinia molestie, velit felis viverra sapien, ut faucibus dolor arcu dignissim lectus. Suspendisse potenti. Nunc vitae erat erat. Duis aliquet enim sit amet velit pellentesque egestas laoreet turpis mattis. In commodo facilisis elementum.</p>	2012-06-29 13:56:36	2012-06-29 16:22:48
3	21	1	<p>\r\n\tCras sollicitudin, arcu id rutrum imperdiet, mauris tortor pretium velit, a lobortis enim leo eget purus. Quisque hendrerit vulputate facilisis. Fusce tempus bibendum nibh, eu blandit leo volutpat ac. Donec laoreet placerat fermentum.</p>\r\n<p>\r\n\tMauris sed nisi quis nibh aliquam faucibus et et sapien. Ut vel orci purus. Proin et malesuada felis. Nam ut nibh urna. Proin a odio a turpis mattis adipiscing. Fusce quis dapibus nibh.</p>	2012-06-29 14:04:37	2012-06-29 16:23:00
5	20	4	<p>\r\n\tDonec vestibulum, dui non dignissim tincidunt, sem mauris scelerisque leo, vel ultrices turpis elit at ipsum. Fusce porttitor tellus dapibus felis rhoncus eget commodo tortor viverra. Duis tristique, metus sed venenatis sagittis, justo orci euismod quam, vel fringilla orci lectus in nulla. Morbi molestie aliquet lectus, in sodales odio dapibus at. Maecenas vel pharetra lectus. Praesent ut egestas justo. Mauris elit ante, convallis at pulvinar id, hendrerit eu massa. Sed euismod euismod egestas. Cras mollis lobortis mi id mollis.&nbsp;</p>	2012-06-29 16:23:56	2012-06-29 16:23:56
\.


--
-- Data for Name: roll; Type: TABLE DATA; Schema: public; Owner: rlbaltha
--

COPY roll (id, role, status, course_id, user_id) FROM stdin;
27	a:1:{i:0;s:10:"instructor";}	1	42	1
28	a:1:{i:0;s:10:"instructor";}	1	43	1
30	a:1:{i:0;s:10:"instructor";}	1	45	1
39	a:1:{i:0;s:10:"instructor";}	1	49	1
42	a:1:{i:1;s:7:"student";}	1	42	4
\.


--
-- Data for Name: tag; Type: TABLE DATA; Schema: public; Owner: rlbaltha
--

COPY tag (id, name, color, icon, sort, user_id) FROM stdin;
2	Draft 2	Blue	\N	\N	\N
1	Draft 1	Green	s	\N	\N
3	Final	Black	\N	\N	\N
4	Draft 1	Blue	\N	\N	1
5	Draft 2	Blue	\N	\N	1
\.


--
-- Data for Name: tag_tagset; Type: TABLE DATA; Schema: public; Owner: rlbaltha
--

COPY tag_tagset (tag_id, tagset_id) FROM stdin;
4	3
5	3
\.


--
-- Data for Name: tagset; Type: TABLE DATA; Schema: public; Owner: rlbaltha
--

COPY tagset (id, name, user_id) FROM stdin;
3	Comp	1
\.


--
-- Data for Name: term; Type: TABLE DATA; Schema: public; Owner: rlbaltha
--

COPY term (id, term, termname, status) FROM stdin;
2	201205	Spring 2012	0
3	201208	Fall 2012	0
1	201202	Winter 2012	1
\.


--
-- Name: calendar_pkey; Type: CONSTRAINT; Schema: public; Owner: rlbaltha; Tablespace: 
--

ALTER TABLE ONLY calendar
    ADD CONSTRAINT calendar_pkey PRIMARY KEY (id);


--
-- Name: comment_pkey; Type: CONSTRAINT; Schema: public; Owner: rlbaltha; Tablespace: 
--

ALTER TABLE ONLY comment
    ADD CONSTRAINT comment_pkey PRIMARY KEY (id);


--
-- Name: course_pkey; Type: CONSTRAINT; Schema: public; Owner: rlbaltha; Tablespace: 
--

ALTER TABLE ONLY course
    ADD CONSTRAINT course_pkey PRIMARY KEY (id);


--
-- Name: course_tagset_pkey; Type: CONSTRAINT; Schema: public; Owner: rlbaltha; Tablespace: 
--

ALTER TABLE ONLY course_tagset
    ADD CONSTRAINT course_tagset_pkey PRIMARY KEY (course_id, tagset_id);


--
-- Name: doc_pkey; Type: CONSTRAINT; Schema: public; Owner: rlbaltha; Tablespace: 
--

ALTER TABLE ONLY doc
    ADD CONSTRAINT doc_pkey PRIMARY KEY (id);


--
-- Name: ext_log_entries_pkey; Type: CONSTRAINT; Schema: public; Owner: rlbaltha; Tablespace: 
--

ALTER TABLE ONLY ext_log_entries
    ADD CONSTRAINT ext_log_entries_pkey PRIMARY KEY (id);


--
-- Name: ext_translations_pkey; Type: CONSTRAINT; Schema: public; Owner: rlbaltha; Tablespace: 
--

ALTER TABLE ONLY ext_translations
    ADD CONSTRAINT ext_translations_pkey PRIMARY KEY (id);


--
-- Name: file_pkey; Type: CONSTRAINT; Schema: public; Owner: rlbaltha; Tablespace: 
--

ALTER TABLE ONLY file
    ADD CONSTRAINT file_pkey PRIMARY KEY (id);


--
-- Name: file_portitem_pkey; Type: CONSTRAINT; Schema: public; Owner: rlbaltha; Tablespace: 
--

ALTER TABLE ONLY file_portitem
    ADD CONSTRAINT file_portitem_pkey PRIMARY KEY (file_id, portitem_id);


--
-- Name: file_tag_pkey; Type: CONSTRAINT; Schema: public; Owner: rlbaltha; Tablespace: 
--

ALTER TABLE ONLY file_tag
    ADD CONSTRAINT file_tag_pkey PRIMARY KEY (file_id, tag_id);


--
-- Name: forum_pkey; Type: CONSTRAINT; Schema: public; Owner: rlbaltha; Tablespace: 
--

ALTER TABLE ONLY forum
    ADD CONSTRAINT forum_pkey PRIMARY KEY (id);


--
-- Name: journal_pkey; Type: CONSTRAINT; Schema: public; Owner: rlbaltha; Tablespace: 
--

ALTER TABLE ONLY journal
    ADD CONSTRAINT journal_pkey PRIMARY KEY (id);


--
-- Name: marca_user_pkey; Type: CONSTRAINT; Schema: public; Owner: rlbaltha; Tablespace: 
--

ALTER TABLE ONLY marca_user
    ADD CONSTRAINT marca_user_pkey PRIMARY KEY (id);


--
-- Name: markup_pkey; Type: CONSTRAINT; Schema: public; Owner: rlbaltha; Tablespace: 
--

ALTER TABLE ONLY markup
    ADD CONSTRAINT markup_pkey PRIMARY KEY (id);


--
-- Name: page_pkey; Type: CONSTRAINT; Schema: public; Owner: rlbaltha; Tablespace: 
--

ALTER TABLE ONLY page
    ADD CONSTRAINT page_pkey PRIMARY KEY (id);


--
-- Name: portfolio_pkey; Type: CONSTRAINT; Schema: public; Owner: rlbaltha; Tablespace: 
--

ALTER TABLE ONLY portfolio
    ADD CONSTRAINT portfolio_pkey PRIMARY KEY (id);


--
-- Name: portitem_pkey; Type: CONSTRAINT; Schema: public; Owner: rlbaltha; Tablespace: 
--

ALTER TABLE ONLY portitem
    ADD CONSTRAINT portitem_pkey PRIMARY KEY (id);


--
-- Name: portset_pkey; Type: CONSTRAINT; Schema: public; Owner: rlbaltha; Tablespace: 
--

ALTER TABLE ONLY portset
    ADD CONSTRAINT portset_pkey PRIMARY KEY (id);


--
-- Name: project_pkey; Type: CONSTRAINT; Schema: public; Owner: rlbaltha; Tablespace: 
--

ALTER TABLE ONLY project
    ADD CONSTRAINT project_pkey PRIMARY KEY (id);


--
-- Name: reply_pkey; Type: CONSTRAINT; Schema: public; Owner: rlbaltha; Tablespace: 
--

ALTER TABLE ONLY reply
    ADD CONSTRAINT reply_pkey PRIMARY KEY (id);


--
-- Name: roll_pkey; Type: CONSTRAINT; Schema: public; Owner: rlbaltha; Tablespace: 
--

ALTER TABLE ONLY roll
    ADD CONSTRAINT roll_pkey PRIMARY KEY (id);


--
-- Name: tag_pkey; Type: CONSTRAINT; Schema: public; Owner: rlbaltha; Tablespace: 
--

ALTER TABLE ONLY tag
    ADD CONSTRAINT tag_pkey PRIMARY KEY (id);


--
-- Name: tag_tagset_pkey; Type: CONSTRAINT; Schema: public; Owner: rlbaltha; Tablespace: 
--

ALTER TABLE ONLY tag_tagset
    ADD CONSTRAINT tag_tagset_pkey PRIMARY KEY (tag_id, tagset_id);


--
-- Name: tagset_pkey; Type: CONSTRAINT; Schema: public; Owner: rlbaltha; Tablespace: 
--

ALTER TABLE ONLY tagset
    ADD CONSTRAINT tagset_pkey PRIMARY KEY (id);


--
-- Name: term_pkey; Type: CONSTRAINT; Schema: public; Owner: rlbaltha; Tablespace: 
--

ALTER TABLE ONLY term
    ADD CONSTRAINT term_pkey PRIMARY KEY (id);


--
-- Name: idx_11326a8fa76ed395; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_11326a8fa76ed395 ON course USING btree (user_id);


--
-- Name: idx_11326a8fc6de68c3; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_11326a8fc6de68c3 ON course USING btree (portset_id);


--
-- Name: idx_11326a8fe2c35fc; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_11326a8fe2c35fc ON course USING btree (term_id);


--
-- Name: idx_21af56b5a76ed395; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_21af56b5a76ed395 ON markup USING btree (user_id);


--
-- Name: idx_2b1c92c1591cc992; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_2b1c92c1591cc992 ON portfolio USING btree (course_id);


--
-- Name: idx_2b1c92c193cb796c; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_2b1c92c193cb796c ON portfolio USING btree (file_id);


--
-- Name: idx_2b1c92c1a76ed395; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_2b1c92c1a76ed395 ON portfolio USING btree (user_id);


--
-- Name: idx_2b1c92c1cc0545fb; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_2b1c92c1cc0545fb ON portfolio USING btree (portitem_id);


--
-- Name: idx_2cad992e166d1f9c; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_2cad992e166d1f9c ON file USING btree (project_id);


--
-- Name: idx_2cad992e591cc992; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_2cad992e591cc992 ON file USING btree (course_id);


--
-- Name: idx_2cad992ea76ed395; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_2cad992ea76ed395 ON file USING btree (user_id);


--
-- Name: idx_2cca391a93cb796c; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_2cca391a93cb796c ON file_tag USING btree (file_id);


--
-- Name: idx_2cca391abad26311; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_2cca391abad26311 ON file_tag USING btree (tag_id);


--
-- Name: idx_3901f3eca76ed395; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_3901f3eca76ed395 ON portset USING btree (user_id);


--
-- Name: idx_391d0064c1726df5; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_391d0064c1726df5 ON portitem USING btree (portset_id);


--
-- Name: idx_3bc4f163a76ed395; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_3bc4f163a76ed395 ON tag USING btree (user_id);


--
-- Name: idx_3c69e9e4a76ed395; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_3c69e9e4a76ed395 ON reply USING btree (user_id);


--
-- Name: idx_3c69e9e4f8697d13; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_3c69e9e4f8697d13 ON reply USING btree (comment_id);


--
-- Name: idx_3d2b86cba76ed395; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_3d2b86cba76ed395 ON tagset USING btree (user_id);


--
-- Name: idx_44ea91c9591cc992; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_44ea91c9591cc992 ON forum USING btree (course_id);


--
-- Name: idx_44ea91c9a76ed395; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_44ea91c9a76ed395 ON forum USING btree (user_id);


--
-- Name: idx_5bc96bf029ccbad0; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_5bc96bf029ccbad0 ON comment USING btree (forum_id);


--
-- Name: idx_5bc96bf0a76ed395; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_5bc96bf0a76ed395 ON comment USING btree (user_id);


--
-- Name: idx_713bedaf93cb796c; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_713bedaf93cb796c ON file_portitem USING btree (file_id);


--
-- Name: idx_713bedafcc0545fb; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_713bedafcc0545fb ON file_portitem USING btree (portitem_id);


--
-- Name: idx_8e879df0591cc992; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_8e879df0591cc992 ON roll USING btree (course_id);


--
-- Name: idx_8e879df0a76ed395; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_8e879df0a76ed395 ON roll USING btree (user_id);


--
-- Name: idx_94bf42b0591cc992; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_94bf42b0591cc992 ON course_tagset USING btree (course_id);


--
-- Name: idx_94bf42b0b82ff882; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_94bf42b0b82ff882 ON course_tagset USING btree (tagset_id);


--
-- Name: idx_97dfc310591cc992; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_97dfc310591cc992 ON calendar USING btree (course_id);


--
-- Name: idx_97dfc310a76ed395; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_97dfc310a76ed395 ON calendar USING btree (user_id);


--
-- Name: idx_d3c6cad1b82ff882; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_d3c6cad1b82ff882 ON tag_tagset USING btree (tagset_id);


--
-- Name: idx_d3c6cad1bad26311; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_d3c6cad1bad26311 ON tag_tagset USING btree (tag_id);


--
-- Name: idx_e00ee972591cc992; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_e00ee972591cc992 ON project USING btree (course_id);


--
-- Name: idx_e1aded1591cc992; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_e1aded1591cc992 ON journal USING btree (course_id);


--
-- Name: idx_e1aded1a76ed395; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX idx_e1aded1a76ed395 ON journal USING btree (user_id);


--
-- Name: log_class_lookup_idx; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX log_class_lookup_idx ON ext_log_entries USING btree (object_class);


--
-- Name: log_date_lookup_idx; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX log_date_lookup_idx ON ext_log_entries USING btree (logged_at);


--
-- Name: log_user_lookup_idx; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX log_user_lookup_idx ON ext_log_entries USING btree (username);


--
-- Name: lookup_unique_idx; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE UNIQUE INDEX lookup_unique_idx ON ext_translations USING btree (locale, object_class, foreign_key, field);


--
-- Name: translations_lookup_idx; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE INDEX translations_lookup_idx ON ext_translations USING btree (locale, object_class, foreign_key);


--
-- Name: uniq_11326a8f7fba2c37; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE UNIQUE INDEX uniq_11326a8f7fba2c37 ON course USING btree (projectdefault_id);


--
-- Name: uniq_be0cbb8493cb796c; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE UNIQUE INDEX uniq_be0cbb8493cb796c ON doc USING btree (file_id);


--
-- Name: uniq_d9c3f7492fc23a8; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE UNIQUE INDEX uniq_d9c3f7492fc23a8 ON marca_user USING btree (username_canonical);


--
-- Name: uniq_d9c3f74a0d96fbf; Type: INDEX; Schema: public; Owner: rlbaltha; Tablespace: 
--

CREATE UNIQUE INDEX uniq_d9c3f74a0d96fbf ON marca_user USING btree (email_canonical);


--
-- Name: fk_11326a8f7fba2c37; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY course
    ADD CONSTRAINT fk_11326a8f7fba2c37 FOREIGN KEY (projectdefault_id) REFERENCES project(id) ON DELETE CASCADE;


--
-- Name: fk_11326a8fa76ed395; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY course
    ADD CONSTRAINT fk_11326a8fa76ed395 FOREIGN KEY (user_id) REFERENCES marca_user(id);


--
-- Name: fk_11326a8fc6de68c3; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY course
    ADD CONSTRAINT fk_11326a8fc6de68c3 FOREIGN KEY (portset_id) REFERENCES portset(id);


--
-- Name: fk_11326a8fe2c35fc; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY course
    ADD CONSTRAINT fk_11326a8fe2c35fc FOREIGN KEY (term_id) REFERENCES term(id);


--
-- Name: fk_21af56b5a76ed395; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY markup
    ADD CONSTRAINT fk_21af56b5a76ed395 FOREIGN KEY (user_id) REFERENCES marca_user(id);


--
-- Name: fk_2b1c92c1591cc992; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY portfolio
    ADD CONSTRAINT fk_2b1c92c1591cc992 FOREIGN KEY (course_id) REFERENCES course(id);


--
-- Name: fk_2b1c92c193cb796c; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY portfolio
    ADD CONSTRAINT fk_2b1c92c193cb796c FOREIGN KEY (file_id) REFERENCES file(id);


--
-- Name: fk_2b1c92c1a76ed395; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY portfolio
    ADD CONSTRAINT fk_2b1c92c1a76ed395 FOREIGN KEY (user_id) REFERENCES marca_user(id);


--
-- Name: fk_2b1c92c1cc0545fb; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY portfolio
    ADD CONSTRAINT fk_2b1c92c1cc0545fb FOREIGN KEY (portitem_id) REFERENCES portitem(id);


--
-- Name: fk_2cad992e166d1f9c; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY file
    ADD CONSTRAINT fk_2cad992e166d1f9c FOREIGN KEY (project_id) REFERENCES project(id);


--
-- Name: fk_2cad992e591cc992; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY file
    ADD CONSTRAINT fk_2cad992e591cc992 FOREIGN KEY (course_id) REFERENCES course(id);


--
-- Name: fk_2cad992ea76ed395; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY file
    ADD CONSTRAINT fk_2cad992ea76ed395 FOREIGN KEY (user_id) REFERENCES marca_user(id);


--
-- Name: fk_2cca391a93cb796c; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY file_tag
    ADD CONSTRAINT fk_2cca391a93cb796c FOREIGN KEY (file_id) REFERENCES file(id) ON DELETE CASCADE;


--
-- Name: fk_2cca391abad26311; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY file_tag
    ADD CONSTRAINT fk_2cca391abad26311 FOREIGN KEY (tag_id) REFERENCES tag(id) ON DELETE CASCADE;


--
-- Name: fk_3901f3eca76ed395; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY portset
    ADD CONSTRAINT fk_3901f3eca76ed395 FOREIGN KEY (user_id) REFERENCES marca_user(id);


--
-- Name: fk_391d0064c1726df5; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY portitem
    ADD CONSTRAINT fk_391d0064c1726df5 FOREIGN KEY (portset_id) REFERENCES portset(id);


--
-- Name: fk_3bc4f163a76ed395; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY tag
    ADD CONSTRAINT fk_3bc4f163a76ed395 FOREIGN KEY (user_id) REFERENCES marca_user(id);


--
-- Name: fk_3c69e9e4a76ed395; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY reply
    ADD CONSTRAINT fk_3c69e9e4a76ed395 FOREIGN KEY (user_id) REFERENCES marca_user(id);


--
-- Name: fk_3c69e9e4f8697d13; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY reply
    ADD CONSTRAINT fk_3c69e9e4f8697d13 FOREIGN KEY (comment_id) REFERENCES comment(id);


--
-- Name: fk_3d2b86cba76ed395; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY tagset
    ADD CONSTRAINT fk_3d2b86cba76ed395 FOREIGN KEY (user_id) REFERENCES marca_user(id);


--
-- Name: fk_44ea91c9591cc992; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY forum
    ADD CONSTRAINT fk_44ea91c9591cc992 FOREIGN KEY (course_id) REFERENCES course(id);


--
-- Name: fk_44ea91c9a76ed395; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY forum
    ADD CONSTRAINT fk_44ea91c9a76ed395 FOREIGN KEY (user_id) REFERENCES marca_user(id);


--
-- Name: fk_5bc96bf029ccbad0; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY comment
    ADD CONSTRAINT fk_5bc96bf029ccbad0 FOREIGN KEY (forum_id) REFERENCES forum(id);


--
-- Name: fk_5bc96bf0a76ed395; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY comment
    ADD CONSTRAINT fk_5bc96bf0a76ed395 FOREIGN KEY (user_id) REFERENCES marca_user(id);


--
-- Name: fk_713bedaf93cb796c; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY file_portitem
    ADD CONSTRAINT fk_713bedaf93cb796c FOREIGN KEY (file_id) REFERENCES file(id) ON DELETE CASCADE;


--
-- Name: fk_713bedafcc0545fb; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY file_portitem
    ADD CONSTRAINT fk_713bedafcc0545fb FOREIGN KEY (portitem_id) REFERENCES portitem(id) ON DELETE CASCADE;


--
-- Name: fk_8e879df0591cc992; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY roll
    ADD CONSTRAINT fk_8e879df0591cc992 FOREIGN KEY (course_id) REFERENCES course(id) ON DELETE CASCADE;


--
-- Name: fk_8e879df0a76ed395; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY roll
    ADD CONSTRAINT fk_8e879df0a76ed395 FOREIGN KEY (user_id) REFERENCES marca_user(id);


--
-- Name: fk_94bf42b03ade7a21; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY course_tagset
    ADD CONSTRAINT fk_94bf42b03ade7a21 FOREIGN KEY (tagset_id) REFERENCES tagset(id);


--
-- Name: fk_94bf42b0dbed4b31; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY course_tagset
    ADD CONSTRAINT fk_94bf42b0dbed4b31 FOREIGN KEY (course_id) REFERENCES course(id);


--
-- Name: fk_97dfc310591cc992; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY calendar
    ADD CONSTRAINT fk_97dfc310591cc992 FOREIGN KEY (course_id) REFERENCES course(id);


--
-- Name: fk_97dfc310a76ed395; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY calendar
    ADD CONSTRAINT fk_97dfc310a76ed395 FOREIGN KEY (user_id) REFERENCES marca_user(id);


--
-- Name: fk_be0cbb8493cb796c; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY doc
    ADD CONSTRAINT fk_be0cbb8493cb796c FOREIGN KEY (file_id) REFERENCES file(id) ON DELETE CASCADE;


--
-- Name: fk_d3c6cad1b82ff882; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY tag_tagset
    ADD CONSTRAINT fk_d3c6cad1b82ff882 FOREIGN KEY (tagset_id) REFERENCES tagset(id) ON DELETE CASCADE;


--
-- Name: fk_d3c6cad1bad26311; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY tag_tagset
    ADD CONSTRAINT fk_d3c6cad1bad26311 FOREIGN KEY (tag_id) REFERENCES tag(id) ON DELETE CASCADE;


--
-- Name: fk_e00ee972591cc992; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY project
    ADD CONSTRAINT fk_e00ee972591cc992 FOREIGN KEY (course_id) REFERENCES course(id) ON DELETE CASCADE;


--
-- Name: fk_e1aded1591cc992; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY journal
    ADD CONSTRAINT fk_e1aded1591cc992 FOREIGN KEY (course_id) REFERENCES course(id);


--
-- Name: fk_e1aded1a76ed395; Type: FK CONSTRAINT; Schema: public; Owner: rlbaltha
--

ALTER TABLE ONLY journal
    ADD CONSTRAINT fk_e1aded1a76ed395 FOREIGN KEY (user_id) REFERENCES marca_user(id);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

