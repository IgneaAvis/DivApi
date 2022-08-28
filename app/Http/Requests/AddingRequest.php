<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'email' => 'required|email',
            'message' => 'required|max:10000'
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Поле name обязательно к заполнению',
            'email.required' => 'Поле email обязательно к заполнению',
            'message.required' => 'Поле message обязательно к заполнению',
            'email.email' => 'Поле email должно быть примерно таким test@gmail.com',
            'name.max' => 'Максимальное количество символов в поле name - 255',
            'message.max' => 'Максимальное количество символов в поле message - 10000',
        ];
    }
}
