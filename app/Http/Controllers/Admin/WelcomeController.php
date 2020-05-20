<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Response;

class WelcomeController
{
    public function __invoke()
    {
        return new Response('Hello guest');
    }
}
