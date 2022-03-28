<?php

/**
 * User: Celio Natti
 * Date: 2/23/2022
 * Time: 8:07 AM
 */

class m0001_initial
{
    public function up()
    {
        $db = \natoxCore\Application::$app->db;
        $SQL = "CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                created_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NULL,
                fname VARCHAR(255) NULL,
                lname VARCHAR(255) NULL,
                email VARCHAR(255) NULL,
                password VARCHAR(255) NULL,
                acl VARCHAR(100) NULL DEFAULT 'Guests',
                blocked TINYINT(1) NOT NULL DEFAULT '0',
                img VARCHAR(255) NULL,
                verified TINYINT(1) NOT NULL DEFAULT '0',
                ip VARCHAR(50) NULL,
                2fa_code VARCHAR(6) NULL,
                pin VARCHAR(4) NULL,
                status TINYINT DEFAULT 0,
                INDEX email (`email`)
            )  ENGINE=INNODB;";
        $db->_dbh->exec($SQL);
    }

    public function down()
    {
        $db = \natoxCore\Application::$app->db;
        $SQL = "DROP TABLE users;";
        $db->_dbh->exec($SQL);
    }
}
