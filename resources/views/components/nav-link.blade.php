@props(['route', 'label', 'icon'])

<a href="{{ route($route) }}"
   {{ $attributes->class([
        'nav-item',
        'active' => request()->routeIs($route),
   ]) }}>
   <i class="{{ $icon }}" style="width: 20px; text-align: center;"></i>
    {{ $label }}
</a>
