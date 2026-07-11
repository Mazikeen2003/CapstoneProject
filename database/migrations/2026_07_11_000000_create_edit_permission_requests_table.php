<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEditPermissionRequestsTable extends Migration
{
    public function up(): void
    {
        Schema::create('edit_permission_requests', function (Blueprint $table) {
            $table->id('request_id');
            $table->foreignId('project_id')->constrained('projects', 'project_id')->cascadeOnDelete();
            $table->foreignId('requested_by')->constrained('users', 'user_id')->cascadeOnDelete();
            $table->json('fields_requested'); // e.g. ["start_date","target_end_date","approved_budget","actual_budget"]
            $table->text('reason')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->foreignId('reviewed_by')->nullable()->constrained('users', 'user_id')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->timestamp('used_at')->nullable(); // set when the approved fields are actually saved
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('edit_permission_requests');
    }
}