@extends('layouts.app')
@section('judul', 'Detail Booking ' . $booking->kode_booking)

@section('konten')
    <div class="mx-auto max-w-4xl">
        <div class="mb-7 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div><p class="text-xs font-bold uppercase tracking-[.18em] text-emerald-300">Rincian reservasi</p><h1 class="mt-2 font-display text-3xl font-bold text-white sm:text-4xl">Tiket pertandingan</h1></div>
            <a href="{{ route('booking.index') }}" class="inline-flex min-h-10 items-center gap-2 text-sm font-semibold text-slate-400 hover:text-white"><i data-lucide="arrow-left" class="h-4 w-4"></i>Kembali ke riwayat</a>
        </div>

        <article class="ticket-notch relative overflow-hidden rounded-3xl border border-white/10 bg-surface shadow-card">
            <div class="relative overflow-hidden bg-gradient-to-br from-emerald-950/60 via-surface to-surface px-6 py-7 sm:px-9 sm:py-9">
                <div class="absolute -right-16 -top-20 h-56 w-56 rounded-full bg-emerald-400/10 blur-3xl"></div>
                <div class="relative flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
                    <div><span class="inline-flex items-center gap-2 rounded-full border border-emerald-400/20 bg-emerald-500/10 px-3 py-1.5 text-[10px] font-bold uppercase tracking-[.18em] text-emerald-300"><i data-lucide="ticket" class="h-3.5 w-3.5"></i>ArenaFutsal · E-Tiket</span><p class="mt-5 text-xs font-semibold uppercase tracking-[.2em] text-slate-500">Kode booking</p><h2 class="mt-1 break-all font-display text-3xl font-bold tracking-[.08em] text-white sm:text-4xl">{{ $booking->kode_booking }}</h2></div>
                    @php
                        $statusTone = match($booking->status->value) {
                            'menunggu_pembayaran' => 'border-amber-400/20 bg-amber-500/10 text-amber-300',
                            'menunggu_verifikasi' => 'border-cyan-400/20 bg-cyan-500/10 text-cyan-300',
                            'dikonfirmasi', 'selesai' => 'border-emerald-400/20 bg-emerald-500/10 text-emerald-300',
                            'dibatalkan' => 'border-rose-400/20 bg-rose-500/10 text-rose-300',
                            'kedaluwarsa' => 'border-slate-500/20 bg-slate-500/10 text-slate-300',
                            default => 'border-white/10 bg-white/5 text-slate-300',
                        };
                    @endphp
                    <span class="inline-flex min-h-8 w-fit items-center rounded-full border px-3 text-xs font-bold {{ $statusTone }}">{{ $booking->status->label() }}</span>
                </div>
            </div>

            <div class="relative border-y border-dashed border-white/15 px-6 py-7 sm:px-9">
                <div class="grid gap-x-8 gap-y-6 sm:grid-cols-2">
                    <div><p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Lapangan</p><p class="mt-2 font-display text-xl font-bold text-white">{{ $booking->lapangan->nama }}</p><p class="mt-1 text-sm text-slate-400">{{ $booking->lapangan->jenis_lantai }}</p></div>
                    <div><p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Pemesan</p><p class="mt-2 font-semibold text-white">{{ $booking->nama_pemesan }}</p><p class="mt-1 text-sm text-slate-400">{{ $booking->no_hp_pemesan }}</p></div>
                    <div><p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Tanggal Main</p><p class="mt-2 font-display text-lg font-bold text-white">{{ $booking->tanggal->format('d F Y') }}</p></div>
                    <div><p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Waktu &amp; Durasi</p><p class="mt-2 font-display text-lg font-bold text-white">{{ substr($booking->jam_mulai,0,5) }}–{{ substr($booking->jam_selesai,0,5) }}</p><p class="mt-1 text-sm text-slate-400">{{ $booking->durasi }} jam bermain</p></div>
                </div>
                <div class="mt-7 flex flex-col gap-2 border-t border-white/10 pt-5 sm:flex-row sm:items-end sm:justify-between">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total tagihan</p>
                    <p class="font-display text-3xl font-bold text-emerald-300">Rp{{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                </div>
            </div>

            @if($booking->batas_bayar && $booking->status === \App\Enums\StatusBooking::MenungguPembayaran)
                <div class="mx-5 mt-5 flex items-start gap-3 rounded-2xl border border-rose-400/20 bg-rose-500/10 p-4 sm:mx-8">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-rose-500/15 text-rose-300"><i data-lucide="timer" class="h-5 w-5"></i></span>
                    <div class="min-w-0 flex-1"><p class="text-sm font-bold text-rose-200">Selesaikan pembayaran sebelum batas waktu</p><p class="mt-1 text-xs text-rose-200/70">Batas: {{ $booking->batas_bayar->format('d M Y H:i') }} WIB</p><p id="countdown-bayar" data-deadline="{{ $booking->batas_bayar->toIso8601String() }}" class="mt-2 font-display text-lg font-bold text-rose-300"></p></div>
                </div>
            @endif

            @if($booking->catatan_admin)
                <div class="mx-5 mt-5 flex gap-3 rounded-2xl border border-amber-400/15 bg-amber-500/5 p-4 sm:mx-8"><i data-lucide="message-square-warning" class="mt-0.5 h-5 w-5 shrink-0 text-amber-300"></i><div><p class="text-xs font-bold uppercase tracking-wider text-amber-200">Catatan Admin</p><p class="mt-1 text-sm leading-6 text-slate-200">{{ $booking->catatan_admin }}</p></div></div>
            @endif

            @if($booking->bukti_bayar)
                <section class="px-5 pt-6 sm:px-8">
                    <div class="mb-3 flex items-center justify-between gap-3"><div><p class="text-xs font-bold uppercase tracking-wider text-slate-400">Bukti pembayaran</p><p class="mt-1 text-xs text-slate-500">Pilih gambar untuk memperbesar.</p></div><i data-lucide="image" class="h-5 w-5 text-slate-500"></i></div>
                    <button type="button" id="bukti-preview-open" class="group block w-full max-w-sm overflow-hidden rounded-2xl border border-white/10 bg-obsidian/50 text-left focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400">
                        <img src="{{ asset('storage/' . $booking->bukti_bayar) }}" alt="Bukti Bayar" class="aspect-video w-full object-cover transition-transform duration-300 group-hover:scale-[1.02]">
                    </button>
                </section>
                <div id="bukti-preview-modal" class="fixed inset-0 z-[80] hidden items-center justify-center bg-obsidian/90 p-4 backdrop-blur-md" role="dialog" aria-modal="true" aria-label="Pratinjau bukti pembayaran">
                    <button type="button" id="bukti-preview-close" aria-label="Tutup pratinjau" class="absolute right-4 top-4 flex h-11 w-11 items-center justify-center rounded-full border border-white/15 bg-surface text-white hover:bg-white/10"><i data-lucide="x" class="h-5 w-5"></i></button>
                    <img src="{{ asset('storage/' . $booking->bukti_bayar) }}" alt="Bukti Bayar diperbesar" class="max-h-[85vh] max-w-full rounded-2xl object-contain shadow-card">
                </div>
            @endif

            <div class="mt-6 border-t border-dashed border-white/15 px-5 py-6 sm:px-8">
                @can('bayar', $booking)
                    <div class="rounded-2xl border border-white/10 bg-obsidian/35 p-5 sm:p-6">
                        <div class="flex items-start gap-3"><span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-cyan-500/10 text-cyan-300"><i data-lucide="landmark" class="h-5 w-5"></i></span><div><h3 class="font-display text-lg font-bold text-white">Instruksi pembayaran</h3><p class="mt-1 text-sm text-slate-400">Transfer sesuai total tagihan ke rekening berikut.</p></div></div>
                        <div class="mt-5 flex flex-col gap-3 rounded-xl border border-white/5 bg-surface p-4 sm:flex-row sm:items-center sm:justify-between">
                            <div><p class="text-xs font-bold uppercase tracking-wider text-slate-500">BCA · Arena Futsal</p><p id="nomor-rekening" class="mt-1 font-display text-xl font-bold tracking-[.12em] text-white">1234567890</p></div>
                            <button type="button" id="salin-rekening" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-white/10 px-4 text-sm font-bold text-slate-200 hover:border-emerald-400/30 hover:bg-emerald-500/10 hover:text-emerald-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400"><i data-lucide="copy" class="h-4 w-4"></i>Salin</button>
                        </div>
                        <form action="{{ route('booking.bayar', $booking->id) }}" method="POST" enctype="multipart/form-data" class="mt-5">
                            @csrf
                            <label for="bukti_bayar" class="mb-2 block text-sm font-semibold text-slate-200">Unggah Bukti Transfer</label>
                            <label id="dropzone-bukti" for="bukti_bayar" class="flex min-h-40 cursor-pointer flex-col items-center justify-center rounded-2xl border border-dashed border-white/15 bg-obsidian/50 p-5 text-center transition-colors hover:border-emerald-400/40 hover:bg-emerald-500/5 focus-within:ring-2 focus-within:ring-emerald-500/50">
                                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/5 text-emerald-300"><i data-lucide="cloud-upload" class="h-5 w-5"></i></span>
                                <span class="mt-3 text-sm font-bold text-slate-100">Pilih atau tarik gambar ke sini</span>
                                <span class="mt-1 text-xs text-slate-500">Format JPG atau PNG · Maksimal 2 MB</span>
                                <input id="bukti_bayar" type="file" name="bukti_bayar" accept="image/jpeg,image/png" required class="sr-only">
                            </label>
                            @error('bukti_bayar')<p class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</p>@enderror
                            <p id="pesan-file-bukti" class="mt-2 text-xs text-slate-400" aria-live="polite"></p>
                            <div id="preview-file-bukti" class="mt-3 hidden"><img id="gambar-file-bukti" alt="Pratinjau bukti transfer" class="max-h-56 rounded-xl border border-white/10 object-contain"><button type="button" id="hapus-file-bukti" class="mt-2 min-h-10 text-xs font-semibold text-rose-300 hover:text-rose-200">Pilih gambar lain</button></div>
                            <button type="submit" class="mt-4 flex min-h-12 w-full items-center justify-center gap-2 rounded-xl bg-emerald-500 px-5 font-bold text-obsidian hover:bg-emerald-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-300 active:scale-[.99]">Kirim Bukti Pembayaran <i data-lucide="arrow-up-right" class="h-4 w-4"></i></button>
                        </form>
                    </div>
                @endcan

                @can('batal', $booking)
                    <form action="{{ route('booking.batal', $booking->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan booking ini?');" class="mt-4">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="flex min-h-11 items-center justify-center gap-2 rounded-xl border border-rose-400/20 px-5 text-sm font-bold text-rose-300 hover:bg-rose-500/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-400"><i data-lucide="calendar-x-2" class="h-4 w-4"></i>Batalkan Booking</button>
                    </form>
                @endcan
            </div>
        </article>
    </div>

    <style>
        .ticket-notch::before, .ticket-notch::after { position: absolute; z-index: 2; top: 54%; width: 1.5rem; height: 1.5rem; border: 1px solid rgba(255,255,255,.1); border-radius: 9999px; background: #0B0F19; content: ""; }
        .ticket-notch::before { left: -.8rem; }
        .ticket-notch::after { right: -.8rem; }
    </style>
    <script>
        const countdownBayar = document.getElementById('countdown-bayar');
        if (countdownBayar) {
            const perbaruiCountdown = () => {
                const sisa = new Date(countdownBayar.dataset.deadline).getTime() - Date.now();
                if (sisa <= 0) {
                    countdownBayar.textContent = 'Waktu pembayaran telah berakhir';
                    return;
                }
                const jam = Math.floor(sisa / 3600000);
                const menit = Math.floor((sisa % 3600000) / 60000);
                const detik = Math.floor((sisa % 60000) / 1000);
                countdownBayar.textContent = `Sisa ${jam}j ${menit}m ${detik}d`;
            };
            perbaruiCountdown();
            window.setInterval(perbaruiCountdown, 1000);
        }

        const nomorRekening = document.getElementById('nomor-rekening');
        const tombolSalinRekening = document.getElementById('salin-rekening');
        if (nomorRekening && tombolSalinRekening) {
            tombolSalinRekening.addEventListener('click', async () => {
                try {
                    await navigator.clipboard.writeText(nomorRekening.textContent.trim());
                    tombolSalinRekening.innerHTML = '<i data-lucide="check" class="h-4 w-4"></i>Tersalin';
                    if (window.lucide) lucide.createIcons();
                } catch (err) {
                    tombolSalinRekening.textContent = 'Gagal menyalin';
                }
            });
        }

        const modalBukti = document.getElementById('bukti-preview-modal');
        const bukaBukti = document.getElementById('bukti-preview-open');
        const tutupBukti = document.getElementById('bukti-preview-close');
        if (modalBukti && bukaBukti && tutupBukti) {
            bukaBukti.addEventListener('click', () => modalBukti.classList.remove('hidden'));
            tutupBukti.addEventListener('click', () => modalBukti.classList.add('hidden'));
            modalBukti.addEventListener('click', (event) => {
                if (event.target === modalBukti) modalBukti.classList.add('hidden');
            });
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') modalBukti.classList.add('hidden');
            });
        }

        const inputBukti = document.getElementById('bukti_bayar');
        const dropzoneBukti = document.getElementById('dropzone-bukti');
        const pesanBukti = document.getElementById('pesan-file-bukti');
        const previewBukti = document.getElementById('preview-file-bukti');
        const gambarBukti = document.getElementById('gambar-file-bukti');
        const hapusBukti = document.getElementById('hapus-file-bukti');
        if (inputBukti && dropzoneBukti && pesanBukti && previewBukti && gambarBukti && hapusBukti) {
            const periksaFile = (file) => {
                if (!file) return;
                if (!['image/jpeg', 'image/png'].includes(file.type) || file.size > 2 * 1024 * 1024) {
                    inputBukti.value = '';
                    previewBukti.classList.add('hidden');
                    pesanBukti.textContent = 'Pilih gambar JPG atau PNG dengan ukuran maksimal 2 MB.';
                    pesanBukti.classList.add('text-rose-300');
                    return;
                }
                pesanBukti.textContent = file.name;
                pesanBukti.classList.remove('text-rose-300');
                gambarBukti.src = URL.createObjectURL(file);
                previewBukti.classList.remove('hidden');
            };
            inputBukti.addEventListener('change', () => periksaFile(inputBukti.files[0]));
            dropzoneBukti.addEventListener('dragover', (event) => {
                event.preventDefault();
                dropzoneBukti.classList.add('border-emerald-400');
            });
            dropzoneBukti.addEventListener('dragleave', () => dropzoneBukti.classList.remove('border-emerald-400'));
            dropzoneBukti.addEventListener('drop', (event) => {
                event.preventDefault();
                dropzoneBukti.classList.remove('border-emerald-400');
                if (event.dataTransfer.files.length) {
                    inputBukti.files = event.dataTransfer.files;
                    periksaFile(inputBukti.files[0]);
                }
            });
            hapusBukti.addEventListener('click', () => {
                inputBukti.value = '';
                gambarBukti.removeAttribute('src');
                previewBukti.classList.add('hidden');
                pesanBukti.textContent = '';
            });
        }
    </script>
@endsection
