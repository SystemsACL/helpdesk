@extends('layouts.app')
@section('title', 'Solicitar soporte')
@section('public', '1')
@section('content')
<h1>Solicitar soporte</h1>
<p class="muted">Registra tu solicitud en menos de 30 segundos. Te avisamos por WhatsApp.</p>

<div class="card">
  <div class="bar" id="bar"></div>
  <form id="solicitud" method="POST" action="{{ route('solicitud.store') }}" novalidate>
    @csrf
    <div class="hp" aria-hidden="true">
      <input type="text" name="hp_field" tabindex="-1" autocomplete="off">
    </div>

    <div class="field @error('requester_name') bad @enderror" data-f="n">
      <label for="n">Tu nombre</label>
      <input id="n" name="requester_name" value="{{ old('requester_name') }}" maxlength="120" autocomplete="name" placeholder="Ej. María López" required>
      <span class="fe">@error('requester_name'){{ $message }}@enderror</span>
    </div>

    <div class="field @error('requester_whatsapp') bad @enderror" data-f="w">
      <label for="w">Tu WhatsApp</label>
      <div class="phone">
        <div class="cc">+52</div>
        <input id="w" name="requester_whatsapp" value="{{ old('requester_whatsapp') }}" type="tel" inputmode="numeric" autocomplete="tel-national" placeholder="10 dígitos" maxlength="25" required>
      </div>
      <span class="fe">@error('requester_whatsapp'){{ $message }}@enderror</span>
    </div>

    <div class="field @error('category_id') bad @enderror" data-f="s">
      <label for="s">Tipo de servicio</label>
      <select id="s" name="category_id" required>
        <option value="" disabled @selected(! old('category_id'))>Elige una opción…</option>
        @foreach($groups as $group => $items)
          <optgroup label="{{ $group }}">
            @foreach($items as $cat)
              <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
          </optgroup>
        @endforeach
      </select>
      <span class="fe">@error('category_id'){{ $message }}@enderror</span>
    </div>

    <div class="field @error('description') bad @enderror" data-f="d">
      <label for="d">Describe el problema</label>
      <textarea id="d" name="description" maxlength="1000" placeholder="Cuéntalo con tus palabras" required>{{ old('description') }}</textarea>
      <span class="fe">@error('description'){{ $message }}@enderror</span>
    </div>
  </form>
</div>
<p class="note">Si necesitas mandar una captura, envíala por WhatsApp después de registrar tu solicitud.</p>

<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=function(s){return document.querySelector(s)};
  var R={
    n:function(v){return v.trim().length>=2},
    w:function(v){return v.replace(/\D/g,'').length===10},
    s:function(v){return !!v},
    d:function(v){return v.trim().length>=10}
  };
  var M={n:'Escribe tu nombre.',w:'Necesitamos 10 dígitos para avisarte.',s:'Elige con qué necesitas ayuda.',d:'Cuéntanos un poco más (mínimo 10 caracteres).'};
  var K=Object.keys(R);
  var val=function(k){return $('#'+k).value};
  var field=function(k){return $('[data-f="'+k+'"]')};
  function mark(k,bad){
    var f=field(k);
    f.classList.toggle('bad',bad);
    if(bad)f.querySelector('.fe').textContent=M[k];
  }
  function progress(){
    var n=0;
    K.forEach(function(k){var ok=R[k](val(k));if(ok)n++;field(k).classList.toggle('done',ok)});
    $('#bar').style.width=(n/K.length*100)+'%';
  }
  $('#w').addEventListener('input',function(e){
    var d=e.target.value.replace(/\D/g,'').slice(0,10);
    e.target.value=d.replace(/^(\d{2})(\d{0,4})(\d{0,4})$/,function(m,a,b,c){return [a,b,c].filter(Boolean).join(' ')});
  });
  K.forEach(function(k){
    var el=$('#'+k);
    var onInput=function(){progress();if(R[k](val(k)))field(k).classList.remove('bad')};
    el.addEventListener('input',onInput);
    el.addEventListener('change',onInput);
    el.addEventListener('blur',function(){if(val(k)!=='')mark(k,!R[k](val(k)))});
  });
  var btn=$('#send');
  $('#solicitud').addEventListener('submit',function(e){
    var first=null;
    K.forEach(function(k){var bad=!R[k](val(k));mark(k,bad);if(bad&&!first)first=k});
    if(first){e.preventDefault();$('#'+first).focus();return}
    btn.disabled=true;btn.textContent='Enviando…';
  });
  window.addEventListener('pageshow',function(){btn.disabled=false;btn.textContent='Enviar ticket'});
  progress();
});
</script>
@endsection
@section('dock')<button id="send" class="btn" type="submit" form="solicitud">Enviar ticket</button>@endsection
