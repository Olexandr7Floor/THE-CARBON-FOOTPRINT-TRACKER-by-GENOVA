-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Час створення: Бер 16 2025 р., 08:07
-- Версія сервера: 10.4.32-MariaDB
-- Версія PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `hackathon`
--

-- --------------------------------------------------------

--
-- Структура таблиці `conferences`
--

CREATE TABLE `conferences` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` datetime DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп даних таблиці `conferences`
--

INSERT INTO `conferences` (`id`, `title`, `description`, `event_date`, `link`) VALUES
(2, 'Зелена енергія майбутнього', 'Обговорення шляхів впровадження відновлюваної енергетики для зменшення вуглецевого сліду. Ми також говоритимемо про роль громадських ініціатив та міжнародної співпраці у фінансуванні «зеленої» інфраструктури.', '2025-04-11 17:30:00', 'https://meet.google.com/hxm-zcgp-zyy'),
(3, 'Міські екоініціативи', 'Як громади можуть впроваджувати стратегічні плани для скорочення CO₂ у транспорті, побуті та комунальних послугах. Презентація успішних кейсів з різних міст, а також панельна дискусія за участі представників муніципалітетів.', '2025-05-02 10:00:00', 'https://zoom.us/j/123456789'),
(4, 'Агросектор і кліматичні виклики', 'Розглядаємо практичні рішення для сільського господарства: органічні методи, еко-інновації, роль цифровізації у зменшенні вуглецевого сліду. Будуть представлені короткі доповіді фермерів-практиків та дослідників.', '2025-06-15 14:00:00', 'https://meet.google.com/xyz-abcq-tuv'),
(5, 'Бізнес без викидів', 'Круглий стіл зі сталого розвитку для компаній, які прагнуть зменшити свій вуглецевий слід. Обговоримо фінансові інструменти, ESG-звітування, кейси з оптимізації ланцюгів постачання, а також формування культури корпоративної відповідальності.', '2025-07-01 09:30:00', 'https://teams.microsoft.com/l/meetup-join/abc123'),
(10, 'New', 'new', '2025-04-11 17:30:00', 'https://meet.google.com/hxm-zcgp-zyt');

-- --------------------------------------------------------

--
-- Структура таблиці `registration`
--

CREATE TABLE `registration` (
  `id` int(11) NOT NULL,
  `login` text NOT NULL,
  `password` text NOT NULL,
  `country` text NOT NULL,
  `city` text NOT NULL,
  `friend_room` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `registration`
--

INSERT INTO `registration` (`id`, `login`, `password`, `country`, `city`, `friend_room`) VALUES
(1, 'Андрій', '$2y$10$rb.g3AvJ95/L812WDsYezOQQPQg8ZJLTjVAsgVtAtwFrlYveGv8Je', 'Ukraine', 'Kyiv', NULL),
(2, 'Міша', '$2y$10$XzULu9yNGLlTntgO0.O0PeWazePsBzYnUMNQC0DFASvi61qyxSazm', 'Spain', 'Madrid', NULL),
(3, 'Віка', '$2y$10$OpcPebhQ9nnTqc.wznldFOHWcARTAvvRogu3UGXkiFLJBliW5lCnm', 'Ukraine', 'Kyiv', NULL),
(4, 'Саша', '$2y$10$4fIOeXR.yHT4Rvreo/kCt.Xlujg7qNzp.KF2JCFhHP6pYgrTgVgQK', 'Spain', 'Madrid', NULL),
(12, 'New', '$2y$10$UhRxwxJjRlPSygTO2g5FceUj4gK3igAaAQccicim3Cip3wMZVWFUq', 'Ukraine', 'Kyiv', NULL);

-- --------------------------------------------------------

--
-- Структура таблиці `reiting`
--

CREATE TABLE `reiting` (
  `id` int(11) NOT NULL,
  `login` text NOT NULL,
  `slid` varchar(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `reiting`
--

INSERT INTO `reiting` (`id`, `login`, `slid`, `date`) VALUES
(1, 'Андрій', '11', '2025-03-13'),
(2, 'Міша', '23.67', '2025-03-15'),
(3, 'Віка', '6.23', '2025-03-15'),
(4, 'Андрій', '6.23', '2025-02-10'),
(21, 'Міша', '9.23', '2025-03-13'),
(22, 'Віка', '12.23', '2025-03-14'),
(24, 'Саша', '9.38', '2025-03-15'),
(45, 'New', '11.50', '2025-03-16'),
(46, 'New', '1.21', '2025-03-16');

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `conferences`
--
ALTER TABLE `conferences`
  ADD PRIMARY KEY (`id`);

--
-- Індекси таблиці `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`id`);

--
-- Індекси таблиці `reiting`
--
ALTER TABLE `reiting`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `conferences`
--
ALTER TABLE `conferences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблиці `registration`
--
ALTER TABLE `registration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблиці `reiting`
--
ALTER TABLE `reiting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
