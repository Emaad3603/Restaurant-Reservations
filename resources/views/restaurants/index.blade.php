@extends('layouts.app')

@section('title', 'Select Restaurant - {{ $company->company_name }}')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="alert alert-info">
                <h5 class="mb-2">Reservation Status</h5>
                @if($remainingFreeReservations === -1)
                    <p class="mb-0">You have unlimited free reservations available.</p>
                @elseif($remainingFreeReservations === 0)
                    <p class="mb-0">You have no free reservations remaining. All reservations will be charged.</p>
                @else
                    <p class="mb-0">You have {{ $remainingFreeReservations }} free reservation(s) remaining.</p>
                @endif
            </div>
        </div>
    </div>

 

    <div class="row">
        @forelse($restaurants as $restaurant)
            <div class="col-md-4 mb-4 restaurant-card" data-meal-types="{{ $restaurant->mealTypes->pluck('meal_types_id')->join(',') }}">
                <div class="card h-100">
                    @if($restaurant->logo_url)
                        <img src="{{ $restaurant->logo_url }}" class="card-img-top" alt="{{ $restaurant->name }}">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-utensils fa-3x text-muted"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $restaurant->name }}</h5>
                        <p class="card-text">{{ $restaurant->description }}</p>
                        
                        @php
                            $restrictionType = $hotel->getRestaurantRestrictionType($restaurant->restaurants_id);
                            $reservationStatus = $hotel->getRestaurantReservationStatus($restaurant->restaurants_id, $roomNumber);
                        @endphp
                        
                        @if($restrictionType === 1)
                            <div class="alert alert-warning mb-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                This restaurant is only available for guests staying at its hotel.
                            </div>
                        @elseif($restrictionType === 2)
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                This restaurant is available but requires payment for guests from other hotels.
                            </div>
                        @endif

                        <div class="alert alert-{{ $reservationStatus['type'] }} mb-3">
                            <i class="fas fa-{{ $reservationStatus['type'] === 'success' ? 'check-circle' : ($reservationStatus['type'] === 'warning' ? 'exclamation-triangle' : 'info-circle') }} me-2"></i>
                            {{ $reservationStatus['message'] }}
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('restaurants.menu', ['restaurantId' => $restaurant->restaurants_id]) }}" class="btn btn-outline-primary">
                                View Menu
                            </a>
                            <a href="{{ route('restaurants.reserve', ['restaurantId' => $restaurant->restaurants_id]) }}" class="btn btn-primary">
                                Make Reservation
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-md-12">
                <div class="alert alert-info">
                    No restaurants available at this time.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mealTypeButtons = document.querySelectorAll('.meal-type-filters button');
    const restaurantCards = document.querySelectorAll('.restaurant-card');
    
    mealTypeButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Update active state
            mealTypeButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const selectedMealType = this.dataset.mealType;
            
            // Show/hide restaurants based on meal type
            restaurantCards.forEach(card => {
                if (selectedMealType === 'all') {
                    card.style.display = 'block';
                } else {
                    const mealTypes = card.dataset.mealTypes.split(',');
                    card.style.display = mealTypes.includes(selectedMealType) ? 'block' : 'none';
                }
            });
        });
    });
});
</script>
@endsection 