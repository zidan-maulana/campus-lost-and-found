<?php

namespace App\Http\Controllers;

use App\Models\ItemCategory;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Beranda / katalog laporan (PR-03, UI_UX 5.1).
 * Dapat diakses pengunjung tanpa login (DEC-020).
 */
class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $viewer = $request->user();

        $recent = Report::query()
            ->publiclyVisible()
            ->with(['user', 'category'])
            ->withCount('claims')
            ->latest()
            ->limit(10)
            ->get();

        $featured = Report::query()
            ->publiclyVisible()
            ->with(['user', 'category'])
            ->withCount('claims')
            ->latest('created_at')
            ->limit(5)
            ->get();

        return view('welcome', [
            'viewer' => $viewer,
            'recentReports' => $recent,
            'featuredReports' => $featured,
            'categories' => ItemCategory::query()->active()->ordered()->get(),
            'stats' => [
                'lost' => Report::query()->publiclyVisible()->where('type', 'lost')->count(),
                'found' => Report::query()->publiclyVisible()->where('type', 'found')->count(),
                'resolved' => Report::query()->where('status', 'resolved')->count(),
            ],
        ]);
    }
}
