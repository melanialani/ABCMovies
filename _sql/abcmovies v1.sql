-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 16, 2017 at 04:34 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.5.37

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `abcmovies`
--

-- --------------------------------------------------------

--
-- Table structure for table `film`
--

CREATE TABLE `film` (
  `id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `summary` text NOT NULL,
  `genre` varchar(256) DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `playing_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `length` smallint(3) DEFAULT NULL,
  `director` varchar(125) DEFAULT NULL,
  `writer` text,
  `actors` text,
  `poster` text,
  `trailer` text,
  `imdb_id` varchar(12) DEFAULT NULL,
  `imdb_rating` float DEFAULT NULL,
  `metascore` int(2) DEFAULT NULL COMMENT 'from metacritic.com',
  `twitter_positif` int(6) DEFAULT NULL,
  `twitter_negatif` int(6) DEFAULT NULL,
  `rating` float DEFAULT NULL,
  `status` tinyint(1) NOT NULL COMMENT '0 for Coming Soon, 1 for Now Playing, 2 for Old Movies, 3 for Not Visible'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `film`
--

INSERT INTO `film` (`id`, `title`, `summary`, `genre`, `year`, `playing_date`, `length`, `director`, `writer`, `actors`, `poster`, `trailer`, `imdb_id`, `imdb_rating`, `metascore`, `twitter_positif`, `twitter_negatif`, `rating`, `status`) VALUES
(1, 'The LEGO Batman Movie', 'Dengan datangnya Joker di kota Gotham, LEGO Batman harus melindungi para penduduk kota dari ancaman sang penjahat. Akan tetapi Batman menyadari jika kini ia tidak dapat beraksi sendirian sebagai superhero.', 'Animation, Action, Adventure', 2017, '2017-02-10 00:00:00', 104, 'Chris McKay', 'Seth Grahame-Smith (screenplay), Chris McKenna (screenplay), Erik Sommers (screenplay), Jared Stern (screenplay), John Whitti', 'Will Arnett, Michael Cera, Rosario Dawson, Ralph Fiennes', '<img title=''THE LEGO BATMAN MOVIE (IMAX 3D)'' alt=''THE LEGO BATMAN MOVIE (IMAX 3D)'' src=''http://www.21cineplex.com/data/gallery/pictures/148523299121583_100x147.jpg''/>', '<a style=''color: #009EF0;'' href=''http://www.21cineplex.com/video/trailer-hd/the-lego-batman-movie-(imax-3d),4435.htm'' class=''navtxtimg sym1''>Trailer<\\a>', 'tt4116284', 7.8, 75, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id` int(11) NOT NULL,
  `film_id` int(11) NOT NULL,
  `username` varchar(32) NOT NULL,
  `rating` tinyint(2) NOT NULL,
  `review` text NOT NULL,
  `tanggal` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `username` varchar(32) NOT NULL COMMENT 'cannot be changed',
  `email` varchar(125) NOT NULL COMMENT 'cannot be changed',
  `password` varchar(32) NOT NULL,
  `name` varchar(125) NOT NULL,
  `birthdate` date NOT NULL,
  `picture` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `email`, `password`, `name`, `birthdate`, `picture`) VALUES
('admin', 'admin@abcmovies.co.id', '21232f297a57a5a743894a0e4a801fc3', 'Admin of ABCMovies', '1990-12-25', './pictures/admin.jpg'),
('meloniaseven', 'meloniaseven@gmail.com', '3e4063d6f38d738b1e6608a2c1f119d8', 'Melania Laniwati', '1995-02-07', './pictures/meloniaseven.gif');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `film`
--
ALTER TABLE `film`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`username`,`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `film`
--
ALTER TABLE `film`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
