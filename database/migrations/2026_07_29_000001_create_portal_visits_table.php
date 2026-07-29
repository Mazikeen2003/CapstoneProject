<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up()
    {
        Schema::create('portal_visits', function (Blueprint $table) {
            $table->id('visit_id');
            $table->string('page')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('visited_at')->useCurrent();

            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->nullOnDelete();
        });
    }
    public function down()
    {
        Schema::dropIfExists('portal_visits');
    }
};