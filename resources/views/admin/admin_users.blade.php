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
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }

        /* Form Custom Input */
        .form-label { display: block; font-size: 0.875rem; font-weight: 700; color: #6B85B5; margin-bottom: 0.5rem; }
        .form-input {
            width: 100%; border: 1px solid #B2C5E5; border-radius: 0.75rem; padding: 0.75rem 1rem;
            font-size: 0.875rem; color: #1C2C4A; background-color: white; transition: all 0.3s ease;
        }
        .form-input:focus { outline: none; border-color: #5C72A6; box-shadow: 0 0 0 3px rgba(92, 114, 166, 0.1); }
        select.form-input {
            appearance: none;
            background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%236B85B5%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E');
            background-repeat: no-repeat; background-position: right 1rem center; background-size: 0.65em auto;
        }
    </style>
</head>
<body class="flex h-screen text-[#1C2C4A] overflow-hidden relative">

    @if(session('custom_toast') || session('success'))
    <div id="customToast" class="fixed top-10 left-1/2 transform -translate-x-1/2 z-[100] transition-all duration-500 ease-in-out w-11/12 md:w-auto">
        <div class="bg-[#5978B4] text-white px-6 md:px-10 py-5 md:py-6 rounded-2xl shadow-2xl flex flex-col items-center gap-3 md:gap-4 relative w-full md:min-w-[400px]">
            <button onclick="closeToast()" class="absolute top-4 right-4 text-white/80 hover:text-white"><i class="fa-solid fa-xmark text-lg md:text-xl"></i></button>
            <div class="w-12 h-12 md:w-16 md:h-16 bg-[#22C55E] rounded-full flex items-center justify-center text-white text-2xl md:text-3xl shadow-inner">
                <i class="fa-solid fa-check"></i>
            </div>
            <h3 class="text-base md:text-xl font-bold text-center tracking-wide">{{ session('custom_toast') ?? session('success') }}</h3>
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

    @if(session('error') || $errors->any())
    <div id="errorToast" class="fixed top-10 left-1/2 transform -translate-x-1/2 z-[100] transition-all duration-500 ease-in-out w-11/12 md:w-auto">
        <div class="bg-white border-l-4 border-red-500 px-6 md:px-10 py-5 md:py-6 rounded-2xl shadow-2xl flex flex-col items-center gap-3 md:gap-4 relative w-full md:min-w-[400px]">
            <button onclick="closeErrorToast()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-lg md:text-xl"></i></button>
            <div class="w-12 h-12 md:w-16 md:h-16 bg-red-100 rounded-full flex items-center justify-center text-red-500 text-2xl md:text-3xl shadow-inner">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h3 class="text-base md:text-sm font-bold text-center tracking-wide text-red-600">
                {{ session('error') ?? $errors->first() }}
            </h3>
        </div>
    </div>
    <script>
        setTimeout(() => closeErrorToast(), 5000);
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
                    <input type="text" placeholder="Search...." class="w-full glass-search text-white placeholder-white rounded-full py-2 md:py-2.5 pl-10 md:pl-12 pr-4 focus:outline-none focus:bg-white/30 transition shadow-inner text-sm md:text-base pointer-events-none opacity-50" readonly>
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
                
                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto items-center">
                    <div class="bg-[#E2EAF6] border border-[#B2C5E5] text-[#4A6EB0] px-4 py-2.5 rounded-full font-bold text-xs md:text-sm shadow-sm">
                        {{ $kycPending }} KYC Pending
                    </div>
                    <div class="bg-[#FEE2E2] border border-[#FCA5A5] text-[#EF4444] px-4 py-2.5 rounded-full font-bold text-xs md:text-sm shadow-sm">
                        {{ $blacklistCount }} Blacklisted
                    </div>
                    <button onclick="toggleModal('addUserModal')" class="w-full sm:w-auto justify-center bg-[#5C72A6] hover:bg-[#4A5D8A] text-white font-semibold py-2.5 px-6 rounded-full shadow-md transition flex items-center gap-2 text-sm md:text-base">
                        <i class="fa-solid fa-user-plus"></i> Add User
                    </button>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-3 md:gap-4 mb-6">
                <div class="relative flex-grow w-full lg:max-w-md">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 transform -translate-y-1/2 text-[#8CA1C4]"></i>
                    <input type="text" id="searchInput" placeholder="Search by Name or Email...." class="w-full bg-[#DCE4F2] text-[#4A6EB0] placeholder-[#8CA1C4] rounded-full py-2.5 pl-12 pr-4 focus:outline-none text-sm">
                </div>
                <div class="relative w-full md:w-auto">
                    <select id="statusFilter" class="w-full md:w-48 bg-[#DCE4F2] text-[#4A6EB0] text-sm md:text-base font-medium rounded-full py-2.5 px-6 appearance-none pr-10 outline-none cursor-pointer">
                        <option value="All">All Statuses</option>
                        <option value="Active">Active</option>
                        <option value="Pending">Pending</option>
                        <option value="Blacklist">Blacklist</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-[#8CA1C4] pointer-events-none"></i>
                </div>
            </div>

            <div class="bg-white rounded-2xl card-shadow overflow-hidden border border-gray-100 p-2 md:p-4 w-full overflow-x-auto">
                <table class="w-full min-w-[750px] text-left border-collapse whitespace-nowrap" id="userTable">
                    <thead>
                        <tr class="bg-white text-[10px] md:text-[11px] uppercase tracking-widest text-black font-bold border-b border-gray-100">
                            <th class="py-3 px-3 md:py-4 md:px-4">USER</th>
                            <th class="py-3 px-3 md:py-4 md:px-4">CONTACT</th>
                            <th class="py-3 px-3 md:py-4 md:px-4">ROLE</th>
                            <th class="py-3 px-3 md:py-4 md:px-4">STATUS</th>
                            <th class="py-3 px-3 md:py-4 md:px-4 text-center">ACTION</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs md:text-sm">
                        @foreach($users as $user)
                        @php
                            $statusStr = $user->status ?? 'Pending';
                            $badgeColor = '';
                            if($statusStr == 'Active' || $statusStr == 'Aktif') { $badgeColor = 'bg-green-100 text-green-600'; $statusStr = 'Active'; }
                            elseif($statusStr == 'Blacklist') { $badgeColor = 'bg-red-400 text-white'; }
                            else { $badgeColor = 'bg-orange-100 text-orange-600'; $statusStr = 'Pending'; }
                            
                            $searchString = strtolower($user->name . ' ' . $user->email);
                        @endphp
                        
                        <tr class="user-row border-b border-gray-50 hover:bg-gray-50 transition"
                            data-status="{{ $statusStr }}" 
                            data-search="{{ $searchString }}">
                            
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
                                    <p class="text-[9px] md:text-[10px] text-[#8CA1C4]">Gabung {{ $user->created_at->format('d M Y') }}</p>
                                </div>
                            </td>
                            <td class="py-3 px-3 md:px-4">
                                <p class="font-medium text-[#1C2C4A] text-[11px] md:text-xs">{{ $user->email }}</p>
                                <p class="text-[9px] md:text-[10px] text-[#8CA1C4]">{{ $user->nomor_hp ?? '-' }}</p>
                            </td>
                            <td class="py-3 px-3 md:px-4">
                                <span class="font-bold uppercase tracking-wider text-[10px] {{ $user->role == 'admin' ? 'text-[#4A6EB0]' : 'text-gray-500' }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="py-3 px-3 md:px-4">
                                <span class="{{ $badgeColor }} px-3 py-1 text-[9px] md:text-[10px] rounded-full font-bold shadow-sm">{{ $statusStr }}</span>
                            </td>
                            <td class="py-3 px-3 md:px-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="openDetailModal({{ json_encode($user) }}, '{{ $statusStr }}')" class="text-[#4A6EB0] hover:text-white bg-[#EBF1FA] hover:bg-[#4A6EB0] w-7 h-7 md:w-8 md:h-8 rounded-full transition shadow-sm flex items-center justify-center">
                                        <i class="fa-solid fa-eye text-[10px] md:text-xs"></i>
                                    </button>
                                    
                                    <button onclick="openEditModal({{ json_encode($user) }})" class="text-orange-500 hover:text-white bg-orange-50 hover:bg-orange-500 w-7 h-7 md:w-8 md:h-8 rounded-full transition shadow-sm flex items-center justify-center border border-orange-100">
                                        <i class="fa-solid fa-pen text-[10px] md:text-xs"></i>
                                    </button>

                                    @if($user->id !== Auth::id()) @if($statusStr == 'Blacklist')
                                            <button onclick="openToggleBlacklistModal({{ $user->id }}, '{{ addslashes($user->name) }}', 'unblacklist')" class="text-green-600 hover:text-white bg-green-50 hover:bg-green-500 w-7 h-7 md:w-8 md:h-8 rounded-full transition shadow-sm flex items-center justify-center border border-green-100" title="Unblacklist User">
                                                <i class="fa-solid fa-user-check text-[10px] md:text-xs"></i>
                                            </button>
                                        @else
                                            <button onclick="openToggleBlacklistModal({{ $user->id }}, '{{ addslashes($user->name) }}', 'blacklist')" class="text-gray-500 hover:text-white bg-gray-100 hover:bg-gray-600 w-7 h-7 md:w-8 md:h-8 rounded-full transition shadow-sm flex items-center justify-center border border-gray-200" title="Blacklist User">
                                                <i class="fa-solid fa-user-slash text-[10px] md:text-xs"></i>
                                            </button>
                                        @endif

                                        <button onclick="openDeleteModal({{ $user->id }}, '{{ addslashes($user->name) }}')" class="text-red-500 hover:text-white bg-red-50 hover:bg-red-500 w-7 h-7 md:w-8 md:h-8 rounded-full transition shadow-sm flex items-center justify-center border border-red-100" title="Delete User">
                                            <i class="fa-regular fa-trash-can text-[10px] md:text-xs"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div id="noResultMsg" class="hidden text-center py-8 text-gray-400 font-medium w-full">
                    <i class="fa-solid fa-users-slash text-3xl mb-2 opacity-50"></i><br>
                    User tidak ditemukan
                </div>
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

    <div id="addUserModal" class="fixed inset-0 z-[60] flex items-center justify-center opacity-0 pointer-events-none modal px-4 py-6">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="toggleModal('addUserModal')"></div>
        <div class="bg-white rounded-3xl w-full max-w-3xl max-h-[95vh] overflow-y-auto relative shadow-2xl p-6 md:p-10 z-10 hide-scroll border border-gray-100">
            
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-[#5C72A6] rounded-full flex items-center justify-center text-white shrink-0 shadow-md">
                        <i class="fa-solid fa-user-plus text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-[#5C72A6]">Add New User</h2>
                        <p class="text-xs md:text-sm text-[#8CA1C4]">Create new account manually</p>
                    </div>
                </div>
                <button onclick="toggleModal('addUserModal')" class="text-[#8CA1C4] hover:text-red-500 text-2xl p-2 transition"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <form action="{{ route('admin.users.store') ?? '#' }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5 mb-8">
                    <div>
                        <label class="form-label">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" class="form-input" required placeholder="User full name">
                    </div>
                    <div>
                        <label class="form-label">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" class="form-input" required placeholder="user@gmail.com">
                    </div>
                    
                    <div>
                        <label class="form-label">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" class="form-input" required placeholder="Min 6 character">
                    </div>
                    <div>
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="nomor_hp" class="form-input" placeholder="08xxxx" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>

                    <div>
                        <label class="form-label">Role <span class="text-red-500">*</span></label>
                        <select name="role" class="form-input" required>
                            <option value="user">User / Customer</option>
                            <option value="admin">Administrator</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Account Status <span class="text-red-500">*</span></label>
                        <select name="status" class="form-input" required>
                            <option value="Active">Active</option>
                            <option value="Pending">Pending</option>
                            <option value="Blacklist">Blacklist</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2 border-t border-gray-100 pt-4 mt-2">
                        <p class="text-sm font-bold text-[#8CA1C4] mb-4 uppercase tracking-wider">Additional Information (Optional)</p>
                    </div>

                    <div>
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="tanggal_lahir" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Gender</label>
                        <select name="jenis_kelamin" class="form-input">
                            <option value="">Pilih Kelamin</option>
                            <option value="Pria">Pria</option>
                            <option value="Wanita">Wanita</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">City</label>
                        <input type="text" name="kota" class="form-input" placeholder="Bandar Lampung">
                    </div>
                    <div>
                        <label class="form-label">Address</label>
                        <input type="text" name="alamat" class="form-input" placeholder="Full address">
                    </div>
                    
                    <div>
                        <label class="form-label">Jenis SIM</label>
                        <select name="jenis_sim" class="form-input">
                            <option value="">Pilih SIM</option>
                            <option value="SIM A">SIM A</option>
                            <option value="SIM C">SIM C</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Nomor SIM</label>
                        <input type="text" name="nomor_sim" class="form-input" placeholder="Nomor SIM" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>
                    <div>
                        <label class="form-label">Masa Berlaku SIM</label>
                        <input type="date" name="masa_berlaku_sim" class="form-input">
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-end gap-4 border-t border-gray-100 pt-6">
                    <button type="button" onclick="toggleModal('addUserModal')" class="w-full sm:w-auto px-10 py-3 rounded-full border-2 border-[#6B85B5] text-[#6B85B5] font-bold hover:bg-[#F4F7FC] transition text-sm">Cancel</button>
                    <button type="submit" class="w-full sm:w-auto px-12 py-3 rounded-full bg-[#5C72A6] text-white font-bold hover:bg-[#4A5D8A] transition shadow-lg text-sm">Save User</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editUserModal" class="fixed inset-0 z-[60] flex items-center justify-center opacity-0 pointer-events-none modal px-4 py-6">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="toggleModal('editUserModal')"></div>
        <div class="bg-white rounded-3xl w-full max-w-3xl max-h-[95vh] overflow-y-auto relative shadow-2xl p-6 md:p-10 z-10 hide-scroll border border-gray-100">
            
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-orange-100 text-orange-500 rounded-full flex items-center justify-center shrink-0 shadow-sm border border-orange-200">
                        <i class="fa-solid fa-pen text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-[#1C2C4A]">Edit User</h2>
                        <p class="text-xs md:text-sm text-[#8CA1C4]">Update account information</p>
                    </div>
                </div>
                <button onclick="toggleModal('editUserModal')" class="text-[#8CA1C4] hover:text-red-500 text-2xl p-2 transition"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5 mb-8">
                    <div>
                        <label class="form-label">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_name" name="name" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" id="edit_email" name="email" class="form-input" required>
                    </div>
                    
                    <div>
                        <label class="form-label">Password <span class="text-xs font-normal text-gray-400">(Kosongkan jika tidak diubah)</span></label>
                        <input type="password" name="password" class="form-input" placeholder="Isi untuk mengganti password">
                    </div>
                    <div>
                        <label class="form-label">Phone Number</label>
                        <input type="text" id="edit_phone" name="nomor_hp" class="form-input" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>

                    <div>
                        <label class="form-label">Role <span class="text-red-500">*</span></label>
                        <select id="edit_role" name="role" class="form-input" required>
                            <option value="user">User / Customer</option>
                            <option value="admin">Administrator</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Account Status <span class="text-red-500">*</span></label>
                        <select id="edit_status" name="status" class="form-input" required>
                            <option value="Active">Active</option>
                            <option value="Pending">Pending</option>
                            <option value="Blacklist">Blacklist</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2 border-t border-gray-100 pt-4 mt-2">
                        <p class="text-sm font-bold text-[#8CA1C4] mb-4 uppercase tracking-wider">Additional Information</p>
                    </div>

                    <div>
                        <label class="form-label">Date of Birth</label>
                        <input type="date" id="edit_dob" name="tanggal_lahir" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Gender</label>
                        <select id="edit_gender" name="jenis_kelamin" class="form-input">
                            <option value="">Pilih Kelamin</option>
                            <option value="Pria">Pria</option>
                            <option value="Wanita">Wanita</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">City</label>
                        <input type="text" id="edit_city" name="kota" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Address</label>
                        <input type="text" id="edit_address" name="alamat" class="form-input">
                    </div>
                    
                    <div>
                        <label class="form-label">Jenis SIM</label>
                        <select id="edit_sim_type" name="jenis_sim" class="form-input">
                            <option value="">Pilih SIM</option>
                            <option value="SIM A">SIM A</option>
                            <option value="SIM C">SIM C</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Nomor SIM</label>
                        <input type="text" id="edit_sim_number" name="nomor_sim" class="form-input" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>
                    <div>
                        <label class="form-label">Masa Berlaku SIM</label>
                        <input type="date" id="edit_sim_expire" name="masa_berlaku_sim" class="form-input">
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-end gap-4 border-t border-gray-100 pt-6">
                    <button type="button" onclick="toggleModal('editUserModal')" class="w-full sm:w-auto px-10 py-3 rounded-full border-2 border-[#6B85B5] text-[#6B85B5] font-bold hover:bg-[#F4F7FC] transition text-sm">Cancel</button>
                    <button type="submit" class="w-full sm:w-auto px-12 py-3 rounded-full bg-orange-500 text-white font-bold hover:bg-orange-600 transition shadow-lg text-sm">Update User</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteUserModal" class="fixed inset-0 z-[60] flex items-center justify-center opacity-0 pointer-events-none modal px-4">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="toggleModal('deleteUserModal')"></div>
        <div class="bg-white rounded-3xl w-full max-w-sm md:max-w-md relative shadow-2xl z-10 flex flex-col p-6 md:p-8 text-center border border-gray-100">
            <div class="w-16 h-16 bg-red-100 text-red-500 rounded-full mx-auto flex items-center justify-center text-3xl mb-4 shadow-sm border border-red-200">
                <i class="fa-regular fa-trash-can"></i>
            </div>
            <h2 class="text-2xl font-bold text-[#1C2C4A] mb-2">Delete User?</h2>
            <p class="text-[#8CA1C4] text-sm mb-8 leading-relaxed">
                Are you sure you want to completely delete <br><strong id="del_user_name" class="text-red-500"></strong>?<br>This action cannot be undone.
            </p>
            <form id="deleteUserForm" method="POST" class="flex gap-3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="toggleModal('deleteUserModal')" class="w-1/2 py-3 rounded-xl border border-[#6B85B5] text-[#6B85B5] font-bold hover:bg-gray-50 transition shadow-sm">Cancel</button>
                <button type="submit" class="w-1/2 py-3 rounded-xl bg-red-500 text-white font-bold hover:bg-red-600 transition shadow-md">Delete</button>
            </form>
        </div>
    </div>

    <div id="toggleBlacklistModal" class="fixed inset-0 z-[60] flex items-center justify-center opacity-0 pointer-events-none modal px-4">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="toggleModal('toggleBlacklistModal')"></div>
        <div class="bg-white rounded-3xl w-full max-w-sm md:max-w-md relative shadow-2xl z-10 flex flex-col p-6 md:p-8 text-center border border-gray-100">
            
            <div id="tbl_icon_box" class="w-16 h-16 rounded-full mx-auto flex items-center justify-center text-3xl mb-4 shadow-sm border">
                <i id="tbl_icon" class=""></i>
            </div>

            <h2 id="tbl_title" class="text-2xl font-bold mb-2"></h2>
            <p id="tbl_desc" class="text-[#8CA1C4] text-sm mb-8 leading-relaxed px-2"></p>

            <form id="toggleBlacklistForm" method="POST" class="flex gap-3">
                @csrf
                <button type="button" onclick="toggleModal('toggleBlacklistModal')" class="w-1/2 py-3 rounded-xl border border-[#6B85B5] text-[#6B85B5] font-bold hover:bg-gray-50 transition shadow-sm">Cancel</button>
                <button type="submit" id="tbl_btn_submit" class="w-1/2 py-3 rounded-xl text-white font-bold transition shadow-md"></button>
            </form>
        </div>
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
                <span id="mdl_user_role" class="mt-2 inline-block px-3 py-1 bg-gray-100 text-gray-600 text-[10px] uppercase font-bold rounded-full border"></span>
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

        // VIEW DETAIL USER
        function openDetailModal(user, statusStr) {
            document.getElementById('mdl_user_name').innerText = user.name;
            document.getElementById('mdl_user_email').innerText = user.email;
            document.getElementById('mdl_user_role').innerText = user.role;
            document.getElementById('mdl_user_phone').innerText = user.nomor_hp || '-';
            document.getElementById('mdl_user_joined').innerText = user.created_at ? user.created_at.split('T')[0] : '-';
            document.getElementById('mdl_user_dob').innerText = user.tanggal_lahir ? user.tanggal_lahir.split('T')[0] : '-';
            document.getElementById('mdl_user_gender').innerText = user.jenis_kelamin || '-'; 
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

        // EDIT USER
        function openEditModal(user) {
            document.getElementById('editUserForm').action = `/admin/users/${user.id}`;
            document.getElementById('edit_name').value = user.name || '';
            document.getElementById('edit_email').value = user.email || '';
            document.getElementById('edit_phone').value = user.nomor_hp || '';
            document.getElementById('edit_role').value = user.role || 'user';
            
            let stat = user.status || 'Pending';
            if(stat === 'Aktif') stat = 'Active';
            document.getElementById('edit_status').value = stat;

            document.getElementById('edit_gender').value = user.jenis_kelamin || '';
            document.getElementById('edit_city').value = user.kota || '';
            document.getElementById('edit_address').value = user.alamat || '';
            document.getElementById('edit_sim_type').value = user.jenis_sim || '';
            document.getElementById('edit_sim_number').value = user.nomor_sim || '';

            if(user.tanggal_lahir) {
                document.getElementById('edit_dob').value = user.tanggal_lahir.split('T')[0];
            } else {
                document.getElementById('edit_dob').value = '';
            }
            if(user.masa_berlaku_sim) {
                document.getElementById('edit_sim_expire').value = user.masa_berlaku_sim.split('T')[0];
            } else {
                document.getElementById('edit_sim_expire').value = '';
            }

            toggleModal('editUserModal');
        }

        // DELETE USER
        function openDeleteModal(id, name) {
            document.getElementById('del_user_name').innerText = name;
            document.getElementById('deleteUserForm').action = `/admin/users/${id}`;
            toggleModal('deleteUserModal');
        }

        // TOGGLE BLACKLIST / UNBLACKLIST
        function openToggleBlacklistModal(id, name, actionType) {
            const form = document.getElementById('toggleBlacklistForm');
            const title = document.getElementById('tbl_title');
            const desc = document.getElementById('tbl_desc');
            const btnSubmit = document.getElementById('tbl_btn_submit');
            const iconBox = document.getElementById('tbl_icon_box');
            const icon = document.getElementById('tbl_icon');

            if(actionType === 'blacklist') {
                form.action = `/admin/users/${id}/blacklist`;
                title.innerText = "Blacklist User?";
                title.className = "text-2xl font-bold mb-2 text-[#1C2C4A]";
                desc.innerHTML = `Are you sure you want to blacklist <strong class="text-red-500">${name}</strong>?<br>They will not be able to rent again.`;
                
                iconBox.className = "w-16 h-16 bg-gray-100 text-gray-500 rounded-full mx-auto flex items-center justify-center text-3xl mb-4 shadow-sm border border-gray-200";
                icon.className = "fa-solid fa-user-slash";
                
                btnSubmit.innerText = "Blacklist";
                btnSubmit.className = "w-1/2 py-3 rounded-xl text-white font-bold transition shadow-md bg-gray-600 hover:bg-gray-800";
            } else {
                form.action = `/admin/users/${id}/unblacklist`; // Pastiin route ini ada di backend lu ya
                title.innerText = "Unblacklist User?";
                title.className = "text-2xl font-bold mb-2 text-green-600";
                desc.innerHTML = `Are you sure you want to restore access for <strong class="text-green-600">${name}</strong>?`;
                
                iconBox.className = "w-16 h-16 bg-green-100 text-green-500 rounded-full mx-auto flex items-center justify-center text-3xl mb-4 shadow-sm border border-green-200";
                icon.className = "fa-solid fa-user-check";
                
                btnSubmit.innerText = "Unblacklist";
                btnSubmit.className = "w-1/2 py-3 rounded-xl text-white font-bold transition shadow-md bg-green-500 hover:bg-green-600";
            }

            toggleModal('toggleBlacklistModal');
        }

        // 🟢 LOGIKA FILTER DAN SEARCH REAL-TIME 🟢
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const rows = document.querySelectorAll('.user-row');
            const noResultMsg = document.getElementById('noResultMsg');

            function filterTable() {
                const query = searchInput.value.toLowerCase().trim();
                const status = statusFilter.value;
                let visibleCount = 0;

                rows.forEach(row => {
                    const rowSearch = row.getAttribute('data-search');
                    const rowStatus = row.getAttribute('data-status');

                    const matchSearch = rowSearch.includes(query);
                    const matchStatus = (status === 'All') || (rowStatus === status);

                    if (matchSearch && matchStatus) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (visibleCount === 0) {
                    noResultMsg.classList.remove('hidden');
                } else {
                    noResultMsg.classList.add('hidden');
                }
            }

            searchInput.addEventListener('input', filterTable);
            statusFilter.addEventListener('change', filterTable);
        });
    </script>
</body>
</html>