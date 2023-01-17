<?php

use Illuminate\Support\Facades\DB;

it('can create new task.', function () {
    fresh_project_dir(name: 'tests');

    $this->artisan('new:status Todo')->assertExitCode(0);
    $this->artisan('new:task Something --status=Todo')->assertExitCode(0);

    $task = DB::connection('board')->table('tasks')->where('description', 'Something')->first();

    expect($task->description)->toBe('Something');
});

it('cannot create new task without status.', function () {
    fresh_project_dir(name: 'tests');

    $command = $this->artisan('new:task Something')->assertExitCode(1);
    $command->expectsOutputToContain('The --status option is required.');

    $task = DB::connection('board')->table('tasks')->where('description', 'Something')->first();

    expect($task)->toBeNull();
});

it('cannot create new task with bad status.', function () {
    fresh_project_dir(name: 'tests');

    $command = $this->artisan('new:task Something --status=DontExist')->assertExitCode(1);

    $command->expectsOutputToContain('Status does not exist: DontExist.');

    $task = DB::connection('board')->table('tasks')->where('description', 'Something')->first();

    expect($task)->toBeNull();
});

it('cannot create new task with bad tag.', function () {
    fresh_project_dir(name: 'tests');

    $this->artisan('new:status Todo')->assertExitCode(0);

    $command = $this->artisan('new:task Something --status=Todo --tag=example')->assertExitCode(1);

    $command->expectsOutputToContain('Tag does not exist: example.');

    $task = DB::connection('board')->table('tasks')->where('description', 'Something')->first();

    expect($task)->toBeNull();
});
