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
        body { font-family: 'Poppins', sans-serif; background-color: #313A53; color: white; }
        .nav-pill { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .card-bg { background-color: #9AB1D6; }
        
        .profile-input {
            width: 100%; background-color: transparent; color: white; font-weight: 700; font-size: 1.125rem;
            border: 2px solid transparent; padding: 0.5rem 1rem; border-radius: 0.5rem; transition: all 0.3s ease;
            text-transform: uppercase; letter-spacing: 0.05em;
        }
        body.edit-mode .profile-input:not([readonly]) { background-color: #4A5578; border-color: #313A53; }
        body.edit-mode .profile-input:not([readonly]):focus { outline: none; border-color: #7A9FE0; }

        .status-overlay { transition: opacity 0.3s ease; backdrop-filter: blur(5px); }
        .icon-circle { width: 300px; height: 300px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 120px; color: white; box-shadow: 0 20px 50px rgba(0,0,0,0.3); }
        .bg-success-circle { background-color: #34D399; } 
        .bg-error-circle { background-color: #EF4444; } 
    </style>
</head>
<body class="flex flex-col min-h-screen relative" id="body-container">

    <!-- OVERLAY STATUS (SUCCESS / ERROR) -->
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

    <!-- NAVBAR -->
    <nav class="pt-6 px-8 flex justify-between items-center mb-12 max-w-7xl mx-auto w-full">
        <div class="nav-pill rounded-full px-6 py-3 flex space-x-6 text-sm font-bold tracking-wider uppercase text-white">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-300 transition">Home</a>
            <a href="{{ route('user.catalog') }}" class="hover:text-blue-300 transition">Catalog</a>
            <a href="#" class="hover:text-blue-300 transition">Destination</a>
            <a href="{{ route('user.orders') }}" class="hover:text-blue-300 transition">Orders</a>
        </div>
        <div class="flex space-x-4 items-center">
            <a href="{{ route('user.profile') }}" class="w-10 h-10 rounded-full nav-pill flex items-center justify-center bg-white/20 text-white">
                <i class="fa-solid fa-user text-lg"></i>
            </a>
            <button class="w-10 h-10 rounded-full nav-pill flex items-center justify-center hover:bg-white/20 transition text-white">
                <i class="fa-solid fa-bell text-lg"></i>
            </button>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="flex-grow max-w-7xl mx-auto px-4 lg:px-8 w-full mb-20 relative">
        
        <!-- JIKA ADA ERROR VALIDASI (Biar ketahuan kenapa nggak mau disimpen) -->
        @if ($errors->any())
            <div class="bg-red-500/80 text-white p-4 rounded-xl mb-6">
                <p class="font-bold mb-2"><i class="fa-solid fa-triangle-exclamation"></i> Gagal menyimpan data:</p>
                <ul class="list-disc pl-5 text-sm font-bold">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FORM (Tambahin enctype biar bisa upload gambar) -->
        <form action="{{ route('user.profile.update') }}" method="POST" id="profileForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- HEADER PROFILE -->
            <div class="card-bg rounded-3xl relative mt-20 p-8 pt-16 flex justify-between items-start mb-12 shadow-lg">
                
                <!-- Foto Profil (Sekarang bisa diklik pas mode edit) -->
                <div class="absolute -top-20 left-12 w-40 h-40 rounded-full border-8 border-[#313A53] overflow-hidden bg-gray-300 shadow-xl flex items-center justify-center group relative">
                    @if($user->profile_photo)
                        <img id="photoPreview" src="{{ asset('storage/' . $user->profile_photo) }}" class="w-full h-full object-cover">
                    @else
                        <img id="photoPreview" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=1C2C4A&color=fff&size=200" class="w-full h-full object-cover">
                    @endif

                    <!-- Lapisan hitam + icon kamera (Cuma muncul pas Edit Mode) -->
                    <label id="photoLabel" class="absolute inset-0 bg-black/60 hidden flex-col items-center justify-center cursor-pointer text-white opacity-0 group-hover:opacity-100 transition">
                        <i class="fa-solid fa-camera text-2xl mb-1"></i>
                        <span class="text-[10px] font-bold uppercase text-center">Ganti<br>Foto</span>
                        <input type="file" name="profile_photo" id="photoInput" class="hidden" accept="image/png, image/jpeg, image/jpg" onchange="previewImage(event)">
                    </label>
                </div>

                <div class="pl-48">
                    <h1 class="text-5xl font-bebas tracking-widest text-white uppercase">{{ $user->name }}</h1>
                    <p class="text-xl text-white mt-1">{{ $user->email }}</p>
                </div>

                <!-- Toggle Buttons -->
                <button type="button" id="btnEdit" onclick="toggleEdit()" class="text-white font-bebas text-2xl tracking-widest uppercase border-b-2 border-white hover:text-gray-200">
                    EDIT PROFILE
                </button>
                <button type="button" id="btnBatal" onclick="toggleEdit()" class="hidden text-white font-bebas text-2xl tracking-widest uppercase border-b-2 border-white hover:text-gray-200">
                    BATAL
                </button>
            </div>

            <h2 class="text-2xl font-bebas tracking-widest uppercase mb-6 ml-4">PERSONAL INFORMATION</h2>

            <!-- INFO BOX -->
            <div class="card-bg rounded-3xl p-10 grid grid-cols-1 md:grid-cols-3 gap-y-8 gap-x-12 shadow-lg relative pb-24">
                
                <div>
                    <label class="block text-xs text-[#313A53] font-bold tracking-widest uppercase mb-1 ml-4">NAMA LENGKAP</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="profile-input" readonly required>
                </div>
                <div>
                    <label class="block text-xs text-[#313A53] font-bold tracking-widest uppercase mb-1 ml-4">NOMOR TELEPON</label>
                    <input type="text" name="nomor_hp" value="{{ old('nomor_hp', $user->nomor_hp) }}" class="profile-input" readonly>
                </div>
                <div>
                    <label class="block text-xs text-[#313A53] font-bold tracking-widest uppercase mb-1 ml-4">KOTA</label>
                    <input type="text" name="kota" value="{{ old('kota', $user->kota) }}" class="profile-input" readonly>
                </div>
                <div>
                    <label class="block text-xs text-[#313A53] font-bold tracking-widest uppercase mb-1 ml-4">JENIS SIM</label>
                    <input type="text" name="jenis_sim" value="{{ old('jenis_sim', $user->jenis_sim) }}" class="profile-input" readonly>
                </div>

                <div class="md:col-start-2 md:row-start-1">
                    <label class="block text-xs text-[#313A53] font-bold tracking-widest uppercase mb-1 ml-4">EMAIL</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="profile-input" readonly required>
                </div>
                <div class="md:col-start-2 md:row-start-2">
                    <label class="block text-xs text-[#313A53] font-bold tracking-widest uppercase mb-1 ml-4">ALAMAT</label>
                    <input type="text" name="alamat" value="{{ old('alamat', $user->alamat) }}" class="profile-input" readonly>
                </div>
                <div class="md:col-start-2 md:row-start-3">
                    <label class="block text-xs text-[#313A53] font-bold tracking-widest uppercase mb-1 ml-4">NOMOR SIM</label>
                    <input type="text" name="nomor_sim" value="{{ old('nomor_sim', $user->nomor_sim) }}" class="profile-input" readonly>
                </div>
                <div class="md:col-start-2 md:row-start-4">
                    <label class="block text-xs text-[#313A53] font-bold tracking-widest uppercase mb-1 ml-4">MASA BERLAKU SIM</label>
                    <input type="date" name="masa_berlaku_sim" value="{{ old('masa_berlaku_sim', $user->masa_berlaku_sim ? $user->masa_berlaku_sim->format('Y-m-d') : '') }}" class="profile-input" readonly>
                </div>

                <div class="md:col-start-3 md:row-start-1">
                    <label class="block text-xs text-[#313A53] font-bold tracking-widest uppercase mb-1 ml-4">TANGGAL LAHIR</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '') }}" class="profile-input" readonly>
                </div>

                <!-- Tombol Simpan -->
                <div class="absolute bottom-10 right-10 hidden" id="saveBtnContainer">
                    <button type="submit" class="bg-[#85A6A1] hover:bg-[#688581] text-white px-8 py-3 rounded-full font-bold transition shadow-md tracking-wider uppercase">
                        simpan perubahan
                    </button>
                </div>

            </div>
        </form>

    </main>

    <!-- FOOTER (Tetap sama) -->
    <footer class="mt-auto border-t border-gray-600 pt-10 pb-8 bg-[#313A53]">
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

    <!-- LOGIKA JAVASCRIPT -->
    <script>
        function toggleEdit() {
            const body = document.getElementById('body-container');
            const inputs = document.querySelectorAll('.profile-input');
            const btnEdit = document.getElementById('btnEdit');
            const btnBatal = document.getElementById('btnBatal');
            const saveBtnContainer = document.getElementById('saveBtnContainer');
            const photoLabel = document.getElementById('photoLabel'); // Tombol upload foto
            
            const isEditing = body.classList.contains('edit-mode');

            if (isEditing) {
                // Cancel Edit
                body.classList.remove('edit-mode');
                btnEdit.classList.remove('hidden');
                btnBatal.classList.add('hidden');
                saveBtnContainer.classList.add('hidden');
                
                // Sembunyikan fitur ganti foto
                photoLabel.classList.add('hidden');
                photoLabel.classList.remove('flex');
                
                inputs.forEach(input => { input.setAttribute('readonly', true); });
            } else {
                // Aktifkan Edit
                body.classList.add('edit-mode');
                btnEdit.classList.add('hidden');
                btnBatal.classList.remove('hidden');
                saveBtnContainer.classList.remove('hidden');
                
                // Munculin fitur ganti foto
                photoLabel.classList.remove('hidden');
                photoLabel.classList.add('flex');
                
                inputs.forEach(input => { input.removeAttribute('readonly'); });
            }
        }

        // Fitur Preview Foto sebelum di-save
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
    </script>
</body>
</html>