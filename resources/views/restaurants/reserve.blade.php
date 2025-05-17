@extends('layouts.app')

@section('title', 'Reserve Restaurant - Restaurant Reservations')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2>Reserve a Table at {{ $restaurant->name }}</h2>
        <p class="lead">Complete the form below to make your reservation.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Reservation Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('reservations.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="restaurant_id" value="{{ $restaurant->restaurants_id }}">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="time" class="form-label">Time</label>
                            <input type="time" class="form-control @error('time') is-invalid @enderror" id="time" name="time" value="{{ old('time', '19:00') }}" required>
                            @error('time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="meal_type_id" class="form-label">Meal Type</label>
                        <select class="form-select @error('meal_type_id') is-invalid @enderror" id="meal_type_id" name="meal_type_id" required>
                            <option value="">Select a meal type</option>
                            @foreach($mealTypes as $mealType)
                                <option value="{{ $mealType->meal_type_id }}" {{ old('meal_type_id') == $mealType->meal_type_id ? 'selected' : '' }}>
                                    {{ $mealType->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('meal_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="customer_request" class="form-label">Special Requests (Optional)</label>
                        <textarea class="form-control @error('customer_request') is-invalid @enderror" id="customer_request" name="customer_request" rows="3">{{ old('customer_request') }}</textarea>
                        @error('customer_request')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check-circle me-2"></i> Confirm Reservation
                        </button>
                        <a href="{{ route('restaurants.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Restaurants
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Reservation Summary</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    @if($restaurant->logo_url)
                        <img src="{{ $restaurant->logo_url }}" alt="{{ $restaurant->name }}" class="img-fluid mb-3" style="max-height: 100px;">
                    @else
                        <i class="fas fa-utensils fa-4x text-muted mb-3"></i>
                    @endif
                    <h4>{{ $restaurant->name }}</h4>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <p><strong>Guest:</strong> {{ session('guest_name') }}</p>
                    <p><strong>Hotel:</strong> {{ session('hotel_name') }}</p>
                    <p><strong>Room:</strong> {{ session('room_number') }}</p>
                    <p><strong>Number of Guests:</strong> {{ session('number_of_guests') }}</p>
                </div>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Your reservation will be confirmed immediately after submission.
                </div>
                
                <a href="{{ route('restaurants.menu', ['restaurantId' => $restaurant->restaurants_id]) }}" class="btn btn-outline-primary w-100">
                    <i class="fas fa-book-open me-2"></i> View Menu
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 