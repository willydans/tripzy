<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Admin Tripzy</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #EBF1FA; }
        .sidebar-bg { background: linear-gradient(180deg, #627AA3 0%, #859BBE 100%); }
        .topbar-bg { background: linear-gradient(to right, #B2C5E5 0%, #EBF1FA 50%); }
        .card-shadow { box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); }
        .glass-search { background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.6); }
        .modal { transition: opacity 0.25s ease, transform 0.3s ease; }
        body.modal-active { overflow: hidden; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #B2C5E5; border-radius: 10px; }

        /* Sembunyikan scrollbar untuk elemen tertentu di mobile */
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="flex h-screen text-[#1C2C4A] overflow-hidden relative">

    @if(session('custom_toast'))
    <div id="customToast" class="fixed top-10 left-1/2 transform -translate-x-1/2 z-[100] transition-all duration-500 ease-in-out w-11/12 md:w-auto">
        <div class="bg-[#5978B4] text-white px-6 md:px-10 py-5 md:py-6 rounded-2xl shadow-2xl flex flex-col items-center gap-3 md:gap-4 relative w-full md:min-w-[400px]">
            <button onclick="closeToast()" class="absolute top-4 right-4 text-white/80 hover:text-white"><i class="fa-solid fa-xmark text-lg md:text-xl"></i></button>
            <div class="w-12 h-12 md:w-16 md:h-16 bg-[#22C55E] rounded-full flex items-center justify-center text-white text-2xl md:text-3xl shadow-inner">
                <i class="fa-solid fa-check"></i>
            </div>
            <h3 class="text-base md:text-xl font-bold text-center tracking-wide">{{ session('custom_toast') }}</h3>
        </div>
    </div>
    <script>
        setTimeout(() => closeToast(), 4000);
        function closeToast() {
            const toast = document.getElementById('customToast');
            if(toast) { toast.style.opacity = '0'; toast.style.transform = 'translate(-50%, -20px)'; setTimeout(() => toast.remove(), 500); }
        }
    </script>
    @endif

    @if(session('error'))
    <div id="errorToast" class="fixed top-10 left-1/2 transform -translate-x-1/2 z-[100] transition-all duration-500 ease-in-out w-11/12 md:w-auto">
        <div class="bg-white border-l-4 border-red-500 px-6 md:px-10 py-5 md:py-6 rounded-2xl shadow-2xl flex flex-col items-center gap-3 md:gap-4 relative w-full md:min-w-[400px]">
            <button onclick="closeErrorToast()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-lg md:text-xl"></i></button>
            <div class="w-12 h-12 md:w-16 md:h-16 bg-red-100 rounded-full flex items-center justify-center text-red-500 text-2xl md:text-3xl shadow-inner">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h3 class="text-base md:text-xl font-bold text-center tracking-wide text-red-600">{{ session('error') }}</h3>
        </div>
    </div>
    <script>
        setTimeout(() => closeErrorToast(), 4000);
        function closeErrorToast() {
            const toast = document.getElementById('errorToast');
            if(toast) { toast.style.opacity = '0'; toast.style.transform = 'translate(-50%, -20px)'; setTimeout(() => toast.remove(), 500); }
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
            <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg font-medium opacity-80 transition">Booking Verification</a>
            <a href="{{ route('admin.catalog.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg font-medium opacity-80 transition">Manage Catalog</a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 bg-white/20 rounded-lg font-semibold border border-white/30 backdrop-blur-sm shadow-sm transition">User Management</a>
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
            
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 md:mb-8 gap-4 lg:gap-0">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-[#4A6EB0] mb-1">User Management</h2>
                    <p class="text-[#6B85B5] text-xs md:text-sm font-medium">Manage users, verify KYC, and blacklist</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                    <div class="bg-[#E2EAF6] border border-[#B2C5E5] text-[#4A6EB0] px-4 md:px-6 py-2.5 rounded-xl font-bold text-xs md:text-sm shadow-sm text-center flex-1 lg:flex-none">
                        {{ $kycPending }} KYC Pending
                    </div>
                    <div class="bg-[#FEE2E2] border border-[#FCA5A5] text-[#EF4444] px-4 md:px-6 py-2.5 rounded-xl font-bold text-xs md:text-sm shadow-sm text-center flex-1 lg:flex-none">
                        {{ $blacklistCount }} Blacklist
                    </div>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-3 md:gap-4 mb-6">
                <div class="relative flex-grow w-full lg:max-w-md">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 transform -translate-y-1/2 text-[#8CA1C4]"></i>
                    <input type="text" placeholder="Search...." class="w-full bg-[#DCE4F2] text-[#4A6EB0] placeholder-[#8CA1C4] rounded-full py-2.5 pl-12 pr-4 focus:outline-none text-sm">
                </div>
                <div class="relative w-full md:w-auto">
                    <select class="w-full md:w-48 bg-[#DCE4F2] text-[#4A6EB0] text-sm md:text-base font-medium rounded-full py-2.5 px-6 appearance-none pr-10 outline-none">
                        <option>All Statuses</option>
                        <option>Active</option>
                        <option>Pending</option>
                        <option>Blacklist</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-[#8CA1C4] pointer-events-none"></i>
                </div>
            </div>

            <div class="bg-white rounded-2xl card-shadow overflow-hidden border border-gray-100 p-2 md:p-4 w-full overflow-x-auto">
                <table class="w-full min-w-[700px] text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-white text-[10px] md:text-[11px] uppercase tracking-widest text-black font-bold border-b border-gray-100">
                            <th class="py-3 px-3 md:py-4 md:px-4">USER</th>
                            <th class="py-3 px-3 md:py-4 md:px-4">CONTACT</th>
                            <th class="py-3 px-3 md:py-4 md:px-4">STATUS</th>
                            <th class="py-3 px-3 md:py-4 md:px-4">CAR RENT</th>
                            <th class="py-3 px-3 md:py-4 md:px-4 text-center">ACTION</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs md:text-sm">
                        @foreach($users as $user)
                        @php
                            // Set badge color
                            $statusStr = $user->status ?? 'Pending';
                            $badgeColor = '';
                            if($statusStr == 'Active' || $statusStr == 'Aktif') { $badgeColor = 'bg-green-100 text-green-600'; $statusStr = 'Active'; }
                            elseif($statusStr == 'Blacklist') { $badgeColor = 'bg-red-400 text-white'; }
                            else { $badgeColor = 'bg-orange-100 text-orange-600'; $statusStr = 'Pending'; }
                        @endphp
                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                            <td class="py-3 px-3 md:px-4 flex items-center gap-3">
                                <div class="w-8 h-8 md:w-10 md:h-10 bg-[#1C2C4A] rounded-full flex items-center justify-center text-white overflow-hidden shrink-0 shadow-sm">
                                    @if($user->profile_photo)
                                        <img src="{{ asset('storage/'.$user->profile_photo) }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="fa-solid fa-user text-xs md:text-sm"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-[#1C2C4A] text-xs md:text-sm">{{ $user->name }}</p>
                                    <p class="text-[9px] md:text-[10px] text-[#8CA1C4]">Bergabung {{ $user->created_at->format('Y-m-d') }}</p>
                                </div>
                            </td>
                            <td class="py-3 px-3 md:px-4">
                                <p class="font-medium text-[#1C2C4A] text-[11px] md:text-xs">{{ $user->email }}</p>
                                <p class="text-[9px] md:text-[10px] text-[#8CA1C4]">{{ $user->nomor_hp ?? '-' }}</p>
                            </td>
                            <td class="py-3 px-3 md:px-4">
                                <span class="{{ $badgeColor }} px-3 py-1 text-[9px] md:text-[10px] rounded-full font-bold shadow-sm">{{ $statusStr }}</span>
                            </td>
                            <td class="py-3 px-3 md:px-4">
                                <p class="font-bold text-[#4A6EB0]">{{ $user->bookings_count }}x</p>
                            </td>
                            <td class="py-3 px-3 md:px-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="openDetailModal({{ json_encode($user) }}, '{{ $statusStr }}')" class="text-[#4A6EB0] hover:text-blue-700 bg-[#EBF1FA] w-7 h-7 md:w-8 md:h-8 rounded-full transition shadow-sm flex items-center justify-center">
                                        <i class="fa-solid fa-eye text-[10px] md:text-xs"></i>
                                    </button>
                                    
                                    @if($statusStr != 'Blacklist' && $user->role != 'admin')
                                    <button onclick="openBlacklistModal({{ $user->id }}, '{{ addslashes($user->name) }}')" class="text-red-500 hover:text-red-700 bg-red-50 w-7 h-7 md:w-8 md:h-8 rounded-full transition shadow-sm flex items-center justify-center border border-red-100">
                                        <i class="fa-regular fa-trash-can text-[10px] md:text-xs"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </main>
        
        <footer class="mt-auto pt-8 pb-6 px-4 md:px-8 flex flex-col md:flex-row justify-between items-center md:items-end gap-6 md:gap-0 opacity-60 text-center md:text-left">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold font-bebas tracking-widest text-[#4A6EB0] mb-2">Tripzy</h2>
                <div class="flex space-x-4 text-lg md:text-xl text-[#4A6EB0] justify-center md:justify-start">
                    <i class="fa-brands fa-discord"></i>
                    <i class="fa-brands fa-whatsapp"></i>
                    <i class="fa-brands fa-telegram"></i>
                    <i class="fa-brands fa-instagram"></i>
                </div>
            </div>
            <div>
                <h3 class="text-base md:text-lg font-bold text-[#4A6EB0] mb-2">Contact Us</h3>
                <ul class="space-y-1 text-[9px] md:text-[10px] font-medium text-[#4A6EB0]">
                    <li><i class="fa-solid fa-location-dot"></i> Jl. Prof. Dr. Ir. Sumantri Brojonegoro</li>
                    <li><i class="fa-solid fa-phone"></i> +6285278139801</li>
                    <li><i class="fa-solid fa-envelope"></i> tripzy@gmail.com</li>
                </ul>
            </div>
        </footer>
    </div>

    <div id="userDetailModal" class="fixed inset-0 z-[60] flex items-center justify-center opacity-0 pointer-events-none modal px-4">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="toggleModal('userDetailModal')"></div>
        <div class="bg-white rounded-2xl w-full max-w-sm md:max-w-md relative shadow-2xl z-10 flex flex-col p-6 md:p-8 max-h-[90vh] overflow-y-auto hide-scroll">
            
            <div class="flex justify-between items-center mb-4 sticky top-0 bg-white z-20 pb-2 border-b border-gray-100">
                <h2 class="text-[#6B85B5] font-bold text-base md:text-lg">User Details</h2>
                <button onclick="toggleModal('userDetailModal')" class="text-[#6B85B5] hover:text-gray-600"><i class="fa-solid fa-xmark text-lg md:text-xl"></i></button>
            </div>

            <div class="text-center mb-6 md:mb-8 mt-2">
                <div class="w-16 h-16 md:w-20 md:h-20 bg-[#DCE4F2] text-[#1C2C4A] rounded-full mx-auto flex items-center justify-center text-3xl md:text-4xl mb-3 shadow-inner overflow-hidden">
                    <img id="mdl_user_avatar" src="" class="w-full h-full object-cover hidden">
                    <i id="mdl_user_icon" class="fa-solid fa-user"></i>
                </div>
                <h3 id="mdl_user_name" class="text-lg md:text-xl font-bold text-[#1C2C4A] truncate"></h3>
                <p id="mdl_user_email" class="text-xs md:text-sm text-gray-600 truncate"></p>
            </div>

            <div class="space-y-3 md:space-y-4 text-xs md:text-sm font-medium text-[#1C2C4A] mb-8">
                <div class="flex justify-between border-b border-gray-50 pb-2">
                    <span class="text-gray-600">Phone Number</span>
                    <span id="mdl_user_phone" class="font-bold"></span>
                </div>
                <div class="flex justify-between border-b border-gray-50 pb-2">
                    <span class="text-gray-600">Joined</span>
                    <span id="mdl_user_joined" class="font-bold"></span>
                </div>
                <div class="flex justify-between border-b border-gray-50 pb-2">
                    <span class="text-gray-600">Date of Birth</span>
                    <span id="mdl_user_dob" class="font-bold"></span>
                </div>
                <div class="flex justify-between border-b border-gray-50 pb-2">
                    <span class="text-gray-600">Gender</span>
                    <span id="mdl_user_gender" class="font-bold"></span>
                </div>
                <div class="flex justify-between border-b border-gray-50 pb-2">
                    <span class="text-gray-600">Account Status</span>
                    <span id="mdl_user_status" class="font-bold"></span>
                </div>
                <div class="flex justify-between border-b border-gray-50 pb-2">
                    <span class="text-gray-600">Total Rentals</span>
                    <span id="mdl_user_rentals" class="font-bold"></span>
                </div>
            </div>

            <form id="verifyForm" method="POST" class="flex flex-col sm:flex-row gap-3 md:gap-4 mt-auto">
                @csrf
                <button type="button" onclick="toggleModal('userDetailModal')" class="w-full sm:w-1/3 py-2.5 md:py-3 rounded-xl md:rounded-2xl border border-[#6B85B5] text-[#6B85B5] font-bold hover:bg-gray-50 transition shadow-sm text-sm">
                    Close
                </button>
                <button type="submit" id="btnVerify" class="w-full sm:w-2/3 py-2.5 md:py-3 rounded-xl md:rounded-2xl bg-[#22C55E] text-white font-bold hover:bg-green-600 transition shadow-md text-sm">
                    Account Verification
                </button>
            </form>
        </div>
    </div>

    <div id="blacklistModal" class="fixed inset-0 z-[60] flex items-center justify-center opacity-0 pointer-events-none modal px-4">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="toggleModal('blacklistModal')"></div>
        <div class="bg-white rounded-3xl w-full max-w-sm md:max-w-lg relative shadow-2xl z-10 flex flex-col p-6 md:p-10 text-center">
            
            <div class="w-16 h-16 md:w-20 md:h-20 bg-[#FEE2E2] text-[#EF4444] rounded-full mx-auto flex items-center justify-center text-2xl md:text-3xl mb-4 md:mb-6 shadow-sm">
                <i class="fa-regular fa-trash-can"></i>
            </div>

            <h2 class="text-2xl md:text-3xl font-bold text-[#6B85B5] mb-2 md:mb-4">Blacklist User?</h2>
            <p class="text-[#8CA1C4] text-sm md:text-lg mb-8 md:mb-10 leading-relaxed px-2 md:px-4">
                Are you sure you want to blacklist <span id="blacklistUserName" class="font-bold text-[#4A6EB0]"></span>? This user will not be able to rent again.
            </p>

            <form id="blacklistForm" method="POST" class="flex flex-col sm:flex-row gap-3 md:gap-4 px-2 md:px-4">
                @csrf
                <button type="button" onclick="toggleModal('blacklistModal')" class="w-full sm:w-1/2 py-3 md:py-4 rounded-xl md:rounded-2xl border border-[#6B85B5] text-[#6B85B5] font-bold text-sm md:text-xl hover:bg-gray-50 transition shadow-sm">
                    Cancel
                </button>
                <button type="submit" class="w-full sm:w-1/2 py-3 md:py-4 rounded-xl md:rounded-2xl bg-[#FF8A8A] text-white font-bold text-sm md:text-xl hover:bg-red-500 transition shadow-md">
                    Blacklist
                </button>
            </form>
        </div>
    </div>

    <script>
        // Toggle Sidebar Mobile
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

        // Modal Logic
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

        // Setup Info Detail User
        function openDetailModal(user, statusStr) {
            document.getElementById('mdl_user_name').innerText = user.name;
            document.getElementById('mdl_user_email').innerText = user.email;
            document.getElementById('mdl_user_phone').innerText = user.nomor_hp || '-';
            document.getElementById('mdl_user_joined').innerText = user.created_at ? user.created_at.split('T')[0] : '-';
            document.getElementById('mdl_user_dob').innerText = user.tanggal_lahir ? user.tanggal_lahir.split('T')[0] : '-';
            document.getElementById('mdl_user_gender').innerText = user.jenis_kelamin || 'Male'; 
            document.getElementById('mdl_user_rentals').innerText = user.bookings_count + 'x';
            
            const statEl = document.getElementById('mdl_user_status');
            statEl.innerText = statusStr;
            if(statusStr === 'Active') { statEl.className = 'font-bold text-[#22C55E]'; }
            else if(statusStr === 'Blacklist') { statEl.className = 'font-bold text-red-500'; }
            else { statEl.className = 'font-bold text-orange-500'; }

            const verifyForm = document.getElementById('verifyForm');
            const btnVerify = document.getElementById('btnVerify');
            verifyForm.action = `/admin/users/${user.id}/verify`;
            
            if(statusStr === 'Active' || statusStr === 'Blacklist') {
                btnVerify.style.display = 'none';
            } else {
                btnVerify.style.display = 'block';
            }

            if(user.profile_photo) {
                document.getElementById('mdl_user_avatar').src = '/storage/' + user.profile_photo;
                document.getElementById('mdl_user_avatar').classList.remove('hidden');
                document.getElementById('mdl_user_icon').classList.add('hidden');
            } else {
                document.getElementById('mdl_user_avatar').classList.add('hidden');
                document.getElementById('mdl_user_icon').classList.remove('hidden');
            }

            toggleModal('userDetailModal');
        }

        function openBlacklistModal(userId, userName) {
            document.getElementById('blacklistUserName').innerText = userName;
            document.getElementById('blacklistForm').action = `/admin/users/${userId}/blacklist`;
            toggleModal('blacklistModal');
        }
    </script>
</body>
</html>