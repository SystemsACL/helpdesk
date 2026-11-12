<?php
namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $catalog = [
            'HARDWARE' => ['PC / Computadora', 'Laptop', 'Monitor', 'Impresora', 'Teclado y mouse', 'Componentes', 'Disco duro / SSD', 'Memoria RAM'],
            'INFRAESTRUCTURA' => ['Red / Internet', 'Wi-Fi', 'Telefonía', 'Cámaras', 'Cableado', 'Servidores'],
            'SOFTWARE' => ['Windows', 'Microsoft Office', 'SAP', 'Correo electrónico', 'Sistemas y aplicaciones'],
            'OTROS' => ['Otras solicitudes'],
        ];
        foreach ($catalog as $group => $names) {
            foreach ($names as $i => $name) {
                ServiceCategory::firstOrCreate(['group' => $group, 'name' => $name], ['sort_order' => $i]);
            }
        }
    }
}
