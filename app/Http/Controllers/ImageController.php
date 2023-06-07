<?php

namespace App\Http\Controllers;

use App\Actions\ImageLoadAction;
use App\Actions\ImageSizeAction;
use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManagerStatic;
use Intervention\Image\ImageManager;

class ImageController
{
    public function load(ImageLoadAction $action)
    {
        return $action();
    }

    public function size(ImageSizeAction $action)
    {
        return $action();
    }


}

