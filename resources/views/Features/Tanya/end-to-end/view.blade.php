<x-script></x-script>
@if (Auth::user()->role === 'Siswa')
    <div class="grid lg:grid-cols-2 border-[1px] border-gray-400 gap-8 mx-20">
        <div class="lg:col-span-1 bg-white shadow-lg h-max">
            <header class="grid border-b-[1px] border-gray-200 pb-2">
                <div class="text-md mb-1 px-2">
                    <span class="text-gray-900 font-medium">Nama Siswa :</span>
                    <span>{{ $getTanya->Student->StudentProfiles->nama_lengkap ?? '-' }}</span>
                </div>
                <div class="text-md mb-1 px-2">
                    <span class="text-gray-900 font-medium">Sekolah :</span>
                    <span>{{ $getTanya->Student->StudentProfiles->sekolah ?? '-' }}</span>
                </div>
                <div class="text-md mb-1 px-2">
                    <span class="text-gray-900 font-medium">Kelas :</span>
                    <span>{{ $getTanya->Kelas->kelas ?? '-' }}</span>
                </div>
                <div class="text-md mb-1 px-2">
                    <span class="text-gray-900 font-medium">Jam Tanya :</span>
                    <span>{{ $getTanya->created_at->locale('id')->translatedFormat('l d-M-Y, H:i:s') ?? '-' }}</span>
                </div>
            </header>
            <div class="w-full mt-2 mx-2">
                <div class="flex gap-2 text-lg mb-1">
                    <span class="text-gray-900 font-medium">Mata Pelajaran :</span>
                    <span>{{ $getTanya->Mapel->mata_pelajaran ?? '-' }}</span>
                </div>
                <div class="flex gap-2 text-lg">
                    <span class="text-gray-900 font-medium">Bab :</span>
                    <span>{{ $getTanya->Bab->nama_bab ?? '-' }}</span>
                </div>
            </div>
            <div class="flex mx-6 my-6 gap-12">
                <div class="w-3/4 mx-2">
                    <span class="text-gray-900 font-medium text-lg">Pertanyaan</span>
                    <div
                        class="h-[150px] bg-white shadow-xl shadow-gray-200 drop-shadow-xl rounded-xl overflow-y-auto text-sm p-4 mb-14 mt-4">
                        <span>{{ $getTanya->pertanyaan ?? '-' }}</span>
                    </div>
                </div>
                <div class="w-3/4 mx-2">
                    <label class="text-gray-900 font-medium text-lg">Gambar</label>
                    <div class="h-80 bg-white shadow-lg rounded-lg mb-28 overflow-hidden cursor-pointer mt-4"
                        onclick="my_modal_1.showModal()">
                        @if (isset($getTanya) && is_object($getTanya) && !empty($getTanya->image_tanya))
                            <img src="{{ asset('images_tanya/' . $getTanya->image_tanya) }}" alt=""
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
                    @if (isset($getTanya) && is_object($getTanya) && !empty($getTanya->image_tanya))
                        <img src="{{ asset('images_tanya/' . $getTanya->image_tanya) }}" alt=""
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
                    <span>{{ $getTanya->mentor ?? '-' }}</span>
                </div>
                <div class="text-md mb-1 px-2">
                    <span class="text-gray-900 font-medium">Asal Sekolah :</span>
                    <span>{{ $getTanya->asal_mengajar ?? '-' }}</span>
                </div>
                <div class="text-md mb-1 px-2">
                    <span class="text-gray-900 font-medium">Kelas :</span>
                    {{-- <span>{{ $getTanya->kelas }}</span> --}}
                </div>
                <div class="text-md mb-1 px-2">
                    <span class="text-gray-900 font-medium">Jam Jawab :</span>
                    <span>{{ $getTanya->updated_at->locale('id')->translatedFormat('l d-M-Y, H:i:s') ?? '-' }}</span>
                </div>
            </header>
            <div class="flex mx-6 my-6 gap-12">
                <div class="w-3/4 mx-2 mt-16">
                    <span class="text-gray-900 font-medium text-lg">Jawaban</span>
                    <div
                        class="h-[150px] bg-white shadow-xl shadow-gray-200 drop-shadow-xl rounded-xl overflow-y-auto text-sm p-6 mt-4">
                        <span>{{ $getTanya->jawaban ?? '-' }}</span>
                    </div>
                </div>
                <div class="w-3/4 mx-2 mt-16">
                    <label class="text-gray-900 font-medium text-lg">Gambar</label>
                    <div class="h-80 bg-white shadow-lg rounded-lg mb-28 overflow-hidden mt-4"
                        onclick="my_modal_2.showModal()">
                        <div class="h-80 bg-white shadow-lg rounded-lg mb-28 overflow-hidden cursor-pointer mt-4">
                            @if (isset($getTanya) && is_object($getTanya) && !empty($getTanya->image_jawab))
                                <img src="{{ asset('images_tanya/' . $getTanya->image_jawab) }}" alt=""
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
                        @if (isset($getTanya) && is_object($getTanya) && !empty($getTanya->image_jawab))
                            <img src="{{ asset('images_tanya/' . $getTanya->image_jawab) }}" alt=""
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
@elseif (Auth::user()->role === 'Mentor')
    <main>
        <section id="question-answer" data-question-id="{{ $getRestore->id }}">
            <div class="grid grid-cols-8 gap-2 mx-2 lg:w-[90%] lg:mx-auto mt-20">
                <!---- sisi TANYA siswa ----->
                <div class="col-span-8 md:col-span-4 bg-white shadow-lg border-[1px] border-gray-200 rounded-md">
                    <!---- identitas siswa ----->
                    <div class="grid grid-cols-6 px-4 leading-8">
                        <div class="col-span-6">
                            <div class="">
                                <span class="text-gray-900 font-medium">Nama Siswa :</span>
                                <span>{{ $getRestore->Student->StudentProfiles->nama_lengkap ?? '-' }}</span>
                            </div>
                            <div class="">
                                <span class="text-gray-900 font-medium">Sekolah :</span>
                                <span>{{ $getRestore->Student->StudentProfiles->sekolah ?? '-' }}</span>
                            </div>
                            <div class="">
                                <span class="text-gray-900 font-medium">Kelas :</span>
                                <span>{{ $getRestore->Kelas->kelas ?? '-' }}</span>
                            </div>
                            <div class="">
                                <span class="text-gray-900 font-medium">Jam berTANYA :</span>
                                <span>{{ $getRestore->created_at->locale('id')->translatedFormat('l d-M-Y, H:i:s') ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="border-b-[1px] border-gray-200"></div>

                    <!---- mapel dan bab ----->
                    <div class="mx-4 my-8 leading-8">
                        <div class="">
                            <span>Mata Pelajaran :</span>
                            {{ $getTanya->Mapel->mata_pelajaran ?? '-' }}
                        </div>
                        <div class="">
                            <span>Bab :</span>
                            {{ $getTanya->Bab->nama_bab ?? '-' }}
                        </div>
                    </div>

                    <!---- pertanyaan dan image Tanya siswa ----->
                    <!---- pertanyaan ----->
                    <div class="mx-4 my-8">
                        <span>Pertanyaan</span>
                        <div class="max-h-40 h-40 my-2 p-2 bg-white shadow-xl border-[1px] border-gray-200 rounded-md overflow-y-auto text-sm resize-none"
                            disabled>
                            {!! nl2br(e($getRestore->pertanyaan)) ?? '-' !!}
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
                    <div class="flex justify-between mb-6">
                        <div class="mx-6">
                            <form action="{{ route('tanya.markViewedBackButton', $getRestore->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button
                                    class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all flex items-center gap-2">
                                    <i class="fas fa-chevron-left"></i>
                                    Kembali
                                </button>
                            </form>
                        </div>
                        <div class="mx-6">
                            <button
                                class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all"
                                onclick="my_modal_3.showModal()">
                                Tolak Soal
                            </button>
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
                <!---- sisi TANYA mentor ----->

                <!---- upload jawaban ----->
                <div
                    class="col-span-8 md:col-span-4 bg-white shadow-lg border-[1px] border-gray-200 rounded-md relative">
                    <form action="{{ route('tanya.update', $getTanya->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="border-b-[1px] border-gray-200 mt-32"></div>
                        <!---- jawaban dan image Tanya mentor ----->

                        <!---- jawaban ----->
                        <div class="mt-8 md:mt-32 mx-8">
                            <label>Jawaban</label>
                            <textarea name="jawaban"
                                class="h-40 w-full bg-white border-[1px] border-gray-200 shadow-lg shadow-gray-200 drop-shadow-xl outline-none rounded-lg text-xs p-4 resize-none mt-2 focus-within:border-[1px] focus-within:border-[dodgerblue] focus-within:shadow-[0_0_9px_0_dodgerblue] {{ $errors->has('jawaban') ? 'border-[1px] border-red-400' : '' }}"
                                placeholder="Masukkan Jawaban"></textarea>
                        </div>
                        @error('jawaban')
                            <span class="text-red-500 font-bold text-sm pl-8">{{ $message }}</span>
                        @enderror
                        <!---- image ----->
                        <div class="w-max my-8 mx-8">
                            <label class="text-gray-900 font-medium text-lg">Gambar</label>
                            <div class="mt-2 h-auto">
                                <span class="text-sm">Upload Gambar<sup>&#42;Optional</sup></span>
                                <div class="text-xs mt-1">
                                    <span>Maksimum ukuran file 2MB. <br> File dapat dalam format
                                        .jpg/.png/.jpeg.</span>
                                </div>
                                <div class="upload-icon">
                                    <div class="flex flex-col max-w-[260px]">
                                        <div id="imagePreview" class="max-w-[140px] cursor-pointer mt-4"
                                            onclick="openModal()">
                                            {{-- Image will be inserted here --}}
                                            <img id="popupImage" alt="" class="">
                                        </div>
                                        <div id="textPreview" class="text-red-500 font-bold mt-2"></div>
                                    </div>
                                    <div class="text-icon"></div>
                                </div>

                                <div
                                    class="content-upload w-[200px] h-10 bg-[#4189e0] hover:bg-blue-500 text-white font-bold rounded-lg mt-6">
                                    <label for="file-upload"
                                        class="w-full h-full flex justify-center items-center cursor-pointer gap-2">
                                        <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                        <span>Upload Gambar</span>
                                    </label>
                                    <input id="file-upload" name="image_jawab" class="hidden"
                                        onchange="previewImage(event)" type="file" accept=".jpg, .png, .jpeg">
                                </div>
                                @error('image_jawab')
                                    <div class="w-2/4">
                                        <span
                                            class="relative ... top-2 text-red-500 font-bold text-sm">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="absolute bottom-6 right-8">
                            <button
                                class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all">
                                Kirim
                            </button>
                        </div>
                    </form>
                </div>
                <!-- Modal for displaying the image -->
                <dialog id="my_modal_2" class="modal">
                    <div class="modal-box bg-white">
                        <img id="modalImage" alt="" class="w-full h-auto">
                    </div>
                    <form method="dialog" class="modal-backdrop">
                        <button>close</button>
                    </form>
                </dialog>
            </div>
            <!---- modal reject question ----->
            <dialog id="my_modal_3" class="modal">
                <div class="modal-box h-96">
                    <form method="dialog">
                        <button class="absolute right-4 top-2 outline-none">✕</button>
                    </form>
                    <form action="{{ route('tanya.reject', $postReject->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <h3 class="text-lg font-bold">Alasan Tolak</h3>
                        @error('alasan_ditolak')
                            <span class="text-red-500 font-bold text-sm">{{ $message }}</span>
                        @enderror
                        <div class="flex flex-col">
                            <input type="radio" name="alasan_ditolak" id="reason1"
                                value="gambar tidak sesuai dengan pertanyaan">
                            <label for="reason1" class="radioList">
                                <a>Gambar tidak sesuai dengan pertanyaan</a>
                            </label>

                            <input type="radio" name="alasan_ditolak" id="reason2" value="Gambar tidak jelas">
                            <label for="reason2" class="radioList">
                                <a>Gambar tidak jelas</a>
                            </label>

                            <input type="radio" name="alasan_ditolak" id="reason3" value="Lorem Ipsum">
                            <label for="reason3" class="radioList">
                                <a>Lorem Ipsum</a>
                            </label>

                            <input type="radio" name="alasan_ditolak" id="reason4" value="Lorem Ipsum">
                            <label for="reason4" class="radioList">
                                <a>Lorem Ipsum</a>
                            </label>

                            <input type="radio" name="alasan_ditolak" id="reason5" value="Lorem Ipsum">
                            <label for="reason5" class="radioList">
                                <a>Lorem Ipsum</a>
                            </label>
                        </div>
                        <button
                            class="absolute right-10 w-[150px] h-10 bg-[#4189e0] hover:bg-blue-500 text-white font-bold rounded-lg mt-10">Kirim</button>
                    </form>
                </div>
            </dialog>
        </section>
    </main>
@else
    <p>You do not have access to this dashboard.</p>
@endif

<!--- untuk tracker pertanyaan jika ada mentor sedang melihat soal dan lalu kembali menggunakan back button chrome maka status soal berubah ---->
<script src="{{ asset('js/Tanya/end-to-end/view-questions-tracker.js') }}"></script>

<!-- COMPONENTS --->
<script src="{{ asset('js/components/preview/image-tanya-preview.js') }}"></script> <!--- show image tanya ---->

<!--- untuk membuka modal kembali jika ada validasi error pada form modal ---->
<script>
    document.getElementById('submit').addEventListener('click', function() {
        const form = document.querySelector('#my_modal_3 form[action]');
        form.submit(); // Submit the form
    });
</script>

@if ($errors->has('alasan_ditolak'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('my_modal_3').showModal(); // Show modal for Form 1
        });
    </script>
@endif
