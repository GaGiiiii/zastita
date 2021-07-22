-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 22, 2021 at 02:59 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zastita`
--

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` varchar(100) NOT NULL,
  `order_id` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `payment_product`
--

CREATE TABLE `payment_product` (
  `id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` double NOT NULL,
  `description` varchar(1000) NOT NULL,
  `image` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `price`, `description`, `image`) VALUES
(1, 'Bananica', 20, 'Ovo je cokoladna bananica', 'https://cenoteka.rs/assets/images/articles/cokoladica-stark-bananica-25g-1001195-large.jpg'),
(2, 'Smoki', 45, 'Ovo je smoki mali od 50g', 'https://smoki.rs/wp-content/uploads/2021/02/Smoki_Original150g@2x.png'),
(4, 'Najlepse Zelje 100g', 120, 'Ova cokolada je super i bas je kul', 'https://cenoteka.rs/assets/images/articles/cokolada-stark-najlepse-zelje-mlecna-200g-1001205-large.jpg'),
(5, 'Grand Kafa 500g', 450, 'Kafa je super ukoliko zelite da vam se srce ubrza i podigne pritisak', 'https://cenoteka.rs/assets/images/articles/kafa-grand-gold-500g-1000225-large.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `image` varchar(100) NOT NULL,
  `is_admin` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `image`, `is_admin`) VALUES
(1, 'GaGiiiii', 'gagi@gagi.com', '$2y$10$sZWQ/djeG35Qsu.KW3aYHe9bnR6H/zV7h.KgV12H/5JWYWtzVgtza', 'noimage.png', 1),
(2, 'Mata', 'mata@mata.com', '$2y$10$dMI.SUG3fQC.xsKKNetEI.eJop8Aqn.8KAqCm1cXYqG.h30b0j69G', 'noimage.png', NULL),
(12, 'pera', 'pera@pera.com', '$2y$10$uJbgPjtElngxREHIykNbxeZ85/HElvLTqvP4wUk/7VOSG1TAUPlZC', 'pera20210616.jpg', NULL),
(13, 'a', 'gag@gag.com', '$2y$10$equS5IfwwKcqfPXG7YQBE.Yqry26QGxS.joG/4kN1MHUuXSVSqgmi', 'noimage.png', NULL),
(14, '&lt;script&gt;alert(1)&lt;/script&gt;', 'a@a.com', '$2y$10$9qAdqnNS5IF2DDMMa.0nvefc2KXDfjURRvJ9JDbYTg7HltKw5S/z.', 'noimage.png', NULL),
(15, 'User', 'user@user.com', '$2y$10$GIbX1cpCabXycJtVVKTdGej6dZqFSOpjqrBuI1HIqstQdBLXXUSri', 'User20210718.txt', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_product`
--
ALTER TABLE `payment_product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_product`
--
ALTER TABLE `payment_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
