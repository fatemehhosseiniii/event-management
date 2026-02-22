<?php

namespace App\Http\Requests\Api\V1\Panel;

use App\Enums\ReservConfirmed;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

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
        return [
            'is_confirmed'=>['required',Rule::in([ReservConfirmed::Confirmed->value,ReservConfirmed::RejectedConfirmed->value])]
        ];
    }

    public function attributes(): array
    {
        return [
            'is_confirmed'=>__('app.reservs.is_confirmed'),
        ];
    }
}
