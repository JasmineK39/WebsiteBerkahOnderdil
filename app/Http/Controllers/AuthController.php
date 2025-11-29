<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Cari User berdasarkan Email
        $user = User::where('email', $request->email)->first();

        // 3. Cek Password
        if (! $user || ! Hash::check($request->password, $user->password)) {
            // Jika salah, kirim error ke Vue
            throw ValidationException::withMessages([
                'email' => ['Kombinasi email dan password salah.'],
            ]);
        }
        if ($user->status !== 'active') {
            return response()->json([
                'message' => 'Akun belum terverifikasi, silahkan cek email kode OTP.'
            ], 403);
        }
        // 4. GENERATE TOKEN (Bagian Inti Sanctum)
        // 'auth_token' hanyalah nama bebas untuk token ini
        $token = $user->createToken('auth_token')->plainTextToken;

        // 5. Kirim Response ke Vue (Berisi Token & User)
        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token, // <-- Ini yang akan disimpan di localStorage Vue
            'user' => $user    // <-- Ini untuk cek role (admin/customer)
        ]);
    }
    public function register(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:150|unique:users', // Cek agar email tidak kembar
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed', // 'confirmed' berarti harus cocok dengan password_confirmation
            'captcha_token' => 'required',
        ]);
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
        'secret' => env('RECAPTCHA_SECRET'),
        'response' => $request->captcha_token,
    ]);

    $result = $response->json();

    if (!$result['success']) {
        return response()->json(['error' => 'Captcha verification failed'], 422);
    }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password), // Password wajib di-hash
            'role' => 'customer', // Default role user baru adalah customer
            'status' => 'verify',
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;
        $otp = strval(rand(100000, 999999));
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(5),
            'resend_count' => 0,
            'last_resend_at' => now(),
        ]);
        $user->save();

        Mail::to($user->email)->send(new \App\Mail\SendOtpMail($otp));
        return response()->json([
            'message' => 'Registrasi berhasil',
            'token' => $token,
            'user' => $user
        ], 201);
    }
    public function google_redirect()
    {
        /** @var \Laravel\Socialite\Two\GoogleProvider $driver */
        $driver = Socialite::driver('google');

        return $driver->stateless()->redirect();
    }
    public function google_callback()
    {
        /** @var \Laravel\Socialite\Two\GoogleProvider $driver */
        $driver = Socialite::driver('google');
        
        $googleUser = $driver->stateless()->user();
        $user = User::where('email', $googleUser->getEmail())->first();
         if (!$user) {
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password' => bcrypt(uniqid()),
                'status' => 'active',
                'role' => 'customer',
            ]);}
         if($user&&$user->status==='banned'){
            return redirect('/login')->with('error', 'Akun Anda dibanned. Silahkan hubungi admin.');
        }
        $token = $user->createToken('auth_token')->plainTextToken;

        // Redirect back to the frontend SPA so it can store the token.
        // Use URL fragment (hash) instead of query param so token is not sent to server.
        $redirectUrl = url('/') . '#token=' . $token;
        return redirect()->to($redirectUrl);
    }
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || strval($user->otp) !== strval($request->otp)) {
            return response()->json(['message' => 'Kode OTP salah'], 400);
        }

        if (now()->greaterThan($user->otp_expires_at)) {
            return response()->json(['message' => 'Kode OTP expired'], 400);
        }

        $user->update([
            'otp' => null,
            'otp_expires_at' => null,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        return response()->json(['message' => 'Verifikasi berhasil, silahkan login kembali','user' => $user]);
       
    }
    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) return response()->json(['message' => 'Email tidak ditemukan'], 404);
         // Tambahkan cek 30 detik
        if ($user->last_resend_at && $user->last_resend_at->addSeconds(30) > now()) {
            return response()->json(['message' => 'Tunggu 30 detik sebelum resend'], 429);
        }
        if ($user->last_resend_at && $user->last_resend_at->addMinutes(10) < now()) {
            $user->resend_count = 0;
            $user->save();
        }
        // Cek limit
        if ($user->resend_count >= 3) {
            return response()->json(['message' => 'Maksimal 3 kali resend dalam 10 menit'], 429);
        }
        $otp = strval(rand(100000,999999));
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(5),
            'resend_count' => $user->resend_count + 1,
            'last_resend_at' => now(),
        ]);

        Mail::to($user->email)->send(new \App\Mail\SendOtpMail($otp));

        return response()->json(['message'=>'OTP baru telah dikirim']);
    }

    // Opsional: Logout
    public function logout(Request $request)
    {
        // Hapus token yang sedang dipakai
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout berhasil']);
    }
}