@extends('layouts.app')
@section('title', 'Solicitar soporte')
@section('content')
<h1>Solicitar soporte</h1>
<p class="muted">Cuéntanos qué pasa y registra tu solicitud.</p>

@if($errors->any())<div class="alert">Revisa los campos marcados.</div>@endif

<form method="POST" action="{{ route('solicitud.store') }}" class="card">
  @csrf
  <div style="position:absolute;left:-9999px" aria-hidden="true">
    <input type="text" name="website" tabindex="-1" autocomplete="off">
  </div>

  <label>Tu nombre
    <input name="requester_name" value="{{ old('requester_name') }}" maxlength="120" autocomplete="name" required>
    @error('requester_name')<span class="err">{{ $message }}</span>@enderror
  </label>

  <label>Tu WhatsApp
    <input name="requester_whatsapp" value="{{ old('requester_whatsapp') }}" type="tel" inputmode="tel" placeholder="10 dígitos" autocomplete="tel" required>
    @error('requester_whatsapp')<span class="err">{{ $message }}</span>@enderror
  </label>

  <label>Tipo de servicio
    <select name="category_id" required>
      <option value="">Elige una opción…</option>
      @foreach($groups as $group => $items)
        <optgroup label="{{ $group }}">
          @foreach($items as $cat)
            <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
          @endforeach
        </optgroup>
      @endforeach
    </select>
    @error('category_id')<span class="err">{{ $message }}</span>@enderror
  </label>

  <label>Describe el problema
    <textarea name="description" maxlength="1000" required>{{ old('description') }}</textarea>
    @error('description')<span class="err">{{ $message }}</span>@enderror
  </label>

  <button class="btn" type="submit">Enviar ticket</button>
  <p class="note">Si necesitas mandar una captura, envíala por WhatsApp después de registrar tu solicitud.</p>
</form>
@endsection
