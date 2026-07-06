<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <flux:heading size="xl">Prediksi Naive Bayes</flux:heading>
            <flux:subheading>Lakukan prediksi Tauladan dan Ranking menggunakan metode Naive Bayes.</flux:subheading>
        </div>
        <div>
            <flux:button wire:click="prediksiSekarang" variant="primary" icon="sparkles">Prediksi Sekarang</flux:button>
        </div>
    </div>

    @if (session()->has('message'))
        <flux:toast variant="success" text="{{ session('message') }}" />
    @endif

    <flux:table>
        <flux:table.columns>
            <flux:table.column>Ranking Kelas</flux:table.column>
            <flux:table.column>Nama Siswa</flux:table.column>
            <flux:table.column>Kelas</flux:table.column>
            <flux:table.column>Rata-rata</flux:table.column>
            <flux:table.column>Skor Probabilitas</flux:table.column>
            <flux:table.column>Hasil Prediksi</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach($data as $row)
                <flux:table.row>
                    <flux:table.cell>
                        <flux:badge size="sm" variant="{{ $row->ranking == 1 ? 'success' : 'solid' }}">#{{ $row->ranking }}</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>{{ optional($row->siswa)->nama_siswa }}</flux:table.cell>
                    <flux:table.cell>{{ optional(optional($row->siswa)->kelas)->nama_kelas }}</flux:table.cell>
                    <flux:table.cell>
                        @php
                            $p = optional($row->siswa)->preprocessing;
                            $avgAll = $p ? ($p->rata_rata_mapel + $p->rata_rata_pengetahuan + $p->rata_rata_keterampilan + $p->rata_rata_sikap) / 4 : 0;
                        @endphp
                        {{ number_format($avgAll, 2) }}
                    </flux:table.cell>
                    <flux:table.cell>{{ number_format($row->skor_probabilitas * 100, 2) }}%</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge size="sm" variant="{{ $row->hasil_prediksi == 'Tauladan' ? 'success' : 'warning' }}">
                            {{ $row->hasil_prediksi }}
                        </flux:badge>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
    
    <div class="mt-4">
        {{ $data->links() }}
    </div>
</div>
