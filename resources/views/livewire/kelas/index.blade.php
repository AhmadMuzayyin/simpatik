<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <flux:heading size="xl">Master Kelas</flux:heading>
            <flux:subheading>Kelola data kelas yang ada di lembaga.</flux:subheading>
        </div>
        <div class="flex gap-2">
            <flux:button wire:click="create" variant="primary" icon="plus">Tambah Kelas</flux:button>
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
            <flux:table.column>Nama Kelas</flux:table.column>
            <flux:table.column>Aksi</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach($kelases as $kelas)
                <flux:table.row>
                    <flux:table.cell>{{ $kelas->nama_kelas }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="edit({{ $kelas->id }})">Edit</flux:button>
                        <flux:button size="sm" variant="ghost" icon="trash" wire:click="delete({{ $kelas->id }})" class="text-red-500 hover:text-red-700">Hapus</flux:button>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
    
    <div class="mt-4">
        {{ $kelases->links() }}
    </div>

    <!-- Modal Form Tambah/Edit -->
    <flux:modal wire:model="showModal" class="max-w-md">
        <form wire:submit.prevent="save">
            <div class="space-y-4">
                <flux:heading size="lg">{{ $edit_id ? 'Edit' : 'Tambah' }} Kelas</flux:heading>
                
                <flux:input wire:model="nama_kelas" label="Nama Kelas" placeholder="Contoh: Kelas 7A" />
                
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
                <flux:subheading>Pastikan format file Excel sesuai dengan template (Kolom A: Nama Kelas).</flux:subheading>
                
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
