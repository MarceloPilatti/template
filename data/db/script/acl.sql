DROP TABLE IF EXISTS `ROLE`;
/*!40101 SET @SAVED_CS_CLIENT     = @@CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_CLIENT = UTF8 */;
CREATE TABLE `ROLE` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `NAME` VARCHAR(255) COLLATE UTF8_UNICODE_CI NOT NULL,
  `IS_ADMIN` TINYINT(1) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=INNODB AUTO_INCREMENT=0 DEFAULT CHARSET=UTF8 COLLATE=UTF8_UNICODE_CI;
/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;

DROP TABLE IF EXISTS `USER`;
/*!40101 SET @SAVED_CS_CLIENT     = @@CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_CLIENT = UTF8 */;
CREATE TABLE `USER` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `USER_NAME` VARCHAR(10) COLLATE UTF8_UNICODE_CI NOT NULL,
  `PASSWORD` VARCHAR(60) COLLATE UTF8_UNICODE_CI NOT NULL,
  `ROLE_ID` INT(11) COLLATE UTF8_UNICODE_CI NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_ROLE_ID` (`ROLE_ID`),
  CONSTRAINT `FK_USER_ROLE_ID` FOREIGN KEY (`ROLE_ID`) REFERENCES `ROLE` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=INNODB AUTO_INCREMENT=0 DEFAULT CHARSET=UTF8 COLLATE=UTF8_UNICODE_CI;
/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;

DROP TABLE IF EXISTS `PRIVILEGE`;
/*!40101 SET @SAVED_CS_CLIENT     = @@CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_CLIENT = UTF8 */;
CREATE TABLE `PRIVILEGE` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `NAME` VARCHAR(255) COLLATE UTF8_UNICODE_CI NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=INNODB AUTO_INCREMENT=0 DEFAULT CHARSET=UTF8 COLLATE=UTF8_UNICODE_CI;
/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;

DROP TABLE IF EXISTS `ROLE_PRIVILEGE`;
/*!40101 SET @SAVED_CS_CLIENT     = @@CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_CLIENT = UTF8 */;
CREATE TABLE `ROLE_PRIVILEGE` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `ROLE_ID` INT(11) NOT NULL,
  `PRIVILEGE_ID` INT(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_ROLE_ID` (`ROLE_ID`),
  KEY `FK_PRIVILEGE_ID` (`PRIVILEGE_ID`),
  CONSTRAINT `FK_PRIVILEGE_ID` FOREIGN KEY (`PRIVILEGE_ID`) REFERENCES `PRIVILEGE` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_ROLE_ID` FOREIGN KEY (`ROLE_ID`) REFERENCES `ROLE` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=INNODB AUTO_INCREMENT=0 DEFAULT CHARSET=UTF8 COLLATE=UTF8_UNICODE_CI;
/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;