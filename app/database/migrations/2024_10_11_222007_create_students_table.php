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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('admission_setting_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('photo');
            $table->string('birth_place');
            $table->date('birth_date');
            $table->text('address');
            $table->string('phone_number', 20);
            $table->enum('gender', ['L', 'P']);
            $table->foreignId('program_id')->constrained();
            $table->jsonb('details')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
