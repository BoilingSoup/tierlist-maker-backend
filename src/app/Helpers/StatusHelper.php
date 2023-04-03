<?php

namespace App\Helpers;

/**
 * StatusHelper provides constants used as http error status messages.
 */
class StatusHelper
{
    public const UserWithEmailAlreadyExists = 'A user with this email already exists.';

    public const AlreadySignedIn = 'You are already signed in.';
}
