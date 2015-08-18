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

/*Table structure for table `impressions` */

DROP TABLE IF EXISTS `impressions`;

CREATE TABLE `impressions` (
  `impression_no` int(11) NOT NULL auto_increment,
  `impression_user` varchar(32) NOT NULL,
  `impression_friend` varchar(32) NOT NULL,
  `impression_text` text NOT NULL,
  `impression_ctime` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`impression_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `impressions` */

/*Table structure for table `messages` */

DROP TABLE IF EXISTS `messages`;

CREATE TABLE `messages` (
  `message_no` int(11) NOT NULL auto_increment,
  `message_type` int(2) NOT NULL default '1' COMMENT '1系统消息 2评论消息 3点赞的消息 4FAQ',
  `message_content` text NOT NULL,
  `message_ctime` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `message_from` varchar(32) NOT NULL,
  `message_to` varchar(32) default NULL,
  `message_ref` int(11) NOT NULL default '0' COMMENT '0代表系统信息，否则是帖子的no',
  `message_parent` int(11) NOT NULL default '0' COMMENT '0代表评论信息，而不是回复信息',
  `message_disabled` tinyint(1) NOT NULL default '0' COMMENT '当前消息是否可用来计算 赞的次数',
  PRIMARY KEY  (`message_no`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

/*Data for the table `messages` */

insert  into `messages`(`message_no`,`message_type`,`message_content`,`message_ctime`,`message_from`,`message_to`,`message_ref`,`message_parent`,`message_disabled`) values (1,2,'this message from the publish.','2015-07-29 15:22:38','nealli','nealli',14,0,0),(24,1,'<a href=\"zone/admin\">Gauze</a> 开始关注您了！','2015-08-18 14:39:38','admin','nealli',0,0,0),(26,2,'hello this is from admin.','2015-08-18 16:35:00','admin','nealli',14,0,0),(27,2,'hello this is from admin2.','2015-08-18 16:38:54','admin','nealli',14,0,0),(28,2,'hello this is from admin second.','2015-08-18 16:42:06','admin','nealli',12,0,0),(29,2,'hello there.','2015-08-18 16:49:31','admin','nealli',11,0,0),(30,2,'this pic very nice.','2015-08-18 16:50:34','admin','nealli',9,0,0),(31,2,'yes','2015-08-18 16:51:27','admin','nealli',14,0,0),(32,2,'comment from myself.','2015-08-18 16:52:29','admin','admin',13,0,0),(33,2,'comment myself.','2015-08-18 16:54:08','admin','admin',13,0,0),(34,2,'nice bro.','2015-08-18 17:08:36','nealli','admin',13,0,0);

/*Table structure for table `posts` */

DROP TABLE IF EXISTS `posts`;

CREATE TABLE `posts` (
  `post_no` int(11) NOT NULL auto_increment,
  `post_img_key` varchar(48) NOT NULL,
  `post_ctime` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `post_user` varchar(32) NOT NULL,
  PRIMARY KEY  (`post_no`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

/*Data for the table `posts` */

insert  into `posts`(`post_no`,`post_img_key`,`post_ctime`,`post_user`) values (1,'nealli/F0s1H50yMU031430TNhj488082YeD','2015-07-29 14:07:30','nealli'),(2,'nealli/3_n1118PAmDMYP8u8GZl105470lTh','2015-07-29 14:10:03','nealli'),(3,'nealli/60s32F6yuBT8zq549XhoFx31n19h7','2015-07-29 14:13:30','nealli'),(4,'nealli/82tqg5i0YTHQp1161B5C4e_T47m36','2015-07-29 14:13:57','nealli'),(5,'nealli/8cTyQbm09r1SgY28mq94P6V541314','2015-07-29 14:15:01','nealli'),(6,'nealli/5kF2H83esl3150TKow29759C08L14','2015-07-29 14:16:48','nealli'),(7,'nealli/VH3X44T5A1824YUC0r23R71oB659P','2015-07-29 14:18:06','nealli'),(8,'nealli/14g15Mw4w8uwd3DoO97120K35bcfL','2015-07-29 14:22:18','nealli'),(9,'nealli/zm211zOZ8Dx4Zb3kI22T50159Zz2p','2015-07-29 14:27:13','nealli'),(10,'nealli/Y74115m5Ep78R61O5hJ3n4EKX5v57','2015-07-29 14:31:37','nealli'),(11,'nealli/s097f1g148l7Tp61rw53S50_R9Vss','2015-07-29 14:34:35','nealli'),(12,'nealli/33FFKc371B4y5oz18r71zd5wIC1X4','2015-07-29 14:35:27','nealli'),(13,'admin/Z84W41K3SXWN251t1EX581Qi81BJ6','2015-07-29 14:37:11','admin'),(14,'nealli/40885Y593K4Nai8Y13h3514to614i','2015-07-29 15:22:38','nealli');

/*Table structure for table `relations` */

DROP TABLE IF EXISTS `relations`;

CREATE TABLE `relations` (
  `relations_no` int(11) NOT NULL auto_increment,
  `relations_star` varchar(32) NOT NULL COMMENT '主角的username',
  `relations_eachother` int(2) NOT NULL default '0' COMMENT '0为当方面关注 1为相互关注',
  `relations_fans` varchar(32) NOT NULL COMMENT '跟我产生关系的人username',
  `relations_ftime` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT '关注的时间',
  PRIMARY KEY  (`relations_no`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

/*Data for the table `relations` */

insert  into `relations`(`relations_no`,`relations_star`,`relations_eachother`,`relations_fans`,`relations_ftime`) values (1,'admin',1,'nealli','2015-08-17 16:53:55'),(25,'nealli',1,'admin','2015-08-18 14:39:38');

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
  `user_motto` text,
  `user_extension` text,
  `user_permission` int(48) NOT NULL default '1',
  PRIMARY KEY  (`user_no`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `users` */

insert  into `users`(`user_no`,`user_name`,`user_pass`,`user_nick`,`user_gender`,`user_ctime`,`user_avator_key`,`user_motto`,`user_extension`,`user_permission`) values (1,'admin','daa3db8ffb7dc80a0d02075cd26f0c4e','Gauze',1,'2015-08-18 10:34:39','nealli/1852B8rf73JOz3t8p2N3E8bP95zR4','anything is possible',NULL,256),(5,'nealli','daa3db8ffb7dc80a0d02075cd26f0c4e','Gauze007',0,'2015-07-25 16:50:27','nealli/FiX737I97Hst60CV1C31jv9J1a4o6','anything is impossible',NULL,1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
