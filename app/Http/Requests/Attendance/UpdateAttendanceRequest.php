<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required'|'integer'],
            'time_start' => ['required'|'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format')],
            'time_end' => ['date_format:' . config('panel.date_format') . ' ' . config('panel.time_format')|'nullable'],
        ];
    }
}
