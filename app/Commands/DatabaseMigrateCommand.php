<?php

namespace App\Commands;

use Exception;
use App\Commands\BaseCommand;
use Illuminate\Support\Facades\Artisan;

class DatabaseMigrateCommand extends BaseCommand
{
    
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'db:migrate';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run migrations for selected board.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $task = $this->runTask("Run board database migrations", function ($task){

            $this->configureDatabaseConnection();

            try {
                Artisan::call('migrate');
            }catch (Exception $e){
                $task->data(['error'=>$e->getMessage()]);
                return false;
            }
        });

        $data = $task->getData();
        
        $this->newLine();

        if($data["error"] ?? false){
            $this->exit("Failed to run migrations:". $data["error"]);
        }

        $this->components->info("The board database migrations ran successfully.");
    }
}
