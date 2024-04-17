<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ParseStatistic;
class ParseNotEndCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:notEnd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ParseStatistic::whereDoesntHave('job')
                        ->where('file','=',null)
                        ->update(
                            [
                                'status' => 'error load'
                            ]
                        );
    }
}
