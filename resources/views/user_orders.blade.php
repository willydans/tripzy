<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Tripzy</title>
    
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

        .nav-pill {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .tab-btn { transition: all 0.3s ease; }
        .tab-active { background: rgba(255, 255, 255, 0.1); border-color: rgba(255,255,255,0.3); }
        
        .text-outline {
            color: transparent;
            -webkit-text-stroke: 2px white;
            font-family: 'Bebas Neue', cursive;
            letter-spacing: 2px;
        }

        .modal-card { background-color: #7183A6; } 
        
        /* Sembunyikan scrollbar untuk menu navigasi di mobile */
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
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
            <a href="{{ route('user.destination') }}" class="hover:text-blue-300 transition">Destination</a>
            <a href="{{ route('user.orders') }}" class="text-blue-300 transition border-b-2 border-blue-300 pb-1">Orders</a>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto px-4 lg:px-8 w-full mb-20 relative z-10">
        
        <h1 class="text-4xl md:text-5xl font-bold font-bebas tracking-wide uppercase mb-2">MY BOOKINGS</h1>
        <p class="text-gray-300 text-xs md:text-sm mb-8 md:mb-10">Easily access your booking details and status anytime.</p>

        @if($bookings->isEmpty())
            <div class="flex flex-col items-center justify-center mt-20 mb-20 text-center px-4">
                <h2 class="text-2xl md:text-4xl font-bebas tracking-widest uppercase mb-2 md:mb-4">START YOUR JOURNEY BY</h2>
                <h1 class="text-4xl md:text-7xl text-outline uppercase tracking-widest leading-tight">BOOKING YOUR FIRST TRIP</h1>
            </div>
        @else
            <div class="flex flex-wrap gap-2 md:gap-4 mb-8">
                <button onclick="filterTabs('Pending Payment')" class="tab-btn tab-active border border-gray-600 rounded-full px-5 py-2 md:px-8 md:py-2.5 text-sm md:text-lg tracking-wide hover:bg-white/10">Pending Payment</button>
                <button onclick="filterTabs('Ongoing')" class="tab-btn border border-gray-600 rounded-full px-5 py-2 md:px-8 md:py-2.5 text-sm md:text-lg tracking-wide hover:bg-white/10">Ongoing</button>
                <button onclick="filterTabs('History')" class="tab-btn border border-gray-600 rounded-full px-5 py-2 md:px-8 md:py-2.5 text-sm md:text-lg tracking-wide hover:bg-white/10">History</button>
                <button onclick="filterTabs('Cancelled')" class="tab-btn border border-gray-600 rounded-full px-5 py-2 md:px-8 md:py-2.5 text-sm md:text-lg tracking-wide hover:bg-white/10">Cancelled</button>
            </div>

            <div class="space-y-6" id="booking-container">
                @foreach($bookings as $booking)
                    @php
                        $tabCategory = $booking->status; 
                        if($booking->status == 'Ordered' || $booking->status == 'Ongoing') { $tabCategory = 'Ongoing'; }
                        if($booking->status == 'History') { $tabCategory = 'History'; }
                    @endphp

                    <div class="booking-card flex flex-col md:flex-row bg-[#6C7A9C] rounded-3xl overflow-hidden shadow-xl" data-status="{{ $tabCategory }}">
                        
                        <div class="bg-white w-full md:w-1/3 p-4 md:p-6 flex items-center justify-center border-b-4 md:border-b-0 md:border-r-4 border-[#1A1F36]">
                            <img src="{{ asset($booking->car->image_path) }}" class="h-32 md:h-auto max-w-full object-contain drop-shadow-xl" alt="{{ $booking->car->name }}">
                        </div>
                        
                        <div class="w-full md:w-2/3 p-5 md:p-8 flex flex-col justify-between relative">
                            
                            <div class="absolute top-4 right-4 md:top-6 md:right-8">
                                @if($booking->status == 'Pending Payment')
                                    <span class="border-2 border-gray-300 text-gray-200 px-3 py-1 md:px-4 md:py-1.5 rounded-full text-[10px] md:text-xs font-bold shadow-sm backdrop-blur-sm">Pending Payment</span>
                                @elseif($booking->status == 'Ordered' || $booking->status == 'Ongoing')
                                    <span class="border-2 border-gray-300 text-gray-200 px-3 py-1 md:px-4 md:py-1.5 rounded-full text-[10px] md:text-xs font-bold shadow-sm backdrop-blur-sm">Ongoing</span>
                                @elseif($booking->status == 'History')
                                    <span class="bg-[#4ADE80] text-white px-4 py-1 md:px-5 md:py-1.5 rounded-full text-[10px] md:text-xs font-bold shadow-sm">Completed</span>
                                @else
                                    <span class="bg-[#EF4444] text-white px-4 py-1 md:px-5 md:py-1.5 rounded-full text-[10px] md:text-xs font-bold shadow-sm">Cancelled</span>
                                @endif
                            </div>

                            <div class="mb-4 mt-8 md:mt-2">
                                <p class="text-gray-300 text-xs md:text-sm font-semibold mb-1">{{ $booking->booking_code }}</p>
                                <h2 class="text-3xl md:text-4xl font-bebas tracking-wide uppercase pr-16">{{ $booking->car->name }}</h2>
                                <p class="text-[11px] md:text-xs text-gray-300 mt-2 flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                                    <span><i class="fa-regular fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($booking->start_date)->translatedFormat('j M Y') }} - {{ \Carbon\Carbon::parse($booking->end_date)->translatedFormat('j M Y') }}</span>
                                    <span><i class="fa-solid fa-location-dot mr-1"></i> {{ $booking->duration ?? \Carbon\Carbon::parse($booking->start_date)->diffInDays($booking->end_date) + 1 }} Hari</span>
                                </p>
                            </div>

                            <hr class="border-gray-400/30 my-4">

                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 sm:gap-0">
                                <div>
                                    <h3 class="text-2xl md:text-3xl font-bold">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</h3>
                                    <p class="text-[10px] md:text-xs text-gray-300">Rp{{ number_format($booking->car->price_per_day, 0, ',', '.') }}/day</p>
                                </div>
                                
                                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                                    @if($booking->status == 'Pending Payment')
                                        <form action="{{ route('user.orders.cancel', $booking->id) }}" method="POST" class="w-full sm:w-auto">
                                            @csrf
                                            <button type="submit" class="w-full sm:w-auto bg-[#D34D4D] hover:bg-red-600 text-white px-6 py-2.5 sm:py-2 rounded-full font-bold transition shadow-md text-sm">Cancel</button>
                                        </form>
                                        
                                        <form action="{{ route('user.orders.pay', $booking->id) }}" method="POST" class="w-full sm:w-auto">
                                            @csrf
                                            <button type="submit" class="w-full sm:w-auto bg-[#2A344D] hover:bg-[#1A1F36] text-white px-6 py-2.5 sm:py-2 rounded-full font-bold transition shadow-md border border-gray-500 text-sm">
                                                Payment Now
                                            </button>
                                        </form>
                                    @else
                                        <button onclick="openDetailModal({{ json_encode($booking) }}, {{ json_encode($booking->car) }})" class="w-full sm:w-auto bg-[#2A344D] hover:bg-[#1A1F36] text-white px-8 py-2.5 sm:py-2 rounded-full font-bold transition shadow-md border border-gray-500 text-sm">Details</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                <div id="empty-tab-msg" class="hidden text-center text-gray-400 mt-12 mb-12">
                    <p class="text-lg md:text-xl">Tidak ada booking di kategori ini.</p>
                </div>
            </div>
        @endif

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
                    <li class="flex items-center gap-3"><i class="fa-solid fa-phone text-[#7A9FE0] shrink-0"></i><span>+6285276139801</span></li>
                    <li class="flex items-center gap-3"><i class="fa-solid fa-envelope text-[#7A9FE0] shrink-0"></i><span>tripzy@gmail.com</span></li>
                    <li class="flex items-start gap-3"><i class="fa-solid fa-clock mt-1 text-[#7A9FE0] shrink-0"></i><span>Senin - Minggu<br>24 Jam</span></li>
                </ul>
            </div>
        </div>
    </footer>

    @if(!$bookings->isEmpty())
    <div id="detailModal" class="fixed inset-0 z-[100] flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300 p-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeModal()"></div>
        <div class="modal-card rounded-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto relative shadow-2xl p-5 md:p-8 z-10 text-white">
            
            <div class="flex justify-between items-center mb-6 border-b border-white/20 pb-4">
                <h2 class="text-base md:text-lg font-bold tracking-widest uppercase flex items-center gap-2"><i class="fa-regular fa-file-lines"></i> DETAILS</h2>
                <button onclick="closeModal()" class="text-white hover:text-red-300 transition text-2xl"><i class="fa-solid fa-circle-xmark"></i></button>
            </div>

            <div class="flex flex-col md:flex-row gap-6 mb-6">
                <div class="bg-[#9AB1D6] rounded-2xl p-4 flex items-center justify-center w-full md:w-1/3 shadow-inner">
                    <img id="mdl_car_img" src="" class="max-w-full h-auto object-contain drop-shadow-lg">
                </div>
                <div class="w-full md:w-2/3">
                    <p id="mdl_car_category" class="text-xs md:text-sm font-semibold tracking-widest uppercase mb-1 text-gray-200"></p>
                    <h2 id="mdl_car_name" class="text-3xl md:text-4xl font-bebas tracking-widest uppercase mb-4 shadow-sm"></h2>
                    
                    <div id="mdl_specs_container" class="flex flex-wrap gap-2">
                        </div>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                <div class="bg-white/10 p-3 rounded-lg border border-white/20">
                    <p class="text-[10px] text-gray-300 uppercase tracking-wider mb-1">RENTAL DATE</p>
                    <p id="mdl_rental_date" class="font-bold text-xs md:text-sm uppercase"></p>
                </div>
                <div class="bg-white/10 p-3 rounded-lg border border-white/20">
                    <p class="text-[10px] text-gray-300 uppercase tracking-wider mb-1">DURATION</p>
                    <p id="mdl_duration" class="font-bold text-xs md:text-sm uppercase"></p>
                </div>
                <div class="bg-white/10 p-3 rounded-lg border border-white/20">
                    <p class="text-[10px] text-gray-300 uppercase tracking-wider mb-1">PLAT NUMBER</p>
                    <p id="mdl_plat" class="font-bold text-xs md:text-sm uppercase"></p>
                </div>
                <div class="bg-white/10 p-3 rounded-lg border border-white/20">
                    <p class="text-[10px] text-gray-300 uppercase tracking-wider mb-1">PICK UP TIME</p>
                    <p id="mdl_pickup" class="font-bold text-xs md:text-sm uppercase">10.00 AM</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-white/10 p-4 rounded-lg border border-white/20">
                    <p class="text-[10px] text-gray-300 uppercase tracking-wider mb-2">RENTER DETAILS</p>
                    <h4 id="mdl_renter_name" class="font-bold text-xs md:text-sm uppercase tracking-wide mb-1"></h4>
                    <p id="mdl_renter_phone" class="font-mono text-xs md:text-sm tracking-widest"></p>
                    <p id="mdl_renter_id" class="font-mono text-xs md:text-sm tracking-widest"></p>
                </div>
                <div class="bg-white/10 p-4 rounded-lg border border-white/20">
                    <p class="text-[10px] text-gray-300 uppercase tracking-wider mb-2">RENTER DOCUMENTS</p>
                    <div class="flex flex-col sm:flex-row gap-2">
                        <div id="doc_ktp" class="bg-white/80 text-black text-[10px] md:text-xs font-semibold py-1.5 px-3 rounded-md shadow flex-grow text-center truncate">ktp</div>
                        <div id="doc_sim" class="bg-white/80 text-black text-[10px] md:text-xs font-semibold py-1.5 px-3 rounded-md shadow flex-grow text-center truncate">sim</div>
                        <div id="doc_selfie" class="bg-white/80 text-black text-[10px] md:text-xs font-semibold py-1.5 px-3 rounded-md shadow flex-grow text-center truncate">selfie</div>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-white/20">
                <p class="text-[10px] md:text-xs font-bold text-gray-300 uppercase tracking-wider mb-2">COST SUMMARY</p>
                <div class="flex justify-between text-xs md:text-sm mb-1 font-semibold text-gray-200">
                    <span id="mdl_price_calc"></span>
                    <span id="mdl_price_subtotal"></span>
                </div>
                <div class="flex justify-between text-xs md:text-sm mb-3 font-semibold text-gray-200">
                    <span>BIAYA DRIVER (OPSIONAL)</span>
                    <span id="mdl_driver_cost">RP.0</span>
                </div>
                
                <div class="flex justify-between items-center pt-3 border-t border-white/10">
                    <span class="font-bold uppercase tracking-wider text-sm md:text-base">TOTAL AMOUNT</span>
                    <span id="mdl_total_amount" class="text-lg md:text-xl font-bold"></span>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <span class="font-bold uppercase tracking-wider text-xs md:text-sm">PAYMENT STATUS</span>
                    <span id="mdl_payment_status" class="font-bold uppercase tracking-wider text-xs md:text-sm"></span>
                </div>
            </div>

        </div>
    </div>
    @endif

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

        function filterTabs(status) {
            const buttons = document.querySelectorAll('.tab-btn');
            buttons.forEach(btn => {
                if(btn.innerText.trim() === status) {
                    btn.classList.add('tab-active');
                } else {
                    btn.classList.remove('tab-active');
                }
            });

            let visibleCount = 0;
            const cards = document.querySelectorAll('.booking-card');
            cards.forEach(card => {
                if(card.getAttribute('data-status') === status) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });

            const emptyMsg = document.getElementById('empty-tab-msg');
            if(visibleCount === 0) {
                emptyMsg.classList.remove('hidden');
            } else {
                emptyMsg.classList.add('hidden');
            }
        }

        document.addEventListener("DOMContentLoaded", () => {
            const container = document.getElementById('booking-container');
            if(container) { filterTabs('Pending Payment'); }
        });

        function formatRupiah(angka) {
            return 'RP' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function getFileName(path) {
            if(!path) return '-';
            return path.split('\\').pop().split('/').pop();
        }

        function openDetailModal(booking, car) {
            document.getElementById('mdl_car_img').src = `/${car.image_path}`;
            document.getElementById('mdl_car_category').innerText = car.category;
            document.getElementById('mdl_car_name').innerText = car.name;
            
            const specsHTML = `
                <span class="border border-white/40 bg-white/5 rounded px-2 md:px-3 py-1 text-[10px] md:text-xs font-semibold tracking-wider">${car.year}</span>
                <span class="border border-white/40 bg-white/5 rounded px-2 md:px-3 py-1 text-[10px] md:text-xs font-semibold tracking-wider uppercase">${car.transmission}</span>
                <span class="border border-white/40 bg-white/5 rounded px-2 md:px-3 py-1 text-[10px] md:text-xs font-semibold tracking-wider">${car.seats} SEATS</span>
                <span class="border border-white/40 bg-white/5 rounded px-2 md:px-3 py-1 text-[10px] md:text-xs font-semibold tracking-wider uppercase">${car.color}</span>
                <span class="border border-white/40 bg-white/5 rounded px-2 md:px-3 py-1 text-[10px] md:text-xs font-semibold tracking-wider uppercase">${car.fuel_type}</span>
                <span class="border border-white/40 bg-white/5 rounded px-2 md:px-3 py-1 text-[10px] md:text-xs font-semibold tracking-wider uppercase">${car.license_plate}</span>
            `;
            document.getElementById('mdl_specs_container').innerHTML = specsHTML;

            const startDate = new Date(booking.start_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
            const endDate = new Date(booking.end_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
            
            const diffTime = Math.abs(new Date(booking.end_date) - new Date(booking.start_date));
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; 

            document.getElementById('mdl_rental_date').innerText = `${startDate} - ${endDate}`;
            document.getElementById('mdl_duration').innerText = `${diffDays} DAY`;
            document.getElementById('mdl_plat').innerText = car.license_plate;
            document.getElementById('mdl_pickup').innerText = booking.pickup_time ? booking.pickup_time : '10:00';

            document.getElementById('mdl_renter_name').innerText = booking.renter_name;
            document.getElementById('mdl_renter_phone').innerText = booking.renter_phone;
            document.getElementById('mdl_renter_id').innerText = booking.renter_id_number;

            document.getElementById('doc_ktp').innerText = getFileName(booking.doc_ktp);
            document.getElementById('doc_sim').innerText = getFileName(booking.doc_sim);
            document.getElementById('doc_selfie').innerText = getFileName(booking.doc_selfie);

            const baseTotal = car.price_per_day * diffDays;
            document.getElementById('mdl_price_calc').innerText = `${formatRupiah(car.price_per_day)} X ${diffDays} DAY`;
            document.getElementById('mdl_price_subtotal').innerText = formatRupiah(baseTotal);
            
            const driverCost = booking.total_price - baseTotal;
            document.getElementById('mdl_driver_cost').innerText = driverCost > 0 ? formatRupiah(driverCost) : 'RP.0';
            
            document.getElementById('mdl_total_amount').innerText = formatRupiah(booking.total_price);
            
            const payStatElement = document.getElementById('mdl_payment_status');
            if(booking.status == 'History') {
                payStatElement.innerText = 'COMPLETED';
                payStatElement.className = 'font-bold uppercase tracking-wider text-[#4ADE80] text-xs md:text-sm';
            } else if (booking.status == 'Cancelled') {
                payStatElement.innerText = 'CANCELLED';
                payStatElement.className = 'font-bold uppercase tracking-wider text-[#EF4444] text-xs md:text-sm';
            } else if (booking.status == 'Ongoing' || booking.status == 'Ordered') {
                payStatElement.innerText = 'PAID (ONGOING)';
                payStatElement.className = 'font-bold uppercase tracking-wider text-[#60A5FA] text-xs md:text-sm';
            } else {
                payStatElement.innerText = 'PENDING';
                payStatElement.className = 'font-bold uppercase tracking-wider text-orange-400 text-xs md:text-sm';
            }

            const modal = document.getElementById('detailModal');
            modal.classList.remove('opacity-0', 'pointer-events-none');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal() {
            const modal = document.getElementById('detailModal');
            modal.classList.add('opacity-0', 'pointer-events-none');
            document.body.classList.remove('overflow-hidden');
        }
    </script>
</body>
</html>