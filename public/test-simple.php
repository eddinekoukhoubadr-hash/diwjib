<?php
echo "<h1>Laravel Simple Test</h1>";

require __DIR__.'/../vendor/autoload.php';

try {
    $app = require_once __DIR__.'/../bootstrap/app.php';
    
    echo "1. App bootstrap: SUCCESS<br>";
    
    // Initialiser Laravel
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "2. Kernel: SUCCESS<br>";
    
    // Test MongoDB directement
    echo "3. Testing MongoDB connection...<br>";
    $mongo = new MongoDB\Client($_ENV['DB_URI'] ?? 'mongodb://localhost');
    $db = $mongo->selectDatabase('diwjib');
    $collection = $db->selectCollection('test_connection');
    $result = $collection->insertOne(['test' => time()]);
    echo "4. MongoDB: SUCCESS (Inserted ID: " . $result->getInsertedId() . ")<br>";
    
    echo "<h2 style='color: green;'>✅ ALL TESTS PASSED!</h2>";
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>ERROR: " . $e->getMessage() . "</h2>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
}