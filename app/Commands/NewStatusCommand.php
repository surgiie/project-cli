<?php

namespace App\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Surgiie\Console\Concerns\WithTransformers;
use Surgiie\Console\Concerns\WithValidation;

class NewStatusCommand extends BaseCommand
{
    use WithValidation, WithTransformers;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'new:status {name : The name of the status.}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create a new task status.';

    /**Transform inputs.*/
    public function transformers()
    {
        return [
            'name' => 'trim',
        ];
    }

    /**
     * The command requirements.
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
            'name' => [function ($_, $name, $fail) {
                $status = DB::table('statuses')->where('name', Str::kebab($name))->first();
                if (! is_null($status)) {
                    $fail("A status '$name' already exists for this board.");
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

        $task = $this->runTask("Create new task status called $name", function () {
            $name = Str::kebab($givenValue = $this->data->get('name'));

            return DB::table('statuses')->insert([
                'name' => $name,
                'display' => $givenValue,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        $success = $task->succeeded();
        if($success){
            $this->newLine();

            $this->components->info("The task status '$name' was created successfully.");
        }
        
        return $success ? 0: 1;


    }
}
