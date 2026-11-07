<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookmarkApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $reports = Report::query()
            ->whereHas('savedBy', fn ($q) => $q->where('users.id', $user->id))
            ->with(['user', 'category'])
            ->latest()
            ->get();

        return response()->json([
            'data' => $reports->map(fn (Report $r) => $r->toUiArray($user)),
        ]);
    }

    public function toggle(Request $request, Report $report): JsonResponse
    {
        $user = $request->user();

        $exists = DB::table('saved_reports')
            ->where('user_id', $user->id)
            ->where('report_id', $report->id)
            ->exists();

        if ($exists) {
            DB::table('saved_reports')
                ->where('user_id', $user->id)
                ->where('report_id', $report->id)
                ->delete();

            return response()->json([
                'message' => 'Laporan dihapus dari daftar tersimpan.',
                'saved' => false,
            ]);
        }

        DB::table('saved_reports')->insert([
            'user_id' => $user->id,
            'report_id' => $report->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Laporan disimpan.',
            'saved' => true,
        ], 201);
    }
}
