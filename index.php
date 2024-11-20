<?php

require "vendor/autoload.php";
require "init.php";

// Database connection object (from init.php (DatabaseConnection))
global $conn;

try {



    // Create Router instance
    $router = new \Bramus\Router\Router();

    // Define routes
    $router->get('/registration-form', '\App\Controllers\RegistrationController@showRegisterForm');
    $router->post('/register', '\App\Controllers\RegistrationController@register');

    $router->get('/login-form', '\App\Controllers\LoginController@showLoginForm');
    $router->post('/login', '\App\Controllers\LoginController@login');
    $router->get('/welcome', '\App\Controllers\HomeController@welcome');
    $router->get('/logout', '\App\Controllers\LoginController@logout');


    $router->get('/', '\App\Controllers\PageController@dashboard');

    $router->get('/documentation', '\App\Controllers\PageController@documentation');


    $router->get('/manage-products', '\App\Controllers\PageController@manageProducts');

    $router->get('/categories', '\App\Controllers\PageController@categories');
    $router->post('/add-category', '\App\Controllers\PageController@addCategory');

    
  
    $router->get('/edit-category', '\App\Controllers\PageController@editCategory');  // For GET requests
    $router->post('/edit-category', '\App\Controllers\PageController@editCategory'); // For POST requests
    $router->get('/delete-category', '\App\Controllers\PageController@deleteCategory'); // For DELETE requests
    
    


    // Run it!
    $router->run();

} catch (Exception $e) {

    echo json_encode([
        'error' => $e->getMessage()
    ]);

}
