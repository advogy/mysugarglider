@props([
    'type'    => 'success',   // success | danger | warning
    'message' => null,
    'errors'  => null,
    'class'   => 'mb-5',
])

@php
    $hasErrors  = $errors instanceof \Illuminate\Support\MessageBag ? $errors->any() : false;
    $hasMessage = !empty($message);
    $show       = $hasMessage || $hasErrors;

    $icon = match($type) {
        'success' => 'bi-check-circle-fill',
        'warning' => 'bi-exclamation-triangle-fill',
        default   => 'bi-exclamation-circle-fill',
    };
@endphp

@if ($show)
<div class="alert-{{ $type }} {{ $class }}">
    <i class="bi {{ $icon }} text-lg flex-shrink-0"></i>
    @if ($hasErrors)
        <div>@foreach ($errors->all() as $err)<p>{{ $err }}</p>@endforeach</div>
    @else
        <p class="font-semibold">{{ $message }}</p>
    @endif
</div>
@endif
