<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

/** Folio TK-2026-000001. Llamar SIEMPRE dentro de DB::transaction. */
class TicketNumberGenerator
{
    public function next(): string
    {
        $year = (int) now()->format('Y');

        DB::table('ticket_sequences')->insertOrIgnore(['year' => $year, 'last_number' => 0]);

        $row = DB::table('ticket_sequences')->where('year', $year)->lockForUpdate()->first();
        $n = $row->last_number + 1;
        DB::table('ticket_sequences')->where('year', $year)->update(['last_number' => $n]);

        return sprintf('TK-%d-%06d', $year, $n);
    }
}
