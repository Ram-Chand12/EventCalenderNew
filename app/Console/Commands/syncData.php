<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Traits\wordpressSyncer;
class syncData extends Command
{
    use wordpressSyncer;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'syncData:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Data to wordpress';

    /**
     * Execute the console command.
     */
    public function handle()
    {
      
        $object = $this->SyncWordpressData();
    }
}
