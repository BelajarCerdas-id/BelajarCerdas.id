<x-navbar></x-navbar>

<!-- ALERT TANYA ACCESS UNTUK ROLE SELAIN SISWA -->
@if (session('error-access-feature-purchase-view'))
    <script>
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "{{ session('error-access-feature-purchase-view') }}",
        });
    </script>
@endif

<main>
    <div class="flex flex-col md:flex-row relative mt-32 lg:mt-26 lg:h-[90lvh]">
        <!-- TEXT -->
        <div
            class="flex flex-col order-2 md:order-1 justify-center w-full px-4 sm:px-8 xl:pl-[40px] gap-8 lg:gap-16 text-center md:text-left">
            <div>
                <span
                    class="text-[#0071BD] text-[30px] sm:text-[36px] md:text-[45px] xl:text-[65px] font-volterounded font-semibold leading-tight">
                    Belajar itu bukan<br>
                    hanya serius,
                    tapi juga
                </span>

                <p
                    class="text-[#29AAE1] text-[30px] sm:text-[36px] md:text-[50px] xl:text-[65px] font-semibold font-poppins lg:mt-2">
                    menyenangkan
                </p>
            </div>

            <div class="flex flex-col gap-4 lg:gap-6 items-center md:items-start relative -top-4">
                <span class="max-w-md">
                    Yuk gabung bareng ribuan pelajar yang<br>
                    sudah belajar cerdas dan fun bareng kami!
                </span>

                <a href="{{ route('daftar.user') }}">
                    <button class="bg-[#29AAE1] hover:bg-[#1f9ad3] text-white font-semibold h-11 w-40 rounded-lg">
                        Daftar Sekarang
                    </button>
                </a>
            </div>
        </div>

        <!-- wave book hero (HIDE DI MOBILE) -->
        <div class="absolute bottom-27 md:bottom-0 lg:bottom-24 left-[67%] md:left-[40%] lg:left-[47%] -z-10">
            <img src="{{ asset('image/homepage/vector-hero-wave-book.svg') }}" alt="">
        </div>

        <!-- IMAGE -->
        <div class="w-full order-1 md:order-2 flex items-center justify-center mb-8 lg:mb-0">
            <img src="{{ asset('image/homepage/illustration-hero-online-learning.svg') }}" alt="no-image"
                class="w-full max-w-sm sm:max-w-md lg:max-w-none lg:h-[600px] pointer-events-none">
        </div>
    </div>

    <div class="w-full h-[800px] lg:h-[800px] bg-[#E1F6FF] shadow-lg relative overflow-hidden">
        <!-- Heading -->
        <div class="pt-10 text-center z-10 relative">
            <p
                class="text-[#0071BD] text-[25px] sm:text-[30px] md:text-[35px] lg:text-[50px] font-semibold leading-tight">
                Belajar <span class="text-[#29AAE1]">online</span> yang vibes nya<br>
                kayak <span class="text-[#29AAE1]">privat</span>
            </p>
        </div>

        <!-- Background SVG -->
        <img src="{{ asset('image/homepage/wave-vector.svg') }}" alt=""
            class="jumbotoron-wave-background absolute bottom-0 md:top-[500px] lg:top-[410px] left-0 w-full pointer-events-none z-1">

        <!-- Konten Mobile -->
        <div class="sm:hidden bg-[#E1F6FF] px-4 py-20 relative h-full">

            <div class="flex flex-col gap-20 max-w-[360px] mx-auto">

                <!-- SECTION 1 -->
                <div class="flex flex-col items-center">
                    <!-- Bubble -->
                    <img src="{{ asset('image/homepage/bubble-learning-hangout.svg') }}" alt=""
                        class="w-[200px]" />

                    <!-- LIST WRAPPER (INI KUNCINYA) -->
                    <div class="w-full mt-4">
                        <ul class="list-disc list-outside pl-5 space-y-2 text-sm font-medium">
                            <li>Belajar bareng teman sekelas</li>
                            <li>Bisa kenalan teman se-Indonesia</li>
                            <li>
                                Bareng mentor yang chill, suasana jadi<br>
                                santai dan nyaman yang bikin kita bahagia
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- SECTION 2 -->
                <div class="flex flex-col items-center">
                    <!-- Bubble -->
                    <img src="{{ asset('image/homepage/bubble-student-smart.svg') }}" alt=""
                        class="w-[190px]" />

                    <!-- LIST WRAPPER (SAMA PERSIS) -->
                    <div class="w-full mt-4 z-10">
                        <ul class="list-disc list-outside pl-5 space-y-2 text-sm font-medium">
                            <li>
                                Mentor berpengalaman bikin materi<br>
                                mudah dipahami
                            </li>
                            <li>Murid belajar secara aktif</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Ilustrasi -->
            <div class="absolute left-2 bottom-32 z-10">
                <img src="{{ asset('image/homepage/woman-image.svg') }}" class="h-[100px]">
            </div>

            <div class="absolute right-2 bottom-32 z-10">
                <img src="{{ asset('image/homepage/man-image.svg') }}" class="h-[160px]">
            </div>
        </div>

        <!-- Konten Grid Tablet -->
        <div class="hidden sm:block lg:hidden py-14 relative z-10 h-full">

            <div class="grid grid-cols-2 gap-14 max-w-5xl px-10 mx-auto">

                <!-- KIRI -->
                <div class="flex flex-col items-start pt-20 md:pt-14">
                    <img src="{{ asset('image/homepage/bubble-learning-hangout.svg') }}" class="w-[260px] md:w-[360px]">

                    <ul class="list-disc list-outside pl-10 space-y-3 text-[15px] font-medium">
                        <li>Belajar bareng teman sekelas</li>
                        <li>Bisa kenalan teman se-Indonesia</li>
                        <li>
                            Bareng mentor yang chill, suasana jadi<br>
                            santai dan nyaman yang bikin kita bahagia
                        </li>
                    </ul>
                </div>

                <!-- KANAN -->
                <div class="flex flex-col items-start">
                    <img src="{{ asset('image/homepage/bubble-student-smart.svg') }}" class="w-[240px] md:w-[340px]">

                    <ul class="list-disc list-outside pl-5 space-y-3 text-[15px] font-medium">
                        <li>
                            Mentor berpengalaman bikin materi<br>
                            mudah dipahami
                        </li>
                        <li>Murid belajar secara aktif</li>
                    </ul>
                </div>
            </div>

            <!-- Ilustrasi -->
            <div class="absolute w-full bottom-36 flex justify-center items-end z-20 pointer-events-none">
                <img src="{{ asset('image/homepage/woman-image.svg') }}" class="h-[160px]">
                <img src="{{ asset('image/homepage/man-image.svg') }}" class="h-[260px]">
            </div>
        </div>


        <!-- Konten Grid Dekstop -->
        <div class="hidden lg:block">
            <div class="grid grid-cols-2 gap-14 w-full h-full px-10">
                <div class="flex flex-col  items-center lg:pt-[150px] z-10">
                    <img src="{{ asset('image/homepage/bubble-learning-hangout.svg') }}"
                        class="w-[450px] mb-2 lg:translate-x-0 xl:translate-x-4">

                    <ul class="list-disc list-outside pl-32 space-y-3 text-[15px] font-semibold opacity-70">
                        <li>Belajar bareng teman sekelas</li>
                        <li>Bisa kenalan teman se-Indonesia</li>
                        <li>
                            Bareng mentor yang chill, suasana jadi<br>
                            santai dan nyaman yang bikin kita bahagia
                        </li>
                    </ul>
                </div>

                <div class="flex flex-col items-start lg:pt-[90px] translate-x-6">
                    <img src="{{ asset('image/homepage/bubble-student-smart.svg') }}" class="w-[400px] mb-2">

                    <ul class="list-disc list-outside pl-12 space-y-3 text-[15px] font-semibold opacity-70">
                        <li>
                            Mentor berpengalaman bikin materi<br>
                            mudah dipahami
                        </li>
                        <li>Murid belajar secara aktif</li>
                    </ul>
                </div>
            </div>

            <!-- Ilustrasi -->
            <div
                class="hidden absolute w-full bottom-2 -translate-x-10 lg:flex justify-center items-end z-10 pointer-events-none">
                <img src="{{ asset('image/homepage/woman-image.svg') }}" class="h-[160px]">
                <img src="{{ asset('image/homepage/man-image.svg') }}" class="h-[260px]">
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-6 bg-white py-20">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 items-stretch">
            @foreach ($features as $item)
                @if ($item->nama_fitur !== 'LMS')
                    <!-- cards -->
                    <div
                        class="bg-[#E6F7FF] rounded-xl shadow-md flex flex-col h-full transition hover:-translate-y-2 duration-300">

                        <div class="flex justify-center px-6 pt-6">
                            <img src="{{ $descriptionsFeatures[$item->nama_fitur]['image_feature'] ?? '' }}"
                                loading="lazy" alt="Logo Fitur {{ $item->nama_fitur }}"
                                class="w-[166px] h-[112px] object-contain pointer-events-none">
                        </div>

                        <ul class="text-sm text-gray-700 space-y-2 leading-relaxed p-6 flex-1">
                            @foreach ($descriptionsFeatures[$item->nama_fitur]['descriptions'] ?? [] as $desc)
                                <li class="desc-option-list">
                                    <input type="checkbox" name="desc-feature" checked>
                                    <label class="option-desc">
                                        <span>{{ $desc }}</span>
                                    </label>
                                </li>
                            @endforeach
                        </ul>

                        <div class="text-center bg-[#AEE7FF] mt-auto py-4">
                            <p class="text-sm font-bold text-[#0071BD]">Mulai dari</p>
                            <p class="text-lg font-bold text-[#0071BD] my-3">
                                {{ $descriptionsFeatures[$item->nama_fitur]['price'] ?? '' }}
                            </p>

                            <a href="{{ route('paymentFeaturesView', $item->nama_fitur) }}"
                                class="w-full flex justify-center" aria-label="{{ $item->nama_fitur }}">
                                <button
                                    class="bg-[#29AAE1] hover:bg-[#1f9ad3] text-white px-6 py-2 rounded-lg text-sm font-semibold transition">
                                    {{ $descriptionsFeatures[$item->nama_fitur]['textButton'] ?? '' }}
                                </button>
                            </a>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <div class="bg-[#E6F7FF] w-full">
        <div
            class="max-w-[1200px] mx-auto px-4 sm:px-6 md:px-4 lg:px-6 py-16 lg:py-0 min-h-[520px] grid grid-cols-1 md:grid-cols-2 items-center md:gap-4 lg:gap-16">

            <!-- LEFT CONTENT -->
            <div
                class="w-full h-full min-h-[420px] md:min-h-[480px] relative text-center md:text-left order-2 md:order-1 flex flex-col justify-center">

                <p
                    class="text-[#0071BD] text-[30px] md:text-[20px] font-semibold mb-4 md:pl-7 relative -top-10 md:top-0">
                    Ada Istilah
                </p>

                <div class="relative inline-block mx-auto lg:mx-0 -top-10 md:top-0">
                    <img src="{{ asset('image/homepage/bg-speech-bubble.svg') }}" alt=""
                        class="w-full max-w-[360px] sm:max-w-[360px] md:max-w-[470px] lg:max-w-none">

                    <div
                        class="absolute inset-0 flex items-center justify-center -translate-y-2 lg:-translate-y-2.5 xl:-translate-y-3">
                        <div
                            class="relative text-[#0071BD] font-bold leading-snug
                                    text-[32px] sm:text-[32px] md:text-[35px]
                                    lg:text-[50px] xl:text-[54px]">

                            <span class="absolute -left-4 -top-1 sm:-left-5 lg:-left-6">"</span>

                            <div class="flex flex-col items-start">
                                <span>Tak kenal maka</span>
                                <span>tak sayang"</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="mt-4 flex justify-center md:justify-start w-full text-[#0071BD] font-semibold relative md:static -top-20 md:top-0">
                    <div
                        class="flex flex-col items-start text-[20px] lg:text-[22px] absolute top-[58%] md:top-[57%] lg:top-[60%] md:left-4 lg:left-8 -translate-x-6 sm:-translate-x-6 md:-translate-x-0">
                        <span>Biar kamu jadi sayang</span>
                        <span>Yuk Kenalan dulu</span>
                    </div>
                </div>


                <a href="#" class="mt-6 sm:mt-8 w-full translate-y-2">
                    <button
                        class="inline-flex items-center gap-2 bg-[#29AAE1] hover:bg-[#1f9ad3] text-white text-base lg:text-xl font-semibold w-max md:w-full px-6 py-3 rounded-full transition">
                        Cari Mentor Kesayanganmu Disini
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </a>
            </div>

            <!-- RIGHT ILLUSTRATION -->
            <div class="w-full flex justify-center order-1 md:order-2">
                <img src="{{ asset('image/homepage/illustration-mascot-books.svg') }}" alt="Ilustrasi Mentor"
                    class="w-full max-w-[280px] sm:max-w-[360px] md:max-w-none lg:max-w-[520px]">
            </div>
        </div>
    </div>

    <footer class="relative bg-[#29AAE1] text-white">
        <!-- Main footer -->
        <div class="px-6 md:px-12 lg:px-20 py-20 grid grid-cols-1 md:grid-cols-2 gap-10">

            <!-- Company -->
            <div class="">
                <h3 class="text-xl font-bold mb-4">
                    PT Belajar Cerdas Lintas Media
                </h3>
                <p class="text-sm opacity-90 mb-4 min-w-83 xl:w-[446px]">
                    MTH Square Apartment & Office Tower, Jl. Otista Raya No.390 6, RT.6/RW.12, Bidara Cina, Kec.
                    Jatinegara,
                    Kota Jakarta Timur, DKI Jakarta 13330 (dummy address)
                </p>

                <div class="text-sm flex items-center gap-6">
                    <p class="flex items-center gap-2">
                        <i class="fa-regular fa-envelope"></i>
                        <a href="#" class="hover:underline">
                            info@belajarcerdas.id
                        </a>
                    </p>
                    <p class="flex items-center gap-2">
                        <i class="fa-solid fa-phone"></i>
                        <a href="#" class="hover:underline">
                            021 000 0000
                        </a>
                    </p>
                </div>

                <!-- Social icons -->
                <div class="flex gap-4 mt-6 text-2xl">
                    <a href="#" class="hover:opacity-80">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="hover:opacity-80">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="hover:opacity-80">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="hover:opacity-80">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>

            <!-- WRAPPER MENU FOOTER -->
            <div class="grid grid-cols-3 gap-8">

                <!-- Layanan -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Layanan</h3>
                    <ul class="space-y-3 text-sm opacity-90">
                        <li><a href="#" class="hover:underline"></a></li>
                        <li><a href="#" class="hover:underline">Lorem ipsum</a></li>
                        <li><a href="#" class="hover:underline">Lorem ipsum</a></li>
                        <li><a href="#" class="hover:underline">Lorem ipsum</a></li>
                        <li><a href="#" class="hover:underline">Lorem ipsum</a></li>
                    </ul>
                </div>

                <!-- Tentang Kami -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Tentang Kami</h3>
                    <ul class="space-y-3 text-sm opacity-90">
                        <li><a href="#" class="hover:underline">Belajar Cerdas</a></li>
                        <li><a href="#" class="hover:underline">Blog</a></li>
                        <li><a href="#" class="hover:underline">Bantuan</a></li>
                        <li><a href="#" class="hover:underline">Karir</a></li>
                    </ul>
                </div>

                <!-- Info -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Info</h3>
                    <ul class="space-y-3 text-sm opacity-90">
                        <li><a href="#" class="hover:underline">Hubungi Kami</a></li>
                        <li><a href="#" class="hover:underline">Partnership</a></li>
                        <li><a href="#" class="hover:underline">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="hover:underline">Kebijakan Privasi</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Bottom -->
        <div class="border-t border-white/30">
            <div class="w-full flex justify-center px-6 py-4 text-sm opacity-90">
                © 2025 PT Belajar Cerdas Lintas Media. All rights reserved.
            </div>
        </div>
    </footer>
</main>
