<x-script></x-script>

<nav class="mb-32">
    <div class="flex justify-between items-center h-24 px-7 fixed w-full bg-white top-0 z-[9999]">
        <!-- navbar elements --->
        @if (request()->routeIs('homePage'))
            <!-- element top left -->
            <div class="flex items-center justify-between w-full">
                <!--- for mobile --->
                <div class="flex items-center relative lg:left-[-98px] gap-4">
                    <div
                        class="relative top-[-20px] h-32 w-32 bg-white rounded-full shadow-[0_10px_24px_rgba(0,0,0,0.23)] hidden lg:block">
                    </div>
                    @if (Auth::user() != null)
                        <div class="bars lg:hidden md:flex list-none cursor-pointer text-gray-600 z-[9999]">
                            <li class="" onclick="openNavbar()" id="Show">
                                <i class="fa-solid fa-bars"></i>
                            </li>
                            <li class="hidden" onclick="hideNavbar()" id="Hide">
                                <i class="fa-solid fa-xmark"></i>
                            </li>
                        </div>
                    @endif
                    <a href="{{ route('homePage') }}" class="">
                        <!-- logo bc -->
                        <img src="{{ asset('image/logoBC.png') }}" class="w-[80px]">
                    </a>
                </div>
                @if (Auth::user() != null)
                    <!-- profile user -->
                    <li class="list-item relative md:hidden">
                        <div class="dropdown-menu">
                            <div class="toggle-menu">
                                <i class="fas fa-circle-user !text-4xl"></i>
                            </div>
                            <div
                                class="content-dropdown z-[9999] absolute bg-white border border-gray-200 shadow-lg w-[140px] rounded-lg mt-2 right-0">
                                <a href="{{ route('beranda') }}">
                                    <div
                                        class="link-href hover:bg-gray-100 hover:!text-black !flex-row items-center gap-[5px]">
                                        <i class="fa-solid fa-house"></i>
                                        Beranda
                                    </div>
                                </a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button
                                        class="link-href hover:bg-gray-100 hover:!text-black cursor-pointer w-full flex !flex-row items-center gap-2 text-start">
                                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </li>
                @endif

                @if (Auth::user() === null)
                    <div class="hidden sm:block space-x-6">
                        {{-- <x-nav-active href="{{ route('siswa') }}" :active="request()->is('siswa')">Siswa</x-nav-active>
                        <x-nav-active href="/about" :active="request()->is('sekolah')">Sekolah</x-nav-active> --}}
                        <x-nav-active href="{{ route('about') }}" :active="request()->is('about')">Tentang Kami</x-nav-active>
                        <x-nav-active href="{{ route('mitraCerdas') }}" :active="request()->is('mitra-cerdas')">Mitra Cerdas</x-nav-active>
                    </div>

                    <div class="bars md:hidden list-none cursor-pointer text-gray-600 z-[9999]">
                        <li class="" onclick="openNavbar()" id="Show">
                            <i class="fa-solid fa-bars"></i>
                        </li>
                        <li class="hidden" onclick="hideNavbar()" id="Hide">
                            <i class="fa-solid fa-xmark"></i>
                        </li>
                    </div>
                @else
                    <div class="hidden lg:block space-x-6">
                        {{-- <x-nav-active href="{{ route('siswa') }}" :active="request()->is('siswa')">Siswa</x-nav-active>
                        <x-nav-active href="/about" :active="request()->is('sekolah')">Sekolah</x-nav-active> --}}
                        <x-nav-active href="{{ route('about') }}" :active="request()->is('about')">Tentang Kami</x-nav-active>
                        <x-nav-active href="{{ route('mitraCerdas') }}" :active="request()->is('mitra-cerdas')">Mitra Cerdas</x-nav-active>
                    </div>
                @endif


                <!-- for dekstop --->
                @if (Auth::user() === null)
                    <button onclick="modalLogin(this)"
                        class="bg-[--color-default] text-white px-4 py-2 rounded-full hidden md:block">
                        Masuk / Daftar</button>
                @else
                    <div class="hidden md:block">
                        <li class="list-item">
                            <div class="dropdown-menu">
                                <div class="toggle-menu">
                                    <div class="profile justify-between bg-[--color-default]">
                                        <div class="flex items-center gap-2">
                                            <i class="fa-regular fa-circle-user !text-3xl"></i>
                                            <div class="information-profile">
                                                <span
                                                    class="name">{{ Str::limit(Auth::user()->Profile->nama_lengkap ?? '', 20) }}</span>
                                                <span
                                                    class="class">{{ Str::limit(Auth::user()->role ?? '', 20) }}</span>
                                            </div>
                                        </div>
                                        <i id="rotate" class="fas fa-chevron-down"></i>
                                    </div>
                                </div>
                                <div
                                    class="content-dropdown z-[9999] absolute bg-white border border-gray-200 shadow-lg w-[220px] rounded-lg mt-2">
                                    <a href="{{ route('beranda') }}">
                                        <div
                                            class="link-href hover:bg-gray-100 hover:!text-black !flex-row items-center gap-[5px]">
                                            <i class="fa-solid fa-house"></i>
                                            Beranda
                                        </div>
                                    </a>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button
                                            class="link-href hover:bg-gray-100 hover:!text-black cursor-pointer w-full flex !flex-row items-center gap-2 text-start">
                                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    </div>
                @endif
                <!-- navbar for mobile & dekstop  --->
                <div class="navbar-component fixed top-[90px] left-[0px] z-[999] w-full h-auto bg-white shadow-lg font-bold md:pb-0 hidden"
                    id="accordion">
                    <div class="lg:hidden">
                        <div class="item w-[250px]">
                            <ul class="header">
                                <li class="list-item w-max {{ Auth::user() === null ? 'sm:hidden' : '' }}">
                                    <div class="dropdown-menu">
                                        <div class="toggle-menu hover:bg-gray-100 w-32 h-10 pl-2">
                                            <span class="text-md">Produk Kami</span>
                                            <i class="fas fa-chevron-right" id="rotate-navbar-component"></i>
                                        </div>
                                        <div class="content-dropdown transition-all duration-500">
                                            <div class="content-dropdown">
                                                {{-- <a href="{{ route('siswa') }}"
                                                    class="link-href hover:!text-black items-start gap-[5px] !p-0 !pl-2">
                                                    <span class="hover:bg-gray-100 p-2 w-40 rounded-sm">
                                                        Siswa
                                                    </span>
                                                </a> --}}
                                                <a href="{{ route('mitraCerdas') }}"
                                                    class="link-href hover:!text-black items-start gap-[5px] !p-0 !pl-2">
                                                    <span class="hover:bg-gray-100 p-2 w-40 rounded-sm">
                                                        Mitra Cerdas
                                                    </span>
                                                </a>
                                                {{-- <a href=""
                                                    class="link-href hover:!text-black items-start gap-[5px] !p-0 !pl-2">
                                                    <span class="hover:bg-gray-100 p-2 w-40 rounded-sm">
                                                        Sekolah
                                                    </span>
                                                </a> --}}
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-item w-max {{ Auth::user() === null ? 'sm:hidden' : '' }}">
                                    <a href="{{ route('about') }}"
                                        class="link-href hover:!text-black items-start gap-[5px] !pl-0 text-sm">
                                        <span class="hover:bg-gray-100 p-2 w-40 rounded-sm">
                                            Tentang Kami
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!--- button masuk / daftar ---->
                        @if (Auth::user() === null)
                            <div class="w-full h-32 px-20 flex items-center md:hidden">
                                <button onclick="my_modal_1.showModal()"
                                    class="bg-[--color-default] text-white px-4 py-[4px] rounded-full w-full">
                                    Masuk / daftar
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @elseif(request()->routeIs('mitraCerdas'))
            <!-- element top left -->
            <div class="flex items-center justify-between w-full relative">
                <div
                    class="absolute left-[-125px] top-[-20px] w-[260px] h-[75px]
                        bg-gradient-to-br from-[#FFE588]/70 to-white
                        rounded-full rotate-[60deg] opacity-60">
                </div>
                <!-- Rounded hijau kebiruan transparan -->
                <div
                    class="absolute left-[-310px] top-[-180px] w-[680px] h-[155px]
                        bg-gradient-to-b from-[#5EF2D5]/70 to-[#5EF2D5]/70
                        rounded-full rotate-[60deg] opacity-10">
                </div>

                <div
                    class="absolute left-[-145px] top-[180px] w-[260px] h-[75px]
                        bg-gradient-to-b from-[#5EF2D5]/80 to-[#5EF2D5]/20
                        rounded-full rotate-[60deg] opacity-20">
                </div>
                <!-- element top right -->
                <div
                    class="absolute right-[-195px] top-[-180px] w-[680px] h-[155px]
                        bg-gradient-to-b from-[#5EF2D5]/70 to-[#5EF2D5]/70
                        rounded-full rotate-[60deg] opacity-10 z-[-1]">
                </div>
                <!--- for mobile --->
                @if (Auth::user() === null)
                    <a href="{{ route('homePage') }}" class="relative left-[25px]">
                        <!-- logo bc -->
                        <img src="{{ asset('image/logoBC.png') }}" class="w-[80px]">
                    </a>
                @else
                    <div class="flex items-center">
                        <!-- bars & xmark navbar mobile -->
                        <div class="bars lg:hidden md:flex list-none cursor-pointer text-gray-600 z-[9999]">
                            <li class="" onclick="openNavbar()" id="Show">
                                <i class="fa-solid fa-bars"></i>
                            </li>
                            <li class="hidden" onclick="hideNavbar()" id="Hide">
                                <i class="fa-solid fa-xmark"></i>
                            </li>
                        </div>
                        <!-- logo bc -->
                        <a href="{{ route('homePage') }}" class="relative left-[25px]">
                            <img src="{{ asset('image/logoBC.png') }}" class="w-[80px]">
                        </a>
                    </div>
                    <!-- profile user -->
                    <li class="list-item relative md:hidden">
                        <div class="dropdown-menu">
                            <div class="toggle-menu">
                                <i class="fas fa-circle-user !text-4xl"></i>
                            </div>
                            <div
                                class="content-dropdown z-[9999] absolute bg-white border border-gray-200 shadow-lg w-[140px] rounded-lg mt-2 right-0">
                                <a href="{{ route('beranda') }}">
                                    <div
                                        class="link-href hover:bg-gray-100 hover:!text-black !flex-row items-center gap-[5px]">
                                        <i class="fa-solid fa-house"></i>
                                        Beranda
                                    </div>
                                </a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button
                                        class="link-href hover:bg-gray-100 hover:!text-black cursor-pointer w-full flex !flex-row items-center gap-2 text-start">
                                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </li>
                @endif

                @if (Auth::user() === null)
                    <div class="hidden sm:block space-x-6">
                        {{-- <x-nav-active href="{{ route('siswa') }}" :active="request()->is('siswa')">Siswa</x-nav-active>
                        <x-nav-active href="/about" :active="request()->is('sekolah')">Sekolah</x-nav-active> --}}
                        <x-nav-active href="{{ route('about') }}" :active="request()->is('about')">Tentang Kami</x-nav-active>
                        <x-nav-active href="{{ route('mitraCerdas') }}" :active="request()->is('mitra-cerdas')">Mitra Cerdas</x-nav-active>
                    </div>

                    <div class="bars md:hidden list-none cursor-pointer text-gray-600 z-[9999]">
                        <li class="" onclick="openNavbar()" id="Show">
                            <i class="fa-solid fa-bars"></i>
                        </li>
                        <li class="hidden" onclick="hideNavbar()" id="Hide">
                            <i class="fa-solid fa-xmark"></i>
                        </li>
                    </div>
                @else
                    <div class="hidden lg:block space-x-6">
                        {{-- <x-nav-active href="{{ route('siswa') }}" :active="request()->is('siswa')">Siswa</x-nav-active>
                        <x-nav-active href="/about" :active="request()->is('sekolah')">Sekolah</x-nav-active> --}}
                        <x-nav-active href="{{ route('about') }}" :active="request()->is('about')">Tentang Kami</x-nav-active>
                        <x-nav-active href="{{ route('mitraCerdas') }}" :active="request()->is('mitra-cerdas')">Mitra Cerdas</x-nav-active>
                    </div>
                @endif


                <!-- for dekstop --->
                @if (Auth::user() === null)
                    <button onclick="modalLogin(this)"
                        class="bg-[--color-default] text-white px-4 py-2 rounded-full hidden md:block">
                        Masuk / Daftar
                    </button>
                @else
                    <div class="hidden md:block">
                        <li class="list-item">
                            <div class="dropdown-menu">
                                <div class="toggle-menu">
                                    <div class="profile justify-between bg-[--color-default]">
                                        <div class="flex items-center gap-2">
                                            <i class="fa-regular fa-circle-user !text-3xl"></i>
                                            <div class="information-profile">
                                                <span
                                                    class="name">{{ Str::limit(Auth::user()->Profile->nama_lengkap ?? '', 20) }}</span>
                                                <span
                                                    class="class">{{ Str::limit(Auth::user()->role ?? '', 20) }}</span>
                                            </div>
                                        </div>
                                        <i id="rotate" class="fas fa-chevron-down"></i>
                                    </div>
                                </div>
                                <div
                                    class="content-dropdown z-[9999] absolute bg-white border border-gray-200 shadow-lg w-[220px] rounded-lg mt-2">
                                    <a href="{{ route('beranda') }}">
                                        <div
                                            class="link-href hover:bg-gray-100 hover:!text-black !flex-row items-center gap-[5px]">
                                            <i class="fa-solid fa-house"></i>
                                            Beranda
                                        </div>
                                    </a>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button
                                            class="link-href hover:bg-gray-100 hover:!text-black cursor-pointer w-full flex !flex-row items-center gap-2 text-start">
                                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    </div>
                @endif
                <!-- navbar for mobile & dekstop  --->
                <div class="navbar-component fixed top-[90px] left-[0px] z-[999] w-full h-auto bg-white shadow-lg font-bold md:pb-0 hidden"
                    id="accordion">
                    <div class="lg:hidden">
                        <div class="item w-[250px]">
                            <ul class="header">
                                <li class="list-item w-max {{ Auth::user() === null ? 'sm:hidden' : '' }}">
                                    <div class="dropdown-menu">
                                        <div class="toggle-menu hover:bg-gray-100 w-32 h-10 pl-2">
                                            <span class="text-md">Produk Kami</span>
                                            <i class="fas fa-chevron-right" id="rotate-navbar-component"></i>
                                        </div>
                                        <div class="content-dropdown transition-all duration-500">
                                            <div class="content-dropdown">
                                                {{-- <a href="{{ route('siswa') }}"
                                                    class="link-href hover:!text-black items-start gap-[5px] !p-0 !pl-2">
                                                    <span class="hover:bg-gray-100 p-2 w-40 rounded-sm">
                                                        Siswa
                                                    </span>
                                                </a> --}}
                                                <a href="{{ route('mitraCerdas') }}"
                                                    class="link-href hover:!text-black items-start gap-[5px] !p-0 !pl-2">
                                                    <span class="hover:bg-gray-100 p-2 w-40 rounded-sm">
                                                        Mitra Cerdas
                                                    </span>
                                                </a>
                                                {{-- <a href=""
                                                    class="link-href hover:!text-black items-start gap-[5px] !p-0 !pl-2">
                                                    <span class="hover:bg-gray-100 p-2 w-40 rounded-sm">
                                                        Sekolah
                                                    </span>
                                                </a> --}}
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-item w-max {{ Auth::user() === null ? 'sm:hidden' : '' }}">
                                    <a href="{{ route('about') }}"
                                        class="link-href hover:!text-black items-start gap-[5px] !pl-0 text-sm">
                                        <span class="hover:bg-gray-100 p-2 w-40 rounded-sm">
                                            Tentang Kami
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!--- button masuk / daftar ---->
                        @if (Auth::user() === null)
                            <div class="w-full h-32 px-20 flex items-center md:hidden">
                                <button onclick="my_modal_1.showModal()"
                                    class="bg-[--color-default] text-white px-4 py-[4px] rounded-full w-full">
                                    Masuk / daftar
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!---- navbar untuk selain yang ditentukan ---->
        @else
            <!-- element top left -->
            <div class="flex items-center justify-between w-full">
                <!--- for mobile --->
                <div class="flex items-center relative gap-12">
                    @if (Auth::user() != null)
                        <div class="bars lg:hidden md:flex list-none cursor-pointer text-gray-600 z-[9999]">
                            <li class="" onclick="openNavbar()" id="Show">
                                <i class="fa-solid fa-bars"></i>
                            </li>
                            <li class="hidden" onclick="hideNavbar()" id="Hide">
                                <i class="fa-solid fa-xmark"></i>
                            </li>
                        </div>
                    @endif
                    <a href="{{ route('homePage') }}" class="">
                        <!-- logo bc -->
                        <img src="{{ asset('image/logoBC.png') }}" class="w-[80px]">
                    </a>
                </div>
                @if (Auth::user() != null)
                    <!-- profile user -->
                    <li class="list-item relative md:hidden">
                        <div class="dropdown-menu">
                            <div class="toggle-menu">
                                <i class="fas fa-circle-user !text-4xl"></i>
                            </div>
                            <div
                                class="content-dropdown z-[9999] absolute bg-white border border-gray-200 shadow-lg w-[140px] rounded-lg mt-2 right-0">
                                <a href="{{ route('beranda') }}">
                                    <div
                                        class="link-href hover:bg-gray-100 hover:!text-black !flex-row items-center gap-[5px]">
                                        <i class="fa-solid fa-house"></i>
                                        Beranda
                                    </div>
                                </a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button
                                        class="link-href hover:bg-gray-100 hover:!text-black cursor-pointer w-full flex !flex-row items-center gap-2 text-start">
                                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </li>
                @endif

                @if (Auth::user() === null)
                    <div class="hidden sm:block space-x-6">
                        {{-- <x-nav-active href="{{ route('siswa') }}" :active="request()->is('siswa')">Siswa</x-nav-active>
                        <x-nav-active href="/about" :active="request()->is('sekolah')">Sekolah</x-nav-active> --}}
                        <x-nav-active href="{{ route('about') }}" :active="request()->is('about')">Tentang Kami</x-nav-active>
                        <x-nav-active href="{{ route('mitraCerdas') }}" :active="request()->is('mitra-cerdas')">Mitra Cerdas</x-nav-active>
                    </div>

                    <div class="bars md:hidden list-none cursor-pointer text-gray-600 z-[9999]">
                        <li class="" onclick="openNavbar()" id="Show">
                            <i class="fa-solid fa-bars"></i>
                        </li>
                        <li class="hidden" onclick="hideNavbar()" id="Hide">
                            <i class="fa-solid fa-xmark"></i>
                        </li>
                    </div>
                @else
                    <div class="hidden lg:block space-x-6">
                        {{-- <x-nav-active href="{{ route('siswa') }}" :active="request()->is('siswa')">Siswa</x-nav-active>
                        <x-nav-active href="/about" :active="request()->is('sekolah')">Sekolah</x-nav-active> --}}
                        <x-nav-active href="{{ route('about') }}" :active="request()->is('about')">Tentang Kami</x-nav-active>
                        <x-nav-active href="{{ route('mitraCerdas') }}" :active="request()->is('mitra-cerdas')">Mitra Cerdas</x-nav-active>
                    </div>
                @endif


                <!-- for dekstop --->
                @if (Auth::user() === null)
                    <button onclick="modalLogin(this)"
                        class="bg-[--color-default] text-white px-4 py-2 rounded-full hidden md:block">Masuk /
                        Daftar</button>
                @else
                    <div class="hidden md:block">
                        <li class="list-item">
                            <div class="dropdown-menu">
                                <div class="toggle-menu">
                                    <div class="profile justify-between bg-[--color-default]">
                                        <div class="flex items-center gap-2">
                                            <i class="fa-regular fa-circle-user !text-3xl"></i>
                                            <div class="information-profile">
                                                <span
                                                    class="name">{{ Str::limit(Auth::user()->Profile->nama_lengkap ?? '', 20) }}</span>
                                                <span
                                                    class="class">{{ Str::limit(Auth::user()->role ?? '', 20) }}</span>
                                            </div>
                                        </div>
                                        <i id="rotate" class="fas fa-chevron-down"></i>
                                    </div>
                                </div>
                                <div
                                    class="content-dropdown z-[9999] absolute bg-white border border-gray-200 shadow-lg w-[220px] rounded-lg mt-2">
                                    <a href="{{ route('beranda') }}">
                                        <div
                                            class="link-href hover:bg-gray-100 hover:!text-black !flex-row items-center gap-[5px]">
                                            <i class="fa-solid fa-house"></i>
                                            Beranda
                                        </div>
                                    </a>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button
                                            class="link-href hover:bg-gray-100 hover:!text-black cursor-pointer w-full flex !flex-row items-center gap-2 text-start">
                                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    </div>
                @endif
                <!-- navbar for mobile & dekstop  --->
                <div class="navbar-component fixed top-[90px] left-[0px] z-[999] w-full h-auto bg-white shadow-lg font-bold md:pb-0 hidden"
                    id="accordion">
                    <div class="lg:hidden">
                        <div class="item w-[250px]">
                            <ul class="header">
                                <li class="list-item w-max {{ Auth::user() === null ? 'sm:hidden' : '' }}">
                                    <div class="dropdown-menu">
                                        <div class="toggle-menu hover:bg-gray-100 w-32 h-10 pl-2">
                                            <span class="text-md">Produk Kami</span>
                                            <i class="fas fa-chevron-right" id="rotate-navbar-component"></i>
                                        </div>
                                        <div class="content-dropdown transition-all duration-500">
                                            <div class="content-dropdown">
                                                {{-- <a href="{{ route('siswa') }}"
                                                    class="link-href hover:!text-black items-start gap-[5px] !p-0 !pl-2">
                                                    <span class="hover:bg-gray-100 p-2 w-40 rounded-sm">
                                                        Siswa
                                                    </span>
                                                </a> --}}
                                                <a href="{{ route('mitraCerdas') }}"
                                                    class="link-href hover:!text-black items-start gap-[5px] !p-0 !pl-2">
                                                    <span class="hover:bg-gray-100 p-2 w-40 rounded-sm">
                                                        Mitra Cerdas
                                                    </span>
                                                </a>
                                                {{-- <a href=""
                                                    class="link-href hover:!text-black items-start gap-[5px] !p-0 !pl-2">
                                                    <span class="hover:bg-gray-100 p-2 w-40 rounded-sm">
                                                        Sekolah
                                                    </span>
                                                </a> --}}
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-item w-max {{ Auth::user() === null ? 'sm:hidden' : '' }}">
                                    <a href="{{ route('about') }}"
                                        class="link-href hover:!text-black items-start gap-[5px] !pl-0 text-sm">
                                        <span class="hover:bg-gray-100 p-2 w-40 rounded-sm">
                                            Tentang Kami
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!--- button masuk / daftar ---->
                        @if (Auth::user() === null)
                            <div class="w-full h-32 px-20 flex items-center md:hidden">
                                <button onclick="my_modal_1.showModal()"
                                    class="bg-[--color-default] text-white px-4 py-[4px] rounded-full w-full">
                                    Masuk / daftar
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!--- modal login --->
        <dialog id="my_modal_1" class="modal">
            <div class="modal-box bg-white !p-0 w-[80%] md:w-[450px] h-max">

                <!-- untuk menghilangkan focus input type pada saat open modal  --->
                <div tabindex="-1"></div> <!-- Tambahkan ini -->

                <div class="aspect-[8/3] w-full flex items-center justify-center bg-gray-100 rounded-md">
                    <img src="{{ asset('image/paket4.jpg') }}" alt="Gambar Paket" class="w-full h-full" />
                </div>
                <span class="font-bold text-lg text-gray-800 flex justify-center mt-4 mb-2">Selamat Datang!</span>

                @if (session('alert-error-login'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 my-2 h-max mx-6">
                        <p class="font-bold">{{ session('alert-error-login') }}</p>
                    </div>
                @endif

                <div class="px-6">
                    <form action="{{ route('auth.login') }}" method="POST" autocomplete="OFF">
                        @csrf
                        <div class="w-full mb-6">
                            <label class="text-sm">Email</label>
                            <input type="text" name="email" id="email" placeholder="Masukkan Email"
                                class="w-full bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md text-sm px-2 mt-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] {{ $errors->has('email') ? 'border-[1px] border-red-500' : '' }}"
                                value="{{ @old('email') }}">
                            @error('email')
                                <span
                                    class="text-red-500 font-bold text-xs pt-2 flex flex-start">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="w-full mb-6">
                            <label class="text-sm">Password</label>
                            <input type="password" name="password" placeholder="Masukkan Password"
                                class="w-full bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md text-sm px-2 mt-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] {{ $errors->has('password') ? 'border-[1px] border-red-500' : '' }}"
                                value="{{ @old('password') }}">
                            @error('password')
                                <span
                                    class="text-red-500 font-bold text-xs pt-2 flex flex-start">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit"
                            class="bg-[#4179e0] text-white w-full mt-2 h-10 rounded-lg font-semibold hover:bg-[#4189e0]">
                            Masuk
                        </button>
                    </form>
                    <div class="text-sm mb-6 mt-4 flex justify-center gap-[3px]">
                        Belum punya akun?
                        <a href="{{ route('daftar.user') }}"
                            class="text-blue-500 font-semibold hover:underline">Daftar
                            sekarang
                        </a>
                    </div>
                </div>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>
    </div>
</nav>

<script src="{{ asset('js/components/navbar-button-profile-user.js') }}"></script> <!-- button profile user in navbar -->
<script src="{{ asset('js/components/modal-login.js') }}"></script> <!-- modal login -->
<script src="{{ asset('js/components/navbar-accordion.js') }}"></script> <!-- navbar open & accordion open -->

@if ($errors->any() || session('alert-error-login'))
    <!-- untuk menampilkan modal kembali jika terjadi erorr pada modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ensure the modal is displayed if there are validation errors
            document.getElementById('my_modal_1').showModal();
        });
    </script>
@endif
