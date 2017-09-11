<?php

/*
 * Script para nos conectar á BD world
 */

$host = 'localhost';
$db = 'world';
$user = 'root';
$password = '';
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
];
//try {
    $pdo = new PDO($dsn, $user, $password, $opt);
/*} catch (PDOException $e) {
    echo '<p>No conectado !!</p>';
    echo $e->getMessage();
    exit;
}*/
