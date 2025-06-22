<?php
$host = 'localhost';
$port = '5432';
$dbname = 'storageBG';
$user = 'postgres';
$password = '123456';

try {
$pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
die("Erro ao conectar ao banco: " . $e->getMessage());
}
?>
