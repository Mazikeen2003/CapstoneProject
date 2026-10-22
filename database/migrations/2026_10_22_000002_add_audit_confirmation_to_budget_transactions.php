<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('budget_transactions', function (Blueprint $table) {
            $table->timestamp('audit_confirmed_at')->nullable()->after('transaction_date');
        });
    }

    public function down(): void
    {
        Schema::table('budget_transactions', function (Blueprint $table) {
            $table->dropColumn('audit_confirmed_at');
        });
    }
};
