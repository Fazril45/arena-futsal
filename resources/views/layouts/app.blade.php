<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0B0F19">
    <title>@yield('judul', 'ArenaFutsal')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        obsidian: '#0B0F19',
                        surface: '#131826',
                        'surface-2': '#1A2133',
                        border: 'rgba(255,255,255,0.08)',
                        emerald: { 400: '#22C55E', 500: '#10B981', 600: '#059669' },
                        cyan: { 500: '#06B6D4' },
                        amber: { 500: '#F59E0B' },
                        rose: { 500: '#F43F5E' },
                        slate: { 600: '#475569', 400: '#94A3B8', 500: '#64748B' }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        display: ['Space Grotesk', 'sans-serif']
                    },
                    boxShadow: {
                        glow: '0 0 40px rgba(16,185,129,0.25)',
                        card: '0 18px 60px rgba(0,0,0,0.2)'
                    },
                    keyframes: {
                        'toast-in': {
                            from: { opacity: '0', transform: 'translateY(-12px) translateX(12px)' },
                            to: { opacity: '1', transform: 'translateY(0) translateX(0)' }
                        }
                    },
                    animation: { 'toast-in': 'toast-in 300ms ease-out both' }
                }
            }
        };
    </script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        html { color-scheme: dark; }
        body { background-image: radial-gradient(ellipse at 12% 0%, rgba(16,185,129,.10), transparent 34rem); }
        #tabel-slot:empty::before { content: "Belum ada slot tersedia untuk tanggal ini."; display: block; grid-column: 1 / -1; padding: 2rem; text-align: center; color: #94A3B8; }
        [data-toast] { animation: toast-in 300ms ease-out both; }
        .toast-hide { opacity: 0; transform: translateY(-8px); transition: opacity 250ms ease, transform 250ms ease; }
        nav[role="navigation"] a, nav[role="navigation"] span { border-color: rgba(255,255,255,.1) !important; color: #cbd5e1 !important; }
        nav[role="navigation"] a:hover { background: rgba(255,255,255,.08) !important; color: #fff !important; }
        nav[role="navigation"] span[aria-current="page"] { background: rgba(16,185,129,.18) !important; border-color: rgba(16,185,129,.35) !important; color: #6ee7b7 !important; }
        nav[role="navigation"] svg { color: #94a3b8 !important; }
        input, select, textarea, button, a { transition: all 200ms ease; }
    </style>
    @php
        $fotoLapanganFallback = [
            'vinyl' => 'https://images.unsplash.com/photo-1793929590042-06dbc8fdda46?auto=format&fit=crop&w=1200&q=80',
            'rumput' => 'https://images.unsplash.com/photo-1522778119026-d647f0596c20?auto=format&fit=crop&w=1200&q=80',
            'semen' => 'https://images.unsplash.com/photo-1552741775-7817c0c85843?auto=format&fit=crop&w=1200&q=80',
        ];
    @endphp
</head>
<body class="min-h-screen bg-obsidian font-sans text-slate-100 antialiased">
    <nav class="sticky top-0 z-50 border-b border-white/10 bg-obsidian/80 backdrop-blur-xl">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex min-h-11 items-center gap-2 rounded-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500/70">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-500 text-obsidian"><i data-lucide="trophy" class="h-5 w-5"></i></span>
                <span class="font-display text-lg font-bold tracking-tight text-white">Arena<span class="text-emerald-400">Futsal</span></span>
            </a>

            <button type="button" id="menu-mobile-toggle" aria-label="Buka menu" aria-expanded="false" class="flex h-11 w-11 items-center justify-center rounded-xl border border-white/10 text-slate-200 hover:bg-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 lg:hidden">
                <i data-lucide="menu" class="h-5 w-5"></i>
            </button>

            <div class="hidden items-center gap-1 lg:flex">
                @auth
                    <a href="{{ route('home') }}" class="rounded-full px-4 py-2 text-sm font-semibold {{ request()->routeIs('home') || request()->routeIs('lapangan.*') ? 'bg-emerald-500/15 text-emerald-300' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">Lapangan</a>
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.booking.index') }}" class="rounded-full px-4 py-2 text-sm font-semibold {{ request()->routeIs('admin.booking.*') ? 'bg-emerald-500/15 text-emerald-300' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">Verifikasi Booking</a>
                    @else
                        <a href="{{ route('booking.index') }}" class="rounded-full px-4 py-2 text-sm font-semibold {{ request()->routeIs('booking.index') || request()->routeIs('booking.show') ? 'bg-emerald-500/15 text-emerald-300' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">Booking Saya</a>
                    @endif
                    <div class="ml-3 flex items-center gap-2 border-l border-white/10 pl-4">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-surface-2 font-display font-bold text-emerald-300">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        <span class="max-w-36 truncate text-sm font-semibold text-slate-100">{{ auth()->user()->name }}</span>
                        <span class="rounded-full border border-white/10 bg-white/5 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ auth()->user()->role === 'admin' ? 'Admin' : 'Pelanggan' }}</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="ml-2">
                        @csrf
                        <button type="submit" class="flex min-h-11 items-center gap-2 rounded-xl border border-white/10 px-4 text-sm font-semibold text-slate-300 hover:border-rose-500/40 hover:bg-rose-500/10 hover:text-rose-300 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-400"><i data-lucide="log-out" class="h-4 w-4"></i>Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="rounded-full px-4 py-2 text-sm font-semibold {{ request()->routeIs('login') ? 'bg-emerald-500/15 text-emerald-300' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">Masuk</a>
                    <a href="{{ route('register') }}" class="ml-1 inline-flex min-h-11 items-center rounded-xl bg-emerald-500 px-5 text-sm font-bold text-obsidian hover:bg-emerald-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-300">Daftar</a>
                @endauth
            </div>
        </div>
        <div id="menu-mobile" class="hidden border-t border-white/10 bg-obsidian/95 px-4 py-4 backdrop-blur-xl lg:hidden">
            <div class="mx-auto flex max-w-7xl flex-col gap-2 sm:px-2">
                @auth
                    <a href="{{ route('home') }}" class="flex min-h-11 items-center rounded-xl px-4 text-sm font-semibold {{ request()->routeIs('home') || request()->routeIs('lapangan.*') ? 'bg-emerald-500/15 text-emerald-300' : 'text-slate-300 hover:bg-white/5' }}">Lapangan</a>
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.booking.index') }}" class="flex min-h-11 items-center rounded-xl px-4 text-sm font-semibold {{ request()->routeIs('admin.booking.*') ? 'bg-emerald-500/15 text-emerald-300' : 'text-slate-300 hover:bg-white/5' }}">Verifikasi Booking</a>
                    @else
                        <a href="{{ route('booking.index') }}" class="flex min-h-11 items-center rounded-xl px-4 text-sm font-semibold {{ request()->routeIs('booking.index') || request()->routeIs('booking.show') ? 'bg-emerald-500/15 text-emerald-300' : 'text-slate-300 hover:bg-white/5' }}">Booking Saya</a>
                    @endif
                    <div class="flex items-center gap-3 border-t border-white/10 pt-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-surface-2 font-display font-bold text-emerald-300">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        <span class="min-w-0 flex-1 truncate text-sm font-semibold">{{ auth()->user()->name }}</span>
                        <span class="rounded-full border border-white/10 bg-white/5 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ auth()->user()->role === 'admin' ? 'Admin' : 'Pelanggan' }}</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="pt-1">
                        @csrf
                        <button type="submit" class="flex min-h-11 w-full items-center gap-2 rounded-xl border border-white/10 px-4 text-sm font-semibold text-slate-300 hover:border-rose-500/40 hover:bg-rose-500/10 hover:text-rose-300"><i data-lucide="log-out" class="h-4 w-4"></i>Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="flex min-h-11 items-center rounded-xl px-4 text-sm font-semibold text-slate-300 hover:bg-white/5">Masuk</a>
                    <a href="{{ route('register') }}" class="flex min-h-11 items-center justify-center rounded-xl bg-emerald-500 px-4 text-sm font-bold text-obsidian hover:bg-emerald-400">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    @if(session('sukses') || session('galat') || $errors->any())
        <div class="fixed right-4 top-20 z-[60] flex w-[calc(100%-2rem)] max-w-md flex-col gap-3 sm:right-6" aria-live="polite">
            @if(session('sukses'))
                <div data-toast class="flex items-start gap-3 rounded-2xl border border-emerald-400/25 bg-surface/95 p-4 shadow-card backdrop-blur-xl">
                    <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-500/15 text-emerald-300"><i data-lucide="circle-check" class="h-4 w-4"></i></span>
                    <p class="flex-1 text-sm leading-6 text-slate-100"><strong class="mr-1 text-emerald-300">Berhasil.</strong>{{ session('sukses') }}</p>
                    <button type="button" data-toast-close aria-label="Tutup notifikasi" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-slate-400 hover:bg-white/10 hover:text-white"><i data-lucide="x" class="h-4 w-4"></i></button>
                </div>
            @endif
            @if(session('galat'))
                <div data-toast class="flex items-start gap-3 rounded-2xl border border-rose-400/25 bg-surface/95 p-4 shadow-card backdrop-blur-xl">
                    <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-rose-500/15 text-rose-300"><i data-lucide="circle-alert" class="h-4 w-4"></i></span>
                    <p class="flex-1 text-sm leading-6 text-slate-100"><strong class="mr-1 text-rose-300">Perlu perhatian.</strong>{{ session('galat') }}</p>
                    <button type="button" data-toast-close aria-label="Tutup notifikasi" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-slate-400 hover:bg-white/10 hover:text-white"><i data-lucide="x" class="h-4 w-4"></i></button>
                </div>
            @endif
            @if($errors->any())
                <div data-toast class="flex items-start gap-3 rounded-2xl border border-rose-400/25 bg-surface/95 p-4 shadow-card backdrop-blur-xl">
                    <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-rose-500/15 text-rose-300"><i data-lucide="triangle-alert" class="h-4 w-4"></i></span>
                    <ul class="flex-1 space-y-1 text-sm leading-5 text-rose-200">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" data-toast-close aria-label="Tutup notifikasi" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-slate-400 hover:bg-white/10 hover:text-white"><i data-lucide="x" class="h-4 w-4"></i></button>
                </div>
            @endif
        </div>
    @endif

    <main class="mx-auto min-h-[calc(100vh-9rem)] max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @yield('konten')
    </main>

    <footer class="border-t border-white/10">
        <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-6 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <span class="font-display font-semibold text-slate-300">Arena<span class="text-emerald-400">Futsal</span></span>
            <span>Lapangan siap untuk permainan terbaikmu.</span>
            <span>Setiap hari, 08:00–23:00</span>
        </div>
    </footer>

    @stack('scripts')
    <script>
        const fotoLapanganFallback = @json($fotoLapanganFallback);
        document.querySelectorAll('img[data-foto-fallback]').forEach((gambar) => {
            if (!gambar.getAttribute('src')) gambar.src = fotoLapanganFallback[gambar.dataset.fotoFallback];
        });
        if (window.lucide) lucide.createIcons();
        const mobileMenuToggle = document.getElementById('menu-mobile-toggle');
        const mobileMenu = document.getElementById('menu-mobile');
        if (mobileMenuToggle && mobileMenu) {
            mobileMenuToggle.addEventListener('click', () => {
                const isOpen = mobileMenuToggle.getAttribute('aria-expanded') === 'true';
                mobileMenuToggle.setAttribute('aria-expanded', String(!isOpen));
                mobileMenu.classList.toggle('hidden', isOpen);
                mobileMenuToggle.innerHTML = isOpen ? '<i data-lucide="menu" class="h-5 w-5"></i>' : '<i data-lucide="x" class="h-5 w-5"></i>';
                if (window.lucide) lucide.createIcons();
            });
        }
        document.querySelectorAll('[data-toast]').forEach((toast) => {
            const dismiss = () => {
                toast.classList.add('toast-hide');
                window.setTimeout(() => toast.remove(), 260);
            };
            const close = toast.querySelector('[data-toast-close]');
            if (close) close.addEventListener('click', dismiss);
            window.setTimeout(dismiss, 6000);
        });
    </script>
</body>
</html>
