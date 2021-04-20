<?php

namespace App\DataFixtures\Faker\Provider;

use Faker\Provider\Base as BaseProvider;

final class StatusProvider extends BaseProvider
{
    public static function status()
    {
        return self::randomElement([
            'PENDING',
            'VALIDATED',
            'DENIED',
        ]);
    }

    public static function orderStatus()
    {
        return self::randomElement([
            'ORDERED',
            'IN STOCK',
            'OUT OF STOCK',
        ]);
    }
}
