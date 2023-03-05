<?php

namespace Database\Helpers;

use App\Helpers\Images\ImageHelper;

class ImageItemProvider extends \Faker\Provider\Base
{
    public function tierListImages(int $nbImages = 2)
    {
        $images = [];

        for ($i = 0; $i < $nbImages; $i++) {
            $image = [
                'src' => $this->generator->imageUrl(ImageHelper::THUMBNAIL_WIDTH, ImageHelper::THUMBNAIL_HEIGHT),
                'text' => $this->optional()->sentence(),
                'alt' => $this->optional()->sentence(),
            ];
            $images[] = $image;
        }

        return $images;
    }
}
