<?php
echo "<h1>Final Test</h1>";

// Test direct des variables Railway
echo "Testing direct environment...<br>";

$env_vars = [
    'APP_ENV', 'APP_KEY', 'DB_URI', 'DB_CONNECTION'
];

foreach ($env_vars as $var) {
    $value = getenv($var);
    echo "$var: " . ($value ? "SET" : "NULL") . "<br>";
}

// Test si le problème est le service
echo "<br>Service should be: diwjib<br>";
echo "RAILWAY_SERVICE_NAME: " . getenv('RAILWAY_SERVICE_NAME') . "<br>";