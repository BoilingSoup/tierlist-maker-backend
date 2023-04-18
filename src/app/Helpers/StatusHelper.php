<?php

namespace App\Helpers;

/**
 * StatusHelper provides constants used as http error status messages.
 */
class StatusHelper
{
  public const UserWithEmailAlreadyExists = 'This email is already in use.';

  public const AlreadySignedIn = 'You are already signed in.';
}
