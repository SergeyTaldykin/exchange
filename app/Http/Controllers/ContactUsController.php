<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUsRequest;
use App\Models\ContactUs;

class ContactUsController extends Controller
{
    public function store(ContactUsRequest $request)
    {
        $validated = $request->validated();

        ContactUs::create($validated);

        return back()->with('contact_us_message', 'Ваше обращение успешно добавлено!');
    }
}
