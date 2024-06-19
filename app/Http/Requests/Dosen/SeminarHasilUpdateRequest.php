<?php

namespace App\Http\Requests\Dosen;

use App\Models\SeminarHasil;
use Illuminate\Foundation\Http\FormRequest;

class  SeminarHasilUpdateRequest extends FormRequest
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
            SeminarHasil::STATUS_DECLINE,
            SeminarHasil::STATUS_APPROVE,
        ];

        return [
            'status'    => ['sometimes', 'nullable', 'in:' . implode(',', $status)],
            'note'      => ['required_if:status,' . SeminarHasil::STATUS_DECLINE, 'string'],
        ];
    }

    public function messages()
    {
        return [];
    }
}
