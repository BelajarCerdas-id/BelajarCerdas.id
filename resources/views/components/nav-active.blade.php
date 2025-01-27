@props(['active' => false]) {{-- - untuk mengeluarkan active dari attribut yang muncul pada html - --}}

<a {{ $attributes }}
    class="{{ $active ? 'bg-[#60b5cf] text-white' : 'text-[#60b5cf] font-bold' }} rounded-md px-8 py-2 text-sm font-bold hover:bg-[#60b5cf] hover:text-white"
    aria-current="{{ $active ? 'page' : false }}" {{ $attributes }}>{{ $slot }}</a>
