-- Enhanced Calendar Database Schema
-- This is the complete schema including all calendar enhancements
-- Use this for fresh installations

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `calendario`
--
CREATE DATABASE IF NOT EXISTS `calendario` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE `calendario`;

-- --------------------------------------------------------

--
-- Enhanced structure for table `eventoscalendar`
--

CREATE TABLE `eventoscalendar` (
  `id` int(11) NOT NULL,
  `evento` varchar(250) DEFAULT NULL COMMENT 'Event title',
  `color_evento` varchar(20) DEFAULT NULL COMMENT 'Event color',
  `fecha_inicio` varchar(20) DEFAULT NULL COMMENT 'Start date',
  `fecha_fin` varchar(20) DEFAULT NULL COMMENT 'End date',
  `hora_inicio` time DEFAULT NULL COMMENT 'Event start time',
  `descripcion` text DEFAULT NULL COMMENT 'Event description',
  `es_recurrente` tinyint(1) DEFAULT 0 COMMENT 'Si el evento es recurrente (0=no, 1=si)',
  `dias_semana` varchar(20) DEFAULT NULL COMMENT 'Días de la semana separados por comas (0=Dom, 1=Lun, 2=Mar, 3=Mie, 4=Jue, 5=Vie, 6=Sab)',
  `fecha_fin_recurrencia` date DEFAULT NULL COMMENT 'Fecha límite para generar instancias del evento recurrente',
  `evento_padre_id` int(11) DEFAULT NULL COMMENT 'ID del evento padre si es una instancia de evento recurrente',
  `recurring_group_id` varchar(50) DEFAULT NULL COMMENT 'ID de grupo para eventos recurrentes relacionados'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cumpleanoscalendar`
--

CREATE TABLE `cumpleanoscalendar` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL COMMENT 'Name of the person',
  `dia_nacimiento` int(2) NOT NULL COMMENT 'Birth day (1-31)',
  `mes_nacimiento` int(2) NOT NULL COMMENT 'Birth month (1-12)',
  `color_cumpleanos` varchar(20) DEFAULT '#FF69B4' COMMENT 'Birthday color',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Table for storing birthday information';

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(11) NOT NULL,
  `migration_name` varchar(255) NOT NULL,
  `executed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eventoscalendar`
--
ALTER TABLE `eventoscalendar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_fecha_inicio` (`fecha_inicio`),
  ADD KEY `idx_fecha_fin` (`fecha_fin`),
  ADD KEY `idx_recurrente` (`es_recurrente`),
  ADD KEY `idx_evento_padre` (`evento_padre_id`),
  ADD KEY `idx_recurring_group` (`recurring_group_id`);

--
-- Indexes for table `cumpleanoscalendar`
--
ALTER TABLE `cumpleanoscalendar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_birth_date` (`dia_nacimiento`,`mes_nacimiento`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `migration_name` (`migration_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eventoscalendar`
--
ALTER TABLE `eventoscalendar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cumpleanoscalendar`
--
ALTER TABLE `cumpleanoscalendar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;