<?php

namespace App\Http\Requests\User;

use Orion\Http\Requests\Request;
//use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email:filter|max:255',
            'selected_roles' => 'max:255|array',
        ];
    }
}
