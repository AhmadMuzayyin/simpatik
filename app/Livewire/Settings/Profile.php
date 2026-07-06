<?php

namespace App\Livewire\Settings;

use App\Concerns\ProfileValidationRules;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Title('Profile settings')]
class Profile extends Component
{
    use ProfileValidationRules;
    use WithFileUploads;

    public string $name = '';

    public string $email = '';

    public string $app_name = '';

    public string $lembaga = '';

    public $logo;
    public $favicon;
    public $existing_logo = null;
    public $existing_favicon = null;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = auth()->user()->name;
        $this->email = auth()->user()->email;
        
        $setting = \App\Models\Setting::first();
        if ($setting) {
            $this->app_name = $setting->app_name;
            $this->lembaga = $setting->lembaga;
            $this->existing_logo = $setting->logo;
            $this->existing_favicon = $setting->favicon;
        }
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $this->validate([
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:1024',
        ]);

        $user = auth()->user();

        $user->fill([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
        
        $setting = \App\Models\Setting::firstOrCreate(['id' => 1]);
        $setting->app_name = $this->app_name;
        $setting->lembaga = $this->lembaga;

        if ($this->logo) {
            if ($setting->logo) Storage::disk('public')->delete($setting->logo);
            $setting->logo = $this->logo->store('settings', 'public');
            $this->existing_logo = $setting->logo;
        }

        if ($this->favicon) {
            if ($setting->favicon) Storage::disk('public')->delete($setting->favicon);
            $setting->favicon = $this->favicon->store('settings', 'public');
            $this->existing_favicon = $setting->favicon;
        }

        $setting->save();

        $this->dispatch('profile-updated', name: $user->name);
        
        Flux::toast(variant: 'success', text: __('Profile updated.'));
    }
}
