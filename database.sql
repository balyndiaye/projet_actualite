-- phpMyAdmin SQL Dump
-- version 5.2.1
-- Base de données : `projet_actualite`

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Structure de la table `categories`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `categories` (`id`, `nom`) VALUES
(1, 'Education'),
(2, 'Sport'),
(3, 'Politique'),
(5, 'Technologie'),
(23, 'culture');

-- --------------------------------------------------------
-- Structure de la table `utilisateurs`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT 'editeur',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `utilisateurs` (`id`, `login`, `password`, `role`) VALUES
(3, 'astou', '$2y$10$K1BgB7I./ixZwvRiDf6Wpuo/SqUbq6wGVO6zQa07VdEAVr2CYzQyO', 'editeur'),
(4, 'admin', '$2y$10$VLm9PXPGKvsSJYKbYXsUTODJKmesG0zkc9oaSWkkXPtHSNWxj7gqK', 'admin');

-- --------------------------------------------------------
-- Structure de la table `articles`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `contenu` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `date_creation` datetime DEFAULT current_timestamp(),
  `id_categorie` int(11) DEFAULT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_categorie` (`id_categorie`),
  KEY `id_utilisateur` (`id_utilisateur`),
  CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`id_categorie`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `articles_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `articles` (`id`, `titre`, `contenu`, `image`, `date_creation`, `id_categorie`, `id_utilisateur`) VALUES
(4, 'Innovation technologique : Dakar...', 'Le Sénégal se positionne...', '1774458177_WhatsApp...webp', '2026-03-25 17:02:57', 5, 3),
(5, 'Demande de visa : BLS International...', 'BLS International a mis...', '1774458644_Demande-visa...webp', '2026-03-25 17:10:44', 5, 3),
(6, 'Korité 2026 : le croissant lunaire...', 'La fin du mois de Ramadan...', '1774459026_487202945...jpg', '2026-03-25 17:17:06', 23, 3),
(7, 'Éducation et formation : le programme Clé...', 'Combattre l’exclusion...', '1774459152_3.-Photo-2...webp', '2026-03-25 17:19:12', 1, 3),
(8, 'Sénégal,Algérie, Cap‑Vert… : Visa américain...', 'À quelques mois du coup d’envoi...', '1774459471_can-2025...jpg', '2026-03-25 17:24:31', 2, 3);

COMMIT;