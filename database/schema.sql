CREATE TABLE IF NOT EXISTS shulesoft.connect_organizations
(
    id bigserial,
    username character varying(255) COLLATE pg_catalog."default" NOT NULL,
	name character varying(255) COLLATE pg_catalog."default" NOT NULL,
	description text,
    is_active boolean NOT NULL DEFAULT true,
    created_at timestamp(0) without time zone default now(),
    updated_at timestamp(0) without time zone,
    CONSTRAINT connect_organizations_pkey PRIMARY KEY (id),
    CONSTRAINT connect_organizations_name_unique UNIQUE (username)
);


CREATE TABLE IF NOT EXISTS shulesoft.connect_roles
(
    id bigserial,
    name character varying(255) COLLATE pg_catalog."default" NOT NULL,
    display_name character varying(255) COLLATE pg_catalog."default" NOT NULL,
    description text COLLATE pg_catalog."default",
    menu_access json,
    is_active boolean NOT NULL DEFAULT true,
    created_at timestamp(0) without time zone default now(),
    updated_at timestamp(0) without time zone,
    CONSTRAINT roles_pkey PRIMARY KEY (id),
    CONSTRAINT roles_name_unique UNIQUE (name)
);

CREATE TABLE IF NOT EXISTS shulesoft.connect_users
(
    id bigserial,
    name character varying(255) COLLATE pg_catalog."default" NOT NULL,
    email character varying(255) COLLATE pg_catalog."default" NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) COLLATE pg_catalog."default" NOT NULL,
    role_id bigint,
    status character varying(255) COLLATE pg_catalog."default" NOT NULL DEFAULT 'pending'::character varying,
    phone character varying(255) COLLATE pg_catalog."default",
    avatar character varying(255) COLLATE pg_catalog."default",
    last_login_at timestamp(0) without time zone,
    last_login_ip character varying(255) COLLATE pg_catalog."default",
    remember_token character varying(100) COLLATE pg_catalog."default",
	connect_organization_id bigint NOT NULL,
    created_at timestamp(0) without time zone default now(),
    updated_at timestamp(0) without time zone,
    CONSTRAINT connect_users_pkey PRIMARY KEY (id),
    CONSTRAINT connect_users_email_unique UNIQUE (email),
	CONSTRAINT connect_users_phone_unique UNIQUE (phone),
    CONSTRAINT connect_users_role_id_foreign FOREIGN KEY (role_id)
        REFERENCES shulesoft.connect_roles (id) MATCH SIMPLE
        ON UPDATE CASCADE
        ON DELETE CASCADE,
	 CONSTRAINT connect_organization_id_foreign FOREIGN KEY (connect_organization_id)
        REFERENCES shulesoft.connect_organizations (id) MATCH SIMPLE
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT users_status_check CHECK (status::text = ANY (ARRAY['active'::character varying, 'inactive'::character varying, 'pending'::character varying]::text[]))
);


CREATE TABLE IF NOT EXISTS shulesoft.connect_permissions
(
    id bigserial,
    name character varying(255) COLLATE pg_catalog."default" NOT NULL,
    display_name character varying(255) COLLATE pg_catalog."default" NOT NULL,
    description text COLLATE pg_catalog."default",
    module character varying(255) COLLATE pg_catalog."default" NOT NULL,
    action character varying(255) COLLATE pg_catalog."default" NOT NULL,
    created_at timestamp(0) without time zone default now(),
    updated_at timestamp(0) without time zone,
    CONSTRAINT permissions_pkey PRIMARY KEY (id),
    CONSTRAINT permissions_name_unique UNIQUE (name)
);

CREATE TABLE IF NOT EXISTS shulesoft.connect_role_permissions
(
    id bigserial,
    role_id bigint NOT NULL,
    permission_id bigint NOT NULL,
    created_at timestamp(0) without time zone default now(),
    updated_at timestamp(0) without time zone,
    CONSTRAINT connect_role_permissions_pkey PRIMARY KEY (id),
    CONSTRAINT connect_role_permissions_role_id_permission_id_unique UNIQUE (role_id, permission_id),
    CONSTRAINT connect_role_permissions_permission_id_foreign FOREIGN KEY (permission_id)
        REFERENCES shulesoft.connect_permissions (id) MATCH SIMPLE
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT role_permissions_role_id_foreign FOREIGN KEY (role_id)
        REFERENCES shulesoft.connect_roles (id) MATCH SIMPLE
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS shulesoft.connect_schools
(
    id bigserial,
	school_setting_uid integer,
	connect_organization_id bigint NOT NULL,

	connect_user_id bigint NOT NULL,
   
    is_active boolean NOT NULL DEFAULT true,

	
    shulesoft_code character varying(255) COLLATE pg_catalog."default",
    settings json,
	created_by integer,
    created_at timestamp(0) without time zone default now(),
    updated_at timestamp(0) without time zone,
    CONSTRAINT connect_schools_pkey PRIMARY KEY (id),
    CONSTRAINT connect_schools_shulesoft_code_unique UNIQUE (shulesoft_code),
	CONSTRAINT user_schools_user_id_school_id_unique UNIQUE (connect_user_id, connect_organization_id),
	CONSTRAINT school_setting_id_foreign FOREIGN KEY (school_setting_uid)
        REFERENCES shulesoft.setting (uid) MATCH SIMPLE
        ON UPDATE CASCADE
        ON DELETE CASCADE,
	CONSTRAINT connect_organization_id_foreign FOREIGN KEY (connect_organization_id)
        REFERENCES shulesoft.connect_organizations (id) MATCH SIMPLE
        ON UPDATE CASCADE
        ON DELETE CASCADE,
	CONSTRAINT connect_schools_created_by_foreign FOREIGN KEY (created_by)
        REFERENCES shulesoft.connect_users (id) MATCH SIMPLE
        ON UPDATE CASCADE
        ON DELETE CASCADE,
	CONSTRAINT user_schools_user_id_foreign FOREIGN KEY (connect_user_id)
        REFERENCES shulesoft.connect_users (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE CASCADE
);

--shulesoft main tables
--



--
-- TOC entry 1765 (class 1259 OID 49594)
-- Name: academic_year_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.academic_year_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.academic_year_id_seq OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 1766 (class 1259 OID 49595)
-- Name: academic_year; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.academic_year (
    id integer DEFAULT nextval('shulesoft.academic_year_id_seq'::regclass) NOT NULL,
    name character varying(100) NOT NULL,
    class_level_id integer NOT NULL,
    created_at timestamp without time zone DEFAULT ('now'::text)::timestamp(0) without time zone NOT NULL,
    updated_at timestamp without time zone,
    status smallint DEFAULT 1,
    start_date date,
    end_date date,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.academic_year OWNER TO postgres;

--
-- TOC entry 1818 (class 1259 OID 49929)
-- Name: academic_year_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.academic_year_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.academic_year_uid_seq OWNER TO postgres;

--
-- TOC entry 14025 (class 0 OID 0)
-- Dependencies: 1818
-- Name: academic_year_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.academic_year_uid_seq OWNED BY shulesoft.academic_year.uid;


--
-- TOC entry 1819 (class 1259 OID 49930)
-- Name: account_groups_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.account_groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.account_groups_id_seq OWNER TO postgres;

--
-- TOC entry 1820 (class 1259 OID 49931)
-- Name: account_groups; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.account_groups (
    id integer DEFAULT nextval('shulesoft.account_groups_id_seq'::regclass) NOT NULL,
    name character varying,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    note text,
    category_id integer,
    financial_category_id integer NOT NULL,
    predefined smallint DEFAULT 0,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.account_groups OWNER TO postgres;

--
-- TOC entry 1821 (class 1259 OID 49939)
-- Name: account_groups_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.account_groups_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.account_groups_uid_seq OWNER TO postgres;

--
-- TOC entry 14026 (class 0 OID 0)
-- Dependencies: 1821
-- Name: account_groups_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.account_groups_uid_seq OWNED BY shulesoft.account_groups.uid;


--
-- TOC entry 1767 (class 1259 OID 49604)
-- Name: admissions_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.admissions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.admissions_id_seq OWNER TO postgres;

--
-- TOC entry 1768 (class 1259 OID 49605)
-- Name: admissions; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.admissions (
    id integer DEFAULT nextval('shulesoft.admissions_id_seq'::regclass) NOT NULL,
    student_id integer,
    status integer,
    user_id integer,
    created_at timestamp without time zone DEFAULT '2019-09-19 12:17:33.401324'::timestamp without time zone NOT NULL,
    updated_at timestamp without time zone,
    comment text,
    "time" character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.admissions OWNER TO postgres;

--
-- TOC entry 1822 (class 1259 OID 49940)
-- Name: admissions_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.admissions_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.admissions_uid_seq OWNER TO postgres;

--
-- TOC entry 14027 (class 0 OID 0)
-- Dependencies: 1822
-- Name: admissions_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.admissions_uid_seq OWNED BY shulesoft.admissions.uid;


--
-- TOC entry 1823 (class 1259 OID 49941)
-- Name: advance_payments_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.advance_payments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.advance_payments_id_seq OWNER TO postgres;

--
-- TOC entry 1824 (class 1259 OID 49942)
-- Name: advance_payments; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.advance_payments (
    id integer DEFAULT nextval('shulesoft.advance_payments_id_seq'::regclass) NOT NULL,
    student_id integer,
    fee_id integer,
    payment_id integer,
    amount numeric,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone,
    date date,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.advance_payments OWNER TO postgres;

--
-- TOC entry 1825 (class 1259 OID 49950)
-- Name: advance_payments_invoices_fees_installments_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.advance_payments_invoices_fees_installments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.advance_payments_invoices_fees_installments_id_seq OWNER TO postgres;

--
-- TOC entry 1826 (class 1259 OID 49951)
-- Name: advance_payments_invoices_fees_installments; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.advance_payments_invoices_fees_installments (
    id integer DEFAULT nextval('shulesoft.advance_payments_invoices_fees_installments_id_seq'::regclass) NOT NULL,
    invoices_fees_installments_id integer,
    advance_payment_id integer,
    amount numeric,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    date date,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.advance_payments_invoices_fees_installments OWNER TO postgres;

--
-- TOC entry 1827 (class 1259 OID 49959)
-- Name: advance_amount_balance; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.advance_amount_balance AS
 SELECT sum(p.amount) AS total_amount,
    sum((COALESCE(p.amount, (0)::numeric) - COALESCE(r_1.total_advance_invoice_fee_amount, (0)::numeric))) AS reminder,
    p.fee_id,
    p.student_id,
    p.schema_name
   FROM (shulesoft.advance_payments p
     LEFT JOIN ( SELECT sum(b_1.amount) AS total_advance_invoice_fee_amount,
            b_1.advance_payment_id,
            b_1.schema_name
           FROM shulesoft.advance_payments_invoices_fees_installments b_1
          GROUP BY b_1.advance_payment_id, b_1.schema_name) r_1 ON (((r_1.advance_payment_id = p.id) AND ((p.schema_name)::text = (r_1.schema_name)::text))))
  GROUP BY p.fee_id, p.student_id, p.schema_name;


ALTER VIEW shulesoft.advance_amount_balance OWNER TO postgres;

--
-- TOC entry 1828 (class 1259 OID 49964)
-- Name: advance_payment_balance; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.advance_payment_balance AS
 SELECT sum(amount) AS total_amount,
    sum((COALESCE(amount, (0)::numeric) - COALESCE(( SELECT sum(advance_payments_invoices_fees_installments.amount) AS sum
           FROM shulesoft.advance_payments_invoices_fees_installments
          WHERE (advance_payments_invoices_fees_installments.advance_payment_id = p.id)), (0)::numeric))) AS reminder,
    fee_id,
    student_id,
    schema_name
   FROM shulesoft.advance_payments p
  GROUP BY fee_id, student_id, schema_name;


ALTER VIEW shulesoft.advance_payment_balance OWNER TO postgres;

--
-- TOC entry 1829 (class 1259 OID 49968)
-- Name: advance_payments_invoices_fees_installments_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.advance_payments_invoices_fees_installments_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.advance_payments_invoices_fees_installments_uid_seq OWNER TO postgres;

--
-- TOC entry 14028 (class 0 OID 0)
-- Dependencies: 1829
-- Name: advance_payments_invoices_fees_installments_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.advance_payments_invoices_fees_installments_uid_seq OWNED BY shulesoft.advance_payments_invoices_fees_installments.uid;


--
-- TOC entry 1830 (class 1259 OID 49969)
-- Name: advance_payments_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.advance_payments_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.advance_payments_uid_seq OWNER TO postgres;

--
-- TOC entry 14029 (class 0 OID 0)
-- Dependencies: 1830
-- Name: advance_payments_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.advance_payments_uid_seq OWNED BY shulesoft.advance_payments.uid;


--
-- TOC entry 1769 (class 1259 OID 49613)
-- Name: bank_accounts_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.bank_accounts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.bank_accounts_id_seq OWNER TO postgres;

--
-- TOC entry 1770 (class 1259 OID 49614)
-- Name: bank_accounts; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.bank_accounts (
    id integer DEFAULT nextval('shulesoft.bank_accounts_id_seq'::regclass) NOT NULL,
    name character varying,
    number character varying,
    currency character varying,
    branch character varying,
    refer_bank_id integer,
    opening_balance numeric,
    note text,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    refer_currency_id integer,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    is_main smallint DEFAULT 0
);


ALTER TABLE shulesoft.bank_accounts OWNER TO postgres;

--
-- TOC entry 1781 (class 1259 OID 49678)
-- Name: expense_expenseID_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft."expense_expenseID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft."expense_expenseID_seq" OWNER TO postgres;

--
-- TOC entry 1782 (class 1259 OID 49679)
-- Name: expense; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.expense (
    "expenseID" integer DEFAULT nextval('shulesoft."expense_expenseID_seq"'::regclass) NOT NULL,
    create_date date NOT NULL,
    date date NOT NULL,
    expense character varying NOT NULL,
    amount numeric NOT NULL,
    "userID" integer,
    uname character varying(60) NOT NULL,
    usertype character varying(190) NOT NULL,
    expenseyear integer NOT NULL,
    note text,
    created_at timestamp without time zone DEFAULT now(),
    "categoryID" integer,
    is_depreciation character(1),
    depreciation numeric,
    refer_expense_id integer,
    ref_no character varying,
    payment_method character varying,
    created_by character varying,
    bank_account_id integer,
    transaction_id character varying,
    created_by_table character varying,
    reconciled smallint DEFAULT 0,
    voucher_no integer DEFAULT 0,
    payer_name character varying,
    recipient character varying,
    updated_at timestamp without time zone,
    payment_type_id integer,
    voucher integer,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.expense OWNER TO postgres;

--
-- TOC entry 1831 (class 1259 OID 49970)
-- Name: payment_types_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.payment_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.payment_types_id_seq OWNER TO postgres;

--
-- TOC entry 1832 (class 1259 OID 49971)
-- Name: payment_types; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.payment_types (
    id integer DEFAULT nextval('shulesoft.payment_types_id_seq'::regclass) NOT NULL,
    name character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.payment_types OWNER TO postgres;

--
-- TOC entry 1833 (class 1259 OID 49979)
-- Name: payments_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.payments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.payments_id_seq OWNER TO postgres;

--
-- TOC entry 1834 (class 1259 OID 49980)
-- Name: payments; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.payments (
    id integer DEFAULT nextval('shulesoft.payments_id_seq'::regclass) NOT NULL,
    student_id integer NOT NULL,
    amount numeric NOT NULL,
    payment_type_id integer,
    date date NOT NULL,
    transaction_id character varying,
    created_at timestamp without time zone DEFAULT now(),
    cheque_number character varying,
    bank_account_id integer,
    payer_name character varying,
    mobile_transaction_id character varying,
    transaction_time character varying,
    account_number character varying,
    token character varying,
    reconciled smallint DEFAULT 0,
    receipt_code character varying,
    updated_at timestamp without time zone,
    channel character varying,
    amount_entered numeric,
    created_by integer,
    created_by_table character varying,
    note character varying DEFAULT 'Fee Payments'::character varying,
    invoice_id integer,
    status smallint DEFAULT 0,
    sid integer,
    priority character varying DEFAULT '{0}'::integer[],
    comment text,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    verification_code character varying,
    verification_url character varying,
    code character varying,
    refer_expense_id integer
);


ALTER TABLE shulesoft.payments OWNER TO postgres;

--
-- TOC entry 1835 (class 1259 OID 49992)
-- Name: revenues_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.revenues_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.revenues_id_seq OWNER TO postgres;

--
-- TOC entry 1836 (class 1259 OID 49993)
-- Name: revenues_number_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.revenues_number_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.revenues_number_seq OWNER TO postgres;

--
-- TOC entry 1837 (class 1259 OID 49994)
-- Name: revenues; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.revenues (
    id integer DEFAULT nextval('shulesoft.revenues_id_seq'::regclass) NOT NULL,
    payer_name character varying,
    payer_phone character varying,
    payer_email character varying,
    refer_expense_id integer,
    amount numeric,
    created_by_id integer,
    created_by_table character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    payment_method character varying,
    transaction_id character varying,
    bank_account_id integer,
    invoice_number character varying,
    note text,
    date date,
    user_in_shulesoft smallint,
    user_id integer,
    user_table character varying,
    reconciled smallint DEFAULT 0,
    number integer DEFAULT nextval('shulesoft.revenues_number_seq'::regclass) NOT NULL,
    payment_type_id integer,
    loan_application_id integer,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    status smallint DEFAULT 1,
    reference character varying(30),
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.revenues OWNER TO postgres;

--
-- TOC entry 1807 (class 1259 OID 49866)
-- Name: student_student_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_student_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.student_student_id_seq OWNER TO postgres;

--
-- TOC entry 1808 (class 1259 OID 49867)
-- Name: student; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.student (
    student_id integer DEFAULT nextval('shulesoft.student_student_id_seq'::regclass) NOT NULL,
    name character varying(60) NOT NULL,
    dob date NOT NULL,
    sex character varying(10) NOT NULL,
    email character varying,
    phone text,
    address text,
    "classesID" integer NOT NULL,
    "sectionID" integer,
    roll text NOT NULL,
    create_date date DEFAULT now() NOT NULL,
    photo character varying(200) DEFAULT 'defualt.png'::character varying,
    year integer,
    username character varying(250) NOT NULL,
    password character varying(128) NOT NULL,
    usertype character varying(20) NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    academic_year_id bigint,
    status integer DEFAULT 1,
    health character varying(500),
    health_other text,
    status_id integer,
    religion_id integer,
    updated_at date,
    city_id integer,
    health_condition_id integer,
    parent_type_id integer,
    health_insurance_id integer,
    physical_condition_id integer,
    birth_certificate_number character varying,
    distance_from_school character varying,
    remember_token character varying(255),
    jod timestamp without time zone,
    joining_status smallint DEFAULT 1,
    number character varying,
    government_number character varying,
    sid integer DEFAULT nextval('public.unique_identifier_seq'::regclass) NOT NULL,
    location character varying(50),
    health_status_id integer,
    national_id character varying,
    country_id integer,
    index character varying,
    email_valid smallint,
    payroll_status smallint DEFAULT 1 NOT NULL,
    is_hostel integer DEFAULT 0,
    tribe character varying,
    denomination character varying,
    signature character varying,
    nationality integer,
    school_phone_number character varying,
    school_id integer,
    head_teacher_name character varying,
    fcm_token character varying,
    lat double precision,
    lng double precision,
    qr_code text,
    login_code character varying,
    expire_at timestamp without time zone,
    plem_number character varying,
    mock_result_file character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.student OWNER TO postgres;

--
-- TOC entry 1838 (class 1259 OID 50005)
-- Name: user_userID_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft."user_userID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft."user_userID_seq" OWNER TO postgres;

--
-- TOC entry 1839 (class 1259 OID 50006)
-- Name: user; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft."user" (
    "userID" integer DEFAULT nextval('shulesoft."user_userID_seq"'::regclass) NOT NULL,
    name character varying(60) NOT NULL,
    dob date NOT NULL,
    sex character varying(10) NOT NULL,
    email character varying(40) DEFAULT 'default_user@shulesoft.com'::character varying,
    phone text,
    address text,
    jod date NOT NULL,
    photo character varying(200),
    username character varying(40) NOT NULL,
    password character varying(128) NOT NULL,
    usertype character varying NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    signature character varying,
    signature_path character varying(250),
    role_id integer,
    salary double precision,
    id_number character varying,
    default_password character varying,
    status smallint DEFAULT 1,
    status_id smallint,
    bank_account_number character varying,
    bank_name character varying,
    remember_token character varying(255),
    number character varying,
    sid integer DEFAULT nextval('public.unique_identifier_seq'::regclass) NOT NULL,
    location character varying,
    education_level_id integer,
    employment_type_id integer,
    physical_condition_id integer,
    health_status_id integer,
    health_insurance_id integer,
    religion_id integer,
    town character varying,
    national_id character varying(23),
    country_id integer,
    qualification character varying,
    email_valid smallint,
    payroll_status smallint DEFAULT 1 NOT NULL,
    fcm_token character varying,
    updated_at timestamp without time zone,
    tin character varying DEFAULT '999-999-999'::character varying,
    qr_code text,
    login_code character varying,
    expire_at timestamp without time zone,
    pf_number character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft."user" OWNER TO postgres;

--
-- TOC entry 1840 (class 1259 OID 50019)
-- Name: total_expenses; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.total_expenses AS
 SELECT a."expenseID" AS id,
    a.amount,
    a.created_at,
    a.date,
    a.payment_method,
    a.transaction_id,
    2 AS is_payment,
    a.bank_account_id,
    a."userID" AS user_id,
    'user'::text AS user_table,
    ( SELECT "user".name
           FROM shulesoft."user"
          WHERE ("user"."userID" = a."userID")) AS payer_name,
    a.note,
    a.reconciled,
    a.payment_type_id,
    r.name AS bank_name,
    b.number AS account_number,
    b.currency,
    b.name AS account_name,
    a.schema_name
   FROM ((shulesoft.expense a
     LEFT JOIN shulesoft.bank_accounts b ON (((b.id = a.bank_account_id) AND ((b.schema_name)::text = (a.schema_name)::text))))
     JOIN constant.refer_banks r ON ((r.id = b.refer_bank_id)));


ALTER VIEW shulesoft.total_expenses OWNER TO postgres;

--
-- TOC entry 1841 (class 1259 OID 50024)
-- Name: total_revenues; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.total_revenues AS
 SELECT a.id,
    a.amount,
    a.created_at,
    a.date,
    a.payment_method,
    a.transaction_id,
    a.is_payment,
    a.bank_account_id,
    a.user_id,
    a.user_table,
    a.payer_name,
    a.note,
    a.payment_type_id,
    a.reconciled,
    b.name AS bank_name,
    b.number AS account_number,
    b.currency,
    b.name AS account_name,
    b.schema_name
   FROM (( SELECT revenues.id,
            revenues.amount,
            revenues.created_at,
            revenues.date,
            revenues.payment_method,
            revenues.transaction_id,
            0 AS is_payment,
            revenues.bank_account_id,
            revenues.user_id,
            revenues.payment_type_id,
            revenues.user_table,
            revenues.payer_name,
            revenues.note,
            revenues.reconciled,
            revenues.schema_name
           FROM shulesoft.revenues
        UNION
         SELECT payments.id,
            payments.amount,
            payments.created_at,
            payments.date AS payment_date,
            ( SELECT payment_types.name
                   FROM shulesoft.payment_types
                  WHERE (payment_types.id = payments.payment_type_id)) AS payments_method,
            payments.transaction_id,
            1 AS is_payment,
            payments.bank_account_id,
            payments.student_id AS user_id,
            payments.payment_type_id,
            'student'::character varying AS user_table,
            ( SELECT student.name
                   FROM shulesoft.student
                  WHERE (student.student_id = payments.student_id)
                 LIMIT 1) AS payer_name,
            payments.payer_name,
            payments.reconciled,
            payments.schema_name
           FROM shulesoft.payments) a
     LEFT JOIN shulesoft.bank_accounts b ON (((b.id = a.bank_account_id) AND ((a.schema_name)::text = (b.schema_name)::text))));


ALTER VIEW shulesoft.total_revenues OWNER TO postgres;

--
-- TOC entry 1842 (class 1259 OID 50029)
-- Name: all_transactions; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.all_transactions AS
 SELECT total_expenses.id,
    total_expenses.amount,
    total_expenses.created_at,
    total_expenses.date,
    total_expenses.payment_method,
    total_expenses.transaction_id,
    total_expenses.is_payment,
    total_expenses.bank_account_id,
    total_expenses.payment_type_id,
    total_expenses.user_id,
    total_expenses.user_table,
    total_expenses.payer_name,
    total_expenses.note,
    total_expenses.reconciled,
    total_expenses.bank_name,
    total_expenses.account_number,
    total_expenses.currency,
    total_expenses.account_name,
    1 AS is_expense,
    total_expenses.schema_name
   FROM shulesoft.total_expenses
UNION ALL
 SELECT total_revenues.id,
    total_revenues.amount,
    total_revenues.created_at,
    total_revenues.date,
    total_revenues.payment_method,
    total_revenues.transaction_id,
    total_revenues.is_payment,
    total_revenues.bank_account_id,
    total_revenues.payment_type_id,
    total_revenues.user_id,
    total_revenues.user_table,
    total_revenues.payer_name,
    total_revenues.note,
    total_revenues.reconciled,
    total_revenues.bank_name,
    total_revenues.account_number,
    total_revenues.currency,
    total_revenues.account_name,
    0 AS is_expense,
    total_revenues.schema_name
   FROM shulesoft.total_revenues;


ALTER VIEW shulesoft.all_transactions OWNER TO postgres;

--
-- TOC entry 1843 (class 1259 OID 50034)
-- Name: allowances_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.allowances_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.allowances_id_seq OWNER TO postgres;

--
-- TOC entry 1844 (class 1259 OID 50035)
-- Name: allowances; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.allowances (
    id integer DEFAULT nextval('shulesoft.allowances_id_seq'::regclass) NOT NULL,
    name character varying,
    amount double precision,
    percent double precision,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    description text,
    is_percentage integer,
    type smallint DEFAULT 0,
    pension_included smallint DEFAULT 1,
    category smallint DEFAULT 1,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.allowances OWNER TO postgres;

--
-- TOC entry 1845 (class 1259 OID 50046)
-- Name: allowances_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.allowances_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.allowances_uid_seq OWNER TO postgres;

--
-- TOC entry 14030 (class 0 OID 0)
-- Dependencies: 1845
-- Name: allowances_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.allowances_uid_seq OWNED BY shulesoft.allowances.uid;


--
-- TOC entry 1846 (class 1259 OID 50047)
-- Name: discount_fees_installments_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.discount_fees_installments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.discount_fees_installments_id_seq OWNER TO postgres;

--
-- TOC entry 1847 (class 1259 OID 50048)
-- Name: discount_fees_installments; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.discount_fees_installments (
    id integer DEFAULT nextval('shulesoft.discount_fees_installments_id_seq'::regclass) NOT NULL,
    fees_installment_id integer,
    amount numeric,
    student_id integer,
    note text,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.discount_fees_installments OWNER TO postgres;

--
-- TOC entry 1848 (class 1259 OID 50056)
-- Name: due_amounts_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.due_amounts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.due_amounts_id_seq OWNER TO postgres;

--
-- TOC entry 1849 (class 1259 OID 50057)
-- Name: due_amounts; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.due_amounts (
    id integer DEFAULT nextval('shulesoft.due_amounts_id_seq'::regclass) NOT NULL,
    amount numeric DEFAULT 0.00 NOT NULL,
    student_id integer,
    fee_id integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.due_amounts OWNER TO postgres;

--
-- TOC entry 1850 (class 1259 OID 50066)
-- Name: fees_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.fees_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.fees_id_seq OWNER TO postgres;

--
-- TOC entry 1851 (class 1259 OID 50067)
-- Name: fees; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.fees (
    id integer DEFAULT nextval('shulesoft.fees_id_seq'::regclass) NOT NULL,
    name character varying,
    priority integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    description text,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.fees OWNER TO postgres;

--
-- TOC entry 1852 (class 1259 OID 50076)
-- Name: fees_installments_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.fees_installments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.fees_installments_id_seq OWNER TO postgres;

--
-- TOC entry 1853 (class 1259 OID 50077)
-- Name: fees_installments; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.fees_installments (
    id integer DEFAULT nextval('shulesoft.fees_installments_id_seq'::regclass) NOT NULL,
    fee_id integer NOT NULL,
    installment_id integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.fees_installments OWNER TO postgres;

--
-- TOC entry 1854 (class 1259 OID 50085)
-- Name: fees_installments_classes_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.fees_installments_classes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.fees_installments_classes_id_seq OWNER TO postgres;

--
-- TOC entry 1855 (class 1259 OID 50086)
-- Name: fees_installments_classes; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.fees_installments_classes (
    id integer DEFAULT nextval('shulesoft.fees_installments_classes_id_seq'::regclass) NOT NULL,
    fees_installment_id integer,
    class_id integer,
    amount numeric,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.fees_installments_classes OWNER TO postgres;

--
-- TOC entry 1785 (class 1259 OID 49698)
-- Name: hmembers_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.hmembers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.hmembers_id_seq OWNER TO postgres;

--
-- TOC entry 1786 (class 1259 OID 49699)
-- Name: hmembers; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.hmembers (
    id integer DEFAULT nextval('shulesoft.hmembers_id_seq'::regclass) NOT NULL,
    student_id integer NOT NULL,
    hostel_category_id integer,
    jod date,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    hostel_id integer,
    installment_id integer,
    bed_id integer,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    amount numeric DEFAULT 0.0
);


ALTER TABLE shulesoft.hmembers OWNER TO postgres;

--
-- TOC entry 1856 (class 1259 OID 50094)
-- Name: hostel_fees_installments_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.hostel_fees_installments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.hostel_fees_installments_id_seq OWNER TO postgres;

--
-- TOC entry 1857 (class 1259 OID 50095)
-- Name: hostel_fees_installments; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.hostel_fees_installments (
    id integer DEFAULT nextval('shulesoft.hostel_fees_installments_id_seq'::regclass) NOT NULL,
    hostel_id integer,
    fees_installment_id integer,
    amount numeric,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.hostel_fees_installments OWNER TO postgres;

--
-- TOC entry 1858 (class 1259 OID 50103)
-- Name: hostels_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.hostels_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.hostels_id_seq OWNER TO postgres;

--
-- TOC entry 1859 (class 1259 OID 50104)
-- Name: hostels; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.hostels (
    id integer DEFAULT nextval('shulesoft.hostels_id_seq'::regclass) NOT NULL,
    name character varying(128) NOT NULL,
    htype character varying(11) NOT NULL,
    address character varying(200),
    note text,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    user_sid integer,
    usertype character varying(100),
    beds_no integer,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.hostels OWNER TO postgres;

--
-- TOC entry 1860 (class 1259 OID 50112)
-- Name: installments_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.installments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.installments_id_seq OWNER TO postgres;

--
-- TOC entry 1861 (class 1259 OID 50113)
-- Name: installments; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.installments (
    id integer DEFAULT nextval('shulesoft.installments_id_seq'::regclass) NOT NULL,
    academic_year_id integer,
    start_date date,
    end_date date,
    name character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    installment_package_id integer DEFAULT 0
);


ALTER TABLE shulesoft.installments OWNER TO postgres;

--
-- TOC entry 1862 (class 1259 OID 50122)
-- Name: student_archive_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_archive_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.student_archive_id_seq OWNER TO postgres;

--
-- TOC entry 1863 (class 1259 OID 50123)
-- Name: student_archive; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.student_archive (
    id integer DEFAULT nextval('shulesoft.student_archive_id_seq'::regclass) NOT NULL,
    student_id bigint NOT NULL,
    academic_year_id integer NOT NULL,
    section_id integer NOT NULL,
    created_at timestamp without time zone DEFAULT ('now'::text)::timestamp(0) without time zone NOT NULL,
    updated_at timestamp without time zone,
    due_amount numeric,
    status smallint DEFAULT 1,
    status_id integer DEFAULT 0,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.student_archive OWNER TO postgres;

--
-- TOC entry 1864 (class 1259 OID 50133)
-- Name: hostel_installment_detail; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.hostel_installment_detail AS
 SELECT a.student_id,
    a.hostel_category_id,
    b.amount,
    b.fees_installment_id,
    a.schema_name
   FROM (((((shulesoft.hmembers a
     JOIN shulesoft.hostels d ON (((d.id = a.hostel_id) AND ((d.schema_name)::text = (a.schema_name)::text))))
     JOIN shulesoft.hostel_fees_installments b ON (((a.hostel_id = b.hostel_id) AND ((a.schema_name)::text = (b.schema_name)::text))))
     JOIN shulesoft.fees_installments c ON (((c.id = b.fees_installment_id) AND ((c.schema_name)::text = (b.schema_name)::text))))
     JOIN shulesoft.installments e ON (((e.id = c.installment_id) AND (a.installment_id = e.id) AND ((e.schema_name)::text = (c.schema_name)::text) AND ((a.schema_name)::text = (e.schema_name)::text))))
     JOIN shulesoft.student_archive s ON (((s.student_id = a.student_id) AND (e.academic_year_id = s.academic_year_id) AND ((s.schema_name)::text = (a.schema_name)::text) AND ((e.schema_name)::text = (s.schema_name)::text))))
  GROUP BY a.student_id, a.hostel_category_id, b.fees_installment_id, e.id, b.amount, a.schema_name;


ALTER VIEW shulesoft.hostel_installment_detail OWNER TO postgres;

--
-- TOC entry 1787 (class 1259 OID 49708)
-- Name: invoices_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.invoices_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.invoices_id_seq OWNER TO postgres;

--
-- TOC entry 1788 (class 1259 OID 49709)
-- Name: invoices_prefix_index_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.invoices_prefix_index_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.invoices_prefix_index_seq OWNER TO postgres;

--
-- TOC entry 1789 (class 1259 OID 49710)
-- Name: invoices; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.invoices (
    id integer DEFAULT nextval('shulesoft.invoices_id_seq'::regclass) NOT NULL,
    reference character varying,
    student_id integer,
    created_at date DEFAULT now(),
    sync smallint DEFAULT 0,
    return_message text,
    push_status character varying,
    date timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    academic_year_id integer,
    prefix character varying,
    due_date date,
    sid integer,
    token text,
    order_id character varying,
    qr text,
    gateway_buyer_uuid text,
    payment_gateway_url text,
    user_table character varying,
    source character varying,
    amount numeric,
    status smallint DEFAULT 0 NOT NULL,
    live_package_id integer DEFAULT 0 NOT NULL,
    type smallint DEFAULT 1,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    created_by_sid integer,
    prefix_index integer DEFAULT nextval('shulesoft.invoices_prefix_index_seq'::regclass) NOT NULL
);


ALTER TABLE shulesoft.invoices OWNER TO postgres;

--
-- TOC entry 1865 (class 1259 OID 50138)
-- Name: invoices_fees_installments_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.invoices_fees_installments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.invoices_fees_installments_id_seq OWNER TO postgres;

--
-- TOC entry 1866 (class 1259 OID 50139)
-- Name: invoices_fees_installments; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.invoices_fees_installments (
    id integer DEFAULT nextval('shulesoft.invoices_fees_installments_id_seq'::regclass) NOT NULL,
    invoice_id integer,
    fees_installment_id integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    reference character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.invoices_fees_installments OWNER TO postgres;

--
-- TOC entry 1867 (class 1259 OID 50147)
-- Name: payments_invoices_fees_installments_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.payments_invoices_fees_installments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.payments_invoices_fees_installments_id_seq OWNER TO postgres;

--
-- TOC entry 1868 (class 1259 OID 50148)
-- Name: payments_invoices_fees_installments; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.payments_invoices_fees_installments (
    id integer DEFAULT nextval('shulesoft.payments_invoices_fees_installments_id_seq'::regclass) NOT NULL,
    invoices_fees_installment_id integer,
    payment_id integer,
    amount numeric,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    date date,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    CONSTRAINT amount_check CHECK ((amount > (0)::numeric))
);


ALTER TABLE shulesoft.payments_invoices_fees_installments OWNER TO postgres;

--
-- TOC entry 1869 (class 1259 OID 50157)
-- Name: hostel_invoices_fees_installments_balance; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.hostel_invoices_fees_installments_balance AS
 SELECT COALESCE(f.amount, (0)::numeric) AS total_amount,
    COALESCE(c.total_payment_invoice_amount, (0)::numeric) AS total_payment_invoice_fee_amount,
    COALESCE(d.total_advance_invoice_fee_amount, (0)::numeric) AS total_advance_invoice_fee_amount,
    COALESCE(e.amount, (0)::numeric) AS discount_amount,
    g.student_id,
    g.date AS created_at,
    b.id,
    r.total_amount AS advance_amount,
    b.fees_installment_id,
    h.id AS installment_id,
    h.start_date,
    h.academic_year_id,
    2000 AS fee_id,
    b.invoice_id,
        CASE
            WHEN ((((f.amount - COALESCE(c.total_payment_invoice_amount, (0)::numeric)) - COALESCE(d.total_advance_invoice_fee_amount, (0)::numeric)) - COALESCE(e.amount, (0)::numeric)) > (0)::numeric) THEN 0
            ELSE 1
        END AS status,
    h.end_date,
    b.schema_name
   FROM ((((((((shulesoft.invoices_fees_installments b
     JOIN shulesoft.invoices g ON (((g.id = b.invoice_id) AND ((g.schema_name)::text = (b.schema_name)::text))))
     JOIN shulesoft.hostel_installment_detail f ON (((b.fees_installment_id = f.fees_installment_id) AND (f.student_id = g.student_id) AND ((b.schema_name)::text = (f.schema_name)::text) AND ((g.schema_name)::text = (b.schema_name)::text))))
     JOIN shulesoft.fees_installments k ON (((k.id = b.fees_installment_id) AND ((k.schema_name)::text = (b.schema_name)::text))))
     JOIN shulesoft.installments h ON (((h.id = k.installment_id) AND ((h.schema_name)::text = (k.schema_name)::text))))
     LEFT JOIN ( SELECT sum(payments_invoices_fees_installments.amount) AS total_payment_invoice_amount,
            payments_invoices_fees_installments.invoices_fees_installment_id,
            payments_invoices_fees_installments.schema_name
           FROM shulesoft.payments_invoices_fees_installments
          GROUP BY payments_invoices_fees_installments.invoices_fees_installment_id, payments_invoices_fees_installments.schema_name) c ON (((c.invoices_fees_installment_id = b.id) AND ((c.schema_name)::text = (b.schema_name)::text))))
     LEFT JOIN ( SELECT sum(advance_payments_invoices_fees_installments.amount) AS total_advance_invoice_fee_amount,
            advance_payments_invoices_fees_installments.invoices_fees_installments_id,
            advance_payments_invoices_fees_installments.schema_name
           FROM shulesoft.advance_payments_invoices_fees_installments
          GROUP BY advance_payments_invoices_fees_installments.invoices_fees_installments_id, advance_payments_invoices_fees_installments.schema_name) d ON (((d.invoices_fees_installments_id = b.id) AND ((d.schema_name)::text = (b.schema_name)::text))))
     LEFT JOIN shulesoft.discount_fees_installments e ON (((e.fees_installment_id = b.fees_installment_id) AND (g.student_id = e.student_id) AND ((e.schema_name)::text = (b.schema_name)::text) AND ((g.schema_name)::text = (e.schema_name)::text))))
     LEFT JOIN shulesoft.advance_payment_balance r ON (((r.fee_id = 2000) AND (r.student_id = g.student_id) AND ((g.schema_name)::text = (r.schema_name)::text))))
  WHERE (k.fee_id = 2000)
  ORDER BY h.start_date;


ALTER VIEW shulesoft.hostel_invoices_fees_installments_balance OWNER TO postgres;

--
-- TOC entry 1870 (class 1259 OID 50162)
-- Name: section_sectionID_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft."section_sectionID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft."section_sectionID_seq" OWNER TO postgres;

--
-- TOC entry 1871 (class 1259 OID 50163)
-- Name: section; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.section (
    "sectionID" integer DEFAULT nextval('shulesoft."section_sectionID_seq"'::regclass) NOT NULL,
    section character varying NOT NULL,
    category character varying(128) NOT NULL,
    "classesID" integer NOT NULL,
    "teacherID" integer NOT NULL,
    note text,
    extra character varying(60),
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    special_grade_name_id integer,
    target integer DEFAULT 50,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.section OWNER TO postgres;

--
-- TOC entry 1872 (class 1259 OID 50172)
-- Name: invoices_fees_installments_balance; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.invoices_fees_installments_balance AS
 SELECT COALESCE(a.amount, (0)::numeric) AS total_amount,
    COALESCE(c.total_payment_invoice_amount, (0)::numeric) AS total_payment_invoice_fee_amount,
    COALESCE(d.total_advance_invoice_fee_amount, (0)::numeric) AS total_advance_invoice_fee_amount,
    COALESCE(e.amount, (0)::numeric) AS discount_amount,
    f.student_id,
    f.date AS created_at,
    b.id,
    b.fees_installment_id,
    h.id AS installment_id,
    h.start_date,
    h.academic_year_id,
    i.id AS fee_id,
    f.id AS invoice_id,
        CASE
            WHEN ((((a.amount - COALESCE(c.total_payment_invoice_amount, (0)::numeric)) - COALESCE(d.total_advance_invoice_fee_amount, (0)::numeric)) - COALESCE(e.amount, (0)::numeric)) > (0)::numeric) THEN 0
            ELSE 1
        END AS status,
    h.end_date,
    a.schema_name
   FROM (((((((((shulesoft.fees_installments_classes a
     JOIN shulesoft.invoices_fees_installments b ON (((b.fees_installment_id = a.fees_installment_id) AND ((b.schema_name)::text = (a.schema_name)::text))))
     JOIN shulesoft.invoices f ON (((f.id = b.invoice_id) AND ((b.schema_name)::text = (f.schema_name)::text))))
     JOIN shulesoft.fees_installments g ON (((g.id = a.fees_installment_id) AND ((g.schema_name)::text = (a.schema_name)::text))))
     JOIN shulesoft.installments h ON (((h.id = g.installment_id) AND ((h.schema_name)::text = (g.schema_name)::text))))
     JOIN shulesoft.fees i ON (((i.id = g.fee_id) AND ((i.schema_name)::text = (g.schema_name)::text))))
     JOIN shulesoft.student_archive s ON (((s.student_id = f.student_id) AND ((s.schema_name)::text = (f.schema_name)::text) AND (s.section_id IN ( SELECT section."sectionID"
           FROM shulesoft.section
          WHERE ((section."classesID" = a.class_id) AND ((section.schema_name)::text = (a.schema_name)::text)))) AND (h.academic_year_id = s.academic_year_id) AND ((h.schema_name)::text = (s.schema_name)::text))))
     LEFT JOIN ( SELECT sum(payments_invoices_fees_installments.amount) AS total_payment_invoice_amount,
            payments_invoices_fees_installments.invoices_fees_installment_id,
            payments_invoices_fees_installments.schema_name
           FROM shulesoft.payments_invoices_fees_installments
          WHERE (payments_invoices_fees_installments.payment_id IN ( SELECT payments.id
                   FROM shulesoft.payments))
          GROUP BY payments_invoices_fees_installments.invoices_fees_installment_id, payments_invoices_fees_installments.schema_name) c ON (((c.invoices_fees_installment_id = b.id) AND ((b.schema_name)::text = (c.schema_name)::text))))
     LEFT JOIN ( SELECT sum(advance_payments_invoices_fees_installments.amount) AS total_advance_invoice_fee_amount,
            advance_payments_invoices_fees_installments.invoices_fees_installments_id,
            advance_payments_invoices_fees_installments.schema_name
           FROM shulesoft.advance_payments_invoices_fees_installments
          GROUP BY advance_payments_invoices_fees_installments.invoices_fees_installments_id, advance_payments_invoices_fees_installments.schema_name) d ON (((d.invoices_fees_installments_id = b.id) AND ((d.schema_name)::text = (b.schema_name)::text))))
     LEFT JOIN shulesoft.discount_fees_installments e ON (((e.fees_installment_id = a.fees_installment_id) AND (f.student_id = e.student_id) AND ((e.schema_name)::text = (a.schema_name)::text) AND ((f.schema_name)::text = (e.schema_name)::text))))
  ORDER BY h.start_date, i.priority;


ALTER VIEW shulesoft.invoices_fees_installments_balance OWNER TO postgres;

--
-- TOC entry 1816 (class 1259 OID 49919)
-- Name: tmembers_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.tmembers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.tmembers_id_seq OWNER TO postgres;

--
-- TOC entry 1817 (class 1259 OID 49920)
-- Name: tmembers; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.tmembers (
    id integer DEFAULT nextval('shulesoft.tmembers_id_seq'::regclass) NOT NULL,
    student_id integer NOT NULL,
    transport_route_id integer NOT NULL,
    tjoindate date,
    vehicle_id integer,
    is_oneway integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    installment_id integer,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    amount numeric DEFAULT 0.00
);


ALTER TABLE shulesoft.tmembers OWNER TO postgres;

--
-- TOC entry 1873 (class 1259 OID 50177)
-- Name: transport_routes_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.transport_routes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.transport_routes_id_seq OWNER TO postgres;

--
-- TOC entry 1874 (class 1259 OID 50178)
-- Name: transport_routes; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.transport_routes (
    id integer DEFAULT nextval('shulesoft.transport_routes_id_seq'::regclass) NOT NULL,
    name character varying,
    note text,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.transport_routes OWNER TO postgres;

--
-- TOC entry 1875 (class 1259 OID 50186)
-- Name: transport_routes_fees_installments_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.transport_routes_fees_installments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.transport_routes_fees_installments_id_seq OWNER TO postgres;

--
-- TOC entry 1876 (class 1259 OID 50187)
-- Name: transport_routes_fees_installments; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.transport_routes_fees_installments (
    id integer DEFAULT nextval('shulesoft.transport_routes_fees_installments_id_seq'::regclass) NOT NULL,
    transport_route_id integer,
    fees_installment_id integer,
    amount numeric,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.transport_routes_fees_installments OWNER TO postgres;

--
-- TOC entry 1877 (class 1259 OID 50195)
-- Name: transport_installment_detail; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.transport_installment_detail AS
 SELECT a.student_id,
    a.vehicle_id,
    a.is_oneway,
        CASE
            WHEN (a.is_oneway = 0) THEN b.amount
            ELSE (b.amount * 0.5::numeric(10,2))
        END AS amount,
    b.fees_installment_id,
    a.schema_name
   FROM (((((shulesoft.tmembers a
     JOIN shulesoft.transport_routes d ON (((d.id = a.transport_route_id) AND ((d.schema_name)::text = (a.schema_name)::text))))
     JOIN shulesoft.transport_routes_fees_installments b ON (((a.transport_route_id = b.transport_route_id) AND ((a.schema_name)::text = (b.schema_name)::text))))
     JOIN shulesoft.fees_installments c ON (((c.id = b.fees_installment_id) AND ((c.schema_name)::text = (b.schema_name)::text))))
     JOIN shulesoft.installments e ON (((e.id = c.installment_id) AND (a.installment_id = e.id) AND ((e.schema_name)::text = (c.schema_name)::text) AND ((a.schema_name)::text = (e.schema_name)::text))))
     JOIN shulesoft.student_archive s ON (((s.student_id = a.student_id) AND (e.academic_year_id = s.academic_year_id) AND ((s.schema_name)::text = (a.schema_name)::text) AND ((e.schema_name)::text = (s.schema_name)::text))))
  GROUP BY a.student_id, a.vehicle_id, a.is_oneway, b.amount, b.fees_installment_id, e.id, a.schema_name;


ALTER VIEW shulesoft.transport_installment_detail OWNER TO postgres;

--
-- TOC entry 1878 (class 1259 OID 50200)
-- Name: transport_invoices_fees_installments_balance; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.transport_invoices_fees_installments_balance AS
 SELECT COALESCE(f.amount, (0)::numeric) AS total_amount,
    COALESCE(c.total_payment_invoice_amount, (0)::numeric) AS total_payment_invoice_fee_amount,
    COALESCE(d.total_advance_invoice_fee_amount, (0)::numeric) AS total_advance_invoice_fee_amount,
    COALESCE(e.amount, (0)::numeric) AS discount_amount,
    g.student_id,
    g.date AS created_at,
    b.id,
    r.total_amount AS advance_amount,
    b.fees_installment_id,
    h.id AS installment_id,
    h.start_date,
    h.academic_year_id,
    1000 AS fee_id,
    b.invoice_id,
        CASE
            WHEN ((((f.amount - COALESCE(c.total_payment_invoice_amount, (0)::numeric)) - COALESCE(d.total_advance_invoice_fee_amount, (0)::numeric)) - COALESCE(e.amount, (0)::numeric)) > (0)::numeric) THEN 0
            ELSE 1
        END AS status,
    h.end_date,
    b.schema_name
   FROM ((((((((shulesoft.invoices_fees_installments b
     JOIN shulesoft.invoices g ON (((g.id = b.invoice_id) AND ((g.schema_name)::text = (b.schema_name)::text))))
     JOIN shulesoft.transport_installment_detail f ON (((b.fees_installment_id = f.fees_installment_id) AND (f.student_id = g.student_id) AND ((b.schema_name)::text = (f.schema_name)::text) AND ((g.schema_name)::text = (f.schema_name)::text))))
     JOIN shulesoft.fees_installments k ON (((k.id = b.fees_installment_id) AND ((k.schema_name)::text = (b.schema_name)::text))))
     JOIN shulesoft.installments h ON (((h.id = k.installment_id) AND ((h.schema_name)::text = (k.schema_name)::text))))
     LEFT JOIN ( SELECT sum(payments_invoices_fees_installments.amount) AS total_payment_invoice_amount,
            payments_invoices_fees_installments.invoices_fees_installment_id,
            payments_invoices_fees_installments.schema_name
           FROM shulesoft.payments_invoices_fees_installments
          GROUP BY payments_invoices_fees_installments.invoices_fees_installment_id, payments_invoices_fees_installments.schema_name) c ON (((c.invoices_fees_installment_id = b.id) AND ((c.schema_name)::text = (b.schema_name)::text))))
     LEFT JOIN ( SELECT sum(advance_payments_invoices_fees_installments.amount) AS total_advance_invoice_fee_amount,
            advance_payments_invoices_fees_installments.invoices_fees_installments_id,
            advance_payments_invoices_fees_installments.schema_name
           FROM shulesoft.advance_payments_invoices_fees_installments
          GROUP BY advance_payments_invoices_fees_installments.invoices_fees_installments_id, advance_payments_invoices_fees_installments.schema_name) d ON (((d.invoices_fees_installments_id = b.id) AND ((d.schema_name)::text = (b.schema_name)::text))))
     LEFT JOIN shulesoft.discount_fees_installments e ON (((e.fees_installment_id = b.fees_installment_id) AND (g.student_id = e.student_id) AND ((e.schema_name)::text = (b.schema_name)::text) AND ((g.schema_name)::text = (e.schema_name)::text))))
     LEFT JOIN shulesoft.advance_payment_balance r ON (((r.fee_id = 1000) AND (r.student_id = g.student_id) AND ((r.schema_name)::text = (g.schema_name)::text))))
  WHERE (k.fee_id = 1000)
  ORDER BY h.start_date;


ALTER VIEW shulesoft.transport_invoices_fees_installments_balance OWNER TO postgres;

--
-- TOC entry 1879 (class 1259 OID 50205)
-- Name: invoice_balances; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.invoice_balances AS
 SELECT transport_invoices_fees_installments_balance.student_id,
    transport_invoices_fees_installments_balance.created_at,
    transport_invoices_fees_installments_balance.total_amount,
    transport_invoices_fees_installments_balance.total_payment_invoice_fee_amount,
    transport_invoices_fees_installments_balance.total_advance_invoice_fee_amount,
    transport_invoices_fees_installments_balance.discount_amount,
    transport_invoices_fees_installments_balance.fees_installment_id,
    transport_invoices_fees_installments_balance.installment_id,
    transport_invoices_fees_installments_balance.start_date,
    transport_invoices_fees_installments_balance.academic_year_id,
    transport_invoices_fees_installments_balance.fee_id,
    transport_invoices_fees_installments_balance.id,
    transport_invoices_fees_installments_balance.invoice_id,
    (((COALESCE(transport_invoices_fees_installments_balance.total_amount, (0)::numeric) - COALESCE(transport_invoices_fees_installments_balance.total_payment_invoice_fee_amount, (0)::numeric)) - COALESCE(transport_invoices_fees_installments_balance.total_advance_invoice_fee_amount, (0)::numeric)) - COALESCE(transport_invoices_fees_installments_balance.discount_amount, (0)::numeric)) AS balance,
    transport_invoices_fees_installments_balance.end_date,
    transport_invoices_fees_installments_balance.schema_name
   FROM shulesoft.transport_invoices_fees_installments_balance
UNION ALL
 SELECT invoices_fees_installments_balance.student_id,
    invoices_fees_installments_balance.created_at,
    invoices_fees_installments_balance.total_amount,
    invoices_fees_installments_balance.total_payment_invoice_fee_amount,
    invoices_fees_installments_balance.total_advance_invoice_fee_amount,
    invoices_fees_installments_balance.discount_amount,
    invoices_fees_installments_balance.fees_installment_id,
    invoices_fees_installments_balance.installment_id,
    invoices_fees_installments_balance.start_date,
    invoices_fees_installments_balance.academic_year_id,
    invoices_fees_installments_balance.fee_id,
    invoices_fees_installments_balance.id,
    invoices_fees_installments_balance.invoice_id,
    (((COALESCE(invoices_fees_installments_balance.total_amount, (0)::numeric) - COALESCE(invoices_fees_installments_balance.total_payment_invoice_fee_amount, (0)::numeric)) - COALESCE(invoices_fees_installments_balance.total_advance_invoice_fee_amount, (0)::numeric)) - COALESCE(invoices_fees_installments_balance.discount_amount, (0)::numeric)) AS balance,
    invoices_fees_installments_balance.end_date,
    invoices_fees_installments_balance.schema_name
   FROM shulesoft.invoices_fees_installments_balance
UNION ALL
 SELECT hostel_invoices_fees_installments_balance.student_id,
    hostel_invoices_fees_installments_balance.created_at,
    hostel_invoices_fees_installments_balance.total_amount,
    hostel_invoices_fees_installments_balance.total_payment_invoice_fee_amount,
    hostel_invoices_fees_installments_balance.total_advance_invoice_fee_amount,
    hostel_invoices_fees_installments_balance.discount_amount,
    hostel_invoices_fees_installments_balance.fees_installment_id,
    hostel_invoices_fees_installments_balance.installment_id,
    hostel_invoices_fees_installments_balance.start_date,
    hostel_invoices_fees_installments_balance.academic_year_id,
    hostel_invoices_fees_installments_balance.fee_id,
    hostel_invoices_fees_installments_balance.id,
    hostel_invoices_fees_installments_balance.invoice_id,
    (((COALESCE(hostel_invoices_fees_installments_balance.total_amount, (0)::numeric) - COALESCE(hostel_invoices_fees_installments_balance.total_payment_invoice_fee_amount, (0)::numeric)) - COALESCE(hostel_invoices_fees_installments_balance.total_advance_invoice_fee_amount, (0)::numeric)) - COALESCE(hostel_invoices_fees_installments_balance.discount_amount, (0)::numeric)) AS balance,
    hostel_invoices_fees_installments_balance.end_date,
    hostel_invoices_fees_installments_balance.schema_name
   FROM shulesoft.hostel_invoices_fees_installments_balance;


ALTER VIEW shulesoft.invoice_balances OWNER TO postgres;

--
-- TOC entry 1880 (class 1259 OID 50210)
-- Name: amount_receivable; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.amount_receivable AS
 SELECT sum((a.total_amount - a.discount_amount)) AS total_amount,
    b.name,
    b.student_id,
    (a.created_at)::date AS created_at,
    a.schema_name
   FROM (shulesoft.invoice_balances a
     JOIN shulesoft.student b ON (((b.student_id = a.student_id) AND ((a.schema_name)::text = (b.schema_name)::text))))
  GROUP BY b.name, b.student_id, a.created_at, a.schema_name
UNION ALL
 SELECT sum(a.amount) AS total_amount,
    b.name,
    b.student_id,
    (a.created_at)::date AS created_at,
    a.schema_name
   FROM (shulesoft.due_amounts a
     JOIN shulesoft.student b ON (((b.student_id = a.student_id) AND ((a.schema_name)::text = (b.schema_name)::text))))
  GROUP BY b.name, b.student_id, a.created_at, a.schema_name
UNION ALL
 SELECT sum(((0)::numeric - a.amount)) AS total_amount,
    b.name,
    b.student_id,
    a.date AS created_at,
    a.schema_name
   FROM (shulesoft.payments a
     JOIN shulesoft.student b ON (((b.student_id = a.student_id) AND ((a.schema_name)::text = (b.schema_name)::text))))
  GROUP BY b.name, b.student_id, a.date, a.schema_name
UNION ALL
 SELECT sum(((0)::numeric - a.amount)) AS total_amount,
    b.name,
    b.student_id,
    (a.created_at)::date AS created_at,
    a.schema_name
   FROM (shulesoft.advance_payments a
     JOIN shulesoft.student b ON (((b.student_id = a.student_id) AND ((a.schema_name)::text = (b.schema_name)::text) AND (a.payment_id IS NULL))))
  GROUP BY b.name, b.student_id, a.created_at, a.schema_name;


ALTER VIEW shulesoft.amount_receivable OWNER TO postgres;

--
-- TOC entry 1881 (class 1259 OID 50215)
-- Name: amount_receivable_per_date; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.amount_receivable_per_date AS
 SELECT sum((a.total_amount - a.discount_amount)) AS total_amount,
    b.name,
    b.student_id,
    (a.created_at)::date AS created_at
   FROM (shulesoft.invoice_balances a
     JOIN shulesoft.student b ON ((b.student_id = a.student_id)))
  GROUP BY b.name, b.student_id, a.created_at
UNION ALL
 SELECT sum(a.amount) AS total_amount,
    b.name,
    b.student_id,
    (a.created_at)::date AS created_at
   FROM (shulesoft.due_amounts a
     JOIN shulesoft.student b ON ((b.student_id = a.student_id)))
  GROUP BY b.name, b.student_id, a.created_at
UNION ALL
 SELECT sum(((0)::numeric - a.amount)) AS total_amount,
    b.name,
    b.student_id,
    (a.created_at)::date AS created_at
   FROM (shulesoft.payments a
     JOIN shulesoft.student b ON ((b.student_id = a.student_id)))
  GROUP BY b.name, b.student_id, a.created_at
UNION ALL
 SELECT sum(((0)::numeric - a.amount)) AS total_amount,
    b.name,
    b.student_id,
    (a.created_at)::date AS created_at
   FROM (shulesoft.advance_payments a
     JOIN shulesoft.student b ON (((b.student_id = a.student_id) AND (a.payment_id IS NULL))))
  GROUP BY b.name, b.student_id, a.created_at;


ALTER VIEW shulesoft.amount_receivable_per_date OWNER TO postgres;

--
-- TOC entry 1882 (class 1259 OID 50220)
-- Name: application_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.application_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.application_id_seq OWNER TO postgres;

--
-- TOC entry 1883 (class 1259 OID 50221)
-- Name: application; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.application (
    id integer DEFAULT nextval('shulesoft.application_id_seq'::regclass) NOT NULL,
    student_id integer,
    apply_for character varying,
    created_at timestamp without time zone DEFAULT now(),
    created_by integer,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.application OWNER TO postgres;

--
-- TOC entry 1884 (class 1259 OID 50229)
-- Name: application_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.application_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.application_uid_seq OWNER TO postgres;

--
-- TOC entry 14031 (class 0 OID 0)
-- Dependencies: 1884
-- Name: application_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.application_uid_seq OWNED BY shulesoft.application.uid;


--
-- TOC entry 1885 (class 1259 OID 50230)
-- Name: appointments_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.appointments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.appointments_id_seq OWNER TO postgres;

--
-- TOC entry 1886 (class 1259 OID 50231)
-- Name: appointments; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.appointments (
    id integer DEFAULT nextval('shulesoft.appointments_id_seq'::regclass) NOT NULL,
    date date,
    "time" time without time zone,
    to_user_id integer,
    to_table character varying,
    from_user_id integer,
    from_table character varying,
    message text,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    status smallint,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.appointments OWNER TO postgres;

--
-- TOC entry 1887 (class 1259 OID 50239)
-- Name: appointments_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.appointments_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.appointments_uid_seq OWNER TO postgres;

--
-- TOC entry 14032 (class 0 OID 0)
-- Dependencies: 1887
-- Name: appointments_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.appointments_uid_seq OWNED BY shulesoft.appointments.uid;


--
-- TOC entry 1888 (class 1259 OID 50245)
-- Name: assignment_downloads_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.assignment_downloads_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.assignment_downloads_id_seq OWNER TO postgres;

--
-- TOC entry 1889 (class 1259 OID 50246)
-- Name: assignment_downloads; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.assignment_downloads (
    id integer DEFAULT nextval('shulesoft.assignment_downloads_id_seq'::regclass) NOT NULL,
    assignment_id integer,
    counter integer,
    created_by integer,
    created_by_table character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.assignment_downloads OWNER TO postgres;

--
-- TOC entry 1890 (class 1259 OID 50254)
-- Name: assignment_downloads_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.assignment_downloads_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.assignment_downloads_uid_seq OWNER TO postgres;

--
-- TOC entry 14033 (class 0 OID 0)
-- Dependencies: 1890
-- Name: assignment_downloads_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.assignment_downloads_uid_seq OWNED BY shulesoft.assignment_downloads.uid;


--
-- TOC entry 1891 (class 1259 OID 50255)
-- Name: assignment_files_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.assignment_files_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.assignment_files_id_seq OWNER TO postgres;

--
-- TOC entry 1892 (class 1259 OID 50256)
-- Name: assignment_files; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.assignment_files (
    id integer DEFAULT nextval('shulesoft.assignment_files_id_seq'::regclass) NOT NULL,
    assignment_id integer,
    attach text,
    attach_file_name text,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    status integer DEFAULT 1,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.assignment_files OWNER TO postgres;

--
-- TOC entry 1893 (class 1259 OID 50265)
-- Name: assignment_files_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.assignment_files_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.assignment_files_uid_seq OWNER TO postgres;

--
-- TOC entry 14034 (class 0 OID 0)
-- Dependencies: 1893
-- Name: assignment_files_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.assignment_files_uid_seq OWNED BY shulesoft.assignment_files.uid;


--
-- TOC entry 1894 (class 1259 OID 50266)
-- Name: assignment_viewers_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.assignment_viewers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.assignment_viewers_id_seq OWNER TO postgres;

--
-- TOC entry 1895 (class 1259 OID 50267)
-- Name: assignment_viewers; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.assignment_viewers (
    id integer DEFAULT nextval('shulesoft.assignment_viewers_id_seq'::regclass) NOT NULL,
    assignment_id integer,
    created_by integer,
    created_by_table character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.assignment_viewers OWNER TO postgres;

--
-- TOC entry 1896 (class 1259 OID 50275)
-- Name: assignment_viewers_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.assignment_viewers_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.assignment_viewers_uid_seq OWNER TO postgres;

--
-- TOC entry 14035 (class 0 OID 0)
-- Dependencies: 1896
-- Name: assignment_viewers_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.assignment_viewers_uid_seq OWNED BY shulesoft.assignment_viewers.uid;


--
-- TOC entry 1897 (class 1259 OID 50276)
-- Name: assignments_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.assignments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.assignments_id_seq OWNER TO postgres;

--
-- TOC entry 1898 (class 1259 OID 50277)
-- Name: assignments; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.assignments (
    id integer DEFAULT nextval('shulesoft.assignments_id_seq'::regclass) NOT NULL,
    subject_id integer,
    note text,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    title character varying NOT NULL,
    attach text,
    attach_file_name text,
    due_date timestamp without time zone,
    exam_group_id integer,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.assignments OWNER TO postgres;

--
-- TOC entry 1899 (class 1259 OID 50285)
-- Name: assignments_submitted_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.assignments_submitted_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.assignments_submitted_id_seq OWNER TO postgres;

--
-- TOC entry 1900 (class 1259 OID 50286)
-- Name: assignments_submitted; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.assignments_submitted (
    id integer DEFAULT nextval('shulesoft.assignments_submitted_id_seq'::regclass) NOT NULL,
    assignment_id integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    student_id integer,
    score_mark double precision,
    attach text,
    attach_file_name text,
    note text,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.assignments_submitted OWNER TO postgres;

--
-- TOC entry 1901 (class 1259 OID 50294)
-- Name: assignments_submitted_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.assignments_submitted_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.assignments_submitted_uid_seq OWNER TO postgres;

--
-- TOC entry 14036 (class 0 OID 0)
-- Dependencies: 1901
-- Name: assignments_submitted_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.assignments_submitted_uid_seq OWNED BY shulesoft.assignments_submitted.uid;


--
-- TOC entry 1902 (class 1259 OID 50295)
-- Name: assignments_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.assignments_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.assignments_uid_seq OWNER TO postgres;

--
-- TOC entry 14037 (class 0 OID 0)
-- Dependencies: 1902
-- Name: assignments_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.assignments_uid_seq OWNED BY shulesoft.assignments.uid;


--
-- TOC entry 1903 (class 1259 OID 50296)
-- Name: attendance_attendanceID_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft."attendance_attendanceID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft."attendance_attendanceID_seq" OWNER TO postgres;

--
-- TOC entry 1904 (class 1259 OID 50297)
-- Name: attendance; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.attendance (
    "attendanceID" integer DEFAULT nextval('shulesoft."attendance_attendanceID_seq"'::regclass) NOT NULL,
    student_id integer NOT NULL,
    "classesID" integer NOT NULL,
    "userID" integer,
    usertype character varying(20) NOT NULL,
    monthyear character varying(10) NOT NULL,
    a1 character varying(3),
    a2 character varying(3),
    a3 character varying(3),
    a4 character varying(3),
    a5 character varying(3),
    a6 character varying(3),
    a7 character varying(3),
    a8 character varying(3),
    a9 character varying(3),
    a10 character varying(3),
    a11 character varying(3),
    a12 character varying(3),
    a13 character varying(3),
    a14 character varying(3),
    a15 character varying(3),
    a16 character varying(3),
    a17 character varying(3),
    a18 character varying(3),
    a19 character varying(3),
    a20 character varying(3),
    a21 character varying(3),
    a22 character varying(3),
    a23 character varying(3),
    a24 character varying(3),
    a25 character varying(3),
    a26 character varying(3),
    a27 character varying(3),
    a28 character varying(3),
    a29 character varying(3),
    a30 character varying(3),
    a31 character varying(3),
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.attendance OWNER TO postgres;

--
-- TOC entry 1905 (class 1259 OID 50305)
-- Name: attendance_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.attendance_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.attendance_uid_seq OWNER TO postgres;

--
-- TOC entry 14038 (class 0 OID 0)
-- Dependencies: 1905
-- Name: attendance_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.attendance_uid_seq OWNED BY shulesoft.attendance.uid;


--
-- TOC entry 1906 (class 1259 OID 50306)
-- Name: bank_accounts_fees_classes_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.bank_accounts_fees_classes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.bank_accounts_fees_classes_id_seq OWNER TO postgres;

--
-- TOC entry 1907 (class 1259 OID 50307)
-- Name: bank_accounts_fees_classes; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.bank_accounts_fees_classes (
    id integer DEFAULT nextval('shulesoft.bank_accounts_fees_classes_id_seq'::regclass) NOT NULL,
    bank_account_id integer,
    fees_classes_id integer,
    updated_at timestamp without time zone,
    created_at timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.bank_accounts_fees_classes OWNER TO postgres;

--
-- TOC entry 1908 (class 1259 OID 50315)
-- Name: bank_accounts_fees_classes_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.bank_accounts_fees_classes_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.bank_accounts_fees_classes_uid_seq OWNER TO postgres;

--
-- TOC entry 14039 (class 0 OID 0)
-- Dependencies: 1908
-- Name: bank_accounts_fees_classes_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.bank_accounts_fees_classes_uid_seq OWNED BY shulesoft.bank_accounts_fees_classes.uid;


--
-- TOC entry 1771 (class 1259 OID 49623)
-- Name: bank_accounts_integrations_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.bank_accounts_integrations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.bank_accounts_integrations_id_seq OWNER TO postgres;

--
-- TOC entry 1772 (class 1259 OID 49624)
-- Name: bank_accounts_integrations; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.bank_accounts_integrations (
    id integer DEFAULT nextval('shulesoft.bank_accounts_integrations_id_seq'::regclass) NOT NULL,
    bank_account_id integer,
    api_username character varying,
    api_password character varying,
    invoice_prefix character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    sandbox_api_username character varying,
    sandbox_api_password character varying,
    payment_type text DEFAULT nextval('constant.payment_type_seq'::regclass),
    payment_type_id smallint DEFAULT nextval('constant.payment_type_seq'::regclass),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.bank_accounts_integrations OWNER TO postgres;

--
-- TOC entry 1909 (class 1259 OID 50316)
-- Name: bank_accounts_integrations_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.bank_accounts_integrations_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.bank_accounts_integrations_uid_seq OWNER TO postgres;

--
-- TOC entry 14040 (class 0 OID 0)
-- Dependencies: 1909
-- Name: bank_accounts_integrations_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.bank_accounts_integrations_uid_seq OWNED BY shulesoft.bank_accounts_integrations.uid;


--
-- TOC entry 1910 (class 1259 OID 50317)
-- Name: bank_accounts_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.bank_accounts_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.bank_accounts_uid_seq OWNER TO postgres;

--
-- TOC entry 14041 (class 0 OID 0)
-- Dependencies: 1910
-- Name: bank_accounts_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.bank_accounts_uid_seq OWNED BY shulesoft.bank_accounts.uid;


--
-- TOC entry 1911 (class 1259 OID 50318)
-- Name: refer_expense_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.refer_expense_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.refer_expense_id_seq OWNER TO postgres;

--
-- TOC entry 1912 (class 1259 OID 50319)
-- Name: refer_expense; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.refer_expense (
    id integer DEFAULT nextval('shulesoft.refer_expense_id_seq'::regclass) NOT NULL,
    name character varying(500),
    create_date timestamp without time zone DEFAULT now(),
    financial_category_id integer,
    note text,
    status integer DEFAULT 0 NOT NULL,
    code character varying,
    date date,
    open_balance numeric DEFAULT 0,
    account_group_id integer NOT NULL,
    updated_at timestamp without time zone,
    created_at timestamp without time zone DEFAULT now(),
    predefined bigint DEFAULT 0,
    depreciation numeric,
    chart_no integer,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    source_table character varying,
    source_id integer,
    nature smallint
);


ALTER TABLE shulesoft.refer_expense OWNER TO postgres;

--
-- TOC entry 14042 (class 0 OID 0)
-- Dependencies: 1912
-- Name: COLUMN refer_expense.source_table; Type: COMMENT; Schema: shulesoft; Owner: postgres
--

COMMENT ON COLUMN shulesoft.refer_expense.source_table IS 'Table which this chart originate, ';


--
-- TOC entry 14043 (class 0 OID 0)
-- Dependencies: 1912
-- Name: COLUMN refer_expense.source_id; Type: COMMENT; Schema: shulesoft; Owner: postgres
--

COMMENT ON COLUMN shulesoft.refer_expense.source_id IS 'Unique primary id of that table from source_table';


--
-- TOC entry 1913 (class 1259 OID 50331)
-- Name: bank_transactions; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.bank_transactions AS
 SELECT payments.bank_account_id,
    payments.payment_type_id,
    payments.amount,
    payments.note,
    payments.date,
    payments.transaction_id,
    student.name AS recipient
   FROM (shulesoft.payments
     JOIN shulesoft.student ON ((student.student_id = payments.student_id)))
UNION ALL
 SELECT d.bank_account_id,
    d.payment_type_id,
    d.amount,
    d.note,
    d.date,
    d.transaction_id,
    d.recipient
   FROM ( SELECT a.bank_account_id,
            a.date,
            a.transaction_id,
            a.expense AS note,
            a.payment_type_id,
            a.recipient,
            sum(
                CASE
                    WHEN (b.financial_category_id = ANY (ARRAY[2, 3, 4])) THEN ((0)::numeric - COALESCE(a.amount, (0)::numeric))
                    ELSE a.amount
                END) AS amount
           FROM (shulesoft.expense a
             JOIN shulesoft.refer_expense b ON ((b.id = a.refer_expense_id)))
          GROUP BY a.bank_account_id, a.date, a.expense, a.payment_type_id, a.transaction_id, a.recipient) d
UNION ALL
 SELECT revenues.bank_account_id,
    revenues.payment_type_id,
    revenues.amount,
    revenues.note,
    revenues.date,
    revenues.transaction_id,
    revenues.payer_name AS recipient
   FROM shulesoft.revenues;


ALTER VIEW shulesoft.bank_transactions OWNER TO postgres;

--
-- TOC entry 1914 (class 1259 OID 50336)
-- Name: book_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.book_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.book_id_seq OWNER TO postgres;

--
-- TOC entry 1915 (class 1259 OID 50337)
-- Name: book; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.book (
    id integer DEFAULT nextval('shulesoft.book_id_seq'::regclass) NOT NULL,
    name character varying NOT NULL,
    author character varying NOT NULL,
    rack text NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    edition character varying,
    user_id integer,
    serial_no character varying(50),
    subject_code character varying(5),
    subject_id integer,
    "classesID" integer,
    whois character(1) DEFAULT 1 NOT NULL,
    quantity integer,
    book_for character varying,
    due_quantity integer,
    updated_at timestamp with time zone,
    book_classfication_id integer,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.book OWNER TO postgres;

--
-- TOC entry 1916 (class 1259 OID 50346)
-- Name: book_class_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.book_class_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.book_class_id_seq OWNER TO postgres;

--
-- TOC entry 1917 (class 1259 OID 50347)
-- Name: book_class; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.book_class (
    id integer DEFAULT nextval('shulesoft.book_class_id_seq'::regclass) NOT NULL,
    classes_id integer,
    created_at timestamp without time zone DEFAULT now(),
    book_id integer NOT NULL,
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.book_class OWNER TO postgres;

--
-- TOC entry 1918 (class 1259 OID 50355)
-- Name: book_class_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.book_class_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.book_class_uid_seq OWNER TO postgres;

--
-- TOC entry 14044 (class 0 OID 0)
-- Dependencies: 1918
-- Name: book_class_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.book_class_uid_seq OWNED BY shulesoft.book_class.uid;


--
-- TOC entry 1919 (class 1259 OID 50356)
-- Name: book_quantity_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.book_quantity_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.book_quantity_id_seq OWNER TO postgres;

--
-- TOC entry 1920 (class 1259 OID 50357)
-- Name: book_quantity; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.book_quantity (
    id integer DEFAULT nextval('shulesoft.book_quantity_id_seq'::regclass) NOT NULL,
    book_id integer,
    status integer DEFAULT 1,
    book_condition integer DEFAULT 1 NOT NULL,
    "bID" integer,
    updated_at timestamp without time zone,
    created_at timestamp without time zone,
    essential_number character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.book_quantity OWNER TO postgres;

--
-- TOC entry 1921 (class 1259 OID 50366)
-- Name: book_quantity_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.book_quantity_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.book_quantity_uid_seq OWNER TO postgres;

--
-- TOC entry 14045 (class 0 OID 0)
-- Dependencies: 1921
-- Name: book_quantity_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.book_quantity_uid_seq OWNED BY shulesoft.book_quantity.uid;


--
-- TOC entry 1922 (class 1259 OID 50367)
-- Name: book_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.book_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.book_uid_seq OWNER TO postgres;

--
-- TOC entry 14046 (class 0 OID 0)
-- Dependencies: 1922
-- Name: book_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.book_uid_seq OWNED BY shulesoft.book.uid;


--
-- TOC entry 1923 (class 1259 OID 50368)
-- Name: budget_item_period_amounts; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.budget_item_period_amounts (
    id integer NOT NULL,
    budget_item_id bigint NOT NULL,
    budget_period_group_id bigint NOT NULL,
    schema_name character varying NOT NULL,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    amount numeric NOT NULL
);


ALTER TABLE shulesoft.budget_item_period_amounts OWNER TO postgres;

--
-- TOC entry 1924 (class 1259 OID 50374)
-- Name: budget_item_period_amounts_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.budget_item_period_amounts_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.budget_item_period_amounts_id_seq OWNER TO postgres;

--
-- TOC entry 14047 (class 0 OID 0)
-- Dependencies: 1924
-- Name: budget_item_period_amounts_id_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.budget_item_period_amounts_id_seq OWNED BY shulesoft.budget_item_period_amounts.id;


--
-- TOC entry 1925 (class 1259 OID 50375)
-- Name: budget_items; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.budget_items (
    id integer NOT NULL,
    refer_expense_id integer NOT NULL,
    manage_budget_id bigint NOT NULL,
    financial_category_id integer NOT NULL,
    schema_name character varying NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4()
);


ALTER TABLE shulesoft.budget_items OWNER TO postgres;

--
-- TOC entry 1926 (class 1259 OID 50382)
-- Name: budget_items_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.budget_items_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.budget_items_id_seq OWNER TO postgres;

--
-- TOC entry 14048 (class 0 OID 0)
-- Dependencies: 1926
-- Name: budget_items_id_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.budget_items_id_seq OWNED BY shulesoft.budget_items.id;


--
-- TOC entry 1927 (class 1259 OID 50383)
-- Name: budgets; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.budgets (
    id integer NOT NULL,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    refer_expense_id integer,
    batch_id character varying,
    budget_from date,
    budget_to date,
    quantity integer,
    amount numeric,
    requested_by_sid integer,
    requested_date date,
    checked_by_sid integer,
    checked_date date,
    approved_by_sid integer,
    approved_date date,
    received_by_sid integer,
    received_date date,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    schema_name character varying NOT NULL
);


ALTER TABLE shulesoft.budgets OWNER TO postgres;

--
-- TOC entry 1928 (class 1259 OID 50390)
-- Name: budgets_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.budgets_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.budgets_id_seq OWNER TO postgres;

--
-- TOC entry 14049 (class 0 OID 0)
-- Dependencies: 1928
-- Name: budgets_id_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.budgets_id_seq OWNED BY shulesoft.budgets.id;


--
-- TOC entry 1929 (class 1259 OID 50391)
-- Name: capital; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.capital (
    id integer NOT NULL,
    uid integer NOT NULL,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    refer_expense_id integer,
    account_id integer,
    transaction_id character varying,
    amount numeric,
    user_sid integer,
    created_by_id integer,
    note text,
    reconciled smallint DEFAULT 0,
    number integer DEFAULT nextval('public.revenues_number_seq'::regclass) NOT NULL,
    date timestamp without time zone,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    schema_name character varying
);


ALTER TABLE shulesoft.capital OWNER TO postgres;

--
-- TOC entry 1930 (class 1259 OID 50400)
-- Name: capital_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.capital_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.capital_id_seq OWNER TO postgres;

--
-- TOC entry 14050 (class 0 OID 0)
-- Dependencies: 1930
-- Name: capital_id_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.capital_id_seq OWNED BY shulesoft.capital.id;


--
-- TOC entry 1931 (class 1259 OID 50401)
-- Name: capital_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.capital_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.capital_uid_seq OWNER TO postgres;

--
-- TOC entry 14051 (class 0 OID 0)
-- Dependencies: 1931
-- Name: capital_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.capital_uid_seq OWNED BY shulesoft.capital.uid;


--
-- TOC entry 1932 (class 1259 OID 50402)
-- Name: car_tracker_key_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.car_tracker_key_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.car_tracker_key_id_seq OWNER TO postgres;

--
-- TOC entry 1933 (class 1259 OID 50403)
-- Name: car_tracker_key; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.car_tracker_key (
    id integer DEFAULT nextval('shulesoft.car_tracker_key_id_seq'::regclass) NOT NULL,
    access_token character varying,
    app_key character varying,
    secret_key character varying,
    user_id character varying,
    target character varying,
    user_pwd_md5 character varying,
    message text,
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.car_tracker_key OWNER TO postgres;

--
-- TOC entry 1934 (class 1259 OID 50411)
-- Name: car_tracker_key_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.car_tracker_key_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.car_tracker_key_uid_seq OWNER TO postgres;

--
-- TOC entry 14052 (class 0 OID 0)
-- Dependencies: 1934
-- Name: car_tracker_key_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.car_tracker_key_uid_seq OWNED BY shulesoft.car_tracker_key.uid;


--
-- TOC entry 1935 (class 1259 OID 50412)
-- Name: cash_requests_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.cash_requests_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.cash_requests_id_seq OWNER TO postgres;

--
-- TOC entry 1936 (class 1259 OID 50413)
-- Name: cash_requests; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.cash_requests (
    id integer DEFAULT nextval('shulesoft.cash_requests_id_seq'::regclass) NOT NULL,
    amount numeric,
    requested_by integer,
    requested_by_table character varying,
    requested_date date,
    checked_by integer,
    checked_by_table character varying,
    checked_date date,
    approved_by integer,
    approved_by_table character varying,
    approved_date date,
    received_by integer,
    received_by_table character varying,
    received_date date,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    particulars json,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.cash_requests OWNER TO postgres;

--
-- TOC entry 1937 (class 1259 OID 50421)
-- Name: cash_requests_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.cash_requests_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.cash_requests_uid_seq OWNER TO postgres;

--
-- TOC entry 14053 (class 0 OID 0)
-- Dependencies: 1937
-- Name: cash_requests_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.cash_requests_uid_seq OWNED BY shulesoft.cash_requests.uid;


--
-- TOC entry 1938 (class 1259 OID 50422)
-- Name: certificate_setting_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.certificate_setting_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.certificate_setting_id_seq OWNER TO postgres;

--
-- TOC entry 1939 (class 1259 OID 50423)
-- Name: certificate_setting; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.certificate_setting (
    id integer DEFAULT nextval('shulesoft.certificate_setting_id_seq'::regclass) NOT NULL,
    certificate_type character varying,
    show_remarks smallint DEFAULT 0,
    show_grade smallint DEFAULT 0,
    final_date character varying,
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.certificate_setting OWNER TO postgres;

--
-- TOC entry 1940 (class 1259 OID 50433)
-- Name: certificate_setting_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.certificate_setting_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.certificate_setting_uid_seq OWNER TO postgres;

--
-- TOC entry 14054 (class 0 OID 0)
-- Dependencies: 1940
-- Name: certificate_setting_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.certificate_setting_uid_seq OWNED BY shulesoft.certificate_setting.uid;


--
-- TOC entry 1941 (class 1259 OID 50434)
-- Name: character_categories_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.character_categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.character_categories_id_seq OWNER TO postgres;

--
-- TOC entry 1942 (class 1259 OID 50435)
-- Name: character_categories; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.character_categories (
    id integer DEFAULT nextval('shulesoft.character_categories_id_seq'::regclass) NOT NULL,
    character_category character varying(255),
    based_on "char",
    "position" integer,
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    updated_at timestamp without time zone,
    schema_name character varying,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.character_categories OWNER TO postgres;

--
-- TOC entry 1943 (class 1259 OID 50443)
-- Name: character_categories_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.character_categories_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.character_categories_uid_seq OWNER TO postgres;

--
-- TOC entry 14055 (class 0 OID 0)
-- Dependencies: 1943
-- Name: character_categories_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.character_categories_uid_seq OWNED BY shulesoft.character_categories.uid;


--
-- TOC entry 1944 (class 1259 OID 50444)
-- Name: character_classes_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.character_classes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.character_classes_id_seq OWNER TO postgres;

--
-- TOC entry 1945 (class 1259 OID 50445)
-- Name: character_classes; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.character_classes (
    id integer DEFAULT nextval('shulesoft.character_classes_id_seq'::regclass) NOT NULL,
    character_id integer,
    class_id integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    created_by integer,
    created_by_table character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.character_classes OWNER TO postgres;

--
-- TOC entry 1946 (class 1259 OID 50453)
-- Name: character_classes_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.character_classes_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.character_classes_uid_seq OWNER TO postgres;

--
-- TOC entry 14056 (class 0 OID 0)
-- Dependencies: 1946
-- Name: character_classes_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.character_classes_uid_seq OWNED BY shulesoft.character_classes.uid;


--
-- TOC entry 1947 (class 1259 OID 50454)
-- Name: characters_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.characters_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.characters_id_seq OWNER TO postgres;

--
-- TOC entry 1948 (class 1259 OID 50455)
-- Name: characters; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.characters (
    id integer DEFAULT nextval('shulesoft.characters_id_seq'::regclass) NOT NULL,
    code character varying,
    description text,
    created_by character varying,
    created_by_id integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    character_category_id integer,
    "position" integer,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.characters OWNER TO postgres;

--
-- TOC entry 1949 (class 1259 OID 50463)
-- Name: characters_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.characters_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.characters_uid_seq OWNER TO postgres;

--
-- TOC entry 14057 (class 0 OID 0)
-- Dependencies: 1949
-- Name: characters_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.characters_uid_seq OWNED BY shulesoft.characters.uid;


--
-- TOC entry 1950 (class 1259 OID 50464)
-- Name: class_exam_class_exam_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.class_exam_class_exam_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.class_exam_class_exam_id_seq OWNER TO postgres;

--
-- TOC entry 1951 (class 1259 OID 50465)
-- Name: class_exam; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.class_exam (
    class_exam_id integer DEFAULT nextval('shulesoft.class_exam_class_exam_id_seq'::regclass) NOT NULL,
    class_id integer,
    exam_id integer,
    academic_year_id integer,
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.class_exam OWNER TO postgres;

--
-- TOC entry 1952 (class 1259 OID 50473)
-- Name: class_exam_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.class_exam_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.class_exam_uid_seq OWNER TO postgres;

--
-- TOC entry 14058 (class 0 OID 0)
-- Dependencies: 1952
-- Name: class_exam_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.class_exam_uid_seq OWNED BY shulesoft.class_exam.uid;


--
-- TOC entry 1773 (class 1259 OID 49634)
-- Name: classes_classesID_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft."classes_classesID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft."classes_classesID_seq" OWNER TO postgres;

--
-- TOC entry 1774 (class 1259 OID 49635)
-- Name: classes; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.classes (
    "classesID" integer DEFAULT nextval('shulesoft."classes_classesID_seq"'::regclass) NOT NULL,
    classes character varying(180) NOT NULL,
    classes_numeric integer NOT NULL,
    "teacherID" integer NOT NULL,
    note text,
    created_at timestamp without time zone DEFAULT now(),
    classlevel_id integer,
    special_grade_name_id integer,
    refer_class_id integer,
    updated_at timestamp without time zone,
    target numeric,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.classes OWNER TO postgres;

--
-- TOC entry 1953 (class 1259 OID 50474)
-- Name: classes_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.classes_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.classes_uid_seq OWNER TO postgres;

--
-- TOC entry 14059 (class 0 OID 0)
-- Dependencies: 1953
-- Name: classes_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.classes_uid_seq OWNED BY shulesoft.classes.uid;


--
-- TOC entry 1775 (class 1259 OID 49643)
-- Name: classlevel_classlevel_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.classlevel_classlevel_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.classlevel_classlevel_id_seq OWNER TO postgres;

--
-- TOC entry 1776 (class 1259 OID 49644)
-- Name: classlevel; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.classlevel (
    classlevel_id integer DEFAULT nextval('shulesoft.classlevel_classlevel_id_seq'::regclass) NOT NULL,
    name character varying(50),
    start_date character varying(50),
    end_date character varying(50),
    span_number integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    note text,
    result_format character varying(15),
    terms text,
    level_numeric integer DEFAULT 2,
    school_level_id integer,
    stamp character varying,
    head_teacher_title character varying,
    school_id integer,
    leaving_certificate character varying,
    pass_mark numeric,
    reg_form character varying,
    gender smallint,
    category smallint,
    religion smallint,
    education_level_id integer,
    target integer DEFAULT 50,
    title text,
    logo character varying,
    headname character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.classlevel OWNER TO postgres;

--
-- TOC entry 1954 (class 1259 OID 50475)
-- Name: classlevel_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.classlevel_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.classlevel_uid_seq OWNER TO postgres;

--
-- TOC entry 14060 (class 0 OID 0)
-- Dependencies: 1954
-- Name: classlevel_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.classlevel_uid_seq OWNED BY shulesoft.classlevel.uid;


--
-- TOC entry 2641 (class 1259 OID 54844)
-- Name: client_payment_status; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.client_payment_status AS
SELECT
    NULL::integer AS student_id,
    NULL::character varying(250) AS username,
    NULL::character varying(60) AS name,
    NULL::date AS created_at,
    NULL::date AS due_date,
    NULL::character varying AS invoice_year,
    NULL::numeric AS invoiced_amount,
    NULL::numeric AS paid_amount,
    NULL::numeric AS pending_balance,
    NULL::integer AS payment_status;


ALTER VIEW shulesoft.client_payment_status OWNER TO postgres;

--
-- TOC entry 1955 (class 1259 OID 50476)
-- Name: closing_year_balance_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.closing_year_balance_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.closing_year_balance_id_seq OWNER TO postgres;

--
-- TOC entry 1956 (class 1259 OID 50477)
-- Name: closing_year_balance; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.closing_year_balance (
    id integer DEFAULT nextval('shulesoft.closing_year_balance_id_seq'::regclass) NOT NULL,
    amount numeric,
    date date,
    closing_year character varying,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.closing_year_balance OWNER TO postgres;

--
-- TOC entry 1957 (class 1259 OID 50484)
-- Name: closing_year_balance_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.closing_year_balance_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.closing_year_balance_uid_seq OWNER TO postgres;

--
-- TOC entry 14061 (class 0 OID 0)
-- Dependencies: 1957
-- Name: closing_year_balance_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.closing_year_balance_uid_seq OWNED BY shulesoft.closing_year_balance.uid;


--
-- TOC entry 1958 (class 1259 OID 50485)
-- Name: configurations; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.configurations (
    id integer NOT NULL,
    uid integer NOT NULL,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    name character varying,
    status smallint DEFAULT 0,
    "position" smallint,
    module_id integer,
    schema_name character varying,
    user_sid integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    url character varying,
    relation character varying[]
);


ALTER TABLE shulesoft.configurations OWNER TO postgres;

--
-- TOC entry 1959 (class 1259 OID 50493)
-- Name: configurations_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.configurations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.configurations_id_seq OWNER TO postgres;

--
-- TOC entry 14062 (class 0 OID 0)
-- Dependencies: 1959
-- Name: configurations_id_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.configurations_id_seq OWNED BY shulesoft.configurations.id;


--
-- TOC entry 1960 (class 1259 OID 50494)
-- Name: configurations_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.configurations_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.configurations_uid_seq OWNER TO postgres;

--
-- TOC entry 14063 (class 0 OID 0)
-- Dependencies: 1960
-- Name: configurations_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.configurations_uid_seq OWNED BY shulesoft.configurations.uid;


--
-- TOC entry 1961 (class 1259 OID 50495)
-- Name: subject_section_subject_section_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.subject_section_subject_section_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.subject_section_subject_section_id_seq OWNER TO postgres;

--
-- TOC entry 1962 (class 1259 OID 50496)
-- Name: subject_section; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.subject_section (
    subject_section_id integer DEFAULT nextval('shulesoft.subject_section_subject_section_id_seq'::regclass) NOT NULL,
    subject_id integer,
    section_id integer,
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.subject_section OWNER TO postgres;

--
-- TOC entry 1963 (class 1259 OID 50504)
-- Name: subject_student_subject_student_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.subject_student_subject_student_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.subject_student_subject_student_id_seq OWNER TO postgres;

--
-- TOC entry 1964 (class 1259 OID 50505)
-- Name: subject_student; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.subject_student (
    subject_student_id integer DEFAULT nextval('shulesoft.subject_student_subject_student_id_seq'::regclass) NOT NULL,
    subject_id integer,
    student_id integer,
    created_at timestamp without time zone DEFAULT now(),
    academic_year_id integer,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.subject_student OWNER TO postgres;

--
-- TOC entry 1965 (class 1259 OID 50513)
-- Name: core_option_subject_count; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.core_option_subject_count AS
 SELECT count(DISTINCT a.subject_id) AS core_subjects,
    b.student_id,
    count(DISTINCT c.subject_id) AS option_subjects,
    (count(DISTINCT a.subject_id) + count(DISTINCT c.subject_id)) AS total_subjects
   FROM ((shulesoft.subject_section a
     JOIN shulesoft.student b ON ((b."sectionID" = a.section_id)))
     LEFT JOIN shulesoft.subject_student c ON ((c.student_id = b.student_id)))
  GROUP BY b.student_id;


ALTER VIEW shulesoft.core_option_subject_count OWNER TO postgres;

--
-- TOC entry 1966 (class 1259 OID 50518)
-- Name: current_assets_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.current_assets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.current_assets_id_seq OWNER TO postgres;

--
-- TOC entry 1967 (class 1259 OID 50519)
-- Name: current_assets2; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.current_assets2 (
    id integer DEFAULT nextval('shulesoft.current_assets_id_seq'::regclass) NOT NULL,
    amount numeric,
    date date,
    from_refer_expense_id integer,
    to_refer_expense_id integer,
    payer_name character varying,
    usertype character varying,
    uname character varying,
    created_by character varying,
    "userID" integer,
    note text,
    recipient character varying,
    transaction_id character varying,
    voucher_no integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.current_assets2 OWNER TO postgres;

--
-- TOC entry 1968 (class 1259 OID 50528)
-- Name: current_asset_transactions; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.current_asset_transactions AS
 SELECT d.amount,
    d.refer_expense_id,
    d.id,
    d.predefined,
    d.transaction_id,
    d.note,
    d.date,
    d.name,
    d.code,
    d.recipient
   FROM ( SELECT a.amount,
            b.id AS refer_expense_id,
            a.id,
            b.predefined,
            a.transaction_id,
            a.note,
            a.date,
            a.recipient,
            b.name,
            b.code
           FROM (shulesoft.current_assets2 a
             JOIN shulesoft.refer_expense b ON ((b.id = a.to_refer_expense_id)))) d
UNION ALL
 SELECT e.amount,
    e.refer_expense_id,
    e.id,
    e.predefined,
    e.transaction_id,
    e.note,
    e.date,
    e.name,
    e.code,
    e.recipient
   FROM ( SELECT
                CASE
                    WHEN (a.from_refer_expense_id = b.id) THEN ((0)::numeric - a.amount)
                    ELSE a.amount
                END AS amount,
            a.id,
            b.id AS refer_expense_id,
            b.predefined,
            a.transaction_id,
            a.note,
            a.date,
            a.recipient,
            b.name,
            b.code
           FROM (shulesoft.current_assets2 a
             JOIN shulesoft.refer_expense b ON ((b.id = a.from_refer_expense_id)))) e;


ALTER VIEW shulesoft.current_asset_transactions OWNER TO postgres;

--
-- TOC entry 1969 (class 1259 OID 50533)
-- Name: current_assets; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.current_assets (
    id integer NOT NULL,
    uid integer NOT NULL,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    refer_expense_id integer,
    account_id integer,
    transaction_id character varying,
    amount numeric,
    user_sid integer,
    created_by_id integer,
    note text,
    reconciled smallint DEFAULT 0,
    number integer DEFAULT nextval('public.revenues_number_seq'::regclass) NOT NULL,
    sms_sent smallint,
    date timestamp without time zone,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    schema_name character varying
);


ALTER TABLE shulesoft.current_assets OWNER TO postgres;

--
-- TOC entry 1970 (class 1259 OID 50542)
-- Name: current_assets_id_seq1; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.current_assets_id_seq1
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.current_assets_id_seq1 OWNER TO postgres;

--
-- TOC entry 14064 (class 0 OID 0)
-- Dependencies: 1970
-- Name: current_assets_id_seq1; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.current_assets_id_seq1 OWNED BY shulesoft.current_assets.id;


--
-- TOC entry 1971 (class 1259 OID 50543)
-- Name: current_assets_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.current_assets_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.current_assets_uid_seq OWNER TO postgres;

--
-- TOC entry 14065 (class 0 OID 0)
-- Dependencies: 1971
-- Name: current_assets_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.current_assets_uid_seq OWNED BY shulesoft.current_assets2.uid;


--
-- TOC entry 1972 (class 1259 OID 50544)
-- Name: current_assets_uid_seq1; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.current_assets_uid_seq1
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.current_assets_uid_seq1 OWNER TO postgres;

--
-- TOC entry 14066 (class 0 OID 0)
-- Dependencies: 1972
-- Name: current_assets_uid_seq1; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.current_assets_uid_seq1 OWNED BY shulesoft.current_assets.uid;


--
-- TOC entry 1973 (class 1259 OID 50545)
-- Name: deductions_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.deductions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.deductions_id_seq OWNER TO postgres;

--
-- TOC entry 1974 (class 1259 OID 50546)
-- Name: deductions; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.deductions (
    id integer DEFAULT nextval('shulesoft.deductions_id_seq'::regclass) NOT NULL,
    name character varying,
    percent double precision,
    amount double precision,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    description text,
    is_percentage integer,
    category smallint DEFAULT 1,
    account_number character varying,
    bank_account_id integer,
    employer_amount numeric,
    employer_percent numeric,
    gross_pay smallint DEFAULT 0,
    predefined smallint DEFAULT 0,
    type smallint DEFAULT 0,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    minimum_amount double precision
);


ALTER TABLE shulesoft.deductions OWNER TO postgres;

--
-- TOC entry 1975 (class 1259 OID 50557)
-- Name: deductions_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.deductions_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.deductions_uid_seq OWNER TO postgres;

--
-- TOC entry 14067 (class 0 OID 0)
-- Dependencies: 1975
-- Name: deductions_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.deductions_uid_seq OWNED BY shulesoft.deductions.uid;


--
-- TOC entry 1976 (class 1259 OID 50558)
-- Name: default_months; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.default_months AS
 SELECT ((to_char(now(), 'YYYY-01-01'::text))::date + ('1 mon'::interval month * (generate_series(0, (month_count)::integer))::double precision)) AS default_month
   FROM ( SELECT (((date_part('year'::text, td.diff) * (12)::double precision) + date_part('month'::text, td.diff)) + ((12)::double precision - date_part('month'::text, now()))) AS month_count
           FROM ( SELECT age(now(), ((to_char(now(), 'YYYY-01-01'::text))::timestamp without time zone)::timestamp with time zone) AS diff) td) t;


ALTER VIEW shulesoft.default_months OWNER TO postgres;

--
-- TOC entry 1777 (class 1259 OID 49654)
-- Name: diaries_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.diaries_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.diaries_id_seq OWNER TO postgres;

--
-- TOC entry 1778 (class 1259 OID 49655)
-- Name: diaries; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.diaries (
    id integer DEFAULT nextval('shulesoft.diaries_id_seq'::regclass) NOT NULL,
    student_id integer,
    teacher_id integer,
    work_title text,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    start_date date,
    description text,
    end_date date,
    subject_id integer,
    book_id integer,
    book_chapter character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.diaries OWNER TO postgres;

--
-- TOC entry 1977 (class 1259 OID 50562)
-- Name: diaries_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.diaries_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.diaries_uid_seq OWNER TO postgres;

--
-- TOC entry 14068 (class 0 OID 0)
-- Dependencies: 1977
-- Name: diaries_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.diaries_uid_seq OWNED BY shulesoft.diaries.uid;


--
-- TOC entry 1978 (class 1259 OID 50563)
-- Name: diary_comments_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.diary_comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.diary_comments_id_seq OWNER TO postgres;

--
-- TOC entry 1979 (class 1259 OID 50564)
-- Name: diary_comments; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.diary_comments (
    id integer DEFAULT nextval('shulesoft.diary_comments_id_seq'::regclass) NOT NULL,
    user_id integer,
    "table" character varying,
    comment text,
    diary_id integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    opened smallint DEFAULT 0,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.diary_comments OWNER TO postgres;

--
-- TOC entry 1980 (class 1259 OID 50573)
-- Name: diary_comments_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.diary_comments_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.diary_comments_uid_seq OWNER TO postgres;

--
-- TOC entry 14069 (class 0 OID 0)
-- Dependencies: 1980
-- Name: diary_comments_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.diary_comments_uid_seq OWNED BY shulesoft.diary_comments.uid;


--
-- TOC entry 1981 (class 1259 OID 50574)
-- Name: digital_invoices; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.digital_invoices AS
 SELECT a.reference,
    a.student_id,
    a.sync,
    b.name AS student_name,
    a.prefix,
    c.total_amount AS amount,
    c.balance,
    a.status
   FROM ((shulesoft.invoices a
     JOIN shulesoft.student b ON ((a.student_id = b.student_id)))
     JOIN shulesoft.invoice_balances c ON ((c.student_id = a.student_id)))
  WHERE (c.fee_id = 3000);


ALTER VIEW shulesoft.digital_invoices OWNER TO postgres;

--
-- TOC entry 1982 (class 1259 OID 50579)
-- Name: discount_fees_installments_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.discount_fees_installments_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.discount_fees_installments_uid_seq OWNER TO postgres;

--
-- TOC entry 14070 (class 0 OID 0)
-- Dependencies: 1982
-- Name: discount_fees_installments_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.discount_fees_installments_uid_seq OWNED BY shulesoft.discount_fees_installments.uid;


--
-- TOC entry 1983 (class 1259 OID 50580)
-- Name: due_amounts_payments_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.due_amounts_payments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.due_amounts_payments_id_seq OWNER TO postgres;

--
-- TOC entry 1984 (class 1259 OID 50581)
-- Name: due_amounts_payments; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.due_amounts_payments (
    id integer DEFAULT nextval('shulesoft.due_amounts_payments_id_seq'::regclass) NOT NULL,
    payment_id integer,
    due_amount_id integer,
    amount numeric,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    date date,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.due_amounts_payments OWNER TO postgres;

--
-- TOC entry 1985 (class 1259 OID 50589)
-- Name: due_amounts_payments_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.due_amounts_payments_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.due_amounts_payments_uid_seq OWNER TO postgres;

--
-- TOC entry 14071 (class 0 OID 0)
-- Dependencies: 1985
-- Name: due_amounts_payments_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.due_amounts_payments_uid_seq OWNED BY shulesoft.due_amounts_payments.uid;


--
-- TOC entry 1986 (class 1259 OID 50590)
-- Name: due_amounts_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.due_amounts_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.due_amounts_uid_seq OWNER TO postgres;

--
-- TOC entry 14072 (class 0 OID 0)
-- Dependencies: 1986
-- Name: due_amounts_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.due_amounts_uid_seq OWNED BY shulesoft.due_amounts.uid;


--
-- TOC entry 1987 (class 1259 OID 50591)
-- Name: dues_balance; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.dues_balance AS
 SELECT COALESCE(a.amount, (0)::numeric) AS amount,
    a.id,
    COALESCE(b.due_paid_amount, (0)::numeric) AS due_paid_amount,
    a.fee_id,
    a.student_id,
        CASE
            WHEN ((COALESCE(a.amount, (0)::numeric) - COALESCE(b.due_paid_amount, (0)::numeric)) > (0)::numeric) THEN 0
            ELSE 1
        END AS status,
    a.schema_name
   FROM (shulesoft.due_amounts a
     LEFT JOIN ( SELECT sum(COALESCE(due_amounts_payments.amount, (0)::numeric)) AS due_paid_amount,
            due_amounts_payments.due_amount_id,
            due_amounts_payments.schema_name
           FROM shulesoft.due_amounts_payments
          GROUP BY due_amounts_payments.due_amount_id, due_amounts_payments.schema_name) b ON (((b.due_amount_id = a.id) AND ((b.schema_name)::text = (a.schema_name)::text))));


ALTER VIEW shulesoft.dues_balance OWNER TO postgres;

--
-- TOC entry 1811 (class 1259 OID 49898)
-- Name: duties_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.duties_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.duties_id_seq OWNER TO postgres;

--
-- TOC entry 1812 (class 1259 OID 49899)
-- Name: duties; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.duties (
    id integer DEFAULT nextval('shulesoft.duties_id_seq'::regclass) NOT NULL,
    start_date timestamp without time zone,
    end_date timestamp without time zone,
    name character varying,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    created_by integer,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.duties OWNER TO postgres;

--
-- TOC entry 1988 (class 1259 OID 50596)
-- Name: duties_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.duties_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.duties_uid_seq OWNER TO postgres;

--
-- TOC entry 14073 (class 0 OID 0)
-- Dependencies: 1988
-- Name: duties_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.duties_uid_seq OWNED BY shulesoft.duties.uid;


--
-- TOC entry 1989 (class 1259 OID 50597)
-- Name: duty_reports_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.duty_reports_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.duty_reports_id_seq OWNER TO postgres;

--
-- TOC entry 1990 (class 1259 OID 50598)
-- Name: duty_reports; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.duty_reports (
    id integer DEFAULT nextval('shulesoft.duty_reports_id_seq'::regclass) NOT NULL,
    date date,
    transport integer,
    feed integer,
    special_event integer,
    tod_comment text,
    headteacher_comment text,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    created_by character varying,
    created_by_table character varying,
    duty_id integer,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.duty_reports OWNER TO postgres;

--
-- TOC entry 1991 (class 1259 OID 50605)
-- Name: duty_reports_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.duty_reports_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.duty_reports_uid_seq OWNER TO postgres;

--
-- TOC entry 14074 (class 0 OID 0)
-- Dependencies: 1991
-- Name: duty_reports_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.duty_reports_uid_seq OWNED BY shulesoft.duty_reports.uid;


--
-- TOC entry 1992 (class 1259 OID 50606)
-- Name: eattendance_eattendanceID_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft."eattendance_eattendanceID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft."eattendance_eattendanceID_seq" OWNER TO postgres;

--
-- TOC entry 1993 (class 1259 OID 50607)
-- Name: eattendance; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.eattendance (
    "eattendanceID" integer DEFAULT nextval('shulesoft."eattendance_eattendanceID_seq"'::regclass) NOT NULL,
    "examID" integer NOT NULL,
    "classesID" integer NOT NULL,
    "subjectID" integer NOT NULL,
    date date NOT NULL,
    student_id integer,
    s_name character varying(60),
    eattendance character varying(20),
    year integer NOT NULL,
    eextra character varying(60),
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.eattendance OWNER TO postgres;

--
-- TOC entry 1994 (class 1259 OID 50615)
-- Name: eattendance_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.eattendance_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.eattendance_uid_seq OWNER TO postgres;

--
-- TOC entry 14075 (class 0 OID 0)
-- Dependencies: 1994
-- Name: eattendance_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.eattendance_uid_seq OWNED BY shulesoft.eattendance.uid;


--
-- TOC entry 1995 (class 1259 OID 50616)
-- Name: email_email_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.email_email_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.email_email_id_seq OWNER TO postgres;

--
-- TOC entry 1996 (class 1259 OID 50617)
-- Name: email; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.email (
    email_id integer DEFAULT nextval('shulesoft.email_email_id_seq'::regclass) NOT NULL,
    body text,
    subject text,
    user_id integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT now(),
    status integer DEFAULT 0,
    email character varying(250),
    "table" character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone,
    return_message text,
    user_sid integer
);


ALTER TABLE shulesoft.email OWNER TO postgres;

--
-- TOC entry 1997 (class 1259 OID 50627)
-- Name: email_lists_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.email_lists_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.email_lists_id_seq OWNER TO postgres;

--
-- TOC entry 1998 (class 1259 OID 50628)
-- Name: email_lists; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.email_lists (
    id integer DEFAULT nextval('shulesoft.email_lists_id_seq'::regclass) NOT NULL,
    email character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    created_by character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.email_lists OWNER TO postgres;

--
-- TOC entry 1999 (class 1259 OID 50636)
-- Name: email_lists_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.email_lists_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.email_lists_uid_seq OWNER TO postgres;

--
-- TOC entry 14076 (class 0 OID 0)
-- Dependencies: 1999
-- Name: email_lists_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.email_lists_uid_seq OWNED BY shulesoft.email_lists.uid;


--
-- TOC entry 2000 (class 1259 OID 50637)
-- Name: email_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.email_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.email_uid_seq OWNER TO postgres;

--
-- TOC entry 14077 (class 0 OID 0)
-- Dependencies: 2000
-- Name: email_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.email_uid_seq OWNED BY shulesoft.email.uid;


--
-- TOC entry 2001 (class 1259 OID 50638)
-- Name: exam_examID_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft."exam_examID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft."exam_examID_seq" OWNER TO postgres;

--
-- TOC entry 2002 (class 1259 OID 50639)
-- Name: exam; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.exam (
    "examID" integer DEFAULT nextval('shulesoft."exam_examID_seq"'::regclass) NOT NULL,
    exam character varying(60) NOT NULL,
    date date NOT NULL,
    note text,
    created_at timestamp without time zone DEFAULT now(),
    abbreviation character varying(10),
    semester_id integer,
    semister_id integer,
    refer_exam_id integer,
    show_division integer DEFAULT 0,
    special_grade_name_id integer,
    global_exam_id integer,
    target integer DEFAULT 50,
    weight integer DEFAULT 100,
    updated_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.exam OWNER TO postgres;

--
-- TOC entry 2003 (class 1259 OID 50651)
-- Name: exam_comments_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.exam_comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.exam_comments_id_seq OWNER TO postgres;

--
-- TOC entry 2004 (class 1259 OID 50652)
-- Name: exam_comments; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.exam_comments (
    id integer DEFAULT nextval('shulesoft.exam_comments_id_seq'::regclass) NOT NULL,
    body text,
    student_id integer,
    exam_report_id integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone DEFAULT now(),
    user_id integer,
    name character varying,
    academic_year_id integer,
    status smallint DEFAULT 1,
    user_table character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.exam_comments OWNER TO postgres;

--
-- TOC entry 2005 (class 1259 OID 50662)
-- Name: exam_comments_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.exam_comments_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.exam_comments_uid_seq OWNER TO postgres;

--
-- TOC entry 14078 (class 0 OID 0)
-- Dependencies: 2005
-- Name: exam_comments_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.exam_comments_uid_seq OWNED BY shulesoft.exam_comments.uid;


--
-- TOC entry 2006 (class 1259 OID 50663)
-- Name: exam_groups_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.exam_groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.exam_groups_id_seq OWNER TO postgres;

--
-- TOC entry 2007 (class 1259 OID 50664)
-- Name: exam_groups; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.exam_groups (
    id integer DEFAULT nextval('shulesoft.exam_groups_id_seq'::regclass) NOT NULL,
    name character varying,
    note text,
    weight numeric,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    predefined smallint,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.exam_groups OWNER TO postgres;

--
-- TOC entry 2008 (class 1259 OID 50672)
-- Name: exam_groups_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.exam_groups_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.exam_groups_uid_seq OWNER TO postgres;

--
-- TOC entry 14079 (class 0 OID 0)
-- Dependencies: 2008
-- Name: exam_groups_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.exam_groups_uid_seq OWNED BY shulesoft.exam_groups.uid;


--
-- TOC entry 1779 (class 1259 OID 49663)
-- Name: exam_report_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.exam_report_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.exam_report_id_seq OWNER TO postgres;

--
-- TOC entry 1780 (class 1259 OID 49664)
-- Name: exam_report; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.exam_report (
    id integer DEFAULT nextval('shulesoft.exam_report_id_seq'::regclass) NOT NULL,
    classes_id integer,
    combined_exams character varying(250),
    name character varying(180),
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    sms_sent character varying DEFAULT '0'::character varying,
    exam_id integer,
    email_sent character(1) DEFAULT '0'::bpchar,
    academic_year_id integer,
    combined_exam_array character varying,
    reporting_date date,
    percent character varying,
    semester_id integer,
    rank_per_stream smallint DEFAULT 0,
    show_division smallint DEFAULT 0,
    show_reporting_date smallint DEFAULT 0,
    rank_per_class smallint DEFAULT 0,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.exam_report OWNER TO postgres;

--
-- TOC entry 2009 (class 1259 OID 50673)
-- Name: exam_report_settings_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.exam_report_settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.exam_report_settings_id_seq OWNER TO postgres;

--
-- TOC entry 2010 (class 1259 OID 50674)
-- Name: exam_report_settings; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.exam_report_settings (
    id integer DEFAULT nextval('shulesoft.exam_report_settings_id_seq'::regclass) NOT NULL,
    exam_type character varying,
    average_column_name character varying,
    show_teacher smallint,
    show_remarks smallint,
    show_teacher_sign smallint,
    show_grade smallint,
    show_pos_in_stream smallint,
    show_csee_division smallint,
    show_acsee_division smallint,
    show_subject_point smallint,
    show_division_by_exam smallint,
    show_division_total_points smallint,
    show_overall_division smallint,
    show_pos_in_class smallint,
    show_pos_in_section smallint,
    show_subject_pos_in_class smallint,
    show_subject_pos_in_section smallint,
    semester_average_name character varying DEFAULT 'AVG'::character varying,
    single_semester_avg_name character varying DEFAULT 'AVERAGE OF AVG'::character varying,
    overall_semester_avg_name character varying DEFAULT 'AVERAGE OF AVG'::character varying,
    show_classteacher_name smallint DEFAULT 0,
    show_classteacher_phone smallint DEFAULT 0,
    class_teacher_remark text,
    head_teacher_remark text,
    show_all_signature_on_the_footer smallint DEFAULT 0,
    show_student_attendance smallint DEFAULT 0,
    show_overall_grade smallint DEFAULT 0,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    show_average_row smallint DEFAULT 1,
    show_percentage_on_mark_column integer DEFAULT 0,
    subject_order_by_arrangement smallint DEFAULT 0,
    show_total_marks smallint,
    show_subject_total_across_exams smallint DEFAULT 0,
    classlevel_id integer,
    show_parent_comment smallint DEFAULT 0,
    show_marks smallint DEFAULT 1,
    show_fee_payment_status smallint DEFAULT 0,
    show_overall_average_across_exams smallint DEFAULT 1,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.exam_report_settings OWNER TO postgres;

--
-- TOC entry 2011 (class 1259 OID 50697)
-- Name: exam_report_settings_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.exam_report_settings_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.exam_report_settings_uid_seq OWNER TO postgres;

--
-- TOC entry 14080 (class 0 OID 0)
-- Dependencies: 2011
-- Name: exam_report_settings_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.exam_report_settings_uid_seq OWNED BY shulesoft.exam_report_settings.uid;


--
-- TOC entry 2012 (class 1259 OID 50698)
-- Name: exam_report_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.exam_report_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.exam_report_uid_seq OWNER TO postgres;

--
-- TOC entry 14081 (class 0 OID 0)
-- Dependencies: 2012
-- Name: exam_report_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.exam_report_uid_seq OWNED BY shulesoft.exam_report.uid;


--
-- TOC entry 2013 (class 1259 OID 50699)
-- Name: exam_special_cases_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.exam_special_cases_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.exam_special_cases_id_seq OWNER TO postgres;

--
-- TOC entry 2014 (class 1259 OID 50700)
-- Name: exam_special_cases; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.exam_special_cases (
    id integer DEFAULT nextval('shulesoft.exam_special_cases_id_seq'::regclass) NOT NULL,
    student_id integer,
    exam_id integer,
    description text,
    special_exam_reason_id integer,
    created_by integer,
    created_by_table character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    subjects_excluded integer,
    subjects_ids_excluded character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.exam_special_cases OWNER TO postgres;

--
-- TOC entry 2015 (class 1259 OID 50708)
-- Name: exam_special_cases_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.exam_special_cases_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.exam_special_cases_uid_seq OWNER TO postgres;

--
-- TOC entry 14082 (class 0 OID 0)
-- Dependencies: 2015
-- Name: exam_special_cases_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.exam_special_cases_uid_seq OWNED BY shulesoft.exam_special_cases.uid;


--
-- TOC entry 2016 (class 1259 OID 50709)
-- Name: exam_subject_mark_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.exam_subject_mark_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.exam_subject_mark_id_seq OWNER TO postgres;

--
-- TOC entry 2017 (class 1259 OID 50710)
-- Name: exam_subject_mark; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.exam_subject_mark (
    id integer DEFAULT nextval('shulesoft.exam_subject_mark_id_seq'::regclass) NOT NULL,
    "examID" integer NOT NULL,
    "subjectID" integer NOT NULL,
    mark numeric DEFAULT 0.0,
    created_at timestamp without time zone DEFAULT now(),
    created_by integer,
    "table" character varying,
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.exam_subject_mark OWNER TO postgres;

--
-- TOC entry 2018 (class 1259 OID 50719)
-- Name: exam_subject_mark_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.exam_subject_mark_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.exam_subject_mark_uid_seq OWNER TO postgres;

--
-- TOC entry 14083 (class 0 OID 0)
-- Dependencies: 2018
-- Name: exam_subject_mark_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.exam_subject_mark_uid_seq OWNED BY shulesoft.exam_subject_mark.uid;


--
-- TOC entry 2019 (class 1259 OID 50720)
-- Name: exam_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.exam_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.exam_uid_seq OWNER TO postgres;

--
-- TOC entry 14084 (class 0 OID 0)
-- Dependencies: 2019
-- Name: exam_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.exam_uid_seq OWNED BY shulesoft.exam.uid;


--
-- TOC entry 2020 (class 1259 OID 50721)
-- Name: examschedule_examscheduleID_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft."examschedule_examscheduleID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft."examschedule_examscheduleID_seq" OWNER TO postgres;

--
-- TOC entry 2021 (class 1259 OID 50722)
-- Name: examschedule; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.examschedule (
    "examscheduleID" integer DEFAULT nextval('shulesoft."examschedule_examscheduleID_seq"'::regclass) NOT NULL,
    "examID" integer NOT NULL,
    "classesID" integer NOT NULL,
    "sectionID" integer NOT NULL,
    "subjectID" integer NOT NULL,
    edate date NOT NULL,
    examfrom character varying(10) NOT NULL,
    examto character varying(10) NOT NULL,
    room text,
    year integer NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.examschedule OWNER TO postgres;

--
-- TOC entry 2022 (class 1259 OID 50730)
-- Name: examschedule_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.examschedule_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.examschedule_uid_seq OWNER TO postgres;

--
-- TOC entry 14085 (class 0 OID 0)
-- Dependencies: 2022
-- Name: examschedule_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.examschedule_uid_seq OWNED BY shulesoft.examschedule.uid;


--
-- TOC entry 2023 (class 1259 OID 50731)
-- Name: exchange_rates_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.exchange_rates_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.exchange_rates_id_seq OWNER TO postgres;

--
-- TOC entry 2024 (class 1259 OID 50732)
-- Name: exchange_rates; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.exchange_rates (
    id integer DEFAULT nextval('shulesoft.exchange_rates_id_seq'::regclass) NOT NULL,
    from_currency character varying,
    to_currency character varying,
    rate numeric,
    note text,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    created_by_id integer,
    created_by_table character varying,
    status smallint,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.exchange_rates OWNER TO postgres;

--
-- TOC entry 2025 (class 1259 OID 50740)
-- Name: exchange_rates_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.exchange_rates_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.exchange_rates_uid_seq OWNER TO postgres;

--
-- TOC entry 14086 (class 0 OID 0)
-- Dependencies: 2025
-- Name: exchange_rates_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.exchange_rates_uid_seq OWNED BY shulesoft.exchange_rates.uid;


--
-- TOC entry 2026 (class 1259 OID 50741)
-- Name: expense_cart_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.expense_cart_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.expense_cart_id_seq OWNER TO postgres;

--
-- TOC entry 2027 (class 1259 OID 50742)
-- Name: expense_cart; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.expense_cart (
    id integer DEFAULT nextval('shulesoft.expense_cart_id_seq'::regclass) NOT NULL,
    name character varying,
    note text,
    date date,
    expense_id integer NOT NULL,
    refer_expense_id integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    created_by character varying,
    amount numeric NOT NULL,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.expense_cart OWNER TO postgres;

--
-- TOC entry 2028 (class 1259 OID 50750)
-- Name: expense_cart_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.expense_cart_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.expense_cart_uid_seq OWNER TO postgres;

--
-- TOC entry 14087 (class 0 OID 0)
-- Dependencies: 2028
-- Name: expense_cart_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.expense_cart_uid_seq OWNED BY shulesoft.expense_cart.uid;


--
-- TOC entry 2029 (class 1259 OID 50751)
-- Name: expense_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.expense_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.expense_uid_seq OWNER TO postgres;

--
-- TOC entry 14088 (class 0 OID 0)
-- Dependencies: 2029
-- Name: expense_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.expense_uid_seq OWNED BY shulesoft.expense.uid;


--
-- TOC entry 2032 (class 1259 OID 50765)
-- Name: expenses; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.expenses (
    id integer NOT NULL,
    uid integer NOT NULL,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    refer_expense_id integer,
    account_id integer,
    category character varying,
    transaction_id character varying,
    reference character varying,
    amount numeric,
    vendor_id integer,
    created_by_sid integer,
    note text,
    reconciled smallint DEFAULT 0,
    number integer DEFAULT nextval('public.revenues_number_seq'::regclass) NOT NULL,
    date timestamp without time zone,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    schema_name character varying,
    voucher integer DEFAULT 0,
    user_sid integer,
    user_name character varying,
    user_phone character varying
);


ALTER TABLE shulesoft.expenses OWNER TO postgres;

--
-- TOC entry 14089 (class 0 OID 0)
-- Dependencies: 2032
-- Name: COLUMN expenses.user_sid; Type: COMMENT; Schema: shulesoft; Owner: postgres
--

COMMENT ON COLUMN shulesoft.expenses.user_sid IS 'if user exists in shulesoft, we save sid ';


--
-- TOC entry 14090 (class 0 OID 0)
-- Dependencies: 2032
-- Name: COLUMN expenses.user_name; Type: COMMENT; Schema: shulesoft; Owner: postgres
--

COMMENT ON COLUMN shulesoft.expenses.user_name IS 'if user not in shulesoft, and not a vendor';


--
-- TOC entry 14091 (class 0 OID 0)
-- Dependencies: 2032
-- Name: COLUMN expenses.user_phone; Type: COMMENT; Schema: shulesoft; Owner: postgres
--

COMMENT ON COLUMN shulesoft.expenses.user_phone IS 'if user not in shulesoft and not a vendor, we store phone number';


--
-- TOC entry 2033 (class 1259 OID 50775)
-- Name: expenses_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.expenses_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.expenses_id_seq OWNER TO postgres;

--
-- TOC entry 14092 (class 0 OID 0)
-- Dependencies: 2033
-- Name: expenses_id_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.expenses_id_seq OWNED BY shulesoft.expenses.id;


--
-- TOC entry 2034 (class 1259 OID 50776)
-- Name: expenses_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.expenses_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.expenses_uid_seq OWNER TO postgres;

--
-- TOC entry 14093 (class 0 OID 0)
-- Dependencies: 2034
-- Name: expenses_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.expenses_uid_seq OWNED BY shulesoft.expenses.uid;


--
-- TOC entry 2035 (class 1259 OID 50777)
-- Name: feecat_class_feecat_classesID_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft."feecat_class_feecat_classesID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft."feecat_class_feecat_classesID_seq" OWNER TO postgres;

--
-- TOC entry 2036 (class 1259 OID 50778)
-- Name: feecat_class; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.feecat_class (
    "feecat_classesID" integer DEFAULT nextval('shulesoft."feecat_class_feecat_classesID_seq"'::regclass) NOT NULL,
    "classesID" integer,
    "feetype_categoryID" integer,
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.feecat_class OWNER TO postgres;

--
-- TOC entry 2037 (class 1259 OID 50786)
-- Name: feecat_class_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.feecat_class_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.feecat_class_uid_seq OWNER TO postgres;

--
-- TOC entry 14094 (class 0 OID 0)
-- Dependencies: 2037
-- Name: feecat_class_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.feecat_class_uid_seq OWNED BY shulesoft.feecat_class.uid;


--
-- TOC entry 2038 (class 1259 OID 50787)
-- Name: fees_classes_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.fees_classes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.fees_classes_id_seq OWNER TO postgres;

--
-- TOC entry 2039 (class 1259 OID 50788)
-- Name: fees_classes; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.fees_classes (
    id integer DEFAULT nextval('shulesoft.fees_classes_id_seq'::regclass) NOT NULL,
    fee_id integer,
    class_id integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    amount numeric
);


ALTER TABLE shulesoft.fees_classes OWNER TO postgres;

--
-- TOC entry 2040 (class 1259 OID 50796)
-- Name: fees_classes_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.fees_classes_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.fees_classes_uid_seq OWNER TO postgres;

--
-- TOC entry 14095 (class 0 OID 0)
-- Dependencies: 2040
-- Name: fees_classes_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.fees_classes_uid_seq OWNED BY shulesoft.fees_classes.uid;


--
-- TOC entry 2041 (class 1259 OID 50797)
-- Name: fees_installments_amounts; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.fees_installments_amounts AS
 SELECT a.fees_installment_id,
    a.amount
   FROM shulesoft.fees_installments_classes a
UNION
 SELECT b.fees_installment_id,
    b.amount
   FROM shulesoft.transport_routes_fees_installments b
UNION
 SELECT c.fees_installment_id,
    c.amount
   FROM shulesoft.hostel_fees_installments c;


ALTER VIEW shulesoft.fees_installments_amounts OWNER TO postgres;

--
-- TOC entry 2042 (class 1259 OID 50801)
-- Name: fees_installments_classes_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.fees_installments_classes_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.fees_installments_classes_uid_seq OWNER TO postgres;

--
-- TOC entry 14096 (class 0 OID 0)
-- Dependencies: 2042
-- Name: fees_installments_classes_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.fees_installments_classes_uid_seq OWNED BY shulesoft.fees_installments_classes.uid;


--
-- TOC entry 2043 (class 1259 OID 50802)
-- Name: fees_installments_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.fees_installments_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.fees_installments_uid_seq OWNER TO postgres;

--
-- TOC entry 14097 (class 0 OID 0)
-- Dependencies: 2043
-- Name: fees_installments_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.fees_installments_uid_seq OWNED BY shulesoft.fees_installments.uid;


--
-- TOC entry 2044 (class 1259 OID 50803)
-- Name: total_advance_invoice_fee_amount; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.total_advance_invoice_fee_amount AS
 SELECT sum(amount) AS total_advance_invoice_fee_amount,
    invoices_fees_installments_id,
    schema_name
   FROM shulesoft.advance_payments_invoices_fees_installments b_1
  GROUP BY invoices_fees_installments_id, schema_name;


ALTER VIEW shulesoft.total_advance_invoice_fee_amount OWNER TO postgres;

--
-- TOC entry 2045 (class 1259 OID 50807)
-- Name: total_payment_invoice_amount; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.total_payment_invoice_amount AS
 SELECT sum(amount) AS total_payment_invoice_amount,
    invoices_fees_installment_id
   FROM shulesoft.payments_invoices_fees_installments
  GROUP BY invoices_fees_installment_id;


ALTER VIEW shulesoft.total_payment_invoice_amount OWNER TO postgres;

--
-- TOC entry 2046 (class 1259 OID 50811)
-- Name: school_basic_invoices; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.school_basic_invoices AS
 SELECT COALESCE(a.amount, (0)::numeric) AS total_amount,
    COALESCE(c.total_payment_invoice_amount, (0)::numeric) AS total_payment_invoice_fee_amount,
    COALESCE(d.total_advance_invoice_fee_amount, (0)::numeric) AS total_advance_invoice_fee_amount,
    COALESCE(e.amount, (0)::numeric) AS discount_amount,
    f.student_id,
    f.date AS created_at,
    b.id AS invoices_fees_installments_id,
    COALESCE(r.total_amount, (0)::numeric) AS advance_amount,
    b.fees_installment_id,
    g.fee_id,
    f.academic_year_id,
    g.installment_id,
    x.start_date,
    x.end_date,
    f.id AS invoice_id,
        CASE
            WHEN ((((a.amount - COALESCE(c.total_payment_invoice_amount, (0)::numeric)) - COALESCE(d.total_advance_invoice_fee_amount, (0)::numeric)) - COALESCE(e.amount, (0)::numeric)) > (0)::numeric) THEN 0
            ELSE 1
        END AS status,
    (((COALESCE(a.amount, (0)::numeric) - COALESCE(c.total_payment_invoice_amount, (0)::numeric)) - COALESCE(d.total_advance_invoice_fee_amount, (0)::numeric)) - COALESCE(e.amount, (0)::numeric)) AS balance,
    a.schema_name
   FROM ((((((((((shulesoft.invoices f
     JOIN shulesoft.invoices_fees_installments b ON ((b.invoice_id = f.id)))
     JOIN shulesoft.fees_installments_classes a ON ((a.fees_installment_id = b.fees_installment_id)))
     JOIN shulesoft.fees_installments g ON ((g.id = a.fees_installment_id)))
     JOIN shulesoft.installments x ON ((x.id = g.installment_id)))
     JOIN shulesoft.student_archive ss ON (((ss.student_id = f.student_id) AND (ss.academic_year_id = f.academic_year_id))))
     JOIN shulesoft.section se ON (((se."classesID" = a.class_id) AND (se."sectionID" = ss.section_id))))
     LEFT JOIN shulesoft.total_payment_invoice_amount c ON ((c.invoices_fees_installment_id = b.id)))
     LEFT JOIN shulesoft.total_advance_invoice_fee_amount d ON ((d.invoices_fees_installments_id = b.id)))
     LEFT JOIN ( SELECT COALESCE(sum(p.amount), (0)::numeric) AS total_amount,
            sum((COALESCE(p.amount, (0)::numeric) - COALESCE(r_1.total_advance_invoice_fee_amount, (0)::numeric))) AS reminder,
            p.fee_id,
            p.student_id
           FROM (shulesoft.advance_payments p
             LEFT JOIN ( SELECT COALESCE(sum(b_1.amount), (0)::numeric) AS total_advance_invoice_fee_amount,
                    b_1.advance_payment_id
                   FROM shulesoft.advance_payments_invoices_fees_installments b_1
                  GROUP BY b_1.advance_payment_id) r_1 ON ((r_1.advance_payment_id = p.id)))
          GROUP BY p.fee_id, p.student_id) r ON (((r.student_id = f.student_id) AND (r.fee_id = g.fee_id))))
     LEFT JOIN shulesoft.discount_fees_installments e ON (((e.fees_installment_id = a.fees_installment_id) AND (f.student_id = e.student_id))));


ALTER VIEW shulesoft.school_basic_invoices OWNER TO postgres;

--
-- TOC entry 2047 (class 1259 OID 50816)
-- Name: school_hostel_invoices2; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.school_hostel_invoices2 AS
 SELECT DISTINCT COALESCE(a.amount) AS total_amount,
    COALESCE(( SELECT sum(payments_invoices_fees_installments.amount) AS sum
           FROM shulesoft.payments_invoices_fees_installments
          WHERE (payments_invoices_fees_installments.invoices_fees_installment_id = g.id)), (0)::numeric) AS total_payment_invoice_fee_amount,
    COALESCE(( SELECT sum(advance_payments_invoices_fees_installments.amount) AS sum
           FROM shulesoft.advance_payments_invoices_fees_installments
          WHERE (advance_payments_invoices_fees_installments.invoices_fees_installments_id = g.id)), (0)::numeric) AS total_advance_invoice_fee_amount,
    COALESCE(k.amount, (0)::numeric) AS discount_amount,
    d.student_id,
    f.date AS created_at,
    g.id AS invoices_fees_installments_id,
    COALESCE(( SELECT sum(advance_payments_invoices_fees_installments.amount) AS sum
           FROM shulesoft.advance_payments_invoices_fees_installments
          WHERE (advance_payments_invoices_fees_installments.invoices_fees_installments_id = g.id)), (0)::numeric) AS advance_amount,
    a.fees_installment_id,
    c.installment_id,
    e.start_date,
    f.academic_year_id,
    ( SELECT fees.id
           FROM shulesoft.fees
          WHERE (((fees.schema_name)::text = (b.schema_name)::text) AND (lower((fees.name)::text) ~~ '%hostel%'::text))
         LIMIT 1) AS fee_id,
    f.id AS invoice_id,
        CASE
            WHEN ((((a.amount - COALESCE(( SELECT sum(payments_invoices_fees_installments.amount) AS sum
               FROM shulesoft.payments_invoices_fees_installments
              WHERE (payments_invoices_fees_installments.invoices_fees_installment_id = g.id)), (0)::numeric)) - COALESCE(i.total_advance_invoice_fee_amount, (0)::numeric)) - COALESCE(a.amount, (0)::numeric)) > (0)::numeric) THEN 0
            ELSE 1
        END AS status,
    COALESCE((((a.amount - COALESCE(( SELECT sum(payments_invoices_fees_installments.amount) AS sum
           FROM shulesoft.payments_invoices_fees_installments
          WHERE (payments_invoices_fees_installments.invoices_fees_installment_id = g.id)), (0)::numeric)) - COALESCE(i.total_advance_invoice_fee_amount, (0)::numeric)) - COALESCE(k.amount, (0)::numeric)), (0)::numeric) AS balance,
    e.end_date,
    b.schema_name
   FROM ((((((((shulesoft.invoices f
     JOIN shulesoft.invoices_fees_installments g ON ((g.invoice_id = f.id)))
     JOIN shulesoft.hostel_fees_installments a ON ((a.fees_installment_id = g.fees_installment_id)))
     JOIN shulesoft.hostels b ON ((b.id = a.hostel_id)))
     JOIN shulesoft.fees_installments c ON ((c.id = a.fees_installment_id)))
     JOIN shulesoft.hmembers d ON (((d.hostel_id = a.hostel_id) AND (d.student_id = f.student_id))))
     LEFT JOIN shulesoft.total_advance_invoice_fee_amount i ON ((i.invoices_fees_installments_id = g.id)))
     LEFT JOIN shulesoft.discount_fees_installments k ON (((k.fees_installment_id = c.id) AND (k.student_id = f.student_id))))
     JOIN shulesoft.installments e ON ((e.id = c.installment_id)))
  WHERE (a.amount > (0)::numeric);


ALTER VIEW shulesoft.school_hostel_invoices2 OWNER TO postgres;

--
-- TOC entry 2048 (class 1259 OID 50821)
-- Name: school_transport_invoices; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.school_transport_invoices AS
 SELECT DISTINCT COALESCE(d.amount, (0)::numeric) AS total_amount,
    COALESCE(( SELECT sum(payments_invoices_fees_installments.amount) AS sum
           FROM shulesoft.payments_invoices_fees_installments
          WHERE (payments_invoices_fees_installments.invoices_fees_installment_id = g.id)), (0)::numeric) AS total_payment_invoice_fee_amount,
    COALESCE(( SELECT sum(advance_payments_invoices_fees_installments.amount) AS sum
           FROM shulesoft.advance_payments_invoices_fees_installments
          WHERE (advance_payments_invoices_fees_installments.invoices_fees_installments_id = g.id)), (0)::numeric) AS total_advance_invoice_fee_amount,
    COALESCE(k.amount, (0)::numeric) AS discount_amount,
    f.student_id,
    f.date AS created_at,
    g.id AS invoices_fees_installments_id,
    COALESCE(( SELECT sum(advance_payments_invoices_fees_installments.amount) AS sum
           FROM shulesoft.advance_payments_invoices_fees_installments
          WHERE (advance_payments_invoices_fees_installments.invoices_fees_installments_id = g.id)), (0)::numeric) AS advance_amount,
    a.fees_installment_id,
    c.installment_id,
    e.start_date,
    f.academic_year_id,
    z.id AS fee_id,
    f.id AS invoice_id,
        CASE
            WHEN ((((d.amount - COALESCE(( SELECT sum(payments_invoices_fees_installments.amount) AS sum
               FROM shulesoft.payments_invoices_fees_installments
              WHERE (payments_invoices_fees_installments.invoices_fees_installment_id = g.id)), (0)::numeric)) - COALESCE(i.total_advance_invoice_fee_amount, (0)::numeric)) - COALESCE(d.amount, (0)::numeric)) > (0)::numeric) THEN 0
            ELSE 1
        END AS status,
    (((COALESCE(d.amount, (0)::numeric) - COALESCE(( SELECT sum(payments_invoices_fees_installments.amount) AS sum
           FROM shulesoft.payments_invoices_fees_installments
          WHERE (payments_invoices_fees_installments.invoices_fees_installment_id = g.id)), (0)::numeric)) - COALESCE(i.total_advance_invoice_fee_amount, (0)::numeric)) - COALESCE(k.amount, (0)::numeric)) AS balance,
    e.end_date,
    b.schema_name
   FROM (((((((((shulesoft.invoices f
     JOIN shulesoft.invoices_fees_installments g ON ((g.invoice_id = f.id)))
     JOIN shulesoft.transport_routes_fees_installments a ON ((a.fees_installment_id = g.fees_installment_id)))
     JOIN shulesoft.transport_routes b ON ((b.id = a.transport_route_id)))
     JOIN shulesoft.fees_installments c ON ((c.id = a.fees_installment_id)))
     JOIN shulesoft.fees z ON (((z.id = c.fee_id) AND (lower((z.name)::text) ~~ '%transport%'::text) AND ((z.schema_name)::text = (c.schema_name)::text))))
     JOIN shulesoft.tmembers d ON (((d.transport_route_id = a.transport_route_id) AND (d.student_id = f.student_id) AND (d.installment_id = c.installment_id))))
     LEFT JOIN shulesoft.total_advance_invoice_fee_amount i ON ((i.invoices_fees_installments_id = g.id)))
     LEFT JOIN shulesoft.discount_fees_installments k ON (((k.fees_installment_id = c.id) AND (k.student_id = f.student_id))))
     JOIN shulesoft.installments e ON ((e.id = c.installment_id)))
  WHERE (d.amount > (0)::numeric);


ALTER VIEW shulesoft.school_transport_invoices OWNER TO postgres;

--
-- TOC entry 2049 (class 1259 OID 50826)
-- Name: invoice_balance; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.invoice_balance AS
 SELECT school_basic_invoices.total_amount,
    school_basic_invoices.total_payment_invoice_fee_amount,
    school_basic_invoices.total_advance_invoice_fee_amount,
    school_basic_invoices.discount_amount,
    school_basic_invoices.student_id,
    school_basic_invoices.created_at,
    school_basic_invoices.invoices_fees_installments_id,
    school_basic_invoices.advance_amount,
    school_basic_invoices.fees_installment_id,
    school_basic_invoices.installment_id,
    school_basic_invoices.start_date,
    school_basic_invoices.academic_year_id,
    school_basic_invoices.fee_id,
    school_basic_invoices.invoice_id,
    school_basic_invoices.status,
    school_basic_invoices.balance,
    school_basic_invoices.end_date,
    school_basic_invoices.schema_name
   FROM shulesoft.school_basic_invoices
UNION ALL
 SELECT school_transport_invoices.total_amount,
    school_transport_invoices.total_payment_invoice_fee_amount,
    school_transport_invoices.total_advance_invoice_fee_amount,
    school_transport_invoices.discount_amount,
    school_transport_invoices.student_id,
    school_transport_invoices.created_at,
    school_transport_invoices.invoices_fees_installments_id,
    school_transport_invoices.advance_amount,
    school_transport_invoices.fees_installment_id,
    school_transport_invoices.installment_id,
    school_transport_invoices.start_date,
    school_transport_invoices.academic_year_id,
    school_transport_invoices.fee_id,
    school_transport_invoices.invoice_id,
    school_transport_invoices.status,
    school_transport_invoices.balance,
    school_transport_invoices.end_date,
    school_transport_invoices.schema_name
   FROM shulesoft.school_transport_invoices
UNION ALL
 SELECT school_hostel_invoices2.total_amount,
    school_hostel_invoices2.total_payment_invoice_fee_amount,
    school_hostel_invoices2.total_advance_invoice_fee_amount,
    school_hostel_invoices2.discount_amount,
    school_hostel_invoices2.student_id,
    school_hostel_invoices2.created_at,
    school_hostel_invoices2.invoices_fees_installments_id,
    school_hostel_invoices2.advance_amount,
    school_hostel_invoices2.fees_installment_id,
    school_hostel_invoices2.installment_id,
    school_hostel_invoices2.start_date,
    school_hostel_invoices2.academic_year_id,
    school_hostel_invoices2.fee_id,
    school_hostel_invoices2.invoice_id,
    school_hostel_invoices2.status,
    school_hostel_invoices2.balance,
    school_hostel_invoices2.end_date,
    school_hostel_invoices2.schema_name
   FROM shulesoft.school_hostel_invoices2;


ALTER VIEW shulesoft.invoice_balance OWNER TO postgres;

--
-- TOC entry 2050 (class 1259 OID 50831)
-- Name: fees_invoiced_amount; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.fees_invoiced_amount AS
 SELECT DISTINCT invoice_id,
    (sum(total_amount) - sum(discount_amount)) AS amount,
    fee_id,
    installment_id,
    schema_name,
    student_id
   FROM shulesoft.invoice_balance a
  GROUP BY invoice_id, fee_id, installment_id, schema_name, student_id
 HAVING (sum(total_amount) > (0)::numeric);


ALTER VIEW shulesoft.fees_invoiced_amount OWNER TO postgres;

--
-- TOC entry 2051 (class 1259 OID 50835)
-- Name: fees_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.fees_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.fees_uid_seq OWNER TO postgres;

--
-- TOC entry 14098 (class 0 OID 0)
-- Dependencies: 2051
-- Name: fees_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.fees_uid_seq OWNED BY shulesoft.fees.uid;


--
-- TOC entry 2052 (class 1259 OID 50836)
-- Name: file_folder_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.file_folder_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.file_folder_id_seq OWNER TO postgres;

--
-- TOC entry 2053 (class 1259 OID 50837)
-- Name: file_folder; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.file_folder (
    id integer DEFAULT nextval('shulesoft.file_folder_id_seq'::regclass) NOT NULL,
    user_id integer NOT NULL,
    "table" character varying(20) NOT NULL,
    name character varying(255) NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.file_folder OWNER TO postgres;

--
-- TOC entry 2054 (class 1259 OID 50845)
-- Name: file_folder_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.file_folder_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.file_folder_uid_seq OWNER TO postgres;

--
-- TOC entry 14099 (class 0 OID 0)
-- Dependencies: 2054
-- Name: file_folder_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.file_folder_uid_seq OWNED BY shulesoft.file_folder.uid;


--
-- TOC entry 2055 (class 1259 OID 50846)
-- Name: file_share_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.file_share_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.file_share_id_seq OWNER TO postgres;

--
-- TOC entry 2056 (class 1259 OID 50847)
-- Name: file_share; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.file_share (
    id integer DEFAULT nextval('shulesoft.file_share_id_seq'::regclass) NOT NULL,
    "classesID" integer,
    public integer NOT NULL,
    file_or_folder integer NOT NULL,
    item_id integer NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.file_share OWNER TO postgres;

--
-- TOC entry 2057 (class 1259 OID 50855)
-- Name: file_share_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.file_share_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.file_share_uid_seq OWNER TO postgres;

--
-- TOC entry 14100 (class 0 OID 0)
-- Dependencies: 2057
-- Name: file_share_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.file_share_uid_seq OWNED BY shulesoft.file_share.uid;


--
-- TOC entry 2058 (class 1259 OID 50856)
-- Name: files_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.files_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.files_id_seq OWNER TO postgres;

--
-- TOC entry 2059 (class 1259 OID 50857)
-- Name: files; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.files (
    id integer DEFAULT nextval('shulesoft.files_id_seq'::regclass) NOT NULL,
    mime text,
    file_folder_id integer DEFAULT 0,
    name character varying(255) NOT NULL,
    display_name character varying(255) NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    user_id integer,
    "table" character varying,
    size integer,
    caption character varying,
    path character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.files OWNER TO postgres;

--
-- TOC entry 2060 (class 1259 OID 50866)
-- Name: files_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.files_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.files_uid_seq OWNER TO postgres;

--
-- TOC entry 14101 (class 0 OID 0)
-- Dependencies: 2060
-- Name: files_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.files_uid_seq OWNED BY shulesoft.files.uid;


--
-- TOC entry 2061 (class 1259 OID 50867)
-- Name: financial_year_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.financial_year_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.financial_year_id_seq OWNER TO postgres;

--
-- TOC entry 2062 (class 1259 OID 50868)
-- Name: financial_year; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.financial_year (
    id integer DEFAULT nextval('shulesoft.financial_year_id_seq'::regclass) NOT NULL,
    name character varying(100) NOT NULL,
    status integer,
    start_date date,
    end_date date,
    created_at timestamp without time zone DEFAULT ('now'::text)::timestamp(0) without time zone NOT NULL,
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    CONSTRAINT financial_year_date_check CHECK ((start_date < end_date))
);


ALTER TABLE shulesoft.financial_year OWNER TO postgres;

--
-- TOC entry 2063 (class 1259 OID 50877)
-- Name: financial_year_payments; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.financial_year_payments AS
 SELECT id,
    student_id,
    amount,
    payment_type_id,
    date,
    transaction_id,
    created_at,
    cheque_number,
    bank_account_id,
    payer_name,
    mobile_transaction_id,
    transaction_time,
    account_number,
    token,
    reconciled,
    receipt_code,
    updated_at,
    channel,
    amount_entered,
    created_by,
    created_by_table,
    note,
    invoice_id,
    status,
    sid,
    priority,
    comment
   FROM shulesoft.payments
  WHERE (date > ( SELECT max(closing_year_balance.date) AS max
           FROM shulesoft.closing_year_balance));


ALTER VIEW shulesoft.financial_year_payments OWNER TO postgres;

--
-- TOC entry 2064 (class 1259 OID 50882)
-- Name: product_cart_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.product_cart_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.product_cart_id_seq OWNER TO postgres;

--
-- TOC entry 2065 (class 1259 OID 50883)
-- Name: product_cart; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.product_cart (
    id integer DEFAULT nextval('shulesoft.product_cart_id_seq'::regclass) NOT NULL,
    name character varying,
    product_alert_id integer,
    revenue_id integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    quantity integer,
    amount numeric,
    date date,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.product_cart OWNER TO postgres;

--
-- TOC entry 2066 (class 1259 OID 50891)
-- Name: financial_year_product_cart; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.financial_year_product_cart AS
 SELECT id,
    name,
    product_alert_id,
    revenue_id,
    created_at,
    updated_at,
    quantity,
    amount,
    date
   FROM shulesoft.product_cart
  WHERE (date > ( SELECT max(closing_year_balance.date) AS max
           FROM shulesoft.closing_year_balance));


ALTER VIEW shulesoft.financial_year_product_cart OWNER TO postgres;

--
-- TOC entry 2030 (class 1259 OID 50752)
-- Name: product_purchases_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.product_purchases_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.product_purchases_id_seq OWNER TO postgres;

--
-- TOC entry 2031 (class 1259 OID 50753)
-- Name: product_purchases; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.product_purchases (
    id integer DEFAULT nextval('shulesoft.product_purchases_id_seq'::regclass) NOT NULL,
    created_by integer,
    product_alert_id integer,
    quantity double precision,
    amount numeric,
    vendor_id integer,
    created_at timestamp without time zone,
    note text,
    created_by_table character varying,
    updated_at timestamp without time zone,
    expense_id integer,
    date date,
    unit_price numeric,
    status smallint,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.product_purchases OWNER TO postgres;

--
-- TOC entry 2067 (class 1259 OID 50895)
-- Name: financial_year_product_purchases; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.financial_year_product_purchases AS
 SELECT id,
    created_by,
    product_alert_id,
    quantity,
    amount,
    vendor_id,
    created_at,
    note,
    created_by_table,
    updated_at,
    expense_id,
    date,
    unit_price,
    status
   FROM shulesoft.product_purchases
  WHERE (date > ( SELECT max(closing_year_balance.date) AS max
           FROM shulesoft.closing_year_balance));


ALTER VIEW shulesoft.financial_year_product_purchases OWNER TO postgres;

--
-- TOC entry 2068 (class 1259 OID 50900)
-- Name: financial_year_revenues; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.financial_year_revenues AS
 SELECT a.id,
    a.payer_name,
    a.payer_phone,
    a.payer_email,
    a.refer_expense_id,
    a.amount,
    a.created_by_id,
    a.created_by_table,
    a.created_at,
    a.updated_at,
    a.payment_method,
    a.transaction_id,
    a.bank_account_id,
    a.invoice_number,
    a.note,
    a.date,
    a.user_in_shulesoft,
    a.user_id,
    a.user_table,
    a.reconciled,
    a.number,
    a.payment_type_id,
    a.loan_application_id
   FROM (shulesoft.revenues a
     JOIN shulesoft.refer_expense b ON ((b.id = a.refer_expense_id)))
  WHERE (a.date > ( SELECT max(closing_year_balance.date) AS max
           FROM shulesoft.closing_year_balance));


ALTER VIEW shulesoft.financial_year_revenues OWNER TO postgres;

--
-- TOC entry 2069 (class 1259 OID 50905)
-- Name: financial_year_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.financial_year_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.financial_year_uid_seq OWNER TO postgres;

--
-- TOC entry 14102 (class 0 OID 0)
-- Dependencies: 2069
-- Name: financial_year_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.financial_year_uid_seq OWNED BY shulesoft.financial_year.uid;


--
-- TOC entry 2070 (class 1259 OID 50906)
-- Name: fixed_assets; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.fixed_assets (
    id integer NOT NULL,
    uid integer NOT NULL,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    name character varying,
    asset_number character varying,
    refer_expense_id integer,
    account_id integer,
    category character varying,
    transaction_id character varying,
    reference character varying,
    amount numeric,
    vendor_id integer,
    created_by_id integer,
    depreciation numeric,
    note text,
    reconciled smallint DEFAULT 0,
    number integer DEFAULT nextval('public.revenues_number_seq'::regclass) NOT NULL,
    date timestamp without time zone,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    schema_name character varying,
    status integer DEFAULT 1 NOT NULL
);


ALTER TABLE shulesoft.fixed_assets OWNER TO postgres;

--
-- TOC entry 2071 (class 1259 OID 50916)
-- Name: fixed_assets_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.fixed_assets_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.fixed_assets_id_seq OWNER TO postgres;

--
-- TOC entry 14103 (class 0 OID 0)
-- Dependencies: 2071
-- Name: fixed_assets_id_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.fixed_assets_id_seq OWNED BY shulesoft.fixed_assets.id;


--
-- TOC entry 2072 (class 1259 OID 50917)
-- Name: fixed_assets_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.fixed_assets_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.fixed_assets_uid_seq OWNER TO postgres;

--
-- TOC entry 14104 (class 0 OID 0)
-- Dependencies: 2072
-- Name: fixed_assets_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.fixed_assets_uid_seq OWNED BY shulesoft.fixed_assets.uid;


--
-- TOC entry 2073 (class 1259 OID 50918)
-- Name: forum_answer_votes_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.forum_answer_votes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.forum_answer_votes_id_seq OWNER TO postgres;

--
-- TOC entry 2074 (class 1259 OID 50919)
-- Name: forum_answer_votes; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.forum_answer_votes (
    id integer DEFAULT nextval('shulesoft.forum_answer_votes_id_seq'::regclass) NOT NULL,
    forum_answer_id integer,
    vote_type integer,
    created_by integer,
    created_by_table character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.forum_answer_votes OWNER TO postgres;

--
-- TOC entry 2075 (class 1259 OID 50927)
-- Name: forum_answer_votes_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.forum_answer_votes_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.forum_answer_votes_uid_seq OWNER TO postgres;

--
-- TOC entry 14105 (class 0 OID 0)
-- Dependencies: 2075
-- Name: forum_answer_votes_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.forum_answer_votes_uid_seq OWNED BY shulesoft.forum_answer_votes.uid;


--
-- TOC entry 2076 (class 1259 OID 50928)
-- Name: forum_answers_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.forum_answers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.forum_answers_id_seq OWNER TO postgres;

--
-- TOC entry 2077 (class 1259 OID 50929)
-- Name: forum_answers; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.forum_answers (
    id integer DEFAULT nextval('shulesoft.forum_answers_id_seq'::regclass) NOT NULL,
    forum_question_id integer,
    answer text,
    created_by integer,
    created_by_table character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    is_correct smallint DEFAULT 0,
    teacher_id integer,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.forum_answers OWNER TO postgres;

--
-- TOC entry 2078 (class 1259 OID 50938)
-- Name: forum_answers_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.forum_answers_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.forum_answers_uid_seq OWNER TO postgres;

--
-- TOC entry 14106 (class 0 OID 0)
-- Dependencies: 2078
-- Name: forum_answers_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.forum_answers_uid_seq OWNED BY shulesoft.forum_answers.uid;


--
-- TOC entry 2079 (class 1259 OID 50939)
-- Name: forum_categories_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.forum_categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.forum_categories_id_seq OWNER TO postgres;

--
-- TOC entry 2080 (class 1259 OID 50940)
-- Name: forum_categories; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.forum_categories (
    id integer DEFAULT nextval('shulesoft.forum_categories_id_seq'::regclass) NOT NULL,
    parent_id integer,
    class_id integer,
    "order" integer DEFAULT 1 NOT NULL,
    name character varying(255) NOT NULL,
    color character varying(20) NOT NULL,
    slug character varying(255) NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.forum_categories OWNER TO postgres;

--
-- TOC entry 2081 (class 1259 OID 50948)
-- Name: forum_categories_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.forum_categories_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.forum_categories_uid_seq OWNER TO postgres;

--
-- TOC entry 14107 (class 0 OID 0)
-- Dependencies: 2081
-- Name: forum_categories_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.forum_categories_uid_seq OWNED BY shulesoft.forum_categories.uid;


--
-- TOC entry 2082 (class 1259 OID 50949)
-- Name: forum_discussion_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.forum_discussion_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.forum_discussion_id_seq OWNER TO postgres;

--
-- TOC entry 2083 (class 1259 OID 50950)
-- Name: forum_discussion; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.forum_discussion (
    id integer DEFAULT nextval('shulesoft.forum_discussion_id_seq'::regclass) NOT NULL,
    forum_category_id integer DEFAULT 1 NOT NULL,
    title character varying(255) NOT NULL,
    user_table character varying(255) NOT NULL,
    user_id integer NOT NULL,
    sticky boolean DEFAULT false NOT NULL,
    views integer DEFAULT 0 NOT NULL,
    answered boolean DEFAULT false NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    slug character varying(255) NOT NULL,
    color character varying(20) DEFAULT '#232629'::character varying,
    deleted_at timestamp without time zone,
    last_reply_at timestamp without time zone DEFAULT now() NOT NULL,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.forum_discussion OWNER TO postgres;

--
-- TOC entry 2084 (class 1259 OID 50963)
-- Name: forum_discussion_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.forum_discussion_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.forum_discussion_uid_seq OWNER TO postgres;

--
-- TOC entry 14108 (class 0 OID 0)
-- Dependencies: 2084
-- Name: forum_discussion_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.forum_discussion_uid_seq OWNED BY shulesoft.forum_discussion.uid;


--
-- TOC entry 2085 (class 1259 OID 50964)
-- Name: forum_post_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.forum_post_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.forum_post_id_seq OWNER TO postgres;

--
-- TOC entry 2086 (class 1259 OID 50965)
-- Name: forum_post; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.forum_post (
    id integer DEFAULT nextval('shulesoft.forum_post_id_seq'::regclass) NOT NULL,
    forum_discussion_id integer NOT NULL,
    user_id integer NOT NULL,
    user_table character varying(255) NOT NULL,
    body text NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    markdown boolean DEFAULT false NOT NULL,
    locked boolean DEFAULT false NOT NULL,
    deleted_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.forum_post OWNER TO postgres;

--
-- TOC entry 2087 (class 1259 OID 50974)
-- Name: forum_post_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.forum_post_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.forum_post_uid_seq OWNER TO postgres;

--
-- TOC entry 14109 (class 0 OID 0)
-- Dependencies: 2087
-- Name: forum_post_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.forum_post_uid_seq OWNED BY shulesoft.forum_post.uid;


--
-- TOC entry 2088 (class 1259 OID 50975)
-- Name: forum_question_viewers_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.forum_question_viewers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.forum_question_viewers_id_seq OWNER TO postgres;

--
-- TOC entry 2089 (class 1259 OID 50976)
-- Name: forum_question_viewers; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.forum_question_viewers (
    id integer DEFAULT nextval('shulesoft.forum_question_viewers_id_seq'::regclass) NOT NULL,
    forum_question_id integer,
    created_by integer,
    created_by_table character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.forum_question_viewers OWNER TO postgres;

--
-- TOC entry 2090 (class 1259 OID 50984)
-- Name: forum_question_viewers_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.forum_question_viewers_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.forum_question_viewers_uid_seq OWNER TO postgres;

--
-- TOC entry 14110 (class 0 OID 0)
-- Dependencies: 2090
-- Name: forum_question_viewers_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.forum_question_viewers_uid_seq OWNED BY shulesoft.forum_question_viewers.uid;


--
-- TOC entry 2091 (class 1259 OID 50985)
-- Name: forum_questions_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.forum_questions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.forum_questions_id_seq OWNER TO postgres;

--
-- TOC entry 2092 (class 1259 OID 50986)
-- Name: forum_questions; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.forum_questions (
    id integer DEFAULT nextval('shulesoft.forum_questions_id_seq'::regclass) NOT NULL,
    syllabus_topic_id integer,
    title character varying,
    question text,
    created_by integer,
    created_by_table character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    status smallint DEFAULT 0 NOT NULL,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.forum_questions OWNER TO postgres;

--
-- TOC entry 2093 (class 1259 OID 50995)
-- Name: forum_questions_comments_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.forum_questions_comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.forum_questions_comments_id_seq OWNER TO postgres;

--
-- TOC entry 2094 (class 1259 OID 50996)
-- Name: forum_questions_comments; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.forum_questions_comments (
    id integer DEFAULT nextval('shulesoft.forum_questions_comments_id_seq'::regclass) NOT NULL,
    forum_question_id integer,
    content text,
    created_by integer,
    created_by_table character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.forum_questions_comments OWNER TO postgres;

--
-- TOC entry 2095 (class 1259 OID 51004)
-- Name: forum_questions_comments_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.forum_questions_comments_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.forum_questions_comments_uid_seq OWNER TO postgres;

--
-- TOC entry 14111 (class 0 OID 0)
-- Dependencies: 2095
-- Name: forum_questions_comments_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.forum_questions_comments_uid_seq OWNED BY shulesoft.forum_questions_comments.uid;


--
-- TOC entry 2096 (class 1259 OID 51005)
-- Name: forum_questions_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.forum_questions_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.forum_questions_uid_seq OWNER TO postgres;

--
-- TOC entry 14112 (class 0 OID 0)
-- Dependencies: 2096
-- Name: forum_questions_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.forum_questions_uid_seq OWNED BY shulesoft.forum_questions.uid;


--
-- TOC entry 2097 (class 1259 OID 51006)
-- Name: forum_questions_votes_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.forum_questions_votes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.forum_questions_votes_id_seq OWNER TO postgres;

--
-- TOC entry 2098 (class 1259 OID 51007)
-- Name: forum_questions_votes; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.forum_questions_votes (
    id integer DEFAULT nextval('shulesoft.forum_questions_votes_id_seq'::regclass) NOT NULL,
    forum_question_answer_id integer,
    vote_type integer,
    created_by integer,
    created_by_table character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.forum_questions_votes OWNER TO postgres;

--
-- TOC entry 2099 (class 1259 OID 51015)
-- Name: forum_questions_votes_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.forum_questions_votes_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.forum_questions_votes_uid_seq OWNER TO postgres;

--
-- TOC entry 14113 (class 0 OID 0)
-- Dependencies: 2099
-- Name: forum_questions_votes_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.forum_questions_votes_uid_seq OWNED BY shulesoft.forum_questions_votes.uid;


--
-- TOC entry 2100 (class 1259 OID 51016)
-- Name: forum_user_discussion_user_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.forum_user_discussion_user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.forum_user_discussion_user_id_seq OWNER TO postgres;

--
-- TOC entry 2101 (class 1259 OID 51017)
-- Name: forum_user_discussion; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.forum_user_discussion (
    user_id integer DEFAULT nextval('shulesoft.forum_user_discussion_user_id_seq'::regclass) NOT NULL,
    user_table character varying(255) NOT NULL,
    discussion_id integer NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.forum_user_discussion OWNER TO postgres;

--
-- TOC entry 2102 (class 1259 OID 51025)
-- Name: forum_user_discussion_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.forum_user_discussion_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.forum_user_discussion_uid_seq OWNER TO postgres;

--
-- TOC entry 14114 (class 0 OID 0)
-- Dependencies: 2102
-- Name: forum_user_discussion_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.forum_user_discussion_uid_seq OWNED BY shulesoft.forum_user_discussion.uid;


--
-- TOC entry 1783 (class 1259 OID 49689)
-- Name: general_character_assessment_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.general_character_assessment_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.general_character_assessment_id_seq OWNER TO postgres;

--
-- TOC entry 1784 (class 1259 OID 49690)
-- Name: general_character_assessment; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.general_character_assessment (
    id integer DEFAULT nextval('shulesoft.general_character_assessment_id_seq'::regclass) NOT NULL,
    student_id integer NOT NULL,
    semester_id integer,
    class_teacher_id integer NOT NULL,
    class_teacher_comment text,
    head_teacher_id integer,
    head_teacher_comment text,
    class_teacher_created_at timestamp without time zone,
    head_teacher_created_at timestamp without time zone,
    class_teacher_updated_at timestamp without time zone,
    head_teacher_updated_at timestamp without time zone,
    exam_id integer,
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.general_character_assessment OWNER TO postgres;

--
-- TOC entry 2103 (class 1259 OID 51026)
-- Name: general_character_assessment_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.general_character_assessment_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.general_character_assessment_uid_seq OWNER TO postgres;

--
-- TOC entry 14115 (class 0 OID 0)
-- Dependencies: 2103
-- Name: general_character_assessment_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.general_character_assessment_uid_seq OWNED BY shulesoft.general_character_assessment.uid;


--
-- TOC entry 2104 (class 1259 OID 51027)
-- Name: grade_gradeID_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft."grade_gradeID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft."grade_gradeID_seq" OWNER TO postgres;

--
-- TOC entry 2105 (class 1259 OID 51028)
-- Name: grade; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.grade (
    "gradeID" integer DEFAULT nextval('shulesoft."grade_gradeID_seq"'::regclass) NOT NULL,
    grade character varying(60) NOT NULL,
    point character varying(11) NOT NULL,
    gradefrom integer NOT NULL,
    gradeupto integer NOT NULL,
    note text,
    created_at timestamp without time zone DEFAULT now(),
    classlevel_id integer,
    overall_note text,
    overall_academic_note character varying,
    gpa_points double precision DEFAULT 0,
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.grade OWNER TO postgres;

--
-- TOC entry 2106 (class 1259 OID 51037)
-- Name: grade_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.grade_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.grade_uid_seq OWNER TO postgres;

--
-- TOC entry 14116 (class 0 OID 0)
-- Dependencies: 2106
-- Name: grade_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.grade_uid_seq OWNED BY shulesoft.grade.uid;


--
-- TOC entry 2107 (class 1259 OID 51038)
-- Name: hattendances_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.hattendances_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.hattendances_id_seq OWNER TO postgres;

--
-- TOC entry 2108 (class 1259 OID 51039)
-- Name: hattendances; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.hattendances (
    id integer DEFAULT nextval('shulesoft.hattendances_id_seq'::regclass) NOT NULL,
    student_id integer,
    created_by integer,
    created_by_table character varying,
    date date,
    present smallint DEFAULT 0,
    absent_reason character varying,
    absent_reason_id integer,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    timeout timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.hattendances OWNER TO postgres;

--
-- TOC entry 2109 (class 1259 OID 51047)
-- Name: hattendances_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.hattendances_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.hattendances_uid_seq OWNER TO postgres;

--
-- TOC entry 14117 (class 0 OID 0)
-- Dependencies: 2109
-- Name: hattendances_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.hattendances_uid_seq OWNED BY shulesoft.hattendances.uid;


--
-- TOC entry 2110 (class 1259 OID 51048)
-- Name: hmembers_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.hmembers_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.hmembers_uid_seq OWNER TO postgres;

--
-- TOC entry 14118 (class 0 OID 0)
-- Dependencies: 2110
-- Name: hmembers_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.hmembers_uid_seq OWNED BY shulesoft.hmembers.uid;


--
-- TOC entry 2111 (class 1259 OID 51049)
-- Name: hostel_beds_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.hostel_beds_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.hostel_beds_id_seq OWNER TO postgres;

--
-- TOC entry 2112 (class 1259 OID 51050)
-- Name: hostel_beds; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.hostel_beds (
    id integer DEFAULT nextval('shulesoft.hostel_beds_id_seq'::regclass) NOT NULL,
    name character varying,
    hostel_id integer NOT NULL,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.hostel_beds OWNER TO postgres;

--
-- TOC entry 2113 (class 1259 OID 51058)
-- Name: hostel_beds_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.hostel_beds_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.hostel_beds_uid_seq OWNER TO postgres;

--
-- TOC entry 14119 (class 0 OID 0)
-- Dependencies: 2113
-- Name: hostel_beds_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.hostel_beds_uid_seq OWNED BY shulesoft.hostel_beds.uid;


--
-- TOC entry 2114 (class 1259 OID 51059)
-- Name: hostel_category_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.hostel_category_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.hostel_category_id_seq OWNER TO postgres;

--
-- TOC entry 2115 (class 1259 OID 51060)
-- Name: hostel_category; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.hostel_category (
    id integer DEFAULT nextval('shulesoft.hostel_category_id_seq'::regclass) NOT NULL,
    hostel_id integer NOT NULL,
    class_type character varying(60) NOT NULL,
    hbalance character varying(20) NOT NULL,
    note text,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.hostel_category OWNER TO postgres;

--
-- TOC entry 2116 (class 1259 OID 51068)
-- Name: hostel_category_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.hostel_category_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.hostel_category_uid_seq OWNER TO postgres;

--
-- TOC entry 14120 (class 0 OID 0)
-- Dependencies: 2116
-- Name: hostel_category_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.hostel_category_uid_seq OWNED BY shulesoft.hostel_category.uid;


--
-- TOC entry 2117 (class 1259 OID 51069)
-- Name: hostel_fees_installments_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.hostel_fees_installments_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.hostel_fees_installments_uid_seq OWNER TO postgres;

--
-- TOC entry 14121 (class 0 OID 0)
-- Dependencies: 2117
-- Name: hostel_fees_installments_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.hostel_fees_installments_uid_seq OWNED BY shulesoft.hostel_fees_installments.uid;


--
-- TOC entry 2118 (class 1259 OID 51070)
-- Name: hostel_info; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.hostel_info AS
 SELECT b.hostel_id,
    a.student_id,
    a.id,
    b.amount,
    b.fees_installment_id,
    COALESCE(c.amount, (0)::numeric(10,2)) AS discount,
    d.name,
    a.installment_id,
    a.schema_name,
    a.bed_id
   FROM ((((shulesoft.hmembers a
     JOIN shulesoft.fees_installments f ON (((f.installment_id = a.installment_id) AND ((a.schema_name)::text = (f.schema_name)::text))))
     JOIN shulesoft.hostel_fees_installments b ON (((b.fees_installment_id = f.id) AND (b.hostel_id = a.hostel_id) AND ((b.schema_name)::text = (f.schema_name)::text))))
     LEFT JOIN shulesoft.discount_fees_installments c ON (((c.fees_installment_id = b.fees_installment_id) AND (a.student_id = c.student_id) AND ((a.schema_name)::text = (c.schema_name)::text))))
     JOIN shulesoft.hostels d ON (((d.id = a.hostel_id) AND ((d.schema_name)::text = (a.schema_name)::text))))
  GROUP BY b.hostel_id, a.student_id, b.amount, b.fees_installment_id, c.amount, d.name, a.id, a.installment_id, a.schema_name, a.bed_id
  ORDER BY a.student_id DESC;


ALTER VIEW shulesoft.hostel_info OWNER TO postgres;

--
-- TOC entry 2119 (class 1259 OID 51075)
-- Name: hostels_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.hostels_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.hostels_uid_seq OWNER TO postgres;

--
-- TOC entry 14122 (class 0 OID 0)
-- Dependencies: 2119
-- Name: hostels_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.hostels_uid_seq OWNED BY shulesoft.hostels.uid;


--
-- TOC entry 2120 (class 1259 OID 51076)
-- Name: id_cards_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.id_cards_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.id_cards_id_seq OWNER TO postgres;

--
-- TOC entry 2121 (class 1259 OID 51077)
-- Name: id_cards; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.id_cards (
    id integer DEFAULT nextval('shulesoft.id_cards_id_seq'::regclass) NOT NULL,
    show_gender smallint DEFAULT 1,
    show_birthday smallint DEFAULT 1,
    show_admission_number smallint DEFAULT 1,
    show_class smallint DEFAULT 1,
    show_issue_date smallint DEFAULT 1,
    show_barcode smallint DEFAULT 1,
    show_payment_status smallint DEFAULT 1,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    show_watermark smallint DEFAULT 1,
    show_year_range smallint DEFAULT 0,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    show_id_validity smallint DEFAULT 0,
    id_validity character varying(100)
);


ALTER TABLE shulesoft.id_cards OWNER TO postgres;

--
-- TOC entry 2122 (class 1259 OID 51095)
-- Name: id_cards_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.id_cards_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.id_cards_uid_seq OWNER TO postgres;

--
-- TOC entry 14123 (class 0 OID 0)
-- Dependencies: 2122
-- Name: id_cards_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.id_cards_uid_seq OWNED BY shulesoft.id_cards.uid;


--
-- TOC entry 2123 (class 1259 OID 51096)
-- Name: installment_packages; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.installment_packages (
    id integer NOT NULL,
    name character varying,
    number_of_installments integer,
    details text,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    schema_name character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL
);


ALTER TABLE shulesoft.installment_packages OWNER TO postgres;

--
-- TOC entry 2124 (class 1259 OID 51103)
-- Name: installment_packages_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.installment_packages_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.installment_packages_id_seq OWNER TO postgres;

--
-- TOC entry 14124 (class 0 OID 0)
-- Dependencies: 2124
-- Name: installment_packages_id_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.installment_packages_id_seq OWNED BY shulesoft.installment_packages.id;


--
-- TOC entry 1796 (class 1259 OID 49756)
-- Name: parent_parentID_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft."parent_parentID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft."parent_parentID_seq" OWNER TO postgres;

--
-- TOC entry 1797 (class 1259 OID 49757)
-- Name: parent; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.parent (
    "parentID" integer DEFAULT nextval('shulesoft."parent_parentID_seq"'::regclass) NOT NULL,
    name character varying(60) NOT NULL,
    father_name character varying(60),
    mother_name character varying(60),
    father_profession character varying(40),
    mother_profession character varying(40),
    email character varying(200) DEFAULT 'default_parent@shulesoft.com'::character varying,
    phone text,
    address text,
    photo character varying(200) NOT NULL,
    username character varying(40) NOT NULL,
    password character varying(128) NOT NULL,
    usertype character varying(20) NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    other_phone character varying(25),
    status integer DEFAULT 1,
    employer character varying(500),
    guardian_profession character varying,
    professionals character varying,
    dob date,
    sex character varying,
    relation character varying,
    physical_condition_id integer,
    default_password character varying,
    updated_at timestamp without time zone,
    language character varying(10) DEFAULT 'kisw'::character varying,
    denomination character varying,
    box character varying,
    remember_token character varying(255),
    profession_id integer,
    status_id integer,
    location character varying,
    region character varying,
    sid integer DEFAULT nextval('public.unique_identifier_seq'::regclass) NOT NULL,
    employer_type_id integer,
    city_id integer,
    signature character varying,
    national_id character varying,
    country_id integer,
    email_valid smallint,
    payroll_status smallint DEFAULT 1 NOT NULL,
    fcm_token character varying,
    qr_code text,
    login_code character varying,
    expire_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.parent OWNER TO postgres;

--
-- TOC entry 2125 (class 1259 OID 51104)
-- Name: student_parents_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_parents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.student_parents_id_seq OWNER TO postgres;

--
-- TOC entry 2126 (class 1259 OID 51105)
-- Name: student_parents; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.student_parents (
    id integer DEFAULT nextval('shulesoft.student_parents_id_seq'::regclass) NOT NULL,
    student_id integer,
    parent_id integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.student_parents OWNER TO postgres;

--
-- TOC entry 2127 (class 1259 OID 51113)
-- Name: installment_reminders; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.installment_reminders AS
 SELECT a.student_id,
    b.name AS student,
    c.name AS parent,
    c.phone AS phone_number,
    a.balance AS unpaid_amount,
    f.name,
    f.start_date,
    f.end_date,
        CASE
            WHEN (f.end_date <= now()) THEN 0
            ELSE 1
        END AS status
   FROM ((((shulesoft.invoice_balances a
     JOIN shulesoft.student b ON ((b.student_id = a.student_id)))
     JOIN shulesoft.student_parents d ON ((b.student_id = d.student_id)))
     JOIN shulesoft.parent c ON ((c."parentID" = d.parent_id)))
     JOIN shulesoft.installments f ON ((f.id = a.installment_id)))
  WHERE (a.balance > (0)::numeric)
  ORDER BY a.student_id;


ALTER VIEW shulesoft.installment_reminders OWNER TO postgres;

--
-- TOC entry 2128 (class 1259 OID 51118)
-- Name: installments_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.installments_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.installments_uid_seq OWNER TO postgres;

--
-- TOC entry 14125 (class 0 OID 0)
-- Dependencies: 2128
-- Name: installments_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.installments_uid_seq OWNED BY shulesoft.installments.uid;


--
-- TOC entry 2129 (class 1259 OID 51119)
-- Name: invoice_prefix_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.invoice_prefix_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.invoice_prefix_id_seq OWNER TO postgres;

--
-- TOC entry 2130 (class 1259 OID 51120)
-- Name: invoice_prefix; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.invoice_prefix (
    id integer DEFAULT nextval('shulesoft.invoice_prefix_id_seq'::regclass) NOT NULL,
    invoice_id integer,
    bank_accounts_integration_id integer,
    reference character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    sync smallint DEFAULT 0,
    return_message text,
    status smallint DEFAULT 0 NOT NULL,
    push_status character varying,
    prefix character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.invoice_prefix OWNER TO postgres;

--
-- TOC entry 2131 (class 1259 OID 51130)
-- Name: invoice_prefix_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.invoice_prefix_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.invoice_prefix_uid_seq OWNER TO postgres;

--
-- TOC entry 14126 (class 0 OID 0)
-- Dependencies: 2131
-- Name: invoice_prefix_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.invoice_prefix_uid_seq OWNED BY shulesoft.invoice_prefix.uid;


--
-- TOC entry 2132 (class 1259 OID 51131)
-- Name: invoice_settings_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.invoice_settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.invoice_settings_id_seq OWNER TO postgres;

--
-- TOC entry 2133 (class 1259 OID 51132)
-- Name: invoice_settings; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.invoice_settings (
    id integer DEFAULT nextval('shulesoft.invoice_settings_id_seq'::regclass) NOT NULL,
    title character varying,
    reference_naming character varying,
    show_banks smallint DEFAULT 1,
    show_payment_plan smallint DEFAULT 1,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    show_different_header integer DEFAULT 0,
    show_mobile_payment smallint DEFAULT 1,
    show_other_student_transaction smallint DEFAULT 0,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.invoice_settings OWNER TO postgres;

--
-- TOC entry 2134 (class 1259 OID 51145)
-- Name: invoice_settings_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.invoice_settings_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.invoice_settings_uid_seq OWNER TO postgres;

--
-- TOC entry 14127 (class 0 OID 0)
-- Dependencies: 2134
-- Name: invoice_settings_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.invoice_settings_uid_seq OWNED BY shulesoft.invoice_settings.uid;


--
-- TOC entry 2135 (class 1259 OID 51146)
-- Name: invoice_subviews; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.invoice_subviews AS
 SELECT b.id,
    b.student_id,
    (((b.reference)::text || 'EA'::text) || a.fee_id) AS reference,
    b.prefix,
    b.date,
    b.sync,
    b.return_message,
    b.push_status,
    b.academic_year_id,
    b.created_at,
    b.updated_at,
    a.balance AS amount,
    c.name,
    1 AS sub_invoice,
    a.fee_id
   FROM ((shulesoft.invoices b
     JOIN shulesoft.student c ON ((c.student_id = b.student_id)))
     JOIN shulesoft.invoice_balances a ON ((a.invoice_id = b.id)));


ALTER VIEW shulesoft.invoice_subviews OWNER TO postgres;

--
-- TOC entry 2136 (class 1259 OID 51151)
-- Name: invoice_summary; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.invoice_summary AS
 SELECT COALESCE((COALESCE(sum(a.total_amount), (0)::numeric) - sum(a.discount_amount)), (0)::numeric) AS amount,
    COALESCE((COALESCE(sum(a.total_payment_invoice_fee_amount), (0)::numeric) + COALESCE(sum(a.total_advance_invoice_fee_amount))), (0)::numeric) AS paid_amount,
    sum(a.balance) AS balance,
    a.invoice_id,
    a.student_id,
    c.reference,
    c.sync,
    b.name AS student_name,
    b.roll,
    a.created_at,
    c.academic_year_id,
    c.date,
    c.due_date,
    d.section_id,
    e."classesID"
   FROM ((((shulesoft.invoice_balances a
     JOIN shulesoft.student b ON ((a.student_id = b.student_id)))
     JOIN shulesoft.invoices c ON ((c.id = a.invoice_id)))
     JOIN shulesoft.student_archive d ON (((d.academic_year_id = c.academic_year_id) AND (d.student_id = b.student_id))))
     JOIN shulesoft.section e ON ((e."sectionID" = d.section_id)))
  WHERE (b.status = 1)
  GROUP BY a.invoice_id, a.created_at, b.name, d.section_id, e."classesID", b.roll, a.student_id, c.reference, c.sync, c.academic_year_id, c.date, c.due_date;


ALTER VIEW shulesoft.invoice_summary OWNER TO postgres;

--
-- TOC entry 1803 (class 1259 OID 49796)
-- Name: setting_settingID_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft."setting_settingID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft."setting_settingID_seq" OWNER TO postgres;

--
-- TOC entry 1804 (class 1259 OID 49797)
-- Name: setting; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.setting (
    "settingID" integer DEFAULT nextval('shulesoft."setting_settingID_seq"'::regclass) NOT NULL,
    sname text,
    name character varying(60),
    phone text,
    address text,
    email character varying(40),
    sid integer DEFAULT nextval('public.unique_identifier_seq'::regclass) NOT NULL,
    currency_code character varying(11),
    currency_symbol text,
    footer text,
    photo character varying(128) DEFAULT 'shulesoft.png'::character varying,
    username character varying(128),
    password character varying(128),
    usertype character varying(128),
    purchase_code character varying(255),
    created_at timestamp without time zone DEFAULT now(),
    api_key character varying(90),
    api_secret character varying(120),
    box character varying(90),
    payment_integrated integer DEFAULT 0,
    pass_mark integer,
    website character varying(250),
    academic_year_id integer,
    motto character varying,
    sms_enabled integer,
    email_enabled integer DEFAULT 0,
    sms_type integer DEFAULT 1,
    headname character varying,
    signature text,
    signature_path character varying(100),
    exam_avg_format smallint,
    school_format character varying DEFAULT 'NECTA'::character varying,
    registration_number character varying,
    salary double precision,
    id_number character varying,
    empty_mark character varying(4) DEFAULT '-'::character varying,
    institution_code character varying,
    price_per_student character varying,
    api_username character varying,
    api_password character varying,
    default_password character varying,
    shulesoft_comission numeric,
    nmb_comission numeric,
    transaction_fee numeric,
    bank_account_number character varying,
    bank_name character varying,
    updated_at timestamp without time zone,
    remember_token character varying(255),
    show_report_to smallint DEFAULT 1,
    custom_to smallint,
    custom_to_amount numeric,
    payment_status smallint DEFAULT 0,
    payment_deadline_date date,
    show_zero_in_report integer,
    show_report_to_all integer,
    school_gender character varying,
    currency_rounding integer DEFAULT 2,
    email_list character varying,
    invoice_guide text,
    transaction_charges_to_parents smallint DEFAULT 1,
    publish_exam smallint DEFAULT 1,
    number character varying,
    show_payment_plan character(1) DEFAULT 1,
    estimated_students integer DEFAULT 0,
    account_manager_id integer DEFAULT 20,
    show_bank integer DEFAULT 1,
    enable_payment_delete smallint DEFAULT 1,
    total_paid_amount numeric,
    region character varying,
    school_id integer,
    roll_no_initial character varying,
    online_admission smallint,
    email_valid smallint,
    payroll_status smallint DEFAULT 1 NOT NULL,
    pay_live_session smallint DEFAULT 0,
    other_learning_material smallint DEFAULT 0,
    enable_parent_charging integer DEFAULT 0,
    enable_self_registration smallint DEFAULT 0,
    collection_method smallint DEFAULT 0,
    fcm_token character varying,
    sub_invoice smallint,
    source character varying,
    last_payment_date date,
    next_payment_date date,
    school_status integer DEFAULT 2,
    default_lang character varying(50) DEFAULT 'sw'::character varying,
    country_id integer DEFAULT 1,
    sms_lang character varying DEFAULT 'sw'::character varying,
    gender character varying,
    category character varying,
    whatsapp_enabled integer DEFAULT 1,
    telegram_bot_token text,
    login_code character varying,
    expire_at timestamp without time zone,
    school_attendance_mode smallint DEFAULT 1,
    school_hostel_attendance smallint DEFAULT 0,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    site_theme integer DEFAULT 1,
    enabled_modules text,
    income_tax character varying,
    high_performance integer DEFAULT 80,
    low_performance integer DEFAULT 30,
    inventory_format smallint DEFAULT 3,
    business_type smallint DEFAULT 1,
    span_number integer,
    vfd_password character varying,
    tin character varying,
    vfd_serial_number character varying,
    vrn character varying,
    tax_group character varying DEFAULT 5,
    vfd_enabled smallint DEFAULT 0,
    vfd_approved character varying DEFAULT 0,
    allow_insurance smallint DEFAULT 0
);


ALTER TABLE shulesoft.setting OWNER TO postgres;

--
-- TOC entry 14128 (class 0 OID 0)
-- Dependencies: 1804
-- Name: COLUMN setting.business_type; Type: COMMENT; Schema: shulesoft; Owner: postgres
--

COMMENT ON COLUMN shulesoft.setting.business_type IS '1=school, 2=enterprise';


--
-- TOC entry 2137 (class 1259 OID 51156)
-- Name: invoice_views; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.invoice_views AS
 SELECT b.id,
    b.student_id,
    b.reference,
    b.prefix,
    b.date,
    b.sync,
    b.return_message,
    b.push_status,
    b.academic_year_id,
    b.created_at,
    b.updated_at,
    ( SELECT sum(invoice_balance.balance) AS sum
           FROM shulesoft.invoice_balance
          WHERE (invoice_balance.invoice_id = b.id)) AS amount,
    c.name,
    ( SELECT setting.sub_invoice
           FROM shulesoft.setting
          WHERE ((setting.schema_name)::text = (c.schema_name)::text)
         LIMIT 1) AS sub_invoice,
    0 AS fee_id,
    c.schema_name
   FROM (shulesoft.invoices b
     JOIN shulesoft.student c ON ((c.student_id = b.student_id)));


ALTER VIEW shulesoft.invoice_views OWNER TO postgres;

--
-- TOC entry 2138 (class 1259 OID 51161)
-- Name: invoices_fees_installments_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.invoices_fees_installments_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.invoices_fees_installments_uid_seq OWNER TO postgres;

--
-- TOC entry 14129 (class 0 OID 0)
-- Dependencies: 2138
-- Name: invoices_fees_installments_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.invoices_fees_installments_uid_seq OWNED BY shulesoft.invoices_fees_installments.uid;


--
-- TOC entry 2139 (class 1259 OID 51162)
-- Name: invoices_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.invoices_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.invoices_uid_seq OWNER TO postgres;

--
-- TOC entry 14130 (class 0 OID 0)
-- Dependencies: 2139
-- Name: invoices_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.invoices_uid_seq OWNED BY shulesoft.invoices.uid;


--
-- TOC entry 2140 (class 1259 OID 51163)
-- Name: issue_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.issue_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.issue_id_seq OWNER TO postgres;

--
-- TOC entry 2141 (class 1259 OID 51164)
-- Name: issue; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.issue (
    id integer DEFAULT nextval('shulesoft.issue_id_seq'::regclass) NOT NULL,
    lmember_id integer NOT NULL,
    issue_date date NOT NULL,
    due_date date NOT NULL,
    return_date date,
    fine character varying(11),
    note text,
    created_at timestamp without time zone DEFAULT now(),
    is_returned integer DEFAULT 0 NOT NULL,
    "book_quantityID" integer,
    created_by integer,
    type character(1) DEFAULT 1 NOT NULL,
    book_quantity_id integer,
    book_id integer NOT NULL,
    created_by_table character varying,
    updated_at timestamp without time zone,
    serial_no character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    "lID" integer,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.issue OWNER TO postgres;

--
-- TOC entry 2142 (class 1259 OID 51174)
-- Name: issue_inventory_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.issue_inventory_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.issue_inventory_id_seq OWNER TO postgres;

--
-- TOC entry 2143 (class 1259 OID 51175)
-- Name: issue_inventory; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.issue_inventory (
    id integer DEFAULT nextval('shulesoft.issue_inventory_id_seq'::regclass) NOT NULL,
    date date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    quantity integer,
    product_cart_id integer,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.issue_inventory OWNER TO postgres;

--
-- TOC entry 2144 (class 1259 OID 51182)
-- Name: issue_inventory_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.issue_inventory_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.issue_inventory_uid_seq OWNER TO postgres;

--
-- TOC entry 14131 (class 0 OID 0)
-- Dependencies: 2144
-- Name: issue_inventory_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.issue_inventory_uid_seq OWNED BY shulesoft.issue_inventory.uid;


--
-- TOC entry 2145 (class 1259 OID 51183)
-- Name: issue_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.issue_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.issue_uid_seq OWNER TO postgres;

--
-- TOC entry 14132 (class 0 OID 0)
-- Dependencies: 2145
-- Name: issue_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.issue_uid_seq OWNED BY shulesoft.issue.uid;


--
-- TOC entry 2146 (class 1259 OID 51184)
-- Name: item_code_number_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.item_code_number_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.item_code_number_seq OWNER TO postgres;

--
-- TOC entry 1798 (class 1259 OID 49770)
-- Name: product_alert_quantity; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.product_alert_quantity (
    id integer NOT NULL,
    product_register_id integer,
    alert_quantity integer,
    updated_at time with time zone,
    created_at timestamp without time zone DEFAULT now(),
    name character varying(150),
    note text,
    metric_id integer,
    refer_expense_id integer,
    open_blance double precision DEFAULT 0,
    warehouse_id integer,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    code character varying,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    price_per_unit integer
);


ALTER TABLE shulesoft.product_alert_quantity OWNER TO postgres;

--
-- TOC entry 2147 (class 1259 OID 51185)
-- Name: product_sales_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.product_sales_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.product_sales_id_seq OWNER TO postgres;

--
-- TOC entry 2148 (class 1259 OID 51186)
-- Name: product_sales; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.product_sales (
    id integer DEFAULT nextval('shulesoft.product_sales_id_seq'::regclass) NOT NULL,
    product_alert_id integer,
    quantity double precision,
    selling_price numeric,
    revenue_id integer,
    created_by integer,
    created_by_table character varying,
    date date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    note text,
    name character varying,
    email character varying,
    phone character varying,
    amount double precision DEFAULT 0,
    reference character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.product_sales OWNER TO postgres;

--
-- TOC entry 2149 (class 1259 OID 51194)
-- Name: role_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.role_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.role_id_seq OWNER TO postgres;

--
-- TOC entry 2150 (class 1259 OID 51195)
-- Name: role; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.role (
    id integer DEFAULT nextval('shulesoft.role_id_seq'::regclass) NOT NULL,
    name character varying NOT NULL,
    sys_role character(1) DEFAULT '0'::bpchar NOT NULL,
    is_super character(1) DEFAULT '0'::bpchar NOT NULL,
    created_at timestamp without time zone DEFAULT ('now'::text)::timestamp(0) without time zone NOT NULL,
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.role OWNER TO postgres;

--
-- TOC entry 1809 (class 1259 OID 49882)
-- Name: teacher_teacherID_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft."teacher_teacherID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft."teacher_teacherID_seq" OWNER TO postgres;

--
-- TOC entry 1810 (class 1259 OID 49883)
-- Name: teacher; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.teacher (
    "teacherID" integer DEFAULT nextval('shulesoft."teacher_teacherID_seq"'::regclass) NOT NULL,
    name character varying(60) NOT NULL,
    designation character varying(128),
    dob date NOT NULL,
    sex character varying(10) NOT NULL,
    email character varying(40) DEFAULT 'default_teacher@shulesoft.com'::character varying,
    phone text,
    address text,
    jod date NOT NULL,
    photo character varying(200) DEFAULT 'defualt.png'::character varying,
    username character varying(40) NOT NULL,
    password character varying(128) NOT NULL,
    usertype character varying(200) NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    employment_type character varying(50),
    signature character varying,
    signature_path character varying(250),
    id_number character varying,
    library integer,
    health_insurance_id integer,
    health_status_id integer,
    education_id integer,
    designation_id integer,
    education_level_id integer,
    updated_at timestamp without time zone,
    physical_condition_id integer,
    religion_id integer,
    nationality integer,
    salary double precision,
    default_password character varying,
    status smallint DEFAULT 1,
    status_id smallint,
    bank_account_number character varying,
    bank_name character varying,
    remember_token character varying(255),
    email_valid smallint,
    number character varying,
    location character varying,
    region character varying,
    sid integer DEFAULT nextval('public.unique_identifier_seq'::regclass) NOT NULL,
    city_id integer,
    national_id character varying,
    country_id integer,
    qualification character varying,
    payroll_status smallint DEFAULT 1 NOT NULL,
    fcm_token character varying,
    role_id integer,
    tin character varying DEFAULT '999-999-999'::character varying,
    school_phone_number character varying,
    head_teacher_name character varying,
    qr_code text,
    target integer DEFAULT 50,
    login_code character varying,
    expire_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    employment_type_id integer
);


ALTER TABLE shulesoft.teacher OWNER TO postgres;

--
-- TOC entry 2151 (class 1259 OID 51205)
-- Name: users; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.users AS
 SELECT a."userID" AS id,
    a.email,
    a.phone,
    a.remember_token,
    a.username,
    a.usertype,
    a.password,
    'user'::character varying AS "table",
    a.name,
    a.role_id,
    a.status,
    a.photo,
    a.payroll_status,
    a.created_at,
    a.updated_at,
    a.sid,
    a.default_password,
    a.dob,
    a.sex,
    a.country_id,
    a.schema_name,
    a.uuid
   FROM shulesoft."user" a
UNION ALL
 SELECT t."teacherID" AS id,
    t.email,
    t.phone,
    t.remember_token,
    t.username,
    t.usertype,
    t.password,
    'teacher'::character varying AS "table",
    t.name,
    t.role_id,
    t.status,
    t.photo,
    t.payroll_status,
    t.created_at,
    t.updated_at,
    t.sid,
    t.default_password,
    t.dob,
    t.sex,
    t.country_id,
    t.schema_name,
    t.uuid
   FROM shulesoft.teacher t
UNION ALL
 SELECT s.student_id AS id,
    s.email,
    s.phone,
    s.remember_token,
    s.username,
    'Student'::character varying AS usertype,
    s.password,
    'student'::character varying AS "table",
    s.name,
    ( SELECT role.id
           FROM shulesoft.role
          WHERE ((lower((role.name)::text) = 'student'::text) AND ((role.schema_name)::text = (s.schema_name)::text))
         LIMIT 1) AS role_id,
    s.status,
    s.photo,
    s.payroll_status,
    s.created_at,
    s.updated_at,
    s.sid,
    'abc123456'::character varying AS default_password,
    s.dob,
    s.sex,
    s.country_id,
    s.schema_name,
    s.uuid
   FROM shulesoft.student s
UNION ALL
 SELECT p."parentID" AS id,
    p.email,
    p.phone,
    p.remember_token,
    p.username,
    'Parent'::character varying AS usertype,
    p.password,
    'parent'::character varying AS "table",
    p.name,
    ( SELECT role.id
           FROM shulesoft.role
          WHERE ((lower((role.name)::text) = 'parent'::text) AND ((role.schema_name)::text = (p.schema_name)::text))
         LIMIT 1) AS role_id,
    p.status,
    p.photo,
    p.payroll_status,
    p.created_at,
    p.updated_at,
    p.sid,
    p.default_password,
    p.dob,
    p.sex,
    p.country_id,
    p.schema_name,
    p.uuid
   FROM shulesoft.parent p
UNION ALL
 SELECT b."settingID" AS id,
    b.email,
    b.phone,
    b.remember_token,
    b.username,
    'Admin'::character varying AS usertype,
    b.password,
    'setting'::character varying AS "table",
    b.name,
    ( SELECT role.id
           FROM shulesoft.role
          WHERE ((lower((role.name)::text) = 'admin'::text) AND ((role.schema_name)::text = (b.schema_name)::text))
         LIMIT 1) AS role_id,
    1 AS status,
    b.photo,
    b.payroll_status,
    b.created_at,
    b.updated_at,
    b.sid,
    b.default_password,
    CURRENT_DATE AS dob,
    'Male'::character varying AS sex,
    b.country_id,
    b.schema_name,
    b.uuid
   FROM shulesoft.setting b;


ALTER VIEW shulesoft.users OWNER TO postgres;

--
-- TOC entry 2152 (class 1259 OID 51210)
-- Name: item_usage; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.item_usage AS
 SELECT a.id,
    a.name,
    a.product_alert_id,
    a.revenue_id,
    a.created_at,
    a.quantity,
    a.amount,
    a.date,
    a.uuid,
    b.payer_name,
    b.note,
    d.name AS user_name,
    b.amount AS revenue
   FROM ((shulesoft.product_cart a
     JOIN shulesoft.revenues b ON ((a.revenue_id = b.id)))
     JOIN shulesoft.users d ON (((b.created_by_id = d.id) AND ((b.created_by_table)::text = (d."table")::text))))
UNION ALL
 SELECT a.id,
    c.name,
    a.product_alert_id,
    a.revenue_id,
    a.created_at,
    a.quantity,
    a.amount,
    a.date,
    a.uuid,
    a.name AS payer_name,
    a.note,
    d.name AS user_name,
    a.amount AS revenue
   FROM ((shulesoft.product_sales a
     JOIN shulesoft.product_alert_quantity c ON ((c.id = a.product_alert_id)))
     LEFT JOIN shulesoft.users d ON (((a.created_by = d.id) AND ((a.created_by_table)::text = (d."table")::text))));


ALTER VIEW shulesoft.item_usage OWNER TO postgres;

--
-- TOC entry 2153 (class 1259 OID 51215)
-- Name: items_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.items_id_seq OWNER TO postgres;

--
-- TOC entry 2154 (class 1259 OID 51216)
-- Name: items; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.items (
    id integer DEFAULT nextval('shulesoft.items_id_seq'::regclass) NOT NULL,
    name character varying(200),
    batch_number character varying(90),
    quantity integer,
    vendor_id integer,
    contact_person_name character varying(200),
    contact_person_number character varying(90),
    status integer,
    price real,
    created_at timestamp without time zone DEFAULT now(),
    comments text,
    depreciation real,
    current_price real,
    date_purchased timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone,
    warehouse_id integer
);


ALTER TABLE shulesoft.items OWNER TO postgres;

--
-- TOC entry 2155 (class 1259 OID 51224)
-- Name: items_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.items_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.items_uid_seq OWNER TO postgres;

--
-- TOC entry 14133 (class 0 OID 0)
-- Dependencies: 2155
-- Name: items_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.items_uid_seq OWNED BY shulesoft.items.uid;


--
-- TOC entry 2156 (class 1259 OID 51225)
-- Name: journal_group; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.journal_group (
    id integer NOT NULL,
    date date,
    note character varying(255),
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    schema_name character varying(250)
);


ALTER TABLE shulesoft.journal_group OWNER TO postgres;

--
-- TOC entry 2157 (class 1259 OID 51230)
-- Name: journal_group_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.journal_group_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.journal_group_id_seq OWNER TO postgres;

--
-- TOC entry 14134 (class 0 OID 0)
-- Dependencies: 2157
-- Name: journal_group_id_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.journal_group_id_seq OWNED BY shulesoft.journal_group.id;


--
-- TOC entry 2158 (class 1259 OID 51231)
-- Name: journals; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.journals (
    id integer NOT NULL,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    account_id integer NOT NULL,
    description text NOT NULL,
    amount numeric(19,2) NOT NULL,
    entry_date character varying(40) NOT NULL,
    financial_year_id integer,
    academic_year_id integer,
    entry_batch_id character varying(200) NOT NULL,
    school_id integer,
    book_side character varying(40),
    entry_method character varying(40),
    client_id integer,
    client_type character varying(40),
    client_code character varying(250),
    client_name character varying(250),
    transaction_reference character varying(250),
    is_bank_transaction boolean,
    currency_exchange_rate numeric(19,12),
    currency_equivalent_amount numeric(19,2),
    currency_code character varying(20) DEFAULT 'TZS'::character varying NOT NULL,
    batch_id character varying(250),
    archived boolean NOT NULL,
    transaction_type character varying(40),
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    created_by integer,
    created_by_table character varying,
    auto_approved boolean,
    model_id integer,
    model character varying,
    model_uuid character varying,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    journal_group_id integer
);


ALTER TABLE shulesoft.journals OWNER TO postgres;

--
-- TOC entry 2159 (class 1259 OID 51239)
-- Name: journals_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.journals_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.journals_id_seq OWNER TO postgres;

--
-- TOC entry 14135 (class 0 OID 0)
-- Dependencies: 2159
-- Name: journals_id_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.journals_id_seq OWNED BY shulesoft.journals.id;


--
-- TOC entry 2160 (class 1259 OID 51240)
-- Name: journals_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.journals_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.journals_uid_seq OWNER TO postgres;

--
-- TOC entry 14136 (class 0 OID 0)
-- Dependencies: 2160
-- Name: journals_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.journals_uid_seq OWNED BY shulesoft.journals.uid;


--
-- TOC entry 2161 (class 1259 OID 51241)
-- Name: lesson_plan_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.lesson_plan_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.lesson_plan_id_seq OWNER TO postgres;

--
-- TOC entry 2162 (class 1259 OID 51242)
-- Name: lesson_plan; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.lesson_plan (
    id integer DEFAULT nextval('shulesoft.lesson_plan_id_seq'::regclass) NOT NULL,
    syllabus_objective_id integer,
    stage_position integer,
    stage_name character varying,
    activity text,
    time_taken text,
    resource text,
    teacher_id integer,
    created_by_id integer,
    created_by_table character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.lesson_plan OWNER TO postgres;

--
-- TOC entry 2163 (class 1259 OID 51250)
-- Name: lesson_plan_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.lesson_plan_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.lesson_plan_uid_seq OWNER TO postgres;

--
-- TOC entry 14137 (class 0 OID 0)
-- Dependencies: 2163
-- Name: lesson_plan_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.lesson_plan_uid_seq OWNED BY shulesoft.lesson_plan.uid;


--
-- TOC entry 2164 (class 1259 OID 51251)
-- Name: liabilities; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.liabilities (
    id integer NOT NULL,
    uid integer NOT NULL,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    refer_expense_id integer,
    account_id integer,
    transaction_id character varying,
    amount numeric,
    user_sid integer,
    created_by_id integer,
    note text,
    reconciled smallint DEFAULT 0,
    number integer DEFAULT nextval('public.revenues_number_seq'::regclass) NOT NULL,
    sms_sent smallint,
    paid smallint DEFAULT 0,
    date timestamp without time zone,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    schema_name character varying
);


ALTER TABLE shulesoft.liabilities OWNER TO postgres;

--
-- TOC entry 2165 (class 1259 OID 51261)
-- Name: liabilities_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.liabilities_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.liabilities_id_seq OWNER TO postgres;

--
-- TOC entry 14138 (class 0 OID 0)
-- Dependencies: 2165
-- Name: liabilities_id_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.liabilities_id_seq OWNED BY shulesoft.liabilities.id;


--
-- TOC entry 2166 (class 1259 OID 51262)
-- Name: liabilities_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.liabilities_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.liabilities_uid_seq OWNER TO postgres;

--
-- TOC entry 14139 (class 0 OID 0)
-- Dependencies: 2166
-- Name: liabilities_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.liabilities_uid_seq OWNED BY shulesoft.liabilities.uid;


--
-- TOC entry 2167 (class 1259 OID 51263)
-- Name: livestudy_packages_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.livestudy_packages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.livestudy_packages_id_seq OWNER TO postgres;

--
-- TOC entry 2168 (class 1259 OID 51264)
-- Name: livestudy_packages; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.livestudy_packages (
    id integer DEFAULT nextval('shulesoft.livestudy_packages_id_seq'::regclass) NOT NULL,
    amount numeric,
    days character varying,
    note text,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.livestudy_packages OWNER TO postgres;

--
-- TOC entry 2169 (class 1259 OID 51272)
-- Name: livestudy_packages_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.livestudy_packages_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.livestudy_packages_uid_seq OWNER TO postgres;

--
-- TOC entry 14140 (class 0 OID 0)
-- Dependencies: 2169
-- Name: livestudy_packages_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.livestudy_packages_uid_seq OWNED BY shulesoft.livestudy_packages.uid;


--
-- TOC entry 2170 (class 1259 OID 51273)
-- Name: livestudy_payments_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.livestudy_payments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.livestudy_payments_id_seq OWNER TO postgres;

--
-- TOC entry 2171 (class 1259 OID 51274)
-- Name: livestudy_payments; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.livestudy_payments (
    id integer DEFAULT nextval('shulesoft.livestudy_payments_id_seq'::regclass) NOT NULL,
    sid integer,
    payment_id integer,
    end_date timestamp without time zone,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    livestudy_package_id integer,
    start_date timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.livestudy_payments OWNER TO postgres;

--
-- TOC entry 2172 (class 1259 OID 51282)
-- Name: livestudy_payments_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.livestudy_payments_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.livestudy_payments_uid_seq OWNER TO postgres;

--
-- TOC entry 14141 (class 0 OID 0)
-- Dependencies: 2172
-- Name: livestudy_payments_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.livestudy_payments_uid_seq OWNED BY shulesoft.livestudy_payments.uid;


--
-- TOC entry 2173 (class 1259 OID 51283)
-- Name: lmember_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.lmember_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.lmember_id_seq OWNER TO postgres;

--
-- TOC entry 2174 (class 1259 OID 51284)
-- Name: lmember; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.lmember (
    id integer DEFAULT nextval('shulesoft.lmember_id_seq'::regclass) NOT NULL,
    "lID" integer,
    user_id integer,
    lbalance character varying(20),
    ljoindate date NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    status character varying DEFAULT 1,
    user_table character varying,
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    user_sid integer
);


ALTER TABLE shulesoft.lmember OWNER TO postgres;

--
-- TOC entry 2175 (class 1259 OID 51293)
-- Name: lmember_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.lmember_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.lmember_uid_seq OWNER TO postgres;

--
-- TOC entry 14142 (class 0 OID 0)
-- Dependencies: 2175
-- Name: lmember_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.lmember_uid_seq OWNED BY shulesoft.lmember.uid;


--
-- TOC entry 2176 (class 1259 OID 51294)
-- Name: loan_applications_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.loan_applications_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.loan_applications_id_seq OWNER TO postgres;

--
-- TOC entry 2177 (class 1259 OID 51295)
-- Name: loan_applications; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.loan_applications (
    id integer DEFAULT nextval('shulesoft.loan_applications_id_seq'::regclass) NOT NULL,
    user_id integer,
    "table" character varying,
    created_by integer,
    created_by_table character varying,
    approved_by integer,
    approved_by_table character varying,
    approval_status smallint DEFAULT 0,
    amount numeric,
    payment_start_date date,
    loan_type_id integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    qualify character varying,
    months integer,
    description text,
    monthly_repayment_amount numeric,
    loan_source_id integer,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.loan_applications OWNER TO postgres;

--
-- TOC entry 2178 (class 1259 OID 51304)
-- Name: loan_applications_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.loan_applications_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.loan_applications_uid_seq OWNER TO postgres;

--
-- TOC entry 14143 (class 0 OID 0)
-- Dependencies: 2178
-- Name: loan_applications_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.loan_applications_uid_seq OWNED BY shulesoft.loan_applications.uid;


--
-- TOC entry 2179 (class 1259 OID 51305)
-- Name: loan_payments_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.loan_payments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.loan_payments_id_seq OWNER TO postgres;

--
-- TOC entry 2180 (class 1259 OID 51306)
-- Name: loan_payments; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.loan_payments (
    id integer DEFAULT nextval('shulesoft.loan_payments_id_seq'::regclass) NOT NULL,
    loan_application_id integer,
    amount numeric NOT NULL,
    payment_type_id integer,
    date date NOT NULL,
    transaction_id character varying,
    created_at timestamp without time zone DEFAULT now(),
    cheque_number character varying,
    bank_account_id integer,
    payer_name character varying,
    mobile_transaction_id character varying,
    transaction_time character varying,
    account_number character varying,
    token character varying,
    reconciled smallint DEFAULT 0,
    receipt_code character varying,
    updated_at timestamp without time zone,
    channel character varying,
    created_by integer,
    created_by_table character varying,
    status smallint DEFAULT 0,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.loan_payments OWNER TO postgres;

--
-- TOC entry 2181 (class 1259 OID 51316)
-- Name: loan_payments_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.loan_payments_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.loan_payments_uid_seq OWNER TO postgres;

--
-- TOC entry 14144 (class 0 OID 0)
-- Dependencies: 2181
-- Name: loan_payments_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.loan_payments_uid_seq OWNED BY shulesoft.loan_payments.uid;


--
-- TOC entry 2182 (class 1259 OID 51317)
-- Name: loan_types_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.loan_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.loan_types_id_seq OWNER TO postgres;

--
-- TOC entry 2183 (class 1259 OID 51318)
-- Name: loan_types; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.loan_types (
    id integer DEFAULT nextval('shulesoft.loan_types_id_seq'::regclass) NOT NULL,
    name character varying,
    source smallint DEFAULT 1,
    minimum_amount numeric,
    maximum_amount numeric,
    maximum_tenor numeric,
    minimum_tenor numeric,
    interest_rate numeric,
    credit_ratio numeric,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    created_by integer,
    created_by_table character varying,
    description text,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.loan_types OWNER TO postgres;

--
-- TOC entry 2184 (class 1259 OID 51327)
-- Name: loan_types_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.loan_types_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.loan_types_uid_seq OWNER TO postgres;

--
-- TOC entry 14145 (class 0 OID 0)
-- Dependencies: 2184
-- Name: loan_types_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.loan_types_uid_seq OWNED BY shulesoft.loan_types.uid;


--
-- TOC entry 2185 (class 1259 OID 51328)
-- Name: log_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.log_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.log_id_seq OWNER TO postgres;

--
-- TOC entry 2186 (class 1259 OID 51329)
-- Name: log; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.log (
    id integer DEFAULT nextval('shulesoft.log_id_seq'::regclass) NOT NULL,
    url character varying,
    user_agent character varying,
    platform character varying,
    platform_name character varying,
    source character varying,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone,
    "user" character varying,
    user_id integer,
    country character varying,
    city character varying,
    region character varying,
    isp character varying,
    "table" character varying,
    controller character varying,
    method character varying,
    is_ajax smallint DEFAULT 0,
    request json,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying,
    uid integer NOT NULL,
    user_sid integer
);


ALTER TABLE shulesoft.log OWNER TO postgres;

--
-- TOC entry 2187 (class 1259 OID 51338)
-- Name: log_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.log_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.log_uid_seq OWNER TO postgres;

--
-- TOC entry 14146 (class 0 OID 0)
-- Dependencies: 2187
-- Name: log_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.log_uid_seq OWNED BY shulesoft.log.uid;


--
-- TOC entry 2188 (class 1259 OID 51339)
-- Name: login_attempts_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.login_attempts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.login_attempts_id_seq OWNER TO postgres;

--
-- TOC entry 2189 (class 1259 OID 51340)
-- Name: login_attempts; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.login_attempts (
    id integer DEFAULT nextval('shulesoft.login_attempts_id_seq'::regclass) NOT NULL,
    username character varying,
    wrong_password character varying,
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.login_attempts OWNER TO postgres;

--
-- TOC entry 2190 (class 1259 OID 51348)
-- Name: login_attempts_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.login_attempts_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.login_attempts_uid_seq OWNER TO postgres;

--
-- TOC entry 14147 (class 0 OID 0)
-- Dependencies: 2190
-- Name: login_attempts_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.login_attempts_uid_seq OWNED BY shulesoft.login_attempts.uid;


--
-- TOC entry 1790 (class 1259 OID 49724)
-- Name: login_locations_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.login_locations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.login_locations_id_seq OWNER TO postgres;

--
-- TOC entry 1791 (class 1259 OID 49725)
-- Name: login_locations; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.login_locations (
    id integer DEFAULT nextval('shulesoft.login_locations_id_seq'::regclass) NOT NULL,
    ip character varying,
    city character varying,
    region character varying,
    country character varying,
    latitude character varying,
    longtude character varying,
    timezone character varying,
    user_id integer,
    "table" character varying,
    continent character varying,
    currency_code character varying,
    currency_symbol character varying,
    currency_convert character varying,
    location_radius_accuracy character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    action character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.login_locations OWNER TO postgres;

--
-- TOC entry 2191 (class 1259 OID 51349)
-- Name: login_locations_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.login_locations_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.login_locations_uid_seq OWNER TO postgres;

--
-- TOC entry 14148 (class 0 OID 0)
-- Dependencies: 2191
-- Name: login_locations_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.login_locations_uid_seq OWNED BY shulesoft.login_locations.uid;


--
-- TOC entry 2192 (class 1259 OID 51350)
-- Name: mailandsms_mailandsmsID_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft."mailandsms_mailandsmsID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft."mailandsms_mailandsmsID_seq" OWNER TO postgres;

--
-- TOC entry 2193 (class 1259 OID 51351)
-- Name: mailandsms; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.mailandsms (
    "mailandsmsID" integer DEFAULT nextval('shulesoft."mailandsms_mailandsmsID_seq"'::regclass) NOT NULL,
    users character varying NOT NULL,
    type character varying(10) NOT NULL,
    message text NOT NULL,
    create_date timestamp without time zone DEFAULT now() NOT NULL,
    year integer NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.mailandsms OWNER TO postgres;

--
-- TOC entry 2194 (class 1259 OID 51360)
-- Name: mailandsms_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.mailandsms_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.mailandsms_uid_seq OWNER TO postgres;

--
-- TOC entry 14149 (class 0 OID 0)
-- Dependencies: 2194
-- Name: mailandsms_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.mailandsms_uid_seq OWNED BY shulesoft.mailandsms.uid;


--
-- TOC entry 2195 (class 1259 OID 51361)
-- Name: mailandsmstemplate_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.mailandsmstemplate_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.mailandsmstemplate_id_seq OWNER TO postgres;

--
-- TOC entry 2196 (class 1259 OID 51362)
-- Name: mailandsmstemplate; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.mailandsmstemplate (
    id integer DEFAULT nextval('shulesoft.mailandsmstemplate_id_seq'::regclass) NOT NULL,
    name character varying(128) NOT NULL,
    "user" character varying(15),
    type character varying(10) NOT NULL,
    template text NOT NULL,
    create_date timestamp without time zone DEFAULT now() NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    status integer DEFAULT 1,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.mailandsmstemplate OWNER TO postgres;

--
-- TOC entry 2197 (class 1259 OID 51372)
-- Name: mailandsmstemplate_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.mailandsmstemplate_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.mailandsmstemplate_uid_seq OWNER TO postgres;

--
-- TOC entry 14150 (class 0 OID 0)
-- Dependencies: 2197
-- Name: mailandsmstemplate_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.mailandsmstemplate_uid_seq OWNED BY shulesoft.mailandsmstemplate.uid;


--
-- TOC entry 2198 (class 1259 OID 51373)
-- Name: mailandsmstemplatetag_mailandsmstemplatetagID_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft."mailandsmstemplatetag_mailandsmstemplatetagID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft."mailandsmstemplatetag_mailandsmstemplatetagID_seq" OWNER TO postgres;

--
-- TOC entry 2199 (class 1259 OID 51374)
-- Name: mailandsmstemplatetag; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.mailandsmstemplatetag (
    "mailandsmstemplatetagID" integer DEFAULT nextval('shulesoft."mailandsmstemplatetag_mailandsmstemplatetagID_seq"'::regclass) NOT NULL,
    "usersID" integer NOT NULL,
    name character varying(15) NOT NULL,
    tagname character varying(128) NOT NULL,
    mailandsmstemplatetag_extra character varying(255),
    create_date timestamp without time zone DEFAULT now() NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.mailandsmstemplatetag OWNER TO postgres;

--
-- TOC entry 2200 (class 1259 OID 51383)
-- Name: mailandsmstemplatetag_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.mailandsmstemplatetag_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.mailandsmstemplatetag_uid_seq OWNER TO postgres;

--
-- TOC entry 14151 (class 0 OID 0)
-- Dependencies: 2200
-- Name: mailandsmstemplatetag_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.mailandsmstemplatetag_uid_seq OWNED BY shulesoft.mailandsmstemplatetag.uid;


--
-- TOC entry 2201 (class 1259 OID 51384)
-- Name: major_subjects_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.major_subjects_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.major_subjects_id_seq OWNER TO postgres;

--
-- TOC entry 2202 (class 1259 OID 51385)
-- Name: major_subjects; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.major_subjects (
    id integer DEFAULT nextval('shulesoft.major_subjects_id_seq'::regclass) NOT NULL,
    name character varying(60) NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.major_subjects OWNER TO postgres;

--
-- TOC entry 2203 (class 1259 OID 51393)
-- Name: major_subjects_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.major_subjects_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.major_subjects_uid_seq OWNER TO postgres;

--
-- TOC entry 14152 (class 0 OID 0)
-- Dependencies: 2203
-- Name: major_subjects_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.major_subjects_uid_seq OWNED BY shulesoft.major_subjects.uid;


--
-- TOC entry 2204 (class 1259 OID 51394)
-- Name: manage_budgets; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.manage_budgets (
    id integer NOT NULL,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    refer_expense_id integer,
    batch_id character varying,
    period character varying,
    period_value character varying,
    financial_year_id integer,
    amount numeric,
    created_by_sid integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    schema_name character varying NOT NULL,
    name character varying,
    budget_period_id integer,
    created_by integer
);


ALTER TABLE shulesoft.manage_budgets OWNER TO postgres;

--
-- TOC entry 2205 (class 1259 OID 51401)
-- Name: manage_budgets_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.manage_budgets_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.manage_budgets_id_seq OWNER TO postgres;

--
-- TOC entry 14153 (class 0 OID 0)
-- Dependencies: 2205
-- Name: manage_budgets_id_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.manage_budgets_id_seq OWNED BY shulesoft.manage_budgets.id;


--
-- TOC entry 1792 (class 1259 OID 49733)
-- Name: mark_markID_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft."mark_markID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft."mark_markID_seq" OWNER TO postgres;

--
-- TOC entry 1793 (class 1259 OID 49734)
-- Name: mark; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.mark (
    "markID" integer DEFAULT nextval('shulesoft."mark_markID_seq"'::regclass) NOT NULL,
    "examID" integer NOT NULL,
    exam character varying(60) NOT NULL,
    student_id integer NOT NULL,
    "classesID" integer NOT NULL,
    "subjectID" integer NOT NULL,
    subject character varying(60) NOT NULL,
    mark numeric DEFAULT 0.0,
    year integer NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    postion integer,
    academic_year_id integer,
    created_by integer,
    "table" character varying,
    updated_at timestamp without time zone,
    status smallint DEFAULT 1,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.mark OWNER TO postgres;

--
-- TOC entry 2206 (class 1259 OID 51402)
-- Name: total_mark; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.total_mark AS
 SELECT sum(mark) AS total,
    student_id,
    "examID",
    year,
    "classesID"
   FROM shulesoft.mark
  GROUP BY student_id, "examID", "classesID", year;


ALTER VIEW shulesoft.total_mark OWNER TO postgres;

--
-- TOC entry 2207 (class 1259 OID 51406)
-- Name: mark_grade; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.mark_grade AS
 SELECT student_id,
    "classesID",
    year,
    "examID",
    total,
    row_number() OVER (ORDER BY total DESC) AS rank
   FROM shulesoft.total_mark;


ALTER VIEW shulesoft.mark_grade OWNER TO postgres;

--
-- TOC entry 2208 (class 1259 OID 51410)
-- Name: refer_subject_subject_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.refer_subject_subject_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.refer_subject_subject_id_seq OWNER TO postgres;

--
-- TOC entry 2209 (class 1259 OID 51411)
-- Name: refer_subject; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.refer_subject (
    subject_id integer DEFAULT nextval('shulesoft.refer_subject_subject_id_seq'::regclass) NOT NULL,
    subject_name character varying(500),
    code character varying,
    arrangement integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.refer_subject OWNER TO postgres;

--
-- TOC entry 2210 (class 1259 OID 51419)
-- Name: subject_subjectID_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft."subject_subjectID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft."subject_subjectID_seq" OWNER TO postgres;

--
-- TOC entry 2211 (class 1259 OID 51420)
-- Name: subject; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.subject (
    "subjectID" integer DEFAULT nextval('shulesoft."subject_subjectID_seq"'::regclass) NOT NULL,
    "classesID" integer NOT NULL,
    "teacherID" integer NOT NULL,
    subject character varying(60) NOT NULL,
    subject_author character varying(100),
    teacher_name character varying(60) NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    is_counted integer DEFAULT 1,
    is_penalty integer DEFAULT 0,
    grade_mark character(5) DEFAULT 0,
    pass_mark integer,
    subject_id integer,
    subject_type character varying(50),
    is_counted_indivision smallint DEFAULT 1,
    updated_at timestamp without time zone,
    semester_id integer DEFAULT 0,
    credit_number integer DEFAULT 0,
    category_id integer DEFAULT 0,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.subject OWNER TO postgres;

--
-- TOC entry 2212 (class 1259 OID 51435)
-- Name: mark_info; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.mark_info AS
 SELECT a.student_id,
    d.name,
    d.roll,
    c.subject_name,
    a.mark,
    a."classesID",
    e.section_id AS "sectionID",
    a."examID",
    a."subjectID",
    b.is_counted,
    b.is_penalty,
    b.pass_mark,
    a.academic_year_id,
    p.global_exam_id,
    x.name AS academic_year,
    f.refer_class_id,
    a.created_at,
    d.status AS student_status,
    d.sex,
    ( SELECT setting.region
           FROM shulesoft.setting
          WHERE ((setting.schema_name)::text = (a.schema_name)::text)
         LIMIT 1) AS region,
    a.schema_name
   FROM (((((((shulesoft.mark a
     JOIN shulesoft.student_archive e ON (((a.student_id = e.student_id) AND (a.academic_year_id = e.academic_year_id) AND ((a.schema_name)::text = (e.schema_name)::text))))
     JOIN shulesoft.subject b ON (((b."subjectID" = a."subjectID") AND ((a.schema_name)::text = (b.schema_name)::text))))
     JOIN shulesoft.refer_subject c ON (((c.subject_id = b.subject_id) AND ((c.schema_name)::text = (b.schema_name)::text))))
     JOIN shulesoft.student d ON (((d.student_id = a.student_id) AND ((a.schema_name)::text = (d.schema_name)::text))))
     JOIN shulesoft.classes f ON (((f."classesID" = a."classesID") AND ((a.schema_name)::text = (f.schema_name)::text))))
     JOIN shulesoft.exam p ON (((p."examID" = a."examID") AND ((a.schema_name)::text = (p.schema_name)::text))))
     JOIN shulesoft.academic_year x ON (((x.id = a.academic_year_id) AND ((a.schema_name)::text = (x.schema_name)::text))))
  WHERE ((a.mark IS NOT NULL) AND ((d.status = 1) OR (d.status = 2)));


ALTER VIEW shulesoft.mark_info OWNER TO postgres;

--
-- TOC entry 2213 (class 1259 OID 51440)
-- Name: mark_ranking; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.mark_ranking AS
 SELECT student_id,
    mark,
    rank() OVER (PARTITION BY "subjectID" ORDER BY mark DESC) AS dense_rank,
    "examID",
    "subjectID"
   FROM shulesoft.mark;


ALTER VIEW shulesoft.mark_ranking OWNER TO postgres;

--
-- TOC entry 2214 (class 1259 OID 51444)
-- Name: mark_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.mark_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.mark_uid_seq OWNER TO postgres;

--
-- TOC entry 14154 (class 0 OID 0)
-- Dependencies: 2214
-- Name: mark_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.mark_uid_seq OWNED BY shulesoft.mark.uid;


--
-- TOC entry 2215 (class 1259 OID 51445)
-- Name: media_mediaID_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft."media_mediaID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft."media_mediaID_seq" OWNER TO postgres;

--
-- TOC entry 2216 (class 1259 OID 51446)
-- Name: media; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.media (
    "mediaID" integer DEFAULT nextval('shulesoft."media_mediaID_seq"'::regclass) NOT NULL,
    "userID" integer NOT NULL,
    usertype character varying(20) NOT NULL,
    "mcategoryID" integer DEFAULT 0 NOT NULL,
    file_name character varying(255) NOT NULL,
    file_name_display character varying(255) NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    "table" character varying,
    class character varying,
    name character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.media OWNER TO postgres;

--
-- TOC entry 2217 (class 1259 OID 51455)
-- Name: media_categories_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.media_categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.media_categories_id_seq OWNER TO postgres;

--
-- TOC entry 2218 (class 1259 OID 51456)
-- Name: media_categories; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.media_categories (
    id integer DEFAULT nextval('shulesoft.media_categories_id_seq'::regclass) NOT NULL,
    name character varying NOT NULL,
    updated_at timestamp without time zone DEFAULT now() NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.media_categories OWNER TO postgres;

--
-- TOC entry 2219 (class 1259 OID 51465)
-- Name: media_categories_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.media_categories_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.media_categories_uid_seq OWNER TO postgres;

--
-- TOC entry 14155 (class 0 OID 0)
-- Dependencies: 2219
-- Name: media_categories_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.media_categories_uid_seq OWNED BY shulesoft.media_categories.uid;


--
-- TOC entry 2220 (class 1259 OID 51466)
-- Name: media_category_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.media_category_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.media_category_id_seq OWNER TO postgres;

--
-- TOC entry 2221 (class 1259 OID 51467)
-- Name: media_category; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.media_category (
    id integer DEFAULT nextval('shulesoft.media_category_id_seq'::regclass) NOT NULL,
    folder_name character varying(255) NOT NULL,
    create_time timestamp without time zone DEFAULT now() NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.media_category OWNER TO postgres;

--
-- TOC entry 2222 (class 1259 OID 51476)
-- Name: media_category_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.media_category_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.media_category_uid_seq OWNER TO postgres;

--
-- TOC entry 14156 (class 0 OID 0)
-- Dependencies: 2222
-- Name: media_category_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.media_category_uid_seq OWNED BY shulesoft.media_category.uid;


--
-- TOC entry 2223 (class 1259 OID 51477)
-- Name: media_comment_reply_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.media_comment_reply_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.media_comment_reply_id_seq OWNER TO postgres;

--
-- TOC entry 2224 (class 1259 OID 51478)
-- Name: media_comment_reply; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.media_comment_reply (
    id integer DEFAULT nextval('shulesoft.media_comment_reply_id_seq'::regclass) NOT NULL,
    user_id integer,
    "table" character varying,
    comment text,
    comment_id integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    opened smallint DEFAULT 0,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.media_comment_reply OWNER TO postgres;

--
-- TOC entry 2225 (class 1259 OID 51487)
-- Name: media_comment_reply_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.media_comment_reply_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.media_comment_reply_uid_seq OWNER TO postgres;

--
-- TOC entry 14157 (class 0 OID 0)
-- Dependencies: 2225
-- Name: media_comment_reply_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.media_comment_reply_uid_seq OWNED BY shulesoft.media_comment_reply.uid;


--
-- TOC entry 2226 (class 1259 OID 51488)
-- Name: media_comments_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.media_comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.media_comments_id_seq OWNER TO postgres;

--
-- TOC entry 2227 (class 1259 OID 51489)
-- Name: media_comments; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.media_comments (
    id integer DEFAULT nextval('shulesoft.media_comments_id_seq'::regclass) NOT NULL,
    media_id integer,
    comment text,
    created_by integer,
    created_by_table character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.media_comments OWNER TO postgres;

--
-- TOC entry 2228 (class 1259 OID 51497)
-- Name: media_comments_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.media_comments_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.media_comments_uid_seq OWNER TO postgres;

--
-- TOC entry 14158 (class 0 OID 0)
-- Dependencies: 2228
-- Name: media_comments_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.media_comments_uid_seq OWNED BY shulesoft.media_comments.uid;


--
-- TOC entry 2229 (class 1259 OID 51498)
-- Name: media_likes_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.media_likes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.media_likes_id_seq OWNER TO postgres;

--
-- TOC entry 2230 (class 1259 OID 51499)
-- Name: media_likes; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.media_likes (
    id integer DEFAULT nextval('shulesoft.media_likes_id_seq'::regclass) NOT NULL,
    media_id integer,
    created_by integer,
    created_by_table character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    type smallint,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.media_likes OWNER TO postgres;

--
-- TOC entry 2231 (class 1259 OID 51507)
-- Name: media_likes_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.media_likes_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.media_likes_uid_seq OWNER TO postgres;

--
-- TOC entry 14159 (class 0 OID 0)
-- Dependencies: 2231
-- Name: media_likes_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.media_likes_uid_seq OWNED BY shulesoft.media_likes.uid;


--
-- TOC entry 2232 (class 1259 OID 51508)
-- Name: media_live_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.media_live_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.media_live_id_seq OWNER TO postgres;

--
-- TOC entry 2233 (class 1259 OID 51509)
-- Name: media_live; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.media_live (
    id integer DEFAULT nextval('shulesoft.media_live_id_seq'::regclass) NOT NULL,
    media_id integer,
    start_time time without time zone,
    streaming_url character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    end_time time without time zone,
    source character varying,
    date date,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.media_live OWNER TO postgres;

--
-- TOC entry 2234 (class 1259 OID 51517)
-- Name: media_live_comments_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.media_live_comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.media_live_comments_id_seq OWNER TO postgres;

--
-- TOC entry 2235 (class 1259 OID 51518)
-- Name: media_live_comments; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.media_live_comments (
    id integer DEFAULT nextval('shulesoft.media_live_comments_id_seq'::regclass) NOT NULL,
    media_live_id integer,
    comment text,
    created_by integer,
    created_by_table character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.media_live_comments OWNER TO postgres;

--
-- TOC entry 2236 (class 1259 OID 51526)
-- Name: media_live_comments_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.media_live_comments_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.media_live_comments_uid_seq OWNER TO postgres;

--
-- TOC entry 14160 (class 0 OID 0)
-- Dependencies: 2236
-- Name: media_live_comments_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.media_live_comments_uid_seq OWNED BY shulesoft.media_live_comments.uid;


--
-- TOC entry 2237 (class 1259 OID 51527)
-- Name: media_live_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.media_live_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.media_live_uid_seq OWNER TO postgres;

--
-- TOC entry 14161 (class 0 OID 0)
-- Dependencies: 2237
-- Name: media_live_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.media_live_uid_seq OWNED BY shulesoft.media_live.uid;


--
-- TOC entry 2238 (class 1259 OID 51528)
-- Name: media_share_shareID_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft."media_share_shareID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft."media_share_shareID_seq" OWNER TO postgres;

--
-- TOC entry 2239 (class 1259 OID 51529)
-- Name: media_share; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.media_share (
    "shareID" integer DEFAULT nextval('shulesoft."media_share_shareID_seq"'::regclass) NOT NULL,
    "classesID" integer,
    public integer NOT NULL,
    file_or_folder integer NOT NULL,
    item_id integer NOT NULL,
    create_time timestamp without time zone DEFAULT now() NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.media_share OWNER TO postgres;

--
-- TOC entry 2240 (class 1259 OID 51538)
-- Name: media_share_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.media_share_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.media_share_uid_seq OWNER TO postgres;

--
-- TOC entry 14162 (class 0 OID 0)
-- Dependencies: 2240
-- Name: media_share_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.media_share_uid_seq OWNED BY shulesoft.media_share.uid;


--
-- TOC entry 2241 (class 1259 OID 51539)
-- Name: media_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.media_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.media_uid_seq OWNER TO postgres;

--
-- TOC entry 14163 (class 0 OID 0)
-- Dependencies: 2241
-- Name: media_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.media_uid_seq OWNED BY shulesoft.media.uid;


--
-- TOC entry 2242 (class 1259 OID 51540)
-- Name: medias_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.medias_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.medias_id_seq OWNER TO postgres;

--
-- TOC entry 2243 (class 1259 OID 51541)
-- Name: medias; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.medias (
    id integer DEFAULT nextval('shulesoft.medias_id_seq'::regclass) NOT NULL,
    created_by integer,
    size character varying,
    media_category_id integer DEFAULT 0,
    title character varying NOT NULL,
    description character varying(255) NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    created_by_table character varying,
    url character varying,
    name character varying,
    syllabus_topic_id integer,
    updated_at timestamp without time zone,
    status character varying,
    syllabus_subtopic_id integer,
    media_link text,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.medias OWNER TO postgres;

--
-- TOC entry 2244 (class 1259 OID 51550)
-- Name: syllabus_topics_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.syllabus_topics_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.syllabus_topics_id_seq OWNER TO postgres;

--
-- TOC entry 2245 (class 1259 OID 51551)
-- Name: syllabus_topics; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.syllabus_topics (
    id integer DEFAULT nextval('shulesoft.syllabus_topics_id_seq'::regclass) NOT NULL,
    code character varying,
    title character varying,
    subject_id integer,
    start_date date,
    end_date date,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.syllabus_topics OWNER TO postgres;

--
-- TOC entry 2246 (class 1259 OID 51559)
-- Name: media_videos; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.media_videos AS
 SELECT a.media_id,
    b.title,
    b.description,
    b.name AS media_name,
    b.status,
    b.syllabus_topic_id,
    c.title AS topic,
    d.subject_id,
    g.subject_name,
    f.classes,
    f.refer_class_id,
    b.created_by,
    e.name AS teacher,
    a.start_time,
    a.end_time,
    a.streaming_url,
    a.source,
    a.date,
    a.created_at
   FROM ((((((shulesoft.medias b
     JOIN shulesoft.media_live a ON ((a.media_id = b.id)))
     JOIN shulesoft.syllabus_topics c ON ((b.syllabus_topic_id = c.id)))
     JOIN shulesoft.subject d ON ((d."subjectID" = c.subject_id)))
     JOIN shulesoft.teacher e ON ((e."teacherID" = b.created_by)))
     JOIN shulesoft.classes f ON ((f."classesID" = d."classesID")))
     JOIN shulesoft.refer_subject g ON ((d.subject_id = g.subject_id)));


ALTER VIEW shulesoft.media_videos OWNER TO postgres;

--
-- TOC entry 2247 (class 1259 OID 51564)
-- Name: media_viewers_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.media_viewers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.media_viewers_id_seq OWNER TO postgres;

--
-- TOC entry 2248 (class 1259 OID 51565)
-- Name: media_viewers; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.media_viewers (
    id integer DEFAULT nextval('shulesoft.media_viewers_id_seq'::regclass) NOT NULL,
    media_id integer,
    created_by integer,
    created_by_table character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.media_viewers OWNER TO postgres;

--
-- TOC entry 2249 (class 1259 OID 51573)
-- Name: media_viewers_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.media_viewers_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.media_viewers_uid_seq OWNER TO postgres;

--
-- TOC entry 14164 (class 0 OID 0)
-- Dependencies: 2249
-- Name: media_viewers_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.media_viewers_uid_seq OWNED BY shulesoft.media_viewers.uid;


--
-- TOC entry 2250 (class 1259 OID 51574)
-- Name: medias_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.medias_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.medias_uid_seq OWNER TO postgres;

--
-- TOC entry 14165 (class 0 OID 0)
-- Dependencies: 2250
-- Name: medias_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.medias_uid_seq OWNED BY shulesoft.medias.uid;


--
-- TOC entry 2251 (class 1259 OID 51575)
-- Name: message_messageID_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft."message_messageID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft."message_messageID_seq" OWNER TO postgres;

--
-- TOC entry 2252 (class 1259 OID 51576)
-- Name: message; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.message (
    "messageID" integer DEFAULT nextval('shulesoft."message_messageID_seq"'::regclass) NOT NULL,
    email character varying(128) NOT NULL,
    "receiverID" integer NOT NULL,
    "receiverType" character varying(20) NOT NULL,
    subject character varying(255) NOT NULL,
    message text NOT NULL,
    attach text,
    attach_file_name text,
    "userID" integer,
    usertype character varying(20) NOT NULL,
    useremail character varying(40) NOT NULL,
    year integer NOT NULL,
    date date NOT NULL,
    create_date timestamp without time zone DEFAULT now() NOT NULL,
    read_status smallint NOT NULL,
    from_status integer NOT NULL,
    to_status integer NOT NULL,
    fav_status smallint NOT NULL,
    fav_status_sent smallint NOT NULL,
    reply_status integer NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    "receiverTable" character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.message OWNER TO postgres;

--
-- TOC entry 2253 (class 1259 OID 51585)
-- Name: message_channels_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.message_channels_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.message_channels_id_seq OWNER TO postgres;

--
-- TOC entry 2254 (class 1259 OID 51586)
-- Name: message_channels; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.message_channels (
    id bigint DEFAULT nextval('shulesoft.message_channels_id_seq'::regclass) NOT NULL,
    name character varying(255) NOT NULL,
    status integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    slug character varying(255),
    schema_name character varying,
    is_approved smallint DEFAULT 0,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    is_default smallint DEFAULT 0,
    username character varying
);


ALTER TABLE shulesoft.message_channels OWNER TO postgres;

--
-- TOC entry 2255 (class 1259 OID 51596)
-- Name: message_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.message_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.message_uid_seq OWNER TO postgres;

--
-- TOC entry 14166 (class 0 OID 0)
-- Dependencies: 2255
-- Name: message_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.message_uid_seq OWNED BY shulesoft.message.uid;


--
-- TOC entry 2256 (class 1259 OID 51597)
-- Name: minor_exam_marks_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.minor_exam_marks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.minor_exam_marks_id_seq OWNER TO postgres;

--
-- TOC entry 2257 (class 1259 OID 51598)
-- Name: minor_exam_marks; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.minor_exam_marks (
    id integer DEFAULT nextval('shulesoft.minor_exam_marks_id_seq'::regclass) NOT NULL,
    minor_exam_id integer NOT NULL,
    student_id integer NOT NULL,
    mark numeric,
    academic_year_id integer,
    status smallint DEFAULT 0,
    created_by integer,
    created_by_table character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.minor_exam_marks OWNER TO postgres;

--
-- TOC entry 2258 (class 1259 OID 51607)
-- Name: minor_exam_marks_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.minor_exam_marks_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.minor_exam_marks_uid_seq OWNER TO postgres;

--
-- TOC entry 14167 (class 0 OID 0)
-- Dependencies: 2258
-- Name: minor_exam_marks_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.minor_exam_marks_uid_seq OWNED BY shulesoft.minor_exam_marks.uid;


--
-- TOC entry 2259 (class 1259 OID 51608)
-- Name: minor_exams_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.minor_exams_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.minor_exams_id_seq OWNER TO postgres;

--
-- TOC entry 2260 (class 1259 OID 51609)
-- Name: minor_exams; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.minor_exams (
    id integer DEFAULT nextval('shulesoft.minor_exams_id_seq'::regclass) NOT NULL,
    subject_id integer,
    exam_group_id integer,
    date date,
    note text,
    created_by integer,
    created_by_table character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    total_question integer,
    publish_exam integer DEFAULT 0,
    publish_result integer DEFAULT 0,
    syllabus_topic_id integer,
    total_time integer DEFAULT 30,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.minor_exams OWNER TO postgres;

--
-- TOC entry 2261 (class 1259 OID 51620)
-- Name: minor_exams_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.minor_exams_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.minor_exams_uid_seq OWNER TO postgres;

--
-- TOC entry 14168 (class 0 OID 0)
-- Dependencies: 2261
-- Name: minor_exams_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.minor_exams_uid_seq OWNED BY shulesoft.minor_exams.uid;


--
-- TOC entry 2262 (class 1259 OID 51621)
-- Name: necta_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.necta_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.necta_id_seq OWNER TO postgres;

--
-- TOC entry 2263 (class 1259 OID 51622)
-- Name: necta; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.necta (
    id integer DEFAULT nextval('shulesoft.necta_id_seq'::regclass) NOT NULL,
    name character varying,
    class_level_id integer,
    url character varying,
    year character varying,
    regional_position integer,
    national_position integer,
    candidate_type character varying,
    centre_gpa character varying,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    created_by_id integer,
    created_by_user_table character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.necta OWNER TO postgres;

--
-- TOC entry 2264 (class 1259 OID 51629)
-- Name: necta_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.necta_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.necta_uid_seq OWNER TO postgres;

--
-- TOC entry 14169 (class 0 OID 0)
-- Dependencies: 2264
-- Name: necta_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.necta_uid_seq OWNED BY shulesoft.necta.uid;


--
-- TOC entry 2265 (class 1259 OID 51630)
-- Name: news_board_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.news_board_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.news_board_id_seq OWNER TO postgres;

--
-- TOC entry 2266 (class 1259 OID 51631)
-- Name: news_board; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.news_board (
    id integer DEFAULT nextval('shulesoft.news_board_id_seq'::regclass) NOT NULL,
    title character varying(128) NOT NULL,
    role_id integer,
    body text NOT NULL,
    create_date date,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone DEFAULT now(),
    status integer DEFAULT 1,
    attach_file_name text,
    attach text,
    class_id character varying,
    attachment text,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.news_board OWNER TO postgres;

--
-- TOC entry 2267 (class 1259 OID 51641)
-- Name: news_board_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.news_board_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.news_board_uid_seq OWNER TO postgres;

--
-- TOC entry 14170 (class 0 OID 0)
-- Dependencies: 2267
-- Name: news_board_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.news_board_uid_seq OWNED BY shulesoft.news_board.uid;


--
-- TOC entry 2268 (class 1259 OID 51642)
-- Name: next_year_invoices_fees_installments_balance; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.next_year_invoices_fees_installments_balance AS
 SELECT COALESCE(a.amount, (0)::numeric) AS total_amount,
    COALESCE(c.total_payment_invoice_amount, (0)::numeric) AS total_payment_invoice_fee_amount,
    COALESCE(d.total_advance_invoice_fee_amount, (0)::numeric) AS total_advance_invoice_fee_amount,
    COALESCE(e.amount, (0)::numeric) AS discount_amount,
    f.student_id,
    f.date AS created_at,
    b.id,
    a.class_id,
    b.fees_installment_id,
    h.id AS installment_id,
    h.start_date,
    h.academic_year_id,
    i.id AS fee_id,
    f.id AS invoice_id,
        CASE
            WHEN ((((a.amount - COALESCE(c.total_payment_invoice_amount, (0)::numeric)) - COALESCE(d.total_advance_invoice_fee_amount, (0)::numeric)) - COALESCE(e.amount, (0)::numeric)) > (0)::numeric) THEN 0
            ELSE 1
        END AS status,
    a.schema_name
   FROM (((((((((shulesoft.fees_installments_classes a
     JOIN shulesoft.invoices_fees_installments b ON ((b.fees_installment_id = a.fees_installment_id)))
     JOIN shulesoft.invoices f ON ((f.id = b.invoice_id)))
     JOIN shulesoft.fees_installments g ON ((g.id = a.fees_installment_id)))
     JOIN shulesoft.installments h ON ((h.id = g.installment_id)))
     JOIN shulesoft.fees i ON ((i.id = g.fee_id)))
     JOIN shulesoft.student s ON (((s.student_id = f.student_id) AND (s.academic_year_id <> h.academic_year_id) AND (a.class_id = s."classesID"))))
     LEFT JOIN ( SELECT sum(payments_invoices_fees_installments.amount) AS total_payment_invoice_amount,
            payments_invoices_fees_installments.invoices_fees_installment_id
           FROM shulesoft.payments_invoices_fees_installments
          GROUP BY payments_invoices_fees_installments.invoices_fees_installment_id) c ON ((c.invoices_fees_installment_id = b.id)))
     LEFT JOIN ( SELECT sum(advance_payments_invoices_fees_installments.amount) AS total_advance_invoice_fee_amount,
            advance_payments_invoices_fees_installments.invoices_fees_installments_id
           FROM shulesoft.advance_payments_invoices_fees_installments
          GROUP BY advance_payments_invoices_fees_installments.invoices_fees_installments_id) d ON ((d.invoices_fees_installments_id = b.id)))
     LEFT JOIN shulesoft.discount_fees_installments e ON (((e.fees_installment_id = a.fees_installment_id) AND (f.student_id = e.student_id))))
  ORDER BY h.start_date, i.priority;


ALTER VIEW shulesoft.next_year_invoices_fees_installments_balance OWNER TO postgres;

--
-- TOC entry 2269 (class 1259 OID 51647)
-- Name: next_year_transport_installment_detail; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.next_year_transport_installment_detail AS
 SELECT a.student_id,
    a.vehicle_id,
    a.is_oneway,
        CASE
            WHEN (a.is_oneway = 0) THEN b.amount
            ELSE (b.amount * 0.5::numeric(10,2))
        END AS amount,
    b.fees_installment_id
   FROM (((((shulesoft.tmembers a
     JOIN shulesoft.transport_routes d ON ((d.id = a.transport_route_id)))
     JOIN shulesoft.transport_routes_fees_installments b ON ((a.transport_route_id = b.transport_route_id)))
     JOIN shulesoft.fees_installments c ON ((c.id = b.fees_installment_id)))
     JOIN shulesoft.installments e ON ((e.id = c.installment_id)))
     JOIN shulesoft.student_archive s ON (((s.student_id = a.student_id) AND (e.academic_year_id <> s.academic_year_id))))
  GROUP BY a.student_id, a.vehicle_id, a.is_oneway, b.amount, b.fees_installment_id, e.id;


ALTER VIEW shulesoft.next_year_transport_installment_detail OWNER TO postgres;

--
-- TOC entry 2270 (class 1259 OID 51652)
-- Name: next_year_transport_invoices_fees_installments_balance; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.next_year_transport_invoices_fees_installments_balance AS
 SELECT COALESCE(f.amount, (0)::numeric) AS total_amount,
    COALESCE(c.total_payment_invoice_amount, (0)::numeric) AS total_payment_invoice_fee_amount,
    COALESCE(d.total_advance_invoice_fee_amount, (0)::numeric) AS total_advance_invoice_fee_amount,
    COALESCE(e.amount, (0)::numeric) AS discount_amount,
    g.student_id,
    g.date AS created_at,
    b.id,
    r.total_amount AS advance_amount,
    b.fees_installment_id,
    h.id AS installment_id,
    h.start_date,
    h.academic_year_id,
    1000 AS fee_id,
    b.invoice_id,
        CASE
            WHEN ((((f.amount - COALESCE(c.total_payment_invoice_amount, (0)::numeric)) - COALESCE(d.total_advance_invoice_fee_amount, (0)::numeric)) - COALESCE(e.amount, (0)::numeric)) > (0)::numeric) THEN 0
            ELSE 1
        END AS status,
    g.schema_name
   FROM ((((((((shulesoft.invoices_fees_installments b
     JOIN shulesoft.invoices g ON ((g.id = b.invoice_id)))
     JOIN shulesoft.next_year_transport_installment_detail f ON (((b.fees_installment_id = f.fees_installment_id) AND (f.student_id = g.student_id))))
     JOIN shulesoft.fees_installments k ON ((k.id = b.fees_installment_id)))
     JOIN shulesoft.installments h ON ((h.id = k.installment_id)))
     LEFT JOIN ( SELECT sum(payments_invoices_fees_installments.amount) AS total_payment_invoice_amount,
            payments_invoices_fees_installments.invoices_fees_installment_id
           FROM shulesoft.payments_invoices_fees_installments
          GROUP BY payments_invoices_fees_installments.invoices_fees_installment_id) c ON ((c.invoices_fees_installment_id = b.id)))
     LEFT JOIN ( SELECT sum(advance_payments_invoices_fees_installments.amount) AS total_advance_invoice_fee_amount,
            advance_payments_invoices_fees_installments.invoices_fees_installments_id
           FROM shulesoft.advance_payments_invoices_fees_installments
          GROUP BY advance_payments_invoices_fees_installments.invoices_fees_installments_id) d ON ((d.invoices_fees_installments_id = b.id)))
     LEFT JOIN shulesoft.discount_fees_installments e ON (((e.fees_installment_id = b.fees_installment_id) AND (g.student_id = e.student_id))))
     LEFT JOIN shulesoft.advance_payment_balance r ON (((r.fee_id = 1000) AND (r.student_id = g.student_id))))
  WHERE (k.fee_id = 1000)
  ORDER BY h.start_date;


ALTER VIEW shulesoft.next_year_transport_invoices_fees_installments_balance OWNER TO postgres;

--
-- TOC entry 2271 (class 1259 OID 51657)
-- Name: next_year_invoice_balances; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.next_year_invoice_balances AS
 SELECT next_year_transport_invoices_fees_installments_balance.student_id,
    next_year_transport_invoices_fees_installments_balance.created_at,
    next_year_transport_invoices_fees_installments_balance.total_amount,
    next_year_transport_invoices_fees_installments_balance.total_payment_invoice_fee_amount,
    next_year_transport_invoices_fees_installments_balance.total_advance_invoice_fee_amount,
    next_year_transport_invoices_fees_installments_balance.discount_amount,
    next_year_transport_invoices_fees_installments_balance.fees_installment_id,
    next_year_transport_invoices_fees_installments_balance.installment_id,
    next_year_transport_invoices_fees_installments_balance.start_date,
    next_year_transport_invoices_fees_installments_balance.academic_year_id,
    next_year_transport_invoices_fees_installments_balance.fee_id,
    next_year_transport_invoices_fees_installments_balance.id,
    next_year_transport_invoices_fees_installments_balance.invoice_id,
    (((COALESCE(next_year_transport_invoices_fees_installments_balance.total_amount, (0)::numeric) - COALESCE(next_year_transport_invoices_fees_installments_balance.total_payment_invoice_fee_amount, (0)::numeric)) - COALESCE(next_year_transport_invoices_fees_installments_balance.total_advance_invoice_fee_amount, (0)::numeric)) - COALESCE(next_year_transport_invoices_fees_installments_balance.discount_amount, (0)::numeric)) AS balance,
    next_year_transport_invoices_fees_installments_balance.schema_name
   FROM shulesoft.next_year_transport_invoices_fees_installments_balance
UNION ALL
 SELECT next_year_invoices_fees_installments_balance.student_id,
    next_year_invoices_fees_installments_balance.created_at,
    next_year_invoices_fees_installments_balance.total_amount,
    next_year_invoices_fees_installments_balance.total_payment_invoice_fee_amount,
    next_year_invoices_fees_installments_balance.total_advance_invoice_fee_amount,
    next_year_invoices_fees_installments_balance.discount_amount,
    next_year_invoices_fees_installments_balance.fees_installment_id,
    next_year_invoices_fees_installments_balance.installment_id,
    next_year_invoices_fees_installments_balance.start_date,
    next_year_invoices_fees_installments_balance.academic_year_id,
    next_year_invoices_fees_installments_balance.fee_id,
    next_year_invoices_fees_installments_balance.id,
    next_year_invoices_fees_installments_balance.invoice_id,
    (((COALESCE(next_year_invoices_fees_installments_balance.total_amount, (0)::numeric) - COALESCE(next_year_invoices_fees_installments_balance.total_payment_invoice_fee_amount, (0)::numeric)) - COALESCE(next_year_invoices_fees_installments_balance.total_advance_invoice_fee_amount, (0)::numeric)) - COALESCE(next_year_invoices_fees_installments_balance.discount_amount, (0)::numeric)) AS balance,
    next_year_invoices_fees_installments_balance.schema_name
   FROM shulesoft.next_year_invoices_fees_installments_balance;


ALTER VIEW shulesoft.next_year_invoice_balances OWNER TO postgres;

--
-- TOC entry 1794 (class 1259 OID 49744)
-- Name: notice_noticeID_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft."notice_noticeID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft."notice_noticeID_seq" OWNER TO postgres;

--
-- TOC entry 1795 (class 1259 OID 49745)
-- Name: notice; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.notice (
    "noticeID" integer DEFAULT nextval('shulesoft."notice_noticeID_seq"'::regclass) NOT NULL,
    title character varying(128) NOT NULL,
    notice text NOT NULL,
    year integer NOT NULL,
    date date NOT NULL,
    create_date timestamp without time zone DEFAULT now() NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    class_id character varying,
    to_roll_id character varying,
    notice_for character varying,
    status integer DEFAULT 0,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone,
    closed_for_staff smallint DEFAULT 0
);


ALTER TABLE shulesoft.notice OWNER TO postgres;

--
-- TOC entry 2272 (class 1259 OID 51662)
-- Name: notice_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.notice_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.notice_uid_seq OWNER TO postgres;

--
-- TOC entry 14171 (class 0 OID 0)
-- Dependencies: 2272
-- Name: notice_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.notice_uid_seq OWNED BY shulesoft.notice.uid;


--
-- TOC entry 2273 (class 1259 OID 51663)
-- Name: page_tips_viewers_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.page_tips_viewers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.page_tips_viewers_id_seq OWNER TO postgres;

--
-- TOC entry 2274 (class 1259 OID 51664)
-- Name: page_tips_viewers; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.page_tips_viewers (
    id integer DEFAULT nextval('shulesoft.page_tips_viewers_id_seq'::regclass) NOT NULL,
    user_id integer,
    "table" character varying,
    page_tip_id integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    is_helpful integer,
    not_helpful_comment text,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.page_tips_viewers OWNER TO postgres;

--
-- TOC entry 2275 (class 1259 OID 51672)
-- Name: page_tips_viewers_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.page_tips_viewers_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.page_tips_viewers_uid_seq OWNER TO postgres;

--
-- TOC entry 14172 (class 0 OID 0)
-- Dependencies: 2275
-- Name: page_tips_viewers_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.page_tips_viewers_uid_seq OWNED BY shulesoft.page_tips_viewers.uid;


--
-- TOC entry 2276 (class 1259 OID 51673)
-- Name: parent_documents_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.parent_documents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.parent_documents_id_seq OWNER TO postgres;

--
-- TOC entry 2277 (class 1259 OID 51674)
-- Name: parent_documents; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.parent_documents (
    id integer DEFAULT nextval('shulesoft.parent_documents_id_seq'::regclass) NOT NULL,
    type character varying,
    parent_id integer,
    attach text,
    attach_file_name text,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.parent_documents OWNER TO postgres;

--
-- TOC entry 2278 (class 1259 OID 51682)
-- Name: parent_documents_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.parent_documents_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.parent_documents_uid_seq OWNER TO postgres;

--
-- TOC entry 14173 (class 0 OID 0)
-- Dependencies: 2278
-- Name: parent_documents_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.parent_documents_uid_seq OWNED BY shulesoft.parent_documents.uid;


--
-- TOC entry 2279 (class 1259 OID 51683)
-- Name: parent_phones_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.parent_phones_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.parent_phones_id_seq OWNER TO postgres;

--
-- TOC entry 2280 (class 1259 OID 51684)
-- Name: parent_phones; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.parent_phones (
    id integer DEFAULT nextval('shulesoft.parent_phones_id_seq'::regclass) NOT NULL,
    parent_id integer NOT NULL,
    phone character varying,
    updated_at date,
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.parent_phones OWNER TO postgres;

--
-- TOC entry 2281 (class 1259 OID 51692)
-- Name: parent_phones_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.parent_phones_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.parent_phones_uid_seq OWNER TO postgres;

--
-- TOC entry 14174 (class 0 OID 0)
-- Dependencies: 2281
-- Name: parent_phones_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.parent_phones_uid_seq OWNED BY shulesoft.parent_phones.uid;


--
-- TOC entry 2282 (class 1259 OID 51693)
-- Name: parent_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.parent_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.parent_uid_seq OWNER TO postgres;

--
-- TOC entry 14175 (class 0 OID 0)
-- Dependencies: 2282
-- Name: parent_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.parent_uid_seq OWNED BY shulesoft.parent.uid;


--
-- TOC entry 2283 (class 1259 OID 51694)
-- Name: payment_distribution_status; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.payment_distribution_status AS
 SELECT
        CASE
            WHEN ((((a.amount - COALESCE(b.amount, (0)::numeric)) - COALESCE(c.amount, (0)::numeric)) - COALESCE(d.amount, (0)::numeric)) > (0)::numeric) THEN 0
            ELSE 1
        END AS status,
    a.status AS type,
    a.id,
    a.student_id,
    a.amount AS original_amount,
    a.date,
    (a.amount - ((COALESCE(b.amount, (0)::numeric) + COALESCE(c.amount, (0)::numeric)) + COALESCE(d.amount, (0)::numeric))) AS undistributed_amount,
    a.schema_name
   FROM (((shulesoft.payments a
     LEFT JOIN ( SELECT sum(advance_payments.amount) AS amount,
            advance_payments.payment_id,
            advance_payments.schema_name
           FROM shulesoft.advance_payments
          GROUP BY advance_payments.payment_id, advance_payments.schema_name) b ON (((b.payment_id = a.id) AND ((a.schema_name)::text = (b.schema_name)::text))))
     LEFT JOIN ( SELECT sum(due_amounts_payments.amount) AS amount,
            due_amounts_payments.payment_id,
            due_amounts_payments.schema_name
           FROM shulesoft.due_amounts_payments
          GROUP BY due_amounts_payments.payment_id, due_amounts_payments.schema_name) c ON (((c.payment_id = a.id) AND ((a.schema_name)::text = (c.schema_name)::text))))
     LEFT JOIN ( SELECT sum(payments_invoices_fees_installments.amount) AS amount,
            payments_invoices_fees_installments.payment_id,
            payments_invoices_fees_installments.schema_name
           FROM shulesoft.payments_invoices_fees_installments
          GROUP BY payments_invoices_fees_installments.payment_id, payments_invoices_fees_installments.schema_name) d ON (((d.payment_id = a.id) AND ((a.schema_name)::text = (d.schema_name)::text))));


ALTER VIEW shulesoft.payment_distribution_status OWNER TO postgres;

--
-- TOC entry 2284 (class 1259 OID 51699)
-- Name: payment_types_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.payment_types_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.payment_types_uid_seq OWNER TO postgres;

--
-- TOC entry 14176 (class 0 OID 0)
-- Dependencies: 2284
-- Name: payment_types_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.payment_types_uid_seq OWNED BY shulesoft.payment_types.uid;


--
-- TOC entry 2285 (class 1259 OID 51700)
-- Name: payments_invoices_fees_installments_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.payments_invoices_fees_installments_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.payments_invoices_fees_installments_uid_seq OWNER TO postgres;

--
-- TOC entry 14177 (class 0 OID 0)
-- Dependencies: 2285
-- Name: payments_invoices_fees_installments_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.payments_invoices_fees_installments_uid_seq OWNED BY shulesoft.payments_invoices_fees_installments.uid;


--
-- TOC entry 2286 (class 1259 OID 51701)
-- Name: payments_receipt_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.payments_receipt_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.payments_receipt_seq OWNER TO postgres;

--
-- TOC entry 14178 (class 0 OID 0)
-- Dependencies: 2286
-- Name: payments_receipt_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.payments_receipt_seq OWNED BY shulesoft.payments.receipt_code;


--
-- TOC entry 2287 (class 1259 OID 51702)
-- Name: payments_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.payments_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.payments_uid_seq OWNER TO postgres;

--
-- TOC entry 14179 (class 0 OID 0)
-- Dependencies: 2287
-- Name: payments_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.payments_uid_seq OWNED BY shulesoft.payments.uid;


--
-- TOC entry 2288 (class 1259 OID 51703)
-- Name: payroll_setting_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.payroll_setting_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.payroll_setting_id_seq OWNER TO postgres;

--
-- TOC entry 2289 (class 1259 OID 51704)
-- Name: payroll_setting; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.payroll_setting (
    id integer DEFAULT nextval('shulesoft.payroll_setting_id_seq'::regclass) NOT NULL,
    show_allowance smallint DEFAULT 0,
    show_basic_pay smallint DEFAULT 0,
    show_bank smallint DEFAULT 0,
    show_bank_account smallint DEFAULT 0,
    show_gross_pay smallint DEFAULT 0,
    show_pansion smallint DEFAULT 0,
    show_deduction smallint DEFAULT 0,
    show_taxable_amount smallint DEFAULT 0,
    show_paye smallint DEFAULT 0,
    show_net_payment smallint DEFAULT 0,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    show_status smallint DEFAULT 1,
    show_date smallint DEFAULT 1,
    show_individual_deduction smallint DEFAULT 0,
    show_action smallint DEFAULT 1,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.payroll_setting OWNER TO postgres;

--
-- TOC entry 2290 (class 1259 OID 51725)
-- Name: payroll_setting_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.payroll_setting_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.payroll_setting_uid_seq OWNER TO postgres;

--
-- TOC entry 14180 (class 0 OID 0)
-- Dependencies: 2290
-- Name: payroll_setting_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.payroll_setting_uid_seq OWNED BY shulesoft.payroll_setting.uid;


--
-- TOC entry 2291 (class 1259 OID 51726)
-- Name: payslip_settings_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.payslip_settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.payslip_settings_id_seq OWNER TO postgres;

--
-- TOC entry 2292 (class 1259 OID 51727)
-- Name: payslip_settings; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.payslip_settings (
    id integer DEFAULT nextval('shulesoft.payslip_settings_id_seq'::regclass) NOT NULL,
    show_employee_signature smallint DEFAULT 0,
    show_employer_signature smallint DEFAULT 0,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    show_employee_digital_signature smallint DEFAULT 1,
    show_employer_digital_signature smallint DEFAULT 1,
    show_address smallint,
    show_employer_contribution integer DEFAULT 1,
    show_tax_summary integer DEFAULT 1,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.payslip_settings OWNER TO postgres;

--
-- TOC entry 2293 (class 1259 OID 51740)
-- Name: payslip_settings_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.payslip_settings_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.payslip_settings_uid_seq OWNER TO postgres;

--
-- TOC entry 14181 (class 0 OID 0)
-- Dependencies: 2293
-- Name: payslip_settings_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.payslip_settings_uid_seq OWNED BY shulesoft.payslip_settings.uid;


--
-- TOC entry 2294 (class 1259 OID 51741)
-- Name: pensions_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.pensions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.pensions_id_seq OWNER TO postgres;

--
-- TOC entry 2295 (class 1259 OID 51742)
-- Name: pensions; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.pensions (
    id integer DEFAULT nextval('shulesoft.pensions_id_seq'::regclass) NOT NULL,
    name character varying,
    employer_percentage double precision,
    employee_percentage double precision,
    created_at timestamp without time zone,
    address character varying,
    updated_at timestamp without time zone,
    refer_pension_id integer,
    status integer DEFAULT 0,
    reg character varying(30),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.pensions OWNER TO postgres;

--
-- TOC entry 2296 (class 1259 OID 51750)
-- Name: pensions_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.pensions_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.pensions_uid_seq OWNER TO postgres;

--
-- TOC entry 14182 (class 0 OID 0)
-- Dependencies: 2296
-- Name: pensions_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.pensions_uid_seq OWNED BY shulesoft.pensions.uid;


--
-- TOC entry 2297 (class 1259 OID 51751)
-- Name: prepayments_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.prepayments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.prepayments_id_seq OWNER TO postgres;

--
-- TOC entry 2298 (class 1259 OID 51752)
-- Name: prepayments; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.prepayments (
    id integer DEFAULT nextval('shulesoft.prepayments_id_seq'::regclass) NOT NULL,
    "invoiceID" integer NOT NULL,
    student_id integer NOT NULL,
    paymentamount numeric NOT NULL,
    paymenttype character varying(128) NOT NULL,
    paymentdate date NOT NULL,
    paymentmonth character varying(10) NOT NULL,
    paymentyear integer NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    transaction_id character varying(120),
    "userID" integer,
    slipfile character varying(250),
    approved integer DEFAULT 0,
    approved_date timestamp without time zone,
    approved_user_id integer,
    cheque_number character varying,
    bank_name character varying,
    payer_name character varying,
    fee_id character varying,
    transaction_time character varying,
    account_number character varying,
    token character varying,
    bank_account_id integer,
    reconciled smallint DEFAULT 0,
    updated_at timestamp without time zone,
    reject_reason text,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.prepayments OWNER TO postgres;

--
-- TOC entry 2299 (class 1259 OID 51762)
-- Name: prepayments_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.prepayments_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.prepayments_uid_seq OWNER TO postgres;

--
-- TOC entry 14183 (class 0 OID 0)
-- Dependencies: 2299
-- Name: prepayments_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.prepayments_uid_seq OWNED BY shulesoft.prepayments.uid;


--
-- TOC entry 2300 (class 1259 OID 51763)
-- Name: product_alert_quantity_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.product_alert_quantity_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.product_alert_quantity_id_seq OWNER TO postgres;

--
-- TOC entry 2301 (class 1259 OID 51764)
-- Name: product_alert_quantity_id_seq1; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.product_alert_quantity_id_seq1
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.product_alert_quantity_id_seq1 OWNER TO postgres;

--
-- TOC entry 14184 (class 0 OID 0)
-- Dependencies: 2301
-- Name: product_alert_quantity_id_seq1; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.product_alert_quantity_id_seq1 OWNED BY shulesoft.product_alert_quantity.id;


--
-- TOC entry 2302 (class 1259 OID 51765)
-- Name: product_alert_quantity_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.product_alert_quantity_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.product_alert_quantity_uid_seq OWNER TO postgres;

--
-- TOC entry 14185 (class 0 OID 0)
-- Dependencies: 2302
-- Name: product_alert_quantity_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.product_alert_quantity_uid_seq OWNED BY shulesoft.product_alert_quantity.uid;


--
-- TOC entry 2303 (class 1259 OID 51766)
-- Name: product_cart_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.product_cart_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.product_cart_uid_seq OWNER TO postgres;

--
-- TOC entry 14186 (class 0 OID 0)
-- Dependencies: 2303
-- Name: product_cart_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.product_cart_uid_seq OWNED BY shulesoft.product_cart.uid;


--
-- TOC entry 2304 (class 1259 OID 51767)
-- Name: warehouse_transfers_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.warehouse_transfers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.warehouse_transfers_id_seq OWNER TO postgres;

--
-- TOC entry 2305 (class 1259 OID 51768)
-- Name: warehouse_transfers; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.warehouse_transfers (
    id integer DEFAULT nextval('shulesoft.warehouse_transfers_id_seq'::regclass) NOT NULL,
    from_warehouse_id integer,
    to_warehouse_id integer,
    product_alert_quantity_id integer,
    created_at timestamp without time zone,
    date date,
    quantity integer,
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.warehouse_transfers OWNER TO postgres;

--
-- TOC entry 2306 (class 1259 OID 51775)
-- Name: product_items_balance; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.product_items_balance AS
 SELECT
        CASE
            WHEN (a.from_warehouse_id = b.warehouse_id) THEN ((0)::numeric - (a.quantity)::numeric)
            ELSE (a.quantity)::numeric
        END AS quantity,
    b.id AS product_alert_quantity_id,
    b.warehouse_id,
    a.date,
    2 AS status,
    a.schema_name
   FROM (shulesoft.warehouse_transfers a
     JOIN shulesoft.product_alert_quantity b ON ((b.id = a.product_alert_quantity_id)))
UNION ALL
 SELECT a.quantity,
    b.id AS product_alert_quantity_id,
    b.warehouse_id,
    a.date,
    1 AS status,
    a.schema_name
   FROM (shulesoft.product_purchases a
     JOIN shulesoft.product_alert_quantity b ON ((b.id = a.product_alert_id)))
UNION ALL
 SELECT ((0)::double precision - a.quantity) AS quantity,
    b.id AS product_alert_quantity_id,
    b.warehouse_id,
    a.date,
    3 AS status,
    a.schema_name
   FROM (shulesoft.product_sales a
     JOIN shulesoft.product_alert_quantity b ON ((b.id = a.product_alert_id)))
UNION ALL
 SELECT
        CASE
            WHEN (b.open_blance IS NULL) THEN (0)::double precision
            ELSE b.open_blance
        END AS quantity,
    b.id AS product_alert_quantity_id,
    b.warehouse_id,
    b.created_at AS date,
    4 AS status,
    b.schema_name
   FROM shulesoft.product_alert_quantity b;


ALTER VIEW shulesoft.product_items_balance OWNER TO postgres;

--
-- TOC entry 2307 (class 1259 OID 51780)
-- Name: product_purchases_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.product_purchases_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.product_purchases_uid_seq OWNER TO postgres;

--
-- TOC entry 14187 (class 0 OID 0)
-- Dependencies: 2307
-- Name: product_purchases_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.product_purchases_uid_seq OWNED BY shulesoft.product_purchases.uid;


--
-- TOC entry 2308 (class 1259 OID 51781)
-- Name: product_quantities; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.product_quantities AS
 SELECT b.id,
    a.name AS category,
    b.name,
    b.note,
    b.alert_quantity,
    c.account_group_id,
    c.financial_category_id,
    c.name AS inventory,
    e.name AS metrics,
    e.abbreviation,
    (b.open_blance + COALESCE(( SELECT sum(product_purchases.quantity) AS sum
           FROM shulesoft.product_purchases
          WHERE (product_purchases.product_alert_id = b.id)), ((0)::bigint)::double precision)) AS total_quantity,
    ((b.open_blance + COALESCE(( SELECT sum(product_purchases.quantity) AS sum
           FROM shulesoft.product_purchases
          WHERE (product_purchases.product_alert_id = b.id)), ((0)::bigint)::double precision)) - COALESCE(( SELECT sum(product_sales.quantity) AS sum
           FROM shulesoft.product_sales
          WHERE (product_sales.product_alert_id = b.id)), ((0)::bigint)::double precision)) AS remain_quantity
   FROM (((shulesoft.product_alert_quantity b
     LEFT JOIN constant.product_registers a ON ((a.id = b.product_register_id)))
     LEFT JOIN shulesoft.refer_expense c ON ((c.id = b.refer_expense_id)))
     LEFT JOIN constant.metrics e ON ((e.id = b.metric_id)));


ALTER VIEW shulesoft.product_quantities OWNER TO postgres;

--
-- TOC entry 2309 (class 1259 OID 51786)
-- Name: product_registers_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.product_registers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.product_registers_id_seq OWNER TO postgres;

--
-- TOC entry 2310 (class 1259 OID 51787)
-- Name: product_registers; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.product_registers (
    id integer DEFAULT nextval('shulesoft.product_registers_id_seq'::regclass) NOT NULL,
    product_name character varying,
    alert_quantity integer,
    product_code character varying,
    refer_expense_id integer,
    unit_id integer,
    created_at timestamp without time zone,
    vendor_id integer,
    comment text,
    contact_person_name character varying,
    contact_person_number character varying,
    account_group_id integer,
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.product_registers OWNER TO postgres;

--
-- TOC entry 2311 (class 1259 OID 51794)
-- Name: product_registers_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.product_registers_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.product_registers_uid_seq OWNER TO postgres;

--
-- TOC entry 14188 (class 0 OID 0)
-- Dependencies: 2311
-- Name: product_registers_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.product_registers_uid_seq OWNED BY shulesoft.product_registers.uid;


--
-- TOC entry 2312 (class 1259 OID 51795)
-- Name: product_sales_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.product_sales_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.product_sales_uid_seq OWNER TO postgres;

--
-- TOC entry 14189 (class 0 OID 0)
-- Dependencies: 2312
-- Name: product_sales_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.product_sales_uid_seq OWNED BY shulesoft.product_sales.uid;


--
-- TOC entry 2313 (class 1259 OID 51796)
-- Name: proforma_invoices; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.proforma_invoices (
    id integer NOT NULL,
    reference character varying,
    student_id integer,
    created_at date DEFAULT now(),
    sync smallint DEFAULT 0,
    return_message text,
    push_status character varying,
    date timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    academic_year_id integer,
    prefix character varying,
    due_date date,
    sid integer,
    token text,
    amount numeric,
    status smallint DEFAULT 0 NOT NULL,
    type smallint DEFAULT 1,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    created_by_sid integer,
    invoice_year character varying,
    term_condition character varying DEFAULT 'All accounts are to be paid up to the Due Date above.'::character varying,
    description text
);


ALTER TABLE shulesoft.proforma_invoices OWNER TO postgres;

--
-- TOC entry 2314 (class 1259 OID 51808)
-- Name: proforma_invoices_fee_amount; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.proforma_invoices_fee_amount (
    id integer NOT NULL,
    proforma_invoice_id integer,
    fee_id integer,
    amount numeric(10,2),
    discount numeric(10,2),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    quantity integer,
    unit_amount numeric
);


ALTER TABLE shulesoft.proforma_invoices_fee_amount OWNER TO postgres;

--
-- TOC entry 2315 (class 1259 OID 51815)
-- Name: proforma_invoices_fee_amount_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.proforma_invoices_fee_amount_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.proforma_invoices_fee_amount_id_seq OWNER TO postgres;

--
-- TOC entry 14190 (class 0 OID 0)
-- Dependencies: 2315
-- Name: proforma_invoices_fee_amount_id_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.proforma_invoices_fee_amount_id_seq OWNED BY shulesoft.proforma_invoices_fee_amount.id;


--
-- TOC entry 2316 (class 1259 OID 51816)
-- Name: proforma_invoices_fee_amount_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.proforma_invoices_fee_amount_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.proforma_invoices_fee_amount_uid_seq OWNER TO postgres;

--
-- TOC entry 14191 (class 0 OID 0)
-- Dependencies: 2316
-- Name: proforma_invoices_fee_amount_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.proforma_invoices_fee_amount_uid_seq OWNED BY shulesoft.proforma_invoices_fee_amount.uid;


--
-- TOC entry 2317 (class 1259 OID 51817)
-- Name: proforma_invoices_fees_installments; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.proforma_invoices_fees_installments (
    id integer NOT NULL,
    proforma_invoice_id integer,
    fees_installment_id integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.proforma_invoices_fees_installments OWNER TO postgres;

--
-- TOC entry 2318 (class 1259 OID 51824)
-- Name: proforma_invoices_fees_installments_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.proforma_invoices_fees_installments_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.proforma_invoices_fees_installments_id_seq OWNER TO postgres;

--
-- TOC entry 14192 (class 0 OID 0)
-- Dependencies: 2318
-- Name: proforma_invoices_fees_installments_id_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.proforma_invoices_fees_installments_id_seq OWNED BY shulesoft.proforma_invoices_fees_installments.id;


--
-- TOC entry 2319 (class 1259 OID 51825)
-- Name: proforma_invoices_fees_installments_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.proforma_invoices_fees_installments_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.proforma_invoices_fees_installments_uid_seq OWNER TO postgres;

--
-- TOC entry 14193 (class 0 OID 0)
-- Dependencies: 2319
-- Name: proforma_invoices_fees_installments_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.proforma_invoices_fees_installments_uid_seq OWNED BY shulesoft.proforma_invoices_fees_installments.uid;


--
-- TOC entry 2320 (class 1259 OID 51826)
-- Name: proforma_invoices_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.proforma_invoices_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.proforma_invoices_id_seq OWNER TO postgres;

--
-- TOC entry 14194 (class 0 OID 0)
-- Dependencies: 2320
-- Name: proforma_invoices_id_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.proforma_invoices_id_seq OWNED BY shulesoft.proforma_invoices.id;


--
-- TOC entry 2321 (class 1259 OID 51827)
-- Name: proforma_invoices_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.proforma_invoices_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.proforma_invoices_uid_seq OWNER TO postgres;

--
-- TOC entry 14195 (class 0 OID 0)
-- Dependencies: 2321
-- Name: proforma_invoices_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.proforma_invoices_uid_seq OWNED BY shulesoft.proforma_invoices.uid;


--
-- TOC entry 2322 (class 1259 OID 51828)
-- Name: proforma_payments; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.proforma_payments (
    id integer NOT NULL,
    proforma_invoice_id integer NOT NULL,
    student_id integer NOT NULL,
    amount numeric NOT NULL,
    payment_type_id integer,
    date date NOT NULL,
    transaction_id character varying,
    created_at timestamp without time zone DEFAULT now(),
    cheque_number character varying,
    bank_account_id integer,
    payer_name character varying,
    mobile_transaction_id character varying,
    transaction_time character varying,
    account_number character varying,
    token character varying,
    reconciled smallint DEFAULT 0,
    receipt_code character varying DEFAULT nextval('shulesoft.payments_receipt_seq'::regclass),
    updated_at timestamp without time zone,
    channel character varying,
    amount_entered numeric,
    created_by integer,
    created_by_table character varying,
    note character varying DEFAULT 'Fee Payments'::character varying,
    invoice_id integer,
    status smallint DEFAULT 0,
    sid integer,
    priority character varying DEFAULT '{0}'::integer[],
    comment text,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer DEFAULT nextval('shulesoft.payments_uid_seq'::regclass) NOT NULL,
    verification_code character varying,
    verification_url character varying,
    code character varying,
    refer_expense_id integer
);


ALTER TABLE shulesoft.proforma_payments OWNER TO postgres;

--
-- TOC entry 2323 (class 1259 OID 51841)
-- Name: proforma_payments_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.proforma_payments_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.proforma_payments_id_seq OWNER TO postgres;

--
-- TOC entry 14196 (class 0 OID 0)
-- Dependencies: 2323
-- Name: proforma_payments_id_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.proforma_payments_id_seq OWNED BY shulesoft.proforma_payments.id;


--
-- TOC entry 2324 (class 1259 OID 51842)
-- Name: promotionsubject_promotionSubjectID_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft."promotionsubject_promotionSubjectID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft."promotionsubject_promotionSubjectID_seq" OWNER TO postgres;

--
-- TOC entry 2325 (class 1259 OID 51843)
-- Name: promotionsubject; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.promotionsubject (
    "promotionSubjectID" integer DEFAULT nextval('shulesoft."promotionsubject_promotionSubjectID_seq"'::regclass) NOT NULL,
    "classesID" integer NOT NULL,
    "subjectID" integer NOT NULL,
    "subjectCode" text NOT NULL,
    "subjectMark" integer,
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.promotionsubject OWNER TO postgres;

--
-- TOC entry 2326 (class 1259 OID 51851)
-- Name: promotionsubject_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.promotionsubject_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.promotionsubject_uid_seq OWNER TO postgres;

--
-- TOC entry 14197 (class 0 OID 0)
-- Dependencies: 2326
-- Name: promotionsubject_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.promotionsubject_uid_seq OWNED BY shulesoft.promotionsubject.uid;


--
-- TOC entry 2327 (class 1259 OID 51852)
-- Name: questions_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.questions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.questions_id_seq OWNER TO postgres;

--
-- TOC entry 2328 (class 1259 OID 51853)
-- Name: questions; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.questions (
    id integer DEFAULT nextval('shulesoft.questions_id_seq'::regclass) NOT NULL,
    minor_exam_id integer,
    question text,
    weight integer DEFAULT 1 NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    attach text,
    type integer DEFAULT 1,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.questions OWNER TO postgres;

--
-- TOC entry 2329 (class 1259 OID 51863)
-- Name: questions_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.questions_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.questions_uid_seq OWNER TO postgres;

--
-- TOC entry 14198 (class 0 OID 0)
-- Dependencies: 2329
-- Name: questions_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.questions_uid_seq OWNED BY shulesoft.questions.uid;


--
-- TOC entry 2330 (class 1259 OID 51864)
-- Name: receipt_settings_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.receipt_settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.receipt_settings_id_seq OWNER TO postgres;

--
-- TOC entry 2331 (class 1259 OID 51865)
-- Name: receipt_settings; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.receipt_settings (
    id integer DEFAULT nextval('shulesoft.receipt_settings_id_seq'::regclass) NOT NULL,
    show_installment smallint DEFAULT 0,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    show_class smallint DEFAULT 1,
    template character varying,
    available_templates character varying,
    show_single_fee smallint DEFAULT 0,
    copy_to_print character varying DEFAULT 1,
    show_balance smallint DEFAULT 0,
    show_digital_signature smallint DEFAULT 0,
    show_school_stamp smallint DEFAULT 0,
    show_stream smallint DEFAULT 1,
    show_fee_amount smallint DEFAULT 0,
    show_invoice_prefix smallint DEFAULT 1,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.receipt_settings OWNER TO postgres;

--
-- TOC entry 2332 (class 1259 OID 51882)
-- Name: receipt_settings_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.receipt_settings_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.receipt_settings_uid_seq OWNER TO postgres;

--
-- TOC entry 14199 (class 0 OID 0)
-- Dependencies: 2332
-- Name: receipt_settings_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.receipt_settings_uid_seq OWNED BY shulesoft.receipt_settings.uid;


--
-- TOC entry 2333 (class 1259 OID 51883)
-- Name: refer_character_grading_systems_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.refer_character_grading_systems_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.refer_character_grading_systems_id_seq OWNER TO postgres;

--
-- TOC entry 2334 (class 1259 OID 51884)
-- Name: refer_character_grading_systems; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.refer_character_grading_systems (
    id integer DEFAULT nextval('shulesoft.refer_character_grading_systems_id_seq'::regclass) NOT NULL,
    grade_remark character varying(30) NOT NULL,
    grade character varying NOT NULL,
    description text,
    points integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.refer_character_grading_systems OWNER TO postgres;

--
-- TOC entry 2335 (class 1259 OID 51892)
-- Name: refer_character_grading_systems_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.refer_character_grading_systems_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.refer_character_grading_systems_uid_seq OWNER TO postgres;

--
-- TOC entry 14200 (class 0 OID 0)
-- Dependencies: 2335
-- Name: refer_character_grading_systems_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.refer_character_grading_systems_uid_seq OWNED BY shulesoft.refer_character_grading_systems.uid;


--
-- TOC entry 2336 (class 1259 OID 51893)
-- Name: refer_exam_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.refer_exam_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.refer_exam_id_seq OWNER TO postgres;

--
-- TOC entry 2337 (class 1259 OID 51894)
-- Name: refer_exam; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.refer_exam (
    id integer DEFAULT nextval('shulesoft.refer_exam_id_seq'::regclass) NOT NULL,
    name character varying,
    classlevel_id integer,
    note text,
    created_at timestamp without time zone DEFAULT now(),
    abbreviation character varying,
    exam_group_id integer,
    updated_at timestamp without time zone,
    target integer DEFAULT 50,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.refer_exam OWNER TO postgres;

--
-- TOC entry 2338 (class 1259 OID 51903)
-- Name: refer_exam_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.refer_exam_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.refer_exam_uid_seq OWNER TO postgres;

--
-- TOC entry 14201 (class 0 OID 0)
-- Dependencies: 2338
-- Name: refer_exam_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.refer_exam_uid_seq OWNED BY shulesoft.refer_exam.uid;


--
-- TOC entry 2339 (class 1259 OID 51904)
-- Name: refer_expense_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.refer_expense_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.refer_expense_uid_seq OWNER TO postgres;

--
-- TOC entry 14202 (class 0 OID 0)
-- Dependencies: 2339
-- Name: refer_expense_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.refer_expense_uid_seq OWNED BY shulesoft.refer_expense.uid;


--
-- TOC entry 2340 (class 1259 OID 51905)
-- Name: refer_subject_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.refer_subject_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.refer_subject_uid_seq OWNER TO postgres;

--
-- TOC entry 14203 (class 0 OID 0)
-- Dependencies: 2340
-- Name: refer_subject_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.refer_subject_uid_seq OWNED BY shulesoft.refer_subject.uid;


--
-- TOC entry 2341 (class 1259 OID 51906)
-- Name: reminders_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.reminders_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.reminders_id_seq OWNER TO postgres;

--
-- TOC entry 2342 (class 1259 OID 51907)
-- Name: reminders; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.reminders (
    id integer DEFAULT nextval('shulesoft.reminders_id_seq'::regclass) NOT NULL,
    user_sid character varying,
    role_id character varying,
    date timestamp without time zone,
    "time" timestamp without time zone,
    mailandsmstemplate_id integer,
    title character varying,
    is_repeated smallint DEFAULT 0,
    days character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    message text,
    type smallint DEFAULT 0,
    category character varying,
    student_id integer,
    last_schedule_date date,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    is_letter smallint DEFAULT 0,
    user_id character varying
);


ALTER TABLE shulesoft.reminders OWNER TO postgres;

--
-- TOC entry 2343 (class 1259 OID 51918)
-- Name: reminders_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.reminders_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.reminders_uid_seq OWNER TO postgres;

--
-- TOC entry 14204 (class 0 OID 0)
-- Dependencies: 2343
-- Name: reminders_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.reminders_uid_seq OWNED BY shulesoft.reminders.uid;


--
-- TOC entry 2344 (class 1259 OID 51919)
-- Name: reply_msg_replyID_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft."reply_msg_replyID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft."reply_msg_replyID_seq" OWNER TO postgres;

--
-- TOC entry 2345 (class 1259 OID 51920)
-- Name: reply_msg; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.reply_msg (
    "replyID" integer DEFAULT nextval('shulesoft."reply_msg_replyID_seq"'::regclass) NOT NULL,
    "messageID" integer NOT NULL,
    reply_msg text NOT NULL,
    status integer NOT NULL,
    create_time timestamp without time zone DEFAULT now() NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    sender_id integer,
    sender_table character varying,
    updated_at time with time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.reply_msg OWNER TO postgres;

--
-- TOC entry 2346 (class 1259 OID 51929)
-- Name: reply_msg_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.reply_msg_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.reply_msg_uid_seq OWNER TO postgres;

--
-- TOC entry 14205 (class 0 OID 0)
-- Dependencies: 2346
-- Name: reply_msg_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.reply_msg_uid_seq OWNED BY shulesoft.reply_msg.uid;


--
-- TOC entry 2347 (class 1259 OID 51930)
-- Name: reply_sms_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.reply_sms_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.reply_sms_id_seq OWNER TO postgres;

--
-- TOC entry 2348 (class 1259 OID 51931)
-- Name: reply_sms; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.reply_sms (
    id integer DEFAULT nextval('shulesoft.reply_sms_id_seq'::regclass) NOT NULL,
    secret character varying,
    "from" character varying,
    message_id character varying,
    message character varying,
    sent_to character varying,
    device_id character varying,
    created_at timestamp without time zone DEFAULT now(),
    "table" character varying,
    user_id integer,
    sent_timestamp character varying,
    opened integer DEFAULT 0,
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.reply_sms OWNER TO postgres;

--
-- TOC entry 2349 (class 1259 OID 51940)
-- Name: reply_sms_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.reply_sms_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.reply_sms_uid_seq OWNER TO postgres;

--
-- TOC entry 14206 (class 0 OID 0)
-- Dependencies: 2349
-- Name: reply_sms_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.reply_sms_uid_seq OWNED BY shulesoft.reply_sms.uid;


--
-- TOC entry 2350 (class 1259 OID 51941)
-- Name: reset_resetID_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft."reset_resetID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft."reset_resetID_seq" OWNER TO postgres;

--
-- TOC entry 2351 (class 1259 OID 51942)
-- Name: reset; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.reset (
    "resetID" integer DEFAULT nextval('shulesoft."reset_resetID_seq"'::regclass) NOT NULL,
    "keyID" character varying(128) NOT NULL,
    email character varying(60) NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    code character varying(15),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.reset OWNER TO postgres;

--
-- TOC entry 2352 (class 1259 OID 51950)
-- Name: reset_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.reset_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.reset_uid_seq OWNER TO postgres;

--
-- TOC entry 14207 (class 0 OID 0)
-- Dependencies: 2352
-- Name: reset_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.reset_uid_seq OWNED BY shulesoft.reset.uid;


--
-- TOC entry 2353 (class 1259 OID 51951)
-- Name: revenue; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.revenue (
    id integer NOT NULL,
    uid integer NOT NULL,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    refer_expense_id integer,
    account_id integer,
    category character varying,
    transaction_id character varying,
    reference character varying,
    amount numeric,
    user_sid integer,
    created_by_sid integer,
    note text,
    reconciled smallint DEFAULT 0,
    number integer DEFAULT nextval('public.revenues_number_seq'::regclass) NOT NULL,
    sms_sent smallint,
    date timestamp without time zone,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    schema_name character varying,
    status smallint DEFAULT 1
);


ALTER TABLE shulesoft.revenue OWNER TO postgres;

--
-- TOC entry 2354 (class 1259 OID 51961)
-- Name: revenue_cart_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.revenue_cart_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.revenue_cart_id_seq OWNER TO postgres;

--
-- TOC entry 2355 (class 1259 OID 51962)
-- Name: revenue_cart; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.revenue_cart (
    id integer DEFAULT nextval('shulesoft.revenue_cart_id_seq'::regclass) NOT NULL,
    name character varying,
    note text,
    created_by_table character varying,
    date date,
    revenue_id integer NOT NULL,
    refer_expense_id integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    amount numeric NOT NULL,
    created_by_id integer,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.revenue_cart OWNER TO postgres;

--
-- TOC entry 2356 (class 1259 OID 51970)
-- Name: revenue_cart_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.revenue_cart_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.revenue_cart_uid_seq OWNER TO postgres;

--
-- TOC entry 14208 (class 0 OID 0)
-- Dependencies: 2356
-- Name: revenue_cart_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.revenue_cart_uid_seq OWNED BY shulesoft.revenue_cart.uid;


--
-- TOC entry 2357 (class 1259 OID 51971)
-- Name: revenue_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.revenue_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.revenue_id_seq OWNER TO postgres;

--
-- TOC entry 14209 (class 0 OID 0)
-- Dependencies: 2357
-- Name: revenue_id_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.revenue_id_seq OWNED BY shulesoft.revenue.id;


--
-- TOC entry 2358 (class 1259 OID 51972)
-- Name: revenue_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.revenue_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.revenue_uid_seq OWNER TO postgres;

--
-- TOC entry 14210 (class 0 OID 0)
-- Dependencies: 2358
-- Name: revenue_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.revenue_uid_seq OWNED BY shulesoft.revenue.uid;


--
-- TOC entry 2359 (class 1259 OID 51973)
-- Name: revenue_view; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.revenue_view AS
 SELECT a.id,
    a.created_at,
    a.date,
    a.note,
    b.amount,
    a.user_id,
    a.number,
    a.user_in_shulesoft,
    a.invoice_number,
    a.payment_method,
    a.bank_account_id,
    a.transaction_id,
    a.user_table,
    a.payer_name,
    a.payer_phone,
    a.payment_type_id,
    b.refer_expense_id
   FROM (shulesoft.revenues a
     JOIN shulesoft.revenue_cart b ON ((b.revenue_id = a.id)))
UNION ALL
 SELECT a.id,
    a.created_at,
    a.date,
    a.note,
    b.amount,
    a.user_id,
    a.number,
    a.user_in_shulesoft,
    a.invoice_number,
    a.payment_method,
    a.bank_account_id,
    a.transaction_id,
    a.user_table,
    a.payer_name,
    a.payer_phone,
    a.payment_type_id,
    c.refer_expense_id
   FROM ((shulesoft.revenues a
     JOIN shulesoft.product_cart b ON ((a.id = b.revenue_id)))
     JOIN shulesoft.product_alert_quantity c ON ((c.id = b.product_alert_id)));


ALTER VIEW shulesoft.revenue_view OWNER TO postgres;

--
-- TOC entry 2360 (class 1259 OID 51978)
-- Name: revenues_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.revenues_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.revenues_uid_seq OWNER TO postgres;

--
-- TOC entry 14211 (class 0 OID 0)
-- Dependencies: 2360
-- Name: revenues_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.revenues_uid_seq OWNED BY shulesoft.revenues.uid;


--
-- TOC entry 2361 (class 1259 OID 51979)
-- Name: role_permission_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.role_permission_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.role_permission_id_seq OWNER TO postgres;

--
-- TOC entry 2362 (class 1259 OID 51980)
-- Name: role_permission; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.role_permission (
    id integer DEFAULT nextval('shulesoft.role_permission_id_seq'::regclass) NOT NULL,
    role_id integer NOT NULL,
    permission_id integer NOT NULL,
    created_at timestamp without time zone DEFAULT ('now'::text)::timestamp(0) without time zone NOT NULL,
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.role_permission OWNER TO postgres;

--
-- TOC entry 2363 (class 1259 OID 51988)
-- Name: role_permission_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.role_permission_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.role_permission_uid_seq OWNER TO postgres;

--
-- TOC entry 14212 (class 0 OID 0)
-- Dependencies: 2363
-- Name: role_permission_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.role_permission_uid_seq OWNED BY shulesoft.role_permission.uid;


--
-- TOC entry 2364 (class 1259 OID 51989)
-- Name: role_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.role_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.role_uid_seq OWNER TO postgres;

--
-- TOC entry 14213 (class 0 OID 0)
-- Dependencies: 2364
-- Name: role_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.role_uid_seq OWNED BY shulesoft.role.uid;


--
-- TOC entry 2365 (class 1259 OID 51990)
-- Name: route_vehicle_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.route_vehicle_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.route_vehicle_id_seq OWNER TO postgres;

--
-- TOC entry 2366 (class 1259 OID 51991)
-- Name: route_vehicle; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.route_vehicle (
    id integer DEFAULT nextval('shulesoft.route_vehicle_id_seq'::regclass) NOT NULL,
    transport_id integer,
    vehicle_id integer,
    created_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.route_vehicle OWNER TO postgres;

--
-- TOC entry 2367 (class 1259 OID 51998)
-- Name: route_vehicle_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.route_vehicle_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.route_vehicle_uid_seq OWNER TO postgres;

--
-- TOC entry 14214 (class 0 OID 0)
-- Dependencies: 2367
-- Name: route_vehicle_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.route_vehicle_uid_seq OWNED BY shulesoft.route_vehicle.uid;


--
-- TOC entry 2368 (class 1259 OID 51999)
-- Name: routine_routineID_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft."routine_routineID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft."routine_routineID_seq" OWNER TO postgres;

--
-- TOC entry 2369 (class 1259 OID 52000)
-- Name: routine; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.routine (
    "routineID" integer DEFAULT nextval('shulesoft."routine_routineID_seq"'::regclass) NOT NULL,
    "classesID" integer NOT NULL,
    "sectionID" integer NOT NULL,
    "subjectID" integer NOT NULL,
    day character varying(60) NOT NULL,
    room text NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    start_time character varying(50) NOT NULL,
    end_time character varying(50) NOT NULL,
    updated_at time without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.routine OWNER TO postgres;

--
-- TOC entry 2370 (class 1259 OID 52008)
-- Name: routine_daily_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.routine_daily_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.routine_daily_id_seq OWNER TO postgres;

--
-- TOC entry 2371 (class 1259 OID 52009)
-- Name: routine_daily; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.routine_daily (
    id integer DEFAULT nextval('shulesoft.routine_daily_id_seq'::regclass) NOT NULL,
    day character varying(60) NOT NULL,
    start_time character varying NOT NULL,
    end_time character varying NOT NULL,
    activity text NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    class_level_id integer,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.routine_daily OWNER TO postgres;

--
-- TOC entry 2372 (class 1259 OID 52017)
-- Name: routine_daily_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.routine_daily_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.routine_daily_uid_seq OWNER TO postgres;

--
-- TOC entry 14215 (class 0 OID 0)
-- Dependencies: 2372
-- Name: routine_daily_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.routine_daily_uid_seq OWNED BY shulesoft.routine_daily.uid;


--
-- TOC entry 2373 (class 1259 OID 52018)
-- Name: routine_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.routine_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.routine_uid_seq OWNER TO postgres;

--
-- TOC entry 14216 (class 0 OID 0)
-- Dependencies: 2373
-- Name: routine_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.routine_uid_seq OWNED BY shulesoft.routine.uid;


--
-- TOC entry 1799 (class 1259 OID 49778)
-- Name: salaries_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.salaries_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.salaries_id_seq OWNER TO postgres;

--
-- TOC entry 1800 (class 1259 OID 49779)
-- Name: salaries; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.salaries (
    id integer DEFAULT nextval('shulesoft.salaries_id_seq'::regclass) NOT NULL,
    user_id integer,
    "table" character varying,
    basic_pay double precision,
    allowance double precision,
    gross_pay double precision,
    pension_fund double precision,
    deduction double precision,
    tax double precision,
    paye double precision,
    net_pay double precision,
    payment_date date,
    created_at timestamp without time zone DEFAULT now(),
    reference character varying,
    allowance_distribution character varying,
    deduction_distribution character varying,
    pension_distribution character varying,
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    user_sid integer,
    created_by_sid integer
);


ALTER TABLE shulesoft.salaries OWNER TO postgres;

--
-- TOC entry 2374 (class 1259 OID 52019)
-- Name: salaries_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.salaries_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.salaries_uid_seq OWNER TO postgres;

--
-- TOC entry 14217 (class 0 OID 0)
-- Dependencies: 2374
-- Name: salaries_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.salaries_uid_seq OWNED BY shulesoft.salaries.uid;


--
-- TOC entry 2375 (class 1259 OID 52020)
-- Name: salary_allowances_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.salary_allowances_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.salary_allowances_id_seq OWNER TO postgres;

--
-- TOC entry 2376 (class 1259 OID 52021)
-- Name: salary_allowances; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.salary_allowances (
    id integer DEFAULT nextval('shulesoft.salary_allowances_id_seq'::regclass) NOT NULL,
    salary_id integer,
    allowance_id integer,
    amount double precision,
    created_at timestamp without time zone DEFAULT now(),
    created_by character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone,
    created_by_sid integer
);


ALTER TABLE shulesoft.salary_allowances OWNER TO postgres;

--
-- TOC entry 2377 (class 1259 OID 52029)
-- Name: salary_allowances_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.salary_allowances_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.salary_allowances_uid_seq OWNER TO postgres;

--
-- TOC entry 14218 (class 0 OID 0)
-- Dependencies: 2377
-- Name: salary_allowances_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.salary_allowances_uid_seq OWNED BY shulesoft.salary_allowances.uid;


--
-- TOC entry 2378 (class 1259 OID 52030)
-- Name: salary_deductions_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.salary_deductions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.salary_deductions_id_seq OWNER TO postgres;

--
-- TOC entry 2379 (class 1259 OID 52031)
-- Name: salary_deductions; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.salary_deductions (
    id integer DEFAULT nextval('shulesoft.salary_deductions_id_seq'::regclass) NOT NULL,
    salary_id integer,
    deduction_id integer,
    amount double precision,
    created_at timestamp without time zone DEFAULT now(),
    created_by character varying,
    employer_amount double precision,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone,
    created_by_sid integer
);


ALTER TABLE shulesoft.salary_deductions OWNER TO postgres;

--
-- TOC entry 2380 (class 1259 OID 52039)
-- Name: salary_deductions_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.salary_deductions_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.salary_deductions_uid_seq OWNER TO postgres;

--
-- TOC entry 14219 (class 0 OID 0)
-- Dependencies: 2380
-- Name: salary_deductions_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.salary_deductions_uid_seq OWNED BY shulesoft.salary_deductions.uid;


--
-- TOC entry 2381 (class 1259 OID 52040)
-- Name: salary_pensions_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.salary_pensions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.salary_pensions_id_seq OWNER TO postgres;

--
-- TOC entry 2382 (class 1259 OID 52041)
-- Name: salary_pensions; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.salary_pensions (
    id integer DEFAULT nextval('shulesoft.salary_pensions_id_seq'::regclass) NOT NULL,
    salary_id integer,
    pension_id integer,
    amount double precision,
    created_at timestamp without time zone DEFAULT now(),
    created_by character varying,
    employer_amount double precision,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.salary_pensions OWNER TO postgres;

--
-- TOC entry 2383 (class 1259 OID 52049)
-- Name: salary_pensions_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.salary_pensions_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.salary_pensions_uid_seq OWNER TO postgres;

--
-- TOC entry 14220 (class 0 OID 0)
-- Dependencies: 2383
-- Name: salary_pensions_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.salary_pensions_uid_seq OWNED BY shulesoft.salary_pensions.uid;


--
-- TOC entry 1801 (class 1259 OID 49787)
-- Name: sattendances_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.sattendances_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.sattendances_id_seq OWNER TO postgres;

--
-- TOC entry 1802 (class 1259 OID 49788)
-- Name: sattendances; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.sattendances (
    id integer DEFAULT nextval('shulesoft.sattendances_id_seq'::regclass) NOT NULL,
    student_id integer,
    created_by integer,
    created_by_table character varying,
    date date,
    present smallint DEFAULT 0,
    absent_reason character varying,
    absent_reason_id integer,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    timeout timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.sattendances OWNER TO postgres;

--
-- TOC entry 2384 (class 1259 OID 52050)
-- Name: sattendances_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.sattendances_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.sattendances_uid_seq OWNER TO postgres;

--
-- TOC entry 14221 (class 0 OID 0)
-- Dependencies: 2384
-- Name: sattendances_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.sattendances_uid_seq OWNED BY shulesoft.sattendances.uid;


--
-- TOC entry 2385 (class 1259 OID 52051)
-- Name: school_basic_invoices2; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.school_basic_invoices2 AS
 SELECT COALESCE(a.amount, (0)::numeric) AS total_amount,
    COALESCE(c.total_payment_invoice_amount, (0)::numeric) AS total_payment_invoice_fee_amount,
    COALESCE(d.total_advance_invoice_fee_amount, (0)::numeric) AS total_advance_invoice_fee_amount,
    COALESCE(e.amount, (0)::numeric) AS discount_amount,
    f.student_id,
    f.date AS created_at,
    b.id AS invoices_fees_installments_id,
    r.total_amount AS advance_amount,
    b.fees_installment_id,
    g.fee_id,
    f.academic_year_id,
    g.installment_id,
    x.start_date,
    x.end_date,
    f.id AS invoice_id,
        CASE
            WHEN ((((a.amount - COALESCE(c.total_payment_invoice_amount, (0)::numeric)) - COALESCE(d.total_advance_invoice_fee_amount, (0)::numeric)) - COALESCE(e.amount, (0)::numeric)) > (0)::numeric) THEN 0
            ELSE 1
        END AS status,
    (((COALESCE(a.amount, (0)::numeric) - COALESCE(c.total_payment_invoice_amount, (0)::numeric)) - COALESCE(d.total_advance_invoice_fee_amount, (0)::numeric)) - COALESCE(e.amount, (0)::numeric)) AS balance,
    a.schema_name
   FROM ((((((((((shulesoft.fees_installments_classes a
     JOIN shulesoft.invoices_fees_installments b ON ((b.fees_installment_id = a.fees_installment_id)))
     JOIN shulesoft.invoices f ON ((f.id = b.invoice_id)))
     JOIN shulesoft.fees_installments g ON ((g.id = a.fees_installment_id)))
     JOIN shulesoft.student_archive ss ON (((ss.student_id = f.student_id) AND (ss.academic_year_id = f.academic_year_id))))
     JOIN shulesoft.section se ON (((se."classesID" = a.class_id) AND (se."sectionID" = ss.section_id))))
     JOIN shulesoft.installments x ON ((x.id = g.installment_id)))
     LEFT JOIN ( SELECT sum(b_1.amount) AS total_payment_invoice_amount,
            b_1.invoices_fees_installment_id
           FROM shulesoft.payments_invoices_fees_installments b_1
          GROUP BY b_1.invoices_fees_installment_id) c ON ((c.invoices_fees_installment_id = b.id)))
     LEFT JOIN ( SELECT sum(b_1.amount) AS total_advance_invoice_fee_amount,
            b_1.invoices_fees_installments_id
           FROM shulesoft.advance_payments_invoices_fees_installments b_1
          GROUP BY b_1.invoices_fees_installments_id) d ON ((d.invoices_fees_installments_id = b.id)))
     LEFT JOIN ( SELECT sum(p.amount) AS total_amount,
            sum((COALESCE(p.amount, (0)::numeric) - COALESCE(r_1.total_advance_invoice_fee_amount, (0)::numeric))) AS reminder,
            p.fee_id,
            p.student_id
           FROM (shulesoft.advance_payments p
             LEFT JOIN ( SELECT sum(b_1.amount) AS total_advance_invoice_fee_amount,
                    b_1.advance_payment_id
                   FROM shulesoft.advance_payments_invoices_fees_installments b_1
                  GROUP BY b_1.advance_payment_id) r_1 ON ((r_1.advance_payment_id = p.id)))
          GROUP BY p.fee_id, p.student_id) r ON (((r.student_id = f.student_id) AND (r.fee_id = g.fee_id))))
     LEFT JOIN shulesoft.discount_fees_installments e ON (((e.fees_installment_id = a.fees_installment_id) AND (f.student_id = e.student_id))));


ALTER VIEW shulesoft.school_basic_invoices2 OWNER TO postgres;

--
-- TOC entry 2386 (class 1259 OID 52056)
-- Name: school_fees; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.school_fees AS
 SELECT a.round,
    a.class_id,
    b.classes
   FROM (( SELECT round(sum(fees_installments_classes.amount), 0) AS round,
            fees_installments_classes.class_id
           FROM shulesoft.fees_installments_classes
          WHERE (fees_installments_classes.fees_installment_id IN ( SELECT fees_installments.id
                   FROM shulesoft.fees_installments
                  WHERE (fees_installments.installment_id IN ( SELECT installments.id
                           FROM shulesoft.installments
                          WHERE (installments.academic_year_id IN ( SELECT academic_year.id
                                   FROM shulesoft.academic_year
                                  WHERE ((academic_year.name)::text = '2020'::text)))))))
          GROUP BY fees_installments_classes.class_id) a
     JOIN shulesoft.classes b ON ((b."classesID" = a.class_id)));


ALTER VIEW shulesoft.school_fees OWNER TO postgres;

--
-- TOC entry 2387 (class 1259 OID 52061)
-- Name: school_hostel_invoices; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.school_hostel_invoices AS
 SELECT COALESCE(f.amount, (0)::numeric) AS total_amount,
    COALESCE(( SELECT sum(payments_invoices_fees_installments.amount) AS sum
           FROM shulesoft.payments_invoices_fees_installments
          WHERE (payments_invoices_fees_installments.invoices_fees_installment_id = b.id)), (0)::numeric) AS total_payment_invoice_fee_amount,
    COALESCE(d.total_advance_invoice_fee_amount, (0)::numeric) AS total_advance_invoice_fee_amount,
    COALESCE(e.amount, (0)::numeric) AS discount_amount,
    g.student_id,
    g.date AS created_at,
    b.id AS invoices_fees_installments_id,
    ( SELECT sum((COALESCE(p.amount, (0)::numeric) - COALESCE(( SELECT sum(advance_payments_invoices_fees_installments.amount) AS sum
                   FROM shulesoft.advance_payments_invoices_fees_installments
                  WHERE (advance_payments_invoices_fees_installments.advance_payment_id = p.id)), (0)::numeric))) AS reminder
           FROM shulesoft.advance_payments p
          WHERE (p.student_id = g.student_id)) AS advance_amount,
    b.fees_installment_id,
    h.id AS installment_id,
    h.start_date,
    h.academic_year_id,
    ( SELECT fees.id
           FROM shulesoft.fees
          WHERE (((fees.schema_name)::text = (b.schema_name)::text) AND (lower((fees.name)::text) ~~ '%hostel%'::text))
         LIMIT 1) AS fee_id,
    b.invoice_id,
        CASE
            WHEN ((((f.amount - COALESCE(( SELECT sum(payments_invoices_fees_installments.amount) AS sum
               FROM shulesoft.payments_invoices_fees_installments
              WHERE (payments_invoices_fees_installments.invoices_fees_installment_id = b.id)), (0)::numeric)) - COALESCE(d.total_advance_invoice_fee_amount, (0)::numeric)) - COALESCE(e.amount, (0)::numeric)) > (0)::numeric) THEN 0
            ELSE 1
        END AS status,
    (((COALESCE(f.amount, (0)::numeric) - COALESCE(( SELECT sum(payments_invoices_fees_installments.amount) AS sum
           FROM shulesoft.payments_invoices_fees_installments
          WHERE (payments_invoices_fees_installments.invoices_fees_installment_id = b.id)), (0)::numeric)) - COALESCE(d.total_advance_invoice_fee_amount, (0)::numeric)) - COALESCE(e.amount, (0)::numeric)) AS balance,
    h.end_date,
    b.schema_name
   FROM ((((((((shulesoft.invoices g
     JOIN shulesoft.invoices_fees_installments b ON ((g.id = b.invoice_id)))
     JOIN shulesoft.hmembers a ON ((a.student_id = g.student_id)))
     JOIN shulesoft.hostels d_1 ON ((d_1.id = a.hostel_id)))
     JOIN shulesoft.hostel_fees_installments f ON ((a.hostel_id = f.hostel_id)))
     JOIN shulesoft.fees_installments k ON (((k.id = b.fees_installment_id) AND ((k.schema_name)::text = (b.schema_name)::text))))
     JOIN shulesoft.installments h ON (((h.id = k.installment_id) AND ((h.schema_name)::text = (k.schema_name)::text) AND (a.installment_id = h.id))))
     LEFT JOIN shulesoft.total_advance_invoice_fee_amount d ON (((d.invoices_fees_installments_id = b.id) AND ((d.schema_name)::text = (b.schema_name)::text))))
     LEFT JOIN shulesoft.discount_fees_installments e ON (((e.fees_installment_id = b.fees_installment_id) AND (g.student_id = e.student_id) AND ((e.schema_name)::text = (b.schema_name)::text))));


ALTER VIEW shulesoft.school_hostel_invoices OWNER TO postgres;

--
-- TOC entry 2388 (class 1259 OID 52066)
-- Name: school_price; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.school_price AS
 SELECT ((count(a.*))::numeric * (b.price_per_student)::numeric) AS "?column?"
   FROM shulesoft.student a,
    shulesoft.setting b
  WHERE (a.status = 1)
  GROUP BY b.price_per_student;


ALTER VIEW shulesoft.school_price OWNER TO postgres;

--
-- TOC entry 2389 (class 1259 OID 52071)
-- Name: school_transport_invoices2; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.school_transport_invoices2 AS
 SELECT DISTINCT COALESCE(a.amount, (0)::numeric) AS total_amount,
    COALESCE(( SELECT sum(payments_invoices_fees_installments.amount) AS sum
           FROM shulesoft.payments_invoices_fees_installments
          WHERE (payments_invoices_fees_installments.invoices_fees_installment_id = g.id)), (0)::numeric) AS total_payment_invoice_fee_amount,
    COALESCE(( SELECT sum(advance_payments_invoices_fees_installments.amount) AS sum
           FROM shulesoft.advance_payments_invoices_fees_installments
          WHERE (advance_payments_invoices_fees_installments.invoices_fees_installments_id = g.id)), (0)::numeric) AS total_advance_invoice_fee_amount,
    COALESCE(k.amount, (0)::numeric) AS discount_amount,
    f.student_id,
    f.date AS created_at,
    g.id AS invoices_fees_installments_id,
    COALESCE(( SELECT sum(advance_payments_invoices_fees_installments.amount) AS sum
           FROM shulesoft.advance_payments_invoices_fees_installments
          WHERE (advance_payments_invoices_fees_installments.invoices_fees_installments_id = g.id)), (0)::numeric) AS advance_amount,
    a.fees_installment_id,
    c.installment_id,
    e.start_date,
    f.academic_year_id,
    z.id AS fee_id,
    f.id AS invoice_id,
        CASE
            WHEN ((((a.amount - COALESCE(( SELECT sum(payments_invoices_fees_installments.amount) AS sum
               FROM shulesoft.payments_invoices_fees_installments
              WHERE (payments_invoices_fees_installments.invoices_fees_installment_id = g.id)), (0)::numeric)) - COALESCE(i.total_advance_invoice_fee_amount, (0)::numeric)) - COALESCE(a.amount, (0)::numeric)) > (0)::numeric) THEN 0
            ELSE 1
        END AS status,
    (((COALESCE(a.amount, (0)::numeric) - COALESCE(( SELECT sum(payments_invoices_fees_installments.amount) AS sum
           FROM shulesoft.payments_invoices_fees_installments
          WHERE (payments_invoices_fees_installments.invoices_fees_installment_id = g.id)), (0)::numeric)) - COALESCE(i.total_advance_invoice_fee_amount, (0)::numeric)) - COALESCE(k.amount, (0)::numeric)) AS balance,
    e.end_date,
    b.schema_name
   FROM (((((((((shulesoft.invoices f
     JOIN shulesoft.invoices_fees_installments g ON ((g.invoice_id = f.id)))
     JOIN shulesoft.transport_routes_fees_installments a ON ((a.fees_installment_id = g.fees_installment_id)))
     JOIN shulesoft.transport_routes b ON ((b.id = a.transport_route_id)))
     JOIN shulesoft.fees_installments c ON ((c.id = a.fees_installment_id)))
     JOIN shulesoft.fees z ON (((z.id = c.fee_id) AND (lower((z.name)::text) ~~ '%transport%'::text) AND ((z.schema_name)::text = (c.schema_name)::text))))
     JOIN shulesoft.tmembers d ON (((d.transport_route_id = a.transport_route_id) AND (d.student_id = f.student_id))))
     LEFT JOIN shulesoft.total_advance_invoice_fee_amount i ON ((i.invoices_fees_installments_id = g.id)))
     LEFT JOIN shulesoft.discount_fees_installments k ON (((k.fees_installment_id = c.id) AND (k.student_id = f.student_id))))
     JOIN shulesoft.installments e ON ((e.id = c.installment_id)))
  WHERE (a.amount > (0)::numeric);


ALTER VIEW shulesoft.school_transport_invoices2 OWNER TO postgres;

--
-- TOC entry 2390 (class 1259 OID 52076)
-- Name: school_transport_invoices3; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.school_transport_invoices3 AS
 SELECT DISTINCT COALESCE(
        CASE
            WHEN (d.is_oneway = 0) THEN a.amount
            ELSE (a.amount * 0.5::numeric(10,2))
        END, (0)::numeric) AS total_amount,
    COALESCE(( SELECT sum(payments_invoices_fees_installments.amount) AS sum
           FROM shulesoft.payments_invoices_fees_installments
          WHERE (payments_invoices_fees_installments.invoices_fees_installment_id = g.id)), (0)::numeric) AS total_payment_invoice_fee_amount,
    ( SELECT sum(advance_payments_invoices_fees_installments.amount) AS sum
           FROM shulesoft.advance_payments_invoices_fees_installments
          WHERE (advance_payments_invoices_fees_installments.invoices_fees_installments_id = g.id)) AS total_advance_invoice_fee_amount,
    COALESCE(k.amount, (0)::numeric) AS discount_amount,
    d.student_id,
    f.date AS created_at,
    g.id AS invoices_fees_installments_id,
    ( SELECT sum(advance_payments_invoices_fees_installments.amount) AS sum
           FROM shulesoft.advance_payments_invoices_fees_installments
          WHERE (advance_payments_invoices_fees_installments.invoices_fees_installments_id = g.id)) AS advance_amount,
    a.fees_installment_id,
    c.installment_id,
    e.start_date,
    f.academic_year_id,
    ( SELECT fees.id
           FROM shulesoft.fees
          WHERE (((fees.schema_name)::text = (b.schema_name)::text) AND (lower((fees.name)::text) ~~ '%transport%'::text))
         LIMIT 1) AS fee_id,
    f.id AS invoice_id,
        CASE
            WHEN ((((
            CASE
                WHEN (d.is_oneway = 0) THEN a.amount
                ELSE (a.amount * 0.5::numeric(10,2))
            END - COALESCE(( SELECT sum(payments_invoices_fees_installments.amount) AS sum
               FROM shulesoft.payments_invoices_fees_installments
              WHERE (payments_invoices_fees_installments.invoices_fees_installment_id = g.id)), (0)::numeric)) - COALESCE(i.total_advance_invoice_fee_amount, (0)::numeric)) - COALESCE(a.amount, (0)::numeric)) > (0)::numeric) THEN 0
            ELSE 1
        END AS status,
    (((COALESCE(
        CASE
            WHEN (d.is_oneway = 0) THEN a.amount
            ELSE (a.amount * 0.5::numeric(10,2))
        END, (0)::numeric) - COALESCE(( SELECT sum(payments_invoices_fees_installments.amount) AS sum
           FROM shulesoft.payments_invoices_fees_installments
          WHERE (payments_invoices_fees_installments.invoices_fees_installment_id = g.id)), (0)::numeric)) - COALESCE(i.total_advance_invoice_fee_amount, (0)::numeric)) - COALESCE(k.amount, (0)::numeric)) AS balance,
    e.end_date,
    b.schema_name
   FROM ((((((((shulesoft.invoices f
     JOIN shulesoft.invoices_fees_installments g ON ((g.invoice_id = f.id)))
     JOIN shulesoft.transport_routes_fees_installments a ON ((a.fees_installment_id = g.fees_installment_id)))
     JOIN shulesoft.transport_routes b ON ((b.id = a.transport_route_id)))
     JOIN shulesoft.fees_installments c ON ((c.id = a.fees_installment_id)))
     JOIN shulesoft.tmembers d ON (((d.transport_route_id = a.transport_route_id) AND (d.student_id = f.student_id))))
     LEFT JOIN shulesoft.total_advance_invoice_fee_amount i ON ((i.invoices_fees_installments_id = g.id)))
     LEFT JOIN shulesoft.discount_fees_installments k ON (((k.fees_installment_id = c.id) AND (k.student_id = f.student_id))))
     JOIN shulesoft.installments e ON ((e.id = c.installment_id)))
  WHERE (a.amount > (0)::numeric);


ALTER VIEW shulesoft.school_transport_invoices3 OWNER TO postgres;

--
-- TOC entry 2391 (class 1259 OID 52081)
-- Name: section_subject_teacher_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.section_subject_teacher_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.section_subject_teacher_id_seq OWNER TO postgres;

--
-- TOC entry 2392 (class 1259 OID 52082)
-- Name: section_subject_teacher; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.section_subject_teacher (
    id integer DEFAULT nextval('shulesoft.section_subject_teacher_id_seq'::regclass) NOT NULL,
    "sectionID" integer,
    academic_year_id integer,
    "teacherID" integer,
    created_at timestamp without time zone DEFAULT now(),
    refer_subject_id integer,
    subject_id integer,
    "classesID" integer,
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.section_subject_teacher OWNER TO postgres;

--
-- TOC entry 2393 (class 1259 OID 52090)
-- Name: section_subject_teacher_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.section_subject_teacher_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.section_subject_teacher_uid_seq OWNER TO postgres;

--
-- TOC entry 14222 (class 0 OID 0)
-- Dependencies: 2393
-- Name: section_subject_teacher_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.section_subject_teacher_uid_seq OWNED BY shulesoft.section_subject_teacher.uid;


--
-- TOC entry 2394 (class 1259 OID 52091)
-- Name: section_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.section_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.section_uid_seq OWNER TO postgres;

--
-- TOC entry 14223 (class 0 OID 0)
-- Dependencies: 2394
-- Name: section_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.section_uid_seq OWNED BY shulesoft.section.uid;


--
-- TOC entry 2395 (class 1259 OID 52092)
-- Name: semester_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.semester_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.semester_id_seq OWNER TO postgres;

--
-- TOC entry 2396 (class 1259 OID 52093)
-- Name: semester; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.semester (
    id integer DEFAULT nextval('shulesoft.semester_id_seq'::regclass) NOT NULL,
    name character varying(100) NOT NULL,
    class_level_id integer NOT NULL,
    created_at timestamp without time zone DEFAULT ('now'::text)::timestamp(0) without time zone NOT NULL,
    updated_at timestamp without time zone,
    academic_year_id integer,
    start_date date,
    end_date date,
    study_days integer,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.semester OWNER TO postgres;

--
-- TOC entry 2397 (class 1259 OID 52101)
-- Name: semester_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.semester_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.semester_uid_seq OWNER TO postgres;

--
-- TOC entry 14224 (class 0 OID 0)
-- Dependencies: 2397
-- Name: semester_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.semester_uid_seq OWNED BY shulesoft.semester.uid;


--
-- TOC entry 2398 (class 1259 OID 52102)
-- Name: setting_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.setting_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.setting_uid_seq OWNER TO postgres;

--
-- TOC entry 14225 (class 0 OID 0)
-- Dependencies: 2398
-- Name: setting_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.setting_uid_seq OWNED BY shulesoft.setting.uid;


--
-- TOC entry 2399 (class 1259 OID 52103)
-- Name: slots; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.slots (
    id integer NOT NULL,
    start_time time without time zone NOT NULL,
    end_time time without time zone NOT NULL,
    slot_type_id integer NOT NULL,
    slot_day_id integer NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying
);


ALTER TABLE shulesoft.slots OWNER TO postgres;

--
-- TOC entry 2400 (class 1259 OID 52110)
-- Name: slots_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.slots_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.slots_id_seq OWNER TO postgres;

--
-- TOC entry 14226 (class 0 OID 0)
-- Dependencies: 2400
-- Name: slots_id_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.slots_id_seq OWNED BY shulesoft.slots.id;


--
-- TOC entry 1805 (class 1259 OID 49844)
-- Name: sms_sms_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.sms_sms_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.sms_sms_id_seq OWNER TO postgres;

--
-- TOC entry 1806 (class 1259 OID 49845)
-- Name: sms; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.sms (
    sms_id integer DEFAULT nextval('shulesoft.sms_sms_id_seq'::regclass) NOT NULL,
    body text,
    user_id integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT now(),
    status integer DEFAULT 0,
    return_code character varying,
    phone_number character varying,
    type integer DEFAULT 1,
    "table" character varying,
    priority smallint DEFAULT 0,
    updated_at timestamp without time zone,
    opened smallint DEFAULT 0,
    sms_keys_id integer DEFAULT 1,
    sms_count integer,
    sms_content_id integer DEFAULT 1,
    subject character varying,
    sent_from character varying DEFAULT 'phonesms'::character varying,
    users_id integer,
    department_id integer DEFAULT 0,
    source integer DEFAULT 0,
    notify_status integer DEFAULT 0,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying DEFAULT 'shulesoft'::character varying NOT NULL,
    uid integer NOT NULL,
    user_sid integer,
    project character varying DEFAULT 'shulesoft'::character varying
);


ALTER TABLE shulesoft.sms OWNER TO postgres;

--
-- TOC entry 14227 (class 0 OID 0)
-- Dependencies: 1806
-- Name: COLUMN sms.sms_content_id; Type: COMMENT; Schema: shulesoft; Owner: postgres
--

COMMENT ON COLUMN shulesoft.sms.sms_content_id IS 'put it 1 by default then make a condition for all old messages to map into this one';


--
-- TOC entry 2401 (class 1259 OID 52111)
-- Name: sms_content_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.sms_content_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.sms_content_id_seq OWNER TO postgres;

--
-- TOC entry 2402 (class 1259 OID 52112)
-- Name: sms_content; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.sms_content (
    id integer DEFAULT nextval('shulesoft.sms_content_id_seq'::regclass) NOT NULL,
    message text,
    created_by integer,
    created_by_table character varying,
    channels character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    created_by_sid integer,
    data_object json
);


ALTER TABLE shulesoft.sms_content OWNER TO postgres;

--
-- TOC entry 2403 (class 1259 OID 52120)
-- Name: sms_content_channels_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.sms_content_channels_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.sms_content_channels_id_seq OWNER TO postgres;

--
-- TOC entry 2404 (class 1259 OID 52121)
-- Name: sms_content_channels; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.sms_content_channels (
    id integer DEFAULT nextval('shulesoft.sms_content_channels_id_seq'::regclass) NOT NULL,
    sms_content_id integer,
    sms_keys_id integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.sms_content_channels OWNER TO postgres;

--
-- TOC entry 2405 (class 1259 OID 52129)
-- Name: sms_content_channels_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.sms_content_channels_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.sms_content_channels_uid_seq OWNER TO postgres;

--
-- TOC entry 14228 (class 0 OID 0)
-- Dependencies: 2405
-- Name: sms_content_channels_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.sms_content_channels_uid_seq OWNED BY shulesoft.sms_content_channels.uid;


--
-- TOC entry 2406 (class 1259 OID 52130)
-- Name: sms_content_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.sms_content_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.sms_content_uid_seq OWNER TO postgres;

--
-- TOC entry 14229 (class 0 OID 0)
-- Dependencies: 2406
-- Name: sms_content_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.sms_content_uid_seq OWNED BY shulesoft.sms_content.uid;


--
-- TOC entry 2407 (class 1259 OID 52131)
-- Name: sms_files_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.sms_files_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.sms_files_id_seq OWNER TO postgres;

--
-- TOC entry 2408 (class 1259 OID 52132)
-- Name: sms_files; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.sms_files (
    id integer DEFAULT nextval('shulesoft.sms_files_id_seq'::regclass) NOT NULL,
    sms_content_id integer,
    url character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.sms_files OWNER TO postgres;

--
-- TOC entry 2409 (class 1259 OID 52140)
-- Name: sms_files_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.sms_files_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.sms_files_uid_seq OWNER TO postgres;

--
-- TOC entry 14230 (class 0 OID 0)
-- Dependencies: 2409
-- Name: sms_files_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.sms_files_uid_seq OWNED BY shulesoft.sms_files.uid;


--
-- TOC entry 2410 (class 1259 OID 52141)
-- Name: sms_keys_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.sms_keys_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.sms_keys_id_seq OWNER TO postgres;

--
-- TOC entry 2411 (class 1259 OID 52142)
-- Name: sms_keys; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.sms_keys (
    id integer DEFAULT nextval('shulesoft.sms_keys_id_seq'::regclass) NOT NULL,
    api_secret character varying(250),
    api_key character varying(250),
    phone_number character varying,
    name character varying,
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone,
    file_path character varying,
    is_approved smallint DEFAULT 1,
    is_default smallint DEFAULT 0,
    CONSTRAINT sms_keys_name_check CHECK (((name)::text = ANY (ARRAY[('whatsapp'::character varying)::text, ('telegram'::character varying)::text, ('quick-sms'::character varying)::text, ('phone-sms'::character varying)::text, 'email'::text])))
);


ALTER TABLE shulesoft.sms_keys OWNER TO postgres;

--
-- TOC entry 2412 (class 1259 OID 52153)
-- Name: sms_keys_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.sms_keys_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.sms_keys_uid_seq OWNER TO postgres;

--
-- TOC entry 14231 (class 0 OID 0)
-- Dependencies: 2412
-- Name: sms_keys_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.sms_keys_uid_seq OWNED BY shulesoft.sms_keys.uid;


--
-- TOC entry 2413 (class 1259 OID 52154)
-- Name: sms_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.sms_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.sms_uid_seq OWNER TO postgres;

--
-- TOC entry 14232 (class 0 OID 0)
-- Dependencies: 2413
-- Name: sms_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.sms_uid_seq OWNED BY shulesoft.sms.uid;


--
-- TOC entry 2414 (class 1259 OID 52155)
-- Name: smssettings_smssettingsID_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft."smssettings_smssettingsID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft."smssettings_smssettingsID_seq" OWNER TO postgres;

--
-- TOC entry 2415 (class 1259 OID 52156)
-- Name: smssettings; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.smssettings (
    "smssettingsID" integer DEFAULT nextval('shulesoft."smssettings_smssettingsID_seq"'::regclass) NOT NULL,
    types character varying(255),
    field_names character varying(255),
    field_values character varying(255),
    smssettings_extra character varying(255),
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.smssettings OWNER TO postgres;

--
-- TOC entry 2416 (class 1259 OID 52164)
-- Name: smssettings_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.smssettings_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.smssettings_uid_seq OWNER TO postgres;

--
-- TOC entry 14233 (class 0 OID 0)
-- Dependencies: 2416
-- Name: smssettings_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.smssettings_uid_seq OWNED BY shulesoft.smssettings.uid;


--
-- TOC entry 2417 (class 1259 OID 52165)
-- Name: special_grade_names_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.special_grade_names_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.special_grade_names_id_seq OWNER TO postgres;

--
-- TOC entry 2418 (class 1259 OID 52166)
-- Name: special_grade_names; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.special_grade_names (
    id integer DEFAULT nextval('shulesoft.special_grade_names_id_seq'::regclass) NOT NULL,
    name character varying,
    note text,
    created_at timestamp without time zone DEFAULT '2018-02-04 00:45:57.688526'::timestamp without time zone,
    updated_at timestamp without time zone,
    special_for character varying(200),
    global_grade_id integer,
    association_id integer,
    pass_mark double precision DEFAULT 50,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.special_grade_names OWNER TO postgres;

--
-- TOC entry 2419 (class 1259 OID 52175)
-- Name: special_grade_names_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.special_grade_names_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.special_grade_names_uid_seq OWNER TO postgres;

--
-- TOC entry 14234 (class 0 OID 0)
-- Dependencies: 2419
-- Name: special_grade_names_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.special_grade_names_uid_seq OWNED BY shulesoft.special_grade_names.uid;


--
-- TOC entry 2420 (class 1259 OID 52176)
-- Name: special_grades_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.special_grades_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.special_grades_id_seq OWNER TO postgres;

--
-- TOC entry 2421 (class 1259 OID 52177)
-- Name: special_grades; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.special_grades (
    id integer DEFAULT nextval('shulesoft.special_grades_id_seq'::regclass) NOT NULL,
    grade character varying(60) NOT NULL,
    point character varying(11) NOT NULL,
    gradefrom integer NOT NULL,
    gradeupto integer NOT NULL,
    note text,
    created_at timestamp without time zone DEFAULT now(),
    classlevel_id integer,
    overall_note text,
    overall_academic_note character varying,
    special_grade_name_id integer,
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    gpa_points double precision
);


ALTER TABLE shulesoft.special_grades OWNER TO postgres;

--
-- TOC entry 2422 (class 1259 OID 52185)
-- Name: special_grades_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.special_grades_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.special_grades_uid_seq OWNER TO postgres;

--
-- TOC entry 14235 (class 0 OID 0)
-- Dependencies: 2422
-- Name: special_grades_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.special_grades_uid_seq OWNED BY shulesoft.special_grades.uid;


--
-- TOC entry 2423 (class 1259 OID 52186)
-- Name: special_promotion_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.special_promotion_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.special_promotion_id_seq OWNER TO postgres;

--
-- TOC entry 2424 (class 1259 OID 52187)
-- Name: special_promotion; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.special_promotion (
    id integer DEFAULT nextval('shulesoft.special_promotion_id_seq'::regclass) NOT NULL,
    student_id integer,
    from_academic_year_id integer,
    to_academic_year_id integer,
    pass_mark real,
    remark character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    status integer,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.special_promotion OWNER TO postgres;

--
-- TOC entry 2425 (class 1259 OID 52195)
-- Name: special_promotion_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.special_promotion_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.special_promotion_uid_seq OWNER TO postgres;

--
-- TOC entry 14236 (class 0 OID 0)
-- Dependencies: 2425
-- Name: special_promotion_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.special_promotion_uid_seq OWNED BY shulesoft.special_promotion.uid;


--
-- TOC entry 2426 (class 1259 OID 52196)
-- Name: sponsors_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.sponsors_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.sponsors_id_seq OWNER TO postgres;

--
-- TOC entry 2427 (class 1259 OID 52197)
-- Name: sponsors; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.sponsors (
    id integer DEFAULT nextval('shulesoft.sponsors_id_seq'::regclass) NOT NULL,
    name character varying,
    location_address character varying,
    phone character varying,
    email character varying,
    created_at time without time zone,
    sex character varying,
    photo character varying(200) DEFAULT 'defualt.png'::character varying,
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    type character varying DEFAULT 'ngo'::character varying
);


ALTER TABLE shulesoft.sponsors OWNER TO postgres;

--
-- TOC entry 2428 (class 1259 OID 52206)
-- Name: sponsors_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.sponsors_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.sponsors_uid_seq OWNER TO postgres;

--
-- TOC entry 14237 (class 0 OID 0)
-- Dependencies: 2428
-- Name: sponsors_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.sponsors_uid_seq OWNED BY shulesoft.sponsors.uid;


--
-- TOC entry 2429 (class 1259 OID 52207)
-- Name: staff_leave_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.staff_leave_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.staff_leave_id_seq OWNER TO postgres;

--
-- TOC entry 2430 (class 1259 OID 52208)
-- Name: staff_leave; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.staff_leave (
    id integer DEFAULT nextval('shulesoft.staff_leave_id_seq'::regclass) NOT NULL,
    user_id integer NOT NULL,
    user_table character varying NOT NULL,
    start_date date,
    end_date date,
    comment text,
    attach text,
    attach_file_name text,
    status integer DEFAULT 1,
    leave_type_id integer NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.staff_leave OWNER TO postgres;

--
-- TOC entry 2431 (class 1259 OID 52217)
-- Name: staff_leave_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.staff_leave_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.staff_leave_uid_seq OWNER TO postgres;

--
-- TOC entry 14238 (class 0 OID 0)
-- Dependencies: 2431
-- Name: staff_leave_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.staff_leave_uid_seq OWNED BY shulesoft.staff_leave.uid;


--
-- TOC entry 2432 (class 1259 OID 52218)
-- Name: staff_report_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.staff_report_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.staff_report_id_seq OWNER TO postgres;

--
-- TOC entry 2433 (class 1259 OID 52219)
-- Name: staff_report; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.staff_report (
    id integer DEFAULT nextval('shulesoft.staff_report_id_seq'::regclass) NOT NULL,
    user_id integer NOT NULL,
    user_table character varying NOT NULL,
    title character varying NOT NULL,
    comment text,
    attach text,
    attach_file_name text,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    status integer DEFAULT 1,
    date date,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    user_sid integer
);


ALTER TABLE shulesoft.staff_report OWNER TO postgres;

--
-- TOC entry 2434 (class 1259 OID 52228)
-- Name: staff_report_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.staff_report_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.staff_report_uid_seq OWNER TO postgres;

--
-- TOC entry 14239 (class 0 OID 0)
-- Dependencies: 2434
-- Name: staff_report_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.staff_report_uid_seq OWNED BY shulesoft.staff_report.uid;


--
-- TOC entry 2435 (class 1259 OID 52229)
-- Name: staff_targets; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.staff_targets (
    id integer NOT NULL,
    uid integer NOT NULL,
    kpi character varying,
    value double precision,
    start_date date,
    end_date date,
    is_derived smallint DEFAULT 0,
    is_derived_sql text,
    created_by_sid integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    user_sid integer,
    connection character varying(255)
);


ALTER TABLE shulesoft.staff_targets OWNER TO postgres;

--
-- TOC entry 2436 (class 1259 OID 52237)
-- Name: staff_targets_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.staff_targets_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.staff_targets_id_seq OWNER TO postgres;

--
-- TOC entry 14240 (class 0 OID 0)
-- Dependencies: 2436
-- Name: staff_targets_id_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.staff_targets_id_seq OWNED BY shulesoft.staff_targets.id;


--
-- TOC entry 2437 (class 1259 OID 52238)
-- Name: staff_targets_reports; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.staff_targets_reports (
    id integer NOT NULL,
    uid integer NOT NULL,
    staff_report_id integer,
    staff_target_id integer,
    date date,
    current_value numeric(10,2),
    is_approved smallint DEFAULT 0,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL
);


ALTER TABLE shulesoft.staff_targets_reports OWNER TO postgres;

--
-- TOC entry 2438 (class 1259 OID 52246)
-- Name: staff_targets_reports_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.staff_targets_reports_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.staff_targets_reports_id_seq OWNER TO postgres;

--
-- TOC entry 14241 (class 0 OID 0)
-- Dependencies: 2438
-- Name: staff_targets_reports_id_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.staff_targets_reports_id_seq OWNED BY shulesoft.staff_targets_reports.id;


--
-- TOC entry 2439 (class 1259 OID 52247)
-- Name: staff_targets_reports_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.staff_targets_reports_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.staff_targets_reports_uid_seq OWNER TO postgres;

--
-- TOC entry 14242 (class 0 OID 0)
-- Dependencies: 2439
-- Name: staff_targets_reports_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.staff_targets_reports_uid_seq OWNED BY shulesoft.staff_targets_reports.uid;


--
-- TOC entry 2440 (class 1259 OID 52248)
-- Name: staff_targets_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.staff_targets_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.staff_targets_uid_seq OWNER TO postgres;

--
-- TOC entry 14243 (class 0 OID 0)
-- Dependencies: 2440
-- Name: staff_targets_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.staff_targets_uid_seq OWNED BY shulesoft.staff_targets.uid;


--
-- TOC entry 2441 (class 1259 OID 52249)
-- Name: student_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 99999999999999
    CACHE 1;


ALTER SEQUENCE shulesoft.student_id_seq OWNER TO postgres;

--
-- TOC entry 2442 (class 1259 OID 52250)
-- Name: store_students_id; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.store_students_id (
    id bigint DEFAULT nextval('shulesoft.student_id_seq'::regclass) NOT NULL,
    student_id bigint NOT NULL,
    status integer DEFAULT 0 NOT NULL,
    schema_name character varying(200) NOT NULL
);


ALTER TABLE shulesoft.store_students_id OWNER TO postgres;

--
-- TOC entry 2443 (class 1259 OID 52255)
-- Name: student_addresses_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_addresses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.student_addresses_id_seq OWNER TO postgres;

--
-- TOC entry 2444 (class 1259 OID 52256)
-- Name: student_addresses; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.student_addresses (
    id integer DEFAULT nextval('shulesoft.student_addresses_id_seq'::regclass) NOT NULL,
    district_id integer NOT NULL,
    ward character varying(50) NOT NULL,
    village character varying(50) NOT NULL,
    studentid integer NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.student_addresses OWNER TO postgres;

--
-- TOC entry 2445 (class 1259 OID 52264)
-- Name: student_addresses_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_addresses_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.student_addresses_uid_seq OWNER TO postgres;

--
-- TOC entry 14244 (class 0 OID 0)
-- Dependencies: 2445
-- Name: student_addresses_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.student_addresses_uid_seq OWNED BY shulesoft.student_addresses.uid;


--
-- TOC entry 2446 (class 1259 OID 52265)
-- Name: student_ages; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.student_ages AS
 SELECT upper("left"((b.sex)::text, 1)) AS sex,
    a."classesID",
    a.classes,
    count(date_part('year'::text, b.dob)) AS total,
    date_part('year'::text, age((b.dob)::timestamp with time zone)) AS age
   FROM (shulesoft.classes a
     JOIN shulesoft.student b ON ((a."classesID" = b."classesID")))
  WHERE (b.status = 1)
  GROUP BY (upper("left"((b.sex)::text, 1))), (date_part('year'::text, age((b.dob)::timestamp with time zone))), a."classesID", a.classes
  ORDER BY a.classes;


ALTER VIEW shulesoft.student_ages OWNER TO postgres;

--
-- TOC entry 2447 (class 1259 OID 52270)
-- Name: student_fees_installments_unsubscriptions_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_fees_installments_unsubscriptions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.student_fees_installments_unsubscriptions_id_seq OWNER TO postgres;

--
-- TOC entry 2448 (class 1259 OID 52271)
-- Name: student_fees_installments_unsubscriptions; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.student_fees_installments_unsubscriptions (
    id integer DEFAULT nextval('shulesoft.student_fees_installments_unsubscriptions_id_seq'::regclass) NOT NULL,
    fees_installment_id integer,
    student_id integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.student_fees_installments_unsubscriptions OWNER TO postgres;

--
-- TOC entry 2449 (class 1259 OID 52279)
-- Name: student_all_fees_subscribed; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.student_all_fees_subscribed AS
 SELECT a.id AS fees_installment_id,
    a.fee_id,
    a.installment_id,
    b.class_id,
    e.student_id,
    e.academic_year_id,
    e.schema_name
   FROM ((((shulesoft.fees_installments a
     JOIN shulesoft.fees_installments_classes b ON (((a.id = b.fees_installment_id) AND ((a.schema_name)::text = (b.schema_name)::text))))
     JOIN shulesoft.installments c ON (((c.id = a.installment_id) AND ((a.schema_name)::text = (c.schema_name)::text))))
     JOIN shulesoft.section d ON (((d."classesID" = b.class_id) AND ((d.schema_name)::text = (b.schema_name)::text))))
     JOIN shulesoft.student_archive e ON (((e.section_id = d."sectionID") AND ((e.schema_name)::text = (d.schema_name)::text))))
  WHERE (NOT (EXISTS ( SELECT 1
           FROM shulesoft.student_fees_installments_unsubscriptions f
          WHERE ((f.fees_installment_id = a.id) AND (f.student_id = e.student_id) AND ((f.schema_name)::text = (e.schema_name)::text)))));


ALTER VIEW shulesoft.student_all_fees_subscribed OWNER TO postgres;

--
-- TOC entry 2450 (class 1259 OID 52284)
-- Name: student_archive_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_archive_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.student_archive_uid_seq OWNER TO postgres;

--
-- TOC entry 14245 (class 0 OID 0)
-- Dependencies: 2450
-- Name: student_archive_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.student_archive_uid_seq OWNED BY shulesoft.student_archive.uid;


--
-- TOC entry 2451 (class 1259 OID 52285)
-- Name: student_assessment_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_assessment_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.student_assessment_id_seq OWNER TO postgres;

--
-- TOC entry 2452 (class 1259 OID 52286)
-- Name: student_assessment; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.student_assessment (
    id integer DEFAULT nextval('shulesoft.student_assessment_id_seq'::regclass) NOT NULL,
    valid_answer_id integer,
    student_id integer,
    question_id integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    student_answer text,
    score integer,
    attach_file_name text,
    attach text,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.student_assessment OWNER TO postgres;

--
-- TOC entry 2453 (class 1259 OID 52294)
-- Name: student_assessment_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_assessment_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.student_assessment_uid_seq OWNER TO postgres;

--
-- TOC entry 14246 (class 0 OID 0)
-- Dependencies: 2453
-- Name: student_assessment_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.student_assessment_uid_seq OWNED BY shulesoft.student_assessment.uid;


--
-- TOC entry 2454 (class 1259 OID 52295)
-- Name: student_characters_student_character_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_characters_student_character_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.student_characters_student_character_id_seq OWNER TO postgres;

--
-- TOC entry 2455 (class 1259 OID 52296)
-- Name: student_characters; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.student_characters (
    student_character_id integer DEFAULT nextval('shulesoft.student_characters_student_character_id_seq'::regclass) NOT NULL,
    student_id integer NOT NULL,
    semester_id integer,
    character_id integer,
    teacher_id integer NOT NULL,
    grade1 smallint,
    grade2 smallint,
    remark text,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    classes_id integer NOT NULL,
    status "char" DEFAULT '0'::"char",
    grade3 integer,
    grade4 integer,
    grade5 integer,
    grade integer,
    exam_id integer,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.student_characters OWNER TO postgres;

--
-- TOC entry 2456 (class 1259 OID 52304)
-- Name: student_characters_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_characters_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.student_characters_uid_seq OWNER TO postgres;

--
-- TOC entry 14247 (class 0 OID 0)
-- Dependencies: 2456
-- Name: student_characters_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.student_characters_uid_seq OWNED BY shulesoft.student_characters.uid;


--
-- TOC entry 2457 (class 1259 OID 52305)
-- Name: student_classes; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.student_classes AS
 SELECT count(a.*) AS count,
    b.classes
   FROM (shulesoft.student a
     JOIN shulesoft.classes b ON ((b."classesID" = a."classesID")))
  WHERE (a.status = 1)
  GROUP BY b.classes;


ALTER VIEW shulesoft.student_classes OWNER TO postgres;

--
-- TOC entry 2458 (class 1259 OID 52310)
-- Name: student_due_date_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_due_date_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.student_due_date_id_seq OWNER TO postgres;

--
-- TOC entry 2459 (class 1259 OID 52311)
-- Name: student_due_date; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.student_due_date (
    id integer DEFAULT nextval('shulesoft.student_due_date_id_seq'::regclass) NOT NULL,
    student_id integer,
    due_date date,
    installment_id integer,
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.student_due_date OWNER TO postgres;

--
-- TOC entry 2460 (class 1259 OID 52319)
-- Name: student_due_date_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_due_date_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.student_due_date_uid_seq OWNER TO postgres;

--
-- TOC entry 14248 (class 0 OID 0)
-- Dependencies: 2460
-- Name: student_due_date_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.student_due_date_uid_seq OWNED BY shulesoft.student_due_date.uid;


--
-- TOC entry 2461 (class 1259 OID 52320)
-- Name: student_duties_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_duties_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.student_duties_id_seq OWNER TO postgres;

--
-- TOC entry 2462 (class 1259 OID 52321)
-- Name: student_duties; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.student_duties (
    id integer DEFAULT nextval('shulesoft.student_duties_id_seq'::regclass) NOT NULL,
    duty_id integer,
    student_id integer,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.student_duties OWNER TO postgres;

--
-- TOC entry 2463 (class 1259 OID 52328)
-- Name: student_duties_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_duties_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.student_duties_uid_seq OWNER TO postgres;

--
-- TOC entry 14249 (class 0 OID 0)
-- Dependencies: 2463
-- Name: student_duties_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.student_duties_uid_seq OWNED BY shulesoft.student_duties.uid;


--
-- TOC entry 2464 (class 1259 OID 52329)
-- Name: student_exams; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.student_exams AS
 SELECT DISTINCT "examID",
    academic_year_id,
    "classesID",
    student_id,
    schema_name
   FROM shulesoft.mark
  WHERE (mark IS NOT NULL);


ALTER VIEW shulesoft.student_exams OWNER TO postgres;

--
-- TOC entry 2465 (class 1259 OID 52333)
-- Name: student_fee_subscription_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_fee_subscription_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.student_fee_subscription_id_seq OWNER TO postgres;

--
-- TOC entry 2466 (class 1259 OID 52334)
-- Name: student_fee_subscription; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.student_fee_subscription (
    id integer DEFAULT nextval('shulesoft.student_fee_subscription_id_seq'::regclass) NOT NULL,
    fee_id integer,
    academic_year_id integer,
    student_id integer,
    status character(1) DEFAULT 1,
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.student_fee_subscription OWNER TO postgres;

--
-- TOC entry 2467 (class 1259 OID 52343)
-- Name: student_fee_subscription_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_fee_subscription_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.student_fee_subscription_uid_seq OWNER TO postgres;

--
-- TOC entry 14250 (class 0 OID 0)
-- Dependencies: 2467
-- Name: student_fee_subscription_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.student_fee_subscription_uid_seq OWNED BY shulesoft.student_fee_subscription.uid;


--
-- TOC entry 2468 (class 1259 OID 52344)
-- Name: student_fees_installments_unsubscriptions_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_fees_installments_unsubscriptions_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.student_fees_installments_unsubscriptions_uid_seq OWNER TO postgres;

--
-- TOC entry 14251 (class 0 OID 0)
-- Dependencies: 2468
-- Name: student_fees_installments_unsubscriptions_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.student_fees_installments_unsubscriptions_uid_seq OWNED BY shulesoft.student_fees_installments_unsubscriptions.uid;


--
-- TOC entry 2469 (class 1259 OID 52345)
-- Name: vehicles_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.vehicles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.vehicles_id_seq OWNER TO postgres;

--
-- TOC entry 2470 (class 1259 OID 52346)
-- Name: vehicles; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.vehicles (
    id integer DEFAULT nextval('shulesoft.vehicles_id_seq'::regclass) NOT NULL,
    plate_number character varying,
    seats integer,
    description character varying,
    created_at timestamp without time zone DEFAULT now(),
    created_by character varying,
    updated_at timestamp without time zone,
    name character varying,
    driver_id character varying,
    conductor_id character varying,
    imeis character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.vehicles OWNER TO postgres;

--
-- TOC entry 2471 (class 1259 OID 52354)
-- Name: student_gps; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.student_gps AS
 SELECT c.parent_id,
    g.phone,
    b.name,
    b.lat,
    b.lng,
    a.transport_route_id,
    d.imeis
   FROM ((((((shulesoft.tmembers a
     JOIN shulesoft.student b ON ((b.student_id = a.student_id)))
     JOIN shulesoft.student_parents c ON ((c.student_id = b.student_id)))
     JOIN shulesoft.vehicles d ON ((d.id = a.vehicle_id)))
     JOIN shulesoft.installments e ON ((e.id = a.installment_id)))
     JOIN shulesoft.academic_year f ON ((f.id = e.academic_year_id)))
     JOIN shulesoft.parent g ON ((g."parentID" = c.parent_id)))
  WHERE (date_part('year'::text, f.start_date) = date_part('year'::text, CURRENT_DATE));


ALTER VIEW shulesoft.student_gps OWNER TO postgres;

--
-- TOC entry 2472 (class 1259 OID 52359)
-- Name: student_installment_packages; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.student_installment_packages (
    id integer NOT NULL,
    student_id integer,
    installment_package_id integer,
    created_by_sid integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    schema_name character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL
);


ALTER TABLE shulesoft.student_installment_packages OWNER TO postgres;

--
-- TOC entry 2473 (class 1259 OID 52366)
-- Name: student_installment_packages_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_installment_packages_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.student_installment_packages_id_seq OWNER TO postgres;

--
-- TOC entry 14252 (class 0 OID 0)
-- Dependencies: 2473
-- Name: student_installment_packages_id_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.student_installment_packages_id_seq OWNED BY shulesoft.student_installment_packages.id;


--
-- TOC entry 2474 (class 1259 OID 52372)
-- Name: student_next_year_all_fees_subscribed; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.student_next_year_all_fees_subscribed AS
 SELECT a.id AS fees_installment_id,
    a.fee_id,
    a.installment_id,
    (e.classes_numeric + 1) AS classes_numeric,
    b.class_id,
    c.academic_year_id,
    d.student_id,
    d.schema_name
   FROM ((((shulesoft.fees_installments a
     JOIN shulesoft.fees_installments_classes b ON (((a.id = b.fees_installment_id) AND ((a.schema_name)::text = (b.schema_name)::text))))
     JOIN shulesoft.installments c ON (((c.id = a.installment_id) AND ((a.schema_name)::text = (c.schema_name)::text))))
     JOIN shulesoft.student d ON (((d."classesID" = b.class_id) AND ((d.schema_name)::text = (b.schema_name)::text) AND (d.status = 1))))
     JOIN shulesoft.classes e ON (((e."classesID" = d."classesID") AND ((e.schema_name)::text = (d.schema_name)::text))))
  WHERE (NOT (EXISTS ( SELECT 1
           FROM shulesoft.student_fees_installments_unsubscriptions f
          WHERE ((f.fees_installment_id = a.id) AND (f.student_id = d.student_id) AND ((f.schema_name)::text = (a.schema_name)::text)))));


ALTER VIEW shulesoft.student_next_year_all_fees_subscribed OWNER TO postgres;

--
-- TOC entry 2475 (class 1259 OID 52377)
-- Name: student_other_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_other_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.student_other_id_seq OWNER TO postgres;

--
-- TOC entry 2476 (class 1259 OID 52378)
-- Name: student_other; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.student_other (
    id integer DEFAULT nextval('shulesoft.student_other_id_seq'::regclass) NOT NULL,
    admitted_from character varying(500),
    reg_number character varying(40),
    student_id integer,
    created_at timestamp without time zone,
    year_finished character varying(40),
    other_subject character varying(200),
    school_id integer,
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.student_other OWNER TO postgres;

--
-- TOC entry 2477 (class 1259 OID 52385)
-- Name: student_other_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_other_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.student_other_uid_seq OWNER TO postgres;

--
-- TOC entry 14253 (class 0 OID 0)
-- Dependencies: 2477
-- Name: student_other_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.student_other_uid_seq OWNED BY shulesoft.student_other.uid;


--
-- TOC entry 2478 (class 1259 OID 52386)
-- Name: student_parents_info; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.student_parents_info AS
 SELECT a.name AS student_name,
    b.classes,
    c.name AS parent_name,
    c.phone,
    c.email,
    a.status,
    a.username AS student_username,
    c.username AS parent_username,
    c.default_password
   FROM (((shulesoft.student a
     JOIN shulesoft.classes b ON ((b."classesID" = a."classesID")))
     JOIN shulesoft.student_parents d ON ((d.student_id = a.student_id)))
     JOIN shulesoft.parent c ON ((c."parentID" = d.parent_id)));


ALTER VIEW shulesoft.student_parents_info OWNER TO postgres;

--
-- TOC entry 2479 (class 1259 OID 52391)
-- Name: student_parents_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_parents_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.student_parents_uid_seq OWNER TO postgres;

--
-- TOC entry 14254 (class 0 OID 0)
-- Dependencies: 2479
-- Name: student_parents_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.student_parents_uid_seq OWNED BY shulesoft.student_parents.uid;


--
-- TOC entry 2480 (class 1259 OID 52392)
-- Name: student_reams_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_reams_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.student_reams_id_seq OWNER TO postgres;

--
-- TOC entry 2481 (class 1259 OID 52393)
-- Name: student_reams; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.student_reams (
    id integer DEFAULT nextval('shulesoft.student_reams_id_seq'::regclass) NOT NULL,
    student_id integer NOT NULL,
    academic_year_id integer NOT NULL,
    amount numeric,
    status smallint DEFAULT 0,
    date date DEFAULT now(),
    created_at timestamp without time zone DEFAULT ('now'::text)::timestamp(0) without time zone NOT NULL,
    updated_at timestamp without time zone,
    user_id integer,
    user_table character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.student_reams OWNER TO postgres;

--
-- TOC entry 2482 (class 1259 OID 52403)
-- Name: student_reams_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_reams_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.student_reams_uid_seq OWNER TO postgres;

--
-- TOC entry 14255 (class 0 OID 0)
-- Dependencies: 2482
-- Name: student_reams_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.student_reams_uid_seq OWNED BY shulesoft.student_reams.uid;


--
-- TOC entry 2483 (class 1259 OID 52404)
-- Name: student_remain_balances; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.student_remain_balances AS
 SELECT (((COALESCE(a.amount, (0)::numeric) - COALESCE(c.total_payment_invoice_amount, (0)::numeric)) - COALESCE(d.total_advance_invoice_fee_amount, (0)::numeric)) - COALESCE(e.amount, (0)::numeric)) AS amount_remain,
    f.student_id,
    i.id AS fee_id,
    f.id AS invoice_id,
    h.academic_year_id
   FROM ((((((((shulesoft.fees_installments_classes a
     JOIN shulesoft.invoices_fees_installments b ON ((b.fees_installment_id = a.fees_installment_id)))
     JOIN shulesoft.invoices f ON ((f.id = b.invoice_id)))
     JOIN shulesoft.fees_installments g ON ((g.id = a.fees_installment_id)))
     JOIN shulesoft.installments h ON ((h.id = g.installment_id)))
     JOIN shulesoft.fees i ON ((i.id = g.fee_id)))
     LEFT JOIN ( SELECT sum(x.amount) AS total_payment_invoice_amount,
            x.invoices_fees_installment_id
           FROM shulesoft.payments_invoices_fees_installments x
          WHERE (x.payment_id IN ( SELECT payments.id
                   FROM shulesoft.payments))
          GROUP BY x.invoices_fees_installment_id) c ON ((c.invoices_fees_installment_id = b.id)))
     LEFT JOIN ( SELECT sum(y.amount) AS total_advance_invoice_fee_amount,
            y.invoices_fees_installments_id
           FROM shulesoft.advance_payments_invoices_fees_installments y
          GROUP BY y.invoices_fees_installments_id) d ON ((d.invoices_fees_installments_id = b.id)))
     LEFT JOIN shulesoft.discount_fees_installments e ON (((e.fees_installment_id = a.fees_installment_id) AND (f.student_id = e.student_id))))
  ORDER BY h.start_date, i.priority;


ALTER VIEW shulesoft.student_remain_balances OWNER TO postgres;

--
-- TOC entry 2484 (class 1259 OID 52409)
-- Name: student_report_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_report_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.student_report_id_seq OWNER TO postgres;

--
-- TOC entry 2485 (class 1259 OID 52410)
-- Name: student_report; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.student_report (
    id integer DEFAULT nextval('shulesoft.student_report_id_seq'::regclass) NOT NULL,
    student_id integer NOT NULL,
    academic_year_id integer NOT NULL,
    status smallint DEFAULT 1,
    date date DEFAULT now(),
    created_at timestamp without time zone DEFAULT ('now'::text)::timestamp(0) without time zone NOT NULL,
    updated_at timestamp without time zone,
    user_id integer,
    user_table character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.student_report OWNER TO postgres;

--
-- TOC entry 2486 (class 1259 OID 52420)
-- Name: student_report_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_report_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.student_report_uid_seq OWNER TO postgres;

--
-- TOC entry 14256 (class 0 OID 0)
-- Dependencies: 2486
-- Name: student_report_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.student_report_uid_seq OWNED BY shulesoft.student_report.uid;


--
-- TOC entry 2487 (class 1259 OID 52421)
-- Name: student_sponsors_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_sponsors_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.student_sponsors_id_seq OWNER TO postgres;

--
-- TOC entry 2488 (class 1259 OID 52422)
-- Name: student_sponsors; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.student_sponsors (
    id integer DEFAULT nextval('shulesoft.student_sponsors_id_seq'::regclass) NOT NULL,
    student_id integer,
    sponsor_id integer,
    status smallint DEFAULT 1,
    updated_at timestamp without time zone,
    created_at timestamp without time zone DEFAULT ('now'::text)::timestamp(0) without time zone NOT NULL,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.student_sponsors OWNER TO postgres;

--
-- TOC entry 2489 (class 1259 OID 52431)
-- Name: student_sponsors_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_sponsors_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.student_sponsors_uid_seq OWNER TO postgres;

--
-- TOC entry 14257 (class 0 OID 0)
-- Dependencies: 2489
-- Name: student_sponsors_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.student_sponsors_uid_seq OWNED BY shulesoft.student_sponsors.uid;


--
-- TOC entry 2490 (class 1259 OID 52432)
-- Name: student_statisctical_report; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.student_statisctical_report AS
 SELECT a.academic_year_id,
    b.name,
    c.classes,
    d.id AS status_id,
    d.reason,
    count(a.student_id) AS total_student
   FROM (((shulesoft.student a
     JOIN constant.student_status d ON ((d.id = a.status_id)))
     JOIN shulesoft.academic_year b ON ((b.id = a.academic_year_id)))
     JOIN shulesoft.classes c ON ((c."classesID" = a."classesID")))
  GROUP BY a.academic_year_id, b.name, d.reason, c.classes, d.id
  ORDER BY a.academic_year_id;


ALTER VIEW shulesoft.student_statisctical_report OWNER TO postgres;

--
-- TOC entry 2491 (class 1259 OID 52437)
-- Name: student_status_status_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_status_status_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.student_status_status_id_seq OWNER TO postgres;

--
-- TOC entry 2492 (class 1259 OID 52438)
-- Name: student_status; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.student_status (
    status_id integer DEFAULT nextval('shulesoft.student_status_status_id_seq'::regclass) NOT NULL,
    reason character varying(500),
    created_at date,
    is_report character(1) DEFAULT '0'::bpchar,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.student_status OWNER TO postgres;

--
-- TOC entry 2493 (class 1259 OID 52446)
-- Name: student_status_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_status_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.student_status_uid_seq OWNER TO postgres;

--
-- TOC entry 14258 (class 0 OID 0)
-- Dependencies: 2493
-- Name: student_status_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.student_status_uid_seq OWNED BY shulesoft.student_status.uid;


--
-- TOC entry 2494 (class 1259 OID 52447)
-- Name: subject_count; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.subject_count AS
 SELECT m.subject_id,
    m.student_id,
    s.subject,
    'Option'::character varying AS subject_type,
    s.is_counted,
    s.is_penalty,
    s.pass_mark,
    s.grade_mark,
    m.academic_year_id,
    s."classesID" AS class_id,
    s.is_counted_indivision,
    c.section_id,
    1 AS teacher_id,
    r.arrangement,
    r.code,
    s.schema_name
   FROM (((shulesoft.subject_student m
     JOIN shulesoft.subject s ON (((s."subjectID" = m.subject_id) AND ((s.schema_name)::text = (m.schema_name)::text))))
     JOIN shulesoft.refer_subject r ON (((r.subject_id = s.subject_id) AND ((s.schema_name)::text = (r.schema_name)::text))))
     JOIN shulesoft.student_archive c ON (((c.student_id = m.student_id) AND (m.academic_year_id = c.academic_year_id) AND ((c.schema_name)::text = (m.schema_name)::text))))
UNION
 SELECT a.subject_id,
    c.student_id,
    s.subject,
    'Core'::character varying AS subject_type,
    s.is_counted,
    s.is_penalty,
    s.pass_mark,
    s.grade_mark,
    c.academic_year_id,
    s."classesID" AS class_id,
    s.is_counted_indivision,
    a.section_id,
    ( SELECT d."teacherID" AS teacher_id
           FROM shulesoft.section_subject_teacher d
          WHERE ((d.subject_id = a.subject_id) AND (d.academic_year_id = c.academic_year_id) AND (d."sectionID" = a.section_id) AND ((a.schema_name)::text = (d.schema_name)::text) AND ((c.schema_name)::text = (d.schema_name)::text) AND ((c.schema_name)::text = (a.schema_name)::text))
         LIMIT 1) AS teacher_id,
    r.arrangement,
    r.code,
    a.schema_name
   FROM (((shulesoft.subject_section a
     JOIN shulesoft.student_archive c ON (((c.section_id = a.section_id) AND ((c.schema_name)::text = (a.schema_name)::text))))
     JOIN shulesoft.subject s ON (((s."subjectID" = a.subject_id) AND ((s.schema_name)::text = (a.schema_name)::text) AND (a.section_id IN ( SELECT section."sectionID"
           FROM shulesoft.section
          WHERE (section."classesID" IN ( SELECT subject."classesID"
                   FROM shulesoft.subject
                  WHERE ((subject."subjectID" = a.subject_id) AND ((subject.schema_name)::text = (a.schema_name)::text)))))))))
     JOIN shulesoft.refer_subject r ON (((r.subject_id = s.subject_id) AND ((s.schema_name)::text = (r.schema_name)::text))));


ALTER VIEW shulesoft.subject_count OWNER TO postgres;

--
-- TOC entry 2495 (class 1259 OID 52452)
-- Name: student_total_subject; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.student_total_subject AS
 SELECT count(*) AS subject_count,
    student_id,
    academic_year_id,
    class_id,
    schema_name
   FROM shulesoft.subject_count a
  GROUP BY student_id, academic_year_id, class_id, schema_name;


ALTER VIEW shulesoft.student_total_subject OWNER TO postgres;

--
-- TOC entry 2496 (class 1259 OID 52456)
-- Name: student_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.student_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.student_uid_seq OWNER TO postgres;

--
-- TOC entry 14259 (class 0 OID 0)
-- Dependencies: 2496
-- Name: student_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.student_uid_seq OWNED BY shulesoft.student.uid;


--
-- TOC entry 2497 (class 1259 OID 52457)
-- Name: subject_mark_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.subject_mark_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.subject_mark_id_seq OWNER TO postgres;

--
-- TOC entry 2498 (class 1259 OID 52458)
-- Name: subject_mark; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.subject_mark (
    id integer DEFAULT nextval('shulesoft.subject_mark_id_seq'::regclass) NOT NULL,
    grade_mark integer,
    effort_mark character varying,
    achievement_mark character varying,
    student_id integer,
    "subjectID" integer,
    "classesID" integer,
    academic_year_id integer,
    "examID" integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at date,
    "sectionID" integer,
    year integer,
    exam character varying,
    subject character varying,
    status character(1),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.subject_mark OWNER TO postgres;

--
-- TOC entry 2499 (class 1259 OID 52466)
-- Name: subject_mark_info; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.subject_mark_info AS
 SELECT a.student_id,
    d.name,
    d.roll,
    c.subject_name,
    a.grade_mark,
    a.id,
    a.effort_mark,
    a.achievement_mark,
    a."classesID",
    e.section_id AS "sectionID",
    a."examID",
    a."subjectID",
    b.is_counted,
    b.is_penalty,
    b.pass_mark,
    a.academic_year_id
   FROM ((((shulesoft.subject_mark a
     JOIN shulesoft.subject b ON ((b."subjectID" = a."subjectID")))
     JOIN shulesoft.refer_subject c ON ((c.subject_id = b.subject_id)))
     JOIN shulesoft.student d ON ((d.student_id = a.student_id)))
     JOIN shulesoft.student_archive e ON (((a.student_id = e.student_id) AND (a.academic_year_id = e.academic_year_id))))
  WHERE ((a.grade_mark IS NOT NULL) AND (a.effort_mark IS NOT NULL) AND (a.achievement_mark IS NOT NULL));


ALTER VIEW shulesoft.subject_mark_info OWNER TO postgres;

--
-- TOC entry 2500 (class 1259 OID 52471)
-- Name: subject_mark_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.subject_mark_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.subject_mark_uid_seq OWNER TO postgres;

--
-- TOC entry 14260 (class 0 OID 0)
-- Dependencies: 2500
-- Name: subject_mark_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.subject_mark_uid_seq OWNED BY shulesoft.subject_mark.uid;


--
-- TOC entry 2501 (class 1259 OID 52472)
-- Name: subject_section_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.subject_section_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.subject_section_uid_seq OWNER TO postgres;

--
-- TOC entry 14261 (class 0 OID 0)
-- Dependencies: 2501
-- Name: subject_section_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.subject_section_uid_seq OWNED BY shulesoft.subject_section.uid;


--
-- TOC entry 2502 (class 1259 OID 52473)
-- Name: subject_student_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.subject_student_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.subject_student_uid_seq OWNER TO postgres;

--
-- TOC entry 14262 (class 0 OID 0)
-- Dependencies: 2502
-- Name: subject_student_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.subject_student_uid_seq OWNED BY shulesoft.subject_student.uid;


--
-- TOC entry 2503 (class 1259 OID 52474)
-- Name: subject_topic_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.subject_topic_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.subject_topic_id_seq OWNER TO postgres;

--
-- TOC entry 2504 (class 1259 OID 52475)
-- Name: subject_topic; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.subject_topic (
    id integer DEFAULT nextval('shulesoft.subject_topic_id_seq'::regclass) NOT NULL,
    subject_id integer,
    "subjectID" integer,
    topic_name character varying,
    status character(1) DEFAULT 1 NOT NULL,
    semester_id integer,
    academic_year_id integer,
    "classesID" integer,
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.subject_topic OWNER TO postgres;

--
-- TOC entry 2505 (class 1259 OID 52484)
-- Name: subject_topic_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.subject_topic_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.subject_topic_uid_seq OWNER TO postgres;

--
-- TOC entry 14263 (class 0 OID 0)
-- Dependencies: 2505
-- Name: subject_topic_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.subject_topic_uid_seq OWNED BY shulesoft.subject_topic.uid;


--
-- TOC entry 2506 (class 1259 OID 52485)
-- Name: subject_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.subject_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.subject_uid_seq OWNER TO postgres;

--
-- TOC entry 14264 (class 0 OID 0)
-- Dependencies: 2506
-- Name: subject_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.subject_uid_seq OWNED BY shulesoft.subject.uid;


--
-- TOC entry 2507 (class 1259 OID 52486)
-- Name: submit_files_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.submit_files_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.submit_files_id_seq OWNER TO postgres;

--
-- TOC entry 2508 (class 1259 OID 52487)
-- Name: submit_files; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.submit_files (
    id integer DEFAULT nextval('shulesoft.submit_files_id_seq'::regclass) NOT NULL,
    assignment_submit_id integer,
    attach text,
    attach_file_name text,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    status integer DEFAULT 1,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.submit_files OWNER TO postgres;

--
-- TOC entry 2509 (class 1259 OID 52496)
-- Name: submit_files_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.submit_files_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.submit_files_uid_seq OWNER TO postgres;

--
-- TOC entry 14265 (class 0 OID 0)
-- Dependencies: 2509
-- Name: submit_files_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.submit_files_uid_seq OWNED BY shulesoft.submit_files.uid;


--
-- TOC entry 2510 (class 1259 OID 52497)
-- Name: sum_exam_average; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.sum_exam_average AS
 SELECT sum(a.mark) AS sum,
    a."examID",
    a.student_id,
    b.subject_count,
    round((sum(a.mark) / (b.subject_count)::numeric), 1) AS average,
    b.academic_year_id,
    a."classesID",
    a.schema_name,
    c.section_id AS "sectionID"
   FROM ((shulesoft.mark a
     LEFT JOIN shulesoft.student_total_subject b ON (((b.student_id = a.student_id) AND (a.academic_year_id = b.academic_year_id) AND ((a.schema_name)::text = (b.schema_name)::text))))
     LEFT JOIN shulesoft.subject_count c ON (((c.subject_id = a."subjectID") AND ((a.schema_name)::text = (c.schema_name)::text) AND (c.student_id = a.student_id) AND (c.academic_year_id = a.academic_year_id))))
  GROUP BY a."examID", a.student_id, b.subject_count, b.academic_year_id, a."classesID", a.schema_name, c.section_id;


ALTER VIEW shulesoft.sum_exam_average OWNER TO postgres;

--
-- TOC entry 2511 (class 1259 OID 52502)
-- Name: sum_exam_average_done; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.sum_exam_average_done AS
 SELECT count("subjectID") AS subject_count,
    student_id,
    "examID",
    sum(mark) AS sum,
    academic_year_id,
    round((sum(mark) / (count("subjectID"))::numeric), 1) AS average,
    "classesID",
    global_exam_id,
    refer_class_id,
    schema_name,
    "sectionID"
   FROM shulesoft.mark_info
  WHERE ((is_counted = 1) AND (mark IS NOT NULL))
  GROUP BY "examID", academic_year_id, student_id, "classesID", global_exam_id, refer_class_id, schema_name, "sectionID";


ALTER VIEW shulesoft.sum_exam_average_done OWNER TO postgres;

--
-- TOC entry 2512 (class 1259 OID 52507)
-- Name: sum_rank; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.sum_rank AS
 SELECT a."examID",
    a."classesID",
    a.student_id,
    a."subjectID",
    c.is_counted,
    c.is_penalty,
    sum(a.mark) AS sum,
    rank() OVER (ORDER BY (sum(a.mark)) DESC) AS rank
   FROM (shulesoft.mark a
     JOIN shulesoft.subject c ON ((c."subjectID" = a."subjectID")))
  WHERE (a.mark IS NOT NULL)
  GROUP BY a."examID", a."classesID", a.student_id, a."subjectID", c.is_counted, c.is_penalty;


ALTER VIEW shulesoft.sum_rank OWNER TO postgres;

--
-- TOC entry 2513 (class 1259 OID 52512)
-- Name: syllabus_benchmarks_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.syllabus_benchmarks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.syllabus_benchmarks_id_seq OWNER TO postgres;

--
-- TOC entry 2514 (class 1259 OID 52513)
-- Name: syllabus_benchmarks; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.syllabus_benchmarks (
    id integer DEFAULT nextval('shulesoft.syllabus_benchmarks_id_seq'::regclass) NOT NULL,
    grade_remark character varying,
    grade character varying,
    description text,
    points integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.syllabus_benchmarks OWNER TO postgres;

--
-- TOC entry 2515 (class 1259 OID 52521)
-- Name: syllabus_benchmarks_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.syllabus_benchmarks_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.syllabus_benchmarks_uid_seq OWNER TO postgres;

--
-- TOC entry 14266 (class 0 OID 0)
-- Dependencies: 2515
-- Name: syllabus_benchmarks_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.syllabus_benchmarks_uid_seq OWNED BY shulesoft.syllabus_benchmarks.uid;


--
-- TOC entry 2516 (class 1259 OID 52522)
-- Name: syllabus_objective_references_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.syllabus_objective_references_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.syllabus_objective_references_id_seq OWNER TO postgres;

--
-- TOC entry 2517 (class 1259 OID 52523)
-- Name: syllabus_objective_references; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.syllabus_objective_references (
    id integer DEFAULT nextval('shulesoft.syllabus_objective_references_id_seq'::regclass) NOT NULL,
    syllabus_objective_id integer,
    book_id integer,
    page_description character varying,
    reference_type character varying,
    created_by_id integer,
    created_by_table character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.syllabus_objective_references OWNER TO postgres;

--
-- TOC entry 2518 (class 1259 OID 52531)
-- Name: syllabus_objective_references_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.syllabus_objective_references_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.syllabus_objective_references_uid_seq OWNER TO postgres;

--
-- TOC entry 14267 (class 0 OID 0)
-- Dependencies: 2518
-- Name: syllabus_objective_references_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.syllabus_objective_references_uid_seq OWNED BY shulesoft.syllabus_objective_references.uid;


--
-- TOC entry 2519 (class 1259 OID 52532)
-- Name: syllabus_objectives_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.syllabus_objectives_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.syllabus_objectives_id_seq OWNER TO postgres;

--
-- TOC entry 2520 (class 1259 OID 52533)
-- Name: syllabus_objectives; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.syllabus_objectives (
    id integer DEFAULT nextval('shulesoft.syllabus_objectives_id_seq'::regclass) NOT NULL,
    syllabus_subtopic_id integer,
    objective text,
    activities text,
    resources text,
    assessment_criteria text,
    remarks text,
    periods character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    reference text,
    materials text,
    weeks character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.syllabus_objectives OWNER TO postgres;

--
-- TOC entry 2521 (class 1259 OID 52541)
-- Name: syllabus_objectives_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.syllabus_objectives_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.syllabus_objectives_uid_seq OWNER TO postgres;

--
-- TOC entry 14268 (class 0 OID 0)
-- Dependencies: 2521
-- Name: syllabus_objectives_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.syllabus_objectives_uid_seq OWNED BY shulesoft.syllabus_objectives.uid;


--
-- TOC entry 2522 (class 1259 OID 52542)
-- Name: syllabus_student_benchmarking_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.syllabus_student_benchmarking_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.syllabus_student_benchmarking_id_seq OWNER TO postgres;

--
-- TOC entry 2523 (class 1259 OID 52543)
-- Name: syllabus_student_benchmarking; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.syllabus_student_benchmarking (
    id integer DEFAULT nextval('shulesoft.syllabus_student_benchmarking_id_seq'::regclass) NOT NULL,
    student_id integer,
    syllabus_benchmark_id integer,
    syllabus_objective_id integer,
    created_by_id integer,
    created_by_table character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    semester_id integer,
    syllabus_topic_id integer,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.syllabus_student_benchmarking OWNER TO postgres;

--
-- TOC entry 2524 (class 1259 OID 52551)
-- Name: syllabus_student_benchmarking_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.syllabus_student_benchmarking_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.syllabus_student_benchmarking_uid_seq OWNER TO postgres;

--
-- TOC entry 14269 (class 0 OID 0)
-- Dependencies: 2524
-- Name: syllabus_student_benchmarking_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.syllabus_student_benchmarking_uid_seq OWNED BY shulesoft.syllabus_student_benchmarking.uid;


--
-- TOC entry 2525 (class 1259 OID 52552)
-- Name: syllabus_subtopics_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.syllabus_subtopics_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.syllabus_subtopics_id_seq OWNER TO postgres;

--
-- TOC entry 2526 (class 1259 OID 52553)
-- Name: syllabus_subtopics; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.syllabus_subtopics (
    id integer DEFAULT nextval('shulesoft.syllabus_subtopics_id_seq'::regclass) NOT NULL,
    syllabus_topic_id integer,
    code character varying,
    subtitle character varying,
    start_date date,
    end_date date,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.syllabus_subtopics OWNER TO postgres;

--
-- TOC entry 2527 (class 1259 OID 52561)
-- Name: syllabus_subtopics_teachers_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.syllabus_subtopics_teachers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.syllabus_subtopics_teachers_id_seq OWNER TO postgres;

--
-- TOC entry 2528 (class 1259 OID 52562)
-- Name: syllabus_subtopics_teachers; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.syllabus_subtopics_teachers (
    id integer DEFAULT nextval('shulesoft.syllabus_subtopics_teachers_id_seq'::regclass) NOT NULL,
    syllabus_subtopic_id integer,
    teacher_id integer,
    year character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    academic_year_id integer,
    is_completed smallint DEFAULT 0
);


ALTER TABLE shulesoft.syllabus_subtopics_teachers OWNER TO postgres;

--
-- TOC entry 2529 (class 1259 OID 52571)
-- Name: syllabus_subtopics_teachers_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.syllabus_subtopics_teachers_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.syllabus_subtopics_teachers_uid_seq OWNER TO postgres;

--
-- TOC entry 14270 (class 0 OID 0)
-- Dependencies: 2529
-- Name: syllabus_subtopics_teachers_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.syllabus_subtopics_teachers_uid_seq OWNED BY shulesoft.syllabus_subtopics_teachers.uid;


--
-- TOC entry 2530 (class 1259 OID 52572)
-- Name: syllabus_subtopics_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.syllabus_subtopics_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.syllabus_subtopics_uid_seq OWNER TO postgres;

--
-- TOC entry 14271 (class 0 OID 0)
-- Dependencies: 2530
-- Name: syllabus_subtopics_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.syllabus_subtopics_uid_seq OWNED BY shulesoft.syllabus_subtopics.uid;


--
-- TOC entry 2531 (class 1259 OID 52573)
-- Name: syllabus_topics_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.syllabus_topics_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.syllabus_topics_uid_seq OWNER TO postgres;

--
-- TOC entry 14272 (class 0 OID 0)
-- Dependencies: 2531
-- Name: syllabus_topics_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.syllabus_topics_uid_seq OWNED BY shulesoft.syllabus_topics.uid;


--
-- TOC entry 2532 (class 1259 OID 52574)
-- Name: tattendance_tattendanceID_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft."tattendance_tattendanceID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft."tattendance_tattendanceID_seq" OWNER TO postgres;

--
-- TOC entry 2533 (class 1259 OID 52575)
-- Name: tattendance; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.tattendance (
    "tattendanceID" integer DEFAULT nextval('shulesoft."tattendance_tattendanceID_seq"'::regclass) NOT NULL,
    "teacherID" integer NOT NULL,
    usertype character varying(20) NOT NULL,
    monthyear character varying(10) NOT NULL,
    a1 character varying(3),
    a2 character varying(3),
    a3 character varying(3),
    a4 character varying(3),
    a5 character varying(3),
    a6 character varying(3),
    a7 character varying(3),
    a8 character varying(3),
    a9 character varying(3),
    a10 character varying(3),
    a11 character varying(3),
    a12 character varying(3),
    a13 character varying(3),
    a14 character varying(3),
    a15 character varying(3),
    a16 character varying(3),
    a17 character varying(3),
    a18 character varying(3),
    a19 character varying(3),
    a20 character varying(3),
    a21 character varying(3),
    a22 character varying(3),
    a23 character varying(3),
    a24 character varying(3),
    a25 character varying(3),
    a26 character varying(3),
    a27 character varying(3),
    a28 character varying(3),
    a29 character varying(3),
    a30 character varying(3),
    a31 character varying(3),
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.tattendance OWNER TO postgres;

--
-- TOC entry 2534 (class 1259 OID 52583)
-- Name: tattendance_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.tattendance_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.tattendance_uid_seq OWNER TO postgres;

--
-- TOC entry 14273 (class 0 OID 0)
-- Dependencies: 2534
-- Name: tattendance_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.tattendance_uid_seq OWNED BY shulesoft.tattendance.uid;


--
-- TOC entry 2535 (class 1259 OID 52584)
-- Name: tattendances; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.tattendances (
    id integer NOT NULL,
    uid integer NOT NULL,
    user_id integer,
    created_by integer,
    created_by_table character varying,
    user_table character varying,
    date date,
    timein timestamp without time zone,
    timeout timestamp without time zone,
    present smallint DEFAULT 0,
    absent_reason character varying,
    absent_reason_id integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying
);


ALTER TABLE shulesoft.tattendances OWNER TO postgres;

--
-- TOC entry 2536 (class 1259 OID 52592)
-- Name: tattendances_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.tattendances_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.tattendances_id_seq OWNER TO postgres;

--
-- TOC entry 14274 (class 0 OID 0)
-- Dependencies: 2536
-- Name: tattendances_id_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.tattendances_id_seq OWNED BY shulesoft.tattendances.id;


--
-- TOC entry 2537 (class 1259 OID 52593)
-- Name: tattendances_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.tattendances_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.tattendances_uid_seq OWNER TO postgres;

--
-- TOC entry 14275 (class 0 OID 0)
-- Dependencies: 2537
-- Name: tattendances_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.tattendances_uid_seq OWNED BY shulesoft.tattendances.uid;


--
-- TOC entry 1813 (class 1259 OID 49906)
-- Name: teacher_duties_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.teacher_duties_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.teacher_duties_id_seq OWNER TO postgres;

--
-- TOC entry 1814 (class 1259 OID 49907)
-- Name: teacher_duties; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.teacher_duties (
    id integer DEFAULT nextval('shulesoft.teacher_duties_id_seq'::regclass) NOT NULL,
    duty_id integer,
    teacher_id integer,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.teacher_duties OWNER TO postgres;

--
-- TOC entry 2538 (class 1259 OID 52594)
-- Name: teacher_duties_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.teacher_duties_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.teacher_duties_uid_seq OWNER TO postgres;

--
-- TOC entry 14276 (class 0 OID 0)
-- Dependencies: 2538
-- Name: teacher_duties_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.teacher_duties_uid_seq OWNED BY shulesoft.teacher_duties.uid;


--
-- TOC entry 1815 (class 1259 OID 49914)
-- Name: teacher_on_duty; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.teacher_on_duty AS
 SELECT a.name,
    a.phone,
    a.email,
    b.duty_id
   FROM ((shulesoft.teacher a
     JOIN shulesoft.teacher_duties b ON ((b.teacher_id = a."teacherID")))
     JOIN shulesoft.duties c ON ((c.id = b.duty_id)))
  WHERE ((c.start_date <= now()) AND (c.end_date >= now()));


ALTER VIEW shulesoft.teacher_on_duty OWNER TO postgres;

--
-- TOC entry 2539 (class 1259 OID 52595)
-- Name: teacher_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.teacher_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.teacher_uid_seq OWNER TO postgres;

--
-- TOC entry 14277 (class 0 OID 0)
-- Dependencies: 2539
-- Name: teacher_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.teacher_uid_seq OWNED BY shulesoft.teacher.uid;


--
-- TOC entry 2540 (class 1259 OID 52596)
-- Name: tempfiles_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.tempfiles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.tempfiles_id_seq OWNER TO postgres;

--
-- TOC entry 2541 (class 1259 OID 52597)
-- Name: tempfiles; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.tempfiles (
    id integer DEFAULT nextval('shulesoft.tempfiles_id_seq'::regclass) NOT NULL,
    data json NOT NULL,
    filename character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    method character varying,
    controller character varying,
    created_by integer,
    created_by_table character varying,
    processed smallint DEFAULT 0,
    status character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.tempfiles OWNER TO postgres;

--
-- TOC entry 2542 (class 1259 OID 52606)
-- Name: tempfiles_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.tempfiles_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.tempfiles_uid_seq OWNER TO postgres;

--
-- TOC entry 14278 (class 0 OID 0)
-- Dependencies: 2542
-- Name: tempfiles_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.tempfiles_uid_seq OWNED BY shulesoft.tempfiles.uid;


--
-- TOC entry 2543 (class 1259 OID 52607)
-- Name: timetables; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.timetables (
    id integer NOT NULL,
    slot_id integer NOT NULL,
    subject_id integer DEFAULT 0,
    teacher_id integer DEFAULT 0,
    section_id integer NOT NULL,
    status integer DEFAULT 1,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    slot_day_id integer NOT NULL,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying
);


ALTER TABLE shulesoft.timetables OWNER TO postgres;

--
-- TOC entry 2544 (class 1259 OID 52617)
-- Name: timetables_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.timetables_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.timetables_id_seq OWNER TO postgres;

--
-- TOC entry 14279 (class 0 OID 0)
-- Dependencies: 2544
-- Name: timetables_id_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.timetables_id_seq OWNED BY shulesoft.timetables.id;


--
-- TOC entry 2545 (class 1259 OID 52618)
-- Name: tmembers_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.tmembers_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.tmembers_uid_seq OWNER TO postgres;

--
-- TOC entry 14280 (class 0 OID 0)
-- Dependencies: 2545
-- Name: tmembers_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.tmembers_uid_seq OWNED BY shulesoft.tmembers.uid;


--
-- TOC entry 2546 (class 1259 OID 52619)
-- Name: topic_mark_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.topic_mark_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.topic_mark_id_seq OWNER TO postgres;

--
-- TOC entry 2547 (class 1259 OID 52620)
-- Name: topic_mark; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.topic_mark (
    id integer DEFAULT nextval('shulesoft.topic_mark_id_seq'::regclass) NOT NULL,
    achievement_mark character varying,
    effort_mark character varying,
    grade_mark numeric,
    created_at timestamp without time zone DEFAULT now(),
    updated_at date,
    "subjectID" integer,
    "examID" integer,
    academic_year_id integer,
    student_id integer,
    "classesID" integer,
    subject_topic_id integer,
    subject_mark_id integer,
    exam character varying,
    subject character varying,
    status character varying DEFAULT 0 NOT NULL,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.topic_mark OWNER TO postgres;

--
-- TOC entry 2548 (class 1259 OID 52629)
-- Name: topic_mark_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.topic_mark_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.topic_mark_uid_seq OWNER TO postgres;

--
-- TOC entry 14281 (class 0 OID 0)
-- Dependencies: 2548
-- Name: topic_mark_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.topic_mark_uid_seq OWNED BY shulesoft.topic_mark.uid;


--
-- TOC entry 2549 (class 1259 OID 52630)
-- Name: total_current_assets; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.total_current_assets AS
 SELECT p.id,
    p.amount,
    p.created_at,
    p.date,
    p.payment_method,
    p.transaction_id,
    5 AS is_payment,
    p.bank_account_id,
    p.user_id,
    p.user_table,
    p.payer_name,
    p.note,
    p.reconciled,
    p.bank_name,
    p.account_number,
    p.currency,
    p.account_name
   FROM ( SELECT a.id,
            a.amount,
            b.created_at,
            a.date,
            NULL::text AS payment_method,
            a.transaction_id,
            5 AS is_payment,
            b.predefined AS bank_account_id,
            a."userID" AS user_id,
            'user'::text AS user_table,
            ( SELECT "user".name
                   FROM shulesoft."user"
                  WHERE ("user"."userID" = a."userID")) AS payer_name,
            a.note,
            0 AS reconciled,
            d.name AS bank_name,
            d.number AS account_number,
            d.currency,
            d.name AS account_name
           FROM ((shulesoft.current_assets2 a
             LEFT JOIN shulesoft.refer_expense b ON ((a.to_refer_expense_id = b.id)))
             LEFT JOIN shulesoft.bank_accounts d ON ((d.id = b.predefined)))) p
UNION ALL
 SELECT e.id,
    e.amount,
    e.created_at,
    e.date,
    e.payment_method,
    e.transaction_id,
    5 AS is_payment,
    e.predefined AS bank_account_id,
    e."userID" AS user_id,
    'user'::text AS user_table,
    ( SELECT "user".name
           FROM shulesoft."user"
          WHERE ("user"."userID" = e."userID")) AS payer_name,
    e.note,
    e.reconciled,
    e.bank_name,
    e.account_number,
    e.currency,
    e.account_name
   FROM ( SELECT
                CASE
                    WHEN (a.from_refer_expense_id = b.id) THEN ((0)::numeric - a.amount)
                    ELSE a.amount
                END AS amount,
            a.id,
            b.created_at,
            a.note,
            a.date,
            NULL::text AS payment_method,
            a.transaction_id,
            b.predefined,
            a."userID",
            0 AS reconciled,
            d.name AS bank_name,
            d.number AS account_number,
            d.currency,
            d.name AS account_name
           FROM ((shulesoft.current_assets2 a
             JOIN shulesoft.refer_expense b ON ((b.id = a.from_refer_expense_id)))
             JOIN shulesoft.bank_accounts d ON ((d.id = b.predefined)))) e;


ALTER VIEW shulesoft.total_current_assets OWNER TO postgres;

--
-- TOC entry 2550 (class 1259 OID 52635)
-- Name: total_financial_categories; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.total_financial_categories AS
 SELECT d.bank_account_id,
    d.payment_type_id,
    d.amount,
    d.note,
    d.financial_category_id,
    d.date
   FROM ( SELECT a.bank_account_id,
            a.date,
            a.note,
            b.financial_category_id,
            a.payment_type_id,
            sum(
                CASE
                    WHEN (b.financial_category_id = ANY (ARRAY[4, 5, 6, 7])) THEN ((0)::numeric - COALESCE(a.amount, (0)::numeric))
                    ELSE a.amount
                END) AS amount
           FROM (shulesoft.expense a
             JOIN shulesoft.refer_expense b ON ((b.id = a.refer_expense_id)))
          GROUP BY a.bank_account_id, a.date, a.note, a.payment_type_id, b.financial_category_id) d
UNION ALL
 SELECT rev.bank_account_id,
    rev.payment_type_id,
    rev.amount,
    rev.note,
    re.financial_category_id,
    rev.date
   FROM (shulesoft.revenues rev
     JOIN shulesoft.refer_expense re ON ((rev.refer_expense_id = re.id)));


ALTER VIEW shulesoft.total_financial_categories OWNER TO postgres;

--
-- TOC entry 2551 (class 1259 OID 52640)
-- Name: total_fixed_assets; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.total_fixed_assets AS
 SELECT b."expenseID" AS id,
    b.amount,
    b.created_at,
    b.date,
    b.payment_method,
    b.transaction_id,
    6 AS is_payment,
    b.bank_account_id,
    b."userID" AS user_id,
    'user'::text AS user_table,
    ( SELECT "user".name
           FROM shulesoft."user"
          WHERE ("user"."userID" = b."userID")) AS payer_name,
    a.note,
    b.reconciled,
    b.payment_type_id,
    c.name AS bank_name,
    c.number AS account_number,
    c.currency,
    c.name AS account_name
   FROM ((shulesoft.refer_expense a
     JOIN shulesoft.expense b ON ((b.refer_expense_id = a.id)))
     LEFT JOIN shulesoft.bank_accounts c ON ((b.bank_account_id = c.id)))
  WHERE (a.financial_category_id = 4);


ALTER VIEW shulesoft.total_fixed_assets OWNER TO postgres;

--
-- TOC entry 2552 (class 1259 OID 52645)
-- Name: total_liabilities; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.total_liabilities AS
 SELECT b."expenseID" AS id,
    b.amount,
    b.created_at,
    b.date,
    b.payment_method,
    b.transaction_id,
    6 AS is_payment,
    b.bank_account_id,
    b."userID" AS user_id,
    'user'::text AS user_table,
    ( SELECT "user".name
           FROM shulesoft."user"
          WHERE ("user"."userID" = b."userID")) AS payer_name,
    a.note,
    b.reconciled,
    b.payment_type_id,
    c.name AS bank_name,
    c.number AS account_number,
    c.currency,
    c.name AS account_name
   FROM ((shulesoft.refer_expense a
     JOIN shulesoft.expense b ON ((b.refer_expense_id = a.id)))
     LEFT JOIN shulesoft.bank_accounts c ON ((b.bank_account_id = c.id)))
  WHERE (a.financial_category_id = 6);


ALTER VIEW shulesoft.total_liabilities OWNER TO postgres;

--
-- TOC entry 2553 (class 1259 OID 52650)
-- Name: total_transactions; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.total_transactions AS
 SELECT total_expenses.id,
    total_expenses.amount,
    total_expenses.created_at,
    total_expenses.date,
    total_expenses.payment_method,
    total_expenses.transaction_id,
    total_expenses.is_payment,
    total_expenses.bank_account_id,
    total_expenses.user_id,
    total_expenses.user_table,
    total_expenses.payer_name,
    total_expenses.note,
    total_expenses.reconciled,
    total_expenses.bank_name,
    total_expenses.account_number,
    total_expenses.currency,
    total_expenses.account_name,
    1 AS is_expense
   FROM shulesoft.total_expenses
UNION ALL
 SELECT total_revenues.id,
    total_revenues.amount,
    total_revenues.created_at,
    total_revenues.date,
    total_revenues.payment_method,
    total_revenues.transaction_id,
    total_revenues.is_payment,
    total_revenues.bank_account_id,
    total_revenues.user_id,
    total_revenues.user_table,
    total_revenues.payer_name,
    total_revenues.note,
    total_revenues.reconciled,
    total_revenues.bank_name,
    total_revenues.account_number,
    total_revenues.currency,
    total_revenues.account_name,
    0 AS is_expense
   FROM shulesoft.total_revenues
UNION ALL
 SELECT total_current_assets.id,
    total_current_assets.amount,
    total_current_assets.created_at,
    total_current_assets.date,
    total_current_assets.payment_method,
    total_current_assets.transaction_id,
    total_current_assets.is_payment,
    total_current_assets.bank_account_id,
    total_current_assets.user_id,
    total_current_assets.user_table,
    total_current_assets.payer_name,
    total_current_assets.note,
    total_current_assets.reconciled,
    total_current_assets.bank_name,
    total_current_assets.account_number,
    total_current_assets.currency,
    total_current_assets.account_name,
    5 AS is_expense
   FROM shulesoft.total_current_assets
UNION ALL
 SELECT total_liabilities.id,
    total_liabilities.amount,
    total_liabilities.created_at,
    total_liabilities.date,
    total_liabilities.payment_method,
    total_liabilities.transaction_id,
    total_liabilities.is_payment,
    total_liabilities.bank_account_id,
    total_liabilities.user_id,
    total_liabilities.user_table,
    total_liabilities.payer_name,
    total_liabilities.note,
    total_liabilities.reconciled,
    total_liabilities.bank_name,
    total_liabilities.account_number,
    total_liabilities.currency,
    total_liabilities.account_name,
    6 AS is_expense
   FROM shulesoft.total_liabilities
UNION ALL
 SELECT total_fixed_assets.id,
    total_fixed_assets.amount,
    total_fixed_assets.created_at,
    total_fixed_assets.date,
    total_fixed_assets.payment_method,
    total_fixed_assets.transaction_id,
    total_fixed_assets.is_payment,
    total_fixed_assets.bank_account_id,
    total_fixed_assets.user_id,
    total_fixed_assets.user_table,
    total_fixed_assets.payer_name,
    total_fixed_assets.note,
    total_fixed_assets.reconciled,
    total_fixed_assets.bank_name,
    total_fixed_assets.account_number,
    total_fixed_assets.currency,
    total_fixed_assets.account_name,
    7 AS is_expense
   FROM shulesoft.total_fixed_assets;


ALTER VIEW shulesoft.total_transactions OWNER TO postgres;

--
-- TOC entry 2554 (class 1259 OID 52655)
-- Name: tour_users_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.tour_users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.tour_users_id_seq OWNER TO postgres;

--
-- TOC entry 2555 (class 1259 OID 52656)
-- Name: tour_users; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.tour_users (
    id integer DEFAULT nextval('shulesoft.tour_users_id_seq'::regclass) NOT NULL,
    tour_id integer NOT NULL,
    user_id integer NOT NULL,
    "table" character varying(100) NOT NULL,
    tour_seen smallint DEFAULT 0,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.tour_users OWNER TO postgres;

--
-- TOC entry 2556 (class 1259 OID 52665)
-- Name: tour_users_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.tour_users_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.tour_users_uid_seq OWNER TO postgres;

--
-- TOC entry 14282 (class 0 OID 0)
-- Dependencies: 2556
-- Name: tour_users_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.tour_users_uid_seq OWNED BY shulesoft.tour_users.uid;


--
-- TOC entry 2557 (class 1259 OID 52666)
-- Name: tours_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.tours_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.tours_id_seq OWNER TO postgres;

--
-- TOC entry 2558 (class 1259 OID 52667)
-- Name: tours; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.tours (
    id integer DEFAULT nextval('shulesoft.tours_id_seq'::regclass) NOT NULL,
    location character varying(250) DEFAULT NULL::character varying,
    name character varying,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.tours OWNER TO postgres;

--
-- TOC entry 2559 (class 1259 OID 52676)
-- Name: tours_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.tours_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.tours_uid_seq OWNER TO postgres;

--
-- TOC entry 14283 (class 0 OID 0)
-- Dependencies: 2559
-- Name: tours_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.tours_uid_seq OWNED BY shulesoft.tours.uid;


--
-- TOC entry 2560 (class 1259 OID 52677)
-- Name: track_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.track_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.track_id_seq OWNER TO postgres;

--
-- TOC entry 2561 (class 1259 OID 52678)
-- Name: track; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.track (
    id integer DEFAULT nextval('shulesoft.track_id_seq'::regclass) NOT NULL,
    user_id integer,
    user_type character varying,
    table_name character varying,
    column_name character varying,
    column_value_from character varying,
    column_final_value character varying,
    created_at timestamp without time zone DEFAULT now(),
    status smallint DEFAULT 0,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.track OWNER TO postgres;

--
-- TOC entry 2562 (class 1259 OID 52687)
-- Name: track_invoices_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.track_invoices_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.track_invoices_id_seq OWNER TO postgres;

--
-- TOC entry 2563 (class 1259 OID 52688)
-- Name: track_invoices; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.track_invoices (
    id integer DEFAULT nextval('shulesoft.track_invoices_id_seq'::regclass) NOT NULL,
    reference character varying,
    student_id integer,
    invoice_id integer,
    created_at date,
    sync smallint,
    return_message text,
    push_status character varying,
    date timestamp without time zone,
    updated_at timestamp without time zone,
    academic_year_id integer,
    prefix character varying,
    due_date date,
    deleted_at timestamp without time zone DEFAULT now(),
    session_id integer,
    usertype character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.track_invoices OWNER TO postgres;

--
-- TOC entry 2564 (class 1259 OID 52696)
-- Name: track_invoices_fees_installments_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.track_invoices_fees_installments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.track_invoices_fees_installments_id_seq OWNER TO postgres;

--
-- TOC entry 2565 (class 1259 OID 52697)
-- Name: track_invoices_fees_installments; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.track_invoices_fees_installments (
    id integer DEFAULT nextval('shulesoft.track_invoices_fees_installments_id_seq'::regclass) NOT NULL,
    deleted_at timestamp without time zone DEFAULT now(),
    fees_installments_id integer,
    invoice_id integer,
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.track_invoices_fees_installments OWNER TO postgres;

--
-- TOC entry 2566 (class 1259 OID 52706)
-- Name: track_invoices_fees_installments_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.track_invoices_fees_installments_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.track_invoices_fees_installments_uid_seq OWNER TO postgres;

--
-- TOC entry 14284 (class 0 OID 0)
-- Dependencies: 2566
-- Name: track_invoices_fees_installments_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.track_invoices_fees_installments_uid_seq OWNED BY shulesoft.track_invoices_fees_installments.uid;


--
-- TOC entry 2567 (class 1259 OID 52707)
-- Name: track_invoices_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.track_invoices_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.track_invoices_uid_seq OWNER TO postgres;

--
-- TOC entry 14285 (class 0 OID 0)
-- Dependencies: 2567
-- Name: track_invoices_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.track_invoices_uid_seq OWNED BY shulesoft.track_invoices.uid;


--
-- TOC entry 2568 (class 1259 OID 52708)
-- Name: track_payments_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.track_payments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.track_payments_id_seq OWNER TO postgres;

--
-- TOC entry 2569 (class 1259 OID 52709)
-- Name: track_payments_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.track_payments_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.track_payments_uid_seq OWNER TO postgres;

--
-- TOC entry 2570 (class 1259 OID 52710)
-- Name: track_payments; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.track_payments (
    id integer DEFAULT nextval('shulesoft.track_payments_id_seq'::regclass) NOT NULL,
    payment_id integer,
    student_id integer NOT NULL,
    amount numeric NOT NULL,
    payment_type_id integer,
    date date NOT NULL,
    transaction_id character varying,
    created_at timestamp without time zone,
    cheque_number character varying,
    bank_account_id integer,
    payer_name character varying,
    mobile_transaction_id character varying,
    transaction_time character varying,
    account_number character varying,
    token character varying,
    reconciled smallint DEFAULT 0,
    receipt_code character varying,
    updated_at timestamp without time zone,
    channel character varying,
    amount_entered numeric,
    created_by integer,
    created_by_table character varying,
    session_id integer,
    deleted_at timestamp without time zone DEFAULT now(),
    usertype character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer DEFAULT nextval('shulesoft.track_payments_uid_seq'::regclass) NOT NULL
);


ALTER TABLE shulesoft.track_payments OWNER TO postgres;

--
-- TOC entry 2571 (class 1259 OID 52720)
-- Name: track_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.track_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.track_uid_seq OWNER TO postgres;

--
-- TOC entry 14286 (class 0 OID 0)
-- Dependencies: 2571
-- Name: track_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.track_uid_seq OWNED BY shulesoft.track.uid;


--
-- TOC entry 2572 (class 1259 OID 52721)
-- Name: trainings_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.trainings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.trainings_id_seq OWNER TO postgres;

--
-- TOC entry 2573 (class 1259 OID 52722)
-- Name: trainings; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.trainings (
    id integer DEFAULT nextval('shulesoft.trainings_id_seq'::regclass) NOT NULL,
    training_checklist_id integer,
    user_id integer,
    "table" character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.trainings OWNER TO postgres;

--
-- TOC entry 2574 (class 1259 OID 52730)
-- Name: trainings_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.trainings_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.trainings_uid_seq OWNER TO postgres;

--
-- TOC entry 14287 (class 0 OID 0)
-- Dependencies: 2574
-- Name: trainings_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.trainings_uid_seq OWNED BY shulesoft.trainings.uid;


--
-- TOC entry 2575 (class 1259 OID 52731)
-- Name: transport_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.transport_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.transport_id_seq OWNER TO postgres;

--
-- TOC entry 2576 (class 1259 OID 52732)
-- Name: transport; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.transport (
    id integer DEFAULT nextval('shulesoft.transport_id_seq'::regclass) NOT NULL,
    route text NOT NULL,
    vehicle integer,
    fare character varying(11),
    note text,
    created_at timestamp without time zone DEFAULT now(),
    academic_year_id integer,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.transport OWNER TO postgres;

--
-- TOC entry 2577 (class 1259 OID 52740)
-- Name: transport_info; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.transport_info AS
 SELECT b.transport_route_id,
    a.student_id,
    a.id,
    a.amount,
    b.fees_installment_id,
    COALESCE(c.amount, (0)::numeric(10,2)) AS discount,
    d.name AS route_name,
    e.name AS vehicle_name,
    e.plate_number,
    a.is_oneway,
    a.vehicle_id,
    a.installment_id,
    a.schema_name
   FROM (((((shulesoft.tmembers a
     JOIN shulesoft.fees_installments f ON (((f.installment_id = a.installment_id) AND (f.fee_id IN ( SELECT fees.id
           FROM shulesoft.fees
          WHERE (((fees.schema_name)::text = (f.schema_name)::text) AND (lower((fees.name)::text) ~~ '%transport%'::text))
         LIMIT 1)) AND ((a.schema_name)::text = (f.schema_name)::text))))
     JOIN shulesoft.transport_routes_fees_installments b ON (((b.fees_installment_id = f.id) AND (b.transport_route_id = a.transport_route_id) AND ((a.schema_name)::text = (b.schema_name)::text))))
     JOIN shulesoft.vehicles e ON (((e.id = a.vehicle_id) AND ((a.schema_name)::text = (e.schema_name)::text))))
     LEFT JOIN shulesoft.discount_fees_installments c ON (((c.fees_installment_id = b.fees_installment_id) AND (a.student_id = c.student_id) AND ((a.schema_name)::text = (c.schema_name)::text))))
     JOIN shulesoft.transport_routes d ON (((d.id = a.transport_route_id) AND ((a.schema_name)::text = (d.schema_name)::text))))
  GROUP BY b.transport_route_id, a.student_id, a.amount, b.fees_installment_id, c.amount, d.name, e.name, e.plate_number, a.is_oneway, a.id, a.vehicle_id, a.installment_id, a.schema_name
  ORDER BY a.student_id DESC;


ALTER VIEW shulesoft.transport_info OWNER TO postgres;

--
-- TOC entry 2578 (class 1259 OID 52745)
-- Name: transport_installment_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.transport_installment_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.transport_installment_id_seq OWNER TO postgres;

--
-- TOC entry 2579 (class 1259 OID 52746)
-- Name: transport_installment; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.transport_installment (
    id integer DEFAULT nextval('shulesoft.transport_installment_id_seq'::regclass) NOT NULL,
    transport_id integer,
    amount numeric,
    fee_installment_id integer,
    total_amount double precision DEFAULT 0,
    status integer DEFAULT 0 NOT NULL,
    installment_id integer,
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.transport_installment OWNER TO postgres;

--
-- TOC entry 2580 (class 1259 OID 52756)
-- Name: transport_installment_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.transport_installment_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.transport_installment_uid_seq OWNER TO postgres;

--
-- TOC entry 14288 (class 0 OID 0)
-- Dependencies: 2580
-- Name: transport_installment_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.transport_installment_uid_seq OWNED BY shulesoft.transport_installment.uid;


--
-- TOC entry 2581 (class 1259 OID 52757)
-- Name: transport_routes_fees_installments_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.transport_routes_fees_installments_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.transport_routes_fees_installments_uid_seq OWNER TO postgres;

--
-- TOC entry 14289 (class 0 OID 0)
-- Dependencies: 2581
-- Name: transport_routes_fees_installments_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.transport_routes_fees_installments_uid_seq OWNED BY shulesoft.transport_routes_fees_installments.uid;


--
-- TOC entry 2582 (class 1259 OID 52758)
-- Name: transport_routes_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.transport_routes_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.transport_routes_uid_seq OWNER TO postgres;

--
-- TOC entry 14290 (class 0 OID 0)
-- Dependencies: 2582
-- Name: transport_routes_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.transport_routes_uid_seq OWNED BY shulesoft.transport_routes.uid;


--
-- TOC entry 2583 (class 1259 OID 52759)
-- Name: transport_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.transport_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.transport_uid_seq OWNER TO postgres;

--
-- TOC entry 14291 (class 0 OID 0)
-- Dependencies: 2583
-- Name: transport_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.transport_uid_seq OWNED BY shulesoft.transport.uid;


--
-- TOC entry 2584 (class 1259 OID 52760)
-- Name: uattendances_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.uattendances_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.uattendances_id_seq OWNER TO postgres;

--
-- TOC entry 2585 (class 1259 OID 52761)
-- Name: uattendances; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.uattendances (
    id integer DEFAULT nextval('shulesoft.uattendances_id_seq'::regclass) NOT NULL,
    user_id integer,
    created_by integer,
    created_by_table character varying,
    user_table character varying,
    date date,
    timein timestamp without time zone,
    timeout timestamp without time zone,
    present smallint DEFAULT 0,
    absent_reason character varying,
    absent_reason_id integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.uattendances OWNER TO postgres;

--
-- TOC entry 2586 (class 1259 OID 52770)
-- Name: uattendances_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.uattendances_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.uattendances_uid_seq OWNER TO postgres;

--
-- TOC entry 14292 (class 0 OID 0)
-- Dependencies: 2586
-- Name: uattendances_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.uattendances_uid_seq OWNED BY shulesoft.uattendances.uid;


--
-- TOC entry 2587 (class 1259 OID 52771)
-- Name: user_allowances_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.user_allowances_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.user_allowances_id_seq OWNER TO postgres;

--
-- TOC entry 2588 (class 1259 OID 52772)
-- Name: user_allowances; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.user_allowances (
    id integer DEFAULT nextval('shulesoft.user_allowances_id_seq'::regclass) NOT NULL,
    user_id integer,
    "table" character varying,
    allowance_id integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    created_by character varying,
    deadline date,
    type smallint DEFAULT 1,
    amount numeric,
    percent smallint,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    user_sid integer,
    created_by_sid integer
);


ALTER TABLE shulesoft.user_allowances OWNER TO postgres;

--
-- TOC entry 2589 (class 1259 OID 52781)
-- Name: user_allowances_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.user_allowances_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.user_allowances_uid_seq OWNER TO postgres;

--
-- TOC entry 14293 (class 0 OID 0)
-- Dependencies: 2589
-- Name: user_allowances_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.user_allowances_uid_seq OWNED BY shulesoft.user_allowances.uid;


--
-- TOC entry 2590 (class 1259 OID 52782)
-- Name: user_contract_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.user_contract_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.user_contract_id_seq OWNER TO postgres;

--
-- TOC entry 2591 (class 1259 OID 52783)
-- Name: user_contract; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.user_contract (
    id integer DEFAULT nextval('shulesoft.user_contract_id_seq'::regclass) NOT NULL,
    user_id integer,
    "table" character varying,
    start_date date,
    end_date date,
    notify_date date,
    employment_type_id integer,
    created_at timestamp without time zone DEFAULT now(),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.user_contract OWNER TO postgres;

--
-- TOC entry 2592 (class 1259 OID 52791)
-- Name: user_contract_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.user_contract_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.user_contract_uid_seq OWNER TO postgres;

--
-- TOC entry 14294 (class 0 OID 0)
-- Dependencies: 2592
-- Name: user_contract_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.user_contract_uid_seq OWNED BY shulesoft.user_contract.uid;


--
-- TOC entry 2593 (class 1259 OID 52792)
-- Name: user_deductions_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.user_deductions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.user_deductions_id_seq OWNER TO postgres;

--
-- TOC entry 2594 (class 1259 OID 52793)
-- Name: user_deductions; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.user_deductions (
    id integer DEFAULT nextval('shulesoft.user_deductions_id_seq'::regclass) NOT NULL,
    user_id bigint,
    "table" character varying,
    deduction_id integer,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    created_by character varying,
    deadline date,
    type smallint DEFAULT 1,
    amount double precision,
    percent double precision,
    employer_amount double precision,
    employer_percent double precision,
    loan_application_id integer,
    member_id character varying(30),
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    user_sid integer,
    created_by_sid integer
);


ALTER TABLE shulesoft.user_deductions OWNER TO postgres;

--
-- TOC entry 2595 (class 1259 OID 52801)
-- Name: user_deductions_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.user_deductions_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.user_deductions_uid_seq OWNER TO postgres;

--
-- TOC entry 14295 (class 0 OID 0)
-- Dependencies: 2595
-- Name: user_deductions_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.user_deductions_uid_seq OWNED BY shulesoft.user_deductions.uid;


--
-- TOC entry 2596 (class 1259 OID 52802)
-- Name: user_devices; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.user_devices (
    id integer NOT NULL,
    uid integer NOT NULL,
    user_sid integer,
    user_agent character varying,
    platform character varying,
    device_token text NOT NULL,
    created_at timestamp(0) without time zone DEFAULT now() NOT NULL,
    updated_at timestamp(0) without time zone,
    comment text,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying
);


ALTER TABLE shulesoft.user_devices OWNER TO postgres;

--
-- TOC entry 2597 (class 1259 OID 52809)
-- Name: user_devices_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.user_devices_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.user_devices_id_seq OWNER TO postgres;

--
-- TOC entry 14296 (class 0 OID 0)
-- Dependencies: 2597
-- Name: user_devices_id_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.user_devices_id_seq OWNED BY shulesoft.user_devices.id;


--
-- TOC entry 2598 (class 1259 OID 52810)
-- Name: user_devices_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.user_devices_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.user_devices_uid_seq OWNER TO postgres;

--
-- TOC entry 14297 (class 0 OID 0)
-- Dependencies: 2598
-- Name: user_devices_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.user_devices_uid_seq OWNED BY shulesoft.user_devices.uid;


--
-- TOC entry 2599 (class 1259 OID 52811)
-- Name: user_pensions_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.user_pensions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.user_pensions_id_seq OWNER TO postgres;

--
-- TOC entry 2600 (class 1259 OID 52812)
-- Name: user_pensions; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.user_pensions (
    id integer DEFAULT nextval('shulesoft.user_pensions_id_seq'::regclass) NOT NULL,
    user_id integer,
    "table" character varying,
    pension_id integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    created_by character varying,
    checknumber character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    user_sid integer,
    created_by_sid integer
);


ALTER TABLE shulesoft.user_pensions OWNER TO postgres;

--
-- TOC entry 2601 (class 1259 OID 52820)
-- Name: user_pensions_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.user_pensions_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.user_pensions_uid_seq OWNER TO postgres;

--
-- TOC entry 14298 (class 0 OID 0)
-- Dependencies: 2601
-- Name: user_pensions_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.user_pensions_uid_seq OWNED BY shulesoft.user_pensions.uid;


--
-- TOC entry 2602 (class 1259 OID 52821)
-- Name: user_phones_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.user_phones_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.user_phones_id_seq OWNER TO postgres;

--
-- TOC entry 2603 (class 1259 OID 52822)
-- Name: user_phones; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.user_phones (
    id integer DEFAULT nextval('shulesoft.user_phones_id_seq'::regclass) NOT NULL,
    user_id integer,
    "table" character varying,
    phone_number integer,
    created_at time without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.user_phones OWNER TO postgres;

--
-- TOC entry 2604 (class 1259 OID 52830)
-- Name: user_phones_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.user_phones_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.user_phones_uid_seq OWNER TO postgres;

--
-- TOC entry 14299 (class 0 OID 0)
-- Dependencies: 2604
-- Name: user_phones_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.user_phones_uid_seq OWNED BY shulesoft.user_phones.uid;


--
-- TOC entry 2605 (class 1259 OID 52831)
-- Name: user_reminders_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.user_reminders_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.user_reminders_id_seq OWNER TO postgres;

--
-- TOC entry 2606 (class 1259 OID 52832)
-- Name: user_reminders; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.user_reminders (
    id integer DEFAULT nextval('shulesoft.user_reminders_id_seq'::regclass) NOT NULL,
    user_id integer,
    end_date timestamp without time zone,
    created_at timestamp with time zone,
    template_id integer,
    status smallint DEFAULT 0,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE shulesoft.user_reminders OWNER TO postgres;

--
-- TOC entry 2607 (class 1259 OID 52840)
-- Name: user_reminders_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.user_reminders_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.user_reminders_uid_seq OWNER TO postgres;

--
-- TOC entry 14300 (class 0 OID 0)
-- Dependencies: 2607
-- Name: user_reminders_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.user_reminders_uid_seq OWNED BY shulesoft.user_reminders.uid;


--
-- TOC entry 2608 (class 1259 OID 52841)
-- Name: user_role_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.user_role_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.user_role_id_seq OWNER TO postgres;

--
-- TOC entry 2609 (class 1259 OID 52842)
-- Name: user_role; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.user_role (
    id integer DEFAULT nextval('shulesoft.user_role_id_seq'::regclass) NOT NULL,
    user_id integer NOT NULL,
    role_id integer NOT NULL,
    created_at timestamp without time zone DEFAULT ('now'::text)::timestamp(0) without time zone NOT NULL,
    updated_at timestamp without time zone,
    "table" character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    user_sid integer
);


ALTER TABLE shulesoft.user_role OWNER TO postgres;

--
-- TOC entry 2610 (class 1259 OID 52850)
-- Name: user_role_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.user_role_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.user_role_uid_seq OWNER TO postgres;

--
-- TOC entry 14301 (class 0 OID 0)
-- Dependencies: 2610
-- Name: user_role_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.user_role_uid_seq OWNED BY shulesoft.user_role.uid;


--
-- TOC entry 2611 (class 1259 OID 52851)
-- Name: user_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.user_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.user_uid_seq OWNER TO postgres;

--
-- TOC entry 14302 (class 0 OID 0)
-- Dependencies: 2611
-- Name: user_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.user_uid_seq OWNED BY shulesoft."user".uid;


--
-- TOC entry 2612 (class 1259 OID 52852)
-- Name: user_updates_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.user_updates_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.user_updates_id_seq OWNER TO postgres;

--
-- TOC entry 2613 (class 1259 OID 52853)
-- Name: user_updates; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.user_updates (
    id integer DEFAULT nextval('shulesoft.user_updates_id_seq'::regclass) NOT NULL,
    update_id integer,
    is_opened smallint DEFAULT 0,
    "like" smallint DEFAULT 0,
    user_id integer,
    "table" character varying,
    opened_date timestamp without time zone,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.user_updates OWNER TO postgres;

--
-- TOC entry 2614 (class 1259 OID 52863)
-- Name: user_updates_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.user_updates_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.user_updates_uid_seq OWNER TO postgres;

--
-- TOC entry 14303 (class 0 OID 0)
-- Dependencies: 2614
-- Name: user_updates_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.user_updates_uid_seq OWNED BY shulesoft.user_updates.uid;


--
-- TOC entry 2615 (class 1259 OID 52864)
-- Name: valid_answers_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.valid_answers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.valid_answers_id_seq OWNER TO postgres;

--
-- TOC entry 2616 (class 1259 OID 52865)
-- Name: valid_answers; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.valid_answers (
    id integer DEFAULT nextval('shulesoft.valid_answers_id_seq'::regclass) NOT NULL,
    question_id integer,
    answer text,
    status integer DEFAULT 0 NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.valid_answers OWNER TO postgres;

--
-- TOC entry 2617 (class 1259 OID 52874)
-- Name: valid_answers_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.valid_answers_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.valid_answers_uid_seq OWNER TO postgres;

--
-- TOC entry 14304 (class 0 OID 0)
-- Dependencies: 2617
-- Name: valid_answers_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.valid_answers_uid_seq OWNED BY shulesoft.valid_answers.uid;


--
-- TOC entry 2618 (class 1259 OID 52875)
-- Name: vehicles_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.vehicles_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.vehicles_uid_seq OWNER TO postgres;

--
-- TOC entry 14305 (class 0 OID 0)
-- Dependencies: 2618
-- Name: vehicles_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.vehicles_uid_seq OWNED BY shulesoft.vehicles.uid;


--
-- TOC entry 2619 (class 1259 OID 52876)
-- Name: vendors_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.vendors_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.vendors_id_seq OWNER TO postgres;

--
-- TOC entry 2620 (class 1259 OID 52877)
-- Name: vendors; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.vendors (
    id integer DEFAULT nextval('shulesoft.vendors_id_seq'::regclass) NOT NULL,
    email character varying(150),
    name character varying(250),
    phone_number character varying(90),
    telephone_number character varying(90),
    country character varying(150),
    city character varying(150),
    location text,
    bank_name character varying(90),
    bank_branch character varying(150),
    account_number character varying(90),
    contact_person_name character varying(150),
    contact_person_phone character varying(90),
    contact_person_email character varying(90),
    contact_person_jobtitle character varying(90),
    service_product text,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.vendors OWNER TO postgres;

--
-- TOC entry 2621 (class 1259 OID 52885)
-- Name: vendors_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.vendors_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.vendors_uid_seq OWNER TO postgres;

--
-- TOC entry 14306 (class 0 OID 0)
-- Dependencies: 2621
-- Name: vendors_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.vendors_uid_seq OWNED BY shulesoft.vendors.uid;


--
-- TOC entry 2622 (class 1259 OID 52886)
-- Name: wallet_cart_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.wallet_cart_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.wallet_cart_id_seq OWNER TO postgres;

--
-- TOC entry 2623 (class 1259 OID 52887)
-- Name: wallet_cart; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.wallet_cart (
    id integer DEFAULT nextval('shulesoft.wallet_cart_id_seq'::regclass) NOT NULL,
    name character varying,
    product_alert_id integer,
    wallet_use_id integer NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    quantity integer,
    amount numeric,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.wallet_cart OWNER TO postgres;

--
-- TOC entry 2624 (class 1259 OID 52895)
-- Name: wallet_cart_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.wallet_cart_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.wallet_cart_uid_seq OWNER TO postgres;

--
-- TOC entry 14307 (class 0 OID 0)
-- Dependencies: 2624
-- Name: wallet_cart_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.wallet_cart_uid_seq OWNED BY shulesoft.wallet_cart.uid;


--
-- TOC entry 2625 (class 1259 OID 52896)
-- Name: wallet_uses_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.wallet_uses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.wallet_uses_id_seq OWNER TO postgres;

--
-- TOC entry 2626 (class 1259 OID 52897)
-- Name: wallet_uses; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.wallet_uses (
    id integer DEFAULT nextval('shulesoft.wallet_uses_id_seq'::regclass) NOT NULL,
    payer_name character varying,
    payer_phone character varying,
    payer_email character varying,
    refer_expense_id integer,
    amount numeric,
    created_by_id integer,
    created_by_table character varying,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone,
    payment_method character varying,
    transaction_id character varying,
    bank_account_id integer,
    invoice_number character varying,
    note text,
    date date,
    user_id integer,
    user_table character varying,
    reconciled smallint DEFAULT 0,
    payment_type_id integer,
    loan_application_id integer,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.wallet_uses OWNER TO postgres;

--
-- TOC entry 2627 (class 1259 OID 52906)
-- Name: wallets_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.wallets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.wallets_id_seq OWNER TO postgres;

--
-- TOC entry 2628 (class 1259 OID 52907)
-- Name: wallets; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.wallets (
    id integer DEFAULT nextval('shulesoft.wallets_id_seq'::regclass) NOT NULL,
    student_id integer,
    amount double precision,
    date timestamp without time zone DEFAULT now() NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    transaction_id character varying,
    reference character varying,
    created_by integer,
    created_by_table character varying,
    channel character varying,
    token character varying,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL
);


ALTER TABLE shulesoft.wallets OWNER TO postgres;

--
-- TOC entry 2629 (class 1259 OID 52915)
-- Name: wallet_usage; Type: VIEW; Schema: shulesoft; Owner: postgres
--

CREATE VIEW shulesoft.wallet_usage AS
 SELECT (sum(COALESCE(wallets.amount, ((0)::numeric)::double precision)))::numeric AS amount,
    wallets.student_id,
    wallets.schema_name,
    0 AS used_amount
   FROM shulesoft.wallets
  GROUP BY wallets.student_id, wallets.schema_name
UNION ALL
 SELECT 0 AS amount,
    wallet_uses.user_id AS student_id,
    wallet_uses.schema_name,
    sum(COALESCE(wallet_uses.amount, (0)::numeric)) AS used_amount
   FROM shulesoft.wallet_uses
  WHERE ((wallet_uses.user_table)::text = 'student'::text)
  GROUP BY wallet_uses.user_id, wallet_uses.schema_name;


ALTER VIEW shulesoft.wallet_usage OWNER TO postgres;

--
-- TOC entry 2630 (class 1259 OID 52920)
-- Name: wallet_uses_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.wallet_uses_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.wallet_uses_uid_seq OWNER TO postgres;

--
-- TOC entry 14308 (class 0 OID 0)
-- Dependencies: 2630
-- Name: wallet_uses_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.wallet_uses_uid_seq OWNED BY shulesoft.wallet_uses.uid;


--
-- TOC entry 2631 (class 1259 OID 52921)
-- Name: wallets_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.wallets_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.wallets_uid_seq OWNER TO postgres;

--
-- TOC entry 14309 (class 0 OID 0)
-- Dependencies: 2631
-- Name: wallets_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.wallets_uid_seq OWNED BY shulesoft.wallets.uid;


--
-- TOC entry 2632 (class 1259 OID 52922)
-- Name: warehouse_store_keepers_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.warehouse_store_keepers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.warehouse_store_keepers_id_seq OWNER TO postgres;

--
-- TOC entry 2633 (class 1259 OID 52923)
-- Name: warehouse_store_keepers; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.warehouse_store_keepers (
    id bigint DEFAULT nextval('shulesoft.warehouse_store_keepers_id_seq'::regclass) NOT NULL,
    warehouse_id bigint NOT NULL,
    store_keeper_id bigint,
    schema_name character varying,
    updated_at timestamp without time zone,
    created_at timestamp without time zone DEFAULT now()
);


ALTER TABLE shulesoft.warehouse_store_keepers OWNER TO postgres;

--
-- TOC entry 2634 (class 1259 OID 52930)
-- Name: warehouse_transfers_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.warehouse_transfers_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.warehouse_transfers_uid_seq OWNER TO postgres;

--
-- TOC entry 14310 (class 0 OID 0)
-- Dependencies: 2634
-- Name: warehouse_transfers_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.warehouse_transfers_uid_seq OWNED BY shulesoft.warehouse_transfers.uid;


--
-- TOC entry 2635 (class 1259 OID 52931)
-- Name: warehouses_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.warehouses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.warehouses_id_seq OWNER TO postgres;

--
-- TOC entry 2636 (class 1259 OID 52932)
-- Name: warehouses; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.warehouses (
    id integer DEFAULT nextval('shulesoft.warehouses_id_seq'::regclass) NOT NULL,
    name character varying,
    description text,
    user_sid character varying,
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    storekeeper_ids integer
);


ALTER TABLE shulesoft.warehouses OWNER TO postgres;

--
-- TOC entry 2637 (class 1259 OID 52940)
-- Name: warehouses_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.warehouses_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.warehouses_uid_seq OWNER TO postgres;

--
-- TOC entry 14311 (class 0 OID 0)
-- Dependencies: 2637
-- Name: warehouses_uid_seq; Type: SEQUENCE OWNED BY; Schema: shulesoft; Owner: postgres
--

ALTER SEQUENCE shulesoft.warehouses_uid_seq OWNED BY shulesoft.warehouses.uid;


--
-- TOC entry 2638 (class 1259 OID 52941)
-- Name: youtube_access_tokens_id_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.youtube_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.youtube_access_tokens_id_seq OWNER TO postgres;

--
-- TOC entry 2639 (class 1259 OID 52942)
-- Name: youtube_access_tokens_uid_seq; Type: SEQUENCE; Schema: shulesoft; Owner: postgres
--

CREATE SEQUENCE shulesoft.youtube_access_tokens_uid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE shulesoft.youtube_access_tokens_uid_seq OWNER TO postgres;

--
-- TOC entry 2640 (class 1259 OID 52943)
-- Name: youtube_access_tokens; Type: TABLE; Schema: shulesoft; Owner: postgres
--

CREATE TABLE shulesoft.youtube_access_tokens (
    id integer DEFAULT nextval('shulesoft.youtube_access_tokens_id_seq'::regclass) NOT NULL,
    access_token text NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone,
    uuid uuid DEFAULT admin.uuid_generate_v4() NOT NULL,
    schema_name character varying NOT NULL,
    uid integer DEFAULT nextval('shulesoft.youtube_access_tokens_uid_seq'::regclass) NOT NULL
);


ALTER TABLE shulesoft.youtube_access_tokens OWNER TO postgres;

--
-- TOC entry 11245 (class 2604 OID 52951)
-- Name: academic_year uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.academic_year ALTER COLUMN uid SET DEFAULT nextval('shulesoft.academic_year_uid_seq'::regclass);


--
-- TOC entry 11445 (class 2604 OID 52952)
-- Name: account_groups uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.account_groups ALTER COLUMN uid SET DEFAULT nextval('shulesoft.account_groups_uid_seq'::regclass);


--
-- TOC entry 11249 (class 2604 OID 52953)
-- Name: admissions uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.admissions ALTER COLUMN uid SET DEFAULT nextval('shulesoft.admissions_uid_seq'::regclass);


--
-- TOC entry 11449 (class 2604 OID 52954)
-- Name: advance_payments uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.advance_payments ALTER COLUMN uid SET DEFAULT nextval('shulesoft.advance_payments_uid_seq'::regclass);


--
-- TOC entry 11453 (class 2604 OID 52955)
-- Name: advance_payments_invoices_fees_installments uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.advance_payments_invoices_fees_installments ALTER COLUMN uid SET DEFAULT nextval('shulesoft.advance_payments_invoices_fees_installments_uid_seq'::regclass);


--
-- TOC entry 11489 (class 2604 OID 52956)
-- Name: allowances uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.allowances ALTER COLUMN uid SET DEFAULT nextval('shulesoft.allowances_uid_seq'::regclass);


--
-- TOC entry 11555 (class 2604 OID 52957)
-- Name: application uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.application ALTER COLUMN uid SET DEFAULT nextval('shulesoft.application_uid_seq'::regclass);


--
-- TOC entry 11559 (class 2604 OID 52958)
-- Name: appointments uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.appointments ALTER COLUMN uid SET DEFAULT nextval('shulesoft.appointments_uid_seq'::regclass);


--
-- TOC entry 11563 (class 2604 OID 52959)
-- Name: assignment_downloads uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.assignment_downloads ALTER COLUMN uid SET DEFAULT nextval('shulesoft.assignment_downloads_uid_seq'::regclass);


--
-- TOC entry 11568 (class 2604 OID 52960)
-- Name: assignment_files uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.assignment_files ALTER COLUMN uid SET DEFAULT nextval('shulesoft.assignment_files_uid_seq'::regclass);


--
-- TOC entry 11572 (class 2604 OID 52961)
-- Name: assignment_viewers uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.assignment_viewers ALTER COLUMN uid SET DEFAULT nextval('shulesoft.assignment_viewers_uid_seq'::regclass);


--
-- TOC entry 11576 (class 2604 OID 52962)
-- Name: assignments uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.assignments ALTER COLUMN uid SET DEFAULT nextval('shulesoft.assignments_uid_seq'::regclass);


--
-- TOC entry 11580 (class 2604 OID 52963)
-- Name: assignments_submitted uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.assignments_submitted ALTER COLUMN uid SET DEFAULT nextval('shulesoft.assignments_submitted_uid_seq'::regclass);


--
-- TOC entry 11584 (class 2604 OID 52964)
-- Name: attendance uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.attendance ALTER COLUMN uid SET DEFAULT nextval('shulesoft.attendance_uid_seq'::regclass);


--
-- TOC entry 11253 (class 2604 OID 52965)
-- Name: bank_accounts uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.bank_accounts ALTER COLUMN uid SET DEFAULT nextval('shulesoft.bank_accounts_uid_seq'::regclass);


--
-- TOC entry 11588 (class 2604 OID 52966)
-- Name: bank_accounts_fees_classes uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.bank_accounts_fees_classes ALTER COLUMN uid SET DEFAULT nextval('shulesoft.bank_accounts_fees_classes_uid_seq'::regclass);


--
-- TOC entry 11260 (class 2604 OID 52967)
-- Name: bank_accounts_integrations uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.bank_accounts_integrations ALTER COLUMN uid SET DEFAULT nextval('shulesoft.bank_accounts_integrations_uid_seq'::regclass);


--
-- TOC entry 11601 (class 2604 OID 52968)
-- Name: book uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.book ALTER COLUMN uid SET DEFAULT nextval('shulesoft.book_uid_seq'::regclass);


--
-- TOC entry 11605 (class 2604 OID 52969)
-- Name: book_class uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.book_class ALTER COLUMN uid SET DEFAULT nextval('shulesoft.book_class_uid_seq'::regclass);


--
-- TOC entry 11610 (class 2604 OID 52970)
-- Name: book_quantity uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.book_quantity ALTER COLUMN uid SET DEFAULT nextval('shulesoft.book_quantity_uid_seq'::regclass);


--
-- TOC entry 11611 (class 2604 OID 52971)
-- Name: budget_item_period_amounts id; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.budget_item_period_amounts ALTER COLUMN id SET DEFAULT nextval('shulesoft.budget_item_period_amounts_id_seq'::regclass);


--
-- TOC entry 11613 (class 2604 OID 52972)
-- Name: budget_items id; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.budget_items ALTER COLUMN id SET DEFAULT nextval('shulesoft.budget_items_id_seq'::regclass);


--
-- TOC entry 11616 (class 2604 OID 52973)
-- Name: budgets id; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.budgets ALTER COLUMN id SET DEFAULT nextval('shulesoft.budgets_id_seq'::regclass);


--
-- TOC entry 11619 (class 2604 OID 52974)
-- Name: capital id; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.capital ALTER COLUMN id SET DEFAULT nextval('shulesoft.capital_id_seq'::regclass);


--
-- TOC entry 11620 (class 2604 OID 52975)
-- Name: capital uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.capital ALTER COLUMN uid SET DEFAULT nextval('shulesoft.capital_uid_seq'::regclass);


--
-- TOC entry 11628 (class 2604 OID 52976)
-- Name: car_tracker_key uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.car_tracker_key ALTER COLUMN uid SET DEFAULT nextval('shulesoft.car_tracker_key_uid_seq'::regclass);


--
-- TOC entry 11632 (class 2604 OID 52977)
-- Name: cash_requests uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.cash_requests ALTER COLUMN uid SET DEFAULT nextval('shulesoft.cash_requests_uid_seq'::regclass);


--
-- TOC entry 11638 (class 2604 OID 52978)
-- Name: certificate_setting uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.certificate_setting ALTER COLUMN uid SET DEFAULT nextval('shulesoft.certificate_setting_uid_seq'::regclass);


--
-- TOC entry 11642 (class 2604 OID 52979)
-- Name: character_categories uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.character_categories ALTER COLUMN uid SET DEFAULT nextval('shulesoft.character_categories_uid_seq'::regclass);


--
-- TOC entry 11646 (class 2604 OID 52980)
-- Name: character_classes uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.character_classes ALTER COLUMN uid SET DEFAULT nextval('shulesoft.character_classes_uid_seq'::regclass);


--
-- TOC entry 11650 (class 2604 OID 52981)
-- Name: characters uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.characters ALTER COLUMN uid SET DEFAULT nextval('shulesoft.characters_uid_seq'::regclass);


--
-- TOC entry 11654 (class 2604 OID 52982)
-- Name: class_exam uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.class_exam ALTER COLUMN uid SET DEFAULT nextval('shulesoft.class_exam_uid_seq'::regclass);


--
-- TOC entry 11264 (class 2604 OID 52983)
-- Name: classes uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.classes ALTER COLUMN uid SET DEFAULT nextval('shulesoft.classes_uid_seq'::regclass);


--
-- TOC entry 11270 (class 2604 OID 52984)
-- Name: classlevel uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.classlevel ALTER COLUMN uid SET DEFAULT nextval('shulesoft.classlevel_uid_seq'::regclass);


--
-- TOC entry 11657 (class 2604 OID 52985)
-- Name: closing_year_balance uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.closing_year_balance ALTER COLUMN uid SET DEFAULT nextval('shulesoft.closing_year_balance_uid_seq'::regclass);


--
-- TOC entry 11658 (class 2604 OID 52986)
-- Name: configurations id; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.configurations ALTER COLUMN id SET DEFAULT nextval('shulesoft.configurations_id_seq'::regclass);


--
-- TOC entry 11659 (class 2604 OID 52987)
-- Name: configurations uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.configurations ALTER COLUMN uid SET DEFAULT nextval('shulesoft.configurations_uid_seq'::regclass);


--
-- TOC entry 11676 (class 2604 OID 52988)
-- Name: current_assets id; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.current_assets ALTER COLUMN id SET DEFAULT nextval('shulesoft.current_assets_id_seq1'::regclass);


--
-- TOC entry 11677 (class 2604 OID 52989)
-- Name: current_assets uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.current_assets ALTER COLUMN uid SET DEFAULT nextval('shulesoft.current_assets_uid_seq1'::regclass);


--
-- TOC entry 11675 (class 2604 OID 52990)
-- Name: current_assets2 uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.current_assets2 ALTER COLUMN uid SET DEFAULT nextval('shulesoft.current_assets_uid_seq'::regclass);


--
-- TOC entry 11688 (class 2604 OID 52991)
-- Name: deductions uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.deductions ALTER COLUMN uid SET DEFAULT nextval('shulesoft.deductions_uid_seq'::regclass);


--
-- TOC entry 11274 (class 2604 OID 52992)
-- Name: diaries uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.diaries ALTER COLUMN uid SET DEFAULT nextval('shulesoft.diaries_uid_seq'::regclass);


--
-- TOC entry 11693 (class 2604 OID 52993)
-- Name: diary_comments uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.diary_comments ALTER COLUMN uid SET DEFAULT nextval('shulesoft.diary_comments_uid_seq'::regclass);


--
-- TOC entry 11493 (class 2604 OID 52994)
-- Name: discount_fees_installments uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.discount_fees_installments ALTER COLUMN uid SET DEFAULT nextval('shulesoft.discount_fees_installments_uid_seq'::regclass);


--
-- TOC entry 11498 (class 2604 OID 52995)
-- Name: due_amounts uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.due_amounts ALTER COLUMN uid SET DEFAULT nextval('shulesoft.due_amounts_uid_seq'::regclass);


--
-- TOC entry 11697 (class 2604 OID 52996)
-- Name: due_amounts_payments uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.due_amounts_payments ALTER COLUMN uid SET DEFAULT nextval('shulesoft.due_amounts_payments_uid_seq'::regclass);


--
-- TOC entry 11433 (class 2604 OID 52997)
-- Name: duties uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.duties ALTER COLUMN uid SET DEFAULT nextval('shulesoft.duties_uid_seq'::regclass);


--
-- TOC entry 11700 (class 2604 OID 52998)
-- Name: duty_reports uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.duty_reports ALTER COLUMN uid SET DEFAULT nextval('shulesoft.duty_reports_uid_seq'::regclass);


--
-- TOC entry 11704 (class 2604 OID 52999)
-- Name: eattendance uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.eattendance ALTER COLUMN uid SET DEFAULT nextval('shulesoft.eattendance_uid_seq'::regclass);


--
-- TOC entry 11710 (class 2604 OID 53000)
-- Name: email uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.email ALTER COLUMN uid SET DEFAULT nextval('shulesoft.email_uid_seq'::regclass);


--
-- TOC entry 11714 (class 2604 OID 53001)
-- Name: email_lists uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.email_lists ALTER COLUMN uid SET DEFAULT nextval('shulesoft.email_lists_uid_seq'::regclass);


--
-- TOC entry 11722 (class 2604 OID 53002)
-- Name: exam uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.exam ALTER COLUMN uid SET DEFAULT nextval('shulesoft.exam_uid_seq'::regclass);


--
-- TOC entry 11728 (class 2604 OID 53003)
-- Name: exam_comments uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.exam_comments ALTER COLUMN uid SET DEFAULT nextval('shulesoft.exam_comments_uid_seq'::regclass);


--
-- TOC entry 11732 (class 2604 OID 53004)
-- Name: exam_groups uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.exam_groups ALTER COLUMN uid SET DEFAULT nextval('shulesoft.exam_groups_uid_seq'::regclass);


--
-- TOC entry 11284 (class 2604 OID 53005)
-- Name: exam_report uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.exam_report ALTER COLUMN uid SET DEFAULT nextval('shulesoft.exam_report_uid_seq'::regclass);


--
-- TOC entry 11751 (class 2604 OID 53006)
-- Name: exam_report_settings uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.exam_report_settings ALTER COLUMN uid SET DEFAULT nextval('shulesoft.exam_report_settings_uid_seq'::regclass);


--
-- TOC entry 11755 (class 2604 OID 53007)
-- Name: exam_special_cases uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.exam_special_cases ALTER COLUMN uid SET DEFAULT nextval('shulesoft.exam_special_cases_uid_seq'::regclass);


--
-- TOC entry 11760 (class 2604 OID 53008)
-- Name: exam_subject_mark uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.exam_subject_mark ALTER COLUMN uid SET DEFAULT nextval('shulesoft.exam_subject_mark_uid_seq'::regclass);


--
-- TOC entry 11764 (class 2604 OID 53009)
-- Name: examschedule uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.examschedule ALTER COLUMN uid SET DEFAULT nextval('shulesoft.examschedule_uid_seq'::regclass);


--
-- TOC entry 11768 (class 2604 OID 53010)
-- Name: exchange_rates uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.exchange_rates ALTER COLUMN uid SET DEFAULT nextval('shulesoft.exchange_rates_uid_seq'::regclass);


--
-- TOC entry 11290 (class 2604 OID 53011)
-- Name: expense uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.expense ALTER COLUMN uid SET DEFAULT nextval('shulesoft.expense_uid_seq'::regclass);


--
-- TOC entry 11772 (class 2604 OID 53012)
-- Name: expense_cart uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.expense_cart ALTER COLUMN uid SET DEFAULT nextval('shulesoft.expense_cart_uid_seq'::regclass);


--
-- TOC entry 11776 (class 2604 OID 53013)
-- Name: expenses id; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.expenses ALTER COLUMN id SET DEFAULT nextval('shulesoft.expenses_id_seq'::regclass);


--
-- TOC entry 11777 (class 2604 OID 53014)
-- Name: expenses uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.expenses ALTER COLUMN uid SET DEFAULT nextval('shulesoft.expenses_uid_seq'::regclass);


--
-- TOC entry 11786 (class 2604 OID 53015)
-- Name: feecat_class uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.feecat_class ALTER COLUMN uid SET DEFAULT nextval('shulesoft.feecat_class_uid_seq'::regclass);


--
-- TOC entry 11503 (class 2604 OID 53016)
-- Name: fees uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.fees ALTER COLUMN uid SET DEFAULT nextval('shulesoft.fees_uid_seq'::regclass);


--
-- TOC entry 11790 (class 2604 OID 53017)
-- Name: fees_classes uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.fees_classes ALTER COLUMN uid SET DEFAULT nextval('shulesoft.fees_classes_uid_seq'::regclass);


--
-- TOC entry 11507 (class 2604 OID 53018)
-- Name: fees_installments uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.fees_installments ALTER COLUMN uid SET DEFAULT nextval('shulesoft.fees_installments_uid_seq'::regclass);


--
-- TOC entry 11511 (class 2604 OID 53019)
-- Name: fees_installments_classes uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.fees_installments_classes ALTER COLUMN uid SET DEFAULT nextval('shulesoft.fees_installments_classes_uid_seq'::regclass);


--
-- TOC entry 11794 (class 2604 OID 53020)
-- Name: file_folder uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.file_folder ALTER COLUMN uid SET DEFAULT nextval('shulesoft.file_folder_uid_seq'::regclass);


--
-- TOC entry 11798 (class 2604 OID 53021)
-- Name: file_share uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.file_share ALTER COLUMN uid SET DEFAULT nextval('shulesoft.file_share_uid_seq'::regclass);


--
-- TOC entry 11803 (class 2604 OID 53022)
-- Name: files uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.files ALTER COLUMN uid SET DEFAULT nextval('shulesoft.files_uid_seq'::regclass);


--
-- TOC entry 11807 (class 2604 OID 53023)
-- Name: financial_year uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.financial_year ALTER COLUMN uid SET DEFAULT nextval('shulesoft.financial_year_uid_seq'::regclass);


--
-- TOC entry 11812 (class 2604 OID 53024)
-- Name: fixed_assets id; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.fixed_assets ALTER COLUMN id SET DEFAULT nextval('shulesoft.fixed_assets_id_seq'::regclass);


--
-- TOC entry 11813 (class 2604 OID 53025)
-- Name: fixed_assets uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.fixed_assets ALTER COLUMN uid SET DEFAULT nextval('shulesoft.fixed_assets_uid_seq'::regclass);


--
-- TOC entry 11822 (class 2604 OID 53026)
-- Name: forum_answer_votes uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.forum_answer_votes ALTER COLUMN uid SET DEFAULT nextval('shulesoft.forum_answer_votes_uid_seq'::regclass);


--
-- TOC entry 11827 (class 2604 OID 53027)
-- Name: forum_answers uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.forum_answers ALTER COLUMN uid SET DEFAULT nextval('shulesoft.forum_answers_uid_seq'::regclass);


--
-- TOC entry 11831 (class 2604 OID 53028)
-- Name: forum_categories uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.forum_categories ALTER COLUMN uid SET DEFAULT nextval('shulesoft.forum_categories_uid_seq'::regclass);


--
-- TOC entry 11840 (class 2604 OID 53029)
-- Name: forum_discussion uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.forum_discussion ALTER COLUMN uid SET DEFAULT nextval('shulesoft.forum_discussion_uid_seq'::regclass);


--
-- TOC entry 11845 (class 2604 OID 53030)
-- Name: forum_post uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.forum_post ALTER COLUMN uid SET DEFAULT nextval('shulesoft.forum_post_uid_seq'::regclass);


--
-- TOC entry 11849 (class 2604 OID 53031)
-- Name: forum_question_viewers uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.forum_question_viewers ALTER COLUMN uid SET DEFAULT nextval('shulesoft.forum_question_viewers_uid_seq'::regclass);


--
-- TOC entry 11854 (class 2604 OID 53032)
-- Name: forum_questions uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.forum_questions ALTER COLUMN uid SET DEFAULT nextval('shulesoft.forum_questions_uid_seq'::regclass);


--
-- TOC entry 11858 (class 2604 OID 53033)
-- Name: forum_questions_comments uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.forum_questions_comments ALTER COLUMN uid SET DEFAULT nextval('shulesoft.forum_questions_comments_uid_seq'::regclass);


--
-- TOC entry 11862 (class 2604 OID 53034)
-- Name: forum_questions_votes uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.forum_questions_votes ALTER COLUMN uid SET DEFAULT nextval('shulesoft.forum_questions_votes_uid_seq'::regclass);


--
-- TOC entry 11866 (class 2604 OID 53035)
-- Name: forum_user_discussion uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.forum_user_discussion ALTER COLUMN uid SET DEFAULT nextval('shulesoft.forum_user_discussion_uid_seq'::regclass);


--
-- TOC entry 11294 (class 2604 OID 53036)
-- Name: general_character_assessment uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.general_character_assessment ALTER COLUMN uid SET DEFAULT nextval('shulesoft.general_character_assessment_uid_seq'::regclass);


--
-- TOC entry 11871 (class 2604 OID 53037)
-- Name: grade uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.grade ALTER COLUMN uid SET DEFAULT nextval('shulesoft.grade_uid_seq'::regclass);


--
-- TOC entry 11875 (class 2604 OID 53038)
-- Name: hattendances uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.hattendances ALTER COLUMN uid SET DEFAULT nextval('shulesoft.hattendances_uid_seq'::regclass);


--
-- TOC entry 11298 (class 2604 OID 53039)
-- Name: hmembers uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.hmembers ALTER COLUMN uid SET DEFAULT nextval('shulesoft.hmembers_uid_seq'::regclass);


--
-- TOC entry 11879 (class 2604 OID 53040)
-- Name: hostel_beds uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.hostel_beds ALTER COLUMN uid SET DEFAULT nextval('shulesoft.hostel_beds_uid_seq'::regclass);


--
-- TOC entry 11883 (class 2604 OID 53041)
-- Name: hostel_category uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.hostel_category ALTER COLUMN uid SET DEFAULT nextval('shulesoft.hostel_category_uid_seq'::regclass);


--
-- TOC entry 11515 (class 2604 OID 53042)
-- Name: hostel_fees_installments uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.hostel_fees_installments ALTER COLUMN uid SET DEFAULT nextval('shulesoft.hostel_fees_installments_uid_seq'::regclass);


--
-- TOC entry 11519 (class 2604 OID 53043)
-- Name: hostels uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.hostels ALTER COLUMN uid SET DEFAULT nextval('shulesoft.hostels_uid_seq'::regclass);


--
-- TOC entry 11896 (class 2604 OID 53044)
-- Name: id_cards uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.id_cards ALTER COLUMN uid SET DEFAULT nextval('shulesoft.id_cards_uid_seq'::regclass);


--
-- TOC entry 11898 (class 2604 OID 53045)
-- Name: installment_packages id; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.installment_packages ALTER COLUMN id SET DEFAULT nextval('shulesoft.installment_packages_id_seq'::regclass);


--
-- TOC entry 11523 (class 2604 OID 53046)
-- Name: installments uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.installments ALTER COLUMN uid SET DEFAULT nextval('shulesoft.installments_uid_seq'::regclass);


--
-- TOC entry 11910 (class 2604 OID 53047)
-- Name: invoice_prefix uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.invoice_prefix ALTER COLUMN uid SET DEFAULT nextval('shulesoft.invoice_prefix_uid_seq'::regclass);


--
-- TOC entry 11919 (class 2604 OID 53048)
-- Name: invoice_settings uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.invoice_settings ALTER COLUMN uid SET DEFAULT nextval('shulesoft.invoice_settings_uid_seq'::regclass);


--
-- TOC entry 11308 (class 2604 OID 53049)
-- Name: invoices uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.invoices ALTER COLUMN uid SET DEFAULT nextval('shulesoft.invoices_uid_seq'::regclass);


--
-- TOC entry 11534 (class 2604 OID 53050)
-- Name: invoices_fees_installments uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.invoices_fees_installments ALTER COLUMN uid SET DEFAULT nextval('shulesoft.invoices_fees_installments_uid_seq'::regclass);


--
-- TOC entry 11925 (class 2604 OID 53051)
-- Name: issue uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.issue ALTER COLUMN uid SET DEFAULT nextval('shulesoft.issue_uid_seq'::regclass);


--
-- TOC entry 11928 (class 2604 OID 53052)
-- Name: issue_inventory uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.issue_inventory ALTER COLUMN uid SET DEFAULT nextval('shulesoft.issue_inventory_uid_seq'::regclass);


--
-- TOC entry 11942 (class 2604 OID 53053)
-- Name: items uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.items ALTER COLUMN uid SET DEFAULT nextval('shulesoft.items_uid_seq'::regclass);


--
-- TOC entry 11943 (class 2604 OID 53054)
-- Name: journal_group id; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.journal_group ALTER COLUMN id SET DEFAULT nextval('shulesoft.journal_group_id_seq'::regclass);


--
-- TOC entry 11944 (class 2604 OID 53055)
-- Name: journals id; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.journals ALTER COLUMN id SET DEFAULT nextval('shulesoft.journals_id_seq'::regclass);


--
-- TOC entry 11948 (class 2604 OID 53056)
-- Name: journals uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.journals ALTER COLUMN uid SET DEFAULT nextval('shulesoft.journals_uid_seq'::regclass);


--
-- TOC entry 11952 (class 2604 OID 53057)
-- Name: lesson_plan uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.lesson_plan ALTER COLUMN uid SET DEFAULT nextval('shulesoft.lesson_plan_uid_seq'::regclass);


--
-- TOC entry 11953 (class 2604 OID 53058)
-- Name: liabilities id; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.liabilities ALTER COLUMN id SET DEFAULT nextval('shulesoft.liabilities_id_seq'::regclass);


--
-- TOC entry 11954 (class 2604 OID 53059)
-- Name: liabilities uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.liabilities ALTER COLUMN uid SET DEFAULT nextval('shulesoft.liabilities_uid_seq'::regclass);


--
-- TOC entry 11963 (class 2604 OID 53060)
-- Name: livestudy_packages uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.livestudy_packages ALTER COLUMN uid SET DEFAULT nextval('shulesoft.livestudy_packages_uid_seq'::regclass);


--
-- TOC entry 11967 (class 2604 OID 53061)
-- Name: livestudy_payments uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.livestudy_payments ALTER COLUMN uid SET DEFAULT nextval('shulesoft.livestudy_payments_uid_seq'::regclass);


--
-- TOC entry 11972 (class 2604 OID 53062)
-- Name: lmember uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.lmember ALTER COLUMN uid SET DEFAULT nextval('shulesoft.lmember_uid_seq'::regclass);


--
-- TOC entry 11977 (class 2604 OID 53063)
-- Name: loan_applications uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.loan_applications ALTER COLUMN uid SET DEFAULT nextval('shulesoft.loan_applications_uid_seq'::regclass);


--
-- TOC entry 11983 (class 2604 OID 53064)
-- Name: loan_payments uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.loan_payments ALTER COLUMN uid SET DEFAULT nextval('shulesoft.loan_payments_uid_seq'::regclass);


--
-- TOC entry 11988 (class 2604 OID 53065)
-- Name: loan_types uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.loan_types ALTER COLUMN uid SET DEFAULT nextval('shulesoft.loan_types_uid_seq'::regclass);


--
-- TOC entry 11993 (class 2604 OID 53066)
-- Name: log uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.log ALTER COLUMN uid SET DEFAULT nextval('shulesoft.log_uid_seq'::regclass);


--
-- TOC entry 11997 (class 2604 OID 53067)
-- Name: login_attempts uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.login_attempts ALTER COLUMN uid SET DEFAULT nextval('shulesoft.login_attempts_uid_seq'::regclass);


--
-- TOC entry 11313 (class 2604 OID 53068)
-- Name: login_locations uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.login_locations ALTER COLUMN uid SET DEFAULT nextval('shulesoft.login_locations_uid_seq'::regclass);


--
-- TOC entry 12002 (class 2604 OID 53069)
-- Name: mailandsms uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.mailandsms ALTER COLUMN uid SET DEFAULT nextval('shulesoft.mailandsms_uid_seq'::regclass);


--
-- TOC entry 12008 (class 2604 OID 53070)
-- Name: mailandsmstemplate uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.mailandsmstemplate ALTER COLUMN uid SET DEFAULT nextval('shulesoft.mailandsmstemplate_uid_seq'::regclass);


--
-- TOC entry 12013 (class 2604 OID 53071)
-- Name: mailandsmstemplatetag uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.mailandsmstemplatetag ALTER COLUMN uid SET DEFAULT nextval('shulesoft.mailandsmstemplatetag_uid_seq'::regclass);


--
-- TOC entry 12017 (class 2604 OID 53072)
-- Name: major_subjects uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.major_subjects ALTER COLUMN uid SET DEFAULT nextval('shulesoft.major_subjects_uid_seq'::regclass);


--
-- TOC entry 12018 (class 2604 OID 53073)
-- Name: manage_budgets id; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.manage_budgets ALTER COLUMN id SET DEFAULT nextval('shulesoft.manage_budgets_id_seq'::regclass);


--
-- TOC entry 11319 (class 2604 OID 53074)
-- Name: mark uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.mark ALTER COLUMN uid SET DEFAULT nextval('shulesoft.mark_uid_seq'::regclass);


--
-- TOC entry 12040 (class 2604 OID 53075)
-- Name: media uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.media ALTER COLUMN uid SET DEFAULT nextval('shulesoft.media_uid_seq'::regclass);


--
-- TOC entry 12045 (class 2604 OID 53076)
-- Name: media_categories uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.media_categories ALTER COLUMN uid SET DEFAULT nextval('shulesoft.media_categories_uid_seq'::regclass);


--
-- TOC entry 12050 (class 2604 OID 53077)
-- Name: media_category uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.media_category ALTER COLUMN uid SET DEFAULT nextval('shulesoft.media_category_uid_seq'::regclass);


--
-- TOC entry 12055 (class 2604 OID 53078)
-- Name: media_comment_reply uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.media_comment_reply ALTER COLUMN uid SET DEFAULT nextval('shulesoft.media_comment_reply_uid_seq'::regclass);


--
-- TOC entry 12059 (class 2604 OID 53079)
-- Name: media_comments uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.media_comments ALTER COLUMN uid SET DEFAULT nextval('shulesoft.media_comments_uid_seq'::regclass);


--
-- TOC entry 12063 (class 2604 OID 53080)
-- Name: media_likes uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.media_likes ALTER COLUMN uid SET DEFAULT nextval('shulesoft.media_likes_uid_seq'::regclass);


--
-- TOC entry 12067 (class 2604 OID 53081)
-- Name: media_live uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.media_live ALTER COLUMN uid SET DEFAULT nextval('shulesoft.media_live_uid_seq'::regclass);


--
-- TOC entry 12071 (class 2604 OID 53082)
-- Name: media_live_comments uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.media_live_comments ALTER COLUMN uid SET DEFAULT nextval('shulesoft.media_live_comments_uid_seq'::regclass);


--
-- TOC entry 12076 (class 2604 OID 53083)
-- Name: media_share uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.media_share ALTER COLUMN uid SET DEFAULT nextval('shulesoft.media_share_uid_seq'::regclass);


--
-- TOC entry 12089 (class 2604 OID 53084)
-- Name: media_viewers uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.media_viewers ALTER COLUMN uid SET DEFAULT nextval('shulesoft.media_viewers_uid_seq'::regclass);


--
-- TOC entry 12081 (class 2604 OID 53085)
-- Name: medias uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.medias ALTER COLUMN uid SET DEFAULT nextval('shulesoft.medias_uid_seq'::regclass);


--
-- TOC entry 12094 (class 2604 OID 53086)
-- Name: message uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.message ALTER COLUMN uid SET DEFAULT nextval('shulesoft.message_uid_seq'::regclass);


--
-- TOC entry 12104 (class 2604 OID 53087)
-- Name: minor_exam_marks uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.minor_exam_marks ALTER COLUMN uid SET DEFAULT nextval('shulesoft.minor_exam_marks_uid_seq'::regclass);


--
-- TOC entry 12111 (class 2604 OID 53088)
-- Name: minor_exams uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.minor_exams ALTER COLUMN uid SET DEFAULT nextval('shulesoft.minor_exams_uid_seq'::regclass);


--
-- TOC entry 12114 (class 2604 OID 53089)
-- Name: necta uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.necta ALTER COLUMN uid SET DEFAULT nextval('shulesoft.necta_uid_seq'::regclass);


--
-- TOC entry 12120 (class 2604 OID 53090)
-- Name: news_board uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.news_board ALTER COLUMN uid SET DEFAULT nextval('shulesoft.news_board_uid_seq'::regclass);


--
-- TOC entry 11325 (class 2604 OID 53091)
-- Name: notice uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.notice ALTER COLUMN uid SET DEFAULT nextval('shulesoft.notice_uid_seq'::regclass);


--
-- TOC entry 12124 (class 2604 OID 53092)
-- Name: page_tips_viewers uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.page_tips_viewers ALTER COLUMN uid SET DEFAULT nextval('shulesoft.page_tips_viewers_uid_seq'::regclass);


--
-- TOC entry 11335 (class 2604 OID 53093)
-- Name: parent uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.parent ALTER COLUMN uid SET DEFAULT nextval('shulesoft.parent_uid_seq'::regclass);


--
-- TOC entry 12128 (class 2604 OID 53094)
-- Name: parent_documents uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.parent_documents ALTER COLUMN uid SET DEFAULT nextval('shulesoft.parent_documents_uid_seq'::regclass);


--
-- TOC entry 12132 (class 2604 OID 53095)
-- Name: parent_phones uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.parent_phones ALTER COLUMN uid SET DEFAULT nextval('shulesoft.parent_phones_uid_seq'::regclass);


--
-- TOC entry 11457 (class 2604 OID 53096)
-- Name: payment_types uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.payment_types ALTER COLUMN uid SET DEFAULT nextval('shulesoft.payment_types_uid_seq'::regclass);


--
-- TOC entry 11461 (class 2604 OID 53097)
-- Name: payments receipt_code; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.payments ALTER COLUMN receipt_code SET DEFAULT nextval('shulesoft.payments_receipt_seq'::regclass);


--
-- TOC entry 11466 (class 2604 OID 53098)
-- Name: payments uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.payments ALTER COLUMN uid SET DEFAULT nextval('shulesoft.payments_uid_seq'::regclass);


--
-- TOC entry 11538 (class 2604 OID 53099)
-- Name: payments_invoices_fees_installments uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.payments_invoices_fees_installments ALTER COLUMN uid SET DEFAULT nextval('shulesoft.payments_invoices_fees_installments_uid_seq'::regclass);


--
-- TOC entry 12149 (class 2604 OID 53100)
-- Name: payroll_setting uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.payroll_setting ALTER COLUMN uid SET DEFAULT nextval('shulesoft.payroll_setting_uid_seq'::regclass);


--
-- TOC entry 12158 (class 2604 OID 53101)
-- Name: payslip_settings uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.payslip_settings ALTER COLUMN uid SET DEFAULT nextval('shulesoft.payslip_settings_uid_seq'::regclass);


--
-- TOC entry 12162 (class 2604 OID 53102)
-- Name: pensions uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.pensions ALTER COLUMN uid SET DEFAULT nextval('shulesoft.pensions_uid_seq'::regclass);


--
-- TOC entry 12168 (class 2604 OID 53103)
-- Name: prepayments uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.prepayments ALTER COLUMN uid SET DEFAULT nextval('shulesoft.prepayments_uid_seq'::regclass);


--
-- TOC entry 11336 (class 2604 OID 53104)
-- Name: product_alert_quantity id; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.product_alert_quantity ALTER COLUMN id SET DEFAULT nextval('shulesoft.product_alert_quantity_id_seq1'::regclass);


--
-- TOC entry 11340 (class 2604 OID 53105)
-- Name: product_alert_quantity uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.product_alert_quantity ALTER COLUMN uid SET DEFAULT nextval('shulesoft.product_alert_quantity_uid_seq'::regclass);


--
-- TOC entry 11811 (class 2604 OID 53106)
-- Name: product_cart uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.product_cart ALTER COLUMN uid SET DEFAULT nextval('shulesoft.product_cart_uid_seq'::regclass);


--
-- TOC entry 11775 (class 2604 OID 53107)
-- Name: product_purchases uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.product_purchases ALTER COLUMN uid SET DEFAULT nextval('shulesoft.product_purchases_uid_seq'::regclass);


--
-- TOC entry 12174 (class 2604 OID 53108)
-- Name: product_registers uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.product_registers ALTER COLUMN uid SET DEFAULT nextval('shulesoft.product_registers_uid_seq'::regclass);


--
-- TOC entry 11932 (class 2604 OID 53109)
-- Name: product_sales uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.product_sales ALTER COLUMN uid SET DEFAULT nextval('shulesoft.product_sales_uid_seq'::regclass);


--
-- TOC entry 12175 (class 2604 OID 53110)
-- Name: proforma_invoices id; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.proforma_invoices ALTER COLUMN id SET DEFAULT nextval('shulesoft.proforma_invoices_id_seq'::regclass);


--
-- TOC entry 12182 (class 2604 OID 53111)
-- Name: proforma_invoices uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.proforma_invoices ALTER COLUMN uid SET DEFAULT nextval('shulesoft.proforma_invoices_uid_seq'::regclass);


--
-- TOC entry 12184 (class 2604 OID 53112)
-- Name: proforma_invoices_fee_amount id; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.proforma_invoices_fee_amount ALTER COLUMN id SET DEFAULT nextval('shulesoft.proforma_invoices_fee_amount_id_seq'::regclass);


--
-- TOC entry 12186 (class 2604 OID 53113)
-- Name: proforma_invoices_fee_amount uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.proforma_invoices_fee_amount ALTER COLUMN uid SET DEFAULT nextval('shulesoft.proforma_invoices_fee_amount_uid_seq'::regclass);


--
-- TOC entry 12188 (class 2604 OID 53114)
-- Name: proforma_invoices_fees_installments id; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.proforma_invoices_fees_installments ALTER COLUMN id SET DEFAULT nextval('shulesoft.proforma_invoices_fees_installments_id_seq'::regclass);


--
-- TOC entry 12191 (class 2604 OID 53115)
-- Name: proforma_invoices_fees_installments uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.proforma_invoices_fees_installments ALTER COLUMN uid SET DEFAULT nextval('shulesoft.proforma_invoices_fees_installments_uid_seq'::regclass);


--
-- TOC entry 12192 (class 2604 OID 53116)
-- Name: proforma_payments id; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.proforma_payments ALTER COLUMN id SET DEFAULT nextval('shulesoft.proforma_payments_id_seq'::regclass);


--
-- TOC entry 12204 (class 2604 OID 53117)
-- Name: promotionsubject uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.promotionsubject ALTER COLUMN uid SET DEFAULT nextval('shulesoft.promotionsubject_uid_seq'::regclass);


--
-- TOC entry 12210 (class 2604 OID 53118)
-- Name: questions uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.questions ALTER COLUMN uid SET DEFAULT nextval('shulesoft.questions_uid_seq'::regclass);


--
-- TOC entry 12223 (class 2604 OID 53119)
-- Name: receipt_settings uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.receipt_settings ALTER COLUMN uid SET DEFAULT nextval('shulesoft.receipt_settings_uid_seq'::regclass);


--
-- TOC entry 12227 (class 2604 OID 53120)
-- Name: refer_character_grading_systems uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.refer_character_grading_systems ALTER COLUMN uid SET DEFAULT nextval('shulesoft.refer_character_grading_systems_uid_seq'::regclass);


--
-- TOC entry 12232 (class 2604 OID 53121)
-- Name: refer_exam uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.refer_exam ALTER COLUMN uid SET DEFAULT nextval('shulesoft.refer_exam_uid_seq'::regclass);


--
-- TOC entry 11596 (class 2604 OID 53122)
-- Name: refer_expense uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.refer_expense ALTER COLUMN uid SET DEFAULT nextval('shulesoft.refer_expense_uid_seq'::regclass);


--
-- TOC entry 12024 (class 2604 OID 53123)
-- Name: refer_subject uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.refer_subject ALTER COLUMN uid SET DEFAULT nextval('shulesoft.refer_subject_uid_seq'::regclass);


--
-- TOC entry 12238 (class 2604 OID 53124)
-- Name: reminders uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.reminders ALTER COLUMN uid SET DEFAULT nextval('shulesoft.reminders_uid_seq'::regclass);


--
-- TOC entry 12244 (class 2604 OID 53125)
-- Name: reply_msg uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.reply_msg ALTER COLUMN uid SET DEFAULT nextval('shulesoft.reply_msg_uid_seq'::regclass);


--
-- TOC entry 12249 (class 2604 OID 53126)
-- Name: reply_sms uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.reply_sms ALTER COLUMN uid SET DEFAULT nextval('shulesoft.reply_sms_uid_seq'::regclass);


--
-- TOC entry 12253 (class 2604 OID 53127)
-- Name: reset uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.reset ALTER COLUMN uid SET DEFAULT nextval('shulesoft.reset_uid_seq'::regclass);


--
-- TOC entry 12254 (class 2604 OID 53128)
-- Name: revenue id; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.revenue ALTER COLUMN id SET DEFAULT nextval('shulesoft.revenue_id_seq'::regclass);


--
-- TOC entry 12255 (class 2604 OID 53129)
-- Name: revenue uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.revenue ALTER COLUMN uid SET DEFAULT nextval('shulesoft.revenue_uid_seq'::regclass);


--
-- TOC entry 12264 (class 2604 OID 53130)
-- Name: revenue_cart uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.revenue_cart ALTER COLUMN uid SET DEFAULT nextval('shulesoft.revenue_cart_uid_seq'::regclass);


--
-- TOC entry 11473 (class 2604 OID 53131)
-- Name: revenues uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.revenues ALTER COLUMN uid SET DEFAULT nextval('shulesoft.revenues_uid_seq'::regclass);


--
-- TOC entry 11938 (class 2604 OID 53132)
-- Name: role uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.role ALTER COLUMN uid SET DEFAULT nextval('shulesoft.role_uid_seq'::regclass);


--
-- TOC entry 12268 (class 2604 OID 53133)
-- Name: role_permission uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.role_permission ALTER COLUMN uid SET DEFAULT nextval('shulesoft.role_permission_uid_seq'::regclass);


--
-- TOC entry 12271 (class 2604 OID 53134)
-- Name: route_vehicle uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.route_vehicle ALTER COLUMN uid SET DEFAULT nextval('shulesoft.route_vehicle_uid_seq'::regclass);


--
-- TOC entry 12275 (class 2604 OID 53135)
-- Name: routine uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.routine ALTER COLUMN uid SET DEFAULT nextval('shulesoft.routine_uid_seq'::regclass);


--
-- TOC entry 12279 (class 2604 OID 53136)
-- Name: routine_daily uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.routine_daily ALTER COLUMN uid SET DEFAULT nextval('shulesoft.routine_daily_uid_seq'::regclass);


--
-- TOC entry 11344 (class 2604 OID 53137)
-- Name: salaries uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.salaries ALTER COLUMN uid SET DEFAULT nextval('shulesoft.salaries_uid_seq'::regclass);


--
-- TOC entry 12283 (class 2604 OID 53138)
-- Name: salary_allowances uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.salary_allowances ALTER COLUMN uid SET DEFAULT nextval('shulesoft.salary_allowances_uid_seq'::regclass);


--
-- TOC entry 12287 (class 2604 OID 53139)
-- Name: salary_deductions uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.salary_deductions ALTER COLUMN uid SET DEFAULT nextval('shulesoft.salary_deductions_uid_seq'::regclass);


--
-- TOC entry 12291 (class 2604 OID 53140)
-- Name: salary_pensions uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.salary_pensions ALTER COLUMN uid SET DEFAULT nextval('shulesoft.salary_pensions_uid_seq'::regclass);


--
-- TOC entry 11348 (class 2604 OID 53141)
-- Name: sattendances uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.sattendances ALTER COLUMN uid SET DEFAULT nextval('shulesoft.sattendances_uid_seq'::regclass);


--
-- TOC entry 11543 (class 2604 OID 53142)
-- Name: section uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.section ALTER COLUMN uid SET DEFAULT nextval('shulesoft.section_uid_seq'::regclass);


--
-- TOC entry 12295 (class 2604 OID 53143)
-- Name: section_subject_teacher uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.section_subject_teacher ALTER COLUMN uid SET DEFAULT nextval('shulesoft.section_subject_teacher_uid_seq'::regclass);


--
-- TOC entry 12299 (class 2604 OID 53144)
-- Name: semester uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.semester ALTER COLUMN uid SET DEFAULT nextval('shulesoft.semester_uid_seq'::regclass);


--
-- TOC entry 11382 (class 2604 OID 53145)
-- Name: setting uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.setting ALTER COLUMN uid SET DEFAULT nextval('shulesoft.setting_uid_seq'::regclass);


--
-- TOC entry 12300 (class 2604 OID 53146)
-- Name: slots id; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.slots ALTER COLUMN id SET DEFAULT nextval('shulesoft.slots_id_seq'::regclass);


--
-- TOC entry 11407 (class 2604 OID 53147)
-- Name: sms uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.sms ALTER COLUMN uid SET DEFAULT nextval('shulesoft.sms_uid_seq'::regclass);


--
-- TOC entry 12306 (class 2604 OID 53148)
-- Name: sms_content uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.sms_content ALTER COLUMN uid SET DEFAULT nextval('shulesoft.sms_content_uid_seq'::regclass);


--
-- TOC entry 12310 (class 2604 OID 53149)
-- Name: sms_content_channels uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.sms_content_channels ALTER COLUMN uid SET DEFAULT nextval('shulesoft.sms_content_channels_uid_seq'::regclass);


--
-- TOC entry 12314 (class 2604 OID 53150)
-- Name: sms_files uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.sms_files ALTER COLUMN uid SET DEFAULT nextval('shulesoft.sms_files_uid_seq'::regclass);


--
-- TOC entry 12318 (class 2604 OID 53151)
-- Name: sms_keys uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.sms_keys ALTER COLUMN uid SET DEFAULT nextval('shulesoft.sms_keys_uid_seq'::regclass);


--
-- TOC entry 12324 (class 2604 OID 53152)
-- Name: smssettings uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.smssettings ALTER COLUMN uid SET DEFAULT nextval('shulesoft.smssettings_uid_seq'::regclass);


--
-- TOC entry 12329 (class 2604 OID 53153)
-- Name: special_grade_names uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.special_grade_names ALTER COLUMN uid SET DEFAULT nextval('shulesoft.special_grade_names_uid_seq'::regclass);


--
-- TOC entry 12333 (class 2604 OID 53154)
-- Name: special_grades uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.special_grades ALTER COLUMN uid SET DEFAULT nextval('shulesoft.special_grades_uid_seq'::regclass);


--
-- TOC entry 12337 (class 2604 OID 53155)
-- Name: special_promotion uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.special_promotion ALTER COLUMN uid SET DEFAULT nextval('shulesoft.special_promotion_uid_seq'::regclass);


--
-- TOC entry 12341 (class 2604 OID 53156)
-- Name: sponsors uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.sponsors ALTER COLUMN uid SET DEFAULT nextval('shulesoft.sponsors_uid_seq'::regclass);


--
-- TOC entry 12347 (class 2604 OID 53157)
-- Name: staff_leave uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.staff_leave ALTER COLUMN uid SET DEFAULT nextval('shulesoft.staff_leave_uid_seq'::regclass);


--
-- TOC entry 12352 (class 2604 OID 53158)
-- Name: staff_report uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.staff_report ALTER COLUMN uid SET DEFAULT nextval('shulesoft.staff_report_uid_seq'::regclass);


--
-- TOC entry 12353 (class 2604 OID 53159)
-- Name: staff_targets id; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.staff_targets ALTER COLUMN id SET DEFAULT nextval('shulesoft.staff_targets_id_seq'::regclass);


--
-- TOC entry 12354 (class 2604 OID 53160)
-- Name: staff_targets uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.staff_targets ALTER COLUMN uid SET DEFAULT nextval('shulesoft.staff_targets_uid_seq'::regclass);


--
-- TOC entry 12358 (class 2604 OID 53161)
-- Name: staff_targets_reports id; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.staff_targets_reports ALTER COLUMN id SET DEFAULT nextval('shulesoft.staff_targets_reports_id_seq'::regclass);


--
-- TOC entry 12359 (class 2604 OID 53162)
-- Name: staff_targets_reports uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.staff_targets_reports ALTER COLUMN uid SET DEFAULT nextval('shulesoft.staff_targets_reports_uid_seq'::regclass);


--
-- TOC entry 11419 (class 2604 OID 53163)
-- Name: student uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student ALTER COLUMN uid SET DEFAULT nextval('shulesoft.student_uid_seq'::regclass);


--
-- TOC entry 12368 (class 2604 OID 53164)
-- Name: student_addresses uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student_addresses ALTER COLUMN uid SET DEFAULT nextval('shulesoft.student_addresses_uid_seq'::regclass);


--
-- TOC entry 11530 (class 2604 OID 53165)
-- Name: student_archive uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student_archive ALTER COLUMN uid SET DEFAULT nextval('shulesoft.student_archive_uid_seq'::regclass);


--
-- TOC entry 12376 (class 2604 OID 53166)
-- Name: student_assessment uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student_assessment ALTER COLUMN uid SET DEFAULT nextval('shulesoft.student_assessment_uid_seq'::regclass);


--
-- TOC entry 12380 (class 2604 OID 53167)
-- Name: student_characters uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student_characters ALTER COLUMN uid SET DEFAULT nextval('shulesoft.student_characters_uid_seq'::regclass);


--
-- TOC entry 12384 (class 2604 OID 53168)
-- Name: student_due_date uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student_due_date ALTER COLUMN uid SET DEFAULT nextval('shulesoft.student_due_date_uid_seq'::regclass);


--
-- TOC entry 12387 (class 2604 OID 53169)
-- Name: student_duties uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student_duties ALTER COLUMN uid SET DEFAULT nextval('shulesoft.student_duties_uid_seq'::regclass);


--
-- TOC entry 12392 (class 2604 OID 53170)
-- Name: student_fee_subscription uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student_fee_subscription ALTER COLUMN uid SET DEFAULT nextval('shulesoft.student_fee_subscription_uid_seq'::regclass);


--
-- TOC entry 12372 (class 2604 OID 53171)
-- Name: student_fees_installments_unsubscriptions uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student_fees_installments_unsubscriptions ALTER COLUMN uid SET DEFAULT nextval('shulesoft.student_fees_installments_unsubscriptions_uid_seq'::regclass);


--
-- TOC entry 12397 (class 2604 OID 53172)
-- Name: student_installment_packages id; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student_installment_packages ALTER COLUMN id SET DEFAULT nextval('shulesoft.student_installment_packages_id_seq'::regclass);


--
-- TOC entry 12402 (class 2604 OID 53173)
-- Name: student_other uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student_other ALTER COLUMN uid SET DEFAULT nextval('shulesoft.student_other_uid_seq'::regclass);


--
-- TOC entry 11904 (class 2604 OID 53174)
-- Name: student_parents uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student_parents ALTER COLUMN uid SET DEFAULT nextval('shulesoft.student_parents_uid_seq'::regclass);


--
-- TOC entry 12408 (class 2604 OID 53175)
-- Name: student_reams uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student_reams ALTER COLUMN uid SET DEFAULT nextval('shulesoft.student_reams_uid_seq'::regclass);


--
-- TOC entry 12414 (class 2604 OID 53176)
-- Name: student_report uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student_report ALTER COLUMN uid SET DEFAULT nextval('shulesoft.student_report_uid_seq'::regclass);


--
-- TOC entry 12419 (class 2604 OID 53177)
-- Name: student_sponsors uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student_sponsors ALTER COLUMN uid SET DEFAULT nextval('shulesoft.student_sponsors_uid_seq'::regclass);


--
-- TOC entry 12423 (class 2604 OID 53178)
-- Name: student_status uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student_status ALTER COLUMN uid SET DEFAULT nextval('shulesoft.student_status_uid_seq'::regclass);


--
-- TOC entry 12035 (class 2604 OID 53179)
-- Name: subject uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.subject ALTER COLUMN uid SET DEFAULT nextval('shulesoft.subject_uid_seq'::regclass);


--
-- TOC entry 12427 (class 2604 OID 53180)
-- Name: subject_mark uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.subject_mark ALTER COLUMN uid SET DEFAULT nextval('shulesoft.subject_mark_uid_seq'::regclass);


--
-- TOC entry 11666 (class 2604 OID 53181)
-- Name: subject_section uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.subject_section ALTER COLUMN uid SET DEFAULT nextval('shulesoft.subject_section_uid_seq'::regclass);


--
-- TOC entry 11670 (class 2604 OID 53182)
-- Name: subject_student uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.subject_student ALTER COLUMN uid SET DEFAULT nextval('shulesoft.subject_student_uid_seq'::regclass);


--
-- TOC entry 12432 (class 2604 OID 53183)
-- Name: subject_topic uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.subject_topic ALTER COLUMN uid SET DEFAULT nextval('shulesoft.subject_topic_uid_seq'::regclass);


--
-- TOC entry 12437 (class 2604 OID 53184)
-- Name: submit_files uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.submit_files ALTER COLUMN uid SET DEFAULT nextval('shulesoft.submit_files_uid_seq'::regclass);


--
-- TOC entry 12441 (class 2604 OID 53185)
-- Name: syllabus_benchmarks uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.syllabus_benchmarks ALTER COLUMN uid SET DEFAULT nextval('shulesoft.syllabus_benchmarks_uid_seq'::regclass);


--
-- TOC entry 12445 (class 2604 OID 53186)
-- Name: syllabus_objective_references uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.syllabus_objective_references ALTER COLUMN uid SET DEFAULT nextval('shulesoft.syllabus_objective_references_uid_seq'::regclass);


--
-- TOC entry 12449 (class 2604 OID 53187)
-- Name: syllabus_objectives uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.syllabus_objectives ALTER COLUMN uid SET DEFAULT nextval('shulesoft.syllabus_objectives_uid_seq'::regclass);


--
-- TOC entry 12453 (class 2604 OID 53188)
-- Name: syllabus_student_benchmarking uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.syllabus_student_benchmarking ALTER COLUMN uid SET DEFAULT nextval('shulesoft.syllabus_student_benchmarking_uid_seq'::regclass);


--
-- TOC entry 12457 (class 2604 OID 53189)
-- Name: syllabus_subtopics uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.syllabus_subtopics ALTER COLUMN uid SET DEFAULT nextval('shulesoft.syllabus_subtopics_uid_seq'::regclass);


--
-- TOC entry 12461 (class 2604 OID 53190)
-- Name: syllabus_subtopics_teachers uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.syllabus_subtopics_teachers ALTER COLUMN uid SET DEFAULT nextval('shulesoft.syllabus_subtopics_teachers_uid_seq'::regclass);


--
-- TOC entry 12085 (class 2604 OID 53191)
-- Name: syllabus_topics uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.syllabus_topics ALTER COLUMN uid SET DEFAULT nextval('shulesoft.syllabus_topics_uid_seq'::regclass);


--
-- TOC entry 12466 (class 2604 OID 53192)
-- Name: tattendance uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.tattendance ALTER COLUMN uid SET DEFAULT nextval('shulesoft.tattendance_uid_seq'::regclass);


--
-- TOC entry 12467 (class 2604 OID 53193)
-- Name: tattendances id; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.tattendances ALTER COLUMN id SET DEFAULT nextval('shulesoft.tattendances_id_seq'::regclass);


--
-- TOC entry 12468 (class 2604 OID 53194)
-- Name: tattendances uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.tattendances ALTER COLUMN uid SET DEFAULT nextval('shulesoft.tattendances_uid_seq'::regclass);


--
-- TOC entry 11430 (class 2604 OID 53195)
-- Name: teacher uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.teacher ALTER COLUMN uid SET DEFAULT nextval('shulesoft.teacher_uid_seq'::regclass);


--
-- TOC entry 11436 (class 2604 OID 53196)
-- Name: teacher_duties uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.teacher_duties ALTER COLUMN uid SET DEFAULT nextval('shulesoft.teacher_duties_uid_seq'::regclass);


--
-- TOC entry 12476 (class 2604 OID 53197)
-- Name: tempfiles uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.tempfiles ALTER COLUMN uid SET DEFAULT nextval('shulesoft.tempfiles_uid_seq'::regclass);


--
-- TOC entry 12477 (class 2604 OID 53198)
-- Name: timetables id; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.timetables ALTER COLUMN id SET DEFAULT nextval('shulesoft.timetables_id_seq'::regclass);


--
-- TOC entry 11440 (class 2604 OID 53199)
-- Name: tmembers uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.tmembers ALTER COLUMN uid SET DEFAULT nextval('shulesoft.tmembers_uid_seq'::regclass);


--
-- TOC entry 12487 (class 2604 OID 53200)
-- Name: topic_mark uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.topic_mark ALTER COLUMN uid SET DEFAULT nextval('shulesoft.topic_mark_uid_seq'::regclass);


--
-- TOC entry 12492 (class 2604 OID 53201)
-- Name: tour_users uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.tour_users ALTER COLUMN uid SET DEFAULT nextval('shulesoft.tour_users_uid_seq'::regclass);


--
-- TOC entry 12497 (class 2604 OID 53202)
-- Name: tours uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.tours ALTER COLUMN uid SET DEFAULT nextval('shulesoft.tours_uid_seq'::regclass);


--
-- TOC entry 12502 (class 2604 OID 53203)
-- Name: track uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.track ALTER COLUMN uid SET DEFAULT nextval('shulesoft.track_uid_seq'::regclass);


--
-- TOC entry 12506 (class 2604 OID 53204)
-- Name: track_invoices uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.track_invoices ALTER COLUMN uid SET DEFAULT nextval('shulesoft.track_invoices_uid_seq'::regclass);


--
-- TOC entry 12511 (class 2604 OID 53205)
-- Name: track_invoices_fees_installments uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.track_invoices_fees_installments ALTER COLUMN uid SET DEFAULT nextval('shulesoft.track_invoices_fees_installments_uid_seq'::regclass);


--
-- TOC entry 12520 (class 2604 OID 53206)
-- Name: trainings uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.trainings ALTER COLUMN uid SET DEFAULT nextval('shulesoft.trainings_uid_seq'::regclass);


--
-- TOC entry 12524 (class 2604 OID 53207)
-- Name: transport uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.transport ALTER COLUMN uid SET DEFAULT nextval('shulesoft.transport_uid_seq'::regclass);


--
-- TOC entry 12530 (class 2604 OID 53208)
-- Name: transport_installment uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.transport_installment ALTER COLUMN uid SET DEFAULT nextval('shulesoft.transport_installment_uid_seq'::regclass);


--
-- TOC entry 11547 (class 2604 OID 53209)
-- Name: transport_routes uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.transport_routes ALTER COLUMN uid SET DEFAULT nextval('shulesoft.transport_routes_uid_seq'::regclass);


--
-- TOC entry 11551 (class 2604 OID 53210)
-- Name: transport_routes_fees_installments uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.transport_routes_fees_installments ALTER COLUMN uid SET DEFAULT nextval('shulesoft.transport_routes_fees_installments_uid_seq'::regclass);


--
-- TOC entry 12535 (class 2604 OID 53211)
-- Name: uattendances uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.uattendances ALTER COLUMN uid SET DEFAULT nextval('shulesoft.uattendances_uid_seq'::regclass);


--
-- TOC entry 11482 (class 2604 OID 53212)
-- Name: user uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft."user" ALTER COLUMN uid SET DEFAULT nextval('shulesoft.user_uid_seq'::regclass);


--
-- TOC entry 12540 (class 2604 OID 53213)
-- Name: user_allowances uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.user_allowances ALTER COLUMN uid SET DEFAULT nextval('shulesoft.user_allowances_uid_seq'::regclass);


--
-- TOC entry 12544 (class 2604 OID 53214)
-- Name: user_contract uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.user_contract ALTER COLUMN uid SET DEFAULT nextval('shulesoft.user_contract_uid_seq'::regclass);


--
-- TOC entry 12548 (class 2604 OID 53215)
-- Name: user_deductions uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.user_deductions ALTER COLUMN uid SET DEFAULT nextval('shulesoft.user_deductions_uid_seq'::regclass);


--
-- TOC entry 12549 (class 2604 OID 53216)
-- Name: user_devices id; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.user_devices ALTER COLUMN id SET DEFAULT nextval('shulesoft.user_devices_id_seq'::regclass);


--
-- TOC entry 12550 (class 2604 OID 53217)
-- Name: user_devices uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.user_devices ALTER COLUMN uid SET DEFAULT nextval('shulesoft.user_devices_uid_seq'::regclass);


--
-- TOC entry 12556 (class 2604 OID 53218)
-- Name: user_pensions uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.user_pensions ALTER COLUMN uid SET DEFAULT nextval('shulesoft.user_pensions_uid_seq'::regclass);


--
-- TOC entry 12560 (class 2604 OID 53219)
-- Name: user_phones uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.user_phones ALTER COLUMN uid SET DEFAULT nextval('shulesoft.user_phones_uid_seq'::regclass);


--
-- TOC entry 12564 (class 2604 OID 53220)
-- Name: user_reminders uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.user_reminders ALTER COLUMN uid SET DEFAULT nextval('shulesoft.user_reminders_uid_seq'::regclass);


--
-- TOC entry 12568 (class 2604 OID 53221)
-- Name: user_role uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.user_role ALTER COLUMN uid SET DEFAULT nextval('shulesoft.user_role_uid_seq'::regclass);


--
-- TOC entry 12574 (class 2604 OID 53222)
-- Name: user_updates uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.user_updates ALTER COLUMN uid SET DEFAULT nextval('shulesoft.user_updates_uid_seq'::regclass);


--
-- TOC entry 12579 (class 2604 OID 53223)
-- Name: valid_answers uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.valid_answers ALTER COLUMN uid SET DEFAULT nextval('shulesoft.valid_answers_uid_seq'::regclass);


--
-- TOC entry 12396 (class 2604 OID 53224)
-- Name: vehicles uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.vehicles ALTER COLUMN uid SET DEFAULT nextval('shulesoft.vehicles_uid_seq'::regclass);


--
-- TOC entry 12583 (class 2604 OID 53225)
-- Name: vendors uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.vendors ALTER COLUMN uid SET DEFAULT nextval('shulesoft.vendors_uid_seq'::regclass);


--
-- TOC entry 12587 (class 2604 OID 53226)
-- Name: wallet_cart uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.wallet_cart ALTER COLUMN uid SET DEFAULT nextval('shulesoft.wallet_cart_uid_seq'::regclass);


--
-- TOC entry 12592 (class 2604 OID 53227)
-- Name: wallet_uses uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.wallet_uses ALTER COLUMN uid SET DEFAULT nextval('shulesoft.wallet_uses_uid_seq'::regclass);


--
-- TOC entry 12596 (class 2604 OID 53228)
-- Name: wallets uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.wallets ALTER COLUMN uid SET DEFAULT nextval('shulesoft.wallets_uid_seq'::regclass);


--
-- TOC entry 12171 (class 2604 OID 53229)
-- Name: warehouse_transfers uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.warehouse_transfers ALTER COLUMN uid SET DEFAULT nextval('shulesoft.warehouse_transfers_uid_seq'::regclass);


--
-- TOC entry 12601 (class 2604 OID 53230)
-- Name: warehouses uid; Type: DEFAULT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.warehouses ALTER COLUMN uid SET DEFAULT nextval('shulesoft.warehouses_uid_seq'::regclass);


--
-- TOC entry 12610 (class 2606 OID 53232)
-- Name: academic_year academic_year_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.academic_year
    ADD CONSTRAINT academic_year_id_primary PRIMARY KEY (id);


--
-- TOC entry 12715 (class 2606 OID 53234)
-- Name: account_groups account_groups_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.account_groups
    ADD CONSTRAINT account_groups_id_primary PRIMARY KEY (id);


--
-- TOC entry 12613 (class 2606 OID 53236)
-- Name: admissions admissions_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.admissions
    ADD CONSTRAINT admissions_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12719 (class 2606 OID 53238)
-- Name: advance_payments advance_payments_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.advance_payments
    ADD CONSTRAINT advance_payments_id_primary PRIMARY KEY (id);


--
-- TOC entry 12724 (class 2606 OID 53240)
-- Name: advance_payments_invoices_fees_installments advance_payments_invoices_fees_installments_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.advance_payments_invoices_fees_installments
    ADD CONSTRAINT advance_payments_invoices_fees_installments_id_primary PRIMARY KEY (id);


--
-- TOC entry 12742 (class 2606 OID 53242)
-- Name: allowances allowances_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.allowances
    ADD CONSTRAINT allowances_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12810 (class 2606 OID 53244)
-- Name: application application_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.application
    ADD CONSTRAINT application_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12813 (class 2606 OID 53246)
-- Name: appointments appointments_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.appointments
    ADD CONSTRAINT appointments_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12816 (class 2606 OID 53248)
-- Name: assignment_downloads assignment_downloads_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.assignment_downloads
    ADD CONSTRAINT assignment_downloads_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12819 (class 2606 OID 53250)
-- Name: assignment_files assignment_files_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.assignment_files
    ADD CONSTRAINT assignment_files_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12822 (class 2606 OID 53252)
-- Name: assignment_viewers assignment_viewers_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.assignment_viewers
    ADD CONSTRAINT assignment_viewers_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12825 (class 2606 OID 53254)
-- Name: assignments assignments_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.assignments
    ADD CONSTRAINT assignments_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12828 (class 2606 OID 53256)
-- Name: assignments_submitted assignments_submitted_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.assignments_submitted
    ADD CONSTRAINT assignments_submitted_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12831 (class 2606 OID 53258)
-- Name: attendance attendance_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.attendance
    ADD CONSTRAINT attendance_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12834 (class 2606 OID 53260)
-- Name: bank_accounts_fees_classes bank_accounts_fees_classes_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.bank_accounts_fees_classes
    ADD CONSTRAINT bank_accounts_fees_classes_id_primary PRIMARY KEY (id);


--
-- TOC entry 12615 (class 2606 OID 53262)
-- Name: bank_accounts bank_accounts_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.bank_accounts
    ADD CONSTRAINT bank_accounts_id_primary PRIMARY KEY (id);


--
-- TOC entry 12618 (class 2606 OID 53264)
-- Name: bank_accounts_integrations bank_accounts_integrations_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.bank_accounts_integrations
    ADD CONSTRAINT bank_accounts_integrations_id_primary PRIMARY KEY (id);


--
-- TOC entry 12847 (class 2606 OID 53266)
-- Name: book_class book_class_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.book_class
    ADD CONSTRAINT book_class_id_primary PRIMARY KEY (id);


--
-- TOC entry 12844 (class 2606 OID 53268)
-- Name: book book_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.book
    ADD CONSTRAINT book_id_primary PRIMARY KEY (id);


--
-- TOC entry 12850 (class 2606 OID 53270)
-- Name: book_quantity book_quantity_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.book_quantity
    ADD CONSTRAINT book_quantity_id_primary PRIMARY KEY (id);


--
-- TOC entry 12857 (class 2606 OID 53272)
-- Name: budgets budget_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.budgets
    ADD CONSTRAINT budget_id_primary PRIMARY KEY (id);


--
-- TOC entry 12853 (class 2606 OID 53274)
-- Name: budget_item_period_amounts budget_item_period_amounts_pkey; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.budget_item_period_amounts
    ADD CONSTRAINT budget_item_period_amounts_pkey PRIMARY KEY (id);


--
-- TOC entry 12855 (class 2606 OID 53276)
-- Name: budget_items budget_items_pkey; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.budget_items
    ADD CONSTRAINT budget_items_pkey PRIMARY KEY (id);


--
-- TOC entry 12860 (class 2606 OID 53278)
-- Name: capital capital_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.capital
    ADD CONSTRAINT capital_id_primary PRIMARY KEY (id);


--
-- TOC entry 12863 (class 2606 OID 53280)
-- Name: car_tracker_key car_tracker_key_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.car_tracker_key
    ADD CONSTRAINT car_tracker_key_id_primary PRIMARY KEY (id);


--
-- TOC entry 12866 (class 2606 OID 53282)
-- Name: cash_requests cash_requests_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.cash_requests
    ADD CONSTRAINT cash_requests_id_primary PRIMARY KEY (id);


--
-- TOC entry 12869 (class 2606 OID 53284)
-- Name: certificate_setting certificate_setting_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.certificate_setting
    ADD CONSTRAINT certificate_setting_id_primary PRIMARY KEY (id);


--
-- TOC entry 12872 (class 2606 OID 53286)
-- Name: character_categories character_categories_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.character_categories
    ADD CONSTRAINT character_categories_id_primary PRIMARY KEY (id);


--
-- TOC entry 12874 (class 2606 OID 53288)
-- Name: character_classes character_classes_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.character_classes
    ADD CONSTRAINT character_classes_id_primary PRIMARY KEY (id);


--
-- TOC entry 12877 (class 2606 OID 53290)
-- Name: characters characters_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.characters
    ADD CONSTRAINT characters_id_primary PRIMARY KEY (id);


--
-- TOC entry 12880 (class 2606 OID 53292)
-- Name: class_exam class_exam_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.class_exam
    ADD CONSTRAINT class_exam_id_primary PRIMARY KEY (class_exam_id);


--
-- TOC entry 12621 (class 2606 OID 53294)
-- Name: classes classes_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.classes
    ADD CONSTRAINT classes_id_primary PRIMARY KEY ("classesID");


--
-- TOC entry 12624 (class 2606 OID 53296)
-- Name: classlevel classlevel_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.classlevel
    ADD CONSTRAINT classlevel_id_primary PRIMARY KEY (classlevel_id);


--
-- TOC entry 12626 (class 2606 OID 53298)
-- Name: classlevel classlevel_name_key; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.classlevel
    ADD CONSTRAINT classlevel_name_key UNIQUE (name, schema_name);


--
-- TOC entry 12883 (class 2606 OID 53300)
-- Name: closing_year_balance closing_year_balance_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.closing_year_balance
    ADD CONSTRAINT closing_year_balance_id_primary PRIMARY KEY (id);


--
-- TOC entry 12886 (class 2606 OID 53302)
-- Name: configurations configurations_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.configurations
    ADD CONSTRAINT configurations_id_primary PRIMARY KEY (id);


--
-- TOC entry 12904 (class 2606 OID 53304)
-- Name: current_assets current_asset_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.current_assets
    ADD CONSTRAINT current_asset_id_primary PRIMARY KEY (id);


--
-- TOC entry 12901 (class 2606 OID 53306)
-- Name: current_assets2 current_assets_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.current_assets2
    ADD CONSTRAINT current_assets_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12906 (class 2606 OID 53308)
-- Name: deductions deductions_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.deductions
    ADD CONSTRAINT deductions_id_primary PRIMARY KEY (id);


--
-- TOC entry 12629 (class 2606 OID 53310)
-- Name: diaries diaries_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.diaries
    ADD CONSTRAINT diaries_id_primary PRIMARY KEY (id);


--
-- TOC entry 12909 (class 2606 OID 53312)
-- Name: diary_comments diary_comments_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.diary_comments
    ADD CONSTRAINT diary_comments_id_primary PRIMARY KEY (id);


--
-- TOC entry 12746 (class 2606 OID 53314)
-- Name: discount_fees_installments discount_fees_installments_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.discount_fees_installments
    ADD CONSTRAINT discount_fees_installments_id_primary PRIMARY KEY (id);


--
-- TOC entry 12750 (class 2606 OID 53316)
-- Name: due_amounts due_amounts_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.due_amounts
    ADD CONSTRAINT due_amounts_id_primary PRIMARY KEY (id);


--
-- TOC entry 12912 (class 2606 OID 53318)
-- Name: due_amounts_payments due_amounts_payments_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.due_amounts_payments
    ADD CONSTRAINT due_amounts_payments_id_primary PRIMARY KEY (id);


--
-- TOC entry 12700 (class 2606 OID 53320)
-- Name: duties duties_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.duties
    ADD CONSTRAINT duties_id_primary PRIMARY KEY (id);


--
-- TOC entry 12915 (class 2606 OID 53322)
-- Name: duty_reports duty_reports_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.duty_reports
    ADD CONSTRAINT duty_reports_id_primary PRIMARY KEY (id);


--
-- TOC entry 12918 (class 2606 OID 53324)
-- Name: eattendance eattendance_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.eattendance
    ADD CONSTRAINT eattendance_id_primary PRIMARY KEY ("eattendanceID");


--
-- TOC entry 12921 (class 2606 OID 53326)
-- Name: email email_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.email
    ADD CONSTRAINT email_id_primary PRIMARY KEY (email_id);


--
-- TOC entry 12924 (class 2606 OID 53328)
-- Name: email_lists email_lists_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.email_lists
    ADD CONSTRAINT email_lists_id_primary PRIMARY KEY (id);


--
-- TOC entry 12930 (class 2606 OID 53330)
-- Name: exam_comments exam_comments_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.exam_comments
    ADD CONSTRAINT exam_comments_id_primary PRIMARY KEY (id);


--
-- TOC entry 12935 (class 2606 OID 53332)
-- Name: exam_groups exam_groups_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.exam_groups
    ADD CONSTRAINT exam_groups_id_primary PRIMARY KEY (id);


--
-- TOC entry 12927 (class 2606 OID 53334)
-- Name: exam exam_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.exam
    ADD CONSTRAINT exam_id_primary PRIMARY KEY ("examID");


--
-- TOC entry 12632 (class 2606 OID 53336)
-- Name: exam_report exam_report_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.exam_report
    ADD CONSTRAINT exam_report_id_primary PRIMARY KEY (id);


--
-- TOC entry 12938 (class 2606 OID 53338)
-- Name: exam_report_settings exam_report_settings_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.exam_report_settings
    ADD CONSTRAINT exam_report_settings_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12941 (class 2606 OID 53340)
-- Name: exam_special_cases exam_special_cases_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.exam_special_cases
    ADD CONSTRAINT exam_special_cases_id_primary PRIMARY KEY (id);


--
-- TOC entry 12946 (class 2606 OID 53342)
-- Name: exam_subject_mark exam_subject_mark_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.exam_subject_mark
    ADD CONSTRAINT exam_subject_mark_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12949 (class 2606 OID 53344)
-- Name: examschedule examschedule_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.examschedule
    ADD CONSTRAINT examschedule_id_primary PRIMARY KEY ("examscheduleID");


--
-- TOC entry 12952 (class 2606 OID 53346)
-- Name: exchange_rates exchange_rates_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.exchange_rates
    ADD CONSTRAINT exchange_rates_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12955 (class 2606 OID 53348)
-- Name: expense_cart expense_cart_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.expense_cart
    ADD CONSTRAINT expense_cart_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12635 (class 2606 OID 53350)
-- Name: expense expense_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.expense
    ADD CONSTRAINT expense_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12961 (class 2606 OID 53352)
-- Name: expenses expenses_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.expenses
    ADD CONSTRAINT expenses_id_primary PRIMARY KEY (id);


--
-- TOC entry 12964 (class 2606 OID 53354)
-- Name: feecat_class feecat_class_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.feecat_class
    ADD CONSTRAINT feecat_class_id_primary PRIMARY KEY ("feecat_classesID");


--
-- TOC entry 12967 (class 2606 OID 53356)
-- Name: fees_classes fees_classes_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.fees_classes
    ADD CONSTRAINT fees_classes_id_primary PRIMARY KEY (id);


--
-- TOC entry 12753 (class 2606 OID 53358)
-- Name: fees fees_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.fees
    ADD CONSTRAINT fees_id_primary PRIMARY KEY (id);


--
-- TOC entry 12762 (class 2606 OID 53360)
-- Name: fees_installments_classes fees_installments_classes_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.fees_installments_classes
    ADD CONSTRAINT fees_installments_classes_id_primary PRIMARY KEY (id);


--
-- TOC entry 12757 (class 2606 OID 53362)
-- Name: fees_installments fees_installments_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.fees_installments
    ADD CONSTRAINT fees_installments_id_primary PRIMARY KEY (id);


--
-- TOC entry 12970 (class 2606 OID 53364)
-- Name: file_folder file_folder_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.file_folder
    ADD CONSTRAINT file_folder_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12973 (class 2606 OID 53366)
-- Name: file_share file_share_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.file_share
    ADD CONSTRAINT file_share_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12976 (class 2606 OID 53368)
-- Name: files files_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.files
    ADD CONSTRAINT files_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12979 (class 2606 OID 53370)
-- Name: financial_year financial_year_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.financial_year
    ADD CONSTRAINT financial_year_id_primary PRIMARY KEY (id);


--
-- TOC entry 12981 (class 2606 OID 53372)
-- Name: financial_year financial_year_name_schema_name; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.financial_year
    ADD CONSTRAINT financial_year_name_schema_name UNIQUE (name, schema_name);


--
-- TOC entry 12987 (class 2606 OID 53374)
-- Name: fixed_assets fixed_assets_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.fixed_assets
    ADD CONSTRAINT fixed_assets_id_primary PRIMARY KEY (id);


--
-- TOC entry 12990 (class 2606 OID 53376)
-- Name: forum_answer_votes forum_answer_votes_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.forum_answer_votes
    ADD CONSTRAINT forum_answer_votes_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12993 (class 2606 OID 53378)
-- Name: forum_answers forum_answers_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.forum_answers
    ADD CONSTRAINT forum_answers_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12996 (class 2606 OID 53380)
-- Name: forum_categories forum_categories_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.forum_categories
    ADD CONSTRAINT forum_categories_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12999 (class 2606 OID 53382)
-- Name: forum_discussion forum_discussion_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.forum_discussion
    ADD CONSTRAINT forum_discussion_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13002 (class 2606 OID 53384)
-- Name: forum_post forum_post_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.forum_post
    ADD CONSTRAINT forum_post_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13005 (class 2606 OID 53386)
-- Name: forum_question_viewers forum_question_viewers_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.forum_question_viewers
    ADD CONSTRAINT forum_question_viewers_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13011 (class 2606 OID 53388)
-- Name: forum_questions_comments forum_questions_comments_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.forum_questions_comments
    ADD CONSTRAINT forum_questions_comments_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13008 (class 2606 OID 53390)
-- Name: forum_questions forum_questions_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.forum_questions
    ADD CONSTRAINT forum_questions_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13014 (class 2606 OID 53392)
-- Name: forum_questions_votes forum_questions_votes_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.forum_questions_votes
    ADD CONSTRAINT forum_questions_votes_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13017 (class 2606 OID 53394)
-- Name: forum_user_discussion forum_user_discussion_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.forum_user_discussion
    ADD CONSTRAINT forum_user_discussion_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12638 (class 2606 OID 53396)
-- Name: general_character_assessment general_character_assessment_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.general_character_assessment
    ADD CONSTRAINT general_character_assessment_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13020 (class 2606 OID 53398)
-- Name: grade grade_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.grade
    ADD CONSTRAINT grade_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13023 (class 2606 OID 53400)
-- Name: hattendances hattendances_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.hattendances
    ADD CONSTRAINT hattendances_id_primary PRIMARY KEY (id);


--
-- TOC entry 12646 (class 2606 OID 53402)
-- Name: hmembers hmembers_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.hmembers
    ADD CONSTRAINT hmembers_id_primary PRIMARY KEY (id);


--
-- TOC entry 13026 (class 2606 OID 53404)
-- Name: hostel_beds hostel_beds_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.hostel_beds
    ADD CONSTRAINT hostel_beds_id_primary PRIMARY KEY (id);


--
-- TOC entry 13029 (class 2606 OID 53406)
-- Name: hostel_category hostel_category_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.hostel_category
    ADD CONSTRAINT hostel_category_id_primary PRIMARY KEY (id);


--
-- TOC entry 12769 (class 2606 OID 53408)
-- Name: hostel_fees_installments hostel_fees_installments_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.hostel_fees_installments
    ADD CONSTRAINT hostel_fees_installments_id_primary PRIMARY KEY (id);


--
-- TOC entry 12772 (class 2606 OID 53410)
-- Name: hostel_fees_installments hostel_id_fees_installment_id_unique; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.hostel_fees_installments
    ADD CONSTRAINT hostel_id_fees_installment_id_unique UNIQUE (hostel_id, fees_installment_id);


--
-- TOC entry 12774 (class 2606 OID 53412)
-- Name: hostels hostels_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.hostels
    ADD CONSTRAINT hostels_id_primary PRIMARY KEY (id);


--
-- TOC entry 13032 (class 2606 OID 53414)
-- Name: id_cards id_cards_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.id_cards
    ADD CONSTRAINT id_cards_id_primary PRIMARY KEY (id);


--
-- TOC entry 13035 (class 2606 OID 53416)
-- Name: installment_packages installment_packages_pkey; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.installment_packages
    ADD CONSTRAINT installment_packages_pkey PRIMARY KEY (id);


--
-- TOC entry 12778 (class 2606 OID 53418)
-- Name: installments installments_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.installments
    ADD CONSTRAINT installments_id_primary PRIMARY KEY (id);


--
-- TOC entry 13041 (class 2606 OID 53420)
-- Name: invoice_prefix invoice_prefix_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.invoice_prefix
    ADD CONSTRAINT invoice_prefix_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13044 (class 2606 OID 53422)
-- Name: invoice_settings invoice_settings_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.invoice_settings
    ADD CONSTRAINT invoice_settings_id_primary PRIMARY KEY (id);


--
-- TOC entry 12789 (class 2606 OID 53424)
-- Name: invoices_fees_installments invoices_fees_installments_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.invoices_fees_installments
    ADD CONSTRAINT invoices_fees_installments_id_primary PRIMARY KEY (id);


--
-- TOC entry 12654 (class 2606 OID 53426)
-- Name: invoices invoices_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.invoices
    ADD CONSTRAINT invoices_id_primary PRIMARY KEY (id);


--
-- TOC entry 13049 (class 2606 OID 53428)
-- Name: issue issue_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.issue
    ADD CONSTRAINT issue_id_primary PRIMARY KEY (id);


--
-- TOC entry 13053 (class 2606 OID 53430)
-- Name: issue_inventory issue_inventory_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.issue_inventory
    ADD CONSTRAINT issue_inventory_id_primary PRIMARY KEY (id);


--
-- TOC entry 13064 (class 2606 OID 53432)
-- Name: items items_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.items
    ADD CONSTRAINT items_id_primary PRIMARY KEY (id);


--
-- TOC entry 13067 (class 2606 OID 53434)
-- Name: journal_group journal_group_pkey; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.journal_group
    ADD CONSTRAINT journal_group_pkey PRIMARY KEY (id);


--
-- TOC entry 13071 (class 2606 OID 53436)
-- Name: journals journal_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.journals
    ADD CONSTRAINT journal_id_primary PRIMARY KEY (id);


--
-- TOC entry 13073 (class 2606 OID 53438)
-- Name: journals journal_uuid_unique; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.journals
    ADD CONSTRAINT journal_uuid_unique UNIQUE (uuid);


--
-- TOC entry 13077 (class 2606 OID 53440)
-- Name: lesson_plan lesson_plan_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.lesson_plan
    ADD CONSTRAINT lesson_plan_id_primary PRIMARY KEY (id);


--
-- TOC entry 13081 (class 2606 OID 53442)
-- Name: liabilities liabilities_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.liabilities
    ADD CONSTRAINT liabilities_id_primary PRIMARY KEY (id);


--
-- TOC entry 13084 (class 2606 OID 53444)
-- Name: livestudy_packages livestudy_packages_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.livestudy_packages
    ADD CONSTRAINT livestudy_packages_id_primary PRIMARY KEY (id);


--
-- TOC entry 13087 (class 2606 OID 53446)
-- Name: livestudy_payments livestudy_payments_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.livestudy_payments
    ADD CONSTRAINT livestudy_payments_id_primary PRIMARY KEY (id);


--
-- TOC entry 13090 (class 2606 OID 53448)
-- Name: lmember lmember_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.lmember
    ADD CONSTRAINT lmember_id_primary PRIMARY KEY (id);


--
-- TOC entry 13093 (class 2606 OID 53450)
-- Name: loan_applications loan_applications_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.loan_applications
    ADD CONSTRAINT loan_applications_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13096 (class 2606 OID 53452)
-- Name: loan_payments loan_payments_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.loan_payments
    ADD CONSTRAINT loan_payments_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13099 (class 2606 OID 53454)
-- Name: loan_types loan_types_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.loan_types
    ADD CONSTRAINT loan_types_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13102 (class 2606 OID 53456)
-- Name: log log_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.log
    ADD CONSTRAINT log_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13105 (class 2606 OID 53458)
-- Name: login_attempts login_attempts_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.login_attempts
    ADD CONSTRAINT login_attempts_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12657 (class 2606 OID 53460)
-- Name: login_locations login_locations_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.login_locations
    ADD CONSTRAINT login_locations_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13108 (class 2606 OID 53462)
-- Name: mailandsms mailandsms_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.mailandsms
    ADD CONSTRAINT mailandsms_id_primary PRIMARY KEY ("mailandsmsID");


--
-- TOC entry 13111 (class 2606 OID 53464)
-- Name: mailandsmstemplate mailandsmstemplate_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.mailandsmstemplate
    ADD CONSTRAINT mailandsmstemplate_id_primary PRIMARY KEY (id);


--
-- TOC entry 13114 (class 2606 OID 53466)
-- Name: mailandsmstemplatetag mailandsmstemplatetag_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.mailandsmstemplatetag
    ADD CONSTRAINT mailandsmstemplatetag_id_primary PRIMARY KEY ("mailandsmstemplatetagID");


--
-- TOC entry 13117 (class 2606 OID 53468)
-- Name: major_subjects major_subjects_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.major_subjects
    ADD CONSTRAINT major_subjects_id_primary PRIMARY KEY (id);


--
-- TOC entry 13120 (class 2606 OID 53470)
-- Name: manage_budgets manage_budget_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.manage_budgets
    ADD CONSTRAINT manage_budget_id_primary PRIMARY KEY (id);


--
-- TOC entry 12665 (class 2606 OID 53472)
-- Name: mark mark_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.mark
    ADD CONSTRAINT mark_id_primary PRIMARY KEY ("markID");


--
-- TOC entry 13134 (class 2606 OID 53474)
-- Name: media_categories media_categories_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.media_categories
    ADD CONSTRAINT media_categories_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13137 (class 2606 OID 53476)
-- Name: media_category media_category_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.media_category
    ADD CONSTRAINT media_category_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13140 (class 2606 OID 53478)
-- Name: media_comment_reply media_comment_reply_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.media_comment_reply
    ADD CONSTRAINT media_comment_reply_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13143 (class 2606 OID 53480)
-- Name: media_comments media_comments_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.media_comments
    ADD CONSTRAINT media_comments_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13131 (class 2606 OID 53482)
-- Name: media media_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.media
    ADD CONSTRAINT media_id_primary PRIMARY KEY ("mediaID");


--
-- TOC entry 13146 (class 2606 OID 53484)
-- Name: media_likes media_likes_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.media_likes
    ADD CONSTRAINT media_likes_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13152 (class 2606 OID 53486)
-- Name: media_live_comments media_live_comments_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.media_live_comments
    ADD CONSTRAINT media_live_comments_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13149 (class 2606 OID 53488)
-- Name: media_live media_live_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.media_live
    ADD CONSTRAINT media_live_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13155 (class 2606 OID 53490)
-- Name: media_share media_share_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.media_share
    ADD CONSTRAINT media_share_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13163 (class 2606 OID 53492)
-- Name: media_viewers media_viewers_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.media_viewers
    ADD CONSTRAINT media_viewers_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13158 (class 2606 OID 53494)
-- Name: medias medias_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.medias
    ADD CONSTRAINT medias_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13169 (class 2606 OID 53496)
-- Name: message_channels message_channels_pkey; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.message_channels
    ADD CONSTRAINT message_channels_pkey PRIMARY KEY (id);


--
-- TOC entry 13166 (class 2606 OID 53498)
-- Name: message message_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.message
    ADD CONSTRAINT message_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13171 (class 2606 OID 53500)
-- Name: minor_exam_marks minor_exam_marks_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.minor_exam_marks
    ADD CONSTRAINT minor_exam_marks_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13174 (class 2606 OID 53502)
-- Name: minor_exams minor_exams_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.minor_exams
    ADD CONSTRAINT minor_exams_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13177 (class 2606 OID 53504)
-- Name: necta necta_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.necta
    ADD CONSTRAINT necta_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13180 (class 2606 OID 53506)
-- Name: news_board news_board_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.news_board
    ADD CONSTRAINT news_board_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12668 (class 2606 OID 53508)
-- Name: notice notice_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.notice
    ADD CONSTRAINT notice_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13183 (class 2606 OID 53510)
-- Name: page_tips_viewers page_tips_viewers_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.page_tips_viewers
    ADD CONSTRAINT page_tips_viewers_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13186 (class 2606 OID 53512)
-- Name: parent_documents parent_documents_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.parent_documents
    ADD CONSTRAINT parent_documents_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12671 (class 2606 OID 53514)
-- Name: parent parent_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.parent
    ADD CONSTRAINT parent_id_primary PRIMARY KEY ("parentID");


--
-- TOC entry 13189 (class 2606 OID 53516)
-- Name: parent_phones parent_phones_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.parent_phones
    ADD CONSTRAINT parent_phones_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12729 (class 2606 OID 53518)
-- Name: payment_types payment_types_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.payment_types
    ADD CONSTRAINT payment_types_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12732 (class 2606 OID 53520)
-- Name: payments payments_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.payments
    ADD CONSTRAINT payments_id_primary PRIMARY KEY (id);


--
-- TOC entry 12792 (class 2606 OID 53522)
-- Name: payments_invoices_fees_installments payments_invoices_fees_installments_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.payments_invoices_fees_installments
    ADD CONSTRAINT payments_invoices_fees_installments_id_primary PRIMARY KEY (id);


--
-- TOC entry 13192 (class 2606 OID 53524)
-- Name: payroll_setting payroll_setting_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.payroll_setting
    ADD CONSTRAINT payroll_setting_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13195 (class 2606 OID 53526)
-- Name: payslip_settings payslip_settings_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.payslip_settings
    ADD CONSTRAINT payslip_settings_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13198 (class 2606 OID 53528)
-- Name: pensions pensions_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.pensions
    ADD CONSTRAINT pensions_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13201 (class 2606 OID 53530)
-- Name: prepayments prepayments_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.prepayments
    ADD CONSTRAINT prepayments_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12674 (class 2606 OID 53532)
-- Name: product_alert_quantity product_alert_quantity_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.product_alert_quantity
    ADD CONSTRAINT product_alert_quantity_id_primary PRIMARY KEY (id);


--
-- TOC entry 12984 (class 2606 OID 53534)
-- Name: product_cart product_cart_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.product_cart
    ADD CONSTRAINT product_cart_id_primary PRIMARY KEY (id);


--
-- TOC entry 12958 (class 2606 OID 53536)
-- Name: product_purchases product_purchases_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.product_purchases
    ADD CONSTRAINT product_purchases_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13207 (class 2606 OID 53538)
-- Name: product_registers product_registers_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.product_registers
    ADD CONSTRAINT product_registers_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13056 (class 2606 OID 53540)
-- Name: product_sales product_sales_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.product_sales
    ADD CONSTRAINT product_sales_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13221 (class 2606 OID 53542)
-- Name: proforma_invoices_fees_installments proforma_invoices_fees_installment_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.proforma_invoices_fees_installments
    ADD CONSTRAINT proforma_invoices_fees_installment_id_primary PRIMARY KEY (id);


--
-- TOC entry 13213 (class 2606 OID 53544)
-- Name: proforma_invoices proforma_invoices_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.proforma_invoices
    ADD CONSTRAINT proforma_invoices_id_primary PRIMARY KEY (id);


--
-- TOC entry 13224 (class 2606 OID 53546)
-- Name: proforma_payments proforma_payments_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.proforma_payments
    ADD CONSTRAINT proforma_payments_id_primary PRIMARY KEY (id);


--
-- TOC entry 13219 (class 2606 OID 53548)
-- Name: proforma_invoices_fee_amount proforma_proforma_invoices_fee_amount_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.proforma_invoices_fee_amount
    ADD CONSTRAINT proforma_proforma_invoices_fee_amount_id_primary PRIMARY KEY (id);


--
-- TOC entry 13229 (class 2606 OID 53550)
-- Name: promotionsubject promotionsubject_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.promotionsubject
    ADD CONSTRAINT promotionsubject_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13232 (class 2606 OID 53552)
-- Name: questions questions_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.questions
    ADD CONSTRAINT questions_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13235 (class 2606 OID 53554)
-- Name: receipt_settings receipt_settings_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.receipt_settings
    ADD CONSTRAINT receipt_settings_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13238 (class 2606 OID 53556)
-- Name: refer_character_grading_systems refer_character_grading_systems_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.refer_character_grading_systems
    ADD CONSTRAINT refer_character_grading_systems_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13241 (class 2606 OID 53558)
-- Name: refer_exam refer_exam_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.refer_exam
    ADD CONSTRAINT refer_exam_id_primary PRIMARY KEY (id);


--
-- TOC entry 12837 (class 2606 OID 53560)
-- Name: refer_expense refer_expense_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.refer_expense
    ADD CONSTRAINT refer_expense_id_primary PRIMARY KEY (id);


--
-- TOC entry 12839 (class 2606 OID 53562)
-- Name: refer_expense refer_expense_id_schema_name; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.refer_expense
    ADD CONSTRAINT refer_expense_id_schema_name UNIQUE (uid, schema_name);


--
-- TOC entry 12842 (class 2606 OID 53564)
-- Name: refer_expense refer_expense_uuid_unique; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.refer_expense
    ADD CONSTRAINT refer_expense_uuid_unique UNIQUE (uuid);


--
-- TOC entry 13122 (class 2606 OID 53566)
-- Name: refer_subject refer_subject_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.refer_subject
    ADD CONSTRAINT refer_subject_id_primary PRIMARY KEY (subject_id);


--
-- TOC entry 13244 (class 2606 OID 53568)
-- Name: reminders reminders_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.reminders
    ADD CONSTRAINT reminders_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13247 (class 2606 OID 53570)
-- Name: reply_msg reply_msg_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.reply_msg
    ADD CONSTRAINT reply_msg_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13250 (class 2606 OID 53572)
-- Name: reply_sms reply_sms_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.reply_sms
    ADD CONSTRAINT reply_sms_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13253 (class 2606 OID 53574)
-- Name: reset reset_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.reset
    ADD CONSTRAINT reset_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13256 (class 2606 OID 53576)
-- Name: revenue revenu_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.revenue
    ADD CONSTRAINT revenu_id_primary PRIMARY KEY (id);


--
-- TOC entry 13261 (class 2606 OID 53578)
-- Name: revenue_cart revenue_cart_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.revenue_cart
    ADD CONSTRAINT revenue_cart_id_primary PRIMARY KEY (id);


--
-- TOC entry 12736 (class 2606 OID 53580)
-- Name: revenues revenues_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.revenues
    ADD CONSTRAINT revenues_id_primary PRIMARY KEY (id);


--
-- TOC entry 13059 (class 2606 OID 53582)
-- Name: role role_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.role
    ADD CONSTRAINT role_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13264 (class 2606 OID 53584)
-- Name: role_permission role_permission_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.role_permission
    ADD CONSTRAINT role_permission_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13267 (class 2606 OID 53586)
-- Name: route_vehicle route_vehicle_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.route_vehicle
    ADD CONSTRAINT route_vehicle_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13273 (class 2606 OID 53588)
-- Name: routine_daily routine_daily_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.routine_daily
    ADD CONSTRAINT routine_daily_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13270 (class 2606 OID 53590)
-- Name: routine routine_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.routine
    ADD CONSTRAINT routine_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12677 (class 2606 OID 53592)
-- Name: salaries salaries_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.salaries
    ADD CONSTRAINT salaries_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13276 (class 2606 OID 53594)
-- Name: salary_allowances salary_allowances_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.salary_allowances
    ADD CONSTRAINT salary_allowances_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13279 (class 2606 OID 53596)
-- Name: salary_deductions salary_deductions_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.salary_deductions
    ADD CONSTRAINT salary_deductions_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13282 (class 2606 OID 53598)
-- Name: salary_pensions salary_pensions_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.salary_pensions
    ADD CONSTRAINT salary_pensions_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12680 (class 2606 OID 53600)
-- Name: sattendances sattendances_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.sattendances
    ADD CONSTRAINT sattendances_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12799 (class 2606 OID 53602)
-- Name: section section_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.section
    ADD CONSTRAINT section_id_primary PRIMARY KEY ("sectionID");


--
-- TOC entry 13290 (class 2606 OID 53604)
-- Name: section_subject_teacher section_subject_teacher_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.section_subject_teacher
    ADD CONSTRAINT section_subject_teacher_id_primary PRIMARY KEY (id);


--
-- TOC entry 13293 (class 2606 OID 53606)
-- Name: semester semester_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.semester
    ADD CONSTRAINT semester_id_primary PRIMARY KEY (id);


--
-- TOC entry 12683 (class 2606 OID 53608)
-- Name: setting setting_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.setting
    ADD CONSTRAINT setting_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12686 (class 2606 OID 53610)
-- Name: setting setting_schema_name_unique; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.setting
    ADD CONSTRAINT setting_schema_name_unique UNIQUE (schema_name);


--
-- TOC entry 13296 (class 2606 OID 53612)
-- Name: slots slots_all_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.slots
    ADD CONSTRAINT slots_all_id_primary PRIMARY KEY (id);


--
-- TOC entry 13301 (class 2606 OID 53614)
-- Name: sms_content_channels sms_content_channels_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.sms_content_channels
    ADD CONSTRAINT sms_content_channels_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13298 (class 2606 OID 53616)
-- Name: sms_content sms_content_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.sms_content
    ADD CONSTRAINT sms_content_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13304 (class 2606 OID 53618)
-- Name: sms_files sms_files_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.sms_files
    ADD CONSTRAINT sms_files_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12688 (class 2606 OID 53620)
-- Name: sms sms_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.sms
    ADD CONSTRAINT sms_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13307 (class 2606 OID 53622)
-- Name: sms_keys sms_keys_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.sms_keys
    ADD CONSTRAINT sms_keys_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13310 (class 2606 OID 53624)
-- Name: smssettings smssettings_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.smssettings
    ADD CONSTRAINT smssettings_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13313 (class 2606 OID 53626)
-- Name: special_grade_names special_grade_names_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.special_grade_names
    ADD CONSTRAINT special_grade_names_id_primary PRIMARY KEY (id);


--
-- TOC entry 13316 (class 2606 OID 53628)
-- Name: special_grades special_grades_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.special_grades
    ADD CONSTRAINT special_grades_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13319 (class 2606 OID 53630)
-- Name: special_promotion special_promotion_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.special_promotion
    ADD CONSTRAINT special_promotion_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13322 (class 2606 OID 53632)
-- Name: sponsors sponsors_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.sponsors
    ADD CONSTRAINT sponsors_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13325 (class 2606 OID 53634)
-- Name: staff_leave staff_leave_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.staff_leave
    ADD CONSTRAINT staff_leave_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13328 (class 2606 OID 53636)
-- Name: staff_report staff_report_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.staff_report
    ADD CONSTRAINT staff_report_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13331 (class 2606 OID 53638)
-- Name: staff_targets staff_targets_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.staff_targets
    ADD CONSTRAINT staff_targets_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13334 (class 2606 OID 53640)
-- Name: staff_targets_reports staff_targets_reports_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.staff_targets_reports
    ADD CONSTRAINT staff_targets_reports_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13338 (class 2606 OID 53642)
-- Name: store_students_id store_students_id_pkey; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.store_students_id
    ADD CONSTRAINT store_students_id_pkey PRIMARY KEY (id);


--
-- TOC entry 13340 (class 2606 OID 53644)
-- Name: student_addresses student_addresses_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student_addresses
    ADD CONSTRAINT student_addresses_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12784 (class 2606 OID 53646)
-- Name: student_archive student_archive_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student_archive
    ADD CONSTRAINT student_archive_id_primary PRIMARY KEY (id);


--
-- TOC entry 13346 (class 2606 OID 53648)
-- Name: student_assessment student_assessment_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student_assessment
    ADD CONSTRAINT student_assessment_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13349 (class 2606 OID 53650)
-- Name: student_characters student_characters_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student_characters
    ADD CONSTRAINT student_characters_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13352 (class 2606 OID 53652)
-- Name: student_due_date student_due_date_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student_due_date
    ADD CONSTRAINT student_due_date_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13355 (class 2606 OID 53654)
-- Name: student_duties student_duties_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student_duties
    ADD CONSTRAINT student_duties_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13358 (class 2606 OID 53656)
-- Name: student_fee_subscription student_fee_subscription_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student_fee_subscription
    ADD CONSTRAINT student_fee_subscription_id_primary PRIMARY KEY (id);


--
-- TOC entry 13343 (class 2606 OID 53658)
-- Name: student_fees_installments_unsubscriptions student_fees_installments_unsubscriptions_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student_fees_installments_unsubscriptions
    ADD CONSTRAINT student_fees_installments_unsubscriptions_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12649 (class 2606 OID 53660)
-- Name: hmembers student_id_hostel_id_installment_id; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.hmembers
    ADD CONSTRAINT student_id_hostel_id_installment_id UNIQUE (student_id, hostel_id, installment_id);


--
-- TOC entry 12691 (class 2606 OID 53662)
-- Name: student student_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student
    ADD CONSTRAINT student_id_primary PRIMARY KEY (student_id);


--
-- TOC entry 13364 (class 2606 OID 53664)
-- Name: student_installment_packages student_installment_packages_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student_installment_packages
    ADD CONSTRAINT student_installment_packages_primary PRIMARY KEY (id);


--
-- TOC entry 13366 (class 2606 OID 53666)
-- Name: student_other student_other_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student_other
    ADD CONSTRAINT student_other_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13038 (class 2606 OID 53668)
-- Name: student_parents student_parents_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student_parents
    ADD CONSTRAINT student_parents_id_primary PRIMARY KEY (id);


--
-- TOC entry 13369 (class 2606 OID 53670)
-- Name: student_reams student_reams_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student_reams
    ADD CONSTRAINT student_reams_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13372 (class 2606 OID 53672)
-- Name: student_report student_report_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student_report
    ADD CONSTRAINT student_report_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13375 (class 2606 OID 53674)
-- Name: student_sponsors student_sponsors_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student_sponsors
    ADD CONSTRAINT student_sponsors_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13378 (class 2606 OID 53676)
-- Name: student_status student_status_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student_status
    ADD CONSTRAINT student_status_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12694 (class 2606 OID 57699)
-- Name: student student_unique_username; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.student
    ADD CONSTRAINT student_unique_username UNIQUE (username);


--
-- TOC entry 13128 (class 2606 OID 53678)
-- Name: subject subject_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.subject
    ADD CONSTRAINT subject_id_primary PRIMARY KEY ("subjectID");


--
-- TOC entry 13381 (class 2606 OID 53680)
-- Name: subject_mark subject_mark_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.subject_mark
    ADD CONSTRAINT subject_mark_id_primary PRIMARY KEY (id);


--
-- TOC entry 12891 (class 2606 OID 53682)
-- Name: subject_section subject_section_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.subject_section
    ADD CONSTRAINT subject_section_id_primary PRIMARY KEY (subject_section_id);


--
-- TOC entry 12897 (class 2606 OID 53684)
-- Name: subject_student subject_student_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.subject_student
    ADD CONSTRAINT subject_student_id_primary PRIMARY KEY (subject_student_id);


--
-- TOC entry 13384 (class 2606 OID 53686)
-- Name: subject_topic subject_topic_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.subject_topic
    ADD CONSTRAINT subject_topic_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13387 (class 2606 OID 53688)
-- Name: submit_files submit_files_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.submit_files
    ADD CONSTRAINT submit_files_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13390 (class 2606 OID 53690)
-- Name: syllabus_benchmarks syllabus_benchmarks_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.syllabus_benchmarks
    ADD CONSTRAINT syllabus_benchmarks_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13393 (class 2606 OID 53692)
-- Name: syllabus_objective_references syllabus_objective_references_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.syllabus_objective_references
    ADD CONSTRAINT syllabus_objective_references_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13396 (class 2606 OID 53694)
-- Name: syllabus_objectives syllabus_objectives_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.syllabus_objectives
    ADD CONSTRAINT syllabus_objectives_id_primary PRIMARY KEY (id);


--
-- TOC entry 13399 (class 2606 OID 53696)
-- Name: syllabus_student_benchmarking syllabus_student_benchmarking_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.syllabus_student_benchmarking
    ADD CONSTRAINT syllabus_student_benchmarking_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13402 (class 2606 OID 53698)
-- Name: syllabus_subtopics syllabus_subtopics_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.syllabus_subtopics
    ADD CONSTRAINT syllabus_subtopics_id_primary PRIMARY KEY (id);


--
-- TOC entry 13405 (class 2606 OID 53700)
-- Name: syllabus_subtopics_teachers syllabus_subtopics_teachers_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.syllabus_subtopics_teachers
    ADD CONSTRAINT syllabus_subtopics_teachers_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13160 (class 2606 OID 53702)
-- Name: syllabus_topics syllabus_topics_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.syllabus_topics
    ADD CONSTRAINT syllabus_topics_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13408 (class 2606 OID 53704)
-- Name: tattendance tattendance_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.tattendance
    ADD CONSTRAINT tattendance_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13411 (class 2606 OID 53706)
-- Name: tattendances tattendances_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.tattendances
    ADD CONSTRAINT tattendances_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12703 (class 2606 OID 53708)
-- Name: teacher_duties teacher_duties_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.teacher_duties
    ADD CONSTRAINT teacher_duties_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12697 (class 2606 OID 53710)
-- Name: teacher teacher_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.teacher
    ADD CONSTRAINT teacher_id_primary PRIMARY KEY ("teacherID");


--
-- TOC entry 13414 (class 2606 OID 53712)
-- Name: tempfiles tempfiles_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.tempfiles
    ADD CONSTRAINT tempfiles_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13417 (class 2606 OID 53714)
-- Name: timetables timetables_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.timetables
    ADD CONSTRAINT timetables_id_primary PRIMARY KEY (id);


--
-- TOC entry 12710 (class 2606 OID 53716)
-- Name: tmembers tmembers_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.tmembers
    ADD CONSTRAINT tmembers_id_primary PRIMARY KEY (id);


--
-- TOC entry 12713 (class 2606 OID 53718)
-- Name: tmembers tmembers_student_id_transport_route_id_installment_id_unique; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.tmembers
    ADD CONSTRAINT tmembers_student_id_transport_route_id_installment_id_unique UNIQUE (student_id, transport_route_id, installment_id);


--
-- TOC entry 13419 (class 2606 OID 53720)
-- Name: topic_mark topic_mark_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.topic_mark
    ADD CONSTRAINT topic_mark_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13422 (class 2606 OID 53722)
-- Name: tour_users tour_users_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.tour_users
    ADD CONSTRAINT tour_users_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13425 (class 2606 OID 53724)
-- Name: tours tours_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.tours
    ADD CONSTRAINT tours_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13428 (class 2606 OID 53726)
-- Name: track track_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.track
    ADD CONSTRAINT track_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13434 (class 2606 OID 53728)
-- Name: track_invoices_fees_installments track_invoices_fees_installments_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.track_invoices_fees_installments
    ADD CONSTRAINT track_invoices_fees_installments_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13431 (class 2606 OID 53730)
-- Name: track_invoices track_invoices_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.track_invoices
    ADD CONSTRAINT track_invoices_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13437 (class 2606 OID 53732)
-- Name: track_payments track_payments_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.track_payments
    ADD CONSTRAINT track_payments_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13440 (class 2606 OID 53734)
-- Name: trainings trainings_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.trainings
    ADD CONSTRAINT trainings_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13443 (class 2606 OID 53736)
-- Name: transport transport_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.transport
    ADD CONSTRAINT transport_id_primary PRIMARY KEY (id);


--
-- TOC entry 13447 (class 2606 OID 53738)
-- Name: transport_installment transport_installment_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.transport_installment
    ADD CONSTRAINT transport_installment_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12807 (class 2606 OID 53740)
-- Name: transport_routes_fees_installments transport_routes_fees_installments_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.transport_routes_fees_installments
    ADD CONSTRAINT transport_routes_fees_installments_id_primary PRIMARY KEY (id);


--
-- TOC entry 12802 (class 2606 OID 53742)
-- Name: transport_routes transport_routes_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.transport_routes
    ADD CONSTRAINT transport_routes_id_primary PRIMARY KEY (id);


--
-- TOC entry 13451 (class 2606 OID 53744)
-- Name: uattendances uattendances_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.uattendances
    ADD CONSTRAINT uattendances_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13454 (class 2606 OID 53746)
-- Name: user_allowances user_allowances_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.user_allowances
    ADD CONSTRAINT user_allowances_id_primary PRIMARY KEY (id);


--
-- TOC entry 13457 (class 2606 OID 53748)
-- Name: user_contract user_contract_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.user_contract
    ADD CONSTRAINT user_contract_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13460 (class 2606 OID 53750)
-- Name: user_deductions user_deductions_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.user_deductions
    ADD CONSTRAINT user_deductions_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13463 (class 2606 OID 53752)
-- Name: user_devices user_devices_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.user_devices
    ADD CONSTRAINT user_devices_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12739 (class 2606 OID 53754)
-- Name: user user_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft."user"
    ADD CONSTRAINT user_id_primary PRIMARY KEY ("userID");


--
-- TOC entry 13466 (class 2606 OID 53756)
-- Name: user_pensions user_pensions_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.user_pensions
    ADD CONSTRAINT user_pensions_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13469 (class 2606 OID 53758)
-- Name: user_phones user_phones_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.user_phones
    ADD CONSTRAINT user_phones_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13472 (class 2606 OID 53760)
-- Name: user_reminders user_reminders_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.user_reminders
    ADD CONSTRAINT user_reminders_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13475 (class 2606 OID 53762)
-- Name: user_role user_role_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.user_role
    ADD CONSTRAINT user_role_id_primary PRIMARY KEY (id);


--
-- TOC entry 13478 (class 2606 OID 53764)
-- Name: user_updates user_updates_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.user_updates
    ADD CONSTRAINT user_updates_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13481 (class 2606 OID 53766)
-- Name: valid_answers valid_answers_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.valid_answers
    ADD CONSTRAINT valid_answers_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13361 (class 2606 OID 53768)
-- Name: vehicles vehicles_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.vehicles
    ADD CONSTRAINT vehicles_id_primary PRIMARY KEY (id);


--
-- TOC entry 13484 (class 2606 OID 53770)
-- Name: vendors vendors_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.vendors
    ADD CONSTRAINT vendors_id_primary PRIMARY KEY (id);


--
-- TOC entry 13487 (class 2606 OID 53772)
-- Name: wallet_cart wallet_cart_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.wallet_cart
    ADD CONSTRAINT wallet_cart_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13490 (class 2606 OID 53774)
-- Name: wallet_uses wallet_uses_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.wallet_uses
    ADD CONSTRAINT wallet_uses_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13493 (class 2606 OID 53776)
-- Name: wallets wallets_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.wallets
    ADD CONSTRAINT wallets_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13496 (class 2606 OID 53778)
-- Name: warehouse_store_keepers warehouse_store_keepers_pkey; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.warehouse_store_keepers
    ADD CONSTRAINT warehouse_store_keepers_pkey PRIMARY KEY (id);


--
-- TOC entry 13204 (class 2606 OID 53780)
-- Name: warehouse_transfers warehouse_transfers_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.warehouse_transfers
    ADD CONSTRAINT warehouse_transfers_id_primary PRIMARY KEY (uid);


--
-- TOC entry 13498 (class 2606 OID 53782)
-- Name: warehouses warehouses_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.warehouses
    ADD CONSTRAINT warehouses_id_primary PRIMARY KEY (id);


--
-- TOC entry 13501 (class 2606 OID 53784)
-- Name: youtube_access_tokens youtube_access_tokens_id_primary; Type: CONSTRAINT; Schema: shulesoft; Owner: postgres
--

ALTER TABLE ONLY shulesoft.youtube_access_tokens
    ADD CONSTRAINT youtube_access_tokens_id_primary PRIMARY KEY (uid);


--
-- TOC entry 12611 (class 1259 OID 53785)
-- Name: academic_year_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX academic_year_schema_name_index ON shulesoft.academic_year USING btree (schema_name);


--
-- TOC entry 12716 (class 1259 OID 53786)
-- Name: account_groups_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX account_groups_schema_name_index ON shulesoft.account_groups USING btree (schema_name);


--
-- TOC entry 12717 (class 1259 OID 53787)
-- Name: advance_payments_id_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX advance_payments_id_index ON shulesoft.advance_payments USING btree (id);


--
-- TOC entry 12725 (class 1259 OID 53788)
-- Name: advance_payments_invoices_fees_installments_payment_id_id_forei; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX advance_payments_invoices_fees_installments_payment_id_id_forei ON shulesoft.advance_payments_invoices_fees_installments USING btree (advance_payment_id);


--
-- TOC entry 12726 (class 1259 OID 53789)
-- Name: advance_payments_invoices_fees_installments_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX advance_payments_invoices_fees_installments_schema_name_index ON shulesoft.advance_payments_invoices_fees_installments USING btree (schema_name);


--
-- TOC entry 12720 (class 1259 OID 53790)
-- Name: advance_payments_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX advance_payments_schema_name_index ON shulesoft.advance_payments USING btree (schema_name);


--
-- TOC entry 12743 (class 1259 OID 53791)
-- Name: allowances_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX allowances_schema_name_index ON shulesoft.allowances USING btree (schema_name);


--
-- TOC entry 12811 (class 1259 OID 53792)
-- Name: application_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX application_schema_name_index ON shulesoft.application USING btree (schema_name);


--
-- TOC entry 12814 (class 1259 OID 53793)
-- Name: appointments_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX appointments_schema_name_index ON shulesoft.appointments USING btree (schema_name);


--
-- TOC entry 12817 (class 1259 OID 53794)
-- Name: assignment_downloads_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX assignment_downloads_schema_name_index ON shulesoft.assignment_downloads USING btree (schema_name);


--
-- TOC entry 12820 (class 1259 OID 53795)
-- Name: assignment_files_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX assignment_files_schema_name_index ON shulesoft.assignment_files USING btree (schema_name);


--
-- TOC entry 12823 (class 1259 OID 53796)
-- Name: assignment_viewers_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX assignment_viewers_schema_name_index ON shulesoft.assignment_viewers USING btree (schema_name);


--
-- TOC entry 12826 (class 1259 OID 53797)
-- Name: assignments_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX assignments_schema_name_index ON shulesoft.assignments USING btree (schema_name);


--
-- TOC entry 12829 (class 1259 OID 53798)
-- Name: assignments_submitted_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX assignments_submitted_schema_name_index ON shulesoft.assignments_submitted USING btree (schema_name);


--
-- TOC entry 12832 (class 1259 OID 53799)
-- Name: attendance_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX attendance_schema_name_index ON shulesoft.attendance USING btree (schema_name);


--
-- TOC entry 12835 (class 1259 OID 53800)
-- Name: bank_accounts_fees_classes_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX bank_accounts_fees_classes_schema_name_index ON shulesoft.bank_accounts_fees_classes USING btree (schema_name);


--
-- TOC entry 12619 (class 1259 OID 53801)
-- Name: bank_accounts_integrations_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX bank_accounts_integrations_schema_name_index ON shulesoft.bank_accounts_integrations USING btree (schema_name);


--
-- TOC entry 12616 (class 1259 OID 53802)
-- Name: bank_accounts_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX bank_accounts_schema_name_index ON shulesoft.bank_accounts USING btree (schema_name);


--
-- TOC entry 12848 (class 1259 OID 53803)
-- Name: book_class_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX book_class_schema_name_index ON shulesoft.book_class USING btree (schema_name);


--
-- TOC entry 12851 (class 1259 OID 53804)
-- Name: book_quantity_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX book_quantity_schema_name_index ON shulesoft.book_quantity USING btree (schema_name);


--
-- TOC entry 12845 (class 1259 OID 53805)
-- Name: book_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX book_schema_name_index ON shulesoft.book USING btree (schema_name);


--
-- TOC entry 12858 (class 1259 OID 53806)
-- Name: budgets_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX budgets_schema_name_index ON shulesoft.budgets USING btree (schema_name);


--
-- TOC entry 12861 (class 1259 OID 53807)
-- Name: capital_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX capital_schema_name_index ON shulesoft.capital USING btree (schema_name);


--
-- TOC entry 12864 (class 1259 OID 53808)
-- Name: car_tracker_key_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX car_tracker_key_schema_name_index ON shulesoft.car_tracker_key USING btree (schema_name);


--
-- TOC entry 12867 (class 1259 OID 53809)
-- Name: cash_requests_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX cash_requests_schema_name_index ON shulesoft.cash_requests USING btree (schema_name);


--
-- TOC entry 12870 (class 1259 OID 53810)
-- Name: certificate_setting_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX certificate_setting_schema_name_index ON shulesoft.certificate_setting USING btree (schema_name);


--
-- TOC entry 12875 (class 1259 OID 53811)
-- Name: character_classes_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX character_classes_schema_name_index ON shulesoft.character_classes USING btree (schema_name);


--
-- TOC entry 12878 (class 1259 OID 53812)
-- Name: characters_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX characters_schema_name_index ON shulesoft.characters USING btree (schema_name);


--
-- TOC entry 12881 (class 1259 OID 53813)
-- Name: class_exam_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX class_exam_schema_name_index ON shulesoft.class_exam USING btree (schema_name);


--
-- TOC entry 12622 (class 1259 OID 53814)
-- Name: classes_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX classes_schema_name_index ON shulesoft.classes USING btree (schema_name);


--
-- TOC entry 12627 (class 1259 OID 53815)
-- Name: classlevel_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX classlevel_schema_name_index ON shulesoft.classlevel USING btree (schema_name);


--
-- TOC entry 12884 (class 1259 OID 53816)
-- Name: closing_year_balance_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX closing_year_balance_schema_name_index ON shulesoft.closing_year_balance USING btree (schema_name);


--
-- TOC entry 12887 (class 1259 OID 53817)
-- Name: configurations_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX configurations_schema_name_index ON shulesoft.configurations USING btree (schema_name);


--
-- TOC entry 12899 (class 1259 OID 53818)
-- Name: current_assets2_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX current_assets2_schema_name_index ON shulesoft.current_assets2 USING btree (schema_name);


--
-- TOC entry 12902 (class 1259 OID 53819)
-- Name: current_assets_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX current_assets_schema_name_index ON shulesoft.current_assets2 USING btree (schema_name);


--
-- TOC entry 12907 (class 1259 OID 53820)
-- Name: deductions_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX deductions_schema_name_index ON shulesoft.deductions USING btree (schema_name);


--
-- TOC entry 12630 (class 1259 OID 53821)
-- Name: diaries_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX diaries_schema_name_index ON shulesoft.diaries USING btree (schema_name);


--
-- TOC entry 12910 (class 1259 OID 53822)
-- Name: diary_comments_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX diary_comments_schema_name_index ON shulesoft.diary_comments USING btree (schema_name);


--
-- TOC entry 12744 (class 1259 OID 53823)
-- Name: discount_fees_installments_id_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX discount_fees_installments_id_index ON shulesoft.discount_fees_installments USING btree (fees_installment_id);


--
-- TOC entry 12747 (class 1259 OID 53824)
-- Name: discount_fees_installments_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX discount_fees_installments_schema_name_index ON shulesoft.discount_fees_installments USING btree (schema_name);


--
-- TOC entry 12748 (class 1259 OID 53825)
-- Name: discount_fees_installments_student_id_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX discount_fees_installments_student_id_index ON shulesoft.discount_fees_installments USING btree (student_id);


--
-- TOC entry 12913 (class 1259 OID 53826)
-- Name: due_amounts_payments_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX due_amounts_payments_schema_name_index ON shulesoft.due_amounts_payments USING btree (schema_name);


--
-- TOC entry 12751 (class 1259 OID 53827)
-- Name: due_amounts_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX due_amounts_schema_name_index ON shulesoft.due_amounts USING btree (schema_name);


--
-- TOC entry 12701 (class 1259 OID 53828)
-- Name: duties_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX duties_schema_name_index ON shulesoft.duties USING btree (schema_name);


--
-- TOC entry 12916 (class 1259 OID 53829)
-- Name: duty_reports_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX duty_reports_schema_name_index ON shulesoft.duty_reports USING btree (schema_name);


--
-- TOC entry 12919 (class 1259 OID 53830)
-- Name: eattendance_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX eattendance_schema_name_index ON shulesoft.eattendance USING btree (schema_name);


--
-- TOC entry 12925 (class 1259 OID 53831)
-- Name: email_lists_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX email_lists_schema_name_index ON shulesoft.email_lists USING btree (schema_name);


--
-- TOC entry 12922 (class 1259 OID 53832)
-- Name: email_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX email_schema_name_index ON shulesoft.email USING btree (schema_name);


--
-- TOC entry 12931 (class 1259 OID 53833)
-- Name: exam_comments_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX exam_comments_schema_name_index ON shulesoft.exam_comments USING btree (schema_name);


--
-- TOC entry 12936 (class 1259 OID 53834)
-- Name: exam_groups_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX exam_groups_schema_name_index ON shulesoft.exam_groups USING btree (schema_name);


--
-- TOC entry 12633 (class 1259 OID 53835)
-- Name: exam_report_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX exam_report_schema_name_index ON shulesoft.exam_report USING btree (schema_name);


--
-- TOC entry 12939 (class 1259 OID 53836)
-- Name: exam_report_settings_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX exam_report_settings_schema_name_index ON shulesoft.exam_report_settings USING btree (schema_name);


--
-- TOC entry 12928 (class 1259 OID 53837)
-- Name: exam_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX exam_schema_name_index ON shulesoft.exam USING btree (schema_name);


--
-- TOC entry 12942 (class 1259 OID 53838)
-- Name: exam_special_cases_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX exam_special_cases_schema_name_index ON shulesoft.exam_special_cases USING btree (schema_name);


--
-- TOC entry 12947 (class 1259 OID 53839)
-- Name: exam_subject_mark_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX exam_subject_mark_schema_name_index ON shulesoft.exam_subject_mark USING btree (schema_name);


--
-- TOC entry 12950 (class 1259 OID 53840)
-- Name: examschedule_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX examschedule_schema_name_index ON shulesoft.examschedule USING btree (schema_name);


--
-- TOC entry 12953 (class 1259 OID 53841)
-- Name: exchange_rates_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX exchange_rates_schema_name_index ON shulesoft.exchange_rates USING btree (schema_name);


--
-- TOC entry 12956 (class 1259 OID 53842)
-- Name: expense_cart_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX expense_cart_schema_name_index ON shulesoft.expense_cart USING btree (schema_name);


--
-- TOC entry 12636 (class 1259 OID 53843)
-- Name: expense_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX expense_schema_name_index ON shulesoft.expense USING btree (schema_name);


--
-- TOC entry 12962 (class 1259 OID 53844)
-- Name: expenses_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX expenses_schema_name_index ON shulesoft.expenses USING btree (schema_name);


--
-- TOC entry 12965 (class 1259 OID 53845)
-- Name: feecat_class_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX feecat_class_schema_name_index ON shulesoft.feecat_class USING btree (schema_name);


--
-- TOC entry 12968 (class 1259 OID 53846)
-- Name: fees_classes_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fees_classes_schema_name_index ON shulesoft.fees_classes USING btree (schema_name);


--
-- TOC entry 12763 (class 1259 OID 53847)
-- Name: fees_installments_classes_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fees_installments_classes_schema_name_index ON shulesoft.fees_installments_classes USING btree (schema_name);


--
-- TOC entry 12758 (class 1259 OID 53848)
-- Name: fees_installments_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fees_installments_schema_name_index ON shulesoft.fees_installments USING btree (schema_name);


--
-- TOC entry 12754 (class 1259 OID 53849)
-- Name: fees_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fees_name_index ON shulesoft.fees USING btree (name);


--
-- TOC entry 12755 (class 1259 OID 53850)
-- Name: fees_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fees_schema_name_index ON shulesoft.fees USING btree (schema_name);


--
-- TOC entry 12971 (class 1259 OID 53851)
-- Name: file_folder_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX file_folder_schema_name_index ON shulesoft.file_folder USING btree (schema_name);


--
-- TOC entry 12974 (class 1259 OID 53852)
-- Name: file_share_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX file_share_schema_name_index ON shulesoft.file_share USING btree (schema_name);


--
-- TOC entry 12977 (class 1259 OID 53853)
-- Name: files_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX files_schema_name_index ON shulesoft.files USING btree (schema_name);


--
-- TOC entry 12982 (class 1259 OID 53854)
-- Name: financial_year_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX financial_year_schema_name_index ON shulesoft.financial_year USING btree (schema_name);


--
-- TOC entry 12988 (class 1259 OID 53855)
-- Name: fixed_assets_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fixed_assets_schema_name_index ON shulesoft.fixed_assets USING btree (schema_name);


--
-- TOC entry 13284 (class 1259 OID 53856)
-- Name: fki_academic_year_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_academic_year_id ON shulesoft.section_subject_teacher USING btree (academic_year_id);


--
-- TOC entry 12721 (class 1259 OID 53857)
-- Name: fki_advance_payments_fee_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_advance_payments_fee_id ON shulesoft.advance_payments USING btree (fee_id);


--
-- TOC entry 12722 (class 1259 OID 53858)
-- Name: fki_advance_payments_student_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_advance_payments_student_id ON shulesoft.advance_payments USING btree (student_id);


--
-- TOC entry 13285 (class 1259 OID 53859)
-- Name: fki_class_id_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_class_id_index ON shulesoft.section_subject_teacher USING btree ("classesID");


--
-- TOC entry 12932 (class 1259 OID 53860)
-- Name: fki_exam_comments_academic_year_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_exam_comments_academic_year_id ON shulesoft.exam_comments USING btree (academic_year_id);


--
-- TOC entry 12933 (class 1259 OID 53861)
-- Name: fki_exam_comments_student_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_exam_comments_student_id ON shulesoft.exam_comments USING btree (student_id);


--
-- TOC entry 12943 (class 1259 OID 53862)
-- Name: fki_exam_special_cases_exam_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_exam_special_cases_exam_id ON shulesoft.exam_special_cases USING btree (exam_id);


--
-- TOC entry 12944 (class 1259 OID 53863)
-- Name: fki_exam_special_cases_student_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_exam_special_cases_student_id ON shulesoft.exam_special_cases USING btree (student_id);


--
-- TOC entry 12759 (class 1259 OID 53864)
-- Name: fki_fee_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_fee_id ON shulesoft.fees_installments USING btree (fee_id);


--
-- TOC entry 12764 (class 1259 OID 53865)
-- Name: fki_fees_installments_classes_class_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_fees_installments_classes_class_id ON shulesoft.fees_installments_classes USING btree (class_id);


--
-- TOC entry 12765 (class 1259 OID 53866)
-- Name: fki_fees_installments_classes_installment_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_fees_installments_classes_installment_id ON shulesoft.fees_installments_classes USING btree (fees_installment_id);


--
-- TOC entry 12640 (class 1259 OID 53867)
-- Name: fki_hmembers_hostel_category_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_hmembers_hostel_category_id ON shulesoft.hmembers USING btree (hostel_category_id);


--
-- TOC entry 12641 (class 1259 OID 53868)
-- Name: fki_hmembers_hostel_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_hmembers_hostel_id ON shulesoft.hmembers USING btree (hostel_id);


--
-- TOC entry 12642 (class 1259 OID 53869)
-- Name: fki_hmembers_installment_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_hmembers_installment_id ON shulesoft.hmembers USING btree (installment_id);


--
-- TOC entry 12643 (class 1259 OID 53870)
-- Name: fki_hmembers_student_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_hmembers_student_id ON shulesoft.hmembers USING btree (student_id);


--
-- TOC entry 12766 (class 1259 OID 53871)
-- Name: fki_hostel_fees_installments_fees_installment_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_hostel_fees_installments_fees_installment_id ON shulesoft.hostel_fees_installments USING btree (fees_installment_id);


--
-- TOC entry 12767 (class 1259 OID 53872)
-- Name: fki_hostel_fees_installments_hostel_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_hostel_fees_installments_hostel_id ON shulesoft.hostel_fees_installments USING btree (hostel_id);


--
-- TOC entry 12760 (class 1259 OID 53873)
-- Name: fki_installment_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_installment_id ON shulesoft.fees_installments USING btree (installment_id);


--
-- TOC entry 12776 (class 1259 OID 53874)
-- Name: fki_installments_academic_year_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_installments_academic_year_id ON shulesoft.installments USING btree (academic_year_id);


--
-- TOC entry 12650 (class 1259 OID 53875)
-- Name: fki_invoices_academic_year_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_invoices_academic_year_id ON shulesoft.invoices USING btree (academic_year_id);


--
-- TOC entry 12727 (class 1259 OID 53876)
-- Name: fki_invoices_fees_installment_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_invoices_fees_installment_id ON shulesoft.advance_payments_invoices_fees_installments USING btree (invoices_fees_installments_id);


--
-- TOC entry 12786 (class 1259 OID 53877)
-- Name: fki_invoices_fees_installments_fees_installment_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_invoices_fees_installments_fees_installment_id ON shulesoft.invoices_fees_installments USING btree (fees_installment_id);


--
-- TOC entry 12787 (class 1259 OID 53878)
-- Name: fki_invoices_fees_installments_invoice_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_invoices_fees_installments_invoice_id ON shulesoft.invoices_fees_installments USING btree (invoice_id);


--
-- TOC entry 12651 (class 1259 OID 53879)
-- Name: fki_invoices_student_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_invoices_student_id ON shulesoft.invoices USING btree (student_id);


--
-- TOC entry 13046 (class 1259 OID 53880)
-- Name: fki_issue_book_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_issue_book_id ON shulesoft.issue USING btree (book_id);


--
-- TOC entry 13051 (class 1259 OID 53881)
-- Name: fki_issue_inventory_product_cart_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_issue_inventory_product_cart_id ON shulesoft.issue_inventory USING btree (product_cart_id);


--
-- TOC entry 13047 (class 1259 OID 53882)
-- Name: fki_issue_lmember_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_issue_lmember_id ON shulesoft.issue USING btree (lmember_id);


--
-- TOC entry 13061 (class 1259 OID 53883)
-- Name: fki_items_vendor_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_items_vendor_id ON shulesoft.items USING btree (vendor_id);


--
-- TOC entry 13062 (class 1259 OID 53884)
-- Name: fki_items_warehouse_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_items_warehouse_id ON shulesoft.items USING btree (warehouse_id);


--
-- TOC entry 13068 (class 1259 OID 53885)
-- Name: fki_journal_account_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_journal_account_id ON shulesoft.journals USING btree (account_id);


--
-- TOC entry 13069 (class 1259 OID 53886)
-- Name: fki_journal_financial_year_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_journal_financial_year_id ON shulesoft.journals USING btree (financial_year_id);


--
-- TOC entry 13075 (class 1259 OID 53887)
-- Name: fki_lesson_plan_teacher_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_lesson_plan_teacher_id ON shulesoft.lesson_plan USING btree (teacher_id);


--
-- TOC entry 13079 (class 1259 OID 53888)
-- Name: fki_liabilities_account_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_liabilities_account_id ON shulesoft.liabilities USING btree (account_id);


--
-- TOC entry 12659 (class 1259 OID 53889)
-- Name: fki_mark_academic_year_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_mark_academic_year_id ON shulesoft.mark USING btree (academic_year_id);


--
-- TOC entry 12660 (class 1259 OID 53890)
-- Name: fki_mark_classes_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_mark_classes_id ON shulesoft.mark USING btree ("classesID");


--
-- TOC entry 12661 (class 1259 OID 53891)
-- Name: fki_mark_exam_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_mark_exam_id ON shulesoft.mark USING btree ("examID");


--
-- TOC entry 12662 (class 1259 OID 53892)
-- Name: fki_mark_student_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_mark_student_id ON shulesoft.mark USING btree (student_id);


--
-- TOC entry 12663 (class 1259 OID 53893)
-- Name: fki_mark_subject_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_mark_subject_id ON shulesoft.mark USING btree ("subjectID");


--
-- TOC entry 13209 (class 1259 OID 53894)
-- Name: fki_proforma_invoices_academic_year_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_proforma_invoices_academic_year_id ON shulesoft.proforma_invoices USING btree (academic_year_id);


--
-- TOC entry 13210 (class 1259 OID 53895)
-- Name: fki_proforma_invoices_student_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_proforma_invoices_student_id ON shulesoft.proforma_invoices USING btree (student_id);


--
-- TOC entry 13286 (class 1259 OID 53896)
-- Name: fki_refer_subject_id_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_refer_subject_id_index ON shulesoft.section_subject_teacher USING btree (refer_subject_id);


--
-- TOC entry 12796 (class 1259 OID 53897)
-- Name: fki_section_classes_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_section_classes_index ON shulesoft.section USING btree ("classesID");


--
-- TOC entry 13287 (class 1259 OID 53898)
-- Name: fki_section_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_section_id ON shulesoft.section_subject_teacher USING btree ("sectionID");


--
-- TOC entry 12797 (class 1259 OID 53899)
-- Name: fki_section_teacher_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_section_teacher_index ON shulesoft.section USING btree ("teacherID");


--
-- TOC entry 12780 (class 1259 OID 53900)
-- Name: fki_student_archive_academic_year_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_student_archive_academic_year_id ON shulesoft.student_archive USING btree (academic_year_id);


--
-- TOC entry 12781 (class 1259 OID 53901)
-- Name: fki_student_archive_section_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_student_archive_section_id ON shulesoft.student_archive USING btree (section_id);


--
-- TOC entry 12782 (class 1259 OID 53902)
-- Name: fki_student_archive_student_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_student_archive_student_id ON shulesoft.student_archive USING btree (student_id);


--
-- TOC entry 13036 (class 1259 OID 53903)
-- Name: fki_student_parents_student_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_student_parents_student_id ON shulesoft.student_parents USING btree (student_id);


--
-- TOC entry 13124 (class 1259 OID 53904)
-- Name: fki_subject_classes_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_subject_classes_id ON shulesoft.subject USING btree ("classesID");


--
-- TOC entry 13125 (class 1259 OID 53905)
-- Name: fki_subject_refer_subject_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_subject_refer_subject_id ON shulesoft.subject USING btree (subject_id);


--
-- TOC entry 12888 (class 1259 OID 53906)
-- Name: fki_subject_section_section_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_subject_section_section_id ON shulesoft.subject_section USING btree (section_id);


--
-- TOC entry 12889 (class 1259 OID 53907)
-- Name: fki_subject_section_subject_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_subject_section_subject_id ON shulesoft.subject_section USING btree (subject_id);


--
-- TOC entry 12893 (class 1259 OID 53908)
-- Name: fki_subject_student_academic_year_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_subject_student_academic_year_id ON shulesoft.subject_student USING btree (academic_year_id);


--
-- TOC entry 12894 (class 1259 OID 53909)
-- Name: fki_subject_student_student_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_subject_student_student_id ON shulesoft.subject_student USING btree (student_id);


--
-- TOC entry 12895 (class 1259 OID 53910)
-- Name: fki_subject_student_subject_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_subject_student_subject_id ON shulesoft.subject_student USING btree (subject_id);


--
-- TOC entry 12695 (class 1259 OID 53911)
-- Name: fki_subject_teacher_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_subject_teacher_id ON shulesoft.teacher USING btree ("teacherID");


--
-- TOC entry 13126 (class 1259 OID 53912)
-- Name: fki_subject_teacher_id_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_subject_teacher_id_index ON shulesoft.subject USING btree ("teacherID");


--
-- TOC entry 13288 (class 1259 OID 53913)
-- Name: fki_teacher_id_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_teacher_id_index ON shulesoft.section_subject_teacher USING btree ("teacherID");


--
-- TOC entry 12705 (class 1259 OID 53914)
-- Name: fki_tmembers_installment_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_tmembers_installment_id ON shulesoft.tmembers USING btree (installment_id);


--
-- TOC entry 12706 (class 1259 OID 53915)
-- Name: fki_tmembers_student_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_tmembers_student_id ON shulesoft.tmembers USING btree (student_id);


--
-- TOC entry 12707 (class 1259 OID 53916)
-- Name: fki_tmembers_transport_route_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_tmembers_transport_route_id ON shulesoft.tmembers USING btree (transport_route_id);


--
-- TOC entry 12708 (class 1259 OID 53917)
-- Name: fki_tmembers_vehicle_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_tmembers_vehicle_id ON shulesoft.tmembers USING btree (vehicle_id);


--
-- TOC entry 12804 (class 1259 OID 53918)
-- Name: fki_transport_route_fees_installment_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_transport_route_fees_installment_id ON shulesoft.transport_routes_fees_installments USING btree (fees_installment_id);


--
-- TOC entry 12805 (class 1259 OID 53919)
-- Name: fki_transport_route_id; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX fki_transport_route_id ON shulesoft.transport_routes_fees_installments USING btree (transport_route_id);


--
-- TOC entry 12991 (class 1259 OID 53920)
-- Name: forum_answer_votes_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX forum_answer_votes_schema_name_index ON shulesoft.forum_answer_votes USING btree (schema_name);


--
-- TOC entry 12994 (class 1259 OID 53921)
-- Name: forum_answers_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX forum_answers_schema_name_index ON shulesoft.forum_answers USING btree (schema_name);


--
-- TOC entry 12997 (class 1259 OID 53922)
-- Name: forum_categories_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX forum_categories_schema_name_index ON shulesoft.forum_categories USING btree (schema_name);


--
-- TOC entry 13000 (class 1259 OID 53923)
-- Name: forum_discussion_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX forum_discussion_schema_name_index ON shulesoft.forum_discussion USING btree (schema_name);


--
-- TOC entry 13003 (class 1259 OID 53924)
-- Name: forum_post_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX forum_post_schema_name_index ON shulesoft.forum_post USING btree (schema_name);


--
-- TOC entry 13006 (class 1259 OID 53925)
-- Name: forum_question_viewers_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX forum_question_viewers_schema_name_index ON shulesoft.forum_question_viewers USING btree (schema_name);


--
-- TOC entry 13012 (class 1259 OID 53926)
-- Name: forum_questions_comments_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX forum_questions_comments_schema_name_index ON shulesoft.forum_questions_comments USING btree (schema_name);


--
-- TOC entry 13009 (class 1259 OID 53927)
-- Name: forum_questions_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX forum_questions_schema_name_index ON shulesoft.forum_questions USING btree (schema_name);


--
-- TOC entry 13015 (class 1259 OID 53928)
-- Name: forum_questions_votes_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX forum_questions_votes_schema_name_index ON shulesoft.forum_questions_votes USING btree (schema_name);


--
-- TOC entry 13018 (class 1259 OID 53929)
-- Name: forum_user_discussion_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX forum_user_discussion_schema_name_index ON shulesoft.forum_user_discussion USING btree (schema_name);


--
-- TOC entry 12639 (class 1259 OID 53930)
-- Name: general_character_assessment_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX general_character_assessment_schema_name_index ON shulesoft.general_character_assessment USING btree (schema_name);


--
-- TOC entry 13021 (class 1259 OID 53931)
-- Name: grade_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX grade_schema_name_index ON shulesoft.grade USING btree (schema_name);


--
-- TOC entry 13024 (class 1259 OID 53932)
-- Name: hattendances_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX hattendances_schema_name_index ON shulesoft.hattendances USING btree (schema_name);


--
-- TOC entry 12644 (class 1259 OID 53933)
-- Name: hmembers_id_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX hmembers_id_index ON shulesoft.hmembers USING btree (id);


--
-- TOC entry 12647 (class 1259 OID 53934)
-- Name: hmembers_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX hmembers_schema_name_index ON shulesoft.hmembers USING btree (schema_name);


--
-- TOC entry 13027 (class 1259 OID 53935)
-- Name: hostel_beds_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX hostel_beds_schema_name_index ON shulesoft.hostel_beds USING btree (schema_name);


--
-- TOC entry 13030 (class 1259 OID 53936)
-- Name: hostel_category_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX hostel_category_schema_name_index ON shulesoft.hostel_category USING btree (schema_name);


--
-- TOC entry 12770 (class 1259 OID 53937)
-- Name: hostel_fees_installments_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX hostel_fees_installments_schema_name_index ON shulesoft.hostel_fees_installments USING btree (schema_name);


--
-- TOC entry 12775 (class 1259 OID 53938)
-- Name: hostels_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX hostels_schema_name_index ON shulesoft.hostels USING btree (schema_name);


--
-- TOC entry 13033 (class 1259 OID 53939)
-- Name: id_cards_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX id_cards_schema_name_index ON shulesoft.id_cards USING btree (schema_name);


--
-- TOC entry 12779 (class 1259 OID 53940)
-- Name: installments_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX installments_schema_name_index ON shulesoft.installments USING btree (schema_name);


--
-- TOC entry 12652 (class 1259 OID 53941)
-- Name: invoice_id_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX invoice_id_index ON shulesoft.invoices USING btree (id);


--
-- TOC entry 13042 (class 1259 OID 53942)
-- Name: invoice_prefix_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX invoice_prefix_schema_name_index ON shulesoft.invoice_prefix USING btree (schema_name);


--
-- TOC entry 13211 (class 1259 OID 53943)
-- Name: invoice_proforma_id_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX invoice_proforma_id_index ON shulesoft.proforma_invoices USING btree (id);


--
-- TOC entry 13045 (class 1259 OID 53944)
-- Name: invoice_settings_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX invoice_settings_schema_name_index ON shulesoft.invoice_settings USING btree (schema_name);


--
-- TOC entry 12790 (class 1259 OID 53945)
-- Name: invoices_fees_installments_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX invoices_fees_installments_schema_name_index ON shulesoft.invoices_fees_installments USING btree (schema_name);


--
-- TOC entry 12655 (class 1259 OID 53946)
-- Name: invoices_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX invoices_schema_name_index ON shulesoft.invoices USING btree (schema_name);


--
-- TOC entry 13054 (class 1259 OID 53947)
-- Name: issue_inventory_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX issue_inventory_schema_name_index ON shulesoft.issue_inventory USING btree (schema_name);


--
-- TOC entry 13050 (class 1259 OID 53948)
-- Name: issue_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX issue_schema_name_index ON shulesoft.issue USING btree (schema_name);


--
-- TOC entry 13065 (class 1259 OID 53949)
-- Name: items_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX items_schema_name_index ON shulesoft.items USING btree (schema_name);


--
-- TOC entry 13074 (class 1259 OID 53950)
-- Name: journals_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX journals_schema_name_index ON shulesoft.journals USING btree (schema_name);


--
-- TOC entry 13078 (class 1259 OID 53951)
-- Name: lesson_plan_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX lesson_plan_schema_name_index ON shulesoft.lesson_plan USING btree (schema_name);


--
-- TOC entry 13082 (class 1259 OID 53952)
-- Name: liabilities_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX liabilities_schema_name_index ON shulesoft.liabilities USING btree (schema_name);


--
-- TOC entry 13085 (class 1259 OID 53953)
-- Name: livestudy_packages_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX livestudy_packages_schema_name_index ON shulesoft.livestudy_packages USING btree (schema_name);


--
-- TOC entry 13088 (class 1259 OID 53954)
-- Name: livestudy_payments_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX livestudy_payments_schema_name_index ON shulesoft.livestudy_payments USING btree (schema_name);


--
-- TOC entry 13091 (class 1259 OID 53955)
-- Name: lmember_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX lmember_schema_name_index ON shulesoft.lmember USING btree (schema_name);


--
-- TOC entry 13094 (class 1259 OID 53956)
-- Name: loan_applications_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX loan_applications_schema_name_index ON shulesoft.loan_applications USING btree (schema_name);


--
-- TOC entry 13097 (class 1259 OID 53957)
-- Name: loan_payments_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX loan_payments_schema_name_index ON shulesoft.loan_payments USING btree (schema_name);


--
-- TOC entry 13100 (class 1259 OID 53958)
-- Name: loan_types_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX loan_types_schema_name_index ON shulesoft.loan_types USING btree (schema_name);


--
-- TOC entry 13103 (class 1259 OID 53959)
-- Name: log_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX log_schema_name_index ON shulesoft.log USING btree (schema_name);


--
-- TOC entry 13106 (class 1259 OID 53960)
-- Name: login_attempts_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX login_attempts_schema_name_index ON shulesoft.login_attempts USING btree (schema_name);


--
-- TOC entry 12658 (class 1259 OID 53961)
-- Name: login_locations_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX login_locations_schema_name_index ON shulesoft.login_locations USING btree (schema_name);


--
-- TOC entry 13109 (class 1259 OID 53962)
-- Name: mailandsms_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX mailandsms_schema_name_index ON shulesoft.mailandsms USING btree (schema_name);


--
-- TOC entry 13112 (class 1259 OID 53963)
-- Name: mailandsmstemplate_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX mailandsmstemplate_schema_name_index ON shulesoft.mailandsmstemplate USING btree (schema_name);


--
-- TOC entry 13115 (class 1259 OID 53964)
-- Name: mailandsmstemplatetag_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX mailandsmstemplatetag_schema_name_index ON shulesoft.mailandsmstemplatetag USING btree (schema_name);


--
-- TOC entry 13118 (class 1259 OID 53965)
-- Name: major_subjects_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX major_subjects_schema_name_index ON shulesoft.major_subjects USING btree (schema_name);


--
-- TOC entry 12666 (class 1259 OID 53966)
-- Name: mark_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX mark_schema_name_index ON shulesoft.mark USING btree (schema_name);


--
-- TOC entry 13135 (class 1259 OID 53967)
-- Name: media_categories_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX media_categories_schema_name_index ON shulesoft.media_categories USING btree (schema_name);


--
-- TOC entry 13138 (class 1259 OID 53968)
-- Name: media_category_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX media_category_schema_name_index ON shulesoft.media_category USING btree (schema_name);


--
-- TOC entry 13141 (class 1259 OID 53969)
-- Name: media_comment_reply_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX media_comment_reply_schema_name_index ON shulesoft.media_comment_reply USING btree (schema_name);


--
-- TOC entry 13144 (class 1259 OID 53970)
-- Name: media_comments_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX media_comments_schema_name_index ON shulesoft.media_comments USING btree (schema_name);


--
-- TOC entry 13147 (class 1259 OID 53971)
-- Name: media_likes_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX media_likes_schema_name_index ON shulesoft.media_likes USING btree (schema_name);


--
-- TOC entry 13153 (class 1259 OID 53972)
-- Name: media_live_comments_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX media_live_comments_schema_name_index ON shulesoft.media_live_comments USING btree (schema_name);


--
-- TOC entry 13150 (class 1259 OID 53973)
-- Name: media_live_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX media_live_schema_name_index ON shulesoft.media_live USING btree (schema_name);


--
-- TOC entry 13132 (class 1259 OID 53974)
-- Name: media_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX media_schema_name_index ON shulesoft.media USING btree (schema_name);


--
-- TOC entry 13156 (class 1259 OID 53975)
-- Name: media_share_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX media_share_schema_name_index ON shulesoft.media_share USING btree (schema_name);


--
-- TOC entry 13164 (class 1259 OID 53976)
-- Name: media_viewers_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX media_viewers_schema_name_index ON shulesoft.media_viewers USING btree (schema_name);


--
-- TOC entry 13167 (class 1259 OID 53977)
-- Name: message_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX message_schema_name_index ON shulesoft.message USING btree (schema_name);


--
-- TOC entry 13172 (class 1259 OID 53978)
-- Name: minor_exam_marks_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX minor_exam_marks_schema_name_index ON shulesoft.minor_exam_marks USING btree (schema_name);


--
-- TOC entry 13175 (class 1259 OID 53979)
-- Name: minor_exams_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX minor_exams_schema_name_index ON shulesoft.minor_exams USING btree (schema_name);


--
-- TOC entry 13178 (class 1259 OID 53980)
-- Name: necta_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX necta_schema_name_index ON shulesoft.necta USING btree (schema_name);


--
-- TOC entry 13181 (class 1259 OID 53981)
-- Name: news_board_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX news_board_schema_name_index ON shulesoft.news_board USING btree (schema_name);


--
-- TOC entry 12669 (class 1259 OID 53982)
-- Name: notice_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX notice_schema_name_index ON shulesoft.notice USING btree (schema_name);


--
-- TOC entry 13184 (class 1259 OID 53983)
-- Name: page_tips_viewers_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX page_tips_viewers_schema_name_index ON shulesoft.page_tips_viewers USING btree (schema_name);


--
-- TOC entry 13187 (class 1259 OID 53984)
-- Name: parent_documents_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX parent_documents_schema_name_index ON shulesoft.parent_documents USING btree (schema_name);


--
-- TOC entry 13190 (class 1259 OID 53985)
-- Name: parent_phones_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX parent_phones_schema_name_index ON shulesoft.parent_phones USING btree (schema_name);


--
-- TOC entry 12672 (class 1259 OID 53986)
-- Name: parent_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX parent_schema_name_index ON shulesoft.parent USING btree (schema_name);


--
-- TOC entry 12730 (class 1259 OID 53987)
-- Name: payment_types_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX payment_types_schema_name_index ON shulesoft.payment_types USING btree (schema_name);


--
-- TOC entry 12793 (class 1259 OID 53988)
-- Name: payments_invoices_fees_installments_invoie_inst_id_foreign_inde; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX payments_invoices_fees_installments_invoie_inst_id_foreign_inde ON shulesoft.payments_invoices_fees_installments USING btree (invoices_fees_installment_id);


--
-- TOC entry 12794 (class 1259 OID 53989)
-- Name: payments_invoices_fees_installments_payment_id_id_foreign_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX payments_invoices_fees_installments_payment_id_id_foreign_index ON shulesoft.payments_invoices_fees_installments USING btree (payment_id);


--
-- TOC entry 12795 (class 1259 OID 53990)
-- Name: payments_invoices_fees_installments_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX payments_invoices_fees_installments_schema_name_index ON shulesoft.payments_invoices_fees_installments USING btree (schema_name);


--
-- TOC entry 12733 (class 1259 OID 53991)
-- Name: payments_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX payments_schema_name_index ON shulesoft.payments USING btree (schema_name);


--
-- TOC entry 12734 (class 1259 OID 53992)
-- Name: payments_student_id_foreign_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX payments_student_id_foreign_index ON shulesoft.payments USING btree (student_id);


--
-- TOC entry 13193 (class 1259 OID 53993)
-- Name: payroll_setting_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX payroll_setting_schema_name_index ON shulesoft.payroll_setting USING btree (schema_name);


--
-- TOC entry 13196 (class 1259 OID 53994)
-- Name: payslip_settings_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX payslip_settings_schema_name_index ON shulesoft.payslip_settings USING btree (schema_name);


--
-- TOC entry 13199 (class 1259 OID 53995)
-- Name: pensions_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX pensions_schema_name_index ON shulesoft.pensions USING btree (schema_name);


--
-- TOC entry 13202 (class 1259 OID 53996)
-- Name: prepayments_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX prepayments_schema_name_index ON shulesoft.prepayments USING btree (schema_name);


--
-- TOC entry 12675 (class 1259 OID 53997)
-- Name: product_alert_quantity_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX product_alert_quantity_schema_name_index ON shulesoft.product_alert_quantity USING btree (schema_name);


--
-- TOC entry 12985 (class 1259 OID 53998)
-- Name: product_cart_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX product_cart_schema_name_index ON shulesoft.product_cart USING btree (schema_name);


--
-- TOC entry 12959 (class 1259 OID 53999)
-- Name: product_purchases_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX product_purchases_schema_name_index ON shulesoft.product_purchases USING btree (schema_name);


--
-- TOC entry 13208 (class 1259 OID 54000)
-- Name: product_registers_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX product_registers_schema_name_index ON shulesoft.product_registers USING btree (schema_name);


--
-- TOC entry 13057 (class 1259 OID 54001)
-- Name: product_sales_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX product_sales_schema_name_index ON shulesoft.product_sales USING btree (schema_name);


--
-- TOC entry 13215 (class 1259 OID 54002)
-- Name: proforma_fee_id_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX proforma_fee_id_index ON shulesoft.proforma_invoices_fee_amount USING btree (fee_id);


--
-- TOC entry 13216 (class 1259 OID 54003)
-- Name: proforma_invoice_id_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX proforma_invoice_id_index ON shulesoft.proforma_invoices_fee_amount USING btree (proforma_invoice_id);


--
-- TOC entry 13217 (class 1259 OID 54004)
-- Name: proforma_invoices_fee_amount_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX proforma_invoices_fee_amount_schema_name_index ON shulesoft.proforma_invoices_fee_amount USING btree (schema_name);


--
-- TOC entry 13222 (class 1259 OID 54005)
-- Name: proforma_invoices_fees_installments_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX proforma_invoices_fees_installments_schema_name_index ON shulesoft.proforma_invoices_fees_installments USING btree (schema_name);


--
-- TOC entry 13214 (class 1259 OID 54006)
-- Name: proforma_invoices_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX proforma_invoices_schema_name_index ON shulesoft.proforma_invoices USING btree (schema_name);


--
-- TOC entry 13225 (class 1259 OID 54007)
-- Name: proforma_payments_invoice_id_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX proforma_payments_invoice_id_index ON shulesoft.proforma_payments USING btree (proforma_invoice_id);


--
-- TOC entry 13226 (class 1259 OID 54008)
-- Name: proforma_payments_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX proforma_payments_schema_name_index ON shulesoft.proforma_payments USING btree (schema_name);


--
-- TOC entry 13227 (class 1259 OID 54009)
-- Name: proforma_payments_student_id_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX proforma_payments_student_id_index ON shulesoft.proforma_payments USING btree (student_id);


--
-- TOC entry 13230 (class 1259 OID 54010)
-- Name: promotionsubject_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX promotionsubject_schema_name_index ON shulesoft.promotionsubject USING btree (schema_name);


--
-- TOC entry 13233 (class 1259 OID 54011)
-- Name: questions_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX questions_schema_name_index ON shulesoft.questions USING btree (schema_name);


--
-- TOC entry 13236 (class 1259 OID 54012)
-- Name: receipt_settings_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX receipt_settings_schema_name_index ON shulesoft.receipt_settings USING btree (schema_name);


--
-- TOC entry 13239 (class 1259 OID 54013)
-- Name: refer_character_grading_systems_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX refer_character_grading_systems_schema_name_index ON shulesoft.refer_character_grading_systems USING btree (schema_name);


--
-- TOC entry 13242 (class 1259 OID 54014)
-- Name: refer_exam_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX refer_exam_schema_name_index ON shulesoft.refer_exam USING btree (schema_name);


--
-- TOC entry 12840 (class 1259 OID 54015)
-- Name: refer_expense_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX refer_expense_schema_name_index ON shulesoft.refer_expense USING btree (schema_name);


--
-- TOC entry 13123 (class 1259 OID 54016)
-- Name: refer_subject_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX refer_subject_schema_name_index ON shulesoft.refer_subject USING btree (schema_name);


--
-- TOC entry 13245 (class 1259 OID 54017)
-- Name: reminders_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX reminders_schema_name_index ON shulesoft.reminders USING btree (schema_name);


--
-- TOC entry 13248 (class 1259 OID 54018)
-- Name: reply_msg_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX reply_msg_schema_name_index ON shulesoft.reply_msg USING btree (schema_name);


--
-- TOC entry 13251 (class 1259 OID 54019)
-- Name: reply_sms_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX reply_sms_schema_name_index ON shulesoft.reply_sms USING btree (schema_name);


--
-- TOC entry 13254 (class 1259 OID 54020)
-- Name: reset_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX reset_schema_name_index ON shulesoft.reset USING btree (schema_name);


--
-- TOC entry 13257 (class 1259 OID 54021)
-- Name: revenue_account_foreign_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX revenue_account_foreign_index ON shulesoft.revenue USING btree (account_id);


--
-- TOC entry 13262 (class 1259 OID 54022)
-- Name: revenue_cart_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX revenue_cart_schema_name_index ON shulesoft.revenue_cart USING btree (schema_name);


--
-- TOC entry 13258 (class 1259 OID 54023)
-- Name: revenue_refer_expense_id_foreign_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX revenue_refer_expense_id_foreign_index ON shulesoft.revenue USING btree (refer_expense_id);


--
-- TOC entry 13259 (class 1259 OID 54024)
-- Name: revenue_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX revenue_schema_name_index ON shulesoft.revenue USING btree (schema_name);


--
-- TOC entry 12737 (class 1259 OID 54025)
-- Name: revenues_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX revenues_schema_name_index ON shulesoft.revenues USING btree (schema_name);


--
-- TOC entry 13265 (class 1259 OID 54026)
-- Name: role_permission_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX role_permission_schema_name_index ON shulesoft.role_permission USING btree (schema_name);


--
-- TOC entry 13060 (class 1259 OID 54027)
-- Name: role_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX role_schema_name_index ON shulesoft.role USING btree (schema_name);


--
-- TOC entry 13268 (class 1259 OID 54028)
-- Name: route_vehicle_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX route_vehicle_schema_name_index ON shulesoft.route_vehicle USING btree (schema_name);


--
-- TOC entry 13274 (class 1259 OID 54029)
-- Name: routine_daily_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX routine_daily_schema_name_index ON shulesoft.routine_daily USING btree (schema_name);


--
-- TOC entry 13271 (class 1259 OID 54030)
-- Name: routine_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX routine_schema_name_index ON shulesoft.routine USING btree (schema_name);


--
-- TOC entry 12678 (class 1259 OID 54031)
-- Name: salaries_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX salaries_schema_name_index ON shulesoft.salaries USING btree (schema_name);


--
-- TOC entry 13277 (class 1259 OID 54032)
-- Name: salary_allowances_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX salary_allowances_schema_name_index ON shulesoft.salary_allowances USING btree (schema_name);


--
-- TOC entry 13280 (class 1259 OID 54033)
-- Name: salary_deductions_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX salary_deductions_schema_name_index ON shulesoft.salary_deductions USING btree (schema_name);


--
-- TOC entry 13283 (class 1259 OID 54034)
-- Name: salary_pensions_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX salary_pensions_schema_name_index ON shulesoft.salary_pensions USING btree (schema_name);


--
-- TOC entry 12681 (class 1259 OID 54035)
-- Name: sattendances_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX sattendances_schema_name_index ON shulesoft.sattendances USING btree (schema_name);


--
-- TOC entry 12800 (class 1259 OID 54036)
-- Name: section_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX section_schema_name_index ON shulesoft.section USING btree (schema_name);


--
-- TOC entry 13291 (class 1259 OID 54037)
-- Name: section_subject_teacher_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX section_subject_teacher_schema_name_index ON shulesoft.section_subject_teacher USING btree (schema_name);


--
-- TOC entry 13294 (class 1259 OID 54038)
-- Name: semester_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX semester_schema_name_index ON shulesoft.semester USING btree (schema_name);


--
-- TOC entry 12684 (class 1259 OID 54039)
-- Name: setting_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX setting_schema_name_index ON shulesoft.setting USING btree (schema_name);


--
-- TOC entry 13302 (class 1259 OID 54040)
-- Name: sms_content_channels_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX sms_content_channels_schema_name_index ON shulesoft.sms_content_channels USING btree (schema_name);


--
-- TOC entry 13299 (class 1259 OID 54041)
-- Name: sms_content_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX sms_content_schema_name_index ON shulesoft.sms_content USING btree (schema_name);


--
-- TOC entry 13305 (class 1259 OID 54042)
-- Name: sms_files_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX sms_files_schema_name_index ON shulesoft.sms_files USING btree (schema_name);


--
-- TOC entry 13308 (class 1259 OID 54043)
-- Name: sms_keys_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX sms_keys_schema_name_index ON shulesoft.sms_keys USING btree (schema_name);


--
-- TOC entry 12689 (class 1259 OID 54044)
-- Name: sms_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX sms_schema_name_index ON shulesoft.sms USING btree (schema_name);


--
-- TOC entry 13311 (class 1259 OID 54045)
-- Name: smssettings_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX smssettings_schema_name_index ON shulesoft.smssettings USING btree (schema_name);


--
-- TOC entry 13314 (class 1259 OID 54046)
-- Name: special_grade_names_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX special_grade_names_schema_name_index ON shulesoft.special_grade_names USING btree (schema_name);


--
-- TOC entry 13317 (class 1259 OID 54047)
-- Name: special_grades_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX special_grades_schema_name_index ON shulesoft.special_grades USING btree (schema_name);


--
-- TOC entry 13320 (class 1259 OID 54048)
-- Name: special_promotion_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX special_promotion_schema_name_index ON shulesoft.special_promotion USING btree (schema_name);


--
-- TOC entry 13323 (class 1259 OID 54049)
-- Name: sponsors_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX sponsors_schema_name_index ON shulesoft.sponsors USING btree (schema_name);


--
-- TOC entry 13326 (class 1259 OID 54050)
-- Name: staff_leave_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX staff_leave_schema_name_index ON shulesoft.staff_leave USING btree (schema_name);


--
-- TOC entry 13329 (class 1259 OID 54051)
-- Name: staff_report_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX staff_report_schema_name_index ON shulesoft.staff_report USING btree (schema_name);


--
-- TOC entry 13335 (class 1259 OID 54052)
-- Name: staff_targets_reports_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX staff_targets_reports_schema_name_index ON shulesoft.staff_targets_reports USING btree (schema_name);


--
-- TOC entry 13332 (class 1259 OID 54053)
-- Name: staff_targets_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX staff_targets_schema_name_index ON shulesoft.staff_targets USING btree (schema_name);


--
-- TOC entry 13336 (class 1259 OID 54054)
-- Name: store_student_id_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX store_student_id_schema_name_index ON shulesoft.store_students_id USING btree (schema_name);


--
-- TOC entry 13341 (class 1259 OID 54055)
-- Name: student_addresses_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX student_addresses_schema_name_index ON shulesoft.student_addresses USING btree (schema_name);


--
-- TOC entry 12785 (class 1259 OID 54056)
-- Name: student_archive_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX student_archive_schema_name_index ON shulesoft.student_archive USING btree (schema_name);


--
-- TOC entry 13347 (class 1259 OID 54057)
-- Name: student_assessment_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX student_assessment_schema_name_index ON shulesoft.student_assessment USING btree (schema_name);


--
-- TOC entry 13350 (class 1259 OID 54058)
-- Name: student_characters_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX student_characters_schema_name_index ON shulesoft.student_characters USING btree (schema_name);


--
-- TOC entry 13353 (class 1259 OID 54059)
-- Name: student_due_date_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX student_due_date_schema_name_index ON shulesoft.student_due_date USING btree (schema_name);


--
-- TOC entry 13356 (class 1259 OID 54060)
-- Name: student_duties_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX student_duties_schema_name_index ON shulesoft.student_duties USING btree (schema_name);


--
-- TOC entry 13359 (class 1259 OID 54061)
-- Name: student_fee_subscription_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX student_fee_subscription_schema_name_index ON shulesoft.student_fee_subscription USING btree (schema_name);


--
-- TOC entry 13344 (class 1259 OID 54062)
-- Name: student_fees_installments_unsubscriptions_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX student_fees_installments_unsubscriptions_schema_name_index ON shulesoft.student_fees_installments_unsubscriptions USING btree (schema_name);


--
-- TOC entry 13367 (class 1259 OID 54063)
-- Name: student_other_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX student_other_schema_name_index ON shulesoft.student_other USING btree (schema_name);


--
-- TOC entry 13039 (class 1259 OID 54064)
-- Name: student_parents_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX student_parents_schema_name_index ON shulesoft.student_parents USING btree (schema_name);


--
-- TOC entry 13370 (class 1259 OID 54065)
-- Name: student_reams_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX student_reams_schema_name_index ON shulesoft.student_reams USING btree (schema_name);


--
-- TOC entry 13373 (class 1259 OID 54066)
-- Name: student_report_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX student_report_schema_name_index ON shulesoft.student_report USING btree (schema_name);


--
-- TOC entry 12692 (class 1259 OID 54067)
-- Name: student_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX student_schema_name_index ON shulesoft.student USING btree (schema_name);


--
-- TOC entry 13376 (class 1259 OID 54068)
-- Name: student_sponsors_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX student_sponsors_schema_name_index ON shulesoft.student_sponsors USING btree (schema_name);


--
-- TOC entry 13379 (class 1259 OID 54069)
-- Name: student_status_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX student_status_schema_name_index ON shulesoft.student_status USING btree (schema_name);


--
-- TOC entry 13382 (class 1259 OID 54070)
-- Name: subject_mark_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX subject_mark_schema_name_index ON shulesoft.subject_mark USING btree (schema_name);


--
-- TOC entry 13129 (class 1259 OID 54071)
-- Name: subject_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX subject_schema_name_index ON shulesoft.subject USING btree (schema_name);


--
-- TOC entry 12892 (class 1259 OID 54072)
-- Name: subject_section_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX subject_section_schema_name_index ON shulesoft.subject_section USING btree (schema_name);


--
-- TOC entry 12898 (class 1259 OID 54073)
-- Name: subject_student_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX subject_student_schema_name_index ON shulesoft.subject_student USING btree (schema_name);


--
-- TOC entry 13385 (class 1259 OID 54074)
-- Name: subject_topic_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX subject_topic_schema_name_index ON shulesoft.subject_topic USING btree (schema_name);


--
-- TOC entry 13388 (class 1259 OID 54075)
-- Name: submit_files_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX submit_files_schema_name_index ON shulesoft.submit_files USING btree (schema_name);


--
-- TOC entry 13391 (class 1259 OID 54076)
-- Name: syllabus_benchmarks_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX syllabus_benchmarks_schema_name_index ON shulesoft.syllabus_benchmarks USING btree (schema_name);


--
-- TOC entry 13394 (class 1259 OID 54077)
-- Name: syllabus_objective_references_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX syllabus_objective_references_schema_name_index ON shulesoft.syllabus_objective_references USING btree (schema_name);


--
-- TOC entry 13397 (class 1259 OID 54078)
-- Name: syllabus_objectives_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX syllabus_objectives_schema_name_index ON shulesoft.syllabus_objectives USING btree (schema_name);


--
-- TOC entry 13400 (class 1259 OID 54079)
-- Name: syllabus_student_benchmarking_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX syllabus_student_benchmarking_schema_name_index ON shulesoft.syllabus_student_benchmarking USING btree (schema_name);


--
-- TOC entry 13403 (class 1259 OID 54080)
-- Name: syllabus_subtopics_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX syllabus_subtopics_schema_name_index ON shulesoft.syllabus_subtopics USING btree (schema_name);


--
-- TOC entry 13406 (class 1259 OID 54081)
-- Name: syllabus_subtopics_teachers_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX syllabus_subtopics_teachers_schema_name_index ON shulesoft.syllabus_subtopics_teachers USING btree (schema_name);


--
-- TOC entry 13161 (class 1259 OID 54082)
-- Name: syllabus_topics_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX syllabus_topics_schema_name_index ON shulesoft.syllabus_topics USING btree (schema_name);


--
-- TOC entry 13409 (class 1259 OID 54083)
-- Name: tattendance_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX tattendance_schema_name_index ON shulesoft.tattendance USING btree (schema_name);


--
-- TOC entry 13412 (class 1259 OID 54084)
-- Name: tattendances_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX tattendances_schema_name_index ON shulesoft.tattendances USING btree (schema_name);


--
-- TOC entry 12704 (class 1259 OID 54085)
-- Name: teacher_duties_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX teacher_duties_schema_name_index ON shulesoft.teacher_duties USING btree (schema_name);


--
-- TOC entry 12698 (class 1259 OID 54086)
-- Name: teacher_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX teacher_schema_name_index ON shulesoft.teacher USING btree (schema_name);


--
-- TOC entry 13415 (class 1259 OID 54087)
-- Name: tempfiles_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX tempfiles_schema_name_index ON shulesoft.tempfiles USING btree (schema_name);


--
-- TOC entry 12711 (class 1259 OID 54088)
-- Name: tmembers_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX tmembers_schema_name_index ON shulesoft.tmembers USING btree (schema_name);


--
-- TOC entry 13420 (class 1259 OID 54089)
-- Name: topic_mark_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX topic_mark_schema_name_index ON shulesoft.topic_mark USING btree (schema_name);


--
-- TOC entry 13423 (class 1259 OID 54090)
-- Name: tour_users_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX tour_users_schema_name_index ON shulesoft.tour_users USING btree (schema_name);


--
-- TOC entry 13426 (class 1259 OID 54091)
-- Name: tours_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX tours_schema_name_index ON shulesoft.tours USING btree (schema_name);


--
-- TOC entry 13435 (class 1259 OID 54092)
-- Name: track_invoices_fees_installments_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX track_invoices_fees_installments_schema_name_index ON shulesoft.track_invoices_fees_installments USING btree (schema_name);


--
-- TOC entry 13432 (class 1259 OID 54093)
-- Name: track_invoices_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX track_invoices_schema_name_index ON shulesoft.track_invoices USING btree (schema_name);


--
-- TOC entry 13438 (class 1259 OID 54094)
-- Name: track_payments_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX track_payments_schema_name_index ON shulesoft.track_payments USING btree (schema_name);


--
-- TOC entry 13429 (class 1259 OID 54095)
-- Name: track_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX track_schema_name_index ON shulesoft.track USING btree (schema_name);


--
-- TOC entry 13441 (class 1259 OID 54096)
-- Name: trainings_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX trainings_schema_name_index ON shulesoft.trainings USING btree (schema_name);


--
-- TOC entry 13445 (class 1259 OID 54097)
-- Name: transport_installment_fee_installment_id_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX transport_installment_fee_installment_id_index ON shulesoft.transport_installment USING btree (fee_installment_id);


--
-- TOC entry 13448 (class 1259 OID 54098)
-- Name: transport_installment_installment_id_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX transport_installment_installment_id_index ON shulesoft.transport_installment USING btree (installment_id);


--
-- TOC entry 13449 (class 1259 OID 54099)
-- Name: transport_installment_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX transport_installment_schema_name_index ON shulesoft.transport_installment USING btree (schema_name);


--
-- TOC entry 12808 (class 1259 OID 54100)
-- Name: transport_routes_fees_installments_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX transport_routes_fees_installments_schema_name_index ON shulesoft.transport_routes_fees_installments USING btree (schema_name);


--
-- TOC entry 12803 (class 1259 OID 54101)
-- Name: transport_routes_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX transport_routes_schema_name_index ON shulesoft.transport_routes USING btree (schema_name);


--
-- TOC entry 13444 (class 1259 OID 54102)
-- Name: transport_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX transport_schema_name_index ON shulesoft.transport USING btree (schema_name);


--
-- TOC entry 13452 (class 1259 OID 54103)
-- Name: uattendances_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX uattendances_schema_name_index ON shulesoft.uattendances USING btree (schema_name);


--
-- TOC entry 13455 (class 1259 OID 54104)
-- Name: user_allowances_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX user_allowances_schema_name_index ON shulesoft.user_allowances USING btree (schema_name);


--
-- TOC entry 13458 (class 1259 OID 54105)
-- Name: user_contract_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX user_contract_schema_name_index ON shulesoft.user_contract USING btree (schema_name);


--
-- TOC entry 13461 (class 1259 OID 54106)
-- Name: user_deductions_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX user_deductions_schema_name_index ON shulesoft.user_deductions USING btree (schema_name);


--
-- TOC entry 13464 (class 1259 OID 54107)
-- Name: user_devices_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX user_devices_schema_name_index ON shulesoft.user_devices USING btree (schema_name);


--
-- TOC entry 13467 (class 1259 OID 54108)
-- Name: user_pensions_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX user_pensions_schema_name_index ON shulesoft.user_pensions USING btree (schema_name);


--
-- TOC entry 13470 (class 1259 OID 54109)
-- Name: user_phones_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX user_phones_schema_name_index ON shulesoft.user_phones USING btree (schema_name);


--
-- TOC entry 13473 (class 1259 OID 54110)
-- Name: user_reminders_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX user_reminders_schema_name_index ON shulesoft.user_reminders USING btree (schema_name);


--
-- TOC entry 13476 (class 1259 OID 54111)
-- Name: user_role_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX user_role_schema_name_index ON shulesoft.user_role USING btree (schema_name);


--
-- TOC entry 12740 (class 1259 OID 54112)
-- Name: user_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX user_schema_name_index ON shulesoft."user" USING btree (schema_name);


--
-- TOC entry 13479 (class 1259 OID 54113)
-- Name: user_updates_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX user_updates_schema_name_index ON shulesoft.user_updates USING btree (schema_name);


--
-- TOC entry 13482 (class 1259 OID 54114)
-- Name: valid_answers_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX valid_answers_schema_name_index ON shulesoft.valid_answers USING btree (schema_name);


--
-- TOC entry 13362 (class 1259 OID 54115)
-- Name: vehicles_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX vehicles_schema_name_index ON shulesoft.vehicles USING btree (schema_name);


--
-- TOC entry 13485 (class 1259 OID 54116)
-- Name: vendors_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX vendors_schema_name_index ON shulesoft.vendors USING btree (schema_name);


--
-- TOC entry 13488 (class 1259 OID 54117)
-- Name: wallet_cart_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX wallet_cart_schema_name_index ON shulesoft.wallet_cart USING btree (schema_name);


--
-- TOC entry 13491 (class 1259 OID 54118)
-- Name: wallet_uses_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX wallet_uses_schema_name_index ON shulesoft.wallet_uses USING btree (schema_name);


--
-- TOC entry 13494 (class 1259 OID 54119)
-- Name: wallets_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX wallets_schema_name_index ON shulesoft.wallets USING btree (schema_name);


--
-- TOC entry 13205 (class 1259 OID 54120)
-- Name: warehouse_transfers_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX warehouse_transfers_schema_name_index ON shulesoft.warehouse_transfers USING btree (schema_name);


--
-- TOC entry 13499 (class 1259 OID 54121)
-- Name: warehouses_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX warehouses_schema_name_index ON shulesoft.warehouses USING btree (schema_name);


--
-- TOC entry 13502 (class 1259 OID 54122)
-- Name: youtube_access_tokens_schema_name_index; Type: INDEX; Schema: shulesoft; Owner: postgres
--

CREATE INDEX youtube_access_tokens_schema_name_index ON shulesoft.youtube_access_tokens USING btree (schema_name);


--
-- TOC entry 13945 (class 2618 OID 54847)
-- Name: client_payment_status _RETURN; Type: RULE; Schema: shulesoft; Owner: postgres
--

CREATE OR REPLACE VIEW shulesoft.client_payment_status AS
 SELECT a.student_id,
    a.username,
    a.name,
    b.created_at,
    b.due_date,
    b.invoice_year,
    COALESCE(( SELECT sum(proforma_invoices_fee_amount.amount) AS sum
           FROM shulesoft.proforma_invoices_fee_amount
          WHERE (proforma_invoices_fee_amount.proforma_invoice_id = b.id)), (0)::numeric) AS invoiced_amount,
    COALESCE(sum(d.amount), (0)::numeric) AS paid_amount,
    (COALESCE(( SELECT sum(proforma_invoices_fee_amount.amount) AS sum
           FROM shulesoft.proforma_invoices_fee_amount
          WHERE (proforma_invoices_fee_amount.proforma_invoice_id = b.id)), (0)::numeric) - COALESCE(sum(d.amount), (0)::numeric)) AS pending_balance,
    a.status_id AS payment_status
   FROM ((shulesoft.student a
     LEFT JOIN shulesoft.proforma_invoices b ON ((b.student_id = a.student_id)))
     LEFT JOIN shulesoft.proforma_payments d ON ((d.student_id = a.student_id)))
  WHERE ((a.status = 1) AND ((a.schema_name)::text = 'shulesoft'::text))
  GROUP BY a.student_id, a.name, b.id, a.username, b.created_at, b.due_date, b.invoice_year;


ALTER TABLE shulesoft.sessions
    ALTER COLUMN last_activity TYPE timestamp without time zone
    USING to_timestamp(last_activity);

    create materialized view shulesoft.material_invoice_balance as
select * from shulesoft.invoice_balance;

CREATE TABLE shulesoft.school_creation_requests (
    id SERIAL PRIMARY KEY,
    school_name VARCHAR(255) NOT NULL,
    location VARCHAR(255),
    contact_person VARCHAR(255),
    contact_email VARCHAR(255) UNIQUE,
    contact_phone VARCHAR(50),
    status VARCHAR(50) DEFAULT 'pending',
	connect_user_id integer,
    requested_at TIMESTAMP DEFAULT NOW(),
	created_at timestamp without time zone,
	updated_at timestamp without time zone
);