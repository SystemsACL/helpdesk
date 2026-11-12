<?php
namespace App\Enums;

enum TicketStatus: string
{
    case Open = 'abierto';
    case InProgress = 'en_proceso';
    case Closed = 'cerrado';

    public function label(): string
    {
        return match ($this) {
            self::Open => 'Abierto',
            self::InProgress => 'En proceso',
            self::Closed => 'Cerrado',
        };
    }
}
