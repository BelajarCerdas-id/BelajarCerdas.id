<x-script></x-script>
@if (session('user')->status === 'Siswa' or session('user')->status === 'Murid')
    <main>
        <section>
            <div class="grid grid-cols-8 gap-2 mx-2 lg:w-[90%] lg:mx-auto mt-20">
                <!---- siswa ----->
                <div class="col-span-8 md:col-span-4 bg-white shadow-lg border-[1px] border-gray-200 rounded-md">
                    <!---- identitas siswa ----->
                    <div class="grid grid-cols-6 px-4 leading-8 h-32">
                        <div class="col-span-6">
                            <div class="">
                                <span class="text-gray-900 font-medium">Nama Siswa :</span>
                                <span>{{ $getRestore->nama_lengkap }}</span>
                            </div>
                            <div class="">
                                <span class="text-gray-900 font-medium">Sekolah :</span>
                                <span>{{ $getRestore->sekolah }}</span>
                            </div>
                            <div class="">
                                <span class="text-gray-900 font-medium">Kelas :</span>
                                <span>{{ $getRestore->kelas }}</span>
                            </div>
                            <div class="">
                                <span class="text-gray-900 font-medium">Jam berTANYA :</span>
                                <span>{{ $getRestore->created_at->locale('id')->translatedFormat('l d-M-Y, H:i:s') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="border-b-[1px] border-gray-200"></div>

                    <!---- mapel dan bab ----->
                    <div class="mx-4 my-8 leading-8">
                        <div class="">
                            <span>Mata Pelajaran :</span>
                            {{ $getRestore->mapel }}
                        </div>
                        <div class="">
                            <span>Bab :</span>
                            {{ $getRestore->bab }}
                        </div>
                    </div>

                    <!---- pertanyaan dan image Tanya siswa ----->
                    <!---- pertanyaan ----->
                    <div class="mx-4 my-8">
                        <span>Pertanyaan</span>
                        <div class="max-h-40 h-40 my-2 p-2 bg-white shadow-xl border-[1px] border-gray-200 rounded-md overflow-y-auto text-sm resize-none"
                            disabled>
                            {{ $getRestore->pertanyaan }}
                        </div>
                    </div>
                    <!---- image ----->
                    <div class="w-max mx-4 mb-12">
                        <label class="text-gray-900 font-medium text-lg">Gambar</label>
                        <div class="h-60 bg-white shadow-lg rounded-lg overflow-hidden mt-2">
                            <div
                                class="w-60 h-60 bg-white shadow-lg rounded-lg overflow-hidden cursor-pointer border-[1px] border-gray-200">
                                @if (isset($getRestore) && is_object($getRestore) && !empty($getRestore->image_tanya))
                                    <img src="{{ asset('images_tanya/' . $getRestore->image_tanya) }}" alt=""
                                        class="w-full h-full" onclick="my_modal_1.showModal()">
                                @else
                                    <div class="h-full w-full flex items-center justify-center cursor-default">
                                        <p>Gambar tidak tersedia</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!---- modal image siswa ----->
                    <dialog id="my_modal_1" class="modal">
                        <div class="modal-box bg-white">
                            @if (isset($getRestore) && is_object($getRestore) && !empty($getRestore->image_tanya))
                                <img src="{{ asset('images_tanya/' . $getRestore->image_tanya) }}" alt=""
                                    class="object-cover w-full">
                            @else
                                <div class="h-full w-full flex items-center justify-center">
                                    <p>Gambar tidak tersedia</p>
                                </div>
                            @endif
                        </div>
                        <form method="dialog" class="modal-backdrop">
                            <button>close</button>
                        </form>
                    </dialog>
                </div>
                <!---- mentor ----->
                <div
                    class="col-span-8 md:col-span-4 bg-white shadow-lg border-[1px] border-gray-200 rounded-md mt-20 md:mt-0">
                    <!---- identitas mentor ----->
                    <div class="grid grid-cols-6 px-4 leading-8 h-32">
                        <div class="col-span-6">
                            <div class="">
                                <span class="text-gray-900 font-medium">Mentor :</span>
                                <span>{{ $getRestore->mentor }}</span>
                            </div>
                            <div class="">
                                <span class="text-gray-900 font-medium">Asal Mengajar :</span>
                                <span>{{ $getRestore->asal_mengajar }}</span>
                            </div>
                            <div class="">
                                <span class="text-gray-900 font-medium">Jam Terjawab :</span>
                                <span>{{ $getRestore->updated_at->locale('id')->translatedFormat('l d-M-Y, H:i:s') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="border-b-[1px] border-gray-200"></div>
                    <!---- jawaban dan image Tanya mentor ----->

                    <!---- jawaban ----->
                    <div class="mx-4 mt-8 md:mt-32 mb-8">
                        <span>Jawaban</span>
                        <div class="max-h-40 h-40 my-2 p-2 bg-white shadow-xl border-[1px] border-gray-200 rounded-md overflow-y-auto text-sm resize-none"
                            disabled>
                            {{ $getRestore->jawaban }}
                        </div>
                    </div>

                    <!---- image ----->
                    <div class="w-max mx-4 mb-12">
                        <label class="text-gray-900 font-medium text-lg">Gambar</label>
                        <div class="h-60 bg-white shadow-lg rounded-lg overflow-hidden mt-2">
                            <div
                                class="w-60 h-60 bg-white shadow-lg rounded-lg overflow-hidden cursor-pointer border-[1px] border-gray-200">
                                @if (isset($getRestore) && is_object($getRestore) && !empty($getRestore->image_jawab))
                                    <img src="{{ asset('images_tanya/' . $getRestore->image_jawab) }}" alt=""
                                        class="w-full h-full" onclick="my_modal_2.showModal()">
                                @else
                                    <div class="h-full w-full flex items-center justify-center cursor-default">
                                        <p>Gambar tidak tersedia</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!---- modal image mentor ----->
                    <dialog id="my_modal_2" class="modal">
                        <div class="modal-box bg-white">
                            @if (isset($getRestore) && is_object($getRestore) && !empty($getRestore->image_jawab))
                                <img src="{{ asset('images_tanya/' . $getRestore->image_jawab) }}" alt=""
                                    class="object-cover w-full">
                            @else
                                <div class="h-full w-full flex items-center justify-center">
                                    <p>Gambar tidak tersedia</p>
                                </div>
                            @endif
                        </div>
                        <form method="dialog" class="modal-backdrop">
                            <button>close</button>
                        </form>
                    </dialog>
                </div>
                <div class="flex justify-start mt-8">
                    <a href="{{ route('tanya.index') }}"
                        class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all flex items-center gap-2">
                        <i class="fas fa-chevron-left"></i>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>
        </section>
    </main>
@elseif (session('user')->status === 'Mentor')
    <main>
        <section>
            <div class="grid grid-cols-8 gap-2 mx-2 lg:w-[90%] lg:mx-auto mt-20">
                <!---- siswa ----->
                <div class="col-span-8 md:col-span-4 bg-white shadow-lg border-[1px] border-gray-200 rounded-md">
                    <!---- identitas siswa ----->
                    <div class="grid grid-cols-6 px-4 leading-8 h-32">
                        <div class="col-span-6">
                            <div class="">
                                <span class="text-gray-900 font-medium">Nama Siswa :</span>
                                <span>{{ $getRestore->nama_lengkap }}</span>
                            </div>
                            <div class="">
                                <span class="text-gray-900 font-medium">Sekolah :</span>
                                <span>{{ $getRestore->sekolah }}</span>
                            </div>
                            <div class="">
                                <span class="text-gray-900 font-medium">Kelas :</span>
                                <span>{{ $getRestore->kelas }}</span>
                            </div>
                            <div class="">
                                <span class="text-gray-900 font-medium">Jam berTANYA :</span>
                                <span>{{ $getRestore->created_at->locale('id')->translatedFormat('l d-M-Y, H:i:s') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="border-b-[1px] border-gray-200"></div>

                    <!---- mapel dan bab ----->
                    <div class="mx-4 my-8 leading-8">
                        <div class="">
                            <span>Mata Pelajaran :</span>
                            {{ $getRestore->mapel }}
                        </div>
                        <div class="">
                            <span>Bab :</span>
                            {{ $getRestore->bab }}
                        </div>
                    </div>

                    <!---- pertanyaan dan image Tanya siswa ----->
                    <!---- pertanyaan ----->
                    <div class="mx-4 my-8">
                        <span>Pertanyaan</span>
                        <div class="max-h-40 h-40 my-2 p-2 bg-white shadow-xl border-[1px] border-gray-200 rounded-md overflow-y-auto text-sm resize-none"
                            disabled>
                            {{ $getRestore->pertanyaan }}
                        </div>
                    </div>
                    <!---- image ----->
                    <div class="w-max mx-4 mb-12">
                        <label class="text-gray-900 font-medium text-lg">Gambar</label>
                        <div class="h-60 bg-white shadow-lg rounded-lg overflow-hidden mt-2">
                            <div
                                class="w-60 h-60 bg-white shadow-lg rounded-lg overflow-hidden cursor-pointer border-[1px] border-gray-200">
                                @if (isset($getRestore) && is_object($getRestore) && !empty($getRestore->image_tanya))
                                    <img src="{{ asset('images_tanya/' . $getRestore->image_tanya) }}" alt=""
                                        class="w-full h-full" onclick="my_modal_1.showModal()">
                                @else
                                    <div class="h-full w-full flex items-center justify-center cursor-default">
                                        <p>Gambar tidak tersedia</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!---- modal image siswa ----->
                    <dialog id="my_modal_1" class="modal">
                        <div class="modal-box bg-white">
                            @if (isset($getRestore) && is_object($getRestore) && !empty($getRestore->image_tanya))
                                <img src="{{ asset('images_tanya/' . $getRestore->image_tanya) }}" alt=""
                                    class="object-cover w-full">
                            @else
                                <div class="h-full w-full flex items-center justify-center">
                                    <p>Gambar tidak tersedia</p>
                                </div>
                            @endif
                        </div>
                        <form method="dialog" class="modal-backdrop">
                            <button>close</button>
                        </form>
                    </dialog>
                </div>
                <!---- mentor ----->
                <div
                    class="col-span-8 md:col-span-4 bg-white shadow-lg border-[1px] border-gray-200 rounded-md mt-20 md:mt-0">
                    <!---- identitas mentor ----->
                    <div class="grid grid-cols-6 px-4 leading-8 h-32">
                        <div class="col-span-6">
                            <div class="">
                                <span class="text-gray-900 font-medium">Mentor :</span>
                                <span>{{ $getRestore->mentor }}</span>
                            </div>
                            <div class="">
                                <span class="text-gray-900 font-medium">Asal Mengajar :</span>
                                <span>{{ $getRestore->asal_mengajar }}</span>
                            </div>
                            <div class="">
                                <span class="text-gray-900 font-medium">Jam Terjawab :</span>
                                <span>{{ $getRestore->updated_at->locale('id')->translatedFormat('l d-M-Y, H:i:s') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="border-b-[1px] border-gray-200"></div>

                    <!---- jawaban dan image Tanya mentor ----->
                    <!---- jawaban ----->
                    <div class="mx-4 mt-8 md:mt-32 mb-8">
                        <span>Jawaban</span>
                        <div class="max-h-40 h-40 my-2 p-2 bg-white shadow-xl border-[1px] border-gray-200 rounded-md overflow-y-auto text-sm resize-none"
                            disabled>
                            @if (isset($getRestore) && is_object($getRestore) && !empty($getRestore->jawaban))
                                {{ $getRestore->jawaban }}
                            @else
                                <div class="w-full h-full flex items-center justify-center cursor-default">
                                    <p>Jawaban tidak tersedia</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!---- image ----->
                    <div class="w-max mx-4 mb-12">
                        <label class="text-gray-900 font-medium text-lg">Gambar</label>
                        <div class="h-60 bg-white shadow-lg rounded-lg overflow-hidden mt-2">
                            <div
                                class="w-60 h-60 bg-white shadow-lg rounded-lg overflow-hidden cursor-pointer border-[1px] border-gray-200">
                                @if (isset($getRestore) && is_object($getRestore) && !empty($getRestore->image_jawab))
                                    <img src="{{ asset('images_tanya/' . $getRestore->image_jawab) }}" alt=""
                                        class="w-full h-full" onclick="my_modal_2.showModal()">
                                @else
                                    <div class="h-full w-full flex items-center justify-center cursor-default">
                                        <p>Gambar tidak tersedia</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!---- modal image mentor ----->
                    <dialog id="my_modal_2" class="modal">
                        <div class="modal-box bg-white">
                            @if (isset($getRestore) && is_object($getRestore) && !empty($getRestore->image_jawab))
                                <img src="{{ asset('images_tanya/' . $getRestore->image_jawab) }}" alt=""
                                    class="object-cover w-full">
                            @else
                                <div class="h-full w-full flex items-center justify-center">
                                    <p>Gambar tidak tersedia</p>
                                </div>
                            @endif
                        </div>
                        <form method="dialog" class="modal-backdrop">
                            <button>close</button>
                        </form>
                    </dialog>
                </div>
                <div class="flex justify-start mt-8">
                    <a href="{{ route('tanya.index') }}"
                        class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all flex items-center gap-2">
                        <i class="fas fa-chevron-left"></i>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>
        </section>
    </main>
@else
    <p>You do not have access to this pages.</p>
@endif
