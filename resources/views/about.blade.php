<x-navbar></x-navbar>

<main>
    <!-- Meet The Founder -->
    <section>
        <article class="relative flex justify-center items-center mx-6 my-40">
            <!-- Main Content -->
            <div class="relative rounded-lg shadow-xl border-[1px] border-gray-200 w-full h-auto p-6 md:p-10">
                <!-- Icon Top Element -->
                <div class="absolute right-4 top-10 md:right-10 md:top-8">
                    <div class="relative w-8 md:w-12">
                        <!-- Diagonal Line 1 -->
                        <div class="absolute inset-0 bg-[--color-default] rotate-[70deg] w-full h-2 md:h-3"></div>
                        <!-- Diagonal Line 2 -->
                        <div class="absolute inset-0 bg-[--color-default] rotate-[-70deg] w-full h-2 md:h-3"></div>
                    </div>
                </div>
                <!-- Icon Bottom Element -->
                <div class="absolute right-4 bottom-10 md:right-10 md:bottom-12">
                    <div class="relative w-8 md:w-12">
                        <!-- Diagonal Line 1 -->
                        <div class="absolute inset-0 bg-[--color-default] w-full h-2 md:h-3"></div>
                        <!-- Diagonal Line 2 -->
                        <div class="absolute inset-0 bg-[--color-default] rotate-[45deg] w-full h-2 md:h-3"></div>
                    </div>
                </div>
                <!-- Icon left Bottom Element -->
                <div class="absolute left-8 bottom-10 md:left-10 md:bottom-10">
                    <div class="relative w-8 md:w-12">
                        <!-- Diagonal Line 1 -->
                        <div class="absolute inset-0 bg-[--color-default] rotate-[70deg] w-full h-2 md:h-3"></div>
                        <!-- Diagonal Line 2 -->
                        <div class="absolute inset-0 bg-[--color-default] rotate-[-70deg] w-full h-2 md:h-3"></div>
                    </div>
                </div>
                <!-- Left Side: Image -->
                <div class="relative grid grid-cols-1 xl:grid-cols-2 gap-6 h-auto py-20">
                    <!-- Left Content -->
                    <figure class="flex justify-center items-center">
                        {{-- <div class="w-80 h-full bg-[#60b5cf] rounded-[14%]"></div> --}}
                        <img class="w-80 h-full"
                            src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                            alt="">
                    </figure>
                    <!-- Right Side: Text Content -->
                    <div class="flex flex-col justify-center relative">
                        <div class="mb-10">
                            <h2 class="text-4xl md:text-xl absolute top-0 md:top-6 flex flex-col gap-4">
                                <span class="text-[#60b5cf] font-bold text-2xl">Meet the founder</span>
                                <span class="font-bold text-3xl lg:text-4xl">Kunto Widyasmoro</span>
                            </h2>
                        </div>
                        <div class="mt-14 md:mt-20">
                            <p class="text-gray-600 text-sm md:text-base leading-6 md:leading-8 text-justify">
                                BelajarCerdas.id lahir dari visi Kunto Widyasmoro, seorang ahli IT yang berkomitmen
                                untuk menciptakan solusi pendidikan
                                modern yang relevan dengan kebutuhan zaman. Dengan latar belakang yang kuat dalam
                                pengembangan teknologi, ia merancang
                                platform ini untuk menghubungkan dunia pembelajaran online dan offline secara seamless.
                            </p>
                        </div>
                        <div class="mt-4">
                            <p class="text-gray-600 text-sm md:text-base leading-6 md:leading-8 text-justify">
                                Dedikasinya untuk memajukan pendidikan tidak hanya berfokus pada siswa, tetapi juga
                                memberdayakan guru agar dapat
                                menciptakan pengalaman belajar yang lebih baik. Melalui BelajarCerdas.id, Kunto
                                Widyasmoro ingin memastikan bahwa setiap
                                individu, di mana pun mereka berada, memiliki akses ke pembelajaran yang berkualitas.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </section>

    <!-- Meet The Our Best Team -->
    <section class="flex justify-center mx-6 mb-20">
        <div
            class="bg-white py-24 sm:py-32 rounded-lg shadow-xl border-[1px] border-gray-200 w-full h-auto p-6 md:p-10">
            <div class="mx-auto grid max-w-7xl gap-20 px-6 lg:px-8 xl:grid-cols-3">
                <div class="max-w-6xl">
                    <h2 class="font-semibold tracking-tight text-pretty text-gray-900 text-3xl">Meet our
                        leadership</h2>
                    <p class="mt-6 text-lg/8 text-gray-600 text-justify">
                        Kami bangga memiliki tim yang terdiri dari individu-individu kompeten dari berbagai latar
                        belakang dan keahlian. Setiap
                        anggota tim kami tidak hanya memiliki pengalaman mendalam di bidangnya masing-masing, tetapi
                        juga memahami dunia
                        pendidikan dengan sangat baik.
                    </p>
                </div>
                <ul role="list" class="grid gap-x-8 gap-y-12 sm:grid-cols-2 sm:gap-y-16 xl:col-span-2">
                    <li>
                        <div class="flex items-center gap-x-6">
                            <img class="size-16 rounded-full"
                                src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                alt="">
                            <div>
                                <h3 class="text-base/7 font-semibold tracking-tight text-gray-900">Leslie Alexander</h3>
                                <p class="text-sm/6 font-semibold text-indigo-600">[Position]</p>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center gap-x-6">
                            <img class="size-16 rounded-full"
                                src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                alt="">
                            <div>
                                <h3 class="text-base/7 font-semibold tracking-tight text-gray-900">Leslie Alexander</h3>
                                <p class="text-sm/6 font-semibold text-indigo-600">[Position]</p>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center gap-x-6">
                            <img class="size-16 rounded-full"
                                src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                alt="">
                            <div>
                                <h3 class="text-base/7 font-semibold tracking-tight text-gray-900">Leslie Alexander</h3>
                                <p class="text-sm/6 font-semibold text-indigo-600">[Position]</p>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center gap-x-6">
                            <img class="size-16 rounded-full"
                                src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                alt="">
                            <div>
                                <h3 class="text-base/7 font-semibold tracking-tight text-gray-900">Leslie Alexander</h3>
                                <p class="text-sm/6 font-semibold text-indigo-600">[Position]</p>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center gap-x-6">
                            <img class="size-16 rounded-full"
                                src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                alt="">
                            <div>
                                <h3 class="text-base/7 font-semibold tracking-tight text-gray-900">Leslie Alexander</h3>
                                <p class="text-sm/6 font-semibold text-indigo-600">[Position]</p>
                            </div>
                        </div>
                    </li>
                    <!-- More people... -->
                </ul>
            </div>
        </div>
        {{-- <article class="relative flex justify-center items-center min-h-screen p-6">
            <!-- Main Content -->
            <div
                class="relative rounded-lg shadow-xl border-[1px] border-gray-200 w-full max-w-[1500px] h-auto p-6 md:p-10">
                <!-- Icon Top Element -->
                <div class="absolute right-4 top-10 md:right-10 md:top-8">
                    <div class="relative w-8 md:w-12">
                        <div class="absolute inset-0 bg-[#60b5cf] rotate-45 w-full h-2 md:h-3"></div>
                        <div class="absolute inset-0 bg-[#60b5cf] -rotate-45 w-full h-2 md:h-3"></div>
                    </div>
                </div>
                <!-- Icon left Bottom Element -->
                <div class="absolute left-6 bottom-10 md:right-10 md:bottom-12">
                    <div class="relative w-8 md:w-12">
                        <div class="absolute inset-0 bg-[#60b5cf] w-full h-2 md:h-3"></div>
                        <div class="absolute inset-0 bg-[#60b5cf] rotate-90 w-full h-2 md:h-3"></div>
                    </div>
                </div>
                <!-- Left Side: Image -->
                <div class="relative grid grid-cols-1 md:grid-cols-12 gap-6 h-auto my-20 border-2">
                    <!-- Left Content -->
                    <div class="col-span-12 md:col-span-6 flex flex-col mx-6 my-8">
                        <h1 class="text-4xl md:text-4xl font-bold text-gray-800 md:top-6 flex gap-2 mb-4">
                            <span>Our</span>
                            <span class="text-[#60b5cf]">Best</span>
                            <span>Team</span>
                        </h1>
                        <p class="text-justify">
                            Kami bangga memiliki tim yang terdiri dari individu-individu kompeten dari berbagai latar
                            belakang dan keahlian. Setiap
                            anggota tim kami tidak hanya memiliki pengalaman mendalam di bidangnya masing-masing, tetapi
                            juga memahami dunia
                            pendidikan dengan sangat baik.
                        </p>
                    </div>
                    <!-- Right Side: Text Content -->
                    <div class="relative w-full overflow-hidden border-2 col-span-12 md:col-span-6">
                        <div class="relative flex gap-8 justify-start w-full h-full">
                            <div class="slider-container">
                                <div class="swiper tranding-slider">
                                    <div class="swiper-wrapper">
                                        <figure class="swiper-slide">
                                            <div class="w-24 h-24 lg:w-32 lg:h-32 rounded-full bg-[#60b5cf] relative">
                                                <div
                                                    class="absolute -bottom-2 -right-2 bg-red-500 w-12 h-12 md:w-14 md:h-14 rounded-full text-2xl flex justify-center items-center">
                                                    <i class="fas fa-microscope"></i>
                                                </div>
                                            </div>
                                        </figure>
                                        <figure class="swiper-slide">
                                            <div class="w-24 h-24 lg:w-32 lg:h-32 rounded-full bg-[#60b5cf] relative">
                                                <div
                                                    class="absolute -bottom-2 -right-2 bg-red-500 w-12 h-12 md:w-14 md:h-14 rounded-full text-2xl flex justify-center items-center">
                                                    <i class="fas fa-microscope"></i>
                                                </div>
                                            </div>
                                        </figure>
                                        <figure class="swiper-slide">
                                            <div class="w-24 h-24 lg:w-32 lg:h-32 rounded-full bg-[#60b5cf] relative">
                                                <div
                                                    class="absolute -bottom-2 -right-2 bg-red-500 w-12 h-12 md:w-14 md:h-14 rounded-full text-2xl flex justify-center items-center">
                                                    <i class="fas fa-microscope"></i>
                                                </div>
                                            </div>
                                        </figure>
                                        <figure class="swiper-slide">
                                            <div class="w-24 h-24 lg:w-32 lg:h-32 rounded-full bg-[#60b5cf] relative">
                                                <div
                                                    class="absolute -bottom-2 -right-2 bg-red-500 w-12 h-12 md:w-14 md:h-14 rounded-full text-2xl flex justify-center items-center">
                                                    <i class="fas fa-microscope"></i>
                                                </div>
                                            </div>
                                        </figure>
                                        <figure class="swiper-slide">
                                            <div class="w-24 h-24 lg:w-32 lg:h-32 rounded-full bg-[#60b5cf] relative">
                                                <div
                                                    class="absolute -bottom-2 -right-2 bg-red-500 w-12 h-12 md:w-14 md:h-14 rounded-full text-2xl flex justify-center items-center">
                                                    <i class="fas fa-microscope"></i>
                                                </div>
                                            </div>
                                        </figure>
                                    </div>
                                    <div class="pagination-slider-our-team">
                                        <div class="swiper-button-prev slider-arrow">
                                            <i class="fa-solid fa-arrow-left"></i>
                                        </div>
                                        <div class="swiper-button-next slider-arrow">
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </div>
                                        <div class="swiper-pagination"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article> --}}
    </section>
</main>

<script src="{{ asset('js/swiper-js/swiper-our-best-team.js') }}"></script>
