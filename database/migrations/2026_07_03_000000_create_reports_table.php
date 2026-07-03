<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_type');
            $table->string('title');
            $table->integer('generated_by_user_id')->nullable();
            $table->string('generated_by_username')->nullable();
            $table->string('status')->default('pending');
            $table->string('pdf_path')->nullable();
            $table->json('snapshot')->nullable();
            $table->timestamps();

            $table->foreign('generated_by_user_id')
                ->references('user_id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reports');
    }
};
