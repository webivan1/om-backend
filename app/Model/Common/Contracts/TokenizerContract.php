<?php

namespace App\Model\Common\Contracts;

interface TokenizerContract
{
    public function generateRandomString(): string;

    public function generateApiToken(): string;
}
