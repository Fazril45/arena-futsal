@extends('layouts.app')
@section('judul', 'Jadwal ' . $lapangan->nama)

@section('konten')
    @php
        $kataFoto = strtolower($lapangan->nama . ' ' . $lapangan->jenis_lantai);
        $fotoKey = str_contains($kataFoto, 'rumput') ? 'rumput' : (str_contains($kataFoto, 'semen') ? 'semen' : 'vinyl');
        $warnaLantai = $fotoKey === 'rumput' ? 'border-emerald-400/20 bg-emerald-400/10 text-emerald-200' : ($fotoKey === 'semen' ? 'border-slate-400/20 bg-slate-400/10 text-slate-200' : 'border-cyan-400/20 bg-cyan-400/10 text-cyan-200');
    @endphp
    <section class="overflow-hidden rounded-3xl border border-white/10 bg-surface shadow-card">
        <div class="relative aspect-[2/1] min-h-64 bg-gradient-to-br from-emerald-950 via-surface to-obsidian sm:aspect-[2.7/1]">
            <img @if($lapangan->foto) src="{{ asset('storage/' . $lapangan->foto) }}" @endif data-foto-fallback="{{ $fotoKey }}" alt="{{ $lapangan->nama }} - {{ $lapangan->jenis_lantai }}" class="h-full w-full object-cover" onerror="this.onerror=null;this.style.display='none';this.parentElement.classList.add('bg-gradient-to-br','from-emerald-950','via-surface','to-obsidian')">
            <div class="absolute inset-0 bg-gradient-to-t from-obsidian via-obsidian/55 to-transparent"></div>
            <div class="absolute inset-x-0 bottom-0 flex flex-col gap-5 p-5 sm:flex-row sm:items-end sm:justify-between sm:p-8">
                <div><span class="mb-3 inline-flex rounded-full border border-white/15 bg-obsidian/50 px-3 py-1 text-[10px] font-bold uppercase tracking-[.18em] text-emerald-200 backdrop-blur-md">Jadwal lapangan</span><h1 class="font-display text-3xl font-bold text-white sm:text-4xl">{{ $lapangan->nama }}</h1><span class="mt-3 inline-flex min-h-8 items-center rounded-full border px-3 text-xs font-bold {{ $warnaLantai }}">{{ $lapangan->jenis_lantai }}</span></div>
                <div class="grid grid-cols-2 gap-2 sm:min-w-72">
                    <div class="rounded-xl border border-white/15 bg-obsidian/60 p-3 backdrop-blur-xl"><p class="text-xs font-semibold text-slate-400">Reguler · 08:00–16:59</p><p class="mt-1 font-display text-lg font-bold text-white">Rp{{ number_format($lapangan->harga_reguler, 0, ',', '.') }}<span class="text-xs text-slate-500">/jam</span></p></div>
                    <div class="rounded-xl border border-emerald-400/20 bg-emerald-950/60 p-3 backdrop-blur-xl"><p class="text-xs font-semibold text-emerald-200/80">Prime · 17:00–22:59</p><p class="mt-1 font-display text-lg font-bold text-emerald-300">Rp{{ number_format($lapangan->harga_prime, 0, ',', '.') }}<span class="text-xs text-emerald-200/60">/jam</span></p></div>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-8 rounded-2xl border border-white/10 bg-surface/70 p-5 sm:p-7">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div><p class="text-xs font-bold uppercase tracking-[.18em] text-emerald-300">Pilih tanggal</p><h2 class="mt-2 font-display text-2xl font-bold text-white">Cari waktu bermain</h2><p class="mt-1 text-sm text-slate-400">Pilih satu slot tersedia untuk melanjutkan booking.</p></div>
            <div class="w-full sm:max-w-xs">
                <label for="pilih-tanggal" class="mb-2 block text-sm font-semibold text-slate-200">Tanggal Main</label>
                <div class="relative"><i data-lucide="calendar-days" class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-emerald-300"></i><input type="date" id="pilih-tanggal" value="{{ now()->format('Y-m-d') }}" min="{{ now()->format('Y-m-d') }}" max="{{ now()->addDays(30)->format('Y-m-d') }}" class="h-12 w-full rounded-xl border border-white/10 bg-obsidian/70 pl-11 pr-4 text-sm font-semibold text-white outline-none [color-scheme:dark] focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30"></div>
            </div>
        </div>

        <div class="mt-7 flex flex-wrap gap-x-5 gap-y-3 rounded-xl border border-white/5 bg-obsidian/40 px-4 py-3 text-xs font-semibold text-slate-400">
            <span class="inline-flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-emerald-400"></span>Tersedia</span>
            <span class="inline-flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-slate-600"></span>Terisi</span>
            <span class="inline-flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-rose-400"></span>Lewat</span>
        </div>
        <div id="status-loading" style="display:none;" class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
            @for($i = 0; $i < 8; $i++)
                <div class="animate-pulse rounded-2xl border border-white/5 bg-obsidian/50 p-4"><div class="h-5 w-20 rounded bg-white/10"></div><div class="mt-3 h-3 w-24 rounded bg-white/5"></div><div class="mt-5 h-4 w-28 rounded bg-white/10"></div><div class="mt-4 h-10 rounded-xl bg-white/5"></div></div>
            @endfor
        </div>
        <div id="pesan-error" style="display:none;" class="mt-5 rounded-xl border border-rose-400/20 bg-rose-500/10 px-4 py-3 text-sm text-rose-200"></div>
        <div id="tabel-slot" class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4"></div>
    </section>

    <style>
        #status-loading[style*="display: block"] ~ #tabel-slot:empty::before { content: none; }
    </style>
    <script>
        const inputTanggal = document.getElementById('pilih-tanggal');
        const tabelSlot = document.getElementById('tabel-slot');
        const loadingEl = document.getElementById('status-loading');
        const errorEl = document.getElementById('pesan-error');
        const lapanganId = {{ $lapangan->id }};
        const isPelanggan = {{ auth()->check() && auth()->user()->role === 'pelanggan' ? 'true' : 'false' }};

        async function muatSlot(tanggal) {
            loadingEl.style.display = 'block';
            errorEl.style.display = 'none';
            tabelSlot.innerHTML = '';

            try {
                const res = await fetch(`/api/lapangan/${lapanganId}/slot?tanggal=${tanggal}`);
                if (!res.ok) throw new Error('Gagal mengambil data jadwal.');
                const data = await res.json();

                data.slot.forEach(item => {
                    const tr = document.createElement('article');
                    let aksiHtml = '-';

                    if (item.status === 'tersedia') {
                        if (isPelanggan) {
                            aksiHtml = `<a class="flex min-h-11 items-center justify-center gap-2 rounded-xl bg-emerald-500 px-3 text-xs font-bold text-obsidian hover:bg-emerald-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-300" href="/booking/create?lapangan_id=${lapanganId}&tanggal=${tanggal}&jam_mulai=${item.jam}">Pilih Jam Ini</a>`;
                        } else {
                            aksiHtml = `<a class="flex min-h-11 items-center justify-center gap-2 rounded-xl border border-white/10 px-3 text-xs font-bold text-slate-100 hover:border-emerald-400/30 hover:bg-emerald-500/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-300" href="/login">Login untuk Booking</a>`;
                        }
                    }

                    tr.className = `rounded-2xl border p-4 transition-all duration-200 ${item.status === 'tersedia' ? 'border-emerald-400/20 bg-emerald-500/10 hover:-translate-y-0.5 hover:border-emerald-300/50 hover:shadow-glow' : item.status === 'terisi' ? 'cursor-not-allowed border-white/5 bg-surface-2/60 opacity-70' : 'cursor-not-allowed border-rose-400/10 bg-surface/50 opacity-40'}`;
                    tr.innerHTML = `
                        <div class="flex items-start justify-between gap-2">
                            <span class="font-display text-xl font-bold text-white">${item.label}</span>
                            ${item.status === 'terisi' ? '<svg aria-label="Terkunci" class="mt-1 h-4 w-4 shrink-0 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>' : ''}
                        </div>
                        <p class="mt-1 text-xs font-semibold text-slate-400">${item.tipe}</p>
                        <p class="mt-4 font-display text-lg font-bold ${item.status === 'tersedia' ? 'text-emerald-300' : 'text-slate-400'} ${item.status === 'lewat' ? 'line-through' : ''}">Rp${item.harga.toLocaleString('id-ID')}<span class="text-xs font-medium text-slate-500">/jam</span></p>
                        <p class="mb-3 mt-2 text-[10px] font-bold uppercase tracking-wider ${item.status === 'tersedia' ? 'text-emerald-300' : item.status === 'terisi' ? 'text-slate-500' : 'text-rose-300'}">${item.status.toUpperCase()}</p>
                        ${aksiHtml}
                    `;
                    tabelSlot.appendChild(tr);
                });
            } catch (err) {
                errorEl.textContent = err.message;
                errorEl.style.display = 'block';
            } finally {
                loadingEl.style.display = 'none';
            }
        }

        inputTanggal.addEventListener('change', () => muatSlot(inputTanggal.value));
        muatSlot(inputTanggal.value);
    </script>
@endsection
