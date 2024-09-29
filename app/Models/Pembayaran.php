<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';
    protected $fillable = [
        'rumah_id', 
        'penghuni_id', 
        'jenis_pembayaran', 
        'jumlah', 
        'tanggal_pembayaran', 
        'periode_awal', 
        'periode_akhir', 
        'status'
    ];

    protected $casts = [
        'tanggal_pembayaran' => 'date',
        'periode_awal' => 'date',
        'periode_akhir' => 'date',
    ];

    public function rumah()
    {
        return $this->belongsTo(Rumah::class);
    }

    public function penghuni()
    {
        return $this->belongsTo(Penghuni::class);
    }

    public function scopeBelumLunas($query)
    {
        return $query->where('status', 'belum_lunas');
    }

    public function scopeInPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal_pembayaran', [$startDate, $endDate]);
    }
}
