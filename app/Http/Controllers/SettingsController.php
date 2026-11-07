<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function show(Request $request): View
    {
        return view('settings', [
            'viewer' => $request->user(),
            'preferences' => $request->user()->preferences ?? [],
        ]);
    }
}
