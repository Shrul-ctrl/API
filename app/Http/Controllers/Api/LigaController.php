<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Liga;
use Validator;
use Illuminate\Http\Request;

class LigaController extends Controller
{
    public function index()
    {
        $liga = Liga::latest()->get();
        $res = [
            'success' => true,
            'message' => 'Daftar Liga Sepak Bola', 
            'data' => $liga,
        ];
        return response()->json($res,200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nama_liga' => 'required|unique:ligas',
            'negara' => 'required',
        ]);

        if($validate->fails()){
            return response()->json([
                'succes' => false,
                'message' => 'validasi gagal',
                'errors' => $validate->errors(),
            ], 422);
        }
        
        try{
            $liga = new Liga;
            $liga->nama_liga = $request->nama_liga;
            $liga->negara = $request->negara;
            $liga->save();
            return response()->json([
                'succes' => true,
                'message' => 'data liga berhasil dibuat',
                'data' => $liga,
            ], 201);
        }catch (\Exception $e){
            return response()->json([
                'succes' => false,
                'message' => 'terjadi kesalahan',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try{
            $liga = Liga::find($id);
            return response()-> json([
                'succes' => true,
                'message' => 'Detail liga',
                'data' => $liga,
            ],201);
        }catch (\Exception $e){
            return response()->json([
                'succes' => false,
                'message' => 'terjadi kesalahan',
                'errors' => $e->getMessage(),
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'nama_liga' => 'required',
            'negara' => 'required',
        ]);
        
        if($validate->fails()){
            return response()->json([
                'succes' => false,
                'message' => 'validasi gagal',
                'errors' => $validate->errors(),
            ], 422);
        }
        
        try{
            $liga = Liga::findOrFail($id);
            $liga->nama_liga = $request->nama_liga;
            $liga->negara = $request->negara;
            $liga->save();
            return response()->json([
                'succes' => true,
                'message' => 'data liga berhasil diperbarui',
                'data' => $liga,
            ], 201);
        }catch (\Exception $e){
            return response()->json([
                'succes' => false,
                'message' => 'terjadi kesalahan',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try{
            $liga = Liga::find($id);
            $liga->delete();
            return response()-> json([
                'succes' => true,
                'message' => 'Data '. $liga->nama_liga .' Berhasil dihapus',
            ],201);
        }catch (\Exception $e){
            return response()->json([
                'succes' => false,
                'message' => 'terjadi kesalahan',
                'errors' => $e->getMessage(),
            ], 404);
        }
    }
}
