SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `framework_sample_site` ;
CREATE SCHEMA IF NOT EXISTS `framework_sample_site` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `framework_sample_site` ;

-- -----------------------------------------------------
-- Table `framework_sample_site`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `framework_sample_site`.`user` ;

CREATE TABLE IF NOT EXISTS `framework_sample_site`.`user` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(20) NOT NULL,
  `password` VARCHAR(64) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `framework_sample_site`.`admin_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `framework_sample_site`.`admin_user` ;

CREATE TABLE IF NOT EXISTS `framework_sample_site`.`admin_user` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  INDEX `fk_user_id_admin_user_user_id_idx` (`user_id` ASC),
  CONSTRAINT `fk_user_id_admin_user_user_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `framework_sample_site`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `framework_sample_site`.`user_login_history`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `framework_sample_site`.`user_login_history` ;

CREATE TABLE IF NOT EXISTS `framework_sample_site`.`user_login_history` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `logged_in_at` DATETIME NOT NULL,
  `logged_ip` VARCHAR(45) NOT NULL,
  `user_agent` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_user_id_user_login_history_user_id_idx` (`user_id` ASC),
  CONSTRAINT `fk_user_id_user_login_history_user_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `framework_sample_site`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `framework_sample_site`.`session`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `framework_sample_site`.`session` ;

CREATE TABLE IF NOT EXISTS `framework_sample_site`.`session` (
  `session_id` VARCHAR(255) NOT NULL,
  `session_value` TEXT NOT NULL,
  `session_time` INT(11) NOT NULL,
  PRIMARY KEY (`session_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `framework_sample_site`.`facebook_account`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `framework_sample_site`.`facebook_account` ;

CREATE TABLE IF NOT EXISTS `framework_sample_site`.`facebook_account` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `facebook_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_user_id_facebook_account_user_id_idx` (`user_id` ASC),
  UNIQUE INDEX `index3` (`user_id` ASC, `facebook_id` ASC),
  CONSTRAINT `fk_user_id_facebook_account_user_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `framework_sample_site`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
