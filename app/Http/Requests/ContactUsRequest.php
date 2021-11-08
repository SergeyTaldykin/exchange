<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactUsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|max:120',
            'last_name' => 'required|max:120',
            'email' => 'required|email',
            'subject' => 'required|max:200',
            'message' => 'required|max:20000',
        ];
    }
}
