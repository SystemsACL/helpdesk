@extends('layouts.app')
@section('title', 'Acceso')
@section('content')
<h1>Acceso</h1>
<p class="muted">Solo personal de soporte.</p>
@error('email')<div class="alert">{{ $message }}</div>@enderror
<form method="POST" action="{{ route('login.attempt') }}" class="card">
  @csrf
  <label>Correo
    <input type="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
  </label>
  <label>Contraseña
    <input type="password" name="password" autocomplete="current-password" required>
  </label>
  <button class="btn" type="submit">Entrar</button>
</form>
@endsection
