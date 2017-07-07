<?php
/**
 * Created by PhpStorm.
 * User: omitobisam
 * Date: 07/07/2017
 * Time: 11:52
 */

namespace Carbonate;


use Carbon\Carbon;

class Carbonate extends Carbon
{
    public function __construct($time = null, $tz = null)
    {
        date_default_timezone_set('Europe/Helsinki');
        parent::__construct($time, $tz);
    }

    public static function thisMonth($end = false)
    {
        return $end ? self::today()->copy()->endOfMonth() : self::today()->startOfMonth();
    }
}