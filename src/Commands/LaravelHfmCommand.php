<?php

namespace AnatolieGhebea\LaravelHfm\Commands;

use Illuminate\Console\Command;

class LaravelHfmCommand extends Command
{
    public $signature = 'laravel-hfm';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
