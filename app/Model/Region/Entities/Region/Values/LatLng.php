<?php

namespace App\Model\Region\Entities\Region\Values;

use App\Model\Common\Contracts\ValueContract;
use Webmozart\Assert\Assert;

class LatLng implements ValueContract
{
    private float $lat;
    private float $lng;

    public function __construct(float $lat, float $lng)
    {
        Assert::notEmpty($lat);
        Assert::notEmpty($lng);

        $this->lat = $lat;
        $this->lng = $lng;
    }

    /**
     * @return float[]
     */
    public function getValue(): array
    {
        return [$this->lat, $this->lng];
    }
}
