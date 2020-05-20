<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Requests\Admin\Auth\RegisterRequest;

class RegisterController
{
    public function form()
    {
        return view('admin.auth.register');
    }

    public function handle(RegisterRequest $request)
    {

    }
}
