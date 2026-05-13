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
        body { font-family: 'Poppins', sans-serif; background-color: #EBF1FA; } 
        .sidebar-bg { background: linear-gradient(180deg, #627AA3 0%, #859BBE 100%); }
        .topbar-bg { background: linear-gradient(to right, #B2C5E5 0%, #EBF1FA 50%); }
        .card-shadow { box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); }
        .glass-search { background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.6); }
        .modal { transition: opacity 0.25s ease; }
        body.modal-active { overflow-y: hidden !important; }
        
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }

        /* Custom Input untuk Form Return */
        .return-input {
            width: 100%; border: 1px solid #B2C5E5; border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.875rem; color: #1C2C4A; outline: none; transition: border-color 0.3s;
        }
        .return-input:focus { border-color: #5C72A6; }
        
        /* Custom Checkbox */
        .custom-checkbox {
            width: 1.25rem; height: 1.25rem; border: 1px solid #B2C5E5; border-radius: 0.25rem; cursor: pointer; accent-color: #5C72A6;
        }
    </style>
</head>
<body class="flex h-screen overflow-hidden text-[#1C2C4A]">

    @if(session('success') || session('error'))
    <div id="toastNotification" class="fixed inset-0 flex items-center justify-center z-[100] bg-black/40 backdrop-blur-sm transition-opacity duration-300 px-4">
        <div class="{{ session('error') ? 'bg-red-500' : 'bg-[#5C72A6]' }} rounded-2xl p-6 md:p-8 flex flex-col items-center shadow-2xl relative w-full max-w-sm transform scale-100 transition-transform">
            <button onclick="closeToast()" class="absolute top-4 right-4 text-white hover:text-gray-200 text-xl"><i class="fa-solid fa-xmark"></i></button>
            <div class="w-12 h-12 md:w-16 md:h-16 bg-white rounded-full flex items-center justify-center {{ session('error') ? 'text-red-500' : 'text-[#5C72A6]' }} text-2xl md:text-3xl mb-4 shadow-lg">
                <i class="fa-solid {{ session('error') ? 'fa-xmark' : 'fa-check' }}"></i>
            </div>
            <h3 class="text-white text-lg md:text-xl font-bold text-center leading-snug">{{ session('success') ?? session('error') }}</h3>
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

    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-30 hidden md:hidden transition-opacity opacity-0" onclick="toggleSidebar()"></div>

    <aside id="sidebar" class="fixed inset-y-0 left-0 w-64 sidebar-bg text-white flex flex-col h-full shadow-2xl md:shadow-lg z-40 transform -translate-x-full md:relative md:translate-x-0 transition-transform duration-300 shrink-0">
        <div class="p-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold italic drop-shadow-md mb-1">Tripzy</h1>
                <p class="text-sm font-medium opacity-90">Admin Panel</p>
            </div>
            <button onclick="toggleSidebar()" class="md:hidden text-white text-2xl hover:text-gray-200"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <nav class="mt-2 md:mt-6 flex-grow flex flex-col gap-2 px-4 overflow-y-auto hide-scroll pb-4">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg font-medium opacity-80 transition">Dashboard</a>
            <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 px-4 py-3 bg-white/20 rounded-lg font-semibold border border-white/30 backdrop-blur-sm shadow-sm transition">Booking Verification</a>
            <a href="{{ route('admin.catalog.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg font-medium opacity-80 transition">Manage Catalog</a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg font-medium opacity-80 transition">User Management</a>
            <a href="{{ route('admin.transactions.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg font-medium opacity-80 transition">Transactions</a>
        </nav>
    </aside>

    <div class="flex-grow flex flex-col h-full overflow-y-auto relative bg-[#EBF1FA]">
        
        <header class="topbar-bg px-4 md:px-8 py-4 md:py-5 flex justify-between items-center sticky top-0 z-20 shadow-sm md:shadow-none">
            <div class="flex items-center gap-3 md:gap-0">
                <button onclick="toggleSidebar()" class="md:hidden text-[#4A6EB0] text-2xl focus:outline-none">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="relative w-full max-w-[200px] md:max-w-xs lg:w-96 hidden sm:block">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 transform -translate-y-1/2 text-white"></i>
                    <input type="text" placeholder="Search...." class="w-full glass-search text-white placeholder-white rounded-full py-2 md:py-2.5 pl-10 md:pl-12 pr-4 focus:outline-none focus:bg-white/30 transition shadow-inner text-sm md:text-base">
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <form action="{{ route('logout') }}" method="POST" class="m-0 hidden sm:block">
                    @csrf
                    <button type="submit" class="text-xs text-red-500 font-semibold hover:underline">Logout</button>
                </form>
                <div class="flex items-center gap-2 md:gap-3 bg-white/60 md:bg-white/40 px-3 md:px-4 py-1.5 md:py-2 rounded-full border border-white/80 backdrop-blur-sm shadow-sm text-[#1C2C4A]">
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-[#1C2C4A] rounded-full flex items-center justify-center text-white text-sm md:text-base"><i class="fa-solid fa-user"></i></div>
                    <div class="leading-tight hidden sm:block">
                        <h4 class="text-xs md:text-sm font-bold">{{ Auth::user()->name }}</h4>
                        <p class="text-[9px] md:text-[10px] font-medium text-[#4A6EB0]">Super Admin</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="m-0 sm:hidden ml-2 border-l border-gray-300 pl-2">
                        @csrf
                        <button type="submit" class="text-[#1C2C4A]"><i class="fa-solid fa-right-from-bracket"></i></button>
                    </form>
                </div>
            </div>
        </header>

        <main class="p-4 md:p-8 pb-24">
            <h2 class="text-2xl md:text-3xl font-bold text-[#4A6EB0] mb-4 md:mb-6">Dashboard</h2>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 md:gap-4 mb-6 md:mb-8">
                <div class="bg-white rounded-2xl p-4 md:p-6 card-shadow col-span-2 md:col-span-1">
                    <h3 class="text-[#4A6EB0] font-bold text-xs md:text-sm mb-1 md:mb-2">Total Bookings</h3>
                    <span class="text-3xl md:text-4xl font-bold text-[#4A6EB0]">{{ $totalBookings }}</span>
                </div>
                <div class="bg-white rounded-2xl p-4 md:p-6 card-shadow">
                    <h3 class="text-[#4A6EB0] font-bold text-xs md:text-sm mb-1 md:mb-2 leading-tight">Verification Req</h3>
                    <span class="text-3xl md:text-4xl font-bold text-[#4A6EB0]">{{ $verificationRequired }}</span>
                </div>
                <div class="bg-white rounded-2xl p-4 md:p-6 card-shadow">
                    <h3 class="text-[#4A6EB0] font-bold text-xs md:text-sm mb-1 md:mb-2">Verified</h3>
                    <span class="text-3xl md:text-4xl font-bold text-[#4A6EB0]">{{ $verified }}</span>
                </div>
                <div class="bg-white rounded-2xl p-4 md:p-6 card-shadow">
                    <h3 class="text-[#4A6EB0] font-bold text-xs md:text-sm mb-1 md:mb-2">Active Rentals</h3>
                    <span class="text-3xl md:text-4xl font-bold text-[#4A6EB0]">{{ $activeRentals }}</span>
                </div>
                <div class="bg-white rounded-2xl p-4 md:p-6 card-shadow">
                    <h3 class="text-[#4A6EB0] font-bold text-xs md:text-sm mb-1 md:mb-2 leading-tight">Awaiting Payment</h3>
                    <span class="text-3xl md:text-4xl font-bold text-[#4A6EB0]">{{ $awaitingPayment }}</span>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-3 md:gap-4 mb-6">
                <div class="relative flex-grow w-full lg:max-w-md">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 transform -translate-y-1/2 text-[#8CA1C4]"></i>
                    <input type="text" placeholder="Search...." class="w-full bg-[#DCE4F2] text-[#4A6EB0] placeholder-[#8CA1C4] rounded-full py-2.5 pl-12 pr-4 focus:outline-none transition text-sm">
                </div>
                <div class="flex gap-2 overflow-x-auto hide-scroll pb-1">
                    <select class="bg-[#DCE4F2] text-[#4A6EB0] text-sm md:text-base font-medium rounded-full py-2.5 px-5 appearance-none pr-8 relative bg-no-repeat bg-[right_1rem_center] bg-[length:1em_1em] whitespace-nowrap outline-none">
                        <option>All Statuses</option>
                    </select>
                </div>
            </div>

            <div class="bg-white rounded-2xl card-shadow overflow-hidden border border-gray-100 p-2 md:p-4 w-full overflow-x-auto">
                <table class="w-full min-w-[700px] text-left border-collapse">
                    <thead>
                        <tr class="bg-white text-[10px] md:text-[11px] uppercase tracking-widest text-black font-bold border-b border-gray-100">
                            <th class="py-3 px-3 md:py-4 md:px-4">BOOKING CODE</th>
                            <th class="py-3 px-3 md:py-4 md:px-4">USER</th>
                            <th class="py-3 px-3 md:py-4 md:px-4">CAR</th>
                            <th class="py-3 px-3 md:py-4 md:px-4">DATE</th>
                            <th class="py-3 px-3 md:py-4 md:px-4">STATUS</th>
                            <th class="py-3 px-3 md:py-4 md:px-4 text-center">ACTION</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs md:text-sm">
                        @foreach($bookings as $booking)
                        @php
                            $uiStatus = ''; $color = '';
                            if($booking->status == 'Ongoing') { $uiStatus = 'Active'; $color = 'bg-cyan-200 text-white'; }
                            elseif($booking->status == 'Ordered' && $booking->verified_at != null) { $uiStatus = 'Verified'; $color = 'bg-pink-300 text-white'; }
                            elseif($booking->status == 'Ordered' && $booking->verified_at == null) { $uiStatus = 'Pending'; $color = 'bg-orange-300 text-white'; }
                            elseif($booking->status == 'History') { $uiStatus = 'Completed'; $color = 'bg-green-300 text-white'; }
                            elseif($booking->status == 'Pending Payment') { $uiStatus = 'Awaiting'; $color = 'bg-gray-300 text-white'; }
                            else { $uiStatus = 'Cancelled'; $color = 'bg-red-300 text-white'; }
                        @endphp
                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                            <td class="py-3 px-3 md:px-4">
                                <p class="font-bold text-[#1C2C4A] whitespace-nowrap">{{ $booking->booking_code }}</p>
                                <p class="text-[9px] md:text-[10px] text-[#8CA1C4] whitespace-nowrap">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                            </td>
                            <td class="py-3 px-3 md:px-4">
                                <p class="font-medium text-[#1C2C4A] whitespace-nowrap truncate max-w-[120px]">{{ $booking->renter_name }}</p>
                                <p class="text-[9px] md:text-[10px] text-[#4A6EB0] whitespace-nowrap">{{ $booking->renter_phone }}</p>
                            </td>
                            <td class="py-3 px-3 md:px-4 flex items-center gap-2 md:gap-3">
                                <img src="{{ asset($booking->car->image_path) }}" class="w-10 h-6 md:w-12 md:h-8 object-contain shrink-0">
                                <div>
                                    <p class="font-medium text-[#1C2C4A] whitespace-nowrap">{{ $booking->car->name }}</p>
                                    <p class="text-[9px] md:text-[10px] text-[#4A6EB0] whitespace-nowrap">{{ $booking->car->license_plate }}</p>
                                </div>
                            </td>
                            <td class="py-3 px-3 md:px-4 text-[#1C2C4A] text-[10px] md:text-[11px] font-medium whitespace-nowrap">
                                {{ $booking->start_date->format('Y-m-d') }}<br>
                                <span class="text-gray-400 font-normal">s/d</span> {{ $booking->end_date->format('Y-m-d') }}
                            </td>
                            <td class="py-3 px-3 md:px-4">
                                <span class="{{ $color }} px-3 py-1 text-[9px] md:text-[10px] rounded-full font-bold shadow-sm whitespace-nowrap">{{ $uiStatus }}</span>
                            </td>
                            <td class="py-3 px-3 md:px-4 text-center">
                                <div class="flex items-center justify-end gap-2 md:gap-3 text-[10px] md:text-xs">
                                    @if($uiStatus == 'Verified')
                                        <span class="text-blue-400 bg-blue-50 px-2 py-1 rounded-md font-semibold cursor-pointer hover:bg-blue-100 whitespace-nowrap" onclick="openPickupModal({{ json_encode($booking) }}, {{ json_encode($booking->car) }})">Ready Pickup</span>
                                    @elseif($uiStatus == 'Pending')
                                        <span class="text-orange-400 font-semibold whitespace-nowrap">Verifikasi</span>
                                    @endif
                                    
                                    <button onclick="openDetailModal({{ json_encode($booking) }}, {{ json_encode($booking->car) }}, '{{ $uiStatus }}')" class="text-[#4A6EB0] hover:text-blue-700 bg-gray-100 p-2 rounded-full transition flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-eye text-sm"></i>
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

    <div id="detailModal" class="fixed inset-0 z-[60] flex items-center justify-center opacity-0 pointer-events-none modal px-4">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="toggleModal('detailModal')"></div>
        <div class="bg-white rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto relative shadow-2xl p-5 md:p-8 z-10 hide-scroll">
            
            <div class="flex justify-between items-center mb-6 sticky top-0 bg-white z-20 pb-2 border-b border-gray-100">
                <h2 class="text-lg md:text-xl font-bold text-[#4A6EB0]">Detail Booking</h2>
                <button onclick="toggleModal('detailModal')" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-xl md:text-2xl p-2"></i></button>
            </div>

            <div class="bg-[#F4F7FC] rounded-xl p-3 md:p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-3 sm:gap-0 border border-gray-100">
                <div class="flex items-center gap-3 md:gap-4 w-full sm:w-auto">
                    <div class="bg-white p-2 rounded-lg shadow-sm shrink-0"><img id="mdl_car_img" src="" class="w-12 h-8 object-contain"></div>
                    <div>
                        <h4 id="mdl_car_name" class="font-bold text-[#1C2C4A] text-sm md:text-base truncate"></h4>
                        <p id="mdl_car_plate" class="text-[10px] md:text-xs text-[#8CA1C4] uppercase"></p>
                    </div>
                </div>
                <span id="mdl_status_badge" class="px-3 py-1 md:px-4 md:py-1 text-[9px] md:text-[10px] rounded-full font-bold shadow-sm text-white shrink-0 self-end sm:self-auto"></span>
            </div>

            <div class="space-y-3 text-xs md:text-sm mb-6 border-b border-gray-100 pb-6">
                <div class="flex justify-between"><span class="text-gray-500 shrink-0 mr-4">Booking Code</span><span id="mdl_code" class="font-bold text-[#4A6EB0] truncate"></span></div>
                <div class="flex justify-between"><span class="text-gray-500 shrink-0 mr-4">User</span><span id="mdl_user" class="font-bold text-[#1C2C4A] truncate text-right"></span></div>
                <div class="flex justify-between"><span class="text-gray-500 shrink-0 mr-4">Phone Number</span><span id="mdl_phone" class="font-bold text-[#1C2C4A] truncate"></span></div>
                <div class="flex justify-between"><span class="text-gray-500 shrink-0 mr-4">Id Number</span><span id="mdl_idnum" class="font-bold text-[#1C2C4A] truncate"></span></div>
                <div class="flex justify-between"><span class="text-gray-500 shrink-0 mr-4">Date Pickup</span><span id="mdl_start" class="font-bold text-[#1C2C4A] truncate"></span></div>
                <div class="flex justify-between"><span class="text-gray-500 shrink-0 mr-4">Return Date</span><span id="mdl_end" class="font-bold text-[#1C2C4A] truncate"></span></div>
                <div class="flex justify-between"><span class="text-gray-500 shrink-0 mr-4">Payment status</span><span id="mdl_paystat" class="font-bold text-green-500 truncate"></span></div>
                <div class="flex justify-between"><span class="text-gray-500 shrink-0 mr-4">Total</span><span id="mdl_total" class="font-bold text-[#4A6EB0] truncate"></span></div>
                <div class="flex justify-between"><span class="text-gray-500 shrink-0 mr-4">Verified</span><span id="mdl_verified" class="font-bold text-[#1C2C4A] truncate"></span></div>
            </div>

            <div id="mdl_return_details_container" class="hidden mb-6 space-y-3 text-xs md:text-sm border-b border-gray-100 pb-6 bg-[#F8FAFC] p-4 rounded-xl">
                <h3 class="font-bold text-[#4A6EB0] text-sm uppercase tracking-wider mb-4 border-b border-gray-200 pb-2">Inspection Return Results</h3>
                
                <div class="grid grid-cols-2 gap-y-2">
                    <span class="text-gray-500">Body & Exterior:</span>
                    <span id="mdl_ret_body" class="font-bold text-[#1C2C4A] text-right"></span>
                    
                    <span class="text-gray-500">Interior:</span>
                    <span id="mdl_ret_interior" class="font-bold text-[#1C2C4A] text-right"></span>
                    
                    <span class="text-gray-500">Tire Condition:</span>
                    <span id="mdl_ret_tire" class="font-bold text-[#1C2C4A] text-right"></span>
                    
                    <span class="text-gray-500">Mileage:</span>
                    <span id="mdl_ret_mileage" class="font-bold text-[#1C2C4A] text-right"></span>
                    
                    <span class="text-gray-500">Delay:</span>
                    <span id="mdl_ret_delay" class="font-bold text-[#1C2C4A] text-right"></span>
                </div>

                <div class="pt-2 mt-2 border-t border-gray-200 grid grid-cols-2 gap-y-2">
                    <span class="text-gray-500">Damage Fine:</span>
                    <span id="mdl_ret_dmg_fine" class="font-bold text-red-500 text-right"></span>
                    
                    <span class="text-gray-500">Late Fine:</span>
                    <span id="mdl_ret_late_fine" class="font-bold text-red-500 text-right"></span>
                    
                    <span class="text-gray-500 font-bold">Total Fine:</span>
                    <span id="mdl_ret_total_fine" class="font-extrabold text-red-500 text-right text-base"></span>
                </div>

                <div class="mt-4">
                    <span class="text-gray-500 block mb-1 text-[10px] uppercase font-bold">General Notes:</span>
                    <p id="mdl_ret_notes" class="text-[#1C2C4A] bg-white p-3 rounded-lg border border-gray-200 italic"></p>
                </div>
            </div>

            <div class="space-y-4 md:space-y-6 mb-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 sm:gap-4">
                    <span class="text-gray-500 text-xs md:text-sm font-semibold">KTP</span>
                    <img id="mdl_doc_ktp" src="" class="w-full sm:w-48 rounded-lg shadow-sm border border-gray-200">
                </div>
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 sm:gap-4">
                    <span class="text-gray-500 text-xs md:text-sm font-semibold">SIM</span>
                    <img id="mdl_doc_sim" src="" class="w-full sm:w-48 rounded-lg shadow-sm border border-gray-200">
                </div>
                <div id="passport_wrapper" class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 sm:gap-4 hidden">
                    <span class="text-gray-500 text-xs md:text-sm font-semibold">Passport</span>
                    <img id="mdl_doc_passport" src="" class="w-full sm:w-48 rounded-lg shadow-sm border border-gray-200">
                </div>
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 sm:gap-4">
                    <span class="text-gray-500 text-xs md:text-sm font-semibold">Selfi</span>
                    <img id="mdl_doc_selfie" src="" class="w-full sm:w-32 rounded-lg shadow-sm border border-gray-200">
                </div>
            </div>

            <div id="mdl_action_buttons" class="flex flex-col sm:flex-row justify-between gap-3 md:gap-4 w-full">
            </div>
        </div>
    </div>

    <div id="pickupModal" class="fixed inset-0 z-[60] flex items-center justify-center opacity-0 pointer-events-none modal px-4">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="toggleModal('pickupModal')"></div>
        <div class="bg-white rounded-2xl w-full max-w-sm md:max-w-md relative shadow-2xl p-6 md:p-8 z-10 hide-scroll">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-[#4A6EB0] font-bold text-base md:text-lg leading-tight">Car Pickup Confirmation</h2>
                <button onclick="toggleModal('pickupModal')" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            <form id="pickupForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-[10px] md:text-xs font-medium text-[#1C2C4A] mb-2 leading-relaxed">Enter the booking code</label>
                    <input type="text" name="booking_code" class="w-full bg-[#F4F7FC] border border-[#B2C5E5] text-[#4A6EB0] font-bold rounded-lg px-4 py-2.5 md:py-3 focus:outline-none text-sm" required>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="toggleModal('pickupModal')" class="w-1/3 py-2.5 md:py-3 rounded-full border border-[#4A6EB0] text-[#4A6EB0] font-bold text-xs md:text-sm">Close</button>
                    <button type="submit" class="w-2/3 py-2.5 md:py-3 rounded-full bg-[#4A6EB0] text-white font-bold text-xs md:text-sm">Confirm Pickup</button>
                </div>
            </form>
        </div>
    </div>

    <div id="returnModal" class="fixed inset-0 z-[70] flex items-center justify-center opacity-0 pointer-events-none modal px-4 py-6">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="toggleModal('returnModal')"></div>
        <div class="bg-white rounded-3xl w-full max-w-2xl relative shadow-2xl p-6 md:p-8 z-10 max-h-[95vh] overflow-y-auto hide-scroll">
            
            <div class="mb-6 pb-4 border-b border-gray-100 flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-bold text-[#5C72A6]">Return Confirmation</h2>
                    <p id="ret_header_subtitle" class="text-xs text-[#8CA1C4] mt-1"></p>
                </div>
                <button onclick="toggleModal('returnModal')" class="text-gray-400 hover:text-red-500"><i class="fa-solid fa-xmark text-2xl"></i></button>
            </div>

            <div class="bg-[#F4F7FC] rounded-2xl p-4 flex justify-between items-center mb-8 border border-[#E2EAF6]">
                <div class="flex items-center gap-4">
                    <div class="bg-white p-2 rounded-xl shadow-sm"><img id="ret_car_img" src="" class="w-16 h-10 object-contain"></div>
                    <div>
                        <h4 id="ret_car_name" class="font-bold text-[#1C2C4A] text-lg"></h4>
                        <p id="ret_car_plate" class="text-[10px] text-[#8CA1C4] uppercase tracking-widest"></p>
                    </div>
                </div>
                <div class="text-right">
                    <h4 id="ret_user_name" class="font-bold text-[#1C2C4A]"></h4>
                    <p id="ret_user_phone" class="text-xs text-[#5C72A6] font-medium"></p>
                </div>
            </div>

            <form id="returnForm" method="POST" action="">
                @csrf
                <div class="mb-6">
                    <label class="block font-bold text-[#1C2C4A] mb-3 text-sm">Body & Exterior Condition</label>
                    <div class="flex gap-3 mb-3">
                        <input type="radio" name="body_condition" id="b_good" value="Good" class="hidden peer" checked>
                        <label for="b_good" class="flex-1 text-center py-2 border border-[#B2C5E5] rounded-lg cursor-pointer text-sm font-medium text-[#5C72A6] peer-checked:bg-[#5C72A6] peer-checked:text-white transition">Good</label>
                        
                        <input type="radio" name="body_condition" id="b_fair" value="Fair" class="hidden peer">
                        <label for="b_fair" class="flex-1 text-center py-2 border border-[#B2C5E5] rounded-lg cursor-pointer text-sm font-medium text-[#5C72A6] peer-checked:bg-[#5C72A6] peer-checked:text-white transition">Fair</label>
                        
                        <input type="radio" name="body_condition" id="b_damage" value="Damaged" class="hidden peer">
                        <label for="b_damage" class="flex-1 text-center py-2 border border-[#B2C5E5] rounded-lg cursor-pointer text-sm font-medium text-[#5C72A6] peer-checked:bg-[#5C72A6] peer-checked:text-white transition">Damaged</label>
                    </div>
                    <textarea name="body_notes" rows="2" class="return-input" placeholder="Note the condition of the car (e.g. scratches on rear mirror)"></textarea>
                </div>

                <div class="mb-8">
                    <label class="block font-bold text-[#1C2C4A] mb-3 text-sm">Interior Condition</label>
                    <div class="flex gap-3 mb-3">
                        <input type="radio" name="interior_condition" id="i_good" value="Good" class="hidden peer" checked>
                        <label for="i_good" class="flex-1 text-center py-2 border border-[#B2C5E5] rounded-lg cursor-pointer text-sm font-medium text-[#5C72A6] peer-checked:bg-[#5C72A6] peer-checked:text-white transition">Good</label>
                        
                        <input type="radio" name="interior_condition" id="i_fair" value="Fair" class="hidden peer">
                        <label for="i_fair" class="flex-1 text-center py-2 border border-[#B2C5E5] rounded-lg cursor-pointer text-sm font-medium text-[#5C72A6] peer-checked:bg-[#5C72A6] peer-checked:text-white transition">Fair</label>
                        
                        <input type="radio" name="interior_condition" id="i_damage" value="Damaged" class="hidden peer">
                        <label for="i_damage" class="flex-1 text-center py-2 border border-[#B2C5E5] rounded-lg cursor-pointer text-sm font-medium text-[#5C72A6] peer-checked:bg-[#5C72A6] peer-checked:text-white transition">Damaged</label>
                    </div>
                    <textarea name="interior_notes" rows="2" class="return-input" placeholder="Note the interior condition"></textarea>
                </div>

                <div class="mb-8">
                    <label class="block font-bold text-[#1C2C4A] mb-3 text-sm">Component Inspection</label>
                    <div class="space-y-2 border border-[#B2C5E5] rounded-xl overflow-hidden bg-[#F8FAFC]">
                        <label class="flex justify-between items-center p-3 border-b border-[#B2C5E5] cursor-pointer hover:bg-white transition">
                            <span class="text-sm text-[#1C2C4A]">There are scratches and dents</span>
                            <input type="checkbox" name="chk_scratches" value="1" class="custom-checkbox">
                        </label>
                        <label class="flex justify-between items-center p-3 border-b border-[#B2C5E5] cursor-pointer hover:bg-white transition">
                            <span class="text-sm text-[#1C2C4A]">The lights are on</span>
                            <input type="checkbox" name="chk_lights" value="1" class="custom-checkbox" checked>
                        </label>
                        <label class="flex justify-between items-center p-3 border-b border-[#B2C5E5] cursor-pointer hover:bg-white transition">
                            <span class="text-sm text-[#1C2C4A]">Complete toolkit</span>
                            <input type="checkbox" name="chk_toolkit" value="1" class="custom-checkbox" checked>
                        </label>
                        <label class="flex justify-between items-center p-3 border-b border-[#B2C5E5] cursor-pointer hover:bg-white transition">
                            <span class="text-sm text-[#1C2C4A]">Spare tire</span>
                            <input type="checkbox" name="chk_sparetire" value="1" class="custom-checkbox" checked>
                        </label>
                        <div class="flex justify-between items-center p-3 bg-white">
                            <span class="text-sm text-[#1C2C4A]">Tire condition</span>
                            <select name="tire_condition" class="border border-[#B2C5E5] text-[#5C72A6] font-medium rounded-md px-3 py-1 outline-none text-sm">
                                <option value="Good">Good</option>
                                <option value="Fair">Fair</option>
                                <option value="Bad">Bad</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-8 space-y-4">
                    <div>
                        <label class="block font-bold text-[#1C2C4A] mb-2 text-sm">Mileage Inspection (KM)</label>
                        <input type="number" name="mileage" class="return-input bg-[#F4F7FC]" placeholder="Example: 4502">
                    </div>
                    <div>
                        <label class="block font-bold text-[#1C2C4A] mb-2 text-sm">Delay (hours)</label>
                        <input type="number" name="delay_hours" class="return-input bg-[#F4F7FC]" placeholder="Example: 4" value="0">
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block font-bold text-[#1C2C4A] mb-3 text-sm">Additional cost</label>
                    <div class="flex gap-4 mb-3">
                        <div class="flex-1">
                            <span class="text-xs text-gray-500 mb-1 block">Damage Fine (Rp)</span>
                            <input type="number" id="inp_damage_fine" name="damage_fine" class="return-input" value="0" oninput="calculateTotalFine()">
                        </div>
                        <div class="flex-1">
                            <span class="text-xs text-gray-500 mb-1 block">Late Fine (Rp)</span>
                            <input type="number" id="inp_late_fine" name="late_fine" class="return-input" value="0" oninput="calculateTotalFine()">
                        </div>
                    </div>
                    <div class="bg-[#B2C5E5] rounded-lg p-4 flex justify-between items-center">
                        <span class="font-bold text-[#1C2C4A]">Total Additional Cost</span>
                        <span id="txt_total_fine" class="font-extrabold text-[#1C2C4A] text-lg">Rp 0</span>
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block font-bold text-[#1C2C4A] mb-2 text-sm">General Notes</label>
                    <textarea name="general_notes" rows="3" class="return-input" placeholder="Type general notes here..."></textarea>
                </div>

                <div class="flex gap-4 pt-4 border-t border-gray-100">
                    <button type="button" onclick="toggleModal('returnModal')" class="w-1/3 py-3 md:py-4 rounded-full border-2 border-[#5C72A6] text-[#5C72A6] font-bold hover:bg-gray-50 transition text-sm md:text-base">Close</button>
                    <button type="submit" class="w-2/3 py-3 md:py-4 rounded-full bg-[#5C72A6] text-white font-bold hover:bg-[#4A5D8A] transition shadow-md text-sm md:text-base">Confirmation</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.remove('opacity-0'), 10);
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('opacity-0');
                setTimeout(() => overlay.classList.add('hidden'), 300);
            }
        }

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

        // Kalkulasi Denda Real-time
        function calculateTotalFine() {
            let dmg = parseFloat(document.getElementById('inp_damage_fine').value) || 0;
            let late = parseFloat(document.getElementById('inp_late_fine').value) || 0;
            document.getElementById('txt_total_fine').innerText = formatRupiah(dmg + late);
        }

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

            document.getElementById('mdl_doc_ktp').src = `/storage/${booking.doc_ktp}`;
            document.getElementById('mdl_doc_sim').src = `/storage/${booking.doc_sim}`;
            document.getElementById('mdl_doc_selfie').src = `/storage/${booking.doc_selfie}`;
            if(booking.doc_passport) {
                document.getElementById('mdl_doc_passport').src = `/storage/${booking.doc_passport}`;
                document.getElementById('passport_wrapper').classList.remove('hidden');
                document.getElementById('passport_wrapper').classList.add('flex');
            } else {
                document.getElementById('passport_wrapper').classList.add('hidden');
                document.getElementById('passport_wrapper').classList.remove('flex');
            }

            const badge = document.getElementById('mdl_status_badge');
            badge.innerText = uiStatus;
            badge.className = "px-3 py-1 md:px-4 md:py-1 text-[9px] md:text-[10px] rounded-full font-bold shadow-sm text-white shrink-0 self-end sm:self-auto"; 
            if(uiStatus == 'Pending') badge.classList.add('bg-orange-300');
            else if(uiStatus == 'Verified') badge.classList.add('bg-pink-300');
            else if(uiStatus == 'Active') badge.classList.add('bg-cyan-300');
            else if(uiStatus == 'Completed') badge.classList.add('bg-green-300');
            else badge.classList.add('bg-gray-400');

            // 🟢 LOGIC MUNCULIN INSPECTION RETURN DETAIL 🟢
            // Bakal ngecek: Kalau statusnya 'Completed' DAN ada isi `body_condition`-nya di database, tampilin div-nya.
            const retContainer = document.getElementById('mdl_return_details_container');
            if(uiStatus === 'Completed' && booking.body_condition) {
                document.getElementById('mdl_ret_body').innerText = booking.body_condition;
                document.getElementById('mdl_ret_interior').innerText = booking.interior_condition || 'Good';
                document.getElementById('mdl_ret_tire').innerText = booking.tire_condition || 'Good';
                document.getElementById('mdl_ret_mileage').innerText = (booking.mileage || '0') + ' KM';
                document.getElementById('mdl_ret_delay').innerText = (booking.delay_hours || '0') + ' Jam';
                document.getElementById('mdl_ret_dmg_fine').innerText = formatRupiah(booking.damage_fine || 0);
                document.getElementById('mdl_ret_late_fine').innerText = formatRupiah(booking.late_fine || 0);
                document.getElementById('mdl_ret_total_fine').innerText = formatRupiah((booking.damage_fine || 0) + (booking.late_fine || 0));
                document.getElementById('mdl_ret_notes').innerText = booking.general_notes || '-';
                
                retContainer.classList.remove('hidden');
            } else {
                retContainer.classList.add('hidden');
            }

            const btnBox = document.getElementById('mdl_action_buttons');
            let closeBtn = `<button type="button" onclick="toggleModal('detailModal')" class="w-full py-2.5 md:py-3 rounded-full border border-[#4A6EB0] text-[#4A6EB0] font-bold hover:bg-gray-50 transition text-sm">Close</button>`;

            if(uiStatus === 'Pending') {
                btnBox.innerHTML = `
                    <form method="POST" action="/admin/bookings/${booking.id}/cancel" class="w-full sm:w-1/3">
                        @csrf
                        <button type="submit" class="w-full py-2.5 md:py-3 rounded-full border border-red-500 text-red-500 font-bold hover:bg-red-50 transition text-sm">Cancel</button>
                    </form>
                    <form method="POST" action="/admin/bookings/${booking.id}/verify" class="w-full sm:w-2/3">
                        @csrf
                        <button type="submit" class="w-full py-2.5 md:py-3 rounded-full bg-[#22C55E] text-white font-bold hover:bg-green-600 transition shadow-md text-sm">Verification</button>
                    </form>
                `;
            } 
            else if (uiStatus === 'Active') {
                btnBox.innerHTML = `
                    ${closeBtn}
                    <button type="button" onclick="openReturnModal(${booking.id}, '${booking.booking_code}', '${car.name}', '${car.license_plate}', '${car.image_path}', '${booking.renter_name}', '${booking.renter_phone}')" class="w-full py-2.5 md:py-3 rounded-full bg-[#4A6EB0] text-white font-bold hover:bg-[#38558A] transition shadow-md text-sm">Return Confirmation</button>
                `;
            }
            else {
                btnBox.innerHTML = closeBtn;
            }

            toggleModal('detailModal');
        }

        // 🟢 BUKA MODAL FORM RETURN 🟢
        function openReturnModal(bookingId, code, carName, plate, carImg, renterName, renterPhone) {
            toggleModal('detailModal');
            
            document.getElementById('ret_header_subtitle').innerText = `${code} | ${carName}`;
            document.getElementById('ret_car_img').src = `/${carImg}`;
            document.getElementById('ret_car_name').innerText = carName;
            document.getElementById('ret_car_plate').innerText = plate;
            document.getElementById('ret_user_name').innerText = renterName;
            document.getElementById('ret_user_phone').innerText = renterPhone;

            document.getElementById('returnForm').action = `/admin/bookings/${bookingId}/return`;

            document.getElementById('inp_damage_fine').value = 0;
            document.getElementById('inp_late_fine').value = 0;
            calculateTotalFine();

            setTimeout(() => {
                toggleModal('returnModal');
            }, 300);
        }

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