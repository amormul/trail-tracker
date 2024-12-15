-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Дек 13 2024 г., 19:26
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `hikeSite`
--

-- --------------------------------------------------------

--
-- Структура таблицы `difficulties`
--

CREATE TABLE `difficulties` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `gallery`
--

CREATE TABLE `gallery` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `trip_id` int UNSIGNED NOT NULL,
  `deleted_at` timestamp NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `inventory`
--

CREATE TABLE `inventory` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `inventory_trip`
--

CREATE TABLE `inventory_trip` (
  `id` int UNSIGNED NOT NULL,
  `trip_id` int UNSIGNED NOT NULL,
  `inventory_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `likes_photo`
--

CREATE TABLE `likes_photo` (
  `id` bigint UNSIGNED NOT NULL,
  `photo_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `likes_route`
--

CREATE TABLE `likes_route` (
  `id` bigint UNSIGNED NOT NULL,
  `route_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `likes_trip`
--

CREATE TABLE `likes_trip` (
  `id` bigint UNSIGNED NOT NULL,
  `trip_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `routes`
--

CREATE TABLE `routes` (
  `id` int UNSIGNED NOT NULL,
  `trip_id` int UNSIGNED NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `statuses`
--

CREATE TABLE `statuses` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `trips`
--

CREATE TABLE `trips` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `difficulty_id` int UNSIGNED NOT NULL,
  `start_date` timestamp NOT NULL,
  `end_date` timestamp NOT NULL,
  `status_id` int UNSIGNED NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `difficulties`
--
ALTER TABLE `difficulties`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status_id` (`deleted_at`),
  ADD KEY `trip_id` (`trip_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `inventory_trip`
--
ALTER TABLE `inventory_trip`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_id` (`inventory_id`),
  ADD KEY `trip_id` (`trip_id`);

--
-- Индексы таблицы `likes_photo`
--
ALTER TABLE `likes_photo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `photo_id` (`photo_id`);

--
-- Индексы таблицы `likes_route`
--
ALTER TABLE `likes_route`
  ADD PRIMARY KEY (`id`),
  ADD KEY `route_id` (`route_id`);

--
-- Индексы таблицы `likes_trip`
--
ALTER TABLE `likes_trip`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trip_id` (`trip_id`);

--
-- Индексы таблицы `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trip_id` (`trip_id`);

--
-- Индексы таблицы `statuses`
--
ALTER TABLE `statuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `difficulty_id` (`difficulty_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `difficulties`
--
ALTER TABLE `difficulties`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `inventory_trip`
--
ALTER TABLE `inventory_trip`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `likes_photo`
--
ALTER TABLE `likes_photo`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `likes_route`
--
ALTER TABLE `likes_route`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `likes_trip`
--
ALTER TABLE `likes_trip`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `routes`
--
ALTER TABLE `routes`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `statuses`
--
ALTER TABLE `statuses`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `trips`
--
ALTER TABLE `trips`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `gallery`
--
ALTER TABLE `gallery`
  ADD CONSTRAINT `gallery_ibfk_2` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `gallery_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `inventory_trip`
--
ALTER TABLE `inventory_trip`
  ADD CONSTRAINT `inventory_trip_ibfk_1` FOREIGN KEY (`inventory_id`) REFERENCES `inventory` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `inventory_trip_ibfk_2` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `likes_photo`
--
ALTER TABLE `likes_photo`
  ADD CONSTRAINT `likes_photo_ibfk_1` FOREIGN KEY (`photo_id`) REFERENCES `gallery` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `likes_route`
--
ALTER TABLE `likes_route`
  ADD CONSTRAINT `likes_route_ibfk_1` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `likes_trip`
--
ALTER TABLE `likes_trip`
  ADD CONSTRAINT `likes_trip_ibfk_1` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `routes`
--
ALTER TABLE `routes`
  ADD CONSTRAINT `routes_ibfk_1` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `trips`
--
ALTER TABLE `trips`
  ADD CONSTRAINT `trips_ibfk_1` FOREIGN KEY (`difficulty_id`) REFERENCES `difficulties` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `trips_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `trips_ibfk_3` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
