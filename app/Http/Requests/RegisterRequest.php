<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|string|max:191|min:3',
            'email' => 'required|email|string|lowercase|max:191|unique:users,email',
            'username' => 'required|string|min:3|max:191|unique:users,username',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required_if:type,student|string|min:8|max:15|regex:/[A-Z]/|regex:/[@$!%*#?&]/',
            'university_id' => 'required_if:type,student,admin,admin_fac,professor|exists:universities,id',
            'faculty_id' => 'required_if:type,student,admin_fac|exists:faculties,id',
            'promotion_id' => 'required_if:type,student|exists:promotions,id',
            'type' => 'required|string|in:student,admin,admin_fac,professor,super_admin',
        ];
    }

    public function message() : array
    {
        return [
            'university_id.required_if' => 'Le Champ Université est obligatoire',
            'faculty_id.required_if' => 'Le Champ Faculté est obligatoire',
            'promotion_id.required_if' => 'Le Champ Promotion est obligatoire',
            ];
    }
}
