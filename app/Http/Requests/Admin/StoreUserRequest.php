<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Vinkla\Hashids\Facades\Hashids;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'  => ['required', 'string', 'min:8'],
            // Stop HR or others from choosing a role not real
            'role'      => ['required', 'string', 'in:owner,branch_manager,cashier'],
            'branch_id' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->filled('branch_id')) {
            $decode = Hashids::decode($this->branch_id);

            if (!empty($decode)) {
                $this->merge([
                    'branch_id'=>$decode[0],
                ]);
            } else{
                $this->merge([
                    'branch_id'=>'invalid_hashid',
                ]);
            }
        }
    }

    public function messages()
    {
        return [
            'email.unique' => 'This email address is already registered in the system.',
            'role.in'      => 'The selected role is invalid. Allowed roles are: owner, branch_manager, cashier.',
            'password.min' => 'The password must be at least 8 characters long for corporate security.',
        ];
    }
}
