@extends('layouts.app')
@section('title', 'Inicio')
@section('content')
<h1>Hola, {{ \Illuminate\Support\Str::before(auth()->user()->name, ' ') }}</h1>
<p class="muted">Resumen de solicitudes</p>

<div class="grid">
  <a class="stat" href="{{ route('tickets.index', ['status' => 'abierto']) }}"><b>{{ $stats['abierto'] }}</b><span>Abiertos</span></a>
  <a class="stat" href="{{ route('tickets.index', ['status' => 'en_proceso']) }}"><b>{{ $stats['en_proceso'] }}</b><span>En proceso</span></a>
  <a class="stat" href="{{ route('tickets.index', ['status' => 'cerrado']) }}"><b>{{ $stats['cerrado'] }}</b><span>Cerrados</span></a>
  <a class="stat" href="{{ route('tickets.index') }}"><b>{{ $stats['total'] }}</b><span>Total</span></a>
</div>

<div class="toprow">
  <h2>Recientes</h2>
  <a class="lnk" href="{{ route('tickets.index') }}">Ver todos</a>
</div>
@forelse($recent as $t)
  @include('panel._card', ['t' => $t])
@empty
  <p class="muted">Aún no hay solicitudes.</p>
@endforelse
@endsection
@section('nav')@include('panel._nav')@endsection
