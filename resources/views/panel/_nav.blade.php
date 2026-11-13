<style>main{padding-bottom:100px}</style>
<nav class="nav">
  <a href="{{ route('panel') }}" class="{{ request()->routeIs('panel') ? 'on' : '' }}">Inicio</a>
  <a href="{{ route('tickets.index') }}" class="{{ request()->routeIs('tickets.*') ? 'on' : '' }}">Tickets</a>
  @if(auth()->user()->isAdmin())
    <a href="{{ route('settings.edit') }}" class="{{ request()->routeIs('settings.*', 'users.*') ? 'on' : '' }}">Ajustes</a>
  @endif
  <a href="{{ route('password.edit') }}" class="{{ request()->routeIs('password.*') ? 'on' : '' }}">Cuenta</a>
</nav>