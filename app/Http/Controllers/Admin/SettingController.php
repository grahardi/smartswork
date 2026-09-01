<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    protected array $keys = [
        'site_name',
        'kontak_email',
        'kontak_whatsapp',
        'demo_enabled',
    ];

    public function edit(): View
    {
        $settings = collect($this->keys)->mapWithKeys(fn ($key) => [$key => Setting::get($key)]);

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => ['nullable', 'string', 'max:255'],
            'kontak_email' => ['nullable', 'email'],
            'kontak_whatsapp' => ['nullable', 'string', 'max:30'],
            'demo_enabled' => ['nullable', 'boolean'],
        ]);

        foreach ($this->keys as $key) {
            if ($key === 'demo_enabled') {
                Setting::set($key, $request->boolean('demo_enabled') ? '1' : '0');
                continue;
            }
            Setting::set($key, $validated[$key] ?? null);
        }

        return redirect()->route('admin.settings.edit')->with('status', 'Pengaturan berhasil disimpan.');
    }
}
