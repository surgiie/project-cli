<?php

namespace App\Commands;

use App\Concerns\FormatsForTableOutput;
use App\Enums\Preference;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\Console\Terminal;

class ShowBoardCommand extends BaseCommand
{
    use FormatsForTableOutput;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'show:board';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Show the board with current tasks.';

    /**
     * The command requirements to run.
     */
    public function requirements(): array
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
    public function handle(): int
    {
        $row = [];
        $rows = [];
        $done = false;
        $cachedTasks = [];
        $emptyStatuses = [];

        $statuses = $this->getPreferenceOrDefault(Preference::STATUS_ORDER, DB::table('statuses')->pluck('display', 'name')->all(), split: ',');
        $totalStatuses = count($statuses);

        if ($totalStatuses == 0) {
            $this->exit('No tasks to show', level: 'warn');
        }

        $wordWrap = floor((new Terminal)->getWidth() / $totalStatuses) / 2;

        // in order to display the tasks by column and status in a table, we need to extract rows into a format
        // where each row item corresponds to the column status.

        while (! $done) {
            foreach ($statuses as $status) {
                $status = Str::kebab($status);
                $cachedTasks[$status] = $cachedTasks[$status] ?? ($cachedTasks[$status] = DB::table('tasks')->where('status', $status)->get()->toArray());
                // check if were done processing this specific status.
                if (count($cachedTasks[$status]) == 0 && ! in_array($status, $emptyStatuses)) {
                    $emptyStatuses[] = $status;
                }

                // if the row length has hit total number of statues, we're done with this row, empty and start over.
                if (count($row) == $totalStatuses) {
                    $rows[] = $row;
                    $row = [];
                }

                // slice of the first task so we're always starting with the first item when starting over.
                $task = array_shift($cachedTasks[$status]);

                // Format task properties so that these properties render cleaner on the table
                if ($task) {
                    $row[] = $this->formatTaskForTable($task);
                } else {
                    // empty cell/no task.
                    $row[] = false;
                }

                // done when we have processed all statuses.
                if ($totalStatuses == count($emptyStatuses)) {
                    $done = true;
                }
            }
        }

        $this->consoleView('board', [
            'rows' => $rows,
            'boardName' => get_selected_board_name(),
            'statuses' => $statuses,
            'wordWrap' => $wordWrap,
        ]);

        return 0;
    }
}
