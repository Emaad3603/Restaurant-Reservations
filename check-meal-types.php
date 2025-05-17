<?php

try {
    // Connect to the dineease database
    $pdo = new PDO('mysql:host=localhost;dbname=dineease', 'root', '');
    
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get the structure of the meal_types table
    echo "Table Structure of meal_types:\n";
    $stmt = $pdo->query("DESCRIBE meal_types");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $column) {
        echo "- " . $column['Field'] . " (" . $column['Type'] . ")" . 
             ($column['Key'] === 'PRI' ? " PRIMARY KEY" : "") . 
             ($column['Key'] === 'MUL' ? " FOREIGN KEY" : "") . 
             ($column['Null'] === 'NO' ? " NOT NULL" : "") . 
             "\n";
    }
    
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage() . "\n");
} 