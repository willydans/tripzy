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
            background-color: #171B2D; /* Tema Dark Mode */
            color: white;
        }

        /* --- BACKGROUND SESUAI GAMBAR BARU --- */
        .hero-bg {
            position: relative;
            background-color: #171B2D; /* Base dark blue */
            /* Glow halus di tengah-tengah teks */
            background-image: radial-gradient(circle at 50% 30%, #263655 0%, transparent 60%);
        }
        
        /* 1. Efek Garis Vertikal (Vertical Bands) */
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

        /* 2. Efek Grid/Kotak di Kiri Bawah */
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

        /* Custom Navbar Pill */
        .nav-pill {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Card Hover Effect */
        .car-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .car-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.4);
        }
    </style>
</head>
<body class="overflow-x-hidden">

    <!-- NAVBAR (Fixed) -->
    <nav class="fixed top-6 left-0 right-0 z-50 px-8 flex justify-between items-center">
        <!-- Left: Navigation Links -->
        <div class="nav-pill rounded-full px-6 py-3 flex space-x-6 text-sm font-bold tracking-wider uppercase text-white">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-300 transition">Home</a>
            <a href="{{ route('user.catalog') }}" class="text-blue-300 hover:text-white transition drop-shadow-md">Catalog</a>
            <a href="#" class="hover:text-blue-300 transition">Destination</a>
            <a href="{{ route('user.orders') }}" class="hover:text-blue-300 transition">Orders</a>
        </div>

        <!-- Right: Profile & Notifications -->
        <div class="flex space-x-4 items-center">
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <a href="{{ route('user.profile') }}" class="w-10 h-10 rounded-full nav-pill flex items-center justify-center hover:bg-white/20 transition text-white">
    <i class="fa-solid fa-user text-lg"></i>
</a>
            </form>
            <button class="w-10 h-10 rounded-full nav-pill flex items-center justify-center hover:bg-white/20 transition text-white">
                <i class="fa-solid fa-bell text-lg"></i>
            </button>
        </div>
    </nav>

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
            
            <div class="relative w-full lg:w-96">
                <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 transform -translate-y-1/2 text-[#8CA1C4]"></i>
                <input type="text" placeholder="Find Your Perfect Car..." class="w-full bg-white/5 border border-white/10 text-sm rounded-full py-3 pl-12 pr-4 focus:outline-none focus:border-white/30 text-white placeholder-[#8CA1C4] backdrop-blur-md transition">
            </div>
            
            <div class="flex flex-wrap justify-center items-center gap-1 text-xs md:text-sm font-medium text-[#8CA1C4] bg-white/5 border border-white/10 rounded-full px-2 py-1.5 backdrop-blur-md">
                <button class="bg-white/10 text-white px-5 py-1.5 rounded-full shadow-sm font-semibold transition">All</button>
                <button class="px-4 py-1.5 hover:text-white transition rounded-full">SUV</button>
                <button class="px-4 py-1.5 hover:text-white transition rounded-full">MPV</button>
                <button class="px-4 py-1.5 hover:text-white transition rounded-full">Premium SUV</button>
                <button class="px-4 py-1.5 hover:text-white transition rounded-full">Premium MPV</button>
                <button class="px-4 py-1.5 hover:text-white transition rounded-full">Luxury MPV & SUV</button>
            </div>

        </div>
    </div>

    <!-- CATALOG GRID (DYNAMIC DARI DATABASE) -->
    <div class="max-w-7xl mx-auto px-4 pb-24 mt-10 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Looping data mobil dari database -->
            @forelse($cars as $car)
                <div class="car-card bg-gradient-to-b from-[#6D819C] to-[#4C5B79] rounded-3xl p-6 shadow-xl border border-white/5 flex flex-col justify-between">
                    <div>
                        <!-- Gambar Mobil Utama -->
                        <img src="{{ asset($car->image_path) }}" alt="{{ $car->name }}" class="w-full h-40 object-contain drop-shadow-2xl mb-4">
                        <div class="flex justify-between items-end mb-4">
                            <div>
                                <!-- Nama Mobil -->
                                <h3 class="text-xs font-bold text-gray-200 uppercase mb-1 tracking-wider">{{ $car->name }}</h3>
                                <!-- Harga -->
                                <div class="text-2xl font-extrabold text-white">
                                    Rp {{ number_format($car->price_per_day, 0, ',', '.') }} 
                                    <span class="text-[10px] font-normal text-gray-300">/day</span>
                                </div>
                                <!-- Spesifikasi Ringkas -->
                                <p class="text-[9px] text-gray-300 mt-1 uppercase tracking-wide">
                                    Seats for {{ $car->seats }} passengers &bull; {{ $car->transmission }} &bull; {{ $car->fuel_type }}
                                </p>
                            </div>
                            <!-- Miniatur Gambar (bisa pakai gambar yang sama) -->
                            <img src="{{ asset($car->image_path) }}" alt="{{ $car->name }}" class="w-16 drop-shadow-lg opacity-80">
                        </div>
                    </div>
                    <!-- Tombol Book Now yang nyambung ke route car.detail bawa id/slug mobil -->
                    <a href="{{ route('user.car.detail', $car->slug) }}" class="block w-full py-2.5 rounded-full bg-gradient-to-r from-white/30 to-white/10 border border-white/30 text-white text-center font-bold shadow-md hover:bg-white/40 transition backdrop-blur-md">
                        Book Now
                    </a>
                </div>
            @empty
                <!-- Tampilan kalau database mobil masih kosong -->
                <div class="col-span-full text-center py-10">
                    <p class="text-gray-400 text-lg">Belum ada mobil yang tersedia di katalog saat ini.</p>
                </div>
            @endforelse

        </div>
    </div>

    <!-- FOOTER SECTION -->
    <footer class="bg-gradient-to-t from-[#B8CDEE] to-[#E2EAF6] py-12 border-t border-[#A9BFE4]">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between items-start">
            <div class="mb-8 md:mb-0">
                <h2 class="text-5xl font-bold italic text-[#4A6EB0] mb-6 drop-shadow-sm">Tripzy</h2>
                <div class="flex space-x-4 text-2xl text-[#6C82A3]">
                    <a href="#" class="hover:text-[#4A6EB0] transition"><i class="fa-brands fa-discord"></i></a>
                    <a href="#" class="hover:text-[#4A6EB0] transition"><i class="fa-brands fa-whatsapp"></i></a>
                    <a href="#" class="hover:text-[#4A6EB0] transition"><i class="fa-brands fa-telegram"></i></a>
                    <a href="#" class="hover:text-[#4A6EB0] transition"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>
            <div class="md:w-1/2">
                <h3 class="text-xl font-bold text-[#4A6EB0] mb-4">Contact Us</h3>
                <ul class="space-y-4 text-sm font-medium text-[#5A7CB8]">
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-location-dot mt-1 text-[#4A6EB0]"></i>
                        <span>Jl. Prof. Dr. Ir. Sumantri Brojonegoro No.1, Gedong Meneng, Kec. Rajabasa,<br>Kota Bandar Lampung, Lampung 35141</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-phone text-[#4A6EB0]"></i>
                        <span>+6285278139801</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-envelope text-[#4A6EB0]"></i>
                        <span>tripzy@gmail.com</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-regular fa-clock text-[#4A6EB0]"></i>
                        <span>Senin - Minggu<br>24 Jam</span>
                    </li>
                </ul>
            </div>
        </div>
    </footer>

</body>
</html>