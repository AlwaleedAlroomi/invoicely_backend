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
        Schema::create('discount_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();
            $table->uuid('remote_id')->nullable()->unique();
            $table->string('code');
            $table->string('type'); // percentage or fixed
            $table->decimal('value', 15, 4);

            // Coupon Terms
            $table->decimal('min_invoice_amount', 15, 4)->default(0.0000);
            $table->integer('max_uses')->nullable();
            $table->integer('uses_count')->default(0);


            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['team_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_codes');
    }
};
