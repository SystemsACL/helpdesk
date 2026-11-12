<?php
namespace App\Services;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TicketService
{
    public function __construct(private TicketNumberGenerator $numbers) {}

    /** Desde el formulario público. $data: requester_name, requester_whatsapp, category_id, description */
    public function create(array $data): Ticket
    {
        return DB::transaction(function () use ($data) {
            $ticket = Ticket::create($data + [
                'ticket_number' => $this->numbers->next(),
                'status' => TicketStatus::Open,
                'opened_at' => now(),
            ]);
            $this->event($ticket, 'created', null, null, $ticket->status->value, 'Ticket creado');
            return $ticket;
        });
    }

    public function changeStatus(Ticket $ticket, TicketStatus $new, User $by, ?string $resolution = null): Ticket
    {
        return DB::transaction(function () use ($ticket, $new, $by, $resolution) {
            $old = $ticket->status;
            if ($old === $new) return $ticket;

            $ticket->status = $new;
            if ($new === TicketStatus::Closed) {
                $ticket->closed_at = now();
                $ticket->resolution = $resolution ?? $ticket->resolution;
            } else {
                $ticket->closed_at = null; // reabierto
            }
            $ticket->save();

            $this->event($ticket, $new === TicketStatus::Closed ? 'closed' : 'status_changed',
                $by, $old->value, $new->value);
            return $ticket;
        });
    }

    public function assign(Ticket $ticket, ?User $assignee, User $by): Ticket
    {
        return DB::transaction(function () use ($ticket, $assignee, $by) {
            $old = $ticket->assignee?->name;
            $ticket->update(['assigned_user_id' => $assignee?->id]);
            $this->event($ticket, 'assigned', $by, $old, $assignee?->name);
            return $ticket;
        });
    }

    public function comment(Ticket $ticket, User $by, string $text): TicketComment
    {
        return DB::transaction(function () use ($ticket, $by, $text) {
            $c = $ticket->comments()->create(['user_id' => $by->id, 'comment' => $text]);
            $this->event($ticket, 'commented', $by, null, null, str($text)->limit(80));
            $ticket->touch();
            return $c;
        });
    }

    private function event(Ticket $t, string $type, ?User $by, ?string $old, ?string $new, ?string $desc = null): void
    {
        $t->events()->create([
            'user_id' => $by?->id, 'event_type' => $type,
            'old_value' => $old, 'new_value' => $new, 'description' => $desc,
        ]);
    }
}
