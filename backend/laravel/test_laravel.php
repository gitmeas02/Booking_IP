<?php
// Test Laravel application
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

// Check if Laravel is working
echo "Laravel Version: " . $app::VERSION . "\n";
echo "Environment: " . $app->environment() . "\n";

// Test routing
try {
    $router = $app->make('router');
    echo "Router loaded successfully\n";
} catch (Exception $e) {
    echo "Router error: " . $e->getMessage() . "\n";
}

// Test if routes are registered
try {
    $routes = $app->make('router')->getRoutes();
    echo "Routes loaded: " . count($routes) . "\n";
} catch (Exception $e) {
    echo "Routes error: " . $e->getMessage() . "\n";
}
