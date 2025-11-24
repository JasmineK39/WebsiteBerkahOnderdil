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
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:150|unique:users', // Cek agar email tidak kembar
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed', // 'confirmed' berarti harus cocok dengan password_confirmation
        ]);
        $request['status']="verify";
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password), // Password wajib di-hash
            'role' => 'customer', // Default role user baru adalah customer
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil',
            'token' => $token,
            'user' => $user
        ], 201);
    }

    // Opsional: Logout
    public function logout(Request $request)
    {
        // Hapus token yang sedang dipakai
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout berhasil']);
    }
}