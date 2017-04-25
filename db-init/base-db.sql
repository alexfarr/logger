DROP schema logger;

CREATE DATABASE `logger` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE logger;

CREATE TABLE `logger` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sensor` varchar(45) DEFAULT NULL,
  `data` varchar(45) DEFAULT NULL,
  `value` varchar(45) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;


CREATE TABLE `logger`.`sensors` (
  `id` VARCHAR(16) NOT NULL,
  `title` VARCHAR(255) NULL,
  PRIMARY KEY (`id`));

