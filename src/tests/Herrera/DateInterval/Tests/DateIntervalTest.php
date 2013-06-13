<?php

namespace Herrera\DateInterval\Tests;

use Herrera\DateInterval\DateInterval;
use PHPUnit_Framework_TestCase as TestCase;

class DateIntervalTest extends TestCase
{
    public function getSeconds()
    {
        return array(
            array('1', 'PT1S'),
            array('60', 'PT1M'),
            array('3600', 'PT1H'),
            array('86400', 'P1D'),
            array('2629740', 'P1M'),
            array('31556874', 'P1Y'),
            array('34276675', 'P1Y1M1DT1H1M1S'),
            array('2056600500', 'P65Y2M1DT16H3M30S'),
        );
    }

    public function getSpecs()
    {
        return array(
            array('PT1S'),
            array('PT1M'),
            array('PT1H'),
            array('P1D'),
            array('P1M'),
            array('P1Y'),
            array('P1Y1M1DT1H1M1S')
        );
    }

    /**
     * @dataProvider getSeconds
     */
    public function testFromSeconds($seconds, $spec)
    {
        $interval = DateInterval::fromSeconds($seconds);

        $this->assertEquals($spec, $interval->toSpec());
    }

    public function testFromSecondsCompare()
    {
        $a = DateInterval::fromSeconds('2056600500');
        $b = new DateInterval ('P60Y60M60DT60H60M60S');

        $this->assertEquals($a->toSeconds(), $b->toSeconds());
    }

    public function testToString()
    {
        $this->assertEquals('PT1S', (string) new DateInterval('PT1S'));
    }

    /**
     * @dataProvider getSeconds
     */
    public function testToSeconds($seconds, $spec)
    {
        $interval = new DateInterval($spec);

        $this->assertEquals($seconds, $interval->toSeconds());
    }
    
    public function testToSecondsUsingDays()
    {
        $date1 = new \DateTime('2012-06-10 12:30:00');
        $date2 = new \DateTime('2013-06-10 13:35:05');
        $intervalfromDiff = $date1->diff($date2);
        
        $interval = new DateInterval('PT0S');
        $this->assertEquals(31539905, $interval->toSecondsUsingDays($intervalfromDiff));
    }

    /**
     * @dataProvider getSpecs
     */
    public function testToSpec($spec)
    {
        $interval = new DateInterval($spec);

        $this->assertEquals($spec, $interval->toSpec());
    }
}
