<?php
include 'database/config.php';
session_start();

// Input sanitization, taking away any spaces
$firstname = trim($_POST['firstname']);
$surname = trim($_POST['surname']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);
$created_on = trim($_POST['created_on']);
$role = trim($_POST['role']);


// Validate username (alphanumeric)
if (!preg_match('/^[a-zA-Z0-9]+$/', $firstname)) {
    $_SESSION['status_message'] = 'firstname is not valid! Only alphanumeric characters are allowed.';
    header('Location: register');
    exit();
}
if (!preg_match('/^[a-zA-Z0-9]+$/', $surname)) {
    $_SESSION['status_message'] = 'surname is not valid! Only alphanumeric characters are allowed.';
    header('Location: register');
    exit();
}

// Validate password (between 5 and 20 characters)
if (strlen($password) < 5 || strlen($password) > 20) {
    $_SESSION['status_message'] = 'Password must be between 5 and 20 characters long!';
    header('Location: register');
    exit();
}

// Validate email (basic validation, can be expanded)
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['status_message'] = 'Invalid email format!';
    header('Location: register');
    exit();
}

// Check if the email already exists in the database
$stmt = $conn->prepare('SELECT id FROM user WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Username already exists
    $_SESSION['status_message'] = 'Email address already exists! Please login.';
    $stmt->close();
    header('Location: login');
    exit();
} else {
    $stmt->close();

    // email doesn't exist, insert new account
    $stmt = $conn->prepare("INSERT INTO user (firstname, surname, email, password, created_on, role) VALUES (?, ?, ?, ?, NOW(), 'user')");
    
    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Bind parameters and execute the query
    if ($stmt) {
        $stmt->bind_param('ssss', $firstname, $surname, $email, $hashed_password);
        $stmt->execute();

        // If account creation is successful
        if ($stmt->affected_rows > 0) {
            $_SESSION['status_message'] = 'Account successfully created! You can now log in.';
            header('Location: login');
        } else {
            $_SESSION['status_message'] = 'Account creation failed. Please try again later.';
            header('Location: register');
        }

        $stmt->close();
    } else {
        $_SESSION['status_message'] = 'Database error. Please try again later.';
        header('Location: register');
    }

    $conn->close();
    exit();
}
?>