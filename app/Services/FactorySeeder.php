<?php

namespace App\Services;

use Faker\Generator;
use Illuminate\Support\Collection;

class FactorySeeder
{
    public static array $data = [];

    public function define(string $key, \Closure $handler): void
    {
        self::$data[$key] = $handler;
    }

    public function factory(string $key, int $amount = 1): Collection
    {
        if (!array_key_exists($key, self::$data)) {
            throw new \Exception('Undefined factory ' . $key);
        }

        $handler = self::$data[$key];
        $collection = collect();

        for ($i = 1; $i <= $amount; $i++) {
            $faker = app(Generator::class);
            $collection->add(call_user_func($handler, $faker));
        }

        return $collection;
    }

    public static function new(): self
    {
        return new self;
    }
}
