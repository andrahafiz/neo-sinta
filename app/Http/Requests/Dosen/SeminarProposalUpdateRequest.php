<?php

namespace App\Http\Requests\Dosen;

use App\Models\SeminarProposal;
use Illuminate\Foundation\Http\FormRequest;

class  SeminarProposalUpdateRequest extends FormRequest
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
            SeminarProposal::STATUS_DECLINE,
            SeminarProposal::STATUS_APPROVE,
        ];
        return [
            'status'    => ['sometimes', 'nullable', 'in:' . implode(',', $status)],
            'note'      => ['required_if:status,' . SeminarProposal::STATUS_DECLINE, 'string'],
        ];
    }

    public function messages()
    {
        return [];
    }
}
