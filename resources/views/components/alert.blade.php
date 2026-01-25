@props([
    'type' => 'success', // success | danger | warning | info
    'message' => null
])

@if($message)
    <div class="alert alert-{{ $type }}">
        {{ $message }}
    </div>
@endif
