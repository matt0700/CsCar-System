-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 04, 2024 at 02:24 PM
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
-- Database: `cscar_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `driver_info`
--

CREATE TABLE `driver_info` (
  `driver_id` int(3) NOT NULL,
  `driver_name` varchar(50) NOT NULL,
  `driver_cellno` varchar(11) NOT NULL,
  `trip` varchar(50) NOT NULL,
  `driver_status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `driver_info`
--

INSERT INTO `driver_info` (`driver_id`, `driver_name`, `driver_cellno`, `trip`, `driver_status`) VALUES
(1, 'DUCUSIN, Ernesto Jr.', '09159307738', 'None', 'Available'),
(2, 'FERNANDEZ, Eufracio B.', '09773670149', 'None', 'Available'),
(3, 'GACUAN, Jomar A.', '9773670149', 'None', 'Available'),
(4, 'LLARIN, Rommy L.', '09359299083', 'None', 'Available'),
(5, 'MEJIA, Joseph Anthony O.', '09150414451', 'None', 'Available'),
(6, 'PREDAS, Dionisio G.', '9359793347', 'None', 'Available'),
(7, 'RODRIGUEZ JR., Pablo O.', '09059256656', 'None', 'Available'),
(8, 'SEBASTIAN,Cesar', '09279271263', 'None', 'Available'),
(9, 'TOLENTINO, Wilfredo P.', '09163609118', 'None', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `ruv_table`
--

CREATE TABLE `ruv_table` (
  `ruvNO` int(10) NOT NULL,
  `pickup_point` text NOT NULL,
  `destination` text NOT NULL,
  `trip_date` date NOT NULL,
  `pref_time` varchar(10) NOT NULL,
  `no_passengers` int(5) NOT NULL,
  `eta_destination` varchar(10) NOT NULL,
  `req_official` varchar(40) NOT NULL,
  `name_passengers` text NOT NULL,
  `reason` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ruv_table`
--

INSERT INTO `ruv_table` (`ruvNO`, `pickup_point`, `destination`, `trip_date`, `pref_time`, `no_passengers`, `eta_destination`, `req_official`, `name_passengers`, `reason`) VALUES
(1, 'CSC-Main', 'Quiapo', '0000-00-00', '1034 am', 3, '1034 pm', 'Ryan', 'dada', 'dadada'),
(2, 'CSC-main', 'Alabang', '2024-06-29', '09:06', 21, '21:06', 'Ryan Cunanan', 'Arnoled', 'shabo'),
(3, 'CSC-main', 'Alabang', '2024-06-29', '09:06', 21, '21:06', 'Ryan Cunanan', 'Arnoled', 'shabo'),
(4, 'CSC-main', 'Alabang', '2024-06-29', '09:06', 21, '21:06', 'Ryan Cunanan', 'Arnoled', 'shabo'),
(5, 'CSC-main', 'Alabang', '2024-06-29', '09:06', 21, '21:06', 'Ryan Cunanan', 'Arnoled', 'shabo'),
(6, 'CSC-main', 'NEgros', '2024-08-02', '09:13', 18, '21:13', 'Ryan Cunanan', 'daaa', 'dadada');

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

CREATE TABLE `trips` (
  `trip_id` int(11) NOT NULL,
  `ruvNO` int(11) DEFAULT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `plate_no` varchar(10) DEFAULT NULL,
  `trip_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_data`
--

CREATE TABLE `vehicle_data` (
  `plate_no` varchar(10) NOT NULL,
  `model` varchar(4) NOT NULL,
  `type` varchar(10) NOT NULL,
  `make_series_type` text NOT NULL,
  `seater` int(5) NOT NULL,
  `mileage` int(5) NOT NULL,
  `fuel_consump` int(5) NOT NULL,
  `car_status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicle_data`
--

INSERT INTO `vehicle_data` (`plate_no`, `model`, `type`, `make_series_type`, `seater`, `mileage`, `fuel_consump`, `car_status`) VALUES
('SAA-9865', '2016', 'VAN', 'Toyota Hi-Ace GL- Grandia 2.5L\r\n', 12, 0, 0, ''),
('SAA-9866', '2016', 'VAN', 'Toyota Hi-Ace GL- Grandia 2.5L', 12, 0, 0, ''),
('SFY-477', '2006', 'SEDAN', 'Toyota Vios 1.3 E\r\n', 5, 0, 0, ''),
('SFY-488', '2006', 'SEDAN', 'Toyota Vios 1.3 E', 5, 0, 0, ''),
('SHZ-133', '2014', 'SEDAN', 'Toyota Corolla Altis 1.6E\r\n', 5, 0, 0, ''),
('SJH-967', '2010', 'SEDAN', 'Toyota Vios 1.3 E\r\n', 5, 0, 0, ''),
('SJH-977', '2010', 'SEDAN', 'Toyota Altis 1.6E M/T\r\n', 5, 0, 0, ''),
('SJP-285', '2009', 'SUV', 'Isuzu Crosswind XL', 7, 0, 0, ''),
('SJP-286', '2009', 'SUV', 'Isuzu Crosswind XL', 7, 0, 0, ''),
('U9-D041', '2021', 'BIG BUS', 'Asia Star Bus 6W 310HP\r\n', 70, 0, 0, ''),
('Z4T-867', '2023', 'VAN', 'Toyota Hi Ace van\r\n', 16, 0, 0, ''),
('Z5G-191', '2023', 'MPV', 'Toyota Avanza 2023 model', 7, 0, 0, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `driver_info`
--
ALTER TABLE `driver_info`
  ADD PRIMARY KEY (`driver_id`);

--
-- Indexes for table `ruv_table`
--
ALTER TABLE `ruv_table`
  ADD PRIMARY KEY (`ruvNO`);

--
-- Indexes for table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`trip_id`),
  ADD KEY `ruvNO` (`ruvNO`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `plate_no` (`plate_no`);

--
-- Indexes for table `vehicle_data`
--
ALTER TABLE `vehicle_data`
  ADD PRIMARY KEY (`plate_no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `driver_info`
--
ALTER TABLE `driver_info`
  MODIFY `driver_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `ruv_table`
--
ALTER TABLE `ruv_table`
  MODIFY `ruvNO` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `trip_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `trips`
--
ALTER TABLE `trips`
  ADD CONSTRAINT `trips_ibfk_1` FOREIGN KEY (`ruvNO`) REFERENCES `ruv_table` (`ruvNO`),
  ADD CONSTRAINT `trips_ibfk_2` FOREIGN KEY (`driver_id`) REFERENCES `driver_info` (`driver_id`),
  ADD CONSTRAINT `trips_ibfk_3` FOREIGN KEY (`plate_no`) REFERENCES `vehicle_data` (`plate_no`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
