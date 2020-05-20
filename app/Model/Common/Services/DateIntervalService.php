<?php

namespace App\Model\Common\Services;

use Illuminate\Support\Carbon;

class DateIntervalService
{
    public static function getHours(\DateTimeInterface $from, \DateTimeInterface $to): int
    {
        $interval = new \DateInterval('PT1H');
        $periods = new \DatePeriod($from, $interval, $to);
        return iterator_count($periods);
    }
}
