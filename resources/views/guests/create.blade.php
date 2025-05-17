@extends('layouts.app')

@section('title', 'Guest Verification - Restaurant Reservations')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h2>Guest Verification</h2>
        <p class="lead">Please enter your birthdate to verify your identity and continue with your restaurant reservation at {{ session('hotel_name') }}.</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Birth Date Verification</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('guest.store') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="birth_date" class="form-label">Birth Date</label>
                            <input type="date" class="form-control @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required>
                            @error('birth_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Please enter your birth date for verification.</small>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Verify & Continue</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Reservation Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Hotel:</strong> {{ session('hotel_name') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Room Number:</strong> {{ session('room_number') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 