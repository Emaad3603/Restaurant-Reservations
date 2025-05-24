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
                
                <!-- Dynamic menu items -->
                <h4 class="mt-4">Menu</h4>
                <div class="row">
                    @foreach($menuCategories as $category)
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5>{{ $category->label }}</h5>
                                    <ul class="list-group list-group-flush">
                                        @foreach($category->subcategories as $subcategory)
                                            @foreach($subcategory->items as $item)
                                                @php
                                                    $menuItem = null;
                                                    $price = null;
                                                    $currencySymbol = '';
                                                    
                                                    // Check each menu for the item
                                                    foreach($menus as $menu) {
                                                        $menuItem = \DB::table('menus_items')
                                                            ->where('menus_id', $menu->menus_id)
                                                            ->where('items_id', $item->items_id)
                                                            ->first();
                                                            
                                                        if($menuItem && $menuItem->price) {
                                                            $price = $menuItem->price;
                                                            if($menuItem->currencies_id) {
                                                                $currency = \DB::table('currencies')
                                                                    ->where('currencies_id', $menuItem->currencies_id)
                                                                    ->first();
                                                                $currencySymbol = $currency->currency_symbol ?? '';
                                                            }
                                                            break;
                                                        }
                                                    }
                                                @endphp
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    {{ $item->label }}
                                                    @if($price)
                                                        <span class="badge bg-primary rounded-pill">{{ $currencySymbol }}{{ $price }}</span>
                                                    @endif
                                                </li>
                                            @endforeach
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
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