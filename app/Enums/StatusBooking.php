<?php

namespace App\Enums;

enum StatusBooking: string
{
    case MenungguPembayaran = 'menunggu_pembayaran';
    case MenungguVerifikasi = 'menunggu_verifikasi';
    case Dikonfirmasi = 'dikonfirmasi';
    case Selesai = 'selesai';
    case Dibatalkan = 'dibatalkan';
    case Kedaluwarsa = 'kedaluwarsa';

    public static function aktif(): array
    {
        return [
            self::MenungguPembayaran->value,
            self::MenungguVerifikasi->value,
            self::Dikonfirmasi->value,
        ];
    }

    public function label(): string
    {
        return match ($this) {
            self::MenungguPembayaran => 'Menunggu Pembayaran',
            self::MenungguVerifikasi => 'Menunggu Verifikasi',
            self::Dikonfirmasi => 'Dikonfirmasi',
            self::Selesai => 'Selesai',
            self::Dibatalkan => 'Dibatalkan',
            self::Kedaluwarsa => 'Kedaluwarsa',
        };
    }
}