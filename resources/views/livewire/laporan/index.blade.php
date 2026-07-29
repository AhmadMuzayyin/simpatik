<div>
    <!-- Header Page & Cetak Button -->
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 no-print">
        <div>
            <flux:heading size="xl">Laporan Siswa Tauladan & Ranking Kelas</flux:heading>
            <flux:subheading>Menampilkan 1 Siswa Tauladan Sekolah dan Peringkat Ranking 1, 2, 3 dari tiap kelas (di luar Siswa Tauladan).</flux:subheading>
        </div>
        <div class="flex items-center gap-2">
            <flux:button variant="primary" icon="printer" onclick="window.print()">Cetak Laporan</flux:button>
        </div>
    </div>

    <!-- Printable Header (Only visible when printing) -->
    @php
        $setting = \App\Models\Setting::first();
        $appName = $setting->app_name ?? 'SIMPATIK';
        $lembaga = $setting->lembaga ?? 'Sistem Informasi Sekolah';
    @endphp
    <div class="hidden print:block mb-8 text-center border-b-2 border-zinc-800 pb-4">
        <h1 class="text-2xl font-black uppercase tracking-wider">{{ $lembaga }}</h1>
        <h2 class="text-lg font-bold text-zinc-700">LAPORAN SISWA TAULADAN & RANKING KELAS</h2>
        <p class="text-xs text-zinc-500 mt-1">Dicetak dari {{ $appName }} pada tanggal {{ date('d F Y') }}</p>
    </div>

    <div class="space-y-8">
        
        <!-- 1. Banner Tauladan Utama Sekolah (Siswa Terbaik #1) -->
        @if($tauladanUtama)
            <div class="relative overflow-hidden rounded-2xl border-2 border-sky-200 dark:border-sky-800/60 bg-gradient-to-r from-sky-50/90 via-white to-indigo-50/50 dark:from-sky-950/40 dark:via-zinc-800 dark:to-indigo-950/30 p-6 md:p-8 shadow-lg">
                <div class="absolute -right-8 -bottom-8 opacity-10 pointer-events-none">
                    <flux:icon.star class="w-64 h-64 text-sky-600" />
                </div>

                <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div class="w-20 h-20 rounded-2xl bg-sky-600 text-white font-black text-3xl flex items-center justify-center shadow-xl shadow-sky-600/30 shrink-0">
                            🏆
                        </div>
                        <div>
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-sky-600 text-white font-bold text-xs shadow-xs mb-2">
                                🌟 SISWA TAULADAN UTAMA (NILAI TERTINGGI SEKOLAH)
                            </div>
                            <h2 class="text-2xl md:text-3xl font-extrabold text-zinc-900 dark:text-white">
                                {{ optional($tauladanUtama->siswa)->nama_siswa }}
                            </h2>
                            <p class="text-sm text-zinc-600 dark:text-zinc-300 mt-1">
                                NIS: <span class="font-bold text-zinc-800 dark:text-zinc-100">{{ optional($tauladanUtama->siswa)->nis }}</span> &bull; 
                                Kelas: <span class="font-bold text-zinc-800 dark:text-zinc-100">{{ optional(optional($tauladanUtama->siswa)->kelas)->nama_kelas }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-zinc-800/90 p-4 rounded-2xl border border-sky-200 dark:border-sky-800/50 shadow-md w-full md:w-auto text-left md:text-right shrink-0">
                        <p class="text-xs font-semibold uppercase tracking-wider text-sky-700 dark:text-sky-400">Skor Probabilitas Naive Bayes</p>
                        <p class="text-3xl font-black text-sky-600 dark:text-sky-400 mt-0.5">
                            {{ number_format($tauladanUtama->skor_probabilitas * 100, 2) }}%
                        </p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">Siswa Tauladan Utama Sekolah</p>
                    </div>
                </div>
            </div>
        @else
            <div class="p-8 text-center bg-white dark:bg-zinc-800 rounded-2xl border border-zinc-200 dark:border-zinc-700 text-zinc-500">
                Belum ada data Tauladan. Silakan jalankan menu <strong>Prediksi Naive Bayes</strong>.
            </div>
        @endif

        <!-- 2. Tabs Kelas & Tabel Peringkat Ranking per Kelas -->
        <div>
            <div class="mb-4 no-print">
                <h3 class="text-xl font-extrabold text-zinc-900 dark:text-white">Daftar Peringkat Ranking per Kelas</h3>
                <p class="text-xs text-zinc-500">Pilih kelas di bawah untuk melihat rincian peringkat 1, 2, 3 di kelas tersebut</p>
            </div>

            <!-- Tabs Kelas -->
            <div class="flex flex-wrap gap-2 mb-6 no-print">
                @foreach($kelases as $kelas)
                    <flux:button wire:click="$set('activeTab', 'kelas_{{ $kelas->id }}')" variant="{{ $activeTab === 'kelas_' . $kelas->id ? 'primary' : 'filled' }}">
                        {{ $kelas->nama_kelas }}
                    </flux:button>
                @endforeach
            </div>

            @foreach($dataPerKelas as $kelasId => $data)
                <div class="{{ $activeTab === 'kelas_' . $kelasId ? 'block' : 'hidden print:block mb-8' }}">
                    <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 p-5 shadow-xs">
                        <div class="flex items-center justify-between mb-4 border-b border-zinc-100 dark:border-zinc-700/60 pb-3">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-sky-100 dark:bg-sky-950 text-sky-600 font-bold flex items-center justify-center text-xs">
                                    🏫
                                </div>
                                <h4 class="text-lg font-bold text-zinc-900 dark:text-white">{{ $data['kelas']->nama_kelas }}</h4>
                            </div>
                        </div>

                        <flux:table>
                            <flux:table.columns>
                                <flux:table.column>Ranking Kelas</flux:table.column>
                                <flux:table.column>NIS</flux:table.column>
                                <flux:table.column>Nama Siswa</flux:table.column>
                                <flux:table.column>Rata-rata Nilai</flux:table.column>
                                <flux:table.column>Skor Probabilitas Naive Bayes</flux:table.column>
                            </flux:table.columns>

                            <flux:table.rows>
                                @forelse($data['topRankings'] as $rIndex => $row)
                                    @php
                                        $p = optional($row->siswa)->preprocessing;
                                        $avgAll = $p ? ($p->rata_rata_mapel + $p->rata_rata_harian) / 2 : 0;
                                        $rankK = $rIndex + 1;
                                    @endphp
                                    <flux:table.row class="{{ $rankK == 1 ? 'bg-sky-50/40 dark:bg-sky-950/20 font-medium' : '' }}">
                                        <flux:table.cell>
                                            @if($rankK == 1)
                                                <flux:badge size="sm" variant="solid" class="bg-sky-600 text-white font-bold">🥇 Rank #1</flux:badge>
                                            @elseif($rankK == 2)
                                                <flux:badge size="sm" variant="solid" class="bg-indigo-600 text-white font-bold">🥈 Rank #2</flux:badge>
                                            @elseif($rankK == 3)
                                                <flux:badge size="sm" variant="solid" class="bg-slate-600 text-white font-bold">🥉 Rank #3</flux:badge>
                                            @else
                                                <flux:badge size="sm" variant="subtle">#{{ $rankK }}</flux:badge>
                                            @endif
                                        </flux:table.cell>
                                        <flux:table.cell>{{ optional($row->siswa)->nis }}</flux:table.cell>
                                        <flux:table.cell class="font-semibold text-zinc-900 dark:text-white">
                                            {{ optional($row->siswa)->nama_siswa }}
                                        </flux:table.cell>
                                        <flux:table.cell class="font-bold">{{ number_format($avgAll, 2) }}</flux:table.cell>
                                        <flux:table.cell class="font-bold text-sky-600 dark:text-sky-400">
                                            {{ number_format($row->skor_probabilitas * 100, 2) }}%
                                        </flux:table.cell>
                                    </flux:table.row>
                                @empty
                                    <flux:table.row>
                                        <flux:table.cell colspan="5" class="text-center py-4 text-zinc-500">
                                            Belum ada data ranking untuk kelas ini.
                                        </flux:table.cell>
                                    </flux:table.row>
                                @endforelse
                            </flux:table.rows>
                        </flux:table>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>
