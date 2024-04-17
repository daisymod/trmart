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
use Illuminate\Support\Facades\Bus;

class TestLoadChainJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected array $excelArray = [];
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
        $rows = count($this->excelArray);
        $peace = ceil($rows / 1000) ?? 1;
        $arrays = array_chunk($this->excelArray, $peace) ?? 1;
        $jobs = [];
        foreach ($arrays as $i => $data) {
            $jobs[] = new CatalogItemsExcelLoadJob($data, $this->user);
        }
        Bus::chain($jobs)->dispatch();
    }
}
