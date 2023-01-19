<?php

it('can create new board.', function () {
    fresh_project_dir(name: null);

    $testBoardPath = project_path('boards/tests');

    $this->artisan('new:board', [
        'name'=>'tests'
    ])->assertExitCode(0);

    expect(is_dir($testBoardPath))->toBeTrue();
    expect(is_file($testBoardPath.'/database'))->toBeTrue();
});

it('throws error when selecting board that doesnt exist', function () {
    fresh_project_dir(name: null);

    $command = $this->artisan('select', ['name'=>'tests'])->assertExitCode(1);

    $command->expectsOutputToContain("The board 'tests' does not exist");
});

it('can select board.', function () {
    fresh_project_dir(name: 'tests');

    $this->artisan('select', ['name'=>'tests'])->assertExitCode(0);

    expect(file_get_contents(project_path('default-board')))->toBe('tests');
});

it('can show board', function () {
    fresh_project_dir(name: 'tests');

    $this->artisan('new:status', ['name'=>'Todo'])->assertExitCode(0);
    $this->artisan('new:tag', ['name'=>'Urgent'])->assertExitCode(0);
    $this->artisan('new:tag', ['name'=>'Devops'])->assertExitCode(0);
    
    $this->artisan('new:task', [
        'description'=>'Something',
        '--status'=>'Todo',
        '--tag'=>['Urgent', 'Devops'],
    ])->assertExitCode(0);

    $command = $this->artisan('show:board')->assertExitCode(0);

    // not entirely sure best way to assert view table output, this feels good enough?
    $command->expectsOutputToContain('ID: 1');
});
