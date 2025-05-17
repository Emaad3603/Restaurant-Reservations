@extends('layouts.app')

@section('title', 'Reservation Confirmation - Restaurant Reservations')

@section('content')
<div class="row mb-4">
    <div class="col-md-12 text-center">
        <div class="mb-4">
            <i class="fas fa-check-circle text-success fa-5x"></i>
            <h1 class="mt-3">Reservation Confirmed!</h1>
            <p class="lead">Your reservation has been successfully confirmed. Please find your details below.</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Reservation Details</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    @if($reservation->restaurant->logo_url)
                        <img src="{{ $reservation->restaurant->logo_url }}" alt="{{ $reservation->restaurant->name }}" class="img-fluid mb-3" style="max-height: 100px;">
                    @else
                        <i class="fas fa-utensils fa-4x text-muted mb-3"></i>
                    @endif
                    <h3>{{ $reservation->restaurant->name }}</h3>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h5>Reservation Information</h5>
                            <p><strong>Confirmation Code:</strong> {{ $reservation->confirmation_code }}</p>
                            <p><strong>Date & Time:</strong> {{ \Carbon\Carbon::parse($reservation->date_time)->format('F j, Y - g:i A') }}</p>
                            <p><strong>Number of Guests:</strong> {{ $reservation->pax }}</p>
                            <p><strong>Meal Type:</strong> {{ $reservation->mealType->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h5>Guest Information</h5>
                            @if($reservation->guestReservation && $reservation->guestReservation->guestDetails->first())
                                <p><strong>Name:</strong> {{ $reservation->guestReservation->guestDetails->first()->first_name }} {{ $reservation->guestReservation->guestDetails->first()->last_name }}</p>
                            @endif
                            <p><strong>Hotel:</strong> {{ session('hotel_name') }}</p>
                            <p><strong>Room Number:</strong> {{ $reservation->guestReservation->room_number ?? session('room_number') }}</p>
                        </div>
                    </div>
                </div>
                
                @if($reservation->customer_request)
                <div class="mb-4">
                    <h5>Special Requests</h5>
                    <p>{{ $reservation->customer_request }}</p>
                </div>
                @endif
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Please arrive 10 minutes before your reservation time. If you need to cancel or modify your reservation, please contact the hotel reception.
                </div>
                
                <div class="d-grid gap-2 mt-4">
                    <a href="{{ route('reservations.print', $reservation->reservations_id) }}" class="btn btn-primary">
                        <i class="fas fa-print me-2"></i> Print Receipt
                    </a>
                    <a href="{{ route('welcome') }}" class="btn btn-outline-primary">
                        <i class="fas fa-home me-2"></i> Return to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 