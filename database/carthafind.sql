-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mar 17, 2018 alle 00:33
-- Versione del server: 10.1.29-MariaDB
-- Versione PHP: 7.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `carthafind`
--
CREATE DATABASE IF NOT EXISTS `carthafind` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `carthafind`;

-- --------------------------------------------------------

--
-- Struttura della tabella `author`
--

CREATE TABLE `author` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `author`
--

INSERT INTO `author` (`id`) VALUES
(1),
(4),
(5);

-- --------------------------------------------------------

--
-- Struttura della tabella `contains`
--

CREATE TABLE `contains` (
  `id` int(11) NOT NULL,
  `id_project` int(11) DEFAULT NULL,
  `id_keyword` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `contains`
--

INSERT INTO `contains` (`id`, `id_project`, `id_keyword`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 4),
(4, 1, 6),
(5, 1, 7),
(6, 1, 10),
(7, 1, 11),
(8, 1, 13);

-- --------------------------------------------------------

--
-- Struttura della tabella `keyword`
--

CREATE TABLE `keyword` (
  `id` int(11) NOT NULL,
  `name` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `keyword`
--

INSERT INTO `keyword` (`id`, `name`) VALUES
(1, 'game'),
(2, 'arduino'),
(3, 'raspberry'),
(4, 'elettronico'),
(5, 'libreria'),
(6, 'database'),
(7, 'php'),
(8, 'java'),
(9, 'c#'),
(10, 'html'),
(11, 'ethernet'),
(12, 'wifi'),
(13, 'shield'),
(14, 'server'),
(15, 'client'),
(16, 'completo');

-- --------------------------------------------------------

--
-- Struttura della tabella `participates`
--

CREATE TABLE `participates` (
  `id` int(11) NOT NULL,
  `id_project` int(11) DEFAULT NULL,
  `id_author` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `participates`
--

INSERT INTO `participates` (`id`, `id_project`, `id_author`) VALUES
(1, 1, 4),
(2, 1, 5),
(3, 1, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `project`
--

CREATE TABLE `project` (
  `id` int(11) NOT NULL,
  `title` varchar(40) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `length` int(11) DEFAULT NULL,
  `final_vote` int(11) DEFAULT NULL,
  `progress` int(11) DEFAULT NULL,
  `comment` longtext,
  `id_responsible` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `project`
--

INSERT INTO `project` (`id`, `title`, `description`, `length`, `final_vote`, `progress`, `comment`, `id_responsible`) VALUES
(1, 'ReactionGame', 'Realizzazione di un sistema di gioco e simulazione per l\'allenamento dei tempi di reazione ed il miglioramento della coordinazione per sportivi basato su Arduino ', 94, 4, 90, 'Quasi completo e funzionante', 6);


CREATE TABLE `teacher` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



INSERT INTO `teacher` (`id`) VALUES
(6);

-- --------------------------------------------------------

--
-- Struttura della tabella `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  `surname` varchar(20) DEFAULT NULL,
  `granted` enum('Administrator','Registered','Normal') DEFAULT NULL,
  `username` varchar(20) NOT NULL,
  `password` binary(64) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `user`
--

INSERT INTO `user` (`id`, `name`, `surname`, `granted`, `username`, `password`, `email`) VALUES
(1, 'Nadir', 'Barlozzo', 'Administrator', 'barnad', 0x7261756c6f3100000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000, 'nadir.barlozzo@samtrevano.ch'),
(4, 'Duchan', 'Bulloni', 'Registered', 'djuman', 0x7261756c6f3200000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000, 'erik.stalliviere@samtrevano.ch'),
(5, 'Erik', 'Stalliviere', 'Registered', 'stanli', 0x7261756c6f3300000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000, 'djuman.bulloni@samtrevano.ch'),
(6, 'Luca', 'Muggiasca', 'Administrator', 'giasca', 0x63617374696f6e650000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000, 'luca.muggiasca@edu.ti.ch'),
(9, 'Francesco', 'Mussi', 'Normal', 'LL', 0x31393866373938393837396336393833303664306361393531666335626630303061666234623032336437356134656436343835313637356462343838316636, 'francesco.mussi@edu.ti.ch');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `contains`
--
ALTER TABLE `contains`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_contains_id_project` (`id_project`),
  ADD KEY `idx_contains_id_keyword` (`id_keyword`);

--
-- Indici per le tabelle `keyword`
--
ALTER TABLE `keyword`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `participates`
--
ALTER TABLE `participates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_participates_id_project` (`id_project`),
  ADD KEY `idx_participates_id_author` (`id_author`);

--
-- Indici per le tabelle `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_project_id_responsabile` (`id_responsible`);

--
-- Indici per le tabelle `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `contains`
--
ALTER TABLE `contains`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT per la tabella `keyword`
--
ALTER TABLE `keyword`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT per la tabella `participates`
--
ALTER TABLE `participates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `project`
--
ALTER TABLE `project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `author`
--
ALTER TABLE `author`
  ADD CONSTRAINT `fk_author_user` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `contains`
--
ALTER TABLE `contains`
  ADD CONSTRAINT `fk_contains_keyword` FOREIGN KEY (`id_keyword`) REFERENCES `keyword` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_contains_project` FOREIGN KEY (`id_project`) REFERENCES `project` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `participates`
--
ALTER TABLE `participates`
  ADD CONSTRAINT `fk_participates_author` FOREIGN KEY (`id_author`) REFERENCES `author` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_participates_project` FOREIGN KEY (`id_project`) REFERENCES `project` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `fk_project_teacher` FOREIGN KEY (`id_responsible`) REFERENCES `teacher` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `teacher`
--
ALTER TABLE `teacher`
  ADD CONSTRAINT `fk_teacher_user` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
