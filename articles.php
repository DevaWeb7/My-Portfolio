<?php
// نمایش خطاها برای دیباگ
ini_set('display_errors', 1);
error_reporting(E_ALL);

// اتصال به دیتابیس
try {
  $pdo = new PDO("mysql:host=localhost;dbname=devaweb1_articles", "rdevaweb1_devaweboot", "2020131377@@NIni");
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // دریافت همه‌ی مقالات به همراه دسته‌بندی‌ها
  $stmt = $pdo->query("SELECT 
      a.id, 
      a.title, 
      a.author, 
      a.reading_time, 
      a.content, 
      a.likes, 
      a.image, 
      c.name AS category
    FROM articles a
    LEFT JOIN categories c ON a.category_id = c.id
    ORDER BY a.id DESC");

  $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // خروجی JSON
  header('Content-Type: application/json');
  echo json_encode($articles, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (PDOException $e) {
  // در صورت بروز خطا
  http_response_code(500);
  header('Content-Type: application/json');
  echo json_encode(['error' => $e->getMessage()]);
}
?>