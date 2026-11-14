@extends('layouts.app')
@section('title', 'Acceso')
@section('content')
<h1>Acceso</h1>
<p class="muted">Solo personal de soporte.</p>
@error('email')<div class="alert">{{ $message }}</div>@enderror
<form id="login" method="POST" action="{{ route('login.attempt') }}" class="card">
  @csrf
  <label>Correo
    <input type="email" name="email" value="{{ old('email') }}" inputmode="email" autocapitalize="none" autocomplete="username" required autofocus>
  </label>
  <label style="margin-bottom:0">Contraseña
    <input type="password" name="password" autocomplete="current-password" required>
  </label>
</form>
@endsection
@section('dock')<button class="btn" type="submit" form="login">Entrar</button>@endsection
