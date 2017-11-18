-- phpMyAdmin SQL Dump
-- version 4.7.5
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Ноя 18 2017 г., 06:47
-- Версия сервера: 5.5.57-0+deb8u1
-- Версия PHP: 5.6.30-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `tatiana`
--

-- --------------------------------------------------------

--
-- Структура таблицы `button_block`
--

CREATE TABLE `button_block` (
  `inpin` tinyint(2) NOT NULL,
  `outpin` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Привязки кнопка-блок';

--
-- Дамп данных таблицы `button_block`
--

INSERT INTO `button_block` (`inpin`, `outpin`) VALUES
(21, 7),
(21, 8);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `button_block`
--
ALTER TABLE `button_block`
  ADD PRIMARY KEY (`outpin`),
  ADD UNIQUE KEY `outpin` (`outpin`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
