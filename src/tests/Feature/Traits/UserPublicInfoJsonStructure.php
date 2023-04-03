<?php

namespace Tests\Feature\Traits;

trait UserPublicInfoJsonStructure
{
    private function userPublicInfoJsonStructure()
    {
        return [
            'data' => [
                'id',
                'username',
                'is_admin',
            ],
        ];
    }
}
