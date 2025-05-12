-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 10, 2025 at 02:43 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kellerundknilche`
--

-- --------------------------------------------------------

--
-- Table structure for table `beute_batzen`
--

CREATE TABLE `beute_batzen` (
  `user_id` int(11) NOT NULL,
  `amount` decimal(65,2) NOT NULL DEFAULT 0.00
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
(2, 'Untoter', 500, 'absolut', 10.00, 'Produktion', 2),
(3, 'Fledermausschwarm', 2500, 'absolut', 100.00, 'Produktion', 3),
(4, 'Geistererscheinung', 50000, 'absolut', 1000.00, 'Produktion', 4),
(5, 'Dämon aus der Mittagspause', 750000, 'absolut', 10000.00, 'Produktion', 5),
(6, 'Höllenhundebrigade', 250000000, 'absolut', 100000.00, 'Produktion', 6),
(7, 'Schattenkaiser', 15000000000, 'absolut', 1000000.00, 'Produktion', 7),
(8, 'Knochentraining', 500, 'prozent', 10.00, 'Boost', 1),
(9, 'Beckenschwingen', 25000, 'prozent', 25.00, 'Boost', 1),
(10, 'Toten-Tai-Chi', 75000, 'prozent', 50.00, 'Boost', 1),
(11, 'Knochen-Choreographie', 250000, 'prozent', 100.00, 'Boost', 1),
(12, 'Höllenknochen-Ballett', 1250000, 'prozent', 200.00, 'Boost', 1),
(13, 'Knochensplitter-Sprint', 5000000, 'prozent', 500.00, 'Boost', 1),
(14, 'Totentanz-Power', 20000000, 'prozent', 1000.00, 'Boost', 1),
(15, 'Untoten-Schreitherapie', 50000, 'prozent', 10.00, 'Boost', 2),
(16, 'Moder-Meditation', 250000, 'prozent', 25.00, 'Boost', 2),
(17, 'Verfaulungs-Yoga', 750000, 'prozent', 50.00, 'Boost', 2),
(18, 'Aas-Massage', 3750000, 'prozent', 100.00, 'Boost', 2),
(19, 'Gammel-Salbung', 18750000, 'prozent', 200.00, 'Boost', 2),
(20, 'Schwamm-Ritual', 75000000, 'prozent', 500.00, 'Boost', 2),
(21, 'Aas-Schreitherapie 2.0', 300000000, 'prozent', 1000.00, 'Boost', 2),
(22, 'Fledermaus-Chorprobe', 1000000, 'prozent', 10.00, 'Boost', 3),
(23, 'Ultraschall-Stimmenbruch', 5000000, 'prozent', 25.00, 'Boost', 3),
(24, 'Flatter-Fitness', 15000000, 'prozent', 50.00, 'Boost', 3),
(25, 'Luftakrobatik', 75000000, 'prozent', 100.00, 'Boost', 3),
(26, 'Scharf-Mäulchen', 375000000, 'prozent', 200.00, 'Boost', 3),
(27, 'Super-Schwarm', 1500000000, 'prozent', 500.00, 'Boost', 3),
(28, 'Fledermaus-Tornado', 6250000000, 'prozent', 1000.00, 'Boost', 3),
(29, 'Geisterrausch', 25000000, 'prozent', 10.00, 'Boost', 4),
(30, 'Ektoplasma-Workout', 125000000, 'prozent', 25.00, 'Boost', 4),
(31, 'Spuk-Intensivkurs', 375000000, 'prozent', 50.00, 'Boost', 4),
(32, 'Nebelsturm', 1250000000, 'prozent', 100.00, 'Boost', 4),
(33, 'Schwarze Aura', 6250000000, 'prozent', 200.00, 'Boost', 4),
(34, 'Konditioniert für Spuk', 25000000000, 'prozent', 500.00, 'Boost', 4),
(35, 'Geister-Tsunami', 100000000000, 'prozent', 1000.00, 'Boost', 4),
(36, 'Dämonenvertrag', 75000000, 'prozent', 10.00, 'Boost', 5),
(37, 'Blutschwur der Faulen', 375000000, 'prozent', 25.00, 'Boost', 5),
(38, 'Höllischer Halbtagsjob', 1125000000, 'prozent', 50.00, 'Boost', 5),
(39, 'Dämonen-Aufzucht', 3750000000, 'prozent', 100.00, 'Boost', 5),
(40, 'Inferno-Bindung', 15000000000, 'prozent', 200.00, 'Boost', 5),
(41, 'Teufelsbeschwörung', 62500000000, 'prozent', 500.00, 'Boost', 5),
(42, 'Dämonen-Energieschub', 250000000000, 'prozent', 1000.00, 'Boost', 5),
(43, 'Höllenhund-Rudelschule', 250000000, 'prozent', 10.00, 'Boost', 6),
(44, 'Feueratem-Training', 1250000000, 'prozent', 25.00, 'Boost', 6),
(45, 'Infernaler Aufmarsch', 3750000000, 'prozent', 50.00, 'Boost', 6),
(46, 'Hundekommando-Feuer', 12500000000, 'prozent', 100.00, 'Boost', 6),
(47, 'Höllenhund-Renntraining', 62500000000, 'prozent', 200.00, 'Boost', 6),
(48, 'Feuer-Pulverisierung', 250000000000, 'prozent', 500.00, 'Boost', 6),
(49, 'Teufelsfeuer-Angriff', 1000000000000, 'prozent', 1000.00, 'Boost', 6),
(50, 'Schattenmeisterei', 1500000000, 'prozent', 10.00, 'Boost', 7),
(51, 'Dunkelheitsdoktorat', 7500000000, 'prozent', 25.00, 'Boost', 7),
(52, 'Herrschaft des Grauens', 22500000000, 'prozent', 50.00, 'Boost', 7),
(53, 'Todeshauch', 75000000000, 'prozent', 100.00, 'Boost', 7),
(54, 'Dunkle Verschmelzung', 375000000000, 'prozent', 200.00, 'Boost', 7),
(55, 'Schattenschwemme', 1500000000000, 'prozent', 500.00, 'Boost', 7),
(56, 'Schreckensherrschaft', 6250000000000, 'prozent', 1000.00, 'Boost', 7),
(57, 'Muskelkater Finger', 50, 'absolut', 2.00, 'Klick', 57),
(58, 'Verstauchte Skeletthand', 250, 'absolut', 5.00, 'Klick', 58),
(59, 'Dämmergriff', 1000, 'absolut', 10.00, 'Klick', 59),
(60, 'Totenfinger', 2000, 'absolut', 15.00, 'Klick', 60),
(61, 'Phantomgriff', 3500, 'absolut', 20.00, 'Klick', 61),
(62, 'Nekro-Krallen', 5500, 'absolut', 40.00, 'Klick', 62),
(63, 'Schattengriff', 8500, 'absolut', 75.00, 'Klick', 63),
(64, 'Doppelschlag des Jenseits', 12000, 'absolut', 100.00, 'Klick', 64),
(65, 'Seelenschnapper', 25000, 'absolut', 150.00, 'Klick', 65),
(66, 'Seelenklau', 50000, 'absolut', 200.00, 'Klick', 66),
(67, 'Greifarm aus dem Jenseits', 100000, 'absolut', 250.00, 'Klick', 67),
(68, 'Griff des Grauens', 250000, 'absolut', 300.00, 'Klick', 68),
(69, 'Nekrotische Multiberührung', 500000, 'absolut', 400.00, 'Klick', 69),
(70, 'Seelenkitzler', 1000000, 'absolut', 500.00, 'Klick', 70),
(71, 'Grabscher der Untoten', 2500000, 'absolut', 600.00, 'Klick', 71),
(72, 'Finger des Verderbens', 5000000, 'absolut', 750.00, 'Klick', 72),
(73, 'Hand des Todes', 10000000, 'absolut', 1000.00, 'Klick', 73);

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
