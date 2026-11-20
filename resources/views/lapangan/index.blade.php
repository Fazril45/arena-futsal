@extends('layouts.app')
@section('judul', 'Daftar Lapangan - ArenaFutsal')

@section('konten')
    <section class="relative overflow-hidden rounded-3xl border border-white/10 bg-surface/70 p-6 shadow-card sm:p-9 lg:p-12">
        <div class="absolute -right-20 -top-32 h-80 w-80 rounded-full bg-emerald-500/10 blur-3xl"></div>
        <div class="relative grid items-end gap-8 lg:grid-cols-[1.25fr_.75fr]">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full border border-emerald-400/20 bg-emerald-500/10 px-4 py-2 text-xs font-bold uppercase tracking-[.18em] text-emerald-300"><i data-lucide="map-pin" class="h-4 w-4"></i>Tempat bermainmu</span>
                <h1 class="mt-6 font-display text-5xl font-bold tracking-[-.06em] text-white sm:text-6xl lg:text-7xl">Arena<span class="text-emerald-400">Futsal</span></h1>
                <p class="mt-5 max-w-2xl text-base leading-7 text-slate-400 sm:text-lg">Pilih lapangan favorit, temukan waktu terbaik, dan nikmati pertandingan yang selalu dinanti.</p>
                <div class="mt-7 inline-flex min-h-11 items-center gap-3 rounded-xl border border-white/10 bg-white/5 px-4 text-sm font-semibold text-slate-200"><i data-lucide="clock-3" class="h-4 w-4 text-emerald-300"></i>Setiap hari <span class="font-display text-white">08:00–23:00</span></div>
            </div>
            @if($lapangans->isNotEmpty())
                @php $tarifRingkasan = $lapangans->first(); @endphp
                <div class="rounded-2xl border border-white/10 bg-obsidian/50 p-5 backdrop-blur-xl sm:p-6">
                    <div class="mb-5 flex items-center justify-between gap-3">
                        <div><p class="text-xs font-bold uppercase tracking-[.16em] text-slate-500">Tarif {{ $tarifRingkasan->nama }}</p><p class="mt-1 text-sm text-slate-300">Pilih jam sesuai ritme permainan</p></div>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/5 text-emerald-300"><i data-lucide="chart-no-axes-column-increasing" class="h-5 w-5"></i></span>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-xl bg-white/5 p-4"><p class="text-xs font-semibold text-slate-400">Reguler <span class="mt-1 block">08:00–16:59</span></p><p class="mt-3 font-display text-xl font-bold text-white">Rp{{ number_format($tarifRingkasan->harga_reguler, 0, ',', '.') }}<span class="text-xs font-medium text-slate-500">/jam</span></p></div>
                        <div class="rounded-xl bg-emerald-500/10 p-4"><p class="text-xs font-semibold text-emerald-200">Prime Time <span class="mt-1 block">17:00–22:59</span></p><p class="mt-3 font-display text-xl font-bold text-emerald-300">Rp{{ number_format($tarifRingkasan->harga_prime, 0, ',', '.') }}<span class="text-xs font-medium text-emerald-200/60">/jam</span></p></div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <section class="mt-12">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div><p class="text-xs font-bold uppercase tracking-[.18em] text-emerald-300">Temukan lapangan</p><h2 class="mt-2 font-display text-3xl font-bold text-white">Pilih tempat bertanding</h2></div>
            <p class="text-sm text-slate-500">Tarif per jam · Reguler 08:00–16:59 · Prime Time 17:00–22:59</p>
        </div>

        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @forelse($lapangans as $lap)
                @php
                    $kataFoto = strtolower($lap->nama . ' ' . $lap->jenis_lantai);
                    $fotoKey = str_contains($kataFoto, 'rumput') ? 'rumput' : (str_contains($kataFoto, 'semen') ? 'semen' : 'vinyl');
                    $warnaLantai = $fotoKey === 'rumput' ? 'border-emerald-400/20 bg-emerald-400/10 text-emerald-200' : ($fotoKey === 'semen' ? 'border-slate-400/20 bg-slate-400/10 text-slate-200' : 'border-cyan-400/20 bg-cyan-400/10 text-cyan-200');
                @endphp
                <article class="group overflow-hidden rounded-2xl border border-white/10 bg-surface shadow-card transition-all duration-200 hover:-translate-y-1 hover:border-emerald-400/25">
                    <a href="{{ route('lapangan.jadwal', $lap->id) }}" class="relative block aspect-video overflow-hidden bg-gradient-to-br from-emerald-950 via-surface to-obsidian">
                        <img @if($lap->foto) src="{{ asset('storage/' . $lap->foto) }}" @endif data-foto-fallback="{{ $fotoKey }}" alt="{{ $lap->nama }} - {{ $lap->jenis_lantai }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" onerror="this.onerror=null;this.style.display='none';this.parentElement.classList.add('bg-gradient-to-br','from-emerald-950','via-surface','to-obsidian')">
                        <div class="absolute inset-0 bg-gradient-to-t from-obsidian via-obsidian/40 to-transparent"></div>
                        <div class="absolute inset-x-0 bottom-0 flex items-end justify-between gap-3 p-5">
                            <div><span class="mb-2 inline-flex items-center gap-1.5 rounded-full border border-white/15 bg-obsidian/50 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-white backdrop-blur-md"><i data-lucide="map-pin" class="h-3 w-3 text-emerald-300"></i>Venue</span><h3 class="font-display text-2xl font-bold text-white">{{ $lap->nama }}</h3></div>
                            <span class="shrink-0 rounded-xl border border-white/15 bg-obsidian/65 px-3 py-2 text-right backdrop-blur-md"><span class="block text-[10px] font-semibold text-slate-300">Mulai</span><span class="font-display text-sm font-bold text-white">Rp{{ number_format(min($lap->harga_reguler, $lap->harga_prime), 0, ',', '.') }}</span></span>
                        </div>
                    </a>
                    <div class="p-5">
                        <span class="inline-flex min-h-8 items-center rounded-full border px-3 text-xs font-bold {{ $warnaLantai }}">{{ $lap->jenis_lantai }}</span>
                        @if($lap->deskripsi)
                            <p class="mt-3 line-clamp-2 min-h-10 text-sm leading-5 text-slate-400">{{ $lap->deskripsi }}</p>
                        @endif
                        <div class="mt-5 grid grid-cols-2 gap-3">
                            <div class="rounded-xl border border-white/5 bg-obsidian/50 p-3"><p class="text-xs font-semibold text-slate-500">Reguler</p><p class="mt-1 font-display text-lg font-bold text-slate-100">Rp{{ number_format($lap->harga_reguler, 0, ',', '.') }}</p><p class="text-[11px] text-slate-500">08:00–16:59 / jam</p></div>
                            <div class="rounded-xl border border-emerald-500/10 bg-emerald-500/5 p-3"><p class="text-xs font-semibold text-emerald-300/80">Prime Time</p><p class="mt-1 font-display text-lg font-bold text-emerald-300">Rp{{ number_format($lap->harga_prime, 0, ',', '.') }}</p><p class="text-[11px] text-emerald-300/50">17:00–22:59 / jam</p></div>
                        </div>
                        <a href="{{ route('lapangan.jadwal', $lap->id) }}" class="mt-5 flex min-h-12 items-center justify-center gap-2 rounded-xl bg-emerald-500 px-4 text-sm font-bold text-obsidian hover:bg-emerald-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-300 active:scale-[.99]">Cek Jadwal &amp; Booking <i data-lucide="arrow-up-right" class="h-4 w-4"></i></a>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-white/10 bg-surface/50 px-6 py-16 text-center">
                    <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-white/5 text-slate-400"><i data-lucide="tent-tree" class="h-7 w-7"></i></span>
                    <h3 class="mt-5 font-display text-xl font-bold text-white">Belum ada data lapangan</h3>
                    <p class="mt-2 text-sm text-slate-400">Silakan kembali lagi nanti untuk melihat pilihan lapangan.</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
