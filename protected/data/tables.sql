-- Adminer 3.7.0 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = '+03:00';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `Captcha`;
CREATE TABLE `Captcha` (
  `capchaId` int(11) NOT NULL,
  `image` varchar(120) DEFAULT NULL,
  `value` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`capchaId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `album`;
CREATE TABLE `album` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `album_image` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Album_1` (`user_id`),
  CONSTRAINT `album_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `blog_message`;
CREATE TABLE `blog_message` (
  `user_id` int(11) NOT NULL,
  `tags` varchar(256) NOT NULL,
  `text` text NOT NULL,
  `title` varchar(256) NOT NULL,
  `date` datetime NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `fk_Blog_1` (`user_id`),
  CONSTRAINT `fk_Blog_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `blog_message_tag`;
CREATE TABLE `blog_message_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_message_id` int(11) NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comm_user_id` int(11) NOT NULL,
  `value` varchar(256) NOT NULL,
  `date` date NOT NULL,
  `blog_message_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Comment_1` (`comm_user_id`),
  KEY `fk_Comment_3` (`blog_message_id`),
  CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`blog_message_id`) REFERENCES `blog_message` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_Comment_1` FOREIGN KEY (`comm_user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `comment_photo`;
CREATE TABLE `comment_photo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comm_user_id` int(11) NOT NULL,
  `value` varchar(256) NOT NULL,
  `date` datetime NOT NULL,
  `photo_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `friend`;
CREATE TABLE `friend` (
  `first_user_id` int(11) NOT NULL,
  `second_user_id` int(11) NOT NULL,
  `friend_status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`first_user_id`,`second_user_id`),
  KEY `fk_Friend_1` (`first_user_id`),
  KEY `fk_Friend_2` (`second_user_id`),
  CONSTRAINT `fk_Friend_1` FOREIGN KEY (`first_user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Friend_2` FOREIGN KEY (`second_user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `value` text NOT NULL,
  `date` datetime NOT NULL,
  `sender_user_id` int(11) NOT NULL,
  `theme` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Messages_1` (`user_id`),
  KEY `fk_Messages_2` (`sender_user_id`),
  CONSTRAINT `fk_Messages_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Messages_2` FOREIGN KEY (`sender_user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `photo`;
CREATE TABLE `photo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `album_id` int(11) NOT NULL,
  `name` varchar(256) DEFAULT NULL,
  `image` varchar(90) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Photo_1` (`album_id`),
  CONSTRAINT `photo_ibfk_2` FOREIGN KEY (`album_id`) REFERENCES `album` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `tag`;
CREATE TABLE `tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `count` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `name` varchar(45) NOT NULL,
  `second_name` varchar(45) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` varchar(1) NOT NULL,
  `country` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `about` text,
  `login` varchar(128) NOT NULL,
  `password` varchar(256) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `random_number` varchar(256) NOT NULL,
  `avatar` varchar(90) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2013-06-17 16:59:49
