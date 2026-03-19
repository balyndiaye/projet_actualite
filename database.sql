CREATE DATABASE IF NOT EXISTS site_actualite CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE site_actualite;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50) NOT NULL UNIQUE,      -- Conforme à la photo
    password VARCHAR(255) NOT NULL,         -- Conforme à la photo
    role VARCHAR(50) DEFAULT 'visiteur'     -- Conforme à la photo
) ENGINE=InnoDB;

CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    contenu TEXT NOT NULL,
    id_categorie INT,
    id_auteur INT,                          -- Changé de id_utilisateur à id_auteur
    date_publication DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_categorie FOREIGN KEY (id_categorie) REFERENCES categories(id),
    CONSTRAINT fk_auteur FOREIGN KEY (id_auteur) REFERENCES utilisateurs(id)
) ENGINE=InnoDB;