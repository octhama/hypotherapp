PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    role TEXT CHECK(role IN ('admin', 'journalier', 'client')) DEFAULT 'client',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO users VALUES(1,'Admin Adminson','admin@example.com','password_hash','admin','2025-01-06 01:19:47','2025-01-06 01:19:47');
INSERT INTO users VALUES(2,'Journalier Jo','journalier@example.com','password_hash','journalier','2025-01-06 01:19:47','2025-01-06 01:19:47');
INSERT INTO users VALUES(3,'Client Client','client@example.com','password_hash','client','2025-01-06 01:19:47','2025-01-06 01:19:47');
CREATE TABLE poneys (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    age INTEGER,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO poneys VALUES(1,'Poney 1',5,'Un poney très actif et aimant les enfants.','2025-01-06 01:19:47','2025-01-06 01:19:47');
INSERT INTO poneys VALUES(2,'Poney 2',3,'Calme, idéal pour les débutants.','2025-01-06 01:19:47','2025-01-06 01:19:47');
INSERT INTO poneys VALUES(3,'Poney 3',7,'Expérimenté, parfait pour les promenades.','2025-01-06 01:19:47','2025-01-06 01:19:47');
INSERT INTO poneys VALUES(4,'Poney 4',2,'Jeune et énergique, nécessite un bon cavalier.','2025-01-06 01:19:47','2025-01-06 01:19:47');
CREATE TABLE rendez_vous (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    date_heure DATETIME NOT NULL,
    duree INTEGER NOT NULL,
    poney_id INTEGER,
    user_id INTEGER,
    status TEXT CHECK(status IN ('planifié', 'annulé', 'terminé')) DEFAULT 'planifié',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (poney_id) REFERENCES poneys(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);
INSERT INTO rendez_vous VALUES(1,'2024-12-15 09:00:00',60,1,3,'planifié','2025-01-06 01:19:47','2025-01-06 01:19:47');
INSERT INTO rendez_vous VALUES(2,'2024-12-16 10:30:00',90,2,3,'annulé','2025-01-06 01:19:47','2025-01-06 01:19:47');
INSERT INTO rendez_vous VALUES(3,'2024-12-17 14:00:00',45,3,3,'terminé','2025-01-06 01:19:47','2025-01-06 01:19:47');
INSERT INTO rendez_vous VALUES(4,'2024-12-18 15:00:00',60,4,3,'planifié','2025-01-06 01:19:47','2025-01-06 01:19:47');
CREATE TABLE factures (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    montant REAL NOT NULL,
    mois TEXT NOT NULL,
    user_id INTEGER,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);
INSERT INTO factures VALUES(1,150.0,'Janvier',3,'2025-01-06 01:19:47','2025-01-06 01:19:47');
INSERT INTO factures VALUES(2,200.0,'Février',3,'2025-01-06 01:19:47','2025-01-06 01:19:47');
INSERT INTO factures VALUES(3,120.0,'Mars',3,'2025-01-06 01:19:47','2025-01-06 01:19:47');
DELETE FROM sqlite_sequence;
INSERT INTO sqlite_sequence VALUES('users',3);
INSERT INTO sqlite_sequence VALUES('poneys',4);
INSERT INTO sqlite_sequence VALUES('rendez_vous',4);
INSERT INTO sqlite_sequence VALUES('factures',3);
COMMIT;
