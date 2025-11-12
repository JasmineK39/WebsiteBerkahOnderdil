<?php

namespace App\Http\Controllers\Api; // <-- PENTING: Namespace harus sesuai dengan folder

use App\Http\Controllers\Controller; // Tetap impor Base Controller
use App\Models\Keranjang;
use App\Models\ItemCard;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KeranjangController extends Controller
{
    /**
     * Tampilkan isi keranjang
     * Endpoint: GET /api/cart
     * Akan merespons data jika login, atau 401 jika gagal otentikasi.
     */
    public function index()
    {
        // Cek autentikasi Sanctum (Bekerja karena route dilindungi auth:sanctum)
        if (Auth::check()) {
            $user = Auth::user();
            
            // Dapatkan atau buat keranjang user
            $keranjang = $user->keranjang()->firstOrCreate(['user_id' => $user->id]);
            
            $items = $keranjang->items()->with('sparepart')->get()->map(function ($item) {
                 if (!$item->sparepart) {
                    // Log the error or skip the item
                    return null;
                }
                // Pastikan struktur output cocok dengan yang diharapkan Pinia/Vue
                return [
                    'id' => $item->sparepart_id, 
                    'keranjang_item_id' => $item->id, 
                    'name' => $item->sparepart->name,
                    'price' => $item->price ?? $item->sparepart->price, 
                    'quantity' => $item->quantity,
                    'image' => $item->sparepart->image,
                ];
            })->filter()->values();

            return response()->json(['items' => $items]);
        }

        // Seharusnya ini tidak tercapai karena middleware 'auth:sanctum' akan menolaknya duluan.
        // Namun, jika Anda memindahkan route GET /cart keluar dari middleware, ini akan berjalan:
        return response()->json(['items' => []], 401);
    }

    /**
     * Tambahkan atau perbarui item di keranjang
     * Endpoint: POST /api/cart
     */
    public function store(Request $request)
    {
        // Auth::check() ini adalah pemeriksaan redundan yang baik, karena middleware sudah berjalan
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        
        $request->validate([
            'sparepart_id' => 'required|exists:spareparts,id',
            'quantity' => 'required|integer|not_in:0', 
        ]);

        $user = Auth::user();
        $sparepartId = $request->sparepart_id;
        $changeQuantity = $request->quantity; 

        try {
            DB::beginTransaction();
            
            $sparepart = Sparepart::findOrFail($sparepartId);

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
                    $itemCard->delete();
                }

            } elseif ($changeQuantity > 0) {
                ItemCard::create([
                    'keranjang_id' => $keranjang->id,
                    'sparepart_id' => $sparepartId,
                    'quantity' => $changeQuantity,
                    'price' => $sparepart->price 
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Keranjang berhasil diperbarui.'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            // In Production, use Laravel's logger
            return response()->json(['message' => 'Gagal memperbarui keranjang.', 'error' => $e->getMessage()], 500);
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

        $deleted = $user->keranjang?->items()
                        ->where('sparepart_id', $sparepartId)
                        ->delete();
        
        if ($deleted) {
            return response()->json(['message' => 'Item berhasil dihapus dari keranjang.'], 200);
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

        if ($keranjang) {
            $keranjang->items()->delete();
            return response()->json(['message' => 'Keranjang berhasil dikosongkan.'], 200);
        }
        
        return response()->json(['message' => 'Keranjang sudah kosong.'], 200);
    }
}