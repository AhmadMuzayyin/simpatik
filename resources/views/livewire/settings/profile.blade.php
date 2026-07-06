<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Profile settings') }}</flux:heading>

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your name, email address, and application settings.')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <flux:input wire:model="app_name" :label="__('Nama Aplikasi')" type="text" required />
                </div>
                <div>
                    <flux:input wire:model="lembaga" :label="__('Nama Lembaga')" type="text" required />
                </div>
                
                <div>
                    <flux:input wire:model="logo" :label="__('Logo Aplikasi')" type="file" accept="image/*" />
                    @if ($logo)
                        <img src="{{ $logo->temporaryUrl() }}" class="mt-2 h-16 w-auto object-contain rounded" />
                    @elseif ($existing_logo)
                        <img src="{{ Storage::url($existing_logo) }}" class="mt-2 h-16 w-auto object-contain rounded" />
                    @endif
                </div>

                <div>
                    <flux:input wire:model="favicon" :label="__('Favicon Aplikasi')" type="file" accept="image/*" />
                    @if ($favicon)
                        <img src="{{ $favicon->temporaryUrl() }}" class="mt-2 h-8 w-8 object-contain rounded" />
                    @elseif ($existing_favicon)
                        <img src="{{ Storage::url($existing_favicon) }}" class="mt-2 h-8 w-8 object-contain rounded" />
                    @endif
                </div>
            </div>
            
            <flux:input wire:model="name" :label="__('Name')" type="text" required autocomplete="name" />

            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />
            </div>

            <div class="flex items-center gap-4">
                <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
            </div>
        </form>

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>
