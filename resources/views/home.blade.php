<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tripzy - Drive Into the Beauty of Lampung</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #F0F4FC; }
        
        .hero-gradient {
            background: linear-gradient(180deg, #3A62A6 0%, #7A9FE0 50%, #F0F4FC 100%);
            position: relative;
        }
        
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
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="text-gray-800 overflow-x-hidden flex flex-col min-h-screen">

    <div class="hero-gradient min-h-[85vh] md:min-h-[90vh] flex flex-col relative overflow-hidden">
        
        <nav class="glass-nav fixed top-4 md:top-5 left-1/2 transform -translate-x-1/2 w-[95%] md:w-[80%] rounded-full px-5 py-3 md:px-8 md:py-4 flex justify-between items-center text-white transition-all z-50">
            <h1 class="text-xl md:text-2xl font-bold italic tracking-wider"><a href="{{ route('home') }}">Tripzy</a></h1>
            
            <div class="hidden md:flex space-x-8 font-medium">
                <a href="{{ route('home') }}" class="text-blue-200 hover:text-white transition drop-shadow-md border-b-2 border-blue-200 pb-1">Home</a>
                <a href="{{ route('catalog') }}" class="hover:text-blue-200 transition">Catalog</a>
                <a href="{{ route('contact') }}" class="hover:text-blue-200 transition">Contact</a>
                <a href="{{ route('destination') }}" class="hover:text-blue-200 transition">Destination</a>
            </div>

            <a href="{{ route('login') }}" class="bg-gray-900 bg-opacity-40 hover:bg-opacity-60 border border-gray-500 px-4 md:px-6 py-1.5 md:py-2 rounded-full font-medium flex items-center gap-2 transition text-white text-xs md:text-base shadow-sm">
                Sign In <i class="fa-solid fa-arrow-up-right-from-square text-xs md:text-sm"></i>
            </a>
        </nav>

        <div class="relative z-10 flex-grow flex flex-col justify-center items-center text-center px-4 mt-24 md:mt-20">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-white leading-tight mb-4 md:mb-6 tracking-wide drop-shadow-md">
                Drive Into the Beauty<br class="hidden sm:block"> of Lampung
            </h1>
            <p class="text-white text-xs sm:text-sm md:text-base max-w-2xl mb-8 md:mb-10 opacity-90 drop-shadow px-2">
                Start your journey with comfort and freedom as you explore Lampung at your own pace. With reliable vehicles and an easy booking process, every destination from beaches to hidden gems becomes closer, more practical, and more enjoyable.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-3 md:gap-4 w-full sm:w-auto px-4 sm:px-0">
                <a href="{{ route('login') }}" class="glass-nav text-white text-sm md:text-base font-semibold px-6 md:px-8 py-3 rounded-full hover:bg-white hover:text-blue-600 transition flex justify-center items-center gap-2 w-full sm:w-auto">
                    Book Now <i class="fa-solid fa-arrow-right"></i>
                </a>
                <a href="{{ route('catalog') }}" class="glass-nav text-white text-sm md:text-base font-semibold px-6 md:px-8 py-3 rounded-full hover:bg-white hover:text-blue-600 transition flex justify-center items-center gap-2 w-full sm:w-auto">
                    Looking Catalog <i class="fa-solid fa-arrow-up-right-from-square text-xs md:text-sm"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-16 md:py-20 w-full">
        <h2 class="text-2xl md:text-3xl font-bold text-center text-[#4A6EB0] mb-8 md:mb-12">Why Choose Tripzy?</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            <div class="bg-[#C5D6F5] p-5 md:p-6 rounded-2xl relative overflow-hidden shadow-sm hover:-translate-y-1 transition transform">
                <span class="absolute top-2 right-4 text-4xl md:text-5xl font-bold text-white opacity-40">01</span>
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gray-600 text-white rounded-full flex items-center justify-center text-lg md:text-xl mb-4 relative z-10">
                    <i class="fa-regular fa-clock"></i>
                </div>
                <h3 class="font-bold text-[#3B5B92] mb-1 md:mb-2 relative z-10 text-sm md:text-base">24/7 SERVICE</h3>
                <p class="text-xs md:text-sm text-gray-600 relative z-10">Customer service is ready to assist you anytime.</p>
            </div>

            <div class="bg-[#C5D6F5] p-5 md:p-6 rounded-2xl relative overflow-hidden shadow-sm hover:-translate-y-1 transition transform">
                <span class="absolute top-2 right-4 text-4xl md:text-5xl font-bold text-white opacity-40">02</span>
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gray-600 text-white rounded-full flex items-center justify-center text-lg md:text-xl mb-4 relative z-10">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h3 class="font-bold text-[#3B5B92] mb-1 md:mb-2 relative z-10 text-sm md:text-base">COMPREHENSIVE INSURANCE</h3>
                <p class="text-xs md:text-sm text-gray-600 relative z-10">Travel with peace of mind with complete protection</p>
            </div>

            <div class="bg-[#C5D6F5] p-5 md:p-6 rounded-2xl relative overflow-hidden shadow-sm hover:-translate-y-1 transition transform">
                <span class="absolute top-2 right-4 text-4xl md:text-5xl font-bold text-white opacity-40">03</span>
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gray-600 text-white rounded-full flex items-center justify-center text-lg md:text-xl mb-4 relative z-10">
                    <i class="fa-regular fa-calendar-check"></i>
                </div>
                <h3 class="font-bold text-[#3B5B92] mb-1 md:mb-2 relative z-10 text-sm md:text-base">EASY BOOKING</h3>
                <p class="text-xs md:text-sm text-gray-600 relative z-10">Fast reservation without complicated steps</p>
            </div>

            <div class="bg-[#C5D6F5] p-5 md:p-6 rounded-2xl relative overflow-hidden shadow-sm hover:-translate-y-1 transition transform">
                <span class="absolute top-2 right-4 text-4xl md:text-5xl font-bold text-white opacity-40">04</span>
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gray-600 text-white rounded-full flex items-center justify-center text-lg md:text-xl mb-4 relative z-10">
                    <i class="fa-solid fa-star"></i>
                </div>
                <h3 class="font-bold text-[#3B5B92] mb-1 md:mb-2 relative z-10 text-sm md:text-base">GUARANTEED QUALITY</h3>
                <p class="text-xs md:text-sm text-gray-600 relative z-10">Experience top-quality travel with confidence</p>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 pb-16 md:pb-24 w-full">
        <div class="text-center mb-8 md:mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-[#4A6EB0] mb-3 md:mb-4">Best Car Selection</h2>
            <p class="text-sm md:text-lg text-[#5A7CB8] max-w-2xl mx-auto">Enjoy a wide selection of top-quality vehicles, giving you the flexibility to choose the perfect car for every journey.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 md:gap-8">
            @if(isset($cars) && $cars->count() > 0)
                @foreach($cars as $car)
                <div class="bg-gradient-to-b from-[#B8CDEE] to-[#DEE8F6] rounded-3xl p-6 md:p-8 shadow-lg flex flex-col relative transition transform hover:-translate-y-1 hover:shadow-xl">
                    <span class="absolute top-5 left-5 md:top-6 md:left-6 bg-white/50 text-[#3B5B92] px-3 py-1 rounded-full text-[10px] md:text-xs font-bold uppercase tracking-wider backdrop-blur-sm z-20">{{ $car->category }}</span>
                    
                    <img src="{{ asset($car->image_path) }}" alt="{{ $car->name }}" class="w-full object-contain h-32 md:h-48 mb-4 md:mb-6 drop-shadow-xl z-10">
                    <h3 class="text-lg md:text-xl font-bold text-gray-800 uppercase truncate">{{ $car->name }}</h3>
                    <div class="text-xl md:text-2xl font-bold text-gray-900 my-1 md:my-2">
                        Rp {{ number_format($car->price_per_day, 0, ',', '.') }} <span class="text-xs md:text-sm font-normal text-gray-600">/day</span>
                    </div>
                    
                    <div class="flex justify-between items-end mt-2 md:mt-4">
                        <div class="space-y-1.5 md:space-y-2 text-[10px] md:text-xs font-medium text-gray-700">
                            <p><i class="fa-solid fa-users w-4 md:w-5"></i> {{ $car->seats }} Passengers</p>
                            <p><i class="fa-solid fa-gear w-4 md:w-5"></i> {{ $car->transmission }}</p>
                            <p><i class="fa-solid fa-gas-pump w-4 md:w-5"></i> {{ $car->fuel_type }}</p>
                        </div>
                        <img src="{{ asset($car->image_path) }}" alt="{{ $car->name }} Thumbnail" class="w-16 h-10 md:w-24 md:h-16 object-contain drop-shadow-md opacity-80">
                    </div>
                    
                    <a href="{{ route('login') }}" class="mt-6 md:mt-8 w-full py-2.5 md:py-3 rounded-full bg-gradient-to-r from-white/40 to-white/20 border border-white/50 text-[#3B5B92] font-bold shadow-sm hover:bg-white/50 transition text-center inline-block text-sm md:text-base">
                        Book Now
                    </a>
                </div>
                @endforeach
            @else
                <div class="col-span-1 md:col-span-2 text-center text-gray-500 py-10 bg-white/50 rounded-2xl">
                    <i class="fa-solid fa-car-side text-4xl mb-3 opacity-50"></i>
                    <p>Mobil belum tersedia di katalog.</p>
                </div>
            @endif
        </div>
    </div>

    <footer class="bg-gradient-to-t from-[#5978B4] to-[#A9BFE4] text-white py-10 md:py-12 mt-auto">
        <div class="max-w-6xl mx-auto px-4 flex flex-col md:flex-row justify-between items-start gap-8 md:gap-0">
            
            <div class="w-full md:w-auto">
                <h2 class="text-4xl md:text-5xl font-bold italic text-[#3B5B92] mb-4 md:mb-6 drop-shadow-sm">Tripzy</h2>
                <div class="flex space-x-4 text-2xl text-white">
                    <a href="#" class="hover:text-[#3B5B92] transition"><i class="fa-brands fa-discord"></i></a>
                    <a href="#" class="hover:text-[#3B5B92] transition"><i class="fa-brands fa-whatsapp"></i></a>
                    <a href="#" class="hover:text-[#3B5B92] transition"><i class="fa-brands fa-telegram"></i></a>
                    <a href="#" class="hover:text-[#3B5B92] transition"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>

            <div class="w-full md:w-1/2">
                <h3 class="text-xl font-bold text-[#3B5B92] mb-4">Contact Us</h3>
                <ul class="space-y-4 text-xs md:text-sm font-medium">
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-location-dot mt-1 shrink-0"></i>
                        <span>Jl. Prof. Dr. Ir. Sumantri Brojonegoro No.1, Gedong Meneng, Kec. Rajabasa,<br>Kota Bandar Lampung, Lampung 35141</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-phone shrink-0"></i>
                        <span>+6285278139801</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-envelope shrink-0"></i>
                        <span>tripzy@gmail.com</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-regular fa-clock shrink-0"></i>
                        <span>Senin - Minggu<br>24 Jam</span>
                    </li>
                </ul>
            </div>
            
        </div>
    </footer>
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