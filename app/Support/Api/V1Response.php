<?php

namespace App\Support\Api;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

/**
 * Pembungkus response API v1 sesuai docs/API_CONTRACT.md §2.2–2.4.
 *
 * Envelope sukses : { success, message, data, meta }
 * Envelope list   : { success, message, data, pagination, meta }
 * Envelope error  : { success: false, message, errors, code }
 */
trait V1Response
{
    protected function ok(mixed $data = null, string $message = 'Permintaan berhasil diproses.', array $meta = []): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => array_merge($this->envelopeMeta(), $meta),
        ]);
    }

    protected function created(mixed $data = null, string $message = 'Data berhasil dibuat.'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => $this->envelopeMeta(),
        ], 201);
    }

    /**
     * @param  callable(mixed): array<string, mixed>  $mapper
     */
    protected function paginated(
        LengthAwarePaginator $paginator,
        callable $mapper,
        string $message = 'Permintaan berhasil diproses.',
        array $meta = [],
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => collect($paginator->items())->map($mapper)->values()->all(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
                'has_next_page' => $paginator->hasMorePages(),
                'has_prev_page' => $paginator->currentPage() > 1,
            ],
            'meta' => array_merge($this->envelopeMeta(), $meta),
        ]);
    }

    /**
     * @param  array<string, array<int, string>>  $errors
     */
    protected function fail(string $message, array $errors = [], int $code = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => (object) $errors,
            'code' => $code,
        ], $code);
    }

    /**
     * @return array<string, string>
     */
    private function envelopeMeta(): array
    {
        return [
            'timestamp' => now()->toIso8601String(),
            'version' => '1.0',
        ];
    }
}
