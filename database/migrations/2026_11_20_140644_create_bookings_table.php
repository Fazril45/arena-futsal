<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking', 30)->unique();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->string('nama_pemesan', 100);
            $table->string('no_hp_pemesan', 20);
            $table->foreignId('lapangan_id')->constrained('lapangan')->restrictOnDelete();
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->unsignedTinyInteger('durasi');
            $table->unsignedInteger('total_harga');
            $table->enum('status', [
                'menunggu_pembayaran',
                'menunggu_verifikasi',
                'dikonfirmasi',
                'selesai',
                'dibatalkan',
                'kedaluwarsa'
            ])->default('menunggu_pembayaran');
            $table->dateTime('batas_bayar')->nullable();
            $table->string('bukti_bayar')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->timestamps();

            $table->index(['lapangan_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};