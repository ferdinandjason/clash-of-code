-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2018 at 07:56 AM
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

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_add_rating` (`p_type_rating_id` INTEGER, `p_level_id` INTEGER, `p_type_id` INTEGER, `p_user_id` INTEGER, `p_rating` INTEGER)  BEGIN
	set @id = (select max(`rating_id`) from `rating` group by rating_id) +1;
	insert into rating values(@id, p_type_rating_id, p_level_id, p_type_id, p_user_id, p_rating);
	select 1, 'Berhasil!';
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_change_password` (`p_username` VARCHAR(100), `p_old_pass` VARCHAR(25), `p_new_pass` VARCHAR(25))  BEGIN
	if exists (select 1 from `user` where `username` = p_username and `password` = md5(p_old_pass)) then
		if md5(p_new_pass) = (select `password` from `user` where username = p_username) then
			select 0, 'Password didnt change!';
		else
			update `user` set `password` = md5(p_new_pass) where username = p_username;
			select 1, 'Password has been changed successfully!';
		end if;
	else
		select 0, 'Your old password was entered incorrectly';
	end if;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_create_room` (IN `p_level` INT, IN `p_name` VARCHAR(100), IN `p_password` VARCHAR(100), IN `p_user` INT)  NO SQL
BEGIN
	SET @id = (SELECT MAX(room_id)+1 FROM room_detail ORDER BY room_id DESC LIMIT 1);
    INSERT INTO room_detail VALUES (@id,p_level,p_name,MD5(p_password),1);
    SELECT 1,"BERHASIL";
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_daftar` (IN `p_username` VARCHAR(100), IN `p_email` VARCHAR(100), IN `p_password` VARCHAR(25))  BEGIN
	if not exists (select 1 from `user` where email = p_email and username = p_username) then
		set @id = (select `user_id` from `user` order by user_id desc limit 1)+1;
		insert into `user` values (@id, 0,p_username, p_email, md5(p_password), 0, 0);
		select 1, 'Pendaftaran Sukses!';
	else
		select 0, 'Pendaftaran Gagal, Email atau Username sudah dipakai!';
	end if;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_into_room` (IN `p_room_id` INT, IN `p_user_id` INT, IN `p_password` VARCHAR(100))  BEGIN
	if exists(select 1 from room_detail where room_id = p_room_id and `password` = md5(p_password)) then
		insert into room values(p_room_id, p_user_id, now(), now() ,0);
		select 1, 'Berhasil!';
	else
		select 0, 'Gagal!';
	end if;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_login` (IN `p_email` VARCHAR(100), IN `p_password` CHAR(32))  BEGIN
	IF EXISTs (select 1 from `user` where email = p_email and `password` = md5(p_password)) then
		select 1, 'Login Sukses!',p_email;
	else
		select 0, 'Email atau Password salah!','@';
	end if;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_set_exp` (IN `p_user_id` INT, IN `p_star` INT)  NO SQL
BEGIN
	UPDATE `user` SET user_level = level_level + p_star WHERE user_id = p_user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_set_score` (IN `p_room_id` INT, IN `p_user_id` INT, IN `p_score` INT, IN `p_level_id` INT)  NO SQL
BEGIN
	UPDATE room SET score = p_score WHERE room_id = p_room_id AND user_id = p_user_id;
    SET @score = (SELECT score FROM room WHERE room_id = p_room_id AND user_id = p_user_id);
    UPDATE room SET end_time = NOW() WHERE room_id = p_room_id AND user_id = p_user_id;
    IF EXISTS (SELECT 1 FROM highscore WHERE level_id = p_level_id AND user_id = p_user_id) THEN
    	IF EXISTS (SELECT @score>score FROM highscore WHERE level_id = p_level_id AND user_id = p_user_id) THEN
    		UPDATE highscore SET score = @score WHERE level_id = p_level_id AND user_id = p_user_id;
        END IF;
    ELSE 
    	INSERT INTO highscore(level_id,user_id,score) VALUES(p_level_id,p_user_id,p_score);
    END IF;
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `fn_get_max_level` () RETURNS INT(11) NO SQL
BEGIN
	RETURN (SELECT MAX(`level_id`) FROM `level` GROUP BY level_id order by level_id DESC limit 1 );

END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `fn_is_joined_room` (`p_room_id` INT, `p_user_id` INT) RETURNS INT(11) READS SQL DATA
BEGIN
	IF EXISTS (SELECT * FROM room WHERE room_id = p_room_id AND user_id = p_user_id) THEN
    	RETURN 1;
    ELSE
    	RETURN 0;
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `highscore`
--

CREATE TABLE `highscore` (
  `highscore_id` int(11) NOT NULL,
  `level_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `score` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `highscore`
--

INSERT INTO `highscore` (`highscore_id`, `level_id`, `user_id`, `score`) VALUES
(1, 5, 4, 300);

-- --------------------------------------------------------

--
-- Table structure for table `level`
--

CREATE TABLE `level` (
  `level_id` int(11) NOT NULL,
  `grid` text,
  `stars` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `level`
--

INSERT INTO `level` (`level_id`, `grid`, `stars`) VALUES
(0, '0', '0'),
(1, '0000000000\r\n0000000000\r\n0000000000\r\n0000000000\r\n0000000000\r\n0000000000\r\n0000000000\r\n0000000000\r\n1111111112\r\n1111111111\r\n', '0000000000\r\n0000000000\r\n0000000000\r\n0000000000\r\n0000000000\r\n0000000000\r\n0000000000\r\n0000000000\r\n1010101010\r\n0101010101'),
(2, '0000000000\r\n0000000000\r\n0000000000\r\n0000000001\r\n0000000001\r\n0000000002\r\n0000000001\r\n0000000001\r\n0000000001\r\n1111111111\r\n', '0000000000\r\n0000000000\r\n0000000000\r\n0000000000\r\n0000000000\r\n0000000000\r\n0000000001\r\n0000000000\r\n0000000001\r\n0101010101\r\n'),
(3, '0000000000\r\n0000000000\r\n0000000000\r\n0000000000\r\n0000000000\r\n0000000000\r\n0000000000\r\n0000000000\r\n0000000002\r\n1111111111', '0000000000\r\n0000000000\r\n0000000000\r\n0000000000\r\n0000000000\r\n0000000000\r\n0000000000\r\n0000000000\r\n0000000000\r\n1111111111\r\n'),
(4, '2000000000\r\n1000000000\r\n1111000000\r\n0001000000\r\n0001000000\r\n0001000000\r\n0001000000\r\n1111000000\r\n1111000000\r\n1111000000', '1000000000\r\n0000000000\r\n0110000000\r\n0001000000\r\n0000000000\r\n0001000000\r\n0000000000\r\n1000000000\r\n1000000000\r\n1000000000\r\n'),
(5, '0000000002\r\n0000000011\r\n0000000110\r\n0000001100\r\n0000011000\r\n0000110000\r\n0001100000\r\n0011000000\r\n0110000000\r\n1100000000\r\n', '0000000001\r\n0000000010\r\n0000000100\r\n0000001000\r\n0000010000\r\n0000100000\r\n0001000000\r\n0010000000\r\n0100000000\r\n1000000000\r\n'),
(6, '0000000000\r\n0000000000\r\n0000000000\r\n0000000000\r\n0000000002\r\n0000111111\r\n0000001100\r\n0000001000\r\n0000001000\r\n1111111000\r\n', '0000000000\r\n0000000000\r\n0000000000\r\n0000000000\r\n0000000001\r\n0000110000\r\n0000000000\r\n0000000000\r\n0000000000\r\n1111110000\r\n'),
(7, '0000020000\r\n0000010000\r\n0000010000\r\n0000110000\r\n0001100000\r\n0011000000\r\n0110000000\r\n0100000000\r\n0100000000\r\n1100000000', '0000000000\r\n0000010000\r\n0000010000\r\n0000010000\r\n0000100000\r\n0001000000\r\n0010000000\r\n0100000000\r\n0100000000\r\n1100000000\r\n'),
(8, '1100000002\r\n1100000001\r\n0000000001\r\n0000000011\r\n0000000111\r\n0000001100\r\n1000011000\r\n1000110000\r\n1101100000\r\n1111000001', '0000000001\r\n0000000001\r\n0000000001\r\n0000000011\r\n0000000101\r\n0000001000\r\n0000010000\r\n0000110000\r\n0101100000\r\n0111000000\r\n'),
(9, '0000000000\r\n0000000000\r\n0000211000\r\n0000111000\r\n0000111111\r\n0000000001\r\n0000000001\r\n1110000111\r\n1110000111\r\n1111111111', '0000000000\r\n0000000000\r\n0000000000\r\n0000100000\r\n0000111111\r\n0000000001\r\n0000000001\r\n0000000000\r\n0000000110\r\n1111111111\r\n'),
(10, '0000000000\r\n1111111111\r\n1000000001\r\n1000000001\r\n1000011111\r\n1001110211\r\n1111010100\r\n1111010100\r\n1100010100\r\n1100011100', '0000000000\r\n0000000001\r\n1000000001\r\n1000000000\r\n1000011100\r\n1001010200\r\n1111000000\r\n1111000000\r\n1100000000\r\n1100011000\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `level_detail`
--

CREATE TABLE `level_detail` (
  `level_level` int(11) NOT NULL,
  `max_exp` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `level_detail`
--

INSERT INTO `level_detail` (`level_level`, `max_exp`) VALUES
(0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `level_rule_desc`
--

CREATE TABLE `level_rule_desc` (
  `level_id` int(11) NOT NULL,
  `rule_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `rating_id` int(11) NOT NULL,
  `type_rating_id` int(11) NOT NULL,
  `level_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `room_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `end_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `score` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`room_id`, `user_id`, `start_time`, `end_time`, `score`) VALUES
(1, 2, '2018-05-26 16:49:35', '2018-05-26 16:49:35', 0),
(1, 3, '2018-05-26 17:18:20', '2018-05-26 11:25:34', 0),
(1, 4, '2018-05-26 17:13:26', '2018-05-26 17:13:26', 0),
(1, 5, '2018-05-26 17:00:34', '2018-05-26 17:00:34', 0),
(2, 2, '2018-05-26 17:18:23', '2018-05-26 12:13:15', 0),
(2, 3, '2018-05-26 18:04:33', '2018-05-26 18:04:33', 0),
(3, 3, '2018-05-26 18:06:17', '2018-05-26 18:06:17', 0),
(3, 4, '2018-05-26 16:58:50', '2018-05-26 16:58:50', 0),
(6, 4, '2018-05-27 05:55:02', '2018-05-27 05:55:02', 300),
(6, 5, '2018-05-26 17:35:06', '2018-05-26 17:35:06', 100),
(10, 3, '2018-05-27 04:03:20', '2018-05-27 04:03:20', 0),
(11, 3, '2018-05-27 04:28:24', '2018-05-27 04:28:24', 0),
(12, 3, '2018-05-27 05:08:17', '2018-05-27 05:08:17', 7),
(13, 3, '2018-05-27 05:22:58', '2018-05-27 05:22:58', 0),
(14, 3, '2018-05-26 18:30:27', '2018-05-26 18:30:27', 0),
(15, 3, '2018-05-26 18:31:05', '2018-05-26 18:31:05', 0),
(16, 3, '2018-05-26 18:41:59', '2018-05-26 18:41:59', 0);

-- --------------------------------------------------------

--
-- Table structure for table `room_detail`
--

CREATE TABLE `room_detail` (
  `room_id` int(11) NOT NULL,
  `level_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` char(32) NOT NULL,
  `avaiable` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `room_detail`
--

INSERT INTO `room_detail` (`room_id`, `level_id`, `name`, `password`, `avaiable`) VALUES
(0, 1, '', '', 0),
(1, 2, 'ha', 'c4ca4238a0b923820dcc509a6f75849b', 1),
(2, 3, 'hai', 'c4ca4238a0b923820dcc509a6f75849b', 1),
(3, 2, 'hehe', 'c4ca4238a0b923820dcc509a6f75849b', 1),
(4, 6, 'asd', '7815696ecbf1c96e6894b779456d330e', 1),
(6, 5, 'asdasd', 'a8f5f167f44f4964e6c998dee827110c', 1),
(7, 7, 'coba', 'dcb76da384ae3028d6aa9b2ebcea01c9', 1),
(8, 9, 'coba', 'c3ec0f7b054e729c5a716c8125839829', 1),
(9, 6, 'coba2', '17146a464726f22dc5ff649fca94761e', 1),
(10, 7, 't', 'd41d8cd98f00b204e9800998ecf8427e', 1),
(11, 6, 'hai', 'd41d8cd98f00b204e9800998ecf8427e', 1),
(12, 6, 'we', 'd41d8cd98f00b204e9800998ecf8427e', 1),
(13, 1, 'eqee', 'd41d8cd98f00b204e9800998ecf8427e', 1),
(14, 9, 'eq', 'd41d8cd98f00b204e9800998ecf8427e', 1),
(15, 5, 'hehe', 'd41d8cd98f00b204e9800998ecf8427e', 1),
(16, 9, 'dd', 'd41d8cd98f00b204e9800998ecf8427e', 1),
(17, 6, 'agagag', 'd41d8cd98f00b204e9800998ecf8427e', 1);

-- --------------------------------------------------------

--
-- Table structure for table `rule`
--

CREATE TABLE `rule` (
  `rule_id` int(11) NOT NULL,
  `rule_title` varchar(50) DEFAULT NULL,
  `rule_desc` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `type_rating`
--

CREATE TABLE `type_rating` (
  `type_rating_id` int(11) NOT NULL,
  `type_rating_title` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `level_level` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` char(32) DEFAULT NULL,
  `user_level` int(11) DEFAULT NULL,
  `exp` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `level_level`, `username`, `email`, `password`, `user_level`, `exp`) VALUES
(1, 0, NULL, NULL, NULL, NULL, NULL),
(2, 0, 'titut', 'titut@gmail.com', '158c9c96f4768dfe0bbedfc420938df1', 0, 0),
(3, 0, 'yolandahp', 'yolandahertita903@gmail.com', '158c9c96f4768dfe0bbedfc420938df1', 9, 0),
(4, 0, 'ferdinandjason', 'ferdinandjasong@gmail.com', '0cbf6943aaf174232b594c1da9c8dd36', 0, 0),
(5, 0, 'inan', 'inan@inan.com', '078ec3809f43c8a821b964acd78064d8', 10, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `highscore`
--
ALTER TABLE `highscore`
  ADD PRIMARY KEY (`highscore_id`),
  ADD KEY `FK_level_highscore` (`level_id`),
  ADD KEY `FK_user_highscore` (`user_id`);

--
-- Indexes for table `level`
--
ALTER TABLE `level`
  ADD PRIMARY KEY (`level_id`);

--
-- Indexes for table `level_detail`
--
ALTER TABLE `level_detail`
  ADD PRIMARY KEY (`level_level`);

--
-- Indexes for table `level_rule_desc`
--
ALTER TABLE `level_rule_desc`
  ADD PRIMARY KEY (`level_id`,`rule_id`),
  ADD KEY `FK_level_rule_desc2` (`rule_id`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `FK_rating_level` (`level_id`),
  ADD KEY `FK_type_rating_detail` (`type_rating_id`),
  ADD KEY `FK_user_rating` (`user_id`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`room_id`,`user_id`),
  ADD KEY `FK_room_user` (`user_id`);

--
-- Indexes for table `room_detail`
--
ALTER TABLE `room_detail`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `level_id` (`level_id`);

--
-- Indexes for table `rule`
--
ALTER TABLE `rule`
  ADD PRIMARY KEY (`rule_id`);

--
-- Indexes for table `type_rating`
--
ALTER TABLE `type_rating`
  ADD PRIMARY KEY (`type_rating_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `FK_lvl` (`level_level`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `highscore`
--
ALTER TABLE `highscore`
  MODIFY `highscore_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `highscore`
--
ALTER TABLE `highscore`
  ADD CONSTRAINT `FK_level_highscore` FOREIGN KEY (`level_id`) REFERENCES `level` (`level_id`),
  ADD CONSTRAINT `FK_user_highscore` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `level_rule_desc`
--
ALTER TABLE `level_rule_desc`
  ADD CONSTRAINT `FK_level_rule_desc` FOREIGN KEY (`level_id`) REFERENCES `level` (`level_id`),
  ADD CONSTRAINT `FK_level_rule_desc2` FOREIGN KEY (`rule_id`) REFERENCES `rule` (`rule_id`);

--
-- Constraints for table `rating`
--
ALTER TABLE `rating`
  ADD CONSTRAINT `FK_rating_level` FOREIGN KEY (`level_id`) REFERENCES `level` (`level_id`),
  ADD CONSTRAINT `FK_type_rating_detail` FOREIGN KEY (`type_rating_id`) REFERENCES `type_rating` (`type_rating_id`),
  ADD CONSTRAINT `FK_user_rating` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `room_detail`
--
ALTER TABLE `room_detail`
  ADD CONSTRAINT `FK_room_detail_level` FOREIGN KEY (`level_id`) REFERENCES `level` (`level_id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_lvl` FOREIGN KEY (`level_level`) REFERENCES `level_detail` (`level_level`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
