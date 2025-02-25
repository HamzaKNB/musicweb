<?php
include 'components/header.php';
?>

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
include 'database/config.php';
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
INNER JOIN user u ON b.author_id=u.id
where b.id = $blogId ");

$blog->execute();
$blog->store_result();
$blog->bind_result($blogTitle,$blogImg,$blogContent,$blogStatus,$created,$firstname,$surname);
$blog->fetch();
?>


<p><?= $blogTitle?></p>

<!-- component -->
<div class="py-16">
  <div class="container m-auto px-6">

   <div class="lg:flex justify-between items-center">
       <div class="lg:w-6/12 lg:p-0 p-7">
          <h1 class="text-4xl font-bold leading-tight mb-5 capitalize"> <?= $blogTitle?> </h1>
          <p class="text-xl">  With Tailwind you can optimized the customization process to save your team time when building websites. </p>

          <div class="py-5">
               <a href="#" class="text-white rounded-full py-2 px-5 text-lg font-semibold bg-purple-600 inline-block border border-purple-600 mr-3">Try for free</a>
               <a href="#" class="text-black rounded-full py-2 px-5 text-lg font-semibold bg-gray-400 inline-block border hover:bg-white hover:text-black">Requist a demo</a>
          </div>

        </div>
        <div class="lg:w-5/12 order-2">
          <img src="<?= $blogImg?>" alt="" class="rounded">
        </div>
    </div>

  </div>
</div>

<?php
include 'components/footer.php';
?>