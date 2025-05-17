@extends('layouts.app')

@section('title', 'Select Hotel - Restaurant Reservations')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="mb-4">Select Your Hotel</h2>
        <p class="lead">Please choose the hotel you are staying at to continue with your restaurant reservation.</p>
    </div>
</div>

<div class="row">
    @forelse($hotels as $hotel)
        <div class="col-md-4 mb-4">
            <div class="card hotel-card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ $hotel->name }}</h5>
                </div>
                <div class="card-body">
                    @if($hotel->logo_url)
                        <img src="{{ $hotel->logo_url }}" alt="{{ $hotel->name }}" class="img-fluid mb-3" style="max-height: 150px;">
                    @else
                        <div class="text-center mb-3">
                            <i class="fas fa-hotel fa-5x text-muted"></i>
                        </div>
                    @endif
                    
                    <form action="{{ route('hotels.validateRoom', ['hotelId' => $hotel->hotel_id]) }}" method="POST" class="mt-3">
                        @csrf
                        <div class="mb-3">
                            <label for="room_number" class="form-label">Room Number</label>
                            <input type="text" class="form-control" id="room_number" name="room_number" required placeholder="Enter your room number">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-check-circle me-2"></i> Select Hotel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> No hotels are currently available. Please try again later.
            </div>
        </div>
    @endforelse
</div>
@endsection 