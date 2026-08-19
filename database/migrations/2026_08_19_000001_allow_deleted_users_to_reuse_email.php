<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_email_unique');
        });

        $driver = DB::connection()->getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('active_user_email')->nullable()
                    ->storedAs('IF(deleted_at IS NULL, user_email, NULL)');
                $table->unique('active_user_email', 'users_active_user_email_unique');
            });
        } else {
            DB::statement('CREATE UNIQUE INDEX users_active_user_email_unique ON users (user_email) WHERE deleted_at IS NULL');
        }
    }

    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique('users_active_user_email_unique');
                $table->dropColumn('active_user_email');
            });
        } else {
            DB::statement('DROP INDEX users_active_user_email_unique');
        }

        Schema::table('users', function (Blueprint $table) {
            $table->unique('user_email');
        });
    }
};