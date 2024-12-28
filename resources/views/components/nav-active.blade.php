@props(['active' => false]) {{-- - untuk mengeluarkan active dari attribut yang muncul pada html - --}}

<a {{ $attributes }}
    class="{{ $active ? 'bg-white text-[#468FAF]' : 'text-white font-bold' }} rounded-md px-8 py-2 text-sm font-bold hover:bg-white hover:text-[#468FAF]"
    aria-current="{{ $active ? 'page' : false }}" {{ $attributes }}>{{ $slot }}</a>
