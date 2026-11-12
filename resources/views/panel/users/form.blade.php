@extends('layouts.app')
@section('title', $user ? 'Editar agente' : 'Crear agente')
@section('content')
<p class="muted"><a href="{{ route('users.index') }}">← Usuarios</a></p>
<h1>{{ $user ? 'Editar agente' : 'Crear agente' }}</h1>

@if($errors->any())<div class="alert">Revisa los campos marcados.</div>@endif

<form method="POST" action="{{ $user ? route('users.update', $user) : route('users.store') }}" class="card">
  @csrf

  <label>Nombre
    <input name="name" value="{{ old('name', $user?->name) }}" maxlength="120" required>
    @error('name')<span class="err">{{ $message }}</span>@enderror
  </label>

  <label>Correo
    <input name="email" type="email" value="{{ old('email', $user?->email) }}" maxlength="150" required>
    @error('email')<span class="err">{{ $message }}</span>@enderror
  </label>

  <label>Teléfono (opcional)
    <input name="phone" type="tel" value="{{ old('phone', $user?->phone) }}" maxlength="20">
    @error('phone')<span class="err">{{ $message }}</span>@enderror
  </label>

  <label>{{ $user ? 'Nueva contraseña (déjala vacía para no cambiarla)' : 'Contraseña temporal' }}
    <input name="password" type="password" minlength="10" autocomplete="new-password" {{ $user ? '' : 'required' }}>
    <small class="muted">Mínimo 10 caracteres. El agente deberá cambiarla al entrar.</small>
    @error('password')<span class="err">{{ $message }}</span>@enderror
  </label>

  @if($user)
    <label class="switch">
      <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active))>
      <span>Agente activo</span>
    </label>
  @endif

  <button class="btn" type="submit">{{ $user ? 'Guardar cambios' : 'Crear agente' }}</button>
</form>
@endsection
@section('nav')@include('panel._nav')@endsection
