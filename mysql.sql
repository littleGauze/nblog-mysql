/*
SQLyog Ultimate v10.00 Beta1
MySQL - 5.0.96-community-nt : Database - blog_gauze
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`blog_gauze` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `blog_gauze`;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_no` int(12) NOT NULL auto_increment,
  `user_name` varchar(24) NOT NULL,
  `user_pass` varchar(32) NOT NULL,
  `user_nick` varchar(24) default NULL,
  `user_gender` int(1) default '1',
  `user_ctime` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `user_avator_key` varchar(48) default 'avator',
  PRIMARY KEY  (`user_no`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `users` */

insert  into `users`(`user_no`,`user_name`,`user_pass`,`user_nick`,`user_gender`,`user_ctime`,`user_avator_key`) values (1,'admin','daa3db8ffb7dc80a0d02075cd26f0c4e','Gauze',1,'2015-07-22 17:32:53','avator'),(5,'nealli','daa3db8ffb7dc80a0d02075cd26f0c4e',NULL,1,'2015-07-22 17:32:33','avator');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
