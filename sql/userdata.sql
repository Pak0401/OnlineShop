-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1:3306
-- 產生時間： 2025-03-14 14:41:56
-- 伺服器版本： 5.7.36
-- PHP 版本： 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫: `userdata`
--

-- --------------------------------------------------------

--
-- 資料表結構 `data`
--

DROP TABLE IF EXISTS `data`;
CREATE TABLE IF NOT EXISTS `data` (
  `UID` int(10) NOT NULL AUTO_INCREMENT,
  `UName` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Role` varchar(10) NOT NULL,
  PRIMARY KEY (`UID`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `data`
--

INSERT INTO `data` (`UID`, `UName`, `Password`, `Email`, `Role`) VALUES
(1, 'Admin', '$2y$10$ZRfBJ9cnFoFl0vRq/OAr8ekkxL4uE5sWlJ7ikQ2HMIMhCfSHGKgtS', 'Admin@gmail.com', 'admin'),
(2, '3', '$2y$10$vqMKlA8oGfEKqorA8DIJxu6MJ71ZT6lhC957K68k00pklq6GYxrkG', '3@gmail.com', 'user'),
(3, '1', '$2y$10$BIRipa5vc0WbgRMnmHhq/usunf/GhubcUV7gEkGB4BHozvWNUl0ZC', '1@gmail.com', 'user');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
