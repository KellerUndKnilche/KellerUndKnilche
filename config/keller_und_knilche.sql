-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 19, 2025 at 07:50 PM
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
-- Database: `keller_knilche_main_db_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `beute_batzen`
--

CREATE TABLE `beute_batzen` (
  `user_id` int(11) NOT NULL,
  `amount` bigint(50) DEFAULT 0
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
(21, 'Muskelkater-Finger', 'Klick'),
(22, 'Nekro-Handschuh', 'Klick'),
(23, 'Greifarm aus dem Jenseits', 'Klick'),
(24, 'Finger des Verderbens', 'Klick'),
(25, 'Magischer Klicker des Todes', 'Klick');

-- --------------------------------------------------------

--
-- Table structure for table `upgrades`
--

CREATE TABLE `upgrades` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `basispreis` int(11) NOT NULL,
  `effektart` enum('prozent','absolut') NOT NULL,
  `effektwert` double(8,2) NOT NULL,
  `kategorie` enum('Produktion','Boost','Klick') NOT NULL,
  `ziel_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `upgrades`
--

INSERT INTO `upgrades` (`id`, `name`, `basispreis`, `effektart`, `effektwert`, `kategorie`, `ziel_id`) VALUES
(1, 'Gerippe', 10, 'absolut', 1.00, 'Produktion', 1),
(2, 'Untoter', 50, 'absolut', 5.00, 'Produktion', 2),
(3, 'Fledermausschwarm', 150, 'absolut', 10.00, 'Produktion', 3),
(4, 'Geistererscheinung', 400, 'absolut', 25.00, 'Produktion', 4),
(5, 'Dämon aus der Mittagspause', 1000, 'absolut', 50.00, 'Produktion', 5),
(6, 'Knochentraining 1', 100, 'prozent', 10.00, 'Boost', 1),
(7, 'Knochentraining 2', 500, 'prozent', 15.00, 'Boost', 1),
(8, 'Knochentraining 3', 1000, 'prozent', 20.00, 'Boost', 1),
(9, 'Untoten-Schreitherapie 1', 250, 'prozent', 10.00, 'Boost', 2),
(10, 'Untoten-Schreitherapie 2', 750, 'prozent', 15.00, 'Boost', 2),
(11, 'Untoten-Schreitherapie 3', 1750, 'prozent', 20.00, 'Boost', 2),
(12, 'Fledermaus-Chorprobe 1', 500, 'prozent', 15.00, 'Boost', 3),
(13, 'Fledermaus-Chorprobe 2', 1250, 'prozent', 15.00, 'Boost', 3),
(14, 'Fledermaus-Chorprobe 3', 2000, 'prozent', 20.00, 'Boost', 3),
(15, 'Geisterrausch 1', 750, 'prozent', 10.00, 'Boost', 4),
(16, 'Geisterrausch 2', 1750, 'prozent', 15.00, 'Boost', 4),
(17, 'Geisterrausch 3', 2750, 'prozent', 20.00, 'Boost', 4),
(18, 'Dämonenvertrag 1', 1250, 'prozent', 10.00, 'Boost', 5),
(19, 'Dämonenvertrag 2', 2500, 'prozent', 15.00, 'Boost', 5),
(20, 'Dämonenvertrag 3', 5000, 'prozent', 20.00, 'Boost', 5),
(21, 'Muskelkater-Finger', 50, 'absolut', 2.00, 'Klick', 21),
(22, 'Nekro-Handschuh', 150, 'absolut', 3.00, 'Klick', 22),
(23, 'Greifarm aus dem Jenseits', 350, 'absolut', 4.00, 'Klick', 23),
(24, 'Finger des Verderbens', 650, 'absolut', 5.00, 'Klick', 24),
(25, 'Magischer Klicker des Todes', 1000, 'absolut', 6.00, 'Klick', 25);

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
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_upgrades`
--

CREATE TABLE `user_upgrades` (
  `user_id` int(11) NOT NULL,
  `upgrade_id` int(11) UNSIGNED NOT NULL,
  `level` int(11) DEFAULT 1,
  `isActive` TINYINT(1) NOT NULL DEFAULT 1
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `upgrades`
--
ALTER TABLE `upgrades`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

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
