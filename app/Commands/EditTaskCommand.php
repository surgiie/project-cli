<?php

namespace App\Commands;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Surgiie\Console\Concerns\WithTransformers;
use Surgiie\Console\Concerns\WithValidation;

class EditTaskCommand extends BaseCommand
{
    use WithTransformers, WithValidation;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'edit:task 
                                {description? : The description of the task.}
                                {--id= : The id of the task to edit.}
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
    protected $description = 'Edit a task.';

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

     /**
     * The command requirements to check.
     *
     * @return array
     */
    public function requirements()
    {
        return array_merge(parent::requirements(), [
            function () {
                $this->configureDatabaseConnection();
            },
        ]);
    }

    /**
     * The input validation rules.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'due-date' => ['nullable', 'date'],
            'tag' => ['nullable', function ($_, $givenTags, $fail) {
                $tags = DB::table('tags')->pluck('name')->all();
                foreach ($givenTags as $tag) {
                    $tag = Str::kebab($tag);
                    if (! in_array($tag, $tags)) {
                        $fail("Tag does not exist: $tag");
                    }
                }
            }],
            'status' => ['nullable', function ($_, $givenStatus, $fail) {
                $status = DB::table('statuses')->where('name', Str::kebab($givenStatus))->first();

                if (is_null($status)) {
                    $fail("Status does not exist: $givenStatus");
                }
            }],
        ];
    }

    /**
     * Execute the console command.
     * 
     * @return int
     */
    public function handle()
    {
        if(!$this->data->get("title") && !$this->data->get("status") && !$this->data->get("tags") && !$this->data->get("description") && !$this->data->get("due_date")){
            $this->exit("No update data given, nothing to do.", level: "warn");
        }

        $task = DB::table('tasks')->where('id', $id = $this->getOrAskForInput('id', ['rules'=> ['integer']]))->first();

        if (is_null($task)) {
            $this->exit("Task with id '$id' does not exist");
        }

        if (! $this->data->get('description') && $this->data->get('editor')) {
            $this->data->put('description', $this->openTmpFile(existingContent: $task->description));
            // if for some reason TERMINAL_EDITOR is not terminal based, fall back to asking for description when --editor is passed.
            $this->data->put('description', $this->getOrAskForInput('description'));
        }

        $task = $this->runTask('Edit task', function () use ($id, $task) {
            $title = $this->data->get('title');
            $status = $this->data->get('status');
            $description = $this->data->get('description');
            $dueDate = $this->data->get('due-date');
            $tags = implode(',', $this->data->get('tag', []));
            
            $result = DB::table('tasks')->where('id', $id)->update([
                'title' => $title ?: $task->title,
                'description' => $description ?: $task->description,
                'status' => $status ?: Str::kebab($this->data->get('status')),
                'tags' => empty($tags) ?$task->tags: $tags,
                'due_date' => $dueDate ? (new Carbon($dueDate))->toDateTimeString() : $task->due_date,
                'updated_at' => now(),
            ]);

            return $result != 0;
        });

        if($success = $task->succeeded()){
            $this->newLine();

            $this->components->info('Task was updated successfully.');
        }

        return $success ? 0: 1;
    }
}
