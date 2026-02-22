<?php

namespace App\Http\Requests\Api\V1\Panel;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
            'title'=>['required','string','max:80','unique:events,title,'.(request()->route('event')?->id ?? null)],
            'description'=>['nullable','string','max:250'],
            'capacity'=>['required','integer','min:1'],
            'start_date'=>['nullable','date_format:Y-m-d H:i'],
            'end_date'=>['nullable','date_format:Y-m-d H:i','after:start_date'],
        ];
    }

    public function attributes():array
    {
        return [
            'title'=>__('app.events.title'),
            'description'=>__('app.events.description'),
            'capacity'=>__('app.events.capacity'),
            'start_date'=>__('app.events.start_date'),
            'end_date'=>__('app.events.end_date'),
        ];
    }
}
