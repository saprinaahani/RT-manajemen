<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Pembayaran::with('rumah', 'penghuni')->get();
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
            'rumah_id' => 'required|exists:rumah,id',
            'penghuni_id' => 'required|exists:penghuni,id',
            'jenis_pembayaran' => 'required|in:iuran_kebersihan,iuran_satpam',
            'jumlah' => 'required|numeric',
            'tanggal_pembayaran' => 'required|date',
            'periode_awal' => 'required|date',
            'periode_akhir' => 'required|date|after:periode_awal',
            'status' => 'required|in:lunas,belum_lunas',
        ]);

        return Pembayaran::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Pembayaran $pembayaran)
    {
        return $pembayaran->load('rumah', 'penghuni');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pembayaran $pembayaran)
    {
        $request->validate([
            'jenis_pembayaran' => 'in:iuran_kebersihan,iuran_satpam',
            'jumlah' => 'numeric',
            'tanggal_pembayaran' => 'date',
            'periode_awal' => 'date',
            'periode_akhir' => 'date|after:periode_awal',
            'status' => 'in:lunas,belum_lunas',
        ]);

        $pembayaran->update($request->all());
        return $pembayaran;
    }

}
