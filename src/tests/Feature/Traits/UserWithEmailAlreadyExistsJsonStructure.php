<?php

namespace Tests\Feature\Traits;

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
