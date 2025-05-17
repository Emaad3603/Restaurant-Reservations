<?php

// Simple script to test Laravel database connection

// Set database credentials (should match your .env)
$host = '127.0.0.1';
$database = 'dineease';
$username = 'root';
$password = '';

try {
    // Create connection
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    
    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully\n";
    
    // Test querying a table from the dineease database
    $stmt = $conn->query("SELECT * FROM hotels LIMIT 1");
    $hotel = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($hotel) {
        echo "Successfully retrieved a hotel:\n";
        echo "ID: " . $hotel['hotel_id'] . "\n";
        echo "Name: " . $hotel['name'] . "\n";
    } else {
        echo "No hotels found in the database.\n";
    }
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
} 