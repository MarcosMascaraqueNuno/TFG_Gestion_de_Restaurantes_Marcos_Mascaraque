-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-01-2025 a las 21:12:30
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gestionrestaurantes`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `disponibilidad_mesas`
--

CREATE TABLE `disponibilidad_mesas` (
  `id_disponibilidad` int(11) NOT NULL,
  `id_mesa` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `estado` enum('Disponible','Reservada') DEFAULT 'Disponible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fotos_restaurante`
--

CREATE TABLE `fotos_restaurante` (
  `id` int(11) NOT NULL,
  `restaurante_id` int(11) DEFAULT NULL,
  `url_foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `fotos_restaurante`
--

INSERT INTO `fotos_restaurante` (`id`, `restaurante_id`, `url_foto`) VALUES
(17, 45, '../../resource/restaurante_fotos/678563e7eec7a.jpg'),
(18, 45, '../../resource/restaurante_fotos/678563e7eed7c.jpg'),
(19, 45, '../../resource/restaurante_fotos/678563e7eee7a.jpg'),
(20, 47, '../../resource/restaurante_fotos/67859913a4204.jpg'),
(21, 47, '../../resource/restaurante_fotos/67859913a4309.jpg'),
(22, 47, '../../resource/restaurante_fotos/67859913a4406.jpeg'),
(23, 48, '../../resource/restaurante_fotos/67859abe524bb.jpg'),
(24, 48, '../../resource/restaurante_fotos/67859abe525e4.jpg'),
(25, 48, '../../resource/restaurante_fotos/67859abe526fb.jpg'),
(26, 49, '../../resource/restaurante_fotos/67859dc6de557.webp'),
(27, 49, '../../resource/restaurante_fotos/67859dc6de65f.webp'),
(28, 50, '../../resource/restaurante_fotos/6785a1f7f2581.jpg'),
(29, 50, '../../resource/restaurante_fotos/6785a1f7f267c.jpg'),
(30, 50, '../../resource/restaurante_fotos/6785a1f7f2772.jpg'),
(31, 51, '../../resource/restaurante_fotos/6785a50a99009.avif'),
(32, 51, '../../resource/restaurante_fotos/6785a50a99108.jpg'),
(37, 53, '../../resource/restaurante_fotos/6785a738305e3.webp'),
(38, 53, '../../resource/restaurante_fotos/6785a7383071a.jpg'),
(39, 53, '../../resource/restaurante_fotos/6785a73830823.avif'),
(40, 53, '../../resource/restaurante_fotos/6785a73830923.png'),
(41, 54, '../../resource/restaurante_fotos/678624c8274ed.jpg'),
(42, 54, '../../resource/restaurante_fotos/678624c827620.jpg'),
(43, 54, '../../resource/restaurante_fotos/678624c82778a.jpg'),
(44, 55, '../../resource/restaurante_fotos/678625b422f1e.jpg'),
(45, 55, '../../resource/restaurante_fotos/678625b423087.avif'),
(46, 55, '../../resource/restaurante_fotos/678625b4234fc.avif');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios_comidas`
--

CREATE TABLE `horarios_comidas` (
  `id` int(11) NOT NULL,
  `restaurante_id` int(11) NOT NULL,
  `hora_inicio` time NOT NULL,
  `tipo` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `horarios_comidas`
--

INSERT INTO `horarios_comidas` (`id`, `restaurante_id`, `hora_inicio`, `tipo`) VALUES
(80, 45, '14:00:00', 1),
(81, 45, '21:30:00', 2),
(87, 47, '13:30:00', 1),
(88, 47, '14:00:00', 1),
(89, 47, '14:30:00', 1),
(90, 47, '15:00:00', 1),
(91, 47, '20:30:00', 2),
(92, 47, '21:00:00', 2),
(93, 47, '21:30:00', 2),
(94, 47, '22:00:00', 2),
(95, 47, '22:30:00', 2),
(96, 48, '13:00:00', 1),
(97, 48, '13:15:00', 1),
(98, 48, '13:30:00', 1),
(99, 48, '13:45:00', 1),
(100, 48, '14:00:00', 1),
(101, 48, '14:15:00', 1),
(102, 48, '14:30:00', 1),
(103, 48, '14:45:00', 1),
(104, 48, '15:00:00', 1),
(105, 48, '20:00:00', 2),
(106, 48, '20:15:00', 2),
(107, 48, '20:30:00', 2),
(108, 48, '20:45:00', 2),
(109, 48, '21:00:00', 2),
(110, 48, '21:15:00', 2),
(111, 48, '21:30:00', 2),
(112, 48, '21:45:00', 2),
(113, 48, '22:00:00', 2),
(114, 49, '13:00:00', 1),
(115, 49, '13:30:00', 1),
(116, 49, '14:00:00', 1),
(117, 49, '14:30:00', 1),
(118, 49, '15:00:00', 1),
(119, 49, '21:00:00', 2),
(120, 49, '21:30:00', 2),
(121, 49, '22:00:00', 2),
(122, 49, '22:30:00', 2),
(123, 50, '13:30:00', 1),
(124, 50, '14:00:00', 1),
(125, 50, '14:30:00', 1),
(126, 50, '15:00:00', 1),
(128, 50, '20:30:00', 2),
(130, 50, '21:00:00', 2),
(131, 50, '20:00:00', 2),
(132, 51, '14:00:00', 1),
(133, 51, '14:30:00', 1),
(134, 51, '15:00:00', 1),
(135, 51, '15:30:00', 1),
(136, 51, '20:30:00', 2),
(137, 51, '20:45:00', 2),
(138, 51, '21:00:00', 2),
(139, 51, '21:15:00', 2),
(140, 51, '21:30:00', 2),
(141, 51, '21:45:00', 2),
(142, 51, '22:00:00', 2),
(143, 51, '22:15:00', 2),
(144, 51, '22:30:00', 2),
(156, 53, '13:30:00', 1),
(157, 53, '14:00:00', 1),
(158, 53, '14:30:00', 1),
(159, 53, '15:00:00', 1),
(160, 53, '15:30:00', 1),
(161, 53, '21:00:00', 2),
(162, 53, '21:30:00', 2),
(163, 53, '22:00:00', 2),
(164, 53, '22:30:00', 2),
(165, 53, '23:00:00', 2),
(166, 54, '14:00:00', 1),
(167, 54, '14:30:00', 1),
(168, 54, '15:00:00', 1),
(169, 54, '15:30:00', 1),
(170, 54, '20:30:00', 2),
(171, 54, '21:00:00', 2),
(172, 54, '21:30:00', 2),
(173, 54, '22:00:00', 2),
(174, 55, '13:30:00', 1),
(175, 55, '14:00:00', 1),
(176, 55, '14:30:00', 1),
(177, 55, '15:00:00', 1),
(178, 55, '20:00:00', 2),
(179, 55, '20:30:00', 2),
(180, 55, '21:00:00', 2),
(181, 55, '21:30:00', 2),
(182, 55, '22:00:00', 2),
(183, 45, '14:30:00', 1),
(184, 45, '15:00:00', 1),
(185, 45, '15:30:00', 1),
(186, 45, '22:00:00', 2),
(187, 45, '22:30:00', 2),
(188, 45, '23:00:00', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resenas`
--

CREATE TABLE `resenas` (
  `id_reseña` int(11) NOT NULL,
  `id_restaurante` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `calificacion` int(1) DEFAULT NULL CHECK (`calificacion` between 1 and 5),
  `comentario` text DEFAULT NULL,
  `fecha_resena` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_reserva` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `resenas`
--

INSERT INTO `resenas` (`id_reseña`, `id_restaurante`, `id_usuario`, `calificacion`, `comentario`, `fecha_resena`, `id_reserva`) VALUES
(18, 49, 21, 4, '¡Todo estuvo excelente! La comida deliciosa y el servicio excepcional. ¡Volveré sin duda!', '2025-01-13 23:00:00', 72),
(19, 45, 21, 5, 'Un lugar muy acogedor, perfecto para una cena tranquila. Los camareros muy amables.', '2025-01-13 23:00:00', 91),
(20, 54, 21, 4, 'Muy buen ambiente y la comida es deliciosa. La atención al cliente fue impecable. ¡Recomendado!', '2025-01-13 23:00:00', 71),
(21, 45, 21, 4, 'La comida estaba increíble, especialmente el postre. Volveré para probar más opciones del menú', '2025-01-13 23:00:00', 89),
(22, 45, 21, 5, 'Una experiencia fantástica. El restaurante tiene un ambiente relajado y el personal es muy atento.', '2025-01-13 23:00:00', 92),
(23, 51, 17, 5, 'Comida muy buena, aunque el servicio estuvo un poco lento. Aún así, volveré por la calidad.', '2025-01-13 23:00:00', 67),
(24, 45, 17, 5, 'Excelente atención, comida deliciosa y el ambiente perfecto para disfrutar de una buena cena.', '2025-01-13 23:00:00', 62),
(25, 45, 23, 5, 'Muy buen restaurante. La comida estaba deliciosa y el servicio excelente. Muy recomendable.', '2025-01-13 23:00:00', 81),
(26, 47, 17, 5, 'Todo perfecto!', '2025-01-13 23:00:00', 63);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id_reserva` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_restaurante` int(11) NOT NULL,
  `fecha_reserva` date NOT NULL,
  `hora_reserva` time NOT NULL,
  `numero_comensales` int(11) NOT NULL,
  `estado_reserva` enum('Pendiente','Confirmada','Finalizada','Cancelada') DEFAULT 'Pendiente',
  `comentarios` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id_reserva`, `id_usuario`, `id_restaurante`, `fecha_reserva`, `hora_reserva`, `numero_comensales`, `estado_reserva`, `comentarios`) VALUES
(62, 17, 45, '2025-01-13', '14:00:00', 3, 'Finalizada', ''),
(63, 17, 47, '2025-01-13', '14:00:00', 3, 'Finalizada', ''),
(64, 17, 48, '2025-01-16', '13:30:00', 4, 'Confirmada', ''),
(65, 17, 49, '2025-01-17', '13:30:00', 4, 'Confirmada', ''),
(66, 17, 50, '2025-01-18', '14:00:00', 5, 'Pendiente', ''),
(67, 17, 51, '2025-01-13', '15:00:00', 3, 'Finalizada', ''),
(68, 17, 53, '2025-01-11', '14:00:00', 3, 'Finalizada', ''),
(69, 17, 54, '2025-01-21', '15:00:00', 4, 'Pendiente', ''),
(70, 17, 55, '2025-01-22', '21:30:00', 4, 'Pendiente', ''),
(71, 21, 54, '2025-01-13', '14:30:00', 2, 'Finalizada', ''),
(72, 21, 49, '2025-01-13', '15:00:00', 3, 'Finalizada', ''),
(73, 21, 45, '2025-01-16', '14:30:00', 3, 'Cancelada', ''),
(74, 21, 50, '2025-01-17', '20:00:00', 2, 'Confirmada', ''),
(75, 21, 48, '2025-01-18', '20:00:00', 3, 'Confirmada', ''),
(76, 21, 48, '2025-01-19', '15:00:00', 6, 'Pendiente', ''),
(77, 21, 54, '2025-01-20', '14:30:00', 2, 'Pendiente', ''),
(79, 21, 49, '2025-01-22', '13:30:00', 4, 'Pendiente', ''),
(80, 23, 55, '2025-01-14', '15:30:00', 2, 'Finalizada', ''),
(81, 23, 45, '2025-01-07', '14:00:00', 3, 'Finalizada', ''),
(82, 23, 47, '2025-01-16', '20:15:00', 5, 'Cancelada', ''),
(83, 23, 45, '2025-01-17', '13:00:00', 4, 'Confirmada', ''),
(84, 23, 51, '2025-01-18', '14:00:00', 6, 'Confirmada', ''),
(85, 23, 47, '2025-01-19', '21:30:00', 5, 'Cancelada', ''),
(86, 23, 51, '2025-01-20', '23:00:00', 7, 'Pendiente', ''),
(87, 23, 55, '2025-01-21', '20:30:00', 3, 'Pendiente', ''),
(88, 23, 50, '2025-01-22', '22:00:00', 7, 'Pendiente', ''),
(89, 21, 45, '2025-01-13', '14:00:00', 2, 'Finalizada', ''),
(90, 21, 45, '2025-01-14', '14:00:00', 2, 'Finalizada', ''),
(91, 21, 45, '2025-01-13', '15:00:00', 5, 'Finalizada', ''),
(92, 21, 45, '2025-01-12', '14:00:00', 8, 'Finalizada', ''),
(93, 21, 45, '2025-01-14', '14:30:00', 4, 'Finalizada', 'asda');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `restaurantes`
--

CREATE TABLE `restaurantes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `numero_direccion` int(15) NOT NULL,
  `ciudad` varchar(40) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `capacidad_total` int(11) DEFAULT NULL,
  `url_menu_pdf` varchar(255) DEFAULT NULL,
  `horario_apertura` varchar(100) DEFAULT NULL,
  `horario_cierre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `Url_Foto` varchar(255) DEFAULT NULL,
  `propietario_id` int(11) NOT NULL,
  `precio_medio` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `restaurantes`
--

INSERT INTO `restaurantes` (`id`, `nombre`, `direccion`, `numero_direccion`, `ciudad`, `telefono`, `email`, `capacidad_total`, `url_menu_pdf`, `horario_apertura`, `horario_cierre`, `descripcion`, `Url_Foto`, `propietario_id`, `precio_medio`) VALUES
(45, 'Casa Carmen', 'Calle Madrid', 23, 'Getafe', '54353591354534', 'sada@g.com', 342, '../../resource/menus/678417fe40a5f.pdf', '21:13', '03:21', 'WELKHOMEclub destaca por su ambiente acogedor y decoración única, aunque algunos mencionan música alta y frío al inicio. La comida es una explosión de sabores, con platos como samosas ibéricas y tacos de pollita pibil que encantan a los comensales. El servicio es excepcional, con personal amable y atento, especialmente Laura y Lili, quienes hacen de la experiencia algo memorable.', '../../resource/img/678417fe40893.jpg', 16, 23),
(47, 'Sushi Zen', 'Av. de China', 5, 'Málaga', '933987654', 'info@sushizen.com', 40, '../../resource/menus/67859913a4079.pdf', '13:00', '23:30', 'Sushi Zen es un restaurante de cocina japonesa situado en el centro de Barcelona. Ofrecen una experiencia gastronómica auténtica, combinando ingredientes frescos y de calidad con la tradición culinaria japonesa. El local tiene un ambiente minimalista y tranquilo, con un diseño inspirado en el zen. En el menú destacan sus sushis hechos a mano, nigiris, makis, y una selección de platos calientes como el ramen y el teriyaki. También se ofrecen menús especiales para grupos y eventos. Sushi Zen se compromete a ofrecer a sus clientes una experiencia inolvidable con una fusión de sabores y presentación impecable.', '../../resource/img/67859913a3f04.jpg', 16, 20),
(48, 'El Rincón Vegano', 'C. Verde', 10, 'Altea', '961234567', 'rinconvegano@vegan.com', 40, '../../resource/menus/67859abe52334.pdf', '12:00', '21:00', 'El Rincón Vegano es un restaurante innovador y ecológico que se especializa en ofrecer opciones 100% veganas y saludables. Situado en Valencia, este restaurante tiene como misión promover una alimentación consciente, respetuosa con el medio ambiente y libre de crueldad animal. El menú cambia con las estaciones para utilizar los ingredientes más frescos y locales. Los platos incluyen desde ensaladas gourmet hasta hamburguesas veganas y postres sin gluten. En El Rincón Vegano también se organizan talleres de cocina saludable y eventos para difundir los beneficios de una alimentación basada en plantas.', '../../resource/img/67859abe521ba.avif', 19, 15),
(49, 'Vegano Delicioso', 'C. de Cdad. Real', 5, 'Madrid', '912345678', 'contacto@veganodelicioso.com', 30, NULL, '12:00', '00:00', 'Vegano Delicioso es un restaurante especializado en cocina vegana que ofrece una amplia variedad de platos frescos, saludables y deliciosos. Ubicado en el corazón de Madrid, este restaurante se enorgullece de utilizar ingredientes orgánicos y locales para crear menús que no solo son sabrosos, sino también respetuosos con el medio ambiente.', '../../resource/img/67859dc6de3e0.avif', 19, 24),
(50, 'Green Plate', 'C. del Dr. Esquerdo', 45, 'Madrid', '910123456', 'contacto@greenplate.com', 60, '../../resource/menus/6785a1f7f2426.pdf', '12:00', '00:00', 'Green Plate es un restaurante vegano que ofrece una experiencia culinaria basada en ingredientes frescos y orgánicos. Ofrecemos platos innovadores, sabrosos y saludables para todos los amantes de la comida consciente.', '../../resource/img/6785a1f7f22ac.jpg', 20, 35),
(51, 'El Buen Sabor', 'C. de Getafe', 23, 'Madrid', '914225756', 'contacto@elbuenesabor.com', 85, '../../resource/menus/6785a50a98eb9.pdf', '14:00', '23:00', 'El Buen Sabor es un restaurante que celebra lo mejor de la gastronomía española, ofreciendo una variada selección de platos que incluyen desde las clásicas tapas españolas hasta suculentas carnes a la parrilla y pescados frescos. Cada plato es preparado con los mejores ingredientes de temporada, garantizando una experiencia culinaria auténtica y deliciosa.', '../../resource/img/6785a50a98d58.jpg', 20, 40),
(53, 'La Toscana', 'Avenida de Italia', 17, 'Sevilla', '934564534', 'info@latoscana.com', 90, '../../resource/menus/6785a73830483.pdf', '13:00', '00:00', 'Restaurante especializado en cocina italiana, donde podrás disfrutar de pastas frescas, pizzas al horno de leña y una selección de vinos italianos.', '../../resource/img/6785a73830292.png', 20, 25),
(54, 'El Rincón de la Parrilla', 'C. Madrid', 23, 'Getafe', '912765756', 'contacto@ewlrincondelaparrila.com', 70, '../../resource/menus/678624c82735e.pdf', '14:00', '00:00', 'En El Rincón de la Parrilla, la pasión por las carnes a la brasa se refleja en cada plato. Este restaurante barcelonés es conocido por su entraña, costillas y chuletones, siempre acompañados de guarniciones frescas y vinos regionales. Con un ambiente rústico y cálido, es perfecto para los amantes de la buena comida y las reuniones informales. Ideal para disfrutar de sabores auténticos sin salir de la ciudad.', '../../resource/img/678624c8271be.jpg', 22, 25),
(55, 'La Cocina de Juan', 'C. de Alcalá', 123, 'Madrid', '916353625', 'contacto@cocinadejuan.es', 45, '../../resource/menus/678625b422d2c.pdf', '13:00', '23:30', 'La Cocina de Juan es un restaurante acogedor y familiar, ideal para disfrutar de platos caseros tradicionales con un toque moderno. Su menú, que cambia según la temporada, incluye opciones para todos los gustos, desde ensaladas frescas hasta guisos y arroces. Con un ambiente relajado y un trato cercano, es el sitio perfecto para una comida entre amigos o una comida en familia.', '../../resource/img/678625b422b61.jpg', 22, 35);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `restaurantes_tipos_comida`
--

CREATE TABLE `restaurantes_tipos_comida` (
  `restaurante_id` int(11) NOT NULL,
  `tipo_comida_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `restaurantes_tipos_comida`
--

INSERT INTO `restaurantes_tipos_comida` (`restaurante_id`, `tipo_comida_id`) VALUES
(45, 1),
(47, 4),
(47, 6),
(48, 10),
(48, 11),
(49, 10),
(49, 11),
(50, 1),
(50, 2),
(50, 8),
(51, 1),
(51, 2),
(51, 3),
(51, 12),
(53, 2),
(54, 1),
(54, 2),
(54, 3),
(54, 4),
(54, 8),
(54, 9),
(55, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_comida`
--

CREATE TABLE `tipos_comida` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_comida`
--

INSERT INTO `tipos_comida` (`id`, `nombre`) VALUES
(1, 'Española'),
(2, 'Italiana'),
(3, 'Mexicana'),
(4, 'Japonesa'),
(5, 'Mediterránea'),
(6, 'China'),
(7, 'Indú'),
(8, 'Francesa'),
(9, 'Americana'),
(10, 'Vegana'),
(11, 'Vegetariana'),
(12, 'Mariscos'),
(13, 'Tailandesa'),
(14, 'Coreana'),
(15, 'Argentina'),
(16, 'Turca'),
(17, 'Hindú');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `telefono` int(11) NOT NULL,
  `favorito` tinyint(1) DEFAULT 0,
  `tipo_usuario` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_usuario`, `contrasena`, `email`, `nombre`, `apellidos`, `telefono`, `favorito`, `tipo_usuario`) VALUES
(16, 'MarcosRestaurante', '$2y$10$MfJQuGTFqdRKdhHzVMAhLu6pEJuL5V0y8BWR0XP3/9bukv2zHgso2', 'marcosrestaurante@gmail.com', 'Marcos', 'Mascaraque', 654654654, 0, 2),
(17, 'Paula', '$2y$10$kvwkTH7baTicJoZDnNA0L.XmS.ut2K8qV.rFYjPacFbAZCwilGKRO', 'paula@gmail.com', 'Paula', 'Mas', 654654654, 0, 1),
(19, 'ejemploRestaurante1', '$2y$10$rFsIPC9cBb2y6w3uYMzsge0aI3OBBWhBvyplSaCCxz5jn.gV9/ffi', 'ejemploRestaurante1@gmail.com', 'ejemploRestaurante1', 'ejemploRestaurante1', 654645645, 0, 2),
(20, 'ejemploRestaurante2', '$2y$10$fs9pdlRLVsF9qq5Ucd4bBeh8pvolFLbYwbKTn4Fydvqt8Nq9PB436', 'ejemploRestaurante1@gmail.com', 'ejemploRestaurante1', 'ejemploRestaurante1', 654654654, 0, 2),
(21, 'ejemploUsuario1', '$2y$10$i3k09ddWzEL8XYQnATRx/etM7OaWuhiN1.dC1zycVF5GgVt4DDvSW', 'ejemploUsuario1@gmail.com', 'ejemploUsuario1', 'ejemploUsuario1', 654654654, 0, 1),
(22, 'ejemploRestaurante3', '$2y$10$sK9erBvag5r.8HnA/jVjE.nnPb1o1VrrNJlrHDrp1XDRE7m4ZIX3C', 'ejemploRestaurante1@gmail.com', 'ejemploRestaurante1', 'ejemploRestaurante1', 654654637, 0, 2),
(23, 'ejemploUsuario2', '$2y$10$OZ.Vz2qAPz/nLHI6bTyg..zPOR2XWrlReWfeMYtrtH/dRe7h61wDS', 'ejemploUsuario1@gmail.com', 'ejemploUsuario1', 'ejemploUsuario1', 654654654, 0, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `disponibilidad_mesas`
--
ALTER TABLE `disponibilidad_mesas`
  ADD PRIMARY KEY (`id_disponibilidad`),
  ADD KEY `id_mesa` (`id_mesa`);

--
-- Indices de la tabla `fotos_restaurante`
--
ALTER TABLE `fotos_restaurante`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurante_id` (`restaurante_id`);

--
-- Indices de la tabla `horarios_comidas`
--
ALTER TABLE `horarios_comidas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurante_id` (`restaurante_id`);

--
-- Indices de la tabla `resenas`
--
ALTER TABLE `resenas`
  ADD PRIMARY KEY (`id_reseña`),
  ADD KEY `id_restaurante` (`id_restaurante`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `fk_resenas_reservas` (`id_reserva`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_restaurante` (`id_restaurante`);

--
-- Indices de la tabla `restaurantes`
--
ALTER TABLE `restaurantes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `restaurantes_tipos_comida`
--
ALTER TABLE `restaurantes_tipos_comida`
  ADD PRIMARY KEY (`restaurante_id`,`tipo_comida_id`),
  ADD KEY `tipo_comida_id` (`tipo_comida_id`);

--
-- Indices de la tabla `tipos_comida`
--
ALTER TABLE `tipos_comida`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `disponibilidad_mesas`
--
ALTER TABLE `disponibilidad_mesas`
  MODIFY `id_disponibilidad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `fotos_restaurante`
--
ALTER TABLE `fotos_restaurante`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `horarios_comidas`
--
ALTER TABLE `horarios_comidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT de la tabla `resenas`
--
ALTER TABLE `resenas`
  MODIFY `id_reseña` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT de la tabla `restaurantes`
--
ALTER TABLE `restaurantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT de la tabla `tipos_comida`
--
ALTER TABLE `tipos_comida`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `disponibilidad_mesas`
--
ALTER TABLE `disponibilidad_mesas`
  ADD CONSTRAINT `disponibilidad_mesas_ibfk_1` FOREIGN KEY (`id_mesa`) REFERENCES `mesas` (`id_mesa`);

--
-- Filtros para la tabla `fotos_restaurante`
--
ALTER TABLE `fotos_restaurante`
  ADD CONSTRAINT `fotos_restaurante_ibfk_1` FOREIGN KEY (`restaurante_id`) REFERENCES `restaurantes` (`id`);

--
-- Filtros para la tabla `horarios_comidas`
--
ALTER TABLE `horarios_comidas`
  ADD CONSTRAINT `horarios_comidas_ibfk_1` FOREIGN KEY (`restaurante_id`) REFERENCES `restaurantes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `resenas`
--
ALTER TABLE `resenas`
  ADD CONSTRAINT `fk_resenas_reservas` FOREIGN KEY (`id_reserva`) REFERENCES `reservas` (`id_reserva`),
  ADD CONSTRAINT `resenas_ibfk_1` FOREIGN KEY (`id_restaurante`) REFERENCES `restaurantes` (`id`),
  ADD CONSTRAINT `resenas_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`id_restaurante`) REFERENCES `restaurantes` (`id`);

--
-- Filtros para la tabla `restaurantes_tipos_comida`
--
ALTER TABLE `restaurantes_tipos_comida`
  ADD CONSTRAINT `restaurantes_tipos_comida_ibfk_1` FOREIGN KEY (`restaurante_id`) REFERENCES `restaurantes` (`id`),
  ADD CONSTRAINT `restaurantes_tipos_comida_ibfk_2` FOREIGN KEY (`tipo_comida_id`) REFERENCES `tipos_comida` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
