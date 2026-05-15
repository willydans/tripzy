<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tripzy</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
<body class="bg-split min-h-screen flex items-center justify-center p-4 md:p-0">

    <div class="w-full h-full md:h-screen grid grid-cols-1 md:grid-cols-2 relative gap-8 md:gap-0 max-w-lg md:max-w-none py-8 md:py-0">
        
        <div class="hidden md:block absolute left-1/2 top-0 bottom-0 w-[1px] bg-white/10 -translate-x-1/2"></div>

        <div class="flex items-center justify-center md:p-8 relative z-10 order-1">
            <div class="glass-card w-full max-w-md rounded-2xl p-6 md:p-10 flex flex-col relative z-10">
                <h2 class="text-2xl md:text-3xl font-bold text-[#1C2C4A] text-center mb-6 drop-shadow-sm">Login here.</h2>
                
                @if(session('success'))
                    <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4 text-xs md:text-sm text-center font-medium shadow-sm border border-green-200">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any() || session('error'))
                    <div class="bg-red-100 text-red-600 p-3 rounded-lg mb-4 text-xs md:text-sm text-center font-medium shadow-sm border border-red-200">
                        {{ session('error') ?? $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="flex flex-col">
                    @csrf 
                    
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" class="input-glass w-full rounded-lg py-2.5 md:py-3 px-4 mb-4 md:mb-5 text-[#1C2C4A] font-medium text-sm md:text-base" required>
                    
                    <input type="password" name="password" placeholder="Password" class="input-glass w-full rounded-lg py-2.5 md:py-3 px-4 mb-4 text-[#1C2C4A] font-medium text-sm md:text-base" required>
                    
                    <div class="flex justify-between items-center text-[10px] md:text-xs text-white mb-6 font-medium opacity-90">
                        <label class="flex items-center gap-2 cursor-pointer hover:text-blue-200 transition">
                            <input type="checkbox" name="remember" class="rounded w-3 h-3 border-none bg-white/50 text-blue-500 focus:ring-0 cursor-pointer"> 
                            Remember me
                        </label>
                        <a href="#" class="hover:text-blue-200 transition">Forgot Password?</a>
                    </div>
                    
                    <div class="flex flex-col items-center gap-4 mt-2">
                        <button type="submit" class="bg-[#B3CBF2] text-white font-semibold py-2.5 px-10 md:px-12 rounded-full hover:bg-white hover:text-[#4A6EB0] transition duration-300 shadow-md text-sm md:text-base w-full">
                            Login
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

        <div class="flex items-center justify-center md:p-8 relative z-10 order-2 mt-4 md:mt-0">
            <div class="text-center max-w-md text-white">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4 md:mb-6 leading-[1.1] drop-shadow-md">
                    Start your<br class="hidden md:block">journey now
                </h1>
                <p class="text-sm md:text-base lg:text-lg opacity-90 mb-8 md:mb-12 leading-relaxed drop-shadow-sm px-4 md:px-0">
                    If you don't have an account yet, join us<br class="hidden md:block">and start your journey
                </p>
                <a href="{{ route('register') }}" class="inline-block border border-white/60 text-white py-2 px-12 md:py-2.5 md:px-12 rounded-full hover:bg-white hover:text-[#1C2C4A] font-medium transition duration-300 shadow-sm backdrop-blur-sm text-sm md:text-base">
                    Register
                </a>
            </div>
        </div>

    </div>

</body>
</html>