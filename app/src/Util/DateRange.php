<?php

namespace App\Util;

/**
 * Class DateRange
 * @package App\Util
 */
class DateRange
{
    private $startDateTime;
    private $endDateTime;

    /**
     * DateRange constructor.
     * @param $start string ISO8601 DateTime
     * @param $end string ISO8601 DateTime
     * @throws DateRangeException
     */
    public function __construct($start, $end)
    {
        if (!$start || !$end) {
            throw new DateRangeException('Date range not specified', 1);
        }

        $this->startDateTime = \DateTime::createFromFormat(\DateTime::RFC3339, $start);
        $this->endDateTime = \DateTime::createFromFormat(\DateTime::RFC3339, $end);

        if ($this->startDateTime->format('Y-m-d\TH:i:s\Z') !== $start) {
            throw new DateRangeException($start . ' is not a valid RFC3339 DateTime', 2);
        }

        if ($this->endDateTime->format('Y-m-d\TH:i:s\Z') !== $end) {
            throw new DateRangeException($end . ' is not a valid RFC3339 DateTime', 3);
        }
    }

    /**
     * @param string $interval 'P1D' = 1 day
     * @return \Generator string
     */
    public function step($interval = 'P1D')
    {

        $startDateTime = \DateTime::createFromFormat(\DateTime::RFC3339, $this->startDateTime->format('Y-m-d\T00:00:00\Z'));
        $endDateTime = \DateTime::createFromFormat(\DateTime::RFC3339, $this->endDateTime->format('Y-m-d\T23:59:59\Z'));

        $currentDateTime = $startDateTime;

        while ($currentDateTime <= $endDateTime) {

            $currentDate = $currentDateTime->format('Y-m-d\TH:i:s\Z');

            yield $currentDate;

            $currentDateTime->add(new \DateInterval($interval));
        }
    }
}