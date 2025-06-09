@extends('layouts.app')

@section('title', 'Select Hotel - {{ $company->company_name }}')

@section('content')
<div class="row mb-4">
    <div class="col-md-12 text-center">
        <h2 class="mb-3">Select Your Hotel</h2>
        <p class="lead mb-4">Please choose the hotel you are staying at to continue with your reservation.</p>
    </div>
</div>

<div class="row justify-content-center">
    @forelse($hotels as $hotel)
        <div class="col-md-4 mb-4">
            <div class="card hotel-card h-100">
                <div class="card-img-top position-relative overflow-hidden" style="height: 160px; background-color: #f0f2ff;">
                    @if($hotel->logo_url)
                        <img src="{{ $hotel->logo_url }}" alt="{{ $hotel->name }}" class="img-fluid w-100 h-100 object-fit-cover">
                    @else
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <i class="fas fa-hotel fa-4x text-primary opacity-50"></i>
                        </div>
                    @endif
                    <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                        <h5 class="card-title text-white mb-0">{{ $hotel->name }}</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('hotels.validateRoom', ['hotelId' => $hotel->hotel_id]) }}" method="POST" class="mt-2">
                        @csrf
                        <div class="mb-3">
                            <label for="room_number_{{ $hotel->hotel_id }}" class="form-label">
                                <i class="fas fa-door-closed me-2 style="color: #FF8A80;""></i>Room Number
                            </label>
                            <input type="text" class="form-control" id="room_number_{{ $hotel->hotel_id }}" name="room_number" required placeholder="Enter your room number">
                        </div>
                        @if($hotel->verification_type == 0)
                            <p class="text-muted">Please enter your birthdate for verification.</p>
                        @elseif($hotel->verification_type == 1)
                            <p class="text-muted">Please enter your departure date for verification.</p>
                        @endif
                        <input type="hidden" name="verification_type" value="{{ $hotel->verification_type }}">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-check-circle me-2"></i> Select Hotel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-md-8">
            <div class="alert alert-info p-4 text-center">
                <i class="fas fa-info-circle fa-2x mb-3 text-primary"></i>
                <h5>No Hotels Available</h5>
                <p class="mb-0">No hotels are currently available. Please try again later.</p>
            </div>
        </div>
    @endforelse
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

    .object-fit-cover {
        object-fit: cover;
    }
    
    .hotel-card {
        transition: all 0.3s ease;
    }
    
    .hotel-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(74, 78, 178, 0.1);
    }
</style>
@endsection 