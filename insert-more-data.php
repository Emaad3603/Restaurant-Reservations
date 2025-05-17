<?php

// Simple script to insert more test data into the dineease database

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
    
    // Get existing company ID and restaurant ID
    $stmt = $conn->query("SELECT company_id FROM companies LIMIT 1");
    $company = $stmt->fetch(PDO::FETCH_ASSOC);
    $companyId = $company['company_id'];
    
    $stmt = $conn->query("SELECT restaurants_id FROM restaurants LIMIT 1");
    $restaurant = $stmt->fetch(PDO::FETCH_ASSOC);
    $restaurantId = $restaurant['restaurants_id'];
    
    echo "Using company ID: $companyId and restaurant ID: $restaurantId\n";
    
    // Start transaction
    $conn->beginTransaction();
    
    // Insert meal types
    $mealTypes = [
        ['name' => 'Breakfast', 'short_name' => 'B', 'description' => 'Morning meal with pastries, eggs, etc.'],
        ['name' => 'Lunch', 'short_name' => 'L', 'description' => 'Midday meal with sandwiches, salads, etc.'],
        ['name' => 'Dinner', 'short_name' => 'D', 'description' => 'Evening meal with full courses.'],
        ['name' => 'Brunch', 'short_name' => 'BR', 'description' => 'Late morning meal combining breakfast and lunch.']
    ];
    
    foreach ($mealTypes as $mealType) {
        $stmt = $conn->prepare("INSERT INTO meal_types (company_id, name, short_name) 
                               VALUES (:company_id, :name, :short_name)");
        $stmt->execute([
            ':company_id' => $companyId,
            ':name' => $mealType['name'],
            ':short_name' => $mealType['short_name']
        ]);
        $mealTypeId = $conn->lastInsertId();
        
        // Insert meal type translation
        $stmt = $conn->prepare("INSERT INTO meal_types_translation (meal_type_id, language_code, name, description) 
                               VALUES (:meal_type_id, :language_code, :name, :description)");
        $stmt->execute([
            ':meal_type_id' => $mealTypeId,
            ':language_code' => 'en',
            ':name' => $mealType['name'],
            ':description' => $mealType['description']
        ]);
        
        echo "Inserted meal type: " . $mealType['name'] . " with ID: $mealTypeId\n";
    }
    
    // Insert menu categories
    $categories = [
        ['name' => 'Starters', 'description' => 'Light appetizers to start your meal'],
        ['name' => 'Main Courses', 'description' => 'Hearty main dishes'],
        ['name' => 'Desserts', 'description' => 'Sweet treats to finish your meal']
    ];
    
    foreach ($categories as $category) {
        $stmt = $conn->prepare("INSERT INTO menu_categories (restaurant_id) 
                               VALUES (:restaurant_id)");
        $stmt->execute([
            ':restaurant_id' => $restaurantId
        ]);
        $categoryId = $conn->lastInsertId();
        
        // Insert category translation
        $stmt = $conn->prepare("INSERT INTO menu_categories_translation (category_id, language_code, name, description) 
                               VALUES (:category_id, :language_code, :name, :description)");
        $stmt->execute([
            ':category_id' => $categoryId,
            ':language_code' => 'en',
            ':name' => $category['name'],
            ':description' => $category['description']
        ]);
        
        echo "Inserted menu category: " . $category['name'] . " with ID: $categoryId\n";
    }
    
    // Commit transaction
    $conn->commit();
    
    echo "Additional test data inserted successfully!\n";
    
} catch(PDOException $e) {
    // Roll back transaction if something failed
    if (isset($conn)) {
        $conn->rollback();
    }
    echo "Error: " . $e->getMessage() . "\n";
} 