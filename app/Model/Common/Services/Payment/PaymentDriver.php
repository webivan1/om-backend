<?php

namespace App\Model\Common\Services\Payment;

use App\Model\Common\Services\Payment\Contracts\DriverContract;
use App\Model\Common\Services\Payment\Drivers\Paypal\Paypal;
use App\Model\Common\Services\Payment\Drivers\Tinkoff\Tinkoff;
use Webmozart\Assert\Assert;

class PaymentDriver
{
    public array $drivers = [
        'paypal' => Paypal::class,
        'tinkoff' => Tinkoff::class
    ];

    private DriverContract $driver;

    public function __construct(string $driver)
    {
        Assert::inArray($driver, array_keys($this->drivers));

        $this->driver = new $this->drivers[$driver];
    }

    public function getDriver(): DriverContract
    {
        return $this->driver;
    }

    public static function allowDrivers(): array
    {
        $ref = new \ReflectionClass(self::class);
        $instance = $ref->newInstanceWithoutConstructor();
        return array_keys($instance->drivers);
    }
}
