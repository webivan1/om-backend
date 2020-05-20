<?php

namespace App\Model\Common\Services;

use App\Model\Common\Contracts\SlugContract;
use Illuminate\Support\Str;

class Slug implements SlugContract
{
    public function transform(string $title): string
    {
        return Str::slug($title);
    }
}
