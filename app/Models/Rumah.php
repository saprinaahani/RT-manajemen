<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rumah extends Model
{
    use HasFactory;

    protected $table = 'rumah';
    protected $fillable = ['nomor_rumah', 'status'];

    public function penghuni()
    {
        return $this->belongsToMany(Penghuni::class, 'rumah_penghuni')
                    ->withPivot('tanggal_mulai', 'tanggal_selesai')
                    ->withTimestamps();
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function penghuniSaatIni()
    {
        return $this->penghuni()->wherePivot('tanggal_selesai', null)->first();
    }
}
