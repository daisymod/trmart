<?php

namespace App\Actions;

use Intervention\Image\ImageManager;

class ImageSizeAction
{
    public function __invoke(): string
    {
        $params = request()->all();
        $x = (int)$params["x"];
        $y = (int)$params["y"];
        $width = (int)$params["width"];
        $height = (int)$params["height"];
        $widthX = $x + $width;
        $hightY = $y + $height;


        $fileName = public_path() . request("file");

        $manager = new ImageManager(['driver' => 'gd']);

        $img = $manager->make($fileName);
        $img = $img->rotate(-(int)$params["rotate"]);

        $canvasSize = $params["canvas"];
        $imageSize = [$img->width(), $img->height()];

        $tmpName = "/files/tmp" . random_int(111, 999) . microtime(true) . ".png";
        $fileSave = public_path() . $tmpName;


        if ($x >= 0 and $y >= 0 and $imageSize[0] >= $widthX and $imageSize[1] >= $hightY) {
            //Начнём с самого простого - исходное изображение больше чем надо
            $img = $img->crop($width, $height, $x, $y);
            $img = $manager->canvas($width, $height)
                ->insert($img, "top-left", 0, 0)
                ->resize($canvasSize[0], $canvasSize[1])
                ->save($fileSave, 100);
        } elseif ($x < 0 and $y < 0 and $imageSize[0] < $widthX and $imageSize[1] < $hightY) {
            //Не вмещается ничего - резать нечего, прсото вставить со смещением
            $img = $manager->canvas($width, $height)
                ->insert($img, "top-left", $x * -1, $y * -1)
                ->resize($canvasSize[0], $canvasSize[1])
                ->save($fileSave, 100);
        } elseif ($x >= 0 and $y >= 0 and $imageSize[0] < $widthX and $imageSize[1] >= $hightY) {
            //Не прошла правая сторона, остальные нормально
            $img = $img->crop($imageSize[0] - $x, (int)$params["height"], $x, $y);
            $img = $manager->canvas($width, $height)
                ->insert($img)
                ->resize($canvasSize[0], $canvasSize[1])
                ->save($fileSave, 100);

        } elseif ($x >= 0 and $y >= 0 and $imageSize[0] < $widthX and $imageSize[1] < $hightY) {
            //Не прошла правая сторона и нижняя
            $img = $img->crop($imageSize[0] - $x, $imageSize[1] - $y, $x, $y);
            $img = $manager->canvas($width, $height)
                ->insert($img)
                ->resize($canvasSize[0], $canvasSize[1])
                ->save($fileSave, 100);
        } elseif ($x < 0 and $y >= 0 and $imageSize[0] < $widthX and $imageSize[1] < $hightY) {
            //Не прошла правая сторона и нижняя и левая
            $img = $img->crop($imageSize[0], $imageSize[1] - $y, 0, $y);
            $img = $manager->canvas($width, $height)
                ->insert($img, "top-left", $x * -1)
                ->resize($canvasSize[0], $canvasSize[1])
                ->save($fileSave, 100);
        } elseif ($x < 0 and $y >= 0 and $imageSize[0] >= $widthX and $imageSize[1] < $hightY) {
            //Не прошла левая сторона и нижняя
            $img = $img->crop($imageSize[0] - $x, $imageSize[1] - $y, 0, $y);
            $img = $manager->canvas($width, $height)
                ->insert($img, "top-left", $x * -1)
                ->resize($canvasSize[0], $canvasSize[1])
                ->save($fileSave, 100);
        } elseif ($x < 0 and $y >= 0 and $imageSize[0] >= $widthX and $imageSize[1] >= $hightY) {
            //Не прошла левая сторона
            $img = $img->crop($imageSize[0], $imageSize[1], 0, $y);
            $img = $manager->canvas($width, $height)
                ->insert($img, "top-left", $x * -1)
                ->resize($canvasSize[0], $canvasSize[1])
                ->save($fileSave, 100);
        } elseif ($x < 0 and $y < 0 and $imageSize[0] >= $widthX and $imageSize[1] >= $hightY) {
            //Не прошла левая сторона и верхняя
            $img = $img->crop($imageSize[0] - $x, $imageSize[1] - $y, 0, 0);
            $img = $manager->canvas($width, $height)
                ->insert($img, "top-left", $x * -1, $y * -1)
                ->resize($canvasSize[0], $canvasSize[1])
                ->save($fileSave, 100);
        } elseif ($x < 0 and $y < 0 and $imageSize[0] < $widthX and $imageSize[1] >= $hightY) {
            //Не прошла левая сторона и верхняя и правая
            $img = $img->crop($imageSize[0], $imageSize[1] - $y, 0, 0);
            $img = $manager->canvas($width, $height)
                ->insert($img, "top-left", $x * -1, $y * -1)
                ->resize($canvasSize[0], $canvasSize[1])
                ->save($fileSave, 100);
        } elseif ($x >= 0 and $y < 0 and $imageSize[0] < $widthX and $imageSize[1] >= $hightY) {
            //Не прошла верхняя и правая
            $img = $img->crop($imageSize[0] - $x, $imageSize[1] - $y, $x, 0);
            $img = $manager->canvas($width, $height)
                ->insert($img, "top-left", 0, $y * -1)
                ->resize($canvasSize[0], $canvasSize[1])
                ->save($fileSave, 100);
        } elseif ($x < 0 and $y >= 0 and $imageSize[0] < $widthX and $imageSize[1] >= $hightY) {
            //Не вмещается левая и правая
            $img = $img->crop($imageSize[0], $height, 0, $y);
            $img = $manager->canvas($width, $height)
                ->insert($img, "top-left", $x * -1, 0)
                ->resize($canvasSize[0], $canvasSize[1])
                ->save($fileSave, 100);
        } elseif ($x >= 0 and $y < 0 and $imageSize[0] >= $widthX and $imageSize[1] < $hightY) {
            //Не вмещается верх и низ
            $img = $img->crop($width, $imageSize[1], $x, 0);
            $img = $manager->canvas($width, $height)
                ->insert($img, "top-left", 0, $y * -1)
                ->resize($canvasSize[0], $canvasSize[1])
                ->save($fileSave, 100);
        } elseif ($x >= 0 and $y >= 0 and $imageSize[0] >= $widthX and $imageSize[1] < $hightY) {
            //Не вмещается низ
            $img = $img->crop($width, $imageSize[1], $x, 0);
            $img = $manager->canvas($width, $height)
                ->insert($img, "top-left", 0, $y * -1)
                ->resize($canvasSize[0], $canvasSize[1])
                ->save($fileSave, 100);
        } elseif ($x >= 0 and $y < 0 and $imageSize[0] < $widthX and $imageSize[1] < $hightY) {
            //Не вмещается верх право низ
            $img = $img->crop($imageSize[0] - $x, $imageSize[1], $x, 0);
            $img = $manager->canvas($width, $height)
                ->insert($img, "top-left", 0, $y * -1)
                ->resize($canvasSize[0], $canvasSize[1])
                ->save($fileSave, 100);
        } elseif ($x < 0 and $y < 0 and $imageSize[0] >= $widthX and $imageSize[1] < $hightY) {
            //Не вмещается верх лево низ
            $img = $img->crop($imageSize[0] - $x, $imageSize[1], 0, 0);
            $img = $manager->canvas($width, $height)
                ->insert($img, "top-left", $x * -1, $y * -1)
                ->resize($canvasSize[0], $canvasSize[1])
                ->save($fileSave, 100);
        } elseif ($x >= 0 and $y < 0 and $imageSize[0] >= $widthX and $imageSize[1] >= $hightY) {
            //Не вмещается верх
            $img = $img->crop($imageSize[0] - $x, $imageSize[1], $x, 0);
            $img = $manager->canvas($width, $height)
                ->insert($img, "top-left", $x * -1, $y * -1)
                ->resize($canvasSize[0], $canvasSize[1])
                ->save($fileSave, 100);
        }
        $info = [
            "file" => request("file"),
            "name" => request("name"),
        ];

        $info["img"] = $this->saveFile($tmpName);

        $img->resize("300", null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save(public_path() . $tmpName, 100);
        $info["small"] = $this->saveFile($tmpName);

        $field = request("field");
        return view("fields.image_box_load", compact("info", "field"))->toHtml();
    }

    protected function saveFile($path)
    {
        $md5Img = md5_file(public_path() . $path);
        copy(public_path() . $path, public_path() . "/files/{$md5Img}.png");
        unlink(public_path() . $path);
        return "/files/{$md5Img}.png";
    }
}
