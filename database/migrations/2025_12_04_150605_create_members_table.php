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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('mosque_id')->constrained('mosques')->onDelete('cascade');
            $table->foreignId('housing_id')->nullable()->constrained('housings')->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->foreignId('profession_id')->nullable()->constrained('professions')->onDelete('set null');
            $table->string('employee_number')->unique();
            $table->string('phone');
            $table->string('national_id')->unique();
            $table->string('appointment_decision');
            $table->date('appointment_date');
            $table->enum('status', ['نشط', 'غير نشط'])->default('نشط');
            $table->unsignedBigInteger('version')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
