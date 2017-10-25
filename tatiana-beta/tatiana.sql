-- Время создания: Окт 25 2017 г., 10:08


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
  `outpin` tinyint(2) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Привязки кнопка-блок';

--
-- Дамп данных таблицы `button_block`
--

INSERT INTO `button_block` (`inpin`, `outpin`, `id`) VALUES
(21, 7, 1),
(21, 8, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `button_device`
--

CREATE TABLE `button_device` (
  `inpin` tinyint(2) NOT NULL COMMENT 'номер входного пина',
  `outpin` tinyint(2) NOT NULL COMMENT 'номер выходного пина'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Привязки кнопка = ОДИН ДЕВАЙС' ROW_FORMAT=COMPACT;

--
-- Дамп данных таблицы `button_device`
--

INSERT INTO `button_device` (`inpin`, `outpin`) VALUES
(1, 11);

-- --------------------------------------------------------

--
-- Структура таблицы `device_stat`
--

CREATE TABLE `device_stat` (
  `pin` tinyint(2) NOT NULL COMMENT 'Пин подвешенного устройства',
  `name` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Имя устройства',
  `activity` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active' COMMENT 'архивный/активный девайс',
  `lastmodified` int(255) NOT NULL COMMENT 'Метка последнего изменения статуса',
  `operationtime` int(255) NOT NULL COMMENT 'Время работы устройства'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Статистика использования устройств';

-- --------------------------------------------------------

--
-- Структура таблицы `dht_data`
--

CREATE TABLE `dht_data` (
  `id` int(11) NOT NULL,
  `pin` int(2) NOT NULL,
  `temperature` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `humidity` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` varchar(10) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Данные DHT-сенсоров';

--
-- Дамп данных таблицы `dht_data`
--

INSERT INTO `dht_data` (`id`, `pin`, `temperature`, `humidity`, `timestamp`) VALUES
(1, 3, '13.66', '66.60', '1508914187');

-- --------------------------------------------------------

--
-- Структура таблицы `dht_sensors`
--

CREATE TABLE `dht_sensors` (
  `pin` int(11) NOT NULL,
  `model` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Список датчиков температуры и влажности' ROW_FORMAT=COMPACT;

--
-- Дамп данных таблицы `dht_sensors`
--

INSERT INTO `dht_sensors` (`pin`, `model`) VALUES
(3, 22);

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
(1, 'Первый', 'input', 0),
(2, 'Второй', 'none', 1),
(3, 'Третий', 'dht', 1),
(4, 'Четвёртый', 'dht', 1),
(5, 'Пятый', 'none', 1),
(6, 'Шестой', 'none', 1),
(7, 'Седьмой', 'output', 0),
(8, 'Восьмой', 'output', 0),
(9, 'Девятый', 'none', 0),
(10, 'Десятый', 'none', 1),
(11, 'Одиннадцатый', 'none', 1),
(12, 'Двенадцатый', 'none', 1),
(13, 'Тринадцатый', 'none', 1),
(14, 'Четырнадцатый', 'output', 0),
(15, 'Пятнадцатый', 'output', 0),
(16, 'Шестнадцатый', 'none', 1),
(17, 'Семнадцатый', 'none', 0),
(18, 'Восемнадцатый', 'output', 0),
(19, 'Девятнадцатый', 'none', 1),
(20, 'Двадцатый', 'none', 1),
(21, 'Двадцать первый', 'block', 1),
(22, 'Двадцать второй', 'none', 1),
(23, 'Двадцать третий', 'pir', 0),
(24, 'Двадцать четвёртый', 'output', 0),
(25, 'Двадцать пятый', 'output', 0),
(26, 'Двадцать шестой', 'none', 1),
(27, 'Двадцать седьмой', 'none', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `pir_data`
--

CREATE TABLE `pir_data` (
  `id` int(8) NOT NULL,
  `message` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Данные PIR-сенсоров и камер';


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
(2, 25, '10:48:45', '12:50:32', 1),
(3, 24, '20:15:55', '20:20:40', 1),
(4, 15, '06:00:00', '07:20:00', 3);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `user_id` tinyint(2) NOT NULL COMMENT 'номер пользователя',
  `login` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'логин',
  `password` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'мд5 пароля',
  `username` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'нормальное имя (Вася)',
  `last_login` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'последняя авторизация',
  `user_sid` varchar(32) COLLATE utf8_unicode_ci DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Таблица пользователей: лоuины, пароли и тд';

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`user_id`, `login`, `password`, `username`, `last_login`, `user_sid`) VALUES
(1, 'tester', 'f5d1278e8109edd94e1e4197e04873b9', 'Тестировщик', '13.10.2017 12:20:24', 'upv0r5l94h1cm8o0em6j1vcdm2');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `button_block`
--
ALTER TABLE `button_block`
  ADD UNIQUE KEY `id` (`id`);

--
-- Индексы таблицы `button_device`
--
ALTER TABLE `button_device`
  ADD PRIMARY KEY (`inpin`);

--
-- Индексы таблицы `dht_data`
--
ALTER TABLE `dht_data`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `dht_sensors`
--
ALTER TABLE `dht_sensors`
  ADD UNIQUE KEY `pin` (`pin`);

--
-- Индексы таблицы `pins`
--
ALTER TABLE `pins`
  ADD PRIMARY KEY (`pin`);

--
-- Индексы таблицы `pir_data`
--
ALTER TABLE `pir_data`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT для таблицы `button_block`
--
ALTER TABLE `button_block`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `dht_data`
--
ALTER TABLE `dht_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `pir_data`
--
ALTER TABLE `pir_data`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `plan`
--
ALTER TABLE `plan`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id строки плана', AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `user_id` tinyint(2) NOT NULL AUTO_INCREMENT COMMENT 'номер пользователя', AUTO_INCREMENT=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
