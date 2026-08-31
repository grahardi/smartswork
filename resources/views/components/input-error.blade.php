@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-xs text-[#DC2626] space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
