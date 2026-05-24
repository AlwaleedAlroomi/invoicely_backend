<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Team;
use App\Models\User;
use App\Models\Client;
use App\Models\InvoiceNumberSequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $team = Team::factory()->create([
            'user_id' => $user->id,
            'name' => 'My First Business',
        ]);

        $user->update(['current_team_id' => $team->id]);

        Client::factory(10)->create(['team_id' => $team->id]);
        Branch::factory()->create(['team_id' => $team->id]);
        InvoiceNumberSequence::firstOrCreate(['team_id' => $team->id], [
            'prefix' => 'INV-' . date('Y') . '-',
            'next_number' => 1,
            'digits_length' => 5,
        ]);
    }
}
