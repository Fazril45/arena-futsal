@extends('layouts.app')
@section('judul', 'Kelola Booking Admin - ArenaFutsal')

@section('konten')
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div><p class="text-xs font-bold uppercase tracking-[.18em] text-emerald-300">Operasional</p><h1 class="mt-2 font-display text-3xl font-bold text-white sm:text-4xl">Verifikasi Booking</h1><p class="mt-2 text-sm text-slate-400">Kelola pembayaran dan jadwal seluruh pelanggan.</p></div>
        <span class="inline-flex min-h-9 w-fit items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 text-xs font-bold text-slate-300"><i data-lucide="shield-check" class="h-4 w-4 text-emerald-300"></i>Panel Admin</span>
    </div>

    <div class="mt-7 grid gap-3 sm:grid-cols-2">
        <div class="flex items-center gap-4 rounded-2xl border border-white/10 bg-surface/75 p-4"><span class="flex h-11 w-11 items-center justify-center rounded-xl bg-cyan-500/10 text-cyan-300"><i data-lucide="layers-3" class="h-5 w-5"></i></span><div><p class="text-xs font-semibold text-slate-500">Hasil pencarian</p><p class="mt-1 font-display text-2xl font-bold text-white">{{ $bookings->total() }}</p></div></div>
        <div class="flex items-center gap-4 rounded-2xl border border-white/10 bg-surface/75 p-4"><span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-300"><i data-lucide="list-checks" class="h-5 w-5"></i></span><div><p class="text-xs font-semibold text-slate-500">Ditampilkan di halaman ini</p><p class="mt-1 font-display text-2xl font-bold text-white">{{ $bookings->count() }}</p></div></div>
    </div>

    <form method="GET" action="{{ route('admin.booking.index') }}" class="mt-5 grid gap-3 rounded-2xl border border-white/10 bg-surface/70 p-4 backdrop-blur-xl sm:grid-cols-2 xl:grid-cols-[1.4fr_1fr_1fr_auto_auto] xl:items-center">
        <label class="relative block"><span class="sr-only">Cari kode atau nama pemesan</span><i data-lucide="search" class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500"></i><input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode / nama pemesan..." class="h-11 w-full rounded-xl border border-white/10 bg-obsidian/70 pl-11 pr-4 text-sm text-white outline-none placeholder:text-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30"></label>
        <select name="lapangan_id" aria-label="Filter lapangan" class="h-11 w-full rounded-xl border border-white/10 bg-obsidian/70 px-4 text-sm text-white outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30">
            <option value="">-- Semua Lapangan --</option>
            @foreach($lapangans as $lap)
                <option value="{{ $lap->id }}" {{ request('lapangan_id') == $lap->id ? 'selected' : '' }}>{{ $lap->nama }}</option>
            @endforeach
        </select>
        <select name="status" aria-label="Filter status" class="h-11 w-full rounded-xl border border-white/10 bg-obsidian/70 px-4 text-sm text-white outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30">
            <option value="">-- Semua Status --</option>
            @foreach(\App\Enums\StatusBooking::cases() as $st)
                <option value="{{ $st->value }}" {{ request('status') === $st->value ? 'selected' : '' }}>{{ $st->label() }}</option>
            @endforeach
        </select>
        <button type="submit" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-emerald-500 px-5 text-sm font-bold text-obsidian hover:bg-emerald-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-300"><i data-lucide="list-filter" class="h-4 w-4"></i>Terapkan</button>
        <a href="{{ route('admin.booking.index') }}" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-white/10 px-5 text-sm font-semibold text-slate-300 hover:bg-white/5 hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-300"><i data-lucide="rotate-ccw" class="h-4 w-4"></i>Reset</a>
    </form>

    <div class="mt-6 overflow-hidden rounded-2xl border border-white/10 bg-surface shadow-card">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1180px] text-left text-sm">
                <thead class="sticky top-0 z-10 border-b border-white/10 bg-surface-2 text-[11px] uppercase tracking-wider text-slate-400"><tr><th class="px-4 py-4 font-bold">Kode</th><th class="px-4 py-4 font-bold">Pelanggan</th><th class="px-4 py-4 font-bold">Lapangan &amp; Jadwal</th><th class="px-4 py-4 font-bold">Total</th><th class="px-4 py-4 font-bold">Bukti Bayar</th><th class="px-4 py-4 font-bold">Status</th><th class="px-4 py-4 font-bold">Aksi Admin</th></tr></thead>
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
                        <tr class="align-top transition-colors hover:bg-white/[.025]">
                            <td class="whitespace-nowrap px-4 py-4 font-display font-bold tracking-wide text-white">{{ $b->kode_booking }}</td>
                            <td class="px-4 py-4"><p class="font-semibold text-slate-100">{{ $b->nama_pemesan }}</p><p class="mt-1 text-xs text-slate-500">{{ $b->no_hp_pemesan }}</p></td>
                            <td class="px-4 py-4"><p class="font-semibold text-slate-100">{{ $b->lapangan->nama }}</p><p class="mt-1 text-xs text-slate-400">{{ $b->tanggal->format('d M Y') }} · {{ substr($b->jam_mulai,0,5) }}–{{ substr($b->jam_selesai,0,5) }}</p></td>
                            <td class="whitespace-nowrap px-4 py-4 font-display font-bold text-slate-100">Rp{{ number_format($b->total_harga, 0, ',', '.') }}</td>
                            <td class="px-4 py-4">
                                @if($b->bukti_bayar)
                                    <button type="button" class="bukti-admin-open flex items-center gap-2 text-left text-xs font-semibold text-emerald-300 hover:text-emerald-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400" data-preview-src="{{ asset('storage/' . $b->bukti_bayar) }}"><img src="{{ asset('storage/' . $b->bukti_bayar) }}" alt="Bukti bayar {{ $b->kode_booking }}" loading="lazy" class="h-12 w-16 rounded-lg border border-white/10 object-cover"><span>Lihat bukti</span></button>
                                @else
                                    <span class="text-xs text-slate-600">Belum ada</span>
                                @endif
                            </td>
                            <td class="px-4 py-4"><span class="inline-flex min-h-7 items-center rounded-full border px-3 text-[11px] font-bold {{ $statusTone }}">{{ $b->status->label() }}</span>
                                @if($b->catatan_admin)
                                    <p class="mt-2 max-w-48 text-xs leading-5 text-rose-300">Catatan: {{ $b->catatan_admin }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex min-w-48 flex-col gap-2">
                                    @if($b->status === \App\Enums\StatusBooking::MenungguVerifikasi)
                                        <form action="{{ route('admin.booking.verifikasi', $b->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="aksi" value="setujui">
                                            <button type="submit" class="flex min-h-10 w-full items-center justify-center gap-2 rounded-xl bg-emerald-500 px-3 text-xs font-bold text-obsidian hover:bg-emerald-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-300"><i data-lucide="check" class="h-4 w-4"></i>Setujui</button>
                                        </form>

                                        <form action="{{ route('admin.booking.verifikasi', $b->id) }}" method="POST" class="space-y-2">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="aksi" value="tolak">
                                            <label class="block"><span class="sr-only">Alasan penolakan</span><input type="text" name="catatan_admin" placeholder="Alasan tolak..." required class="h-10 w-full rounded-xl border border-white/10 bg-obsidian/70 px-3 text-xs text-white outline-none placeholder:text-slate-500 focus:border-rose-400 focus:ring-2 focus:ring-rose-500/20"></label>
                                            <button type="submit" class="flex min-h-10 w-full items-center justify-center gap-2 rounded-xl border border-rose-400/20 bg-rose-500/10 px-3 text-xs font-bold text-rose-300 hover:bg-rose-500/20 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-400"><i data-lucide="x" class="h-4 w-4"></i>Tolak</button>
                                        </form>
                                    @endif

                                    @if(in_array($b->status->value, \App\Enums\StatusBooking::aktif()))
                                        <form action="{{ route('admin.booking.batal', $b->id) }}" method="POST" onsubmit="return confirm('Batalkan booking ini?');" class="space-y-2">
                                            @csrf
                                            @method('PATCH')
                                            <input type="text" name="catatan_admin" placeholder="Alasan batal..." required class="h-10 w-full rounded-xl border border-white/10 bg-obsidian/70 px-3 text-xs text-white outline-none placeholder:text-slate-500 focus:border-rose-400 focus:ring-2 focus:ring-rose-500/20">
                                            <button type="submit" class="flex min-h-10 w-full items-center justify-center gap-2 rounded-xl border border-rose-400/20 px-3 text-xs font-bold text-rose-300 hover:bg-rose-500/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-400"><i data-lucide="ban" class="h-4 w-4"></i>Batalkan</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-6 py-16 text-center"><span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-white/5 text-slate-500"><i data-lucide="inbox" class="h-7 w-7"></i></span><p class="mt-4 font-display text-lg font-bold text-white">Belum ada data booking</p><p class="mt-1 text-sm text-slate-500">Coba ubah filter untuk melihat hasil lainnya.</p></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5 rounded-2xl border border-white/10 bg-surface/70 p-3 sm:p-4">
        {{ $bookings->links() }}
    </div>

    <div id="modal-bukti-admin" class="fixed inset-0 z-[80] hidden items-center justify-center bg-obsidian/90 p-4 backdrop-blur-md" role="dialog" aria-modal="true" aria-label="Pratinjau bukti pembayaran">
        <button type="button" id="tutup-bukti-admin" aria-label="Tutup pratinjau" class="absolute right-4 top-4 flex h-11 w-11 items-center justify-center rounded-full border border-white/15 bg-surface text-white hover:bg-white/10"><i data-lucide="x" class="h-5 w-5"></i></button>
        <img id="gambar-bukti-admin" src="" alt="Bukti pembayaran diperbesar" class="max-h-[85vh] max-w-full rounded-2xl object-contain shadow-card">
    </div>

    <script>
        const modalBuktiAdmin = document.getElementById('modal-bukti-admin');
        const gambarBuktiAdmin = document.getElementById('gambar-bukti-admin');
        const tutupBuktiAdmin = document.getElementById('tutup-bukti-admin');
        if (modalBuktiAdmin && gambarBuktiAdmin && tutupBuktiAdmin) {
            document.querySelectorAll('.bukti-admin-open').forEach((button) => {
                button.addEventListener('click', () => {
                    gambarBuktiAdmin.src = button.dataset.previewSrc;
                    modalBuktiAdmin.classList.remove('hidden');
                });
            });
            const sembunyikanModal = () => {
                modalBuktiAdmin.classList.add('hidden');
                gambarBuktiAdmin.removeAttribute('src');
            };
            tutupBuktiAdmin.addEventListener('click', sembunyikanModal);
            modalBuktiAdmin.addEventListener('click', (event) => {
                if (event.target === modalBuktiAdmin) sembunyikanModal();
            });
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') sembunyikanModal();
            });
        }
    </script>
@endsection
