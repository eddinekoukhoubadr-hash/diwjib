<?php
require __DIR__.'/../vendor/autoload.php';

echo "<h1>Test MongoDB Connection</h1>";

try {
    // Test 1: Chargement des variables d'environnement
    echo "APP_ENV: " . ($_ENV['APP_ENV'] ?? 'NOT SET') . "<br>";
    echo "DB_CONNECTION: " . ($_ENV['DB_CONNECTION'] ?? 'NOT SET') . "<br>";
    
    // Test 2: Connexion MongoDB directe
    $mongo = new MongoDB\Client($_ENV['DB_URI'] ?? 'mongodb://localhost');
    $db = $mongo->selectDatabase($_ENV['DB_DATABASE'] ?? 'diwjib');
    $collection = $db->selectCollection('test');
    
    // Test 3: Insertion et lecture
    $result = $collection->insertOne(['test' => 'connection', 'time' => new MongoDB\BSON\UTCDateTime()]);
    echo "MongoDB Connection: SUCCESS (Insert ID: " . $result->getInsertedId() . ")<br>";
    
    // Test 4: Lecture
    $document = $collection->findOne(['_id' => $result->getInsertedId()]);
    echo "MongoDB Read: SUCCESS<br>";
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>ERROR: " . $e->getMessage() . "</h2>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
}