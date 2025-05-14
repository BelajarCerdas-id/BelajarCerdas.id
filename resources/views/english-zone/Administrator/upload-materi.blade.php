@include('components/sidebar_beranda', ['headerSideNav' => 'Upload Materi'])
@extends('components/sidebar_beranda_mobile')
@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            @if (session('success-insert-materi-englishZone'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success-insert-materi-englishZone'),
                ])
            @endif
            <div class="bg-[--color-second] w-full h-20 shadow-lg rounded-t-xl flex items-center pl-10 mb-10">
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
                <div class="w-full h-auto px-4 my-4 relative" id="content">
                    <div class="pt-6 mb-4">
                        <h1>Tipe Upload</h1>

                        <input type="radio" name="tipeUpload" id="soal" value="materi" onclick="wrapperMateri()"
                            {{ session('formErrorId') != 'sertifikat' ? 'checked' : '' }}>
                        <label for="soal" class="radioList">
                            <a>Materi</a>
                        </label>

                        <input type="radio" name="tipeUpload" id="bulkUpload" value="sertifikat"
                            onclick="wrapperSertifikat()"
                            {{ session('formErrorId') === 'sertifikat' ? 'checked' : '' }}>
                        <label for="bulkUpload" class="radioList">
                            <a>Sertifikat</a>
                        </label>
                    </div>
                    <form action="{{ route('englishZone.uploadMateri') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="w-full h-auto">
                            <div id="wrapperMateri" data-tipe="{{ old('tipeUpload', 'materi') }}">
                                <div class="grid grid-cols-12 md:gap-8">
                                    <div class="col-span-12 md:col-span-6">
                                        <label class="mb-2 text-sm">Modul<sup
                                                class="text-red-500 pl-1">&#42;</sup></label>
                                        <select name="modul"
                                            class="w-full bg-white shadow-lg h-12 text-sm border-gray-200 border-[2px] outline-none rounded-md px-2
                                            focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] mb-2 cursor-pointer {{ $errors->has('modul') ? 'border-[1px] border-red-400' : '' }}"
                                            onchange="showModulDownload()">
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
                                            <option value="Modul 6"
                                                {{ @old('modul') === 'Modul 6' ? 'selected' : '' }}>
                                                Modul 6
                                            </option>
                                            <option value="Modul 7"
                                                {{ @old('modul') === 'Modul 7' ? 'selected' : '' }}>
                                                Modul 7
                                            </option>
                                            <option value="Modul 8"
                                                {{ @old('modul') === 'Modul 8' ? 'selected' : '' }}>
                                                Modul 8
                                            </option>
                                            <option value="Modul 9"
                                                {{ @old('modul') === 'Modul 9' ? 'selected' : '' }}>Modul 9
                                            </option>
                                            <option value="Modul 10"
                                                {{ @old('modul') === 'Modul 10' ? 'selected' : '' }}>Modul 10
                                            </option>
                                            <option value="Modul 11"
                                                {{ @old('modul') === 'Modul 11' ? 'selected' : '' }}>Modul 11
                                            </option>
                                            <option value="Modul 12"
                                                {{ @old('modul') === 'Modul 12' ? 'selected' : '' }}>Modul 12
                                            </option>
                                            <option value="Final Exam"
                                                {{ @old('modul') === 'Final Exam' ? 'selected' : '' }}>Final Exam
                                            </option>
                                        </select>
                                        @error('modul')
                                            <span class="text-red-500 font-bold text-sm pl-2">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-span-12 md:col-span-6">
                                        <label class="mb-2 text-sm">Jenjang<sup
                                                class="text-red-500 pl-1">&#42;</sup></label>
                                        <select name="modul_jenjang"
                                            class="w-full bg-white shadow-lg h-12 text-sm border-gray-200 border-[2px] outline-none rounded-md px-2
                                                focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] mb-2 cursor-pointer {{ $errors->has('modul_jenjang') ? 'border-[1px] border-red-400' : '' }}">
                                            <option value="" class="hidden">Pilih Jenjang</option>
                                            <option value="Beginner"
                                                {{ @old('modul_jenjang') === 'Beginner' ? 'selected' : '' }}>
                                                Beginner
                                            </option>
                                            <option value="Intermediate"
                                                {{ @old('modul_jenjang') === 'Intermediate' ? 'selected' : '' }}>
                                                Intermediate
                                            </option>
                                            <option value="Advanced"
                                                {{ @old('modul_jenjang') === 'Advanced' ? 'selected' : '' }}>
                                                Advanced
                                            </option>
                                            <option value="SD"
                                                {{ @old('modul_jenjang') === 'SD' ? 'selected' : '' }}>SD</option>
                                            <option value="SMP"
                                                {{ @old('modul_jenjang') === 'SMP' ? 'selected' : '' }}>SMP
                                            </option>
                                            <option value="SMA"
                                                {{ @old('modul_jenjang') === 'SMA' ? 'selected' : '' }}>SMA
                                            </option>
                                            {{-- <option value="Guru"
                                                {{ @old('modul_jenjang') === 'Guru' ? 'selected' : '' }}>Guru
                                            </option> --}}
                                        </select>
                                        @error('modul_jenjang')
                                            <span class="text-red-500 font-bold text-sm pl-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="md:w-[49%] mt-8">
                                    <label class="mb-2 text-sm">
                                        <span>Judul modul</span>
                                        <sup class="text-red-500 pl-1">&#42;</sup>
                                    </label>
                                    <input type="text" name="judul_modul"
                                        class="w-full bg-white shadow-lg h-12 text-sm border-gray-200 border-[2px] outline-none rounded-md px-2
                                        focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] mb-2 {{ $errors->has('judul_modul') ? 'border-[1px] border-red-400' : '' }}"
                                        placeholder="Masukkan judul modul" value="{{ old('judul_modul') }}">
                                    @error('judul_modul')
                                        <span class="text-red-500 font-bold text-sm pl-2">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div id="materi-container">
                                    <!-- Iterasi untuk menampilkan materi yang ada -->
                                    @foreach (old('judul_video', ['']) as $index => $judul)
                                        <div class="materi grid grid-cols-12 md:gap-8 mt-8">
                                            {{-- data-id="{{ $index + 1 }}"> --}}
                                            <div class="col-span-12 md:col-span-6">
                                                <label class="mb-2 text-sm">
                                                    <span>Judul video</span>
                                                    <span
                                                        class="materi-label">{{ count(old('judul_video', [''])) > 1 ? $index + 1 : '' }}</span>
                                                    <sup class="text-red-500 pl-1">&#42;</sup>
                                                </label>
                                                <input type="text" name="judul_video[]"
                                                    class="w-full bg-white shadow-lg h-12 text-sm border-gray-200 border-[2px] outline-none rounded-md px-2
                                                    focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] mb-2 {{ $errors->has('judul_video.' . $index) ? 'border-[1px] border-red-400' : '' }}"
                                                    placeholder="Masukkan judul video" value="{{ $judul }}">
                                                @error('judul_video.' . $index)
                                                    <span
                                                        class="text-red-500 font-bold text-sm pl-2">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-span-12 md:col-span-6 relative">
                                                <label class="mb-2 text-sm">
                                                    <span>Link video</span>
                                                    <span
                                                        class="materi-label">{{ count(old('link_video', [''])) > 1 ? $index + 1 : '' }}</span>
                                                    <sup class="text-red-500 pl-1">&#42;</sup>
                                                </label>
                                                <input type="url" name="link_video[]"
                                                    class="w-full bg-white shadow-lg h-12 text-sm border-gray-200 border-[2px] outline-none rounded-md px-2
                                                    focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] mb-2 {{ $errors->has('link_video.' . $index) ? 'border-[1px] border-red-400' : '' }}"
                                                    placeholder="Masukkan link video materi"
                                                    value="{{ old('link_video')[$index] ?? '' }}">
                                                @error('link_video.' . $index)
                                                    <span
                                                        class="text-red-500 font-bold text-sm pl-2">{{ $message }}</span>
                                                @enderror
                                                @if ($index > 0)
                                                    <button type="button"
                                                        class="hapus-materi absolute top-0 right-2 text-red-500 font-bold">
                                                        <i class="fa-solid fa-trash"></i>
                                                        <span class="text-sm">Hapus</span>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <button id="tambah-materi" type="button"
                                    class="bg-[#4189e0] hover:bg-blue-500 w-[200px] h-8 text-white font-bold rounded-lg my-8 text-sm">
                                    <i class="fas fa-plus"></i>
                                    <span>Tambah Materi</span>
                                </button>

                                <div class="grid grid-cols-12 md:gap-8">
                                    <!-- Upload PDF -->
                                    <div class="col-span-12 md:col-span-6 mt-[-2px]">
                                        <div class="w-full">
                                            <div class="w-full h-auto">
                                                <span class="text-sm">Upload materi<sup
                                                        class="text-red-500 pl-1">&#42;</sup></span>
                                                <div class="text-xs mt-1">
                                                    <span>Maksimum ukuran file 10MB. <br> File dapat dalam format
                                                        PDF</span>
                                                </div>
                                                <div class="upload-icon">
                                                    <div class="flex flex-col max-w-[260px]">
                                                        <div id="pdfPreview"
                                                            class="max-w-[280px] cursor-pointer mt-4">
                                                            <div id="pdfPreviewContainer-materi"
                                                                class="bg-white shadow-lg rounded-lg w-max py-2 pr-4 border-[1px] border-gray-200 hidden">
                                                                <div class="flex">
                                                                    <img id="pdfLogo-materi" class="w-[56px] h-max">
                                                                    <div class="mt-2 leading-5">
                                                                        <span id="textPreview-materi"
                                                                            class="font-bold text-sm"></span><br>
                                                                        <span id="textSize-materi"
                                                                            class="text-xs"></span>
                                                                        <span id="textCircle-materi"
                                                                            class="relative top-[-2px] text-[5px]"></span>
                                                                        <span id="textPages-materi"
                                                                            class="text-xs"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="content-upload w-full h-10 bg-[#4189e0] hover:bg-blue-500 text-white font-bold rounded-lg mt-6 mb-2">
                                                <label for="file-upload-materi"
                                                    class="w-full h-full flex justify-center items-center cursor-pointer gap-2">
                                                    <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                                    <span>Upload PDF</span>
                                                </label>
                                                <input id="file-upload-materi" name="materi_pdf" class="hidden"
                                                    onchange="previewPDF(event, 'materi')" type="file"
                                                    accept="application/pdf">
                                            </div>
                                            @error('materi_pdf')
                                                <span
                                                    class="relative top-2 text-red-500 font-bold text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-span-12 md:col-span-6 mt-10 md:mt-[-2px] hidden" id="uploadModul">
                                        <div class="w-full">
                                            <div class="w-full h-auto">
                                                <span class="text-sm">Upload modul download<sup
                                                        class="text-red-500 pl-1">&#42;</sup></span>
                                                <div class="text-xs mt-1">
                                                    <span>Maksimum ukuran file 10MB. <br> File dapat dalam format
                                                        PDF</span>
                                                </div>
                                                <div class="upload-icon">
                                                    <div class="flex flex-col max-w-[260px]">
                                                        <div id="pdfPreview"
                                                            class="max-w-[280px] cursor-pointer mt-4">
                                                            <div id="pdfPreviewContainer-modul"
                                                                class="bg-white shadow-lg rounded-lg w-max py-2 pr-4 border-[1px] border-gray-200 hidden">
                                                                <div class="flex">
                                                                    <img id="pdfLogo-modul" class="w-[56px] h-max">
                                                                    <div class="mt-2 leading-5">
                                                                        <span id="textPreview-modul"
                                                                            class="font-bold text-sm"></span><br>
                                                                        <span id="textSize-modul"
                                                                            class="text-xs"></span>
                                                                        <span id="textCircle-modul"
                                                                            class="relative top-[-2px] text-[5px]"></span>
                                                                        <span id="textPages-modul"
                                                                            class="text-xs"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="content-upload w-full h-10 bg-[#4189e0] hover:bg-blue-500 text-white font-bold rounded-lg mt-6 mb-2">
                                                <label for="file-upload-modul"
                                                    class="w-full h-full flex justify-center items-center cursor-pointer gap-2">
                                                    <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                                    <span>Upload Modul</span>
                                                </label>
                                                <input id="file-upload-modul" name="modul_download" class="hidden"
                                                    onchange="previewPDF(event, 'modul')" type="file"
                                                    accept="application/pdf">
                                            </div>
                                            @error('modul_download')
                                                <span
                                                    class="relative top-2 text-red-500 font-bold text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <input type="text" id="text"></input>
                                <div class="flex justify-end mt-8">
                                    <button
                                        class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all">
                                        Kirim
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div id="wrapperSertifikat" class="hidden" data-tipe="{{ old('tipeUpload', 'sertifikat') }}">
                        <!-- Upload Sertifikat -->
                        <form action="{{ route('certificate.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="hidden">
                                <input type="text" name="nama_lengkap"
                                    value="{{ Auth::user()->OfficeProfiles->nama_lengkap }}">
                                <input type="text" name="status" value="{{ Auth::user()->role }}">
                            </div>
                            <div class="col-span-12 md:col-span-6 mt-10">
                                <span class="text-sm">Upload Sertifikat<sup
                                        class="text-red-500 pl-1">&#42;</sup></span>
                                <div class="text-xs mt-1">
                                    <span>Maksimum ukuran file 2MB. <br> File dapat dalam format
                                        .jpg/.png/.jpeg.</span>
                                </div>
                                <div class="upload-icon">
                                    <div class="flex flex-col max-w-[260px]">
                                        <div id="imagePreview" class="max-w-[140px] cursor-pointer mt-4"
                                            onclick="openModal()">
                                            <!-- Image will be inserted here -->
                                            <img id="popupImage" alt="" class="object-contain">
                                        </div>
                                        <div id="textPreview" class="text-red-500 font-bold mt-2"></div>
                                    </div>
                                    <div class="text-icon"></div>
                                </div>

                                <div
                                    class="content-upload w-full h-10 bg-[#4189e0] text-white font-bold rounded-lg mt-6 mb-2">
                                    <label for="image-upload"
                                        class="w-full h-full flex justify-center items-center cursor-pointer gap-2">
                                        <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                        <span>Upload Sertifikat</span>
                                    </label>
                                    <input id="image-upload" name="certificate" class="hidden"
                                        onchange="previewImage(event)" type="file" accept=".jpg, .png, .jpeg">
                                </div>
                                @error('certificate')
                                    <span class="relative top-2 text-red-500 font-bold text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="w-full relative flex justify-end">
                                <button
                                    class="bg-[#4189e0] w-full md:w-[200px] h-8 text-white font-bold rounded-lg mt-20 mb-8 md:mx-2">Kirim</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- modal for show certificate image --->
                <dialog id="imageModal" class="modal">
                    <div class="modal-box bg-white">
                        <button class="absolute right-2 top-2 outline-none hover:bg-slate-200 btn-circle"
                            onclick="closeModal()">✕</button>
                        <img id="modalImage" alt="" class="w-full h-auto">
                    </div>
                </dialog>
                <div class="w-full h-auto hidden" id="riwayat">
                    <span>Riwayat</span>
                </div>
                @if (session('failed-insert-certificate'))
                    @include('components.alert.failed-insert-data', [
                        'message' => session('failed-insert-certificate'),
                    ])
                @endif
            </div>
        </div>
    </div>
@else
    <div class="flex flex-col min-h-screen items-center justify-center">
        <p>ALERT SEMENTARA</p>
        <p>You do not have access to this pages.</p>
    </div>
@endif


<script src="js/content-riwayat.js"></script> {{-- content slide --}}
<script src="js/upload-pdf.js"></script> {{-- upload pdf --}}

<script>
    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(function() {
            document.getElementById('alertSuccess').remove();
        }, 3000);

        document.getElementById('btnClose').addEventListener('click', function() {
            document.getElementById('alertSuccess').remove();
        })
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cek semua inputan dan hapus error message ketika user mengetik
        document.querySelectorAll('input, select, textarea').forEach(function(el) {
            el.addEventListener('input', function() {
                // Hapus error class
                el.classList.remove('border-red-400');
                const errorMessage = el.nextElementSibling;
                if (errorMessage && errorMessage.classList.contains('text-red-500')) {
                    errorMessage.remove();
                }
            });
        });
    });
</script>

<script>
    const materi = document.getElementById('wrapperMateri');
    const sertifikat = document.getElementById('wrapperSertifikat');
    const tipeUpload = document.querySelector('input[name="tipeUpload"]').value;

    function wrapperMateri(element) {
        materi.classList.remove('hidden');
        sertifikat.classList.add('hidden');
    }

    function wrapperSertifikat(element) {
        materi.classList.add('hidden');
        sertifikat.classList.remove('hidden');
    }

    document.addEventListener("DOMContentLoaded", function() {
        @if (session('formErrorId') === 'sertifikat')
            wrapperSertifikat();
            materi.classList.add('hidden');
        @endif
    });
</script>

<script>
    function showModulDownload() {
        // Ambil nilai modul yang dipilih
        var selectedModul = document.querySelector('select[name="modul"]').value;

        // Cek apakah pilihan adalah 'Final Exam'
        if (selectedModul === 'Final Exam') {
            // Tampilkan input upload sertifikat jika Final Exam dipilih
            document.getElementById('uploadModul').classList.remove('hidden');
            document.getElementById('uploadModul').classList.add('block');
        } else {
            // Sembunyikan input upload sertifikat jika pilihan bukan 'Final Exam'
            document.getElementById('uploadModul').classList.add('hidden');
            document.getElementById('uploadModul').classList.remove('block');
        }

    }
    // Jika halaman di-load dan ada pilihan 'Final Exam', tampilkan sertifikat
    window.onload = function() {
        showModulDownload();
    };
</script>
<script>
    // show upload image
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

    // popup result upload image
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
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('materi-container'); // Container utama
        const tambahMateriButton = document.getElementById('tambah-materi'); // Tombol tambah materi

        // Fungsi untuk memperbarui label dan ID
        function updateMateriLabels() {
            const materiElements = container.querySelectorAll('.materi'); // Semua elemen 'materi'
            materiElements.forEach((materi, index) => {
                const labels = materi.querySelectorAll('.materi-label');
                labels.forEach(label => {
                    label.textContent = materiElements.length > 1 ? index + 1 :
                        ''; // Perbarui nomor urut
                });
                materi.setAttribute('data-id', index + 1); // Update ID berdasarkan urutan
            });
        }

        // Event untuk menambahkan materi baru
        tambahMateriButton.addEventListener('click', function() {
            const newMateri = document.createElement('div'); // Elemen materi baru
            newMateri.classList.add('materi', 'flex', 'lg:gap-12', 'gap-4', 'flex-col', 'lg:flex-row',
                'my-4');
            newMateri.innerHTML = `
        <div class="w-full">
            <label class="mb-2 text-sm">
                <span>Judul video</span>
                <span class="materi-label"></span>
                <sup class="text-red-500 pl-1">&#42;</sup>
            </label>
            <input type="text" name="judul_video[]"
                class="w-full bg-white shadow-lg h-12 text-sm border-gray-200 border-[2px] outline-none rounded-md px-2
                        focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] my-4" placeholder="Masukkan judul materi" value="">
        </div>
        <div class="w-full relative">
            <label class="mb-2 text-sm">
                <span>Link video</span>
                <span class="materi-label"></span>
                <sup class="text-red-500 pl-1">&#42;</sup>
            </label>
            <input type="url" name="link_video[]"
                class="w-full bg-white shadow-lg h-12 text-sm border-gray-200 border-[2px] outline-none rounded-md px-2
                        focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] my-4" placeholder="Masukkan link video materi" value="">
            <button type="button" class="hapus-materi absolute top-0 right-2 text-red-500 font-bold">
                <i class="fa-solid fa-trash"></i>
                <span class="text-sm">Hapus</span>
            </button>
        </div>`;
            container.appendChild(newMateri); // Tambah elemen ke container
            updateMateriLabels(); // Perbarui label dan ID
        });

        // Event untuk menghapus materi berdasarkan ID
        container.addEventListener('click', function(event) {
            if (event.target.closest('.hapus-materi')) {
                const materiToDelete = event.target.closest('.materi');
                if (materiToDelete) {
                    // const materiId = materiToDelete.getAttribute('data-id'); // Ambil ID dari elemen
                    materiToDelete.remove(); // Hapus elemen dari DOM
                    updateMateriLabels(); // Perbarui label dan ID
                }
            }
        });

        // Jalankan update label saat halaman dimuat
        updateMateriLabels();
    });
</script>
