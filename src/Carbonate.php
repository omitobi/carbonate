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

    public function getDatesOfDaysInMonth(array $days)
    {
        $start_of_month = $this->copy()->startOfMonth();
        $end_of_month = $start_of_month->copy()->endOfMonth();

        $the_dates = [];

        $c = 0;
        $start_of_month->diffInDaysFiltered(function (Carbonate $dt) use (&$the_dates, $days, &$c){
            if ( in_array($dt->format('l'), $days) ) {
                $the_dates[$c]['name'] = $dt->format('l');
                $the_dates[$c]['number'] = $dt->dayOfWeek;
                $the_dates[$c]['date'] = $dt;
                $c++;
            }
        }, $end_of_month);

        return $the_dates;
    }
}