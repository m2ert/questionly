-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 22, 2021 at 11:06 PM
-- Server version: 10.1.46-MariaDB
-- PHP Version: 7.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `question_project`
--
CREATE DATABASE IF NOT EXISTS `question_project` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `question_project`;

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `answer_id` int(11) UNSIGNED NOT NULL,
  `question_id` int(11) DEFAULT NULL,
  `answerer_id` int(11) DEFAULT NULL,
  `answer_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `answer_category` int(11) DEFAULT NULL,
  `answer` text CHARACTER SET utf8mb4
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- --------------------------------------------------------

--
-- Table structure for table `answer_comment`
--

CREATE TABLE `answer_comment` (
  `comment_id` int(11) UNSIGNED NOT NULL,
  `answer_id` int(11) DEFAULT NULL,
  `commentor_id` int(11) DEFAULT NULL,
  `comment` text,
  `comment_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- --------------------------------------------------------

--
-- Table structure for table `answer_vote`
--

CREATE TABLE `answer_vote` (
  `vote_id` int(11) UNSIGNED NOT NULL,
  `answer_id` int(11) DEFAULT NULL,
  `voter_id` int(11) DEFAULT NULL,
  `vote_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_id` int(11) UNSIGNED NOT NULL,
  `question` text,
  `questioner_id` int(11) DEFAULT NULL,
  `question_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `question_category` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `question_register`
--

CREATE TABLE `question_register` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `user_name` varchar(15) DEFAULT NULL,
  `user_email` varchar(50) DEFAULT NULL,
  `user_password` varchar(32) DEFAULT NULL,
  `registry_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `account_status` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `spaces`
--

CREATE TABLE `spaces` (
  `space_id` int(11) UNSIGNED NOT NULL,
  `space_name` varchar(50) DEFAULT NULL,
  `space_logo` varchar(50) DEFAULT 'mini-space.png',
  `space_status` tinyint(1) DEFAULT '1',
  `create_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `spaces_followers`
--

CREATE TABLE `spaces_followers` (
  `id` int(11) UNSIGNED NOT NULL,
  `space_id` int(11) UNSIGNED NOT NULL,
  `follower_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE `user_profile` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `user_desc` text,
  `user_img` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`answer_id`);

--
-- Indexes for table `answer_comment`
--
ALTER TABLE `answer_comment`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `answer_vote`
--
ALTER TABLE `answer_vote`
  ADD PRIMARY KEY (`vote_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`);

--
-- Indexes for table `question_register`
--
ALTER TABLE `question_register`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `spaces`
--
ALTER TABLE `spaces`
  ADD PRIMARY KEY (`space_id`);

--
-- Indexes for table `spaces_followers`
--
ALTER TABLE `spaces_followers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `answer_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `answer_comment`
--
ALTER TABLE `answer_comment`
  MODIFY `comment_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `answer_vote`
--
ALTER TABLE `answer_vote`
  MODIFY `vote_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `question_register`
--
ALTER TABLE `question_register`
  MODIFY `user_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `spaces`
--
ALTER TABLE `spaces`
  MODIFY `space_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `spaces_followers`
--
ALTER TABLE `spaces_followers`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
