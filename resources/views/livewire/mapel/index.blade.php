<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <flux:heading size="xl">Master Mata Pelajaran</flux:heading>
            <flux:subheading>Kelola data mata pelajaran.</flux:subheading>
        </div>
        <div class="flex gap-2">
            <flux:button wire:click="create" variant="primary" icon="plus">Tambah Mapel</flux:button>
            <flux:modal.trigger name="importModal">
                <flux:button variant="filled" icon="arrow-down-tray">Import Excel</flux:button>
            </flux:modal.trigger>
        </div>
    </div>

    @if (session()->has('message'))
        <flux:toast variant="success" text="{{ session('message') }}" />
    @endif

    <flux:table>
        <flux:table.columns>
            <flux:table.column>Nama Mata Pelajaran</flux:table.column>
            <flux:table.column>Aksi</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach($mapels as $mapel)
                <flux:table.row>
                    <flux:table.cell>{{ $mapel->nama_mapel }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="edit({{ $mapel->id }})">Edit</flux:button>
                        <flux:button size="sm" variant="ghost" icon="trash" wire:click="delete({{ $mapel->id }})" class="text-red-500 hover:text-red-700">Hapus</flux:button>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
    
    <div class="mt-4">
        {{ $mapels->links() }}
    </div>

    <!-- Modal Form Tambah/Edit -->
    <flux:modal wire:model="showModal" class="max-w-md">
        <form wire:submit.prevent="save">
            <div class="space-y-4">
                <flux:heading size="lg">{{ $edit_id ? 'Edit' : 'Tambah' }} Mata Pelajaran</flux:heading>
                
                <flux:input wire:model="nama_mapel" label="Nama Mata Pelajaran" placeholder="Contoh: Matematika" />
                
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
                <flux:subheading>Pastikan format file Excel sesuai dengan template (Kolom A: Nama Mapel).</flux:subheading>
                
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
