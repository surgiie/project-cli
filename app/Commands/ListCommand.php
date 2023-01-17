<?php

namespace App\Commands;

use Illuminate\Support\Facades\DB;

class ListCommand extends BaseCommand
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'list';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Output the board with current tasks.';


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
      
    }
}
