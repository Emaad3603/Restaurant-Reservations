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
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #e74c3c;
            --accent-color: #f39c12;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
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
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #1a2530;
            border-color: #1a2530;
        }
        
        .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-secondary:hover {
            background-color: #c0392b;
            border-color: #c0392b;
        }
        
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            margin-bottom: 20px;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 10px 10px 0 0 !important;
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
            background-color: #f8f9fa;
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
        }
        
        .step.active .step-icon {
            background-color: var(--primary-color);
            color: white;
        }
        
        .step.completed .step-icon {
            background-color: var(--secondary-color);
            color: white;
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