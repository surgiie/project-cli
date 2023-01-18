<?php

namespace App\Commands;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Surgiie\Console\Concerns\WithTransformers;
use Surgiie\Console\Concerns\WithValidation;

class NewTaskCommand extends BaseCommand
{
    use WithTransformers, WithValidation;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'new:task 
                                {description? : The description of the task.}
                                {--title= : The title of the task.}
                                {--due-date= : The due date for the task.}
                                {--tag=* : Any tags to assign to the task.}
                                {--status= : The status to assign to the task.}
                                {--editor : Create the description of the task in a tmp file using the set terminal editor. }';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create a new board task.';

    /**Transform inputs.*/
    public function transformers()
    {
        return [
            'tags.*' => 'trim',
            'title' => 'trim',
            'due-date' => 'trim',
            'status' => 'trim',
            'description' => 'trim',
        ];
    }

    /**Input validation rules.*/
    public function rules()
    {
        return [
            'due-date' => ['nullable', 'date'],
            'tag' => [function ($_, $givenTags, $fail) {
                $tags = DB::table('tags')->pluck('name')->all();
                foreach ($givenTags as $tag) {
                    $tag = Str::kebab($tag);
                    if (! in_array($tag, $tags)) {
                        $fail("Tag does not exist: $tag");
                    }
                }
            }],
            'status' => ['required', function ($_, $givenStatus, $fail) {
                $status = DB::table('statuses')->where('name', Str::kebab($givenStatus))->first();

                if (is_null($status)) {
                    $fail("Status does not exist: $givenStatus");
                }
            }],
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
        if (! $this->data->get('description') && $this->data->get('editor')) {
            $this->data->put('description', $this->openTmpFile());
        }

        $description = $this->getOrAskForInput('description');

        $this->runTask('Create new task', function () use ($description) {
            $dueDate = $this->data->get('due-date');

            return DB::table('tasks')->insert([
                'title' => $this->data->get('title'),
                'description' => $description,
                'status' => Str::kebab($this->data->get('status')),
                'tags' => implode(',', $this->data->get('tag', [])),
                'due_date' => $dueDate ? (new Carbon($dueDate))->toDateTimeString() : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        $this->newLine();

        $this->components->info('Task was created successfully.');
    }
}
