@props([
    'sidebar' => false,
])

@php
    $setting = \App\Models\Setting::first();
    $appName = $setting && $setting->app_name ? $setting->app_name : 'Laravel Starter Kit';
    $logoUrl = $setting && $setting->logo ? Storage::url($setting->logo) : null;
@endphp

@if($sidebar)
    <flux:sidebar.brand name="{{ $appName }}" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground overflow-hidden">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" class="w-full h-full object-cover" alt="Logo" />
            @else
                <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
            @endif
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="{{ $appName }}" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground overflow-hidden">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" class="w-full h-full object-cover" alt="Logo" />
            @else
                <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
            @endif
        </x-slot>
    </flux:brand>
@endif
