<?php

namespace App\Http\Controllers\Api;

use App\Enums\AbuseReportStatus;
use App\Http\Controllers\Controller;
use App\Models\AbuseReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Pelaporan penyalahgunaan oleh pengguna (PR-08, FR-11).
 */
class AbuseReportApiController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'target_type' => ['required', Rule::in(config('findumb.abuse.target_types', ['report', 'chat_message']))],
            'target_id' => ['required', 'integer', 'min:1'],
            'reason' => ['required', 'string', 'max:150'],
            'details' => ['nullable', 'string', 'max:1000'],
        ]);

        $abuse = AbuseReport::create([
            'reporter_id' => $request->user()->id,
            'target_type' => $validated['target_type'],
            'target_id' => $validated['target_id'],
            'reason' => $validated['reason'],
            'details' => $validated['details'] ?? null,
            'status' => AbuseReportStatus::Pending,
        ]);

        return response()->json([
            'message' => 'Laporan penyalahgunaan terkirim. Tim moderasi akan meninjau.',
            'data' => ['id' => (string) $abuse->id],
        ], 201);
    }
}
