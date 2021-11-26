<?php

namespace App\Http\Controllers\Profile;

use App\Exchange;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\AddOrderRequest;
use App\Models\Balance;
use App\Models\FilledOrder;
use App\Models\Order;
use App\Models\Pair;
use Illuminate\Support\Facades\Auth;

class ExchangeController extends Controller
{
    public function index(Pair $pair)
    {
        if (empty($pair->id)) {
            $pair = Pair::query()->first();
        }

        $user = Auth::user();

        $filledOrders = FilledOrder::query()
            ->where('pair_id', $pair->id)
            ->orderBy('id', 'DESC')
            ->limit(40)
            ->get();

        return view('profile.exchange.index', [
            'qtyLeft' => Balance::format(Balance::getByUserAndAsset($user, $pair->leftAsset)->getFreeVolume()),
            'qtyRight' => Balance::format(Balance::getByUserAndAsset($user, $pair->rightAsset)->getFreeVolume()),
            'buyLimitOrders' => Order::getOrderBook($pair, Exchange::OPERATION_TYPE_BUY, 10),
            'filledOrders' => $filledOrders,
            'pair' => $pair,
            'sellLimitOrders' => Order::getOrderBook($pair, Exchange::OPERATION_TYPE_SELL, 10),
            'user' => $user,
            'usersOrders' => Order::getByPairAndUser($pair, $user),
        ]);
    }

    public function addOrder(AddOrderRequest $request)
    {
        // TODO check balance
        $validated = $request->validated();

        Order::create($validated + [
            'user_id' => Auth::user()->id,
            'status' => Order::STATUS_PENDING,
        ]);

        return back()->with('message', 'Баланс добавлен!');
    }

    public function getPairsList()
    {
        return view('profile.exchange.pairs_list', [
            'pairsList' => Pair::all(),
        ]);
    }
}
