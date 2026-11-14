@extends('layouts.app')
@section('title', $ticket->ticket_number)
@section('content')
@php($tz = \App\Models\CompanySetting::tz())
<p class="muted"><a href="{{ route('tickets.index') }}">← Tickets</a></p>
@error('assign')<div class="alert">{{ $message }}</div>@enderror

<div class="card">
  <div class="toprow">
    <div class="folio-sm">{{ $ticket->ticket_number }}</div>
    <span class="badge b-{{ $ticket->status->value }}">{{ $ticket->status->label() }}</span>
  </div>
  <dl class="kv">
    <div><dt>Solicitante</dt><dd>{{ $ticket->requester_name }}</dd></div>
    <div><dt>WhatsApp</dt><dd>{{ $ticket->requester_whatsapp }}</dd></div>
    <div><dt>Servicio</dt><dd>{{ $ticket->category->name }}</dd></div>
    <div><dt>Asignado a</dt><dd>{{ $ticket->assignee?->name ?? 'Sin asignar' }}</dd></div>
    <div><dt>Apertura</dt><dd>{{ $ticket->opened_at->timezone($tz)->format('d/m/Y H:i') }}</dd></div>
    @if($ticket->closed_at)
      <div><dt>Cierre</dt><dd>{{ $ticket->closed_at->timezone($tz)->format('d/m/Y H:i') }}</dd></div>
    @endif
    <div class="full"><dt>Problema</dt><dd>{{ $ticket->description }}</dd></div>
    @if($ticket->resolution)
      <div class="full"><dt>Resolución</dt><dd>{{ $ticket->resolution }}</dd></div>
    @endif
  </dl>
  @if($ticket->status === \App\Enums\TicketStatus::Closed)
    <a class="btn btn-light" style="margin:14px 0 0" href="{{ $waStatus }}" target="_blank" rel="noopener">Avisar estado por WhatsApp</a>
  @endif
</div>

@unless(auth()->user()->isAdmin())
  @if(! $ticket->assigned_user_id)
    <form method="POST" action="{{ route('tickets.assign', $ticket) }}">
      @csrf
      <input type="hidden" name="assigned_user_id" value="{{ auth()->id() }}">
      <button class="btn" type="submit">Tomar este ticket</button>
    </form>
  @elseif($ticket->assigned_user_id === auth()->id())
    <form method="POST" action="{{ route('tickets.assign', $ticket) }}">
      @csrf
      <input type="hidden" name="assigned_user_id" value="">
      <button class="btn btn-light" type="submit">Soltar ticket</button>
    </form>
  @else
    <p class="note" style="margin:0 0 12px">Lo atiende {{ $ticket->assignee?->name }}.</p>
  @endif
@endunless

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
    <textarea class="sm" name="resolution" maxlength="2000">{{ old('resolution', $ticket->resolution) }}</textarea>
    @error('resolution')<span class="err">{{ $message }}</span>@enderror
  </label>
  <button class="btn" style="margin:0" type="submit">Actualizar estado</button>
</form>

@if(auth()->user()->isAdmin())
<h2>Asignación</h2>
<form method="POST" action="{{ route('tickets.assign', $ticket) }}" class="card">
  @csrf
  <div class="row2">
    <select name="assigned_user_id" aria-label="Atiende">
      <option value="">Sin asignar</option>
      @foreach($users as $u)
        <option value="{{ $u->id }}" @selected($ticket->assigned_user_id === $u->id)>{{ $u->name }}</option>
      @endforeach
    </select>
    <button class="btn btn-light" type="submit">Asignar</button>
  </div>
</form>
@endif

<h2>Comentarios internos</h2>
<div class="card">
  @forelse($ticket->comments as $cm)
    <div class="cm">{{ $cm->comment }}<small>{{ $cm->user?->name }} · {{ $cm->created_at->timezone($tz)->format('d/m/Y H:i') }}</small></div>
  @empty
    <p class="muted" style="margin:0 0 10px">Sin comentarios.</p>
  @endforelse
  <form method="POST" action="{{ route('tickets.comment', $ticket) }}" style="margin-top:12px">
    @csrf
    <label style="margin-bottom:10px">Nuevo comentario
      <textarea class="sm" name="comment" maxlength="1000" required></textarea>
      @error('comment')<span class="err">{{ $message }}</span>@enderror
    </label>
    <button class="btn btn-light" style="margin:0" type="submit">Agregar comentario</button>
  </form>
</div>

<details class="card">
  <summary class="sum">Historial ({{ $ticket->events->count() }})</summary>
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
</details>
@endsection
@section('dock')
  @if($ticket->status === \App\Enums\TicketStatus::Closed)
    <a class="btn btn-wa" href="{{ $waClosed }}" target="_blank" rel="noopener">Avisar cierre por WhatsApp</a>
  @else
    <a class="btn btn-wa" href="{{ $waStatus }}" target="_blank" rel="noopener">Avisar estado por WhatsApp</a>
  @endif
@endsection
@section('nav')@include('panel._nav')@endsection
