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
        body { font-family: 'Poppins', sans-serif; background-color: #F4F7FC; }
        
        /* Gradient Hero lebih pendek dari Home */
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
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 100;
            width: 80%; /* Lebarnya menyesuaikan */
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        /* Filter Active State */
        .filter-btn.active {
            background-color: white;
            color: #4A6EB0;
            font-weight: 600;
            padding: 0.25rem 1rem;
            border-radius: 9999px;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="text-gray-800 overflow-x-hidden">

    <div class="hero-gradient pb-20 flex flex-col relative overflow-hidden">
        
        <nav class="glass-nav rounded-full px-8 py-4 flex justify-between items-center text-white transition-all">
            <h1 class="text-2xl font-bold italic tracking-wider"><a href="{{ route('home') }}">Tripzy</a></h1>
            
            <div class="hidden md:flex space-x-8 font-medium">
                <a href="{{ route('home') }}" class="hover:text-blue-200 transition drop-shadow-md">Home</a>
                <a href="{{ route('catalog') }}" class="text-blue-200 border-b-2 border-blue-200 pb-1 hover:text-white transition">Catalog</a>
                <a href="{{ route('contact') }}" class="hover:text-blue-200 transition">Contact</a>
                <a href="{{ route('destination') }}" class="hover:text-blue-200 transition">Destination</a>
            </div>

            <a href="{{ route('login') }}" class="bg-gray-900 bg-opacity-40 hover:bg-opacity-60 border border-gray-500 px-6 py-2 rounded-full font-medium flex items-center gap-2 transition text-white">
                Sign In <i class="fa-solid fa-arrow-up-right-from-square text-sm"></i>
            </a>
        </nav>

        <div class="relative z-10 flex-grow flex flex-col justify-center items-center text-center px-4 mt-32">
            <p class="text-white text-sm font-bold tracking-widest uppercase mb-2">Our Collection</p>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-6 tracking-wide drop-shadow-md uppercase">
                Luxury Car Selection
            </h1>
            <p class="text-white text-sm md:text-base max-w-3xl mb-8 opacity-90 drop-shadow">
                Our premium fleet offers a range of high-quality vehicles designed to provide a perfect balance of comfort, reliability, and performance, making every journey feel more exclusive and enjoyable.
            </p>
            <a href="#catalog-section" class="glass-nav text-white px-8 py-2.5 rounded-full hover:bg-white hover:text-blue-600 transition flex items-center gap-2" style="position: relative; top: 0; left: 0; transform: none; width: auto;">
                Explore Catalog <i class="fa-solid fa-arrow-down"></i>
            </a>
        </div>
    </div>

    <div id="catalog-section" class="max-w-7xl mx-auto px-4 mt-2 mb-10 flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="relative w-full md:w-80">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 transform -translate-y-1/2 text-[#8CA1C4]"></i>
            <input type="text" id="searchInput" placeholder="Find Your Perfect Car..." class="w-full bg-[#E2EAF6] text-sm rounded-full py-2.5 pl-10 pr-4 focus:outline-none text-[#5A7CB8] placeholder-[#8CA1C4] shadow-inner">
        </div>
        
        <div class="flex flex-wrap justify-center gap-4 md:gap-6 text-sm font-medium text-[#7C98C4]" id="filterContainer">
            <button class="filter-btn active hover:text-[#4A6EB0] transition" data-filter="all">All</button>
            @php
                // Ambil daftar kategori unik dari mobil yang ada
                $categories = $cars->pluck('category')->unique();
            @endphp
            @foreach($categories as $category)
                <button class="filter-btn hover:text-[#4A6EB0] transition" data-filter="{{ strtolower($category) }}">{{ $category }}</button>
            @endforeach
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 pb-24">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="carGrid">
            
            @if(isset($cars) && $cars->count() > 0)
                @foreach($cars as $car)
                <div class="car-card bg-gradient-to-b from-[#8C9FBB] to-[#A9BBD6] rounded-2xl p-6 shadow-md border border-white/20 flex flex-col justify-between transition transform hover:-translate-y-1 hover:shadow-xl" 
                     data-category="{{ strtolower($car->category) }}" 
                     data-name="{{ strtolower($car->name) }}">
                    
                    <div>
                        <span class="absolute top-4 left-4 bg-white/40 text-[#1C2C4A] px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider backdrop-blur-sm z-20">{{ $car->category }}</span>
                        
                        <img src="{{ asset($car->image_path) }}" alt="{{ $car->name }}" class="w-full h-40 object-contain drop-shadow-xl mb-2 z-10 relative">
                        <div class="flex justify-between items-end mb-4">
                            <div class="w-full">
                                <h3 class="text-xs font-bold text-gray-800 uppercase mb-1 truncate">{{ $car->name }}</h3>
                                <div class="text-xl font-extrabold text-black">Rp {{ number_format($car->price_per_day, 0, ',', '.') }} <span class="text-xs font-normal text-gray-700">/day</span></div>
                                <p class="text-[10px] text-gray-700 mt-1 truncate">Seats for {{ $car->seats }} passengers • {{ $car->transmission }} • {{ $car->fuel_type }}</p>
                            </div>
                            <img src="{{ asset($car->image_path) }}" alt="Mini" class="w-16 h-10 object-contain drop-shadow-md ml-2 flex-shrink-0">
                        </div>
                    </div>
                    
                    <a href="{{ route('login') }}" class="w-full py-2 rounded-full bg-white/30 border border-white/40 text-white font-bold shadow-sm hover:bg-white/50 transition backdrop-blur-sm drop-shadow-md text-center inline-block text-sm">
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

        <div id="noResultMsg" class="hidden text-center text-gray-500 py-10 bg-[#E2EAF6] rounded-2xl w-full">
            <i class="fa-solid fa-magnifying-glass text-4xl mb-3 opacity-50"></i>
            <p>Mobil yang Anda cari tidak ditemukan.</p>
        </div>
    </div>

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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const filterBtns = document.querySelectorAll('.filter-btn');
            const carCards = document.querySelectorAll('.car-card');
            const searchInput = document.getElementById('searchInput');
            const noResultMsg = document.getElementById('noResultMsg');

            let activeCategory = 'all';
            let searchQuery = '';

            // Fungsi untuk memfilter mobil
            function filterCars() {
                let visibleCount = 0;

                carCards.forEach(card => {
                    const carCategory = card.getAttribute('data-category');
                    const carName = card.getAttribute('data-name');
                    
                    // Cek cocok kategori?
                    const matchCategory = (activeCategory === 'all') || (carCategory === activeCategory);
                    // Cek cocok pencarian nama?
                    const matchSearch = carName.includes(searchQuery);

                    if (matchCategory && matchSearch) {
                        card.style.display = 'flex';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Tampilkan pesan jika tidak ada mobil yang cocok
                if (visibleCount === 0 && carCards.length > 0) {
                    noResultMsg.classList.remove('hidden');
                } else {
                    noResultMsg.classList.add('hidden');
                }
            }

            // Event Listener untuk Tombol Kategori
            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Hapus class active dari semua tombol
                    filterBtns.forEach(b => {
                        b.classList.remove('active', 'bg-white', 'text-[#4A6EB0]', 'font-semibold', 'px-4', 'py-1', 'rounded-full', 'shadow-sm');
                    });
                    
                    // Tambah class active ke tombol yang diklik
                    this.classList.add('active', 'bg-white', 'text-[#4A6EB0]', 'font-semibold', 'px-4', 'py-1', 'rounded-full', 'shadow-sm');
                    
                    activeCategory = this.getAttribute('data-filter');
                    filterCars();
                });
            });

            // Event Listener untuk Search Input
            searchInput.addEventListener('input', function(e) {
                searchQuery = e.target.value.toLowerCase();
                filterCars();
            });
        });
    </script>
</body>
</html>