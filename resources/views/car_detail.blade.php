<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $car->name }} - Tripzy</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #171B2D; 
            color: white;
        }

        .form-input {
            background-color: #1E2336;
            border: 1px solid #323A56;
            color: white;
            transition: all 0.3s;
        }
        .form-input:focus {
            outline: none;
            border-color: #5C72A6;
            box-shadow: 0 0 0 2px rgba(92, 114, 166, 0.2);
        }
        .form-input::placeholder { color: #4A5578; }

        .spec-badge {
            background-color: #1C2237;
            border: 1px solid #323A56;
            color: #D1D5DB;
        }

        .nav-pill {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        ::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
        }
    </style>
</head>
<body class="overflow-x-hidden pb-10 flex flex-col min-h-screen">

    <nav class="pt-6 px-8 flex justify-between items-center mb-8 relative z-50">
        <div class="nav-pill rounded-full px-6 py-3 flex space-x-6 text-sm font-bold tracking-wider uppercase text-white">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-300 transition">Home</a>
            <a href="{{ route('user.catalog') }}" class="text-blue-300 hover:text-white transition border-b-2 border-blue-300 pb-1">Catalog</a>
            <a href="{{ route('user.destination') }}" class="hover:text-blue-300 transition">Destination</a>
            <a href="{{ route('user.orders') }}" class="hover:text-blue-300 transition">Orders</a>
        </div>
        
        <div class="flex space-x-4 items-center">
            <a href="{{ route('user.profile') }}" class="w-10 h-10 rounded-full nav-pill flex items-center justify-center hover:bg-white/20 transition text-white overflow-hidden relative border border-white/20">
                @if(Auth::check() && Auth::user()->profile_photo)
                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="w-full h-full object-cover absolute inset-0">
                @else
                    <i class="fa-solid fa-user text-lg"></i>
                @endif
            </a>

            <div class="relative inline-block text-left">
                <button onclick="toggleNotif()" id="bellButton" class="w-10 h-10 rounded-full nav-pill flex items-center justify-center hover:bg-white/20 transition text-white relative z-50">
                    <i class="fa-solid fa-bell text-lg"></i>
                    @php
                        $recentBookings = \App\Models\Booking::where('user_id', Auth::id())->orderBy('created_at', 'desc')->take(5)->get();
                    @endphp
                    @if($recentBookings->count() > 0)
                        <span class="absolute top-0 right-0 w-3 h-3 bg-red-500 rounded-full border-2 border-[#1A1F36]"></span>
                    @endif
                </button>

                <div id="notifPanel" class="hidden absolute right-0 mt-4 w-80 bg-[#7A8CA5] rounded-2xl shadow-2xl z-40 transform transition-all duration-300 opacity-0 scale-95 origin-top-right">
                    <div class="absolute -top-2 right-4 w-5 h-5 bg-[#7A8CA5] transform rotate-45 rounded-sm"></div>
                    <div class="relative z-10 p-6">
                        <h3 class="text-white font-bebas tracking-widest text-lg mb-5 uppercase">NOTIFICATION</h3>
                        <div class="space-y-5 max-h-64 overflow-y-auto pr-2" style="scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.5) transparent;">
                            @forelse($recentBookings as $notif)
                                @php $days = \Carbon\Carbon::parse($notif->start_date)->diffInDays($notif->end_date) + 1; @endphp
                                <div class="flex items-start gap-4">
                                    <div class="w-3 h-3 bg-white rounded-full mt-1.5 flex-shrink-0 shadow-sm"></div>
                                    <div>
                                        <h4 class="text-white text-sm font-bold tracking-wider uppercase drop-shadow-sm">BOOKING BERHASIL</h4>
                                        <p class="text-gray-100 text-xs mt-1 leading-relaxed">Berhasil melakukan booking selama {{ $days }} hari</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-200 text-xs text-center italic mt-2">Belum ada aktivitas booking.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 lg:px-8 flex-grow relative z-10">
        
        <div class="text-[#6C82A3] text-sm font-medium tracking-widest uppercase mb-4">
            <a href="{{ route('user.catalog') }}" class="hover:text-white transition">CATALOG</a> > <span class="text-white">{{ $car->name }}</span>
        </div>

        @if ($errors->any())
            <div class="bg-red-500/80 text-white p-4 rounded-xl mb-6 shadow-lg border border-red-400">
                <p class="font-bold mb-2"><i class="fa-solid fa-triangle-exclamation"></i> Gagal memproses booking:</p>
                <ul class="list-disc pl-5 text-sm font-bold">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <div class="lg:col-span-7">
                <div class="bg-gradient-to-b from-[#4A5A80] to-[#2B3553] rounded-2xl p-8 mb-6 flex justify-center items-center h-[400px]">
                    <img src="{{ asset($car->image_path) }}" alt="{{ $car->name }}" class="max-w-full max-h-full object-contain drop-shadow-2xl">
                </div>

                <h1 class="text-5xl font-bold font-bebas tracking-wide uppercase mb-1">{{ $car->name }}</h1>
                <div class="text-[#7A9FE0] text-5xl font-bold mb-6">
                    Rp{{ number_format($car->price_per_day, 0, ',', '.') }}<span class="text-lg font-normal text-[#6C82A3]">/day</span>
                </div>

                <p class="text-gray-300 text-sm leading-relaxed mb-8">{{ $car->description }}</p>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-8">
                    <div class="spec-badge rounded-md px-3 py-2 text-center text-xs font-semibold tracking-wider">{{ $car->year }}</div>
                    <div class="spec-badge rounded-md px-3 py-2 text-center text-xs font-semibold tracking-wider uppercase">{{ $car->transmission }}</div>
                    <div class="spec-badge rounded-md px-3 py-2 text-center text-xs font-semibold tracking-wider">{{ $car->seats }} SEATS</div>
                    <div class="spec-badge rounded-md px-3 py-2 text-center text-xs font-semibold tracking-wider uppercase">{{ $car->color }}</div>
                    <div class="spec-badge rounded-md px-3 py-2 text-center text-xs font-semibold tracking-wider uppercase">{{ $car->fuel_type }}</div>
                    <div class="spec-badge rounded-md px-3 py-2 text-center text-xs font-semibold tracking-wider uppercase">{{ $car->engine_capacity ?? '-' }}</div>
                    <div class="spec-badge rounded-md px-3 py-2 text-center text-xs font-semibold tracking-wider uppercase">{{ $car->luggage_capacity ?? '-' }} SUITCASES</div>
                    <div class="spec-badge rounded-md px-3 py-2 text-center text-xs font-semibold tracking-wider uppercase">{{ $car->license_plate }}</div>
                </div>

                <h2 class="text-2xl font-bold font-bebas tracking-wide uppercase mb-4">FEATURES & FACILITIES</h2>
                <div class="flex flex-wrap gap-3">
                    @if($car->facilities)
                        @foreach($car->facilities as $facility)
                            <div class="spec-badge rounded-md px-4 py-2 text-xs font-bold tracking-wider uppercase">{{ $facility }}</div>
                        @endforeach
                    @else
                        <p class="text-sm text-gray-400">No additional facilities listed.</p>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-5">
                <form id="bookingForm" action="{{ route('user.checkout.process', $car->slug) }}" method="POST" enctype="multipart/form-data" class="bg-[#242B42] rounded-2xl p-6 shadow-xl border border-[#323A56]">
                    @csrf
                    
                    <input type="hidden" name="car_id" value="{{ $car->id }}">
                    <input type="hidden" name="total_price" id="hidden_total_price" value="{{ $car->price_per_day }}">
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-[#6C82A3] text-xs font-semibold uppercase tracking-wider mb-2">DATE</label>
                            <input type="date" name="start_date" class="form-input w-full rounded-md px-3 py-2 text-sm" required min="{{ date('Y-m-d') }}">
                        </div>
                        <div>
                            <label class="block text-[#6C82A3] text-xs font-semibold uppercase tracking-wider mb-2">DURATION</label>
                            <div class="flex h-[38px]">
                                <button type="button" id="btn-minus" class="form-input rounded-l-md px-3 flex items-center justify-center hover:bg-[#323A56]"><i class="fa-solid fa-minus text-xs"></i></button>
                                <input type="text" name="duration" id="duration-input" value="1" class="form-input w-full text-center border-x-0 text-sm font-bold" readonly>
                                <button type="button" id="btn-plus" class="form-input rounded-r-md px-3 flex items-center justify-center hover:bg-[#323A56]"><i class="fa-solid fa-plus text-xs"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-[#6C82A3] text-xs font-semibold uppercase tracking-wider mb-2">NAME</label>
                        <input type="text" name="renter_name" placeholder="Full Name (as per ID)" class="form-input w-full rounded-md px-3 py-2 text-sm" required value="{{ old('renter_name') }}">
                    </div>
                    <div class="mb-4">
                        <label class="block text-[#6C82A3] text-xs font-semibold uppercase tracking-wider mb-2">PHONE NUMBER</label>
                        <input type="text" name="renter_phone" placeholder="+6285-------" class="form-input w-full rounded-md px-3 py-2 text-sm" required value="{{ old('renter_phone') }}">
                    </div>
                    <div class="mb-4">
                        <label class="block text-[#6C82A3] text-xs font-semibold uppercase tracking-wider mb-2">ID NUMBER (KTP / PASSPORT)</label>
                        <input type="text" name="renter_id_number" placeholder="1803---------" class="form-input w-full rounded-md px-3 py-2 text-sm" required value="{{ old('renter_id_number') }}">
                    </div>
                    <div class="mb-6">
                        <label class="block text-[#6C82A3] text-xs font-semibold uppercase tracking-wider mb-2">PICKUP TIME</label>
                        <input type="time" name="pickup_time" class="form-input w-full rounded-md px-3 py-2 text-sm" required value="{{ old('pickup_time') }}">
                    </div>

                    <div class="mb-4">
                        <label class="block text-[#6C82A3] text-xs font-semibold uppercase tracking-wider mb-1">
                            RENTER DOCUMENTS <span class="normal-case text-[10px] text-gray-400 font-normal">(Max 10MB | JPG, PNG)</span>
                        </label>
                        <p class="text-[10px] text-red-400 font-medium italic mb-2">*Wajib upload KTP atau Passport (salah satu)</p>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-6">
                        
                        <label class="border border-[#323A56] rounded-lg flex flex-col items-center justify-center cursor-pointer hover:bg-white/5 transition h-24 relative overflow-hidden group">
                            <div id="text-ktp" class="flex flex-col items-center gap-2 pointer-events-none p-4 z-0">
                                <i class="fa-solid fa-arrow-up-from-bracket text-[#D1D5DB]"></i>
                                <span class="text-xs text-center text-[#D1D5DB]">Upload KTP</span>
                            </div>
                            <img id="preview-ktp" src="" class="hidden absolute inset-0 w-full h-full object-cover z-10 pointer-events-none">
                            <div id="overlay-ktp" class="hidden absolute inset-0 bg-black/60 z-10 flex items-center justify-center pointer-events-none transition group-hover:bg-black/40">
                                <span class="text-white font-bold text-xs tracking-widest">KTP</span>
                            </div>
                            <input type="file" name="doc_ktp" class="absolute inset-0 opacity-0 cursor-pointer z-20" accept=".jpg,.jpeg,.png,image/jpeg,image/png" onchange="previewDocument(event, 'preview-ktp', 'text-ktp', 'overlay-ktp')">
                        </label>

                        <label class="border border-[#323A56] rounded-lg flex flex-col items-center justify-center cursor-pointer hover:bg-white/5 transition h-24 relative overflow-hidden group">
                            <div id="text-sim" class="flex flex-col items-center gap-2 pointer-events-none p-4 z-0">
                                <i class="fa-solid fa-arrow-up-from-bracket text-[#D1D5DB]"></i>
                                <span class="text-xs text-center text-[#D1D5DB]">*Upload SIM</span>
                            </div>
                            <img id="preview-sim" src="" class="hidden absolute inset-0 w-full h-full object-cover z-10 pointer-events-none">
                            <div id="overlay-sim" class="hidden absolute inset-0 bg-black/60 z-10 flex items-center justify-center pointer-events-none transition group-hover:bg-black/40">
                                <span class="text-white font-bold text-xs tracking-widest">SIM</span>
                            </div>
                            <input type="file" name="doc_sim" class="absolute inset-0 opacity-0 cursor-pointer z-20" accept=".jpg,.jpeg,.png,image/jpeg,image/png" required onchange="previewDocument(event, 'preview-sim', 'text-sim', 'overlay-sim')">
                        </label>

                        <label class="border border-[#323A56] rounded-lg flex flex-col items-center justify-center cursor-pointer hover:bg-white/5 transition h-24 relative overflow-hidden group">
                            <div id="text-passport" class="flex flex-col items-center gap-2 pointer-events-none p-4 z-0">
                                <i class="fa-solid fa-arrow-up-from-bracket text-[#D1D5DB]"></i>
                                <span class="text-xs text-center text-[#D1D5DB]">Upload Passport</span>
                            </div>
                            <img id="preview-passport" src="" class="hidden absolute inset-0 w-full h-full object-cover z-10 pointer-events-none">
                            <div id="overlay-passport" class="hidden absolute inset-0 bg-black/60 z-10 flex items-center justify-center pointer-events-none transition group-hover:bg-black/40">
                                <span class="text-white font-bold text-xs tracking-widest">PASSPORT</span>
                            </div>
                            <input type="file" name="doc_passport" class="absolute inset-0 opacity-0 cursor-pointer z-20" accept=".jpg,.jpeg,.png,image/jpeg,image/png" onchange="previewDocument(event, 'preview-passport', 'text-passport', 'overlay-passport')">
                        </label>

                        <label class="border border-[#323A56] rounded-lg flex flex-col items-center justify-center cursor-pointer hover:bg-white/5 transition h-24 relative overflow-hidden group">
                            <div id="text-selfie" class="flex flex-col items-center gap-2 pointer-events-none p-4 z-0">
                                <i class="fa-solid fa-arrow-up-from-bracket text-[#D1D5DB]"></i>
                                <span class="text-xs text-center text-[#D1D5DB]">*Selfie with ID</span>
                            </div>
                            <img id="preview-selfie" src="" class="hidden absolute inset-0 w-full h-full object-cover z-10 pointer-events-none">
                            <div id="overlay-selfie" class="hidden absolute inset-0 bg-black/60 z-10 flex items-center justify-center pointer-events-none transition group-hover:bg-black/40">
                                <span class="text-white font-bold text-xs tracking-widest text-center">SELFIE<br>W/ ID</span>
                            </div>
                            <input type="file" name="doc_selfie" class="absolute inset-0 opacity-0 cursor-pointer z-20" accept=".jpg,.jpeg,.png,image/jpeg,image/png" required onchange="previewDocument(event, 'preview-selfie', 'text-selfie', 'overlay-selfie')">
                        </label>
                    </div>

                    <label class="block text-[#6C82A3] text-xs font-semibold uppercase tracking-wider mb-2">PAYMENT METHODS</label>
                    <div class="mb-4">
                        <div class="inline-block border border-[#5C72A6] bg-[#323A56] text-white text-xs font-medium px-4 py-2 rounded-md">
                            QRIS
                        </div>
                    </div>

                    <label class="form-input border border-[#323A56] rounded-md p-3 flex items-center gap-3 cursor-pointer hover:bg-white/5 transition mb-6">
                        <i class="fa-solid fa-user text-[#D1D5DB]"></i>
                        <div class="flex-grow">
                            <div class="text-sm font-bold text-white">With Driver</div>
                            <div class="text-[10px] text-[#6C82A3]">+200.000/day</div>
                        </div>
                        <input type="checkbox" name="with_driver" id="driver-checkbox" value="1" class="w-4 h-4 rounded border-gray-300 text-[#5C72A6] focus:ring-[#5C72A6]">
                    </label>

                    <div class="border-t border-[#323A56] pt-4 mb-4">
                        <div class="flex justify-between text-xs text-[#D1D5DB] mb-2 font-medium" id="calc-breakdown">
                            Rp{{ number_format($car->price_per_day, 0, ',', '.') }} x 1
                            <span>Rp{{ number_format($car->price_per_day, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-end">
                            <div class="text-lg font-bold text-white uppercase tracking-wider">TOTAL</div>
                            <div class="text-2xl font-bold text-white" id="total-price">
                                Rp{{ number_format($car->price_per_day, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-b from-[#6D819C] to-[#4C5B79] hover:from-[#7B90AE] hover:to-[#5A6A8E] text-white font-bold py-3 rounded-full transition shadow-lg text-lg">
                        Book Now
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Logika Dropdown Notifikasi
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

        // Validasi KTP atau Passport Saat Submit Form
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            const ktpInput = document.querySelector('input[name="doc_ktp"]');
            const passportInput = document.querySelector('input[name="doc_passport"]');

            // Kalau dua-duanya kosong, cegah form terkirim dan keluarin alert
            if (ktpInput.files.length === 0 && passportInput.files.length === 0) {
                e.preventDefault();
                alert('Peringatan: Anda WAJIB mengunggah KTP atau Passport! (Pilih salah satu)');
            }
        });

        // Fungsi buat validasi tipe & preview dokumen
        function previewDocument(event, previewId, textId, overlayId) {
            const input = event.target;
            const preview = document.getElementById(previewId);
            const text = document.getElementById(textId);
            const overlay = document.getElementById(overlayId);

            if (input.files && input.files[0]) {
                const file = input.files[0];
                
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!validTypes.includes(file.type)) {
                    alert("Gagal: File harus berformat JPG, JPEG, atau PNG.");
                    input.value = ""; 
                    return;
                }

                const maxSize = 10 * 1024 * 1024; // 10MB in bytes
                if (file.size > maxSize) {
                    alert("Gagal: Ukuran file terlalu besar! Maksimal 10 MB.");
                    input.value = ""; 
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    text.classList.add('hidden'); 
                    if (overlay) overlay.classList.remove('hidden'); 
                }
                reader.readAsDataURL(file);
            } else {
                preview.src = "";
                preview.classList.add('hidden');
                text.classList.remove('hidden');
                if (overlay) overlay.classList.add('hidden');
            }
        }

        // Kalkulasi Harga Otomatis
        document.addEventListener("DOMContentLoaded", function() {
            const basePrice = {{ $car->price_per_day }}; 
            const driverPrice = 200000;
            
            const btnMinus = document.getElementById('btn-minus');
            const btnPlus = document.getElementById('btn-plus');
            const inputDuration = document.getElementById('duration-input');
            const checkboxDriver = document.getElementById('driver-checkbox');
            const hiddenTotalPrice = document.getElementById('hidden_total_price'); 
            
            const breakdownText = document.getElementById('calc-breakdown');
            const totalPriceText = document.getElementById('total-price');

            function formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number).replace('Rp', 'Rp ').trim();
            }

            function calculateTotal() {
                let duration = parseInt(inputDuration.value) || 1;
                let isDriver = checkboxDriver.checked;
                
                let carTotal = basePrice * duration;
                let extraDriver = isDriver ? (driverPrice * duration) : 0;
                let grandTotal = carTotal + extraDriver;

                let driverBreakdown = isDriver ? `<br>+ Supir: Rp 200.000 x ${duration}` : '';
                breakdownText.innerHTML = `Rp ${basePrice.toLocaleString('id-ID')} x ${duration} ${driverBreakdown} <span class="text-right ml-auto">Rp ${grandTotal.toLocaleString('id-ID')}</span>`;
                totalPriceText.innerText = formatRupiah(grandTotal);
                
                hiddenTotalPrice.value = grandTotal;
            }

            btnMinus.addEventListener('click', () => {
                let val = parseInt(inputDuration.value);
                if (val > 1) {
                    inputDuration.value = val - 1;
                    calculateTotal();
                }
            });

            btnPlus.addEventListener('click', () => {
                let val = parseInt(inputDuration.value);
                inputDuration.value = val + 1;
                calculateTotal();
            });

            checkboxDriver.addEventListener('change', calculateTotal);
        });
    </script>
</body>
</html>