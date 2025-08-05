PRAGMA foreign_keys=OFF;
CREATE TABLE IF NOT EXISTS "migrations" ("id" integer primary key autoincrement not null, "migration" varchar not null, "batch" integer not null);
INSERT INTO migrations VALUES(4,'2025_02_13_003908_create_rendez_vous_poneys_table',1);
INSERT INTO migrations VALUES(5,'2025_02_13_003917_create_rendez_vous_table',1);
INSERT INTO migrations VALUES(9,'2025_02_13_003941_create_poneys_table',2);
INSERT INTO migrations VALUES(10,'2025_02_13_003954_create_facturations_table',2);
INSERT INTO migrations VALUES(11,'2025_02_13_004000_create_clients_table',2);
INSERT INTO migrations VALUES(15,'0001_01_01_000000_create_users_table',3);
INSERT INTO migrations VALUES(16,'0001_01_01_000001_create_cache_table',3);
CREATE TABLE rendez_vous_poneys (
    id             INTEGER PRIMARY KEY AUTOINCREMENT,
    rendez_vous_id INTEGER NOT NULL REFERENCES rendez_vous(id) ON DELETE CASCADE,
    poney_id       INTEGER NOT NULL REFERENCES poneys(id) ON DELETE CASCADE,
    created_at     DATETIME,
    updated_at     DATETIME
);
INSERT INTO rendez_vous_poneys VALUES(39,19,3,NULL,NULL);
INSERT INTO rendez_vous_poneys VALUES(40,19,6,NULL,NULL);
INSERT INTO rendez_vous_poneys VALUES(44,23,7,NULL,NULL);
INSERT INTO rendez_vous_poneys VALUES(45,23,8,NULL,NULL);
CREATE TABLE IF NOT EXISTS "rendez_vous" ("id" integer primary key autoincrement not null, "client_id" integer not null, "horaire_debut" time not null, "horaire_fin" time not null, "nombre_personnes" integer not null, "confirmed" tinyint(1) not null default ('0'), "created_at" datetime, "updated_at" datetime);
INSERT INTO rendez_vous VALUES(19,8,'2025-02-18 10:20:00','2025-02-18 10:40:00',2,0,'2025-02-18 19:09:02','2025-02-18 19:09:02');
INSERT INTO rendez_vous VALUES(23,15,'2025-03-01 14:20:00','2025-03-01 14:40:00',2,0,'2025-03-01 14:38:48','2025-03-01 14:38:48');
CREATE TABLE IF NOT EXISTS "poneys" ("id" integer primary key autoincrement not null, "nom" varchar not null, "disponible" tinyint(1) not null default '1', "heures_travail_validee" integer not null default '0', "created_at" datetime, "updated_at" datetime);
INSERT INTO poneys VALUES(3,'Doudoune',0,1,'2025-02-13 02:55:08','2025-02-18 19:09:02');
INSERT INTO poneys VALUES(6,'Loïse',0,3,'2025-02-14 22:25:08','2025-02-18 19:09:02');
INSERT INTO poneys VALUES(7,'Lourdo',0,3,'2025-02-14 22:25:18','2025-03-01 14:38:48');
INSERT INTO poneys VALUES(8,'Final Test',0,5,'2025-02-18 19:11:01','2025-03-01 14:38:48');
INSERT INTO poneys VALUES(9,'Pegaz',1,2,'2025-02-18 19:41:25','2025-03-01 14:40:13');
INSERT INTO poneys VALUES(10,'poneytest',1,3,'2025-03-01 14:43:43','2025-03-01 14:43:43');
CREATE TABLE IF NOT EXISTS "clients" ("id" integer primary key autoincrement not null, "nom" varchar not null, "nombre_personnes" integer not null default '1', "minutes" integer not null default '0', "prix_total" numeric not null default '0', "created_at" datetime, "updated_at" datetime);
INSERT INTO clients VALUES(7,'Association EkiZen',2,16,160,'2025-02-14 17:52:57','2025-02-14 22:22:45');
INSERT INTO clients VALUES(8,'Ecurie des Vents',2,40,200,'2025-01-08 14:20:30','2025-01-08 14:20:30');
INSERT INTO clients VALUES(9,'Centre Équestre Lumière',1,18,90,'2025-01-12 09:50:05','2025-01-12 09:50:05');
INSERT INTO clients VALUES(10,'Les Cavaliers Libres',2,30,150,'2025-01-20 11:45:58','2025-01-20 11:45:58');
INSERT INTO clients VALUES(11,'Poney Club du Soleil',1,20,100,'2025-01-24 08:25:45','2025-01-24 08:25:45');
INSERT INTO clients VALUES(12,'Ranch des Étoiles',2,45,225,'2025-01-28 18:35:25','2025-01-28 18:35:25');
INSERT INTO clients VALUES(13,'Octhama',1,14,70,'2025-02-14 21:24:06','2025-02-14 21:24:22');
INSERT INTO clients VALUES(14,'Final Test',1,12,60,'2025-02-18 18:58:17','2025-02-18 18:59:25');
INSERT INTO clients VALUES(15,'client test',2,13,130,'2025-03-01 14:35:50','2025-03-01 14:35:50');
CREATE TABLE IF NOT EXISTS "users" ("id" integer primary key autoincrement not null, "name" varchar not null, "email" varchar not null, "password" varchar not null, "role" varchar not null default 'employee', "created_at" datetime, "updated_at" datetime);
INSERT INTO users VALUES(1,'Octhama','octhama@hypotherapp.com','$2y$12$Xf7P4KJ/4ZBO8bEyM6.rc.80gBYJhqTR032AkC8Z5YisInb4g/mlO','admin','2025-02-13 02:54:08','2025-02-13 02:54:08');
INSERT INTO users VALUES(2,'Employee 2','employe2@example.com','$2y$12$0EMxPuUTv/3W9XbhUs06ReNS2i8469uwbBSAiPCulNzPmOqqTIQmi','employee','2025-02-13 03:04:40','2025-02-13 03:04:40');
INSERT INTO users VALUES(3,'maitrepylos','maitrepylos.test@test.com','$2y$12$tAE/TeDs2pCpiNbWo2FsXuBerPaJz6VIpRkv3Yyxx4De0WDraSjRG','admin','2025-03-01 14:34:04','2025-03-01 14:34:04');
CREATE TABLE IF NOT EXISTS "sessions" ("id" varchar not null, "user_id" integer, "ip_address" varchar, "user_agent" text, "payload" text not null, "last_activity" integer not null, primary key ("id"));
INSERT INTO sessions VALUES('xRFQ8SPFO4z8wjQZvkcGjpoHjnYXxZj0UTjQErTQ',3,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.3 Safari/605.1.15','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiS1VnTndoZEdUOUQ3enExVUNyRjZCRkpIZ3hPWWkyeFd0ZmVTOEkycyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZW5kZXotdm91cyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7fQ==',1740840280);
INSERT INTO sessions VALUES('MoN1s6NJRy6SREoaBA7vz7vRuSBJfMzQoQbVDmGH',1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:135.0) Gecko/20100101 Firefox/135.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiM1VvcTd1U1ROWDk4TmtmNE9FdUtVWEY5MUlYaG5nVGN2V3lSVWp5WiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yYXBwb3J0cyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1741076759);
INSERT INTO sessions VALUES('PNC9HWJOVKyGmBh4tEOdiNYrn3dQ8kwSu173I1Fq',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:138.0) Gecko/20100101 Firefox/138.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZHRBN1hyOHhtS2FwQ2lmbFV5amNKWVVUd29mcXY4TW5QTWxFMVU2UCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1746493785);
INSERT INTO sessions VALUES('7NCDEc5PWSpI2QXP0kaTaLolrTyqxikPRNXvNCLe',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOTVETU1GWTBCdG9LdkhQY3ZwOVJ0SEdzTEtkWG16WXNyWmpXaUhJViI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1746498672);
CREATE TABLE IF NOT EXISTS "facturations"
(
    id             integer             not null
        primary key autoincrement,
    client_id      integer             not null
        references clients
            on delete cascade,
    nombre_minutes integer default '0' not null,
    montant        numeric default '0' not null,
    created_at     datetime,
    updated_at     datetime
);
INSERT INTO facturations VALUES(2,8,40,200,'2025-01-08 14:20:30','2025-01-08 14:20:30');
INSERT INTO facturations VALUES(3,9,18,90,'2025-01-12 09:50:05','2025-01-12 09:50:05');
INSERT INTO facturations VALUES(4,10,30,150,'2025-01-20 11:45:58','2025-01-20 11:45:58');
INSERT INTO facturations VALUES(5,11,20,100,'2025-01-24 08:25:45','2025-01-24 08:25:45');
INSERT INTO facturations VALUES(6,12,45,225,'2025-01-28 18:35:25','2025-01-28 18:35:25');
INSERT INTO facturations VALUES(7,7,16,160,'2025-02-14 17:52:57','2025-02-14 22:22:45');
INSERT INTO facturations VALUES(13,13,14,70,'2025-02-14 21:24:06','2025-02-14 21:24:22');
INSERT INTO facturations VALUES(14,14,12,60,'2025-02-18 18:58:17','2025-02-18 18:59:25');
INSERT INTO facturations VALUES(15,15,13,130,'2025-03-01 14:35:50','2025-03-01 14:35:50');
DELETE FROM sqlite_sequence;
INSERT INTO sqlite_sequence VALUES('migrations',17);
INSERT INTO sqlite_sequence VALUES('rendez_vous_poneys',45);
INSERT INTO sqlite_sequence VALUES('rendez_vous',23);
INSERT INTO sqlite_sequence VALUES('users',3);
INSERT INTO sqlite_sequence VALUES('clients',15);
INSERT INTO sqlite_sequence VALUES('poneys',10);
INSERT INTO sqlite_sequence VALUES('facturations',15);
CREATE UNIQUE INDEX "users_email_unique" on "users" ("email");
CREATE INDEX "sessions_user_id_index" on "sessions" ("user_id");
CREATE INDEX "sessions_last_activity_index" on "sessions" ("last_activity");
