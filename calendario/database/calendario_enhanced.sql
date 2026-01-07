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
  `descripcion` text DEFAULT NULL COMMENT 'Event description'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cumpleañoscalendar`
--

CREATE TABLE `cumpleañoscalendar` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL COMMENT 'Name of the person',
  `dia_nacimiento` int(2) NOT NULL COMMENT 'Birth day (1-31)',
  `mes_nacimiento` int(2) NOT NULL COMMENT 'Birth month (1-12)',
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
  ADD KEY `idx_fecha_fin` (`fecha_fin`);

--
-- Indexes for table `cumpleañoscalendar`
--
ALTER TABLE `cumpleañoscalendar`
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
-- AUTO_INCREMENT for table `cumpleañoscalendar`
--
ALTER TABLE `cumpleañoscalendar`
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