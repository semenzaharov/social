-- MySQL Script generated by MySQL Workbench
-- 05/05/14 13:45:35
-- Model: New Model    Version: 1.0
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`Captcha`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Captcha` (
  `capchaId` INT(11) NOT NULL,
  `image` VARCHAR(120) NULL DEFAULT NULL,
  `value` VARCHAR(20) NULL DEFAULT NULL,
  PRIMARY KEY (`capchaId`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `mydb`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`user` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(50) NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `second_name` VARCHAR(45) NOT NULL,
  `age` INT(11) NULL DEFAULT NULL,
  `gender` VARCHAR(1) NOT NULL,
  `country` VARCHAR(45) NULL DEFAULT NULL,
  `city` VARCHAR(45) NULL DEFAULT NULL,
  `about` TEXT NULL DEFAULT NULL,
  `login` VARCHAR(128) NOT NULL,
  `password` VARCHAR(256) NOT NULL,
  `status` TINYINT(1) NOT NULL,
  `random_number` VARCHAR(256) NOT NULL,
  `avatar` VARCHAR(90) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `mydb`.`album`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`album` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `album_image` VARCHAR(128) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Album_1` (`user_id` ASC),
  CONSTRAINT `album_ibfk_2`
    FOREIGN KEY (`user_id`)
    REFERENCES `mydb`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `mydb`.`blog_message`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`blog_message` (
  `user_id` INT(11) NOT NULL,
  `tags` VARCHAR(256) NOT NULL,
  `text` TEXT NOT NULL,
  `title` VARCHAR(256) NOT NULL,
  `date` DATETIME NOT NULL,
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  INDEX `fk_Blog_1` (`user_id` ASC),
  CONSTRAINT `fk_Blog_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `mydb`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `mydb`.`blog_message_tag`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`blog_message_tag` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `blog_message_id` INT(11) NOT NULL,
  `tag_id` INT(10) UNSIGNED NOT NULL,
  `user_id` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `mydb`.`comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`comment` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `comm_user_id` INT(11) NOT NULL,
  `value` VARCHAR(256) NOT NULL,
  `date` DATE NOT NULL,
  `blog_message_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Comment_1` (`comm_user_id` ASC),
  INDEX `fk_Comment_3` (`blog_message_id` ASC),
  CONSTRAINT `comment_ibfk_2`
    FOREIGN KEY (`blog_message_id`)
    REFERENCES `mydb`.`blog_message` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Comment_1`
    FOREIGN KEY (`comm_user_id`)
    REFERENCES `mydb`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `mydb`.`comment_photo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`comment_photo` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `comm_user_id` INT(11) NOT NULL,
  `value` VARCHAR(256) NOT NULL,
  `date` DATETIME NOT NULL,
  `photo_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `mydb`.`friend`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`friend` (
  `first_user_id` INT(11) NOT NULL,
  `second_user_id` INT(11) NOT NULL,
  `friend_status` TINYINT(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`first_user_id`, `second_user_id`),
  INDEX `fk_Friend_1` (`first_user_id` ASC),
  INDEX `fk_Friend_2` (`second_user_id` ASC),
  CONSTRAINT `fk_Friend_1`
    FOREIGN KEY (`first_user_id`)
    REFERENCES `mydb`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Friend_2`
    FOREIGN KEY (`second_user_id`)
    REFERENCES `mydb`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `mydb`.`messages`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`messages` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `value` TEXT NOT NULL,
  `date` DATETIME NOT NULL,
  `sender_user_id` INT(11) NOT NULL,
  `theme` VARCHAR(255) NOT NULL,
  `status` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Messages_1` (`user_id` ASC),
  INDEX `fk_Messages_2` (`sender_user_id` ASC),
  CONSTRAINT `fk_Messages_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `mydb`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Messages_2`
    FOREIGN KEY (`sender_user_id`)
    REFERENCES `mydb`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `mydb`.`photo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`photo` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `album_id` INT(11) NOT NULL,
  `name` VARCHAR(256) NULL DEFAULT NULL,
  `image` VARCHAR(90) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Photo_1` (`album_id` ASC),
  CONSTRAINT `photo_ibfk_2`
    FOREIGN KEY (`album_id`)
    REFERENCES `mydb`.`album` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `mydb`.`tag`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`tag` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `count` INT(10) UNSIGNED NOT NULL,
  `user_id` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `mydb`.`portfolio`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`portfolio` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NULL,
  `name` VARCHAR(256) NULL,
  `descr` TEXT NULL,
  `link` VARCHAR(128) NULL,
  PRIMARY KEY (`id`),
  INDEX `id_idx` (`user_id` ASC),
  CONSTRAINT `user_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `mydb`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`task`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`task` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(256) NULL,
  `descr` TEXT NULL,
  `date` DATETIME NULL,
  `priority` VARCHAR(45) NULL,
  `type` VARCHAR(45) NULL,
  `child` INT NULL,
  `time_estimate` TIME NULL,
  `time_used` TIME NULL,
  `atachment` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`task_user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`task_user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NULL,
  `task_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `user_id_idx` (`user_id` ASC),
  INDEX `task_id_idx` (`task_id` ASC),
  CONSTRAINT `user_index`
    FOREIGN KEY (`user_id`)
    REFERENCES `mydb`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `task_index`
    FOREIGN KEY (`task_id`)
    REFERENCES `mydb`.`task` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`task_blog`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`task_blog` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `task_id` INT NULL,
  `text` TEXT NULL,
  `title` TEXT NULL,
  `date` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `task_id_idx` (`task_id` ASC),
  CONSTRAINT `task_id`
    FOREIGN KEY (`task_id`)
    REFERENCES `mydb`.`task` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`task_blog_comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`task_blog_comment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NULL,
  `task_blog_id` INT NULL,
  `text` TEXT NULL,
  `date` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `user_id_idx` (`user_id` ASC),
  INDEX `task_blog_id_idx` (`task_blog_id` ASC),
  CONSTRAINT `user_id2`
    FOREIGN KEY (`user_id`)
    REFERENCES `mydb`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `task_blog_id2`
    FOREIGN KEY (`task_blog_id`)
    REFERENCES `mydb`.`task_blog` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
