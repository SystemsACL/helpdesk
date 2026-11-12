<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    protected $guarded = [];

    /** Una sola empresa: siempre el primer registro. */
    public static function current(): self
    {
        return static::query()->first() ?? new static(['company_name' => config('app.name')]);
    }

    /** Zona horaria de la empresa (para mostrar y filtrar fechas). */
    public static function tz(): string
    {
        return static::current()->timezone ?: 'America/Cancun';
    }
}
