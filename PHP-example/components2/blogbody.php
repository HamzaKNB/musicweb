<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<?php
include '../database/config.php';
$blog = $conn->prepare("SELECT
    id,
    title,
    image_url,
    content,
    status
FROM blog
WHERE status = 'published' ");
$blog->execute();
$blog->store_result();
$blog->bind_result($blogId,$blogTitle,$blogImg,$blogContent,$status);
?>

<div class="bg-gray-100 md:px-10 px-4 py-12 font-[sans-serif]">
      <div class="max-w-5xl max-lg:max-w-3xl max-sm:max-w-sm mx-auto">
        <h2 class="text-3xl font-extrabold text-gray-800 mb-8">Latest Blog Posts</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-sm:gap-8">
          <?php while ($blog->fetch()) : ?>
          <div class="bg-white rounded overflow-hidden">
            <img src="<?= $blogImg?> " alt="Blog Post 1" class="w-full h-52 object-cover" />
            <div class="p-6">
              <h3 class="text-lg font-bold text-gray-800 mb-3"><?= $blogTitle?> 
              <p class="text-gray-500 text-sm">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore...</p>
              <p class="text-orange-500 text-[13px] font-semibold mt-4">08 April 2024</p>
              <a href="../pages/BlogInfo/blogInfo.php?bid=<?=$blogId?>" class="mt-4 inline-block px-4 py-2 rounded tracking-wider bg-orange-500 hover:bg-orange-600 text-white text-[13px]">Read More</a>
            </div>
          </div>
          <?php endwhile ?>
          </div>
        </div>
      </div>
    </div>
</body>
</html>