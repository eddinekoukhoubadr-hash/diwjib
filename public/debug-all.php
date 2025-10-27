<?php
echo "<h1>Railway Debug - ALL Variables</h1>";

echo "<h3>getenv():</h3>";
echo "APP_ENV: " . (getenv('APP_ENV') ?: 'NULL') . "<br>";
echo "DB_URI: " . (getenv('DB_URI') ? 'SET' : 'NULL') . "<br>";

echo "<h3>$_ENV:</h3>";
echo "APP_ENV: " . ($_ENV['APP_ENV'] ?? 'NULL') . "<br>";
echo "DB_URI: " . (isset($_ENV['DB_URI']) ? 'SET' : 'NULL') . "<br>";

echo "<h3>$_SERVER:</h3>";
echo "APP_ENV: " . ($_SERVER['APP_ENV'] ?? 'NULL') . "<br>";
echo "DB_URI: " . (isset($_SERVER['DB_URI']) ? 'SET' : 'NULL') . "<br>";

echo "<h3>phpinfo():</h3>";
echo "<a href='?info=1'>Show phpinfo()</a>";

if (isset($_GET['info'])) {
    phpinfo();
}