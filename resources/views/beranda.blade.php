<x-sidebar_beranda></x-sidebar_beranda>
@if (isset($user))
    @if ($user->status === 'Siswa')
        <div class="home-beranda">
            <div class="content">
                {{-- Navbar for PC --}}
                <div class="navbar-beranda">
                    <header>Beranda</header>
                    <!-- <div class="information-account">
                            <div class="notification">
                            <i class="fa-solid fa-bell"></i>
                        </div>
                        <div class="coin">
                            <i class="fa-solid fa-coins"></i>
                        </div> -->
                    <div class="profile">
                        <i class="fa-solid fa-user"></i>
                        <div class="information-profile">
                            <span class="name">{{ $user->nama_lengkap }}</span><br>
                            <span class="class">{{ $user->kelas }}</span>
                        </div>
                    </div>
                </div>
                {{-- navbar for mobile --}}
                <div class="navbar-beranda-phone w-full h-full flex justify-between items-center">
                    <div class="flex items-center">
                        <i class="fas fa-bars text-2xl relative top-1" onclick="togglePopup()"></i>
                        <img src="image/logoBC-example.png" alt="" class="w-[80%]">
                    </div>
                    <div class="flex items-center gap-10 text-2xl relative top-1">
                        <span>
                            <i class="fas fa-bell text-blue-500 font-bold"></i>
                        </span>
                        <span class="">
                            <i class="fas fa-coins text-yellow-500 "></i>
                            <a>0</a>
                        </span>
                    </div>
                </div>
                <div class="sidebar-beranda-phone" id="popup-1">
                    <div class="overlay-sidebar-phone"></div>
                    <div class="content-sidebar-phone">
                        <header class="w-full h-20 bg-[--color-default] flex items-center justify-between pl-2 pr-6">
                            <img src="image/logoBC-example.png" alt="" class="w-24">
                            <i class="fas fa-xmark text-2xl text-white" onclick="togglePopup()"></i>
                        </header>
                        <main>
                            <section></section>
                        </main>
                    </div>
                </div>
            </div>
        </div>
        <div class="home-beranda z-[-1] md:z-0">
            <div class="md:content-beranda mt-[120px]">
                <div class="max-w-full border-[1px] border-gray-200 mx-6">
                    <div class="grid grid-cols-5 gap-6">
                        <div
                            class="relative ... lg:col-span-3 col-span-5 h-96 border-[1px] border-gray-200 overflow-hidden">
                            <x-dropdown></x-dropdown>
                            <div class="k13 w-full h-full absolute border-[1px] border-red-500 pt-20 top-0"
                                id="k13">
                                <figure class="flex gap-4 px-14 mb-4">
                                    <img src="image/k13.png" alt="" class="w-[30px]">
                                    <figcaption class="font-bold">K13</figcaption>
                                </figure>
                                <div class="w-full border-[1px] border-gray-200">
                                    <div
                                        class="border-[1px] border-red-500 gap-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
                                        @foreach ($mapelK13 as $mapel)
                                            <figure class="w-full border-[1px] border-yellow-400 hidden md:block">
                                                <div class="w-full flex justify-center">
                                                    <img src="{{ $mapel['image'] }}" alt="" class="w-[30px]">
                                                </div>
                                                <figcaption class="text-center">{{ $mapel['judul'] }}
                                                </figcaption>
                                            </figure>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="md:hidden p-2" onclick="my_modal_3.showModal()">
                                    <figure class="w-max ml-2">
                                        <div class="w-full flex justify-center mb-2">
                                            <img src="image/pkn.png" alt="" class="w-[30px]">
                                        </div>
                                        <figcaption class="text-xs text-center md:hidden">Semua Pelajaran</figcaption>
                                    </figure>
                                </div>

                                <dialog id="my_modal_3" class="modal">
                                    <div class="modal-box">
                                        <form method="dialog">
                                            <button class=" outline-none absolute right-4 top-1">✕</button>
                                        </form>
                                        <h3 class="text-lg font-bold">Hello!</h3>
                                        <p class="py-4">Press ESC key or click on ✕ button to close</p>
                                    </div>
                                </dialog>
                            </div>
                            <div class="merdeka absolute right-[-100%] border-[1px] border-gray-200 w-full h-full pt-20 top-0"
                                id="merdeka">
                                <figure class="flex gap-4 px-14 mb-4">
                                    <img src="image/k13.png" alt="" class="w-[30px]">
                                    <figcaption class="font-bold">Merdeka</figcaption>
                                </figure>
                                <div class="w-full border-[1px] border-gray-200">
                                    <div class="border-[1px] border-blue-200 gap-4 flex flex-wrap">
                                        @foreach ($mapelMerdeka as $mapel)
                                            <figure class="w-[210px] border-2 border-yellow-400 mx-auto">
                                                <div class="w-full flex justify-center">
                                                    <img src="{{ $mapel['image'] }}" alt="" class="w-[30px]">
                                                </div>
                                                <figcaption class="text-center">{{ $mapel['judul'] }}</figcaption>
                                            </figure>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="lg:col-span-2 col-span-5 mt-8">
                            <div class="bg-yellow-500 mb-8 p-10 rounded-xl flex justify-center items-center">Jadwal Hari
                                Ini
                            </div>
                            <div class="bg-green-500 p-10 rounded-xl flex justify-center items-center">Real Clock</div>
                        </div>
                        <div class="lg:col-span-3 md:col-span-5 col-span-5">
                            <div class="grid xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-3 grid-cols-2 gap-4">
                                @foreach ($packetSiswa as $packet)
                                    <div class="w-full h-full relative ... border-[1px] border-gray-200 rounded-lg">
                                        <header>
                                            <div class="w-full h-[110px] border-[1px] border-gray-200">
                                                <img src="{{ $packet['image'] }}" alt=""
                                                    class="w-full h-full object-cover">
                                            </div>

                                            <section class="mt-10 w-full h-16 text-center">
                                                <span class="text-xs">{{ $packet['text'] }}</span>
                                            </section>

                                            <a href="{{ $packet['url'] }}">
                                                <footer class="flex justify-center pb-6 mt-4">
                                                    <button
                                                        class="border-none outline-none bg-gray-700 w-[93%] h-8 rounded-lg text-white font-bold text-sm">{{ $packet['button'] }}</button>
                                                </footer>
                                            </a>
                                        </header>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        {{-- <div
                            class="lg:col-span-3 md:col-span-5 col-span-5 grid lg:grid-cols-4 md:grid-cols-3 gap-4 border-2">
                            @foreach ($packetSiswa as $packet)
                                <div
                                    class="w-full h-full relative ... border-[1px] border-gray-200 rounded-lg hidden md:block">
                                    <header>
                                        <div class="w-full h-[110px] border-[1px] border-gray-200">
                                            <img src="{{ $packet['image'] }}" alt=""
                                                class="w-full h-full bg-cover">
                                        </div>

                                        <section class="mt-10 w-full h-16 text-center">
                                            <span class="text-sm">{{ $packet['text'] }}</span>
                                        </section>

                                        <a href="{{ $packet['url'] }}">
                                            <footer class="flex justify-center pb-6">
                                                <button
                                                    class="border-none outline-none bg-gray-700 w-[93%] h-8 rounded-lg text-white font-bold text-sm">{{ $packet['button'] }}</button>
                                            </footer>
                                        </a>
                                    </header>
                                </div>
                            @endforeach
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>

        <p>Welcome, {{ $user->nama_lengkap }}</p>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">Logout</button>
        </form>
    @elseif ($user->status === 'Murid')
        <p>Welcome, Guru!</p>
        <!-- Guru-specific content here -->
    @elseif ($user->status === 'Guru')
        <div class="home-beranda">
            <div class="content">
                <div class="navbar-beranda">
                    <header>Beranda</header>
                    <!-- <div class="information-account">
                <div class="notification">
                <i class="fa-solid fa-bell"></i>
            </div>
            <div class="coin">
                <i class="fa-solid fa-coins"></i>
            </div> -->
                    <div class="profile">
                        <i class="fa-solid fa-user"></i>
                        <div class="information-profile">
                            <span class="name">{{ $user->nama_lengkap }}</span><br>
                            <span class="class">{{ $user->kelas }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="home-beranda">
            <div class="content-beranda">
                <div class="max-w-full border-[1px] border-gray-200 mx-6">
                    <div class="grid grid-cols-5 gap-6">
                        <div
                            class="relative ... lg:col-span-3 col-span-5 h-96 border-[1px] border-gray-200 overflow-hidden">
                            <x-dropdown></x-dropdown>
                            <div class="k13 w-full h-full absolute border-[1px] border-red-500 pt-20 top-0"
                                id="k13">
                                <figure class="flex gap-4 px-14 mb-4">
                                    <img src="image/k13.png" alt="" class="w-[30px]">
                                    <figcaption class="font-bold">K13</figcaption>
                                </figure>
                                <div class="w-full border-[1px] border-gray-200">
                                    <div class="border-[1px] border-blue-200 gap-4 flex flex-wrap">
                                        @foreach ($mapelK13 as $mapel)
                                            <figure
                                                class="w-[210px] border-[1px] border-yellow-400 mx-auto hidden md:block">
                                                <div class="w-full flex justify-center">
                                                    <img src="{{ $mapel['image'] }}" alt=""
                                                        class="w-[30px]">
                                                </div>
                                                <figcaption class="text-center">{{ $mapel['judul'] }}</figcaption>
                                            </figure>
                                        @endforeach
                                    </div>

                                    <figure class="w-max border-4 ml-2 btn md:hidden">
                                        <button class="btn" onclick="my_modal_3.showModal()">
                                            <div class="w-full flex justify-center mb-2">
                                                <img src="image/pkn.png" alt="" class="w-[30px]">
                                            </div>
                                            <figcaption class="text-xs text-center">Semua Pelajaran</figcaption>
                                        </button>
                                    </figure>
                                </div>

                                <dialog id="my_modal_3" class="modal">
                                    <div class="modal-box">
                                        <form method="dialog">
                                            <button
                                                class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                                        </form>
                                        <h3 class="text-lg font-bold">Hello!</h3>
                                        <p class="py-4">Press ESC key or click on ✕ button to close</p>
                                    </div>
                                </dialog>
                            </div>
                            <div class="merdeka absolute right-[-100%] border-[1px] border-gray-200 w-full h-full pt-20 top-0"
                                id="merdeka">
                                <figure class="flex gap-4 px-14 mb-4">
                                    <img src="image/k13.png" alt="" class="w-[30px]">
                                    <figcaption class="font-bold">Merdeka</figcaption>
                                </figure>
                                <div class="w-full border-[1px] border-gray-200">
                                    <div class="border-[1px] border-blue-200 gap-4 flex flex-wrap">
                                        @foreach ($mapelMerdeka as $mapel)
                                            <figure class="w-[210px] border-2 border-yellow-400 mx-auto">
                                                <div class="w-full flex justify-center">
                                                    <img src="{{ $mapel['image'] }}" alt=""
                                                        class="w-[30px]">
                                                </div>
                                                <figcaption class="text-center">{{ $mapel['judul'] }}</figcaption>
                                            </figure>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="lg:col-span-2 col-span-5 mt-8">
                            <div class="bg-yellow-500 mb-8 p-10 rounded-xl flex justify-center items-center">Jadwal
                                Hari Ini
                            </div>
                            <div class="bg-green-500 p-10 rounded-xl flex justify-center items-center">Real Clock</div>
                        </div>
                        <div
                            class="lg:col-span-3 md:col-span-5 col-span-5 flex flex-wrap border-[1px] border-gray-200">
                            @foreach ($packetSiswa as $packet)
                                <div
                                    class="w-[240px] h-[280px] relative ... border-[1px] border-gray-200 rounded-lg mx-auto hidden md:block">
                                    <header>
                                        <div class="w-full h-[110px] border-[1px] border-gray-200">
                                            <img src="{{ $packet['image'] }}" alt=""
                                                class="w-full h-full bg-cover">
                                        </div>

                                        <section class="mt-10 w-full">
                                            <span class="text-sm flex text-center">{{ $packet['text'] }}</span>
                                        </section>

                                        <a href="{{ $packet['url'] }}">
                                            <footer class="flex justify-center">
                                                <button
                                                    class="absolute bottom-6 border-none outline-none bg-gray-700 w-[93%] h-8 rounded-lg text-white font-bold text-sm">{{ $packet['button'] }}</button>
                                            </footer>
                                        </a>
                                    </header>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <p>Welcome, {{ $user->nama_lengkap }}</p>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">Logout</button>
        </form>
        <!-- Murid-specific content here -->
    @elseif($user->status === 'Admin')

    @elseif($user->status === 'Wakil Kepala Sekolah')

    @elseif($user->status === 'Kepala Sekolah')
    @else
        <p>You do not have access to this dashboard.</p>
    @endif
@else
    <p>You are not logged in.</p>
@endif


<script>
    function togglePopup() {
        var sidebarPhone = document.getElementById("popup-1").classList.toggle("active");
        var overlaySidebar = document.querySelector(".overlay-sidebar-phone");

        if (sidebarPhone.classList.contains("active")) {
            overlaySidebar.classList.add("active");
        } else {
            overlaySidebar.classList.remove("active");
        }
    }
</script>


<script>
    function toggleDropdown() {
        let dropdownButton = document.querySelector('#dropdownButton #dropdown');
        dropdown.classList.toggle("hidden");
        let dropdownArrow = document.getElementById('dropdownArrow').classList.toggle('rotate-180');
    }

    function updateSelection(radio) {
        const selectedKurikulum = document.getElementById("selectedKurikulum");
        selectedKurikulum.textContent = radio.value;

        // Menampilkan ikon centang
        // const icons = document.querySelectorAll(".iconChecked");
        // icons.forEach(icon => icon.classList.add("hidden")); // Sembunyikan semua ikon
        // const checkedIcon = radio.nextElementSibling.querySelector(".iconChecked");
        // checkedIcon.classList.remove("hidden");

        // Menyembunyikan dropdown setelah memilih
        toggleDropdown();
    }
</script>

<script>
    var lampau = document.getElementById('k13');
    var mrdka = document.getElementById('merdeka');

    function k13() {
        lampau.style.left = "0px";
        mrdka.style.right = "-1102px";
    }

    function merdeka() {
        lampau.style.left = "-1100px";
        mrdka.style.right = "0px";
    }
</script>


{{-- <div class="max-w-full border-2 border-red-500 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 xl:grid-cols-5 mx-8">
    <div class="col-span-3 lg:h-[450px] h-[350px] border-2 bg-blue-500">
        <span>Mapel</span>
    </div>
    <div class="col-span-2">
        <div class="w-full h-[150px] border-2 bg-green-500 flex justify-center items-center">
            <span>Jadwal</span>
        </div>
        <div class="w-full h-[150px] border-2 bg-yellow-500 flex justify-center items-center">
            <span>Time</span>
        </div>
    </div>
</div> --}}
