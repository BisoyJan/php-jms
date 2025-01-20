-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 20, 2025 at 05:23 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `contestants`
--

CREATE TABLE `contestants` (
  `contestant_id` int(11) NOT NULL,
  `fullname` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `category` varchar(10) NOT NULL,
  `department_id` int(11) NOT NULL,
  `subevent_id` int(11) NOT NULL,
  `contestant_ctr` int(11) NOT NULL,
  `status` text NOT NULL,
  `txt_code` text NOT NULL,
  `rand_code` int(15) NOT NULL,
  `txtPollScore` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `contestants`
--

INSERT INTO `contestants` (`contestant_id`, `fullname`, `image`, `category`, `department_id`, `subevent_id`, `contestant_ctr`, `status`, `txt_code`, `rand_code`, `txtPollScore`) VALUES
(11, 'James', 'James1.jpg', 'Mr', 4, 7, 1, 'finish', '', 3463079, 0),
(12, 'Mark', 'Mark2.jpg', 'Mr', 5, 7, 2, 'finish', '', 9980157, 0),
(13, 'George', 'George3.jpg', 'Mr', 6, 7, 3, 'finish', '', 5468060, 0),
(15, 'Mae', 'Mae1.jpg', 'Ms', 4, 7, 1, 'finish', '', 7533760, 0),
(16, 'Rae', 'Rae2.jpg', 'Ms', 5, 7, 2, 'finish', '', 7475912, 0);

-- --------------------------------------------------------

--
-- Table structure for table `criteria`
--

CREATE TABLE `criteria` (
  `criteria_id` int(11) NOT NULL,
  `subevent_id` int(11) NOT NULL,
  `criteria` text NOT NULL,
  `percentage` int(11) NOT NULL,
  `criteria_ctr` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `criteria`
--

INSERT INTO `criteria` (`criteria_id`, `subevent_id`, `criteria`, `percentage`, `criteria_ctr`) VALUES
(7, 7, 'Best in production ', 25, 1),
(8, 7, 'Best in gown', 25, 2),
(9, 7, 'Best it swim wear', 25, 3),
(10, 7, 'Q&A', 25, 4);

-- --------------------------------------------------------

--
-- Table structure for table `dapartment`
--

CREATE TABLE `dapartment` (
  `department_id` int(11) NOT NULL,
  `department` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dapartment`
--

INSERT INTO `dapartment` (`department_id`, `department`) VALUES
(4, 'Engineering'),
(5, 'Computer Studies'),
(6, 'Criminology');

-- --------------------------------------------------------

--
-- Table structure for table `judges`
--

CREATE TABLE `judges` (
  `judge_id` int(11) NOT NULL,
  `subevent_id` int(11) NOT NULL,
  `judge_ctr` int(11) NOT NULL,
  `fullname` text NOT NULL,
  `code` varchar(6) NOT NULL,
  `jtype` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `judges`
--

INSERT INTO `judges` (`judge_id`, `subevent_id`, `judge_ctr`, `fullname`, `code`, `jtype`) VALUES
(7, 7, 1, 'Sophie ', 'jj0w5z', ''),
(8, 7, 2, 'Gev', '4u4b7x', ''),
(9, 7, 3, 'Dave', '0oh6hf', '');

-- --------------------------------------------------------

--
-- Table structure for table `main_event`
--

CREATE TABLE `main_event` (
  `mainevent_id` int(11) NOT NULL,
  `event_name` text NOT NULL,
  `status` text NOT NULL,
  `organizer_id` int(11) NOT NULL,
  `sy` varchar(9) NOT NULL,
  `date_start` text NOT NULL,
  `date_end` text NOT NULL,
  `place` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `main_event`
--

INSERT INTO `main_event` (`mainevent_id`, `event_name`, `status`, `organizer_id`, `sy`, `date_start`, `date_end`, `place`) VALUES
(10, 'FOUNDATION DAY', 'activated', 2, '2024-2025', '2025-10-01', '2025-02-01', 'ADFC');

-- --------------------------------------------------------

--
-- Table structure for table `messagein`
--

CREATE TABLE `messagein` (
  `Id` int(11) NOT NULL,
  `SendTime` varchar(10) DEFAULT NULL,
  `ReceiveTime` varchar(10) DEFAULT NULL,
  `MessageFrom` bigint(12) DEFAULT NULL,
  `MessageTo` varchar(10) DEFAULT NULL,
  `SMSC` varchar(10) DEFAULT NULL,
  `MessageText` varchar(4) DEFAULT NULL,
  `MessageType` varchar(10) DEFAULT NULL,
  `MessagePDU` varchar(10) DEFAULT NULL,
  `Gateway` varchar(10) DEFAULT NULL,
  `UserId` varchar(10) DEFAULT NULL,
  `sendStatus` varchar(25) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messagelog`
--

CREATE TABLE `messagelog` (
  `Id` int(11) NOT NULL,
  `SendTime` datetime DEFAULT NULL,
  `ReceiveTime` datetime DEFAULT NULL,
  `StatusCode` int(11) DEFAULT NULL,
  `StatusText` varchar(80) DEFAULT NULL,
  `MessageTo` varchar(80) DEFAULT NULL,
  `MessageFrom` varchar(80) DEFAULT NULL,
  `MessageText` text DEFAULT NULL,
  `MessageType` varchar(80) DEFAULT NULL,
  `MessageId` varchar(80) DEFAULT NULL,
  `ErrorCode` varchar(80) DEFAULT NULL,
  `ErrorText` varchar(80) DEFAULT NULL,
  `Gateway` varchar(80) DEFAULT NULL,
  `MessageParts` int(11) DEFAULT NULL,
  `MessagePDU` text DEFAULT NULL,
  `Connector` varchar(80) DEFAULT NULL,
  `UserId` varchar(80) DEFAULT NULL,
  `UserInfo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messageout`
--

CREATE TABLE `messageout` (
  `Id` int(11) NOT NULL,
  `MessageTo` varchar(80) DEFAULT NULL,
  `MessageFrom` varchar(80) DEFAULT NULL,
  `MessageText` text DEFAULT NULL,
  `MessageType` varchar(80) DEFAULT NULL,
  `Gateway` varchar(80) DEFAULT NULL,
  `UserId` varchar(80) DEFAULT NULL,
  `UserInfo` text DEFAULT NULL,
  `Priority` int(11) DEFAULT NULL,
  `Scheduled` datetime DEFAULT NULL,
  `ValidityPeriod` int(11) DEFAULT NULL,
  `IsSent` tinyint(1) NOT NULL DEFAULT 0,
  `IsRead` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organizer`
--

CREATE TABLE `organizer` (
  `organizer_id` int(11) NOT NULL,
  `fname` text NOT NULL,
  `mname` text NOT NULL,
  `lname` text NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `email` varchar(50) NOT NULL,
  `pnum` varchar(15) NOT NULL,
  `access` varchar(25) NOT NULL,
  `org_id` varchar(12) NOT NULL,
  `status` varchar(12) NOT NULL,
  `company_name` varchar(55) NOT NULL,
  `company_address` varchar(55) NOT NULL,
  `company_logo` varchar(55) NOT NULL,
  `company_telephone` varchar(55) NOT NULL,
  `company_email` varchar(55) NOT NULL,
  `company_website` varchar(55) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `organizer`
--

INSERT INTO `organizer` (`organizer_id`, `fname`, `mname`, `lname`, `username`, `password`, `email`, `pnum`, `access`, `org_id`, `status`, `company_name`, `company_address`, `company_logo`, `company_telephone`, `company_email`, `company_website`) VALUES
(2, 'admin', 'm', 'admin', 'admin', 'admin', 'malate@gmail.com', '09107806750', 'Organizer', '', 'offline', 'Asian Development Foundation College', 'P. Burgos St. Tacloban City', '54013-asian.jpeg', '1234567', 'adfc@gmail.com', 'https//:adfc.com'),
(3, 'Mckervin', 'H', 'Malate', 'admin', 'admin', '', '', 'Tabulator', '2', 'offline', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `rank_system`
--

CREATE TABLE `rank_system` (
  `rs_id` int(11) NOT NULL,
  `subevent_id` varchar(12) NOT NULL,
  `contestant_id` varchar(12) NOT NULL,
  `total_rank` decimal(3,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `rank_system`
--

INSERT INTO `rank_system` (`rs_id`, `subevent_id`, `contestant_id`, `total_rank`) VALUES
(1, '7', '11', 16.0),
(2, '7', '12', 24.0),
(3, '7', '13', 17.0),
(4, '7', '15', 38.0),
(5, '7', '16', 25.0);

-- --------------------------------------------------------

--
-- Table structure for table `sub_event`
--

CREATE TABLE `sub_event` (
  `subevent_id` int(11) NOT NULL,
  `mainevent_id` int(11) NOT NULL,
  `organizer_id` int(11) NOT NULL,
  `event_name` text NOT NULL,
  `status` text NOT NULL,
  `eventdate` text NOT NULL,
  `eventtime` text NOT NULL,
  `place` text NOT NULL,
  `txtpoll_status` text NOT NULL,
  `view` varchar(15) NOT NULL,
  `txtpollview` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sub_event`
--

INSERT INTO `sub_event` (`subevent_id`, `mainevent_id`, `organizer_id`, `event_name`, `status`, `eventdate`, `eventtime`, `place`, `txtpoll_status`, `view`, `txtpollview`) VALUES
(7, 10, 2, 'Mr. & Ms. Sport', 'activated', '2025-01-26', '2;00', 'Robinson North\r\n', '', 'activated', '');

-- --------------------------------------------------------

--
-- Table structure for table `sub_results`
--

CREATE TABLE `sub_results` (
  `subresult_id` int(11) NOT NULL,
  `subevent_id` int(11) NOT NULL,
  `mainevent_id` int(11) NOT NULL,
  `contestant_id` int(11) NOT NULL,
  `judge_id` int(11) NOT NULL,
  `total_score` decimal(11,1) NOT NULL,
  `deduction` int(11) NOT NULL,
  `criteria_ctr1` decimal(11,1) NOT NULL,
  `criteria_ctr2` decimal(11,1) NOT NULL,
  `criteria_ctr3` decimal(11,1) NOT NULL,
  `criteria_ctr4` decimal(11,1) NOT NULL,
  `criteria_ctr5` decimal(11,1) NOT NULL,
  `criteria_ctr6` decimal(11,1) NOT NULL,
  `criteria_ctr7` decimal(11,1) NOT NULL,
  `criteria_ctr8` decimal(11,1) NOT NULL,
  `criteria_ctr9` decimal(11,1) NOT NULL,
  `criteria_ctr10` decimal(11,1) NOT NULL,
  `comments` text NOT NULL,
  `rank` varchar(11) NOT NULL,
  `judge_rank_stat` varchar(15) NOT NULL,
  `place_title` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sub_results`
--

INSERT INTO `sub_results` (`subresult_id`, `subevent_id`, `mainevent_id`, `contestant_id`, `judge_id`, `total_score`, `deduction`, `criteria_ctr1`, `criteria_ctr2`, `criteria_ctr3`, `criteria_ctr4`, `criteria_ctr5`, `criteria_ctr6`, `criteria_ctr7`, `criteria_ctr8`, `criteria_ctr9`, `criteria_ctr10`, `comments`, `rank`, `judge_rank_stat`, `place_title`) VALUES
(1, 7, 10, 11, 7, 95.0, 1, 23.5, 22.5, 24.0, 25.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 'Good job!', '7', '', ''),
(2, 7, 10, 12, 7, 97.0, 0, 23.5, 24.5, 24.5, 24.5, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 'Good!', '3', '', ''),
(3, 7, 10, 13, 7, 96.0, 0, 24.0, 24.0, 24.0, 24.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 'Good', '4', '', ''),
(4, 7, 10, 11, 8, 93.5, 1, 23.5, 23.5, 24.0, 22.5, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 'Great', '8', '', ''),
(5, 7, 10, 12, 8, 92.0, 0, 23.0, 23.0, 23.0, 23.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 'Great ', '9', '', ''),
(6, 7, 10, 13, 8, 97.5, 0, 24.0, 24.5, 24.5, 24.5, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 'Hhh', '2', '', ''),
(7, 7, 10, 11, 9, 98.0, 1, 24.5, 24.5, 24.5, 24.5, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 'Hhh', '1', '', ''),
(8, 7, 10, 12, 9, 79.5, 0, 17.0, 19.5, 23.0, 20.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, '', '12', '', ''),
(9, 7, 10, 13, 9, 83.0, 0, 23.0, 17.5, 20.0, 22.5, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, '', '11', '', ''),
(10, 7, 10, 15, 7, 87.0, 0, 17.0, 22.0, 24.0, 24.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, '', '10', '', ''),
(11, 7, 10, 16, 7, 96.0, 0, 24.0, 24.0, 24.0, 24.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, '', '5', '', ''),
(12, 7, 10, 16, 9, 65.0, 0, 12.0, 18.0, 21.5, 13.5, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, '', '14', '', ''),
(13, 7, 10, 15, 8, 74.0, 0, 23.0, 8.5, 21.0, 21.5, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, '', '13', '', ''),
(14, 7, 10, 15, 9, 61.0, 0, 5.0, 20.5, 18.5, 17.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, '', '15', '', ''),
(15, 7, 10, 16, 8, 96.0, 0, 23.5, 24.0, 24.0, 24.5, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, '', '6', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `textpoll`
--

CREATE TABLE `textpoll` (
  `textpoll_id` int(11) NOT NULL,
  `contestant_id` varchar(12) NOT NULL,
  `text_vote` int(11) NOT NULL,
  `subevent_id` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contestants`
--
ALTER TABLE `contestants`
  ADD PRIMARY KEY (`contestant_id`);

--
-- Indexes for table `criteria`
--
ALTER TABLE `criteria`
  ADD PRIMARY KEY (`criteria_id`);

--
-- Indexes for table `dapartment`
--
ALTER TABLE `dapartment`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `judges`
--
ALTER TABLE `judges`
  ADD PRIMARY KEY (`judge_id`);

--
-- Indexes for table `main_event`
--
ALTER TABLE `main_event`
  ADD PRIMARY KEY (`mainevent_id`);

--
-- Indexes for table `messagein`
--
ALTER TABLE `messagein`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `messagelog`
--
ALTER TABLE `messagelog`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IDX_MessageId` (`MessageId`,`SendTime`);

--
-- Indexes for table `messageout`
--
ALTER TABLE `messageout`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IDX_IsRead` (`IsRead`);

--
-- Indexes for table `organizer`
--
ALTER TABLE `organizer`
  ADD PRIMARY KEY (`organizer_id`);

--
-- Indexes for table `rank_system`
--
ALTER TABLE `rank_system`
  ADD PRIMARY KEY (`rs_id`);

--
-- Indexes for table `sub_event`
--
ALTER TABLE `sub_event`
  ADD PRIMARY KEY (`subevent_id`);

--
-- Indexes for table `sub_results`
--
ALTER TABLE `sub_results`
  ADD PRIMARY KEY (`subresult_id`);

--
-- Indexes for table `textpoll`
--
ALTER TABLE `textpoll`
  ADD PRIMARY KEY (`textpoll_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contestants`
--
ALTER TABLE `contestants`
  MODIFY `contestant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `criteria`
--
ALTER TABLE `criteria`
  MODIFY `criteria_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `dapartment`
--
ALTER TABLE `dapartment`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `judges`
--
ALTER TABLE `judges`
  MODIFY `judge_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `main_event`
--
ALTER TABLE `main_event`
  MODIFY `mainevent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `messagein`
--
ALTER TABLE `messagein`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messagelog`
--
ALTER TABLE `messagelog`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messageout`
--
ALTER TABLE `messageout`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organizer`
--
ALTER TABLE `organizer`
  MODIFY `organizer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rank_system`
--
ALTER TABLE `rank_system`
  MODIFY `rs_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sub_event`
--
ALTER TABLE `sub_event`
  MODIFY `subevent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sub_results`
--
ALTER TABLE `sub_results`
  MODIFY `subresult_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `textpoll`
--
ALTER TABLE `textpoll`
  MODIFY `textpoll_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
