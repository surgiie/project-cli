<?php

namespace App\Commands;

use Illuminate\Support\Str;
use Surgiie\Console\Command as ConsoleCommand;

abstract class BaseCommand extends ConsoleCommand
{
    /**Check requirements for the cli to work properly.*/
    public function requirements()
    {
        return [
            function(){
                if (! extension_loaded('sqlite3')) {
                    return 'The sqlite3 php extension is required by this cli and is not loaded.';
                }
            }
        ];
    }

    /**Configure the database connection for the current selected board. */
    protected function configureDatabaseConnection(string $name = '')
    {
        if(!$name){
            $name = get_selected_board_name();
        }

        if($name === false){
            $this->exit("A board is not selected, please select/set one with: project select <board-name>");
        }
        
        config([
            'database.connections.board' => array_merge(config('database.connections.board'), [
                'database' => project_path("boards/$name/database"),
            ]),
        ]);
    }
    /**Normalize name to snake & uppercase.*/
    protected function normalizeToUpperSnakeCase(string $name)
    {
        $name = str_replace(['-', '_'], [' ', ' '], mb_strtolower($name));

        return mb_strtoupper(Str::snake($name));
    }
}
