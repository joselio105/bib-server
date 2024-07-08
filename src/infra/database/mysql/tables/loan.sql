CREATE TABLE `loan` (
    `id` INT NOT NULL AUTO_INCREMENT , 
    `copyId` INT NOT NULL , 
    `userId` INT NOT NULL , 
    `loannedAt` DATETIME NOT NULL , 
    `returnAt` DATETIME NOT NULL , 
    `returnedAt` DATETIME NULL , 
    `createdAt` DATETIME NOT NULL , 
    `createdBy` INT NOT NULL , 
    `updatedAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
    `updatedBy` INT NOT NULL , 
    PRIMARY KEY (`id`)
) ENGINE = MyISAM;
