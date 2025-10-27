<?php
echo "<h1>Environment Debug</h1>";

// Test 1: Fichier .env existe?
echo ".env exists: " . (file_exists(__DIR__.'/../.env') ? 'YES' : 'NO') . "<br>";

// Test 2: Variables serveur
echo "RAILWAY ENV: " . ($_SERVER['APP_ENV'] ?? 'NOT IN SERVER') . "<br>";

// Test 3: getenv()
echo "getenv APP_ENV: " . (getenv('APP_ENV') ?: 'NOT IN GETENV') . "<br>";

// Test 4: phpinfo
if (isset($_GET['info'])) {
    phpinfo();
    exit;
}