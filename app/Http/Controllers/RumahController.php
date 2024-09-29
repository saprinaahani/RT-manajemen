<?php

namespace App\Http\Controllers;

use App\Models\Rumah;
use App\Models\Penghuni;
use Illuminate\Http\Request;

class RumahController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Rumah::with('penghuniSaatIni')->get();
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
            'nomor_rumah' => 'required|unique:rumah',
            'status' => 'required|in:dihuni,tidak_dihuni',
        ]);

        return Rumah::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Rumah $rumah)
    {
        return $rumah->load('penghuni', 'pembayaran.penghuni');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rumah $rumah)
    {
        $request->validate([
            'status' => 'required|in:dihuni,tidak_dihuni',
        ]);

        $rumah->update($request->all());
        return $rumah;
    }

    public function tambahPenghuni(Request $request, Rumah $rumah)
    {
        $request->validate([
            'penghuni_id' => 'required|exists:penghuni,id',
            'tanggal_mulai' => 'required|date',
        ]);

        $rumah->penghuni()->attach($request->penghuni_id, [
            'tanggal_mulai' => $request->tanggal_mulai,
        ]);

        $rumah->update(['status' => 'dihuni']);

        return $rumah->load('penghuni');
    }

    public function hapusPenghuni(Request $request, Rumah $rumah, Penghuni $penghuni)
    {
        $request->validate([
            'tanggal_selesai' => 'required|date',
        ]);

        $rumah->penghuni()->updateExistingPivot($penghuni->id, [
            'tanggal_selesai' => $request->tanggal_selesai,
        ]);

        if (!$rumah->penghuniSaatIni()) {
            $rumah->update(['status' => 'tidak_dihuni']);
        }

        return $rumah->load('penghuni');
    }

    public function riwayatPembayaran(Rumah $rumah)
    {
        return $rumah->pembayaran()->with('penghuni')->get();
    }
}
