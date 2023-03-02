<?php

namespace Database\Helpers;

class ImageItemProvider extends \Faker\Provider\Base
{
    public function tierListImages(int $nbImages = 2)
    {
        $images = [];

        for ($i = 0; $i < $nbImages; $i++) {
            $image = [
                'src' => $this->generator->imageUrl(),
                'text' => $this->optional()->sentence(),
                'alt' => $this->optional()->sentence(),
            ];
            $images[] = $image;
        }

        return $images;
    }
}
