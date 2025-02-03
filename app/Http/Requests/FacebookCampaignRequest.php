<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FacebookCampaignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                $this->isMethod('post') ? 'required' : 'sometimes',
                'string'
            ],
            'objective' => [
                $this->isMethod('post') ? 'required' : 'sometimes',
                'string'
            ],
            'start_date' => [
                $this->isMethod('post') ? 'required' : 'sometimes',
                'date',
                'before:end_date'
            ],
            'end_date' => [
                $this->isMethod('post') ? 'required' : 'sometimes',
                'date',
                'after_or_equal:start_date'
            ],
            'status' => [
                $this->isMethod('post') ? 'required' : 'sometimes',
                'in:ACTIVE,PAUSED,ARCHIVE'
            ]
        ];
    }
}
