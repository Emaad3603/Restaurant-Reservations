@extends('layouts.app')

@section('title', 'Select Restaurant - Restaurant Reservations')

@section('content')
<div class="row mb-4">
    <div class="col-md-12 text-center">
        <h2 class="mb-3">Select a Restaurant</h2>
        <p class="lead mb-4">Choose from our available restaurants at {{ session('hotel_name') }} for your dining experience.</p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-4 mb-4 mb-lg-0">
        <div class="card border-0 shadow-sm reservation-info-card">
            <div class="card-body p-4">
                <h5 class="card-title mb-4">
                    <i class="fas fa-info-circle me-2 text-primary"></i>Reservation Details
                </h5>
                
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
                        <div class="info-label text-muted">Number of Guests</div>
                        <div class="info-value fw-bold">{{ session('number_of_guests') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="card-title mb-3">
                    <i class="fas fa-utensils me-2 text-primary"></i>Meal Types
                </h5>
                
                <div class="meal-type-container pb-2">
                    <div class="meal-types-scroll">
                        @foreach($mealTypes as $mealType)
                            <button class="btn btn-pill meal-type-btn" data-meal-type="{{ $mealType->meal_type_id }}">
                                {{ $mealType->translated_name }}
                            </button>
                        @endforeach
                        <button class="btn btn-pill meal-type-btn active" data-meal-type="all">
                            All
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row restaurants-container">
    @forelse($restaurants as $restaurant)
        <div class="col-md-6 col-lg-4 mb-4 restaurant-card" data-restaurant-id="{{ $restaurant->restaurants_id }}">
            <div class="card border-0 shadow-sm h-100 restaurant-item">
                <div class="card-img-top position-relative overflow-hidden" style="height: 160px; background-color: #f0f2ff;">
                    @if($restaurant->logo_url)
                        <img src="{{ $restaurant->logo_url }}" alt="{{ $restaurant->name }}" class="img-fluid w-100 h-100 object-fit-cover">
                    @else
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <i class="fas fa-utensils fa-4x text-primary opacity-50"></i>
                        </div>
                    @endif
                    <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                        <h5 class="card-title text-white mb-0">{{ $restaurant->name }}</h5>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-users me-2 text-primary"></i>
                        <span>Capacity: <strong>{{ $restaurant->capacity }} people</strong></span>
                    </div>
                    
                    <div class="d-flex mt-auto pt-3 restaurant-actions">
                        <a href="{{ route('restaurants.menu', ['restaurantId' => $restaurant->restaurants_id]) }}" class="btn btn-outline-primary flex-grow-1 me-2">
                            <i class="fas fa-book-open me-2"></i> Menu
                        </a>
                        <a href="{{ route('restaurants.reserve', ['restaurantId' => $restaurant->restaurants_id]) }}" class="btn btn-primary flex-grow-1">
                            <i class="fas fa-calendar-check me-2"></i> Reserve
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info p-4 text-center">
                <i class="fas fa-info-circle fa-2x mb-3 text-primary"></i>
                <h5>No Restaurants Available</h5>
                <p class="mb-0">No restaurants are currently available. Please try again later.</p>
            </div>
        </div>
    @endforelse
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <a href="{{ route('guest.create') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Go Back
        </a>
    </div>
</div>
@endsection

@section('styles')
<style>
        body {
        background-image: url('/images/texture.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        position: relative;
    }

    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.95);
        z-index: -1;
    }
    .reservation-info-card {
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
    
    .meal-type-container {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .meal-types-scroll {
        display: flex;
        flex-wrap: nowrap;
        gap: 10px;
        padding-bottom: 5px;
    }
    
    .btn-pill {
        border-radius: 50px;
        padding: 8px 20px;
        background-color: #f0f2ff;
        color: #4A4EB2;
        border: none;
        white-space: nowrap;
        transition: all 0.3s ease;
    }
    
    .btn-pill:hover, .btn-pill.active {
        background-color: #4A4EB2;
        color: white;
    }
    
    .restaurant-item {
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .restaurant-item:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(74, 78, 178, 0.1) !important;
    }
    
    .restaurant-actions {
        border-top: 1px solid rgba(0,0,0,0.05);
    }
    
    .object-fit-cover {
        object-fit: cover;
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
        // Filter restaurants by meal type
        $('.meal-type-btn').click(function() {
            $('.meal-type-btn').removeClass('active');
            $(this).addClass('active');
            
            const mealTypeId = $(this).data('meal-type');
            
            if (mealTypeId === 'all') {
                $('.restaurant-card').show();
            } else {
                // Make an AJAX call to filter restaurants by meal type
                $.ajax({
                    url: '{{ route("restaurants.filter") }}',
                    type: 'GET',
                    data: {
                        meal_type_id: mealTypeId,
                        hotel_id: '{{ session("hotel_id") }}'
                    },
                    success: function(response) {
                        // Hide all restaurant cards first
                        $('.restaurant-card').hide();
                        
                        // Show only the restaurants that serve the selected meal type
                        if (response.restaurants && response.restaurants.length > 0) {
                            $.each(response.restaurants, function(index, restaurantId) {
                                $('.restaurant-card[data-restaurant-id="' + restaurantId + '"]').show();
                            });
                        } else {
                            // No restaurants found for this meal type
                            // Show a message or handle as needed
                            console.log('No restaurants found for this meal type');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error filtering restaurants:', xhr.responseText);
                        // Show all restaurants on error
                        $('.restaurant-card').show();
                    }
                });
            }
        });
    });
</script>
@endsection 