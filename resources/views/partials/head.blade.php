<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

@php
    $setting = \App\Models\Setting::first();
    $appName = $setting && $setting->app_name ? $setting->app_name : config('app.name', 'Laravel');
    $favicon = $setting && $setting->favicon ? Storage::url($setting->favicon) : '/favicon.ico';
@endphp

<title>
    {{ filled($title ?? null) ? $title.' - '.$appName : $appName }}
</title>

@if($setting->favicon)
    <link rel="icon" href="{{ $favicon }}" sizes="any">
@else
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
@endif

@fonts

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
