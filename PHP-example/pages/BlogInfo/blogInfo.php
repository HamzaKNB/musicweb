<?php
include '../../components/header.php';
include '../../database/config.php';

// Check if 'bid' is set
if (!isset($_GET['bid']) || empty($_GET['bid'])) {
    die("Invalid Blog ID.");
}

// Sanitize and bind 'bid' to prevent SQL injection
$blogId = $_GET['bid'];

$blog = $conn->prepare("SELECT 
    b.title, 
    b.image_url, 
    b.content, 
    b.status, 
    b.created_at, 
    u.firstname, 
    u.surname 
FROM blog b 
INNER JOIN user u ON b.author_id = u.id 
WHERE b.id = ?");

$blog->bind_param("i", $blogId);
$blog->execute();
$blog->store_result();

// Check if the blog exists
if ($blog->num_rows == 0) {
    die("Blog post not found.");
}

$blog->bind_result($blogTitle, $blogImg, $blogContent, $blogStatus, $created, $firstname, $surname);
$blog->fetch();

if (!isset($_GET['bid'])) {
  die("Blog ID is missing.");
}
$blogId = intval($_GET['bid']); // Ensure it's an integer

// Fetch comments
$stmt = $conn->prepare("SELECT c.content, c.created_at, u.firstname, u.surname 
                      FROM blog_comment c 
                      INNER JOIN user u ON c.user_id = u.id 
                      WHERE c.blog_id = ? 
                      ORDER BY c.created_at DESC");
$stmt->bind_param("i", $blogId);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($blogTitle) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

<!-- Blog Title -->
<div class="py-16">
  <div class="container m-auto px-6">
    <div class="lg:flex justify-between items-center">
      <div class="lg:w-6/12 lg:p-0 p-7">
        <h1 class="text-4xl font-bold leading-tight mb-5 capitalize"><?= htmlspecialchars($blogTitle) ?></h1>
        <p class="text-gray-600 text-sm mb-3">By <?= htmlspecialchars($firstname) ?> <?= htmlspecialchars($surname) ?> | <?= date("F j, Y", strtotime($created)) ?></p>
        <p class="text-xl"><?= nl2br(htmlspecialchars($blogContent)) ?></p>

        <div class="py-5">
          <a href="index.php" class="text-white rounded-full py-2 px-5 text-lg font-semibold bg-purple-600 inline-block border border-purple-600 mr-3">Back to Home</a>
        </div>
      </div>
      <div class="lg:w-5/12 order-2">
        <img src="<?= htmlspecialchars($blogImg) ?>" alt="Blog Image" class="rounded w-full">
      </div>
    </div>
  </div>
</div>


<div class="py-10">
    <h2 class="text-2xl font-semibold mb-5">Comments</h2>

    <?php if (isset($_SESSION['id'])): ?>
      <form method="post" action="../../controller/commentsController.php">
            <input type="hidden" name="blog_id" value="<?= $blogId ?>">
            <textarea name="content" class="w-full p-3 border rounded" placeholder="Write a comment..." required></textarea>
            <button type="submit" class="mt-3 bg-blue-600 text-white px-5 py-2 rounded">Submit</button>
        </form>
    <?php else: ?>
        <p class="text-red-500">You must be logged in to comment.</p>
    <?php endif; ?>

    <div class="mt-5">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="p-4 border-b">
                <p class="font-semibold"><?= htmlspecialchars($row['firstname']) ?> <?= htmlspecialchars($row['surname']) ?></p>
                <p class="text-gray-600"><?= nl2br(htmlspecialchars($row['content'])) ?></p>
                <p class="text-xs text-gray-400"><?= date("F j, Y, g:i a", strtotime($row['created_at'])) ?></p>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include '../../components/footer.php'; ?>

</body>
</html>
