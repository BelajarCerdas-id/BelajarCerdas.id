@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span
                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md dark:text-gray-600 dark:bg-gray-800 dark:border-gray-600">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span
                    class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md dark:text-gray-600 dark:bg-gray-800 dark:border-gray-600">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-center mt-4 mb-4">
            <div class="absolute left-8">
                <p class="text-sm text-gray-700 leading-5 dark:text-gray-400">
                    {!! __('Showing') !!}
                    @if ($paginator->firstItem())
                        <span class="font-medium">{{ $paginator->lastItem() }}</span>
                        {{-- {!! __('to') !!}
                        <span class="font-medium">{{ $paginator->lastItem() }}</span> --}}
                    @else
                        {{ $paginator->count() }}
                    @endif
                    {!! __('of') !!}
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rtl:flex-row-reverse shadow-sm rounded-md">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span
                                class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white cursor-default rounded-l-md leading-5"
                                aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                            class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white rounded-l-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none ring-gray-300 focus:border-blue-300 active:text-gray-500 transition ease-in-out duration-150 dark:focus:border-blue-800"
                            aria-label="{{ __('pagination.previous') }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    <div class="pagination">
                        @php
                            $totalPages = $paginator->lastPage();
                            $currentPage = $paginator->currentPage();
                        @endphp

                        {{-- @if ($currentPage >= 5)
                            <span
                                class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 cursor-default leading-5">...</span>
                        @else
                            {{-- untuk nambahin halaman bisa pake cara ini (manual add halaman) atau bisa pake for tepat dibawah ini (otomatis add halaman)
                            <a href="{{ $paginator->url(2) }}"
                                class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600">{{ 2 }}</a>
                            <a href="{{ $paginator->url(3) }}"
                                class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600">{{ 3 }}</a>
                        @endif --}}

                        {{-- Halaman Pertama --}}
                        @if ($currentPage == 1)
                            <div class="mt-1">
                                <span aria-current="page"
                                    class="relative inline-flex items-center px-[10px] py-[5px] text-sm {{ $currentPage == 1 ? 'text-white font-bold bg-[#007bff] rounded-[50%]' : '' }} cursor-default leading-5">{{ 1 }}</span>
                            </div>
                        @else
                            <div class="mt-1">
                                <a href="{{ $paginator->url(1) }}"
                                    class="relative inline-flex items-center px-[10px] py-[5px] text-sm leading-5 hover:text-gray-500 focus:outline-none focus:border-blue-300 transition ease-in-out duration-150">{{ 1 }}</a>
                            </div>
                        @endif

                        {{-- styling span active selain no 1 bisa tanpa ternary operator langsung styling di class span nya aja, tapi biar clean pake ternary semua --}}
                        {{-- menampilkan halaman dari 2-5 dan tidak lebih dari 5(jika data user tidak lebih dari 5 halaman maka menggunakan ini) --}}
                        @if ($totalPages <= 5)
                            @for ($page = 2; $page <= min(5, $totalPages); $page++)
                                @if ($page == $currentPage)
                                    <div class="mt-1">
                                        <span aria-current="page"
                                            class="relative inline-flex items-center px-[10px] py-[5px] text-sm {{ $currentPage == $page ? 'text-white font-bold bg-[#007bff] rounded-[50%]' : '' }} cursor-default leading-5">{{ $page }}</span>
                                    </div>
                                @else
                                    <div class="mt-1">
                                        <a href="{{ $paginator->url($page) }}"
                                            class="relative inline-flex items-center px-[10px] py-[5px] text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:outline-none focus:border-blue-300 transition ease-in-out duration-150">{{ $page }}</a>
                                    </div>
                                @endif
                            @endfor
                        @endif

                        {{-- menampilkan "---" setelah no satu jika currentPage lebih >= 5 --}}
                        @if ($currentPage >= 5)
                            <span
                                class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 cursor-default leading-5">...</span>
                        @endif

                        {{-- menampilkan halaman 2,3,4, dan 5(jika data user lebih dari 5 halaman maka menggunakan ini) --}}
                        @if ($totalPages > 5)
                            @if ($currentPage <= 4)
                                @for ($page = 2; $page <= min(5, $totalPages); $page++)
                                    @if ($page == $currentPage)
                                        <div class="mt-1">
                                            <span aria-current="page"
                                                class="relative inline-flex items-center px-[10px] py-[5px] text-sm {{ $currentPage == $page ? 'text-white font-bold bg-[#007bff] rounded-[50%]' : '' }} cursor-default leading-5">{{ $page }}</span>
                                        </div>
                                    @else
                                        <div class="mt-1">
                                            <a href="{{ $paginator->url($page) }}"
                                                class="relative inline-flex items-center px-[10px] py-[5px] text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:outline-none ring-gray-300 focus:border-blue-300 transition ease-in-out duration-150">{{ $page }}</a>
                                        </div>
                                    @endif
                                @endfor
                            @elseif ($currentPage > $totalPages - 4)
                                {{-- Menampilkan 4 halaman terakhir --}}
                                @for ($page = max(3, $totalPages - 4); $page <= $totalPages; $page++)
                                    @if ($page == $currentPage)
                                        <div class="mt-1">
                                            <span aria-current="page"
                                                class="relative inline-flex items-center px-[10px] py-[5px] text-sm {{ $currentPage == $page ? 'text-white font-bold bg-[#007bff] rounded-[50%]' : '' }} cursor-default leading-5">{{ $page }}</span>
                                        </div>
                                    @else
                                        <div class="mt-1">
                                            <a href="{{ $paginator->url($page) }}"
                                                class="relative inline-flex items-center px-[10px] py-[5px] text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:outline-none ring-gray-300 focus:border-blue-300 transition ease-in-out duration-150">{{ $page }}</a>
                                        </div>
                                    @endif
                                @endfor
                            @else
                                {{-- Menampilkan halaman di tengah dengan ellipsis(jangan dihapus dulu siapa tau berguna) --}}
                                @if ($currentPage >= 5)
                                    {{-- <span
                                        class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 cursor-default leading-5">...</span> --}}
                                @endif

                                @for ($page = max(2, $currentPage - 1); $page <= min($currentPage + 1, $totalPages - 1); $page++)
                                    @if ($page == $currentPage)
                                        <div class="mt-1">
                                            <span aria-current="page"
                                                class="relative inline-flex items-center px-[10px] py-[5px] text-sm {{ $currentPage == $page ? 'text-white font-bold bg-[#007bff] rounded-[50%]' : '' }} cursor-default leading-5 dark:bg-gray-800 dark:border-gray-600">{{ $page }}</span>
                                        </div>
                                    @else
                                        <div class="mt-1">
                                            <a href="{{ $paginator->url($page) }}"
                                                class="relative inline-flex items-center px-[10px] py-[5px] text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:outline-none ring-gray-300 focus:border-blue-300 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600">{{ $page }}</a>
                                        </div>
                                    @endif
                                @endfor
                            @endif
                        @endif

                        {{-- HalamanTerakhir --}}
                        @if ($totalPages == 6)
                            @if ($currentPage < $totalPages - 1)
                                <span
                                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 cursor-default leading-5">...</span>
                            @endif
                        @endif

                        @if ($totalPages == 7)
                            @if ($currentPage < $totalPages - 2)
                                <span
                                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 cursor-default leading-5">...</span>
                                <div class="mt-1">
                                    <a href="{{ $paginator->url($totalPages) }}"
                                        class="relative inline-flex items-center px-[10px] py-[5px] text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 transition ease-in-out duration-150">{{ $totalPages }}</a>
                                </div>
                            @endif
                        @endif

                        @if ($totalPages > 7)
                            @if ($currentPage < $totalPages - 3)
                                <span
                                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 cursor-default leading-5">...</span>
                                <div class="mt-1">
                                    <a href="{{ $paginator->url($totalPages) }}"
                                        class="relative inline-flex items-center px-[10px] py-[5px] text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 transition ease-in-out duration-150">{{ $totalPages }}</a>
                                </div>
                            @endif
                        @endif
                    </div>

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                            class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white rounded-r-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none ring-gray-300 focus:border-blue-300 active:text-gray-500 transition ease-in-out duration-150 dark:focus:border-blue-800"
                            aria-label="{{ __('pagination.next') }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span
                                class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white cursor-default rounded-r-md leading-5"
                                aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
