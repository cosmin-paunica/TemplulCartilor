-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Gazdă: 127.0.0.1
-- Timp de generare: nov. 14, 2020 la 09:15 PM
-- Versiune server: 10.4.13-MariaDB
-- Versiune PHP: 7.3.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Bază de date: `templulcartilor`
--

-- --------------------------------------------------------

-- Structuri pentru tabele

CREATE TABLE `abonamente` (
  `id_utilizator` int(11) NOT NULL,
  `data_inceput` date NOT NULL,
  `data_expirare` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `autori_carti` (
  `nume_autor` varchar(63) NOT NULL,
  `id_carte` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `carti` (
  `id_carte` int(11) NOT NULL,
  `titlu` varchar(63) NOT NULL,
  `id_limba` int(11) NOT NULL,
  `data_publicare` date DEFAULT NULL,
  `numar_pagini` int(11) DEFAULT NULL,
  `fisier_imagine` varchar(255) DEFAULT NULL,
  `id_serie` int(11) DEFAULT NULL,
  `link_goodreads` varchar(255) DEFAULT NULL,
  `numar_exemplare` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `imprumuturi` (
  `id_utilizator` int(11) NOT NULL,
  `id_carte` int(11) NOT NULL,
  `data_inceput` date NOT NULL,
  `termen_predare` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `limbi` (
  `id_limba` int(11) NOT NULL,
  `nume_limba` varchar(63) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `note` (
  `id_utilizator` int(11) NOT NULL,
  `id_carte` int(11) NOT NULL,
  `data_nota` datetime NOT NULL,
  `valoare_nota` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `recenzii` (
  `id_utilizator` int(11) NOT NULL,
  `id_carte` int(11) NOT NULL,
  `data_recenzie` datetime NOT NULL,
  `mesaj` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `serii` (
  `id_serie` int(11) NOT NULL,
  `nume_serie` varchar(63) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `utilizatori` (
  `id_utilizator` int(11) NOT NULL,
  `email` varchar(63) NOT NULL,
  `prenume` varchar(63) NOT NULL,
  `nume` varchar(63) NOT NULL,
  `parola` varchar(255) NOT NULL,
  `rol` enum('nevalidat','simplu','bibliotecar','admin') NOT NULL DEFAULT 'nevalidat'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Indexuri pentru tabele

ALTER TABLE `abonamente`
  ADD PRIMARY KEY (`id_utilizator`,`data_inceput`);

ALTER TABLE `autori_carti`
  ADD PRIMARY KEY (`nume_autor`,`id_carte`),
  ADD KEY `autori_carti_ibfk_1` (`id_carte`);

ALTER TABLE `carti`
  ADD PRIMARY KEY (`id_carte`),
  ADD UNIQUE KEY `titlu` (`titlu`),
  ADD KEY `carti_ibfk_1` (`id_limba`),
  ADD KEY `carti_ibfk_2` (`id_serie`);

ALTER TABLE `imprumuturi`
  ADD PRIMARY KEY (`id_utilizator`,`id_carte`,`data_inceput`),
  ADD KEY `imprumuturi_ibfk_2` (`id_carte`);

ALTER TABLE `limbi`
  ADD PRIMARY KEY (`id_limba`);

ALTER TABLE `note`
  ADD PRIMARY KEY (`id_utilizator`,`id_carte`),
  ADD KEY `note_ibfk_2` (`id_carte`);

ALTER TABLE `recenzii`
  ADD PRIMARY KEY (`id_utilizator`,`id_carte`),
  ADD KEY `recenzii_ibfk_2` (`id_carte`);

ALTER TABLE `serii`
  ADD PRIMARY KEY (`id_serie`);

ALTER TABLE `utilizatori`
  ADD PRIMARY KEY (`id_utilizator`),
  ADD UNIQUE KEY `email` (`email`);

-- AUTO_INCREMENT pentru tabele eliminate

ALTER TABLE `carti`
  MODIFY `id_carte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `utilizatori`
  MODIFY `id_utilizator` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

-- Constrângeri pentru tabele

ALTER TABLE `abonamente`
  ADD CONSTRAINT `abonamente_ibfk_1` FOREIGN KEY (`id_utilizator`) REFERENCES `utilizatori` (`id_utilizator`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `autori_carti`
  ADD CONSTRAINT `autori_carti_ibfk_1` FOREIGN KEY (`id_carte`) REFERENCES `carti` (`id_carte`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `carti`
  ADD CONSTRAINT `carti_ibfk_1` FOREIGN KEY (`id_limba`) REFERENCES `limbi` (`id_limba`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `carti_ibfk_2` FOREIGN KEY (`id_serie`) REFERENCES `serii` (`id_serie`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `imprumuturi`
  ADD CONSTRAINT `imprumuturi_ibfk_1` FOREIGN KEY (`id_utilizator`) REFERENCES `utilizatori` (`id_utilizator`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `imprumuturi_ibfk_2` FOREIGN KEY (`id_carte`) REFERENCES `carti` (`id_carte`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `note`
  ADD CONSTRAINT `note_ibfk_1` FOREIGN KEY (`id_utilizator`) REFERENCES `utilizatori` (`id_utilizator`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `note_ibfk_2` FOREIGN KEY (`id_carte`) REFERENCES `carti` (`id_carte`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `recenzii`
  ADD CONSTRAINT `recenzii_ibfk_1` FOREIGN KEY (`id_utilizator`) REFERENCES `utilizatori` (`id_utilizator`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `recenzii_ibfk_2` FOREIGN KEY (`id_carte`) REFERENCES `carti` (`id_carte`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
