<?php

namespace App\Model\Donation\UseCases\Donation;

class DonationCreateDto
{
    public ?int $eventId;
    public float $amount;
    public string $username;
    public ?string $email;
    public ?string $message;
    public string $source;
}
