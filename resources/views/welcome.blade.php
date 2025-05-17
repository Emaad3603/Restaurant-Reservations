@extends('layouts.app')

@section('title', 'Welcome - Restaurant Reservations')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h2 class="my-3"><i class="fas fa-utensils me-2"></i> Restaurant Reservations</h2>
                </div>
                <div class="card-body p-5">
                    <div class="mb-4">
                        <img src="https://cdn.pixabay.com/photo/2017/09/23/12/40/catering-2778755_1280.jpg" alt="Restaurant Dining" class="img-fluid rounded mb-4" style="max-height: 250px;">
                        <h3 class="mb-3">Welcome to Hotel Restaurant Reservations</h3>
                        <p class="lead">An easy way to book your dining experience during your stay</p>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('hotels.index') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-arrow-right me-2"></i> Start Making a Reservation
                        </a>
                    </div>
                </div>
                <div class="card-footer bg-light p-3">
                    <p class="mb-0 small text-muted">Select your hotel, choose a restaurant, and book your table in a few simple steps</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
