-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2026 at 08:21 AM
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
-- Database: `unihelp`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `account_ID` varchar(30) NOT NULL,
  `account_email` varchar(100) NOT NULL,
  `account_role` varchar(20) NOT NULL,
  `account_pw` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`account_ID`, `account_email`, `account_role`, `account_pw`) VALUES
('STU00001', 'e2500228@helplive.edu.my', 'student', '$2y$10$5fKbeGiBX7EqSJHGDTJr3uicfHMpLrISH9M4K1gA.ItU33C2Xgk3q'),
('STU00002', 'e2500225@helplive.edu.my', 'Volunteer', '$2y$10$XN1KYDvh2B0pqLXf2BqycehCZ797ZCMCfhiOYM16OQoynXzY6aeYa'),
('STU00003', 'e2500228@disted.edu.my', 'student', '1234'),
('STU00004', 'e2500225@disted.edu.my', 'Volunteer', '6767'),
('STU00005', '1234@abc', 'student', '$2y$10$T7kW5kHTj6Totdj4vazYouYleJtKaAA6zQoMTuIjei8g997ZIr/Z6');

-- --------------------------------------------------------

--
-- Table structure for table `availability`
--

CREATE TABLE `availability` (
  `Volunteer_account_ID` varchar(30) NOT NULL,
  `Availability_availability_ID` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `available times`
--

CREATE TABLE `available times` (
  `availability_ID` varchar(30) NOT NULL,
  `availability_day` varchar(10) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_ID` varchar(30) NOT NULL,
  `rating` int(11) NOT NULL,
  `comments` varchar(255) NOT NULL,
  `feedback_creation` datetime DEFAULT NULL,
  `Support_sessions_supportses_id` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `possesses`
--

CREATE TABLE `possesses` (
  `Volunteer_account_ID` varchar(30) NOT NULL,
  `Skills_skill_ID` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `skill_ID` varchar(30) NOT NULL,
  `skill_name` varchar(50) NOT NULL,
  `skill_description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_requests`
--

CREATE TABLE `support_requests` (
  `supportreq_ID` varchar(30) NOT NULL,
  `preferred_datetime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `preferred_supportmode` varchar(20) NOT NULL,
  `Account_account_ID` varchar(30) NOT NULL,
  `Volunteer_volunteer_ID` varchar(30) NOT NULL,
  `supportreq_status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_sessions`
--

CREATE TABLE `support_sessions` (
  `supportses_ID` varchar(30) NOT NULL,
  `session_datetime` datetime NOT NULL,
  `session_supportmode` varchar(20) NOT NULL,
  `session_status` varchar(10) NOT NULL,
  `session_completion` datetime NOT NULL,
  `support_requests_supportreq_id` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `volunteer`
--

CREATE TABLE `volunteer` (
  `account_ID` varchar(30) NOT NULL,
  `volunteer_ID` varchar(30) NOT NULL,
  `volunteer_supportmode` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `volunteer`
--

INSERT INTO `volunteer` (`account_ID`, `volunteer_ID`, `volunteer_supportmode`) VALUES
('STU00002', 'VOL00001', NULL),
('STU00004', 'VOL00002', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`account_ID`);

--
-- Indexes for table `availability`
--
ALTER TABLE `availability`
  ADD PRIMARY KEY (`Volunteer_account_ID`,`Availability_availability_ID`),
  ADD KEY `fk_availability_availability_ID` (`Availability_availability_ID`);

--
-- Indexes for table `available times`
--
ALTER TABLE `available times`
  ADD PRIMARY KEY (`availability_ID`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_ID`),
  ADD KEY `fk_support_sessions_supportses_id` (`Support_sessions_supportses_id`);

--
-- Indexes for table `possesses`
--
ALTER TABLE `possesses`
  ADD PRIMARY KEY (`Volunteer_account_ID`,`Skills_skill_ID`),
  ADD KEY `fk_Skills_skill_ID` (`Skills_skill_ID`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`skill_ID`);

--
-- Indexes for table `support_requests`
--
ALTER TABLE `support_requests`
  ADD PRIMARY KEY (`supportreq_ID`),
  ADD KEY `fk_account_supportreq_ID` (`Account_account_ID`),
  ADD KEY `fk_volunteer_account_supportreq_ID` (`Volunteer_volunteer_ID`);

--
-- Indexes for table `support_sessions`
--
ALTER TABLE `support_sessions`
  ADD PRIMARY KEY (`supportses_ID`),
  ADD KEY `fk_support_requests_supportreq_id` (`support_requests_supportreq_id`);

--
-- Indexes for table `volunteer`
--
ALTER TABLE `volunteer`
  ADD PRIMARY KEY (`account_ID`),
  ADD UNIQUE KEY `volunteer ID` (`volunteer_ID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `availability`
--
ALTER TABLE `availability`
  ADD CONSTRAINT `fk_availability_availability_ID` FOREIGN KEY (`Availability_availability_ID`) REFERENCES `available times` (`availability_ID`),
  ADD CONSTRAINT `fk_availability_volunteer_account_ID` FOREIGN KEY (`Volunteer_account_ID`) REFERENCES `volunteer` (`account_ID`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `fk_support_sessions_supportses_id` FOREIGN KEY (`Support_sessions_supportses_id`) REFERENCES `support_sessions` (`supportses_ID`);

--
-- Constraints for table `possesses`
--
ALTER TABLE `possesses`
  ADD CONSTRAINT `fk_Skills_skill_ID` FOREIGN KEY (`Skills_skill_ID`) REFERENCES `skills` (`skill_ID`),
  ADD CONSTRAINT `fk_volunteer_account_ID` FOREIGN KEY (`Volunteer_account_ID`) REFERENCES `volunteer` (`account_ID`);

--
-- Constraints for table `support_requests`
--
ALTER TABLE `support_requests`
  ADD CONSTRAINT `fk_account_supportreq_ID` FOREIGN KEY (`Account_account_ID`) REFERENCES `account` (`account_ID`),
  ADD CONSTRAINT `fk_volunteer_account_supportreq_ID` FOREIGN KEY (`Volunteer_volunteer_ID`) REFERENCES `volunteer` (`account_ID`);

--
-- Constraints for table `support_sessions`
--
ALTER TABLE `support_sessions`
  ADD CONSTRAINT `fk_support_requests_supportreq_id` FOREIGN KEY (`support_requests_supportreq_id`) REFERENCES `support_requests` (`supportreq_ID`);

--
-- Constraints for table `volunteer`
--
ALTER TABLE `volunteer`
  ADD CONSTRAINT `fk_account_ID` FOREIGN KEY (`account_ID`) REFERENCES `account` (`account_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
