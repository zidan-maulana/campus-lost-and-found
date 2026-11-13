<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\Report;
use App\Services\ClaimService;
use App\Services\HandoverService;
use App\Support\Api\ApiPresenter;
use App\Support\Api\V1Response;
use App\Support\FindumbStorage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Modul klaim & serah terima — docs/API_CONTRACT.md §6.
 */
class ClaimController extends Controller
{
    use V1Response;

    public function __construct(
        private readonly ClaimService $claims,
        private readonly HandoverService $handover,
    ) {
    }

    /**
     * POST /api/v1/reports/{report}/claims (§6.1)
     */
    public function store(Request $request, Report $report): JsonResponse
    {
        $validated = $request->validate([
            'proof_answer' => ['required', 'string', 'min:10', 'max:1000'],
            'claim_reason' => ['required', 'string', 'min:10', 'max:1000'],
            'proof_attachment' => ['sometimes', 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'proof_answer.required' => 'Jawaban atas pertanyaan rahasia wajib diisi.',
            'proof_answer.min' => 'Jawaban verifikasi minimal 10 karakter.',
            'claim_reason.required' => 'Alasan klaim wajib diisi.',
            'claim_reason.min' => 'Alasan klaim minimal 10 karakter.',
            'proof_attachment.image' => 'Bukti tambahan harus berupa gambar.',
            'proof_attachment.mimes' => 'Format bukti tambahan harus JPG, PNG, atau WebP.',
            'proof_attachment.max' => 'Ukuran bukti tambahan maksimal 2 MB.',
        ]);

        $attachmentPath = $request->hasFile('proof_attachment')
            ? $request->file('proof_attachment')->store('claims', FindumbStorage::disk())
            : null;

        $claim = $this->claims->submit($request->user(), $report, [
            'proof_answer' => $validated['proof_answer'],
            'claim_reason' => $validated['claim_reason'],
            'proof_attachment' => $attachmentPath,
        ]);

        return $this->created(
            ApiPresenter::claim($claim, $request->user()),
            'Klaim berhasil diajukan. Menunggu respons penemu.',
        );
    }

    /**
     * POST /api/v1/claims/{claim}/accept (§6.2) — penemu memilih klaim.
     */
    public function accept(Request $request, Claim $claim): JsonResponse
    {
        $accepted = $this->claims->selectForHandover($claim, $request->user());

        return $this->ok(
            ApiPresenter::claim($accepted, $request->user()),
            'Klaim disetujui. Silakan atur serah terima melalui chat.',
        );
    }

    /**
     * POST /api/v1/claims/{claim}/reject (§6.3)
     */
    public function reject(Request $request, Claim $claim): JsonResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'min:10', 'max:500'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
            'rejection_reason.min' => 'Alasan penolakan minimal 10 karakter.',
        ]);

        $rejected = $this->claims->reject($claim, $request->user(), $validated['rejection_reason']);

        return $this->ok(
            ApiPresenter::claim($rejected, $request->user()),
            'Klaim ditolak.',
        );
    }

    /**
     * POST /api/v1/claims/{claim}/handover/cancel (§6.4)
     */
    public function handoverCancel(Request $request, Claim $claim): JsonResponse
    {
        $validated = $request->validate([
            'cancellation_reason' => ['required', 'string', 'min:10', 'max:500'],
        ], [
            'cancellation_reason.required' => 'Alasan pembatalan serah terima wajib diisi.',
            'cancellation_reason.min' => 'Alasan pembatalan minimal 10 karakter.',
        ]);

        $user = $request->user();

        $cancelled = $claim->isFinder($user)
            ? $this->claims->cancelByFinder($claim, $user, $validated['cancellation_reason'])
            : $this->claims->cancelByClaimant($claim, $user, $validated['cancellation_reason']);

        return $this->ok(
            ApiPresenter::claim($cancelled, $user),
            'Serah terima dibatalkan. Laporan kembali dibuka.',
        );
    }

    /**
     * POST /api/v1/claims/{claim}/handover/confirm (§6.5)
     */
    public function handoverConfirm(Request $request, Claim $claim): JsonResponse
    {
        $result = $this->handover->confirm($claim, $request->user());

        return $this->ok(
            array_merge(
                ApiPresenter::claim($result['claim'], $request->user()),
                ['completed' => (bool) $result['completed']],
            ),
            $result['completed']
                ? 'Serah terima selesai. Laporan telah ditutup.'
                : 'Konfirmasi tercatat. Menunggu konfirmasi pihak lain.',
        );
    }
}
