<?php

namespace App\Http\Requests;

use App\Enums\ReportType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', Rule::enum(ReportType::class)],
            'title' => ['required', 'string', 'min:3', 'max:150'],
            'category_id' => ['nullable', 'integer', 'exists:item_categories,id'],
            'description' => ['nullable', 'string', 'max:2000'],
            'campus_location' => ['nullable', 'string', 'max:120'],
            'location' => ['nullable', 'string', 'max:180'],
            'brand' => ['nullable', 'string', 'max:120'],
            'storage_note' => ['nullable', 'string', 'max:150'],
            'incident_date' => ['nullable', 'date', 'before_or_equal:today'],
            'incident_time' => ['nullable', 'string', 'max:60'],
            // Laporan temuan yang dibuat dari laporan hilang (?ref=ID).
            'reference_report_id' => ['nullable', 'integer', 'exists:reports,id'],
            // DEC-021 — 1 foto, maks 2 MB, JPEG/PNG/WebP.
            'photo' => [
                'nullable',
                'image',
                'mimes:'.implode(',', config('findumb.report.photo_mimes', ['jpg', 'jpeg', 'png', 'webp'])),
                'max:'.(int) config('findumb.report.photo_max_kb', 2048),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'type.required' => 'Jenis laporan wajib dipilih.',
            'title.required' => 'Nama barang wajib diisi.',
            'title.min' => 'Nama barang minimal 3 karakter.',
            'incident_date.before_or_equal' => 'Tanggal kejadian tidak boleh melebihi hari ini.',
            'photo.image' => 'Berkas harus berupa gambar.',
            'photo.mimes' => 'Format foto harus JPG, PNG, atau WebP.',
            'photo.max' => 'Ukuran foto maksimal '.(int) config('findumb.report.photo_max_kb', 2048).' KB.',
        ];
    }
}
