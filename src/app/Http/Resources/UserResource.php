<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
        'id' => $this->id,
        'username' => $this->username,
        'email' => $this->getEmailValue(),
        'is_admin' => (bool) $this->is_admin,
        'email_verified' => ! is_null($this->email_verified_at),
        'oauth_provider' => $this->getOAuthProvider(),
    ];
  }

  private function getEmailValue(): string|null
  {
    $isOAuthUser = ! is_null($this->getOAuthProvider());
    if ($isOAuthUser) {
      return null;
    }

    return $this->email;
  }
}
