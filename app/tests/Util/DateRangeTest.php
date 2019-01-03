<?php

namespace App\Tests\Util;

use App\Util\DateRange;
use App\Util\DateRangeException;
use PHPUnit\Framework\TestCase;

class DateRangeTest extends TestCase
{
    public function testSimpleRange()
    {
        $dateRange = new DateRange('2019-01-01T12:00:00Z', '2019-01-02T12:00:00Z');

        $generator = $dateRange->step();

        $this->assertEquals('2019-01-01T00:00:00Z', $generator->current());
        $generator->next();
        $this->assertEquals('2019-01-02T00:00:00Z', $generator->current());
    }

    public function testEmptyArgsExceptions()
    {
        $this->expectException(DateRangeException::class);
        $this->expectExceptionCode(1);

        new DateRange('', '');
    }

    public function testWrongDateExceptions()
    {
        $this->expectException(DateRangeException::class);
        $this->expectExceptionCode(2);

        new DateRange('2019-00-01T12:00:00Z', '2019-01-02T12:00:00Z');

        $this->expectException(DateRangeException::class);
        $this->expectExceptionCode(3);

        new DateRange('2019-01-01T12:00:00Z', '1899-01-02T12:00:00Z');
    }
}