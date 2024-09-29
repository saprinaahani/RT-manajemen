<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembayaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rumah_id')->constrained('rumah')->onDelete('cascade');
            $table->foreignId('penghuni_id')->constrained('penghuni')->onDelete('cascade');
            $table->enum('jenis_pembayaran', ['iuran_kebersihan', 'iuran_satpam']);
            $table->decimal('jumlah', 10, 2);
            $table->date('tanggal_pembayaran');
            $table->date('periode_awal');
            $table->date('periode_akhir');
            $table->enum('status', ['lunas', 'belum_lunas']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembayaran');
    }
};
