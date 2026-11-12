@extends('layouts.app')
@section('title', 'Tickets')
@section('content')
<h1>Tickets</h1>

<form method="GET" class="card">
  <input type="search" name="q" value="{{ request('q') }}" placeholder="Folio, nombre o WhatsApp">
  <details class="filters" @if(request()->hasAny(['status', 'category_id', 'range'])) open @endif>
    <summary>Filtros</summary>
    <label>Estado
      <select name="status">
        <option value="">Todos</option>
        @foreach(\App\Enums\TicketStatus::cases() as $s)
          <option value="{{ $s->value }}" @selected(request('status') === $s->value)>{{ $s->label() }}</option>
        @endforeach
      </select>
    </label>
    <label>Categoría
      <select name="category_id">
        <option value="">Todas</option>
        @foreach($groups as $group => $items)
          <optgroup label="{{ $group }}">
            @foreach($items as $cat)
              <option value="{{ $cat->id }}" @selected((string) request('category_id') === (string) $cat->id)>{{ $cat->name }}</option>
            @endforeach
          </optgroup>
        @endforeach
      </select>
    </label>
    <label>Fecha
      <select name="range">
        <option value="">Cualquiera</option>
        @foreach($ranges as $k => $label)
          <option value="{{ $k }}" @selected(request('range') === $k)>{{ $label }}</option>
        @endforeach
      </select>
    </label>
  </details>
  <button class="btn" type="submit">Buscar</button>
  <a class="btn btn-light" href="{{ route('tickets.index') }}">Limpiar</a>
</form>

@forelse($tickets as $t)
  @include('panel._card', ['t' => $t])
@empty
  <p class="muted">No hay solicitudes con esos filtros.</p>
@endforelse

<div class="pager">
  <span>@if($tickets->previousPageUrl())<a href="{{ $tickets->previousPageUrl() }}">← Anteriores</a>@endif</span>
  <span>@if($tickets->nextPageUrl())<a href="{{ $tickets->nextPageUrl() }}">Siguientes →</a>@endif</span>
</div>
@endsection
@section('nav')@include('panel._nav')@endsection
