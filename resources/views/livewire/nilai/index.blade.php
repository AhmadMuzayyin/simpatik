<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <flux:heading size="xl">Master Nilai</flux:heading>
            <flux:subheading>Kelola data nilai mapel dan nilai harian siswa.</flux:subheading>
        </div>
        <div class="flex gap-2">
            <flux:modal.trigger name="importModal">
                <flux:button variant="filled" icon="arrow-down-tray">Import Excel</flux:button>
            </flux:modal.trigger>
        </div>
    </div>

    @if (session()->has('message'))
        <flux:toast variant="success" text="{{ session('message') }}" />
    @endif

    <div class="mb-4 flex gap-4 max-w-sm">
        <flux:select wire:model.live="filter_kelas" placeholder="Semua Kelas">
            @foreach($kelases as $kelas)
                <flux:select.option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</flux:select.option>
            @endforeach
        </flux:select>
    </div>

    <div class="flex gap-2 mb-6">
        <flux:button wire:click="$set('activeTab', 'mapel')" variant="{{ $activeTab === 'mapel' ? 'primary' : 'filled' }}">Nilai Mapel</flux:button>
        <flux:button wire:click="$set('activeTab', 'harian')" variant="{{ $activeTab === 'harian' ? 'primary' : 'filled' }}">Nilai Harian (Karakter)</flux:button>
    </div>

    @if($activeTab === 'mapel')
        <div>
            <div class="flex justify-end mb-4">
                <flux:button wire:click="createMapel" variant="primary" icon="plus" size="sm">Tambah Nilai Mapel</flux:button>
            </div>
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Nama Siswa</flux:table.column>
                    <flux:table.column>Kelas</flux:table.column>
                    <flux:table.column>Jumlah Mata Pelajaran</flux:table.column>
                    <flux:table.column>Total Akumulasi Nilai</flux:table.column>
                    <flux:table.column>Rata-rata Nilai</flux:table.column>
                    <flux:table.column>Aksi</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach($mapelSiswas as $s)
                        <flux:table.row>
                            <flux:table.cell>{{ $s->nama_siswa }}</flux:table.cell>
                            <flux:table.cell>{{ optional($s->kelas)->nama_kelas }}</flux:table.cell>
                            <flux:table.cell>{{ $s->nilai_mapels_count }} Mapel</flux:table.cell>
                            <flux:table.cell>{{ number_format($s->nilai_mapels_sum_nilai, 2) }}</flux:table.cell>
                            <flux:table.cell>{{ number_format($s->nilai_mapels_avg_nilai, 2) }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:button size="sm" variant="ghost" icon="eye" wire:click="showDetail({{ $s->id }})">Detail</flux:button>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
            <div class="mt-4">{{ $mapelSiswas->links() }}</div>
        </div>
    @elseif($activeTab === 'harian')
        <div>
            <div class="flex justify-end mb-4">
                <flux:button wire:click="createHarian" variant="primary" icon="plus" size="sm">Tambah Nilai Harian</flux:button>
            </div>
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Nama Siswa</flux:table.column>
                    <flux:table.column>Kelas</flux:table.column>
                    <flux:table.column>Pengetahuan</flux:table.column>
                    <flux:table.column>Keterampilan</flux:table.column>
                    <flux:table.column>Sikap</flux:table.column>
                    <flux:table.column>Aksi</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach($nilaiHarians as $nh)
                        <flux:table.row>
                            <flux:table.cell>{{ optional($nh->siswa)->nama_siswa }}</flux:table.cell>
                            <flux:table.cell>{{ optional(optional($nh->siswa)->kelas)->nama_kelas }}</flux:table.cell>
                            <flux:table.cell>{{ $nh->pengetahuan }}</flux:table.cell>
                            <flux:table.cell>{{ $nh->keterampilan }}</flux:table.cell>
                            <flux:table.cell>{{ $nh->sikap }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="editHarian({{ $nh->id }})">Edit</flux:button>
                                <flux:button size="sm" variant="ghost" icon="trash" wire:click="deleteHarian({{ $nh->id }})" class="text-red-500 hover:text-red-700">Hapus</flux:button>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
            <div class="mt-4">{{ $nilaiHarians->links() }}</div>
        </div>
    @endif

    <!-- Modal Nilai Mapel -->
    <flux:modal wire:model="showMapelModal" class="max-w-xl">
        <form wire:submit.prevent="saveMapel">
            <div class="space-y-4">
                <flux:heading size="lg">{{ $mapel_edit_id ? 'Edit' : 'Tambah' }} Nilai Mapel</flux:heading>
                
                <div class="grid grid-cols-2 gap-4">
                    <flux:select wire:model="mapel_siswa_id" label="Siswa" placeholder="Pilih Siswa">
                        @foreach($siswas as $siswa)
                            <flux:select.option value="{{ $siswa->id }}">{{ $siswa->nama_siswa }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    
                    <flux:select wire:model="mapel_id" label="Mata Pelajaran" placeholder="Pilih Mapel">
                        @foreach($mapels as $mapel)
                            <flux:select.option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>
                
                <flux:input type="number" step="0.01" wire:model="nilai" label="Nilai" />
                
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:button wire:click="$set('showMapelModal', false)">Batal</flux:button>
                    <flux:button type="submit" variant="primary">Simpan</flux:button>
                </div>
            </div>
        </form>
    </flux:modal>

    <!-- Modal Nilai Harian -->
    <flux:modal wire:model="showHarianModal" class="max-w-xl">
        <form wire:submit.prevent="saveHarian">
            <div class="space-y-4">
                <flux:heading size="lg">{{ $harian_edit_id ? 'Edit' : 'Tambah' }} Nilai Harian</flux:heading>
                
                <flux:select wire:model="harian_siswa_id" label="Siswa" placeholder="Pilih Siswa">
                    @foreach($siswas as $siswa)
                        <flux:select.option value="{{ $siswa->id }}">{{ $siswa->nama_siswa }}</flux:select.option>
                    @endforeach
                </flux:select>
                
                <div class="grid grid-cols-3 gap-4">
                    <flux:input type="number" step="0.01" wire:model="pengetahuan" label="Pengetahuan" />
                    <flux:input type="number" step="0.01" wire:model="keterampilan" label="Keterampilan" />
                    <flux:input type="number" step="0.01" wire:model="sikap" label="Sikap" />
                </div>
                
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:button wire:click="$set('showHarianModal', false)">Batal</flux:button>
                    <flux:button type="submit" variant="primary">Simpan</flux:button>
                </div>
            </div>
        </form>
    </flux:modal>
    
    <!-- Modal Detail Nilai Mapel Siswa -->
    <flux:modal wire:model="showDetailModal" class="max-w-3xl">
        <div class="space-y-4">
            <flux:heading size="lg">Detail Nilai Mata Pelajaran</flux:heading>
            @if($detailSiswa)
                <div class="mb-4">
                    <flux:text class="font-semibold">{{ $detailSiswa->nama_siswa }}</flux:text>
                    <flux:text>Kelas: {{ optional($detailSiswa->kelas)->nama_kelas }}</flux:text>
                </div>
                
                <div class="flex justify-end mb-4">
                    <flux:button wire:click="createMapel" variant="primary" icon="plus" size="sm">Tambah Nilai Mapel</flux:button>
                </div>

                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Mata Pelajaran</flux:table.column>
                        <flux:table.column>Nilai</flux:table.column>
                        <flux:table.column>Aksi</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @foreach($detailSiswa->nilaiMapels as $nm)
                            <flux:table.row>
                                <flux:table.cell>{{ optional($nm->mataPelajaran)->nama_mapel }}</flux:table.cell>
                                <flux:table.cell>{{ $nm->nilai }}</flux:table.cell>
                                <flux:table.cell>
                                    <flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="editMapel({{ $nm->id }})">Edit</flux:button>
                                    <flux:button size="sm" variant="ghost" icon="trash" wire:click="deleteMapel({{ $nm->id }})" class="text-red-500 hover:text-red-700">Hapus</flux:button>
                                </flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            @endif

            <div class="flex justify-end mt-4">
                <flux:button wire:click="$set('showDetailModal', false)">Tutup</flux:button>
            </div>
        </div>
    </flux:modal>
    
    <!-- Modal Import Excel -->
    <flux:modal name="importModal" class="max-w-md">
        <form>
            <div class="space-y-4">
                <flux:heading size="lg">Import Excel</flux:heading>
                <flux:subheading>Import data nilai sekaligus.</flux:subheading>
                <flux:input type="file" label="File Excel" />
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button>Batal</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="primary">Import</flux:button>
                </div>
            </div>
        </form>
    </flux:modal>
</div>
