<?php

namespace App\Helpers;

use Carbon\Carbon;

final class DateTimeSetupUsingRequest {

    public static function setupDateTime($time): Carbon {
        config(['app.timezone' => $time['timezone']]);
        date_default_timezone_set($time['timezone']);

        $requestDate = $time['date'];
        $requestTime= $time['current_time'];

        return Carbon::parse("$requestDate $requestTime", $time['timezone']);
    }
}
