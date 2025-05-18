@extends('layouts.app')

@section('title', 'Reserve Restaurant - Restaurant Reservations')

@section('content')
<div class="row mb-4">
    <div class="col-md-12 text-center">
        <h2 class="mb-3">Make a Reservation</h2>
        <p class="lead mb-4">Choose a date and time for your reservation at {{ $restaurant->name }}.</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <form action="{{ route('reservations.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="restaurant_id" value="{{ $restaurant->restaurants_id }}">
                    
                    <!-- Date Selection Card -->
                    <div class="date-selection-card mb-4">
                        <h5 class="mb-3">
                            <i class="fas fa-calendar-alt me-2 text-primary"></i>Select Date
                        </h5>
                        
                        <div class="date-picker-wrapper">
                            <div class="row">
                                <div class="col-12">
                                    <input type="date" class="form-control form-control-lg @error('date') is-invalid @enderror" 
                                           id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                                    @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Date Pills -->
                            <div class="date-pills-container mt-3">
                                <div class="date-pills">
                                    @php
                                        $today = new DateTime();
                                        $dates = [];
                                        for($i = 0; $i < 7; $i++) {
                                            $date = clone $today;
                                            $date->modify("+$i day");
                                            $dates[] = $date;
                                        }
                                    @endphp
                                    
                                    @foreach($dates as $index => $date)
                                        <button type="button" 
                                                class="btn date-pill {{ $index === 0 ? 'active' : '' }}" 
                                                data-date="{{ $date->format('Y-m-d') }}">
                                            <div class="date-pill-day">{{ $date->format('D') }}</div>
                                            <div class="date-pill-date">{{ $date->format('j') }}</div>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Time Slots Card -->
                    <div class="time-slots-card mb-4">
                        <h5 class="mb-3">
                            <i class="fas fa-clock me-2 text-primary"></i>Available Time Slots
                        </h5>
                        
                        <div class="time-slots-container">
                            <div class="time-slots">
                                @php
                                    $timeSlots = [
                                        '12:00', '12:30', '13:00', '13:30', 
                                        '18:00', '18:30', '19:00', '19:30', '20:00', '20:30'
                                    ];
                                @endphp
                                
                                @foreach($timeSlots as $time)
                                    <button type="button" class="btn time-slot {{ $time === '19:00' ? 'active' : '' }}" 
                                            data-time="{{ $time }}">
                                        {{ $time }}
                                    </button>
                                @endforeach
                            </div>
                            <input type="hidden" id="time" name="time" value="{{ old('time', '19:00') }}">
                            @error('time')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Meal Type Selection -->
                    <div class="meal-type-card mb-4">
                        <h5 class="mb-3">
                            <i class="fas fa-utensils me-2 text-primary"></i>Meal Type
                        </h5>
                        
                        <div class="meal-type-container">
                            <select class="form-select form-select-lg @error('meal_type_id') is-invalid @enderror" 
                                    id="meal_type_id" name="meal_type_id" required>
                                <option value="">Select a meal type</option>
                                @foreach($mealTypes as $mealType)
                                    <option value="{{ $mealType->meal_type_id }}" {{ old('meal_type_id') == $mealType->meal_type_id ? 'selected' : '' }}>
                                        {{ $mealType->translated_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('meal_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Special Requests -->
                    <div class="special-requests-card mb-4">
                        <h5 class="mb-3">
                            <i class="fas fa-comment-alt me-2 text-primary"></i>Special Requests (Optional)
                        </h5>
                        
                        <textarea class="form-control @error('customer_request') is-invalid @enderror" 
                                  id="customer_request" name="customer_request" rows="3" 
                                  placeholder="Any special requests or dietary requirements?">{{ old('customer_request') }}</textarea>
                        @error('customer_request')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid gap-2 mt-5">
                        <button type="submit" class="btn btn-primary btn-lg py-3">
                            Confirm Reservation <i class="fas fa-check-circle ms-2"></i>
                        </button>
                        <a href="{{ route('restaurants.index') }}" class="btn btn-outline-secondary mt-2">
                            <i class="fas fa-arrow-left me-2"></i> Back to Restaurants
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Reservation Summary Card -->
        <div class="card border-0 shadow-sm reservation-summary-card">
            <div class="card-body p-4">
                <h5 class="card-title mb-4">
                    <i class="fas fa-info-circle me-2 text-primary"></i>Reservation Summary
                </h5>
                
                <div class="d-flex align-items-center mb-4">
                    @if($restaurant->logo_url)
                        <div class="restaurant-logo me-3">
                            <img src="{{ $restaurant->logo_url }}" alt="{{ $restaurant->name }}" class="img-fluid">
                        </div>
                    @else
                        <div class="restaurant-logo me-3 d-flex align-items-center justify-content-center">
                            <i class="fas fa-utensils fa-2x text-primary"></i>
                        </div>
                    @endif
                    <div>
                        <h5 class="mb-1">{{ $restaurant->name }}</h5>
                        <p class="text-muted mb-0">Capacity: <strong>{{ $restaurant->capacity }} people</strong></p>
                    </div>
                </div>
                
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label text-muted">Guest</div>
                        <div class="info-value fw-bold">{{ session('guest_name') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label text-muted">Hotel</div>
                        <div class="info-value fw-bold">{{ session('hotel_name') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label text-muted">Room</div>
                        <div class="info-value fw-bold">{{ session('room_number') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label text-muted">Guests</div>
                        <div class="info-value fw-bold">{{ session('number_of_guests') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .date-selection-card,
    .time-slots-card,
    .meal-type-card,
    .special-requests-card {
        padding: 20px;
        border-radius: 15px;
        background-color: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        margin-bottom: 20px;
    }
    
    .reservation-summary-card {
        background-color: #F8F9FF;
        border-radius: 15px;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }
    
    .info-item {
        padding: 12px 15px;
        border-radius: 10px;
        background-color: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
    
    .info-label {
        font-size: 0.85rem;
        margin-bottom: 5px;
    }
    
    .info-value {
        font-size: 1.1rem;
    }
    
    .restaurant-logo {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(74, 78, 178, 0.1);
    }
    
    .date-pills-container {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .date-pills {
        display: flex;
        flex-wrap: nowrap;
        gap: 10px;
        padding-bottom: 5px;
    }
    
    .date-pill {
        border-radius: 15px;
        padding: 10px;
        min-width: 75px;
        display: flex;
        flex-direction: column;
        align-items: center;
        background-color: #f0f2ff;
        color: #4E89FF;
        border: none;
        white-space: nowrap;
        transition: all 0.3s ease;
    }
    
    .date-pill:hover, .date-pill.active {
        background-color: #4E89FF;
        color: white;
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(78, 137, 255, 0.2);
    }
    
    .date-pill-day {
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .date-pill-date {
        font-size: 1.2rem;
        font-weight: 600;
    }
    
    .time-slots-container {
        margin-top: 15px;
    }
    
    .time-slots {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .time-slot {
        padding: 12px 20px;
        border-radius: 10px;
        background-color: #f0f2ff;
        color: #4E89FF;
        border: none;
        transition: all 0.3s ease;
    }
    
    .time-slot:hover, .time-slot.active {
        background-color: #4E89FF;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(78, 137, 255, 0.2);
    }
    
    .time-slot.available {
        background-color: rgba(255, 213, 79, 0.2);
        color: #FF8C00;
    }
    
    .time-slot.unavailable {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    @media (max-width: 767.98px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Date pill selection
        $('.date-pill').click(function() {
            $('.date-pill').removeClass('active');
            $(this).addClass('active');
            
            // Update the hidden date input
            const selectedDate = $(this).data('date');
            $('#date').val(selectedDate);
            
            // Here you would normally make an AJAX call to get available time slots
            // For now, we'll just simulate it
            updateAvailableTimeSlots(selectedDate);
        });
        
        // Time slot selection
        $('.time-slot').click(function() {
            if (!$(this).hasClass('unavailable')) {
                $('.time-slot').removeClass('active');
                $(this).addClass('active');
                
                // Update the hidden time input
                const selectedTime = $(this).data('time');
                $('#time').val(selectedTime);
            }
        });
        
        // Simulating fetching available time slots
        function updateAvailableTimeSlots(date) {
            // In a real implementation, this would be an AJAX call
            console.log("Fetching available slots for date: " + date);
            
            // For demo purposes, let's randomly make some slots "available"
            $('.time-slot').each(function() {
                // Remove all special classes
                $(this).removeClass('available unavailable active');
                
                // Randomly assign available/unavailable (70% available)
                const isAvailable = Math.random() > 0.3;
                if (isAvailable) {
                    // 30% of available slots are highlighted as "special availability"
                    if (Math.random() > 0.7) {
                        $(this).addClass('available');
                    }
                } else {
                    $(this).addClass('unavailable');
                }
            });
            
            // Make sure at least one slot is active
            if ($('.time-slot.active').length === 0) {
                $('.time-slot:not(.unavailable)').first().addClass('active');
                const firstAvailableTime = $('.time-slot.active').data('time');
                $('#time').val(firstAvailableTime || '19:00');
            }
        }
        
        // Initialize with today's date
        updateAvailableTimeSlots($('#date').val());
    });
</script>
@endsection 