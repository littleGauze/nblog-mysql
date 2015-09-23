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
  `message_type` int(2) NOT NULL default '1' COMMENT '1系统消息 2评论消息 3点赞的消息 4FA',
  `message_content` text NOT NULL,
  `message_ctime` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `message_from` varchar(32) NOT NULL,
  `message_to` varchar(32) default NULL,
  `message_ref` int(11) NOT NULL default '0' COMMENT '0代表系统信息，否则是帖子的no',
  `message_to_nick` varchar(32) default NULL,
  `message_from_nick` varchar(32) default NULL,
  `message_parent` int(11) NOT NULL default '0' COMMENT '0代表评论信息，而不是回复信息',
  `message_disabled` tinyint(1) NOT NULL default '0' COMMENT '当前消息是否可用来计算 赞的次数',
  `message_readed` tinyint(1) NOT NULL default '0' COMMENT '该条消息是否已读',
  PRIMARY KEY  (`message_no`)
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8;

/*Data for the table `messages` */

insert  into `messages`(`message_no`,`message_type`,`message_content`,`message_ctime`,`message_from`,`message_to`,`message_ref`,`message_to_nick`,`message_from_nick`,`message_parent`,`message_disabled`,`message_readed`) values (1,2,'this message from the publish.','2015-07-29 15:22:38','nealli','nealli',14,'Gauze007','Gauze007',0,0,0),(24,1,'Gauze 开始关注您了！','2015-08-18 14:39:38','admin','nealli',0,'Gauze007','Gauze',0,0,0),(26,2,'hello this is from admin.','2015-08-18 16:35:00','admin','nealli',14,'Gauze007','Gauze',0,0,0),(27,2,'hello this is from admin2.','2015-08-18 16:38:54','admin','nealli',14,'Gauze007','Gauze',0,0,0),(28,2,'hello this is from admin second.','2015-08-18 16:42:06','admin','nealli',12,'Gauze007','Gauze',0,0,0),(29,2,'hello there.','2015-08-18 16:49:31','admin','nealli',11,'Gauze007','Gauze',0,0,0),(30,2,'this pic very nice.','2015-08-18 16:50:34','admin','nealli',9,'Gauze007','Gauze',0,0,0),(31,2,'yes','2015-08-18 16:51:27','admin','nealli',14,'Gauze007','Gauze',0,0,0),(32,2,'comment from myself.','2015-08-18 16:52:29','admin','admin',13,'Gauze','Gauze',0,0,0),(33,2,'comment myself.','2015-08-18 16:54:08','admin','admin',13,'Gauze','Gauze',0,0,0),(34,2,'nice bro.','2015-08-18 17:08:36','nealli','admin',13,'Gauze','Gauze007',0,0,0),(35,2,'very nice.','2015-09-21 14:44:14','nealli','admin',13,'Gauze','Gauze007',0,0,0),(36,2,'It\'s OK.','2015-09-21 15:33:51','admin','nealli',10,'Gauze007','Gauze',0,0,0),(37,2,'hello Gauze','2015-09-21 16:56:42','nealli','nealli',14,'Gauze007','Gauze007',0,0,0),(38,2,'6','2015-09-21 17:07:47','nealli','nealli',6,'Gauze007','Gauze007',0,0,0),(39,2,'6','2015-09-21 17:08:00','nealli','nealli',6,'Gauze007','Gauze007',0,0,0),(40,2,'hello Gauze final.','2015-09-21 17:27:01','nealli','admin',14,'Gauze','Gauze007',27,0,0),(41,3,'Gauze007 赞了您的说说!','2015-09-22 10:42:37','nealli','admin',13,'Gauze','Gauze007',0,0,0),(42,3,'Gauze 赞了您的说说!','2015-09-22 10:49:52','admin','nealli',1,'Gauze007','Gauze',0,0,0),(43,3,'Gauze 赞了您的说说!','2015-09-22 10:49:55','admin','nealli',2,'Gauze007','Gauze',0,0,0),(44,3,'Gauze007 赞了您的说说!','2015-09-22 11:12:22','nealli','nealli',14,'Gauze007','Gauze007',0,0,0),(45,3,'Gauze007 赞了您的说说!','2015-09-22 11:12:50','nealli','nealli',12,'Gauze007','Gauze007',0,0,0),(46,3,'Gauze 赞了您的说说!','2015-09-22 11:35:12','admin','nealli',7,'Gauze007','Gauze',0,0,0),(47,3,'Gauze 赞了您的说说!','2015-09-22 11:36:55','admin','nealli',6,'Gauze007','Gauze',0,0,0),(48,2,'add one.','2015-09-22 11:40:24','admin','nealli',5,'Gauze007','Gauze',0,0,0),(49,2,'add two.','2015-09-22 11:41:57','admin','nealli',5,'Gauze007','Gauze',0,0,0),(50,2,'hello here.','2015-09-22 11:43:12','admin','nealli',11,'Gauze007','Gauze',0,0,0),(51,2,'how about now?','2015-09-22 11:44:20','admin','nealli',12,'Gauze007','Gauze',0,0,0),(52,2,'WTF','2015-09-22 11:46:17','admin','nealli',14,'Gauze007','Gauze',1,0,0),(53,2,'test for the middle insert.','2015-09-22 11:47:58','admin','nealli',14,'Gauze007','Gauze',1,0,0),(54,2,'reply to nice bro.','2015-09-22 11:55:29','admin','nealli',13,'Gauze007','Gauze',34,0,0),(55,2,'thanks','2015-09-22 13:48:42','admin','nealli',13,'Gauze007','Gauze',35,0,0),(56,2,'yes','2015-09-22 13:53:19','nealli','admin',12,'Gauze','Gauze007',28,0,0),(57,2,'66666','2015-09-22 13:59:01','admin','nealli',6,'Gauze007','Gauze',38,0,0),(58,2,'coming again.','2015-09-22 14:09:17','admin','nealli',14,'Gauze007','Gauze',1,0,0),(59,2,'welcome.','2015-09-22 14:10:05','nealli','admin',14,'Gauze','Gauze007',1,0,0),(60,2,'haha','2015-09-22 14:13:22','admin','nealli',14,'Gauze007','Gauze',1,0,0),(61,2,'new comments','2015-09-22 14:13:54','admin','nealli',14,'Gauze007','Gauze',0,0,0),(62,3,'Gauze 赞了您的说说!','2015-09-22 14:14:02','admin','nealli',14,'Gauze007','Gauze',0,0,0),(63,3,'Gauze 赞了您的说说!','2015-09-22 14:18:42','admin','nealli',4,'Gauze007','Gauze',0,0,0),(64,3,'Gauze 赞了您的说说!','2015-09-22 14:19:25','admin','nealli',3,'Gauze007','Gauze',0,0,0),(65,2,'hehe','2015-09-22 14:35:16','admin','nealli',14,'Gauze007','Gauze',1,0,0),(66,2,'hello 007','2015-09-22 14:38:23','admin','nealli',14,'Gauze007','Gauze',37,0,0),(67,3,'Gauze 赞了您的说说!','2015-09-22 15:11:34','admin','nealli',10,'Gauze007','Gauze',0,0,0),(68,2,'nice color.','2015-09-22 15:12:40','admin','nealli',10,'Gauze007','Gauze',0,0,0),(69,3,'Gauze 赞了您的说说!','2015-09-22 15:14:24','admin','nealli',11,'Gauze007','Gauze',0,0,0),(70,3,'Gauze 赞了您的说说!','2015-09-22 15:19:10','admin','admin',13,'Gauze','Gauze',0,0,0),(71,2,'66666666','2015-09-23 10:41:37','admin','admin',15,'Gauze','Gauze',0,0,0),(72,3,'Gauze007 赞了您的说说!','2015-09-23 10:55:41','nealli','admin',15,'Gauze','Gauze007',0,0,0),(73,2,'3333333','2015-09-23 10:55:51','nealli','admin',15,'Gauze','Gauze007',0,0,0),(74,2,'2233333','2015-09-23 13:39:40','admin','nealli',15,'Gauze007','Gauze',0,0,0),(75,2,'for 333333','2015-09-23 13:54:45','admin','nealli',15,'Gauze007','Gauze',73,0,0),(77,2,'yeap!','2015-09-23 13:55:55','admin','nealli',14,'Gauze007','Gauze',1,0,0),(84,4,'你好啊','2015-09-23 14:51:02','guest','admin',0,'管理员','游客',0,0,0),(85,4,'Hello','2015-09-23 15:11:45','guest','admin',0,'管理员','游客',0,0,0),(86,4,'你好，请问有什么问题？','2015-09-23 16:09:24','guest','admin',0,'管理员','游客',84,0,0),(87,4,'没什么问题','2015-09-23 16:25:31','guest','admin',0,'管理员','游客',84,0,0),(88,4,'Hello,any questions?','2015-09-23 16:27:22','guest','admin',0,'管理员','游客',85,0,0),(89,4,'nothing...lol','2015-09-23 16:27:41','guest','admin',0,'管理员','游客',85,0,0),(90,4,'hahahahhaha','2015-09-23 16:36:04','guest','admin',0,'管理员','游客',85,0,0),(91,4,'有毛病','2015-09-23 16:36:25','guest','admin',0,'管理员','游客',84,0,0),(93,4,'你是谁？','2015-09-23 16:37:28','guest','admin',0,'管理员','游客',0,0,0),(94,4,'我就是我，是颜色不一样的烟火','2015-09-23 16:38:17','guest','admin',0,'管理员','游客',93,0,0),(104,2,'google new logo','2015-09-23 17:18:18','admin','admin',16,'Gauze','Gauze',0,0,0),(105,2,'LOL......','2015-09-23 17:21:15','admin','admin',17,'Gauze','Gauze',0,0,0),(106,3,'Gauze 赞了您的说说!','2015-09-23 17:23:45','admin','admin',17,'Gauze','Gauze',0,0,0);

/*Table structure for table `posts` */

DROP TABLE IF EXISTS `posts`;

CREATE TABLE `posts` (
  `post_no` int(11) NOT NULL auto_increment,
  `post_img_key` varchar(48) NOT NULL,
  `post_ctime` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `post_user` varchar(32) NOT NULL,
  `post_desc` text,
  PRIMARY KEY  (`post_no`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

/*Data for the table `posts` */

insert  into `posts`(`post_no`,`post_img_key`,`post_ctime`,`post_user`,`post_desc`) values (1,'nealli/F0s1H50yMU031430TNhj488082YeD','2015-07-29 14:07:30','nealli',NULL),(2,'nealli/3_n1118PAmDMYP8u8GZl105470lTh','2015-07-29 14:10:03','nealli',NULL),(3,'nealli/60s32F6yuBT8zq549XhoFx31n19h7','2015-07-29 14:13:30','nealli',NULL),(4,'nealli/82tqg5i0YTHQp1161B5C4e_T47m36','2015-07-29 14:13:57','nealli',NULL),(5,'nealli/8cTyQbm09r1SgY28mq94P6V541314','2015-07-29 14:15:01','nealli',NULL),(6,'nealli/5kF2H83esl3150TKow29759C08L14','2015-07-29 14:16:48','nealli',NULL),(7,'nealli/VH3X44T5A1824YUC0r23R71oB659P','2015-07-29 14:18:06','nealli',NULL),(8,'nealli/14g15Mw4w8uwd3DoO97120K35bcfL','2015-07-29 14:22:18','nealli',NULL),(9,'nealli/zm211zOZ8Dx4Zb3kI22T50159Zz2p','2015-07-29 14:27:13','nealli',NULL),(10,'nealli/Y74115m5Ep78R61O5hJ3n4EKX5v57','2015-07-29 14:31:37','nealli',NULL),(11,'nealli/s097f1g148l7Tp61rw53S50_R9Vss','2015-07-29 14:34:35','nealli',NULL),(12,'nealli/33FFKc371B4y5oz18r71zd5wIC1X4','2015-07-29 14:35:27','nealli',NULL),(13,'admin/Z84W41K3SXWN251t1EX581Qi81BJ6','2015-07-29 14:37:11','admin',NULL),(14,'nealli/40885Y593K4Nai8Y13h3514to614i','2015-07-29 15:22:38','nealli',NULL),(15,'admin/d31282zg4rvELzs74c1640XU98JLj','2015-09-23 10:41:37','admin','66666666'),(16,'admin/9971439Ur8F4Xmi29AG54EkHvi7I2','2015-09-23 17:18:18','admin','google new logo'),(17,'admin/x1im4M43fv9_014j2Q20S1u0ILy70','2015-09-23 17:21:15','admin','LOL......');

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
