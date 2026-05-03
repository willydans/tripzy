<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tripzy - Drive Into the Beauty of Lampung</title>
    
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts: Poppins untuk kesan modern -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome untuk Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #F0F4FC; }
        
        /* Custom Gradient untuk Hero Section */
        .hero-gradient {
            background: linear-gradient(180deg, #3A62A6 0%, #7A9FE0 50%, #F0F4FC 100%);
            position: relative;
        }
        
        /* Grid pattern tipis di belakang */
        .hero-gradient::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: linear-gradient(to right, rgba(255,255,255,0.05) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size: 40px 40px;
            z-index: 0;
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            /* Tambahan biar sticky 👇 */
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 100;
            width: 80%; /* Lebarnya menyesuaikan */
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="text-gray-800 overflow-x-hidden">

    <!-- HERO SECTION & NAVBAR -->
    <div class="hero-gradient min-h-[90vh] flex flex-col relative overflow-hidden">
        
        <!-- Navbar -->
        <nav class="glass-nav rounded-full px-8 py-4 flex justify-between items-center text-white transition-all">
            <h1 class="text-2xl font-bold italic tracking-wider"><a href="{{ route('home') }}">Tripzy</a></h1>
            
            <div class="hidden md:flex space-x-8 font-medium">
                <a href="{{ route('home') }}" class="text-blue-200 hover:text-white transition drop-shadow-md">Home</a>
                <a href="{{ route('catalog') }}" class="hover:text-blue-200 transition">Catalog</a>
                <a href="{{ route('contact') }}" class="hover:text-blue-200 transition">Contact</a>
                <a href="{{ route('destination') }}" class="hover:text-blue-200 transition">Destination</a>
            </div>

            <!-- Tombol Sign In (Logout & Auth logic dihapus sesuai request) -->
            <a href="{{ route('login') }}" class="bg-gray-900 bg-opacity-40 hover:bg-opacity-60 border border-gray-500 px-6 py-2 rounded-full font-medium flex items-center gap-2 transition text-white">
                Sign In <i class="fa-solid fa-arrow-up-right-from-square text-sm"></i>
            </a>
        </nav>

        <!-- Hero Content -->
        <div class="relative z-10 flex-grow flex flex-col justify-center items-center text-center px-4 mt-20">
            <h1 class="text-4xl md:text-6xl font-bold text-white leading-tight mb-6 tracking-wide drop-shadow-md">
                Drive Into the Beauty<br>of Lampung
            </h1>
            <p class="text-white text-sm md:text-base max-w-2xl mb-10 opacity-90 drop-shadow">
                Start your journey with comfort and freedom as you explore Lampung at your own pace. With reliable vehicles and an easy booking process, every destination from beaches to hidden gems becomes closer, more practical, and more enjoyable.
            </p>
            <div class="flex space-x-4">
                <button class="glass-nav text-white px-8 py-3 rounded-full hover:bg-white hover:text-blue-600 transition flex items-center gap-2" style="position: relative; top: 0; left: 0; transform: none; width: auto;">
                    Book Now <i class="fa-solid fa-arrow-right"></i>
                </button>
                <button class="glass-nav text-white px-8 py-3 rounded-full hover:bg-white hover:text-blue-600 transition flex items-center gap-2" style="position: relative; top: 0; left: 0; transform: none; width: auto;">
                    Looking Catalog <i class="fa-solid fa-arrow-up-right-from-square"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- WHY CHOOSE TRIPZY SECTION -->
    <div class="max-w-6xl mx-auto px-4 py-20">
        <h2 class="text-3xl font-bold text-center text-[#4A6EB0] mb-12">Why Choose Tripzy?</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Card 1 -->
            <div class="bg-[#C5D6F5] p-6 rounded-2xl relative overflow-hidden shadow-sm">
                <span class="absolute top-2 right-4 text-5xl font-bold text-white opacity-40">01</span>
                <div class="w-12 h-12 bg-gray-600 text-white rounded-full flex items-center justify-center text-xl mb-4 relative z-10">
                    <i class="fa-regular fa-clock"></i>
                </div>
                <h3 class="font-bold text-[#3B5B92] mb-2 relative z-10">24/7 SERVICE</h3>
                <p class="text-sm text-gray-600 relative z-10">Customer service is ready to assist you anytime.</p>
            </div>

            <!-- Card 2 -->
            <div class="bg-[#C5D6F5] p-6 rounded-2xl relative overflow-hidden shadow-sm">
                <span class="absolute top-2 right-4 text-5xl font-bold text-white opacity-40">02</span>
                <div class="w-12 h-12 bg-gray-600 text-white rounded-full flex items-center justify-center text-xl mb-4 relative z-10">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h3 class="font-bold text-[#3B5B92] mb-2 relative z-10">COMPREHENSIVE INSURANCE</h3>
                <p class="text-sm text-gray-600 relative z-10">Travel with peace of mind with complete protection</p>
            </div>

            <!-- Card 3 -->
            <div class="bg-[#C5D6F5] p-6 rounded-2xl relative overflow-hidden shadow-sm">
                <span class="absolute top-2 right-4 text-5xl font-bold text-white opacity-40">03</span>
                <div class="w-12 h-12 bg-gray-600 text-white rounded-full flex items-center justify-center text-xl mb-4 relative z-10">
                    <i class="fa-regular fa-calendar-check"></i>
                </div>
                <h3 class="font-bold text-[#3B5B92] mb-2 relative z-10">EASY BOOKING</h3>
                <p class="text-sm text-gray-600 relative z-10">Fast reservation without complicated steps</p>
            </div>

            <!-- Card 4 -->
            <div class="bg-[#C5D6F5] p-6 rounded-2xl relative overflow-hidden shadow-sm">
                <span class="absolute top-2 right-4 text-5xl font-bold text-white opacity-40">04</span>
                <div class="w-12 h-12 bg-gray-600 text-white rounded-full flex items-center justify-center text-xl mb-4 relative z-10">
                    <i class="fa-solid fa-star"></i>
                </div>
                <h3 class="font-bold text-[#3B5B92] mb-2 relative z-10">GUARANTEED QUALITY</h3>
                <p class="text-sm text-gray-600 relative z-10">Experience top-quality travel with confidence</p>
            </div>
        </div>
    </div>

    <!-- CATALOG SECTION -->
    <div class="max-w-6xl mx-auto px-4 pb-24">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-[#4A6EB0] mb-4">Best Car Selection</h2>
            <p class="text-lg text-[#5A7CB8]">Enjoy a wide selection of top-quality vehicles, giving you the flexibility to choose the perfect car for every journey.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Car Card: Avanza -->
            <div class="bg-gradient-to-b from-[#B8CDEE] to-[#DEE8F6] rounded-3xl p-8 shadow-lg flex flex-col relative">
                <img src="{{ asset('images/avanza.png') }}" alt="Toyota Avanza" class="w-full object-contain h-48 mb-6 drop-shadow-xl z-10">
                <h3 class="text-xl font-bold text-gray-800 uppercase">Toyota Avanza</h3>
                <div class="text-2xl font-bold text-gray-900 my-2">Rp 300.000 <span class="text-sm font-normal text-gray-600">/day</span></div>
                
                <div class="flex justify-between items-end mt-4">
                    <div class="space-y-2 text-xs font-medium text-gray-700">
                        <p><i class="fa-solid fa-users w-5"></i> 7 Passengers</p>
                        <p><i class="fa-solid fa-gear w-5"></i> Manual</p>
                        <p><i class="fa-solid fa-gas-pump w-5"></i> Fuel</p>
                    </div>
                    <!-- Mini Thumbnail -->
                    <img src="{{ asset('images/avanza.png') }}" alt="Mini" class="w-24 drop-shadow-md">
                </div>
                
                <button class="mt-8 w-full py-3 rounded-full bg-gradient-to-r from-white/40 to-white/20 border border-white/50 text-[#3B5B92] font-bold shadow-sm hover:bg-white/50 transition">
                    Book Now
                </button>
            </div>

            <!-- Car Card: Brio -->
            <div class="bg-gradient-to-b from-[#B8CDEE] to-[#DEE8F6] rounded-3xl p-8 shadow-lg flex flex-col relative">
                <img src="{{ asset('images/brio.png') }}" alt="Honda Brio" class="w-full object-contain h-48 mb-6 drop-shadow-xl z-10">
                <h3 class="text-xl font-bold text-gray-800 uppercase">Honda Brio</h3>
                <div class="text-2xl font-bold text-gray-900 my-2">Rp 250.000 <span class="text-sm font-normal text-gray-600">/day</span></div>
                
                <div class="flex justify-between items-end mt-4">
                    <div class="space-y-2 text-xs font-medium text-gray-700">
                        <p><i class="fa-solid fa-users w-5"></i> 5 Passengers</p>
                        <p><i class="fa-solid fa-gear w-5"></i> Automatic</p>
                        <p><i class="fa-solid fa-gas-pump w-5"></i> Fuel</p>
                    </div>
                    <img src="{{ asset('images/brio.png') }}" alt="Mini" class="w-24 drop-shadow-md">
                </div>
                
                <button class="mt-8 w-full py-3 rounded-full bg-gradient-to-r from-white/40 to-white/20 border border-white/50 text-[#3B5B92] font-bold shadow-sm hover:bg-white/50 transition">
                    Book Now
                </button>
            </div>

            <!-- Car Card: Fortuner -->
            <div class="bg-gradient-to-b from-[#B8CDEE] to-[#DEE8F6] rounded-3xl p-8 shadow-lg flex flex-col relative">
                <img src="{{ asset('images/fortuner.png') }}" alt="Toyota Fortuner" class="w-full object-contain h-48 mb-6 drop-shadow-xl z-10">
                <h3 class="text-xl font-bold text-gray-800 uppercase">Toyota Fortuner</h3>
                <div class="text-2xl font-bold text-gray-900 my-2">Rp 800.000 <span class="text-sm font-normal text-gray-600">/day</span></div>
                
                <div class="flex justify-between items-end mt-4">
                    <div class="space-y-2 text-xs font-medium text-gray-700">
                        <p><i class="fa-solid fa-users w-5"></i> 7 Passengers</p>
                        <p><i class="fa-solid fa-gear w-5"></i> Automatic</p>
                        <p><i class="fa-solid fa-gas-pump w-5"></i> Diesel</p>
                    </div>
                    <img src="{{ asset('images/fortuner.png') }}" alt="Mini" class="w-24 drop-shadow-md">
                </div>
                
                <button class="mt-8 w-full py-3 rounded-full bg-gradient-to-r from-white/40 to-white/20 border border-white/50 text-[#3B5B92] font-bold shadow-sm hover:bg-white/50 transition">
                    Book Now
                </button>
            </div>

            <!-- Car Card: Innova -->
            <div class="bg-gradient-to-b from-[#B8CDEE] to-[#DEE8F6] rounded-3xl p-8 shadow-lg flex flex-col relative">
                <img src="{{ asset('images/innova.png') }}" alt="Toyota Innova" class="w-full object-contain h-48 mb-6 drop-shadow-xl z-10">
                <h3 class="text-xl font-bold text-gray-800 uppercase">Toyota Innova</h3>
                <div class="text-2xl font-bold text-gray-900 my-2">Rp 500.000 <span class="text-sm font-normal text-gray-600">/day</span></div>
                
                <div class="flex justify-between items-end mt-4">
                    <div class="space-y-2 text-xs font-medium text-gray-700">
                        <p><i class="fa-solid fa-users w-5"></i> 5 Passengers</p>
                        <p><i class="fa-solid fa-gear w-5"></i> Manual</p>
                        <p><i class="fa-solid fa-gas-pump w-5"></i> Bensin</p>
                    </div>
                    <img src="{{ asset('images/innova.png') }}" alt="Mini" class="w-24 drop-shadow-md">
                </div>
                
                <button class="mt-8 w-full py-3 rounded-full bg-gradient-to-r from-white/40 to-white/20 border border-white/50 text-[#3B5B92] font-bold shadow-sm hover:bg-white/50 transition">
                    Book Now
                </button>
            </div>
        </div>
    </div>

    <!-- FOOTER SECTION -->
    <footer class="bg-gradient-to-t from-[#5978B4] to-[#A9BFE4] text-white py-12">
        <div class="max-w-6xl mx-auto px-4 flex flex-col md:flex-row justify-between items-start">
            
            <!-- Left: Brand & Socials -->
            <div class="mb-8 md:mb-0">
                <h2 class="text-5xl font-bold italic text-[#3B5B92] mb-6 drop-shadow-sm">Tripzy</h2>
                <div class="flex space-x-4 text-2xl text-white">
                    <a href="#" class="hover:text-[#3B5B92] transition"><i class="fa-brands fa-discord"></i></a>
                    <a href="#" class="hover:text-[#3B5B92] transition"><i class="fa-brands fa-whatsapp"></i></a>
                    <a href="#" class="hover:text-[#3B5B92] transition"><i class="fa-brands fa-telegram"></i></a>
                    <a href="#" class="hover:text-[#3B5B92] transition"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>

            <!-- Right: Contact Info -->
            <div class="md:w-1/2">
                <h3 class="text-xl font-bold text-[#3B5B92] mb-4">Contact Us</h3>
                <ul class="space-y-4 text-sm font-medium">
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-location-dot mt-1"></i>
                        <span>Jl. Prof. Dr. Ir. Sumantri Brojonegoro No.1, Gedong Meneng, Kec. Rajabasa,<br>Kota Bandar Lampung, Lampung 35141</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-phone"></i>
                        <span>+6285278139801</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-envelope"></i>
                        <span>tripzy@gmail.com</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-regular fa-clock"></i>
                        <span>Senin - Minggu<br>24 Jam</span>
                    </li>
                </ul>
            </div>
            
        </div>
    </footer>

</body>
</html>