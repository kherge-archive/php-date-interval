<?php

namespace Herrera\DateInterval\Tests;

use DateTime;
use Herrera\DateInterval\DateInterval;
use PHPUnit_Framework_TestCase as TestCase;

class DateIntervalTest extends TestCase
{
    public function getDiffs()
    {
        $list = array(
            array(
                '1',
                new DateTime('2010-01-01 00:00:01'),
                new DateTime('2010-01-01 00:00:00'),
            ),
            array(
                '60',
                new DateTime('2010-01-01 00:01:00'),
                new DateTime('2010-01-01 00:00:00'),
            ),
            array(
                '3600',
                new DateTime('2010-01-01 01:00:00'),
                new DateTime('2010-01-01 00:00:00'),
            ),
            array(
                '86400',
                new DateTime('2010-01-02 00:00:00'),
                new DateTime('2010-01-01 00:00:00'),
            ),
            array(
                null,
                new DateTime('2010-02-01 00:00:00'),
                new DateTime('2010-01-01 00:00:00'),
            ),
            array(
                null,
                new DateTime('2011-01-01 00:00:00'),
                new DateTime('2010-01-01 00:00:00'),
            ),
        );

        $month = $list[4][1]->diff($list[4][2]);
        $list[4][0] = $month->days * DateInterval::SECONDS_DAY;

        $year = $list[5][1]->diff($list[5][2]);
        $list[5][0] = $year->days * DateInterval::SECONDS_DAY;

        return $list;
    }

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
        $seconds = DateInterval::fromSeconds('2056600500');
        $interval = new DateInterval('P60Y60M60DT60H60M60S');

        $this->assertEquals($seconds->toSeconds(), $interval->toSeconds());
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

    /**
     * @dataProvider getDiffs
     *
     * @param string  $seconds
     * @param DateTime $left
     * @param DateTime $right
     */
    public function testToSecondsUsingDays($seconds, $left, $right)
    {
        $interval = new DateInterval('PT0S');

        $this->assertEquals(
            $seconds,
            $interval->toSecondsUsingDays($left->diff($right))
        );
    }

    public function testToSecondsUsingDaysNotSet()
    {
        $interval = new DateInterval('PT0S');

        $this->setExpectedException(
            'InvalidArgumentException',
            'The "days" property is not set.'
        );

        DateInterval::toSecondsUsingDays($interval);
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
