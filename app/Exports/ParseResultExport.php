<?php

namespace App\Exports;


use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;


class ParseResultExport implements FromArray,WithColumnWidths,ShouldQueue
{

    public function __construct(public array $data)
    {
    }

    public function array(): array
    {
      
        return $this->data;
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
