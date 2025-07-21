<?php
$pdo = new PDO("mysql:host=localhost;dbname=articles", "root", "");

// Image Upload
$target = "uploads/" . basename($_FILES['image']['name']);
move_uploaded_file($_FILES['image']['tmp_name'], $target);

// Insert Article
$stmt = $pdo->prepare("INSERT INTO articles (title, author, reading_time, content, category_id, image) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->execute([
  $_POST['title'],
  $_POST['author'],
  $_POST['reading_time'],
  $_POST['content'],
  $_POST['category_id'],
  $target
]);

header("Location: admin.php");
?>