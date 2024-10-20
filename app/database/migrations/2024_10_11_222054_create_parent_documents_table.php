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
        Schema::create('parent_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_parent_id')->constrained()->onDelete('cascade');
            $table->foreignId('document_requirement_id')->constrained();
            $table->string('file_path');
            $table->boolean('verified')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parent_documents');
    }
};
