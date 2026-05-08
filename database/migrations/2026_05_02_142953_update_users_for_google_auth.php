<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'password',
                'remember_token',
            ]);

            $table->string('google_id')->unique()->after('email');
            $table->string('avatar')->nullable()->after('google_id');

            $table->timestamp('email_verified_at')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->string('password');
            $table->rememberToken();

            $table->dropColumn([
                'google_id',
                'avatar',
            ]);
        });
    }
};
