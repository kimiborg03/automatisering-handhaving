<?php

// app/Http/Controllers/MailSettingsController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class MailSettingsController extends Controller
{
    public function edit()
    {
        $fields = [
            'MAIL_HOST' => env('MAIL_HOST'),
            'MAIL_PORT' => env('MAIL_PORT'),
            'MAIL_USERNAME' => env('MAIL_USERNAME'),
            'MAIL_PASSWORD' => env('MAIL_PASSWORD'),
            'MAIL_FROM_ADDRESS' => env('MAIL_FROM_ADDRESS'),
            'MAIL_FROM_NAME' => env('MAIL_FROM_NAME'),
        ];
        return view('admin.mailsettings', compact('fields'));
    }

    public function update(Request $request)
    {
        $data = $request->only([
            'MAIL_HOST', 'MAIL_PORT', 'MAIL_USERNAME', 'MAIL_PASSWORD', 'MAIL_FROM_ADDRESS', 'MAIL_FROM_NAME'
        ]);

        $envPath = base_path('.env');
        $env = file_get_contents($envPath);
        foreach ($data as $key => $value) {
            $env = preg_replace("/^{$key}=.*$/m", "{$key}=\"{$value}\"", $env);
        }
        file_put_contents($envPath, $env);

        Artisan::call('config:cache');

        return redirect()->route('admin.mailsettings')->with('success', 'Mailinstellingen bijgewerkt!');
    }
}