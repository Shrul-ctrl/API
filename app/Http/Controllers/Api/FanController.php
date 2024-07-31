<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fan;
use Validator;
use Storage;
use Illuminate\Http\Request;

class FanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fans = Fan::with('klub')->latest()->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar Pemain Sepak Bola',
            'data' => $fans,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nama_fans' => 'required',
            'klub' => 'required|array',

        ]);
        if ($validate->fails()) {
            return response()->json([
                'succes' => false,
                'message' => 'validasi gagal',
                'errors' => $validate->errors(),
            ], 422);
        }
        try {
            $fan = new Fan();
            $fan->nama_fans= $request->nama_fans;
            $fan->save();
            $fan->klub()->attach($request->klub);
            return response()->json([
                'succes' => true,
                'message' => 'data pemain berhasil dibuat',
                'data' => $fan,
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
    public function show(string $id)
    {
        try {
            $fans = Fan::find($id);
            return response()->json([
                'succes' => true,
                'message' => 'Detail fans',
                'data' => $fans,
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
    public function update(Request $request, string $id)
    {
        {
            $validate = Validator::make($request->all(), [
                'nama_fans' => 'required|unique:Fans',
                'klub' => 'required|array',
    
            ]);
            if ($validate->fails()) {
                return response()->json([
                    'succes' => false,
                    'message' => 'validasi gagal',
                    'errors' => $validate->errors(),
                ], 422);
            }
            try {
                $fan = Fan::findOrFail($id);
                $fan->nama_fans = $request->nama_fan;
                $fan->save();
                $fan->klub()->sync($request->klub);
                return response()->json([
                    'succes' => true,
                    'message' => 'data pemain berhasil diubah',
                    'data' => $fan,
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'succes' => false,
                    'message' => 'terjadi kesalahan',
                    'errors' => $e->getMessage(),
                ], 500);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $fan = Fan::findOrFail($id);
            $fan->klub()->detach();
            $fan->delete();

            return response()->json([
                'succes' => true,
                'message' => 'data berhasil dihapus',
                'data' => $fan,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'succes' => false,
                'message' => 'terjadi kesalahan',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
