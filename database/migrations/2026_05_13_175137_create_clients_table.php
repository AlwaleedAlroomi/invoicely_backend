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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            // حقل الربط بالفريق (أساسي لعزل البيانات)
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();

            // الحقول المتوافقة مع Flutter
            $table->uuid('remote_id')->nullable()->unique(); // لربط البيانات بين Flutter و Laravel
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('currency')->default('USD');
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
