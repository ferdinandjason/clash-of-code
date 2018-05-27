-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2018 at 10:01 AM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.1.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vkvxweok_mbd_05111640000033`
--

-- --------------------------------------------------------

--
-- Table structure for table `type_rating`
--

CREATE TABLE `type_rating` (
  `type_rating_id` int(11) NOT NULL,
  `type_rating_title` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `type_rating`
--

INSERT INTO `type_rating` (`type_rating_id`, `type_rating_title`) VALUES
(1, 'Difficulty'),
(2, 'Fun');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `type_rating`
--
ALTER TABLE `type_rating`
  ADD PRIMARY KEY (`type_rating_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
