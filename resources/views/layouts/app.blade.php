@php($c = \App\Models\CompanySetting::current())
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#ffffff">
<title>@yield('title', 'Soporte') · {{ $c->company_name }}</title>
<style>
:root{
  /* Marca (editable en Ajustes) */
  --primary: {{ $c->primary_color ?: '#82C8E5' }};
  --secondary: {{ $c->secondary_color ?: '#0047AB' }};
  --accent: {{ $c->accent_color ?: '#0047AB' }};
  /* Sistema */
  --ink:#000080; --text:#1E2B4A; --muted:#5E7188;
  --bg:#F6F8FB; --card:#fff; --field:#F6F8FB; --line:#DDE5EE;
  --err:#B4232F; --r:16px; --r-sm:12px;
  --ring:0 0 0 3px color-mix(in srgb, var(--accent) 22%, transparent);
}
*{box-sizing:border-box}
html{-webkit-text-size-adjust:100%}
body{margin:0;background:var(--bg);color:var(--text);
  font:16px/1.5 Inter,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;
  -webkit-font-smoothing:antialiased;
  padding:env(safe-area-inset-top) env(safe-area-inset-right) env(safe-area-inset-bottom) env(safe-area-inset-left)}

/* Encabezado */
header{position:sticky;top:0;z-index:5;background:rgba(255,255,255,.9);-webkit-backdrop-filter:saturate(180%) blur(12px);backdrop-filter:saturate(180%) blur(12px);
  border-top:3px solid var(--primary);border-bottom:1px solid var(--line);
  padding:12px 20px;display:flex;align-items:center;gap:12px;color:var(--ink);font-weight:700;font-size:17px;letter-spacing:-.01em}
header img{height:32px;border-radius:8px}
main{max-width:560px;margin:0 auto;padding:24px 20px 112px}

/* Tipografía */
h1{font-size:26px;line-height:1.2;margin:8px 0 6px;color:var(--ink);font-weight:700;letter-spacing:-.02em}
h2{font-size:17px;margin:26px 0 8px;color:var(--ink);font-weight:650;letter-spacing:-.01em}
a{color:var(--secondary)}
.muted{color:var(--muted);margin:0 0 18px}
.muted a{text-decoration:none;font-weight:600}
.note{font-size:13px;color:var(--muted);margin:12px 0 0;text-align:center}

/* Tarjetas */
.card,.stat,.tcard{background:var(--card);border:1px solid var(--line);border-radius:var(--r)}
.card{padding:20px;margin-bottom:16px}
.stat{display:block;text-decoration:none;color:inherit;padding:16px;transition:border-color .15s}
.stat b{display:block;font-size:34px;line-height:1.1;font-weight:700;color:var(--ink);font-variant-numeric:tabular-nums}
.stat span{color:var(--muted);font-size:13px;font-weight:500}
.tcard{display:block;text-decoration:none;color:inherit;padding:14px 16px;margin-bottom:10px;transition:border-color .15s}
.tcard .top{display:flex;justify-content:space-between;align-items:center;gap:8px;font-weight:650;color:var(--ink)}
.tcard small{color:var(--muted);display:block;margin-top:4px;font-size:14px}
.tcard small a{font-weight:600;text-decoration:none}
@media (hover:hover){.stat:hover,.tcard:hover{border-color:var(--secondary)}}
.grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px}
.folio{font-size:30px;font-weight:700;color:var(--ink);text-align:center;margin:6px 0 16px;letter-spacing:.02em;font-variant-numeric:tabular-nums}
dl{margin:0 0 18px}dt{font-size:13px;color:var(--muted)}dd{margin:0 0 12px;font-weight:600;word-break:break-word;color:var(--ink)}

/* Formularios */
label{display:block;font-weight:500;font-size:14px;color:var(--muted);margin-bottom:16px}
input,select,textarea{display:block;width:100%;margin-top:6px;padding:13px 14px;font:inherit;font-size:16px;color:var(--text);
  background:var(--field);border:1px solid var(--line);border-radius:var(--r-sm);transition:border-color .15s,box-shadow .15s,background .15s}
textarea{min-height:110px;resize:vertical}
input::placeholder,textarea::placeholder{color:#9AA9BA}
input:focus,select:focus,textarea:focus{outline:0;background:#fff;border-color:var(--accent);box-shadow:var(--ring)}
.err{display:block;color:var(--err);font-weight:500;font-size:14px;margin-top:6px}
.alert,.ok{padding:12px 14px;border-radius:var(--r-sm);margin-bottom:16px;font-size:15px;font-weight:500}
.alert{background:#FDECEE;color:#8E1B26;border:1px solid #F5C9CE}
.ok{background:#E6F5EC;color:#17623B;border:1px solid #BFE5CE}

/* Interruptor */
.switch{display:flex;align-items:center;gap:12px;color:var(--text);font-size:16px;font-weight:500;cursor:pointer}
input[type=checkbox]{-webkit-appearance:none;appearance:none;flex:none;width:46px;height:28px;margin:0;padding:0;border-radius:999px;
  background:#CBD5E1;border:0;position:relative;cursor:pointer;transition:background .2s}
input[type=checkbox]::after{content:"";position:absolute;top:3px;left:3px;width:22px;height:22px;border-radius:50%;background:#fff;
  box-shadow:0 1px 3px rgba(0,0,0,.25);transition:transform .2s}
input[type=checkbox]:checked{background:var(--secondary)}
input[type=checkbox]:checked::after{transform:translateX(18px)}
input[type=checkbox]:focus-visible{outline:0;box-shadow:var(--ring)}

/* Botones */
.btn{display:block;width:100%;text-align:center;text-decoration:none;border:1.5px solid transparent;border-radius:var(--r-sm);padding:14px 16px;
  font:inherit;font-size:16px;font-weight:600;letter-spacing:-.005em;color:#fff;background:var(--secondary);cursor:pointer;margin-bottom:10px;
  transition:filter .15s,background .15s,transform .05s}
.btn:hover{filter:brightness(1.08)}
.btn:active{transform:scale(.985)}
.btn:focus-visible,.nav a:focus-visible,a:focus-visible,summary:focus-visible{outline:0;box-shadow:var(--ring)}
.btn-dark{background:var(--ink)}
.btn-light{background:#fff;color:var(--secondary);border-color:var(--secondary)}
.btn-soft{background:color-mix(in srgb, var(--primary) 22%, #fff);color:var(--ink)}
.btn-text{background:transparent;color:var(--secondary)}
.btn-danger{background:#fff;color:var(--err);border-color:#F0C2C6}
.btn-wa{background:#1FA855}

/* Estados */
.badge{display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:600;padding:4px 10px;border-radius:999px;white-space:nowrap}
.badge::before{content:"";width:7px;height:7px;border-radius:50%;background:currentColor}
.b-abierto{background:#E6EFFA;color:#0047AB}
.b-en_proceso{background:#FDF3E1;color:#96600F}
.b-cerrado{background:#E6F5EC;color:#1B7040}

/* Navegación inferior */
.nav{position:fixed;left:0;right:0;bottom:0;z-index:5;background:rgba(255,255,255,.92);-webkit-backdrop-filter:saturate(180%) blur(12px);backdrop-filter:saturate(180%) blur(12px);
  border-top:1px solid var(--line);display:flex;padding-bottom:env(safe-area-inset-bottom)}
.nav a{flex:1;text-align:center;padding:14px 4px;text-decoration:none;color:var(--muted);font-size:14px;font-weight:600;border-radius:0}
.nav a.on{color:var(--secondary);box-shadow:inset 0 2px 0 var(--secondary)}

/* Listas y detalle */
details.filters{margin:10px 0}
details.filters summary{cursor:pointer;font-weight:600;color:var(--secondary);padding:6px 0}
.pager{display:flex;justify-content:space-between;margin:12px 0}
.pager a{color:var(--secondary);font-weight:600;text-decoration:none;padding:8px 4px}
ul.hist{list-style:none;margin:0;padding:0}
ul.hist li{padding:10px 0;border-bottom:1px solid var(--line);font-size:14px}
ul.hist li:last-child{border:0}
ul.hist small{display:block;color:var(--muted)}
.cm{padding:10px 0;border-bottom:1px solid var(--line)}
.cm small{color:var(--muted);display:block}

@media (prefers-reduced-motion:reduce){*{transition:none!important}}
</style>
</head>
<body>
<header>
  @if($c->logo_path)<img src="{{ asset('storage/'.$c->logo_path) }}" alt="">@endif
  <span>{{ $c->company_name }}</span>
</header>
<main>
  @if(session('ok'))<div class="ok">{{ session('ok') }}</div>@endif
  @yield('content')
</main>
@yield('nav')
</body>
</html>
