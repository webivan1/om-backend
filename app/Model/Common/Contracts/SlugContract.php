<?php

namespace App\Model\Common\Contracts;

interface SlugContract
{
    public function transform(string $title): string;
}
