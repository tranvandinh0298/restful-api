<?php

namespace App\Http\Requests\User;

use App\Models\User;
use App\Traits\ApiValidation;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'email|unique:users,email,'.$this->id,
            'password' => 'min:6|confirmed',
            'admin' => 'in:'.User::ADMIN_USER.','.User::REGULAR_USER,
        ];
    }
}
