-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 15, 2018 at 04:43 PM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id3183730_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Parent` int(11) NOT NULL,
  `Ordering` int(11) DEFAULT NULL,
  `Visibility` tinyint(4) NOT NULL DEFAULT '0',
  `Allow_Comment` tinyint(4) NOT NULL DEFAULT '0',
  `Allow_Ads` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `Name`, `Description`, `Parent`, `Ordering`, `Visibility`, `Allow_Comment`, `Allow_Ads`) VALUES
(21, 'Computers', 'Computers Parent Categorie', 0, 1, 1, 1, 1),
(22, 'Cell Phones', 'Cell Phones Parent Categorie', 0, 2, 1, 1, 1),
(23, 'Hand Made', 'Hand Made Categorie', 0, 3, 0, 0, 0),
(24, 'Tools', 'Tools Categorie', 0, 4, 0, 0, 0),
(25, 'Apple Iphones', 'Apple Iphones Sub Categorie', 22, 0, 0, 0, 0),
(26, 'BlackBerry', 'blackberry Phones', 22, 0, 0, 0, 0),
(27, 'NVIDIA ', 'NVIDIA GeForce GPU', 21, 0, 0, 0, 0),
(28, 'Boxs', 'Hand Made Boxs', 23, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `c_id` int(11) NOT NULL,
  `comment` text CHARACTER SET utf8 NOT NULL,
  `status` tinyint(4) NOT NULL,
  `comment_date` date NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`c_id`, `comment`, `status`, `comment_date`, `item_id`, `user_id`) VALUES
(16, 'Nice', 1, '2018-03-13', 69, 1),
(17, 'Add Comment', 1, '2018-03-13', 69, 1),
(18, 'Add Comment', 0, '2018-03-13', 69, 1);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `Item_ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Price` varchar(255) NOT NULL,
  `Add_Date` date NOT NULL,
  `Country_Made` varchar(255) NOT NULL,
  `Image` varchar(255) NOT NULL,
  `Status` varchar(255) NOT NULL,
  `Rating` smallint(6) NOT NULL,
  `Approval_Status` tinyint(4) NOT NULL DEFAULT '0',
  `Cat_ID` int(11) NOT NULL,
  `Member_ID` int(11) NOT NULL,
  `Tags` varchar(255) NOT NULL,
  `Item_Img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`Item_ID`, `Name`, `Description`, `Price`, `Add_Date`, `Country_Made`, `Image`, `Status`, `Rating`, `Approval_Status`, `Cat_ID`, `Member_ID`, `Tags`, `Item_Img`) VALUES
(64, 'GTX 1080 ti', 'Nvidea GeForce GPU GTX 1080TI', '500', '2018-03-13', 'USA', '', '3', 0, 1, 27, 1, '1080, gtx,  ti', '1021227389_download.jpg'),
(65, 'Iphone 5s', 'Iphone 5s  \"غالى على الفااضى\"', '9999999999', '2018-03-13', 'USA', '', '3', 0, 1, 25, 32, 'iphone 5s, usa, 5s', '1195346195_apple-iphone-5se-ofic.jpg'),
(66, 'Test Edit', 'Testing Testing Testing', '1000', '2018-03-13', 'japan', '', '1', 0, 0, 24, 1, '24', ''),
(68, 'good car ssss', 'it is not a good car', '130', '2018-03-13', 'Egypt', '', '2', 0, 1, 27, 1, '27', ''),
(69, 'Samsung Galaxy S7 Edge+ ', 'Samsung Galaxy S7 Edge+ Samsung Galaxy S7 Edge+ ', '324234', '2018-03-13', 'japan', '', '2', 0, 1, 24, 1, '24', '844882474_cf488b982bf143c2b33021f430773508 (1).png'),
(71, 'test55', '123456798', '345345', '2018-03-14', 'Test', '', '1', 0, 0, 25, 28, 'test55', '557380529_12322609_573575612815746_3271748554361658945_o.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL COMMENT 'user identifier number',
  `Username` varchar(255) NOT NULL COMMENT 'username to Login',
  `Password` varchar(255) NOT NULL COMMENT 'password to login',
  `Email` varchar(255) NOT NULL,
  `FullName` varchar(255) NOT NULL,
  `GroupID` int(11) NOT NULL DEFAULT '0' COMMENT 'identity user permission (user group)',
  `TrustStatus` int(11) NOT NULL DEFAULT '0' COMMENT 'Seller Rank',
  `RegStatus` int(11) NOT NULL DEFAULT '0' COMMENT 'User Approval',
  `Date` date NOT NULL,
  `Avatar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`, `Email`, `FullName`, `GroupID`, `TrustStatus`, `RegStatus`, `Date`, `Avatar`) VALUES
(1, 'Hozayen', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'Hozayen@hozz.com', 'Mahmoud Hozayen', 1, 0, 1, '0000-00-00', '810340686_flat,1000x1000,075,f.u1.jpg'),
(13, 'Admin0', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'Admin@me.com', 'Admin', 1, 0, 1, '0000-00-00', ''),
(15, 'Admin1', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'Admin@me.com', 'Admin', 1, 0, 1, '0000-00-00', ''),
(16, 'Admin2', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'Admin@me.com', 'Admin', 1, 0, 1, '0000-00-00', ''),
(26, 'Admin3', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'Admin@me.com', 'Admin', 1, 0, 1, '0000-00-00', ''),
(27, 'Admin4', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'Admin@me.com', 'Admin', 1, 0, 1, '0000-00-00', ''),
(28, 'Admin5', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'Admin@me.com', 'Admin', 1, 0, 1, '0000-00-00', ''),
(29, 'Mahmoud Hozayen', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'Hozayen@hozz.com', 'Mahmoud Hozayen', 0, 0, 1, '2018-03-16', '810340686_flat,1000x1000,075,f.u1.jpg'),
(30, 'Aly', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'Hozayen@hozz.com', 'Mahmoud Hozayen', 0, 0, 1, '0000-00-00', '297519434_1513890445763.png'),
(32, 'Test', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'Hozayen@hozz.com', 'Mahmoud Hozayen', 0, 0, 1, '0000-00-00', '1116504843_28167218_1628583903900246_1231432012129107968_n.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`c_id`),
  ADD KEY `items_comment` (`item_id`),
  ADD KEY `user_comment` (`user_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`Item_ID`),
  ADD KEY `member_1` (`Member_ID`),
  ADD KEY `cat_1` (`Cat_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `Item_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'user identifier number', AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `items_comment` FOREIGN KEY (`item_id`) REFERENCES `items` (`Item_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_comment` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `cat_1` FOREIGN KEY (`Cat_ID`) REFERENCES `categories` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `member_1` FOREIGN KEY (`Member_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
