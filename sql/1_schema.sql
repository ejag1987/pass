SET FOREIGN_KEY_CHECKS=0 
;

CREATE DATABASE neorentas_paseo
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE neorentas_paseo;

/* Create Tables */

CREATE TABLE `evento`
(
	`id_evento` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`id_sitio` INT UNSIGNED NOT NULL,
	`foto` VARCHAR(150) 	 NULL,
	`titulo` VARCHAR(100) 	 NULL,
	`descripcion` VARCHAR(255) 	 NULL,
	`fecha_evento` DATE 	 NULL,
	`horario` VARCHAR(50) 	 NULL,
	`orden` INT UNSIGNED NOT NULL DEFAULT 0,
	`eliminado` TINYINT NOT NULL DEFAULT 0,
	`fecha_creacion` DATETIME NOT NULL,
	CONSTRAINT `PK_evento` PRIMARY KEY (`id_evento` ASC)
)

;

CREATE TABLE `galeria`
(
	`id_galeria` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`id_sitio` INT UNSIGNED NOT NULL,
	`titulo` VARCHAR(255) NOT NULL,
	`descripcion` VARCHAR(255) NOT NULL,
	`orden` INT UNSIGNED NOT NULL DEFAULT 0,
	`eliminado` TINYINT UNSIGNED NOT NULL DEFAULT 0,
	CONSTRAINT `PK_galeria` PRIMARY KEY (`id_galeria` ASC)
)

;

CREATE TABLE `galeria_item`
(
	`id_galeria_item` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`id_galeria` INT UNSIGNED NOT NULL,
	`foto` VARCHAR(150) NOT NULL,
	`orden` INT UNSIGNED NOT NULL DEFAULT 0,
	`eliminado` TINYINT UNSIGNED NOT NULL DEFAULT 0,
	CONSTRAINT `PK_galeria_item` PRIMARY KEY (`id_galeria_item` ASC)
)

;

CREATE TABLE `imagen`
(
	`id_imagen` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`archivo` VARCHAR(100) 	 NULL,
	`eliminada` TINYINT UNSIGNED NOT NULL DEFAULT 0,
	`fecha_creacion` DATETIME NOT NULL,
	CONSTRAINT `PK_imagen` PRIMARY KEY (`id_imagen` ASC)
)

;

CREATE TABLE `local`
(
	`id_local` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`id_plano` INT UNSIGNED NOT NULL,
	`numero` VARCHAR(50) NOT NULL,
	`disponible` TINYINT UNSIGNED NOT NULL DEFAULT 1,
	`logo` VARCHAR(50) 	 NULL,
	`metros` FLOAT(6,2) 	 UNSIGNED NULL,
	`instalaciones` TEXT 	 NULL,
	`nota` TEXT 	 NULL,
	`descripcion` TEXT 	 NULL,
	`imagen_ficha` VARCHAR(50) 	 NULL,
	`eliminado` TINYINT UNSIGNED NOT NULL DEFAULT 0,
	CONSTRAINT `PK_local` PRIMARY KEY (`id_local` ASC)
)

;

CREATE TABLE `local_punto`
(
	`id_local_punto` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`id_local` INT UNSIGNED NOT NULL,
	`lat` VARCHAR(10) NOT NULL,
	`long` VARCHAR(10) NOT NULL,
	`orden` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
	CONSTRAINT `PK_local_punto` PRIMARY KEY (`id_local_punto` ASC)
)

;

CREATE TABLE `local_terminacion`
(
	`id_local_terminacion` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`id_terminacion` INT UNSIGNED NOT NULL,
	`id_local` INT UNSIGNED NOT NULL,
	CONSTRAINT `PK_Table1` PRIMARY KEY (`id_local_terminacion` ASC)
)

;

CREATE TABLE `log`
(
	`id_log` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	CONSTRAINT `PK_log` PRIMARY KEY (`id_log` ASC)
)

;

CREATE TABLE `marca`
(
	`id_marca` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`id_sitio` INT UNSIGNED NOT NULL,
	`nombre` VARCHAR(50) 	 NULL,
	`logo` VARCHAR(150) 	 NULL,
	`link` VARCHAR(255) 	 NULL,
	`orden` TINYINT UNSIGNED NOT NULL DEFAULT 0,
	`eliminado` TINYINT UNSIGNED NOT NULL DEFAULT 0,
	CONSTRAINT `PK_marca` PRIMARY KEY (`id_marca` ASC)
)

;

CREATE TABLE `menu`
(
	`id_menu` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`id_sitio` INT UNSIGNED NOT NULL,
	`id_padre` INT UNSIGNED NOT NULL DEFAULT 0,
	`nombre` VARCHAR(50) NOT NULL,
	`alias` VARCHAR(200) 	 NULL,
	`link` VARCHAR(255) 	 NULL,
	`orden` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
	`eliminado` TINYINT UNSIGNED NOT NULL DEFAULT 0,
	CONSTRAINT `PK_menu` PRIMARY KEY (`id_menu` ASC)
)

;

CREATE TABLE `pagina`
(
	`id_pagina` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`id_sitio` INT UNSIGNED NOT NULL,
	`id_pagina_tipo` SMALLINT UNSIGNED NOT NULL,
	`alias` VARCHAR(255) NOT NULL,
	`titulo1` VARCHAR(150) 	 NULL,
	`titulo2` VARCHAR(150) 	 NULL,
	`texto` TEXT 	 NULL,
	`fondo` VARCHAR(255) 	 NULL,
	`fecha_creacion` DATETIME NOT NULL,
	`eliminada` TINYINT UNSIGNED NOT NULL DEFAULT 0,
	CONSTRAINT `PK_pagina` PRIMARY KEY (`id_pagina` ASC)
)

;

CREATE TABLE `pagina_imagen`
(
	`id_pagina_imagen` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`id_pagina` INT UNSIGNED NOT NULL,
	`id_imagen` INT UNSIGNED NOT NULL,
	`orden` INT UNSIGNED NOT NULL,
	`eliminada` TINYINT UNSIGNED NOT NULL DEFAULT 0,
	CONSTRAINT `PK_pagina_imagen` PRIMARY KEY (`id_pagina_imagen` ASC)
)

;

CREATE TABLE `pagina_tipo`
(
	`id_pagina_tipo` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	`nombre` VARCHAR(50) NOT NULL,
	`eliminado` TINYINT UNSIGNED NOT NULL DEFAULT 0,
	CONSTRAINT `PK_pagina_tipo` PRIMARY KEY (`id_pagina_tipo` ASC)
)

;

CREATE TABLE `perfil`
(
	`id_perfil` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
	`nombre` VARCHAR(50) NOT NULL,
	CONSTRAINT `PK_perfil` PRIMARY KEY (`id_perfil` ASC)
)

;

CREATE TABLE `plano`
(
	`id_plano` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`id_sitio` INT UNSIGNED NOT NULL,
	`nombre` VARCHAR(50) NOT NULL,
	`imagen` VARCHAR(100) NOT NULL,
	`orden` TINYINT UNSIGNED NOT NULL DEFAULT 0,
	`eliminado` TINYINT UNSIGNED NOT NULL DEFAULT 0,
	CONSTRAINT `PK_plano` PRIMARY KEY (`id_plano` ASC)
)

;

CREATE TABLE `portada_caluga`
(
	`id_portada_caluga` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`id_sitio` INT UNSIGNED NOT NULL,
	`imagen` VARCHAR(150) 	 NULL,
	`link` VARCHAR(255) 	 NULL,
	`orden` INT UNSIGNED NOT NULL DEFAULT 0,
	`eliminado` TINYINT UNSIGNED NOT NULL DEFAULT 0,
	CONSTRAINT `PK_portada_caluga` PRIMARY KEY (`id_portada_caluga` ASC)
)

;

CREATE TABLE `portada_slide`
(
	`id_portada_slide` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`id_sitio` INT UNSIGNED NOT NULL,
	`imagen` VARCHAR(150) 	 NULL,
	`link` VARCHAR(255) 	 NULL,
	`orden` INT UNSIGNED NOT NULL DEFAULT 0,
	`eliminado` TINYINT UNSIGNED NOT NULL DEFAULT 0,
	CONSTRAINT `PK_portada_slide` PRIMARY KEY (`id_portada_slide` ASC)
)

;

CREATE TABLE `promocion`
(
	`id_promocion` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`id_sitio` INT UNSIGNED NOT NULL,
	`foto1` VARCHAR(150) 	 NULL,
	`foto2` VARCHAR(150) 	 NULL,
	`link` VARCHAR(255) 	 NULL,
	`orden` INT UNSIGNED NOT NULL DEFAULT 0,
	`eliminado` TINYINT UNSIGNED NOT NULL DEFAULT 0,
	`fecha_creacion` DATETIME NOT NULL,
	CONSTRAINT `PK_promocion` PRIMARY KEY (`id_promocion` ASC)
)

;

CREATE TABLE `red_social`
(
	`id_red_social` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`id_sitio` INT UNSIGNED NOT NULL,
	`nombre` VARCHAR(50) 	 NULL,
	`link` VARCHAR(255) 	 NULL,
	`icono` VARCHAR(50) 	 NULL,
	`orden` TINYINT UNSIGNED NOT NULL DEFAULT 0,
	`eliminado` TINYINT NOT NULL DEFAULT 0,
	CONSTRAINT `PK_red_social` PRIMARY KEY (`id_red_social` ASC)
)

;

CREATE TABLE `servicio`
(
	`id_servicio` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	`nombre` VARCHAR(80) NOT NULL,
	`icono` VARCHAR(50) NOT NULL,
	`orden` TINYINT UNSIGNED NOT NULL DEFAULT 0,
	CONSTRAINT `PK_servicio` PRIMARY KEY (`id_servicio` ASC)
)

;

CREATE TABLE `sitio`
(
	`id_sitio` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`nombre` VARCHAR(100) NOT NULL,
	`link` VARCHAR(50) 	 NULL,
	`carpeta` VARCHAR(50) NOT NULL,
	`logo` VARCHAR(50) 	 NULL,
	`informacion` TEXT 	 NULL,
	`direccion` VARCHAR(255) 	 NULL,
	`terreno` VARCHAR(10) 	 NULL,
	`construida` VARCHAR(10) 	 NULL,
	`arrendable` VARCHAR(10) 	 NULL,
	`estacionamientos` INT 	 NULL,
	`correo` VARCHAR(150) NOT NULL,
	`telefono` VARCHAR(20) 	 NULL,
	`google_map` TEXT 	 NULL,
	`horario` TEXT 	 NULL,
	`como_llegar` TEXT 	 NULL,
	`fecha_apertura` VARCHAR(150) 	 NULL,
  `render1` VARCHAR(50) 	 NULL,
  `render2` VARCHAR(50) 	 NULL,
	`eliminado` TINYINT UNSIGNED NOT NULL DEFAULT 0,
	CONSTRAINT `PK_sitio` PRIMARY KEY (`id_sitio` ASC)
)

;

CREATE TABLE `sitio_servicio`
(
	`id_sitio_servicio` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`id_sitio` INT UNSIGNED NOT NULL,
	`id_servicio` SMALLINT UNSIGNED NOT NULL,
	CONSTRAINT `PK_sitio_servicio` PRIMARY KEY (`id_sitio_servicio` ASC)
)

;

CREATE TABLE `terminacion`
(
	`id_terminacion` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`id_sitio` INT UNSIGNED NOT NULL,
	`numero` TINYINT UNSIGNED NOT NULL DEFAULT 1,
	`descripcion` VARCHAR(100) NOT NULL,
	`eliminado` TINYINT UNSIGNED NOT NULL DEFAULT 0,
	CONSTRAINT `PK_terminaciones` PRIMARY KEY (`id_terminacion` ASC)
)

;

CREATE TABLE `usuario`
(
	`id_usuario` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_sitio` INT UNSIGNED NOT NULL,
	`email` VARCHAR(150) NOT NULL,
	`contrasena` VARCHAR(65) NOT NULL,
	`nombre` VARCHAR(100) NOT NULL,
	`apellido` VARCHAR(100) NOT NULL,
	`rut` VARCHAR(15) NOT NULL,
	`telefono` VARCHAR(20) NOT NULL,
	`verificado` TINYINT UNSIGNED NOT NULL DEFAULT 0,
	`fecha_creacion` DATETIME NOT NULL,
  `fecha_envio_correo` DATETIME 	 NULL,
	`fecha_verificacion` DATETIME 	 NULL,
	`id_perfil` TINYINT UNSIGNED NOT NULL,
	`eliminado` TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `codigo` VARCHAR(65) NULL,
	CONSTRAINT `PK_usuario` PRIMARY KEY (`id_usuario` ASC)
)

;

/* Create Primary Keys, Indexes, Uniques, Checks */

ALTER TABLE `evento` 
 ADD INDEX `IXFK_evento_sitio` (`id_sitio` ASC)
;

ALTER TABLE `galeria` 
 ADD INDEX `IXFK_galeria_sitio` (`id_sitio` ASC)
;

ALTER TABLE `galeria_item` 
 ADD INDEX `IXFK_galeria_item_galeria` (`id_galeria` ASC)
;

ALTER TABLE `local` 
 ADD INDEX `IXFK_local_plano` (`id_plano` ASC)
;

ALTER TABLE `local_punto` 
 ADD INDEX `IXFK_local_punto_local` (`id_local` ASC)
;

ALTER TABLE `local_terminacion` 
 ADD INDEX `IXFK_local_terminacion_local` (`id_local` ASC)
;

ALTER TABLE `local_terminacion` 
 ADD INDEX `IXFK_local_terminacion_terminacion` (`id_terminacion` ASC)
;

ALTER TABLE `marca` 
 ADD INDEX `IXFK_marca_sitio` (`id_sitio` ASC)
;

ALTER TABLE `menu` 
 ADD INDEX `IXFK_menu_sitio` (`id_sitio` ASC)
;

ALTER TABLE `pagina`
 ADD CONSTRAINT `UQ_alias` UNIQUE (`alias` ASC)
;

ALTER TABLE `pagina` 
 ADD INDEX `IXFK_pagina_pagina_tipo` (`id_pagina_tipo` ASC)
;

ALTER TABLE `pagina` 
 ADD INDEX `IXFK_pagina_sitio` (`id_sitio` ASC)
;

ALTER TABLE `pagina_imagen` 
 ADD INDEX `IXFK_pagina_imagen_imagen` (`id_imagen` ASC)
;

ALTER TABLE `pagina_imagen` 
 ADD INDEX `IXFK_pagina_imagen_pagina` (`id_pagina` ASC)
;

ALTER TABLE `plano` 
 ADD INDEX `IXFK_plano_sitio` (`id_sitio` ASC)
;

ALTER TABLE `portada_caluga` 
 ADD INDEX `IXFK_portada_caluga_sitio` (`id_sitio` ASC)
;

ALTER TABLE `portada_slide` 
 ADD INDEX `IXFK_portada_slide_sitio` (`id_sitio` ASC)
;

ALTER TABLE `promocion` 
 ADD INDEX `IXFK_promocion_sitio` (`id_sitio` ASC)
;

ALTER TABLE `red_social` 
 ADD INDEX `IXFK_red_social_sitio` (`id_sitio` ASC)
;

ALTER TABLE `sitio_servicio` 
 ADD INDEX `IXFK_sitio_servicio_servicio` (`id_servicio` ASC)
;

ALTER TABLE `sitio_servicio` 
 ADD INDEX `IXFK_sitio_servicio_sitio` (`id_sitio` ASC)
;

ALTER TABLE `terminacion` 
 ADD INDEX `IXFK_terminaciones_sitio` (`id_sitio` ASC)
;

ALTER TABLE `usuario` 
 ADD CONSTRAINT `UQ_email` UNIQUE (`email` ASC)
;

ALTER TABLE `usuario` 
 ADD INDEX `IXFK_usuario_perfil` (`id_perfil` ASC)
;

/* Create Foreign Key Constraints */

ALTER TABLE `evento` 
 ADD CONSTRAINT `FK_evento_sitio`
	FOREIGN KEY (`id_sitio`) REFERENCES `sitio` (`id_sitio`) ON DELETE Restrict ON UPDATE Cascade
;

ALTER TABLE `galeria` 
 ADD CONSTRAINT `FK_galeria_sitio`
	FOREIGN KEY (`id_sitio`) REFERENCES `sitio` (`id_sitio`) ON DELETE Restrict ON UPDATE Cascade
;

ALTER TABLE `galeria_item` 
 ADD CONSTRAINT `FK_galeria_item_galeria`
	FOREIGN KEY (`id_galeria`) REFERENCES `galeria` (`id_galeria`) ON DELETE Restrict ON UPDATE Cascade
;

ALTER TABLE `local` 
 ADD CONSTRAINT `FK_local_plano`
	FOREIGN KEY (`id_plano`) REFERENCES `plano` (`id_plano`) ON DELETE Restrict ON UPDATE Cascade
;

ALTER TABLE `local_punto` 
 ADD CONSTRAINT `FK_local_punto_local`
	FOREIGN KEY (`id_local`) REFERENCES `local` (`id_local`) ON DELETE Restrict ON UPDATE Cascade
;

ALTER TABLE `local_terminacion` 
 ADD CONSTRAINT `FK_local_terminacion_local`
	FOREIGN KEY (`id_local`) REFERENCES `local` (`id_local`) ON DELETE Restrict ON UPDATE Cascade
;

ALTER TABLE `local_terminacion` 
 ADD CONSTRAINT `FK_local_terminacion_terminacion`
	FOREIGN KEY (`id_terminacion`) REFERENCES `terminacion` (`id_terminacion`) ON DELETE Restrict ON UPDATE Cascade
;

ALTER TABLE `marca` 
 ADD CONSTRAINT `FK_marca_sitio`
	FOREIGN KEY (`id_sitio`) REFERENCES `sitio` (`id_sitio`) ON DELETE Restrict ON UPDATE Cascade
;

ALTER TABLE `menu` 
 ADD CONSTRAINT `FK_menu_sitio`
	FOREIGN KEY (`id_sitio`) REFERENCES `sitio` (`id_sitio`) ON DELETE Restrict ON UPDATE Cascade
;

ALTER TABLE `pagina` 
 ADD CONSTRAINT `FK_pagina_pagina_tipo`
	FOREIGN KEY (`id_pagina_tipo`) REFERENCES `pagina_tipo` (`id_pagina_tipo`) ON DELETE Restrict ON UPDATE Cascade
;

ALTER TABLE `pagina` 
 ADD CONSTRAINT `FK_pagina_sitio`
	FOREIGN KEY (`id_sitio`) REFERENCES `sitio` (`id_sitio`) ON DELETE Restrict ON UPDATE Cascade
;

ALTER TABLE `pagina_imagen` 
 ADD CONSTRAINT `FK_pagina_imagen_imagen`
	FOREIGN KEY (`id_imagen`) REFERENCES `imagen` (`id_imagen`) ON DELETE Restrict ON UPDATE Cascade
;

ALTER TABLE `pagina_imagen` 
 ADD CONSTRAINT `FK_pagina_imagen_pagina`
	FOREIGN KEY (`id_pagina`) REFERENCES `pagina` (`id_pagina`) ON DELETE Restrict ON UPDATE Cascade
;

ALTER TABLE `plano` 
 ADD CONSTRAINT `FK_plano_sitio`
	FOREIGN KEY (`id_sitio`) REFERENCES `sitio` (`id_sitio`) ON DELETE Restrict ON UPDATE Cascade
;

ALTER TABLE `portada_caluga` 
 ADD CONSTRAINT `FK_portada_caluga_sitio`
	FOREIGN KEY (`id_sitio`) REFERENCES `sitio` (`id_sitio`) ON DELETE Restrict ON UPDATE Cascade
;

ALTER TABLE `portada_slide` 
 ADD CONSTRAINT `FK_portada_slide_sitio`
	FOREIGN KEY (`id_sitio`) REFERENCES `sitio` (`id_sitio`) ON DELETE Restrict ON UPDATE Cascade
;

ALTER TABLE `promocion` 
 ADD CONSTRAINT `FK_promocion_sitio`
	FOREIGN KEY (`id_sitio`) REFERENCES `sitio` (`id_sitio`) ON DELETE Restrict ON UPDATE Cascade
;

ALTER TABLE `red_social` 
 ADD CONSTRAINT `FK_red_social_sitio`
	FOREIGN KEY (`id_sitio`) REFERENCES `sitio` (`id_sitio`) ON DELETE Restrict ON UPDATE Cascade
;

ALTER TABLE `sitio_servicio` 
 ADD CONSTRAINT `FK_sitio_servicio_servicio`
	FOREIGN KEY (`id_servicio`) REFERENCES `servicio` (`id_servicio`) ON DELETE Restrict ON UPDATE Cascade
;

ALTER TABLE `sitio_servicio` 
 ADD CONSTRAINT `FK_sitio_servicio_sitio`
	FOREIGN KEY (`id_sitio`) REFERENCES `sitio` (`id_sitio`) ON DELETE Restrict ON UPDATE Cascade
;

ALTER TABLE `terminacion` 
 ADD CONSTRAINT `FK_terminaciones_sitio`
	FOREIGN KEY (`id_sitio`) REFERENCES `sitio` (`id_sitio`) ON DELETE Restrict ON UPDATE Cascade
;

ALTER TABLE `usuario` 
 ADD CONSTRAINT `FK_usuario_perfil`
	FOREIGN KEY (`id_perfil`) REFERENCES `perfil` (`id_perfil`) ON DELETE Restrict ON UPDATE Cascade
;

SET FOREIGN_KEY_CHECKS=1 
;
