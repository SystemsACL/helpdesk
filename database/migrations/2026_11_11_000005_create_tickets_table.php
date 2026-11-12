<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $t) {
            $t->id();
            $t->string('ticket_number', 20)->unique();
            $t->string('requester_name', 120);
            $t->string('requester_whatsapp', 20);
            $t->foreignId('category_id')->constrained('service_categories');
            $t->string('description', 1000);
            $t->string('status', 12)->default('abierto')->index();
            $t->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamp('opened_at')->useCurrent();
            $t->timestamp('closed_at')->nullable();
            $t->text('resolution')->nullable();
            $t->timestamps();
            $t->index('opened_at');
        });
    }
    public function down(): void { Schema::dropIfExists('tickets'); }
};
