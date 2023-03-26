<?php

namespace App\Commands;

use App\Concerns\FormatsForTableOutput;
use Illuminate\Support\Facades\DB;

class ShowPreferencesCommand extends BaseCommand
{
    use FormatsForTableOutput;

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

    /**
     * The command input validation to run.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => ['numeric'],
        ];
    }

    /**
     * The command input transformers to run.
     */
    public function transformers(): array
    {
        return [
            'id' => ['intval'],
        ];
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
     * @return int
     */
    public function handle()
    {
        $preferences = DB::table('preferences')->get();

        $headers = ['Name', 'Value'];
        $rows = [];

        foreach ($preferences as $preference) {
            $rows[] = [$preference->name, $preference->value];
        }

        if (empty($rows)) {
            $this->exit('No preferences saved.', level: 'warn');
        }

        $this->table($headers, $rows);

        return 0;
    }
}
