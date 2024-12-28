@include('components/sidebar_beranda')
@extends('components/sidebar_beranda_mobile')

@if (isset($user))
    @if ($user->status === 'Siswa')
        <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
            <div class="content-beranda">
                <div class="bg-[--color-default] w-full h-20 shadow-lg rounded-t-xl flex items-center pl-10 mb-10">
                    <div class="text-white font-bold flex items-center gap-4">
                        <i class="fa-solid fa-file-lines text-4xl"></i>
                        <span class="text-xl">English Zone</span>
                    </div>
                </div>
                <div class="flex mt-10">
                    <div class="w-full hover:bg-gray-100" onclick="content()">
                        <input type="radio" class="hidden" name="radio" id="radio1" checked>
                        <div class="checked-timeline">
                            <label for="radio1" class="cursor-pointer">
                                <span class="text-lg flex justify-center relative top-1">Materi</span>
                                <div class="w-full border-b-[1px] border-gray-200 h-2"></div>
                            </label>
                        </div>
                    </div>
                    <div class="w-full hover:bg-gray-100" onclick="riwayat()">
                        <input type="radio" class="hidden" name="radio" id="radio2">
                        <div class="checked-timeline">
                            <label for="radio2" class="cursor-pointer">
                                <span class="text-lg flex justify-center relative top-1">Riwayat</span>
                                <div class="w-full border-b-[1px] border-gray-200 h-2"></div>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="relative w-full h-auto overflow-hidden bg-white shadow-lg">
                    <div class="w-full h-auto" id="content">
                        materi
                    </div>
                    <div class="w-full h-auto hidden" id="riwayat">
                        riwayat
                    </div>
                </div>
            </div>
        </div>
    @elseif($user->status === 'Mentor')
        <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
            <div class="content-beranda">
                <form action="{{ route('englishZone.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label>Judul PDF:</label>
                    <input type="text" name="mentor" value="{{ $user->nama_lengkap }}" required><br><br>

                    <label>File PDF:</label>
                    <input type="file" name="pdf_file" accept="application/pdf" required><br><br>

                    <button type="submit">Unggah PDF</button>
                    @foreach ($get as $item)
                        <span>{{ $item->mentor }}</span>
                        <a href="{{ route('englishZone.show', $item->id) }}" target="_blank">Lihat</a>
                    @endforeach
                    <input type="url" class="border-4">
                    {{-- @foreach ($get as $data)
                        <div>
                            <h3>Mentor: {{ $data->mentor }}</h3>

                            @if (!empty($data->pdf_file))
                                <!-- Tampilkan PDF menggunakan iframe -->
                                <iframe src="{{ asset('storage/' . $data->pdf_file) }}" width="100%" height="600px"
                                    class="border-4"></iframe>
                                <p>
                                    <a href="{{ asset('storage/' . $data->pdf_file) }}" target="_blank">Lihat PDF</a>
                                </p>
                            @else
                                <p>Tidak ada file PDF untuk ditampilkan.</p>
                            @endif
                        </div>
                        <hr>
                    @endforeach --}}
                </form>
            </div>
        </div>
    @elseif($user->status === 'Murid')
        <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
            <div class="content-beranda">
                <div class="bg-[--color-default] w-full h-20 shadow-lg rounded-t-xl flex items-center pl-10 mb-10">
                    <div class="text-white font-bold flex items-center gap-4">
                        <i class="fa-solid fa-file-lines text-4xl"></i>
                        <span class="text-xl">English Zone</span>
                    </div>
                </div>
                <div class="flex mt-10">
                    <div class="w-full hover:bg-gray-100" onclick="content()">
                        <input type="radio" class="hidden" name="radio" id="radio1" checked>
                        <div class="checked-timeline">
                            <label for="radio1" class="cursor-pointer">
                                <span class="text-lg flex justify-center relative top-1">Materi</span>
                                <div class="w-full border-b-[1px] border-gray-200 h-2"></div>
                            </label>
                        </div>
                    </div>
                    <div class="w-full hover:bg-gray-100" onclick="riwayat()">
                        <input type="radio" class="hidden" name="radio" id="radio2">
                        <div class="checked-timeline">
                            <label for="radio2" class="cursor-pointer">
                                <span class="text-lg flex justify-center relative top-1">Riwayat</span>
                                <div class="w-full border-b-[1px] border-gray-200 h-2"></div>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="relative w-full h-auto overflow-hidden bg-white shadow-lg">
                    <div class="w-full h-auto" id="content">
                        @foreach ($getMateri as $item)
                            <div class="container-accordion">
                                <div class="wrapper-content-accordion">
                                    <button class="toggleButton">
                                        <div class="">
                                            <span>{{ $item->modul }}.</span>
                                            <span>{{ $item->judul }}</span>
                                        </div>
                                        <i class="fa-solid fa-chevron-up icon"></i>
                                    </button>
                                    <div class="content-accordion">
                                        <div class="wrapper-content-accordion1">
                                            <div class="box-content-bab">
                                                <div class="title-content-bab">
                                                    <div class="logo-content-bab"></div>
                                                    <div class="header-title">
                                                        <span> Materi </span>
                                                    </div>
                                                    <div class="bottom-title">
                                                        <span> {{ $item->judul }} </span>
                                                    </div>
                                                </div>
                                                <button class="button-link-concept" formaction="">
                                                    <div onclick="showMateri(this)"
                                                        data-pdf="{{ asset('englishZone_pdf/' . $item->pdf_file) }}#toolbar=0">
                                                        Lihat Materi
                                                        <i class="fa-solid fa-chevron-right"></i>
                                                    </div>
                                                </button>
                                            </div>
                                            <div class="box-content-bab">
                                                <div class="title-content-bab">
                                                    <div class="logo-content-bab"></div>
                                                    <div class="header-title">
                                                        <span> Video Materi </span>
                                                    </div>
                                                    <div class="bottom-title">
                                                        <span> {{ $item->judul }} </span>
                                                    </div>
                                                </div>
                                                <button class="button-link-ppt">
                                                    <a href="{{ $item->video_materi }}" target="_blank"> Lihat Video
                                                    </a>
                                                </button>
                                            </div>
                                            <div class="box-content-bab">
                                                <div class="title-content-bab">
                                                    <div class="logo-content-bab"></div>
                                                    <div class="header-title">
                                                        <span> Pengayaan </span>
                                                    </div>
                                                    <div class="bottom-title">
                                                        <span> {{ $item->judul }} </span>
                                                    </div>
                                                </div>
                                                <form>
                                                    <button class="button-link-ebook" formaction="">
                                                        <a href="/pengayaan"> Lihat Soal </a>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <dialog id="my_modal_5" class="modal">
                            <div class="modal-box bg-white">
                                <!-- Menggunakan iframe untuk menampilkan PDF -->
                                <iframe id="modal_image" src="" class="hidden min-w-full h-[735px]"></iframe>

                                <div class="h-full w-full flex items-center justify-center" id="no_image"
                                    style="display: none;">
                                    <p>PDF tidak tersedia</p>
                                </div>
                            </div>
                            <form method="dialog" class="modal-backdrop">
                                <button>close</button>
                            </form>
                        </dialog>

                    </div>
                    <div class="w-full h-auto hidden" id="riwayat">
                        riwayat
                    </div>
                </div>
            </div>
        </div>
    @elseif($user->status === 'Guru')

    @elseif($user->status === 'Administrator')
        <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
            <div class="content-beranda">
                <div class="bg-[--color-default] w-full h-20 shadow-lg rounded-t-xl flex items-center pl-10 mb-10">
                    <div class="text-white font-bold flex items-center gap-4">
                        <i class="fa-solid fa-file-lines text-4xl"></i>
                        <span class="text-xl">English Zone</span>
                    </div>
                </div>
                <div class="flex mt-10">
                    <div class="w-full hover:bg-gray-100" onclick="content()">
                        <input type="radio" class="hidden" name="radio" id="radio1" checked>
                        <div class="checked-timeline">
                            <label for="radio1" class="cursor-pointer">
                                <span class="text-lg flex justify-center relative top-1">Materi</span>
                                <div class="w-full border-b-[1px] border-gray-200 h-2"></div>
                            </label>
                        </div>
                    </div>
                    <div class="w-full hover:bg-gray-100" onclick="riwayat()">
                        <input type="radio" class="hidden" name="radio" id="radio2">
                        <div class="checked-timeline">
                            <label for="radio2" class="cursor-pointer">
                                <span class="text-lg flex justify-center relative top-1">Riwayat</span>
                                <div class="w-full border-b-[1px] border-gray-200 h-2"></div>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="relative w-full h-auto overflow-hidden bg-white shadow-lg">
                    <div class="w-full h-auto" id="content">
                        <form action="{{ route('englishZone.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="hidden">
                                <input type="text" name="nama_lengkap" value="{{ $user->nama_lengkap }}">
                                <input type="text" name="email" value="{{ $user->email }}">
                                <input type="text" name="status" value="{{ $user->status }}">
                            </div>
                            <div class="flex lg:gap-12 gap-4 flex-col lg:flex-row mx-4 mt-6 mb-8">
                                <div class="w-full">
                                    <label class="mb-2 text-sm">Modul<sup
                                            class="text-red-500 pl-1">&#42;</sup></label>
                                    <select name="modul"
                                        class="w-full bg-gray-100 outline-none rounded-xl text-xs p-3 cursor-pointer mt-2 mb-2 {{ $errors->has('modul') ? 'border-[1px] border-red-500' : '' }}">
                                        <option value="" class="hidden">Pilih Modul</option>
                                        <option value="Modul 1" {{ @old('modul') === 'Modul 1' ? 'selected' : '' }}>
                                            Modul 1
                                        </option>
                                        <option value="Modul 2" {{ @old('modul') === 'Modul 2' ? 'selected' : '' }}>
                                            Modul 2
                                        </option>
                                        <option value="Modul 3" {{ @old('modul') === 'Modul 3' ? 'selected' : '' }}>
                                            Modul 3
                                        </option>
                                        <option value="Modul 4" {{ @old('modul') === 'Modul 4' ? 'selected' : '' }}>
                                            Modul 4
                                        </option>
                                        <option value="Modul 5" {{ @old('modul') === 'Modul 5' ? 'selected' : '' }}>
                                            Modul 5
                                        </option>
                                        <option value="Modul 6" {{ @old('modul') === 'Modul 6' ? 'selected' : '' }}>
                                            Modul 6
                                        </option>
                                        <option value="Modul 7" {{ @old('modul') === 'Modul 7' ? 'selected' : '' }}>
                                            Modul 7
                                        </option>
                                        <option value="Modul 8" {{ @old('modul') === 'Modul 8' ? 'selected' : '' }}>
                                            Modul 8
                                        </option>
                                        <option value="Modul 9" {{ @old('modul') === 'Modul 9' ? 'selected' : '' }}>
                                            Modul 9
                                        </option>
                                        <option value="Modul 10" {{ @old('modul') === 'Modul 10' ? 'selected' : '' }}>
                                            Modul 10
                                        </option>
                                        <option value="Modul 11" {{ @old('modul') === 'Modul 11' ? 'selected' : '' }}>
                                            Modul 11
                                        </option>
                                        <option value="Modul 12" {{ @old('modul') === 'Modul 12' ? 'selected' : '' }}>
                                            Modul 12
                                        </option>
                                    </select>
                                    @error('modul')
                                        <span class="text-red-500 font-bold text-sm pl-2">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="w-full">
                                    <label class="mb-2 text-sm">Jenjang<sup
                                            class="text-red-500 pl-1">&#42;</sup></label>
                                    <select name="jenjang_murid"
                                        class="w-full bg-gray-100 outline-none rounded-xl text-xs p-3 cursor-pointer mt-2 mb-2 {{ $errors->has('jenjang_murid') ? 'border-[1px] border-red-500' : '' }}">
                                        <option value="" class="hidden">Pilih Jenjang</option>
                                        <option value="SD" {{ @old('jenjang_murid') === 'SD' ? 'selected' : '' }}>
                                            SD
                                        </option>
                                        <option value="SMP"
                                            {{ @old('jenjang_murid') === 'SMP' ? 'selected' : '' }}>SMP</option>
                                        <option value="SMA"
                                            {{ @old('jenjang_murid') === 'SMA' ? 'selected' : '' }}>SMA</option>
                                        <option value="Guru"
                                            {{ @old('jenjang_murid') === 'Guru' ? 'selected' : '' }}>Guru</option>
                                    </select>
                                    @error('jenjang_murid')
                                        <span class="text-red-500 font-bold text-sm pl-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="flex lg:gap-12 gap-4 flex-col lg:flex-row mx-4 mb-8">
                                <div class="w-full">
                                    <label class="mb-2 text-sm">Judul<sup
                                            class="text-red-500 pl-1">&#42;</sup></label>
                                    <input type="text" name="judul"
                                        class="w-full bg-gray-100 outline-none rounded-xl text-xs p-3 cursor-pointer mt-2 {{ $errors->has('judul') ? 'border-[1px] border-red-500' : '' }}"
                                        placeholder="Masukkan judul materi" value="{{ @old('judul') }}">
                                    @error('judul')
                                        <span class="text-red-500 font-bold text-sm pl-2">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="w-full">
                                    <label class="mb-2 text-sm">Video Materi<sup
                                            class="text-red-500 pl-1">&#42;</sup></label>
                                    <input type="url" name="video_materi"
                                        class="w-full bg-gray-100 outline-none rounded-xl text-xs p-3 cursor-pointer mt-2 {{ $errors->has('video_materi') ? 'border-[1px] border-red-500' : '' }}"
                                        placeholder="Masukkan link video materi" value="{{ @old('video_materi') }}">
                                    @error('video_materi')
                                        <span class="text-red-500 font-bold text-sm pl-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="flex lg:gap-12 gap-4 flex-col lg:flex-row mx-4 mb-8">
                                <div class="w-[48.5%]">
                                    <div class="w-2/4 h-auto">
                                        <span class="text-sm">Upload PDF<sup
                                                class="text-red-500 pl-1">&#42;</sup></span>
                                        <div class="text-xs mt-1">
                                            <span>Maksimum ukuran file 10MB.</span>
                                        </div>
                                        <div class="upload-icon">
                                            <div class="flex flex-col max-w-[260px]">
                                                <div id="pdfPreview" class="max-w-[280px] cursor-pointer mt-4"
                                                    onclick="openModal()">
                                                    <!-- Gambar thumbnail PDF akan dimuat di sini -->
                                                    {{-- <img id="pdfThumbnail" alt="PDF Thumbnail"
                                                            class="w-full h-full object-cover"> --}}
                                                    <div id="pdfPreviewContainer"
                                                        class="bg-white shadow-lg rounded-lg w-80 py-2 border-[1px] border-gray-200 hidden">
                                                        <div class="flex">
                                                            <img id="pdfLogo" class="w-[56px] h-max">
                                                            <div class="mt-2 leading-5">
                                                                <span id="textPreview"
                                                                    class="font-bold text-sm"></span><br>
                                                                <span id="textSize" class="text-xs"></span>
                                                                <span id="textCircle"
                                                                    class="relative top-[-2px]"></span>
                                                                <span id="textPages" class="text-xs"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="content-upload w-[200px] h-10 bg-red-500 text-white font-bold rounded-lg mt-6 mb-2">
                                        <label for="file-upload"
                                            class="w-full h-full flex justify-center items-center cursor-pointer gap-2">
                                            <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                            <span>Upload PDF</span>
                                        </label>
                                        <input id="file-upload" name="pdf_file" class="hidden"
                                            onchange="previewPDF(event)" type="file" accept="application/pdf">
                                    </div>
                                    @error('pdf_file')
                                        <span class="text-red-500 font-bold text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <button
                                    class="bg-red-500 w-[150px] h-8 text-white font-bold rounded-lg mt-4">Kirim</button>
                            </div>
                    </div>
                    </form>
                </div>
                <div class="w-full h-auto hidden" id="riwayat">
                    @foreach ($getSoal as $item)
                        <textarea class="task-textarea" name="" cols="30" rows="10">
                            {!! $item->description !!}
                        </textarea>
                    @endforeach
                </div>
            </div>
        </div>
        </div>
    @else
        <div class="flex flex-col min-h-screen items-center justify-center">
            <p>ALERT SEMENTARA</p>
            <p>You do not have access to this pages.</p>
        </div>
    @endif
@else
    <p>You are not logged in.</p>
@endif

<script src="js/content-riwayat.js"></script> {{-- content slide --}}
<script src="js/accordion.js"></script> {{-- accordion script --}}
<script src="js/upload-pdf.js"></script> {{-- upload image(js) --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>

<script>
    // Menerapkan CKEditor untuk setiap elemen dengan kelas 'task-textarea'
    document.querySelectorAll('.task-textarea').forEach(function(textarea) {
        ClassicEditor
            .create(textarea)
            .catch(error => {
                console.error(error);
            });
    });
</script>

<script>
    function showMateri(element) {
        const modal = document.getElementById('my_modal_5');
        const modalImage = document.getElementById('modal_image');
        const modalCatatan = document.getElementById('modal_catatan');
        const noImage = document.getElementById('no_image');
        const headCatatan = document.getElementById('head-catatan');

        // Ambil data dari atribut
        const imageSrc = element.getAttribute('data-pdf');
        const catatan = element.getAttribute('data-catatan');

        // Set gambar dan data
        modalImage.src = imageSrc;

        // Cek apakah sumber gambar tidak kosong
        if (imageSrc) {
            modalImage.style.display = 'block';
            noImage.style.display = 'none';
        } else {
            modalImage.style.display = 'none';
            noImage.style.display = 'block';
        }
        // Tampilkan dialog
        modal.showModal();
    }
</script>
