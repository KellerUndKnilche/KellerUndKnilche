-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 16, 2025 at 12:07 AM
-- Server version: 10.11.11-MariaDB-hz2
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `keller_knilche_main_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `beute_batzen`
--

CREATE TABLE `beute_batzen` (
  `user_id` int(11) NOT NULL,
  `amount` bigint(50) UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `targets`
--

CREATE TABLE `targets` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `typ` enum('Produktion','Klick','Sonstiges') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `targets`
--

INSERT INTO `targets` (`id`, `name`, `typ`) VALUES
(1, 'Gerippe', 'Produktion'),
(2, 'Untoter', 'Produktion'),
(3, 'Fledermausschwarm', 'Produktion'),
(4, 'Geistererscheinung', 'Produktion'),
(5, 'Dämon aus der Mittagspause', 'Produktion'),
(6, 'Höllenhundebrigade', 'Produktion'),
(7, 'Schattenkaiser', 'Produktion'),
(57, 'Muskelkater Finger', 'Klick'),
(58, 'Verstauchte Skeletthand', 'Klick'),
(59, 'Dämmergriff', 'Klick'),
(60, 'Totenfinger', 'Klick'),
(61, 'Phantomgriff', 'Klick'),
(62, 'Nekro-Krallen', 'Klick'),
(63, 'Schattengriff', 'Klick'),
(64, 'Doppelschlag des Jenseits', 'Klick'),
(65, 'Seelenschnapper', 'Klick'),
(66, 'Seelenklau', 'Klick'),
(67, 'Greifarm aus dem Jenseits', 'Klick'),
(68, 'Griff des Grauens', 'Klick'),
(69, 'Nekrotische Multiberührung', 'Klick'),
(70, 'Seelenkitzler', 'Klick'),
(71, 'Grabscher der Untoten', 'Klick'),
(72, 'Finger des Verderbens', 'Klick'),
(73, 'Hand des Todes', 'Klick');

-- --------------------------------------------------------

--
-- Table structure for table `upgrades`
--

CREATE TABLE `upgrades` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `basispreis` bigint(20) UNSIGNED DEFAULT NULL,
  `effektart` enum('prozent','absolut') NOT NULL,
  `effektwert` double(20,2) DEFAULT NULL,
  `kategorie` enum('Produktion','Boost','Klick') NOT NULL,
  `ziel_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `upgrades`
--

INSERT INTO `upgrades` (`id`, `name`, `basispreis`, `effektart`, `effektwert`, `kategorie`, `ziel_id`) VALUES
(1, 'Gerippe', 10, 'absolut', 1.00, 'Produktion', 1),
(2, 'Untoter', 100, 'absolut', 5.00, 'Produktion', 2),
(3, 'Fledermausschwarm', 1000, 'absolut', 20.00, 'Produktion', 3),
(4, 'Geistererscheinung', 10000, 'absolut', 80.00, 'Produktion', 4),
(5, 'Dämon aus der Mittagspause', 100000, 'absolut', 320.00, 'Produktion', 5),
(6, 'Höllenhundebrigade', 1000000, 'absolut', 1280.00, 'Produktion', 6),
(7, 'Schattenkaiser', 10000000, 'absolut', 5120.00, 'Produktion', 7),
(8, 'Knochentraining', 50, 'prozent', 10.00, 'Boost', 1),
(9, 'Beckenschwingen', 100, 'prozent', 25.00, 'Boost', 1),
(10, 'Toten-Tai-Chi', 200, 'prozent', 50.00, 'Boost', 1),
(11, 'Knochen-Choreographie', 400, 'prozent', 100.00, 'Boost', 1),
(12, 'Höllenknochen-Ballett', 800, 'prozent', 200.00, 'Boost', 1),
(13, 'Knochensplitter-Sprint', 1600, 'prozent', 500.00, 'Boost', 1),
(14, 'Totentanz-Power', 3200, 'prozent', 1000.00, 'Boost', 1),
(15, 'Untoten-Schreitherapie', 500, 'prozent', 10.00, 'Boost', 2),
(16, 'Moder-Meditation', 1000, 'prozent', 25.00, 'Boost', 2),
(17, 'Verfaulungs-Yoga', 2000, 'prozent', 50.00, 'Boost', 2),
(18, 'Aas-Massage', 4000, 'prozent', 100.00, 'Boost', 2),
(19, 'Gammel-Salbung', 8000, 'prozent', 200.00, 'Boost', 2),
(20, 'Schwamm-Ritual', 16000, 'prozent', 500.00, 'Boost', 2),
(21, 'Aas-Schreitherapie 2.0', 32000, 'prozent', 1000.00, 'Boost', 2),
(22, 'Fledermaus-Chorprobe', 5000, 'prozent', 10.00, 'Boost', 3),
(23, 'Ultraschall-Stimmenbruch', 10000, 'prozent', 25.00, 'Boost', 3),
(24, 'Flatter-Fitness', 20000, 'prozent', 50.00, 'Boost', 3),
(25, 'Luftakrobatik', 40000, 'prozent', 100.00, 'Boost', 3),
(26, 'Scharf-Mäulchen', 80000, 'prozent', 200.00, 'Boost', 3),
(27, 'Super-Schwarm', 160000, 'prozent', 500.00, 'Boost', 3),
(28, 'Fledermaus-Tornado', 320000, 'prozent', 1000.00, 'Boost', 3),
(29, 'Geisterrausch', 50000, 'prozent', 10.00, 'Boost', 4),
(30, 'Ektoplasma-Workout', 100000, 'prozent', 25.00, 'Boost', 4),
(31, 'Spuk-Intensivkurs', 200000, 'prozent', 50.00, 'Boost', 4),
(32, 'Nebelsturm', 400000, 'prozent', 100.00, 'Boost', 4),
(33, 'Schwarze Aura', 800000, 'prozent', 200.00, 'Boost', 4),
(34, 'Konditioniert für Spuk', 1600000, 'prozent', 500.00, 'Boost', 4),
(35, 'Geister-Tsunami', 3200000, 'prozent', 1000.00, 'Boost', 4),
(36, 'Dämonenvertrag', 500000, 'prozent', 10.00, 'Boost', 5),
(37, 'Blutschwur der Faulen', 1000000, 'prozent', 25.00, 'Boost', 5),
(38, 'Höllischer Halbtagsjob', 2000000, 'prozent', 50.00, 'Boost', 5),
(39, 'Dämonen-Aufzucht', 4000000, 'prozent', 100.00, 'Boost', 5),
(40, 'Inferno-Bindung', 8000000, 'prozent', 200.00, 'Boost', 5),
(41, 'Teufelsbeschwörung', 16000000, 'prozent', 500.00, 'Boost', 5),
(42, 'Dämonen-Energieschub', 32000000, 'prozent', 1000.00, 'Boost', 5),
(43, 'Höllenhund-Rudelschule', 5000000, 'prozent', 10.00, 'Boost', 6),
(44, 'Feueratem-Training', 10000000, 'prozent', 25.00, 'Boost', 6),
(45, 'Infernaler Aufmarsch', 20000000, 'prozent', 50.00, 'Boost', 6),
(46, 'Hundekommando-Feuer', 40000000, 'prozent', 100.00, 'Boost', 6),
(47, 'Höllenhund-Renntraining', 80000000, 'prozent', 200.00, 'Boost', 6),
(48, 'Feuer-Pulverisierung', 160000000, 'prozent', 500.00, 'Boost', 6),
(49, 'Teufelsfeuer-Angriff', 320000000, 'prozent', 1000.00, 'Boost', 6),
(50, 'Schattenmeisterei', 50000000, 'prozent', 10.00, 'Boost', 7),
(51, 'Dunkelheitsdoktorat', 100000000, 'prozent', 25.00, 'Boost', 7),
(52, 'Herrschaft des Grauens', 200000000, 'prozent', 50.00, 'Boost', 7),
(53, 'Todeshauch', 400000000, 'prozent', 100.00, 'Boost', 7),
(54, 'Dunkle Verschmelzung', 800000000, 'prozent', 200.00, 'Boost', 7),
(55, 'Schattenschwemme', 1600000000, 'prozent', 500.00, 'Boost', 7),
(56, 'Schreckensherrschaft', 3200000000, 'prozent', 1000.00, 'Boost', 7),
(57, 'Muskelkater Finger', 50, 'absolut', 1.00, 'Klick', 57),
(58, 'Verstauchte Skeletthand', 140, 'absolut', 2.00, 'Klick', 58),
(59, 'Dämmergriff', 392, 'absolut', 4.00, 'Klick', 59),
(60, 'Totenfinger', 1098, 'absolut', 8.00, 'Klick', 60),
(61, 'Phantomgriff', 3073, 'absolut', 16.00, 'Klick', 61),
(62, 'Nekro-Krallen', 8605, 'absolut', 32.00, 'Klick', 62),
(63, 'Schattengriff', 24095, 'absolut', 64.00, 'Klick', 63),
(64, 'Doppelschlag des Jenseits', 67465, 'absolut', 128.00, 'Klick', 64),
(65, 'Seelenschnapper', 188901, 'absolut', 256.00, 'Klick', 65),
(66, 'Seelenklau', 528923, 'absolut', 512.00, 'Klick', 66),
(67, 'Greifarm aus dem Jenseits', 1480984, 'absolut', 1024.00, 'Klick', 67),
(68, 'Griff des Grauens', 4146755, 'absolut', 2048.00, 'Klick', 68),
(69, 'Nekrotische Multiberührung', 11610913, 'absolut', 4096.00, 'Klick', 69),
(70, 'Seelenkitzler', 32510557, 'absolut', 8192.00, 'Klick', 70),
(71, 'Grabscher der Untoten', 91029560, 'absolut', 16384.00, 'Klick', 71),
(72, 'Finger des Verderbens', 254882768, 'absolut', 32768.00, 'Klick', 72),
(73, 'Hand des Todes', 713671750, 'absolut', 65536.00, 'Klick', 73);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `isAdmin` tinyint(4) NOT NULL DEFAULT 0,
  `isLocked` tinyint(4) NOT NULL DEFAULT 0,
  `acceptedTerms` tinyint(4) NOT NULL DEFAULT 0,
  `registrationDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_upgrades`
--

CREATE TABLE `user_upgrades` (
  `user_id` int(11) NOT NULL,
  `upgrade_id` int(11) UNSIGNED NOT NULL,
  `level` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `beute_batzen`
--
ALTER TABLE `beute_batzen`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `targets`
--
ALTER TABLE `targets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `upgrades`
--
ALTER TABLE `upgrades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ziel_id` (`ziel_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_upgrades`
--
ALTER TABLE `user_upgrades`
  ADD PRIMARY KEY (`user_id`,`upgrade_id`),
  ADD KEY `upgrade_id` (`upgrade_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `targets`
--
ALTER TABLE `targets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `upgrades`
--
ALTER TABLE `upgrades`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `beute_batzen`
--
ALTER TABLE `beute_batzen`
  ADD CONSTRAINT `beute_batzen_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `upgrades`
--
ALTER TABLE `upgrades`
  ADD CONSTRAINT `fk_upgrade_target` FOREIGN KEY (`ziel_id`) REFERENCES `targets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_upgrades`
--
ALTER TABLE `user_upgrades`
  ADD CONSTRAINT `user_upgrades_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_upgrades_ibfk_2` FOREIGN KEY (`upgrade_id`) REFERENCES `upgrades` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;