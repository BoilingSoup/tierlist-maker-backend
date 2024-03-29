<?php

namespace Tests\Feature\V1;

use App\Models\TierList;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TierListTest extends TestCase
{
  use RefreshDatabase;

  public function test_tier_list_recent_route_returns_4_most_recent_public_tier_lists(): void
  {
    // Prepare data for test
    TierList::factory(10)->create();
    $mostRecent = TierList::whereIsPublic()->orderByRecency()->take(4)->get();

    // Hit the route
    $response = $this->get(route('v1.tierlist.recent'));
    $response->assertStatus(200);

    // Assert json response contains at least these fields & in this structure
    $response->assertJsonStructure([
        '*' => [
            'id',
            'title',
            'description',
            'thumbnail',
            Model::UPDATED_AT,
            'creator' => [
                'id',
                'username',
            ],
        ],
    ]);
    // Convert JSON response into associative array
    $jsonResponse = json_decode($response->content());

    $mostRecent->each(function ($tierList, $index) use ($jsonResponse) {
      // Assert that the response IDs match the expected IDs & in the same order
      $this->assertEquals($tierList->id, $jsonResponse[$index]->id);
    });
  }
}
