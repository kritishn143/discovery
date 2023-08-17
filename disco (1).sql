-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 17, 2023 at 04:28 PM
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
-- Database: `disco`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `comment_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `post_id`, `comment_text`, `created_at`) VALUES
(12, 3, 19, 'okay working f9', '2023-08-17 12:40:26'),
(15, 6, 26, 'ohohoho', '2023-08-17 12:53:09'),
(17, 6, 26, 'hamburger', '2023-08-17 13:00:32'),
(23, 6, 26, 'muji kosise', '2023-08-17 13:15:27'),
(25, 6, 26, 'sd', '2023-08-17 13:17:54'),
(26, 6, 26, '  ok muji', '2023-08-17 13:18:09'),
(27, 6, 26, '1', '2023-08-17 13:32:46'),
(28, 6, 19, '1', '2023-08-17 13:34:32'),
(30, 3, 18, '', '2023-08-17 13:52:38');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `post_id`, `created_at`) VALUES
(51, 8, 15, '2023-08-17 13:06:05'),
(63, 3, 18, '2023-08-17 13:19:33'),
(64, 6, 18, '2023-08-17 13:32:00'),
(66, 3, 19, '2023-08-17 14:22:47'),
(67, 3, 15, '2023-08-17 14:22:57');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `uploaded_by` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `food_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `description`, `image`, `uploaded_by`, `created_at`, `food_name`) VALUES
(15, 'wines ', 'R (3).jfif', 'manjyeel', '2023-08-16 16:41:48', 'ok'),
(18, 'peri peri pizza', 'R (2).jfif', 'manjyeel', '2023-08-16 16:47:36', 'tasty'),
(19, 'Eat it till you love it', 'OIP (8).jfif', 'manjyeel', '2023-08-16 16:49:55', 'Latest new food '),
(26, 'See this hotel food is tasty an sexy', 'OIP (12).jfif', 'kritish', '2023-08-17 10:08:41', 'Khatra foodieeeeeee'),
(27, 'Just a test', 'Screenshot 2023-08-16 212033.png', 'manjyeel', '2023-08-17 14:25:31', 'exact pixel to upload');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `username`, `password`, `profile_picture`) VALUES
(3, 'kritish neupane', 'kirtish@gmail.com', 'kritish', '$2y$10$Ztgo8uFAgu1cyCmpItro2uwgp0xiDI3J87xFWlWqgMs1EeS9Qmki.', 'profiles/7G4A2000 (2).jpg'),
(5, 'Manjyeel', 'manjyeel@gmail.com', 'manjyeel', '$2y$10$9L0ALygMneEs5GS6j61lMeosMR8TXlbEANLdu1YvPJNh1hZRBiLDi', 'profiles/OIP (6).jfif'),
(6, 'kosis', 'kosis@gmail.com', 'kosis', '$2y$10$XNT8c/hdXbBHPrMd26ZWv.UlEE72l7C0ypj8XkdHyAchCLW/Lsd12', NULL),
(7, 'ryan', 'ryan@gmail.com', 'ryan', '$2y$10$/fQDrX611Z/smKXahBMHvO/yR.3kaHZy7HHfx9x3E/7ja4j/VHAUC', NULL),
(8, 'kule', 'kule@khule.com', 'kule', '$2y$10$wgZ0WgMkzxIHhjeMlxAVpuSTF/h6i7iL4B7jXSnOC/xGpreLi2oYS', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
