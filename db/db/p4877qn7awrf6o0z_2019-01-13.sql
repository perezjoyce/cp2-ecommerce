# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: tk3mehkfmmrhjg0b.cbetxkdyhwsb.us-east-1.rds.amazonaws.com (MySQL 5.7.23-log)
# Database: p4877qn7awrf6o0z
# Generation Time: 2019-01-13 06:00:00 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table tbl_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_users`;

CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT 'uploads/user_default_image',
  `userType` enum('user','admin') NOT NULL DEFAULT 'user',
  `isSeller` enum('yes','no') NOT NULL DEFAULT 'no',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `tbl_users` WRITE;
/*!40000 ALTER TABLE `tbl_users` DISABLE KEYS */;

INSERT INTO `tbl_users` (`id`, `first_name`, `last_name`, `email`, `password`, `username`, `profile_pic`, `userType`, `isSeller`, `date_created`, `last_login`)
VALUES
	(2,'Emmaroo .','Garcia .','binibin*******@gmail.com','f7c3bc1d808e04732adf679965ccc34ca7ae3441','qwerty','uploads/2/5c30d8e5bdf74','user','yes','2019-01-09 18:01:24','2019-01-13 05:58:03'),
	(22,'jojie','garcia','jojie@gmail.com','f7c3bc1d808e04732adf679965ccc34ca7ae3441','jojie','uploads/22/5c0c08c6b1a91','user','no','2019-01-09 18:01:24','2019-01-09 18:01:24'),
	(23,'jocelyls','garcia','joc***@gmail.com','f7c3bc1d808e04732adf679965ccc34ca7ae3441','jocelyl','uploads/23/5c1d6d280dac0','user','yes','2019-01-09 18:01:24','2019-01-10 05:31:41'),
	(24,'jeg','garcia','jeg@gmail.com','f7c3bc1d808e04732adf679965ccc34ca7ae3441','jegjeg',NULL,'user','yes','2019-01-09 18:01:24','2019-01-09 18:01:24'),
	(25,'johnray','garcia','garcia.joyce25@gmail.com','f7c3bc1d808e04732adf679965ccc34ca7ae3441','johnray','uploads/22/5c0c08c6b1a91','user','yes','2019-01-09 18:01:24','2019-01-10 20:37:01'),
	(26,'joy','perez','joycejoyce@gmail.com','f7c3bc1d808e04732adf679965ccc34ca7ae3441','joyjoy',NULL,'user','yes','2019-01-09 18:01:24','2019-01-09 18:01:24'),
	(27,'jem','perez','joyceperez@gmail.com','f7c3bc1d808e04732adf679965ccc34ca7ae3441','qwertyu','uploads/27/5c0c08c6b1a9d','user','yes','2019-01-09 18:01:24','2019-01-09 18:01:24'),
	(28,NULL,NULL,'princess@gmail.com','f7c3bc1d808e04732adf679965ccc34ca7ae3441','princess',NULL,'user','no','2019-01-09 18:01:24','2019-01-10 21:49:36'),
	(29,NULL,NULL,'princessemma@gmail.com','f7c3bc1d808e04732adf679965ccc34ca7ae3441','princessemma',NULL,'user','no','2019-01-09 18:01:24','2019-01-09 18:01:24'),
	(30,NULL,NULL,'adamoo@gmail.com','f7c3bc1d808e04732adf679965ccc34ca7ae3441','adamoo',NULL,'user','no','2019-01-09 18:01:24','2019-01-10 11:18:06');

/*!40000 ALTER TABLE `tbl_users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
