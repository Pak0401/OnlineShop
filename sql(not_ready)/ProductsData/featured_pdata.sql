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
-- Table structure for table `featured_pdata`
--

DROP TABLE IF EXISTS `featured_pdata`;
CREATE TABLE IF NOT EXISTS `featured_pdata` (
  `FPID` int(255) NOT NULL AUTO_INCREMENT,
  `FPName` varchar(255) NOT NULL,
  `Price` int(255) NOT NULL,
  `GID` int(255) NOT NULL,
  PRIMARY KEY (`FPID`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `featured_pdata`
--

INSERT INTO `featured_pdata` (`FPID`, `FPName`, `Price`, `GID`) VALUES
(1, 'DogP01', 300, 1),
(2, 'DogP01', 500, 1),
(3, 'CatP01', 300, 2),
(4, 'CatP01', 500, 2),
(5, 'CatP04', 90, 6),
(6, 'DogP04', 60, 9),
(7, 'DogP04', 75, 9),
(8, 'DogP04', 85, 9);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
