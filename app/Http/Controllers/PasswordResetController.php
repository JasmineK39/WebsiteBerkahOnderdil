<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Email reset password berhasil dikirim. Silakan cek email Anda.'
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Email tidak ditemukan.'
        ], 404);
    }
     public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed'
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        if($status === Password::PASSWORD_RESET){
            return response()->json(['message'=>'Password berhasil direset.']);
        }

        return response()->json(['message'=>'Token reset password tidak valid atau email salah.'], 400);
    }
}
