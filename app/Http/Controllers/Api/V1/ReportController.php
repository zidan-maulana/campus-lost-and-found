<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\ReportStatus;
use App\Enums\ReportType;
use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Services\ReportService;
use App\Support\Api\ApiPresenter;
use App\Support\Api\V1Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Modul laporan barang — docs/API_CONTRACT.md §5.
 */
class ReportController extends Controller
{
    use V1Response;

    public function __construct(private readonly ReportService $reports)
    {
    }

    /**
     * GET /api/v1/reports (§5.1) — publik.
     */
    public function index(Request $request): JsonResponse
    {
        $campuses = array_keys((array) config('findumb.campuses', []));

        $validated = $request->validate([
            'type' => ['sometimes', Rule::enum(ReportType::class)],
            'category_id' => ['sometimes', 'integer', 'exists:item_categories,id'],
            'campus' => ['sometimes', Rule::in($campuses)],
            'month' => ['sometimes', 'integer', 'between:1,12'],
            'year' => ['sometimes', 'integer', 'between:2000,2100'],
            'status' => ['sometimes', Rule::enum(ReportStatus::class)],
            'q' => ['sometimes', 'nullable', 'string', 'max:100'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:50'],
        ]);

        $query = Report::query()
            ->publiclyVisible()
            ->with(['user', 'category'])
            ->withCount('claims')
            ->search($validated['q'] ?? null)
            ->when($validated['type'] ?? null, fn ($q, $type) => $q->where('type', $type))
            ->when($validated['category_id'] ?? null, fn ($q, $id) => $q->where('category_id', $id))
            ->when($validated['campus'] ?? null, function ($q, $campus) {
                // Data lama menyimpan label ("Kampus Meruya"), data v1 menyimpan slug.
                $q->whereIn('campus_location', array_unique([
                    $campus,
                    (string) config('findumb.campuses.'.$campus, $campus),
                ]));
            })
            ->when($validated['month'] ?? null, fn ($q, $month) => $q->whereMonth('incident_date', $month))
            ->when($validated['year'] ?? null, fn ($q, $year) => $q->whereYear('incident_date', $year));

        // Kontrak: default status = 'open' kecuali klien meminta status lain.
        $status = $validated['status'] ?? ReportStatus::Open->value;
        $query->where('status', $status);

        $reports = $query
            ->latest()
            ->paginate((int) ($validated['per_page'] ?? config('findumb.report.per_page', 12)));

        $viewer = $request->user();

        return $this->paginated(
            $reports,
            fn (Report $report) => ApiPresenter::report($report, $viewer),
            'Daftar laporan berhasil diambil.',
        );
    }

    /**
     * GET /api/v1/reports/{id} (§5.2) — publik.
     */
    public function show(Request $request, Report $report): JsonResponse
    {
        $viewer = $request->user();

        if (! $report->status->isPubliclyVisible()) {
            $allowed = $viewer !== null && ($report->isOwnedBy($viewer) || $viewer->isAdmin());
            abort_unless($allowed, 404);
        }

        $report->load(['user', 'category', 'claims.claimant']);

        return $this->ok(
            ApiPresenter::report($report, $viewer, withRelations: true),
            'Detail laporan berhasil diambil.',
        );
    }

    /**
     * POST /api/v1/reports (§5.3) — butuh login + email terverifikasi.
     */
    public function store(Request $request): JsonResponse
    {
        // Kompatibilitas: klien lama mengirim `photo`/`campus_location`, kontrak memakai `image`/`campus`.
        $request->merge([
            'image' => $request->file('image') ?? $request->file('photo'),
            'campus' => $request->input('campus') ?? $this->campusSlugFromLabel($request->input('campus_location')),
        ]);

        $campuses = array_keys((array) config('findumb.campuses', []));

        $validated = $request->validate([
            'type' => ['required', Rule::enum(ReportType::class)],
            'title' => ['required', 'string', 'min:5', 'max:100'],
            'category_id' => ['required', 'integer', 'exists:item_categories,id'],
            'campus' => ['required', Rule::in($campuses)],
            'location' => ['required', 'string', 'max:150'],
            'incident_date' => ['required', 'date', 'before_or_equal:today'],
            'description' => ['required', 'string', 'min:20', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'incident_time' => ['sometimes', 'nullable', 'string', 'max:60'],
            'brand' => ['sometimes', 'nullable', 'string', 'max:120'],
            'proof_question' => ['required_if:type,found', 'nullable', 'string', 'max:255'],
            'storage_location' => ['required_if:type,found', 'nullable', Rule::in(['self', 'security_post'])],
            'security_post_id' => ['required_if:storage_location,security_post', 'nullable', 'integer', 'min:1'],
            'storage_note' => ['sometimes', 'nullable', 'string', 'max:150'],
        ], [
            'type.required' => 'Jenis laporan wajib dipilih.',
            'title.required' => 'Nama barang wajib diisi.',
            'title.min' => 'Nama barang minimal 5 karakter.',
            'title.max' => 'Nama barang maksimal 100 karakter.',
            'category_id.required' => 'Kategori barang wajib dipilih.',
            'category_id.exists' => 'Kategori barang tidak ditemukan.',
            'campus.required' => 'Lokasi kampus wajib dipilih.',
            'campus.in' => 'Lokasi kampus tidak valid.',
            'location.required' => 'Lokasi spesifik wajib diisi.',
            'incident_date.required' => 'Tanggal kejadian wajib diisi.',
            'incident_date.before_or_equal' => 'Tanggal kejadian tidak boleh melebihi hari ini.',
            'description.required' => 'Deskripsi barang wajib diisi.',
            'description.min' => 'Deskripsi minimal 20 karakter.',
            'description.max' => 'Deskripsi maksimal 1000 karakter.',
            'image.image' => 'Berkas harus berupa gambar.',
            'image.mimes' => 'Format foto harus JPG, PNG, atau WebP.',
            'image.max' => 'Ukuran foto maksimal 2 MB.',
            'proof_question.required_if' => 'Pertanyaan rahasia wajib diisi untuk laporan barang temuan.',
            'storage_location.required_if' => 'Lokasi penyimpanan barang wajib dipilih.',
            'security_post_id.required_if' => 'Pos satpam tempat penitipan wajib dipilih.',
        ]);

        $report = $this->reports->create(
            $request->user(),
            array_merge($validated, [
                'type' => ReportType::from($validated['type']),
                'campus_location' => $validated['campus'],
            ]),
            $request->file('image'),
        );

        return $this->created(
            ApiPresenter::report($report->load(['user', 'category']), $request->user()),
            'Laporan barang berhasil dibuat.',
        );
    }

    /**
     * POST /api/v1/reports/{id}/cancel (§5.4) — pemilik laporan.
     */
    public function cancel(Request $request, Report $report): JsonResponse
    {
        $validated = $request->validate([
            'reason' => ['sometimes', 'nullable', 'string', 'max:500'],
        ]);

        $cancelled = $this->reports->cancel($report, $request->user(), $validated['reason'] ?? null);

        return $this->ok(
            ApiPresenter::report($cancelled->load(['user', 'category']), $request->user()),
            'Laporan berhasil dibatalkan.',
        );
    }

    private function campusSlugFromLabel(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $campuses = (array) config('findumb.campuses', []);
        $needle = strtolower(trim($value));

        foreach ($campuses as $slug => $label) {
            if ($needle === $slug || $needle === strtolower($label)) {
                return $slug;
            }
        }

        // Nilai bebas lama seperti "Kampus Meruya Lt. 2" -> cocokkan potongan awal.
        foreach ($campuses as $slug => $label) {
            if (str_contains($needle, str_replace('kampus ', '', strtolower($label)))) {
                return $slug;
            }
        }

        return $needle;
    }
}
