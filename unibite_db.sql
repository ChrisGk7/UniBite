-- phpMyAdmin SQL Dump
-- version 5.2.1
-- Host: localhost
-- Generation Time: Aug 20, 2026 at 09:46 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `unibite_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `username` varchar(63) NOT NULL,
  `email` varchar(63) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cook`
--

CREATE TABLE `cook` (
  `username` varchar(63) NOT NULL,
  `email` varchar(63) NOT NULL,
  `street` text NOT NULL,
  `number` int(5) NOT NULL,
  `city` text NOT NULL,
  `postcode` int(11) NOT NULL,
  `mobile` varchar(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cook`
--

INSERT INTO `cook` (`username`, `email`, `street`, `number`, `city`, `postcode`, `mobile`) VALUES
('teststudent', 'up1234567@upnet.gr', 'Kanakari', 60, 'patra', 26211, '+306982082900'),
('up1234589@gmail.com', 'up1234589@gmail.com', 'korinthou', 10, 'patras', 26221, '6900001234');

-- --------------------------------------------------------

--
-- Table structure for table `dish`
--

CREATE TABLE `dish` (
  `id` int(30) NOT NULL,
  `cook` varchar(63) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `allergens` text NOT NULL,
  `photos_url` varchar(255) DEFAULT NULL,
  `pickup_location` varchar(255) NOT NULL,
  `pickup_time` datetime NOT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `portions` int(2) NOT NULL,
  `credits_per_portion` int(2) NOT NULL DEFAULT 1,
  `reg_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dish`
--

INSERT INTO `dish` (`id`, `cook`, `title`, `description`, `allergens`, `photos_url`, `pickup_location`, `pickup_time`, `latitude`, `longitude`, `portions`, `credits_per_portion`, `reg_date`) VALUES
(17, 'teststudent', 'Μακαρόνια με κιμά', 'Φρέσκα μακαρόνια με κιμά', 'Κρέας', 'uploads/makaronia me kima.jpeg', 'κανακαρι 60', '2026-08-20 15:23:00', 38.2499984, 21.7365360, 4, 1, '2026-08-17 15:23:45'),
(18, 'teststudent', 'Πίτσα', 'Φρέσκια πίτσα', 'Τυρί', 'uploads/pizza.jpeg', 'κανακαρι 80', '2026-08-25 15:26:00', 38.2475260, 21.7367080, 3, 1, '2026-08-17 15:26:20'),
(19, 'teststudent', 'Λαζάνια', 'Φρέσκα λαζάνια', 'γαλα, κιμας', 'uploads/lazania .jpeg', 'κανακαρι 60', '2026-08-26 16:08:00', 38.2464475, 21.7340469, 4, 1, '2026-08-17 16:08:28'),
(20, 'teststudent', 'μακαρονια με κιμα', 'φρεσκα μακαρονια', 'γαλα, κιμας', 'uploads/makaronia.png', 'κανακαρι 60', '2026-08-27 18:13:00', 38.2378188, 21.7319870, 4, 1, '2026-08-17 18:13:19'),
(21, 'teststudent', 'Cinnamon Rolls', 'Freshly made Cinnamon rolls', 'cream', 'uploads/cinnamon_rolls.jpeg', 'κανακαρι 60', '2026-08-21 18:27:00', 38.2456386, 21.7361931, 7, 1, '2026-08-17 18:27:28'),
(22, 'up1234589@gmail.com', 'παστιτσαδα', 'φρέσκο φαγητό', 'κρέας', 'uploads/images_pastitsada.png', 'κανακαρι 40', '2026-08-27 16:03:00', 38.2491222, 21.7409134, 5, 1, '2026-08-19 16:03:57');

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `id` int(30) NOT NULL,
  `stu_username` varchar(63) NOT NULL,
  `cook_username` varchar(63) NOT NULL,
  `dish_id` int(30) NOT NULL,
  `portions` int(2) NOT NULL,
  `credit_cost` int(2) NOT NULL,
  `status` enum('pending','declined','accepted') NOT NULL DEFAULT 'pending',
  `pickup_status` enum('awaiting_pickup','picked_up','no_show') DEFAULT NULL,
  `rating` enum('1','2','3','4','5') DEFAULT NULL,
  `request_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `reply_datetime` datetime DEFAULT NULL,
  `pickup_datetime` datetime DEFAULT NULL,
  `rated_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`id`, `stu_username`, `cook_username`, `dish_id`, `portions`, `credit_cost`, `status`, `pickup_status`, `rating`, `request_datetime`, `reply_datetime`, `pickup_datetime`, `rated_datetime`) VALUES
(3, 'teststudent', 'teststudent', 21, 1, 1, 'pending', NULL, NULL, '2026-08-17 18:33:56', NULL, NULL, NULL),
(4, 'up1234589@gmail.com', 'teststudent', 19, 1, 1, 'pending', NULL, NULL, '2026-08-19 16:06:06', NULL, NULL, NULL),
(5, 'up1234589@gmail.com', 'teststudent', 17, 1, 1, 'pending', NULL, NULL, '2026-08-19 23:02:58', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `username` varchar(63) NOT NULL,
  `email` varchar(63) NOT NULL,
  `credits` int(5) NOT NULL DEFAULT 5,
  `street` text NOT NULL,
  `number` int(5) NOT NULL,
  `city` text NOT NULL,
  `postcode` int(11) NOT NULL,
  `mobile` varchar(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`username`, `email`, `credits`, `street`, `number`, `city`, `postcode`, `mobile`) VALUES
('teststudent', 'up1234567@upnet.gr', 5, 'Kanakari', 60, 'patra', 26211, '+306982082900'),
('up1234589@gmail.com', 'up1234589@gmail.com', 5, 'korinthou', 10, 'patras', 26221, '6900001234');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `username` varchar(63) NOT NULL,
  `email` varchar(63) NOT NULL,
  `pass` varchar(255) DEFAULT NULL,
  `name` varchar(63) DEFAULT NULL,
  `reg_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `email`, `pass`, `name`, `reg_date`) VALUES
('teststudent', 'up1234567@upnet.gr', '$2y$10$FXuvhCDuIdGSI0TAlxZrcuaxYTs1V1/lsACaAQJ8EeqRphO.98o5O', 'Test Student', '2026-08-17 12:27:36'),
('up1234589@gmail.com', 'up1234589@gmail.com', '$2y$10$gS2kMSLE1bYcA57Be3UQLOE8byXzAoaZnxJ.UvlpaEd7SmGsskdKW', 'Alex hat', '2026-08-19 16:00:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `cook`
--
ALTER TABLE `cook`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `dish`
--
ALTER TABLE `dish`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `cook` (`cook`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stu_username` (`stu_username`),
  ADD KEY `cook_username` (`cook_username`),
  ADD KEY `dish_id` (`dish_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dish`
--
ALTER TABLE `dish`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`username`) REFERENCES `user` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `admin_ibfk_2` FOREIGN KEY (`email`) REFERENCES `user` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cook`
--
ALTER TABLE `cook`
  ADD CONSTRAINT `cook_ibfk_1` FOREIGN KEY (`email`) REFERENCES `user` (`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cook_ibfk_2` FOREIGN KEY (`username`) REFERENCES `user` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dish`
--
ALTER TABLE `dish`
  ADD CONSTRAINT `dish_ibfk_1` FOREIGN KEY (`cook`) REFERENCES `cook` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `request_ibfk_1` FOREIGN KEY (`stu_username`) REFERENCES `student` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `request_ibfk_2` FOREIGN KEY (`cook_username`) REFERENCES `cook` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `request_ibfk_3` FOREIGN KEY (`dish_id`) REFERENCES `dish` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;