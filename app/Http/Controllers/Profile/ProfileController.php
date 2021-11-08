<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        var_dump(true);
        exit();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);

        var_dump($validated);
        exit();
        foreach ($validated as $key => $value) {
            Auth::user()->$key = $value;
        }

        Auth::user()->save();

        return $this->index();
    }
}
