-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 10. Mai 2022 um 09:30
-- Server-Version: 10.4.21-MariaDB
-- PHP-Version: 7.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `usarps`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `gameround`
--

CREATE TABLE `gameround` (
  `pk_gameround_id` int(11) NOT NULL,
  `fk_pk_player_1_id` int(11) NOT NULL,
  `fk_pk_player_2_id` int(11) NOT NULL,
  `fk_pk_player_1_pick_id` varchar(8) NOT NULL,
  `fk_pk_player_2_pick_id` varchar(8) NOT NULL,
  `fk_pk_winner_id` int(11) NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `gameround`
--

INSERT INTO `gameround` (`pk_gameround_id`, `fk_pk_player_1_id`, `fk_pk_player_2_id`, `fk_pk_player_1_pick_id`, `fk_pk_player_2_pick_id`, `fk_pk_winner_id`, `date_time`) VALUES
(1, 1, 2, 'rock', 'paper', 2, '2022-05-10 07:28:03'),
(2, 3, 2, 'scissors', 'paper', 3, '2022-05-10 07:28:03'),
(3, 3, 4, 'rock', 'scissors', 3, '2022-05-10 07:28:03'),
(4, 5, 4, 'rock', 'rock', -1, '2022-05-10 07:28:03'),
(5, 1, 5, 'rock', 'paper', 5, '2022-05-10 07:28:03');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pick`
--

CREATE TABLE `pick` (
  `pk_pick` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `pick`
--

INSERT INTO `pick` (`pk_pick`) VALUES
('paper'),
('rock'),
('scissors');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `player`
--

CREATE TABLE `player` (
  `pk_player_id` int(11) NOT NULL,
  `firstname` text NOT NULL,
  `lastname` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `player`
--

INSERT INTO `player` (`pk_player_id`, `firstname`, `lastname`) VALUES
(1, 'Joshua', 'Hugo'),
(2, 'Florian', 'Kozel'),
(3, 'Harman', 'Danone'),
(4, 'Makob', 'Jucherl'),
(5, 'Mister', 'Small');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `gameround`
--
ALTER TABLE `gameround`
  ADD PRIMARY KEY (`pk_gameround_id`),
  ADD KEY `fk_pk_player_1_id` (`fk_pk_player_1_id`,`fk_pk_player_2_id`,`fk_pk_player_1_pick_id`,`fk_pk_player_2_pick_id`),
  ADD KEY `fk_pk_winner_id` (`fk_pk_winner_id`);

--
-- Indizes für die Tabelle `pick`
--
ALTER TABLE `pick`
  ADD PRIMARY KEY (`pk_pick`);

--
-- Indizes für die Tabelle `player`
--
ALTER TABLE `player`
  ADD PRIMARY KEY (`pk_player_id`);

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `gameround`
--
ALTER TABLE `gameround`
    ADD CONSTRAINT `fk_pk_player_1` FOREIGN KEY (`fk_pk_player_1_id`) REFERENCES `player` (`pk_player_id`) ON DELETE CASCADE,
    ADD CONSTRAINT `fk_pk_player_2` FOREIGN KEY (`fk_pk_player_2_id`) REFERENCES `player` (`pk_player_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
