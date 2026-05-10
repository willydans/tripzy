<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Tripzy</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #171B2D; /* Tema Dark Mode */
            color: white;
        }
        .nav-pill {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="flex flex-col min-h-screen">

    <!-- NAVBAR -->
    <nav class="pt-6 px-8 flex justify-between items-center mb-4">
        <div class="nav-pill rounded-full px-6 py-3 flex space-x-6 text-sm font-bold tracking-wider uppercase text-white">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-300 transition">Home</a>
            <a href="{{ route('user.catalog') }}" class="hover:text-blue-300 transition">Catalog</a>
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
            <button class="w-10 h-10 rounded-full nav-pill flex items-center justify-center hover:bg-white/20 transition text-white">
                <i class="fa-solid fa-bell text-lg"></i>
            </button>
        </div>
    </nav>

    <!-- MAIN PAYMENT CONTENT -->
    <main class="flex-grow flex items-center justify-center px-4 py-12 relative z-10">
        <!-- Card QRIS -->
        <div class="bg-[#6D819C] rounded-md shadow-2xl p-10 flex flex-col items-center w-full max-w-[450px]">
            
            <p class="text-white text-xs font-bold tracking-widest uppercase mb-1">BOOKING NUMBER</p>
            <h2 class="text-[#171B2D] text-3xl font-extrabold tracking-widest uppercase mb-8">
                {{ $booking->booking_code ?? 'ODR-202652' }}
            </h2>

            <!-- QR Code (Dinamis sesuai kode booking) -->
            <div class="bg-white p-4 rounded-md shadow-inner mb-10 w-64 h-64 flex items-center justify-center relative">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ $booking->booking_code ?? 'TRIPZY' }}" alt="QR Code" class="w-full h-full object-contain">
                <!-- Frame corner accents (optional buat nambah estetik) -->
                <div class="absolute top-2 left-2 w-4 h-4 border-t-4 border-l-4 border-black"></div>
                <div class="absolute top-2 right-2 w-4 h-4 border-t-4 border-r-4 border-black"></div>
                <div class="absolute bottom-2 left-2 w-4 h-4 border-b-4 border-l-4 border-black"></div>
                <div class="absolute bottom-2 right-2 w-4 h-4 border-b-4 border-r-4 border-black"></div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 w-full">
                <a href="{{ route('user.orders') }}" class="flex-1 text-center bg-white/20 hover:bg-white/30 border border-white/30 text-white font-semibold py-3 rounded-full transition shadow-sm text-sm">
                    View Booking
                </a>
                <a href="{{ route('user.catalog') }}" class="flex-1 text-center bg-transparent hover:bg-white/10 border border-white/30 text-white font-semibold py-3 rounded-full transition shadow-sm text-sm">
                    Back to Catalog
                </a>
            </div>

        </div>
    </main>

    <!-- FOOTER -->
    <footer class="mt-auto border-t border-[#323A56] pt-12 pb-8 bg-[#171B2D]">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between items-start">
            <div class="mb-8 md:mb-0">
                <h2 class="text-5xl font-bold font-bebas tracking-widest text-[#6D819C] mb-4">Tripzy</h2>
                <div class="flex space-x-4 text-2xl text-[#6D819C]">
                    <a href="#" class="hover:text-white transition"><i class="fa-brands fa-discord"></i></a>
                    <a href="#" class="hover:text-white transition"><i class="fa-brands fa-whatsapp"></i></a>
                    <a href="#" class="hover:text-white transition"><i class="fa-brands fa-telegram"></i></a>
                    <a href="#" class="hover:text-white transition"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>
            <div class="md:w-1/3">
                <h3 class="text-xl font-bold text-[#6D819C] mb-4">Contact Us</h3>
                <ul class="space-y-4 text-xs font-medium text-gray-400">
                    <li class="flex items-start gap-3"><i class="fa-solid fa-location-dot mt-1 text-[#6D819C]"></i><span>Jl. Prof. Dr. Ir. Sumantri Brojonegoro No.1, Gedong Meneng, Kec. Rajabasa, Bandar Lampung 35141</span></li>
                    <li class="flex items-center gap-3"><i class="fa-solid fa-phone text-[#6D819C]"></i><span>+6285276139801</span></li>
                    <li class="flex items-center gap-3"><i class="fa-solid fa-envelope text-[#6D819C]"></i><span>tripzy@gmail.com</span></li>
                    <li class="flex items-start gap-3"><i class="fa-solid fa-clock mt-1 text-[#6D819C]"></i><span>Senin - Minggu<br>24 Jam</span></li>
                </ul>
            </div>
        </div>
    </footer>

</body>
</html>