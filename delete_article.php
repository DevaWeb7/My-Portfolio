<?php
$pdo = new PDO("mysql:host=localhost;dbname=articles", "root", "");
$id = $_GET['id'];
$pdo->prepare("DELETE FROM articles WHERE id=?")->execute([$id]);
header("Location: admin.php");
?>