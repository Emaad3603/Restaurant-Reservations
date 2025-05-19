@extends('layouts.app')

@section('title', 'Guest Verification - Restaurant Reservations')

@section('content')
<div class="row mb-4">
    <div class="col-md-12 text-center">
        <h2 class="mb-3">Guest Verification</h2>
        <p class="lead mb-4">Please verify your identity to continue with your reservation at {{ session('hotel_name') }}.</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="verification-icon mb-3">
                        <i class="fas fa-user-check fa-2x style="color: #FF8A80;""></i>
                    </div>
                    <h4 class="mb-1">Birth Date Verification</h4>
                    <p class="text-muted">We need your birth date to verify your identity</p>
                </div>

                <form action="{{ route('guest.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="birth_date" class="form-label">
                            <i class="fas fa-calendar-alt me-2 style="color: #FF8A80;""></i>Birth Date
                        </label>
                        <input type="date" class="form-control form-control-lg @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required>
                        @error('birth_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text text-muted mt-2">
                            <i class="fas fa-info-circle me-1"></i> This information will only be used for verification purposes.
                        </div>
                    </div>
                    
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg py-3">
                            Verify & Continue <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm reservation-info-card">
            <div class="card-body p-4">
                <h5 class="card-title mb-4">
                    <i class="fas fa-info-circle me-2 style="color: #FF8A80;""></i>Reservation Information
                </h5>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label text-muted">Hotel</div>
                            <div class="info-value fw-bold">{{ session('hotel_name') }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label text-muted">Room Number</div>
                            <div class="info-value fw-bold">{{ session('room_number') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
     body {
        background-image: url('/images/restaurantbk.jpg');
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
    .verification-icon {
        height: 80px;
        width: 80px;
        background-color: rgba(74, 78, 178, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    
    .reservation-info-card {
        background-color: #F8F9FF;
        border-radius: 15px;
    }
    
    .info-item {
        padding: 10px 15px;
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
</style>
@endsection 