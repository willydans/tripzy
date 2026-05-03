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
    </style>
</head>
<body class="flex h-screen overflow-hidden text-[#1C2C4A]">

    <!-- SIDEBAR -->
    <aside class="w-64 sidebar-bg text-white flex flex-col h-full shadow-lg z-20 shrink-0">
        <div class="p-6">
            <h1 class="text-3xl font-bold italic drop-shadow-md mb-1">Tripzy</h1>
            <p class="text-sm font-medium opacity-90">Admin Panel</p>
        </div>
        <nav class="mt-6 flex-grow flex flex-col gap-2 px-4">
            
            <!-- 👇 Menu Aktif (Karena ini halaman Dashboard) 👇 -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 bg-white/20 rounded-lg font-semibold border border-white/30 backdrop-blur-sm shadow-sm transition">
                Dashboard
            </a>
            
            <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg font-medium opacity-80 hover:opacity-100 transition">
                Booking Verification
            </a>
            
            <a href="{{ route('admin.catalog.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg font-medium opacity-80 hover:opacity-100 transition">
                Manage Catalog
            </a>
            
            <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg font-medium opacity-80 hover:opacity-100 transition">
                User Management
            </a>
            
            <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg font-medium opacity-80 hover:opacity-100 transition">
                Transactions
            </a>
            
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="flex-grow flex flex-col h-full overflow-y-auto relative">
        
        <!-- TOPBAR -->
        <header class="topbar-bg px-8 py-5 flex justify-between items-center sticky top-0 z-10">
            <!-- Search -->
            <div class="relative w-96">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 transform -translate-y-1/2 text-white"></i>
                <input type="text" placeholder="Search...." class="w-full glass-search text-white placeholder-white rounded-full py-2.5 pl-12 pr-4 focus:outline-none focus:bg-white/30 transition shadow-inner">
            </div>
            
            <!-- Admin Profile & Logout -->
            <div class="flex items-center gap-4">
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="text-xs text-red-500 font-semibold hover:underline">Logout</button>
                </form>
                <div class="flex items-center gap-3 bg-white/40 px-4 py-2 rounded-full border border-white/50 backdrop-blur-sm shadow-sm">
                    <div class="w-10 h-10 bg-[#1C2C4A] rounded-full flex items-center justify-center text-white">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="leading-tight">
                        <h4 class="text-sm font-bold">{{ Auth::user()->name }}</h4>
                        <p class="text-[10px] font-medium text-[#4A6EB0]">Super Admin</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- DASHBOARD CONTENT -->
        <main class="p-8 pb-24">
            <h2 class="text-2xl font-bold text-[#4A6EB0] mb-6">Dashboard</h2>

            <!-- 4 SUMMARY CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Fleet -->
                <div class="bg-white rounded-2xl p-6 card-shadow border border-gray-100">
                    <h3 class="text-[#4A6EB0] font-semibold text-sm mb-4">Total Fleet</h3>
                    <div class="flex justify-between items-end mb-4">
                        <span class="text-5xl font-bold text-[#4A6EB0]">{{ $totalFleet }}</span>
                        <i class="fa-solid fa-car-side text-4xl text-[#8CA1C4]"></i>
                    </div>
                    <p class="text-xs text-green-500 font-medium"><i class="fa-solid fa-arrow-up"></i> +3 <span class="text-gray-400">vs last month</span></p>
                </div>
                <!-- Active -->
                <div class="bg-white rounded-2xl p-6 card-shadow border border-gray-100">
                    <h3 class="text-[#4A6EB0] font-semibold text-sm mb-4">Active Rentals</h3>
                    <div class="flex justify-between items-end mb-4">
                        <span class="text-5xl font-bold text-[#4A6EB0]">{{ $activeRentals }}</span>
                        <i class="fa-solid fa-car text-4xl text-[#8CA1C4]"></i>
                    </div>
                    <p class="text-xs text-green-500 font-medium"><i class="fa-solid fa-arrow-up"></i> +8% <span class="text-gray-400">vs last month</span></p>
                </div>
                <!-- Pending -->
                <div class="bg-white rounded-2xl p-6 card-shadow border border-gray-100">
                    <h3 class="text-[#4A6EB0] font-semibold text-sm mb-4">Pending Verification</h3>
                    <div class="flex justify-between items-end mb-4">
                        <span class="text-5xl font-bold text-[#4A6EB0]">{{ $pendingVerification }}</span>
                        <i class="fa-regular fa-clock text-4xl text-[#8CA1C4]"></i>
                    </div>
                    <p class="text-xs text-red-500 font-medium"><i class="fa-solid fa-arrow-down"></i> -2 <span class="text-gray-400">vs last month</span></p>
                </div>
                <!-- Revenue -->
                <div class="bg-white rounded-2xl p-6 card-shadow border border-gray-100">
                    <h3 class="text-[#4A6EB0] font-semibold text-sm mb-4">Total Revenue</h3>
                    <div class="flex justify-between items-end mb-4">
                        <span class="text-4xl font-bold text-[#4A6EB0]">
                            RP {{ number_format($totalRevenue / 1000000, 1) }}M
                        </span>
                        <div class="w-10 h-10 bg-[#8CA1C4] rounded-full flex items-center justify-center text-white"><i class="fa-solid fa-dollar-sign text-xl"></i></div>
                    </div>
                    <p class="text-xs text-green-500 font-medium"><i class="fa-solid fa-arrow-up"></i> +12% <span class="text-gray-400">vs last month</span></p>
                </div>
            </div>

            <!-- MIDDLE SECTION (CHART & ACTIVE RENTALS) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                
                <!-- Chart Trend -->
                <div class="bg-white rounded-2xl p-6 card-shadow border border-gray-100 lg:col-span-2">
                    <h3 class="text-[#4A6EB0] font-bold text-lg mb-6">Weekly Booking Trends</h3>
                    <div class="w-full h-64">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>

                <!-- Active Rentals List -->
                <div class="bg-white rounded-2xl p-6 card-shadow border border-gray-100 flex flex-col">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-[#4A6EB0] font-bold text-lg">Active Rentals</h3>
                        <span class="text-xs text-gray-400 font-medium">{{ $activeRentalsList->count() }} Car</span>
                    </div>
                    <div class="flex flex-col gap-4 flex-grow">
                        @forelse($activeRentalsList as $rental)
                        <div class="flex items-center justify-between bg-[#F8FAFC] p-3 rounded-xl border border-gray-100">
                            <div class="flex items-center gap-3">
                                <img src="{{ asset($rental->car->image_path) }}" class="w-12 h-8 object-contain">
                                <div>
                                    <h4 class="text-sm font-bold">{{ $rental->car->name }}</h4>
                                    <p class="text-[10px] text-[#4A6EB0]">{{ $rental->renter_name }}</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-location-dot text-gray-400"></i>
                        </div>
                        @empty
                        <p class="text-sm text-center text-gray-400 py-4">No active rentals right now.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- BOTTOM SECTION (TRANSACTIONS & FLEET STATUS) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- New Transactions -->
                <div class="bg-white rounded-2xl p-6 card-shadow border border-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-[#4A6EB0] font-bold text-lg">New Transactions</h3>
                        <a href="#" class="text-xs text-gray-400 hover:text-[#4A6EB0] font-medium">See all</a>
                    </div>
                    <div class="flex flex-col gap-4">
                        @forelse($newTransactions as $trx)
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-4">
                                @if(in_array($trx->status, ['History', 'Ongoing']))
                                    <div class="w-8 h-8 rounded-full bg-green-100 text-green-500 flex items-center justify-center"><i class="fa-solid fa-check"></i></div>
                                @elseif($trx->status == 'Ordered' || $trx->status == 'Pending Payment')
                                    <div class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-500 flex items-center justify-center"><i class="fa-regular fa-clock"></i></div>
                                @else
                                    <div class="w-8 h-8 rounded-full bg-red-100 text-red-500 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></div>
                                @endif
                                <div>
                                    <h4 class="text-sm font-bold">{{ $trx->renter_name }}</h4>
                                    <p class="text-xs text-[#4A6EB0]">{{ $trx->car->name }} . {{ $trx->duration }} day</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-[#4A6EB0] mb-1">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</p>
                                @if(in_array($trx->status, ['History', 'Ongoing']))
                                    <span class="text-[9px] bg-green-100 text-green-600 px-2 py-0.5 rounded-full font-bold">Berhasil</span>
                                @elseif($trx->status == 'Ordered' || $trx->status == 'Pending Payment')
                                    <span class="text-[9px] bg-yellow-100 text-yellow-600 px-2 py-0.5 rounded-full font-bold">Menunggu</span>
                                @else
                                    <span class="text-[9px] bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-bold">Dibatalkan</span>
                                @endif
                            </div>
                        </div>
                        @empty
                        <p class="text-sm text-gray-400">No new transactions.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Status Fleet -->
                <div class="bg-white rounded-2xl p-6 card-shadow border border-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-[#4A6EB0] font-bold text-lg">Status Fleet</h3>
                        <a href="#" class="text-xs text-[#4A6EB0] hover:underline font-medium">Manage Fleet</a>
                    </div>
                    <div class="flex flex-col gap-4">
                        @forelse($statusFleet as $fleet)
                        <div class="flex justify-between items-center border-b border-gray-50 pb-3 last:border-0 last:pb-0">
                            <div class="flex items-center gap-3">
                                <img src="{{ asset($fleet->image_path) }}" class="w-16 object-contain">
                                <div>
                                    <h4 class="text-sm font-bold">{{ $fleet->name }}</h4>
                                    <p class="text-xs text-[#4A6EB0] uppercase">{{ $fleet->license_plate }} . {{ $fleet->category }}</p>
                                </div>
                            </div>
                            <div>
                                @if($fleet->status == 'Tersedia')
                                    <span class="bg-green-100 text-green-600 px-3 py-1 text-[10px] rounded-full font-bold">Tersedia</span>
                                @elseif($fleet->status == 'Disewa')
                                    <span class="bg-orange-100 text-orange-600 px-3 py-1 text-[10px] rounded-full font-bold">Disewa</span>
                                @else
                                    <span class="bg-red-100 text-red-600 px-3 py-1 text-[10px] rounded-full font-bold">Servis</span>
                                @endif
                            </div>
                        </div>
                        @empty
                        <p class="text-sm text-gray-400">No fleet data.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- FOOTER -->
            <footer class="mt-12 bg-gradient-to-t from-[#B2C5E5] to-transparent pt-12 pb-6 border-t border-[#D0DDF0] px-4 -mx-8">
                <div class="max-w-5xl mx-auto flex justify-between items-start">
                    <div>
                        <h2 class="text-4xl font-bold italic text-[#4A6EB0] mb-4 drop-shadow-sm">Tripzy</h2>
                        <div class="flex space-x-3 text-xl text-white">
                            <i class="fa-brands fa-discord"></i>
                            <i class="fa-brands fa-whatsapp"></i>
                            <i class="fa-brands fa-telegram"></i>
                            <i class="fa-brands fa-instagram"></i>
                        </div>
                    </div>
                    <div class="w-1/2">
                        <h3 class="text-lg font-bold text-[#4A6EB0] mb-3">Contact Us</h3>
                        <ul class="space-y-3 text-xs font-medium text-gray-500">
                            <li class="flex items-start gap-2"><i class="fa-solid fa-location-dot mt-0.5 text-[#4A6EB0]"></i><span>Jl. Prof. Dr. Ir. Sumantri Brojonegoro No.1, Bandar Lampung</span></li>
                            <li class="flex items-center gap-2"><i class="fa-solid fa-phone text-[#4A6EB0]"></i><span>+6285276139801</span></li>
                            <li class="flex items-center gap-2"><i class="fa-solid fa-envelope text-[#4A6EB0]"></i><span>tripzy@gmail.com</span></li>
                        </ul>
                    </div>
                </div>
            </footer>
            
        </main>
    </div>

    <!-- Script Chart.js -->
    <script>
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