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
            ->listed()
            ->with(['user', 'category'])
            ->withCount('claims')
            ->latest()
            ->limit(10)
            ->get();

        // "Featured" adalah bagian dari "recent" (5 teratas). Sebelumnya ini
        // query terpisah yang mengulang 3 query eager-load yang sama.
        $featured = $recent->take(5);

        // Dua hitungan digabung jadi satu query (portabel Postgres & SQLite).
        $row = Report::query()
            ->selectRaw(
                "sum(case when type = 'lost' then 1 else 0 end) as total_lost,
                 sum(case when type = 'found' then 1 else 0 end) as total_found"
            )
            ->listed()
            ->first();

        $counts = [
            'lost' => (int) ($row->total_lost ?? 0),
            'found' => (int) ($row->total_found ?? 0),
        ];

        $resolved = Report::query()->where('status', 'resolved')->count();

        return view('welcome', [
            'viewer' => $viewer,
            'recentReports' => $recent,
            'featuredReports' => $featured,
            'categories' => ItemCategory::cachedActive(),
            'savedIds' => $viewer
                ? $viewer->savedReports()->pluck('reports.id')->map(fn ($id) => (string) $id)->all()
                : [],
            'stats' => [
                'lost' => (int) ($counts['lost'] ?? 0),
                'found' => (int) ($counts['found'] ?? 0),
                'resolved' => $resolved,
            ],
        ]);
    }
}
