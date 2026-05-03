<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tripzy</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; }
        
        /* Background Gradient Utama */
        .bg-split {
            background: linear-gradient(135deg, #2D4875 0%, #080D1A 40%, #5672A1 100%);
        }

        /* Glassmorphism Card Style */
        .glass-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.4) 0%, rgba(255, 255, 255, 0.1) 100%);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        /* Input Field Style */
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
        .input-glass::placeholder {
            color: #7E97C2;
        }
    </style>
</head>
<body class="bg-split min-h-screen flex items-center justify-center">

    <!-- Container Utama -->
    <div class="w-full h-screen grid grid-cols-1 md:grid-cols-2 relative">
        
        <!-- Garis Pemisah Tengah (Samar-samar seperti di desain) -->
        <div class="hidden md:block absolute left-1/2 top-0 bottom-0 w-[1px] bg-white/10 -translate-x-1/2"></div>

        <!-- KOLOM KIRI (Form Login) -->
        <div class="flex items-center justify-center p-8">
            <div class="glass-card w-full max-w-md rounded-2xl p-10 flex flex-col relative z-10">
                <h2 class="text-3xl font-bold text-[#1C2C4A] text-center mb-6 drop-shadow-sm">Login here.</h2>
                
                <!-- Notifikasi Pesan Sukses (Habis Register) -->
                @if(session('success'))
                    <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4 text-sm text-center font-medium">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Notifikasi Pesan Error (Kalo salah password/email) -->
                @if ($errors->any())
                    <div class="bg-red-100 text-red-600 p-3 rounded-lg mb-4 text-sm text-center font-medium">
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- Form dicolok ke Route Login dan pake method POST -->
                <form action="{{ route('login') }}" method="POST" class="flex flex-col">
                    @csrf <!-- Wajib ada buat security Laravel -->
                    
                    <!-- Input Email (ditambahin name="email" dan old value) -->
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" class="input-glass w-full rounded-lg py-3 px-4 mb-5 text-[#1C2C4A] font-medium" required>
                    
                    <!-- Input Password (ditambahin name="password") -->
                    <input type="password" name="password" placeholder="Password" class="input-glass w-full rounded-lg py-3 px-4 mb-4 text-[#1C2C4A] font-medium" required>
                    
                    <!-- Remember me & Forgot Password -->
                    <div class="flex justify-between items-center text-xs text-white mb-10 font-medium opacity-90">
                        <label class="flex items-center gap-2 cursor-pointer hover:text-blue-200 transition">
                            <input type="checkbox" name="remember" class="rounded w-3 h-3 border-none bg-white/50 text-blue-500 focus:ring-0 cursor-pointer"> 
                            Remember me
                        </label>
                        <a href="#" class="hover:text-blue-200 transition">Forgot Password?</a>
                    </div>
                    
                    <!-- Login Button -->
                    <div class="flex justify-center">
                        <button type="submit" class="bg-[#B3CBF2] text-white font-semibold py-2.5 px-12 rounded-full hover:bg-white hover:text-[#4A6EB0] transition duration-300 shadow-md">
                            Login
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- KOLOM KANAN (Register Info) -->
        <div class="flex items-center justify-center p-8 relative z-10">
            <div class="text-center max-w-md text-white">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-[1.1] drop-shadow-md">
                    Start your<br>journey now
                </h1>
                <p class="text-base md:text-lg opacity-90 mb-12 leading-relaxed drop-shadow-sm">
                    If you don't have an account yet, join us<br>and start your journey
                </p>
                <!-- Link diganti ngarah ke halaman Register -->
                <a href="{{ route('register') }}" class="inline-block border border-white/60 text-white py-2.5 px-12 rounded-full hover:bg-white hover:text-[#1C2C4A] font-medium transition duration-300 shadow-sm backdrop-blur-sm">
                    Register
                </a>
            </div>
        </div>

    </div>

</body>
</html>