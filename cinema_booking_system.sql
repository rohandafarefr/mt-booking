-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2024 at 12:14 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cinema_booking_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `movie_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `VIP` decimal(10,2) DEFAULT 0.00,
  `PREMIUM` decimal(10,2) DEFAULT 0.00,
  `GOLD` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`movie_id`, `title`, `description`, `release_date`, `image`, `VIP`, `PREMIUM`, `GOLD`) VALUES
(2, 'Oppenheimer', 'Based on the 2005 biography American Prometheus by Kai Bird and Martin J. Sherwin, the film chronicles the career of Oppenheimer, with the story predominantly focusing on his studies, his direction of the Manhattan Project during World War II, and his eventual fall from grace due to his 1954 security hearing.', '2023-07-21', 'http://localhost/mt-booking/uploads/oppenheimer.jpg', '500.00', '400.00', '300.00');

-- --------------------------------------------------------

--
-- Table structure for table `seating`
--

CREATE TABLE `seating` (
  `id` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `seat_number` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seating`
--

INSERT INTO `seating` (`id`, `category`, `seat_number`) VALUES
(4, 'VIP', 'A1'),
(5, 'VIP', 'A2'),
(6, 'VIP', 'A3'),
(7, 'VIP', 'A4'),
(8, 'VIP', 'A5'),
(9, 'VIP', 'A6'),
(10, 'VIP', 'A7'),
(11, 'VIP', 'A8'),
(12, 'VIP', 'B1'),
(13, 'VIP', 'B2'),
(14, 'VIP', 'B3'),
(15, 'VIP', 'B4'),
(16, 'VIP', 'B5'),
(17, 'VIP', 'B6'),
(18, 'VIP', 'B7'),
(19, 'VIP', 'B8'),
(20, 'VIP', 'C1'),
(21, 'VIP', 'C2'),
(22, 'VIP', 'C3'),
(23, 'VIP', 'C4'),
(24, 'VIP', 'C5'),
(25, 'VIP', 'C6'),
(26, 'VIP', 'C7'),
(27, 'VIP', 'C8'),
(28, 'PREMIUM', 'D1'),
(29, 'PREMIUM', 'D2'),
(30, 'PREMIUM', 'D3'),
(31, 'PREMIUM', 'D4'),
(32, 'PREMIUM', 'D5'),
(33, 'PREMIUM', 'D6'),
(34, 'PREMIUM', 'D7'),
(35, 'PREMIUM', 'D8'),
(36, 'PREMIUM', 'D9'),
(37, 'PREMIUM', 'D10'),
(38, 'PREMIUM', 'D11'),
(39, 'PREMIUM', 'D12'),
(40, 'PREMIUM', 'D13'),
(41, 'PREMIUM', 'D14'),
(42, 'PREMIUM', 'D15'),
(43, 'PREMIUM', 'D16'),
(44, 'PREMIUM', 'D17'),
(45, 'PREMIUM', 'D18'),
(46, 'PREMIUM', 'E1'),
(47, 'PREMIUM', 'E2'),
(48, 'PREMIUM', 'E3'),
(49, 'PREMIUM', 'E4'),
(50, 'PREMIUM', 'E5'),
(51, 'PREMIUM', 'E6'),
(52, 'PREMIUM', 'E7'),
(53, 'PREMIUM', 'E8'),
(54, 'PREMIUM', 'E9'),
(55, 'PREMIUM', 'E10'),
(56, 'PREMIUM', 'E11'),
(57, 'PREMIUM', 'E12'),
(58, 'PREMIUM', 'E13'),
(59, 'PREMIUM', 'E14'),
(60, 'PREMIUM', 'E15'),
(61, 'PREMIUM', 'E16'),
(62, 'PREMIUM', 'E17'),
(63, 'PREMIUM', 'E18'),
(64, 'GOLD', 'H1'),
(65, 'GOLD', 'H2'),
(66, 'PREMIUM', 'F1'),
(67, 'PREMIUM', 'F2'),
(68, 'PREMIUM', 'F3'),
(69, 'PREMIUM', 'F4'),
(70, 'PREMIUM', 'F5'),
(71, 'PREMIUM', 'F6'),
(72, 'PREMIUM', 'F7'),
(73, 'PREMIUM', 'F8'),
(74, 'PREMIUM', 'F9'),
(75, 'PREMIUM', 'F10'),
(76, 'PREMIUM', 'F11'),
(77, 'PREMIUM', 'F12'),
(78, 'PREMIUM', 'F13'),
(79, 'PREMIUM', 'F14'),
(80, 'PREMIUM', 'F15'),
(81, 'PREMIUM', 'F16'),
(82, 'PREMIUM', 'F17'),
(83, 'PREMIUM', 'F18'),
(86, 'GOLD', 'G1'),
(87, 'GOLD', 'G2'),
(88, 'GOLD', 'G3'),
(89, 'GOLD', 'G4'),
(90, 'GOLD', 'G5'),
(91, 'GOLD', 'G6'),
(92, 'GOLD', 'G7'),
(93, 'GOLD', 'G8'),
(94, 'GOLD', 'G9'),
(95, 'GOLD', 'G10'),
(96, 'GOLD', 'G11'),
(97, 'GOLD', 'G12'),
(98, 'GOLD', 'G13'),
(99, 'GOLD', 'G14'),
(100, 'GOLD', 'G15'),
(101, 'GOLD', 'G16'),
(102, 'GOLD', 'G17'),
(103, 'GOLD', 'G18'),
(104, 'GOLD', 'G19'),
(105, 'GOLD', 'G20');

-- --------------------------------------------------------

--
-- Table structure for table `show_timings`
--

CREATE TABLE `show_timings` (
  `timing_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `show_timings`
--

INSERT INTO `show_timings` (`timing_id`, `movie_id`, `start_time`, `end_time`) VALUES
(6, 2, '09:00:00', '12:00:00'),
(7, 2, '12:00:00', '15:00:00'),
(8, 2, '15:00:00', '18:00:00'),
(9, 2, '18:00:00', '21:00:00'),
(10, 2, '21:00:00', '00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `ticket_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `timing_id` int(11) NOT NULL,
  `seat_number` varchar(10) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`ticket_id`, `movie_id`, `timing_id`, `seat_number`, `booking_id`, `amount`) VALUES
(68, 2, 6, 'A1', 7317, '500.00'),
(69, 2, 6, 'A2', 7317, '500.00'),
(70, 2, 8, 'A6', 1378, '400.00'),
(71, 2, 8, 'H7', 1378, '400.00'),
(72, 2, 8, 'A1', 9712, '500.00'),
(73, 2, 8, 'A2', 9712, '500.00'),
(74, 2, 8, 'A3', 9712, '500.00'),
(75, 2, 8, 'A4', 9712, '500.00'),
(76, 2, 8, 'A5', 9712, '500.00'),
(77, 2, 8, 'A6', 9712, '400.00'),
(78, 2, 8, 'A7', 9712, '400.00'),
(79, 2, 8, 'A8', 9712, '400.00'),
(80, 2, 8, 'H7', 9712, '400.00'),
(81, 2, 7, 'A1', 4709, '500.00'),
(82, 2, 7, 'A2', 4709, '500.00'),
(83, 2, 7, 'A3', 4709, '500.00'),
(84, 2, 7, 'A4', 4709, '500.00'),
(85, 2, 7, 'D1', 4709, '500.00'),
(86, 2, 7, 'G1', 4709, '500.00'),
(87, 2, 7, 'D4', 6313, '500.00'),
(88, 2, 7, 'D5', 6313, '500.00'),
(89, 2, 7, 'B1', 9262, '500.00'),
(90, 2, 7, 'B2', 9262, '500.00'),
(91, 2, 7, 'B5', 9262, '500.00'),
(92, 2, 7, 'B6', 9262, '400.00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`movie_id`);

--
-- Indexes for table `seating`
--
ALTER TABLE `seating`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `show_timings`
--
ALTER TABLE `show_timings`
  ADD PRIMARY KEY (`timing_id`),
  ADD KEY `show_timings_ibfk_1` (`movie_id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ticket_id`),
  ADD KEY `movie_id` (`movie_id`),
  ADD KEY `timing_id` (`timing_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `movie_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `seating`
--
ALTER TABLE `seating`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `show_timings`
--
ALTER TABLE `show_timings`
  MODIFY `timing_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `show_timings`
--
ALTER TABLE `show_timings`
  ADD CONSTRAINT `new_constraint_name` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `show_timings_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE;

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`),
  ADD CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`timing_id`) REFERENCES `show_timings` (`timing_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
