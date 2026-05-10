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
            font-size: 15rem;
        }

        .anim-car {
            position: absolute;
            z-index: 10;
            width: 80%;
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

        /* Custom Navbar Pill */
        .nav-pill {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .cat-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .cat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.5); }
    </style>
</head>
<body id="main-body" class="overflow-y-hidden">

    <!-- NAVBAR (Fixed) -->
    <nav class="fixed top-6 left-0 right-0 z-50 px-8 flex justify-between items-center relative z-50">
        <div class="nav-pill rounded-full px-6 py-3 flex space-x-6 text-sm font-bold tracking-wider uppercase text-white">
            <a href="{{ route('dashboard') }}" class="text-blue-300 transition border-b-2 border-blue-300 pb-1">Home</a>
            <a href="{{ route('user.catalog') }}" class="hover:text-blue-300 transition">Catalog</a>
            <a href="{{ route('user.destination') }}" class="hover:text-blue-300 transition">Destination</a>
            <a href="{{ route('user.orders') }}" class="hover:text-blue-300 transition">Orders</a>
        </div>

        <div class="flex space-x-4 items-center">
            <!-- Icon User Dinamis -->
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <a href="{{ route('user.profile') }}" class="w-10 h-10 rounded-full nav-pill flex items-center justify-center hover:bg-white/20 transition text-white overflow-hidden relative border border-white/20">
                    @if(Auth::check() && Auth::user()->profile_photo)
                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="w-full h-full object-cover absolute inset-0">
                    @else
                        <i class="fa-solid fa-user text-lg"></i>
                    @endif
                </a>
            </form>

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

    <!-- HERO ANIMATION SECTION -->
    <div id="hero-wrapper" class="hero-section">
        <h1 class="anim-text">TRIPZY</h1>
        <!-- Gambar Mobil Tengah -->
        <img src="https://www.pngplay.com/wp-content/uploads/13/Tesla-PNG-Free-File-Download.png" alt="Hero Car" class="anim-car object-contain drop-shadow-[0_20px_30px_rgba(0,0,0,0.8)]">
        
        <div class="anim-desc flex flex-col md:flex-row gap-4 text-center md:text-left">
            <div class="max-w-md">
                <p class="text-xs md:text-sm text-gray-300 leading-relaxed font-light">
                    Discover a new level of comfort and performance with our premium vehicles, carefully selected to deliver a smooth, reliable, and every trip becomes a memorable experience.
                </p>
            </div>
            
            <!-- Tombol Detail yang mengarah ke Catalog -->
            <a href="{{ route('user.catalog') }}" class="nav-pill px-10 py-2.5 rounded-full font-bold hover:bg-white hover:text-black transition border-white/40 inline-flex items-center justify-center whitespace-nowrap">
                Detail
            </a>

            <div class="max-w-xs md:text-right">
                <p class="text-xs md:text-sm text-gray-300 leading-relaxed font-light">
                    Elevate your travel experience with vehicles designed for comfort, reliability, and style because every journey deserves the best.
                </p>
            </div>
        </div>
    </div>

    <!-- CAR BY CATEGORY SECTION -->
    <div class="max-w-5xl mx-auto px-4 py-24">
        <h2 class="text-4xl font-bold text-center mb-12 tracking-wide uppercase" style="font-family: 'Poppins', sans-serif;">Car by Category</h2>
        
        <div class="grid grid-cols-2 gap-4">
            <!-- MPV -->
            <div class="cat-card bg-black rounded-2xl h-40 relative overflow-hidden flex items-end justify-start p-4 cursor-pointer">
                <img src="https://lh7-rt.googleusercontent.com/docsz/AD_4nXffY-FoeXKYte6W1UY3iuxD9_KEdvf0omethmnckaqamaXPs4nfbmCe4tLxIDUcQMJiwsh5wtMTkl-s9gniBkZpXZmzR4f_esdPlYvkPwq2RbGqKb8Cu3inyR1fIOxyrn7JCd2fFsf4_th0suuFDvrqt4g?key=M190abu9NCcURv-H7bmJ7w" alt="MPV" class="absolute inset-0 w-full h-full object-cover opacity-60">
                <h3 class="relative z-10 text-white font-extrabold text-xl uppercase drop-shadow-md">MPV</h3>
            </div>
            <!-- SUV -->
            <div class="cat-card bg-black rounded-2xl h-40 relative overflow-hidden flex items-end justify-end p-4 cursor-pointer">
                <img src="https://www3.wuling.id/wp-content/uploads/2022/09/Beragam-Jenis-Mobil-SUV-Cover.jpg" alt="SUV" class="absolute inset-0 w-full h-full object-cover opacity-60">
                <h3 class="relative z-10 text-white font-extrabold text-xl uppercase drop-shadow-md">SUV</h3>
            </div>
            
            <!-- Luxury SUV MPV (Besar 2 Kolom) -->
            <div class="cat-card col-span-2 rounded-2xl h-48 relative overflow-hidden flex items-end justify-center pb-4 cursor-pointer">
                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://scene7.toyota.eu/is/image/toyotaeurope/2025-lexus-lm-gallery-exterior-02-1920x1080-00:Large-Landscape?ts=0&resMode=sharp2&op_usm=1.75,0.3,2,0');"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                <h3 class="relative z-10 text-white font-bold text-xl uppercase tracking-widest drop-shadow-md">Luxury SUV MPV</h3>
            </div>

            <!-- PRE MPV -->
            <div class="cat-card bg-black rounded-2xl h-40 relative overflow-hidden flex items-end justify-start p-4 cursor-pointer">
                <img src="https://carro.id/blog/wp-content/uploads/2021/07/5d8e4161-2020-nissan-serena-facelift-japan-spec-0-e1619530135865.jpg" alt="PRE MPV" class="absolute inset-0 w-full h-full object-cover opacity-60">
                <h3 class="relative z-10 text-white font-extrabold text-xl uppercase drop-shadow-md">PRE MPV</h3>
            </div>
            <!-- PRE SUV -->
            <div class="cat-card bg-black rounded-2xl h-40 relative overflow-hidden flex items-end justify-end p-4 cursor-pointer">
                <img src="https://cdn.jdpower.com/JDPA_2020%20Lincoln%20Navigator%20Reserve%20Black%20Front%20View.jpg" alt="PRE SUV" class="absolute inset-0 w-full h-full object-cover opacity-60">
                <h3 class="relative z-10 text-white font-extrabold text-xl uppercase drop-shadow-md">PRE SUV</h3>
            </div>
        </div>
    </div>

    <!-- LATEST ADDITION SECTION (DINAMIS) -->
    <div class="max-w-7xl mx-auto px-4 pb-32">
        <h2 class="text-4xl font-bold uppercase mb-10 tracking-wide text-center md:text-left" style="font-family: 'Bebas Neue', sans-serif; letter-spacing: 2px;">
            Latest Addition To Our Fleet
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                // Tarik 4 mobil terbaru yang statusnya Tersedia dari Database
                $latestCars = \App\Models\Car::where('status', 'Tersedia')->latest()->take(4)->get();
            @endphp

            @forelse($latestCars as $car)
                <div class="bg-[#5974B6] rounded-2xl p-5 flex flex-col justify-between shadow-lg">
                    <img src="{{ asset($car->image_path) }}" alt="{{ $car->name }}" class="w-full h-40 object-contain drop-shadow-xl mb-4">
                    <div>
                        <h3 class="text-lg font-bold uppercase text-white mb-1 truncate">{{ $car->name }}</h3>
                        <div class="text-2xl font-extrabold text-gray-900 mb-4">Rp{{ number_format($car->price_per_day, 0, ',', '.') }}<span class="text-xs font-normal text-gray-800">/day</span></div>
                        <div class="space-y-2 text-xs font-medium text-gray-200">
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

    <!-- FOOTER SECTION -->
    <footer class="bg-gradient-to-t from-[#5978B4] to-[#121826] text-white py-12 mt-10">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between items-start">
            <div class="mb-8 md:mb-0">
                <h2 class="text-5xl font-bold italic text-[#8CA1C4] mb-6 drop-shadow-sm">Tripzy</h2>
                <div class="flex space-x-4 text-2xl text-white">
                    <a href="#" class="hover:text-[#8CA1C4] transition"><i class="fa-brands fa-discord"></i></a>
                    <a href="#" class="hover:text-[#8CA1C4] transition"><i class="fa-brands fa-whatsapp"></i></a>
                    <a href="#" class="hover:text-[#8CA1C4] transition"><i class="fa-brands fa-telegram"></i></a>
                    <a href="#" class="hover:text-[#8CA1C4] transition"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>
            <div class="md:w-1/2">
                <h3 class="text-xl font-bold text-[#8CA1C4] mb-4">Contact Us</h3>
                <ul class="space-y-4 text-sm font-medium text-gray-300">
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-location-dot mt-1 text-[#8CA1C4]"></i>
                        <span>Jl. Prof. Dr. Ir. Sumantri Brojonegoro No.1, Gedong Meneng, Kec. Rajabasa,<br>Kota Bandar Lampung, Lampung 35141</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-phone text-[#8CA1C4]"></i>
                        <span>+6285278139801</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-envelope text-[#8CA1C4]"></i>
                        <span>tripzy@gmail.com</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-regular fa-clock text-[#8CA1C4]"></i>
                        <span>Senin - Minggu<br>24 Jam</span>
                    </li>
                </ul>
            </div>
        </div>
    </footer>

    <!-- JAVASCRIPT LOGIC -->
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
</body>
</html>