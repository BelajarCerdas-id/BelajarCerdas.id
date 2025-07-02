<x-script></x-script>

@if (Auth::user()->role === 'Siswa')
    <!--- Sidebar Beranda for dekstop ---->
    <aside class="sidebar-beranda hidden md:block">
        <div class="logo_details flex items-center justify-center">
            <a href="/">
                <img src="{{ asset('image/logoBC.png') }}" alt="" class="w-2/5">
            </a>
        </div>
        <ul class="nav-list">
            <li class="list-menu">
                <a href="{{ route('beranda') }}">
                    <i class="fa-solid fa-home"></i>
                    <span class="link_name">Beranda</span>
                </a>
                <span class="tooltip">Beranda</span>
            </li>
            {{-- <li class="menu-murid">
                <a href="#">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span class="link_name">Presensi Harian</span>
                </a>
                <span class="tooltip">Presensi Harian</span>
            </li> --}}
            <li class="list-menu">
                <a href="{{ route('historiPembelian.index') }}">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="link_name">Riwayat Pembelian</span>
                </a>
                <span class="tooltip">Riwayat Pembelian</span>
            </li>

            <li class="list-menu">
                <a href="{{ route('historiKoin.index') }}">
                    <i class="fa-solid fa-coins"></i>
                    <span class="link_name">Riwayat Koin</span>
                </a>
                <span class="tooltip">Riwayat Koin</span>
            </li>
            <div class="hideSidebar cursor-pointer" id="close">
                <i class="fa-solid fa-chevron-left" id="log_outt"></i>
                <span>Sembunyikan</span>
            </div>
        </ul>
    </aside>
    <nav class="home-beranda hidden md:block">
        <div class="content">
            {{-- Navbar for PC --}}
            <div class="navbar-beranda">
                <header>
                    @if (isset($linkBackButton))
                        <a href="{{ $linkBackButton }}">
                            @if (isset($backButton))
                                <button class="font-bold text-[#4189e0] text-lg">{!! $backButton !!}</button>
                                {{ $headerSideNav ?? '' }}
                            @endif
                        </a>
                    @else
                        @if (isset($backButton))
                            <button class="font-bold text-[#4189e0] text-lg">{!! $backButton !!}</button>
                        @endif
                        {{ $headerSideNav ?? '' }}
                    @endif
                </header>
                <div class="flex lg:gap-4">
                    <!--- notification & coin --->
                    <div class="information-account">
                        {{-- <div class="notification">
                            <i class="fa-solid fa-bell"></i>
                        </div> --}}
                        <div class="coin flex items-center gap-[4px]">
                            <img src="{{ asset('image/koin.png') }}" alt=""
                                class="w-[25px] pointer-events-none">
                            <span id="jumlahKoin" class="text-lg text-gray-600 font-bold opacity-60">
                                {{ Auth::user()->TanyaUserCoin->jumlah_koin ?? 0 }}
                            </span>
                        </div>
                    </div>
                    <!--- profile button dekstop --->
                    <div class="list-item">
                        <div class="dropdown-menu hidden lg:block">
                            <div class="toggle-menu-sidebar">
                                <div class="profile justify-between bg-[--color-second]">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-regular fa-circle-user !text-3xl"></i>
                                        <div class="information-profile">
                                            <span
                                                class="name">{{ Str::limit(Auth::user()->Profile->nama_lengkap ?? '', 20) }}</span>
                                            <span class="class">{{ Str::limit(Auth::user()->role ?? '', 20) }}</span>
                                        </div>
                                    </div>
                                    <i id="rotate" class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                            <div
                                class="content-dropdown !z-[9999] absolute bg-white border border-gray-200 shadow-lg w-[220px] rounded-lg mt-2">
                                <a href="{{ route('beranda') }}">
                                    <div
                                        class="link-href hover:bg-gray-100 hover:!text-black !flex-row items-center gap-[5px]">
                                        <i class="fa-solid fa-house text-md"></i>
                                        Beranda
                                    </div>
                                </a>
                                <a href="{{ route('profile') }}">
                                    <div
                                        class="link-href hover:bg-gray-100 hover:!text-black !flex-row items-center gap-[5px]">
                                        <i class="fa-regular fa-circle-user text-lg"></i>
                                        Profile
                                    </div>
                                </a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button
                                        class="link-href hover:bg-gray-100 hover:!text-black cursor-pointer w-full flex !flex-row items-center gap-2 text-start">
                                        <i class="fa-solid fa-arrow-right-from-bracket text-md ml-[3px]"></i>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- profile button rounded mobile -->
                    <li class="list-item relative lg:hidden">
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
                                <a href="{{ route('profile') }}">
                                    <div
                                        class="link-href hover:bg-gray-100 hover:!text-black !flex-row items-center gap-[5px]">
                                        <i class="fa-regular fa-circle-user text-lg"></i>
                                        Profile
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
            </div>
        </div>
    </nav>
    <!--- Sidebar Beranda for mobile ---->
    <div class="navbar-beranda-phone w-full h-20 flex justify-between items-center md:hidden bg-[#153569] px-6 ">
        <div class="flex items-center h-full">
            <i class="fas fa-bars text-2xl relative top-1 cursor-pointer text-white" onclick="togglePopup()"></i>
            <a href="{{ route('homePage') }}">
                <img src="{{ asset('image/logoBC.png') }}" alt="" class="w-[65px] ml-4">
            </a>
        </div>
        <div class="flex items-center gap-4 text-2xl relative top-1">
            <div class="coin flex items-center gap-[7px]">
                <img src="{{ asset('image/koin.png') }}" alt="" class="w-[25px] pointer-events-none">
                <span id="jumlahKoin" class="text-lg text-white font-bold">
                    {{ Auth::user()->TanyaUserCoin->jumlah_koin ?? 0 }}
                </span>
            </div>
            {{-- <span>
                <i class="fas fa-bell text-white font-bold"></i>
            </span> --}}
        </div>
    </div>
    <div class="sidebar-beranda-phone md:hidden" id="popup-1">
        <div class="overlay-sidebar-phone"></div>
        <div class="content-sidebar-phone">
            <header class="w-full h-20 bg-[--color-second] flex items-center justify-between pl-2 pr-6">
                <img src="{{ asset('image/logoBC.png') }}" alt="" class="w-[60px]">
                <i class="fas fa-xmark text-2xl text-white cursor-pointer" onclick="togglePopup()"></i>
            </header>
            <main>
                <section>
                    <div class="profile-account flex flex-col items-center px-2 my-6">
                        <a href="{{ route('profile') }}">
                            <i class="fas fa-circle-user text-5xl text-gray-500"></i>
                        </a>
                        <span>{{ Str::limit(Auth::user()->Profile->nama_lengkap ?? '', 20) }}</span>
                        <span class="text-xs">{{ Auth::user()->Profile->Kelas->kelas ?? '' }}</span>
                    </div>
                    <div class="navbar-phone">
                        <ul class="nav-list">
                            <li class="list-menu">
                                <a href="{{ route('beranda') }}">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                    <span class="navbar-phone-link_name">Beranda</span>
                                </a>
                            </li>
                            {{-- <li class="menu-murid">
                                <a href="/histori">
                                    <i class="fa-solid fa-calendar-days"></i>
                                    <span class="link_name">Presensi Harian</span>
                                </a>
                            </li> --}}
                            <div>
                                <li class="list-menu">
                                    <a href="{{ route('historiPembelian.index') }}">
                                        <i class="fa-solid fa-cart-shopping"></i>
                                        <span class="navbar-phone-link_name">Riwayat Pembelian</span>
                                    </a>
                                </li>
                                <li class="list-menu">
                                    <a href="{{ route('historiKoin.index') }}">
                                        <i class="fa-solid fa-coins"></i>
                                        <span class="navbar-phone-link_name">Riwayat Koin</span>
                                    </a>
                                </li>

                                <div class="border-b-[1px] my-4"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button
                                        class="flex items-center justify-center w-full max-w-[250px] px-4 py-2 mt-6 mx-auto text-[--color-second] font-bold bg-red-200 rounded-full gap-2 cursor-pointer transition-all duration-300 hover:bg-red-300 focus:ring-2 focus:ring-red-400 active:scale-95">
                                        <i class="fas fa-right-from-bracket transform rotate-[-180deg]"></i>
                                        <span>Keluar</span>
                                    </button>
                                </form>
                            </div>
                        </ul>
                    </div>
                </section>
            </main>
        </div>
    </div>
@elseif(Auth::user()->role === 'Mentor')
    <!--- Sidebar Beranda for dekstop ---->
    <aside class="sidebar-beranda hidden md:block">
        <div class="logo_details flex items-center justify-center">
            <a href="/">
                <img src="{{ asset('image/logoBC.png') }}" alt="" class="w-2/5">
            </a>
        </div>
        <ul class="nav-list">
            <li class="list-menu">
                <a href="/beranda">
                    <i class="fa-solid fa-home"></i>
                    <span class="link_name">Beranda</span>
                </a>
                <span class="tooltip">Beranda</span>
            </li>
            <li class="list-menu">
                <a href="{{ route('tanya.rank') }}">
                    <i class="fa-solid fa-medal"></i>
                    <span class="link_name">Riwayat Rank</span>
                </a>
                <span class="tooltip">Riwayat Rank</span>
            </li>
            <li class="list-menu">
                <a href="{{ route('report-mentor') }}">
                    <i class="fa-solid fa-chart-simple"></i>
                    <span class="link_name">Laporan Mentor</span>
                </a>
                <span class="tooltip">Laporan Mentor</span>
            </li>
            <div class="hideSidebar cursor-pointer" id="close">
                <i class="fa-solid fa-chevron-left" id="log_outt"></i>
                <span>Sembunyikan</span>
            </div>
        </ul>
    </aside>
    <nav class="home-beranda hidden md:block">
        <div class="content">
            {{-- Navbar for PC --}}
            <div class="navbar-beranda">
                <header>
                    @if (isset($linkBackButton))
                        <a href="{{ $linkBackButton }}">
                            @if (isset($backButton))
                                <button class="font-bold text-[#4189e0] text-lg">{!! $backButton !!}</button>
                                {{ $headerSideNav ?? '' }}
                            @endif
                        </a>
                    @else
                        @if (isset($backButton))
                            <button class="font-bold text-[#4189e0] text-lg">{!! $backButton !!}</button>
                        @endif
                        {{ $headerSideNav ?? '' }}
                    @endif
                </header>
                <div class="flex gap-10">
                    <!--- notification & coin --->
                    <div class="information-account">
                        {{-- <div class="notification">
                            <i class="fa-solid fa-bell"></i>
                        </div> --}}
                    </div>
                    <!--- profile button dekstop --->
                    <div class="list-item">
                        <div class="dropdown-menu hidden lg:block">
                            <div class="toggle-menu-sidebar">
                                <div class="profile justify-between bg-[--color-second]">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-regular fa-circle-user !text-3xl"></i>
                                        <div class="information-profile">
                                            <span
                                                class="name">{{ Str::limit(Auth::user()->Profile->nama_lengkap ?? '', 20) }}</span>
                                            <span class="class">{{ Str::limit(Auth::user()->role ?? '', 20) }}</span>
                                        </div>
                                    </div>
                                    <i id="rotate" class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                            <div
                                class="content-dropdown !z-[9999] absolute bg-white border border-gray-200 shadow-lg w-[220px] rounded-lg mt-2">
                                <a href="{{ route('beranda') }}">
                                    <div
                                        class="link-href hover:bg-gray-100 hover:!text-black !flex-row items-center gap-[5px]">
                                        <i class="fa-solid fa-house text-md"></i>
                                        Beranda
                                    </div>
                                </a>
                                <a href="{{ route('profile') }}">
                                    <div
                                        class="link-href hover:bg-gray-100 hover:!text-black !flex-row items-center gap-[5px]">
                                        <i class="fa-regular fa-circle-user text-lg"></i>
                                        Profile
                                    </div>
                                </a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button
                                        class="link-href hover:bg-gray-100 hover:!text-black cursor-pointer w-full flex !flex-row items-center gap-2 text-start">
                                        <i class="fa-solid fa-arrow-right-from-bracket text-md ml-[3px]"></i>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- profile button rounded mobile -->
                    <li class="list-item relative lg:hidden">
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
                                <a href="{{ route('profile') }}">
                                    <div
                                        class="link-href hover:bg-gray-100 hover:!text-black !flex-row items-center gap-[5px]">
                                        <i class="fa-regular fa-circle-user text-lg"></i>
                                        Profile
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
            </div>
        </div>
    </nav>
    <!--- Sidebar Beranda for mobile ---->
    <nav class="navbar-beranda-phone w-full h-20 flex justify-between items-center md:hidden bg-[#153569] px-6 ">
        <div class="flex items-center h-full">
            <i class="fas fa-bars text-2xl relative top-1 cursor-pointer text-white" onclick="togglePopup()"></i>
            <a href="{{ route('homePage') }}">
                <img src="{{ asset('image/logoBC.png') }}" alt="" class="w-[65px] ml-4">
            </a>
        </div>
        <div class="flex items-center gap-8 text-2xl relative top-1">
            <!-- profile button rounded mobile -->
            <li class="list-item relative lg:hidden">
                <div class="dropdown-menu">
                    <div class="toggle-menu">
                        <i class="fas fa-circle-user !text-4xl text-white font-bold"></i>
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
                        <a href="{{ route('profile') }}">
                            <div
                                class="link-href hover:bg-gray-100 hover:!text-black !flex-row items-center gap-[5px]">
                                <i class="fa-regular fa-circle-user text-lg"></i>
                                Profile
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
    </nav>
    <aside class="sidebar-beranda-phone md:hidden" id="popup-1">
        <div class="overlay-sidebar-phone"></div>
        <div class="content-sidebar-phone">
            <header class="w-full h-20 bg-[--color-second] flex items-center justify-between pl-2 pr-6">
                <img src="{{ asset('image/logoBC.png') }}" alt="" class="w-[60px]">
                <i class="fas fa-xmark text-2xl text-white cursor-pointer" onclick="togglePopup()"></i>
            </header>
            <main>
                <section>
                    <div class="profile-account flex flex-col items-center px-2 my-6">
                        <i class="fas fa-circle-user text-5xl text-gray-500"></i>
                        <span>{{ Str::limit(Auth::user()->Profile->nama_lengkap ?? '', 20) }}</span>
                        <span class="text-xs">{{ Auth::user()->role ?? '' }}</span>
                    </div>
                    <div class="navbar-phone">
                        <ul class="nav-list">
                            <li class="list-menu">
                                <a href="{{ route('beranda') }}">
                                    <i class="fa-solid fa-home"></i>
                                    <span class="navbar-phone-link_name">Beranda</span>
                                </a>
                            </li>
                            <div>
                                <li class="list-menu">
                                    <a href="{{ route('tanya.rank') }}">
                                        <i class="fa-solid fa-medal"></i>
                                        <span class="navbar-phone-link_name">Riwayat Rank</span>
                                    </a>
                                </li>
                                <li class="list-menu">
                                    <a href="{{ route('report-mentor') }}">
                                        <i class="fa-solid fa-chart-simple"></i>
                                        <span class="navbar-phone-link_name">Laporan Mentor</span>
                                    </a>
                                </li>

                                <div class="border-b-[1px] my-4"></div>

                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button
                                        class="flex items-center justify-center w-full max-w-[250px] px-4 py-2 mt-6 mx-auto text-[--color-second] font-bold bg-red-200 rounded-full gap-2 cursor-pointer transition-all duration-300 hover:bg-red-300 focus:ring-2 focus:ring-red-400 active:scale-95">
                                        <i class="fas fa-right-from-bracket transform rotate-[-180deg]"></i>
                                        <span>Keluar</span>
                                    </button>
                                </form>
                            </div>
                        </ul>
                    </div>
                </section>
            </main>
        </div>
    </aside>
@elseif(Auth::user()->role === 'Administrator')
    <aside class="sidebar-beranda-administrator hidden md:block">
        <a href="{{ route('homePage') }}">
            <div class="logo_details flex items-center justify-center">
                <img src="{{ asset('image/logoBC.png') }}" alt="" class="w-2/4">
            </div>
        </a>
        <ul class="mt-8">
            <li class="list-item">
                <div class="dropdown-menu">
                    <div class="content-menu text-sm flex items-center gap-3">
                        <i class="fas fa-house"></i>
                        <a href="{{ route('beranda') }}" class="link-href">Beranda</a>
                    </div>
                </div>
            </li>
            <li class="list-item">
                <div class="dropdown-menu">
                    <div class="toggle-menu-sidebar">
                        <i class="icon-menu fa-solid fa-user-graduate !text-md"></i>
                        <span class="">Mentor</span>
                        <i class="fas fa-chevron-down absolute right-0" id="rotate"></i>
                    </div>
                    <div class="content-dropdown">
                        <a href="{{ route('list.mentor') }}" class="link-href">List Mentor Apply</a>
                        <a href="{{ route('list.mentor.aktif') }}" class="link-href">List Mentor Aktif</a>
                    </div>
                </div>
            </li>
            <li class="list-item">
                <div class="dropdown-menu">
                    <div class="toggle-menu-sidebar">
                        <i class="fa-solid fa-question !text-lg"></i>
                        <span>Syllabus & Services</span>
                        <i class="fas fa-chevron-down absolute right-0" id="rotate"></i>
                    </div>
                    <div class="content-dropdown">
                        <a href="{{ route('kurikulum.index') }}" class="link-href">
                            Manage Syllabus
                        </a>
                    </div>
                </div>
            </li>
            <li class="list-item">
                <div class="dropdown-menu">
                    <div class="toggle-menu-sidebar">
                        <i class="fa-solid fa-question !text-lg"></i>
                        <span>TANYA</span>
                        <i class="fas fa-chevron-down absolute right-0" id="rotate"></i>
                    </div>
                    <div class="content-dropdown">
                        <a href="{{ route('listQuestion.index') }}" class="link-href">List Pertanyaan</a>
                        <a href="{{ route('tanya.mentor') }}" class="link-href">Verifikasi Pertanyaan</a>
                        <a href="{{ route('pembayaran.tanya.mentor.view') }}" class="link-href">Pembayaran Mentor</a>
                        <a href="{{ route('tanya.access') }}" class="link-href">Libur TANYA</a>
                    </div>
                </div>
            </li>
            <li class="list-item">
                <div class="dropdown-menu">
                    <div class="toggle-menu-sidebar">
                        <i class="fas fa-house"></i>
                        <span>English Zone</span>
                        <i class="fas fa-chevron-down absolute right-0" id="rotate"></i>
                    </div>
                    <div class="content-dropdown">
                        <a href="{{ route('englisHone.uploadMateri') }}" class="link-href">Upload Materi</a>
                        <a href="{{ route('englishZone.uploadSoal') }}" class="link-href">Upload Soal</a>
                        <a href="{{ route('englishZone.questionForRelease') }}" class="link-href">Question For
                            Release</a>
                    </div>
                </div>
            </li>
            <li class="list-item">
                <div class="dropdown-menu">
                    <div class="toggle-menu-sidebar">
                        <i class="fa-solid fa-book-open-reader !text-md"></i>
                        <span>Soal & Pembahasan</span>
                        <i class="fas fa-chevron-down absolute right-0" id="rotate"></i>
                    </div>
                    <div class="content-dropdown">
                        <a href="{{ route('bankSoal.view') }}" class="link-href">Bank Soal</a>
                    </div>
                </div>
            </li>
        </ul>
    </aside>

    <div class="home-beranda hidden md:block">
        <div class="content">
            {{-- Navbar for PC --}}
            <div class="navbar-beranda">
                <header>
                    @if (isset($linkBackButton))
                        <a href="{{ $linkBackButton }}">
                            @if (isset($backButton))
                                <div class="flex items-center gap-2">
                                    <button class="font-bold text-[#4189e0] text-lg">{!! $backButton !!}</button>
                                    <span>{{ $headerSideNav ?? '' }}</span>
                                </div>
                            @endif
                        </a>
                    @else
                        @if (isset($backButton))
                            <button class="font-bold text-[#4189e0] text-lg">{!! $backButton !!}</button>
                        @endif
                        {{ $headerSideNav ?? '' }}
                    @endif
                </header>

                <!-- profile button dekstop -->
                <li class="list-item">
                    <div class="dropdown-menu hidden lg:block">
                        <div class="toggle-menu-sidebar">
                            <div class="profile justify-between bg-[--color-second]">
                                <div class="flex items-center gap-2">
                                    <i class="fa-regular fa-circle-user !text-3xl"></i>
                                    <div class="information-profile">
                                        <span
                                            class="name">{{ Str::limit(Auth::user()->Profile->nama_lengkap ?? '', 20) }}</span>
                                        <span class="class">{{ Str::limit(Auth::user()->role ?? '', 20) }}</span>
                                    </div>
                                </div>
                                <i id="rotate" class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                        <div
                            class="content-dropdown !z-[9999] absolute bg-white border border-gray-200 shadow-lg w-[220px] rounded-lg mt-2">
                            <a href="{{ route('beranda') }}">
                                <div
                                    class="link-href hover:bg-gray-100 hover:!text-black !flex-row items-center gap-[5px]">
                                    <i class="fa-solid fa-house text-md"></i>
                                    Beranda
                                </div>
                            </a>
                            <a href="{{ route('profile') }}">
                                <div
                                    class="link-href hover:bg-gray-100 hover:!text-black !flex-row items-center gap-[5px]">
                                    <i class="fa-regular fa-circle-user text-lg"></i>
                                    Profile
                                </div>
                            </a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button
                                    class="link-href hover:bg-gray-100 hover:!text-black cursor-pointer w-full flex !flex-row items-center gap-2 text-start">
                                    <i class="fa-solid fa-arrow-right-from-bracket text-md ml-[3px]"></i>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </li>

                <!-- profile button rounded mobile -->
                <li class="list-item relative lg:hidden">
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
                            <a href="{{ route('profile') }}">
                                <div
                                    class="link-href hover:bg-gray-100 hover:!text-black !flex-row items-center gap-[5px]">
                                    <i class="fa-regular fa-circle-user text-lg"></i>
                                    Profile
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
        </div>
    </div>

    <!--- Sidebar Beranda for mobile ---->
    <nav class="navbar-beranda-phone w-full h-20 flex justify-between items-center md:hidden bg-[#153569] px-6 ">
        <div class="flex items-center h-full">
            <i class="fas fa-bars text-2xl relative top-1 cursor-pointer text-white" onclick="togglePopup()"></i>
            <a href="{{ route('homePage') }}">
                <img src="{{ asset('image/logoBC.png') }}" alt="" class="w-[65px] ml-4">
            </a>
        </div>
        <div class="flex items-center gap-8 text-2xl relative top-1">
            <!-- profile button rounded -->
            <li class="list-item relative md:hidden">
                <div class="dropdown-menu">
                    <div class="toggle-menu">
                        <i class="fas fa-circle-user !text-4xl text-white font-bold"></i>
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
                        <a href="{{ route('profile') }}">
                            <div
                                class="link-href hover:bg-gray-100 hover:!text-black !flex-row items-center gap-[5px]">
                                <i class="fa-regular fa-circle-user text-lg"></i>
                                Profile
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
    </nav>
    <aside class="sidebar-beranda-phone md:hidden" id="popup-1">
        <div class="overlay-sidebar-phone"></div>
        <div class="content-sidebar-phone">
            <header class="w-full h-20 bg-[--color-second] flex items-center justify-between pl-2 pr-6">
                <img src="{{ asset('image/logoBC.png') }}" alt="" class="w-[60px]">
                <i class="fas fa-xmark text-2xl text-white cursor-pointer" onclick="togglePopup()"></i>
            </header>
            <main>
                <section>
                    <div class="profile-account flex flex-col items-center px-2 my-6">
                        <i class="fas fa-circle-user text-5xl text-gray-500"></i>
                        <span>{{ Str::limit(Auth::user()->Profile->nama_lengkap ?? '', 20) }}</span>
                        <span class="text-xs">{{ Auth::user()->role ?? '' }}</span>
                    </div>

                    <div class="border-b-[1px] my-4"></div>

                    <!--- navbar menu in mobile ---->
                    <div class="navbar-phone">
                        <ul class="nav-list">
                            <ul class="mt-8">
                                <li class="list-item">
                                    <div class="dropdown-menu">
                                        <div class="content-menu text-sm flex items-center gap-3">
                                            <i class="fas fa-house"></i>
                                            <a href="{{ route('beranda') }}" class="link-href">Beranda</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-item">
                                    <div class="dropdown-menu">
                                        <div class="toggle-menu-sidebar">
                                            <i class="fa-solid fa-user-graduate !text-md"></i>
                                            <span class="">Mentor</span>
                                            <i class="fas fa-chevron-down absolute right-0" id="rotate"></i>
                                        </div>
                                        <div class="content-dropdown">
                                            <a href="{{ route('list.mentor') }}" class="link-href">
                                                List Mentor Apply
                                            </a>
                                            <a href="{{ route('list.mentor.aktif') }}" class="link-href">
                                                List Mentor Aktif
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-item">
                                    <div class="dropdown-menu">
                                        <div class="toggle-menu-sidebar">
                                            <i class="fa-solid fa-question !text-lg"></i>
                                            <span>Syllabus & Services</span>
                                            <i class="fas fa-chevron-down absolute right-0" id="rotate"></i>
                                        </div>
                                        <div class="content-dropdown">
                                            <a href="{{ route('kurikulum.index') }}" class="link-href">
                                                Manage Syllabus
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-item">
                                    <div class="dropdown-menu">
                                        <div class="toggle-menu-sidebar">
                                            <i class="fa-solid fa-question !text-lg"></i>
                                            <span>TANYA</span>
                                            <i class="fas fa-chevron-down absolute right-0" id="rotate"></i>
                                        </div>
                                        <div class="content-dropdown">
                                            <a href="{{ route('listQuestion.index') }}" class="link-href">
                                                List Pertanyaan
                                            </a>
                                            <a href="{{ route('tanya.mentor') }}" class="link-href">Verifikasi
                                                Pertanyaan</a>
                                            <a href="{{ route('pembayaran.tanya.mentor.view') }}"
                                                class="link-href">Pembayaran Mentor</a>
                                            <a href="{{ route('tanya.access') }}" class="link-href">Libur TANYA</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-item">
                                    <div class="dropdown-menu">
                                        <div class="toggle-menu-sidebar">
                                            <i class="fas fa-house"></i>
                                            <span>English Zone</span>
                                            <i class="fas fa-chevron-down absolute right-0" id="rotate"></i>
                                        </div>
                                        <div class="content-dropdown">
                                            <a href="{{ route('englisHone.uploadMateri') }}" class="link-href">
                                                Upload Materi
                                            </a>
                                            <a href="{{ route('englishZone.uploadSoal') }}" class="link-href">
                                                Upload Soal
                                            </a>
                                            <a href="{{ route('englishZone.questionForRelease') }}"
                                                class="link-href">
                                                Question For Release
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-item">
                                    <div class="dropdown-menu">
                                        <div class="toggle-menu-sidebar">
                                            <i class="fa-solid fa-book-open-reader !text-md"></i>
                                            <span>Soal & Pembahasan</span>
                                            <i class="fas fa-chevron-down absolute right-0" id="rotate"></i>
                                        </div>
                                        <div class="content-dropdown">
                                            <a href="{{ route('bankSoal.view') }}" class="link-href">Bank Soal</a>
                                        </div>
                                    </div>
                                </li>
                            </ul>

                            <div class="border-b-[1px] my-4"></div>

                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button
                                    class="flex items-center justify-center w-full max-w-[250px] px-4 py-2 mt-6 mx-auto text-[--color-second] font-bold bg-red-200 rounded-full gap-2 cursor-pointer transition-all duration-300 hover:bg-red-300 focus:ring-2 focus:ring-red-400 active:scale-95">
                                    <i class="fas fa-right-from-bracket transform rotate-[-180deg]"></i>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </ul>
                    </div>
                </section>
            </main>
        </div>
    </aside>
@elseif(Auth::user()->role === 'Sales')
    <aside class="sidebar-beranda-administrator hidden md:block">
        <a href="{{ route('homePage') }}">
            <div class="logo_details flex items-center justify-center">
                <img src="{{ asset('image/logoBC.png') }}" alt="" class="w-2/4">
            </div>
        </a>
        <ul class="mt-8">
            <li class="list-item">
                <a href="/beranda">
                    <div class="toggle-menu-sidebar">
                        <i class="fas fa-house"></i>
                        <span>Beranda</span>
                    </div>
                </a>
            </li>
            <li class="list-item">
                <div class="dropdown-menu">
                    <!-- Dropdown utama -->
                    <div class="toggle-menu-sidebar">
                        <i class="fas fa-house"></i>
                        <span class="toggle-dropdown">Administrasi Sales</span>
                        <i class="fas fa-chevron-down absolute right-0" id="rotate"></i>
                    </div>

                    <div class="content-dropdown">
                        <!-- Dropdown kedua (sub menu) -->
                        <div class="toggle-menu-sidebar2">
                            <span class="toggle-dropdown">Visitasi</span>
                            <i class="fas fa-chevron-down absolute right-0" id="rotate2"></i>
                        </div>

                        <div class="list-content-dropdown">
                            <a href="{{ route('jadwalKunjungan') }}" class="link-href">Buat Jadwal Kunjungan</a>
                            <a href="{{ route('dataKunjungan') }}" class="link-href">Laporan Kunjungan</a>
                        </div>
                    </div>
                </div>
            </li>
            <li class="list-item">
                <div class="dropdown-menu">
                    <!-- Dropdown utama -->
                    <div class="toggle-menu-sidebar">
                        <i class="fas fa-house"></i>
                        <span class="toggle-dropdown">Management Surat PKS</span>
                        <i class="fas fa-chevron-down absolute right-0" id="rotate"></i>
                    </div>

                    <div class="content-dropdown">
                        <a href="{{ route('input-surat-pks') }}" class="link-href">Buat Surat PKS</a>
                        <a href="{{ route('cetakPKS') }}" class="link-href">Cetak Surat PKS</a>
                    </div>
                </div>
            </li>
        </ul>
    </aside>

    <div class="home-beranda hidden md:block">
        <div class="content">
            {{-- Navbar for PC --}}
            <div class="navbar-beranda">
                <header>Beranda</header>
                <div class="information-account">
                    <div class="profile">
                        <i class="fa-solid fa-user"></i>
                        <div class="information-profile">
                            <span class="name">{{ session('user')->nama_lengkap ?? '' }}</span>
                            <span class="class">{{ Auth::user()->role ?? '' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif(Auth::user()->role === 'Admin Sales')
    <aside class="sidebar-beranda-administrator hidden md:block">
        <a href="{{ route('homePage') }}">
            <div class="logo_details flex items-center justify-center">
                <img src="{{ asset('image/logoBC.png') }}" alt="" class="w-2/4">
            </div>
        </a>
        <ul class="mt-8">
            <li class="list-item">
                <div class="toggle-menu-sidebar">
                    <i class="fas fa-house"></i>
                    <a href="/beranda">Beranda</a>
                </div>
            </li>
            <li class="list-item">
                <div class="dropdown-menu">
                    <!-- Dropdown utama -->
                    <div class="toggle-menu-sidebar">
                        <i class="fas fa-house"></i>
                        <span class="toggle-dropdown">Master Data PKS</span>
                        <i class="fas fa-chevron-down absolute right-0" id="rotate"></i>
                    </div>

                    <div class="content-dropdown">
                        <!-- Dropdown kedua (sub menu) -->
                        <div class="toggle-menu-sidebar2">
                            <span class="toggle-dropdown">Input PKS</span>
                            <i class="fas fa-chevron-down absolute right-0" id="rotate2"></i>
                        </div>

                        <div class="list-content-dropdown">
                            <a href="{{ route('input-murid') }}" class="link-href">Data Civitas Sekolah</a>
                        </div>
                    </div>

                    <div class="content-dropdown">
                        <!-- Dropdown ketiga (sub menu) -->
                        <div class="toggle-menu-sidebar2">
                            <span class="toggle-dropdown">Management PKS</span>
                            <i class="fas fa-chevron-down absolute right-0" id="rotate2"></i>
                        </div>

                        <div class="list-content-dropdown">
                            <a href="{{ route('data-sekolah-pks') }}" class="link-href">Data Civitas Sekolah</a>
                            <a href="{{ route('data-sekolah') }}" class="link-href">Data Sekolah</a>
                        </div>
                    </div>
                </div>
            </li>

            <li class="list-item">
                <div class="dropdown-menu">
                    <!-- Dropdown utama -->
                    <div class="toggle-menu-sidebar">
                        <i class="fas fa-house"></i>
                        <span class="toggle-dropdown">Templates</span>
                        <i class="fas fa-chevron-down absolute right-0" id="rotate"></i>
                    </div>

                    <div class="content-dropdown">
                        <!-- Dropdown ketiga (sub menu) -->
                        <div class="toggle-menu-sidebar2">
                            <span class="toggle-dropdown">BulkUpload Services</span>
                            <i class="fas fa-chevron-down absolute right-0" id="rotate2"></i>
                        </div>

                        <div class="list-content-dropdown">
                            <a href="{{ route('bulk-upload-civitas-sekolah') }}" class="link-href">
                                Civitas Sekolah
                            </a>
                            <a href="" class="link-href">Soal EnglishZone</a>
                        </div>
                    </div>

                    <div class="content-dropdown">
                        <a href="{{ route('suratPKS') }}" class="link-href">Surat PKS</a>
                    </div>
                </div>
            </li>
        </ul>
    </aside>

    <div class="home-beranda hidden md:block">
        <div class="content">
            {{-- Navbar for PC --}}
            <div class="navbar-beranda">
                <header>Beranda</header>
                <div class="information-account">
                    <div class="profile">
                        <i class="fa-solid fa-user"></i>
                        <div class="information-profile">
                            <span class="name">{{ session('user')->nama_lengkap ?? '' }}</span>
                            <span class="class">{{ Auth::user()->role ?? '' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <p>You do not have access to this dashboard.</p>
@endif

<script src="js/Tanya/update-koin-tanya-student-ajax.js"></script>
<script src="{{ asset('js/components/sidebar-administrator.js') }}"></script> <!-- sidebar administrator -->
<script src="{{ asset('js/components/sidebar.js') }}"></script> <!-- sidebar untuk user selain administrator-->
<script src="{{ asset('js/components/navbar-button-profile-user.js') }}"></script> <!-- button profile user in navbar -->


<script>
    document.addEventListener("DOMContentLoaded", () => {
        window.Echo.channel('tanyaUserKoin')
            .listen('.tanya.coin.refunded', (e) => {
                updateJumlahKoinStudent();
            });
    });
</script>

<script>
    function togglePopup() {
        const sidebarMobile = document.getElementById('popup-1').classList.toggle('active');
    }
</script>

<script>
    window.onload = function() {
        const sidebar = document.querySelector(".sidebar-beranda");
        const closeSidebar = document.querySelector("#close");
        const closeBtn = document.querySelector("#log_outt");

        closeSidebar.addEventListener("click", function() {
            sidebar.classList.toggle("open")
            menuBtnChange()
        })

        function menuBtnChange() {
            if (sidebar.classList.contains("open")) {
                closeBtn.classList.replace("fa-chevron-left", "fa-chevron-right");
            } else {
                closeBtn.classList.replace("fa-chevron-right", "fa-chevron-left");
            }
        }
    }
</script>
