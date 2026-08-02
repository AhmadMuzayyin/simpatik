<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <flux:heading size="xl">Hasil Prediksi Naive Bayes</flux:heading>
            <flux:subheading>Daftar hasil analisis prediksi skor probabilitas Naive Bayes dan penetapan peringkat.</flux:subheading>
        </div>
        <div>
            <flux:button wire:click="prediksiSekarang" variant="primary" icon="sparkles">Prediksi Sekarang</flux:button>
        </div>
    </div>

    @if (session()->has('message'))
        <flux:toast variant="success" text="{{ session('message') }}" />
    @endif

    <!-- Simple Clean Table for Prediksi Page -->
    <flux:table>
        <flux:table.columns>
            <flux:table.column>NIS</flux:table.column>
            <flux:table.column>Nama Siswa</flux:table.column>
            <flux:table.column>Kelas</flux:table.column>
            <flux:table.column>Rata-rata Nilai</flux:table.column>
            <flux:table.column>Skor Probabilitas Naive Bayes</flux:table.column>
            <flux:table.column>Status / Ranking</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse($prediksiList as $row)
                @php
                    $p = optional($row->siswa)->preprocessing;
                    $avgAll = $p ? ($p->rata_rata_mapel + $p->rata_rata_harian) / 2 : 0;
                @endphp
                <flux:table.row class="{{ $row->hasil_prediksi == 'Tauladan' ? 'bg-sky-50/60 dark:bg-sky-950/20 font-medium' : '' }}">
                    <flux:table.cell>{{ optional($row->siswa)->nis }}</flux:table.cell>
                    <flux:table.cell class="font-semibold text-zinc-900 dark:text-white">
                        {{ optional($row->siswa)->nama_siswa }}
                    </flux:table.cell>
                    <flux:table.cell>{{ optional(optional($row->siswa)->kelas)->nama_kelas }}</flux:table.cell>
                    <flux:table.cell class="font-bold">{{ number_format($avgAll, 2) }}</flux:table.cell>
                    <flux:table.cell class="font-bold text-sky-600 dark:text-sky-400">
                        {{ number_format($row->skor_probabilitas * 100, 2) }}%
                    </flux:table.cell>
                    <flux:table.cell>
                        @if($row->hasil_prediksi == 'Tauladan')
                            <flux:badge size="sm" variant="success" class="bg-sky-600 text-white font-bold">🏆 Siswa Tauladan</flux:badge>
                        @elseif($row->ranking == 1)
                            <flux:badge size="sm" variant="solid" class="bg-sky-600 text-white font-bold">🥇 Rank 1</flux:badge>
                        @elseif($row->ranking == 2)
                            <flux:badge size="sm" variant="solid" class="bg-indigo-600 text-white font-bold">🥈 Rank 2</flux:badge>
                        @elseif($row->ranking == 3)
                            <flux:badge size="sm" variant="solid" class="bg-slate-600 text-white font-bold">🥉 Rank 3</flux:badge>
                        @else
                            <flux:badge size="sm" variant="subtle">{{ $row->ranking }}</flux:badge>
                        @endif
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="6" class="text-center py-6 text-zinc-500">
                        Belum ada data hasil prediksi. Klik tombol <strong>Prediksi Sekarang</strong> di atas.
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    <div class="mt-4">
        {{ $prediksiList->links() }}
    </div>
</div>
