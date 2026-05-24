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
        
        /* Modal Transition */
        .modal-active { overflow-y: hidden !important; }
        
        /* Input OTP khusus */
        .otp-input {
            width: 3rem; height: 3.5rem; text-align: center; font-size: 1.5rem; font-weight: 700;
            background: rgba(232, 240, 254, 0.75); border: 2px solid transparent; border-radius: 0.5rem; color: #1C2C4A; transition: all 0.2s;
        }
        .otp-input:focus { border-color: #4A6EB0; background: #fff; outline: none; box-shadow: 0 0 10px rgba(255, 255, 255, 0.3); }
    </style>
</head>
<body class="bg-split min-h-screen flex items-center justify-center p-4 md:p-0 relative">

    @if(session('success') || session('error') || $errors->any())
    <div id="toastNotification" class="fixed top-10 left-1/2 transform -translate-x-1/2 z-[100] transition-all duration-500 ease-in-out w-11/12 md:w-auto">
        <div class="{{ session('error') || $errors->any() ? 'bg-red-500/90 border-red-400' : 'bg-[#5C72A6]/90 border-[#4A6EB0]' }} backdrop-blur-md border px-6 md:px-10 py-4 md:py-5 rounded-2xl shadow-2xl flex flex-col md:flex-row items-center gap-3 md:gap-4 relative w-full md:min-w-[400px]">
            <button onclick="closeToast()" class="absolute top-2 right-4 text-white/80 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center text-white text-xl shadow-inner shrink-0">
                <i class="fa-solid {{ session('error') || $errors->any() ? 'fa-triangle-exclamation' : 'fa-check' }}"></i>
            </div>
            <p class="text-sm md:text-base font-bold text-center md:text-left tracking-wide text-white pr-4 leading-snug">
                {{ session('error') ?? session('success') ?? $errors->first() }}
            </p>
        </div>
    </div>
    <script>
        setTimeout(() => closeToast(), 6000); // Hilang otomatis dlm 6 detik
        function closeToast() {
            const toast = document.getElementById('toastNotification');
            if(toast) { toast.style.opacity = '0'; toast.style.transform = 'translate(-50%, -20px)'; setTimeout(() => toast.remove(), 500); }
        }
    </script>
    @endif

    <div class="w-full h-full md:h-screen grid grid-cols-1 md:grid-cols-2 relative gap-8 md:gap-0 max-w-lg md:max-w-none py-8 md:py-0">
        
        <div class="hidden md:block absolute left-1/2 top-0 bottom-0 w-[1px] bg-white/10 -translate-x-1/2"></div>

        <div class="flex items-center justify-center md:p-8 relative z-10 order-1">
            <div class="glass-card w-full max-w-md rounded-2xl p-6 md:p-10 flex flex-col relative z-10">
                <h2 class="text-2xl md:text-3xl font-bold text-[#1C2C4A] text-center mb-8 drop-shadow-sm">Login here.</h2>

                <form action="{{ route('login') }}" method="POST" class="flex flex-col">
                    @csrf 
                    
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" class="input-glass w-full rounded-lg py-2.5 md:py-3 px-4 mb-4 md:mb-5 text-[#1C2C4A] font-medium text-sm md:text-base" required>
                    
                    <input type="password" name="password" placeholder="Password" class="input-glass w-full rounded-lg py-2.5 md:py-3 px-4 mb-4 text-[#1C2C4A] font-medium text-sm md:text-base" required>
                    
                    <div class="flex justify-between items-center text-[10px] md:text-xs text-white mb-6 font-medium opacity-90">
                        <label class="flex items-center gap-2 cursor-pointer hover:text-blue-200 transition">
                            <input type="checkbox" name="remember" class="rounded w-3 h-3 border-none bg-white/50 text-blue-500 focus:ring-0 cursor-pointer"> 
                            Remember me
                        </label>
                        <button type="button" onclick="openModal('modalForgotEmail')" class="hover:text-blue-200 transition">Forgot Password?</button>
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

                        <a href="{{ route('google.login') ?? '#' }}" class="w-full flex items-center justify-center gap-3 py-2.5 rounded-full border border-white/40 text-white font-medium hover:bg-white/20 hover:border-white transition duration-300 shadow-sm text-sm md:text-base backdrop-blur-sm">
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
                <a href="{{ route('register') ?? '#' }}" class="inline-block border border-white/60 text-white py-2 px-12 md:py-2.5 md:px-12 rounded-full hover:bg-white hover:text-[#1C2C4A] font-medium transition duration-300 shadow-sm backdrop-blur-sm text-sm md:text-base">
                    Register
                </a>
            </div>
        </div>
    </div>

    <div id="modalForgotEmail" class="fixed inset-0 z-50 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300 px-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeModal('modalForgotEmail')"></div>
        <div class="glass-card w-full max-w-md relative z-10 rounded-2xl p-6 md:p-8 shadow-2xl transform scale-95 transition-transform duration-300" id="modalForgotEmailContent">
            <button onclick="closeModal('modalForgotEmail')" class="absolute top-4 right-4 text-white/70 hover:text-white"><i class="fa-solid fa-xmark text-xl"></i></button>
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl shadow-inner border border-white/30"><i class="fa-solid fa-envelope"></i></div>
                <h2 class="text-xl md:text-2xl font-bold text-white mb-2">Reset Password</h2>
                <p class="text-white/80 text-xs md:text-sm px-4">Masukkan email Anda. Kami akan mengirimkan kode OTP untuk mengatur ulang kata sandi.</p>
            </div>
            
            <form action="#" method="POST" onsubmit="processSendOTP(event)">
                @csrf
                <input type="email" name="email" placeholder="Masukkan Email Anda" class="input-glass w-full rounded-lg py-3 px-4 mb-6 text-[#1C2C4A] font-medium text-sm text-center" required>
                <button type="submit" class="w-full bg-[#B3CBF2] text-white font-bold py-3 rounded-full hover:bg-white hover:text-[#4A6EB0] transition shadow-md">Kirim Kode OTP</button>
            </form>
        </div>
    </div>

    <div id="modalVerifyOTP" class="fixed inset-0 z-50 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300 px-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeModal('modalVerifyOTP')"></div>
        <div class="glass-card w-full max-w-md relative z-10 rounded-2xl p-6 md:p-8 shadow-2xl transform scale-95 transition-transform duration-300" id="modalVerifyOTPContent">
            <button onclick="closeModal('modalVerifyOTP')" class="absolute top-4 right-4 text-white/70 hover:text-white"><i class="fa-solid fa-xmark text-xl"></i></button>
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl shadow-inner border border-white/30"><i class="fa-solid fa-shield-halved"></i></div>
                <h2 class="text-xl md:text-2xl font-bold text-white mb-2">Verifikasi OTP</h2>
                <p class="text-white/80 text-xs md:text-sm px-4">Kami telah mengirimkan 6 digit kode OTP ke email Anda. Cek kotak masuk atau spam.</p>
            </div>
            
            <form action="#" method="POST" onsubmit="processVerifyOTP(event)">
                @csrf
                <div class="flex justify-center gap-2 md:gap-3 mb-6">
                    <input type="text" maxlength="1" class="otp-input" oninput="moveToNext(this, 'otp2')" id="otp1" required>
                    <input type="text" maxlength="1" class="otp-input" oninput="moveToNext(this, 'otp3')" id="otp2" required>
                    <input type="text" maxlength="1" class="otp-input" oninput="moveToNext(this, 'otp4')" id="otp3" required>
                    <input type="text" maxlength="1" class="otp-input" oninput="moveToNext(this, 'otp5')" id="otp4" required>
                    <input type="text" maxlength="1" class="otp-input" oninput="moveToNext(this, 'otp6')" id="otp5" required>
                    <input type="text" maxlength="1" class="otp-input" id="otp6" required>
                </div>
                
                <div class="text-center mb-6">
                    <button type="button" class="text-blue-200 text-xs hover:text-white transition underline">Kirim Ulang Kode</button>
                </div>

                <button type="submit" class="w-full bg-[#B3CBF2] text-white font-bold py-3 rounded-full hover:bg-white hover:text-[#4A6EB0] transition shadow-md">Verifikasi</button>
            </form>
        </div>
    </div>

    <div id="modalResetPassword" class="fixed inset-0 z-50 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300 px-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeModal('modalResetPassword')"></div>
        <div class="glass-card w-full max-w-md relative z-10 rounded-2xl p-6 md:p-8 shadow-2xl transform scale-95 transition-transform duration-300" id="modalResetPasswordContent">
            <button onclick="closeModal('modalResetPassword')" class="absolute top-4 right-4 text-white/70 hover:text-white"><i class="fa-solid fa-xmark text-xl"></i></button>
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl shadow-inner border border-white/30"><i class="fa-solid fa-key"></i></div>
                <h2 class="text-xl md:text-2xl font-bold text-white mb-2">Password Baru</h2>
                <p class="text-white/80 text-xs md:text-sm px-4">Silakan masukkan password baru Anda yang kuat dan mudah diingat.</p>
            </div>
            
            <form action="#" method="POST" onsubmit="processResetPassword(event)">
                @csrf
                <input type="password" name="password" placeholder="Password Baru" class="input-glass w-full rounded-lg py-3 px-4 mb-4 text-[#1C2C4A] font-medium text-sm" required>
                <input type="password" name="password_confirmation" placeholder="Konfirmasi Password Baru" class="input-glass w-full rounded-lg py-3 px-4 mb-6 text-[#1C2C4A] font-medium text-sm" required>
                
                <button type="submit" class="w-full bg-[#B3CBF2] text-white font-bold py-3 rounded-full hover:bg-white hover:text-[#4A6EB0] transition shadow-md">Simpan Password</button>
            </form>
        </div>
    </div>

    <script>
        let userEmailForReset = '';

        // UI Modals Logic
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            const content = document.getElementById(modalId + 'Content');
            modal.classList.remove('opacity-0', 'pointer-events-none');
            setTimeout(() => { content.classList.remove('scale-95'); content.classList.add('scale-100'); }, 10);
            document.body.classList.add('modal-active');
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            const content = document.getElementById(modalId + 'Content');
            content.classList.remove('scale-100'); content.classList.add('scale-95');
            setTimeout(() => { modal.classList.add('opacity-0', 'pointer-events-none'); document.body.classList.remove('modal-active'); }, 200);
        }

        function moveToNext(current, nextFieldID) {
            if (current.value.length >= current.maxLength) {
                document.getElementById(nextFieldID).focus();
            }
        }

        // ==========================================
        // AJAX FETCH LOGIC
        // ==========================================

        async function processSendOTP(event) {
            event.preventDefault();
            const emailInput = event.target.querySelector('input[name="email"]').value;
            const btn = event.target.querySelector('button[type="submit"]');
            const csrfToken = document.querySelector('input[name="_token"]').value;

            btn.innerText = 'Mengirim...'; 
            btn.disabled = true;

            try {
                const response = await fetch('/forgot-password/send-otp', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ email: emailInput })
                });
                
                const data = await response.json();
                if(response.ok) {
                    userEmailForReset = emailInput; 
                    closeModal('modalForgotEmail');
                    setTimeout(() => { openModal('modalVerifyOTP'); }, 300);
                } else {
                    alert(data.message || 'Email tidak ditemukan di sistem kami.');
                }
            } catch (error) { 
                alert('Terjadi kesalahan jaringan.'); 
            }
            
            btn.innerText = 'Kirim Kode OTP'; 
            btn.disabled = false;
        }

        async function processVerifyOTP(event) {
            event.preventDefault();
            const otpCode = document.getElementById('otp1').value + document.getElementById('otp2').value + 
                            document.getElementById('otp3').value + document.getElementById('otp4').value + 
                            document.getElementById('otp5').value + document.getElementById('otp6').value;
            const btn = event.target.querySelector('button[type="submit"]');
            const csrfToken = document.querySelector('input[name="_token"]').value;

            btn.innerText = 'Memverifikasi...'; 
            btn.disabled = true;

            try {
                const response = await fetch('/forgot-password/verify-otp', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ email: userEmailForReset, otp: otpCode })
                });
                
                const data = await response.json();
                if(response.ok) {
                    closeModal('modalVerifyOTP');
                    setTimeout(() => { openModal('modalResetPassword'); }, 300);
                } else {
                    alert(data.message || 'Kode OTP salah!');
                }
            } catch (error) { 
                alert('Terjadi kesalahan jaringan.'); 
            }
            
            btn.innerText = 'Verifikasi'; 
            btn.disabled = false;
        }

        async function processResetPassword(event) {
            event.preventDefault();
            const pass = event.target.querySelector('input[name="password"]').value;
            const passConfirm = event.target.querySelector('input[name="password_confirmation"]').value;
            const btn = event.target.querySelector('button[type="submit"]');
            const csrfToken = document.querySelector('input[name="_token"]').value;

            if(pass !== passConfirm) { 
                alert('Password dan konfirmasi tidak sama!'); 
                return; 
            }

            btn.innerText = 'Menyimpan...'; 
            btn.disabled = true;

            try {
                const response = await fetch('/forgot-password/reset', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ email: userEmailForReset, password: pass, password_confirmation: passConfirm })
                });
                
                const data = await response.json();
                if(response.ok) {
                    alert('Sukses! ' + data.message);
                    closeModal('modalResetPassword');
                    window.location.reload(); 
                } else {
                    alert(data.message || 'Gagal mengubah password.');
                }
            } catch (error) { 
                alert('Terjadi kesalahan jaringan.'); 
            }
            
            btn.innerText = 'Simpan Password'; 
            btn.disabled = false;
        }
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