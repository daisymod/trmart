<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class BreadcrumbService
{
    public static function get(Model $record)
    {
        $path = [];
        $path[] = $record;
        if (!empty($record->parent)) {
            $path = array_merge(static::get($record->parent), $path);
        }
        return $path;
    }
}
