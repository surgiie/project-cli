<?php

use Illuminate\Support\Facades\DB;

it('can set preferences.', function () {
    fresh_project_dir(name: 'tests');

    $this->artisan('new:status Todo')->assertExitCode(0);
    $this->artisan('new:status Doing')->assertExitCode(0);
    $this->artisan('new:status Done')->assertExitCode(0);

    $this->artisan("set STATUS_ORDER 'Todo,Doing,Done'")->assertExitCode(0);

    $preference = DB::connection('board')->table('preferences')->where('name', 'STATUS_ORDER')->first();

    expect($preference->value)->toBe('Todo,Doing,Done');
});

it('cannot set invalid preferences.', function () {
    fresh_project_dir(name: 'tests');

    $command = $this->artisan('set something-bad "Foobar"')->assertExitCode(1);

    $preference = DB::connection('board')->table('preferences')->where('name', 'something-bad')->first();

    expect($preference)->toBeNull();

    $command->expectsOutputToContain("The preference name 'SOMETHING_BAD' is not valid or accepted by this cli.");
});
