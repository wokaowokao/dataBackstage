DROP TABLE IF EXISTS `synthesize`;
CREATE TABLE `synthesize` ( `id` INT NOT NULL AUTO_INCREMENT , `date` CHAR(10) NOT NULL , `last` VARCHAR(12) NOT NULL DEFAULT '' , `get` VARCHAR(12) NOT NULL DEFAULT '' , `reduce` VARCHAR(12) NOT NULL DEFAULT '' , PRIMARY KEY (`id`), UNIQUE (`date`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_bin;
ALTER TABLE `baidu_visit`
ADD UNIQUE INDEX `date` (`date`, `type`, `name`) ;
ALTER TABLE `tb_activity`
ADD UNIQUE INDEX `date` (`date`, `name`, `zt_id`, `type`) ;
ALTER TABLE `tb_activity`
MODIFY COLUMN `zt_id`  varchar(255) NOT NULL DEFAULT '' COMMENT '主题id' AFTER `name`;



