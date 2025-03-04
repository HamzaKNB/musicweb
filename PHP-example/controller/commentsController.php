<?php
session_start();
include '../database/config.php';

if (!isset($_SESSION['id'])) {
    die("You must be logged in to comment.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['blog_id']) || !isset($_POST['content'])) {
        die("Invalid request.");
    }

    $blog_id = intval($_POST['blog_id']);
    $user_id = $_SESSION['id'];
    $content = trim($_POST['content']);

    if (empty($content)) {
        die("Comment cannot be empty.");
    }

    $stmt = $conn->prepare("INSERT INTO blog_comment (blog_id, user_id, content, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $blog_id, $user_id, $content);

    if ($stmt->execute()) {
        header('Location: blogInfo');
        exit();
    } else {
        die("Error submitting comment.");
    }
}
?>