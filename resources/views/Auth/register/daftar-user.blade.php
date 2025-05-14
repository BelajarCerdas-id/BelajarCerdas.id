<x-script></x-script>

<div class="w-full h-screen bg-[url('/image/register/background-register.png')] bg-cover">
    <!-- Konten di atas background -->
    <div class="min-h-screen flex items-center justify-center mx-8">
        <div class="text-center">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-10">Daftar Sebagai... .</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-[400px] md:max-w-3xl h-[900px] md:h-[470px] mx-auto">
                <!-- Kartu Murid -->
                <a href="{{ route('daftar.siswa') }}"
                    class="bg-white rounded-xl border-[8px] border-[#ffE588] p-6 hover:scale-[1.02] transition-transform shadow-md relative">
                    <h2 class="text-[#ffE588] text-5xl font-bold mb-2 text-center">Murid</h2>
                    <p class="text-[#ffE588] text-center text-lg mb-4 font-bold mt-8">
                        Nikmati pengalaman belajar yang seru, praktis, dan penuh tantangan baru!
                    </p>
                    <img src="{{ asset('image/register/anak.png') }}" alt="Murid"
                        class="w-80 mx-auto absolute bottom-0 md:right-2 right-8" />
                </a>

                <!-- Kartu Guru -->
                <a href="{{ route('daftar.mentor') }}"
                    class="bg-white rounded-xl border-[8px] border-[#5EF2D5] p-6 hover:scale-[1.02] transition-transform shadow-md relative">
                    <h2 class="text-[#5EF2D5] text-5xl font-bold mb-2 text-center">Mentor</h2>
                    <p class="text-[#5EF2D5] text-center  text-lg mb-4 font-bold mt-8">
                        Berbagi ilmu, membangun masa depan, sambil menambah pemasukan.
                    </p>
                    <img src="{{ asset('image/register/mentor.png') }}" alt="Guru"
                        class="w-80 mx-auto absolute bottom-0 md:right-2" />
                </a>
            </div>
        </div>
    </div>
</div>
