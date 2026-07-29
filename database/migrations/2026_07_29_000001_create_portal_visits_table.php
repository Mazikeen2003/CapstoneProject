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
        Schema::create('portal_visits', function (Blueprint $table) {
            $table->bigIncrements('visit_id');
            $table->string('page_type');
            $table->string('ip_address')->nullable();
            $table->timestamp('visited_at');

            $table->index(['page_type', 'visited_at']);
            $table->index('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portal_visits');
    }
};
