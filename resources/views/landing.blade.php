<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{asset('storage/pictures/logo.png') }}">
    <title>Banstech</title>

    <!-- Tailwind css -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
   <link href="{{asset('welcome.css') }}" rel="stylesheet">
   <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
</head>

<body>
    <nav class="flex items-center justify-between px-8 py-4 bg-slate-950/90 backdrop-blur-lg sticky top-0 z-50 border-b border-white/5">
        <div class="flex items-center gap-2">
            <img src="{{ asset('storage/pictures/logo.png') }}" alt="Banstech Logo" class="logoimage me-2">
        </div>
        <div class="hidden lg:flex items-center gap-8 text-sm font-semibold uppercase tracking-wider text-slate-300">
            <a href="#about" class="hover:text-blue-400 transition">About</a>
            <a href="#services" class="hover:text-blue-400 transition">IT Support</a>
            <a href="#freelance" class="hover:text-blue-400 transition">Freelance</a>
            <a href="#ads" class="hover:text-blue-400 transition">Expert Ads</a>
        </div>
        {{-- <div class="flex gap-4">
            <a href="{{ route('login') }}" class="text-white px-5 py-2 hover:text-blue-400 transition">Login</a>
            <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2 rounded-full transition shadow-lg shadow-blue-500/20">Get Started</a>
        </div> --}}
    </nav>

    <header class="relative min-h-[80vh] flex items-center bg-slate-950 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-blue-600/10 rounded-full blur-[120px]"></div>
        </div>
        
        <div class="scroll-mt-24 max-w-7xl mx-auto px-6 relative z-10" id="top">
            <div class="max-w-3xl">
                <span class="text-blue-400 font-bold tracking-[0.2em] uppercase text-sm mb-4 block">Total IT Integration</span>
                <h1 class="text-5xl md:text-7xl font-black text-white leading-tight mb-6">
                    Your Vision, <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-green-400">Our Technology.</span>
                </h1>
                <p class="text-xl text-slate-400 mb-10 leading-relaxed">
                    Banstech bridges the gap between complex IT challenges and seamless digital solutions. From on-demand support to a global freelance marketplace.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#services" class="bg-white text-slate-950 px-8 py-4 rounded-xl font-bold hover:bg-blue-50 transition">Explore Services</a>
                    <a href="#freelance" class="border border-white/20 text-white px-8 py-4 rounded-xl font-bold hover:bg-white/5 transition">Hire Experts</a>
                </div>
            </div>
        </div>
    </header>

    <section id="about" class="scroll-mt-24 py-24 bg-slate-900 border-y border-white/5">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-16 items-center">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Empowering the Digital Frontier</h2>
                <p class="text-slate-400 mb-6 leading-relaxed">
                    Banstech was founded on a simple principle: technology should be an accelerator, not a barrier. We serve as an all-purpose IT partner for businesses of all sizes, providing the infrastructure and the talent needed to thrive in a connected world.
                </p>
                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="mt-1 bg-blue-500/20 p-2 rounded-lg"><svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path></svg></div>
                        <div><h4 class="text-white font-bold">Reliability First</h4><p class="text-sm text-slate-400">On-demand support that never sleeps.</p></div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="mt-1 bg-green-500/20 p-2 rounded-lg"><svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path></svg></div>
                        <div><h4 class="text-white font-bold">Global Talent</h4><p class="text-sm text-slate-400">Accessing experts from around the world.</p></div>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-blue-600 to-indigo-900 rounded-3xl p-1 shadow-2xl">
                <div class="bg-slate-950 rounded-[calc(1.5rem-4px)] p-8">
                    <h3 class="text-2xl font-bold text-white mb-4 italic">"Transforming IT into your greatest asset."</h3>
                    <p class="text-slate-400">Our mission is to provide localized support with global reach, ensuring every small business has the tools of a tech giant.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="scroll-mt-24 py-24 bg-slate-950">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-white mb-16 underline decoration-blue-500 underline-offset-8">Our Ecosystem</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="p-8 rounded-2xl bg-slate-900/50 border border-white/5 hover:border-blue-500/50 transition">
                    <h3 class="text-xl font-bold text-white mb-4">IT Support & Maintenance</h3>
                    <p class="text-slate-400 text-sm mb-6">Full-scale support for hardware, software, and custom web infrastructure.</p>
                    <a href="#services" class="text-blue-400 text-sm font-bold hover:underline">View Details ↓</a>
                </div>
                <div id="freelance" class="p-8 rounded-2xl bg-slate-900/50 border border-white/5 hover:border-green-500/50 transition">
                    <h3 class="text-xl font-bold text-white mb-4">Freelance Marketplace</h3>
                    <p class="text-slate-400 text-sm mb-6">A secure platform to post projects, set milestones, and hire verified experts.</p>
                    <a href="{{ route('freelance.home') }}" class="inline-block bg-green-600/20 text-green-400 px-4 py-2 rounded-lg text-sm hover:bg-green-600 hover:text-white transition">Visit Marketplace</a>
                </div>
                <div id="ads" class="p-8 rounded-2xl bg-slate-900/50 border border-white/5 hover:border-yellow-500/50 transition">
                    <h3 class="text-xl font-bold text-white mb-4">Expert Directory</h3>
                    <p class="text-slate-400 text-sm mb-6">Boost your visibility. Small business subscriptions for prime placement.</p>
                    <a href="{{ url('/freelancer-hub#services') }}" class="text-yellow-500 text-sm font-bold hover:underline">Learn About Subscriptions</a>
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="scroll-mt-24 py-24 bg-slate-900">
        <div class="max-w-7xl mx-auto px-6">
            <div class="mb-12">
                <h2 class="text-4xl font-bold text-white">IT Support Services</h2>
                <p class="text-slate-400 mt-2">Precision technical care for your infrastructure.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-slate-950 p-6 rounded-xl border border-white/5 hover:-translate-y-2 transition duration-300">
                    <div class="text-blue-500 mb-4"><svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg></div>
                    <h4 class="text-white font-bold mb-2">Hardware Setup</h4>
                    <p class="text-slate-400 text-sm italic leading-relaxed">System builds, server rack installations, and local network configuration.</p>
                </div>
                <div class="bg-slate-950 p-6 rounded-xl border border-white/5 hover:-translate-y-2 transition duration-300">
                    <div class="text-green-500 mb-4"><svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg></div>
                    <h4 class="text-white font-bold mb-2">Web Maintenance</h4>
                    <p class="text-slate-400 text-sm italic leading-relaxed">Keeping your Laravel applications updated, secure, and lightning fast.</p>
                </div>
                <div class="bg-slate-950 p-6 rounded-xl border border-white/5 hover:-translate-y-2 transition duration-300">
                    <div class="text-yellow-500 mb-4"><svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg></div>
                    <h4 class="text-white font-bold mb-2">IT Consultations</h4>
                    <p class="text-slate-400 text-sm italic leading-relaxed">Workflow optimization and technology audits to save your company time and money.</p>
                </div>
                <div class="bg-slate-950 p-6 rounded-xl border border-white/5 hover:-translate-y-2 transition duration-300">
                    <div class="text-red-500 mb-4"><svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg></div>
                    <h4 class="text-white font-bold mb-2">24/7 Support</h4>
                    <p class="text-slate-400 text-sm italic leading-relaxed">Immediate troubleshooting for when things go wrong. Your tech lifeline.</p>
                </div>
            </div>
        </div>
    </section>

    @include('layouts.footer')
</body>
</html>
