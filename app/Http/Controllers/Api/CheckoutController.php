<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        // 1. Tambahkan validasi mendalam untuk item array
        $request->validate([
            'payment_method' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.sparepart_id' => 'required|integer|exists:spareparts,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'address' => 'nullable|string', // Tambahkan field ini jika FE mengirimnya
            'location' => 'nullable|string', // Tambahkan field ini jika FE mengirimnya
            'whatsapp_link' => 'nullable|string', // Tambahkan field ini jika FE mengirimnya
        ]);

        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $user = Auth::user();

        DB::beginTransaction();
        try {
            $total = collect($request->items)
                // Cast ke float/decimal secara eksplisit untuk perhitungan aman
                ->sum(fn ($i) => (float) $i['price'] * (int) $i['quantity']);

            // 2. Simpan transaksi
            $transaksi = Transaksi::create([
                'user_id' => $user->id,
                'total_amount' => $total,
                'payment_method' => $request->payment_method,
                // Mengambil field opsional dari request
                'address' => $request->address, 
                'location' => $request->location, 
                'whatsapp_link' => $request->whatsapp_link,
                'status' => 'pending',
            ]);

            // 3. Simpan detail transaksi
            foreach ($request->items as $item) {
                TransactionItem::create([
                    'transaksi_id' => $transaksi->id,
                    'sparepart_id' => (int) $item['sparepart_id'],
                    'quantity' => (int) $item['quantity'],
                    'price' => (float) $item['price'],
                ]);
            }

            // 4. Kosongkan keranjang setelah checkout
            $user->keranjang?->items()->delete();

            DB::commit();

            return response()->json([
                'message' => 'Checkout berhasil',
                'transaksi' => $transaksi, // Pastikan objek Transaksi dikirim
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            // 5. Logging error di backend untuk debugging
            \Illuminate\Support\Facades\Log::error("Checkout Failed for User ID {$user->id}: " . $e->getMessage());
            
            return response()->json([
                'message' => 'Checkout gagal: Terjadi kesalahan server.',
                'error_detail' => $e->getMessage() // Hapus di production
            ], 500);
        }
    }
}
