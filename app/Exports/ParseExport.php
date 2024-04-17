<?php

namespace App\Exports;

use App\Models\CatalogCharacteristic;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;


class ParseExport implements FromArray,WithColumnWidths,ShouldQueue
{

    use Exportable;
    public function __construct(public array $data,public string $locale)
    {
    }

    public function array(): array
    {

        $result = array();


        app()->setLocale($this->locale);
        $lang = $this->locale;


        $headers = [
            trans('system.name_ru'),
            trans('system.name_tr'),
            trans('system.name_kz'),
            trans('system.equipment'),
            trans('system.body_ru'),
            trans('system.body_tr'),
            trans('system.body_kz'),
            trans('system.images').' Main photo',
            trans('catalog_item.form.barcode'),
            trans('system.sale'),
            trans('system.price'),
            trans('system.color'),
            trans('system.size'),
            trans('system.count'),
            trans('system.status'),
            trans('system.active'),
            trans('system.catalog'),
            trans('system.user'),
            trans('system.brand'),
            trans('system.equipment'). 'RUS',
            trans('system.equipment'). 'KZ',
            trans('catalog_item.form.weight'),
            trans('system.images').' Gallery photo - 1',
            trans('system.images').' Gallery photo - 2',
            trans('system.images').' Gallery photo - 3',
            trans('system.images').' Gallery photo - 4',
            trans('system.images').' Gallery photo - 5',
            trans('system.images').' Gallery photo - 6',
        ];


        array_push($result,$headers);
        array_push($result,$this->data);

      return $result;
    }



    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 25,
            'C' => 25,
            'D' => 25,
            'E' => 25,
            'F' => 25,
            'G' => 25,
            'H' => 25,
            'I' => 25,
            'J' => 25,
            'K' => 25,
        ];
    }

}
