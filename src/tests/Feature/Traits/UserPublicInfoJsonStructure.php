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
                'email',
                'is_admin',
                'email_verified',
                'oauth_provider',
            ],
        ];
    }
}
