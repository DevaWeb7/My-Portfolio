<?php
$pdo = new PDO("mysql:host=localhost;dbname=articles", "root", "");
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch the article by ID
$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) {
  echo "Article not found.";
  exit;
}

// Fetch categories
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // If new image is uploaded
  $imagePath = $article['image'];
  if (!empty($_FILES['image']['name'])) {
    $target = "uploads/" . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $target);
    $imagePath = $target;
  }

  $update = $pdo->prepare("UPDATE articles SET title=?, author=?, reading_time=?, content=?, category_id=?, image=? WHERE id=?");
  $update->execute([
    $_POST['title'],
    $_POST['author'],
    $_POST['reading_time'],
    $_POST['content'],
    $_POST['category_id'],
    $imagePath,
    $id
  ]);

  header("Location: admin.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Article</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6 font-sans">

  <div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Edit Article</h1>
    
    <form action="" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow space-y-4">
      <input type="text" name="title" class="w-full p-2 border rounded" value="<?= htmlspecialchars($article['title']) ?>" required>
      
      <input type="text" name="author" class="w-full p-2 border rounded" value="<?= htmlspecialchars($article['author']) ?>" required>
      
      <input type="number" name="reading_time" class="w-full p-2 border rounded" value="<?= $article['reading_time'] ?>" required>
      
      <select name="category_id" class="w-full p-2 border rounded">
        <?php foreach ($categories as $cat): ?>
          <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $article['category_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($cat['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      
      <textarea name="content" rows="6" class="w-full p-2 border rounded"><?= htmlspecialchars($article['content']) ?></textarea>

      <div>
        <label class="block mb-1 text-sm font-medium">Current Image:</label>
        <?php if ($article['image']): ?>
          <img src="<?= $article['image'] ?>" alt="Article Image" class="h-32 mb-2">
        <?php else: ?>
          <p class="text-gray-500 text-sm">No image uploaded.</p>
        <?php endif; ?>
        <input type="file" name="image" class="w-full">
      </div>

      <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Save Changes</button>
    </form>
  </div>
</body>
</html>