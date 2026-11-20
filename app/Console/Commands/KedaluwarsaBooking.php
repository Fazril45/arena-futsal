<?php

namespace App\Console\Commands;

use App\Enums\StatusBooking;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Console\Command;

class KedaluwarsaBooking extends Command
{
    protected $signature = 'booking:kedaluwarsa';
    protected $description = 'Membatalkan booking yang melewati batas bayar dan menyelesaikan booking yang sudah lewat waktu main';

    public function handle(): void
    {
        $kedaluwarsa = Booking::where('status', StatusBooking::MenungguPembayaran)
            ->where('batas_bayar', '<', now())
            ->update(['status' => StatusBooking::Kedaluwarsa]);

        $selesaiCount = 0;
        $aktifBookings = Booking::where('status', StatusBooking::Dikonfirmasi)
            ->whereDate('tanggal', '<=', today())
            ->get();

        foreach ($aktifBookings as $booking) {
            $waktuSelesai = Carbon::parse($booking->tanggal->format('Y-m-d') . ' ' . $booking->jam_selesai);
            if ($waktuSelesai->lte(now())) {
                $booking->update(['status' => StatusBooking::Selesai]);
                $selesaiCount++;
            }
        }

        $this->info("Diperbarui: {$kedaluwarsa} kedaluwarsa, {$selesaiCount} selesai.");
    }
}