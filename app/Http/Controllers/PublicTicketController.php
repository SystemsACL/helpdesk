<?php

namespace App\Http\Controllers;

use App\Models\ServiceCategory;
use App\Models\Ticket;
use App\Services\TicketService;
use App\Services\WhatsAppLinkBuilder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PublicTicketController extends Controller
{
    public function create()
    {
        $groups = ServiceCategory::active()->get()->groupBy('group');
        return view('public.form', compact('groups'));
    }

    public function store(Request $request, TicketService $tickets, WhatsAppLinkBuilder $wa)
    {
        // Honeypot: un humano nunca llena este campo.
        if ($request->filled('hp_field')) {
            return redirect()->route('solicitud.create');
        }

        $data = $request->validate([
            'requester_name' => ['required', 'string', 'max:120'],
            'requester_whatsapp' => ['required', 'string', 'max:25', function ($attr, $value, $fail) use ($wa) {
                $len = strlen($wa->digits($value));
                if ($len < 10 || $len > 15) {
                    $fail('Escribe un número de WhatsApp válido (10 dígitos).');
                }
            }],
            'department' => ['required', 'string', 'max:80'],
            'category_id' => ['required', Rule::exists('service_categories', 'id')->where('is_active', true)],
            'description' => ['required', 'string', 'min:10', 'max:1000'],
        ], [
            'requester_name.required' => 'Escribe tu nombre.',
            'requester_whatsapp.required' => 'Escribe tu número de WhatsApp.',
            'department.required' => 'Escribe tu departamento.',
            'department.max' => 'Máximo 80 caracteres.',
            'category_id.required' => 'Elige el tipo de servicio.',
            'category_id.exists' => 'Elige un tipo de servicio válido.',
            'description.required' => 'Describe el problema.',
            'description.min' => 'Describe el problema con un poco más de detalle.',
            'description.max' => 'Máximo 1,000 caracteres.',
        ]);

        $data['requester_whatsapp'] = $wa->digits($data['requester_whatsapp']);

        $ticket = $tickets->create($data);

        // El folio es secuencial: no se muestra por URL, solo vía sesión.
        session(['ticket_id' => $ticket->id]);
        return redirect()->route('gracias');
    }

    public function thanks(WhatsAppLinkBuilder $links)
    {
        $ticket = Ticket::with('category')->find(session('ticket_id'));
        if (! $ticket) {
            return redirect()->route('solicitud.create');
        }
        return view('public.thanks', [
            'ticket' => $ticket,
            'waLink' => $links->toCompany($ticket),
        ]);
    }
}
