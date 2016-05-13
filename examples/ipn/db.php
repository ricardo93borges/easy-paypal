<?php

function connect()
{
    $host = "localhost";
    $db = "easypaypal";
    $user = "root";
    $passwd = "root";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $passwd);
        return $pdo;
    } catch (Exception $e) {
        die($e->getMessage());
    }
}

$sqlTableNotification = "CREATE TABLE `notification` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `txn_id` INT UNSIGNED DEFAULT NULL,
    `txn_type` VARCHAR(55) DEFAULT NULL,
    `receiver_email` VARCHAR(127) NOT NULL,
    `payment_status` VARCHAR(17) DEFAULT NULL,
    `pending_reason` VARCHAR(17) DEFAULT NULL,
    `reason_code` VARCHAR(31) DEFAULT NULL,
    `custom` VARCHAR(45) DEFAULT NULL,
    `invoice` VARCHAR(45) DEFAULT NULL,
    `notification` MEDIUMTEXT NOT NULL,
    `hash` CHAR(32) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `hash_UNIQUE` (`hash`),
    KEY `custom` (`custom`,`payment_status`),
    KEY `invoice` (`invoice`,`payment_status`),
    KEY `type` (`txn_type`,`payment_status`),
    KEY `id` (`txn_id`,`payment_status`));";

$sqlTableCustomer = "CREATE TABLE `customer` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `address_country` VARCHAR(64) DEFAULT NULL,
    `address_city` VARCHAR(40) DEFAULT NULL,
    `address_country_code` CHAR(2) DEFAULT NULL,
    `address_name` VARCHAR(128) DEFAULT NULL,
    `address_state` VARCHAR(40) DEFAULT NULL,
    `address_status` VARCHAR(11) DEFAULT NULL,
    `address_street` VARCHAR(200) DEFAULT NULL,
    `address_zip` VARCHAR(20) DEFAULT NULL,
    `contact_phone` VARCHAR(20) DEFAULT NULL,
    `first_name` VARCHAR(64) DEFAULT NULL,
    `last_name` VARCHAR(64) DEFAULT NULL,
    `business_name` VARCHAR(127) DEFAULT NULL,
    `email` VARCHAR(127) NOT NULL,
    `paypal_id` VARCHAR(13) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `email_UNIQUE` (`email` ASC),
    UNIQUE INDEX `paypal_id_UNIQUE` (`paypal_id` ASC));";

$sqlTableTransaction = "CREATE TABLE `transaction` (
	    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	    `invoice` VARCHAR(127) NULL DEFAULT NULL,
	    `custom` VARCHAR(255) NULL DEFAULT NULL,
	    `txn_type` VARCHAR(55) NOT NULL,
	    `txn_id` INT NOT NULL,
	    `payer_id` VARCHAR(13) NOT NULL,
	    `currency` CHAR(3) NOT NULL,
	    `gross` DECIMAL(10,2) NOT NULL,
	    `fee` DECIMAL(10,2) NOT NULL,
	    `handling` DECIMAL(10,2) NULL DEFAULT NULL,
	    `shipping` DECIMAL(10,2) NULL DEFAULT NULL,
	    `tax` DECIMAL(10,2) NULL DEFAULT NULL,
	    `payment_status` VARCHAR(17) NULL DEFAULT NULL,
	    `pending_reason` VARCHAR(17) NULL DEFAULT NULL,
	    `reason_code` VARCHAR(31) NULL DEFAULT NULL,
	    PRIMARY KEY (`id`),
	    INDEX `payer` (`payer_id` ASC, `payment_status` ASC),
	    INDEX `txn` (`txn_id` ASC, `payment_status` ASC),
	    INDEX `custom` (`custom` ASC, `payment_status` ASC),
	    INDEX `invoice` (`invoice` ASC, `payment_status` ASC));";

function createTables($tables){
    try {
        foreach($tables as $table) {
            $pdo = connect();
            $pdo->exec($table);
        }
    }catch (Exception $e){
        die($e->getMessage());
    }
}

createTables(array($sqlTableCustomer, $sqlTableNotification, $sqlTableTransaction));

function insert($table, $params){
    $con = connect();
    $stmt = $con->prepare("INSERT INTO $table VALUES(?, ?)");
    foreach($params as $param) {
        $stmt->bindParam(1, $param);
    }
    $stmt->execute();
}