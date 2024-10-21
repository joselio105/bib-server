CREATE TABLE `copy` (
    `id` INT NOT NULL AUTO_INCREMENT , 
    `publicationId` INT NOT NULL , 
    `registrationCode` VARCHAR(15) NOT NULL , 
    `createdAt` DATETIME NOT NULL , 
    `createdBy` INT NOT NULL , 
    `updatedAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
    `updatedBy` INT NOT NULL , 
    PRIMARY KEY (`id`)
) ENGINE = MyISAM;
