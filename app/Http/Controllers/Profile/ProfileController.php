<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index', [
            'user' => $user = Auth::user(),
            'profilePhoto' => $user->getAvatarPath() ?: 'https://bootdey.com/img/Content/avatar/avatar6.png'
        ]);
    }

    public function update(UserProfileRequest $request)
    {
        $validated = $request->validated();

        foreach ($validated as $key => $value) {
            Auth::user()->$key = $value;
        }

        Auth::user()->save();

        return response()->json(['message' => 'ok']);
    }

    public function changeAvatar(Request $request)
    {
        if ($request->hasFile('avatar')) {
            if ($request->file('avatar')->isValid()) {
                $validated = $request->validate([
                    'image' => 'mimes:jpeg,png|max:2048|dimensions:min_width=100,min_height=200',
                ]);

                $extension = $request->avatar->extension();

                $name = sha1(Auth::user()->id . uniqid()) . '.' . $extension;

                $request->avatar->storeAs('/public/images/avatars/' . substr($name, 0, 4), $name);

                Auth::user()->avatar = $name;
                Auth::user()->save();

                return back();
            }
        }

        abort(500, 'Could not upload image :(');
    }
}
