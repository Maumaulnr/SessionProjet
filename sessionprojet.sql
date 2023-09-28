-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           5.7.33 - MySQL Community Server (GPL)
-- SE du serveur:                Win64
-- HeidiSQL Version:             11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour sessionprojet
CREATE DATABASE IF NOT EXISTS `sessionprojet` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `sessionprojet`;

-- Listage de la structure de la table sessionprojet. categorie
CREATE TABLE IF NOT EXISTS `categorie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_categorie` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sessionprojet.categorie : ~0 rows (environ)
/*!40000 ALTER TABLE `categorie` DISABLE KEYS */;
/*!40000 ALTER TABLE `categorie` ENABLE KEYS */;

-- Listage de la structure de la table sessionprojet. cours
CREATE TABLE IF NOT EXISTS `cours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categorie_id` int(11) NOT NULL,
  `nom_module` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_FDCA8C9CBCF5E72D` (`categorie_id`),
  CONSTRAINT `FK_FDCA8C9CBCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sessionprojet.cours : ~0 rows (environ)
/*!40000 ALTER TABLE `cours` DISABLE KEYS */;
/*!40000 ALTER TABLE `cours` ENABLE KEYS */;

-- Listage de la structure de la table sessionprojet. doctrine_migration_versions
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Listage des données de la table sessionprojet.doctrine_migration_versions : ~1 rows (environ)
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
	('DoctrineMigrations\\Version20230926113457', '2023-09-26 11:35:47', 636);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;

-- Listage de la structure de la table sessionprojet. formation
CREATE TABLE IF NOT EXISTS `formation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_formation` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sessionprojet.formation : ~0 rows (environ)
/*!40000 ALTER TABLE `formation` DISABLE KEYS */;
/*!40000 ALTER TABLE `formation` ENABLE KEYS */;

-- Listage de la structure de la table sessionprojet. messenger_messages
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sessionprojet.messenger_messages : ~0 rows (environ)
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;

-- Listage de la structure de la table sessionprojet. programme
CREATE TABLE IF NOT EXISTS `programme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cours_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `nb_jours` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3DDCB9FF7ECF78B0` (`cours_id`),
  KEY `IDX_3DDCB9FF613FECDF` (`session_id`),
  CONSTRAINT `FK_3DDCB9FF613FECDF` FOREIGN KEY (`session_id`) REFERENCES `session` (`id`),
  CONSTRAINT `FK_3DDCB9FF7ECF78B0` FOREIGN KEY (`cours_id`) REFERENCES `cours` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sessionprojet.programme : ~0 rows (environ)
/*!40000 ALTER TABLE `programme` DISABLE KEYS */;
/*!40000 ALTER TABLE `programme` ENABLE KEYS */;

-- Listage de la structure de la table sessionprojet. session
CREATE TABLE IF NOT EXISTS `session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `formation_id` int(11) DEFAULT NULL,
  `nom_session` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `places_theoriques` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D044D5D45200282E` (`formation_id`),
  CONSTRAINT `FK_D044D5D45200282E` FOREIGN KEY (`formation_id`) REFERENCES `formation` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sessionprojet.session : ~0 rows (environ)
/*!40000 ALTER TABLE `session` DISABLE KEYS */;
/*!40000 ALTER TABLE `session` ENABLE KEYS */;

-- Listage de la structure de la table sessionprojet. stagiaire
CREATE TABLE IF NOT EXISTS `stagiaire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `genre` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ville` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_naissance` datetime NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sessionprojet.stagiaire : ~0 rows (environ)
/*!40000 ALTER TABLE `stagiaire` DISABLE KEYS */;
/*!40000 ALTER TABLE `stagiaire` ENABLE KEYS */;

-- Listage de la structure de la table sessionprojet. stagiaire_session
CREATE TABLE IF NOT EXISTS `stagiaire_session` (
  `stagiaire_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  PRIMARY KEY (`stagiaire_id`,`session_id`),
  KEY `IDX_D32D02D4BBA93DD6` (`stagiaire_id`),
  KEY `IDX_D32D02D4613FECDF` (`session_id`),
  CONSTRAINT `FK_D32D02D4613FECDF` FOREIGN KEY (`session_id`) REFERENCES `session` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_D32D02D4BBA93DD6` FOREIGN KEY (`stagiaire_id`) REFERENCES `stagiaire` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sessionprojet.stagiaire_session : ~0 rows (environ)
/*!40000 ALTER TABLE `stagiaire_session` DISABLE KEYS */;
/*!40000 ALTER TABLE `stagiaire_session` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
