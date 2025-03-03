<?php

$hn = "localhost";
$un = "hamza_test";
$pw = "Scg)6Ch4S6QcOsnr";
$db = "harmony_hub";

// Create database connection
$conn = new mysqli($hn, $un, $pw, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>