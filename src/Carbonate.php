<?php
namespace Carbonate;


use Closure;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Collection;

class Carbonate extends Carbon
{
    const ALLOWED_DIFFS = [
        'days', 'months', 'years', 'weeks', 'days', 'hours', 'minutes', 'seconds'
    ];

    public function __construct($time = null, $tz = 'UTC')
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
     * Get the dates of some particular days - from the start of the month to the end
     *
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
     * Get the difference in minutes using a filter closure
     *
     * @param Closure             $callback
     * @param \Carbonate\Carbonate|null $dt
     * @param bool                $abs      Get the absolute of the difference
     *
     * @return int
     */
    public function diffInMinutesFiltered(Closure $callback, Carbonate $dt = null, $abs = true)
    {
        return $this->diffFiltered(CarbonInterval::minute(), $callback, $dt, $abs);
    }

    /**
     * Get the difference in weeks using a filter closure
     *
     * @param Closure             $callback
     * @param \Carbonate\Carbonate|null $dt
     * @param bool                $abs      Get the absolute of the difference
     *
     * @return int
     */
    public function diffInWeeksFiltered(Closure $callback, Carbonate $dt = null, $abs = true)
    {
        return $this->diffFiltered(CarbonInterval::week(), $callback, $dt, $abs);
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
    public function diffInMonthsFiltered(Closure $callback, Carbonate $dt = null, $abs = true)
    {
        return $this->diffFiltered(CarbonInterval::month(), $callback, $dt, $abs);
    }

    /**
     * Get the difference in years using a filter closure
     *
     * @param Closure             $callback
     * @param \Carbonate\Carbonate|null $dt
     * @param bool                $abs      Get the absolute of the difference
     *
     * @return int
     */
    public function diffInYearsFiltered(Closure $callback, Carbonate $dt = null, $abs = true)
    {
        return $this->diffFiltered(CarbonInterval::year(), $callback, $dt, $abs);
    }

    /**
     * Make a collection instance
     * @param array $items
     * @return Collection
     */
    private static function collect($items = [])
    {
        return Collection::make($items);
    }

    /**
     * Get the difference between two dates as a collection of Carbonate dates
     *
     * @param \Carbonate\Carbonate |null $dt
     * @param string $in - diff in 'days', 'months', 'years', 'hours', 'minutes', or  'seconds'
     * @param false $incl_last - include the checked date in the difference [only when getting dates]
     * @param bool $abs Get the absolute of the difference for count
     *
     * @return Collection|array - the difference count or collection of Carbon months Start
     */
    private function diffIn(Carbonate $dt = null, $in = 'months', $incl_last = false, $abs = true)
    {
        $this->checkAllowedDiffs($in);
        $collector = $this->collect();
        $this->{'diffIn'.ucfirst($in).'Filtered'}(function (Carbonate $date) use ($collector, $in){
            $collector->push($date);
        }, $dt, $abs);

        return $incl_last
            ? $collector->push($dt)
            : $collector;
    }

    /**
     * Retrieve dates within two dates as a collection of dates
     *
     * @param Carbonate|null $dt
     * @param string $in
     * @param bool $abs
     * @return Collection
     */
    private function within(Carbonate $dt = null, $in = 'months', $abs = true)
    {
        static::checkAllowedDiffs($in);

        $collector = collect();

        $diffIn = 'diffIn'.ucfirst($in).'Filtered';
//        dd('startOf'.substr(ucfirst($in), 0, -1));

        $count = $this->{$diffIn}(function (Carbonate $date) use (&$collector, $in) {

            $collector->push( $date );

        }, $dt, $abs);

        return $count ? $collector->push($dt) : $collector;
    }


    /**
     * Retrieve years' dates within two dates as a collection of dates
     *
     * @param Carbonate|null $dt
     * @param bool $abs
     * @return Collection
     */
    public function withinYears(Carbonate $dt = null, $abs = true)
    {
        return $this->within($dt, 'years', $abs);
    }

    /**
     * Retrieve months' dates within two dates as a collection of dates
     *
     * @param Carbonate|null $dt
     * @param bool $abs
     * @return Collection
     */
    public function withinMonths(Carbonate $dt = null, $abs = true)
    {
        return $this->within($dt, 'months', $abs);
    }

    /**
     * Retrieve weeks' dates within two dates as a collection of dates
     *
     * @param Carbonate|null $dt
     * @param bool $abs
     * @return Collection
     */
    public function withinWeeks(Carbonate $dt = null, $abs = true)
    {
        return $this->within($dt, 'weeks', $abs);
    }

    /**
     * Retrieve days' dates within two dates as a collection of dates
     *
     * @param Carbonate|null $dt
     * @param bool $abs
     * @return Collection
     */
    public function withinDays(Carbonate $dt = null, $abs = true)
    {
        return $this->within($dt, 'days', $abs);
    }

    /**
     * Retrieve hours' dates within two dates as a collection of dates
     *
     * @param Carbonate|null $dt
     * @param bool $abs
     * @return Collection
     */
    public function withinHours(Carbonate $dt = null, $abs = true)
    {
        return $this->within($dt, 'hours', $abs);
    }

    /**
     * Retrieve minutes' dates within two dates as a collection of dates
     *
     * @param Carbonate|null $dt
     * @param bool $abs
     * @return Collection
     */
    public function withinMinutes(Carbonate $dt = null, $abs = true)
    {
        return $this->within($dt, 'minutes', $abs);
    }

    /**
     * Check if a diff type is allowed
     *
     * @param $diff_type
     * @return \Exception|bool
     */
    public final static function checkAllowedDiffs($diff_type)
    {
        if (! in_array(strtolower($diff_type), static::ALLOWED_DIFFS)) {
            return new \Exception("The given '$diff_type' diff cannot be made");
        }

        return true;
    }

    /**
     * Get every particular dates within a range of dates
     *
     * @param $moment
     * @param $dt
     * @param Carbonate null $to
     * @return Collection
     */
    private function every($moment, $dt, Carbonate $to = null)
    {
        $result = collect();
        $this->startOfDay()->{'diffIn'.ucfirst($moment).'sFiltered'}(function (Carbonate $date) use ($dt, $result){
            if($date->{'is'.ucfirst($dt)}()) {
                $result->push($date);
            }
        }, $to);

        return $result;
    }

    /**
     * Get collection of weekend dates
     *
     * @param Carbonate|null $to
     * @return Collection
     */
    public function everyWeekendDays(Carbonate $to = null)
    {
        return $this->every('day', 'weekend', $to);
    }

    /**
     * Get collection of weekday dates
     *
     * @param Carbonate|null $to
     * @return Collection
     */
    public function everyWeekDays(Carbonate $to = null)
    {
        return $this->every('day', 'weekday', $to);
    }

    /**
     * Get all particular days until a date
     *
     * @param string $day - day of the week e.g sunday
     * @param Carbonate|null $to - becomes the end of the month if null
     * @return Collection - collection of Carbonate Dates
     */
    public function everyDay($day, $to = null)
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
     * @return Collection - collection of Carbonate
     */
    public function random($amount = 1, Carbonate $end_dt = null)
    {
        $end_dt = $end_dt ?: Carbonate::now()->copy()->endOfYear();

        return $this->diffIn($end_dt, 'days')->random($amount)->values();
    }

    /**
     * Get any random date between the two dates
     *
     * @param Carbonate|null $end_dt
     * @return Carbonate
     */
    public function randomOne(Carbonate $end_dt = null)
    {
        return $this->random(1, $end_dt)->first();
    }

    /**
     * Get any One Monday between the dates or till the end of the year
     *
     * @param Carbonate|null $end_dt
     * @return Carbonate|string
     */
    public function anyMonday(Carbonate $end_dt = null)
    {
        return $this->anyOne('Monday', $end_dt);
    }

    /**
     * Get any One Tuesday between the dates or till the end of the year
     *
     * @param Carbonate|null $end_dt
     * @return Carbonate|string
     */
    public function anyTuesday(Carbonate $end_dt = null)
    {
        return $this->anyOne('Tuesday', $end_dt);
    }

    /**
     * Get any One Wednesday between the dates or till the end of the year
     *
     * @param Carbonate|null $end_dt
     * @return Carbonate
     */
    public function anyWednesday(Carbonate $end_dt = null)
    {
        return $this->anyOne('Wednesday', $end_dt);
    }

    /**
     * Get any One Thursday between the dates or till the end of the year
     *
     * @param Carbonate|null $end_dt
     * @return Carbonate
     */
    public function anyThursday(Carbonate $end_dt = null, $format = 'Y-m-d H:i:s')
    {
        return $this->anyOne('Thursday', $end_dt);
    }

    /**
     * Get any One Friday between the dates or till the end of the year
     *
     * @param Carbonate|null $end_dt
     * @return Carbonate|string
     */
    public function anyFriday(Carbonate $end_dt = null)
    {
        return $this->anyOne('Friday', $end_dt);
    }

    /**
     * Get any One Saturday between the dates or till the end of the year
     *
     * @param Carbonate|null $end_dt
     * @return Carbonate
     */
    public function anySaturday(Carbonate $end_dt = null)
    {
        return $this->anyOne('Saturday', $end_dt);
    }

    /**
     * Get any One Sunday between the dates or till the end of the year
     *
     * @param Carbonate|null $end_dt
     * @return Carbonate
     */
    public function anySunday(Carbonate $end_dt = null)
    {
        return $this->anyOne('Sunday', $end_dt);
    }

    /**
     * Get any random date(s) of the given day
     *
     * @param string $day
     * @param Carbonate|null $end_dt
     * @param int $amount
     * @return Collection - collections of Carbonate
     */
    private function any($day = 'Monday', Carbonate $end_dt = null, $amount = 1)
    {
        $end_dt = $end_dt ?: $this->copy()->endOfYear();

        return $result = $this->diffIn($end_dt, 'days')->filter(function (Carbonate $date, $key) use ($day){
            return $date->{'is'.ucfirst($day)}();
        })->random($amount);
    }

    /**
     * Get any of the week's day in the range of Dates
     *
     * @param $day
     * @param Carbonate|null $end_dt
     * @return Carbonate|string - the date in Carbonate or string
     */
    public function anyOne($day, Carbonate $end_dt = null)
    {
        return $this->any($day, $end_dt, 1)->first();
    }

    /**
     * Turn the collection of Carbonate Dates to String dates
     *
     * @param Collection|array $dates
     * @param string $format
     * @return Collection - collection string dates
     */
    public static function stringify($dates, $format = 'Y-m-d H:i:s')
    {
        return Collection::make($dates)->transform(function(Carbonate $date) use ($format){
            return $date->format($format);
        });
    }

    /**
     * Transform arrayed date string into Carbonate collection
     *
     * @param array $dates
     * @return Collection - collection of Carbonate
     */
    public static function carbonate($dates)
    {
        return Collection::make($dates)->transform(function ($date) {
           return self::parse($date);
        });
    }

    public function weekends(Carbonate $end)
    {
        $result = collect();
        $this->diffInWeekendDays(function (Carbonate $dt) use ($result){
                $result->push($dt);
        }, $end);

        return $result;
    }

    //todo: reduce the number of parameters required by diffIn()
    //todo: check inclusion of the last days in all 'diffIn...' functions
    //todo: check that the dates are reset to start of day where needed
    //todo.new: diffHours(), diffMinutes(), diffSeconds()
    //todo.new: endOfHour(), endOfMinutes()
}