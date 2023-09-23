<?php

namespace Database\Helpers;

class JsonDataProvider extends \Faker\Provider\Base
{
    public function tierListData()
    {
        $rowsN = rand(1, 6);
        $rows = [];

        for ($i = 0; $i < $rowsN; $i++) {
            array_push($rows, [
                'id' => $this->generator->uuid(),
                'label' => $this->generator->word(),
                'color' => $this->generator->hexColor(),
                'items' => $this->generator->tierListImages(),
            ]);
        }

        return [
            'rows' => $rows,
            'sidebar' => $this->generator->tierListImages(),
        ];
    }
}
