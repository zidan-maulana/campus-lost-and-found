<?php

namespace App\Http\Controllers;

use App\Enums\ReportType;
use App\Models\ItemCategory;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Pencarian dan filter laporan (PR-03, FR-05).
 * Tersedia untuk pengunjung maupun pengguna terautentikasi.
 */
class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $type = match ($request->query('type')) {
            'lost' => ReportType::Lost,
            'found' => ReportType::Found,
            default => null,
        };

        $reports = Report::query()
            ->publiclyVisible()
            ->ofType($type)
            ->search($request->query('q'))
            ->when($request->query('category'), function ($query, $category) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $category));
            })
            ->when($request->query('campus'), function ($query, $campus) {
                $query->where('campus_location', $campus);
            })
            ->when($request->query('month'), function ($query, $month) {
                // Format: YYYY-MM
                if (preg_match('/^\d{4}-\d{2}$/', (string) $month)) {
                    [$year, $mon] = explode('-', (string) $month);
                    $query->whereYear('incident_date', (int) $year)
                        ->whereMonth('incident_date', (int) $mon);
                }
            })
            ->with(['user', 'category'])
            ->withCount('claims')
            ->latest()
            ->paginate((int) config('findumb.report.per_page', 12))
            ->withQueryString();

        return view('search', [
            'viewer' => $request->user(),
            'reports' => $reports,
            'categories' => ItemCategory::query()->active()->ordered()->get(),
            'filters' => [
                'q' => (string) $request->query('q', ''),
                'type' => $request->query('type'),
                'category' => $request->query('category'),
                'month' => $request->query('month'),
            ],
        ]);
    }
}
