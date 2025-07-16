<?php
// Simple test to check if Laravel is working
echo "Testing Laravel Bootstrap...\n";

try {
    require_once __DIR__.'/../vendor/autoload.php';
    echo "Autoload successful\n";
    
    $app = require_once __DIR__.'/../bootstrap/app.php';
    echo "App created successfully\n";
    
    // Test database connection
    $pdo = new PDO('mysql:host=db;dbname=laravel', 'laravel', 'laravel');
    echo "Database connection successful\n";
    
    // Test simple response
    echo "Laravel is working!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
