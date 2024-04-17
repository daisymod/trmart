<?php

namespace App\Console\Commands;

use App\Services\OrderService;
use Illuminate\Console\Command;

class CheckTrackingDelivery extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:tracking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check tracking delivery';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        protected OrderService $orderService
    )
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->orderService->tracking();
        return 0;
    }
}
