<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <flux:heading size="xl">Preprocessing Data</flux:heading>
            <flux:subheading>Agregasi rata-rata nilai siswa dan kategorisasi dinamis untuk algoritma Naive Bayes.</flux:subheading>
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
            <flux:table.column>Rata Harian (Gabungan)</flux:table.column>
            <flux:table.column>Rincian Kategorisasi Harian Dinamis</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach($data as $row)
                <flux:table.row>
                    <flux:table.cell class="font-semibold text-zinc-900 dark:text-white">{{ optional($row->siswa)->nama_siswa }}</flux:table.cell>
                    <flux:table.cell>{{ optional(optional($row->siswa)->kelas)->nama_kelas }}</flux:table.cell>
                    <flux:table.cell>
                        <div class="flex flex-col">
                            <span class="font-bold">{{ number_format($row->rata_rata_mapel, 2) }}</span>
                            <flux:badge size="sm" variant="{{ $row->kategori_mapel == 'Tinggi' ? 'success' : ($row->kategori_mapel == 'Sedang' ? 'warning' : 'danger') }}">
                                {{ $row->kategori_mapel }}
                            </flux:badge>
                        </div>
                    </flux:table.cell>
                    <flux:table.cell>
                        <div class="flex flex-col">
                            <span class="font-bold text-sky-600 dark:text-sky-400">{{ number_format($row->rata_rata_harian, 2) }}</span>
                            <flux:badge size="sm" variant="{{ $row->kategori_harian == 'Tinggi' ? 'success' : ($row->kategori_harian == 'Sedang' ? 'warning' : 'danger') }}">
                                {{ $row->kategori_harian }}
                            </flux:badge>
                        </div>
                    </flux:table.cell>
                    <flux:table.cell>
                        <div class="flex flex-wrap gap-1.5">
                            @if(is_array($row->detail_harian))
                                @foreach($row->detail_harian as $namaCat => $item)
                                    <flux:badge size="sm" variant="subtle">
                                        {{ $namaCat }}: <strong>{{ number_format($item['nilai'], 1) }}</strong> ({{ $item['kategori'] }})
                                    </flux:badge>
                                @endforeach
                            @else
                                <span class="text-xs text-zinc-400">-</span>
                            @endif
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
