<?php

try {
    // Connect to the dineease database
    $pdo = new PDO('mysql:host=localhost;dbname=dineease', 'root', '');
    
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // List of tables to analyze
    $tablesToAnalyze = [
        'hotels',
        'restaurants',
        'restaurants_translations',
        'guest_details',
        'guest_reservations',
        'reservations',
        'meal_types',
        'meal_types_translation',
        'restaurant_pricing_times'
    ];
    
    // Analyze each table
    foreach ($tablesToAnalyze as $table) {
        echo "\n=============================================\n";
        echo "TABLE: $table\n";
        echo "=============================================\n";
        
        // Get table structure
        $stmt = $pdo->query("DESCRIBE $table");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "Table Structure:\n";
        foreach ($columns as $column) {
            echo "- " . $column['Field'] . " (" . $column['Type'] . ")" . 
                 ($column['Key'] === 'PRI' ? " PRIMARY KEY" : "") . 
                 ($column['Key'] === 'MUL' ? " FOREIGN KEY" : "") . 
                 ($column['Null'] === 'NO' ? " NOT NULL" : "") . 
                 "\n";
        }
        
        // Show a sample row if available
        $stmt = $pdo->query("SELECT * FROM $table LIMIT 1");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            echo "\nSample Data:\n";
            foreach ($row as $column => $value) {
                echo "- $column: " . (is_null($value) ? "NULL" : $value) . "\n";
            }
        } else {
            echo "\nNo data in table.\n";
        }
    }
    
    // List stored procedures
    echo "\n=============================================\n";
    echo "STORED PROCEDURES\n";
    echo "=============================================\n";
    
    $stmt = $pdo->query("SHOW PROCEDURE STATUS WHERE Db = 'dineease'");
    $procedures = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($procedures) {
        foreach ($procedures as $procedure) {
            echo "- " . $procedure['Name'] . "\n";
        }
    } else {
        echo "No stored procedures found.\n";
    }
    
    // List functions
    echo "\n=============================================\n";
    echo "FUNCTIONS\n";
    echo "=============================================\n";
    
    $stmt = $pdo->query("SHOW FUNCTION STATUS WHERE Db = 'dineease'");
    $functions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($functions) {
        foreach ($functions as $function) {
            echo "- " . $function['Name'] . "\n";
        }
    } else {
        echo "No functions found.\n";
    }
    
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage() . "\n");
} 