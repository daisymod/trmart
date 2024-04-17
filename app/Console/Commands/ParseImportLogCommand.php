<?php

namespace App\Console\Commands;

use App\Models\ParseImport;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ParseImportLogCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse-import:check';

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
        $logs = ParseImport::where('uuid','!=',null)
                ->where('status','=','in progress')
                ->get();

        foreach ($logs as $log){
            $data = DB::table('failed_jobs')
                ->where('uuid','LIKE',"%".$log->uuid."%")
                ->first();

            if (!empty($data->exception)){
                $error = explode("\n", $data->exception);
                $log->error  = $error[0];
                $end = Carbon::now();
                $minuteDiff = $end->diffInSeconds($data->failed_at);
                $log->status = 'error';
                $log->time  = $minuteDiff;
                $log->save();
            }
        }
    }
}
