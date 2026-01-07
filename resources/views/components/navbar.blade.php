<x-script></x-script>

<nav
    class="flex items-center justify-between h-[95px] px-[20px] lg:px-[50px] shadow-[0px_8px_7.2px_#0000001A] fixed w-full bg-white top-0 z-[20]">
    <!--- for mobile --->
    <div class="flex items-center relative gap-4">
        @if (Auth::user() != null)
            <div class="bars flex md:hidden list-none cursor-pointer text-gray-600 z-[9999]">
                <li class="" onclick="openNavbar()" id="Show">
                    <i class="fa-solid fa-bars"></i>
                </li>
                <li class="hidden" onclick="hideNavbar()" id="Hide">
                    <i class="fa-solid fa-xmark"></i>
                </li>
            </div>
        @endif
        <a href="{{ route('homePage') }}" class="">
            <div>
                <img src="{{ asset('image/logo-bc/main-logo-bc.svg') }}" alt="no-image" class="h-[46px] w-[136px]">
            </div>
        </a>
    </div>

    <div>
        <ul class="hidden md:flex items-center gap-8 text-[#0071BC] text-[16px] font-volte font-semibold">
            <a href="{{ route('about') }}">
                <li>
                    Tentang Kami
                </li>
            </a>
            <a href="{{ route('mitraCerdas') }}">
                <li>
                    Mitra Cerdas
                </li>
            </a>
            <a href="">
                <li>
                    Promo Bundling
                </li>
            </a>
        </ul>
    </div>
    <div>
        <!-- BUTTON LOGIN / REGISTER -->
        @if (Auth::user() != null)
            <!-- profile user -->
            <li class="list-item-button-profile relative lg:hidden">
                <div class="dropdown-menu">
                    <div class="toggle-menu-button-profile">
                        <i class="fas fa-circle-user !text-4xl text-[#0071BC]"></i>
                    </div>
                    <div
                        class="content-dropdown-button-profile z-[9999] absolute bg-white border border-gray-200 shadow-lg w-[140px] rounded-lg mt-2 right-0">
                        <a href="{{ route('beranda') }}">
                            <div class="link-href hover:bg-gray-100 hover:!text-black !flex-row items-center gap-[5px]">
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

            <div class="hidden lg:block">
                <li class="list-item-button-profile">
                    <div class="dropdown-menu">
                        <div class="toggle-menu-button-profile">
                            <div class="profile justify-between bg-[#0071BC]">
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
                            class="content-dropdown-button-profile z-[9999] absolute bg-white border border-gray-200 shadow-lg w-[220px] rounded-lg mt-2">
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
        @else
            <button class="text-[#0071BC] text-[16px] font-volte font-semibold hidden lg:block"
                onclick="my_modal_1.showModal()">
                Masuk/Daftar
            </button>
        @endif

        @if (Auth::user() === null)
            <div class="bars lg:hidden md:flex list-none cursor-pointer text-gray-600 z-[9999]">
                <li class="" onclick="openNavbar()" id="Show">
                    <i class="fa-solid fa-bars"></i>
                </li>
                <li class="hidden" onclick="hideNavbar()" id="Hide">
                    <i class="fa-solid fa-xmark"></i>
                </li>
            </div>
        @endif
    </div>

    <!-- navbar for mobile & dekstop  --->
    <div class="navbar-component fixed top-[90px] left-[0px] z-[999] w-full h-auto bg-white shadow-lg font-bold md:pb-0 hidden"
        id="accordion">
        <div class="{{ Auth::user() === null ? 'lg:hidden' : 'md:hidden' }}">
            <div class="item w-[250px]">
                <ul class="header">
                    <li class="list-item w-max {{ Auth::user() === null ? 'md:hidden' : '' }}">
                        <a href="{{ route('about') }}"
                            class="link-href hover:!text-black items-start gap-[5px] !pl-0 text-sm">
                            <span class="hover:bg-gray-100 p-2 w-40 rounded-sm">
                                Tentang Kami
                            </span>
                        </a>
                    </li>
                    <li class="list-item w-max {{ Auth::user() === null ? 'md:hidden' : '' }}">
                        <a href="{{ route('mitraCerdas') }}"
                            class="link-href hover:!text-black items-start gap-[5px] !p-0 text-sm">
                            <span class="hover:bg-gray-100 p-2 w-40 rounded-sm">
                                Mitra Cerdas
                            </span>
                        </a>
                    </li>
                    <li class="list-item w-max {{ Auth::user() === null ? 'md:hidden' : '' }}">
                        <a href="" class="link-href hover:!text-black items-start gap-[5px] !pl-0 text-sm">
                            <span class="hover:bg-gray-100 p-2 w-40 rounded-sm">
                                Promo Bundling
                            </span>
                        </a>
                    </li>
                </ul>
            </div>

            <!--- button masuk / daftar ---->
            @if (Auth::user() === null)
                <div class="w-full h-32 px-20 flex items-center lg:hidden">
                    <button onclick="my_modal_1.showModal()"
                        class="bg-[#29AAE1] text-white px-4 py-[4px] rounded-full w-full">
                        Masuk/Daftar
                    </button>
                </div>
            @endif
        </div>
    </div>

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
                <form action="{{ route('auth.login') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="w-full mb-6">
                        <label class="text-sm">Email</label>
                        <input type="text" name="email" id="email" placeholder="Masukkan Email"
                            class="w-full bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md text-sm px-2 mt-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] {{ $errors->has('email') ? 'border-[1px] border-red-500' : '' }}"
                            value="{{ @old('email') }}" autocomplete="off">
                        @error('email')
                            <span class="text-red-500 font-bold text-xs pt-2 flex flex-start">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-full mb-6">
                        <label class="text-sm">Password</label>
                        <input type="password" name="password" placeholder="Masukkan Password"
                            class="w-full bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md text-sm px-2 mt-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] {{ $errors->has('password') ? 'border-[1px] border-red-500' : '' }}"
                            value="{{ @old('password') }}" autocomplete="off">
                        @error('password')
                            <span class="text-red-500 font-bold text-xs pt-2 flex flex-start">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit"
                        class="bg-[#4179e0] text-white w-full mt-2 h-10 rounded-lg font-semibold hover:bg-[#4189e0]">
                        Masuk
                    </button>
                </form>
                <div class="text-sm mb-6 mt-4 flex justify-center gap-[3px]">
                    Belum punya akun?
                    <a href="{{ route('daftar.user') }}" class="text-blue-500 font-semibold hover:underline">
                        Daftar sekarang
                    </a>
                </div>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
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
