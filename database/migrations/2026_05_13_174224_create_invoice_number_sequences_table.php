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
        Schema::create('invoice_number_sequences', function (Blueprint $table) {
            $table->id();
            // ربط التسلسل بالفريق لضمان العزل
            $table->foreignId('team_id')->unique()->constrained()->cascadeOnDelete();

            // البادئة (مثل: INV- أو 2026-)
            $table->string('prefix')->nullable();

            // الرقم التالي في التسلسل (يبدأ عادة من 1)
            $table->integer('next_number')->default(1);

            // طول الرقم (مثلاً 5 ليظهر كـ 00001)
            $table->integer('digits_length')->default(5);

            // استراتيجية إعادة الضبط (سنوي، شهري، أو أبداً)
            $table->string('reset_strategy')->default('never'); // yearly, monthly, never

            // تاريخ آخر عملية توليد (مهم لعملية الـ Reset)
            $table->date('last_reset_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_number_sequences');
    }
};
