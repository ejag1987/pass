USE neorentas_paseo;

-- ----------------------------
-- Records of evento
-- ----------------------------
INSERT INTO `evento` VALUES ('1', '2', 'evento1_1.jpg', 'Praesent placerat', 'vel dictum purus suscipit. Morbi varius hendrerit lorem nec var', '2017-03-12', 'Horarios: 18:00 - 20:00 hrs.', '1', '0', '2017-05-15 17:29:07');
INSERT INTO `evento` VALUES ('2', '2', 'evento2_1.jpg', 'Praesent placerat', 'vel dictum purus suscipit. Morbi varius hendrerit lorem nec var', '2017-06-06', 'Horarios: 18:00 - 20:00 hrs.', '5', '0', '2017-05-15 17:30:11');
INSERT INTO `evento` VALUES ('3', '2', 'evento3_1.jpg', 'Praesent placerat', 'vel dictum purus suscipit. Morbi varius hendrerit lorem nec var', '2017-05-23', 'Horarios: 18:00 - 20:00 hrs.', '2', '0', '2017-05-15 18:21:06');

-- ----------------------------
-- Records of marca
-- ----------------------------
INSERT INTO `marca` VALUES ('1', '1', 'Starbucks', 'marca1.svg', null, '1', '0');
INSERT INTO `marca` VALUES ('2', '1', 'Mokka', 'marca2.svg', null, '2', '0');
INSERT INTO `marca` VALUES ('3', '1', 'MAC', 'marca3.svg', null, '3', '0');
INSERT INTO `marca` VALUES ('4', '1', 'Entel', 'marca4.svg', null, '4', '0');
INSERT INTO `marca` VALUES ('5', '1', 'Banco de Chile', 'marca5.svg', null, '5', '0');
INSERT INTO `marca` VALUES ('6', '1', 'Doite', 'marca6.svg', null, '6', '0');
INSERT INTO `marca` VALUES ('7', '1', 'azaleia', 'marca7.svg', null, '7', '0');
INSERT INTO `marca` VALUES ('8', '1', 'adidas', 'marca8.svg', null, '8', '0');
INSERT INTO `marca` VALUES ('9', '2', 'adidas', 'marca8.svg', null, '1', '0');
INSERT INTO `marca` VALUES ('10', '2', 'Starbucks', 'marca1.svg', null, '3', '0');
INSERT INTO `marca` VALUES ('11', '2', 'Mokka', 'marca2.svg', null, '2', '0');
INSERT INTO `marca` VALUES ('12', '2', 'MAC', 'marca3.svg', null, '4', '0');
INSERT INTO `marca` VALUES ('13', '2', 'Entel', 'marca4.svg', null, '5', '0');
INSERT INTO `marca` VALUES ('14', '2', 'Banco de Chile', 'marca5.svg', null, '6', '0');
INSERT INTO `marca` VALUES ('15', '2', 'Doite', 'marca6.svg', null, '7', '0');
INSERT INTO `marca` VALUES ('16', '2', 'azaleia', 'marca7.svg', null, '8', '0');

-- ----------------------------
-- Records of promocion
-- ----------------------------
INSERT INTO `promocion` VALUES ('1', '2', 'promo1_1.jpg', 'promo1_2.jpg', null, '1', '0', '2017-05-12 10:52:24');
INSERT INTO `promocion` VALUES ('2', '2', 'promo2_1.jpg', 'promo2_2.jpg', null, '2', '0', '2017-05-12 10:52:30');

-- ----------------------------
-- Records of sitio_servicio
-- ----------------------------
INSERT INTO `sitio_servicio` VALUES ('1', '2', '8');
INSERT INTO `sitio_servicio` VALUES ('2', '2', '1');
INSERT INTO `sitio_servicio` VALUES ('3', '2', '4');
INSERT INTO `sitio_servicio` VALUES ('4', '2', '3');
INSERT INTO `sitio_servicio` VALUES ('5', '2', '2');
INSERT INTO `sitio_servicio` VALUES ('6', '2', '5');
INSERT INTO `sitio_servicio` VALUES ('7', '2', '6');
INSERT INTO `sitio_servicio` VALUES ('8', '2', '7');
INSERT INTO `sitio_servicio` VALUES ('9', '3', '1');
INSERT INTO `sitio_servicio` VALUES ('10', '3', '2');
INSERT INTO `sitio_servicio` VALUES ('11', '3', '3');
INSERT INTO `sitio_servicio` VALUES ('12', '3', '4');
INSERT INTO `sitio_servicio` VALUES ('13', '3', '5');
INSERT INTO `sitio_servicio` VALUES ('14', '3', '6');
INSERT INTO `sitio_servicio` VALUES ('15', '3', '7');
INSERT INTO `sitio_servicio` VALUES ('16', '3', '8');
INSERT INTO `sitio_servicio` VALUES ('17', '4', '1');
INSERT INTO `sitio_servicio` VALUES ('18', '4', '2');
INSERT INTO `sitio_servicio` VALUES ('19', '4', '3');
INSERT INTO `sitio_servicio` VALUES ('20', '4', '4');
INSERT INTO `sitio_servicio` VALUES ('21', '4', '5');
INSERT INTO `sitio_servicio` VALUES ('22', '4', '6');
INSERT INTO `sitio_servicio` VALUES ('23', '4', '7');
INSERT INTO `sitio_servicio` VALUES ('24', '4', '8');
INSERT INTO `sitio_servicio` VALUES ('25', '5', '1');
INSERT INTO `sitio_servicio` VALUES ('26', '5', '2');
INSERT INTO `sitio_servicio` VALUES ('27', '5', '3');
INSERT INTO `sitio_servicio` VALUES ('28', '5', '4');
INSERT INTO `sitio_servicio` VALUES ('29', '5', '5');
INSERT INTO `sitio_servicio` VALUES ('30', '5', '6');
INSERT INTO `sitio_servicio` VALUES ('31', '5', '7');
INSERT INTO `sitio_servicio` VALUES ('32', '5', '8');
INSERT INTO `sitio_servicio` VALUES ('33', '6', '1');
INSERT INTO `sitio_servicio` VALUES ('34', '6', '2');
INSERT INTO `sitio_servicio` VALUES ('35', '6', '3');
INSERT INTO `sitio_servicio` VALUES ('36', '6', '4');
INSERT INTO `sitio_servicio` VALUES ('37', '6', '5');
INSERT INTO `sitio_servicio` VALUES ('38', '6', '6');
INSERT INTO `sitio_servicio` VALUES ('39', '6', '7');
INSERT INTO `sitio_servicio` VALUES ('40', '6', '8');
INSERT INTO `sitio_servicio` VALUES ('41', '7', '1');
INSERT INTO `sitio_servicio` VALUES ('42', '7', '2');
INSERT INTO `sitio_servicio` VALUES ('43', '7', '3');
INSERT INTO `sitio_servicio` VALUES ('44', '7', '4');
INSERT INTO `sitio_servicio` VALUES ('45', '7', '5');
INSERT INTO `sitio_servicio` VALUES ('46', '7', '6');
INSERT INTO `sitio_servicio` VALUES ('47', '7', '7');
INSERT INTO `sitio_servicio` VALUES ('48', '7', '8');
INSERT INTO `sitio_servicio` VALUES ('49', '8', '1');
INSERT INTO `sitio_servicio` VALUES ('50', '8', '2');
INSERT INTO `sitio_servicio` VALUES ('51', '8', '3');
INSERT INTO `sitio_servicio` VALUES ('52', '8', '4');
INSERT INTO `sitio_servicio` VALUES ('53', '8', '5');
INSERT INTO `sitio_servicio` VALUES ('54', '8', '6');
INSERT INTO `sitio_servicio` VALUES ('55', '8', '7');
INSERT INTO `sitio_servicio` VALUES ('56', '8', '8');