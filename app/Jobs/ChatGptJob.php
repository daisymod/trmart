<?php

namespace App\Jobs;

use App\Models\CatalogItem;
use App\Requests\GptRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ChatGptJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 9999999;
    public $tries = 10;
    public $backoff = [2, 10, 20];


    public $record;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($record)
    {
        $this->record = $record;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $request = new GptRequest();



        $dataNameKz = $request->getData($this->record->name_tr,'казахский',null,'name');
        if (isset($dataNameKz['data']['choices'][0]['message']['content'])){
            $this->record->name_kz = $dataNameKz['data']['choices'][0]['message']['content'];
        }

        $dataNameRu = $request->getData($this->record->name_tr,'русский',null,'name');
        if (isset($dataNameRu['data']['choices'][0]['message']['content'])){
            $this->record->name_ru = $dataNameRu['data']['choices'][0]['message']['content'];
        }

        $res = str_replace( array( '\'','\'',"\r\n","\n", '"',
            ',' , ';', '<', '>' ), ' ', $this->record->body_tr);

        if (!empty($res)){
            $dataBodyRu = $request->getData($this->record->body_tr,'русский',true,'body');
            $dataBodyKz = $request->getData($this->record->body_tr,'казахский',null,'body');
            if (isset($dataBodyKz['data']['choices'][0]['message']['content'])){
                $this->record->body_kz = $dataBodyKz['data']['choices'][0]['message']['content'];
            }
            if (isset($dataBodyRu['data']['choices'][0]['message']['content'])){
                $this->record->body_ru = $dataBodyRu['data']['choices'][0]['message']['content'];
            }
        }

        CatalogItem::query()->where('id','=',$this->record->id)
            ->update([
                'name_ru' => $this->record->name_ru,
                'name_kz' => $this->record->name_kz,
                'body_ru' => $this->record->body_ru,
                'body_kz' => $this->record->body_kz,
            ]);
    }
}
