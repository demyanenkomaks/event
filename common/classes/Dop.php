<?php

namespace common\classes;


class Dop
{
    public static $formatDateTimeDb = 'php:Y-m-d H:i:s';

    public static function dateTime($dateTime = null) {
        return $dateTime === null ? gmdate('Y-m-d H:i:s') : gmdate('Y-m-d H:i:s', $dateTime);
    }
}
