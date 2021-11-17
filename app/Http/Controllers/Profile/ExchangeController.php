<?php

namespace App\Http\Controllers\Profile;

use App\Exchange;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\AddOrderRequest;
use App\Models\Balance;
use App\Models\LimitOrder;
use App\Models\Operation;
use Illuminate\Support\Facades\Auth;

class ExchangeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('profile.exchange.index', [
//            'assets' => Asset::all(),
            'balances' => Balance::where('user_id', $user->id)->with('asset')->get(),
            'user' => $user,
        ]);
    }

    public function addOrder(AddOrderRequest $request)
    {
        // TODO check balance
        $validated = $request->validated();

        if ((int)$validated['order_type'] === Exchange::ORDER_TYPE_LIMIT) {
            LimitOrder::create($validated + [
                'user_id' => Auth::user()->id,
                'qty_left' => $validated['qty'],
                'status' => LimitOrder::STATUS_PENDING,
            ]);
        } else {
            Operation::create($validated + [
                'user_id' => Auth::user()->id,
            ]);
        }

        return back()->with('message', 'Баланс добавлен!');
    }
}
