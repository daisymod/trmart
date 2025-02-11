<?php

namespace App\Jobs;

use App\Services\CatalogItemsExcelLoadService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class CatalogItemsExcelLoadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 9999999;
    public $tries = 10;
    public $backoff = [2, 10, 20];

    protected  $excelArray;
    protected  $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($excelArray,$user)
    {
        $this->user = $user;
        $this->excelArray = $excelArray;
    }

    /**
     * Execute the job.
     *
     * @return array
     */
    public function handle()
    {

       return  CatalogItemsExcelLoadService::load($this->excelArray,$this->user,$this->job->getJobId());
    }
}
