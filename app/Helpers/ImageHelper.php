<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Spatie\Image\Image;
use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    public static function optimizeAndStore($file, $folder)
    {
        $safeName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $uniqueName = $safeName . '_' . uniqid();

        // Pathsc
        $original = "$folder/$uniqueName.webp";

        // Temporary path for processing
        $tempPath = $file->getRealPath();

        // Convert + compress
        Image::load($tempPath)
            ->format('webp')
            ->width(400)
            ->quality(80)
            ->optimize()
            ->save(storage_path("app/public/$original"));

        return "/storage/$original";
    }
}
