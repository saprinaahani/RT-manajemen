<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use App\Models\Rumah;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function ringkasanBulanan(Request $request)
    {
        $tahun = $request->input('tahun', Carbon::now()->year);

        $dataBulanan = collect(range(1, 12))->map(function ($bulan) use ($tahun) {
            $tanggal = Carbon::createFromDate($tahun, $bulan, 1);

            $pemasukan = Pembayaran::whereYear('tanggal_pembayaran', $tahun)
                ->whereMonth('tanggal_pembayaran', $bulan)
                ->sum('jumlah');

            $pengeluaran = Pengeluaran::whereYear('tanggal_pengeluaran', $tahun)
                ->whereMonth('tanggal_pengeluaran', $bulan)
                ->sum('jumlah');

            $saldo = $pemasukan - $pengeluaran;

            return [
                'bulan' => $tanggal->format('F'),
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran,
                'saldo' => $saldo,
            ];
        });

        return response()->json([
            'tahun' => $tahun,
            'data' => $dataBulanan,
        ]);
    }

    public function detailPembayaran(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer',
            'bulan' => 'required|integer|between:1,12',
        ]);

        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');

        $pembayaran = Pembayaran::with(['rumah', 'penghuni'])
            ->whereYear('tanggal_pembayaran', $tahun)
            ->whereMonth('tanggal_pembayaran', $bulan)
            ->get();

        return response()->json($pembayaran);
    }

    public function detailPengeluaran(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer',
            'bulan' => 'required|integer|between:1,12',
        ]);

        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');

        $pengeluaran = Pengeluaran::whereYear('tanggal_pengeluaran', $tahun)
            ->whereMonth('tanggal_pengeluaran', $bulan)
            ->get();

        return response()->json($pengeluaran);
    }

    public function tunggakanPembayaran()
    {
        $tunggakan = Pembayaran::with(['rumah', 'penghuni'])
            ->where('status', 'belum_lunas')
            ->orderBy('tanggal_pembayaran')
            ->get();

        return response()->json($tunggakan);
    }

    public function occupancyRate(Request $request)
    {
        $tahun = $request->input('tahun', Carbon::now()->year);
        $bulan = $request->input('bulan', Carbon::now()->month);

        $totalRumah = Rumah::count();
        $rumahDihuni = Rumah::where('status', 'dihuni')->count();

        $occupancyRate = ($rumahDihuni / $totalRumah) * 100;

        return response()->json([
            'tahun' => $tahun,
            'bulan' => Carbon::createFromDate($tahun, $bulan, 1)->format('F'),
            'total_rumah' => $totalRumah,
            'rumah_dihuni' => $rumahDihuni,
            'occupancy_rate' => round($occupancyRate, 2),
        ]);
    }

    public function grafikTahunan(Request $request)
    {
        $tahun = $request->input('tahun', Carbon::now()->year);

        $dataBulanan = collect(range(1, 12))->map(function ($bulan) use ($tahun) {
            $tanggalAwal = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
            $tanggalAkhir = $tanggalAwal->copy()->endOfMonth();

            $pemasukan = Pembayaran::inPeriod($tanggalAwal, $tanggalAkhir)->sum('jumlah');
            $pengeluaran = Pengeluaran::inPeriod($tanggalAwal, $tanggalAkhir)->sum('jumlah');
            $saldo = $pemasukan - $pengeluaran;

            return [
                'bulan' => $tanggalAwal->format('F'),
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran,
                'saldo' => $saldo,
            ];
        });

        return response()->json([
            'tahun' => $tahun,
            'data' => $dataBulanan,
        ]);
    }

    public function detailBulanan(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer',
            'bulan' => 'required|integer|between:1,12',
        ]);

        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');
        $tanggalAwal = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $tanggalAkhir = $tanggalAwal->copy()->endOfMonth();

        $pemasukan = Pembayaran::with(['rumah', 'penghuni'])
            ->inPeriod($tanggalAwal, $tanggalAkhir)
            ->get();

        $pengeluaran = Pengeluaran::inPeriod($tanggalAwal, $tanggalAkhir)->get();

        return response()->json([
            'tahun' => $tahun,
            'bulan' => $tanggalAwal->format('F'),
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
        ]);
    }
}
