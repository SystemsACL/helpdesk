@extends('layouts.app')
@section('title', 'Solicitar soporte')
@section('public', '1')
@section('content')
<div class="hero" style="position:relative">
  <div style="position:relative;z-index:1">
    <h1>Solicitar soporte</h1>
    <p class="muted">Registra tu solicitud en menos de 30 segundos. Te avisamos por WhatsApp.</p>
  </div>
  <svg viewBox="0 0 120 106" fill="none" aria-hidden="true" style="position:absolute;right:-4px;top:-14px;width:128px;opacity:.2;pointer-events:none">
    <rect x="6" y="6" width="108" height="76" rx="12" fill="var(--sky)" stroke="var(--primary)" stroke-width="6"/>
    <rect x="16" y="16" width="88" height="56" rx="4" fill="#fff" stroke="var(--primary)" stroke-width="4"/>
    <rect x="52" y="82" width="16" height="10" fill="var(--primary)"/>
    <rect x="34" y="92" width="52" height="10" rx="5" fill="var(--sky)" stroke="var(--primary)" stroke-width="4"/>
    <circle cx="42" cy="44" r="17" fill="none" stroke="var(--primary)" stroke-width="6" stroke-dasharray="5.34 5.34"/>
    <circle cx="42" cy="44" r="12" fill="var(--sky)" stroke="var(--primary)" stroke-width="5"/>
    <circle cx="42" cy="44" r="4.5" fill="#fff" stroke="var(--primary)" stroke-width="3"/>
    <g transform="translate(62 20) scale(1.7)" fill="var(--sky)" stroke="var(--primary)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a4 4 0 0 0-5.4 5.4L3 18l3 3 6.3-6.3a4 4 0 0 0 5.4-5.4l-2.5 2.5-2.8-.7-.7-2.8 2.5-2.5z"/></g>
  </svg>
</div>









<div class="card">
  <div class="bar" id="bar"></div>
  <form id="solicitud" method="POST" action="{{ route('solicitud.store') }}" novalidate>
    @csrf
    <div class="hp" aria-hidden="true">
      <input type="text" name="hp_field" tabindex="-1" autocomplete="off">
    </div>

    <div class="field @error('requester_name') bad @enderror" data-f="n">
      <label for="n" class="lb"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg>Tu nombre</label>
      <input id="n" name="requester_name" value="{{ old('requester_name') }}" maxlength="120" autocomplete="name" placeholder="Ej. María López" required>
      <span class="fe">@error('requester_name'){{ $message }}@enderror</span>
    </div>

    <div class="field @error('requester_whatsapp') bad @enderror" data-f="w">
      <label for="w" class="lb"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 11.5a8.4 8.4 0 0 1-12.2 7.5L3 21l1.9-5.6A8.4 8.4 0 1 1 21 11.5z"/></svg>Tu WhatsApp</label>
      <div class="phone">
        <div class="cc">+52</div>
        <input id="w" name="requester_whatsapp" value="{{ old('requester_whatsapp') }}" type="tel" inputmode="numeric" autocomplete="tel-national" placeholder="10 dígitos" maxlength="25" required>
      </div>
      <span class="fe">@error('requester_whatsapp'){{ $message }}@enderror</span>
    </div>

    <div class="field @error('department') bad @enderror" data-f="dp">
      <label for="dp" class="lb"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7z"/></svg>Departamento</label>
      <input id="dp" name="department" value="{{ old('department') }}" maxlength="80" autocomplete="off" placeholder="Ej. Contabilidad" required>
      <span class="fe">@error('department'){{ $message }}@enderror</span>
    </div>

    <div class="field @error('category_id') bad @enderror" data-f="s">
      <label for="s" class="lb"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14.7 6.3a4 4 0 0 0-5.4 5.4L3 18l3 3 6.3-6.3a4 4 0 0 0 5.4-5.4l-2.5 2.5-2.8-.7-.7-2.8 2.5-2.5z"/></svg>Tipo de servicio</label>
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
      <label for="d" class="lb"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>Describe el problema</label>
      <textarea id="d" name="description" maxlength="1000" placeholder="Cuéntalo con tus palabras…" required>{{ old('description') }}</textarea>
      <div class="cnt" id="cnt">0/1000</div>
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
    dp:function(v){return v.trim().length>=2},
    s:function(v){return !!v},
    d:function(v){return v.trim().length>=10}
  };
  var M={n:'Escribe tu nombre.',w:'Necesitamos 10 dígitos para avisarte.',dp:'Escribe tu departamento.',s:'Elige con qué necesitas ayuda.',d:'Cuéntanos un poco más (mínimo 10 caracteres).'};
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
    $('#cnt').textContent=val('d').length+'/1000';
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
  var btn=$('#send'),lab=btn.querySelector('span');
  $('#solicitud').addEventListener('submit',function(e){
    var first=null;
    K.forEach(function(k){var bad=!R[k](val(k));mark(k,bad);if(bad&&!first)first=k});
    if(first){e.preventDefault();$('#'+first).focus();return}
    btn.disabled=true;lab.textContent='Enviando…';
  });
  window.addEventListener('pageshow',function(){btn.disabled=false;lab.textContent='Enviar ticket'});
  progress();
});
</script>
@endsection
@section('dock')<button id="send" class="btn" type="submit" form="solicitud"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/></svg><span>Enviar ticket</span></button>@endsection
