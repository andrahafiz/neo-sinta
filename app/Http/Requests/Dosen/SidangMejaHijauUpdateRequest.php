<?php

namespace App\Http\Requests\Dosen;

use App\Models\SidangMejaHIjau;
use Illuminate\Foundation\Http\FormRequest;

class  SidangMejaHijauUpdateRequest extends FormRequest
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
            SidangMejaHIjau::STATUS_DECLINE,
            SidangMejaHIjau::STATUS_APPROVE,
        ];
        return [
            'status'    => ['sometimes', 'nullable', 'in:' . implode(',', $status)],
            'note'      => ['required_if:status,' . SidangMejaHIjau::STATUS_DECLINE, 'string'],
        'tanggal_sidang_meja_hijau'      => ['required_if:status,' . SidangMejaHijau::STATUS_APPROVE, 'date_format:Y-m-d H:i'],
        ];
    }

    public function messages()
    {
        return [];
    }
}
