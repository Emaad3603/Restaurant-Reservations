<?php

// Simple script to insert test data into the dineease database

// Set database credentials
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
    
    // Start transaction
    $conn->beginTransaction();
    
    // Insert company
    $stmt = $conn->prepare("INSERT INTO companies (company_name, currency_id, company_uuid, logo_url) 
                           VALUES (:name, :currency_id, :uuid, :logo_url)");
    $stmt->execute([
        ':name' => 'Test Hotel Group',
        ':currency_id' => 1,
        ':uuid' => uniqid(),
        ':logo_url' => 'https://cdn.pixabay.com/photo/2018/05/05/19/28/hotel-3377344_1280.jpg'
    ]);
    $companyId = $conn->lastInsertId();
    
    echo "Inserted company with ID: $companyId\n";
    
    // Insert currency
    $stmt = $conn->prepare("INSERT INTO currencies (company_id, currency_code, name, exchange_rate, currency_symbol, active) 
                           VALUES (:company_id, :code, :name, :rate, :symbol, :active)");
    $stmt->execute([
        ':company_id' => $companyId,
        ':code' => 'USD',
        ':name' => 'US Dollar',
        ':rate' => 1.000000,
        ':symbol' => '$',
        ':active' => 1
    ]);
    $currencyId = $conn->lastInsertId();
    
    echo "Inserted currency with ID: $currencyId\n";
    
    // Insert hotel
    $stmt = $conn->prepare("INSERT INTO hotels (name, verification_type, company_id, free_count, time_zone, plus_days_adjust, minus_days_adjust, active, logo_url) 
                           VALUES (:name, :verification, :company_id, :free_count, :time_zone, :plus_days, :minus_days, :active, :logo_url)");
    $stmt->execute([
        ':name' => 'Grand Hotel',
        ':verification' => 0,
        ':company_id' => $companyId,
        ':free_count' => 3,
        ':time_zone' => '+00:00',
        ':plus_days' => 0,
        ':minus_days' => 0,
        ':active' => 1,
        ':logo_url' => 'https://cdn.pixabay.com/photo/2018/05/05/19/28/hotel-3377344_1280.jpg'
    ]);
    $hotelId = $conn->lastInsertId();
    
    echo "Inserted hotel with ID: $hotelId\n";
    
    // Insert restaurant
    $stmt = $conn->prepare("INSERT INTO restaurants (company_id, name, capacity, active, hotel_id, logo_url, always_paid_free) 
                           VALUES (:company_id, :name, :capacity, :active, :hotel_id, :logo_url, :always_paid_free)");
    $stmt->execute([
        ':company_id' => $companyId,
        ':name' => 'Fine Dining Restaurant',
        ':capacity' => 50,
        ':active' => 1,
        ':hotel_id' => $hotelId,
        ':logo_url' => 'https://cdn.pixabay.com/photo/2016/11/18/14/05/kitchen-1834858_1280.jpg',
        ':always_paid_free' => 0
    ]);
    $restaurantId = $conn->lastInsertId();
    
    echo "Inserted restaurant with ID: $restaurantId\n";
    
    // Insert restaurant translation
    $stmt = $conn->prepare("INSERT INTO restaurants_translations (restaurants_id, language_code, cuisine, about) 
                           VALUES (:restaurants_id, :language_code, :cuisine, :about)");
    $stmt->execute([
        ':restaurants_id' => $restaurantId,
        ':language_code' => 'en',
        ':cuisine' => 'International',
        ':about' => 'A luxurious dining experience with the finest cuisine from around the world.'
    ]);
    
    echo "Inserted restaurant translation\n";
    
    // Commit transaction
    $conn->commit();
    
    echo "Test data inserted successfully!\n";
    
} catch(PDOException $e) {
    // Roll back transaction if something failed
    $conn->rollback();
    echo "Error: " . $e->getMessage() . "\n";
}