<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pemain;
use Validator;
use Storage;
use Illuminate\Http\Request;

class PemainController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pemain = Pemain::latest()->get();
        $res = [
            'success' => true,
            'message' => 'Daftar Pemain Sepak Bola',
            'data' => $pemain,
        ];
        return response()->json($res, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nama_pemain' => 'required',
            'posisi' => 'required',
            'foto' => 'required|image|max:2048',
            'tgl_lahir' => 'required',
            'negara' => 'required',
            'harga_pasar' => 'required',
            'id_klub' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'succes' => false,
                'message' => 'data tidak valid',
                'errors' => $validate->errors(),
            ], 422);
        }

        try {
            $path = $request->file('foto')->store('public/foto');
            $pemain = new pemain;
            $pemain->nama_pemain = $request->nama_pemain;
            $pemain->posisi = $request->posisi;
            $pemain->foto = $path;
            $pemain->tgl_lahir = $request->tgl_lahir;
            $pemain->negara = $request->negara;
            $pemain->harga_pasar = $request->harga_pasar;
            $pemain->id_klub = $request->id_klub;
            $pemain->save();
            return response()->json([
                'succes' => true,
                'message' => 'data pemain berhasil dibuat',
                'data' => $pemain,
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
            $pemain = pemain::find($id);
            return response()->json([
                'succes' => true,
                'message' => 'Detail pemain',
                'data' => $pemain,
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
            'nama_pemain' => 'required',
            'posisi' => 'required',
            'foto' => 'nullable|image|max:2048',
            'tgl_lahir' => 'required',
            'negara' => 'required',
            'harga_pasar' => 'required|numeric',
            'id_klub' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'succes' => false,
                'message' => 'data tidak valid',
                'errors' => $validate->errors(),
            ], 422);
        }

        try {   
            $pemain = pemain::find($id);
            if ($request->hasFile('foto')) {
                storage::delete($pemain->foto);
                $path = $request->file('foto')->store('public/foto');
                $pemain->foto = $path;
            }
            $pemain->nama_pemain = $request->nama_pemain;
            $pemain->posisi = $request->posisi;
            $pemain->foto = $path;
            $pemain->tgl_lahir = $request->tgl_lahir;
            $pemain->negara = $request->negara;
            $pemain->harga_pasar = $request->harga_pasar;
            $pemain->id_klub = $request->id_klub;
            $pemain->save();
            return response()->json([
                'succes' => true,
                'message' => 'data pemain berhasil diperbarui',
                'data' => $pemain,
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
            $pemain = pemain::findOrFail($id);
            storage::delete($pemain->foto);
            $pemain->delete();
            return response()->json([
                'succes' => true,
                'message' => 'Data ' . $pemain->nama_pemain . ' Berhasil dihapus',
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
