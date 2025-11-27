<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Akun</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px;">
    <div style="max-width: 500px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px;">
        
        <h2 style="text-align: center; color: #333;">Verifikasi Akun Anda</h2>

        <p>Halo,</p>
        <p>Terima kasih telah mendaftar. Untuk menyelesaikan proses pendaftaran, silakan gunakan kode OTP berikut:</p>

        <h1 style="text-align: center; letter-spacing: 4px; font-size: 32px; color: #2d89ef;">
            {{ $otp }}
        </h1>

        <p style="text-align: center; color: #666;">
            Kode ini berlaku selama <b>5 menit</b>.
        </p>

        <p>Jika Anda tidak meminta OTP ini, abaikan email ini.</p>

        <p style="margin-top: 30px;">Salam,<br><b>Tim Developer</b></p>
    </div>
</body>
</html>
