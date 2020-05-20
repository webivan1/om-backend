<?php

namespace App\Model\Common\Services;

use App\Model\Common\Contracts\TokenizerContract;
use Ramsey\Uuid\Uuid;

class Tokenizer implements TokenizerContract
{
    public function generateRandomString(): string
    {
        return Uuid::uuid4()->toString();
    }

    public function generateApiToken(): string
    {
        return Uuid::uuid1()->toString();
    }
}
