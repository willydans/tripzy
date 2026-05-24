<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Tripzy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .bg-split { background: linear-gradient(135deg, #2D4875 0%, #080D1A 40%, #5672A1 100%); }
        .glass-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.4) 0%, rgba(255, 255, 255, 0.1) 100%);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }
        .input-glass {
            background: rgba(232, 240, 254, 0.75);
            border: 1px solid transparent;
            transition: all 0.3s;
        }
        .input-glass:focus {
            background: rgba(255, 255, 255, 0.9);
            outline: none;
            border-color: rgba(255, 255, 255, 0.5);
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
        }
        .input-glass::placeholder { color: #7E97C2; }
        /* Style untuk dropdown select biar cakep */
        select.input-glass { color: #7E97C2; }
        select.input-glass option { color: #1C2C4A; }
    </style>
</head>
<body class="bg-split min-h-screen flex items-center justify-center p-4 md:p-0">

    <div class="w-full h-full md:h-screen grid grid-cols-1 md:grid-cols-2 relative gap-8 md:gap-0 max-w-lg md:max-w-none">
        <div class="hidden md:block absolute left-1/2 top-0 bottom-0 w-[1px] bg-white/10 -translate-x-1/2"></div>

        <div class="flex items-center justify-center md:p-8 relative z-10 order-2 md:order-1 pt-6 md:pt-0">
            <div class="text-center max-w-md text-white">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4 md:mb-6 leading-[1.1] drop-shadow-md">
                    Hello<br class="hidden md:block">Friends
                </h1>
                <p class="text-sm md:text-base lg:text-lg opacity-90 mb-8 md:mb-12 leading-relaxed drop-shadow-sm px-4 md:px-0">
                    If you already have an account login<br class="hidden md:block">here and enjoy
                </p>
                <a href="{{ route('login') }}" class="inline-block border border-white/60 text-white py-2 px-12 md:py-2.5 md:px-16 rounded-full hover:bg-white hover:text-[#1C2C4A] font-medium transition duration-300 shadow-sm backdrop-blur-sm text-sm md:text-base">
                    Login
                </a>
            </div>
        </div>

        <div class="flex items-center justify-center md:p-8 order-1 md:order-2 mt-8 md:mt-0">
            <div class="glass-card w-full max-w-md rounded-2xl p-6 md:p-8 flex flex-col relative z-10">
                <h2 class="text-2xl md:text-3xl font-bold text-[#1C2C4A] text-center mb-6 drop-shadow-sm">Register here.</h2>
                
                @if ($errors->any())
                    <div class="bg-red-100 text-red-600 p-3 rounded-lg mb-4 text-xs md:text-sm shadow-sm border border-red-200">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST" class="flex flex-col gap-3 md:gap-4">
                    @csrf 
                    <input type="text" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" class="input-glass w-full rounded-lg py-2.5 px-4 text-[#1C2C4A] font-medium text-xs md:text-sm" required>
                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" class="input-glass w-full rounded-lg py-2.5 px-4 text-[#1C2C4A] font-medium text-xs md:text-sm" required>
                    <input type="tel" name="nomor_hp" placeholder="Nomor Hp" value="{{ old('nomor_hp') }}" class="input-glass w-full rounded-lg py-2.5 px-4 text-[#1C2C4A] font-medium text-xs md:text-sm" required>
                    
                    <div class="relative">
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="input-glass w-full rounded-lg py-2.5 px-4 text-[#7E97C2] font-medium text-xs md:text-sm appearance-none" required>
                    </div>
                    
                    <div class="relative">
                        <select name="jenis_kelamin" class="input-glass w-full rounded-lg py-2.5 px-4 font-medium text-xs md:text-sm appearance-none" required>
                            <option value="" disabled selected>Jenis Kelamin</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[#7E97C2]">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>

                    <input type="password" name="password" placeholder="Password" class="input-glass w-full rounded-lg py-2.5 px-4 text-[#1C2C4A] font-medium text-xs md:text-sm" required>
                    
                    <div class="flex flex-col items-center mt-2 md:mt-4 gap-4">
                        <button type="submit" class="bg-[#B3CBF2] text-white font-semibold py-2.5 px-10 md:px-12 rounded-full hover:bg-white hover:text-[#4A6EB0] transition duration-300 shadow-md text-sm md:text-base w-full">
                            Register
                        </button>

                        <div class="w-full flex items-center justify-between opacity-70">
                            <hr class="w-full border-white/30">
                            <span class="p-2 text-white/80 text-[10px] font-medium tracking-widest">OR</span>
                            <hr class="w-full border-white/30">
                        </div>

                        <a href="{{ route('google.login') }}" class="w-full flex items-center justify-center gap-3 py-2.5 rounded-full border border-white/40 text-white font-medium hover:bg-white/20 hover:border-white transition duration-300 shadow-sm text-sm md:text-base backdrop-blur-sm">
                            <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5 drop-shadow-md" alt="Google">
                            Continue with Google
                        </a>
                    </div>
                </form>
            </div>
        </div>

    </div>
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