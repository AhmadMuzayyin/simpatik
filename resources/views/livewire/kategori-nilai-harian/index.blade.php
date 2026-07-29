<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <flux:heading size="xl">Kategori Nilai Harian</flux:heading>
            <flux:subheading>Kelola kriteria / kategori penilaian harian dinamis sekolah.</flux:subheading>
        </div>
        <div class="flex gap-2">
            <flux:button wire:click="create" variant="primary" icon="plus">Tambah Kategori</flux:button>
        </div>
    </div>

    @if (session()->has('message'))
        <flux:toast variant="success" text="{{ session('message') }}" />
    @endif

    <flux:table>
        <flux:table.columns>
            <flux:table.column>Nama Kategori Nilai Harian</flux:table.column>
            <flux:table.column>Aksi</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse($kategoriList as $k)
                <flux:table.row>
                    <flux:table.cell class="font-semibold text-zinc-900 dark:text-white">{{ $k->nama_kategori }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="edit({{ $k->id }})">Edit</flux:button>
                        <flux:button size="sm" variant="ghost" icon="trash" wire:click="delete({{ $k->id }})" class="text-red-500 hover:text-red-700">Hapus</flux:button>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="2" class="text-center py-6 text-zinc-500">
                        Belum ada kategori nilai harian terdaftar. Silakan tambahkan kategori baru.
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>
    
    <div class="mt-4">
        {{ $kategoriList->links() }}
    </div>

    <!-- Modal Form Tambah/Edit -->
    <flux:modal wire:model="showModal" class="max-w-md">
        <form wire:submit.prevent="save">
            <div class="space-y-4">
                <flux:heading size="lg">{{ $edit_id ? 'Edit' : 'Tambah' }} Kategori Nilai Harian</flux:heading>
                
                <flux:input wire:model="nama_kategori" label="Nama Kategori Nilai" placeholder="Contoh: Akhlak, Tahfidz, Kedisiplinan, Kehadiran" />
                
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:button wire:click="$set('showModal', false)">Batal</flux:button>
                    <flux:button type="submit" variant="primary">Simpan</flux:button>
                </div>
            </div>
        </form>
    </flux:modal>
</div>
