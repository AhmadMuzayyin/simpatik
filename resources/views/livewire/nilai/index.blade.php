<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <flux:heading size="xl">Master Nilai</flux:heading>
            <flux:subheading>Kelola data nilai mapel dan nilai harian siswa.</flux:subheading>
        </div>
    </div>

    @if (session()->has('message'))
        <flux:toast variant="success" text="{{ session('message') }}" />
    @endif

    <div class="mb-4 flex gap-4 max-w-sm">
        <flux:select wire:model.live="filter_kelas" placeholder="Semua Kelas">
            <flux:select.option value="">Semua Kelas</flux:select.option>
            @foreach($kelases as $kelas)
                <flux:select.option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</flux:select.option>
            @endforeach
        </flux:select>
    </div>

    <div class="flex gap-2 mb-6">
        <flux:button wire:click="$set('activeTab', 'mapel')" variant="{{ $activeTab === 'mapel' ? 'primary' : 'filled' }}">Nilai Mapel</flux:button>
        <flux:button wire:click="$set('activeTab', 'harian')" variant="{{ $activeTab === 'harian' ? 'primary' : 'filled' }}">Nilai Harian (Dinamis)</flux:button>
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
                            <flux:table.cell class="font-semibold text-zinc-900 dark:text-white">{{ $s->nama_siswa }}</flux:table.cell>
                            <flux:table.cell>{{ optional($s->kelas)->nama_kelas }}</flux:table.cell>
                            <flux:table.cell>{{ $s->nilai_mapels_count }} Mapel</flux:table.cell>
                            <flux:table.cell>{{ number_format($s->nilai_mapels_sum_nilai, 2) }}</flux:table.cell>
                            <flux:table.cell>{{ number_format($s->nilai_mapels_avg_nilai, 2) }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:button size="sm" variant="ghost" icon="eye" wire:click="showDetail({{ $s->id }})">Detail Mapel</flux:button>
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
                <flux:button wire:click="createHarian" variant="primary" icon="plus" size="sm">Input / Update Nilai Harian</flux:button>
            </div>
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Nama Siswa</flux:table.column>
                    <flux:table.column>Kelas</flux:table.column>
                    <flux:table.column>Rincian Kategori Nilai Harian</flux:table.column>
                    <flux:table.column>Rata-rata Harian</flux:table.column>
                    <flux:table.column>Aksi</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach($harianSiswas as $hs)
                        <flux:table.row>
                            <flux:table.cell class="font-semibold text-zinc-900 dark:text-white">{{ $hs->nama_siswa }}</flux:table.cell>
                            <flux:table.cell>{{ optional($hs->kelas)->nama_kelas }}</flux:table.cell>
                            <flux:table.cell>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($hs->nilaiHarians as $nh)
                                        <flux:badge size="sm" variant="subtle">
                                            {{ optional($nh->kategoriNilaiHarian)->nama_kategori }}: <strong class="ml-1">{{ $nh->nilai }}</strong>
                                        </flux:badge>
                                    @endforeach
                                </div>
                            </flux:table.cell>
                            <flux:table.cell class="font-bold text-sky-600 dark:text-sky-400">
                                {{ number_format($hs->nilaiHarians->avg('nilai'), 2) }}
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="editHarianForSiswa({{ $hs->id }})">Edit</flux:button>
                                <flux:button size="sm" variant="ghost" icon="trash" wire:click="deleteHarianForSiswa({{ $hs->id }})" class="text-red-500 hover:text-red-700">Hapus</flux:button>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
            <div class="mt-4">{{ $harianSiswas->links() }}</div>
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
                            <flux:select.option value="{{ $siswa->id }}">{{ $siswa->nama_siswa }} ({{ optional($siswa->kelas)->nama_kelas }})</flux:select.option>
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

    <!-- Modal Nilai Harian Dinamis -->
    <flux:modal wire:model="showHarianModal" class="max-w-xl">
        <form wire:submit.prevent="saveHarian">
            <div class="space-y-4">
                <flux:heading size="lg">Input / Edit Nilai Harian Siswa</flux:heading>
                <flux:subheading>Inputkan nilai untuk setiap kategori penilaian harian sekolah.</flux:subheading>
                
                <flux:select wire:model.live="harian_siswa_id" label="Pilih Siswa" placeholder="Pilih Siswa">
                    @foreach($siswas as $siswa)
                        <flux:select.option value="{{ $siswa->id }}">{{ $siswa->nama_siswa }} - {{ optional($siswa->kelas)->nama_kelas }}</flux:select.option>
                    @endforeach
                </flux:select>
                
                @if(!empty($available_categories))
                    <div class="border border-zinc-200 dark:border-zinc-700/70 rounded-xl p-4 space-y-3 bg-zinc-50/50 dark:bg-zinc-900/40">
                        <flux:heading size="sm" class="text-zinc-700 dark:text-zinc-300">Kategori Penilaian Harian:</flux:heading>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($available_categories as $cat)
                                <flux:input type="number" step="0.01" wire:model="harian_scores.{{ $cat->id }}" label="{{ $cat->nama_kategori }}" placeholder="0 - 100" />
                            @endforeach
                        </div>
                    </div>
                @elseif($harian_siswa_id)
                    <div class="p-4 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-200 text-xs rounded-xl">
                        Belum ada kategori nilai harian terdaftar. Silakan tambahkan kategori pada menu <strong>Kategori Nilai Harian</strong>.
                    </div>
                @endif
                
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:button wire:click="$set('showHarianModal', false)">Batal</flux:button>
                    @if(!empty($available_categories))
                        <flux:button type="submit" variant="primary">Simpan Nilai Harian</flux:button>
                    @endif
                </div>
            </div>
        </form>
    </flux:modal>
    
    <!-- Modal Detail Nilai Mapel Siswa -->
    <flux:modal wire:model="showDetailModal" class="max-w-3xl">
        <div class="space-y-4">
            <flux:heading size="lg">Detail Nilai Mata Pelajaran Siswa</flux:heading>
            @if($detailSiswa)
                <div class="mb-4">
                    <flux:text class="font-semibold text-base">{{ $detailSiswa->nama_siswa }}</flux:text>
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
                                <flux:table.cell class="font-medium">{{ optional($nm->mataPelajaran)->nama_mapel }}</flux:table.cell>
                                <flux:table.cell class="font-bold">{{ $nm->nilai }}</flux:table.cell>
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
</div>
