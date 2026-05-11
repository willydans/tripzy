<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions - Admin Tripzy</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #EBF1FA; }
        .sidebar-bg { background: linear-gradient(180deg, #627AA3 0%, #859BBE 100%); }
        .topbar-bg { background: linear-gradient(to right, #B2C5E5 0%, #EBF1FA 50%); }
        .card-shadow { box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); }
        .glass-search { background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.6); }
        .modal { transition: opacity 0.25s ease; }
        body.modal-active { overflow: hidden; }
        
        /* Custom scrollbar untuk tabel */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #B2C5E5; border-radius: 10px; }
    </style>
</head>
<body class="flex h-screen text-[#1C2C4A] overflow-hidden">

    <aside class="w-64 sidebar-bg text-white flex flex-col h-full shadow-lg z-20 shrink-0">
        <div class="p-6">
            <h1 class="text-3xl font-bold italic drop-shadow-md mb-1">Tripzy</h1>
            <p class="text-sm font-medium opacity-90">Admin Panel</p>
        </div>
        <nav class="mt-6 flex-grow flex flex-col gap-2 px-4">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg font-medium opacity-80 transition">Dashboard</a>
            <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg font-medium opacity-80 transition">Booking Verification</a>
            <a href="{{ route('admin.catalog.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg font-medium opacity-80 transition">Manage Catalog</a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg font-medium opacity-80 hover:opacity-100 transition">User Management</a>
            <a href="{{ route('admin.transactions.index') }}" class="flex items-center gap-3 px-4 py-3 bg-white/20 rounded-lg font-semibold border border-white/30 backdrop-blur-sm shadow-sm transition">Transactions</a>
        </nav>
    </aside>

    <div class="flex-grow flex flex-col h-full overflow-y-auto relative bg-[#EBF1FA]">
        
        <header class="topbar-bg px-8 py-5 flex justify-between items-center sticky top-0 z-10">
            <div class="relative w-96">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 transform -translate-y-1/2 text-white"></i>
                <input type="text" placeholder="Search...." class="w-full glass-search text-white placeholder-white rounded-full py-2.5 pl-12 pr-4 focus:outline-none focus:bg-white/30 transition shadow-inner">
            </div>
            
            <div class="flex items-center gap-4">
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
            
            <h2 class="text-2xl font-bold text-[#4A6EB0] mb-6">Transactions</h2>

            <div class="flex justify-between items-start mb-8">
                <div class="bg-white rounded-2xl p-8 card-shadow text-center flex-grow max-w-xl mx-auto border-t-4 border-[#4A6EB0]">
                    <h3 class="text-[#4A6EB0] font-bold text-lg mb-2">Transactions</h3>
                    <h1 class="text-4xl md:text-5xl font-bebas tracking-wider text-[#4A6EB0]">RP {{ number_format($totalRevenue, 0, ',', '.') }}</h1>
                </div>
                <a href="{{ route('admin.transactions.export') }}" class="bg-[#5C72A6] hover:bg-[#4A6EB0] text-white px-6 py-3 rounded-full font-bold shadow-md transition flex items-center gap-2 mt-4 ml-8 cursor-pointer">
    <i class="fa-solid fa-download"></i> Export Report
</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
                <div class="bg-white rounded-2xl p-6 card-shadow border-l-4 border-blue-400">
                    <h3 class="text-[#4A6EB0] font-bold text-sm mb-2">Total Transactions</h3>
                    <span class="text-4xl font-bold text-[#4A6EB0]">{{ $totalTransactions }}</span>
                </div>
                <div class="bg-white rounded-2xl p-6 card-shadow border-l-4 border-orange-400">
                    <h3 class="text-[#4A6EB0] font-bold text-sm mb-2">Pending</h3>
                    <span class="text-4xl font-bold text-[#4A6EB0]">{{ $pending }}</span>
                </div>
                <div class="bg-white rounded-2xl p-6 card-shadow border-l-4 border-green-400">
                    <h3 class="text-[#4A6EB0] font-bold text-sm mb-2">Successful</h3>
                    <span class="text-4xl font-bold text-[#4A6EB0]">{{ $successful }}</span>
                </div>
                <div class="bg-white rounded-2xl p-6 card-shadow border-l-4 border-red-400">
                    <h3 class="text-[#4A6EB0] font-bold text-sm mb-2">Cancelled</h3>
                    <span class="text-4xl font-bold text-[#4A6EB0]">{{ $cancelled }}</span>
                </div>
                <div class="bg-white rounded-2xl p-6 card-shadow border-l-4 border-gray-400">
                    <h3 class="text-[#4A6EB0] font-bold text-sm mb-2">Refunded</h3>
                    <span class="text-4xl font-bold text-[#4A6EB0]">{{ $refunded }}</span>
                </div>
            </div>

            <div class="flex gap-4 mb-6">
                <div class="relative flex-grow max-w-md">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 transform -translate-y-1/2 text-[#8CA1C4]"></i>
                    <input type="text" id="searchInput" placeholder="Search...." class="w-full bg-[#DCE4F2] text-[#4A6EB0] placeholder-[#8CA1C4] rounded-full py-2.5 pl-12 pr-4 focus:outline-none">
                </div>
                <div class="relative">
                    <select class="bg-[#DCE4F2] text-[#4A6EB0] font-medium rounded-full py-2.5 px-6 appearance-none pr-10 outline-none w-48">
                        <option>All Statuses</option>
                        <option>Completed</option>
                        <option>Pending</option>
                        <option>Cancelled</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-[#8CA1C4] pointer-events-none"></i>
                </div>
            </div>

            <div class="bg-white rounded-2xl card-shadow overflow-hidden border border-gray-100 p-2">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-white text-[11px] uppercase tracking-widest text-black font-bold border-b border-gray-100">
                                <th class="py-4 px-4">ID</th>
                                <th class="py-4 px-4">USER</th>
                                <th class="py-4 px-4">CAR</th>
                                <th class="py-4 px-4">DATE</th>
                                <th class="py-4 px-4">TOTAL</th>
                                <th class="py-4 px-4">STATUS</th>
                                <th class="py-4 px-4 text-center">ACTION</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @foreach($bookings as $booking)
                            @php
                                // Mapping status buat UI
                                $uiStatus = ''; $color = '';
                                if(in_array($booking->status, ['History', 'Ongoing'])) { 
                                    $uiStatus = 'Completed'; 
                                    $color = 'bg-green-300 text-white'; 
                                } elseif(in_array($booking->status, ['Pending Payment', 'Ordered'])) { 
                                    $uiStatus = 'Pending'; 
                                    $color = 'bg-orange-300 text-white'; 
                                } else { 
                                    $uiStatus = 'Cancelled'; 
                                    $color = 'bg-red-300 text-white'; 
                                }
                            @endphp
                            <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                                <td class="py-3 px-4">
                                    <p class="font-bold text-[#1C2C4A]">{{ $booking->booking_code }}</p>
                                    <p class="text-[10px] text-[#4A6EB0]">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                                </td>
                                <td class="py-3 px-4">
                                    <p class="font-bold text-[#1C2C4A]">{{ $booking->renter_name }}</p>
                                    <p class="text-[10px] text-[#8CA1C4]">{{ $booking->renter_phone }}</p>
                                </td>
                                <td class="py-3 px-4 flex items-center gap-3">
                                    <img src="{{ asset($booking->car->image_path) }}" class="w-12 h-8 object-contain bg-gray-100 rounded p-1">
                                    <div>
                                        <p class="font-bold text-[#1C2C4A]">{{ $booking->car->name }}</p>
                                        <p class="text-[10px] text-[#8CA1C4]">{{ $booking->car->license_plate }}</p>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-[#1C2C4A] text-[11px] font-medium">
                                    {{ $booking->start_date->format('Y-m-d') }}<br>
                                    <span class="text-gray-400 font-normal">s/d</span> {{ $booking->end_date->format('Y-m-d') }}
                                </td>
                                <td class="py-3 px-4">
                                    <p class="font-bold text-[#1C2C4A]">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="{{ $color }} px-4 py-1 text-[10px] rounded-full font-bold shadow-sm">{{ $uiStatus }}</span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <button onclick="openInvoiceModal({{ json_encode($booking) }}, {{ json_encode($booking->car) }}, '{{ $uiStatus }}')" class="text-[#4A6EB0] hover:text-blue-700 bg-[#EBF1FA] p-2 rounded-full transition shadow-sm">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
        
        <footer class="mt-auto pt-10 pb-8 px-8 flex justify-between items-end opacity-60">
            <div>
                <h2 class="text-4xl font-bold font-bebas tracking-widest text-[#4A6EB0] mb-2">Tripzy</h2>
                <div class="flex space-x-4 text-xl text-[#4A6EB0]">
                    <i class="fa-brands fa-discord"></i>
                    <i class="fa-brands fa-whatsapp"></i>
                    <i class="fa-brands fa-telegram"></i>
                    <i class="fa-brands fa-instagram"></i>
                </div>
            </div>
            <div class="text-right">
                <h3 class="text-lg font-bold text-[#4A6EB0] mb-2">Contact Us</h3>
                <ul class="space-y-1 text-[10px] font-medium text-[#4A6EB0]">
                    <li><i class="fa-solid fa-location-dot"></i> Jl. Prof. Dr. Ir. Sumantri Brojonegoro</li>
                    <li><i class="fa-solid fa-phone"></i> +6285278139801</li>
                    <li><i class="fa-solid fa-envelope"></i> tripzy@gmail.com</li>
                </ul>
            </div>
        </footer>
    </div>

    <div id="invoiceModal" class="fixed inset-0 z-[60] flex items-center justify-center opacity-0 pointer-events-none modal p-4">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="toggleModal('invoiceModal')"></div>
        
        <div class="bg-white rounded-2xl w-full max-w-lg relative shadow-2xl z-10 flex flex-col max-h-[95vh]">
            
            <button onclick="toggleModal('invoiceModal')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 z-20" id="btnCloseModal">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>

            <div id="invoiceContent" class="p-8 pb-4 overflow-y-auto bg-white rounded-t-2xl">
                <h2 class="text-xl font-bold text-[#6B85B5] mb-6">E-Invoice</h2>

                <div class="text-center mb-6 border-b border-gray-100 pb-6">
                    <div class="w-20 h-16 mx-auto bg-[#DCE4F2] rounded-lg p-2 mb-3 flex items-center justify-center">
                        <img id="inv_car_img" src="" class="w-full h-full object-contain">
                    </div>
                    <h3 id="inv_car_name" class="text-lg font-bold text-[#6B85B5] uppercase"></h3>
                    <p id="inv_car_plate" class="text-xs text-[#8CA1C4] uppercase tracking-wider"></p>
                </div>

                <div class="bg-[#E2EAF6] rounded-xl p-6 mb-4 text-sm text-[#1C2C4A] space-y-3 font-medium">
                    <div class="flex justify-between">
                        <span class="text-gray-600">User</span>
                        <span id="inv_user" class="font-bold text-right"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Car</span>
                        <span id="inv_car_name2" class="font-bold text-right"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Period</span>
                        <span id="inv_period" class="font-bold text-right"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Duration</span>
                        <span id="inv_duration" class="font-bold text-right"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Daily Rate</span>
                        <span id="inv_rate" class="font-bold text-right"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Payment Method</span>
                        <span class="font-bold text-right">Qris</span>
                    </div>
                    <div class="flex justify-between items-center mt-2 pt-2 border-t border-[#C5D6F5]">
                        <span class="text-gray-600">Status</span>
                        <span id="inv_status" class="font-bold"></span>
                    </div>
                </div>

                <div class="bg-[#DCE4F2] rounded-xl p-6 text-sm text-[#1C2C4A]">
                    <div class="flex justify-between items-center mb-3">
                        <span id="inv_calc_text" class="text-gray-600"></span>
                        <span id="inv_calc_total" class="font-bold"></span>
                    </div>
                    <div class="flex justify-between items-center pt-3 border-t border-[#B2C5E5]">
                        <span class="text-lg font-extrabold">TOTAL</span>
                        <span id="inv_grand_total" class="text-lg font-extrabold"></span>
                    </div>
                </div>
            </div>

            <div class="p-6 pt-4 bg-white rounded-b-2xl border-t border-gray-50 flex gap-4" id="actionButtons">
                <button onclick="toggleModal('invoiceModal')" class="w-1/3 py-3 rounded-full border border-[#6B85B5] text-[#6B85B5] font-bold hover:bg-gray-50 transition">
                    Close
                </button>
                <button onclick="downloadPDF()" class="w-2/3 py-3 rounded-full bg-[#4A6EB0] text-white font-bold hover:bg-[#38558A] transition shadow-md flex justify-center items-center gap-2">
                    <i class="fa-solid fa-download"></i> Download PDF
                </button>
            </div>
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

        // Variabel global buat nangkep kode booking saat PDF didownload
        let currentBookingCode = 'INV';

        // Buka Modal & Isi Data Dinamis
        function openInvoiceModal(booking, car, uiStatus) {
            currentBookingCode = booking.booking_code;
            
            document.getElementById('inv_car_img').src = `/${car.image_path}`;
            document.getElementById('inv_car_name').innerText = car.name;
            document.getElementById('inv_car_plate').innerText = car.license_plate;
            
            document.getElementById('inv_user').innerText = booking.renter_name;
            document.getElementById('inv_car_name2').innerText = car.name;
            
            let start = booking.start_date.split('T')[0];
            let end = booking.end_date.split('T')[0];
            document.getElementById('inv_period').innerText = `${start} - ${end}`;
            document.getElementById('inv_duration').innerText = `${booking.duration} hari`;
            document.getElementById('inv_rate').innerText = formatRupiah(car.price_per_day);
            
            // Set Status Color
            const statEl = document.getElementById('inv_status');
            statEl.innerText = uiStatus === 'Completed' ? 'Success' : uiStatus;
            if(uiStatus === 'Completed') { statEl.className = 'font-bold text-green-500'; }
            else if(uiStatus === 'Pending') { statEl.className = 'font-bold text-orange-500'; }
            else { statEl.className = 'font-bold text-red-500'; }

            // Kalkulasi
            let rentalFee = car.price_per_day * booking.duration;
            let driverText = booking.with_driver ? ' (+ Supir)' : '';
            document.getElementById('inv_calc_text').innerText = `Rental Fee (${booking.duration} days × ${formatRupiah(car.price_per_day)})${driverText}`;
            document.getElementById('inv_calc_total').innerText = formatRupiah(rentalFee);
            document.getElementById('inv_grand_total').innerText = formatRupiah(booking.total_price);

            toggleModal('invoiceModal');
        }

        // Fungsi Download PDF menggunakan html2pdf.js
        function downloadPDF() {
            // Target div yang mau di-print (cuma kontennya, tombol-tombol diabaikan)
            const element = document.getElementById('invoiceContent');
            
            // Konfigurasi PDF
            const opt = {
                margin:       [0.5, 0.5, 0.5, 0.5], // inci
                filename:     `${currentBookingCode}.pdf`,
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true }, // useCORS penting biar gambar mobil bisa ke-render
                jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
            };

            // Jalankan html2pdf
            html2pdf().set(opt).from(element).save();
        }
    </script>
</body>
</html>