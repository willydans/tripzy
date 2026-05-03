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

        /* Nav Pill Styling */
        .nav-pill {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Tab Styling */
        .tab-btn { transition: all 0.3s ease; }
        .tab-active { background: rgba(255, 255, 255, 0.1); border-color: rgba(255,255,255,0.3); }
        
        /* Outline Text for Empty State */
        .text-outline {
            color: transparent;
            -webkit-text-stroke: 2px white;
            font-family: 'Bebas Neue', cursive;
            letter-spacing: 2px;
        }

        /* Modal Background */
        .modal-card { background-color: #7183A6; } /* Grayish Blue like design */
    </style>
</head>
<body class="flex flex-col min-h-screen">

    <!-- NAVBAR -->
    <nav class="pt-6 px-8 flex justify-between items-center mb-12 max-w-7xl mx-auto w-full">
        <div class="nav-pill rounded-full px-6 py-3 flex space-x-6 text-sm font-bold tracking-wider uppercase text-white">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-300 transition">Home</a>
            <a href="{{ route('user.catalog') }}" class="hover:text-blue-300 transition">Catalog</a>
            <a href="{{ route('user.destination') }}" class="hover:text-blue-300 transition">Destination</a>
            <a href="{{ route('user.orders') }}" class="text-blue-300 transition border-b-2 border-blue-300 pb-1">Orders</a>
        </div>
        <div class="flex space-x-4 items-center">
           <a href="{{ route('user.profile') }}" class="w-10 h-10 rounded-full nav-pill flex items-center justify-center hover:bg-white/20 transition text-white overflow-hidden relative border border-white/20">
    @if(Auth::user()->profile_photo)
        <!-- Kalau ada foto, tampilkan fotonya nutupin buletan -->
        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="w-full h-full object-cover absolute inset-0">
    @else
        <!-- Kalau gak ada foto, tampilkan icon orang -->
        <i class="fa-solid fa-user text-lg"></i>
    @endif
</a>
            <button class="w-10 h-10 rounded-full nav-pill flex items-center justify-center hover:bg-white/20 transition text-white">
                <i class="fa-solid fa-bell text-lg"></i>
            </button>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="flex-grow max-w-7xl mx-auto px-4 lg:px-8 w-full mb-20">
        
        <h1 class="text-5xl font-bold font-bebas tracking-wide uppercase mb-2">MY BOOKINGS</h1>
        <p class="text-gray-300 text-sm mb-10">Easily access your booking details and status anytime.</p>

        @if($bookings->isEmpty())
            <!-- EMPTY STATE (JIKA BELUM ADA BOOKING SAMA SEKALI) -->
            <div class="flex flex-col items-center justify-center mt-32 mb-32 text-center">
                <h2 class="text-4xl font-bebas tracking-widest uppercase mb-4">START YOUR JOURNEY BY</h2>
                <h1 class="text-7xl text-outline uppercase tracking-widest">BOOKING YOUR FIRST TRIP</h1>
            </div>
        @else
            <!-- TABS FILTER -->
            <div class="flex flex-wrap gap-4 mb-8">
                <button onclick="filterTabs('Pending Payment')" class="tab-btn tab-active border border-gray-600 rounded-full px-8 py-2.5 text-lg tracking-wide hover:bg-white/10">Pending Payment</button>
                <button onclick="filterTabs('Ongoing')" class="tab-btn border border-gray-600 rounded-full px-8 py-2.5 text-lg tracking-wide hover:bg-white/10">Ongoing</button>
                <button onclick="filterTabs('History')" class="tab-btn border border-gray-600 rounded-full px-8 py-2.5 text-lg tracking-wide hover:bg-white/10">History</button>
                <button onclick="filterTabs('Cancelled')" class="tab-btn border border-gray-600 rounded-full px-8 py-2.5 text-lg tracking-wide hover:bg-white/10">Cancelled</button>
            </div>

            <!-- BOOKING LISTS -->
            <div class="space-y-6" id="booking-container">
                @foreach($bookings as $booking)
                    @php
                        // Mapping status agar sesuai dengan tab
                        $tabCategory = $booking->status; 
                        if($booking->status == 'Ordered' || $booking->status == 'Ongoing') { $tabCategory = 'Ongoing'; }
                        if($booking->status == 'History') { $tabCategory = 'History'; }
                    @endphp

                    <div class="booking-card flex flex-col md:flex-row bg-[#6C7A9C] rounded-3xl overflow-hidden shadow-xl" data-status="{{ $tabCategory }}">
                        <!-- Car Image (Left) -->
                        <div class="bg-white w-full md:w-1/3 p-6 flex items-center justify-center rounded-l-3xl md:rounded-l-3xl md:rounded-tr-none rounded-t-3xl border-r-4 border-[#1A1F36]">
                            <img src="{{ asset($booking->car->image_path) }}" class="max-w-full h-auto object-contain drop-shadow-xl" alt="{{ $booking->car->name }}">
                        </div>
                        
                        <!-- Content (Right) -->
                        <div class="w-full md:w-2/3 p-8 flex flex-col justify-between relative">
                            <!-- Status Badge -->
                            <div class="absolute top-6 right-8">
                                @if($booking->status == 'Pending Payment')
                                    <span class="border-2 border-gray-300 text-gray-200 px-4 py-1.5 rounded-full text-xs font-bold shadow-sm backdrop-blur-sm">Pending Payment</span>
                                @elseif($booking->status == 'Ordered' || $booking->status == 'Ongoing')
                                    <span class="border-2 border-gray-300 text-gray-200 px-4 py-1.5 rounded-full text-xs font-bold shadow-sm backdrop-blur-sm">Ongoing</span>
                                @elseif($booking->status == 'History')
                                    <span class="bg-[#4ADE80] text-white px-5 py-1.5 rounded-full text-xs font-bold shadow-sm">Completed</span>
                                @else
                                    <span class="bg-[#EF4444] text-white px-5 py-1.5 rounded-full text-xs font-bold shadow-sm">Cancelled</span>
                                @endif
                            </div>

                            <div class="mb-4 mt-2">
                                <p class="text-gray-300 text-sm font-semibold mb-1">{{ $booking->booking_code }}</p>
                                <h2 class="text-4xl font-bebas tracking-wide uppercase">{{ $booking->car->name }}</h2>
                                <p class="text-xs text-gray-300 mt-2 flex items-center gap-4">
                                    <span><i class="fa-regular fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($booking->start_date)->translatedFormat('j M Y') }} - {{ \Carbon\Carbon::parse($booking->end_date)->translatedFormat('j M Y') }}</span>
                                    <span><i class="fa-solid fa-location-dot mr-1"></i> {{ $booking->duration ?? \Carbon\Carbon::parse($booking->start_date)->diffInDays($booking->end_date) + 1 }} Hari</span>
                                </p>
                            </div>

                            <hr class="border-gray-400/30 my-4">

                            <div class="flex justify-between items-end">
                                <div>
                                    <h3 class="text-3xl font-bold">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</h3>
                                    <p class="text-xs text-gray-300">Rp{{ number_format($booking->car->price_per_day, 0, ',', '.') }}/day</p>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex gap-3">
                                    @if($booking->status == 'Pending Payment')
                                        <form action="{{ route('user.orders.cancel', $booking->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-[#D34D4D] hover:bg-red-600 text-white px-6 py-2 rounded-full font-bold transition shadow-md">Cancel Booking</button>
                                        </form>
                                        <button class="bg-[#2A344D] hover:bg-[#1A1F36] text-white px-6 py-2 rounded-full font-bold transition shadow-md border border-gray-500">Payment Now</button>
                                    @else
                                        <button onclick="openDetailModal({{ json_encode($booking) }}, {{ json_encode($booking->car) }})" class="bg-[#2A344D] hover:bg-[#1A1F36] text-white px-8 py-2 rounded-full font-bold transition shadow-md border border-gray-500">Details</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                <!-- Notice jika tab kosong -->
                <div id="empty-tab-msg" class="hidden text-center text-gray-400 mt-12 mb-12">
                    <p class="text-xl">Tidak ada booking di kategori ini.</p>
                </div>
            </div>
        @endif

    </main>

    <!-- FOOTER -->
    <footer class="mt-auto border-t border-[#323A56] pt-10 pb-8 bg-[#171B2D]">
        <div class="max-w-7xl mx-auto px-4 lg:px-8 flex flex-col md:flex-row justify-between items-start">
            <div class="mb-8 md:mb-0">
                <h2 class="text-5xl font-bold font-bebas tracking-widest text-[#7A9FE0] mb-4">Tripzy</h2>
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
                    <li class="flex items-start gap-3"><i class="fa-solid fa-location-dot mt-1 text-[#7A9FE0]"></i><span>Jl. Prof. Dr. Ir. Sumantri Brojonegoro No.1, Gedong Meneng, Kec. Rajabasa, Bandar Lampung 35141</span></li>
                    <li class="flex items-center gap-3"><i class="fa-solid fa-phone text-[#7A9FE0]"></i><span>+6285276139801</span></li>
                    <li class="flex items-center gap-3"><i class="fa-solid fa-envelope text-[#7A9FE0]"></i><span>tripzy@gmail.com</span></li>
                    <li class="flex items-start gap-3"><i class="fa-solid fa-clock mt-1 text-[#7A9FE0]"></i><span>Senin - Minggu<br>24 Jam</span></li>
                </ul>
            </div>
        </div>
    </footer>

    <!-- MODAL DETAIL BOOKING -->
    @if(!$bookings->isEmpty())
    <div id="detailModal" class="fixed inset-0 z-[100] flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeModal()"></div>
        <div class="modal-card rounded-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto relative shadow-2xl p-8 z-10 text-white">
            
            <div class="flex justify-between items-center mb-6 border-b border-white/20 pb-4">
                <h2 class="text-lg font-bold tracking-widest uppercase flex items-center gap-2"><i class="fa-regular fa-file-lines"></i> DETAILS</h2>
                <button onclick="closeModal()" class="text-white hover:text-red-300 transition text-2xl"><i class="fa-solid fa-circle-xmark"></i></button>
            </div>

            <!-- Header Modal (Car Info) -->
            <div class="flex flex-col md:flex-row gap-6 mb-6">
                <!-- Image -->
                <div class="bg-[#9AB1D6] rounded-2xl p-4 flex items-center justify-center w-full md:w-1/3 shadow-inner">
                    <img id="mdl_car_img" src="" class="max-w-full h-auto object-contain drop-shadow-lg">
                </div>
                <!-- Specs -->
                <div class="w-full md:w-2/3">
                    <p id="mdl_car_category" class="text-sm font-semibold tracking-widest uppercase mb-1 text-gray-200"></p>
                    <h2 id="mdl_car_name" class="text-4xl font-bebas tracking-widest uppercase mb-4 shadow-sm"></h2>
                    
                    <!-- Dynamic Specs Badges -->
                    <div id="mdl_specs_container" class="flex flex-wrap gap-2">
                        <!-- Disuntik via JS -->
                    </div>
                </div>
            </div>

            <!-- Grid 4 Info Box -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                <div class="bg-white/10 p-3 rounded-lg border border-white/20">
                    <p class="text-[10px] text-gray-300 uppercase tracking-wider mb-1">RENTAL DATE</p>
                    <p id="mdl_rental_date" class="font-bold text-sm uppercase"></p>
                </div>
                <div class="bg-white/10 p-3 rounded-lg border border-white/20">
                    <p class="text-[10px] text-gray-300 uppercase tracking-wider mb-1">DURATION</p>
                    <p id="mdl_duration" class="font-bold text-sm uppercase"></p>
                </div>
                <div class="bg-white/10 p-3 rounded-lg border border-white/20">
                    <p class="text-[10px] text-gray-300 uppercase tracking-wider mb-1">PLAT NUMBER</p>
                    <p id="mdl_plat" class="font-bold text-sm uppercase"></p>
                </div>
                <div class="bg-white/10 p-3 rounded-lg border border-white/20">
                    <p class="text-[10px] text-gray-300 uppercase tracking-wider mb-1">PICK UP TIME</p>
                    <p id="mdl_pickup" class="font-bold text-sm uppercase">10.00 AM</p> <!-- Default klo blm ada dr DB -->
                </div>
            </div>

            <!-- Renter & Document -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-white/10 p-4 rounded-lg border border-white/20">
                    <p class="text-[10px] text-gray-300 uppercase tracking-wider mb-2">RENTER DETAILS</p>
                    <h4 id="mdl_renter_name" class="font-bold text-sm uppercase tracking-wide mb-1"></h4>
                    <p id="mdl_renter_phone" class="font-mono text-sm tracking-widest"></p>
                    <p id="mdl_renter_id" class="font-mono text-sm tracking-widest"></p>
                </div>
                <div class="bg-white/10 p-4 rounded-lg border border-white/20">
                    <p class="text-[10px] text-gray-300 uppercase tracking-wider mb-2">RENTER DOCUMENTS</p>
                    <div class="flex gap-2">
                        <div id="doc_ktp" class="bg-white/80 text-black text-xs font-semibold py-1.5 px-3 rounded-md shadow flex-grow text-center truncate">ktp.jpg</div>
                        <div id="doc_sim" class="bg-white/80 text-black text-xs font-semibold py-1.5 px-3 rounded-md shadow flex-grow text-center truncate">sim.jpg</div>
                        <div id="doc_selfie" class="bg-white/80 text-black text-xs font-semibold py-1.5 px-3 rounded-md shadow flex-grow text-center truncate">selfie.jpg</div>
                    </div>
                </div>
            </div>

            <!-- Cost Summary -->
            <div class="pt-4 border-t border-white/20">
                <p class="text-xs font-bold text-gray-300 uppercase tracking-wider mb-2">COST SUMMARY</p>
                <div class="flex justify-between text-sm mb-1 font-semibold text-gray-200">
                    <span id="mdl_price_calc"></span>
                    <span id="mdl_price_subtotal"></span>
                </div>
                <div class="flex justify-between text-sm mb-3 font-semibold text-gray-200">
                    <span>BIAYA DRIVER (OPSIONAL)</span>
                    <span id="mdl_driver_cost">RP.0</span>
                </div>
                
                <div class="flex justify-between items-center pt-3 border-t border-white/10">
                    <span class="font-bold uppercase tracking-wider">TOTAL AMOUNT</span>
                    <span id="mdl_total_amount" class="text-xl font-bold"></span>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <span class="font-bold uppercase tracking-wider">PAYMENT STATUS</span>
                    <span id="mdl_payment_status" class="font-bold uppercase tracking-wider"></span>
                </div>
            </div>

        </div>
    </div>
    @endif

    <!-- JAVASCRIPT LOGIC -->
    <script>
        // Logika Tab Filter
        function filterTabs(status) {
            // Update Active Style di Button Tab
            const buttons = document.querySelectorAll('.tab-btn');
            buttons.forEach(btn => {
                if(btn.innerText.trim() === status) {
                    btn.classList.add('tab-active');
                } else {
                    btn.classList.remove('tab-active');
                }
            });

            // Filter Card berdasarkan data-status
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

            // Tampilkan pesan kosong jika tidak ada data di tab tsb
            const emptyMsg = document.getElementById('empty-tab-msg');
            if(visibleCount === 0) {
                emptyMsg.classList.remove('hidden');
            } else {
                emptyMsg.classList.add('hidden');
            }
        }

        // Jalankan filter otomatis di tab pertama pas halaman diload (jika ada data)
        document.addEventListener("DOMContentLoaded", () => {
            const container = document.getElementById('booking-container');
            if(container) { filterTabs('Pending Payment'); }
        });

        // Logika Format Rupiah
        function formatRupiah(angka) {
            return 'RP' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Ambil nama file dari path buat ditampilkan di dokumen
        function getFileName(path) {
            if(!path) return '-';
            return path.split('\\').pop().split('/').pop();
        }

        // Buka Modal & Isi Data Dinamis
        function openDetailModal(booking, car) {
            // Car Info
            document.getElementById('mdl_car_img').src = `/${car.image_path}`;
            document.getElementById('mdl_car_category').innerText = car.category;
            document.getElementById('mdl_car_name').innerText = car.name;
            
            // Badges Spesifikasi Mobil
            const specsHTML = `
                <span class="border border-white/40 bg-white/5 rounded px-3 py-1 text-xs font-semibold tracking-wider">${car.year}</span>
                <span class="border border-white/40 bg-white/5 rounded px-3 py-1 text-xs font-semibold tracking-wider uppercase">${car.transmission}</span>
                <span class="border border-white/40 bg-white/5 rounded px-3 py-1 text-xs font-semibold tracking-wider">${car.seats} SEATS</span>
                <span class="border border-white/40 bg-white/5 rounded px-3 py-1 text-xs font-semibold tracking-wider uppercase">${car.color}</span>
                <span class="border border-white/40 bg-white/5 rounded px-3 py-1 text-xs font-semibold tracking-wider uppercase">${car.fuel_type}</span>
                <span class="border border-white/40 bg-white/5 rounded px-3 py-1 text-xs font-semibold tracking-wider uppercase">${car.license_plate}</span>
            `;
            document.getElementById('mdl_specs_container').innerHTML = specsHTML;

            // Booking Details Grid
            const startDate = new Date(booking.start_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
            const endDate = new Date(booking.end_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
            
            // Hitung Hari
            const diffTime = Math.abs(new Date(booking.end_date) - new Date(booking.start_date));
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; 

            document.getElementById('mdl_rental_date').innerText = `${startDate} - ${endDate}`;
            document.getElementById('mdl_duration').innerText = `${diffDays} DAY`;
            document.getElementById('mdl_plat').innerText = car.license_plate;

            // Renter
            document.getElementById('mdl_renter_name').innerText = booking.renter_name;
            document.getElementById('mdl_renter_phone').innerText = booking.renter_phone;
            document.getElementById('mdl_renter_id').innerText = booking.renter_id_number;

            // Docs
            document.getElementById('doc_ktp').innerText = getFileName(booking.doc_ktp);
            document.getElementById('doc_sim').innerText = getFileName(booking.doc_sim);
            document.getElementById('doc_selfie').innerText = getFileName(booking.doc_selfie);

            // Calculation Details
            const baseTotal = car.price_per_day * diffDays;
            document.getElementById('mdl_price_calc').innerText = `${formatRupiah(car.price_per_day)} X ${diffDays} DAY`;
            document.getElementById('mdl_price_subtotal').innerText = formatRupiah(baseTotal);
            
            // Cek driver cost dari selisih total_price dgn baseTotal
            const driverCost = booking.total_price - baseTotal;
            document.getElementById('mdl_driver_cost').innerText = driverCost > 0 ? formatRupiah(driverCost) : 'RP.0';
            
            document.getElementById('mdl_total_amount').innerText = formatRupiah(booking.total_price);
            
            // Payment Status Color
            const payStatElement = document.getElementById('mdl_payment_status');
            if(booking.status == 'History') {
                payStatElement.innerText = 'COMPLETED';
                payStatElement.className = 'font-bold uppercase tracking-wider text-[#4ADE80]';
            } else if (booking.status == 'Cancelled') {
                payStatElement.innerText = 'CANCELLED';
                payStatElement.className = 'font-bold uppercase tracking-wider text-[#EF4444]';
            } else if (booking.status == 'Ongoing' || booking.status == 'Ordered') {
                payStatElement.innerText = 'PAID (ONGOING)';
                payStatElement.className = 'font-bold uppercase tracking-wider text-[#60A5FA]';
            } else {
                payStatElement.innerText = 'PENDING';
                payStatElement.className = 'font-bold uppercase tracking-wider text-orange-400';
            }

            // Show Modal
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