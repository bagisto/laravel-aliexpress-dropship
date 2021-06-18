<?php

namespace Webkul\Dropship\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aliexpress:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will prepare the AliExpress package';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Artisan::call('migrate', [], $this->getOutput());
        
        Artisan::call('optimize', [], $this->getOutput());
        
        Artisan::call('vendor:publish', [
            '--provider' => "Webkul\Dropship\Providers\DropshipServiceProvider",
            '--force'    => true
        ], $this->getOutput());
    }
}
