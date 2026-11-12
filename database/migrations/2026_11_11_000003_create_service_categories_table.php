<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_categories', function (Blueprint $t) {
            $t->id();
            $t->string('group', 40);          // HARDWARE, INFRAESTRUCTURA, SOFTWARE, OTROS
            $t->string('name', 80);
            $t->string('description')->nullable();
            $t->boolean('is_active')->default(true);
            $t->unsignedSmallInteger('sort_order')->default(0);
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('service_categories'); }
};
