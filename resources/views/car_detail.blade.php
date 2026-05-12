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
            overflow-x: hidden;
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

        /* Sembunyikan scrollbar untuk navigasi mobile */
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="overflow-x-hidden pb-10 flex flex-col min-h-screen">

    <nav class="pt-6 px-4 md:px-8 flex flex-col md:flex-row justify-between items-center gap-4 md:gap-0 mb-8 max-w-7xl mx-auto w-full relative z-50">
        
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
            <a href="{{ route('user.catalog') }}" class="text-blue-300 hover:text-white transition border-b-2 border-blue-300 pb-1">Catalog</a>
            <a href="{{ route('user.destination') }}" class="hover:text-blue-300 transition">Destination</a>
            <a href="{{ route('user.orders') }}" class="hover:text-blue-300 transition">Orders</a>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 lg:px-8 flex-grow relative z-10">
        
        <div class="text-[#6C82A3] text-[10px] md:text-sm font-medium tracking-widest uppercase mb-4 md:mb-6">
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

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8">
            
            <div class="lg:col-span-7">
                <div class="bg-gradient-to-b from-[#4A5A80] to-[#2B3553] rounded-2xl p-4 md:p-8 mb-6 flex justify-center items-center h-[250px] md:h-[400px]">
                    <img src="{{ asset($car->image_path) }}" alt="{{ $car->name }}" class="max-w-full max-h-full object-contain drop-shadow-2xl">
                </div>

                <h1 class="text-3xl md:text-5xl font-bold font-bebas tracking-wide uppercase mb-1">{{ $car->name }}</h1>
                <div class="text-[#7A9FE0] text-3xl md:text-5xl font-bold mb-4 md:mb-6">
                    Rp{{ number_format($car->price_per_day, 0, ',', '.') }}<span class="text-sm md:text-lg font-normal text-[#6C82A3]">/day</span>
                </div>

                <p class="text-gray-300 text-xs md:text-sm leading-relaxed mb-6 md:mb-8">{{ $car->description }}</p>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 md:gap-3 mb-6 md:mb-8">
                    <div class="spec-badge rounded-md px-2 md:px-3 py-2 text-center text-[10px] md:text-xs font-semibold tracking-wider">{{ $car->year }}</div>
                    <div class="spec-badge rounded-md px-2 md:px-3 py-2 text-center text-[10px] md:text-xs font-semibold tracking-wider uppercase">{{ $car->transmission }}</div>
                    <div class="spec-badge rounded-md px-2 md:px-3 py-2 text-center text-[10px] md:text-xs font-semibold tracking-wider">{{ $car->seats }} SEATS</div>
                    <div class="spec-badge rounded-md px-2 md:px-3 py-2 text-center text-[10px] md:text-xs font-semibold tracking-wider uppercase">{{ $car->color }}</div>
                    <div class="spec-badge rounded-md px-2 md:px-3 py-2 text-center text-[10px] md:text-xs font-semibold tracking-wider uppercase">{{ $car->fuel_type }}</div>
                    <div class="spec-badge rounded-md px-2 md:px-3 py-2 text-center text-[10px] md:text-xs font-semibold tracking-wider uppercase">{{ $car->engine_capacity ?? '-' }}</div>
                    <div class="spec-badge rounded-md px-2 md:px-3 py-2 text-center text-[10px] md:text-xs font-semibold tracking-wider uppercase">{{ $car->luggage_capacity ?? '-' }} SUITCASES</div>
                    <div class="spec-badge rounded-md px-2 md:px-3 py-2 text-center text-[10px] md:text-xs font-semibold tracking-wider uppercase">{{ $car->license_plate }}</div>
                </div>

                <h2 class="text-xl md:text-2xl font-bold font-bebas tracking-wide uppercase mb-3 md:mb-4">FEATURES & FACILITIES</h2>
                <div class="flex flex-wrap gap-2 md:gap-3 mb-8 lg:mb-0">
                    @if($car->facilities)
                        @foreach($car->facilities as $facility)
                            <div class="spec-badge rounded-md px-3 md:px-4 py-1.5 md:py-2 text-[10px] md:text-xs font-bold tracking-wider uppercase">{{ $facility }}</div>
                        @endforeach
                    @else
                        <p class="text-xs md:text-sm text-gray-400">No additional facilities listed.</p>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-5">
                <form id="bookingForm" action="{{ route('user.checkout.process', $car->slug) }}" method="POST" enctype="multipart/form-data" class="bg-[#242B42] rounded-2xl p-5 md:p-6 shadow-xl border border-[#323A56]">
                    @csrf
                    
                    <input type="hidden" name="car_id" value="{{ $car->id }}">
                    <input type="hidden" name="total_price" id="hidden_total_price" value="{{ $car->price_per_day }}">
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-[#6C82A3] text-[10px] md:text-xs font-semibold uppercase tracking-wider mb-1 md:mb-2">DATE</label>
                            <input type="date" name="start_date" class="form-input w-full rounded-md px-3 py-2 text-sm" required min="{{ date('Y-m-d') }}">
                        </div>
                        <div>
                            <label class="block text-[#6C82A3] text-[10px] md:text-xs font-semibold uppercase tracking-wider mb-1 md:mb-2">DURATION</label>
                            <div class="flex h-[38px]">
                                <button type="button" id="btn-minus" class="form-input rounded-l-md px-3 flex items-center justify-center hover:bg-[#323A56]"><i class="fa-solid fa-minus text-xs"></i></button>
                                <input type="text" name="duration" id="duration-input" value="1" class="form-input w-full text-center border-x-0 text-sm font-bold" readonly>
                                <button type="button" id="btn-plus" class="form-input rounded-r-md px-3 flex items-center justify-center hover:bg-[#323A56]"><i class="fa-solid fa-plus text-xs"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-[#6C82A3] text-[10px] md:text-xs font-semibold uppercase tracking-wider mb-1 md:mb-2">NAME</label>
                        <input type="text" name="renter_name" placeholder="Full Name (as per ID)" class="form-input w-full rounded-md px-3 py-2 text-sm" required value="{{ old('renter_name') }}">
                    </div>
                    <div class="mb-4">
                        <label class="block text-[#6C82A3] text-[10px] md:text-xs font-semibold uppercase tracking-wider mb-1 md:mb-2">PHONE NUMBER</label>
                        <input type="text" name="renter_phone" placeholder="+6285-------" class="form-input w-full rounded-md px-3 py-2 text-sm" required value="{{ old('renter_phone') }}">
                    </div>
                    <div class="mb-4">
                        <label class="block text-[#6C82A3] text-[10px] md:text-xs font-semibold uppercase tracking-wider mb-1 md:mb-2">ID NUMBER (KTP / PASSPORT)</label>
                        <input type="text" name="renter_id_number" placeholder="1803---------" class="form-input w-full rounded-md px-3 py-2 text-sm" required value="{{ old('renter_id_number') }}">
                    </div>
                    <div class="mb-6">
                        <label class="block text-[#6C82A3] text-[10px] md:text-xs font-semibold uppercase tracking-wider mb-1 md:mb-2">PICKUP TIME</label>
                        <input type="time" name="pickup_time" class="form-input w-full rounded-md px-3 py-2 text-sm" required value="{{ old('pickup_time') }}">
                    </div>

                    <div class="mb-3 md:mb-4">
                        <label class="block text-[#6C82A3] text-[10px] md:text-xs font-semibold uppercase tracking-wider mb-1">
                            RENTER DOCUMENTS <span class="normal-case text-[9px] md:text-[10px] text-gray-400 font-normal">(Max 10MB | JPG, PNG)</span>
                        </label>
                        <p class="text-[9px] md:text-[10px] text-red-400 font-medium italic mb-2">*Wajib upload KTP atau Passport (salah satu)</p>
                    </div>

                    <div class="grid grid-cols-2 gap-2 md:gap-3 mb-6">
                        
                        <label class="border border-[#323A56] rounded-lg flex flex-col items-center justify-center cursor-pointer hover:bg-white/5 transition h-20 md:h-24 relative overflow-hidden group">
                            <div id="text-ktp" class="flex flex-col items-center gap-1 md:gap-2 pointer-events-none p-2 md:p-4 z-0">
                                <i class="fa-solid fa-arrow-up-from-bracket text-[#D1D5DB] text-sm md:text-base"></i>
                                <span class="text-[10px] md:text-xs text-center text-[#D1D5DB]">Upload KTP</span>
                            </div>
                            <img id="preview-ktp" src="" class="hidden absolute inset-0 w-full h-full object-cover z-10 pointer-events-none">
                            <div id="overlay-ktp" class="hidden absolute inset-0 bg-black/60 z-10 flex items-center justify-center pointer-events-none transition group-hover:bg-black/40">
                                <span class="text-white font-bold text-[10px] md:text-xs tracking-widest">KTP</span>
                            </div>
                            <input type="file" name="doc_ktp" class="absolute inset-0 opacity-0 cursor-pointer z-20" accept=".jpg,.jpeg,.png,image/jpeg,image/png" onchange="previewDocument(event, 'preview-ktp', 'text-ktp', 'overlay-ktp')">
                        </label>

                        <label class="border border-[#323A56] rounded-lg flex flex-col items-center justify-center cursor-pointer hover:bg-white/5 transition h-20 md:h-24 relative overflow-hidden group">
                            <div id="text-sim" class="flex flex-col items-center gap-1 md:gap-2 pointer-events-none p-2 md:p-4 z-0">
                                <i class="fa-solid fa-arrow-up-from-bracket text-[#D1D5DB] text-sm md:text-base"></i>
                                <span class="text-[10px] md:text-xs text-center text-[#D1D5DB]">*Upload SIM</span>
                            </div>
                            <img id="preview-sim" src="" class="hidden absolute inset-0 w-full h-full object-cover z-10 pointer-events-none">
                            <div id="overlay-sim" class="hidden absolute inset-0 bg-black/60 z-10 flex items-center justify-center pointer-events-none transition group-hover:bg-black/40">
                                <span class="text-white font-bold text-[10px] md:text-xs tracking-widest">SIM</span>
                            </div>
                            <input type="file" name="doc_sim" class="absolute inset-0 opacity-0 cursor-pointer z-20" accept=".jpg,.jpeg,.png,image/jpeg,image/png" required onchange="previewDocument(event, 'preview-sim', 'text-sim', 'overlay-sim')">
                        </label>

                        <label class="border border-[#323A56] rounded-lg flex flex-col items-center justify-center cursor-pointer hover:bg-white/5 transition h-20 md:h-24 relative overflow-hidden group">
                            <div id="text-passport" class="flex flex-col items-center gap-1 md:gap-2 pointer-events-none p-2 md:p-4 z-0">
                                <i class="fa-solid fa-arrow-up-from-bracket text-[#D1D5DB] text-sm md:text-base"></i>
                                <span class="text-[10px] md:text-xs text-center text-[#D1D5DB]">Passport</span>
                            </div>
                            <img id="preview-passport" src="" class="hidden absolute inset-0 w-full h-full object-cover z-10 pointer-events-none">
                            <div id="overlay-passport" class="hidden absolute inset-0 bg-black/60 z-10 flex items-center justify-center pointer-events-none transition group-hover:bg-black/40">
                                <span class="text-white font-bold text-[10px] md:text-xs tracking-widest">PASSPORT</span>
                            </div>
                            <input type="file" name="doc_passport" class="absolute inset-0 opacity-0 cursor-pointer z-20" accept=".jpg,.jpeg,.png,image/jpeg,image/png" onchange="previewDocument(event, 'preview-passport', 'text-passport', 'overlay-passport')">
                        </label>

                        <label class="border border-[#323A56] rounded-lg flex flex-col items-center justify-center cursor-pointer hover:bg-white/5 transition h-20 md:h-24 relative overflow-hidden group">
                            <div id="text-selfie" class="flex flex-col items-center gap-1 md:gap-2 pointer-events-none p-2 md:p-4 z-0">
                                <i class="fa-solid fa-arrow-up-from-bracket text-[#D1D5DB] text-sm md:text-base"></i>
                                <span class="text-[10px] md:text-xs text-center text-[#D1D5DB]">*Selfie ID</span>
                            </div>
                            <img id="preview-selfie" src="" class="hidden absolute inset-0 w-full h-full object-cover z-10 pointer-events-none">
                            <div id="overlay-selfie" class="hidden absolute inset-0 bg-black/60 z-10 flex items-center justify-center pointer-events-none transition group-hover:bg-black/40">
                                <span class="text-white font-bold text-[10px] md:text-xs tracking-widest text-center">SELFIE<br>W/ ID</span>
                            </div>
                            <input type="file" name="doc_selfie" class="absolute inset-0 opacity-0 cursor-pointer z-20" accept=".jpg,.jpeg,.png,image/jpeg,image/png" required onchange="previewDocument(event, 'preview-selfie', 'text-selfie', 'overlay-selfie')">
                        </label>
                    </div>

                    <label class="block text-[#6C82A3] text-[10px] md:text-xs font-semibold uppercase tracking-wider mb-2">PAYMENT METHODS</label>
                    <div class="mb-4">
                        <div class="inline-block border border-[#5C72A6] bg-[#323A56] text-white text-xs font-medium px-4 py-2 rounded-md shadow-sm">
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

                    <div class="border-t border-[#323A56] pt-4 mb-4 md:mb-6">
                        <div class="flex justify-between text-[11px] md:text-xs text-[#D1D5DB] mb-2 font-medium" id="calc-breakdown">
                            Rp{{ number_format($car->price_per_day, 0, ',', '.') }} x 1
                            <span>Rp{{ number_format($car->price_per_day, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-end">
                            <div class="text-base md:text-lg font-bold text-white uppercase tracking-wider">TOTAL</div>
                            <div class="text-xl md:text-2xl font-bold text-white" id="total-price">
                                Rp{{ number_format($car->price_per_day, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-b from-[#6D819C] to-[#4C5B79] hover:from-[#7B90AE] hover:to-[#5A6A8E] text-white font-bold py-3 md:py-4 rounded-full transition shadow-lg text-base md:text-lg">
                        Book Now
                    </button>
                </form>
            </div>
        </div>
    </div>

    <footer class="mt-auto border-t border-white/10 pt-10 pb-8 bg-[#171B2D]">
        <div class="max-w-7xl mx-auto px-4 lg:px-8 flex flex-col md:flex-row justify-between items-start gap-8 md:gap-0">
            <div class="w-full md:w-auto">
                <h2 class="text-4xl md:text-5xl font-bold font-bebas tracking-widest text-[#7A9FE0] mb-4">Tripzy</h2>
                <div class="flex space-x-4 text-2xl text-gray-400">
                    <a href="#" class="hover:text-white transition"><i class="fa-brands fa-discord"></i></a>
                    <a href="#" class="hover:text-white transition"><i class="fa-brands fa-whatsapp"></i></a>
                    <a href="#" class="hover:text-white transition"><i class="fa-brands fa-telegram"></i></a>
                    <a href="#" class="hover:text-white transition"><i class="fa-brands fa-instagram"></i></a>
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

            if (ktpInput.files.length === 0 && passportInput.files.length === 0) {
                e.preventDefault();
                alert('Peringatan: Anda WAJIB mengunggah KTP atau Passport! (Pilih salah satu)');
            }
        });

        // Fungsi preview dokumen upload
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