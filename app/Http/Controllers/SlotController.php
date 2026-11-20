<?php

namespace App\Http\Controllers;

use App\Enums\StatusBooking;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Services\HargaService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SlotController extends Controller
{
    public function index(Request $request, Lapangan $lapangan, HargaService $hargaService)
    {
        $request->validate([
            'tanggal' => ['required', 'date', 'after_or_equal:today', 'before_or_equal:' . now()->addDays(30)->format('Y-m-d')],
        ]);

        $tanggal = $request->input('tanggal');
        $bookings = Booking::where('lapangan_id', $lapangan->id)
            ->whereDate('tanggal', $tanggal)
            ->whereIn('status', StatusBooking::aktif())
            ->get();

        $slots = [];
        for ($jam = 8; $jam <= 22; $jam++) {
            $jamMulaiStr = sprintf('%02d:00', $jam);
            $jamSelesaiStr = sprintf('%02d:00', $jam + 1);
            $waktuSlot = Carbon::parse($tanggal . ' ' . $jamMulaiStr);

            $status = 'tersedia';

            if ($waktuSlot->isToday() && $waktuSlot->lt(now()->addHour())) {
                $status = 'lewat';
            } else {
                foreach ($bookings as $b) {
                    $bMulai = substr($b->jam_mulai, 0, 5);
                    $bSelesai = substr($b->jam_selesai, 0, 5);
                    if ($jamMulaiStr < $bSelesai && $jamSelesaiStr > $bMulai) {
                        $status = 'terisi';
                        break;
                    }
                }
            }

            $slots[] = [
                'jam'    => $jamMulaiStr,
                'label'  => "{$jamMulaiStr} - {$jamSelesaiStr}",
                'status' => $status,
                'harga'  => $hargaService->hargaPerSlot($lapangan, $jamMulaiStr),
                'tipe'   => $jam >= 17 ? 'Prime' : 'Reguler',
            ];
        }

        return response()->json([
            'tanggal' => $tanggal,
            'slot'    => $slots,
        ]);
    }
}