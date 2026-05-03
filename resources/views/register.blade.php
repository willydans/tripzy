<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Tripzy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
<body class="bg-split min-h-screen flex items-center justify-center">

    <div class="w-full h-screen grid grid-cols-1 md:grid-cols-2 relative">
        <div class="hidden md:block absolute left-1/2 top-0 bottom-0 w-[1px] bg-white/10 -translate-x-1/2"></div>

        <!-- KOLOM KIRI (Info & Login Button) -->
        <div class="flex items-center justify-center p-8 relative z-10 order-2 md:order-1">
            <div class="text-center max-w-md text-white">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-[1.1] drop-shadow-md">
                    Hello<br>Friends
                </h1>
                <p class="text-base md:text-lg opacity-90 mb-12 leading-relaxed drop-shadow-sm">
                    If you already have an account login<br>here and enjoy
                </p>
                <!-- Link balik ke halaman Login -->
                <a href="{{ route('login') }}" class="inline-block border border-white/60 text-white py-2.5 px-16 rounded-full hover:bg-white hover:text-[#1C2C4A] font-medium transition duration-300 shadow-sm backdrop-blur-sm">
                    Login
                </a>
            </div>
        </div>

        <!-- KOLOM KANAN (Form Register) -->
        <div class="flex items-center justify-center p-8 order-1 md:order-2">
            <div class="glass-card w-full max-w-md rounded-2xl p-8 flex flex-col relative z-10">
                <h2 class="text-3xl font-bold text-[#1C2C4A] text-center mb-6 drop-shadow-sm">Register here.</h2>
                
                <!-- Pesan Error kalo validasi gagal -->
                @if ($errors->any())
                    <div class="bg-red-100 text-red-600 p-3 rounded-lg mb-4 text-sm">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST" class="flex flex-col gap-3">
                    @csrf <!-- Wajib ada buat security Laravel -->
                    
                    <input type="text" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" class="input-glass w-full rounded-lg py-2.5 px-4 text-[#1C2C4A] font-medium text-sm" required>
                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" class="input-glass w-full rounded-lg py-2.5 px-4 text-[#1C2C4A] font-medium text-sm" required>
                    <input type="text" name="nomor_hp" placeholder="Nomor Hp" value="{{ old('nomor_hp') }}" class="input-glass w-full rounded-lg py-2.5 px-4 text-[#1C2C4A] font-medium text-sm" required>
                    
                    <!-- Pake input date biar gampang milih tanggal -->
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="input-glass w-full rounded-lg py-2.5 px-4 text-[#7E97C2] font-medium text-sm" required>
                    
                    <!-- Pake select dropdown buat Jenis Kelamin -->
                    <select name="jenis_kelamin" class="input-glass w-full rounded-lg py-2.5 px-4 font-medium text-sm" required>
                        <option value="" disabled selected>Jenis Kelamin</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>

                    <input type="password" name="password" placeholder="Password" class="input-glass w-full rounded-lg py-2.5 px-4 text-[#1C2C4A] font-medium text-sm" required>
                    
                    <div class="flex justify-center mt-4">
                        <button type="submit" class="bg-[#B3CBF2] text-white font-semibold py-2.5 px-12 rounded-full hover:bg-white hover:text-[#4A6EB0] transition duration-300 shadow-md">
                            Register
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</body>
</html>