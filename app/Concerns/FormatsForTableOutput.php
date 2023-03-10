<?php

namespace App\Concerns;

use Illuminate\Support\Facades\DB;
use stdClass;
use Symfony\Component\Console\Terminal;

trait FormatsForTableOutput
{
    protected ?array $cachedTableFormatQueries = null;

    /**
     * Format the value so that it can render properly on terminal.
     */
    protected function formatTaskForTable(stdClass $task): stdClass
    {
        foreach (array_keys(get_object_vars($task)) as $property) {
            $task->$property = wordwrap(preg_replace('!\s+!', ' ', $task->$property), $this->getTableWordWrap());
        }

        return $task;
    }

    /**
     * Get table wordwrap for text values.
     */
    protected function getTableWordWrap(): int
    {
        $this->cachedTableFormatQueries['statues'] ??= DB::table('statuses')->count();

        return floor((new Terminal)->getWidth() / $this->cachedTableFormatQueries['statues']) / 2;
    }
}
