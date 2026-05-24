<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Tripzy</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #E2EAF6; overflow-x: hidden; }
        
        /* Gradient lebih gelap sesuai gambar */
        .hero-gradient {
            background: linear-gradient(180deg, #293D66 0%, #4A6BAA 50%, #E2EAF6 100%);
            min-height: 100vh;
            position: relative;
        }
        
        /* Grid pattern tipis */
        .hero-gradient::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: linear-gradient(to right, rgba(255,255,255,0.03) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            z-index: 0;
        }

        /* Navbar Sticky (Responsive) */
        .glass-nav {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: fixed;
            top: 15px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 100;
            width: 95%; 
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }
        @media (min-width: 768px) {
            .glass-nav { width: 80%; top: 20px; }
        }

        /* Sembunyikan scrollbar untuk navigasi mobile */
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }

        /* Icon Glow & Hover Effect */
        .social-icon {
            transition: all 0.3s ease;
            filter: drop-shadow(0px 4px 6px rgba(0, 0, 0, 0.1));
        }
        .social-icon:hover {
            transform: translateY(-5px) scale(1.1);
            filter: drop-shadow(0px 0px 15px rgba(255, 255, 255, 0.5));
        }
    </style>
</head>
<body class="text-gray-800 flex flex-col min-h-screen">

    <nav class="glass-nav rounded-full px-5 py-3 md:px-8 md:py-4 flex justify-between items-center text-white transition-all overflow-x-auto hide-scroll">
        <h1 class="text-xl md:text-2xl font-bold italic tracking-wider shrink-0 mr-4"><a href="{{ route('home') }}">Tripzy</a></h1>
        
        <div class="flex space-x-4 md:space-x-8 font-medium text-xs md:text-base shrink-0 items-center">
            <a href="{{ route('home') }}" class="hover:text-blue-200 transition hidden sm:block">Home</a>
            <a href="{{ route('catalog') }}" class="hover:text-blue-200 transition">Catalog</a>
            <a href="{{ route('contact') }}" class="text-blue-200 hover:text-white transition drop-shadow-md border-b-2 border-blue-200 pb-1 md:pb-0 md:border-none">Contact</a>
            <a href="{{ route('destination') }}" class="hover:text-blue-200 transition hidden sm:block">Destination</a>
        </div>

        <a href="{{ route('login') }}" class="bg-gray-900 bg-opacity-40 hover:bg-opacity-60 border border-gray-600 px-4 md:px-6 py-1.5 md:py-2 rounded-full font-medium flex items-center gap-2 transition text-xs md:text-base shrink-0 ml-4 shadow-sm">
            Sign In <i class="fa-solid fa-arrow-up-right-from-square text-[10px] md:text-sm"></i>
        </a>
    </nav>

    <div class="hero-gradient flex flex-col justify-center items-center relative overflow-hidden px-4 flex-grow pt-28 md:pt-20">
        
        <div class="relative z-10 text-center mt-10 md:mt-20 max-w-4xl mx-auto">
            <p class="text-white text-xs md:text-sm font-bold tracking-[0.2em] uppercase mb-3 md:mb-4 opacity-90">Get In Touch With Us</p>
            <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-white mb-4 md:mb-6 tracking-wide drop-shadow-lg uppercase leading-tight" style="font-family: 'Bebas Neue', sans-serif; letter-spacing: 2px;">
                We're Here To Help <br class="block sm:hidden"> You Anytime
            </h1>
            <p class="text-white text-sm md:text-base lg:text-lg leading-relaxed opacity-90 drop-shadow max-w-3xl mx-auto font-light px-2 md:px-0">
                Our team is ready to assist you with bookings, questions, or any support you need during your journey. Feel free to reach out we'll make sure your experience stays smooth, comfortable, and worry free.
            </p>
        </div>

        <div class="relative z-10 flex flex-wrap justify-center gap-6 md:gap-12 mt-12 md:mt-16 pb-20">
            <a href="#" class="text-white text-5xl md:text-6xl lg:text-7xl social-icon p-2">
                <i class="fa-brands fa-discord"></i>
            </a>
            <a href="#" class="text-white text-5xl md:text-6xl lg:text-7xl social-icon p-2">
                <i class="fa-brands fa-whatsapp"></i>
            </a>
            <a href="#" class="text-white text-5xl md:text-6xl lg:text-7xl social-icon p-2">
                <i class="fa-brands fa-telegram"></i>
            </a>
            <a href="#" class="text-white text-5xl md:text-6xl lg:text-7xl social-icon p-2">
                <i class="fa-brands fa-instagram"></i>
            </a>
        </div>
    </div>

    <footer class="bg-gradient-to-t from-[#5978B4] to-[#E2EAF6] text-white py-10 md:py-12 mt-auto">
        <div class="max-w-7xl mx-auto px-4 lg:px-8 flex flex-col md:flex-row justify-between items-start gap-8 md:gap-0">
            
            <div class="w-full md:w-auto text-center md:text-left">
                <h2 class="text-4xl md:text-5xl font-bold italic text-[#3B5B92] mb-4 md:mb-6 drop-shadow-sm">Tripzy</h2>
                <div class="flex space-x-4 text-2xl text-white justify-center md:justify-start">
                    <a href="#" class="hover:text-[#3B5B92] transition"><i class="fa-brands fa-discord"></i></a>
                    <a href="#" class="hover:text-[#3B5B92] transition"><i class="fa-brands fa-whatsapp"></i></a>
                    <a href="#" class="hover:text-[#3B5B92] transition"><i class="fa-brands fa-telegram"></i></a>
                    <a href="#" class="hover:text-[#3B5B92] transition"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>

            <div class="w-full md:w-1/2 text-center md:text-left">
                <h3 class="text-xl font-bold text-[#3B5B92] mb-4">Contact Us</h3>
                <ul class="space-y-4 text-xs md:text-sm font-medium text-gray-700">
                    <li class="flex items-start justify-center md:justify-start gap-3">
                        <i class="fa-solid fa-location-dot mt-1 text-[#3B5B92] shrink-0"></i>
                        <span>Jl. Prof. Dr. Ir. Sumantri Brojonegoro No.1, Gedong Meneng, Kec. Rajabasa,<br>Kota Bandar Lampung, Lampung 35141</span>
                    </li>
                    <li class="flex items-center justify-center md:justify-start gap-3">
                        <i class="fa-solid fa-phone text-[#3B5B92] shrink-0"></i>
                        <span>+6285278139801</span>
                    </li>
                    <li class="flex items-center justify-center md:justify-start gap-3">
                        <i class="fa-solid fa-envelope text-[#3B5B92] shrink-0"></i>
                        <span>tripzy@gmail.com</span>
                    </li>
                    <li class="flex items-center justify-center md:justify-start gap-3">
                        <i class="fa-regular fa-clock text-[#3B5B92] shrink-0"></i>
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