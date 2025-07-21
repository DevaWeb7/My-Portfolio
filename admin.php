<?php
$pdo = new PDO("mysql:host=localhost;dbname=articles", "root", "");

$articles = $pdo->query("SELECT a.*, c.name AS category FROM articles a LEFT JOIN categories c ON a.category_id = c.id ORDER BY a.id DESC")->fetchAll();
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6 font-sans">
  <div class="max-w-5xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>

    <!-- Add Article Form -->
    <div class="bg-white p-4 rounded-lg shadow mb-10">
      <h2 class="text-xl font-semibold mb-4">Add New Article</h2>
      <form action="upload_article.php" method="POST" enctype="multipart/form-data" class="space-y-4">
        <input type="text" name="title" placeholder="Title" class="w-full p-2 border rounded" required>
        <input type="text" name="author" placeholder="Author" class="w-full p-2 border rounded" required>
        <input type="number" name="reading_time" placeholder="Reading Time (min)" class="w-full p-2 border rounded" required>
        <select name="category_id" class="w-full p-2 border rounded">
          <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
          <?php endforeach; ?>
        </select>
        <textarea name="content" placeholder="Content" class="w-full p-2 border rounded" rows="5"></textarea>
        <input type="file" name="image" class="w-full">
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Upload Article</button>
      </form>
    </div>

    <!-- List Articles -->
    <div class="bg-white p-4 rounded-lg shadow">
      <h2 class="text-xl font-semibold mb-4">Articles</h2>
      <table class="w-full table-auto border-collapse">
        <thead>
          <tr class="bg-gray-200">
            <th class="p-2 text-left">Title</th>
            <th class="p-2">Author</th>
            <th class="p-2">Category</th>
            <th class="p-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($articles as $a): ?>
            <tr class="border-t">
              <td class="p-2"><?= htmlspecialchars($a['title']) ?></td>
              <td class="p-2"><?= htmlspecialchars($a['author']) ?></td>
              <td class="p-2"><?= htmlspecialchars($a['category']) ?></td>
              <td class="p-2 flex gap-2">
                <a href="edit_article.php?id=<?= $a['id'] ?>" class="text-blue-600 hover:underline">Edit</a>
                <a href="delete_article.php?id=<?= $a['id'] ?>" class="text-red-600 hover:underline" onclick="return confirm('Are you sure you want to delete this?');">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>