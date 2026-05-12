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
        body { font-family: 'Poppins', sans-serif; background-color: #E2EAF6; overflow-x: hidden; }
        
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

        /* Animasi Accordion Cards Responsif */
        .card-container {
            display: flex;
            flex-direction: column; /* Vertikal di HP */
            gap: 0.75rem;
            height: 75vh;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }
        @media (min-width: 768px) {
            .card-container {
                flex-direction: row; /* Horizontal di Laptop */
                gap: 1.5rem;
                height: 600px;
            }
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
        .dest-card.inactive { flex: 1; }
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
        .dest-card.active { flex: 5; cursor: default; }
        .dest-card.active .card-inactive-content {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
        }
        /* Transisi arah untuk konten inactive */
        @media (max-width: 767px) {
            .dest-card.active .card-inactive-content { transform: translateX(-20px); }
        }
        @media (min-width: 768px) {
            .dest-card.active .card-inactive-content { transform: translateY(-20px); }
        }

        .dest-card.active .card-active-content {
            opacity: 1;
            transform: translateY(0);
            transition: opacity 0.5s 0.3s, transform 0.5s 0.3s;
        }
        .dest-card.inactive .card-active-content {
            transform: translateY(20px);
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

        /* Teks Vertikal di Laptop, Horizontal di HP */
        .text-vertical { white-space: nowrap; }
        @media (min-width: 768px) {
            .text-vertical {
                writing-mode: vertical-rl;
                text-orientation: mixed;
                letter-spacing: 4px;
                transform: rotate(180deg);
            }
        }
        
        .font-number { font-family: 'Bebas Neue', sans-serif; }
    </style>
</head>
<body class="text-gray-800 flex flex-col min-h-screen">

    <nav class="glass-nav rounded-full px-5 py-3 md:px-8 md:py-4 flex justify-between items-center text-white transition-all overflow-x-auto hide-scroll">
        <h1 class="text-xl md:text-2xl font-bold italic tracking-wider shrink-0 mr-4"><a href="{{ route('home') }}">Tripzy</a></h1>
        
        <div class="flex space-x-4 md:space-x-8 font-medium text-xs md:text-base shrink-0 items-center">
            <a href="{{ route('home') }}" class="hover:text-blue-200 transition hidden sm:block">Home</a>
            <a href="{{ route('catalog') }}" class="hover:text-blue-200 transition">Catalog</a>
            <a href="{{ route('contact') }}" class="hover:text-blue-200 transition hidden sm:block">Contact</a>
            <a href="{{ route('destination') }}" class="text-blue-200 hover:text-white transition drop-shadow-md border-b-2 border-blue-200 pb-1 md:pb-0 md:border-none">Destination</a>
        </div>

        <a href="{{ route('login') }}" class="bg-gray-900 bg-opacity-40 hover:bg-opacity-60 border border-gray-600 px-4 md:px-6 py-1.5 md:py-2 rounded-full font-medium flex items-center gap-2 transition text-xs md:text-base shrink-0 ml-4">
            Sign In <i class="fa-solid fa-arrow-up-right-from-square text-[10px] md:text-sm"></i>
        </a>
    </nav>

    <div class="hero-gradient pt-28 md:pt-40 pb-16 md:pb-20 flex flex-col items-center relative overflow-hidden flex-grow">
        
        <div class="text-center px-4 mb-8 md:mb-12 relative z-10">
            <p class="text-white text-xs md:text-sm font-bold tracking-widest uppercase mb-2 md:mb-3">Recomendation Destination</p>
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white tracking-wider drop-shadow-lg uppercase font-bebas leading-tight" style="font-family: 'Bebas Neue', sans-serif; letter-spacing: 2px;">
                Top Travel Spots<br class="block sm:hidden"> Worth Visiting
            </h1>
        </div>

        <div class="card-container px-4 relative z-10">
            
            <div class="dest-card active" style="background-image: url('https://heartline.co.id/wp-content/uploads/2025/01/Pulau-Pahawang-Sebuah-Permata-Tersembunyi-Di-Lampung-05.jpg');" onclick="activateCard(this)">
                <div class="card-overlay"></div>
                
                <div class="card-inactive-content absolute inset-0 flex flex-row md:flex-col items-center justify-between py-4 px-6 md:py-8 md:px-0">
                    <div class="hidden md:block w-0.5 h-16 bg-white/60"></div>
                    <h3 class="text-white font-bold text-lg md:text-xl uppercase text-vertical md:mt-4">Pahawang Island</h3>
                    <span class="text-3xl md:text-5xl font-number text-white drop-shadow-md">01</span>
                </div>

                <div class="card-active-content absolute inset-x-0 bottom-0 p-5 md:p-8 w-full flex flex-col justify-end h-full z-10">
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-2 md:mb-3 uppercase tracking-wider" style="font-family: 'Bebas Neue', sans-serif;">Pahawang Island</h2>
                    <p class="text-gray-200 text-[10px] md:text-sm w-full md:w-3/4 leading-relaxed line-clamp-3 md:line-clamp-none">
                        A breathtaking tropical island known for its crystal-clear turquoise waters, soft white sand, and vibrant marine life. Pahawang Island is a paradise for snorkeling and underwater exploration, where you can swim alongside colorful fish and coral reefs. 
                    </p>
                </div>
            </div>

            <div class="dest-card inactive" style="background-image: url('https://akcdn.detik.net.id/community/media/visual/2024/01/29/gajah-sumatera-di-taman-nasional-way-kambas_169.jpeg?w=700&q=90');" onclick="activateCard(this)">
                <div class="card-overlay"></div>
                <div class="card-inactive-content absolute inset-0 flex flex-row md:flex-col items-center justify-between py-4 px-6 md:py-8 md:px-0">
                    <div class="hidden md:block w-0.5 h-16 bg-white/60"></div>
                    <h3 class="text-white font-bold text-lg md:text-xl uppercase text-vertical md:mt-4">Way Kambas</h3>
                    <span class="text-3xl md:text-5xl font-number text-white drop-shadow-md">02</span>
                </div>
                <div class="card-active-content absolute inset-x-0 bottom-0 p-5 md:p-8 w-full flex flex-col justify-end h-full z-10">
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-2 md:mb-3 uppercase tracking-wider" style="font-family: 'Bebas Neue', sans-serif;">Way Kambas</h2>
                    <p class="text-gray-200 text-[10px] md:text-sm w-full md:w-3/4 leading-relaxed line-clamp-3 md:line-clamp-none">
                        National Park known for its elephant conservation center. Experience the wild and interact with these gentle giants in their natural habitat.
                    </p>
                </div>
            </div>

            <div class="dest-card inactive" style="background-image: url('https://ik.imagekit.io/tvlk/blog/2024/07/shutterstock_492879184.jpg?tr=q-70,c-at_max,w-1000,h-600');" onclick="activateCard(this)">
                <div class="card-overlay"></div>
                <div class="card-inactive-content absolute inset-0 flex flex-row md:flex-col items-center justify-between py-4 px-6 md:py-8 md:px-0">
                    <div class="hidden md:block w-0.5 h-16 bg-white/60"></div>
                    <h3 class="text-white font-bold text-lg md:text-xl uppercase text-vertical md:mt-4">Kiluan Bay</h3>
                    <span class="text-3xl md:text-5xl font-number text-white drop-shadow-md">03</span>
                </div>
                <div class="card-active-content absolute inset-x-0 bottom-0 p-5 md:p-8 w-full flex flex-col justify-end h-full z-10">
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-2 md:mb-3 uppercase tracking-wider" style="font-family: 'Bebas Neue', sans-serif;">Kiluan Bay</h2>
                    <p class="text-gray-200 text-[10px] md:text-sm w-full md:w-3/4 leading-relaxed line-clamp-3 md:line-clamp-none">
                        Famous for its wild dolphin sightings and stunning coastal views. A hidden gem for nature lovers.
                    </p>
                </div>
            </div>

            <div class="dest-card inactive" style="background-image: url('https://www.batiqa.com/upload/news/z/lampung-pantai-gigi-hiu_3mnwt.jpg');" onclick="activateCard(this)">
                <div class="card-overlay"></div>
                <div class="card-inactive-content absolute inset-0 flex flex-row md:flex-col items-center justify-between py-4 px-6 md:py-8 md:px-0">
                    <div class="hidden md:block w-0.5 h-16 bg-white/60"></div>
                    <h3 class="text-white font-bold text-lg md:text-xl uppercase text-vertical md:mt-4">Gigi Hiu Beach</h3>
                    <span class="text-3xl md:text-5xl font-number text-white drop-shadow-md">04</span>
                </div>
                <div class="card-active-content absolute inset-x-0 bottom-0 p-5 md:p-8 w-full flex flex-col justify-end h-full z-10">
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-2 md:mb-3 uppercase tracking-wider" style="font-family: 'Bebas Neue', sans-serif;">Gigi Hiu Beach</h2>
                    <p class="text-gray-200 text-[10px] md:text-sm w-full md:w-3/4 leading-relaxed line-clamp-3 md:line-clamp-none">
                        A unique beach featuring sharp, towering rock formations resembling shark teeth. Perfect for photography.
                    </p>
                </div>
            </div>

            <div class="dest-card inactive" style="background-image: url('https://s-light.tiket.photos/t/01E25EBZS3W0FY9GTG6C42E1SE/rsfit1440960gsm/events/2024/01/18/f0f500bb-fea9-4e4b-9443-88a0ea9ad65d-1705550023217-4f1de8e38bb54e954f45ac776c75d4b5.png');" onclick="activateCard(this)">
                <div class="card-overlay"></div>
                <div class="card-inactive-content absolute inset-0 flex flex-row md:flex-col items-center justify-between py-4 px-6 md:py-8 md:px-0">
                    <div class="hidden md:block w-0.5 h-16 bg-white/60"></div>
                    <h3 class="text-white font-bold text-lg md:text-xl uppercase text-vertical md:mt-4">Sebesi Island</h3>
                    <span class="text-3xl md:text-5xl font-number text-white drop-shadow-md">05</span>
                </div>
                <div class="card-active-content absolute inset-x-0 bottom-0 p-5 md:p-8 w-full flex flex-col justify-end h-full z-10">
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-2 md:mb-3 uppercase tracking-wider" style="font-family: 'Bebas Neue', sans-serif;">Sebesi Island</h2>
                    <p class="text-gray-200 text-[10px] md:text-sm w-full md:w-3/4 leading-relaxed line-clamp-3 md:line-clamp-none">
                        The closest inhabited island to Krakatoa. Offers beautiful beaches and acts as a basecamp for volcano tours.
                    </p>
                </div>
            </div>

            <div class="dest-card inactive" style="background-image: url('https://wisato.id/wp-content/uploads/2019/11/Curup-Gangsa-@igustimade.wira_.jpg');" onclick="activateCard(this)">
                <div class="card-overlay"></div>
                <div class="card-inactive-content absolute inset-0 flex flex-row md:flex-col items-center justify-between py-4 px-6 md:py-8 md:px-0">
                    <div class="hidden md:block w-0.5 h-16 bg-white/60"></div>
                    <h3 class="text-white font-bold text-lg md:text-xl uppercase text-vertical md:mt-4">Gangsa Waterfall</h3>
                    <span class="text-3xl md:text-5xl font-number text-white drop-shadow-md">06</span>
                </div>
                <div class="card-active-content absolute inset-x-0 bottom-0 p-5 md:p-8 w-full flex flex-col justify-end h-full z-10">
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-2 md:mb-3 uppercase tracking-wider" style="font-family: 'Bebas Neue', sans-serif;">Gangsa Waterfall</h2>
                    <p class="text-gray-200 text-[10px] md:text-sm w-full md:w-3/4 leading-relaxed line-clamp-3 md:line-clamp-none">
                        A majestic waterfall hidden within lush greenery, offering a refreshing and serene natural retreat.
                    </p>
                </div>
            </div>

        </div>
    </div>

    <footer class="mt-auto border-t border-gray-300 pt-10 pb-8 bg-[#E2EAF6]">
        <div class="max-w-7xl mx-auto px-4 lg:px-8 flex flex-col md:flex-row justify-between items-start gap-6 md:gap-0">
            <div class="w-full md:w-auto text-center md:text-left">
                <h2 class="text-4xl md:text-5xl font-bold font-bebas tracking-widest text-[#3A62A6] mb-4">Tripzy</h2>
            </div>
            <div class="w-full md:w-1/3 text-center md:text-left">
                <h3 class="text-xl font-bold text-[#3A62A6] mb-4">Contact Us</h3>
                <ul class="space-y-4 text-xs font-medium text-gray-600">
                    <li class="flex items-start justify-center md:justify-start gap-3">
                        <i class="fa-solid fa-location-dot mt-1 text-[#3A62A6] shrink-0"></i>
                        <span>Jl. Prof. Dr. Ir. Sumantri Brojonegoro No.1, Gedong Meneng, Kec. Rajabasa, Bandar Lampung 35141</span>
                    </li>
                </ul>
            </div>
        </div>
    </footer>

    <script>
        function activateCard(clickedCard) {
            const cards = document.querySelectorAll('.dest-card');
            
            cards.forEach(card => {
                card.classList.remove('active');
                card.classList.add('inactive');
            });

            clickedCard.classList.remove('inactive');
            clickedCard.classList.add('active');
        }
    </script>

</body>
</html>