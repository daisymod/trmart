<?php

namespace App\Traits;

trait ModelDNDTrait
{
    public static function dragAndDrop($data)
    {
        $ids = [];
        if (!empty($data)) {
            foreach ($data as $k => $i) {
                $ids[] = $i[0];
            }
            $positions = static::query()->whereIn("id", $ids)->pluck("position", "id")->toArray();
            foreach ($data as $k => $i) {
                $record = static::findOrFail((int)$i[0]);
                $record->position = $positions[(int)$i[1]];
                $record->save();
            }
        }
    }
}
