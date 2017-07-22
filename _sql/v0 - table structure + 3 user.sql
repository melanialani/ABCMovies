-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 12, 2017 at 04:32 AM
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
  `name` varchar(256) NOT NULL,
  `picture` varchar(256) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `film`
--

CREATE TABLE `film` (
  `id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `summary` text,
  `genre` varchar(256) DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `playing_date` date DEFAULT NULL,
  `length` smallint(3) DEFAULT NULL,
  `director` varchar(256) DEFAULT NULL,
  `writer` varchar(256) DEFAULT NULL,
  `actors` varchar(256) DEFAULT NULL,
  `poster` varchar(256) DEFAULT NULL,
  `trailer` varchar(256) DEFAULT NULL,
  `imdb_id` varchar(12) DEFAULT NULL,
  `imdb_rating` float DEFAULT NULL,
  `metascore` int(2) DEFAULT NULL COMMENT 'from metacritic.com',
  `twitter_positif` int(6) DEFAULT '0',
  `twitter_negatif` int(6) DEFAULT '0',
  `twitter_search` varchar(256) DEFAULT NULL,
  `rating` float DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '3' COMMENT '0 for Coming Soon, 1 for Now Playing, 2 for Old Movies, 3 for Unchecked Coming Soon, 4 for Unchecked Now Playing'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

-- --------------------------------------------------------

--
-- Table structure for table `tweets`
--

CREATE TABLE `tweets` (
  `id` int(11) NOT NULL,
  `film_id` int(11) NOT NULL,
  `tweet` varchar(256) NOT NULL,
  `status` tinyint(1) DEFAULT NULL COMMENT '0 for negative, 1 for positive, 2 for not a review, 3 for neutral',
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `truth_rule` tinyint(1) DEFAULT NULL,
  `truth_naive` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tweets_final`
--

CREATE TABLE `tweets_final` (
  `id` int(11) NOT NULL,
  `ori_id` varchar(256) NOT NULL,
  `film_id` int(11) NOT NULL,
  `text` varchar(256) NOT NULL,
  `is_review` tinyint(1) NOT NULL COMMENT '1 if system says its a review, 0 if non-review',
  `is_positive` tinyint(1) NOT NULL COMMENT '1 if system says it''s positive, 0 if negative',
  `yes_review` tinyint(1) DEFAULT NULL COMMENT '1 if the tweet is indeed a review, 0 if not a review or havent checked',
  `yes_positive` tinyint(1) DEFAULT NULL COMMENT '1 if the tweet is indeed has positive sentiment, 0 if negative or havent checked',
  `confirmed` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 if the tweet has been checked by human, 0 if not checked',
  `duplicate` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 if the tweet has a duplicate text before in the same table, 0 if not'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tweets_lexicon`
--

CREATE TABLE `tweets_lexicon` (
  `id` int(11) NOT NULL,
  `ori_id` varchar(256) NOT NULL,
  `intersect` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tweets_ori`
--

CREATE TABLE `tweets_ori` (
  `id` int(11) NOT NULL,
  `film_id` int(11) NOT NULL,
  `twitter_id` varchar(256) NOT NULL,
  `text` varchar(256) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tweets_regex`
--

CREATE TABLE `tweets_regex` (
  `id` int(11) NOT NULL,
  `ori_id` varchar(256) NOT NULL,
  `text` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tweets_replaced`
--

CREATE TABLE `tweets_replaced` (
  `id` int(11) NOT NULL,
  `ori_id` varchar(256) NOT NULL,
  `text` varchar(256) NOT NULL,
  `intersect` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `email` varchar(256) NOT NULL COMMENT 'cannot be changed',
  `password` varchar(256) NOT NULL,
  `name` varchar(256) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `picture` varchar(256) DEFAULT './pictures/default.png',
  `role` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 for user, 0 for admin'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`email`, `password`, `name`, `birthdate`, `picture`, `role`) VALUES
('melanialani@gmail.com', '21232f297a57a5a743894a0e4a801fc3', 'Admin of ABCMovies', '1995-12-25', './pictures/1488602184adm.png', 0),
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
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_review_film_id` (`film_id`),
  ADD KEY `fk_review_email` (`email`);

--
-- Indexes for table `tweets`
--
ALTER TABLE `tweets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tweets_film_id` (`film_id`);

--
-- Indexes for table `tweets_final`
--
ALTER TABLE `tweets_final`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `twitter_id` (`ori_id`);

--
-- Indexes for table `tweets_lexicon`
--
ALTER TABLE `tweets_lexicon`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `twitter_id` (`ori_id`);

--
-- Indexes for table `tweets_ori`
--
ALTER TABLE `tweets_ori`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `twitter_id` (`twitter_id`);

--
-- Indexes for table `tweets_regex`
--
ALTER TABLE `tweets_regex`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `twitter_id` (`ori_id`);

--
-- Indexes for table `tweets_replaced`
--
ALTER TABLE `tweets_replaced`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `twitter_id` (`ori_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `film`
--
ALTER TABLE `film`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=223;
--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `tweets`
--
ALTER TABLE `tweets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3974;
--
-- AUTO_INCREMENT for table `tweets_final`
--
ALTER TABLE `tweets_final`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tweets_lexicon`
--
ALTER TABLE `tweets_lexicon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tweets_ori`
--
ALTER TABLE `tweets_ori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4046;
--
-- AUTO_INCREMENT for table `tweets_regex`
--
ALTER TABLE `tweets_regex`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tweets_replaced`
--
ALTER TABLE `tweets_replaced`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `fk_review_email` FOREIGN KEY (`email`) REFERENCES `user` (`email`),
  ADD CONSTRAINT `fk_review_film_id` FOREIGN KEY (`film_id`) REFERENCES `film` (`id`);

--
-- Constraints for table `tweets`
--
ALTER TABLE `tweets`
  ADD CONSTRAINT `fk_tweets_film_id` FOREIGN KEY (`film_id`) REFERENCES `film` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
