<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        $report = $this->route('report');

        return $this->user() !== null && $report && $report->isOwnedBy($this->user());
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'min:3', 'max:150'],
            'category_id' => ['sometimes', 'nullable', 'integer', 'exists:item_categories,id'],
            'description' => ['sometimes', 'nullable', 'string', 'max:2000'],
            'campus_location' => ['sometimes', 'nullable', 'string', 'max:120'],
            'location' => ['sometimes', 'nullable', 'string', 'max:180'],
            'brand' => ['sometimes', 'nullable', 'string', 'max:120'],
            'storage_note' => ['sometimes', 'nullable', 'string', 'max:150'],
            'incident_date' => ['sometimes', 'nullable', 'date', 'before_or_equal:today'],
            'incident_time' => ['sometimes', 'nullable', 'string', 'max:60'],
            'photo' => [
                'sometimes',
                'nullable',
                'image',
                'mimes:'.implode(',', config('findumb.report.photo_mimes', ['jpg', 'jpeg', 'png', 'webp'])),
                'max:'.(int) config('findumb.report.photo_max_kb', 2048),
            ],
        ];
    }
}
