<x-sidebar_beranda :user="session('user')"></x-sidebar_beranda>
@extends('components/sidebar_beranda_mobile') <!-- Menggunakan layout dengan modal -->
@if (isset($user))
    @if ($user->status === 'Siswa')
        <div class="home-beranda z-[-1] md:z-0 border-4 mt-[80px] md:mt-0"> {{-- mt ini berguna untuk ketika sidebar lagi terbuka dan di responsif ke layar hp, content didalam sini turun supaya tidak bentrok sama extends sidebar mobile dan bisa dibuka --}}
            <div class="content-beranda mt-[120px]">
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
                            <div class="bg-yellow-500 mb-8 p-10 rounded-xl flex justify-center items-center">Jadwal
                                Hari
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
                    </div>
                </div>
            </div>
        </div>
    @elseif ($user->status === 'Murid')
        INI BERANDA MURID
    @elseif ($user->status === 'Mentor')
        <div class="home-beranda z-[-1] md:z-0 border-4 mt-[80px] md:mt-0"> {{-- mt ini berguna untuk ketika sidebar lagi terbuka dan di responsif ke layar hp, content didalam sini turun supaya tidak bentrok sama extends sidebar mobile dan bisa dibuka --}}
            <div class="content-beranda mt-[120px]">
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
                                Hari Ini</div>
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
                    </div>
                </div>
            </div>
        </div>
    @elseif($user->status === 'Admin')

    @elseif($user->status === 'Wakil Kepala Sekolah')

    @elseif($user->status === 'Kepala Sekolah')
        <span>jkenfjksdn</span>
    @elseif($user->status === 'Team Leader')
        <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0"> {{-- mt ini berguna untuk ketika sidebar lagi terbuka dan di responsif ke layar hp, content didalam sini turun supaya tidak bentrok sama extends sidebar mobile dan bisa dibuka --}}
            <div class="content-beranda mt-[120px]">
                <header class="text-2xl mb-8 font-bold">List Pertanyaan</header>
                <div class="w-full h-auto" id="questionTL">
                    @if (isset($getTanyaTL) && is_iterable($getTanyaTL) && $getTanyaTL->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="table" id="tableTanyaTL">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Siswa</th>
                                        <th>Pertanyaan</th>
                                        <th>Jawaban</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Bab</th>
                                        <th>Jam_Tanya</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>
                                <tbody id="tableListTL">
                                    {{-- show data in ajax --}}
                                </tbody>
                            </table>
                            <div class="pagination-container-TL"></div>
                            <div class="flex justify-center">
                                <span class="showMessage hidden absolute top-2/4">Tidak ada
                                    riwayat</span>
                            </div>
                        </div>
                    @else
                        <div class="h-full flex justify-center items-center">
                            <span>Tidak ada pertanyaan</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="flex flex-col min-h-screen items-center justify-center">
            <p>ALERT SEMENTARA</p>
            <p>You do not have access to this pages.</p>
        </div>
    @endif
@else
    <p>You are not logged in.</p>
@endif


<script src="js/tanya-TL-ajax.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuItems = document.querySelectorAll('.menu-murid a');

        // Ambil URL halaman saat ini
        const currentPage = window.location.pathname;

        // Periksa setiap menu dan beri kelas 'active' jika href-nya cocok dengan URL saat ini
        menuItems.forEach(item => {
            const menuLink = item.getAttribute('href'); // ambil href dari setiap link

            // Jika href menu sesuai dengan URL halaman saat ini
            if (currentPage.includes(menuLink)) {
                item.closest('.menu-murid').classList.add('active'); // tambahkan kelas active
            } else {
                item.closest('.menu-murid').classList.remove(
                    'active'); // hapus kelas active jika tidak sesuai
            }
        });
    });
</script>

<script>
    function togglePopup() {
        const sidebarMobile = document.getElementById('popup-1').classList.toggle('active');
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
