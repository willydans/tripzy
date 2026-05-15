<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Tripzy</title>
    <style>
        /* Reset Styles */
        body, p, h1, h2, h3, h4, h5, h6 { margin: 0; padding: 0; }
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            background-color: #EBF1FA; 
            -webkit-font-smoothing: antialiased; 
            -webkit-text-size-adjust: none; 
            width: 100% !important; 
            height: 100%; 
        }
        
        /* Container Setup */
        .wrapper { padding: 40px 20px; display: flex; justify-content: center; }
        .container { 
            max-width: 600px; 
            margin: 0 auto; 
            background-color: #ffffff; 
            border-radius: 16px; 
            overflow: hidden; 
            box-shadow: 0 10px 25px rgba(28, 44, 74, 0.05); 
        }
        
        /* Header */
        .header { 
            background: linear-gradient(135deg, #1C2C4A 0%, #4A6EB0 100%); 
            padding: 35px 20px; 
            text-align: center; 
        }
        .header h1 { 
            color: #ffffff; 
            font-size: 32px; 
            letter-spacing: 3px; 
            font-weight: 800; 
            font-style: italic;
            text-transform: uppercase;
        }
        
        /* Body Content */
        .content { padding: 40px 30px; color: #1C2C4A; line-height: 1.6; }
        .content h2 { font-size: 20px; font-weight: 700; margin-bottom: 15px; }
        .content p { font-size: 15px; color: #5C72A6; margin-bottom: 25px; }
        
        /* OTP Box */
        .otp-box { 
            background-color: #F4F7FC; 
            border: 2px dashed #4A6EB0; 
            border-radius: 12px; 
            padding: 25px; 
            text-align: center; 
            margin: 10px 0 30px 0; 
        }
        .otp-code { 
            font-size: 46px !important; 
            font-weight: 800; 
            color: #1C2C4A !important; 
            letter-spacing: 12px; 
            margin: 0 !important;
            padding-left: 12px; /* Center alignment fix for letter-spacing */
        }
        
        /* Warning Message */
        .warning-box { 
            background-color: #FEF2F2; 
            border-left: 4px solid #EF4444; 
            padding: 15px; 
            border-radius: 0 8px 8px 0;
            margin-bottom: 30px;
        }
        .warning-box p { color: #B91C1C; font-size: 13px; margin: 0; font-weight: 500; }
        
        /* Footer */
        .footer { 
            background-color: #F8FAFC; 
            padding: 25px 20px; 
            text-align: center; 
            border-top: 1px solid #E2EAF6; 
        }
        .footer p { font-size: 12px; color: #8CA1C4; margin-bottom: 5px; }
        
        /* Responsive */
        @media only screen and (max-width: 600px) {
            .content { padding: 30px 20px; }
            .otp-code { font-size: 36px !important; letter-spacing: 8px; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <table class="container" width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td>
                    <div class="header">
                        <h1>TRIPZY</h1>
                    </div>
                    
                    <div class="content">
                        <h2>Halo, Pengguna Tripzy!</h2>
                        <p>Kami menerima permintaan untuk mengatur ulang kata sandi (reset password) pada akun Anda. Untuk melanjutkan proses keamanan ini, silakan gunakan kode OTP berikut:</p>
                        
                        <div class="otp-box">
                            <p class="otp-code">{{ $otp }}</p>
                        </div>
                        
                        <div class="warning-box">
                            <p>⚠️ <strong>PENTING:</strong> Kode OTP ini hanya berlaku selama <strong>10 menit</strong>. JANGAN bagikan kode ini kepada siapa pun, termasuk pihak yang mengatasnamakan Tripzy.</p>
                        </div>
                        
                        <p style="margin-bottom: 5px;">Jika Anda tidak merasa melakukan permintaan reset kata sandi ini, abaikan email ini. Akun Anda akan tetap aman.</p>
                        
                        <p style="margin-top: 30px; margin-bottom: 0;">
                            Terima kasih,<br>
                            <strong style="color: #1C2C4A;">Tim Tripzy Car Rental</strong>
                        </p>
                    </div>
                    
                    <div class="footer">
                        <p>&copy; {{ date('Y') }} Tripzy Car Rental. Hak cipta dilindungi undang-undang.</p>
                        <p>Jl. Prof. Dr. Ir. Sumantri Brojonegoro No.1, Bandar Lampung</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>