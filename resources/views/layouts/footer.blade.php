<footer id="contact" class="bg-slate-950 text-slate-400 pt-20 pb-10 border-t border-white/5">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
            
            <div class="space-y-6">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('storage/pictures/logo.png') }}" alt="Banstech Logo" class="logoimage me-2 h-8 w-16">
                </div>
                <p class="text-sm leading-relaxed">
                    Banstech is a South African-based IT leader providing complete digital ecosystems—from technical infrastructure to global freelance talent.
                </p>
                <div class="flex gap-4">
                    <a href="https://www.linkedin.com/in/bans-technology-2334343b5/" target="_blank" class="hover:text-blue-400 transition"><i class="bi bi-linkedin"></i></a>
                    {{-- <a href="#" class="hover:text-blue-400 transition"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="hover:text-blue-400 transition"><i class="bi bi-twitter-x"></i></a> --}}
                </div>
            </div>

            <div>
                <h4 class="text-white font-bold uppercase tracking-widest text-xs mb-6">Contact Us</h4>
                <ul class="space-y-4 text-sm">
                    <li>
                        <a href="mailto:support@banstech.co.za" class="flex items-center gap-3 hover:text-blue-400 transition">
                            <i class="bi bi-envelope-fill text-blue-500"></i>
                            support@banstech.co.za
                        </a>
                    </li>
                    <li>
                        <a href="tel:+27106341558" class="flex items-center gap-3 hover:text-blue-400 transition">
                            <i class="bi bi-telephone-fill text-blue-500"></i>
                            (+27) 106 3415 58
                        </a>
                    </li>
                    <li>
                        <a href="https://maps.google.com/?q=51+Harrison+St,Johannesburg,2001" target="_blank" class="flex items-center gap-3 hover:text-blue-400 transition">
                            <i class="bi bi-geo-alt-fill text-blue-500"></i>
                            51 Harrison St, Johannesburg, 2001
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold uppercase tracking-widest text-xs mb-6">Quick Links</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="#top" class="hover:text-white transition">Home</a></li>
                    <li><a href="#about" class="hover:text-white transition">About Banstech</a></li>
                    <li><a href="#services" class="hover:text-white transition">IT Support</a></li>
                    <li><a href="{{ route('freelance.home') }}" class="hover:text-white transition">Freelance Portal</a></li>
                    <li><a href="#ads" class="hover:text-white transition">Expert Ads</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold uppercase tracking-widest text-xs mb-6">Our Services</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-center gap-2 italic"><span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span> IT Services</li>
                    <li class="flex items-center gap-2 italic"><span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Virtual Assistance</li>
                    <li class="flex items-center gap-2 italic"><span class="w-1.5 h-1.5 bg-yellow-500 rounded-full"></span> Physical Services</li>
                    <li class="flex items-center gap-2 italic"><span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span> Tech Consultations</li>
                </ul>
            </div>
        </div>

        <div class="pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="text-xs text-slate-500">
                © 2026 Banstech. All rights reserved. Registered in South Africa.
            </p>
            <div class="flex gap-8 text-xs font-medium">
                <a href="#" class="hover:text-white transition">Policies</a>
                <a href="#" class="hover:text-white transition">Related Information</a>
                <a href="#top" class="text-blue-400 hover:text-blue-300 transition flex items-center gap-1">
                    Back to Top <i class="bi bi-arrow-up"></i>
                </a>
            </div>
        </div>
    </div>
</footer>