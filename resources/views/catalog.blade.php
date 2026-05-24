<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalog - Tripzy</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #F4F7FC; overflow-x: hidden; }
        
        .hero-gradient {
            background: linear-gradient(180deg, #3A62A6 0%, #7A9FE0 50%, #F4F7FC 100%);
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

        .filter-btn.active {
            background-color: white;
            color: #4A6EB0;
            font-weight: 600;
            padding: 0.25rem 1rem;
            border-radius: 9999px;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="text-gray-800 flex flex-col min-h-screen">

    <div class="hero-gradient pb-16 md:pb-20 flex flex-col relative overflow-hidden">
        
        <nav class="glass-nav rounded-full px-5 py-3 md:px-8 md:py-4 flex justify-between items-center text-white transition-all overflow-x-auto hide-scroll">
            <h1 class="text-xl md:text-2xl font-bold italic tracking-wider shrink-0 mr-4"><a href="{{ route('home') }}">Tripzy</a></h1>
            
            <div class="flex space-x-4 md:space-x-8 font-medium text-xs md:text-base shrink-0 items-center">
                <a href="{{ route('home') }}" class="hover:text-blue-200 transition hidden sm:block">Home</a>
                <a href="{{ route('catalog') }}" class="text-blue-200 border-b-2 border-blue-200 pb-1 hover:text-white transition">Catalog</a>
                <a href="{{ route('contact') }}" class="hover:text-blue-200 transition hidden sm:block">Contact</a>
                <a href="{{ route('destination') }}" class="hover:text-blue-200 transition">Destination</a>
            </div>

            <a href="{{ route('login') }}" class="bg-gray-900 bg-opacity-40 hover:bg-opacity-60 border border-gray-500 px-4 md:px-6 py-1.5 md:py-2 rounded-full font-medium flex items-center gap-2 transition text-white text-xs md:text-base shrink-0 ml-4 shadow-sm">
                Sign In <i class="fa-solid fa-arrow-up-right-from-square text-[10px] md:text-sm"></i>
            </a>
        </nav>

        <div class="relative z-10 flex-grow flex flex-col justify-center items-center text-center px-4 mt-28 md:mt-32">
            <p class="text-white text-xs md:text-sm font-bold tracking-widest uppercase mb-2">Our Collection</p>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4 md:mb-6 tracking-wide drop-shadow-md uppercase">
                Luxury Car Selection
            </h1>
            <p class="text-white text-xs md:text-base max-w-3xl mb-8 md:mb-8 opacity-90 drop-shadow px-2">
                Our premium fleet offers a range of high-quality vehicles designed to provide a perfect balance of comfort, reliability, and performance, making every journey feel more exclusive and enjoyable.
            </p>
            <a href="#catalog-section" class="bg-white/20 border border-white/40 text-white px-6 md:px-8 py-2 md:py-2.5 rounded-full hover:bg-white hover:text-blue-600 transition flex items-center gap-2 text-sm md:text-base font-medium backdrop-blur-md">
                Explore Catalog <i class="fa-solid fa-arrow-down"></i>
            </a>
        </div>
    </div>

    <div id="catalog-section" class="max-w-7xl mx-auto px-4 mt-4 md:mt-2 mb-8 md:mb-10 flex flex-col md:flex-row justify-between items-center gap-4 md:gap-6 w-full">
        <div class="relative w-full md:w-80">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 transform -translate-y-1/2 text-[#8CA1C4]"></i>
            <input type="text" id="searchInput" placeholder="Find Your Perfect Car..." class="w-full bg-[#E2EAF6] text-sm rounded-full py-2.5 md:py-3 pl-10 pr-4 focus:outline-none text-[#5A7CB8] placeholder-[#8CA1C4] shadow-inner transition">
        </div>
        
        <div class="w-full md:w-auto overflow-x-auto hide-scroll rounded-full">
            <div class="flex flex-nowrap md:flex-wrap justify-start md:justify-center items-center gap-2 text-xs md:text-sm font-medium text-[#7C98C4] min-w-max pb-1 md:pb-0" id="filterContainer">
                <button class="filter-btn active hover:text-[#4A6EB0] transition px-3 py-1.5 md:px-4 md:py-2 rounded-full whitespace-nowrap" data-filter="all">All</button>
                @php
                    $categories = $cars->pluck('category')->unique();
                @endphp
                @foreach($categories as $category)
                    @php
                        // Memastikan format filter bener-bener bersih pakai Str::slug
                        $filterSlug = \Illuminate\Support\Str::slug($category);
                    @endphp
                    <button class="filter-btn hover:text-[#4A6EB0] transition px-3 py-1.5 md:px-4 md:py-2 rounded-full whitespace-nowrap" data-filter="{{ $filterSlug }}">{{ trim($category) }}</button>
                @endforeach
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 pb-20 md:pb-24 w-full flex-grow">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6" id="carGrid">
            
            @if(isset($cars) && $cars->count() > 0)
                @foreach($cars as $car)
                @php
                    // Pastikan kategori pada mobil formatnya persis sama dengan Str::slug
                    $carCategorySlug = \Illuminate\Support\Str::slug($car->category);
                    $carNameSafe = trim(strtolower($car->name));
                @endphp
                <div class="car-card bg-gradient-to-b from-[#8C9FBB] to-[#A9BBD6] rounded-2xl md:rounded-3xl p-5 md:p-6 shadow-md border border-white/20 flex flex-col justify-between transition transform hover:-translate-y-1 hover:shadow-xl" 
                     data-category="{{ $carCategorySlug }}" 
                     data-name="{{ $carNameSafe }}">
                    
                    <div>
                        <span class="absolute top-4 md:top-5 left-4 md:left-5 bg-white/40 text-[#1C2C4A] px-2 py-1 rounded-md text-[9px] md:text-[10px] font-bold uppercase tracking-wider backdrop-blur-sm z-20">{{ trim($car->category) }}</span>
                        
                        <img src="{{ asset($car->image_path) }}" alt="{{ $car->name }}" class="w-full h-32 md:h-40 object-contain drop-shadow-xl mb-2 md:mb-4 z-10 relative">
                        <div class="flex justify-between items-end mb-3 md:mb-4">
                            <div class="w-2/3 pr-2">
                                <h3 class="text-[11px] md:text-xs font-bold text-gray-800 uppercase mb-1 truncate">{{ $car->name }}</h3>
                                <div class="text-lg md:text-xl font-extrabold text-black">Rp {{ number_format($car->price_per_day, 0, ',', '.') }} <span class="text-[10px] md:text-xs font-normal text-gray-700">/day</span></div>
                                <p class="text-[9px] md:text-[10px] text-gray-700 mt-1 truncate">Seats for {{ $car->seats }} • {{ $car->transmission }} • {{ $car->fuel_type }}</p>
                            </div>
                            <img src="{{ asset($car->image_path) }}" alt="Mini" class="w-14 md:w-16 h-8 md:h-10 object-contain drop-shadow-md flex-shrink-0 opacity-80">
                        </div>
                    </div>
                    
                    <a href="{{ route('login') }}" class="w-full py-2 md:py-2.5 rounded-full bg-white/30 border border-white/40 text-white font-bold shadow-sm hover:bg-white/50 transition backdrop-blur-sm drop-shadow-md text-center inline-block text-xs md:text-sm mt-2">
                        Book Now
                    </a>
                </div>
                @endforeach
            @else
                <div class="col-span-1 md:col-span-3 text-center text-gray-500 py-10 bg-[#E2EAF6] rounded-2xl">
                    <i class="fa-solid fa-car-side text-4xl mb-3 opacity-50"></i>
                    <p>Mobil belum tersedia di katalog.</p>
                </div>
            @endif

        </div>

        <div id="noResultMsg" class="hidden text-center text-gray-500 py-10 bg-[#E2EAF6] rounded-2xl w-full mt-4">
            <i class="fa-solid fa-magnifying-glass text-4xl mb-3 opacity-50"></i>
            <p>Mobil yang Anda cari tidak ditemukan.</p>
        </div>
    </div>

    <footer class="bg-gradient-to-t from-[#5978B4] to-[#A9BFE4] text-white py-10 md:py-12 mt-auto">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between items-start gap-8 md:gap-0">
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
                <ul class="space-y-4 text-xs md:text-sm font-medium">
                    <li class="flex items-start justify-center md:justify-start gap-3">
                        <i class="fa-solid fa-location-dot mt-1 shrink-0"></i>
                        <span>Jl. Prof. Dr. Ir. Sumantri Brojonegoro No.1, Gedong Meneng, Kec. Rajabasa,<br>Kota Bandar Lampung, Lampung 35141</span>
                    </li>
                    <li class="flex items-center justify-center md:justify-start gap-3">
                        <i class="fa-solid fa-phone shrink-0"></i>
                        <span>+6285278139801</span>
                    </li>
                    <li class="flex items-center justify-center md:justify-start gap-3">
                        <i class="fa-solid fa-envelope shrink-0"></i>
                        <span>tripzy@gmail.com</span>
                    </li>
                    <li class="flex items-center justify-center md:justify-start gap-3">
                        <i class="fa-regular fa-clock shrink-0"></i>
                        <span>Senin - Minggu<br>24 Jam</span>
                    </li>
                </ul>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const filterBtns = document.querySelectorAll('.filter-btn');
            const carCards = document.querySelectorAll('.car-card');
            const searchInput = document.getElementById('searchInput');
            const noResultMsg = document.getElementById('noResultMsg');

            let activeCategory = 'all';
            let searchQuery = '';

            function filterCars() {
                let visibleCount = 0;

                carCards.forEach(card => {
                    const carCategory = card.getAttribute('data-category');
                    const carName = card.getAttribute('data-name');
                    
                    // KOMPARASI MUTLAK === (mpv TIDAK AKAN SAMA DENGAN luxury-mpv)
                    const matchCategory = (activeCategory === 'all') || (carCategory === activeCategory);
                    const matchSearch = carName.includes(searchQuery);

                    if (matchCategory && matchSearch) {
                        card.style.display = 'flex';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                if (visibleCount === 0 && carCards.length > 0) {
                    noResultMsg.classList.remove('hidden');
                } else {
                    noResultMsg.classList.add('hidden');
                }
            }

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    filterBtns.forEach(b => {
                        b.classList.remove('active', 'bg-white', 'text-[#4A6EB0]', 'font-semibold', 'shadow-sm');
                    });
                    
                    this.classList.add('active', 'bg-white', 'text-[#4A6EB0]', 'font-semibold', 'shadow-sm');
                    
                    activeCategory = this.getAttribute('data-filter');
                    filterCars(); // Panggil fungsi filter ulang
                });
            });

            if(searchInput) {
                searchInput.addEventListener('input', function(e) {
                    searchQuery = e.target.value.toLowerCase().trim();
                    filterCars();
                });
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