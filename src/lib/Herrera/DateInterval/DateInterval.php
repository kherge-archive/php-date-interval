<?php

namespace Herrera\DateInterval;

use InvalidArgumentException;

/**
 * Adds functionality to the DateInterval class.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class DateInterval extends \DateInterval
{
    /**
     * Number of seconds in a minute.
     *
     * @var integer
     */
    const SECONDS_MINUTE = 60;

    /**
     * Number of seconds in an hour.
     *
     * @var integer
     */
    const SECONDS_HOUR = 3600;

    /**
     * Number of seconds in a day.
     *
     * @var integer
     */
    const SECONDS_DAY = 86400;

    /**
     * The number of seconds in a month.
     *
     * Based on a 30.4368 day month, with the product rounded.
     *
     * @var integer
     */
    const SECONDS_MONTH = 2629740;

    /**
     * The number of seconds in a year.
     *
     * Based on a 365.2416 day year, with the product rounded.
     *
     * @var integer
     */
    const SECONDS_YEAR = 31556874;

    /**
     * The date properties.
     *
     * @var array
     */
    private static $date = array('y' => 'Y', 'm' => 'M', 'd' => 'D');

    /**
     * The time properties.
     *
     * @var array
     */
    private static $time = array('h' => 'H', 'i' => 'M', 's' => 'S');

    /**
     * Returns the interval specification.
     *
     * @return string The interval specification.
     */
    public function __toString()
    {
        return self::toSpec($this);
    }

    /**
     * Returns the DateInterval instance for the number of seconds.
     *
     * @param integer|string $seconds The number of seconds.
     *
     * @return DateInterval The date interval.
     */
    public static function fromSeconds($seconds)
    {
        $interval = new static('PT0S');

        foreach (array(
            'y' => self::SECONDS_YEAR,
            'm' => self::SECONDS_MONTH,
            'd' => self::SECONDS_DAY,
            'h' => self::SECONDS_HOUR,
            'i' => self::SECONDS_MINUTE
        ) as $property => $increment) {
            if (-1 !== bccomp($seconds, $increment)) {
                $count = floor(bcdiv($seconds, $increment, 1));

                $interval->$property = $count;

                $seconds = bcsub($seconds, bcmul($count, $increment));
            }
        }

        $interval->s = (int) $seconds;

        return $interval;
    }

    /**
     * Returns the total number of seconds in the interval.
     *
     * @param \DateInterval $interval The date interval.
     *
     * @return string The number of seconds.
     */
    public static function toSeconds(\DateInterval $interval = null)
    {
        if ((null === $interval) && isset($this)) {
            $interval = $this;
        }

        $seconds = (string) $interval->s;

        if ($interval->i) {
            $seconds = bcadd($seconds, bcmul($interval->i, self::SECONDS_MINUTE));
        }

        if ($interval->h) {
            $seconds = bcadd($seconds, bcmul($interval->h, self::SECONDS_HOUR));
        }

        if ($interval->d) {
            $seconds = bcadd($seconds, bcmul($interval->d, self::SECONDS_DAY));
        }

        if ($interval->m) {
            $seconds = bcadd($seconds, bcmul($interval->m, self::SECONDS_MONTH));
        }

        if ($interval->y) {
            $seconds = bcadd($seconds, bcmul($interval->y, self::SECONDS_YEAR));
        }

        return $seconds;
    }

    /**
     * Returns the total number of seconds in the interval using `days` as
     * returned by `DateTime::diff()`.
     *
     * @param \DateInterval $interval The date interval.
     *
     * @return string The number of seconds.
     *
     * @throws InvalidArgumentException If "days" is not set.
     */
    public static function toSecondsUsingDays(\DateInterval $interval)
    {
        $seconds = (string) $interval->s;

        if ($interval->i) {
            $seconds = bcadd($seconds, bcmul($interval->i, self::SECONDS_MINUTE));
        }

        if ($interval->h) {
            $seconds = bcadd($seconds, bcmul($interval->h, self::SECONDS_HOUR));
        }

        if ((false !== $interval->days) && (0 <= $interval->days)) {
            $seconds = bcadd($seconds, bcmul($interval->days, self::SECONDS_DAY));
        } else {
            throw new InvalidArgumentException(
                'The "days" property is not set.'
            );
        }

        return $seconds;
    }

    /**
     * Returns the interval specification.
     *
     * @param \DateInterval $interval The date interval.
     *
     * @return string The interval specification.
     */
    public function toSpec(\DateInterval $interval = null)
    {
        if ((null === $interval) && isset($this)) {
            $interval = $this;
        }

        $string = 'P';

        foreach (self::$date as $property => $suffix) {
            if ($interval->{$property}) {
                $string .= $interval->{$property} . $suffix;
            }
        }

        if ($interval->h || $interval->i || $interval->s) {
            $string .= 'T';

            foreach (self::$time as $property => $suffix) {
                if ($interval->{$property}) {
                    $string .= $interval->{$property} . $suffix;
                }
            }
        }

        return $string;
    }
}
