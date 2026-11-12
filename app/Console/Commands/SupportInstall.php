<?php
namespace App\Console\Commands;

use App\Enums\UserRole;
use App\Models\CompanySetting;
use App\Models\User;
use Illuminate\Console\Command;

class SupportInstall extends Command
{
    protected $signature = 'support:install';
    protected $description = 'Configura la empresa y crea el administrador único';

    public function handle(): int
    {
        if (User::where('role', UserRole::Admin->value)->exists()) {
            $this->error('Ya existe un administrador. Instalación cancelada.');
            return self::FAILURE;
        }

        $company = $this->ask('Nombre de la empresa');
        $wa = $this->ask('WhatsApp de la empresa (10 dígitos o con código de país)');
        $name = $this->ask('Nombre del administrador');
        $email = $this->ask('Correo de acceso');
        $pass = $this->secret('Contraseña (mínimo 10 caracteres)');

        if (strlen((string) $pass) < 10) {
            $this->error('Contraseña demasiado corta.');
            return self::FAILURE;
        }

        CompanySetting::updateOrCreate(['id' => 1], ['company_name' => $company, 'whatsapp_number' => $wa]);
        User::create([
            'name' => $name, 'email' => $email, 'password' => $pass,
            'role' => UserRole::Admin, 'is_active' => true,
        ]);

        $this->info('Listo. Ahora crea al agente desde el panel (Usuarios).');
        return self::SUCCESS;
    }
}
