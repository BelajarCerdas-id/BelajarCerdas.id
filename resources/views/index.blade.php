<x-layout>
    {{-- <x-slot:title>{{ $title }}</x-slot:title> --}}
</x-layout>

<header>
    <main>
        <section class="grid grid-cols-12 relative">
            <div class="col-span-12 md:col-span-6 px-4 md:pl-12">
                <div class=" md:text-left flex flex-col justify-center h-full">
                    <p class="text-4xl md:text-4xl lg:text-6xl xl:text-7xl">
                        <span>Belajar Mandiri<br> atau dengan <br> bantuan tutor</span>
                    </p>
                    <p class="mt-2">
                        <span>Solusi lengkap dan terjangkau – <br>
                            belajar jadi lebih mudah dan asyik!</span>
                    </p>
                    <div class="flex flex-col md:flex-row gap-2 mt-4">
                        <button
                            class="border-2 outline-none w-full md:w-32 h-10 rounded-full text-sm hover:bg-[--color-default] hover:text-white font-bold">Daftar</button>
                        <a href="/login">
                            <button
                                class="border-2 outline-none w-full md:w-32 h-10 rounded-full text-sm hover:bg-[--color-default] hover:text-white font-bold">Masuk</button>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-span-5 hidden md:block">
                <img src="image/software tester-amico.png" alt="">
            </div>
        </section>
    </main>
</header>

<div
    class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 lg:grid-cols-4 gap-2 mx-2 lg:w-[90%] xl:w-max lg:mx-auto mt-40">
    @foreach ($packets as $packet)
        <article class="xl:w-[225px] xl:h-max bg-white shadow-lg rounded-lg overflow-hidden relative ... border-[1px]">
            <section class="relative top-[-10px]">
                <header>
                    <img src="{{ $packet['Image'] }}" alt="" class="w-full h-[100px] object-cover">
                </header>
                <main>
                    <ul class="text-sm list-none px-4 h-[150px]">
                        <li class="flex gap-1 mt-4 mb-2">
                            <i class="fa-solid fa-circle text-[8px] text-[--color-default] relative ... top-[6px]"></i>
                            <span class="text-[12px] text-justify"><?= $packet['Desc1'] ?></span>
                        </li>
                        <li class="flex gap-1 mt-4 mb-2">
                            <i class="fa-solid fa-circle text-[8px] text-[--color-default] relative ... top-[6px]"></i>
                            <span class="text-[12px] text-justify"><?= $packet['Desc2'] ?></span>
                        </li>
                    </ul>
                </main>
                <footer class="text-center">
                    <div class="border-b-[1px] border-gray-300 w-full"></div>
                    <div class="priceList mt-10">
                        <div class="flex justify-center gap-2">
                            <a class="text-xs md:text-sm text-[--color-second] font-medium">Mulai dari</a>
                            <span
                                class="line-through text-red-500 font-bold text-xs md:text-sm">{{ $packet['Discount'] }}</span>
                        </div>
                        <span class="font-bold text-xs md:text-base">{{ $packet['Price'] }}</span>
                    </div>
                    <button
                        class="bg-[--color-second] w-[90%] rounded-full h-[40px] text-white font-bold text-xs md:text-sm mt-4">{{ $packet['Button'] }}</button>
                </footer>
            </section>
        </article>
    @endforeach
</div>

    <div class="relative flex justify-center items-center min-h-screen p-6">
        <!-- Main Content -->
        <div class="relative rounded-lg shadow-xl border-[1px] border-gray-200 w-full max-w-[1500px] h-auto p-6 md:p-10">
            <!-- Icon Top Element -->
            <div class="absolute right-4 top-10 md:right-10 md:top-8">
                <div class="relative w-8 md:w-12">
                    <!-- Diagonal Line 1 -->
                    <div class="absolute inset-0 bg-[--color-default] rotate-45 w-full h-2 md:h-3"></div>
                    <!-- Diagonal Line 2 -->
                    <div class="absolute inset-0 bg-[--color-default] -rotate-45 w-full h-2 md:h-3"></div>
                </div>
            </div>
            <!-- Icon Bottom Element -->
            <div class="absolute right-4 bottom-10 md:right-10 md:bottom-12">
                <div class="relative w-8 md:w-12">
                    <!-- Diagonal Line 1 -->
                    <div class="absolute inset-0 bg-[--color-default] w-full h-2 md:h-3"></div>
                    <!-- Diagonal Line 2 -->
                    <div class="absolute inset-0 bg-[--color-default] rotate-90 w-full h-2 md:h-3"></div>
                </div>
            </div>
            <!-- Left Side: Image -->
            <div class="relative grid grid-cols-1 md:grid-cols-12 gap-6 h-auto my-20">
                <!-- Left Content -->
                <div class="col-span-12 md:col-span-6 flex justify-center items-center">
                    <!-- Background Elements -->
                    <div class="bg-[--color-default] w-52 h-52 md:w-80 md:h-80 rounded-full relative">
                        <div class="w-[300px] md:w-[460px] relative left-[-24%] md:left-[-23%] top-[-25%] z-20">
                            <!-- Image -->
                            <img src="image/asset_belajarcerdas2-01.png" alt="Person with laptop" class="">
                        </div>
                        <div class="absolute inset-0">
                            <div class="absolute top-0 left-0 bg-[--color-default] w-6 h-6 md:w-10 md:h-10 rounded-full"></div>
                        </div>
                        <div class="absolute inset-[-10px]">
                            <div class="absolute bottom-0 right-0 bg-[--color-default] w-8 h-8 md:w-14 md:h-14 rounded-full"></div>
                        </div>
                    </div>


                </div>
                <!-- Right Side: Text Content -->
                <div class="flex flex-col justify-center col-span-12 md:col-span-6 relative">
                    <h1 class="text-4xl md:text-4xl font-bold text-gray-800 absolute top-0 md:top-6">
                        BelajarCerdas.<span class="text-[--color-default]">id</span>
                    </h1>
                    <div class="mt-20">
                        <p class="text-gray-600 text-sm md:text-base leading-6 md:leading-8 text-justify">
                            BelajarCerdas.id hadir sebagai solusi inovatif di dunia pendidikan, menggabungkan kekuatan
                            teknologi online dengan pendekatan offline yang personal.
                        </p>
                    </div>
                    <div class="mt-4">
                        <p class="text-gray-600 text-sm md:text-base leading-6 md:leading-8 text-justify">
                            Kami percaya bahwa belajar bukan hanya tentang siswa, tetapi juga tentang memberdayakan guru untuk
                            menciptakan pengalaman belajar yang seamless dan efektif.
                        </p>
                    </div>
                    <button
                        class="mt-6 px-6 py-3 bg-[#ffc000] font-bold rounded-full shadow-md hover:bg-yellow-600 w-full md:w-[160px] mb-20">
                        KNOW MORE
                    </button>
                </div>
            </div>
        </div>
    </div>

<div class="relative flex justify-center items-center p-6">
    <!-- Main Content -->
    <div class="relative rounded-lg shadow-xl border-[1px] border-gray-200 w-full max-w-[1500px] h-auto p-6 md:p-10">
        <!-- Icon Top Element -->
        <div class="absolute right-4 top-10 md:right-10 md:top-8">
            <div class="relative w-8 md:w-12">
                <!-- Diagonal Line 1 -->
                <div class="absolute inset-0 bg-[--color-default] rotate-45 w-full h-2 md:h-3"></div>
                <!-- Diagonal Line 2 -->
                <div class="absolute inset-0 bg-[--color-default] -rotate-45 w-full h-2 md:h-3"></div>
            </div>
        </div>
        <!-- Icon Bottom Element -->
        <div class="absolute right-4 bottom-10 md:right-10 md:bottom-12">
            <div class="relative w-8 md:w-12">
                <!-- Diagonal Line 1 -->
                <div class="absolute inset-0 bg-[--color-default] w-full h-2 md:h-3"></div>
                <!-- Diagonal Line 2 -->
                <div class="absolute inset-0 bg-[--color-default] rotate-90 w-full h-2 md:h-3"></div>
            </div>
        </div>
        <!-- Left Side: Image -->
        <div class="relative grid grid-cols-1 md:grid-cols-12 gap-6 h-auto my-20">
            <!-- Left Content -->
            <div class="col-span-12 md:col-span-6 flex justify-center items-center">
                <!-- image  -->
                <div class="flex flex-col gap-4">
                    <img src="image/icon1-ourGoals.png" alt="Person with laptop"
                        class="relative z-10 w-full max-h-[300px] object-cover mx-auto">
                    <img src="image/icon2-ourGoals.png" alt="Person with laptop"
                        class="relative z-10 w-full max-h-[300px] object-cover mx-auto">
                    <img src="image/icon3-ourGoals.png" alt="Person with laptop"
                        class="relative z-10 w-full max-h-[300px] object-cover mx-auto">
                </div>
            </div>
            <!-- Right Side: Text Content -->
            <div class="flex flex-col justify-center col-span-12 md:col-span-6 relative mb-32">
                <div class="flex gap-2">
                    <h1 class="text-4xl md:text-4xl font-bold absolute top-0 text-gray-800 md:top-6 flex gap-2">
                        Our<span class="text-[--color-default]">Goals</span>
                    </h1>
                </div>
                <div class="border-b-4 border-[--color-default] mt-14 md:mt-20 w-60"></div>
                <div class="mt-4">
                    <p class="text-gray-600 text-sm md:text-base leading-6 md:leading-8 font-bold">
                        "Menghadirkan Konsep dan pengalaman yang Menginspirasi Pendidikan Masa Depan"
                    </p>
                </div>
                <div class="my-4">
                    <p class="text-gray-600 text-sm md:text-base leading-6 md:leading-8 text-justify">
                        Kami tidak hanya menawarkan produk, tetapi menciptakan pengalaman yang relevan dan bermakna bagi konsumen kami. Dengan pendekatan Konsep STAR,
                        tujuan kami adalah:
                    </p>
                </div>
                <!-- Right Side: icon text Content -->
                <div class="flex flex-col gap-4">
                    <div class="flex gap-2">
                        <img src="image/globe-icon.png" alt="" class="w-6 object-contain">
                        <span class="text-justify text-sm">
                            Menghadirkan Pembelajaran yang Relevan dan Bermakna. Kami fokus menjual konsep dan pengalaman, bukan sekadar produk.
                        </span>
                    </div>
                    <div class="flex gap-2">
                        <img src="image/science-icon.png" alt="" class="w-6 object-contain">
                        <span class="text-justify text-sm">
                            Menyediakan Solusi Pendidikan yang Seamless. Menggabungkan pembelajaran online dan offline untuk menyelesaikan
                            tantangan.
                        </span>
                    </div>
                    <div class="flex gap-2">
                        <img src="image/telescope-icon.png" alt="" class="w-6 object-contain">
                        <span class="text-justify text-sm">
                            Menjangkau prospek yang sesuai: sekolah, guru, dan siswa yang berkeputusan untuk berinvestasi dalam pendidikan
                            berkualitas.
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="relative flex justify-center items-center min-h-screen p-6">
    <!-- Main Content -->
    <div class="relative rounded-lg shadow-xl border-[1px] border-gray-200 w-full max-w-[1500px] h-auto p-6 md:p-10">
        <!-- Icon Top Element -->
        <div class="absolute right-4 top-10 md:right-10 md:top-8">
            <div class="relative w-8 md:w-12">
                <!-- Diagonal Line 1 -->
                <div class="absolute inset-0 bg-[--color-default] rotate-45 w-full h-2 md:h-3"></div>
                <!-- Diagonal Line 2 -->
                <div class="absolute inset-0 bg-[--color-default] -rotate-45 w-full h-2 md:h-3"></div>
            </div>
        </div>
        <!-- Icon Bottom Element -->
        <div class="absolute right-4 bottom-10 md:right-10 md:bottom-12">
            <div class="relative w-8 md:w-12">
                <!-- Diagonal Line 1 -->
                <div class="absolute inset-0 bg-[--color-default] w-full h-2 md:h-3"></div>
                <!-- Diagonal Line 2 -->
                <div class="absolute inset-0 bg-[--color-default] rotate-90 w-full h-2 md:h-3"></div>
            </div>
        </div>
        <!-- Left Side: Image -->
        <div class="relative grid grid-cols-1 md:grid-cols-12 gap-6 h-auto my-20">
            <!-- Left Content -->
            <div class="col-span-12 md:col-span-6 flex justify-center items-center">
                <!-- Background Elements -->
                <div class="w-full h-full flex flex-col gap-10">
                        <input type="radio" id="tanyaServiceRadio" name="radioService" checked>
                        <label for="tanyaServiceRadio" class="flex items-center gap-4 serviceOption" onclick="tanyaServiceContent()">
                            <div class="icon-service">
                                <i class="fa-regular fa-circle-question text-4xl"></i>
                            </div>
                            <div class="title-service">
                                <h1 class="font-bold">Tanya</h1>
                                <span>Tanya: Jawaban untuk Rasa ingin Tahu Anda</span>
                            </div>
                        </label>
                        <input type="radio" id="haloGurServiceRadio" name="radioService">
                        <label for="haloGurServiceRadio" class="flex items-center gap-4 serviceOption" onclick="haloGurServiceContent()">
                            <div class="icon-service">
                                <i class="fa-solid fa-jedi text-4xl"></i>
                            </div>
                            <div class="title-service">
                                <h1 class="font-bold">Halo Guru</h1>
                                <span>Koneksi Langsung dengan Mentor Terbaik</span>
                            </div>
                        </label>
                    <input type="radio" id="englishZoneServiceRadio" name="radioService">
                    <label for="englishZoneServiceRadio" class="flex items-center gap-4 serviceOption" onclick="englishZoneServiceContent()">
                        <div class="icon-service">
                            <i class="fa-solid fa-microscope text-4xl"></i>
                        </div>
                        <div class="title-service">
                            <h1 class="font-bold">English Zone</h1>
                            <span>Berbicara Bahasa Inggris setiap hari</span>
                        </div>
                    </label>
                    <input type="radio" id="bimbelMapelUmumServiceRadio" name="radioService">
                    <label for="bimbelMapelUmumServiceRadio" class="flex items-center gap-4 serviceOption" onclick="bimbelMapelUmumServiceContent()">
                        <div class="icon-service">
                            <i class="fa-solid fa-globe text-4xl"></i>
                        </div>
                        <div class="title-service">
                            <h1 class="font-bold">Bimbel Mapel Umum</h1>
                            <span>Semua Mata Pelajaran, Semua Solusi</span>
                        </div>
                    </label>
                </div>
            </div>
            <!-- Right Side: Text Content -->
            <div class="flex flex-col justify-center col-span-12 md:col-span-6 relative">
                <div class="flex gap-2">
                    <h1 class="text-4xl md:text-4xl font-bold absolute top-0 text-gray-800 md:top-6 flex gap-2">
                        <span>Our</span>
                        <span class="text-[--color-default]">Best</span>
                        <span>Service</span>
                    </h1>
                    <div class="border-b-4 border-[--color-default] mt-14 md:mt-20 w-96"></div>
                </div>
                <div class="relative w-full h-80 flex overflow-hidden flex-col mt-6">
                    <div class="absolute w-full h-full flex flex-col right-0 duration-700 ease-out" id="tanyaServiceContent">
                        <p class="text-gray-600 text-sm md:text-base leading-6 md:leading-8">
                            Tanya adalah fitur dari BelajarCerdas.id yang mempermudah siswa mendapatkan jawaban atas pertanyaan mereka. Siswa
                            yang
                            login setiap hari akan menerima 10 koin gratis, dan setiap pertanyaan yang diajukan juga menambah 1 koin. Dengan
                            hanya 5
                            koin per pertanyaan, siswa bisa belajar lebih efektif dan interaktif.
                        </p>
                        <p class="mt-4 text-gray-600 text-sm md:text-base leading-6 md:leading-8">
                            Jika membutuhkan tambahan koin, siswa dapat membelinya dengan harga Rp3.000 per koin.* Dengan Tanya, siswa dapat
                            belajar
                            kapan saja dengan bimbingan langsung dari tim ahli kami.
                        </p>
                    </div>
                    <div class="absolute w-full h-full flex flex-col right-[-100%] duration-700 ease-out" id="haloGurServiceContent">
                        <p class="text-gray-600 text-sm md:text-base leading-6 md:leading-8">
                            Halo Guru adalah layanan yang menghubungkan siswa dengan mentor secara langsung melalui chat. Siswa dan mentor dapat
                            mendaftar secara mandiri, dan siswa memiliki kebebasan untuk memilih mentor dari daftar yang tersedia. Dengan
                            pendekatan
                            crowdsource, pertanyaan siswa dijawab secara cepat dan akurat.
                        </p>
                        <p class="mt-4 text-gray-600 text-sm md:text-base leading-6 md:leading-8">
                            Layanan ini dirancang untuk memberikan pengalaman belajar yang fleksibel dan personal, memastikan setiap siswa
                            mendapatkan bimbingan sesuai kebutuhan mereka. Halo Guru adalah solusi tepat untuk belajar efektif dengan bimbingan
                            ahli
                            kapan saja.
                        </p>
                    </div>
                    <div class="absolute w-full h-full flex flex-col right-[-100%] duration-700 ease-out" id="englishZoneServiceContent">
                        <p class="text-gray-600 text-sm md:text-base leading-6 md:leading-8">
                            English Zone adalah layanan pembelajaran bahasa Inggris yang menghubungkan siswa dengan mentor pilihan mereka. Siswa
                            dan
                            mentor dapat mendaftar secara mandiri, dan interaksi berlangsung melalui Zoom dengan durasi 90 menit per sesi.
                        </p>
                        <p class="mt-4 text-gray-600 text-sm md:text-base leading-6 md:leading-8">
                            Dirancang untuk mendukung kemajuan dalam keterampilan bahasa Inggris, English Zone memberikan pengalaman belajar
                            yang
                            interaktif, fleksibel, dan fokus pada pencapaian hasil terbaik.
                        </p>
                    </div>
                    <div class="absolute w-full h-full flex flex-col right-[-100%] duration-700 ease-out" id="bimbelMapelUmumServiceContent">
                        <p class="text-gray-600 text-sm md:text-base leading-6 md:leading-8">
                            Bimbel Umum menyediakan berbagai mata pelajaran, mulai dari sains, sosial, hingga bahasa, yang dirancang untuk
                            memenuhi
                            kebutuhan belajar siswa. Dengan pembelajaran online yang fleksibel, siswa dapat mengakses video pembelajaran
                            interaktif,
                            modul yang komprehensif, dan latihan soal untuk memperdalam pemahaman.
                        </p>
                        <p class="mt-4 text-gray-600 text-sm md:text-base leading-6 md:leading-8">
                            Layanan ini memberikan pengalaman belajar yang terstruktur dan mendukung siswa mencapai prestasi terbaik mereka di
                            berbagai bidang akademik.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var tanyaServices = document.getElementById('tanyaServiceContent');
    var haloGurServices = document.getElementById('haloGurServiceContent');
    var englishZoneServices = document.getElementById('englishZoneServiceContent');
    var bimbelMapelUmumServices = document.getElementById('bimbelMapelUmumServiceContent');

    function tanyaServiceContent() {
        tanyaServices.style.right = "0%";
        haloGurServices.style.right = "-100%";
        englishZoneServices.style.right = "-100%";
        bimbelMapelUmumServices.style.right = "-100%";
    }

    function haloGurServiceContent() {
        haloGurServices.style.right = "0%";
        tanyaServices.style.right = "-100%";
        englishZoneServices.style.right = "-100%";
        bimbelMapelUmumServices.style.right = "-100%";
    }

    function englishZoneServiceContent() {
        englishZoneServices.style.right = "0%";
        tanyaServices.style.right = "-100%";
        haloGurServices.style.right = "-100%";
        bimbelMapelUmumServices.style.right = "-100%";
    }

    function bimbelMapelUmumServiceContent() {
        bimbelMapelUmumServices.style.right = "0%";
        tanyaServices.style.right = "-100%";
        haloGurServices.style.right = "-100%";
        englishZoneServices.style.right = "-100%";
    }
</script>
{{-- <script>
    $('.show-password').on('click', function() {
        if ($('#password').attr('type') == 'password') {
            $('#password').attr('type', 'text');
            $('#password-lock').attr('class', 'fas fa-unlock');
        } else {
            $('#password').attr('type', 'password');
            $('#password-lock').attr('class', 'fas fa-lock');
        }
    })
</script> --}}
