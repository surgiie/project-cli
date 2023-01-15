<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Collection;
use Surgiie\Console\Command as ConsoleCommand;

class InspireCommand extends ConsoleCommand
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'inspire';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Display an inspiring quote';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $quote = Collection::make([
            '“The world isn’t perfect. But it’s there for us, doing the best it can… that’s what makes it so damn beautiful.” — Roy Mustang (Full Metal Alchemist)',
            '“To know sorrow is not terrifying. What is terrifying is to know you can’t go back to happiness you could have.” — Matsumoto Rangiku (Bleach)',
            '“Fear is not evil. It tells you what weakness is. And once you know your weakness, you can become stronger as well as kinder.” — Gildarts Clive (Fairy Tail)',
            '“Whatever you lose, you’ll find it again. But what you throw away you’ll never get back.” — Kenshin Himura (Rurouni Kenshin: Meiji Kenkaku Romantan)',
            '“If you don’t take risks, you can’t create a future!” — Monkey D. Luffy (One Piece)',
            "People's lives don't end when they die. It ends when they lose faith. - Itachi Uchiha (Naruto)",
            "People live their lives bound by what they accept as correct and true. That's how they define ‘reality’. But what does it mean to be ‘correct’ or ‘true’? Merely vague concepts... Their ‘reality’ may all be a mirage. Can we consider them to simply be living in their own world, shaped by their beliefs? - Itachi Uchiha (Naruto)",
        ])->random();

        $this->consoleView('inspire', [
            'quote' => $quote,
        ]);
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function schedule(Schedule $schedule)
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
