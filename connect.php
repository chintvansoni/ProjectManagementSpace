<?php 
/*****************
    
    Project Management Space
    Name: Chintvan Soni
    Date: November 6 2020
    Description: PHP connection module to database

*****************/

/*const DB_USER = "chintz";
const DB_PASS = "Password01";
const DB_DSN = "mysql:host=localhost;dbname=serverside;charset=utf8";*/

//id13442814_projectspace	id13442814_chintz	localhost

define('DB_DSN','mysql:host=localhost;dbname=projectspace;charset=utf8');
define('DB_USER','chintz');
define('DB_PASS','Password01');

try {
    $db = new PDO(DB_DSN, DB_USER, DB_PASS);
} catch (PDOException $e) {
    print "Error: " . $e->getMessage();
    die(); // Force execution to stop on errors.
}

?>