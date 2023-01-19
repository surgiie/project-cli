<?php

namespace App\Commands;

use App\Enums\Preference;
use Illuminate\Support\Facades\DB;
use App\Concerns\FormatsForTableOutput;
use Surgiie\Console\Concerns\WithValidation;
use Surgiie\Console\Concerns\WithTransformers;

class ShowPreferencesCommand extends BaseCommand
{
    use WithValidation, WithTransformers, FormatsForTableOutput;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'show:preferences';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Show current preferences settings.';

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
        $preferences = DB::table('preferences')->get();

        $headers = ["Name", "Value"];
        $rows = [];

        foreach ($preferences as $preference){
            $rows[] = [$preference->name, $preference->value];
        }

        if(empty($rows)){
            $this->exit("No preferences saved.", level: "warn");
        }

        $this->table($headers, $rows);
    }
}
