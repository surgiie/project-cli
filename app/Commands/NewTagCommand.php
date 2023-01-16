<?php

namespace App\Commands;

use Illuminate\Support\Str;
use App\Commands\BaseCommand;
use Illuminate\Support\Facades\DB;
use Surgiie\Console\Concerns\WithValidation;

class NewTagCommand extends BaseCommand
{
    use WithValidation;
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

    /**Command requirements.*/
    public function requirements()
    {
        return array_merge(parent::requirements(), [
            function () {
                $this->configureDatabaseConnection();
            }
        ]);
    }

    /**Command input validation rules.*/
    public function rules()
    {
        return [
            'name' => [function ($_, $name, $fail) {          
                $tag = DB::table('tags')->where('name', Str::kebab($name))->first();
                if (! is_null($tag)) {
                    $fail("A tag '$name' already exists for this board.");
                }
            }]
        ];
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name =  $this->data->get('name');

        $this->runTask("Create new task tag called $name", function () {
            $name = Str::kebab($givenValue = $this->data->get('name'));

            return DB::table('tags')->insert([
                'name'=>$name,
                'display' => $givenValue,
            ]);
        });

        $this->newLine();

        $this->components->info("The task tag '$name' was created successfully.");
    }
}
