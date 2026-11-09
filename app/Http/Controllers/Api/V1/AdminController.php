<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\ReportStatus;
use App\Http\Controllers\Controller;
use App\Models\AbuseReport;
use App\Models\Claim;
use App\Models\ItemCategory;
use App\Models\Report;
use App\Models\User;
use App\Services\ModerationService;
use App\Support\Api\ApiPresenter;
use App\Support\Api\V1Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Panel administrator — docs/API_CONTRACT.md §9.
 */
class AdminController extends Controller
{
    use V1Response;

    public function __construct(private readonly ModerationService $moderation)
    {
    }

    /**
     * GET /api/v1/admin/overview (§9.1)
     */
    public function overview(): JsonResponse
    {
        return $this->ok([
            'total_reports' => Report::count(),
            'open_reports' => Report::where('status', ReportStatus::Open->value)->count(),
            'resolved_reports' => Report::where('status', ReportStatus::Resolved->value)->count(),
            'hidden_reports' => Report::where('status', ReportStatus::Hidden->value)->count(),
            'total_users' => User::count(),
            'suspended_users' => User::where('status', 'suspended')->count(),
            'active_claims' => Claim::whereIn('status', ['submitted', 'in_handover'])->count(),
            'pending_moderations' => AbuseReport::query()->pending()->count(),
        ], 'Ringkasan KPI berhasil diambil.');
    }

    /**
     * PATCH /api/v1/admin/reports/{report}/status (§9.2)
     */
    public function updateReportStatus(Request $request, Report $report): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['hidden', 'open'])],
            'reason' => ['required', 'string', 'min:5', 'max:500'],
        ], [
            'status.required' => 'Status laporan wajib dikirim.',
            'status.in' => 'Status hanya boleh hidden atau open.',
            'reason.required' => 'Alasan moderasi wajib diisi.',
        ]);

        if ($validated['status'] === 'hidden') {
            $this->moderation->hideReport($request->user(), $report, $validated['reason']);
        } else {
            $this->moderation->unhideReport($request->user(), $report, $validated['reason']);
        }

        return $this->ok(
            ApiPresenter::report($report->refresh()->load(['user', 'category']), $request->user()),
            $validated['status'] === 'hidden'
                ? 'Laporan disembunyikan dari katalog publik.'
                : 'Laporan ditampilkan kembali.',
        );
    }

    /**
     * PATCH /api/v1/admin/users/{user}/status (§9.3, DEC-026)
     */
    public function updateUserStatus(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['active', 'suspended'])],
            'reason' => ['required', 'string', 'min:5', 'max:500'],
        ], [
            'status.required' => 'Status akun wajib dikirim.',
            'status.in' => 'Status hanya boleh active atau suspended.',
            'reason.required' => 'Alasan tindakan wajib diisi.',
        ]);

        if ((int) $user->id === (int) $request->user()->id) {
            return $this->fail('Administrator tidak dapat mengubah status akunnya sendiri.', [
                'status' => ['Administrator tidak dapat mengubah status akunnya sendiri.'],
            ], 422);
        }

        if ($validated['status'] === 'suspended') {
            $this->moderation->suspendUser($request->user(), $user, $validated['reason']);
        } else {
            $this->moderation->reactivateUser($request->user(), $user, $validated['reason']);
        }

        return $this->ok(
            ApiPresenter::user($user->refresh()),
            $validated['status'] === 'suspended'
                ? 'Akun dinonaktifkan sesuai kebijakan DEC-026.'
                : 'Akun diaktifkan kembali. Status laporan lama tidak berubah otomatis.',
        );
    }

    /**
     * GET /api/v1/admin/categories (§9.4)
     */
    public function categories(): JsonResponse
    {
        $categories = ItemCategory::query()
            ->withCount('reports')
            ->ordered()
            ->get()
            ->map(fn (ItemCategory $category) => [
                'id' => (int) $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'icon' => $category->icon,
                'is_active' => (bool) $category->is_active,
                'sort_order' => (int) $category->sort_order,
                'reports_count' => (int) $category->reports_count,
            ])
            ->values()
            ->all();

        return $this->ok($categories, 'Daftar kategori berhasil diambil.');
    }

    /**
     * POST /api/v1/admin/categories (§9.4)
     */
    public function storeCategory(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:item_categories,name'],
            'description' => ['sometimes', 'nullable', 'string', 'max:500'],
            'icon' => ['sometimes', 'nullable', 'string', 'max:50'],
            'is_active' => ['sometimes', 'boolean'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori sudah digunakan.',
        ]);

        $category = ItemCategory::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'icon' => $validated['icon'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
            'sort_order' => (int) (ItemCategory::max('sort_order') ?? 0) + 1,
        ]);

        return $this->created([
            'id' => (int) $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'description' => $category->description,
            'icon' => $category->icon,
            'is_active' => (bool) $category->is_active,
            'sort_order' => (int) $category->sort_order,
            'reports_count' => 0,
        ], 'Kategori berhasil ditambahkan.');
    }

    /**
     * PUT /api/v1/admin/categories/{category} (§9.4)
     */
    public function updateCategory(Request $request, ItemCategory $category): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:100', Rule::unique('item_categories', 'name')->ignore($category->id)],
            'description' => ['sometimes', 'nullable', 'string', 'max:500'],
            'icon' => ['sometimes', 'nullable', 'string', 'max:50'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
        ]);

        if (isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category->update($validated);

        return $this->ok([
            'id' => (int) $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'description' => $category->description,
            'icon' => $category->icon,
            'is_active' => (bool) $category->is_active,
            'sort_order' => (int) $category->sort_order,
            'reports_count' => $category->reports()->count(),
        ], 'Kategori berhasil diperbarui.');
    }

    /**
     * DELETE /api/v1/admin/categories/{category} (§9.4)
     */
    public function destroyCategory(ItemCategory $category): JsonResponse
    {
        $used = $category->reports()->count();

        if ($used > 0) {
            return $this->fail('Kategori tidak dapat dihapus karena masih digunakan oleh '.$used.' laporan.', [
                'category' => ['Kategori masih digunakan oleh '.$used.' laporan.'],
            ], 422);
        }

        $category->delete();

        return $this->ok(null, 'Kategori berhasil dihapus.');
    }
}
