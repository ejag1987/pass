-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 05-11-2018 a las 14:44:33
-- Versión del servidor: 10.1.36-MariaDB-cll-lve
-- Versión de PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `extetsdn_neorentas_paseo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evento`
--

CREATE TABLE `evento` (
  `id_evento` int(10) NOT NULL,
  `id_sitio` int(10) UNSIGNED NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `foto` varchar(150) CHARACTER SET utf8 DEFAULT NULL,
  `titulo` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `descripcion` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `contenido` text CHARACTER SET utf8,
  `fecha_evento` date DEFAULT NULL,
  `horario` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `orden` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `eliminado` tinyint(4) NOT NULL DEFAULT '0',
  `fecha_creacion` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `evento`
--

INSERT INTO `evento` (`id_evento`, `id_sitio`, `alias`, `foto`, `titulo`, `descripcion`, `contenido`, `fecha_evento`, `horario`, `orden`, `eliminado`, `fecha_creacion`) VALUES
(1, 2, NULL, 'noticias.jpg', 'El café se disfruta más en tu nueva terraza', 'El mejor café y la mejor compañía están en tu Nuevo Punto de Encuentro. Ven a conocer el nuevo Starbucks de la comuna en Av. Balmaceda 2885 y disfruta en nuestra increíble terraza al aire libre.', NULL, NULL, NULL, 15, 0, '2017-05-15 17:29:07'),
(2, 2, NULL, 'noticias3.jpg', 'Maestro hay uno solo y está en La Serena', 'Ven y conoce los secretos del maestro en tu Nuevo Punto de Encuentro de La Serena. Juan Maestro y los mejores sándwiches llegaron a Paseo Balmaceda. Visítalos en Av. Balmaceda 2885, Local 212.', NULL, NULL, NULL, 16, 0, '2017-05-15 17:30:11'),
(3, 2, NULL, 'noticias2.jpg', 'El primer Automac de La Serena está en tu Nuevo Punto de Encuentro', 'Tu comida rápida favorita está más cerca. Ven y sorpréndete con el sistema “Made For You”, que permite que la comida se prepare a tu medida. ¡Te esperamos!', NULL, NULL, NULL, 4, 1, '2017-05-15 18:21:06'),
(4, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, 1, '2018-03-12 15:41:25'),
(5, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2018-03-15 13:16:27'),
(6, 2, 'estrella', 'noticias_marzo1.jpg', 'Estrella Alpina llegó a tu Nuevo Punto de Encuentro', 'Todos los zapatos y accesorios de Estrella Aplina ahora más cerca de ti en Paseo Balmaceda. Ven a conocer esta nueva tienda en los locales 110 y 111 de tu Nuevo Punto de Encuentro.', '\r\n\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. In sit amet tellus a orci fringilla fermentum sit amet eget ligula. Vestibulum non dui consequat, lobortis velit sit amet, facilisis est. Aliquam erat volutpat. Mauris elementum ligula in mauris efficitur euismod. Aliquam tempus tristique ante sit amet sodales. Nam maximus dictum tincidunt. Aliquam id laoreet ex. Donec nec felis at eros suscipit ultrices. Fusce id tellus tincidunt, scelerisque purus ac, molestie enim. Ut libero nisi, interdum at lobortis semper, dignissim at neque. Suspendisse vitae feugiat ante, in porta erat.\r\n\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ac varius nulla. Nulla facilisi. Morbi ac venenatis turpis, ut luctus risus. Suspendisse in tellus rutrum, cursus erat vitae, tincidunt odio. Suspendisse ornare congue dui at molestie. Nam at augue a sem porttitor tincidunt ac eu augue. Ut sed lorem dui. Donec erat ipsum, aliquam ac posuere quis, pharetra et risus. Vestibulum non quam mauris.\r\n\r\nUt iaculis dictum molestie. Pellentesque dignissim blandit tempor. Nam non augue et quam tempor finibus ac et nunc. Phasellus feugiat neque vitae velit feugiat aliquet. Vestibulum tellus urna, efficitur sed nibh in, placerat dignissim lectus. Maecenas pulvinar justo quis lorem facilisis ultricies non eu magna. Fusce vitae justo ut elit gravida facilisis. Nam pretium sollicitudin porttitor. Cras ut risus id turpis iaculis dapibus.\r\n\r\nInteger placerat eros massa, id euismod lacus malesuada dapibus. Donec accumsan eget ex vel congue. Aenean pulvinar augue quis nisl lobortis varius. Morbi ut interdum metus, vel vestibulum est. Nam porta libero velit, sed vehicula tellus auctor non. Praesent ligula purus, semper vel neque a, consequat placerat purus. Nam tincidunt massa sed odio hendrerit dapibus. Sed dapibus, enim eu ultricies aliquet, mauris sapien luctus augue, eget scelerisque tortor mi quis turpis. Etiam at aliquet libero. Aenean tristique dolor purus, sit amet iaculis lectus varius et.\r\n\r\nDuis quis augue porttitor, egestas nunc sed, pharetra augue. Quisque nec felis et libero semper iaculis. Nulla facilisi. Praesent ligula nulla, venenatis et risus sit amet, rhoncus sagittis ipsum. In malesuada velit turpis, ut rutrum augue scelerisque at. Donec sed odio malesuada, dapibus ipsum eget, pharetra justo. Proin id felis vulputate, elementum sem a, tempus velit. Proin id molestie augue. Pellentesque at lorem a nunc posuere egestas finibus ac libero. Duis eget tempus justo. ', NULL, NULL, 14, 0, '2018-03-15 13:34:03'),
(7, 2, NULL, 'noticias_marzo2.jpg', 'La moda de Tricot ya está Paseo Balmaceda', 'Lo mejor de la moda ya está en Paseo Balmaceda de la mano de Tricot. Sorpréndete con las mejores prendas a inmejorables precios en el local 113.', NULL, NULL, NULL, 13, 0, '2018-03-15 13:34:51'),
(8, 2, NULL, '4.jpg', 'La magia del chocolate y el conejo de Pascua se tomaron Paseo Balmaceda', 'Paseo Balmaceda invitó a los niños y vecinos del centro comercial a conocer la historia del conejo de Pascua y del origen de esta celebración, mientras disfrutaban de los infaltables huevitos de chocolate.', NULL, NULL, NULL, 12, 0, '2018-03-29 20:33:07'),
(9, 2, NULL, '3.jpg', 'GMO ya es parte de tu Nuevo Punto de Encuentro', 'GMO ama tus ojos y ahora está más cerca de ti. Ven a Paseo Balmaceda y sorpréndete con los novedosos diseños de anteojos. Para que cuidar tus ojos no te impida estar a la moda, visita esta tienda ubicada en el local 128 del primer nivel.', NULL, NULL, NULL, 11, 0, '2018-04-03 10:57:33'),
(10, 2, NULL, '1.jpg', 'Lo mejor de Savory llegó a Paseo Balmaceda', 'Desde 1965, Savory te complace con todos sus helados, por eso es un infaltable en tu Nuevo Punto de Encuentro. Ven al local 211 del segundo nivel y endulza tus tardes.', NULL, NULL, NULL, 10, 0, '2018-04-05 12:07:46'),
(11, 2, NULL, '2.jpg', '¿Un viaje? Preocúpate de disfrutar y deja todo en manos de Cocha', 'Con más de 70 años en el mercado, Cocha es una excelente opción para planificar tus viajes con la tranquilidad que esta empresa te entrega. Ven y cotiza los mejores destinos en los locales 135 y 136.', NULL, NULL, NULL, 9, 0, '2018-04-05 12:08:59'),
(12, 2, NULL, 'homecenter.jpg', 'Todo lo que necesitas para construir tus sueños lo encuentras en Paseo Balmaceda', 'Sabemos que la esperabas y ya llegó. La Casa de Chile ya se incorporó al mix de tiendas de tu Nuevo Punto de Encuentro. Aquí encontrarás de todo para renovar tus espacios, tu jardín y mejorar tu hogar.', NULL, NULL, NULL, 8, 0, '2018-04-12 18:36:50'),
(13, 2, 'la-dulzura-se-tomo-la-serena', 'varsovienne.jpg', 'La dulzura se tomó La Serena', '¡Atención amantes del chocolate! Varsovienne llegó a Paseo Balmaceda para deleitarlos con sus exquisitos bombones, calugas y alfajores. No te pierdas la oportunidad de disfrutar lo que esta tienda tiene preparado para ti.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sit amet tellus a orci fringilla fermentum sit amet eget ligula. Vestibulum non dui consequat, lobortis velit sit amet, facilisis est. Aliquam erat volutpat. Mauris elementum ligula in mauris efficitur euismod. Aliquam tempus tristique ante sit amet sodales. Nam maximus dictum tincidunt. Aliquam id laoreet ex. Donec nec felis at eros suscipit ultrices. Fusce id tellus tincidunt, scelerisque purus ac, molestie enim. Ut libero nisi, interdum at lobortis semper, dignissim at neque. Suspendisse vitae feugiat ante, in porta erat.', NULL, NULL, 7, 0, '2018-04-12 18:37:50'),
(14, 2, 'el-fin-de-semana-tu-paseo', 'noticia_dia_de_la_musica.jpg', 'El fin de semana, tú paseo es más entretenido', 'Este sábado 21, ven y disfruta con la mejor música en vivo en Paseo Balmaceda. Desde las 18:00 horas, te esperamos en nuestra plaza central para sorprenderte con los mejores acordes y un grato momento en familia. ¡No te lo pierdas!', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sit amet tellus a orci fringilla fermentum sit amet eget ligula. Vestibulum non dui consequat, lobortis velit sit amet, facilisis est. Aliquam erat volutpat. Mauris elementum ligula in mauris efficitur euismod. Aliquam tempus tristique ante sit amet sodales. Nam maximus dictum tincidunt. Aliquam id laoreet ex. Donec nec felis at eros suscipit ultrices. Fusce id tellus tincidunt, scelerisque purus ac, molestie enim. Ut libero nisi, interdum at lobortis semper, dignissim at neque. Suspendisse vitae feugiat ante, in porta erat.\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ac varius nulla. Nulla facilisi. Morbi ac venenatis turpis, ut luctus risus. Suspendisse in tellus rutrum, cursus erat vitae, tincidunt odio. Suspendisse ornare congue dui at molestie. Nam at augue a sem porttitor tincidunt ac eu augue. Ut sed lorem dui. Donec erat ipsum, aliquam ac posuere quis, pharetra et risus. Vestibulum non quam mauris.\n\nUt iaculis dictum molestie. Pellentesque dignissim blandit tempor. Nam non augue et quam tempor finibus ac et nunc. Phasellus feugiat neque vitae velit feugiat aliquet. Vestibulum tellus urna, efficitur sed nibh in, placerat dignissim lectus. Maecenas pulvinar justo quis lorem facilisis ultricies non eu magna. Fusce vitae justo ut elit gravida facilisis. Nam pretium sollicitudin porttitor. Cras ut risus id turpis iaculis dapibus.\n\nInteger placerat eros massa, id euismod lacus malesuada dapibus. Donec accumsan eget ex vel congue. Aenean pulvinar augue quis nisl lobortis varius. Morbi ut interdum metus, vel vestibulum est. Nam porta libero velit, sed vehicula tellus auctor non. Praesent ligula purus, semper vel neque a, consequat placerat purus. Nam tincidunt massa sed odio hendrerit dapibus. Sed dapibus, enim eu ultricies aliquet, mauris sapien luctus augue, eget scelerisque tortor mi quis turpis. Etiam at aliquet libero. Aenean tristique dolor purus, sit amet iaculis lectus varius et.', NULL, NULL, 5, 0, '2018-04-20 15:07:17'),
(15, 2, 'el-dia-de-la-tierra-lo-celebramos-con-un-regalo', 'noticia_dia_de_la_tierra.jpg', 'El Día de la Tierra lo celebramos con un regalo a la naturaleza', '¿Sabías que la capacidad de las plantas para absorber dióxido de carbono será un factor esencial en la lucha contra el cambio climático? En Paseo Balmaceda regalaremos 300 plantas a los clientes que vengan el domingo 22 a compartir este importante día con', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sit amet tellus a orci fringilla fermentum sit amet eget ligula. Vestibulum non dui consequat, lobortis velit sit amet, facilisis est. Aliquam erat volutpat. Mauris elementum ligula in mauris efficitur euismod. Aliquam tempus tristique ante sit amet sodales. Nam maximus dictum tincidunt. Aliquam id laoreet ex. Donec nec felis at eros suscipit ultrices. Fusce id tellus tincidunt, scelerisque purus ac, molestie enim. Ut libero nisi, interdum at lobortis semper, dignissim at neque. Suspendisse vitae feugiat ante, in porta erat.', NULL, NULL, 6, 0, '2018-04-20 15:07:22'),
(16, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 13, 1, '2018-04-20 15:07:30'),
(17, 2, 'el-dia-de-la-madre-se-celebra-al-ritmo-de-the', 'noticia_aceite.jpg', 'El Día de la Madre se celebra al ritmo de The Beatles', '¿All you need is Love? Es lo único que necesitas en este Día de la Madre. Por eso en Paseo Balmaceda tendremos un tributo a The Beatles para que compartas la mejor música en familia. \nTe esperamos este sábado 12 de mayo desde las 18:00 horas y el domingo', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sit amet tellus a orci fringilla fermentum sit amet eget ligula. Vestibulum non dui consequat, lobortis velit sit amet, facilisis est. Aliquam erat volutpat. Mauris elementum ligula in mauris efficitur euismod. Aliquam tempus tristique ante sit amet sodales. Nam maximus dictum tincidunt. Aliquam id laoreet ex. Donec nec felis at eros suscipit ultrices. Fusce id tellus tincidunt, scelerisque purus ac, molestie enim. Ut libero nisi, interdum at lobortis semper, dignissim at neque. Suspendisse vitae feugiat ante, in porta erat.\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ac varius nulla. Nulla facilisi. Morbi ac venenatis turpis, ut luctus risus. Suspendisse in tellus rutrum, cursus erat vitae, tincidunt odio. Suspendisse ornare congue dui at molestie. Nam at augue a sem porttitor tincidunt ac eu augue. Ut sed lorem dui. Donec erat ipsum, aliquam ac posuere quis, pharetra et risus. Vestibulum non quam mauris.\n\nUt iaculis dictum molestie. Pellentesque dignissim blandit tempor. Nam non augue et quam tempor finibus ac et nunc. Phasellus feugiat neque vitae velit feugiat aliquet. Vestibulum tellus urna, efficitur sed nibh in, placerat dignissim lectus. Maecenas pulvinar justo quis lorem facilisis ultricies non eu magna. Fusce vitae justo ut elit gravida facilisis. Nam pretium sollicitudin porttitor. Cras ut risus id turpis iaculis dapibus.\n\nInteger placerat eros massa, id euismod lacus malesuada dapibus. Donec accumsan eget ex vel congue. Aenean pulvinar augue quis nisl lobortis varius. Morbi ut interdum metus, vel vestibulum est. Nam porta libero velit, sed vehicula tellus auctor non. Praesent ligula purus, semper vel neque a, consequat placerat purus. Nam tincidunt massa sed odio hendrerit dapibus. Sed dapibus, enim eu ultricies aliquet, mauris sapien luctus augue, eget scelerisque tortor mi quis turpis. Etiam at aliquet libero. Aenean tristique dolor purus, sit amet iaculis lectus varius et.\n\nDuis quis augue porttitor, egestas nunc sed, pharetra augue. Quisque nec felis et libero semper iaculis. Nulla facilisi. Praesent ligula nulla, venenatis et risus sit amet, rhoncus sagittis ipsum. In malesuada velit turpis, ut rutrum augue scelerisque at. Donec sed odio malesuada, dapibus ipsum eget, pharetra justo. Proin id felis vulputate, elementum sem a, tempus velit. Proin id molestie augue. Pellentesque at lorem a nunc posuere egestas finibus ac libero. Duis eget tempus justo.', NULL, NULL, 3, 0, '2018-05-09 12:47:49'),
(18, 2, 'paseo-balmaceda-te-invita-a', 'noticias_alumno.jpg', '¡Paseo Balmaceda te invita a celebrar el Día del alumno!', 'Ven a celebrar tu día con nosotros y disfruta de un increíble show musical y de entretención. Además, tendremos pinta caritas y premios para canjear en nuestro patio de comidas.\n¡No te lo pierdas! Te esperamos este viernes 11 de mayo de 11:00 a 13:00 hora', '.', NULL, NULL, 4, 0, '2018-05-09 12:49:11'),
(19, 2, NULL, 'noticias_sturbucks.jpg', '#StarbucksRun te invita a correr por la donación de órganos', 'Este domingo 27 de mayo a las 9:00 horas, Starbucks te invita a apoyar la donación de órganos corriendo desde su tienda en Paseo Balmaceda. Si participas, te ganarás un café del día que puedes canjear hasta las 14:00 horas en nuestra tienda. Comparte esta', NULL, NULL, NULL, 1, 1, '2018-05-25 11:46:21'),
(20, 2, 'starbucksrun-te-invita-a-correr-por-la-donacion', 'im.jpg', '#StarbucksRun te invita a correr por la donación de órganos', '\"Este domingo 27 de mayo a las 9:00 horas, Starbucks te invita a apoyar la donación de órganos corriendo desde su tienda en Paseo Balmaceda. Si participas, te ganarás un café del día que puedes canjear hasta las 14:00 horas en nuestra tienda\".', 'contenido de prueba', NULL, NULL, 2, 0, '2018-05-25 17:34:42'),
(21, 2, 'junio-mes-del-medio-ambiente-feliz', 'noticia_aceite.jpg', 'Junio: Mes del Medio Ambiente feliz', 'Paseo Balmaceda te invita a celebrar el Mes del Medio Ambiente aprendiendo sobre el reciclaje de vidrio y aceite. A un costado de Homecenter Sodimac, podrás encontrar los contenedores y las instrucciones para utilizarlos ¡Recicla!', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sit amet tellus a orci fringilla fermentum sit amet eget ligula. Vestibulum non dui consequat, lobortis velit sit amet, facilisis est. Aliquam erat volutpat. Mauris elementum ligula in mauris efficitur euismod. Aliquam tempus tristique ante sit amet sodales. Nam maximus dictum tincidunt. Aliquam id laoreet ex. Donec nec felis at eros suscipit ultrices. Fusce id tellus tincidunt, scelerisque purus ac, molestie enim. Ut libero nisi, interdum at lobortis semper, dignissim at neque. Suspendisse vitae feugiat ante, in porta erat.\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ac varius nulla. Nulla facilisi. Morbi ac venenatis turpis, ut luctus risus. Suspendisse in tellus rutrum, cursus erat vitae, tincidunt odio. Suspendisse ornare congue dui at molestie. Nam at augue a sem porttitor tincidunt ac eu augue. Ut sed lorem dui. Donec erat ipsum, aliquam ac posuere quis, pharetra et risus. Vestibulum non quam mauris.\n\nUt iaculis dictum molestie. Pellentesque dignissim blandit tempor. Nam non augue et quam tempor finibus ac et nunc. Phasellus feugiat neque vitae velit feugiat aliquet. Vestibulum tellus urna, efficitur sed nibh in, placerat dignissim lectus. Maecenas pulvinar justo quis lorem facilisis ultricies non eu magna. Fusce vitae justo ut elit gravida facilisis. Nam pretium sollicitudin porttitor. Cras ut risus id turpis iaculis dapibus.\n\nInteger placerat eros massa, id euismod lacus malesuada dapibus. Donec accumsan eget ex vel congue. Aenean pulvinar augue quis nisl lobortis varius. Morbi ut interdum metus, vel vestibulum est. Nam porta libero velit, sed vehicula tellus auctor non. Praesent ligula purus, semper vel neque a, consequat placerat purus. Nam tincidunt massa sed odio hendrerit dapibus. Sed dapibus, enim eu ultricies aliquet, mauris sapien luctus augue, eget scelerisque tortor mi quis turpis. Etiam at aliquet libero. Aenean tristique dolor purus, sit amet iaculis lectus varius et.\n\nDuis quis augue porttitor, egestas nunc sed, pharetra augue. Quisque nec felis et libero semper iaculis. Nulla facilisi. Praesent ligula nulla, venenatis et risus sit amet, rhoncus sagittis ipsum. In malesuada velit turpis, ut rutrum augue scelerisque at. Donec sed odio malesuada, dapibus ipsum eget, pharetra justo. Proin id felis vulputate, elementum sem a, tempus velit. Proin id molestie augue. Pellentesque at lorem a nunc posuere egestas finibus ac libero. Duis eget tempus justo.', NULL, NULL, 1, 0, '2018-06-08 11:11:10'),
(24, 2, 'el-cafe-se-disfruta-mas-en-tu-nueva-terraza', 'pelota.jpg', 'El café se disfruta más en tu nueva terraza', 'El mejor café y la mejor compañía están en tu Nuevo Punto de Encuentro. Ven a conocer el nuevo Starbucks de la comuna en Av. Balmaceda 2885 y disfruta en nuestra increíble terraza al aire libre.', 'El mejor café y la mejor compañía están en tu Nuevo Punto de Encuentro. Ven a conocer el nuevo Starbucks de la comuna en Av. Balmaceda 2885 y disfruta en nuestra increíble terraza al aire libre.', NULL, NULL, 17, 1, '2018-07-26 09:57:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `galeria`
--

CREATE TABLE `galeria` (
  `id_galeria` int(10) UNSIGNED NOT NULL,
  `id_sitio` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `orden` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `eliminado` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `galeria`
--

INSERT INTO `galeria` (`id_galeria`, `id_sitio`, `titulo`, `descripcion`, `orden`, `eliminado`) VALUES
(1, 2, '¡El primer AutoMac ya está en tu Nuevo Punto de Encuentro!', NULL, 1, 0),
(2, 2, 'Ven a disfrutar del nuevo Doggis en Paseo Balmaceda', NULL, 2, 0),
(3, 2, '¡El maestro y sus sandwich ya están en La Serena!', NULL, 3, 0),
(4, 2, 'El mejor café se disfruta en la terraza de tu Nuevo Punto de Encuentro', NULL, 4, 0),
(5, 2, 'Niños y vecinos de Paseo Balmaceda recibieron la visita del conejo, quien les regaló huevitos de chocolate y les contó la historia de esta linda fecha', NULL, 5, 1),
(6, 2, 'Niños y vecinos de Paseo Balmaceda recibieron la visita del conejo, quien les regaló huevitos de chocolate y les contó la historia de esta linda fecha', NULL, 5, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `galeria_item`
--

CREATE TABLE `galeria_item` (
  `id_galeria_item` int(10) UNSIGNED NOT NULL,
  `id_galeria` int(10) UNSIGNED NOT NULL,
  `foto` varchar(150) NOT NULL,
  `orden` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `eliminado` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `galeria_item`
--

INSERT INTO `galeria_item` (`id_galeria_item`, `id_galeria`, `foto`, `orden`, `eliminado`) VALUES
(14, 1, 'DSC00003.jpg', 1, 1),
(15, 1, 'DSC00004.jpg', 2, 1),
(16, 1, 'DSC00015.jpg', 7, 1),
(17, 1, 'DSC00019.jpg', 4, 0),
(18, 1, 'DSC00024.jpg', 9, 1),
(19, 1, 'DSC00026.jpg', 10, 1),
(20, 1, 'DSC00021.jpg', 9, 0),
(21, 1, 'DSC00030.jpg', 10, 0),
(22, 1, 'DSC00043.jpg', 3, 0),
(23, 1, 'DSC00010.jpg', 1, 0),
(24, 1, 'DSC00039.jpg', 6, 0),
(25, 1, 'DSC00047.jpg', 14, 1),
(26, 1, 'DSC00038.jpg', 11, 0),
(27, 1, 'DSC00035.jpg', 16, 1),
(28, 1, 'DSC00065.jpg', 16, 1),
(29, 1, 'DSC00080.jpg', 17, 1),
(30, 1, 'DSC00053.jpg', 7, 0),
(31, 1, 'DSC00067.jpg', 12, 0),
(32, 1, 'DSC00078.jpg', 5, 0),
(33, 1, 'DSC00082.jpg', 8, 0),
(34, 1, 'DSC00055.jpg', 17, 1),
(35, 1, 'DSC00059.jpg', 13, 0),
(36, 1, 'DSC00088.jpg', 2, 0),
(37, 1, 'DSC00087.jpg', 22, 1),
(38, 1, 'DSC00094.jpg', 19, 1),
(39, 1, 'DSC00092.jpg', 14, 0),
(64, 2, 'DSC00312.jpg', 8, 0),
(65, 2, 'DSC00318.jpg', 2, 1),
(66, 2, 'DSC00321.jpg', 3, 0),
(67, 2, 'DSC00326.jpg', 4, 1),
(68, 2, 'DSC00324.jpg', 5, 0),
(69, 2, 'DSC00332.jpg', 7, 0),
(70, 2, 'DSC00323.jpg', 6, 1),
(71, 2, 'DSC00329.jpg', 1, 0),
(72, 2, 'DSC00337.jpg', 9, 0),
(73, 2, 'DSC00343.jpg', 10, 1),
(74, 2, 'DSC00338.jpg', 11, 0),
(75, 2, 'DSC00344.jpg', 12, 1),
(89, 3, 'DSC00287.jpg', 1, 1),
(90, 3, 'DSC00301.jpg', 2, 1),
(91, 3, 'DSC00293.jpg', 1, 0),
(92, 3, 'DSC00289.jpg', 4, 1),
(93, 3, 'DSC00275.jpg', 2, 0),
(94, 3, 'DSC00272.jpg', 4, 1),
(95, 3, 'DSC00297.jpg', 4, 0),
(96, 3, 'DSC00303.jpg', 3, 0),
(97, 3, 'DSC00281.jpg', 9, 1),
(98, 3, 'DSC00276.jpg', 6, 0),
(99, 3, 'DSC00283.jpg', 5, 0),
(100, 3, 'DSC00280.jpg', 7, 1),
(101, 3, 'DSC00282.jpg', 8, 0),
(102, 4, 'DSC00203.jpg', 2, 0),
(103, 4, 'DSC00205.jpg', 3, 0),
(104, 4, 'DSC00202.jpg', 4, 0),
(105, 4, 'DSC00214.jpg', 5, 0),
(106, 4, 'DSC00212.jpg', 6, 0),
(107, 4, 'DSC00231.jpg', 7, 0),
(108, 4, 'DSC00210.jpg', 8, 0),
(109, 4, 'DSC00259.jpg', 1, 0),
(110, 4, 'DSC00229.jpg', 9, 0),
(111, 4, 'DSC00250.jpg', 10, 0),
(112, 4, 'DSC00265.jpg', 11, 1),
(114, 5, 'DSCN3416_ok.jpg', 1, 0),
(115, 5, 'DSCN3421_ok.jpg', 2, 0),
(116, 5, 'DSCN3422_ok.jpg', 3, 0),
(117, 5, 'DSCN3425_ok.jpg', 4, 0),
(118, 5, 'DSCN3436_ok.jpg', 5, 0),
(119, 5, 'DSCN3439_ok.jpg', 6, 0),
(120, 5, 'DSCN3443_ok.jpg', 7, 0),
(121, 5, 'DSCN3446_ok.jpg', 8, 0),
(122, 5, 'DSCN3447_ok.jpg', 9, 0),
(123, 5, 'DSCN3453_ok.jpg', 10, 0),
(124, 5, 'DSCN3460_ok.jpg', 11, 0),
(125, 5, 'DSCN3461_ok.jpg', 12, 0),
(126, 5, 'DSCN3468_ok.jpg', 13, 0),
(127, 5, 'DSCN3485_ok.jpg', 14, 0),
(128, 5, 'DSCN3492_ok.jpg', 15, 0),
(129, 5, 'DSCN3493_ok.jpg', 16, 1),
(130, 6, 'DSCN3416_ok.jpg', 1, 1),
(131, 6, 'DSCN3422_ok.jpg', 2, 1),
(132, 6, 'DSCN3416_ok_1.jpg', 3, 1),
(133, 6, 'DSCN3493_ok.jpg', 1, 0),
(134, 6, 'DSCN3492_ok.jpg', 2, 0),
(135, 6, 'DSCN3485_ok.jpg', 3, 0),
(136, 6, 'DSCN3422_ok_1.jpg', 4, 0),
(137, 6, 'DSCN3443_ok.jpg', 5, 0),
(138, 6, 'DSCN3421_ok.jpg', 6, 0),
(139, 6, 'DSCN3425_ok.jpg', 7, 0),
(140, 6, 'DSCN3416_ok_2.jpg', 8, 0),
(141, 6, 'DSCN3436_ok.jpg', 9, 0),
(142, 6, 'DSCN3446_ok.jpg', 10, 0),
(143, 6, 'DSCN3439_ok.jpg', 11, 0),
(144, 6, 'DSCN3447_ok.jpg', 12, 0),
(145, 6, 'DSCN3453_ok.jpg', 13, 0),
(146, 6, 'DSCN3461_ok.jpg', 14, 0),
(147, 6, 'DSCN3468_ok.jpg', 15, 0),
(148, 6, 'DSCN3460_ok.jpg', 16, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagen`
--

CREATE TABLE `imagen` (
  `id_imagen` int(10) UNSIGNED NOT NULL,
  `archivo` varchar(100) DEFAULT NULL,
  `eliminada` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `fecha_creacion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `imagen`
--

INSERT INTO `imagen` (`id_imagen`, `archivo`, `eliminada`, `fecha_creacion`) VALUES
(1, 'logo_color.svg', 0, '2017-04-10 12:31:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `local`
--

CREATE TABLE `local` (
  `id_local` int(10) UNSIGNED NOT NULL,
  `id_plano` int(10) UNSIGNED NOT NULL,
  `id_categoria` int(11) UNSIGNED DEFAULT NULL,
  `numero` varchar(50) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `disponible` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `reservado` tinyint(3) DEFAULT '0',
  `abierto` tinyint(3) UNSIGNED DEFAULT NULL,
  `logo` varchar(50) DEFAULT NULL,
  `metros` float(6,2) UNSIGNED DEFAULT NULL,
  `altura` varchar(6) DEFAULT NULL,
  `instalaciones` text,
  `nota` text,
  `descripcion` text,
  `imagen_ficha` varchar(50) DEFAULT NULL,
  `pie_ficha` varchar(255) DEFAULT NULL,
  `fecha_actualizacion` varchar(255) DEFAULT NULL,
  `foto` varchar(150) DEFAULT NULL,
  `plano_ubicacion` varchar(150) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `pagina_web` varchar(255) DEFAULT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `local`
--

INSERT INTO `local` (`id_local`, `id_plano`, `id_categoria`, `numero`, `nombre`, `disponible`, `reservado`, `abierto`, `logo`, `metros`, `altura`, `instalaciones`, `nota`, `descripcion`, `imagen_ficha`, `pie_ficha`, `fecha_actualizacion`, `foto`, `plano_ubicacion`, `telefono`, `pagina_web`, `eliminado`) VALUES
(1, 1, 2, '100', 'Sodimac Constructor', 0, 0, 0, 'sodimac_con.jpg', NULL, '4,00 ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'constructor.svg', NULL, 'www.sodimac.cl', 0),
(2, 1, 2, '101', 'Homecenter Sodimac', 0, 0, 0, 'sodimac.jpg', NULL, '4,00 ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sodimac.svg', NULL, 'www.sodimac.cl', 0),
(3, 1, 1, '102', 'Akua', 0, 0, 0, 'akua.jpg', 49.57, '4,00 ', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '102.svg', NULL, NULL, NULL, 'akua.svg', NULL, NULL, 0),
(4, 1, NULL, '103', NULL, 1, 1, NULL, NULL, 44.79, '4,00 ', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '103.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(5, 1, NULL, '104', NULL, 1, 0, NULL, NULL, 46.94, '4,00 ', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '104.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(6, 1, NULL, '105', NULL, 1, 1, NULL, NULL, 48.38, '4,00 ', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '105.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(7, 1, NULL, '106', NULL, 1, 1, NULL, NULL, 49.01, '4,00 ', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '106.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(8, 1, NULL, '107-108', NULL, 1, 1, NULL, '', NULL, '4,00 ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(9, 1, NULL, '109', NULL, 1, 1, NULL, NULL, 46.94, '4,00 ', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '109.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(10, 1, 5, '110-111', 'Estrella Alpina', 0, 0, 0, 'alpina.jpg', NULL, '4,00 ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'estrella_alpina.svg', NULL, '', 0),
(11, 1, NULL, '112', NULL, 1, 0, NULL, NULL, 929.45, '4,00 ', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '112.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(12, 1, 5, '113', 'Tricot', 0, 0, 0, NULL, 774.17, '4,00 ', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '113.svg', NULL, NULL, NULL, 'tricot.svg', NULL, 'www.tricot.cl', 0),
(13, 1, 6, '114', 'Tottus', 0, 0, 0, 'tottus.jpg', NULL, '4,00 ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'tottus.svg', NULL, 'www.tottus.cl', 0),
(14, 1, 3, '115', 'Varsovienne', 0, 0, 0, 'varsovienne.jpg', 36.32, '4,00 ', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '115.svg', NULL, NULL, NULL, 'varsovienne.svg', NULL, 'www.varsovienne.cl', 0),
(15, 1, 3, '116', 'Jardín Sushi', 0, 0, 0, 'jardin_sushi.jpg', NULL, '4,00 ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'jardin_sushi.svg', NULL, NULL, 0),
(16, 1, NULL, '117', NULL, 1, 1, NULL, NULL, 40.96, '4,00 ', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '117.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(17, 1, NULL, '118', NULL, 1, 1, NULL, NULL, 43.66, '4,00 ', 'Arranque sensor de humo.\r\nPunto arranque eléctrico.\r\nDucto proyectado alcantarillado.\r\nDucto ventilación.\r\nDucto proyectado agua potable.\r\nBajada aguas lluvia 200 mm.\r\nConexión a gas.\r\nConexión a cámara desgrasadora.', 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.', NULL, '118.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(18, 1, NULL, '119', NULL, 1, 1, NULL, NULL, 45.85, '4,00 ', 'Arranque sensor de humo.\r\nPunto arranque eléctrico.\r\nDucto proyectado alcantarillado.\r\nDucto ventilación.\r\nDucto proyectado agua potable.\r\nBajada aguas lluvia 200 mm.\r\nConexión a gas.\r\nConexión a cámara desgrasadora.', 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.', NULL, '119.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(19, 1, 1, '120-121-122', 'Farmacia Cruz Verde', 0, 0, 1, 'cruz_verde.jpg', NULL, '4,00 ', NULL, NULL, 'Tu salud es lo más importante y Cruz Verde tiene todo para que la cuides. En nuestra farmacia encontrarás todos los medicamentos, suplementos, cremas y productos para el cuidado personal que necesites y recibirás la mejor asesoría de nuestros profesionales.', NULL, NULL, NULL, NULL, 'cruz_verde.svg', NULL, 'www.cruzverde.cl', 0),
(20, 1, NULL, '123', NULL, 1, 1, NULL, NULL, NULL, '4,00 ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(21, 1, 3, '124-125-126-127', 'Starbucks', 0, 0, 1, 'starbucks.jpg', NULL, '4,00 ', NULL, NULL, 'Tu café de la mañana se disfruta mejor en el “Tercer Lugar”. Entre tu casa y tu trabajo está Starbucks para encantarte con el aroma de los mejores granos de café y sus increíbles brownies y sándwiches. Ven y vive la experiencia Starbucks en Paseo Balmaceda.', NULL, NULL, NULL, '124.jpg', 'starbucks.svg', NULL, 'www.starbucks.cl', 0),
(22, 1, 1, '128', 'GMO', 0, 0, 0, NULL, 55.32, '4,00 ', 'Arranque sensor de humo.\nPunto arranque eléctrico.\nDucto proyectado alcantarillado.\nDucto ventilación.\nDucto proyectado agua potable.\nBajada aguas lluvia 200 mm.\nConexión a gas.\nConexión a cámara desgrasadora.', 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.', NULL, '128.svg', NULL, NULL, NULL, 'gmo.svg', NULL, 'www.gmo.cl', 0),
(23, 1, 8, '129', 'Qué Leo', 0, 1, 0, NULL, 49.02, '4,00 ', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '129.svg', NULL, NULL, NULL, 'que_leo.svg', NULL, 'www.queleochile.cl', 0),
(24, 1, NULL, '130', NULL, 1, 0, NULL, NULL, 49.02, '4,00 ', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '130.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(25, 1, NULL, '131', NULL, 1, 0, NULL, NULL, 49.02, '4,00 ', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '131.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(26, 1, NULL, '132', NULL, 1, 0, NULL, NULL, 49.02, '4,00 ', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '132.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(27, 1, 2, '133-134', 'Decoración', 0, 1, 0, NULL, 49.02, '4,00 ', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '133.svg', NULL, NULL, NULL, 'decoracion.svg', NULL, NULL, 0),
(28, 1, NULL, '134', NULL, 1, 1, NULL, NULL, 49.02, '4,00 ', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '134.svg', NULL, NULL, NULL, NULL, NULL, NULL, 1),
(29, 1, 7, '135-136', 'Turismo Cocha', 0, 0, 0, 'cocha.jpg', NULL, '4,00 ', NULL, NULL, NULL, '135.svg', NULL, NULL, NULL, 'cocha.svg', NULL, 'www.cocha.com', 0),
(31, 1, NULL, '137', NULL, 1, 1, NULL, NULL, 49.02, '4,00 ', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '137.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(32, 1, NULL, '138', NULL, 1, 1, NULL, NULL, 49.02, '4,00 ', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '138.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(33, 1, NULL, '139', NULL, 1, 1, NULL, NULL, 49.02, '4,00 ', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '139.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(34, 1, NULL, '140', NULL, 1, 1, NULL, NULL, 49.02, '4,00 ', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '140.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(35, 2, NULL, '200-A', 'Sodimac Constructor', 0, 0, 0, 'sodimac_con.jpg', NULL, '3,50', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'constructor.svg', NULL, 'www.sodimac.cl', 0),
(36, 2, NULL, '200-B', 'Homecenter Sodimac', 0, 0, 0, 'sodimac.jpg', NULL, '3,50', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sodimac.svg', NULL, 'www.sodimac.cl', 0),
(37, 2, 1, '201-202', 'French Beauty', 0, 0, 0, 'french_beauty.jpg', NULL, '3,50', NULL, NULL, NULL, '201.svg', NULL, NULL, NULL, 'french_beauty.svg', NULL, 'www.frenchbeauty.cl', 0),
(39, 2, NULL, '203', NULL, 1, 0, NULL, NULL, 35.17, '3,50', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '203.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(40, 2, NULL, '204', NULL, 1, 0, NULL, NULL, 36.66, '3,50', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '204.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(41, 2, NULL, '205', NULL, 1, 1, NULL, NULL, 37.40, '3,50', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '205.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(42, 2, NULL, '206', NULL, 1, 1, NULL, NULL, 37.40, '3,50', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '206.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(43, 2, NULL, '207', NULL, 1, 1, NULL, NULL, 36.66, '3,50', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '207.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(44, 2, NULL, '208', NULL, 1, 1, NULL, NULL, 35.17, '3,50', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '208.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(45, 2, NULL, '209', NULL, 1, 1, NULL, NULL, 32.94, '3,50', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '209.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(46, 2, NULL, '210', NULL, 1, 1, NULL, NULL, 38.75, '3,50', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '210.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(47, 2, 3, '211', 'Savory', 0, 0, 0, NULL, 52.55, '3,50', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '211.svg', NULL, NULL, NULL, 'savory.svg', NULL, 'www.savory.cl', 0),
(48, 2, 3, '212', 'Juan Maestro', 0, 0, 1, 'juan_maestro.jpg', NULL, '3,50', NULL, NULL, NULL, '212.svg', NULL, NULL, '212.jpg', 'juan_maestro.svg', NULL, NULL, 0),
(49, 2, 3, '213', 'Telepizza', 0, 0, 0, 'telepizza.jpg', NULL, '3,50', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'telepizza.svg', NULL, 'www.telepizza.cl', 0),
(50, 2, NULL, '214', NULL, 1, 1, NULL, NULL, 56.20, '3,50', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '214.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(51, 2, 3, '215', 'Subway', 0, 0, 0, NULL, 56.20, '3,50', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '215.svg', NULL, NULL, NULL, 'subway.svg', NULL, 'www.subway.cl', 0),
(52, 2, 3, '216', 'Doggis ', 0, 0, 1, 'doggis.jpg', NULL, '3,50', NULL, NULL, 'Calma tu hambre con los mejores hot dogs de La Serena. Ven y disfruta de los completos más grandes, ricos y al mejor precio. Acompáñalos con las papas más crujientes y termina con uno de sus exquisitos helados. ', '216.svg', NULL, NULL, '216.jpg', 'doggis.svg', NULL, NULL, 0),
(53, 2, NULL, '217', NULL, 1, 0, NULL, NULL, 459.87, '3,50', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '217.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(54, 2, NULL, '218', NULL, 1, 1, NULL, NULL, 33.47, '3,50', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '218.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(55, 2, 7, '219A', 'Tabaquería Tobaffe', 0, 0, 0, NULL, 19.01, '3,50', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '219.svg', NULL, NULL, NULL, 'tobaffe.svg', NULL, NULL, 0),
(56, 2, NULL, '219B', NULL, 1, 1, NULL, NULL, 19.01, '3,50', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '220.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(57, 2, NULL, '220', NULL, 1, 0, NULL, NULL, 25.33, '3,50', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '221.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(65, 2, NULL, '222', NULL, 1, 0, NULL, NULL, 25.85, '3,50', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '222.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(66, 2, NULL, '223', NULL, 1, 0, NULL, NULL, 19.49, '3,50', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '223.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(67, 2, NULL, '224', NULL, 1, 0, NULL, NULL, 28.84, '3,50', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '224.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(68, 2, NULL, '225', NULL, 1, 0, NULL, NULL, 31.49, '3,50', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '225.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(69, 2, NULL, '226', NULL, 1, 0, NULL, NULL, 33.80, '3,50', NULL, 'Posición de las instalaciones eléctricas y sanitarias debe ser ratificada en terreno.\nLocales disponibles a entregar en condición de obra gruesa (sin terminaciones interiores)', NULL, '226.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(70, 2, NULL, '221', NULL, 1, 1, NULL, NULL, 347.01, '3,50', NULL, NULL, NULL, '227.svg', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(71, 2, 4, '227', 'Energy', 0, 0, 0, 'energy.jpg', NULL, '3,50', NULL, NULL, NULL, '228.svg', NULL, NULL, NULL, 'energy.svg', NULL, 'www.energy.cl', 0),
(72, 2, NULL, '228', 'Núcleo Balmaceda', 0, 0, 0, 'nucleo_balmaceda.jpg', NULL, '3,50', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(73, 1, 3, '141', 'McDonald\'s ', 0, 0, 1, 'mcdonalds.jpg', NULL, NULL, NULL, NULL, '¿Una cajita feliz? ¿Una Big Mac? ¿Qué tal unos ricos Nuggets? Ven a disfrutar tu comida favorita en Paseo Balmaceda y conoce el primer Automac de La Serena. Además, enamórate del innovador sistema “Made For You”, que permite que tu hamburguesa esté siempre fresca. ¡Te esperamos!', NULL, NULL, NULL, '141.jpg', 'mcdonalds.svg', NULL, 'www.mcdonalds.cl', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `local_categoria`
--

CREATE TABLE `local_categoria` (
  `id_categoria` int(10) UNSIGNED NOT NULL,
  `categoria` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `local_categoria`
--

INSERT INTO `local_categoria` (`id_categoria`, `categoria`) VALUES
(1, 'Salud y belleza'),
(2, 'Hogar y decoración'),
(3, 'Comidas y bebidas'),
(4, 'Deportes'),
(5, 'Vestimenta y calzado'),
(6, 'Supermercados'),
(7, 'Tienda de servicios'),
(8, 'Libros y cultura');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `local_punto`
--

CREATE TABLE `local_punto` (
  `id_local_punto` int(10) UNSIGNED NOT NULL,
  `id_local` int(10) UNSIGNED NOT NULL,
  `lat` varchar(10) NOT NULL,
  `long` varchar(10) NOT NULL,
  `orden` smallint(5) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `local_punto`
--

INSERT INTO `local_punto` (`id_local_punto`, `id_local`, `lat`, `long`, `orden`) VALUES
(1, 1, '679.11666', '30.5625', 1),
(2, 1, '573.61666', '30.5625', 2),
(3, 1, '573.61666', '0.0625', 3),
(4, 1, '477.36667', '0.0625', 4),
(5, 1, '477.36667', '30.0625', 5),
(6, 1, '440.36667', '30.0625', 6),
(7, 1, '440.36667', '244.8125', 7),
(8, 1, '679.86667', '244.8125', 8),
(9, 2, '679.86667', '244.8125', 1),
(10, 2, '440.36667', '244.8125', 2),
(11, 2, '440.36667', '602.5625', 3),
(12, 2, '679.86667', '602.5625', 4),
(13, 3, '440.18333', '302.28125', 1),
(14, 3, '436.18333', '302.28125', 2),
(15, 3, '436.18333', '317.28125', 3),
(16, 3, '407.80833', '317.28125', 4),
(17, 3, '405.55833', '330.28125', 5),
(18, 3, '440.18333', '330.28125', 6),
(19, 4, '440.18333', '330.28125', 1),
(20, 4, '405.1833', '330.28125', 2),
(21, 4, '403.30833', '343.15625', 3),
(22, 4, '440.18333', '343.15625', 4),
(23, 5, '440.18333', '343.15625', 1),
(24, 5, '403.30833', '343.15625', 2),
(25, 5, '401.30833', '356.15625', 3),
(26, 5, '440.18333', '356.15625', 4),
(27, 6, '440.18333', '356.15625', 1),
(28, 6, '401.30833', '356.15625', 2),
(29, 6, '400.30833', '369.15625', 3),
(30, 6, '440.18333', '369.15625', 4),
(31, 7, '440.18333', '369.15625', 1),
(32, 7, '400.30833', '369.15625', 2),
(33, 7, '400.30833', '381.90625', 3),
(34, 7, '440.18333', '381.90625', 4),
(35, 8, '440.18333', '381.90625', 1),
(36, 8, '400.30833', '381.90625', 2),
(37, 8, '399.93333', '394.90625', 3),
(38, 8, '401.18333', '407.15625', 4),
(39, 8, '440.18333', '407.15625', 5),
(40, 9, '440.18333', '407.15625', 1),
(41, 9, '401.18333', '407.15625', 2),
(42, 9, '403.55833', '420.40625', 3),
(43, 9, '440.18333', '420.40625', 4),
(44, 10, '440.18333', '420.40625', 1),
(45, 10, '403.55833', '420.40625', 2),
(46, 10, '407.55833', '446.28125', 3),
(47, 10, '435.43333', '446.28125', 4),
(48, 10, '435.43333', '461.65625', 5),
(49, 10, '435.43333', '461.65625', 6),
(50, 10, '440.18333', '461.65625', 7),
(51, 11, '357.55833', '541.28125', 1),
(52, 11, '357.55833', '658.65625', 2),
(53, 11, '443.55833', '658.65625', 3),
(54, 11, '443.55833', '618.78125', 4),
(55, 11, '421.68333', '618.78125', 5),
(56, 11, '421.68333', '505.90625', 6),
(57, 11, '410.55833', '499.53125', 7),
(58, 11, '383.68333', '499.53125', 8),
(59, 11, '383.68333', '541.28125', 9),
(60, 12, '357.55833', '499.53125', 1),
(61, 12, '357.55833', '658.65625', 2),
(62, 12, '306.55833', '658.65625', 3),
(63, 12, '306.55833', '508.78125', 4),
(64, 12, '323.55833', '491.15625', 5),
(65, 12, '331.93333', '499.53125', 6),
(66, 13, '317.18333', '496.90625', 1),
(67, 13, '306.05833', '508.90625', 2),
(68, 13, '306.05833', '709.15625', 3),
(69, 13, '96.05833', '709.15625', 4),
(70, 13, '96.05833', '487.53125', 5),
(71, 13, '122.30833', '487.53125', 6),
(72, 13, '122.30833', '477.90625', 7),
(73, 13, '171.30833', '477.90625', 8),
(74, 13, '171.30833', '487.40625', 9),
(75, 13, '283.18333', '487.40625', 10),
(76, 13, '294.18333', '476.65625', 11),
(77, 14, '301.05833', '469.28125', 1),
(78, 14, '282.93333', '487.15625', 2),
(79, 14, '279.18333', '487.40625', 3),
(80, 14, '279.18333', '460.53125', 4),
(81, 14, '292.18333', '460.53125', 5),
(82, 15, '278.43333', '457.03125', 1),
(83, 15, '278.43333', '487.28125', 2),
(84, 15, '265.68333', '487.28125', 3),
(85, 15, '265.68333', '454.28125', 4),
(86, 16, '265.68333', '454.28125', 1),
(87, 16, '265.68333', '487.28125', 2),
(88, 16, '253.18333', '487.28125', 3),
(89, 16, '253.18333', '452.28125', 4),
(90, 17, '253.18333', '452.28125', 1),
(91, 17, '253.18333', '487.28125', 2),
(92, 17, '239.30833', '487.28125', 3),
(93, 17, '239.30833', '450.15625', 4),
(94, 18, '239.30833', '450.15625', 1),
(95, 18, '239.30833', '487.28125', 2),
(96, 18, '226.55833', '487.28125', 3),
(97, 18, '226.55833', '448.78125', 4),
(98, 19, '226.55833', '448.78125', 1),
(99, 19, '226.55833', '487.28125', 2),
(100, 19, '171.68333', '487.28125', 3),
(101, 19, '171.68333', '478.40625', 4),
(102, 19, '186.43333', '478.40625', 5),
(103, 19, '186.43333', '447.53125', 6),
(104, 20, '148.05833', '461.15625', 1),
(105, 20, '148.05833', '477.78125', 2),
(106, 20, '128.18333', '477.78125', 3),
(107, 20, '128.18333', '461.28125', 4),
(108, 21, '122.05833', '461.28125', 1),
(109, 21, '122.05833', '487.28125', 2),
(110, 21, '54.05833', '487.28125', 3),
(111, 21, '54.05833', '479.28125', 4),
(112, 21, '73.05833', '461.28125', 5),
(113, 22, '95.55833', '503.53125', 1),
(114, 22, '66.05833', '503.53125', 2),
(115, 22, '66.05833', '496.15625', 3),
(116, 22, '55.30833', '496.15625', 4),
(117, 22, '55.30833', '516.40625', 5),
(118, 22, '95.68333', '516.40625', 6),
(119, 23, '95.43333', '516.40625', 1),
(120, 23, '55.30833', '516.40625', 2),
(121, 23, '55.30833', '529.65625', 3),
(122, 23, '95.55833', '529.65625', 4),
(123, 24, '95.55833', '529.65625', 1),
(124, 24, '55.30833', '529.65625', 2),
(125, 24, '55.30833', '542.40625', 3),
(126, 24, '95.55833', '542.40625', 4),
(127, 25, '95.55833', '542.40625', 1),
(128, 25, '55.30833', '542.40625', 2),
(129, 25, '55.30833', '555.40625', 3),
(130, 25, '95.55833', '555.40625', 4),
(131, 26, '95.55833', '555.40625', 1),
(132, 26, '55.30833', '555.40625', 2),
(133, 26, '55.30833', '568.53125', 3),
(134, 26, '95.55833', '568.53125', 4),
(135, 27, '95.55833', '568.53125', 1),
(136, 27, '55.30833', '568.53125', 2),
(137, 27, '55.30833', '581.28125', 3),
(138, 27, '95.55833', '581.28125', 4),
(139, 28, '95.55833', '581.28125', 1),
(140, 28, '55.30833', '581.28125', 2),
(141, 28, '55.30833', '594.53125', 3),
(142, 28, '95.55833', '594.53125', 4),
(143, 29, '95.55833', '594.53125', 1),
(144, 29, '55.30833', '594.53125', 2),
(145, 29, '55.30833', '621.28125', 3),
(146, 29, '95.55833', '621.28125', 4),
(151, 31, '95.55833', '621.28125', 1),
(152, 31, '55.30833', '621.28125', 2),
(153, 31, '55.30833', '634.40625', 3),
(154, 31, '95.55833', '634.40625', 4),
(155, 32, '95.55833', '634.40625', 1),
(156, 32, '55.30833', '634.40625', 2),
(157, 32, '55.30833', '647.78125', 3),
(158, 32, '95.55833', '647.78125', 4),
(159, 33, '95.55833', '647.78125', 1),
(160, 33, '55.30833', '647.78125', 2),
(161, 33, '55.30833', '661.03125', 3),
(162, 33, '95.55833', '661.03125', 4),
(163, 34, '95.55833', '661.03125', 1),
(164, 34, '55.30833', '661.03125', 2),
(165, 34, '55.30833', '674.28125', 3),
(166, 34, '95.55833', '674.28125', 4),
(167, 35, '680.02917', '24.39063', 1),
(168, 35, '573.61666', '24.39063', 2),
(169, 35, '573.61666', '0.0625', 3),
(170, 35, '477.36667', '0.0625', 4),
(171, 35, '477.36667', '24.39063', 5),
(172, 35, '440.36667', '24.39063', 6),
(173, 35, '440.36667', '239.65625', 7),
(174, 35, '680.02917', '239.65625', 8),
(175, 36, '680.02917', '239.65625', 1),
(176, 36, '440.36667', '239.65625', 2),
(177, 36, '440.36667', '600.53125', 3),
(178, 36, '680.02917', '600.53125', 4),
(179, 37, '440.36667', '299.53125', 1),
(180, 37, '435.93333', '299.53125', 2),
(181, 37, '435.93333', '313.6562', 3),
(182, 37, '416.30833', '313.6562', 4),
(183, 37, '411.80833', '339.51563', 5),
(184, 37, '440.36667', '339.51563', 6),
(189, 39, '440.36667', '339.51563', 1),
(190, 39, '411.80833', '339.51563', 2),
(191, 39, '410.30833', '352.70313', 3),
(192, 39, '440.36667', '352.70313', 4),
(193, 40, '440.36667', '352.70313', 1),
(194, 40, '410.30833', '352.70313', 2),
(195, 40, '409.30833', '365.82813', 3),
(196, 40, '440.36667', '365.82813', 4),
(197, 41, '440.36667', '365.82813', 1),
(198, 41, '409.30833', '365.82813', 2),
(199, 41, '409.30833', '379.01563', 3),
(200, 41, '440.36667', '379.01563', 4),
(201, 42, '440.36667', '379.01563', 1),
(202, 42, '409.30833', '379.01563', 2),
(203, 42, '409.30833', '392.26563', 3),
(204, 42, '440.36667', '392.26563', 4),
(205, 43, '440.36667', '392.26563', 1),
(206, 43, '409.30833', '392.26563', 2),
(207, 43, '410.30833', '405.39063', 3),
(208, 43, '440.36667', '405.39063', 4),
(209, 44, '440.36667', '405.39063', 1),
(210, 44, '410.30833', '405.39063', 2),
(211, 44, '411.80833', '418.45313', 3),
(212, 44, '440.36667', '418.45313', 4),
(213, 45, '440.36667', '418.45313', 1),
(214, 45, '411.80833', '418.45313', 2),
(215, 45, '414.30833', '431.70313', 3),
(216, 45, '440.36667', '431.70313', 4),
(217, 46, '440.36667', '431.70313', 1),
(218, 46, '414.30833', '431.70313', 2),
(219, 46, '416.43333', '444.82813', 3),
(220, 46, '436.30833', '444.82813', 4),
(221, 46, '436.30833', '458.51563', 5),
(222, 46, '440.36667', '458.51563', 6),
(223, 47, '440.36667', '542.64063', 1),
(224, 47, '421.71667', '542.64063', 2),
(225, 47, '421.71667', '563.07813', 3),
(226, 47, '436.93333', '578.95313', 4),
(227, 47, '440.36667', '578.95313', 5),
(228, 48, '432.80833', '617.53906', 1),
(229, 48, '432.80833', '584.28125', 2),
(230, 48, '421.93333', '573.65625', 3),
(231, 48, '408.17083', '587.90625', 4),
(232, 48, '408.17083', '617.53906', 5),
(233, 49, '408.17083', '617.53906', 1),
(234, 49, '408.17083', '586.57813', 2),
(235, 49, '387.67083', '586.57813', 3),
(236, 49, '387.67083', '617.53906', 4),
(237, 50, '387.67083', '617.53906', 1),
(238, 50, '387.67083', '586.57813', 2),
(239, 50, '367.55833', '586.57813', 3),
(240, 50, '367.55833', '617.53906', 4),
(241, 51, '367.55833', '617.53906', 1),
(242, 51, '367.55833', '586.57813', 2),
(243, 51, '346.93333', '586.57813', 3),
(244, 51, '346.93333', '617.53906', 4),
(245, 52, '346.93333', '624.28125', 1),
(246, 52, '346.93333', '586.57813', 2),
(247, 52, '327.43333', '586.57813', 3),
(248, 52, '327.43333', '624.28125', 4),
(249, 53, '327.43333', '590.8625', 1),
(250, 53, '327.43333', '582.03125', 2),
(251, 53, '309.80833', '563.78125', 3),
(252, 53, '302.55833', '563.78125', 4),
(253, 53, '302.55833', '590.8625', 5),
(256, 54, '318.29583', '554.38281', 1),
(257, 54, '309.54583', '562.72656', 2),
(258, 54, '302.98333', '562.72656', 3),
(259, 54, '302.98333', '537.47656', 4),
(260, 54, '318.29583', '537.47656', 5),
(261, 55, '318.29583', '537.47656', 1),
(262, 55, '302.55833', '537.47656', 2),
(263, 55, '302.55833', '524.28906', 3),
(264, 55, '318.29583', '524.28906', 4),
(265, 56, '318.29583', '524.28906', 1),
(266, 56, '302.55833', '524.28906', 2),
(267, 56, '302.55833', ' 511.20313', 3),
(268, 56, '318.05833', ' 511.20313', 4),
(269, 57, '318.05833', ' 511.20313', 1),
(270, 57, '302.55833', ' 511.20313', 2),
(271, 57, '296.40417', '505.01563', 3),
(272, 57, '310.90417', '490.64063', 4),
(273, 57, '318.05833', '498.97656', 5),
(303, 65, '296.82708', '476.00781', 1),
(304, 65, '281.85833', '490.66406', 2),
(305, 65, '275.92083', '484.72656', 3),
(306, 65, '275.79583', '465.53906', 4),
(307, 65, '286.73333', '465.53906', 5),
(308, 66, '275.92083', '484.72656', 1),
(309, 66, '275.92083', '465.53906', 2),
(310, 66, '264.21667', '465.53906', 3),
(311, 66, '264.21667', '484.72656', 4),
(312, 67, '264.21667', '484.72656', 1),
(313, 67, '264.21667', '462.20313', 2),
(314, 67, '250.71667', '459.60156', 3),
(315, 67, '250.65417', '484.72656', 4),
(316, 68, '250.71667', '459.60156', 1),
(317, 68, '250.65417', '484.72656', 2),
(318, 68, '237.27917', '484.72656', 3),
(319, 68, '237.27917', '457.57813', 4),
(320, 69, '237.27917', '484.72656', 1),
(321, 69, '237.27917', '457.57813', 2),
(322, 69, '224.67083', '456.25781', 3),
(323, 69, '224.67083', '484.72656', 4),
(324, 70, '310.90417', '490.64063', 1),
(325, 70, '296.40417', '505.01563', 2),
(326, 70, '302.55833', '511.20313', 3),
(327, 70, '302.55833', '537.47656', 4),
(328, 70, '235.09167', '537.47656', 5),
(329, 70, '235.09167', '484.72656', 6),
(330, 70, '275.92083', '484.72656', 7),
(331, 70, '281.85833', '490.66406', 8),
(332, 70, '296.82708', '476.00781', 9),
(333, 71, '224.67083', '456.25781', 1),
(334, 71, '224.67083', '484.72656', 2),
(335, 71, '235.09167', '484.72656', 3),
(336, 71, '235.09167', '591.57813', 4),
(337, 71, '146.34167', '591.57813', 5),
(338, 71, '146.34167', '526.20313', 6),
(339, 71, '121.90417', '526.26563', 7),
(340, 71, '121.90417', '487.32813', 8),
(341, 71, '134.84167', '487.32813', 9),
(342, 71, '134.84167', '465.32813', 10),
(343, 71, '121.90417', '465.32813', 11),
(344, 71, '121.90417', '458.39063', 12),
(345, 71, '171.40417', '458.39063', 13),
(346, 71, '171.40417', '475.20313', 14),
(347, 71, '184.52917', '475.20313', 15),
(348, 71, '184.52917', '454.76563', 16),
(349, 71, '198.71667', '454.76563', 17),
(350, 72, '121.90417', '458.39063', 1),
(351, 72, '121.90417', '683.26563', 2),
(352, 72, '97.71667', '683.26563', 3),
(353, 72, '97.71667', '709.45313', 4),
(354, 72, '54.34167', '709.45313', 5),
(355, 72, '54.34167', '698.07813', 6),
(356, 72, '40.09167', '698.07813', 7),
(357, 72, '40.09167', '669.57813', 8),
(358, 72, '54.15417', '669.57813', 9),
(359, 72, '54.15417', '477.64063', 10),
(360, 72, '73.27917', '458.39063', 11);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `local_terminacion`
--

CREATE TABLE `local_terminacion` (
  `id_local_terminacion` int(10) UNSIGNED NOT NULL,
  `id_terminacion` int(10) UNSIGNED NOT NULL,
  `id_local` int(10) UNSIGNED NOT NULL,
  `valor` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `local_terminacion`
--

INSERT INTO `local_terminacion` (`id_local_terminacion`, `id_terminacion`, `id_local`, `valor`) VALUES
(1, 7, 3, 'Si'),
(2, 1, 3, '100 W/m2'),
(3, 2, 3, '25 mm'),
(4, 3, 3, '110 mm'),
(5, 4, 3, 'Si'),
(6, 5, 3, 'Sin factibilidad'),
(7, 6, 3, 'Sin factibilidad'),
(8, 1, 4, '100 W/m2'),
(9, 2, 4, '25 mm'),
(10, 3, 4, '110 mm'),
(11, 4, 4, 'Si'),
(12, 5, 4, 'Sin factibilidad'),
(13, 6, 4, 'Sin factibilidad'),
(14, 7, 4, 'Si'),
(15, 1, 5, '100 W/m2'),
(16, 2, 5, '25 mm'),
(17, 3, 5, '110 mm'),
(18, 4, 5, 'Si'),
(19, 5, 5, 'Sin factibilidad'),
(20, 6, 5, 'Sin factibilidad'),
(21, 7, 5, 'Si'),
(22, 1, 6, '100 W/m2'),
(23, 2, 6, '25 mm'),
(24, 3, 6, '110 mm'),
(25, 4, 6, 'Si'),
(26, 5, 6, 'Sin factibilidad'),
(27, 6, 6, 'Sin factibilidad'),
(28, 7, 6, 'Si'),
(29, 1, 7, '100 W/m2'),
(30, 2, 7, '25 mm'),
(31, 3, 7, '110 mm'),
(32, 4, 7, 'Si'),
(33, 5, 7, 'Sin factibilidad'),
(34, 6, 7, 'Sin factibilidad'),
(35, 7, 7, 'Si'),
(36, 1, 9, '100 W/m2'),
(37, 2, 9, '25 mm'),
(38, 3, 9, '110 mm'),
(39, 4, 9, 'Si'),
(40, 5, 9, 'Sin factibilidad'),
(41, 6, 9, 'Sin factibilidad'),
(42, 7, 9, 'Si'),
(43, 1, 11, '150 W/m2'),
(44, 2, 11, '75 mm'),
(45, 3, 11, '110 mm'),
(46, 4, 11, 'Si'),
(47, 5, 11, 'Sin factibilidad'),
(48, 6, 11, 'Sin factibilidad'),
(49, 7, 11, 'Si'),
(50, 1, 12, '150 W/M2'),
(51, 2, 12, '75 MM'),
(52, 3, 12, '110 MM'),
(53, 4, 12, 'Si'),
(54, 5, 12, 'Sin factibilidad'),
(55, 6, 12, 'Sin factibilidad'),
(56, 7, 12, 'Si'),
(57, 1, 14, '350 W/M2'),
(58, 2, 14, '25 MM'),
(59, 3, 14, '110 MM'),
(60, 4, 14, 'Si'),
(61, 5, 14, '110 MM'),
(62, 6, 14, 'Si'),
(63, 7, 14, 'Si'),
(64, 1, 16, '350 W/M2'),
(65, 2, 16, '25 MM'),
(66, 3, 16, '110 MM'),
(67, 4, 16, 'Si'),
(68, 5, 16, '110 MM'),
(69, 6, 16, 'Si'),
(70, 7, 16, 'Si'),
(71, 1, 23, '100 W/m2'),
(72, 2, 23, '25 mm'),
(73, 3, 23, '110 mm'),
(74, 4, 23, 'Si'),
(75, 5, 23, 'Sin factibilidad'),
(76, 6, 23, 'Sin factibilidad'),
(77, 7, 23, 'Si'),
(78, 1, 24, '100 W/m2'),
(79, 2, 24, '25 mm'),
(80, 3, 24, '110 mm'),
(81, 4, 24, 'Si'),
(82, 5, 24, 'Sin factibilidad'),
(83, 6, 24, 'Sin factibilidad'),
(84, 7, 24, 'Si'),
(85, 1, 25, '100 W/m2'),
(86, 2, 25, '25 mm'),
(87, 3, 25, '110 mm'),
(88, 4, 25, 'Si'),
(89, 5, 25, 'Sin factibilidad'),
(90, 6, 25, 'Sin factibilidad'),
(91, 7, 25, 'Si'),
(92, 1, 26, '100 W/m2'),
(93, 2, 26, '25 mm'),
(94, 3, 26, '110 mm'),
(95, 4, 26, 'Si'),
(96, 5, 26, 'Sin factibilidad'),
(97, 6, 26, 'Sin factibilidad'),
(98, 7, 26, 'Si'),
(99, 1, 27, '100 W/m2'),
(100, 2, 27, '25 mm'),
(101, 3, 27, '110 mm'),
(102, 4, 27, 'Si'),
(103, 5, 27, 'Sin factibilidad'),
(104, 6, 27, 'Sin factibilidad'),
(105, 7, 27, 'Si'),
(106, 1, 28, '100 W/m2'),
(107, 2, 28, '25 mm'),
(108, 3, 28, '110 mm'),
(109, 4, 28, 'Si'),
(110, 5, 28, 'Sin factibilidad'),
(111, 6, 28, 'Sin factibilidad'),
(112, 7, 28, 'Si'),
(113, 1, 29, '100 W/m2'),
(114, 2, 29, '25 mm'),
(115, 3, 29, '110 mm'),
(116, 4, 29, 'Si'),
(117, 5, 29, 'Sin factibilidad'),
(118, 6, 29, 'Sin factibilidad'),
(119, 7, 29, 'Si'),
(120, 1, 30, '100 W/m2'),
(121, 2, 30, '25 mm'),
(122, 3, 30, '110 mm'),
(123, 4, 30, 'Si'),
(124, 5, 30, 'Sin factibilidad'),
(125, 6, 30, 'Sin factibilidad'),
(126, 7, 30, 'Si'),
(127, 1, 31, '100 W/m2'),
(128, 2, 31, '25 mm'),
(129, 3, 31, '110 mm'),
(130, 4, 31, 'Si'),
(131, 5, 31, 'Sin factibilidad'),
(132, 6, 31, 'Sin factibilidad'),
(133, 7, 31, 'Si'),
(134, 1, 32, '100 W/m2'),
(135, 2, 32, '25 mm'),
(136, 3, 32, '110 mm'),
(137, 4, 32, 'Si'),
(138, 5, 32, 'Sin factibilidad'),
(139, 6, 32, 'Sin factibilidad'),
(140, 7, 32, 'Si'),
(141, 1, 33, '100 W/m2'),
(142, 2, 33, '25 mm'),
(143, 3, 33, '110 mm'),
(144, 4, 33, 'Si'),
(145, 5, 33, 'Sin factibilidad'),
(146, 6, 33, 'Sin factibilidad'),
(147, 7, 33, 'Si'),
(148, 1, 34, '100 W/m2'),
(149, 2, 34, '25 mm'),
(150, 3, 34, '110 mm'),
(151, 4, 34, 'Si'),
(152, 5, 34, 'Sin factibilidad'),
(153, 6, 34, 'Sin factibilidad'),
(154, 7, 34, 'Si'),
(155, 1, 37, '100 W/m2'),
(156, 2, 37, '25 mm'),
(157, 3, 37, '110 mm'),
(158, 4, 37, 'Si'),
(159, 5, 37, 'Sin factibilidad'),
(160, 6, 37, 'Sin factibilidad'),
(161, 7, 37, 'Si'),
(162, 1, 38, '100 W/m2'),
(163, 2, 38, '25 mm'),
(164, 3, 38, '110 mm'),
(165, 4, 38, 'Si'),
(166, 5, 38, 'Sin factibilidad'),
(167, 6, 38, 'Sin factibilidad'),
(168, 7, 38, 'Si'),
(169, 1, 39, '100 W/m2'),
(170, 2, 39, '25 mm'),
(171, 3, 39, '110 mm'),
(172, 4, 39, 'Si'),
(173, 5, 39, 'Sin factibilidad'),
(174, 6, 39, 'Sin factibilidad'),
(175, 7, 39, 'Si'),
(176, 1, 40, '100 W/m2'),
(177, 2, 40, '25 mm'),
(178, 3, 40, '110 mm'),
(179, 4, 40, 'Si'),
(180, 5, 40, 'Sin factibilidad'),
(181, 6, 40, 'Sin factibilidad'),
(182, 7, 40, 'Si'),
(183, 1, 41, '100 W/m2'),
(184, 2, 41, '25 mm'),
(185, 3, 41, '110 mm'),
(186, 4, 41, 'Si'),
(187, 5, 41, 'Sin factibilidad'),
(188, 6, 41, 'Sin factibilidad'),
(189, 7, 41, 'Si'),
(190, 1, 42, '100 W/m2'),
(191, 2, 42, '25 mm'),
(192, 3, 42, '110 mm'),
(193, 4, 42, 'Si'),
(194, 5, 42, 'Sin factibilidad'),
(195, 6, 42, 'Sin factibilidad'),
(196, 7, 42, 'Si'),
(197, 1, 43, '100 W/m2'),
(198, 2, 43, '25 mm'),
(199, 3, 43, '110 mm'),
(200, 4, 43, 'Si'),
(201, 5, 43, 'Sin factibilidad'),
(202, 6, 43, 'Sin factibilidad'),
(203, 7, 43, 'Si'),
(204, 1, 44, '100 W/m2'),
(205, 2, 44, '25 mm'),
(206, 3, 44, '110 mm'),
(207, 4, 44, 'Si'),
(208, 5, 44, 'Sin factibilidad'),
(209, 6, 44, 'Sin factibilidad'),
(210, 7, 44, 'Si'),
(211, 1, 45, '100 W/m2'),
(212, 2, 45, '25 mm'),
(213, 3, 45, '110 mm'),
(214, 4, 45, 'Si'),
(215, 5, 45, 'Sin factibilidad'),
(216, 6, 45, 'Sin factibilidad'),
(217, 7, 45, 'Si'),
(218, 1, 46, '100 W/m2'),
(219, 2, 46, '25 mm'),
(220, 3, 46, '110 mm'),
(221, 4, 46, 'Si'),
(222, 5, 46, 'Sin factibilidad'),
(223, 6, 46, 'Sin factibilidad'),
(224, 7, 46, 'Si'),
(225, 1, 47, '500 W/m2'),
(226, 2, 47, '32 mm'),
(227, 3, 47, '110 mm'),
(228, 4, 47, 'Si'),
(229, 5, 47, '110 mm'),
(230, 6, 47, 'Si'),
(231, 7, 47, 'Si'),
(232, 1, 48, '500 W/m2'),
(233, 2, 48, '32 mm'),
(234, 3, 48, '110 mm'),
(235, 4, 48, 'Si'),
(236, 5, 48, '110 mm'),
(237, 6, 48, 'Si'),
(238, 7, 48, 'Si'),
(239, 1, 50, '500 W/m2'),
(240, 2, 50, '32 mm'),
(241, 3, 50, '110 mm'),
(242, 4, 50, 'Si'),
(243, 5, 50, '110 mm'),
(244, 6, 50, 'Si'),
(245, 7, 50, 'Si'),
(246, 1, 51, '500 W/m2'),
(247, 2, 51, '32 mm'),
(248, 3, 51, '110 mm'),
(249, 4, 51, 'Si'),
(250, 5, 51, '110 mm'),
(251, 6, 51, 'Si'),
(252, 7, 51, 'Si'),
(253, 1, 52, '500 W/m2'),
(254, 2, 52, '50 mm'),
(255, 3, 52, '110 mm'),
(256, 4, 52, 'Si'),
(257, 5, 52, '110 mm'),
(258, 6, 52, 'Si'),
(259, 7, 52, 'Si'),
(260, 1, 53, '150 W/m2'),
(261, 2, 53, '63 mm'),
(262, 3, 53, '110 mm'),
(263, 4, 53, 'Si'),
(264, 5, 53, 'Sin factibilidad'),
(265, 6, 53, 'Sin factibilidad'),
(266, 7, 53, 'Si'),
(267, 1, 54, '100 W/m2'),
(268, 2, 54, 'Sin factibilidad'),
(269, 3, 54, 'Sin factibilidad'),
(270, 4, 54, 'Si'),
(271, 5, 54, 'Sin factibilidad'),
(272, 6, 54, 'Sin factibilidad'),
(273, 7, 54, 'Si'),
(274, 1, 55, '100 W/m2'),
(275, 2, 55, 'Sin factibilidad'),
(276, 3, 55, 'Sin factibilidad'),
(277, 4, 55, 'Si'),
(278, 5, 55, 'Sin factibilidad'),
(279, 6, 55, 'Sin factibilidad'),
(280, 7, 55, 'Si'),
(281, 1, 56, '100 W/m2'),
(282, 2, 56, 'Sin factibilidad'),
(283, 3, 56, 'Sin factibilidad'),
(284, 4, 56, 'Si'),
(285, 5, 56, 'Sin factibilidad'),
(286, 6, 56, 'Sin factibilidad'),
(287, 7, 56, 'Si'),
(288, 1, 57, '100 W/m2'),
(289, 2, 57, '25 mm'),
(290, 3, 57, '110 mm'),
(291, 4, 57, 'Si'),
(292, 5, 57, 'Sin factibilidad'),
(293, 6, 57, 'Sin factibilidad'),
(294, 7, 57, 'Si'),
(295, 1, 65, '100 W/m2'),
(296, 2, 65, '25 mm'),
(297, 3, 65, '110 mm'),
(298, 4, 65, 'Si'),
(299, 5, 65, 'Sin factibilidad'),
(300, 6, 65, 'Sin factibilidad'),
(301, 7, 65, 'Si'),
(302, 1, 66, '100 W/m2'),
(303, 2, 66, '25 mm'),
(304, 3, 66, '110 mm'),
(305, 4, 66, 'Si'),
(306, 5, 66, 'Sin factibilidad'),
(307, 6, 66, 'Sin factibilidad'),
(308, 7, 66, 'Si'),
(309, 1, 67, '100 W/m2'),
(310, 2, 67, '25 mm'),
(311, 3, 67, '110 mm'),
(312, 4, 67, 'Si'),
(313, 5, 67, 'Sin factibilidad'),
(314, 6, 67, 'Sin factibilidad'),
(315, 7, 67, 'Si'),
(316, 1, 68, '350 W/m2'),
(317, 2, 68, '25 mm'),
(318, 3, 68, '110 mm'),
(319, 4, 68, 'Si'),
(320, 5, 68, 'Sin factibilidad'),
(321, 6, 68, 'Sin factibilidad'),
(322, 7, 68, 'Si'),
(323, 2, 69, '110 mm'),
(324, 3, 69, '110 mm'),
(325, 4, 69, 'Si'),
(326, 5, 69, 'Sin factibilidad'),
(327, 6, 69, 'Si'),
(328, 7, 69, 'Si'),
(329, 1, 69, '100 W/m2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log`
--

CREATE TABLE `log` (
  `id_log` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marca`
--

CREATE TABLE `marca` (
  `id_marca` int(10) UNSIGNED NOT NULL,
  `id_sitio` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `logo` varchar(150) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `orden` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `eliminado` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `marca`
--

INSERT INTO `marca` (`id_marca`, `id_sitio`, `nombre`, `logo`, `link`, `orden`, `eliminado`) VALUES
(1, 1, 'Energy', 'energy.png', NULL, 1, 0),
(2, 1, 'Estrella Alpina', 'estrella_alpina.png', NULL, 2, 0),
(3, 1, 'Homecenter Sodimac', 'homecenter_sodimac.png', NULL, 3, 0),
(4, 1, 'McDonald\'s', 'mcdonalds.png', NULL, 4, 0),
(5, 1, 'Salón Akua', 'salon_akua.png', NULL, 5, 0),
(6, 1, 'Sodimac Constructor', 'sodimac_constructor.png', NULL, 6, 0),
(7, 1, 'Starbucks', 'starbucks.png', NULL, 7, 0),
(8, 1, 'Telepizza', 'telepizza.png', NULL, 8, 0),
(9, 2, 'Telepizza', 'telepizza.png', NULL, 1, 0),
(10, 2, 'Energy', 'energy.png', NULL, 3, 0),
(11, 2, 'Estrella Alpina', 'estrella_alpina.png', NULL, 2, 0),
(12, 2, 'Homecenter Sodimac', 'homecenter_sodimac.png', NULL, 4, 0),
(13, 2, 'McDonald\'s', 'mcdonalds.png', NULL, 5, 0),
(14, 2, 'Salón Akua', 'salon_akua.png', NULL, 6, 0),
(15, 2, 'Sodimac Constructor', 'sodimac_constructor.png', NULL, 7, 0),
(16, 2, 'Starbucks', 'starbucks.png', NULL, 8, 0),
(17, 1, 'Tottus', 'tottus.png', NULL, 9, 0),
(18, 2, 'Tottus', 'tottus.png', NULL, 9, 0),
(19, 2, 'GMO', 'gmo.png', NULL, 10, 0),
(20, 2, 'Que Leo', 'que_leo.png', NULL, 11, 0),
(21, 2, 'Varsovienne', 'varsovienne.png', NULL, 12, 0),
(22, 2, 'Farmacia Cruz Verde', 'cruz_verde.png', NULL, 17, 0),
(23, 2, NULL, NULL, NULL, 14, 1),
(24, 2, 'Turismo Cocha', 'cocha_carrusel.png', NULL, 13, 0),
(25, 2, 'Tricot', 'tricot_carrusel.png', NULL, 14, 0),
(26, 2, 'Jardin Sushi', 'jardin_sushi_carrusel.png', NULL, 15, 0),
(27, 2, 'Juan Maestro', 'juan_maestro_carrusel.png', NULL, 16, 0),
(28, 2, 'Doggis', 'doggis_carrusel.png', NULL, 18, 0),
(29, 1, NULL, 'cocha_carrusel.png', NULL, 10, 0),
(30, 1, NULL, 'doggis_carrusel.png', NULL, 11, 0),
(31, 1, NULL, 'gmo.svg', NULL, 12, 0),
(32, 1, NULL, 'que_leo.svg', NULL, 13, 0),
(33, 1, NULL, 'tricot_carrusel.png', NULL, 14, 0),
(34, 1, NULL, 'varsovienne.svg', NULL, 15, 0),
(35, 1, NULL, NULL, NULL, 16, 1),
(36, 1, NULL, 'jardin_sushi_carrusel.png', NULL, 16, 0),
(37, 1, NULL, 'juan_maestro_carrusel.png', NULL, 17, 0),
(38, 1, NULL, 'subway_logo_paseo.png', NULL, 18, 0),
(39, 1, NULL, 'cruz_verde.png', NULL, 19, 0),
(40, 1, NULL, NULL, NULL, 20, 1),
(41, 2, NULL, 'subway_logo_paseo.png', NULL, 19, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `id_menu` int(10) UNSIGNED NOT NULL,
  `id_sitio` int(10) UNSIGNED NOT NULL,
  `id_padre` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `nombre` varchar(50) NOT NULL,
  `alias` varchar(200) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `orden` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `eliminado` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`id_menu`, `id_sitio`, `id_padre`, `nombre`, `alias`, `link`, `orden`, `eliminado`) VALUES
(1, 1, 0, 'INICIO', '/', NULL, 1, 0),
(2, 1, 0, 'CONÓCENOS', 'acerca_de_la_empresa', NULL, 2, 0),
(3, 1, 0, 'CENTROS COMERCIALES', 'informacion_paseo_balmaceda', NULL, 3, 0),
(4, 1, 0, 'CONTACTO', 'contacto_paseo', NULL, 11, 0),
(5, 1, 3, 'PASEO BALMACEDA, LA SERENA', 'informacion_paseo_balmaceda', NULL, 4, 0),
(6, 1, 3, 'PASEO LA PORTADA, ANTOFAGASTA', 'informacion_paseo_la_portada', NULL, 5, 0),
(7, 1, 3, 'PASEO SAN FERNANDO, COPIAPÓ', 'informacion_paseo_san_fernando', NULL, 6, 0),
(8, 1, 3, 'PASEO MONTT, CORONEL', 'informacion_paseo_montt', NULL, 7, 0),
(9, 1, 3, 'PASEO LAS RASTRAS, TALCA', 'informacion_paseo_las_rastras', NULL, 8, 0),
(10, 1, 3, 'PASEO MANUEL RODRÍGUEZ, CALAMA', 'informacion_paseo_manuel_rodriguez', NULL, 9, 0),
(11, 1, 3, 'PASEO MACHALÍ', 'informacion_paseo_machali', NULL, 10, 1),
(12, 2, 0, 'INICIO', '/', NULL, 1, 0),
(13, 2, 0, 'TIENDAS', 'tiendas_paseo_balmaceda', NULL, 2, 0),
(14, 2, 13, 'BUSCA TU TIENDA', 'tiendas_paseo_balmaceda', NULL, 3, 0),
(15, 2, 13, 'PRÓXIMAMENTE', 'promociones_paseo_balmaceda', NULL, 4, 0),
(16, 2, 0, 'NOTICIAS', 'noticias_paseo_balmaceda', NULL, 5, 0),
(17, 2, 16, 'EVENTOS', 'eventos_paseo_balmaceda', NULL, 6, 1),
(18, 2, 0, 'GALERÍAS', 'eventos_anteriores_paseo_balmaceda', NULL, 6, 0),
(19, 2, 0, 'INFORMACIÓN COMERCIAL', 'informacion_comercial_paseo_balmaceda', NULL, 7, 0),
(20, 2, 0, 'CONÓCENOS', 'acerca_de_la_empresa', NULL, 8, 0),
(21, 2, 0, 'CONTACTO', 'contacto_paseo_balmaceda', NULL, 9, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagina`
--

CREATE TABLE `pagina` (
  `id_pagina` int(10) UNSIGNED NOT NULL,
  `id_sitio` int(10) UNSIGNED NOT NULL,
  `id_pagina_tipo` smallint(5) UNSIGNED NOT NULL,
  `alias` varchar(255) NOT NULL,
  `titulo1` varchar(150) DEFAULT NULL,
  `titulo2` varchar(150) DEFAULT NULL,
  `texto` text,
  `fondo` varchar(255) DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL,
  `eliminada` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `pagina`
--

INSERT INTO `pagina` (`id_pagina`, `id_sitio`, `id_pagina_tipo`, `alias`, `titulo1`, `titulo2`, `texto`, `fondo`, `fecha_creacion`, `eliminada`) VALUES
(1, 1, 1, 'acerca_de_la_empresa', NULL, 'Conócenos', '<p>Nos inspira construir centros comerciales con un estilo alegre y amistoso con sus usuarios y de esta forma acercarnos a la comunidad. Todos ubicados en lugares estrat&eacute;gicos para entregar una alternativa de comercio atractiva, c&oacute;moda y adaptada a la realidad local.</p>\n\n<p>&nbsp;</p>\n\n<p>As&iacute; nacen nuestros distintos Paseos, que hoy suman seis centros comerciales vecinales para atender una creciente demanda desde Antofagasta a Concepci&oacute;n.</p>\n\n<p>&nbsp;</p>\n\n<p>En cada uno de ellos, de diferentes tama&ntilde;os, proponemos los productos y servicios necesarios para evitar desplazamientos por toda la ciudad. Desde hipermercados o grandes tiendas hasta el m&aacute;s peque&ntilde;o de los locales.</p>\n\n<p>&nbsp;</p>\n\n<p>Ese fue el compromiso que adquiri&oacute; Neorentas al lanzar Paseo en 2014; encontrar terrenos estrat&eacute;gicos y desarrollar proyectos inmobiliarios atractivos, logrando incorporar a importantes operadores y actores.</p>\n\n<p>&nbsp;</p>\n\n<p>As&iacute; es Paseo, un lugar donde se pasa bien y que resuelve tus necesidades.</p>\n\n<p>&nbsp;</p>\n\n<p><strong style=\"color: rgb(0, 177, 235);\">&iexcl;Te invitamos a dar un Paseo!</strong></p>', 'fondo2.jpg', '2017-04-10 12:31:38', 0),
(2, 1, 2, 'contacto_paseo', NULL, 'Contacto', NULL, 'fondo4.jpg', '2017-04-10 12:39:33', 0),
(3, 2, 3, 'informacion_paseo_balmaceda', 'Centros', 'Comerciales', NULL, 'fondo_balmaceda.jpg', '2017-04-10 13:34:06', 0),
(4, 4, 3, 'informacion_paseo_la_portada', 'Centros', 'Comerciales', NULL, 'fondo_la_portada.jpg', '2017-04-10 15:35:37', 0),
(5, 3, 3, 'informacion_paseo_las_rastras', 'Centros', 'Comerciales', NULL, 'fondo_las_rastras.jpg', '2017-04-10 15:36:28', 0),
(6, 5, 3, 'informacion_paseo_san_fernando', 'Centros', 'Comerciales', NULL, 'fondo_san_fernando.jpg', '2017-04-10 15:36:56', 0),
(7, 6, 3, 'informacion_paseo_montt', 'Centros', 'Comerciales', NULL, 'fondo_biobio.jpg', '2017-04-10 15:37:28', 0),
(8, 7, 3, 'informacion_paseo_manuel_rodriguez', 'Centros', 'Comerciales', NULL, 'fondo_manuel_rodriguez.jpg', '2017-04-10 15:37:44', 0),
(9, 8, 3, 'informacion_paseo_machali', 'Centros', 'Comerciales', NULL, 'fondo_machali.jpg', '2017-04-10 15:38:02', 0),
(10, 4, 2, 'contacto_paseo_la_portada', NULL, 'Contacto', NULL, NULL, '2017-04-10 15:54:52', 0),
(11, 2, 2, 'contacto_paseo_balmaceda', NULL, 'Contacto', NULL, 'fondo7.jpg', '2017-04-10 15:55:28', 0),
(12, 3, 2, 'contacto_paseo_las_rastras', NULL, NULL, NULL, NULL, '2017-04-10 16:55:06', 0),
(13, 6, 2, 'contacto_paseo_montt', NULL, 'Contacto', NULL, NULL, '2017-04-21 16:40:12', 0),
(14, 2, 4, 'tiendas_paseo_balmaceda', NULL, 'Tiendas', NULL, 'fondo2.jpg', '2017-04-21 17:13:46', 0),
(15, 2, 5, 'promociones_paseo_balmaceda', NULL, 'Próximamente', NULL, 'fondo5.jpg', '2017-04-21 17:37:02', 0),
(16, 2, 6, 'noticias_paseo_balmaceda', NULL, 'Noticias', NULL, 'fondo4.jpg', '2017-04-24 09:56:22', 0),
(17, 2, 7, 'eventos_anteriores_paseo_balmaceda', NULL, 'Galerías', NULL, 'fondo8.jpg', '2017-04-24 09:57:18', 0),
(18, 2, 3, 'informacion_comercial_paseo_balmaceda', 'Información', 'Comercial', NULL, 'fondo6.jpg', '2017-04-24 18:20:09', 0),
(19, 5, 2, 'contacto_paseo_san_fernando', NULL, NULL, NULL, NULL, '2017-08-22 18:14:03', 0),
(20, 7, 2, 'contacto_paseo_manuel_rodriguez', NULL, NULL, NULL, NULL, '2017-08-22 18:18:21', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagina_imagen`
--

CREATE TABLE `pagina_imagen` (
  `id_pagina_imagen` int(10) UNSIGNED NOT NULL,
  `id_pagina` int(10) UNSIGNED NOT NULL,
  `id_imagen` int(10) UNSIGNED NOT NULL,
  `orden` int(10) UNSIGNED NOT NULL,
  `eliminada` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `pagina_imagen`
--

INSERT INTO `pagina_imagen` (`id_pagina_imagen`, `id_pagina`, `id_imagen`, `orden`, `eliminada`) VALUES
(1, 1, 1, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagina_tipo`
--

CREATE TABLE `pagina_tipo` (
  `id_pagina_tipo` smallint(5) UNSIGNED NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `pagina_tipo`
--

INSERT INTO `pagina_tipo` (`id_pagina_tipo`, `nombre`, `eliminado`) VALUES
(1, 'Acerca de la empresa', 0),
(2, 'Contacto', 0),
(3, 'Información comercial', 0),
(4, 'Tiendas', 0),
(5, 'Promociones', 0),
(6, 'Eventos', 0),
(7, 'Eventos anteriores', 0),
(8, 'Plano', 0),
(9, 'Plano tiendas', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil`
--

CREATE TABLE `perfil` (
  `id_perfil` tinyint(3) UNSIGNED NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `perfil`
--

INSERT INTO `perfil` (`id_perfil`, `nombre`) VALUES
(1, 'Administrador'),
(2, 'Arrendatario'),
(3, 'Admin usuarios');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plano`
--

CREATE TABLE `plano` (
  `id_plano` int(10) UNSIGNED NOT NULL,
  `id_sitio` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `imagen` varchar(100) DEFAULT NULL,
  `pie_imagen` varchar(255) DEFAULT NULL,
  `fecha_actualizacion` varchar(255) DEFAULT NULL,
  `orden` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `eliminado` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `plano`
--

INSERT INTO `plano` (`id_plano`, `id_sitio`, `nombre`, `imagen`, `pie_imagen`, `fecha_actualizacion`, `orden`, `eliminado`) VALUES
(1, 2, 'Piso 1', 'balmaceda_piso_1.svg', NULL, NULL, 0, 0),
(2, 2, 'Piso 2', 'balmaceda_piso_2.svg', NULL, NULL, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `portada_caluga`
--

CREATE TABLE `portada_caluga` (
  `id_portada_caluga` int(10) UNSIGNED NOT NULL,
  `id_sitio` int(10) UNSIGNED NOT NULL,
  `imagen` varchar(150) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `orden` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `eliminado` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `panel` tinyint(3) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `portada_caluga`
--

INSERT INTO `portada_caluga` (`id_portada_caluga`, `id_sitio`, `imagen`, `link`, `orden`, `eliminado`, `panel`) VALUES
(1, 1, 'paseo_san_fernando.jpg', 'http://www.paseoschile.cl/pagina/informacion_paseo_san_fernando', 3, 0, 0),
(2, 1, 'paseo_montt.jpg', 'http://www.paseoschile.cl/pagina/informacion_paseo_montt', 4, 0, 0),
(3, 1, 'paseo_balmaceda.jpg', 'http://www.paseoschile.cl/pagina/informacion_paseo_balmaceda', 1, 0, 0),
(4, 1, 'paseo_manuel_rodriguez.jpg', 'http://www.paseoschile.cl/pagina/informacion_paseo_manuel_rodriguez', 6, 0, 0),
(5, 1, 'paseo_la_portada.jpg', 'http://www.paseoschile.cl/pagina/informacion_paseo_la_portada', 2, 0, 0),
(6, 1, 'paseo_machali.jpg', 'http://www.paseoschile.cl/pagina/informacion_paseo_machali', 7, 1, 0),
(7, 1, 'paseo_las_rastras.jpg', 'http://www.paseoschile.cl/pagina/informacion_paseo_las_rastras', 5, 0, 0),
(8, 2, 'sturbucks_fin.jpg', NULL, 2, 0, 1),
(9, 2, 'automac_2.jpg', NULL, 1, 0, 1),
(10, 2, 'galeria.jpg', 'http://balmaceda.extenddigital.cl/pagina/eventos_anteriores_paseo_balmaceda', 4, 0, 0),
(11, 2, 'lo_que_se-viene.jpg', 'http://balmaceda.extenddigital.cl/pagina/promociones_paseo_balmaceda', 5, 0, 0),
(12, 2, 'Noticias.jpg', 'http://balmaceda.extenddigital.cl/pagina/eventos_paseo_balmaceda', 3, 0, 0),
(13, 2, 'caluga_juan_maestro.jpg', NULL, 3, 0, 1),
(14, 2, 'banner_caluga.jpg', NULL, 4, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `portada_slide`
--

CREATE TABLE `portada_slide` (
  `id_portada_slide` int(10) UNSIGNED NOT NULL,
  `id_sitio` int(10) UNSIGNED NOT NULL,
  `imagen` varchar(150) DEFAULT NULL,
  `imagen_movil` varchar(150) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `orden` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `eliminado` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `comentario` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `portada_slide`
--

INSERT INTO `portada_slide` (`id_portada_slide`, `id_sitio`, `imagen`, `imagen_movil`, `link`, `orden`, `eliminado`, `comentario`) VALUES
(1, 1, '1.jpg', 'banner_vertical2.jpg', 'http://www.paseoschile.cl/pagina/informacion_paseo_balmaceda', 1, 0, ''),
(2, 2, 'banner_tricot.jpg', 'slide_1.jpg', NULL, 1, 1, ''),
(3, 2, 'banner_estrellaaplmina.jpg', 'slide_2.jpg', NULL, 2, 1, ''),
(4, 1, '2.jpg', 'banner_vertical1.jpg', 'http://www.paseoschile.cl/pagina/informacion_paseo_balmaceda', 2, 0, ''),
(5, 1, 'header3.jpg', 'banner_vertical3.jpg', NULL, 3, 0, ''),
(6, 2, 'banner_cocha.jpg', NULL, NULL, 3, 1, ''),
(7, 2, 'prueba_1.jpg', NULL, NULL, 1, 0, 'PASEO BALMACEDA TE INVITA\r\nA CONOCER LA NUEVA TIENDA\r\nDE AQUA'),
(8, 2, 'prueba_1.jpg', NULL, NULL, 2, 0, 'PASEO BALMACEDA\r\nTIENDA DE PIZZA\r\nNIVEL 2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promocion`
--

CREATE TABLE `promocion` (
  `id_promocion` int(10) UNSIGNED NOT NULL,
  `id_sitio` int(10) UNSIGNED NOT NULL,
  `foto1` varchar(150) DEFAULT NULL,
  `foto2` varchar(150) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `orden` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `eliminado` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `fecha_creacion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `promocion`
--

INSERT INTO `promocion` (`id_promocion`, `id_sitio`, `foto1`, `foto2`, `link`, `orden`, `eliminado`, `fecha_creacion`) VALUES
(1, 2, 'inauguracion1.jpg', 'inauguracion1-1.jpg', NULL, 2, 0, '2017-05-12 10:52:24'),
(2, 2, 'inauguracion2.jpg', 'inaururacion2_2.jpg', NULL, 1, 0, '2017-05-12 10:52:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `red_social`
--

CREATE TABLE `red_social` (
  `id_red_social` int(10) UNSIGNED NOT NULL,
  `id_sitio` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `icono` varchar(50) DEFAULT NULL,
  `orden` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `eliminado` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `red_social`
--

INSERT INTO `red_social` (`id_red_social`, `id_sitio`, `nombre`, `link`, `icono`, `orden`, `eliminado`) VALUES
(1, 1, 'Facebook', 'http://www.facebook.com', 'facebook.svg', 2, 1),
(2, 1, 'Instagram', 'http://www.instagram.com', 'instagram.svg', 1, 1),
(3, 2, 'Facebook', 'http://www.facebook.com', 'facebook.svg', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio`
--

CREATE TABLE `servicio` (
  `id_servicio` smallint(5) UNSIGNED NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `icono` varchar(50) NOT NULL,
  `orden` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `servicio`
--

INSERT INTO `servicio` (`id_servicio`, `nombre`, `icono`, `orden`) VALUES
(1, 'Supermercado', 'supermercado.svg', 1),
(2, 'Tienda de mejoramiento del hogar', 'tienda_mejoramiento_del_hogar.svg', 2),
(3, 'Cajero', 'cajeros.svg', 3),
(4, 'Farmacia', 'farmacia.svg', 4),
(5, 'Gimnasio', 'gimnasio.svg', 5),
(6, 'Restaurantes', 'patio_de_comida.svg', 6),
(7, 'Mini bodegas', 'bodega.svg', 9),
(8, 'Estacionamientos', 'estacionamientos.svg', 10),
(9, 'Oficinas', 'oficina.svg', 7),
(10, 'Zona de juegos', 'zona_de_juego.svg', 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sitio`
--

CREATE TABLE `sitio` (
  `id_sitio` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `link` varchar(50) DEFAULT NULL,
  `carpeta` varchar(50) NOT NULL,
  `logo` varchar(50) DEFAULT NULL,
  `informacion` text,
  `direccion` varchar(255) DEFAULT NULL,
  `terreno` varchar(10) DEFAULT NULL,
  `construida` varchar(10) DEFAULT NULL,
  `arrendable` varchar(10) DEFAULT NULL,
  `estacionamientos` int(11) DEFAULT NULL,
  `correo` varchar(150) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `google_map` text,
  `horario` text,
  `como_llegar` text,
  `fecha_apertura` varchar(150) DEFAULT NULL,
  `render1` varchar(50) DEFAULT NULL,
  `pie_foto1` varchar(255) DEFAULT NULL,
  `render2` varchar(50) DEFAULT NULL,
  `pie_foto2` varchar(255) DEFAULT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `sitio`
--

INSERT INTO `sitio` (`id_sitio`, `nombre`, `link`, `carpeta`, `logo`, `informacion`, `direccion`, `terreno`, `construida`, `arrendable`, `estacionamientos`, `correo`, `telefono`, `google_map`, `horario`, `como_llegar`, `fecha_apertura`, `render1`, `pie_foto1`, `render2`, `pie_foto2`, `eliminado`) VALUES
(1, 'Paseo', 'http://www.paseos.cl/', 'paseo', 'logo_color.svg', NULL, 'Alonso de Córdova 2.700 of. 24 <br>Vitacura - Santiago, Chile', NULL, NULL, NULL, NULL, 'contacto@neorentas.cl', '(56 2) 2464 3591', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3330.8138464209387!2d-70.59598148435282!3d-33.402020480787755!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9662cf250de7f841%3A0xf9f838a245f24075!2sAlonso+de+C%C3%B3rdova%2C+Vitacura%2C+Regi%C3%B3n+Metropolitana!5e0!3m2!1sen!2scl!4v1490040817463\" width=\"600\" height=\"450\" frameborder=\"0\" style=\"border:0\" allowfullscreen></iframe>', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(2, 'Paseo Balmaceda', 'http://www.paseobalmaceda.cl/', 'paseo_balmaceda', 'paseo_balmaceda.svg', '<p>Este nuevo subcentro est&aacute; emplazado en Av. Balmaceda, entre el centro de la ciudad y Av. Cuatro Esquinas. Con excelentes accesos, su ubicaci&oacute;n es un aporte para eliminar la congesti&oacute;n del centro de la ciudad. Con m&aacute;s de 36 mil m2 construidos y c&oacute;modos estacionamientos, incluye una tienda de mejoramiento del hogar Homecenter, un supermercado Tottus, locales comerciales, gimnasio, restaurantes, zona de juegos, oficinas, mini bodegas y una placa de servicios para peque&ntilde;as empresas.</p>', 'La Serena | Región de Coquimbo<br>Av. Balmaceda 2885', '60 mil', '37 mil', '32 mil', 590, 'contacto@neorentas.cl', '(56 2) 2374 1364', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1728.9286104832013!2d-71.25908024189306!3d-29.926012395481386!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9691ca4427e6a043%3A0x8a6813e31e72643f!2sPaseo+Balmaceda!5e0!3m2!1sen!2scl!4v1515766682286\" width=\"600\" height=\"450\" frameborder=\"0\" style=\"border:0\" allowfullscreen></iframe>', 'Lunes a domingo<br>de 10:00 a 22:00 hrs.', 'https://www.google.com/maps/dir//-29.9260124,-71.2579859/@-29.926012,-71.257986,16z?hl=en-US', NULL, 'render_balmaceda_1.jpg', NULL, 'render_balmaceda_2.jpg', NULL, 0),
(3, 'Paseo Las Rastras', '/', 'paseo_las_rastras', 'paseo_las_rastras.svg', '<p>Est&aacute; ubicado estrat&eacute;gicamente en el sector nororiente de Talca, en una zona de gran crecimiento inmobiliario residencial. Adem&aacute;s de tener un excelente acceso al centro de la ciudad, Paseo Las Rastras cuenta con un supermercado Lider, farmacia Cruz Verde, locales menores, caf&eacute;s, restaurantes y zona de juegos.</p>', 'Talca | Región del Maule<br>Las Rastras 1800', '49 mil', '4 mil', '3 mil', 148, 'contacto@neorentas.cl', NULL, '<iframe src=\"https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d6501.488148363303!2d-71.6132944!3d-35.4363693!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9665c72d1b3385dd%3A0x6ae7f4d03e7a9138!2sCamino+Las+Rastras%2C+Talca%2C+VII+Regi%C3%B3n!5e0!3m2!1sen!2scl!4v1515766992723\" width=\"600\" height=\"450\" frameborder=\"0\" style=\"border:0\" allowfullscreen></iframe>', NULL, 'https://www.google.cl/maps/dir//Camino+Las+Rastras,+Talca,+VII+Regi%C3%B3n/@-35.4356318,-71.6145357,16z/data=!4m9!4m8!1m0!1m5!1m1!1s0x9665c72d1b3385dd:0x6ae7f4d03e7a9138!2m2!1d-71.6101583!2d-35.4356319!3e0', NULL, 'Talca 1.jpg', NULL, 'Talca 2.jpg', NULL, 0),
(4, 'Paseo La Portada', '/', 'paseo_la_portada', 'paseo_la_portada.svg', '<p>Ante todo, una excelente ubicaci&oacute;n. Paseo La Portada estar&aacute; emplazado en el sector norte de Antofagasta en Av. Pedro Aguirre Cerda esquina Caparrosa, frente a Hiper Lider y a un costado del parque Juan L&oacute;pez, muy cerca de varios proyectos residenciales y de desarrollo urbano. Con m&aacute;s de 6 mil m2 de oficinas y servicios de apoyo, el centro comercial incluir&aacute; una tienda de mejoramiento del hogar Homecenter, restaurantes, zona de juegos, una zona comercial de locales menores, oficinas, mini bodegas y estacionamientos.</p>', 'Antofagasta | Región de Antofagasta<br>Av. Pedro Aguirre Cerda 10578', '25 mil', '55 mil', '32 mil', 631, 'contacto@neorentas.cl', NULL, '<iframe src=\"https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3657.075253234294!2d-70.391794!3d-23.5657406!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x96ae2a51f83a5d9b%3A0xdc28b3087ab0a833!2sMall+Paseo+Antofagasta!5e0!3m2!1sen!2scl!4v1515767020481\" width=\"600\" height=\"450\" frameborder=\"0\" style=\"border:0\" allowfullscreen></iframe>', NULL, NULL, 'segundo semestre 2019.', 'Antofagasta 1.jpg', NULL, 'Antofagasta 2.jpg', NULL, 0),
(5, 'Paseo San Fernando', '/', 'paseo_san_fernando', 'paseo_san_fernando.svg', '<p>A minutos del centro de Copiap&oacute;, aporta un nuevo polo comercial en un sector de gran desarrollo residencial. Paseo San Fernando es un lugar ideal para tomarse un tiempo y disfrutar de restaurantes, caf&eacute;s, gimnasio Pacific Fitness, un &aacute;rea de servicios y zona de juegos. Este espacio comercial tambi&eacute;n incluye un supermercado Tottus y una tienda de mejoramiento del hogar Homecenter para tu comodidad.</p>', 'Copiapó | Región de Atacama<br>Av. Los Carrera 4723', '30 mil', '20 mil', '16 mil', 312, 'contacto@neorentas.cl', NULL, '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3542.291950661338!2d-70.29656808494465!3d-27.39781988292112!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x969804e0cb4e865d%3A0xeccc84151df4816!2sPaseo+San+Fernando%2C+Copiapo!5e0!3m2!1sen!2scl!4v1515767055577\" width=\"600\" height=\"450\" frameborder=\"0\" style=\"border:0\" allowfullscreen></iframe>', NULL, NULL, NULL, 'Copiapo 1.jpg', NULL, 'Copiapo 2.jpg', NULL, 0),
(6, 'Paseo Montt', '/', 'paseo_montt', 'paseo_montt.svg', '<p>Con una excelente visibilidad y gran conectividad, Paseo Montt es un lugar ideal para reunirse y pasarlo bien. El centro comercial comunal cuenta con diversos locales, entre ellos, un supermercado Santa Isabel, una tienda Easy, una multitienda Johnson, un gimnasio Pacific Fitness y una farmacia Salcobrand, adem&aacute;s de una zona de juegos. Su cercan&iacute;a a la estaci&oacute;n de transporte y buses interurbanos de Coronel lo hace un polo de encuentro c&oacute;modo y atractivo en medio de la ciudad.</p>', 'Coronel | Región del Biobío<br>Av. Manuel Montt 1600', '23 mil', '19 mil', '13 mil', 340, 'contacto@neorentas.cl', NULL, '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3186.5125946886583!2d-73.16445608470458!3d-36.997565879908386!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9669c6bf3348a9e1%3A0x750f77fc8d6d4a5b!2sPaseo+Montt+Coronel!5e0!3m2!1sen!2scl!4v1515767079845\" width=\"600\" height=\"450\" frameborder=\"0\" style=\"border:0\" allowfullscreen></iframe>', NULL, NULL, NULL, 'Coronel 1.jpg', NULL, 'Coronel 2.jpg', NULL, 0),
(7, 'Paseo Manuel Rodríguez', '/', 'paseo_manuel_rodriguez', 'paseo_manuel_rodriguez.svg', '<p>En pleno centro de la ciudad, este centro comercial se integra armónicamente al Parque Manuel Rodríguez y a los departamentos y oficinas que lo circundan. Parte de un proyecto mayor, que en 2015 obtuvo el Premio Aporte Urbano (PAU), Paseo Manuel Rodríguez ofrece una amplia gama de productos y servicios de alto estándar y seguridad para cubrir todas las necesidades incluyendo restaurantes, cajero y estacionamientos.</p>', 'Calama | Región de Antofagasta<br>Bartolomé Vivar 1536', '5 mil', NULL, 'mil', 60, 'contacto@neorentas.cl', NULL, '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3687.0865489933867!2d-68.92736678504244!3d-22.463381685239135!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x96ac09c939120679%3A0x75d4749b85a1501!2sPaseo+Manuel+Rodr%C3%ADguez!5e0!3m2!1sen!2scl!4v1515767100930\" width=\"600\" height=\"450\" frameborder=\"0\" style=\"border:0\" allowfullscreen></iframe>', NULL, NULL, NULL, 'Calama 1.png', NULL, NULL, NULL, 0),
(8, 'Paseo Machalí', '/', 'paseo_machali', 'paseo_machali.svg', '<p>Por estar ubicado en la principal v&iacute;a de la comuna, Paseo Machal&iacute; cuenta con excelentes accesos: 120 metros de frente, que adem&aacute;s aseguran una muy buena visibilidad. El centro comercial entrega una oferta completa de bienes y servicios, que incluyen un supermercado L&iacute;der Express, una farmacia Cruz Verde y varios locales menores.</p>', 'Av. San Juan 2184<br>Machalí | Región de O’Higgins', '17.850', '3.418', '3.418', 147, 'contacto@neorentas.cl', NULL, '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3301.1399344712363!2d-70.66727668433674!3d-34.16833978057456!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x966345289f1d77a1%3A0x56364c79120fce82!2sAv+San+Juan+2184%2C+Machal%C3%AD%2C+VI+Regi%C3%B3n!5e0!3m2!1sen!2scl!4v1491488171035\" width=\"600\" height=\"450\" frameborder=\"0\" style=\"border:0\" allowfullscreen></iframe>', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sitio_servicio`
--

CREATE TABLE `sitio_servicio` (
  `id_sitio_servicio` int(10) UNSIGNED NOT NULL,
  `id_sitio` int(10) UNSIGNED NOT NULL,
  `id_servicio` smallint(5) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `sitio_servicio`
--

INSERT INTO `sitio_servicio` (`id_sitio_servicio`, `id_sitio`, `id_servicio`) VALUES
(1, 2, 8),
(2, 2, 1),
(3, 2, 4),
(4, 2, 3),
(5, 2, 2),
(6, 2, 5),
(7, 2, 6),
(8, 2, 7),
(9, 3, 1),
(11, 3, 3),
(12, 3, 4),
(14, 3, 6),
(16, 3, 8),
(18, 4, 2),
(19, 4, 3),
(20, 4, 4),
(21, 4, 5),
(22, 4, 6),
(23, 4, 7),
(24, 4, 8),
(25, 5, 1),
(26, 5, 2),
(27, 5, 3),
(28, 5, 4),
(29, 5, 5),
(30, 5, 6),
(32, 5, 8),
(33, 6, 1),
(34, 6, 2),
(35, 6, 3),
(36, 6, 4),
(37, 6, 5),
(38, 6, 6),
(40, 6, 8),
(43, 7, 3),
(46, 7, 6),
(48, 7, 8),
(49, 8, 1),
(50, 8, 2),
(51, 8, 3),
(52, 8, 4),
(53, 8, 5),
(54, 8, 6),
(55, 8, 7),
(56, 8, 8),
(57, 2, 9),
(61, 8, 9),
(64, 3, 10),
(65, 4, 10),
(66, 5, 10),
(67, 6, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `terminacion`
--

CREATE TABLE `terminacion` (
  `id_terminacion` int(10) UNSIGNED NOT NULL,
  `id_sitio` int(10) UNSIGNED NOT NULL,
  `numero` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `descripcion` varchar(100) NOT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `terminacion`
--

INSERT INTO `terminacion` (`id_terminacion`, `id_sitio`, `numero`, `descripcion`, `eliminado`) VALUES
(1, 2, 1, 'Potencia eléctrica asignada', 0),
(2, 2, 2, 'Ducto proyectado agua potable', 0),
(3, 2, 3, 'Ducto proyectado alcantarillado', 0),
(4, 2, 4, 'Ducto ventilación', 0),
(5, 2, 5, 'Conexión a cámara desgrasadora', 0),
(6, 2, 6, 'Conexión a gas', 0),
(7, 2, 7, 'Arranque sensor de humo', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `id_sitio` int(10) UNSIGNED NOT NULL,
  `email` varchar(150) NOT NULL,
  `contrasena` varchar(65) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `rut` varchar(15) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `verificado` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `fecha_creacion` datetime NOT NULL,
  `fecha_envio_correo` datetime DEFAULT NULL,
  `fecha_verificacion` datetime DEFAULT NULL,
  `id_perfil` tinyint(3) UNSIGNED NOT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `codigo` varchar(65) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `id_sitio`, `email`, `contrasena`, `nombre`, `apellido`, `rut`, `telefono`, `verificado`, `fecha_creacion`, `fecha_envio_correo`, `fecha_verificacion`, `id_perfil`, `eliminado`, `codigo`) VALUES
(1, 0, 'admin@neorentas.cl', '$2y$10$8mQOczlGQRIpIfd70I9K7et2Ub0.vDyAs5PCO/jHBvvrqL7MBZ.jG', 'Administrador', '', '', '', 1, '2017-01-18 09:58:05', NULL, '2017-03-29 13:49:20', 1, 0, NULL),
(4, 2, 'fnunez@extend.cl', '$2y$10$LsT1PhKVi2e/66FfIXxAdOkAMHLpUqGOGMr.KRIpUG64Q99nN9M6y', 'Francisca', 'Núñez', NULL, NULL, 1, '2017-08-23 13:01:34', '2018-03-01 20:19:51', '2017-10-30 18:53:26', 2, 0, NULL),
(6, 1, 'vhc@neorentas.cl', '$2y$10$3hx.KdgCP.cQYUOyHtkeyeTmWwh1SYwSqRqD9XACYCPc/YXSVrgQy', 'Victoria', 'Horning', NULL, NULL, 1, '2017-10-19 09:52:24', '2017-10-19 09:52:24', '2017-10-19 09:58:55', 3, 0, NULL),
(7, 1, 'asomoza@extend.cl', '$2y$10$JrzQfj9xw29Rkttc5mu7iuZrHX40Uq.W1JvDUPb9KGyhd9v6FIezq', 'Alvaro', 'Somoza', NULL, NULL, 1, '2017-10-19 09:56:53', '2018-01-24 12:46:21', '2017-10-19 09:58:09', 2, 0, NULL),
(9, 2, 'randrews@extend.cl', '$2y$10$ACCrZ7HfW0xt/dM6yNz/hOHh8PZqPOe0f6zFdg48CKpvhdyDiyq/O', 'Ricardo', 'Andrews', NULL, NULL, 0, '2017-10-25 18:17:07', '2017-10-25 18:17:08', NULL, 2, 0, '430C3626B8-A10743F36C8CA40845FC3FD5BBF7B809'),
(10, 1, 'msantibanez@extend.cl', '$2y$10$FWB.6lVLrkTB6BKpqdvFTOppEmoFRqth1Uzgjl0gwgAWyKF7F/Izq', 'María José', 'Santibañez', NULL, NULL, 0, '2017-10-26 12:59:47', '2017-10-26 12:59:48', NULL, 2, 0, '7F975A56C7-15557AFFFAB40D6432D40CD55946315F'),
(11, 1, 'imanriquez@extend.cl', '$2y$10$o211gxsGLNG73QLHf89AeuSSgxqQB8KEUMxjFwyEtHiY0HSa8JPZy', 'Ignacia', 'Manriquez', NULL, NULL, 1, '2017-10-26 13:00:19', '2018-01-24 12:28:49', '2018-01-24 12:28:58', 2, 0, NULL),
(12, 1, 'evielma@xtend.cl', '$2y$10$p1gWYI5Xv8hmK9ewHoOf/O3x3loHf51jlpu2TPG3o0RhzlR9rd8Ee', 'Elvis', 'Vielma', NULL, NULL, 0, '2017-10-26 13:01:13', '2017-10-26 13:01:13', NULL, 2, 0, '285AB9448D-7B59DFBC8B2BEBF7E3A05BF9B6E9F93B'),
(13, 1, 'thundt@extend.cl', '$2y$10$Y0Y31dVo9RBfZLKs71ZQQOAxQY9KnxeinZ6Ei0wcMcFeu431BBDwC', 'Trinidad', 'von der Hundt', NULL, NULL, 0, '2017-10-26 13:05:37', '2017-10-26 13:05:38', NULL, 2, 0, '7BB060764A-A20F2881D7EBB4000775F4BF77DCB18B'),
(14, 1, 'nyanez@extend.cl', '$2y$10$jIQkz5IlNxznW5wDttNYROC8OQfH3pn8Z31hbE1RCMwMCxh6a79RS', 'Natalia', 'Yáñez', NULL, NULL, 0, '2017-10-26 13:06:48', '2017-10-26 13:06:48', NULL, 2, 0, '54072F485C-06E88CC80F2F2C296B0859570F5FD457'),
(18, 2, 'cristian.fuentes4200@gmail.com', '$2y$10$B3o7zb5NESREkuq5O1rLr.nNipa4a.HEU0yF/hjSleT7k9MsQi9My', 'Cristian', 'Fuentes', NULL, NULL, 1, '2017-11-02 18:59:31', '2017-11-22 21:34:17', '2017-11-25 05:27:18', 2, 0, NULL),
(19, 2, 'igor.jimenez@outlook.cl', '$2y$10$qfqTBTlyc/Des.uP.0ne1ugfyNzpybkjhg283Czi1E5MmK8z4t4JC', 'Igor', 'Jiménez', NULL, NULL, 1, '2017-11-04 19:26:56', '2017-11-04 19:26:56', '2017-11-04 19:27:36', 2, 0, NULL),
(20, 2, 'chamaleon77@inboxbear.com', '$2y$10$7rIS5S9hcVeYHWPeZSLEaOBlgq.YKpTGibsEqsbh7nKCXrOiy0cU2', 'Chama', 'Leon', NULL, NULL, 1, '2017-11-05 09:39:22', '2017-11-05 09:39:22', '2017-11-05 09:39:37', 2, 0, NULL),
(21, 2, 'angelotapiaramos@gmail.com', '$2y$10$URDsUvdoOg1ZSCor5FQosuI95eOd3KlaNzRVgW5cWsYG1gMRJ1WUy', 'angelo', 'tapia', NULL, NULL, 0, '2017-11-12 20:15:57', '2017-11-12 20:15:57', NULL, 2, 0, '02B1BE0D48-53FFB4FD63B8C807B9C18812061E19E4'),
(22, 1, 'fbahamonde@extend.cl', '$2y$10$yLTA96xiIUTYg3jlD.tgR.gaUY6HGEhztsyiSptnn26I85DeD01qm', 'Florencia', 'Bahamonde', NULL, NULL, 1, '2017-11-20 11:49:27', '2017-11-20 11:49:27', '2017-11-20 11:50:10', 2, 0, NULL),
(23, 2, 'mikhail.cicala@outlook.com', '$2y$10$p8J2BJthYW5nFvh9SdtUy.RgKcXgS1Mu78crXChnvzo9cnF4pHeaa', 'Mikhail', 'Cicala', NULL, NULL, 1, '2017-11-21 07:41:05', '2017-11-21 07:41:05', '2017-11-21 07:41:56', 2, 0, NULL),
(24, 2, 'balma@inboxbear.com', '$2y$10$oF9niIgiKfQ2NioCq/EHseJLA8ESim3oZnmaulSsLJWzAzPmx47j6', 'Balma', 'Ceda', NULL, NULL, 1, '2017-12-28 14:26:06', '2017-12-28 14:26:06', '2017-12-28 14:26:23', 2, 0, NULL),
(25, 1, 'sgf@neorentas.cl', '$2y$10$7vy8lJ6fPolvc2iToIkeuOZGUkTeSOiwg9IfpJTKZbTYwxcY2bdWm', 'Silvana', 'Galarce', NULL, NULL, 1, '2018-01-18 10:03:18', '2018-01-18 10:03:19', '2018-01-18 10:03:32', 2, 0, NULL),
(26, 2, 'jinfante@extend.cl', '$2y$10$BqLrhulboBYopHIVRLyiZ.Rg/Py3k4gyGI3SQaM6P8tX.yjebbtJO', 'Javier', 'Infante', NULL, NULL, 1, '2018-01-31 13:03:04', '2018-01-31 13:03:05', '2018-01-31 13:03:29', 2, 0, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `evento`
--
ALTER TABLE `evento`
  ADD PRIMARY KEY (`id_evento`);

--
-- Indices de la tabla `galeria`
--
ALTER TABLE `galeria`
  ADD PRIMARY KEY (`id_galeria`),
  ADD KEY `IXFK_galeria_sitio` (`id_sitio`) USING BTREE;

--
-- Indices de la tabla `galeria_item`
--
ALTER TABLE `galeria_item`
  ADD PRIMARY KEY (`id_galeria_item`),
  ADD KEY `IXFK_galeria_item_galeria` (`id_galeria`) USING BTREE;

--
-- Indices de la tabla `imagen`
--
ALTER TABLE `imagen`
  ADD PRIMARY KEY (`id_imagen`);

--
-- Indices de la tabla `local`
--
ALTER TABLE `local`
  ADD PRIMARY KEY (`id_local`) USING BTREE,
  ADD KEY `IXFK_local_plano` (`id_plano`) USING BTREE;

--
-- Indices de la tabla `local_categoria`
--
ALTER TABLE `local_categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `local_punto`
--
ALTER TABLE `local_punto`
  ADD PRIMARY KEY (`id_local_punto`) USING BTREE,
  ADD KEY `IXFK_local_punto_local` (`id_local`) USING BTREE;

--
-- Indices de la tabla `local_terminacion`
--
ALTER TABLE `local_terminacion`
  ADD PRIMARY KEY (`id_local_terminacion`),
  ADD KEY `IXFK_local_terminacion_local` (`id_local`) USING BTREE,
  ADD KEY `IXFK_local_terminacion_terminacion` (`id_terminacion`) USING BTREE;

--
-- Indices de la tabla `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id_log`);

--
-- Indices de la tabla `marca`
--
ALTER TABLE `marca`
  ADD PRIMARY KEY (`id_marca`),
  ADD KEY `IXFK_marca_sitio` (`id_sitio`) USING BTREE;

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`),
  ADD KEY `IXFK_menu_sitio` (`id_sitio`) USING BTREE;

--
-- Indices de la tabla `pagina`
--
ALTER TABLE `pagina`
  ADD PRIMARY KEY (`id_pagina`),
  ADD UNIQUE KEY `UQ_alias` (`alias`) USING BTREE,
  ADD KEY `IXFK_pagina_pagina_tipo` (`id_pagina_tipo`) USING BTREE,
  ADD KEY `IXFK_pagina_sitio` (`id_sitio`) USING BTREE;

--
-- Indices de la tabla `pagina_imagen`
--
ALTER TABLE `pagina_imagen`
  ADD PRIMARY KEY (`id_pagina_imagen`),
  ADD KEY `IXFK_pagina_imagen_imagen` (`id_imagen`) USING BTREE,
  ADD KEY `IXFK_pagina_imagen_pagina` (`id_pagina`) USING BTREE;

--
-- Indices de la tabla `pagina_tipo`
--
ALTER TABLE `pagina_tipo`
  ADD PRIMARY KEY (`id_pagina_tipo`);

--
-- Indices de la tabla `perfil`
--
ALTER TABLE `perfil`
  ADD PRIMARY KEY (`id_perfil`);

--
-- Indices de la tabla `plano`
--
ALTER TABLE `plano`
  ADD PRIMARY KEY (`id_plano`),
  ADD KEY `IXFK_plano_sitio` (`id_sitio`) USING BTREE;

--
-- Indices de la tabla `portada_caluga`
--
ALTER TABLE `portada_caluga`
  ADD PRIMARY KEY (`id_portada_caluga`),
  ADD KEY `IXFK_portada_caluga_sitio` (`id_sitio`) USING BTREE;

--
-- Indices de la tabla `portada_slide`
--
ALTER TABLE `portada_slide`
  ADD PRIMARY KEY (`id_portada_slide`),
  ADD KEY `IXFK_portada_slide_sitio` (`id_sitio`) USING BTREE;

--
-- Indices de la tabla `promocion`
--
ALTER TABLE `promocion`
  ADD PRIMARY KEY (`id_promocion`),
  ADD KEY `IXFK_promocion_sitio` (`id_sitio`) USING BTREE;

--
-- Indices de la tabla `red_social`
--
ALTER TABLE `red_social`
  ADD PRIMARY KEY (`id_red_social`),
  ADD KEY `IXFK_red_social_sitio` (`id_sitio`) USING BTREE;

--
-- Indices de la tabla `servicio`
--
ALTER TABLE `servicio`
  ADD PRIMARY KEY (`id_servicio`);

--
-- Indices de la tabla `sitio`
--
ALTER TABLE `sitio`
  ADD PRIMARY KEY (`id_sitio`);

--
-- Indices de la tabla `sitio_servicio`
--
ALTER TABLE `sitio_servicio`
  ADD PRIMARY KEY (`id_sitio_servicio`),
  ADD KEY `IXFK_sitio_servicio_servicio` (`id_servicio`) USING BTREE,
  ADD KEY `IXFK_sitio_servicio_sitio` (`id_sitio`) USING BTREE;

--
-- Indices de la tabla `terminacion`
--
ALTER TABLE `terminacion`
  ADD PRIMARY KEY (`id_terminacion`),
  ADD KEY `IXFK_terminaciones_sitio` (`id_sitio`) USING BTREE;

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `UQ_email` (`email`) USING BTREE,
  ADD KEY `IXFK_usuario_perfil` (`id_perfil`) USING BTREE;

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `evento`
--
ALTER TABLE `evento`
  MODIFY `id_evento` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `galeria`
--
ALTER TABLE `galeria`
  MODIFY `id_galeria` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `galeria_item`
--
ALTER TABLE `galeria_item`
  MODIFY `id_galeria_item` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- AUTO_INCREMENT de la tabla `imagen`
--
ALTER TABLE `imagen`
  MODIFY `id_imagen` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `local`
--
ALTER TABLE `local`
  MODIFY `id_local` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT de la tabla `local_categoria`
--
ALTER TABLE `local_categoria`
  MODIFY `id_categoria` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `local_punto`
--
ALTER TABLE `local_punto`
  MODIFY `id_local_punto` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=361;

--
-- AUTO_INCREMENT de la tabla `local_terminacion`
--
ALTER TABLE `local_terminacion`
  MODIFY `id_local_terminacion` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=330;

--
-- AUTO_INCREMENT de la tabla `log`
--
ALTER TABLE `log`
  MODIFY `id_log` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `marca`
--
ALTER TABLE `marca`
  MODIFY `id_marca` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `pagina`
--
ALTER TABLE `pagina`
  MODIFY `id_pagina` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `pagina_imagen`
--
ALTER TABLE `pagina_imagen`
  MODIFY `id_pagina_imagen` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `pagina_tipo`
--
ALTER TABLE `pagina_tipo`
  MODIFY `id_pagina_tipo` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `perfil`
--
ALTER TABLE `perfil`
  MODIFY `id_perfil` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `plano`
--
ALTER TABLE `plano`
  MODIFY `id_plano` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `portada_caluga`
--
ALTER TABLE `portada_caluga`
  MODIFY `id_portada_caluga` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `portada_slide`
--
ALTER TABLE `portada_slide`
  MODIFY `id_portada_slide` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `promocion`
--
ALTER TABLE `promocion`
  MODIFY `id_promocion` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `red_social`
--
ALTER TABLE `red_social`
  MODIFY `id_red_social` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `servicio`
--
ALTER TABLE `servicio`
  MODIFY `id_servicio` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `sitio`
--
ALTER TABLE `sitio`
  MODIFY `id_sitio` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `sitio_servicio`
--
ALTER TABLE `sitio_servicio`
  MODIFY `id_sitio_servicio` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT de la tabla `terminacion`
--
ALTER TABLE `terminacion`
  MODIFY `id_terminacion` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `galeria`
--
ALTER TABLE `galeria`
  ADD CONSTRAINT `galeria_ibfk_1` FOREIGN KEY (`id_sitio`) REFERENCES `sitio` (`id_sitio`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `galeria_item`
--
ALTER TABLE `galeria_item`
  ADD CONSTRAINT `galeria_item_ibfk_1` FOREIGN KEY (`id_galeria`) REFERENCES `galeria` (`id_galeria`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `local`
--
ALTER TABLE `local`
  ADD CONSTRAINT `local_ibfk_1` FOREIGN KEY (`id_plano`) REFERENCES `plano` (`id_plano`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `local_punto`
--
ALTER TABLE `local_punto`
  ADD CONSTRAINT `local_punto_ibfk_1` FOREIGN KEY (`id_local`) REFERENCES `local` (`id_local`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `marca`
--
ALTER TABLE `marca`
  ADD CONSTRAINT `marca_ibfk_1` FOREIGN KEY (`id_sitio`) REFERENCES `sitio` (`id_sitio`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
