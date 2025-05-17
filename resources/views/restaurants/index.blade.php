@extends('layouts.app')

@section('title', 'Select Restaurant - Restaurant Reservations')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2>Select a Restaurant</h2>
        <p class="lead">Choose from our available restaurants at {{ session('hotel_name') }} for your dining experience.</p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Reservation Info</h5>
            </div>
            <div class="card-body">
                <p><strong>Guest:</strong> {{ session('guest_name') }}</p>
                <p><strong>Hotel:</strong> {{ session('hotel_name') }}</p>
                <p><strong>Room:</strong> {{ session('room_number') }}</p>
                <p><strong>Number of Guests:</strong> {{ session('number_of_guests') }}</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Meal Types</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    @foreach($mealTypes as $mealType)
                        <button class="btn btn-outline-primary meal-type-btn" data-meal-type="{{ $mealType->meal_type_id }}">
                            {{ $mealType->name }}
                        </button>
                    @endforeach
                    <button class="btn btn-outline-primary meal-type-btn active" data-meal-type="all">
                        All
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row restaurants-container">
    @forelse($restaurants as $restaurant)
        <div class="col-md-4 mb-4 restaurant-card" data-restaurant-id="{{ $restaurant->restaurants_id }}">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ $restaurant->name }}</h5>
                </div>
                <div class="card-body">
                    @if($restaurant->logo_url)
                        <img src="{{ $restaurant->logo_url }}" alt="{{ $restaurant->name }}" class="img-fluid mb-3" style="max-height: 150px;">
                    @else
                        <div class="text-center mb-3">
                            <i class="fas fa-utensils fa-5x text-muted"></i>
                        </div>
                    @endif
                    
                    <p class="card-text">
                        <i class="fas fa-users me-2"></i> Capacity: {{ $restaurant->capacity }} people
                    </p>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <a href="{{ route('restaurants.menu', ['restaurantId' => $restaurant->restaurants_id]) }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-book-open me-2"></i> View Menu
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('restaurants.reserve', ['restaurantId' => $restaurant->restaurants_id]) }}" class="btn btn-primary w-100">
                                <i class="fas fa-calendar-check me-2"></i> Reserve
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> No restaurants are currently available. Please try again later.
            </div>
        </div>
    @endforelse
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <a href="{{ route('guest.create') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Go Back to Guest Information
        </a>
    </div>
</div>
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
                // In a real implementation, this would need an AJAX call to filter restaurants by meal type
                // For now, we'll just show all restaurants
                $('.restaurant-card').show();
            }
        });
    });
</script>
@endsection 