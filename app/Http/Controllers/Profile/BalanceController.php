<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\BalanceRequest;
use App\Models\Asset;
use App\Models\Balance;
use Illuminate\Support\Facades\Auth;

class BalanceController extends Controller
{
    public function index()
    {
        return view('profile.balance', [
            'assets' => Asset::all(),
            'balances' => Balance::where('user_id', Auth::user()->id)->with('asset')->get(),
            'user' => $user = Auth::user(),
            'profilePhoto' => $user->getAvatarPath() ?: 'https://bootdey.com/img/Content/avatar/avatar6.png',
        ]);
    }

    public function addBalance(BalanceRequest $request)
    {
        $validated = $request->validated();

        if (!$balance = Auth::user()->balances()->where('asset_id', $validated['asset_id'])->first()) {
            $balance = new Balance();
            $balance->asset_id = $validated['asset_id'];
            $balance->volume = 0.0;
            $balance->user()->associate(Auth::user());
        }

        $balance->volume += $validated['volume'];

        $balance->save();

        return back()->with('message', 'Баланс добавлен!');
    }
}
