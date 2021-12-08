-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 08 dec 2021 om 11:32
-- Serverversie: 10.4.21-MariaDB
-- PHP-versie: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fakebook`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `friend`
--

CREATE TABLE `friend` (
  `id` int(11) NOT NULL,
  `userId1` int(11) DEFAULT NULL,
  `userId2` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `friend_request`
--

CREATE TABLE `friend_request` (
  `id` int(11) NOT NULL,
  `senderId` int(11) DEFAULT NULL,
  `receiverId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `page`
--

CREATE TABLE `page` (
  `id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `content` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `page`
--

INSERT INTO `page` (`id`, `title`, `content`) VALUES
(1, 'Meld je aan of registreer je', NULL),
(2, 'Registreren', NULL),
(3, 'Tijdlijn', NULL),
(4, 'Uitloggen', NULL),
(5, 'Profiel', NULL),
(6, 'Profiel bewerken', NULL),
(7, 'Ontdekken', 'Op deze pagina kun je mensen zoeken via de zoekbalk, en daaronder vind je een overzicht met gemeenschappelijke vrienden');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `postDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `post`
--

INSERT INTO `post` (`id`, `userId`, `content`, `postDate`) VALUES
(1, 1, 'Niks!', '2021-09-30 11:13:12'),
(2, 1, 'Niks!', '2021-09-30 11:13:32'),
(3, 1, 'Niks!', '2021-09-30 11:54:10'),
(4, 2, 'Testen', '2021-10-07 11:21:30'),
(5, 2, 'Test of dit werkt', '2021-10-07 11:31:38'),
(6, 2, 'Dit\r\n\r\nis\r\n\r\nvet\r\n\r\ncool', '2021-10-07 11:31:47'),
(7, 2, 'Niks', '2021-10-07 11:37:39'),
(8, 2, 'Hoi', '2021-10-07 12:21:44'),
(9, 2, 'fetgg', '2021-12-08 08:46:50');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(250) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `firstName` varchar(100) DEFAULT NULL,
  `lastName` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `profilePicUrl` varchar(500) DEFAULT NULL,
  `biography` text DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `gender` int(11) DEFAULT NULL,
  `profileFont` varchar(100) DEFAULT NULL,
  `profileColor` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `firstName`, `lastName`, `country`, `profilePicUrl`, `biography`, `birthdate`, `gender`, `profileFont`, `profileColor`) VALUES
(2, 'jaimy.tieck@gmail.com', '$2y$10$Z0BYfxDq.AR9DEqaGWFfoOezDNa948XAyE4GR/Qbmm4.Ry81XQZU6', 'Henk Peter', 'Gerardus', 'Duitsland', 'gorilla-g0c394d5ed_1920.jpg', 'hoi ik ben henk peter gerardus', '2003-01-29', 0, 'Lato', '#0ebe3a'),
(3, 'info@jaimytieck.com', '$2y$10$TxAYhtWRbelRLRgZC0qGwOqccp4Ww6sMPDc1gwrua4jJU9lAy9eRq', 'Jaimy', 'Tieck', 'Albanië', NULL, NULL, '2003-08-12', 0, 'roboto', '#00ff00');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `friend`
--
ALTER TABLE `friend`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `friend_request`
--
ALTER TABLE `friend_request`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `page`
--
ALTER TABLE `page`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `friend`
--
ALTER TABLE `friend`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT voor een tabel `friend_request`
--
ALTER TABLE `friend_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT voor een tabel `page`
--
ALTER TABLE `page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT voor een tabel `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT voor een tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
