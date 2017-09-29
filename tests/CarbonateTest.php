<?php

use \Carbonate\Carbonate;

class CarbonateTest extends PHPUnit_Framework_TestCase {

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
        $carbonate = new Carbonate();
        $this->assertEquals(2, $carbonate->addMonth(2)->diffInMonthsFiltered(function(Carbonate $dt) {
            return  $dt;
        }));
    }

    public function testDiffInMonthsYears()
    {
        $carbonate = new Carbonate();
        $this->assertEquals(2, $carbonate->addYear(2)->diffInYearsFiltered(function(Carbonate $dt) {
            return  $dt;
        }));
    }

    public function testEveryWithDays()
    {
        $carbonate = new Carbonate('2017-09-01');
        $this->assertEquals(4, $carbonate->everyDay('Monday')->count());
    }

    public function testEveryWeekDay()
    {
        $carbonate = new Carbonate('2017-09-01');
        $this->assertEquals(21, $carbonate->everyWeekDay()->count());
    }

    public function testEveryWeekend()
    {
        $carbonate = new Carbonate('2017-09-01');
        $this->assertEquals(8, $carbonate->everyWeekend()->count());
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
        $carbonates = collect([
           Carbonate::parse('2017-09-01'),
           Carbonate::parse('2017-09-01')->addYear(1),
           Carbonate::parse('2017-09-01')->addMonth(),
           Carbonate::parse('2017-09-01')->addDay(),
        ]);

        $expect = collect([
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

        $expected = collect([
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