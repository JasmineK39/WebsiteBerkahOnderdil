<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Transaksi;
use App\Models\Review;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Mengambil detail transaksi dari database (tanpa simulasi data).
     * @param string $id ID Transaksi dari URL
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        Log::info("Permintaan detail transaksi diterima untuk ID: " . $id);

        try {
            $transaction = Transaksi::with('items.sparepart')->findOrFail($id);
            return response()->json($transaction);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Jika transaksi dengan ID tersebut tidak ditemukan, kembalikan 404
            Log::warning("Transaksi tidak ditemukan untuk ID: " . $id);
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        } catch (\Exception $e) {
            // Log error lain (misalnya, masalah database atau relasi)
            Log::error("Error saat mengambil transaksi ID {$id}: " . $e->getMessage());
            // Kembalikan error 500
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function reviewDetails($id)
    {
        Log::info("Permintaan detail review diterima untuk Transaksi ID: " . $id);

        try {
            // 1. Ambil data transaksi beserta item-item dan sparepartnya
            $transaction = Transaksi::with('items.sparepart')->findOrFail($id);
            
            // Dapatkan ID pengguna yang sedang login
            $userId = Auth::id();

            // 2. Iterasi setiap item transaksi dan cek status review
            $itemsWithReviewStatus = $transaction->items->map(function (TransactionItem $item) use ($userId, $id) {
                
                // Cek apakah sudah ada Review yang dibuat oleh user ini
                // untuk sparepart_id dan transaksi_id ini.
                $isReviewed = Review::where('user_id', $userId)
                                    ->where('sparepart_id', $item->sparepart_id)
                                    ->where('transaksi_id', $id)
                                    ->exists();
                
                // Tambahkan properti 'is_reviewed' ke objek item
                $item->is_reviewed = $isReviewed;
                
                return $item;
            });
            
            // 3. Susun respons (kita gunakan objek transaksi yang sudah dimodifikasi)
            $response = $transaction->toArray();
            $response['items'] = $itemsWithReviewStatus->toArray();

            return response()->json($response);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Transaksi untuk review tidak ditemukan ID: " . $id);
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        } catch (\Exception $e) {
            Log::error("Error saat mengambil detail review Transaksi ID {$id}: " . $e->getMessage());
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Memperbarui detail transaksi (Finalisasi Alamat/Pembayaran) dan mengarahkan ke WA.
     */
    public function finalize($id, Request $request)
    {
        $data = $request->validate([
            'address' => 'required|string|max:255',
            'payment_method' => 'required|string|in:Transfer,Cash',
        ]);
        
        try {
            $transaction = Transaksi::findOrFail($id);

            // Update data transaksi di database
            $transaction->address = $data['address'];
            $transaction->payment_method = $data['payment_method'];
            $transaction->status = 'shipped';
            $transaction->save();

            $formatRupiah = function($amount) {
                return number_format($amount, 0, ',', '.');
            };
            
            // Reload transaksi dengan item-itemnya untuk respons
            $transaction->load('items.sparepart');
            $totalRupiah = number_format($transaction->total_amount, 0, ',', '.');

            $itemDetails = "\n";
            foreach ($transaction->items as $item) {
                $name = $item->sparepart->name ?? 'Sparepart Dihapus';
                $subtotalRupiah = $formatRupiah($item->subtotal);
                $itemDetails .= "• {$name} ({$item->quantity} pcs) = Rp {$subtotalRupiah}\n";
            }
            $itemDetails .= "\n";

            $message = "Halo Admin Berkah Onderdil,\n\n";
            $message .= "Saya telah memfinalisasi pesanan. Bagaimana sistem pengirimannya?\n\n";
            $message .= "*Detail Pesanan:*\n";
            $message .= $itemDetails;
            $message .= "Total Pembayaran: Rp {$totalRupiah}\n";
            $message .= "Metode Pembayaran: {$transaction->payment_method}\n";
            $message .= "Alamat Pengiriman: {$transaction->address}\n";
            $message .= "Terima kasih.";

            $whatsappLink = "https://wa.me/6281326553304?text=" . urlencode($message);
            
            return response()->json([
                'message' => 'Transaksi berhasil difinalisasi.',
                // Mengembalikan objek transaksi yang sudah diperbarui
                'transaksi' => $transaction, 
                'whatsapp_link' => $whatsappLink,
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Transaksi tidak ditemukan saat finalisasi.'], 404);
        } catch (\Exception $e) {
            Log::error("Finalisasi Error Transaksi ID {$id}: " . $e->getMessage());
            return response()->json(['message' => 'Gagal memproses finalisasi.'], 500);
        }
    }
}