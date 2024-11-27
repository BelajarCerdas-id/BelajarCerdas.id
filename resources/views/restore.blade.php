<x-script></x-script>
@if (isset($user))
    @if ($user->status === 'Siswa')
        <div class="grid lg:grid-cols-2 border-[1px] border-gray-400 gap-8 mx-20">
            <div class="lg:col-span-1 bg-white shadow-lg h-max">
                <header class="grid border-b-[1px] border-gray-200 pb-2">
                    <div class="text-md mb-1 px-2">
                        <span class="text-gray-900 font-medium">Nama Siswa :</span>
                        <span>{{ $getRestore->nama_lengkap }}</span>
                    </div>
                    <div class="text-md mb-1 px-2">
                        <span class="text-gray-900 font-medium">Sekolah :</span>
                        <span>{{ $getRestore->sekolah }}</span>
                    </div>
                    <div class="text-md mb-1 px-2">
                        <span class="text-gray-900 font-medium">Kelas :</span>
                        <span>{{ $getRestore->kelas }}</span>
                    </div>
                    <div class="text-md mb-1 px-2">
                        <span class="text-gray-900 font-medium">Jam Tanya :</span>
                        <span>{{ $getRestore->created_at->locale('id')->translatedFormat('l d-M-Y, H:i:s') }}</span>
                    </div>
                </header>
                <div class="w-full mt-2 mx-2">
                    <div class="flex gap-2 text-lg mb-1">
                        <span class="text-gray-900 font-medium">Mata Pelajaran :</span>
                        <span>{{ $getRestore->mapel }}</span>
                    </div>
                    <div class="flex gap-2 text-lg">
                        <span class="text-gray-900 font-medium">Bab :</span>
                        <span>{{ $getRestore->bab }}</span>
                    </div>
                </div>
                <div class="flex mx-6 my-6 gap-12">
                    <div class="w-3/4 mx-2">
                        <span class="text-gray-900 font-medium text-lg">Pertanyaan</span>
                        <div
                            class="h-[150px] bg-white shadow-xl shadow-gray-200 drop-shadow-xl rounded-xl overflow-y-auto text-sm p-4 mb-14 mt-4">
                            <span>{{ $getRestore->pertanyaan }}</span>
                        </div>
                    </div>
                    <div class="w-3/4 mx-2">
                        <label class="text-gray-900 font-medium text-lg">Gambar</label>
                        <div class="h-80 bg-white shadow-lg rounded-lg mb-28 overflow-hidden cursor-pointer mt-4"
                            onclick="my_modal_1.showModal()">
                            @if (isset($getRestore) && is_object($getRestore) && !empty($getRestore->image_tanya))
                                <img src="{{ asset('images_tanya/' . $getRestore->image_tanya) }}" alt=""
                                    class="w-full h-full">
                            @else
                                <div class="h-full w-full flex items-center justify-center">
                                    <p>Gambar tidak tersedia</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
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
            <div class="lg:col-span-1 bg-white shadow-lg h-max hidden lg:block">
                <header class="grid border-b-[1px] border-gray-200 pb-2">
                    <div class="text-md mb-1 px-2">
                        <span class="text-gray-900 font-medium">Mentor :</span>
                        <span>{{ $getRestore->mentor }}</span>
                    </div>
                    <div class="text-md mb-1 px-2">
                        <span class="text-gray-900 font-medium">Asal Sekolah :</span>
                        <span>{{ $getRestore->asal_mengajar }}</span>
                    </div>
                    <div class="text-md mb-1 px-2">
                        <span class="text-gray-900 font-medium">Kelas :</span>
                        {{-- <span>{{ $getRestore->kelas }}</span> --}}
                    </div>
                    <div class="text-md mb-1 px-2">
                        <span class="text-gray-900 font-medium">Jam Jawab :</span>
                        <span>{{ $getRestore->updated_at->locale('id')->translatedFormat('l d-M-Y, H:i:s') }}</span>
                    </div>
                </header>
                <div class="flex mx-6 my-6 gap-12">
                    <div class="w-3/4 mx-2 mt-16">
                        <span class="text-gray-900 font-medium text-lg">Jawaban</span>
                        <div
                            class="h-[150px] bg-white shadow-xl shadow-gray-200 drop-shadow-xl rounded-xl overflow-y-auto text-sm p-6 mt-4">
                            <span>{{ $getRestore->jawaban }}</span>
                        </div>
                    </div>
                    <div class="w-3/4 mx-2 mt-16">
                        <label class="text-gray-900 font-medium text-lg">Gambar</label>
                        <div class="h-80 bg-white shadow-lg rounded-lg mb-28 overflow-hidden mt-4"
                            onclick="my_modal_2.showModal()">
                            <div class="h-80 bg-white shadow-lg rounded-lg mb-28 overflow-hidden cursor-pointer mt-4">
                                @if (isset($getRestore) && is_object($getRestore) && !empty($getRestore->image_jawab))
                                    <img src="{{ asset('images_tanya/' . $getRestore->image_jawab) }}" alt=""
                                        class="w-full h-full">
                                @else
                                    <div class="h-full w-full flex items-center justify-center">
                                        <p>Gambar tidak tersedia</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
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
                </form>
            </div>
        </div>
    @elseif ($user->status === 'Mentor')
        <div class="grid lg:grid-cols-2 border-[1px] border-gray-400 gap-8 mx-20">
            <div class="lg:col-span-1 bg-white shadow-lg h-max">
                <header class="grid border-b-[1px] border-gray-200 pb-2">
                    <div class="text-md mb-1 px-2">
                        <span class="text-gray-900 font-medium">Nama Siswa :</span>
                        <span>{{ $getRestore->nama_lengkap }}</span>
                    </div>
                    <div class="text-md mb-1 px-2">
                        <span class="text-gray-900 font-medium">Sekolah :</span>
                        <span>{{ $getRestore->sekolah }}</span>
                    </div>
                    <div class="text-md mb-1 px-2">
                        <span class="text-gray-900 font-medium">Kelas :</span>
                        <span>{{ $getRestore->kelas }}</span>
                    </div>
                    <div class="text-md mb-1 px-2">
                        <span class="text-gray-900 font-medium">Jam Tanya :</span>
                        <span>{{ $getRestore->created_at->locale('id')->translatedFormat('l d-M-Y, H:i:s') }}</span>
                    </div>
                </header>
                <div class="w-full mt-2 mx-2">
                    <div class="flex gap-2 text-lg mb-1">
                        <span class="text-gray-900 font-medium">Mata Pelajaran :</span>
                        <span>{{ $getRestore->mapel }}</span>
                    </div>
                    <div class="flex gap-2 text-lg">
                        <span class="text-gray-900 font-medium">Bab :</span>
                        <span>{{ $getRestore->bab }}</span>
                    </div>
                </div>
                <div class="flex mx-6 my-6 gap-12">
                    <div class="w-3/4 mx-2">
                        <span class="text-gray-900 font-medium text-lg">Pertanyaan</span>
                        <div
                            class="h-[150px] bg-white shadow-xl shadow-gray-200 drop-shadow-xl rounded-xl overflow-y-auto text-sm p-4 mb-14 mt-4">
                            <span>{{ $getRestore->pertanyaan }}</span>
                        </div>
                    </div>
                    <div class="w-3/4 mx-2">
                        <label class="text-gray-900 font-medium text-lg">Gambar</label>
                        <div class="h-80 bg-white shadow-lg rounded-lg mb-28 overflow-hidden cursor-pointer mt-4"
                            onclick="my_modal_1.showModal()">
                            @if (isset($getRestore) && is_object($getRestore) && !empty($getRestore->image_tanya))
                                <img src="{{ asset('images_tanya/' . $getRestore->image_tanya) }}" alt=""
                                    class="w-full h-full">
                            @else
                                <div class="h-full w-full flex items-center justify-center">
                                    <p>Gambar tidak tersedia</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <dialog id="my_modal_1" class="modal">
                    <div class="modal-box">
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
            <div class="lg:col-span-1 bg-white shadow-lg h-max hidden lg:block">
                <header class="grid border-b-[1px] border-gray-200 pb-2">
                    <div class="text-md mb-1 px-2">
                        <span class="text-gray-900 font-medium">Mentor :</span>
                        <span>{{ $getRestore->mentor }}</span>
                    </div>
                    <div class="text-md mb-1 px-2">
                        <span class="text-gray-900 font-medium">Asal Sekolah :</span>
                        <span>{{ $getRestore->asal_mengajar }}</span>
                    </div>
                    <div class="text-md mb-1 px-2">
                        <span class="text-gray-900 font-medium">Kelas :</span>
                        {{-- <span>{{ $getRestore->kelas }}</span> --}}
                    </div>
                    <div class="text-md mb-1 px-2">
                        <span class="text-gray-900 font-medium">Jam Jawab :</span>
                        <span>{{ $getRestore->updated_at->locale('id')->translatedFormat('l d-M-Y, H:i:s') }}</span>
                    </div>
                </header>
                <div class="flex mx-6 my-6 gap-12">
                    <div class="w-3/4 mx-2 mt-16">
                        <span class="text-gray-900 font-medium text-lg">Jawaban</span>
                        <div
                            class="h-[150px] bg-white shadow-xl shadow-gray-200 drop-shadow-xl rounded-xl overflow-y-auto text-sm p-6 mt-4">
                            <span>{{ $getRestore->jawaban }}</span>
                        </div>
                    </div>
                    <div class="w-3/4 mx-2 mt-16">
                        <label class="text-gray-900 font-medium text-lg">Gambar</label>
                        <div class="h-80 bg-white shadow-lg rounded-lg mb-28 overflow-hidden mt-4"
                            onclick="my_modal_2.showModal()">
                            <div class="h-80 bg-white shadow-lg rounded-lg mb-28 overflow-hidden cursor-pointer mt-4">
                                @if (isset($getRestore) && is_object($getRestore) && !empty($getRestore->image_jawab))
                                    <img src="{{ asset('images_tanya/' . $getRestore->image_jawab) }}" alt=""
                                        class="w-full h-full">
                                @else
                                    <div class="h-full w-full flex items-center justify-center">
                                        <p>Gambar tidak tersedia</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <dialog id="my_modal_2" class="modal">
                        <div class="modal-box">
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
                </form>
            </div>
        </div>
    @else
        <p>You do not have access to this pages.</p>
    @endif
@else
    <p>You are not logged in.</p>
@endif
