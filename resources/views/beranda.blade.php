@include('components/sidebar_beranda', ['headerSideNav' => 'Beranda'])

<!-- ALERT tanya access -->
@if (session('error'))
    <script>
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "{{ session('error') }}!",
        });
    </script>
@endif
@if (session('alertAccess'))
    <script>
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "{{ session('alertAccess') }}",
        });
    </script>
@endif
@if (Auth::user()->role === 'Siswa')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0"> {{-- mt ini berguna untuk ketika sidebar lagi terbuka dan di responsif ke layar hp, content didalam sini turun supaya tidak bentrok sama extends sidebar mobile dan bisa dibuka --}}
        <div class="content-beranda mt-[120px] md:mx-6">
            <div class="max-w-full">
                <div class="grid grid-cols-5 gap-6">
                    <div
                        class="relative lg:col-span-3 col-span-5 h-[440px] md:h-[480px] lg:h-[440px] bg-white shadow-lg rounded-lg">
                        <span class="text-lg font-bold opacity-70">Pengguna Tanya Terbanyak</span>
                        {{-- @if ($sortedSiswa->isNotEmpty()) --}}
                        <!-- TABLE -->
                        <div class="overflow-x-auto max-h-[310px]">
                            <table class="table mt-4 border border-separate w-full">
                                <thead class="thead-table-leaderboard-rank-tanya-student">
                                    <tr>
                                        <th class="border border-gray-400 px-3 py-2 text-center text-black">Rank</th>
                                        <th class="border border-gray-400 px-3 py-2 text-center text-black">Nama
                                            Lengkap</th>
                                        <th class="border border-gray-400 px-3 py-2 text-center text-black">Kelas
                                        </th>
                                        <th class="border border-gray-400 px-3 py-2 text-center text-black">Asal
                                            Sekolah</th>
                                        <th class="border border-gray-400 px-3 py-2 text-center text-black">Koin
                                            Terpakai</th>
                                        <th class="border border-gray-400 px-3 py-2 text-center text-black">Total
                                            BerTANYA</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-leaderboard-rank-tanya-student">
                                    {{-- @foreach ($sortedTanyaStudent as $value)
                                            <tr class="{{ $value->rowClass }}">
                                                <td class="border border-gray-300 px-3 py-2 text-center">
                                                    {!! $value->rankIcon ?? '' !!}</td>
                                                <td class="border border-gray-300 px-3 py-2 text-center">
                                                    {{ $value->Profile->nama_lengkap ?? '' }}</td>
                                                <td class="border border-gray-300 px-3 py-2 text-center">
                                                    {{ $value->Profile->Kelas->kelas ?? '' }}</td>
                                                <td class="border border-gray-300 px-3 py-2 text-center">
                                                    {{ $value->Profile->sekolah ?? '' }}</td>
                                                <td class="border border-gray-300 px-3 py-2 text-center">
                                                    {{ $value->jumlah_koin ?? '' }}</td>
                                                <td class="border border-gray-300 px-3 py-2 text-center">
                                                    {{ $value->jumlah_tanya ?? '' }}</td>
                                            </tr>
                                        @endforeach --}}
                                </tbody>
                            </table>
                            <div
                                class="pagination-container-leaderboard-rank-tanya-student flex justify-center my-4 sm:my-0">
                            </div>

                            <div id="empty-message-leaderboard-rank-tanya-student" class="w-full h-96 hidden">
                                <span class="w-full h-full flex items-center justify-center">
                                    Belum ada leaderboard.
                                </span>
                            </div>
                        </div>
                        <!-- PERINGKAT USER (Size XL)-->
                        <div class="hidden xl:block">
                            <div id="container-leaderboard-rank-tanya-user"
                                class="w-full h-26 bg-white absolute bottom-0 shadow-md border-t flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            </div>
                        </div>
                        {{-- <div class="hidden xl:block">
                                <div
                                    class="w-full h-26 bg-white absolute bottom-0 shadow-md border-t flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                    <div class="flex items-center gap-4 h-full">
                                        <div
                                            class="text-sm sm:text-base text-gray-700 border-r-2 h-full flex flex-col items-center justify-center border w-17">
                                            <span class="font-bold opacity-70 text-sm">Rank :</span>
                                            <span class="font-semibold text-indigo-600">
                                                @if ($rankingTanyaUser == 1)
                                                    <i class='fa-solid fa-crown text-yellow-400 font-bold text-lg'></i>
                                                @elseif ($rankingTanyaUser == 2)
                                                    <i class='fa-solid fa-crown text-gray-400 font-bold text-lg'></i>
                                                @elseif($rankingTanyaUser == 3)
                                                    <i class='fa-solid fa-crown text-amber-800 font-bold text-lg'></i>
                                                @else
                                                    <span>{{ $rankingTanyaUser ?? '?' }}</span>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="text-md font-bold opacity-70 ml-2">
                                            {{ Auth::user()->Profile->nama_lengkap ?? '' }}
                                        </div>
                                    </div>
                                    <div
                                        class="text-sm sm:text-base text-gray-700 border-l-2 w-45 h-full px-4 flex flex-col items-center justify-center">
                                        <span class="font-bold opacity-70 text-sm">Total berTANYA:</span>
                                        <span class="font-bold opacity-70">{{ $countDataTanyaUserLogin ?? 0 }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- PERINGKAT USER (Size Mobile, Lg)-->
                            <div class="xl:hidden">
                                <div
                                    class="w-full h-32 bg-white absolute bottom-0 shadow-md border-t flex flex-col items-center gap-4">
                                    <div class="text-md font-bold opacity-70 ml-2 px-6 pt-4 xl:pt-0">
                                        {{ Auth::user()->Profile->nama_lengkap ?? '' }}
                                    </div>
                                    <div class="grid grid-cols-2 gap-4 w-full h-full">
                                        <div
                                            class="text-sm sm:text-base text-gray-700 h-full flex flex-col items-center justify-center w-full">
                                            <span class="font-bold opacity-70 text-sm">Peringkat :</span>
                                            <span class="font-semibold text-indigo-600">
                                                @if ($rankingTanyaUser == 1)
                                                    <i class='fa-solid fa-crown text-yellow-400 font-bold text-lg'></i>
                                                @elseif ($rankingTanyaUser == 2)
                                                    <i class='fa-solid fa-crown text-gray-400 font-bold text-lg'></i>
                                                @elseif($rankingTanyaUser == 3)
                                                    <i class='fa-solid fa-crown text-amber-800 font-bold text-lg'></i>
                                                @else
                                                    <span>{{ $rankingTanyaUser ?? '?' }}</span>
                                                @endif
                                            </span>
                                        </div>
                                        <div
                                            class="text-sm sm:text-base text-gray-700 h-full px-4 flex flex-col items-center justify-center">
                                            <span class="font-bold opacity-70 text-sm">Total berTANYA:</span>
                                            <span
                                                class="font-bold opacity-70">{{ $countDataTanyaUserLogin ?? 0 }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                        {{-- @else
                            <div class="h-full flex justify-center items-center">
                                <span>Tidak ada riwayat</span>
                            </div>
                        @endif --}}
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
@elseif (Auth::user()->role === 'Mentor')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0"> {{-- mt ini berguna untuk ketika sidebar lagi terbuka dan di responsif ke layar hp, content didalam sini turun supaya tidak bentrok sama extends sidebar mobile dan bisa dibuka --}}
        <div class="content-beranda mt-[120px]">
            <div class="max-w-full mx-6">
                <div class="grid grid-cols-5 gap-6">
                    <div
                        class="relative lg:col-span-3 col-span-5 md:h-[480px] lg:h-[440px] h-[440px] overflow-hidden bg-white shadow-lg rounded-lg">
                        a
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
@elseif(Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            <header class="text-3xl">
                Dashboard
            </header>
            <main>
                <section>
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12 sm:col-span-6 xl:col-span-4 shadow-lg bg-white px-6 py-6 flex gap-4">
                            <div class="bg-[#A294F9] w-20 h-18 flex items-center justify-center rounded-xl text-white">
                                <i class="fa-solid fa-user text-2xl"></i>
                            </div>
                            <div class="flex flex-col leading-8">
                                <span class="">Murid B2C</span>
                                <span>{{ $getDataSiswa->count() }} Murid</span>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 xl:col-span-4 shadow-lg bg-white px-6 py-6 flex gap-4">
                            <div
                                class="bg-[--color-three] w-20 h-18 flex items-center justify-center rounded-xl text-white">
                                <i class="fa-solid fa-users text-2xl"></i>
                            </div>
                            <div class="flex flex-col leading-8">
                                <span class="">Murid B2B & B2G</span>
                                <span>{{ $getDataMurid->count() }} Murid</span>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 xl:col-span-4 shadow-lg bg-white px-6 py-6 flex gap-4">
                            <div class="bg-[#79D7BE] w-20 h-18 flex items-center justify-center rounded-xl text-white">
                                <i class="fa-solid fa-school text-2xl"></i>
                            </div>
                            <div class="flex flex-col leading-8">
                                <span class="">Sekolah Terdaftar</span>
                                <span>{{ $getDataMurid->count() }} Sekolah</span>
                            </div>
                        </div>
                    </div>
                </section>
                <!---- chart TANYA harian  ----->
                <div class="relative w-full bg-white shadow-lg p-4">
                    <div class="relative !h-[520px] w-full">
                        <canvas id="myChart-days"
                            data-chart-tanya-harian="{{ route('getChartDataTanyaHarian') }}"></canvas>

                        <div id="noDataTanyaHarianMessage" class="text-center text-gray-500 my-4 hidden">
                            <span class="flex items-center justify-center">Tidak ada data harian pada bulan ini.</span>
                        </div>
                    </div>
                    <div class="flex justify-center gap-10 mt-14">
                        <button id="prevMonth" class="w-10 h-10 bg-[--color-three] rounded-full text-white hidden">
                            <div class="flex items-center justify-center">
                                <i class="fa-solid fa-arrow-left"></i>
                            </div>
                        </button>

                        <span id="monthDisplay" class="mt-2"></span>

                        <button id="nextMonth" class="w-10 h-10 bg-[--color-three] rounded-full text-white hidden">
                            <div class="flex items-center justify-center">
                                <i class="fa-solid fa-arrow-right"></i>
                            </div>
                        </button>
                    </div>
                </div>

                <!---- chart TANYA bulanan  ----->
                <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[300px] gap-6 mt-20">
                    <div class="bg-white shadow-lg p-4 relative">
                        <canvas id="myChart-months"
                            data-chart-tanya-bulanan="{{ route('getChartDataTanyaBulanan') }}">
                        </canvas>
                        <div id="noDataTanyaBulananMessage" class="text-center text-gray-500 hidden">
                            <span class="flex items-center justify-center">Tidak ada data bulanan pada
                                tahun ini.</span>
                        </div>

                        <div class="flex justify-center gap-10 mt-4">
                            <button id="prevYear"
                                class="w-10 h-10 bg-[--color-three] rounded-full text-white hidden">
                                <div class="flex items-center justify-center">
                                    <i class="fa-solid fa-arrow-left"></i>
                                </div>
                            </button>

                            <span id="yearDisplay" class="mt-2"></span>

                            <button id="nextYear"
                                class="w-10 h-10 bg-[--color-three] rounded-full text-white hidden">
                                <div class="flex items-center justify-center">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!---- chart TANYA tahunan  ----->
                    <div class="bg-white shadow-lg p-4 w-full relative">
                        <canvas id="myChart-years" class=""
                            data-chart-tanya-tahunan="{{ route('getChartDataTanyaTahunan') }}"></canvas>
                        <div id="noDataTanyaTahunanMessage" class="text-gray-500 hidden">
                            <span class="flex items-center justify-center">Tidak ada data pada tahun ini.</span>
                        </div>

                        <div class="flex justify-center gap-10 mt-4">
                            <button id="prevYearButton"
                                class="w-10 h-10 bg-[--color-three] rounded-full text-white hidden">
                                <div class="flex items-center justify-center">
                                    <i class="fa-solid fa-arrow-left"></i>
                                </div>
                            </button>

                            <span id="displayYear" class="mt-2"></span>

                            <button id="nextYearButton"
                                class="w-10 h-10 bg-[--color-three] rounded-full text-white hidden">
                                <div class="flex items-center justify-center">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

                <!---- list mentor active & table laporan pengguna TANYA terbanyak  ----->
                <section>
                    <div class="grid grid-cols-12 gap-6 rounded-lg mt-20">
                        <div
                            class="col-span-12 xl:col-span-4 h-40 bg-white shadow-lg flex flex-col items-center justify-center leading-10">
                            <span>Jumlah Mentor Aktif</span>
                            <span
                                class="text-[--color-default] font-bold text-2xl">{{ $countDataMentor->count() }}</span>
                        </div>
                        <div class="col-span-12 xl:col-span-8 bg-white shadow-lg p-6">
                            <div class="flex justify-between border-b-[1px] border-gray-400">
                                <span class="">Laporan Pengguna Tanya Terbanyak</span>
                                <span>{{ $countDataTanyaAll->count() }}</span>
                            </div>
                            @if ($sortedSiswa->isNotEmpty())
                                <div class="overflow-x-auto">
                                    <table class="table mt-4 ">
                                        <!-- head -->
                                        <thead>
                                            <tr>
                                                <th class="th-question">No</th>
                                                <th class="th-question">Nama Lengkap</th>
                                                <th class="th-question">Email</th>
                                                <th class="th-question">No.Hp</th>
                                                <th class="th-question">Kelas</th>
                                                <th class="th-question">Asal Sekolah</th>
                                                <th class="th-question">Total BerTANYA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($sortedSiswa as $value)
                                                <tr>
                                                    <td class="td-question !text-center">
                                                        {{ $loop->iteration === 1 ? '1st' : ($loop->iteration === 2 ? '2nd' : ($loop->iteration === 3 ? '3rd' : $loop->iteration)) }}
                                                    </td>
                                                    <td class="td-question">
                                                        {{ $value->Profile->nama_lengkap ?? '' }}
                                                    </td>
                                                    <td class="td-question">
                                                        {{ $value->email ?? '' }}
                                                    </td>
                                                    <td class="td-question !text-center">
                                                        {{ $value->no_hp ?? '' }}
                                                    </td>
                                                    <td class="td-question !text-center">
                                                        {{ $value->Profile->Kelas->kelas ?? '' }}
                                                    </td>
                                                    <td class="td-question">
                                                        {{ $value->Profile->sekolah ?? '' }}
                                                    </td>
                                                    <td class="td-question !text-center">
                                                        {{ $value->jumlah_tanya ?? '' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="h-full flex justify-center items-center">
                                    <span>Tidak ada riwayat</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>
@elseif(Auth::user()->role === 'Sales')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda"></div>
    </div>
@elseif(Auth::user()->role === 'Admin Sales')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda"></div>
    </div>
@else
    <div class="flex flex-col min-h-screen items-center justify-center">
        <p>ALERT SEMENTARA</p>
        <p>You do not have access to this pages.</p>
    </div>
@endif

<script src="js/chart.js"></script> <!--- chart js ---->

<script src="{{ asset('js/Tanya/leaderboard-rank-tanya/leaderboard-rank-tanya-student.js') }}"></script>

<!--- PUSHER LISTENER TANYA ---->
<script src="{{ asset('js/pusher-listener/leaderboard-rank-tanya-student.js') }}"></script>

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
