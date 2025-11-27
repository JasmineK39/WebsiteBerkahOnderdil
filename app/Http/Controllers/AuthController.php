<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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
        // Validasi
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:150|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Generate OTP
        $otp = rand(100000, 999999);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'status' => 'verify',
            'otp' => $otp,
            'otp_expired_at' => now()->addMinutes(5),
        ]);

        // Kirim OTP ke Email
        \Mail::to($user->email)->send(new \App\Mail\SendOtpMail($otp));

        return response()->json([
            'message' => 'Registrasi berhasil. Silakan verifikasi OTP yang dikirim ke email.',
            'user_id' => $user->id
        ], 201);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'otp'     => 'required'
        ]);

        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        if ($user->otp != $request->otp) {
            return response()->json(['message' => 'OTP salah'], 422);
        }

        if (now()->greaterThan($user->otp_expired_at)) {
            return response()->json(['message' => 'OTP kadaluarsa'], 422);
        }

        // Update status
        $user->status = 'active';
        $user->otp = null;
        $user->otp_expired_at = null;
        $user->save();

        return response()->json(['message' => 'Verifikasi berhasil']);
    }

    public function resendOtp(Request $request)
    {
        $request->validate(['user_id' => 'required']);

        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $otp = rand(100000, 999999);

        $user->otp = $otp;
        $user->otp_expired_at = now()->addMinutes(5);
        $user->save();

        Mail::to($user->email)->send(new SendOtpMail($otp));

        return response()->json(['message' => 'OTP baru telah dikirim']);
    }

    // Logout
    public function logout(Request $request)
    {
        // Hapus token yang sedang dipakai
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout berhasil']);
    }
}