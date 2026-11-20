<?php

namespace App\Services;

use App\Models\Lapangan;
use Carbon\Carbon;

class HargaService
{
    public function hitung(Lapangan $lapangan, string $jamMulai, int $durasi): int
    {
        $total = 0;
        $waktu = Carbon::createFromFormat('H:i', substr($jamMulai, 0, 5));

        for ($i = 0; $i < $durasi; $i++) {
            $jam = (int) $waktu->format('H');
            if ($jam >= 17) {
                $total += $lapangan->harga_prime;
            } else {
                $total += $lapangan->harga_reguler;
            }
            $waktu->addHour();
        }

        return $total;
    }

    public function hargaPerSlot(Lapangan $lapangan, string $jam): int
    {
        $hour = (int) substr($jam, 0, 2);
        return $hour >= 17 ? $lapangan->harga_prime : $lapangan->harga_reguler;
    }
}