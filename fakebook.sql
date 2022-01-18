-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 18 jan 2022 om 11:52
-- Serverversie: 10.4.21-MariaDB
-- PHP-versie: 8.0.10

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

--
-- Gegevens worden geëxporteerd voor tabel `friend`
--

INSERT INTO `friend` (`id`, `userId1`, `userId2`) VALUES
(5, 2, 6),
(6, 2, 9),
(7, 6, 4),
(8, 6, 5),
(9, 9, 7);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `friend_request`
--

CREATE TABLE `friend_request` (
  `id` int(11) NOT NULL,
  `senderId` int(11) DEFAULT NULL,
  `receiverId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `friend_request`
--

INSERT INTO `friend_request` (`id`, `senderId`, `receiverId`) VALUES
(7, 8, 2),
(10, 10, 2);

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
  `filename` varchar(200) DEFAULT NULL,
  `postDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `post`
--

INSERT INTO `post` (`id`, `userId`, `content`, `filename`, `postDate`) VALUES
(10, 2, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris quis accumsan tortor, non vehicula libero. Nulla nec tempus ante. Maecenas aliquet, tortor quis sodales porta, eros orci varius mauris, in.', NULL, '2022-01-18 11:23:54'),
(11, 6, 'Curabitur lacus justo, fringilla in lectus at, porta molestie dui. Donec sit amet fermentum libero. In eu molestie felis. Nam eu sollicitudin dui. In tellus nisl, maximus quis mattis a, sollicitudin non dolor. Ut malesuada, nisl ut mollis vestibulum, libero mauris bibendum odio, in feugiat sem neque in augue.', NULL, '2022-01-18 11:24:06'),
(12, 6, 'Nam accumsan consequat eros, at maximus leo lacinia vitae. Donec egestas in sapien non posuere. Duis congue mattis lorem eget hendrerit. Cras dui odio, ornare vitae ante quis', NULL, '2022-01-18 11:24:12'),
(13, 9, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce finibus massa turpis, sed viverra libero feugiat nec. Duis vel mi tincidunt, auctor ligula sit amet, iaculis urna. Ut tortor nibh, porta in odio nec, dapibus feugiat arcu. Mauris non tempor lacus. Quisque commodo commodo urna.', NULL, '2022-01-18 11:24:16'),
(14, 5, 'Nunc et ipsum imperdiet, porttitor nisi quis, mollis orci. Aenean venenatis, lacus id luctus pellentesque, velit tortor placerat dui, a bibendum sapien nisi a sapien.', NULL, '2022-01-18 11:24:22'),
(15, 7, 'Interdum et malesuada fames ac ante ipsum primis in faucibus. Nunc in elit ac nunc pretium condimentum sit amet eu leo. Cras quis mollis purus, id imperdiet orci. Sed consectetur felis nec ullamcorper egestas. Phasellus a elit ac orci gravida blandit. Cras sit amet diam quis nunc hendrerit interdum nec nec purus.', NULL, '2022-01-18 11:24:28'),
(16, 10, 'Nulla rutrum, lacus vitae posuere dapibus, risus lorem cursus risus, vel sodales leo arcu elementum diam. Curabitur velit dui, mattis ut orci a, volutpat eleifend orci. Suspendisse et mi urna.', NULL, '2022-01-18 11:24:33'),
(17, 9, 'Cras tincidunt enim neque, et sagittis nibh consequat non. Phasellus tempus turpis arcu, nec lobortis ligula dictum et.', NULL, '2022-01-18 11:24:38');

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
(2, 'testgebruiker1@gmail.com', '$2y$10$Lj3dkmhU1zo0Km6rUxzGbuWd8Qt4pHuzowSv.xs344rTJ723Cqgg6', 'Docent', 'Account', 'Nederland', 'download.png', '', '1997-05-08', 0, 'Roboto', '#ff0000'),
(4, 'testgebruiker2@gmail.com', '$2y$10$Lj3dkmhU1zo0Km6rUxzGbuWd8Qt4pHuzowSv.xs344rTJ723Cqgg6', 'Henk', 'Visser', 'Nederland', NULL, NULL, '1997-07-05', 0, NULL, NULL),
(5, 'testgebruiker3@gmail.com', '$2y$10$Lj3dkmhU1zo0Km6rUxzGbuWd8Qt4pHuzowSv.xs344rTJ723Cqgg6', 'Bert', 'Visscher', 'Nederland', NULL, NULL, '1997-07-05', 0, NULL, NULL),
(6, 'testgebruiker4@gmail.com', '$2y$10$Lj3dkmhU1zo0Km6rUxzGbuWd8Qt4pHuzowSv.xs344rTJ723Cqgg6', 'Hendrik', 'Vos', 'Nederland', NULL, NULL, '1996-04-09', 0, NULL, NULL),
(7, 'testgebruiker5@gmail.com', '$2y$10$Lj3dkmhU1zo0Km6rUxzGbuWd8Qt4pHuzowSv.xs344rTJ723Cqgg6', 'Petra', 'Bloem', 'Nederland', NULL, 'Candy crush level 200', '1995-11-08', 1, NULL, NULL),
(8, 'testgebruiker6@gmail.com', '$2y$10$Lj3dkmhU1zo0Km6rUxzGbuWd8Qt4pHuzowSv.xs344rTJ723Cqgg6', 'Bob', 'de Bouwer', 'Nederland', NULL, 'Kunnen wij het maken?', '1995-11-08', 0, NULL, NULL),
(9, 'testgebruiker7@gmail.com', '$2y$10$Lj3dkmhU1zo0Km6rUxzGbuWd8Qt4pHuzowSv.xs344rTJ723Cqgg6', 'Pieter', 'Flatgebouw', 'Nederland', NULL, '13 hoog', '1995-09-14', 0, NULL, NULL),
(10, 'testgebruiker8@gmail.com', '$2y$10$Lj3dkmhU1zo0Km6rUxzGbuWd8Qt4pHuzowSv.xs344rTJ723Cqgg6', 'Anja', 'Peters', 'Nederland', NULL, NULL, '1995-09-14', 1, NULL, NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT voor een tabel `friend_request`
--
ALTER TABLE `friend_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT voor een tabel `page`
--
ALTER TABLE `page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT voor een tabel `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT voor een tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
