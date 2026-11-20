@extends('layouts.app')
@section('judul', 'Form Booking - ArenaFutsal')

@section('konten')
    @php
        $ringkasanLapangan = $lapangans->firstWhere('id', old('lapangan_id', $selectedLapangan)) ?? $lapangans->first();
        $tanggalRingkasan = old('tanggal', $selectedTanggal);
        $jamRingkasan = old('jam_mulai', $selectedJam);
        $durasiRingkasan = old('durasi', 1);
    @endphp
    <div class="mb-8">
        <p class="text-xs font-bold uppercase tracking-[.18em] text-emerald-300">Reservasi lapangan</p>
        <h1 class="mt-2 font-display text-3xl font-bold text-white sm:text-4xl">Atur pertandinganmu</h1>
        <p class="mt-2 text-sm text-slate-400">Tentukan lapangan, tanggal, dan durasi bermain.</p>
    </div>
    <form action="{{ route('booking.store') }}" method="POST" class="grid items-start gap-6 lg:grid-cols-3">
        @csrf
        <div class="space-y-5 lg:col-span-2">
            <section class="rounded-2xl border border-white/10 bg-surface/80 p-5 shadow-card sm:p-7">
                <div class="mb-6 flex items-start gap-4">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-500/10 font-display text-lg font-bold text-emerald-300">01</span>
                    <div><h2 class="font-display text-xl font-bold text-white">Pilih lapangan</h2><p class="mt-1 text-sm text-slate-400">Pilih permukaan yang paling nyaman untuk tim kamu.</p></div>
                </div>
                <label for="lapangan_id" class="mb-2 block text-sm font-semibold text-slate-200">Lapangan</label>
                <select name="lapangan_id" id="lapangan_id" required class="h-12 w-full rounded-xl border border-white/10 bg-obsidian/70 px-4 text-sm text-white outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30">
                    @foreach($lapangans as $lap)
                        <option value="{{ $lap->id }}"
                                data-reguler="{{ $lap->harga_reguler }}"
                                data-prime="{{ $lap->harga_prime }}"
                                {{ old('lapangan_id', $selectedLapangan) == $lap->id ? 'selected' : '' }}>
                            {{ $lap->nama }} (Reg: Rp{{ number_format($lap->harga_reguler,0,',','.') }} | Prime: Rp{{ number_format($lap->harga_prime,0,',','.') }})
                        </option>
                    @endforeach
                </select>
                @error('lapangan_id')<p class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</p>@enderror
            </section>

            <section class="rounded-2xl border border-white/10 bg-surface/80 p-5 shadow-card sm:p-7">
                <div class="mb-6 flex items-start gap-4">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-500/10 font-display text-lg font-bold text-emerald-300">02</span>
                    <div><h2 class="font-display text-xl font-bold text-white">Atur jadwal</h2><p class="mt-1 text-sm text-slate-400">Pilih tanggal dan jam mulai pertandingan.</p></div>
                </div>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="tanggal" class="mb-2 block text-sm font-semibold text-slate-200">Tanggal Main</label>
                        <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $selectedTanggal) }}" min="{{ now()->format('Y-m-d') }}" max="{{ now()->addDays(30)->format('Y-m-d') }}" required class="h-12 w-full rounded-xl border border-white/10 bg-obsidian/70 px-4 text-sm font-semibold text-white outline-none [color-scheme:dark] focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30">
                        @error('tanggal')<p class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="jam_mulai" class="mb-2 block text-sm font-semibold text-slate-200">Jam Mulai</label>
                        <select name="jam_mulai" id="jam_mulai" required class="h-12 w-full rounded-xl border border-white/10 bg-obsidian/70 px-4 text-sm text-white outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30">
                            @for($j = 8; $j <= 22; $j++)
                                @php $jamStr = sprintf('%02d:00', $j); @endphp
                                <option value="{{ $jamStr }}" {{ old('jam_mulai', $selectedJam) == $jamStr ? 'selected' : '' }}>
                                    {{ $jamStr }}
                                </option>
                            @endfor
                        </select>
                        @error('jam_mulai')<p class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</p>@enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="durasi" class="mb-2 block text-sm font-semibold text-slate-200">Durasi (Jam)</label>
                        <select name="durasi" id="durasi" required class="h-12 w-full rounded-xl border border-white/10 bg-obsidian/70 px-4 text-sm text-white outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30 sm:max-w-xs">
                            @foreach([1, 2, 3] as $d)
                                <option value="{{ $d }}" {{ old('durasi', 1) == $d ? 'selected' : '' }}>{{ $d }} Jam</option>
                            @endforeach
                        </select>
                        @error('durasi')<p class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</p>@enderror
                    </div>
                </div>
            </section>
        </div>

        <aside class="lg:sticky lg:top-24">
            <div class="rounded-2xl border border-emerald-400/20 bg-surface/90 p-5 shadow-glow backdrop-blur-xl sm:p-6">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-300"><i data-lucide="receipt-text" class="h-5 w-5"></i></span>
                <p class="mt-5 text-xs font-bold uppercase tracking-[.18em] text-emerald-300">Ringkasan tagihan</p>
                <h2 class="mt-1 font-display text-2xl font-bold text-white">Booking kamu</h2>
                <dl class="mt-6 space-y-4 border-y border-white/10 py-5 text-sm">
                    <div class="flex items-start justify-between gap-4"><dt class="text-slate-500">Lapangan</dt><dd class="text-right font-semibold text-slate-100">{{ $ringkasanLapangan?->nama ?? 'Pilih lapangan' }}</dd></div>
                    <div class="flex items-start justify-between gap-4"><dt class="text-slate-500">Tanggal</dt><dd class="text-right font-semibold text-slate-100">{{ $tanggalRingkasan ? date('d/m/Y', strtotime($tanggalRingkasan)) : '-' }}</dd></div>
                    <div class="flex items-start justify-between gap-4"><dt class="text-slate-500">Jam mulai</dt><dd class="font-display font-semibold text-slate-100">{{ $jamRingkasan }}</dd></div>
                    <div class="flex items-start justify-between gap-4"><dt class="text-slate-500">Durasi</dt><dd class="font-semibold text-slate-100">{{ $durasiRingkasan }} Jam</dd></div>
                </dl>
                <p class="mt-5 text-xs font-semibold uppercase tracking-wider text-slate-500">Estimasi total</p>
                <p id="estimasi-harga" class="mt-1 font-display text-3xl font-bold tracking-tight text-emerald-300 drop-shadow-[0_0_16px_rgba(16,185,129,0.3)]">Rp0</p>
                <p class="mt-2 text-xs leading-5 text-slate-500">Total menyesuaikan tarif reguler dan prime time.</p>
                <button type="submit" class="mt-6 flex min-h-12 w-full items-center justify-center gap-2 rounded-xl bg-emerald-500 px-5 font-bold text-obsidian hover:bg-emerald-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-300 active:scale-[.99]">Konfirmasi Booking <i data-lucide="arrow-right" class="h-4 w-4"></i></button>
                <div class="mt-4 flex items-center justify-center gap-2 text-[11px] text-slate-500"><i data-lucide="shield-check" class="h-3.5 w-3.5 text-emerald-400"></i>Jadwal akan dikonfirmasi setelah pembayaran.</div>
            </div>
        </aside>
    </form>

    <script>
        const selLap = document.getElementById('lapangan_id');
        const selJam = document.getElementById('jam_mulai');
        const selDurasi = document.getElementById('durasi');
        const outHarga = document.getElementById('estimasi-harga');

        function hitungEstimasi() {
            const opt = selLap.options[selLap.selectedIndex];
            const reg = parseInt(opt.dataset.reguler || 0);
            const prime = parseInt(opt.dataset.prime || 0);
            const jamAwal = parseInt(selJam.value.split(':')[0]);
            const durasi = parseInt(selDurasi.value);

            let total = 0;
            for (let i = 0; i < durasi; i++) {
                total += (jamAwal + i >= 17) ? prime : reg;
            }
            outHarga.textContent = 'Rp' + total.toLocaleString('id-ID');
        }

        selLap.addEventListener('change', hitungEstimasi);
        selJam.addEventListener('change', hitungEstimasi);
        selDurasi.addEventListener('change', hitungEstimasi);
        hitungEstimasi();
    </script>
@endsection
