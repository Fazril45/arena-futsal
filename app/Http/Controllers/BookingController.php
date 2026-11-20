<?php

namespace App\Http\Controllers;

use App\Enums\StatusBooking;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Services\HargaService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with('lapangan')
            ->where('user_id', Auth::id())
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->paginate(10)->withQueryString();

        return view('booking.index', compact('bookings'));
    }

    public function create(Request $request)
    {
        $lapangans = Lapangan::where('aktif', true)->get();
        $selectedLapangan = $request->input('lapangan_id');
        $selectedTanggal = $request->input('tanggal', now()->format('Y-m-d'));
        $selectedJam = $request->input('jam_mulai', '08:00');

        return view('booking.create', compact('lapangans', 'selectedLapangan', 'selectedTanggal', 'selectedJam'));
    }

    public function store(StoreBookingRequest $request, HargaService $hargaService)
    {
        $lapangan = Lapangan::where('aktif', true)->findOrFail($request->lapangan_id);
        $mulai = Carbon::createFromFormat('H:i', $request->jam_mulai);
        $selesai = $mulai->copy()->addHours((int) $request->durasi);
        $totalHarga = $hargaService->hitung($lapangan, $mulai->format('H:i'), (int) $request->durasi);

        try {
            $booking = DB::transaction(function () use ($request, $lapangan, $mulai, $selesai, $totalHarga) {
                $bentrok = Booking::bentrok(
                    $lapangan->id,
                    $request->tanggal,
                    $mulai->format('H:i'),
                    $selesai->format('H:i')
                )->lockForUpdate()->exists();

                if ($bentrok) {
                    throw new \RuntimeException("Jam {$mulai->format('H:i')}–{$selesai->format('H:i')} sudah dipesan pengguna lain.");
                }

                $urutan = Booking::whereDate('created_at', today())->lockForUpdate()->count() + 1;
                $kode = 'BK-' . date('Ymd') . '-' . str_pad($urutan, 4, '0', STR_PAD_LEFT);

                return Booking::create([
                    'kode_booking'  => $kode,
                    'user_id'       => Auth::id(),
                    'nama_pemesan'  => Auth::user()->name,
                    'no_hp_pemesan' => Auth::user()->no_hp,
                    'lapangan_id'   => $lapangan->id,
                    'tanggal'       => $request->tanggal,
                    'jam_mulai'     => $mulai->format('H:i'),
                    'jam_selesai'   => $selesai->format('H:i'),
                    'durasi'        => $request->durasi,
                    'total_harga'   => $totalHarga,
                    'status'        => StatusBooking::MenungguPembayaran,
                    'batas_bayar'   => now()->addHour(),
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->withInput()->withErrors(['jam_mulai' => $e->getMessage()]);
        }

        return redirect()->route('booking.show', $booking->kode_booking)
            ->with('sukses', 'Booking berhasil dibuat! Segera selesaikan pembayaran sebelum batas waktu.');
    }

    public function show(Booking $booking)
    {
        Gate::authorize('view', $booking);
        $booking->load('lapangan');

        return view('booking.show', compact('booking'));
    }

    public function bayar(Request $request, Booking $booking)
    {
        Gate::authorize('bayar', $booking);

        $request->validate([
            'bukti_bayar' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'bukti_bayar.required' => 'File bukti transfer wajib diunggah.',
            'bukti_bayar.image'    => 'File harus berupa gambar.',
            'bukti_bayar.mimes'    => 'Format gambar harus JPG atau PNG.',
            'bukti_bayar.max'      => 'Ukuran gambar maksimal 2 MB.',
        ]);

        if ($booking->bukti_bayar) {
            Storage::disk('public')->delete($booking->bukti_bayar);
        }

        $path = $request->file('bukti_bayar')->store('bukti_bayar', 'public');

        $booking->update([
            'bukti_bayar'   => $path,
            'status'        => StatusBooking::MenungguVerifikasi,
            'catatan_admin' => null,
        ]);

        return back()->with('sukses', 'Bukti pembayaran berhasil dikirim. Menunggu verifikasi admin.');
    }

    public function batal(Booking $booking)
    {
        if (! Gate::allows('batal', $booking)) {
            return back()->with('galat', 'Booking tidak dapat dibatalkan (minimal 24 jam sebelum jadwal main).');
        }

        $booking->update([
            'status' => StatusBooking::Dibatalkan,
        ]);

        return back()->with('sukses', 'Booking berhasil dibatalkan. Slot jadwal telah dibuka kembali.');
    }
}