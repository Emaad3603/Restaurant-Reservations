@extends('layouts.app')

@section('title', 'Make Reservation - ' . $restaurant->name)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Make a Reservation at {{ $restaurant->name }}</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($restaurant->always_paid_free === 0)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> This restaurant is always free. You have unlimited free reservations.
                        </div>
                    @elseif($restaurant->always_paid_free === 1)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i> This restaurant is always paid. No free reservations are available.
                        </div>
                    @elseif(is_null($restaurant->always_paid_free) && $remainingFreeReservations === null)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> You have unlimited free reservations.
                        </div>
                    @elseif(is_null($restaurant->always_paid_free) && $remainingFreeReservations <= 0)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i> You have no free reservations remaining.
                        </div>
                    @elseif(is_null($restaurant->always_paid_free))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i> You have {{ $remainingFreeReservations }} free reservation(s) remaining.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('reservations.store') }}" id="reservationForm">
                        @csrf
                        <input type="hidden" name="restaurant_id" value="{{ $restaurant->restaurants_id }}">
                        
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" required min="{{ date('Y-m-d') }}">
                        </div>

                        <div class="mb-3">
                            <label for="time" class="form-label">Time</label>
                            <select class="form-control" id="time" name="time" required>
                                <option value="">Select a time</option>
                                {{-- Options will be populated by JS --}}
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Meal Type</label>
                            <input type="text" class="form-control" id="meal_type_display" value="" readonly>
                            <input type="hidden" name="meal_type_id" id="meal_type_id" value="">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Select Guests</label>
                            <div class="row">
                                @foreach($guests as $guest)
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="guests[]" value="{{ $guest->guest_details_id }}" id="guest{{ $guest->guest_details_id }}">
                                            <label class="form-check-label" for="guest{{ $guest->guest_details_id }}">
                                                {{ $guest->guest_name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="special_requests" class="form-label">Special Requests (Optional)</label>
                            <textarea class="form-control" id="special_requests" name="special_requests" rows="3"></textarea>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">Pricing Information</h5>
                            </div>
                            <div class="card-body">
                                <div id="pricingInfo">
                                    <p class="text-muted">Select a date and time to view pricing details.</p>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-calendar-check me-2"></i> Make Reservation
                            </button>
                            <a href="{{ route('restaurants.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Back to Restaurants
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('date');
    const timeSelect = document.getElementById('time');
    const pricingInfo = document.getElementById('pricingInfo');
    const guestCheckboxes = document.querySelectorAll('input[name="guests[]"]');
    const mealTypeDisplay = document.getElementById('meal_type_display');
    const mealTypeIdInput = document.getElementById('meal_type_id');

    // Map of time to meal type and per_person (populated from backend)
    let timeToMealType = {};
    let timeToPerPerson = {};
    let mealTypeNames = {
        18: 'Breakfast',
        19: 'Lunch',
        20: 'Dinner'
    };

    // Function to fetch available times for selected date
    function fetchAvailableTimes(date) {
        console.log('Fetching times for date:', date); // Debug log
        fetch(`/restaurants/{{ $restaurant->restaurants_id }}/time-slots?date=${date}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            console.log('Response status:', response.status); // Debug log
            return response.json();
        })
        .then(data => {
            console.log('Received data:', data); // Debug log
            // Clear existing options
            timeSelect.innerHTML = '<option value="">Select a time</option>';
            timeToMealType = {};
            timeToPerPerson = {};
            if (data.success && data.times && data.times.length > 0) {
                data.times.forEach(timeObj => {
                    const option = document.createElement('option');
                    option.value = timeObj.time;
                    option.textContent = new Date('1970-01-01T' + timeObj.time).toLocaleTimeString('en-US', { 
                        hour: 'numeric', 
                        minute: '2-digit', 
                        hour12: true 
                    });
                    timeSelect.appendChild(option);
                    // Store meal type and per_person for this time
                    timeToMealType[timeObj.time] = timeObj.meal_type;
                    timeToPerPerson[timeObj.time] = timeObj.per_person;
                });
            } else {
                timeSelect.innerHTML = '<option value="">No times available</option>';
            }
            // Reset meal type display
            mealTypeDisplay.value = '';
            mealTypeIdInput.value = '';
            // Update pricing if time is already selected
            if (timeSelect.value) {
                updateMealType();
                updatePerPerson();
                updatePricing();
            }
        })
        .catch(error => {
            console.error('Error fetching available times:', error);
            timeSelect.innerHTML = '<option value="">Error loading times</option>';
        });
    }

    // Function to update meal type display and hidden input
    function updateMealType() {
        const selectedTime = timeSelect.value;
        const mealTypeId = timeToMealType[selectedTime];
        if (mealTypeId) {
            mealTypeDisplay.value = mealTypeNames[mealTypeId] || mealTypeId;
            mealTypeIdInput.value = mealTypeId;
        } else {
            mealTypeDisplay.value = '';
            mealTypeIdInput.value = '';
        }
    }

    // Function to update guest selection based on per_person
    function updatePerPerson() {
        const selectedTime = timeSelect.value;
        const perPerson = timeToPerPerson[selectedTime];
        guestCheckboxes.forEach(cb => {
            if (perPerson == 0) {
                cb.checked = true;
                cb.disabled = true;
            } else {
                cb.disabled = false;
            }
        });
    }

    // Function to update pricing information
    function updatePricing() {
        const date = dateInput.value;
        const time = timeSelect.value;
        const selectedGuests = Array.from(guestCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        if (date && time && selectedGuests.length > 0) {
            // Make AJAX call to get pricing
            fetch('{{ route("pricing.calculate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    restaurant_id: '{{ $restaurant->restaurants_id }}',
                    date: date,
                    time: time,
                    guests: selectedGuests
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let statusMsg = '';
                    if (data.status === 'always_free') {
                        statusMsg = '<div class="alert alert-info">This reservation is always free.</div>';
                    } else if (data.status === 'always_paid') {
                        statusMsg = '<div class="alert alert-warning">This reservation is always paid.</div>';
                    } else if (data.status === 'free_remaining') {
                        statusMsg = '<div class="alert alert-success">You are using a free reservation.</div>';
                    } else if (data.status === 'paid_after_free') {
                        statusMsg = '<div class="alert alert-warning">You have used all your free reservations. This reservation will be paid.</div>';
                    }
                    pricingInfo.innerHTML = `
                        ${statusMsg}
                        <div class="alert alert-info">
                            <h6>Pricing Details:</h6>
                            <p class="mb-1">Base Price: ${data.currency_symbol}${data.base_price}</p>
                            <p class="mb-0"><strong>Total Price: ${data.currency_symbol}${data.total_price}</strong></p>
                        </div>
                    `;
                } else {
                    pricingInfo.innerHTML = `
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i> ${data.message}
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error calculating price:', error);
                pricingInfo.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i> Error calculating price. Please try again.
                    </div>
                `;
            });
        } else {
            pricingInfo.innerHTML = `
                <p class="text-muted">Select a date, time, and guests to view pricing details.</p>
            `;
        }
    }

    // Add event listeners
    dateInput.addEventListener('change', function() {
        console.log('Date changed:', this.value); // Debug log
        if (this.value) {
            fetchAvailableTimes(this.value);
        }
    });
    timeSelect.addEventListener('change', function() {
        updateMealType();
        updatePerPerson();
        updatePricing();
    });
    guestCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updatePricing);
    });
});
</script>
@endpush
@endsection