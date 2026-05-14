<?php

use Intervention\Image\Facades\Image;

if (!function_exists('uploadImageOptimized')) {

    function uploadImageOptimized($file, $folder = 'general', $width = 1200)
    {
        $fileName = time() . '_' . uniqid() . '.webp';
        $destinationPath = public_path('uploads/' . $folder);

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $img = Image::make($file);

        $img->resize($width, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $img->encode('webp', 80)
            ->save($destinationPath . '/' . $fileName);

        return 'uploads/' . $folder . '/' . $fileName;
    }
}