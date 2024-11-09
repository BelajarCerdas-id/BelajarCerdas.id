<x-script></x-script>
@if (isset($user))
    @if ($user->status === 'Siswa')
        <div class="grid lg:grid-cols-2 border-[1px] border-gray-400 gap-8 mx-20">
            <div class="lg:col-span-1 bg-white shadow-lg h-max">
                <header class="grid border-b-[1px] border-gray-200 pb-2">
                    <div class="text-md mb-1 px-2">
                        <span class="text-gray-900 font-medium">Nama Siswa :</span>
                        <span>{{ $getTanya->nama_lengkap }}</span>
                    </div>
                    <div class="text-md mb-1 px-2">
                        <span class="text-gray-900 font-medium">Sekolah :</span>
                        <span>{{ $getTanya->sekolah }}</span>
                    </div>
                    <div class="text-md mb-1 px-2">
                        <span class="text-gray-900 font-medium">Kelas :</span>
                        <span>{{ $getTanya->kelas }}</span>
                    </div>
                    <div class="text-md mb-1 px-2">
                        <span class="text-gray-900 font-medium">Jam Tanya :</span>
                        <span>{{ $getTanya->created_at->locale('id')->translatedFormat('l d-M-Y, H:i:s') }}</span>
                    </div>
                </header>
                <div class="w-full mt-2 mx-2">
                    <div class="flex gap-2 text-lg mb-1">
                        <span class="text-gray-900 font-medium">Mata Pelajaran :</span>
                        <span>{{ $getTanya->mapel }}</span>
                    </div>
                    <div class="flex gap-2 text-lg">
                        <span class="text-gray-900 font-medium">Bab :</span>
                        <span>{{ $getTanya->bab }}</span>
                    </div>
                </div>
                <div class="flex mx-6 my-6 gap-12">
                    <div class="w-3/4 mx-2">
                        <span class="text-gray-900 font-medium text-lg">Pertanyaan</span>
                        <div
                            class="h-[150px] bg-white shadow-xl shadow-gray-200 drop-shadow-xl rounded-xl overflow-y-auto text-sm p-4 mb-14 mt-4">
                            <span>{{ $getTanya->pertanyaan }}</span>
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
                        <span>{{ $getTanya->mentor }}</span>
                    </div>
                    <div class="text-md mb-1 px-2">
                        <span class="text-gray-900 font-medium">Asal Sekolah :</span>
                        <span>{{ $getTanya->asal_mengajar }}</span>
                    </div>
                    <div class="text-md mb-1 px-2">
                        <span class="text-gray-900 font-medium">Kelas :</span>
                        {{-- <span>{{ $getTanya->kelas }}</span> --}}
                    </div>
                    <div class="text-md mb-1 px-2">
                        <span class="text-gray-900 font-medium">Jam Jawab :</span>
                        <span>{{ $getTanya->updated_at->locale('id')->translatedFormat('l d-M-Y, H:i:s') }}</span>
                    </div>
                </header>
                <div class="flex mx-6 my-6 gap-12">
                    <div class="w-3/4 mx-2 mt-16">
                        <span class="text-gray-900 font-medium text-lg">Jawaban</span>
                        <div
                            class="h-[150px] bg-white shadow-xl shadow-gray-200 drop-shadow-xl rounded-xl overflow-y-auto text-sm p-6 mt-4">
                            <span>{{ $getTanya->jawaban }}</span>
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
    @elseif ($user->status === 'Guru')
        <div class="grid lg:grid-cols-4 border-[1px] border-gray-400 gap-8">
            <div class="lg:col-span-2 bg-white shadow-lg h-auto">
                <header class="grid border-b-[1px] border-gray-200 pb-2">
                    <div class="text-md mb-1 px-2">
                        <span class="text-gray-900 font-medium">Nama Siswa :</span>
                        <span>{{ $getTanya->nama_lengkap }}</span>
                    </div>
                    <div class="text-md mb-1 px-2">
                        <span class="text-gray-900 font-medium">Sekolah :</span>
                        <span>{{ $getTanya->sekolah }}</span>
                    </div>
                    <div class="text-md mb-1 px-2">
                        <span class="text-gray-900 font-medium">Kelas :</span>
                        <span>{{ $getTanya->kelas }}</span>
                    </div>
                    <div class="text-md mb-1 px-2">
                        <span class="text-gray-900 font-medium">Jam Tanya :</span>
                        <span>{{ $getTanya->created_at->locale('id')->translatedFormat('l d-M-Y, H:i:s') }}</span>
                    </div>
                </header>
                <div class="w-full mt-6 mx-2">
                    <div class="flex gap-2 text-lg mb-2">
                        <span class="text-gray-900 font-medium">Mata Pelajaran :</span>
                        <span>{{ $getTanya->mapel }}</span>
                    </div>
                    <div class="flex gap-2 text-lg">
                        <span class="text-gray-900 font-medium">Bab :</span>
                        <span>{{ $getTanya->bab }}</span>
                    </div>
                </div>
                <div class="grid grid-cols-2 mt-10 gap-8 mx-2">
                    <div class="pertanyaan sm:col-span-2 md:col-span-1">
                        <span class="text-gray-900 font-medium text-lg">Pertanyaan</span>
                        <div
                            class="h-40 bg-white shadow-xl shadow-gray-200 drop-shadow-xl rounded-xl overflow-y-auto text-sm p-4 mb-14 mt-4">
                            {{ $getTanya->pertanyaan }}
                        </div>
                    </div>
                    <div class="image sm:col-span-1 md:col-span-1 relative ...">
                        <span class="text-gray-900 font-medium text-lg">Image</span>
                        <div class="h-80 bg-white rounded-xl shadow-lg mb-28 mt-5" onclick="my_modal_4.showModal()">
                            <div class="cursor-pointer h-full rounded-lg overflow-hidden">
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
                        <button
                            class="absolute bottom-4 right-8 bg-red-500 rounded-lg w-[150px] h-10 text-white font-bold"
                            onclick="my_modal_3.showModal()">Tolak</button>
                    </div>
                </div>
            </div>

            <dialog id="my_modal_4" class="modal">
                <div class="modal-box">
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

            <div class="lg:col-span-2 bg-white shadow-lg h-auto hidden lg:block">
                <form action="{{ route('tanya.update', $getTanya->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="input-hidden border-b-[1px] border-gray-200 pb-[120px]">
                        <input type="hidden" name="mentor" value="{{ $user->nama_lengkap }}">
                        <input type="hidden" name="asal_mengajar" value="{{ $user->sekolah }}">
                        {{-- <input type="hidden" name="kelas" value="{{ $user->kelas }}"> --}}
                        <input type="hidden" name="email_mentor" value="{{ $user->email }}">
                        <input type="hidden" name="status" value="Diterima">
                    </div>
                    <div class="flex mx-6 my-6 gap-12 mt-32">
                        <div class="w-2/4 relative ... h-max">
                            <label class="text-gray-900 font-medium text-lg">Jawaban</label>
                            <textarea name="jawaban"
                                class="h-40 w-full bg-white shadow-xl shadow-gray-200 drop-shadow-xl outline-none rounded-xl text-xs p-4 resize-none mt-4 mb-16 {{ $errors->has('jawaban') ? 'border-[2px] border-red-500' : '' }}"
                                placeholder="Masukkan Jawaban">{{ @old('jawaban') }}</textarea>
                            @error('jawaban')
                                <span
                                    class="relative ... bottom-16 text-red-500 font-bold text-md pl-2">{{ $message }}</span>
                            @enderror
                            {{-- <div class="bg-gray-300 absolute right-0 bottom-0 w-[200px] h-10 rounded-lg flex justify-center items-center text-white font-bold transition duration-300 ease-in-out"
                                id="vibrateButton">
                                Kirim</div> --}}
                            <button type="submit"
                                class="absolute right-0 bottom-0 w-[200px] h-10 bg-red-500 text-white font-bold rounded-lg mt-10">Kirim</button>
                        </div>
                        <div class="w-2/4 h-auto">
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

                            <div class="content-upload w-[200px] h-10 bg-red-500 text-white font-bold rounded-lg mt-6">
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
                </form>

                <!-- Modal for displaying the image -->
                <dialog id="imageModal" class="modal">
                    <div class="modal-box">
                        <button class="btn btn-sm btn-circle btn-ghost absolute right-0 top-0 outline-none"
                            onclick="closeModal()">✕</button>
                        <img id="modalImage" alt="" class="w-full h-auto">
                    </div>
                </dialog>

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
                            <div class="input-hidden">
                                <input type="hidden" name="mentor" value="{{ $user->nama_lengkap }}">
                                <input type="hidden" name="asal_mengajar" value="{{ $user->sekolah }}">
                                {{-- <input type="hidden" name="kelas" value="{{ $user->kelas }}"> --}}
                                <input type="hidden" name="email_mentor" value="{{ $user->email }}">
                                <input type="hidden" name="status" value="Ditolak">
                            </div>
                            <div class="flex flex-col">
                                <input type="radio" name="alasan_ditolak" id="reason1"
                                    value="gambar tidak sesuai dengan pertanyaan">
                                <label for="reason1" class="listReason">
                                    <a>Gambar tidak sesuai dengan pertanyaan</a>
                                </label>

                                <input type="radio" name="alasan_ditolak" id="reason2"
                                    value="Gambar tidak jelas">
                                <label for="reason2" class="listReason">
                                    <a>Gambar tidak jelas</a>
                                </label>

                                <input type="radio" name="alasan_ditolak" id="reason3" value="Lorem Ipsum">
                                <label for="reason3" class="listReason">
                                    <a>Lorem Ipsum</a>
                                </label>

                                <input type="radio" name="alasan_ditolak" id="reason4" value="Lorem Ipsum">
                                <label for="reason4" class="listReason">
                                    <a>Lorem Ipsum</a>
                                </label>

                                <input type="radio" name="alasan_ditolak" id="reason5" value="Lorem Ipsum">
                                <label for="reason5" class="listReason">
                                    <a>Lorem Ipsum</a>
                                </label>
                            </div>
                            <button
                                class="absolute right-10 w-[150px] h-10 bg-red-500 text-white font-bold rounded-lg mt-10">Kirim</button>
                        </form>
                    </div>
                </dialog>
            </div>
        </div>
    @else
        <p>You do not have access to this dashboard.</p>
    @endif
@else
    <p>You are not logged in.</p>
@endif

{{-- <div class="mx-2">
    responsive content
    <label class="text-gray-900 font-medium text-lg">Image</label>
    <div class="h-[450px] max-w-[500px] bg-white rounded-lg overflow-hidden">
        @if (isset($getTanya) && is_object($getTanya) && !empty($getTanya->image_tanya))
            <img src="{{ asset('images_tanya/' . $getTanya->image_tanya) }}" alt=""
                class="object-cover w-full h-full">
        @else
            <div class="h-full w-full flex items-center justify-center">
                <p>Tidak ada image</p>
            </div>
        @endif
    </div>
</div> --}}

<script>
    function togglePopup() {
        document.getElementById("popup-1").classList.toggle("active");
    }
</script>

<script>
    function openModal() {
        var imgSrc = document.querySelector('#imagePreview img').src;
        var modalImage = document.getElementById('modalImage');
        modalImage.src = imgSrc;
        document.getElementById('imageModal').showModal();
    }

    function closeModal() {
        document.getElementById('imageModal').close();
    }
</script>


<script>
    const button = document.getElementById('vibrateButton');

    button.addEventListener('click', () => {
        button.classList.add('vibrate');
        setTimeout(() => {
            button.classList.remove('vibrate');
        }, 100);
    });
</script>

<script>
    var Btn = document.getElementById('btnUnBlocked');

    function openBlocked() {
        Btn.style.display = "block";
    }
</script>


<script>
    function previewImage(event) {
        var file = event.target.files[0];
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('imagePreview');
            var textOutput = document.getElementById('textPreview');
            output.innerHTML = '<img src="' + reader.result +
                '" alt="Image Preview" class="w-full h-full object-cover">';
            textOutput.innerHTML = 'Klik, pastikan gambar tidak blur!';
        };
        reader.readAsDataURL(file);
    }

    function openModal() {
        var imgSrc = document.querySelector('#imagePreview img').src;
        var modalImage = document.getElementById('modalImage');
        modalImage.src = imgSrc;
        document.getElementById('imageModal').showModal();
    }

    function closeModal() {
        document.getElementById('imageModal').close();
    }
</script>

<script>
    const hideContent = document.getElementById('answer');

    function showAnswer() {
        hideContent.style.display = "block";
    }
</script>


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



{{-- <div class="grid grid-cols-2 gap-6 mt-20 ml-4 border-2">
            <div class="pertanyaaan relative ... h-70 border-2">
                <label class="text-gray-900 font-medium text-lg">Jawaban</label>
                <textarea name="pertanyaan"
                    class="h-40 w-full bg-white shadow-xl shadow-gray-200 drop-shadow-xl outline-none rounded-xl text-xs p-2 resize-none mt-2 mb-16 {{ $errors->has('pertanyaan') ? 'border-[1px] border-red-500' : '' }}"
                    placeholder="Masukkan Pertanyaan"></textarea>
                <button
                    class="absolute right-0 bottom-0 w-[200px] h-10 bg-red-500 text-white font-bold rounded-lg">Kirim</button>
            </div>
            <div class="h-auto border-2">
                <span class="text-sm">Upload Gambar<sup>&#42;Optional</sup></span>
                <div class="upload-icon">
                    <div class="flex flex-col max-w-[260px]">
                        <div id="imagePreview" class="max-w-[140px]"> {{-- atur size image --
                            <img id="popupImage" alt="" class="">
                        </div>
                        <div id="textPreview" class="text-red-500 font-bold mt-2"></div>
                    </div>
                    <div class="text-icon"></div>
                </div>

                <div class="content-upload w-[200px] h-10 bg-red-500 text-white font-bold rounded-lg mt-6">
                    <label for="file-upload"
                        class="w-full h-full flex justify-center items-center cursor-pointer gap-2">
                        <i class="fa-solid fa-arrow-up-from-bracket"></i>
                        <a>Upload Gambar</a>
                    </label>
                    <input id="file-upload" name="image_tanya" class="hidden" onchange="previewImage(event)"
                        type="file" accept=".jpg, .png">
                </div>
            </div>
        </div> --}}


{{-- <div class="w-2/4 px-6 bg-white shadow-lg">
        <header class="w-full border-b-[1px] border-gray-200 pb-4">
            <div class="flex gap-4 mb-4">
                <div class="w-full text-lg">
                    <span class="flex gap-2 text-lg">
                        <p class="text-gray-900 font-medium">Nama Lengkap :</p>
                        <p>{{ $getTanya->nama_lengkap }}</p>
                    </span>
                </div>
                <div class="w-full text-lg">
                    <span class="flex gap-2 text-lg">
                        <p class="text-gray-900 font-medium">Sekolah :</p>
                        <p>{{ $getTanya->sekolah }}</p>
                    </span>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="w-full">
                    <span class="flex gap-2 text-lg">
                        <p class="text-gray-900 font-medium">Kelas :</p>
                        <p>{{ $getTanya->kelas }}</p>
                    </span>
                </div>
                <div class="w-full">
                    <span class="text-lg">
                        <span class="flex gap-2 text-lg">
                            <p class="text-gray-900 font-medium">Jam Tanya :</p>
                            <p>{{ $getTanya->created_at->locale('id')->translatedFormat('l d-M-Y') }}</p>
                        </span>
                    </span>
                </div>
            </div>
        </header>
        <div class="flex flex-col mt-4">
            <span class="flex gap-2 mb-2">
                <p class="text-gray-900 font-medium">Mata Pelajaran :</p>
                <p>{{ $getTanya->mapel }}</p>
            </span>
            <span class="flex gap-2">
                <p class="text-gray-900 font-medium">Bab :</p>
                <p>{{ $getTanya->bab }}</p>
            </span>
        </div>

        <div class="">
            <header class="mb-4 font-bold text-lg mt-8">Pertanyaan</header>
            <div
                class="w-[450px] h-[180px] bg-white shadow-xl shadow-gray-200 drop-shadow-xl rounded-xl overflow-y-auto text-sm p-4 mb-14">
                {{ $getTanya->pertanyaan }}
            </div>
        </div>
    </div> --}}


{{-- <div class="flex gap-20 w-full h-auto border-[1px] text-md border-gray-200 p-6">
    <div class="w-2/4">
        <div class="h-auto bg-white shadow-xl rounded-xl pt-4">
            <header class="w-full border-b-[1px] border-gray-200 pb-4">
                <div class="flex gap-4 mb-4">
                    <div class="w-full text-lg">
                        <span class="text-lg"><strong>Nama Lengkap :</strong></span>
                    </div>
                    <div class="w-full text-lg">
                        <span class="text-lg"><strong>Asal Sekolah :</strong></span>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="w-full">
                        <span class="text-lg"><strong>Kelas :</strong></span>
                    </div>
                    <div class="w-full">
                        <span class="text-lg"><strong>Jam Tanya :</strong></span>
                    </div>
                </div>
            </header>
            <div class= flex flex-col mt-4">
                <span class="text-lg mb-2"><strong>Mata Pelajaran :</strong></span>
                <span class="text-lg"><strong>Bab :</strong></span>
            </div>
            <div class="">
                <div class="flex gap-20 mt-8 mx-6 pb-40">
                    <div class="relative ...">
                        <header class="mb-4 font-bold text-lg">Pertanyaan</header>
                        <div
                            class="w-[450px] h-[180px] bg-white shadow-xl shadow-gray-200 drop-shadow-xl rounded-xl overflow-y-auto text-sm p-4 mb-14">
                        </div>
                        <form action="">
                            <button type="submit"
                                class="absolute left-0 gap-8 bg-[#092635] w-[150px] rounded-xl h-[50px] text-white font-bold">
                                <i class="fa-solid fa-chevron-left"></i>
                                <span class="text-lg">Kembali</span>
                            </button>
                        </form>
                    </div>
                    <div class="relative ...">
                        <header class="mb-4 font-bold text-lg">Image</header>
                        <div
                            class="w-[300px] h-[180px] bg-white shadow-xl shadow-gray-200 drop-shadow-xl rounded-xl text-sm overflow-hidden mb-14">
                            <img src="{{ asset('images_tanya/' . $getTanya->image_tanya) }}" alt=""
                                class="w-full h-full object-cover">
                        </div>
                        <div class="absolute right-0 flex">
                            <button class="btn btn-error text-white font-bold text-base w-[150px] rounded-xl mr-4"
                                onclick="my_modal_3.showModal()">Tolak</button>
                            <button class="btn btn-success text-white font-bold text-base w-[150px] rounded-xl"
                                onclick="showAnswer()">Terima</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="w-2/4 h-[500px] bg-white shadow-xl rounded-xl pt-4 relative ... hidden" id="answer">
        <form action="" method="POST" id="answerForm">
            <div class="flex mt-8 gap-20">
                <div class="answer">
                    <h1 class= font-bold text-lg">Jawaban</h1>
                    <textarea name="jawaban"
                        class="resize-none w-[400px] h-[180px] bg-white shadow-xl shadow-gray-200 drop-shadow-xl outline-none rounded-xl ml-6 mt-4 p-4"
                        placeholder="Masukkan Jawaban"></textarea>
                    <div id="errorAnswer" class="text-red-500 pl-6 mb-4 font-bold text-lg"></div>
                    <!--- Error message will be inserted here by JavaScript --->
                </div>
                <div class="upload-Image">
                    <div class="w-full h-auto">
                        <span class="text-sm">Upload Gambar<sup>&#42;Optional</sup></span>
                        <div class="upload-icon">
                            <div class="flex flex-col max-w-[260px]">
                                <div id="imagePreview" class="max-w-[140px]"> {{-- atur size image --
<img id="popupImage" alt="" class="">
</div>
<div id="textPreview" class="text-red-500 font-bold mt-2"></div>
</div>
<div class="text-icon"></div>
</div>

<div class="content-upload w-[200px] h-10 bg-red-500 text-white font-bold rounded-lg mt-6">
    <label for="file-upload" class="w-full h-full flex justify-center items-center cursor-pointer gap-2">
        <i class="fa-solid fa-arrow-up-from-bracket"></i>
        <a>Upload Gambar</a>
    </label>
    <input id="file-upload" name="image_tanya" class="hidden" onchange="previewImage(event)" type="file"
        accept=".jpg, .png">
</div>
</div>
</div>
</div>
</form>
</div>
</div> --}}
