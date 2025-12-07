<?php

namespace App\Http\Controllers\Admin;

use App\Enums\grade;
use App\Enums\statussparepart;
use App\Http\Controllers\Controller;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SparepartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $spareparts = Sparepart::with('modelMobil')->get();
        return response()->json($spareparts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validator = Validator::make($request->all(), [
             'model_mobil_id' => 'required|exists:model_mobils,id',
             'name' => 'required|string|max:150',
             'brand' => 'required|string|max:100',
             'type' => 'nullable|string|max:100',
             'grade' => ['required', Rule::enum(grade::class)],
             'stock' => 'required|integer|min:0',
             'price' => 'required|numeric|min:0',
             'description' => 'nullable|string',
             'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
             'status' => ['required',Rule::enum(statussparepart::class)],
         ]);

         if ($validator->fails()) {
             return response()->json(['errors' => $validator->errors()], 422);
         }

         $validatedData = $validator->validated();
         
         if ($request->hasFile('image')) {
             $imagePath = $request->file('image')->store('images/spareparts', 'public');
             $validatedData['image'] = $imagePath;
         } else {
             unset($validatedData['image']);
         }

         // Cek apakah 'image' ada. Jika tidak ada file diupload, biarkan kosong (null)
         // Jika Anda ingin memastikan image null saat tidak diupload, Anda bisa menambahkan ini:
         if (!isset($validatedData['image'])) {
            $validatedData['image'] = null;
         }
         
         // Simpan data ke database
         $sparepart = Sparepart::create($validatedData);
         return response()->json($sparepart, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $sparepart = Sparepart::with('modelMobil')->findOrFail($id);
        return response()->json($sparepart);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $sparepart = Sparepart::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'model_mobil_id' => 'sometimes|exists:model_mobils,id',
            'name' => 'sometimes|string|max:255',
            'brand' => 'sometimes|string|max:255',
            'type' => 'nullable|string|max:255',
            'grade' => ['sometimes', Rule::enum(grade::class)],
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048', 
            'stock' => 'sometimes|integer|min:0',
            'price' => 'sometimes|numeric|min:0',
            'description' => 'nullable|string',
            'status' => ['sometimes', Rule::enum(statussparepart::class)],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();
        // Logika Update Gambar
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($sparepart->image && Storage::disk('public')->exists($sparepart->image)) {
                Storage::disk('public')->delete($sparepart->image);
            }
            
            // Simpan gambar baru
            $imagePath = $request->file('image')->store('images/spareparts', 'public');
            $validatedData['image'] = $imagePath;
            
        } else if (isset($validatedData['image']) && $validatedData['image'] === null) {
            // Ini untuk kasus user secara eksplisit menghapus gambar yang sudah ada
            if ($sparepart->image && Storage::disk('public')->exists($sparepart->image)) {
                 Storage::disk('public')->delete($sparepart->image);
            }
            $validatedData['image'] = null; // Set di DB menjadi null
            
        } else {
            // JANGAN SENTUH KOLOM 'image' jika tidak ada file baru dan tidak ada perintah hapus.
            unset($validatedData['image']);
        }

        // Proses File Upload untuk Update (Jika Ada)
        if ($request->hasFile('image')) {
            // 1. Hapus gambar lama (jika ada)
            if ($sparepart->image) {
                if (Storage::disk('public')->exists($sparepart->image)) {
                    Storage::disk('public')->delete($sparepart->image);
                }
            }
            
            // 2. Simpan gambar baru
            $imagePath = $request->file('image')->store('images/spareparts', 'public');
            
            // 3. Update path gambar di data
            $validatedData['image'] = $imagePath;
        } else {
            // Penting: Jika tidak ada file baru diupload, 
            // kita hapus key 'image' agar tidak menimpa nilai lama di DB
            unset($validatedData['image']);
        }

        $sparepart->update($validatedData); 
        return response()->json($sparepart);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $sparepart = Sparepart::findOrFail($id);
        
        // Hapus file gambar terkait dari storage
        if ($sparepart->image) {
             if (Storage::disk('public')->exists($sparepart->image)) {
                Storage::disk('public')->delete($sparepart->image);
            }
        }
        
        $sparepart->delete();

        return response()->json(['message' => 'Sparepart berhasil dihapus']);
    }
}