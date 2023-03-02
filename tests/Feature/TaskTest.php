<?php

use Illuminate\Support\Facades\DB;

it('can create new task.', function () {
    fresh_project_dir(name: 'tests');

    $this->artisan('new:status Todo')->assertExitCode(0);
    $this->artisan('new:tag Urgent')->assertExitCode(0);
    $this->artisan('new:tag Devops')->assertExitCode(0);
    $this->artisan('new:task Something --status=Todo --tag=Urgent --tag=Devops')->assertExitCode(0);

    $task = DB::connection('board')->table('tasks')->where('description', 'Something')->first();

    expect($task->description)->toBe('Something');
    expect($task->tags)->toBe('Urgent,Devops');
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

it('can show task', function () {
    fresh_project_dir(name: 'tests');

    $this->artisan('new:status Todo')->assertExitCode(0);
    $this->artisan('new:tag Urgent')->assertExitCode(0);
    $this->artisan('new:tag Devops')->assertExitCode(0);
    $this->artisan('new:task Something --status=Todo --tag=Urgent --tag=Devops')->assertExitCode(0);

    $command = $this->artisan('show:task 1')->assertExitCode(0);

    $command->expectsOutputToContain('ID: 1');
});

it('can edit task.', function () {
    fresh_project_dir(name: 'tests');

    $this->artisan('new:status Todo')->assertExitCode(0);
    $this->artisan('new:tag Urgent')->assertExitCode(0);
    $this->artisan('new:tag Devops')->assertExitCode(0);
    $this->artisan('new:tag NotUrgent')->assertExitCode(0);
    $this->artisan('new:tag NotDevops')->assertExitCode(0);
    $this->artisan('new:task Something --title="test" --status=Todo --tag=Urgent --tag=Devops')->assertExitCode(0);

    $task = DB::connection('board')->table('tasks')->where('title', 'test')->first();

    expect($task->description)->toBe('Something');
    expect($task->tags)->toBe('Urgent,Devops');

    $this->artisan('edit:task SomethingElse --id="1" --title="test" --status=Todo --tag=NotUrgent --tag=NotDevops')->assertExitCode(0);

    $task = DB::connection('board')->table('tasks')->where('title', 'test')->first();

    expect($task->description)->toBe('SomethingElse');
    expect($task->tags)->toBe('NotUrgent,NotDevops');
});

it('cannot edit task with bad status.', function () {
    fresh_project_dir(name: 'tests');

    $this->artisan('new:status Todo')->assertExitCode(0);
    $this->artisan('new:tag Urgent')->assertExitCode(0);
    $this->artisan('new:tag Devops')->assertExitCode(0);
    $this->artisan('new:task Something --title="test" --status=Todo --tag=Urgent --tag=Devops')->assertExitCode(0);

    $command = $this->artisan('edit:task SomethingElse --id=1 --status=DontExist')->assertExitCode(1);

    $command->expectsOutputToContain('Status does not exist: DontExist.');

    $task = DB::connection('board')->table('tasks')->where('title', 'test')->first();

    expect($task->description)->not->toBe('SomethingElse');
});

it('cannot edit task with bad tag.', function () {
    fresh_project_dir(name: 'tests');

    $this->artisan('new:status Todo')->assertExitCode(0);
    $this->artisan('new:tag Urgent')->assertExitCode(0);
    $this->artisan('new:tag Devops')->assertExitCode(0);

    $this->artisan('new:task Something --title="test" --status=Todo --tag=Urgent --tag=Devops')->assertExitCode(0);

    $command = $this->artisan('edit:task SomethingElse --id=1 --status=Todo --tag=example')->assertExitCode(1);

    $command->expectsOutputToContain('Tag does not exist: example.');

    $task = DB::connection('board')->table('tasks')->where('title', 'test')->first();

    expect($task->description)->not->toBe('SomethingElse');
});

it('can delete task.', function () {
    fresh_project_dir(name: 'tests');

    $this->artisan('new:status Todo')->assertExitCode(0);
    $this->artisan('new:tag Urgent')->assertExitCode(0);
    $this->artisan('new:tag Devops')->assertExitCode(0);
    $this->artisan('new:task Something --status=Todo --tag=Urgent --tag=Devops')->assertExitCode(0);

    $task = DB::connection('board')->table('tasks')->where('description', 'Something')->first();

    expect($task->description)->toBe('Something');
    expect($task->tags)->toBe('Urgent,Devops');

    $this->artisan('remove:task', ['--id' => ['1']])->assertExitCode(0);
    $task = DB::connection('board')->table('tasks')->where('description', 'Something')->first();
    expect($task)->toBeNull();
});
