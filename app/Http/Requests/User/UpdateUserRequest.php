<?php

namespace App\Http\Requests\User;

use App\Rules\PermissionsExist;
use App\Rules\RolesExist;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'sometimes'],
            'username' => ['sometimes', 'string', Rule::unique('users')->ignore($this->route('user')), 'required'],
            'password' => ['sometimes', Password::defaults()],
            'email' => ['sometimes','string', Rule::unique('users')->ignore($this->route('user')), 'required'],
            'roles' => ['array', 'sometimes', new RolesExist()],
            'permissions' => ['array', 'sometimes', new PermissionsExist()],
        ];
    }
}
