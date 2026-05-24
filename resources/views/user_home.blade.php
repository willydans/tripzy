<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Tripzy Dashboard</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #121826; 
            color: white;
            overflow-x: hidden; /* Biar animasi text ga bikin layar HP geser nyamping */
        }

        /* --- ANIMASI HERO SECTION --- */
        .hero-section {
            background: radial-gradient(circle at center, #2A3F6A 0%, #121826 60%);
            height: 100vh;
            width: 100%;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .anim-text {
            font-family: 'Bebas Neue', sans-serif;
            font-style: italic;
            position: absolute;
            z-index: 1;
            transform-origin: center center;
            letter-spacing: 5px;
            transform: scale(2.5) translateY(-15%);
            opacity: 0.15;
            font-size: 25vw; /* Pake VW biar responsif ukurannya di HP/Laptop */
            white-space: nowrap;
        }

        .anim-car {
            position: absolute;
            z-index: 10;
            width: 90%;
            max-width: 1000px;
            transform: translateY(15%) scale(0.9);
        }

        .anim-desc {
            position: absolute;
            bottom: 40px;
            z-index: 20;
            width: 100%;
            padding: 0 5%;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            opacity: 1;
            transform: translateY(0);
        }

        .play-anim .anim-text { animation: zoomText 6.5s cubic-bezier(0.25, 0.8, 0.25, 1) forwards; }
        .play-anim .anim-car { animation: slideUpCar 5s 1.5s cubic-bezier(0.25, 0.8, 0.25, 1) forwards; opacity: 0; }
        .play-anim .anim-desc { animation: fadeInUpDesc 2s 4.5s ease-out forwards; opacity: 0; }

        @keyframes zoomText {
            0%   { transform: scale(1) translateY(0); opacity: 1; }
            20%  { transform: scale(1.05) translateY(-2%); opacity: 1; }
            100% { transform: scale(2.5) translateY(-15%); opacity: 0.15; }
        }
        @keyframes slideUpCar {
            0%   { transform: translateY(100%) scale(1); opacity: 0; }
            30%  { transform: translateY(5%) scale(1.05); opacity: 1; }
            100% { transform: translateY(15%) scale(0.9); opacity: 1; }
        }
        @keyframes fadeInUpDesc {
            0%   { opacity: 0; transform: translateY(40px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* Responsive Hero Adjustments */
        @media (max-width: 768px) {
            .anim-desc {
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: 1.5rem;
                bottom: 20px;
            }
            @keyframes slideUpCar {
                0%   { transform: translateY(100%) scale(1); opacity: 0; }
                30%  { transform: translateY(-5%) scale(1.1); opacity: 1; }
                100% { transform: translateY(0%) scale(1); opacity: 1; }
            }
        }

        /* Custom Navbar Pill */
        .nav-pill {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .cat-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .cat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.5); }
        
        /* Sembunyikan scrollbar untuk navigasi mobile */
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body id="main-body" class="overflow-y-hidden">

    <nav class="fixed top-0 md:top-6 left-0 right-0 z-50 px-4 md:px-8 py-4 md:py-0 flex flex-col md:flex-row justify-between items-center gap-4 md:gap-0 max-w-7xl mx-auto w-full transition-all bg-[#121826]/90 md:bg-transparent backdrop-blur-md md:backdrop-blur-none">
        
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

                    <button onclick="toggleNotif()" id="bellButton" class="w-10 h-10 rounded-full nav-pill flex items-center justify-center hover:bg-white/20 transition text-white relative z-50">
                        <i class="fa-solid fa-bell text-lg"></i>
                        @if($notifications->count() > 0)
                            <span class="absolute top-0 right-0 w-3 h-3 bg-red-500 rounded-full border-2 border-[#1A1F36]"></span>
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
            <a href="{{ route('dashboard') }}" class="text-blue-300 transition border-b-2 border-blue-300 pb-1">Home</a>
            <a href="{{ route('user.catalog') }}" class="hover:text-blue-300 transition">Catalog</a>
            <a href="{{ route('user.destination') }}" class="hover:text-blue-300 transition">Destination</a>
            <a href="{{ route('user.orders') }}" class="hover:text-blue-300 transition">Orders</a>
        </div>
    </nav>

    <div id="hero-wrapper" class="hero-section">
        <h1 class="anim-text">TRIPZY</h1>
        <img src="https://www.pngplay.com/wp-content/uploads/13/Tesla-PNG-Free-File-Download.png" alt="Hero Car" class="anim-car object-contain drop-shadow-[0_20px_30px_rgba(0,0,0,0.8)]">
        
        <div class="anim-desc">
            <div class="max-w-md">
                <p class="text-[11px] md:text-sm text-gray-300 leading-relaxed font-light">
                    Discover a new level of comfort and performance with our premium vehicles, carefully selected to deliver a smooth, reliable, and every trip becomes a memorable experience.
                </p>
            </div>
            
            <a href="{{ route('user.catalog') }}" class="nav-pill px-10 py-3 md:py-2.5 rounded-full font-bold hover:bg-white hover:text-black transition border-white/40 inline-flex items-center justify-center whitespace-nowrap text-sm">
                Detail
            </a>

            <div class="max-w-xs md:text-right hidden md:block">
                <p class="text-xs md:text-sm text-gray-300 leading-relaxed font-light">
                    Elevate your travel experience with vehicles designed for comfort, reliability, and style because every journey deserves the best.
                </p>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 py-16 md:py-24">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-8 md:mb-12 tracking-wide uppercase" style="font-family: 'Poppins', sans-serif;">Car by Category</h2>
        
        <div class="grid grid-cols-2 gap-3 md:gap-4">
            <div class="cat-card bg-black rounded-2xl h-32 md:h-40 relative overflow-hidden flex items-end justify-start p-4 cursor-pointer">
                <img src="https://lh7-rt.googleusercontent.com/docsz/AD_4nXffY-FoeXKYte6W1UY3iuxD9_KEdvf0omethmnckaqamaXPs4nfbmCe4tLxIDUcQMJiwsh5wtMTkl-s9gniBkZpXZmzR4f_esdPlYvkPwq2RbGqKb8Cu3inyR1fIOxyrn7JCd2fFsf4_th0suuFDvrqt4g?key=M190abu9NCcURv-H7bmJ7w" alt="MPV" class="absolute inset-0 w-full h-full object-cover opacity-60">
                <h3 class="relative z-10 text-white font-extrabold text-lg md:text-xl uppercase drop-shadow-md">MPV</h3>
            </div>
            <div class="cat-card bg-black rounded-2xl h-32 md:h-40 relative overflow-hidden flex items-end justify-end p-4 cursor-pointer">
                <img src="https://www3.wuling.id/wp-content/uploads/2022/09/Beragam-Jenis-Mobil-SUV-Cover.jpg" alt="SUV" class="absolute inset-0 w-full h-full object-cover opacity-60">
                <h3 class="relative z-10 text-white font-extrabold text-lg md:text-xl uppercase drop-shadow-md">SUV</h3>
            </div>
            
            <div class="cat-card col-span-2 rounded-2xl h-40 md:h-48 relative overflow-hidden flex items-end justify-center pb-4 cursor-pointer">
                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://scene7.toyota.eu/is/image/toyotaeurope/2025-lexus-lm-gallery-exterior-02-1920x1080-00:Large-Landscape?ts=0&resMode=sharp2&op_usm=1.75,0.3,2,0');"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                <h3 class="relative z-10 text-white font-bold text-lg md:text-xl uppercase tracking-widest drop-shadow-md">Luxury SUV MPV</h3>
            </div>

            <div class="cat-card bg-black rounded-2xl h-32 md:h-40 relative overflow-hidden flex items-end justify-start p-4 cursor-pointer">
                <img src="https://carro.id/blog/wp-content/uploads/2021/07/5d8e4161-2020-nissan-serena-facelift-japan-spec-0-e1619530135865.jpg" alt="PRE MPV" class="absolute inset-0 w-full h-full object-cover opacity-60">
                <h3 class="relative z-10 text-white font-extrabold text-lg md:text-xl uppercase drop-shadow-md">PRE MPV</h3>
            </div>
            <div class="cat-card bg-black rounded-2xl h-32 md:h-40 relative overflow-hidden flex items-end justify-end p-4 cursor-pointer">
                <img src="https://cdn.jdpower.com/JDPA_2020%20Lincoln%20Navigator%20Reserve%20Black%20Front%20View.jpg" alt="PRE SUV" class="absolute inset-0 w-full h-full object-cover opacity-60">
                <h3 class="relative z-10 text-white font-extrabold text-lg md:text-xl uppercase drop-shadow-md">PRE SUV</h3>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 pb-20 md:pb-32">
        <h2 class="text-3xl md:text-4xl font-bold uppercase mb-8 md:mb-10 tracking-wide text-center md:text-left" style="font-family: 'Bebas Neue', sans-serif; letter-spacing: 2px;">
            Latest Addition To Our Fleet
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            @php
                $latestCars = \App\Models\Car::where('status', 'Tersedia')->latest()->take(4)->get();
            @endphp

            @forelse($latestCars as $car)
                <div class="bg-[#5974B6] rounded-2xl p-5 flex flex-col justify-between shadow-lg">
                    <img src="{{ asset($car->image_path) }}" alt="{{ $car->name }}" class="w-full h-32 md:h-40 object-contain drop-shadow-xl mb-4">
                    <div>
                        <h3 class="text-lg font-bold uppercase text-white mb-1 truncate">{{ $car->name }}</h3>
                        <div class="text-xl md:text-2xl font-extrabold text-gray-900 mb-4">Rp{{ number_format($car->price_per_day, 0, ',', '.') }}<span class="text-xs font-normal text-gray-800">/day</span></div>
                        <div class="space-y-2 text-[11px] md:text-xs font-medium text-gray-200">
                            <p><i class="fa-solid fa-users w-5"></i> {{ $car->seats }} Passengers</p>
                            <p><i class="fa-solid fa-gear w-5"></i> {{ ucfirst($car->transmission) }}</p>
                            <p><i class="fa-solid fa-gas-pump w-5"></i> {{ ucfirst($car->fuel_type) }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-10">
                    <p class="text-gray-400 text-lg">Belum ada mobil terbaru yang ditambahkan.</p>
                </div>
            @endforelse
        </div>
    </div>

    <footer class="bg-gradient-to-t from-[#5978B4] to-[#121826] text-white py-12 mt-10">
        <div class="max-w-7xl mx-auto px-4 lg:px-8 flex flex-col md:flex-row justify-between items-start gap-8 md:gap-0">
            <div class="w-full md:w-auto">
                <h2 class="text-5xl font-bold italic text-[#8CA1C4] mb-6 drop-shadow-sm">Tripzy</h2>
                <div class="flex space-x-4 text-2xl text-white">
                    <a href="#" class="hover:text-[#8CA1C4] transition"><i class="fa-brands fa-discord"></i></a>
                    <a href="#" class="hover:text-[#8CA1C4] transition"><i class="fa-brands fa-whatsapp"></i></a>
                    <a href="#" class="hover:text-[#8CA1C4] transition"><i class="fa-brands fa-telegram"></i></a>
                    <a href="#" class="hover:text-[#8CA1C4] transition"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>
            <div class="w-full md:w-1/2 md:pl-10">
                <h3 class="text-xl font-bold text-[#8CA1C4] mb-4">Contact Us</h3>
                <ul class="space-y-4 text-xs md:text-sm font-medium text-gray-300">
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-location-dot mt-1 text-[#8CA1C4] shrink-0"></i>
                        <span>Jl. Prof. Dr. Ir. Sumantri Brojonegoro No.1, Gedong Meneng, Kec. Rajabasa,<br>Kota Bandar Lampung, Lampung 35141</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-phone text-[#8CA1C4] shrink-0"></i>
                        <span>+6285278139801</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-envelope text-[#8CA1C4] shrink-0"></i>
                        <span>tripzy@gmail.com</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-regular fa-clock text-[#8CA1C4] shrink-0"></i>
                        <span>Senin - Minggu<br>24 Jam</span>
                    </li>
                </ul>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const body = document.getElementById('main-body');
            const heroWrapper = document.getElementById('hero-wrapper');
            const animDuration = 6500; 

            heroWrapper.classList.add('play-anim');
            setTimeout(() => { body.classList.remove('overflow-y-hidden'); }, animDuration);
        });

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
    </script>
    <script>
        window.addEventListener('pageshow', function (event) {
            // Jika halaman dimuat dari cache memori browser (tombol back)
            if (event.persisted || (typeof window.performance != "undefined" && window.performance.navigation.type === 2)) {
                // Paksa refresh halaman agar nembak ke server (Auth/Middleware) lagi
                window.location.reload();
            }
        });
    </script>
</body>
</html>