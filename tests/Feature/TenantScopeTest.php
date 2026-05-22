<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Branch;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantScopeTest extends TestCase
{
    use RefreshDatabase;


    public function test_users_can_only_see_branches_belonging_to_their_own_team()
    {
        $teamA = Team::factory()->create(['name' => 'Company A']);
        $teamB = Team::factory()->create(['name' => 'Company B']);

        $user = User::factory()->create(['current_team_id' => $teamA]);

        $branchOfTeamA = Branch::factory()->create(['team_id' => $teamA->id, 'name' => 'Branch A']);
        $branchOfTeamB = Branch::factory(2)->create(['team_id' => $teamB->id, 'name' => 'Branch B']);

        $this->actingAs($user, 'sanctum');

        $retrievedBranches = Branch::all();

        $this->assertCount(1, $retrievedBranches);

        $this->assertEquals('Branch A', $retrievedBranches->first()->name);

        foreach ($retrievedBranches as $branch) {
            $this->assertNotEquals($teamB->id, $branch->team_id, '🔒 Security Breach! A user from Team A was able to retrieve and view data belonging to Team B.');
        }
    }
}
