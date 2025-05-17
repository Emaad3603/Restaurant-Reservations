<?php

try {
    // Connect to the dineease database
    $pdo = new PDO('mysql:host=localhost;dbname=dineease', 'root', '');
    
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Tables to check
    $tables = [
        'meal_types',
        'meal_types_translation',
        'restaurants',
        'restaurants_translations',
        'restaurant_pricing_times',
        'guest_reservations',
        'guest_details',
        'reservations'
    ];
    
    foreach ($tables as $table) {
        echo "\n=============================================\n";
        echo "TABLE: $table\n";
        echo "=============================================\n";
        
        // Get structure
        $stmt = $pdo->query("DESCRIBE $table");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($columns as $column) {
            echo "- " . $column['Field'] . " (" . $column['Type'] . ")" . 
                 ($column['Key'] === 'PRI' ? " PRIMARY KEY" : "") . 
                 ($column['Key'] === 'MUL' ? " FOREIGN KEY" : "") . 
                 ($column['Null'] === 'NO' ? " NOT NULL" : "") . 
                 "\n";
        }
    }
    
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage() . "\n");
} 