<?php

namespace App\Http\Controllers;

use App\Enums\TicketStatus;
use App\Models\CompanySetting;
use App\Models\ServiceCategory;
use App\Models\Ticket;
use App\Models\User;
use App\Services\TicketService;
use App\Services\WhatsAppLinkBuilder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PanelController extends Controller
{
    public function dashboard()
    {
        $counts = Ticket::selectRaw('status, count(*) as c')->groupBy('status')->pluck('c', 'status');
        $stats = [
            'abierto' => (int) ($counts['abierto'] ?? 0),
            'en_proceso' => (int) ($counts['en_proceso'] ?? 0),
            'cerrado' => (int) ($counts['cerrado'] ?? 0),
        ];
        $stats['total'] = array_sum($stats);

        $recent = Ticket::with(['category', 'assignee'])->latest('opened_at')->limit(8)->get();

        return view('panel.dashboard', compact('stats', 'recent'));
    }

    public function index(Request $request)
    {
        $q = Ticket::with(['category', 'assignee'])->latest('opened_at');

        if ($s = trim((string) $request->q)) {
            $digits = preg_replace('/\D/', '', $s);
            $q->where(function ($w) use ($s, $digits) {
                $w->where('ticket_number', 'ilike', "%{$s}%")
                  ->orWhere('requester_name', 'ilike', "%{$s}%");
                if ($digits !== '') {
                    $w->orWhere('requester_whatsapp', 'like', "%{$digits}%");
                }
            });
        }

        if (TicketStatus::tryFrom((string) $request->status)) {
            $q->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $q->where('category_id', (int) $request->category_id);
        }

        if ($range = $this->range((string) $request->range)) {
            $q->whereBetween('opened_at', $range);
        }

        return view('panel.index', [
            'tickets' => $q->simplePaginate(15)->withQueryString(),
            'groups' => ServiceCategory::orderBy('group')->orderBy('sort_order')->get()->groupBy('group'),
            'ranges' => [
                'hoy' => 'Hoy', 'ayer' => 'Ayer', '7d' => 'Últimos 7 días', '30d' => 'Últimos 30 días',
                'mes' => 'Este mes', 'mes_ant' => 'Mes anterior',
            ],
        ]);
    }

    public function show(Ticket $ticket, WhatsAppLinkBuilder $links)
    {
        $ticket->load(['category', 'assignee', 'comments.user', 'events.user']);

        return view('panel.show', [
            'ticket' => $ticket,
            'users' => User::where('is_active', true)->orderBy('name')->get(),
            'waStatus' => $links->toRequester($ticket, 'status'),
            'waClosed' => $links->toRequester($ticket, 'closed'),
        ]);
    }

    public function status(Request $request, Ticket $ticket, TicketService $svc)
    {
        $data = $request->validate([
            'status' => ['required', Rule::enum(TicketStatus::class)],
            'resolution' => ['nullable', 'string', 'max:2000'],
        ]);
        $new = TicketStatus::from($data['status']);

        if ($new === TicketStatus::Closed && blank($data['resolution'] ?? null) && blank($ticket->resolution)) {
            return back()->withErrors(['resolution' => 'Escribe la resolución para cerrar el ticket.'])->withInput();
        }

        $svc->changeStatus($ticket, $new, $request->user(), $data['resolution'] ?? null);
        return back()->with('ok', 'Estado actualizado.');
    }

    public function assign(Request $request, Ticket $ticket, TicketService $svc)
    {
        $data = $request->validate([
            'assigned_user_id' => ['nullable', Rule::exists('users', 'id')->where('is_active', true)],
        ]);
        $assignee = $data['assigned_user_id'] ? User::find($data['assigned_user_id']) : null;
        $me = $request->user();

        // El admin asigna a quien quiera. El agente solo puede tomar un ticket
        // libre para sí mismo o soltar uno que ya es suyo.
        if (! $me->isAdmin()) {
            $takingFree = $ticket->assigned_user_id === null && $assignee?->id === $me->id;
            $releasingOwn = $ticket->assigned_user_id === $me->id && $assignee === null;
            if (! $takingFree && ! $releasingOwn) {
                return back()->withErrors(['assign' => 'Este ticket ya lo atiende otra persona.']);
            }
        }

        $svc->assign($ticket, $assignee, $me);

        $msg = $assignee === null
            ? 'Ticket liberado.'
            : ($assignee->id === $me->id ? 'Ticket asignado a ti.' : 'Asignación actualizada.');
        return back()->with('ok', $msg);
    }

    public function comment(Request $request, Ticket $ticket, TicketService $svc)
    {
        $data = $request->validate(['comment' => ['required', 'string', 'max:1000']]);
        $svc->comment($ticket, $request->user(), $data['comment']);
        return back()->with('ok', 'Comentario agregado.');
    }

    /** Rango de fechas en la zona horaria de la empresa, convertido a la zona de la BD. */
    private function range(string $key): ?array
    {
        $n = now(CompanySetting::tz());
        $r = match ($key) {
            'hoy' => [$n->copy()->startOfDay(), $n->copy()->endOfDay()],
            'ayer' => [$n->copy()->subDay()->startOfDay(), $n->copy()->subDay()->endOfDay()],
            '7d' => [$n->copy()->subDays(6)->startOfDay(), $n->copy()->endOfDay()],
            '30d' => [$n->copy()->subDays(29)->startOfDay(), $n->copy()->endOfDay()],
            'mes' => [$n->copy()->startOfMonth(), $n->copy()->endOfMonth()],
            'mes_ant' => [$n->copy()->subMonthNoOverflow()->startOfMonth(), $n->copy()->subMonthNoOverflow()->endOfMonth()],
            default => null,
        };
        if (! $r) return null;

        $db = config('app.timezone');
        return [$r[0]->setTimezone($db), $r[1]->setTimezone($db)];
    }
}
