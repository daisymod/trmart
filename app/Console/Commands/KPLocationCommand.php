<?php

namespace App\Console\Commands;

use App\Imports\KPLocationImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class KPLocationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kp:li';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kaz Post Location Import';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ini_set('memory_limit', '-1');
        Excel::import(new KPLocationImport(), public_path('excel/location.xlsx'));
        dd('finish');
    }
}
