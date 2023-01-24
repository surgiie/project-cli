<?php

namespace App\Commands;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Surgiie\Console\Concerns\WithTransformers;
use Surgiie\Console\Concerns\WithValidation;

class RemoveTaskCommand extends BaseCommand
{
    use WithTransformers, WithValidation;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'remove:task {--id=* : The ids of the tasks to edit.}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Remove tasks by id.';

    /**Transform inputs.*/
    public function transformers()
    {
        return [
            'id.*' => 'trim',
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
            'id'=>['required', 'array'],
            'id.*' => ['numeric'],
        ];
    }

    /**
     * Execute the console command.
     * 
     * @return int
     */
    public function handle()
    {
        $failures = false;
        
        foreach($this->data->get('id') as $id){
            if(is_null($task = DB::table('tasks')->where('id', $id)->first())){
                $this->components->warn("Task with id '$id' does not exit");
                continue;
            }
            $task = $this->runTask("Remove task with id: $id", function () use ($id) {
                
                $result = DB::table('tasks')->where('id', $id)->delete();
                
                return $result != 0;
            });
            
            if($task->succeeded()){
                $this->newLine();
                
                $this->components->info("Task with id '$id' was deleted successfully.");
            }else{
                $failures = true;
            }
        }

        return $failures ? 1: 0;
    }
}
