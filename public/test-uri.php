<?php
echo "<h1>MongoDB URI Test</h1>";

require __DIR__.'/../vendor/autoload.php';

try {
    $app = require_once __DIR__.'/../bootstrap/app.php';
    
    // Test 1: Voir l'URI
    $uri = $_ENV['DB_URI'] ?? 'NOT SET';
    echo "DB_URI: " . htmlspecialchars($uri) . "<br>";
    
    // Test 2: Voir si .env est lu
    echo "APP_ENV from env: " . ($_ENV['APP_ENV'] ?? 'NOT SET') . "<br>";
    
    // Test 3: Test de connexion direct
    echo "Testing connection...<br>";
    $mongo = new MongoDB\Client($uri);
    $db = $mongo->selectDatabase('diwjib');
    $collection = $db->selectCollection('test');
    $result = $collection->insertOne(['test' => 'connection', 'time' => new DateTime()]);
    echo "SUCCESS! Inserted ID: " . $result->getInsertedId() . "<br>";
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>ERROR: " . $e->getMessage() . "</h2>";
}