<?php

namespace App\Model\Region\UseCases\Region;

class RegionCreateDto
{
    public string $label;
    public ?string $slug;
    public float $lat;
    public float $lng;
    public int $distance;
    public string $timezone;
}
