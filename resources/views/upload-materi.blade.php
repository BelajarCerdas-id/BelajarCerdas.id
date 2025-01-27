@include('components/sidebar_beranda')
@extends('components/sidebar_beranda_mobile')
@if (isset($user))
    @if ($user->status === 'Administrator')
        <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
            <div class="content-beranda">
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
                    <div class="w-full h-auto" id="content">
                        <form action="{{ route('englishZone.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="hidden">
                                <input type="text" name="nama_lengkap" value="{{ $user->nama_lengkap }}">
                                <input type="text" name="email" value="{{ $user->email }}">
                                <input type="text" name="status" value="{{ $user->status }}">
                            </div>
                            <div class="flex lg:gap-6 gap-4 flex-col lg:flex-row mx-4 mt-6 mb-8">
                                <div class="w-full">
                                    <label class="mb-2 text-sm">Modul<sup class="text-red-500 pl-1">&#42;</sup></label>
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
                                        <option value="SMP" {{ @old('jenjang_murid') === 'SMP' ? 'selected' : '' }}>
                                            SMP</option>
                                        <option value="SMA" {{ @old('jenjang_murid') === 'SMA' ? 'selected' : '' }}>
                                            SMA</option>
                                        <option value="Guru"
                                            {{ @old('jenjang_murid') === 'Guru' ? 'selected' : '' }}>Guru</option>
                                    </select>
                                    @error('jenjang_murid')
                                        <span class="text-red-500 font-bold text-sm pl-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="w-[48.5%] mx-4 mb-8">
                                <label class="mb-2 text-sm">
                                    <span>Judul modul</span>
                                    <sup class="text-red-500 pl-1">&#42;</sup>
                                </label>
                                <input type="text" name="judul_modul"
                                    class="w-full bg-gray-100 outline-none rounded-xl text-xs p-3 cursor-pointer mt-2 mb-2 {{ $errors->has('judul_modul.') ? 'border-[1px] border-red-500' : '' }}"
                                    placeholder="Masukkan judul modul" value="{{ old('judul_modul') }}">
                                @error('judul_modul')
                                    <span class="text-red-500 font-bold text-sm pl-2">{{ $message }}</span>
                                @enderror
                            </div>
                            <div id="materi-container">
                                <!-- Iterasi untuk menampilkan materi yang ada -->
                                @foreach (old('judul_video', ['']) as $index => $judul)
                                    <div class="materi flex lg:gap-6 gap-4 flex-col lg:flex-row mx-4 mb-8">
                                        {{-- data-id="{{ $index + 1 }}"> --}}
                                        <div class="w-full">
                                            <label class="mb-2 text-sm">
                                                <span>Judul video</span>
                                                <span
                                                    class="materi-label">{{ count(old('judul_video', [''])) > 1 ? $index + 1 : '' }}</span>
                                                <sup class="text-red-500 pl-1">&#42;</sup>
                                            </label>
                                            <input type="text" name="judul_video[]"
                                                class="w-full bg-gray-100 outline-none rounded-xl text-xs p-3 cursor-pointer mt-2 mb-2 {{ $errors->has('judul_video.' . $index) ? 'border-[1px] border-red-500' : '' }}"
                                                placeholder="Masukkan judul video" value="{{ $judul }}">
                                            @error('judul_video.' . $index)
                                                <span
                                                    class="text-red-500 font-bold text-sm pl-2">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="w-full relative">
                                            <label class="mb-2 text-sm">
                                                <span>Link video</span>
                                                <span
                                                    class="materi-label">{{ count(old('link_video', [''])) > 1 ? $index + 1 : '' }}</span>
                                                <sup class="text-red-500 pl-1">&#42;</sup>
                                            </label>
                                            <input type="url" name="link_video[]"
                                                class="w-full bg-gray-100 outline-none rounded-xl text-xs p-3 cursor-pointer mt-2 mb-2 {{ $errors->has('link_video.' . $index) ? 'border-[1px] border-red-500' : '' }}"
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

                            <!-- Tombol untuk menambahkan materi -->
                            <button id="tambah-materi" type="button"
                                class="bg-[#4189e0] w-[200px] h-8 text-white font-bold rounded-lg mx-4 mb-6 text-sm">
                                <i class="fas fa-plus"></i>
                                <span>Tambah Materi</span>
                            </button>


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
                                                        class="bg-white shadow-lg rounded-lg w-max py-2 pr-4 border-[1px] border-gray-200 hidden">
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
                    <div class="w-full h-auto hidden" id="riwayat">
                        <span>Riwayat</span>
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
<script src="js/upload-pdf.js"></script> {{-- upload PDF(js) --}}

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
                'mx-4', 'mb-8');
            newMateri.innerHTML = `
        <div class="w-full">
            <label class="mb-2 text-sm">
                <span>Judul video</span>
                <span class="materi-label"></span>
                <sup class="text-red-500 pl-1">&#42;</sup>
            </label>
            <input type="text" name="judul_video[]"
                class="w-full bg-gray-100 outline-none rounded-xl text-xs p-3 cursor-pointer mt-2 mb-2" placeholder="Masukkan judul materi" value="">
        </div>
        <div class="w-full relative">
            <label class="mb-2 text-sm">
                <span>Link video</span>
                <span class="materi-label"></span>
                <sup class="text-red-500 pl-1">&#42;</sup>
            </label>
            <input type="url" name="link_video[]"
                class="w-full bg-gray-100 outline-none rounded-xl text-xs p-3 cursor-pointer mt-2 mb-2" placeholder="Masukkan link video materi" value="">
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
