<header class="navbar navbar-expand-lg position-absolute w-100 py-1">
    <div class="container-fluid d-flex mt-1 align-items-center mb-1 justify-content-between">
      <!-- Logo Section -->
      <a href="{{ url('/') }}" class="d-flex align-items-center text-decoration-none">
        <img src="{{ asset('storage/pictures/logo.png') }}" alt="Banstech Logo" class="logoimage me-2">
      </a>
  
      <!-- Navigation Links -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
       data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link text-light" href="/">Home</a></li>
          <li class="nav-item"><a class="nav-link text-light" href="#about">About Us</a></li>
          <li class="nav-item"><a class="nav-link text-light" href="#services">Services</a></li>
          <li class="nav-item"><a class="nav-link text-light" href="{{ route('login') }}">Login</a></li>
          <li class="nav-item"><a class="nav-link text-light" href="{{ route('register') }}">Register</a></li>
          <li class="nav-item"><a class="nav-link text-light" href="{{ route('client.jobs.create') }}">Post Project</a></li>   
        </ul>
      </div>
    </div>
  </header> 