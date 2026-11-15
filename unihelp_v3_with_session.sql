-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 26, 2026 at 10:54 AM
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
('STU00002', 'e2500225@helplive.edu.my', 'volunteer', '$2y$10$XN1KYDvh2B0pqLXf2BqycehCZ797ZCMCfhiOYM16OQoynXzY6aeYa'),
('STU00003', 'e2500228@disted.edu.my', 'student', '1234'),
('STU00004', 'e2500225@disted.edu.my', 'volunteer', '6767'),
('STU00006', 'bda25004@dmail.disted.edu.my', 'volunteer', '$2y$10$UCuQbPjowLzRKgXK7A0u9.zveApCh7p/gt1o5CjHytnxmYhrBgB4y'),
('STU00007', 'wshern23@gmail.com', 'student', '$2y$10$A72kwSnWusrf2/4YRVrxBuiCuV.OE3H3zbqXQ6iqaihFlq6ojGypW');

-- --------------------------------------------------------

--
-- Table structure for table `availability`
--

CREATE TABLE `availability` (
  `Volunteer_account_ID` varchar(30) NOT NULL,
  `Availability_availability_ID` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `availability`
--

INSERT INTO `availability` (`Volunteer_account_ID`, `Availability_availability_ID`) VALUES
('STU00006', 'FRI_1300'),
('STU00006', 'MON_1300');

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

--
-- Dumping data for table `available times`
--

INSERT INTO `available times` (`availability_ID`, `availability_day`, `start_time`, `end_time`) VALUES
('FRI_1300', 'Friday', '13:00:00', '14:00:00'),
('FRI_1500', 'Friday', '15:00:00', '16:00:00'),
('MON_1100', 'Monday', '11:00:00', '12:00:00'),
('MON_1300', 'Monday', '13:00:00', '14:00:00'),
('THU_1500', 'Thursday', '15:00:00', '16:00:00'),
('TUE_1100', 'Tuesday', '11:00:00', '12:00:00');

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

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_ID`, `rating`, `comments`, `feedback_creation`, `Support_sessions_supportses_id`) VALUES
('FB00001', 5, 'Overall it was a good session', '2026-10-25 23:07:44', 'SES00002'),
('FB00002', 2, '', '2026-10-26 17:40:53', 'SES00003');

-- --------------------------------------------------------

--
-- Table structure for table `possesses`
--

CREATE TABLE `possesses` (
  `Volunteer_account_ID` varchar(30) NOT NULL,
  `Skills_skill_ID` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `possesses`
--

INSERT INTO `possesses` (`Volunteer_account_ID`, `Skills_skill_ID`) VALUES
('STU00006', 'SK01');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `skill_ID` varchar(30) NOT NULL,
  `skill_name` varchar(50) NOT NULL,
  `skill_description` varchar(100) NOT NULL,
  `skill_category` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`skill_ID`, `skill_name`, `skill_description`, `skill_category`) VALUES
('SK01', 'IT Support', 'Information Technology support, such as fixing computer issues ', 'Technology Assistance'),
('SK02', 'Assignment Consultation', 'Provide personalized consultation on assignment', 'Academic Support'),
('SK03', 'Mathematics', 'Solve mathematics question', 'Academic Support');

-- --------------------------------------------------------

--
-- Table structure for table `support_requests`
--

CREATE TABLE `support_requests` (
  `supportreq_ID` varchar(30) NOT NULL,
  `preferred_datetime` datetime NOT NULL,
  `preferred_supportmode` varchar(20) NOT NULL,
  `Account_account_ID` varchar(30) NOT NULL,
  `Volunteer_volunteer_ID` varchar(30) NOT NULL,
  `supportreq_status` varchar(10) NOT NULL,
  `request_desc` varchar(1000) DEFAULT NULL,
  `required_skill_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `support_requests`
--

INSERT INTO `support_requests` (`supportreq_ID`, `preferred_datetime`, `preferred_supportmode`, `Account_account_ID`, `Volunteer_volunteer_ID`, `supportreq_status`, `request_desc`, `required_skill_id`) VALUES
('REQ000001', '2026-10-24 11:00:00', 'Online', 'STU00007', 'STU00006', 'Cancelled', 'My laptop occured blue screen', 'SK01'),
('REQ000002', '2026-10-31 08:00:00', 'Physical', 'STU00007', 'STU00006', 'Completed', 'My laptop really lag!!!', 'SK01'),
('REQ000003', '2026-11-15 11:00:00', 'Physical', 'STU00007', 'STU00006', 'Completed', 'I can\'t solve math question', 'SK03');

-- --------------------------------------------------------

--
-- Table structure for table `support_sessions`
--

CREATE TABLE `support_sessions` (
  `supportses_ID` varchar(30) NOT NULL,
  `session_datetime` datetime NOT NULL,
  `session_supportmode` varchar(20) NOT NULL,
  `session_status` varchar(10) NOT NULL,
  `session_completion` datetime DEFAULT NULL,
  `support_requests_supportreq_id` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `support_sessions`
--

INSERT INTO `support_sessions` (`supportses_ID`, `session_datetime`, `session_supportmode`, `session_status`, `session_completion`, `support_requests_supportreq_id`) VALUES
('SES00001', '2026-10-26 13:00:00', 'Online', 'Cancelled', NULL, 'REQ000001'),
('SES00002', '2026-10-26 13:00:00', 'Physical', 'Completed', '2026-10-25 14:58:21', 'REQ000002'),
('SES00003', '2026-11-16 13:00:00', 'Physical', 'Completed', '2026-10-26 10:36:02', 'REQ000003');

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
('STU00004', 'VOL00002', NULL),
('STU00006', 'VOL00003', 'Online');

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
  ADD KEY `fk_volunteer_account_supportreq_ID` (`Volunteer_volunteer_ID`),
  ADD KEY `fk_request_skill` (`required_skill_id`);

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
  ADD CONSTRAINT `fk_request_skill` FOREIGN KEY (`required_skill_id`) REFERENCES `skills` (`skill_ID`),
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
