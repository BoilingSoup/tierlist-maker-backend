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

    private function getOAuthProvider(): string|null
    {
        switch (true) {
            case ! is_null($this->github_id):
                return 'GITHUB';
            case ! is_null($this->gitlab_id):
                return 'GITLAB';
            case ! is_null($this->google_id):
                return 'GOOGLE';
            case ! is_null($this->reddit_id):
                return 'REDDIT';
            case ! is_null($this->discord_id):
                return 'DISCORD';
            default:
                return null;
        }
    }
}
