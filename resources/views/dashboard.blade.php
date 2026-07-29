<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        
        <!-- Hero Welcome Banner -->
        @php
            $setting = \App\Models\Setting::first();
            $appName = $setting->app_name ?? 'SIMPATIK';
            $lembaga = $setting->lembaga ?? 'Sistem Informasi Sekolah';
            $totalSiswa = \App\Models\Siswa::count();
            $totalKelas = \App\Models\Kelas::count();
            $totalMapel = \App\Models\MataPelajaran::count();
            $totalPrediksi = \App\Models\Prediksi::count();
            $globalTauladan = \App\Models\Prediksi::with('siswa.kelas', 'siswa.preprocessing')->orderBy('skor_probabilitas', 'desc')->first();
        @endphp

        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-sky-600 via-sky-700 to-indigo-800 p-6 md:p-8 text-white shadow-xl shadow-sky-900/10">
            <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
                <svg class="w-80 h-80 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                </svg>
            </div>
            
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 backdrop-blur-md border border-white/20 text-xs font-medium text-sky-100 mb-3">
                        <span class="w-2 h-2 rounded-full bg-sky-300 animate-pulse"></span>
                        {{ $lembaga }}
                    </div>
                    <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Selamat Datang di {{ $appName }}</h1>
                    <p class="mt-1 text-sky-100 text-sm md:text-base max-w-xl">
                        Sistem Prediksi Siswa Tauladan & Ranking Kelas berbasis Machine Learning Naive Bayes.
                    </p>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('prediksi.index') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white text-sky-800 hover:bg-sky-50 font-semibold text-sm shadow-md transition-all duration-200 transform hover:-translate-y-0.5">
                        <flux:icon.sparkles class="w-4 h-4 text-sky-600" />
                        <span>Mulai Prediksi</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card Siswa -->
            <div class="relative overflow-hidden rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 p-5 shadow-xs hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Total Siswa</p>
                        <h3 class="text-3xl font-extrabold mt-1 text-zinc-900 dark:text-white">{{ $totalSiswa }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-sky-50 dark:bg-sky-950/50 text-sky-600 dark:text-sky-400 flex items-center justify-center border border-sky-100 dark:border-sky-800/40">
                        <flux:icon.users class="w-6 h-6" />
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-zinc-500 dark:text-zinc-400">
                    <span class="text-sky-600 dark:text-sky-400 font-semibold flex items-center gap-1">
                        Siswa Terdaftar
                    </span>
                </div>
            </div>

            <!-- Card Kelas -->
            <div class="relative overflow-hidden rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 p-5 shadow-xs hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Total Kelas</p>
                        <h3 class="text-3xl font-extrabold mt-1 text-zinc-900 dark:text-white">{{ $totalKelas }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center border border-indigo-100 dark:border-indigo-800/40">
                        <flux:icon.academic-cap class="w-6 h-6" />
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-zinc-500 dark:text-zinc-400">
                    <span>Semua Jenjang Terdaftar</span>
                </div>
            </div>

            <!-- Card Mapel -->
            <div class="relative overflow-hidden rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 p-5 shadow-xs hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Mata Pelajaran</p>
                        <h3 class="text-3xl font-extrabold mt-1 text-zinc-900 dark:text-white">{{ $totalMapel }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-sky-50 dark:bg-sky-950/50 text-sky-600 dark:text-sky-400 flex items-center justify-center border border-sky-100 dark:border-sky-800/40">
                        <flux:icon.book-open class="w-6 h-6" />
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-zinc-500 dark:text-zinc-400">
                    <span>Kurikulum Aktif</span>
                </div>
            </div>

            <!-- Card Hasil Prediksi -->
            <div class="relative overflow-hidden rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 p-5 shadow-xs hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Siswa Diprediksi</p>
                        <h3 class="text-3xl font-extrabold mt-1 text-zinc-900 dark:text-white">{{ $totalPrediksi }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-sky-50 dark:bg-sky-950/50 text-sky-600 dark:text-sky-400 flex items-center justify-center border border-sky-100 dark:border-sky-800/40">
                        <flux:icon.sparkles class="w-6 h-6" />
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-zinc-500 dark:text-zinc-400">
                    <span>Hasil Naive Bayes</span>
                </div>
            </div>
        </div>

        <!-- Main Content Area: Spotlight Tauladan & Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Tauladan Spotlight Showcase (2 cols) -->
            <div class="lg:col-span-2 rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 p-6 flex flex-col justify-between shadow-xs">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <div class="p-2 rounded-lg bg-sky-50 dark:bg-sky-950/40 text-sky-600 border border-sky-200 dark:border-sky-800/40">
                                <flux:icon.star class="w-5 h-5 text-sky-600" />
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Siswa Tauladan Terbaru</h3>
                                <p class="text-xs text-zinc-500">Skor probabilitas tertinggi dari hasil analisis Naive Bayes</p>
                            </div>
                        </div>
                        
                        <a href="{{ route('laporan.index') }}" wire:navigate class="text-xs font-semibold text-sky-600 dark:text-sky-400 hover:underline flex items-center gap-1">
                            Lihat Laporan Lengkap
                            <flux:icon.arrow-right class="w-3.5 h-3.5" />
                        </a>
                    </div>

                    @if(!$globalTauladan)
                        <div class="flex flex-col items-center justify-center py-12 px-4 rounded-xl border border-dashed border-zinc-200 dark:border-zinc-700 bg-zinc-50/50 dark:bg-zinc-900/40 text-center">
                            <div class="w-14 h-14 rounded-full bg-sky-100 dark:bg-sky-900/40 text-sky-600 flex items-center justify-center mb-3">
                                <flux:icon.sparkles class="w-7 h-7" />
                            </div>
                            <h4 class="font-semibold text-zinc-800 dark:text-zinc-200 text-base">Belum Ada Data Prediksi</h4>
                            <p class="text-xs text-zinc-500 max-w-sm mt-1 mb-4">
                                Silakan jalankan Preprocessing dan Prediksi Naive Bayes untuk menampilkan Siswa Tauladan terpilih.
                            </p>
                            <a href="{{ route('preprocessing.index') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-sky-600 hover:bg-sky-700 text-white text-xs font-medium transition-colors">
                                Masuk ke Preprocessing
                            </a>
                        </div>
                    @else
                        <div class="relative overflow-hidden rounded-xl border border-sky-200/80 dark:border-sky-800/50 bg-gradient-to-br from-sky-50/70 via-white to-sky-100/40 dark:from-zinc-900 dark:via-zinc-800 dark:to-sky-950/30 p-6">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-2xl bg-sky-600 text-white font-extrabold text-2xl flex items-center justify-center shadow-lg shadow-sky-600/20 shrink-0">
                                        {{ strtoupper(substr($globalTauladan->siswa->nama_siswa, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-sky-600/10 text-sky-800 dark:text-sky-300 text-xs font-semibold mb-1">
                                            🏆 Champion Tauladan
                                        </div>
                                        <h2 class="text-xl font-extrabold text-zinc-900 dark:text-white">{{ $globalTauladan->siswa->nama_siswa }}</h2>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">
                                            NIS: <span class="font-semibold text-zinc-700 dark:text-zinc-300">{{ $globalTauladan->siswa->nis }}</span> &bull; 
                                            Kelas: <span class="font-semibold text-zinc-700 dark:text-zinc-300">{{ optional($globalTauladan->siswa->kelas)->nama_kelas }}</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="sm:text-right bg-white/80 dark:bg-zinc-800/80 backdrop-blur-sm p-3.5 rounded-xl border border-zinc-200/60 dark:border-zinc-700/60 shrink-0 w-full sm:w-auto">
                                    <p class="text-xs text-zinc-500 font-medium">Skor Probabilitas Naive Bayes</p>
                                    <p class="text-2xl font-black text-sky-600 dark:text-sky-400 mt-0.5">
                                        {{ number_format($globalTauladan->skor_probabilitas * 100, 2) }}%
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Navigation & Workflow Card (1 col) -->
            <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 p-6 flex flex-col justify-between shadow-xs">
                <div>
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-1">Menu Akses Cepat</h3>
                    <p class="text-xs text-zinc-500 mb-4">Pintas cepat navigasi ke modul aplikasi</p>

                    <div class="flex flex-col gap-2.5">
                        <a href="{{ route('siswa.index') }}" wire:navigate class="flex items-center justify-between p-3 rounded-xl border border-zinc-100 dark:border-zinc-700/60 hover:border-sky-300 dark:hover:border-sky-700 bg-zinc-50/50 dark:bg-zinc-900/40 hover:bg-sky-50/50 dark:hover:bg-sky-950/30 transition-all group">
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-lg bg-sky-100 dark:bg-sky-900/40 text-sky-600 group-hover:scale-105 transition-transform">
                                    <flux:icon.users class="w-4 h-4" />
                                </div>
                                <span class="text-xs font-semibold text-zinc-800 dark:text-zinc-200">Kelola Siswa</span>
                            </div>
                            <flux:icon.arrow-right class="w-4 h-4 text-zinc-400 group-hover:text-sky-600 transition-colors" />
                        </a>

                        <a href="{{ route('nilai.index') }}" wire:navigate class="flex items-center justify-between p-3 rounded-xl border border-zinc-100 dark:border-zinc-700/60 hover:border-sky-300 dark:hover:border-sky-700 bg-zinc-50/50 dark:bg-zinc-900/40 hover:bg-sky-50/50 dark:hover:bg-sky-950/30 transition-all group">
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-lg bg-sky-100 dark:bg-sky-900/40 text-sky-600 group-hover:scale-105 transition-transform">
                                    <flux:icon.clipboard-document-check class="w-4 h-4" />
                                </div>
                                <span class="text-xs font-semibold text-zinc-800 dark:text-zinc-200">Master Nilai</span>
                            </div>
                            <flux:icon.arrow-right class="w-4 h-4 text-zinc-400 group-hover:text-sky-600 transition-colors" />
                        </a>

                        <a href="{{ route('preprocessing.index') }}" wire:navigate class="flex items-center justify-between p-3 rounded-xl border border-zinc-100 dark:border-zinc-700/60 hover:border-sky-300 dark:hover:border-sky-700 bg-zinc-50/50 dark:bg-zinc-900/40 hover:bg-sky-50/50 dark:hover:bg-sky-950/30 transition-all group">
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-lg bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 group-hover:scale-105 transition-transform">
                                    <flux:icon.arrow-path class="w-4 h-4" />
                                </div>
                                <span class="text-xs font-semibold text-zinc-800 dark:text-zinc-200">Preprocessing Data</span>
                            </div>
                            <flux:icon.arrow-right class="w-4 h-4 text-zinc-400 group-hover:text-indigo-600 transition-colors" />
                        </a>

                        <a href="{{ route('laporan.index') }}" wire:navigate class="flex items-center justify-between p-3 rounded-xl border border-zinc-100 dark:border-zinc-700/60 hover:border-sky-300 dark:hover:border-sky-700 bg-zinc-50/50 dark:bg-zinc-900/40 hover:bg-sky-50/50 dark:hover:bg-sky-950/30 transition-all group">
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-lg bg-sky-100 dark:bg-sky-900/40 text-sky-600 group-hover:scale-105 transition-transform">
                                    <flux:icon.document-chart-bar class="w-4 h-4" />
                                </div>
                                <span class="text-xs font-semibold text-zinc-800 dark:text-zinc-200">Laporan Ranking</span>
                            </div>
                            <flux:icon.arrow-right class="w-4 h-4 text-zinc-400 group-hover:text-sky-600 transition-colors" />
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-layouts::app>
