<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModelMobil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage; // Wajib: Import Storage untuk hapus/cek gambar
use Illuminate\Database\Eloquent\ModelNotFoundException; 


class ModelMobilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(ModelMobil::with('spareparts')->get());
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validator = Validator::make($request->all(), [
            
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:150',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();
         
        if ($request->hasFile('image')) {
             $imagePath = $request->file('image')->store('images/models', 'public');
             $validatedData['image'] = $imagePath;
        } else {
             // Jika tidak ada file yang di-upload, pastikan image null, bukan dihapus dari array
             $validatedData['image'] = null; 
        }

        $model = ModelMobil::create($validatedData);
        return response()->json($model, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $model = ModelMobil::with('spareparts')->findOrFail($id);
        return response()->json($model);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        
        $model = ModelMobil::findOrFail($id);
        $maxYear = date('Y') ;

        $validator = Validator::make($request->all(), [
            'brand' => 'sometimes|string|max:255',
            'model' => 'sometimes|string|max:255',
            'year' => "nullable|integer|digits:4|min:1886|max:$maxYear",
            'description' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();
        
        // Logika Update Gambar
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($model->image && Storage::disk('public')->exists($model->image)) {
                Storage::disk('public')->delete($model->image);
            }
            
            // Simpan gambar baru
            $imagePath = $request->file('image')->store('images/models', 'public');
            $validatedData['image'] = $imagePath;
            
        } else if (isset($validatedData['image']) && $validatedData['image'] === null) {
            // Ini untuk kasus user secara eksplisit menghapus gambar yang sudah ada
            if ($model->image && Storage::disk('public')->exists($model->image)) {
                 Storage::disk('public')->delete($model->image);
            }
            $validatedData['image'] = null; // Set di DB menjadi null
            
        } else {
            // JANGAN SENTUH KOLOM 'image' jika tidak ada file baru dan tidak ada perintah hapus.
            unset($validatedData['image']);
        }

        $model->update($validatedData);
        return response()->json($model);
    }

}
