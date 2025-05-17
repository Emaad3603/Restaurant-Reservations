<?php

/**
 * Complete Test Data Insertion Script for Restaurant Reservations
 * This script creates a full set of test data for testing the entire reservation flow:
 * 1. Hotels with rooms
 * 2. Restaurants with translations, menus, and operating hours
 * 3. Meal types
 * 4. Restaurant pricing times
 */

try {
    // Connect to the dineease database
    $pdo = new PDO('mysql:host=localhost;dbname=dineease', 'root', '');
    
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully\n";
    
    // Start transaction
    $pdo->beginTransaction();
    
    // ==================== BASICS ====================
    
    // Check if we already have currencies
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM currencies");
    $currencyCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Insert currency if needed
    $currencyId = 1;
    if ($currencyCount == 0) {
        $stmt = $pdo->prepare("INSERT INTO currencies (currency_code, name, exchange_rate, currency_symbol, active) 
                              VALUES (:code, :name, :rate, :symbol, :active)");
        $stmt->execute([
            ':code' => 'USD',
            ':name' => 'US Dollar',
            ':rate' => 1.000000,
            ':symbol' => '$',
            ':active' => 1
        ]);
        $currencyId = $pdo->lastInsertId();
        echo "Inserted currency with ID: $currencyId\n";
    } else {
        $stmt = $pdo->query("SELECT currencies_id FROM currencies LIMIT 1");
        $currencyId = $stmt->fetch(PDO::FETCH_ASSOC)['currencies_id'];
        echo "Using existing currency with ID: $currencyId\n";
    }
    
    // Check if we already have companies
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM companies");
    $companyCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Insert company if needed
    $companyId = 1;
    if ($companyCount == 0) {
        $stmt = $pdo->prepare("INSERT INTO companies (company_name, currency_id, company_uuid, logo_url) 
                              VALUES (:name, :currency_id, :uuid, :logo_url)");
        $stmt->execute([
            ':name' => 'Test Hotels Group',
            ':currency_id' => $currencyId,
            ':uuid' => uniqid(),
            ':logo_url' => 'https://cdn.pixabay.com/photo/2018/05/05/19/28/hotel-3377344_1280.jpg'
        ]);
        $companyId = $pdo->lastInsertId();
        echo "Inserted company with ID: $companyId\n";
    } else {
        $stmt = $pdo->query("SELECT company_id FROM companies LIMIT 1");
        $companyId = $stmt->fetch(PDO::FETCH_ASSOC)['company_id'];
        echo "Using existing company with ID: $companyId\n";
    }
    
    // ==================== HOTELS ====================
    
    // Create multiple hotels
    $hotels = [
        [
            'name' => 'Luxury Beach Resort',
            'logo_url' => 'https://cdn.pixabay.com/photo/2016/11/17/09/28/hotel-1831072_1280.jpg',
        ],
        [
            'name' => 'Mountain View Hotel',
            'logo_url' => 'https://cdn.pixabay.com/photo/2015/09/21/09/54/villa-cortine-palace-949552_1280.jpg',
        ],
        [
            'name' => 'City Center Hotel',
            'logo_url' => 'https://cdn.pixabay.com/photo/2020/10/18/09/16/bedroom-5664221_1280.jpg',
        ]
    ];
    
    $hotelIds = [];
    foreach ($hotels as $hotel) {
        $stmt = $pdo->prepare("INSERT INTO hotels (name, verification_type, company_id, free_count, time_zone, 
                              plus_days_adjust, minus_days_adjust, created_at, active, restricted_restaurants, logo_url) 
                              VALUES (:name, :verification, :company_id, :free_count, :time_zone, 
                              :plus_days, :minus_days, NOW(), :active, :restricted, :logo_url)");
        $stmt->execute([
            ':name' => $hotel['name'],
            ':verification' => 0, // No verification required
            ':company_id' => $companyId,
            ':free_count' => 3, // Free count for reservations
            ':time_zone' => '+00:00',
            ':plus_days' => 14, // Allow reservations up to 14 days in advance
            ':minus_days' => 0,
            ':active' => 1,
            ':restricted' => 0,
            ':logo_url' => $hotel['logo_url']
        ]);
        $hotelId = $pdo->lastInsertId();
        $hotelIds[] = $hotelId;
        echo "Inserted hotel: " . $hotel['name'] . " with ID: $hotelId\n";
    }
    
    // ==================== MEAL TYPES ====================
    
    // Create meal types
    $mealTypes = [
        ['name' => 'Breakfast', 'short_name' => 'B', 'description' => 'Start your day with a delicious breakfast.'],
        ['name' => 'Lunch', 'short_name' => 'L', 'description' => 'Enjoy a refreshing lunch.'],
        ['name' => 'Dinner', 'short_name' => 'D', 'description' => 'Experience fine dining in the evening.'],
        ['name' => 'Brunch', 'short_name' => 'BR', 'description' => 'Late morning meal combining breakfast and lunch.']
    ];
    
    $mealTypeIds = [];
    foreach ($mealTypes as $mealType) {
        $stmt = $pdo->prepare("INSERT INTO meal_types (company_id, name, short_name) 
                              VALUES (:company_id, :name, :short_name)");
        $stmt->execute([
            ':company_id' => $companyId,
            ':name' => $mealType['name'],
            ':short_name' => $mealType['short_name']
        ]);
        $mealTypeId = $pdo->lastInsertId();
        $mealTypeIds[] = $mealTypeId;
        
        // Insert meal type translation
        $stmt = $pdo->prepare("INSERT INTO meal_types_translation (meal_type_id, language_code, name, description) 
                              VALUES (:meal_type_id, :language_code, :name, :description)");
        $stmt->execute([
            ':meal_type_id' => $mealTypeId,
            ':language_code' => 'en',
            ':name' => $mealType['name'],
            ':description' => $mealType['description']
        ]);
        
        echo "Inserted meal type: " . $mealType['name'] . " with ID: $mealTypeId\n";
    }
    
    // ==================== RESTAURANTS ====================
    
    // Create restaurants for each hotel
    $restaurants = [
        [
            'name' => 'Ocean View Restaurant',
            'cuisine' => 'Seafood',
            'about' => 'Enjoy fresh seafood with a beautiful ocean view. Our restaurant offers the freshest catches prepared by world-class chefs.',
            'logo_url' => 'https://cdn.pixabay.com/photo/2018/07/14/15/27/cafe-3537801_1280.jpg',
            'capacity' => 60
        ],
        [
            'name' => 'Garden Terrace',
            'cuisine' => 'Italian',
            'about' => 'Authentic Italian cuisine served in a beautiful garden setting. Perfect for a romantic dinner or family gathering.',
            'logo_url' => 'https://cdn.pixabay.com/photo/2014/09/17/20/26/restaurant-449952_1280.jpg',
            'capacity' => 45
        ],
        [
            'name' => 'Skyline Rooftop',
            'cuisine' => 'International',
            'about' => 'Dine with a spectacular view of the city skyline. Our menu offers a variety of international dishes crafted with local ingredients.',
            'logo_url' => 'https://cdn.pixabay.com/photo/2020/01/30/12/27/celebration-4805518_1280.jpg',
            'capacity' => 50
        ]
    ];
    
    $restaurantIds = [];
    for ($i = 0; $i < count($hotelIds); $i++) {
        // Each hotel gets all restaurants for testing purposes
        foreach ($restaurants as $restaurant) {
            $stmt = $pdo->prepare("INSERT INTO restaurants (company_id, name, capacity, created_at, active, hotel_id, logo_url, always_paid_free) 
                                  VALUES (:company_id, :name, :capacity, NOW(), :active, :hotel_id, :logo_url, :always_paid_free)");
            $stmt->execute([
                ':company_id' => $companyId,
                ':name' => $restaurant['name'],
                ':capacity' => $restaurant['capacity'],
                ':active' => 1,
                ':hotel_id' => $hotelIds[$i],
                ':logo_url' => $restaurant['logo_url'],
                ':always_paid_free' => 0
            ]);
            $restaurantId = $pdo->lastInsertId();
            $restaurantIds[] = $restaurantId;
            
            // Insert restaurant translation
            $stmt = $pdo->prepare("INSERT INTO restaurants_translations (restaurants_id, language_code, cuisine, about) 
                                  VALUES (:restaurants_id, :language_code, :cuisine, :about)");
            $stmt->execute([
                ':restaurants_id' => $restaurantId,
                ':language_code' => 'en',
                ':cuisine' => $restaurant['cuisine'],
                ':about' => $restaurant['about']
            ]);
            
            echo "Inserted restaurant: " . $restaurant['name'] . " for hotel ID: " . $hotelIds[$i] . " with ID: $restaurantId\n";
            
            // ==================== RESTAURANT PRICING TIMES ====================
            
            // Add operating hours for each restaurant
            // Different times for different meal types
            foreach ($mealTypeIds as $index => $mealTypeId) {
                $mealTypeName = $mealTypes[$index]['name'];
                
                // Different times based on meal type
                if ($mealTypeName == 'Breakfast') {
                    $startTime = '07:00:00';
                    $endTime = '10:30:00';
                } elseif ($mealTypeName == 'Lunch') {
                    $startTime = '12:00:00';
                    $endTime = '15:30:00';
                } elseif ($mealTypeName == 'Dinner') {
                    $startTime = '18:00:00';
                    $endTime = '22:30:00';
                } elseif ($mealTypeName == 'Brunch') {
                    $startTime = '10:30:00';
                    $endTime = '14:00:00';
                }
                
                // Add pricing times for every day of the week
                for ($day = 0; $day <= 6; $day++) {
                    $stmt = $pdo->prepare("INSERT INTO restaurant_pricing_times 
                                          (restaurant_id, company_id, start_time, end_time, day_of_week, active, meal_type_id) 
                                          VALUES (:restaurant_id, :company_id, :start_time, :end_time, :day_of_week, :active, :meal_type_id)");
                    $stmt->execute([
                        ':restaurant_id' => $restaurantId,
                        ':company_id' => $companyId,
                        ':start_time' => $startTime,
                        ':end_time' => $endTime,
                        ':day_of_week' => $day,
                        ':active' => 1,
                        ':meal_type_id' => $mealTypeId
                    ]);
                    
                    echo "Added operating hours for " . $mealTypeName . " on day " . $day . " for restaurant ID: $restaurantId\n";
                }
            }
        }
    }
    
    // ==================== SAMPLE GUEST RESERVATION AND DETAILS ====================
    
    // Add sample guest reservations (hotel booking/check-in records)
    $roomNumbers = ['101', '102', '103', '201', '202', '203', '301', '302', '303'];
    $arrivalDate = date('Y-m-d'); // Today
    $departureDate = date('Y-m-d', strtotime('+7 days')); // 7 days from now
    
    $guestReservationIds = [];
    foreach ($hotelIds as $hotelId) {
        for ($i = 0; $i < 3; $i++) { // Add 3 guest reservations per hotel
            $roomNumber = $roomNumbers[array_rand($roomNumbers)];
            $pax = rand(1, 4); // Number of guests
            
            $stmt = $pdo->prepare("INSERT INTO guest_reservations 
                                  (reservation_id, room_number, arrival_date, departure_date, pax, 
                                  status, hotel_id, company_id, board_type) 
                                  VALUES (:reservation_id, :room_number, :arrival_date, :departure_date, 
                                  :pax, :status, :hotel_id, :company_id, :board_type)");
            $stmt->execute([
                ':reservation_id' => 'RES' . rand(10000, 99999),
                ':room_number' => $roomNumber,
                ':arrival_date' => $arrivalDate,
                ':departure_date' => $departureDate,
                ':pax' => $pax,
                ':status' => 'checked_in',
                ':hotel_id' => $hotelId,
                ':company_id' => $companyId,
                ':board_type' => 'BB' // Bed & Breakfast
            ]);
            $guestReservationId = $pdo->lastInsertId();
            $guestReservationIds[] = $guestReservationId;
            
            echo "Added guest reservation for room $roomNumber in hotel ID: $hotelId with ID: $guestReservationId\n";
            
            // Add guest details for each reservation
            for ($j = 1; $j <= $pax; $j++) {
                $firstName = "Guest";
                $lastName = "Person" . $j;
                $guestName = $firstName . " " . $lastName;
                $birthDate = date('Y-m-d', strtotime('-' . rand(20, 60) . ' years')); // Random adult age
                
                $stmt = $pdo->prepare("INSERT INTO guest_details 
                                      (guest_reservations_id, guest_name, guest_type, birth_date) 
                                      VALUES (:guest_reservations_id, :guest_name, :guest_type, :birth_date)");
                $stmt->execute([
                    ':guest_reservations_id' => $guestReservationId,
                    ':guest_name' => $guestName,
                    ':guest_type' => ($j == 1) ? 'main' : 'additional',
                    ':birth_date' => $birthDate
                ]);
                
                echo "Added guest details for $guestName in reservation ID: $guestReservationId\n";
            }
        }
    }
    
    // ==================== SAMPLE RESTAURANT RESERVATIONS ====================
    
    // Add a few existing restaurant reservations
    for ($i = 0; $i < 5; $i++) {
        $guestReservationId = $guestReservationIds[array_rand($guestReservationIds)];
        $restaurantId = $restaurantIds[array_rand($restaurantIds)];
        
        // Get restaurant and hotel information
        $stmt = $pdo->query("SELECT hotel_id FROM restaurants WHERE restaurants_id = $restaurantId");
        $restaurantHotelId = $stmt->fetch(PDO::FETCH_ASSOC)['hotel_id'];
        
        $stmt = $pdo->query("SELECT room_number, pax, hotel_id FROM guest_reservations WHERE guest_reservations_id = $guestReservationId");
        $guestInfo = $stmt->fetch(PDO::FETCH_ASSOC);
        $roomNumber = $guestInfo['room_number'];
        $pax = $guestInfo['pax'];
        $guestHotelId = $guestInfo['hotel_id'];
        
        // Get guest names
        $stmt = $pdo->query("SELECT guest_name FROM guest_details WHERE guest_reservations_id = $guestReservationId");
        $guestNames = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $names = implode(', ', $guestNames);
        
        // Get available time slots from restaurant_pricing_times
        $stmt = $pdo->query("SELECT start_time, end_time FROM restaurant_pricing_times WHERE restaurant_id = $restaurantId LIMIT 1");
        $timeSlot = $stmt->fetch(PDO::FETCH_ASSOC);
        $startTime = $timeSlot['start_time'];
        
        // Calculate a reservation date within the next 3 days
        $reservationDate = date('Y-m-d', strtotime('+' . rand(1, 3) . ' days'));
        
        $stmt = $pdo->prepare("INSERT INTO reservations 
                              (guest_reservations_id, room_number, pax, names, restaurant_id, 
                              day, time, company_id, guest_hotel_id, restaurant_hotel_id, 
                              canceled, ended, created_at, qrcode) 
                              VALUES (:guest_reservations_id, :room_number, :pax, :names, :restaurant_id, 
                              :day, :time, :company_id, :guest_hotel_id, :restaurant_hotel_id, 
                              :canceled, :ended, NOW(), :qrcode)");
        $stmt->execute([
            ':guest_reservations_id' => $guestReservationId,
            ':room_number' => $roomNumber,
            ':pax' => $pax,
            ':names' => $names,
            ':restaurant_id' => $restaurantId,
            ':day' => $reservationDate,
            ':time' => $startTime,
            ':company_id' => $companyId,
            ':guest_hotel_id' => $guestHotelId,
            ':restaurant_hotel_id' => $restaurantHotelId,
            ':canceled' => 0,
            ':ended' => 0,
            ':qrcode' => 'QR' . uniqid()
        ]);
        
        $reservationId = $pdo->lastInsertId();
        echo "Added restaurant reservation for date $reservationDate at $startTime with ID: $reservationId\n";
    }
    
    // Commit transaction
    $pdo->commit();
    
    echo "\nAll test data inserted successfully!\n";
    echo "You can now test the complete reservation flow:\n";
    echo "1. Select a hotel\n";
    echo "2. Enter a room number (use one from the list below)\n";
    echo "3. Enter a birthdate for validation\n";
    echo "4. Select a restaurant\n";
    echo "5. Choose reservation date and time\n";
    echo "6. Confirm reservation and print receipt\n\n";
    
    echo "Available room numbers for testing:\n";
    $stmt = $pdo->query("SELECT hotel_id, name, room_number FROM hotels JOIN guest_reservations USING (hotel_id) GROUP BY hotel_id, room_number LIMIT 10");
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rooms as $room) {
        echo "- Hotel ID " . $room['hotel_id'] . " (" . $room['name'] . "): Room " . $room['room_number'] . "\n";
    }
    
} catch(PDOException $e) {
    // Roll back transaction if something failed
    if (isset($pdo)) {
        $pdo->rollback();
    }
    echo "Error: " . $e->getMessage() . "\n";
} 