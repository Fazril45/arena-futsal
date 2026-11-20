<?php

namespace App\Models;

use App\Enums\StatusBooking;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'booking';

    protected $fillable = [
        'kode_booking',
        'user_id',
        'nama_pemesan',
        'no_hp_pemesan',
        'lapangan_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'durasi',
        'total_harga',
        'status',
        'batas_bayar',
        'bukti_bayar',
        'catatan_admin',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'batas_bayar' => 'datetime',
            'status' => StatusBooking::class,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }

    public function scopeBentrok($query, int $lapanganId, string $tanggal, string $mulai, string $selesai)
    {
        return $query->where('lapangan_id', $lapanganId)
            ->whereDate('tanggal', $tanggal)
            ->whereIn('status', StatusBooking::aktif())
            ->where('jam_mulai', '<', $selesai)
            ->where('jam_selesai', '>', $mulai);
    }
}