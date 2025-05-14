<x-layout></x-layout>

<main>
    <section class="">
        <!--- tampilan diluar mobile ---->
        <div class="hidden md:block">
            <!-- Teks Kiri -->
            <div class="relative grid grid-cols-1 md:grid-cols-2 gap-10">
                <!-- Kolom kiri -->
                <div class="flex flex-col justify-center gap-6 pl-10 lg:pl-20">
                    <span class="md:text-5xl text-6xl text-[#00B2FF] font-bold">SISWA</span>
                    <div class="w-full xl:max-w-4xl flex flex-wrap mt-[1px]">
                        <span class="text-justify lg:leading-9 xl:leading-9 text-lg lg:text-xl xl:text-2xl">
                            Di belajarcerdas.id, siswa melatih kemandirian dan tanggung jawab melalui materi interaktif,
                            mengembangkan keterampilan penting untuk masa depan, sambil dapat memilih beragam program
                            belajar
                            yang sesuai dengan kebutuhan mereka di platform kami.
                        </span>
                    </div>
                    <a href="{{ route('daftar.siswa') }}">
                        <button
                            class="w-max bg-[#00B2FF] py-2 px-6 rounded-full shadow-md transition-all cursor-pointer text-white font-bold hover:scale-105">
                            DAFTAR SEKARANG
                        </button>
                    </a>
                </div>

                <!-- Kolom kanan: Gambar -->
                <div class="flex justify-center mt-10 md:mt-0">
                    <img src="{{ asset('image/services-pages/siswa/Image 3 anak.png') }}" alt="Murid Belajar"
                        class="max-w-xs lg:max-w-md object-cover min-w-[250px]">

                </div>
                <div
                    class="absolute md:bottom-[-247px] lg:bottom-[-249px] xl:bottom-[-163px] w-[83%] lg:w-[80%] ml-20 lg:ml-32 border-4 rounded-2xl border-[#00B2FF] pb-4">
                    <h2 class="font-bold text-center my-4">Pilih program yang cocok untukmu:</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 px-10 max-w-full">
                        @foreach (['TANYA', 'ENGLISH ZONE', 'SOAL SNBT', 'SOAL UMUM', 'SOAL OSN'] as $program)
                            <div
                                class="flex items-center md:gap-2 xl:gap-4 w-full h-16 justify-center border-2 rounded-xl border-[#00B2FF] hover:shadow-lg transition
                                mx-auto hover:bg-[#00B2FF] hover:text-white">
                                <img src="{{ asset('image/science-icon.png') }}" alt="" class="w-8">
                                <span
                                    class="font-bold md:text-sm lg:text-md xl:text-md text-center">{{ $program }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <!--- tampilan mobile ---->
        <div class="md:hidden">
            <div class="relative grid grid-cols-1 md:grid-cols-2">
                <!-- Gambar Atas -->
                <div class="flex flex-col justify-center gap-6 xl:pl-20">
                    <div class="flex justify-center md:mt-0 z-[-20]">
                        <img src="{{ asset('image/services-pages/siswa/Image 3 anak.png') }}" alt="Murid Belajar"
                            class="max-w-xs lg:max-w-md object-cover">
                    </div>
                </div>
                <!-- Teks Bawah -->
                <div class="flex flex-col justify-center gap-6 xl:pl-20 mt-6 mx-4">
                    <span class="text-4xl text-[#00B2FF] font-bold flex flex-start">SISWA</span>
                    <div class="w-full flex flex-wrap mt-[1px]">
                        <span class="text-justify text-md">Di belajarcerdas.id, siswa melatih
                            kemandirian
                            dan
                            tanggung jawab
                            melalui
                            materi interaktif,
                            mengembangkan keterampilan penting untuk masa depan, sambil dapat memilih beragam program
                            belajar
                            yang sesuai dengan kebutuhan mereka di platform kamui
                        </span>
                    </div>
                    <a href="{{ route('daftar.siswa') }}">
                        <button
                            class="text-start w-max bg-[#00B2FF] py-2 px-6 rounded-full shadow-md transition-all cursor-pointer text-white font-bold hover:scale-105">
                            DAFTAR SEKARANG
                        </button>
                    </a>
                </div>
            </div>
            <div class="w-[90%] border-4 rounded-3xl border-[#00B2FF] pb-4 mx-6 mt-20 mb-10 bg-white shadow-lg">
                <h2 class="font-bold text-center my-4">Pilih program yang cocok untukmu:</h2>
                <div class="grid grid-cols-1 gap-6 px-10 max-w-full">
                    @foreach (['TANYA', 'ENGLISH ZONE', 'SOAL SNBT', 'SOAL UMUM', 'SOAL OSN'] as $program)
                        <div
                            class="flex items-center gap-4 w-full h-16 justify-center border-2 rounded-xl border-[#00B2FF] hover:shadow-lg hover:bg-[#00B2FF] hover:text-white transition mx-auto">
                            <div class="grid grid-cols-2 w-full h-full items-center gap-2 mr-10">
                                <div class="col-span-1 flex justify-end">
                                    <img src="{{ asset('image/science-icon.png') }}" alt="" class="w-8">
                                </div>
                                <div class="col-span-1">
                                    <span class="font-bold">{{ $program }}</span>
                                </div>
                            </div>
                            {{-- <span class="font-bold lg:text-md xl:text-md text-center">{{ $program }}</span> --}}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</main>
