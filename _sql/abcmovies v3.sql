-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 10, 2017 at 02:41 PM
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
-- Table structure for table `banner`
--

CREATE TABLE `banner` (
  `id` int(11) NOT NULL,
  `name` varchar(125) NOT NULL,
  `picture` varchar(256) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `banner`
--

INSERT INTO `banner` (`id`, `name`, `picture`, `status`) VALUES
(5, 'kong skull island', './pictures/banner/kong-banner-2.jpg', 1),
(6, 'galih dan ratna', './pictures/banner/galihratna_s.png', 1),
(7, 'beauty and the beast', './pictures/banner/new_beauty_and_the_beast__2017__banner_poster_by_artlover67-davww9h.jpg', 1),
(9, 'logan', './pictures/banner/wolverine-last-time-banner.jpg', 1);

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
  `playing_date` date DEFAULT NULL,
  `length` smallint(3) DEFAULT NULL,
  `director` varchar(125) DEFAULT NULL,
  `writer` text,
  `actors` text,
  `poster` varchar(256) DEFAULT NULL,
  `trailer` text,
  `imdb_id` varchar(12) DEFAULT NULL,
  `imdb_rating` float DEFAULT NULL,
  `metascore` int(2) DEFAULT NULL COMMENT 'from metacritic.com',
  `twitter_positif` int(6) DEFAULT '0',
  `twitter_negatif` int(6) DEFAULT '0',
  `rating` float DEFAULT '0',
  `status` tinyint(1) NOT NULL COMMENT '0 for Coming Soon, 1 for Now Playing, 2 for Old Movies, 3 for Not Visible'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `film`
--

INSERT INTO `film` (`id`, `title`, `summary`, `genre`, `year`, `playing_date`, `length`, `director`, `writer`, `actors`, `poster`, `trailer`, `imdb_id`, `imdb_rating`, `metascore`, `twitter_positif`, `twitter_negatif`, `rating`, `status`) VALUES
(1, 'The LEGO Batman Movie', 'Dengan datangnya Joker di kota Gotham, LEGO Batman harus melindungi para penduduk kota dari ancaman sang penjahat. Akan tetapi Batman menyadari jika kini ia tidak dapat beraksi sendirian sebagai superhero.', 'Animation, Action, Adventure', 2017, '2017-02-10', 104, 'Chris McKay', 'Seth Grahame-Smith (screenplay), Chris McKenna (screenplay), Erik Sommers (screenplay), Jared Stern (screenplay), John Whitti', 'Will Arnett, Michael Cera, Rosario Dawson, Ralph Fiennes', '148523299121583', '<iframe width="854" height="480" src="https://www.youtube.com/embed/LZSQTVdF3QM" frameborder="0" allowfullscreen></iframe>', 'tt4116284', 7.8, 75, NULL, NULL, NULL, 1),
(2, 'Logan', 'Pada tahun 2024, Logan (Hugh Jackman) dan Profesor Charles Xavier (Patrick Stewart) harus bertahan tanpa X-Men ketika perusahaan yang dipimpin oleh Nathaniel Essex menghancurkan dunia. Keadaan menjadi semakin rumit dengan kemampuan regenerasi mutan logan yang mulai menghilang dan Xavier yang menderita Alzheimer. Logan harus mengalahkan Nathaniel Essex dengan bantuan seorang anak perempuan bernama Laura Kinney yang merupakan hasil kloning dari Wolverine', 'Action, Drama, Sci-Fi', 2017, '2017-03-03', 135, 'James Mangold', 'James Mangold (story by), Scott Frank (screenplay), James Mangold (screenplay), Michael Green (screenplay)', 'Hugh Jackman, Patrick Stewart, Dafne Keen, Boyd Holbrook', '148704367892393', '<iframe width="854" height="480" src="https://www.youtube.com/embed/Div0iP65aZo" frameborder="0" allowfullscreen></iframe>', 'tt3315342', 9.6, 73, NULL, NULL, NULL, 1),
(3, 'Max Steel', 'Max McGrath (Ben Winchell) adalah seorang remaja berusia 16 tahun yang tengah beradaptasi dengan lingkungan barunya. Tidak lama sejak kepindahannya, ia menemukan bahwa sang almarhum ayah yang pernah bekerja sebagai ilmuwan telah mewariskan kekuatan super yang dapat merubah', 'Action, Adventure, Family', 2016, '2016-10-14', 92, 'Stewart Hendler', 'Christopher Yost', 'Ben Winchell, Josh Brener, Maria Bello, Andy Garcia', '148792051549046', '<iframe width="854" height="480" src="https://www.youtube.com/embed/Tf4sa0BVJVw" frameborder="0" allowfullscreen></iframe>', 'tt1472584', 4.6, 22, NULL, NULL, 6.5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id` int(11) NOT NULL,
  `film_id` int(11) NOT NULL,
  `email` varchar(256) NOT NULL,
  `rating` tinyint(2) NOT NULL,
  `review` text NOT NULL,
  `tanggal` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`id`, `film_id`, `email`, `rating`, `review`, `tanggal`) VALUES
(4, 1, 'meloniaseven@gmail.com', 8, 'Great. Love this one. Super funny. So many lovable characters (I love the batman!!)', '2017-03-08 21:56:21'),
(5, 2, 'meloniaseven@gmail.com', 6, 'Meh, not that great.\r\nSangat mengecewakan ya buat yg udah nonton semua seri xmen. Ini film yg memang buat jadi akhirnya dr xmen, tapi ya ampun, mengecewakan deh pokoknya. Ceritanya ga jelas, karakteristiknya Wolverine The Legend yg dr awal cerita itu immortal + ageless, sekarang tiba-tiba jadi tua dan sakit"an. Darimana bisa kayak gitu? Ga dijelasin. Sama kayak film xmen sebelumnya (Future Past), ga jelas darimana awalnya. Top deh.', '2017-03-08 21:56:21'),
(6, 3, 'meloniaseven@gmail.com', 6, 'Film apa ini, ga jelas banget', '2017-03-08 21:58:49'),
(8, 1, 'scooby@gmail.com', 8, 'Top deh. Suka banget gw sama film ini.', '2017-03-08 21:59:50'),
(9, 2, 'scooby@gmail.com', 9, 'action-nya keren banget brooohhh!! love laura!!', '2017-03-08 21:59:50'),
(12, 3, 'scooby@gmail.com', 7, 'yahh oke lahh', '2017-03-09 17:36:27'),
(13, 2, 'admin@abcmovies.co.id', 8, 'cool action scenes', '2017-03-10 16:00:45');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `email` varchar(125) NOT NULL COMMENT 'cannot be changed',
  `password` varchar(32) NOT NULL,
  `name` varchar(125) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `picture` varchar(256) DEFAULT './pictures/default.png',
  `role` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 for user, 0 for admin'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`email`, `password`, `name`, `birthdate`, `picture`, `role`) VALUES
('admin@abcmovies.co.id', '21232f297a57a5a743894a0e4a801fc3', 'Admin of ABCMovies', '1995-12-25', './pictures/1488602184adm.png', 0),
('meloniaseven@gmail.com', '3e4063d6f38d738b1e6608a2c1f119d8', 'Melania Laniwati', '1995-02-07', './pictures/1488602110mel.jpg', 1),
('scooby@gmail.com', '38371fef7d829c7f8b0e2fedf7a04334', 'Scooby Doo', '1992-01-03', './pictures/1488602156sco.jpg', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banner`
--
ALTER TABLE `banner`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banner`
--
ALTER TABLE `banner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `film`
--
ALTER TABLE `film`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

ALTER TABLE `film` CHANGE `status` `status` TINYINT(1) NOT NULL DEFAULT '3' COMMENT '0 for Coming Soon, 1 for Now Playing, 2 for Old Movies, 3 for Not Visible';  
ALTER TABLE `film` CHANGE `status` `status` TINYINT(1) NOT NULL DEFAULT '3' COMMENT '0 for Coming Soon, 1 for Now Playing, 2 for Old Movies, 3 for Not Visible, 4 for Unchecked Coming Soon, 5 for Unchecked Now Playing';

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
