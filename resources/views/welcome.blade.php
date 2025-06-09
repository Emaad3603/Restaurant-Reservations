@extends('layouts.app')

@section('title', 'Welcome - {{ $company->company_name }}')

@section('content')
<div class="container welcome-container">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="welcome-card card border-0 shadow-lg overflow-hidden">
                <div class="welcome-header position-relative">
                    @if($company->logo_url)
                        <img src="{{ $company->logo_url }}" alt="{{ $company->company_name }}" class="img-fluid w-100" style="height: 250px; object-fit: cover;">
                    @else
                        <img src="https://cdn.pixabay.com/photo/2017/09/23/12/40/catering-2778755_1280.jpg" alt="Restaurant Dining" class="img-fluid w-100" style="height: 250px; object-fit: cover;">
                    @endif
                    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(rgba(74, 78, 178, 0.3), rgba(74, 78, 178, 0.8))"></div>
                    <div class="position-absolute top-50 start-50 translate-middle text-center w-100 px-4">
                        <h1 class="text-white fw-bold mb-3">{{ $company->company_name }}</h1>
                        <p class="text-white fs-5 mb-0">Simplify your dining experience</p>
                    </div>
                </div>
                
                <div class="card-body p-4 p-lg-5">
                    <div class="features-section mb-5">
                        <h3 class="text-center mb-4">How It Works</h3>
                        <div class="row g-4 text-center">
                            <div class="col-md-4">
                                <div class="feature-item">
                                    <div class="feature-icon mb-3">
                                        <i class="fas fa-hotel fa-2x text-primary"></i>
                                    </div>
                                    <h5>Choose Your Hotel</h5>
                                    <p class="text-muted">Select the hotel you're staying at</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="feature-item">
                                    <div class="feature-icon mb-3">
                                        <i class="fas fa-utensils fa-2x text-primary"></i>
                                    </div>
                                    <h5>Pick a Restaurant</h5>
                                    <p class="text-muted">Browse available restaurants</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="feature-item">
                                    <div class="feature-icon mb-3">
                                        <i class="fas fa-calendar-check fa-2x text-primary"></i>
                                    </div>
                                    <h5>Confirm Booking</h5>
                                    <p class="text-muted">Make your reservation in seconds</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <a href="{{ route('hotels.index') }}" class="btn btn-primary btn-lg py-3">
                            Start Your Reservation <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <p class="text-muted small">© {{ date('Y') }} Restaurant Reservation System. All rights reserved.</p>
            </div>
        </div>
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
    .welcome-container {
        margin-top: 2rem;
        margin-bottom: 3rem;
    }
    
    .welcome-card {
        border-radius: 20px;
        overflow: hidden;
    }
    
    .welcome-header {
        height: 250px;
        overflow: hidden;
    }
    
    .feature-icon {
        height: 60px;
        width: 60px;
        background-color: rgba(74, 78, 178, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    
    .feature-item {
        padding: 1.5rem;
        border-radius: 15px;
        transition: all 0.3s ease;
    }
    
    .feature-item:hover {
        background-color: rgba(74, 78, 178, 0.05);
        transform: translateY(-5px);
    }
    
    @media (max-width: 767.98px) {
        .welcome-header {
            height: 200px;
        }
    }
</style>
@endsection
