<?php

namespace App\Commands;

use Symfony\Component\Finder\Finder;


class SelectBoardCommand extends BaseCommand
{

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'select {name? : The name of the board to select.}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Select the default board the cli should work with.';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->data->get('name');


        if (!is_dir(project_path("boards/$name"))) {
            $this->exit("The board '$name' does not exist");
        }


        if (!$name) {
            $files = (new Finder())->directories()->in(project_path('boards'))->depth(0);

            $boards = [];

            foreach ($files as $file) {

                $name = $file->getBaseName();

                $boards[$name] = $name;
            }

            $name = $this->menu("Select a board:", $boards)->open();
        }

        $task = $this->runTask("Save default board", function () use ($name) {
            $defaultFile = project_path("default-board");
            return file_put_contents($defaultFile, $name) !== false;
        });

        if ($task->succeeded()) {
            $this->newLine();
            $this->components->info("Set the default board to: $name");
        }
    }
}
