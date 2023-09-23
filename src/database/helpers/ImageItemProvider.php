<?php

namespace Database\Helpers;

use App\Helpers\ImageHelper;

class ImageItemProvider extends \Faker\Provider\Base
{
    public function tierListImages()
    {
        $n = rand(0, 4);
        $images = [];

        for ($i = 0; $i < $n; $i++) {
            $image = [
                'id' => $this->generator->uuid(),
                'src' => $this->generator->imageUrl(ImageHelper::THUMBNAIL_WIDTH, ImageHelper::THUMBNAIL_HEIGHT),
            ];
            $images[] = $image;
        }

        return $images;
    }
}
