<?php

use Illuminate\Support\Facades\DB;


it('can create new task tag.', function () {
    fresh_project_dir(name: 'tests');

    $this->artisan("new:tag Urgent")->assertExitCode(0);

    $status = DB::connection('board')->table('tags')->where('name', 'urgent')->first();

    expect($status->name)->toBe("urgent");
    expect($status->display)->toBe("Urgent");
});


it('cannot create duplicate task tag.', function () {
    fresh_project_dir(name: 'tests');

    $this->artisan("new:tag Urgent")->assertExitCode(0);
    
    $command = $this->artisan("new:tag Urgent")->assertExitCode(1);
    $command->expectsOutputToContain("A tag 'Urgent' already exists for this board.");
});
