-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Янв 02 2017 г., 12:58
-- Версия сервера: 5.5.53-0+deb8u1
-- Версия PHP: 5.6.29-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
-- Структура таблицы `buttons`
--

CREATE TABLE `buttons` (
  `inpin` tinyint(2) NOT NULL COMMENT 'номер входного пина',
  `outpin` char(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'может быть несколько через запятую'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Привязки пинов входных к выходным';

-- --------------------------------------------------------

--
-- Структура таблицы `pins`
--

CREATE TABLE `pins` (
  `pin` tinyint(2) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `direction` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Номера пинов, их имена, направление (ввод-вывод) и статусы' ROW_FORMAT=COMPACT;

--
-- Дамп данных таблицы `pins`
--

INSERT INTO `pins` (`pin`, `name`, `direction`, `status`) VALUES
(1, 'Главный рубильник', 'input', 0),
(2, 'Ёлка', 'output', 1),
(3, '', 'none', 1),
(4, '', 'none', 1),
(5, '', 'none', 1),
(6, 'Туалет', 'output', 0),
(7, '', 'none', 1),
(8, '', 'none', 1),
(9, '', 'none', 1),
(10, '', 'none', 1),
(11, '', 'none', 1),
(12, '', 'none', 1),
(13, 'Ванная', 'output', 0),
(14, 'Спальня', 'output', 0),
(15, 'Коридор', 'output', 0),
(16, 'Прихожая', 'output', 0),
(17, '', 'none', 1),
(18, '', 'none', 1),
(19, 'Зал', 'output', 1),
(20, '', 'none', 1),
(21, '', 'none', 1),
(22, '', 'none', 1),
(23, '', 'none', 1),
(24, '', 'none', 1),
(25, '', 'none', 1),
(26, 'Кухня', 'output', 1),
(27, '', 'none', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `plan`
--

CREATE TABLE `plan` (
  `id` int(10) NOT NULL COMMENT 'id строки плана',
  `pin` tinyint(2) NOT NULL COMMENT 'выходой пин',
  `ontime` char(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'время включения (hh:mm:ss)',
  `offtime` char(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'время выключения (hh:mm:ss)',
  `calendar` int(1) NOT NULL DEFAULT '3' COMMENT '1-будни, 2-выхи, 3-ежедневно'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='таблица планировщика';

--
-- Дамп данных таблицы `plan`
--

INSERT INTO `plan` (`id`, `pin`, `ontime`, `offtime`, `calendar`) VALUES
(4, 9, '11:11:11', '20:13:13', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `user_id` tinyint(2) NOT NULL COMMENT 'номер пользователя',
  `login` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'логин',
  `password` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'мд5 пароля',
  `username` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'нормальное имя (Вася)',
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'последняя авторизация'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Таблица пользователей: лоuины, пароли и тд';

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `buttons`
--
ALTER TABLE `buttons`
  ADD PRIMARY KEY (`inpin`);

--
-- Индексы таблицы `pins`
--
ALTER TABLE `pins`
  ADD PRIMARY KEY (`pin`);

--
-- Индексы таблицы `plan`
--
ALTER TABLE `plan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `plan`
--
ALTER TABLE `plan`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id строки плана', AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `user_id` tinyint(2) NOT NULL AUTO_INCREMENT COMMENT 'номер пользователя';
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
