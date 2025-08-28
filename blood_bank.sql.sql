-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 28, 2025 at 12:45 PM
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
-- Database: `blood_bank`
--

-- --------------------------------------------------------

--
-- Table structure for table `approved_requests`
--

CREATE TABLE `approved_requests` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `blood_group` varchar(5) NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` varchar(20) DEFAULT 'Approved',
  `date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `approved_requests`
--

INSERT INTO `approved_requests` (`id`, `name`, `blood_group`, `quantity`, `status`, `date`) VALUES
(7, 'Pranshu Singh', 'B+', 1, 'Approved', '2025-05-10 12:02:45'),
(8, 'Md Javed', 'B+', 1, 'Approved', '2025-05-10 12:02:49'),
(10, 'Prashansha singh', 'A+', 2, 'Approved', '2025-04-14 09:39:50');

-- --------------------------------------------------------

--
-- Table structure for table `blood_groups`
--

CREATE TABLE `blood_groups` (
  `id` int(11) NOT NULL,
  `blood_group` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_groups`
--

INSERT INTO `blood_groups` (`id`, `blood_group`) VALUES
(1, 'A+'),
(2, 'A-'),
(7, 'AB+'),
(8, 'AB-'),
(3, 'B+'),
(4, 'B-'),
(5, 'O+'),
(6, 'O-');

-- --------------------------------------------------------

--
-- Table structure for table `blood_requests`
--

CREATE TABLE `blood_requests` (
  `id` int(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `blood_group` varchar(3) NOT NULL,
  `status` varchar(20) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_requests`
--

INSERT INTO `blood_requests` (`id`, `name`, `blood_group`, `status`, `quantity`, `date_added`) VALUES
(12, 'aman singh', 'O-', '', 2, '2025-05-10 06:31:49');

-- --------------------------------------------------------

--
-- Table structure for table `donors`
--

CREATE TABLE `donors` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `blood_group` varchar(3) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `hospital` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donors`
--

INSERT INTO `donors` (`id`, `name`, `blood_group`, `quantity`, `date_added`, `hospital`) VALUES
(7, 'Pranshu Singh', 'B+', 1, '2025-04-14 04:15:42', 'Dev Hospital'),
(8, 'Md Javed', 'B+', 3, '2025-04-14 04:16:26', 'Apollo Hospital'),
(9, 'Panchdev Maddheshiya', 'A+', 3, '2025-04-14 03:58:43', 'Dev Hospital'),
(10, 'Prashansha singh', 'A+', 4, '2025-04-14 04:08:42', 'AIIMS'),
(13, 'Shikha Maddhesiya', 'B+', 3, '2025-06-21 05:50:07', 'Dev Hospital');

-- --------------------------------------------------------

--
-- Table structure for table `donor_history`
--

CREATE TABLE `donor_history` (
  `id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `hospital_name` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `donation_date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donor_history`
--

INSERT INTO `donor_history` (`id`, `donor_id`, `hospital_name`, `quantity`, `donation_date`) VALUES
(10, 9, 'Dev Hospital', 1, '2025-03-26 18:23:32'),
(11, 9, 'AIIMS', 1, '2025-03-26 19:01:26'),
(12, 9, 'Apollo Hospital', 2, '2025-03-27 06:28:33'),
(13, 9, 'Dev Hospital', 3, '2025-04-14 03:58:43'),
(14, 10, 'Fortis Hospital', 3, '2025-04-14 04:08:29'),
(15, 10, 'AIIMS', 2, '2025-04-14 04:08:42'),
(16, 7, 'Dev Hospital', 3, '2025-04-14 04:15:42'),
(17, 8, 'Apollo Hospital', 3, '2025-04-14 04:16:26'),
(18, 13, 'Dev Hospital', 3, '2025-06-21 05:50:07');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `blood_group` varchar(10) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `profile_img` varchar(255) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `blood_group`, `contact`, `profile_img`, `role`) VALUES
(1, 'devji0408', '$2y$10$EZe5WBxSOh/p4mpd47FtlOiZcdNhhCA8jnFWysl/SQ2mxEXX9yQ7K', 'Panchdev Maddheshiya', '', '', '', 'admin'),
(7, 'Pranshu07', '$2y$10$EZe5WBxSOh/p4mpd47FtlOiZcdNhhCA8jnFWysl/SQ2mxEXX9yQ7K', 'Pranshu Singh', 'B+', '9457180256', 'images/1725948100496.jpg', 'user'),
(8, 'md_javed', '$2y$10$H0MlXyexQsS3H7t8Chrx0.lNnQeKbGAeP.zcez99yeouws9gFv2JG', 'Md Javed', 'B+', '9758791016', 'images/javed.jpg', 'user'),
(9, 'dev', '$2y$10$732O1okWyV30x682aqs60uCE5o7Rg9ZODQG8UD3cLd05IU1FAtmOC', 'Panchdev Maddheshiya', 'A+', '8874996980', 'images/1712384786080.jpg', 'user'),
(10, 'pr_01', '$2y$10$a5oUZwKxwtoiI2zs1POzIujOWa04gGS1ZuTtmRE75eQ/0FkphypzK', 'Prashansha singh', 'A+', '1234567890', 'images/1720681001771.jpg', 'user'),
(11, 'utkarsh123', '$2y$10$jwuuweWgTBtPCRl7SEbU4Ov1rowlWjrr/8QlYEkpnnuo/PKsIQa5e', 'utkarsh mishra', 'A+', '9555342280', NULL, 'user'),
(12, 'aman', '$2y$10$avJzYHR6yJoMca072DSX8.QZ8YeszauFvT9ClI2LFiKlXtEuIsdsK', 'aman singh', 'O-', '65657575775', NULL, 'user'),
(13, 'Kirandevika', '$2y$10$mdqfSVl0/7WZB8ghtopCbebuS8lxDragZepb3sLQMqqjWYI2OUlXa', 'Shikha Maddhesiya', 'B+', '9727696480', 'images/WhatsApp Image 2025-06-24 at 12.21.11_4bfacf44.jpg', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `approved_requests`
--
ALTER TABLE `approved_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blood_groups`
--
ALTER TABLE `blood_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blood_group` (`blood_group`);

--
-- Indexes for table `blood_requests`
--
ALTER TABLE `blood_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donors`
--
ALTER TABLE `donors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donor_history`
--
ALTER TABLE `donor_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donor_id` (`donor_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blood_groups`
--
ALTER TABLE `blood_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `blood_requests`
--
ALTER TABLE `blood_requests`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `donors`
--
ALTER TABLE `donors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `donor_history`
--
ALTER TABLE `donor_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `donor_history`
--
ALTER TABLE `donor_history`
  ADD CONSTRAINT `donor_history_ibfk_1` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
