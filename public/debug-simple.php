<?php
echo "<h1>Laravel Debug</h1>";

// Test 1: PHP info
echo "PHP Version: " . PHP_VERSION . "<br>";

// Test 2: Extensions
echo "MongoDB Extension: " . (extension_loaded('mongodb') ? 'LOADED' : 'NOT LOADED') . "<br>";

// Test 3: Environment
echo "APP_ENV: " . ($_ENV['APP_ENV'] ?? 'NOT SET') . "<br>";

// Test 4: Basic Laravel
try {
    require __DIR__.'/../vendor/autoload.php';
    $app = require_once __DIR__.'/../bootstrap/app.php';
    echo "Laravel Boot: SUCCESS<br>";
} catch (Exception $e) {
    echo "Laravel Error: " . $e->getMessage() . "<br>";
}