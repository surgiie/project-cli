<?php

namespace App\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Surgiie\Console\Concerns\WithTransformers;
use Surgiie\Console\Concerns\WithValidation;

class NewTagCommand extends BaseCommand
{
    use WithValidation, WithTransformers;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'new:tag {name : The name of the tag.}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create a new task tag.';

    /**
     * The command requirements to run.
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
     * The input transformer rules.
     *
     * @return int
     */
    public function transformers()
    {
        return [
            'name' => 'trim',
        ];
    }

    /**
     * The input validation rules.
     *
     * @return int
     */
    public function rules()
    {
        return [
            'name' => [function ($_, $name, $fail) {
                $tag = DB::table('tags')->where('name', Str::kebab($name))->first();
                if (! is_null($tag)) {
                    $fail("A tag '$name' already exists for this board.");
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
        $name = $this->data->get('name');

        $task = $this->runTask("Create new task tag called $name", function () {
            $name = Str::kebab($givenValue = $this->data->get('name'));

            return DB::table('tags')->insert([
                'name' => $name,
                'display' => $givenValue,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        $success = $task->succeeded();
        if ($success) {
            $this->newLine();

            $this->components->info("The task tag '$name' was created successfully.");
        }

        return $success ? 0 : 1;
    }
}
