@extends('layouts.app')
@section('judul', 'Riwayat Booking - ArenaFutsal')

@section('konten')
    <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div><p class="text-xs font-bold uppercase tracking-[.18em] text-emerald-300">Ruang pribadimu</p><h1 class="mt-2 font-display text-3xl font-bold text-white sm:text-4xl">Riwayat Booking</h1><p class="mt-2 text-sm text-slate-400">Semua jadwal dan status reservasi dalam satu tempat.</p></div>
        <a href="{{ route('booking.create') }}" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-emerald-500 px-5 text-sm font-bold text-obsidian hover:bg-emerald-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-300"><i data-lucide="plus" class="h-4 w-4"></i>Buat Booking</a>
    </div>

    <form method="GET" action="{{ route('booking.index') }}" class="mt-7 flex flex-col gap-3 rounded-2xl border border-white/10 bg-surface/70 p-4 sm:flex-row sm:items-center sm:justify-between">
        <label for="status" class="text-sm font-semibold text-slate-300">Filter status</label>
        <div class="flex w-full gap-2 sm:max-w-md">
            <select id="status" name="status" onchange="this.form.submit()" class="h-11 min-w-0 flex-1 rounded-xl border border-white/10 bg-obsidian/70 px-4 text-sm text-white outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30">
                <option value="">-- Semua Status --</option>
                @foreach(\App\Enums\StatusBooking::cases() as $st)
                    <option value="{{ $st->value }}" {{ request('status') === $st->value ? 'selected' : '' }}>
                        {{ $st->label() }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-white/10 text-slate-300 hover:bg-white/5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500" aria-label="Terapkan filter"><i data-lucide="list-filter" class="h-4 w-4"></i></button>
        </div>
    </form>

    <div class="mt-6 grid gap-3 lg:hidden">
        @forelse($bookings as $b)
            @php
                $statusTone = match($b->status->value) {
                    'menunggu_pembayaran' => 'border-amber-400/20 bg-amber-500/10 text-amber-300',
                    'menunggu_verifikasi' => 'border-cyan-400/20 bg-cyan-500/10 text-cyan-300',
                    'dikonfirmasi', 'selesai' => 'border-emerald-400/20 bg-emerald-500/10 text-emerald-300',
                    'dibatalkan' => 'border-rose-400/20 bg-rose-500/10 text-rose-300',
                    'kedaluwarsa' => 'border-slate-500/20 bg-slate-500/10 text-slate-300',
                    default => 'border-white/10 bg-white/5 text-slate-300',
                };
            @endphp
            <article class="rounded-2xl border border-white/10 bg-surface p-5 shadow-card">
                <div class="flex items-start justify-between gap-3"><div><p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Kode booking</p><p class="mt-1 font-display text-lg font-bold tracking-wide text-white">{{ $b->kode_booking }}</p></div><span class="inline-flex min-h-7 items-center rounded-full border px-3 text-[11px] font-bold {{ $statusTone }}">{{ $b->status->label() }}</span></div>
                <div class="mt-4 border-t border-white/5 pt-4"><p class="font-semibold text-slate-100">{{ $b->lapangan->nama }}</p><p class="mt-1 text-sm text-slate-400">{{ $b->tanggal->format('d M Y') }} · {{ substr($b->jam_mulai,0,5) }}–{{ substr($b->jam_selesai,0,5) }}</p></div>
                <div class="mt-4 flex items-center justify-between gap-3"><div><p class="text-xs text-slate-500">Total</p><p class="mt-1 font-display text-lg font-bold text-emerald-300">Rp{{ number_format($b->total_harga, 0, ',', '.') }}</p></div><a href="{{ route('booking.show', $b->kode_booking) }}" class="flex min-h-11 items-center gap-2 rounded-xl border border-white/10 px-4 text-sm font-bold text-slate-100 hover:border-emerald-400/30 hover:bg-emerald-500/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-300">Detail <i data-lucide="arrow-up-right" class="h-4 w-4"></i></a></div>
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-white/10 bg-surface/50 px-6 py-14 text-center">
                <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-white/5 text-slate-400"><i data-lucide="calendar-x-2" class="h-7 w-7"></i></span>
                <h3 class="mt-5 font-display text-xl font-bold text-white">Belum ada data booking</h3>
                <p class="mt-2 text-sm text-slate-400">Pilih lapangan dan jadwalkan pertandingan pertamamu.</p>
                <a href="{{ route('booking.create') }}" class="mt-5 inline-flex min-h-11 items-center gap-2 rounded-xl bg-emerald-500 px-5 text-sm font-bold text-obsidian hover:bg-emerald-400"><i data-lucide="plus" class="h-4 w-4"></i>Buat Booking</a>
            </div>
        @endforelse
    </div>

    <div class="mt-6 hidden overflow-hidden rounded-2xl border border-white/10 bg-surface shadow-card lg:block">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] text-left text-sm">
                <thead class="border-b border-white/10 bg-obsidian/60 text-xs uppercase tracking-wider text-slate-500"><tr><th class="px-5 py-4 font-bold">Kode Booking</th><th class="px-5 py-4 font-bold">Lapangan</th><th class="px-5 py-4 font-bold">Tanggal &amp; Jam</th><th class="px-5 py-4 font-bold">Total Harga</th><th class="px-5 py-4 font-bold">Status</th><th class="px-5 py-4 text-right font-bold">Aksi</th></tr></thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($bookings as $b)
                        @php
                            $statusTone = match($b->status->value) {
                                'menunggu_pembayaran' => 'border-amber-400/20 bg-amber-500/10 text-amber-300',
                                'menunggu_verifikasi' => 'border-cyan-400/20 bg-cyan-500/10 text-cyan-300',
                                'dikonfirmasi', 'selesai' => 'border-emerald-400/20 bg-emerald-500/10 text-emerald-300',
                                'dibatalkan' => 'border-rose-400/20 bg-rose-500/10 text-rose-300',
                                'kedaluwarsa' => 'border-slate-500/20 bg-slate-500/10 text-slate-300',
                                default => 'border-white/10 bg-white/5 text-slate-300',
                            };
                        @endphp
                        <tr class="transition-colors hover:bg-white/[.025]">
                            <td class="px-5 py-4 font-display font-bold tracking-wide text-white">{{ $b->kode_booking }}</td>
                            <td class="px-5 py-4 font-semibold text-slate-200">{{ $b->lapangan->nama }}</td>
                            <td class="px-5 py-4 text-slate-400">{{ $b->tanggal->format('d M Y') }}<span class="mt-1 block text-xs text-slate-500">{{ substr($b->jam_mulai,0,5) }}–{{ substr($b->jam_selesai,0,5) }}</span></td>
                            <td class="px-5 py-4 font-display font-bold text-slate-100">Rp{{ number_format($b->total_harga, 0, ',', '.') }}</td>
                            <td class="px-5 py-4"><span class="inline-flex min-h-7 items-center rounded-full border px-3 text-[11px] font-bold {{ $statusTone }}">{{ $b->status->label() }}</span></td>
                            <td class="px-5 py-4 text-right"><a href="{{ route('booking.show', $b->kode_booking) }}" class="inline-flex min-h-10 items-center gap-2 rounded-xl border border-white/10 px-4 text-xs font-bold text-slate-100 hover:border-emerald-400/30 hover:bg-emerald-500/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-300">Detail <i data-lucide="arrow-up-right" class="h-4 w-4"></i></a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center text-slate-400"><i data-lucide="calendar-x-2" class="mx-auto h-8 w-8 text-slate-500"></i><p class="mt-3 font-semibold text-slate-200">Belum ada data booking.</p><a href="{{ route('booking.create') }}" class="mt-4 inline-flex min-h-10 items-center gap-2 rounded-xl bg-emerald-500 px-4 text-xs font-bold text-obsidian hover:bg-emerald-400">Buat Booking <i data-lucide="arrow-right" class="h-4 w-4"></i></a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 rounded-2xl border border-white/10 bg-surface/70 p-3 sm:p-4">
        {{ $bookings->links() }}
    </div>
@endsection
