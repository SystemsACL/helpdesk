@extends('layouts.app')
@section('title', 'Usuarios')
@section('content')
<p class="muted"><a href="{{ route('settings.edit') }}">← Ajustes</a></p>
<h1>Usuarios</h1>

@foreach($users as $u)
  <div class="tcard">
    <div class="top">
      <span>{{ $u->name }}</span>
      <span class="badge {{ $u->is_active ? 'b-cerrado' : 'b-en_proceso' }}">{{ $u->is_active ? 'Activo' : 'Inactivo' }}</span>
    </div>
    <small>{{ $u->email }} · {{ $u->isAdmin() ? 'Administrador' : 'Agente' }}</small>
    @unless($u->isAdmin())
      <small><a href="{{ route('users.edit', $u) }}">Editar</a></small>
    @endunless
  </div>
@endforeach
@endsection
@unless($users->contains(fn ($u) => ! $u->isAdmin()))
@section('dock')<a class="btn" href="{{ route('users.create') }}">Crear agente</a>@endsection
@endunless
@section('nav')@include('panel._nav')@endsection
