<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <flux:heading size="xl">Preprocessing Data</flux:heading>
            <flux:subheading>Agregasi rata-rata nilai siswa dan kategorisasi untuk algoritma Naive Bayes.</flux:subheading>
        </div>
        <div>
            <flux:button wire:click="proses" variant="primary" icon="arrow-path">Proses Preprocessing</flux:button>
        </div>
    </div>

    @if (session()->has('message'))
        <flux:toast variant="success" text="{{ session('message') }}" />
    @endif

    <flux:table>
        <flux:table.columns>
            <flux:table.column>Nama Siswa</flux:table.column>
            <flux:table.column>Kelas</flux:table.column>
            <flux:table.column>Rata Mapel</flux:table.column>
            <flux:table.column>Rata Pengetahuan</flux:table.column>
            <flux:table.column>Rata Keterampilan</flux:table.column>
            <flux:table.column>Rata Sikap</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach($data as $row)
                <flux:table.row>
                    <flux:table.cell>{{ optional($row->siswa)->nama_siswa }}</flux:table.cell>
                    <flux:table.cell>{{ optional(optional($row->siswa)->kelas)->nama_kelas }}</flux:table.cell>
                    <flux:table.cell>
                        <div class="flex flex-col">
                            <span>{{ number_format($row->rata_rata_mapel, 2) }}</span>
                            <span class="text-xs text-gray-500">{{ $row->kategori_mapel }}</span>
                        </div>
                    </flux:table.cell>
                    <flux:table.cell>
                        <div class="flex flex-col">
                            <span>{{ number_format($row->rata_rata_pengetahuan, 2) }}</span>
                            <span class="text-xs text-gray-500">{{ $row->kategori_pengetahuan }}</span>
                        </div>
                    </flux:table.cell>
                    <flux:table.cell>
                        <div class="flex flex-col">
                            <span>{{ number_format($row->rata_rata_keterampilan, 2) }}</span>
                            <span class="text-xs text-gray-500">{{ $row->kategori_keterampilan }}</span>
                        </div>
                    </flux:table.cell>
                    <flux:table.cell>
                        <div class="flex flex-col">
                            <span>{{ number_format($row->rata_rata_sikap, 2) }}</span>
                            <span class="text-xs text-gray-500">{{ $row->kategori_sikap }}</span>
                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
    
    <div class="mt-4">
        {{ $data->links() }}
    </div>
</div>
