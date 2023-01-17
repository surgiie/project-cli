<?php

namespace App\Commands;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Surgiie\Console\Concerns\WithValidation;
use Surgiie\Console\Concerns\WithTransformers;

class SetCommand extends BaseCommand
{
    use WithValidation, WithTransformers;

    /**The allowed preference names that can be set. */
    protected $allowedNames = [
        // the order of status columns on boards
        'status-order',
    ];

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'set {name : The name of the preference.}
                                {value : The value of the preference.}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Set a preference value in the preferences table';

    /**The transformers to run on arguments.*/
    public function transformers()
    {
        return [
            'name' => 'trim',
            'value' => 'trim',
        ];
    }

    /**The validation rules for arguments.*/
    public function rules()
    {
        return ['name' => function ($_, $v, $fail) {
            if (! in_array($v, $this->allowedNames)) {
                $fail("The preference name '$v' is not valid or accepted by this cli.");
            }
        }, 'value'=>['required']];
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
    
    protected function getPreferenceOrDefault(string $name, string $default)
    {

    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->data->get('name');
        $value = $this->data->get('value');

        $validateMethod = "validate".Str::studly($name);

        if(method_exists($this, $validateMethod)){
            $this->$validateMethod();
        }

        $task = $this->runTask("Set $name preference", function () {
            return DB::table('preferences')->updateOrInsert([
                'name' => $this->data->get('name'),
            ], [
                'name' => $this->data->get('name'),
                'value' => $this->data->get('value'),
            ]);
        });

        if($task->succeeded()){
            $this->newLine();
            $this->components->info("Set the $name preference to: $value");
        }
    }

    /**Validate that the status order value*/
    protected function validateStatusOrder()
    {
        $statuses = explode(',', $this->data->get('value'));
    
        foreach($statuses as $status){
            $record = DB::table('statuses')->where('name', Str::kebab($status))->first();
            if(is_null($record)){
                $this->exit("Status does not exist: $status");
            }
        }

        if(count($statuses) != ($recordCount = DB::table('statuses')->count())){
            $this->exit("Given list of status does not match table count of: $recordCount");
        }
    }
}
