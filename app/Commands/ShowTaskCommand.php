<?php

namespace App\Commands;

use App\Enums\Preference;
use Illuminate\Support\Facades\DB;
use App\Concerns\FormatsForTableOutput;
use Surgiie\Console\Concerns\WithValidation;
use Surgiie\Console\Concerns\WithTransformers;

class ShowTaskCommand extends BaseCommand
{
    use WithValidation, WithTransformers, FormatsForTableOutput;

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

    /**Command input validation rules.*/
    public function rules()
    {
        return [
            'id' => ['numeric'],
        ];
    }

    /**Command input transformers.*/
    public function transformers()
    {
        return [
            'id' => ['intval'],
        ];
    }

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
        $task = DB::table('tasks')->where('id', $id = $this->getOrAskForInput('id', rules: ['integer']))->first();

        if (is_null($task)) {
            $this->exit("Task with id '$id' does not exist");
        }
        $this->consoleView('task', [
            'task' => $this->formatTaskForTable($task),
            'wordWrap' => $this->getTableWordWrap(),
            'boardName' => get_selected_board_name(),
            'timezone' => $this->getPreferenceOrDefault(Preference::DATE_TIMEZONE, 'America/Indiana/Indianapolis'),
        ]);
    }
}
