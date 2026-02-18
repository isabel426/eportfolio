@props([
    'types' => ['success', 'error', 'warning', 'info'],
])

@php
    $messages = collect($types)
        ->filter(fn ($type) => session()->has($type))
        ->map(fn ($type) => [
            'type'    => $type,
            'message' => session($type),
        ]);
@endphp

@if ($messages->isNotEmpty())
    <div class="flash-container" role="alert" aria-live="polite">
        @foreach ($messages as $flash)
            <div class="flash flash--{{ $flash['type'] }}" data-flash>
                <span class="flash__icon" aria-hidden="true"></span>
                <p class="flash__message">{{ $flash['message'] }}</p>
                <button
                    class="flash__close"
                    type="button"
                    aria-label="Cerrar"
                    onclick="this.closest('[data-flash]').remove()"
                >
                    &times;
                </button>
            </div>
        @endforeach
    </div>
@endif
