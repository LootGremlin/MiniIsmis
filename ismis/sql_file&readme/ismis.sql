-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2020 at 01:04 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ismis`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `Id` int(11) NOT NULL,
  `FirstName` varchar(32) NOT NULL,
  `LastName` varchar(32) NOT NULL,
  `UserType` varchar(32) NOT NULL,
  `HashPass` longtext NOT NULL,
  `Email` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`Id`, `FirstName`, `LastName`, `UserType`, `HashPass`, `Email`) VALUES
(1, 'Admin', 'Admin', 'Admin', '827ccb0eea8a706c4c34a16891f84e7b', 'admin@gmail.com'),
(3, 'Lizzy', 'Nightray', 'Student', 'e10adc3949ba59abbe56e057f20f883e', 'Lootgremlin@gmail.com'),
(4, 'Josh', 'Baz', 'Faculty', 'e10adc3949ba59abbe56e057f20f883e', 'joshbaz@gmail.com'),
(6, 'Bruddah', 'Bear', 'Faculty', '202cb962ac59075b964b07152d234b70', 'bruddahBear@gmail.com'),
(7, 'Kylo', 'Solo', 'Student', 'e10adc3949ba59abbe56e057f20f883e', 'bensolo@gmail.com'),
(8, 'Kyla', 'Conlu', 'Student', 'e10adc3949ba59abbe56e057f20f883e', 'kylaconlu@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `subjattendees`
--

CREATE TABLE `subjattendees` (
  `SubjId` int(11) NOT NULL,
  `StudId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subjattendees`
--

INSERT INTO `subjattendees` (`SubjId`, `StudId`) VALUES
(1, 8),
(9, 3),
(5, 3),
(5, 7),
(1, 7);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `SubjectId` int(11) NOT NULL,
  `SubjectName` varchar(32) NOT NULL,
  `StartingTime` time NOT NULL,
  `EndTime` time NOT NULL,
  `Day` varchar(32) NOT NULL,
  `MaxStud` int(11) NOT NULL,
  `SubjCode` varchar(32) NOT NULL,
  `TeacherId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`SubjectId`, `SubjectName`, `StartingTime`, `EndTime`, `Day`, `MaxStud`, `SubjCode`, `TeacherId`) VALUES
(1, 'Web Dev', '07:30:00', '08:00:00', 'Monday', 10, '1102', 4),
(5, 'Information Management', '08:00:00', '09:00:00', 'Tuesday', 10, '1204', 4),
(9, 'Digital Logic', '07:30:00', '08:00:00', 'Monday', 10, '2103', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`SubjectId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `SubjectId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
