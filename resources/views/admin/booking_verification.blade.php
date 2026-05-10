<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Verification - Admin Tripzy</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #EBF1FA; } /* Light blue background like image */
        .sidebar-bg { background: linear-gradient(180deg, #627AA3 0%, #859BBE 100%); }
        .topbar-bg { background: linear-gradient(to right, #B2C5E5 0%, #EBF1FA 50%); }
        .card-shadow { box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); }
        .glass-search { background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.6); }
        .modal { transition: opacity 0.25s ease; }
        body.modal-active { overflow-x: hidden; overflow-y: hidden !important; }
    </style>
</head>
<body class="flex h-screen overflow-hidden text-[#1C2C4A]">

    @if(session('success') || session('error'))
    <div id="toastNotification" class="fixed inset-0 flex items-center justify-center z-[100] bg-black/40 backdrop-blur-sm transition-opacity duration-300">
        <div class="{{ session('error') ? 'bg-red-500' : 'bg-[#5C72A6]' }} rounded-2xl p-8 flex flex-col items-center shadow-2xl relative w-96">
            <button onclick="closeToast()" class="absolute top-4 right-4 text-white hover:text-gray-200 text-xl"><i class="fa-solid fa-xmark"></i></button>
            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center {{ session('error') ? 'text-red-500' : 'text-[#5C72A6]' }} text-3xl mb-4 shadow-lg">
                <i class="fa-solid {{ session('error') ? 'fa-xmark' : 'fa-check' }}"></i>
            </div>
            <h3 class="text-white text-xl font-bold text-center leading-snug">{{ session('success') ?? session('error') }}</h3>
        </div>
    </div>
    <script>
        setTimeout(() => closeToast(), 3000);
        function closeToast() {
            const toast = document.getElementById('toastNotification');
            if(toast) { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }
        }
    </script>
    @endif

    <aside class="w-64 sidebar-bg text-white flex flex-col h-full shadow-lg z-20 shrink-0">
        <div class="p-6">
            <h1 class="text-3xl font-bold italic drop-shadow-md mb-1">Tripzy</h1>
            <p class="text-sm font-medium opacity-90">Admin Panel</p>
        </div>
        <nav class="mt-6 flex-grow flex flex-col gap-2 px-4">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg font-medium opacity-80 transition">Dashboard</a>
            <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 px-4 py-3 bg-white/20 rounded-lg font-semibold border border-white/30 backdrop-blur-sm shadow-sm transition">Booking Verification</a>
            <a href="{{ route('admin.catalog.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg font-medium opacity-80 transition">Manage Catalog</a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg font-medium opacity-80 transition">User Management</a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg font-medium opacity-80 transition">Transactions</a>
        </nav>
    </aside>

    <div class="flex-grow flex flex-col h-full overflow-y-auto relative bg-[#EBF1FA]">
        
        <header class="topbar-bg px-8 py-5 flex justify-between items-center sticky top-0 z-10">
            <div class="relative w-96">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 transform -translate-y-1/2 text-white"></i>
                <input type="text" placeholder="Search...." class="w-full glass-search text-white placeholder-white rounded-full py-2.5 pl-12 pr-4 focus:outline-none focus:bg-white/30 transition shadow-inner">
            </div>
            
            <div class="flex items-center gap-4">
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="text-xs text-red-500 font-semibold hover:underline">Logout</button>
                </form>
                <div class="flex items-center gap-3 bg-white/60 px-4 py-2 rounded-full border border-white/80 backdrop-blur-sm shadow-sm text-[#1C2C4A]">
                    <div class="w-10 h-10 bg-[#1C2C4A] rounded-full flex items-center justify-center text-white"><i class="fa-solid fa-user"></i></div>
                    <div class="leading-tight">
                        <h4 class="text-sm font-bold">{{ Auth::user()->name }}</h4>
                        <p class="text-[10px] font-medium text-[#4A6EB0]">Super Admin</p>
                    </div>
                </div>
            </div>
        </header>

        <main class="p-8 pb-24">
            <h2 class="text-2xl font-bold text-[#4A6EB0] mb-6">Dashboard</h2>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
                <div class="bg-white rounded-2xl p-6 card-shadow">
                    <h3 class="text-[#4A6EB0] font-bold text-sm mb-2">Total Bookings</h3>
                    <span class="text-4xl font-bold text-[#4A6EB0]">{{ $totalBookings }}</span>
                </div>
                <div class="bg-white rounded-2xl p-6 card-shadow">
                    <h3 class="text-[#4A6EB0] font-bold text-sm mb-2">Verification Required</h3>
                    <span class="text-4xl font-bold text-[#4A6EB0]">{{ $verificationRequired }}</span>
                </div>
                <div class="bg-white rounded-2xl p-6 card-shadow">
                    <h3 class="text-[#4A6EB0] font-bold text-sm mb-2">Verified</h3>
                    <span class="text-4xl font-bold text-[#4A6EB0]">{{ $verified }}</span>
                </div>
                <div class="bg-white rounded-2xl p-6 card-shadow">
                    <h3 class="text-[#4A6EB0] font-bold text-sm mb-2">Active Rentals</h3>
                    <span class="text-4xl font-bold text-[#4A6EB0]">{{ $activeRentals }}</span>
                </div>
                <div class="bg-white rounded-2xl p-6 card-shadow">
                    <h3 class="text-[#4A6EB0] font-bold text-sm mb-2">Awaiting Payment</h3>
                    <span class="text-4xl font-bold text-[#4A6EB0]">{{ $awaitingPayment }}</span>
                </div>
            </div>

            <div class="flex gap-4 mb-6">
                <div class="relative flex-grow max-w-md">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 transform -translate-y-1/2 text-[#8CA1C4]"></i>
                    <input type="text" placeholder="Search...." class="w-full bg-[#DCE4F2] text-[#4A6EB0] placeholder-[#8CA1C4] rounded-full py-2.5 pl-12 pr-4 focus:outline-none">
                </div>
                <select class="bg-[#DCE4F2] text-[#4A6EB0] font-medium rounded-full py-2.5 px-6 appearance-none pr-10">
                    <option>All Statuses</option>
                </select>
            </div>

            <div class="bg-white rounded-2xl card-shadow overflow-hidden border border-gray-100 p-2">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white text-[11px] uppercase tracking-widest text-black font-bold border-b border-gray-100">
                            <th class="py-4 px-4">BOOKING CODE</th>
                            <th class="py-4 px-4">USER</th>
                            <th class="py-4 px-4">CAR</th>
                            <th class="py-4 px-4">DATE</th>
                            <th class="py-4 px-4">STATUS</th>
                            <th class="py-4 px-4 text-center">ACTION</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($bookings as $booking)
                        @php
                            // Nentuin Label Status UI
                            $uiStatus = ''; $color = '';
                            if($booking->status == 'Ongoing') { $uiStatus = 'Active'; $color = 'bg-cyan-200 text-white'; }
                            elseif($booking->status == 'Ordered' && $booking->verified_at != null) { $uiStatus = 'Verified'; $color = 'bg-pink-300 text-white'; }
                            elseif($booking->status == 'Ordered' && $booking->verified_at == null) { $uiStatus = 'Pending'; $color = 'bg-orange-300 text-white'; }
                            elseif($booking->status == 'History') { $uiStatus = 'Completed'; $color = 'bg-green-300 text-white'; }
                            elseif($booking->status == 'Pending Payment') { $uiStatus = 'Awaiting'; $color = 'bg-gray-300 text-white'; }
                            else { $uiStatus = 'Cancelled'; $color = 'bg-red-300 text-white'; }
                        @endphp
                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                            <td class="py-3 px-4">
                                <p class="font-bold text-[#1C2C4A]">{{ $booking->booking_code }}</p>
                                <p class="text-[10px] text-[#8CA1C4]">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                            </td>
                            <td class="py-3 px-4">
                                <p class="font-medium text-[#1C2C4A]">{{ $booking->renter_name }}</p>
                                <p class="text-[10px] text-[#4A6EB0]">{{ $booking->renter_phone }}</p>
                            </td>
                            <td class="py-3 px-4 flex items-center gap-3">
                                <img src="{{ asset($booking->car->image_path) }}" class="w-12 h-8 object-contain">
                                <div>
                                    <p class="font-medium text-[#1C2C4A]">{{ $booking->car->name }}</p>
                                    <p class="text-[10px] text-[#4A6EB0]">{{ $booking->car->license_plate }}</p>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-[#1C2C4A] text-[11px] font-medium">
                                {{ $booking->start_date->format('Y-m-d') }}<br>
                                <span class="text-gray-400 font-normal">s/d</span> {{ $booking->end_date->format('Y-m-d') }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="{{ $color }} px-4 py-1 text-[10px] rounded-full font-bold shadow-sm">{{ $uiStatus }}</span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-end gap-3 text-xs">
                                    @if($uiStatus == 'Verified')
                                        <span class="text-blue-400 bg-blue-50 px-2 py-1 rounded-md font-semibold cursor-pointer hover:bg-blue-100" onclick="openPickupModal({{ json_encode($booking) }}, {{ json_encode($booking->car) }})">Ready for Pickup</span>
                                    @elseif($uiStatus == 'Pending')
                                        <span class="text-orange-400 font-semibold">Verifikasi</span>
                                    @endif
                                    
                                    <button onclick="openDetailModal({{ json_encode($booking) }}, {{ json_encode($booking->car) }}, '{{ $uiStatus }}')" class="text-[#4A6EB0] hover:text-blue-700 bg-gray-100 p-2 rounded-full transition">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </main>
    </div>

    <div id="detailModal" class="fixed inset-0 z-[60] flex items-center justify-center opacity-0 pointer-events-none modal">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="toggleModal('detailModal')"></div>
        <div class="bg-white rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto relative shadow-2xl p-8 z-10">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-[#4A6EB0]">Detail Booking</h2>
                <button onclick="toggleModal('detailModal')" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>

            <div class="bg-[#F4F7FC] rounded-xl p-4 flex items-center justify-between mb-6">
                <div class="flex items-center gap-4">
                    <div class="bg-white p-2 rounded-lg shadow-sm"><img id="mdl_car_img" src="" class="w-12 h-8 object-contain"></div>
                    <div>
                        <h4 id="mdl_car_name" class="font-bold text-[#1C2C4A]"></h4>
                        <p id="mdl_car_plate" class="text-xs text-[#8CA1C4]"></p>
                    </div>
                </div>
                <span id="mdl_status_badge" class="px-4 py-1 text-[10px] rounded-full font-bold shadow-sm text-white"></span>
            </div>

            <div class="space-y-3 text-sm mb-6 border-b border-gray-100 pb-6">
                <div class="flex justify-between"><span class="text-gray-500">Booking Code</span><span id="mdl_code" class="font-bold text-[#4A6EB0]"></span></div>
                <div class="flex justify-between"><span class="text-gray-500">User</span><span id="mdl_user" class="font-bold text-[#1C2C4A]"></span></div>
                <div class="flex justify-between"><span class="text-gray-500">Phone Number</span><span id="mdl_phone" class="font-bold text-[#1C2C4A]"></span></div>
                <div class="flex justify-between"><span class="text-gray-500">Id Number</span><span id="mdl_idnum" class="font-bold text-[#1C2C4A]"></span></div>
                <div class="flex justify-between"><span class="text-gray-500">Date Pickup</span><span id="mdl_start" class="font-bold text-[#1C2C4A]"></span></div>
                <div class="flex justify-between"><span class="text-gray-500">Return Date</span><span id="mdl_end" class="font-bold text-[#1C2C4A]"></span></div>
                <div class="flex justify-between"><span class="text-gray-500">Payment status</span><span id="mdl_paystat" class="font-bold text-green-500"></span></div>
                <div class="flex justify-between"><span class="text-gray-500">Total</span><span id="mdl_total" class="font-bold text-[#4A6EB0]"></span></div>
                <div class="flex justify-between"><span class="text-gray-500">Verified</span><span id="mdl_verified" class="font-bold text-[#1C2C4A]"></span></div>
            </div>

            <div class="space-y-6 mb-8">
                <div class="flex justify-between items-start gap-4">
                    <span class="text-gray-500 text-sm">KTP</span>
                    <img id="mdl_doc_ktp" src="" class="w-48 rounded-lg shadow-sm border border-gray-100">
                </div>
                <div class="flex justify-between items-start gap-4">
                    <span class="text-gray-500 text-sm">SIM</span>
                    <img id="mdl_doc_sim" src="" class="w-48 rounded-lg shadow-sm border border-gray-100">
                </div>
                <div id="passport_wrapper" class="flex justify-between items-start gap-4 hidden">
                    <span class="text-gray-500 text-sm">Passport</span>
                    <img id="mdl_doc_passport" src="" class="w-48 rounded-lg shadow-sm border border-gray-100">
                </div>
                <div class="flex justify-between items-start gap-4">
                    <span class="text-gray-500 text-sm">Selfi</span>
                    <img id="mdl_doc_selfie" src="" class="w-32 rounded-lg shadow-sm border border-gray-100">
                </div>
            </div>

            <div id="mdl_action_buttons" class="flex justify-between gap-4">
                </div>
        </div>
    </div>

    <div id="pickupModal" class="fixed inset-0 z-[60] flex items-center justify-center opacity-0 pointer-events-none modal">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="toggleModal('pickupModal')"></div>
        <div class="bg-white rounded-2xl w-full max-w-md relative shadow-2xl p-8 z-10">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-[#4A6EB0] font-bold text-lg">Car Pickup Confirmation</h2>
                <button onclick="toggleModal('pickupModal')" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <div class="bg-[#F4F7FC] rounded-xl p-4 flex items-center gap-4 mb-6 border border-gray-100">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-[#4A6EB0] shadow-sm"><i class="fa-solid fa-location-dot"></i></div>
                <div>
                    <h4 class="font-bold text-[#1C2C4A] text-sm">Showroom Tripzy</h4>
                    <p class="text-[10px] text-[#8CA1C4]">Showroom Tripzy - Bandar Lampung</p>
                </div>
            </div>

            <div class="text-center mb-6">
                <img id="pickup_car_img" src="" class="w-20 mx-auto mb-2 bg-[#EBF1FA] p-2 rounded-lg">
                <h4 id="pickup_car_name" class="font-bold text-[#4A6EB0]"></h4>
                <p id="pickup_car_plate" class="text-[10px] text-[#8CA1C4] uppercase"></p>
            </div>

            <div class="bg-[#F4F7FC] rounded-xl p-4 text-center mb-6">
                <p class="text-xs font-bold text-[#1C2C4A] mb-1">Traveler Booking Code</p>
                <h2 id="pickup_code_display" class="text-2xl font-bold text-[#4A6EB0]"></h2>
            </div>

            <form id="pickupForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-medium text-[#1C2C4A] mb-2">Enter the booking code from the traveler</label>
                    <input type="text" name="booking_code" placeholder="Example: ODR-1234" class="w-full bg-[#F4F7FC] border border-[#B2C5E5] text-[#4A6EB0] font-bold rounded-lg px-4 py-3 focus:outline-none" required>
                </div>

                <div class="bg-[#F4F7FC] rounded-xl p-4 mb-6 text-xs text-[#1C2C4A] leading-relaxed">
                    <p class="font-bold mb-2">Instructions:</p>
                    <ol class="list-decimal pl-4 space-y-1">
                        <li>Pastikan wisatawan membawa KTP/SIM asli</li>
                        <li>Periksa kondisi mobil bersama wisatawan</li>
                        <li>Memasukkan kode booking di atas</li>
                        <li>Klik "Konfirmasi Pengambilan" setelah verifikasi</li>
                    </ol>
                </div>

                <div class="flex gap-4">
                    <button type="button" onclick="toggleModal('pickupModal')" class="w-1/3 py-3 rounded-full border border-[#4A6EB0] text-[#4A6EB0] font-bold hover:bg-gray-50 transition">Close</button>
                    <button type="submit" class="w-2/3 py-3 rounded-full bg-[#4A6EB0] text-white font-bold hover:bg-[#38558A] transition shadow-md">Confirmation of Pickup</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            if (modal.classList.contains('opacity-0')) {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                document.body.classList.add('modal-active');
            } else {
                modal.classList.add('opacity-0', 'pointer-events-none');
                document.body.classList.remove('modal-active');
            }
        }

        function formatRupiah(num) {
            return "Rp " + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Buka Modal Detail Booking & Generate Tombol Aksi
        function openDetailModal(booking, car, uiStatus) {
            document.getElementById('mdl_car_img').src = `/${car.image_path}`;
            document.getElementById('mdl_car_name').innerText = car.name;
            document.getElementById('mdl_car_plate').innerText = car.license_plate;
            
            document.getElementById('mdl_code').innerText = booking.booking_code;
            document.getElementById('mdl_user').innerText = booking.renter_name;
            document.getElementById('mdl_phone').innerText = booking.renter_phone;
            document.getElementById('mdl_idnum').innerText = booking.renter_id_number;
            document.getElementById('mdl_start').innerText = booking.start_date.split('T')[0];
            document.getElementById('mdl_end').innerText = booking.end_date.split('T')[0];
            document.getElementById('mdl_paystat').innerText = booking.payment_status;
            document.getElementById('mdl_total').innerText = formatRupiah(booking.total_price);
            document.getElementById('mdl_verified').innerText = booking.verified_at ? booking.verified_at.replace('T', ', ').substring(0,19) : '-';

            // Set Images - UDAH DITAMBAHIN /storage/ BIAR NAMPIL!
            document.getElementById('mdl_doc_ktp').src = `/storage/${booking.doc_ktp}`;
            document.getElementById('mdl_doc_sim').src = `/storage/${booking.doc_sim}`;
            document.getElementById('mdl_doc_selfie').src = `/storage/${booking.doc_selfie}`;
            if(booking.doc_passport) {
                document.getElementById('mdl_doc_passport').src = `/storage/${booking.doc_passport}`;
                document.getElementById('passport_wrapper').classList.remove('hidden');
            } else {
                document.getElementById('passport_wrapper').classList.add('hidden');
            }

            // Set Status Badge UI
            const badge = document.getElementById('mdl_status_badge');
            badge.innerText = uiStatus;
            badge.className = "px-4 py-1 text-[10px] rounded-full font-bold shadow-sm text-white"; // Reset class
            if(uiStatus == 'Pending') badge.classList.add('bg-orange-300');
            else if(uiStatus == 'Verified') badge.classList.add('bg-pink-300');
            else if(uiStatus == 'Active') badge.classList.add('bg-cyan-300');
            else if(uiStatus == 'Completed') badge.classList.add('bg-green-300');
            else badge.classList.add('bg-gray-400');

            // Generate Action Buttons based on Status
            const btnBox = document.getElementById('mdl_action_buttons');
            btnBox.innerHTML = ''; // Clear old buttons

            let closeBtn = `<button type="button" onclick="toggleModal('detailModal')" class="w-full py-3 rounded-full border border-[#4A6EB0] text-[#4A6EB0] font-bold hover:bg-gray-50 transition">Close</button>`;

            if(uiStatus === 'Pending') {
                btnBox.innerHTML = `
                    <form method="POST" action="/admin/bookings/${booking.id}/cancel" class="w-1/3">
                        @csrf
                        <button type="submit" class="w-full py-3 rounded-full border border-red-500 text-red-500 font-bold hover:bg-red-50 transition">Cancel</button>
                    </form>
                    <form method="POST" action="/admin/bookings/${booking.id}/verify" class="w-2/3">
                        @csrf
                        <button type="submit" class="w-full py-3 rounded-full bg-[#22C55E] text-white font-bold hover:bg-green-600 transition shadow-md">Verification</button>
                    </form>
                `;
            } 
            else if (uiStatus === 'Active') {
                btnBox.innerHTML = `
                    ${closeBtn}
                    <form method="POST" action="/admin/bookings/${booking.id}/return" class="w-full">
                        @csrf
                        <button type="submit" class="w-full py-3 rounded-full bg-[#4A6EB0] text-white font-bold hover:bg-[#38558A] transition shadow-md">Return Confirmation</button>
                    </form>
                `;
            }
            else {
                // Verified / Completed / Cancelled
                btnBox.innerHTML = closeBtn;
            }

            toggleModal('detailModal');
        }

        // Buka Modal Pickup
        function openPickupModal(booking, car) {
            document.getElementById('pickupForm').action = `/admin/bookings/${booking.id}/pickup`;
            document.getElementById('pickup_car_img').src = `/${car.image_path}`;
            document.getElementById('pickup_car_name').innerText = car.name;
            document.getElementById('pickup_car_plate').innerText = car.license_plate;
            document.getElementById('pickup_code_display').innerText = booking.booking_code;
            
            toggleModal('pickupModal');
        }
    </script>
</body>
</html>