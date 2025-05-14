<x-script>

</x-script>

<nav class="mb-40">
    <div class="flex justify-between items-center h-24 px-7 fixed w-full bg-white top-0">
        <!-- navbar elements --->
        @if (request()->routeIs('homePage'))
            <!-- element top left -->
            <div class="flex items-center justify-between w-full">
                <!--- for mobile --->
                <div class="flex items-center relative lg:left-[-98px] gap-12">
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
                        <img src="image/logoBC.png" class="w-[80px]">
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
                        <x-nav-active href="{{ route('siswa') }}" :active="request()->is('siswa')">Siswa</x-nav-active>
                        <x-nav-active href="{{ route('mitraCerdas') }}" :active="request()->is('mitra-cerdas')">Mitra Cerdas</x-nav-active>
                        <x-nav-active href="/about" :active="request()->is('sekolah')">Sekolah</x-nav-active>
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
                        <x-nav-active href="{{ route('siswa') }}" :active="request()->is('siswa')">Siswa</x-nav-active>
                        <x-nav-active href="{{ route('mitraCerdas') }}" :active="request()->is('mitra-cerdas')">Mitra Cerdas</x-nav-active>
                        <x-nav-active href="/about" :active="request()->is('sekolah')">Sekolah</x-nav-active>
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
                            <div class="header">
                                <li class="list-item w-max {{ Auth::user() === null ? 'sm:hidden' : '' }}">
                                    <div class="dropdown-menu">
                                        <div class="toggle-menu hover:bg-gray-100 w-32 h-10 pl-2">
                                            <span class="text-md">Produk Kami</span>
                                            <i class="fas fa-chevron-right" id="rotate-navbar-component"></i>
                                        </div>
                                        <div class="content-dropdown transition-all duration-500">
                                            <div class="content-dropdown">
                                                <a href="{{ route('siswa') }}"
                                                    class="link-href hover:!text-black items-start gap-[5px] !p-0 !pl-2">
                                                    <span class="hover:bg-gray-100 p-2 w-40 rounded-sm">
                                                        Siswa
                                                    </span>
                                                </a>
                                                <a href="{{ route('mitraCerdas') }}"
                                                    class="link-href hover:!text-black items-start gap-[5px] !p-0 !pl-2">
                                                    <span class="hover:bg-gray-100 p-2 w-40 rounded-sm">
                                                        Mitra Cerdas
                                                    </span>
                                                </a>
                                                <a href=""
                                                    class="link-href hover:!text-black items-start gap-[5px] !p-0 !pl-2">
                                                    <span class="hover:bg-gray-100 p-2 w-40 rounded-sm">
                                                        Sekolah
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </div>
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
            <div class="flex items-center justify-between w-full">
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
                        <img src="image/logoBC.png" class="w-[80px]">
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
                            <img src="image/logoBC.png" class="w-[80px]">
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
                        <x-nav-active href="{{ route('siswa') }}" :active="request()->is('siswa')">Siswa</x-nav-active>
                        <x-nav-active href="{{ route('mitraCerdas') }}" :active="request()->is('mitra-cerdas')">Mitra Cerdas</x-nav-active>
                        <x-nav-active href="/about" :active="request()->is('sekolah')">Sekolah</x-nav-active>
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
                        <x-nav-active href="{{ route('siswa') }}" :active="request()->is('siswa')">Siswa</x-nav-active>
                        <x-nav-active href="{{ route('mitraCerdas') }}" :active="request()->is('mitra-cerdas')">Mitra Cerdas</x-nav-active>
                        <x-nav-active href="/about" :active="request()->is('sekolah')">Sekolah</x-nav-active>
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
                            <div class="header">
                                <li class="list-item w-max {{ Auth::user() === null ? 'sm:hidden' : '' }}">
                                    <div class="dropdown-menu">
                                        <div class="toggle-menu hover:bg-gray-100 w-32 h-10 pl-2">
                                            <span class="text-md">Produk Kami</span>
                                            <i class="fas fa-chevron-right" id="rotate-navbar-component"></i>
                                        </div>
                                        <div class="content-dropdown transition-all duration-500">
                                            <div class="content-dropdown">
                                                <a href="{{ route('siswa') }}"
                                                    class="link-href hover:!text-black items-start gap-[5px] !p-0 !pl-2">
                                                    <span class="hover:bg-gray-100 p-2 w-40 rounded-sm">
                                                        Siswa
                                                    </span>
                                                </a>
                                                <a href="{{ route('mitraCerdas') }}"
                                                    class="link-href hover:!text-black items-start gap-[5px] !p-0 !pl-2">
                                                    <span class="hover:bg-gray-100 p-2 w-40 rounded-sm">
                                                        Mitra Cerdas
                                                    </span>
                                                </a>
                                                <a href=""
                                                    class="link-href hover:!text-black items-start gap-[5px] !p-0 !pl-2">
                                                    <span class="hover:bg-gray-100 p-2 w-40 rounded-sm">
                                                        Sekolah
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </div>
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
                        <img src="image/logoBC.png" class="w-[80px]">
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
                        <x-nav-active href="{{ route('siswa') }}" :active="request()->is('siswa')">Siswa</x-nav-active>
                        <x-nav-active href="{{ route('mitraCerdas') }}" :active="request()->is('mitra-cerdas')">Mitra Cerdas</x-nav-active>
                        <x-nav-active href="/about" :active="request()->is('sekolah')">Sekolah</x-nav-active>
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
                        <x-nav-active href="{{ route('siswa') }}" :active="request()->is('siswa')">Siswa</x-nav-active>
                        <x-nav-active href="{{ route('mitraCerdas') }}" :active="request()->is('mitra-cerdas')">Mitra Cerdas</x-nav-active>
                        <x-nav-active href="/about" :active="request()->is('sekolah')">Sekolah</x-nav-active>
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
                            <div class="header">
                                <li class="list-item w-max {{ Auth::user() === null ? 'sm:hidden' : '' }}">
                                    <div class="dropdown-menu">
                                        <div class="toggle-menu hover:bg-gray-100 w-32 h-10 pl-2">
                                            <span class="text-md">Produk Kami</span>
                                            <i class="fas fa-chevron-right" id="rotate-navbar-component"></i>
                                        </div>
                                        <div class="content-dropdown transition-all duration-500">
                                            <div class="content-dropdown">
                                                <a href="{{ route('siswa') }}"
                                                    class="link-href hover:!text-black items-start gap-[5px] !p-0 !pl-2">
                                                    <span class="hover:bg-gray-100 p-2 w-40 rounded-sm">
                                                        Siswa
                                                    </span>
                                                </a>
                                                <a href="{{ route('mitraCerdas') }}"
                                                    class="link-href hover:!text-black items-start gap-[5px] !p-0 !pl-2">
                                                    <span class="hover:bg-gray-100 p-2 w-40 rounded-sm">
                                                        Mitra Cerdas
                                                    </span>
                                                </a>
                                                <a href=""
                                                    class="link-href hover:!text-black items-start gap-[5px] !p-0 !pl-2">
                                                    <span class="hover:bg-gray-100 p-2 w-40 rounded-sm">
                                                        Sekolah
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </div>
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
                    <form action="{{ route('auth.login') }}" method="POST">
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

<script>
    function modalLogin(element, dummyId) {
        const modal = document.getElementById('my_modal_1');
        const input = document.getElementById('email');


        modal.showModal();
        // Alihkan fokus agar browser tidak auto-focus ke input pertama
        setTimeout(() => {
            dummy?.focus();
        }, 1); // Delay supaya browser sempat jalanin autofokus-nya dulu
    }
</script>

<script>
    document.getElementById('submit').addEventListener('click', function() {
        const form = document.querySelector('#my_modal_1 form[action]');
        form.submit(); // Submit the form
    });
</script>

@if ($errors->any() || session('alert-error-login'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ensure the modal is displayed if there are validation errors
            document.getElementById('my_modal_1').showModal();
        });
    </script>
@endif


{{-- <header class="mb-10">
    <main>
        <section class="relative flex justify-between items-center h-24 px-10">
            @if (request()->routeIs('homePage'))
                <div class="flex items-center">
                    <!-- rounded top left -->
                    <div
                        class="relative left-[-98px] top-[-20px] h-32 w-32 bg-white rounded-full shadow-[0_10px_24px_rgba(0,0,0,0.23)]">
                    </div>
                    <a href="{{ route('homePage') }}" class="relative left-[-15px]">
                        <!-- logo bc untuk dekstop -->
                        <img src="image/logo-bc/asset logo landing page.png" class="w-[240px] hidden md:block">
                        <!-- logo bc untuk mobile -->
                        <img src="image/logoBC.png" class="w-[80px] md:hidden">
                    </a>
                </div>
            @elseif(request()->routeIs('mitraCerdas'))
                <!-- element top left -->
                <div class="flex items-center">
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
                        rounded-full rotate-[60deg] opacity-10">
                    </div>
                    <i class="fa-solid fa-bars text-gray-600 z-[9999]"></i>
                    <a href="{{ route('homePage') }}" class="relative left-[25px]">
                        <!-- logo bc untuk dekstop -->
                        <img src="image/logo-bc/asset logo landing page.png" class="w-[240px] hidden md:block">
                        <!-- logo bc untuk mobile -->
                        <img src="image/logoBC.png" class="w-[80px] md:hidden">
                    </a>
                </div>
            @else
                <div class="flex items-center">
                    <a href="{{ route('homePage') }}">
                        <!-- logo bc untuk dekstop -->
                        <img src="image/logo-bc/asset logo landing page.png" class="w-[240px] hidden md:block">
                        <!-- logo bc untuk mobile -->
                        <img src="image/logoBC.png" class="w-[80px] md:hidden">
                    </a>
                </div>
            @endif

            <nav class="hidden md:flex space-x-6">
                <x-nav-active href="/about" :active="request()->is('murid')">Murid</x-nav-active>
                <x-nav-active href="{{ route('mitraCerdas') }}" :active="request()->is('mitra-cerdas')">Mitra Cerdas</x-nav-active>
                <x-nav-active href="/about" :active="request()->is('sekolah')">Sekolah</x-nav-active>
            </nav>
            <div class="flex items-center space-x-3">
                {{-- <label class="input border-none bg-[#ededed] rounded-full w-max h-8 hidden lg:flex items-center">
                    <input type="search" required placeholder="Search..."
                        class="ml-2 text-sm placeholder-gray-400 placeholder:italic text-right" />
                    <svg class="h-[1.5em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <defs>
                            <!-- Gradient dari atas ke bawah -->
                            <linearGradient id="gradientColor" x1="0%" y1="0%" x2="0%"
                                y2="100%">
                                <stop offset="0%" stop-color="#60B5FF" />
                                <stop offset="50%" stop-color="#60B5FF" />
                                <stop offset="50%" stop-color="#5EF2D5" />
                                <stop offset="100%" stop-color="#5EF2D5" />
                            </linearGradient>
                        </defs>
                        <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none">
                            <circle cx="11" cy="11" r="8" stroke="url(#gradientColor)"></circle>
                            <path d="M21 21l-4.3-4.3" stroke="#5EF2D5"></path>
                        </g>
                    </svg>
                </label> --}
                @if (session('user') == null)
                    <a href="{{ route('login') }}" class="hidden md:block">
                        <button class="bg-[--color-default] text-white px-4 py-2 rounded-full">Masuk / Daftar</button>
                    </a>
                @else
                    <li class="list-item">
                        <div class="dropdown-menu">
                            <div class="toggle-menu">
                                <div class="profile justify-between bg-[--color-default]">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-regular fa-circle-user !text-3xl"></i>
                                        <div class="information-profile">
                                            <span
                                                class="name">{{ Str::limit(session('user')->nama_lengkap ?? '', 20) }}</span>
                                            <span
                                                class="class">{{ Str::limit(session('user')->status ?? '', 20) }}</span>
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
                @endif
            </div>

        </section>
    </main>
</header> --}}

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const toggles = document.querySelectorAll(".toggle-menu"); // Dropdown utama
        const toggles2 = document.querySelectorAll(".toggle-menu2"); // Sub-dropdown

        // Fungsi untuk menutup semua dropdown
        function closeAllSubDropdowns(except = null) {
            document.querySelectorAll(".content-dropdown").forEach(dropdown => {
                if (dropdown !== except) {
                    dropdown.classList.remove("show");
                }
            });
        }

        function closeAllListDropdowns(except = null) {
            document.querySelectorAll(".list-content-dropdown").forEach(dropdown => {
                if (dropdown !== except) {
                    dropdown.classList.remove("show");
                }
            });
        }

        // Event listener untuk dropdown utama
        toggles.forEach(toggle => {
            toggle.addEventListener("click", () => {
                const parent = toggle.closest('.list-item'); // Cari elemen utama

                // Toggle dropdown utama
                parent.classList.toggle("show");

                // Tutup dropdown lain yang tidak diklik
                document.querySelectorAll(".list-item").forEach(dropdown => {
                    if (dropdown !== parent) {
                        dropdown.classList.remove("show");
                    }
                });

                // Tutup semua sub-dropdown saat dropdown utama berubah
                closeAllSubDropdowns();
            });
        });

        // Event listener untuk sub-dropdown (toggle-menu2)
        toggles2.forEach(toggle => {
            toggle.addEventListener("click", () => {
                const parent = toggle.closest('.content-dropdown'); // Cari elemen sub-dropdown

                if (parent.classList.contains("show")) {
                    // Jika sudah terbuka, tutup
                    parent.classList.remove("show");
                } else {
                    // Jika belum terbuka, tutup yang lain lalu buka yang ini
                    closeAllSubDropdowns();
                    closeAllListDropdowns();
                    parent.classList.add("show");
                }
            });
        });
    });
</script>

<script>
    let items = document.querySelectorAll('#accordion .item .header');
    let lastClickedItem = null; // Variabel untuk melacak item terakhir yang diklik

    items.forEach((item) => {
        item.addEventListener("click", (e) => {
            const clickedItem = e.currentTarget.closest(".item");

            // Jika item yang diklik adalah item terakhir yang diklik, maka toggle class active
            if (lastClickedItem === clickedItem) {
                // Hapus active class dari item terakhir yang diklik
                clickedItem.classList.remove('active');
                lastClickedItem = null; // Reset variabel item terakhir yang diklik
            } else {
                // Hapus active class dari semua item
                items.forEach((header) => {
                    header.closest('.item').classList.remove('active');
                });

                // Tambahkan active class ke item yang diklik
                clickedItem.classList.add('active');
                lastClickedItem = clickedItem; // Update variabel item terakhir yang diklik
            }
        });
    });
</script>

<script>
    var navbar = document.querySelector('.navbar-component');
    var open = document.getElementById('Show');
    var close = document.getElementById('Hide');

    function openNavbar() {
        navbar.style.display = "block";
        open.style.display = "none";
        close.style.display = "block";
    }

    function hideNavbar() {
        close.style.display = "none";
        open.style.display = "block";
        navbar.style.display = "none";
    }
</script>

<nav>
    {{-- <div class="navbar fixed z-[999] bg-white shadow-lg text-[#60b5cf] font-bold gap-4 flex justify-between px-10">
        <a href="/" class="">
            <img src="image/logoBC.png" class="w-[80px]">
        </a>

        <div class="NavLink gap-2 hidden md:flex">
            <x-nav-active href="/murid" :active="request()->is('murid')">Murid</x-nav-active>
            <x-nav-active href="/guru" :active="request()->is('guru')">Guru</x-nav-active>
            <x-nav-active href="/sekolah" :active="request()->is('sekolah')">Sekolah</x-nav-active>
            <x-nav-active href="/post" :active="request()->is('post')">Post</x-nav-active>
            <x-nav-active href="/about" :active="request()->is('about')">About</x-nav-active>
        </div>

        <div class="auth gap-6 lg:flex md:hidden sm:hidden hidden">
            <a href="daftar">
                <button class="bg-[#60b5cf] px-8 py-2 rounded-lg text-white text-sm font-bold">Daftar</button>
            </a>
            <a href="/login">
                <button class="bg-[#60b5cf] px-8 py-2 rounded-lg text-white text-sm font-bold">Login</button>
            </a>
        </div>

        <ul class="bars lg:hidden md:flex px-6 list-none cursor-pointer">
            <li class="" onclick="openSidebar()" id="Show"><i class="fa-solid fa-bars"></i></li>
            <li class="hidden" onclick="hideSidebar()" id="Hide"><i class="fa-solid fa-xmark"></i></li>
        </ul>
    </div> --}}


    {{-- <div class="Sidebar fixed top-[64px] left-[0px] z-[999] w-full h-auto bg-white shadow-lg font-bold pb-8 md:pb-0 hidden"
        id="accordion">
        <div class="item w-[250px] m-2 p-2 lg:hidden">
            <div class="header p-2 px-2 flex items-center rounded-xl text-md text-slate-500 gap-4 cursor-pointer">
                <div class="">
                    <span>Product</span>
                    {{-- <span>Overview</span> --}
    </div>
    <ul class="relative ... text-md">
        <li class="nonIcon"><i class="fa-solid fa-chevron-right"></i></li>
        <li class="activeIcon"><i class="fa-solid fa-chevron-down"></i></li>
    </ul>
    </div>
    <div class="content transition-all duration-500 text-slate-500">
        <ul>
            <li><span>Murid</span></li>
            <li><span>Guru</span></li>
            <li><span>Sekolah</span></li>
        </ul>
    </div>
    </div>
    <div class="Login text-slate-500 p-2 px-6 flex gap-4 md:h-20 lg:hidden">
        <a href="/daftar">
            <button>Daftar</button>
        </a>
        <a href="/login">
            <button>Login</button>
        </a>
    </div>
    </div> --}}
</nav>

</html>
