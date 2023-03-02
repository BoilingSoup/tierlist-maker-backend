<?php

namespace Database\Helpers;

class JsonDataProvider extends \Faker\Provider\Base
{
    public function tierListTiers(int $nbObjects = 2)
    {
        $objects = [];

        for ($i = 0; $i < $nbObjects; $i++) {
            $object = [
                'tier' => [
                    'label' => $this->generator->word(),
                    'color' => $this->generator->hexColor(),
                ],
                'items' => $this->generator->tierListImages(),
            ];
            $objects[] = $object;
        }

        return $objects;
    }
}
