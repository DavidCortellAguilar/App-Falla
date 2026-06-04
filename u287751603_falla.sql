-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 02, 2026 at 06:39 PM
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
(6, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 15:32:44'),
(7, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 15:35:03'),
(8, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 15:38:01'),
(9, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 15:40:21'),
(10, NULL, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:11:17'),
(11, NULL, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:11:21'),
(12, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:15:12'),
(13, NULL, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:19:25'),
(14, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:19:47'),
(15, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:21:47'),
(16, 1, 'save', 'actos', 'Acto guardado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:22:38'),
(17, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:30:10'),
(18, NULL, 'cambiar_password', 'perfil', 'Cambio de contraseña desde Mi perfil', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:30:43'),
(19, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:31:00'),
(20, NULL, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:36:37'),
(21, NULL, 'cancel', 'reservas', 'Reserva cancelada por fallero', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:37:16'),
(22, NULL, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:37:43'),
(23, NULL, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:37:45'),
(24, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:38:53'),
(25, 1, 'save', 'avisos', 'Aviso guardado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:39:26'),
(26, 1, 'save', 'actos', 'Acto guardado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:43:35'),
(27, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:43:54'),
(28, NULL, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:44:10'),
(29, NULL, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:44:20'),
(30, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:51:01'),
(31, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:59:29'),
(32, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 16:59:39'),
(33, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', '2026-05-09 17:20:42'),
(34, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-09 17:26:42'),
(35, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-09 17:27:15'),
(36, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-09 17:30:46'),
(37, NULL, 'login', 'auth', 'Inicio de sesión correcto', '212.230.117.129', 'Mozilla/5.0 (Linux; Android 12; Redmi Note 9 Pro Build/SKQ1.211019.001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.7049.79 Mobile Safari/537.36 XiaoMi/MiuiBrowser/14.54.0-gn', '2026-05-09 22:51:25'),
(38, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-10 16:46:33'),
(39, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-10 16:48:26'),
(40, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 17:42:45'),
(41, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 17:46:47'),
(42, 1, 'save', 'avisos', 'Aviso guardado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 17:47:37'),
(43, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-10 17:55:56'),
(44, NULL, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 17:57:31'),
(45, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-10 17:59:28'),
(46, 1, 'save', 'avisos', 'Aviso guardado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:02:10'),
(47, 1, 'save', 'avisos', 'Aviso guardado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:02:49'),
(48, 1, 'save', 'actos', 'Acto guardado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:06:00'),
(49, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-10 18:06:14'),
(50, 1, 'save', 'actos', 'Acto guardado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:06:42'),
(51, 1, 'save', 'actos', 'Acto guardado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:09:28'),
(52, 1, 'save', 'avisos', 'Aviso guardado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:10:27'),
(53, 1, 'delete', 'avisos', 'Aviso eliminado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:10:50'),
(54, 1, 'delete', 'avisos', 'Aviso eliminado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:10:52'),
(55, 1, 'delete', 'avisos', 'Aviso eliminado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:10:54'),
(56, 1, 'delete', 'avisos', 'Aviso eliminado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:10:57'),
(57, 1, 'delete', 'avisos', 'Aviso eliminado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:10:59'),
(58, 1, 'save', 'avisos', 'Aviso guardado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:12:53'),
(59, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-10 18:26:45'),
(60, 1, 'save', 'avisos', 'Aviso guardado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:27:12'),
(61, 1, 'delete', 'avisos', 'Aviso eliminado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:27:31'),
(62, 1, 'save', 'avisos', 'Aviso guardado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:27:39'),
(63, 1, 'delete', 'actos', 'Acto eliminado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:27:59'),
(64, 1, 'delete', 'actos', 'Acto eliminado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:28:02'),
(65, 1, 'delete', 'actos', 'Acto eliminado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:28:09'),
(66, 1, 'save', 'actos', 'Acto guardado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:28:42'),
(67, 1, 'save', 'actos', 'Acto guardado', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:29:30'),
(68, NULL, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '88.19.52.253', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-10 18:30:03'),
(69, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-10 18:31:30'),
(70, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.19.52.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-10 18:33:33'),
(71, NULL, 'login', 'auth', 'Inicio de sesión correcto', '139.47.20.20', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-05-11 10:44:44'),
(72, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.17.215.19', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-05-11 18:46:26'),
(73, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.17.215.19', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-05-11 18:47:59'),
(74, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.20.210.228', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', '2026-05-12 08:02:26'),
(75, 1, 'login', 'auth', 'Inicio de sesión correcto', '84.125.71.29', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-05-12 08:08:40'),
(76, 1, 'login', 'auth', 'Inicio de sesión correcto', '84.123.208.144', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-12 11:12:27'),
(77, 1, 'login', 'auth', 'Inicio de sesión correcto', '84.123.208.144', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-12 11:13:00'),
(78, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.18.157.181', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-14 16:58:43'),
(79, 1, 'login', 'auth', 'Inicio de sesión correcto', '176.80.69.31', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-14 16:59:57'),
(80, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.18.157.181', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-14 17:07:19'),
(81, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.18.157.181', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-14 17:11:12'),
(82, 1, 'login', 'auth', 'Inicio de sesión correcto', '84.125.74.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36 EdgA/147.0.0.0', '2026-05-14 17:12:38'),
(83, NULL, 'login', 'auth', 'Inicio de sesión correcto', '84.125.74.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36 EdgA/147.0.0.0', '2026-05-14 17:18:20'),
(84, NULL, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '84.125.74.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36 EdgA/147.0.0.0', '2026-05-14 17:19:16'),
(85, NULL, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '84.125.74.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36 EdgA/147.0.0.0', '2026-05-14 17:19:33'),
(86, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.18.157.181', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-14 17:19:51'),
(87, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.18.157.181', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-14 17:20:08'),
(88, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.17.215.19', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-05-14 17:30:05'),
(89, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.18.157.181', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-14 17:31:34'),
(90, NULL, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '88.17.215.19', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-05-14 17:31:40'),
(91, 1, 'update', 'falleros', 'Fallero actualizado', '88.18.157.181', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-14 17:31:54'),
(92, NULL, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '88.17.215.19', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-05-14 17:32:17'),
(93, NULL, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '88.17.215.19', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-05-14 17:32:23'),
(94, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.17.215.19', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-05-14 17:32:59'),
(95, 1, 'update', 'reservas', 'Estado de reserva actualizado', '88.17.215.19', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-05-14 17:33:52'),
(96, NULL, 'login', 'auth', 'Inicio de sesión correcto', '84.127.38.152', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-05-14 20:52:33'),
(97, NULL, 'login', 'auth', 'Inicio de sesión correcto', '84.125.71.41', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-05-15 20:38:31'),
(98, NULL, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '84.125.71.41', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-05-15 20:38:59'),
(99, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-21 08:23:21'),
(100, 1, 'save', 'actos', 'Acto guardado', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-21 14:12:39'),
(101, 1, 'save', 'actos', 'Acto guardado', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-21 14:13:47'),
(102, 1, 'save', 'actos', 'Acto guardado', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-21 14:19:19'),
(103, 1, 'login', 'auth', 'Inicio de sesión correcto', '2a02:9130:8535:be9b:a0e4:25b3:5dde:8481', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-22 11:11:43'),
(104, 1, 'save', 'actos', 'Acto guardado', '2a02:9130:8535:be9b:a0e4:25b3:5dde:8481', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-22 11:13:55'),
(105, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.18.157.181', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-22 22:25:45'),
(106, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.18.157.181', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-22 22:26:05'),
(107, 1, 'login', 'auth', 'Inicio de sesión correcto', '2a02:9130:852f:6856:cd8f:2ae:ffc5:9ecc', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-24 12:18:04'),
(108, NULL, 'login', 'auth', 'Inicio de sesión correcto', '84.125.69.84', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36 EdgA/148.0.0.0', '2026-05-26 17:15:04'),
(109, 1, 'login', 'auth', 'Inicio de sesión correcto', '84.125.125.76', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-26 17:24:57'),
(110, NULL, 'login', 'auth', 'Inicio de sesión correcto', '84.125.125.76', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-26 17:38:43'),
(111, NULL, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '84.125.125.76', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-26 17:47:59'),
(112, NULL, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '84.125.125.76', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-26 17:48:20'),
(113, 1, 'login', 'auth', 'Inicio de sesión correcto', '84.125.125.76', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-26 17:48:59'),
(114, NULL, 'login', 'auth', 'Inicio de sesión correcto', '2a02:9130:802a:6b3f:2cab:eae4:8c45:bdb9', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-26 17:49:36'),
(115, 1, 'save', 'actos', 'Acto guardado', '84.125.125.76', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-26 17:51:08'),
(116, NULL, 'cancel', 'reservas', 'Reserva cancelada por fallero', '2a02:9130:802a:6b3f:2cab:eae4:8c45:bdb9', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-26 17:51:31'),
(117, NULL, 'create', 'reservas', 'Reserva realizada por fallero o representante familiar', '2a02:9130:802a:6b3f:2cab:eae4:8c45:bdb9', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-26 17:52:48'),
(118, NULL, 'login', 'auth', 'Inicio de sesión correcto', '84.125.125.76', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-26 17:54:03'),
(119, 1, 'login', 'auth', 'Inicio de sesión correcto', '84.125.125.76', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-26 17:57:46'),
(120, 1, 'save', 'actos', 'Acto guardado', '84.125.125.76', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-26 17:58:52'),
(121, 1, 'save', 'avisos', 'Aviso guardado', '84.125.125.76', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-26 17:59:16'),
(122, 1, 'login', 'auth', 'Inicio de sesión correcto', '84.125.125.76', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-26 18:08:37'),
(123, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.17.215.19', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-26 19:49:19'),
(124, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.17.215.19', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-26 19:52:48'),
(125, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.18.157.181', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-26 23:17:33'),
(126, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-27 07:06:42'),
(127, 1, 'pago', 'reservas', 'Reserva marcada como pagada', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-27 07:17:11'),
(128, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-27 07:17:40'),
(129, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-27 07:40:00'),
(130, NULL, 'login', 'auth', 'Inicio de sesión correcto', '2a02:9130:80a6:9ca4:9433:da01:b9ce:ea3e', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-27 07:44:07'),
(131, NULL, 'create', 'reservas', 'Reserva realizada o actualizada por fallero o representante familiar', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-27 08:05:22'),
(132, NULL, 'create', 'reservas', 'Reserva realizada o actualizada por fallero o representante familiar', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-27 08:05:48'),
(133, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-27 08:06:18'),
(134, 1, 'pago', 'reservas', 'Reserva marcada como pagada', '88.21.212.211', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-27 08:06:45'),
(135, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-27 08:07:51'),
(136, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-27 08:08:51'),
(137, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-27 08:19:16'),
(138, 1, 'save', 'familias', 'Familia guardada', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-27 08:22:29'),
(139, 1, 'save', 'familias', 'Familia guardada', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-27 08:22:41'),
(140, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-27 08:35:05'),
(141, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-27 08:47:57'),
(142, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-27 08:52:38'),
(143, 1, 'pago', 'reservas', 'Reserva marcada como pagada', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-27 08:54:04'),
(144, 1, 'save', 'actos', 'Acto guardado', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-27 08:58:07'),
(145, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-27 09:00:47'),
(146, NULL, 'create', 'reservas', 'Reserva realizada o actualizada por fallero o representante familiar', '88.21.212.211', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-27 09:05:40'),
(147, NULL, 'create', 'reservas', 'Reserva realizada o actualizada por fallero o representante familiar', '88.21.212.211', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-27 09:05:50'),
(148, 1, 'pago', 'reservas', 'Reserva marcada como pagada', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-27 09:14:01'),
(149, 1, 'pago', 'reservas', 'Reserva marcada como pagada', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-27 09:14:03'),
(150, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-27 09:24:12'),
(151, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-27 09:24:31'),
(152, NULL, 'create', 'reservas', 'Reserva realizada o actualizada por fallero o representante familiar', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-27 09:28:07'),
(153, NULL, 'create', 'reservas', 'Reserva realizada o actualizada por fallero o representante familiar', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-27 09:28:15'),
(154, 1, 'pago', 'reservas', 'Reserva marcada como pagada', '88.21.212.211', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-27 09:28:39'),
(155, 1, 'pago', 'reservas', 'Reserva marcada como pagada', '88.21.212.211', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-27 09:28:46'),
(156, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-28 09:36:19'),
(157, 1, 'save', 'actos', 'Acto guardado', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-28 09:37:35'),
(158, 1, 'delete', 'actos', 'Acto eliminado', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-28 09:47:22'),
(159, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-28 10:04:08'),
(160, 1, 'delete', 'familias', 'Familia eliminada', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-28 10:04:32'),
(161, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-28 10:23:29'),
(162, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-28 10:45:51'),
(163, 1, 'save', 'actos', 'Acto guardado', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-28 11:22:08'),
(164, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-28 11:22:53'),
(165, NULL, 'create', 'reservas', 'Reserva realizada o actualizada por fallero o representante familiar', '88.21.212.211', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-28 11:23:13'),
(166, 1, 'pago', 'reservas', 'Reserva marcada como pagada', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-28 11:24:35'),
(167, NULL, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-28 11:26:25'),
(168, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-28 11:26:37'),
(169, 118, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-28 13:34:38'),
(170, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-28 13:35:27'),
(171, 1, 'save', 'familias', 'Familia guardada', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-28 13:38:27'),
(172, 1, 'save', 'familias', 'Familia guardada', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-28 13:38:45'),
(173, 1, 'save', 'familias', 'Familia guardada', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-28 13:39:54'),
(174, 1, 'delete', 'familias', 'Familia eliminada', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-28 13:40:03'),
(175, 1, 'delete', 'familias', 'Familia eliminada', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-28 13:40:07'),
(176, 1, 'save', 'familias', 'Familia guardada', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-28 13:41:10'),
(177, 1, 'save', 'familias', 'Familia guardada', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-28 13:42:15'),
(178, 1, 'save', 'familias', 'Familia guardada', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-28 13:44:04'),
(179, 1, 'save', 'familias', 'Familia guardada', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-28 13:44:52'),
(180, 1, 'save', 'familias', 'Familia guardada', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-28 13:56:18'),
(181, 1, 'save', 'familias', 'Familia guardada', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-28 14:00:42'),
(182, 1, 'update', 'falleros', 'Fallero actualizado', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-28 14:47:43'),
(183, 1, 'save', 'familias', 'Familia guardada', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-28 14:47:57'),
(184, 27, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-28 14:49:09'),
(185, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.21.212.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-29 10:47:30'),
(186, 1, 'login', 'auth', 'Inicio de sesión correcto', '62.87.75.39', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', '2026-05-29 21:01:58'),
(187, 101, 'login', 'auth', 'Inicio de sesión correcto', '84.125.68.46', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', '2026-05-29 21:03:14'),
(188, 101, 'create', 'reservas', 'Reserva realizada o actualizada por fallero o representante familiar', '84.125.68.46', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', '2026-05-29 21:03:31'),
(189, 1, 'pago', 'reservas', 'Reserva marcada como pagada', '62.87.75.39', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', '2026-05-29 21:03:42'),
(190, 101, 'create', 'reservas', 'Reserva realizada o actualizada por fallero o representante familiar', '84.125.68.46', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', '2026-05-29 21:04:41'),
(191, 1, 'pago', 'reservas', 'Reserva marcada como pagada', '62.87.75.39', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', '2026-05-29 21:05:03'),
(192, 1, 'save', 'avisos', 'Aviso guardado', '62.87.75.39', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', '2026-05-29 21:05:48'),
(193, 1, 'save', 'avisos', 'Aviso guardado', '62.87.75.39', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', '2026-05-29 21:53:21'),
(194, 101, 'login', 'auth', 'Inicio de sesión correcto', '188.85.43.250', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', '2026-05-29 22:35:56'),
(195, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.18.157.181', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-29 23:51:44'),
(196, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.18.157.181', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-29 23:52:13'),
(197, 118, 'login', 'auth', 'Inicio de sesión correcto', '88.18.157.181', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-29 23:54:26'),
(198, 118, 'create', 'reservas', 'Reserva realizada o actualizada por fallero o representante familiar', '88.18.157.181', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-29 23:56:05'),
(199, 1, 'pago', 'reservas', 'Reserva marcada como pagada', '88.18.157.181', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-29 23:56:40'),
(200, 118, 'create', 'reservas', 'Reserva realizada o actualizada por fallero o representante familiar', '88.18.157.181', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-29 23:57:18'),
(201, 1, 'pago', 'reservas', 'Reserva marcada como pagada', '88.18.157.181', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-29 23:57:28'),
(202, 118, 'login', 'auth', 'Inicio de sesión correcto', '88.18.157.181', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-29 23:58:06'),
(203, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.18.157.181', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-29 23:58:21'),
(204, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.18.157.181', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-30 00:01:23'),
(205, 118, 'login', 'auth', 'Inicio de sesión correcto', '88.18.157.181', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-30 00:01:34'),
(206, 1, 'save', 'avisos', 'Aviso guardado', '88.18.157.181', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-30 00:02:39'),
(207, 118, 'login', 'auth', 'Inicio de sesión correcto', '88.18.157.181', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', '2026-05-30 00:04:27'),
(208, 1, 'save', 'avisos', 'Aviso guardado', '88.18.157.181', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-30 00:04:58'),
(209, 1, 'save', 'actos', 'Acto guardado', '88.18.157.181', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-30 00:08:30'),
(210, 118, 'create', 'reservas', 'Reserva realizada o actualizada por fallero o representante familiar', '88.18.157.181', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-05-30 22:08:18'),
(211, 1, 'pago', 'reservas', 'Reserva marcada como pagada', '88.18.157.181', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-30 22:08:41'),
(212, 1, 'save', 'juntas', 'Junta guardada', '88.18.157.181', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-30 22:21:23'),
(213, 1, 'save', 'juntas', 'Junta guardada', '88.18.157.181', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-30 22:28:44'),
(214, 1, 'save', 'actos', 'Acto guardado', '88.18.157.181', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-30 23:38:05'),
(215, 1, 'delete', 'juntas', 'Junta eliminada', '88.18.157.181', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-30 23:52:00'),
(216, 1, 'save', 'juntas', 'Junta guardada', '88.18.157.181', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-30 23:52:10'),
(217, 1, 'save', 'actos', 'Acto guardado', '88.18.157.181', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-30 23:52:26'),
(218, 1, 'update', 'falleros', 'Fallero actualizado', '88.18.157.181', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-30 23:59:41'),
(219, 1, 'update', 'falleros', 'Fallero actualizado', '88.18.157.181', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 00:00:00'),
(220, 186, 'login', 'auth', 'Inicio de sesión correcto', '88.18.157.181', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 00:00:20'),
(221, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.18.157.181', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 00:00:47'),
(222, 1, 'login', 'auth', 'Inicio de sesión correcto', '88.18.157.181', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 16:08:49'),
(223, 1, 'login', 'auth', 'Inicio de sesión correcto', '2a0c:5a82:a501:cc00:5832:50fc:f03e:d4e5', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 19:23:52');

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
(1, 'Comida sábado 15 marzo', 'Comida popular de fallas.', '2026-03-15', '14:00:00', 'Casal fallero', NULL, 'comida', 200, 'cerrado', 1, '2026-05-09 15:26:54', '2026-05-27 08:58:07'),
(3, 'Pasacalles general', 'Pasacalles por el barrio.', '2026-03-17', '11:00:00', 'Plaza principal', NULL, 'pasacalles', NULL, 'abierto', 1, '2026-05-09 15:26:54', NULL),
(4, 'Cena Proclamación', '', '2026-05-09', '22:00:00', 'Moncada', NULL, 'cena', NULL, 'abierto', 1, '2026-05-09 16:22:38', NULL),
(5, 'Cena Prueba', 'Esto es la descripcion de el acto', '2026-05-10', '21:30:00', 'Casal fallero', 'uploads/actos/acto_1779372759_51dd4ea63e1c.jpg', 'comida', NULL, 'abierto', 1, '2026-05-09 16:43:35', '2026-05-21 14:13:47'),
(6, 'Cena Prueba', '', '2026-05-10', '21:30:00', 'Casal fallero', NULL, 'comida', NULL, 'cerrado', 1, '2026-05-10 18:06:00', '2026-05-26 17:51:08'),
(9, 'Cena Pressentación', 'Cena Pressentación', '2026-05-10', '21:30:00', 'Moncada', NULL, 'comida', NULL, 'cerrado', 1, '2026-05-10 18:28:42', '2026-05-10 18:29:30'),
(10, 'Prueba Web', 'Esto es una prueba para la web de la falla san sebastian arzobispo fuero', '2026-05-21', '21:00:00', 'Casal fallero', 'uploads/actos/acto_1779373159_858ebd8c85ad.jpg', 'reunion', NULL, 'abierto', 1, '2026-05-21 14:19:19', NULL),
(11, 'Prueba Web 2', 'Esta es la segunda prueba que hago para la web de la falla San Sebastián arzobispo fuero', '2026-05-22', '13:12:00', 'Godella', 'uploads/actos/acto_1779448435_9632d7a08f56.png', 'comida', NULL, 'abierto', 1, '2026-05-22 11:13:55', NULL),
(12, 'Cena Prueba 8', 'Esto es una prueba', '2026-05-26', NULL, 'Casal', NULL, 'comida', NULL, 'abierto', 1, '2026-05-26 17:58:52', NULL),
(14, 'Prueba Pagos', '', '2026-05-28', NULL, '', NULL, 'comida', NULL, 'abierto', 1, '2026-05-28 11:22:08', NULL),
(15, 'Acto Prueba', 'add', '2026-05-31', NULL, '', 'uploads/actos/acto_1780099710_081b83acbfc4.jpg', 'pasacalles', NULL, 'abierto', 1, '2026-05-30 00:08:30', NULL),
(16, 'Acto Prueba 3', '', '2026-05-30', NULL, '', NULL, 'comida', NULL, 'abierto', 1, '2026-05-30 23:38:05', '2026-05-30 23:52:26');

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
(11, 'aviso', 'loteria', NULL, 'normal', 1, NULL, NULL, 1, '2026-05-26 17:59:16', NULL),
(12, 'Aviso prueba', 'hola', NULL, 'normal', 1, NULL, NULL, 1, '2026-05-29 21:05:48', NULL),
(13, 'probando', 'probando', NULL, 'normal', 1, NULL, NULL, 1, '2026-05-29 21:53:21', NULL),
(14, 'Aviso de prueba', 'g', NULL, 'normal', 1, NULL, NULL, 1, '2026-05-30 00:02:39', NULL),
(15, 'Aviso Prueba', 'a', NULL, 'normal', 1, NULL, NULL, 1, '2026-05-30 00:04:58', NULL);

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
(5, 'MARI ANGELES', 'HORTELANO SERRANO', '48439371Z', '1978-05-16', 'Mujer', NULL, NULL, 'AVDA. DELS DIAMANTS', 'adulto', NULL, 'activo', 4, '2026-05-28 13:33:01', NULL),
(6, 'JOSE LUIS', 'MARIN ROBERTO', '48437222G', '1978-02-28', 'Hombre', '622133065', NULL, 'AVDA. DELS DIAMANTS', 'adulto', NULL, 'activo', 4, '2026-05-28 13:33:01', NULL),
(7, 'ESTHER', 'AGUILAR HORTELANO', '09108321E', '2009-08-05', 'Mujer', '622133065', NULL, 'AVDA. DELS DIAMANTS', 'adulto', NULL, 'activo', 4, '2026-05-28 13:33:01', NULL),
(8, 'JAVIER', 'AGUILAR HORTELANO', '09108323R', '2011-04-16', 'Hombre', '622133065', NULL, 'AVDA. DELS DIAMANTS', 'adulto', NULL, 'activo', 4, '2026-05-28 13:33:01', NULL),
(9, 'ISABEL', 'ÁLAMO MONTERO', '54747986M', '2016-05-27', 'Mujer', '672655958', NULL, 'AVENIDA PORT SAINT MARTIN 79', 'infantil', NULL, 'activo', 50, '2026-05-28 13:33:01', NULL),
(10, 'NEREA', 'ÁLAMO MONTERO', '49353043B', '2012-03-25', 'Mujer', '672655958', NULL, 'AVENIDA PORT SAINT MARTIN 79', 'infantil', NULL, 'activo', 50, '2026-05-28 13:33:01', NULL),
(11, 'JUANMANUEL', 'ALAMO VILLALBA', '48435311W', '1976-06-29', 'Hombre', '681148156', NULL, 'AVENIDA PORT SAINT MARTIN 79', 'adulto', NULL, 'activo', 50, '2026-05-28 13:33:01', NULL),
(12, 'CLARISBEL', 'MONTERO ROSALES', '55272107W', '1991-01-10', 'Mujer', '672655958', NULL, 'AVENIDA PONT SAINT MARTIN', 'adulto', NULL, 'activo', 50, '2026-05-28 13:33:01', NULL),
(13, 'RAMÓN', 'ANDREU TAMARIT', '29163867M', '1970-04-18', 'Hombre', '649147771', NULL, 'C/ CONDE DE CIRAT 4-B2', 'adulto', NULL, 'activo', 5, '2026-05-28 13:33:01', NULL),
(14, 'INÉS', 'MARCO SOLA', '48435262E', '1976-01-10', 'Mujer', '620563291', NULL, 'C/ CONDE DE CIRAT 4-B2', 'adulto', NULL, 'activo', 5, '2026-05-28 13:33:01', NULL),
(15, 'LAURA', 'ANDREU ROS', '23872148B', '2003-09-06', 'Mujer', '671238612', NULL, 'C/ CONDE DE CIRAT 4-B2', 'adulto', NULL, 'activo', 5, '2026-05-28 13:33:01', NULL),
(16, 'MARTA', 'MARTÍNEZ MARCO', '21795908G', '2007-06-22', 'Mujer', '644638927', NULL, 'C/ CONDE DE CIRAT 4-B2', 'adulto', NULL, 'activo', 5, '2026-05-28 13:33:01', NULL),
(17, 'NEREA', 'ANDREU ROS', '23872150J', '2008-10-14', 'Mujer', '649147771', NULL, 'C/ CONDE DE CIRAT 4-B2', 'adulto', NULL, 'activo', 5, '2026-05-28 13:33:01', NULL),
(18, 'ESPERANZA', 'HERNANDEZ RUEDA', '73723446M', '1947-01-15', 'Mujer', NULL, NULL, 'SANTA TERESA 5 PTA 2', 'adulto', NULL, 'activo', 6, '2026-05-28 13:33:01', NULL),
(19, 'LUIS', 'AVILÉS ZAMORA', '26153947A', '1942-08-02', 'Hombre', NULL, NULL, 'SANTA TERESA 5 PTA 2', 'adulto', NULL, 'activo', 6, '2026-05-28 13:33:01', NULL),
(20, 'SERGIO', 'BAUSACH RUIZ', '52656363W', '1978-02-13', 'Hombre', '654326192', NULL, 'CTRA. ROCAFORT 2-5', 'adulto', NULL, 'activo', 7, '2026-05-28 13:33:01', NULL),
(21, 'M.CARMEN', 'CORRAL GARCÍA', '48439509Z', '1980-11-14', 'Mujer', '651057592', NULL, 'CTRA. ROCAFORT 2-5', 'adulto', NULL, 'activo', 7, '2026-05-28 13:33:01', NULL),
(22, 'NOA', 'BAUSACH CORRAL', '26888786S', '2011-10-06', 'Mujer', '654326192', NULL, 'CTRA ROCAFORT 2-5', 'adulto', NULL, 'activo', 7, '2026-05-28 13:33:01', NULL),
(23, 'DUNIA', 'BAUSACH CORRAL', 'FN20200129', '2020-01-29', 'Mujer', '654326192', NULL, 'CTRA ROCAFORT 2-5', 'infantil', NULL, 'activo', 7, '2026-05-28 13:33:01', NULL),
(24, 'VERONICA', 'ESCUDERO ILLANA', '48441642P', '1981-12-12', 'Mujer', '657411071', NULL, 'PLAZA DELS FURS 2-6', 'adulto', NULL, 'activo', 8, '2026-05-28 13:33:01', NULL),
(25, 'CARLOS', 'BELLVER LLORENS', '29193513G', '1977-07-19', 'Hombre', '661747511', NULL, 'PLAZA DELS FURS 2-6', 'adulto', NULL, 'activo', 8, '2026-05-28 13:33:01', NULL),
(26, 'VICENTE', 'BELLVER LLORENS', '44869824K', '1979-02-06', 'Hombre', '625286361', NULL, 'C/SAN JOSÉ, 11-5', 'adulto', NULL, 'activo', 9, '2026-05-28 13:33:01', NULL),
(27, 'NICOLAS', 'BELLVER SANCHEZ', 'FN20130114', '2013-01-14', 'Hombre', '625286361', NULL, 'C/SAN JOSÉ, 11-5', 'infantil', NULL, 'activo', 9, '2026-05-28 13:33:01', NULL),
(28, 'LUCAS', 'BELLVER SANCHEZ', 'FN20151116', '2015-11-16', 'Hombre', '625286361', NULL, 'C/SAN JOSÉ, 11-5', 'infantil', NULL, 'activo', 9, '2026-05-28 13:33:01', NULL),
(29, 'ALICIA', 'BLASCO LEAL', '44896870L', '2003-10-03', 'Mujer', '601301084', NULL, 'VILLAS DEL COLLADO 1 PTA 7', 'adulto', NULL, 'activo', 10, '2026-05-28 13:33:01', NULL),
(30, 'LAURA', 'BLASCO LEAL', '13311682H', '2010-12-15', 'Mujer', '601301084', NULL, 'VILLAS DEL COLLADO 1 PTA 7', 'adulto', NULL, 'activo', 10, '2026-05-28 13:33:01', NULL),
(31, 'FEDERICO', 'BLASCO BORJA', 'FN20170204', '2017-02-04', 'Hombre', '654689460', NULL, 'C/VALL DE LA SABINA, 2', 'infantil', NULL, 'activo', 11, '2026-05-28 13:33:01', NULL),
(32, 'VERONICA', 'TORRENT CASANOVA', '48441465S', '1979-12-15', 'Mujer', '657545703', NULL, 'C/VALL DE LA SABINA, 2', 'adulto', NULL, 'activo', 11, '2026-05-28 13:33:01', NULL),
(33, 'FEDERICO', 'BLASCO SOLER', '48436267S', '1978-01-12', 'Hombre', '654689460', NULL, 'C/VALL DE LA SABINA, 2', 'adulto', NULL, 'activo', 11, '2026-05-28 13:33:01', NULL),
(34, 'OLGA', 'CASTILLA BENLLOCH', '49359337A', '2011-10-01', 'Mujer', '669240942', NULL, 'SAGRADO CORAZON 16-2', 'adulto', NULL, 'activo', 12, '2026-05-28 13:33:01', NULL),
(35, 'ANA', 'BENLLOCH ORTS', '52655745M', '1972-11-27', 'Mujer', '669240942', NULL, 'SAGRADO CORAZÓN 16-2', 'adulto', NULL, 'activo', 12, '2026-05-28 13:33:01', NULL),
(36, 'VICENTE', 'CHAPARRO BUENO', '52652615A', '1968-04-03', 'Hombre', '644053379', NULL, 'C/ 124 JARDINES DE MASIAS 2A-20', 'adulto', NULL, 'activo', 13, '2026-05-28 13:33:01', NULL),
(37, 'ISABEL', 'CHAPARRO AVILÉS', '23919356T', '1997-02-19', 'Mujer', '692567086', NULL, 'C/ 124 JARDINES DE MASIAS 2A-20', 'adulto', NULL, 'activo', 13, '2026-05-28 13:33:01', NULL),
(38, 'ISABEL', 'AVILÉS HERNÁNDEZ', '73769296Q', '1968-07-08', 'Mujer', '644417065', NULL, 'C/ 124 JARDINES DE MASIAS 2A-20', 'adulto', NULL, 'activo', 13, '2026-05-28 13:33:01', NULL),
(39, 'AINARA', 'CHAPARRO AVILÉS', '23919357R', '2005-12-30', 'Mujer', '652328361', NULL, 'C/ 124 JARDINES DE MASIAS 2A-20', 'adulto', NULL, 'activo', 13, '2026-05-28 13:33:01', NULL),
(40, 'MARIEN', 'CLEMENTE SORIANO', '48437607K', '1978-12-01', 'Mujer', '600388147', NULL, 'C/SANTA AURORA, 4-21', 'adulto', NULL, 'activo', 14, '2026-05-28 13:33:01', NULL),
(41, 'ALMA', 'CLEMENTE SORIANO', 'FN20180315', '2018-03-15', 'Mujer', '600388147', NULL, 'C/SANTA AURORA, 4-21', 'infantil', NULL, 'activo', 14, '2026-05-28 13:33:01', NULL),
(42, 'BEATRIZ MARIA', 'CUQUERELLA SORIANO', '48597110L', '1989-10-27', 'Mujer', '605208803', NULL, 'CRTA/ROCAFORT 18-6', 'adulto', NULL, 'activo', 15, '2026-05-28 13:33:01', NULL),
(43, 'CRISTIAN', 'COBO CUQUERELLA', '17519068Z', '2021-10-01', 'Hombre', NULL, NULL, 'CRTA/ROCAFORT 18-6', 'infantil', NULL, 'activo', 15, '2026-05-28 13:33:01', NULL),
(44, 'JONATAN', 'COBO RODRIGUEZ', '34272228C', '1987-02-11', 'Hombre', '605213948', NULL, 'CRTA/ROCAFORT 18-6', 'adulto', NULL, 'activo', 15, '2026-05-28 13:33:01', NULL),
(45, 'DANIELLA', 'CONTRERAS RODRÍGUEZ', '03197735E', '2011-04-04', 'Mujer', '672300822', NULL, 'CONDE DE CIRAT 24 - 3B', 'adulto', NULL, 'activo', 16, '2026-05-28 13:33:01', NULL),
(46, 'ÁNGEL', 'CONTRERAS GARCÍA', '48436294L', '1976-10-12', 'Hombre', '672300821', NULL, 'CONDE DE CIRAT 24 - 3B', 'adulto', NULL, 'activo', 16, '2026-05-28 13:33:01', NULL),
(47, 'CARMEN', 'RODRIGUEZ MARTINEZ', '48436517N', '1977-04-14', 'Mujer', '672300822', NULL, 'CONDE DE CIRAT 24 - 3B', 'adulto', NULL, 'activo', 16, '2026-05-28 13:33:01', NULL),
(48, 'JIMENA', 'GÁLVEZ MOLLÁ', 'FN20160529', '2016-05-29', 'Mujer', '665865014', '', 'VILA BLANCA 27 PTA 25', 'infantil', NULL, 'activo', 21, '2026-05-28 13:33:01', '2026-05-30 23:59:41'),
(49, 'JUANA', 'GÁLVEZ MOLLÁ', 'FN20190313', '2019-03-13', 'Mujer', '665865014', NULL, 'VILA BLANCA 27 PTA 25', 'infantil', NULL, 'activo', 21, '2026-05-28 13:33:01', NULL),
(50, 'TERESA', 'GÁLVEZ BENLLOCH', '48443651Q', '1984-01-02', 'Mujer', '665865014', NULL, 'VILA BLANCA 27 PTA 25', 'adulto', NULL, 'activo', 21, '2026-05-28 13:33:01', NULL),
(51, 'ALADINO', 'GARCIA REDONDO', '48438911Z', '1978-11-16', 'Hombre', '698976203', NULL, 'CTRA. ROCAFORT, 18-1', 'adulto', NULL, 'activo', 17, '2026-05-28 13:33:01', NULL),
(52, 'IBTISSAM', 'AHGBAR', 'MA19891120', '1989-11-20', 'Mujer', '698976203', NULL, 'CTRA. ROCAFORT, 18-1', 'adulto', NULL, 'activo', 17, '2026-05-28 13:33:01', NULL),
(53, 'ALEJANDRO', 'GARCIA AHGBAR', '17517741K', '2023-12-15', 'Hombre', '698976203', NULL, 'CTRA. ROCAFORT, 18-1', 'infantil', NULL, 'activo', 17, '2026-05-28 13:33:01', NULL),
(54, 'INMACULADA', 'GARCÍA OSCA', '48435767K', '1980-06-01', 'Mujer', '675374218', NULL, 'SANT ANTONI 15-6', 'adulto', NULL, 'activo', 18, '2026-05-28 13:33:01', NULL),
(55, 'FRANCISCO', 'GARCÍA LÓPEZ', '48311089A', '1980-11-25', 'Hombre', '691992191', NULL, 'SANT ANTONI 15-6', 'adulto', NULL, 'activo', 18, '2026-05-28 13:33:01', NULL),
(56, 'INMACULADA', 'GARCIA GARCÍA', 'FN20160801', '2016-08-01', 'Mujer', '657374218', NULL, 'SANT ANTONI 15-6', 'infantil', NULL, 'activo', 18, '2026-05-28 13:33:01', NULL),
(57, 'IRENE', 'PÉREZ VIVARACHO', '20839010K', '1984-03-06', 'Mujer', '641536358', NULL, 'CARRETERA DE ROCAFORT 14 PTA 2', 'adulto', NULL, 'activo', 19, '2026-05-28 13:33:01', NULL),
(58, 'DIEGO', 'GARCÍA PÉREZ', 'FN20130813', '2013-08-13', 'Hombre', '641536358', NULL, 'CARRETERA DE ROCAFORT 14 PTA 2', 'infantil', NULL, 'activo', 19, '2026-05-28 13:33:01', NULL),
(59, 'JACK', 'GARCÍA PÉREZ', 'FN20221018', '2022-10-18', 'Hombre', '641536358', NULL, 'CARRETERA DE ROCAFORT 14 PTA 2', 'infantil', NULL, 'activo', 19, '2026-05-28 13:33:01', NULL),
(60, 'DIEGO', 'GARCÍA SÁNCHEZ', '48438165G', '1981-05-23', 'Hombre', '614183812', NULL, 'CARRETERA DE ROCAFORT 14 PTA 2', 'adulto', NULL, 'activo', 19, '2026-05-28 13:33:01', NULL),
(61, 'JESUS', 'GIMENEZ POLO', '73554357N', '1973-07-14', 'Hombre', '625623445', NULL, 'VIRGEN DESAMPARADOS 41-13', 'adulto', NULL, 'activo', 20, '2026-05-28 13:33:01', NULL),
(62, 'CRISTINA', 'PEREZ LACUEVA', '48435541W', '1977-12-15', 'Mujer', '653638293', NULL, 'VIRGEN DESAMPARADOS 41-13', 'adulto', NULL, 'activo', 20, '2026-05-28 13:33:01', NULL),
(63, 'CLAUDIA', 'GIMENEZ PEREZ', '54747399Q', '2011-04-18', 'Mujer', '653638293', NULL, 'VIRGEN DESAMPARADOS 41-13', 'adulto', NULL, 'activo', 20, '2026-05-28 13:33:01', NULL),
(64, 'VEGA', 'GIMENEZ PEREZ', '54747400V', '2016-02-09', 'Mujer', '653638293', NULL, 'VIRGEN DESAMPARADOS 41-13', 'infantil', NULL, 'activo', 20, '2026-05-28 13:33:01', NULL),
(65, 'Mª DEL PILAR', 'ESTELLÉS CIFRE', '48436499V', '1978-05-12', 'Mujer', '659950804', NULL, 'SAN BARTOLOMÉ 57', 'adulto', NULL, 'activo', 22, '2026-05-28 13:33:01', NULL),
(66, 'BLAI', 'LAFUENTE ESTELLÉS', 'FN20150318', '2015-03-18', 'Hombre', '659950804', NULL, 'SAN BARTOLOMÉ 57', 'infantil', NULL, 'activo', 22, '2026-05-28 13:33:01', NULL),
(67, 'NIKOL', 'LEAL VASHAKIDZE', 'FN20211125', '2021-11-25', 'Mujer', '651427054', NULL, 'CARRETERA DE RODAFORT 5', 'infantil', NULL, 'activo', 23, '2026-05-28 13:33:01', NULL),
(68, 'LIA', 'LEAL VASHAKIDZE', 'FN20240616', '2024-06-16', 'Mujer', '651427054', NULL, 'CARRETERA DE ROCAFORT 5', 'infantil', NULL, 'activo', 23, '2026-05-28 13:33:01', NULL),
(69, 'KASI', 'LILLO MONTERO', '52659092V', '1975-02-17', 'Hombre', '651904950', NULL, 'C/MAESTRO GINER 30-6', 'adulto', NULL, 'activo', 24, '2026-05-28 13:33:01', NULL),
(70, 'LOLI', 'ESCORIHUELA MOLINA', '48438211G', '1979-02-07', 'Mujer', '678255969', NULL, 'C/MAESTRO GINER 30-6', 'adulto', NULL, 'activo', 24, '2026-05-28 13:33:01', NULL),
(71, 'HUGO', 'LILLO ESCORIHUELA', 'FN20150918', '2015-09-18', 'Hombre', NULL, NULL, 'C/MAESTRO GINER 30-6', 'infantil', NULL, 'activo', 24, '2026-05-28 13:33:01', NULL),
(72, 'CRISTINA', 'MESA VERCHER', '48590657Y', '1988-05-23', 'Mujer', '620006324', NULL, 'SAN BARTOLOME 22', 'adulto', NULL, 'activo', 25, '2026-05-28 13:33:01', NULL),
(73, 'PEDRO', 'LOPE GIL', '33568382C', '1986-10-13', 'Hombre', '676285594', NULL, 'SAN BARTOLOME 22', 'adulto', NULL, 'activo', 25, '2026-05-28 13:33:01', NULL),
(74, 'DANIELA', 'LOPE MESA', 'FN20160916', '2016-09-16', 'Mujer', '620006324', NULL, 'SAN BARTOLOME 22', 'infantil', NULL, 'activo', 25, '2026-05-28 13:33:01', NULL),
(75, 'FRANCISCO', 'MARCO SOLA', '48435264R', '1982-05-20', 'Hombre', '646853560', NULL, 'C/ EL PUIG 19-4', 'adulto', NULL, 'activo', 26, '2026-05-28 13:33:01', NULL),
(76, 'INMACULADA', 'ARGUISUELAS MATEU', '48440594H', '1983-04-29', 'Mujer', '639513002', NULL, 'C/ EL PUIG 19-4', 'adulto', NULL, 'activo', 26, '2026-05-28 13:33:01', NULL),
(77, 'FRAN', 'MARCO ARGUISUELAS', '49358802C', '2011-04-20', 'Hombre', '639513002', NULL, 'C/ EL PUIG 19-4', 'adulto', NULL, 'activo', 26, '2026-05-28 13:33:01', NULL),
(78, 'ALEXANDRA', 'MARCO ARGUISUELAS', '49359038A', '2015-09-15', 'Mujer', '639513002', NULL, 'C/ EL PUIG 19-4', 'infantil', NULL, 'activo', 26, '2026-05-28 13:33:01', NULL),
(79, 'JOSÉ JOAQUÍN', 'MARCO SOLA', '48435263T', '1977-06-23', 'Hombre', '635142653', NULL, 'AVENIDA MUSSEROS 29-3', 'adulto', NULL, 'activo', 27, '2026-05-28 13:33:01', NULL),
(80, 'CARMEN', 'MARCO FERNÁNDEZ', '21801687X', '2008-10-08', 'Mujer', '635142653', NULL, 'AVENIDA MUSSEROS 29-3', 'adulto', NULL, 'activo', 27, '2026-05-28 13:33:01', NULL),
(81, 'CELIA', 'MARCO FERNÁNDEZ', '26664083E', '2013-12-21', 'Mujer', '635142653', NULL, 'AVENIDA MUSSEROS 29-3', 'infantil', NULL, 'activo', 27, '2026-05-28 13:33:01', NULL),
(82, 'CAROL', 'UTIEL ESCRICH', '22599647P', '1988-10-18', 'Mujer', '628243831', NULL, 'RAMÓN MUNTANER 6-11', 'adulto', NULL, 'activo', 28, '2026-05-28 13:33:01', NULL),
(83, 'IZÁN', 'MARTÍNEZ UTIEL', 'FN20220309', '2022-03-09', 'Hombre', '628243831', NULL, 'RAMÓN MUNTANER 6-11', 'infantil', NULL, 'activo', 28, '2026-05-28 13:33:01', NULL),
(84, 'ANA', 'MASET SOLA', '26626559B', '2006-09-15', 'Mujer', '651148453', NULL, 'ANTONIO COLOMER 4 - 33', 'adulto', NULL, 'activo', 29, '2026-05-28 13:33:01', NULL),
(85, 'MARINA', 'MASET SOLA', '26626563S', '2010-01-29', 'Mujer', '651148453', NULL, 'ANTONIO COLOMER 4 - 33', 'adulto', NULL, 'activo', 29, '2026-05-28 13:33:01', NULL),
(86, 'ALVARO', 'MATUT PEREZ', '54779333A', '2004-12-31', 'Hombre', '640047476', NULL, 'C/STO DOMINGO GUZMAN,5-20', 'adulto', NULL, 'activo', 30, '2026-05-28 13:33:01', NULL),
(87, 'MARIA', 'MATUT PEREZ', '54779332W', '2006-07-04', 'Mujer', '640754248', NULL, 'C/STO DOMINGO GUZMAN,5-20', 'adulto', NULL, 'activo', 30, '2026-05-28 13:33:01', NULL),
(88, 'SARA', 'MAYORDOMO ESCUDERO', '54778626D', '2017-09-25', 'Mujer', '625317141', NULL, 'C/ POPA 18', 'infantil', NULL, 'activo', 31, '2026-05-28 13:33:01', NULL),
(89, 'LUCIA', 'MAYORDOMO ESCUDERO', '50326970A', '2011-01-18', 'Mujer', '625317141', NULL, 'C/ POPA 18', 'adulto', NULL, 'activo', 31, '2026-05-28 13:33:01', NULL),
(90, 'LUIS', 'MAYORDOMO URIETA', '04587587F', '1977-04-25', 'Hombre', '625317142', NULL, 'POPA 18 URBANIZACION MARAVISA', 'adulto', NULL, 'activo', 31, '2026-05-28 13:33:01', NULL),
(91, 'VANESSA GEMMA', 'ESCUDERO ILLANA', '48438081N', '1977-04-26', 'Mujer', '625317141', NULL, 'POPA 18 URBANIZACION MARAVISA', 'adulto', NULL, 'activo', 31, '2026-05-28 13:33:01', NULL),
(92, 'FERNANDO', 'MORENO OCTAVIO DE TOLEDO', '48437790C', '1976-05-24', 'Hombre', '693750056', NULL, 'C/BARÓN DE SANTA BÁRBARA, 47', 'adulto', NULL, 'activo', 32, '2026-05-28 13:33:01', NULL),
(93, 'MONICA', 'ALARCON TABORIKOVA', '48678578K', '2007-01-22', 'Mujer', '640019074', NULL, 'C/BARÓN DE SANTA BÁRBARA, 17', 'adulto', NULL, 'activo', 32, '2026-05-28 13:33:01', NULL),
(94, 'VEGA', 'MORENO ARAQUE', 'FN20160629', '2016-06-29', 'Mujer', '645839536', '', 'BARÓN DE SANTA BÁRBARA 47', 'infantil', NULL, 'activo', 33, '2026-05-28 13:33:01', '2026-05-31 00:00:00'),
(95, 'MAITE', 'ARAQUE ELVIRA', '44858509E', '1977-11-24', 'Mujer', '645839536', NULL, 'BARÓN DE SANTA BÁRBARA 47', 'adulto', NULL, 'activo', 33, '2026-05-28 13:33:01', NULL),
(96, 'JAVIER', 'NAHARRO RODRIGUEZ', '48305609C', '1983-05-05', 'Hombre', '608144999', NULL, 'CALLE PUIG 38 ESC 2 PTA 10', 'adulto', NULL, 'activo', 34, '2026-05-28 13:33:01', NULL),
(97, 'VERONICA', 'NAVARRO SAURA', '48595139A', '1988-06-22', 'Mujer', '626306357', NULL, 'CALLE PUIG 38 ESC 2 PTA 10', 'adulto', NULL, 'activo', 34, '2026-05-28 13:33:01', NULL),
(98, 'EMMA', 'NAHARRO NAVARRO', 'FN20200112', '2020-01-12', 'Mujer', '626306357', NULL, 'CALLE PUIG 38 ESC 2 PTA 10', 'infantil', NULL, 'activo', 34, '2026-05-28 13:33:01', NULL),
(99, 'MARCOS', 'NAHARRO NAVARRO', 'FN20160704', '2016-07-04', 'Hombre', '626306357', NULL, 'CALLE PUIG 38 ESC 2 PTA 10', 'infantil', NULL, 'activo', 34, '2026-05-28 13:33:01', NULL),
(100, 'ÁNGEL', 'NAVARRO ZORNOZA', '19837526A', '1961-02-26', 'Hombre', '625440510', NULL, 'PASAJE PROFESOR RICARDO ORBAICETA 2-43', 'adulto', NULL, 'activo', 35, '2026-05-28 13:33:01', NULL),
(101, 'LALI', 'SAURA TORROMÉ', '24335588R', '1963-11-07', 'Mujer', '677582977', NULL, 'PASAJE PROFESOR RICARDO ORBAICETA 2-43', 'adulto', NULL, 'activo', 35, '2026-05-28 13:33:01', NULL),
(102, 'ISABEL', 'CASTRO MARTILLO', '55270951L', '1975-03-28', 'Mujer', NULL, NULL, 'CARRETERA DE ROCAFORT 28 PISO 3 PTA 12', 'adulto', NULL, 'activo', 36, '2026-05-28 13:33:01', NULL),
(103, 'NEREA', 'NUÑEZ CASTRO', '49357384M', '2012-10-30', 'Mujer', NULL, NULL, 'CARRETERA DE ROCAFORT 28 PISO 3 PTA 12', 'infantil', NULL, 'activo', 36, '2026-05-28 13:33:01', NULL),
(104, 'Mª LUISA', 'GONZALEZ HERVAS', '22682185E', '1962-07-09', 'Mujer', '646535683', NULL, 'JOSÉ LÓPEZ TRIGO 4 - 16', 'adulto', NULL, 'activo', 38, '2026-05-28 13:33:01', NULL),
(105, 'FRANCISCO', 'ORTÍZ MARQUEZ', '73538103L', '1958-06-28', 'Hombre', NULL, NULL, 'JOSÉ LÓPEZ TRIGO 4 - 16', 'adulto', NULL, 'activo', 38, '2026-05-28 13:33:01', NULL),
(106, 'NOELIA', 'ORTS BADENES', '49469129Q', '2003-12-10', 'Mujer', '674049654', NULL, 'C/ SANTA AURORA 4-12', 'adulto', NULL, 'activo', 37, '2026-05-28 13:33:01', NULL),
(107, 'PABLO', 'ORTS BADENES', '49469130V', '2006-08-17', 'Hombre', '674049654', NULL, 'C/ SANTA AURORA 4-12', 'adulto', NULL, 'activo', 37, '2026-05-28 13:33:01', NULL),
(108, 'ÁNGEL', 'PÉREZ GIMÉNEZ', '52656776R', '1978-02-24', 'Hombre', '645109697', NULL, 'C/ ARZOBISPO FUERO 46-2', 'adulto', NULL, 'activo', 40, '2026-05-28 13:33:01', NULL),
(109, 'FRAN', 'PÉREZ CARDO', '23919971V', '2005-09-05', 'Hombre', '664358730', NULL, 'C/ ARZOBISPO FUERO 46-2', 'adulto', NULL, 'activo', 40, '2026-05-28 13:33:01', NULL),
(110, 'INÉS', 'CARDO PLA', '52656964M', '1979-10-09', 'Mujer', '666866995', NULL, 'C/ ARZOBISPO FUERO 46-2', 'adulto', NULL, 'activo', 40, '2026-05-28 13:33:01', NULL),
(111, 'INÉS', 'PÉREZ CARDO', '49355459N', '2009-01-04', 'Mujer', NULL, NULL, 'C/ ARZOBISPO FUERO 46-2', 'adulto', NULL, 'activo', 40, '2026-05-28 13:33:01', NULL),
(112, 'JOSE MARIA', 'CARDO MORA', '73525392G', '1948-12-19', 'Hombre', '647790566', NULL, 'ARZOBISPO FUERO 46, PTA 2', 'adulto', NULL, 'activo', 40, '2026-05-28 13:33:01', NULL),
(113, 'INES', 'PLA FEO', '20390852H', '1951-08-23', 'Mujer', '647790566', NULL, 'ARZOBISPO FUERO 46, PTA 2', 'adulto', NULL, 'activo', 40, '2026-05-28 13:33:01', NULL),
(114, 'ALEJANDRA', 'MORENO PASCUAL', '73594895R', '1993-08-12', 'Mujer', '727716689', NULL, 'C/PUERTO DE VALENCIA 22', 'adulto', NULL, 'activo', 39, '2026-05-28 13:33:01', NULL),
(115, 'ROBERTO', 'PEREZ APARICIO', '48442756H', '1987-12-28', 'Hombre', '649921097', NULL, 'C/PUERTO DE VALENCIA 22', 'adulto', NULL, 'activo', 39, '2026-05-28 13:33:01', NULL),
(116, 'ADRIANA', 'PEREZ MORENO', 'FN20210906', '2021-09-06', 'Mujer', NULL, NULL, 'C/PUERTO DE VALENCIA 22', 'infantil', NULL, 'activo', 39, '2026-05-28 13:33:01', NULL),
(117, 'SERGIO', 'PEREZ MORENO', 'FN20230102', '2023-01-02', 'Hombre', NULL, NULL, 'C/PUERTO DE VALENCIA 22', 'infantil', NULL, 'activo', 39, '2026-05-28 13:33:01', NULL),
(118, 'ANGELA', 'PEIRO MONCHOLI', '48708487F', '1996-01-06', 'Mujer', '672764526', NULL, 'SANTA TERESA 23', 'adulto', NULL, 'activo', 41, '2026-05-28 13:33:01', NULL),
(119, 'BRYAN', 'QUINTERO MENDEZ', '55270348Z', '1994-04-28', 'Hombre', '639030948', NULL, 'SANTA TERESA 23', 'adulto', NULL, 'activo', 41, '2026-05-28 13:33:01', NULL),
(120, 'LUCÍA', 'QUINTERO PEIRO', 'FN20160302', '2016-03-02', 'Mujer', '672764526', NULL, 'SANTA TERESA 23', 'infantil', NULL, 'activo', 41, '2026-05-28 13:33:01', NULL),
(121, 'AMAIA', 'QUINTERO PEIRO', 'FN20210116', '2021-01-16', 'Mujer', '672764526', NULL, 'SANTA TERESA 23', 'infantil', NULL, 'activo', 41, '2026-05-28 13:33:01', NULL),
(122, 'AROA', 'ALCALA REDONDO', '49355609R', '2007-12-18', 'Mujer', '633971812', NULL, 'CRTA/ROCAFORT 20-11', 'adulto', NULL, 'activo', 42, '2026-05-28 13:33:01', NULL),
(123, 'ZAIRA', 'REDONDO OLIVA', '48439538C', '1983-09-17', 'Mujer', '722204430', NULL, 'CRTA/ROCAFORT 20-11', 'adulto', NULL, 'activo', 42, '2026-05-28 13:33:01', NULL),
(124, 'LOLA', 'CONTRERAS JIMÉNEZ', '48438393W', '1983-07-25', 'Mujer', '600753197', NULL, 'CASTELL Nº5 PTA1', 'adulto', NULL, 'activo', 46, '2026-05-28 13:33:01', NULL),
(125, 'ALBERTO', 'SÁNCHEZ CONTRERAS', '73667067E', '2010-03-01', 'Hombre', '600753197', NULL, 'CASTELL Nº5 PTA1', 'adulto', NULL, 'activo', 46, '2026-05-28 13:33:01', NULL),
(126, 'NATXO', 'SÁNCHEZ CONTRERAS', '73377321F', '2013-09-12', 'Hombre', '600753197', NULL, 'CASTELL Nº5 PTA1', 'infantil', NULL, 'activo', 46, '2026-05-28 13:33:01', NULL),
(127, 'ERNESTO', 'SANCHEZ ORTEGA', '48597186A', '1989-12-30', 'Hombre', '665775677', NULL, 'BONAVISTA 43-1', 'adulto', NULL, 'activo', 44, '2026-05-28 13:33:01', NULL),
(128, 'ANA', 'CORBERÁN IZQUIERDO', '48590136Z', '1985-02-11', 'Mujer', '675872256', NULL, 'BONAVISTA 43-1', 'adulto', NULL, 'activo', 44, '2026-05-28 13:33:01', NULL),
(129, 'JOAQUIN', 'SANCHEZ ORTEGA', '48599977B', '1991-09-21', 'Hombre', '672776149', NULL, 'SANTA TERESA 5 PTA 6', 'adulto', NULL, 'activo', 43, '2026-05-28 13:33:01', NULL),
(130, 'Mª ANGELES', 'MILLAN LABRADA', '48597512F', '1992-12-08', 'Mujer', '699538328', NULL, 'SANTA TERESA 5 PTA 6', 'adulto', NULL, 'activo', 43, '2026-05-28 13:33:01', NULL),
(131, 'AITANA', 'SOLER BOFILL', 'FN20150430', '2015-04-30', 'Mujer', '619404895', NULL, 'MESTRE VICENT ALONSO 2 - 20', 'infantil', NULL, 'activo', 45, '2026-05-28 13:33:01', NULL),
(132, 'ALEIX', 'SOLER BOFILL', 'FN20170801', '2017-08-01', 'Hombre', '600221991', NULL, 'MESTRE VICENT ALONSO 2 - 20', 'infantil', NULL, 'activo', 45, '2026-05-28 13:33:01', NULL),
(133, 'FRANÇESC VICENT', 'SOLER IZQUIERDO', '29188546M', '1978-11-24', 'Hombre', '619404895', NULL, 'MESTRE VICENT ALONSO 2 - 20', 'adulto', NULL, 'activo', 45, '2026-05-28 13:33:01', NULL),
(134, 'JULIA', 'BOFILL MOLLA', '48438404J', '1979-03-23', 'Mujer', '600221991', NULL, 'MESTRE VICENT ALONSO 2 - 20', 'adulto', NULL, 'activo', 45, '2026-05-28 13:33:01', NULL),
(135, 'JOHNATAN', 'TEIXEIRA MARTINS', '20250872Q', '1987-04-19', 'Hombre', '699041242', NULL, 'CARRER VILLA BLANCA 27-20', 'adulto', NULL, 'activo', 47, '2026-05-28 13:33:01', NULL),
(136, 'SABINA', 'JUAREZ REIG', '48435750G', '1977-01-04', 'Mujer', '722457353', NULL, 'CARRER VILLA BLANCA 27-20', 'adulto', NULL, 'activo', 47, '2026-05-28 13:33:01', NULL),
(137, 'MARIA JESÚS', 'BERNAL ARNEDO', '48437934A', '1976-12-11', 'Mujer', '630472760', NULL, 'C/ ARZOBISPO FUERO 46-1', 'adulto', NULL, 'activo', 48, '2026-05-28 13:33:01', NULL),
(138, 'MANUEL', 'TOMÁS SOLER', '22571900E', '1978-01-01', 'Hombre', '665835499', NULL, 'C/ ARZOBISPO FUERO 46-1', 'adulto', NULL, 'activo', 48, '2026-05-28 13:33:01', NULL),
(139, 'INMA', 'ESCRICH SOLER', '52727556X', '1970-09-23', 'Mujer', '655534248', NULL, 'PLAZA DIPUTACION 2-6', 'adulto', NULL, 'activo', 49, '2026-05-28 13:33:01', NULL),
(140, 'VALERIA', 'UTIEL ESCRICH', '49359063M', '2008-07-12', 'Mujer', '627751318', NULL, 'PLAZA DIPUTACION 2-6', 'adulto', NULL, 'activo', 49, '2026-05-28 13:33:01', NULL),
(141, 'PASCUAL', 'BAREA CAMPIZANO', '22685487N', '1960-11-15', 'Hombre', '665814826', '', 'PLAZA DIPUTACION 2-3', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', '2026-05-28 14:47:43'),
(142, 'JOSÉ', 'MARCO YERBES', '73747310H', '1952-01-23', 'Hombre', '644640600', NULL, 'C/ SANTA TERESA 3-3', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(143, 'INÉS', 'SOLA SERRANO', '73725192A', '1950-12-31', 'Mujer', '644905346', NULL, 'C/ SANTA TERESA 3-3', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(144, 'JAUME', 'SILVESTRE GUAITA', '48438683Q', '1978-11-30', 'Hombre', '661956036', NULL, 'C/ SAN BARTOLOME 77-4', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(145, 'SUSANA', 'SILVESTRE GUAITA', '52659637X', '1974-06-06', 'Mujer', '654696435', NULL, 'C/ SAN BARTOLOME 77-4', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(146, 'CRISTINA', 'MENCHÓN SÁNCHEZ', '49352174Q', '2002-12-18', 'Mujer', '634405507', NULL, 'C/ MARÍA ROS 50-9', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(147, 'DAVID', 'CORTELL AGUILAR', '50326279W', '2005-06-06', 'Hombre', '644237791', NULL, 'CTRA. ROCAFORT 28-4', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(148, 'ALVARO', 'FERNANDEZ ALONSO', '35606820Z', '2001-03-07', 'Hombre', '601205474', NULL, 'RAMON Y CAJAL 1E', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(149, 'RAUL', 'TEIJEIRO ANIORTE', '35606201Q', '2002-04-04', 'Hombre', '601219419', NULL, 'DOCTOR JOSE VILELLA 2 PUERTA 5', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(150, 'LAURA', 'MONTESINOS MURILLO', '44868470R', '1979-02-24', 'Mujer', '680541413', NULL, 'PASAJE PROFESOR RICARDO ORBAICENA 1-20', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(151, 'GEMA', 'BAYONA QUIJANA', '55271002R', '2007-09-08', 'Mujer', '679110560', NULL, 'JUPITER 12', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(152, 'MARTA', 'SERRANO RODRIGO', '26578469Z', '2011-09-23', 'Mujer', '690069520', NULL, 'AVENIDA LOS ALMENDROS 8-6', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(153, 'NURIA', 'BAYONA QUIJANA', '55271001T', '2005-09-21', 'Mujer', '679110560', NULL, 'CALLE JUPITER PUERTA 12', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(154, 'MIRIAM', 'CORELL MATOSES', '49603947P', '2009-05-04', 'Mujer', '646139628', NULL, 'RAMÓN Y CAJAL 3 PTA 6', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(155, 'MIGUEL JOSÉ', 'MONTESINOS MURILLO', '48443581S', '1982-06-29', 'Hombre', '675111394', NULL, 'MENENDEZ PELAYO 7 - 7', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(156, 'TONI', 'LEAL MOYA', '29182849N', '1973-09-02', 'Hombre', '650390184', NULL, 'CARRETERA DE ROCAFORT 16 PTA 1', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(157, 'SUSI', 'PÉREZ LACUEVA', '29175676S', '1973-02-19', 'Mujer', '669714335', NULL, 'SANTO DOMINGO DE GUZMAN 5 PTA 20 IZQ', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(158, 'RAFA', 'FERRANDO MUÑOZ', '49353229J', '2002-04-17', 'Hombre', '652564381', NULL, 'CALLE MONCADA 1 - 1', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(159, 'AINHOA', 'NAVAS GOMERA', '45904187M', '2004-09-07', 'Mujer', '600394617', NULL, 'CALLE BLANQUERS 4 PTA 2', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(160, 'ELVIRA', 'VILLALBA MARRUFO', '31317821D', '1956-04-04', 'Mujer', '677344041', NULL, 'C/ BETERA 4 PTA 2', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(161, 'BLANCA', 'SÁNCHEZ GÁLVEZ', 'FN20130420', '2013-04-20', 'Mujer', '661091777', NULL, 'SAN BARTOLOMÉ 46 ESC B PTA 14', 'infantil', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(162, 'JESÚS', 'BENET MARTÍNEZ', '24337170L', '1965-04-03', 'Hombre', '654696435', NULL, 'SAN BARTOLOMÉ 77 PTA 14', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(163, 'ALFONSO', 'DOMENECH AGUILAR', '73768735F', '1971-03-21', 'Hombre', '659419825', NULL, 'MAESTRO CHAPI 2 PTA1', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(164, 'SOFÍA', 'LLATAS GARCÍA', '49602427Y', '2009-10-06', 'Mujer', NULL, NULL, 'CALLE LEPANTO  3', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(165, 'EVA', 'LAZO ESTELLÉS', '52656394X', '1973-04-26', 'Mujer', '691352315', NULL, 'PLAZA DE LA DIPUTACIÓN 2-5', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(166, 'EDUARDO', 'ALCANTARA MORATO', '50597572X', '2012-05-21', 'Hombre', '615879735', NULL, 'CALLE GODELLA 11', 'infantil', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(167, 'IRENE', 'LOPEZ SORIANO', '50594296T', '2012-09-03', 'Mujer', '669100058', NULL, 'C/BÉTERA, 39-10', 'infantil', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(168, 'DAVID', 'GARRIGOS RODENAS', '44529015A', '1989-08-06', 'Hombre', '697702031', NULL, 'AVDA. CARDENAL BENLLOCH, 47-11', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(169, 'MARIA', 'ORTIZ GONZALEZ', '48599338Q', '1991-06-12', 'Mujer', '699021831', NULL, 'C/JOSÉ LÓPEZ TRIGO, 4-16', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(170, 'Mª CARMEN', 'MORENO OCTAVIO DE TOLEDO', '48437510Q', '1974-09-17', 'Mujer', '649588284', NULL, 'C/VIRGEN DE LOS DESAMPARADOS, 27-11', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(171, 'ANA ARACELY', 'CASTRO BROVO', '54527069A', '1970-02-06', 'Mujer', '690006135', NULL, 'C/FRAY LUIS AMIGÓ', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(172, 'ALBA', 'CATALA GOMEZ', '49182280T', '2009-04-25', 'Mujer', '666696423', NULL, 'C/CONVENTO, 20-1-8', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(173, 'POL', 'MOLLA SANCHEZ', '70062818B', '1984-04-07', 'Hombre', '639264200', NULL, 'C/VILA BLANCA, 27-25', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(174, 'JUDIT', 'MARTINEZ JUAREZ', 'FN20160804', '2016-08-04', 'Mujer', '722457353', NULL, 'C/VILA BLANCA, 27-20', 'infantil', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(175, 'NAIARA', 'CONTRERAS MUÑOZ', '49358932N', '2004-10-11', 'Mujer', '610435401', NULL, 'CALLE SAN BARTOLOME 63', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(176, 'MARINA', 'CASINOS JUNQUERO', '49180153N', '2004-07-01', 'Mujer', '645713447', NULL, 'CALLE DOCTOR JOSE LOPEZ TRIGO,2', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(177, 'LOLA', 'MOLLA MARAVILLA', 'FN20130622', '2013-06-22', 'Mujer', NULL, NULL, 'CALLE ISAAC PERAL 58 A PTA 8', 'infantil', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(178, 'ALEJANDRO', 'CERVERA GARCIA', '73676601B', '2010-12-14', 'Hombre', '671247707', NULL, 'AVD DERECHOS HUMANOS 5-8', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(179, 'ALICIA', 'SEMPERE FRANCES', '29168766M', '1969-06-11', 'Mujer', '679345732', NULL, 'C/VILLA BLANCA 25-6', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(180, 'CARLA', 'ROMERO CAÑAVERAS', '54779611M', '2000-12-12', 'Mujer', '695322680', NULL, 'C/HISPANIDAD ,56', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(181, 'JAQUELIN', 'MARTINEZ VALIENTE', '22586308D', '1982-08-05', 'Mujer', '620994623', NULL, 'C/SANBARTOLOME ,75-7', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(182, 'JAVIER', 'ARTERO MARTINEZ', '56180375T', '2017-01-22', 'Hombre', '620994623', NULL, 'C/SAN BARTOLOME ,75-7', 'infantil', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(183, 'SHEILA', 'LEAL BORJA', '48597663C', '1990-12-28', 'Mujer', '651427054', NULL, 'CRTA/ROCAFORT ,5', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(184, 'AINHOA ABIGAIL', 'SOSA LOOR', '51157219K', '2008-01-23', 'Mujer', '645054551', NULL, 'CRTA/ROCAFORT 28--12', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(185, 'CARMEN', 'REDONDO MORENO', '39125109Q', '1951-07-06', 'Mujer', '674517108', NULL, 'CRTA/ROCAFORT 18-1', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(186, 'DESIREE', 'CUELLAR CORDOBA', '49353387X', '2005-01-17', 'Mujer', '747748738', NULL, 'C/ARZOBISPO FUERO', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL),
(187, 'LAURA', 'MONSALVEZ ANTON', '49359032C', '2009-01-05', 'Mujer', '643412743', NULL, 'CALLE ANTONIO MAURA,1-2', 'adulto', NULL, 'activo', NULL, '2026-05-28 13:33:01', NULL);

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
(4, 'AGUILAR HORTELANO', 6, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(5, 'ANDREU MARCO', 13, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(6, 'AVILÉS HERNANDEZ', 19, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(7, 'BAUSACH CORRAL', 20, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(8, 'BELLVER ESCUDERO', 25, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(9, 'BELLVER SANCHEZ', 26, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:38:27'),
(10, 'BLASCO LEAL', 29, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:38:45'),
(11, 'BLASCO TORRENT', 33, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(12, 'CASTILLA BENLLOCH', 35, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(13, 'CHAPARRO AVILÉS', 36, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(14, 'CLEMENTE SORIANO', 40, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:39:54'),
(15, 'COBO CUQUERELLA', 44, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(16, 'CONTRERAS RODRÍGUEZ', 46, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(17, 'GARCIA AHGBAR', 51, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(18, 'GARCIA GARCÍA', 54, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(19, 'GARCÍA PÉREZ', 60, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(20, 'GIMENEZ PEREZ', 61, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(21, 'GÁLVEZ MOLLÁ', 50, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:41:10'),
(22, 'LAFUENTE ESTELLÉS', 65, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:42:15'),
(23, 'LEAL VASHAKIDZE', 67, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(24, 'LILLO ESCORIHUELA', 69, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(25, 'LOPE MESA', 73, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(26, 'MARCO ARGUISUELAS', 75, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(27, 'MARCO FERNÁNDEZ', 79, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(28, 'MARTÍNEZ UTIEL', 82, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:44:04'),
(29, 'MASET SOLA', 84, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(30, 'MATUT PEREZ', 86, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(31, 'MAYORDOMO ESCUDERO', 90, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(32, 'MORENO ALARCON', 92, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(33, 'MORENO ARAQUE', 95, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:44:52'),
(34, 'NAHARRO NAVARRO', 96, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(35, 'NAVARRO SAURA', 100, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(36, 'NUÑEZ CASTRO', 102, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:56:18'),
(37, 'ORTS BADENES', 106, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(38, 'ORTÍZ GONZALEZ', 105, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(39, 'PEREZ MORENO', 115, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(40, 'PÉREZ CARDO', 110, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 14:00:42'),
(41, 'QUINTERO PEIRO', 119, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(42, 'REDONDO', 123, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(43, 'SANCHEZ MILLAN', 129, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(44, 'SANCHÉZ CORBERÁN', 128, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(45, 'SOLER BOFILL', 133, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(46, 'SÁNCHEZ CONTRERAS', 124, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(47, 'TEIXEIRA JUAREZ', 136, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(48, 'TOMÁS BERNAL', 137, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01'),
(49, 'UTIEL ESCRICH', 139, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 14:47:57'),
(50, 'ÁLAMO MONTERO', 11, 'Importada desde Excel censo 11-05-2026', '2026-05-28 13:33:01', '2026-05-28 13:33:01');

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
(4, 5, '2026-05-28 13:33:01'),
(4, 6, '2026-05-28 13:33:01'),
(5, 13, '2026-05-28 13:33:01'),
(5, 14, '2026-05-28 13:33:01'),
(6, 18, '2026-05-28 13:33:01'),
(6, 19, '2026-05-28 13:33:01'),
(7, 20, '2026-05-28 13:33:01'),
(7, 21, '2026-05-28 13:33:01'),
(8, 24, '2026-05-28 13:33:01'),
(8, 25, '2026-05-28 13:33:01'),
(9, 26, '2026-05-28 13:38:27'),
(10, 29, '2026-05-28 13:38:45'),
(11, 32, '2026-05-28 13:33:01'),
(11, 33, '2026-05-28 13:33:01'),
(12, 34, '2026-05-28 13:33:01'),
(12, 35, '2026-05-28 13:33:01'),
(13, 36, '2026-05-28 13:33:01'),
(13, 38, '2026-05-28 13:33:01'),
(14, 40, '2026-05-28 13:39:54'),
(15, 42, '2026-05-28 13:33:01'),
(15, 44, '2026-05-28 13:33:01'),
(16, 46, '2026-05-28 13:33:01'),
(16, 47, '2026-05-28 13:33:01'),
(17, 51, '2026-05-28 13:33:01'),
(17, 52, '2026-05-28 13:33:01'),
(18, 54, '2026-05-28 13:33:01'),
(18, 55, '2026-05-28 13:33:01'),
(19, 57, '2026-05-28 13:33:01'),
(19, 60, '2026-05-28 13:33:01'),
(20, 61, '2026-05-28 13:33:01'),
(20, 62, '2026-05-28 13:33:01'),
(21, 50, '2026-05-28 13:41:10'),
(22, 65, '2026-05-28 13:42:15'),
(23, 67, '2026-05-28 13:33:01'),
(23, 68, '2026-05-28 13:33:01'),
(24, 69, '2026-05-28 13:33:01'),
(24, 70, '2026-05-28 13:33:01'),
(25, 72, '2026-05-28 13:33:01'),
(25, 73, '2026-05-28 13:33:01'),
(26, 75, '2026-05-28 13:33:01'),
(26, 76, '2026-05-28 13:33:01'),
(27, 79, '2026-05-28 13:33:01'),
(27, 80, '2026-05-28 13:33:01'),
(28, 82, '2026-05-28 13:44:04'),
(29, 84, '2026-05-28 13:33:01'),
(29, 85, '2026-05-28 13:33:01'),
(30, 86, '2026-05-28 13:33:01'),
(30, 87, '2026-05-28 13:33:01'),
(31, 90, '2026-05-28 13:33:01'),
(31, 91, '2026-05-28 13:33:01'),
(32, 92, '2026-05-28 13:33:01'),
(32, 93, '2026-05-28 13:33:01'),
(33, 95, '2026-05-28 13:44:52'),
(34, 96, '2026-05-28 13:33:01'),
(34, 97, '2026-05-28 13:33:01'),
(35, 100, '2026-05-28 13:33:01'),
(35, 101, '2026-05-28 13:33:01'),
(36, 102, '2026-05-28 13:56:18'),
(37, 106, '2026-05-28 13:33:01'),
(37, 107, '2026-05-28 13:33:01'),
(38, 104, '2026-05-28 13:33:01'),
(38, 105, '2026-05-28 13:33:01'),
(39, 114, '2026-05-28 13:33:01'),
(39, 115, '2026-05-28 13:33:01'),
(40, 109, '2026-05-28 14:00:42'),
(40, 110, '2026-05-28 14:00:42'),
(41, 118, '2026-05-28 13:33:01'),
(41, 119, '2026-05-28 13:33:01'),
(42, 122, '2026-05-28 13:33:01'),
(42, 123, '2026-05-28 13:33:01'),
(43, 129, '2026-05-28 13:33:01'),
(43, 130, '2026-05-28 13:33:01'),
(44, 127, '2026-05-28 13:33:01'),
(44, 128, '2026-05-28 13:33:01'),
(45, 133, '2026-05-28 13:33:01'),
(45, 134, '2026-05-28 13:33:01'),
(46, 124, '2026-05-28 13:33:01'),
(46, 125, '2026-05-28 13:33:01'),
(47, 135, '2026-05-28 13:33:01'),
(47, 136, '2026-05-28 13:33:01'),
(48, 137, '2026-05-28 13:33:01'),
(48, 138, '2026-05-28 13:33:01'),
(49, 139, '2026-05-28 14:47:57'),
(50, 11, '2026-05-28 13:33:01'),
(50, 12, '2026-05-28 13:33:01');

-- --------------------------------------------------------

--
-- Table structure for table `juntas`
--

CREATE TABLE `juntas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `fecha` date NOT NULL,
  `descripcion` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `juntas`
--

INSERT INTO `juntas` (`id`, `nombre`, `fecha`, `descripcion`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 'Junta', '2026-05-30', 'junta general', 1, '2026-05-30 22:28:44', '2026-05-30 23:52:10');

-- --------------------------------------------------------

--
-- Table structure for table `junta_archivos`
--

CREATE TABLE `junta_archivos` (
  `id` int(11) NOT NULL,
  `junta_id` int(11) NOT NULL,
  `nombre_original` varchar(255) NOT NULL,
  `ruta` varchar(500) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

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
(22, 12, 'Menu 2', '', NULL, 1, '2026-05-26 17:58:52'),
(23, 14, 'Menu 2', '', NULL, 1, '2026-05-28 11:22:08');

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
(3, 118, 'https://web.push.apple.com/QBZvZ5LPa7q4L50n4JZPxMgRZ2XiEj9mRf8f5QW7P9YPWAH2QtBivh-6ZksBRVFPtzTl1ylY2HN3QXHKm4eLUkU-5OyAghTK4Dm9xtEb3FzVXWQNg9jAshFkOPdTO5jk4fDhMHwBJN9BUQVuugY59Y7T1Jav4UGctbQp0VtMo5M', 'BD8_kR7DL1j8AL69bLyCamCHzbGTJL0tfBCuTYYu9R2RNAJ4kzoiF0-JbQDIPALipytNmmAE3S0utkDgPSUpJwY', 'N2R10RtH2pGt_prQdZHlig', '2026-05-14 17:19:59'),
(6, 118, 'https://fcm.googleapis.com/fcm/send/fJnur6bpvVc:APA91bGwaMWmMC_w8OOOJ0XsrRmH8tFv80xKe-8_f9Mnek08Wl6Vrf_V9mlo_YYZWYS3zA0lW3d9WiWGF4Nh8xZwYoWA8eqCZc7du5186Oet73zebtUKhJs4b8qRbxzUKH1_fCSsHhFi', 'BE6p28M5OcrF1ioEqXFyaG1XF9doTlndMEWRKGakGsHFDk2IIImz5hJ2XPAJzzsqc3dPJ8yM38fBMVJwdEFKMKQ', 'ds9J1I59LlScyMVZ0rdzqQ', '2026-05-30 00:04:38');

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
(30, 146, 4, 7, NULL, NULL, NULL, 'confirmada', 1, '2026-05-29 21:03:42', 'e3e717850a5cb69cf6dcaa9d252a00123aec8c392f36468f611b6e5aa058422e', 0, NULL, NULL, '2026-05-29 21:03:31', NULL, '2026-05-29 21:03:31', '2026-05-29 21:03:42'),
(31, 146, 5, 9, NULL, NULL, NULL, 'confirmada', 1, '2026-05-29 21:05:03', '56fa390c3b043536366a5827ae8564302c8854ed0cbc68f00a19d78cf4513455', 0, NULL, NULL, '2026-05-29 21:04:41', NULL, '2026-05-29 21:04:41', '2026-05-29 21:05:03'),
(32, 147, 4, 7, NULL, NULL, NULL, 'confirmada', 1, '2026-05-29 23:56:40', '23d9fa4acb78300c5c6adafb1198e7252e65f95fbc0e3286edab7124e99ff081', 1, '2026-05-30 00:00:13', 1, '2026-05-29 23:56:05', NULL, '2026-05-29 23:56:05', '2026-05-30 00:00:13'),
(33, 147, 5, 9, NULL, NULL, NULL, 'confirmada', 1, '2026-05-29 23:57:28', '0c1867c2b12e9920c40c00d107cb41540d5a286d66e000e5de8cfabd010a8927', 1, '2026-05-29 23:59:00', 1, '2026-05-29 23:57:18', NULL, '2026-05-29 23:57:18', '2026-05-29 23:59:00'),
(34, 147, 11, 20, NULL, NULL, NULL, 'confirmada', 1, '2026-05-30 22:08:41', 'b5b87f300da233b418f1ee74eafe7427bd1fd1d0beef30de6a9bcd977842e82e', 0, NULL, NULL, '2026-05-30 22:08:18', NULL, '2026-05-30 22:08:18', '2026-05-30 22:08:41');

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
(1, '12345678A', '$2y$10$WQHJj0EgAV5bw8fRD3ZwBegUFktrAbMkdHcaxyhh8wnSyKX8LuDbO', 'admin', NULL, 1, '2026-05-31 19:23:52', 0, '2026-05-09 15:26:54', '2026-05-29 10:47:30'),
(5, '03197735E', '', 'fallero', 45, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(6, '04587587F', '', 'fallero', 90, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(7, '09108321E', '', 'fallero', 7, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(8, '09108323R', '', 'fallero', 8, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(9, '13311682H', '', 'fallero', 30, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(10, '17517741K', '', 'fallero', 53, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(11, '17519068Z', '', 'fallero', 43, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(12, '19837526A', '', 'fallero', 100, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(13, '20250872Q', '', 'fallero', 135, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(14, '20390852H', '', 'fallero', 113, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(15, '20839010K', '', 'fallero', 57, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(16, '21795908G', '', 'fallero', 16, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(17, '21801687X', '', 'fallero', 80, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(18, '22571900E', '', 'fallero', 138, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(19, '22586308D', '', 'fallero', 181, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(20, '22599647P', '', 'fallero', 82, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(21, '22682185E', '', 'fallero', 104, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(22, '22685487N', '', 'fallero', 141, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(23, '23872148B', '', 'fallero', 15, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(24, '23872150J', '', 'fallero', 17, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(25, '23919356T', '', 'fallero', 37, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(26, '23919357R', '', 'fallero', 39, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(27, '23919971V', '', 'fallero', 109, 1, '2026-05-28 14:49:09', 0, '2026-05-28 13:33:01', NULL),
(28, '24335588R', '', 'fallero', 101, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(29, '24337170L', '', 'fallero', 162, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(30, '26153947A', '', 'fallero', 19, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(31, '26578469Z', '', 'fallero', 152, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(32, '26626559B', '', 'fallero', 84, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(33, '26626563S', '', 'fallero', 85, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(34, '26664083E', '', 'fallero', 81, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(35, '26888786S', '', 'fallero', 22, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(36, '29163867M', '', 'fallero', 13, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(37, '29168766M', '', 'fallero', 179, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(38, '29175676S', '', 'fallero', 157, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(39, '29182849N', '', 'fallero', 156, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(40, '29188546M', '', 'fallero', 133, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(41, '29193513G', '', 'fallero', 25, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(42, '31317821D', '', 'fallero', 160, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(43, '33568382C', '', 'fallero', 73, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(44, '34272228C', '', 'fallero', 44, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(45, '35606201Q', '', 'fallero', 149, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(46, '35606820Z', '', 'fallero', 148, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(47, '39125109Q', '', 'fallero', 185, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(48, '44529015A', '', 'fallero', 168, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(49, '44858509E', '', 'fallero', 95, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(50, '44868470R', '', 'fallero', 150, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(51, '44869824K', '', 'fallero', 26, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(52, '44896870L', '', 'fallero', 29, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(53, '45904187M', '', 'fallero', 159, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(54, '48305609C', '', 'fallero', 96, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(55, '48311089A', '', 'fallero', 55, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(56, '48435262E', '', 'fallero', 14, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(57, '48435263T', '', 'fallero', 79, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(58, '48435264R', '', 'fallero', 75, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(59, '48435311W', '', 'fallero', 11, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(60, '48435541W', '', 'fallero', 62, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(61, '48435750G', '', 'fallero', 136, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(62, '48435767K', '', 'fallero', 54, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(63, '48436267S', '', 'fallero', 33, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(64, '48436294L', '', 'fallero', 46, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(65, '48436499V', '', 'fallero', 65, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(66, '48436517N', '', 'fallero', 47, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(67, '48437222G', '', 'fallero', 6, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(68, '48437510Q', '', 'fallero', 170, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(69, '48437607K', '', 'fallero', 40, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(70, '48437790C', '', 'fallero', 92, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(71, '48437934A', '', 'fallero', 137, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(72, '48438081N', '', 'fallero', 91, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(73, '48438165G', '', 'fallero', 60, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(74, '48438211G', '', 'fallero', 70, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(75, '48438393W', '', 'fallero', 124, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(76, '48438404J', '', 'fallero', 134, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(77, '48438683Q', '', 'fallero', 144, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(78, '48438911Z', '', 'fallero', 51, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(79, '48439371Z', '', 'fallero', 5, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(80, '48439509Z', '', 'fallero', 21, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(81, '48439538C', '', 'fallero', 123, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(82, '48440594H', '', 'fallero', 76, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(83, '48441465S', '', 'fallero', 32, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(84, '48441642P', '', 'fallero', 24, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(85, '48442756H', '', 'fallero', 115, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(86, '48443581S', '', 'fallero', 155, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(87, '48443651Q', '', 'fallero', 50, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(88, '48590136Z', '', 'fallero', 128, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(89, '48590657Y', '', 'fallero', 72, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(90, '48595139A', '', 'fallero', 97, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(91, '48597110L', '', 'fallero', 42, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(92, '48597186A', '', 'fallero', 127, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(93, '48597512F', '', 'fallero', 130, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(94, '48597663C', '', 'fallero', 183, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(95, '48599338Q', '', 'fallero', 169, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(96, '48599977B', '', 'fallero', 129, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(97, '48678578K', '', 'fallero', 93, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(98, '48708487F', '', 'fallero', 118, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(99, '49180153N', '', 'fallero', 176, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(100, '49182280T', '', 'fallero', 172, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(101, '49352174Q', '', 'fallero', 146, 1, '2026-05-29 22:35:56', 0, '2026-05-28 13:33:01', NULL),
(102, '49353043B', '', 'fallero', 10, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(103, '49353229J', '', 'fallero', 158, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(104, '49353387X', '', 'fallero', 186, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(105, '49355459N', '', 'fallero', 111, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(106, '49355609R', '', 'fallero', 122, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(107, '49357384M', '', 'fallero', 103, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(108, '49358802C', '', 'fallero', 77, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(109, '49358932N', '', 'fallero', 175, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(110, '49359032C', '', 'fallero', 187, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(111, '49359038A', '', 'fallero', 78, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(112, '49359063M', '', 'fallero', 140, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(113, '49359337A', '', 'fallero', 34, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(114, '49469129Q', '', 'fallero', 106, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(115, '49469130V', '', 'fallero', 107, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(116, '49602427Y', '', 'fallero', 164, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(117, '49603947P', '', 'fallero', 154, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(118, '50326279W', '', 'fallero', 147, 1, '2026-05-30 00:04:27', 0, '2026-05-28 13:33:01', NULL),
(119, '50326970A', '', 'fallero', 89, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(120, '50594296T', '', 'fallero', 167, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(121, '50597572X', '', 'fallero', 166, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(122, '51157219K', '', 'fallero', 184, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(123, '52652615A', '', 'fallero', 36, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(124, '52655745M', '', 'fallero', 35, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(125, '52656363W', '', 'fallero', 20, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(126, '52656394X', '', 'fallero', 165, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(127, '52656776R', '', 'fallero', 108, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(128, '52656964M', '', 'fallero', 110, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(129, '52659092V', '', 'fallero', 69, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(130, '52659637X', '', 'fallero', 145, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(131, '52727556X', '', 'fallero', 139, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(132, '54527069A', '', 'fallero', 171, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(133, '54747399Q', '', 'fallero', 63, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(134, '54747400V', '', 'fallero', 64, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(135, '54747986M', '', 'fallero', 9, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(136, '54778626D', '', 'fallero', 88, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(137, '54779332W', '', 'fallero', 87, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(138, '54779333A', '', 'fallero', 86, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(139, '54779611M', '', 'fallero', 180, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(140, '55270348Z', '', 'fallero', 119, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(141, '55270951L', '', 'fallero', 102, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(142, '55271001T', '', 'fallero', 153, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(143, '55271002R', '', 'fallero', 151, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(144, '55272107W', '', 'fallero', 12, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(145, '56180375T', '', 'fallero', 182, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(146, '70062818B', '', 'fallero', 173, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(147, '73377321F', '', 'fallero', 126, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(148, '73525392G', '', 'fallero', 112, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(149, '73538103L', '', 'fallero', 105, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(150, '73554357N', '', 'fallero', 61, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(151, '73594895R', '', 'fallero', 114, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(152, '73667067E', '', 'fallero', 125, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(153, '73676601B', '', 'fallero', 178, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(154, '73723446M', '', 'fallero', 18, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(155, '73725192A', '', 'fallero', 143, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(156, '73747310H', '', 'fallero', 142, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(157, '73768735F', '', 'fallero', 163, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(158, '73769296Q', '', 'fallero', 38, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(159, 'FN20130114', '', 'fallero', 27, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(160, 'FN20130420', '', 'fallero', 161, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(161, 'FN20130622', '', 'fallero', 177, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(162, 'FN20130813', '', 'fallero', 58, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(163, 'FN20150318', '', 'fallero', 66, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(164, 'FN20150430', '', 'fallero', 131, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(165, 'FN20150918', '', 'fallero', 71, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(166, 'FN20151116', '', 'fallero', 28, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(167, 'FN20160302', '', 'fallero', 120, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(168, 'FN20160529', '', 'fallero', 48, 1, NULL, 0, '2026-05-28 13:33:01', '2026-05-30 23:59:41'),
(169, 'FN20160704', '', 'fallero', 99, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(170, 'FN20160801', '', 'fallero', 56, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(171, 'FN20160804', '', 'fallero', 174, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(172, 'FN20160916', '', 'fallero', 74, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(173, 'FN20170204', '', 'fallero', 31, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(174, 'FN20170801', '', 'fallero', 132, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(175, 'FN20180315', '', 'fallero', 41, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(176, 'FN20190313', '', 'fallero', 49, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(177, 'FN20200112', '', 'fallero', 98, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(178, 'FN20200129', '', 'fallero', 23, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(179, 'FN20210116', '', 'fallero', 121, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(180, 'FN20210906', '', 'fallero', 116, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(181, 'FN20211125', '', 'fallero', 67, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(182, 'FN20220309', '', 'fallero', 83, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(183, 'FN20221018', '', 'fallero', 59, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(184, 'FN20230102', '', 'fallero', 117, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(185, 'FN20240616', '', 'fallero', 68, 1, NULL, 0, '2026-05-28 13:33:01', NULL),
(186, 'FN20160629', '', 'fallero', 94, 1, '2026-05-31 00:00:20', 0, '2026-05-28 13:33:01', '2026-05-31 00:00:00'),
(187, 'MA19891120', '', 'fallero', 52, 1, NULL, 0, '2026-05-28 13:33:01', NULL);

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
-- Indexes for table `juntas`
--
ALTER TABLE `juntas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_fecha` (`fecha`);

--
-- Indexes for table `junta_archivos`
--
ALTER TABLE `junta_archivos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_junta_id` (`junta_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=224;

--
-- AUTO_INCREMENT for table `actos`
--
ALTER TABLE `actos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `avisos`
--
ALTER TABLE `avisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `falleros`
--
ALTER TABLE `falleros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=260;

--
-- AUTO_INCREMENT for table `familias`
--
ALTER TABLE `familias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `juntas`
--
ALTER TABLE `juntas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `junta_archivos`
--
ALTER TABLE `junta_archivos`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `push_subscriptions`
--
ALTER TABLE `push_subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `reserva_invitados`
--
ALTER TABLE `reserva_invitados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=260;

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
-- Constraints for table `junta_archivos`
--
ALTER TABLE `junta_archivos`
  ADD CONSTRAINT `fk_junta_archivos_junta` FOREIGN KEY (`junta_id`) REFERENCES `juntas` (`id`) ON DELETE CASCADE;

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
