<?php

namespace App\Http\Controllers\Admin;

use App\Enums\grade;
use App\Enums\statussparepart;
use App\Http\Controllers\Controller;
use App\Models\Sparepart;
use Illuminate\Http\Request;
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
            'image' => 'nullable|string',
            'status' => ['required',Rule::enum(statussparepart::class)],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $sparepart = Sparepart::create($validator->validated());
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
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $sparepart->update($validator->validated());
        return response()->json($sparepart);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $sparepart = Sparepart::findOrFail($id);
        $sparepart->delete();

        return response()->json(['message' => 'Sparepart berhasil dihapus']);
    }
}
