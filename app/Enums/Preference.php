<?php

namespace App\Enums;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Surgiie\Console\Exceptions\ExitCommandException;

enum Preference
{
    /**The terminal editor to use for tmp files. */
    case TERMINAL_EDITOR;
    /**The order of statuses for board columns. */
    case STATUS_ORDER;

    /**The timezone to use for dates shown on the boards/task detail. */
    case DATE_TIMEZONE;

    /**
     * Determine if there is a preference with this name.
     *
     * @param string $name
     * @return boolean
     */
    public static function has(string $name): bool
    {
        $enumsArr = Preference::cases();

        $names = array_column($enumsArr, 'name');

        return in_array($name, $names);
    }

    /**
     * Validate that the status order value is valid for save.
     *
     * @param string $value
     * @return void
     */
    public static function validateStatusOrder(string $value)
    {
        $statuses = explode(',', $value);

        foreach ($statuses as $status) {
            $record = DB::table('statuses')->where('name', Str::kebab($status))->first();

            if (is_null($record)) {
                throw new ExitCommandException("Status does not exist: $status");
            }
        }

        if (count($statuses) != ($recordCount = DB::table('statuses')->count())) {
            throw new ExitCommandException("Given list of status does not match table count of: $recordCount");
        }
    }
    /**
     * Validate that the timezone value is valid for save.
     *
     * @param string $value
     * @return void
     */
    public static function validateDateTimezone(string $value)
    {
        $tzs = timezone_identifiers_list ();
        if(!in_array($value, $tzs)){
            throw new ExitCommandException("Invalid timezone '$value'");
        }
    }
}
