<?php

try {
    // Connect to the dineease database
    $pdo = new PDO('mysql:host=localhost;dbname=dineease', 'root', '');
    
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get a list of all tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Database 'dineease' contains the following tables:\n";
    foreach ($tables as $table) {
        echo "- $table\n";
    }
    
    echo "\nTotal number of tables: " . count($tables) . "\n";
    
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage() . "\n");
} 