<?php

namespace App\Http\Requests\Profile;

use App\Exchange;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'operation_type' => ['required', Rule::in([Exchange::OPERATION_TYPE_BUY, Exchange::OPERATION_TYPE_SELL])],
            'order_type' => ['required', Rule::in([Exchange::ORDER_TYPE_LIMIT, Exchange::ORDER_TYPE_MARKET])],
            'pair_id' => ['required', 'exists:pairs,id'],
            'price' => ['required', 'numeric'],
            'qty' => ['required', 'numeric'],
        ];
    }
}
