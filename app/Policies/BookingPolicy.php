<?php

namespace App\Policies;

use App\Enums\StatusBooking;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;

class BookingPolicy
{
    public function view(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id || $user->role === 'admin';
    }

    public function bayar(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id
            && $booking->status === StatusBooking::MenungguPembayaran
            && $booking->batas_bayar !== null
            && $booking->batas_bayar->isFuture();
    }

    public function batal(User $user, Booking $booking): bool
    {
        if ($user->id !== $booking->user_id) {
            return false;
        }

        if (! in_array($booking->status, [
            StatusBooking::MenungguPembayaran,
            StatusBooking::MenungguVerifikasi,
            StatusBooking::Dikonfirmasi,
        ])) {
            return false;
        }

        $waktuMulai = Carbon::parse($booking->tanggal->format('Y-m-d') . ' ' . $booking->jam_mulai);
        return now()->diffInHours($waktuMulai, false) >= 24;
    }
}