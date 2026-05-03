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

        /* Custom Input Styling */
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
        .form-input::placeholder {
            color: #4A5578;
        }

        /* Badge Styling */
        .spec-badge {
            background-color: #1C2237;
            border: 1px solid #323A56;
            color: #D1D5DB;
        }

        /* Nav Pill */
        .nav-pill {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Ubah icon kalender jadi putih (untuk input date) */
        ::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
        }
    </style>
</head>
<body class="overflow-x-hidden pb-10">

    <!-- NAVBAR -->
    <nav class="pt-6 px-8 flex justify-between items-center mb-8">
        <div class="nav-pill rounded-full px-6 py-3 flex space-x-6 text-sm font-bold tracking-wider uppercase text-white">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-300 transition">Home</a>
            <a href="{{ route('user.catalog') }}" class="hover:text-blue-300 transition">Catalog</a>
            <a href="#" class="hover:text-blue-300 transition">Destination</a>
            <a href="#" class="hover:text-blue-300 transition">Orders</a>
        </div>
        <div class="flex space-x-4 items-center">
            <button class="w-10 h-10 rounded-full nav-pill flex items-center justify-center hover:bg-white/20 transition text-white">
                <i class="fa-solid fa-user text-lg"></i>
            </button>
            <button class="w-10 h-10 rounded-full nav-pill flex items-center justify-center hover:bg-white/20 transition text-white">
                <i class="fa-solid fa-bell text-lg"></i>
            </button>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <div class="max-w-7xl mx-auto px-4 lg:px-8">
        
        <!-- Breadcrumbs -->
        <div class="text-[#6C82A3] text-sm font-medium tracking-widest uppercase mb-4">
            <a href="{{ route('user.catalog') }}" class="hover:text-white transition">CATALOG</a> > <span class="text-white">{{ $car->name }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- LEFT COLUMN: CAR DETAILS -->
            <div class="lg:col-span-7">
                <!-- Car Image Background -->
                <div class="bg-gradient-to-b from-[#4A5A80] to-[#2B3553] rounded-2xl p-8 mb-6 flex justify-center items-center h-[400px]">
                    <img src="{{ asset($car->image_path) }}" alt="{{ $car->name }}" class="max-w-full max-h-full object-contain drop-shadow-2xl">
                </div>

                <!-- Car Title & Price -->
                <h1 class="text-5xl font-bold font-bebas tracking-wide uppercase mb-1">{{ $car->name }}</h1>
                <div class="text-[#7A9FE0] text-5xl font-bold mb-6">
                    Rp{{ number_format($car->price_per_day, 0, ',', '.') }}<span class="text-lg font-normal text-[#6C82A3]">/day</span>
                </div>

                <!-- Description -->
                <p class="text-gray-300 text-sm leading-relaxed mb-8">
                    {{ $car->description }}
                </p>

                <!-- Specifications Badges -->
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

                <!-- Features & Facilities -->
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

            <!-- RIGHT COLUMN: BOOKING FORM -->
            <div class="lg:col-span-5">
                <!-- Nantinya form action ngarah ke proses simpan database -->
                <form action="#" method="POST" enctype="multipart/form-data" class="bg-[#242B42] rounded-2xl p-6 shadow-xl border border-[#323A56]">
                    @csrf
                    
                    <!-- Date & Duration -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-[#6C82A3] text-xs font-semibold uppercase tracking-wider mb-2">DATE</label>
                            <input type="date" name="start_date" class="form-input w-full rounded-md px-3 py-2 text-sm" required>
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

                    <!-- Personal Info -->
                    <div class="mb-4">
                        <label class="block text-[#6C82A3] text-xs font-semibold uppercase tracking-wider mb-2">NAME</label>
                        <input type="text" name="renter_name" placeholder="Full Name (as per ID)" class="form-input w-full rounded-md px-3 py-2 text-sm" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-[#6C82A3] text-xs font-semibold uppercase tracking-wider mb-2">PHONE NUMBER</label>
                        <input type="text" name="renter_phone" placeholder="+6285-------" class="form-input w-full rounded-md px-3 py-2 text-sm" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-[#6C82A3] text-xs font-semibold uppercase tracking-wider mb-2">ID NUMBER</label>
                        <input type="text" name="renter_id_number" placeholder="1803---------" class="form-input w-full rounded-md px-3 py-2 text-sm" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-[#6C82A3] text-xs font-semibold uppercase tracking-wider mb-2">PICKUP TIME</label>
                        <input type="time" name="pickup_time" class="form-input w-full rounded-md px-3 py-2 text-sm" required>
                    </div>

                    <!-- Renter Documents (Upload File Custom UI with Preview) -->
                    <label class="block text-[#6C82A3] text-xs font-semibold uppercase tracking-wider mb-3">RENTER DOCUMENTS</label>
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        
                        <!-- KTP -->
                        <label class="border border-[#323A56] rounded-lg flex flex-col items-center justify-center cursor-pointer hover:bg-white/5 transition h-24 relative overflow-hidden">
                            <div id="text-ktp" class="flex flex-col items-center gap-2 pointer-events-none p-4">
                                <i class="fa-solid fa-arrow-up-from-bracket text-[#D1D5DB]"></i>
                                <span class="text-xs text-center text-[#D1D5DB]">*Upload KTP</span>
                            </div>
                            <img id="preview-ktp" src="" class="hidden absolute inset-0 w-full h-full object-cover z-10 pointer-events-none">
                            <input type="file" name="doc_ktp" class="absolute inset-0 opacity-0 cursor-pointer z-20" accept="image/*" required onchange="previewDocument(event, 'preview-ktp', 'text-ktp')">
                        </label>

                        <!-- SIM -->
                        <label class="border border-[#323A56] rounded-lg flex flex-col items-center justify-center cursor-pointer hover:bg-white/5 transition h-24 relative overflow-hidden">
                            <div id="text-sim" class="flex flex-col items-center gap-2 pointer-events-none p-4">
                                <i class="fa-solid fa-arrow-up-from-bracket text-[#D1D5DB]"></i>
                                <span class="text-xs text-center text-[#D1D5DB]">*Upload SIM</span>
                            </div>
                            <img id="preview-sim" src="" class="hidden absolute inset-0 w-full h-full object-cover z-10 pointer-events-none">
                            <input type="file" name="doc_sim" class="absolute inset-0 opacity-0 cursor-pointer z-20" accept="image/*" required onchange="previewDocument(event, 'preview-sim', 'text-sim')">
                        </label>

                        <!-- Passport -->
                        <label class="border border-[#323A56] rounded-lg flex flex-col items-center justify-center cursor-pointer hover:bg-white/5 transition h-24 relative overflow-hidden">
                            <div id="text-passport" class="flex flex-col items-center gap-2 pointer-events-none p-4">
                                <i class="fa-solid fa-arrow-up-from-bracket text-[#D1D5DB]"></i>
                                <span class="text-xs text-center text-[#D1D5DB]">Upload Passport</span>
                            </div>
                            <img id="preview-passport" src="" class="hidden absolute inset-0 w-full h-full object-cover z-10 pointer-events-none">
                            <input type="file" name="doc_passport" class="absolute inset-0 opacity-0 cursor-pointer z-20" accept="image/*" onchange="previewDocument(event, 'preview-passport', 'text-passport')">
                        </label>

                        <!-- Selfie -->
                        <label class="border border-[#323A56] rounded-lg flex flex-col items-center justify-center cursor-pointer hover:bg-white/5 transition h-24 relative overflow-hidden">
                            <div id="text-selfie" class="flex flex-col items-center gap-2 pointer-events-none p-4">
                                <i class="fa-solid fa-arrow-up-from-bracket text-[#D1D5DB]"></i>
                                <span class="text-xs text-center text-[#D1D5DB]">*Selfie with ID Card</span>
                            </div>
                            <img id="preview-selfie" src="" class="hidden absolute inset-0 w-full h-full object-cover z-10 pointer-events-none">
                            <input type="file" name="doc_selfie" class="absolute inset-0 opacity-0 cursor-pointer z-20" accept="image/*" required onchange="previewDocument(event, 'preview-selfie', 'text-selfie')">
                        </label>

                    </div>

                    <!-- Payment Methods -->
                    <label class="block text-[#6C82A3] text-xs font-semibold uppercase tracking-wider mb-2">PAYMENT METHODS</label>
                    <div class="mb-4">
                        <!-- Cuma ada 1 opsi, dibikin mirip button active -->
                        <div class="inline-block border border-[#5C72A6] bg-[#323A56] text-white text-xs font-medium px-4 py-2 rounded-md">
                            QRIS
                        </div>
                    </div>

                    <!-- With Driver Toggle -->
                    <label class="form-input border border-[#323A56] rounded-md p-3 flex items-center gap-3 cursor-pointer hover:bg-white/5 transition mb-6">
                        <i class="fa-solid fa-user text-[#D1D5DB]"></i>
                        <div class="flex-grow">
                            <div class="text-sm font-bold text-white">With Driver</div>
                            <div class="text-[10px] text-[#6C82A3]">+200.000/day</div>
                        </div>
                        <input type="checkbox" name="with_driver" id="driver-checkbox" class="w-4 h-4 rounded border-gray-300 text-[#5C72A6] focus:ring-[#5C72A6]">
                    </label>

                    <!-- Summary & Calculation -->
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

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-gradient-to-b from-[#6D819C] to-[#4C5B79] hover:from-[#7B90AE] hover:to-[#5A6A8E] text-white font-bold py-3 rounded-full transition shadow-lg text-lg">
                        Book Now
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT: Auto-Calculate Harga & Preview Gambar -->
    <script>
        // Fungsi buat preview dokumen (Bisa dipake berulang buat KTP, SIM, Passport, Selfie)
        function previewDocument(event, previewId, textId) {
            const input = event.target;
            const preview = document.getElementById(previewId);
            const text = document.getElementById(textId);

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    text.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = "";
                preview.classList.add('hidden');
                text.classList.remove('hidden');
            }
        }

        // Kalkulasi Harga (Tetap kayak sebelumnya)
        document.addEventListener("DOMContentLoaded", function() {
            const basePrice = {{ $car->price_per_day }}; 
            const driverPrice = 200000;
            
            const btnMinus = document.getElementById('btn-minus');
            const btnPlus = document.getElementById('btn-plus');
            const inputDuration = document.getElementById('duration-input');
            const checkboxDriver = document.getElementById('driver-checkbox');
            
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