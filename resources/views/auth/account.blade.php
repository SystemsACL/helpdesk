@extends('layouts.app')
@section('title', 'Mi cuenta')
@section('content')
<h1>Mi cuenta</h1>
<p class="muted">{{ auth()->user()->name }} · {{ auth()->user()->email }}</p>

@if(auth()->user()->must_change_password)
  <div class="alert">Por seguridad, cambia tu contraseña para continuar.</div>
@endif

<form method="POST" action="{{ route('password.update') }}" class="card">
  @csrf
  <label>Contraseña actual
    <input type="password" name="current_password" autocomplete="current-password" required>
    @error('current_password')<span class="err">{{ $message }}</span>@enderror
  </label>
  <label>Contraseña nueva (mínimo 10)
    <input type="password" name="password" autocomplete="new-password" required>
    @error('password')<span class="err">{{ $message }}</span>@enderror
  </label>
  <label>Repite la nueva
    <input type="password" name="password_confirmation" autocomplete="new-password" required>
  </label>
  <button class="btn" type="submit">Cambiar contraseña</button>
</form>

<form method="POST" action="{{ route('logout') }}">
  @csrf
  <button class="btn btn-light" type="submit">Cerrar sesión</button>
</form>
@endsection
@section('nav')@include('panel._nav')@endsection
