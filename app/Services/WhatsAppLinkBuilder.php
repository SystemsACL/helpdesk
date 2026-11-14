<?php
namespace App\Services;

use App\Models\CompanySetting;
use App\Models\Ticket;

/** WhatsApp sin API: solo enlaces wa.me con el mensaje prellenado. */
class WhatsAppLinkBuilder
{
    private const DEFAULTS = [
        'new' => "Hola, soy {nombre}.\nRegistré una solicitud de soporte.\nFolio: {folio}\nDepto: {depto}\nServicio: {categoria}\nProblema: {descripcion}\nAbrir ticket: {enlace}",
        'status' => "Hola {nombre}, tu solicitud {folio} cambió de estado: {estado}.",
        'closed' => "Hola {nombre}, tu solicitud {folio} fue cerrada. Gracias por tu paciencia.",
    ];

    /** Solicitante -> empresa (pantalla de confirmación). */
    public function toCompany(Ticket $t): string
    {
        $s = CompanySetting::current();
        return $this->url($s->whatsapp_number, $this->text($t, $s->msg_new ?: self::DEFAULTS['new']));
    }

    /** Admin/agente -> solicitante. $type: status | closed */
    public function toRequester(Ticket $t, string $type = 'status'): string
    {
        $s = CompanySetting::current();
        $tpl = $s->{'msg_' . $type} ?: self::DEFAULTS[$type];
        return $this->url($t->requester_whatsapp, $this->text($t, $tpl));
    }

    private function text(Ticket $t, string $tpl): string
    {
        return strtr($tpl, [
            '{nombre}' => $t->requester_name,
            '{folio}' => $t->ticket_number,
            '{categoria}' => $t->category?->name ?? '',
            '{depto}' => $t->department ?? '',
            '{estado}' => $t->status->label(),
            '{descripcion}' => str($t->description)->limit(300)->toString(),
            '{enlace}' => $this->ticketUrl($t),
        ]);
    }

    /** Enlace al detalle del ticket en el panel (pide login si no hay sesión). Usa APP_URL. */
    private function ticketUrl(Ticket $t): string
    {
        return rtrim((string) config('app.url'), '/') . route('tickets.show', $t, false);
    }

    private function url(?string $number, string $text): string
    {
        return 'https://wa.me/' . $this->digits($number) . '?text=' . rawurlencode($text);
    }

    /** Solo dígitos con código de país. México: 10 dígitos -> antepone 52. */
    public function digits(?string $number): string
    {
        $d = preg_replace('/\D/', '', (string) $number);
        if (strlen($d) === 10) $d = '52' . $d;
        if (strlen($d) === 13 && str_starts_with($d, '521')) $d = '52' . substr($d, 3);
        return $d;
    }
}
