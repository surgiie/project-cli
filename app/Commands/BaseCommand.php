<?php

namespace App\Commands;

use App\Enums\Preference;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Surgiie\Console\Command as ConsoleCommand;
use Symfony\Component\Process\Process;

abstract class BaseCommand extends ConsoleCommand
{
    /**The cached user preferences.*/
    protected ?array $preferences = null;

    /**Check requirements for the cli to work properly.*/
    public function requirements()
    {
        return [
            function () {
                if (! extension_loaded('sqlite3')) {
                    return 'The sqlite3 php extension is required by this cli and is not loaded.';
                }
            },
        ];
    }

    /**Open a tmp file using the given editor binary string. */
    protected function openTmpFile($existingContent = '')
    {
        $handle = tmpfile();

        $meta = stream_get_meta_data($handle);

        fwrite($handle, $existingContent);

        $editor = $this->getPreferenceOrDefault(Preference::TERMINAL_EDITOR, 'vim');

        $process = new Process([$editor, $meta['uri']]);

        $process->setTty(true);
        $process->setIdleTimeout(null);
        $process->setTimeout(null);
        $process->mustRun();

        return file_get_contents($meta['uri']);
    }

    /**Configure the database connection for the current selected board. */
    protected function configureDatabaseConnection(string $name = '')
    {
        if (! $name) {
            $name = get_selected_board_name();
        }

        if ($name === false) {
            $this->exit('A board is not selected, please select one with: project select <board-name>');
        }

        config([
            'database.connections.board' => array_merge(config('database.connections.board'), [
                'database' => project_path("boards/$name/database"),
            ]),
        ]);
    }

      /**Get preference value or default.*/
      public function getPreferenceOrDefault(Preference $preference, $default, bool|string $split = false)
      {
          $this->preferences ??= DB::table('preferences')->pluck('value', 'name')->all();

          if (! array_key_exists($preference->name, $this->preferences)) {
              return value($default);
          }

          return $split === false ? $this->preferences[$preference->name] : explode($split, $this->preferences[$preference->name]);
      }

    /**Normalize name to snake & uppercase.*/
    protected function normalizeToUpperSnakeCase(string $name)
    {
        $name = str_replace(['-', '_'], [' ', ' '], mb_strtolower($name));

        return mb_strtoupper(Str::snake($name));
    }
}
