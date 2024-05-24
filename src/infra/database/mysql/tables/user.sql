CREATE TABLE `pib-lib`.`user` (
    `id` INT NOT NULL AUTO_INCREMENT , 
    `name` VARCHAR(255) NOT NULL , 
    `password` VARCHAR(64) NULL , 
    `email` VARCHAR(255) NOT NULL , 
    `phone` VARCHAR(20) NOT NULL , 
    `isActive` BOOLEAN NOT NULL , 
    `isAdmin` BOOLEAN NOT NULL , 
    PRIMARY KEY (`id`)
) ENGINE = MyISAM;
