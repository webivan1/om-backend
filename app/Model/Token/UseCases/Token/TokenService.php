<?php

namespace App\Model\Token\UseCases\Token;

use App\Model\Common\Contracts\TokenizerContract;
use App\Model\Token\Entities\Token\Token;
use App\Model\Token\Entities\Token\Values\Uuid;
use App\Model\User\Entities\User\User;
use Doctrine\ORM\EntityManagerInterface;

class TokenService
{
    private TokenizerContract $tokenizer;
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em, TokenizerContract $tokenizer)
    {
        $this->tokenizer = $tokenizer;
        $this->em = $em;
    }

    public function createToken(User $user): Token
    {
        $uuid = new Uuid($this->tokenizer->generateApiToken());

        $token = Token::create($user, $uuid);

        $this->em->persist($token);
        $this->em->flush();

        return $token;
    }

    // @todo Clear all tokens (leave from all devices)
}
