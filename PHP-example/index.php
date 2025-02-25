<!-- Create routing for application -->
<?php
// Get the requested URL from the 'url' query paramete
$url =isset($_GET['url'])? rtrim($_GET['url'],'/'): "";

// Define available routes (URL => corresponding PHP file)
$routes = [    
    '' => 'pages/index.php',
    'home' => 'pages/index.php',           // Home route
    'contact' => 'pages/contact.php',          // contact route
    'register' => 'pages/register.php',    // register page route

    'login' => 'pages/login.php', // login page route
    'blog' => 'pages/blog.php', // blog page route
    'admin' => 'pages/admin/dashboardAdmin.php', // admin page route
    'dashboard' => 'pages/dashboard.php', // user page route
    'blogInfo'=> 'pages/BlogInfo/blogInfo.php',


//configuration files

'registerController' => 'controller/registerController.php',
'loginController' => 'controller/loginController.php',
'logoutController' => 'controller/logoutController.php',
];


//Check if the UR matches a route

if (array_key_exists($url, $routes)){
    require $routes[$url];  // Load the appropriate file for the route
} else {
    // If no route matches, show a 404 page
    require 'pages/error_404.php';
}
?>











    
