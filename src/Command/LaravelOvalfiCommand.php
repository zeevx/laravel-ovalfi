<?php

namespace Zeevx\LaravelOvalfi\Command;

use Illuminate\Console\Command;

class LaravelOvalfiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ovalfi:publish {--force : Overwrite any existing config file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish laravel ovalfi config file';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->call('vendor:publish', [
            '--tag' => 'laravel-ovalfi-config',
            '--force' => $this->option('force'),
        ]);
    }
}
