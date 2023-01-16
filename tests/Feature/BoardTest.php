<?php


it('can create new board.', function () {
    fresh_project_dir(name: null);

    $testBoardPath = project_path('boards/tests');

    $this->artisan("new:board tests")->assertExitCode(0);

    expect(is_dir($testBoardPath))->toBeTrue();
    expect(is_file($testBoardPath."/database"))->toBeTrue();
});

it('throws error when selecting board that doesnt exist', function () {
    fresh_project_dir(name: null);

    $command = $this->artisan("select tests")->assertExitCode(1);

    $command->expectsOutputToContain("The board 'tests' does not exist");
});

it('can select board.', function () {
    fresh_project_dir(name: 'tests');

    $this->artisan("select tests")->assertExitCode(0);

    expect(file_get_contents(project_path('default-board')))->toBe('tests');
});