<div>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <flux:heading size="xl">Laporan Tauladan & Ranking</flux:heading>
            <flux:subheading>Menampilkan Siswa Tauladan terbaik dan 10 Ranking Teratas per kelas.</flux:subheading>
        </div>
        <flux:button variant="primary" icon="printer" onclick="window.print()">Cetak</flux:button>
    </div>

    @if($kelases->isEmpty())
        <flux:card class="text-center py-8">Belum ada data kelas.</flux:card>
    @else
        @if($globalTauladan)
            <div class="mb-8 p-6 bg-sky-50 dark:bg-zinc-800 border border-sky-200 dark:border-zinc-700 rounded-xl flex items-center gap-6">
                <div class="w-20 h-20 bg-sky-500 rounded-full flex items-center justify-center shrink-0">
                    <flux:icon.star class="w-10 h-10 text-white" />
                </div>
                <div>
                    <flux:heading size="xl" class="text-zinc-900 dark:text-white">Siswa Tauladan Tahun Ini</flux:heading>
                    <flux:text class="text-lg font-semibold text-zinc-800 dark:text-zinc-200">{{ $globalTauladan->siswa->nama_siswa }} (Kelas {{ optional($globalTauladan->siswa->kelas)->nama_kelas }})</flux:text>
                    <flux:text class="text-zinc-600 dark:text-zinc-400">NIS: {{ $globalTauladan->siswa->nis }}</flux:text>
                    <flux:text class="text-zinc-600 dark:text-zinc-400">Skor Probabilitas Naive Bayes: {{ number_format($globalTauladan->skor_probabilitas * 100, 2) }}%</flux:text>
                </div>
            </div>
        @endif

        <div class="flex flex-wrap gap-2 mb-6">
            @foreach($kelases as $kelas)
                <flux:button wire:click="$set('activeTab', 'kelas_{{ $kelas->id }}')" variant="{{ $activeTab === 'kelas_' . $kelas->id ? 'primary' : 'filled' }}">{{ $kelas->nama_kelas }}</flux:button>
            @endforeach
        </div>

        @foreach($dataPerKelas as $kelasId => $data)
            @if($activeTab === 'kelas_' . $kelasId)
                <div>
                    <flux:heading size="lg" class="mb-4">Top 10 Ranking Kelas {{ $data['kelas']->nama_kelas }}</flux:heading>
                    
                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column>Rank</flux:table.column>
                            <flux:table.column>NIS</flux:table.column>
                            <flux:table.column>Nama Siswa</flux:table.column>
                            <flux:table.column>Rata-rata Nilai</flux:table.column>
                        </flux:table.columns>

                        <flux:table.rows>
                            @forelse($data['top10'] as $row)
                                <flux:table.row>
                                    <flux:table.cell>
                                        <flux:badge size="sm" variant="{{ $row->ranking == 1 ? 'success' : 'solid' }}">#{{ $row->ranking }}</flux:badge>
                                    </flux:table.cell>
                                    <flux:table.cell>{{ optional($row->siswa)->nis }}</flux:table.cell>
                                    <flux:table.cell>{{ optional($row->siswa)->nama_siswa }}</flux:table.cell>
                                    <flux:table.cell>
                                        @php
                                            $p = optional($row->siswa)->preprocessing;
                                            $avgAll = $p ? ($p->rata_rata_mapel + $p->rata_rata_pengetahuan + $p->rata_rata_keterampilan + $p->rata_rata_sikap) / 4 : 0;
                                        @endphp
                                        {{ number_format($avgAll, 2) }}
                                    </flux:table.cell>
                                </flux:table.row>
                            @empty
                                <flux:table.row>
                                    <flux:table.cell colspan="4" class="text-center py-4">Tidak ada data ranking. Silakan lakukan prediksi terlebih dahulu.</flux:table.cell>
                                </flux:table.row>
                            @endforelse
                        </flux:table.rows>
                    </flux:table>
                </div>
            @endif
        @endforeach
    @endif
</div>
