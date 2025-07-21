<?php
$pdo = new PDO("mysql:host=localhost;dbname=articles", "root", "");
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT a.*, c.name AS category FROM articles a LEFT JOIN categories c ON a.category_id = c.id WHERE a.id = ?");
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) {
  echo "Article not found.";
  exit;
}

$more = $pdo->query("SELECT id, title, image FROM articles WHERE id != $id ORDER BY id DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en" class="transition duration-300">
<head>
  <meta charset="UTF-8" />
  <title><?= htmlspecialchars($article['title']) ?> | Queen Portfolio</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Lora&display=swap" rel="stylesheet" />
   <link rel="stylesheet" href="./style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>

    <!-- CSS -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/locomotive-scroll@4.1.4/dist/locomotive-scroll.min.css"
    />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kalam:wght@300;400;700&family=Libertinus+Mono&display=swap" rel="stylesheet">
    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/locomotive-scroll@4.1.4/dist/locomotive-scroll.min.js"></script>

    <!-- GSAP Core -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

    <!-- ScrollTrigger Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <title>Document</title>
  </head>
  <body>
    <section class="nav-wrapper">
      <div class="navbar">
        <div class="logo">BRW BAR inc.</div>
        <nav class="menu">
           <a href="./index.html">Home</a>
          <a href="./index.html#about">About</a>
          <a href="./index.html#skills">Skills</a>
          <a href="./index.html#portfolio">Portfolio</a>
          <a href="./index.html#contact">Contact</a>
        </nav>


        <!-- <div class="icons">
          <a href="./blog-page.php">Blog</a>
          <span class="cart">🐻‍❄️ྀིྀི</span>
        </div> -->
      </div>
    </section>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #fff1f3, #fae6f1);
      color: #1f2937;
    }
    .dark body {
      background: linear-gradient(135deg, #1e1b4b, #0f0f2d);
      color: #fdf4ff;
    }
    .glass {
      background: rgba(255,255,255,0.65);
      backdrop-filter: blur(10px);
      border-radius: 1rem;
      box-shadow: 0 6px 20px rgba(0,0,0,0.08);
      transition: transform 0.3s ease;
    }
    .glass:hover { transform: translateY(-2px); }
    .fadeIn {
      opacity: 0;
      transform: scale(0.95) translateY(20px);
    }
    .font-serif { font-family: 'Lora', serif; }
  </style>
</head>
<body class="p-6 transition duration-300">

<!-- 🌗 Theme Toggle -->
<button onclick="toggleDark()" id="themeToggle" class="fixed top-5 right-5 p-2 rounded-full shadow bg-pink-200 hover:bg-pink-300 dark:bg-yellow-300 text-purple-900 dark:text-yellow-800 z-50 text-lg">
  🌙
</button>

<div class="max-w-screen-xl mx-auto grid lg:grid-cols-[2fr_1fr] gap-12 fadeIn">

  <!-- 📝 Main Article -->
  <div class="space-y-10">
    <div class="glass p-6">
      <?php if ($article['image']): ?>
        <img src="<?= $article['image'] ?>" class="w-full aspect-square object-cover rounded-xl mb-6 shadow" alt="Article Image">
      <?php endif; ?>
      <h1 class="text-4xl font-semibold mb-3"><?= htmlspecialchars($article['title']) ?></h1>
      <div class="text-sm text-purple-600 dark:text-pink-100 mb-4 flex flex-wrap gap-4">
        <span>👑 <?= htmlspecialchars($article['author']) ?></span>
        <span>⏱️ <?= $article['reading_time'] ?> min</span>
        <?php if ($article['category']): ?>
          <span>📂 <?= htmlspecialchars($article['category']) ?></span>
        <?php endif; ?>
        <span>📅 <?= date("Y/m/d", strtotime($article['created_at'] ?? "now")) ?></span>
      </div>
      <div class="font-serif leading-relaxed text-gray-800 dark:text-purple-100"><?= nl2br(htmlspecialchars($article['content'])) ?></div>

      <!-- ❤️ Like -->
      <div class="mt-6 text-red-500 flex items-center gap-2 text-lg cursor-pointer" onclick="likeArticle()">
        ❤️ <span id="likeCount"><?= $article['likes'] ?></span>
      </div>
    </div>
  </div>

  <!-- 📚 More Articles Sidebar -->
  <div class="space-y-6 fadeIn">
    <h2 class="text-xl font-semibold">More Articles</h2>
    <div class="flex flex-col gap-4">
      <?php foreach ($more as $item): ?>
      <a href="article.php?id=<?= $item['id'] ?>" class="glass p-3 hover:shadow-lg transition">
        <div class="flex gap-3 items-center">
          <?php if ($item['image']): ?>
            <img src="<?= $item['image'] ?>" class="w-16 h-16 object-cover rounded-lg" alt="Thumbnail">
          <?php endif; ?>
          <div class="text-sm text-purple-700 dark:text-pink-100 font-medium"><?= htmlspecialchars($item['title']) ?></div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>

</div>

<!-- 🌐 Explore Button -->
<a href="articles.html" class="fixed bottom-6 right-6 bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-full shadow text-sm font-medium z-40">
  Explore More →
</a>
 <footer>
  <div class="container footer-content">
    
    <div class="address">
      <p class="phone"><i class="fa fa-phone" aria-hidden="true"></i> Phone:<br><span>09902109461</span></p>

        <br>
      <p  class="phone"><i class="fa fa-map-marker" aria-hidden="true"></i>
        Location : IRAN ,Tehran</p>
    </div>
    <div class="footer-menu">
       <a href="./index.html" class="footer-link">Home</a>
      <a href="./index.html#about" class="footer-link">About</a>
      <a href="./index.html#skills" class="footer-link">Skills</a>
      <a href="./index.html#portfolio" class="footer-link">Portfolio</a>
    </div>
    <div class="media">
      <a href="#" class="social-media"><i class="fa fa-instagram"></i></a>
      <a href="#" class="social-media"><i class="fa fa-linkedin"></i></a>
      <a href="#" class="social-media"><i class="fa fa-github"></i></a>
      <a href="#" class="social-media"><i class="fa fa-medium" aria-hidden="true"></i>
      </a>
    </div>
   <div class="line"></div>
    <p class="copy-right">© 2025 Devaweb. All rights reserved.</p>

  </div>
</footer>

<!-- ✨ Scripts -->
<script>
  function likeArticle() {
    fetch("like_article.php?id=<?= $article['id'] ?>")
      .then(res => res.json())
      .then(data => {
        document.getElementById('likeCount').innerText = data.likes;
      });
  }

  function toggleDark() {
    const root = document.documentElement;
    root.classList.toggle('dark');
    const icon = document.getElementById("themeToggle");
    icon.innerText = root.classList.contains('dark') ? '☀️' : '🌙';
  }

  gsap.registerPlugin(ScrollTrigger);
  gsap.utils.toArray('.fadeIn').forEach((el, i) => {
    gsap.to(el, {
      y: 0,
      scale: 1,
      opacity: 1,
      duration: 0.9,
      delay: i * 0.15,
      ease: "power3.out",
      scrollTrigger: {
        trigger: el,
        start: "top 90%",
        toggleActions: "play none none reverse"
      }
    });
  });
</script>
</body>
</html>