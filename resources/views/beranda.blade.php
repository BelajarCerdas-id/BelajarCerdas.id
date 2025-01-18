@include('components/sidebar_beranda')
@extends('components/sidebar_beranda_mobile') <!-- Menggunakan layout dengan modal -->
@if (isset($user))
    @if ($user->status === 'Siswa')
        <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0"> {{-- mt ini berguna untuk ketika sidebar lagi terbuka dan di responsif ke layar hp, content didalam sini turun supaya tidak bentrok sama extends sidebar mobile dan bisa dibuka --}}
            <div class="content-beranda mt-[120px]">
                <div class="max-w-full mx-6">
                    <div class="grid grid-cols-5 gap-6">
                        <div
                            class="relative lg:col-span-3 col-span-5 md:h-[480px] lg:h-[440px] h-[440px] overflow-hidden bg-white shadow-lg rounded-lg">
                            <x-dropdown></x-dropdown>
                            <div class="k13 w-full h-full absolute pt-20 top-0" id="k13">
                                <figure class="flex gap-4 px-14 mb-4 lg:mt-4">
                                    <img src="image/k13.png" alt="" class="w-[30px]">
                                    <figcaption class="font-bold">K13</figcaption>
                                </figure>
                                <div class="w-full lg:mt-12">
                                    <div
                                        class="gap-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 mx-4">
                                        @foreach ($mapelK13 as $mapel)
                                            <figure
                                                class="w-full hidden md:block hover:bg-slate-200 cursor-pointer rounded-lg">
                                                <div class="w-full flex justify-center">
                                                    <img src="{{ $mapel['image'] }}" alt=""
                                                        class="w-[34px] h-10">
                                                </div>
                                                <figcaption class="text-center text-sm">{{ $mapel['judul'] }}
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
                            <div class="merdeka absolute right-[-100%] w-full h-full pt-20 top-0" id="merdeka">
                                <figure class="flex gap-4 px-14 mb-4 lg:mt-4">
                                    <img src="image/k13.png" alt="" class="w-[30px]">
                                    <figcaption class="font-bold">Merdeka</figcaption>
                                </figure>
                                <div class="w-full lg:mt-12">
                                    <div
                                        class="gap-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 mx-4">
                                        @foreach ($mapelMerdeka as $mapel)
                                            <figure
                                                class="w-full hidden md:block hover:bg-slate-200 cursor-pointer rounded-lg">
                                                <div class="w-full flex justify-center">
                                                    <img src="{{ $mapel['image'] }}" alt=""
                                                        class="w-[34px] h-10">
                                                </div>
                                                <figcaption class="text-center text-sm">{{ $mapel['judul'] }}
                                                </figcaption>
                                            </figure>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="lg:col-span-2 col-span-5 mt-8">
                            <span class="text-lg"> Jadwal : </span>
                            <div class="p-10 rounded-xl flex justify-center items-center bg-white shadow-lg mb-8">Jadwal
                                Hari
                                Ini
                            </div>
                            <span class="text-lg"> Hari Ini : </span>
                            <div
                                class="lg:col-span-2 col-span-5 p-10 rounded-xl flex justify-center items-center bg-white shadow-lg">
                                <div id="timestamp" class="text-center text-lg font-bold"></div>
                            </div>
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
        <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0"> {{-- mt ini berguna untuk ketika sidebar lagi terbuka dan di responsif ke layar hp, content didalam sini turun supaya tidak bentrok sama extends sidebar mobile dan bisa dibuka --}}
            <div class="content-beranda mt-[120px]">
                <div class="max-w-full mx-6">
                    <div class="grid grid-cols-5 gap-6">
                        <div
                            class="relative lg:col-span-3 col-span-5 md:h-[480px] lg:h-[440px] h-[440px] overflow-hidden bg-white shadow-lg rounded-lg">
                            <x-dropdown></x-dropdown>
                            <div class="k13 w-full h-full absolute pt-20 top-0" id="k13">
                                <figure class="flex gap-4 px-14 mb-4 lg:mt-4">
                                    <img src="image/k13.png" alt="" class="w-[30px]">
                                    <figcaption class="font-bold">K13</figcaption>
                                </figure>
                                <div class="w-full lg:mt-12">
                                    <div
                                        class="gap-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 mx-4">
                                        @foreach ($mapelK13 as $mapel)
                                            <figure
                                                class="w-full hidden md:block hover:bg-slate-200 cursor-pointer rounded-lg">
                                                <div class="w-full flex justify-center">
                                                    <img src="{{ $mapel['image'] }}" alt=""
                                                        class="w-[34px] h-10">
                                                </div>
                                                <figcaption class="text-center text-sm">{{ $mapel['judul'] }}
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
                            <div class="merdeka absolute right-[-100%] w-full h-full pt-20 top-0" id="merdeka">
                                <figure class="flex gap-4 px-14 mb-4 lg:mt-4">
                                    <img src="image/k13.png" alt="" class="w-[30px]">
                                    <figcaption class="font-bold">Merdeka</figcaption>
                                </figure>
                                <div class="w-full lg:mt-12">
                                    <div
                                        class="gap-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 mx-4">
                                        @foreach ($mapelMerdeka as $mapel)
                                            <figure
                                                class="w-full hidden md:block hover:bg-slate-200 cursor-pointer rounded-lg">
                                                <div class="w-full flex justify-center">
                                                    <img src="{{ $mapel['image'] }}" alt=""
                                                        class="w-[34px] h-10">
                                                </div>
                                                <figcaption class="text-center text-sm">{{ $mapel['judul'] }}
                                                </figcaption>
                                            </figure>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="lg:col-span-2 col-span-5 mt-8">
                            <span class="text-lg"> Jadwal : </span>
                            <div class="p-10 rounded-xl flex justify-center items-center bg-white shadow-lg mb-8">
                                Jadwal
                                Hari
                                Ini
                            </div>
                            <span class="text-lg"> Hari Ini : </span>
                            <div
                                class="lg:col-span-2 col-span-5 p-10 rounded-xl flex justify-center items-center bg-white shadow-lg">
                                <div id="timestamp" class="text-center text-lg font-bold"></div>
                            </div>
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
    @elseif ($user->status === 'Mentor')
        <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0"> {{-- mt ini berguna untuk ketika sidebar lagi terbuka dan di responsif ke layar hp, content didalam sini turun supaya tidak bentrok sama extends sidebar mobile dan bisa dibuka --}}
            <div class="content-beranda mt-[120px]">
                <div class="max-w-full mx-6">
                    <div class="grid grid-cols-5 gap-6">
                        <div
                            class="relative lg:col-span-3 col-span-5 md:h-[480px] lg:h-[440px] h-[440px] overflow-hidden bg-white shadow-lg rounded-lg">
                            <x-dropdown></x-dropdown>
                            <div class="k13 w-full h-full absolute pt-20 top-0" id="k13">
                                <figure class="flex gap-4 px-14 mb-4 lg:mt-4">
                                    <img src="image/k13.png" alt="" class="w-[30px]">
                                    <figcaption class="font-bold">K13</figcaption>
                                </figure>
                                <div class="w-full lg:mt-12">
                                    <div
                                        class="gap-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 mx-4">
                                        @foreach ($mapelK13 as $mapel)
                                            <figure
                                                class="w-full hidden md:block hover:bg-slate-200 cursor-pointer rounded-lg">
                                                <div class="w-full flex justify-center">
                                                    <img src="{{ $mapel['image'] }}" alt=""
                                                        class="w-[34px] h-10">
                                                </div>
                                                <figcaption class="text-center text-sm">{{ $mapel['judul'] }}
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
                            <div class="merdeka absolute right-[-100%] w-full h-full pt-20 top-0" id="merdeka">
                                <figure class="flex gap-4 px-14 mb-4 lg:mt-4">
                                    <img src="image/k13.png" alt="" class="w-[30px]">
                                    <figcaption class="font-bold">Merdeka</figcaption>
                                </figure>
                                <div class="w-full lg:mt-12">
                                    <div
                                        class="gap-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 mx-4">
                                        @foreach ($mapelMerdeka as $mapel)
                                            <figure
                                                class="w-full hidden md:block hover:bg-slate-200 cursor-pointer rounded-lg">
                                                <div class="w-full flex justify-center">
                                                    <img src="{{ $mapel['image'] }}" alt=""
                                                        class="w-[34px] h-10">
                                                </div>
                                                <figcaption class="text-center text-sm">{{ $mapel['judul'] }}
                                                </figcaption>
                                            </figure>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="lg:col-span-2 col-span-5 mt-8">
                            <span class="text-lg"> Jadwal : </span>
                            <div class="p-10 rounded-xl flex justify-center items-center bg-white shadow-lg mb-8">
                                Jadwal
                                Hari
                                Ini
                            </div>
                            <span class="text-lg"> Hari Ini : </span>
                            <div
                                class="lg:col-span-2 col-span-5 p-10 rounded-xl flex justify-center items-center bg-white shadow-lg">
                                <div id="timestamp" class="text-center text-lg font-bold"></div>
                            </div>
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
        </div>
    @elseif($user->status === 'Admin')

    @elseif($user->status === 'Wakil Kepala Sekolah')

    @elseif($user->status === 'Kepala Sekolah')

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
    @elseif($user->status === 'XR')
        <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
            <div class="content-beranda mt-[120px]">
                <header class="text-2xl mb-8 font-bold">List Mentor</header>
                <ul class="bg-white shadow-lg w-[450px] h-40 p-4 mb-8 rounded-lg">
                    <li>
                        <span class="text-sm">Total Dibayar</span> :
                        <span class="text-sm">{{ $totalPaidCount }}</span>
                    </li>
                    <li>
                        <span class="text-sm">Total Belum Dibayar</span> :
                        <span class="text-sm">{{ $totalUnpaidCount }}</span>
                    </li>
                    <li>
                        <span class="text-sm">Total Data Menunggu</span> :
                        <span class="text-sm">{{ $totalWaitingUnpaidCount }}</span>
                    </li>
                </ul>
                <div class="grid grid-cols-4 gap-8">
                    @foreach ($getData as $item)
                        <a href="{{ route('laporan.edit', $item->id) }}">
                            <div class="bg-white flex items-center gap-2 pl-6 rounded-xl shadow-lg">
                                <i class="fas fa-circle-user text-4xl"></i>
                                <div class="flex flex-col w-full">
                                    <span class="text-sm leading-6">
                                        Nama:
                                        {{ $item->nama_lengkap }}
                                    </span>
                                    <span class="text-xs leading-6">
                                        Sekolah:
                                        {{ $item->sekolah }}
                                    </span>
                                    <span class="text-xs leading-6">
                                        Total Tanya:
                                        {{ isset($countData[$item->email]) ? $countData[$item->email] : 0 }}
                                    </span>
                                    <div class="flex justify-between pr-8">
                                        <span class="text-xs leading-6">
                                            Dibayar :
                                            {{ isset($paidBatchCount[$item->email]) ? $paidBatchCount[$item->email] : 0 }}
                                        </span>
                                        <span class="text-xs leading-6">
                                            Belum Dibayar :
                                            {{ isset($unpaidBatchCount[$item->email]) ? $unpaidBatchCount[$item->email] : 0 }}
                                        </span>
                                        <span class="text-xs leading-6">
                                            Menunggu :
                                            {{ isset($waitingBatchCount[$item->email]) ? $waitingBatchCount[$item->email] : 0 }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @elseif($user->status === 'Administrator')
        fvbdf
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

<script>
    function formatNumber(num) {
        return num < 10 ? '0' + num : num; // Menambahkan 0 di depan jika kurang dari 10
    }

    function updateTimestamp() {
        const date = new Date();

        const days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu"];
        const day = days[date.getDay()]; // Mendapatkan hari dalam minggu

        const dayNumber = formatNumber(date.getDate()); // Tanggal

        const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September",
            "Oktober", "November", "Desember",
        ];
        const month = months[date.getMonth()]; // Bulan (Januari - Desember)

        const year = date.getFullYear(); // Tahun

        const hours = formatNumber(date.getHours()); // Jam
        const minutes = formatNumber(date.getMinutes()); // Minute
        const seconds = formatNumber(date.getSeconds()); // Detik

        const formattedTimestamp =
            `${day}, ${dayNumber} ${month} ${year}
                                            ${hours}:${minutes}:${seconds}`; // Format: Day, DD/MM/YYYY HH:MM:SS
        document.getElementById('timestamp').innerText = formattedTimestamp;
    }

    // Update timestamp setiap detik
    setInterval(updateTimestamp, 1000);

    // Panggil fungsi sekali untuk menampilkan timestamp saat pertama kali
    updateTimestamp();
</script>
