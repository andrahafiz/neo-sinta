<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;

class SeminarLiteraturStoreRequest extends FormRequest
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

        return [
            'file_ppt'               => ['required', 'file'],
            'file_literatur'         => ['required', 'array'],
            'file_literatur.*'       => ['required', 'file'],
        ];
    }

    public function messages()
    {
        return [];
    }
}
