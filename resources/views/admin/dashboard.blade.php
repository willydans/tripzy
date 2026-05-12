<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Tripzy</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #F4F7FC; }
        
        .sidebar-bg { background: linear-gradient(180deg, #627AA3 0%, #859BBE 100%); }
        .topbar-bg { background: linear-gradient(to right, #B2C5E5 0%, #F4F7FC 50%); }
        
        .card-shadow { box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); }
        .glass-search { background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.4); }

        /* Sembunyikan scrollbar untuk elemen tertentu di mobile */
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="flex h-screen overflow-hidden text-[#1C2C4A]">

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
            
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 bg-white/20 rounded-lg font-semibold border border-white/30 backdrop-blur-sm shadow-sm transition">
                Dashboard
            </a>
            
            <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg font-medium opacity-80 hover:opacity-100 transition">
                Booking Verification
            </a>
            
            <a href="{{ route('admin.catalog.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg font-medium opacity-80 hover:opacity-100 transition">
                Manage Catalog
            </a>
            
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg font-medium opacity-80 hover:opacity-100 transition">
                User Management
            </a>
            
            <a href="{{ route('admin.transactions.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg font-medium opacity-80 transition">Transactions</a>
            
        </nav>
    </aside>

    <div class="flex-grow flex flex-col h-full overflow-y-auto relative bg-[#F4F7FC]">
        
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
                <div class="flex items-center gap-2 md:gap-3 bg-white/60 md:bg-white/40 px-3 md:px-4 py-1.5 md:py-2 rounded-full border border-white/50 backdrop-blur-sm shadow-sm">
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-[#1C2C4A] rounded-full flex items-center justify-center text-white text-sm md:text-base">
                        <i class="fa-solid fa-user"></i>
                    </div>
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

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
                <div class="bg-white rounded-2xl p-5 md:p-6 card-shadow border border-gray-100">
                    <h3 class="text-[#4A6EB0] font-semibold text-xs md:text-sm mb-3 md:mb-4">Total Fleet</h3>
                    <div class="flex justify-between items-end mb-3 md:mb-4">
                        <span class="text-4xl md:text-5xl font-bold text-[#4A6EB0]">{{ $totalFleet }}</span>
                        <i class="fa-solid fa-car-side text-3xl md:text-4xl text-[#8CA1C4]"></i>
                    </div>
                    <p class="text-[10px] md:text-xs text-green-500 font-medium"><i class="fa-solid fa-arrow-up"></i> +3 <span class="text-gray-400">vs last month</span></p>
                </div>
                <div class="bg-white rounded-2xl p-5 md:p-6 card-shadow border border-gray-100">
                    <h3 class="text-[#4A6EB0] font-semibold text-xs md:text-sm mb-3 md:mb-4">Active Rentals</h3>
                    <div class="flex justify-between items-end mb-3 md:mb-4">
                        <span class="text-4xl md:text-5xl font-bold text-[#4A6EB0]">{{ $activeRentals }}</span>
                        <i class="fa-solid fa-car text-3xl md:text-4xl text-[#8CA1C4]"></i>
                    </div>
                    <p class="text-[10px] md:text-xs text-green-500 font-medium"><i class="fa-solid fa-arrow-up"></i> +8% <span class="text-gray-400">vs last month</span></p>
                </div>
                <div class="bg-white rounded-2xl p-5 md:p-6 card-shadow border border-gray-100">
                    <h3 class="text-[#4A6EB0] font-semibold text-xs md:text-sm mb-3 md:mb-4">Pending Verification</h3>
                    <div class="flex justify-between items-end mb-3 md:mb-4">
                        <span class="text-4xl md:text-5xl font-bold text-[#4A6EB0]">{{ $pendingVerification }}</span>
                        <i class="fa-regular fa-clock text-3xl md:text-4xl text-[#8CA1C4]"></i>
                    </div>
                    <p class="text-[10px] md:text-xs text-red-500 font-medium"><i class="fa-solid fa-arrow-down"></i> -2 <span class="text-gray-400">vs last month</span></p>
                </div>
                <div class="bg-white rounded-2xl p-5 md:p-6 card-shadow border border-gray-100">
                    <h3 class="text-[#4A6EB0] font-semibold text-xs md:text-sm mb-3 md:mb-4">Total Revenue</h3>
                    <div class="flex justify-between items-end mb-3 md:mb-4">
                        <span class="text-2xl sm:text-3xl md:text-4xl font-bold text-[#4A6EB0]">
                            RP {{ number_format($totalRevenue / 1000000, 1) }}M
                        </span>
                        <div class="w-8 h-8 md:w-10 md:h-10 bg-[#8CA1C4] rounded-full flex items-center justify-center text-white shrink-0"><i class="fa-solid fa-dollar-sign text-base md:text-xl"></i></div>
                    </div>
                    <p class="text-[10px] md:text-xs text-green-500 font-medium"><i class="fa-solid fa-arrow-up"></i> +12% <span class="text-gray-400">vs last month</span></p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                
                <div class="bg-white rounded-2xl p-5 md:p-6 card-shadow border border-gray-100 lg:col-span-2">
                    <h3 class="text-[#4A6EB0] font-bold text-base md:text-lg mb-4 md:mb-6">Weekly Booking Trends</h3>
                    <div class="w-full h-48 md:h-64 relative">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-5 md:p-6 card-shadow border border-gray-100 flex flex-col">
                    <div class="flex justify-between items-center mb-4 md:mb-6">
                        <h3 class="text-[#4A6EB0] font-bold text-base md:text-lg">Active Rentals</h3>
                        <span class="text-[10px] md:text-xs text-gray-400 font-medium">{{ $activeRentalsList->count() }} Car</span>
                    </div>
                    <div class="flex flex-col gap-3 md:gap-4 flex-grow max-h-64 overflow-y-auto pr-2">
                        @forelse($activeRentalsList as $rental)
                        <div class="flex items-center justify-between bg-[#F8FAFC] p-3 rounded-xl border border-gray-100">
                            <div class="flex items-center gap-3">
                                <img src="{{ asset($rental->car->image_path) }}" class="w-10 h-6 md:w-12 md:h-8 object-contain">
                                <div>
                                    <h4 class="text-xs md:text-sm font-bold truncate max-w-[100px] sm:max-w-[150px]">{{ $rental->car->name }}</h4>
                                    <p class="text-[9px] md:text-[10px] text-[#4A6EB0] truncate max-w-[100px] sm:max-w-[150px]">{{ $rental->renter_name }}</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-location-dot text-gray-400 text-sm"></i>
                        </div>
                        @empty
                        <p class="text-xs md:text-sm text-center text-gray-400 py-4">No active rentals right now.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <div class="bg-white rounded-2xl p-5 md:p-6 card-shadow border border-gray-100">
                    <div class="flex justify-between items-center mb-4 md:mb-6">
                        <h3 class="text-[#4A6EB0] font-bold text-base md:text-lg">New Transactions</h3>
                        <a href="{{ route('admin.transactions.index') }}" class="text-[10px] md:text-xs text-gray-400 hover:text-[#4A6EB0] font-medium">See all</a>
                    </div>
                    <div class="flex flex-col gap-3 md:gap-4">
                        @forelse($newTransactions as $trx)
                        <div class="flex justify-between items-center bg-[#F8FAFC] md:bg-transparent p-3 md:p-0 rounded-xl md:rounded-none">
                            <div class="flex items-center gap-3 md:gap-4 w-2/3">
                                @if(in_array($trx->status, ['History', 'Ongoing']))
                                    <div class="w-6 h-6 md:w-8 md:h-8 rounded-full bg-green-100 text-green-500 flex items-center justify-center shrink-0"><i class="fa-solid fa-check text-[10px] md:text-sm"></i></div>
                                @elseif($trx->status == 'Ordered' || $trx->status == 'Pending Payment')
                                    <div class="w-6 h-6 md:w-8 md:h-8 rounded-full bg-yellow-100 text-yellow-500 flex items-center justify-center shrink-0"><i class="fa-regular fa-clock text-[10px] md:text-sm"></i></div>
                                @else
                                    <div class="w-6 h-6 md:w-8 md:h-8 rounded-full bg-red-100 text-red-500 flex items-center justify-center shrink-0"><i class="fa-solid fa-xmark text-[10px] md:text-sm"></i></div>
                                @endif
                                <div class="overflow-hidden">
                                    <h4 class="text-xs md:text-sm font-bold truncate">{{ $trx->renter_name }}</h4>
                                    <p class="text-[10px] md:text-xs text-[#4A6EB0] truncate">{{ $trx->car->name }} . {{ $trx->duration }} day</p>
                                </div>
                            </div>
                            <div class="text-right w-1/3">
                                <p class="text-[10px] md:text-xs font-bold text-[#4A6EB0] mb-1">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</p>
                                @if(in_array($trx->status, ['History', 'Ongoing']))
                                    <span class="text-[8px] md:text-[9px] bg-green-100 text-green-600 px-2 py-0.5 rounded-full font-bold">Berhasil</span>
                                @elseif($trx->status == 'Ordered' || $trx->status == 'Pending Payment')
                                    <span class="text-[8px] md:text-[9px] bg-yellow-100 text-yellow-600 px-2 py-0.5 rounded-full font-bold">Menunggu</span>
                                @else
                                    <span class="text-[8px] md:text-[9px] bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-bold">Dibatalkan</span>
                                @endif
                            </div>
                        </div>
                        @empty
                        <p class="text-xs md:text-sm text-gray-400">No new transactions.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-5 md:p-6 card-shadow border border-gray-100">
                    <div class="flex justify-between items-center mb-4 md:mb-6">
                        <h3 class="text-[#4A6EB0] font-bold text-base md:text-lg">Status Fleet</h3>
                        <a href="{{ route('admin.catalog.index') }}" class="text-[10px] md:text-xs text-[#4A6EB0] hover:underline font-medium">Manage Fleet</a>
                    </div>
                    <div class="flex flex-col gap-3 md:gap-4 max-h-64 overflow-y-auto pr-2">
                        @forelse($statusFleet as $fleet)
                        <div class="flex justify-between items-center border-b border-gray-50 pb-2 md:pb-3 last:border-0 last:pb-0">
                            <div class="flex items-center gap-3">
                                <img src="{{ asset($fleet->image_path) }}" class="w-12 md:w-16 object-contain shrink-0">
                                <div>
                                    <h4 class="text-xs md:text-sm font-bold truncate max-w-[120px] sm:max-w-[200px]">{{ $fleet->name }}</h4>
                                    <p class="text-[9px] md:text-[10px] text-[#4A6EB0] uppercase truncate">{{ $fleet->license_plate }} . {{ $fleet->category }}</p>
                                </div>
                            </div>
                            <div>
                                @if($fleet->status == 'Tersedia')
                                    <span class="bg-green-100 text-green-600 px-2 md:px-3 py-1 text-[8px] md:text-[10px] rounded-full font-bold">Tersedia</span>
                                @elseif($fleet->status == 'Disewa')
                                    <span class="bg-orange-100 text-orange-600 px-2 md:px-3 py-1 text-[8px] md:text-[10px] rounded-full font-bold">Disewa</span>
                                @else
                                    <span class="bg-red-100 text-red-600 px-2 md:px-3 py-1 text-[8px] md:text-[10px] rounded-full font-bold">Servis</span>
                                @endif
                            </div>
                        </div>
                        @empty
                        <p class="text-xs md:text-sm text-gray-400">No fleet data.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <footer class="mt-12 md:mt-16 bg-gradient-to-t from-[#B2C5E5] to-transparent pt-8 md:pt-12 pb-6 border-t border-[#D0DDF0] px-4 md:px-0 -mx-4 md:-mx-8">
                <div class="max-w-5xl mx-auto flex flex-col sm:flex-row justify-between items-center sm:items-start text-center sm:text-left gap-6 sm:gap-0 px-4">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-bold italic text-[#4A6EB0] mb-3 md:mb-4 drop-shadow-sm">Tripzy</h2>
                        <div class="flex space-x-3 text-lg md:text-xl text-white justify-center sm:justify-start">
                            <i class="fa-brands fa-discord hover:text-[#4A6EB0] transition cursor-pointer"></i>
                            <i class="fa-brands fa-whatsapp hover:text-[#4A6EB0] transition cursor-pointer"></i>
                            <i class="fa-brands fa-telegram hover:text-[#4A6EB0] transition cursor-pointer"></i>
                            <i class="fa-brands fa-instagram hover:text-[#4A6EB0] transition cursor-pointer"></i>
                        </div>
                    </div>
                    <div class="w-full sm:w-1/2 flex flex-col items-center sm:items-start">
                        <h3 class="text-base md:text-lg font-bold text-[#4A6EB0] mb-2 md:mb-3">Contact Us</h3>
                        <ul class="space-y-2 text-[10px] md:text-xs font-medium text-gray-500">
                            <li class="flex items-start justify-center sm:justify-start gap-2"><i class="fa-solid fa-location-dot mt-0.5 text-[#4A6EB0] shrink-0"></i><span>Jl. Prof. Dr. Ir. Sumantri Brojonegoro No.1, Bandar Lampung</span></li>
                            <li class="flex items-center justify-center sm:justify-start gap-2"><i class="fa-solid fa-phone text-[#4A6EB0] shrink-0"></i><span>+6285276139801</span></li>
                            <li class="flex items-center justify-center sm:justify-start gap-2"><i class="fa-solid fa-envelope text-[#4A6EB0] shrink-0"></i><span>tripzy@gmail.com</span></li>
                        </ul>
                    </div>
                </div>
            </footer>
            
        </main>
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

        // Script Chart.js Responsive
        const ctx = document.getElementById('trendChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                datasets: [{
                    label: 'Bookings',
                    data: [8, 22, 19, 34, 40, 52, 58], // Data dummy grafik
                    borderColor: '#627AA3',
                    borderWidth: 2,
                    tension: 0.3,
                    pointRadius: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 63,
                        ticks: { stepSize: 9, color: '#A0AEC0' },
                        grid: { color: '#F1F5F9', drawBorder: false }
                    },
                    x: {
                        ticks: { color: '#A0AEC0' },
                        grid: { display: false, drawBorder: false }
                    }
                }
            }
        });
    </script>
</body>
</html>