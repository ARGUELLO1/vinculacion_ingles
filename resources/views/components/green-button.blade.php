<button
    {{ $attributes->merge([
        'class' => 'px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 active:bg-green-800 transition',
    ]) }}>
    {{ $slot }}
</button>
