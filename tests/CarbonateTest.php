<?php

use \Carbonate\Carbonate;

class NachoTest extends PHPUnit_Framework_TestCase {

    public function testIsThisMonth()
    {
        $carbonate = new Carbonate();
        $this->assertTrue($carbonate->thisMonth()->equalTo( Carbonate::today()->startOfMonth() )) ;
    }

}