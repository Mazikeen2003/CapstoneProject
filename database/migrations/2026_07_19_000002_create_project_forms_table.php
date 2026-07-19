<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_forms', function (Blueprint $table) {
            $table->id('form_id');
            $table->foreignId('project_id')->constrained('projects', 'project_id')->onDelete('cascade');
            $table->string('form_type');
            $table->string('form_title')->nullable();
            $table->json('form_data');
            $table->foreignId('created_by')->nullable()->constrained('users', 'user_id')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users', 'user_id')->nullOnDelete();
            $table->timestamps();

            $table->index(['project_id', 'form_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_forms');
    }
};