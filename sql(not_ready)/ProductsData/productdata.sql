-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 01, 2024 at 05:32 AM
-- Server version: 5.7.36
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `product_data`
--

-- --------------------------------------------------------

--
-- Table structure for table `productdata`
--

DROP TABLE IF EXISTS `productdata`;
CREATE TABLE IF NOT EXISTS `productdata` (
  `PID` int(255) NOT NULL AUTO_INCREMENT,
  `PName` varchar(255) NOT NULL,
  `Price` int(255) NOT NULL,
  `Variant` varchar(255) NOT NULL,
  `GID` int(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  PRIMARY KEY (`PID`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `productdata`
--

INSERT INTO `productdata` (`PID`, `PName`, `Price`, `Variant`, `GID`, `Description`) VALUES
(1, 'DogP01', 300, '5kg', 1, 'Dog Food [Suitable for All Dogs]\r\nProtein dry food + carrot meat pellets series,\r\ncontaining a single formula of mutton and pumpkin'),
(2, 'DogP01', 500, '10kg', 1, 'Dog Food [Suitable for All Dogs]\r\nProtein dry food + carrot meat pellets series,\r\ncontaining a single formula of mutton and pumpkin'),
(3, 'CatP01', 300, '5kg', 2, ''),
(4, 'CatP01', 500, '10kg', 2, ''),
(5, 'CleanP01', 200, '500ml', 3, ''),
(6, 'CleanP01', 250, '950ml', 3, ''),
(7, 'CatP02', 300, 'Tofu Cat Litter', 4, 'Tofu Cat Litter'),
(8, 'CatP03', 300, 'GreenTea Tofu Cat Litter', 5, 'GreenTea Tofu Cat Litter'),
(9, 'CatP04', 90, 'Cat Toy', 6, 'Cat Toy'),
(10, 'DogP02', 375, '50 sheets', 7, 'Dog Sheet'),
(11, 'DogP03', 700, '100 sheets', 8, 'Dog Sheet'),
(12, 'DogP04', 60, 'Samll', 9, 'Tree Wood Chew\r\nFor Dog'),
(13, 'DogP04', 75, 'Medium', 9, 'Tree Wood Chew\r\nFor Dog'),
(14, 'DogP04', 85, 'Large', 9, 'Tree Wood Chew\r\nFor Dog'),
(15, 'CleanP02', 200, '500ml', 10, ''),
(16, 'CleanP02', 250, '950ml', 10, ''),
(17, 'CleanP03', 200, '500ml', 11, ''),
(18, 'CleanP03', 250, '950ml', 11, ''),
(19, 'CleanP04', 200, '500ml', 12, ''),
(20, 'CleanP04', 250, '950ml', 12, '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
