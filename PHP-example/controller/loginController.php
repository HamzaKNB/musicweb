<?php
//start a seassion and include the database configuartion file to establish a database connection
require 'databse/config.php';
session_start();

//check iif the login form was submitted with both email and password fileds filled 
if (empty($_POST['email']) || empty($_POST['password'])) {
// set a session error message and redirect to the loign pageif any filed is empty 
$_SESSION['status_message'] = 'Please fill both the username and password fields!';
header('location: login');
exit();
}
//prepare an SQL statemnet to prevent SQL injection when checking user credentials
if ($stmt = $conn->prepare('SELECT id, password, role FROM user WHERE email = ?')) {
//Bind the input email parameter to the SQL query and execute the statement 
$stmt->bind_param('s', $_POST['email']);
$stmt->execute();  
$stmt->store_result(); //Store the result to check if the email exists

// if the email exists, bind the result to check variables and fetch data

if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $password, $role);
    $stmt->fetch();
    
// verify the entered password against the hashed password stored in the database
if (password_verify($_POST['password'], $password)) {

// password is correct, regenerate session ID for security 
session_regenerate_id();
            $_SESSION['loggedin'] = true;
            $_SESSION['email'] = $_POST['email']; // Store the user email in session 
            $_SESSION['id'] = $id;// Store the user ID session 
            $_SESSION['role'] = $role;// store user role (admin or regular user)
// set a secure coockie with the email (HTTP only and secure flag enabled)
setcookie("email", $_POST['email'], time() + 86400, "/", "", true, true);
//Redirect user based on their role
if ($role == 'admin') {
    header('Location: pages/admin/dashboardAdmin'); //redirect  admin to admin dashboard
} else {  
    header('Location: pages/dashboard'); // Redirect regular users to user dashboard
}
exit();
} else {
// If password is incorrect, set an error message and redirect back to login page
$_SESSION['status_message'] = 'Incorrect email or password!';
header('Location: login');
exit();
}
} else {
// If email does not exist, set an error message and redirect to login page
$_SESSION['status_message'] = 'Incorrect email or password';
header('Location: login');
exit();
}

// Close the prepared statement after execution
$stmt->close();
} else {
// If preparing the SQL statement fails, set an error message and redirect to login page
$_SESSION['status_message'] = 'Login system error. Please try again later.';
header('Location: login');
exit();
}
?>



