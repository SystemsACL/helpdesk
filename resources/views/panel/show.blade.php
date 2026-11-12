@extends('layouts.app')
@section('title', $ticket->ticket_number)
@section('content')
@php($tz = \App\Models\CompanySetting::tz())
<p class="muted"><a href="{{ route('tickets.index') }}">← Tickets</a></p>

<div class="card">
  <div class="folio">{{ $ticket->ticket_number }}</div>
  <p style="text-align:center;margin:0 0 14px"><span class="badge b-{{ $ticket->status->value }}">{{ $ticket->status->label() }}</span></p>
  <dl>
    <dt>Solicitante</dt><dd>{{ $ticket->requester_name }}</dd>
    <dt>WhatsApp</dt><dd>{{ $ticket->requester_whatsapp }}</dd>
    <dt>Servicio</dt><dd>{{ $ticket->category->name }}</dd>
    <dt>Problema</dt><dd>{{ $ticket->description }}</dd>
    <dt>Apertura</dt><dd>{{ $ticket->opened_at->timezone($tz)->format('d/m/Y H:i') }}</dd>
    <dt>Asignado a</dt><dd>{{ $ticket->assignee?->name ?? 'Sin asignar' }}</dd>
    @if($ticket->closed_at)
      <dt>Cierre</dt><dd>{{ $ticket->closed_at->timezone($tz)->format('d/m/Y H:i') }}</dd>
    @endif
    @if($ticket->resolution)
      <dt>Resolución</dt><dd>{{ $ticket->resolution }}</dd>
    @endif
  </dl>
  <a class="btn btn-wa" href="{{ $waStatus }}" target="_blank" rel="noopener">Avisar estado por WhatsApp</a>
  @if($ticket->status === \App\Enums\TicketStatus::Closed)
    <a class="btn btn-wa" href="{{ $waClosed }}" target="_blank" rel="noopener">Avisar cierre por WhatsApp</a>
  @endif
</div>

<h2>Estado</h2>
<form method="POST" action="{{ route('tickets.status', $ticket) }}" class="card">
  @csrf
  <label>Estado
    <select name="status">
      @foreach(\App\Enums\TicketStatus::cases() as $s)
        <option value="{{ $s->value }}" @selected($ticket->status === $s)>{{ $s->label() }}</option>
      @endforeach
    </select>
  </label>
  <label>Resolución (obligatoria para cerrar)
    <textarea name="resolution" maxlength="2000">{{ old('resolution', $ticket->resolution) }}</textarea>
    @error('resolution')<span class="err">{{ $message }}</span>@enderror
  </label>
  <button class="btn" type="submit">Actualizar estado</button>
</form>

<h2>Asignación</h2>
<form method="POST" action="{{ route('tickets.assign', $ticket) }}" class="card">
  @csrf
  <label>Atiende
    <select name="assigned_user_id">
      <option value="">Sin asignar</option>
      @foreach($users as $u)
        <option value="{{ $u->id }}" @selected($ticket->assigned_user_id === $u->id)>{{ $u->name }}</option>
      @endforeach
    </select>
  </label>
  <button class="btn btn-light" type="submit">Asignar</button>
</form>

<h2>Comentarios internos</h2>
<div class="card">
  @forelse($ticket->comments as $cm)
    <div class="cm">{{ $cm->comment }}<small>{{ $cm->user?->name }} · {{ $cm->created_at->timezone($tz)->format('d/m/Y H:i') }}</small></div>
  @empty
    <p class="muted" style="margin:0 0 10px">Sin comentarios.</p>
  @endforelse
  <form method="POST" action="{{ route('tickets.comment', $ticket) }}" style="margin-top:12px">
    @csrf
    <label>Nuevo comentario
      <textarea name="comment" maxlength="1000" required></textarea>
      @error('comment')<span class="err">{{ $message }}</span>@enderror
    </label>
    <button class="btn btn-light" type="submit">Agregar comentario</button>
  </form>
</div>

<h2>Historial</h2>
<div class="card">
  <ul class="hist">
    @foreach($ticket->events as $e)
      <li>
        @switch($e->event_type)
          @case('created') Ticket creado @break
          @case('closed') Ticket cerrado @break
          @case('status_changed')
            Estado: {{ \App\Enums\TicketStatus::tryFrom((string) $e->old_value)?->label() }} → {{ \App\Enums\TicketStatus::tryFrom((string) $e->new_value)?->label() }}
            @break
          @case('assigned') Asignado a {{ $e->new_value ?? 'nadie' }} @break
          @case('commented') Comentario: {{ $e->description }} @break
          @default {{ $e->event_type }}
        @endswitch
        <small>{{ $e->user?->name ?? 'Solicitante' }} · {{ $e->created_at->timezone($tz)->format('d/m/Y H:i') }}</small>
      </li>
    @endforeach
  </ul>
</div>
@endsection
@section('nav')@include('panel._nav')@endsection
