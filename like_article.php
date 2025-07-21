<?php
$pdo = new PDO("mysql:host=localhost;dbname=articles", "root", "");
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("UPDATE articles SET likes = likes + 1 WHERE id = ?");
$stmt->execute([$id]);

$stmt = $pdo->prepare("SELECT likes FROM articles WHERE id = ?");
$stmt->execute([$id]);
$likes = $stmt->fetchColumn();

header('Content-Type: application/json');
echo json_encode(['likes' => $likes]);