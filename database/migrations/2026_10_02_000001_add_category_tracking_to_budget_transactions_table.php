<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
{
    if (!Schema::hasColumn('budget_transactions', 'category')) {
        Schema::table('budget_transactions', function (Blueprint $table) {
            $table->string('category', 50)->nullable()->after('transaction_type');
            $table->string('type', 20)->nullable()->after('category');
            $table->date('transaction_date')->nullable()->after('type');
        });
    }
}

    public function down(): void
    {
        Schema::table('budget_transactions', function (Blueprint $table) {
            $table->dropColumn(['category', 'type', 'transaction_date']);
        });
    }
};