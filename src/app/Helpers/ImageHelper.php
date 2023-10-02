<?php

namespace App\Helpers;

use Nette\Utils\Arrays;

/**
 * ImageHelper provides constant values related to images.
 */
class ImageHelper
{
    const THUMBNAIL_WIDTH = 600;

    const THUMBNAIL_HEIGHT = 420;

    public static function UrlToPublicID(string $url): string
    {
      $idWithExtension = Arrays::last(explode('/', $url));

      return Arrays::first(explode('.', $idWithExtension));
    }
}
