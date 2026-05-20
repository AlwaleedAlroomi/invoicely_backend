<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('discount_code_id')->nullable()->constrained('discount_codes')->nullOnDelete();
            $table->uuid('remote_id')->nullable()->unique();

            $table->string('invoice_number')->unique();
            $table->date('invoice_date');
            $table->date('due_date')->nullable();

            $table->decimal('sub_total', 15, 4)->default(0.0000);
            $table->decimal('tax_total', 15, 4)->default(0.0000);
            $table->decimal('discount_total', 15, 4)->default(0.0000);
            $table->decimal('grand_total', 15, 4)->default(0.0000);
            $table->decimal('paid_total', 15, 4)->default(0.0000);

            $table->string('status')->default('draft'); // draft, sent, paid, partially_paid, overdue
            $table->string('currency', 3)->default('USD');
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
