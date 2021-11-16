<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\BalanceRequest;
use App\Models\Asset;
use App\Models\Balance;
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

    public function addOrder(BalanceRequest $request)
    {
        $validated = $request->validated();

//        if (!$balance = Auth::user()->balances()->where('asset_id', $validated['asset_id'])->first()) {
//            $balance = new Balance();
//            $balance->asset_id = $validated['asset_id'];
//            $balance->volume = 0.0;
//            $balance->user()->associate(Auth::user());
//        }
//
//        $balance->volume += $validated['volume'];
//
//        $balance->save();





        return back()->with('message', 'Баланс добавлен!');
    }
}
