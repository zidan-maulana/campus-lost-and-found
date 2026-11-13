<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * "Laporan Tersimpan" — daftar bookmark pengguna.
 */
class SavedReportsController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $saved = Report::query()
            ->whereHas('savedBy', fn ($q) => $q->where('users.id', $user->id))
            ->with(['user', 'category'])
            ->withCount('claims')
            ->latest()
            ->get();

        return view('saved-reports', [
            'viewer' => $user,
            'reports' => $saved,
        ]);
    }
}
