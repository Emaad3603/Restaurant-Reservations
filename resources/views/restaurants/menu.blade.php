@extends('layouts.app')

@section('title', 'Restaurant Menu - Restaurant Reservations')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2>{{ $restaurant->name }} - Menu</h2>
        <p class="lead">Browse our menu offerings and culinary delights.</p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Menu Information</h5>
            </div>
            <div class="card-body">
                @if($restaurant->logo_url)
                    <img src="{{ $restaurant->logo_url }}" alt="{{ $restaurant->name }}" class="img-fluid mb-4" style="max-height: 200px;">
                @endif
                
                @if($translation)
                    <h4>About Our Restaurant</h4>
                    <p>{{ $translation->about }}</p>
                    
                    <h4 class="mt-4">Cuisine Type</h4>
                    <p>{{ $translation->cuisine }}</p>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Detailed menu information is not available at this time.
                    </div>
                @endif
                
                <!-- Sample menu items - in a real app these would come from the database -->
                <h4 class="mt-4">Sample Menu Items</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5>Appetizers</h5>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Caesar Salad
                                        <span class="badge bg-primary rounded-pill">$12</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Garlic Bread
                                        <span class="badge bg-primary rounded-pill">$8</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Bruschetta
                                        <span class="badge bg-primary rounded-pill">$10</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5>Main Courses</h5>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Filet Mignon
                                        <span class="badge bg-primary rounded-pill">$38</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Grilled Salmon
                                        <span class="badge bg-primary rounded-pill">$28</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Vegetable Risotto
                                        <span class="badge bg-primary rounded-pill">$22</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5>Desserts</h5>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Tiramisu
                                        <span class="badge bg-primary rounded-pill">$10</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Chocolate Lava Cake
                                        <span class="badge bg-primary rounded-pill">$12</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Ice Cream Selection
                                        <span class="badge bg-primary rounded-pill">$8</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5>Beverages</h5>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        House Wine (Glass)
                                        <span class="badge bg-primary rounded-pill">$9</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Soft Drinks
                                        <span class="badge bg-primary rounded-pill">$4</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Specialty Coffee
                                        <span class="badge bg-primary rounded-pill">$6</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card sticky-top" style="top: 20px;">
            <div class="card-header">
                <h5 class="card-title mb-0">Restaurant Details</h5>
            </div>
            <div class="card-body">
                <p><i class="fas fa-users me-2"></i> <strong>Capacity:</strong> {{ $restaurant->capacity }} people</p>
                
                <hr>
                
                <h5>Make a Reservation</h5>
                <p>Ready to dine with us? Reserve your table now.</p>
                
                <a href="{{ route('restaurants.reserve', ['restaurantId' => $restaurant->restaurants_id]) }}" class="btn btn-primary w-100 mb-3">
                    <i class="fas fa-calendar-check me-2"></i> Reserve Now
                </a>
                
                <a href="{{ route('restaurants.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-arrow-left me-2"></i> Back to Restaurants
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 