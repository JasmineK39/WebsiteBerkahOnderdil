<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Keranjang;
use App\Models\ItemCard;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log; // Import Log Facade

class KeranjangController extends Controller
{
    /**
     * Tampilkan isi keranjang
     * Endpoint: GET /api/cart

     */
    public function index()
    {

        if (Auth::check()) {
            $user = Auth::user();
            
            // Dapatkan atau buat keranjang user
            $keranjang = $user->keranjang()->firstOrCreate(['user_id' => $user->id]);
            
            $items = $keranjang->items()->with('sparepart')->get()->map(function ($item) {

                // Tambahkan pengecekan ini untuk memastikan ItemCard tidak merujuk ke Sparepart yang sudah dihapus
                if (!$item->sparepart) {
                    // Item ini tidak valid, abaikan (atau hapus dari DB, tergantung kebijakan)
                    Log::warning('Item keranjang ID ' . $item->id . ' merujuk ke sparepart yang hilang.');
                    return null;
                }
                
                // Pastikan harga adalah angka yang valid, fallback ke 0 jika null/tidak ada
                $finalPrice = $item->price ?? $item->sparepart->price ?? 0;
                

                return [
                    'id' => $item->sparepart_id, 
                    'keranjang_item_id' => $item->id, 
                    'name' => $item->sparepart->name,
                    'price' => $finalPrice, 

                    'quantity' => $item->quantity,
                    'image' => $item->sparepart->image,
                ];
            })->filter()->values();

            return response()->json(['items' => $items]);
        }
        return response()->json(['items' => []], 401);
    }

    /**
     * Tambahkan atau perbarui item di keranjang
     * Endpoint: POST /api/cart
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        
        // Perbaiki validasi quantity agar tidak boleh kurang dari 0
        $request->validate([
            'sparepart_id' => 'required|exists:spareparts,id',
            'quantity' => 'required|integer', // Tidak lagi not_in:0 karena kita mungkin menghapus item dengan quantity <= 0
        ]);

        $user = Auth::user();
        $sparepartId = $request->sparepart_id;
        $changeQuantity = (int)$request->quantity; // Pastikan ini integer

        try {
            DB::beginTransaction();
            
            $sparepart = Sparepart::findOrFail($sparepartId);

            
            // 💡 PERBAIKAN KRITIS UNTUK ERROR 500: Ambil harga Sparepart dan pastikan itu bukan NULL.
            // Jika price dari sparepart null, defaultkan ke 0.
            $sparepartPrice = $sparepart->price ?? 0; 
            if (!is_numeric($sparepartPrice)) {
                 $sparepartPrice = 0; // Fallback ekstra jika harga bukan numerik
            }
            
            $keranjang = $user->keranjang()->firstOrCreate(['user_id' => $user->id]);

            $itemCard = ItemCard::where('keranjang_id', $keranjang->id)
                                 ->where('sparepart_id', $sparepartId)
                                 ->first();

            if ($itemCard) {
                $newQuantity = $itemCard->quantity + $changeQuantity;

                if ($newQuantity > 0) {
                    $itemCard->quantity = $newQuantity;
                    $itemCard->save();
                } else {

                    // Jika kuantitas baru <= 0, hapus item dari keranjang

                    $itemCard->delete();
                }

            } elseif ($changeQuantity > 0) {

                // Jika item belum ada dan quantity > 0, buat item baru.

                ItemCard::create([
                    'keranjang_id' => $keranjang->id,
                    'sparepart_id' => $sparepartId,
                    'quantity' => $changeQuantity,

                    'price' => $sparepartPrice // Menggunakan harga yang sudah divalidasi
                ]);
            }
            // Jika item belum ada dan changeQuantity <= 0, maka tidak ada yang dilakukan, yang sudah benar.


            DB::commit();
            return response()->json(['message' => 'Keranjang berhasil diperbarui.'], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            // 💡 PERBAIKAN DIAGNOSTIK: Tambahkan pesan error yang jelas ke respons JSON.
            Log::error('Gagal memperbarui keranjang untuk user ' . $user->id . ': ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            // Mengembalikan pesan error agar frontend dapat mendiagnosis
            return response()->json([
                'message' => 'Gagal memperbarui keranjang.', 
                'internal_error' => $e->getMessage() // Sediakan pesan internal error untuk debugging
            ], 500);

        }
    }

    /**
     * Hapus item dari keranjang
     * Endpoint: DELETE /api/cart/{sparepart_id}
     */
    public function destroy($sparepartId)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $user = Auth::user();

        // 💡 PERBAIKAN: Gunakan try-catch untuk mencegah error 500 jika ada masalah relasi
        try {
            $deleted = $user->keranjang?->items()
                             ->where('sparepart_id', $sparepartId)
                             ->delete();
            
            if ($deleted) {
                return response()->json(['message' => 'Item berhasil dihapus dari keranjang.'], 200);
            }
        } catch (\Exception $e) {
             Log::error('Gagal menghapus item keranjang: ' . $e->getMessage());
             return response()->json(['message' => 'Gagal menghapus item karena kesalahan server.', 'internal_error' => $e->getMessage()], 500);
        }


        return response()->json(['message' => 'Item tidak ditemukan di keranjang.'], 404);
    }

    /**
     * Bersihkan semua item dari keranjang
     * Endpoint: DELETE /api/cart
     */
    public function clear()
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $user = Auth::user();
        $keranjang = $user->keranjang;


        try {
             if ($keranjang) {
                $keranjang->items()->delete();
                return response()->json(['message' => 'Keranjang berhasil dikosongkan.'], 200);
             }
        } catch (\Exception $e) {
             Log::error('Gagal membersihkan keranjang: ' . $e->getMessage());
             return response()->json(['message' => 'Gagal membersihkan keranjang karena kesalahan server.', 'internal_error' => $e->getMessage()], 500);

        }
        
        return response()->json(['message' => 'Keranjang sudah kosong.'], 200);
    }
}