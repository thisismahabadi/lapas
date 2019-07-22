<?php

namespace Modules\V1\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

 /**
  * @version 1.0.0
  */
class RegisterUser extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'A name is required',
            'name.string' => 'A name is string',
            'name.max' => 'A name max character is 255',
            'email.required'  => 'A email is required',
            'email.email'  => 'A email must be correct format',
            'email.unique'  => 'A email must be unique',
            'password.required'  => 'A password is required',
            'password.string'  => 'A password is string',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
