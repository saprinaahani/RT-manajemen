<?php

namespace App\Http\Controllers;

use App\Models\Penghuni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PenghuniController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Penghuni::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'status_penghuni' => 'required|in:kontrak,tetap',
            'nomor_telepon' => 'required|string|max:20',
            'status_pernikahan' => 'required|boolean',
            'foto_ktp' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $fotoKtpPath = $request->file('foto_ktp')->store('ktp', 'public');

        $penghuni = Penghuni::create([
            'nama_lengkap' => $request->nama_lengkap,
            'status_penghuni' => $request->status_penghuni,
            'nomor_telepon' => $request->nomor_telepon,
            'status_pernikahan' => $request->status_pernikahan,
            'foto_ktp' => $fotoKtpPath,
        ]);

        return response()->json($penghuni, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Penghuni $penghuni)
    {
        return $penghuni->load('rumah', 'pembayaran');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, penghuni $penghuni)
    {
        $request->validate([
            'nama_lengkap' => 'string|max:255',
            'status_penghuni' => 'in:kontrak,tetap',
            'nomor_telepon' => 'string|max:20',
            'status_pernikahan' => 'boolean',
            'foto_ktp' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto_ktp')) {
            Storage::disk('public')->delete($penghuni->foto_ktp);
            $fotoKtpPath = $request->file('foto_ktp')->store('ktp', 'public');
            $penghuni->foto_ktp = $fotoKtpPath;
        }

        $penghuni->update($request->except('foto_ktp'));

        return response()->json($penghuni, 200);
    }

}
