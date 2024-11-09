@props(['active' => false]) {{-- - untuk mengeluarkan active dari attribut yang muncul pada html - --}}

<a {{ $attributes }}
    class="{{ $active ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700' }} rounded-md px-8 py-2 text-sm font-medium"
    aria-current="{{ $active ? 'page' : false }}" {{ $attributes }}>{{ $slot }}</a>
