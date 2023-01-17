<?php

namespace App\Commands;

use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Terminal;

class ShowCommand extends BaseCommand
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'show';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Show the board with current tasks.';

    /**Command requirements.*/
    public function requirements()
    {
        return array_merge(parent::requirements(), [
            function () {
                $this->configureDatabaseConnection();
            },
        ]);
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tasks = DB::table('tasks')->get();

        $statuses = DB::table('statuses')->pluck('display', 'name')->all();

        $rows = [];

        $terminal = new Terminal;

        $width = $terminal->getWidth();

        foreach ($tasks as $task) {
            // dd($task);
        }

        $this->consoleView('board', [
            'statuses' => $statuses,
            'wrap'=> floor($width / count($statuses)) / 2
        ]);
    }
}
