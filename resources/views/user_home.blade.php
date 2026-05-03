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
            background-color: #121826; /* Dark theme background */
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

        /* Teks Tripzy Raksasa */
        .anim-text {
            font-family: 'Bebas Neue', sans-serif;
            font-style: italic;
            position: absolute;
            z-index: 1;
            transform-origin: center center;
            letter-spacing: 5px;
            /* State default jika animasi sudah pernah dimainkan */
            transform: scale(2.5) translateY(-15%);
            opacity: 0.15;
            font-size: 15rem;
        }

        /* Mobil */
        .anim-car {
            position: absolute;
            z-index: 10;
            width: 80%;
            max-width: 1000px;
            /* State default jika animasi sudah pernah dimainkan */
            transform: translateY(15%) scale(0.9);
        }

        /* Deskripsi Bawah */
        .anim-desc {
            position: absolute;
            bottom: 40px;
            z-index: 20;
            width: 100%;
            padding: 0 5%;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            /* State default jika animasi sudah pernah dimainkan */
            opacity: 1;
            transform: translateY(0);
        }

        /* KEYFRAMES (Hanya ditambahkan via class 'play-anim' oleh JS) */
        /* KEYFRAMES (Hanya ditambahkan via class 'play-anim' oleh JS) */
        .play-anim .anim-text {
            /* Durasi dinaikin jadi 6.5 detik biar mundurnya smooth dan pelan */
            animation: zoomText 6.5s cubic-bezier(0.25, 0.8, 0.25, 1) forwards;
        }
        .play-anim .anim-car {
            /* Delay 1.5 detik (nunggu teks gerak dulu), lalu durasi 5 detik */
            animation: slideUpCar 5s 1.5s cubic-bezier(0.25, 0.8, 0.25, 1) forwards;
            opacity: 0; /* Sembunyiin dulu sebelum waktunya muncul */
        }
        .play-anim .anim-desc {
            /* Delay 4.5 detik (nunggu mobil udah di atas), lalu muncul selama 2 detik */
            animation: fadeInUpDesc 2s 4.5s ease-out forwards;
            opacity: 0; /* Mulai dari hilang */
        }

        @keyframes zoomText {
            0%   { transform: scale(1) translateY(0); opacity: 1; }
            20%  { transform: scale(1.05) translateY(-2%); opacity: 1; } /* Nahan dikit di awal */
            100% { transform: scale(2.5) translateY(-15%); opacity: 0.15; }
        }

        @keyframes slideUpCar {
            0%   { transform: translateY(100%) scale(1); opacity: 0; }
            30%  { transform: translateY(5%) scale(1.05); opacity: 1; } /* Muncul agak cepat dari bawah */
            100% { transform: translateY(15%) scale(0.9); opacity: 1; } /* Mundur perlahan ke belakang */
        }

        @keyframes fadeInUpDesc {
            0%   { opacity: 0; transform: translateY(40px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        /* --- END ANIMASI --- */
        /* --- END ANIMASI --- */


        /* Custom Navbar Pill */
        .nav-pill {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        /* Category Card Hover */
        .cat-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .cat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
        }
    </style>
</head>
<!-- Body dikunci scroll-nya secara default. JS akan membukanya -->
<body id="main-body" class="overflow-y-hidden">

    <!-- NAVBAR (Fixed) -->
    <nav class="fixed top-6 left-0 right-0 z-50 px-8 flex justify-between items-center">
        <!-- Left: Navigation Links -->
        <div class="nav-pill rounded-full px-6 py-3 flex space-x-6 text-sm font-bold tracking-wider uppercase">
            <!-- Update link Home ke dashboard -->
            <a href="{{ route('dashboard') }}" class="hover:text-blue-300 transition">Home</a>
            
            <!-- Update link Catalog ke user.catalog 👇 -->
            <a href="{{ route('user.catalog') }}" class="hover:text-blue-300 transition">Catalog</a>
            
            <a href="#" class="hover:text-blue-300 transition">Destination</a>
            <a href="#" class="hover:text-blue-300 transition">Orders</a>
        </div>


           <!-- Right: Profile & Notifications -->
        <div class="flex space-x-4 items-center">
            
            <!-- Tombol Logout Sementara (Nempel di Icon User) -->
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="w-10 h-10 rounded-full nav-pill flex items-center justify-center hover:bg-red-500/50 transition cursor-pointer" title="Klik untuk Logout">
                    <i class="fa-solid fa-user text-lg"></i>
                </button>
            </form>

            <button class="w-10 h-10 rounded-full nav-pill flex items-center justify-center hover:bg-white/20 transition">
                <i class="fa-solid fa-bell text-lg"></i>
            </button>
        </div>
    </nav>

    <!-- HERO ANIMATION SECTION -->
    <!-- ID 'hero-wrapper' digunakan JS untuk menyuntikkan class animasi -->
    <div id="hero-wrapper" class="hero-section">
        
        <!-- Teks Latar Belakang -->
        <h1 class="anim-text">TRIPZY</h1>
        
        <!-- Gambar Mobil Tengah (Ganti source-nya pakai gambar lu bre) -->
        <img src="https://www.pngplay.com/wp-content/uploads/13/Tesla-PNG-Free-File-Download.png" alt="Hero Car" class="anim-car object-contain drop-shadow-[0_20px_30px_rgba(0,0,0,0.8)]">
        
        <!-- Deskripsi dan Tombol -->
        <div class="anim-desc">
            <div class="max-w-md">
                <p class="text-xs md:text-sm text-gray-300 leading-relaxed font-light">
                    Discover a new level of comfort and performance with our premium vehicles, carefully selected to deliver a smooth, reliable, and every trip becomes a memorable experience.
                </p>
            </div>
            
            <button class="nav-pill px-10 py-2 rounded-full font-bold hover:bg-white/20 transition border-white/40">
                Detail
            </button>

            <div class="max-w-xs text-right">
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
            <div class="cat-card bg-white rounded-2xl p-4 flex items-end justify-between relative h-40 overflow-hidden cursor-pointer">
                <img src="{{ asset('images/voxy.png') }}" alt="MPV" class="absolute inset-0 w-full h-full object-contain p-2">
                <h3 class="relative z-10 text-black font-extrabold text-xl uppercase drop-shadow-md">MPV</h3>
            </div>
            <!-- SUV -->
            <div class="cat-card bg-white rounded-2xl p-4 flex items-end justify-end relative h-40 overflow-hidden cursor-pointer">
                <img src="{{ asset('images/fortuner.png') }}" alt="SUV" class="absolute inset-0 w-full h-full object-contain p-2">
                <h3 class="relative z-10 text-black font-extrabold text-xl uppercase drop-shadow-md">SUV</h3>
            </div>
            
            <!-- Luxury SUV MPV (Besar 2 Kolom) -->
            <div class="cat-card col-span-2 rounded-2xl h-48 relative overflow-hidden flex items-end justify-center pb-4 cursor-pointer">
                <!-- Background Image (Gelap/Gold) -->
                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1549399542-7e3f8b79c341?q=80&w=1000&auto=format&fit=crop');"></div>
                <!-- Overlay gelap -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                <h3 class="relative z-10 text-white font-bold text-xl uppercase tracking-widest">Luxury SUV MPV</h3>
            </div>

            <!-- PRE MPV -->
            <div class="cat-card bg-white rounded-2xl p-4 flex items-end justify-end relative h-40 overflow-hidden cursor-pointer">
                <img src="{{ asset('images/alphard.png') }}" alt="PRE MPV" class="absolute inset-0 w-full h-full object-contain p-2">
                <h3 class="relative z-10 text-[#1C2C4A] font-extrabold text-xl uppercase drop-shadow-md">PRE MPV</h3>
            </div>
            <!-- PRE SUV -->
            <div class="cat-card bg-white rounded-2xl p-4 flex items-end justify-start relative h-40 overflow-hidden cursor-pointer">
                <img src="{{ asset('images/mercy.png') }}" alt="PRE SUV" class="absolute inset-0 w-full h-full object-contain p-2">
                <h3 class="relative z-10 text-[#1C2C4A] font-extrabold text-xl uppercase drop-shadow-md">PRE SUV</h3>
            </div>
        </div>
    </div>

    <!-- LATEST ADDITION SECTION -->
    <div class="max-w-7xl mx-auto px-4 pb-32">
        <h2 class="text-4xl font-bold uppercase mb-10 tracking-wide" style="font-family: 'Bebas Neue', sans-serif; letter-spacing: 2px;">
            Latest Addition To Our Fleet
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Card 1: Jimny -->
            <div class="bg-[#5974B6] rounded-2xl p-5 flex flex-col justify-between shadow-lg">
                <img src="{{ asset('images/jimny.png') }}" alt="Suzuki Jimny" class="w-full h-40 object-contain drop-shadow-xl mb-4">
                <div>
                    <h3 class="text-lg font-bold uppercase text-white mb-1">Suzuki Jimny</h3>
                    <div class="text-2xl font-extrabold text-gray-900 mb-4">Rp1.200.000<span class="text-xs font-normal text-gray-800">/day</span></div>
                    <div class="space-y-2 text-xs font-medium text-gray-200">
                        <p><i class="fa-solid fa-users w-5"></i> 4 Passengers</p>
                        <p><i class="fa-solid fa-gear w-5"></i> Automatic</p>
                        <p><i class="fa-solid fa-gas-pump w-5"></i> Petrol</p>
                    </div>
                </div>
            </div>

            <!-- Card 2: Land Cruiser -->
            <div class="bg-[#5974B6] rounded-2xl p-5 flex flex-col justify-between shadow-lg">
                <img src="{{ asset('images/landcruiser.png') }}" alt="Land Cruiser" class="w-full h-40 object-contain drop-shadow-xl mb-4">
                <div>
                    <h3 class="text-lg font-bold uppercase text-white mb-1">Toyota Land Cruiser</h3>
                    <div class="text-2xl font-extrabold text-gray-900 mb-4">Rp1.000.000<span class="text-xs font-normal text-gray-800">/day</span></div>
                    <div class="space-y-2 text-xs font-medium text-gray-200">
                        <p><i class="fa-solid fa-users w-5"></i> 5 Passengers</p>
                        <p><i class="fa-solid fa-gear w-5"></i> Manual</p>
                        <p><i class="fa-solid fa-gas-pump w-5"></i> Petrol</p>
                    </div>
                </div>
            </div>

            <!-- Card 3: Jimny -->
            <div class="bg-[#5974B6] rounded-2xl p-5 flex flex-col justify-between shadow-lg">
                <img src="{{ asset('images/jimny.png') }}" alt="Suzuki Jimny" class="w-full h-40 object-contain drop-shadow-xl mb-4">
                <div>
                    <h3 class="text-lg font-bold uppercase text-white mb-1">Suzuki Jimny</h3>
                    <div class="text-2xl font-extrabold text-gray-900 mb-4">Rp1.200.000<span class="text-xs font-normal text-gray-800">/day</span></div>
                    <div class="space-y-2 text-xs font-medium text-gray-200">
                        <p><i class="fa-solid fa-users w-5"></i> 4 Passengers</p>
                        <p><i class="fa-solid fa-gear w-5"></i> Automatic</p>
                        <p><i class="fa-solid fa-gas-pump w-5"></i> Petrol</p>
                    </div>
                </div>
            </div>

            <!-- Card 4: Jimny -->
            <div class="bg-[#5974B6] rounded-2xl p-5 flex flex-col justify-between shadow-lg">
                <img src="{{ asset('images/jimny.png') }}" alt="Suzuki Jimny" class="w-full h-40 object-contain drop-shadow-xl mb-4">
                <div>
                    <h3 class="text-lg font-bold uppercase text-white mb-1">Suzuki Jimny</h3>
                    <div class="text-2xl font-extrabold text-gray-900 mb-4">Rp1.200.000<span class="text-xs font-normal text-gray-800">/day</span></div>
                    <div class="space-y-2 text-xs font-medium text-gray-200">
                        <p><i class="fa-solid fa-users w-5"></i> 4 Passengers</p>
                        <p><i class="fa-solid fa-gear w-5"></i> Automatic</p>
                        <p><i class="fa-solid fa-gas-pump w-5"></i> Petrol</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- FOOTER SECTION (Telah disesuaikan warnanya agar menyatu dengan Dark Theme) -->
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

  <!-- JAVASCRIPT: LOGIKA ANIMASI & SCROLL LOCK -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const body = document.getElementById('main-body');
            const heroWrapper = document.getElementById('hero-wrapper');
            const animDuration = 6500; // 👈 Waktu kunci scroll disamain jadi 6.5 detik

            // Langsung tambahin class animasi setiap kali page dimuat/di-refresh
            heroWrapper.classList.add('play-anim');
            
            // Kunci scroll selama animasi berlangsung, lalu buka saat animasi kelar
            setTimeout(() => {
                body.classList.remove('overflow-y-hidden');
            }, animDuration);
        });
    </script>

</body>
</html>