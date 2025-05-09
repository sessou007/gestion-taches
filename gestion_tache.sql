-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 25 avr. 2025 à 17:18
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_tache`
--

-- --------------------------------------------------------

--
-- Structure de la table `departments`
--

DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `department_id` int NOT NULL AUTO_INCREMENT,
  `department_name` varchar(255) NOT NULL,
  PRIMARY KEY (`department_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`) VALUES
(18, 'DCMP'),
(16, 'DSI'),
(4, 'DPAF'),
(7, 'Secrétariat'),
(12, 'SECURITE'),
(17, 'PRMP'),
(10, 'COMPATABILITÉ');

-- --------------------------------------------------------

--
-- Structure de la table `evenement`
--

DROP TABLE IF EXISTS `evenement`;
CREATE TABLE IF NOT EXISTS `evenement` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `debut` datetime NOT NULL,
  `alarme` int DEFAULT '0',
  `fin` datetime DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `DELETED` tinyint(1) DEFAULT '0',
  `status` enum('effectué','non effectué','en cours') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'en cours',
  `raison` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `button_disabled` tinyint(1) DEFAULT '0',
  `event_name` varchar(255) NOT NULL,
  `user_id` int NOT NULL,
  `termine` int NOT NULL,
  `assigned_by` int NOT NULL,
  `actions_menes` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=126 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `evenement`
--

INSERT INTO `evenement` (`id`, `titre`, `description`, `debut`, `alarme`, `fin`, `url`, `DELETED`, `status`, `raison`, `button_disabled`, `event_name`, `user_id`, `termine`, `assigned_by`, `actions_menes`) VALUES
(1, 'aaaa', 'f', '2025-01-30 12:06:00', 5, '2025-01-31 12:06:00', NULL, 1, 'non effectué', '', 0, '', 2, 0, 0, NULL),
(2, 'ddd', 'kh', '2025-01-30 12:01:00', 10, '2025-01-31 12:01:00', NULL, 0, 'non effectué', '', 0, '', 3, 0, 0, NULL),
(3, 'reunion', 'reunion generale', '2025-02-03 10:46:00', 5, '2025-02-04 10:47:00', NULL, 0, 'effectué', '', 1, '', 10, 1, 0, NULL),
(4, 'AG', 'GUFUY', '2025-02-03 11:09:00', 5, '2025-02-04 11:09:00', NULL, 0, 'non effectué', 'YTRYGUH', 1, '', 10, 1, 0, NULL),
(5, 'sortie', 'gdcv', '2025-02-03 11:17:00', 5, '2025-02-04 11:17:00', NULL, 0, 'non effectué', 'utc', 1, '', 10, 1, 0, NULL),
(6, 'jyftydy', 'ukfiy', '2025-02-07 12:03:00', 5, '2025-02-08 12:03:00', NULL, 0, 'effectué', '', 1, '', 10, 1, 0, NULL),
(7, 'kydj', 'yifiyk', '2025-02-07 16:49:00', 5, '2025-02-08 16:49:00', NULL, 0, 'effectué', '', 1, '', 10, 1, 0, NULL),
(8, 'dd', 'yiuo', '2025-02-16 00:32:00', 10, '2025-02-17 19:32:00', NULL, 0, 'en cours', '', 0, '', 14, 0, 0, NULL),
(9, 'fsd', 'eth', '2025-02-16 20:35:00', 5, '2025-02-17 20:35:00', NULL, 0, 'effectué', '', 1, '', 11, 1, 0, NULL),
(10, 'SORTiw', 'UOFO', '2025-02-19 12:40:00', 5, '2025-02-20 12:40:00', NULL, 1, 'non effectué', 'tucyvi', 1, '', 7, 1, 0, NULL),
(11, 'exrytuiu', 'xr6ctuvyiu', '2025-02-20 10:25:00', 5, '2025-02-21 10:25:00', NULL, 1, 'en cours', '', 0, '', 7, 0, 0, NULL),
(12, 'txrcytvu', 'yihk', '2025-02-20 11:20:00', 5, '2025-02-21 11:20:00', NULL, 1, 'en cours', '', 0, '', 7, 0, 0, NULL),
(13, 'wedsv', 'qsac', '2025-01-29 08:59:00', 5, '2025-01-30 08:59:00', NULL, 0, 'non effectué', 's7rtduyiuo', 1, '', 17, 1, 0, NULL),
(14, 'tuyiu', 'vy9uoi', '2025-02-25 10:22:00', 5, '2025-02-26 10:22:00', NULL, 1, 'non effectué', 'ôpijk', 1, '', 17, 1, 0, NULL),
(15, 'ghj', 'ou', '2025-02-25 10:48:00', 30, '2025-02-26 10:48:00', NULL, 1, 'non effectué', 'i;becile', 1, '', 7, 1, 0, NULL),
(16, '65t4rr4', '4re', '2025-02-25 16:57:00', 30, '2025-02-26 10:52:00', NULL, 1, 'effectué', '', 1, '', 7, 1, 0, NULL),
(17, '6s4d7tfyu', 'ytu', '2025-02-25 10:56:00', 30, '2025-02-26 10:56:00', NULL, 1, 'non effectué', 'ertyu', 1, '', 7, 1, 0, NULL),
(18, 'tcyuvioui', 'uvyu', '2025-02-25 10:05:00', 30, '2025-02-26 10:05:00', NULL, 1, 'non effectué', 'kutjyhg', 1, '', 7, 1, 0, NULL),
(19, '5d7tfyui', 'tyui', '2025-02-25 16:23:00', 10, '2025-02-26 11:12:00', NULL, 1, 'en cours', '', 0, '', 7, 0, 0, NULL),
(20, 'etryu', 'ikj', '2025-02-25 11:28:00', 30, '2025-02-26 11:28:00', NULL, 1, 'en cours', '', 0, '', 7, 0, 0, NULL),
(21, 'cytuiohijoipklbjk', 'jk', '2025-02-25 11:46:00', 30, '2025-02-26 11:46:00', NULL, 1, 'en cours', '', 0, '', 7, 0, 0, NULL),
(22, 'jhjhs', 'gd', '2025-02-25 11:53:00', 30, '2025-02-26 11:53:00', NULL, 1, 'en cours', '', 0, '', 7, 0, 0, NULL),
(23, 'opu', 'cutvhk', '2025-02-25 12:19:00', 30, '2025-02-26 12:19:00', NULL, 1, 'en cours', '', 0, '', 7, 0, 0, NULL),
(24, 'etryuoukhj', 'cugj', '2025-02-25 14:22:00', 10, '2025-02-26 14:22:00', NULL, 1, 'en cours', '', 0, '', 7, 0, 0, NULL),
(25, 'op', 'QWERTYU', '2025-02-25 16:14:00', 5, '2025-01-28 16:16:00', NULL, 1, 'en cours', '', 0, '', 7, 0, 0, NULL),
(26, 'uyigil;hkl,jnm', 'gukhmn', '2025-02-25 16:16:00', 10, '2025-02-26 16:21:00', NULL, 1, 'en cours', '', 0, '', 7, 0, 0, NULL),
(27, '35', 'ertyu', '2025-02-25 16:21:00', 5, '2025-02-25 16:18:00', NULL, 1, 'en cours', '', 0, '', 7, 0, 0, NULL),
(28, 'kjgmhds', 'tujmfhng', '2025-02-25 17:20:00', 10, '2025-02-26 16:32:00', NULL, 1, 'en cours', '', 0, '', 7, 0, 0, NULL),
(29, ' hvjh', 'io', '2025-03-05 09:02:00', 0, '2025-03-20 09:01:00', NULL, 1, 'en cours', '', 0, '', 17, 0, 0, NULL),
(30, 'lkjh', 'pjoj', '2025-03-18 10:08:00', 0, '2025-03-19 10:13:00', NULL, 0, 'en cours', '', 0, '', 17, 0, 0, NULL),
(31, 'ilukhjg', 'xr6ycutgv', '2025-03-18 10:13:00', 0, '2025-03-18 10:13:00', NULL, 0, 'en cours', '', 0, '', 17, 0, 0, NULL),
(32, 'likjhg', 'ytdfukj', '2025-03-19 11:01:00', 0, '2025-03-19 11:01:00', NULL, 0, 'en cours', '', 0, '', 17, 0, 0, NULL),
(33, 'iutkjhdg', '', '2025-03-06 11:58:00', 5, '2025-03-13 10:00:00', NULL, 0, 'en cours', '', 0, '', 17, 0, 0, NULL),
(34, 'rdyuio', 'rdytuik', '2025-03-21 12:01:00', 0, '2025-03-21 12:01:00', NULL, 0, 'en cours', '', 0, '', 17, 0, 0, NULL),
(35, 'rytuiupoiug', 'fhhf', '2025-03-21 12:01:00', 0, '2025-03-21 08:01:00', NULL, 0, 'en cours', '', 0, '', 17, 0, 0, NULL),
(36, 'tryusaugi', 'yyi', '2025-03-05 12:06:00', 0, '2025-03-05 06:06:00', NULL, 0, 'en cours', '', 0, '', 17, 0, 0, NULL),
(37, 'yxr', 'opipoi', '2025-03-05 12:07:00', 0, '2025-03-05 08:07:00', NULL, 0, 'en cours', '', 0, '', 17, 0, 0, NULL),
(38, 'fds', 'fffffffff', '2025-03-03 12:36:00', 10, '2025-03-04 12:36:00', NULL, 1, 'en cours', '', 0, '', 17, 0, 0, NULL),
(39, 'xfil', 'jj', '2025-03-03 13:39:00', 10, '2025-03-04 13:39:00', NULL, 1, 'en cours', '', 0, '', 17, 0, 0, NULL),
(40, 'sdfsgsf', 'sffsgd', '2025-03-03 13:58:00', 10, '2025-03-04 13:58:00', NULL, 1, 'en cours', '', 0, '', 17, 0, 0, NULL),
(41, 'JVJVJ', 'PIOJ', '2025-03-03 15:39:00', 10, '2025-03-04 15:39:00', NULL, 0, 'en cours', '', 0, '', 17, 0, 0, NULL),
(42, 'kjclk', 'ii', '2025-03-03 15:49:00', 10, '2025-03-04 15:49:00', NULL, 0, 'en cours', '', 0, '', 17, 0, 0, NULL),
(43, 'dtfyg', '6t7yerdtyu', '2025-03-03 17:26:00', 5, '2025-03-04 17:27:00', NULL, 1, 'effectué', '', 1, '', 19, 1, 0, NULL),
(44, 'fxio', 'ioui', '2025-03-05 14:59:00', 10, '2025-03-06 14:59:00', NULL, 1, 'en cours', '', 0, '', 19, 0, 0, NULL),
(45, 'fxcyt', 'tuyi', '2025-03-06 14:44:00', 10, '2025-03-07 14:44:00', NULL, 1, 'en cours', '', 0, '', 19, 0, 0, NULL),
(46, 'hfxhf', 'kjkj', '2025-03-06 14:51:00', 10, '2025-03-07 14:51:00', NULL, 1, 'en cours', '', 0, '', 19, 0, 0, NULL),
(47, 'txxa', 'jkh ', '2025-03-07 09:30:00', 10, '2025-03-07 14:01:00', NULL, 1, 'en cours', '', 0, '', 19, 0, 0, NULL),
(48, 'srydk', '7ir86r', '2025-03-07 09:40:00', 10, '2025-03-08 09:37:00', NULL, 1, 'en cours', '', 0, '', 19, 0, 0, NULL),
(49, 'xjxf', 'uogwd', '2025-03-07 09:46:00', 10, '2025-03-08 09:43:00', NULL, 1, 'en cours', '', 0, '', 19, 0, 0, NULL),
(50, 'ezrtr', 'ytckutc', '2025-03-07 11:45:00', 10, '2025-03-08 11:07:00', NULL, 1, 'en cours', '', 0, '', 19, 0, 0, NULL),
(51, 'gjuxyk', 'iuvbjk', '2025-03-07 11:50:00', 10, '2025-03-08 11:41:00', NULL, 1, 'en cours', '', 0, '', 19, 0, 0, NULL),
(52, 'dzvdsfbad', 'rwdsbfd', '2025-03-07 11:54:00', 10, '2025-03-08 11:51:00', NULL, 0, 'en cours', '', 0, '', 19, 0, 0, NULL),
(53, 'exrdtuf g', 'hj blbh', '2025-03-07 14:24:00', 10, '2025-03-08 11:57:00', NULL, 1, 'en cours', '', 0, '', 19, 0, 0, NULL),
(54, 'rwsrw', '4tef', '2025-03-07 14:22:00', 10, '2025-03-08 14:22:00', NULL, 0, 'en cours', '', 0, '', 19, 0, 0, NULL),
(55, 'etrfhgj', '7riyfly', '2025-03-10 15:40:00', 10, '2025-03-11 14:40:00', NULL, 1, 'en cours', '', 0, '', 19, 0, 0, NULL),
(56, 'vuy', 'yvyug', '2025-03-10 15:15:00', 10, '2025-03-11 15:15:00', NULL, 1, 'en cours', '', 0, '', 19, 0, 0, NULL),
(57, 'fycgvj', 'bobo', '2025-03-10 16:20:00', 10, '2025-03-11 15:43:00', NULL, 1, 'en cours', '', 0, '', 19, 0, 0, NULL),
(58, 'ae', 'uthn', '2025-03-10 16:29:00', 10, '2025-03-11 16:29:00', NULL, 1, 'en cours', '', 0, '', 19, 0, 0, NULL),
(59, 'rdytu', 'yfu', '2025-03-10 16:50:00', 10, '2025-03-11 16:41:00', NULL, 1, 'en cours', '', 0, '', 19, 0, 0, NULL),
(60, '7tfuy', 'iyvh', '2025-03-10 16:48:00', 10, '2025-03-11 16:48:00', NULL, 1, 'en cours', '', 0, '', 19, 0, 0, NULL),
(61, 'ryiyv', 'yuu', '2025-03-10 16:54:00', 10, '2025-03-11 16:54:00', NULL, 1, 'en cours', '', 0, '', 19, 0, 0, NULL),
(62, 'djk', 'rfb', '2025-03-10 17:02:00', 10, '2025-03-11 16:02:00', NULL, 1, 'en cours', '', 0, '', 19, 0, 0, NULL),
(63, 'rytupio', 'uy', '2025-03-10 17:18:00', 10, '2025-03-11 17:12:00', NULL, 1, 'en cours', '', 0, '', 19, 0, 0, NULL),
(64, 'txrfcygj', 'ui', '2025-03-10 17:27:00', 10, '2025-03-11 17:27:00', NULL, 1, 'en cours', '', 0, '', 19, 0, 0, NULL),
(65, 'jlg', 'h', '2025-03-10 17:35:00', 10, '2025-03-11 17:35:00', NULL, 1, 'en cours', '', 0, '', 19, 0, 0, NULL),
(66, 'yctvuhk.jb', 'fiyvh', '2025-03-10 17:48:00', 10, '2025-03-11 17:48:00', NULL, 1, 'en cours', '', 0, '', 19, 0, 0, NULL),
(67, ' jhkj', 'uigvkh', '2025-03-10 18:07:00', 10, '2025-03-11 17:57:00', NULL, 0, 'en cours', '', 0, '', 19, 0, 0, NULL),
(68, 'iogukjv', 'ytcjvh', '2025-03-10 18:12:00', 10, '2025-03-11 18:12:00', NULL, 0, 'en cours', '', 0, '', 19, 0, 0, NULL),
(69, 'ryjy', '6fyujh', '2025-03-12 15:42:00', 10, '2025-03-13 15:42:00', NULL, 0, 'effectué', '', 1, '', 7, 1, 0, NULL),
(70, 'wrestrdy', 'drytuh', '2025-03-14 12:12:00', 0, '2025-03-15 12:12:00', NULL, 0, 'effectué', '', 1, '', 11, 1, 0, NULL),
(71, 'rytui', 'pyiogukjv', '2025-03-14 12:14:00', 0, '2025-03-15 12:14:00', NULL, 0, 'effectué', '', 1, '', 11, 1, 0, NULL),
(72, 'etxryculvy', 'yoiugjh', '2025-03-14 14:09:00', 0, '2025-03-15 14:09:00', NULL, 1, 'en cours', '', 0, '', 11, 0, 7, NULL),
(73, 'dxtfygu', 'ghjhk', '2025-03-14 15:31:00', 0, '2025-03-15 15:30:00', NULL, 0, 'non effectué', 'refuhjk', 1, '', 11, 1, 7, NULL),
(74, 'trdyui', 'u9piogjyh', '2025-03-17 13:10:00', 0, '2025-03-18 13:09:00', NULL, 0, 'non effectué', 'zredxtfcyg', 1, '', 7, 1, 7, NULL),
(75, 'papa', '-09uoyiug', '2025-03-17 13:08:00', 0, '2025-03-18 13:08:00', NULL, 1, 'effectué', '', 1, '', 11, 1, 7, NULL),
(76, 'ikug', 'ff', '2025-03-17 13:57:00', 0, '2025-03-18 13:58:00', NULL, 0, 'en cours', '', 0, '', 11, 0, 7, NULL),
(77, 'ioasd', 'ygi', '2025-03-17 13:59:00', 0, '2025-03-18 13:58:00', NULL, 0, 'en cours', '', 0, '', 6, 0, 7, NULL),
(78, 'ioasd', 'ygi', '2025-03-17 13:59:00', 0, '2025-03-18 13:58:00', NULL, 0, 'en cours', '', 0, '', 11, 0, 7, NULL),
(79, 'oiutyhtdg', 'oiugkj', '2025-03-19 13:58:00', 0, '2025-03-20 13:02:00', NULL, 0, 'en cours', '', 0, '', 11, 0, 11, NULL),
(80, 'jhdj', 'rdyh', '2025-03-19 14:16:00', 0, '2025-03-20 14:16:00', NULL, 0, 'en cours', '', 0, '', 11, 0, 11, NULL),
(81, 'pioguk', 'drytui', '2025-03-19 16:41:00', 0, '2025-03-20 16:42:00', NULL, 0, 'en cours', '', 0, '', 11, 0, 11, NULL),
(82, 'fjhh', 'oiu', '2025-03-19 16:49:00', 0, '2025-03-20 16:49:00', NULL, 0, 'en cours', '', 0, '', 11, 0, 11, NULL),
(85, 'sort', 'yufi', '2025-03-17 17:31:00', 0, '2025-03-18 17:31:00', NULL, 0, 'en cours', '', 0, '', 11, 0, 7, NULL),
(86, 'ku', '6ufyjh', '2025-03-21 18:21:00', 0, '2025-03-22 18:22:00', NULL, 0, 'en cours', '', 0, '', 6, 0, 11, NULL),
(87, 'plan', '7ty', '2025-03-18 11:43:00', 0, '2025-03-19 11:43:00', NULL, 0, 'en cours', '', 0, '', 11, 0, 7, NULL),
(88, 'iuo', 'iuj', '2025-03-20 11:49:00', 0, '2025-03-21 11:55:00', NULL, 0, 'effectué', '', 1, '', 7, 1, 7, 'sotie'),
(89, 'try', 'uihj', '2025-03-05 11:54:00', 0, '2025-03-06 11:55:00', NULL, 0, 'en cours', '', 0, '', 11, 0, 7, NULL),
(90, 'utd', 'uig', '2025-03-19 12:19:00', 0, '2025-03-20 12:17:00', NULL, 0, 'en cours', '', 0, '', 11, 0, 7, NULL),
(91, 'j', 'yhjc', '2025-03-20 12:27:00', 0, '2025-03-21 12:27:00', NULL, 0, 'en cours', '', 0, '', 11, 0, 7, NULL),
(92, 'yud', 'ytgj', '2025-03-19 12:34:00', 0, '2025-03-20 12:33:00', NULL, 0, 'en cours', '', 0, '', 11, 0, 7, NULL),
(93, 'il', 'yu', '2025-03-20 13:23:00', 0, '2025-03-21 13:21:00', NULL, 0, 'en cours', '', 0, '', 11, 0, 7, NULL),
(94, 'jh', 'gc', '2025-03-19 13:34:00', 0, '2025-03-20 13:37:00', NULL, 0, 'en cours', '', 0, '', 11, 0, 7, NULL),
(95, 'opop', 'ikjgh', '2025-03-20 13:39:00', 0, '2025-03-21 13:39:00', NULL, 0, 'en cours', '', 0, '', 11, 0, 7, NULL),
(96, 'saaa', 'ds', '2025-03-19 13:50:00', 0, '2025-03-20 13:52:00', NULL, 0, 'en cours', '', 0, '', 11, 0, 7, NULL),
(97, 'bizih', 'uio', '2025-03-27 15:28:00', 0, '2025-03-28 15:28:00', NULL, 0, 'en cours', '', 0, '', 11, 0, 7, NULL),
(98, 'popop', 'lgu', '2025-01-26 16:57:00', 0, '2025-01-27 16:59:00', NULL, 0, 'en cours', '', 0, '', 11, 0, 7, NULL),
(99, 'et', 'yu', '2025-03-29 16:58:00', 0, '2025-03-30 16:01:00', NULL, 0, 'en cours', '', 0, '', 11, 0, 7, NULL),
(100, 'aaaaaaaaaaaaaaa', 'fdg', '2025-03-23 17:21:00', 0, '2025-03-24 17:21:00', NULL, 0, 'effectué', '', 1, '', 7, 1, 7, 'oufvkhj'),
(101, 'sssssssssss', 'rwsf', '2025-03-23 17:19:00', 0, '2025-03-24 17:23:00', NULL, 0, 'en cours', '', 0, '', 11, 0, 7, NULL),
(102, 'TOOI', 'IKL', '2025-03-19 14:15:00', 0, '2025-03-19 14:18:00', NULL, 0, 'effectué', '', 1, '', 7, 1, 7, 'yirfyidchj'),
(103, 'ZRDTXFYKH', 'RTYUH', '2025-03-26 14:20:00', 0, '2025-03-27 14:20:00', NULL, 0, 'en cours', '', 0, '', 11, 0, 11, NULL),
(104, 'reuinion', 'tdxfhgj', '2025-04-24 18:29:00', 0, '2025-04-25 18:29:00', NULL, 0, 'effectué', '', 1, '', 7, 1, 7, '5estryu'),
(105, 'reu', 'dffgd', '2025-04-24 18:30:00', 5, '2025-04-25 18:30:00', NULL, 0, 'en cours', '', 0, '', 7, 0, 7, NULL),
(125, 'as', 'yrgt', '2025-04-25 17:05:00', 5, '2025-04-26 16:05:00', NULL, 0, 'en cours', '', 0, '', 7, 0, 7, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `message` text NOT NULL,
  `read_status` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `read_status`, `created_at`) VALUES
(1, 11, 'Une nouvelle tâche vous a été assignée : bizih', 1, '2025-03-18 14:23:15'),
(2, 11, 'Une nouvelle tâche vous a été assignée : popop ', 1, '2025-03-18 15:55:15'),
(3, 11, 'Une nouvelle tâche vous a été assignée : et ', 0, '2025-03-18 15:56:47'),
(4, 11, 'Une nouvelle tâche vous a été assignée : sssssssssss par Anicet DJOSSOU. Date de début : 2025-03-23 17:19', 0, '2025-03-18 16:17:07');

-- --------------------------------------------------------

--
-- Structure de la table `postes`
--

DROP TABLE IF EXISTS `postes`;
CREATE TABLE IF NOT EXISTS `postes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `poste_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `postes`
--

INSERT INTO `postes` (`id`, `poste_name`) VALUES
(3, 'supra_admin'),
(4, 'directeur'),
(5, 'secretaire'),
(6, 'chef_service'),
(7, 'sous_chef_service');

-- --------------------------------------------------------

--
-- Structure de la table `task_assignments`
--

DROP TABLE IF EXISTS `task_assignments`;
CREATE TABLE IF NOT EXISTS `task_assignments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `task_id` int NOT NULL,
  `user_id` int NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `debut` datetime NOT NULL,
  `fin` datetime DEFAULT NULL,
  `status` enum('effectué','non effectué','en cours') DEFAULT 'en cours',
  `assigned_by` int NOT NULL,
  `alarme` int DEFAULT '0',
  `url` varchar(255) DEFAULT NULL,
  `DELETED` tinyint(1) DEFAULT '0',
  `raison` varchar(255) NOT NULL,
  `button_disabled` tinyint(1) DEFAULT '0',
  `event_name` varchar(255) NOT NULL,
  `termine` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `task_assignments`
--

INSERT INTO `task_assignments` (`id`, `task_id`, `user_id`, `titre`, `description`, `debut`, `fin`, `status`, `assigned_by`, `alarme`, `url`, `DELETED`, `raison`, `button_disabled`, `event_name`, `termine`) VALUES
(1, 85, 11, 'sort', 'yufi', '2025-03-17 17:31:00', '2025-03-18 17:31:00', 'en cours', 7, 0, '', 0, '', 0, '', 0),
(2, 86, 6, 'ku', '6ufyjh', '2025-03-21 18:21:00', '2025-03-22 18:22:00', 'en cours', 11, 0, '', 0, '', 0, '', 0),
(3, 87, 11, 'plan', '7ty', '2025-03-18 11:43:00', '2025-03-19 11:43:00', 'en cours', 7, 0, '', 0, '', 0, '', 0),
(4, 89, 11, 'try', 'uihj', '2025-03-05 11:54:00', '2025-03-06 11:55:00', 'en cours', 7, 0, '', 0, '', 0, '', 0),
(5, 90, 11, 'utd', 'uig', '2025-03-19 12:19:00', '2025-03-20 12:17:00', 'en cours', 7, 0, '', 0, '', 0, '', 0),
(6, 91, 11, 'j', 'yhjc', '2025-03-20 12:27:00', '2025-03-21 12:27:00', 'en cours', 7, 0, '', 0, '', 0, '', 0),
(7, 92, 11, 'yud', 'ytgj', '2025-03-19 12:34:00', '2025-03-20 12:33:00', 'en cours', 7, 0, '', 0, '', 0, '', 0),
(8, 93, 11, 'il', 'yu', '2025-03-20 13:23:00', '2025-03-21 13:21:00', 'en cours', 7, 0, '', 0, '', 0, '', 0),
(9, 94, 11, 'jh', 'gc', '2025-03-19 13:34:00', '2025-03-20 13:37:00', 'en cours', 7, 0, '', 0, '', 0, '', 0),
(10, 95, 11, 'opop', 'ikjgh', '2025-03-20 13:39:00', '2025-03-21 13:39:00', 'en cours', 7, 0, '', 0, '', 0, '', 0),
(11, 96, 11, 'saaa', 'ds', '2025-03-19 13:50:00', '2025-03-20 13:52:00', 'en cours', 7, 0, NULL, 0, '', 0, '', 0),
(12, 97, 11, 'bizih', 'uio', '2025-03-27 15:28:00', '2025-03-28 15:28:00', 'en cours', 7, 0, '', 0, '', 0, '', 0),
(13, 98, 11, 'popop', 'lgu', '2025-01-26 16:57:00', '2025-01-27 16:59:00', 'en cours', 7, 0, '', 0, '', 0, '', 0),
(14, 99, 11, 'et', 'yu', '2025-03-29 16:58:00', '2025-03-30 16:01:00', 'en cours', 7, 0, '', 0, '', 0, '', 0),
(15, 101, 11, 'sssssssssss', 'rwsf', '2025-03-23 17:19:00', '2025-03-24 17:23:00', 'en cours', 7, 0, '', 0, '', 0, '', 0);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `department_id` int DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `active` tinyint(1) DEFAULT '0',
  `poste_id` int NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `fk_department` (`department_id`),
  KEY `fk_poste` (`poste_id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`user_id`, `password`, `email`, `department_id`, `first_name`, `last_name`, `active`, `poste_id`) VALUES
(6, '$2y$10$TnLaNfXqvvhe7uIpEKKTleTCz1/8Qft9wuhDQ3dOltiuLtR3W4fqe', 'dangbo12@gmail.com', 16, 'Aristide David dyh', 'SESSOU', 1, 3),
(7, '$2y$10$fGrgQAqYQ7cOWF4o6C6zU.gHTH6a9vpUflFJ9ibuhSteSRSDBodiW', 'direct@gmail.com', 16, 'Anicet', 'DJOSSOU', 1, 4),
(11, '$2y$10$XlOTk2yPGoVctu9W4qyQJeL1imPhSZNheBhTNNK7NUjMrKg.Gycty', 'dangbo@gmail.com', 16, 'fiacre', 'woss', 1, 5),
(12, '$2y$10$2hXCBiMgV6n9omu2Zmx7Mu9lXEKu/V1g5gr07uTDY8ibqkwxwvnVi', 'emedangbo88@gmail.com', 16, 'gf', 'OURO', 1, 6),
(14, '$2y$10$0bRLeAooatcbmK7VkwXnYeIxHwU893uGFlkH4rYsawpKCE523t2US', 'aristiou324@gmail.com', 12, 'Aristide', 'SESSOU', 1, 7),
(15, '$2y$10$bUWAgt1.FEQhzcoN4xMeSuSJxTKVB6BIr42dyKShOOqYf1cLj96rq', 'dang@gmail.com', 16, 'Clarel', 'Aidjedo', 1, 5),
(16, '$2y$10$6rJHbhZlKOmvVvpWWWhOUO81bvxQkkL2hofs.pcTMeqW7RcpHnzCq', 'dangbi@gmail.com', 16, 'sd', 'sar', 1, 6),
(17, '$2y$10$UTy090.MBHUGHLEcj4IpJu8GFn8yRgRjT3aWMxi7il42TDSeSvrHO', 'jean@gmail.com', 16, 'Jacques', 'Wankpo', 1, 6),
(19, '$2y$10$21DTcBeaDgW6LdrcilicG.VUOipcfJg831f22OTdTl/Nte1hQkLIm', 'ahi@gmail.com', 16, '6rt7y8', 'jhkl', 1, 5),
(23, '$2y$10$4MJeRAQTqwl.TD67WRxZXuIMZgx/DwTVPxUp5U5xBtwP/y7AF80tC', 'Aristde324@gmail.com', 16, 'da', 'fh', 1, 3);

-- --------------------------------------------------------

--
-- Structure de la table `usersc`
--

DROP TABLE IF EXISTS `usersc`;
CREATE TABLE IF NOT EXISTS `usersc` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('supra_admin','directeur','secretaire','chef_service','sous_chef_service') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `department_id` int DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `active` tinyint(1) DEFAULT '0',
  `deleted_at` date DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `fk_department` (`department_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `usersc`
--

INSERT INTO `usersc` (`user_id`, `password`, `email`, `role`, `department_id`, `first_name`, `last_name`, `active`, `deleted_at`) VALUES
(10, '$2y$10$uPl2AaN2Hj43aeG0nE/8QOwFOPP1oE/54QUQuMpJF427AX1T1Y3fS', 'admi@gmail.com', 'sous_chef_service', 2, 'boni', 'yaya', 1, '2025-02-16'),
(13, '$2y$10$0N4Nj9VTTDb1Gm6ghrPNU.DKbYcjIdXn5fQdseb/UtZ5FAM.ApU1y', 'emedangbo22@gmail.com', 'chef_service', 2, 'Clarel', 'Aidjedo', 1, '2025-02-16');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
