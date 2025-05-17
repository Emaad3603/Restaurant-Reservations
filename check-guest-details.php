<?php

try {
    // Connect to the dineease database
    $pdo = new PDO('mysql:host=localhost;dbname=dineease', 'root', '');
    
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get the structure of the guest_details table
    echo "Table Structure of guest_details:\n";
    $stmt = $pdo->query("DESCRIBE guest_details");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $column) {
        echo "- " . $column['Field'] . " (" . $column['Type'] . ")" . 
             ($column['Key'] === 'PRI' ? " PRIMARY KEY" : "") . 
             ($column['Key'] === 'MUL' ? " FOREIGN KEY" : "") . 
             ($column['Null'] === 'NO' ? " NOT NULL" : "") . 
             "\n";
    }
    
    // Check for sample data
    $stmt = $pdo->query("SELECT * FROM guest_details LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        echo "\nSample Data:\n";
        foreach ($row as $field => $value) {
            echo "- $field: " . ($value !== null ? $value : "NULL") . "\n";
        }
    } else {
        echo "\nNo data in the guest_details table.\n";
    }
    
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage() . "\n");
} 