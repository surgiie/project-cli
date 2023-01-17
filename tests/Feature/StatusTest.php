<?php

use Illuminate\Support\Facades\DB;

it('can create new task status.', function () {
    fresh_project_dir(name: 'tests');

    $this->artisan('new:status Testing')->assertExitCode(0);

    $status = DB::connection('board')->table('statuses')->where('name', 'testing')->first();

    expect($status->name)->toBe('testing');
    expect($status->display)->toBe('Testing');
});

it('cannot create duplicate task status.', function () {
    fresh_project_dir(name: 'tests');

    $this->artisan('new:status Testing')->assertExitCode(0);

    $command = $this->artisan('new:status Testing')->assertExitCode(1);
    $command->expectsOutputToContain("A status 'Testing' already exists for this board.");
});
