<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModelMobil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


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
         $maxYear = date('Y') ;
         $validator = Validator::make($request->all(), [
            
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:150',
            // Aturan 'year' yang disempurnakan
            'year' => "nullable|integer|digits:4|min:1886|max:$maxYear",
            'description' => 'nullable|string',
            'image' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $model = ModelMobil::create($validator->validated());
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
            'image' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $model->update($validator->validated());
        return response()->json($model);
    }

}
