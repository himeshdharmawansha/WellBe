-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 28, 2025 at 04:04 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wellbe_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrative_staff`
--

DROP TABLE IF EXISTS `administrative_staff`;
CREATE TABLE IF NOT EXISTS `administrative_staff` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nic` varchar(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `contact` int NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `address` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nic` (`nic`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `administrative_staff`
--

INSERT INTO `administrative_staff` (`id`, `nic`, `password`, `first_name`, `last_name`, `contact`, `email`, `address`, `role`) VALUES
(1, '200264401515a', '$2y$10$142TKWR5c7P.Dxw5W34QXOsmCPvVD3st1ZAPNNQ9jLp6X3u3KdxTS', 'Raveesha', 'Samarasekera', 710906717, 'raveeshagihanI@gmail.com', '45/4, Temple Road, Negombo', 'Create, edit, delete staff profiles\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

DROP TABLE IF EXISTS `appointment`;
CREATE TABLE IF NOT EXISTS `appointment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `appointment_id` int NOT NULL,
  `doctor_id` int NOT NULL,
  `patient_id` int NOT NULL,
  `date` int NOT NULL,
  `payment_fee` int NOT NULL,
  `payment_status` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `patient_type` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `scheduled` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `appointment_id` (`appointment_id`,`doctor_id`,`patient_id`,`date`),
  KEY `doctor_id` (`doctor_id`),
  KEY `patient_id` (`patient_id`),
  KEY `appointment_ibfk_3` (`date`)
) ENGINE=InnoDB AUTO_INCREMENT=163 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`id`, `appointment_id`, `doctor_id`, `patient_id`, `date`, `payment_fee`, `payment_status`, `state`, `patient_type`, `scheduled`) VALUES
(63, 1, 2, 22, 118, 1500, 'Paid', 'PRESENT', 'NEW', 'Scheduled'),
(111, 1, 2, 16, 166, 1500, NULL, 'NOT PRESENT', 'RETURNING', 'SCHEDULED'),
(112, 2, 2, 16, 166, 1500, NULL, 'NOT PRESENT', 'RETURNING', 'SCHEDULED'),
(113, 3, 2, 16, 166, 1500, NULL, 'NOT PRESENT', 'RETURNING', 'SCHEDULED'),
(114, 4, 2, 16, 166, 1500, NULL, 'NOT PRESENT', 'RETURNING', 'SCHEDULED'),
(115, 5, 2, 16, 166, 1500, NULL, 'NOT PRESENT', 'RETURNING', 'SCHEDULED'),
(116, 6, 2, 16, 166, 1500, 'Paid', 'NOT PRESENT', 'RETURNING', 'SCHEDULED'),
(118, 1, 4, 33, 171, 1500, 'Paid', 'NOT PRESENT', 'NEW', 'Rescheduled'),
(119, 2, 4, 33, 171, 1500, 'Paid', 'NOT PRESENT', 'NEW', 'Rescheduled'),
(122, 10, 4, 34, 168, 1500, NULL, 'NOT PRESENT', 'RETURNING', 'Rescheduled'),
(123, 11, 4, 34, 168, 1500, NULL, 'NOT PRESENT', 'RETURNING', 'Rescheduled'),
(124, 1, 4, 33, 170, 1500, 'Paid', 'NOT PRESENT', 'RETURNING', 'Rescheduled'),
(139, 1, 5, 36, 173, 1500, 'Not Paid', 'NOT PRESENT', 'NEW', 'Scheduled'),
(140, 1, 5, 36, 172, 1500, 'Not Paid', 'NOT PRESENT', 'NEW', 'Scheduled'),
(145, 1, 8, 36, 175, 1500, NULL, 'NOT PRESENT', 'NEW', 'SCHEDULED'),
(146, 1, 6, 36, 182, 1500, NULL, 'NOT PRESENT', 'NEW', 'SCHEDULED'),
(148, 12, 8, 36, 168, 1500, 'Paid', 'PRESENT', 'RETURNING', 'SCHEDULED'),
(149, 13, 8, 36, 168, 1500, 'Paid', 'PRESENT', 'RETURNING', 'SCHEDULED'),
(150, 14, 8, 36, 168, 1500, 'Paid', 'DONE', 'RETURNING', 'SCHEDULED'),
(151, 1, 8, 36, 182, 1500, NULL, 'NOT PRESENT', 'RETURNING', 'SCHEDULED'),
(152, 1, 8, 36, 177, 1500, NULL, 'NOT PRESENT', 'RETURNING', 'SCHEDULED'),
(154, 3, 8, 40, 182, 1500, NULL, 'NOT PRESENT', 'NEW', 'SCHEDULED'),
(155, 5, 4, 40, 170, 1500, 'Paid', 'NOT PRESENT', 'NEW', 'Rescheduled'),
(156, 1, 5, 40, 171, 1500, 'Paid', 'NOT PRESENT', 'NEW', 'SCHEDULED'),
(157, 1, 7, 41, 171, 1500, 'Paid', 'PRESENT', 'NEW', 'SCHEDULED'),
(158, 2, 5, 41, 171, 1500, 'Not Paid', 'NOT PRESENT', 'NEW', 'Scheduled'),
(159, 6, 4, 41, 170, 1500, 'Not Paid', 'NOT PRESENT', 'NEW', 'Rescheduled'),
(160, 1, 8, 36, 169, 1500, 'Not Paid', 'NOT PRESENT', 'RETURNING', 'SCHEDULED'),
(161, 2, 8, 36, 169, 1500, 'Not Paid', 'NOT PRESENT', 'RETURNING', 'SCHEDULED'),
(162, 3, 8, 43, 169, 1500, 'Paid', 'DONE', 'NEW', 'SCHEDULED');

-- --------------------------------------------------------

--
-- Table structure for table `appointment_channel_details`
--

DROP TABLE IF EXISTS `appointment_channel_details`;
CREATE TABLE IF NOT EXISTS `appointment_channel_details` (
  `id` int NOT NULL,
  `doctor_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `specialization` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `appointment_id` int NOT NULL,
  `appointment_number` int NOT NULL,
  `appointment_fees` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `doctor_notes` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`appointment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointment_patient_details`
--

DROP TABLE IF EXISTS `appointment_patient_details`;
CREATE TABLE IF NOT EXISTS `appointment_patient_details` (
  `patient_fname` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL,
  `nationality` enum('Sri Lankan','Foreign') COLLATE utf8mb4_general_ci NOT NULL,
  `NIC` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL,
  `contact_no` int NOT NULL,
  `emergency_contact_no` int NOT NULL,
  `save_permission` enum('YES','NO') COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_patient_details`
--

INSERT INTO `appointment_patient_details` (`patient_fname`, `nationality`, `NIC`, `email`, `contact_no`, `emergency_contact_no`, `save_permission`) VALUES
('Mumu', 'Foreign', '200260502667', 'mumu@gmail.com', 771333370, 77423723, 'YES'),
('Bunny', 'Sri Lankan', '200145968732', 'bunny@gmail.com', 771458974, 776321485, 'YES'),
('maze', 'Foreign', '8965412368', 'mazi@gmail.com', 774526398, 223569874, 'YES'),
('koko', 'Foreign', '456987123556', 'koko@gmail.com', 96587412, 965478213, 'YES');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

DROP TABLE IF EXISTS `doctor`;
CREATE TABLE IF NOT EXISTS `doctor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nic` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `dob` date NOT NULL,
  `age` int NOT NULL,
  `gender` char(1) COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `contact` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `fees` int NOT NULL DEFAULT '1500',
  `emergency_contact` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `emergency_contact_relationship` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `medical_license_no` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `specialization` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `experience` int NOT NULL,
  `qualifications` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `medical_school` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `account_state` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Active',
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nic` (`nic`),
  KEY `doctor_profile_fk` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`id`, `nic`, `password`, `first_name`, `last_name`, `dob`, `age`, `gender`, `address`, `email`, `contact`, `fees`, `emergency_contact`, `emergency_contact_relationship`, `medical_license_no`, `specialization`, `experience`, `qualifications`, `medical_school`, `account_state`, `user_id`) VALUES
(1, '200232401699d', '$2y$10$2GUM2jL/vOwsu4HWjEYCWuHIAY.ooCZsHGrHa6Eu4V6f8xcj/Wzvi', 'Saduni', 'Perera', '1995-11-15', 29, 'm', 'Pelmadulla,Ratnapura', 'sadun@gmail.com', '0725555555', 0, '0725555555', 'Brother', '500MBBS', 'Cardiology', 3, 'MBBS', 'UOK', 'Deleted', 0),
(2, '200260502668d', '$2y$10$2GUM2jL/vOwsu4HWjEYCWuHIAY.ooCZsHGrHa6Eu4V6f8xcj/Wzvi', 'Ajitha', 'Nipun', '1990-11-19', 34, 'M', '123,Kinf,Kandy', 'Ajith@gmail.com', '0778965412', 0, '0773698521', 'Wife', 'MD2210', 'Cardiology', 5, 'vbesy14dnehdb2bd3eb2', 'UOC', 'Active', 1),
(3, '200260502669d', '12345', 'Vijitha', 'Kamal', '1954-08-20', 70, 'M', '234, Kandy Road, Jaffna', 'Vijitha@gmail.com', '0778965214', 1500, '0778932146', 'Son', 'MD44903', 'Neurology', 20, 'vrdh2neq xhjqwbfd3ewqs', 'UOM', 'Active', 2),
(4, '199060502667d', '$2y$10$.lZ0rxdQ54tfWEEP.64Xru31tOHFnvHxt7TUWkIkRoKk5aOmpPYfm', 'Manoj', 'Supun', '1990-02-12', 35, 'M', '123,Colomo', 'manoj@gmail.com', '0771258963', 1500, '0117845693', 'Wife', '1234', 'Dentist', 8, 'MBBS', 'UOC', 'Active', 2147483647),
(5, '198534567889d', '$2y$10$UrXFZwuct8BraD7FKR9SJe6hFr47Gk9.RI2y.xrPvmZtQxZtnf1F.', 'Bhagya', 'Hettiarachchi', '1985-02-14', 40, 'F', '101, Kindom Road, Kaluthara', 'Bhagya@gmail.com', '0778965412', 1500, '0778541236', 'Husband', '123654', 'Eye Surgeon', 6, 'MBBS', 'UOM', 'Active', 2147483647),
(6, '197005026997d', '$2y$10$gphWxc27Lo19YGbiQPu11.0Ro0NHlYFH86R9M97efUi0af2C7hg8O', 'Sarath', 'Munasinghe', '1970-02-05', 55, 'M', '1/2/6, Paliya Road, Wattala', 'Sarath@gmail.com', '0778965412', 1500, '0772459631', 'Wife', '145697', 'Gastroenterologist', 5, 'MBBS', 'UK', 'Active', 2147483647),
(7, '908637456Vd', '$2y$10$Io/qgLFi40sWb.0XHGKKweCkBEEfuLpBJoFBp6QcV4EJ8SIF/qO8S', 'Charith', 'Fonseka', '1990-02-01', 35, 'M', '34, Nokewood, Kandana', 'Charith@gmail.com', '0778964125', 1500, '0778962314', 'Daughter', '78541', 'Dermatology', 4, 'MBBS', 'UOJ', 'Active', 908637456),
(8, '200232401698d', '$2y$10$iVcAVdovD0cQY9TSOQJVb.wp2c5m2GR65e7QEA..mF8jiOnVguXpC', 'Saliya', 'Pathirana', '1970-03-06', 55, 'M', '31/2, Kensington Garden, Bambalapitiya', 'Saliya123@gmail.com', '0778451296', 1500, '0115236478', 'Son', '458963', 'Oncologist', 8, 'MBBS', 'UOM', 'Active', 2147483647);

-- --------------------------------------------------------

--
-- Table structure for table `lab_technician`
--

DROP TABLE IF EXISTS `lab_technician`;
CREATE TABLE IF NOT EXISTS `lab_technician` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nic` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `dob` date NOT NULL,
  `age` int NOT NULL,
  `gender` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `contact` int NOT NULL,
  `emergency_contact_no` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `medical_license_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `specialization` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `experience` int NOT NULL,
  `qualifications` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prev_employment_history` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `account_state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Active',
  `user_id` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nic` (`nic`),
  KEY `lab_tech_profile_fk` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_technician`
--

INSERT INTO `lab_technician` (`id`, `nic`, `password`, `first_name`, `last_name`, `dob`, `age`, `gender`, `address`, `email`, `contact`, `emergency_contact_no`, `medical_license_no`, `specialization`, `experience`, `qualifications`, `prev_employment_history`, `account_state`, `user_id`) VALUES
(2, '197243552233l', '$2y$10$nD63QQx83pU2xAvAbAw0e.C97ZtZF7FZ6ABH91Fi672xY2f2YpzIu', 'Hishan', 'Mathu', '2014-11-30', 53, 'M', 'example', 'hishan@example.com', 756598755, '', 'Phs23ww', 'example', 8, 'example', 'example', '', '19724355223l'),
(3, '200205702050l', '$2y$10$MTs/P0Zv3M82JLveZxGvVO1tQ0shMsMsFDS8wwBbtHQ6J/5cGgeRa', 'Ben', 'Ken', '2002-01-14', 23, 'M', '123, Kandy Road, Kandy', 'ben@gmail.com', 778965412, '0778965412', '1234', 'Blood', 8, 'MBBS', 'None', 'Active', '200260502998l');

-- --------------------------------------------------------

--
-- Table structure for table `medication_requests`
--

DROP TABLE IF EXISTS `medication_requests`;
CREATE TABLE IF NOT EXISTS `medication_requests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `doctor_id` int NOT NULL,
  `patient_id` int NOT NULL,
  `date` int NOT NULL,
  `time` time NOT NULL,
  `remark` text COLLATE utf8mb4_general_ci,
  `state` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `diagnosis` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `file_name` text COLLATE utf8mb4_general_ci NOT NULL,
  `appointment_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `state` (`state`),
  KEY `date` (`date`),
  KEY `doctor_id` (`doctor_id`),
  KEY `patient_id` (`patient_id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medication_requests`
--

INSERT INTO `medication_requests` (`id`, `doctor_id`, `patient_id`, `date`, `time`, `remark`, `state`, `diagnosis`, `file_name`, `appointment_id`) VALUES
(28, 2, 22, 118, '00:00:00', '', 'Done', 'Arthritis', '', 0),
(29, 1, 22, 118, '00:00:00', NULL, 'pending', 'Fever', '', 0),
(30, 2, 16, 118, '00:00:00', 'ihfyir', 'pending', 'Pressure', '', 0),
(31, 4, 33, 166, '10:39:40', 'Headache due to studies', 'new', 'Migraine', '166_4_1_33.png', 0),
(32, 4, 33, 166, '10:42:53', 'High Gastritis', 'new', 'Ulcer', '166_4_1_33.png', 0),
(33, 4, 33, 166, '11:35:55', 'Cavity worse', 'new', 'Cavity', '166_4_1_33.png', 0),
(34, 4, 33, 166, '11:40:08', '', 'new', 'gas', '166_4_1_33.png', 0),
(35, 4, 34, 166, '12:58:21', 'none', 'new', 'gas', '166_4_1_34.png', 0),
(36, 8, 36, 168, '15:22:36', 'Ulcer due to high gastritis', 'completed', 'Ulcer', '168_8_12_36.png', 0),
(37, 8, 36, 168, '17:59:50', 'Headache', 'new', 'Migraine', '168_8_14_36.png', 0),
(38, 8, 43, 169, '13:53:16', 'none', 'completed', 'Gas', '169_8_3_43.png', 3);

-- --------------------------------------------------------

--
-- Table structure for table `medication_request_details`
--

DROP TABLE IF EXISTS `medication_request_details`;
CREATE TABLE IF NOT EXISTS `medication_request_details` (
  `req_id` int NOT NULL,
  `medication_name` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `dosage` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `taken_time` text COLLATE utf8mb4_general_ci NOT NULL,
  `substitution` tinyint(1) NOT NULL,
  `id` int NOT NULL AUTO_INCREMENT,
  `state` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`),
  KEY `id` (`req_id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medication_request_details`
--

INSERT INTO `medication_request_details` (`req_id`, `medication_name`, `dosage`, `taken_time`, `substitution`, `id`, `state`) VALUES
(28, 'ghh', '22', '1 1 1 1', 1, 28, 'pending'),
(28, 'dsdsd', '11', '2 2 2 1', 1, 29, 'pending'),
(29, 'Penadol', '500', '2 2 2 2', 1, 30, 'pending'),
(29, 'aaaa', '100', '1 1 1 1', 0, 31, 'pending'),
(30, 'poison', '100mg', '1 1 1 2', 0, 32, 'pending'),
(30, 'Food', '500', '1 2 3 4', 1, 33, 'pending'),
(31, 'Paracetamol', '10', '1 1 1 1', 1, 34, 'pending'),
(31, 'Cipro', '100', '1 1 1 1', 1, 35, 'pending'),
(31, 'Azithromycin', '10', '1 1 1 1', 1, 36, 'pending'),
(31, 'Lantus', '11', '2 2 2 1', 0, 37, 'pending'),
(35, 'Cipro', '20', '2 2 2 2', 1, 38, 'pending'),
(36, 'Plavix', '100', '1 1 1 1', 1, 39, 'given'),
(36, 'Ciprofloxacin', '20', '2   ', 0, 40, 'given'),
(37, 'Amoxicillin', '10', '1 1 1 1', 1, 41, 'pending'),
(37, 'Bayer', '10', '1  1 ', 0, 42, 'pending'),
(37, 'Advil', '12', '2 1 2 ', 0, 43, 'pending'),
(38, 'Cipro', '100', '2 2 1 0', 1, 44, 'given'),
(38, 'Metformin', '200', '1 2 1 0', 1, 45, 'notavailable');

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

DROP TABLE IF EXISTS `medicines`;
CREATE TABLE IF NOT EXISTS `medicines` (
  `medicine_id` int NOT NULL AUTO_INCREMENT,
  `generic_name` varchar(255) NOT NULL,
  `brand_name` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `quantity_in_stock` int DEFAULT '0',
  `unit` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`medicine_id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`medicine_id`, `generic_name`, `brand_name`, `category`, `price`, `expiry_date`, `quantity_in_stock`, `unit`) VALUES
(1, 'Paracetamol', 'Tylenol', 'Pain Relief', 800.00, '2026-12-31', 99, 'strips'),
(2, 'Ibuprofen', 'Advil', 'Pain Relief', 950.00, '2026-10-24', 0, 'tablets'),
(3, 'Aspirin', 'Bayer', 'Pain Relief', 650.00, '2026-06-15', 80, 'tablets'),
(4, 'Amoxicillin', 'Amoxil', 'Antibiotic', 1800.00, '2027-01-10', 200, 'capsules'),
(5, 'Ciprofloxacin', 'Cipro', 'Antibiotic', 2300.00, '2026-09-05', 120, 'tablets'),
(6, 'Azithromycin', 'Zithromax', 'Antibiotic', 2500.00, '2025-12-22', 90, 'capsules'),
(7, 'Metformin', 'Glucophage', 'Diabetes', 2000.00, '2027-03-18', 110, 'tablets'),
(8, 'Insulin', 'Lantus', 'Diabetes', 8500.00, '2022-07-13', 19, 'vials'),
(9, 'Amlodipine', 'Norvasc', 'Blood Pressure', 1600.00, '2026-04-25', 130, 'tablets'),
(10, 'Losartan', 'Cozaar', 'Blood Pressure', 1700.00, '2027-08-12', 140, 'tablets'),
(11, 'Simvastatin', 'Zocor', 'Cholesterol', 2000.00, '2022-11-01', 90, 'tablets'),
(12, 'Atorvastatin', 'Lipitor', 'Cholesterol', 2800.00, '2025-09-14', 100, 'tablets'),
(13, 'Cetirizine', 'Zyrtec', 'Allergy', 1200.00, '2027-05-06', 120, 'tablets'),
(14, 'Loratadine', 'Claritin', 'Allergy', 1400.00, '2026-12-11', 140, 'tablets'),
(15, 'Omeprazole', 'Prilosec', 'Acid Reflux', 1800.00, '2026-10-15', 110, 'capsules'),
(16, 'Ranitidine', 'Zantac', 'Acid Reflux', 1500.00, '2027-02-20', 95, 'tablets'),
(17, 'Salbutamol', 'Ventolin', 'Respiratory', 2000.00, '2025-11-05', 160, 'inhalers'),
(18, 'Budesonide', 'Pulmicort', 'Respiratory', 2500.00, '2026-07-28', 85, 'inhalers'),
(19, 'Dextromethorphan', 'Robitussin', 'Cough & Cold', 900.00, '2026-03-21', 140, 'syrup bottles'),
(20, 'Guaifenesin', 'Mucinex', 'Cough & Cold', 1000.00, '2025-08-14', 150, 'syrup bottles'),
(21, 'Doxycycline', 'Doryx', 'Antibiotic', 2400.00, '2026-09-30', 100, 'capsules'),
(22, 'Prednisone', 'Deltasone', 'Steroid', 1900.00, '2027-01-09', 130, 'tablets'),
(23, 'Hydroxychloroquine', 'Plaquenil', 'Anti-Malarial', 3200.00, '2026-06-22', 75, 'tablets'),
(24, 'Ivermectin', 'Stromectol', 'Anti-Parasitic', 2500.00, '2025-12-17', 85, 'tablets'),
(25, 'Warfarin', 'Coumadin', 'Blood Thinner', 2900.00, '2026-04-10', 95, 'tablets'),
(26, 'Clopidogrel', 'Plavix', 'Blood Thinner', 3100.00, '2027-05-12', 120, 'tablets'),
(27, 'Diazepam', 'Valium', 'Anxiety', 1850.00, '2026-08-29', 140, 'tablets'),
(28, 'Alprazolam', 'Xanax', 'Anxiety', 2100.00, '2025-10-30', 100, 'tablets'),
(29, 'Zolpidem', 'Ambien', 'Insomnia', 2700.00, '2026-12-05', 80, 'tablets'),
(30, 'Levothyroxine', 'Synthroid', 'Thyroid', 1600.00, '2027-03-15', 110, 'tablets');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `sender` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `receiver` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `files` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `date` datetime NOT NULL,
  `seen` int NOT NULL DEFAULT '0',
  `received` int NOT NULL DEFAULT '0',
  `deleted_sender` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_receiver` tinyint(1) NOT NULL DEFAULT '0',
  `edited` tinyint(1) NOT NULL DEFAULT '0',
  `type` enum('text','photo','document') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'text',
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `caption` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `file_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `file_size` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sender` (`sender`),
  KEY `receiver` (`receiver`),
  KEY `date` (`date`),
  KEY `seen` (`seen`),
  KEY `deleted_receiver` (`deleted_receiver`),
  KEY `deleted_sender` (`deleted_sender`)
) ENGINE=InnoDB AUTO_INCREMENT=493 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `sender`, `receiver`, `message`, `files`, `date`, `seen`, `received`, `deleted_sender`, `deleted_receiver`, `edited`, `type`, `file_path`, `caption`, `file_type`, `file_size`) VALUES
(461, '2000234353a', '20023434221h', 'lkjlckajle', NULL, '2025-04-13 21:37:31', 1, 1, 0, 1, 0, 'text', NULL, NULL, NULL, NULL),
(463, '20023434221h', '2034553322a', 'jclkaslke', NULL, '2025-04-13 21:44:21', 0, 0, 0, 0, 0, 'text', NULL, NULL, NULL, NULL),
(464, '20023434221h', '2034553322a', 'hkjkbjjkh\'', NULL, '2025-04-13 21:44:47', 0, 0, 0, 0, 0, 'text', NULL, NULL, NULL, NULL),
(470, '200023435353a', '20023434221h', 'hello', NULL, '2025-04-15 16:42:48', 0, 1, 0, 0, 0, 'text', NULL, NULL, NULL, NULL),
(471, '20023434221h', '2000234353a', 'hello sir', NULL, '2025-04-15 16:49:33', 1, 1, 0, 0, 0, 'text', NULL, NULL, NULL, NULL),
(473, '20023434221h', '2000234353a', 'hello', NULL, '2025-04-15 17:14:59', 1, 0, 0, 1, 0, 'text', NULL, NULL, NULL, NULL),
(474, '20023434221h', '2000234353a', 'hello how are you?', NULL, '2025-04-15 17:17:54', 1, 0, 0, 0, 0, 'text', NULL, NULL, NULL, NULL),
(475, '2000234353a', '20023434221h', 'fine', NULL, '2025-04-15 17:18:02', 1, 0, 0, 0, 0, 'text', NULL, NULL, NULL, NULL),
(476, '20023434221h', '2034553322a', 'hello', NULL, '2025-04-15 17:18:23', 0, 0, 0, 0, 0, 'text', NULL, NULL, NULL, NULL),
(477, '2000234353a', '20023434221h', 'klakjsdf', NULL, '2025-04-15 17:18:36', 1, 0, 0, 0, 0, 'text', NULL, NULL, NULL, NULL),
(478, '2000234353a', '20023434221h', 'hlkajlksd', NULL, '2025-04-15 17:18:55', 1, 0, 0, 0, 0, 'text', NULL, NULL, NULL, NULL),
(479, '2000234353a', '20023434221h', 'lkjalskjelkfa', NULL, '2025-04-15 17:18:57', 1, 0, 0, 0, 0, 'text', NULL, NULL, NULL, NULL),
(480, '2000234353a', '20023434221h', 'kjclaksd', NULL, '2025-04-15 17:18:59', 1, 0, 0, 0, 0, 'text', NULL, NULL, NULL, NULL),
(481, '20023434221h', '2000234353a', 'hi how are you sir', NULL, '2025-04-15 17:19:20', 1, 0, 0, 0, 0, 'text', NULL, NULL, NULL, NULL),
(482, '16', '1998234543a', 'hello', NULL, '2025-04-16 19:31:08', 0, 0, 0, 0, 0, 'text', NULL, NULL, NULL, NULL),
(483, '16', '200232401698p', 'hello', NULL, '2025-04-16 19:31:17', 0, 0, 0, 0, 0, 'text', NULL, NULL, NULL, NULL),
(484, '16', '200232401698p', '1744812090_r3.jpeg', NULL, '2025-04-16 19:31:30', 0, 0, 0, 0, 0, 'photo', 'assets/chats/photos/1744812090_r3.jpeg', NULL, 'Document', '0.0 MB'),
(485, '16', '200232401698p', '1744812113_SCS 2212.pdf', NULL, '2025-04-16 19:31:53', 0, 0, 0, 0, 0, 'document', 'assets/chats/documents/1744812113_SCS 2212.pdf', NULL, 'PDF Document', '0.4 MB'),
(486, '16', '200232401698p', 'shdgysdgdys', NULL, '2025-04-20 16:13:22', 0, 0, 0, 0, 0, 'text', NULL, NULL, NULL, NULL),
(487, '16', '200232401698p', 'hii', NULL, '2025-04-23 13:41:15', 0, 0, 0, 0, 0, 'text', NULL, NULL, NULL, NULL),
(488, '200260502666p', '198534567889d', 'Hello from Amrah', NULL, '2025-04-28 10:04:59', 0, 1, 0, 0, 0, 'text', NULL, NULL, NULL, NULL),
(489, '200232401698d', '200236547889h', 'hi', NULL, '2025-04-28 16:10:40', 1, 0, 0, 0, 0, 'text', NULL, NULL, NULL, NULL),
(490, '200236547889h', '197045826441d', 'hello', NULL, '2025-04-28 16:10:53', 0, 0, 0, 0, 0, 'text', NULL, NULL, NULL, NULL),
(491, '200236547889h', '200232401698d', 'hello this is ra', NULL, '2025-04-28 16:16:11', 1, 1, 0, 0, 0, 'text', NULL, NULL, NULL, NULL),
(492, '200232401698d', '200236547889h', 'hi', NULL, '2025-04-28 16:16:18', 1, 0, 0, 0, 0, 'text', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

DROP TABLE IF EXISTS `patient`;
CREATE TABLE IF NOT EXISTS `patient` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nic` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `dob` date NOT NULL,
  `age` int NOT NULL,
  `gender` char(1) COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `contact` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `medical_history` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `allergies` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `emergency_contact_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `emergency_contact_no` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `emergency_contact_relationship` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `e_wallet` int NOT NULL,
  `verified` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Not Verified',
  `account_state` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Active',
  `user_id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`id`, `nic`, `password`, `first_name`, `last_name`, `dob`, `age`, `gender`, `address`, `email`, `contact`, `medical_history`, `allergies`, `emergency_contact_name`, `emergency_contact_no`, `emergency_contact_relationship`, `e_wallet`, `verified`, `account_state`, `user_id`) VALUES
(4, '200334576812', '$2y$10$bFLTaOa/KkyZBhnsstJBb.BsQCymGyS.l.gCuWgxbUHfK58RsHfNq', 'Chris', 'Evans', '1990-06-13', 34, 'm', '45 Elm St', 'chris.evans@gmail.com', '771234567', 'Hypertension', 'None', 'Sarah Evans', '776543210', 'Wife', 0, 'Not Verified', 'Active', ''),
(16, '200260500000p', '$2y$10$bFLTaOa/KkyZBhnsstJBb.BsQCymGyS.l.gCuWgxbUHfK58RsHfNq', 'Tom', 'Hardy', '2024-11-20', 19, 'M', 'SamagiMawatha,Dippitigala,Lellopitiya', 'Thamindu@gmail.com', '721111111', 'sds', 'asasadsd', 'ghgjh', '721234567', 'bro', 0, 'Not Verified', 'Active', ''),
(22, '200260502668p', '$2y$10$2GUM2jL/vOwsu4HWjEYCWuHIAY.ooCZsHGrHa6Eu4V6f8xcj/Wzvi', 'Himesh', 'Dharmawansha', '2024-11-28', 20, 'M', 'SamagiMawatha,Dippitigala,Lellopitiya', 'Thamindu@gnail.com', '721111111', 'dsdsds', 'asasadsd', 'ghgjh', '721234567', 'bro', 0, 'Not Verified', 'Active', ''),
(23, '200260502555p', '$2y$10$wFBrbGDNJz1fogM1NVPVze2Rr9swkTDkYbz/KTjqotkPuxFiHT2V.', 'Ajith', 'Alwis', '1999-06-15', 25, 'M', 'Kahawatta,Ratnapura', 'alwis10@gmail.com', '721111133', 'sds', 'asasadsd', 'ghgjh', '721234567', 'Mother', 0, 'Not Verified', 'Active', ''),
(27, '200232401569p', '$2y$10$cI9GXBcnqiO46.40Rdwq0upTUbrk9YNxmyYqGqBAjQ1XgTbcx5Y0q', 'kkk', 'kkkk', '2002-02-20', 0, 'M', 'hjggjh', 'slamathamrah@gmail.com', '729912020', 'hhh', 'sdsds', 'sddsd', '745558585', 'sdsds', 0, 'Not Verified', 'Active', ''),
(33, '200260502661p', '$2y$10$CWW/CLN1yL19v8Uagn4Kx.8wKtRyK72SzOMjx46/svZQ6kZhVWETe', 'Ammu', 'ren', '2002-04-14', 0, 'F', 'bhjrbdbnr ecnbedehfbrhjbdhjebdhebfhre', 'slamathamrah@gmail.com', '725684123', 'trhyrhyt', 'hyrtfhth', 'egtregreg', '114789652', 'gr5rfg5r', 0, 'Not Verified', 'Active', ''),
(34, '200260502664p', '$2y$10$bFLTaOa/KkyZBhnsstJBb.BsQCymGyS.l.gCuWgxbUHfK58RsHfNq', 'Amrah', 'Slamath', '2002-04-14', 0, 'F', '123, Kandy, nady', 'slamathamrah@gmail.com', '0771333370', 'None', 'None', 'Mazeena', '0777334616', 'Mother', 0, 'Not Verified', 'Active', ''),
(36, '200260502666p', '$2y$10$VFrX4T7Vmx2IThJwH3kimOWv7XbFHWuBIZsKl6toduWhZEpdaFuJ.', 'Amrah', 'Slamath', '2002-04-14', 0, 'F', '15/28, Stewart Street, Colombo 02', 'slamathamrah@gmail.com', '0771333370', 'None', 'None', 'Maze', '0778965412', 'mom', 3000, 'Not Verified', 'Active', ''),
(37, '200260502663p', '$2y$10$juinSHXSzbE5YD13gzLEeOlD7VCfUJuDQwEh30odoV9H6A2If98U.', 'Sara', 'John', '2000-01-01', 0, 'F', '123, Negombo Road, Negombo', 'sara@gmail.com', '0771528936', 'None', 'None', 'Jack', '0774523698', 'Brother', 0, 'Not Verified', 'Active', ''),
(38, '200260502600p', '$2y$10$Q/PR2R0mnrKYkLq51mfV/./g.BTwTNAo4tthQA3gjmLMpD6PJskda', 'Noah', 'John', '2000-01-01', 0, 'M', '123, Negombo Road, Negombo', 'john@gmail.com', '0771528937', 'None', 'None', 'Amrah', '0778965419', 'Sister', 0, 'Not Verified', 'Active', ''),
(39, '200260502000p', '$2y$10$NNYGf2N6hlM/uACJWGlKl.obEtQE.g6uMxJnoKkq6wDEc1k3DE9ca', 'Salma', 'Manal', '2002-10-10', 0, 'F', '123,abc road, wellawatta', 'manal@gmail.com', '0771889965', 'none', 'none', 'Nimal', '0114785236', 'Brother', 0, 'Not Verified', 'Active', ''),
(40, '200205100507p', '$2y$10$utZXPDavI4bFFq3UktO/1.Wyft.pvh/i4mXEkDGxkR2mBdJXSHRvS', 'Amri', 'Zameen', '2002-02-20', 0, 'M', '11/5f, IDH Road, Salamulla, Kolonnawa', 'amrizameen62@gmail.com', '0774257950', 'Astma', 'None', 'Amrah Slamath', '0771333370', 'Wife', 1500, 'Not Verified', 'Active', ''),
(41, '737692434vp', '$2y$10$ytCHvUEmmmPLvQRpM9u9WeeONQ78d4h/0FCNY1sigqyJaYP6IqJ0K', 'Sonali', 'Perera', '1973-09-25', 0, 'F', '45/4, Temple Road, Negombo', 'sonali@gmail.com', '0716335851', 'Back Pain', 'none', 'Nishantha Samarasekera', '0714086677', 'Spouse', 0, 'Verified', 'Active', ''),
(42, '200132568415p', '$2y$10$Ru9rcWr4Il.wOo9kMpe75.8.il7Dn5Z/BYTPLp.Ycfb78CD8NjFLq', 'Kasun', 'Alwis', '2000-09-26', 0, 'M', '45/4, Temple Road, Nugegoda', 'himeshdharmawansha1119@gmail.com', '0754568254', 'none', 'none', 'Sadun Perera', '0748954265', 'Brother', 0, 'Not Verified', 'Active', ''),
(43, '200234567896p', '$2y$10$2BM7jKBKNZg7kkcmXx56fOODgGc14cVwilbsXr72FVxD7G5VIclhe', 'Timber', 'Hipsha', '2001-09-24', 0, 'M', '5 Temple Road, Colombo', 'slamathamrah@gmail.com', '0754568234', 'none', 'none', 'Fefa', '0762345326', 'Mother', 0, 'Verified', 'Active', '');

-- --------------------------------------------------------

--
-- Table structure for table `patient_search_for_doc`
--

DROP TABLE IF EXISTS `patient_search_for_doc`;
CREATE TABLE IF NOT EXISTS `patient_search_for_doc` (
  `doctor_id` int NOT NULL,
  `doctor_fname` varchar(300) COLLATE utf8mb4_general_ci NOT NULL,
  `specialization` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Appointment_no` int NOT NULL AUTO_INCREMENT,
  `date_time` date NOT NULL,
  UNIQUE KEY `Appointment_no` (`Appointment_no`),
  KEY `fk_doctor_id` (`doctor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_search_for_doc`
--

INSERT INTO `patient_search_for_doc` (`doctor_id`, `doctor_fname`, `specialization`, `Appointment_no`, `date_time`) VALUES
(0, 'Sandaru', 'neurology', 1, '2024-12-03'),
(0, 'Pradeep', 'cardiology', 2, '2024-10-09'),
(0, 'Weera', 'Eye Surgeon', 3, '2024-11-09'),
(0, 'Pradeep', 'Eye Surgeon', 4, '2024-11-30'),
(0, 'Weera', 'cardiology', 5, '2024-11-17'),
(0, 'Weera', 'neurology', 6, '2024-11-29'),
(0, 'Weera', 'cardiology', 7, '2024-11-22'),
(0, 'Weera', 'Eye Surgeon', 8, '2024-11-02');

-- --------------------------------------------------------

--
-- Table structure for table `pharmacist`
--

DROP TABLE IF EXISTS `pharmacist`;
CREATE TABLE IF NOT EXISTS `pharmacist` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nic` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `dob` date NOT NULL,
  `age` int NOT NULL,
  `gender` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `contact` int NOT NULL,
  `emergency_contact_no` int NOT NULL,
  `medical_license_no` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `experience` int NOT NULL,
  `qualifications` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `prev_employment_history` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `account_state` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Active',
  `user_id` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nic` (`nic`),
  KEY `pharmacist_profile_fk` (`user_id`),
  KEY `password` (`password`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pharmacist`
--

INSERT INTO `pharmacist` (`id`, `nic`, `password`, `first_name`, `last_name`, `dob`, `age`, `gender`, `address`, `email`, `contact`, `emergency_contact_no`, `medical_license_no`, `experience`, `qualifications`, `prev_employment_history`, `account_state`, `user_id`) VALUES
(1, '20023434221h', 'password', 'Yart', 'Tali', '2014-11-30', 29, 'M', '5, main street Gampaha ', 'yart@gmail.com', 784545497, 117878457, 'Mvb45d5', 12, 'example', 'example', 'Active', '1'),
(2, '200205702049h', '$2y$10$KqCXU/.CyfQM4rXn9OrS0u4A4EjaMssUHOweHask0GsT181TfLK.m', 'Raveenu', 'Mohan', '2001-02-01', 24, 'F', '123, Badulla Road, Badulla', 'Ravee@gmail.com', 774589632, 774125896, '123445', 5, 'MBBS', 'None', 'Active', '200236547889h');

-- --------------------------------------------------------

--
-- Table structure for table `receptionist`
--

DROP TABLE IF EXISTS `receptionist`;
CREATE TABLE IF NOT EXISTS `receptionist` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `contact` int NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `experience` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nic` (`nic`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `receptionist`
--

INSERT INTO `receptionist` (`id`, `nic`, `password`, `first_name`, `last_name`, `contact`, `email`, `address`, `experience`) VALUES
(1, '200264401515r', '$2y$10$wJDtvNflLxeRav8CxMrXZe.xe4cPUi1KH5C2PPhKqjcKW50l8v/82', 'Raveesha', 'Samarasekera', 710906717, 'raveeshagihanI@gmail.com', 'Negombo', 5);

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

DROP TABLE IF EXISTS `session`;
CREATE TABLE IF NOT EXISTS `session` (
  `id` int NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `doctor_id` int NOT NULL,
  `booked_slots` int NOT NULL,
  `available_slots` int NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `appointment_fee` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `session_doc_fk` (`doctor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `session_cancellation`
--

DROP TABLE IF EXISTS `session_cancellation`;
CREATE TABLE IF NOT EXISTS `session_cancellation` (
  `id` int NOT NULL,
  `session_id` int NOT NULL,
  `cancellation_reason` int NOT NULL,
  `cacellation_date` date NOT NULL,
  `rescheduled_time_slot` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `session_fk` (`session_id`),
  KEY `rescheduled_slot_fk` (`rescheduled_time_slot`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `test_requests`
--

DROP TABLE IF EXISTS `test_requests`;
CREATE TABLE IF NOT EXISTS `test_requests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `doctor_id` int NOT NULL,
  `patient_id` int NOT NULL,
  `date` int NOT NULL,
  `state` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `appointment_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `state` (`state`),
  KEY `patient_id` (`patient_id`),
  KEY `doctor_id` (`doctor_id`),
  KEY `date` (`date`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test_requests`
--

INSERT INTO `test_requests` (`id`, `doctor_id`, `patient_id`, `date`, `state`, `appointment_id`) VALUES
(14, 2, 4, 115, 'pending', 0),
(30, 2, 16, 118, 'completed', 0),
(31, 8, 36, 168, 'Pending', 0),
(32, 8, 43, 169, 'completed', 3);

-- --------------------------------------------------------

--
-- Table structure for table `test_request_details`
--

DROP TABLE IF EXISTS `test_request_details`;
CREATE TABLE IF NOT EXISTS `test_request_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `test_request_id` int NOT NULL,
  `test_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `state` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `priority` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `test_request_id` (`test_request_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test_request_details`
--

INSERT INTO `test_request_details` (`id`, `test_request_id`, `test_name`, `state`, `priority`, `file`) VALUES
(15, 30, 'FBC', 'completed', 'HIGH', '118'),
(16, 30, 'ERC', 'pending', 'Low', '0'),
(17, 31, 'ESR', 'pending', 'HIGH', '0'),
(18, 31, 'FBC', 'pending', 'LOW', '0'),
(19, 31, 'ABC', 'pending', 'HIGH', '0'),
(20, 32, 'fbc', 'completed', 'high', '169_43_32_fbc.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `timeslot`
--

DROP TABLE IF EXISTS `timeslot`;
CREATE TABLE IF NOT EXISTS `timeslot` (
  `slot_id` int NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  PRIMARY KEY (`slot_id`)
) ENGINE=InnoDB AUTO_INCREMENT=184 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timeslot`
--

INSERT INTO `timeslot` (`slot_id`, `date`) VALUES
(112, '2025-02-04'),
(113, '2025-02-05'),
(114, '2025-02-06'),
(115, '2025-02-07'),
(116, '2025-02-08'),
(117, '2025-02-09'),
(118, '2025-02-10'),
(119, '2025-02-11'),
(120, '2025-02-12'),
(121, '2025-02-13'),
(122, '2025-02-14'),
(123, '2025-02-15'),
(124, '2025-02-16'),
(125, '2025-02-17'),
(126, '2025-02-18'),
(127, '2025-02-19'),
(128, '2025-02-20'),
(129, '2025-02-21'),
(130, '2025-02-22'),
(131, '2025-02-23'),
(132, '2025-02-24'),
(133, '2025-02-25'),
(134, '2025-02-26'),
(135, '2025-02-27'),
(136, '2025-02-28'),
(137, '2025-03-01'),
(138, '2025-03-02'),
(139, '2025-03-03'),
(140, '2025-03-04'),
(141, '2025-03-05'),
(142, '2025-03-06'),
(143, '2025-03-07'),
(144, '2025-03-08'),
(145, '2025-03-09'),
(146, '2025-03-10'),
(147, '2025-03-11'),
(148, '2025-04-07'),
(149, '2025-04-08'),
(150, '2025-04-09'),
(151, '2025-04-10'),
(152, '2025-04-11'),
(153, '2025-04-12'),
(154, '2025-04-13'),
(155, '2025-04-14'),
(156, '2025-04-15'),
(157, '2025-04-16'),
(158, '2025-04-17'),
(159, '2025-04-18'),
(160, '2025-04-19'),
(161, '2025-04-20'),
(162, '2025-04-21'),
(163, '2025-04-22'),
(164, '2025-04-23'),
(165, '2025-04-24'),
(166, '2025-04-25'),
(167, '2025-04-26'),
(168, '2025-04-27'),
(169, '2025-04-28'),
(170, '2025-04-29'),
(171, '2025-04-30'),
(172, '2025-05-01'),
(173, '2025-05-02'),
(174, '2025-05-03'),
(175, '2025-05-04'),
(176, '2025-05-05'),
(177, '2025-05-06'),
(178, '2025-05-07'),
(179, '2025-05-08'),
(180, '2025-05-09'),
(181, '2025-05-10'),
(182, '2025-05-11'),
(183, '2025-05-12');

-- --------------------------------------------------------

--
-- Table structure for table `timeslot_doctor`
--

DROP TABLE IF EXISTS `timeslot_doctor`;
CREATE TABLE IF NOT EXISTS `timeslot_doctor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `slot_id` int NOT NULL,
  `doctor_id` int NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `session` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `slot_id` (`slot_id`),
  KEY `doctor_id` (`doctor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=135 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timeslot_doctor`
--

INSERT INTO `timeslot_doctor` (`id`, `slot_id`, `doctor_id`, `start_time`, `end_time`, `session`) VALUES
(22, 118, 2, '23:57:00', '17:00:00', 'CANCELED'),
(23, 120, 2, '11:00:00', '17:00:00', 'SET'),
(90, 116, 2, '09:05:00', '11:30:00', 'CANCELED'),
(91, 121, 2, '00:05:00', '00:05:00', 'SET'),
(92, 135, 2, '18:12:00', '20:14:00', 'SET'),
(93, 158, 2, '12:11:00', '15:11:00', 'SET'),
(94, 166, 2, '22:03:00', '23:06:00', 'SET'),
(96, 166, 4, '10:00:00', '12:00:00', 'SET'),
(99, 171, 5, '10:00:00', '12:00:00', 'SET'),
(100, 180, 5, '10:00:00', '12:00:00', 'SET'),
(101, 172, 5, '08:00:00', '22:00:00', 'SET'),
(102, 176, 5, '10:00:00', '13:00:00', 'SET'),
(103, 173, 5, '10:00:00', '12:00:00', 'SET'),
(104, 175, 5, '13:00:00', '15:00:00', 'SET'),
(105, 177, 5, '14:00:00', '16:00:00', 'SET'),
(106, 179, 5, '17:00:00', '19:00:00', 'SET'),
(107, 171, 6, '18:00:00', '19:00:00', 'SET'),
(108, 172, 6, '16:00:00', '18:00:00', 'SET'),
(109, 180, 6, '13:00:00', '15:00:00', 'SET'),
(110, 176, 6, '22:00:00', '12:00:00', 'SET'),
(111, 182, 6, '09:00:00', '11:00:00', 'SET'),
(112, 178, 6, '18:00:00', '20:00:00', 'SET'),
(113, 174, 6, '14:00:00', '17:00:00', 'SET'),
(114, 181, 6, '13:00:00', '16:00:00', 'SET'),
(115, 171, 7, '17:00:00', '20:00:00', 'SET'),
(116, 180, 7, '19:00:00', '21:00:00', 'SET'),
(117, 176, 7, '19:00:00', '20:30:00', 'SET'),
(118, 174, 7, '17:00:00', '19:00:00', 'SET'),
(119, 178, 7, '17:30:00', '19:30:00', 'SET'),
(120, 172, 7, '14:30:00', '16:30:00', 'SET'),
(121, 171, 8, '14:30:00', '17:30:00', 'SET'),
(122, 180, 8, '08:00:00', '10:00:00', 'SET'),
(123, 177, 8, '08:30:00', '11:00:00', 'SET'),
(124, 174, 8, '11:00:00', '13:00:00', 'SET'),
(125, 175, 8, '10:00:00', '13:00:00', 'SET'),
(126, 172, 8, '09:00:00', '11:00:00', 'SET'),
(127, 182, 8, '14:00:00', '16:00:00', 'SET'),
(128, 168, 8, '19:00:00', '21:00:00', 'SET'),
(129, 169, 8, '18:00:00', '20:00:00', 'SET'),
(130, 171, 4, '08:00:00', '11:00:00', 'SET'),
(131, 170, 8, '14:00:00', '16:00:00', 'SET'),
(132, 173, 8, '16:30:00', '18:30:00', 'SET'),
(133, 179, 8, '13:00:00', '17:00:00', 'SET'),
(134, 183, 8, '14:00:00', '16:00:00', 'SET');

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

DROP TABLE IF EXISTS `user_profile`;
CREATE TABLE IF NOT EXISTS `user_profile` (
  `id` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` bigint NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Profile_default.png',
  `state` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_profile`
--

INSERT INTO `user_profile` (`id`, `username`, `password`, `role`, `image`, `state`) VALUES
('197005026997d', 'Sarath', '$2y$10$c3YUtyz4zw7CYL6Boc39/e82kmHU9KD0FCeaFHoFAtRJ/9ng9cyce', 5, 'Profile_default.png', 0),
('19724355223l', 'Hisan', 'password', 2, 'Profile_default.png', 0),
('198534567889d', 'Bhagya', '$2y$10$HSsYtQoydG8C3YUgiYCvd.em08zW2kKbCRLcLfO4K6uHYwrNeNmFm', 5, 'Profile_default.png', 1),
('199060502667d', 'Manoj', '$2y$10$00tvzMtgni8lwlro16gL5OUWkwbbQnbjwBOLQEFgFc.gAxXW8WfbS', 5, 'Profile_default.png', 1),
('1998234543a', 'Trime', 'password', 3, 'Profile_default.png', 0),
('2000234353a', 'Yusith', 'password', 3, 'Profile_default.png', 1),
('200132568415p', 'Kasun', '$2y$10$Ru9rcWr4Il.wOo9kMpe75.8.il7Dn5Z/BYTPLp.Ycfb78CD8NjFLq', 4, 'Profile_default.png', 1),
('200205100507p', 'Amri', '$2y$10$utZXPDavI4bFFq3UktO/1.Wyft.pvh/i4mXEkDGxkR2mBdJXSHRvS', 4, 'Profile_default.png', 0),
('200205702049h', 'Raveenu', '$2y$10$94hH9A/9SP3mSVdEFyU4DubzWo9VhijS6HFqYv0gMImgAWb1nJqd6', 1, 'Profile_default.png', 0),
('200205702050l', 'Ben', '$2y$10$hvrqNSmHZE0ipE7CUk3xduXnH8AZt99FlRlLY6oUdg6vclHEfkkPi', 2, 'Profile_default.png', 1),
('200232401698d', 'Saliya', '$2y$10$p2oWTImfueNT.iLsyqqI9ehjGBRNeOEedVT5VQYAj6vnw7z6QK2Bu', 5, 'Profile_default.png', 1),
('200232401698p', 'Himesh', 'password', 5, 'Profile_default.png', 1),
('200234567896p', 'Timber', '$2y$10$2BM7jKBKNZg7kkcmXx56fOODgGc14cVwilbsXr72FVxD7G5VIclhe', 4, 'Profile_default.png', 1),
('200260500000p', 'Tom', 'password', 4, '7zpebubuve2nzv6mtknjc.jpg', 0),
('200260502000p', 'Salma', '$2y$10$NNYGf2N6hlM/uACJWGlKl.obEtQE.g6uMxJnoKkq6wDEc1k3DE9ca', 4, 'Profile_default.png', 0),
('200260502668d', 'Ajith', 'password', 4, 'Profile_default.png', 1),
('200260502669d', 'Vijitha', 'password', 4, 'Profile_default.png', 0),
('200285201445p', 'Sas', '$2y$10$vUWo1c1dDn3Md0xHHzp.zOy4GkE2pXXm26HCGA/jNve2mucxNG7dq', 4, 'Profile_default.png', 1),
('2034553322a', 'Mahali', 'password', 3, 'Profile_default.png', 0),
('737692434vp', 'Sonali', '$2y$10$ytCHvUEmmmPLvQRpM9u9WeeONQ78d4h/0FCNY1sigqyJaYP6IqJ0K', 4, 'Profile_default.png', 1),
('908637456Vd', 'Charith', '$2y$10$JrgvCls03rpRBDnjtdz6LOJEe9p4Y4n1KyHFsNQ2Qf.4HFnDgr0ci', 5, 'Profile_default.png', 0);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `appointment_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`),
  ADD CONSTRAINT `appointment_ibfk_3` FOREIGN KEY (`date`) REFERENCES `timeslot` (`slot_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `medication_requests`
--
ALTER TABLE `medication_requests`
  ADD CONSTRAINT `medication_requests_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `medication_requests_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`),
  ADD CONSTRAINT `medication_requests_ibfk_3` FOREIGN KEY (`date`) REFERENCES `timeslot` (`slot_id`);

--
-- Constraints for table `medication_request_details`
--
ALTER TABLE `medication_request_details`
  ADD CONSTRAINT `medication_request_details_ibfk_1` FOREIGN KEY (`req_id`) REFERENCES `medication_requests` (`id`);

--
-- Constraints for table `session_cancellation`
--
ALTER TABLE `session_cancellation`
  ADD CONSTRAINT `session_fk` FOREIGN KEY (`session_id`) REFERENCES `session` (`id`);

--
-- Constraints for table `test_requests`
--
ALTER TABLE `test_requests`
  ADD CONSTRAINT `test_requests_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`),
  ADD CONSTRAINT `test_requests_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `test_requests_ibfk_3` FOREIGN KEY (`date`) REFERENCES `timeslot` (`slot_id`);

--
-- Constraints for table `test_request_details`
--
ALTER TABLE `test_request_details`
  ADD CONSTRAINT `test_request_details_ibfk_1` FOREIGN KEY (`test_request_id`) REFERENCES `test_requests` (`id`);

--
-- Constraints for table `timeslot_doctor`
--
ALTER TABLE `timeslot_doctor`
  ADD CONSTRAINT `timeslot_doctor_ibfk_1` FOREIGN KEY (`slot_id`) REFERENCES `timeslot` (`slot_id`),
  ADD CONSTRAINT `timeslot_doctor_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
