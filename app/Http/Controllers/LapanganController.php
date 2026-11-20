<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;

class LapanganController extends Controller
{
    public function index()
    {
        $lapangans = Lapangan::where('aktif', true)->get();
        return view('lapangan.index', compact('lapangans'));
    }

    public function jadwal(Lapangan $lapangan)
    {
        if (! $lapangan->aktif) {
            abort(404);
        }

        return view('lapangan.jadwal', compact('lapangan'));
    }
}