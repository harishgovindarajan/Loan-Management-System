-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 14, 2024 at 08:46 AM
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
-- Database: `lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `applicationstatus`
--

CREATE TABLE `applicationstatus` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `adhar` varchar(12) NOT NULL,
  `pan` varchar(10) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `period` int(11) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `applied_date` date NOT NULL,
  `adhar_file` varchar(255) NOT NULL,
  `pan_file` varchar(255) NOT NULL,
  `tran_slip` varchar(255) NOT NULL,
  `security_doc` varchar(255) NOT NULL,
  `status` varchar(10) NOT NULL,
  `payment_status` varchar(20) NOT NULL,
  `accept_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applicationstatus`
--

INSERT INTO `applicationstatus` (`id`, `name`, `email`, `phone`, `adhar`, `pan`, `amount`, `period`, `purpose`, `applied_date`, `adhar_file`, `pan_file`, `tran_slip`, `security_doc`, `status`, `payment_status`, `accept_date`) VALUES
(17, 'Anirudh', 'harishragavan2502@gmail.com', '9677559295', '123456789456', '2d5e8s9d3v', 2900000.00, 24, 'Studies in Abroad', '2024-03-05', 'Aadhar.pdf', 'Licence.pdf', 'ID Card.pdf', 'Harish_resume.pdf', 'Accepted', '', '2024-03-13 09:54:41'),
(18, 'Anirudh', 'ani7@gmail.com', '7894561235', '585869694714', 'e8r7e8w9s5', 1000.00, 2, 'chill', '2024-03-12', 'Aadhar-1.pdf', 'Licence-1.pdf', 'ID Card.pdf', 'Harish_resume.pdf', 'Accepted', '', '2024-03-13 09:43:53'),
(19, 'Harish', 'harishragavan2502@gmail.com', '7894561235', '564896521235', 'q7w8e9d6s5', 100.00, 1, 's', '2024-03-13', 'ID Card (1).pdf', 'Licence-2.pdf', 'Aadhar-1.pdf', 'Harish_resume.pdf', 'Accepted', '', '2024-03-13 09:57:43');

-- --------------------------------------------------------

--
-- Table structure for table `borrowerapplication`
--

CREATE TABLE `borrowerapplication` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `adhar` varchar(12) NOT NULL,
  `pan` varchar(10) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `period` int(11) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `applied_date` date NOT NULL,
  `adhar_file` varchar(255) NOT NULL,
  `pan_file` varchar(255) NOT NULL,
  `tran_slip` varchar(255) NOT NULL,
  `security_doc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowerapplication`
--

INSERT INTO `borrowerapplication` (`id`, `name`, `email`, `phone`, `adhar`, `pan`, `amount`, `period`, `purpose`, `applied_date`, `adhar_file`, `pan_file`, `tran_slip`, `security_doc`) VALUES
(21, 'Anirudh', 'harishragavan2502@gmail.com', '9677559295', '123456789456', '2d5e8s9d3v', 2900000.00, 24, 'Studies in Abroad', '2024-03-05', 'Aadhar.pdf', 'Licence.pdf', 'ID Card.pdf', 'Harish_resume.pdf'),
(22, 'Anirudh', 'ani7@gmail.com', '7894561235', '585869694714', 'e8r7e8w9s5', 1000.00, 2, 'chill', '2024-03-12', 'Aadhar-1.pdf', 'Licence-1.pdf', 'ID Card.pdf', 'Harish_resume.pdf'),
(23, 'Harish', 'harishragavan2502@gmail.com', '7894561235', '564896521235', 'q7w8e9d6s5', 100.00, 1, 's', '2024-03-13', 'ID Card (1).pdf', 'Licence-2.pdf', 'Aadhar-1.pdf', 'Harish_resume.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `borrowers`
--

CREATE TABLE `borrowers` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `loan_amount` decimal(10,2) NOT NULL,
  `period` int(11) NOT NULL,
  `loan_start_date` date NOT NULL,
  `interest_rate` int(11) NOT NULL,
  `emi` decimal(10,2) NOT NULL,
  `payable_amount` decimal(10,2) NOT NULL,
  `paid_amount` decimal(10,2) DEFAULT 0.00,
  `payment_date` date DEFAULT NULL,
  `loan_status` varchar(20) NOT NULL DEFAULT 'inProgress'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowers`
--

INSERT INTO `borrowers` (`id`, `name`, `email`, `phone`, `loan_amount`, `period`, `loan_start_date`, `interest_rate`, `emi`, `payable_amount`, `paid_amount`, `payment_date`, `loan_status`) VALUES
(21, 'Anirudh', 'ani7@gmail.com', '09519515632', 10000.00, 10, '2024-03-13', 10, 1100.00, 11000.00, 11000.00, '2024-03-12', 'Paid'),
(23, 'Anirudh', 'ani7@gmail.com', '7894561235', 1000.00, 2, '2024-03-13', 10, 550.00, 1100.00, 1100.00, '2024-03-12', 'Paid'),
(24, 'Harish', 'harishragavan2502@gmail.com', '8610085142', 1000.00, 5, '2024-03-15', 10, 220.00, 1100.00, 220.00, '2024-03-14', 'inProgress');

-- --------------------------------------------------------

--
-- Table structure for table `borrowersignup`
--

CREATE TABLE `borrowersignup` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `otp` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowersignup`
--

INSERT INTO `borrowersignup` (`id`, `name`, `email`, `phone`, `password`, `otp`) VALUES
(17, 'Harish', 'harishragavan2502@gmail.com', '8610085142', '$2y$10$pxDt3ZxCxTLzVaIuUcRra.fTI.QaUnewkQBXmflV7/7oavk6vRQ0K', NULL),
(21, 'Anirudh', 'ani7@gmail.com', '9696963525', '$2y$10$L9HYRJIBQgI2WHQhR89fOuOWu3U48eJeObeui7NCbnVNgxEtv7k3q', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lendersignup`
--

CREATE TABLE `lendersignup` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lendersignup`
--

INSERT INTO `lendersignup` (`id`, `name`, `email`, `phone`, `password`) VALUES
(19, 'para', 'harishragava2532@gmail.com', '9677559295', '$2y$10$W.THgGSn20OCAGtiCpdvP.kJOAZPErE0GyA3TxcARsYeR6PJn/2PO'),
(20, 'Rakesh', 'rakesh123@gmail.com', '1234567895', '$2y$10$4R0w7x3krz2zUN2bJdva9OqRo7lOPeg1D7hmmSQcCPbMhwC9sSf5u');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applicationstatus`
--
ALTER TABLE `applicationstatus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `borrowerapplication`
--
ALTER TABLE `borrowerapplication`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `borrowers`
--
ALTER TABLE `borrowers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `borrowersignup`
--
ALTER TABLE `borrowersignup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lendersignup`
--
ALTER TABLE `lendersignup`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applicationstatus`
--
ALTER TABLE `applicationstatus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `borrowerapplication`
--
ALTER TABLE `borrowerapplication`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `borrowers`
--
ALTER TABLE `borrowers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `borrowersignup`
--
ALTER TABLE `borrowersignup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `lendersignup`
--
ALTER TABLE `lendersignup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
