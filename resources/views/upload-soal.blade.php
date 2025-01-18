@include('components/sidebar_beranda')
@extends('components/sidebar_beranda_mobile')
@if (isset($user))
    @if ($user->status === 'Administrator')
        <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
            <div class="content-beranda">
                <div class="bg-white shadow-lg rounded-lg w-full h-auto border-[1px] border-gray-200">
                    <div class="pt-6 px-5">
                        <h1>Tipe Upload</h1>
                        <input type="radio" name="tipeUpload" id="soal" checked>
                        <label for="soal" class="radioList">
                            <a>Soal</a>
                        </label>
                        <input type="radio" name="tipeUpload" id="bulkUpload">
                        <label for="bulkUpload" class="radioList">
                            <a>Bulk Upload</a>
                        </label>
                    </div>
                    <form action="{{ route('englishZone.uploadSoal') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="flex w-full" id="editor-container"
                            data-upload-url="{{ route('englishZone.uploadImage', ['_token' => csrf_token()]) }}"
                            data-delete-url="{{ route('englishZone.deleteImage') }}">

                            <input type="hidden" name="nama_lengkap" value="{{ $user->nama_lengkap }}">
                            <input type="hidden" name="status" value="{{ $user->status }}">
                            <input type="hidden" name="tipe_upload" value="Soal">
                            <div class="mx-6 my-4 w-full text-sm">
                                <div class="flex gap-6">
                                    <div class="w-full">
                                        <span class="mb-2">
                                            Modul
                                            <sup class="text-red-500 pl-1">&#42;</sup>
                                        </span>
                                        <select name="modul_soal"
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
                                            <option value="Modul 5"
                                                {{ @old('modul') === 'Modul 5' ? 'selected' : '' }}>
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
                                                {{ @old('modul') === 'Modul 9' ? 'selected' : '' }}>
                                                Modul 9
                                            </option>
                                            <option value="Modul 10"
                                                {{ @old('modul') === 'Modul 10' ? 'selected' : '' }}>
                                                Modul 10
                                            </option>
                                            <option value="Modul 11"
                                                {{ @old('modul') === 'Modul 11' ? 'selected' : '' }}>
                                                Modul 11
                                            </option>
                                            <option value="Modul 12"
                                                {{ @old('modul') === 'Modul 12' ? 'selected' : '' }}>
                                                Modul 12
                                            </option>
                                        </select>
                                        @error('modul_soal')
                                            <span class="text-red-500 font-bold text-sm pl-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="w-full">
                                        <span class="mb-2">
                                            Jenjang
                                            <sup class="text-red-500 pl-1">&#42;</sup>
                                        </span>
                                        <select name="jenjang"
                                            class="w-full bg-gray-100 outline-none rounded-xl text-xs p-3 cursor-pointer mt-2 mb-2 {{ $errors->has('jenjang_murid') ? 'border-[1px] border-red-500' : '' }}">
                                            <option value="" class="hidden">Pilih Jenjang</option>
                                            <option value="SD" {{ @old('jenjang') === 'SD' ? 'selected' : '' }}>
                                                SD
                                            </option>
                                            <option value="SMP" {{ @old('jenjang') === 'SMP' ? 'selected' : '' }}>
                                                SMP</option>
                                            <option value="SMA" {{ @old('jenjang') === 'SMA' ? 'selected' : '' }}>
                                                SMA</option>
                                            <option value="Guru" {{ @old('jenjang') === 'Guru' ? 'selected' : '' }}>
                                                Guru</option>
                                        </select>
                                        @error('jenjang')
                                            <span class="text-red-500 font-bold text-sm pl-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="leading-10">
                                    <span>
                                        Soal
                                        <sup class="text-red-500 pl-1">&#42;</sup>
                                    </span>
                                    <textarea name="soal" class="editor"></textarea>
                                </div>
                                <div class="gap-8 mt-10">
                                    <div class="flex gap-6">
                                        <div class="jawaban mb-8 w-full">
                                            <span>
                                                Jawaban A
                                                <sup class="text-red-500 pl-1">&#42;</sup>
                                            </span>
                                            <textarea name="jawaban_pilihan[]" id="" class="editor"></textarea>
                                            <input type="hidden" name="option_pilihan[]" value="A">
                                        </div>
                                        <div class="w-full">
                                            <span>
                                                Jawaban Benar
                                                <sup class="text-red-500 pl-1">&#42;</sup>
                                            </span>
                                            <select name="jawaban_benar" id="jawaban_benar"
                                                class="w-full bg-gray-100 outline-none rounded-xl text-xs p-3 cursor-pointer mt-2 mb-2 {{ $errors->has('jawaban_benar') ? 'border-[1px] border-red-500' : '' }}">
                                                {{-- option add in javascript --}}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="flex gap-6">
                                        <div class="jawaban mb-8 w-full">
                                            <span>
                                                Jawaban B
                                                <sup class="text-red-500 pl-1">&#42;</sup>
                                                <input type="hidden" name="option_pilihan[]" value="B">
                                            </span>
                                            <textarea name="jawaban_pilihan[]" id="" class="editor"></textarea>
                                        </div>
                                        <div class="w-full">
                                            <span>
                                                Tigkat Kesulitan
                                                <sup class="text-red-500 pl-1">&#42;</sup>
                                            </span>
                                            <select name="tingkat_kesulitan"
                                                class="w-full bg-gray-100 outline-none rounded-xl text-xs p-3 cursor-pointer mt-2 mb-2 {{ $errors->has('jawaban_benar') ? 'border-[1px] border-red-500' : '' }}">
                                                <option class="hidden">Pilih tingkat kesulitan</option>
                                                <option>Mudah</option>
                                                <option>Sedang</option>
                                                <option>Sulit</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="flex gap-6">
                                        <div class="jawaban mb-8 w-full">
                                            <span>
                                                Jawaban C
                                                <sup class="text-red-500 pl-1">&#42;</sup>
                                            </span>
                                            <textarea name="jawaban_pilihan[]" id="" class="editor"></textarea>
                                            <input type="hidden" name="option_pilihan[]" value="C">
                                        </div>

                                        <div class="w-full">
                                            <span>
                                                Deskripsi Jawaban
                                                <sup class="text-red-500 pl-1">&#42;</sup>
                                            </span>
                                            <textarea name="deskripsi_jawaban" id="" class="editor"></textarea>
                                        </div>
                                    </div>
                                    <div id="jawaban-container" class="w-2/4">
                                        <div class="jawaban">
                                        </div>
                                    </div>
                                    <div class="flex justify-between">
                                        <!-- Tombol Tambah Jawaban -->
                                        <button id="tambah-jawaban" type="button"
                                            class="bg-[#4189e0] w-[200px] h-8 text-white font-bold rounded-lg mt-8 text-sm">
                                            <i class="fas fa-plus"></i>
                                            <span>Tambah Materi</span>
                                        </button>
                                        <button
                                            class="bg-red-500 w-[150px] h-8 text-white font-bold rounded-lg mt-8 text-sm">Kirim</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="flex w-full" id="editor-container"
                            data-upload-url="{{ route('englishZone.uploadImage', ['_token' => csrf_token()]) }}"
                            data-delete-url="{{ route('englishZone.deleteImage', ['_token' => csrf_token()]) }}">
                            <input type="text" class="border-2" name="title">
                            <textarea name="description" id="editor" cols="60" rows="60"></textarea>
                            <button type="submit">Kirim</button>
                        </div> --}}
                    </form>
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


<script>
    document.getElementById('tambah-jawaban').addEventListener('click', function() {
        const jawabanContainer = document.getElementById('jawaban-container');
        const totalJawaban = jawabanContainer.children.length;

        // Tentukan abjad berdasarkan jumlah elemen yang ada sekarang
        const abjadCode = 68 + totalJawaban; // 68 adalah kode untuk "D"

        // Batasi sampai abjad "H" (kode ASCII 72)
        if (abjadCode > 70) { // Jika abjadCode melebihi kode untuk "H" (72)
            document.getElementById('tambah-jawaban').style.display = 'none'; // Menyembunyikan tombol
            return; // Tidak menambahkan elemen baru jika sudah mencapai batas
        }

        const abjad = String.fromCharCode(abjadCode);

        // Buat elemen baru untuk jawaban
        const jawabanItem = document.createElement('div');
        jawabanItem.classList.add('jawaban-item');
        jawabanItem.innerHTML = `
        <div class="w-[98.5%] mb-6">
            <div class="flex justify-between">
                <div>
                    <span>Jawaban ${abjad}</span>
                    <sup class="text-red-500 pl-1">&#42;</sup>
                </div>
                <div class="text-red-500">
                    <i class="fas fa-trash"></i>
                    <button type="button" class="hapus-jawaban">Hapus</button>
                </div>
            </div>
            <textarea name="jawaban_pilihan[]" class="editor"></textarea>
            <input type="hidden" name="option_pilihan[]" value="${abjad}">
        </div>
    `;

        // Tambahkan elemen ke dalam container
        jawabanContainer.appendChild(jawabanItem);

        // Inisialisasi CKEditor 5 untuk textarea baru
        const newTextarea = jawabanItem.querySelector('.editor');
        if (!newTextarea.classList.contains('ckeditor-initialized')) {
            ClassicEditor.create(newTextarea).then(editor => {
                newTextarea.classList.add('ckeditor-initialized');
            }).catch(error => {
                console.error('Terjadi error saat inisialisasi CKEditor:', error);
            });
        }

        // Tambahkan event listener untuk tombol hapus
        jawabanItem.querySelector('.hapus-jawaban').addEventListener('click', function() {
            jawabanItem.remove();
            updateAbjad(); // Memperbarui urutan abjad setelah elemen dihapus
        });

        updateAbjad(); // Memperbarui urutan abjad setelah elemen ditambahkan
    });

    // Fungsi untuk memperbarui opsi dalam <select>
    function updateSelectOptions() {
        const jawabanContainer = document.getElementById('jawaban-container');
        const jawabanItems = jawabanContainer.querySelectorAll('.jawaban-item');

        const select = document.getElementById('jawaban_benar');
        select.innerHTML = `
        <option class="hidden">Pilih jawaban benar</option>
        <option>A</option>
        <option>B</option>
        <option>C</option>
    `;

        jawabanItems.forEach((item, index) => {
            const abjad = String.fromCharCode(68 + index); // 68 adalah kode untuk "D"
            const option = document.createElement('option');
            option.value = abjad;
            option.textContent = abjad;
            select.appendChild(option);
        });

        // Sembunyikan tombol "Tambah Jawaban" jika jumlah jawaban sudah mencapai 5
        if (jawabanItems.length >= 5) {
            document.getElementById('tambah-jawaban').style.display = 'none';
        } else {
            document.getElementById('tambah-jawaban').style.display = 'inline-block';
        }
    }

    // Fungsi untuk memperbarui abjad setelah elemen dihapus atau ditambahkan
    function updateAbjad() {
        const jawabanItems = document.querySelectorAll('.jawaban-item');
        jawabanItems.forEach((item, index) => {
            const abjad = String.fromCharCode(68 + index); // 68 adalah kode untuk "D"
            item.querySelector('span').textContent = `Jawaban ${abjad}`;
            item.querySelector('input[name="option_pilihan[]"]').value = abjad;
        });

        // Perbarui opsi dalam <select>
        updateSelectOptions();
    }

    // Inisialisasi abjad pada halaman dimuat pertama kali
    updateAbjad();
</script>

<script src="js/ckeditor.js"></script> {{-- script ckeditor untuk menampilkan dan mendelete gambar diserver setelah user menghapus gambar di editor --}}
