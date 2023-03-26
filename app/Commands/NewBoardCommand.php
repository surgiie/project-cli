<?php

namespace App\Commands;

use Illuminate\Support\Str;

class NewBoardCommand extends BaseCommand
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'new:board {name : The name of the board.}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create a new board.';

    /**
     * The input transformer rules.
     */
    public function transformers(): array
    {
        return [
            'name' => 'trim',
        ];
    }

    /**
     * The input validation rules.
     */
    public function rules(): array
    {
        return [
            'name' => [function ($_, $name, $fail) {
                if (is_dir(project_path("boards/$name"))) {
                    $fail("The project board '$name' already exists.");
                }
            }],
        ];
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        @mkdir(project_path('boards'), recursive: true);

        $name = Str::kebab($this->data->get('name'));

        if (is_dir(project_path("boards/$name"))) {
            $this->exit("The board '$name' already exists");
        }

        $this->runTask("Create new project board called $name", function () use ($name) {
            @mkdir(project_path("boards/$name"));

            touch(project_path("boards/$name/database"));
        });

        $task = $this->runTask("Create $name board database", function () use ($name) {
            $this->configureDatabaseConnection($name);
            $this->callSilently('migrate', ['--force' => true]);
        });

        if ($success = $task->succeeded()) {
            $this->newLine();

            $this->components->info('The project board was created successfully.');

            if (get_selected_board_name() === false) {
                $this->callSilently('select', ['name' => $name]);
            }
        }

        return $success ? 0 : 1;
    }
}
