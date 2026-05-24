<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Tripzy</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #313A53; color: white; overflow-x: hidden; }
        .nav-pill { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .card-bg { background-color: #9AB1D6; }
        
        .profile-input {
            width: 100%; background-color: transparent; color: white; font-weight: 700; font-size: 1rem;
            border: 2px solid transparent; padding: 0.5rem 1rem; border-radius: 0.5rem; transition: all 0.3s ease;
            text-transform: uppercase; letter-spacing: 0.05em;
        }
        @media (min-width: 768px) { .profile-input { font-size: 1.125rem; } }
        
        body.edit-mode .profile-input:not([readonly]) { background-color: #4A5578; border-color: #313A53; }
        body.edit-mode .profile-input:not([readonly]):focus { outline: none; border-color: #7A9FE0; }

        .status-overlay { transition: opacity 0.3s ease; backdrop-filter: blur(5px); }
        .icon-circle { width: 150px; height: 150px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 60px; color: white; box-shadow: 0 20px 50px rgba(0,0,0,0.3); }
        @media (min-width: 768px) { .icon-circle { width: 300px; height: 300px; font-size: 120px; } }
        .bg-success-circle { background-color: #34D399; } 
        .bg-error-circle { background-color: #EF4444; } 

        /* Sembunyikan scrollbar untuk navigasi mobile */
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
        
    </style>
</head>
<body class="flex flex-col min-h-screen relative" id="body-container">

    @if(session('success') || session('error'))
    <div id="statusOverlay" class="fixed inset-0 z-[100] bg-[#313A53]/80 flex items-center justify-center status-overlay">
        <div class="icon-circle {{ session('success') ? 'bg-success-circle' : 'bg-error-circle' }} animate-bounce">
            <i class="fa-solid {{ session('success') ? 'fa-check' : 'fa-xmark' }}"></i>
        </div>
    </div>
    <script>
        setTimeout(() => {
            const overlay = document.getElementById('statusOverlay');
            if(overlay) { overlay.style.opacity = '0'; setTimeout(() => overlay.remove(), 300); }
        }, 2000); 
    </script>
    @endif

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
            <a href="{{ route('user.orders') }}" class="hover:text-blue-300 transition">Orders</a>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto px-4 lg:px-8 w-full mb-20 relative">
        
        <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>

        @if ($errors->any())
            <div class="bg-red-500/80 text-white p-4 rounded-xl mb-6 shadow-md mx-4 md:mx-0">
                <p class="font-bold mb-2"><i class="fa-solid fa-triangle-exclamation"></i> Gagal menyimpan data:</p>
                <ul class="list-disc pl-5 text-sm font-bold">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('user.profile.update') }}" method="POST" id="profileForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card-bg rounded-3xl relative mt-24 md:mt-20 p-6 md:p-8 pt-24 md:pt-16 flex flex-col md:flex-row justify-between items-center md:items-start mb-8 md:mb-12 shadow-lg z-10 text-center md:text-left mx-2 md:mx-0">
                
                <div class="absolute -top-16 md:-top-20 left-1/2 transform -translate-x-1/2 md:left-12 md:translate-x-0 w-32 h-32 md:w-40 md:h-40 rounded-full border-8 border-[#313A53] overflow-hidden bg-gray-300 shadow-xl flex items-center justify-center group z-20">
                    @if($user->profile_photo)
                        <img id="photoPreview" src="{{ asset('storage/' . $user->profile_photo) }}" class="w-full h-full object-cover">
                    @else
                        <img id="photoPreview" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=1C2C4A&color=fff&size=200" class="w-full h-full object-cover">
                    @endif

                    <label id="photoLabel" class="absolute inset-0 bg-black/60 hidden flex-col items-center justify-center cursor-pointer text-white opacity-0 group-hover:opacity-100 transition">
                        <i class="fa-solid fa-camera text-xl md:text-2xl mb-1"></i>
                        <span class="text-[10px] md:text-xs font-bold uppercase text-center">Ganti<br>Foto</span>
                        <input type="file" name="profile_photo" id="photoInput" class="hidden" accept="image/png, image/jpeg, image/jpg" onchange="previewImage(event)">
                    </label>
                </div>

                <div class="md:pl-48 w-full">
                    <h1 class="text-3xl md:text-5xl font-bebas tracking-widest text-white uppercase break-words px-4 md:px-0">{{ $user->name }}</h1>
                    <p class="text-sm md:text-xl text-white mt-1 opacity-90">{{ $user->email }}</p>
                </div>

                <div class="flex items-center justify-center md:justify-end gap-6 mt-6 md:mt-0 w-full md:w-auto">
                    <button type="button" onclick="document.getElementById('logoutForm').submit();" class="text-white font-bebas text-xl md:text-2xl tracking-widest uppercase border-b-2 border-transparent md:border-white hover:text-gray-200 cursor-pointer">
                        LOGOUT
                    </button>

                    <button type="button" id="btnEdit" onclick="toggleEdit()" class="text-white font-bebas text-xl md:text-2xl tracking-widest uppercase border-b-2 border-white hover:text-gray-200">
                        EDIT PROFILE
                    </button>
                    <button type="button" id="btnBatal" onclick="toggleEdit()" class="hidden text-white font-bebas text-xl md:text-2xl tracking-widest uppercase border-b-2 border-white hover:text-gray-200">
                        BATAL
                    </button>
                </div>
            </div>

            <h2 class="text-xl md:text-2xl font-bebas tracking-widest uppercase mb-4 md:mb-6 ml-4 text-center md:text-left">PERSONAL INFORMATION</h2>

            <div class="card-bg rounded-3xl p-6 md:p-10 grid grid-cols-1 md:grid-cols-3 gap-y-6 md:gap-y-8 gap-x-12 shadow-lg relative mx-2 md:mx-0">
                
                <div>
                    <label class="block text-[10px] md:text-xs text-[#313A53] font-bold tracking-widest uppercase mb-1 ml-4">NAMA LENGKAP</label>
                    <input type="text" name="name" value="{{ $user->name }}" class="profile-input" readonly required>
                </div>
                <div>
                    <label class="block text-[10px] md:text-xs text-[#313A53] font-bold tracking-widest uppercase mb-1 ml-4">NOMOR TELEPON</label>
                    <input type="text" name="nomor_hp" value="{{ $user->nomor_hp }}" class="profile-input" readonly inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
                <div>
                    <label class="block text-[10px] md:text-xs text-[#313A53] font-bold tracking-widest uppercase mb-1 ml-4">KOTA</label>
                    <input type="text" name="kota" value="{{ $user->kota }}" class="profile-input" readonly>
                </div>
                <div>
                    <label class="block text-[10px] md:text-xs text-[#313A53] font-bold tracking-widest uppercase mb-1 ml-4">JENIS SIM</label>
                    <input type="text" name="jenis_sim" value="{{ $user->jenis_sim }}" class="profile-input" readonly>
                </div>

                <div class="md:col-start-2 md:row-start-1">
                    <label class="block text-[10px] md:text-xs text-[#313A53] font-bold tracking-widest uppercase mb-1 ml-4">EMAIL</label>
                    <input type="email" name="email" value="{{ $user->email }}" class="profile-input" readonly required>
                </div>
                <div class="md:col-start-2 md:row-start-2">
                    <label class="block text-[10px] md:text-xs text-[#313A53] font-bold tracking-widest uppercase mb-1 ml-4">ALAMAT</label>
                    <input type="text" name="alamat" value="{{ $user->alamat }}" class="profile-input" readonly>
                </div>
                <div class="md:col-start-2 md:row-start-3">
                    <label class="block text-[10px] md:text-xs text-[#313A53] font-bold tracking-widest uppercase mb-1 ml-4">NOMOR SIM</label>
                    <input type="text" name="nomor_sim" value="{{ $user->nomor_sim }}" class="profile-input" readonly>
                </div>
                <div class="md:col-start-2 md:row-start-4">
                    <label class="block text-[10px] md:text-xs text-[#313A53] font-bold tracking-widest uppercase mb-1 ml-4">MASA BERLAKU SIM</label>
                    <input type="date" name="masa_berlaku_sim" value="{{ $user->masa_berlaku_sim ? \Carbon\Carbon::parse($user->masa_berlaku_sim)->format('Y-m-d') : '' }}" class="profile-input" readonly>
                </div>

                <div class="md:col-start-3 md:row-start-1">
                    <label class="block text-[10px] md:text-xs text-[#313A53] font-bold tracking-widest uppercase mb-1 ml-4">TANGGAL LAHIR</label>
                    <input type="date" name="tanggal_lahir" value="{{ $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->format('Y-m-d') : '' }}" class="profile-input" readonly>
                </div>

                <div class="col-span-1 md:col-span-3 hidden flex-col md:flex-row justify-end mt-4 md:mt-8" id="saveBtnContainer">
                    <button type="submit" class="w-full md:w-auto bg-[#85A6A1] hover:bg-[#688581] text-white px-8 py-3 md:py-4 rounded-full font-bold transition shadow-md tracking-wider uppercase text-sm md:text-base">
                        Simpan Perubahan
                    </button>
                </div>

            </div>
        </form>

    </main>

    <footer class="mt-auto border-t border-white/10 pt-10 pb-8 bg-[#313A53]">
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
                    <li class="flex items-center gap-3"><i class="fa-solid fa-clock text-[#7A9FE0] shrink-0"></i><span>Senin - Minggu<br>24 Jam</span></li>
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

        function toggleEdit() {
            const body = document.getElementById('body-container');
            const inputs = document.querySelectorAll('.profile-input');
            const btnEdit = document.getElementById('btnEdit');
            const btnBatal = document.getElementById('btnBatal');
            const saveBtnContainer = document.getElementById('saveBtnContainer');
            const photoLabel = document.getElementById('photoLabel'); 
            
            const isEditing = body.classList.contains('edit-mode');

            if (isEditing) {
                // Saat batal, kembalikan value form ke kondisi asal biar bener-bener batal
                document.getElementById('profileForm').reset();

                body.classList.remove('edit-mode');
                btnEdit.classList.remove('hidden');
                btnBatal.classList.add('hidden');
                saveBtnContainer.classList.add('hidden');
                saveBtnContainer.classList.remove('flex');
                
                photoLabel.classList.add('hidden');
                photoLabel.classList.remove('flex');
                
                inputs.forEach(input => { input.setAttribute('readonly', true); });
            } else {
                body.classList.add('edit-mode');
                btnEdit.classList.add('hidden');
                btnBatal.classList.remove('hidden');
                
                saveBtnContainer.classList.remove('hidden');
                saveBtnContainer.classList.add('flex');
                
                photoLabel.classList.remove('hidden');
                photoLabel.classList.add('flex', 'flex-col');
                
                inputs.forEach(input => { input.removeAttribute('readonly'); });
            }
        }

        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('photoPreview');
                output.src = reader.result;
            };
            if(event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }

        // Kalau ada error validasi dari server pas buka halaman, otomatis trigger mode edit biar form bisa dikoreksi
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                toggleEdit();
            });
        @endif
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