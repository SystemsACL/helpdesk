<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->string('role', 10)->default('agent')->after('password'); // admin | agent
            $t->boolean('is_active')->default(true);
            $t->string('phone', 20)->nullable();
            $t->boolean('must_change_password')->default(false);
        });
    }
    public function down(): void
    {
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn(['role', 'is_active', 'phone', 'must_change_password']));
    }
};
