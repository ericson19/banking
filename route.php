<?php

// Define your base path (only if your app is in a subfolder)
$basePath = '/bank'; // Change to your actual folder name

// Get the requested URI
$uri = str_replace($basePath, '', $_SERVER['REQUEST_URI']);
$uri = parse_url($uri, PHP_URL_PATH);
$route = [
    '/dashboard' => 'view/dashboard.php',
    '/login' => 'auth/login.php',
    '/loan' => 'view/loan.php',
    '/transfer' => '/payment/inbank.php'
];
// $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
echo 'current url:' . $uri;

if (array_key_exists($uri, $route)) {
    require $route[$uri];
} else {
    http_response_code(404);
    echo "<h1>404 - Page Not Found</h1>";
}
