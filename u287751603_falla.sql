-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 27, 2026 at 08:29 AM
-- Server version: 11.8.6-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u287751603_falla`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `accion` varchar(150) NOT NULL,
  `modulo` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `accion`, `modulo`, `descripcion`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 15:27:58'),
(2, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 15:30:40'),
(3, 1, 'update', 'falleros', 'Fallero actualizado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 15:31:01'),
(4, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 15:32:17'),
(5, 1, 'approve', 'falleros', 'Solicitud de fallero aprobada', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 15:32:29'),
(6, 4, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 15:32:44'),
(7, 2, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 15:35:03'),
(8, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 15:38:01'),
(9, 2, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 15:40:21'),
(10, 2, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:11:17'),
(11, 2, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:11:21'),
(12, 4, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:15:12'),
(13, 4, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:19:25'),
(14, 2, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:19:47'),
(15, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:21:47'),
(16, 1, 'save', 'actos', 'Acto guardado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:22:38'),
(17, 4, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:30:10'),
(18, 4, 'cambiar_password', 'perfil', 'Cambio de contraseña desde Mi perfil', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:30:43'),
(19, 4, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:31:00'),
(20, 4, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:36:37'),
(21, 4, 'cancel', 'reservas', 'Reserva cancelada por fallero', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:37:16'),
(22, 4, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:37:43'),
(23, 4, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:37:45'),
(24, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:38:53'),
(25, 1, 'save', 'avisos', 'Aviso guardado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:39:26'),
(26, 1, 'save', 'actos', 'Acto guardado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:43:35'),
(27, 4, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:43:54'),
(28, 4, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:44:10'),
(29, 4, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:44:20'),
(30, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:51:01'),
(31, 4, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:59:29'),
(32, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:59:39'),
(33, 4, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', '2026-05-09 17:20:42'),
(34, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-09 17:26:42'),
(35, 4, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-09 17:27:15'),
(36, 2, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 17:30:46'),
(37, 2, 'login', 'auth', 'Inicio de sesión correcto', '212.230.117.129', 'Mozilla/5.0 (Linux; Android 12; Redmi Note 9 Pro Build/SKQ1.211019.001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.7049.79 Mobile Safari/537.36 XiaoMi/MiuiBrowser/14.54.0-gn', '2026-05-09 22:51:25'),
(38, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-10 16:46:33'),
(39, 4, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-10 16:48:26'),
(40, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 17:42:45'),
(41, 4, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 17:46:47'),
(42, 1, 'save', 'avisos', 'Aviso guardado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 17:47:37'),
(43, 4, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-10 17:55:56'),
(44, 4, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 17:57:31'),
(45, 4, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-10 17:59:28'),
(46, 1, 'save', 'avisos', 'Aviso guardado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:02:10'),
(47, 1, 'save', 'avisos', 'Aviso guardado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:02:49'),
(48, 1, 'save', 'actos', 'Acto guardado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:06:00'),
(49, 4, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-10 18:06:14'),
(50, 1, 'save', 'actos', 'Acto guardado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:06:42'),
(51, 1, 'save', 'actos', 'Acto guardado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:09:28'),
(52, 1, 'save', 'avisos', 'Aviso guardado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:10:27'),
(53, 1, 'delete', 'avisos', 'Aviso eliminado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:10:50'),
(54, 1, 'delete', 'avisos', 'Aviso eliminado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:10:52'),
(55, 1, 'delete', 'avisos', 'Aviso eliminado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:10:54'),
(56, 1, 'delete', 'avisos', 'Aviso eliminado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:10:57'),
(57, 1, 'delete', 'avisos', 'Aviso eliminado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:10:59'),
(58, 1, 'save', 'avisos', 'Aviso guardado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:12:53'),
(59, 4, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-10 18:26:45'),
(60, 1, 'save', 'avisos', 'Aviso guardado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:27:12'),
(61, 1, 'delete', 'avisos', 'Aviso eliminado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:27:31'),
(62, 1, 'save', 'avisos', 'Aviso guardado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:27:39'),
(63, 1, 'delete', 'actos', 'Acto eliminado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:27:59'),
(64, 1, 'delete', 'actos', 'Acto eliminado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:28:02'),
(65, 1, 'delete', 'actos', 'Acto eliminado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:28:09'),
(66, 1, 'save', 'actos', 'Acto guardado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:28:42'),
(67, 1, 'save', 'actos', 'Acto guardado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:29:30'),
(68, 4, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '88.19.52.253', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-10 18:30:03'),
(69, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-10 18:31:30'),
(70, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:33:33'),
(71, 2, 'login', 'auth', 'Inicio de sesión correcto', '139.47.20.20', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-05-11 10:44:44'),
(72, 2, 'login', 'auth', 'Inicio de sesión correcto', '88.17.215.19', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-05-11 18:46:26'),
(73, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.17.215.19', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-05-11 18:47:59'),
(74, 2, 'login', 'auth', 'Inicio de sesión correcto', '88.20.210.228', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', '2026-05-12 08:02:26'),
(75, 1, 'login', 'auth', 'Inicio de sesión correcto', '84.125.71.29', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-05-12 08:08:40'),
(76, 1, 'login', 'auth', 'Inicio de sesión correcto', '84.123.208.144', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-12 11:12:27'),
(77, 1, 'login', 'auth', 'Inicio de sesión correcto', '84.123.208.144', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-12 11:13:00'),
(78, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.18.157.181', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-14 16:58:43'),
(79, 1, 'login', 'auth', 'Inicio de sesión correcto', '176.80.69.31', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-14 16:59:57'),
(80, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.18.157.181', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-14 17:07:19'),
(81, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.18.157.181', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-14 17:11:12'),
(82, 1, 'login', 'auth', 'Inicio de sesión correcto', '84.125.74.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36 EdgA/147.0.0.0', '2026-05-14 17:12:38'),
(83, 2, 'login', 'auth', 'Inicio de sesión correcto', '84.125.74.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36 EdgA/147.0.0.0', '2026-05-14 17:18:20'),
(84, 2, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '84.125.74.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36 EdgA/147.0.0.0', '2026-05-14 17:19:16'),
(85, 2, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '84.125.74.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36 EdgA/147.0.0.0', '2026-05-14 17:19:33'),
(86, 4, 'login', 'auth', 'Inicio de sesión correcto', '88.18.157.181', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-14 17:19:51'),
(87, 4, 'login', 'auth', 'Inicio de sesión correcto', '88.18.157.181', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-14 17:20:08'),
(88, 2, 'login', 'auth', 'Inicio de sesión correcto', '88.17.215.19', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-05-14 17:30:05'),
(89, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.18.157.181', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-14 17:31:34'),
(90, 2, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '88.17.215.19', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-05-14 17:31:40'),
(91, 1, 'update', 'falleros', 'Fallero actualizado', '88.18.157.181', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-14 17:31:54'),
(92, 2, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '88.17.215.19', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-05-14 17:32:17'),
(93, 2, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '88.17.215.19', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-05-14 17:32:23'),
(94, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.17.215.19', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-05-14 17:32:59'),
(95, 1, 'update', 'reservas', 'Estado de reserva actualizado', '88.17.215.19', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-05-14 17:33:52'),
(96, 2, 'login', 'auth', 'Inicio de sesión correcto', '84.127.38.152', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-05-14 20:52:33'),
(97, 2, 'login', 'auth', 'Inicio de sesión correcto', '84.125.71.41', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-05-15 20:38:31'),
(98, 2, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '84.125.71.41', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-05-15 20:38:59'),
(99, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-21 08:23:21'),
(100, 1, 'save', 'actos', 'Acto guardado', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-21 14:12:39'),
(101, 1, 'save', 'actos', 'Acto guardado', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-21 14:13:47'),
(102, 1, 'save', 'actos', 'Acto guardado', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-21 14:19:19'),
(103, 1, 'login', 'auth', 'Inicio de sesión correcto', '2a02:9130:8535:be9b:a0e4:25b3:5dde:8481', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-22 11:11:43'),
(104, 1, 'save', 'actos', 'Acto guardado', '2a02:9130:8535:be9b:a0e4:25b3:5dde:8481', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-22 11:13:55'),
(105, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.18.157.181', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-22 22:25:45'),
(106, 4, 'login', 'auth', 'Inicio de sesión correcto', '88.18.157.181', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-22 22:26:05'),
(107, 1, 'login', 'auth', 'Inicio de sesión correcto', '2a02:9130:852f:6856:cd8f:2ae:ffc5:9ecc', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-24 12:18:04'),
(108, 2, 'login', 'auth', 'Inicio de sesión correcto', '84.125.69.84', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36 EdgA/148.0.0.0', '2026-05-26 17:15:04'),
(109, 1, 'login', 'auth', 'Inicio de sesión correcto', '84.125.125.76', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-26 17:24:57'),
(110, 4, 'login', 'auth', 'Inicio de sesión correcto', '84.125.125.76', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-26 17:38:43'),
(111, 4, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '84.125.125.76', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-26 17:47:59'),
(112, 4, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '84.125.125.76', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-26 17:48:20'),
(113, 1, 'login', 'auth', 'Inicio de sesión correcto', '84.125.125.76', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-26 17:48:59'),
(114, 4, 'login', 'auth', 'Inicio de sesión correcto', '2a02:9130:802a:6b3f:2cab:eae4:8c45:bdb9', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-26 17:49:36'),
(115, 1, 'save', 'actos', 'Acto guardado', '84.125.125.76', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-26 17:51:08'),
(116, 4, 'cancel', 'reservas', 'Reserva cancelada por fallero', '2a02:9130:802a:6b3f:2cab:eae4:8c45:bdb9', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-26 17:51:31'),
(117, 4, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '2a02:9130:802a:6b3f:2cab:eae4:8c45:bdb9', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-26 17:52:48'),
(118, 4, 'login', 'auth', 'Inicio de sesión correcto', '84.125.125.76', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-26 17:54:03'),
(119, 1, 'login', 'auth', 'Inicio de sesión correcto', '84.125.125.76', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-26 17:57:46'),
(120, 1, 'save', 'actos', 'Acto guardado', '84.125.125.76', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-26 17:58:52'),
(121, 1, 'save', 'avisos', 'Aviso guardado', '84.125.125.76', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-26 17:59:16'),
(122, 1, 'login', 'auth', 'Inicio de sesión correcto', '84.125.125.76', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-26 18:08:37'),
(123, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.17.215.19', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-26 19:49:19'),
(124, 2, 'login', 'auth', 'Inicio de sesión correcto', '88.17.215.19', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-26 19:52:48'),
(125, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.18.157.181', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-26 23:17:33'),
(126, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-27 07:06:42'),
(127, 1, 'pago', 'reservas', 'Reserva marcada como pagada', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-27 07:17:11'),
(128, 4, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-27 07:17:40'),
(129, 4, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-27 07:40:00'),
(130, 4, 'login', 'auth', 'Inicio de sesión correcto', '2a02:9130:80a6:9ca4:9433:da01:b9ce:ea3e', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-27 07:44:07'),
(131, 4, 'create', 'reservas', 'Reserva realizada o actualizada por fallero o representante familiar', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-27 08:05:22'),
(132, 4, 'create', 'reservas', 'Reserva realizada o actualizada por fallero o representante familiar', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-27 08:05:48'),
(133, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-27 08:06:18'),
(134, 1, 'pago', 'reservas', 'Reserva marcada como pagada', '88.21.212.211', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-27 08:06:45'),
(135, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-27 08:07:51'),
(136, 4, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-27 08:08:51'),
(137, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-27 08:19:16'),
(138, 1, 'save', 'familias', 'Familia guardada', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-27 08:22:29'),
(139, 1, 'save', 'familias', 'Familia guardada', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-27 08:22:41');

-- --------------------------------------------------------

--
-- Table structure for table `actos`
--

CREATE TABLE `actos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(180) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha` date NOT NULL,
  `hora` time DEFAULT NULL,
  `ubicacion` varchar(255) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `tipo` enum('comida','cena','pasacalles','reunion','especial') NOT NULL,
  `max_plazas` int(11) DEFAULT NULL,
  `estado` enum('abierto','cerrado','cancelado') NOT NULL DEFAULT 'abierto',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `actos`
--

INSERT INTO `actos` (`id`, `titulo`, `descripcion`, `fecha`, `hora`, `ubicacion`, `imagen`, `tipo`, `max_plazas`, `estado`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Comida sábado 15 marzo', 'Comida popular de fallas.', '2026-03-15', '14:00:00', 'Casal fallero', NULL, 'comida', 200, 'abierto', 1, '2026-05-09 15:26:54', NULL),
(3, 'Pasacalles general', 'Pasacalles por el barrio.', '2026-03-17', '11:00:00', 'Plaza principal', NULL, 'pasacalles', NULL, 'abierto', 1, '2026-05-09 15:26:54', NULL),
(4, 'Cena Proclamación', '', '2026-05-09', '22:00:00', 'Moncada', NULL, 'cena', NULL, 'abierto', 1, '2026-05-09 16:22:38', NULL),
(5, 'Cena Prueba', 'Esto es la descripcion de el acto', '2026-05-10', '21:30:00', 'Casal fallero', 'uploads/actos/acto_1779372759_51dd4ea63e1c.jpg', 'comida', NULL, 'abierto', 1, '2026-05-09 16:43:35', '2026-05-21 14:13:47'),
(6, 'Cena Prueba', '', '2026-05-10', '21:30:00', 'Casal fallero', NULL, 'comida', NULL, 'cerrado', 1, '2026-05-10 18:06:00', '2026-05-26 17:51:08'),
(9, 'Cena Pressentación', 'Cena Pressentación', '2026-05-10', '21:30:00', 'Moncada', NULL, 'comida', NULL, 'cerrado', 1, '2026-05-10 18:28:42', '2026-05-10 18:29:30'),
(10, 'Prueba Web', 'Esto es una prueba para la web de la falla san sebastian arzobispo fuero', '2026-05-21', '21:00:00', 'Casal fallero', 'uploads/actos/acto_1779373159_858ebd8c85ad.jpg', 'reunion', NULL, 'abierto', 1, '2026-05-21 14:19:19', NULL),
(11, 'Prueba Web 2', 'Esta es la segunda prueba que hago para la web de la falla San Sebastián arzobispo fuero', '2026-05-22', '13:12:00', 'Godella', 'uploads/actos/acto_1779448435_9632d7a08f56.png', 'comida', NULL, 'abierto', 1, '2026-05-22 11:13:55', NULL),
(12, 'Cena Prueba 8', 'Esto es una prueba', '2026-05-26', NULL, 'Casal', NULL, 'comida', NULL, 'abierto', 1, '2026-05-26 17:58:52', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `avisos`
--

CREATE TABLE `avisos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(180) NOT NULL,
  `texto` text NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `prioridad` enum('normal','importante','urgente') NOT NULL DEFAULT 'normal',
  `destacado` tinyint(1) NOT NULL DEFAULT 0,
  `visible_desde` datetime DEFAULT NULL,
  `visible_hasta` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `avisos`
--

INSERT INTO `avisos` (`id`, `titulo`, `texto`, `imagen`, `prioridad`, `destacado`, `visible_desde`, `visible_hasta`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Bienvenida a la plataforma', 'Ya está disponible la nueva plataforma interna de la falla.', NULL, 'importante', 1, NULL, NULL, 1, '2026-05-09 15:26:54', NULL),
(2, 'Recordatorio de reservas', 'Recuerda apuntarte a las comidas antes del plazo indicado.', NULL, 'normal', 0, NULL, NULL, 1, '2026-05-09 15:26:54', NULL),
(8, 'Cena Prueba', 'k', NULL, 'normal', 0, NULL, NULL, 1, '2026-05-10 18:12:53', NULL),
(10, 'Cena Prueba', 'asasas', NULL, 'normal', 0, NULL, NULL, 1, '2026-05-10 18:27:39', NULL),
(11, 'aviso', 'loteria', NULL, 'normal', 1, NULL, NULL, 1, '2026-05-26 17:59:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `falleros`
--

CREATE TABLE `falleros` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `sexo` varchar(20) NOT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `tipo` enum('infantil','adulto') NOT NULL DEFAULT 'adulto',
  `foto` varchar(255) DEFAULT NULL,
  `estado` enum('activo','inactivo','pendiente','baja') NOT NULL DEFAULT 'pendiente',
  `familia_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `falleros`
--

INSERT INTO `falleros` (`id`, `nombre`, `apellidos`, `dni`, `fecha_nacimiento`, `sexo`, `telefono`, `email`, `direccion`, `tipo`, `foto`, `estado`, `familia_id`, `created_at`, `updated_at`) VALUES
(1, 'Vicent', 'Martínez Soler', '11111111A', '1984-03-15', '', '600000001', 'vicent@example.com', NULL, 'adulto', NULL, 'activo', 1, '2026-05-09 15:26:54', NULL),
(2, 'María', 'García Ferrer', '22222222B', '1990-06-22', 'Mujer', '600000002', 'maria@example.com', '', 'adulto', NULL, 'activo', 2, '2026-05-09 15:26:54', '2026-05-14 17:31:54'),
(3, 'Pau', 'Martínez Soler', '33333333C', '2017-09-10', '', '600000003', 'pau@example.com', NULL, 'infantil', NULL, 'activo', 1, '2026-05-09 15:26:54', NULL),
(4, 'David', 'Cortell Aguilar', '23456789A', '2005-06-06', '', '123456789', 'david@gmail.com', 'Calle Mayor 8', 'adulto', NULL, 'activo', 3, '2026-05-09 15:30:16', '2026-05-09 15:32:29');

-- --------------------------------------------------------

--
-- Table structure for table `familias`
--

CREATE TABLE `familias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `representante_fallero_id` int(11) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `familias`
--

INSERT INTO `familias` (`id`, `nombre`, `representante_fallero_id`, `observaciones`, `created_at`, `updated_at`) VALUES
(1, 'Familia Martínez', 1, 'Familia de ejemplo', '2026-05-09 15:26:54', '2026-05-27 08:22:41'),
(2, 'Familia García', 2, 'Familia de ejemplo', '2026-05-09 15:26:54', NULL),
(3, 'Cortell Aguilar', 4, 'Solicitud de alta pendiente de aprobación.', '2026-05-09 15:30:16', '2026-05-09 15:32:29');

-- --------------------------------------------------------

--
-- Table structure for table `familia_representantes`
--

CREATE TABLE `familia_representantes` (
  `familia_id` int(11) NOT NULL,
  `fallero_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `familia_representantes`
--

INSERT INTO `familia_representantes` (`familia_id`, `fallero_id`, `created_at`) VALUES
(1, 1, '2026-05-27 08:22:41'),
(2, 2, '2026-05-27 07:05:56'),
(3, 4, '2026-05-27 07:05:56');

-- --------------------------------------------------------

--
-- Table structure for table `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `titulo` varchar(180) NOT NULL,
  `mensaje` text NOT NULL,
  `tipo` enum('info','reserva','aviso','sistema') NOT NULL DEFAULT 'info',
  `leida` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `opciones_comida`
--

CREATE TABLE `opciones_comida` (
  `id` int(11) NOT NULL,
  `acto_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `max_plazas` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `opciones_comida`
--

INSERT INTO `opciones_comida` (`id`, `acto_id`, `nombre`, `descripcion`, `max_plazas`, `is_active`, `created_at`) VALUES
(1, 1, 'Paella', 'Ración de paella', NULL, 1, '2026-05-09 15:26:54'),
(2, 1, 'Torra', 'Torra valenciana', NULL, 1, '2026-05-09 15:26:54'),
(3, 1, 'Menú infantil', 'Menú para infantil', NULL, 1, '2026-05-09 15:26:54'),
(4, 1, 'Vegetariano', 'Opción vegetariana', NULL, 1, '2026-05-09 15:26:54'),
(7, 4, 'Menu 1', '', NULL, 1, '2026-05-09 16:22:38'),
(8, 4, 'Menu 2', '', NULL, 1, '2026-05-09 16:22:38'),
(9, 5, 'Menu 2', '', NULL, 1, '2026-05-09 16:43:35'),
(10, 5, 'Menu 3', '', NULL, 1, '2026-05-09 16:43:35'),
(11, 5, 'Menu 4', '', NULL, 1, '2026-05-09 16:43:35'),
(12, 6, 'Tortilla', '', NULL, 1, '2026-05-10 18:06:00'),
(13, 6, 'Torra', '', NULL, 1, '2026-05-10 18:06:00'),
(18, 9, 'Menu 1', '', NULL, 1, '2026-05-10 18:28:42'),
(19, 9, 'Menu 2', '', NULL, 1, '2026-05-10 18:28:42'),
(20, 11, 'Menú 1', '', NULL, 1, '2026-05-22 11:13:55'),
(21, 12, 'Menu 1', '', NULL, 1, '2026-05-26 17:58:52'),
(22, 12, 'Menu 2', '', NULL, 1, '2026-05-26 17:58:52');

-- --------------------------------------------------------

--
-- Table structure for table `push_subscriptions`
--

CREATE TABLE `push_subscriptions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `endpoint` text NOT NULL,
  `p256dh` varchar(255) NOT NULL,
  `auth` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `push_subscriptions`
--

INSERT INTO `push_subscriptions` (`id`, `user_id`, `endpoint`, `p256dh`, `auth`, `created_at`) VALUES
(1, 4, 'https://web.push.apple.com/QFyuY9pKUBP6xrW8ufKhj4p0KoC5V6od6MQqci2mEJ65m4ZOt6XtfMBd96hyVzqIqqJKX-nxcEHHIuyA_dsQyjP0JlVd0BwbNSqSxNMP1GUOze7J-XVhkqwzfkTzfgT7kUNlwT7Eq9GFtRLl2GtA9Y0NYza8WbSvVvZcKeVm-PA', 'BIiiEbWZid4cjjpqsjTShOuXzBqw0Daqul75hmOgs1U37EOFRKKM9bDm-Oj-CROtqceWJ0Xtto_Du_N-FSXg1iE', 'daNgWLHr0HI589NpzNdRxw', '2026-05-10 17:59:37'),
(2, 4, 'https://web.push.apple.com/QMnPgwXBOF8QZzo2U_U_Yh9CaHDzHotUO6mLwqevkwKa86VRbXTupxGafVfOuA-KpsPWgzVihju35vHr4JcMsPU-cnXb69rHg17Pv6FVSOmWquVLZKhfE9KcTKuBjudDBJCG8vLN-l0wn1FD1GOeLTZ1YY-vfdDf4uEmFZrbEEw', 'BHi5tpfrHjNHWJZg_4EPnlT3dZE6mTiL8no_oCvTvBnh-sgs7rSgQyF_JAszpqRkJd4j-9rVuKp2lEuYyInVNXY', 'luQfkYcnBVDhKJkc4l474w', '2026-05-10 18:27:26'),
(3, 4, 'https://web.push.apple.com/QBZvZ5LPa7q4L50n4JZPxMgRZ2XiEj9mRf8f5QW7P9YPWAH2QtBivh-6ZksBRVFPtzTl1ylY2HN3QXHKm4eLUkU-5OyAghTK4Dm9xtEb3FzVXWQNg9jAshFkOPdTO5jk4fDhMHwBJN9BUQVuugY59Y7T1Jav4UGctbQp0VtMo5M', 'BD8_kR7DL1j8AL69bLyCamCHzbGTJL0tfBCuTYYu9R2RNAJ4kzoiF0-JbQDIPALipytNmmAE3S0utkDgPSUpJwY', 'N2R10RtH2pGt_prQdZHlig', '2026-05-14 17:19:59');

-- --------------------------------------------------------

--
-- Table structure for table `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `fallero_id` int(11) NOT NULL,
  `acto_id` int(11) NOT NULL,
  `opcion_comida_id` int(11) DEFAULT NULL,
  `invitado_nombre` varchar(180) DEFAULT NULL,
  `invitado_tipo` enum('adulto','infantil') DEFAULT NULL,
  `invitado_opcion_comida_id` int(11) DEFAULT NULL,
  `estado` enum('confirmada','cancelada','pendiente') NOT NULL DEFAULT 'confirmada',
  `pagada` tinyint(1) NOT NULL DEFAULT 0,
  `fecha_pago` datetime DEFAULT NULL,
  `qr_token` varchar(128) DEFAULT NULL,
  `qr_usado` tinyint(1) NOT NULL DEFAULT 0,
  `fecha_qr_usado` datetime DEFAULT NULL,
  `validado_por` int(11) DEFAULT NULL,
  `fecha_reserva` datetime NOT NULL DEFAULT current_timestamp(),
  `observaciones` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `reservas`
--

INSERT INTO `reservas` (`id`, `fallero_id`, `acto_id`, `opcion_comida_id`, `invitado_nombre`, `invitado_tipo`, `invitado_opcion_comida_id`, `estado`, `pagada`, `fecha_pago`, `qr_token`, `qr_usado`, `fecha_qr_usado`, `validado_por`, `fecha_reserva`, `observaciones`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, NULL, NULL, NULL, 'confirmada', 0, NULL, NULL, 0, NULL, NULL, '2026-05-09 15:26:54', NULL, '2026-05-09 15:26:54', '2026-05-14 17:32:23'),
(2, 2, 1, 2, NULL, NULL, NULL, 'confirmada', 0, NULL, NULL, 0, NULL, NULL, '2026-05-09 15:26:54', NULL, '2026-05-09 15:26:54', NULL),
(3, 3, 1, 3, NULL, NULL, NULL, 'confirmada', 0, NULL, NULL, 0, NULL, NULL, '2026-05-09 15:26:54', NULL, '2026-05-09 15:26:54', '2026-05-15 20:38:59'),
(4, 1, 3, NULL, NULL, NULL, NULL, 'confirmada', 0, NULL, NULL, 0, NULL, NULL, '2026-05-09 16:11:17', NULL, '2026-05-09 16:11:17', NULL),
(5, 3, 3, NULL, NULL, NULL, NULL, 'confirmada', 0, NULL, NULL, 0, NULL, NULL, '2026-05-09 16:11:21', NULL, '2026-05-09 16:11:21', '2026-05-14 17:31:40'),
(6, 4, 3, NULL, NULL, NULL, NULL, 'confirmada', 0, NULL, NULL, 0, NULL, NULL, '2026-05-09 16:19:25', NULL, '2026-05-09 16:19:25', '2026-05-09 16:37:45'),
(7, 4, 4, 7, NULL, NULL, NULL, 'confirmada', 1, '2026-05-27 07:17:11', '67291fb983ca7d635c762a07270b3113e08aed1dd38726103d11922dd768a556', 1, '2026-05-27 07:18:51', 1, '2026-05-09 16:36:37', NULL, '2026-05-09 16:36:37', '2026-05-27 07:18:51'),
(10, 4, 5, 10, NULL, NULL, NULL, 'confirmada', 0, NULL, NULL, 0, NULL, NULL, '2026-05-09 16:44:10', NULL, '2026-05-09 16:44:10', '2026-05-09 16:44:20'),
(13, 4, 1, 2, 'Manolo', 'adulto', 1, 'confirmada', 1, '2026-05-27 08:06:45', '0ca301299231a05662b905f9647bc280b5566ea621d50890c52ec3f81481af57', 1, '2026-05-27 08:07:13', 1, '2026-05-10 18:30:03', NULL, '2026-05-10 18:30:03', '2026-05-27 08:07:13'),
(15, 3, 6, 13, NULL, NULL, NULL, 'confirmada', 0, NULL, NULL, 0, NULL, NULL, '2026-05-14 17:19:33', NULL, '2026-05-14 17:19:33', NULL),
(17, 3, 4, 8, NULL, NULL, NULL, 'confirmada', 0, NULL, NULL, 0, NULL, NULL, '2026-05-14 17:32:17', NULL, '2026-05-14 17:32:17', '2026-05-14 17:33:52'),
(21, 4, 6, 13, NULL, NULL, NULL, 'cancelada', 0, NULL, NULL, 0, NULL, NULL, '2026-05-26 17:48:20', NULL, '2026-05-26 17:48:20', '2026-05-26 17:51:31');

-- --------------------------------------------------------

--
-- Table structure for table `reserva_invitados`
--

CREATE TABLE `reserva_invitados` (
  `id` int(11) NOT NULL,
  `reserva_id` int(11) NOT NULL,
  `nombre` varchar(180) NOT NULL,
  `tipo` enum('adulto','infantil') NOT NULL DEFAULT 'adulto',
  `opcion_comida_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `reserva_invitados`
--

INSERT INTO `reserva_invitados` (`id`, `reserva_id`, `nombre`, `tipo`, `opcion_comida_id`, `created_at`) VALUES
(1, 13, 'Manolo', 'adulto', 1, '2026-05-27 08:05:48'),
(2, 13, 'Pedro', 'infantil', 2, '2026-05-27 08:05:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','fallero') NOT NULL DEFAULT 'fallero',
  `fallero_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `failed_attempts` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `dni`, `password_hash`, `role`, `fallero_id`, `is_active`, `last_login`, `failed_attempts`, `created_at`, `updated_at`) VALUES
(1, '12345678A', '1234', 'admin', NULL, 1, '2026-05-27 08:19:16', 0, '2026-05-09 15:26:54', NULL),
(2, '11111111A', '1234', 'fallero', 1, 1, '2026-05-26 19:52:48', 0, '2026-05-09 15:26:54', NULL),
(3, '22222222B', '1234', 'fallero', 2, 1, NULL, 0, '2026-05-09 15:26:54', NULL),
(4, '23456789A', '$2y$10$hSPDNRYVvjjdU.HMsWv1fe7IvWDFlS4FOclLM1EQ74ho70iAnSGGi', 'fallero', 4, 1, '2026-05-27 08:08:51', 0, '2026-05-09 15:30:16', '2026-05-09 16:30:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_logs_user` (`user_id`);

--
-- Indexes for table `actos`
--
ALTER TABLE `actos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_actos_created_by` (`created_by`);

--
-- Indexes for table `avisos`
--
ALTER TABLE `avisos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_avisos_created_by` (`created_by`);

--
-- Indexes for table `falleros`
--
ALTER TABLE `falleros`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD KEY `fk_falleros_familia` (`familia_id`);

--
-- Indexes for table `familias`
--
ALTER TABLE `familias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_familias_representante` (`representante_fallero_id`);

--
-- Indexes for table `familia_representantes`
--
ALTER TABLE `familia_representantes`
  ADD PRIMARY KEY (`familia_id`,`fallero_id`),
  ADD KEY `fk_familia_representantes_fallero` (`fallero_id`);

--
-- Indexes for table `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_notificaciones_user` (`user_id`);

--
-- Indexes for table `opciones_comida`
--
ALTER TABLE `opciones_comida`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_opciones_acto` (`acto_id`);

--
-- Indexes for table `push_subscriptions`
--
ALTER TABLE `push_subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_reserva_fallero_acto` (`fallero_id`,`acto_id`),
  ADD UNIQUE KEY `qr_token` (`qr_token`),
  ADD KEY `fk_reservas_acto` (`acto_id`),
  ADD KEY `fk_reservas_opcion` (`opcion_comida_id`),
  ADD KEY `fk_reservas_invitado_opcion` (`invitado_opcion_comida_id`);

--
-- Indexes for table `reserva_invitados`
--
ALTER TABLE `reserva_invitados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reserva_invitados_reserva` (`reserva_id`),
  ADD KEY `fk_reserva_invitados_opcion` (`opcion_comida_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD KEY `fk_users_fallero` (`fallero_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT for table `actos`
--
ALTER TABLE `actos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `avisos`
--
ALTER TABLE `avisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `falleros`
--
ALTER TABLE `falleros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `familias`
--
ALTER TABLE `familias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `opciones_comida`
--
ALTER TABLE `opciones_comida`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `push_subscriptions`
--
ALTER TABLE `push_subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `reserva_invitados`
--
ALTER TABLE `reserva_invitados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `fk_logs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `actos`
--
ALTER TABLE `actos`
  ADD CONSTRAINT `fk_actos_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `avisos`
--
ALTER TABLE `avisos`
  ADD CONSTRAINT `fk_avisos_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `falleros`
--
ALTER TABLE `falleros`
  ADD CONSTRAINT `fk_falleros_familia` FOREIGN KEY (`familia_id`) REFERENCES `familias` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `familias`
--
ALTER TABLE `familias`
  ADD CONSTRAINT `fk_familias_representante` FOREIGN KEY (`representante_fallero_id`) REFERENCES `falleros` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `familia_representantes`
--
ALTER TABLE `familia_representantes`
  ADD CONSTRAINT `fk_familia_representantes_fallero` FOREIGN KEY (`fallero_id`) REFERENCES `falleros` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_familia_representantes_familia` FOREIGN KEY (`familia_id`) REFERENCES `familias` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `fk_notificaciones_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `opciones_comida`
--
ALTER TABLE `opciones_comida`
  ADD CONSTRAINT `fk_opciones_acto` FOREIGN KEY (`acto_id`) REFERENCES `actos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `fk_reservas_acto` FOREIGN KEY (`acto_id`) REFERENCES `actos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reservas_fallero` FOREIGN KEY (`fallero_id`) REFERENCES `falleros` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reservas_invitado_opcion` FOREIGN KEY (`invitado_opcion_comida_id`) REFERENCES `opciones_comida` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_reservas_opcion` FOREIGN KEY (`opcion_comida_id`) REFERENCES `opciones_comida` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `reserva_invitados`
--
ALTER TABLE `reserva_invitados`
  ADD CONSTRAINT `fk_reserva_invitados_opcion` FOREIGN KEY (`opcion_comida_id`) REFERENCES `opciones_comida` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_reserva_invitados_reserva` FOREIGN KEY (`reserva_id`) REFERENCES `reservas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_fallero` FOREIGN KEY (`fallero_id`) REFERENCES `falleros` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
