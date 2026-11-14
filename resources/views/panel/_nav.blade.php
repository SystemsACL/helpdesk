<nav class="nav">
  <a href="{{ route('panel') }}" class="{{ request()->routeIs('panel') ? 'on' : '' }}">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 11l9-8 9 8"/><path d="M5 10v10h14V10"/></svg>Inicio
  </a>
  <a href="{{ route('tickets.index') }}" class="{{ request()->routeIs('tickets.*') ? 'on' : '' }}">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 6h13M8 12h13M8 18h13"/><path d="M3 6h.01M3 12h.01M3 18h.01"/></svg>Tickets
  </a>
  @if(auth()->user()->isAdmin())
  <a href="{{ route('settings.edit') }}" class="{{ request()->routeIs('settings.*', 'users.*') ? 'on' : '' }}">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h9M17 6h3M4 12h3M11 12h9M4 18h11M19 18h1"/><circle cx="15" cy="6" r="2"/><circle cx="9" cy="12" r="2"/><circle cx="17" cy="18" r="2"/></svg>Ajustes
  </a>
  @endif
  <a href="{{ route('password.edit') }}" class="{{ request()->routeIs('password.*') ? 'on' : '' }}">
    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg>Cuenta
  </a>
</nav>
