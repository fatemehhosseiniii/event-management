<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReservRequest extends FormRequest
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
        $reservId = $this->route('reserv')?->id ?? null;
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');
        
        return [
            'event_code' => [$isUpdate ? 'sometimes' : 'required','uuid','exists:events,uuid'],
        ];
    }

    public function attributes(): array
    {
        return [
            'event_code' => __('app.reservs.event'),
        ];
    }
}

