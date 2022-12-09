<?php

declare(strict_types=1);

namespace Flow\ETL\GroupBy\Aggregator;

use Flow\ETL\Exception\InvalidArgumentException;
use Flow\ETL\GroupBy\Aggregator;
use Flow\ETL\Row;
use Flow\ETL\Row\Entry;
use Flow\ETL\Row\EntryReference;

final class Average implements Aggregator
{
    private int $count;

    private readonly EntryReference $entry;

    private float $sum;

    public function __construct(string|EntryReference $entry)
    {
        $this->entry = \is_string($entry) ? new EntryReference($entry) : $entry;
        $this->count = 0;
        $this->sum = 0;
    }

    public function aggregate(Row $row) : void
    {
        try {
            /** @var mixed $value */
            $value = $row->valueOf($this->entry->to());

            if (\is_numeric($value)) {
                $this->sum += $value;
                $this->count++;
            }
        } catch (InvalidArgumentException) {
            // do nothing?
        }
    }

    public function result() : Entry
    {
        if (!$this->entry->hasAlias()) {
            $this->entry->as($this->entry->to() . '_avg');
        }

        $result = $this->sum / $this->count;
        $resultInt = (int) $result;

        if ($result - $resultInt === 0.0) {
            return \Flow\ETL\DSL\Entry::integer($this->entry->name(), (int) $result);
        }

        return \Flow\ETL\DSL\Entry::float($this->entry->name(), $result);
    }
}
