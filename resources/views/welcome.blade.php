<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{asset('pictures/logo.png') }}">
    <title>Banstech</title>
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
   <link href="{{asset('welcome.css') }}" rel="stylesheet">
   <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
</head>

<body>
<!-- Header Section -->
@include('welcome-componets.welcome-header')

<main>
  <!-- Section 2: hero-section -->
@include('welcome-componets.hero-section')

 <!-- Section 3: company-section -->
@include('welcome-componets.company-section')
 
<!-- Section 4: List of our Services -->
@include('welcome-componets.welcome-services')
 
<!--  Section 5: Testimonials Section -->
 @include('welcome-componets.testimony-section')
 
<!-- Section 6: Subscribe-Section -->
 @include('welcome-componets.subscribe-section')
</main>

@include('layouts.footer')

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
  document.addEventListener("DOMContentLoaded", () => {
    const configElements = document.querySelectorAll(".swiper-config");
    configElements.forEach((configEl) => {
      const config = JSON.parse(configEl.textContent);
      new Swiper(configEl.closest(".init-swiper"), config);
    });
  });
</script>
 
</body>
</html>