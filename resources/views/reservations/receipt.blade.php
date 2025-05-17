<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reservation Receipt #{{ $reservation->confirmation_code }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
        }
        .logo {
            max-height: 100px;
            margin-bottom: 15px;
        }
        h1 {
            font-size: 24px;
            margin: 0;
            color: #2c3e50;
        }
        h2 {
            font-size: 18px;
            margin: 15px 0 10px 0;
            color: #2c3e50;
        }
        .receipt-details {
            margin-bottom: 30px;
        }
        .info-box {
            margin-bottom: 30px;
        }
        .row {
            display: flex;
            margin-bottom: 10px;
        }
        .column {
            flex: 1;
        }
        .confirmation-code {
            font-size: 18px;
            font-weight: bold;
            padding: 10px;
            border: 2px solid #2c3e50;
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
        }
        .footer {
            margin-top: 40px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
        .note {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Restaurant Reservation Receipt</h1>
            <p>{{ \Carbon\Carbon::now()->format('F j, Y') }}</p>
            <div class="confirmation-code">
                {{ $reservation->confirmation_code }}
            </div>
        </div>
        
        <div class="receipt-details">
            <div class="info-box">
                <h2>Restaurant Information</h2>
                <p><strong>Restaurant:</strong> {{ $reservation->restaurant->name }}</p>
                <p><strong>Hotel:</strong> {{ session('hotel_name') }}</p>
            </div>
            
            <div class="info-box">
                <h2>Reservation Details</h2>
                <p><strong>Date & Time:</strong> {{ \Carbon\Carbon::parse($reservation->date_time)->format('F j, Y - g:i A') }}</p>
                <p><strong>Number of Guests:</strong> {{ $reservation->pax }}</p>
                <p><strong>Meal Type:</strong> {{ $reservation->mealType->name ?? 'N/A' }}</p>
                
                @if($reservation->customer_request)
                <p><strong>Special Requests:</strong> {{ $reservation->customer_request }}</p>
                @endif
            </div>
            
            <div class="info-box">
                <h2>Guest Information</h2>
                @if($reservation->guestReservation && $reservation->guestReservation->guestDetails->first())
                <p><strong>Name:</strong> {{ $reservation->guestReservation->guestDetails->first()->first_name }} {{ $reservation->guestReservation->guestDetails->first()->last_name }}</p>
                @endif
                <p><strong>Room Number:</strong> {{ $reservation->guestReservation->room_number ?? session('room_number') }}</p>
            </div>
        </div>
        
        <div class="note">
            <p><strong>Note:</strong> Please arrive 10 minutes before your reservation time. If you need to cancel or modify your reservation, please contact the hotel reception.</p>
        </div>
        
        <div class="footer">
            <p>Thank you for choosing to dine with us at {{ $reservation->restaurant->name }}.</p>
            <p>Restaurant Reservations System &copy; {{ date('Y') }}</p>
        </div>
    </div>
</body>
</html> 