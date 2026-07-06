<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6 flex flex-col items-center justify-center">
                <flux:heading size="xl" class="text-zinc-500">Total Siswa</flux:heading>
                <div class="text-4xl font-bold mt-2 text-zinc-800 dark:text-zinc-100">{{ \App\Models\Siswa::count() }}</div>
            </div>
            <div class="relative overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6 flex flex-col items-center justify-center">
                <flux:heading size="xl" class="text-zinc-500">Total Kelas</flux:heading>
                <div class="text-4xl font-bold mt-2 text-zinc-800 dark:text-zinc-100">{{ \App\Models\Kelas::count() }}</div>
            </div>
            <div class="relative overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6 flex flex-col items-center justify-center">
                <flux:heading size="xl" class="text-zinc-500">Total Mapel</flux:heading>
                <div class="text-4xl font-bold mt-2 text-zinc-800 dark:text-zinc-100">{{ \App\Models\MataPelajaran::count() }}</div>
            </div>
        </div>
        
        <div class="relative h-full flex-1 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6">
            <flux:heading size="xl" class="mb-4">Daftar Siswa Tauladan</flux:heading>
            @php
                $globalTauladan = \App\Models\Prediksi::with('siswa.kelas')->orderBy('skor_probabilitas', 'desc')->first();
            @endphp
            
            @if(!$globalTauladan)
                <div class="flex items-center justify-center h-48 text-zinc-500">
                    Belum ada data prediksi.
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="p-4 bg-sky-50 dark:bg-zinc-800 border border-sky-200 dark:border-zinc-700 rounded-lg flex items-center gap-4">
                        <div class="w-10 h-10 bg-sky-500 rounded-full flex items-center justify-center shrink-0">
                            <flux:icon.star class="w-6 h-6 text-white" />
                        </div>
                        <div>
                            <flux:heading size="lg" class="text-zinc-900 dark:text-white">{{ $globalTauladan->siswa->nama_siswa }}</flux:heading>
                            <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">Kelas: {{ optional($globalTauladan->siswa->kelas)->nama_kelas }}</flux:text>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layouts::app>
