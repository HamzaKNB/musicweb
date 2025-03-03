<?php
// Start a session
session_start();

// Include the database configuration file
require '../database/config.php';

// Check if the login form was submitted with both email and password fields filled
if (empty(trim($_POST['email'])) || empty(trim($_POST['password']))) {
    // Set a session error message and redirect to the login page if any field is empty
    $_SESSION['status_message'] = 'Please fill both the username and password fields!';
    header('Location: ../pages/login.php');
    exit();
}

// Prepare an SQL statement to prevent SQL injection when checking user credentials
if ($stmt = $conn->prepare('SELECT id, password, role FROM user WHERE email = ?')) {
    // Bind the input email parameter to the SQL query and execute the statement
    $email = trim($_POST['email']);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result(); // Store the result to check if the email exists

    // If the email exists, bind the result to check variables and fetch data
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password, $role);
        $stmt->fetch();

        // Verify the entered password against the hashed password stored in the database
        if (password_verify($_POST['password'], $password)) {
            // Password is correct, regenerate session ID for security
            session_regenerate_id();
            $_SESSION['loggedin'] = true;
            $_SESSION['email'] = $email; // Store the user email in session
            $_SESSION['id'] = $id; // Store the user ID in session
            $_SESSION['role'] = $role; // Store user role (admin or regular user)

            // Set a secure cookie with the email (HTTP only and Secure flag enabled)
            setcookie("email", $email, time() + 86400, "/", "", true, true);

            // Redirect user based on their role
            if ($role == 'admin') {
                header('Location: ../pages/admin/dashboardAdmin.php'); // Redirect admin to admin dashboard
            } else {
                header('Location: ../pages/dashboard.php'); // Redirect regular users to user dashboard
            }
            exit();
        } else {
            // If password is incorrect, set an error message and redirect back to login page
            $_SESSION['status_message'] = 'Incorrect email or password!';
            header('Location: ../pages/login.php');
            exit();
        }
    } else {
        // If email does not exist, set an error message and redirect to login page
        $_SESSION['status_message'] = 'Incorrect email or password!';
        header('Location: ../pages/login.php');
        exit();
    }

    // Close the prepared statement after execution
    $stmt->close();
} else {
    // If preparing the SQL statement fails, set an error message and redirect to login page
    $_SESSION['status_message'] = 'Login system error. Please try again later.';
    header('Location: ../pages/login.php');
    exit();
}
?>


