<?php

namespace App\Http\Requests\Profile;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

class CancelOrderRequest extends FormRequest
{
    private ?Order $order;

    public function authorize()
    {
        $this->order = Order::find($this->request->get('order_id'));

        return $this->order && $this->user()->id === $this->order->user_id; // todo $this->user()->can($order);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }
}
