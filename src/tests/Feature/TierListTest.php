<?php

namespace Tests\Feature;

use App\Models\TierList;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TierListTest extends TestCase
{
    use RefreshDatabase;

    public function test_tier_list_recent_route_returns_6_most_recent_public_tier_lists(): void
    {
        // Prepare data for test
        TierList::factory(10)->create();
        $mostRecent = TierList::whereIsPublic()->orderByRecency()->take(6)->get();

        // Hit the route
        $response = $this->get(route('tierlist.recent'));
        $response->assertStatus(200);

        // Assert json response contains at least these fields & in this structure
        $response->assertJsonStructure([
            '*' => [
                'title',
                'description',
                'thumbnail',
                Model::CREATED_AT,
                User::FOREIGN_KEY,
                'creator' => [
                    'id',
                    'username',
                ],
            ],
        ]);
        // Convert JSON response into associative array
        $jsonResponse = json_decode($response->content());

        $mostRecent->each(function ($tierList, $index) use ($jsonResponse) {
            // Assert that the response 'created_at' fields match the expected Models.
            // I do some conversion with toArray(), so the date field is formatted in the same way as the API response.
            // Used dd function to inspect the outputs and compare.
            $this->assertEquals($tierList->toArray()[Model::CREATED_AT], $jsonResponse[$index]->created_at);
        });
    }
}
