<?php

namespace App\Http\Requests\Dosen;

use App\Models\SeminarLiteratur;
use App\Models\SeminarProyek;
use Illuminate\Foundation\Http\FormRequest;

class  SeminarProyekUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $status = [
            SeminarProyek::STATUS_DECLINE,
            SeminarProyek::STATUS_APPROVE,
        ];
        return [
            'status'    => ['sometimes', 'nullable', 'in:' . implode(',', $status)],
            'note'      => ['required_if:status,' . SeminarProyek::STATUS_DECLINE, 'string'],
            'tanggal_seminar_proyek'      => ['required_if:status,' . SeminarProyek::STATUS_APPROVE, 'date_format:Y-m-d H:i'],
        ];
    }

    public function messages()
    {
        return [];
    }
}
