<?php

namespace Tests\Feature\V1\Traits;

use App\Helpers\StatusHelper;

trait UserWithEmailAlreadyExistsJsonStructure
{
  private function userWithEmailAlreadyExistsJsonStructure()
  {
    return [
        'message' => StatusHelper::UserWithEmailAlreadyExists,
    ];
  }
}
