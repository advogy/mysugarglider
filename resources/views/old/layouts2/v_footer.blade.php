<footer class="bg-bark text-white mt-20">
    <div class="max-w-6xl mx-auto px-6 pt-16 pb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">

            <!-- Brand -->
            <div class="lg:col-span-2">
                <div class="flex items-center gap-3 mb-4">
                    <img src="{{ asset('assets/images/logo/logo.svg') }}" alt="MySugarGlider" class="h-8 brightness-0 invert">
                </div>
                <p class="text-gray-400 text-sm leading-relaxed max-w-xs">
                    Platform komunitas peternak sugar glider Indonesia. Catat data, lacak silsilah, dan temukan adopsi terbaik.
                </p>
                <div class="flex items-center gap-4 mt-6">
                    <a href="https://www.instagram.com/mysugarglider.id/" target="_blank"
                       class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center hover:bg-sage transition-colors duration-200">
                        <i class="bi bi-instagram text-sm"></i>
                    </a>
                    <a href="https://wa.me/6285755333232" target="_blank"
                       class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center hover:bg-sage transition-colors duration-200">
                        <i class="bi bi-whatsapp text-sm"></i>
                    </a>
                    <a href="mailto:info@mysugarglider.id"
                       class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center hover:bg-sage transition-colors duration-200">
                        <i class="bi bi-envelope text-sm"></i>
                    </a>
                </div>
            </div>

            <!-- Navigasi -->
            <div>
                <h5 class="font-semibold text-white mb-4 text-sm tracking-wider uppercase">Navigasi</h5>
                <ul class="space-y-3">
                    <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-honey text-sm transition-colors duration-200">Beranda</a></li>
                    <li><a href="{{ route('collections') }}" class="text-gray-400 hover:text-honey text-sm transition-colors duration-200">Koleksi</a></li>
                    <li><a href="{{ route('shelters') }}" class="text-gray-400 hover:text-honey text-sm transition-colors duration-200">Kandang</a></li>
                    <li><a href="{{ route('pedigree') }}" class="text-gray-400 hover:text-honey text-sm transition-colors duration-200">Silsilah</a></li>
                    <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-honey text-sm transition-colors duration-200">Tentang</a></li>
                </ul>
            </div>

            <!-- Kontak -->
            <div>
                <h5 class="font-semibold text-white mb-4 text-sm tracking-wider uppercase">Kontak</h5>
                <ul class="space-y-3">
                    <li class="flex items-start gap-2 text-gray-400 text-sm">
                        <i class="bi bi-geo-alt mt-0.5 text-sage-light flex-shrink-0"></i>
                        <span>Kota Surabaya, Jawa Timur, Indonesia</span>
                    </li>
                    <li class="flex items-center gap-2 text-gray-400 text-sm">
                        <i class="bi bi-envelope text-sage-light flex-shrink-0"></i>
                        <a href="mailto:info@mysugarglider.id" class="hover:text-honey transition-colors duration-200">info@mysugarglider.id</a>
                    </li>
                    <li class="flex items-center gap-2 text-gray-400 text-sm">
                        <i class="bi bi-whatsapp text-sage-light flex-shrink-0"></i>
                        <a href="https://wa.me/6285755333232" target="_blank" class="hover:text-honey transition-colors duration-200">+62 857 5533 3232</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bottom bar -->
        <div class="border-t border-white/10 pt-6 flex flex-col md:flex-row items-center justify-between gap-3">
            <p class="text-gray-500 text-xs">
                &copy; 2022–{{ date('Y') }} <span class="text-gray-400">{{ config('app.name') }}</span>. All rights reserved.
            </p>
            <p class="text-gray-500 text-xs">
                Developed by <a href="https://athoria.me" target="_blank" class="text-sage-light hover:text-honey transition-colors duration-200">AthoRia.me</a>
            </p>
        </div>
    </div>
</footer>

<!-- Back to top -->
<button id="back-to-top"
    class="fixed bottom-6 right-6 w-10 h-10 bg-sage text-white rounded-2xl shadow-hover hidden items-center justify-center
           hover:bg-sage-dark transition-all duration-200 z-50">
    <i class="bi bi-arrow-up text-sm"></i>
</button>

<!-- GLightbox -->
<script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>

<!-- PureCounter -->
<script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>

<script>
    // Lightbox
    GLightbox({ selector: '.glightbox' });

    // PureCounter
    new PureCounter();

    // Back to top
    const btn = document.getElementById('back-to-top');
    window.addEventListener('scroll', () => {
        btn.classList.toggle('hidden', window.scrollY < 300);
        btn.classList.toggle('flex', window.scrollY >= 300);
    });
    btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

    // Mobile menu toggle
    const mobileToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu   = document.getElementById('mobile-menu');
    if (mobileToggle && mobileMenu) {
        mobileToggle.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // Navbar scroll effect
    const navbar = document.getElementById('main-navbar');
    if (navbar) {
        // If no full-screen hero on this page, start in glass mode
        const hasHero = document.getElementById('hero-section');
        if (!hasHero) navbar.classList.add('navbar-scrolled');

        window.addEventListener('scroll', () => {
            if (hasHero) {
                navbar.classList.toggle('navbar-scrolled', window.scrollY > 80);
            }
        });
    }
</script>

@stack('scripts')
</body>
</html>
