<?php

namespace Tests\Feature\V1\Traits;

trait UserResourceJsonStructure
{
  private function userResourceJsonStructure()
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
