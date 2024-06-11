<?php
$dbhost = "localhost";
$dbname = "carpool_ifa_mns";
$username = "root";
$password = "";

try {
    $db = new PDO("mysql:host=" . $dbhost . ";dbname=" . $dbname . ";charset=utf8", $username, $password);
} catch (Exception $e) {
    die("Erreur :" . $e->getMessage());
}