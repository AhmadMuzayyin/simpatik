<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <flux:heading size="xl">Master Siswa</flux:heading>
            <flux:subheading>Kelola data siswa berdasarkan kelas.</flux:subheading>
        </div>
        <div class="flex gap-2">
            <flux:button wire:click="create" variant="primary" icon="plus">Tambah Siswa</flux:button>
            <flux:button wire:click="$set('showExportModal', true)" variant="filled" icon="arrow-down-tray">Download Template</flux:button>
            <flux:modal.trigger name="importModal">
                <flux:button variant="filled" icon="arrow-up-tray">Import Excel</flux:button>
            </flux:modal.trigger>
        </div>
    </div>

    @if (session()->has('message'))
        <flux:toast variant="success" text="{{ session('message') }}" />
    @endif

    <flux:table>
        <flux:table.columns>
            <flux:table.column>NIS</flux:table.column>
            <flux:table.column>Nama Siswa</flux:table.column>
            <flux:table.column>Kelas</flux:table.column>
            <flux:table.column>Tempat, Tgl Lahir</flux:table.column>
            <flux:table.column>Aksi</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach($siswas as $siswa)
                <flux:table.row>
                    <flux:table.cell>{{ $siswa->nis }}</flux:table.cell>
                    <flux:table.cell>{{ $siswa->nama_siswa }}</flux:table.cell>
                    <flux:table.cell>{{ $siswa->kelas->nama_kelas }}</flux:table.cell>
                    <flux:table.cell>{{ $siswa->tempat_lahir }}, {{ $siswa->tanggal_lahir }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="edit({{ $siswa->id }})">Edit</flux:button>
                        <flux:button size="sm" variant="ghost" icon="trash" wire:click="delete({{ $siswa->id }})" class="text-red-500 hover:text-red-700">Hapus</flux:button>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
    
    <div class="mt-4">
        {{ $siswas->links() }}
    </div>

    <!-- Modal Form Tambah/Edit -->
    <flux:modal wire:model="showModal" class="max-w-xl">
        <form wire:submit.prevent="save">
            <div class="space-y-4">
                <flux:heading size="lg">{{ $edit_id ? 'Edit' : 'Tambah' }} Siswa</flux:heading>
                
                <div class="grid grid-cols-2 gap-4">
                    <flux:input wire:model="nis" label="NIS" placeholder="Nomor Induk Siswa" />
                    <flux:select wire:model="kelas_id" label="Kelas" placeholder="Pilih Kelas">
                        @foreach($kelases as $kelas)
                            <flux:select.option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>
                
                <flux:input wire:model="nama_siswa" label="Nama Siswa" placeholder="Nama Lengkap" />
                
                <div class="grid grid-cols-2 gap-4">
                    <flux:input wire:model="tempat_lahir" label="Tempat Lahir" placeholder="Contoh: Jakarta" />
                    <flux:input type="date" wire:model="tanggal_lahir" label="Tanggal Lahir" />
                </div>
                
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:button wire:click="$set('showModal', false)">Batal</flux:button>
                    <flux:button type="submit" variant="primary">Simpan</flux:button>
                </div>
            </div>
        </form>
    </flux:modal>

    <!-- Modal Import Excel -->
    <flux:modal name="importModal" class="max-w-md">
        <form>
            <div class="space-y-4">
                <flux:heading size="lg">Import Excel</flux:heading>
                <flux:subheading>Pastikan kolom sesuai dengan template yang didownload.</flux:subheading>
                
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

    <!-- Modal Download Template -->
    <flux:modal wire:model="showExportModal" class="max-w-md">
        <form wire:submit.prevent="downloadTemplate">
            <div class="space-y-4">
                <flux:heading size="lg">Download Template Siswa</flux:heading>
                <flux:subheading>Pilih kelas. File template akan otomatis terisi data siswa di kelas tersebut (jika ada).</flux:subheading>
                
                <flux:select wire:model="template_kelas_id" label="Kelas" placeholder="Pilih Kelas">
                    @foreach($kelases as $kelas)
                        <flux:select.option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</flux:select.option>
                    @endforeach
                </flux:select>
                
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:button wire:click="$set('showExportModal', false)">Batal</flux:button>
                    <flux:button type="submit" variant="primary">Download</flux:button>
                </div>
            </div>
        </form>
    </flux:modal>
</div>
