<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalog - Tripzy Dashboard</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #171B2D; 
            color: white;
        }

        .hero-bg {
            position: relative;
            background-color: #171B2D;
            background-image: radial-gradient(circle at 50% 30%, #263655 0%, transparent 60%);
        }
        
        .hero-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(
                to right,
                transparent,
                transparent 60px,
                rgba(255, 255, 255, 0.015) 60px,
                rgba(255, 255, 255, 0.015) 120px
            );
            z-index: 0;
        }

        .hero-bg::after {
            content: '';
            position: absolute;
            bottom: -80px; 
            left: 0;
            width: 450px;
            height: 350px;
            background-image: 
                linear-gradient(to right, rgba(255,255,255,0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255,255,255,0.04) 1px, transparent 1px);
            background-size: 25px 25px;
            -webkit-mask-image: linear-gradient(to top right, black 10%, transparent 80%);
            mask-image: linear-gradient(to top right, black 10%, transparent 80%);
            z-index: 0;
        }

        .nav-pill {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .car-card { transition: transform 0.3s ease, box-shadow 0.3s ease, opacity 0.3s ease; }
        .car-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.4);
        }
    </style>
</head>
<body class="overflow-x-hidden flex flex-col min-h-screen">

    <!-- NAVBAR (Fixed) -->
    <nav class="fixed top-6 left-0 right-0 z-50 px-8 flex justify-between items-center relative z-50">
        <div class="nav-pill rounded-full px-6 py-3 flex space-x-6 text-sm font-bold tracking-wider uppercase text-white">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-300 transition">Home</a>
            <a href="{{ route('user.catalog') }}" class="text-blue-300 transition border-b-2 border-blue-300 pb-1">Catalog</a>
            <a href="{{ route('user.destination') }}" class="hover:text-blue-300 transition">Destination</a>
            <a href="{{ route('user.orders') }}" class="hover:text-blue-300 transition">Orders</a>
        </div>

        <div class="flex space-x-4 items-center">
            <!-- Icon User Dinamis -->
            <a href="{{ route('user.profile') }}" class="w-10 h-10 rounded-full nav-pill flex items-center justify-center hover:bg-white/20 transition text-white overflow-hidden relative border border-white/20">
                @if(Auth::check() && Auth::user()->profile_photo)
                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="w-full h-full object-cover absolute inset-0">
                @else
                    <i class="fa-solid fa-user text-lg"></i>
                @endif
            </a>

            <!-- BELL ICON & NOTIFICATION DROPDOWN -->
            <div class="relative inline-block text-left">
                <button onclick="toggleNotif()" id="bellButton" class="w-10 h-10 rounded-full nav-pill flex items-center justify-center hover:bg-white/20 transition text-white relative z-50">
                    <i class="fa-solid fa-bell text-lg"></i>
                    @php
                        $recentBookings = \App\Models\Booking::where('user_id', Auth::id())->orderBy('created_at', 'desc')->take(5)->get();
                    @endphp
                    @if($recentBookings->count() > 0)
                        <span class="absolute top-0 right-0 w-3 h-3 bg-red-500 rounded-full border-2 border-[#1A1F36]"></span>
                    @endif
                </button>

                <div id="notifPanel" class="hidden absolute right-0 mt-4 w-80 bg-[#7A8CA5] rounded-2xl shadow-2xl z-40 transform transition-all duration-300 opacity-0 scale-95 origin-top-right">
                    <div class="absolute -top-2 right-4 w-5 h-5 bg-[#7A8CA5] transform rotate-45 rounded-sm"></div>
                    <div class="relative z-10 p-6">
                        <h3 class="text-white font-bebas tracking-widest text-lg mb-5 uppercase">NOTIFICATION</h3>
                        <div class="space-y-5 max-h-64 overflow-y-auto pr-2" style="scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.5) transparent;">
                            @forelse($recentBookings as $notif)
                                @php $days = \Carbon\Carbon::parse($notif->start_date)->diffInDays($notif->end_date) + 1; @endphp
                                <div class="flex items-start gap-4">
                                    <div class="w-3 h-3 bg-white rounded-full mt-1.5 flex-shrink-0 shadow-sm"></div>
                                    <div>
                                        <h4 class="text-white text-sm font-bold tracking-wider uppercase drop-shadow-sm">BOOKING BERHASIL</h4>
                                        <p class="text-gray-100 text-xs mt-1 leading-relaxed">Berhasil melakukan booking selama {{ $days }} hari</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-200 text-xs text-center italic mt-2">Belum ada aktivitas booking.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT (FLEX GROW) -->
    <div class="flex-grow">
        <!-- HERO SECTION -->
        <div class="hero-bg pt-40 pb-16 flex flex-col items-center relative overflow-hidden">
            <div class="relative z-10 text-center px-4 mt-8">
                <p class="text-white text-sm font-bold tracking-[0.2em] uppercase mb-2 opacity-90 font-bebas" style="font-family: 'Bebas Neue', sans-serif; letter-spacing: 3px;">Our Collection</p>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 tracking-wide drop-shadow-md uppercase font-bebas" style="font-family: 'Bebas Neue', sans-serif; letter-spacing: 2px;">
                    Luxury Car Selection
                </h1>
                <p class="text-gray-300 text-sm md:text-base max-w-3xl mb-8 leading-relaxed font-light mx-auto">
                    Our premium fleet offers a range of high-quality vehicles designed to provide a perfect balance of comfort, reliability, and performance, making every journey feel more exclusive and enjoyable.
                </p>
                <button class="nav-pill text-white px-8 py-2.5 rounded-full hover:bg-white hover:text-[#171B2D] transition flex items-center gap-2 mx-auto font-medium">
                    Book Now <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
            
            <!-- FILTER & SEARCH BAR -->
            <div class="max-w-7xl w-full mx-auto px-4 mt-16 mb-4 flex flex-col lg:flex-row justify-between items-center gap-6 relative z-10">
                <!-- Search Bar -->
                <div class="relative w-full lg:w-96">
                    <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 transform -translate-y-1/2 text-[#8CA1C4]"></i>
                    <input type="text" id="searchInput" placeholder="Find Your Perfect Car..." class="w-full bg-white/5 border border-white/10 text-sm rounded-full py-3 pl-12 pr-4 focus:outline-none focus:border-white/30 text-white placeholder-[#8CA1C4] backdrop-blur-md transition">
                </div>
                
                <!-- Filter Buttons -->
                <div class="flex flex-wrap justify-center items-center gap-1 text-xs md:text-sm font-medium text-[#8CA1C4] bg-white/5 border border-white/10 rounded-full px-2 py-1.5 backdrop-blur-md" id="filterContainer">
                    <button class="filter-btn bg-white/10 text-white px-5 py-1.5 rounded-full shadow-sm font-semibold transition" data-filter="all">All</button>
                    <button class="filter-btn px-4 py-1.5 hover:text-white transition rounded-full" data-filter="suv">SUV</button>
                    <button class="filter-btn px-4 py-1.5 hover:text-white transition rounded-full" data-filter="mpv">MPV</button>
                    <button class="filter-btn px-4 py-1.5 hover:text-white transition rounded-full" data-filter="premium suv">Premium SUV</button>
                    <button class="filter-btn px-4 py-1.5 hover:text-white transition rounded-full" data-filter="premium mpv">Premium MPV</button>
                    <button class="filter-btn px-4 py-1.5 hover:text-white transition rounded-full" data-filter="luxury mpv & suv">Luxury MPV & SUV</button>
                </div>
            </div>
        </div>

        <!-- CATALOG GRID (DYNAMIC) -->
        <div class="max-w-7xl mx-auto px-4 pb-24 mt-10 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="catalogGrid">
                
                @foreach($cars as $car)
                    <div class="car-card bg-gradient-to-b from-[#6D819C] to-[#4C5B79] rounded-3xl p-6 shadow-xl border border-white/5 flex flex-col justify-between" 
                         data-category="{{ strtolower($car->category) }}" 
                         data-name="{{ strtolower($car->name) }}">
                        <div>
                            <img src="{{ asset($car->image_path) }}" alt="{{ $car->name }}" class="w-full h-40 object-contain drop-shadow-2xl mb-4">
                            <div class="flex justify-between items-end mb-4">
                                <div>
                                    <h3 class="text-xs font-bold text-gray-200 uppercase mb-1 tracking-wider">{{ $car->name }}</h3>
                                    <div class="text-2xl font-extrabold text-white">
                                        Rp {{ number_format($car->price_per_day, 0, ',', '.') }} 
                                        <span class="text-[10px] font-normal text-gray-300">/day</span>
                                    </div>
                                    <p class="text-[9px] text-gray-300 mt-1 uppercase tracking-wide">
                                        Seats for {{ $car->seats }} passengers &bull; {{ $car->transmission }} &bull; {{ $car->fuel_type }}
                                    </p>
                                </div>
                                <img src="{{ asset($car->image_path) }}" alt="{{ $car->name }}" class="w-16 drop-shadow-lg opacity-80">
                            </div>
                        </div>
                        <a href="{{ route('user.car.detail', $car->slug) }}" class="block w-full py-2.5 rounded-full bg-gradient-to-r from-white/30 to-white/10 border border-white/30 text-white text-center font-bold shadow-md hover:bg-white/40 transition backdrop-blur-md">
                            Book Now
                        </a>
                    </div>
                @endforeach

            </div>

            <!-- Tampilan kalau pencarian gak ketemu / database kosong -->
            <div id="emptyState" class="text-center py-10 {{ $cars->count() > 0 ? 'hidden' : '' }}">
                <p class="text-gray-400 text-lg">Oops! Mobil tidak ditemukan.</p>
            </div>
        </div>
    </div>

    <!-- FOOTER (UPDATED DARK THEME) -->
    <footer class="mt-auto border-t border-white/10 pt-12 pb-8 bg-[#171B2D]">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between items-start">
            <div class="mb-8 md:mb-0">
                <h2 class="text-5xl font-bold font-bebas tracking-widest text-[#7A9FE0] mb-4">Tripzy</h2>
                <div class="flex space-x-4 text-2xl text-gray-400">
                    <a href="#" class="hover:text-white transition"><i class="fa-brands fa-discord"></i></a>
                    <a href="#" class="hover:text-white transition"><i class="fa-brands fa-whatsapp"></i></a>
                    <a href="#" class="hover:text-white transition"><i class="fa-brands fa-telegram"></i></a>
                    <a href="#" class="hover:text-white transition"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>
            <div class="md:w-1/3">
                <h3 class="text-xl font-bold text-[#7A9FE0] mb-4">Contact Us</h3>
                <ul class="space-y-4 text-xs font-medium text-gray-400">
                    <li class="flex items-start gap-3"><i class="fa-solid fa-location-dot mt-1 text-[#7A9FE0]"></i><span>Jl. Prof. Dr. Ir. Sumantri Brojonegoro No.1, Gedong Meneng, Kec. Rajabasa, Bandar Lampung 35141</span></li>
                    <li class="flex items-center gap-3"><i class="fa-solid fa-phone text-[#7A9FE0]"></i><span>+6285276139801</span></li>
                    <li class="flex items-center gap-3"><i class="fa-solid fa-envelope text-[#7A9FE0]"></i><span>tripzy@gmail.com</span></li>
                    <li class="flex items-start gap-3"><i class="fa-solid fa-clock mt-1 text-[#7A9FE0]"></i><span>Senin - Minggu<br>24 Jam</span></li>
                </ul>
            </div>
        </div>
    </footer>

    <!-- JAVASCRIPT LENGKAP -->
    <script>
        // Logika Dropdown Notifikasi
        function toggleNotif() {
            const panel = document.getElementById('notifPanel');
            if (panel.classList.contains('hidden')) {
                panel.classList.remove('hidden');
                setTimeout(() => { panel.classList.remove('opacity-0', 'scale-95'); }, 10);
            } else {
                panel.classList.add('opacity-0', 'scale-95');
                setTimeout(() => { panel.classList.add('hidden'); }, 300); 
            }
        }
        document.addEventListener('click', function(event) {
            const panel = document.getElementById('notifPanel');
            const bellBtn = document.getElementById('bellButton');
            if (panel && bellBtn && !panel.contains(event.target) && !bellBtn.contains(event.target)) {
                if (!panel.classList.contains('hidden')) {
                    panel.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => { panel.classList.add('hidden'); }, 300);
                }
            }
        });

        // LOGIKA FILTER & SEARCH DINAMIS (REAL-TIME)
        document.addEventListener("DOMContentLoaded", function() {
            const filterBtns = document.querySelectorAll('.filter-btn');
            const searchInput = document.getElementById('searchInput');
            const carCards = document.querySelectorAll('.car-card');
            const emptyState = document.getElementById('emptyState');

            let currentFilter = 'all';
            let currentSearch = '';

            function filterCars() {
                let visibleCount = 0;
                
                carCards.forEach(card => {
                    const category = card.getAttribute('data-category');
                    const name = card.getAttribute('data-name');
                    
                    const matchesSearch = name.includes(currentSearch);
                    const matchesFilter = (currentFilter === 'all' || category.includes(currentFilter) || currentFilter.includes(category));

                    if (matchesFilter && matchesSearch) {
                        card.classList.remove('hidden');
                        card.classList.add('flex');
                        setTimeout(() => { card.style.opacity = '1'; }, 10);
                        visibleCount++;
                    } else {
                        card.style.opacity = '0';
                        setTimeout(() => { 
                            card.classList.add('hidden'); 
                            card.classList.remove('flex'); 
                        }, 300);
                    }
                });

                setTimeout(() => {
                    if (visibleCount === 0) {
                        emptyState.classList.remove('hidden');
                    } else {
                        emptyState.classList.add('hidden');
                    }
                }, 300);
            }

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    filterBtns.forEach(b => {
                        b.classList.remove('bg-white/10', 'text-white', 'shadow-sm', 'font-semibold');
                        b.classList.add('hover:text-white');
                    });
                    
                    this.classList.add('bg-white/10', 'text-white', 'shadow-sm', 'font-semibold');
                    this.classList.remove('hover:text-white');

                    currentFilter = this.getAttribute('data-filter').toLowerCase();
                    filterCars();
                });
            });

            if(searchInput) {
                searchInput.addEventListener('input', function(e) {
                    currentSearch = e.target.value.toLowerCase();
                    filterCars();
                });
            }
        });
    </script>
</body>
</html>