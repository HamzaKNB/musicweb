<?php
session_start();
include '../../database/config.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $blog_id = $_POST['blog_id'];
    $user_id = $_SESSION['id'];
    $content = trim($_POST['content']);

    if (empty($content)) {
        die("Comment cannot be empty.");
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO blog_comment (blog_id, user_id, content, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $blog_id, $user_id, $content);

    if ($stmt->execute()) {
        header('Location: ../../pages/BlogInfo/bloginfo.php');
        exit();
    } else {
        die("Error posting comment.");
    }
}
?>
