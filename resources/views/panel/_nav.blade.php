<style>main{padding-bottom:100px}</style>
<nav class="nav">
  <a href="{{ route('panel') }}" class="{{ request()->routeIs('panel') ? 'on' : '' }}">Inicio</a>
  <a href="{{ route('tickets.index') }}" class="{{ request()->routeIs('tickets.*') ? 'on' : '' }}">Tickets</a>
  <a href="{{ route('password.edit') }}" class="{{ request()->routeIs('password.*') ? 'on' : '' }}">Cuenta</a>
</nav>
