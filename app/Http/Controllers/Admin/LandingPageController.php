<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LandingPageController extends Controller
{
    protected array $keys = [
        'landing_headline',
        'landing_subtext',
        'landing_cta_headline',
        'landing_cta_subtext',
    ];

    public function edit(): View
    {
        $settings = collect($this->keys)->mapWithKeys(fn ($key) => [$key => Setting::get($key)]);

        return view('admin.landing.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'landing_headline' => ['nullable', 'string', 'max:255'],
            'landing_subtext' => ['nullable', 'string', 'max:500'],
            'landing_cta_headline' => ['nullable', 'string', 'max:255'],
            'landing_cta_subtext' => ['nullable', 'string', 'max:500'],
        ]);

        foreach ($this->keys as $key) {
            Setting::set($key, $validated[$key] ?? null);
        }

        return redirect()->route('admin.landing.edit')->with('status', 'Halaman depan berhasil diperbarui.');
    }
}
