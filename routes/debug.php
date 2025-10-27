<?php
use Illuminate\Support\Facades\Route;

Route::get('/debug', function () {
    try {
        // Test de la base de données
        \DB::connection()->getPdo();
        echo "Database connected successfully!<br>";
        
        // Test des routes
        echo "Routes are working!<br>";
        
        // Test de la vue
        return view('welcome');
        
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . "<br>";
        echo "File: " . $e->getFile() . "<br>";
        echo "Line: " . $e->getLine() . "<br>";
    }
});