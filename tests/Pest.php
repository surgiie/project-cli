<?php

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Surgiie\Console\Command;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(Tests\TestCase::class)->in('Feature');

uses()->beforeAll(function () {
    Command::disableConcurrentTasks();
})->in(__DIR__);

function fresh_project_dir(?string $name = 'tests')
{
    $fs = new Filesystem;

    $basePath = __DIR__.'/.project';

    putenv("PROJECT_CLI_BASE_PATH=$basePath");

    $fs->deleteDirectory($basePath);

    @mkdir($basePath);

    if (! is_null($name)) {
        @mkdir($basePath."/boards/$name", recursive: true);
        touch($basePath."/boards/$name/database");
        configure_board_database_connection($name);
        Artisan::call('migrate');
        file_put_contents($basePath.'/default-board', $name);
    }
}

function configure_board_database_connection(string $name)
{
    config([
        'database.connections.board' => array_merge(config('database.connections.board'), [
            'database' => project_path("boards/$name/database"),
        ]),
    ]);
}
