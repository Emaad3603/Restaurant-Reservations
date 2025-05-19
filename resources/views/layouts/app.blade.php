<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Restaurant Reservations')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #000000; /* Changed to match Flutter app */
            --secondary-color: #FF8A80; /* Changed to match Flutter app */
            --accent-color: #FFD54F; /* Changed to match Flutter app */
            --light-color: #F5F5F5;
            --dark-color: #333333;
            --background-color: #F8F8FF; /* Light lavender background */
        }
        
        body {
            font-family: 'Poppins', sans-serif; /* Changed to Poppins to match Flutter app */
            background-color: var(--background-color);
            color: var(--dark-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .content {
            flex: 1;
        }
        
        .navbar {
            background-color: var(--primary-color);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 25px; /* Rounded buttons like Flutter app */
            padding: 8px 20px;
            font-weight: 500;
            box-shadow: 0 3px 5px rgba(74, 78, 178, 0.2);
        }
        
        .btn-primary:hover {
            background-color: #FF8A80; /* Slightly darker */
            border-color: #FF8A80;
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(74, 78, 178, 0.3);
        }
        
        .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 500;
        }
        
        .btn-secondary:hover {
            background-color: #ff6b6b;
            border-color: #ff6b6b;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            font-weight: 600;
            padding: 15px 20px;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .form-control {
            border-radius: 10px;
            padding: 10px 15px;
            border: 1px solid #e0e0e0;
            background-color: #f9f9f9;
        }
        
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(74, 78, 178, 0.2);
            border-color: var(--primary-color);
        }
        
        .form-label {
            font-weight: 500;
            color: #555;
            margin-bottom: 8px;
        }
        
        .footer {
            background-color: var(--primary-color);
            color: white;
            padding: 20px 0;
            margin-top: auto;
        }
        
        .hotel-card, .restaurant-card {
            cursor: pointer;
            height: 100%;
        }
        
        .reservation-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }
        
        .reservation-steps::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #e9ecef;
            z-index: 1;
        }
        
        .step {
            z-index: 2;
            position: relative;
            background-color: var(--background-color);
            padding: 0 10px;
        }
        
        .step-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            transition: all 0.3s ease;
        }
        
        .step.active .step-icon {
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 3px 10px rgba(74, 78, 178, 0.3);
        }
        
        .step.completed .step-icon {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .step-title {
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        /* Alert styling */
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }
        
        .alert-success {
            background-color: #E8F5E9;
            color: #2E7D32;
        }
        
        .alert-danger {
            background-color: #FFEBEE;
            color: #C62828;
        }
        
        /* Container styling */
        .container {
            padding: 0 20px;
        }
        
        /* Page headings */
        h2, h3, h4 {
            font-weight: 600;
            color: var(--primary-color);
        }
        
        /* Lead text */
        .lead {
            font-weight: 400;
            color: #555;
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('welcome') }}">
                <i class="fas fa-utensils me-2"></i>Restaurant Reservations
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('welcome') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('hotels.index') }}">Hotels</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Reservation Steps -->
    @if(request()->route() && !in_array(request()->route()->getName(), ['welcome']))
    <div class="container mt-4">
        <div class="reservation-steps">
            <div class="step {{ in_array(request()->route()->getName(), ['hotels.index', 'hotels.validateRoom', 'guest.create', 'guest.store', 'restaurants.index', 'restaurants.menu', 'restaurants.reserve', 'reservations.store', 'reservations.confirm', 'reservations.print']) ? 'active' : '' }}">
                <div class="step-icon">1</div>
                <div class="step-title text-center">Select Hotel</div>
            </div>
            <div class="step {{ in_array(request()->route()->getName(), ['guest.create', 'guest.store', 'restaurants.index', 'restaurants.menu', 'restaurants.reserve', 'reservations.store', 'reservations.confirm', 'reservations.print']) ? 'active' : '' }}">
                <div class="step-icon">2</div>
                <div class="step-title text-center">Guest Info</div>
            </div>
            <div class="step {{ in_array(request()->route()->getName(), ['restaurants.index', 'restaurants.menu', 'restaurants.reserve', 'reservations.store', 'reservations.confirm', 'reservations.print']) ? 'active' : '' }}">
                <div class="step-icon">3</div>
                <div class="step-title text-center">Select Restaurant</div>
            </div>
            <div class="step {{ in_array(request()->route()->getName(), ['reservations.confirm', 'reservations.print']) ? 'active' : '' }}">
                <div class="step-icon">4</div>
                <div class="step-title text-center">Confirmation</div>
            </div>
        </div>
    </div>
    @endif

    <!-- Content -->
    <div class="content">
        <div class="container mt-4">
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Restaurant Reservations</h5>
                    <p>Your hotel dining experience, simplified.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; {{ date('Y') }} Restaurant Reservations. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    @yield('scripts')
</body>
</html> 