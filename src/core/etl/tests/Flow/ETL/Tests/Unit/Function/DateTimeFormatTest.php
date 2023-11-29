<?php

declare(strict_types=1);

namespace Flow\ETL\Tests\Unit\Function;

use function Flow\ETL\DSL\date_time_format;
use function Flow\ETL\DSL\datetime_entry;
use function Flow\ETL\DSL\now;
use function Flow\ETL\DSL\ref;
use function Flow\ETL\DSL\str_entry;
use Flow\ETL\Row;
use PHPUnit\Framework\TestCase;

final class DateTimeFormatTest extends TestCase
{
    public function test_date_time_format() : void
    {
        $this->assertEquals(
            '2020-01-01 00:00:00',
            date_time_format(ref('date_time'), 'Y-m-d H:i:s')->eval(Row::create(datetime_entry('date_time', new \DateTimeImmutable('2020-01-01 00:00:00', new \DateTimeZone('UTC')))))
        );
    }

    public function test_formatting_now() : void
    {
        $this->assertInstanceOf(
            \DateTimeImmutable::class,
            now()->eval(Row::create(datetime_entry('date_time', new \DateTimeImmutable('2020-01-01 00:00:00', new \DateTimeZone('UTC')))))
        );
    }

    public function test_invalid_date_time_format() : void
    {
        $this->assertNull(
            date_time_format(ref('date_time'), 'Y-m-d H:i:s')->eval(Row::create(str_entry('date_time', '2020-01-01 00:00:00')))
        );
    }
}
