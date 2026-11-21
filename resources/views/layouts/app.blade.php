@php($c = \App\Models\CompanySetting::current())
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#F3F6FD">
<title>@yield('title', 'Soporte') · {{ $c->company_name }}</title>
<link rel="preload" href="{{ asset('fonts/PlusJakartaSans-latin-variable.woff2') }}" as="font" type="font/woff2" crossorigin>
<style>
@font-face{font-family:"Plus Jakarta Sans";font-style:normal;font-weight:200 800;font-display:swap;
  src:url("{{ asset('fonts/PlusJakartaSans-latin-variable.woff2') }}") format("woff2")}
:root{
  /* Marca (editable en Ajustes): principal = azul claro, secundario = azul fuerte (botones), acento = cielo */
  --primary: {{ $c->primary_color ?: '#4F7DF0' }};
  --navy: {{ $c->secondary_color ?: '#2A4DB8' }};
  --sky: {{ $c->accent_color ?: '#BCD0FA' }};
  /* Sistema */
  --ink:#1E2A52; --muted:#6B7693; --bg:#F3F6FD; --card:#fff; --field:#F4F7FE;
  --line:#E2E9F8; --gray:#D3DCF2; --err:#D64545; --ok:#2E9E6B; --nav:54px;
  --shadow:0 10px 30px -14px rgba(42,77,184,.28),0 2px 6px rgba(42,77,184,.06);
  --ring:0 0 0 3px color-mix(in srgb, var(--primary) 22%, transparent);
}
*{box-sizing:border-box}
html{-webkit-text-size-adjust:100%}
body{margin:0;color:var(--ink);
  font:15px/1.45 "Plus Jakarta Sans",system-ui,-apple-system,"Segoe UI",Roboto,sans-serif;
  background:
    radial-gradient(360px 300px at 100% 0%, color-mix(in srgb, var(--primary) 20%, transparent), transparent 70%),
    radial-gradient(320px 280px at 0% 38%, color-mix(in srgb, var(--sky) 38%, transparent), transparent 70%),
    var(--bg);
  background-repeat:no-repeat;min-height:100vh;
  -webkit-font-smoothing:antialiased;-webkit-tap-highlight-color:transparent;
  padding:env(safe-area-inset-top) env(safe-area-inset-right) 0 env(safe-area-inset-left)}
body.has-dock{padding-bottom:calc(88px + env(safe-area-inset-bottom))}
body.has-nav{padding-bottom:calc(var(--nav) + 16px + env(safe-area-inset-bottom))}
body.has-nav.has-dock{padding-bottom:calc(var(--nav) + 100px + env(safe-area-inset-bottom))}

/* Encabezado */
.hd{max-width:560px;margin:0 auto;padding:12px 16px 4px;display:flex;align-items:center;justify-content:space-between;gap:10px}
.brand{display:flex;align-items:center;gap:10px;min-width:0}
.logo{width:36px;height:36px;border-radius:12px;display:grid;place-items:center;flex:none;color:#fff;font-weight:700;font-size:16px;
  background:linear-gradient(135deg,var(--primary),var(--navy))}
.hd img{height:36px;border-radius:10px}
.hd b{display:block;font-size:15px;line-height:1.15;letter-spacing:-.01em}
.hd small{display:block;color:var(--muted);font-size:11.5px}
.hbtn{width:40px;height:40px;border-radius:50%;display:grid;place-items:center;flex:none;background:#fff;color:var(--navy);box-shadow:var(--shadow)}
.hbtn svg{width:20px;height:20px}
main{max-width:560px;margin:0 auto;padding:8px 16px 24px}

/* Tipografía */
h1{font-size:22px;line-height:1.2;margin:6px 0 4px;font-weight:800;letter-spacing:-.02em}
h2{font-size:14px;margin:20px 0 8px;font-weight:700}
a{color:var(--primary)}
.muted{color:var(--muted);font-size:14px;margin:0 0 14px}
.muted a{text-decoration:none;font-weight:600}
.note{font-size:12.5px;color:var(--muted);margin:10px 0 0;text-align:center}

/* Portada del formulario */
.hero{display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:6px}
.hero p{margin:0}
.hero svg{width:92px;height:auto;flex:none}

/* Tarjetas */
.card,.stat,.tcard{background:var(--card);border:1px solid var(--line);border-radius:24px;box-shadow:var(--shadow)}
.card{position:relative;overflow:hidden;padding:18px;margin-bottom:12px}
.stat{display:block;text-decoration:none;color:inherit;padding:14px 16px;border-radius:20px}
.stat b{display:block;font-size:26px;line-height:1.1;font-weight:800;font-variant-numeric:tabular-nums}
.stat span{color:var(--muted);font-size:12.5px;font-weight:500}
.tcard{display:block;text-decoration:none;color:inherit;padding:12px 14px;margin-bottom:8px;border-radius:18px}
.tcard .top{display:flex;justify-content:space-between;align-items:center;gap:8px;font-size:14px;font-weight:700}
.tcard small{color:var(--muted);display:block;margin-top:3px;font-size:13px}
.tcard small a{font-weight:600;text-decoration:none}
@media (hover:hover){.stat:hover,.tcard:hover{border-color:var(--primary)}}
.grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px}
.folio{font-size:24px;font-weight:800;text-align:center;margin:2px 0 12px;letter-spacing:.02em;font-variant-numeric:tabular-nums}
dl{margin:0 0 14px}dt{font-size:12px;color:var(--muted)}dd{margin:0 0 8px;font-size:14px;font-weight:600;word-break:break-word}
.bar{position:absolute;left:0;top:0;height:3px;width:0;background:linear-gradient(90deg,var(--primary),var(--navy));transition:width .25s}

/* Formularios */
label{display:block;font-size:13px;font-weight:600;color:#4B5675;margin-bottom:12px}
input,select,textarea{display:block;width:100%;min-height:48px;margin-top:6px;padding:11px 14px;font:inherit;font-size:16px;color:var(--ink);
  background:var(--field);border:1.5px solid var(--gray);border-radius:16px;box-shadow:inset 0 1px 2px rgba(42,77,184,.06);
  transition:border-color .15s,box-shadow .15s,background .15s}
textarea{min-height:96px;resize:vertical;line-height:1.4}
select{-webkit-appearance:none;appearance:none;padding-right:40px;background-repeat:no-repeat;background-position:right 15px center;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1.5l5 5 5-5' fill='none' stroke='%232A4DB8' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E")}
select:invalid{color:#98A3C2}
option{color:var(--ink)}
input::placeholder,textarea::placeholder{color:#98A3C2}
input:focus,select:focus,textarea:focus{outline:0;background:#fff;border-color:var(--primary);box-shadow:var(--ring)}
.err{display:block;color:var(--err);font-weight:500;font-size:13px;margin-top:5px}
.alert,.ok{padding:10px 13px;border-radius:14px;margin-bottom:12px;font-size:14px;font-weight:500}
.alert{background:#FDECEC;color:#9B2C2C;border:1px solid #F3C8C8}
.ok{background:#E6F5EC;color:#1B6B45;border:1px solid #BFE5CE}

/* Campos del formulario público */
.field{margin-bottom:12px;position:relative}
.field label{margin-bottom:0}
.lb{display:flex;align-items:center;gap:8px;font-size:13.5px;font-weight:700;color:var(--ink)}
.lb svg{width:18px;height:18px;flex:none;fill:none;stroke:var(--primary);stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
.field.done input,.field.done select,.field.done textarea{border-color:color-mix(in srgb, var(--primary) 55%, transparent);background:#fff}
.field.bad input,.field.bad select,.field.bad textarea{border-color:var(--err);box-shadow:0 0 0 3px rgba(214,69,69,.1)}
.fe{display:none;color:var(--err);font-size:12.5px;font-weight:500;margin-top:5px}
.field.bad .fe{display:block}
.phone{display:grid;grid-template-columns:68px 1fr;gap:8px}
.phone .cc{margin-top:6px;min-height:48px;display:grid;place-items:center;border-radius:16px;font-weight:700;font-size:15px;
  color:var(--navy);background:color-mix(in srgb, var(--primary) 12%, #fff);border:1.5px solid color-mix(in srgb, var(--primary) 22%, #fff)}
.cnt{text-align:right;font-size:12px;color:var(--muted);margin-top:4px}
.field textarea{resize:none}
.hp{position:absolute;left:-9999px}

/* Interruptor */
.switch{display:flex;align-items:center;gap:10px;color:var(--ink);font-size:15px;font-weight:500;cursor:pointer}
input[type=checkbox]{-webkit-appearance:none;appearance:none;flex:none;width:42px;min-height:0;height:25px;margin:0;padding:0;border:0;border-radius:999px;
  background:#C9D3EA;position:relative;cursor:pointer;transition:background .2s;box-shadow:none}
input[type=checkbox]::after{content:"";position:absolute;top:3px;left:3px;width:19px;height:19px;border-radius:50%;background:#fff;box-shadow:0 1px 2px rgba(0,0,0,.25);transition:transform .2s}
input[type=checkbox]:checked{background:var(--primary)}
input[type=checkbox]:checked::after{transform:translateX(17px)}
input[type=search]{-webkit-appearance:none;appearance:none}
input[type=color]{padding:3px;cursor:pointer}
textarea.sm{min-height:72px}

/* Botones */
.btn{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;min-height:52px;padding:12px 16px;margin-bottom:8px;border:1.5px solid transparent;border-radius:18px;
  background:linear-gradient(135deg,var(--primary),var(--navy));color:#fff;font:inherit;font-size:16px;font-weight:700;line-height:1.2;text-align:center;text-decoration:none;cursor:pointer;
  box-shadow:0 10px 20px -10px color-mix(in srgb, var(--navy) 75%, transparent);transition:filter .15s,transform .05s}
.btn svg{width:20px;height:20px;fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
.btn:active{transform:scale(.98)}
.btn[disabled]{opacity:.65;pointer-events:none}
.btn-light{background:#fff;color:var(--navy);border-color:var(--gray);box-shadow:none}
.btn-soft{background:color-mix(in srgb, var(--sky) 35%, #fff);color:var(--ink);box-shadow:none}
.btn-text{background:transparent;color:var(--primary);box-shadow:none}
.btn-danger{background:#fff;color:var(--err);border-color:#F0C2C6;box-shadow:none}
.btn-wa{background:#168A47;box-shadow:0 10px 20px -10px rgba(22,138,71,.6)}
.btn-dark{background:var(--ink);box-shadow:none}
:focus-visible{outline:2px solid var(--primary);outline-offset:2px}

/* Estados */
.badge{display:inline-flex;align-items:center;gap:5px;font-size:11.5px;font-weight:700;padding:3px 9px;border-radius:999px;white-space:nowrap}
.badge::before{content:"";width:6px;height:6px;border-radius:50%;background:currentColor}
.b-abierto{background:color-mix(in srgb, var(--primary) 14%, #fff);color:var(--navy)}
.b-en_proceso{background:#FDF3E1;color:#8A5A0B}
.b-cerrado{background:#E6F5EC;color:#1B7040}

/* Barra de acción fija (zona del pulgar) y navegación */
.dock{position:fixed;left:0;right:0;bottom:0;z-index:10;border:0;
  background:linear-gradient(to top, var(--bg) 60%, transparent);
  padding:22px 16px calc(12px + env(safe-area-inset-bottom))}
.dock .btn{max-width:528px;margin:0 auto}
.dock.on-nav{bottom:calc(var(--nav) + env(safe-area-inset-bottom));padding-bottom:10px}
.nav{position:fixed;left:0;right:0;bottom:0;z-index:11;background:#fff;border-top:1px solid var(--line);display:flex;padding-bottom:env(safe-area-inset-bottom)}
.nav a{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:2px;min-height:var(--nav);text-decoration:none;color:var(--muted);font-size:11.5px;font-weight:600}
.nav svg{width:20px;height:20px;fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
.nav a.on{color:var(--ink);box-shadow:inset 0 2px 0 var(--primary)}
.nav a.on svg{stroke:var(--primary)}

/* Listas y detalle */
details.filters{margin:8px 0}
details.filters summary{cursor:pointer;font-weight:600;font-size:14px;color:var(--primary);padding:6px 0}
.pager{display:flex;justify-content:space-between;margin:10px 0}
.pager a{color:var(--primary);font-weight:600;font-size:14px;text-decoration:none;padding:8px 4px}
ul.hist{list-style:none;margin:0;padding:0}
ul.hist li{padding:9px 0;border-bottom:1px solid var(--line);font-size:13.5px}
ul.hist li:last-child{border:0}
ul.hist small{display:block;color:var(--muted)}
.cm{padding:9px 0;border-bottom:1px solid var(--line);font-size:14px}
.cm small{color:var(--muted);display:block}
.row2{display:grid;grid-template-columns:1fr auto;gap:8px;align-items:center}
.row2 input,.row2 select{margin:0}
.row2 .btn{width:auto;margin:0;padding:11px 18px;min-height:48px}
.cols3{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
.cols3 label{margin:0}
.toprow{display:flex;justify-content:space-between;align-items:center;gap:8px}
.toprow h2{margin:18px 0 8px}
.lnk{font-size:13px;font-weight:600;text-decoration:none}
.folio-sm{font-size:18px;font-weight:800;letter-spacing:.02em;font-variant-numeric:tabular-nums}
.kv{display:grid;grid-template-columns:1fr 1fr;gap:10px 12px;margin:12px 0 0}
.kv dd{margin:0}
.kv .full{grid-column:1/-1}
.card>h2:first-child{margin-top:0}
.sum{cursor:pointer;font-weight:700;font-size:14px;list-style:none}
.sum::-webkit-details-marker{display:none}
.sum::after{content:"\203A";float:right;color:var(--muted);transition:transform .15s}
details[open]>.sum::after{transform:rotate(90deg)}
details.card[open]>.sum{margin-bottom:8px}

/* Confirmación y pie público */
.done{text-align:center;padding-top:6px}
.tick{width:48px;height:48px;margin:0 auto 8px;border-radius:50%;display:grid;place-items:center;color:var(--ok);background:rgba(46,158,107,.12)}
.foot{max-width:560px;margin:0 auto;padding:4px 16px 20px;text-align:center;font-size:12px;color:var(--muted)}
.foot a{color:var(--primary);font-weight:600;text-decoration:none}

@media (prefers-reduced-motion:reduce){*{transition:none!important}}
</style>
</head>
<body class="@hasSection('dock')has-dock @endif @hasSection('nav')has-nav @endif">
<header class="hd">
  <div class="brand">
    @if($c->logo_path)<img src="{{ asset('storage/'.$c->logo_path) }}" alt="">@else<span class="logo">{{ mb_strtoupper(mb_substr($c->company_name, 0, 1)) }}</span>@endif
    <div><b>{{ $c->company_name }}</b><small>Support Desk</small></div>
  </div>
  @hasSection('public')
    @php($wa = preg_replace('/\D/', '', (string) $c->whatsapp_number))
    @if($wa)
      <a class="hbtn" href="https://wa.me/{{ strlen($wa) === 10 ? '52'.$wa : $wa }}" target="_blank" rel="noopener" aria-label="Escribir por WhatsApp">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/></svg>
      </a>
    @endif
  @endif
</header>
<main>
  @if(session('ok'))<div class="ok">{{ session('ok') }}</div>@endif
  @yield('content')
</main>
@hasSection('public')
<footer class="foot"><a href="{{ url('/login') }}">Acceso</a> · Powered by <a href="https://www.aclsys.net" target="_blank" rel="noopener noreferrer">ACLSYS</a></footer>
@endif
@hasSection('dock')<div class="dock @hasSection('nav')on-nav @endif">@yield('dock')</div>@endif
@yield('nav')
</body>
</html>
