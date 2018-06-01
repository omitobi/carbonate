<?php

use \Carbonate\Carbonate;

class CarbonateTest extends PHPUnit_Framework_TestCase {

    public function collect($items = []) {
       return \Illuminate\Support\Collection::make($items);
    }

    public function testIsThisMonthFromStart()
    {
        $carbonate = new Carbonate();
        $this->assertTrue($carbonate->thisMonth()->equalTo( Carbonate::today()->startOfMonth() )) ;
    }

    public function testIsThisMonthAtEnd()
    {
        $carbonate = new Carbonate();
        $this->assertTrue($carbonate->thisMonth(true)->equalTo( Carbonate::today()->endOfMonth() )) ;
    }

    public function testGetDatesOfDaysInMonthIsNotEmpty()
    {
        $carbonate = new Carbonate();
        $this->assertNotEmpty($carbonate->getDatesOfDaysInMonth(['Saturday', 'Wednesday'])) ;
    }

    public function testDiffInMonthsFiltered()
    {
        $from = new Carbonate('2017-11-01');
        $until = new Carbonate('2017-09-01');
        $this->assertEquals(2, $from->diffInMonthsFiltered(function(Carbonate $dt) {
            return  $dt;
        }, $until));
    }

    public function testDiffInYears()
    {
        $from = new Carbonate('2019-09-01');
        $until = new Carbonate('2017-09-01');
        $this->assertEquals(2, $from->diffInYearsFiltered(function(Carbonate $dt) {
            return  $dt;
        }, $until));
    }

    public function testEveryWithDays()
    {
        $from = new Carbonate('2017-09-01');
        $until = new Carbonate('2017-09-30');
        $this->assertEquals(4, $from->everyDay('Monday', $until)->count());
    }

    public function testEveryWeekDay()
    {
        $from = new Carbonate('2017-09-01');
        $until = new Carbonate('2017-09-30');
        $this->assertEquals(21, $from->everyWeekDays($until)->count());
    }

    public function testEveryWeekend()
    {
        $from = new Carbonate('2017-09-01');
        $until = new Carbonate('2017-09-30');
        $this->assertEquals(8, $from->everyWeekendDays($until)->count());
    }

    public function testRandom()
    {
        $carbonate = new Carbonate('2017-09-01');
        $this->assertEquals(5, $carbonate->random(5)->count());
    }

    public function testRandomOne()
    {
        $carbonate = new Carbonate('2017-09-01');
        $this->assertNotNull(5, $carbonate->randomOne());
    }

    public function testAnyOne()
    {
        $carbonate = new Carbonate('2017-09-01');
        $this->assertTrue($carbonate->anyOne('Tuesday')->isTuesday());
    }

    public function testStringify()
    {
        $carbonates = $this->collect([
           Carbonate::parse('2017-09-01'),
           Carbonate::parse('2017-09-01')->addYear(1),
           Carbonate::parse('2017-09-01')->addMonth(),
           Carbonate::parse('2017-09-01')->addDay(),
        ]);

        $expect = $this->collect([
            '2017-09-01 00:00:00',
            '2018-09-01 00:00:00',
            '2017-10-01 00:00:00',
            '2017-09-02 00:00:00'
        ]);

        $this->assertEquals($expect, Carbonate::stringify($carbonates),[]);
    }

    public function testCarbonate()
    {
        $dates = [
            '2017-09-01 00:00:00',
            '2018-09-01 00:00:00',
            '2017-10-01 00:00:00',
            '2017-09-02 00:00:00'
        ];

        $expected = $this->collect([
            Carbonate::parse('2017-09-01'),
            Carbonate::parse('2017-09-01')->addYear(1),
            Carbonate::parse('2017-09-01')->addMonth(),
            Carbonate::parse('2017-09-01')->addDay(),
        ]);

        $this->assertEquals($expected, Carbonate::carbonate($dates));
    }

    public function testWeekends()
    {
        $carbonate = new Carbonate('2017-09-01');
        $this->assertEquals(2, $carbonate->weekends(Carbonate::parse('2017-09-07'))->count());
    }


}