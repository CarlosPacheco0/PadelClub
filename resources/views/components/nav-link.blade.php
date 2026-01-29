@props(['route', 'label'])

<a href="{{ route($route) }}"
   {{ $attributes->class([
        'nav-item',
        'active' => request()->routeIs($route),
   ]) }}>
    {{ $label }}
</a>
