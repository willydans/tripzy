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
        
        /* Sembunyikan scrollbar untuk navigasi mobile */
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Animasi Card Gallery (Responsive) */
        .gallery-container {
            display: flex;
            flex-direction: column; /* Vertikal di HP */
            gap: 0.75rem;
            height: 75vh; /* Tinggi menyesuaikan layar HP */
            width: 100%;
            margin: 0 auto;
        }

        @media (min-width: 768px) {
            .gallery-container {
                flex-direction: row; /* Horizontal di Laptop */
                gap: 1rem;
                height: 550px;
            }
        }

        .dest-card {
            position: relative;
            flex: 1; /* Awalnya bagi rata semua */
            border-radius: 1.5rem;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.6s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .dest-card.active { flex: 6; } /* Pas diklik membesar */

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
            white-space: nowrap;
        }

        @media (min-width: 768px) {
            .text-vertical {
                writing-mode: vertical-rl;
                transform: rotate(180deg);
                text-orientation: mixed;
            }
        }

        .content-unexpanded { transition: opacity 0.4s ease, transform 0.4s ease; }
        .content-expanded {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.4s ease 0.2s, transform 0.4s ease 0.2s;
            pointer-events: none;
        }

        /* Animasi Transisi Unexpanded */
        .dest-card.active .content-unexpanded { opacity: 0; pointer-events: none; }
        @media (max-width: 767px) {
            .dest-card.active .content-unexpanded { transform: translateX(-20px); }
        }
        @media (min-width: 768px) {
            .dest-card.active .content-unexpanded { transform: translateY(-20px); }
        }

        .dest-card.active .content-expanded { opacity: 1; transform: translateY(0); pointer-events: auto; }
    </style>
</head>
<body class="flex flex-col min-h-screen">

    <nav class="pt-6 px-4 md:px-8 flex flex-col md:flex-row justify-between items-center gap-4 md:gap-0 mb-8 md:mb-12 max-w-7xl mx-auto w-full relative z-50">
        
        <div class="flex justify-between items-center w-full md:w-auto md:order-2">
            <h1 class="text-3xl font-bebas tracking-widest md:hidden text-[#7A9FE0]">TRIPZY</h1>
            
            <div class="flex space-x-4 items-center">
                <a href="{{ route('user.profile') }}" class="w-10 h-10 rounded-full nav-pill flex items-center justify-center hover:bg-white/20 transition text-white overflow-hidden relative border border-white/20">
                    @if(Auth::check() && Auth::user()->profile_photo)
                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="w-full h-full object-cover absolute inset-0">
                    @else
                        <i class="fa-solid fa-user text-lg"></i>
                    @endif
                </a>
                
                <div class="relative inline-block text-left">
                    @php
                        $now = \Carbon\Carbon::now();
                        $activeBookings = \App\Models\Booking::with('car')
                                            ->where('user_id', Auth::id())
                                            ->whereNotIn('status', ['Cancelled'])
                                            ->orderBy('created_at', 'desc')
                                            ->get();

                        $notifications = collect();

                        foreach($activeBookings as $b) {
                            $pickupTime = $b->pickup_time ?? '00:00:00';
                            $start = \Carbon\Carbon::parse($b->start_date)->format('Y-m-d');
                            $end = \Carbon\Carbon::parse($b->end_date)->format('Y-m-d');

                            $pickupDateTime = \Carbon\Carbon::parse($start . ' ' . $pickupTime);
                            $returnDateTime = \Carbon\Carbon::parse($end . ' ' . $pickupTime);

                            if (in_array($b->status, ['Ongoing', 'Paid'])) {
                                if ($now->greaterThan($returnDateTime)) {
                                    $daysLate = $now->diffInDays($returnDateTime);
                                    if ($daysLate > 0) {
                                        $notifications->push([
                                            'title' => 'TELAT MENGEMBALIKAN',
                                            'msg' => "Booking {$b->booking_code}: Anda telat mengembalikan mobil {$b->car->name} selama {$daysLate} hari. Segera kembalikan!",
                                            'color' => 'bg-red-500'
                                        ]);
                                    } else {
                                        $notifications->push([
                                            'title' => 'WAKTU HABIS',
                                            'msg' => "Booking {$b->booking_code}: Durasi rental {$b->car->name} habis hari ini. Silahkan kembalikan tepat waktu.",
                                            'color' => 'bg-orange-400'
                                        ]);
                                    }
                                } elseif ($now->greaterThanOrEqualTo($pickupDateTime) && $now->lessThan($returnDateTime)) {
                                    $notifications->push([
                                        'title' => 'WAKTU PENGAMBILAN',
                                        'msg' => "Booking {$b->booking_code}: Waktu rental dimulai! Silahkan ambil mobil {$b->car->name} di perental sekarang.",
                                        'color' => 'bg-green-400'
                                    ]);
                                } else {
                                    $daysToPickup = $now->diffInDays($pickupDateTime);
                                    $notifications->push([
                                        'title' => 'BOOKING BERHASIL',
                                        'msg' => "Booking {$b->booking_code}: Pemesanan {$b->car->name} berhasil. Jadwal ambil mobil {$daysToPickup} hari lagi.",
                                        'color' => 'bg-white'
                                    ]);
                                }
                            } elseif ($b->status == 'Pending Payment') {
                                $notifications->push([
                                    'title' => 'MENUNGGU PEMBAYARAN',
                                    'msg' => "Booking {$b->booking_code} menunggu pembayaran. Segera selesaikan dengan metode QRIS.",
                                    'color' => 'bg-blue-400'
                                ]);
                            }
                        }
                    @endphp

                    <button onclick="toggleNotif()" id="bellButton" class="w-10 h-10 rounded-full nav-pill flex items-center justify-center hover:bg-white/20 transition text-white relative z-50 cursor-pointer">
                        <i class="fa-solid fa-bell text-lg"></i>
                        @if($notifications->count() > 0)
                            <span class="absolute top-0 right-0 w-3 h-3 bg-red-500 rounded-full border-2 border-[#1A1F36]"></span>
                        @endif
                    </button>

                    <div id="notifPanel" class="hidden absolute right-0 mt-4 w-[85vw] max-w-xs sm:w-80 bg-[#7A8CA5] rounded-2xl shadow-2xl z-40 transform transition-all duration-300 opacity-0 scale-95 origin-top-right border border-white/10">
                        <div class="absolute -top-2 right-4 w-5 h-5 bg-[#7A8CA5] transform rotate-45 rounded-sm border-t border-l border-white/10"></div>
                        <div class="relative z-10 p-5 md:p-6">
                            <h3 class="text-white font-bebas tracking-widest text-lg mb-4 uppercase">NOTIFICATION</h3>
                            <div class="space-y-4 max-h-64 overflow-y-auto pr-2" style="scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.5) transparent;">
                                @forelse($notifications->take(5) as $notif)
                                    <div class="flex items-start gap-3 md:gap-4">
                                        <div class="w-3 h-3 {{ $notif['color'] }} rounded-full mt-1.5 flex-shrink-0 shadow-sm border border-white/20"></div>
                                        <div>
                                            <h4 class="text-white text-sm font-bold tracking-wider uppercase drop-shadow-sm">{{ $notif['title'] }}</h4>
                                            <p class="text-gray-100 text-[11px] md:text-xs mt-1 leading-relaxed">{{ $notif['msg'] }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4">
                                        <i class="fa-regular fa-bell-slash text-2xl text-white/50 mb-2"></i>
                                        <p class="text-gray-200 text-xs italic">Belum ada notifikasi rental.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="nav-pill w-full md:w-auto overflow-x-auto hide-scroll rounded-full px-4 md:px-6 py-3 flex space-x-4 md:space-x-6 text-xs md:text-sm font-bold tracking-wider uppercase text-white md:order-1 whitespace-nowrap">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-300 transition">Home</a>
            <a href="{{ route('user.catalog') }}" class="hover:text-blue-300 transition">Catalog</a>
            <a href="{{ route('user.destination') }}" class="text-blue-300 transition border-b-2 border-blue-300 pb-1">Destination</a>
            <a href="{{ route('user.orders') }}" class="hover:text-blue-300 transition">Orders</a>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto px-4 lg:px-8 w-full mb-24 relative z-10">
        <div class="text-center mb-8 md:mb-12">
            <p class="text-xs md:text-sm font-bold tracking-widest uppercase text-gray-300 mb-2">RECOMENDATION DESTINATION</p>
            <h1 class="text-4xl md:text-6xl font-bebas tracking-widest uppercase leading-tight">TOP TRAVEL SPOTS WORTH VISITING</h1>
        </div>

        @php
            $destinations = [
                ['id' => '01', 'title' => 'PAHAWANG ISLAND', 'img' => 'https://heartline.co.id/wp-content/uploads/2025/01/Pulau-Pahawang-Sebuah-Permata-Tersembunyi-Di-Lampung-05.jpg', 'desc' => 'Pahawang Island offers a truly unique experience with its crystal clear water and beautiful coral reefs. Early in the morning, visitors can witness the beauty of marine life.'],
                ['id' => '02', 'title' => 'WAY KAMBAS', 'img' => 'https://akcdn.detik.net.id/community/media/visual/2024/01/29/gajah-sumatera-di-taman-nasional-way-kambas_169.jpeg?w=700&q=90', 'desc' => 'A famous national park known for its elephant conservation center. Experience the wildlife and natural habitat of Sumatran elephants.'],
                ['id' => '03', 'title' => 'KILLUAN BAY', 'img' => 'https://ik.imagekit.io/tvlk/blog/2024/07/shutterstock_492879184.jpg?tr=q-70,c-at_max,w-1000,h-600', 'desc' => 'Killuan Bay offers a truly unique experience with its famous dolphin-watching tours. Early in the morning, visitors can witness pods of dolphins swimming freely in the open sea.'],
                ['id' => '04', 'title' => 'GIGI HIU BEACH', 'img' => 'https://www.batiqa.com/upload/news/z/lampung-pantai-gigi-hiu_3mnwt.jpg', 'desc' => 'Known as the "Shark Teeth Beach" due to its sharp and majestic rock formations standing tall against the strong ocean waves.'],
                ['id' => '05', 'title' => 'SEBESI ISLAND', 'img' => 'https://s-light.tiket.photos/t/01E25EBZS3W0FY9GTG6C42E1SE/rsfit1440960gsm/events/2024/01/18/f0f500bb-fea9-4e4b-9443-88a0ea9ad65d-1705550023217-4f1de8e38bb54e954f45ac776c75d4b5.png', 'desc' => 'The closest inhabited island to Mount Krakatoa. A perfect spot for hiking, snorkeling, and witnessing the legendary volcano.'],
                ['id' => '06', 'title' => 'GANGSA WATERFALL', 'img' => 'https://wisato.id/wp-content/uploads/2019/11/Curup-Gangsa-@igustimade.wira_.jpg', 'desc' => 'A hidden gem in Lampung featuring a majestic waterfall surrounded by dense tropical rainforest.'],
            ];
        @endphp

        <div class="gallery-container">
            @foreach($destinations as $index => $dest)
                <div class="dest-card {{ $index == 0 ? 'active' : '' }}" onclick="toggleCard(this)">
                    <img src="{{ $dest['img'] }}" class="absolute inset-0 w-full h-full object-cover">
                    <div class="overlay-unexpanded"></div>
                    <div class="overlay-expanded"></div>
                    
                    <div class="content-unexpanded absolute inset-0 flex flex-row md:flex-col items-center justify-between py-4 px-6 md:py-10 md:px-0">
                        <div class="hidden md:block w-[2px] h-16 bg-white/70"></div>
                        <h3 class="text-vertical font-bebas text-xl md:text-3xl tracking-widest text-white drop-shadow-md flex-grow flex items-center justify-center md:my-4">{{ $dest['title'] }}</h3>
                        <h2 class="font-bebas text-2xl md:text-4xl text-white drop-shadow-md">{{ $dest['id'] }}</h2>
                    </div>
                    
                    <div class="content-expanded absolute inset-x-0 bottom-0 p-5 md:p-8 flex flex-col justify-end h-full">
                        <h2 class="font-bebas text-3xl md:text-4xl tracking-widest text-white mb-2 drop-shadow-lg">{{ $dest['title'] }}</h2>
                        <p class="text-[11px] md:text-xs text-gray-200 leading-relaxed font-medium drop-shadow-md max-w-xl line-clamp-3 md:line-clamp-4">{{ $dest['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </main>

    <footer class="mt-auto border-t border-[#323A56] pt-10 pb-8 bg-[#171B2D]">
        <div class="max-w-7xl mx-auto px-4 lg:px-8 flex flex-col md:flex-row justify-between items-start gap-8 md:gap-0">
            <div class="w-full md:w-auto">
                <h2 class="text-4xl md:text-5xl font-bold font-bebas tracking-widest text-[#7A9FE0] mb-4">Tripzy</h2>
                <div class="flex space-x-4 text-2xl text-gray-400">
                    <i class="fa-brands fa-discord hover:text-white cursor-pointer transition"></i>
                    <i class="fa-brands fa-whatsapp hover:text-white cursor-pointer transition"></i>
                    <i class="fa-brands fa-telegram hover:text-white cursor-pointer transition"></i>
                    <i class="fa-brands fa-instagram hover:text-white cursor-pointer transition"></i>
                </div>
            </div>
            <div class="w-full md:w-1/3">
                <h3 class="text-xl font-bold text-[#7A9FE0] mb-4">Contact Us</h3>
                <ul class="space-y-4 text-xs font-medium text-gray-400">
                    <li class="flex items-start gap-3"><i class="fa-solid fa-location-dot mt-1 text-[#7A9FE0] shrink-0"></i><span>Jl. Prof. Dr. Ir. Sumantri Brojonegoro No.1, Gedong Meneng, Kec. Rajabasa, Bandar Lampung 35141</span></li>
                    <li class="flex items-center gap-3"><i class="fa-solid fa-phone text-[#7A9FE0] shrink-0"></i><span>+6285278139801</span></li>
                    <li class="flex items-center gap-3"><i class="fa-solid fa-envelope text-[#7A9FE0] shrink-0"></i><span>tripzy@gmail.com</span></li>
                    <li class="flex items-start gap-3"><i class="fa-solid fa-clock mt-1 text-[#7A9FE0] shrink-0"></i><span>Senin - Minggu<br>24 Jam</span></li>
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

        // Script Gallery Accordion
        function toggleCard(clickedCard) {
            const isActive = clickedCard.classList.contains('active');
            // Hapus class active dari semua card
            document.querySelectorAll('.dest-card').forEach(card => card.classList.remove('active'));
            // Tambahkan class active ke card yang diklik (jika sebelumnya tidak aktif)
            if (!isActive) clickedCard.classList.add('active');
        }
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