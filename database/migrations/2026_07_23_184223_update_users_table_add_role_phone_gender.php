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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('phone');
            $table->enum('role', ['user', 'admin', 'super_admin'])->default('user')->after('gender');
            $table->enum('status', ['active', 'suspended', 'banned'])->default('active')->after('role');
            $table->string('avatar')->nullable()->after('status');
            $table->date('date_of_birth')->nullable()->after('avatar');
            $table->string('region')->nullable()->after('date_of_birth');
            $table->timestamp('last_active_at')->nullable()->after('region');
            $table->index(['role', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role', 'status']);
            $table->dropColumn([
                'phone', 'gender', 'role', 'status', 'avatar',
                'date_of_birth', 'region', 'last_active_at',
            ]);
        });
    }
};
