<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostAnswerRequest extends FormRequest
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
            'comment' => 'required|max:10000',
        ];
    }
    public function messages()
    {
        return [
            'comment.required' => 'Поле comment обязательно к заполнению',
            'comment.max' => 'Максимальное количество символов в поле comment - 10000',
        ];
    }
}
