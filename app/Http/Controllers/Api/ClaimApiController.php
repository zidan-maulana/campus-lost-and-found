<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\Report;
use App\Services\ClaimService;
use App\Services\HandoverService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClaimApiController extends Controller
{
    public function __construct(
        private readonly ClaimService $claims,
        private readonly HandoverService $handover,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $claims = Claim::query()
            ->where('claimant_id', $user->id)
            ->orWhereHas('report', fn ($q) => $q->where('user_id', $user->id))
            ->with(['report.category', 'report.user', 'claimant', 'confirmations'])
            ->latest()
            ->get();

        return response()->json([
            'data' => $claims->map(fn (Claim $c) => array_merge($c->toUiArray($user), [
                'report' => $c->report?->toUiArray($user),
            ])),
        ]);
    }

    public function store(Request $request, Report $report): JsonResponse
    {
        $claim = $this->claims->submit($request->user(), $report);

        return response()->json([
            'message' => 'Klaim berhasil diajukan. Menunggu respons penemu.',
            'data' => $claim->toUiArray($request->user()),
        ], 201);
    }

    public function reject(Request $request, Claim $claim): JsonResponse
    {
        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $rejected = $this->claims->reject($claim, $request->user(), $validated['reason'] ?? null);

        return response()->json([
            'message' => 'Klaim ditolak.',
            'data' => $rejected->toUiArray($request->user()),
        ]);
    }

    public function cancel(Request $request, Claim $claim): JsonResponse
    {
        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $user = $request->user();

        // Penemu membatalkan serah terima wajib menyertakan alasan (DEC-018).
        $cancelled = $claim->isFinder($user)
            ? $this->claims->cancelByFinder($claim, $user, (string) ($validated['reason'] ?? ''))
            : $this->claims->cancelByClaimant($claim, $user, $validated['reason'] ?? null);

        return response()->json([
            'message' => 'Klaim dibatalkan.',
            'data' => $cancelled->toUiArray($user),
        ]);
    }

    public function select(Request $request, Claim $claim): JsonResponse
    {
        $selected = $this->claims->selectForHandover($claim, $request->user());

        return response()->json([
            'message' => 'Klaim disetujui. Silakan atur serah terima melalui chat.',
            'data' => $selected->toUiArray($request->user()),
        ]);
    }

    public function confirmHandover(Request $request, Claim $claim): JsonResponse
    {
        $result = $this->handover->confirm($claim, $request->user());

        return response()->json([
            'message' => $result['completed']
                ? 'Serah terima selesai. Laporan telah ditutup.'
                : 'Konfirmasi tercatat. Menunggu konfirmasi pihak lain.',
            'completed' => $result['completed'],
            'data' => $result['claim']->toUiArray($request->user()),
        ]);
    }
}
