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
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #1A1F36; /* Dark Navy Background */
            color: white;
            overflow-x: hidden;
        }

        .nav-pill { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
        
        /* Animasi Card Gallery */
        .gallery-container {
            display: flex;
            gap: 1rem;
            height: 550px;
            width: 100%;
            max-w: 7xl;
            margin: 0 auto;
        }

        .dest-card {
            position: relative;
            flex: 1; /* Awalnya kecil semua */
            border-radius: 1.5rem;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.6s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .dest-card.active { flex: 5; }

        .overlay-unexpanded {
            position: absolute; inset: 0;
            background: linear-gradient(to bottom, rgba(154, 177, 214, 0.6), rgba(26, 31, 54, 0.8));
            transition: opacity 0.5s ease;
        }
        
        .overlay-expanded {
            position: absolute; inset: 0;
            background: linear-gradient(to top, rgba(26, 31, 54, 0.9) 0%, transparent 50%);
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .dest-card.active .overlay-unexpanded { opacity: 0; }
        .dest-card.active .overlay-expanded { opacity: 1; }

        .text-vertical {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            text-orientation: mixed;
            white-space: nowrap;
        }

        .content-unexpanded { transition: opacity 0.4s ease, transform 0.4s ease; }
        .content-expanded {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.4s ease 0.2s, transform 0.4s ease 0.2s;
            pointer-events: none;
        }

        .dest-card.active .content-unexpanded { opacity: 0; transform: translateY(-20px); pointer-events: none; }
        .dest-card.active .content-expanded { opacity: 1; transform: translateY(0); pointer-events: auto; }
    </style>
</head>
<body class="flex flex-col min-h-screen">

    <!-- NAVBAR (Z-50 biar notif ga ketutup card) -->
    <nav class="pt-6 px-8 flex justify-between items-center mb-12 max-w-7xl mx-auto w-full relative z-50">
        <div class="nav-pill rounded-full px-6 py-3 flex space-x-6 text-sm font-bold tracking-wider uppercase text-white">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-300 transition">Home</a>
            <a href="{{ route('user.catalog') }}" class="hover:text-blue-300 transition">Catalog</a>
            <a href="{{ route('user.destination') }}" class="text-blue-300 transition border-b-2 border-blue-300 pb-1">Destination</a>
            <a href="{{ route('user.orders') }}" class="hover:text-blue-300 transition">Orders</a>
        </div>

        <div class="flex space-x-4 items-center">
            <!-- Icon User Dinamis -->
            <a href="{{ route('user.profile') }}" class="w-10 h-10 rounded-full nav-pill flex items-center justify-center hover:bg-white/20 transition text-white overflow-hidden relative border border-white/20">
                @if(Auth::check() && Auth::user()->profile_photo)
                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="w-full h-full object-cover absolute inset-0">
                @else
                    <i class="fa-solid fa-user text-lg"></i>
                @endif
            </a>

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

    <!-- MAIN CONTENT -->
    <main class="flex-grow max-w-7xl mx-auto px-4 lg:px-8 w-full mb-24 relative z-10">
        <div class="text-center mb-12">
            <p class="text-sm font-bold tracking-widest uppercase text-gray-300 mb-2">RECOMENDATION DESTINATION</p>
            <h1 class="text-6xl font-bebas tracking-widest uppercase">TOP TRAVEL SPOTS WORTH VISITING</h1>
        </div>

        @php
            $destinations = [
                ['id' => '01', 'title' => 'PAHAWANG ISLAND', 'img' => 'https://images.unsplash.com/photo-1596484552834-6a58f850e0a1?q=80&w=1000&auto=format&fit=crop', 'desc' => 'Pahawang Island offers a truly unique experience with its crystal clear water and beautiful coral reefs. Early in the morning, visitors can witness the beauty of marine life.'],
                ['id' => '02', 'title' => 'WAY KAMBAS', 'img' => 'https://images.unsplash.com/photo-1557050543-4d5f4e07ef46?q=80&w=1000&auto=format&fit=crop', 'desc' => 'A famous national park known for its elephant conservation center. Experience the wildlife and natural habitat of Sumatran elephants.'],
                ['id' => '03', 'title' => 'KILLUAN BAY', 'img' => 'https://images.unsplash.com/photo-1559128010-7c1ad6e1b6a5?q=80&w=1000&auto=format&fit=crop', 'desc' => 'Killuan Bay offers a truly unique experience with its famous dolphin-watching tours. Early in the morning, visitors can witness pods of dolphins swimming freely in the open sea.'],
                ['id' => '04', 'title' => 'GIGI HIU BEACH', 'img' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=1000&auto=format&fit=crop', 'desc' => 'Known as the "Shark Teeth Beach" due to its sharp and majestic rock formations standing tall against the strong ocean waves.'],
                ['id' => '05', 'title' => 'SEBESI ISLAND', 'img' => 'https://images.unsplash.com/photo-1544376798-89aa6b82c6cd?q=80&w=1000&auto=format&fit=crop', 'desc' => 'The closest inhabited island to Mount Krakatoa. A perfect spot for hiking, snorkeling, and witnessing the legendary volcano.'],
                ['id' => '06', 'title' => 'GANGSA WATERFALL', 'img' => 'https://images.unsplash.com/photo-1433086966358-54859d0ed716?q=80&w=1000&auto=format&fit=crop', 'desc' => 'A hidden gem in Lampung featuring a majestic waterfall surrounded by dense tropical rainforest.'],
            ];
        @endphp

        <div class="gallery-container">
            @foreach($destinations as $dest)
                <div class="dest-card" onclick="toggleCard(this)">
                    <img src="{{ $dest['img'] }}" class="absolute inset-0 w-full h-full object-cover">
                    <div class="overlay-unexpanded"></div>
                    <div class="overlay-expanded"></div>
                    <div class="content-unexpanded absolute inset-0 flex flex-col items-center justify-between py-10">
                        <div class="w-[2px] h-16 bg-white/70"></div>
                        <h3 class="text-vertical font-bebas text-3xl tracking-widest text-white drop-shadow-md flex-grow flex items-center justify-center my-4">{{ $dest['title'] }}</h3>
                        <h2 class="font-bebas text-4xl text-white drop-shadow-md">{{ $dest['id'] }}</h2>
                    </div>
                    <div class="content-expanded absolute inset-x-0 bottom-0 p-8 flex flex-col justify-end h-full">
                        <h2 class="font-bebas text-4xl tracking-widest text-white mb-2 drop-shadow-lg">{{ $dest['title'] }}</h2>
                        <p class="text-xs text-gray-200 leading-relaxed font-medium drop-shadow-md max-w-xl line-clamp-4">{{ $dest['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="mt-auto border-t border-gray-600 pt-10 pb-8 bg-[#1A1F36]">
        <div class="max-w-7xl mx-auto px-4 lg:px-8 flex flex-col md:flex-row justify-between items-start">
            <div class="mb-8 md:mb-0"><h2 class="text-5xl font-bold font-bebas tracking-widest text-[#7A9FE0] mb-4">Tripzy</h2></div>
            <div class="w-full md:w-1/3">
                <h3 class="text-xl font-bold text-[#7A9FE0] mb-4">Contact Us</h3>
                <ul class="space-y-4 text-xs font-medium text-gray-400">
                    <li class="flex items-start gap-3"><i class="fa-solid fa-location-dot mt-1 text-[#7A9FE0]"></i><span>Jl. Prof. Dr. Ir. Sumantri Brojonegoro No.1, Gedong Meneng, Kec. Rajabasa, Bandar Lampung 35141</span></li>
                </ul>
            </div>
        </div>
    </footer>

    <script>
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

        function toggleCard(clickedCard) {
            const isActive = clickedCard.classList.contains('active');
            document.querySelectorAll('.dest-card').forEach(card => card.classList.remove('active'));
            if (!isActive) clickedCard.classList.add('active');
        }
    </script>
</body>
</html>