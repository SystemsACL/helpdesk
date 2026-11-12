<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit(Request $request)
    {
        abort_unless($request->user()->isAdmin(), 403);
        return view('panel.settings', ['s' => CompanySetting::current()]);
    }

    public function update(Request $request)
    {
        abort_unless($request->user()->isAdmin(), 403);

        $hex = ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'];
        $data = $request->validate([
            'company_name' => ['required', 'string', 'max:120'],
            'whatsapp_number' => ['nullable', 'string', 'max:20'],
            'primary_color' => $hex,
            'secondary_color' => $hex,
            'accent_color' => $hex,
            'timezone' => ['required', 'timezone'],
            'msg_new' => ['nullable', 'string', 'max:1000'],
            'msg_status' => ['nullable', 'string', 'max:1000'],
            'msg_closed' => ['nullable', 'string', 'max:1000'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:1024'],
        ]);

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('logos', 'public');
        }
        unset($data['logo']);

        $settings = CompanySetting::query()->first() ?? new CompanySetting();
        $settings->fill($data)->save();

        return redirect()->route('settings.edit')->with('ok', 'Ajustes guardados.');
    }
}
