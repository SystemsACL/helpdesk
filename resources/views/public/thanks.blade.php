@extends('layouts.app')
@section('title', 'Solicitud registrada')
@section('public', '1')
@section('content')
<div class="done">
  <span class="tick"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg></span>
  <h1>¡Solicitud registrada!</h1>
  <p class="muted">Guarda tu folio.</p>
</div>

<div class="card">
  <div class="folio">{{ $ticket->ticket_number }}</div>
  <dl>
    <dt>Nombre</dt><dd>{{ $ticket->requester_name }}</dd>
    <dt>Departamento</dt><dd>{{ $ticket->department }}</dd>
    <dt>Servicio</dt><dd>{{ $ticket->category->name }}</dd>
    <dt>Problema</dt><dd>{{ $ticket->description }}</dd>
  </dl>
  <p class="note" style="margin:0">Presiona “Enviar por WhatsApp” y luego toca “Enviar” en WhatsApp para que recibamos tu mensaje.</p>
</div>
<p class="note"><a href="{{ route('solicitud.create') }}">Registrar otra solicitud</a></p>
@endsection
@section('dock')<a class="btn btn-wa" href="{{ $waLink }}" target="_blank" rel="noopener">Enviar por WhatsApp</a>@endsection
