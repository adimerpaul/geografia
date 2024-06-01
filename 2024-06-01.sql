-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-06-2024 a las 08:17:49
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `geografica`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agenda`
--

CREATE TABLE `agenda` (
  `id` int(11) NOT NULL,
  `idbrigada` int(11) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `fecha_agenda` date NOT NULL,
  `observacion` varchar(200) NOT NULL DEFAULT 'SIN OBSERVACION'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `agenda`
--

INSERT INTO `agenda` (`id`, `idbrigada`, `fecha_registro`, `fecha_agenda`, `observacion`) VALUES
(9, 4, '2024-06-01 06:02:17', '2024-06-07', ''),
(10, 4, '2024-06-01 06:31:35', '2024-05-31', 'zzzz'),
(11, 2, '2024-06-01 06:32:12', '2024-05-31', ''),
(12, 1, '2024-06-01 06:32:50', '2024-05-29', 'yyyyy'),
(13, 2, '2024-06-01 06:43:00', '2024-06-01', 'bbb'),
(14, 1, '2024-06-01 07:13:13', '2024-06-01', 'REVISION EXASTIVA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `brigada`
--

CREATE TABLE `brigada` (
  `id` int(11) NOT NULL,
  `tipo` varchar(30) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `brigada`
--

INSERT INTO `brigada` (`id`, `tipo`, `descripcion`, `username`, `password`, `role`) VALUES
(1, 'ADMIN', 'ADIMER PAUL CHAMBI AJATA', 'admin', 'admin', 'ADMIN'),
(2, 'MANTENIMIENTO', 'ELIAS Y AS', 'elias', 'elias', 'BRIGADA'),
(4, 'CONSULTOR MOVIL', 'SAN JUAN DE DIOS', 'juan', 'juan', 'BRIGADA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entidad`
--

CREATE TABLE `entidad` (
  `id` int(10) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `prioridad` int(11) NOT NULL DEFAULT 0,
  `latitud` varchar(255) NOT NULL,
  `longitud` varchar(255) NOT NULL,
  `tipo` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `entidad`
--

INSERT INTO `entidad` (`id`, `descripcion`, `prioridad`, `latitud`, `longitud`, `tipo`) VALUES
(7, 'SANCRISTOBAL', 1, '-17.968506920094086', '-67.11437559140904', 'HOSPITAL'),
(15, 'HOSPITAL KOREA ', 1, '-17.976850142986493', '-67.10004293921885', 'HOSPITAL'),
(16, 'SANCRISTOBAL ZZ', 0, '-17.964294401322324', '-67.10766792297365', 'HOSPITAL'),
(17, 'PLAZA EDUARDO AVAROA', 0, '-17.964662925764614', '-67.11304306983949', 'CINFA'),
(18, 'SEBASTIAN PAGADOR', 1, '-17.951456163071285', '-67.11073637008668', 'EXTERNO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ruta`
--

CREATE TABLE `ruta` (
  `id` int(11) NOT NULL,
  `idagenda` int(11) NOT NULL,
  `identidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `ruta`
--

INSERT INTO `ruta` (`id`, `idagenda`, `identidad`) VALUES
(2, 9, 7),
(3, 9, 15),
(4, 9, 16),
(5, 10, 15),
(6, 10, 16),
(7, 11, 15),
(8, 12, 16),
(20, 13, 7),
(21, 13, 15),
(22, 14, 7),
(23, 14, 15),
(24, 14, 16),
(25, 14, 17),
(26, 14, 18);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `agenda`
--
ALTER TABLE `agenda`
  ADD PRIMARY KEY (`id`),
  ADD KEY `agenda_ibfk_1` (`idbrigada`);

--
-- Indices de la tabla `brigada`
--
ALTER TABLE `brigada`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `entidad`
--
ALTER TABLE `entidad`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ruta`
--
ALTER TABLE `ruta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idagenda` (`idagenda`),
  ADD KEY `identidad` (`identidad`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `agenda`
--
ALTER TABLE `agenda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `brigada`
--
ALTER TABLE `brigada`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `entidad`
--
ALTER TABLE `entidad`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `ruta`
--
ALTER TABLE `ruta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `agenda`
--
ALTER TABLE `agenda`
  ADD CONSTRAINT `agenda_ibfk_1` FOREIGN KEY (`idbrigada`) REFERENCES `brigada` (`ID`);

--
-- Filtros para la tabla `ruta`
--
ALTER TABLE `ruta`
  ADD CONSTRAINT `ruta_ibfk_1` FOREIGN KEY (`idagenda`) REFERENCES `agenda` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ruta_ibfk_2` FOREIGN KEY (`identidad`) REFERENCES `entidad` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
