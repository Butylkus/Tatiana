-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Янв 03 2017 г., 01:01
-- Версия сервера: 5.5.45
-- Версия PHP: 5.6.23

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
(2, 'Ёлка', 'output', 0),
(3, '', 'none', 1),
(4, '', 'none', 1),
(5, '', 'none', 1),
(6, 'Туалет', 'output', 1),
(7, '', 'none', 1),
(8, '', 'none', 1),
(9, '', 'none', 1),
(10, '', 'none', 1),
(11, '', 'none', 1),
(12, '', 'none', 1),
(13, 'Ванная', 'output', 0),
(14, 'Спальня', 'output', 1),
(15, 'Коридор', 'output', 1),
(16, 'Прихожая', 'output', 1),
(17, '', 'none', 1),
(18, '', 'none', 1),
(19, 'Зал', 'output', 1),
(20, '', 'none', 1),
(21, '', 'none', 1),
(22, '', 'none', 1),
(23, '', 'none', 1),
(24, '', 'none', 1),
(25, '', 'none', 1),
(26, 'Кухня', 'output', 0),
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
  `password` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'мд5 пароля',
  `username` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'нормальное имя (Вася)',
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'последняя авторизация',
  `user_sid` varchar(32) COLLATE utf8_unicode_ci DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Таблица пользователей: лоuины, пароли и тд';

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`user_id`, `login`, `password`, `username`, `last_login`, `user_sid`) VALUES
(1, 'webTester', '827ccb0eea8a706c4c34a16891f84e7b', 'Саныч', '2017-01-02 21:26:26', 'v54mu2nih21f4pmder9cfpp403');

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
  MODIFY `user_id` tinyint(2) NOT NULL AUTO_INCREMENT COMMENT 'номер пользователя', AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
