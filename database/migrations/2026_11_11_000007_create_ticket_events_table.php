<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ticket_events', function (Blueprint $t) {
            $t->id();
            $t->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // null = solicitante/sistema
            $t->string('event_type', 30);  // created | status_changed | assigned | commented | closed
            $t->string('old_value')->nullable();
            $t->string('new_value')->nullable();
            $t->string('description')->nullable();
            $t->timestamp('created_at')->useCurrent();
            $t->index(['ticket_id', 'created_at']);
        });
    }
    public function down(): void { Schema::dropIfExists('ticket_events'); }
};
