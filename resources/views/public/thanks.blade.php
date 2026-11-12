@extends('layouts.app')
@section('title', 'Solicitud registrada')
@section('content')
<h1>¡Solicitud registrada!</h1>
<p class="muted">Guarda tu folio.</p>

<div class="card">
  <div class="folio">{{ $ticket->ticket_number }}</div>
  <dl>
    <dt>Nombre</dt><dd>{{ $ticket->requester_name }}</dd>
    <dt>Servicio</dt><dd>{{ $ticket->category->name }}</dd>
    <dt>Problema</dt><dd>{{ $ticket->description }}</dd>
  </dl>
  <a class="btn btn-wa" href="{{ $waLink }}" target="_blank" rel="noopener">Enviar por WhatsApp</a>
  <p class="note">Presiona el botón y luego toca “Enviar” en WhatsApp para que recibamos tu mensaje.</p>
</div>
<p class="note"><a href="{{ route('solicitud.create') }}">Registrar otra solicitud</a></p>
@endsection
