<?php

namespace App\Http\Controllers\Api;

use App\Enums\ReportStatus;
use App\Enums\ReportType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Models\ItemCategory;
use App\Models\Report;
use App\Services\ReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API laporan barang — dipakai UI yang sudah ada (tanpa mengubah markup).
 */
class ReportApiController extends Controller
{
    public function __construct(private readonly ReportService $reports)
    {
    }

    public function index(Request $request): JsonResponse
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
            ->when($request->query('campus'), fn ($q, $campus) => $q->where('campus_location', $campus))
            ->when($request->query('month'), function ($query, $month) {
                if (preg_match('/^\d{4}-\d{2}$/', (string) $month)) {
                    [$year, $mon] = explode('-', (string) $month);
                    $query->whereYear('incident_date', (int) $year)->whereMonth('incident_date', (int) $mon);
                }
            })
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->with(['user', 'category'])
            ->withCount('claims')
            ->latest()
            ->paginate(min(50, (int) $request->query('per_page', config('findumb.report.per_page', 12))))
            ->withQueryString();

        $viewer = $request->user();

        return response()->json([
            'data' => collect($reports->items())->map(fn (Report $r) => $r->toUiArray($viewer)),
            'meta' => [
                'current_page' => $reports->currentPage(),
                'last_page' => $reports->lastPage(),
                'per_page' => $reports->perPage(),
                'total' => $reports->total(),
            ],
        ]);
    }

    public function show(Request $request, Report $report): JsonResponse
    {
        $viewer = $request->user();

        if (! $report->status->isPubliclyVisible()) {
            $allowed = $viewer && ($report->isOwnedBy($viewer) || $viewer->isAdmin());
            abort_unless($allowed, 404);
        }

        $report->load(['user', 'category', 'claims.claimant']);

        return response()->json([
            'data' => array_merge($report->toUiArray($viewer), [
                'claims' => $report->claims->map(fn ($claim) => $claim->toUiArray($viewer)),
                'can_claim' => $viewer
                    && $report->canReceiveClaim()
                    && ! $report->isOwnedBy($viewer)
                    && ! $viewer->hasActiveClaim(),
            ]),
        ]);
    }

    public function store(StoreReportRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['type'] = $request->enum('type', ReportType::class);

        $report = $this->reports->create(
            $request->user(),
            $data,
            $request->file('photo'),
        );

        return response()->json([
            'message' => 'Laporan berhasil dibuat.',
            'data' => $report->load(['user', 'category'])->toUiArray($request->user()),
        ], 201);
    }

    public function update(UpdateReportRequest $request, Report $report): JsonResponse
    {
        $updated = $this->reports->update(
            $report,
            $request->user(),
            $request->validated(),
            $request->file('photo'),
        );

        return response()->json([
            'message' => 'Laporan berhasil diperbarui.',
            'data' => $updated->load(['user', 'category'])->toUiArray($request->user()),
        ]);
    }

    public function cancel(Request $request, Report $report): JsonResponse
    {
        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $cancelled = $this->reports->cancel($report, $request->user(), $validated['reason'] ?? null);

        return response()->json([
            'message' => 'Laporan dibatalkan.',
            'data' => $cancelled->toUiArray($request->user()),
        ]);
    }

    public function mine(Request $request): JsonResponse
    {
        $user = $request->user();

        $reports = Report::query()
            ->where('user_id', $user->id)
            ->with(['user', 'category'])
            ->withCount('claims')
            ->latest()
            ->get();

        return response()->json([
            'data' => $reports->map(fn (Report $r) => $r->toUiArray($user)),
        ]);
    }
}
