<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penghuni extends Model
{
    use HasFactory;
    
    protected $table = 'penghuni';
    protected $fillable = ['nama_lengkap', 'foto_ktp', 'status_penghuni', 'nomor_telepon', 'status_pernikahan'];

    protected $casts = [
        'status_pernikahan' => 'boolean',
    ];

    public function rumah()
    {
        return $this->belongsToMany(Rumah::class, 'rumah_penghuni')
                    ->withPivot('tanggal_mulai', 'tanggal_selesai')
                    ->withTimestamps();
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }
}
