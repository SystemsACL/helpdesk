@extends('layouts.app')
@section('title', 'Ajustes')
@section('content')
@php
    $zones = array_values(array_unique(array_merge(
        [$s->timezone ?: 'America/Cancun'],
        ['America/Cancun', 'America/Mexico_City', 'America/Bogota', 'America/Lima', 'America/New_York', 'Europe/Madrid']
    )));
@endphp
<h1>Ajustes</h1>
<p class="muted">Identidad y mensajes de la empresa.</p>

@if($errors->any())<div class="alert">Revisa los campos marcados.</div>@endif

<a class="btn btn-light" href="{{ route('users.index') }}">Usuarios</a>

<form id="ajustes" method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
  @csrf

  <div class="card">
    <h2>Empresa</h2>
    <label>Nombre de la empresa
      <input name="company_name" value="{{ old('company_name', $s->company_name) }}" maxlength="120" required>
      @error('company_name')<span class="err">{{ $message }}</span>@enderror
    </label>
    <label>WhatsApp de la empresa
      <input name="whatsapp_number" type="tel" inputmode="numeric" value="{{ old('whatsapp_number', $s->whatsapp_number) }}" placeholder="10 dígitos o con código de país">
      @error('whatsapp_number')<span class="err">{{ $message }}</span>@enderror
    </label>
    <label>Logotipo (PNG, JPG o WebP, máx. 1 MB)
      @if($s->logo_path)<img src="{{ asset('storage/'.$s->logo_path) }}" alt="" style="height:40px;margin:6px 0 0;display:block">@endif
      <input type="file" name="logo" accept="image/png,image/jpeg,image/webp">
      @error('logo')<span class="err">{{ $message }}</span>@enderror
    </label>
    <label style="margin-bottom:0">Zona horaria
      <select name="timezone">
        @foreach($zones as $z)
          <option value="{{ $z }}" @selected(old('timezone', $s->timezone ?: 'America/Cancun') === $z)>{{ $z }}</option>
        @endforeach
      </select>
    </label>
  </div>

  <div class="card">
    <h2>Colores</h2>
    <div class="cols3">
      <label>Principal
        <input type="color" name="primary_color" value="{{ old('primary_color', $s->primary_color ?: '#348BB0') }}">
      </label>
      <label>Botones
        <input type="color" name="secondary_color" value="{{ old('secondary_color', $s->secondary_color ?: '#222943') }}">
      </label>
      <label>Acento
        <input type="color" name="accent_color" value="{{ old('accent_color', $s->accent_color ?: '#80AEC5') }}">
      </label>
    </div>
  </div>

  <div class="card">
    <h2>Mensajes de WhatsApp</h2>
    <p class="muted">Variables: {nombre} {folio} {categoria} {estado} {descripcion}. Si lo dejas vacío se usa el mensaje por defecto.</p>
    <label>Al registrar un ticket (solicitante → empresa)
      <textarea class="sm" name="msg_new">{{ old('msg_new', $s->msg_new) }}</textarea>
    </label>
    <label>Cambio de estado (empresa → solicitante)
      <textarea class="sm" name="msg_status">{{ old('msg_status', $s->msg_status) }}</textarea>
    </label>
    <label style="margin-bottom:0">Ticket cerrado (empresa → solicitante)
      <textarea class="sm" name="msg_closed">{{ old('msg_closed', $s->msg_closed) }}</textarea>
    </label>
  </div>
</form>
@endsection
@section('dock')<button class="btn" type="submit" form="ajustes">Guardar ajustes</button>@endsection
@section('nav')@include('panel._nav')@endsection
