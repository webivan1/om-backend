<?php

namespace App\Model\Event\UseCases\EventCreate;

class EventCreateDto
{
    public string $title;
    public string $description;
    public int $region;
    public string $startAt;
    public int $duration;
}
