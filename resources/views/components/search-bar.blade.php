@props([
    'placeholder' => 'Cari...',
    'resetRoute'  => null,
    'q'           => '',
])

<div class="be-card px-4 py-3 mb-4">
    <form method="GET" class="flex gap-2">
        <div class="relative flex-1 max-w-sm">
            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-bark-muted text-sm"></i>
            <input type="text" name="q" value="{{ $q }}" placeholder="{{ $placeholder }}"
                   class="input-field pl-9">
        </div>
        <button type="submit" class="btn-create">Cari</button>
        @if ($q && $resetRoute)
            <a href="{{ $resetRoute }}" class="btn-ghost">Reset</a>
        @endif
    </form>
</div>
