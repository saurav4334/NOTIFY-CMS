<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();           // super_admin, content_manager, support_admin
            $table->string('description')->nullable();
            $table->json('permissions')->nullable();     // array of permission keys; null = all (super admin)
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('email')->constrained('roles')->nullOnDelete();
            $table->string('phone')->nullable()->after('role_id');
            $table->string('avatar')->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('avatar');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
            $table->dropColumn(['phone', 'avatar', 'is_active', 'last_login_at']);
        });
        Schema::dropIfExists('roles');
    }
};
