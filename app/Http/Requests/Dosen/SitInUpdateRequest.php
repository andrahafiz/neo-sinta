<?php

namespace App\Http\Requests\Dosen;

use App\Models\Sitin;
use Illuminate\Foundation\Http\FormRequest;

class SitInUpdateRequest extends FormRequest
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
            Sitin::STATUS_IN_PROGRESS,
            Sitin::STATUS_DECLINE,
            Sitin::STATUS_APPROVE,
            Sitin::STATUS_CONFIRM,
        ];

        return [
            'date' => ['sometimes'],
            'check_in' => ['sometimes'],
            'check_out' => ['sometimes', 'after:check_in'],
            'check_in_proof' => ['sometimes', 'file'],
            'check_out_proof' => ['sometimes', 'file'],
            'check_out_document' => ['sometimes', 'file'],
            'status' => ['sometimes', 'in:' . implode(',', $status)],
            'mahasiswas_id' => ['sometimes', 'exists:mahasiswas,id'],
            'approval_by' =>  ['sometimes', 'exists:lecture,id'],
        ];
    }

    public function messages()
    {
        return [];
    }
}
