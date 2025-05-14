@include('components/sidebar_beranda', ['headerSideNav' => 'Upload Soal'])
@extends('components/sidebar_beranda_mobile')

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            @if (session('success-insert-soal-englishZone'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success-insert-soal-englishZone'),
                ])
            @endif
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
                        <input type="hidden" name="tipe_upload" value="Soal">
                        <div class="mx-6 my-4 w-full text-sm">
                            <div class="grid grid-cols-12 gap-6">
                                <div class="col-span-12 md:col-span-6">
                                    <span class="mb-2">
                                        Modul
                                        <sup class="text-red-500 pl-1">&#42;</sup>
                                    </span>
                                    <select name="modul_soal"
                                        class="w-full bg-white shadow-lg h-12 text-sm border-gray-200 border-[2px] outline-none rounded-md px-2
                                        focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] mt-2 mb-2 cursor-pointer {{ $errors->has('modul_soal') ? 'border-[1px] border-red-400' : '' }}">
                                        <option value="" class="hidden">Pilih Modul</option>
                                        <option value="Modul 1"
                                            {{ @old('modul_soal') === 'Modul 1' ? 'selected' : '' }}>Modul 1
                                        </option>
                                        <option value="Modul 2"
                                            {{ @old('modul_soal') === 'Modul 2' ? 'selected' : '' }}>Modul 2
                                        </option>
                                        <option value="Modul 3"
                                            {{ @old('modul_soal') === 'Modul 3' ? 'selected' : '' }}>Modul 3
                                        </option>
                                        <option value="Modul 4"
                                            {{ @old('modul_soal') === 'Modul 4' ? 'selected' : '' }}>Modul 4
                                        </option>
                                        <option value="Modul 5"
                                            {{ @old('modul_soal') === 'Modul 5' ? 'selected' : '' }}>Modul 5
                                        </option>
                                        <option value="Modul 6"
                                            {{ @old('modul_soal') === 'Modul 6' ? 'selected' : '' }}>Modul 6
                                        </option>
                                        <option value="Modul 7"
                                            {{ @old('modul_soal') === 'Modul 7' ? 'selected' : '' }}>Modul 7
                                        </option>
                                        <option value="Modul 8"
                                            {{ @old('modul_soal') === 'Modul 8' ? 'selected' : '' }}>Modul 8
                                        </option>
                                        <option value="Modul 9"
                                            {{ @old('modul_soal') === 'Modul 9' ? 'selected' : '' }}>Modul 9
                                        </option>
                                        <option value="Modul 10"
                                            {{ @old('modul_soal') === 'Modul 10' ? 'selected' : '' }}>Modul 10
                                        </option>
                                        <option value="Modul 11"
                                            {{ @old('modul_soal') === 'Modul 11' ? 'selected' : '' }}>Modul 11
                                        </option>
                                        <option
                                            value="Modul 12"{{ @old('modul_soal') === 'Modul 12' ? 'selected' : '' }}>
                                            Modul 12</option>
                                        <option value="Final Exam"
                                            {{ @old('modul_soal') === 'Final Exam' ? 'selected' : '' }}>Final Exam
                                        </option>
                                    </select>
                                    @error('modul_soal')
                                        <span class="text-red-500 font-bold text-sm pl-2">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-span-12 md:col-span-6 mb-4">
                                    <span class="mb-2">
                                        Jenjang
                                        <sup class="text-red-500 pl-1">&#42;</sup>
                                    </span>
                                    <select name="jenjang_soal"
                                        class="w-full bg-white shadow-lg h-12 text-sm border-gray-200 border-[2px] outline-none rounded-md px-2
                                                    focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] mt-2 mb-2 cursor-pointer {{ $errors->has('jenjang_soal') ? 'border-[1px] border-red-400' : '' }}">
                                        <option value="" class="hidden">Pilih Jenjang</option>
                                        <option value="Beginner"
                                            {{ @old('jenjang_soal') === 'Beginner' ? 'selected' : '' }}>
                                            Beginner
                                        </option>
                                        <option value="Intermediate"
                                            {{ @old('jenjang_soal') === 'Intermediate' ? 'selected' : '' }}>
                                            Intermediate
                                        </option>
                                        <option value="Advanced"
                                            {{ @old('jenjang_soal') === 'Advanced' ? 'selected' : '' }}>
                                            Advanced
                                        </option>
                                        <option value="SD" {{ @old('jenjang_soal') === 'SD' ? 'selected' : '' }}>
                                            SD
                                        </option>
                                        <option value="SMP" {{ @old('jenjang_soal') === 'SMP' ? 'selected' : '' }}>
                                            SMP</option>
                                        <option value="SMA" {{ @old('jenjang_soal') === 'SMA' ? 'selected' : '' }}>
                                            SMA</option>
                                        <option value="Guru" {{ @old('jenjang_soal') === 'Guru' ? 'selected' : '' }}>
                                            Guru</option>
                                    </select>
                                    @error('jenjang_soal')
                                        <span class="text-red-500 font-bold text-sm pl-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <!---- Responsive mobile  ----->
                            <div class="grid grid-cols-12 gap-6 md:hidden">
                                <div class="col-span-12">
                                    <span class="mb-2">
                                        Tingkat Kesulitan
                                        <sup class="text-red-500 pl-1">&#42;</sup>
                                    </span>
                                    <select name="tingkat_kesulitan"
                                        class="w-full bg-white shadow-lg h-12 text-sm border-gray-200 border-[2px] outline-none rounded-md px-2
                                                    focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] mt-2 mb-2 cursor-pointer {{ $errors->has('tingkat_kesulitan') ? 'border-[1px] border-red-400' : '' }}">
                                        <option value="" class="hidden">Pilih Jenjang</option>
                                        <option class="hidden">Pilih tingkat kesulitan</option>
                                        <option value="Mudah"
                                            {{ old('tingkat_kesulitan') == 'Mudah' ? 'selected' : '' }}>Mudah
                                        </option>
                                        <option value="Sedang"
                                            {{ old('tingkat_kesulitan') == 'Sedang' ? 'selected' : '' }}>Sedang
                                        </option>
                                        <option value="Sulit"
                                            {{ old('tingkat_kesulitan') == 'Sulit' ? 'selected' : '' }}>Sulit
                                        </option>
                                    </select>
                                    @error('tingkat_kesulitan')
                                        <span class="text-red-500 font-bold text-sm pl-2">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-span-12">
                                    <span>
                                        Jawaban Benar
                                        <sup class="text-red-500 pl-1">&#42;</sup>
                                    </span>
                                    <select name="jawaban_benar" id="jawaban_benar_mobile"
                                        class="w-full bg-white shadow-lg h-12 text-sm border-gray-200 border-[2px] outline-none rounded-md px-2
                                        focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] mt-2 mb-2 cursor-pointer {{ $errors->has('jawaban_benar') ? 'border-[1px] border-red-400' : '' }}">
                                        <option value="" class="hidden">Pilih jawaban benar</option>
                                        {{-- option add in javascript --}}
                                    </select>
                                    @error('jawaban_benar')
                                        <span class="text-red-500 font-bold text-sm pl-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="leading-10 mb-6">
                                <span>
                                    Soal
                                    <sup class="text-red-500 pl-1">&#42;</sup>
                                </span>
                                <textarea name="soal" id="soal" class="editor">{{ @old('soal') }}</textarea>
                                @error('soal')
                                    <span class="text-red-500 font-bold text-sm pl-2">{{ $message }}</span>
                                @enderror
                            </div>

                            <!--- Reponsive Mobile  ---->
                            <div class="w-full md:hidden mb-8">
                                <span>
                                    Deskripsi Jawaban
                                    <sup class="text-red-500 pl-1">&#42;</sup>
                                </span>
                                <textarea name="deskripsi_jawaban" id="deskripsi_jawaban_mobile" class="editor">{{ @old('deskripsi_jawaban') }}</textarea>
                                @error('deskripsi_jawaban')
                                    <span class="text-red-500 font-bold text-sm pl-2">{{ $message }}</span>
                                @enderror
                            </div>

                            <!----  Responsive Dekstop  ---->
                            <div class="hidden md:block gap-8 mt-10">
                                <div class="flex gap-6">
                                    <div class="jawaban mb-8 w-full">
                                        <span>
                                            Jawaban A
                                            <sup class="text-red-500 pl-1">&#42;</sup>
                                        </span>
                                        <textarea name="jawaban_pilihan[]" id="jawaban_A" class="editor"></textarea>
                                        <input type="hidden" name="option_pilihan[]" value="A">
                                        @error('jawaban_pilihan.*')
                                            <span class="text-red-500 font-bold text-sm pl-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="w-full">
                                        <span>
                                            Jawaban Benar
                                            <sup class="text-red-500 pl-1">&#42;</sup>
                                        </span>
                                        <select name="jawaban_benar" id="jawaban_benar_dekstop"
                                            class="w-full bg-white shadow-lg h-12 text-sm border-gray-200 border-[2px] outline-none rounded-md px-2
                                                    focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] mt-2 mb-2 cursor-pointer {{ $errors->has('jawaban_benar') ? 'border-[1px] border-red-400' : '' }}">
                                            <option value=""class="hidden">Pilih jawaban benar</option>
                                            {{-- option add in javascript --}}
                                        </select>
                                        @error('jawaban_benar')
                                            <span class="text-red-500 font-bold text-sm pl-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="flex gap-6">
                                    <div class="jawaban mb-8 w-full">
                                        <span>
                                            Jawaban B
                                            <sup class="text-red-500 pl-1">&#42;</sup>
                                            <input type="hidden" name="option_pilihan[]" value="B">
                                        </span>
                                        <textarea name="jawaban_pilihan[]" id="jawaban_B" class="editor"></textarea>
                                        @error('jawaban_pilihan.*')
                                            <span class="text-red-500 font-bold text-sm pl-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="w-full">
                                        <span class="mb-2">
                                            Tingkat Kesulitan
                                            <sup class="text-red-500 pl-1">&#42;</sup>
                                        </span>
                                        <select name="tingkat_kesulitan"
                                            class="w-full bg-white shadow-lg h-12 text-sm border-gray-200 border-[2px] outline-none rounded-md px-2
                                                    focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] mt-2 mb-2 cursor-pointer {{ $errors->has('tingkat_kesulitan') ? 'border-[1px] border-red-400' : '' }}">
                                            <option value="" class="hidden">Pilih Jenjang</option>
                                            <option class="hidden">Pilih tingkat kesulitan</option>
                                            <option value="Mudah"
                                                {{ old('tingkat_kesulitan') == 'Mudah' ? 'selected' : '' }}>Mudah
                                            </option>
                                            <option value="Sedang"
                                                {{ old('tingkat_kesulitan') == 'Sedang' ? 'selected' : '' }}>Sedang
                                            </option>
                                            <option value="Sulit"
                                                {{ old('tingkat_kesulitan') == 'Sulit' ? 'selected' : '' }}>Sulit
                                            </option>
                                        </select>
                                        @error('tingkat_kesulitan')
                                            <span class="text-red-500 font-bold text-sm pl-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="flex gap-6">
                                    <div class="jawaban mb-8 w-full">
                                        <span>
                                            Jawaban C
                                            <sup class="text-red-500 pl-1">&#42;</sup>
                                        </span>
                                        <textarea name="jawaban_pilihan[]" id="jawaban_C" class="editor"></textarea>
                                        <input type="hidden" name="option_pilihan[]" value="C">
                                        @error('jawaban_pilihan.*')
                                            <span class="text-red-500 font-bold text-sm pl-2">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="w-full">
                                        <span>
                                            Deskripsi Jawaban
                                            <sup class="text-red-500 pl-1">&#42;</sup>
                                        </span>
                                        <textarea name="deskripsi_jawaban" id="deskripsi_jawaban_dekstop" class="editor">{{ @old('deskripsi_jawaban') }}</textarea>
                                        @error('deskripsi_jawaban')
                                            <span class="text-red-500 font-bold text-sm pl-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <!--- Reponsive phone   ---->
                            {{-- <div class="sm:grid grid-cols-12 gap-6 md:hidden">
                                    <div class="jawaban mb-8 col-span-12">
                                        <span>
                                            Jawaban A
                                            <sup class="text-red-500 pl-1">&#42;</sup>
                                        </span>
                                        <textarea name="jawaban_pilihan[]" id="" class="editor"></textarea>
                                        <input type="hidden" name="option_pilihan[]" value="A">
                                        @error('jawaban_pilihan.*')
                                            <span class="text-red-500 font-bold text-sm pl-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="jawaban mb-8 col-span-12">
                                        <span>
                                            Jawaban B
                                            <sup class="text-red-500 pl-1">&#42;</sup>
                                            <input type="hidden" name="option_pilihan[]" value="B">
                                        </span>
                                        <textarea name="jawaban_pilihan[]" id="" class="editor"></textarea>
                                        @error('jawaban_pilihan.*')
                                            <span class="text-red-500 font-bold text-sm pl-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="jawaban mb-8 col-span-12">
                                        <span>
                                            Jawaban C
                                            <sup class="text-red-500 pl-1">&#42;</sup>
                                        </span>
                                        <textarea name="jawaban_pilihan[]" id="" class="editor"></textarea>
                                        <input type="hidden" name="option_pilihan[]" value="C">
                                        @error('jawaban_pilihan.*')
                                            <span class="text-red-500 font-bold text-sm pl-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div> --}}
                            <div id="jawaban-container" class="w-full md:w-2/4">
                                <div class="jawaban"></div>
                            </div>
                            <!-- Tombol Tambah Jawaban (mobile & dekstop) -->
                            <button id="tambah-jawaban" type="button"
                                class="bg-[#4189e0] w-full md:w-[200px] h-8 text-white font-bold rounded-lg mt-8 text-sm">
                                <i class="fas fa-plus"></i>
                                <span>Tambah Jawaban</span>
                            </button>
                            <div class="w-full flex justify-end">
                                <button
                                    class="bg-[#4189e0] w-[150px] h-8 text-white font-bold rounded-lg mt-14 md:mt-8 text-sm">Kirim</button>
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

<script src="js/ckeditor.js"></script> {{-- script ckeditor untuk menampilkan dan mendelete gambar diserver setelah user menghapus gambar di editor --}}


<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Untuk input & select biasa (bukan ckeditor)
        document.querySelectorAll('input, select').forEach(function(el) {
            el.addEventListener('input', function() {
                el.classList.remove('border-red-400');
                const wrapper = el.closest('.jawaban') || el.parentNode;
                const errorText = wrapper.querySelector('.text-red-500');
                if (errorText) {
                    errorText.remove();
                }
            });
        });
    });
</script>

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
            ClassicEditor.create(newTextarea, {
                toolbar: {
                    shouldNotGroupWhenFull: true // Pastikan toolbar tetap terlihat
                }
            }).then(editor => {
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

        // select option for mobile & dekstop
        const selectMobile = document.getElementById('jawaban_benar_mobile');
        selectMobile.innerHTML = `
        <option value="" class="hidden">Pilih jawaban benar</option>
        <option>A</option>
        <option>B</option>
        <option>C</option>
    `;

        const selectDekstop = document.getElementById('jawaban_benar_dekstop');
        selectDekstop.innerHTML = `
        <option value=""class="hidden">Pilih jawaban benar</option>
        <option>A</option>
        <option>B</option>
        <option>C</option>
    `;

        jawabanItems.forEach((item, index) => {
            const abjad = String.fromCharCode(68 + index); // 68 adalah kode untuk "D"

            // foreach option for mobile & dekstop
            const optionMobile = document.createElement('option');
            optionMobile.value = abjad;
            optionMobile.textContent = abjad;
            selectMobile.appendChild(optionMobile);

            const optionDekstop = document.createElement('option');
            optionDekstop.value = abjad;
            optionDekstop.textContent = abjad;
            selectDekstop.appendChild(optionDekstop);
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
