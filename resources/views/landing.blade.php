<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark:bg-zinc-900">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen flex flex-col text-white">

        @php
            $setting = \App\Models\Setting::first();
            $appName = $setting && $setting->app_name ? $setting->app_name : config('app.name', 'SIMPATIK');
            $lembaga = $setting && $setting->lembaga ? $setting->lembaga : 'Sistem Informasi Sekolah';
        @endphp

        <!-- Full-page Background Image -->
        <div
            class="fixed inset-0 bg-cover bg-center"
            style="background-image:url('https://images.unsplash.com/photo-1592066575517-58df903152f2?auto=format&fit=crop&w=1920&q=80')"
        ></div>
        <div class="fixed inset-0 bg-zinc-900/70"></div>

        <div class="relative z-10 flex flex-col min-h-screen">

            <!-- Navbar -->
            <header class="bg-transparent">
                <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
                    <x-app-logo />

                    <nav class="flex items-center gap-2">
                        @auth
                            <flux:button href="{{ route('dashboard') }}" variant="primary" wire:navigate>Dashboard</flux:button>
                        @else
                            <flux:button href="{{ route('login') }}" variant="primary" wire:navigate>Masuk</flux:button>
                        @endauth
                    </nav>
                </div>
            </header>

            <!-- Hero Section -->
            <main class="flex-1 flex items-center">
                <div class="max-w-6xl mx-auto px-6 py-24 md:py-32 text-center">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 backdrop-blur text-sky-300 font-semibold text-xs mb-6 border border-white/20">
                        🎓 {{ $lembaga }}
                    </div>

                    <h1 class="text-4xl md:text-6xl font-black tracking-tight text-white">
                        {{ $appName }}
                    </h1>

                    <p class="mt-5 text-lg md:text-xl text-zinc-200 max-w-2xl mx-auto">
                        Sistem informasi pengelolaan nilai dan penentuan siswa tauladan berbasis Naive Bayes, cepat dan mudah digunakan.
                    </p>

                    <div class="mt-10 flex items-center justify-center gap-3">
                        @auth
                            <flux:button href="{{ route('dashboard') }}" variant="primary" icon="arrow-right" class="!h-12 !px-6 !text-base" wire:navigate>
                                Buka Dashboard
                            </flux:button>
                        @else
                            <flux:button href="{{ route('login') }}" variant="primary" icon="arrow-right" class="!h-12 !px-6 !text-base" wire:navigate>
                                Masuk ke Sistem
                            </flux:button>
                        @endauth
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-transparent">
                <div class="max-w-6xl mx-auto px-6 py-6 text-center text-sm text-zinc-300">
                    &copy; {{ date('Y') }} {{ $appName }}. Seluruh hak cipta dilindungi.
                </div>
            </footer>

        </div>

        @fluxScripts
    </body>
</html>
