<?php
// Test database connection without Laravel
echo "Testing database connection...\n";

try {
    $pdo = new PDO('mysql:host=db;dbname=laravel', 'laravel', 'laravel');
    echo "Database connection successful\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $count = $stmt->fetchColumn();
    echo "Users count: " . $count . "\n";
    
} catch (Exception $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}

echo "Done\n";
?>
