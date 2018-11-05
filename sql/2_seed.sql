USE neorentas_paseo;

-- ----------------------------
-- Records of sitio
-- ----------------------------
INSERT INTO `sitio` VALUES ('1', 'Paseo', 'http://www.paseos.cl/', 'paseo', null, null, 'Alonso de Córdova 2.700 of. 24 <br>Vitacura - Santiago, Chile', null, null, null, null, 'contacto@paseo.cl', '(56 2) 2464 3591', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3330.8138464209387!2d-70.59598148435282!3d-33.402020480787755!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9662cf250de7f841%3A0xf9f838a245f24075!2sAlonso+de+C%C3%B3rdova%2C+Vitacura%2C+Regi%C3%B3n+Metropolitana!5e0!3m2!1sen!2scl!4v1490040817463\" width=\"600\" height=\"450\" frameborder=\"0\" style=\"border:0\" allowfullscreen></iframe>', null, null, null, null, null, '0');

-- ----------------------------
-- Records of pagina_tipo
-- ----------------------------
INSERT INTO `pagina_tipo` VALUES ('1', 'Acerca de la empresa', '0');
INSERT INTO `pagina_tipo` VALUES ('2', 'Contacto', '0');
INSERT INTO `pagina_tipo` VALUES ('3', 'Información comercial', '0');
INSERT INTO `pagina_tipo` VALUES ('4', 'Tiendas', '0');
INSERT INTO `pagina_tipo` VALUES ('5', 'Promociones', '0');
INSERT INTO `pagina_tipo` VALUES ('6', 'Eventos', '0');
INSERT INTO `pagina_tipo` VALUES ('7', 'Eventos anteriores', '0');
INSERT INTO `pagina_tipo` VALUES ('8', 'Plano', '0');
INSERT INTO `pagina_tipo` VALUES ('9', 'Plano tiendas', '0');

-- ----------------------------
-- Records of servicio
-- ----------------------------
INSERT INTO `servicio` VALUES ('1', 'Supermercado', 'supermercado.svg', '1');
INSERT INTO `servicio` VALUES ('2', 'Tienda de mejoramiento del hogar', 'tienda_mejoramiento_del_hogar.svg', '2');
INSERT INTO `servicio` VALUES ('3', 'Cajero', 'cajeros.svg', '3');
INSERT INTO `servicio` VALUES ('4', 'Farmacia', 'farmacia.svg', '4');
INSERT INTO `servicio` VALUES ('5', 'Gimnasio', 'gimnasio.svg', '5');
INSERT INTO `servicio` VALUES ('6', 'Patio de comidas', 'patio_de_comida.svg', '6');
INSERT INTO `servicio` VALUES ('7', 'Centro médico', 'centro_medico.svg', '7');
INSERT INTO `servicio` VALUES ('8', 'Estacionamientos', 'estacionamientos.svg', '8');
INSERT INTO `servicio` VALUES ('9', 'Oficinas', 'estacionamientos.svg', '9');

-- ----------------------------
-- Records of perfil
-- ----------------------------
INSERT INTO `perfil` VALUES ('1', 'Administrador');
INSERT INTO `perfil` VALUES ('2', 'Arrendatario');

