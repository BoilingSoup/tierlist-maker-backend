<?php

namespace Database\Helpers;

class JsonDataProvider extends \Faker\Provider\Base
{
    public function tierListTiers($nbObjects = 2)
    {
        $objects = [];

        for ($i = 0; $i < $nbObjects; $i++) {
            $object = [
                'tier' => $this->generator->word(),
                'items' => $this->generator->tierListImages(),
            ];
            $objects[] = $object;
        }

        return $objects;
    }
}
