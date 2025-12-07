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
        Schema::create('transfer_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->string('from_mosque');
            $table->string('to_mosque');
            $table->date('transfer_date');
            $table->string('transferred_by');
            $table->text('reason');
            $table->enum('old_category', ['أ', 'ب', 'ج'])->nullable();
            $table->enum('new_category', ['أ', 'ب', 'ج'])->nullable();
            $table->unsignedBigInteger('version')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_histories');
    }
};
