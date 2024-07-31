<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Klub;
use Validator;
use Storage;
use Illuminate\Http\Request;

class KlubController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $klub = Klub::latest()->get();
        $res = [
            'success' => true,
            'message' => 'Daftar Klub Sepak Bola',
            'data' => $klub,
        ];
        return response()->json($res, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nama_klub' => 'required',
            'logo' => 'required|image|max:2048',
            'id_liga' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'succes' => false,
                'message' => 'data tidak valid',
                'errors' => $validate->errors(),
            ], 422);
        }

        try {
            $path = $request->file('logo')->store('public/logo');
            $klub = new klub;
            $klub->nama_klub = $request->nama_klub;
            $klub->logo = $path;
            $klub->id_liga = $request->id_liga;
            $klub->save();
            return response()->json([
                'succes' => true,
                'message' => 'data klub berhasil dibuat',
                'data' => $klub,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'succes' => false,
                'message' => 'terjadi kesalahan',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $klub = Klub::find($id);
            return response()->json([
                'succes' => true,
                'message' => 'Detail klub',
                'data' => $klub,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'succes' => false,
                'message' => 'terjadi kesalahan',
                'errors' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [  
            'nama_klub' => 'required',
            'logo' => 'required|image|max:2048',
            'id_liga' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'succes' => false,
                'message' => 'data tidak valid',
                'errors' => $validate->errors(),
            ], 422);
        }

        try {
            $klub = Klub::find($id);
            if ($request->hasFile('logo')) {
                storage::delete($klub->logo);
                $path = $request->file('logo')->store('public/logo');
                $klub->logo = $path;
            }
            $klub->nama_klub = $request->nama_klub;
            $klub->logo = $path;
            $klub->id_liga = $request->id_liga;
            $klub->save();
            return response()->json([
                'succes' => true,
                'message' => 'data klub berhasil diperbarui',
                'data' => $klub,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'succes' => false,
                'message' => 'terjadi kesalahan',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $klub = Klub::findOrFail($id);
            storage::delete($klub->logo);
            $klub->delete();
            return response()->json([
                'succes' => true,
                'message' => 'Data ' . $klub->nama_klub . ' Berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'succes' => false,
                'message' => 'terjadi kesalahan',
                'errors' => $e->getMessage(),
            ], 404);
        }
    }
}
