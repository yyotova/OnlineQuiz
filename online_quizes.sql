-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Време на генериране: 17 юни 2021 в 16:16
-- Версия на сървъра: 10.4.19-MariaDB
-- Версия на PHP: 8.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данни: `online_quizes`
--

-- --------------------------------------------------------

--
-- Структура на таблица `answers`
--

CREATE TABLE `answers` (
  `id` varchar(124) NOT NULL,
  `content` text NOT NULL,
  `is_correct` tinyint(1) NOT NULL,
  `question_id` varchar(124) NOT NULL,
  `is_text` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `levels`
--

CREATE TABLE `levels` (
  `id` varchar(124) NOT NULL,
  `name` varchar(124) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Схема на данните от таблица `levels`
--

INSERT INTO `levels` (`id`, `name`) VALUES
('07f32f0e-c510-11eb-8529-0242ac130003', 'HARD'),
('d3ca906e-c50f-11eb-8529-0242ac130003', 'EASY'),
('d3ca928a-c50f-11eb-8529-0242ac130003', 'MEDIUM');

-- --------------------------------------------------------

--
-- Структура на таблица `questions`
--

CREATE TABLE `questions` (
  `id` varchar(124) NOT NULL,
  `title` text NOT NULL,
  `points` int(11) NOT NULL,
  `quiz_id` varchar(124) NOT NULL,
  `picture` varchar(124) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `quizes`
--

CREATE TABLE `quizes` (
  `id` varchar(124) NOT NULL,
  `title` varchar(124) NOT NULL,
  `description` text NOT NULL,
  `level_id` varchar(124) NOT NULL,
  `max_score` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Схема на данните от таблица `quizes`
--

INSERT INTO `quizes` (`id`, `title`, `description`, `level_id`, `max_score`) VALUES
('67df61fc-c6ba-11eb-b8bc-0242ac130003', 'PHP', 'OOP with PHP', '07f32f0e-c510-11eb-8529-0242ac130003', 100),
('67df6422-c6ba-11eb-b8bc-0242ac130003', 'SQL', 'Basic Queries', 'd3ca906e-c50f-11eb-8529-0242ac130003', 100);

-- --------------------------------------------------------

--
-- Структура на таблица `roles`
--

CREATE TABLE `roles` (
  `id` varchar(124) NOT NULL,
  `name` varchar(124) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Схема на данните от таблица `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
('84b2b226-c46b-11eb-8529-0242ac130003', 'creator'),
('9ed693c0-c46b-11eb-8529-0242ac130003', 'test checker');

-- --------------------------------------------------------

--
-- Структура на таблица `users`
--

CREATE TABLE `users` (
  `id` varchar(124) NOT NULL,
  `name` varchar(124) NOT NULL,
  `family_name` varchar(124) NOT NULL,
  `email` varchar(124) NOT NULL,
  `password` varchar(15) NOT NULL,
  `number` varchar(124) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `role_id` varchar(124) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Схема на данните от таблица `users`
--

INSERT INTO `users` (`id`, `name`, `family_name`, `email`, `password`, `number`, `enabled`, `role_id`) VALUES
('60bca4a76d933', 'Anni', 'Yotova', 'anni@gmail.com', '$2y$10$PAGOAoV.', '0881234567', 1, '84b2b226-c46b-11eb-8529-0242ac130003'),
('60bcb2d799462', 'Pufi', 'Staneva', 'staneva@gmail.com', '$2y$10$HiBySOWV', '0881234123', 1, '84b2b226-c46b-11eb-8529-0242ac130003');

-- --------------------------------------------------------

--
-- Структура на таблица `users_answers`
--

CREATE TABLE `users_answers` (
  `id` varchar(124) NOT NULL,
  `user_id` varchar(124) NOT NULL,
  `answer_id` varchar(124) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `user_info`
--

CREATE TABLE `user_info` (
  `id` varchar(124) NOT NULL,
  `user_id` varchar(124) NOT NULL,
  `quiz_id` varchar(124) NOT NULL,
  `user_score` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `questions`
  ADD record blob;

--
-- Схема на данните от таблица `user_info`
--

INSERT INTO `user_info` (`id`, `user_id`, `quiz_id`, `user_score`) VALUES
('67df6512-c6ba-11eb-b8bc-0242ac130003', '60bca4a76d933', '67df61fc-c6ba-11eb-b8bc-0242ac130003', 60);

--
-- Indexes for dumped tables
--

--
-- Индекси за таблица `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `answer_question_fk` (`question_id`);

--
-- Индекси за таблица `levels`
--
ALTER TABLE `levels`
  ADD PRIMARY KEY (`id`);

--
-- Индекси за таблица `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_quiz_fk` (`quiz_id`);

--
-- Индекси за таблица `quizes`
--
ALTER TABLE `quizes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `level_quiz_fk` (`level_id`);

--
-- Индекси за таблица `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Индекси за таблица `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_role_fk` (`role_id`);

--
-- Индекси за таблица `users_answers`
--
ALTER TABLE `users_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users` (`user_id`),
  ADD KEY `answers` (`answer_id`);

--
-- Индекси за таблица `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_quiz_fk` (`quiz_id`),
  ADD KEY `user_info_fk` (`user_id`);

--
-- Ограничения за дъмпнати таблици
--

--
-- Ограничения за таблица `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answer_question_fk` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Ограничения за таблица `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `question_quiz_fk` FOREIGN KEY (`quiz_id`) REFERENCES `quizes` (`id`) ON DELETE CASCADE;

--
-- Ограничения за таблица `quizes`
--
ALTER TABLE `quizes`
  ADD CONSTRAINT `level_quiz_fk` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`) ON DELETE CASCADE;

--
-- Ограничения за таблица `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `user_role_fk` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Ограничения за таблица `users_answers`
--
ALTER TABLE `users_answers`
  ADD CONSTRAINT `answers` FOREIGN KEY (`answer_id`) REFERENCES `answers` (`id`),
  ADD CONSTRAINT `users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения за таблица `user_info`
--
ALTER TABLE `user_info`
  ADD CONSTRAINT `user_info_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_quiz_fk` FOREIGN KEY (`quiz_id`) REFERENCES `quizes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
