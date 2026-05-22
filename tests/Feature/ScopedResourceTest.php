<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Team;
use App\Models\Branch;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScopedResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test 1: Verify that a regular branch employee cannot view invoices from other branches.
     */
    public function test_regular_employee_can_only_see_invoices_of_their_assigned_branch()
    {
        // 1. Create the primary tenant/company
        $team = Team::factory()->create(['name' => 'Main Company']);

        // 2. Create two distinct branches within this company (A and B)
        $branchA = Branch::factory()->create(['team_id' => $team->id, 'name' => 'A Branch']);
        $branchB = Branch::factory()->create(['team_id' => $team->id, 'name' => 'B Branch']);

        // 3. Create a regular employee assigned exclusively to the A Branch (branch_id = $branchA->id)
        $employee = User::factory()->create([
            'current_team_id' => $team->id,
            'branch_id' => $branchA->id,
        ]);

        // 4. Create one invoice under the A branch and another under the B branch
        $invoiceOfA = Invoice::factory()->create(['team_id' => $team->id, 'branch_id' => $branchA->id]);
        $invoiceOfB = Invoice::factory()->create(['team_id' => $team->id, 'branch_id' => $branchB->id]);

        $this->actingAs($employee, 'sanctum');

        // 6. Retrieve available invoices from the database
        $retrievedInvoices = Invoice::all();

        // Security Assertions:
        // A) The employee must only see exactly one invoice
        $this->assertCount(1, $retrievedInvoices);

        // B) The retrieved invoice must belong strictly to the A branch
        $this->assertEquals($branchA->id, $retrievedInvoices->first()->branch_id);

        // C) Strict check: Ensure none of the retrieved data belongs to the B branch
        foreach ($retrievedInvoices as $invoice) {
            $this->assertNotEquals(
                $branchB->id,
                $invoice->branch_id,
                '🔒 Security Breach! A regular branch employee was able to view an invoice from another branch.'
            );
        }
    }

    /**
     * Test 2: Verify that a global Admin user can view invoices across all branches.
     */
    public function test_admin_user_can_see_invoices_from_all_branches()
    {
        $team = Team::factory()->create(['name' => 'Main Company']);

        $branchA = Branch::factory()->create(['team_id' => $team->id, 'name' => 'A Branch']);
        $branchB = Branch::factory()->create(['team_id' => $team->id, 'name' => 'B Branch']);

        // 1. Create a global administrator (linked to the company but branch_id is null)
        $admin = User::factory()->create([
            'current_team_id' => $team->id,
            'branch_id' => null, // Admins bypass the branch restriction scope
        ]);

        // 2. Populate invoices distributed across different branches
        Invoice::factory()->create(['team_id' => $team->id, 'branch_id' => $branchA->id]);
        Invoice::factory()->create(['team_id' => $team->id, 'branch_id' => $branchB->id]);

        // 3. Authenticate programmatically as the global Admin
        $this->actingAs($admin, 'sanctum');

        // 4. Retrieve available invoices from the database
        $retrievedInvoices = Invoice::all();

        // Administrative Assertion: The admin must see both invoices seamlessly without restriction
        $this->assertCount(2, $retrievedInvoices);
    }
}
