-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 25, 2024 at 01:56 PM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

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
  `id` int NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nic` varchar(12) COLLATE utf8mb4_general_ci NOT NULL,
  `contact` int NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `userid` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `admin_profile_fk` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `administrative_staff`
--

INSERT INTO `administrative_staff` (`id`, `first_name`, `last_name`, `nic`, `contact`, `email`, `password`, `address`, `role`, `userid`) VALUES
(53, 'Yusith', 'kaiel', '2000234353a', 777777778, 'asd@gmail.com', '$2y$10$nD63QQx83pU2xAvAbAw0e.C97ZtZF7FZ6ABH91Fi672xY2f2YpzIu', 'asdf', 'asdf', '53'),
(222, 'Trime', 'Ubba', '1998234543a', 762423534, 'ubba@gmail.com', '$2y$10$nD63QQx83pU2xAvAbAw0e.C97ZtZF7FZ6ABH91Fi672xY2f2YpzIu', 'asgewr', 'adminstarative_staff', '222'),
(542, 'Mahali', 'Tar', '2034553322a', 782335532, 'vikki@gmail.com', '$2y$10$nD63QQx83pU2xAvAbAw0e.C97ZtZF7FZ6ABH91Fi672xY2f2YpzIu', 'lgkjeps', 'administrative_staff', '542');

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
  `id` int NOT NULL,
  `nic` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `dob` date NOT NULL,
  `age` int NOT NULL,
  `gender` char(1) COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `contact` int NOT NULL,
  `emergency_contact` int NOT NULL,
  `emergency_contact_relationship` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `medical_license_no` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `specialization` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `experience` int NOT NULL,
  `qualifications_cerifications` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `medical_school` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `doctor_profile_fk` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`id`, `nic`, `password`, `first_name`, `last_name`, `dob`, `age`, `gender`, `address`, `email`, `contact`, `emergency_contact`, `emergency_contact_relationship`, `medical_license_no`, `specialization`, `experience`, `qualifications_cerifications`, `medical_school`, `user_id`) VALUES
(1, '200260502668d', 'password', 'Dr. Ajith', 'Nipun', '1990-11-19', 34, 'M', '123,Kinf,Kandy', 'Ajith@gmail.com', 778965412, 773698521, 'Wife', 'MD2210', 'Cardiology', 5, 'vbesy14dnehdb2bd3eb2', 'UOC', '22'),
(2, '200260502669d', 'password', 'Dr. Vijitha', 'Kamal', '1954-08-20', 70, 'M', '234, Kandy Road, Jaffna', 'Vijitha@gmail.com', 778965214, 778932146, 'Son', 'MD44903', 'Neurology', 20, 'vrdh2neq xhjqwbfd3ewqs', 'UOM', '23');

-- --------------------------------------------------------

--
-- Table structure for table `lab_technician`
--

DROP TABLE IF EXISTS `lab_technician`;
CREATE TABLE IF NOT EXISTS `lab_technician` (
  `id` int NOT NULL,
  `nic` varchar(12) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `dob` date NOT NULL,
  `age` int NOT NULL,
  `gender` char(1) COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `contact` int NOT NULL,
  `medical_license_no` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `specialization` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `experience` int NOT NULL,
  `qualifications_certifications` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prev_employment_history` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `userid` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lab_tech_profile_fk` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_technician`
--

INSERT INTO `lab_technician` (`id`, `nic`, `password`, `first_name`, `last_name`, `dob`, `age`, `gender`, `address`, `email`, `contact`, `medical_license_no`, `specialization`, `experience`, `qualifications_certifications`, `prev_employment_history`, `userid`) VALUES
(2, '19724355223l', '$2y$10$nD63QQx83pU2xAvAbAw0e.C97ZtZF7FZ6ABH91Fi672xY2f2YpzIu', 'Hishan', 'Mathu', '2014-11-30', 53, 'M', 'example', 'hishan@example.com', 756598755, 'Phs23ww', 'example', 8, 'example', 'example', '2');

-- --------------------------------------------------------

--
-- Table structure for table `medication_requests`
--

DROP TABLE IF EXISTS `medication_requests`;
CREATE TABLE IF NOT EXISTS `medication_requests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `doctor_id` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `patient_id` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `remark` text COLLATE utf8mb4_general_ci,
  `state` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `diagnosis` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `state` (`state`),
  KEY `date` (`date`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medication_requests`
--

INSERT INTO `medication_requests` (`id`, `doctor_id`, `patient_id`, `date`, `time`, `remark`, `state`, `diagnosis`) VALUES
(1, '123', '432', '2024-11-25', '03:00:00', NULL, 'pending', 'Hyperlipidemia'),
(2, '543', '122', '2024-11-26', '09:50:46', 'Naproxen is not available for now, so I suggest giving that  ', 'progress', 'Hypertension'),
(3, '23', '44', '2024-11-27', '14:50:46', 'asehello', 'completed', 'Hyperlipidemia'),
(4, '245', '532', '2024-11-28', '10:30:00', 'Patient has mild symptoms', 'pending', 'Common Cold'),
(5, '167', '987', '2024-11-29', '12:45:30', 'Follow-up required in 2 weeks', 'completed', 'Diabetes'),
(6, '321', '654', '2024-11-30', '16:15:20', NULL, 'progress', 'Migraine'),
(7, '789', '321', '2024-12-01', '08:20:10', 'Check for allergic reactions', 'pending', 'Asthma'),
(8, '987', '456', '2024-12-02', '18:00:00', NULL, 'completed', 'Arthritis'),
(9, '111', '200', '2024-12-03', '09:30:00', 'Patient needs immediate treatment', 'pending', 'Fever'),
(10, '222', '201', '2024-12-04', '10:15:00', NULL, 'progress', 'Cold'),
(11, '333', '202', '2024-12-05', '11:00:00', 'Monitor for signs of infection', 'completed', 'Pneumonia'),
(12, '444', '203', '2024-12-06', '12:30:00', 'Low dosage recommended', 'pending', 'Cough'),
(13, '555', '204', '2024-12-07', '14:00:00', NULL, 'progress', 'Asthma'),
(14, '666', '205', '2024-12-08', '15:30:00', 'Follow-up after 3 days', 'completed', 'Chronic Pain'),
(15, '777', '206', '2024-12-09', '16:00:00', NULL, 'pending', 'Anxiety'),
(16, '888', '207', '2024-12-10', '17:15:00', 'Need to check for allergies', 'completed', 'Depression'),
(17, '999', '208', '2024-12-11', '18:00:00', 'Patient to monitor blood pressure', 'pending', 'Hypertension'),
(18, '101', '209', '2024-12-12', '08:30:00', NULL, 'progress', 'Migraine'),
(19, '202', '210', '2024-12-13', '09:45:00', 'Patient has mild cold', 'completed', 'Cold'),
(20, '303', '211', '2024-12-14', '10:30:00', NULL, 'pending', 'Diabetes'),
(21, '404', '212', '2024-12-15', '11:15:00', 'Prescribe anti-inflammatory medication', 'progress', 'Arthritis'),
(22, '505', '213', '2024-12-16', '12:00:00', 'Request for painkillers for arthritis', 'pending', 'Osteoarthritis'),
(23, '606', '214', '2024-12-17', '13:00:00', NULL, 'completed', 'Back pain'),
(24, '707', '215', '2024-12-18', '14:30:00', 'Check kidney function', 'completed', 'Kidney disease'),
(25, '808', '216', '2024-12-19', '15:00:00', 'Patient needs higher dosage', 'progress', 'Hypothyroidism'),
(26, '909', '217', '2024-12-20', '16:15:00', 'Monitor heart rate regularly', 'pending', 'Heart disease'),
(27, '101', '218', '2024-12-21', '17:00:00', 'Follow-up required in 2 weeks', 'completed', 'Hypertension'),
(28, '202', '219', '2024-12-22', '18:30:00', 'Check liver function', 'pending', 'Liver disease'),
(29, '303', '220', '2024-12-23', '09:00:00', 'Need blood test results', 'completed', 'Diabetes'),
(30, '404', '221', '2024-12-24', '09:45:00', NULL, 'pending', 'Depression'),
(31, '505', '222', '2024-11-05', '10:30:00', 'Patient has severe pain', 'progress', 'Chronic Pain'),
(32, '606', '223', '2024-11-06', '11:15:00', 'Patient needs inhaler immediately', 'completed', 'Asthma'),
(33, '707', '224', '2024-11-07', '12:00:00', 'Patient feeling nauseous, check further', 'pending', 'Gastroenteritis'),
(34, '808', '225', '2024-11-08', '13:30:00', 'Check for blood clotting issue', 'completed', 'Stroke'),
(35, '909', '226', '2024-11-09', '14:00:00', NULL, 'pending', 'Anxiety'),
(36, '101', '227', '2024-11-09', '15:00:00', 'Adjust medication dosage', 'progress', 'Insomnia'),
(37, '202', '228', '2024-11-10', '16:15:00', 'Prescribe medication after lab test results', 'completed', 'Lupus'),
(38, '303', '229', '2024-11-11', '17:00:00', NULL, 'pending', 'Rheumatoid arthritis'),
(39, '404', '230', '2024-11-12', '18:30:00', 'Start IV drip immediately', 'completed', 'Sepsis'),
(40, '505', '231', '2024-11-13', '09:30:00', 'Check glucose levels regularly', 'pending', 'Diabetes'),
(41, '606', '232', '2024-11-13', '10:00:00', 'Patient needs immediate pain relief', 'progress', 'Back pain'),
(42, '707', '233', '2024-11-01', '11:15:00', 'Reevaluate diagnosis after 3 days', 'completed', 'Chronic cough'),
(43, '808', '234', '2024-11-02', '12:30:00', 'Patient needs blood pressure medication', 'pending', 'Hypertension'),
(44, '909', '235', '2024-11-03', '13:00:00', 'Monitor kidney function', 'progress', 'Kidney disease'),
(45, '101', '236', '2024-11-04', '14:30:00', 'No further medication required for now', 'completed', 'Cold'),
(46, '202', '237', '2024-10-30', '15:00:00', 'Patient requires a new prescription', 'pending', 'Asthma'),
(47, '303', '238', '2024-12-06', '16:30:00', 'Provide vitamin supplements', 'completed', 'Fatigue'),
(48, '404', '239', '2024-11-14', '17:00:00', NULL, 'progress', 'Fever'),
(49, '505', '240', '2024-12-08', '18:00:00', 'New diagnosis and treatment required', 'pending', 'Anemia'),
(50, '606', '241', '2024-12-09', '09:15:00', NULL, 'completed', 'Migraine'),
(51, '101', '250', '2024-12-22', '09:00:00', 'Check patient’s vitals regularly', 'pending', 'Hypertension'),
(52, '202', '251', '2024-12-21', '10:15:00', 'Reevaluate blood pressure medication', 'progress', 'Hypertension'),
(53, '303', '252', '2024-12-20', '11:30:00', 'Patient recovering from fever', 'completed', 'Fever'),
(54, '404', '253', '2024-12-19', '12:45:00', 'Immediate surgery required', 'pending', 'Appendicitis'),
(55, '505', '254', '2024-12-18', '14:00:00', 'Need a detailed lab report', 'progress', 'Diabetes'),
(56, '606', '255', '2024-12-17', '15:15:00', 'Assess patient for allergic reactions', 'completed', 'Asthma'),
(57, '707', '256', '2024-12-16', '16:30:00', 'No significant improvement, change dosage', 'progress', 'Insomnia'),
(58, '808', '257', '2024-12-15', '17:45:00', 'Recheck thyroid levels after 1 week', 'pending', 'Hypothyroidism'),
(59, '909', '258', '2024-12-14', '18:00:00', 'Patient needs a mental health consultation', 'completed', 'Anxiety'),
(60, '101', '259', '2024-12-13', '08:15:00', 'Ensure patient compliance with medication', 'pending', 'Arthritis'),
(61, '202', '260', '2024-12-12', '09:30:00', 'Further tests recommended', 'progress', 'Kidney disease'),
(62, '303', '261', '2024-12-11', '10:45:00', 'Low dosage prescribed due to side effects', 'completed', 'Migraine'),
(63, '404', '262', '2024-12-10', '12:00:00', 'Monitor liver enzymes weekly', 'pending', 'Liver disease'),
(64, '505', '263', '2024-12-09', '13:15:00', 'Patient advised rest and hydration', 'completed', 'Cold'),
(65, '606', '264', '2024-12-08', '14:30:00', 'Consider alternative therapy options', 'progress', 'Arthritis'),
(66, '707', '265', '2024-12-07', '15:45:00', 'New symptoms reported, reassess diagnosis', 'pending', 'Asthma'),
(67, '808', '266', '2024-12-06', '16:00:00', 'Advise physiotherapy sessions', 'completed', 'Chronic Pain'),
(68, '909', '267', '2024-12-05', '17:15:00', 'Ensure regular follow-up appointments', 'progress', 'Heart disease'),
(69, '101', '268', '2024-12-04', '18:30:00', 'Prescribe new pain relief medication', 'pending', 'Osteoarthritis'),
(70, '202', '269', '2024-12-03', '08:00:00', 'Patient recovering well, discharge planned', 'completed', 'Pneumonia');

-- --------------------------------------------------------

--
-- Table structure for table `medication_request_details`
--

DROP TABLE IF EXISTS `medication_request_details`;
CREATE TABLE IF NOT EXISTS `medication_request_details` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `req_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `medication_name` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `dosage` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `taken_time` text COLLATE utf8mb4_general_ci NOT NULL,
  `substitution` tinyint(1) NOT NULL,
  `state` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`),
  KEY `id` (`req_id`),
  KEY `state` (`state`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medication_request_details`
--

INSERT INTO `medication_request_details` (`id`, `req_id`, `medication_name`, `dosage`, `taken_time`, `substitution`, `state`) VALUES
(1, '1', 'Amoxicillin', '2mg', '2 3 1 1', 0, 'pending'),
(2, '1', 'Aspirin', '5mg', '1 2 1 1', 0, 'pending'),
(3, '1', 'Sertraline', '2mg', '0 2 3 1', 1, 'pending'),
(4, '2', 'Aspirin', '2mg', '2 3 1 1', 0, 'given'),
(5, '2', 'Naproxen', '1mg', '2 2 1 1', 0, 'notavailable'),
(6, '3', 'Amoxicillin', '3mg', '2 2 2 2', 0, 'given'),
(7, '3', 'Atorvastatin', '3mg', '1 1 2 1', 0, 'notavailable'),
(8, '4', 'Paracetamol', '500mg', '2 2 1 1', 0, 'pending'),
(9, '4', 'Ibuprofen', '200mg', '1 2 0 1', 1, 'pending'),
(10, '5', 'Metformin', '500mg', '1 1 1 1', 0, 'pending'),
(11, '5', 'Insulin', '10 units', '1 0 1 1', 0, 'pending'),
(12, '6', 'Sumatriptan', '50mg', '1 0 0 1', 0, 'pending'),
(13, '6', 'Naproxen', '250mg', '0 1 0 1', 1, 'pending'),
(14, '7', 'Salbutamol', '2mg', '2 2 1 0', 0, 'pending'),
(15, '8', 'Glucosamine', '1500mg', '1 0 1 1', 0, 'pending'),
(16, '8', 'Diclofenac', '75mg', '0 1 1 0', 1, 'pending'),
(17, '9', 'Paracetamol', '500mg', '1 1 2 1', 0, 'pending'),
(18, '9', 'Aspirin', '200mg', '1 2 0 1', 1, 'pending'),
(19, '10', 'Amoxicillin', '250mg', '0 1 0 1', 0, 'pending'),
(20, '10', 'Ibuprofen', '200mg', '1 1 1 1', 0, 'pending'),
(21, '11', 'Metformin', '500mg', '1 2 1 1', 1, 'pending'),
(22, '11', 'Insulin', '10 units', '2 0 0 1', 0, 'given'),
(23, '12', 'Sumatriptan', '50mg', '0 1 0 1', 1, 'pending'),
(24, '12', 'Paracetamol', '500mg', '1 1 1 1', 0, 'pending'),
(25, '13', 'Salbutamol', '2mg', '1 1 1 1', 0, 'given'),
(26, '13', 'Naproxen', '250mg', '2 3 0 1', 1, 'notavailable'),
(27, '14', 'Glucosamine', '1500mg', '1 0 2 1', 0, 'pending'),
(28, '14', 'Diclofenac', '75mg', '1 1 1 0', 0, 'pending'),
(29, '15', 'Sumatriptan', '50mg', '1 2 1 1', 0, 'completed'),
(30, '15', 'Aspirin', '5mg', '2 1 0 1', 0, 'completed'),
(31, '16', 'Aspirin', '2mg', '1 1 1 1', 1, 'pending'),
(32, '16', 'Amoxicillin', '500mg', '1 2 1 1', 0, 'notavailable'),
(33, '17', 'Paracetamol', '500mg', '2 1 0 1', 0, 'completed'),
(34, '17', 'Naproxen', '250mg', '1 1 2 1', 0, 'pending'),
(35, '18', 'Ibuprofen', '200mg', '0 2 2 1', 0, 'pending'),
(36, '18', 'Sertraline', '2mg', '2 3 1 0', 1, 'pending'),
(37, '19', 'Metformin', '500mg', '2 1 2 1', 0, 'pending'),
(38, '19', 'Insulin', '10 units', '2 0 2 1', 0, 'notavailable'),
(39, '20', 'Sumatriptan', '50mg', '1 2 0 1', 0, 'notavailable'),
(40, '20', 'Paracetamol', '500mg', '1 1 2 1', 1, 'pending'),
(41, '21', 'Aspirin', '5mg', '1 1 1 0', 0, 'pending'),
(42, '21', 'Sertraline', '2mg', '2 3 0 1', 1, 'completed'),
(43, '22', 'Aspirin', '2mg', '1 1 1 1', 1, 'completed'),
(44, '22', 'Amoxicillin', '250mg', '2 1 1 1', 0, 'pending'),
(45, '23', 'Paracetamol', '500mg', '1 2 1 0', 0, 'notavailable'),
(46, '23', 'Diclofenac', '75mg', '1 1 2 1', 0, 'pending'),
(47, '24', 'Aspirin', '5mg', '0 1 0 1', 1, 'completed'),
(48, '24', 'Glucosamine', '1500mg', '2 1 1 1', 0, 'completed'),
(49, '25', 'Amoxicillin', '500mg', '2 3 1 1', 1, 'pending'),
(50, '25', 'Sumatriptan', '50mg', '2 2 0 1', 0, 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `sender` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `receiver` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `files` text COLLATE utf8mb4_general_ci,
  `date` datetime NOT NULL,
  `seen` int NOT NULL DEFAULT '0',
  `received` int NOT NULL DEFAULT '0',
  `deleted_sender` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_receiver` tinyint(1) NOT NULL DEFAULT '0',
  `edited` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sender` (`sender`),
  KEY `receiver` (`receiver`),
  KEY `date` (`date`),
  KEY `seen` (`seen`),
  KEY `deleted_receiver` (`deleted_receiver`),
  KEY `deleted_sender` (`deleted_sender`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `sender`, `receiver`, `message`, `files`, `date`, `seen`, `received`, `deleted_sender`, `deleted_receiver`, `edited`) VALUES
(22, '23', '3', 'seedf', NULL, '2024-11-25 17:23:14', 0, 1, 0, 0, 1),
(24, '23', '1', 'sappaduxdf', NULL, '2024-11-25 19:58:02', 0, 1, 0, 0, 1),
(25, '3', '1', 'hello', NULL, '2024-11-25 20:51:06', 1, 1, 0, 0, 0),
(26, '3', '1', 'sorry', NULL, '2024-11-25 20:51:12', 1, 1, 0, 0, 1),
(27, '1', '3', 'hi', NULL, '2024-11-25 20:53:38', 1, 1, 0, 0, 0),
(28, '3', '1', 'h', NULL, '2024-11-25 21:35:12', 0, 1, 0, 0, 0),
(29, '1', '53', 'hello', NULL, '2024-11-26 12:11:10', 1, 1, 0, 0, 0),
(31, '53', '1', 'hei', NULL, '2024-11-26 14:31:48', 1, 1, 0, 0, 0),
(32, '53', '1', 'hekko', NULL, '2024-11-26 14:31:57', 1, 1, 0, 0, 0),
(33, '1', '53', 'hk', NULL, '2024-11-26 14:37:24', 1, 1, 0, 0, 0),
(34, '1', '53', 'jlskd', NULL, '2024-11-26 15:29:38', 1, 1, 0, 0, 0),
(35, '53', '2', 'sdf', NULL, '2024-11-26 16:18:15', 1, 1, 0, 0, 0),
(36, '1', '2', 'hek', NULL, '2024-11-26 16:21:18', 1, 1, 0, 0, 0),
(37, '53', '1', 'hello', NULL, '2024-11-27 15:51:05', 1, 1, 0, 0, 0),
(38, '1', '53', 'hi', NULL, '2024-11-27 15:51:26', 1, 1, 0, 0, 0),
(39, '53', '1', 'se', NULL, '2024-11-27 15:51:36', 1, 1, 0, 0, 0),
(40, '1', '53', 'hi', NULL, '2024-11-27 15:52:01', 1, 1, 0, 0, 0),
(41, '1', '53', 'sdf', NULL, '2024-11-27 15:52:40', 1, 1, 0, 0, 0),
(42, '53', '1', 'heles', NULL, '2024-11-27 16:16:39', 1, 1, 0, 0, 0),
(43, '53', '1', 'sd', NULL, '2024-11-27 16:17:16', 1, 1, 0, 0, 0),
(44, '53', '1', 'as', NULL, '2024-11-27 17:23:54', 1, 1, 0, 0, 0),
(45, '0', '222', 'hsaes', NULL, '2024-11-27 18:52:00', 0, 0, 0, 0, 0),
(46, '1', '53', 'sdfg', NULL, '2024-11-27 19:11:50', 1, 1, 0, 0, 0),
(48, '1', '53', 'sdfsd', NULL, '2024-11-29 11:52:41', 1, 1, 0, 0, 0),
(50, '2', '53', 'werwsdf', NULL, '2024-11-29 13:59:30', 1, 1, 0, 0, 1),
(51, '53', '1', 'hello', NULL, '2024-12-16 17:32:58', 1, 1, 0, 0, 0),
(52, '53', '1', 'yty', NULL, '2024-12-16 17:33:27', 1, 1, 0, 0, 0),
(53, '53', '1', 'sd', NULL, '2024-12-16 17:33:32', 1, 1, 0, 0, 0),
(54, '222', '1', 'uoisd', NULL, '2024-12-16 18:28:09', 1, 1, 0, 0, 0),
(55, '542', '1', 'yiseu', NULL, '2024-12-17 13:20:09', 1, 1, 0, 0, 0),
(56, '1', '222', 'hello', NULL, '2024-12-17 13:38:19', 1, 0, 0, 0, 0),
(57, '53', '1', 'Goodmorning', NULL, '2024-12-21 11:09:46', 1, 1, 0, 1, 0),
(58, '1', '53', 'Googhjmorning', NULL, '2024-12-21 11:25:46', 1, 1, 0, 0, 0),
(59, '1', '53', 'good morning', NULL, '2024-12-21 11:42:28', 1, 1, 0, 0, 0),
(61, '53', '1', 'hello', NULL, '2024-12-21 11:54:09', 1, 1, 0, 0, 0),
(62, '222', '1', 'hey', NULL, '2024-12-21 12:00:15', 1, 1, 0, 0, 0),
(63, '222', '1', 'asdflajkwe', NULL, '2024-12-21 12:13:40', 1, 1, 0, 0, 0),
(64, '53', '2', 'uialsjd', NULL, '2024-12-23 07:59:20', 1, 1, 0, 0, 0),
(65, '1', '222', 'hello', NULL, '2024-12-24 21:08:01', 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

DROP TABLE IF EXISTS `patient`;
CREATE TABLE IF NOT EXISTS `patient` (
  `id` int NOT NULL,
  `nic` varchar(12) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `dob` date NOT NULL,
  `age` int NOT NULL,
  `gender` char(1) COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `contact` int NOT NULL,
  `medical_history` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `allergies` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `emergency_contact_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `emergency_contact_no` int NOT NULL,
  `emergency_contact_relationship` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `user_ID` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_ID` (`user_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`id`, `nic`, `password`, `first_name`, `last_name`, `dob`, `age`, `gender`, `address`, `email`, `contact`, `medical_history`, `allergies`, `emergency_contact_name`, `emergency_contact_no`, `emergency_contact_relationship`, `user_ID`) VALUES
(1, '200232401698', '12345', 'Himesh', 'Dharmawansha', '2002-11-19', 21, 'M', 'SamagiMawatha,Dippitigala,Lellopitiya', 'ss@gmail.com', 721111111, 'dsdsds', 'asasadsd', 'ghgjh', 721234567, 'bro', '40');

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
  `id` int NOT NULL,
  `nic` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `dob` date NOT NULL,
  `age` int NOT NULL,
  `gender` char(1) COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `contact` int NOT NULL,
  `emergency_contact_no` int NOT NULL,
  `medical_license_no` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `experiience` int NOT NULL,
  `qualifications_certifications` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `prev_employment_histroy` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `userid` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pharmacist_profile_fk` (`userid`),
  KEY `password` (`password`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pharmacist`
--

INSERT INTO `pharmacist` (`id`, `nic`, `first_name`, `last_name`, `dob`, `age`, `gender`, `address`, `email`, `password`, `contact`, `emergency_contact_no`, `medical_license_no`, `experiience`, `qualifications_certifications`, `prev_employment_histroy`, `userid`) VALUES
(1, '20023434221h', 'Yart', 'Tali', '2014-11-30', 29, 'M', '5, main street Gampaha ', 'yart@gmail.com', '$2y$10$nD63QQx83pU2xAvAbAw0e.C97ZtZF7FZ6ABH91Fi672xY2f2YpzIu', 784545497, 117878457, 'Mvb45d5', 12, 'example', 'example', '1');

-- --------------------------------------------------------

--
-- Table structure for table `receptionist`
--

DROP TABLE IF EXISTS `receptionist`;
CREATE TABLE IF NOT EXISTS `receptionist` (
  `id` int NOT NULL,
  `nic` varchar(12) COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `contact` int NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `experience` int NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `receptionist_profile_fk` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `doctor_id` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `patient_id` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `state` varchar(200) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test_requests`
--

INSERT INTO `test_requests` (`id`, `doctor_id`, `patient_id`, `date`, `time`, `state`) VALUES
(1, '433', '343', '2024-11-09', '00:07:28', 'pending'),
(2, '102', '202', '2024-12-23', '14:27:55', 'ongoing'),
(3, '103', '203', '2024-12-22', '23:57:11', 'completed'),
(4, '104', '204', '2024-12-22', '04:22:09', 'pending'),
(5, '105', '205', '2024-12-21', '21:59:15', 'ongoing'),
(6, '106', '206', '2024-12-21', '00:49:48', 'completed'),
(7, '107', '207', '2024-12-20', '10:11:14', 'pending'),
(8, '108', '208', '2024-12-19', '00:26:45', 'ongoing'),
(9, '109', '209', '2024-12-19', '19:40:06', 'completed'),
(10, '110', '210', '2024-12-18', '01:00:16', 'pending'),
(11, '111', '211', '2024-12-17', '18:01:02', 'ongoing'),
(12, '112', '212', '2024-12-16', '15:04:23', 'completed'),
(13, '113', '213', '2024-12-15', '21:18:49', 'pending'),
(14, '114', '214', '2024-12-14', '13:20:57', 'ongoing'),
(15, '115', '215', '2024-12-13', '02:48:19', 'completed'),
(16, '116', '216', '2024-12-12', '21:58:44', 'pending'),
(17, '117', '217', '2024-12-11', '05:28:43', 'ongoing'),
(18, '118', '218', '2024-12-10', '09:27:22', 'completed'),
(19, '119', '219', '2024-12-09', '06:50:44', 'pending'),
(20, '120', '220', '2024-12-08', '05:51:32', 'ongoing'),
(21, '121', '221', '2024-12-07', '08:45:30', 'completed'),
(22, '122', '222', '2024-12-06', '02:12:54', 'pending'),
(23, '123', '223', '2024-12-05', '08:47:59', 'ongoing'),
(24, '124', '224', '2024-12-04', '13:21:14', 'completed'),
(25, '125', '225', '2024-12-03', '16:22:13', 'pending'),
(26, '126', '226', '2024-12-02', '17:47:22', 'ongoing'),
(27, '127', '227', '2024-12-01', '15:50:14', 'completed'),
(28, '128', '228', '2024-11-30', '01:49:04', 'pending'),
(29, '129', '229', '2024-11-29', '09:34:39', 'ongoing'),
(30, '130', '230', '2024-11-28', '18:26:03', 'completed'),
(31, '131', '231', '2024-11-27', '15:26:18', 'pending'),
(32, '132', '232', '2024-11-26', '21:53:23', 'ongoing'),
(33, '133', '233', '2024-11-25', '15:07:59', 'completed'),
(34, '134', '234', '2024-11-24', '09:59:46', 'pending'),
(35, '135', '235', '2024-11-23', '04:34:55', 'ongoing'),
(36, '136', '236', '2024-11-22', '16:55:19', 'completed'),
(37, '137', '237', '2024-11-21', '22:51:51', 'completed'),
(38, '138', '238', '2024-11-20', '15:33:17', 'ongoing'),
(39, '139', '239', '2024-11-19', '09:10:51', 'completed'),
(40, '140', '240', '2024-11-18', '23:14:26', 'pending'),
(41, '141', '241', '2024-11-17', '16:39:36', 'ongoing'),
(42, '142', '242', '2024-11-16', '13:34:44', 'completed'),
(43, '143', '243', '2024-11-15', '17:54:51', 'pending'),
(44, '144', '244', '2024-11-14', '00:50:05', 'ongoing'),
(45, '145', '245', '2024-11-13', '22:25:51', 'completed'),
(46, '146', '246', '2024-11-12', '13:39:01', 'pending'),
(47, '147', '247', '2024-11-11', '00:57:32', 'completed'),
(48, '148', '248', '2024-11-10', '11:50:39', 'completed'),
(49, '149', '249', '2024-11-09', '08:20:38', 'ongoing'),
(50, '150', '250', '2024-11-08', '06:11:14', 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `test_request_details`
--

DROP TABLE IF EXISTS `test_request_details`;
CREATE TABLE IF NOT EXISTS `test_request_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `req_id` int NOT NULL,
  `test_name` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `state` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `priority` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `file` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `req_id` (`req_id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test_request_details`
--

INSERT INTO `test_request_details` (`id`, `req_id`, `test_name`, `state`, `priority`, `file`) VALUES
(1, 1, 'Urine\r\n', 'pending', 'medium', ''),
(2, 1, 'Blood(Acute)\r\n', 'pending', 'high', ''),
(3, 2, 'Blood', 'pending', 'medium', ''),
(4, 2, 'Urine', 'ongoing', 'high', ''),
(5, 3, 'Blood', 'completed', 'low', ''),
(6, 3, 'Urine', 'completed', 'medium', ''),
(7, 4, 'Blood', 'pending', 'high', ''),
(8, 4, 'Urine', 'ongoing', 'low', ''),
(9, 5, 'Blood', 'pending', 'medium', ''),
(10, 5, 'Urine', 'ongoing', 'high', ''),
(11, 6, 'Blood', 'completed', 'low', ''),
(12, 6, 'Urine', 'completed', 'medium', ''),
(13, 7, 'Blood', 'pending', 'high', ''),
(14, 7, 'Urine', 'ongoing', 'medium', ''),
(15, 8, 'Blood', 'pending', 'low', ''),
(16, 8, 'Urine', 'ongoing', 'high', ''),
(17, 9, 'Blood', 'completed', 'medium', ''),
(18, 9, 'Urine', 'completed', 'low', ''),
(19, 10, 'Blood', 'pending', 'high', ''),
(20, 10, 'Urine', 'ongoing', 'medium', ''),
(21, 11, 'Blood', 'pending', 'low', ''),
(22, 11, 'Urine', 'ongoing', 'high', ''),
(23, 12, 'Blood', 'completed', 'medium', ''),
(24, 12, 'Urine', 'completed', 'low', ''),
(25, 13, 'Blood', 'pending', 'high', ''),
(26, 13, 'Urine', 'ongoing', 'medium', ''),
(27, 14, 'Blood', 'pending', 'low', ''),
(28, 14, 'Urine', 'ongoing', 'high', ''),
(29, 15, 'Blood', 'completed', 'medium', ''),
(30, 15, 'Urine', 'completed', 'low', ''),
(31, 16, 'Blood', 'pending', 'high', ''),
(32, 16, 'Urine', 'ongoing', 'medium', ''),
(33, 17, 'Blood', 'pending', 'low', ''),
(34, 17, 'Urine', 'ongoing', 'high', ''),
(35, 18, 'Blood', 'completed', 'medium', ''),
(36, 18, 'Urine', 'completed', 'low', ''),
(37, 19, 'Blood', 'pending', 'high', ''),
(38, 19, 'Urine', 'ongoing', 'medium', ''),
(39, 20, 'Blood', 'pending', 'low', ''),
(40, 20, 'Urine', 'ongoing', 'high', ''),
(41, 21, 'Blood', 'completed', 'medium', ''),
(42, 21, 'Urine', 'completed', 'low', ''),
(43, 22, 'Blood', 'pending', 'high', ''),
(44, 22, 'Urine', 'ongoing', 'medium', ''),
(45, 23, 'Blood', 'pending', 'low', ''),
(46, 23, 'Urine', 'ongoing', 'high', ''),
(47, 24, 'Blood', 'completed', 'medium', ''),
(48, 24, 'Urine', 'completed', 'low', ''),
(49, 25, 'Blood', 'pending', 'high', ''),
(50, 25, 'Urine', 'ongoing', 'medium', ''),
(51, 26, 'Blood', 'pending', 'low', ''),
(52, 26, 'Urine', 'ongoing', 'high', ''),
(53, 27, 'Blood', 'completed', 'medium', ''),
(54, 27, 'Urine', 'completed', 'low', ''),
(55, 28, 'Blood', 'pending', 'high', ''),
(56, 28, 'Urine', 'ongoing', 'medium', ''),
(57, 29, 'Blood', 'pending', 'low', ''),
(58, 29, 'Urine', 'ongoing', 'high', ''),
(59, 30, 'Blood', 'completed', 'medium', ''),
(60, 30, 'Urine', 'completed', 'low', ''),
(61, 31, 'Blood', 'pending', 'high', ''),
(62, 31, 'Urine', 'ongoing', 'medium', ''),
(63, 32, 'Blood', 'pending', 'low', ''),
(64, 32, 'Urine', 'ongoing', 'high', ''),
(65, 33, 'Blood', 'completed', 'medium', ''),
(66, 33, 'Urine', 'completed', 'low', ''),
(67, 34, 'Blood', 'pending', 'high', ''),
(68, 34, 'Urine', 'ongoing', 'medium', ''),
(69, 35, 'Blood', 'pending', 'low', ''),
(70, 35, 'Urine', 'ongoing', 'high', ''),
(71, 36, 'Blood', 'completed', 'medium', ''),
(72, 36, 'Urine', 'completed', 'low', ''),
(73, 37, 'Blood', 'completed', 'high', 'Database II Lab sheet.docx'),
(74, 37, 'Urine', 'completed', 'medium', 'Document1.pdf'),
(75, 38, 'Blood', 'pending', 'low', ''),
(76, 38, 'Urine', 'ongoing', 'high', ''),
(77, 39, 'Blood', 'completed', 'medium', ''),
(78, 39, 'Urine', 'completed', 'low', ''),
(79, 40, 'Blood', 'pending', 'high', ''),
(80, 40, 'Urine', 'ongoing', 'medium', ''),
(81, 41, 'Blood', 'pending', 'low', ''),
(82, 41, 'Urine', 'ongoing', 'high', ''),
(83, 42, 'Blood', 'completed', 'medium', ''),
(84, 42, 'Urine', 'completed', 'low', ''),
(85, 43, 'Blood', 'pending', 'high', ''),
(86, 43, 'Urine', 'ongoing', 'medium', ''),
(87, 44, 'Blood', 'pending', 'low', ''),
(88, 44, 'Urine', 'ongoing', 'high', ''),
(89, 45, 'Blood', 'completed', 'medium', ''),
(90, 45, 'Urine', 'completed', 'low', ''),
(91, 46, 'Blood', 'pending', 'high', ''),
(92, 46, 'Urine', 'ongoing', 'medium', ''),
(93, 47, 'Blood', 'completed', 'low', 'Database II Lab sheet.docx'),
(94, 47, 'Urine', 'completed', 'high', 'Document1.pdf'),
(95, 48, 'Blood', 'completed', 'medium', ''),
(96, 48, 'Urine', 'completed', 'low', ''),
(97, 49, 'Blood', 'completed', 'high', ''),
(98, 49, 'Urine', 'completed', 'medium', 'Database II Lab sheet.docx'),
(99, 50, 'Blood', 'pending', 'low', ''),
(100, 50, 'Urine', 'completed', 'medium', '');

-- --------------------------------------------------------

--
-- Table structure for table `timeslot`
--

DROP TABLE IF EXISTS `timeslot`;
CREATE TABLE IF NOT EXISTS `timeslot` (
  `slot_id` int NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  PRIMARY KEY (`slot_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timeslot`
--

INSERT INTO `timeslot` (`slot_id`, `date`) VALUES
(1, '2024-11-29'),
(2, '2024-11-30'),
(3, '2024-12-01'),
(4, '2024-12-02'),
(5, '2024-12-03'),
(6, '2024-12-04'),
(7, '2024-12-05'),
(8, '2024-12-06'),
(9, '2024-12-07'),
(10, '2024-12-08'),
(11, '2024-12-09'),
(12, '2024-12-10'),
(13, '2024-12-11'),
(14, '2024-12-12'),
(15, '2024-12-13'),
(16, '2024-12-14'),
(17, '2024-12-15'),
(18, '2024-12-16'),
(19, '2024-12-17'),
(20, '2024-12-18'),
(21, '2024-12-19'),
(22, '2024-12-20'),
(23, '2024-12-21'),
(24, '2024-12-22'),
(25, '2024-12-23'),
(26, '2024-12-24'),
(27, '2024-12-25');

-- --------------------------------------------------------

--
-- Table structure for table `timeslot_doctor`
--

DROP TABLE IF EXISTS `timeslot_doctor`;
CREATE TABLE IF NOT EXISTS `timeslot_doctor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `slot_id` int NOT NULL,
  `doctor_id` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `slot_id` (`slot_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `time_slot`
--

DROP TABLE IF EXISTS `time_slot`;
CREATE TABLE IF NOT EXISTS `time_slot` (
  `slot_id` int NOT NULL,
  `date` date NOT NULL,
  `doctor_timeslot` longtext COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`slot_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `time_slot`
--

INSERT INTO `time_slot` (`slot_id`, `date`, `doctor_timeslot`) VALUES
(1, '2024-11-09', '{\r\n  \"200123145674\": [\r\n    {\"start\": \"08:00\", \"end\": \"11:00\"},\r\n    {\"start\": \"13:00\", \"end\": \"15:00\"}\r\n  ],\r\n  \"200143701245\": [\r\n    {\"start\": \"08:00\", \"end\": \"10:00\"},\r\n    {\"start\": \"13:00\", \"end\": \"16:00\"}\r\n  ]\r\n}'),
(2, '2024-11-10', '{\r\n  \"200123145674\": [\r\n    {\"start\": \"08:00\", \"end\": \"10:00\"},\r\n    {\"start\": \"13:00\", \"end\": \"15:00\"}\r\n  ],\r\n  \"200143701245\": [\r\n    {\"start\": \"09:00\", \"end\": \"12:00\"},\r\n    {\"start\": \"13:00\", \"end\": \"16:00\"}\r\n  ]\r\n}'),
(3, '2024-11-11', '{\r\n  \"200123145674\": [\r\n    {\"start\": \"08:00\", \"end\": \"10:00\"},\r\n    {\"start\": \"13:00\", \"end\": \"15:00\"}\r\n  ],\r\n  \"200143701245\": [\r\n    {\"start\": \"09:00\", \"end\": \"12:00\"},\r\n    {\"start\": \"13:00\", \"end\": \"16:00\"}\r\n  ]\r\n}'),
(4, '2024-11-12', '{\r\n  \"200123145674\": [\r\n    {\"start\": \"08:00\", \"end\": \"10:00\"},\r\n    {\"start\": \"13:00\", \"end\": \"15:00\"}\r\n  ],\r\n  \"200143701245\": [\r\n    {\"start\": \"09:00\", \"end\": \"12:00\"},\r\n    {\"start\": \"13:00\", \"end\": \"16:00\"}\r\n  ]\r\n}'),
(5, '2024-11-13', '{\r\n  \"200123145674\": [\r\n    {\"start\": \"08:00\", \"end\": \"10:00\"},\r\n    {\"start\": \"13:00\", \"end\": \"15:00\"}\r\n  ],\r\n  \"200143701245\": [\r\n    {\"start\": \"09:00\", \"end\": \"12:00\"},\r\n    {\"start\": \"13:00\", \"end\": \"16:00\"}\r\n  ]\r\n}'),
(6, '2024-11-14', '{\r\n  \"200123145674\": [\r\n    {\"start\": \"08:00\", \"end\": \"10:00\"},\r\n    {\"start\": \"13:00\", \"end\": \"15:00\"}\r\n  ],\r\n  \"200143701245\": [\r\n    {\"start\": \"09:00\", \"end\": \"12:00\"},\r\n    {\"start\": \"13:00\", \"end\": \"16:00\"}\r\n  ]\r\n}'),
(7, '2024-11-15', '{\r\n  \"200123145674\": [\r\n    {\"start\": \"08:00\", \"end\": \"10:00\"},\r\n    {\"start\": \"13:00\", \"end\": \"15:00\"}\r\n  ],\r\n  \"200143701245\": [\r\n    {\"start\": \"09:00\", \"end\": \"12:00\"},\r\n    {\"start\": \"13:00\", \"end\": \"16:00\"}\r\n  ]\r\n}');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `userid` bigint DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `gender` varchar(6) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `image` varchar(500) DEFAULT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `username` (`username`),
  KEY `email` (`email`),
  KEY `gender` (`gender`),
  KEY `date` (`date`),
  KEY `state` (`state`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `userid`, `username`, `email`, `gender`, `password`, `date`, `image`, `state`) VALUES
(1, 239152703, 'Eathorne', 'eathorne@yahoo.com', 'Male', 'password', '2020-12-25 15:31:32', 'uploads/afro-beautiful-black-women-fashion-Favim.com-3980589.jpg', 0),
(2, 89701890839882223, 'Maran', 'mary@yahoo.com', 'male', 'password', '2020-12-25 15:31:49', NULL, 0),
(3, 1148711, 'John', 'john@yahoo.com', 'Male', 'password', '2020-12-25 15:32:10', 'uploads/handsome-adult-black-man-successful-business-african-person-117063782.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

DROP TABLE IF EXISTS `user_profile`;
CREATE TABLE IF NOT EXISTS `user_profile` (
  `id` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` bigint NOT NULL,
  `image` text COLLATE utf8mb4_general_ci,
  `state` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_profile`
--

INSERT INTO `user_profile` (`id`, `username`, `password`, `role`, `image`, `state`) VALUES
('1', 'Yart', 'password', 1, NULL, 1),
('2', 'Hisan', 'password', 2, NULL, 1),
('22', 'Ajith', 'password', 4, NULL, 0),
('222', 'Trime', 'password', 3, NULL, 0),
('23', 'Vijitha', 'password', 4, NULL, 0),
('40', 'Himesh', 'password', 5, NULL, 0),
('53', 'Yusith', 'password', 3, NULL, 1),
('542', 'Mahali', 'password', 3, NULL, 0);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `administrative_staff`
--
ALTER TABLE `administrative_staff`
  ADD CONSTRAINT `administrative_staff_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user_profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `doctor`
--
ALTER TABLE `doctor`
  ADD CONSTRAINT `doctor_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lab_technician`
--
ALTER TABLE `lab_technician`
  ADD CONSTRAINT `lab_technician_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user_profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `patient`
--
ALTER TABLE `patient`
  ADD CONSTRAINT `patient_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `user_profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pharmacist`
--
ALTER TABLE `pharmacist`
  ADD CONSTRAINT `pharmacist_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user_profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `session`
--
ALTER TABLE `session`
  ADD CONSTRAINT `session_doc_fk` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`id`);

--
-- Constraints for table `session_cancellation`
--
ALTER TABLE `session_cancellation`
  ADD CONSTRAINT `rescheduled_slot_fk` FOREIGN KEY (`rescheduled_time_slot`) REFERENCES `time_slot` (`slot_id`),
  ADD CONSTRAINT `session_fk` FOREIGN KEY (`session_id`) REFERENCES `session` (`id`);

--
-- Constraints for table `timeslot_doctor`
--
ALTER TABLE `timeslot_doctor`
  ADD CONSTRAINT `timeslot_doctor_ibfk_1` FOREIGN KEY (`slot_id`) REFERENCES `timeslot` (`slot_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
