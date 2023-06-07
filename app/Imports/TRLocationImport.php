<?php

namespace App\Imports;

use App\Models\TRLocation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class TRLocationImport implements ToCollection
{
    private function setLocation ($name): void
    {
        $query = TRLocation::query()->where('name', $name)->value('id');
        if (!isset($query)) {
            $model            = new TRLocation();
            $model->name      = $name;
            $model->save();
        }
    }

    public function collection(Collection $collection): bool
    {
        foreach ($collection as $item) {
            foreach ($item as $k) {
                if (isset($k)) $this->setLocation($k);
            }
        }
        return true;
    }
}
