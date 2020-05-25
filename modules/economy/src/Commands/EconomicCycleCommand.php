<?php

namespace Modules\Economy\Commands;

use Modules\Economy\Actions\PerformEconomicCycle;
use Illuminate\Console\Command;

class EconomicCycleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'economy:cycle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform economic cycle';

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
     * @return void
     */
    public function handle()
    {
        (new PerformEconomicCycle)->run();
        $this->info("success !");
    }
}
