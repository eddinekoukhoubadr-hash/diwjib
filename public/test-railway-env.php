<?php
echo "<h1>Railway Environment Test</h1>";

// Test getenv() - comment Railway injecte les variables
echo "APP_ENV: " . (getenv('APP_ENV') ?: 'NOT FOUND') . "<br>";
echo "DB_URI: " . (getenv('DB_URI') ? 'SET (hidden for security)' : 'NOT FOUND') . "<br>";

// Test avec MongoDB utilisant getenv()
try {
    $uri = getenv('DB_URI');
    if (!$uri) {
        throw new Exception("DB_URI not found in environment");
    }
    
    echo "Connecting to MongoDB...<br>";
    $mongo = new MongoDB\Client($uri);
    $db = $mongo->selectDatabase('diwjib');
    $collection = $db->selectCollection('test');
    $result = $collection->insertOne(['test' => 'railway', 'time' => new DateTime()]);
    echo "SUCCESS! Inserted ID: " . $result->getInsertedId() . "<br>";
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>ERROR: " . $e->getMessage() . "</h2>";
}