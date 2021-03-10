-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Фев 24 2021 г., 08:23
-- Версия сервера: 10.4.17-MariaDB
-- Версия PHP: 7.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `igam`
--

-- --------------------------------------------------------

--
-- Структура таблицы `TLogin_api_tokens`
--

CREATE TABLE `TLogin_api_tokens` (
  `id` int(11) NOT NULL,
  `owner` int(11) NOT NULL,
  `title` text NOT NULL,
  `token` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `TLogin_api_tokens`
--

INSERT INTO `TLogin_api_tokens` (`id`, `owner`, `title`, `token`) VALUES
(1, 807, 'THE HUB', '1d952aad0be1b2b1465f44786a0c2a79');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `TLogin_api_tokens`
--
ALTER TABLE `TLogin_api_tokens`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `TLogin_api_tokens`
--
ALTER TABLE `TLogin_api_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
