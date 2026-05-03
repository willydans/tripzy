<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Destination - Tripzy</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #E2EAF6; }
        
        .hero-gradient {
            background: linear-gradient(180deg, #3A62A6 0%, #7A9FE0 50%, #E2EAF6 100%);
            min-height: 100vh;
        }

        /* Styling Navbar Sticky */
        .glass-nav {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 100;
            width: 80%; 
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        /* Animasi Accordion Cards */
        .card-container {
            display: flex;
            gap: 1.5rem;
            height: 600px;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .dest-card {
            position: relative;
            border-radius: 1.5rem;
            overflow: hidden;
            background-size: cover;
            background-position: center;
            cursor: pointer;
            transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1);
            display: flex;
            align-items: flex-end;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        /* State pas Kartu Mengecil (Default/Inactive) */
        .dest-card.inactive {
            flex: 1;
        }
        .dest-card.inactive .card-active-content {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
        }
        .dest-card.inactive .card-inactive-content {
            opacity: 1;
            transition: opacity 0.5s 0.2s; 
        }

        /* State pas Kartu Melebar (Active) */
        .dest-card.active {
            flex: 4; 
            cursor: default;
        }
        .dest-card.active .card-inactive-content {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
        }
        .dest-card.active .card-active-content {
            opacity: 1;
            transition: opacity 0.5s 0.3s;
        }

        /* Gradient Overlay Biru khas Tripzy */
        .card-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(58, 98, 166, 0.2), rgba(58, 98, 166, 0.8));
            transition: all 0.5s ease;
        }
        .dest-card.active .card-overlay {
            background: linear-gradient(to top, rgba(10, 25, 50, 0.9) 0%, rgba(58, 98, 166, 0.1) 100%);
        }

        /* Teks Vertikal */
        .text-vertical {
            writing-mode: vertical-rl;
            text-orientation: mixed;
            letter-spacing: 4px;
        }
        
        /* Font Bebas Neue untuk Angka 01, 02 dll */
        .font-number { font-family: 'Bebas Neue', sans-serif; }
    </style>
</head>
<body class="text-gray-800 overflow-x-hidden">

    <!-- NAVBAR STICKY -->
    <nav class="glass-nav rounded-full px-8 py-4 flex justify-between items-center text-white transition-all">
        <h1 class="text-2xl font-bold italic tracking-wider"><a href="{{ route('home') }}">Tripzy</a></h1>
        
        <div class="hidden md:flex space-x-8 font-medium">
            <a href="{{ route('home') }}" class="hover:text-blue-200 transition">Home</a>
            <a href="{{ route('catalog') }}" class="hover:text-blue-200 transition">Catalog</a>
            <a href="{{ route('contact') }}" class="hover:text-blue-200 transition">Contact</a>
            <a href="{{ route('destination') }}" class="text-blue-200 hover:text-white transition drop-shadow-md">Destination</a>
        </div>

        <a href="{{ route('login') }}" class="bg-gray-900 bg-opacity-40 hover:bg-opacity-60 border border-gray-600 px-6 py-2 rounded-full font-medium flex items-center gap-2 transition">
            Sign In <i class="fa-solid fa-arrow-up-right-from-square text-sm"></i>
        </a>
    </nav>

    <!-- HERO SECTION -->
    <div class="hero-gradient pt-40 pb-20 flex flex-col items-center relative overflow-hidden">
        
        <!-- Header Text -->
        <div class="text-center px-4 mb-12 relative z-10">
            <p class="text-white text-sm font-bold tracking-widest uppercase mb-3">Recomendation Destination</p>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white tracking-wider drop-shadow-lg uppercase font-bebas" style="font-family: 'Bebas Neue', sans-serif; letter-spacing: 2px;">
                Top Travel Spots Worth Visiting
            </h1>
        </div>

        <!-- ACCORDION CARDS SECTION -->
        <div class="card-container px-4 relative z-10">
            
            <!-- Card 1 (Default Active) -->
            <div class="dest-card active" style="background-image: url('https://images.unsplash.com/photo-1544256718-3b10b06abdb9?q=80&w=1000&auto=format&fit=crop');" onclick="activateCard(this)">
                <div class="card-overlay"></div>
                
                <!-- Konten saat menyusut (Inactive) -->
                <div class="card-inactive-content absolute inset-0 flex flex-col items-center justify-between py-8">
                    <div class="w-0.5 h-16 bg-white/60"></div>
                    <h3 class="text-white font-bold text-xl uppercase text-vertical mt-4">Pahawang Island</h3>
                    <span class="text-5xl font-number text-white drop-shadow-md">01</span>
                </div>

                <!-- Konten saat melebar (Active) -->
                <div class="card-active-content relative z-10 p-8 w-full">
                    <h2 class="text-4xl font-bold text-white mb-3 uppercase tracking-wider" style="font-family: 'Bebas Neue', sans-serif;">Pahawang Island</h2>
                    <p class="text-gray-200 text-sm w-3/4 leading-relaxed">
                        A breathtaking tropical island known for its crystal-clear turquoise waters, soft white sand, and vibrant marine life. Pahawang Island is a paradise for snorkeling and underwater exploration, where you can swim alongside colorful fish and coral reefs. The peaceful atmosphere and untouched beauty make it a perfect escape from the busy city, ideal for both relaxing and adventure-filled trips.
                    </p>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="dest-card inactive" style="background-image: url('https://images.unsplash.com/photo-1552596222-72c28a0f1db0?q=80&w=1000&auto=format&fit=crop');" onclick="activateCard(this)">
                <div class="card-overlay"></div>
                <div class="card-inactive-content absolute inset-0 flex flex-col items-center justify-between py-8">
                    <div class="w-0.5 h-16 bg-white/60"></div>
                    <h3 class="text-white font-bold text-xl uppercase text-vertical mt-4">Way Kambas</h3>
                    <span class="text-5xl font-number text-white drop-shadow-md">02</span>
                </div>
                <div class="card-active-content relative z-10 p-8 w-full">
                    <h2 class="text-4xl font-bold text-white mb-3 uppercase tracking-wider" style="font-family: 'Bebas Neue', sans-serif;">Way Kambas</h2>
                    <p class="text-gray-200 text-sm w-3/4 leading-relaxed">
                        National Park known for its elephant conservation center. Experience the wild and interact with these gentle giants in their natural habitat.
                    </p>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="dest-card inactive" style="background-image: url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=1000&auto=format&fit=crop');" onclick="activateCard(this)">
                <div class="card-overlay"></div>
                <div class="card-inactive-content absolute inset-0 flex flex-col items-center justify-between py-8">
                    <div class="w-0.5 h-16 bg-white/60"></div>
                    <h3 class="text-white font-bold text-xl uppercase text-vertical mt-4">Kiluan Bay</h3>
                    <span class="text-5xl font-number text-white drop-shadow-md">03</span>
                </div>
                <div class="card-active-content relative z-10 p-8 w-full">
                    <h2 class="text-4xl font-bold text-white mb-3 uppercase tracking-wider" style="font-family: 'Bebas Neue', sans-serif;">Kiluan Bay</h2>
                    <p class="text-gray-200 text-sm w-3/4 leading-relaxed">
                        Famous for its wild dolphin sightings and stunning coastal views. A hidden gem for nature lovers.
                    </p>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="dest-card inactive" style="background-image: url('https://images.unsplash.com/photo-1520483601560-389dff434fdf?q=80&w=1000&auto=format&fit=crop');" onclick="activateCard(this)">
                <div class="card-overlay"></div>
                <div class="card-inactive-content absolute inset-0 flex flex-col items-center justify-between py-8">
                    <div class="w-0.5 h-16 bg-white/60"></div>
                    <h3 class="text-white font-bold text-xl uppercase text-vertical mt-4">Gigi Hiu Beach</h3>
                    <span class="text-5xl font-number text-white drop-shadow-md">04</span>
                </div>
                <div class="card-active-content relative z-10 p-8 w-full">
                    <h2 class="text-4xl font-bold text-white mb-3 uppercase tracking-wider" style="font-family: 'Bebas Neue', sans-serif;">Gigi Hiu Beach</h2>
                    <p class="text-gray-200 text-sm w-3/4 leading-relaxed">
                        A unique beach featuring sharp, towering rock formations resembling shark teeth. Perfect for photography.
                    </p>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="dest-card inactive" style="background-image: url('https://images.unsplash.com/photo-1589394815804-964ce0ff81a7?q=80&w=1000&auto=format&fit=crop');" onclick="activateCard(this)">
                <div class="card-overlay"></div>
                <div class="card-inactive-content absolute inset-0 flex flex-col items-center justify-between py-8">
                    <div class="w-0.5 h-16 bg-white/60"></div>
                    <h3 class="text-white font-bold text-xl uppercase text-vertical mt-4">Sebesi Island</h3>
                    <span class="text-5xl font-number text-white drop-shadow-md">05</span>
                </div>
                <div class="card-active-content relative z-10 p-8 w-full">
                    <h2 class="text-4xl font-bold text-white mb-3 uppercase tracking-wider" style="font-family: 'Bebas Neue', sans-serif;">Sebesi Island</h2>
                    <p class="text-gray-200 text-sm w-3/4 leading-relaxed">
                        The closest inhabited island to Krakatoa. Offers beautiful beaches and acts as a basecamp for volcano tours.
                    </p>
                </div>
            </div>

            <!-- Card 6 -->
            <div class="dest-card inactive" style="background-image: url('https://images.unsplash.com/photo-1432405972618-fc2c07040d9c?q=80&w=1000&auto=format&fit=crop');" onclick="activateCard(this)">
                <div class="card-overlay"></div>
                <div class="card-inactive-content absolute inset-0 flex flex-col items-center justify-between py-8">
                    <div class="w-0.5 h-16 bg-white/60"></div>
                    <h3 class="text-white font-bold text-xl uppercase text-vertical mt-4">Gangsa Waterfall</h3>
                    <span class="text-5xl font-number text-white drop-shadow-md">06</span>
                </div>
                <div class="card-active-content relative z-10 p-8 w-full">
                    <h2 class="text-4xl font-bold text-white mb-3 uppercase tracking-wider" style="font-family: 'Bebas Neue', sans-serif;">Gangsa Waterfall</h2>
                    <p class="text-gray-200 text-sm w-3/4 leading-relaxed">
                        A majestic waterfall hidden within lush greenery, offering a refreshing and serene natural retreat.
                    </p>
                </div>
            </div>

        </div>
    </div>

    <!-- FOOTER SECTION -->
    <footer class="bg-gradient-to-t from-[#5978B4] to-[#A9BFE4] text-white py-12 mt-10">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between items-start">
            <div class="mb-8 md:mb-0">
                <h2 class="text-5xl font-bold italic text-[#3B5B92] mb-6 drop-shadow-sm">Tripzy</h2>
                <div class="flex space-x-4 text-2xl text-white">
                    <a href="#" class="hover:text-[#3B5B92] transition"><i class="fa-brands fa-discord"></i></a>
                    <a href="#" class="hover:text-[#3B5B92] transition"><i class="fa-brands fa-whatsapp"></i></a>
                    <a href="#" class="hover:text-[#3B5B92] transition"><i class="fa-brands fa-telegram"></i></a>
                    <a href="#" class="hover:text-[#3B5B92] transition"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>
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

    <!-- JAVASCRIPT UNTUK ANIMASI ACCORDION -->
    <script>
        function activateCard(clickedCard) {
            // Ambil semua elemen dengan class 'dest-card'
            const cards = document.querySelectorAll('.dest-card');
            
            // Loop semua kartu, ubah jadi 'inactive'
            cards.forEach(card => {
                card.classList.remove('active');
                card.classList.add('inactive');
            });

            // Jadikan kartu yang diklik menjadi 'active'
            clickedCard.classList.remove('inactive');
            clickedCard.classList.add('active');
        }
    </script>

</body>
</html>