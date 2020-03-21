-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2019 at 11:42 AM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gp`
--

-- --------------------------------------------------------

--
-- Table structure for table `admindet`
--

CREATE TABLE `admindet` (
  `lname` varchar(30) NOT NULL,
  `fname` varchar(30) NOT NULL,
  `fathername` varchar(30) NOT NULL,
  `mothername` varchar(30) NOT NULL,
  `id` varchar(12) NOT NULL,
  `email` varchar(50) NOT NULL,
  `OTP` text NOT NULL,
  `expiry_time` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admindet`
--

INSERT INTO `admindet` (`lname`, `fname`, `fathername`, `mothername`, `id`, `email`, `OTP`, `expiry_time`) VALUES
('ASHAR', 'BRINDA', 'SUNIL', 'SONAL', '7234', 'brinda.ashar@somaiya.edu', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `cancelled`
--

CREATE TABLE `cancelled` (
  `gid` varchar(12) NOT NULL,
  `uemail` varchar(500) NOT NULL,
  `urole` varchar(10) NOT NULL,
  `gsub` varchar(5000) NOT NULL,
  `gcat` varchar(5000) NOT NULL,
  `gtype` varchar(5000) NOT NULL,
  `gdes` mediumtext NOT NULL,
  `gfile` varchar(4000) NOT NULL,
  `timeofg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(5000) NOT NULL DEFAULT 'Pending',
  `raised` tinyint(1) NOT NULL,
  `act` text NOT NULL,
  `uptime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cancelled`
--

INSERT INTO `cancelled` (`gid`, `uemail`, `urole`, `gsub`, `gcat`, `gtype`, `gdes`, `gfile`, `timeofg`, `status`, `raised`, `act`, `uptime`) VALUES
('030320190000', 'brinda.ashar@somaiya.edu', 'Student', 'asdasd', 'Complaint', 'Vigilance', 'gvbn', 'proofDocs/030320190000.pdf', '2019-03-02 19:23:01', 'Cancelled', 0, '', '2019-05-30 10:49:13'),
('090620190000', 'brinda.ashar@somaiya.edu', 'Student', 'test1', 'Other - Student', 'test desc', '', '2019-06-09 16:56:22', '0000-00-00 00:00:00', 'Cancelled', 0, '', '2019-09-01 19:21:32'),
('090620190001', 'brinda.ashar@somaiya.edu', 'Student', 'test1', 'Other - Student', 'test desc', '', '2019-06-09 16:58:29', '0000-00-00 00:00:00', 'Cancelled', 0, '', '2019-09-01 19:23:24'),
('270720190000', 'brinda.ashar@somaiya.edu', 'Student', 'Ragging', 'Ragging', 'Ragging', 'proofDocs/270720190000.mkv', '2019-07-27 00:49:00', '0000-00-00 00:00:00', 'Cancelled', 0, '', '2019-07-26 19:27:17'),
('270720190001', 'brinda.ashar@somaiya.edu', 'Student', 'Ragging', 'Ragging', 'Ragging', 'proofDocs/270720190001.jpg', '2019-07-27 01:57:42', '0000-00-00 00:00:00', 'Cancelled', 0, '', '2019-07-26 20:27:57'),
('270720190002', 'brinda.ashar@somaiya.edu', 'Student', 'Ragging', 'Other(Student)', 'Ragging', '', '2019-07-27 01:59:51', '0000-00-00 00:00:00', 'Cancelled', 0, '', '2019-07-26 20:30:23');

-- --------------------------------------------------------

--
-- Table structure for table `catdet`
--

CREATE TABLE `catdet` (
  `category` varchar(50) NOT NULL,
  `comm_name` varchar(75) NOT NULL,
  `student` tinyint(1) NOT NULL,
  `employee` tinyint(1) NOT NULL,
  `thresh_rem` int(5) NOT NULL,
  `thresh_raise` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `catdet`
--

INSERT INTO `catdet` (`category`, `comm_name`, `student`, `employee`, `thresh_rem`, `thresh_raise`) VALUES
('Caste Discrimination', 'Special Cell', 1, 1, 5, 7),
('Fee Related', 'Students Grievance Redressal Committee', 1, 0, 5, 7),
('Other(Employee)', 'Grievance Redressal Cell', 0, 1, 5, 7),
('Other(Student)', 'Students Grievance Redressal Committee', 1, 0, 5, 7),
('Ragging', 'Anti-Ragging Committee', 1, 1, 5, 7),
('Re-appeal against committee', 'Principal', 1, 1, 5, 7),
('Salary Related', 'Grievance Redressal Cell', 0, 1, 5, 7),
('Scholarship Related', 'Students Grievance Redressal Committee', 1, 0, 5, 7),
('Sexual Harassment', 'Internal Complaints Committee', 1, 1, 5, 7);

-- --------------------------------------------------------

--
-- Table structure for table `commdet`
--

CREATE TABLE `commdet` (
  `name` varchar(1000) NOT NULL,
  `email` varchar(500) NOT NULL,
  `OTP` text NOT NULL,
  `expiry_time` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `commdet`
--

INSERT INTO `commdet` (`name`, `email`, `OTP`, `expiry_time`) VALUES
('Anti-Ragging Committee', 'committee.antir@somaiya.edu', '', ''),
('Grievance Redressal Cell', 'committee.gcell@somaiya.edu', '', ''),
('Students Grievance Redressal Committee', 'committee.gcomm@somaiya.edu', '', ''),
('Internal Complaints Committee', 'committee.sh@somaiya.edu', '', ''),
('Special Cell', 'committee.spcl@somaiya.edu', '', ''),
('Principal', 'principal.engg@somaiya.edu', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `facdet`
--

CREATE TABLE `facdet` (
  `lname` varchar(30) NOT NULL,
  `fname` varchar(30) NOT NULL,
  `fathername` varchar(30) DEFAULT NULL,
  `mothername` varchar(30) DEFAULT NULL,
  `id` varchar(12) NOT NULL,
  `email` varchar(50) NOT NULL,
  `dept` varchar(20) NOT NULL,
  `designation` varchar(10000) NOT NULL,
  `joindate` date NOT NULL,
  `suspend` tinyint(1) NOT NULL,
  `OTP` text NOT NULL,
  `expiry_time` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `grievance`
--

CREATE TABLE `grievance` (
  `gid` varchar(12) NOT NULL,
  `uemail` varchar(500) NOT NULL,
  `urole` varchar(10) NOT NULL,
  `gsub` varchar(5000) NOT NULL,
  `gcat` varchar(5000) NOT NULL,
  `gdes` mediumtext NOT NULL,
  `gfile` varchar(4000) NOT NULL,
  `timeofg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(5000) NOT NULL DEFAULT 'Pending',
  `fordet` text NOT NULL,
  `raised` tinyint(1) NOT NULL,
  `act` text NOT NULL,
  `uptime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `grievance`
--

INSERT INTO `grievance` (`gid`, `uemail`, `urole`, `gsub`, `gcat`, `gdes`, `gfile`, `timeofg`, `status`, `fordet`, `raised`, `act`, `uptime`) VALUES
('030320190001', 'brinda.ashar@somaiya.edu', 'Student', 'asdasd', 'Complaint', 'gvbn', 'proofDocs/030320190001.pdf', '2019-03-02 19:30:37', 'Partially Solved', 'test', 1, '<p><b>Human Resources: </b>test</p>', '2019-05-30 10:49:03'),
('030320190002', 'brinda.ashar@somaiya.edu', 'Student', 'asdasd', 'Complaint', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'proofDocs/030320190002.pdf', '2019-03-02 19:47:22', 'Partially Solved', 'abcd', 1, '', '2019-05-30 10:35:34'),
('090620190002', 'brinda.ashar@somaiya.edu', 'Student', 'test1', 'Other - Student', 'test desc', '', '2019-06-09 11:30:36', 'Pending', '', 0, '', '0000-00-00 00:00:00'),
('090620190003', 'brinda.ashar@somaiya.edu', 'Student', 'test1', 'Other - Student', 'test desc', '', '2019-06-09 11:30:51', 'Pending', '', 0, '', '0000-00-00 00:00:00'),
('100220190000', 'brinda.ashar@somaiya.edu', 'Student', 'blah', 'Complaint', 'fdas', '', '2019-02-10 09:54:17', 'Raised to Principal', '', 0, '15', '2019-05-29 17:09:34'),
('110220190000', 'brinda.ashar@somaiya.edu', 'Student', 'Symphony', 'Against_Authority', 'Shutting down of symphony', '', '2019-02-10 18:51:45', 'In progress', '', 0, '', '2019-05-29 17:09:34'),
('110220190001', 'brinda.ashar@somaiya.edu', 'Student', 'kfh', 'Against_Authority', 'fvf', '', '2019-02-10 19:04:14', 'In progress', '', 0, '', '2019-05-29 17:09:34'),
('110220190002', 'brinda.ashar@somaiya.edu', 'Student', 'Fees', 'Complaint', 'Fees', '', '2019-02-11 13:46:57', 'Action Taken', '', 0, '', '2019-05-29 17:09:34'),
('110620190000', 'brinda.ashar@somaiya.edu', 'Student', 'test', 'Ragging', 'test', '', '2019-07-06 14:08:43', 'Pending', '', 0, '', '2019-07-11 08:28:52'),
('110620190001', 'brinda.ashar@somaiya.edu', 'Student', 'asdasd', 'Other(Employee)', 'tadadadadada', '', '2019-06-11 15:07:01', 'Pending', '', 0, '', '0000-00-00 00:00:00'),
('120720190000', 'rushabh.bid@somaiya.edu', 'Student', 'Cooler not working', 'Other(Student)', 'Cooler not working on 2nd floor of B building', '', '2019-07-11 18:32:05', 'Raised to Principal', '', 1, '', '2019-07-19 17:47:55'),
('160220190001', 'brinda.ashar@somaiya.edu', 'Student', 'asdasd', 'Scholarship', 'sadasd', '', '2019-06-26 16:18:43', 'In progress', '', 0, '', '2019-07-01 10:45:13'),
('160220190002', 'brinda.ashar@somaiya.edu', 'Student', 'asdasd', 'Scholarship', 'asdasd', '', '2019-02-16 16:18:53', 'Raised to Principal', '', 0, '', '2019-05-29 17:09:34'),
('160220190004', 'brinda.ashar@somaiya.edu', 'Student', 'avwewta', 'Complaint', 'ewavttvawe', '', '2019-02-16 16:19:23', 'In progress', '', 0, '', '2019-05-29 17:09:34'),
('160220190005', 'brinda.ashar@somaiya.edu', 'Student', 'aevtvadf', 'Complaint', 'aevwfvadsv', '', '2019-02-16 16:19:32', 'Action Taken', '', 0, '', '2019-05-29 17:09:34'),
('160220190007', 'brinda.ashar@somaiya.edu', 'Student', 'sdafavae', 'Complaint', 'asdfvwa', '', '2019-02-16 16:19:56', 'Raised to Principal', '', 0, '', '2019-05-29 17:09:34'),
('160220190010', 'brinda.ashar@somaiya.edu', 'Student', 'asvfaweb', 'Harassment', 'avrvddas', '', '2019-02-16 16:20:43', 'In progress', '', 0, '', '2019-05-29 17:09:34'),
('160220190011', 'brinda.ashar@somaiya.edu', 'Student', 'vaefv', 'Harassment', 'avdsfv', '', '2019-02-16 16:20:58', 'Raised to Principal', '', 0, '', '2019-05-29 17:09:34'),
('160220190013', 'brinda.ashar@somaiya.edu', 'Student', 'adfvfvawfev', 'Against_Authority', 'avewvwf', '', '2019-02-16 16:21:23', 'In progress', '', 0, '', '2019-05-29 17:09:34'),
('160220190014', 'brinda.ashar@somaiya.edu', 'Student', 'dsfvae', 'Against_Authority', 'sdavfaev', '', '2019-02-16 16:21:37', 'Pending', '', 0, '', '2019-05-29 17:09:34'),
('250220190000', 'brinda.ashar@somaiya.edu', 'Student', 'abcd', 'Complaint', 'As usual, Lionel Messi provided it. The Argentine attacker was hauled down by Victor Sanchez on his way into the area and there were calls for', '', '2019-02-25 10:14:32', 'Partially Solved', 'Lionel Andrés Messi Cuccittini is an Argentine professional footballer who plays as a forward and captains both Spanish club Barcelona and the Argentina national team.', 0, 'HR: boomabada<p><b>Antiragging committee: </b>test action</p>', '2019-05-29 17:09:34'),
('2701201900', 'brinda.ashar@somaiya.edu', 'Student', 'Fees', 'Complaint', 'Fees', '', '2019-01-27 14:37:58', 'Action Taken', '', 0, 'looked into', '2019-05-29 17:09:34'),
('270720190000', 'brinda.ashar@somaiya.edu', 'Student', 'Ragging', 'Ragging', 'Ragging', 'proofDocs/270720190000.jpg', '2019-07-26 19:37:31', 'Pending', '', 0, '', '0000-00-00 00:00:00'),
('270720190001', 'brinda.ashar@somaiya.edu', 'Student', 'Ragging', 'Other(Student)', 'Ragging', '', '2019-07-26 20:29:11', 'Pending', '', 0, '', '0000-00-00 00:00:00'),
('270720190002', 'brinda.ashar@somaiya.edu', 'Student', 'Something', 'Other(Student)', 'Dont know', '', '2019-07-26 20:30:48', 'Pending', '', 0, '', '0000-00-00 00:00:00'),
('270720190003', 'brinda.ashar@somaiya.edu', 'Student', 'Blah2', 'Fee Related', 'hehe', '', '2019-07-26 20:31:51', 'Pending', '', 0, '', '0000-00-00 00:00:00'),
('30320190000', 'brinda.ashar@somaiya.edu', 'Student', 'asdasd', 'Complaint', 'gvbn', 'proofDocs/030320190000.pdf', '2019-03-02 19:15:29', 'Pending', '', 0, '', '2019-05-30 10:48:59');

-- --------------------------------------------------------

--
-- Table structure for table `logins`
--

CREATE TABLE `logins` (
  `email` text NOT NULL,
  `status` text NOT NULL,
  `attempt_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `logins`
--

INSERT INTO `logins` (`email`, `status`, `attempt_time`) VALUES
('committee.antir@somaiya.edu', 'Successful Authority Login', '2019-03-17 11:39:40'),
('committee.hr@somaiya.edu', 'Successful Authority Login', '2019-03-17 11:43:10'),
('committee.antir@somaiya.edu', 'Successful Authority Login', '2019-03-17 16:52:45'),
('rushabh.bid@somaiya.edu', 'Successful Login', '2019-03-24 15:54:23'),
('principal.kjsce@somaiya.edu', 'Successful Authority Login', '2019-03-24 18:10:19'),
('committee.antir@somaiya.edu', 'Successful Authority Login', '2019-03-24 19:16:37'),
('committee.antir@somaiya.edu', 'Successful Authority Login', '2019-03-25 15:33:11'),
('committee.antir@somaiya.edu', 'Successful Authority Login', '2019-03-26 18:21:58'),
('committee.antir@somaiya.edu', 'Successful Authority Login', '2019-03-28 17:23:22'),
('committee.antir@somaiya.edu', 'Successful Authority Login', '2019-03-31 12:58:57'),
('', 'Failed intrusion from a Non-Somaiya ID', '2019-04-01 16:20:24'),
('rushabh.bid@somaiya.edu', 'Successful Login', '2019-04-01 16:21:02'),
('brinda.ashar@somaiya.edu', 'Successful Login', '2019-04-03 10:14:45'),
('committee.antir@somaiya.edu', 'Successful Authority Login', '2019-04-03 10:17:35'),
('committee.hr@somaiya.edu', 'Successful Authority Login', '2019-04-03 10:37:57'),
('brinda.ashar@somaiya.edu', 'Successful Login', '2019-04-03 19:37:59'),
('committee.antir@somaiya.edu', 'Successful Authority Login', '2019-04-03 19:44:41'),
('brinda.ashar@somaiya.edu', 'Successful Login', '2019-05-25 06:02:52'),
('brinda.ashar@somaiya.edu', 'Successful Login', '2019-05-25 06:30:29'),
('brinda.ashar@somaiya.edu', 'Successful Login', '2019-05-25 06:31:13'),
('brinda.ashar@somaiya.edu', 'Successful Login', '2019-05-25 06:48:02'),
('babaso.aldar@somaiya.edu', 'Successful Login', '2019-05-25 06:50:31'),
('', 'Failed attempt from an invalid Somaiya ID', '2019-05-25 06:51:31'),
('', 'Failed attempt from an invalid Somaiya ID', '2019-05-25 06:55:56'),
('brinda.ashar@somaiya.edu', 'Failed attempt from an invalid Somaiya ID', '2019-05-25 06:57:05'),
('', 'Failed attempt from an invalid Somaiya ID', '2019-05-25 06:58:49'),
('', 'Failed attempt from an invalid Somaiya ID', '2019-05-25 07:00:48'),
('brinda.ashar@somaiya.edu', 'Failed attempt from an invalid Somaiya ID', '2019-05-25 07:01:45'),
('babaso.aldar@somaiya.edu', 'Successful Login', '2019-05-27 09:13:24'),
('committee.antir@somaiya.edu', 'Successful Authority Login', '2019-05-29 17:03:07'),
('committee.antir@somaiya.edu', 'Successful Authority Login', '2019-05-30 07:05:13'),
('brinda.ashar@somaiya.edu', 'Successful Login', '2019-06-09 11:15:10'),
('brinda.ashar@somaiya.edu', 'Successful Login', '2019-06-11 14:06:19'),
('brinda.ashar@somaiya.edu', 'Successful Login', '2019-06-11 14:51:20'),
('committee.antir@somaiya.edu', 'Successful Authority Login', '2019-06-11 15:12:54'),
('committee.antir@somaiya.edu', 'Failed attempt from an invalid Somaiya ID', '2019-06-25 09:41:36'),
('committee.antir@somaiya.edu', 'Failed attempt from an invalid Somaiya ID', '2019-06-25 09:43:54'),
('committee.antir@somaiya.edu', 'Successful Authority Login', '2019-06-25 09:44:49'),
('committee.antir@somaiya.edu', 'Successful Authority Login', '2019-07-01 10:18:37'),
('committee.antir@somaiya.edu', 'Failed attempt from an invalid Somaiya ID', '2019-07-11 17:48:38'),
('committee.antir@somaiya.edu', 'Failed attempt from an invalid Somaiya ID', '2019-07-11 17:49:09'),
('committee.antir@somaiya.edu', 'Successful Authority Login', '2019-07-11 17:51:01'),
('committee.antir@somaiya.edu', 'Successful Authority Login', '2019-07-11 18:27:29'),
('rushabh.bid@somaiya.edu', 'Successful Login', '2019-07-11 18:31:11'),
('brinda.ashar@somaiya.edu', 'Successful Login', '2019-07-11 18:33:48'),
('principal.kjsce@somaiya.edu', 'Successful Authority Login', '2019-07-11 18:35:54'),
('brinda.ashar@somaiya.edu', 'Successful Login', '2019-07-11 18:39:26'),
('brinda.ashar@somaiya.edu', 'Successful Login', '2019-07-11 18:48:19'),
('brinda.ashar@somaiya.edu', 'Successful Login', '2019-07-12 06:06:30'),
('brinda.ashar@somaiya.edu', 'Successful Login', '2019-07-12 06:08:38'),
('committee.antir@somaiya.edu', 'Successful Authority Login', '2019-07-12 06:09:36'),
('committee.antir@somaiya.edu', 'Successful Authority Login', '2019-07-12 06:11:07'),
('brinda.ashar@somaiya.edu', 'Successful Login', '2019-07-12 06:17:43'),
('brinda.ashar@somaiya.edu', 'Successful Login', '2019-07-12 06:22:28'),
('committee.antir@somaiya.edu', 'Successful Authority Login', '2019-07-12 07:21:44'),
('committee.antir@somaiya.edu', 'Successful Authority Login', '2019-07-12 07:25:37'),
('committee.antir@somaiya.edu', 'Successful Authority Login', '2019-07-12 09:38:00'),
('committee.antir@somaiya.edu', 'Successful Authority Login', '2019-07-12 09:41:28'),
('committee.antir@somaiya.edu', 'Successful Authority Login', '2019-07-12 09:44:26'),
('committee.antir@somaiya.edu', 'Successful Authority Login', '2019-07-12 09:55:26'),
('committee.antir@somaiya.edu', 'Successful Authority Login', '2019-07-12 13:33:32'),
('brinda.ashar@somaiya.edu', 'Successful Login', '2019-07-26 16:36:00'),
('brinda.ashar@somaiya.edu', 'Successful Login', '2019-09-01 19:21:19');

-- --------------------------------------------------------

--
-- Table structure for table `resolved`
--

CREATE TABLE `resolved` (
  `gid` varchar(12) NOT NULL,
  `uemail` varchar(500) NOT NULL,
  `urole` varchar(10) NOT NULL,
  `gsub` varchar(5000) NOT NULL,
  `gcat` varchar(5000) NOT NULL,
  `gtype` varchar(5000) NOT NULL,
  `gdes` varchar(5000) NOT NULL,
  `gfile` varchar(500) NOT NULL,
  `timeofg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(5000) NOT NULL,
  `raised` tinyint(1) NOT NULL,
  `act` text NOT NULL,
  `uptime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `resolved`
--

INSERT INTO `resolved` (`gid`, `uemail`, `urole`, `gsub`, `gcat`, `gtype`, `gdes`, `gfile`, `timeofg`, `status`, `raised`, `act`, `uptime`) VALUES
('030420190000', 'brinda.ashar@somaiya.edu', 'Student', 'test 1', 'Complaint', 'Certificate', 'test1', 'proofDocs/030420190000.cpp', '2019-04-03 10:15:29', 'Action Taken', 1, '<p><b>Antiragging committee: </b>test 1 partially solved</p><p><b>Human Resources: </b>reverted by Human Resources</p><p><b>Antiragging committee: </b>complete</p>', '2019-05-29 17:12:30'),
('100220190000', 'brinda.ashar@somaiya.edu', 'Student', 'blah', 'Complaint', 'gtype', 'fdas', '', '2019-02-25 20:14:42', 'Action Taken', 0, 'looked into it.', '2019-05-29 17:12:30'),
('100220190001', 'brinda.ashar@somaiya.edu', 'Student', 'Fees', 'Complaint', 'Vigilance', 'Fees', '', '2019-02-10 14:44:02', 'Action Taken', 0, 'rush\r\n', '2019-05-29 17:12:30'),
('160220190000', 'brinda.ashar@somaiya.edu', 'Student', 'asd', 'Scholarship', 'Institute Related Issue', 'asadas', '', '2019-02-25 20:14:42', 'Action Taken', 0, 'dummy', '2019-05-29 17:12:30'),
('160220190006', 'brinda.ashar@somaiya.edu', 'Student', 'aewrvadfv', 'Complaint', 'Fees', 'sdfava3f', '', '2019-02-25 20:14:42', 'Resolved', 0, 'asff', '2019-05-29 17:12:30'),
('160220190009', 'brinda.ashar@somaiya.edu', 'Student', 'dsavfwfv', 'Harassment', 'C', 'atvaweta', '', '2019-02-25 20:14:42', 'Resolved', 0, 'ashf', '2019-05-29 17:12:30'),
('20011999', 'brinda.ashar@somaiya.edu', 'Student', 'id', 'Complaints/Vigilants', 'Others', 'dfb', '', '2019-02-25 20:14:42', 'Resolved', 0, 'looked into the matter.', '2019-05-29 17:12:30'),
('2701201900', 'brinda.ashar@somaiya.edu', 'Student', 'Fees', 'Complaint', 'gtype', 'Fees', '', '2019-02-25 20:14:42', 'Action Taken', 0, '', '2019-05-29 17:12:30'),
('300520190000', 'brinda.ashar@somaiya.edu', 'Student', 'Dummy subj', 'Complaint', '', 'Dummy description', '', '2019-05-05 18:30:00', 'Resolved Offline', 0, 'Dummy Action', '2019-05-30 08:38:32'),
('300520190001', 'brinda.ashar@somaiya.edu', 'Student', 'a', 'Complaint', '', 'aag', '', '2019-05-13 18:30:00', 'Resolved Offline', 0, 'sadfhra', '2019-05-30 08:53:07');

-- --------------------------------------------------------

--
-- Table structure for table `userdet`
--

CREATE TABLE `userdet` (
  `lname` varchar(30) NOT NULL,
  `fname` varchar(30) NOT NULL,
  `fathername` varchar(30) DEFAULT NULL,
  `mothername` varchar(30) DEFAULT NULL,
  `id` varchar(12) NOT NULL,
  `email` varchar(50) NOT NULL,
  `class` varchar(10) NOT NULL,
  `dept` varchar(1000) NOT NULL,
  `joinyear` varchar(4) NOT NULL,
  `suspend` tinyint(1) NOT NULL DEFAULT '0',
  `OTP` text NOT NULL,
  `expiry_time` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userdet`
--

INSERT INTO `userdet` (`lname`, `fname`, `fathername`, `mothername`, `id`, `email`, `class`, `dept`, `joinyear`, `suspend`, `OTP`, `expiry_time`) VALUES
('PATIL', 'ANIKET', 'SANJAY', '', '1611121', '', 'LY', 'COMPS', '2016', 0, '', ''),
('BHARADWAJ', 'AVINASH', 'SHANKAR', '', '1611065', 'a.bharadwaj@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('PAREKH', 'AAYUSH', 'SANJAY', '', '1611093', 'aayush.p@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('SHETTY', 'AHAAN', 'RAMESH', '', '1611059', 'ahan.shetty@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('KOTAK', 'AKSHAY', 'RAJESH', '', '1611085', 'akshay.kotak@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('VALERA', 'AMAN', 'MANISH', '', '1611031', 'aman.valera@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('BHUJBAL', 'AMIT', 'SANJAY', '', '1611124', 'amit.bhujbal@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('DAVE', 'AMIT', 'NILESHKUMAR', '', '1611073', 'amit.dave@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('YADAV', 'AMIT', 'JAIPRAKASH', '', '1721011', 'amit.jy@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('MAHAJAN', 'AMIT', 'ATUL', '', '1611087', 'amit.mahajan@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('GHOSH', 'ANIMESH', 'DULAL', '', '1721020', 'animesh.g@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('CHAUDHARI', 'ANJALI', 'EKNATH', '', '1721022', 'anjali.chaudhari@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('GUJARE', 'ANKIT', 'VIDYADHAR', '', '1611018', 'ankit.gujare@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('SHAH', 'ANUSHREE', 'JAYESH', '', '1721009', 'anushree.shah@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('SHAH', 'ASHNA', 'JINESH', '', '1611102', 'ashna.shah@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('GOLE', 'ATHARVA', 'MUKUND', '', '1611078', 'atharva.gole@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('SANDBHOR', 'ATHARVA', 'SHASHANK', '', '1721008', 'atharva.sandbhor@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('SURI', 'AYUSH', 'RAVISH', '', '1721021', 'ayush.suri@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('CHAUHAN', 'BHAVIN', 'JAGDISH', '', '1611007', 'b.chauhan@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('SEVAK', 'BHUMIKA', 'KANHAIYA', '', '1721015', 'b.sevak@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('MAMTORA', 'BHAVINI', 'PIYUSH', '', '1611025', 'bhavini.mamtora@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('ASHAR', 'BRINDA', 'SUNIL', '', '1611064', 'brinda.ashar@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('MORE', 'CHAITRAVI', 'NARENDRA', '', '1611028', 'chaitravi.m@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('CHHEDA', 'RUSHABH', 'ABHAY', '', '1611070', 'chheda.r@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('KAMERKAR', 'CHINMAY', 'SANJAY', '', '1611063', 'chinmay.kamerkar@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('SHETTY', 'CHIRAG', 'AVINASH', '', '1611110', 'chirag.shetty@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('AGNIHOTRI', 'CHITRA', 'RANJEET', '', '1721012', 'chitra.a@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('GANDHI', 'DARSHAN', 'BHAVESH', '', '1611069', 'darshan.gandhi@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('MACHHAR', 'DARSHEE', 'BHUPENDRA', '', '1611024', 'darshee.m@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('SHAH', 'DARSHIL', 'KAUSHIK', '', '1721014', 'darshil.ks@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('SHAH', 'DARSHIL', 'SANJAY', '', '1611040', 'darshil11@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('DASH', 'DEBABRATA', 'PRASANNAKUMAR', '', '1611009', 'debabrata.d@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('SOLANKI', 'DEVANSH', 'SANJEEV', '', '1611112', 'devansh.solanki@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('joshi', 'dharmik', 'vinod', '', '1611020', 'dharmik.joshi@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('NAGDA', 'DHARMIL', 'BHAVESH', '', '1611128', 'dharmil.n@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('PAL', 'DHIRAJKUMAR', 'RAMANAND', '', '1611092', 'dhirajkumar.p@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('PATEL', 'DHRUTI', 'SUNIL', '', '1721007', 'dhruti.p@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('VYAS', 'DHRUV', 'GHANSHYAM', '', '1721002', 'dhruv.vyas@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('VADALIA', 'DHRUVI', 'HITESH', '', '1611115', 'dhruvi.vadalia@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('JHAVERI', 'DHRUVIL', 'RAJESH', '', '1611082', 'dhruvil.j@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('SHAH', 'DHRUVIL', 'KETAN', '', '1611103', 'dhruvil.s@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('SHAH', 'DHRUVIN', 'SUNIL', '', '1611041', 'dhruvin30@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('VIRADIYA', 'DHRUVIT', 'BHARATBHAI', '', '1611116', 'dhruvit.v@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('KHETAN', 'DIVYA', 'VINAY', '', '1611061', 'divya.khetan@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('DOSHI', 'VATSAL', 'SANJAY', '', '1721016', 'doshi.vs@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('KHANDEKAR', 'GAURAV', 'RAJESH', '', '1721023', 'g.khandekar@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('GANDHI', 'HARSH', 'KIRAN', '', '1611014', 'gandhi.hk@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('SHAH', 'GREEVA', 'JAYESH', '', '1611104', 'greeva.shah@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('DHAROD', 'GRUSHA', 'JAYESH', '', '1611012', 'grusha.d@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('CHODVADIYA', 'HARDIK', 'KAMLESH', '', '1611071', 'h.chodvadiya@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('RATHOD', 'HARDIK', 'PARESH', '', '1611099', 'hardik.pr@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('DOMADIA', 'HARSH', 'MINESH', '', '1611075', 'harsh.domadia@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('PALAN', 'HARSH', 'JITESH', '', '1611030', 'harsh.palan@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('PATEL', 'HARSH', 'JITENDRA', '', '1611032', 'harsh.patel4@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('DEDHIA', 'HARSHAL', 'ASHOK', '', '1611011', 'harshal.ad@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('ATTARWALA', 'HUSSAIN', 'SAIFUDDIN', '', '1611002', 'hussain.a@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('SHAH', 'JAINEEL', 'NAILESH', '', '1611042', 'jaineel.ns@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('GROVER', 'JASDEEP', 'AMARDEEP SINGH', '', '1611079', 'jasdeepsingh.g@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('GALA', 'JAY', 'VIPUL', '', '1611076', 'jay.gala1@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('SHAH', 'JAY', 'RAJIV', '', '1611130', 'jay.shah7@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('SANGHAVI', 'JAYNAM', 'HASMUKH', '', '1721013', 'jaynam.s@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('GUJARATHI', 'JEET', 'KAUSHAL', '', '1611080', 'jeet.gujarathi@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('MEHTA', 'JINIT', 'HITESH', '', '1611001', 'jinit.mehta@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('MEHTA', 'JASH', 'KAMLESH', '', '1611026', 'jkm@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('TELLIS', 'JOEL', 'MARVIN ALEX', '', '1611050', 'joelmarvin.t@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('BHARMANI', 'JUGAL', 'ATUL', '', '1611004', 'jugal.bharmani@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('MENGADE', 'JYOTI', 'DNYANESHWAR', '', '1721019', 'jyoti.mengade@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('CHANDRA', 'KARAN', 'RAMESH', '', '1611006', 'karan.chandra@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('YADAV', 'KAUSHAL', 'RAMESH', '', '1611054', 'kaushal.y@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('DAVDA', 'KETKI', 'JAYANT', '', '1611010', 'ketki.davda@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('GANGAR', 'KEWAL', 'KIRAN', '', '1721018', 'kewal.kg@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('HARIA', 'KUNJ', 'CHETAN', '', '1721001', 'kunj.ch@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('MEHTA', 'KUNJ', 'CHETAN', '', '1611089', 'kunj.mehta@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('KOTIAN', 'LAKSH', 'BHASKAR', '', '1611047', 'laksh.kotian@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('KANKROLIWALA', 'MURTAZA', 'SHABBIR', '', '1721017', 'm.kankoliwala@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('PATRAWALA', 'MURTAZA', 'TAHER', '', '1611034', 'm.patrawala@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('SHAH', 'MAHEK', 'KAILESH', '', '1721003', 'mahek.shah2@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('TANNA', 'MAULIK', 'ASHISH', '', '1611049', 'maulik.tanna@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('GUDI', 'MAYUR', 'MANOJ', '', '1611017', 'mayur.gudi@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('BALSARA', 'MIHIR', 'VIRESH', '', '1611003', 'mihir.balsara@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('GANDHI', 'MIHIR', 'MILIND', '', '1611077', 'mihir.mg@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('SHAH', 'MIHIR', 'SHAILESH', '', '1611118', 'mihir.ss@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('GUPTA', 'MUSKAAN', 'HARSH', '', '1611122', 'muskaan.g@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('NADIGER', 'ROHIT', '', '', '1611056', 'nadiger.r@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('GHOGALE', 'NARAYAN', 'BHIKAJI', '', '1611015', 'narayan.ghogale@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('SHAH', 'NEEL', 'MANILAL', '', '1611105', 'neel12@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('GOSAR', 'NEELAY', 'VIRAL', '', '1611016', 'neelay.gosar@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('KUMAR', 'NEHA', 'RAMA', '', '1611029', 'neha.kumar@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('MAPARA', 'NINAD', 'UMESH', '', '1611088', 'ninad.mapara@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('IYER', 'NIPUN', 'MOHAN', '', '1611081', 'nipun.iyer@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('SHETTY', 'NISHCHITH', 'KORAGA', '', '1611043', 'nishchith.s@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('PATEL', 'NISHIT', 'MUKESH', '', '1611095', 'nishit.mp@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('HEBBARE', 'NITESH', 'MALTESH', '', '1611123', 'nitesh.h@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('KAPRE', 'OJAS', 'SUHAS', '', '1611022', 'ojas.kapre@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('NIKHARE', 'PRATIKSHA', 'DAMODHAR', '', '1611129', 'p.nikhare@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('CHOUDHARY', 'PRANAVI', 'P', '', '1611055', 'p.pranavi@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('THAKKAR', 'PANKTI', 'ASHOK', '', '1611114', 'pankti.thakkar@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('BARBHAYA', 'PARSHVA', 'HITESH', '', '1611066', 'parshva.barbhaya@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('SANGHAVI', 'PARTH', 'JITENDRA', '', '1611038', 'parth.js@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('MEHTA', 'PARTHVI', 'NIMISH', '', '1611027', 'parthvi.m@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('PATIL', 'NEHA', 'PRADEEP', '', '1611096', 'patil.n@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('SHAH', 'PARTH', 'HEMANT', '', '1611044', 'phs1@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('JAVARE', 'PRAFFUL', 'DATTU', '', '1611062', 'prafful.j@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('SHETTY', 'PRAMIT', 'PRADEEP', '', '1611048', 'pramit.shetty@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('PAREKH', 'PRAVAR', 'UMESH', '', '1611053', 'pravar.p@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('KUNDER', 'PRITHVI', 'UMESH', '', '1611023', 'prithvi.kunder@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('SHAH', 'PRIYAM', 'DINESH', '', '1611107', 'priyam.ds@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('PATEL', 'PRIYESH', 'RAJESH', '', '1611033', 'priyesh.patel@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('SHAH', 'PULIN', 'DEEPAK', '', '1611045', 'pulin.shah@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('PUNJABI', 'RAHUL', 'PURSHOTTAM', '', '1611035', 'rahul.punjabi@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('VORA', 'RAJ', 'JAYESH', '', '1611117', 'raj.jv@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('TAPASE', 'RAJ', 'ANANT', '', '1611113', 'raj.tapase@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('RAJANI', 'RAM', 'VINOD', '', '1611098', 'ram.rajani@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('REBECCA', 'BIJU', '', '', '1611100', 'rebecca.biju@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('KABRA', 'RISHIK', 'PRASHANT', '', '1611021', 'rishik.kabra@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('SOLSI', 'ROHAN', 'SHARATKUMAR', '', '1611005', 'rohan.solsi@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('VADHAIYA', 'RONAK', 'DINESHKUMAR', '', '1721004', 'ronak.vadhaiya@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('BHATIA', 'RUCHI', 'SHAILESH', '', '1611067', 'ruchi.bhatia@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('THOSAR', 'RUCHI', 'SACHIN', '', '1611057', 'ruchi.thosar@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('BID', 'RUSHABH', 'HITESH', 'MEETA', '1514009', 'rushabh.bid@somaiya.edu', 'Passed Out', 'IT', '2015', 0, '', ''),
('DAPTARDAR', 'RUTWIJ', 'SANJAY', '', '1611125', 'rutwij.d@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('DASORWALA', 'SAKINA', 'KAYYUM', '', '1611072', 'sakina.dasorwala@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('DESAI', 'SANGRAM', 'SANJAY', '', '1611074', 'sangram.desai@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('JOSHI', 'SANJANA', 'SHREEKANT', '', '1611083', 'sanjana.j@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('SHAMILEE', 'PERUVALUDHI', '', '', '1611046', 'shamilee.p@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('PATIL', 'SHARVAI', 'MILIND', '', '1611109', 'sharvai.p@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('MAHAJAN', 'SHIVAM', 'PRANAY', '', '1611126', 'shivam.mahajan@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('PAWASE', 'SHIVAM', 'VITTHAL', '', '1611097', 'shivam.pawase@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('SANSARE', 'SHIVAM', 'ARVIND', '', '1611101', 'shivam.sansare@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('JAISWAL', 'SHIVANEE', 'DINESHKUMAR', '', '1611019', 'shivanee.j@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('JAISWAL', 'SHIVASHISH', 'OMPRAKASH', '', '1611060', 'shivashish.j@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('VARMA', 'SHREYA', 'SHIV', '', '1611052', 'shreya.varma@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('DHARIYA', 'SHRUTI', 'SANJAY', '', '1611094', 'shruti.dhariya@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('UMACHANDAR', 'SIDDHARTH', '', '', '1611111', 'siddharth.u@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('BHOJANI', 'SIMITH', 'JASMINE', '', '1721006', 'simith.bhojani@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('KOUL', 'SIMRAN', '', '', '1611127', 'simran.koul@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('CHITALIA', 'SNEH', 'BINDESH', '', '1611008', 'sneh.c@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('RAUL', 'TANAY', 'HEMANT', '', '1611037', 'tanay.raul@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('SEHGAL', 'TANISH', 'DEEPAK', '', '1721010', 'tanish.sehgal@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('LOHANA', 'TARUN', 'ANILKUMAR', '', '1611086', 'tarun.lohana@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('THAKKAR', 'SOHAM', 'ATUL', '', '1611051', 'thakkar.sa@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('SHAH', 'TIRTH', 'SHAILESH', '', '1611108', 'tirth.ss@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('KADAM', 'TUSHAR', 'PANDURANG', '', '1611084', 'tushar.kadam@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('UTEKAR', 'VAISHNAVI', 'SUDHIR', '', '1721024', 'vaishnavi.u@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('DAIYA', 'VICKY', 'AMAR', '', '1611013', 'vicky.daiya@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('PARIKH', 'VIDHI', 'SARJU', '', '1721005', 'vidhi.parikh@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('RAMBHIA', 'VIRAL', 'ANIL', '', '1611036', 'viral.rambhia@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('KOTHARI', 'VISHAL', 'KAMLESH', '', '1511061', 'vishal.kk@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('PATEL', 'VRIDHI', 'BHADRESH', '', '1611106', 'vridhi.patel@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', ''),
('GUPTE', 'YASH', 'RAJESH', '', '1611058', 'yash.gupte@somaiya.edu', 'LY', 'COMPS', '2016', 0, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `userdetold`
--

CREATE TABLE `userdetold` (
  `name` varchar(1000) NOT NULL,
  `id` bigint(20) NOT NULL,
  `email` varchar(500) NOT NULL,
  `OTP` text NOT NULL,
  `expiry_time` text NOT NULL,
  `suspend` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userdetold`
--

INSERT INTO `userdetold` (`name`, `id`, `email`, `OTP`, `expiry_time`, `suspend`) VALUES
('PATIL ANIKET SANJAY', 1611121, '', '', '', 0),
('BHARADWAJ AVINASH SHANKAR', 1611065, 'a.bharadwaj@somaiya.edu', '', '', 0),
('PAREKH AAYUSH SANJAY', 1611093, 'aayush.p@somaiya.edu', '', '', 0),
('SHETTY AHAAN RAMESH', 1611059, 'ahan.shetty@somaiya.edu', '', '', 0),
('KOTAK AKSHAY RAJESH', 1611085, 'akshay.kotak@somaiya.edu', '', '', 0),
('VALERA AMAN MANISH', 1611031, 'aman.valera@somaiya.edu', '', '', 0),
('BHUJBAL AMIT SANJAY', 1611124, 'amit.bhujbal@somaiya.edu', '', '', 0),
('DAVE AMIT NILESHKUMAR', 1611073, 'amit.dave@somaiya.edu', '', '', 0),
('YADAV AMIT JAIPRAKASH', 1721011, 'amit.jy@somaiya.edu', '', '', 0),
('MAHAJAN AMIT ATUL', 1611087, 'amit.mahajan@somaiya.edu', '', '', 0),
('GHOSH ANIMESH DULAL', 1721020, 'animesh.g@somaiya.edu', '', '', 0),
('CHAUDHARI ANJALI EKNATH', 1721022, 'anjali.chaudhari@somaiya.edu', '', '', 0),
('GUJARE ANKIT VIDYADHAR', 1611018, 'ankit.gujare@somaiya.edu', '', '', 0),
('SHAH ANUSHREE JAYESH', 1721009, 'anushree.shah@somaiya.edu', '', '', 0),
('SHAH ASHNA JINESH', 1611102, 'ashna.shah@somaiya.edu', '', '', 0),
('GOLE ATHARVA MUKUND', 1611078, 'atharva.gole@somaiya.edu', '', '', 0),
('SANDBHOR ATHARVA SHASHANK', 1721008, 'atharva.sandbhor@somaiya.edu', '', '', 0),
('SURI AYUSH RAVISH', 1721021, 'ayush.suri@somaiya.edu', '', '', 0),
('CHAUHAN BHAVIN JAGDISH', 1611007, 'b.chauhan@somaiya.edu', '', '', 0),
('SEVAK BHUMIKA KANHAIYA', 1721015, 'b.sevak@somaiya.edu', '', '', 0),
('MAMTORA BHAVINI PIYUSH', 1611025, 'bhavini.mamtora@somaiya.edu', '', '', 0),
('ASHAR BRINDA SUNIL', 1611064, 'brinda.ashar@somaiya.edu', '', '', 0),
('MORE CHAITRAVI NARENDRA', 1611028, 'chaitravi.m@somaiya.edu', '', '', 0),
('CHHEDA RUSHABH ABHAY', 1611070, 'chheda.r@somaiya.edu', '', '', 0),
('KAMERKAR CHINMAY SANJAY', 1611063, 'chinmay.kamerkar@somaiya.edu', '', '', 0),
('SHETTY CHIRAG AVINASH', 1611110, 'chirag.shetty@somaiya.edu', '', '', 0),
('AGNIHOTRI CHITRA RANJEET', 1721012, 'chitra.a@somaiya.edu', '', '', 0),
('GANDHI DARSHAN BHAVESH', 1611069, 'darshan.gandhi@somaiya.edu', '', '', 0),
('MACHHAR DARSHEE BHUPENDRA', 1611024, 'darshee.m@somaiya.edu', '', '', 0),
('SHAH DARSHIL KAUSHIK', 1721014, 'darshil.ks@somaiya.edu', '', '', 0),
('SHAH DARSHIL SANJAY', 1611040, 'darshil11@somaiya.edu', '', '', 0),
('DASH DEBABRATA PRASANNAKUMAR', 1611009, 'debabrata.d@somaiya.edu', '', '', 0),
('SOLANKI DEVANSH SANJEEV', 1611112, 'devansh.solanki@somaiya.edu', '', '', 0),
('joshi dharmik vinod', 1611020, 'dharmik.joshi@somaiya.edu', '', '', 0),
('NAGDA DHARMIL BHAVESH', 1611128, 'dharmil.n@somaiya.edu', '', '', 0),
('PAL DHIRAJKUMAR RAMANAND', 1611092, 'dhirajkumar.p@somaiya.edu', '', '', 0),
('PATEL DHRUTI SUNIL', 1721007, 'dhruti.p@somaiya.edu', '', '', 0),
('VYAS DHRUV GHANSHYAM', 1721002, 'dhruv.vyas@somaiya.edu', '', '', 0),
('VADALIA DHRUVI HITESH', 1611115, 'dhruvi.vadalia@somaiya.edu', '', '', 0),
('JHAVERI DHRUVIL RAJESH', 1611082, 'dhruvil.j@somaiya.edu', '', '', 0),
('SHAH DHRUVIL KETAN', 1611103, 'dhruvil.s@somaiya.edu', '', '', 0),
('SHAH DHRUVIN SUNIL', 1611041, 'dhruvin30@somaiya.edu', '', '', 0),
('VIRADIYA DHRUVIT BHARATBHAI', 1611116, 'dhruvit.v@somaiya.edu', '', '', 0),
('KHETAN DIVYA VINAY', 1611061, 'divya.khetan@somaiya.edu', '', '', 0),
('DOSHI VATSAL SANJAY', 1721016, 'doshi.vs@somaiya.edu', '', '', 0),
('KHANDEKAR GAURAV RAJESH', 1721023, 'g.khandekar@somaiya.edu', '', '', 0),
('GANDHI HARSH KIRAN', 1611014, 'gandhi.hk@somaiya.edu', '', '', 0),
('SHAH GREEVA JAYESH', 1611104, 'greeva.shah@somaiya.edu', '', '', 0),
('DHAROD GRUSHA JAYESH', 1611012, 'grusha.d@somaiya.edu', '', '', 0),
('CHODVADIYA HARDIK KAMLESH', 1611071, 'h.chodvadiya@somaiya.edu', '', '', 0),
('RATHOD HARDIK PARESH', 1611099, 'hardik.pr@somaiya.edu', '', '', 0),
('DOMADIA HARSH MINESH', 1611075, 'harsh.domadia@somaiya.edu', '', '', 0),
('PALAN HARSH JITESH', 1611030, 'harsh.palan@somaiya.edu', '', '', 0),
('PATEL HARSH JITENDRA', 1611032, 'harsh.patel4@somaiya.edu', '', '', 0),
('DEDHIA HARSHAL ASHOK', 1611011, 'harshal.ad@somaiya.edu', '', '', 0),
('ATTARWALA HUSSAIN SAIFUDDIN', 1611002, 'hussain.a@somaiya.edu', '', '', 0),
('SHAH JAINEEL NAILESH', 1611042, 'jaineel.ns@somaiya.edu', '', '', 0),
('GROVER JASDEEP SINGH AMARDEEP SINGH', 1611079, 'jasdeepsingh.g@somaiya.edu', '', '', 0),
('GALA JAY VIPUL', 1611076, 'jay.gala1@somaiya.edu', '', '', 0),
('SHAH JAY RAJIV', 1611130, 'jay.shah7@somaiya.edu', '', '', 0),
('SANGHAVI JAYNAM HASMUKH', 1721013, 'jaynam.s@somaiya.edu', '', '', 0),
('GUJARATHI JEET KAUSHAL', 1611080, 'jeet.gujarathi@somaiya.edu', '', '', 0),
('MEHTA JINIT HITESH', 1611001, 'jinit.mehta@somaiya.edu', '', '', 0),
('MEHTA JASH KAMLESH', 1611026, 'jkm@somaiya.edu', '', '', 0),
('TELLIS JOEL MARVIN ALEX', 1611050, 'joelmarvin.t@somaiya.edu', '', '', 0),
('BHARMANI JUGAL ATUL', 1611004, 'jugal.bharmani@somaiya.edu', '', '', 0),
('MENGADE JYOTI DNYANESHWAR', 1721019, 'jyoti.mengade@somaiya.edu', '', '', 0),
('CHANDRA KARAN RAMESH', 1611006, 'karan.chandra@somaiya.edu', '', '', 0),
('YADAV KAUSHAL RAMESH', 1611054, 'kaushal.y@somaiya.edu', '', '', 0),
('DAVDA KETKI JAYANT', 1611010, 'ketki.davda@somaiya.edu', '', '', 0),
('GANGAR KEWAL KIRAN', 1721018, 'kewal.kg@somaiya.edu', '', '', 0),
('HARIA KUNJ CHETAN', 1721001, 'kunj.ch@somaiya.edu', '', '', 0),
('MEHTA KUNJ CHETAN', 1611089, 'kunj.mehta@somaiya.edu', '', '', 0),
('KOTIAN LAKSH BHASKAR', 1611047, 'laksh.kotian@somaiya.edu', '', '', 0),
('KANKROLIWALA MURTAZA SHABBIR', 1721017, 'm.kankoliwala@somaiya.edu', '', '', 0),
('PATRAWALA MURTAZA TAHER', 1611034, 'm.patrawala@somaiya.edu', '', '', 0),
('SHAH MAHEK KAILESH', 1721003, 'mahek.shah2@somaiya.edu', '', '', 0),
('TANNA MAULIK ASHISH', 1611049, 'maulik.tanna@somaiya.edu', '', '', 0),
('GUDI MAYUR MANOJ', 1611017, 'mayur.gudi@somaiya.edu', '', '', 0),
('BALSARA MIHIR VIRESH', 1611003, 'mihir.balsara@somaiya.edu', '', '', 0),
('GANDHI MIHIR MILIND', 1611077, 'mihir.mg@somaiya.edu', '', '', 0),
('SHAH MIHIR SHAILESH', 1611118, 'mihir.ss@somaiya.edu', '', '', 0),
('GUPTA MUSKAAN HARSH', 1611122, 'muskaan.g@somaiya.edu', '', '', 0),
('NADIGER ROHIT', 1611056, 'nadiger.r@somaiya.edu', '', '', 0),
('GHOGALE NARAYAN BHIKAJI', 1611015, 'narayan.ghogale@somaiya.edu', '', '', 0),
('SHAH NEEL MANILAL', 1611105, 'neel12@somaiya.edu', '', '', 0),
('GOSAR NEELAY VIRAL', 1611016, 'neelay.gosar@somaiya.edu', '', '', 0),
('KUMAR NEHA RAMA', 1611029, 'neha.kumar@somaiya.edu', '', '', 0),
('MAPARA NINAD UMESH', 1611088, 'ninad.mapara@somaiya.edu', '', '', 0),
('IYER NIPUN MOHAN', 1611081, 'nipun.iyer@somaiya.edu', '', '', 0),
('SHETTY NISHCHITH KORAGA', 1611043, 'nishchith.s@somaiya.edu', '', '', 0),
('PATEL NISHIT MUKESH', 1611095, 'nishit.mp@somaiya.edu', '', '', 0),
('HEBBARE NITESH MALTESH', 1611123, 'nitesh.h@somaiya.edu', '', '', 0),
('KAPRE OJAS SUHAS', 1611022, 'ojas.kapre@somaiya.edu', '', '', 0),
('NIKHARE PRATIKSHA DAMODHAR', 1611129, 'p.nikhare@somaiya.edu', '', '', 0),
('CHOUDHARY PRANAVI P', 1611055, 'p.pranavi@somaiya.edu', '', '', 0),
('THAKKAR PANKTI ASHOK', 1611114, 'pankti.thakkar@somaiya.edu', '', '', 0),
('BARBHAYA PARSHVA HITESH', 1611066, 'parshva.barbhaya@somaiya.edu', '', '', 0),
('SANGHAVI PARTH JITENDRA', 1611038, 'parth.js@somaiya.edu', '', '', 0),
('MEHTA PARTHVI NIMISH', 1611027, 'parthvi.m@somaiya.edu', '', '', 0),
('PATIL NEHA PRADEEP', 1611096, 'patil.n@somaiya.edu', '', '', 0),
('SHAH PARTH HEMANT', 1611044, 'phs1@somaiya.edu', '', '', 0),
('JAVARE PRAFFUL DATTU', 1611062, 'prafful.j@somaiya.edu', '', '', 0),
('SHETTY PRAMIT PRADEEP', 1611048, 'pramit.shetty@somaiya.edu', '', '', 0),
('PAREKH PRAVAR UMESH', 1611053, 'pravar.p@somaiya.edu', '', '', 0),
('KUNDER PRITHVI UMESH', 1611023, 'prithvi.kunder@somaiya.edu', '', '', 0),
('SHAH PRIYAM DINESH', 1611107, 'priyam.ds@somaiya.edu', '', '', 0),
('PATEL PRIYESH RAJESH', 1611033, 'priyesh.patel@somaiya.edu', '', '', 0),
('SHAH PULIN DEEPAK', 1611045, 'pulin.shah@somaiya.edu', '', '', 0),
('PUNJABI RAHUL PURSHOTTAM', 1611035, 'rahul.punjabi@somaiya.edu', '', '', 0),
('VORA RAJ JAYESH', 1611117, 'raj.jv@somaiya.edu', '', '', 0),
('TAPASE RAJ ANANT', 1611113, 'raj.tapase@somaiya.edu', '', '', 0),
('RAJANI RAM VINOD', 1611098, 'ram.rajani@somaiya.edu', '', '', 0),
('REBECCA BIJU', 1611100, 'rebecca.biju@somaiya.edu', '', '', 0),
('KABRA RISHIK PRASHANT', 1611021, 'rishik.kabra@somaiya.edu', '', '', 0),
('SOLSI ROHAN SHARATKUMAR', 1611005, 'rohan.solsi@somaiya.edu', '', '', 0),
('VADHAIYA RONAK DINESHKUMAR', 1721004, 'ronak.vadhaiya@somaiya.edu', '', '', 0),
('BHATIA RUCHI SHAILESH', 1611067, 'ruchi.bhatia@somaiya.edu', '', '', 0),
('THOSAR RUCHI SACHIN', 1611057, 'ruchi.thosar@somaiya.edu', '', '', 0),
('DAPTARDAR RUTWIJ SANJAY', 1611125, 'rutwij.d@somaiya.edu', '', '', 0),
('DASORWALA SAKINA KAYYUM', 1611072, 'sakina.dasorwala@somaiya.edu', '', '', 0),
('DESAI SANGRAM SANJAY', 1611074, 'sangram.desai@somaiya.edu', '', '', 0),
('JOSHI SANJANA SHREEKANT', 1611083, 'sanjana.j@somaiya.edu', '', '', 0),
('SHAMILEE PERUVALUDHI', 1611046, 'shamilee.p@somaiya.edu', '', '', 0),
('PATIL SHARVAI MILIND', 1611109, 'sharvai.p@somaiya.edu', '', '', 0),
('MAHAJAN SHIVAM PRANAY', 1611126, 'shivam.mahajan@somaiya.edu', '', '', 0),
('PAWASE SHIVAM VITTHAL', 1611097, 'shivam.pawase@somaiya.edu', '', '', 0),
('SANSARE SHIVAM ARVIND', 1611101, 'shivam.sansare@somaiya.edu', '', '', 0),
('JAISWAL SHIVANEE DINESHKUMAR', 1611019, 'shivanee.j@somaiya.edu', '', '', 0),
('JAISWAL SHIVASHISH OMPRAKASH', 1611060, 'shivashish.j@somaiya.edu', '', '', 0),
('VARMA SHREYA SHIV', 1611052, 'shreya.varma@somaiya.edu', '', '', 0),
('DHARIYA SHRUTI SANJAY', 1611094, 'shruti.dhariya@somaiya.edu', '', '', 0),
('UMACHANDAR SIDDHARTH', 1611111, 'siddharth.u@somaiya.edu', '', '', 0),
('BHOJANI SIMITH JASMINE', 1721006, 'simith.bhojani@somaiya.edu', '', '', 0),
('KOUL SIMRAN', 1611127, 'simran.koul@somaiya.edu', '', '', 0),
('CHITALIA SNEH BINDESH', 1611008, 'sneh.c@somaiya.edu', '', '', 0),
('RAUL TANAY HEMANT', 1611037, 'tanay.raul@somaiya.edu', '', '', 0),
('SEHGAL TANISH DEEPAK', 1721010, 'tanish.sehgal@somaiya.edu', '', '', 0),
('LOHANA TARUN ANILKUMAR', 1611086, 'tarun.lohana@somaiya.edu', '', '', 0),
('THAKKAR SOHAM ATUL', 1611051, 'thakkar.sa@somaiya.edu', '', '', 0),
('SHAH TIRTH SHAILESH', 1611108, 'tirth.ss@somaiya.edu', '', '', 0),
('KADAM TUSHAR PANDURANG', 1611084, 'tushar.kadam@somaiya.edu', '', '', 0),
('UTEKAR VAISHNAVI SUDHIR', 1721024, 'vaishnavi.u@somaiya.edu', '', '', 0),
('DAIYA VICKY AMAR', 1611013, 'vicky.daiya@somaiya.edu', '', '', 0),
('PARIKH VIDHI SARJU', 1721005, 'vidhi.parikh@somaiya.edu', '', '', 0),
('RAMBHIA VIRAL ANIL', 1611036, 'viral.rambhia@somaiya.edu', '', '', 0),
('KOTHARI VISHAL KAMLESH', 1511061, 'vishal.kk@somaiya.edu', '', '', 0),
('PATEL VRIDHI BHADRESH', 1611106, 'vridhi.patel@somaiya.edu', '', '', 0),
('GUPTE YASH RAJESH', 1611058, 'yash.gupte@somaiya.edu', '', '', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admindet`
--
ALTER TABLE `admindet`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `cancelled`
--
ALTER TABLE `cancelled`
  ADD PRIMARY KEY (`gid`);

--
-- Indexes for table `catdet`
--
ALTER TABLE `catdet`
  ADD PRIMARY KEY (`category`);

--
-- Indexes for table `commdet`
--
ALTER TABLE `commdet`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `facdet`
--
ALTER TABLE `facdet`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `grievance`
--
ALTER TABLE `grievance`
  ADD PRIMARY KEY (`gid`);

--
-- Indexes for table `resolved`
--
ALTER TABLE `resolved`
  ADD PRIMARY KEY (`gid`);

--
-- Indexes for table `userdet`
--
ALTER TABLE `userdet`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `userdetold`
--
ALTER TABLE `userdetold`
  ADD PRIMARY KEY (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
