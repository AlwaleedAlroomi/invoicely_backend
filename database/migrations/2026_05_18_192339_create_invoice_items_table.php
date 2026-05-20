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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->foreignId('item_id')->nullable()->constrained('items')->nullOnDelete();
            $table->uuid('remote_id')->nullable()->unique();
            $table->decimal('quantity', 12, 4)->default(1.0000);
            $table->decimal('unit_price', 15, 4)->default(0.0000);
            $table->decimal('tax_rate', 5, 2)->default(0.00);
            $table->decimal('tax_total', 15, 4)->default(0.0000);
            $table->decimal('discount_total', 15, 4)->default(0.0000);
            $table->decimal('sub_total', 15, 4)->default(0.0000);
            $table->$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
