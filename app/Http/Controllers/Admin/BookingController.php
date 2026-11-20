<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusBooking;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['lapangan', 'user'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('lapangan_id')) {
            $query->where('lapangan_id', $request->lapangan_id);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('kode_booking', 'like', "%{$q}%")
                    ->orWhere('nama_pemesan', 'like', "%{$q}%");
            });
        }

        $bookings = $query->paginate(10)->withQueryString();
        $lapangans = Lapangan::all();

        return view('admin.booking.index', compact('bookings', 'lapangans'));
    }

    public function verifikasi(Request $request, Booking $booking)
    {
        if ($booking->status !== StatusBooking::MenungguVerifikasi) {
            return back()->with('galat', 'Status booking tidak valid untuk diverifikasi.');
        }

        $request->validate([
            'aksi'          => ['required', 'in:setujui,tolak'],
            'catatan_admin' => ['required_if:aksi,tolak', 'nullable', 'string', 'max:500'],
        ], [
            'catatan_admin.required_if' => 'Alasan penolakan wajib diisi jika bukti ditolak.',
        ]);

        if ($request->aksi === 'setujui') {
            $booking->update([
                'status'        => StatusBooking::Dikonfirmasi,
                'catatan_admin' => null,
            ]);
            return back()->with('sukses', "Booking {$booking->kode_booking} berhasil dikonfirmasi.");
        }

        $booking->update([
            'status'        => StatusBooking::MenungguPembayaran,
            'catatan_admin' => $request->catatan_admin,
        ]);

        return back()->with('sukses', "Bukti bayar {$booking->kode_booking} ditolak.");
    }

    public function batal(Request $request, Booking $booking)
    {
        if (! in_array($booking->status, StatusBooking::aktif())) {
            return back()->with('galat', 'Booking ini sudah tidak aktif.');
        }

        $request->validate([
            'catatan_admin' => ['required', 'string', 'max:500'],
        ], [
            'catatan_admin.required' => 'Alasan pembatalan wajib diisi.',
        ]);

        $booking->update([
            'status'        => StatusBooking::Dibatalkan,
            'catatan_admin' => 'Dibatalkan Admin: ' . $request->catatan_admin,
        ]);

        return back()->with('sukses', "Booking {$booking->kode_booking} dibatalkan.");
    }
}