<?php
namespace Carbonate;


use Closure;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Collection;

class Carbonate extends Carbon
{
    public function __construct($time = null, $tz = 'Europe/Helsinki')
    {
//        date_default_timezone_set('Europe/Helsinki');
        parent::__construct($time, $tz);
    }

    /**
     * @param bool $end
     * @return static
     */
    public static function thisMonth($end = false)
    {
        return $end ? self::today()->copy()->endOfMonth() : self::today()->startOfMonth();
    }

    /**
     * @param array $days
     * @return array
     */
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

    /**
     * Get the difference in months using a filter closure
     *
     * @param Closure             $callback
     * @param \Carbonate\Carbonate|null $dt
     * @param bool                $abs      Get the absolute of the difference
     *
     * @return int
     */
    public function diffInMonthsFiltered(Closure $callback, Carbon $dt = null, $abs = true)
    {
        return $this->diffFiltered(CarbonInterval::month(), $callback, $dt, $abs);
    }

    /**
     * Get the difference in years using a filter closure
     *
     * @param Closure             $callback
     * @param \Carbon\Carbon|null $dt
     * @param bool                $abs      Get the absolute of the difference
     *
     * @return int
     */
    public function diffInYearsFiltered(Closure $callback, Carbon $dt = null, $abs = true)
    {
        return $this->diffFiltered(CarbonInterval::year(), $callback, $dt, $abs);
    }

    /**

     * Get the difference (of collection of Carbon dates or the count) in the given period
     *
     * @param \Carbonate\Carbonate |null $dt
     * @param string $in - diff in 'days', 'months', 'years', 'hours', 'minutes', or  'seconds'
     * @param string $incl_last - include the checked date in the difference [only when getting dates]
     * @param string $just_diff
     * @param bool   $abs Get the absolute of the difference for count
     *
     * @return Collection|array - the difference count or collection of Carbon months Start
     */
    public function diffIn(Carbon $dt, $in = 'months', $incl_last = false, $just_diff = false, $abs = true)
    {
        $time = $this;
        if ($just_diff) {
            return $time->{'diffIn' . ucfirst($in)}($dt, $abs);
        }

        $collector = [];
        $time->{'diffIn'.ucfirst($in).'Filtered'}(function (Carbon $date) use (&$collector, $in){
            $collector[] = $date->{'startOf'.substr(ucfirst($in), 0, -1)}();
        }, $dt, $abs);

        $carbon_coll = $incl_last ? collect($collector)->push($dt) : collect($collector);
        return $carbon_coll;
    }


    /**
     *
     * @param $moment
     * @param $dt
     * @param null $to
     * @return Collection
     */
    private function every($moment, $dt, $to = null)
    {
        $result = collect();
        $this->{'diffIn'.ucfirst($moment).'sFiltered'}(function (Carbonate $date) use ($dt, $result){
            if($date->{'is'.ucfirst($dt)}()) {
                $result->push($date->toDateString());
            }
        }, $to);

        return $result;
    }

    /**
     * Get all particular days within a difference
     *
     * @param string $day - day of the week e.g sunday
     * @param Carbonate|null $to - becomes the end of the month if null
     * @return Collection - collection of Carbonate Dates
     */
    public function everyDay(string $day, $to = null)
    {
        if ( $to == null) {
            $to = $this->copy()->endOfMonth();
        }
        return $this->every('day', $day, $to);
    }


    /**
     * Get random date(s) in the year
     *
     * @param Carbonate|null $end_dt
     * @param int $amount
     * @param string $format
     * @return Collection - collection of Carbonate
     */
    public function random($amount = 1, Carbonate $end_dt = null)
    {
        $end_dt = $end_dt ?: Carbonate::now()->copy()->endOfYear();

        return $this->diffIn($end_dt, 'days')->random($amount);
    }

    public function randomOne(Carbonate $end_dt = null, $format = 'Y-m-d H:i:s', $to_string = true)
    {
        $result = $this->random(1, $end_dt)->first();

        return $to_string ? $result->format($format) : $result;
    }
}