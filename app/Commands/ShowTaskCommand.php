<?php

namespace App\Commands;

use App\Concerns\FormatsForTableOutput;
use App\Enums\Preference;
use Illuminate\Support\Facades\DB;

class ShowTaskCommand extends BaseCommand
{
    use FormatsForTableOutput;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'show:task {id? : The id of the task to show details for.}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Show detail for a single task by id.';

    /**
     * The input validation rules.
     */
    public function rules(): array
    {
        return [
            'id' => ['numeric'],
        ];
    }

    /**
     * The command input transformers to run.
     */
    public function transformers(): array
    {
        return [
            'id' => ['intval'],
        ];
    }

    /**
     * The command input validation to run.
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
        $task = DB::table('tasks')->where('id', $id = $this->getOrAskForInput('id', ['rules' => ['integer']]))->first();

        if (is_null($task)) {
            $this->exit("Task with id '$id' does not exist");
        }
        $this->consoleView('task', [
            'task' => $this->formatTaskForTable($task),
            'wordWrap' => $this->getTableWordWrap(),
            'boardName' => get_selected_board_name(),
            'timezone' => $this->getPreferenceOrDefault(Preference::DATE_TIMEZONE, 'America/Indiana/Indianapolis'),
        ]);

        return 0;
    }
}
