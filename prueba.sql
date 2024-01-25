-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3307
-- Tiempo de generación: 25-01-2024 a las 21:43:57
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `prueba`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citizens`
--

CREATE TABLE `citizens` (
  `citizenId` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `surname` varchar(100) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `citizens`
--

INSERT INTO `citizens` (`citizenId`, `userId`, `name`, `surname`, `phone`, `address`) VALUES
(1, 3, 'CitizenName', 'CitizenSurname', '123456789', 'CitizenAddress');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rescuers`
--

CREATE TABLE `rescuers` (
  `rescuerId` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `name` varchar(45) NOT NULL,
  `surname` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rescuers`
--

INSERT INTO `rescuers` (`rescuerId`, `userId`, `name`, `surname`) VALUES
(1, 2, 'rescuerName', 'rescuerSurname');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `userId` int(11) NOT NULL,
  `username` varchar(45) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','citizen','rescuer') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`userId`, `username`, `password`, `role`) VALUES
(1, 'admin', 'password', 'admin'),
(2, 'rescuer1', 'rescuer', 'rescuer'),
(3, 'citizen1', 'citizen', 'citizen');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `citizens`
--
ALTER TABLE `citizens`
  ADD PRIMARY KEY (`citizenId`),
  ADD UNIQUE KEY `userId` (`userId`);

--
-- Indices de la tabla `rescuers`
--
ALTER TABLE `rescuers`
  ADD PRIMARY KEY (`rescuerId`),
  ADD UNIQUE KEY `userId` (`userId`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `citizens`
--
ALTER TABLE `citizens`
  MODIFY `citizenId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `rescuers`
--
ALTER TABLE `rescuers`
  MODIFY `rescuerId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citizens`
--
ALTER TABLE `citizens`
  ADD CONSTRAINT `citizens_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`);

--
-- Filtros para la tabla `rescuers`
--
ALTER TABLE `rescuers`
  ADD CONSTRAINT `rescuers_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
