<?php
echo "<h1>Laravel Test</h1>";

require __DIR__.'/../vendor/autoload.php';

try {
    $app = require_once __DIR__.'/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    
    echo "Laravel Kernel: SUCCESS<br>";
    
    // Test de la configuration
    $config = $app->make('config');
    echo "APP_NAME: " . $config->get('app.name') . "<br>";
    echo "APP_ENV: " . $config->get('app.env') . "<br>";
    
    // Test de la base de données
    try {
        \DB::connection()->getPdo();
        echo "Database: CONNECTED<br>";
    } catch (Exception $e) {
        echo "Database Error: " . $e->getMessage() . "<br>";
    }
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>LARAVEL ERROR: " . $e->getMessage() . "</h2>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
}