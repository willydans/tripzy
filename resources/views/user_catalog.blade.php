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
            overflow-x: hidden;
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
            width: 100%;
            max-width: 450px;
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

        /* Sembunyikan scrollbar untuk navigasi mobile */
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="flex flex-col min-h-screen">

    @if(session('error'))
    <div id="errorToast" class="fixed top-24 md:top-10 left-1/2 transform -translate-x-1/2 z-[100] transition-all duration-500 ease-in-out w-11/12 md:w-auto">
        <div class="bg-red-500/90 backdrop-blur-md border border-red-400 px-6 md:px-10 py-4 md:py-5 rounded-2xl shadow-2xl flex flex-col md:flex-row items-center gap-3 md:gap-4 relative w-full md:min-w-[400px]">
            <button onclick="closeErrorToast()" class="absolute top-2 right-4 text-white/80 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center text-white text-xl shadow-inner shrink-0">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <p class="text-sm md:text-base font-bold text-center md:text-left tracking-wide text-white pr-4">{{ session('error') }}</p>
        </div>
    </div>
    <script>
        setTimeout(() => closeErrorToast(), 5000);
        function closeErrorToast() {
            const toast = document.getElementById('errorToast');
            if(toast) { toast.style.opacity = '0'; toast.style.transform = 'translate(-50%, -20px)'; setTimeout(() => toast.remove(), 500); }
        }
    </script>
    @endif

    <nav class="fixed top-0 md:top-6 left-0 right-0 z-50 px-4 md:px-8 py-4 md:py-0 flex flex-col md:flex-row justify-between items-center gap-4 md:gap-0 max-w-7xl mx-auto w-full transition-all bg-[#171B2D]/90 md:bg-transparent backdrop-blur-md md:backdrop-blur-none">
        
        <div class="flex justify-between items-center w-full md:w-auto md:order-2">
            <h1 class="text-3xl font-bebas tracking-widest md:hidden text-[#7A9FE0] drop-shadow-md">TRIPZY</h1>
            
            <div class="flex space-x-4 items-center">
                <a href="{{ route('user.profile') }}" class="w-10 h-10 rounded-full nav-pill flex items-center justify-center hover:bg-white/20 transition text-white overflow-hidden relative border border-white/20">
                    @if(Auth::check() && Auth::user()->profile_photo)
                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="w-full h-full object-cover absolute inset-0">
                    @else
                        <i class="fa-solid fa-user text-lg"></i>
                    @endif
                </a>

                <div class="relative inline-block text-left">
                    @php
                        $now = \Carbon\Carbon::now();
                        
                        $activeBookings = \App\Models\Booking::with('car')
                                            ->where('user_id', Auth::id())
                                            ->whereNotIn('status', ['Cancelled'])
                                            ->orderBy('created_at', 'desc')
                                            ->get();

                        $notifications = collect();

                        foreach($activeBookings as $b) {
                            $pickupTime = $b->pickup_time ?? '00:00:00';
                            $start = \Carbon\Carbon::parse($b->start_date)->format('Y-m-d');
                            $end = \Carbon\Carbon::parse($b->end_date)->format('Y-m-d');

                            $pickupDateTime = \Carbon\Carbon::parse($start . ' ' . $pickupTime);
                            $returnDateTime = \Carbon\Carbon::parse($end . ' ' . $pickupTime);

                            if (in_array($b->status, ['Ongoing', 'Paid'])) {
                                if ($now->greaterThan($returnDateTime)) {
                                    $daysLate = $now->diffInDays($returnDateTime);
                                    if ($daysLate > 0) {
                                        $notifications->push([
                                            'title' => 'TELAT MENGEMBALIKAN',
                                            'msg' => "Booking {$b->booking_code}: Anda telat mengembalikan mobil {$b->car->name} selama {$daysLate} hari. Segera kembalikan!",
                                            'color' => 'bg-red-500'
                                        ]);
                                    } else {
                                        $notifications->push([
                                            'title' => 'WAKTU HABIS',
                                            'msg' => "Booking {$b->booking_code}: Durasi rental {$b->car->name} habis hari ini. Silahkan kembalikan tepat waktu.",
                                            'color' => 'bg-orange-400'
                                        ]);
                                    }
                                } elseif ($now->greaterThanOrEqualTo($pickupDateTime) && $now->lessThan($returnDateTime)) {
                                    $notifications->push([
                                        'title' => 'WAKTU PENGAMBILAN',
                                        'msg' => "Booking {$b->booking_code}: Waktu rental dimulai! Silahkan ambil mobil {$b->car->name} di perental sekarang.",
                                        'color' => 'bg-green-400'
                                    ]);
                                } else {
                                    $daysToPickup = $now->diffInDays($pickupDateTime);
                                    $notifications->push([
                                        'title' => 'BOOKING BERHASIL',
                                        'msg' => "Booking {$b->booking_code}: Pemesanan {$b->car->name} berhasil. Jadwal ambil mobil {$daysToPickup} hari lagi.",
                                        'color' => 'bg-white'
                                    ]);
                                }
                            } elseif ($b->status == 'Pending Payment') {
                                $notifications->push([
                                    'title' => 'MENUNGGU PEMBAYARAN',
                                    'msg' => "Booking {$b->booking_code} menunggu pembayaran. Segera selesaikan dengan metode QRIS.",
                                    'color' => 'bg-blue-400'
                                ]);
                            }
                        }
                    @endphp

                    <button onclick="toggleNotif()" id="bellButton" class="w-10 h-10 rounded-full nav-pill flex items-center justify-center hover:bg-white/20 transition text-white relative z-50 cursor-pointer">
                        <i class="fa-solid fa-bell text-lg"></i>
                        @if($notifications->count() > 0)
                            <span class="absolute top-0 right-0 w-3 h-3 bg-red-500 rounded-full border-2 border-[#171B2D]"></span>
                        @endif
                    </button>

                    <div id="notifPanel" class="hidden absolute right-0 mt-4 w-[85vw] max-w-xs sm:w-80 bg-[#7A8CA5] rounded-2xl shadow-2xl z-40 transform transition-all duration-300 opacity-0 scale-95 origin-top-right border border-white/10">
                        <div class="absolute -top-2 right-4 w-5 h-5 bg-[#7A8CA5] transform rotate-45 rounded-sm border-t border-l border-white/10"></div>
                        <div class="relative z-10 p-5 md:p-6">
                            <h3 class="text-white font-bebas tracking-widest text-lg mb-4 uppercase">NOTIFICATION</h3>
                            <div class="space-y-4 max-h-64 overflow-y-auto pr-2" style="scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.5) transparent;">
                                @forelse($notifications->take(5) as $notif)
                                    <div class="flex items-start gap-3 md:gap-4">
                                        <div class="w-3 h-3 {{ $notif['color'] }} rounded-full mt-1.5 flex-shrink-0 shadow-sm border border-white/20"></div>
                                        <div>
                                            <h4 class="text-white text-sm font-bold tracking-wider uppercase drop-shadow-sm">{{ $notif['title'] }}</h4>
                                            <p class="text-gray-100 text-[11px] md:text-xs mt-1 leading-relaxed">{{ $notif['msg'] }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4">
                                        <i class="fa-regular fa-bell-slash text-2xl text-white/50 mb-2"></i>
                                        <p class="text-gray-200 text-xs italic">Belum ada notifikasi rental.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="nav-pill w-full md:w-auto overflow-x-auto hide-scroll rounded-full px-4 md:px-6 py-3 flex space-x-4 md:space-x-6 text-xs md:text-sm font-bold tracking-wider uppercase text-white md:order-1 whitespace-nowrap">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-300 transition">Home</a>
            <a href="{{ route('user.catalog') }}" class="text-blue-300 transition border-b-2 border-blue-300 pb-1">Catalog</a>
            <a href="{{ route('user.destination') }}" class="hover:text-blue-300 transition">Destination</a>
            <a href="{{ route('user.orders') }}" class="hover:text-blue-300 transition">Orders</a>
        </div>
    </nav>

    <div class="flex-grow pt-24 md:pt-0">
        <div class="hero-bg pt-12 md:pt-40 pb-16 flex flex-col items-center relative overflow-hidden">
            <div class="relative z-10 text-center px-4 mt-8">
                <p class="text-white text-xs md:text-sm font-bold tracking-[0.2em] uppercase mb-2 opacity-90 font-bebas" style="font-family: 'Bebas Neue', sans-serif; letter-spacing: 3px;">Our Collection</p>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4 md:mb-6 tracking-wide drop-shadow-md uppercase font-bebas" style="font-family: 'Bebas Neue', sans-serif; letter-spacing: 2px;">
                    Luxury Car Selection
                </h1>
                <p class="text-gray-300 text-xs md:text-sm lg:text-base max-w-3xl mb-8 leading-relaxed font-light mx-auto">
                    Our premium fleet offers a range of high-quality vehicles designed to provide a perfect balance of comfort, reliability, and performance, making every journey feel more exclusive and enjoyable.
                </p>
                <a href="#catalogGrid" class="nav-pill text-white px-8 py-2.5 rounded-full hover:bg-white hover:text-[#171B2D] transition inline-flex items-center gap-2 mx-auto font-medium text-sm md:text-base">
                    Explore Now <i class="fa-solid fa-arrow-down"></i>
                </a>
            </div>
            
            <div class="max-w-7xl w-full mx-auto px-4 mt-12 md:mt-16 mb-4 flex flex-col lg:flex-row justify-between items-center gap-4 md:gap-6 relative z-10">
                <div class="relative w-full lg:w-96">
                    <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 transform -translate-y-1/2 text-[#8CA1C4]"></i>
                    <input type="text" id="searchInput" placeholder="Find Your Perfect Car..." class="w-full bg-white/5 border border-white/10 text-sm rounded-full py-3 pl-12 pr-4 focus:outline-none focus:border-white/30 text-white placeholder-[#8CA1C4] backdrop-blur-md transition">
                </div>
                
                <div class="w-full lg:w-auto overflow-x-auto hide-scroll rounded-full">
                    <div class="flex flex-nowrap lg:flex-wrap justify-start lg:justify-center items-center gap-2 text-xs md:text-sm font-medium text-[#8CA1C4] bg-white/5 border border-white/10 rounded-full px-2 py-1.5 backdrop-blur-md min-w-max" id="filterContainer">
                        <button class="filter-btn bg-white/10 text-white px-4 md:px-5 py-1.5 rounded-full shadow-sm font-semibold transition" data-filter="all">All</button>
                        @php
                            // Ambil daftar kategori dinamis dari database biar anti bocor
                            $categories = $cars->pluck('category')->unique();
                        @endphp
                        @foreach($categories as $category)
                            @php
                                $filterSlug = \Illuminate\Support\Str::slug($category);
                            @endphp
                            <button class="filter-btn px-3 md:px-4 py-1.5 hover:text-white transition rounded-full whitespace-nowrap" data-filter="{{ $filterSlug }}">{{ trim($category) }}</button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 pb-24 mt-6 md:mt-10 relative z-10">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6" id="catalogGrid">
                
                @foreach($cars as $car)
                    @php
                        // Format slug biar cocok mutlak sama filter
                        $carCategorySlug = \Illuminate\Support\Str::slug($car->category);
                        $carNameSafe = trim(strtolower($car->name));
                    @endphp
                    <div class="car-card bg-gradient-to-b from-[#6D819C] to-[#4C5B79] rounded-3xl p-5 md:p-6 shadow-xl border border-white/5 flex flex-col justify-between relative overflow-hidden" 
                         data-category="{{ $carCategorySlug }}" 
                         data-name="{{ $carNameSafe }}">
                        
                        @if($car->stock <= 0)
                            <div class="absolute inset-0 bg-[#171B2D]/70 backdrop-blur-[2px] z-20 flex items-center justify-center pointer-events-none">
                                <span class="bg-red-500 text-white px-5 py-2 rounded-full font-bold text-sm uppercase tracking-wider transform -rotate-12 border-2 border-white/20 shadow-2xl">
                                    Habis Disewa
                                </span>
                            </div>
                        @endif

                        <div class="relative z-10">
                            <img src="{{ asset($car->image_path) }}" alt="{{ $car->name }}" class="w-full h-32 md:h-40 object-contain drop-shadow-2xl mb-4">
                            <div class="flex justify-between items-end mb-4">
                                <div class="w-2/3 pr-2">
                                    <h3 class="text-[10px] md:text-xs font-bold text-gray-200 uppercase mb-1 tracking-wider truncate">{{ $car->name }}</h3>
                                    <div class="text-xl md:text-2xl font-extrabold text-white">
                                        Rp {{ number_format($car->price_per_day, 0, ',', '.') }} 
                                        <span class="text-[10px] font-normal text-gray-300">/day</span>
                                    </div>
                                    <p class="text-[9px] text-gray-300 mt-1 uppercase tracking-wide leading-relaxed">
                                        Seats for {{ $car->seats }} &bull; {{ $car->transmission }} &bull; {{ $car->fuel_type }}
                                    </p>
                                </div>
                                <div class="w-1/3 flex justify-end">
                                    <img src="{{ asset($car->image_path) }}" alt="{{ $car->name }}" class="w-12 md:w-16 drop-shadow-lg opacity-80 object-contain">
                                </div>
                            </div>
                        </div>

                        <div class="relative z-30">
                            @if($car->stock > 0)
                                <a href="{{ route('user.car.detail', $car->slug) }}" class="block w-full py-2 md:py-2.5 rounded-full bg-gradient-to-r from-white/30 to-white/10 border border-white/30 text-white text-center text-sm md:text-base font-bold shadow-md hover:bg-white/40 transition backdrop-blur-md mt-2">
                                    Book Now
                                </a>
                            @else
                                <button disabled class="block w-full py-2 md:py-2.5 rounded-full bg-white/10 border border-white/10 text-white/50 text-center text-sm md:text-base font-bold cursor-not-allowed mt-2">
                                    Stock Kosong
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach

            </div>

            <div id="emptyState" class="text-center py-10 {{ $cars->count() > 0 ? 'hidden' : '' }}">
                <p class="text-gray-400 text-base md:text-lg">Oops! Mobil tidak ditemukan.</p>
            </div>
        </div>
    </div>

    <footer class="mt-auto border-t border-white/10 pt-10 md:pt-12 pb-8 bg-[#171B2D]">
        <div class="max-w-7xl mx-auto px-4 lg:px-8 flex flex-col md:flex-row justify-between items-start gap-8 md:gap-0">
            <div class="w-full md:w-auto">
                <h2 class="text-4xl md:text-5xl font-bold font-bebas tracking-widest text-[#7A9FE0] mb-4">Tripzy</h2>
                <div class="flex space-x-4 text-2xl text-gray-400">
                    <a href="#" class="hover:text-white transition"><i class="fa-brands fa-discord"></i></a>
                    <a href="#" class="hover:text-white transition"><i class="fa-brands fa-whatsapp"></i></a>
                    <a href="#" class="hover:text-white transition"><i class="fa-brands fa-telegram"></i></a>
                    <a href="#" class="hover:text-white transition"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>
            <div class="w-full md:w-1/3">
                <h3 class="text-xl font-bold text-[#7A9FE0] mb-4">Contact Us</h3>
                <ul class="space-y-4 text-xs md:text-sm font-medium text-gray-400">
                    <li class="flex items-start gap-3"><i class="fa-solid fa-location-dot mt-1 text-[#7A9FE0] shrink-0"></i><span>Jl. Prof. Dr. Ir. Sumantri Brojonegoro No.1, Gedong Meneng, Kec. Rajabasa, Bandar Lampung 35141</span></li>
                    <li class="flex items-center gap-3"><i class="fa-solid fa-phone text-[#7A9FE0] shrink-0"></i><span>+6285278139801</span></li>
                    <li class="flex items-center gap-3"><i class="fa-solid fa-envelope text-[#7A9FE0] shrink-0"></i><span>tripzy@gmail.com</span></li>
                    <li class="flex items-center gap-3"><i class="fa-solid fa-clock text-[#7A9FE0] shrink-0"></i><span>Senin - Minggu<br>24 Jam</span></li>
                </ul>
            </div>
        </div>
    </footer>

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

        // LOGIKA FILTER & SEARCH DINAMIS (REAL-TIME) - SUDAH FIX 100%
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
                    // 🟢 INI KUNCI FIX-NYA: Pakai '===' biar MPV nggak disamakan sama Premium MPV
                    const matchesFilter = (currentFilter === 'all' || category === currentFilter);

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

                    // Filter sekarang pakai atribut khusus 'data-filter'
                    currentFilter = this.getAttribute('data-filter').toLowerCase();
                    filterCars();
                });
            });

            if(searchInput) {
                searchInput.addEventListener('input', function(e) {
                    currentSearch = e.target.value.toLowerCase().trim();
                    filterCars();
                });
            }
        });
    </script>
</body>
</html>