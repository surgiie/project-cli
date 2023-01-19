<?php

namespace App\Commands;

use App\Enums\Preference;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Surgiie\Console\Concerns\WithTransformers;
use Surgiie\Console\Concerns\WithValidation;

class SetCommand extends BaseCommand
{
    use WithValidation, WithTransformers;

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

    /**
     * The command transformers to apply to input/options.
     * 
     * @return array
     */
    public function transformers()
    {
        return [
            'name' => ['trim', fn ($v) => $this->normalizeToUpperSnakeCase($v)],
            'value' => 'trim',
        ];
    }

    /**
     * The command input validation to run.
     * 
     * @return array
     */
    public function rules()
    {
        return ['name' => function ($_, $v, $fail) {
            if (! Preference::has($v)) {
                $fail("The preference name '$v' is not valid or accepted by this cli.");
            }
        }, 'value' => ['required']];
    }

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->data->get('name');
        $value = $this->data->get('value');

        $validateMethod = 'validate'.Str::studly(strtolower($name));

        if (method_exists(Preference::class, $validateMethod)) {
            Preference::$validateMethod($value);
        }

        $task = $this->runTask("Set $name preference", function () {
            return DB::table('preferences')->updateOrInsert([
                'name' => $this->data->get('name'),
            ], [
                'name' => $this->data->get('name'),
                'value' => $this->data->get('value'),
            ]);
        });

        if ($success = $task->succeeded()) {
            $this->newLine();
            $this->components->info("Set the $name preference to: $value");
        }

        
        return $success ? 0: 1;
    }
}
