-- 1. Création de la base de données
CREATE DATABASE IF NOT EXISTS `projet_actualite` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `projet_actualite`;

-- 2. Table des catégories
CREATE TABLE `categories` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `nom` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- 3. Table des utilisateurs (D'après ta capture écran)
CREATE TABLE `utilisateurs` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `login` VARCHAR(50) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` VARCHAR(20) NOT NULL DEFAULT 'editeur',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- 4. Table des articles (Avec les corrections de colonnes)
CREATE TABLE `articles` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `titre` VARCHAR(255) NOT NULL,
    `contenu` TEXT NOT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    `date_creation` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `id_categorie` INT(11),
    `id_utilisateur` INT(11),
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_categorie` FOREIGN KEY (`id_categorie`) REFERENCES `categories`(`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_auteur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 5. Insertion des utilisateurs de ta capture
INSERT INTO `utilisateurs` (`id`, `login`, `password`, `role`) VALUES
(3, 'astou', '$2y$10$K1BgB7I./ixZwvRiDf6Wpuo/SqUbq6wGVO6zQa07VdE...', 'editeur'),
(4, 'admin', '$2y$10$VLm9PXPGKvssSJYKbYxUTODJKmesG0zkc9oaSWkkXPt...', 'admin');

-- 6. Insertion de quelques catégories de test
INSERT INTO `categories` (`nom`) VALUES ('Technologie'), ('Sport'), ('Culture');