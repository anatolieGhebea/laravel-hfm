<?php

namespace Ghebby\LaravelHfm\Commands;

use Illuminate\Console\Command;

class LaravelHfmCommand extends Command
{
    public $signature = 'laravel-hfm';

    public $description = 'Hfm test command';

    public function handle()
    {
        $this->comment('All done');
    }
}
