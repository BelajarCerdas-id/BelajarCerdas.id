<x-navbar></x-navbar>
<main>
    <div class="jumbotron-mitra-cerdas relative hidden lg:block">
        <!-- Background SVG -->
        <img src="{{ asset('image/services-pages/mitra-cerdas/gelombang_svg.svg') }}" alt=""
            class="jumbotoron-wave-background absolute top-0 left-0 w-full pointer-events-none">

        <!-- Konten Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-10 h-full">
            <!-- Teks dan Tombol -->
            <div class="w-full h-full description-btn-register-mitra-cerdas relative">

                <div class="relative flex flex-col justify-center gap-6 max-w-4xl">
                    <h1 class="font-bold opacity-70">
                        MITRA CERDAS
                    </h1>
                    <span class="max-w-2xl text-gray-700 mx-auto md:mx-0 leading-relaxed">
                        Mitra Cerdas adalah Guru profesional ataupun individu dewasa yang memiliki keilmuan di bidang
                        pendidikan
                        dan kemampuan sebagai tenaga pengajar.
                    </span>
                    <div>
                        <!--- rounded gradient ---->
                        <div class="absolute w-40 h-40 left-20 bottom-[-100px] z-[-1] rounded-full"
                            style="background: radial-gradient(circle at bottom left, rgba(94, 242, 213, 0.4) 0%, rgba(255,255,255,0) 70%);">
                        </div>

                        <a href="{{ route('daftar.mentor') }}" class="z-10 relative">
                            <button
                                class="bg-[#60B5FF] w-50 h-12 rounded-lg shadow-md text-white font-semibold hover:scale-105 transition-transform">
                                Daftar Mitra Cerdas
                            </button>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Gambar Ilustrasi -->
            <div class="relative flex justify-center items-end gap-4 w-full">
                <img src="{{ asset('image/services-pages/mitra-cerdas/teacher cowok jas kuning.png') }}"
                    alt="Guru laki-laki" class="jumbotron-teacher-man-mitra-cerdas h-auto">

                <img src="{{ asset('image/services-pages/mitra-cerdas/teacher cewek.png') }}" alt="Guru laki-laki"
                    class="jumbotron-teacher-woman-mitra-cerdas h-auto">
            </div>
        </div>
    </div>

    <div class="relative z-10 flex justify-center my-20 mx-4 md:mx-10 px-4 md:px-20">
        <!--- wrapper background ---->
        <img src="{{ asset('image/services-pages/mitra-cerdas/bg kata mitra.png') }}" alt=""
            class="absolute top-0 left-0 w-full h-full z-[-20] rounded-lg pointer-events-none">
        <div class="w-full my-14">
            <!--- header ---->
            <span class="text-2xl sm:text-3xl font-bold flex justify-center opacity-50">Kata Mitra Cerdas</span>
            <!--- SLIDER ---->
            <div class="max-w-6xl mx-auto relative">
                <div class="swiper mySwiper pt-20">
                    <div class="swiper-wrapper">
                        <!--- swiper slide 1 ---->
                        <div class="swiper-slide flex">
                            <figure class="relative bg-white rounded-4xl shadow-md p-6 max-w-[350px]">
                                <div class="w-full flex justify-center">
                                    <div class="w-[120px] absolute top-[-50px]">
                                        <img src="{{ asset('image/services-pages/mitra-cerdas/wahyu.png') }}"
                                            alt="" class="">
                                    </div>
                                </div>
                                <!-- Foto mentor (bulat) -->
                                <div class="mt-14">
                                    <p class="text-sm text-gray-700 mb-4 text-justify max-h-64">
                                        Senang sekali bisa menjadi bagian dari komunitas mitra Belajar Cerdas!
                                        Program
                                        ini
                                        memberikan kesempatan untuk membantu siswa dengan menjawab pertanyaan
                                        mereka,
                                        menjelaskan materi, dan memberikan konsultasi. Selain itu, saya juga
                                        dapat
                                        meningkatkan kemampuan dalam membimbing siswa. Terima kasih, Belajar
                                        Cerdas!
                                    </p>
                                    <h4 class="font-semibold text-black italic text-center">Wahyu Hendana</h4>
                                    <div class="w-16 h-0.5 bg-black mx-auto mt-1"></div>
                                </div>
                            </figure>
                        </div>
                        <!--- swiper slide 2 ---->
                        <div class="swiper-slide flex">
                            <figure class="relative bg-white rounded-4xl shadow-md p-6 max-w-[350px]">
                                <div class="w-full flex justify-center">
                                    <div class="w-[120px] absolute top-[-50px]">
                                        <img src="{{ asset('image/services-pages/mitra-cerdas/danien.png') }}"
                                            alt="" class="">
                                    </div>
                                </div>
                                <div class="mt-14">
                                    <p class="text-sm text-gray-700 mb-4 text-justify max-h-64">
                                        Senang sekali bisa menjadi bagian dari komunitas Belajar Cerdas! Program
                                        ini
                                        memberikan wadah bagi kita untuk ikut mempromosikan Belajar Cerdas,
                                        mendapatkan
                                        keuntungan, serta menambah pengalaman pribadi. Saya juga jadi lebih
                                        memahami
                                        strategi pemasaran dan cara menentukan target yang tepat. Terima kasih,
                                        Belajar
                                        Cerdas!
                                    </p>
                                    <h4 class="font-semibold text-black italic text-center">Danien Haqien</h4>
                                    <div class="w-16 h-0.5 bg-black mx-auto mt-1"></div>
                                </div>
                            </figure>
                        </div>
                        <!--- swiper slide 3 ---->
                        <div class="swiper-slide flex">
                            <figure class="relative bg-white rounded-4xl shadow-md p-6 max-w-[350px]">
                                <div class="w-full flex justify-center">
                                    <div class="w-[120px] absolute top-[-50px]">
                                        <img src="{{ asset('image/services-pages/mitra-cerdas/rahma.png') }}"
                                            alt="" class="">
                                    </div>
                                </div>
                                <div class="mt-14">
                                    <p class="text-sm text-gray-700 mb-4 text-justify max-h-64">
                                        Bergabung sebagai mitra di Belajar Cerdas memberi saya banyak manfaat.
                                        Saya
                                        bisa
                                        membantu siswa memahami materi pelajaran dengan lebih baik. Selain itu,
                                        saya
                                        juga
                                        mendapatkan banyak wawasan baru, bertemu dengan rekan sesama mitra, dan
                                        tentunya
                                        menikmati pengalaman seru dalam mendampingi proses belajar siswa!
                                    </p>
                                    <h4 class="font-semibold text-black italic text-center">Rahmawati</h4>
                                    <div class="w-16 h-0.5 bg-black mx-auto mt-1"></div>
                                </div>
                            </figure>
                        </div>
                        <!--- swiper slide 4 ---->
                        <div class="swiper-slide flex">
                            <figure class="relative bg-white rounded-4xl shadow-md p-6 max-w-[350px]">
                                <div class="w-full flex justify-center">
                                    <div class="w-[120px] absolute top-[-50px]">
                                        <img src="{{ asset('image/services-pages/mitra-cerdas/rahma.png') }}"
                                            alt="" class="">
                                    </div>
                                </div>
                                <div class="mt-14">
                                    <p class="text-sm text-gray-700 mb-4 text-justify max-h-64">
                                        Bergabung sebagai mitra di Belajar Cerdas memberi saya banyak manfaat.
                                        Saya
                                        bisa
                                        membantu siswa memahami materi pelajaran dengan lebih baik. Selain itu,
                                        saya
                                        juga
                                        mendapatkan banyak wawasan baru, bertemu dengan rekan sesama mitra, dan
                                        tentunya
                                        menikmati pengalaman seru dalam mendampingi proses belajar siswa!
                                    </p>
                                    <h4 class="font-semibold text-black italic text-center">Rahmawati</h4>
                                    <div class="w-16 h-0.5 bg-black mx-auto mt-1"></div>
                                </div>
                            </figure>
                        </div>
                    </div>
                </div>

                <!-- Pagination & Navigation buttons -->
                <div class="pagination-slider-kata-mitra px-20">

                    <!-- Pagination -->
                    <div class="swiper-pagination absolute !bottom-4 -translate-x-[20px]"></div>

                    <!-- Navigation Buttons -->
                    <div class="button-prev slider-arrow">
                        <div
                            class="absolute bottom-0 left-1/2 -translate-x-[150%] md:translate-x-0 md:bottom-auto md:left-[-70px] md:top-1/2 md:-translate-y-1/2 z-30 bg-[#F35252] w-12.5 h-12 rounded-full transition-all duration-150 active:scale-90 shadow-md active:shadow-inner">
                            <div id="button-slider-prev"
                                class="pointer-events-auto text-white font-bold bg-[#FFE588] w-12 h-12 rounded-full text-4xl
                                    flex items-center justify-center shadow-md">
                                <i class="fas fa-chevron-left"></i>
                            </div>
                        </div>
                    </div>
                    <div class="button-next slider-arrow">
                        <div
                            class="absolute bottom-0 right-1/2 translate-x-[150%] md:translate-x-0 md:bottom-auto md:right-[-70px] md:top-1/2 md:-translate-y-1/2 z-30 bg-[#F35252] w-12.5 h-12 rounded-full transition-all duration-150 active:scale-90 shadow-md active:shadow-inner">
                            <div id="button-slider-next"
                                class="pointer-events-auto text-white font-bold bg-[#FFE588] w-12 h-12 rounded-full text-4xl
                                flex items-center justify-center shadow-md">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section>
        <div class="relative h-max flex justify-center mx-4 md:mx-10 py-4">
            <img src="{{ asset('image/services-pages/mitra-cerdas/bg kata mitra.png') }}"
                class="absolute top-0 left-0 w-full h-full object-cover z-0 rounded-lg pointer-events-none"
                alt="">

            <div class="relative z-10 flex justify-center mt-4 mx-10">
                <div class="w-full">
                    <!-- Semua konten masuk di sini -->
                    <span class="text-2xl sm:text-3xl font-bold flex justify-center opacity-50">Syarat &
                        Ketentuan</span>
                    <!-- S&K 1 --->
                    <div class="bg-white border shadow-lg relative max-w-[1300px] my-8 flex items-center">
                        <div class="flex-grow pr-4 p-2">
                            <div class="flex justify-between mb-4 sm:mb-0">
                                <span class="font-bold text-[14.5px] sm:text-base">Pendaftaran Mitra</span>
                                <div
                                    class="flex-shrink-1 relative top-[10px] left-4 w-[40px] h-full flex items-center sm:hidden">
                                    <img src="{{ asset('image/services-pages/mitra-cerdas/s&k 1.png') }}"
                                        alt=""
                                        class="w-full h-[80px] object-contain absolute pointer-events-none">
                                </div>
                            </div>
                            <p class="text-gray-500 font-bold text-justify">
                                Calon Mitra wajib mendaftar melalui tautan
                                resmi: Https://belajarcerdas.id
                            </p>
                        </div>
                        <div class="hidden sm:block">
                            <div class="flex-shrink-1 relative w-[80px] h-full flex items-center">
                                <img src="{{ asset('image/services-pages/mitra-cerdas/s&k 1.png') }}" alt=""
                                    class="w-full h-[80px] object-contain absolute pointer-events-none">
                            </div>
                        </div>
                    </div>

                    <!-- S&K 2 --->
                    <div class="bg-white border shadow-lg relative max-w-[1300px] my-8 flex items-center">
                        <div class="flex-grow pr-4 p-2">
                            <div class="flex justify-between mb-4 sm:mb-0">
                                <span class="font-bold text-[14.5px] sm:text-base">Dokumen Persyaratan</span>
                                <div
                                    class="flex-shrink-1 relative top-[10px] left-4 w-[40px] h-full flex items-center sm:hidden">
                                    <img src="{{ asset('image/services-pages/mitra-cerdas/s&k 2.png') }}"
                                        alt=""
                                        class="w-full h-[80px] object-contain absolute pointer-events-none">
                                </div>
                            </div>
                            <p class="text-gray-500 font-bold text-justify">
                                Dokumen yang harus disiapkan: Foto buku
                                Tabungan (selain
                                BCA), Foto KTP, dan Foto NPWP.
                            </p>
                        </div>
                        <div class="hidden sm:block">
                            <div class="flex-shrink-1 relative w-[80px] h-full flex items-center">
                                <img src="{{ asset('image/services-pages/mitra-cerdas/s&k 2.png') }}" alt=""
                                    class="w-full h-[80px] object-contain absolute pointer-events-none">
                            </div>
                        </div>
                    </div>

                    <!-- S&K 3 --->
                    <div class="bg-white border shadow-lg relative max-w-[1300px] my-8 flex items-center">
                        <div class="flex-grow pr-4 p-2">
                            <div class="flex justify-between mb-4 sm:mb-0">
                                <span class="font-bold text-[14.5px] sm:text-base">Perubahan Kebijakan</span>
                                <div
                                    class="flex-shrink-1 relative top-[10px] left-4 w-[40px] h-full flex items-center sm:hidden">
                                    <img src="{{ asset('image/services-pages/mitra-cerdas/s&k 3.png') }}"
                                        alt=""
                                        class="w-full h-[80px] object-contain absolute pointer-events-none">
                                </div>
                            </div>
                            <p class="text-gray-500 font-bold text-justify">
                                Belajar Cerdas dapat mengubah S&K
                                sewaktu-waktu. Setiap
                                pembaruan diumumkan di website resmi.
                            </p>
                        </div>
                        <div class="hidden sm:block">
                            <div class="flex-shrink-1 relative w-[80px] h-full flex items-center">
                                <img src="{{ asset('image/services-pages/mitra-cerdas/s&k 3.png') }}" alt=""
                                    class="w-full h-[80px] object-contain absolute pointer-events-none">
                            </div>
                        </div>
                    </div>

                    <!-- S&K 4 --->
                    <div class="bg-white border shadow-lg relative max-w-[1300px] my-8 flex items-center">
                        <div class="flex-grow pr-4 p-2">
                            <div class="flex justify-between mb-4 sm:mb-0">
                                <span class="font-bold text-[14.5px] sm:text-base">Hak Pengelola Program</span>
                                <div
                                    class="flex-shrink-1 relative top-[10px] left-4 w-[40px] h-full flex items-center sm:hidden">
                                    <img src="{{ asset('image/services-pages/mitra-cerdas/s&k 4.png') }}"
                                        alt=""
                                        class="w-full h-[80px] object-contain absolute pointer-events-none">
                                </div>
                            </div>
                            <p class="text-gray-500 font-bold text-justify">
                                Belajar Cerdas berhak membatalkan, mengubah, atau memberhentikan program, serta menarik
                                komisi atau menangguhkan status Mitra jika ditemukan pelanggaran, dengan pemberitahuan
                                sebelumnya.
                            </p>
                        </div>
                        <div class="hidden sm:block">
                            <div
                                class="flex-shrink-1 relative top-[-10px] sm:top-[0.5px] right-0 left-[10px] sm:left-[-2px] lg:left-[2px] w-[80px] h-full flex items-center">
                                <img src="{{ asset('image/services-pages/mitra-cerdas/s&k 4.png') }}" alt=""
                                    class="w-full h-[80px] lg:h-[90px] object-contain absolute pointer-events-none">
                            </div>
                        </div>
                    </div>

                    <!-- S&K 5 --->
                    <div class="bg-white border shadow-lg relative max-w-[1300px] my-8 flex items-center">
                        <div class="flex-grow pr-4 p-2">
                            <div class="flex justify-between mb-4 sm:mb-0">
                                <span class="font-bold text-[14.5px] sm:text-base">Pembatasan Tanggung Jawab</span>
                                <div
                                    class="flex-shrink-1 relative top-[10px] left-4 w-[40px] h-full flex items-center sm:hidden">
                                    <img src="{{ asset('image/services-pages/mitra-cerdas/s&k 5.png') }}"
                                        alt=""
                                        class="w-full h-[80px] object-contain absolute pointer-events-none">
                                </div>
                            </div>
                            <p class="text-gray-500 font-bold text-justify">
                                Mitra tidak diperbolehkan mengalihkan atau
                                memindahkan
                                hak
                                dan kewajibannya tanpa izin tertulis.
                            </p>
                        </div>
                        <div class="hidden sm:block">
                            <div class="flex-shrink-1 relative w-[80px] h-full flex items-center">
                                <img src="{{ asset('image/services-pages/mitra-cerdas/s&k 5.png') }}" alt=""
                                    class="w-full h-[80px] object-contain absolute pointer-events-none">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script src="{{ asset('js/swiper-js/swiper-kata-mitra-cerdas.js') }}"></script>
