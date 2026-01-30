<?php

namespace App\Http\Requests\Admin;

use App\Rules\ImageMimeTypeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'image' => $this->hasFile('image') ? new ImageMimeTypeRule : '',
            'role_id' => 'required',
            'username' => [
                'required',
                'max:255',
                Rule::unique('admins')->ignore($this->id),
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                Rule::unique('admins')->ignore($this->id),
            ],
            'first_name' => 'required',
            'last_name' => 'required',
        ];
    }
}
