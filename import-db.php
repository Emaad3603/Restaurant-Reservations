<?php

try {
    // Connect to MySQL without selecting a database
    $pdo = new PDO('mysql:host=localhost', 'root', '');
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS dineease");
    
    echo "Database 'dineease' created or already exists.\n";
    
    // Select the dineease database
    $pdo->exec("USE dineease");
    
    // Read SQL file
    $sql = file_get_contents('c:\Users\Emad\Downloads\dbs\dbs.sql');
    
    // Execute SQL script
    $pdo->exec($sql);
    
    echo "SQL file imported successfully.\n";
} catch (PDOException $e) {
    die("Error: " . $e->getMessage() . "\n");
} 