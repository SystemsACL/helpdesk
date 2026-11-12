<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('company_settings', function (Blueprint $t) {
            $t->id();
            $t->string('company_name');
            $t->string('logo_path')->nullable();
            $t->string('primary_color', 7)->default('#38BDF8');   // azul celeste
            $t->string('secondary_color', 7)->default('#2563EB'); // azul medio
            $t->string('accent_color', 7)->default('#0EA5E9');
            $t->string('whatsapp_number', 20)->nullable();
            $t->string('timezone', 50)->default('America/Cancun');
            // Plantillas de mensaje WhatsApp (variables: {nombre} {folio} {categoria} {estado} {descripcion})
            $t->text('msg_new')->nullable();
            $t->text('msg_status')->nullable();
            $t->text('msg_closed')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('company_settings'); }
};
