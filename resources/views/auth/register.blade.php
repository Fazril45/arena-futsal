@extends('layouts.app')
@section('judul', 'Daftar Pelanggan - ArenaFutsal')

@section('konten')
    <div class="mx-auto grid min-h-[calc(100vh-15rem)] max-w-6xl items-center gap-8 lg:grid-cols-2 lg:gap-14">
        <aside class="relative hidden min-h-[680px] overflow-hidden rounded-3xl border border-white/10 bg-surface shadow-card lg:block">
            <img data-foto-fallback="vinyl" alt="Lapangan futsal ArenaFutsal" class="absolute inset-0 h-full w-full object-cover" onerror="this.onerror=null;this.style.display='none';this.parentElement.classList.add('bg-gradient-to-br','from-emerald-950','via-surface','to-obsidian')">
            <div class="absolute inset-0 bg-gradient-to-t from-obsidian via-obsidian/55 to-obsidian/10"></div>
            <div class="absolute inset-x-0 bottom-0 p-8 xl:p-10">
                <span class="mb-5 inline-flex items-center gap-2 rounded-full border border-emerald-300/25 bg-emerald-400/10 px-4 py-2 text-xs font-bold uppercase tracking-[.18em] text-emerald-200"><i data-lucide="users-round" class="h-4 w-4"></i>Gabung komunitas</span>
                <h1 class="max-w-lg font-display text-4xl font-bold leading-tight text-white xl:text-5xl">Satu akun. <span class="text-emerald-300">Lebih banyak aksi.</span></h1>
                <p class="mt-4 max-w-md text-sm leading-7 text-slate-300">Buat akun ArenaFutsal dan siapkan pertandingan berikutnya dengan lebih mudah.</p>
                <div class="mt-8 grid gap-3">
                    <div class="flex items-center gap-3 text-sm font-medium text-slate-100"><span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-emerald-300"><i data-lucide="calendar-days" class="h-4 w-4"></i></span>Pilih waktu sesuai tim kamu</div>
                    <div class="flex items-center gap-3 text-sm font-medium text-slate-100"><span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-emerald-300"><i data-lucide="badge-check" class="h-4 w-4"></i></span>Pantau status booking kapan saja</div>
                    <div class="flex items-center gap-3 text-sm font-medium text-slate-100"><span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-emerald-300"><i data-lucide="heart-handshake" class="h-4 w-4"></i></span>Siap main bersama teman</div>
                </div>
            </div>
        </aside>

        <section class="mx-auto w-full max-w-xl">
            <div class="relative mb-5 h-32 overflow-hidden rounded-2xl border border-white/10 bg-surface lg:hidden">
                <img data-foto-fallback="vinyl" alt="Lapangan futsal ArenaFutsal" class="h-full w-full object-cover" onerror="this.onerror=null;this.style.display='none';this.parentElement.classList.add('bg-gradient-to-br','from-emerald-950','via-surface','to-obsidian')">
                <div class="absolute inset-0 bg-gradient-to-r from-obsidian/90 to-obsidian/20"></div>
                <div class="absolute inset-0 flex items-center px-5">
                    <div><p class="font-display text-xl font-bold">Arena<span class="text-emerald-300">Futsal</span></p><p class="mt-1 text-xs text-slate-300">Bersiap untuk permainan berikutnya.</p></div>
                </div>
            </div>
            <div class="rounded-3xl border border-white/10 bg-surface/80 p-6 shadow-card backdrop-blur-xl sm:p-9">
                <div class="mb-7">
                    <span class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-300"><i data-lucide="user-round-plus" class="h-6 w-6"></i></span>
                    <p class="text-xs font-bold uppercase tracking-[.2em] text-emerald-300">Mulai perjalananmu</p>
                    <h2 class="mt-2 font-display text-3xl font-bold tracking-tight text-white">Buat akun</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-400">Daftar sebagai pelanggan untuk mulai memesan lapangan.</p>
                </div>
                <form action="{{ route('register') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="mb-2 block text-sm font-semibold text-slate-200">Nama Lengkap</label>
                        <div class="relative"><i data-lucide="user-round" class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500"></i><input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" class="h-12 w-full rounded-xl border border-white/10 bg-obsidian/70 pl-11 pr-4 text-sm text-white outline-none placeholder:text-slate-600 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30" placeholder="Nama lengkap"></div>
                        @error('name')<p class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-slate-200">Email</label>
                        <div class="relative"><i data-lucide="mail" class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500"></i><input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="h-12 w-full rounded-xl border border-white/10 bg-obsidian/70 pl-11 pr-4 text-sm text-white outline-none placeholder:text-slate-600 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30" placeholder="nama@email.com"></div>
                        @error('email')<p class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="no_hp" class="mb-2 block text-sm font-semibold text-slate-200">Nomor HP (08xxxxxxxxxx)</label>
                        <div class="relative"><i data-lucide="phone" class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500"></i><input id="no_hp" type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="081234567890" required autocomplete="tel" class="h-12 w-full rounded-xl border border-white/10 bg-obsidian/70 pl-11 pr-4 text-sm text-white outline-none placeholder:text-slate-600 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30"></div>
                        @error('no_hp')<p class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="password" class="mb-2 block text-sm font-semibold text-slate-200">Kata Sandi (min. 8 karakter)</label>
                        <div class="relative"><i data-lucide="lock-keyhole" class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500"></i><input id="password" type="password" name="password" required autocomplete="new-password" class="h-12 w-full rounded-xl border border-white/10 bg-obsidian/70 pl-11 pr-12 text-sm text-white outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30"><button type="button" class="password-toggle absolute right-2 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-lg text-slate-500 hover:bg-white/5 hover:text-slate-200" aria-label="Tampilkan kata sandi" data-target="password"><i data-lucide="eye" class="h-4 w-4"></i></button></div>
                        @error('password')<p class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-200">Konfirmasi Kata Sandi</label>
                        <div class="relative"><i data-lucide="lock-keyhole" class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500"></i><input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="h-12 w-full rounded-xl border border-white/10 bg-obsidian/70 pl-11 pr-12 text-sm text-white outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30"><button type="button" class="password-toggle absolute right-2 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-lg text-slate-500 hover:bg-white/5 hover:text-slate-200" aria-label="Tampilkan kata sandi" data-target="password_confirmation"><i data-lucide="eye" class="h-4 w-4"></i></button></div>
                        @error('password_confirmation')<p class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="mt-2 flex min-h-12 w-full items-center justify-center gap-2 rounded-xl bg-emerald-500 px-5 font-bold text-obsidian shadow-glow hover:-translate-y-0.5 hover:bg-emerald-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-300 active:translate-y-0">Daftar Sekarang <i data-lucide="arrow-right" class="h-4 w-4"></i></button>
                </form>
                <p class="mt-6 text-center text-sm text-slate-400">Sudah punya akun? <a href="{{ route('login') }}" class="font-bold text-emerald-300 underline decoration-emerald-500/30 underline-offset-4 hover:text-emerald-200">Masuk di sini</a></p>
            </div>
        </section>
    </div>
    <script>
        document.querySelectorAll('.password-toggle').forEach((button) => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.target);
                const isHidden = input.type === 'password';
                input.type = isHidden ? 'text' : 'password';
                button.setAttribute('aria-label', isHidden ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
                button.innerHTML = isHidden ? '<i data-lucide="eye-off" class="h-4 w-4"></i>' : '<i data-lucide="eye" class="h-4 w-4"></i>';
                if (window.lucide) lucide.createIcons();
            });
        });
    </script>
@endsection
