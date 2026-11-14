@extends('layouts.app')
@section('title', $user ? 'Editar agente' : 'Crear agente')
@section('content')
<p class="muted"><a href="{{ route('users.index') }}">← Usuarios</a></p>
<h1>{{ $user ? 'Editar agente' : 'Crear agente' }}</h1>

@if($errors->any())<div class="alert">Revisa los campos marcados.</div>@endif

<form id="agente" method="POST" action="{{ $user ? route('users.update', $user) : route('users.store') }}" class="card">
  @csrf

  <label>Nombre
    <input name="name" value="{{ old('name', $user?->name) }}" maxlength="120" required>
    @error('name')<span class="err">{{ $message }}</span>@enderror
  </label>

  <label>Correo
    <input name="email" type="email" inputmode="email" autocapitalize="none" value="{{ old('email', $user?->email) }}" maxlength="150" required>
    @error('email')<span class="err">{{ $message }}</span>@enderror
  </label>

  <label>Teléfono (opcional)
    <input name="phone" type="tel" value="{{ old('phone', $user?->phone) }}" maxlength="20">
    @error('phone')<span class="err">{{ $message }}</span>@enderror
  </label>

  <label @if(! $user) style="margin-bottom:0" @endif>{{ $user ? 'Nueva contraseña (vacía para no cambiarla)' : 'Contraseña temporal' }}
    <input name="password" type="password" minlength="10" autocomplete="new-password" {{ $user ? '' : 'required' }}>
    <small class="muted" style="display:block;margin:4px 0 0;font-size:12.5px">Mínimo 10 caracteres. El agente deberá cambiarla al entrar.</small>
    @error('password')<span class="err">{{ $message }}</span>@enderror
  </label>

  @if($user)
    <label class="switch" style="margin:4px 0 0">
      <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active))>
      <span>Agente activo</span>
    </label>
  @endif
</form>
@endsection
@section('dock')<button class="btn" type="submit" form="agente">{{ $user ? 'Guardar cambios' : 'Crear agente' }}</button>@endsection
@section('nav')@include('panel._nav')@endsection
